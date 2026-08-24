<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class PromptRegistryLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-prompt-registry::resource-list', ResourceList::class);
        Livewire::component('module-automation-prompt-registry::versioned-prompts', ResourceList::class);
        Livewire::component('module-automation-prompt-registry::variables', ResourceList::class);
        Livewire::component('module-automation-prompt-registry::evaluation-sets', ResourceList::class);
        Livewire::component('module-automation-prompt-registry::tenant-overrides', ResourceList::class);
        Livewire::component('module-automation-prompt-registry::approvals', ResourceList::class);
        Livewire::component('module-automation-prompt-registry::rollback', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-prompt-registry-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.prompt-registry.versioned-prompts' => 'module-automation-prompt-registry::versioned-prompts',
            'automation.prompt-registry.variables' => 'module-automation-prompt-registry::variables',
            'automation.prompt-registry.evaluation-sets' => 'module-automation-prompt-registry::evaluation-sets',
            'automation.prompt-registry.tenant-overrides' => 'module-automation-prompt-registry::tenant-overrides',
            'automation.prompt-registry.approvals' => 'module-automation-prompt-registry::approvals',
            'automation.prompt-registry.rollback' => 'module-automation-prompt-registry::rollback',
        ];
    }
}
