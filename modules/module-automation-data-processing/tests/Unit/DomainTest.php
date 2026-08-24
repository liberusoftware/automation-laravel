<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\DataProcessing\Domain\ExtractionSchema;
use Liberu\Modules\Automation\DataProcessing\Domain\ProcessingBatch;
use Liberu\Modules\Automation\DataProcessing\Domain\ProcessingRequest;
use Liberu\Modules\Automation\DataProcessing\Domain\RedactionPolicy;

it('requires supported processing input and flags redaction', function (): void {
    expect((new ProcessingRequest('redaction', 'record'))->requiresRedaction())->toBeTrue();
    expect(fn () => new ProcessingRequest('unknown', 'record'))->toThrow(InvalidArgumentException::class);
});

it('validates extraction, translation, redaction, and bounded batches', function (): void {
    $translation = new ProcessingRequest('translation', 'hello', options: ['target_locale' => 'fr-FR']);
    $schema = new ExtractionSchema(['amount' => 'number', 'approved' => 'boolean']);
    $policy = new RedactionPolicy(['/secret\\s+\\w+/i']);
    $batch = new ProcessingBatch('batch-1', [$translation, new ProcessingRequest('redaction', 'secret value')]);

    $schema->validate(['amount' => 12.5, 'approved' => true]);
    expect($translation->targetLocale())->toBe('fr-FR')
        ->and($policy->apply('keep secret value'))->toBe('keep [REDACTED]')
        ->and($batch->operations())->toBe(['translation', 'redaction']);

    expect(fn () => new ProcessingRequest('translation', 'hello'))->toThrow(InvalidArgumentException::class)
        ->and(fn () => $schema->validate(['amount' => '12', 'approved' => true]))->toThrow(InvalidArgumentException::class);
});
