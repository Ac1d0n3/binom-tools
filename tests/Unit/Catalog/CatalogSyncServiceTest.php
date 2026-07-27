<?php

namespace Tests\Unit\Catalog;

use App\Catalog\CatalogSyncService;
use Tests\TestCase;

class CatalogSyncServiceTest extends TestCase
{
    public function test_list_documents_finds_suppliers_and_glossary(): void
    {
        $docs = app(CatalogSyncService::class)->listDocuments();
        $facets = collect($docs)->map(fn (array $d): string => $d['catalog'].'/'.$d['facet'])->all();

        $this->assertContains('suppliers/products', $facets);
        $this->assertContains('suppliers/meta', $facets);
        $this->assertContains('glossary/terms-core', $facets);
        $this->assertContains('glossary/terms-buzzwords', $facets);
    }

    public function test_dry_run_sync_counts_documents(): void
    {
        $result = app(CatalogSyncService::class)->sync(null, true);
        $this->assertGreaterThanOrEqual(7, $result['synced']);
        $this->assertContains('suppliers', $result['catalogs']);
        $this->assertContains('glossary', $result['catalogs']);
    }
}
