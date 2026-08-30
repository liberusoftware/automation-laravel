<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Domain;

use InvalidArgumentException;

final class RateLimit
{
    private int $used = 0;

    private ?int $windowStartedAt = null;

    public function __construct(public readonly int $limit, public readonly int $windowSeconds)
    {
        if ($limit < 1 || $windowSeconds < 1) {
            throw new InvalidArgumentException('Connector rate limits require positive bounds.');
        }
    }

    public function consume(int $amount = 1, ?int $now = null): bool
    {
        if ($amount < 1 || $amount > $this->limit) {
            return false;
        }

        $now ??= time();
        $this->resetIfWindowElapsed($now);

        if ($this->windowStartedAt === null) {
            $this->windowStartedAt = $now;
        }

        if ($this->used + $amount > $this->limit) {
            return false;
        }

        $this->used += $amount;

        return true;
    }

    public function remaining(?int $now = null): int
    {
        if ($now !== null) {
            $this->resetIfWindowElapsed($now);
        }

        return $this->limit - $this->used;
    }

    public function retryAfter(?int $now = null): int
    {
        if ($this->windowStartedAt === null) {
            return 0;
        }

        $now ??= time();

        return max(0, $this->windowStartedAt + $this->windowSeconds - $now);
    }

    private function resetIfWindowElapsed(int $now): void
    {
        if ($this->windowStartedAt !== null && $now >= $this->windowStartedAt + $this->windowSeconds) {
            $this->used = 0;
            $this->windowStartedAt = null;
        }
    }
}
