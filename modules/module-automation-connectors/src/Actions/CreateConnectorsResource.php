<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\Connectors\Models\ConnectorsResource;

final class CreateConnectorsResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): ConnectorsResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): ConnectorsResource {
            if ($idempotencyKey !== null) {
                $existing = ConnectorsResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return ConnectorsResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
