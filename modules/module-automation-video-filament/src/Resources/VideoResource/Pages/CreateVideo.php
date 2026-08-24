<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Filament\Resources\VideoResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\Video\Filament\Resources\VideoResource;

final class CreateVideo extends CreateRecord
{
    protected static string $resource = VideoResource::class;
}
