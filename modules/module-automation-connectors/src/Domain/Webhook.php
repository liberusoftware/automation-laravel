<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Domain;

use InvalidArgumentException;

final readonly class Webhook
{
    public function __construct(public string $event, public string $payload, public int $timestamp, public string $signature)
    {
        if ($event === '' || $payload === '' || $timestamp < 1 || $signature === '') {
            throw new InvalidArgumentException('Webhooks require event, payload, timestamp, and signature.');
        }
    }

    public function verify(string $secret, int $now, int $toleranceSeconds = 300): bool
    {
        if ($secret === '' || $toleranceSeconds < 1 || abs($now - $this->timestamp) > $toleranceSeconds) {
            return false;
        }

        $expected = hash_hmac('sha256', $this->timestamp.'.'.$this->payload, $secret);

        return hash_equals($expected, $this->signature);
    }
}
