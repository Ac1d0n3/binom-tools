<?php

namespace Tests\Feature\Admin;

use App\Accounts\UserRepository;
use App\Admin\Content\ContentOwnership;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AdminContentPermissionsTest extends TestCase
{
    private string $basePath;

    private string $storiesPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->basePath = sys_get_temp_dir().'/bn-tools-admin-perms-'.bin2hex(random_bytes(4));
        $this->storiesPath = $this->basePath.'/stories';
        mkdir($this->storiesPath, 0775, true);
        Config::set('accounts.enabled', true);
        Config::set('accounts.path', $this->basePath);
        Config::set('admin.stories_path', $this->storiesPath);

        app(UserRepository::class)->upsert([
            'id' => 'user_admin',
            'email' => 'admin@example.com',
            'displayName' => 'Admin',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => true,
            'canManageTeams' => true,
            'canManageContent' => true,
            'contentAreas' => [
                'stories' => true,
                'planTemplates' => true,
                'vendorsSources' => true,
                'news' => true,
                'glossary' => true,
            ],
            'active' => true,
            'shortName' => 'ADM',
            'colorToken' => 'accent-2',
        ]);

        app(UserRepository::class)->upsert([
            'id' => 'user_writer',
            'email' => 'writer@example.com',
            'displayName' => 'Writer',
            'passwordHash' => password_hash('password123', PASSWORD_DEFAULT),
            'canManageUsers' => false,
            'canManageTeams' => false,
            'canManageContent' => false,
            'contentAreas' => [
                'stories' => true,
                'planTemplates' => false,
                'vendorsSources' => false,
                'news' => true,
                'glossary' => false,
            ],
            'active' => true,
            'shortName' => 'WRI',
            'colorToken' => 'accent-1',
        ]);
    }

    protected function tearDown(): void
    {
        $this->removeDir($this->basePath);
        parent::tearDown();
    }

    public function test_sidebar_shows_profile_hub_and_filters_by_area(): void
    {
        $this->login('writer@example.com');
        $this->get('/admin')
            ->assertOk()
            ->assertSee('Profile Hub', false)
            ->assertSee('>Radar</span>', false)
            ->assertSee('>Stories</span>', false)
            ->assertDontSee('>Vendors</span>', false)
            ->assertDontSee('>Glossary</span>', false)
            ->assertDontSee('>Users</span>', false);
    }

    public function test_writer_cannot_open_vendors(): void
    {
        $this->login('writer@example.com');
        $this->get('/admin/vendors')->assertForbidden();
    }

    public function test_writer_story_create_sets_owner_and_blocks_foreign(): void
    {
        $this->login('writer@example.com');
        $body = ContentOwnership::ensureMarkdownOwner("---\ntitle: \"Mine\"\n---\n\nHi\n", 'user_writer');
        // store without owner in request body — controller stamps
        $this->post('/admin/stories', [
            'slug' => 'my-story',
            'body_en' => "---\ntitle: \"Mine\"\n---\n\nHi\n",
            'body_de' => "---\ntitle: \"Meine\"\n---\n\nHi\n",
        ])->assertRedirect();

        $written = file_get_contents($this->storiesPath.'/my-story.en.md');
        $this->assertNotFalse($written);
        $this->assertSame('user_writer', ContentOwnership::ownerFromMarkdown($written));

        file_put_contents(
            $this->storiesPath.'/foreign.en.md',
            "---\ntitle: \"Other\"\ncreatedByUserId: user_admin\n---\n\nX\n"
        );

        $this->get('/admin/stories/foreign/edit')->assertForbidden();
        $this->get('/admin/stories')
            ->assertOk()
            ->assertSee('my-story', false)
            ->assertDontSee('foreign', false);
    }

    public function test_content_admin_can_edit_ownerless_story(): void
    {
        file_put_contents(
            $this->storiesPath.'/legacy.en.md',
            "---\ntitle: \"Legacy\"\n---\n\nOld\n"
        );

        $this->login('writer@example.com');
        $this->get('/admin/stories/legacy/edit')->assertForbidden();

        $this->login('admin@example.com');
        $this->get('/admin/stories/legacy/edit')->assertOk();
    }

    public function test_delete_forms_use_confirm_modal_attribute(): void
    {
        $this->login('admin@example.com');
        $this->get('/admin/radar')
            ->assertOk()
            ->assertSee('data-admin-confirm-delete', false)
            ->assertSee('data-admin-confirm-delete-modal', false)
            ->assertDontSee('return confirm(', false);
    }

    private function login(string $email): void
    {
        $this->post('/login', [
            'email' => $email,
            'password' => 'password123',
        ])->assertRedirect();
    }

    private function removeDir(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }
        foreach (scandir($dir) ?: [] as $item) {
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
