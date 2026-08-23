<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\Rules\Models\RulesResource;

final class CreateRulesResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): RulesResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): RulesResource {
            if ($idempotencyKey !== null) {
                $existing = RulesResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return RulesResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
