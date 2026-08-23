<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Domain;

use InvalidArgumentException;

final class WorkflowRun
{
    public function __construct(public readonly string $id, public readonly string $workflowId, private string $status = 'queued')
    {
        if ($id === '' || $workflowId === '' || ! in_array($status, ['queued', 'running', 'succeeded', 'failed', 'cancelled'], true)) {
            throw new InvalidArgumentException('Workflow runs require valid identifiers and status.');
        }
    }

    public function status(): string
    {
        return $this->status;
    }

    public function transitionTo(string $status): void
    {
        $allowed = match ($this->status) {
            'queued' => ['running', 'cancelled'],
            'running' => ['succeeded', 'failed', 'cancelled'],
            default => [],
        };

        if (! in_array($status, $allowed, true)) {
            throw new InvalidArgumentException('Workflow run transition is not allowed.');
        }

        $this->status = $status;
    }
}
