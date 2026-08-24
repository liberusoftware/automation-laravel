<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\PromptRegistry\Domain\PromptVersion;

it('renders required prompt variables', function (): void {
    $prompt = new PromptVersion('welcome', 1, 'Hello {{ name }}', ['name']);

    expect($prompt->render(['name' => 'Ada']))->toBe('Hello Ada');
    expect(fn () => $prompt->render([]))->toThrow(InvalidArgumentException::class);
});
