<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Livewire;

use Liberu\Modules\Automation\PromptRegistry\Models\PromptRegistryResource;
use Livewire\Component;

final class ResourceList extends Component
{
    public string $search = '';

    public function render(): mixed
    {
        $teamId = auth()->user()?->currentTeam?->getKey();
        $resources = $teamId === null ? collect() : PromptRegistryResource::query()->forTeam((string) $teamId)->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))->latest()->limit(25)->get();

        return view('module-automation-prompt-registry-livewire::resource-list', ['resources' => $resources]);
    }
}
