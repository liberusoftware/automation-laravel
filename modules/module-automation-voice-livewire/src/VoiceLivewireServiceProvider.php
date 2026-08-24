<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class VoiceLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-voice::resource-list', ResourceList::class);
        Livewire::component('module-automation-voice::speech-to-text', ResourceList::class);
        Livewire::component('module-automation-voice::text-to-speech', ResourceList::class);
        Livewire::component('module-automation-voice::streaming-sessions', ResourceList::class);
        Livewire::component('module-automation-voice::interruption', ResourceList::class);
        Livewire::component('module-automation-voice::transcripts', ResourceList::class);
        Livewire::component('module-automation-voice::consent-controls', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-voice-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.voice.speech-to-text' => 'module-automation-voice::speech-to-text',
            'automation.voice.text-to-speech' => 'module-automation-voice::text-to-speech',
            'automation.voice.streaming-sessions' => 'module-automation-voice::streaming-sessions',
            'automation.voice.interruption' => 'module-automation-voice::interruption',
            'automation.voice.transcripts' => 'module-automation-voice::transcripts',
            'automation.voice.consent-controls' => 'module-automation-voice::consent-controls',
        ];
    }
}
