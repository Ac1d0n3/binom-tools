<?php

namespace Tests\Feature\Accounts;

use App\Catalog\LinkInventoryScanner;
use Tests\TestCase;

class LinkCheckPageTest extends TestCase
{
    public function test_link_check_requires_accounts_and_auth(): void
    {
        if (! config('accounts.enabled')) {
            $this->get('/account/link-check')->assertStatus(404);

            return;
        }

        $this->get('/account/link-check')->assertRedirect();
    }

    public function test_inventory_scanner_finds_https_urls(): void
    {
        $hits = app(LinkInventoryScanner::class)->scan();
        $this->assertNotEmpty($hits);
        $this->assertTrue(
            collect($hits)->contains(fn (array $hit): bool => str_starts_with($hit['url'], 'https://')),
            'Expected at least one https URL in inventory'
        );
    }
}
