<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Filament\Resources\AutomationCoreResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\AutomationCore\Filament\Resources\AutomationCoreResource;

final class ListAutomationCore extends ListRecords
{
    protected static string $resource = AutomationCoreResource::class;
}
