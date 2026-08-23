<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class DataProcessingLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-data-processing::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-data-processing-livewire');
    }
}
