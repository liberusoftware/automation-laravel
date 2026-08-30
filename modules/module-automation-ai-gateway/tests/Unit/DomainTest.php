<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\AiGateway\Domain\GatewayRequest;
use Liberu\Modules\Automation\AiGateway\Domain\ProviderContract;
use Liberu\Modules\Automation\AiGateway\Domain\RoutingPolicy;
use Liberu\Modules\Automation\AiGateway\Domain\StructuredOutput;

it('supports declared models and bounded fallback attempts', function (): void {
    expect((new ProviderContract('openai', ['gpt-5']))->supports('gpt-5'))->toBeTrue()
        ->and((new RoutingPolicy(['primary', 'fallback'], 2))->providerForAttempt(2))->toBe('fallback');

    expect(fn () => new RoutingPolicy([], 1))->toThrow(InvalidArgumentException::class);
});

it('validates gateway requests against provider capabilities', function (): void {
    $request = new GatewayRequest(
        'classification',
        'gpt-5',
        ['text' => 'hello'],
        StructuredOutput::fromSchema(['type' => 'object', 'properties' => ['label' => ['type' => 'string']]]),
    );

    (new ProviderContract('openai', ['gpt-5'], supportsStructuredOutput: true))->validateRequest($request);

    expect(fn () => (new ProviderContract('legacy', ['gpt-5']))->validateRequest($request))
        ->toThrow(InvalidArgumentException::class, 'structured output');
    expect(fn () => (new ProviderContract('other', ['small-model']))->validateRequest($request))
        ->toThrow(InvalidArgumentException::class, 'requested model');
});
