<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Domain;

use InvalidArgumentException;

final readonly class PromptEvaluationSet
{
    /** @param list<array{input: array<string, scalar>, expected: string}> $cases */
    public function __construct(public string $name, public array $cases)
    {
        if ($name === '' || $cases === [] || count($cases) > 1000) {
            throw new InvalidArgumentException('Prompt evaluation sets require between 1 and 1000 cases.');
        }

        foreach ($cases as $case) {
            if (! is_array($case) || ! is_array($case['input'] ?? null) || $case['input'] === [] || ! is_string($case['expected'] ?? null) || trim($case['expected']) === '') {
                throw new InvalidArgumentException('Prompt evaluation cases require input values and an expected result.');
            }
            foreach ($case['input'] as $value) {
                if (! is_scalar($value) && $value !== null) {
                    throw new InvalidArgumentException('Prompt evaluation inputs must be scalar values.');
                }
            }
        }
    }
}
