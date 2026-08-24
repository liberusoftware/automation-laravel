<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Domain;

use InvalidArgumentException;

final readonly class ReconciliationReport
{
    public function __construct(public int $received, public int $matched, public int $missing, public int $conflicting)
    {
        if (min($received, $matched, $missing, $conflicting) < 0 || $matched + $missing + $conflicting > $received) {
            throw new InvalidArgumentException('Reconciliation counts are inconsistent.');
        }
    }

    public function isHealthy(): bool
    {
        return $this->missing === 0 && $this->conflicting === 0;
    }
}
