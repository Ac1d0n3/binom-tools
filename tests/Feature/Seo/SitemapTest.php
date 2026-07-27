<?php

namespace Tests\Feature\Seo;

use Tests\TestCase;

class SitemapTest extends TestCase
{
    public function test_robots_txt_disallows_private_paths_and_points_to_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $response->assertSee('Disallow: /account', false);
        $response->assertSee('Disallow: /api/', false);
        $response->assertSee('Disallow: /governance/sessions', false);
        $response->assertSee('Sitemap: ', false);
        $response->assertSee('/sitemap.xml', false);
    }

    public function test_sitemap_index_lists_groups(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('<sitemapindex', false);
        $response->assertSee('/sitemap-pages.xml', false);
        $response->assertSee('/sitemap-playbooks.xml', false);
        $response->assertSee('/sitemap-tools.xml', false);
        $response->assertSee('/sitemap-suppliers.xml', false);
    }

    public function test_pages_sitemap_includes_governance_hub_not_redirect_landings(): void
    {
        $response = $this->get('/sitemap-pages.xml');

        $response->assertOk();
        $response->assertSee('<urlset', false);
        $response->assertSee('/governance</loc>', false);
        $response->assertSee('/governance/radar</loc>', false);
        $response->assertDontSee('/governance/berater</loc>', false);
        $response->assertDontSee('/governance/stacks</loc>', false);
        $response->assertDontSee('/governance/kpi-requirements</loc>', false);
        $response->assertDontSee('/governance/supplier-discovery</loc>', false);
        $response->assertDontSee('/governance/discovery-canvas</loc>', false);
        $response->assertDontSee('/de/governance/berater</loc>', false);
        $response->assertDontSee('/governance/sessions', false);
    }

    public function test_unknown_sitemap_group_returns_404(): void
    {
        $this->get('/sitemap-unknown.xml')->assertNotFound();
    }
}
