#!/usr/bin/env php
<?php

/**
 * Export PHP catalog waves → content/catalogs JSON.
 *
 * Usage:
 *   php -d memory_limit=512M scripts/export-catalog-json.php
 *   php -d memory_limit=512M scripts/export-catalog-json.php --catalog=suppliers
 *   php -d memory_limit=512M scripts/export-catalog-json.php --catalog=glossary
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$args = array_slice($argv, 1);
$catalogFilter = null;
foreach ($args as $arg) {
    if (str_starts_with($arg, '--catalog=')) {
        $catalogFilter = substr($arg, strlen('--catalog='));
    }
}

/**
 * @param  mixed  $data
 */
function write_json(string $path, mixed $data): void
{
    $dir = dirname($path);
    if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
        fwrite(STDERR, "Failed to create {$dir}\n");
        exit(1);
    }

    $json = json_encode(
        $data,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    );
    if (file_put_contents($path, $json."\n") === false) {
        fwrite(STDERR, "Failed to write {$path}\n");
        exit(1);
    }

    echo 'wrote '.$path.' ('.number_format(strlen($json))." bytes)\n";
}

/**
 * @return list<array<string, mixed>>
 */
function load_supplier_products_base(string $root): array
{
    $catalogPath = $root.'/config/suppliers-catalog.php';
    $source = file_get_contents($catalogPath);
    if ($source === false) {
        fwrite(STDERR, "Cannot read suppliers-catalog.php\n");
        exit(1);
    }

    $cut = strpos($source, "\n\$governance = array_merge");
    if ($cut === false) {
        fwrite(STDERR, "Overlay marker not found — catalog may already be JSON-backed\n");
        exit(1);
    }

    $tmp = $root.'/config/.binom-suppliers-base-export.php';
    $head = substr($source, 0, $cut)."\nreturn \$products;\n";
    file_put_contents($tmp, $head);
    try {
        /** @var mixed $products */
        $products = require $tmp;
    } finally {
        @unlink($tmp);
    }

    if (! is_array($products) || ! array_is_list($products)) {
        fwrite(STDERR, "Base products export did not return a list\n");
        exit(1);
    }

    return $products;
}

/**
 * @return array<string, array<string, mixed>>
 */
function merge_overlay_files(string $root, string $baseName, int $fromWave, int $toWave): array
{
    $files = [];
    $base = $root.'/config/'.$baseName.'.php';
    if (is_file($base)) {
        $files[] = $base;
    }
    for ($w = $fromWave; $w <= $toWave; $w++) {
        $path = $root.'/config/'.$baseName.'-wave'.$w.'.php';
        if (is_file($path)) {
            $files[] = $path;
        }
    }

    $merged = [];
    foreach ($files as $file) {
        $chunk = require $file;
        if (! is_array($chunk)) {
            fwrite(STDERR, "Expected array from {$file}\n");
            exit(1);
        }
        $merged = array_merge($merged, $chunk);
    }

    return $merged;
}

function export_suppliers(string $root): void
{
    if (! is_file($root.'/config/suppliers-catalog.php')) {
        fwrite(STDERR, "suppliers-catalog.php missing; skipping suppliers export\n");

        return;
    }

    $out = $root.'/content/catalogs/suppliers';
    $suppliersMeta = require $root.'/config/suppliers.php';
    // When suppliers.php already uses CatalogJsonLoader this require would recurse — only call before facade swap.
    $domains = is_array($suppliersMeta['domains'] ?? null) ? $suppliersMeta['domains'] : [];

    $products = load_supplier_products_base($root);
    $governance = merge_overlay_files($root, 'suppliers-governance', 2, 12);
    $quality = merge_overlay_files($root, 'suppliers-quality', 2, 12);
    $sql = merge_overlay_files($root, 'suppliers-sql', 2, 12);

    write_json($out.'/meta.json', [
        'schemaVersion' => 1,
        'catalog' => 'suppliers',
        'domains' => $domains,
    ]);
    write_json($out.'/products.json', $products);
    write_json($out.'/governance.json', $governance);
    write_json($out.'/quality.json', $quality);
    write_json($out.'/sql.json', $sql);

    echo 'suppliers products='.count($products)
        .' governance='.count($governance)
        .' quality='.count($quality)
        .' sql='.count($sql)."\n";
}

function export_glossary(string $root): void
{
    $glossaryPath = $root.'/config/glossary.php';
    if (! is_file($glossaryPath)) {
        fwrite(STDERR, "glossary.php missing\n");
        exit(1);
    }

    $raw = file_get_contents($glossaryPath);
    if (is_string($raw) && str_contains($raw, 'CatalogJsonLoader::load')) {
        fwrite(STDERR, "glossary.php already JSON facade; skipping\n");

        return;
    }

    $config = require $glossaryPath;
    if (! is_array($config)) {
        fwrite(STDERR, "glossary.php did not return array\n");
        exit(1);
    }

    $categories = is_array($config['categories'] ?? null) ? $config['categories'] : [];
    $allTerms = is_array($config['terms'] ?? null) ? $config['terms'] : [];

    $buzzIds = [];
    for ($w = 2; $w <= 7; $w++) {
        $waveFile = $root.'/config/glossary-buzzwords-wave'.$w.'.php';
        if (! is_file($waveFile)) {
            continue;
        }
        $wave = require $waveFile;
        if (! is_array($wave)) {
            continue;
        }
        foreach ($wave as $term) {
            if (is_array($term) && isset($term['id'])) {
                $buzzIds[(string) $term['id']] = true;
            }
        }
    }

    $core = [];
    $buzz = [];
    foreach ($allTerms as $term) {
        if (! is_array($term) || ! isset($term['id'])) {
            continue;
        }
        $id = (string) $term['id'];
        if (isset($buzzIds[$id])) {
            $buzz[] = $term;
        } else {
            $core[] = $term;
        }
    }

    $out = $root.'/content/catalogs/glossary';
    write_json($out.'/meta.json', [
        'schemaVersion' => 1,
        'catalog' => 'glossary',
        'categories' => $categories,
    ]);
    write_json($out.'/terms-core.json', array_values($core));
    write_json($out.'/terms-buzzwords.json', array_values($buzz));

    echo 'glossary core='.count($core).' buzzwords='.count($buzz).' total='.count($allTerms)."\n";
}

if ($catalogFilter === null || $catalogFilter === 'suppliers') {
    export_suppliers($root);
}
if ($catalogFilter === null || $catalogFilter === 'glossary') {
    export_glossary($root);
}

echo "done\n";
