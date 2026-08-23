<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing;

use Illuminate\Support\ServiceProvider;

final class DataProcessingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/data-processing.php', 'data-processing');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
