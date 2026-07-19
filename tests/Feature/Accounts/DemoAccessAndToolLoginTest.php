<?php

namespace Tests\Feature\Accounts;

use App\Accounts\UserRepository;
use App\Support\ToolsNav;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DemoAccessAndToolLoginTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-demo-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_sprint_planner_is_reachable_without_login_when_accounts_on(): void
    {
        $this->get('/sprint-planner')
            ->assertOk()
            ->assertSee('data-demo-mode="1"', false)
            ->assertSee('Try templates locally in this browser session', false)
            ->assertSee('Plan password', false)
            ->assertSee('Session demo', false)
            ->assertSee('With sign-in (not in this demo)', false)
            ->assertSee('personal user plans and team plans', false)
            ->assertSee('id="sp-demo-banner"', false)
            ->assertDontSee('sp.pageLeadDemo', false)
            ->assertDontSee('sp.demo.sessionBanner', false);
    }

    public function test_guest_index_renders_translated_lead_not_raw_key_on_de_route(): void
    {
        $this->get('/de/sprint-planner')
            ->assertOk()
            ->assertSee('Vorlagen lokal in dieser Browser-Session ausprobieren', false)
            ->assertSee('Plan-Passwort', false)
            ->assertSee('Session-Demo', false)
            ->assertSee('Mit Login (nicht in dieser Demo)', false)
            ->assertSee('User-Pläne und Team-Pläne', false)
            ->assertSee('id="sp-demo-banner"', false)
            ->assertDontSee('sp.pageLeadDemo', false)
            ->assertDontSee('sp.demo.sessionBanner', false);
    }

    public function test_guest_index_renders_english_lead_on_en_route(): void
    {
        $this->get('/en/sprint-planner')
            ->assertOk()
            ->assertSee('Try templates locally in this browser session', false)
            ->assertSee('Plan password', false)
            ->assertDontSee('sp.pageLeadDemo', false)
            ->assertDontSee('sp.demo.sessionBanner', false);
    }

    public function test_signed_in_index_hides_demo_banner(): void
    {
        app(UserRepository::class)->upsert([
            'id' => 'user_admin',
            'email' => 'admin@example.com',
            'displayName' => 'Admin',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => true,
            'canManageTeams' => true,
            'active' => true,
        ]);

        $this->post('/login', [
            'email' => 'admin@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $this->get('/sprint-planner')
            ->assertOk()
            ->assertSee('Saved plans sync to this server.', false)
            ->assertDontSee('id="sp-demo-banner"', false)
            ->assertDontSee('Try templates locally in this browser session', false)
            ->assertDontSee('data-demo-mode="1"', false);
    }

    public function test_tool_login_required_defaults_to_false(): void
    {
        $this->assertFalse(ToolsNav::isToolLoginRequired('prompt-studio'));
        $this->get('/tools/prompt-studio')->assertOk();
    }

    public function test_tool_login_required_redirects_guest_when_flag_true(): void
    {
        Config::set('tools.login_required.prompt-studio', true);

        $this->get('/tools/prompt-studio')
            ->assertRedirect();

        $this->assertStringContainsString('/login', parse_url(
            (string) $this->get('/tools/prompt-studio')->headers->get('Location'),
            PHP_URL_PATH,
        ) ?: '');
    }

    public function test_tool_login_required_allows_authenticated_user(): void
    {
        Config::set('tools.login_required.prompt-studio', true);

        app(UserRepository::class)->upsert([
            'id' => 'user_demo',
            'email' => 'demo@example.com',
            'displayName' => 'Demo',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'active' => true,
        ]);

        $this->post('/login', [
            'email' => 'demo@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $this->get('/tools/prompt-studio')->assertOk();
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
