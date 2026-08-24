<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Evaluation\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Liberu\Modules\Automation\Evaluation\Filament\Resources\EvaluationResource;

final class EvaluationFilamentPlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'module-automation-evaluation-filament';
    }

    public function register(Panel $panel): void
    {
        $panel->resources([EvaluationResource::class]);
    }

    public function boot(Panel $panel): void {}
}
