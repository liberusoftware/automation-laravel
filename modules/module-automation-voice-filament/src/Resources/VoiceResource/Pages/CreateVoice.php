<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource;

final class CreateVoice extends CreateRecord
{
    protected static string $resource = VoiceResource::class;
}
