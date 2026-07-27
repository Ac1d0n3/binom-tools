<?php

namespace App\Catalog;

use App\Models\BnTools\BnCatalogDocument;
use App\Support\StorageDriver;
use RuntimeException;

/**
 * Optional MySQL cache of repo catalog JSON. File JSON remains source of truth.
 */
final class CatalogSyncService
{
    /**
     * @return list<array{catalog: string, facet: string, path: string, bytes: int, checksum: string}>
     */
    public function listDocuments(?string $catalogFilter = null): array
    {
        $root = CatalogJsonLoader::catalogRoot();
        if (! is_dir($root)) {
            return [];
        }

        $docs = [];
        foreach (scandir($root) ?: [] as $catalog) {
            if ($catalog === '.' || $catalog === '..') {
                continue;
            }
            if ($catalogFilter !== null && $catalog !== $catalogFilter) {
                continue;
            }
            $dir = $root.'/'.$catalog;
            if (! is_dir($dir)) {
                continue;
            }
            foreach (scandir($dir) ?: [] as $file) {
                if (! str_ends_with($file, '.json')) {
                    continue;
                }
                $path = $dir.'/'.$file;
                $raw = file_get_contents($path);
                if ($raw === false) {
                    continue;
                }
                $facet = basename($file, '.json');
                $docs[] = [
                    'catalog' => $catalog,
                    'facet' => $facet,
                    'path' => $path,
                    'bytes' => strlen($raw),
                    'checksum' => hash('sha256', $raw),
                    'payload' => json_decode($raw, true),
                ];
            }
        }

        return $docs;
    }

    /**
     * @return array{synced: int, skipped: int, catalogs: list<string>}
     */
    public function sync(?string $catalogFilter = null, bool $dryRun = false): array
    {
        if (! StorageDriver::isMysql() && ! $dryRun) {
            throw new RuntimeException('Catalog sync to DB requires BINOM_TOOLS_STORAGE_DRIVER=mysql (or use --dry-run).');
        }

        $docs = $this->listDocuments($catalogFilter);
        $synced = 0;
        $skipped = 0;
        $catalogs = [];

        foreach ($docs as $doc) {
            $catalogs[$doc['catalog']] = true;
            if ($doc['payload'] === null) {
                $skipped++;
                continue;
            }
            if ($dryRun) {
                $synced++;
                continue;
            }

            BnCatalogDocument::query()->updateOrCreate(
                [
                    'catalog' => $doc['catalog'],
                    'facet' => $doc['facet'],
                ],
                [
                    'checksum' => $doc['checksum'],
                    'payload' => $doc['payload'],
                ]
            );
            $synced++;
        }

        CatalogJsonLoader::clearCache();

        return [
            'synced' => $synced,
            'skipped' => $skipped,
            'catalogs' => array_keys($catalogs),
        ];
    }
}
