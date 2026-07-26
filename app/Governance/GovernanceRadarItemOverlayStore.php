<?php

namespace App\Governance;

use App\Accounts\AccountUser;
use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Models\BnTools\BnGovernanceRadarItemOverlay;
use App\Support\StorageDriver;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

final class GovernanceRadarItemOverlayStore
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $files,
    ) {}

    /**
     * @return array<string, array<string, mixed>> keyed by item id
     */
    public function allByItemId(): array
    {
        $overlays = StorageDriver::isMysql() && Schema::hasTable('bn_governance_radar_item_overlays')
            ? $this->listFromDatabase()
            : $this->listFromFiles();

        $byId = [];
        foreach ($overlays as $overlay) {
            $id = (string) ($overlay['itemId'] ?? '');
            if ($id !== '') {
                $byId[$id] = $overlay;
            }
        }

        return $byId;
    }

    public function find(string $itemId): ?array
    {
        if (! $this->validItemId($itemId)) {
            throw new InvalidArgumentException('Invalid radar item id.');
        }

        return $this->allByItemId()[$itemId] ?? null;
    }

    public function save(AccountUser $admin, string $itemId, array $input): array
    {
        if (! $this->validItemId($itemId)) {
            throw new InvalidArgumentException('Invalid radar item id.');
        }
        if (! $this->configItemExists($itemId)) {
            throw new InvalidArgumentException('Unknown radar item id.');
        }

        $existing = $this->find($itemId);
        $overlay = $this->normalize([
            ...($existing ?? []),
            ...$input,
            'itemId' => $itemId,
            'updatedByUserId' => $admin->id,
            'createdAt' => $existing['createdAt'] ?? now()->toIso8601String(),
            'updatedAt' => now()->toIso8601String(),
        ]);

        if ($this->isEmptyOverlay($overlay)) {
            $this->delete($itemId);

            return $overlay;
        }

        if (StorageDriver::isMysql() && Schema::hasTable('bn_governance_radar_item_overlays')) {
            BnGovernanceRadarItemOverlay::query()->updateOrCreate(
                ['item_id' => $overlay['itemId']],
                [
                    'updated_by_user_id' => $overlay['updatedByUserId'],
                    'title_de' => $overlay['titleDe'],
                    'summary_de' => $overlay['summaryDe'],
                    'recommended_action_de' => $overlay['recommendedActionDe'],
                    'editorial_note' => $overlay['editorialNote'],
                    'impact' => $overlay['impact'],
                ],
            );
        } else {
            $overlays = $this->listFromFiles();
            $overlays = array_values(array_filter(
                $overlays,
                static fn (array $row): bool => ($row['itemId'] ?? '') !== $overlay['itemId'],
            ));
            $overlays[] = $overlay;
            $this->writeFiles($overlays);
        }

        return $overlay;
    }

    public function delete(string $itemId): void
    {
        if (! $this->validItemId($itemId)) {
            throw new InvalidArgumentException('Invalid radar item id.');
        }

        if (StorageDriver::isMysql() && Schema::hasTable('bn_governance_radar_item_overlays')) {
            BnGovernanceRadarItemOverlay::query()->where('item_id', $itemId)->delete();

            return;
        }

        $overlays = array_values(array_filter(
            $this->listFromFiles(),
            static fn (array $row): bool => ($row['itemId'] ?? '') !== $itemId,
        ));
        $this->writeFiles($overlays);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listFromDatabase(): array
    {
        return BnGovernanceRadarItemOverlay::query()
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (BnGovernanceRadarItemOverlay $row): array => [
                'itemId' => $row->item_id,
                'updatedByUserId' => $row->updated_by_user_id,
                'titleDe' => $row->title_de,
                'summaryDe' => $row->summary_de,
                'recommendedActionDe' => $row->recommended_action_de,
                'editorialNote' => $row->editorial_note,
                'impact' => $row->impact,
                'createdAt' => $row->created_at?->toIso8601String() ?? '',
                'updatedAt' => $row->updated_at?->toIso8601String() ?? '',
            ])
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function listFromFiles(): array
    {
        $this->files->ensureDirectory($this->config->basePath());
        $payload = $this->files->read($this->path(), [
            'overlays' => [],
        ]);
        if (! is_array($payload['overlays'] ?? null)) {
            return [];
        }

        return array_values(array_map(
            fn (array $overlay): array => $this->normalize($overlay),
            $payload['overlays'],
        ));
    }

    /**
     * @param  list<array<string, mixed>>  $overlays
     */
    private function writeFiles(array $overlays): void
    {
        $this->files->write($this->path(), [
            'overlays' => array_values($overlays),
            'updatedAt' => now()->toIso8601String(),
        ]);
    }

    private function normalize(array $input): array
    {
        return [
            'itemId' => (string) ($input['itemId'] ?? $input['item_id'] ?? ''),
            'updatedByUserId' => $this->optionalString($input['updatedByUserId'] ?? $input['updated_by_user_id'] ?? null, 64),
            'titleDe' => $this->optionalString($input['titleDe'] ?? $input['title_de'] ?? null, 500),
            'summaryDe' => $this->optionalString($input['summaryDe'] ?? $input['summary_de'] ?? null, 4000),
            'recommendedActionDe' => $this->optionalString(
                $input['recommendedActionDe'] ?? $input['recommended_action_de'] ?? null,
                2000,
            ),
            'editorialNote' => $this->optionalString($input['editorialNote'] ?? $input['editorial_note'] ?? null, 2000),
            'impact' => $this->optionalString($input['impact'] ?? null, 64),
            'createdAt' => (string) ($input['createdAt'] ?? now()->toIso8601String()),
            'updatedAt' => (string) ($input['updatedAt'] ?? now()->toIso8601String()),
        ];
    }

    private function isEmptyOverlay(array $overlay): bool
    {
        return ($overlay['titleDe'] ?? null) === null
            && ($overlay['summaryDe'] ?? null) === null
            && ($overlay['recommendedActionDe'] ?? null) === null
            && ($overlay['editorialNote'] ?? null) === null
            && ($overlay['impact'] ?? null) === null;
    }

    private function optionalString(mixed $value, int $max): ?string
    {
        $value = trim((string) ($value ?? ''));

        return $value === '' ? null : mb_substr($value, 0, $max);
    }

    private function validItemId(string $id): bool
    {
        return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9_-]{1,118}$/', $id) === 1;
    }

    private function configItemExists(string $itemId): bool
    {
        /** @var list<array<string, mixed>> $items */
        $items = config('governance-radar.items', []);
        foreach ($items as $item) {
            if (($item['id'] ?? '') === $itemId) {
                return true;
            }
        }

        return false;
    }

    private function path(): string
    {
        return $this->config->governanceRadarOverlaysPath();
    }
}
