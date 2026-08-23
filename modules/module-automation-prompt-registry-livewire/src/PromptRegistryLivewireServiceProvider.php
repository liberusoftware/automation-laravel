<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class PromptRegistryLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-prompt-registry::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-prompt-registry-livewire');
    }
}
