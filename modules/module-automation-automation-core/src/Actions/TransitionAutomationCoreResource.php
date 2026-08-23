<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\Modules\Automation\AutomationCore\Models\AutomationCoreResource;

final class TransitionAutomationCoreResource
{
    /** @param list<string> $allowedStatuses */
    public function execute(AutomationCoreResource $resource, string $teamId, string $status, array $allowedStatuses = ['draft', 'active', 'paused', 'completed', 'failed', 'cancelled']): AutomationCoreResource
    {
        if ($resource->team_id !== $teamId) {
            throw new InvalidArgumentException('The resource does not belong to the active team.');
        }
        if (! in_array($status, $allowedStatuses, true)) {
            throw new InvalidArgumentException('Unsupported resource status.');
        }
        $resource->status = $status;
        DB::transaction(fn () => $resource->save());

        return $resource->refresh();
    }
}
