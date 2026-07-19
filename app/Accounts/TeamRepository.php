<?php

namespace App\Accounts;

use InvalidArgumentException;

final class TeamRepository
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    /**
     * @return list<AccountTeam>
     */
    public function all(bool $includeArchived = false): array
    {
        return array_values(array_filter(
            $this->indexed(),
            static fn (AccountTeam $team): bool => $includeArchived || ! $team->archived,
        ));
    }

    public function findById(string $id): ?AccountTeam
    {
        return $this->indexed()[$id] ?? null;
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public function upsert(array $input): AccountTeam
    {
        $teams = $this->indexed();
        $id = (string) ($input['id'] ?? '');
        if ($id === '') {
            $label = (string) (is_array($input['name'] ?? null)
                ? ($input['name']['en'] ?? $input['name']['de'] ?? 'team')
                : ($input['name'] ?? 'team'));
            $id = 'team_'.preg_replace('/[^a-z0-9]+/', '_', strtolower($label));
            $id = trim((string) $id, '_');
            while ($id === 'team' || isset($teams[$id])) {
                $id = 'team_'.bin2hex(random_bytes(3));
            }
        }

        $current = $teams[$id] ?? null;
        $team = AccountTeam::fromArray([
            'id' => $id,
            'name' => $input['name'] ?? $current?->name ?? ['de' => $id, 'en' => $id],
            'description' => $input['description'] ?? $current?->description ?? ['de' => '', 'en' => ''],
            'memberIds' => $input['memberIds'] ?? $current?->memberIds ?? [],
            'memberRoles' => $input['memberRoles'] ?? $current?->memberRoles ?? [],
            'archived' => $input['archived'] ?? $current?->archived ?? false,
            'shortName' => $input['shortName'] ?? $current?->shortName ?? '',
            'colorToken' => $input['colorToken'] ?? $current?->colorToken ?? 'accent-1',
            'avatarIcon' => array_key_exists('avatarIcon', $input)
                ? $input['avatarIcon']
                : ($current?->avatarIcon ?? ''),
        ]);

        $teams[$id] = $team;
        $this->persist($teams);

        return $team;
    }

    public function delete(string $id): void
    {
        $teams = $this->indexed();
        if (! isset($teams[$id])) {
            return;
        }
        unset($teams[$id]);
        $this->persist($teams);
    }

    /**
     * @return array<string, AccountTeam>
     */
    private function indexed(): array
    {
        $raw = $this->store->read($this->config->teamsPath(), [
            'schemaVersion' => 1,
            'teams' => [],
        ]);
        $list = $raw['teams'] ?? [];
        if (! is_array($list)) {
            return [];
        }

        $out = [];
        foreach ($list as $row) {
            if (! is_array($row)) {
                continue;
            }
            $team = AccountTeam::fromArray($row);
            $out[$team->id] = $team;
        }

        return $out;
    }

    /**
     * @param  array<string, AccountTeam>  $teams
     */
    private function persist(array $teams): void
    {
        $this->store->write($this->config->teamsPath(), [
            'schemaVersion' => 1,
            'teams' => array_map(
                static fn (AccountTeam $team): array => $team->toArray(),
                array_values($teams),
            ),
        ]);
    }
}
