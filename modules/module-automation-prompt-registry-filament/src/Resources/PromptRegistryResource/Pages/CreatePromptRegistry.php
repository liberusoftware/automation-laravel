<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Filament\Resources\PromptRegistryResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\PromptRegistry\Filament\Resources\PromptRegistryResource;

final class CreatePromptRegistry extends CreateRecord
{
    protected static string $resource = PromptRegistryResource::class;
}
