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

        $show = $this->get('/glossary/dsdr');
        $show->assertOk();
        $show->assertSee('DSDR', false);
        $show->assertSee('glossary-detail__related', false);

        $this->get('/de/glossary')->assertOk();
        $this->get('/en/glossary/pii')->assertOk();
        $this->get('/glossary/does-not-exist')->assertNotFound();
    }
}
