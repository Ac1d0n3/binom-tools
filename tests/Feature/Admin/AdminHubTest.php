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

    public function test_plain_user_is_redirected_from_admin_hub(): void
    {
        $this->login('plain@example.com');
        $this->get('/admin')->assertRedirect('/profile');
    }

    public function test_manager_sees_admin_hub_without_personal_nav(): void
    {
        $this->login('admin@example.com');
        $this->get('/admin')
            ->assertOk()
            ->assertSee('Dashboard', false)
            ->assertSee('Administration', false)
            ->assertSee('Back to app', false)
            ->assertDontSee('My Workspaces', false)
            ->assertSee('admin-sidenav', false)
            ->assertSee('admin-hub__dashboard', false);
    }

    public function test_dashboard_counts_match_admin_lists(): void
    {
        $this->login('admin@example.com');
        $html = $this->get('/admin')->assertOk()->getContent();

        $storyCount = count((new \App\Admin\Content\MarkdownContentWriter(
            (string) config('admin.stories_path')
        ))->listSlugs());
        $advisorCount = count((new \App\Admin\Content\CatalogJsonWriter(
            base_path('content/catalogs/advisor-recommendations')
        ))->read()['items'] ?? []);
        $radarSources = count((new \App\Admin\Content\CatalogJsonWriter(
            base_path('content/catalogs/governance-radar')
        ))->read()['sources'] ?? []);
        $vendors = count((new \App\Admin\Content\CatalogJsonWriter(
            base_path('content/catalogs/vendor-resources')
        ))->read()['vendors'] ?? []);
        $suppliers = count((new \App\Admin\Content\CatalogJsonWriter(
            base_path('content/catalogs/suppliers'),
            'products.json'
        ))->read());
        $glossary = count((new \App\Admin\Content\CatalogJsonWriter(
            base_path('content/catalogs/glossary'),
            'terms-core.json'
        ))->read()) + count((new \App\Admin\Content\CatalogJsonWriter(
            base_path('content/catalogs/glossary'),
            'terms-buzzwords.json'
        ))->read());

        $this->assertStringContainsString('admin-hub__dashboard', $html);
        $this->assertStringContainsString('tools-card--hub', $html);
        $this->assertStringContainsString('tools-section__art', $html);
        $this->assertStringNotContainsString('data-overview-search', $html);
        $this->assertStringContainsString('tools-card__count">'.$storyCount.'<', $html);
        $this->assertStringContainsString('tools-card__count">'.$advisorCount.'<', $html);
        $this->assertStringContainsString('tools-card__count">'.$radarSources.'<', $html);
        $this->assertStringContainsString('tools-card__count">'.$vendors.'<', $html);
        $this->assertStringContainsString('tools-card__count">'.$suppliers.'<', $html);
        $this->assertStringContainsString('tools-card__count">'.$glossary.'<', $html);
    }

    public function test_legacy_users_url_redirects_to_admin(): void
    {
        $this->login('admin@example.com');
        $this->get('/account/users')->assertRedirect('/admin/users');
    }

    public function test_stories_index_requires_manage_users(): void
    {
        $this->login('plain@example.com');
        $this->get('/admin/stories')->assertRedirect('/profile');
    }

    public function test_manager_can_open_content_pages(): void
    {
        $this->login('admin@example.com');
        $this->get('/admin/stories')->assertOk()->assertSee('Stories', false);
        $this->get('/admin/plan-templates')->assertOk();
        $this->get('/admin/radar')->assertOk();
        $this->get('/admin/vendors')->assertOk();
        $this->get('/admin/glossary')->assertOk();
    }

    public function test_stories_create_prefills_single_template(): void
    {
        $this->login('admin@example.com');
        $this->get('/admin/stories/create')
            ->assertOk()
            ->assertSee('Single story', false)
            ->assertSee('Series episode', false)
            ->assertSee('author: Thomas Lindackers', false)
            ->assertSee('Apply template', false);
    }

    public function test_stories_create_series_template_shows_series_picker(): void
    {
        $this->login('admin@example.com');
        $this->get('/admin/stories/create?template=series')
            ->assertOk()
            ->assertSee('New series…', false)
            ->assertSee('seriesPart: 1', false);
    }

    public function test_plan_templates_create_has_help_rail(): void
    {
        $this->login('admin@example.com');
        $this->get('/admin/plan-templates/create')
            ->assertOk()
            ->assertSee('Hide side panel', false)
            ->assertSee('```sprint', false)
            ->assertSee('type: sprint-plan', false)
            ->assertSee('Hilfe', false);
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
