<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation;

use Illuminate\Support\ServiceProvider;

final class EvaluationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/evaluation.php', 'evaluation');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
