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
        $response->assertDontSee('data-overview-filter-root', false);
    }

    public function test_tools_overview_lists_workflow_examples_with_search(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();
        $response->assertSee('Governance AI Sanitizer');
        $response->assertSee('data-overview-filter-root', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-overview-item', false);
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
