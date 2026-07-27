<?php

namespace App\Governance;

use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Models\BnTools\BnGovernanceRadarFeedItem;
use App\Models\BnTools\BnGovernanceRadarFeedSync;
use App\Support\StorageDriver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

final class GovernanceRadarFeedItemStore
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $files,
    ) {}

    private function useDatabase(): bool
    {
        return StorageDriver::isMysql()
            && Schema::hasTable('bn_governance_radar_feed_items')
            && Schema::hasTable('bn_governance_radar_feed_syncs');
    }

    /**
     * @param  list<array{guid: string, title: string, summary: string, url: string, published_at: ?string, topics: list<string>}>  $entries
     */
    public function replaceSourceItems(string $sourceId, string $feedUrl, array $entries, string $language = 'en'): int
    {
        $now = now();
        $rows = [];
        foreach ($entries as $entry) {
            $rows[] = [
                'source_id' => $sourceId,
                'guid' => mb_substr((string) ($entry['guid'] ?? ''), 0, 500),
                'title' => mb_substr((string) ($entry['title'] ?? ''), 0, 500),
                'summary' => (string) ($entry['summary'] ?? ''),
                'url' => mb_substr((string) ($entry['url'] ?? ''), 0, 1000),
                'published_at' => $entry['published_at'] ?? null,
                'language' => $language,
                'raw_topics' => array_values(array_filter((array) ($entry['topics'] ?? []))),
                'fetched_at' => $now->toIso8601String(),
                'updated_at' => $now->toIso8601String(),
                'created_at' => $now->toIso8601String(),
            ];
        }

        if ($this->useDatabase()) {
            BnGovernanceRadarFeedItem::query()->where('source_id', $sourceId)->delete();
            foreach ($rows as $row) {
                BnGovernanceRadarFeedItem::query()->create([
                    'source_id' => $row['source_id'],
                    'guid' => $row['guid'],
                    'title' => $row['title'],
                    'summary' => $row['summary'],
                    'url' => $row['url'] !== '' ? $row['url'] : null,
                    'published_at' => $row['published_at'],
                    'language' => $row['language'],
                    'raw_topics' => $row['raw_topics'],
                    'fetched_at' => $now,
                ]);
            }
            $this->writeSyncStatus($sourceId, $feedUrl, 'ok', null, count($rows));

            return count($rows);
        }

        $payload = $this->readItemsFile();
        $payload['items'] = array_values(array_filter(
            $payload['items'],
            static fn (array $item): bool => ($item['source_id'] ?? '') !== $sourceId,
        ));
        foreach ($rows as $row) {
            $payload['items'][] = $row;
        }
        $payload['updatedAt'] = $now->toIso8601String();
        $this->files->write($this->config->governanceRadarFeedItemsPath(), $payload);
        $this->writeSyncStatus($sourceId, $feedUrl, 'ok', null, count($rows));

        return count($rows);
    }

    public function writeSyncStatus(string $sourceId, string $feedUrl, string $status, ?string $error, int $itemCount): void
    {
        $now = now();

        if ($this->useDatabase()) {
            BnGovernanceRadarFeedSync::query()->updateOrCreate(
                ['source_id' => $sourceId],
                [
                    'feed_url' => mb_substr($feedUrl, 0, 1000),
                    'last_synced_at' => $now,
                    'last_status' => $status,
                    'last_error' => $error,
                    'item_count' => $itemCount,
                ],
            );

            return;
        }

        $payload = $this->readSyncsFile();
        $payload['syncs'][$sourceId] = [
            'source_id' => $sourceId,
            'feed_url' => $feedUrl,
            'last_synced_at' => $now->toIso8601String(),
            'last_status' => $status,
            'last_error' => $error,
            'item_count' => $itemCount,
            'updated_at' => $now->toIso8601String(),
        ];
        $payload['updatedAt'] = $now->toIso8601String();
        $this->files->write($this->config->governanceRadarFeedSyncsPath(), $payload);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function allItems(): array
    {
        if ($this->useDatabase()) {
            return BnGovernanceRadarFeedItem::query()
                ->orderByDesc('published_at')
                ->orderByDesc('id')
                ->get()
                ->map(static fn (BnGovernanceRadarFeedItem $row): array => [
                    'source_id' => $row->source_id,
                    'guid' => $row->guid,
                    'title' => $row->title,
                    'summary' => (string) $row->summary,
                    'url' => (string) ($row->url ?? ''),
                    'published_at' => $row->published_at?->format('Y-m-d H:i:s'),
                    'language' => $row->language,
                    'raw_topics' => $row->raw_topics ?? [],
                    'fetched_at' => $row->fetched_at?->toIso8601String(),
                ])
                ->all();
        }

        $items = $this->readItemsFile()['items'];
        usort($items, static function (array $a, array $b): int {
            return strcmp((string) ($b['published_at'] ?? ''), (string) ($a['published_at'] ?? ''));
        });

        return $items;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function syncStatusesBySourceId(): array
    {
        if ($this->useDatabase()) {
            $byId = [];
            foreach (BnGovernanceRadarFeedSync::query()->get() as $row) {
                $byId[$row->source_id] = [
                    'source_id' => $row->source_id,
                    'feed_url' => $row->feed_url,
                    'last_synced_at' => $row->last_synced_at?->toIso8601String(),
                    'last_status' => $row->last_status,
                    'last_error' => $row->last_error,
                    'item_count' => (int) $row->item_count,
                ];
            }

            return $byId;
        }

        return $this->readSyncsFile()['syncs'];
    }

    public function latestSyncedAt(): ?string
    {
        $times = [];
        foreach ($this->syncStatusesBySourceId() as $sync) {
            $value = (string) ($sync['last_synced_at'] ?? '');
            if ($value !== '') {
                $times[] = $value;
            }
        }
        if ($times === []) {
            return null;
        }
        rsort($times);

        return $times[0];
    }

    /**
     * Best-effort “list last updated” stamp for hub badges:
     * prefer last successful feed sync, else newest published_at across feed + curated items.
     */
    public function latestListUpdatedAt(): ?string
    {
        $sync = $this->latestSyncedAt();
        if ($sync !== null && $sync !== '') {
            return $sync;
        }

        $candidates = [];
        foreach ($this->allItems() as $item) {
            $value = trim((string) ($item['published_at'] ?? ''));
            if ($value !== '') {
                $candidates[] = $value;
            }
        }

        foreach (config('governance-radar.items', []) as $item) {
            if (! is_array($item)) {
                continue;
            }
            $value = trim((string) ($item['published_at'] ?? ''));
            if ($value !== '') {
                $candidates[] = $value;
            }
        }

        if ($candidates === []) {
            return null;
        }

        $normalized = [];
        foreach ($candidates as $candidate) {
            try {
                $normalized[] = Carbon::parse($candidate)->toIso8601String();
            } catch (\Throwable) {
                // Ignore unparseable stamps.
            }
        }

        if ($normalized === []) {
            return null;
        }

        rsort($normalized);

        return $normalized[0];
    }

    /**
     * @return array{items: list<array<string, mixed>>, updatedAt: ?string}
     */
    private function readItemsFile(): array
    {
        $payload = $this->files->read($this->config->governanceRadarFeedItemsPath(), [
            'items' => [],
            'updatedAt' => null,
        ]);

        return [
            'items' => array_values(array_filter((array) ($payload['items'] ?? []), 'is_array')),
            'updatedAt' => is_string($payload['updatedAt'] ?? null) ? $payload['updatedAt'] : null,
        ];
    }

    /**
     * @return array{syncs: array<string, array<string, mixed>>, updatedAt: ?string}
     */
    private function readSyncsFile(): array
    {
        $payload = $this->files->read($this->config->governanceRadarFeedSyncsPath(), [
            'syncs' => [],
            'updatedAt' => null,
        ]);
        $syncs = [];
        foreach ((array) ($payload['syncs'] ?? []) as $key => $row) {
            if (! is_array($row)) {
                continue;
            }
            $id = (string) ($row['source_id'] ?? $key);
            if ($id !== '') {
                $syncs[$id] = $row;
            }
        }

        return [
            'syncs' => $syncs,
            'updatedAt' => is_string($payload['updatedAt'] ?? null) ? $payload['updatedAt'] : null,
        ];
    }
}
