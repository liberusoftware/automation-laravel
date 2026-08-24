<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Domain;

use InvalidArgumentException;

final readonly class PromptOverride
{
    public function __construct(public string $key, public string $teamId, public ?string $brand, public PromptVersion $version, public int $priority = 0)
    {
        if ($key === '' || $teamId === '' || $version->key !== $key || $priority < 0) {
            throw new InvalidArgumentException('Prompt overrides require matching key, team, version, and priority.');
        }
    }

    public function appliesTo(string $teamId, ?string $brand): bool
    {
        return $this->teamId === $teamId && ($this->brand === null || $this->brand === $brand);
    }
}
