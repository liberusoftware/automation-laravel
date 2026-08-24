<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Livewire;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

final class DataProcessingLivewireServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('module-automation-data-processing::resource-list', ResourceList::class);
        Livewire::component('module-automation-data-processing::classification', ResourceList::class);
        Livewire::component('module-automation-data-processing::extraction', ResourceList::class);
        Livewire::component('module-automation-data-processing::summarization', ResourceList::class);
        Livewire::component('module-automation-data-processing::translation', ResourceList::class);
        Livewire::component('module-automation-data-processing::enrichment', ResourceList::class);
        Livewire::component('module-automation-data-processing::redaction', ResourceList::class);
        Livewire::component('module-automation-data-processing::batch-processing', ResourceList::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'module-automation-data-processing-livewire');
    }

    /** @return array<string, string> */
    public function capabilities(): array
    {
        return [
            'automation.data-processing.classification' => 'module-automation-data-processing::classification',
            'automation.data-processing.extraction' => 'module-automation-data-processing::extraction',
            'automation.data-processing.summarization' => 'module-automation-data-processing::summarization',
            'automation.data-processing.translation' => 'module-automation-data-processing::translation',
            'automation.data-processing.enrichment' => 'module-automation-data-processing::enrichment',
            'automation.data-processing.redaction' => 'module-automation-data-processing::redaction',
            'automation.data-processing.batch-processing' => 'module-automation-data-processing::batch-processing',
        ];
    }
}
