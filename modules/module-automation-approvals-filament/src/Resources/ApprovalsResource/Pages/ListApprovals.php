<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Filament\Resources\ApprovalsResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\Approvals\Filament\Resources\ApprovalsResource;

final class ListApprovals extends ListRecords
{
    protected static string $resource = ApprovalsResource::class;
}
