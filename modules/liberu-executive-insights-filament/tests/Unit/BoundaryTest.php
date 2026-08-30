<?php

declare(strict_types=1);

it('keeps the filament adapter as an independent package', function (): void {
    expect('liberusoftware/liberu-executive-insights-filament')->toStartWith('liberusoftware/')
        ->and('liberusoftware/liberu-executive-insights')->toStartWith('liberusoftware/');
});
