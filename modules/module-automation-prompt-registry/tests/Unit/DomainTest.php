<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\PromptRegistry\Domain\PromptEvaluationSet;
use Liberu\Modules\Automation\PromptRegistry\Domain\PromptOverride;
use Liberu\Modules\Automation\PromptRegistry\Domain\PromptRelease;
use Liberu\Modules\Automation\PromptRegistry\Domain\PromptVersion;

it('renders required prompt variables', function (): void {
    $prompt = new PromptVersion('welcome', 1, 'Hello {{ name }}', ['name']);

    expect($prompt->render(['name' => 'Ada']))->toBe('Hello Ada');
    expect(fn () => $prompt->render([]))->toThrow(InvalidArgumentException::class);
});

it('governs prompt approvals, overrides, evaluation sets, and rollback', function (): void {
    $versionOne = new PromptVersion('welcome', 1, 'Hello {{ name }}', ['name']);
    $versionTwo = new PromptVersion('welcome', 2, 'Welcome {{ name }}', ['name']);
    $approval = $versionTwo->approvedForRelease('reviewer-1', 'author-1');
    $release = new PromptRelease('welcome', [$versionOne, $versionTwo]);
    $release->publish($versionTwo, $approval);

    expect($release->active()?->version)->toBe(2)
        ->and((new PromptOverride('welcome', 'team-1', 'brand-a', $versionTwo, 10))->appliesTo('team-1', 'brand-a'))->toBeTrue()
        ->and(new PromptEvaluationSet('smoke', [['input' => ['name' => 'Ada'], 'expected' => 'Welcome Ada']]))->toBeInstanceOf(PromptEvaluationSet::class);

    $release->rollbackTo(1);
    expect($release->active()?->version)->toBe(1);
    expect(fn () => $versionTwo->approvedForRelease('author-1', 'author-1'))->toThrow(InvalidArgumentException::class);
    expect(fn () => new PromptVersion('unsafe', 1, 'Hello {{ missing }}'))->toThrow(InvalidArgumentException::class)
        ->and(fn () => new PromptEvaluationSet('invalid', [['input' => [], 'expected' => '']]))->toThrow(InvalidArgumentException::class);
});
