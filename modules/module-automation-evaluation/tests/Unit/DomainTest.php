<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Evaluation\Domain\EvaluationCase;
use Liberu\Modules\Automation\Evaluation\Domain\EvaluationSuite;
use Liberu\Modules\Automation\Evaluation\Domain\QualityGate;
use Liberu\Modules\Automation\Evaluation\Domain\RegressionComparison;
use Liberu\Modules\Automation\Evaluation\Domain\SafetyCheck;

it('enforces quality gate thresholds', function (): void {
    $gate = new QualityGate('accuracy', 0.9);

    expect($gate->passes(0.95))->toBeTrue()->and($gate->passes(0.8))->toBeFalse();
    expect(fn () => new QualityGate('accuracy', 1.1))->toThrow(InvalidArgumentException::class);
});

it('compares evaluation regressions and blocks unsafe release output', function (): void {
    $suite = new EvaluationSuite('release', [new EvaluationCase('case-1', ['prompt' => 'hello'], 'world')], [new QualityGate('accuracy', 0.9)]);
    $regression = new RegressionComparison('accuracy', 0.95, 0.92, 0.02);
    $safety = new SafetyCheck('secret leakage', ['/api[_-]?key/i']);

    expect($suite->passes(['accuracy' => 0.92]))->toBeTrue()
        ->and($regression->passes())->toBeFalse()
        ->and($regression->delta())->toBe(-0.03)
        ->and($safety->passes('safe output'))->toBeTrue()
        ->and($safety->passes('api_key=secret'))->toBeFalse();
});
