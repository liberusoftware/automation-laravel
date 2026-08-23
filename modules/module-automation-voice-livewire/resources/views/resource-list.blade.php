<div>
    <label for="voice-search">Search</label>
    <input id="voice-search" type="search" wire:model.live="search">
    <ul>
        @forelse ($resources as $resource)
            <li wire:key="{{ $resource->getKey() }}">{{ $resource->name }} <span>{{ $resource->status }}</span></li>
        @empty
            <li>No resources found.</li>
        @endforelse
    </ul>
</div>

