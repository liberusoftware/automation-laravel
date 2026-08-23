<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Livewire;

use Liberu\Modules\Automation\Voice\Models\VoiceResource;
use Livewire\Component;

final class ResourceList extends Component
{
    public string $search = '';

    public function render(): mixed
    {
        $teamId = auth()->user()?->currentTeam?->getKey();
        $resources = $teamId === null ? collect() : VoiceResource::query()->forTeam((string) $teamId)->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))->latest()->limit(25)->get();

        return view('module-automation-voice-livewire::resource-list', ['resources' => $resources]);
    }
}
