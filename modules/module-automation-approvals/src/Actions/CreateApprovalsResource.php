<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Automation\Approvals\Models\ApprovalsResource;

final class CreateApprovalsResource
{
    public function execute(string $teamId, string $name, array $payload = [], ?string $idempotencyKey = null): ApprovalsResource
    {
        return DB::transaction(function () use ($teamId, $name, $payload, $idempotencyKey): ApprovalsResource {
            if ($idempotencyKey !== null) {
                $existing = ApprovalsResource::query()->where('team_id', $teamId)->where('idempotency_key', $idempotencyKey)->first();
                if ($existing !== null) {
                    return $existing;
                }
            }

            return ApprovalsResource::query()->create([
                'team_id' => $teamId, 'name' => $name, 'status' => 'draft',
                'payload' => $payload, 'idempotency_key' => $idempotencyKey,
            ]);
        });
    }
}
