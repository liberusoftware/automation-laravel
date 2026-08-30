<?php

declare(strict_types=1);

use Liberu\Modules\Automation\Rules\Domain\DecisionTable;
use Liberu\Modules\Automation\Rules\Domain\RuleCondition;
use Liberu\Modules\Automation\Rules\Domain\RuleExpression;
use Liberu\Modules\Automation\Rules\Services\RuleEvaluator;

it('evaluates typed rule conditions', function (): void {
    $condition = RuleCondition::fromArray(['field' => 'amount', 'operator' => 'greater_than', 'value' => 100]);

    expect($condition->matches(['amount' => 150]))->toBeTrue()
        ->and((new RuleEvaluator())->all([['field' => 'amount', 'operator' => 'equals', 'value' => 150]], ['amount' => 150]))->toBeTrue();
});

it('evaluates nested expressions and validates decision-table rows', function (): void {
    $expression = RuleExpression::fromArray([
        'operator' => 'all',
        'conditions' => [
            ['field' => 'country', 'operator' => 'equals', 'value' => 'GB'],
            ['operator' => 'not', 'conditions' => [['field' => 'blocked', 'operator' => 'exists', 'value' => true]]],
        ],
    ]);
    $table = new DecisionTable('routing', [[
        'conditions' => [['field' => 'amount', 'operator' => 'greater_than', 'value' => 100]],
        'outcome' => 'manual-review',
    ]]);

    expect($expression->matches(['country' => 'GB']))->toBeTrue()
        ->and((new RuleEvaluator())->expression([
            'operator' => 'any',
            'conditions' => [['field' => 'country', 'operator' => 'equals', 'value' => 'US']],
        ], ['country' => 'US']))->toBeTrue()
        ->and($table->simulate(['amount' => 125])->toArray()['outcomes'])->toBe(['manual-review']);

    expect(fn () => new DecisionTable('routing', [['conditions' => [['field' => 'amount']], 'outcome' => 'review']]))
        ->toThrow(InvalidArgumentException::class)
        ->and(fn () => new DecisionTable('routing', [['conditions' => [], 'outcome' => 123]]))
        ->toThrow(InvalidArgumentException::class);
});
