<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\ExecutiveInsights\Actions;

use Illuminate\Validation\ValidationException;
use Liberu\Modules\Liberu\ExecutiveInsights\Models\MetricRecord;

final class CaptureDecisionSnapshot
{
    public function execute(MetricRecord $metric, array $values, string $version): MetricRecord
    {
        if ($metric->status !== 'active') {
            throw ValidationException::withMessages(['metric' => 'Only active metrics may be snapshotted.']);
        }
        $metric->update(['status' => 'snapshotted', 'snapshot' => ['version' => $version, 'values' => $values, 'captured_at' => now()->toISOString()]]);

        return $metric->refresh();
    }
}
