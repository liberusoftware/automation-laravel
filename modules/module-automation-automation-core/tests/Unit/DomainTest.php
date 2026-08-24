<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\AutomationCore\Domain\WorkflowDefinition;

it('requires a named workflow with steps', function (): void {
    $workflow = WorkflowDefinition::fromArray(['name' => 'Publish', 'steps' => [['type' => 'action']]]);

    expect($workflow->toArray()['name'])->toBe('Publish');
    expect(fn () => WorkflowDefinition::fromArray(['name' => 'Invalid', 'steps' => []]))
        ->toThrow(InvalidArgumentException::class);
});
