<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Filament\Resources\AutomationCoreResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\AutomationCore\Filament\Resources\AutomationCoreResource;

final class CreateAutomationCore extends CreateRecord
{
    protected static string $resource = AutomationCoreResource::class;
}
