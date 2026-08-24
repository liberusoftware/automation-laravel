<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Domain;

use InvalidArgumentException;

final readonly class TranscriptSegment
{
    public function __construct(public int $sequence, public string $speaker, public string $text, public float $startSeconds, public float $endSeconds, public bool $final = true)
    {
        if ($sequence < 0 || $speaker === '' || trim($text) === '' || $startSeconds < 0 || $endSeconds < $startSeconds) {
            throw new InvalidArgumentException('Transcript segments require ordered, non-negative timing and content.');
        }
    }
}
