<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\ExecutiveInsights\Actions;

use Illuminate\Support\Facades\DB;
use Liberu\Modules\Liberu\ExecutiveInsights\Models\MetricRecord;

final class RegisterMetric
{
    public function execute(string $teamId, string $key, array $definition, array $lineage = []): MetricRecord
    {
        return DB::transaction(fn (): MetricRecord => MetricRecord::query()->updateOrCreate(
            ['team_id' => $teamId, 'key' => $key],
            ['status' => 'active', 'definition' => $definition, 'lineage' => $lineage, 'snapshot' => null, 'fresh_at' => now()],
        ));
    }
}
