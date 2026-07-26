<?php

namespace Tests\Feature\Suppliers;

use Tests\TestCase;

class SupplierLibraryHubTest extends TestCase
{
    public function test_suppliers_index_lists_products_with_domain_filter(): void
    {
        $response = $this->get('/suppliers');

        $response->assertOk();
        $response->assertSee('data-overview-filter-root', false);
        $response->assertSee('data-overview-search', false);
        $response->assertSee('data-overview-product', false);
        $response->assertSee('data-overview-result-count', false);
        $response->assertSee('data-overview-count-mode="items"', false);
        $response->assertSee('data-i18n="suppliers.visibleProductCount"', false);
        $response->assertSee('data-overview-layout-toggle="grid"', false);
        $response->assertSee('data-overview-layout-toggle="list"', false);
        $response->assertSee('data-overview-stories-grid', false);
        $response->assertSee('supplier-hub-grid', false);
        $response->assertSee('data-i18n="suppliers.indexTitle"', false);
        $response->assertSee('data-product-id="salesforce"', false);
        // Search index includes measure labels (e.g. ARR), not only title/purpose.
        $response->assertSee('data-search-text="', false);
        $response->assertSee('arr', false);
        $response->assertSee('data-product-id="hubspot"', false);
        $response->assertSee('data-product-id="ga4"', false);
        $response->assertSee('data-product-id="dynamics365"', false);
        $response->assertSee('data-product-id="servicenow"', false);
        $response->assertSee('data-product-id="zendesk"', false);
        $response->assertSee('data-product-id="shopify"', false);
        $response->assertSee('data-product-id="sap-s4hana"', false);
        $response->assertSee('data-product-id="netsuite"', false);
        $response->assertSee('data-product-id="workday"', false);
        $response->assertSee('data-product-id="successfactors"', false);
        $response->assertSee('data-product-id="jira"', false);
        $response->assertSee('data-product-id="confluence"', false);
        $response->assertSee('data-product-id="slack"', false);
        $response->assertSee('data-product-id="microsoft-teams"', false);
        $response->assertSee('data-product-id="stripe"', false);
        $response->assertSee('data-product-id="sap-concur"', false);
        $response->assertSee('data-product-id="sap-ariba"', false);
        $response->assertSee('data-product-id="coupa"', false);
        $response->assertSee('data-product-id="entra-id"', false);
        $response->assertSee('data-product-id="github"', false);
        $response->assertSee('data-product-id="sharepoint"', false);
        $response->assertSee('data-product-id="google-workspace"', false);
        $response->assertSee('data-product-id="temenos"', false);
        $response->assertSee('data-product-id="avaloq"', false);
        $response->assertSee('data-product-id="thought-machine"', false);
        $response->assertSee('data-product-id="finastra"', false);
        $response->assertSee('data-product-id="murex"', false);
        $response->assertSee('data-product-id="fis"', false);
        $response->assertSee('data-product-id="guidewire"', false);
        $response->assertSee('data-product-id="duck-creek"', false);
        $response->assertSee('data-product-id="opentext"', false);
        $response->assertSee('data-product-id="fabasoft"', false);
        $response->assertSee('data-product-id="elo"', false);
        $response->assertSee('data-product-id="docuware"', false);
        $response->assertSee('data-product-id="box"', false);
        $response->assertSee('data-product-id="datev"', false);
        $response->assertSee('data-product-id="sage"', false);
        $response->assertSee('data-product-id="oracle-fusion"', false);
        $response->assertSee('data-product-id="personio"', false);
        $response->assertSee('data-product-id="exchange"', false);
        $response->assertSee('data-product-id="monday"', false);
        $response->assertSee('data-product-id="moodle"', false);
        $response->assertSee('data-product-id="adobe-experience-cloud"', false);
        $response->assertSee('data-product-id="freshdesk"', false);
        $response->assertSee('data-product-id="pega"', false);
        $response->assertSee('data-product-id="camunda"', false);
        $response->assertSee('data-product-id="epic"', false);
        $response->assertSee('Salesforce');
        $response->assertSee('HubSpot');
        $response->assertSee('Google Analytics 4');
        $response->assertSee('value="crm"', false);
        $response->assertSee('value="analytics"', false);
        $response->assertSee('value="service"', false);
        $response->assertSee('value="commerce"', false);
        $response->assertSee('value="erp"', false);
        $response->assertSee('value="hcm"', false);
        $response->assertSee('value="collab"', false);
        $response->assertSee('value="finance"', false);
        $response->assertSee('value="workplace"', false);
        $response->assertSee('value="banking"', false);
        $response->assertSee('value="insurance"', false);
        $response->assertSee('value="dms"', false);
        $response->assertSee('value="learning"', false);
        $response->assertSee('value="marketing"', false);
        $response->assertSee('value="bpm"', false);
        $response->assertSee('value="healthcare"', false);
        $response->assertSee('data-i18n="nav.suppliers"', false);
    }

    public function test_wave1_supplier_detail_pages_render(): void
    {
        foreach (['dynamics365', 'servicenow', 'zendesk', 'shopify'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('supplier-resources-card', false);
        }
    }

    public function test_wave2_supplier_detail_pages_render(): void
    {
        foreach (['sap-s4hana', 'netsuite', 'workday', 'successfactors'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave3_supplier_detail_pages_render(): void
    {
        foreach (['jira', 'confluence', 'slack', 'microsoft-teams'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave4_supplier_detail_pages_render(): void
    {
        foreach (['stripe', 'sap-concur', 'sap-ariba', 'coupa'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave5_supplier_detail_pages_render(): void
    {
        foreach (['entra-id', 'github', 'sharepoint', 'google-workspace'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave6_supplier_detail_pages_render(): void
    {
        foreach (['temenos', 'avaloq', 'thought-machine', 'finastra'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave7_supplier_detail_pages_render(): void
    {
        foreach (['murex', 'fis', 'guidewire', 'duck-creek'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave8_supplier_detail_pages_render(): void
    {
        foreach (['opentext', 'fabasoft', 'elo', 'docuware', 'box'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave9_supplier_detail_pages_render(): void
    {
        foreach (['datev', 'sage', 'oracle-fusion', 'personio'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave10_supplier_detail_pages_render(): void
    {
        foreach (['exchange', 'monday', 'moodle', 'adobe-experience-cloud'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_wave11_supplier_detail_pages_render(): void
    {
        foreach (['freshdesk', 'pega', 'camunda', 'epic'] as $slug) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-library', false);
            $response->assertSee('data-supplier-tab="measures"', false);
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('supplier-resources-card', false);
            $response->assertSee('supplier-measure-card--example', false);
        }
    }

    public function test_suppliers_show_renders_sections_and_examples(): void
    {
        $response = $this->get('/suppliers/salesforce');

        $response->assertOk();
        $response->assertSee('data-supplier-library', false);
        $response->assertSee('data-supplier-tab="measures"', false);
        $response->assertSee('data-supplier-tab="tables"', false);
        $response->assertSee('data-supplier-tab="skip"', false);
        $response->assertSee('data-supplier-tab="sql"', false);
        $response->assertSee('data-supplier-tab="quality"', false);
        $response->assertSee('data-supplier-panel="measures"', false);
        $response->assertSee('data-supplier-panel="dimensions"', false);
        $response->assertSee('data-supplier-panel="pii"', false);
        $response->assertSee('data-supplier-panel="quality"', false);
        $response->assertSee('data-supplier-panel="tables"', false);
        $response->assertSee('data-supplier-panel="fields"', false);
        $response->assertSee('data-supplier-panel="skip"', false);
        $response->assertSee('data-supplier-panel="sql"', false);
        $response->assertSee('data-supplier-panel="tools"', false);
        $response->assertDontSee('data-supplier-tab="playbooks"', false);
        $response->assertDontSee('data-supplier-panel="playbooks"', false);
        $response->assertDontSee('supplier-detail__toc', false);
        $response->assertDontSee('supplier-tool-chip', false);
        $response->assertSee('supplier-link-list', false);
        $response->assertSee('data-i18n="suppliers.categorySystem"', false);
        $response->assertSee('supplier-resources-card', false);
        $response->assertSee('vendor=salesforce', false);
        $response->assertSee('data-i18n="suppliers.resourcesCardTitle"', false);
        $response->assertDontSee('supplier-detail__resources', false);
        $response->assertSee('Won revenue');
        $response->assertSee('Customers with won opp');
        $response->assertSee('Won revenue by owner');
        $response->assertSee('SUM(Opportunity.Amount) WHERE Opportunity.IsWon = true', false);
        $response->assertSee('supplier-table--measures', false);
        $response->assertSee('data-i18n="suppliers.colClassification"', false);
        $response->assertSee('supplier-pii-badge--direct', false);
        $response->assertSee('MailingPostalCode');
        $response->assertSee('Match keys');
        $response->assertSee('Warehouse copies');
        $response->assertSee('Opportunity.Amount');
        $response->assertSee('Opportunity.Probability');
        $response->assertSee('CurrencyIsoCode');
        $response->assertSee('FeedItem');
        $response->assertSee('SetupAuditTrail');
        $response->assertSee('raw_opportunity');
        $response->assertSee('curated_fct_opportunity');
        $response->assertSee('supplier-sql-group', false);
        $response->assertSee('supplier-load-badge--required', false);
        $response->assertSee('supplier-load-badge--optional', false);
        $response->assertSee('data-supplier-copy', false);
        $response->assertSee('supplier-measure-card--example', false);
        $response->assertSee('data-i18n="suppliers.sectionTables"', false);
        $response->assertSee('data-i18n="suppliers.sectionSkip"', false);
        $response->assertSee('data-i18n="suppliers.sectionSql"', false);
        $response->assertSee('data-i18n="suppliers.sectionQuality"', false);
        $response->assertSee('data-i18n="suppliers.dqTitle"', false);
        $response->assertSee('data-i18n="suppliers.mdmTitle"', false);
        $response->assertSee('Duplicate accounts');
        $response->assertSee('Matching rules + duplicate rules', false);
        $response->assertSee('Setup → Duplicate Management', false);
        $response->assertSee('supplier-priority-badge--high', false);
        $response->assertSee('DQ Rules Generator');
        $response->assertSee('data-i18n="suppliers.skipTablesTitle"', false);
        $response->assertSee('data-i18n="suppliers.toolsBlockTitle"', false);
        $response->assertSee('data-i18n="suppliers.playbooksBlockTitle"', false);
    }

    public function test_all_suppliers_render_deep_governance(): void
    {
        $expectations = [
            'salesforce' => ['Contact', 'MailingPostalCode', 'CampaignMember', 'supplier-pii-badge'],
            'hubspot' => ['Contacts', 'Form submissions', 'supplier-pii-badge'],
            'ga4' => ['user_pseudo_id', 'page_location', 'supplier-pii-badge'],
            'dynamics365' => ['emailaddress1', 'annotation', 'supplier-pii-badge'],
            'servicenow' => ['sys_user', 'sys_journal_field', 'supplier-pii-badge'],
            'zendesk' => ['end-users', 'ticket comments', 'supplier-pii-badge'],
            'shopify' => ['customers', 'metafields', 'supplier-pii-badge'],
            'sap-s4hana' => ['KNA1', 'kunnr', 'CDHDR', 'supplier-pii-badge'],
            'netsuite' => ['customer', 'SuiteQL', 'System notes', 'supplier-pii-badge'],
            'workday' => ['Worker', 'RaaS', 'national', 'supplier-pii-badge'],
            'successfactors' => ['PerPerson', 'personIdExternal', 'OData', 'supplier-pii-badge'],
            'jira' => ['assignee', 'JQL', 'changelog', 'supplier-pii-badge'],
            'confluence' => ['space', 'page', 'attachment', 'supplier-pii-badge'],
            'slack' => ['channel', 'Message text', 'Conversations', 'supplier-pii-badge'],
            'microsoft-teams' => ['Graph', 'chat', 'Purview', 'supplier-pii-badge'],
            'stripe' => ['Customer', 'Radar', 'Webhook', 'supplier-pii-badge'],
            'sap-concur' => ['Employee', 'Receipt', 'allocation', 'supplier-pii-badge'],
            'sap-ariba' => ['Supplier', 'commodity', 'attachment', 'supplier-pii-badge'],
            'coupa' => ['User', 'Approval', 'Chart of accounts', 'supplier-pii-badge'],
            'entra-id' => ['User', 'Conditional Access', 'Sign-in', 'supplier-pii-badge'],
            'github' => ['pull_request', 'repository', 'Actions', 'supplier-pii-badge'],
            'sharepoint' => ['site', 'drive', 'Purview', 'supplier-pii-badge'],
            'google-workspace' => ['Admin SDK', 'org unit', 'Drive', 'supplier-pii-badge'],
        ];

        foreach ($expectations as $slug => $needles) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-i18n="suppliers.colClassification"', false);
            $response->assertSee('data-i18n="suppliers.colStageTreatment"', false);
            $response->assertSee('data-i18n="suppliers.dsdrLead"', false);
            foreach ($needles as $needle) {
                $response->assertSee($needle, false);
            }
        }
    }

    public function test_all_suppliers_render_source_native_sql_examples(): void
    {
        $expectations = [
            'salesforce' => ['raw_opportunity', 'curated_fct_opportunity', 'IsWon', 'Opportunity'],
            'hubspot' => ['raw_hubspot_deal', 'hs_is_closed_won', 'raw_hubspot_association_deal_contact', 'closed_won_amount'],
            'ga4' => ['raw_ga4_event', 'event_params', 'user_pseudo_id', 'purchase_revenue', 'session_key'],
            'dynamics365' => ['raw_d365_opportunity', 'actualvalue', 'statecode', 'estimatedvalue'],
            'servicenow' => ['raw_sn_incident', 'caller_id', 'mttr_hours', 'cmdb_ci'],
            'zendesk' => ['raw_zd_ticket', 'requester_id', 'first_reply_minutes', 'csat_score'],
            'shopify' => ['raw_shopify_order', 'shop_money', 'total_price', 'is_gmv_order', 'refund_rate'],
            'sap-s4hana' => ['raw_sap', 'kunnr', 'curated_fct', 'SE11'],
            'netsuite' => ['raw_ns', 'internalid', 'SuiteQL', 'curated_fct'],
            'workday' => ['raw_wd_worker', 'RaaS', 'headcount', 'curated_fct'],
            'successfactors' => ['raw_sf_emp_job', 'personIdExternal', 'OData', 'curated_fct'],
            'jira' => ['raw_jira', 'assignee', 'JQL', 'curated_fct'],
            'confluence' => ['raw_confluence', 'space_key', 'curated_fct'],
            'slack' => ['raw_slack', 'message_count', 'channel_id', 'curated_fct'],
            'microsoft-teams' => ['raw_teams', 'message_count', 'Graph', 'curated_fct'],
            'stripe' => ['raw_stripe', 'payment_intent', 'curated_fct', 'minor'],
            'sap-concur' => ['raw_concur', 'expense', 'curated_fct'],
            'sap-ariba' => ['raw_ariba', 'purchase_order', 'curated_fct'],
            'coupa' => ['raw_coupa', 'purchase_order', 'curated_fct'],
            'entra-id' => ['raw_entra', 'userPrincipalName', 'curated_fct'],
            'github' => ['raw_github', 'pull_request', 'curated_fct'],
            'sharepoint' => ['raw_sharepoint', 'drive', 'curated_fct'],
            'google-workspace' => ['raw_gws', 'org_unit', 'curated_fct'],
        ];

        foreach ($expectations as $slug => $needles) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-tab="sql"', false);
            $response->assertSee('data-supplier-panel="sql"', false);
            $response->assertSee('supplier-sql-group', false);
            $response->assertSee('data-i18n="suppliers.sqlStageRaw"', false);
            $response->assertSee('data-i18n="suppliers.sqlStageCurated"', false);
            foreach ($needles as $needle) {
                $response->assertSee($needle, false);
            }
        }
    }

    public function test_all_suppliers_render_quality_and_mdm_guides(): void
    {
        $expectations = [
            'salesforce' => [
                'Won without Amount',
                'Staff must know',
                'Object Manager',
                'Matching / Duplicate Rules',
                'Duplicate accounts',
            ],
            'hubspot' => [
                'Closed-won without amount',
                'CRM Properties',
                'Pipelines API',
                'Contact duplicates by email',
            ],
            'ga4' => [
                'PII in event params',
                'BigQuery Export Schema',
                'event_params',
                'Consent Mode',
            ],
            'dynamics365' => [
                'Won without actualvalue',
                'Dataverse tables',
                'Alternate Keys',
                'Duplicate Detection',
            ],
            'servicenow' => [
                'Incident without caller_id',
                'sys_dictionary',
                'CI Class Manager',
                'Data Policies',
            ],
            'zendesk' => [
                'Ticket without requester',
                'Ticket Fields API',
                'End-user duplicates',
                'User merge',
            ],
            'shopify' => [
                'Order/customer without email',
                'Metafield Definitions',
                'shop_money',
                'Checkout',
            ],
            'sap-s4hana' => [
                'Open order without kunnr',
                'Data Dictionary (SE11)',
                'CDS views',
                'Duplicate customers',
            ],
            'netsuite' => [
                'Invoice without entity/customer',
                'Records catalog',
                'SuiteQL schema',
                'Duplicate customers',
            ],
            'workday' => [
                'Worker without hire date',
                'Business object docs',
                'RaaS (Report-as-a-Service)',
                'Worker duplicates',
            ],
            'successfactors' => [
                'EmpJob without department',
                'OData $metadata',
                'MDF (Metadata Framework)',
                'Person duplicates',
            ],
            'jira' => [
                'Open issue without assignee',
                'Issue fields admin',
                'JQL',
                'User duplicates',
            ],
            'confluence' => [
                'Page without space',
                'Space permissions',
                'REST content',
                'Space duplicates',
            ],
            'slack' => [
                'Channel without purpose',
                'Conversations API',
                'Admin analytics APIs',
                'User duplicates',
            ],
            'microsoft-teams' => [
                'Chat without team/channel context',
                'Microsoft Graph $metadata',
                'Teams admin center',
                'User duplicates',
            ],
            'stripe' => [
                'Payment succeeded without amount/currency',
                'Dashboard data / API objects',
                'Sigma / SQL',
                'Customer duplicates',
            ],
            'sap-concur' => [
                'Approved report without cost center/allocation',
                'Expense types config',
                'Allocation fields',
                'Employee duplicates',
            ],
            'sap-ariba' => [
                'PO without supplier',
                'Realm config',
                'Commodity taxonomy',
                'Supplier duplicates',
            ],
            'coupa' => [
                'Invoice without PO when policy requires',
                'Chart of accounts',
                'Approval chains',
                'Supplier duplicates',
            ],
            'entra-id' => [
                'Disabled user still in groups/roles',
                'Graph $metadata / directory objects',
                'Conditional Access policies',
                'User duplicates',
            ],
            'github' => [
                'PR without repository',
                'REST / GraphQL schema',
                'Actions metadata',
                'User duplicates',
            ],
            'sharepoint' => [
                'DriveItem / ListItem without site',
                'Graph sites / drives',
                'Sharing / Purview labels',
                'Site duplicates',
            ],
            'google-workspace' => [
                'User without org unit',
                'Admin SDK Directory',
                'Reports API',
                'User duplicates',
            ],
        ];

        foreach ($expectations as $slug => $needles) {
            $response = $this->get('/suppliers/'.$slug);
            $response->assertOk();
            $response->assertSee('data-supplier-tab="quality"', false);
            $response->assertSee('data-supplier-panel="quality"', false);
            $response->assertSee('data-i18n="suppliers.qualityLead"', false);
            $response->assertSee('data-i18n="suppliers.metadataTitle"', false);
            $response->assertSee('supplier-quality-block', false);
            $response->assertSee('supplier-dq-card', false);
            $response->assertSee('supplier-dq-card__summary', false);
            $response->assertSee('<details class="supplier-dq-card">', false);
            $response->assertSee('data-i18n="suppliers.colStaff"', false);
            $response->assertSee('data-i18n="suppliers.colPrevent"', false);
            foreach ($needles as $needle) {
                $response->assertSee($needle, false);
            }
        }
    }

    public function test_suppliers_show_resources_card_falls_back_to_search_for_ga4(): void
    {
        $response = $this->get('/suppliers/ga4');

        $response->assertOk();
        $response->assertSee('supplier-resources-card', false);
        $response->assertSee('q=GA4', false);
    }

    public function test_suppliers_show_returns_404_for_unknown_slug(): void
    {
        $this->get('/suppliers/not-a-real-product')->assertNotFound();
    }

    public function test_german_suppliers_index_uses_locale_prefix(): void
    {
        $response = $this->get('/de/suppliers');

        $response->assertOk();
        $response->assertSee('href="http://localhost/de/suppliers/salesforce"', false);
        $response->assertSee('data-i18n="suppliers.indexTitle"', false);
    }

    public function test_landing_and_sidebar_link_to_suppliers(): void
    {
        $landing = $this->get('/');
        $landing->assertOk();
        $landing->assertSee('hub-suppliers', false);
        $landing->assertSee('data-i18n="home.hub.suppliers.title"', false);

        $suppliers = $this->get('/suppliers');
        $suppliers->assertOk();
        $suppliers->assertSee('data-i18n="nav.suppliers"', false);
        $suppliers->assertSee('href="http://localhost/suppliers"', false);
    }
}
