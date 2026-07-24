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
        $response->assertSee('data-i18n="home.aiTitle"', false);
        $response->assertSee('data-i18n="home.storiesTitle"', false);
        $response->assertSee('AI Sanitizer');
        $response->assertSee('Prompt Studio');
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

        $html = $response->getContent();
        $storiesPos = strpos($html, 'data-i18n="home.storiesTitle"');
        $aiPos = strpos($html, 'data-i18n="home.aiTitle"');
        $toolsPos = strpos($html, 'data-i18n="home.toolsTitle"');
        $this->assertNotFalse($storiesPos);
        $this->assertNotFalse($aiPos);
        $this->assertNotFalse($toolsPos);
        $this->assertLessThan($aiPos, $storiesPos);
        $this->assertLessThan($toolsPos, $aiPos);
        $response->assertSee('data-i18n="footer.about"', false);
        $response->assertSee('v0.1.0', false);
        $response->assertSee('tools-beta-badge', false);
        $response->assertDontSee('class="tools-about-body"', false);
    }

    public function test_footer_shows_release_meta_on_all_pages(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();
        $response->assertSee('tools-release-meta', false);
        $response->assertSee('v0.1.0', false);
        $response->assertSee('data-i18n="footer.about"', false);
    }

    public function test_tools_overview_lists_workflow_examples_with_search(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();
        $response->assertSee('AI Sanitizer');
        $response->assertSee('PII Macro Generator');
        $response->assertSee('DQ Macro Generator');
        $response->assertSee('Schema YML Editor');
        $response->assertSee('Meta Export Generator');
        $response->assertSee('PII Policy Generator');
        $response->assertSee('DQ Rules Generator');
        $response->assertSee('Fabric DQ Pattern Generator');
        $response->assertSee('Databricks DQ Pattern Generator');
        $response->assertSee('Fabric PII Governance Pattern Generator');
        $response->assertSee('Databricks PII Governance Pattern Generator');
        $response->assertSee('Fabric DQ Rule Generator');
        $response->assertSee('Fabric Notebook Snippet Generator');
        $response->assertSee('Fabric Pipeline Checklist Generator');
        $response->assertSee('Fabric Semantic Model Guardrails');
        $response->assertSee('Databricks DQ Expectation Generator');
        $response->assertSee('Databricks dbt-on-Databricks Generator');
        $response->assertSee('Unity Catalog Governance Generator');
        $response->assertSee('Delta Load / SCD Pattern Generator');
        $response->assertSee('PureView Scan Generator');
        $response->assertSee('PureView Classification Generator');
        $response->assertSee('PureView Glossary Generator');
        $response->assertSee('PureView Data Product Generator');
        $response->assertSee('Qlik Set Analysis Generator');
        $response->assertSee('tools-card__platform-mark', false);
        $response->assertSee('images/fabric-badge.svg', false);
        $response->assertSee('images/databricks-badge.svg', false);
        $response->assertSee('Fabric');
        $response->assertSee('Databricks');
        $response->assertSee('PII Table Gate');
        $response->assertSee('Recommend Generator');
        $response->assertSee('Security &amp; governance setup', false);
        $response->assertSee('Data quality setup');
        $response->assertSee('tools-workflow-section', false);
        $response->assertSee('data-overview-filter-root', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-overview-product', false);
        $response->assertSee('data-products="fabric"', false);
        $response->assertSee('data-products="databricks"', false);
        $response->assertSee('data-products="pureview"', false);
        $response->assertSee('data-products="qlik"', false);
        $response->assertSee('data-products="dbt"', false);
        $response->assertSee('data-overview-item', false);
        $response->assertSee('tools-overview-sticky-header', false);
        $response->assertSee('tools-shell__main--overview', false);
        $response->assertSee('tools-overview-scroll', false);
        $response->assertSee('tools-release-meta', false);
        $response->assertSee('v0.1.0', false);
        $response->assertDontSee('data-i18n="tools.overviewTitle"', false);
        $response->assertDontSee('data-i18n="tools.overviewLead"', false);
    }

    public function test_tools_overview_can_show_header_when_enabled(): void
    {
        config([
            'tools.overview.show_title' => true,
            'tools.overview.show_lead' => true,
        ]);

        $response = $this->get('/tools');

        $response->assertOk();
        $response->assertSee('data-i18n="tools.overviewTitle"', false);
        $response->assertSee('data-i18n="tools.overviewLead"', false);
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

    public function test_lakehouse_dq_pattern_generators_render(): void
    {
        $this->get('/tools/fabric-dq-pattern-generator')
            ->assertOk()
            ->assertSee('lakehouse-dq-pattern-generator-app', false)
            ->assertSee('lakehouse-dq-sql-pre', false)
            ->assertSee('lakehouseDq.fabric.pageTitle', false);

        $this->get('/tools/databricks-dq-pattern-generator')
            ->assertOk()
            ->assertSee('lakehouse-dq-pattern-generator-app', false)
            ->assertSee('lakehouse-dq-notebook-pre', false)
            ->assertSee('lakehouseDq.databricks.pageTitle', false);

        $this->get('/tools/fabric-pii-governance-pattern-generator')
            ->assertOk()
            ->assertSee('lakehouse-dq-pattern-generator-app', false)
            ->assertSee('lakehouse-dq-sql-pre', false)
            ->assertSee('lakehouseDq.fabricPii.pageTitle', false);

        $this->get('/tools/databricks-pii-governance-pattern-generator')
            ->assertOk()
            ->assertSee('lakehouse-dq-pattern-generator-app', false)
            ->assertSee('lakehouse-dq-notebook-pre', false)
            ->assertSee('lakehouseDq.databricksPii.pageTitle', false);

        foreach ([
            '/tools/fabric-dq-rule-generator' => 'lakehouseDq.fabricDqRule.pageTitle',
            '/tools/fabric-notebook-snippet-generator' => 'lakehouseDq.fabricNotebook.pageTitle',
            '/tools/fabric-pipeline-checklist-generator' => 'lakehouseDq.fabricPipeline.pageTitle',
            '/tools/fabric-semantic-model-guardrails' => 'lakehouseDq.fabricSemantic.pageTitle',
            '/tools/databricks-dq-expectation-generator' => 'lakehouseDq.databricksDqExpectation.pageTitle',
            '/tools/databricks-dbt-on-databricks-generator' => 'lakehouseDq.databricksDbt.pageTitle',
            '/tools/unity-catalog-governance-generator' => 'lakehouseDq.unityCatalog.pageTitle',
            '/tools/delta-load-scd-pattern-generator' => 'lakehouseDq.deltaScd.pageTitle',
        ] as $url => $titleKey) {
            $this->get($url)
                ->assertOk()
                ->assertSee('lakehouse-dq-pattern-generator-app', false)
                ->assertSee($titleKey, false);
        }
    }

    public function test_pureview_generators_render(): void
    {
        foreach ([
            '/tools/pureview-scan-generator' => 'pureview.scan.pageTitle',
            '/tools/pureview-classification-generator' => 'pureview.classification.pageTitle',
            '/tools/pureview-glossary-generator' => 'pureview.glossary.pageTitle',
            '/tools/pureview-data-product-generator' => 'pureview.dataProduct.pageTitle',
        ] as $url => $titleKey) {
            $this->get($url)
                ->assertOk()
                ->assertSee('pureview-generator-app', false)
                ->assertSee('pureview-json-pre', false)
                ->assertSee('pureview-mapping-pre', false)
                ->assertSee('pureview-runbook-pre', false)
                ->assertSee($titleKey, false)
                ->assertSee('pureview.howto.summary', false);
        }
    }

    public function test_qlik_set_analysis_generator_renders(): void
    {
        $this->get('/tools/qlik-set-analysis-generator')
            ->assertOk()
            ->assertSee('qlik-set-analysis-generator-app', false)
            ->assertSee('qlik-set-workbench-title', false)
            ->assertSee('qlik-set-help-toggle', false)
            ->assertSee('qlik-set-help-body', false)
            ->assertSee('qlikSet.help.show', false)
            ->assertSee('qlik-set-base-measure', false)
            ->assertSee('qlik-set-current-formula-pre', false)
            ->assertSee('qlikSet.formula.current', false)
            ->assertSee('qlikSet.functions.title', false)
            ->assertSee('data-qlik-function="Aggr(Sum([Sales]), [Region])"', false)
            ->assertSee('qlik-set-base-description', false)
            ->assertSee('qlik-set-csv-file', false)
            ->assertSee('qlik-set-fields-file', false)
            ->assertSee('qlik-set-vars-file', false)
            ->assertSee('qlik-set-import-modal-open', false)
            ->assertSee('qlik-set-import-modal', false)
            ->assertSee('qlik-set-import-modal-close', false)
            ->assertSee('qlikSet.catalog.rawDataHint', false)
            ->assertSee('qlik-set-field-options', false)
            ->assertSee('qlik-set-variable-use', false)
            ->assertSee('qlik-set-use-variable-base', false)
            ->assertSee('qlik-set-field-chips', false)
            ->assertSee('qlik-set-filter-dropzone', false)
            ->assertSee('qlik-set-set-var-name', false)
            ->assertSee('qlik-set-set-var-values', false)
            ->assertSee('qlik-set-add-set-variable', false)
            ->assertSee('qlikSet.setVars.title', false)
            ->assertSee('qlik-set-search-expression', false)
            ->assertSee('qlik-set-add-search-filter', false)
            ->assertSee('qlikSet.setSearch.title', false)
            ->assertSee('qlik-set-tree-preview', false)
            ->assertSee('qlikSet.tree.title', false)
            ->assertSee('qlikSet.filter.dropTitle', false)
            ->assertSee('qlik-set-hierarchy-dropzone', false)
            ->assertSee('qlik-set-hierarchy-levels', false)
            ->assertSee('qlik-set-hierarchy-pre', false)
            ->assertSee('qlikSet.hierarchy.title', false)
            ->assertSee('data-qlik-dropzone="formula"', false)
            ->assertSee('data-qlik-dropzone="hierarchy"', false)
            ->assertSee('data-qlik-kpi="yoyPct"', false)
            ->assertSee('data-qlik-help-tab="quick"', false)
            ->assertSee('qlik-set-measures-pre', false)
            ->assertSee('qlik-set-time-vars-pre', false)
            ->assertSee('qlikSet.masterItems.summary', false)
            ->assertSee('qlikSet.useCases.summary', false)
            ->assertSee('qlikSet.fields.summary', false);
    }

    public function test_meta_export_generator_page_renders(): void
    {
        $response = $this->get('/tools/meta-export-generator');

        $response->assertOk();
        $response->assertSee('meta-export-generator-app', false);
        $response->assertSee('meta-platform', false);
        $response->assertSee('meta-schemas-box', false);
        $response->assertSee('playbook-code', false);
        $response->assertSee('meta-access-box', false);
    }

    public function test_discovery_assessment_tools_render(): void
    {
        $this->get('/tools/stakeholder-matrix')
            ->assertOk()
            ->assertSee('stakeholder-matrix-app', false)
            ->assertSee('tools-workflow-flowchart', false)
            ->assertSee('data-discovery-table', false)
            ->assertSee('discovery-ephemeral-banner', false)
            ->assertSee('data-i18n="discovery.warnTitle"', false);

        $this->get('/tools/report-inventory')
            ->assertOk()
            ->assertSee('report-inventory-app', false)
            ->assertSee('data-copy-md', false)
            ->assertSee('data-download-md', false)
            ->assertSee('discovery.exportHint', false);

        $this->get('/tools/kpi-definition')
            ->assertOk()
            ->assertSee('kpi-definition-app', false);

        $this->get('/tools/architecture-fit')
            ->assertOk()
            ->assertSee('architecture-fit-app', false)
            ->assertSee('data-discovery-checklist', false)
            ->assertSee('discovery-ephemeral-banner', false);

        $this->get('/tools/impact-effort')
            ->assertOk()
            ->assertSee('impact-effort-app', false)
            ->assertSee('data-discovery-extra', false);
    }

    public function test_tools_overview_lists_discovery_workflow(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();
        $response->assertSee('Discovery &amp; assessment', false);
        $response->assertSee('Stakeholder &amp; RACI Matrix', false);
        $response->assertSee('Report Inventory Canvas', false);
        $response->assertSee('KPI Definition Card', false);
        $response->assertSee('Architecture Fit Checklist', false);
        $response->assertSee('Impact–Effort Prioritizer', false);
    }

    public function test_sidebar_includes_home_link(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('data-i18n="nav.home"', false);
        $response->assertSee(route('tools.landing'), false);
        $response->assertSee(route('tools.overview'), false);
    }

    public function test_sidebar_groups_tools_by_product_accordions(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();

        $toolsNav = (string) str($response->getContent())
            ->after('data-i18n="nav.tools">Governance</p>')
            ->before('</aside>');

        $this->assertStringContainsString('tools-sidenav__accordion', $toolsNav);
        $this->assertStringContainsString('tools-sidenav__accordion-input', $toolsNav);
        $this->assertStringContainsString('type="checkbox"', $toolsNav);
        $this->assertStringContainsString('data-sidenav-accordion="ai"', $toolsNav);
        $this->assertStringContainsString('data-sidenav-accordion="dbt"', $toolsNav);
        $this->assertStringContainsString('data-sidenav-accordion="fabric"', $toolsNav);
        $this->assertStringContainsString('data-sidenav-accordion="databricks"', $toolsNav);
        $this->assertStringContainsString('data-sidenav-accordion="pureview"', $toolsNav);
        $this->assertStringContainsString('data-sidenav-accordion="qlik"', $toolsNav);
        $this->assertLessThan(
            strpos($toolsNav, 'data-sidenav-accordion="dbt"'),
            strpos($toolsNav, 'data-sidenav-accordion="ai"'),
        );
        $this->assertLessThan(
            strpos($toolsNav, 'data-sidenav-accordion="fabric"'),
            strpos($toolsNav, 'data-sidenav-accordion="dbt"'),
        );

        $this->assertStringContainsString('data-i18n-nav="prompt-studio"', $toolsNav);
        $this->assertStringContainsString('data-i18n-nav="dbt-governance-macro-generator"', $toolsNav);
        $this->assertStringContainsString('data-i18n-nav="fabric-dq-pattern-generator"', $toolsNav);
        $this->assertStringContainsString('data-i18n-nav="pureview-scan-generator"', $toolsNav);
        $this->assertStringContainsString('data-i18n-nav="qlik-set-analysis-generator"', $toolsNav);
        $this->assertStringContainsString('PII Macro Generator', $toolsNav);
        $this->assertStringContainsString('Prompt Studio', $toolsNav);
        $this->assertStringContainsString('PureView Scan Generator', $toolsNav);
        $this->assertStringContainsString('Qlik Set Analysis Generator', $toolsNav);
        $this->assertStringNotContainsString('tools-sidenav__step-num', $toolsNav);

        $storiesNav = (string) str($response->getContent())
            ->after('data-i18n="nav.stories">Stories</p>')
            ->before('data-i18n="nav.tools">Governance</p>');

        $storyLinkCount = substr_count($storiesNav, 'data-playbook-nav-title');
        $this->assertLessThanOrEqual(\App\Playbooks\PlaybookRepository::SIDEBAR_INDEX_LIMIT, $storyLinkCount);

        $totalStories = count(app(\App\Playbooks\PlaybookRepository::class)->allForIndex());
        if ($totalStories > \App\Playbooks\PlaybookRepository::SIDEBAR_INDEX_LIMIT) {
            $remaining = $totalStories - min($totalStories, \App\Playbooks\PlaybookRepository::SIDEBAR_INDEX_LIMIT);
            $response->assertSee('data-i18n="nav.storiesMore"', false);
            $response->assertSee('data-i18n-count="'.$remaining.'"', false);
            $response->assertSee(route('playbooks.index'), false);
        }
    }

    public function test_legacy_tool_urls_remain_available(): void
    {
        $this->get('/tools/dbt-dq-macro-generator')->assertOk();
        $this->get('/de/tools/dbt-dq-macro-generator')->assertOk();
        $this->get('/en/tools/dbt-dq-macro-generator')->assertOk();
    }

    public function test_german_landing_uses_de_prefix_in_story_links(): void
    {
        $response = $this->get('/de/');

        $response->assertOk();
        $response->assertSee('/de/playbooks', false);
        $response->assertSee('/de/tools', false);
    }

    public function test_header_includes_layout_settings_menu(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-header-settings', false);
        $response->assertSee('data-shell-full-width-toggle', false);
        $response->assertSee('data-i18n="settings.fullWidth"', false);
        $response->assertSee('dataset.shellFullWidth', false);
    }
}
