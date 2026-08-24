<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Domain;

use InvalidArgumentException;

final readonly class RegressionComparison
{
    public function __construct(public string $metric, public float $baseline, public float $candidate, public float $allowedDrop = 0.0)
    {
        if ($metric === '' || $allowedDrop < 0) {
            throw new InvalidArgumentException('Regression comparisons require a metric and non-negative tolerance.');
        }
    }

    public function passes(): bool
    {
        return $this->candidate >= $this->baseline - $this->allowedDrop;
    }

    public function delta(): float
    {
        return round($this->candidate - $this->baseline, 8);
    }
}
