<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Domain;

use InvalidArgumentException;

final readonly class VoiceRequest
{
    public function __construct(public string $mode, public string $locale, public bool $consentGiven)
    {
        if (! in_array($mode, ['speech_to_text', 'text_to_speech', 'stream'], true) || $locale === '') {
            throw new InvalidArgumentException('Voice mode and locale are required.');
        }

        if (! $consentGiven) {
            throw new InvalidArgumentException('Voice processing requires recorded consent.');
        }
    }
}
