<?php

namespace App\Accounts;

use InvalidArgumentException;

final class UserRepository
{
    public function __construct(
        private readonly AccountsConfig $config,
        private readonly JsonFileStore $store,
    ) {}

    /**
     * @return list<AccountUser>
     */
    public function all(): array
    {
        return array_values($this->indexed());
    }

    public function findById(string $id): ?AccountUser
    {
        return $this->indexed()[$id] ?? null;
    }

    public function findByEmail(string $email): ?AccountUser
    {
        $email = strtolower(trim($email));
        foreach ($this->all() as $user) {
            if ($user->email === $email) {
                return $user;
            }
        }

        return null;
    }

    /**
     * @param  array{
     *   id?: string,
     *   email: string,
     *   displayName?: string,
     *   passwordHash: string,
     *   teamIds?: list<string>,
     *   canManageUsers?: bool,
     *   canManageTeams?: bool,
     *   active?: bool
     * }  $input
     */
    public function upsert(array $input): AccountUser
    {
        if (isset($input['password']) || isset($input['password_plaintext']) || isset($input['plainPassword'])) {
            throw new InvalidArgumentException('Plaintext password fields must not be persisted.');
        }

        $users = $this->indexed();
        $email = strtolower(trim((string) ($input['email'] ?? '')));
        $id = (string) ($input['id'] ?? '');

        if ($id === '') {
            $id = 'user_'.preg_replace('/[^a-z0-9]+/', '_', pathinfo($email, PATHINFO_FILENAME) ?: 'account');
            $id = trim((string) $id, '_');
            if ($id === 'user' || $id === '') {
                $id = 'user_'.bin2hex(random_bytes(4));
            }
            while (isset($users[$id])) {
                $id = 'user_'.bin2hex(random_bytes(4));
            }
        }

        foreach ($users as $existing) {
            if ($existing->email === $email && $existing->id !== $id) {
                throw new InvalidArgumentException("Email already in use: {$email}");
            }
        }

        $current = $users[$id] ?? null;
        $hash = (string) ($input['passwordHash'] ?? $current?->passwordHash ?? '');
        if ($hash === '' || ! str_starts_with($hash, '$')) {
            throw new InvalidArgumentException('passwordHash is required and must be a password_hash() digest.');
        }

        $user = AccountUser::fromArray([
            'id' => $id,
            'email' => $email,
            'displayName' => $input['displayName'] ?? $current?->displayName ?? $email,
            'passwordHash' => $hash,
            'teamIds' => $input['teamIds'] ?? $current?->teamIds ?? [],
            'canManageUsers' => $input['canManageUsers'] ?? $current?->canManageUsers ?? false,
            'canManageTeams' => $input['canManageTeams'] ?? $current?->canManageTeams ?? false,
            'active' => $input['active'] ?? $current?->active ?? true,
        ]);

        $users[$id] = $user;
        $this->persist($users);

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
        $users = $this->indexed();
        if (! isset($users[$id])) {
            return;
        }
        unset($users[$id]);
        $this->persist($users);
    }

    /**
     * @return array<string, AccountUser>
     */
    private function indexed(): array
    {
        $raw = $this->store->read($this->config->usersPath(), [
            'schemaVersion' => 1,
            'users' => [],
        ]);

        if (isset($raw['password']) || isset($raw['passwords'])) {
            throw new InvalidArgumentException('users.json must not contain plaintext password fields.');
        }

        $list = $raw['users'] ?? [];
        if (! is_array($list)) {
            return [];
        }

        /** @type array<string, AccountUser> */
        $out = [];
        foreach ($list as $row) {
            if (! is_array($row)) {
                continue;
            }
            $user = AccountUser::fromArray($row);
            $out[$user->id] = $user;
        }

        return $out;
    }

    /**
     * @param  array<string, AccountUser>  $users
     */
    private function persist(array $users): void
    {
        $payload = [
            'schemaVersion' => 1,
            'users' => array_map(
                static fn (AccountUser $user): array => $user->toArray(),
                array_values($users),
            ),
        ];

        $encoded = json_encode($payload);
        if (is_string($encoded) && (
            str_contains($encoded, '"password":')
            || str_contains($encoded, '"plainPassword":')
            || str_contains($encoded, '"password_plaintext":')
        )) {
            throw new InvalidArgumentException('Refusing to write plaintext password fields.');
        }

        $this->store->write($this->config->usersPath(), $payload);
    }
}
