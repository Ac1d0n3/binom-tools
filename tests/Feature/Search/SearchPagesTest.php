<?php

namespace Tests\Feature\Search;

use Tests\TestCase;

class SearchPagesTest extends TestCase
{
    public function test_search_page_renders_and_returns_results(): void
    {
        $empty = $this->get('/search');
        $empty->assertOk();
        $empty->assertSee('data-i18n="search.indexTitle"', false);
        $empty->assertSee('tools-header__search', false);

        $response = $this->get('/search?q=PII');
        $response->assertOk();
        $response->assertSee('search-hub-results', false);
        $response->assertSee('PII', false);
        $response->assertSee(route('glossary.show', ['slug' => 'pii']), false);

        $this->get('/de/search?q=PII')->assertOk();
        $this->get('/en/search?q=lineage&type=glossary')->assertOk()
            ->assertSee('Lineage', false);
    }
}
