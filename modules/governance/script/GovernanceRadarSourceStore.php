<?php

namespace App\Governance;

use App\Accounts\AccountUser;
use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Models\BnTools\BnGovernanceRadarSource;
use App\Support\StorageDriver;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

final class GovernanceRadarSourceStore
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $files,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function listFor(AccountUser $user): array
    {
        $sources = StorageDriver::isMysql() && Schema::hasTable('bn_governance_radar_sources')
            ? $this->listFromDatabase($user)
            : $this->listFromFiles($user);

        usort($sources, static fn (array $a, array $b): int => strcmp((string) ($b['updatedAt'] ?? ''), (string) ($a['updatedAt'] ?? '')));

        return array_values($sources);
    }

    public function save(AccountUser $user, array $input): array
    {
        $id = (string) ($input['id'] ?? '');
        if ($id !== '' && ! $this->validId($id)) {
            throw new InvalidArgumentException('Invalid radar source id.');
        }
        if ($id === '') {
            $id = $this->createId();
        }

        $existing = $this->findFor($user, $id);
        $source = $this->normalize([
            ...($existing ?? []),
            ...$input,
            'id' => $id,
            'ownerUserId' => $user->id,
            'createdAt' => $existing['createdAt'] ?? now()->toIso8601String(),
            'updatedAt' => now()->toIso8601String(),
        ], $user);

        if (StorageDriver::isMysql() && Schema::hasTable('bn_governance_radar_sources')) {
            BnGovernanceRadarSource::query()->updateOrCreate(
                ['id' => $source['id']],
                [
                    'owner_user_id' => $source['ownerUserId'],
                    'name' => $source['name'],
                    'feed_url' => $source['feedUrl'],
                    'source_url' => $source['sourceUrl'],
                    'type' => $source['type'],
                    'region' => $source['region'],
                    'language' => $source['language'],
                    'cadence' => $source['cadence'],
                    'topics' => $source['topics'],
                    'note' => $source['note'],
                    'active' => $source['active'],
                ],
            );
        } else {
            $sources = $this->listFromFiles($user);
            $sources = array_values(array_filter($sources, static fn (array $row): bool => ($row['id'] ?? '') !== $source['id']));
            $sources[] = $source;
            $this->files->write($this->pathForUser($user), [
                'ownerUserId' => $user->id,
                'sources' => $sources,
                'updatedAt' => now()->toIso8601String(),
            ]);
        }

        return $source;
    }

    public function delete(AccountUser $user, string $id): void
    {
        if (! $this->validId($id)) {
            throw new InvalidArgumentException('Invalid radar source id.');
        }

        if (StorageDriver::isMysql() && Schema::hasTable('bn_governance_radar_sources')) {
            BnGovernanceRadarSource::query()
                ->where('id', $id)
                ->where('owner_user_id', $user->id)
                ->delete();

            return;
        }

        $sources = array_values(array_filter($this->listFromFiles($user), static fn (array $row): bool => ($row['id'] ?? '') !== $id));
        $this->files->write($this->pathForUser($user), [
            'ownerUserId' => $user->id,
            'sources' => $sources,
            'updatedAt' => now()->toIso8601String(),
        ]);
    }

    private function findFor(AccountUser $user, string $id): ?array
    {
        foreach ($this->listFor($user) as $source) {
            if (($source['id'] ?? '') === $id) {
                return $source;
            }
        }

        return null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listFromDatabase(AccountUser $user): array
    {
        return BnGovernanceRadarSource::query()
            ->where('owner_user_id', $user->id)
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (BnGovernanceRadarSource $row): array => [
                'id' => $row->id,
                'ownerUserId' => $row->owner_user_id,
                'name' => $row->name,
                'feedUrl' => $row->feed_url,
                'sourceUrl' => $row->source_url,
                'type' => $row->type,
                'region' => $row->region,
                'language' => $row->language,
                'cadence' => $row->cadence,
                'topics' => is_array($row->topics) ? $row->topics : [],
                'note' => $row->note,
                'active' => (bool) $row->active,
                'createdAt' => $row->created_at?->toIso8601String() ?? '',
                'updatedAt' => $row->updated_at?->toIso8601String() ?? '',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listFromFiles(AccountUser $user): array
    {
        $this->files->ensureDirectory($this->config->governanceRadarSourcesDirectory());
        $payload = $this->files->read($this->pathForUser($user), [
            'ownerUserId' => $user->id,
            'sources' => [],
        ]);
        if (($payload['ownerUserId'] ?? null) !== $user->id || ! is_array($payload['sources'] ?? null)) {
            return [];
        }

        return array_values(array_map(fn (array $source): array => $this->normalize($source, $user), $payload['sources']));
    }

    private function normalize(array $input, AccountUser $user): array
    {
        $topics = $input['topics'] ?? [];
        if (is_string($topics)) {
            $topics = preg_split('/[,;\n]+/', $topics) ?: [];
        }
        if (! is_array($topics)) {
            $topics = [];
        }
        $topics = array_values(array_unique(array_filter(array_map(
            static fn (mixed $topic): string => mb_substr(trim((string) $topic), 0, 48),
            $topics,
        ))));

        return [
            'id' => (string) ($input['id'] ?? $this->createId()),
            'ownerUserId' => $user->id,
            'name' => mb_substr(trim((string) ($input['name'] ?? 'Eigene RSS-Quelle')), 0, 190),
            'feedUrl' => mb_substr(trim((string) ($input['feedUrl'] ?? $input['feed_url'] ?? '')), 0, 500),
            'sourceUrl' => $this->optionalString($input['sourceUrl'] ?? $input['source_url'] ?? null, 500),
            'type' => mb_substr(trim((string) ($input['type'] ?? 'Custom')), 0, 64),
            'region' => mb_substr(trim((string) ($input['region'] ?? 'Global')), 0, 64),
            'language' => mb_substr(trim((string) ($input['language'] ?? 'de')), 0, 16),
            'cadence' => mb_substr(trim((string) ($input['cadence'] ?? 'rss')), 0, 64),
            'topics' => $topics,
            'note' => $this->optionalString($input['note'] ?? null, 1000),
            'active' => (bool) ($input['active'] ?? true),
            'createdAt' => (string) ($input['createdAt'] ?? now()->toIso8601String()),
            'updatedAt' => (string) ($input['updatedAt'] ?? now()->toIso8601String()),
        ];
    }

    private function optionalString(mixed $value, int $max): ?string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? null : mb_substr($value, 0, $max);
    }

    private function validId(string $id): bool
    {
        return preg_match('/^radsrc_[a-zA-Z0-9_]+$/', $id) === 1;
    }

    private function createId(): string
    {
        return 'radsrc_'.date('Ymd_His').'_'.bin2hex(random_bytes(3));
    }

    private function pathForUser(AccountUser $user): string
    {
        $safe = preg_replace('/[^a-zA-Z0-9_]/', '', $user->id) ?: 'invalid';

        return $this->config->governanceRadarSourcesDirectory().DIRECTORY_SEPARATOR.$safe.'.json';
    }
}
