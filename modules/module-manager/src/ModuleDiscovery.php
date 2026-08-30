<?php

namespace Liberu\Foundation\ModuleManager;

use Composer\InstalledVersions;
use Liberu\Foundation\ModuleManager\Exceptions\InvalidManifest;

final class ModuleDiscovery
{
    /** @param list<string> $paths */
    public function discover(array $paths): ModuleRegistry
    {
        $modules = [];
        $capabilities = [];
        $packageNames = [];
        $installedPaths = [];
        $installedPackageNames = [];

        if (class_exists(InstalledVersions::class)) {
            foreach (InstalledVersions::getInstalledPackagesByType('liberu-module') as $package) {
                $installedPackageNames[$this->canonicalPackageName($package)] = true;
                $installPath = InstalledVersions::getInstallPath($package);
                if (is_string($installPath) && is_dir($installPath)) {
                    $installedPaths[] = realpath($installPath) ?: $installPath;
                }
            }
        }

        $paths = array_values(array_unique(array_filter(array_map(
            static fn (string $path): string|false => realpath($path),
            $paths,
        ))));

        $manifestPaths = [];
        foreach ($installedPaths as $path) {
            if (is_file($path.'/module.json')) {
                // Installed packages are the runtime source of truth. This is
                // important during a package rename, when the old checkout can
                // still sit beside the newly installed package.
                $manifestPaths[] = $path.'/module.json';
            }
        }
        foreach ($paths as $root) {
            foreach (glob(rtrim($root, '/').'/*/module.json') ?: [] as $path) {
                if (! in_array(realpath(dirname($path)), $installedPaths, true)) {
                    $manifestPaths[] = $path;
                }
            }
        }

        foreach (array_values(array_unique($manifestPaths)) as $path) {
            $manifest = Manifest::fromFile($path);
            if (! is_file($manifest->path.'/composer.json')) {
                throw new InvalidManifest("Module [{$manifest->name()}] has no composer.json.");
            }
            $composer = json_decode((string) file_get_contents($manifest->path.'/composer.json'), true, flags: JSON_THROW_ON_ERROR);
            $package = $composer['name'] ?? null;
            if (! is_string($package)) {
                throw new InvalidManifest("Module [{$manifest->name()}] has a missing or duplicate Composer package name.");
            }
            $package = $this->canonicalPackageName($package);

            // During the package rename, keep the legacy checkout available for
            // existing deployments but never register it beside its canonical
            // sibling in a fresh checkout.
            $directory = basename($manifest->path);
            if (str_starts_with($directory, 'module-liberu-')) {
                $canonicalDirectory = dirname($manifest->path).'/'.substr($directory, strlen('module-'));
                if (is_file($canonicalDirectory.'/module.json')) {
                    continue;
                }
            }

            $isInstalledCopy = in_array(realpath($manifest->path), $installedPaths, true);

            // A checked-out module is authoritative over the copy Composer installed
            // for the same package. This also makes package renames atomic while old
            // and new package names coexist in a developer's working tree.
            if (isset($packageNames[$package])) {
                if ($isInstalledCopy || isset($installedPackageNames[$package])) {
                    continue;
                }

                throw new InvalidManifest("Module [{$manifest->name()}] has a missing or duplicate Composer package name.");
            }
            if (isset($modules[$manifest->name()])) {
                throw new InvalidManifest("Duplicate module [{$manifest->name()}].");
            }
            $packageNames[$package] = $manifest->name();
            foreach ($manifest->capabilities() as $capability) {
                if (isset($capabilities[$capability])) {
                    throw new InvalidManifest("Duplicate capability [{$capability}].");
                }
                $capabilities[$capability] = $manifest->name();
            }
            $modules[$manifest->name()] = $manifest;
        }

        ksort($modules);

        return new ModuleRegistry($modules);
    }

    private function canonicalPackageName(string $package): string
    {
        return str_replace('liberusoftware/module-liberu-', 'liberusoftware/liberu-', $package);
    }
}
