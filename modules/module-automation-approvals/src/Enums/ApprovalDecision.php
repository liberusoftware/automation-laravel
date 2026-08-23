<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Enums;

enum ApprovalDecision: string
{
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Returned = 'returned';
}
