<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\Rules\Filament\Resources\RulesResource;

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

    public function register(Panel $panel): void
    {
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.rules.typed-conditions' => RulesResource::class,
            'automation.rules.expressions' => RulesResource::class,
            'automation.rules.validation' => RulesResource::class,
            'automation.rules.simulation' => RulesResource::class,
            'automation.rules.decision-tables' => RulesResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
