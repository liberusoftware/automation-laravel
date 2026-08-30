<?php

declare(strict_types=1);

it('keeps the filament adapter as an independent package', function (): void {
    expect('liberusoftware/liberu-business-workflow-reconciliation-filament')->toStartWith('liberusoftware/')
        ->and('liberusoftware/liberu-business-workflow-reconciliation')->toStartWith('liberusoftware/');
});
