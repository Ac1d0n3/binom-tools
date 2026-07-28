<?php

namespace Tests\Unit\Governance;

use App\Governance\AdvisorContentCardResolver;
use Tests\TestCase;

class AdvisorContentCardResolverTest extends TestCase
{
    public function test_resolves_catalog_story_and_series_cards(): void
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
        $this->assertContains('series-governance-pillars', $ids);
        $this->assertContains('series-metadata-deep-dive', $ids);
        $this->assertContains('story-pii-privacy-governance', $ids);
        $this->assertContains('story-data-architect-role', $ids);
        $this->assertContains('story-one-app', $ids);
        $this->assertContains('story-end-to-end-governance-architecture', $ids);
        $this->assertContains('story-ai-gov', $ids);

        $kinds = [];
        foreach ($cards as $card) {
            $this->assertNotSame('', $card['url'] ?? '');
            $this->assertStringContainsString('/playbooks/', (string) $card['url']);
            $kinds[(string) ($card['kind'] ?? '')] = true;
        }
        $this->assertArrayHasKey('story', $kinds);
        $this->assertArrayHasKey('series', $kinds);

        $series = collect($cards)->firstWhere('id', 'series-governance-pillars');
        $this->assertNotNull($series);
        $this->assertStringContainsString('/playbooks/series/governance-pillars', (string) ($series['url'] ?? ''));
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
                    'id' => 'series-missing',
                    'kind' => 'series',
                    'ref' => 'not-a-real-series',
                    'enabled' => true,
                    'title' => ['de' => 'X', 'en' => 'X'],
                    'reason' => ['de' => 'Y', 'en' => 'Y'],
                ],
                [
                    'id' => 'series-pillars-on',
                    'kind' => 'series',
                    'ref' => 'governance-pillars',
                    'enabled' => true,
                    'title' => ['de' => 'Pillars', 'en' => 'Pillars'],
                    'reason' => ['de' => 'Serie', 'en' => 'Series'],
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
        $this->assertNotContains('series-missing', $ids);
        $this->assertNotContains('vendor-databricks', $ids);
        $this->assertContains('series-pillars-on', $ids);
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

    public function test_when_filters_match_goal_and_role_smoke(): void
    {
        $resolver = new AdvisorContentCardResolver;
        $cards = $resolver->resolveContentCards(config('advisor-recommendations') ?: []);

        $pii = array_values(array_filter(
            $cards,
            static function (array $card): bool {
                $goals = $card['when']['goals'] ?? [];

                return $card['kind'] === 'story'
                    && is_array($goals)
                    && in_array('pii', $goals, true);
            }
        ));
        $piiIds = array_map(static fn (array $c): string => (string) $c['id'], $pii);
        $this->assertContains('story-pii-privacy-governance', $piiIds);
        $this->assertContains('story-dsdr-governance', $piiIds);

        $architect = array_values(array_filter(
            $cards,
            static function (array $card): bool {
                $roles = $card['when']['roles'] ?? [];

                return is_array($roles) && in_array('architect', $roles, true);
            }
        ));
        $architectIds = array_map(static fn (array $c): string => (string) $c['id'], $architect);
        $this->assertContains('story-data-architect-role', $architectIds);
    }
}
