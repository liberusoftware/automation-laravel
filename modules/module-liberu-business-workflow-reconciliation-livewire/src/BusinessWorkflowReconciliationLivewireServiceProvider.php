<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class BusinessWorkflowReconciliationLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('liberu-workflow-reconciliation::runs', RunList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-liberu-business-workflow-reconciliation-livewire');
    }
}
