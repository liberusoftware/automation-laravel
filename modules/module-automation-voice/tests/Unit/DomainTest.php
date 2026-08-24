<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Voice\Domain\VoiceRequest;

it('requires consent for voice processing', function (): void {
    expect((new VoiceRequest('stream', 'en-GB', true))->locale)->toBe('en-GB');
    expect(fn () => new VoiceRequest('stream', 'en-GB', false))->toThrow(InvalidArgumentException::class);
});
