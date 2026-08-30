<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\RevenueAndCareOrchestration\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class RevenueAndCareOrchestrationLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('liberu-revenue-care::signals', SignalList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-liberu-revenue-and-care-orchestration-livewire');
    }
}
