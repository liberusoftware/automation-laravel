<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Domain;

use InvalidArgumentException;

final readonly class CaptionCue
{
    public function __construct(public int $sequence, public string $text, public float $startSeconds, public float $endSeconds)
    {
        if ($sequence < 0 || trim($text) === '' || $startSeconds < 0 || $endSeconds <= $startSeconds) {
            throw new InvalidArgumentException('Caption cues require ordered positive timing and text.');
        }
    }
}
