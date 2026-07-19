<?php

namespace Tests\Feature\Accounts;

use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AccountsAdminPagesTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-admin-'.bin2hex(random_bytes(4));
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
            'shortName' => 'ADM',
            'colorToken' => 'accent-2',
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_team_create_edit_pages_and_membership_sync(): void
    {
        $this->loginAdmin();

        $this->get('/account/teams')->assertOk()->assertSee('accounts.addTeam', false);
        $this->get('/account/teams/create')->assertOk();

        $this->post('/account/teams', [
            'name_de' => 'Analytics',
            'name_en' => 'Analytics',
            'description_de' => 'Team DE',
            'description_en' => 'Team EN',
            'memberIds' => ['user_admin'],
            'shortName' => 'ANA',
            'colorToken' => 'accent-3',
            'avatarIcon' => 'yin-yang',
        ])->assertRedirect('/account/teams');

        $team = collect(app(TeamRepository::class)->all(true))->first();
        $this->assertNotNull($team);
        $this->assertSame(['user_admin'], $team->memberIds);
        $this->assertSame('ANA', $team->shortName);
        $this->assertSame('yin-yang', $team->avatarIcon);
        $this->assertContains($team->id, app(UserRepository::class)->findById('user_admin')?->teamIds ?? []);

        $this->get('/account/teams/'.$team->id.'/edit')->assertOk()->assertSee('Analytics');
    }

    public function test_team_icon_only_without_trigram(): void
    {
        $this->loginAdmin();

        $this->post('/account/teams', [
            'name_de' => 'Icon Team',
            'name_en' => 'Icon Team',
            'memberIds' => [],
            'shortName' => '',
            'colorToken' => 'accent-2',
            'avatarIcon' => 'rocket',
        ])->assertRedirect('/account/teams');

        $team = collect(app(TeamRepository::class)->all(true))->first(
            static fn ($t) => ($t->name['en'] ?? '') === 'Icon Team',
        );
        $this->assertNotNull($team);
        $this->assertSame('', $team->shortName);
        $this->assertSame('rocket', $team->avatarIcon);
    }

    public function test_team_member_roles_persist(): void
    {
        $this->loginAdmin();

        $this->post('/account/teams', [
            'name_de' => 'Team Q',
            'name_en' => 'Team Q',
            'memberIds' => ['user_admin'],
            'memberRoles' => ['user_admin' => 'manager'],
            'shortName' => 'TQ',
            'colorToken' => 'accent-1',
        ])->assertRedirect('/account/teams');

        $team = collect(app(TeamRepository::class)->all(true))->first(
            static fn ($t) => ($t->name['en'] ?? '') === 'Team Q',
        );
        $this->assertNotNull($team);
        $this->assertSame(['user_admin' => 'manager'], $team->memberRoles);
        $this->assertSame('manager', $team->roleFor('user_admin'));
        $this->assertSame('member', $team->roleFor('user_other'));
    }

    public function test_short_name_rejects_invalid_lengths_and_digits(): void
    {
        $this->loginAdmin();

        $this->post('/account/users', [
            'email' => 'bad1@example.com',
            'displayName' => 'Bad One',
            'password' => 'password123',
            'shortName' => 'A',
            'active' => '1',
        ])->assertSessionHasErrors('shortName');

        $this->post('/account/users', [
            'email' => 'bad2@example.com',
            'displayName' => 'Bad Two',
            'password' => 'password123',
            'shortName' => 'ABCD',
            'active' => '1',
        ])->assertSessionHasErrors('shortName');

        $this->post('/account/users', [
            'email' => 'bad3@example.com',
            'displayName' => 'Bad Three',
            'password' => 'password123',
            'shortName' => 'A1',
            'active' => '1',
        ])->assertSessionHasErrors('shortName');

        $this->post('/account/users', [
            'email' => 'ok@example.com',
            'displayName' => 'Ok User',
            'password' => 'password123',
            'shortName' => 'OK',
            'active' => '1',
        ])->assertRedirect('/account/users');

        $ok = app(UserRepository::class)->findByEmail('ok@example.com');
        $this->assertSame('OK', $ok?->shortName);
    }

    public function test_user_create_edit_pages(): void
    {
        $this->loginAdmin();

        $this->get('/account/users')->assertOk()->assertSee('accounts.addUser', false);
        $this->get('/account/users/create')->assertOk();

        $this->post('/account/users', [
            'email' => 'new@example.com',
            'displayName' => 'New User',
            'password' => 'password123',
            'teamIds' => [],
            'active' => '1',
            'shortName' => 'NEW',
            'colorToken' => 'outline-2',
            'avatarIcon' => 'user-astronaut',
        ])->assertRedirect('/account/users');

        $created = app(UserRepository::class)->findByEmail('new@example.com');
        $this->assertNotNull($created);
        $this->assertSame('outline-2', $created->colorToken);
        $this->assertSame('user-astronaut', $created->avatarIcon);
        $this->get('/account/users/'.$created->id.'/edit')->assertOk()->assertSee('new@example.com');
    }

    public function test_story_acl_list_and_edit_flow(): void
    {
        $this->loginAdmin();

        $index = $this->get('/account/story-access');
        $index->assertOk()->assertSee('accounts.storyAclTitle', false);

        $slug = 'ai-basics';
        $this->get('/account/story-access/'.$slug.'/edit')
            ->assertOk()
            ->assertSee('accounts.editStoryAcl', false);

        $this->put('/account/story-access/'.$slug, [
            'visibility' => 'restricted',
            'userIds' => ['user_admin'],
            'teamIds' => [],
        ])->assertRedirect('/account/story-access');

        $acl = app(\App\Accounts\StoryAclRepository::class)->forSlug($slug);
        $this->assertSame('restricted', $acl['visibility']);
        $this->assertSame(['user_admin'], $acl['userIds']);

        $this->get('/account/story-access')
            ->assertOk()
            ->assertSee('accounts.visibility.restricted', false);
    }

    public function test_self_delete_is_rejected(): void
    {
        $this->loginAdmin();
        $this->delete('/account/users/user_admin')->assertStatus(422);
    }

    private function loginAdmin(): void
    {
        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ])->assertRedirect('/');
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
