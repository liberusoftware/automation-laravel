<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Liberu\Modules\Automation\Approvals\Domain\ApprovalRequest;
use Liberu\Modules\Automation\Approvals\Domain\Delegation;
use Liberu\Modules\Automation\Approvals\Enums\ApprovalDecision;

it('prevents requester self-approval', function (): void {
    $request = new ApprovalRequest('approval-1', 'team-1', 'requester-1', 'pending', CarbonImmutable::tomorrow());

    expect(fn () => $request->decide('requester-1', ApprovalDecision::Approved, CarbonImmutable::now()))
        ->toThrow(InvalidArgumentException::class);
});

it('preserves delegation authority and expires overdue requests', function (): void {
    $now = CarbonImmutable::parse('2026-08-30T00:00:00Z');
    $request = new ApprovalRequest('approval-1', 'team-1', 'requester-1', 'pending', $now->addHour());
    $delegated = $request->delegate('requester-1', new Delegation('reviewer-1', $now->addHour()), $now);

    expect($delegated->delegateId)->toBe('reviewer-1')
        ->and($delegated->decide('reviewer-1', ApprovalDecision::Approved, $now)->status)->toBe('approved');
    expect(fn () => $delegated->decide('reviewer-2', ApprovalDecision::Approved, $now))
        ->toThrow(InvalidArgumentException::class);

    $overdue = new ApprovalRequest('approval-2', 'team-1', 'requester-1', 'pending', $now->subMinute());
    expect($overdue->expire($now)->status)->toBe('expired');
});
