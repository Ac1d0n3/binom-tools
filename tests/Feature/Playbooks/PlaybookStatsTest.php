<?php

namespace Tests\Feature\Playbooks;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

final class PlaybookStatsTest extends TestCase
{
    private string $statsDir;

    protected function setUp(): void
    {
        parent::setUp();
        $this->statsDir = storage_path('app/playbook-stats-test-'.uniqid('', true));
        File::ensureDirectoryExists($this->statsDir);
        $this->app->instance(
            \App\Playbooks\PlaybookStatsStore::class,
            new \App\Playbooks\PlaybookStatsStore($this->statsDir),
        );
    }

    protected function tearDown(): void
    {
        File::deleteDirectory($this->statsDir);
        parent::tearDown();
    }

    public function test_stats_endpoints_count_view_once_per_cookie_and_toggle_like(): void
    {
        $slug = 'eight-pillars';
        $cookieName = \App\Http\Controllers\Playbooks\PlaybookStatsController::ENGAGEMENT_COOKIE;

        $this->getJson("/playbooks/{$slug}/stats")
            ->assertOk()
            ->assertJson(['views' => 0, 'likes' => 0, 'liked' => false]);

        $this->postJson("/playbooks/{$slug}/stats/view")
            ->assertOk()
            ->assertJson(['views' => 1, 'counted' => true]);

        $alreadyViewed = base64_encode(json_encode([
            'v' => [$slug => gmdate('Y-m-d')],
            'l' => [],
        ], JSON_THROW_ON_ERROR));

        $this->call('POST', "/playbooks/{$slug}/stats/view", server: [
            'HTTP_ACCEPT' => 'application/json',
        ], cookies: [
            $cookieName => $alreadyViewed,
        ])->assertOk()->assertJson(['views' => 1, 'counted' => false]);

        $likedCookie = base64_encode(json_encode([
            'v' => [$slug => gmdate('Y-m-d')],
            'l' => [],
        ], JSON_THROW_ON_ERROR));

        $this->call('POST', "/playbooks/{$slug}/stats/like", server: [
            'HTTP_ACCEPT' => 'application/json',
        ], cookies: [
            $cookieName => $likedCookie,
        ])->assertOk()->assertJson(['views' => 1, 'likes' => 1, 'liked' => true]);

        $unlikeCookie = base64_encode(json_encode([
            'v' => [$slug => gmdate('Y-m-d')],
            'l' => [$slug => 1],
        ], JSON_THROW_ON_ERROR));

        $this->call('POST', "/playbooks/{$slug}/stats/like", server: [
            'HTTP_ACCEPT' => 'application/json',
        ], cookies: [
            $cookieName => $unlikeCookie,
        ])->assertOk()->assertJson(['likes' => 0, 'liked' => false]);
    }

    public function test_share_controls_respect_env_flag_on_show_page(): void
    {
        config(['playbooks.share_enabled' => false]);

        $this->get('/playbooks/eight-pillars')
            ->assertOk()
            ->assertDontSee('data-playbook-share', false)
            ->assertSee('data-playbook-engagement', false);
    }

    public function test_share_menu_lists_social_networks_when_enabled(): void
    {
        config(['playbooks.share_enabled' => true]);

        $this->get('/playbooks/eight-pillars')
            ->assertOk()
            ->assertSee('data-playbook-share-menu', false)
            ->assertSee('data-share-network="facebook"', false)
            ->assertSee('data-share-network="linkedin"', false)
            ->assertSee('data-share-network="xing"', false)
            ->assertSee('data-share-copy', false);
    }

    public function test_disabled_tool_returns_404(): void
    {
        config(['tools.enabled.prompt-studio' => false]);

        $this->get('/tools/prompt-studio')->assertNotFound();

        $overview = $this->get('/tools');
        $overview->assertOk();
        $this->assertStringNotContainsString('/tools/prompt-studio"', $overview->getContent());
        $this->assertStringNotContainsString("data-card-id=\"prompt-studio\"", $overview->getContent());
    }
}
