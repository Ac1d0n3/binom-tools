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
        $response->assertSee('AI Sanitizer');
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
        $response->assertSee('AI Sanitizer');
        $response->assertSee('Macro Generator');
        $response->assertSee('Table Gate');
        $response->assertSee('Recommend Generator');
        $response->assertSee('Security &amp; governance setup', false);
        $response->assertSee('Data quality setup');
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

    public function test_dq_macro_generator_page_renders(): void
    {
        $response = $this->get('/tools/dbt-dq-macro-generator');

        $response->assertOk();
        $response->assertSee('dbt-dq-macro-generator-app', false);
        $response->assertSee('tools-workflow-flowchart', false);
        $response->assertSee('workflow.setupLabel.dbt-dq-governance', false);
    }

    public function test_dq_rules_generator_page_renders(): void
    {
        $response = $this->get('/tools/dbt-dq-rules-generator');

        $response->assertOk();
        $response->assertSee('tools-content tools-content--wide', false);
        $response->assertSee('dbt-dq-rules-generator-app', false);
        $response->assertSee('dq-rules-columns-root', false);
        $response->assertSee('dq-rules-source-table', false);
        $response->assertSee('tools-column-accordion', false);
        $response->assertSee('dq-rules-governance-pre', false);
        $response->assertSee('dq-rules-dq-rule-pre', false);
        $response->assertSee('dqRules.model.sourceTable', false);
    }

    public function test_workflow_tools_use_generator_page_shell(): void
    {
        $response = $this->get('/tools/dbt-governance-macro-generator');

        $response->assertOk();
        $response->assertSee('tools-content tools-content--wide', false);
        $response->assertSee('dbt-governance-macro-generator-app', false);
        $response->assertSee('data-i18n="govMacro.pageTitle"', false);
    }

    public function test_dq_history_generator_page_renders(): void
    {
        $response = $this->get('/tools/dbt-dq-history-generator');

        $response->assertOk();
        $response->assertSee('dbt-dq-history-generator-app', false);
        $response->assertSee('dq-history-pre', false);
    }

    public function test_sidebar_includes_home_link(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('data-i18n="nav.home"', false);
        $response->assertSee(route('tools.landing'), false);
        $response->assertSee(route('tools.overview'), false);
    }

    public function test_sidebar_orders_workflow_tools_with_chain_icons_and_short_labels(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();

        $toolsNav = (string) str($response->getContent())
            ->after('data-i18n="nav.tools">Tools</p>')
            ->before('</aside>');

        $this->assertStringContainsString('data-i18n-nav="dbt-governance-macro-generator"', $toolsNav);
        $this->assertStringContainsString('data-i18n-nav="dbt-dq-history-generator"', $toolsNav);
        $this->assertLessThan(
            strpos($toolsNav, 'data-i18n-nav="dbt-dq-macro-generator"'),
            strpos($toolsNav, 'data-i18n-nav="pii-recommend-generator"'),
        );

        $response->assertSee('tools-sidenav__link-icon', false);
        $response->assertSee('fa-shield-halved', false);
        $response->assertSee('fa-table', false);
        $this->assertStringNotContainsString('tools-sidenav__step-num', $toolsNav);
        $this->assertStringNotContainsString('1/4', $toolsNav);
        $this->assertStringNotContainsString('1/3', $toolsNav);
        $this->assertStringNotContainsString('>DQ ', $toolsNav);
    }
}
