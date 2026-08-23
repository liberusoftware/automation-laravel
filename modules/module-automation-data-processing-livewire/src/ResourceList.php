<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Livewire;

use Liberu\Modules\Automation\DataProcessing\Models\DataProcessingResource;
use Livewire\Component;

final class ResourceList extends Component
{
    public string $search = '';

    public function render(): mixed
    {
        $teamId = auth()->user()?->currentTeam?->getKey();
        $resources = $teamId === null ? collect() : DataProcessingResource::query()->forTeam((string) $teamId)->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))->latest()->limit(25)->get();

        return view('module-automation-data-processing-livewire::resource-list', ['resources' => $resources]);
    }
}
