<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class PlatformOrchestrationFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-liberu-platform-orchestration-filament';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
