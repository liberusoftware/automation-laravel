<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class ConnectorsLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-connectors::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-connectors-livewire');
    }
}
