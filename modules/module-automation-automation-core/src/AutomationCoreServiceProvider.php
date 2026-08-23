<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore;

use Illuminate\Support\ServiceProvider;

final class AutomationCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/automation-core.php', 'automation-core');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
