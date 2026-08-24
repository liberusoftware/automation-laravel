<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Domain;

use InvalidArgumentException;

final readonly class ImageVariant
{
    public function __construct(public string $assetId, public int $number, public string $format, public int $width, public int $height)
    {
        if ($assetId === '' || $number < 1 || ! in_array($format, ['png', 'jpeg', 'webp'], true) || $width < 1 || $height < 1 || $width > 10000 || $height > 10000) {
            throw new InvalidArgumentException('Image variants require bounded dimensions and a supported format.');
        }
    }
}
