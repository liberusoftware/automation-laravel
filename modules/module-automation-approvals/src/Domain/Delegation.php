<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Domain;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

final readonly class Delegation
{
    public function __construct(public string $delegateId, public CarbonImmutable $expiresAt)
    {
        if ($delegateId === '') {
            throw new InvalidArgumentException('A delegation requires a delegate.');
        }
    }
}
