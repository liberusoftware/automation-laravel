<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Domain;

use InvalidArgumentException;

final readonly class Evidence
{
    public function __construct(public string $type, public string $reference, public string $hash)
    {
        if ($type === '' || $reference === '' || ! preg_match('/^[a-f0-9]{64}$/', $hash)) {
            throw new InvalidArgumentException('Evidence requires a type, reference, and SHA-256 hash.');
        }
    }
}
