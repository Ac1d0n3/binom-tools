<?php

namespace Tests\Unit\Support;

use App\Support\AppBase;
use App\Support\LocaleUrl;
use Illuminate\Http\Request;
use Tests\TestCase;

class AppBaseTest extends TestCase
{
    public function test_ignores_configured_subdirectory_when_request_is_at_domain_root(): void
    {
        config(['app.url' => 'http://localhost/binom-tools']);

        $request = Request::create('/playbooks/eight-pillars', 'GET');
        $this->app->instance('request', $request);

        $this->assertSame('', AppBase::path());
    }

    public function test_uses_configured_subdirectory_when_request_matches_it(): void
    {
        config(['app.url' => 'http://localhost/binom-tools']);

        $request = Request::create('/binom-tools/playbooks/eight-pillars', 'GET');
        $this->app->instance('request', $request);

        $this->assertSame('/binom-tools', AppBase::path());
    }

    public function test_locale_switch_urls_for_playbook_detail(): void
    {
        $this->app['url']->forceRootUrl('https://governance.binom.net');

        $response = $this->get('/playbooks/metadata-catalog-lineage');

        $response->assertOk();
        $response->assertSee('data-locale-url="http://governance.binom.net/de/playbooks/metadata-catalog-lineage"', false);
        $response->assertSee('data-locale-url="http://governance.binom.net/playbooks/metadata-catalog-lineage"', false);
    }

    public function test_locale_path_strips_bare_de_prefix_for_english(): void
    {
        config(['app.url' => 'https://governance.binom.net']);
        $this->app['url']->forceRootUrl('https://governance.binom.net');

        $this->assertSame('/', LocaleUrl::path('/de', 'en'));
        $this->assertSame('/de', LocaleUrl::path('/de', 'de'));
    }
}
