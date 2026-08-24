<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\ExecutiveInsights\Api;

use Illuminate\Support\ServiceProvider;

final class ExecutiveInsightsApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
