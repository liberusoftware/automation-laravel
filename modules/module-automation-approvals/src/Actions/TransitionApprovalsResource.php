<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\Modules\Automation\Approvals\Models\ApprovalsResource;

final class TransitionApprovalsResource
{
    /** @param list<string> $allowedStatuses */
    public function execute(ApprovalsResource $resource, string $teamId, string $status, array $allowedStatuses = ['draft', 'active', 'paused', 'completed', 'failed', 'cancelled']): ApprovalsResource
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
