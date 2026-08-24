<?php

declare(strict_types=1);

namespace Liberu\Modules\Liberu\BusinessWorkflowReconciliation\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;

final class BusinessWorkflowReconciliationFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-liberu-business-workflow-reconciliation-filament';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
