<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Livewire;

use Liberu\Modules\Automation\Approvals\Actions\TransitionApprovalsResource;
use Liberu\Modules\Automation\Approvals\Models\ApprovalsResource;
use Livewire\Component;

final class ResourceList extends Component
{
    public string $search = '';

    public string $name = '';

    public string $status = 'draft';

    public string $payload = '{}';

    public ?string $editingId = null;

    public function save(): void
    {
        $teamId = $this->teamId();
        abort_if($teamId === null, 403);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'payload' => ['nullable', 'json'],
        ]);

        $attributes = [
            'name' => $validated['name'],
            'payload' => $validated['payload'] === '' ? [] : json_decode($validated['payload'], true, 512, JSON_THROW_ON_ERROR),
        ];

        if ($this->editingId === null) {
            ApprovalsResource::query()->create(['team_id' => $teamId, ...$attributes]);
        } else {
            $this->query()->findOrFail($this->editingId)->update($attributes);
        }

        $this->resetForm();
    }

    public function edit(string $id): void
    {
        $record = $this->query()->findOrFail($id);
        $this->editingId = (string) $record->getKey();
        $this->name = (string) $record->name;
        $this->status = (string) $record->status;
        $this->payload = json_encode($record->payload ?? [], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    }

    public function delete(string $id): void
    {
        $this->query()->findOrFail($id)->delete();

        if ($this->editingId === $id) {
            $this->resetForm();
        }
    }

    public function transition(string $id, string $status): void
    {
        $teamId = $this->teamId();
        abort_if($teamId === null, 403);

        $record = $this->query()->findOrFail($id);
        app(TransitionApprovalsResource::class)->execute($record, $teamId, $status);
    }

    public function render(): mixed
    {
        return view('module-automation-approvals-livewire::resource-list', [
            'resources' => $this->query()
                ->when($this->search !== '', fn ($query) => $query->where('name', 'like', '%'.$this->search.'%'))
                ->latest()
                ->limit(25)
                ->get(),
        ]);
    }

    private function teamId(): ?string
    {
        $teamId = auth()->user()?->currentTeam?->getKey();

        return $teamId === null ? null : (string) $teamId;
    }

    private function query()
    {
        $teamId = $this->teamId();

        return ApprovalsResource::query()->when(
            $teamId === null,
            fn ($query) => $query->whereRaw('1 = 0'),
            fn ($query) => $query->forTeam($teamId),
        );
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'payload', 'editingId']);
        $this->status = 'draft';
    }
}
