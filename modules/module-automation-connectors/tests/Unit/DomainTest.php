<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Connectors\Domain\ConnectorDefinition;

it('requires HTTPS connector endpoints', function (): void {
    expect(new ConnectorDefinition('billing', 'https://billing.example.test', 'secret.billing'))->toBeInstanceOf(ConnectorDefinition::class);
    expect(fn () => new ConnectorDefinition('billing', 'http://billing.example.test', 'secret.billing'))
        ->toThrow(InvalidArgumentException::class);
});
