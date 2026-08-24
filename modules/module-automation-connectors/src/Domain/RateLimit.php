<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Domain;

use InvalidArgumentException;

final class RateLimit
{
    private int $used = 0;

    public function __construct(public readonly int $limit, public readonly int $windowSeconds)
    {
        if ($limit < 1 || $windowSeconds < 1) {
            throw new InvalidArgumentException('Connector rate limits require positive bounds.');
        }
    }

    public function consume(int $amount = 1): bool
    {
        if ($amount < 1 || $this->used + $amount > $this->limit) {
            return false;
        }
        $this->used += $amount;

        return true;
    }

    public function remaining(): int
    {
        return $this->limit - $this->used;
    }
}
