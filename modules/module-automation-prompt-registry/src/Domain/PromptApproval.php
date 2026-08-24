<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Domain;

use InvalidArgumentException;

final readonly class PromptApproval
{
    private function __construct(public string $key, public int $version, public string $approverId) {}

    public static function approve(string $key, int $version, string $approverId, string $authorId): self
    {
        if ($key === '' || $version < 1 || $approverId === '' || $approverId === $authorId) {
            throw new InvalidArgumentException('Prompt approval requires a distinct authorized approver.');
        }

        return new self($key, $version, $approverId);
    }
}
