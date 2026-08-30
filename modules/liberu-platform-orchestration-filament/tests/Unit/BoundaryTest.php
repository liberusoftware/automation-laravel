<?php

declare(strict_types=1);

it('keeps the filament adapter as an independent package', function (): void {
    expect('liberusoftware/liberu-platform-orchestration-filament')->toStartWith('liberusoftware/')
        ->and('liberusoftware/liberu-platform-orchestration')->toStartWith('liberusoftware/');
});
