<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\AutomationCore\Models\AutomationCoreResource;

final class CreateAutomationCoreResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): AutomationCoreResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): AutomationCoreResource {
            if ($idempotencyKey !== null) {
                $existing = AutomationCoreResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return AutomationCoreResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
