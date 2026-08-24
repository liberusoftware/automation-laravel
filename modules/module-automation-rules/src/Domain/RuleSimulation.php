<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Domain;

final readonly class RuleSimulation
{
    /** @param array<string, mixed> $context @param list<string> $outcomes */
    public function __construct(public bool $matched, public array $outcomes, public array $context) {}

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return ['matched' => $this->matched, 'outcomes' => $this->outcomes, 'context' => $this->context];
    }
}
