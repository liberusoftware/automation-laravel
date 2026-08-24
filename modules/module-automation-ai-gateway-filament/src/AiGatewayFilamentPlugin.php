<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\AiGateway\Filament\Resources\AiGatewayResource;

final class AiGatewayFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-ai-gateway-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([AiGatewayResource::class]);
    }

    public function boot(Panel $panel): void {}
}
