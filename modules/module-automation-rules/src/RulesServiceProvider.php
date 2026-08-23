<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules;

use Illuminate\Support\ServiceProvider;

final class RulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/rules.php', 'rules');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
