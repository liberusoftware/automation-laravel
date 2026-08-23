<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\DataProcessing\Models\DataProcessingResource;

final class CreateDataProcessingResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): DataProcessingResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): DataProcessingResource {
            if ($idempotencyKey !== null) {
                $existing = DataProcessingResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return DataProcessingResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
