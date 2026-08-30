<?php

declare(strict_types=1);

it('keeps the livewire adapter as an independent package', function (): void {
    expect('liberusoftware/liberu-platform-orchestration-livewire')->toStartWith('liberusoftware/')
        ->and('liberusoftware/liberu-platform-orchestration')->toStartWith('liberusoftware/');
});
