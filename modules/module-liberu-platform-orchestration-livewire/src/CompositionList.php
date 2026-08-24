<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration\Livewire;

use Liberu\Modules\Liberu\PlatformOrchestration\Models\CompositionRecord;
use Livewire\Component;

final class CompositionList extends Component
{
    public string $search = '';

    public function render(): mixed
    {
        $teamId = auth()->user()?->currentTeam?->getKey();

        return view('module-liberu-platform-orchestration-livewire::list', ['records' => CompositionRecord::query()->forTeam($teamId)->when($this->search !== '', fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))->latest()->limit(25)->get()]);
    }
}
