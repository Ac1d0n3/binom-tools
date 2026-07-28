<?php

namespace Tests\Feature\Admin;

use App\Accounts\UserRepository;
use App\Admin\Content\CatalogJsonWriter;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AdminCatalogUxTest extends TestCase
{
    private string $basePath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-admin-ux-'.bin2hex(random_bytes(4));
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
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_vendors_page_uses_sticky_header_and_modals(): void
    {
        $this->login();
        $this->get('/admin/vendors')
            ->assertOk()
            ->assertSee('admin-hub__sticky', false)
            ->assertSee('data-overview-search', false)
            ->assertSee('admin-vendor-create-modal', false)
            ->assertSee('admin-vendor-edit-modal', false)
            ->assertSee('data-admin-vendor-workspace', false)
            ->assertSee('data-admin-product-switcher', false)
            ->assertDontSee('admin-product-edit-modal', false)
            ->assertSee('admin-hub__locale-tabs', false)
            ->assertSee('Add vendor', false);
    }

    public function test_product_can_be_created_updated_and_deleted_with_links(): void
    {
        $this->login();
        $productId = 'ux-test-'.bin2hex(random_bytes(3));

        $this->post('/admin/vendors/products', [
            'id' => $productId,
            'vendor' => 'amazon',
            'family' => 'cloud',
            'label_de' => 'UX Test DE',
            'label_en' => 'UX Test EN',
            'purpose_de' => 'Zweck',
            'purpose_en' => 'Purpose',
            'brandColor' => '#123456',
            'logo' => 'test.svg',
            'models' => 'a, b',
            'residency' => 'eu',
            'links' => [
                'help' => [[
                    'href' => 'https://example.com/docs',
                    'label_de' => 'Docs DE',
                    'label_en' => 'Docs EN',
                    'description_de' => 'Beschreibung',
                    'description_en' => 'Description',
                ]],
            ],
        ])->assertRedirect();

        $writer = new CatalogJsonWriter(base_path('content/catalogs/vendor-resources'));
        $doc = $writer->read();
        $found = null;
        foreach ($doc['products'] ?? [] as $product) {
            if (($product['id'] ?? '') === $productId) {
                $found = $product;
                break;
            }
        }
        $this->assertNotNull($found);
        $this->assertSame('UX Test EN', $found['label']['en'] ?? null);
        $this->assertSame('https://example.com/docs', $found['help'][0]['href'] ?? null);

        $this->put('/admin/vendors/products/'.$productId, [
            'vendor' => 'amazon',
            'family' => 'cloud',
            'label_de' => 'UX Test DE2',
            'label_en' => 'UX Test EN2',
            'purpose_de' => 'Zweck 2',
            'purpose_en' => 'Purpose 2',
            'brandColor' => '#654321',
            'logo' => 'test.svg',
            'models' => 'a',
            'residency' => 'eu',
            'links' => [
                'help' => [[
                    'href' => 'https://example.com/updated',
                    'label_de' => 'Docs DE2',
                    'label_en' => 'Docs EN2',
                    'description_de' => '',
                    'description_en' => '',
                ]],
                'governance' => [],
                'learning' => [],
                'certifications' => [],
                'compliance' => [],
            ],
        ])->assertRedirect();

        $doc = $writer->read();
        $found = null;
        foreach ($doc['products'] ?? [] as $product) {
            if (($product['id'] ?? '') === $productId) {
                $found = $product;
                break;
            }
        }
        $this->assertNotNull($found);
        $this->assertSame('UX Test EN2', $found['label']['en'] ?? null);
        $this->assertSame('https://example.com/updated', $found['help'][0]['href'] ?? null);

        $this->delete('/admin/vendors/products/'.$productId)->assertRedirect();
        $doc = $writer->read();
        foreach ($doc['products'] ?? [] as $product) {
            $this->assertNotSame($productId, $product['id'] ?? null);
        }
    }

    public function test_glossary_and_radar_use_modals(): void
    {
        $this->login();
        $this->get('/admin/glossary')
            ->assertOk()
            ->assertSee('admin-glossary-create-modal', false)
            ->assertSee('data-overview-search', false)
            ->assertSee('admin-hub__sticky', false);

        $this->get('/admin/radar')
            ->assertOk()
            ->assertSee('admin-radar-news-create-modal', false)
            ->assertSee('admin-radar-news-edit-modal', false)
            ->assertSee('admin-radar-source-create-modal', false)
            ->assertSee('admin-radar-source-edit-modal', false)
            ->assertSee('admin-hub__expand-children', false)
            ->assertSee('data-admin-expand-toggle', false)
            ->assertSee('data-admin-overview-root', false)
            ->assertSee('admin-hub__sticky', false);
    }

    public function test_suppliers_admin_lists_and_updates_source_meta(): void
    {
        $this->login();
        $this->get('/admin/suppliers')
            ->assertOk()
            ->assertSee('admin-supplier-create-modal', false)
            ->assertSee('admin-supplier-edit-modal', false)
            ->assertSee('data-overview-search', false);

        $id = 'ux-src-'.bin2hex(random_bytes(3));
        $this->post('/admin/suppliers', [
            'id' => $id,
            'domain' => 'crm',
            'order' => 50,
            'label_de' => 'UX Quelle DE',
            'label_en' => 'UX Source EN',
            'purpose_de' => 'Zweck',
            'purpose_en' => 'Purpose',
        ])->assertRedirect();

        $writer = new CatalogJsonWriter(base_path('content/catalogs/suppliers'), 'products.json');
        $products = $writer->read();
        $found = null;
        foreach ($products as $product) {
            if (($product['id'] ?? '') === $id) {
                $found = $product;
                break;
            }
        }
        $this->assertNotNull($found);
        $this->assertSame('UX Source EN', $found['label']['en'] ?? null);

        $this->put('/admin/suppliers/'.$id, [
            'domain' => 'crm',
            'order' => 51,
            'label_de' => 'UX Quelle DE2',
            'label_en' => 'UX Source EN2',
            'purpose_de' => 'Zweck 2',
            'purpose_en' => 'Purpose 2',
        ])->assertRedirect();

        $this->delete('/admin/suppliers/'.$id)->assertRedirect();
        foreach ($writer->read() as $product) {
            $this->assertNotSame($id, $product['id'] ?? null);
        }
    }

    public function test_stories_form_uses_locale_tabs(): void
    {
        $this->login();
        $this->get('/admin/stories/create')
            ->assertOk()
            ->assertSee('admin-hub__locale-tabs', false)
            ->assertSee('admin-hub--md-editor', false)
            ->assertSee('data-admin-confirm-delete-modal', false)
            ->assertDontSee('admin-hub__editor-grid--split', false);
    }

    public function test_admin_overviews_expose_cards_and_table_layout_toggle(): void
    {
        $this->login();

        foreach ([
            '/admin/suppliers',
            '/admin/vendors',
            '/admin/glossary',
            '/admin/radar',
            '/admin/stories',
            '/admin/plan-templates',
            '/admin/users',
            '/admin/teams',
        ] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('data-admin-layout-toggle-group', false)
                ->assertSee('data-admin-layout-toggle="cards"', false)
                ->assertSee('data-admin-layout-toggle="table"', false)
                ->assertSee('fa-grip', false)
                ->assertSee('fa-table', false)
                ->assertDontSee('data-text-de="Karten"', false)
                ->assertDontSee('data-text-en="Cards"', false)
                ->assertSee('data-admin-overview-root', false)
                ->assertSee('data-layout="table"', false)
                ->assertSee('data-admin-overview-panel="cards"', false)
                ->assertSee('data-admin-overview-panel="table"', false)
                ->assertSee('admin-hub__card-grid', false)
                ->assertSee('supplier-table', false);
        }

        // Catalog/content pages always have rows → icon actions must be present.
        foreach (['/admin/suppliers', '/admin/stories', '/admin/glossary'] as $path) {
            $this->get($path)
                ->assertOk()
                ->assertSee('admin-hub__icon-btn', false)
                ->assertSee('fa-pen', false);
        }

        // Full glossary (core + buzzwords), not the old 200-term slice.
        $this->get('/admin/glossary')
            ->assertOk()
            ->assertSee('medallion-architecture', false)
            ->assertSee('data-admin-glossary-id="pii"', false);
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
