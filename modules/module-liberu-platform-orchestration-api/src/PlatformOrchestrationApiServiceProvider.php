<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration\Api;

use Illuminate\Support\ServiceProvider;

final class PlatformOrchestrationApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
