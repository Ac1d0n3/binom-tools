<?php

namespace Tests\Feature\Governance;

use App\Accounts\UserRepository;
use App\Governance\GovernanceRadarFeedItemStore;
use App\Governance\GovernanceRadarFeedSync;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GovernanceRadarFeedIngestTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-radar-feeds-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
        Config::set('storage.driver', 'file');
        Config::set('governance-radar.ingest.on_request', false);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_sync_ingests_rss_and_radar_page_shows_live_items(): void
    {
        Http::fake([
            'https://docs.databricks.com/aws/en/feed.xml' => Http::response(<<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>Databricks</title>
    <item>
      <title>Unity Catalog row filters GA</title>
      <link>https://docs.databricks.com/aws/en/release-notes/product/2026/july/uc-row-filters</link>
      <guid>uc-row-filters-2026</guid>
      <pubDate>Tue, 21 Jul 2026 09:00:00 GMT</pubDate>
      <description>Governance and permission update for Unity Catalog.</description>
      <category>Unity Catalog</category>
    </item>
    <item>
      <title>Unrelated notebook UI polish</title>
      <link>https://docs.databricks.com/aws/en/release-notes/product/2026/july/ui</link>
      <guid>ui-polish-2026</guid>
      <pubDate>Tue, 21 Jul 2026 08:00:00 GMT</pubDate>
      <description>Cosmetic editor changes.</description>
    </item>
  </channel>
</rss>
XML, 200, ['Content-Type' => 'application/rss+xml']),
            '*' => Http::response('not found', 404),
        ]);

        $result = app(GovernanceRadarFeedSync::class)->sync(null, ['databricks-release-notes']);
        $this->assertSame(1, $result['synced']);
        $this->assertSame(0, $result['failed']);

        $stored = app(GovernanceRadarFeedItemStore::class)->allItems();
        $this->assertNotEmpty($stored);
        $this->assertSame('Unity Catalog row filters GA', $stored[0]['title']);
        $this->assertTrue(collect($stored)->every(
            static fn (array $item): bool => ($item['source_id'] ?? '') === 'databricks-release-notes'
                && str_contains(mb_strtolower($item['title'].' '.$item['summary']), 'unity catalog'),
        ));

        $response = $this->get('/governance/radar');
        $response->assertOk();
        $response->assertSee('data-origin="feed"', false);
        $response->assertSee('Unity Catalog row filters GA', false);
        $response->assertSee('governance-radar__badge--live', false);
        $response->assertSee('data-governance-radar-feed-status', false);
        $response->assertDontSee('Unrelated notebook UI polish', false);
    }

    public function test_ingest_skips_sources_without_ingest_flag(): void
    {
        Http::fake();

        $result = app(GovernanceRadarFeedSync::class)->sync(null, ['snowflake-release-notes']);
        $this->assertSame(0, $result['synced']);
        Http::assertNothingSent();
    }

    public function test_signed_in_user_can_trigger_feed_sync_api(): void
    {
        app(UserRepository::class)->upsert([
            'id' => 'user_radar_feed',
            'email' => 'radar-feed@example.com',
            'displayName' => 'Radar Feed',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
            'teamIds' => [],
        ]);

        Http::fake([
            'https://qlik.dev/rss.xml' => Http::response(<<<'XML'
<?xml version="1.0"?>
<rss version="2.0"><channel>
<item>
  <title>Qlik space access control update</title>
  <link>https://qlik.dev/changelog/access</link>
  <guid>qlik-access-1</guid>
  <pubDate>Mon, 20 Jul 2026 12:00:00 GMT</pubDate>
  <description>Security and space permission changes.</description>
</item>
</channel></rss>
XML, 200),
            '*' => Http::response('skip', 404),
        ]);

        $this->post('/login', [
            'email' => 'radar-feed@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $this->postJson('/api/governance/radar/feeds/sync')
            ->assertOk()
            ->assertJsonPath('synced', fn ($value) => is_int($value) && $value >= 1);

        $this->get('/governance/radar')
            ->assertOk()
            ->assertSee('data-radar-feed-sync-api-url', false)
            ->assertSee('data-governance-radar-feed-sync', false)
            ->assertSee('Qlik space access control update', false);
    }

    private function removeDir(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }
        $items = scandir($path) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $full = $path.DIRECTORY_SEPARATOR.$item;
            if (is_dir($full)) {
                $this->removeDir($full);
            } else {
                @unlink($full);
            }
        }
        @rmdir($path);
    }
}
