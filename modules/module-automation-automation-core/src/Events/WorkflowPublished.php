<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Events;

use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

final readonly class WorkflowPublished implements ShouldDispatchAfterCommit
{
    public function __construct(
        public string $workflowId,
        public string $teamId,
        public int $version,
    ) {}
}
