<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Filament\Resources\ImageResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Liberu\Modules\Automation\Image\Filament\Resources\ImageResource;

final class EditImage extends EditRecord
{
    protected static string $resource = ImageResource::class;
}
