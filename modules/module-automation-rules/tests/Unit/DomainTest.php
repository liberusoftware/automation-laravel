<?php

declare(strict_types=1);

use Liberu\Modules\Automation\Rules\Domain\RuleCondition;
use Liberu\Modules\Automation\Rules\Services\RuleEvaluator;

it('evaluates typed rule conditions', function (): void {
    $condition = RuleCondition::fromArray(['field' => 'amount', 'operator' => 'greater_than', 'value' => 100]);

    expect($condition->matches(['amount' => 150]))->toBeTrue()
        ->and((new RuleEvaluator())->all([['field' => 'amount', 'operator' => 'equals', 'value' => 150]], ['amount' => 150]))->toBeTrue();
});
