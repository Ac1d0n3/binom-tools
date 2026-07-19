<?php

namespace Tests\Feature\Accounts;

use App\Accounts\AccountAuth;
use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AccountsAuthTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-feat-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);

        app(UserRepository::class)->upsert([
            'id' => 'user_admin',
            'email' => 'admin@example.com',
            'displayName' => 'Admin',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_login_success_and_failure(): void
    {
        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ])->assertRedirect('/');

        $this->assertTrue(app(AccountAuth::class)->check());
        $this->assertSame('user_admin', app(AccountAuth::class)->id());
    }

    public function test_admin_users_gate(): void
    {
        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->get('/account/users')->assertOk();
        $this->get('/sprint-planner/people')->assertOk();

        app(UserRepository::class)->upsert([
            'id' => 'user_plain',
            'email' => 'plain@example.com',
            'displayName' => 'Plain',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
        ]);

        $this->post('/logout');
        $this->post('/login', [
            'email' => 'plain@example.com',
            'password' => 'password123',
        ]);

        $this->get('/account/users')->assertForbidden();
        $this->get('/account/teams')->assertForbidden();
        $this->get('/sprint-planner/people')->assertForbidden();
        $this->get('/sprint-planner')
            ->assertOk()
            ->assertDontSee(locale_route('sprint-planner.people'), false);
    }

    public function test_guest_is_redirected_from_people_page_when_accounts_on(): void
    {
        $this->get('/sprint-planner/people')->assertRedirect();
        $location = (string) $this->get('/sprint-planner/people')->headers->get('Location');
        $this->assertStringContainsString('/login', parse_url($location, PHP_URL_PATH) ?: '');
    }

    public function test_login_is_404_when_accounts_disabled(): void
    {
        Config::set('accounts.enabled', false);
        $this->get('/login')->assertNotFound();
    }

    public function test_profile_can_update_avatar_when_enabled(): void
    {
        Config::set('accounts.profile_avatar_enabled', true);

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->get('/account')
            ->assertOk()
            ->assertSee('accounts.avatarIcon', false);

        $this->put('/account', [
            'displayName' => 'Admin',
            'shortName' => 'ADM',
            'colorToken' => 'outline-3',
            'avatarIcon' => 'user-astronaut',
        ])->assertRedirect();

        $user = app(UserRepository::class)->findById('user_admin');
        $this->assertNotNull($user);
        $this->assertSame('ADM', $user->shortName);
        $this->assertSame('outline-3', $user->colorToken);
        $this->assertSame('user-astronaut', $user->avatarIcon);
    }

    public function test_profile_rejects_avatar_fields_when_disabled(): void
    {
        Config::set('accounts.profile_avatar_enabled', false);

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ]);

        $this->get('/account')
            ->assertOk()
            ->assertDontSee('accounts.avatarIcon', false);

        $this->put('/account', [
            'displayName' => 'Admin Renamed',
            'shortName' => 'XXX',
            'colorToken' => 'accent-9',
            'avatarIcon' => 'rocket',
        ])->assertRedirect();

        $user = app(UserRepository::class)->findById('user_admin');
        $this->assertNotNull($user);
        $this->assertSame('Admin Renamed', $user->displayName);
        $this->assertSame('', $user->shortName);
        $this->assertSame('accent-1', $user->colorToken);
        $this->assertSame('', $user->avatarIcon);
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
