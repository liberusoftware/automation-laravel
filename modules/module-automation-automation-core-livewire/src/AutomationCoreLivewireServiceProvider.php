<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class AutomationCoreLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-automation-core::resource-list', ResourceList::class);
        Livewire::component('module-automation-automation-core::workflow-definitions', ResourceList::class);
        Livewire::component('module-automation-automation-core::versions', ResourceList::class);
        Livewire::component('module-automation-automation-core::triggers', ResourceList::class);
        Livewire::component('module-automation-automation-core::state', ResourceList::class);
        Livewire::component('module-automation-automation-core::runs', ResourceList::class);
        Livewire::component('module-automation-automation-core::variables', ResourceList::class);
        Livewire::component('module-automation-automation-core::schedules', ResourceList::class);
        Livewire::component('module-automation-automation-core::retries', ResourceList::class);
        Livewire::component('module-automation-automation-core::cancellation', ResourceList::class);
        Livewire::component('module-automation-automation-core::compensation', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-automation-core-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.automation-core.workflow-definitions' => 'module-automation-automation-core::workflow-definitions',
            'automation.automation-core.versions' => 'module-automation-automation-core::versions',
            'automation.automation-core.triggers' => 'module-automation-automation-core::triggers',
            'automation.automation-core.state' => 'module-automation-automation-core::state',
            'automation.automation-core.runs' => 'module-automation-automation-core::runs',
            'automation.automation-core.variables' => 'module-automation-automation-core::variables',
            'automation.automation-core.schedules' => 'module-automation-automation-core::schedules',
            'automation.automation-core.retries' => 'module-automation-automation-core::retries',
            'automation.automation-core.cancellation' => 'module-automation-automation-core::cancellation',
            'automation.automation-core.compensation' => 'module-automation-automation-core::compensation',
        ];
    }
}
