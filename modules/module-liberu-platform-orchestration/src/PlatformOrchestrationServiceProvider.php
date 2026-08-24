<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration;

use Illuminate\Support\ServiceProvider;

final class PlatformOrchestrationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
