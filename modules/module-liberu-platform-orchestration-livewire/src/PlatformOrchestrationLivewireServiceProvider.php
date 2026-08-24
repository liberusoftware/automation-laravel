<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\PlatformOrchestration\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class PlatformOrchestrationLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('liberu-platform-orchestration::compositions', CompositionList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-liberu-platform-orchestration-livewire');
    }
}
