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
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.video.generation-jobs' => VideoResource::class,
            'automation.video.editing-jobs' => VideoResource::class,
            'automation.video.scripts' => VideoResource::class,
            'automation.video.captions' => VideoResource::class,
            'automation.video.audio' => VideoResource::class,
            'automation.video.moderation' => VideoResource::class,
            'automation.video.provenance' => VideoResource::class,
            'automation.video.delivery' => VideoResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
