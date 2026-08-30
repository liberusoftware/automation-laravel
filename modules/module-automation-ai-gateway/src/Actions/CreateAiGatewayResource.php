<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\Modules\Automation\AiGateway\Models\AiGatewayResource;

final class CreateAiGatewayResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null, ?string $actorId = null, ?string $correlationId = null): AiGatewayResource
    {
        $name = trim($name);
        if ($teamId === '' || $name === '') {
            throw new InvalidArgumentException('A team and a non-empty resource name are required.');
        }
        $idempotencyKey = $idempotencyKey === null ? null : trim($idempotencyKey);
        $idempotencyKey = $idempotencyKey === '' ? null : $idempotencyKey;

        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey, $actorId, $correlationId): AiGatewayResource {
            if ($idempotencyKey !== null) {
                $existing = AiGatewayResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return AiGatewayResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
                'actor_id' => $actorId, 'correlation_id' => $correlationId,
            ]);
        });
    }
}
