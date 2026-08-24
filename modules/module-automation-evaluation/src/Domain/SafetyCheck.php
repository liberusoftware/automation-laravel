<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Domain;

use InvalidArgumentException;

final readonly class SafetyCheck
{
    /** @param list<string> $forbiddenPatterns */
    public function __construct(public string $name, public array $forbiddenPatterns)
    {
        if ($name === '' || $forbiddenPatterns === [] || array_filter($forbiddenPatterns, static fn (mixed $pattern): bool => ! is_string($pattern) || @preg_match($pattern, '') === false) !== []) {
            throw new InvalidArgumentException('Safety checks require valid forbidden patterns.');
        }
    }

    public function passes(string $output): bool
    {
        foreach ($this->forbiddenPatterns as $pattern) {
            if (preg_match($pattern, $output) === 1) {
                return false;
            }
        }

        return true;
    }
}
