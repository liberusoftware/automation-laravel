<?php

declare(strict_types=1);

/**
 * Update the thin workflow callers in the package-repository fleet.
 *
 * The package repositories are the source of truth for published packages;
 * the application checkout must never be edited in place to make this fleet
 * look consistent. This script is intentionally explicit about its workspace
 * and reusable-workflow commit.
 */

$workspace = getenv('FLEET_WORKSPACE') ?: '';
$workflowSha = $argv[1] ?? '';

if ($workspace === '' || ! is_dir($workspace)) {
    fwrite(STDERR, "FLEET_WORKSPACE must point at a cloned package fleet.\n");
    exit(2);
}

if (! preg_match('/^[0-9a-f]{40}$/', $workflowSha)) {
    fwrite(STDERR, "Usage: FLEET_WORKSPACE=/path php scripts/propagate-package-ci.php <workflow-commit-sha>\n");
    exit(2);
}

$workflowTemplates = [
    'install.yml' => <<<'YAML'
name: Install

on:
  push:
    tags: ['[0-9]+.[0-9]+.[0-9]+']
  workflow_dispatch:

jobs:
  install:
    uses: liberusoftware/.github/.github/workflows/package-install.yml@SHA
YAML,
    'compatibility.yml' => <<<'YAML'
name: Compatibility

on:
  push:
    tags: ['[0-9]+.[0-9]+.[0-9]+']
  workflow_dispatch:

jobs:
  compatibility:
    uses: liberusoftware/.github/.github/workflows/package-compatibility.yml@SHA
YAML,
    'tests.yml' => <<<'YAML'
name: Tests

on:
  push:
    branches: [main]
  pull_request:
    branches: [main]
  workflow_dispatch:

concurrency:
  group: ${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true

jobs:
  tests:
    if: ${{ hashFiles('tests/**/*.php') != '' }}
    uses: liberusoftware/.github/.github/workflows/package-tests.yml@SHA
YAML,
];

$changed = [];

foreach (new DirectoryIterator($workspace) as $entry) {
    if (! $entry->isDir() || $entry->isDot() || ! is_dir($entry->getPathname().'/.git')) {
        continue;
    }

    $repo = $entry->getPathname();
    $workflowDir = $repo.'/.github/workflows';
    if (! is_dir($workflowDir)) {
        mkdir($workflowDir, 0755, true);
    }

    $repoChanged = false;
    foreach ($workflowTemplates as $filename => $template) {
        $path = $workflowDir.'/'.$filename;
        if (is_file($path)) {
            $contents = file_get_contents($path);
            $updated = preg_replace(
                '/(liberusoftware\/\.github\/\.github\/workflows\/[^\s@]+)@main/',
                '$1@'.$workflowSha,
                $contents,
            );
            if ($updated !== $contents) {
                file_put_contents($path, $updated);
                $repoChanged = true;
            }
            continue;
        }

        if ($filename === 'tests.yml' && ! is_dir($repo.'/tests')) {
            continue;
        }

        file_put_contents($path, str_replace('SHA', $workflowSha, $template)."\n");
        $repoChanged = true;
    }

    if ($repoChanged) {
        $changed[] = basename($repo);
    }
}

sort($changed);
printf("Updated %d package repositories.\n", count($changed));
foreach ($changed as $repo) {
    echo "- {$repo}\n";
}
