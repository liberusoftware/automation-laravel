<?php

declare(strict_types=1);

it('keeps the api adapter as an independent package', function (): void {
    expect('liberusoftware/liberu-revenue-and-care-orchestration-api')->toStartWith('liberusoftware/')
        ->and('liberusoftware/liberu-revenue-and-care-orchestration')->toStartWith('liberusoftware/');
});
