<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\AutomationCore\Filament\Resources\AutomationCoreResource;

final class AutomationCoreFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-automation-core-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([AutomationCoreResource::class]);
    }

    public function boot(Panel $panel): void {}
}
