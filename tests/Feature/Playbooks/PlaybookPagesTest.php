<?php

namespace Tests\Feature\Playbooks;

use Tests\TestCase;

class PlaybookPagesTest extends TestCase
{
    public function test_playbook_index_lists_help_hub_story(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('Governance Help Hub');
        $response->assertDontSee('Snowflake Governance Playbook');
        $response->assertSee('data-playbook-card-title', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-overview-tag="help-hub"', false);
    }

    public function test_help_hub_platform_story_includes_repository_link(): void
    {
        $response = $this->get('/playbooks/help-hub-platform');

        $response->assertOk();
        $response->assertSee('Governance Help Hub');
        $response->assertSee('Help Hub', false);
        $response->assertDontSee('Plattform &amp; Download', false);
        $response->assertDontSee('Platform &amp; Download', false);
        $response->assertSee('playbook-detail__actions', false);
        $response->assertSee('tools-btn--primary', false);
        $response->assertSee('Git-Repo klonen', false);
        $response->assertSee('https://github.com/Ac1d0n3/binom-tools', false);
        $response->assertSee('Projektstruktur', false);
        $response->assertDontSee('Storybook Start', false);
        $response->assertDontSee('Starter-Template herunterladen', false);
    }

    public function test_playbook_detail_renders_localized_story_content(): void
    {
        $response = $this->get('/playbooks/help-hub-platform');

        $response->assertOk();
        $response->assertSee('Governance Help Hub');
        $response->assertSee('Projektstruktur');
        $response->assertSee('Project structure');
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
        $response->assertSee('data-playbook-toc-start', false);
        $response->assertSee('data-target-id="de-playbook-start"', false);
        $response->assertSee('data-i18n="playbooks.tocTitle"', false);
        $response->assertSee('id="toc-sub-de-story-hinzufugen"', false);
        $response->assertDontSee('data-playbook-restart', false);
    }

    public function test_playbook_toc_has_branches_and_leaves(): void
    {
        $html = $this->get('/playbooks/help-hub-platform')->getContent();

        preg_match_all('/data-playbook-toc-leaf/', $html, $leafMatches);
        preg_match_all('/data-playbook-toc-branch/', $html, $branchMatches);
        preg_match_all('/data-playbook-toc-group-toggle/', $html, $toggleMatches);

        $this->assertSame(16, count($leafMatches[0]), 'Expected eight leaf groups per locale panel');
        $this->assertSame(4, count($branchMatches[0]), 'Expected two branches per locale panel');
        $this->assertSame(count($toggleMatches[0]), count($branchMatches[0]));
    }

    public function test_unknown_playbook_returns_not_found(): void
    {
        $this->get('/playbooks/does-not-exist')->assertNotFound();
    }

    public function test_removed_snowflake_story_returns_not_found(): void
    {
        $this->get('/playbooks/snowflake-governance')->assertNotFound();
    }
}
