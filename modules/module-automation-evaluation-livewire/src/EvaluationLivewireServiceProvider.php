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
        Livewire::component('module-automation-evaluation::quality-suites', ResourceList::class);
        Livewire::component('module-automation-evaluation::regression-comparison', ResourceList::class);
        Livewire::component('module-automation-evaluation::latency-cost-metrics', ResourceList::class);
        Livewire::component('module-automation-evaluation::safety-checks', ResourceList::class);
        Livewire::component('module-automation-evaluation::release-gates', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-evaluation-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.evaluation.quality-suites' => 'module-automation-evaluation::quality-suites',
            'automation.evaluation.regression-comparison' => 'module-automation-evaluation::regression-comparison',
            'automation.evaluation.latency-cost-metrics' => 'module-automation-evaluation::latency-cost-metrics',
            'automation.evaluation.safety-checks' => 'module-automation-evaluation::safety-checks',
            'automation.evaluation.release-gates' => 'module-automation-evaluation::release-gates',
        ];
    }
}
