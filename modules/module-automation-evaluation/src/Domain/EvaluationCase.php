<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Domain;

use InvalidArgumentException;

final readonly class EvaluationCase
{
    /** @param array<string, mixed> $input */
    public function __construct(public string $id, public array $input, public string $expected)
    {
        if ($id === '' || $input === [] || trim($expected) === '') {
            throw new InvalidArgumentException('Evaluation cases require an identifier, input, and expected result.');
        }
    }
}
