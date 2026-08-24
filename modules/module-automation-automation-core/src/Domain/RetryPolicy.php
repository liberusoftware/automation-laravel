<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Domain;

use InvalidArgumentException;

final readonly class RetryPolicy
{
    public function __construct(
        public int $maxAttempts = 1,
        public int $backoffSeconds = 0,
        public bool $retryableFailuresOnly = true,
    ) {
        if ($maxAttempts < 1 || $maxAttempts > 20 || $backoffSeconds < 0 || $backoffSeconds > 86400) {
            throw new InvalidArgumentException('Retry policy limits are invalid.');
        }
    }

    /** @param array<string, mixed> $attributes */
    public static function fromArray(array $attributes): self
    {
        return new self(
            maxAttempts: (int) ($attributes['max_attempts'] ?? 1),
            backoffSeconds: (int) ($attributes['backoff_seconds'] ?? 0),
            retryableFailuresOnly: (bool) ($attributes['retryable_failures_only'] ?? true),
        );
    }

    /** @return array<string, int|bool> */
    public function toArray(): array
    {
        return ['max_attempts' => $this->maxAttempts, 'backoff_seconds' => $this->backoffSeconds, 'retryable_failures_only' => $this->retryableFailuresOnly];
    }
}
