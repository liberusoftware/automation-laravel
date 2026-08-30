<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Liberu\Modules\Automation\Image\Events\ResourceTransitioned;
use Liberu\Modules\Automation\Image\Models\ImageResource;

final class TransitionImageResource
{
    /** @param list<string> $allowedStatuses */
    public function execute(ImageResource $resource, string $teamId, string $status, array $allowedStatuses = ['draft', 'active', 'paused', 'completed', 'failed', 'cancelled'], ?string $actorId = null, ?string $correlationId = null): ImageResource
    {
        if ($resource->team_id !== $teamId) {
            throw new InvalidArgumentException('The resource does not belong to the active team.');
        }
        if (! in_array($status, $allowedStatuses, true)) {
            throw new InvalidArgumentException('Unsupported resource status.');
        }
        $transitions = ['draft' => ['active', 'cancelled'], 'active' => ['paused', 'completed', 'failed', 'cancelled'], 'paused' => ['active', 'cancelled'], 'failed' => ['active', 'cancelled'], 'completed' => [], 'cancelled' => []];
        if (! in_array($status, $transitions[$resource->status] ?? [], true)) {
            throw new InvalidArgumentException("Resource cannot transition from [{$resource->status}] to [{$status}].");
        }
        $from = (string) $resource->status;
        DB::transaction(function () use ($resource, $teamId, $from, $status, $actorId, $correlationId): void {
            $resource->status = $status;
            $resource->lock_version++;
            $resource->save();
            ResourceTransitioned::dispatch(
                (string) $resource->getKey(),
                $teamId,
                $from,
                $status,
                $actorId,
                $correlationId,
            );
        });

        return $resource->refresh();
    }
}
