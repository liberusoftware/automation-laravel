<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\Image\Filament\Resources\ImageResource;

final class ImageFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-image-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.image.generation-requests' => ImageResource::class,
            'automation.image.editing-requests' => ImageResource::class,
            'automation.image.source-assets' => ImageResource::class,
            'automation.image.moderation' => ImageResource::class,
            'automation.image.provenance' => ImageResource::class,
            'automation.image.variants' => ImageResource::class,
            'automation.image.delivery' => ImageResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
