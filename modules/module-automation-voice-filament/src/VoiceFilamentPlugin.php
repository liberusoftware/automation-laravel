<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource;

final class VoiceFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-voice-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([VoiceResource::class]);
    }

    public function boot(Panel $panel): void {}
}
