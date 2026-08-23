<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Api;

use Illuminate\Support\ServiceProvider;

final class AutomationCoreApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
