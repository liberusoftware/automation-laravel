<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class AutomationCoreLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-automation-core::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-automation-core-livewire');
    }
}
