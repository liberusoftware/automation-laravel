<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\AiGateway\Models\AiGatewayResource;

final class CreateAiGatewayResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): AiGatewayResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): AiGatewayResource {
            if ($idempotencyKey !== null) {
                $existing = AiGatewayResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return AiGatewayResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
