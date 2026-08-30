<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\ExecutiveInsights\Livewire;

use Liberu\Modules\Liberu\ExecutiveInsights\Models\MetricRecord;
use Livewire\Component;

final class MetricList extends Component
{
    public string $search = '';

    public function render(): mixed
    {
        $teamId = auth()->user()?->currentTeam?->getKey();

        return view('module-liberu-executive-insights-livewire::list', ['records' => MetricRecord::query()->forTeam($teamId)->when($this->search !== '', fn ($q) => $q->where('key', 'like', '%'.$this->search.'%'))->latest()->limit(25)->get()]);
    }
}
