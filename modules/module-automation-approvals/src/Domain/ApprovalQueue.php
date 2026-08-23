<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Domain;

use InvalidArgumentException;

final readonly class ApprovalQueue
{
    /** @param list<ApprovalRequest> $requests */
    public function __construct(public string $teamId, public array $requests)
    {
        if ($teamId === '') {
            throw new InvalidArgumentException('Approval queues require a team identifier.');
        }

        foreach ($requests as $request) {
            if ($request->teamId !== $teamId) {
                throw new InvalidArgumentException('Approval queues cannot mix teams.');
            }
        }
    }

    /** @return list<ApprovalRequest> */
    public function pending(): array
    {
        return array_values(array_filter($this->requests, static fn (ApprovalRequest $request): bool => $request->isPending()));
    }
}
