<?php

namespace App\Accounts;

/**
 * Limits which account users/teams are exposed to a signed-in actor.
 * Guests never receive a directory (callers pass null / skip).
 * Non-admins only see themselves and teammates of shared teams.
 */
final class DirectoryVisibility
{
    public function __construct(
        private readonly Contracts\UserRepositoryInterface $users,
        private readonly Contracts\TeamRepositoryInterface $teams,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function usersFor(?AccountUser $actor): array
    {
        if ($actor === null) {
            return [];
        }

        if ($actor->canManageUsers) {
            return array_values(array_map(
                static fn (AccountUser $u) => $u->toPublicArray(),
                $this->users->all(),
            ));
        }

        $allowedIds = $this->visibleUserIds($actor);
        $out = [];
        foreach ($this->users->all() as $user) {
            if (! isset($allowedIds[$user->id])) {
                continue;
            }
            $payload = $user->toPublicArray();
            // Hide colleague emails from non-admins (self keeps email for profile UX).
            if ($user->id !== $actor->id) {
                $payload['email'] = '';
            }
            $out[] = $payload;
        }

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function teamsFor(?AccountUser $actor): array
    {
        if ($actor === null) {
            return [];
        }

        if ($actor->canManageTeams || $actor->canManageUsers) {
            return array_values(array_map(
                static fn (AccountTeam $t) => $t->toArray(),
                $this->teams->all(),
            ));
        }

        $ownTeamIds = array_fill_keys($actor->teamIds, true);
        $out = [];
        foreach ($this->teams->all() as $team) {
            if (! isset($ownTeamIds[$team->id])) {
                continue;
            }
            $out[] = $team->toArray();
        }

        return $out;
    }

    /**
     * @return array<string, true>
     */
    private function visibleUserIds(AccountUser $actor): array
    {
        $ids = [$actor->id => true];
        foreach ($this->teams->all() as $team) {
            if (! in_array($actor->id, $team->memberIds, true)
                && ! in_array($team->id, $actor->teamIds, true)) {
                continue;
            }
            foreach ($team->memberIds as $memberId) {
                $ids[(string) $memberId] = true;
            }
        }

        return $ids;
    }
}
