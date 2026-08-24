<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\AiGateway\Domain\GatewayRequest;
use Liberu\Modules\Automation\AiGateway\Domain\ModelDefinition;
use Liberu\Modules\Automation\AiGateway\Domain\StructuredOutput;
use Liberu\Modules\Automation\AiGateway\Domain\ToolPolicy;
use Liberu\Modules\Automation\AiGateway\Domain\UsageMeter;

it('validates model catalog metadata and context limits', function (): void {
    $model = new ModelDefinition('openai', 'gpt-5', 'chat', 4096, inputCostMicros: 2, outputCostMicros: 4);

    expect($model->supportsContext(4096))->toBeTrue()
        ->and($model->supportsContext(4097))->toBeFalse();
});

it('validates structured output and enforces tool policy', function (): void {
    $output = StructuredOutput::fromSchema([
        'type' => 'object',
        'properties' => ['answer' => ['type' => 'string']],
        'required' => ['answer'],
    ]);
    $policy = new ToolPolicy(['lookup'], maxCalls: 2);
    $request = new GatewayRequest('chat', 'gpt-5', ['prompt' => 'hello'], $output, $policy);

    $output->validate(['answer' => 'done']);
    expect($policy->allows('lookup'))->toBeTrue()
        ->and($policy->allows('lookup', 2))->toBeFalse()
        ->and($request->capability)->toBe('chat');

    expect(fn () => $output->validate(['answer' => 12]))->toThrow(InvalidArgumentException::class);
});

it('records usage and computes model cost', function (): void {
    $model = new ModelDefinition('openai', 'gpt-5', 'chat', 4096, inputCostMicros: 2, outputCostMicros: 4);
    $meter = new UsageMeter();
    $meter->record(10, 5, $model);
    $meter->record(2, 3, $model);

    expect($meter->totals())->toBe(['requests' => 2, 'input_tokens' => 12, 'output_tokens' => 8])
        ->and($meter->estimatedCostMicros($model))->toBe(56);
});
