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
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.ai-gateway.provider-contracts' => AiGatewayResource::class,
            'automation.ai-gateway.model-catalog' => AiGatewayResource::class,
            'automation.ai-gateway.routing' => AiGatewayResource::class,
            'automation.ai-gateway.fallback' => AiGatewayResource::class,
            'automation.ai-gateway.structured-output' => AiGatewayResource::class,
            'automation.ai-gateway.tool-policy' => AiGatewayResource::class,
            'automation.ai-gateway.usage-metering' => AiGatewayResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
