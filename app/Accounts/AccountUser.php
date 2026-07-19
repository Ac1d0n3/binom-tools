<?php

namespace App\Accounts;

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

        return new self(
            id: $id,
            email: $email,
            displayName: trim((string) ($data['displayName'] ?? $email)),
            passwordHash: $hash,
            teamIds: $teamIds,
            canManageUsers: (bool) ($data['canManageUsers'] ?? false),
            canManageTeams: (bool) ($data['canManageTeams'] ?? false),
            active: (bool) ($data['active'] ?? true),
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
        ];
    }
}
