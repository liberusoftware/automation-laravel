<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Filament\Resources\DataProcessingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Liberu\Modules\Automation\DataProcessing\Filament\Resources\DataProcessingResource;

final class EditDataProcessing extends EditRecord
{
    protected static string $resource = DataProcessingResource::class;
}
