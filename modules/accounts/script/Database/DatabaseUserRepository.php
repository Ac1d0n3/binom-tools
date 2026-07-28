<?php

namespace App\Accounts\Database;

use App\Accounts\AccountUser;
use App\Accounts\Contracts\UserRepositoryInterface;
use App\Models\BnTools\BnUser;
use InvalidArgumentException;

final class DatabaseUserRepository implements UserRepositoryInterface
{
    public function all(): array
    {
        return BnUser::query()
            ->orderBy('email')
            ->get()
            ->map(fn (BnUser $row): AccountUser => $this->toUser($row))
            ->all();
    }

    public function findById(string $id): ?AccountUser
    {
        $row = BnUser::query()->find($id);

        return $row ? $this->toUser($row) : null;
    }

    public function findByEmail(string $email): ?AccountUser
    {
        $email = strtolower(trim($email));
        $row = BnUser::query()->where('email', $email)->first();

        return $row ? $this->toUser($row) : null;
    }

    public function upsert(array $input): AccountUser
    {
        if (isset($input['password']) || isset($input['password_plaintext']) || isset($input['plainPassword'])) {
            throw new InvalidArgumentException('Plaintext password fields must not be persisted.');
        }

        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $id = (string) ($input['id'] ?? '');
        $current = $id !== '' ? $this->findById($id) : null;

        if ($id === '') {
            $id = 'user_'.preg_replace('/[^a-z0-9]+/', '_', pathinfo($email, PATHINFO_FILENAME) ?: 'account');
            $id = trim((string) $id, '_');
            if ($id === 'user' || $id === '') {
                $id = 'user_'.bin2hex(random_bytes(4));
            }
            while ($this->findById($id) !== null) {
                $id = 'user_'.bin2hex(random_bytes(4));
            }
        } else {
            $current = $this->findById($id);
        }

        $byEmail = $this->findByEmail($email);
        if ($byEmail !== null && $byEmail->id !== $id) {
            throw new InvalidArgumentException("Email already in use: {$email}");
        }

        $hash = (string) ($input['passwordHash'] ?? $current?->passwordHash ?? '');
        if ($hash === '' || ! str_starts_with($hash, '$')) {
            throw new InvalidArgumentException('passwordHash is required and must be a password_hash() digest.');
        }

        $payload = [
            'id' => $id,
            'email' => $email,
            'displayName' => $input['displayName'] ?? $current?->displayName ?? $email,
            'passwordHash' => $hash,
            'teamIds' => $input['teamIds'] ?? $current?->teamIds ?? [],
            'canManageUsers' => $input['canManageUsers'] ?? $current?->canManageUsers ?? false,
            'canManageTeams' => $input['canManageTeams'] ?? $current?->canManageTeams ?? false,
            'active' => $input['active'] ?? $current?->active ?? true,
            'pendingApproval' => array_key_exists('pendingApproval', $input)
                ? (bool) $input['pendingApproval']
                : ($current?->pendingApproval ?? false),
            'shortName' => $input['shortName'] ?? $current?->shortName ?? '',
            'colorToken' => $input['colorToken'] ?? $current?->colorToken ?? 'accent-1',
            'avatarIcon' => array_key_exists('avatarIcon', $input)
                ? $input['avatarIcon']
                : ($current?->avatarIcon ?? ''),
            'mustChangePassword' => array_key_exists('mustChangePassword', $input)
                ? (bool) $input['mustChangePassword']
                : ($current?->mustChangePassword ?? false),
        ];
        if (array_key_exists('canManageContent', $input)) {
            $payload['canManageContent'] = (bool) $input['canManageContent'];
        } elseif ($current !== null) {
            $payload['canManageContent'] = $current->canManageContent;
        }
        if (array_key_exists('contentAreas', $input)) {
            $payload['contentAreas'] = $input['contentAreas'];
        } elseif ($current !== null) {
            $payload['contentAreas'] = $current->contentAreas;
        }

        $user = AccountUser::fromArray($payload);

        BnUser::query()->updateOrCreate(
            ['id' => $user->id],
            [
                'email' => $user->email,
                'display_name' => $user->displayName,
                'password_hash' => $user->passwordHash,
                'team_ids' => $user->teamIds,
                'can_manage_users' => $user->canManageUsers,
                'can_manage_teams' => $user->canManageTeams,
                'can_manage_content' => $user->canManageContent,
                'content_areas' => $user->contentAreas,
                'active' => $user->active,
                'pending_approval' => $user->pendingApproval,
                'short_name' => $user->shortName,
                'color_token' => $user->colorToken,
                'avatar_icon' => $user->avatarIcon,
                'must_change_password' => $user->mustChangePassword,
            ],
        );

        return $user;
    }

    public function setPasswordHash(string $emailOrId, string $passwordHash): AccountUser
    {
        if ($passwordHash === '' || ! str_starts_with($passwordHash, '$')) {
            throw new InvalidArgumentException('Only password_hash() digests are accepted.');
        }

        $user = $this->findByEmail($emailOrId) ?? $this->findById($emailOrId);
        if ($user === null) {
            throw new InvalidArgumentException('User not found.');
        }

        return $this->upsert([
            ...$user->toArray(),
            'passwordHash' => $passwordHash,
        ]);
    }

    public function delete(string $id): void
    {
        BnUser::query()->where('id', $id)->delete();
    }

    private function toUser(BnUser $row): AccountUser
    {
        return AccountUser::fromArray([
            'id' => $row->id,
            'email' => $row->email,
            'displayName' => $row->display_name,
            'passwordHash' => $row->password_hash,
            'teamIds' => is_array($row->team_ids) ? $row->team_ids : [],
            'canManageUsers' => (bool) $row->can_manage_users,
            'canManageTeams' => (bool) $row->can_manage_teams,
            'canManageContent' => (bool) ($row->can_manage_content ?? false),
            'contentAreas' => is_array($row->content_areas ?? null) ? $row->content_areas : null,
            'active' => (bool) $row->active,
            'pendingApproval' => (bool) $row->pending_approval,
            'shortName' => (string) $row->short_name,
            'colorToken' => (string) $row->color_token,
            'avatarIcon' => (string) $row->avatar_icon,
            'mustChangePassword' => (bool) $row->must_change_password,
        ]);
    }
}
