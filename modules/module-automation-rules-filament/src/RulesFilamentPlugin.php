<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class RulesFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-rules-filament';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
