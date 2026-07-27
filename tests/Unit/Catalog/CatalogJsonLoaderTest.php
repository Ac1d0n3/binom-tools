<?php

namespace Tests\Unit\Catalog;

use App\Catalog\CatalogJsonLoader;
use Tests\TestCase;

class CatalogJsonLoaderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        CatalogJsonLoader::clearCache();
    }

    public function test_suppliers_catalog_loads_unique_products(): void
    {
        $catalog = CatalogJsonLoader::load('suppliers');

        $this->assertSame(1, $catalog['schemaVersion'] ?? null);
        $this->assertNotEmpty($catalog['domains'] ?? []);
        $products = $catalog['products'] ?? [];
        $this->assertIsArray($products);
        $this->assertGreaterThanOrEqual(50, count($products));

        $ids = array_map(static fn (array $p): string => (string) ($p['id'] ?? ''), $products);
        $this->assertSame(count($ids), count(array_unique($ids)));
        $this->assertContains('salesforce', $ids);
    }

    public function test_glossary_catalog_loads_unique_terms(): void
    {
        $catalog = CatalogJsonLoader::load('glossary');

        $this->assertSame(1, $catalog['schemaVersion'] ?? null);
        $this->assertArrayHasKey('roles', $catalog['categories'] ?? []);
        $terms = $catalog['terms'] ?? [];
        $this->assertGreaterThanOrEqual(700, count($terms));

        $ids = array_map(static fn (array $t): string => (string) ($t['id'] ?? ''), $terms);
        $this->assertSame(count($ids), count(array_unique($ids)));
        $this->assertContains('data-steward', $ids);
    }

    public function test_config_facades_match_loader(): void
    {
        $this->assertSame(
            count(CatalogJsonLoader::load('suppliers')['products']),
            count(config('suppliers.products'))
        );
        $this->assertSame(
            count(CatalogJsonLoader::load('glossary')['terms']),
            count(config('glossary.terms'))
        );
    }

    public function test_document_catalogs_load(): void
    {
        foreach (['roles', 'learning-paths', 'vendor-resources', 'compliance', 'governance-radar', 'tools'] as $name) {
            $catalog = CatalogJsonLoader::load($name);
            $this->assertSame(1, $catalog['schemaVersion'] ?? null, $name);
            $this->assertIsArray($catalog);
            $this->assertGreaterThan(2, count($catalog), $name);
        }

        $this->assertNotEmpty(config('roles.roles'));
        $this->assertNotEmpty(config('compliance.items'));
        $this->assertNotEmpty(config('tools.workflows'));
        $this->assertSame(config('tools.version'), env('BINOM_TOOLS_VERSION', '1.0.0'));
    }
}
