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
        $index->assertDontSee('roles-persona-bar', false);
        $index->assertDontSee('data-overview-search', false);
        $index->assertSee(route('roles.show', ['slug' => 'architect']), false);

        $show = $this->get('/roles/architect');
        $show->assertOk();
        $show->assertSee('Data Architect', false);
        $show->assertSee(route('glossary.show', ['slug' => 'data-architect']), false);
        $show->assertSee(route('playbooks.show', ['slug' => 'data-architect-role']), false);

        $this->get('/de/roles')->assertOk();
        $this->get('/en/roles/steward')->assertOk();
        $this->get('/roles/missing')->assertNotFound();
    }
}
