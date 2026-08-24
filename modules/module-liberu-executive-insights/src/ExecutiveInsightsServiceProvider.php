<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\ExecutiveInsights;

use Illuminate\Support\ServiceProvider;

final class ExecutiveInsightsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
