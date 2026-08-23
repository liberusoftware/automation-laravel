<?php

declare(strict_types=1);

it('keeps the automation core and presentation packages one-to-one', function (): void {
    $root = dirname(__DIR__, 2);
    $slugs = [
        'automation-core', 'rules', 'approvals', 'ai-gateway', 'prompt-registry',
        'data-processing', 'voice', 'image', 'video', 'connectors', 'evaluation',
    ];

    foreach ($slugs as $slug) {
        foreach (['', '-api', '-filament', '-livewire'] as $suffix) {
            $directory = $root.'/modules/module-automation-'.$slug.$suffix;
            expect(is_dir($directory))->toBeTrue($directory);

            $composer = json_decode(file_get_contents($directory.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);
            $manifest = json_decode(file_get_contents($directory.'/module.json'), true, flags: JSON_THROW_ON_ERROR);

            expect($composer['name'])->toBe('liberusoftware/automation-'.$slug.$suffix)
                ->and($manifest['name'])->toBe('module-automation-'.$slug.$suffix)
                ->and($composer['type'])->toBe('liberu-module')
                ->and($manifest['requires']['php'])->toBe('^8.5');
        }
    }
});

it('keeps deferred presentation technologies out of the automation implementation', function (): void {
    $root = dirname(__DIR__, 2);

    expect(glob($root.'/modules/module-automation-*-react*'))->toBe([])
        ->and(glob($root.'/modules/module-automation-*-vue*'))->toBe([])
        ->and(glob($root.'/modules/module-automation-*-nuxt*'))->toBe([])
        ->and(glob($root.'/modules/module-automation-*-flutter*'))->toBe([])
        ->and(glob($root.'/modules/module-automation-*-react-native*'))->toBe([]);
});
