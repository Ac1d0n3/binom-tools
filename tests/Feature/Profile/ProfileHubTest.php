<?php

namespace Tests\Feature\Profile;

use App\Accounts\AccountAuth;
use App\Accounts\UserRepository;
use App\Profile\Contracts\WorkspaceStoreInterface;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class ProfileHubTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-profile-hub-'.bin2hex(random_bytes(4));
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

    public function test_guest_cannot_open_profile_hub(): void
    {
        $this->get('/profile')->assertRedirect();
    }

    public function test_plain_user_sees_profile_hub(): void
    {
        $this->login('plain@example.com');
        $this->get('/profile')
            ->assertOk()
            ->assertSee('Profile Hub', false)
            ->assertSee('My Workspaces', false)
            ->assertSee('Back to app', false)
            ->assertDontSee('Administration', false);
    }

    public function test_workspace_can_be_created(): void
    {
        $this->login('admin@example.com');
        $this->post('/profile/workspaces', [
            'name' => 'Client Fabric',
            'stack' => 'fabric',
            'label' => 'Acme',
            'notes' => 'Test',
        ])->assertRedirect();

        $this->get('/profile/workspaces')
            ->assertOk()
            ->assertSee('Client Fabric', false);
    }

    public function test_active_workspace_stack_can_sync_and_save_named_stack(): void
    {
        $this->login('admin@example.com');
        $this->post('/profile/workspaces', [
            'name' => 'Lakehouse ACME',
            'stack' => 'unknown',
            'label' => '',
            'notes' => '',
        ])->assertRedirect();

        $list = app(WorkspaceStoreInterface::class)
            ->listFor(app(AccountAuth::class)->user());
        $this->assertNotEmpty($list);
        $workspaceId = $list[0]['id'];
        $this->post("/profile/workspaces/{$workspaceId}/activate")->assertRedirect();

        $this->putJson('/profile/api/workspace/active/stack', [
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

        $this->postJson('/profile/api/workspace/active/saved-stacks', [
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

        $this->getJson('/profile/api/workspace/active')
            ->assertOk()
            ->assertJsonPath('workspace.stack', 'custom')
            ->assertJsonPath('workspace.savedStacks.0.name', 'Finance Stack');
    }

    public function test_active_workspace_can_save_and_delete_tool_artifacts(): void
    {
        $this->login('admin@example.com');
        $this->post('/profile/workspaces', [
            'name' => 'DQ ACME',
            'stack' => 'snowflake-dbt',
            'label' => '',
            'notes' => '',
        ])->assertRedirect();

        $list = app(WorkspaceStoreInterface::class)
            ->listFor(app(AccountAuth::class)->user());
        $this->assertNotEmpty($list);
        $workspaceId = $list[0]['id'];
        $this->post("/profile/workspaces/{$workspaceId}/activate")->assertRedirect();

        $this->postJson('/profile/api/workspace/active/tool-artifacts', [
            'name' => 'Orders PII DE',
            'toolId' => 'dbt-dq-rules-generator',
            'kind' => 'dq-config',
            'region' => 'DE',
            'payload' => [
                'modelName' => 'orders',
                'columns' => [['name' => 'email', 'dqRules' => [['type' => 'regex']]]],
            ],
        ])->assertOk()
            ->assertJsonPath('toolArtifact.name', 'Orders PII DE')
            ->assertJsonPath('toolArtifact.toolId', 'dbt-dq-rules-generator')
            ->assertJsonPath('toolArtifact.region', 'DE');

        $active = $this->getJson('/profile/api/workspace/active')->assertOk();
        $active->assertJsonPath('workspace.toolArtifacts.0.name', 'Orders PII DE');
        $artifactId = $active->json('workspace.toolArtifacts.0.id');
        $this->assertNotEmpty($artifactId);

        $this->deleteJson("/profile/api/workspace/active/tool-artifacts/{$artifactId}")
            ->assertOk()
            ->assertJsonPath('toolArtifacts', []);
    }

    public function test_legacy_personal_urls_redirect_to_profile(): void
    {
        $this->login('plain@example.com');
        $this->get('/admin/workspaces')->assertRedirect('/profile/workspaces');
        $this->get('/admin/plans')->assertRedirect('/profile/plans');
        $this->get('/admin/reads')->assertRedirect('/profile/reads');
        $this->get('/admin/quiz')->assertRedirect('/profile/quiz');
        $this->get('/account')->assertRedirect('/profile/settings');
    }

    public function test_profile_settings_page_opens(): void
    {
        $this->login('plain@example.com');
        $this->get('/profile/settings')
            ->assertOk()
            ->assertSee('Account', false);
    }

    public function test_reads_and_quiz_pages_open(): void
    {
        $this->login('plain@example.com');
        $this->get('/profile/reads')->assertOk();
        $this->get('/profile/quiz')->assertOk();
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
