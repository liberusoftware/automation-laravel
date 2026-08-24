<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\Connectors\Filament\Resources\ConnectorsResource;

final class ConnectorsFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-connectors-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.connectors.authenticated-triggers-actions' => ConnectorsResource::class,
            'automation.connectors.webhooks' => ConnectorsResource::class,
            'automation.connectors.rate-limits' => ConnectorsResource::class,
            'automation.connectors.cursor-sync' => ConnectorsResource::class,
            'automation.connectors.replay' => ConnectorsResource::class,
            'automation.connectors.reconciliation' => ConnectorsResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
