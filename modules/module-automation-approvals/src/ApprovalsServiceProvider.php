<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals;

use Illuminate\Support\ServiceProvider;

final class ApprovalsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/approvals.php', 'approvals');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
