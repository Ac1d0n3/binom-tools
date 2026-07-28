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
        $response->assertSee('data-i18n="home.biTitle"', false);
        $response->assertSee('data-i18n="home.storiesTitle"', false);
        $response->assertSee('data-phone-hide-tools', false);
        $response->assertSee('phone-hide-tools', false);
        $response->assertDontSee('data-tools-phone-gate', false);
        $response->assertSee('AI Sanitizer');
        $response->assertSee('Prompt Studio');
        $response->assertSee('Qlik Set Analysis Generator');
        $response->assertSee('Tableau Calculation Generator');
        $response->assertSee('Power BI DAX Measure Generator');
        $response->assertSee('tools-card--overview', false);
        $response->assertSee('tools-card--quote', false);
        $response->assertSee('tools-card__quote-binary', false);
        $response->assertSee('tools-card--hub', false);
        $response->assertSee('tools-card--top-stories', false);
        $response->assertSee('data-i18n="home.topStories.title"', false);
        $response->assertSee('data-i18n="home.hero.ctaGovernance"', false);
        $response->assertSee('data-i18n="home.hero.ctaBi"', false);
        $response->assertSee('data-i18n="home.hero.ctaSources"', false);
        $response->assertDontSee('data-i18n="home.hero.ctaSdk"', false);
        $response->assertSee('data-i18n="home.hero.notice"', false);
        $response->assertSee('name="keywords"', false);
        $response->assertSee('Governance Hub', false);
        $response->assertSee('og:title', false);
        $response->assertSee(route('learning-paths.show', ['slug' => 'trusted-metrics']), false);
        $response->assertSee(route('suppliers.index'), false);
        $response->assertDontSee('tools-hero__pills', false);
        $response->assertDontSee('tools-hero__pill', false);
        $response->assertSee(route('governance.index'), false);
        $response->assertSee(route('tools.overview'), false);
        $response->assertSee(route('playbooks.index'), false);
        $response->assertSee('Radar', false);
        $response->assertSee('binom-ngx', false);
        $response->assertSee('qlik.binom.net', false);
        $response->assertSee('https://qlik.binom.net', false);
        $response->assertSee('https://ngx-docs.binom.net', false);
        $response->assertDontSee('http://localhost:4200', false);
        $response->assertSee('Git-Repo klonen', false);
        $response->assertSee('https://github.com/Ac1d0n3/binom-tools', false);
        $response->assertSee('fa-brands fa-github', false);
        $response->assertDontSee('data-overview-filter-root', false);

        $html = $response->getContent();
        $storiesPos = strpos($html, 'data-i18n="home.storiesTitle"');
        $aiPos = strpos($html, 'data-i18n="home.aiTitle"');
        $biPos = strpos($html, 'data-i18n="home.biTitle"');
        $toolsPos = strpos($html, 'data-i18n="home.toolsTitle"');
        $this->assertNotFalse($storiesPos);
        $this->assertNotFalse($aiPos);
        $this->assertNotFalse($biPos);
        $this->assertNotFalse($toolsPos);
        $this->assertLessThan($aiPos, $storiesPos);
        $this->assertLessThan($biPos, $aiPos);
        $this->assertLessThan($toolsPos, $biPos);
        $response->assertSee('data-i18n="footer.about"', false);
        $response->assertSee('v1.0.0', false);
        $response->assertDontSee('tools-beta-badge', false);
        $response->assertDontSee('class="tools-about-body"', false);
    }

    public function test_footer_shows_release_meta_on_all_pages(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();
        $response->assertSee('tools-release-meta', false);
        $response->assertSee('v1.0.0', false);
        $response->assertSee('data-i18n="footer.about"', false);
        $response->assertSee('data-i18n="footer.disclaimer"', false);
        $response->assertSee('data-i18n="footer.sitemap"', false);
        $response->assertSee('href="'.route('seo.sitemap.html').'"', false);
        $response->assertSee('data-i18n="footer.sitemapXml"', false);
        $response->assertSee('href="'.route('seo.sitemap').'"', false);
        $response->assertSee('data-disclaimer-banner', false);
        $response->assertSee('data-disclaimer-dismiss', false);
        $response->assertSee('/disclaimer', false);
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
        $response->assertSee('Tableau Calculation Generator');
        $response->assertSee('Power BI DAX Measure Generator');
        $response->assertSee('KPI Requirements Intake');
        $response->assertSee('Source Scope Builder');
        $response->assertSee('Mart Design Brief Generator');
        $response->assertSee('Governance Stack Advisor');
        $response->assertSee('PII/DSDR Readiness Checker');
        $response->assertSee('Decision Brief Generator');
        $response->assertSee('Vendor Learning Path Builder');
        $response->assertSee('tools-card__platform-mark', false);
        $response->assertSee('images/fabric-badge.svg', false);
        $response->assertSee('images/databricks-badge.svg', false);
        $response->assertSee('Fabric');
        $response->assertSee('Databricks');
        $response->assertSee('PII Table Gate');
        $response->assertSee('Recommend Generator');
        $response->assertDontSee('tools-workflow-section', false);
        $response->assertDontSee('Security &amp; governance setup', false);
        $response->assertSee('Setup and reference workflows in the Governance Hub', false);
        $response->assertSee('data-overview-filter-root', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-overview-product', false);
        $response->assertSee('data-products="fabric"', false);
        $response->assertSee('data-products="databricks"', false);
        $response->assertSee('data-products="pureview"', false);
        $response->assertSee('data-products="qlik"', false);
        $response->assertSee('data-products="tableau"', false);
        $response->assertSee('data-products="powerbi"', false);
        $response->assertSee('data-products="dbt"', false);
        $response->assertSee('data-overview-item', false);
        $response->assertSee('tools-overview-sticky-header', false);
        $response->assertSee('tools-shell__main--overview', false);
        $response->assertSee('tools-overview-scroll', false);
        $response->assertSee('data-tools-phone-gate', false);
        $response->assertSee('data-i18n="tools.phoneGate.title"', false);
        $response->assertSee('tools-release-meta', false);
        $response->assertSee('v1.0.0', false);
        $response->assertDontSee('data-i18n="tools.overviewTitle"', false);
        $response->assertDontSee('data-i18n="tools.overviewLead"', false);
    }

    public function test_tool_pages_include_phone_gate_markup(): void
    {
        $response = $this->get('/tools/prompt-studio');

        $response->assertOk();
        $response->assertSee('data-tools-phone-gate', false);
        $response->assertSee('data-i18n="tools.phoneGate.title"', false);
        $response->assertSee('data-i18n="tools.phoneGate.lead"', false);
        $response->assertSee('data-i18n="tools.phoneGate.ctaStories"', false);
        $response->assertSee('data-i18n="tools.phoneGate.ctaHome"', false);
        $response->assertSee(route('playbooks.index'), false);
    }

    public function test_landing_does_not_include_phone_gate_markup(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('data-tools-phone-gate', false);
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
        $response = $this->get('/tools/qlik-set-analysis-generator');

        $response
            ->assertOk()
            ->assertSee('qlik-set-analysis-generator-app', false)
            ->assertSee('qlik-set-workbench-title', false)
            ->assertSee('qlik-set-help-toggle', false)
            ->assertSee('qlik-set-help-body', false)
            ->assertSee('qlik-set-help__links', false)
            ->assertSee('qlikSet.help.productLink', false)
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
            ->assertSee('qlik-set-dimension-chips', false)
            ->assertSee('qlik-set-measure-chips', false)
            ->assertSee('qlik-set-generated-measures', false)
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

        $this->assertStringNotContainsString('id="qlik-set-hierarchy-pre"', $response->getContent());
        $this->assertStringNotContainsString('id="qlik-set-variable-use"', $response->getContent());
        $this->assertStringNotContainsString('id="qlik-set-use-variable-base"', $response->getContent());
    }

    public function test_tableau_calculation_generator_renders(): void
    {
        $response = $this->get('/tools/tableau-calculation-generator');

        $response
            ->assertOk()
            ->assertSee('tableau-calculation-generator-app', false)
            ->assertSee('data-tableau-calc-root', false)
            ->assertSee('data-tableau-fields', false)
            ->assertSee('data-tableau-values', false)
            ->assertSee('data-tableau-definition-dimension', false)
            ->assertSee('data-tableau-definition-values', false)
            ->assertSee('data-tableau-base-expression', false)
            ->assertSee('data-tableau-current-formula', false)
            ->assertSee('data-tableau-apply-base', false)
            ->assertSee('data-tableau-function', false)
            ->assertSee('data-tableau-definition-dropzone', false)
            ->assertSee('data-tableau-hierarchy-dropzone', false)
            ->assertSee('data-tableau-dimension-chips', false)
            ->assertSee('data-tableau-measure-chips', false)
            ->assertSee('data-tableau-base-list', false)
            ->assertSee('data-tableau-hierarchy-list', false)
            ->assertSee('data-tableau-definitions', false)
            ->assertSee('data-tableau-base-measures', false)
            ->assertSee('data-tableau-tab="calculations"', false)
            ->assertSee('data-tableau-delete-app', false)
            ->assertSee('data-tableau-import-modal', false)
            ->assertSee('data-tableau-undo', false)
            ->assertSee('data-tableau-redo', false)
            ->assertSee('data-tableau-hierarchy-preview', false)
            ->assertSee('data-tableau-definition-list', false)
            ->assertSee('data-tableau-download-xlsx', false)
            ->assertSee('data-tableau-help-toggle', false)
            ->assertSee('qlik-set-help__links', false)
            ->assertSee('tableauCalc.pageTitle', false);
    }

    public function test_powerbi_dax_generator_renders(): void
    {
        $response = $this->get('/tools/powerbi-dax-generator');

        $response
            ->assertOk()
            ->assertSee('powerbi-dax-generator-app', false)
            ->assertSee('data-powerbi-dax-root', false)
            ->assertSee('data-powerbi-fields', false)
            ->assertSee('data-powerbi-values', false)
            ->assertSee('data-powerbi-definition-column', false)
            ->assertSee('data-powerbi-definition-values', false)
            ->assertSee('data-powerbi-base-expression', false)
            ->assertSee('data-powerbi-current-formula', false)
            ->assertSee('data-powerbi-apply-base', false)
            ->assertSee('data-powerbi-function', false)
            ->assertSee('data-powerbi-definition-dropzone', false)
            ->assertSee('data-powerbi-hierarchy-dropzone', false)
            ->assertSee('data-powerbi-dimension-chips', false)
            ->assertSee('data-powerbi-measure-chips', false)
            ->assertSee('data-powerbi-base-list', false)
            ->assertSee('data-powerbi-hierarchy-list', false)
            ->assertSee('data-powerbi-definitions', false)
            ->assertSee('data-powerbi-base-measures', false)
            ->assertSee('data-powerbi-tab="measures"', false)
            ->assertSee('data-powerbi-delete-app', false)
            ->assertSee('data-powerbi-import-modal', false)
            ->assertSee('data-powerbi-undo', false)
            ->assertSee('data-powerbi-redo', false)
            ->assertSee('data-powerbi-hierarchy-preview', false)
            ->assertSee('data-powerbi-definition-list', false)
            ->assertSee('data-powerbi-download-xlsx', false)
            ->assertSee('data-powerbi-help-toggle', false)
            ->assertSee('qlik-set-help__links', false)
            ->assertSee('powerbiDax.pageTitle', false);
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
            ->assertSee('kpi-definition-app', false)
            ->assertSee('data-discovery-extra', false)
            ->assertSee('data-print-report', false)
            ->assertSee('kpi-definition', false);

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

    public function test_tools_overview_lists_discovery_tools_without_workflow_sections(): void
    {
        $response = $this->get('/tools');

        $response->assertOk();
        $response->assertDontSee('tools-workflow-section', false);
        $response->assertDontSee('Discovery &amp; assessment', false);
        $response->assertSee('Stakeholder &amp; RACI Matrix', false);
        $response->assertSee('Report Inventory Canvas', false);
        $response->assertSee('KPI Definition Card', false);
        $response->assertSee('Architecture Fit Checklist', false);
        $response->assertSee('Impact–Effort Prioritizer', false);
        $response->assertSee(route('governance.index'), false);
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
            ->after('data-i18n="nav.tools">Binom-Tools</p>')
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
            ->before('data-i18n="nav.hubs">Hubs</p>');

        $storyLinkCount = substr_count($storiesNav, 'data-playbook-nav-title');
        $this->assertLessThanOrEqual(\App\Playbooks\PlaybookRepository::SIDEBAR_INDEX_LIMIT, $storyLinkCount);

        $repository = app(\App\Playbooks\PlaybookRepository::class);
        $totalStories = count($repository->allForIndex());
        $sidebarCards = $repository->latestCatalogCards();
        if ($totalStories > count($sidebarCards)) {
            $remaining = $totalStories - count($sidebarCards);
            $response->assertSee('data-i18n="nav.storiesMore"', false);
            $response->assertSee('data-i18n-count="'.$remaining.'"', false);
            $response->assertSee(route('playbooks.index'), false);
        }

        $this->assertGreaterThan(0, substr_count($storiesNav, 'data-playbook-nav-title'));
        $this->assertLessThanOrEqual(1, substr_count($storiesNav, 'metadata-deep-dive'));
        if (str_contains($storiesNav, 'metadata-deep-dive')) {
            $this->assertStringContainsString('/playbooks/series/metadata-deep-dive', $storiesNav);
        }

        $hubsNav = (string) str($response->getContent())
            ->after('data-i18n="nav.hubs">Hubs</p>')
            ->before('data-i18n="nav.tools">Binom-Tools</p>');
        $this->assertStringContainsString('data-i18n="nav.radar"', $hubsNav);
        $this->assertStringContainsString('data-i18n="nav.resources"', $hubsNav);
        $this->assertStringContainsString('data-i18n="nav.suppliers"', $hubsNav);
        $this->assertStringContainsString('data-i18n="nav.compliance"', $hubsNav);
        $this->assertStringContainsString('data-i18n="nav.learningPaths"', $hubsNav);
        $this->assertStringContainsString('data-i18n="nav.roles"', $hubsNav);
        $this->assertStringContainsString('data-i18n="nav.glossary"', $hubsNav);
        $this->assertStringContainsString('data-i18n="nav.sprintPlanner"', $hubsNav);
        $this->assertStringContainsString('data-i18n="nav.calendar"', $hubsNav);
        $pathsPos = strpos($hubsNav, 'data-i18n="nav.learningPaths"');
        $rolesPos = strpos($hubsNav, 'data-i18n="nav.roles"');
        $glossaryPos = strpos($hubsNav, 'data-i18n="nav.glossary"');
        $this->assertNotFalse($pathsPos);
        $this->assertNotFalse($rolesPos);
        $this->assertNotFalse($glossaryPos);
        $this->assertLessThan($rolesPos, $pathsPos);
        $this->assertLessThan($glossaryPos, $rolesPos);
        $this->assertStringNotContainsString('data-i18n="nav.search"', $hubsNav);
        $this->assertStringNotContainsString('data-i18n="nav.workspace"', $hubsNav);
        $this->assertStringNotContainsString('data-i18n="nav.sprintPlannerPlans"', $hubsNav);
        $this->assertStringNotContainsString('data-i18n="nav.account"', $hubsNav);
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
        $response->assertSee('data-shell-hide-hub-leads-toggle', false);
        $response->assertSee('data-i18n="settings.hideHubLeads"', false);
        $response->assertSee('dataset.shellFullWidth', false);
        $response->assertSee('dataset.hideHubLeads', false);
    }

    public function test_shared_shell_header_markup_on_hub_views(): void
    {
        $paths = [
            '/',
            '/playbooks',
            '/playbooks/help-hub-platform',
            '/governance',
            '/roles',
            '/glossary',
            '/glossary/bingo',
            '/learning-paths',
            '/resources',
            '/suppliers',
            '/compliance',
            '/search',
            '/calendar',
            '/sprint-planner',
            '/about',
        ];

        foreach ($paths as $path) {
            $response = $this->get($path);
            $html = $response->getContent();

            $this->assertSame(200, $response->status(), "Expected HTTP 200 for {$path}");
            $this->assertStringContainsString('tools-shell__header', $html, "Missing shell header on {$path}");
            $this->assertStringContainsString('tools-header__search', $html, "Missing header search form on {$path}");
            $this->assertStringContainsString('tools-header__search-link', $html, "Missing mobile search link on {$path}");
            $this->assertStringContainsString('data-header-search-link', $html, "Missing search link hook on {$path}");
            $this->assertStringNotContainsString('tools-header__mission', $html, "Mission text must stay removed on {$path}");
        }
    }

    public function test_landing_page_shows_hub_overview(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-i18n="home.hubsTitle"', false);
        $response->assertSee('tools-section__head', false);
        $response->assertSee('tools-section__title-binary', false);
        $response->assertSee('tools-section--band', false);
        $response->assertSee('tools-section__art', false);
        $response->assertSee('data-i18n="home.featuredGovernance.title"', false);
        $response->assertSee('data-i18n="home.hub.tools.title"', false);
        $response->assertSee('data-i18n="home.hub.stories.title"', false);
        $response->assertSee('data-i18n="home.hub.learningPaths.title"', false);
        $response->assertSee('data-i18n="home.hub.glossary.title"', false);
        $response->assertSee('data-i18n="home.hub.roles.title"', false);
        $response->assertSee('data-i18n="home.hub.resources.title"', false);
        $response->assertSee('data-i18n="home.hub.suppliers.title"', false);
        $response->assertSee('data-i18n="home.hub.compliance.title"', false);
        $response->assertSee('data-i18n="home.hub.sprintPlanner.title"', false);
        $response->assertSee('data-i18n="home.hub.radar.title"', false);
        $response->assertSee('data-card-id="featured-governance"', false);
        $response->assertSee('data-card-id="hub-tools"', false);
        $response->assertSee('data-card-id="hub-learning-paths"', false);
        $response->assertSee('data-card-id="hub-glossary"', false);
        $response->assertSee('data-card-id="hub-roles"', false);
        $response->assertSee('data-card-id="hub-radar"', false);
        $html = (string) $response->getContent();
        $pathsCard = strpos($html, 'data-card-id="hub-learning-paths"');
        $rolesCard = strpos($html, 'data-card-id="hub-roles"');
        $glossaryCard = strpos($html, 'data-card-id="hub-glossary"');
        $this->assertNotFalse($pathsCard);
        $this->assertNotFalse($rolesCard);
        $this->assertNotFalse($glossaryCard);
        $this->assertLessThan($rolesCard, $pathsCard);
        $this->assertLessThan($glossaryCard, $rolesCard);
        $response->assertSee('tools-card__badge--date', false);
        $response->assertSee('fa-arrows-rotate', false);
        $response->assertSee('data-series-teaser', false);
        $response->assertDontSee('data-i18n="header.mission"', false);
        $response->assertDontSee('tools-header__mission', false);
        $response->assertSee('tools-header__search', false);
        $response->assertSee('tools-header__search-link', false);
        $response->assertSee('data-header-search-link', false);
        $response->assertSee(route('governance.index'), false);
        $response->assertSee(route('governance.radar'), false);
        $response->assertSee(route('tools.overview'), false);
        $response->assertSee(route('learning-paths.index'), false);
        $response->assertSee(route('glossary.index'), false);
        $response->assertSee(route('roles.index'), false);
        $response->assertSee(route('resources.index'), false);
        $response->assertSee(route('suppliers.index'), false);
        $response->assertSee(route('compliance.index'), false);
        $response->assertSee(route('sprint-planner.index'), false);
        $response->assertSee(route('search.index'), false);
    }
}
