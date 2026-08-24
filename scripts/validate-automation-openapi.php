#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$files = glob($root.'/modules/module-automation-*-api/openapi/v1/*.yaml') ?: [];
$errors = [];

foreach ($files as $file) {
    $body = (string) file_get_contents($file);
    $required = ['openapi: 3.1.0', 'paths:', 'components:', 'securitySchemes:', 'sanctum:'];

    foreach ($required as $needle) {
        if (! str_contains($body, $needle)) {
            $errors[] = basename(dirname(dirname($file))).': missing '.$needle;
        }
    }

    foreach (['get:', 'post:', 'patch:', 'delete:'] as $operation) {
        if (! str_contains($body, "    {$operation}")) {
            $errors[] = basename($file).": missing CRUD operation {$operation}";
        }
    }
}

if ($files === []) {
    $errors[] = 'No Automation OpenAPI documents found.';
}

if ($errors !== []) {
    fwrite(STDERR, implode(PHP_EOL, $errors).PHP_EOL);
    exit(1);
}

printf("Validated %d Automation OpenAPI documents.\n", count($files));
