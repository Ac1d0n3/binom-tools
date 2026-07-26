<?php

namespace Tests\Unit\Governance;

use App\Governance\GovernanceRadarFeedFetchGuard;
use App\Governance\GovernanceRadarFeedParser;
use App\Governance\GovernanceRadarFeedUnavailableException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GovernanceRadarFeedFetchGuardTest extends TestCase
{
    public function test_fetch_salvages_oversized_rss_prefix(): void
    {
        Config::set('governance-radar.ingest.max_bytes', 280);

        $items = '';
        for ($i = 1; $i <= 20; $i++) {
            $items .= '<item><title>Item '.$i.' governance</title><link>https://example.com/'.$i.'</link>'
                .'<guid>g-'.$i.'</guid><description>'.str_repeat('x', 40).'</description></item>';
        }

        $xml = '<?xml version="1.0"?><rss version="2.0"><channel><title>Big</title>'.$items.'</channel></rss>';
        $this->assertGreaterThan(280, strlen($xml));

        Http::fake([
            'https://example.com/big.xml' => Http::response($xml, 200),
        ]);

        $body = app(GovernanceRadarFeedFetchGuard::class)->fetch('https://example.com/big.xml');
        $entries = app(GovernanceRadarFeedParser::class)->parse($body);

        $this->assertNotEmpty($entries);
        $this->assertSame('Item 1 governance', $entries[0]['title']);
        $this->assertLessThanOrEqual(280 + 40, strlen($body));
    }

    public function test_fetch_marks_403_as_unavailable_blocked(): void
    {
        Http::fake([
            'https://example.com/blocked.xml' => Http::response('nope', 403),
        ]);

        try {
            app(GovernanceRadarFeedFetchGuard::class)->fetch('https://example.com/blocked.xml');
            $this->fail('Expected GovernanceRadarFeedUnavailableException');
        } catch (GovernanceRadarFeedUnavailableException $exception) {
            $this->assertTrue($exception->isBlocked());
            $this->assertSame('blocked', $exception->syncStatus());
        }
    }
}
