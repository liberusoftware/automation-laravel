<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Filament\Resources\RulesResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\Rules\Filament\Resources\RulesResource;

final class CreateRules extends CreateRecord
{
    protected static string $resource = RulesResource::class;
}
