<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Video\Domain;

use InvalidArgumentException;

final readonly class VideoDelivery
{
    public function __construct(public string $url, public string $format, public int $expiresAt)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false || parse_url($url, PHP_URL_SCHEME) !== 'https' || ! in_array($format, ['mp4', 'webm'], true) || $expiresAt <= time()) {
            throw new InvalidArgumentException('Video deliveries require an HTTPS URL, supported format, and future expiry.');
        }
    }
}
