<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Filament\Resources\DataProcessingResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\DataProcessing\Filament\Resources\DataProcessingResource;

final class CreateDataProcessing extends CreateRecord
{
    protected static string $resource = DataProcessingResource::class;
}
