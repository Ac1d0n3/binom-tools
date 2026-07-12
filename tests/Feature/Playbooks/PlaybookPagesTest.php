<?php

namespace Tests\Feature\Playbooks;

use Tests\TestCase;

class PlaybookPagesTest extends TestCase
{
    public function test_playbook_index_lists_help_hub_story(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('Bridge Solutions');
        $response->assertSee('Governance Help Hub');
        $response->assertSee('data-i18n="nav.stories"', false);
        $response->assertDontSee('data-i18n="playbooks.indexTitle"', false);
        $response->assertDontSee('data-i18n="playbooks.indexLead"', false);
        $response->assertDontSee('Snowflake Governance Playbook');
        $response->assertSee('data-playbook-card-title', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-tag-sidebar', false);
        $response->assertSee('tools-overview-layout--with-tags', false);
        $response->assertSee('data-overview-tag="help-hub"', false);
        $response->assertSee('data-overview-tag="bridge-solution"', false);
        $response->assertSee('data-overview-category="all"', false);
        $response->assertSee('data-overview-category="data-governance"', false);
        $response->assertSee('data-overview-tag-mode-toggle="or"', false);
        $response->assertSee('data-overview-tag-mode-toggle="and"', false);
        $response->assertSee('data-tag-match-mode="or"', false);
        $response->assertSee('tools-filter-sidebar__tag-mode-btn', false);
        $response->assertSee('data-overview-filter-reset', false);
        $response->assertSee('data-i18n="overview.filterTitle"', false);
        $response->assertSee('data-i18n="overview.tagsSectionTitle"', false);
        $response->assertSee('fa-filter', false);
        $response->assertSee('data-category-key="data-governance"', false);
        $response->assertSee('tools-tag-sidebar__count', false);
        $response->assertSee('data-tag-sidebar-toggle', false);
        $response->assertSee('data-overview-view-toggle="stories"', false);
        $response->assertSee('data-overview-view-toggle="series"', false);
        $response->assertSee('data-overview-series-item', false);
        $response->assertSee('tools-shell__main--overview', false);
        $response->assertSee('tools-overview-scroll', false);
        $response->assertDontSee('tools-overview-tags', false);

        $html = $response->getContent();
        $this->assertMatchesRegularExpression(
            '/tools-overview-main[\s\S]*?data-tag-sidebar-toggle[\s\S]*?data-tag-sidebar/',
            $html,
        );
    }

    public function test_playbook_index_can_show_overview_header_when_enabled(): void
    {
        config([
            'playbooks.overview.show_title' => true,
            'playbooks.overview.show_lead' => true,
        ]);

        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('data-i18n="playbooks.indexTitle"', false);
        $response->assertSee('data-i18n="playbooks.indexLead"', false);
    }

    public function test_playbook_index_tag_sidebar_lists_tags_by_usage_count(): void
    {
        $html = $this->get('/playbooks')->getContent();

        $governancePos = strpos($html, 'data-overview-tag="data-governance"');
        $bridgePos = strpos($html, 'data-overview-tag="bridge-solution"');

        $this->assertNotFalse($governancePos);
        $this->assertNotFalse($bridgePos);
        $this->assertLessThan($bridgePos, $governancePos, 'data-governance should rank above single-use tags.');

        $this->assertMatchesRegularExpression(
            '/data-overview-tag="data-governance"[\s\S]*?tools-tag-sidebar__count">\d+<\/span>/',
            $html,
        );
    }

    public function test_playbook_index_lists_bridge_solution_and_help_hub(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('Bridge Solutions');
        $response->assertSee('Governance Help Hub');
    }

    public function test_dbt_role_story_renders_playbook_images(): void
    {
        $response = $this->get('/playbooks/dbt-role');

        $response->assertOk();
        $response->assertSee('dbt-role-hero.png', false);
        $response->assertSee(asset('images/playbooks/dbt-role-img1-en.png'), false);
        $response->assertSee(asset('images/playbooks/dbt-role-img2-en.png'), false);
        $response->assertDontSee('images/stories/', false);
    }

    public function test_big_five_story_renders_localized_hero_and_diagram(): void
    {
        $response = $this->get('/playbooks/big-five');

        $response->assertOk();
        $response->assertSee('big-five-hero.png', false);
        $response->assertSee(asset('images/playbooks/big-five-de.png'), false);
        $response->assertSee(asset('images/playbooks/big-five-en.png'), false);
        $response->assertDontSee('images/stories/', false);
    }

    public function test_bridge_solution_story_renders_localized_hero_and_concept_framing(): void
    {
        $response = $this->get('/playbooks/bridge-solution');

        $response->assertOk();
        $response->assertSee('Bridge Solutions');
        $response->assertSee('Thomas Lindackers');
        $response->assertSee('itemprop="author"', false);
        $response->assertSee('data-i18n="playbooks.author"', false);
        $response->assertSee('Die vergessene Mitte');
        $response->assertSee('The forgotten middle ground');
        $response->assertSee('Konzept', false);
        $response->assertSee('concept', false);
        $response->assertSee('Data-Engineering-Team', false);
        $response->assertSee('data engineering team', false);
        $response->assertSee('bridge-hero.png', false);
        $response->assertSee(asset('images/playbooks/bridge-solution-de.png'), false);
        $response->assertSee(asset('images/playbooks/bridge-solution-en.png'), false);
        $response->assertDontSee('src="/images/playbooks/bridge-solution-de.png"', false);
        $response->assertDontSee('src="/images/playbooks/bridge-solution-en.png"', false);
        $response->assertSee('playbook-prose__figure', false);
        $response->assertSee('playbook-prose__image--diagram', false);
        $response->assertSee('Andere Wege zum Ziel');
        $response->assertSee('Other paths to the goal');
        $response->assertSee('binom-tools');
        $response->assertSee('/tools/dbt-dq-macro-generator', false);
        $response->assertSee('/playbooks/help-hub-platform', false);
        $response->assertSee('data-playbook-locale-panel="de"', false);
        $response->assertSee('data-playbook-locale-panel="en"', false);
    }

    public function test_eight_pillar_story_renders_series_navigation(): void
    {
        $response = $this->get('/playbooks/eight-pillars');

        $response->assertOk();
        $response->assertSee('playbook-series', false);
        $response->assertSee('Part 1 of 9', false);
        $response->assertSee('playbook-series__option--active', false);
        $response->assertSee('/playbooks/data-ownership-stewardship', false);
        $response->assertSee('/playbooks/metadata-catalog-lineage', false);
    }

    public function test_metadata_story_renders_series_navigation(): void
    {
        $response = $this->get('/playbooks/metadata-catalog-lineage');

        $response->assertOk();
        $response->assertSee('playbook-series', false);
        $response->assertSee('Part 3 of 9', false);
        $response->assertSee('/playbooks/eight-pillars', false);
        $response->assertSee('/playbooks/data-ownership-stewardship', false);
    }

    public function test_playbook_index_series_view_renders_series_card(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('tools-series-card', false);
        $response->assertSee('The 8 Pillars of Data Governance', false);
        $response->assertSee('eight-pillar-hero.png', false);
        $response->assertSee('/playbooks/eight-pillars', false);
        $response->assertSee('data-overview-view-panel="series"', false);
    }

    public function test_eight_pillar_story_renders_localized_diagram_images(): void
    {
        $response = $this->get('/playbooks/eight-pillars');

        $response->assertOk();
        $response->assertSee('Die 8 Säulen der Data Governance');
        $response->assertSee('The 8 Pillars of Data Governance');
        $response->assertSee(asset('images/playbooks/eight-pillar-hero.png'), false);
        $response->assertSee(asset('images/playbooks/eight-pillar-de.png'), false);
        $response->assertSee(asset('images/playbooks/eight-pillar-en.png'), false);
        $response->assertDontSee('eight-pillor', false);
    }

    public function test_data_ownership_stewardship_story_renders_localized_diagram_images(): void
    {
        $response = $this->get('/playbooks/data-ownership-stewardship');

        $response->assertOk();
        $response->assertSee('Data Ownership & Stewardship');
        $response->assertSee(asset('images/playbooks/data-ownership-stewardship-hero.png'), false);
        $response->assertSee(asset('images/playbooks/data-ownership-stewardship-de.png'), false);
        $response->assertSee(asset('images/playbooks/data-ownership-stewardship-en.png'), false);
        $response->assertDontSee('data-steward-', false);
        $response->assertSee('playbook-series', false);
        $response->assertSee('Part 2 of 9', false);
        $response->assertSee('/playbooks/eight-pillars', false);
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

    public function test_legacy_playbook_url_shows_english_panel_by_default(): void
    {
        $response = $this->get('/playbooks/eight-pillars');

        $response->assertOk();
        $html = $response->getContent();
        $this->assertStringContainsString('data-playbook-locale-panel="en"', $html);
        $this->assertStringContainsString('data-playbook-locale-panel="de"', $html);
        $this->assertMatchesRegularExpression(
            '/data-playbook-locale-panel="en"[^>]*>\s*<div class="playbook-detail__layout"/',
            $html,
        );
        $this->assertMatchesRegularExpression(
            '/playbook-detail__scroll[\s\S]*?<div class="playbook-detail__hero"/',
            $html,
        );
        $this->assertMatchesRegularExpression(
            '/data-playbook-locale-panel="de"[^>]*hidden[^>]*>/',
            $html,
        );
    }

    public function test_german_prefixed_playbook_url_shows_german_panel(): void
    {
        $response = $this->get('/de/playbooks/eight-pillars');

        $response->assertOk();
        $html = $response->getContent();
        $this->assertMatchesRegularExpression(
            '/data-playbook-locale-panel="de"[^>]*>\s*<div class="playbook-detail__layout"/',
            $html,
        );
        $this->assertMatchesRegularExpression(
            '/playbook-detail__scroll[\s\S]*?<div class="playbook-detail__hero"/',
            $html,
        );
        $this->assertMatchesRegularExpression(
            '/data-playbook-locale-panel="en"[^>]*hidden[^>]*>/',
            $html,
        );
        $response->assertSee('/de/playbooks/', false);
    }

    public function test_english_prefixed_playbook_url_shows_english_panel(): void
    {
        $response = $this->get('/en/playbooks/eight-pillars');

        $response->assertOk();
        $html = $response->getContent();
        $this->assertMatchesRegularExpression(
            '/data-playbook-locale-panel="en"[^>]*>\s*<div class="playbook-detail__layout"/',
            $html,
        );
        $this->assertMatchesRegularExpression(
            '/playbook-detail__scroll[\s\S]*?<div class="playbook-detail__hero"/',
            $html,
        );
        $this->assertMatchesRegularExpression(
            '/data-playbook-locale-panel="de"[^>]*hidden[^>]*>/',
            $html,
        );
    }

    public function test_german_playbook_index_links_use_de_prefix(): void
    {
        $response = $this->get('/de/playbooks');

        $response->assertOk();
        $response->assertSee('/de/playbooks/eight-pillars', false);
        $response->assertSee('/de/playbooks/bridge-solution', false);
        $response->assertDontSee('href="http://localhost/playbooks/eight-pillars"', false);
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
