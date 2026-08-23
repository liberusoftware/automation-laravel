<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowDefinition;
use Liberu\Modules\Automation\AutomationCore\Events\WorkflowPublished;
use Liberu\Modules\Automation\AutomationCore\Models\AutomationCoreResource;
use Liberu\Modules\Automation\AutomationCore\Models\WorkflowVersion;
use RuntimeException;

final class PublishWorkflow
{
    public function execute(AutomationCoreResource $workflow, string $teamId, WorkflowDefinition $definition): WorkflowVersion
    {
        if ($workflow->team_id !== $teamId) {
            throw new RuntimeException('The workflow does not belong to the active team.');
        }

        return DB::transaction(function () use ($workflow, $definition, $teamId): WorkflowVersion {
            $version = (int) WorkflowVersion::query()
                ->where('workflow_id', $workflow->getKey())
                ->lockForUpdate()
                ->max('version') + 1;

            $published = WorkflowVersion::query()->create([
                'workflow_id' => $workflow->getKey(),
                'team_id' => $teamId,
                'version' => $version,
                'definition' => $definition->toArray(),
            ]);

            $workflow->forceFill(['status' => 'published', 'payload' => $definition->toArray()])->save();
            event(new WorkflowPublished((string) $workflow->getKey(), $teamId, $version));

            return $published;
        });
    }
}
