<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Livewire;

use Liberu\Modules\Automation\Video\Models\VideoResource;
use Livewire\Component;

final class ResourceList extends Component
{
    public string $search = '';

    public function render(): mixed
    {
        $teamId = auth()->user()?->currentTeam?->getKey();
        $resources = $teamId === null ? collect() : VideoResource::query()->forTeam((string) $teamId)->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))->latest()->limit(25)->get();

        return view('module-automation-video-livewire::resource-list', ['resources' => $resources]);
    }
}
