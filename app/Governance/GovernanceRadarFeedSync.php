<?php

namespace App\Governance;

use App\Accounts\AccountUser;
use InvalidArgumentException;

final class GovernanceRadarFeedSync
{
    public function __construct(
        private readonly GovernanceRadarFeedFetchGuard $fetchGuard,
        private readonly GovernanceRadarFeedParser $parser,
        private readonly GovernanceRadarFeedItemStore $store,
        private readonly GovernanceRadarSourceStore $sourceStore,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function ingestibleSources(?AccountUser $user = null): array
    {
        /** @var list<array<string, mixed>> $configSources */
        $configSources = config('governance-radar.sources', []);
        $sources = [];

        foreach ($configSources as $source) {
            if (! ($source['ingest'] ?? false)) {
                continue;
            }
            $feedUrl = trim((string) ($source['feed_url'] ?? ''));
            if ($feedUrl === '') {
                continue;
            }
            $sources[] = $source;
        }

        if ($user !== null) {
            foreach ($this->sourceStore->listFor($user) as $custom) {
                if (! ($custom['active'] ?? true)) {
                    continue;
                }
                $feedUrl = trim((string) ($custom['feedUrl'] ?? ''));
                if ($feedUrl === '' || ! $this->looksLikeFeedUrl($feedUrl)) {
                    continue;
                }
                $sources[] = [
                    'id' => $custom['id'],
                    'name' => $custom['name'] ?? $custom['id'],
                    'short_name' => $custom['name'] ?? $custom['id'],
                    'type' => $custom['type'] ?? 'Custom',
                    'region' => $custom['region'] ?? 'Global',
                    'language' => $custom['language'] ?? 'en',
                    'topics' => $custom['topics'] ?? [],
                    'feed_url' => $feedUrl,
                    'source_url' => $custom['sourceUrl'] ?? $feedUrl,
                    'ingest' => true,
                    'item_type' => null,
                    'stack' => [],
                ];
            }
        }

        return $sources;
    }

    /**
     * @param  list<string>|null  $onlySourceIds
     * @return array{synced: int, failed: int, skipped: int, errors: list<string>, statuses: array<string, array<string, mixed>>}
     */
    public function sync(?AccountUser $user = null, ?array $onlySourceIds = null, ?int $maxSources = null, ?int $budgetSeconds = null): array
    {
        $started = microtime(true);
        $budget = $budgetSeconds ?? 0;
        $synced = 0;
        $failed = 0;
        $skipped = 0;
        $errors = [];
        $attempted = 0;

        foreach ($this->ingestibleSources($user) as $source) {
            $sourceId = (string) ($source['id'] ?? '');
            if ($sourceId === '') {
                continue;
            }
            if ($onlySourceIds !== null && ! in_array($sourceId, $onlySourceIds, true)) {
                continue;
            }
            if ($maxSources !== null && $attempted >= $maxSources) {
                $skipped++;
                continue;
            }
            if ($budget > 0 && (microtime(true) - $started) >= $budget) {
                $skipped++;
                continue;
            }

            $attempted++;
            try {
                $this->syncSource($source);
                $synced++;
            } catch (\Throwable $exception) {
                $failed++;
                $message = $exception->getMessage();
                $errors[] = $sourceId.': '.$message;
                $this->store->writeSyncStatus(
                    $sourceId,
                    (string) ($source['feed_url'] ?? ''),
                    'error',
                    $message,
                    0,
                );
            }
        }

        return [
            'synced' => $synced,
            'failed' => $failed,
            'skipped' => $skipped,
            'errors' => $errors,
            'statuses' => $this->store->syncStatusesBySourceId(),
        ];
    }

    /**
     * Sync only stale ingestible sources (for on-demand page refresh).
     *
     * @return array{synced: int, failed: int, skipped: int, errors: list<string>, statuses: array<string, array<string, mixed>>}
     */
    public function ensureFresh(?AccountUser $user = null): array
    {
        $ttlMinutes = (int) config('governance-radar.ingest.ttl_minutes', 45);
        $maxSources = (int) config('governance-radar.ingest.max_sources_per_request', 3);
        $budget = (int) config('governance-radar.ingest.request_budget_seconds', 8);
        $statuses = $this->store->syncStatusesBySourceId();
        $staleIds = [];

        foreach ($this->ingestibleSources($user) as $source) {
            $sourceId = (string) ($source['id'] ?? '');
            if ($sourceId === '') {
                continue;
            }
            $sync = $statuses[$sourceId] ?? null;
            $lastSynced = is_array($sync) ? (string) ($sync['last_synced_at'] ?? '') : '';
            if ($lastSynced === '') {
                $staleIds[] = $sourceId;
                continue;
            }
            try {
                $ageMinutes = (now()->getTimestamp() - (new \DateTimeImmutable($lastSynced))->getTimestamp()) / 60;
            } catch (\Throwable) {
                $staleIds[] = $sourceId;
                continue;
            }
            if ($ageMinutes >= $ttlMinutes || (($sync['last_status'] ?? '') === 'error')) {
                $staleIds[] = $sourceId;
            }
        }

        if ($staleIds === []) {
            return [
                'synced' => 0,
                'failed' => 0,
                'skipped' => 0,
                'errors' => [],
                'statuses' => $statuses,
            ];
        }

        return $this->sync($user, $staleIds, $maxSources, $budget);
    }

    /**
     * @param  array<string, mixed>  $source
     */
    public function syncSource(array $source): int
    {
        $sourceId = (string) ($source['id'] ?? '');
        $feedUrl = trim((string) ($source['feed_url'] ?? ''));
        if ($sourceId === '' || $feedUrl === '') {
            throw new InvalidArgumentException('Source id and feed_url are required.');
        }

        $xml = $this->fetchGuard->fetch($feedUrl);
        $entries = $this->parser->parse($xml);
        $entries = $this->filterEntries($entries, $source);
        $limit = (int) ($source['ingest_limit'] ?? config('governance-radar.ingest.default_limit', 8));
        $entries = array_slice($entries, 0, max(1, $limit));

        return $this->store->replaceSourceItems(
            $sourceId,
            $feedUrl,
            $entries,
            (string) ($source['language'] ?? 'en'),
        );
    }

    /**
     * @param  list<array{guid: string, title: string, summary: string, url: string, published_at: ?string, topics: list<string>}>  $entries
     * @param  array<string, mixed>  $source
     * @return list<array{guid: string, title: string, summary: string, url: string, published_at: ?string, topics: list<string>}>
     */
    private function filterEntries(array $entries, array $source): array
    {
        $keywords = $source['ingest_keywords'] ?? null;
        if (! is_array($keywords) || $keywords === []) {
            // Loud vendor/blog feeds without explicit keywords keep all entries.
            // Search-style / broad blogs should set ingest_keywords in config.
            return $entries;
        }

        $normalized = array_values(array_filter(array_map(
            static fn ($keyword): string => mb_strtolower(trim((string) $keyword)),
            $keywords,
        )));
        if ($normalized === []) {
            return $entries;
        }

        return array_values(array_filter($entries, static function (array $entry) use ($normalized): bool {
            $haystack = mb_strtolower(implode(' ', [
                $entry['title'] ?? '',
                $entry['summary'] ?? '',
                implode(' ', $entry['topics'] ?? []),
            ]));
            foreach ($normalized as $keyword) {
                if ($keyword !== '' && str_contains($haystack, $keyword)) {
                    return true;
                }
            }

            return false;
        }));
    }

    private function looksLikeFeedUrl(string $url): bool
    {
        $lower = mb_strtolower($url);

        return str_contains($lower, 'rss')
            || str_contains($lower, 'atom')
            || str_contains($lower, 'feed')
            || str_ends_with($lower, '.xml');
    }
}
