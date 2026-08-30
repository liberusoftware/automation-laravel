<div>
    <form wire:submit="save">
        <label for="prompt-registry-name">Name</label>
        <input id="prompt-registry-name" type="text" wire:model="name" required>
        <label for="prompt-registry-status">Status</label>
        <input id="prompt-registry-status" type="text" wire:model="status" readonly aria-readonly="true">
        <label for="prompt-registry-payload">Payload (JSON)</label>
        <textarea id="prompt-registry-payload" wire:model="payload"></textarea>
        <button type="submit">Save</button>
    </form>

    <input type="search" wire:model.live="search" placeholder="Search">
    <ul>
        @forelse ($resources as $resource)
            <li wire:key="{{ $resource->getKey() }}">
                {{ $resource->name }} <span>{{ $resource->status }}</span>
                @if ($resource->status === 'draft')
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'active')">Activate</button>
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'cancelled')">Cancel</button>
                @elseif ($resource->status === 'active')
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'paused')">Pause</button>
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'completed')">Complete</button>
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'failed')">Fail</button>
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'cancelled')">Cancel</button>
                @elseif ($resource->status === 'paused')
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'active')">Resume</button>
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'cancelled')">Cancel</button>
                @elseif ($resource->status === 'failed')
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'active')">Retry</button>
                    <button type="button" wire:click="transition('{{ $resource->getKey() }}', 'cancelled')">Cancel</button>
                @endif
                <button type="button" wire:click="edit('{{ $resource->getKey() }}')">Edit</button>
                <button type="button" wire:click="delete('{{ $resource->getKey() }}')">Delete</button>
            </li>
        @empty
            <li>No resources found.</li>
        @endforelse
    </ul>
</div>
