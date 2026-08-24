#!/usr/bin/env php
<?php

declare(strict_types=1);

$root = realpath(__DIR__.'/..');
if ($root === false) {
    fwrite(STDERR, "Unable to resolve the repository root.\n");
    exit(1);
}

$files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
foreach ($iterator as $file) {
    if (! $file->isFile() || $file->getExtension() !== 'md') {
        continue;
    }

    $path = $file->getPathname();
    if (str_contains($path, DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR)
        || str_contains($path, DIRECTORY_SEPARATOR.'node_modules'.DIRECTORY_SEPARATOR)) {
        continue;
    }
    $files[] = $path;
}

$failures = [];
foreach ($files as $file) {
    $content = (string) file_get_contents($file);
    $fenceCount = preg_match_all('/^\s*```/m', $content);
    if ($fenceCount === false || $fenceCount % 2 !== 0) {
        $failures[] = relative($root, $file).': unbalanced fenced code block';
    }

    preg_match_all('/\[[^\]]*\]\(([^)]+)\)/', $content, $matches);
    foreach ($matches[1] as $target) {
        $target = trim($target);
        if ($target === '' || preg_match('/^(?:[a-z][a-z0-9+.-]*:|\/\/)/i', $target)) {
            continue;
        }

        $target = trim((string) preg_replace('/\s+.*$/', '', $target));
        $target = rawurldecode(explode('#', $target, 2)[0]);
        if ($target === '') {
            continue;
        }

        $resolved = realpath(dirname($file).DIRECTORY_SEPARATOR.$target);
        if ($resolved === false || ! is_file($resolved)) {
            $failures[] = relative($root, $file).": broken internal link '{$target}'";
        }
    }
}

if ($failures !== []) {
    fwrite(STDERR, implode("\n", $failures)."\n");
    fwrite(STDERR, sprintf("Documentation validation failed with %d finding(s).\n", count($failures)));
    exit(1);
}

printf("Documentation validation passed for %d Markdown files.\n", count($files));

function relative(string $root, string $path): string
{
    return ltrim(str_replace($root, '', $path), DIRECTORY_SEPARATOR);
}
