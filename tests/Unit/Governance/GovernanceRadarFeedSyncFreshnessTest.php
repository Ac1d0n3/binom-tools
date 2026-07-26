<?php

namespace Tests\Unit\Governance;

use App\Accounts\AccountsConfig;
use App\Governance\GovernanceRadarFeedItemStore;
use App\Governance\GovernanceRadarFeedSync;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GovernanceRadarFeedSyncFreshnessTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-radar-fresh-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
        Config::set('storage.driver', 'file');
        Config::set('governance-radar.ingest.max_sources_per_request', 5);
        Config::set('governance-radar.ingest.request_budget_seconds', 10);
        Config::set('governance-radar.ingest.request_timeout_seconds', 2);
        Config::set('governance-radar.ingest.error_backoff_minutes', 360);
        Config::set('governance-radar.ingest.ttl_minutes', 45);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_ensure_fresh_skips_recent_error_feeds_within_backoff(): void
    {
        $store = app(GovernanceRadarFeedItemStore::class);
        $recentOk = now()->subMinutes(10)->toIso8601String();
        $staleOk = now()->subHours(2)->toIso8601String();
        $recentError = now()->subMinutes(10)->toIso8601String();

        foreach (app(GovernanceRadarFeedSync::class)->ingestibleSources(null) as $source) {
            $sourceId = (string) ($source['id'] ?? '');
            $feedUrl = (string) ($source['feed_url'] ?? '');
            if ($sourceId === '' || $feedUrl === '') {
                continue;
            }
            if ($sourceId === 'databricks-release-notes') {
                $store->writeSyncStatus($sourceId, $feedUrl, 'error', 'timeout', 0);
                continue;
            }
            if ($sourceId === 'qlik-release-notes') {
                $store->writeSyncStatus($sourceId, $feedUrl, 'ok', null, 1);
                continue;
            }
            $store->writeSyncStatus($sourceId, $feedUrl, 'ok', null, 1);
        }

        $path = app(AccountsConfig::class)->governanceRadarFeedSyncsPath();
        $payload = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        foreach ($payload['syncs'] as $sourceId => &$sync) {
            if ($sourceId === 'databricks-release-notes') {
                $sync['last_synced_at'] = $recentError;
                $sync['last_status'] = 'error';
            } elseif ($sourceId === 'qlik-release-notes') {
                $sync['last_synced_at'] = $staleOk;
                $sync['last_status'] = 'ok';
            } else {
                $sync['last_synced_at'] = $recentOk;
                $sync['last_status'] = 'ok';
            }
        }
        unset($sync);
        file_put_contents($path, json_encode($payload));

        Http::fake([
            'https://qlik.dev/rss.xml' => Http::response(<<<'XML'
<?xml version="1.0"?><rss version="2.0"><channel>
<item>
  <title>Qlik access governance update</title>
  <link>https://qlik.dev/changelog/access-2</link>
  <guid>qlik-access-2</guid>
  <pubDate>Mon, 20 Jul 2026 12:00:00 GMT</pubDate>
  <description>Security and space permission changes.</description>
</item>
</channel></rss>
XML, 200),
            '*' => Http::response('should-not-hit-error-feed', 500),
        ]);

        $result = app(GovernanceRadarFeedSync::class)->ensureFresh(null);

        $this->assertSame(1, $result['synced']);
        $this->assertSame(0, $result['failed']);
        Http::assertSent(fn ($request) => str_contains($request->url(), 'qlik.dev'));
        Http::assertNotSent(fn ($request) => str_contains($request->url(), 'databricks.com'));
    }

    public function test_ensure_fresh_skips_gone_feeds_without_http(): void
    {
        $store = app(GovernanceRadarFeedItemStore::class);
        $recent = now()->subMinutes(10)->toIso8601String();

        foreach (app(GovernanceRadarFeedSync::class)->ingestibleSources(null) as $source) {
            $sourceId = (string) ($source['id'] ?? '');
            $feedUrl = (string) ($source['feed_url'] ?? '');
            if ($sourceId === '' || $feedUrl === '') {
                continue;
            }
            if ($sourceId === 'qlik-release-notes') {
                $store->writeSyncStatus($sourceId, $feedUrl, 'gone', 'Feed URL is unavailable: HTTP 404', 0);
                continue;
            }
            $store->writeSyncStatus($sourceId, $feedUrl, 'ok', null, 1);
        }

        $path = app(AccountsConfig::class)->governanceRadarFeedSyncsPath();
        $payload = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        foreach ($payload['syncs'] as $sourceId => &$sync) {
            $sync['last_synced_at'] = $recent;
            if ($sourceId === 'qlik-release-notes') {
                $sync['last_status'] = 'gone';
                $sync['last_error'] = 'Feed URL is unavailable: HTTP 404';
            } else {
                $sync['last_status'] = 'ok';
            }
        }
        unset($sync);
        file_put_contents($path, json_encode($payload));

        Http::fake(['*' => Http::response('should-not-hit', 500)]);

        $result = app(GovernanceRadarFeedSync::class)->ensureFresh(null);

        $this->assertSame(0, $result['synced']);
        $this->assertSame(0, $result['failed']);
        Http::assertNothingSent();
    }

    public function test_sync_marks_http_404_as_gone(): void
    {
        Http::fake([
            'https://qlik.dev/rss.xml' => Http::response('missing', 404),
        ]);

        $result = app(GovernanceRadarFeedSync::class)->sync(null, ['qlik-release-notes'], 1, 5, 2);

        $this->assertSame(0, $result['synced']);
        $this->assertSame(1, $result['failed']);
        $statuses = app(GovernanceRadarFeedItemStore::class)->syncStatusesBySourceId();
        $this->assertSame('gone', $statuses['qlik-release-notes']['last_status'] ?? null);
    }

    public function test_sync_marks_http_403_as_blocked(): void
    {
        Http::fake([
            'https://qlik.dev/rss.xml' => Http::response('forbidden', 403),
        ]);

        $result = app(GovernanceRadarFeedSync::class)->sync(null, ['qlik-release-notes'], 1, 5, 2);

        $this->assertSame(0, $result['synced']);
        $this->assertSame(1, $result['failed']);
        $statuses = app(GovernanceRadarFeedItemStore::class)->syncStatusesBySourceId();
        $this->assertSame('blocked', $statuses['qlik-release-notes']['last_status'] ?? null);
    }

    public function test_ensure_fresh_retries_oversized_feed_errors_immediately(): void
    {
        $store = app(GovernanceRadarFeedItemStore::class);
        $recent = now()->subMinutes(5)->toIso8601String();

        foreach (app(GovernanceRadarFeedSync::class)->ingestibleSources(null) as $source) {
            $sourceId = (string) ($source['id'] ?? '');
            $feedUrl = (string) ($source['feed_url'] ?? '');
            if ($sourceId === '' || $feedUrl === '') {
                continue;
            }
            if ($sourceId === 'qlik-release-notes') {
                $store->writeSyncStatus($sourceId, $feedUrl, 'error', 'Feed exceeds maximum allowed size.', 0);
                continue;
            }
            $store->writeSyncStatus($sourceId, $feedUrl, 'ok', null, 1);
        }

        $path = app(AccountsConfig::class)->governanceRadarFeedSyncsPath();
        $payload = json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        foreach ($payload['syncs'] as $sourceId => &$sync) {
            $sync['last_synced_at'] = $recent;
            if ($sourceId === 'qlik-release-notes') {
                $sync['last_status'] = 'error';
                $sync['last_error'] = 'Feed exceeds maximum allowed size.';
            } else {
                $sync['last_status'] = 'ok';
            }
        }
        unset($sync);
        file_put_contents($path, json_encode($payload));

        Http::fake([
            'https://qlik.dev/rss.xml' => Http::response(<<<'XML'
<?xml version="1.0"?><rss version="2.0"><channel>
<item>
  <title>Qlik access governance update</title>
  <link>https://qlik.dev/changelog/access-3</link>
  <guid>qlik-access-3</guid>
  <pubDate>Mon, 20 Jul 2026 12:00:00 GMT</pubDate>
  <description>Security and space permission changes.</description>
</item>
</channel></rss>
XML, 200),
            '*' => Http::response('skip', 500),
        ]);

        $result = app(GovernanceRadarFeedSync::class)->ensureFresh(null);

        $this->assertSame(1, $result['synced']);
        $this->assertSame(0, $result['failed']);
        Http::assertSent(fn ($request) => str_contains($request->url(), 'qlik.dev'));
    }

    public function test_radar_page_renders_without_waiting_on_feed_http(): void
    {
        // Unit-test kernel skips on-request sync; this still guards against
        // accidental reintroduction of a blocking sync before the view returns.
        Config::set('governance-radar.ingest.on_request', true);
        Http::fake(fn () => Http::response('slow', 500));

        $started = microtime(true);
        $this->get('/governance/radar')->assertOk()->assertSee('governance-radar', false);
        $elapsedMs = (microtime(true) - $started) * 1000;

        $this->assertLessThan(2000, $elapsedMs, 'Radar page took too long to render');
        Http::assertNothingSent();
    }

    private function removeDir(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }
        foreach (scandir($path) ?: [] as $item) {
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
