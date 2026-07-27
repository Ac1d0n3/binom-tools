<?php

namespace Tests\Feature\Governance;

use Tests\TestCase;

class GovernanceWwwLandingsTest extends TestCase
{
    public function test_advisor_landing_redirects_into_hub_tab(): void
    {
        $this->get('/governance/berater')
            ->assertRedirect('/governance?tab=advisor');
    }

    public function test_stacks_landing_redirects_into_guides_anchor(): void
    {
        $this->get('/governance/stacks')
            ->assertRedirect('/governance?tab=guides#stacks');
    }

    public function test_kpi_requirements_landing_redirects_into_guides_anchor(): void
    {
        $this->get('/governance/kpi-requirements')
            ->assertRedirect('/governance?tab=guides#kpi');
    }

    public function test_supplier_discovery_landing_redirects_into_guides_anchor(): void
    {
        $this->get('/governance/supplier-discovery')
            ->assertRedirect('/governance?tab=guides#supplier');
    }

    public function test_discovery_canvas_redirects_into_hub_tab(): void
    {
        $this->get('/governance/discovery-canvas')
            ->assertRedirect('/governance?tab=canvas');
    }

    public function test_hub_tab_query_exposes_canvas_and_guides_content(): void
    {
        $response = $this->get('/governance?tab=canvas');

        $response->assertOk();
        $response->assertSee('data-governance-initial-tab="canvas"', false);
        $response->assertSee('data-governance-discovery-canvas', false);
        $response->assertSee('data-discovery-step="stakeholders"', false);
        $response->assertSee('data-discovery-step="decision"', false);
        $response->assertSee('data-discovery-copy-md', false);
        $response->assertSee('data-discovery-copy-json', false);

        $guides = $this->get('/governance?tab=guides');
        $guides->assertOk();
        $guides->assertSee('data-governance-initial-tab="guides"', false);
        $guides->assertSee('id="guides-stacks"', false);
        $guides->assertSee('Modern Data Stack', false);
        $guides->assertSee('Microsoft Fabric', false);
        $guides->assertSee('Start with these 3 tools', false);
        $guides->assertSee(route('tools.kpi-requirements-intake'), false);
    }

    public function test_legacy_tab_aliases_resolve_to_guides(): void
    {
        $this->get('/governance?tab=stacks')
            ->assertOk()
            ->assertSee('data-governance-initial-tab="guides"', false)
            ->assertSee('data-governance-initial-fragment="stacks"', false);
    }

    public function test_localized_landings_redirect(): void
    {
        $this->get('/de/governance/berater')->assertRedirect('/de/governance?tab=advisor');
        $this->get('/de/governance/discovery-canvas')->assertRedirect('/de/governance?tab=canvas');
    }

    public function test_search_is_noindex(): void
    {
        $this->get('/search')->assertOk()->assertSee('name="robots" content="noindex,follow"', false);
    }
}
