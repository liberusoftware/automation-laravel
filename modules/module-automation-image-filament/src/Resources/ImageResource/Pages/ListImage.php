<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Filament\Resources\ImageResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\Image\Filament\Resources\ImageResource;

final class ListImage extends ListRecords
{
    protected static string $resource = ImageResource::class;
}
