<?php

namespace App\Catalog;

use RuntimeException;

/**
 * Load repo catalog JSON from content/catalogs/{name}/.
 * Source of truth is always files (cloneable); optional DB sync is separate.
 *
 * Loader types (meta.json "loader"):
 * - suppliers / glossary — specialized merges
 * - document — single document.json merged with meta (default for new catalogs)
 * - files — named JSON files listed in meta.files (key => relative path)
 */
final class CatalogJsonLoader
{
    private static ?array $cache = null;

    public static function catalogRoot(): string
    {
        if (function_exists('base_path')) {
            try {
                return base_path('content/catalogs');
            } catch (\Throwable) {
                // Fall through when container is not ready.
            }
        }

        return dirname(__DIR__, 2).'/content/catalogs';
    }

    /**
     * @return array<string, mixed>
     */
    public static function load(string $catalog): array
    {
        self::$cache ??= [];

        if (isset(self::$cache[$catalog])) {
            return self::$cache[$catalog];
        }

        $dir = self::catalogRoot().'/'.$catalog;
        if (! is_dir($dir)) {
            throw new RuntimeException("Catalog directory missing: content/catalogs/{$catalog}");
        }

        $meta = self::readJsonFile($dir.'/meta.json');
        $schemaVersion = (int) ($meta['schemaVersion'] ?? 0);
        if ($schemaVersion < 1) {
            throw new RuntimeException("Catalog {$catalog} meta.json requires schemaVersion >= 1");
        }

        $loader = (string) ($meta['loader'] ?? $catalog);

        $payload = match ($loader) {
            'suppliers' => self::loadSuppliers($dir, $meta),
            'glossary' => self::loadGlossary($dir, $meta),
            'document' => self::loadDocument($dir, $meta),
            'files' => self::loadFiles($dir, $meta),
            default => throw new RuntimeException("Unknown catalog loader \"{$loader}\" for {$catalog}"),
        };

        return self::$cache[$catalog] = $payload;
    }

    public static function clearCache(): void
    {
        self::$cache = null;
    }

    /**
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    private static function loadSuppliers(string $dir, array $meta): array
    {
        $products = self::readJsonFile($dir.'/products.json');
        if (! array_is_list($products)) {
            throw new RuntimeException('suppliers/products.json must be a list');
        }

        $governance = self::readJsonFile($dir.'/governance.json');
        $quality = self::readJsonFile($dir.'/quality.json');
        $sql = self::readJsonFile($dir.'/sql.json');

        $merged = array_map(static function (array $product) use ($governance, $quality, $sql): array {
            $id = (string) ($product['id'] ?? '');
            if ($id === '') {
                return $product;
            }

            if (isset($governance[$id]) && is_array($governance[$id])) {
                $product = array_merge($product, $governance[$id]);
            }

            if (isset($quality[$id]) && is_array($quality[$id])) {
                $overlay = $quality[$id];
                $appendTools = is_array($overlay['tools'] ?? null) ? $overlay['tools'] : [];
                unset($overlay['tools']);
                $product = array_merge($product, $overlay);

                $tools = is_array($product['tools'] ?? null) ? $product['tools'] : [];
                foreach ($appendTools as $toolId) {
                    if (! is_string($toolId) || $toolId === '') {
                        continue;
                    }
                    if (! in_array($toolId, $tools, true)) {
                        $tools[] = $toolId;
                    }
                }
                $product['tools'] = $tools;
            }

            if (isset($sql[$id]) && is_array($sql[$id])) {
                $product = array_merge($product, $sql[$id]);
            }

            return $product;
        }, $products);

        self::assertUniqueIds($merged, 'suppliers products');

        return [
            'domains' => is_array($meta['domains'] ?? null) ? $meta['domains'] : [],
            'products' => $merged,
            'schemaVersion' => (int) $meta['schemaVersion'],
        ];
    }

    /**
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    private static function loadGlossary(string $dir, array $meta): array
    {
        $core = self::readJsonFile($dir.'/terms-core.json');
        $buzz = self::readJsonFile($dir.'/terms-buzzwords.json');
        if (! array_is_list($core) || ! array_is_list($buzz)) {
            throw new RuntimeException('glossary terms JSON files must be lists');
        }

        $terms = array_merge($core, $buzz);
        self::assertUniqueIds($terms, 'glossary terms');

        return [
            'categories' => is_array($meta['categories'] ?? null) ? $meta['categories'] : [],
            'terms' => $terms,
            'schemaVersion' => (int) $meta['schemaVersion'],
        ];
    }

    /**
     * Load document.json and merge meta fields (except loader/catalog bookkeeping).
     *
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    private static function loadDocument(string $dir, array $meta): array
    {
        $document = self::readJsonFile($dir.'/document.json');
        $metaPublic = self::publicMeta($meta);

        return array_merge($metaPublic, $document, [
            'schemaVersion' => (int) $meta['schemaVersion'],
        ]);
    }

    /**
     * Load named files from meta.files: { "roles": "roles.json", ... }.
     *
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    private static function loadFiles(string $dir, array $meta): array
    {
        $files = $meta['files'] ?? null;
        if (! is_array($files) || $files === []) {
            throw new RuntimeException('files loader requires meta.files map');
        }

        $payload = self::publicMeta($meta);
        foreach ($files as $key => $relative) {
            if (! is_string($key) || $key === '' || ! is_string($relative) || $relative === '') {
                throw new RuntimeException('files loader: invalid files map entry');
            }
            if (str_contains($relative, '..') || str_starts_with($relative, '/')) {
                throw new RuntimeException("files loader: unsafe path {$relative}");
            }
            $payload[$key] = self::readJsonFile($dir.'/'.$relative);
        }

        $payload['schemaVersion'] = (int) $meta['schemaVersion'];

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    private static function publicMeta(array $meta): array
    {
        $skip = ['schemaVersion' => true, 'catalog' => true, 'loader' => true, 'files' => true];
        $out = [];
        foreach ($meta as $key => $value) {
            if (isset($skip[$key])) {
                continue;
            }
            $out[$key] = $value;
        }

        return $out;
    }

    /**
     * @return array<mixed>
     */
    private static function readJsonFile(string $path): array
    {
        if (! is_file($path)) {
            throw new RuntimeException("Catalog JSON missing: {$path}");
        }

        $raw = file_get_contents($path);
        if ($raw === false || $raw === '') {
            throw new RuntimeException("Catalog JSON empty: {$path}");
        }

        try {
            $decoded = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException("Invalid JSON in {$path}: ".$e->getMessage(), 0, $e);
        }

        if (! is_array($decoded)) {
            throw new RuntimeException("Catalog JSON must decode to array: {$path}");
        }

        return $decoded;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    private static function assertUniqueIds(array $items, string $label): void
    {
        $seen = [];
        foreach ($items as $item) {
            $id = (string) ($item['id'] ?? '');
            if ($id === '') {
                throw new RuntimeException("{$label}: entry missing id");
            }
            if (isset($seen[$id])) {
                throw new RuntimeException("{$label}: duplicate id {$id}");
            }
            $seen[$id] = true;
        }
    }
}
