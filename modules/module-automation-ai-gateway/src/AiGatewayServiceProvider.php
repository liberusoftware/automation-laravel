<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway;

use Illuminate\Support\ServiceProvider;

final class AiGatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ai-gateway.php', 'ai-gateway');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
