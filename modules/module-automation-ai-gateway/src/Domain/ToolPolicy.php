<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Domain;

use InvalidArgumentException;

final readonly class ToolPolicy
{
    /** @param list<string> $allowedTools */
    public function __construct(public array $allowedTools, public bool $requireConfirmation = true, public int $maxCalls = 1)
    {
        if ($maxCalls < 0 || $maxCalls > 100 || array_filter($allowedTools, static fn (mixed $tool): bool => ! is_string($tool) || trim($tool) === '') !== []) {
            throw new InvalidArgumentException('Tool policies require valid tool names and a bounded call limit.');
        }
    }

    public function allows(string $tool, int $callsMade = 0): bool
    {
        return in_array($tool, $this->allowedTools, true) && $callsMade < $this->maxCalls;
    }
}
