<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\ExecutiveInsights\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class ExecutiveInsightsLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('liberu-executive-insights::metrics', MetricList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-liberu-executive-insights-livewire');
    }
}
