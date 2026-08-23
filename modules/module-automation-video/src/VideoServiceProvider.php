<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video;

use Illuminate\Support\ServiceProvider;

final class VideoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/video.php', 'video');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
