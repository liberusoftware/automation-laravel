<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\Video\Filament\Resources\VideoResource;

final class VideoFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-video-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([VideoResource::class]);
    }

    public function boot(Panel $panel): void {}
}
