<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\RevenueAndCareOrchestration\Api;

use Illuminate\Support\ServiceProvider;

final class RevenueAndCareOrchestrationApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
