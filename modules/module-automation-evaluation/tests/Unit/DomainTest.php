<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Evaluation\Domain\QualityGate;

it('enforces quality gate thresholds', function (): void {
    $gate = new QualityGate('accuracy', 0.9);

    expect($gate->passes(0.95))->toBeTrue()->and($gate->passes(0.8))->toBeFalse();
    expect(fn () => new QualityGate('accuracy', 1.1))->toThrow(InvalidArgumentException::class);
});
