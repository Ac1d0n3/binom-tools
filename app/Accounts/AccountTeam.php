<?php

namespace App\Accounts;

use App\Support\AccentColors;
use App\Support\ShortName;

final class AccountTeam
{
    public const ROLE_MEMBER = 'member';

    public const ROLE_MANAGER = 'manager';

    public const ROLE_CEO = 'ceo';

    /**
     * @var list<string>
     */
    public const ROLES = [
        self::ROLE_MEMBER,
        self::ROLE_MANAGER,
        self::ROLE_CEO,
    ];

    /**
     * @param  array{de: string, en: string}  $name
     * @param  array{de: string, en: string}  $description
     * @param  list<string>  $memberIds
     * @param  array<string, string>  $memberRoles  userId => role
     */
    public function __construct(
        public readonly string $id,
        public readonly array $name,
        public readonly array $description,
        public readonly array $memberIds,
        public readonly bool $archived = false,
        public readonly string $shortName = '',
        public readonly string $colorToken = 'accent-1',
        public readonly array $memberRoles = [],
    ) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        $id = (string) ($data['id'] ?? '');
        if ($id === '') {
            throw new \InvalidArgumentException('Team is missing id.');
        }

        $name = $data['name'] ?? [];
        if (is_string($name)) {
            $name = ['de' => $name, 'en' => $name];
        }
        if (! is_array($name)) {
            $name = ['de' => $id, 'en' => $id];
        }

        $description = $data['description'] ?? ['de' => '', 'en' => ''];
        if (is_string($description)) {
            $description = ['de' => $description, 'en' => $description];
        }
        if (! is_array($description)) {
            $description = ['de' => '', 'en' => ''];
        }

        $memberIds = [];
        if (isset($data['memberIds']) && is_array($data['memberIds'])) {
            $memberIds = array_values(array_map('strval', $data['memberIds']));
        }

        $rawRoles = is_array($data['memberRoles'] ?? null) ? $data['memberRoles'] : [];

        return new self(
            id: $id,
            name: [
                'de' => (string) ($name['de'] ?? $name['en'] ?? $id),
                'en' => (string) ($name['en'] ?? $name['de'] ?? $id),
            ],
            description: [
                'de' => (string) ($description['de'] ?? ''),
                'en' => (string) ($description['en'] ?? ''),
            ],
            memberIds: $memberIds,
            archived: (bool) ($data['archived'] ?? false),
            shortName: ShortName::normalize($data['shortName'] ?? ''),
            colorToken: AccentColors::normalize($data['colorToken'] ?? null),
            memberRoles: self::normalizeMemberRoles($rawRoles, $memberIds),
        );
    }

    public static function normalizeRole(mixed $role): string
    {
        $value = strtolower(trim((string) $role));

        return in_array($value, self::ROLES, true) ? $value : self::ROLE_MEMBER;
    }

    /**
     * @param  array<mixed, mixed>  $roles
     * @param  list<string>  $memberIds
     * @return array<string, string>
     */
    public static function normalizeMemberRoles(array $roles, array $memberIds): array
    {
        $allowed = array_fill_keys($memberIds, true);
        $out = [];
        foreach ($roles as $userId => $role) {
            $id = (string) $userId;
            if ($id === '' || ! isset($allowed[$id])) {
                continue;
            }
            $normalized = self::normalizeRole($role);
            if ($normalized === self::ROLE_MEMBER) {
                continue;
            }
            $out[$id] = $normalized;
        }

        return $out;
    }

    public function roleFor(string $userId): string
    {
        return $this->memberRoles[$userId] ?? self::ROLE_MEMBER;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'memberIds' => $this->memberIds,
            'memberRoles' => $this->memberRoles,
            'archived' => $this->archived,
            'shortName' => $this->shortName,
            'colorToken' => $this->colorToken,
        ];
    }
}
