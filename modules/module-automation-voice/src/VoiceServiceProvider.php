<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice;

use Illuminate\Support\ServiceProvider;

final class VoiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/voice.php', 'voice');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
