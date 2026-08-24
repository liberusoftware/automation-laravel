<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Filament\Resources\RulesResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\Rules\Filament\Resources\RulesResource;

final class ListRules extends ListRecords
{
    protected static string $resource = RulesResource::class;
}
