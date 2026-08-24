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
        Livewire::component('module-automation-approvals::human-review-queues', ResourceList::class);
        Livewire::component('module-automation-approvals::separation-of-duties', ResourceList::class);
        Livewire::component('module-automation-approvals::expiry', ResourceList::class);
        Livewire::component('module-automation-approvals::escalation', ResourceList::class);
        Livewire::component('module-automation-approvals::delegation', ResourceList::class);
        Livewire::component('module-automation-approvals::evidence', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-approvals-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.approvals.human-review-queues' => 'module-automation-approvals::human-review-queues',
            'automation.approvals.separation-of-duties' => 'module-automation-approvals::separation-of-duties',
            'automation.approvals.expiry' => 'module-automation-approvals::expiry',
            'automation.approvals.escalation' => 'module-automation-approvals::escalation',
            'automation.approvals.delegation' => 'module-automation-approvals::delegation',
            'automation.approvals.evidence' => 'module-automation-approvals::evidence',
        ];
    }
}
