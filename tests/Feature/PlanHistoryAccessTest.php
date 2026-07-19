<?php

namespace Tests\Feature;

use App\Accounts\PlanStore;
use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PlanHistoryAccessTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-hist-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);

        app(UserRepository::class)->upsert([
            'id' => 'user_owner',
            'email' => 'owner@example.com',
            'displayName' => 'Owner',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
            'teamIds' => ['team_q'],
        ]);

        app(UserRepository::class)->upsert([
            'id' => 'user_outsider',
            'email' => 'outsider@example.com',
            'displayName' => 'Outsider',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
            'teamIds' => [],
        ]);

        app(PlanStore::class)->save([
            'id' => 'plan_20260719_histapi',
            'templateSlug' => 'demo',
            'completedTasks' => [],
            'viewerTeamIds' => ['team_q'],
        ], app(UserRepository::class)->findById('user_owner'));
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_owner_can_list_history(): void
    {
        $this->post('/login', [
            'email' => 'owner@example.com',
            'password' => 'password123',
        ])->assertRedirect('/');

        $this->getJson('/api/sprint-planner/plans/plan_20260719_histapi/history')
            ->assertOk()
            ->assertJsonStructure(['revisions']);
    }

    public function test_outsider_gets_forbidden(): void
    {
        $this->post('/login', [
            'email' => 'outsider@example.com',
            'password' => 'password123',
        ])->assertRedirect('/');

        $this->getJson('/api/sprint-planner/plans/plan_20260719_histapi/history')
            ->assertForbidden();
    }

    public function test_missing_plan_returns_not_found_not_forbidden(): void
    {
        $this->post('/login', [
            'email' => 'owner@example.com',
            'password' => 'password123',
        ])->assertRedirect('/');

        $this->getJson('/api/sprint-planner/plans/plan_20260719_missing/history')
            ->assertNotFound();
    }

    private function removeDir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        $items = scandir($dir) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $path = $dir.DIRECTORY_SEPARATOR.$item;
            if (is_dir($path)) {
                $this->removeDir($path);
            } else {
                @unlink($path);
            }
        }
        @rmdir($dir);
    }
}
