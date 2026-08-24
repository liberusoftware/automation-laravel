<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Liberu\Modules\Automation\AiGateway\Domain\ProviderContract;
use Liberu\Modules\Automation\AiGateway\Domain\RoutingPolicy;
use Liberu\Modules\Automation\Approvals\Domain\ApprovalQueue;
use Liberu\Modules\Automation\Approvals\Domain\ApprovalRequest;
use Liberu\Modules\Automation\Approvals\Domain\Delegation;
use Liberu\Modules\Automation\Approvals\Domain\EscalationPolicy;
use Liberu\Modules\Automation\Approvals\Domain\Evidence;
use Liberu\Modules\Automation\Approvals\Enums\ApprovalDecision;
use Liberu\Modules\Automation\AutomationCore\Domain\RetryPolicy;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowDefinition;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowRun;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowTrigger;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowVariables;
use Liberu\Modules\Automation\Connectors\Domain\ConnectorDefinition;
use Liberu\Modules\Automation\DataProcessing\Domain\ProcessingRequest;
use Liberu\Modules\Automation\Evaluation\Domain\QualityGate;
use Liberu\Modules\Automation\Image\Domain\ImageRequest;
use Liberu\Modules\Automation\PromptRegistry\Domain\PromptVersion;
use Liberu\Modules\Automation\Rules\Domain\DecisionTable;
use Liberu\Modules\Automation\Rules\Domain\RuleCondition;
use Liberu\Modules\Automation\Rules\Domain\RuleExpression;
use Liberu\Modules\Automation\Rules\Services\RuleEvaluator;
use Liberu\Modules\Automation\Video\Domain\VideoRequest;
use Liberu\Modules\Automation\Voice\Domain\VoiceRequest;

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

it('models schedules, retries, compensation, and typed workflow variables', function (): void {
    $workflow = WorkflowDefinition::fromArray([
        'name' => 'Invoice processing',
        'steps' => [['id' => 'charge', 'type' => 'action']],
        'schedule' => ['expression' => '0 * * * *', 'timezone' => 'UTC'],
        'retry' => ['max_attempts' => 3, 'backoff_seconds' => 30],
        'compensation' => [['type' => 'refund']],
    ]);

    $variables = WorkflowVariables::validate(['amount' => 10], ['amount' => ['type' => 'integer', 'required' => true]]);
    expect($workflow->schedule?->timezone)->toBe('UTC')
        ->and($workflow->retryPolicy->maxAttempts)->toBe(3)
        ->and($workflow->compensation)->toHaveCount(1)
        ->and($variables->values['amount'])->toBe(10);

    expect(fn () => WorkflowVariables::validate([], ['amount' => ['type' => 'integer', 'required' => true]]))
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

it('evaluates only allow-listed nested rule expressions and produces simulations', function (): void {
    $expression = RuleExpression::fromArray([
        'operator' => 'all',
        'conditions' => [
            ['field' => 'amount', 'operator' => 'greater_than', 'value' => 100],
            ['operator' => 'not', 'conditions' => [['field' => 'blocked', 'operator' => 'exists', 'value' => true]]],
        ],
    ]);
    $table = new DecisionTable('routing', [[
        'conditions' => [['field' => 'amount', 'operator' => 'greater_than', 'value' => 100]],
        'outcome' => 'review',
    ]]);

    expect($expression->matches(['amount' => 150]))->toBeTrue()
        ->and($table->simulate(['amount' => 150])->toArray()['outcomes'])->toBe(['review']);
    expect(fn () => RuleExpression::fromArray(['operator' => 'eval', 'conditions' => []]))
        ->toThrow(InvalidArgumentException::class);
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

it('supports bounded approval delegation, escalation, and evidence', function (): void {
    $request = new ApprovalRequest('approval-2', 'team-1', 'requester-1', 'pending', CarbonImmutable::parse('2026-08-24T01:00:00Z'));
    $delegated = $request->delegate('requester-1', new Delegation('reviewer-1', CarbonImmutable::parse('2026-08-24T00:30:00Z')), CarbonImmutable::parse('2026-08-23T00:00:00Z'));
    $escalated = $request->escalate('manager-1', new EscalationPolicy(3600, ['manager-1']), CarbonImmutable::parse('2026-08-24T00:30:00Z'));

    expect($delegated->status)->toBe('delegated')->and($escalated->status)->toBe('escalated');
    expect(new Evidence('decision', 'audit-1', str_repeat('a', 64))->type)->toBe('decision');
    expect(fn () => new Evidence('decision', 'audit-1', 'invalid'))->toThrow(InvalidArgumentException::class);
});

it('governs provider routing and prompt rendering', function (): void {
    $provider = new ProviderContract('openai', ['gpt-5'], supportsStructuredOutput: true);
    $routing = new RoutingPolicy(['openai', 'fallback'], maxAttempts: 2);
    $prompt = new PromptVersion('invoice-summary', 1, 'Summarise {{ document }}', ['document']);

    expect($provider->supports('gpt-5'))->toBeTrue()
        ->and($routing->providerForAttempt(2))->toBe('fallback')
        ->and($prompt->render(['document' => 'invoice']))->toBe('Summarise invoice');
    expect(fn () => $prompt->render([]))->toThrow(InvalidArgumentException::class);
});

it('validates processing, media, and consent boundaries', function (): void {
    $processing = new ProcessingRequest('redaction', 'customer record');
    $voice = new VoiceRequest('speech_to_text', 'en-GB', consentGiven: true);
    $image = new ImageRequest('remove background', 'edit', 'asset-1');
    $video = new VideoRequest('A short product demonstration');

    expect($processing->requiresRedaction())->toBeTrue()
        ->and($voice->locale)->toBe('en-GB')
        ->and($image->sourceAsset)->toBe('asset-1')
        ->and($video->captionsRequired)->toBeTrue();
    expect(fn () => new VoiceRequest('stream', 'en-GB', consentGiven: false))
        ->toThrow(InvalidArgumentException::class);
});

it('protects connector endpoints and evaluation release gates', function (): void {
    $connector = new ConnectorDefinition('billing', 'https://billing.example.test', 'secret.billing');
    $gate = new QualityGate('accuracy', 0.9);

    expect($connector->baseUrl)->toStartWith('https://')
        ->and($gate->passes(0.95))->toBeTrue()
        ->and($gate->passes(0.75))->toBeFalse();
    expect(fn () => new ConnectorDefinition('unsafe', 'http://billing.example.test', 'secret'))
        ->toThrow(InvalidArgumentException::class);
});

it('enforces workflow run transitions and trigger contracts', function (): void {
    $run = new WorkflowRun('run-1', 'workflow-1');
    $trigger = new WorkflowTrigger('webhook', 'invoice.created');

    $run->transitionTo('running');
    $run->transitionTo('succeeded');

    expect($run->status())->toBe('succeeded')->and($trigger->enabled)->toBeTrue();
    expect(fn () => $run->transitionTo('failed'))->toThrow(InvalidArgumentException::class);
});

it('supports bounded retries and explicit cancellation', function (): void {
    $run = new WorkflowRun('run-2', 'workflow-1');
    $run->startAttempt();
    $run->transitionTo('failed');

    expect($run->canRetry(new RetryPolicy(maxAttempts: 2)))->toBeTrue();
    $cancelled = new WorkflowRun('run-3', 'workflow-1');
    $cancelled->requestCancellation();
    expect($cancelled->status())->toBe('cancelled')->and($cancelled->cancellationRequested())->toBeTrue();
});

it('evaluates reusable decision tables and keeps approval queues team-scoped', function (): void {
    $table = new DecisionTable('invoice-routing', [[
        'conditions' => [['field' => 'amount', 'operator' => 'greater_than', 'value' => 1000]],
        'outcome' => 'manual-review',
    ]]);
    $pending = new ApprovalRequest('approval-2', 'team-1', 'requester-2', 'pending', CarbonImmutable::tomorrow());
    $queue = new ApprovalQueue('team-1', [$pending]);

    expect($table->outcomesFor(['amount' => 1500]))->toBe(['manual-review'])
        ->and($queue->pending())->toHaveCount(1);
    expect(fn () => new ApprovalQueue('team-2', [$pending]))->toThrow(InvalidArgumentException::class);
});
