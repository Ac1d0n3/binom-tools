<?php

namespace Tests\Unit\Catalog;

use App\Catalog\SearchIndex;
use Tests\TestCase;

class SearchIndexTest extends TestCase
{
    public function test_index_includes_core_hub_types(): void
    {
        $index = app(SearchIndex::class);
        $entries = $index->all();
        $types = array_values(array_unique(array_map(
            static fn (array $entry): string => (string) ($entry['type'] ?? ''),
            $entries,
        )));

        foreach (['story', 'series', 'tool', 'resource', 'supplier', 'compliance', 'radar', 'glossary', 'path'] as $type) {
            $this->assertContains($type, $types);
        }

        $this->assertGreaterThan(100, count($entries));
    }

    public function test_search_finds_glossary_and_story_terms(): void
    {
        $index = app(SearchIndex::class);

        $piiHits = $index->search('PII');
        $this->assertNotEmpty($piiHits);
        $this->assertTrue(collect($piiHits)->contains(
            static fn (array $hit): bool => ($hit['type'] ?? '') === 'glossary' && ($hit['id'] ?? '') === 'pii',
        ));

        $lineageHits = $index->search('lineage', 'glossary');
        $this->assertNotEmpty($lineageHits);
        $this->assertSame('glossary', $lineageHits[0]['type']);
    }
}
