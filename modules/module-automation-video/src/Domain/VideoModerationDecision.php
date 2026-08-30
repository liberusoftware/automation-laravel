<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Domain;

use InvalidArgumentException;

final readonly class VideoModerationDecision
{
    public function __construct(public string $status, public string $policyVersion, public ?string $reason = null)
    {
        if (! in_array($status, ['approved', 'rejected', 'review'], true) || trim($policyVersion) === '' || ($status !== 'approved' && trim((string) $reason) === '')) {
            throw new InvalidArgumentException('Video moderation requires a policy and reason for non-approved outcomes.');
        }
    }

    public function mayDeliver(): bool
    {
        return $this->status === 'approved';
    }
}
