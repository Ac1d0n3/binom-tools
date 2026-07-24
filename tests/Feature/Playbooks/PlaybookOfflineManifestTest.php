<?php

namespace Tests\Feature\Playbooks;

use Tests\TestCase;

class PlaybookOfflineManifestTest extends TestCase
{
    public function test_story_offline_manifest_returns_urls(): void
    {
        $response = $this->getJson('/playbooks/help-hub-platform/offline-manifest');

        $response->assertOk();
        $response->assertJsonPath('slug', 'help-hub-platform');
        $response->assertJsonStructure([
            'slug',
            'title',
            'titleDe',
            'titleEn',
            'modifiedAt',
            'bytesEstimate',
            'pageUrl',
            'urls',
        ]);

        $urls = $response->json('urls');
        $this->assertIsArray($urls);
        $this->assertNotEmpty($urls);
        $this->assertTrue(
            collect($urls)->contains(fn (string $url): bool => str_contains($url, 'help-hub-platform')),
            'Manifest should include the story page URL.',
        );
    }

    public function test_bulk_offline_manifest_lists_visible_stories(): void
    {
        $response = $this->getJson('/playbooks/offline-manifest');

        $response->assertOk();
        $response->assertJsonStructure([
            'bytesEstimate',
            'shellUrls',
            'indexUrls',
            'stories',
        ]);

        $stories = $response->json('stories');
        $this->assertIsArray($stories);
        $this->assertNotEmpty($stories);

        $slugs = collect($stories)->pluck('slug')->all();
        $this->assertContains('help-hub-platform', $slugs);
    }

    public function test_unknown_story_manifest_is_not_found(): void
    {
        $this->getJson('/playbooks/does-not-exist-story/offline-manifest')->assertNotFound();
    }

    public function test_series_offline_manifest_lists_series_parts(): void
    {
        $response = $this->getJson('/playbooks/series/building-modern-data-warehouse/offline-manifest');

        $response->assertOk();
        $response->assertJsonPath('seriesId', 'building-modern-data-warehouse');
        $response->assertJsonStructure([
            'seriesId',
            'title',
            'titleDe',
            'titleEn',
            'bytesEstimate',
            'shellUrls',
            'indexUrls',
            'stories',
        ]);

        $stories = $response->json('stories');
        $this->assertIsArray($stories);
        $this->assertNotEmpty($stories);

        $slugs = collect($stories)->pluck('slug')->all();
        $this->assertContains('beyond-bronze-silver-gold', $slugs);
    }

    public function test_unknown_series_manifest_is_not_found(): void
    {
        $this->getJson('/playbooks/series/does-not-exist-series/offline-manifest')->assertNotFound();
    }
}
