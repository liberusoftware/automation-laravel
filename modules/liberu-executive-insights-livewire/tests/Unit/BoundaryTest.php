<?php

declare(strict_types=1);

it('keeps the livewire adapter as an independent package', function (): void {
    expect('liberusoftware/liberu-executive-insights-livewire')->toStartWith('liberusoftware/')
        ->and('liberusoftware/liberu-executive-insights')->toStartWith('liberusoftware/');
});
