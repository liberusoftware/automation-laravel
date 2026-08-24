<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\AiGateway\Domain\ProviderContract;
use Liberu\Modules\Automation\AiGateway\Domain\RoutingPolicy;

it('supports declared models and bounded fallback attempts', function (): void {
    expect((new ProviderContract('openai', ['gpt-5']))->supports('gpt-5'))->toBeTrue()
        ->and((new RoutingPolicy(['primary', 'fallback'], 2))->providerForAttempt(2))->toBe('fallback');

    expect(fn () => new RoutingPolicy([], 1))->toThrow(InvalidArgumentException::class);
});
