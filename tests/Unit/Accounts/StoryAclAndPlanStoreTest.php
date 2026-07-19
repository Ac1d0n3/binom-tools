<?php

namespace Tests\Unit\Accounts;

use App\Accounts\AccountUser;
use App\Accounts\AccountsConfig;
use App\Accounts\JsonFileStore;
use App\Accounts\PlanStore;
use App\Accounts\StoryAclRepository;
use App\Accounts\TeamRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class StoryAclAndPlanStoreTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-acl-'.bin2hex(random_bytes(4));
        mkdir($this->basePath.'/plans', 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_story_acl_defaults_to_public_and_filters_restricted(): void
    {
        $acl = new StoryAclRepository(new AccountsConfig, new JsonFileStore);
        $user = $this->user('user_a', ['team_q']);

        $this->assertTrue($acl->canAccess(null, 'any-story'));
        $this->assertTrue($acl->canAccess($user, 'any-story'));

        $acl->set('secret-guide', [
            'visibility' => 'restricted',
            'userIds' => ['user_a'],
            'teamIds' => [],
        ]);

        $this->assertFalse($acl->canAccess(null, 'secret-guide'));
        $this->assertTrue($acl->canAccess($user, 'secret-guide'));
        $this->assertFalse($acl->canAccess($this->user('user_b', []), 'secret-guide'));
    }

    public function test_plan_visibility_for_owner_viewer_and_team(): void
    {
        $store = new PlanStore(
            new AccountsConfig,
            new JsonFileStore,
            new TeamRepository(new AccountsConfig, new JsonFileStore),
        );
        $teams = new TeamRepository(new AccountsConfig, new JsonFileStore);
        $teams->upsert([
            'id' => 'team_q',
            'name' => ['de' => 'Q', 'en' => 'Q'],
            'memberIds' => ['user_c'],
        ]);

        $owner = $this->user('user_owner', []);
        $viewer = $this->user('user_viewer', []);
        $teamMember = $this->user('user_c', ['team_q']);
        $stranger = $this->user('user_x', []);

        $plan = $store->save([
            'id' => 'plan_20260719_abc',
            'templateSlug' => 'demo',
            'viewerUserIds' => ['user_viewer'],
            'viewerTeamIds' => ['team_q'],
            'linkedStorySlugs' => ['ai-basics'],
        ], $owner);

        $this->assertTrue($store->canAccess($owner, $plan));
        $this->assertTrue($store->canAccess($viewer, $plan));
        $this->assertTrue($store->canAccess($teamMember, $plan));
        $this->assertFalse($store->canAccess($stranger, $plan));
        $this->assertSame(['ai-basics'], $plan['linkedStorySlugs']);
    }

    public function test_acidone_style_saved_plan_is_visible_to_owner(): void
    {
        $store = new PlanStore(
            new AccountsConfig,
            new JsonFileStore,
            new TeamRepository(new AccountsConfig, new JsonFileStore),
        );
        $owner = $this->user('user_acidone', ['team_q']);

        $plan = $store->save([
            'id' => 'plan_20260817_acid1',
            'templateSlug' => 'data-reporting-first-quarter',
            'templateVersion' => 1,
            'startedAt' => '2026-08-17',
            'status' => 'active',
            'teamId' => 'team_q',
            'ephemeral' => false,
            'archived' => false,
        ], $owner);

        $this->assertTrue($store->canAccess($owner, $plan));
        $this->assertSame('2026-08-17', $plan['startedAt']);
        $this->assertSame('user_acidone', $plan['ownerUserId']);
        $visible = $store->listVisibleTo($owner);
        $this->assertCount(1, $visible);
        $this->assertSame('plan_20260817_acid1', $visible[0]['id']);
    }

    /**
     * @param  list<string>  $teamIds
     */
    private function user(string $id, array $teamIds): AccountUser
    {
        return new AccountUser(
            id: $id,
            email: $id.'@example.com',
            displayName: $id,
            passwordHash: password_hash('password123', PASSWORD_DEFAULT),
            teamIds: $teamIds,
            canManageUsers: false,
            canManageTeams: false,
            active: true,
        );
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
