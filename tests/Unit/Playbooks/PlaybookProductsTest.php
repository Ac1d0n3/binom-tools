<?php

namespace Tests\Unit\Playbooks;

use App\Playbooks\PlaybookProducts;
use PHPUnit\Framework\TestCase;

class PlaybookProductsTest extends TestCase
{
    public function test_normalize_id_maps_aliases(): void
    {
        $this->assertSame('qlik', PlaybookProducts::normalizeId('qlik-sense'));
        $this->assertSame('powerbi', PlaybookProducts::normalizeId('Power BI'));
        $this->assertSame('fabric', PlaybookProducts::normalizeId('microsoft-fabric'));
        $this->assertSame('pureview', PlaybookProducts::normalizeId('microsoft-purview'));
        $this->assertNull(PlaybookProducts::normalizeId('pii'));
    }

    public function test_resolve_prefers_explicit_products_over_tags(): void
    {
        $this->assertSame(
            ['snowflake', 'dbt', 'qlik'],
            PlaybookProducts::resolve(['snowflake', 'dbt', 'qlik'], ['databricks', 'fabric']),
        );
    }

    public function test_resolve_falls_back_to_known_tags(): void
    {
        $this->assertSame(
            ['dbt', 'qlik', 'fabric'],
            PlaybookProducts::resolve([], ['dbt', 'qlik-sense', 'fabric-lakehouse', 'pii']),
        );
    }

    public function test_union_sorts_and_deduplicates(): void
    {
        $this->assertSame(
            ['snowflake', 'dbt', 'qlik'],
            PlaybookProducts::union(['qlik', 'dbt'], ['snowflake', 'dbt'], []),
        );
    }
}
