<?php

namespace Tests\Unit\Accounts;

use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Accounts\MembershipSync;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MembershipSyncTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-sync-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_sync_users_from_team_updates_user_team_ids(): void
    {
        [$users, $teams, $sync] = $this->services();
        $hash = password_hash('password123', PASSWORD_DEFAULT);

        $users->upsert([
            'id' => 'user_a',
            'email' => 'a@example.com',
            'displayName' => 'A',
            'passwordHash' => $hash,
            'teamIds' => [],
        ]);
        $users->upsert([
            'id' => 'user_b',
            'email' => 'b@example.com',
            'displayName' => 'B',
            'passwordHash' => $hash,
            'teamIds' => ['team_q'],
        ]);

        $team = $teams->upsert([
            'id' => 'team_q',
            'name' => ['de' => 'Q', 'en' => 'Q'],
            'memberIds' => ['user_a'],
        ]);

        $sync->syncUsersFromTeam($team);

        $this->assertSame(['team_q'], $users->findById('user_a')?->teamIds);
        $this->assertSame([], $users->findById('user_b')?->teamIds);
    }

    public function test_sync_teams_from_user_updates_member_ids(): void
    {
        [$users, $teams, $sync] = $this->services();
        $hash = password_hash('password123', PASSWORD_DEFAULT);

        $teams->upsert([
            'id' => 'team_q',
            'name' => ['de' => 'Q', 'en' => 'Q'],
            'memberIds' => [],
        ]);
        $teams->upsert([
            'id' => 'team_x',
            'name' => ['de' => 'X', 'en' => 'X'],
            'memberIds' => ['user_a'],
        ]);

        $user = $users->upsert([
            'id' => 'user_a',
            'email' => 'a@example.com',
            'displayName' => 'A',
            'passwordHash' => $hash,
            'teamIds' => ['team_q'],
        ]);

        $sync->syncTeamsFromUser($user);

        $this->assertSame(['user_a'], $teams->findById('team_q')?->memberIds);
        $this->assertSame([], $teams->findById('team_x')?->memberIds);
    }

    public function test_remove_user_from_teams_clears_member_roles(): void
    {
        [$users, $teams, $sync] = $this->services();
        $hash = password_hash('password123', PASSWORD_DEFAULT);

        $users->upsert([
            'id' => 'user_a',
            'email' => 'a@example.com',
            'displayName' => 'A',
            'passwordHash' => $hash,
            'teamIds' => ['team_q'],
        ]);
        $teams->upsert([
            'id' => 'team_q',
            'name' => ['de' => 'Q', 'en' => 'Q'],
            'memberIds' => ['user_a'],
            'memberRoles' => ['user_a' => 'ceo'],
        ]);

        $sync->removeUserFromTeams('user_a');

        $team = $teams->findById('team_q');
        $this->assertSame([], $team?->memberIds);
        $this->assertSame([], $team?->memberRoles);
    }

    /**
     * @return array{0: UserRepository, 1: TeamRepository, 2: MembershipSync}
     */
    private function services(): array
    {
        $users = new UserRepository(new AccountsConfig, new JsonFileStore);
        $teams = new TeamRepository(new AccountsConfig, new JsonFileStore);

        return [$users, $teams, new MembershipSync($users, $teams)];
    }

    private function removeDir(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }
        foreach (scandir($path) ?: [] as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $full = $path.'/'.$item;
            is_dir($full) ? $this->removeDir($full) : unlink($full);
        }
        rmdir($path);
    }
}
