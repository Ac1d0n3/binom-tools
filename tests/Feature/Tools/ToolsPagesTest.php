<?php

namespace Tests\Feature\Tools;

use Tests\TestCase;

class ToolsPagesTest extends TestCase
{
    public function test_landing_page_shows_hero_tools_stories_and_ecosystem(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('tools-hero', false);
        $response->assertSee('data-i18n="home.toolsTitle"', false);
        $response->assertSee('data-i18n="home.storiesTitle"', false);
        $response->assertSee('Governance AI Sanitizer');
        $response->assertSee('tools-card--overview', false);
        $response->assertSee(route('tools.overview'), false);
        $response->assertSee(route('playbooks.index'), false);
        $response->assertSee('binom-ngx', false);
        $response->assertSee('https://ngx-docs.binom.net', false);
        $response->assertDontSee('http://localhost:4200', false);
        $response->assertSee('Git-Repo klonen', false);
        $response->assertSee('https://github.com/Ac1d0n3/binom-tools', false);
        $response->assertSee('fa-brands fa-github', false);
        $response->assertDontSee('data-overview-filter-root', false);
    }

    public function test_tools_overview_lists_workflow_examples_with_search(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();
        $response->assertSee('Governance AI Sanitizer');
        $response->assertSee('Governance Macro Generator');
        $response->assertSee('Unreviewed Table Gate Generator');
        $response->assertSee('PII Recommend Generator');
        $response->assertSee('tools-workflow-section', false);
        $response->assertSee('data-overview-filter-root', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-overview-item', false);
    }

    public function test_governance_macro_generator_page_renders(): void
    {
        $response = $this->get('/tools/dbt-governance-macro-generator');

        $response->assertOk();
        $response->assertSee('dbt-governance-macro-generator-app', false);
        $response->assertSee('tools-workflow-flowchart', false);
    }

    public function test_unreviewed_gate_generator_page_renders(): void
    {
        $response = $this->get('/tools/pii-unreviewed-gate-generator');

        $response->assertOk();
        $response->assertSee('pii-unreviewed-gate-generator-app', false);
        $response->assertSee('tools-workflow-flowchart', false);
    }

    public function test_pii_recommend_generator_page_renders(): void
    {
        $response = $this->get('/tools/pii-recommend-generator');

        $response->assertOk();
        $response->assertSee('pii-recommend-generator-app', false);
        $response->assertSee('tools-workflow-flowchart', false);
        $response->assertSee('rec-name-rules-body', false);
        $response->assertSee('rec-content-rules-body', false);
    }

    public function test_sidebar_includes_home_link(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('data-i18n="nav.home"', false);
        $response->assertSee(route('tools.landing'), false);
        $response->assertSee(route('tools.overview'), false);
    }
}
