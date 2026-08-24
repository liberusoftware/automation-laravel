<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Image\Domain\DeliveryTarget;
use Liberu\Modules\Automation\Image\Domain\ImageRequest;
use Liberu\Modules\Automation\Image\Domain\ImageVariant;
use Liberu\Modules\Automation\Image\Domain\ModerationDecision;
use Liberu\Modules\Automation\Image\Domain\Provenance;

it('requires a source asset for edits', function (): void {
    expect((new ImageRequest('remove background', 'edit', 'asset-1'))->sourceAsset)->toBe('asset-1');
    expect(fn () => new ImageRequest('remove background', 'edit'))->toThrow(InvalidArgumentException::class);
});

it('governs image moderation, provenance, variants, and safe delivery', function (): void {
    $moderation = new ModerationDecision('approved', 'policy-1');
    $provenance = new Provenance('generated', 'actor-1', str_repeat('a', 64));
    $variant = new ImageVariant('asset-1', 1, 'webp', 1200, 800);

    expect($moderation->mayDeliver())->toBeTrue()
        ->and($provenance->source)->toBe('generated')
        ->and($variant->format)->toBe('webp')
        ->and(new DeliveryTarget('https://cdn.example.test/image.webp'))->toBeInstanceOf(DeliveryTarget::class);
    expect(fn () => new ModerationDecision('rejected', 'policy-1'))->toThrow(InvalidArgumentException::class)
        ->and(fn () => new DeliveryTarget('http://cdn.example.test/image.webp'))->toThrow(InvalidArgumentException::class);
});
