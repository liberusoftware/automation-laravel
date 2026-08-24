<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Actions;

use Illuminate\Validation\ValidationException;
use Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Models\WorkflowRun;

final class ReconcileWorkflowRun
{
    public function execute(WorkflowRun $run, string $status, array $event): WorkflowRun
    {
        if (! in_array($status, ['reconciled', 'attention', 'recovery'], true)) {
            throw ValidationException::withMessages(['status' => 'Unsupported reconciliation status.']);
        }
        $run->update(['status' => $status, 'events' => [...($run->events ?? []), $event]]);

        return $run->refresh();
    }
}
