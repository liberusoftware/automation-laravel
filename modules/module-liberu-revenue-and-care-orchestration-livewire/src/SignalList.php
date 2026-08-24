<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\RevenueAndCareOrchestration\Livewire;

use Liberu\Modules\Liberu\RevenueAndCareOrchestration\Models\HealthSignal;
use Livewire\Component;

final class SignalList extends Component
{
    public string $search = '';

    public function render(): mixed
    {
        $teamId = auth()->user()?->currentTeam?->getKey();

        return view('module-liberu-revenue-and-care-orchestration-livewire::list', ['records' => HealthSignal::query()->forTeam($teamId)->fresh()->when($this->search !== '', fn ($q) => $q->where('kind', 'like', '%'.$this->search.'%'))->latest()->limit(25)->get()]);
    }
}
