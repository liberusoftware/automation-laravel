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

it('keeps every Automation API adapter aligned with its CRUD and OpenAPI contract', function (): void {
    $root = dirname(__DIR__, 2);
    $slugs = [
        'automation-core', 'rules', 'approvals', 'ai-gateway', 'prompt-registry',
        'data-processing', 'voice', 'image', 'video', 'connectors', 'evaluation',
    ];

    foreach ($slugs as $slug) {
        $directory = $root.'/modules/module-automation-'.$slug.'-api';
        $controllerFile = glob($directory.'/src/Http/Controllers/*Controller.php')[0];
        $controller = file_get_contents($controllerFile);
        $routes = file_get_contents($directory.'/routes/api.php');
        $openapi = file_get_contents((glob($directory.'/openapi/v1/*.yaml'))[0]);
        $manifest = json_decode(file_get_contents($root.'/modules/module-automation-'.$slug.'/module.json'), true, flags: JSON_THROW_ON_ERROR);

        expect($routes)->toContain("'auth:sanctum'")
            ->toContain("'throttle:60,1'");

        foreach (['index', 'store', 'show', 'update', 'destroy'] as $method) {
            expect($controller)->toContain('function '.$method);
        }

        foreach (['Route::get', 'Route::post', 'Route::patch', 'Route::delete'] as $route) {
            expect($routes)->toContain($route);
        }

        if ($slug === 'automation-core') {
            expect($routes)->toContain("'/{id}/publish'")
                ->toContain("'/{id}/runs'")
                ->toContain("'/{id}/runs/{runId}/cancel'")
                ->and($openapi)->toContain('operationId: automation.automation.core.publish')
                ->toContain('operationId: automation.automation.core.run')
                ->toContain('operationId: automation.automation.core.cancel-run');
        }

        $operationPrefix = 'automation.'.str_replace('-', '.', $slug);
        foreach (['.list', '.create', '.get', '.update', '.delete'] as $operation) {
            expect($openapi)->toContain('operationId: '.$operationPrefix.$operation);
        }

        foreach ($manifest['capabilities'] as $capability) {
            $field = str_replace('-', '_', substr($capability, strrpos($capability, '.') + 1));
            expect($openapi)->toContain("        {$field}:");
        }
    }
});

it('keeps every Automation Filament and Livewire adapter interactive', function (): void {
    $root = dirname(__DIR__, 2);
    $slugs = [
        'automation-core', 'rules', 'approvals', 'ai-gateway', 'prompt-registry',
        'data-processing', 'voice', 'image', 'video', 'connectors', 'evaluation',
    ];

    foreach ($slugs as $slug) {
        $filament = $root.'/modules/module-automation-'.$slug.'-filament/src';
        $resource = glob($filament.'/Resources/*Resource.php')[0];
        $plugin = file_get_contents(glob($filament.'/*FilamentPlugin.php')[0]);
        $livewireDirectory = $root.'/modules/module-automation-'.$slug.'-livewire/src';
        $livewire = file_get_contents($livewireDirectory.'/ResourceList.php');
        $livewireProvider = file_get_contents(glob($livewireDirectory.'/*LivewireServiceProvider.php')[0]);
        $manifest = json_decode(file_get_contents($root.'/modules/module-automation-'.$slug.'/module.json'), true, flags: JSON_THROW_ON_ERROR);

        expect(file_get_contents($resource))
            ->toContain('function form', 'function table', 'function getPages', 'getEloquentQuery')
            ->and($plugin)->toContain('capabilities()', 'array_values($this->capabilities())')
            ->and($livewire)->toContain('function save', 'function edit', 'function delete', 'forTeam')
            ->and($livewireProvider)->toContain('capabilities()');

        foreach ($manifest['capabilities'] as $capability) {
            expect($plugin)->toContain("'{$capability}'")
                ->and($livewireProvider)->toContain("'{$capability}'");
        }
    }
});
