<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Domain;

use InvalidArgumentException;

final readonly class RedactionPolicy
{
    /** @param list<string> $patterns */
    public function __construct(public array $patterns, public string $replacement = '[REDACTED]')
    {
        if ($patterns === [] || count($patterns) > 100 || $replacement === '' || strlen($replacement) > 1000 || array_filter($patterns, static fn (mixed $pattern): bool => ! is_string($pattern) || strlen($pattern) > 1000 || @preg_match($pattern, '') === false) !== []) {
            throw new InvalidArgumentException('Redaction policies require valid patterns and a replacement.');
        }
    }

    public function apply(string $input): string
    {
        foreach ($this->patterns as $pattern) {
            $input = (string) preg_replace($pattern, $this->replacement, $input);
        }

        return $input;
    }
}
