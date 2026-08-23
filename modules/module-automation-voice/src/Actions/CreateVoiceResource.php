<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\Voice\Models\VoiceResource;

final class CreateVoiceResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): VoiceResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): VoiceResource {
            if ($idempotencyKey !== null) {
                $existing = VoiceResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return VoiceResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
