<?php

namespace App\Providers;

use App\Accounts\Contracts\PlanAttachmentStoreInterface;
use App\Accounts\Contracts\PlanStoreInterface;
use App\Accounts\Contracts\PromptStudioLibraryStoreInterface;
use App\Accounts\Contracts\ReadStateStoreInterface;
use App\Accounts\Contracts\StoryAclRepositoryInterface;
use App\Accounts\Contracts\TeamRepositoryInterface;
use App\Accounts\Contracts\UserRepositoryInterface;
use App\Accounts\Contracts\UserTemplateStoreInterface;
use App\Accounts\Database\DatabasePlanAttachmentStore;
use App\Accounts\Database\DatabasePlanStore;
use App\Accounts\Database\DatabasePromptStudioLibraryStore;
use App\Accounts\Database\DatabaseReadStateStore;
use App\Accounts\Database\DatabaseStoryAclRepository;
use App\Accounts\Database\DatabaseTeamRepository;
use App\Accounts\Database\DatabaseUserRepository;
use App\Accounts\Database\DatabaseUserTemplateStore;
use App\Accounts\PlanAttachmentStore;
use App\Accounts\PlanStore;
use App\Accounts\PromptStudioLibraryStore;
use App\Accounts\ReadStateStore;
use App\Accounts\StoryAclRepository;
use App\Accounts\TeamRepository;
use App\Accounts\UserRepository;
use App\Accounts\UserTemplateStore;
use App\Playbooks\Contracts\PlaybookStatsStoreInterface;
use App\Playbooks\Database\DatabasePlaybookStatsStore;
use App\Playbooks\PlaybookStatsStore;
use App\Support\StorageDriver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Support/helpers.php');

        $this->app->singleton(\App\SprintPlanner\BnToolsSeedStore::class, static function (): \App\SprintPlanner\BnToolsSeedStore {
            return \App\SprintPlanner\BnToolsSeedStore::default();
        });

        $this->registerStorageBindings();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (StorageDriver::isMysql()) {
            try {
                StorageDriver::assertMysqlReady();
            } catch (\Throwable) {
                // Allow migrate / early console before DB is ready; stores will fail clearly later.
            }
        }

        try {
            app(\App\SprintPlanner\BnToolsSeedStore::class)->hydrateRuntimeFromSeeds();
        } catch (\Throwable) {
            // Ignore seed hydration failures (e.g. missing storage on first boot).
        }

        view()->composer('*', function ($view): void {
            $config = app(\App\Accounts\AccountsConfig::class);
            if (! $config->enabled()) {
                $view->with([
                    'accountsEnabled' => false,
                    'accountUser' => null,
                    'accountsReadSlugs' => [],
                    'accountsReadUrlTemplate' => null,
                    'registrationEnabled' => false,
                ]);

                return;
            }

            $auth = app(\App\Accounts\AccountAuth::class);
            $user = $auth->user();
            $readSlugs = [];
            if ($user !== null) {
                $readSlugs = array_keys(app(ReadStateStoreInterface::class)->forUser($user->id));
            }

            $view->with([
                'accountsEnabled' => true,
                'accountUser' => $user?->toPublicArray(),
                'accountsReadSlugs' => $readSlugs,
                'accountsReadUrlTemplate' => $user !== null
                    ? str_replace('__SLUG__', '__SLUG__', locale_route('accounts.playbooks.read', ['slug' => '__SLUG__']))
                    : null,
                'registrationEnabled' => $config->registrationEnabled(),
            ]);
        });
    }

    private function registerStorageBindings(): void
    {
        $driver = StorageDriver::current();

        if ($driver === StorageDriver::MYSQL) {
            $this->app->singleton(UserRepositoryInterface::class, DatabaseUserRepository::class);
            $this->app->singleton(TeamRepositoryInterface::class, DatabaseTeamRepository::class);
            $this->app->singleton(StoryAclRepositoryInterface::class, DatabaseStoryAclRepository::class);
            $this->app->singleton(PlanStoreInterface::class, DatabasePlanStore::class);
            $this->app->singleton(UserTemplateStoreInterface::class, DatabaseUserTemplateStore::class);
            $this->app->singleton(ReadStateStoreInterface::class, DatabaseReadStateStore::class);
            $this->app->singleton(PromptStudioLibraryStoreInterface::class, DatabasePromptStudioLibraryStore::class);
            $this->app->singleton(PlanAttachmentStoreInterface::class, DatabasePlanAttachmentStore::class);
            $this->app->singleton(PlaybookStatsStoreInterface::class, DatabasePlaybookStatsStore::class);

            return;
        }

        $this->app->singleton(UserRepositoryInterface::class, static function ($app): UserRepository {
            return new UserRepository($app->make(\App\Accounts\AccountsConfig::class), $app->make(\App\Accounts\JsonFileStore::class));
        });
        $this->app->singleton(TeamRepositoryInterface::class, static function ($app): TeamRepository {
            return new TeamRepository($app->make(\App\Accounts\AccountsConfig::class), $app->make(\App\Accounts\JsonFileStore::class));
        });
        $this->app->singleton(StoryAclRepositoryInterface::class, static function ($app): StoryAclRepository {
            return new StoryAclRepository($app->make(\App\Accounts\AccountsConfig::class), $app->make(\App\Accounts\JsonFileStore::class));
        });
        $this->app->singleton(PlanStoreInterface::class, static function ($app): PlanStore {
            return new PlanStore(
                $app->make(\App\Accounts\AccountsConfig::class),
                $app->make(\App\Accounts\JsonFileStore::class),
                $app->make(TeamRepositoryInterface::class),
            );
        });
        $this->app->singleton(UserTemplateStoreInterface::class, static function ($app): UserTemplateStore {
            return new UserTemplateStore($app->make(\App\Accounts\AccountsConfig::class), $app->make(\App\Accounts\JsonFileStore::class));
        });
        $this->app->singleton(ReadStateStoreInterface::class, static function ($app): ReadStateStore {
            return new ReadStateStore($app->make(\App\Accounts\AccountsConfig::class), $app->make(\App\Accounts\JsonFileStore::class));
        });
        $this->app->singleton(PromptStudioLibraryStoreInterface::class, static function ($app): PromptStudioLibraryStore {
            return new PromptStudioLibraryStore($app->make(\App\Accounts\AccountsConfig::class), $app->make(\App\Accounts\JsonFileStore::class));
        });
        $this->app->singleton(PlanAttachmentStoreInterface::class, static function ($app): PlanAttachmentStore {
            return new PlanAttachmentStore($app->make(\App\Accounts\AccountsConfig::class), $app->make(\App\Accounts\JsonFileStore::class));
        });
        $this->app->singleton(PlaybookStatsStoreInterface::class, static function (): PlaybookStatsStore {
            return PlaybookStatsStore::default();
        });

        $this->app->singleton(UserRepository::class, static fn ($app) => $app->make(UserRepositoryInterface::class));
        $this->app->singleton(TeamRepository::class, static fn ($app) => $app->make(TeamRepositoryInterface::class));
        $this->app->singleton(StoryAclRepository::class, static fn ($app) => $app->make(StoryAclRepositoryInterface::class));
        $this->app->singleton(PlanStore::class, static fn ($app) => $app->make(PlanStoreInterface::class));
        $this->app->singleton(UserTemplateStore::class, static fn ($app) => $app->make(UserTemplateStoreInterface::class));
        $this->app->singleton(ReadStateStore::class, static fn ($app) => $app->make(ReadStateStoreInterface::class));
        $this->app->singleton(PromptStudioLibraryStore::class, static fn ($app) => $app->make(PromptStudioLibraryStoreInterface::class));
        $this->app->singleton(PlaybookStatsStore::class, static fn ($app) => $app->make(PlaybookStatsStoreInterface::class));
    }
}
