<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Domain;

use InvalidArgumentException;

final readonly class RoutingPolicy
{
    /** @param list<string> $providers */
    public function __construct(public array $providers, public int $maxAttempts = 1)
    {
        if ($providers === [] || $maxAttempts < 1 || $maxAttempts > count($providers)) {
            throw new InvalidArgumentException('Routing requires ordered providers and bounded attempts.');
        }
    }

    public function providerForAttempt(int $attempt): string
    {
        if ($attempt < 1 || $attempt > $this->maxAttempts) {
            throw new InvalidArgumentException('The routing attempt is outside the configured policy.');
        }

        return $this->providers[$attempt - 1];
    }
}
