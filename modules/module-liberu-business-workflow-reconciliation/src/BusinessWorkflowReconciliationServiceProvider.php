<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\BusinessWorkflowReconciliation;

use Illuminate\Support\ServiceProvider;

final class BusinessWorkflowReconciliationServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
