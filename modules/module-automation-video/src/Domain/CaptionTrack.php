<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Domain;

use InvalidArgumentException;

final readonly class CaptionTrack
{
    /** @param list<CaptionCue> $cues */
    public function __construct(public string $locale, public array $cues)
    {
        if ($locale === '' || $cues === []) {
            throw new InvalidArgumentException('Caption tracks require a locale and cues.');
        }
        foreach ($cues as $index => $cue) {
            if ($cue->sequence !== $index) {
                throw new InvalidArgumentException('Caption cues must have contiguous sequence numbers.');
            }
        }
    }
}
