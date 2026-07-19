<?php

namespace App\Accounts;

use App\Support\AccentColors;
use App\Support\AvatarIcons;

final class AccountUser
{
    /**
     * @param  list<string>  $teamIds
     */
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $displayName,
        public readonly string $passwordHash,
        public readonly array $teamIds,
        public readonly bool $canManageUsers,
        public readonly bool $canManageTeams,
        public readonly bool $active,
        public readonly string $shortName = '',
        public readonly string $colorToken = 'accent-1',
        public readonly string $avatarIcon = '',
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $email = strtolower(trim((string) ($data['email'] ?? '')));
        $hash = (string) ($data['passwordHash'] ?? '');

        if (isset($data['password']) || isset($data['password_plaintext']) || isset($data['plainPassword'])) {
            throw new \InvalidArgumentException('Plaintext password fields are not allowed in users.json.');
        }

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('User email is missing or invalid.');
        }

        if ($hash === '' || ! str_starts_with($hash, '$')) {
            throw new \InvalidArgumentException("User {$email} must have a passwordHash (never plaintext).");
        }

        $id = (string) ($data['id'] ?? '');
        if ($id === '') {
            throw new \InvalidArgumentException("User {$email} is missing id.");
        }

        $teamIds = [];
        if (isset($data['teamIds']) && is_array($data['teamIds'])) {
            $teamIds = array_values(array_map('strval', $data['teamIds']));
        }

        $displayName = trim((string) ($data['displayName'] ?? $email));

        return new self(
            id: $id,
            email: $email,
            displayName: $displayName,
            passwordHash: $hash,
            teamIds: $teamIds,
            canManageUsers: (bool) ($data['canManageUsers'] ?? false),
            canManageTeams: (bool) ($data['canManageTeams'] ?? false),
            active: (bool) ($data['active'] ?? true),
            shortName: strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', (string) ($data['shortName'] ?? '')) ?: '', 0, 3)),
            colorToken: AccentColors::normalize($data['colorToken'] ?? null),
            avatarIcon: AvatarIcons::normalize($data['avatarIcon'] ?? null),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'displayName' => $this->displayName,
            'passwordHash' => $this->passwordHash,
            'teamIds' => $this->teamIds,
            'canManageUsers' => $this->canManageUsers,
            'canManageTeams' => $this->canManageTeams,
            'active' => $this->active,
            'shortName' => $this->shortName,
            'colorToken' => $this->colorToken,
            'avatarIcon' => $this->avatarIcon,
        ];
    }

    /**
     * Safe payload for UI/API (no hash).
     *
     * @return array<string, mixed>
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'displayName' => $this->displayName,
            'teamIds' => $this->teamIds,
            'canManageUsers' => $this->canManageUsers,
            'canManageTeams' => $this->canManageTeams,
            'active' => $this->active,
            'shortName' => $this->shortName,
            'colorToken' => $this->colorToken,
            'avatarIcon' => $this->avatarIcon,
        ];
    }
}
