<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Domain;

use InvalidArgumentException;

final readonly class ImageRequest
{
    public function __construct(public string $prompt, public string $operation = 'generate', public ?string $sourceAsset = null)
    {
        if (trim($prompt) === '' || strlen($prompt) > 10000 || ! in_array($operation, ['generate', 'edit', 'variant'], true)) {
            throw new InvalidArgumentException('Image requests require a prompt and supported operation.');
        }

        if ($operation !== 'generate' && trim((string) $sourceAsset) === '') {
            throw new InvalidArgumentException('Editing and variants require a source asset.');
        }

        if ($operation === 'generate' && $sourceAsset !== null) {
            throw new InvalidArgumentException('Generation requests cannot include a source asset.');
        }
    }
}
