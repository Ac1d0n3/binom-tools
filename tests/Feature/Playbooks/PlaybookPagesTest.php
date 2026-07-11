<?php

namespace Tests\Feature\Playbooks;

use Tests\TestCase;

class PlaybookPagesTest extends TestCase
{
    public function test_playbook_index_lists_snowflake_story(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('Governance Help Hub');
        $response->assertSee('Snowflake Governance Playbook');
        $response->assertDontSee('Microsoft Fabric Governance Playbook');
        $response->assertDontSee('Databricks Governance Playbook');
        $response->assertSee('data-playbook-card-title', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-overview-tag="snowflake"', false);
    }

    public function test_help_hub_platform_story_includes_download_cta(): void
    {
        $response = $this->get('/playbooks/help-hub-platform');

        $response->assertOk();
        $response->assertSee('Governance Help Hub');
        $response->assertSee('playbook-detail__download', false);
        $response->assertSee('/archive/refs/heads/main.zip', false);
        $response->assertSee('Projektstruktur', false);
    }

    public function test_playbook_detail_renders_localized_story_content(): void
    {
        $response = $this->get('/playbooks/snowflake-governance');

        $response->assertOk();
        $response->assertSee('Snowflake Governance Playbook');
        $response->assertSee('Datenplattform');
        $response->assertSee('Data Platform');
        $response->assertSee('data-playbook-locale-panel="de"', false);
        $response->assertSee('data-playbook-locale-panel="en"', false);
        $response->assertSee('id="de-uberblick"', false);
        $response->assertSee('id="en-overview"', false);
        $response->assertSee('playbook-code', false);
        $response->assertSee('data-playbook-scroll-root', false);
        $response->assertSee('playbook-detail__toc-rail', false);
        $response->assertSee('tools-shell__main--playbook', false);
        $response->assertSee('data-playbook-toc-branch', false);
        $response->assertSee('data-playbook-toc-leaf', false);
        $response->assertSee('data-playbook-toc-sublist', false);
        $response->assertSee('data-playbook-toc-group-toggle', false);
        $response->assertSee('playbook-toc__group--branch', false);
        $response->assertSee('playbook-toc__group--leaf', false);
        $response->assertSee('id="toc-sub-de-implementierungsbeispiel"', false);
        $response->assertDontSee('data-playbook-restart', false);
    }

    public function test_playbook_toc_has_one_leaf_without_toggle(): void
    {
        $html = $this->get('/playbooks/snowflake-governance')->getContent();

        preg_match_all('/data-playbook-toc-leaf/', $html, $leafMatches);
        preg_match_all('/data-playbook-toc-branch/', $html, $branchMatches);
        preg_match_all('/data-playbook-toc-group-toggle/', $html, $toggleMatches);

        $this->assertSame(2, count($leafMatches[0]), 'Expected one leaf per locale panel');
        $this->assertGreaterThanOrEqual(6, count($branchMatches[0]), 'Expected branches for sections with h3 children');
        $this->assertSame(count($toggleMatches[0]), count($branchMatches[0]));
    }

    public function test_unknown_playbook_returns_not_found(): void
    {
        $this->get('/playbooks/does-not-exist')->assertNotFound();
    }
}
