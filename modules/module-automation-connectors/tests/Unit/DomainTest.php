<?php

declare(strict_types=1);

use InvalidArgumentException;
use Liberu\Modules\Automation\Connectors\Domain\ConnectorAction;
use Liberu\Modules\Automation\Connectors\Domain\ConnectorDefinition;
use Liberu\Modules\Automation\Connectors\Domain\Cursor;
use Liberu\Modules\Automation\Connectors\Domain\RateLimit;
use Liberu\Modules\Automation\Connectors\Domain\ReconciliationReport;
use Liberu\Modules\Automation\Connectors\Domain\Webhook;

it('requires HTTPS connector endpoints', function (): void {
    expect(new ConnectorDefinition('billing', 'https://billing.example.test', 'secret.billing'))->toBeInstanceOf(ConnectorDefinition::class);
    expect(fn () => new ConnectorDefinition('billing', 'http://billing.example.test', 'secret.billing'))
        ->toThrow(InvalidArgumentException::class);
});

it('governs authenticated actions, signed webhooks, cursors, and reconciliation', function (): void {
    $action = new ConnectorAction('create-invoice', 'POST', '/invoices', ['write:billing']);
    $payload = '{"id":"invoice-1"}';
    $webhook = new Webhook('invoice.created', $payload, 1000, hash_hmac('sha256', '1000.'.$payload, 'secret'));
    $limit = new RateLimit(2, 60);

    expect($action->authorized(['write:billing']))->toBeTrue()
        ->and($webhook->verify('secret', 1000))->toBeTrue()
        ->and($limit->consume())->toBeTrue()->and($limit->consume())->toBeTrue()->and($limit->consume())->toBeFalse()
        ->and((new Cursor('cursor-1'))->next('cursor-2')->value)->toBe('cursor-2')
        ->and((new ReconciliationReport(3, 3, 0, 0))->isHealthy())->toBeTrue();

    expect(fn () => new ConnectorAction('unsafe', 'TRACE', '/'))->toThrow(InvalidArgumentException::class)
        ->and((new Webhook('invoice.created', $payload, 1000, 'bad'))->verify('secret', 1000))->toBeFalse();
});
