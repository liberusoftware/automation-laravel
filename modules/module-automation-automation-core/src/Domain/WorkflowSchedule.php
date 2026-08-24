<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Domain;

use InvalidArgumentException;

final readonly class WorkflowSchedule
{
    private function __construct(public string $expression, public string $timezone, public bool $enabled) {}

    /** @param array<string, mixed> $attributes */
    public static function fromArray(array $attributes): self
    {
        $expression = trim((string) ($attributes['expression'] ?? ''));
        $timezone = trim((string) ($attributes['timezone'] ?? 'UTC'));
        if ($expression === '' || mb_strlen($expression) > 120 || ! in_array($timezone, timezone_identifiers_list(), true)) {
            throw new InvalidArgumentException('A valid schedule expression and timezone are required.');
        }

        return new self($expression, $timezone, (bool) ($attributes['enabled'] ?? true));
    }

    /** @return array<string, string|bool> */
    public function toArray(): array
    {
        return ['expression' => $this->expression, 'timezone' => $this->timezone, 'enabled' => $this->enabled];
    }
}
