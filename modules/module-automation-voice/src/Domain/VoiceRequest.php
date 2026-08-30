<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Domain;

use Carbon\CarbonImmutable;
use InvalidArgumentException;

final readonly class VoiceRequest
{
    public function __construct(public string $mode, public string $locale, public bool $consentGiven)
    {
        if (! in_array($mode, ['speech_to_text', 'text_to_speech', 'stream'], true) || preg_match('/^[a-z]{2,3}(?:-[A-Z][a-z]{3})?(?:-[A-Z]{2}|-[0-9]{3})?$/', $locale) !== 1) {
            throw new InvalidArgumentException('Voice mode and locale are required.');
        }

        if (! $consentGiven) {
            throw new InvalidArgumentException('Voice processing requires recorded consent.');
        }
    }

    public function assertConsentActive(ConsentRecord $consent, CarbonImmutable $at): void
    {
        if (! $consent->isActive($at)) {
            throw new InvalidArgumentException('Voice processing requires active consent at execution time.');
        }
    }
}
