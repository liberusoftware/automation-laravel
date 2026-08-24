<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class ImageLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-image::resource-list', ResourceList::class);
        Livewire::component('module-automation-image::generation-requests', ResourceList::class);
        Livewire::component('module-automation-image::editing-requests', ResourceList::class);
        Livewire::component('module-automation-image::source-assets', ResourceList::class);
        Livewire::component('module-automation-image::moderation', ResourceList::class);
        Livewire::component('module-automation-image::provenance', ResourceList::class);
        Livewire::component('module-automation-image::variants', ResourceList::class);
        Livewire::component('module-automation-image::delivery', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-image-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.image.generation-requests' => 'module-automation-image::generation-requests',
            'automation.image.editing-requests' => 'module-automation-image::editing-requests',
            'automation.image.source-assets' => 'module-automation-image::source-assets',
            'automation.image.moderation' => 'module-automation-image::moderation',
            'automation.image.provenance' => 'module-automation-image::provenance',
            'automation.image.variants' => 'module-automation-image::variants',
            'automation.image.delivery' => 'module-automation-image::delivery',
        ];
    }
}
