<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Domain;

use InvalidArgumentException;

final readonly class CaptionTrack
{
    /** @param list<CaptionCue> $cues */
    public function __construct(public string $locale, public array $cues)
    {
        if (preg_match('/^[a-z]{2,3}(?:-[A-Z][a-z]{3})?(?:-[A-Z]{2}|-[0-9]{3})?$/', $locale) !== 1 || $cues === [] || array_filter($cues, static fn (mixed $cue): bool => ! $cue instanceof CaptionCue) !== []) {
            throw new InvalidArgumentException('Caption tracks require a locale and cues.');
        }
        foreach ($cues as $index => $cue) {
            $previous = $cues[$index - 1] ?? null;
            if ($cue->sequence !== $index || ($previous instanceof CaptionCue && $cue->startSeconds < $previous->endSeconds)) {
                throw new InvalidArgumentException('Caption cues must have contiguous sequence numbers.');
            }
        }
    }
}
