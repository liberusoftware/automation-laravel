<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors;

use Illuminate\Support\ServiceProvider;

final class ConnectorsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/connectors.php', 'connectors');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
