<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Liberu\PlatformOrchestration\Models\CompositionRecord;

final class RegisterComposition
{
    public function execute(string $teamId, string $name, array $manifest, ?string $idempotencyKey = null): CompositionRecord
    {
        return DB::transaction(function () use ($teamId, $name, $manifest, $idempotencyKey): CompositionRecord {
            if ($idempotencyKey !== null) {
                $existing = CompositionRecord::query()->forTeam($teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return CompositionRecord::query()->create([
                'team_id' => $teamId,
                'name' => $name,
                'status' => 'registered',
                'manifest' => $manifest,
                'capabilities' => $manifest['capabilities'] ?? [],
                'evidence' => [],
                'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
