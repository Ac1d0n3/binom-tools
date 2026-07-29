<?php

namespace Tests\Unit\Accounts;

use App\Accounts\DirectoryVisibility;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DirectoryVisibilityTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-dirvis-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_guest_receives_empty_directory(): void
    {
        $directory = app(DirectoryVisibility::class);

        $this->assertSame([], $directory->usersFor(null));
        $this->assertSame([], $directory->teamsFor(null));
    }

    public function test_member_sees_teammates_without_their_emails(): void
    {
        $users = app(UserRepository::class);
        $teams = app(TeamRepository::class);

        $users->upsert([
            'id' => 'user_admin',
            'email' => 'admin@example.com',
            'displayName' => 'Admin',
            'passwordHash' => password_hash('x', PASSWORD_DEFAULT),
            'teamIds' => ['team_q'],
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
        ]);
        $users->upsert([
            'id' => 'user_lena',
            'email' => 'lena.s@example.com',
            'displayName' => 'Lena S.',
            'passwordHash' => password_hash('x', PASSWORD_DEFAULT),
            'teamIds' => ['team_q'],
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
        ]);
        $users->upsert([
            'id' => 'user_outsider',
            'email' => 'out@example.com',
            'displayName' => 'Outsider',
            'passwordHash' => password_hash('x', PASSWORD_DEFAULT),
            'teamIds' => ['team_other'],
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
        ]);
        $teams->upsert([
            'id' => 'team_q',
            'name' => ['de' => 'Team Q', 'en' => 'Team Q'],
            'description' => ['de' => '', 'en' => ''],
            'memberIds' => ['user_admin', 'user_lena'],
            'archived' => false,
        ]);
        $teams->upsert([
            'id' => 'team_other',
            'name' => ['de' => 'Other', 'en' => 'Other'],
            'description' => ['de' => '', 'en' => ''],
            'memberIds' => ['user_outsider'],
            'archived' => false,
        ]);

        $lena = $users->findById('user_lena');
        $this->assertNotNull($lena);

        $directory = app(DirectoryVisibility::class);
        $visibleUsers = $directory->usersFor($lena);
        $ids = array_column($visibleUsers, 'id');
        $this->assertContains('user_lena', $ids);
        $this->assertContains('user_admin', $ids);
        $this->assertNotContains('user_outsider', $ids);

        $byId = [];
        foreach ($visibleUsers as $row) {
            $byId[$row['id']] = $row;
        }
        $this->assertSame('lena.s@example.com', $byId['user_lena']['email']);
        $this->assertSame('', $byId['user_admin']['email']);

        $teamIds = array_column($directory->teamsFor($lena), 'id');
        $this->assertSame(['team_q'], $teamIds);
    }

    public function test_admin_sees_full_directory(): void
    {
        $users = app(UserRepository::class);
        $teams = app(TeamRepository::class);

        $users->upsert([
            'id' => 'user_admin',
            'email' => 'admin@example.com',
            'displayName' => 'Admin',
            'passwordHash' => password_hash('x', PASSWORD_DEFAULT),
            'teamIds' => ['team_q'],
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
        ]);
        $users->upsert([
            'id' => 'user_outsider',
            'email' => 'out@example.com',
            'displayName' => 'Outsider',
            'passwordHash' => password_hash('x', PASSWORD_DEFAULT),
            'teamIds' => [],
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
        ]);
        $teams->upsert([
            'id' => 'team_q',
            'name' => ['de' => 'Team Q', 'en' => 'Team Q'],
            'description' => ['de' => '', 'en' => ''],
            'memberIds' => ['user_admin'],
            'archived' => false,
        ]);
        $teams->upsert([
            'id' => 'team_hidden',
            'name' => ['de' => 'Hidden', 'en' => 'Hidden'],
            'description' => ['de' => '', 'en' => ''],
            'memberIds' => [],
            'archived' => false,
        ]);

        $admin = $users->findById('user_admin');
        $directory = app(DirectoryVisibility::class);

        $this->assertCount(2, $directory->usersFor($admin));
        $this->assertCount(2, $directory->teamsFor($admin));
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
            $full = $path.DIRECTORY_SEPARATOR.$item;
            is_dir($full) ? $this->removeDir($full) : @unlink($full);
        }
        @rmdir($path);
    }
}
