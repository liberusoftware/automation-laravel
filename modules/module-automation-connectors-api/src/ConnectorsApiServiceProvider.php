<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Api;

use Illuminate\Support\ServiceProvider;

final class ConnectorsApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
