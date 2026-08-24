<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Filament\Resources\DataProcessingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\DataProcessing\Filament\Resources\DataProcessingResource;

final class ListDataProcessing extends ListRecords
{
    protected static string $resource = DataProcessingResource::class;
}
