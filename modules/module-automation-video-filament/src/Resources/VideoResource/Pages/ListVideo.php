<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Filament\Resources\VideoResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\Video\Filament\Resources\VideoResource;

final class ListVideo extends ListRecords
{
    protected static string $resource = VideoResource::class;
}
