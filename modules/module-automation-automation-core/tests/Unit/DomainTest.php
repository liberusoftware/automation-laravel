<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\AutomationCore\Domain\RetryPolicy;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowDefinition;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowRun;

it('requires a named workflow with steps', function (): void {
    $workflow = WorkflowDefinition::fromArray(['name' => 'Publish', 'steps' => [['type' => 'action']]]);

    expect($workflow->toArray()['name'])->toBe('Publish');
    expect(fn () => WorkflowDefinition::fromArray(['name' => 'Invalid', 'steps' => []]))
        ->toThrow(InvalidArgumentException::class);
});

it('enforces run completion, failure, retry, and cancellation transitions', function (): void {
    $run = new WorkflowRun('run-1', 'workflow-1');
    $policy = new RetryPolicy(maxAttempts: 2);

    expect($run->startAttempt())->toBe(1)
        ->and($run->status())->toBe('running');

    $run->fail();
    expect($run->canRetry($policy))->toBeTrue()
        ->and($run->retry($policy))->toBe(2)
        ->and($run->status())->toBe('running');

    $run->complete();
    expect($run->status())->toBe('succeeded');
    expect(fn () => $run->retry($policy))->toThrow(InvalidArgumentException::class);

    $nonRetryable = new WorkflowRun('run-3', 'workflow-1');
    $nonRetryable->startAttempt();
    $nonRetryable->fail(false);
    expect($nonRetryable->canRetry(new RetryPolicy(maxAttempts: 2)))->toBeFalse()
        ->and(fn () => WorkflowDefinition::fromArray(['name' => 'Bad', 'steps' => [['type' => 'action']], 'schedule' => 'daily']))
        ->toThrow(InvalidArgumentException::class);

    $cancelled = new WorkflowRun('run-2', 'workflow-1');
    $cancelled->requestCancellation();
    expect($cancelled->status())->toBe('cancelled')
        ->and($cancelled->cancellationRequested())->toBeTrue();
});
