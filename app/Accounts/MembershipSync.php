<?php

namespace App\Accounts;

/**
 * Keep teams.memberIds and users.teamIds bidirectional.
 */
final class MembershipSync
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly TeamRepository $teams,
    ) {}

    /**
     * Team is source of truth: ensure each member has teamId and non-members do not.
     */
    public function syncUsersFromTeam(AccountTeam $team): void
    {
        $memberSet = array_fill_keys($team->memberIds, true);

        foreach ($this->users->all() as $user) {
            $has = in_array($team->id, $user->teamIds, true);
            $should = isset($memberSet[$user->id]);
            if ($has === $should) {
                continue;
            }

            $next = $user->teamIds;
            if ($should) {
                $next[] = $team->id;
            } else {
                $next = array_values(array_filter(
                    $next,
                    static fn (string $id): bool => $id !== $team->id,
                ));
            }

            $this->users->upsert([
                ...$user->toArray(),
                'teamIds' => array_values(array_unique($next)),
            ]);
        }
    }

    /**
     * User is source of truth: ensure each team lists the user (and remove from others).
     */
    public function syncTeamsFromUser(AccountUser $user): void
    {
        $teamSet = array_fill_keys($user->teamIds, true);

        foreach ($this->teams->all(true) as $team) {
            $has = in_array($user->id, $team->memberIds, true);
            $should = isset($teamSet[$team->id]);
            if ($has === $should) {
                continue;
            }

            $next = $team->memberIds;
            if ($should) {
                $next[] = $user->id;
            } else {
                $next = array_values(array_filter(
                    $next,
                    static fn (string $id): bool => $id !== $user->id,
                ));
            }

            $this->teams->upsert([
                ...$team->toArray(),
                'memberIds' => array_values(array_unique($next)),
            ]);
        }
    }

    public function removeTeamFromUsers(string $teamId): void
    {
        foreach ($this->users->all() as $user) {
            if (! in_array($teamId, $user->teamIds, true)) {
                continue;
            }
            $this->users->upsert([
                ...$user->toArray(),
                'teamIds' => array_values(array_filter(
                    $user->teamIds,
                    static fn (string $id): bool => $id !== $teamId,
                )),
            ]);
        }
    }

    public function removeUserFromTeams(string $userId): void
    {
        foreach ($this->teams->all(true) as $team) {
            if (! in_array($userId, $team->memberIds, true)) {
                continue;
            }
            $this->teams->upsert([
                ...$team->toArray(),
                'memberIds' => array_values(array_filter(
                    $team->memberIds,
                    static fn (string $id): bool => $id !== $userId,
                )),
            ]);
        }
    }
}
