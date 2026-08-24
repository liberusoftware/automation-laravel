<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Liberu\Modules\Automation\Approvals\Domain\ApprovalRequest;
use Liberu\Modules\Automation\Approvals\Enums\ApprovalDecision;

it('prevents requester self-approval', function (): void {
    $request = new ApprovalRequest('approval-1', 'team-1', 'requester-1', 'pending', CarbonImmutable::tomorrow());

    expect(fn () => $request->decide('requester-1', ApprovalDecision::Approved, CarbonImmutable::now()))
        ->toThrow(InvalidArgumentException::class);
});
