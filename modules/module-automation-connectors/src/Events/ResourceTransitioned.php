<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class ResourceTransitioned implements ShouldDispatchAfterCommit
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly string $resourceId,
        public readonly string $teamId,
        public readonly string $from,
        public readonly string $to,
        public readonly ?string $actorId = null,
        public readonly ?string $correlationId = null,
    ) {}
}
