<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Image\Domain\ImageRequest;

it('requires a source asset for edits', function (): void {
    expect((new ImageRequest('remove background', 'edit', 'asset-1'))->sourceAsset)->toBe('asset-1');
    expect(fn () => new ImageRequest('remove background', 'edit'))->toThrow(InvalidArgumentException::class);
});
