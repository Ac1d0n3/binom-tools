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
        $response->assertSee('supplier-hub-grid', false);
        $response->assertSee('data-i18n="suppliers.indexTitle"', false);
        $response->assertSee('data-product-id="salesforce"', false);
        $response->assertSee('data-product-id="hubspot"', false);
        $response->assertSee('data-product-id="ga4"', false);
        $response->assertSee('Salesforce');
        $response->assertSee('HubSpot');
        $response->assertSee('Google Analytics 4');
        $response->assertSee('value="crm"', false);
        $response->assertSee('value="analytics"', false);
        $response->assertSee('data-i18n="nav.suppliers"', false);
    }

    public function test_suppliers_show_renders_sections_and_examples(): void
    {
        $response = $this->get('/suppliers/salesforce');

        $response->assertOk();
        $response->assertSee('data-supplier-library', false);
        $response->assertSee('data-supplier-tab="measures"', false);
        $response->assertSee('data-supplier-tab="tables"', false);
        $response->assertSee('data-supplier-tab="skip"', false);
        $response->assertSee('data-supplier-panel="measures"', false);
        $response->assertSee('data-supplier-panel="dimensions"', false);
        $response->assertSee('data-supplier-panel="pii"', false);
        $response->assertSee('data-supplier-panel="tables"', false);
        $response->assertSee('data-supplier-panel="fields"', false);
        $response->assertSee('data-supplier-panel="skip"', false);
        $response->assertSee('data-supplier-panel="tools"', false);
        $response->assertDontSee('data-supplier-tab="playbooks"', false);
        $response->assertDontSee('data-supplier-panel="playbooks"', false);
        $response->assertDontSee('supplier-detail__toc', false);
        $response->assertDontSee('supplier-tool-chip', false);
        $response->assertSee('supplier-link-list', false);
        $response->assertSee('data-i18n="suppliers.categorySystem"', false);
        $response->assertSee('data-i18n="suppliers.toolsBlockTitle"', false);
        $response->assertSee('data-i18n="suppliers.playbooksBlockTitle"', false);
        $response->assertSee('Revenue (Won)');
        $response->assertSee('ARR');
        $response->assertSee('SUM(amount) WHERE is_won = true', false);
        $response->assertSee('Opportunity.Amount');
        $response->assertSee('Opportunity.Probability');
        $response->assertSee('CurrencyIsoCode');
        $response->assertSee('FeedItem');
        $response->assertSee('SetupAuditTrail');
        $response->assertSee('supplier-load-badge--required', false);
        $response->assertSee('supplier-load-badge--optional', false);
        $response->assertSee('data-supplier-copy', false);
        $response->assertSee('supplier-measure-card--example', false);
        $response->assertSee('data-i18n="suppliers.sectionTables"', false);
        $response->assertSee('data-i18n="suppliers.sectionSkip"', false);
        $response->assertSee('data-i18n="suppliers.skipTablesTitle"', false);
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
