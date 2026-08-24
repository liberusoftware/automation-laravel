<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Domain;

use InvalidArgumentException;

final readonly class Cursor
{
    public function __construct(public string $value)
    {
        if ($value === '' || strlen($value) > 1024) {
            throw new InvalidArgumentException('Sync cursors must be non-empty and bounded.');
        }
    }

    public function next(string $value): self
    {
        return new self($value);
    }
}
