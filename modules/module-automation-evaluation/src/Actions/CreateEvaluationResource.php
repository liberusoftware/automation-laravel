<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\Evaluation\Models\EvaluationResource;

final class CreateEvaluationResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): EvaluationResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): EvaluationResource {
            if ($idempotencyKey !== null) {
                $existing = EvaluationResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return EvaluationResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
