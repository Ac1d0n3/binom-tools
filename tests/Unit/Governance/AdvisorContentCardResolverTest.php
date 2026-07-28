<?php

namespace Tests\Unit\Governance;

use App\Governance\AdvisorContentCardResolver;
use Tests\TestCase;

class AdvisorContentCardResolverTest extends TestCase
{
    public function test_resolves_migrated_story_cards_with_urls(): void
    {
        $resolver = new AdvisorContentCardResolver;
        $cards = $resolver->resolveContentCards(
            is_array(config('advisor-recommendations')) ? config('advisor-recommendations') : []
        );

        $this->assertNotEmpty($cards);
        $ids = array_map(static fn (array $c): string => (string) ($c['id'] ?? ''), $cards);
        $this->assertContains('story-eight-pillars', $ids);
        $this->assertContains('story-bridge-solution', $ids);
        $this->assertContains('story-metadata-catalog-lineage', $ids);

        foreach ($cards as $card) {
            $this->assertNotSame('', $card['url'] ?? '');
            $this->assertStringContainsString('/playbooks/', (string) $card['url']);
            $this->assertSame('story', $card['kind']);
        }
    }

    public function test_skips_invalid_refs_and_disabled_items(): void
    {
        $resolver = new AdvisorContentCardResolver;
        $cards = $resolver->resolveContentCards([
            'items' => [
                [
                    'id' => 'story-missing',
                    'kind' => 'story',
                    'ref' => 'does-not-exist-slug',
                    'enabled' => true,
                    'title' => ['de' => 'X', 'en' => 'X'],
                    'reason' => ['de' => 'Y', 'en' => 'Y'],
                ],
                [
                    'id' => 'supplier-salesforce',
                    'kind' => 'supplier',
                    'ref' => 'salesforce',
                    'enabled' => true,
                    'title' => ['de' => 'Salesforce', 'en' => 'Salesforce'],
                    'reason' => ['de' => 'CRM', 'en' => 'CRM'],
                    'tags' => ['crm', 'supplier'],
                ],
                [
                    'id' => 'vendor-databricks',
                    'kind' => 'vendor',
                    'ref' => 'databricks',
                    'enabled' => false,
                    'title' => ['de' => 'Databricks', 'en' => 'Databricks'],
                    'reason' => ['de' => 'Lakehouse', 'en' => 'Lakehouse'],
                ],
                [
                    'id' => 'vendor-databricks-on',
                    'kind' => 'vendor',
                    'ref' => 'databricks',
                    'enabled' => true,
                    'title' => ['de' => 'Databricks', 'en' => 'Databricks'],
                    'reason' => ['de' => 'Lakehouse', 'en' => 'Lakehouse'],
                ],
            ],
        ]);

        $ids = array_map(static fn (array $c): string => (string) ($c['id'] ?? ''), $cards);
        $this->assertNotContains('story-missing', $ids);
        $this->assertNotContains('vendor-databricks', $ids);
        $this->assertContains('supplier-salesforce', $ids);
        $this->assertContains('vendor-databricks-on', $ids);

        $supplier = collect($cards)->firstWhere('id', 'supplier-salesforce');
        $this->assertStringContainsString('/suppliers/salesforce', (string) ($supplier['url'] ?? ''));

        $vendor = collect($cards)->firstWhere('id', 'vendor-databricks-on');
        $this->assertStringContainsString('vendor=databricks', (string) ($vendor['url'] ?? ''));
    }

    public function test_guidance_story_urls_map_known_slugs(): void
    {
        $resolver = new AdvisorContentCardResolver;
        $cards = $resolver->resolveContentCards(config('advisor-recommendations') ?: []);
        $urls = $resolver->guidanceStoryUrls($cards);

        $this->assertArrayHasKey('eightPillars', $urls);
        $this->assertArrayHasKey('bridgeSolutionStory', $urls);
        $this->assertArrayHasKey('metadataCatalogStory', $urls);
        $this->assertStringContainsString('eight-pillars', $urls['eightPillars']);
    }
}
