<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Image\Domain;

use InvalidArgumentException;

final readonly class DeliveryTarget
{
    public function __construct(public string $url, public string $method = 'GET')
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false || parse_url($url, PHP_URL_SCHEME) !== 'https' || $method !== 'GET') {
            throw new InvalidArgumentException('Image delivery targets must be HTTPS read endpoints.');
        }
    }
}
