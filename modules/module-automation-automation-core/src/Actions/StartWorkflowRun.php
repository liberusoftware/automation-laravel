<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowVariables;
use Liberu\Modules\Automation\AutomationCore\Models\AutomationCoreResource;
use Liberu\Modules\Automation\AutomationCore\Models\WorkflowRunRecord;
use Liberu\Modules\Automation\AutomationCore\Models\WorkflowVersion;

final class StartWorkflowRun
{
    /** @param array<string, mixed> $variables */
    public function execute(AutomationCoreResource $workflow, string $teamId, array $variables = [], ?string $idempotencyKey = null, ?string $actorId = null, ?string $correlationId = null): WorkflowRunRecord
    {
        if ($workflow->team_id !== $teamId || $workflow->status !== 'published') {
            throw new InvalidArgumentException('Only a published workflow in the active team can run.');
        }

        return DB::transaction(function () use ($workflow, $teamId, $variables, $idempotencyKey, $actorId, $correlationId): WorkflowRunRecord {
            if ($idempotencyKey !== null) {
                $existing = WorkflowRunRecord::query()->forTeam($teamId)->where('workflow_id', $workflow->getKey())->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            $version = WorkflowVersion::query()->forTeam($teamId)->where('workflow_id', $workflow->getKey())->latest('version')->first();
            if ($version === null) {
                throw new InvalidArgumentException('The published workflow has no version.');
            }
            $definition = (array) $version->definition;
            $validated = WorkflowVariables::validate($variables, (array) ($definition['input_schema'] ?? []));

            return WorkflowRunRecord::query()->create([
                'workflow_id' => $workflow->getKey(),
                'team_id' => $teamId,
                'version' => $version->version,
                'status' => 'queued',
                'variables' => $validated->values,
                'idempotency_key' => $idempotencyKey,
                'actor_id' => $actorId,
                'correlation_id' => $correlationId,
            ]);
        });
    }
}
