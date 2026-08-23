<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class EvaluationLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-evaluation::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-evaluation-livewire');
    }
}
