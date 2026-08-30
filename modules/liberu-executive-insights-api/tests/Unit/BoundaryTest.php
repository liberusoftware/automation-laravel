<?php

declare(strict_types=1);

it('keeps the api adapter as an independent package', function (): void {
    expect('liberusoftware/liberu-executive-insights-api')->toStartWith('liberusoftware/')
        ->and('liberusoftware/liberu-executive-insights')->toStartWith('liberusoftware/');
});
