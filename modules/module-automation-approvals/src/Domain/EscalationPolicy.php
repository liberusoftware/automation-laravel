<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Domain;

use InvalidArgumentException;

final readonly class EscalationPolicy
{
    /** @param list<string> $authorizedActors */
    public function __construct(public int $afterSeconds, public array $authorizedActors)
    {
        if ($afterSeconds < 1 || $authorizedActors === [] || array_filter($authorizedActors, static fn (mixed $actor): bool => ! is_string($actor) || trim($actor) === '') !== []) {
            throw new InvalidArgumentException('Escalation requires a positive delay and authorized actors.');
        }
    }
}
