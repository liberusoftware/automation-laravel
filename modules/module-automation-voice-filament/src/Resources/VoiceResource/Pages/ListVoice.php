<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource;

final class ListVoice extends ListRecords
{
    protected static string $resource = VoiceResource::class;
}
