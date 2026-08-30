<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Domain;

use InvalidArgumentException;

final class WorkflowRun
{
    private int $attempts = 0;

    private bool $cancellationRequested = false;

    private bool $lastFailureRetryable = true;

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

    public function complete(): void
    {
        $this->transitionTo('succeeded');
    }

    public function fail(bool $retryable = true): void
    {
        $this->transitionTo('failed');
        $this->lastFailureRetryable = $retryable;
    }

    public function retry(RetryPolicy $policy): int
    {
        if (! $this->canRetry($policy)) {
            throw new InvalidArgumentException('This workflow run cannot be retried.');
        }

        $this->status = 'queued';

        return $this->startAttempt();
    }

    public function startAttempt(): int
    {
        if ($this->status !== 'queued' && $this->status !== 'running') {
            throw new InvalidArgumentException('Only queued or running workflow runs can attempt work.');
        }
        $this->attempts++;
        $this->status = 'running';

        return $this->attempts;
    }

    public function attempts(): int
    {
        return $this->attempts;
    }

    public function canRetry(RetryPolicy $policy): bool
    {
        return $this->status === 'failed'
            && $this->attempts < $policy->maxAttempts
            && (! $policy->retryableFailuresOnly || $this->lastFailureRetryable);
    }

    public function requestCancellation(): void
    {
        if (in_array($this->status, ['succeeded', 'failed', 'cancelled'], true)) {
            throw new InvalidArgumentException('A completed workflow run cannot be cancelled.');
        }
        $this->cancellationRequested = true;
        $this->status = 'cancelled';
    }

    public function cancellationRequested(): bool
    {
        return $this->cancellationRequested;
    }
}
