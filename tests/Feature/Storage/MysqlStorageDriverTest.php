<?php

namespace Tests\Feature\Storage;

use App\Accounts\Contracts\PlanStoreInterface;
use App\Accounts\Contracts\UserRepositoryInterface;
use App\Playbooks\Contracts\PlaybookStatsStoreInterface;
use App\Support\StorageDriver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class MysqlStorageDriverTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        putenv('BINOM_TOOLS_STORAGE_DRIVER=mysql');
        $_ENV['BINOM_TOOLS_STORAGE_DRIVER'] = 'mysql';
        $_SERVER['BINOM_TOOLS_STORAGE_DRIVER'] = 'mysql';

        parent::setUp();

        config([
            'storage.driver' => 'mysql',
            'accounts.enabled' => true,
            'accounts.registration_enabled' => true,
        ]);

        // Re-bind stores for mysql after config change (provider already ran with env).
        $this->app->singleton(UserRepositoryInterface::class, \App\Accounts\Database\DatabaseUserRepository::class);
        $this->app->singleton(\App\Accounts\Contracts\TeamRepositoryInterface::class, \App\Accounts\Database\DatabaseTeamRepository::class);
        $this->app->singleton(PlanStoreInterface::class, \App\Accounts\Database\DatabasePlanStore::class);
        $this->app->singleton(PlaybookStatsStoreInterface::class, \App\Playbooks\Database\DatabasePlaybookStatsStore::class);
    }

    protected function tearDown(): void
    {
        putenv('BINOM_TOOLS_STORAGE_DRIVER=file');
        $_ENV['BINOM_TOOLS_STORAGE_DRIVER'] = 'file';
        $_SERVER['BINOM_TOOLS_STORAGE_DRIVER'] = 'file';
        parent::tearDown();
    }

    public function test_storage_driver_is_mysql(): void
    {
        $this->assertSame(StorageDriver::MYSQL, StorageDriver::current());
    }

    public function test_user_stats_and_plan_persist_in_database(): void
    {
        $users = app(UserRepositoryInterface::class);
        $user = $users->upsert([
            'id' => 'user_db',
            'email' => 'db@example.com',
            'displayName' => 'DB User',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'active' => true,
            'pendingApproval' => false,
        ]);

        $found = $users->findByEmail('db@example.com');
        $this->assertNotNull($found);
        $this->assertSame('user_db', $found->id);

        $stats = app(PlaybookStatsStoreInterface::class);
        $stats->set('eight-pillars', 3, 2);
        $this->assertSame(['views' => 3, 'likes' => 2], $stats->get('eight-pillars'));
        $after = $stats->incrementView('eight-pillars');
        $this->assertSame(4, $after['views']);

        $plans = app(PlanStoreInterface::class);
        $saved = $plans->save([
            'id' => 'plan_test1',
            'templateSlug' => 'planning-month',
            'templateSnapshot' => ['slug' => 'planning-month', 'sprints' => []],
            'startedAt' => now()->toIso8601String(),
            'status' => 'active',
            'participantIds' => [$user->id],
            'completedTasks' => [],
        ], $user);

        $this->assertSame('plan_test1', $saved['id']);
        $loaded = $plans->find('plan_test1');
        $this->assertNotNull($loaded);
        $this->assertSame('planning-month', $loaded['templateSlug'] ?? null);
        $this->assertIsArray($loaded['templateSnapshot'] ?? null);
    }
}
