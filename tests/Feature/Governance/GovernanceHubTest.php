<?php

namespace Tests\Feature\Governance;

use Tests\TestCase;

class GovernanceHubTest extends TestCase
{
    public function test_governance_hub_connects_existing_hubs_and_tools(): void
    {
        $response = $this->get('/governance');

        $response->assertOk();
        $response->assertSee('Governance Hub');
        $response->assertSee('governance-hub', false);
        $response->assertSee('Governance control hub');
        $response->assertSee('Collect infos workflow');
        $response->assertSee('Entscheidungshilfen', false);
        $response->assertSee('Decision question');
        $response->assertSee('Helps with');
        $response->assertSee('Afterwards you have');
        $response->assertSee('Shortlist');
        $response->assertSee('Source scope');
        $response->assertSee('fact/dimension candidates');
        $response->assertSee('Risk backlog');
        $response->assertSee('Decides: load scope, PII/DSDR, skip, KPI candidates');
        $response->assertSee('KPI Definition Card');
        $response->assertSee('Supplier library');
        $response->assertSee('Resources stack filter');
        $response->assertSee('PII Policy');
        $response->assertSee('/resources', false);
        $response->assertSee('/suppliers', false);
        $response->assertSee('/tools/stakeholder-matrix', false);
        $response->assertSee('/tools/kpi-definition', false);
        $response->assertSee('/tools/pii-policy-generator', false);
        $response->assertSee('/compliance', false);
        $response->assertSee('data-text-de="Governance"', false);
        $response->assertSee('tools-sidenav__link--active', false);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('"@context":"https://schema.org"', false);
        $response->assertDontSee('__contextArgs', false);
        $response->assertSee('Thomas Lindackers');
    }
}
