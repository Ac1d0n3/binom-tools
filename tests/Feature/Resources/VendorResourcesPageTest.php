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
        $response->assertSee('value="salesforce"', false);
        $response->assertSee('value="hubspot"', false);
        $response->assertSee('data-overview-stack', false);
        $response->assertSee('data-overview-stack-banner', false);
        $response->assertSee('data-overview-item', false);
        $response->assertSee('vendor-resources-grid', false);
        $content = $response->getContent();
        $this->assertIsString($content);
        $familyPos = strpos($content, 'data-overview-product');
        $modelPos = strpos($content, 'data-overview-model');
        $residencyPos = strpos($content, 'data-overview-residency');
        $vendorPos = strpos($content, 'data-overview-vendor');
        $this->assertNotFalse($familyPos);
        $this->assertNotFalse($modelPos);
        $this->assertNotFalse($residencyPos);
        $this->assertNotFalse($vendorPos);
        $this->assertLessThan($vendorPos, $familyPos, 'Family filter should appear before vendor filter');
        $this->assertLessThan($vendorPos, $residencyPos, 'Vendor filter should be last among dropdowns');
        $response->assertSee('vendor-resources-card', false);
        $response->assertSee('vendor-resources-card__aside', false);
        $response->assertSee('vendor-resources-card__purpose', false);
        $response->assertSee('vendor-resources-sticky', false);
        $response->assertSee('vendor-resources-stack-banner', false);
        $response->assertSee('data-i18n="resources.indexTitle"', false);
        $response->assertSee('data-overview-result-count', false);
        $response->assertSee('data-i18n="resources.visibleVendorCount"', false);
        $response->assertSee('data-i18n="resources.helpTitle"', false);
        $response->assertSee('data-i18n="resources.governanceTitle"', false);
        $response->assertSee('data-i18n="resources.learningTitle"', false);
        $response->assertSee('data-i18n="resources.certificationsTitle"', false);
        $response->assertSee('data-i18n="resources.familyAll"', false);
        $response->assertSee('data-i18n="resources.familyLabel"', false);
        $response->assertSee('data-i18n="resources.stackAll"', false);
        $response->assertSee('data-i18n="resources.stackLabel"', false);
        $response->assertSee('data-i18n="resources.ourToolsTitle"', false);
        $response->assertSee('data-i18n="resources.modelAll"', false);
        $response->assertSee('data-i18n="resources.modelLabel"', false);
        $response->assertSee('data-i18n="resources.residencyAll"', false);
        $response->assertSee('data-i18n="resources.complianceTitle"', false);

        $response->assertSee('value="modern-data-stack"', false);
        $response->assertSee('value="microsoft-fabric"', false);
        $response->assertSee('value="databricks-lakehouse"', false);
        $response->assertSee('value="gcp-analytics"', false);
        $response->assertSee('value="open-source-stack"', false);
        $response->assertDontSee('value="open-source-stack-a"', false);
        $response->assertDontSee('value="open-source-stack-b"', false);
        $response->assertSee('data-slots="', false);
        $response->assertSee('"chooseOne":true');
        $response->assertSee('DataHub or OpenMetadata', false);
        $response->assertSee('pick one', false);
        $response->assertSee('value="eu-sovereign"', false);
        $response->assertSee('value="sap-enterprise"', false);
        $response->assertSee('value="ai-assisted-delivery"', false);
        $response->assertSee('data-stacks="', false);
        $response->assertSee('data-product-id="dbt"', false);
        $response->assertSee('data-product-id="fabric"', false);
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
        $response->assertSee('Airbyte');
        $response->assertSee('Matillion');
        $response->assertSee('Informatica');
        $response->assertSee('SQLMesh');
        $response->assertSee('Coalesce');
        $response->assertSee('Dataform');
        $response->assertSee('Azure Data Factory');
        $response->assertSee('AWS Glue');
        $response->assertSee('Google Cloud Dataflow');
        $response->assertSee('Stitch');
        $response->assertSee('Meltano');
        $response->assertSee('Hevo Data');
        $response->assertSee('IBM DataStage');
        $response->assertSee('SSIS');
        $response->assertSee('SAP Data Services');
        $response->assertSee('Oracle Data Integrator');
        $response->assertSee('Apache NiFi');
        $response->assertSee('Apache Airflow');
        $response->assertSee('Dagster');
        $response->assertSee('Prefect');
        $response->assertSee('Alteryx');
        $response->assertSee('KNIME');
        $response->assertSee('Pentaho');
        $response->assertSee('Boomi');
        $response->assertSee('MuleSoft');
        $response->assertSee('Rivery');
        $response->assertSee('Estuary');
        $response->assertSee('Hightouch');
        $response->assertSee('Census');
        $response->assertSee('Prophecy');
        $response->assertSee('Apache Spark');
        $response->assertSee('dbt Cloud');
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
        $response->assertSee('Salesforce');
        $response->assertSee('Dynamics 365');
        $response->assertSee('HubSpot');
        $response->assertSee('ServiceNow');
        $response->assertSee('Workday');
        $response->assertSee('SAP SuccessFactors');
        $response->assertSee('Oracle NetSuite');
        $response->assertSee('Jira');
        $response->assertSee('SharePoint');
        $response->assertSee('Moodle');
        $response->assertSee('Zendesk');
        $response->assertSee('Shopify');
        $response->assertSee('Google Workspace');
        $response->assertSee('Adobe Experience Cloud');
        $response->assertSee('SAP S/4HANA');
        $response->assertSee('Temenos');
        $response->assertSee('Avaloq');
        $response->assertSee('Guidewire');
        $response->assertSee('FIS');
        $response->assertSee('Finastra');
        $response->assertSee('Murex');
        $response->assertSee('Duck Creek');
        $response->assertSee('Thought Machine');
        $response->assertSee('OpenText');
        $response->assertSee('DATEV');
        $response->assertSee('Fabasoft');
        $response->assertSee('ELO');
        $response->assertSee('DocuWare');
        $response->assertSee('Pega');
        $response->assertSee('Microsoft Entra ID');
        $response->assertSee('Microsoft Exchange');
        $response->assertSee('Microsoft Teams');
        $response->assertSee('Oracle Fusion Cloud');
        $response->assertSee('SAP Concur');
        $response->assertSee('SAP Ariba');
        $response->assertSee('Confluence');
        $response->assertSee('Slack');
        $response->assertSee('Personio');
        $response->assertSee('Stripe');
        $response->assertSee('Box');
        $response->assertSee('Coupa');
        $response->assertSee('Epic');
        $response->assertSee('monday.com');
        $response->assertSee('Freshdesk');
        $response->assertSee('Sage');
        $response->assertSee('Camunda');

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
        $response->assertSee('data-products="suppliers"', false);
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
        $response->assertSee('value="suppliers"', false);

        $response->assertSee('vendor-resources-group--our-tools', false);
        $response->assertSee('vendor-resources-link--internal', false);
        $response->assertSee(locale_route('tools.dbt-dq-macro-generator'), false);
        $response->assertSee(locale_route('tools.pii-policy-generator'), false);
        $response->assertSee(locale_route('tools.fabric-dq-pattern-generator'), false);
        $response->assertSee(locale_route('tools.prompt-studio'), false);
        $response->assertSee(locale_route('tools.governance-ai-sanitizer'), false);
    }

    public function test_localized_resources_route_is_available(): void
    {
        $response = $this->get('/de/resources');

        $response->assertOk();
        $response->assertSee('vendor-resources-grid', false);
        $response->assertSee('data-overview-product', false);
        $response->assertSee('data-overview-stack', false);
        $response->assertSee('data-i18n="resources.familyAll"', false);
        $response->assertSee('data-i18n="resources.ourToolsTitle"', false);
    }

    public function test_landing_page_links_to_resources_overview(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-i18n="home.hub.resources.title"', false);
        $response->assertSee(route('resources.index'), false);
    }

    public function test_sidebar_includes_resources_nav(): void
    {
        $response = $this->get('/resources');

        $response->assertOk();
        $response->assertSee('data-i18n="nav.hubs"', false);
        $response->assertSee('data-i18n="nav.resources"', false);
        $response->assertSee('data-hub-lead', false);
    }
}
