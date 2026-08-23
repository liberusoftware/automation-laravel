<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\Image\Models\ImageResource;

final class CreateImageResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): ImageResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): ImageResource {
            if ($idempotencyKey !== null) {
                $existing = ImageResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return ImageResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
