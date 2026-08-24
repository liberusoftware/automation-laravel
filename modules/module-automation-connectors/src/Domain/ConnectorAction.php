<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Domain;

use InvalidArgumentException;

final readonly class ConnectorAction
{
    /** @param list<string> $requiredScopes */
    public function __construct(public string $name, public string $method, public string $path, public array $requiredScopes = [])
    {
        if ($name === '' || ! in_array($method, ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], true) || $path === '' || $path[0] !== '/' || array_filter($requiredScopes, static fn (mixed $scope): bool => ! is_string($scope) || trim($scope) === '') !== []) {
            throw new InvalidArgumentException('Connector actions require a valid HTTP method, path, and scopes.');
        }
    }

    /** @param list<string> $scopes */
    public function authorized(array $scopes): bool
    {
        return array_diff($this->requiredScopes, $scopes) === [];
    }
}
