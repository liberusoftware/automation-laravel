<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Domain;

use InvalidArgumentException;

final readonly class Provenance
{
    public function __construct(public string $source, public string $actorId, public string $promptHash, public ?string $parentAsset = null)
    {
        if ($source === '' || $actorId === '' || ! preg_match('/^[a-f0-9]{64}$/', $promptHash)) {
            throw new InvalidArgumentException('Image provenance requires source, actor, and SHA-256 prompt hash.');
        }
    }
}
