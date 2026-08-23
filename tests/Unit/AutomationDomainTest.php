<?php

declare(strict_types=1);

use Carbon\CarbonImmutable;
use InvalidArgumentException;
use Liberu\Modules\Automation\AiGateway\Domain\ProviderContract;
use Liberu\Modules\Automation\AiGateway\Domain\RoutingPolicy;
use Liberu\Modules\Automation\Approvals\Domain\ApprovalRequest;
use Liberu\Modules\Automation\Approvals\Enums\ApprovalDecision;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowDefinition;
use Liberu\Modules\Automation\Connectors\Domain\ConnectorDefinition;
use Liberu\Modules\Automation\DataProcessing\Domain\ProcessingRequest;
use Liberu\Modules\Automation\Evaluation\Domain\QualityGate;
use Liberu\Modules\Automation\Image\Domain\ImageRequest;
use Liberu\Modules\Automation\PromptRegistry\Domain\PromptVersion;
use Liberu\Modules\Automation\Rules\Domain\RuleCondition;
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
