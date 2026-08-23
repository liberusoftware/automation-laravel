<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class ApprovalsLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-approvals::resource-list', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-approvals-livewire');
    }
}
