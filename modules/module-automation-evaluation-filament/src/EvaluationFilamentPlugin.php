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
        $panel->resources(array_values($this->capabilities()));
    }

    /** @return array<string, class-string> */
    public function capabilities(): array
    {
        return [
            'automation.evaluation.quality-suites' => EvaluationResource::class,
            'automation.evaluation.regression-comparison' => EvaluationResource::class,
            'automation.evaluation.latency-cost-metrics' => EvaluationResource::class,
            'automation.evaluation.safety-checks' => EvaluationResource::class,
            'automation.evaluation.release-gates' => EvaluationResource::class,
        ];
    }

    public function boot(Panel $panel): void {}
}
