<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\Video\Models\VideoResource;

final class CreateVideoResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): VideoResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): VideoResource {
            if ($idempotencyKey !== null) {
                $existing = VideoResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return VideoResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
