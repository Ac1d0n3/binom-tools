<?php

namespace App\Accounts;

use App\Support\AccentColors;
use App\Support\AvatarIcons;
use App\Support\ShortName;

final class AccountUser
{
    /**
     * @param  list<string>  $teamIds
     * @param  array<string, bool>  $contentAreas
     */
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $displayName,
        public readonly string $passwordHash,
        public readonly array $teamIds,
        public readonly bool $canManageUsers,
        public readonly bool $canManageTeams,
        public readonly bool $canManageContent,
        public readonly array $contentAreas,
        public readonly bool $active,
        public readonly string $shortName = '',
        public readonly string $colorToken = 'accent-1',
        public readonly string $avatarIcon = '',
        public readonly bool $mustChangePassword = false,
        public readonly bool $pendingApproval = false,
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
        $canManageUsers = (bool) ($data['canManageUsers'] ?? false);
        $legacyContentAdmin = ! array_key_exists('canManageContent', $data) && $canManageUsers;
        $canManageContent = array_key_exists('canManageContent', $data)
            ? (bool) $data['canManageContent']
            : $legacyContentAdmin;
        $areasRaw = is_array($data['contentAreas'] ?? null) ? $data['contentAreas'] : null;
        $legacyAreas = $areasRaw === null && ($canManageContent || $canManageUsers);
        $contentAreas = ContentAreas::normalize($areasRaw, $legacyAreas);

        return new self(
            id: $id,
            email: $email,
            displayName: $displayName,
            passwordHash: $hash,
            teamIds: $teamIds,
            canManageUsers: $canManageUsers,
            canManageTeams: (bool) ($data['canManageTeams'] ?? false),
            canManageContent: $canManageContent,
            contentAreas: $contentAreas,
            active: (bool) ($data['active'] ?? true),
            shortName: ShortName::normalize($data['shortName'] ?? ''),
            colorToken: AccentColors::normalize($data['colorToken'] ?? null),
            avatarIcon: AvatarIcons::normalize($data['avatarIcon'] ?? null),
            mustChangePassword: (bool) ($data['mustChangePassword'] ?? false),
            pendingApproval: (bool) ($data['pendingApproval'] ?? false),
        );
    }

    public function canAccessContentArea(string $area): bool
    {
        if ($this->canManageContent) {
            return true;
        }

        return (bool) ($this->contentAreas[$area] ?? false);
    }

    public function hasAnyContentAccess(): bool
    {
        if ($this->canManageContent) {
            return true;
        }

        return in_array(true, $this->contentAreas, true);
    }

    public function canAccessAdminHub(): bool
    {
        return $this->canManageUsers || $this->canManageTeams || $this->hasAnyContentAccess();
    }

    /**
     * Create always allowed when area is open; mutate needs content admin or matching owner.
     */
    public function canMutateOwnedContent(?string $ownerUserId): bool
    {
        if ($this->canManageContent) {
            return true;
        }

        return is_string($ownerUserId) && $ownerUserId !== '' && $ownerUserId === $this->id;
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
            'canManageContent' => $this->canManageContent,
            'contentAreas' => $this->contentAreas,
            'active' => $this->active,
            'shortName' => $this->shortName,
            'colorToken' => $this->colorToken,
            'avatarIcon' => $this->avatarIcon,
            'mustChangePassword' => $this->mustChangePassword,
            'pendingApproval' => $this->pendingApproval,
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
            'canManageContent' => $this->canManageContent,
            'contentAreas' => $this->contentAreas,
            'active' => $this->active,
            'shortName' => $this->shortName,
            'colorToken' => $this->colorToken,
            'avatarIcon' => $this->avatarIcon,
            'mustChangePassword' => $this->mustChangePassword,
            'pendingApproval' => $this->pendingApproval,
        ];
    }
}
