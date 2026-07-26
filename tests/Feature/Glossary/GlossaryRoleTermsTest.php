<?php

namespace Tests\Feature\Glossary;

use Tests\TestCase;

class GlossaryRoleTermsTest extends TestCase
{
    public function test_new_role_glossary_terms_are_reachable(): void
    {
        $this->get('/glossary/data-architect')
            ->assertOk()
            ->assertSee('Data Architect', false)
            ->assertSee(route('learning-paths.show', ['slug' => 'modernize-warehouse']), false);

        $this->get('/glossary/data-custodian')->assertOk()->assertSee('Data Custodian', false);
        $this->get('/glossary/data-consumer')->assertOk()->assertSee('Data Consumer', false);
    }
}
