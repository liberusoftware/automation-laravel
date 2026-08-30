<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Domain;

use InvalidArgumentException;

final readonly class EvaluationSuite
{
    /** @param list<EvaluationCase> $cases @param list<QualityGate> $gates */
    public function __construct(public string $name, public array $cases, public array $gates = [])
    {
        if (trim($name) === '' || $cases === [] || count($cases) > 1000 || array_filter($cases, static fn (mixed $case): bool => ! $case instanceof EvaluationCase) !== [] || array_filter($gates, static fn (mixed $gate): bool => ! $gate instanceof QualityGate) !== []) {
            throw new InvalidArgumentException('Evaluation suites require between 1 and 1000 cases.');
        }
    }

    /** @param array<string, float> $scores */
    public function passes(array $scores): bool
    {
        foreach ($this->gates as $gate) {
            if (! array_key_exists($gate->metric, $scores) || ! is_float($scores[$gate->metric]) && ! is_int($scores[$gate->metric]) || ! is_finite((float) $scores[$gate->metric]) || ! $gate->passes((float) $scores[$gate->metric])) {
                return false;
            }
        }

        return true;
    }
}
