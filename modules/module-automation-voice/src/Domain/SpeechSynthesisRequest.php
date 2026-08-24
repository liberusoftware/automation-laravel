<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Domain;

use InvalidArgumentException;

final readonly class SpeechSynthesisRequest
{
    public function __construct(public string $text, public string $voice, public string $format = 'audio/mpeg', public int $sampleRate = 24000)
    {
        if (trim($text) === '' || $voice === '' || ! in_array($format, ['audio/mpeg', 'audio/wav', 'audio/ogg'], true) || $sampleRate < 8000 || $sampleRate > 96000) {
            throw new InvalidArgumentException('Text-to-speech requests require valid voice, format, and sample rate.');
        }
    }
}
