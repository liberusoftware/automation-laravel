<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Filament\Resources\PromptRegistryResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\PromptRegistry\Filament\Resources\PromptRegistryResource;

final class ListPromptRegistry extends ListRecords
{
    protected static string $resource = PromptRegistryResource::class;
}
