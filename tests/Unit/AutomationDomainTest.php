<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Liberu\Modules\Automation\Approvals\Domain\ApprovalRequest;
use Liberu\Modules\Automation\Approvals\Enums\ApprovalDecision;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowDefinition;
use Liberu\Modules\Automation\Rules\Domain\RuleCondition;
use Liberu\Modules\Automation\Rules\Services\RuleEvaluator;

it('validates workflow definitions before they cross the domain boundary', function (): void {
    $workflow = WorkflowDefinition::fromArray([
        'name' => 'Publish article',
        'steps' => [['type' => 'action', 'name' => 'publish']],
    ]);

    expect($workflow->name)->toBe('Publish article')
        ->and($workflow->steps)->toHaveCount(1);

    expect(fn () => WorkflowDefinition::fromArray(['name' => 'Invalid', 'steps' => []]))
        ->toThrow(InvalidArgumentException::class);
});

it('evaluates typed rule conditions without executing arbitrary expressions', function (): void {
    $rules = new RuleEvaluator();
    $context = ['amount' => 150, 'description' => 'urgent invoice'];

    expect($rules->all([
        ['field' => 'amount', 'operator' => 'greater_than', 'value' => 100],
        ['field' => 'description', 'operator' => 'contains', 'value' => 'urgent'],
    ], $context))->toBeTrue()
        ->and($rules->any([
            ['field' => 'amount', 'operator' => 'less_than', 'value' => 0],
            ['field' => 'missing', 'operator' => 'exists', 'value' => true],
        ], $context))->toBeFalse()
        ->and(RuleCondition::fromArray(['field' => 'amount', 'operator' => 'equals', 'value' => 150])->matches($context))
        ->toBeTrue();
});

it('enforces approval expiry and separation of duties', function (): void {
    $request = new ApprovalRequest(
        id: 'approval-1',
        teamId: 'team-1',
        requesterId: 'requester-1',
        status: 'pending',
        expiresAt: CarbonImmutable::parse('2026-08-24T00:00:00Z'),
    );

    expect($request->decide('reviewer-1', ApprovalDecision::Approved, CarbonImmutable::parse('2026-08-23T00:00:00Z'))->status)
        ->toBe('approved');

    expect(fn () => $request->decide('requester-1', ApprovalDecision::Approved, CarbonImmutable::parse('2026-08-23T00:00:00Z')))
        ->toThrow(InvalidArgumentException::class);
    expect(fn () => $request->decide('reviewer-1', ApprovalDecision::Approved, CarbonImmutable::parse('2026-08-24T00:00:00Z')))
        ->toThrow(InvalidArgumentException::class);
});
