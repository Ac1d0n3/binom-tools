<?php

namespace Tests\Feature\Accounts;

use App\Accounts\UserRepository;
use App\Catalog\LinkCheckStore;
use App\Catalog\LinkInventoryScanner;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LinkCheckPageTest extends TestCase
{
    private string $basePath;

    private string $linkCheckDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-link-check-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        $this->linkCheckDir = $this->basePath.'/link-checks';
        mkdir($this->linkCheckDir, 0775, true);

        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);

        app()->instance(LinkCheckStore::class, new LinkCheckStore($this->linkCheckDir.'/latest.json'));

        app(UserRepository::class)->upsert([
            'id' => 'user_admin',
            'email' => 'admin@example.com',
            'displayName' => 'Admin',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
            'shortName' => 'ADM',
            'colorToken' => 'accent-2',
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

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

    public function test_admin_can_open_link_check_and_start_scan(): void
    {
        $this->login('admin@example.com');
        Http::fake([
            '*' => Http::response('', 200),
        ]);

        $this->get('/admin/link-check')->assertOk();

        $this->post('/admin/link-check/run', ['limit' => 2])
            ->assertRedirect('/admin/link-check');

        $latest = app(LinkCheckStore::class)->latest();
        $this->assertNotNull($latest);
        $this->assertSame('done', $latest['status'] ?? null);
        $this->assertSame(2, (int) ($latest['total'] ?? 0));
        $this->assertArrayHasKey('summary', $latest);
    }

    public function test_dashboard_shows_last_link_check_result(): void
    {
        app(LinkCheckStore::class)->save([
            'status' => 'done',
            'checkedAt' => '2026-07-28T12:00:00+00:00',
            'results' => [],
            'summary' => ['ok' => 10, 'redirect' => 1, 'broken' => 3, 'error' => 2],
            'total' => 16,
        ]);

        $this->login('admin@example.com');
        $html = $this->get('/admin')->assertOk()->getContent();

        $this->assertStringContainsString('tools-card__count">5<', $html);
        $this->assertStringContainsString('>3</strong>', $html);
        $this->assertStringContainsString('broken', $html);
        $this->assertMatchesRegularExpression('/2026-07-28\s+12:00|2026-07-28\s+14:00/', $html);
    }

    private function login(string $email): void
    {
        $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ])->assertRedirect();
    }

    private function removeDir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        $items = scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir.DIRECTORY_SEPARATOR.$item;
            if (is_dir($path)) {
                $this->removeDir($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}
