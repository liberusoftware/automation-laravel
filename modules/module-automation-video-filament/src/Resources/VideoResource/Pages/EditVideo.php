<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Filament\Resources\VideoResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Liberu\Modules\Automation\Video\Filament\Resources\VideoResource;

final class EditVideo extends EditRecord
{
    protected static string $resource = VideoResource::class;
}
