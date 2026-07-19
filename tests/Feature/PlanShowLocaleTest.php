<?php

namespace Tests\Feature;

use App\Accounts\PlanStore;
use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PlanShowLocaleTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-locale-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);

        app(UserRepository::class)->upsert([
            'id' => 'user_locale',
            'email' => 'locale@example.com',
            'displayName' => 'Locale',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
            'teamIds' => [],
        ]);

        app(PlanStore::class)->save([
            'id' => 'plan_20260817_acid1',
            'templateSlug' => 'demo',
            'completedTasks' => [],
            'viewerTeamIds' => [],
        ], app(UserRepository::class)->findById('user_locale'));
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_api_urls_are_locale_free_and_history_works(): void
    {
        $this->post('/de/login', [
            'email' => 'locale@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        // locale_route must not prefix /de for plan APIs
        $this->assertStringNotContainsString('/de/api/', locale_route('accounts.plans.index', [], 'de'));
        $this->assertStringContainsString('/api/sprint-planner/plans', locale_route('accounts.plans.index', [], 'de'));

        $this->getJson('/api/sprint-planner/plans/plan_20260817_acid1')
            ->assertOk()
            ->assertJsonPath('plan.id', 'plan_20260817_acid1');

        $this->getJson('/api/sprint-planner/plans/plan_20260817_acid1/history')
            ->assertOk()
            ->assertJsonStructure(['revisions']);

        // Old localized API paths should 404 (no longer registered)
        $this->getJson('/de/api/sprint-planner/plans/plan_20260817_acid1/history')
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
