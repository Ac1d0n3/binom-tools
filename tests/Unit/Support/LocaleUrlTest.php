<?php

namespace Tests\Unit\Support;

use App\Support\LocaleUrl;
use Tests\TestCase;

class LocaleUrlTest extends TestCase
{
    public function test_locale_route_uses_subdirectory_before_locale_prefix(): void
    {
        $this->app['url']->forceRootUrl('http://localhost/binom-tools');

        $this->assertSame(
            'http://localhost/binom-tools/de/playbooks/eight-pillars',
            locale_route('playbooks.show', ['slug' => 'eight-pillars'], 'de'),
        );

        $this->assertSame(
            'http://localhost/binom-tools/playbooks/eight-pillars',
            locale_route('playbooks.show', ['slug' => 'eight-pillars'], 'en'),
        );
    }

    public function test_locale_path_uses_subdirectory_before_locale_prefix(): void
    {
        config(['app.url' => 'http://localhost/binom-tools']);
        $this->app['url']->forceRootUrl('http://localhost/binom-tools');

        $this->assertSame(
            '/binom-tools/de/playbooks/eight-pillars',
            LocaleUrl::path('/playbooks/eight-pillars', 'de'),
        );

        $this->assertSame(
            '/binom-tools/playbooks/eight-pillars',
            LocaleUrl::path('/playbooks/eight-pillars', 'en'),
        );
    }
}
