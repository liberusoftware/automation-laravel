<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Filament\Resources\ApprovalsResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Liberu\Modules\Automation\Approvals\Filament\Resources\ApprovalsResource;

final class EditApprovals extends EditRecord
{
    protected static string $resource = ApprovalsResource::class;
}
