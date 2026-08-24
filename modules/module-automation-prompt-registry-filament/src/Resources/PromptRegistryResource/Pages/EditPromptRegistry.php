<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Filament\Resources\PromptRegistryResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Liberu\Modules\Automation\PromptRegistry\Filament\Resources\PromptRegistryResource;

final class EditPromptRegistry extends EditRecord
{
    protected static string $resource = PromptRegistryResource::class;
}
