<?php

namespace App\Console\Commands;

use App\Catalog\CatalogSyncService;
use Illuminate\Console\Command;

class CatalogSyncCommand extends Command
{
    protected $signature = 'bn-tools:catalog-sync
        {--catalog= : Only sync one catalog (suppliers|glossary)}
        {--dry-run : List documents without writing to MySQL}';

    protected $description = 'Sync content/catalogs JSON into bn_catalog_documents (MySQL cache; files stay source of truth)';

    public function handle(CatalogSyncService $sync): int
    {
        $catalog = $this->option('catalog');
        $catalog = is_string($catalog) && $catalog !== '' ? $catalog : null;
        $dryRun = (bool) $this->option('dry-run');

        try {
            $result = $sync->sync($catalog, $dryRun);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        $prefix = $dryRun ? '[dry-run] ' : '';
        $this->info($prefix.'synced='.$result['synced'].' skipped='.$result['skipped'].' catalogs='.implode(',', $result['catalogs']));

        return self::SUCCESS;
    }
}
