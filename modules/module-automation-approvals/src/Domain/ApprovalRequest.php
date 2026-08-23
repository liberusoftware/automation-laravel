<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Domain;

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Liberu\Modules\Automation\Approvals\Enums\ApprovalDecision;

final readonly class ApprovalRequest
{
    public function __construct(
        public string $id,
        public string $teamId,
        public string $requesterId,
        public string $status,
        public ?CarbonImmutable $expiresAt = null,
    ) {}

    public function decide(string $actorId, ApprovalDecision $decision, CarbonImmutable $now): self
    {
        if ($actorId === $this->requesterId) {
            throw new InvalidArgumentException('Separation of duties prevents the requester from deciding this approval.');
        }

        if ($this->expiresAt !== null && $this->expiresAt->lessThanOrEqualTo($now)) {
            throw new InvalidArgumentException('This approval request has expired.');
        }

        if ($this->status !== 'pending') {
            throw new InvalidArgumentException('Only pending approval requests can be decided.');
        }

        return new self($this->id, $this->teamId, $this->requesterId, $decision->value, $this->expiresAt);
    }
}
