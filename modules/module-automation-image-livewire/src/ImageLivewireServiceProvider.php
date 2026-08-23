<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class ImageLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-image::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-image-livewire');
    }
}
