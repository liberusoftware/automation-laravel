<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class RulesLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-rules::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-rules-livewire');
    }
}
