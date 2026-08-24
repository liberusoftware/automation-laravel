<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\DataProcessing\Domain\ProcessingRequest;

it('requires supported processing input and flags redaction', function (): void {
    expect((new ProcessingRequest('redaction', 'record'))->requiresRedaction())->toBeTrue();
    expect(fn () => new ProcessingRequest('unknown', 'record'))->toThrow(InvalidArgumentException::class);
});
