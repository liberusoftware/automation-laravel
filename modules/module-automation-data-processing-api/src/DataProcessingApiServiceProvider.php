<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Api;

use Illuminate\Support\ServiceProvider;

final class DataProcessingApiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
    }
}
