<?php

namespace Tests\Feature\Resources;

use Tests\TestCase;

class VendorResourcesPageTest extends TestCase
{
    public function test_resources_page_lists_products_with_search_and_family_filter(): void
    {
        $response = $this->get('/resources');

        $response->assertOk();
        $response->assertSee('data-overview-filter-root', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-overview-product', false);
        $response->assertSee('data-overview-model', false);
        $response->assertSee('data-overview-residency', false);
        $response->assertSee('data-overview-vendor', false);
        $response->assertSee('data-overview-item', false);
        $response->assertSee('vendor-resources-grid', false);
        $response->assertSee('vendor-resources-card', false);
        $response->assertSee('vendor-resources-card__aside', false);
        $response->assertSee('vendor-resources-card__purpose', false);
        $response->assertSee('vendor-resources-sticky', false);
        $response->assertSee('data-i18n="resources.indexTitle"', false);
        $response->assertSee('data-i18n="resources.helpTitle"', false);
        $response->assertSee('data-i18n="resources.governanceTitle"', false);
        $response->assertSee('data-i18n="resources.learningTitle"', false);
        $response->assertSee('data-i18n="resources.certificationsTitle"', false);
        $response->assertSee('data-i18n="resources.familyAll"', false);
        $response->assertSee('data-i18n="resources.familyLabel"', false);
        $response->assertSee('data-i18n="resources.modelAll"', false);
        $response->assertSee('data-i18n="resources.modelLabel"', false);
        $response->assertSee('data-i18n="resources.residencyAll"', false);
        $response->assertSee('data-i18n="resources.complianceTitle"', false);

        $response->assertSee('dbt');
        $response->assertSee('Databricks');
        $response->assertSee('Snowflake');
        $response->assertSee('Amazon Web Services');
        $response->assertSee('Microsoft Azure');
        $response->assertSee('Google Cloud');
        $response->assertSee('OVHcloud');
        $response->assertSee('Hetzner');
        $response->assertSee('Talend');
        $response->assertSee('Miro');
        $response->assertSee('Microsoft Whiteboard');
        $response->assertSee('Microsoft Planner');
        $response->assertSee('BigQuery');
        $response->assertSee('Microsoft Fabric');
        $response->assertSee('SAP');
        $response->assertSee('Microsoft Purview');
        $response->assertSee('Fivetran');
        $response->assertSee('Power BI');
        $response->assertSee('Qlik');
        $response->assertSee('Tableau');
        $response->assertSee('Metabase');
        $response->assertSee('Apache Superset');
        $response->assertSee('Lightdash');
        $response->assertSee('Atlan');
        $response->assertSee('Collibra');
        $response->assertSee('Alation');
        $response->assertSee('DataHub');
        $response->assertSee('OpenMetadata');
        $response->assertSee('OpenLineage');
        $response->assertSee('Marquez');
        $response->assertSee('ChatGPT');
        $response->assertSee('Claude');
        $response->assertSee('Cursor');
        $response->assertSee('OpenAI Codex');
        $response->assertSee('GitHub Copilot');
        $response->assertSee('GitHub');
        $response->assertSee('Microsoft Copilot');

        $response->assertSee('vendor-resources-card__models', false);
        $response->assertSee('vendor-resources-model--saas', false);
        $response->assertSee('vendor-resources-model--opensource', false);
        $response->assertSee('vendor-resources-model--onprem', false);
        $response->assertSee('data-i18n="resources.modelSaas"', false);
        $response->assertSee('data-i18n="resources.modelOpenSource"', false);
        $response->assertSee('data-i18n="resources.modelOnPrem"', false);
        $response->assertSee('data-models="saas,onprem"', false);
        $response->assertSee('vendor-resources-card__wordmark', false);
        $response->assertSee('--vendor-brand: #FF3621', false);
        $response->assertSee('--vendor-brand: #FF694B', false);
        $response->assertSee('--vendor-brand: #10A37F', false);

        $response->assertSee('https://docs.getdbt.com/', false);
        $response->assertSee('https://docs.databricks.com/aws/en/data-governance/unity-catalog/', false);
        $response->assertSee('https://docs.atlan.com/product/capabilities/governance/stewardship', false);
        $response->assertSee('https://help.qlik.com/', false);
        $response->assertSee('https://openlineage.io/docs/', false);
        $response->assertSee('https://learn.snowflake.com/', false);
        $response->assertSee('rel="noopener noreferrer"', false);
        $response->assertSee('data-products="platforms"', false);
        $response->assertSee('data-products="cloud"', false);
        $response->assertSee('data-products="planning"', false);
        $response->assertSee('data-vendor="microsoft"', false);
        $response->assertSee('data-vendor="talend"', false);
        $response->assertSee('data-i18n="resources.vendorAll"', false);
        $response->assertSee('data-i18n="resources.bundleM365"', false);
        $response->assertSee('vendor-resources-model--bundle-m365', false);
        $response->assertSee('data-products="transformation"', false);
        $response->assertSee('data-products="bi"', false);
        $response->assertSee('data-models="opensource,onprem"', false);
        $response->assertSee('data-models="saas,opensource,onprem"', false);
        $response->assertSee('data-models="saas,onprem"', false);
        $response->assertSee('data-residency="eu,de"', false);
        $response->assertSee('data-residency="eu,de,us,global"', false);
        $response->assertSee('BSI C5', false);
        $response->assertSee('ISO 27001', false);
        $response->assertSee('PCI DSS', false);
        $response->assertSee('vendor-resources-compliance-chip', false);
        $response->assertSee('vendor-resources-model--residency', false);
        $response->assertSee('data-products="catalogs"', false);
        $response->assertSee('data-products="lineage"', false);
        $response->assertSee('data-products="ai"', false);
        $response->assertSee('Cloud data warehouse', false);
        $response->assertSee('value="platforms"', false);
    }

    public function test_localized_resources_route_is_available(): void
    {
        $response = $this->get('/de/resources');

        $response->assertOk();
        $response->assertSee('vendor-resources-grid', false);
        $response->assertSee('data-overview-product', false);
        $response->assertSee('data-i18n="resources.familyAll"', false);
    }

    public function test_landing_page_links_to_resources_overview(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-i18n="home.resourcesTitle"', false);
        $response->assertSee(route('resources.index'), false);
        $response->assertSee('data-i18n="home.viewAllResources.title"', false);
    }

    public function test_sidebar_includes_resources_nav(): void
    {
        $response = $this->get('/resources');

        $response->assertOk();
        $response->assertSee('data-i18n="nav.resources"', false);
        $response->assertSee('data-i18n="nav.resourcesOverview"', false);
    }
}
