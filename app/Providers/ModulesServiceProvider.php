<?php

namespace App\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

/**
 * Register mega-modules under modules/<id>/{views,js,css,script}.
 *
 * Module root config:
 * - config.php → config('<id>')
 * - <key>.config.php → config('<key>')  (e.g. governance-radar.config.php)
 *
 * Blade: view('calendar::index') → modules/calendar/views/index.blade.php
 */
class ModulesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $root = base_path('modules');
        if (! is_dir($root)) {
            return;
        }

        foreach (File::directories($root) as $modulePath) {
            $id = basename($modulePath);
            if ($id === '' || str_starts_with($id, '.')) {
                continue;
            }

            $main = $modulePath.'/config.php';
            if (is_file($main)) {
                $this->mergeConfigFrom($main, $id);
            }

            foreach (File::files($modulePath) as $file) {
                $name = $file->getFilename();
                if (! str_ends_with($name, '.config.php')) {
                    continue;
                }
                $key = substr($name, 0, -strlen('.config.php'));
                if ($key === '' || $key === $id) {
                    continue;
                }
                $this->mergeConfigFrom($file->getPathname(), $key);
            }
        }
    }

    public function boot(): void
    {
        $root = base_path('modules');
        if (! is_dir($root)) {
            return;
        }

        foreach (File::directories($root) as $modulePath) {
            $id = basename($modulePath);
            if ($id === '' || str_starts_with($id, '.')) {
                continue;
            }

            $views = $modulePath.'/views';
            if (is_dir($views)) {
                View::addNamespace($id, $views);
            }
        }
    }

    /**
     * @return list<string> Absolute paths to module JS/CSS entry candidates (for tooling).
     */
    public static function assetRoots(): array
    {
        $root = base_path('modules');
        if (! is_dir($root)) {
            return [];
        }

        $paths = [];
        foreach (File::directories($root) as $modulePath) {
            foreach (['js', 'css'] as $asset) {
                $dir = $modulePath.'/'.$asset;
                if (is_dir($dir)) {
                    $paths[] = $dir;
                }
            }
        }

        return $paths;
    }
}
