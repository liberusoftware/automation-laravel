<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Domain;

use InvalidArgumentException;

final readonly class QualityGate
{
    public function __construct(public string $metric, public float $minimum, public float $maximum = 1.0)
    {
        if ($metric === '' || $minimum < 0 || $maximum > 1 || $minimum > $maximum) {
            throw new InvalidArgumentException('Quality gates require bounded metric thresholds.');
        }
    }

    public function passes(float $score): bool
    {
        return $score >= $this->minimum && $score <= $this->maximum;
    }
}
