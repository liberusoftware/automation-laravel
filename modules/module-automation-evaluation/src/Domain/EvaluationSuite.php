<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Domain;

use InvalidArgumentException;

final readonly class EvaluationSuite
{
    /** @param list<EvaluationCase> $cases @param list<QualityGate> $gates */
    public function __construct(public string $name, public array $cases, public array $gates = [])
    {
        if ($name === '' || $cases === [] || count($cases) > 1000) {
            throw new InvalidArgumentException('Evaluation suites require between 1 and 1000 cases.');
        }
    }

    /** @param array<string, float> $scores */
    public function passes(array $scores): bool
    {
        foreach ($this->gates as $gate) {
            if (! array_key_exists($gate->metric, $scores) || ! $gate->passes($scores[$gate->metric])) {
                return false;
            }
        }

        return true;
    }
}
