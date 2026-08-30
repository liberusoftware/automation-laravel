<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Livewire;

use Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Models\WorkflowRun;
use Livewire\Component;

final class RunList extends Component
{
    public string $search = '';

    public function render(): mixed
    {
        $teamId = auth()->user()?->currentTeam?->getKey();

        return view('module-liberu-business-workflow-reconciliation-livewire::list', ['records' => WorkflowRun::query()->forTeam($teamId)->when($this->search !== '', fn ($q) => $q->where('workflow', 'like', '%'.$this->search.'%'))->latest()->limit(25)->get()]);
    }
}
