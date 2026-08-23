<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Domain;

use InvalidArgumentException;

final readonly class ConnectorDefinition
{
    public function __construct(public string $name, public string $baseUrl, public string $secretReference)
    {
        if ($name === '' || $secretReference === '' || filter_var($baseUrl, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException('Connectors require a name, valid endpoint, and secret reference.');
        }

        if (parse_url($baseUrl, PHP_URL_SCHEME) !== 'https') {
            throw new InvalidArgumentException('Connector endpoints must use HTTPS.');
        }
    }
}
