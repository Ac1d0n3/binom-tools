<?php

namespace App\Governance;

final class GovernanceRadarFeedDisplay
{
    public function __construct(
        private readonly GovernanceRadarFeedItemStore $store,
    ) {}

    /**
     * @param  list<array<string, mixed>>  $sources
     * @return list<array<string, mixed>>
     */
    public function displayItems(array $sources): array
    {
        $sourcesById = [];
        foreach ($sources as $source) {
            $id = (string) ($source['id'] ?? '');
            if ($id !== '') {
                $sourcesById[$id] = $source;
            }
        }

        $typeMap = config('governance-radar.ingest.source_type_to_item_type', []);
        $items = [];

        foreach ($this->store->allItems() as $row) {
            $sourceId = (string) ($row['source_id'] ?? '');
            $source = $sourcesById[$sourceId] ?? null;
            if ($source === null) {
                continue;
            }

            $sourceType = (string) ($source['type'] ?? 'Custom');
            $itemType = (string) ($source['item_type'] ?? ($typeMap[$sourceType] ?? 'Governance News'));
            $topics = array_values(array_unique(array_filter([
                ...((array) ($source['topics'] ?? [])),
                ...((array) ($row['raw_topics'] ?? [])),
            ], static fn ($topic): bool => is_string($topic) && $topic !== '')));

            $stack = (array) ($source['stack'] ?? []);
            if ($stack === []) {
                $short = (string) ($source['short_name'] ?? $source['name'] ?? '');
                if ($short !== '' && in_array($sourceType, ['Vendor'], true)) {
                    $stack = [$this->guessStack($short, $sourceId)];
                }
            }
            $stack = array_values(array_filter($stack, static fn ($value): bool => is_string($value) && $value !== ''));

            $published = (string) ($row['published_at'] ?? '');
            $publishedDate = $published !== '' ? substr($published, 0, 10) : null;
            $guidHash = substr(hash('sha256', $sourceId.'|'.(string) ($row['guid'] ?? '')), 0, 16);
            $summary = trim((string) ($row['summary'] ?? ''));
            if ($summary === '') {
                $summary = 'Live feed update from '.(string) ($source['short_name'] ?? $source['name'] ?? $sourceId).'.';
            }

            $items[] = [
                'id' => 'feed:'.$sourceId.':'.$guidHash,
                'source_id' => $sourceId,
                'title' => (string) ($row['title'] ?? 'Untitled update'),
                'summary' => $summary,
                'published_at' => $publishedDate,
                'type' => $itemType,
                'impact' => 'Beobachten',
                'region' => (string) ($source['region'] ?? 'Global'),
                'topics' => $topics,
                'stack' => $stack,
                'url' => (string) ($row['url'] ?? $source['source_url'] ?? $source['feed_url'] ?? ''),
                'recommended_action' => 'Feed-Update prüfen und nur bei Governance-Relevanz in den Advisor nehmen.',
                'origin' => 'feed',
                'language' => (string) ($row['language'] ?? $source['language'] ?? 'en'),
                'enrichable' => false,
            ];
        }

        return $items;
    }

    private function guessStack(string $shortName, string $sourceId): string
    {
        $hay = mb_strtolower($shortName.' '.$sourceId);
        return match (true) {
            str_contains($hay, 'snowflake') => 'Snowflake',
            str_contains($hay, 'databricks') => 'Databricks',
            str_contains($hay, 'fabric') || str_contains($hay, 'purview') => 'Fabric',
            str_contains($hay, 'power bi') || str_contains($hay, 'power-bi') => 'Power BI',
            str_contains($hay, 'qlik') => 'Qlik',
            str_contains($hay, 'tableau') => 'Tableau',
            str_contains($hay, 'dbt') => 'dbt',
            str_contains($hay, 'aws') => 'AWS',
            str_contains($hay, 'gcp') || str_contains($hay, 'google') => 'GCP',
            str_contains($hay, 'alation') => 'Alation',
            str_contains($hay, 'collibra') => 'Collibra',
            default => $shortName,
        };
    }
}
