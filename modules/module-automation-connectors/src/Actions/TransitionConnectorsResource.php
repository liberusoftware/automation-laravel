<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\Modules\Automation\Connectors\Models\ConnectorsResource;

final class TransitionConnectorsResource
{
    /** @param list<string> $allowedStatuses */
    public function execute(ConnectorsResource $resource, string $teamId, string $status, array $allowedStatuses = ['draft', 'active', 'paused', 'completed', 'failed', 'cancelled']): ConnectorsResource
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
