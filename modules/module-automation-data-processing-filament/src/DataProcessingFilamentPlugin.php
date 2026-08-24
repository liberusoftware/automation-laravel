<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\DataProcessing\Filament\Resources\DataProcessingResource;

final class DataProcessingFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-data-processing-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([DataProcessingResource::class]);
    }

    public function boot(Panel $panel): void {}
}
