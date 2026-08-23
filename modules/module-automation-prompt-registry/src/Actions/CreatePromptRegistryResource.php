<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\PromptRegistry\Models\PromptRegistryResource;

final class CreatePromptRegistryResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): PromptRegistryResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): PromptRegistryResource {
            if ($idempotencyKey !== null) {
                $existing = PromptRegistryResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return PromptRegistryResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
