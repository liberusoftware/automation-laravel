<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class VideoLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-video::resource-list', ResourceList::class);
        Livewire::component('module-automation-video::generation-jobs', ResourceList::class);
        Livewire::component('module-automation-video::editing-jobs', ResourceList::class);
        Livewire::component('module-automation-video::scripts', ResourceList::class);
        Livewire::component('module-automation-video::captions', ResourceList::class);
        Livewire::component('module-automation-video::audio', ResourceList::class);
        Livewire::component('module-automation-video::moderation', ResourceList::class);
        Livewire::component('module-automation-video::provenance', ResourceList::class);
        Livewire::component('module-automation-video::delivery', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-video-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.video.generation-jobs' => 'module-automation-video::generation-jobs',
            'automation.video.editing-jobs' => 'module-automation-video::editing-jobs',
            'automation.video.scripts' => 'module-automation-video::scripts',
            'automation.video.captions' => 'module-automation-video::captions',
            'automation.video.audio' => 'module-automation-video::audio',
            'automation.video.moderation' => 'module-automation-video::moderation',
            'automation.video.provenance' => 'module-automation-video::provenance',
            'automation.video.delivery' => 'module-automation-video::delivery',
        ];
    }
}
