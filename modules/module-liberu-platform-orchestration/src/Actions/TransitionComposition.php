<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration\Actions;

use Illuminate\Validation\ValidationException;
use Liberu\Modules\Liberu\PlatformOrchestration\Models\CompositionRecord;

final class TransitionComposition
{
    /** @var array<string, list<string>> */
    private const TRANSITIONS = [
        'registered' => ['enabled', 'disabled'],
        'enabled' => ['entitled', 'disabled', 'upgrading'],
        'entitled' => ['active', 'disabled', 'upgrading'],
        'active' => ['disabled', 'upgrading'],
        'upgrading' => ['active', 'disabled'],
        'disabled' => ['enabled'],
    ];

    public function execute(CompositionRecord $record, string $status, array $evidence = []): CompositionRecord
    {
        $allowed = self::TRANSITIONS[$record->status] ?? [];
        if (! in_array($status, $allowed, true)) {
            throw ValidationException::withMessages(['status' => "Cannot transition from {$record->status} to {$status}."]);
        }

        $record->update(['status' => $status, 'evidence' => [...($record->evidence ?? []), ...$evidence]]);

        return $record->refresh();
    }
}
