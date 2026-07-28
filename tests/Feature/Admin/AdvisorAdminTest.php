<?php

namespace Tests\Feature\Admin;

use App\Accounts\UserRepository;
use App\Admin\Content\CatalogJsonWriter;
use App\Catalog\CatalogJsonLoader;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AdvisorAdminTest extends TestCase
{
    private string $basePath;

    private string $catalogBackup;

    private string $catalogPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-advisor-admin-'.bin2hex(random_bytes(4));
        mkdir($this->basePath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);

        $this->catalogPath = base_path('content/catalogs/advisor-recommendations/document.json');
        $this->catalogBackup = (string) file_get_contents($this->catalogPath);

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
    }

    protected function tearDown(): void
    {
        file_put_contents($this->catalogPath, $this->catalogBackup);
        CatalogJsonLoader::clearCache();
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_advisor_admin_page_renders(): void
    {
        $this->login();
        $this->get('/admin/advisor')
            ->assertOk()
            ->assertSee('admin-hub__sticky', false)
            ->assertSee('admin-advisor-create-modal', false)
            ->assertSee('story-eight-pillars', false)
            ->assertSee('series-governance-pillars', false)
            ->assertSee('Add recommendation', false)
            ->assertSee('data-admin-advisor-ref="series"', false);
    }

    public function test_story_item_crud_roundtrip(): void
    {
        $this->login();
        $itemId = 'story-ux-'.bin2hex(random_bytes(3));

        $this->post('/admin/advisor/items', [
            'id' => $itemId,
            'kind' => 'story',
            'ref' => 'eight-pillars',
            'enabled' => '1',
            'group' => 'resources',
            'icon' => 'fa-book',
            'score' => 77,
            'tags' => 'help, learning',
            'title_de' => 'Test DE',
            'title_en' => 'Test EN',
            'reason_de' => 'Grund DE',
            'reason_en' => 'Reason EN',
            'when_scenarios' => 'help',
        ])->assertRedirect();

        $writer = new CatalogJsonWriter(base_path('content/catalogs/advisor-recommendations'));
        $doc = $writer->read();
        $found = null;
        foreach ($doc['items'] ?? [] as $item) {
            if (($item['id'] ?? '') === $itemId) {
                $found = $item;
                break;
            }
        }
        $this->assertNotNull($found);
        $this->assertSame('story', $found['kind'] ?? null);
        $this->assertSame('eight-pillars', $found['ref'] ?? null);
        $this->assertSame('Test EN', $found['title']['en'] ?? null);
        $this->assertSame(['help'], $found['when']['scenarios'] ?? null);

        $this->put('/admin/advisor/items/'.$itemId, [
            'kind' => 'story',
            'ref' => 'eight-pillars',
            'enabled' => '1',
            'group' => 'resources',
            'icon' => 'fa-landmark',
            'score' => 88,
            'tags' => 'help',
            'title_de' => 'Updated DE',
            'title_en' => 'Updated EN',
            'reason_de' => 'Grund DE',
            'reason_en' => 'Reason EN',
        ])->assertRedirect();

        $doc = $writer->read();
        $found = null;
        foreach ($doc['items'] ?? [] as $item) {
            if (($item['id'] ?? '') === $itemId) {
                $found = $item;
                break;
            }
        }
        $this->assertSame('Updated EN', $found['title']['en'] ?? null);
        $this->assertSame(88, $found['score'] ?? null);

        $this->delete('/admin/advisor/items/'.$itemId)->assertRedirect();
        $doc = $writer->read();
        $ids = array_map(static fn (array $i): string => (string) ($i['id'] ?? ''), $doc['items'] ?? []);
        $this->assertNotContains($itemId, $ids);
    }

    public function test_invalid_ref_is_rejected(): void
    {
        $this->login();
        $this->from('/admin/advisor')->post('/admin/advisor/items', [
            'id' => 'story-bad-ref',
            'kind' => 'story',
            'ref' => 'not-a-real-story',
            'enabled' => '1',
            'title_de' => 'Bad',
            'title_en' => 'Bad',
            'reason_de' => 'Bad',
            'reason_en' => 'Bad',
        ])->assertRedirect('/admin/advisor')
            ->assertSessionHasErrors('ref');
    }

    public function test_governance_page_injects_content_cards(): void
    {
        $this->get('/governance')
            ->assertOk()
            ->assertSee('contentCards', false)
            ->assertSee('story-eight-pillars', false)
            ->assertSee('series-governance-pillars', false)
            ->assertSee('story-pii-privacy-governance', false);
    }

    public function test_series_item_can_be_stored(): void
    {
        $this->login();
        $itemId = 'series-ux-'.bin2hex(random_bytes(3));

        $this->post('/admin/advisor/items', [
            'id' => $itemId,
            'kind' => 'series',
            'ref' => 'roles-hub',
            'enabled' => '1',
            'group' => 'resources',
            'icon' => 'fa-layer-group',
            'score' => 71,
            'tags' => 'learning, help',
            'title_de' => 'Serie Test DE',
            'title_en' => 'Series Test EN',
            'reason_de' => 'Grund DE',
            'reason_en' => 'Reason EN',
            'when_goals' => 'learning',
        ])->assertRedirect();

        $writer = new CatalogJsonWriter(base_path('content/catalogs/advisor-recommendations'));
        $doc = $writer->read();
        $found = null;
        foreach ($doc['items'] ?? [] as $item) {
            if (($item['id'] ?? '') === $itemId) {
                $found = $item;
                break;
            }
        }
        $this->assertNotNull($found);
        $this->assertSame('series', $found['kind'] ?? null);
        $this->assertSame('roles-hub', $found['ref'] ?? null);

        $this->delete('/admin/advisor/items/'.$itemId)->assertRedirect();
    }

    private function login(): void
    {
        $this->post('/login', [
            'email' => 'admin@example.com',
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
            $full = $path.DIRECTORY_SEPARATOR.$item;
            is_dir($full) ? $this->removeDir($full) : unlink($full);
        }
        rmdir($path);
    }
}
