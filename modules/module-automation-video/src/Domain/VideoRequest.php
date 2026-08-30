<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Domain;

use InvalidArgumentException;

final readonly class VideoRequest
{
    public function __construct(public string $script, public bool $captionsRequired = true, public ?string $audioAsset = null)
    {
        if (trim($script) === '' || strlen($script) > 100000 || ($audioAsset !== null && trim($audioAsset) === '')) {
            throw new InvalidArgumentException('Video generation requires a non-empty script.');
        }
    }

    public function requiresAudio(): bool
    {
        return $this->audioAsset !== null;
    }
}
