<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class VideoLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-video::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-video-livewire');
    }
}
