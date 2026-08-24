<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Filament\Resources\ImageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\Image\Filament\Resources\ImageResource;

final class CreateImage extends CreateRecord
{
    protected static string $resource = ImageResource::class;
}
