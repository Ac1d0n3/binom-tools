<?php

namespace Tests\Feature\Tools;

use App\Accounts\UserRepository;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PromptStudioLibraryApiTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-ps-lib-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);

        app(UserRepository::class)->upsert([
            'id' => 'user_ps',
            'email' => 'ps@example.com',
            'displayName' => 'PS Tester',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_guest_cannot_access_library_api(): void
    {
        $this->getJson('/api/prompt-studio/library')->assertUnauthorized();
    }

    public function test_logged_in_user_can_upsert_library(): void
    {
        $this->post('/login', [
            'email' => 'ps@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $payload = [
            'library' => [
                'templates' => [
                    ['id' => 'tpl_1', 'name' => 'Demo', 'kind' => 'prompt'],
                ],
                'chains' => [
                    ['id' => 'uchain_1', 'name' => 'My flow', 'steps' => []],
                ],
                'customRoles' => [
                    ['id' => 'custom_1', 'label' => ['en' => 'Mine', 'de' => 'Meine'], 'taskIds' => []],
                ],
            ],
        ];

        $this->postJson('/api/prompt-studio/library', $payload)
            ->assertOk()
            ->assertJsonPath('templates.0.id', 'tpl_1')
            ->assertJsonPath('chains.0.id', 'uchain_1');

        $this->getJson('/api/prompt-studio/library')
            ->assertOk()
            ->assertJsonPath('templates.0.name', 'Demo')
            ->assertJsonPath('customRoles.0.id', 'custom_1');
    }

    public function test_manifest_lists_new_chains(): void
    {
        $manifestPath = public_path('prompt-studio/config/manifest.json');
        $this->assertFileExists($manifestPath);
        $json = json_decode((string) file_get_contents($manifestPath), true);
        $chains = $json['files']['chains'] ?? [];
        $this->assertContains('chains/meeting-to-tasks.json', $chains);
        $this->assertContains('chains/debug-investigate.json', $chains);
        $this->assertContains('chains/playbook-outline.json', $chains);
        $this->assertContains('chains/email-inbox-to-reply.json', $chains);
        $this->assertContains('chains/md-story-bilingual.json', $chains);
        $this->assertContains('chains/email-inbox-insights.json', $chains);
    }

    private function removeDir(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }
        $items = scandir($path) ?: [];
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }
            $full = $path.DIRECTORY_SEPARATOR.$item;
            if (is_dir($full)) {
                $this->removeDir($full);
            } else {
                @unlink($full);
            }
        }
        @rmdir($path);
    }
}
