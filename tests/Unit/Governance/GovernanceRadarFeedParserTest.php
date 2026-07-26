<?php

namespace Tests\Unit\Governance;

use App\Governance\GovernanceRadarFeedFetchGuard;
use App\Governance\GovernanceRadarFeedParser;
use InvalidArgumentException;
use Tests\TestCase;

class GovernanceRadarFeedParserTest extends TestCase
{
    public function test_parses_rss_items(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>Example</title>
    <item>
      <title>Unity Catalog governance update</title>
      <link>https://example.com/uc</link>
      <guid>https://example.com/uc</guid>
      <pubDate>Mon, 20 Jul 2026 10:00:00 GMT</pubDate>
      <description>Catalog and access controls.</description>
      <category>Governance</category>
    </item>
  </channel>
</rss>
XML;

        $items = app(GovernanceRadarFeedParser::class)->parse($xml);

        $this->assertCount(1, $items);
        $this->assertSame('Unity Catalog governance update', $items[0]['title']);
        $this->assertSame('https://example.com/uc', $items[0]['url']);
        $this->assertSame('2026-07-20 10:00:00', $items[0]['published_at']);
        $this->assertContains('Governance', $items[0]['topics']);
    }

    public function test_parses_atom_entries(): void
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>Atom Example</title>
  <entry>
    <id>urn:example:1</id>
    <title>Fabric security release</title>
    <updated>2026-07-21T12:00:00Z</updated>
    <link href="https://example.com/fabric" rel="alternate"/>
    <summary>Workspace security controls.</summary>
    <category term="Security"/>
  </entry>
</feed>
XML;

        $items = app(GovernanceRadarFeedParser::class)->parse($xml);

        $this->assertCount(1, $items);
        $this->assertSame('Fabric security release', $items[0]['title']);
        $this->assertSame('https://example.com/fabric', $items[0]['url']);
        $this->assertSame('urn:example:1', $items[0]['guid']);
        $this->assertContains('Security', $items[0]['topics']);
    }

    public function test_rejects_private_feed_hosts(): void
    {
        $this->expectException(InvalidArgumentException::class);
        app(GovernanceRadarFeedFetchGuard::class)->validateUrl('http://127.0.0.1/rss.xml');
    }
}
