<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Filament\Resources\ApprovalsResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\Approvals\Filament\Resources\ApprovalsResource;

final class CreateApprovals extends CreateRecord
{
    protected static string $resource = ApprovalsResource::class;
}
