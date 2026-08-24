<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Video\Domain\VideoRequest;

it('tracks video audio requirements', function (): void {
    expect((new VideoRequest('A product demo', true, 'audio-1'))->requiresAudio())->toBeTrue();
    expect(fn () => new VideoRequest(''))->toThrow(InvalidArgumentException::class);
});
