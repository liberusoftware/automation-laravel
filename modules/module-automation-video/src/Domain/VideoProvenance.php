<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Domain;

use InvalidArgumentException;

final readonly class VideoProvenance
{
    public function __construct(public string $source, public string $actorId, public string $scriptHash, public ?string $parentAsset = null)
    {
        if ($source === '' || $actorId === '' || ! preg_match('/^[a-f0-9]{64}$/', $scriptHash)) {
            throw new InvalidArgumentException('Video provenance requires source, actor, and SHA-256 script hash.');
        }
    }
}
