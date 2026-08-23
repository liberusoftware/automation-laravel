<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image;

use Illuminate\Support\ServiceProvider;

final class ImageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/image.php', 'image');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
