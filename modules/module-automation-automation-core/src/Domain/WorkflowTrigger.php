<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Domain;

use InvalidArgumentException;

final readonly class WorkflowTrigger
{
    public function __construct(public string $type, public string $event, public bool $enabled = true)
    {
        if (! in_array($type, ['event', 'schedule', 'webhook', 'manual'], true) || trim($event) === '') {
            throw new InvalidArgumentException('Workflow triggers require a supported type and event.');
        }
    }
}
