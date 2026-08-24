<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Approvals\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\Approvals\Filament\Resources\ApprovalsResource;

final class ApprovalsFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-approvals-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.approvals.human-review-queues' => ApprovalsResource::class,
            'automation.approvals.separation-of-duties' => ApprovalsResource::class,
            'automation.approvals.expiry' => ApprovalsResource::class,
            'automation.approvals.escalation' => ApprovalsResource::class,
            'automation.approvals.delegation' => ApprovalsResource::class,
            'automation.approvals.evidence' => ApprovalsResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
