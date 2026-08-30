<?php

declare(strict_types=1);

it('keeps the livewire adapter as an independent package', function (): void {
    expect('liberusoftware/liberu-revenue-and-care-orchestration-livewire')->toStartWith('liberusoftware/')
        ->and('liberusoftware/liberu-revenue-and-care-orchestration')->toStartWith('liberusoftware/');
});
