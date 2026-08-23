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
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-ai-gateway-livewire');
    }
}
