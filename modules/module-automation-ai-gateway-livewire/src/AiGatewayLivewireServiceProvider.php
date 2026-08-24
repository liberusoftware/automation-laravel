<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class AiGatewayLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-ai-gateway::resource-list', ResourceList::class);
        Livewire::component('module-automation-ai-gateway::provider-contracts', ResourceList::class);
        Livewire::component('module-automation-ai-gateway::model-catalog', ResourceList::class);
        Livewire::component('module-automation-ai-gateway::routing', ResourceList::class);
        Livewire::component('module-automation-ai-gateway::fallback', ResourceList::class);
        Livewire::component('module-automation-ai-gateway::structured-output', ResourceList::class);
        Livewire::component('module-automation-ai-gateway::tool-policy', ResourceList::class);
        Livewire::component('module-automation-ai-gateway::usage-metering', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-ai-gateway-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.ai-gateway.provider-contracts' => 'module-automation-ai-gateway::provider-contracts',
            'automation.ai-gateway.model-catalog' => 'module-automation-ai-gateway::model-catalog',
            'automation.ai-gateway.routing' => 'module-automation-ai-gateway::routing',
            'automation.ai-gateway.fallback' => 'module-automation-ai-gateway::fallback',
            'automation.ai-gateway.structured-output' => 'module-automation-ai-gateway::structured-output',
            'automation.ai-gateway.tool-policy' => 'module-automation-ai-gateway::tool-policy',
            'automation.ai-gateway.usage-metering' => 'module-automation-ai-gateway::usage-metering',
        ];
    }
}
