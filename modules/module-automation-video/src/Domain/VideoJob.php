<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Domain;

use InvalidArgumentException;

final class VideoJob
{
    private string $status = 'queued';

    private int $attempts = 0;

    public function __construct(public readonly string $id, public readonly string $teamId, public readonly VideoRequest $request)
    {
        if ($id === '' || $teamId === '') {
            throw new InvalidArgumentException('Video jobs require identifiers and team context.');
        }
    }

    public function start(): void
    {
        $this->transition('running');
        $this->attempts++;
    }

    public function complete(): void
    {
        $this->transition('completed');
    }

    public function fail(): void
    {
        if ($this->status !== 'running') {
            throw new InvalidArgumentException('Only running video jobs can fail.');
        }
        $this->status = 'failed';
    }

    public function retry(int $maxAttempts = 3): void
    {
        if ($maxAttempts < 1 || $this->status !== 'failed' || $this->attempts >= $maxAttempts) {
            throw new InvalidArgumentException('This video job cannot be retried.');
        }
        $this->status = 'queued';
    }

    public function status(): string
    {
        return $this->status;
    }

    public function attempts(): int
    {
        return $this->attempts;
    }

    private function transition(string $status): void
    {
        $allowed = match ($this->status) {
            'queued' => ['running'], 'running' => ['completed', 'failed'], default => [],
        };
        if (! in_array($status, $allowed, true)) {
            throw new InvalidArgumentException('Video job transition is not allowed.');
        }
        $this->status = $status;
    }
}
