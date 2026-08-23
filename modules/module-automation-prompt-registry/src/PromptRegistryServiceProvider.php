<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry;

use Illuminate\Support\ServiceProvider;

final class PromptRegistryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/prompt-registry.php', 'prompt-registry');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
