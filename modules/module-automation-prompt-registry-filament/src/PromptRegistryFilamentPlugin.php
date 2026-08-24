<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\PromptRegistry\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\PromptRegistry\Filament\Resources\PromptRegistryResource;

final class PromptRegistryFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-prompt-registry-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.prompt-registry.versioned-prompts' => PromptRegistryResource::class,
            'automation.prompt-registry.variables' => PromptRegistryResource::class,
            'automation.prompt-registry.evaluation-sets' => PromptRegistryResource::class,
            'automation.prompt-registry.tenant-overrides' => PromptRegistryResource::class,
            'automation.prompt-registry.approvals' => PromptRegistryResource::class,
            'automation.prompt-registry.rollback' => PromptRegistryResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
