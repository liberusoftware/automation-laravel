<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Domain;

use InvalidArgumentException;

final readonly class ModerationDecision
{
    public function __construct(public string $status, public string $policyVersion, public ?string $reason = null)
    {
        if (! in_array($status, ['approved', 'rejected', 'review'], true) || $policyVersion === '' || ($status !== 'approved' && trim((string) $reason) === '')) {
            throw new InvalidArgumentException('Moderation decisions require a policy and reason for non-approved outcomes.');
        }
    }

    public function mayDeliver(): bool
    {
        return $this->status === 'approved';
    }
}
