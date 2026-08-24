<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Rules\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class RulesLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-rules::resource-list', ResourceList::class);
        Livewire::component('module-automation-rules::typed-conditions', ResourceList::class);
        Livewire::component('module-automation-rules::expressions', ResourceList::class);
        Livewire::component('module-automation-rules::validation', ResourceList::class);
        Livewire::component('module-automation-rules::simulation', ResourceList::class);
        Livewire::component('module-automation-rules::decision-tables', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-rules-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.rules.typed-conditions' => 'module-automation-rules::typed-conditions',
            'automation.rules.expressions' => 'module-automation-rules::expressions',
            'automation.rules.validation' => 'module-automation-rules::validation',
            'automation.rules.simulation' => 'module-automation-rules::simulation',
            'automation.rules.decision-tables' => 'module-automation-rules::decision-tables',
        ];
    }
}
