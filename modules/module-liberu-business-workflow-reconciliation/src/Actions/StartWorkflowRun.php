<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Models\WorkflowRun;

final class StartWorkflowRun
{
    public function execute(string $teamId, string $workflow, string $correlationId, array $steps = []): WorkflowRun
    {
        return DB::transaction(fn (): WorkflowRun => WorkflowRun::query()->firstOrCreate(['team_id' => $teamId, 'correlation_id' => $correlationId], ['workflow' => $workflow, 'status' => 'running', 'steps' => $steps, 'events' => [], 'recovery' => []]));
    }
}
