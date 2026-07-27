<?php

namespace Tests\Feature\Roles;

use Tests\TestCase;

class RolesPagesTest extends TestCase
{
    public function test_roles_index_and_show_pages(): void
    {
        $index = $this->get('/roles');
        $index->assertOk();
        $index->assertSee('data-i18n="roles.indexTitle"', false);
        $index->assertSee('roles-hub-grid', false);
        $index->assertSee('roles-hub-card', false);
        $index->assertSee('roles-hub-card__icon-wrap', false);
        $index->assertSee('roles-hub-card__title', false);
        $index->assertSee('roles-hub-card__purpose', false);
        $index->assertSee('roles-hub-card__tag', false);
        $index->assertSee('roles.focusLabel', false);
        $index->assertSee('Definitions', false);
        $index->assertSee('DQ gates', false);
        $index->assertSee('Catalog care', false);
        $index->assertDontSee('roles-persona-bar', false);
        $index->assertDontSee('data-overview-search', false);
        $index->assertSee(route('roles.show', ['slug' => 'architect']), false);
        $index->assertSee('id="roles-bridges"', false);
        $index->assertSee('data-i18n="roles.bridgesTitle"', false);
        $index->assertSee('data-bridge-id="report-builder-model"', false);
        $index->assertSee('data-bridge-id="steward-architect"', false);
        $index->assertSee('data-bridge-id="product-owner-owner"', false);
        $index->assertSee('data-bridge-id="architect-interfaces"', false);
        $index->assertSee('data-bridge-id="custodian-architect"', false);
        $index->assertSee('Report / BI builder × model', false);
        $index->assertSee('Architect owns the interfaces', false);
        $index->assertSee('data-bridge-tone="caution"', false);
        $index->assertSee('data-bridge-tone="accountability"', false);
        $index->assertSee('tools-card--quote', false);
        $index->assertSee('tools-card__quote-binary', false);

        $show = $this->get('/roles/architect');
        $show->assertOk();
        $show->assertSee('Data Architect', false);
        $show->assertSee('tools-card--quote', false);
        $show->assertSee('tools-card__quote-binary', false);
        $show->assertSee('data-i18n="roles.boundariesTitle"', false);
        $show->assertSee('data-i18n="roles.ownsTitle"', false);
        $show->assertSee('data-i18n="roles.doesNotTitle"', false);
        $show->assertSee('data-i18n="roles.tasksTitle"', false);
        $show->assertSee('data-i18n="roles.worksWithTitle"', false);
        $show->assertSee('data-i18n="roles.relatedBridgesTitle"', false);
        $show->assertSee('data-bridge-id="architect-interfaces"', false);
        $show->assertSee('Architect owns the interfaces', false);
        $show->assertSee(route('roles.index').'#roles-bridges', false);
        $show->assertSee('data-i18n="roles.pathsTitle"', false);
        $show->assertSee('data-i18n="roles.toolsTitle"', false);
        $show->assertSee('data-i18n="roles.glossaryTitle"', false);
        $show->assertDontSee('roles.hubLinksTitle', false);
        $show->assertSee(route('learning-paths.show', ['slug' => 'modernize-warehouse']), false);
        $show->assertSee(route('tools.architecture-fit'), false);
        $show->assertSee(route('roles.show', ['slug' => 'steward']), false);
        $show->assertSee(route('glossary.show', ['slug' => 'data-architect']), false);
        $show->assertSee(route('playbooks.show', ['slug' => 'data-architect-role']), false);

        // Glossary is a secondary section — boundaries come before it in the HTML.
        $html = $show->getContent();
        $boundariesPos = strpos($html, 'id="roles-boundaries"');
        $glossaryPos = strpos($html, 'id="roles-glossary"');
        $bridgesPos = strpos($html, 'id="roles-related-bridges"');
        $worksWithPos = strpos($html, 'id="roles-works-with"');
        $this->assertNotFalse($boundariesPos);
        $this->assertNotFalse($glossaryPos);
        $this->assertNotFalse($bridgesPos);
        $this->assertNotFalse($worksWithPos);
        $this->assertLessThan($glossaryPos, $boundariesPos);
        $this->assertLessThan($bridgesPos, $worksWithPos);
        $this->assertLessThan($glossaryPos, $bridgesPos);

        $productOwner = $this->get('/roles/product-owner');
        $productOwner->assertOk();
        $productOwner->assertSee(route('glossary.show', ['slug' => 'data-product-owner']), false);
        $productOwner->assertSee('href="'.route('glossary.show', ['slug' => 'data-product-owner']).'"', false);
        $productOwner->assertSee('data-bridge-id="product-owner-owner"', false);
        $productOwner->assertSee('data-bridge-tone="caution"', false);

        $consumer = $this->get('/roles/consumer');
        $consumer->assertOk();
        $consumer->assertSee('data-bridge-id="report-builder-model"', false);
        $consumer->assertSee('Report / BI builder × model', false);
        $consumer->assertSee('semantic model', false);

        $this->get('/de/roles')->assertOk();
        $this->get('/en/roles/steward')->assertOk();
        $this->get('/roles/missing')->assertNotFound();
    }
}
