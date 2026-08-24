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
        $panel->resources([ConnectorsResource::class]);
    }

    public function boot(Panel $panel): void {}
}
