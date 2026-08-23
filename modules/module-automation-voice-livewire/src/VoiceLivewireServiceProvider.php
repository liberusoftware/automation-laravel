<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class VoiceLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-voice::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-voice-livewire');
    }
}
