<div>
    <form wire:submit="save">
        <label for="connectors-name">Name</label>
        <input id="connectors-name" type="text" wire:model="name" required>
        <label for="connectors-status">Status</label>
        <select id="connectors-status" wire:model="status">
            <option value="draft">Draft</option>
            <option value="active">Active</option>
            <option value="paused">Paused</option>
            <option value="completed">Completed</option>
            <option value="failed">Failed</option>
            <option value="cancelled">Cancelled</option>
            <option value="published">Published</option>
        </select>
        <label for="connectors-payload">Payload (JSON)</label>
        <textarea id="connectors-payload" wire:model="payload"></textarea>
        <button type="submit">Save</button>
    </form>

    <input type="search" wire:model.live="search" placeholder="Search">
    <ul>
        @forelse ($resources as $resource)
            <li wire:key="{{ $resource->getKey() }}">
                {{ $resource->name }} <span>{{ $resource->status }}</span>
                <button type="button" wire:click="edit('{{ $resource->getKey() }}')">Edit</button>
                <button type="button" wire:click="delete('{{ $resource->getKey() }}')">Delete</button>
            </li>
        @empty
            <li>No resources found.</li>
        @endforelse
    </ul>
</div>
