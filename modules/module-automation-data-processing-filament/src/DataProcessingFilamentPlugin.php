<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\DataProcessing\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\DataProcessing\Filament\Resources\DataProcessingResource;

final class DataProcessingFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-data-processing-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.data-processing.classification' => DataProcessingResource::class,
            'automation.data-processing.extraction' => DataProcessingResource::class,
            'automation.data-processing.summarization' => DataProcessingResource::class,
            'automation.data-processing.translation' => DataProcessingResource::class,
            'automation.data-processing.enrichment' => DataProcessingResource::class,
            'automation.data-processing.redaction' => DataProcessingResource::class,
            'automation.data-processing.batch-processing' => DataProcessingResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
