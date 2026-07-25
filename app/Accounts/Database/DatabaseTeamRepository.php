<?php

namespace App\Accounts\Database;

use App\Accounts\AccountTeam;
use App\Accounts\Contracts\TeamRepositoryInterface;
use App\Models\BnTools\BnTeam;

final class DatabaseTeamRepository implements TeamRepositoryInterface
{
    public function all(bool $includeArchived = false): array
    {
        $query = BnTeam::query()->orderBy('id');
        if (! $includeArchived) {
            $query->where('archived', false);
        }

        return $query->get()
            ->map(fn (BnTeam $row): AccountTeam => $this->toTeam($row))
            ->all();
    }

    public function findById(string $id): ?AccountTeam
    {
        $row = BnTeam::query()->find($id);

        return $row ? $this->toTeam($row) : null;
    }

    public function upsert(array $input): AccountTeam
    {
        $id = (string) ($input['id'] ?? '');
        $current = $id !== '' ? $this->findById($id) : null;

        if ($id === '') {
            $label = (string) (is_array($input['name'] ?? null)
                ? ($input['name']['en'] ?? $input['name']['de'] ?? 'team')
                : ($input['name'] ?? 'team'));
            $id = 'team_'.preg_replace('/[^a-z0-9]+/', '_', strtolower($label));
            $id = trim((string) $id, '_');
            while ($id === 'team' || $this->findById($id) !== null) {
                $id = 'team_'.bin2hex(random_bytes(3));
            }
        }

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

        BnTeam::query()->updateOrCreate(
            ['id' => $team->id],
            [
                'name' => $team->name,
                'description' => $team->description,
                'member_ids' => $team->memberIds,
                'member_roles' => $team->memberRoles,
                'archived' => $team->archived,
                'short_name' => $team->shortName,
                'color_token' => $team->colorToken,
                'avatar_icon' => $team->avatarIcon,
            ],
        );

        return $team;
    }

    public function delete(string $id): void
    {
        BnTeam::query()->where('id', $id)->delete();
    }

    private function toTeam(BnTeam $row): AccountTeam
    {
        return AccountTeam::fromArray([
            'id' => $row->id,
            'name' => is_array($row->name) ? $row->name : ['de' => $row->id, 'en' => $row->id],
            'description' => is_array($row->description) ? $row->description : ['de' => '', 'en' => ''],
            'memberIds' => is_array($row->member_ids) ? $row->member_ids : [],
            'memberRoles' => is_array($row->member_roles) ? $row->member_roles : [],
            'archived' => (bool) $row->archived,
            'shortName' => (string) $row->short_name,
            'colorToken' => (string) $row->color_token,
            'avatarIcon' => (string) $row->avatar_icon,
        ]);
    }
}
