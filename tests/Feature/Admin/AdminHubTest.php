<?php

namespace Tests\Feature\Admin;

use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AdminHubTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-admin-hub-'.bin2hex(random_bytes(4));
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

        app(UserRepository::class)->upsert([
            'id' => 'user_plain',
            'email' => 'plain@example.com',
            'displayName' => 'Plain',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
            'shortName' => 'PLN',
            'colorToken' => 'accent-1',
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_guest_cannot_open_admin_hub(): void
    {
        $this->get('/admin')->assertRedirect();
    }

    public function test_manager_sees_admin_hub_and_sidebar(): void
    {
        $this->login('admin@example.com');
        $this->get('/admin')
            ->assertOk()
            ->assertSee('Admin Hub', false)
            ->assertSee('My Workspaces', false)
            ->assertSee('Administration', false)
            ->assertSee('admin-sidenav', false);
    }

    public function test_workspace_can_be_created(): void
    {
        $this->login('admin@example.com');
        $this->post('/admin/workspaces', [
            'name' => 'Client Fabric',
            'stack' => 'fabric',
            'label' => 'Acme',
            'notes' => 'Test',
        ])->assertRedirect();

        $this->get('/admin/workspaces')
            ->assertOk()
            ->assertSee('Client Fabric', false);
    }

    public function test_active_workspace_stack_can_sync_and_save_named_stack(): void
    {
        $this->login('admin@example.com');
        $this->post('/admin/workspaces', [
            'name' => 'Lakehouse ACME',
            'stack' => 'unknown',
            'label' => '',
            'notes' => '',
        ])->assertRedirect();

        $list = app(\App\Admin\Contracts\WorkspaceStoreInterface::class)
            ->listFor(app(\App\Accounts\AccountAuth::class)->user());
        $this->assertNotEmpty($list);
        $workspaceId = $list[0]['id'];
        $this->post("/admin/workspaces/{$workspaceId}/activate")->assertRedirect();

        $this->putJson('/admin/api/workspace/active/stack', [
            'stack' => 'custom',
            'customStack' => [
                'ingest' => ['fivetran'],
                'storage' => ['snowflake'],
                'transform' => ['dbt'],
                'bi' => ['powerbi'],
                'catalog' => [],
                'orchestration' => [],
            ],
        ])->assertOk()
            ->assertJsonPath('workspace.stack', 'custom');

        $this->postJson('/admin/api/workspace/active/saved-stacks', [
            'name' => 'Finance Stack',
            'selection' => [
                'ingest' => ['fivetran'],
                'storage' => ['snowflake'],
                'transform' => ['dbt'],
                'bi' => ['powerbi'],
                'catalog' => [],
                'orchestration' => [],
            ],
        ])->assertOk()
            ->assertJsonPath('savedStack.name', 'Finance Stack');

        $this->getJson('/admin/api/workspace/active')
            ->assertOk()
            ->assertJsonPath('workspace.stack', 'custom')
            ->assertJsonPath('workspace.savedStacks.0.name', 'Finance Stack');
    }

    public function test_legacy_users_url_redirects_to_admin(): void
    {
        $this->login('admin@example.com');
        $this->get('/account/users')->assertRedirect('/admin/users');
    }

    public function test_stories_index_requires_manage_users(): void
    {
        $this->login('plain@example.com');
        $this->get('/admin/stories')->assertForbidden();
    }

    public function test_manager_can_open_content_pages(): void
    {
        $this->login('admin@example.com');
        $this->get('/admin/stories')->assertOk()->assertSee('Stories', false);
        $this->get('/admin/plan-templates')->assertOk();
        $this->get('/admin/radar')->assertOk();
        $this->get('/admin/vendors')->assertOk();
        $this->get('/admin/glossary')->assertOk();
        $this->get('/admin/reads')->assertOk();
        $this->get('/admin/quiz')->assertOk();
    }

    private function login(string $email): void
    {
        $this->post('/login', [
            'email' => $email,
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
