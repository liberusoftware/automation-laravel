<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\Modules\Automation\Evaluation\Models\EvaluationResource;

final class CreateEvaluationResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null, ?string $actorId = null, ?string $correlationId = null): EvaluationResource
    {
        $name = trim($name);
        if ($teamId === '' || $name === '') {
            throw new InvalidArgumentException('A team and a non-empty resource name are required.');
        }
        $idempotencyKey = $idempotencyKey === null ? null : trim($idempotencyKey);
        $idempotencyKey = $idempotencyKey === '' ? null : $idempotencyKey;

        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey, $actorId, $correlationId): EvaluationResource {
            if ($idempotencyKey !== null) {
                $existing = EvaluationResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return EvaluationResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
                'actor_id' => $actorId, 'correlation_id' => $correlationId,
            ]);
        });
    }
}
