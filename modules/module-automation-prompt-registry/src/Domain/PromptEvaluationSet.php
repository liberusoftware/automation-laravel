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
    }
}
