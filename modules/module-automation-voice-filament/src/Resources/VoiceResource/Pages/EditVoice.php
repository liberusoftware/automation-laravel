<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource;

final class EditVoice extends EditRecord
{
    protected static string $resource = VoiceResource::class;
}
