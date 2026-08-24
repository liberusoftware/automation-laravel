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
        Livewire::component('module-automation-connectors::authenticated-triggers-actions', ResourceList::class);
        Livewire::component('module-automation-connectors::webhooks', ResourceList::class);
        Livewire::component('module-automation-connectors::rate-limits', ResourceList::class);
        Livewire::component('module-automation-connectors::cursor-sync', ResourceList::class);
        Livewire::component('module-automation-connectors::replay', ResourceList::class);
        Livewire::component('module-automation-connectors::reconciliation', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-connectors-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.connectors.authenticated-triggers-actions' => 'module-automation-connectors::authenticated-triggers-actions',
            'automation.connectors.webhooks' => 'module-automation-connectors::webhooks',
            'automation.connectors.rate-limits' => 'module-automation-connectors::rate-limits',
            'automation.connectors.cursor-sync' => 'module-automation-connectors::cursor-sync',
            'automation.connectors.replay' => 'module-automation-connectors::replay',
            'automation.connectors.reconciliation' => 'module-automation-connectors::reconciliation',
        ];
    }
}
