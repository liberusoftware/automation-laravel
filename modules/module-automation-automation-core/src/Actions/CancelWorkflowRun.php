<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\Modules\Automation\AutomationCore\Models\WorkflowRunRecord;

final class CancelWorkflowRun
{
    public function execute(WorkflowRunRecord $run, string $teamId): WorkflowRunRecord
    {
        if ($run->team_id !== $teamId || ! in_array($run->status, ['queued', 'running'], true)) {
            throw new InvalidArgumentException('Only an active run in the current team can be cancelled.');
        }

        DB::transaction(fn () => $run->forceFill(['status' => 'cancelled'])->save());

        return $run->refresh();
    }
}
