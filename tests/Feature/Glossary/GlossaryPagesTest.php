<?php

namespace Tests\Feature\Glossary;

use Tests\TestCase;

class GlossaryPagesTest extends TestCase
{
    public function test_glossary_index_and_show_pages(): void
    {
        $index = $this->get('/glossary');
        $index->assertOk();
        $index->assertSee('data-i18n="glossary.indexTitle"', false);
        $index->assertSee('glossary-hub-grid', false);
        $index->assertSee('/glossary/data-steward', false);
        $index->assertSee('data-glossary-az-panel', false);
        $index->assertSee('data-glossary-az-toggle', false);
        $index->assertSee('data-glossary-letter="all"', false);
        $index->assertSee('data-letter-en=', false);
        $index->assertSee('data-overview-result-count', false);
        $index->assertSee('tools-overview-count-badge', false);

        $show = $this->get('/glossary/dsdr');
        $show->assertOk();
        $show->assertSee('DSDR', false);
        $show->assertSee('glossary-detail__related', false);

        $this->get('/de/glossary')->assertOk();
        $this->get('/en/glossary/pii')->assertOk();
        $this->get('/glossary/does-not-exist')->assertNotFound();
    }

    public function test_glossary_has_expanded_vocabulary_and_new_categories(): void
    {
        /** @var list<array<string, mixed>> $terms */
        $terms = config('glossary.terms', []);
        $this->assertGreaterThanOrEqual(100, count($terms));

        /** @var array<string, array{de: string, en: string}> $categories */
        $categories = config('glossary.categories', []);
        foreach (['architecture', 'modeling', 'security', 'ai'] as $categoryId) {
            $this->assertArrayHasKey($categoryId, $categories);
        }

        $index = $this->get('/glossary');
        $index->assertOk();
        $index->assertSee('value="architecture"', false);
        $index->assertSee('value="modeling"', false);
        $index->assertSee('value="security"', false);
        $index->assertSee('value="ai"', false);
        $index->assertSee('/glossary/medallion-architecture', false);
        $index->assertSee('/glossary/semantic-layer', false);
        $index->assertSee('/glossary/rag', false);
    }

    public function test_new_buzzword_glossary_terms_are_reachable(): void
    {
        $this->get('/glossary/medallion-architecture')
            ->assertOk()
            ->assertSee('Medallion Architecture', false);

        $this->get('/glossary/semantic-layer')
            ->assertOk()
            ->assertSee('Semantic Layer', false);

        $this->get('/glossary/rag')
            ->assertOk()
            ->assertSee('RAG', false);

        $this->get('/glossary/data-observability')
            ->assertOk()
            ->assertSee('Data Observability', false);

        $this->get('/glossary/business-glossary')
            ->assertOk()
            ->assertSee('Business Glossary', false);

        $this->get('/de/glossary/lakehouse')->assertOk();
        $this->get('/en/glossary/scd2')->assertOk();
    }
}
