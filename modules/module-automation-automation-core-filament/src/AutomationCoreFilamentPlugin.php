<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AutomationCore\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\AutomationCore\Filament\Resources\AutomationCoreResource;

final class AutomationCoreFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-automation-core-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.automation-core.workflow-definitions' => AutomationCoreResource::class,
            'automation.automation-core.versions' => AutomationCoreResource::class,
            'automation.automation-core.triggers' => AutomationCoreResource::class,
            'automation.automation-core.state' => AutomationCoreResource::class,
            'automation.automation-core.runs' => AutomationCoreResource::class,
            'automation.automation-core.variables' => AutomationCoreResource::class,
            'automation.automation-core.schedules' => AutomationCoreResource::class,
            'automation.automation-core.retries' => AutomationCoreResource::class,
            'automation.automation-core.cancellation' => AutomationCoreResource::class,
            'automation.automation-core.compensation' => AutomationCoreResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
