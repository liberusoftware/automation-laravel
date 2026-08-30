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
        public ?string $delegateId = null,
    ) {
        if ($id === '' || $teamId === '' || $requesterId === '' || ! in_array($status, ['pending', 'approved', 'rejected', 'returned', 'expired', 'delegated', 'escalated'], true)) {
            throw new InvalidArgumentException('Approval requests require valid identifiers and lifecycle state.');
        }
        if ($delegateId === '') {
            throw new InvalidArgumentException('A delegated approval requires a delegate identifier.');
        }
    }

    public function decide(string $actorId, ApprovalDecision $decision, CarbonImmutable $now): self
    {
        $mayDecide = $this->status === 'pending'
            || ($this->status === 'delegated' && $actorId === $this->delegateId)
            || ($this->status === 'escalated' && $actorId !== $this->requesterId);
        if (! $mayDecide) {
            throw new InvalidArgumentException('Only an authorized reviewer may decide this approval.');
        }

        if ($actorId === $this->requesterId) {
            throw new InvalidArgumentException('Separation of duties prevents the requester from deciding this approval.');
        }

        if ($this->expiresAt !== null && $this->expiresAt->lessThanOrEqualTo($now)) {
            throw new InvalidArgumentException('This approval request has expired.');
        }

        return new self($this->id, $this->teamId, $this->requesterId, $decision->value, $this->expiresAt, $this->delegateId);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function delegate(string $actorId, Delegation $delegation, CarbonImmutable $now): self
    {
        if ($actorId !== $this->requesterId || ! $this->isPending() || $delegation->expiresAt->lessThanOrEqualTo($now)) {
            throw new InvalidArgumentException('Only the requester may delegate a pending, non-expired approval.');
        }

        return new self($this->id, $this->teamId, $this->requesterId, 'delegated', $this->expiresAt, $delegation->delegateId);
    }

    public function escalate(string $actorId, EscalationPolicy $policy, CarbonImmutable $now): self
    {
        if (! $this->isPending() || $this->expiresAt === null || $this->expiresAt->greaterThan($now->addSeconds($policy->afterSeconds))) {
            throw new InvalidArgumentException('This approval is not eligible for escalation.');
        }
        if (! in_array($actorId, $policy->authorizedActors, true)) {
            throw new InvalidArgumentException('The actor cannot escalate this approval.');
        }

        return new self($this->id, $this->teamId, $this->requesterId, 'escalated', $this->expiresAt, $this->delegateId);
    }

    public function expire(CarbonImmutable $now): self
    {
        if (! $this->isPending() || $this->expiresAt === null || $this->expiresAt->greaterThan($now)) {
            throw new InvalidArgumentException('Only an overdue pending approval can expire.');
        }

        return new self($this->id, $this->teamId, $this->requesterId, 'expired', $this->expiresAt, $this->delegateId);
    }
}
