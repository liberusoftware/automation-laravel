<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\RevenueAndCareOrchestration\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Liberu\RevenueAndCareOrchestration\Models\HealthSignal;

final class ObserveHealthSignal
{
    public function execute(string $teamId, string $customerId, string $kind, array $observation, array $evidence = [], ?string $expiresAt = null): HealthSignal
    {
        return DB::transaction(fn (): HealthSignal => HealthSignal::query()->create(['team_id' => $teamId, 'customer_id' => $customerId, 'kind' => $kind, 'status' => 'observed', 'observation' => $observation, 'evidence' => $evidence, 'consent' => ['checked_at' => now()->toISOString()], 'expires_at' => $expiresAt]));
    }
}
