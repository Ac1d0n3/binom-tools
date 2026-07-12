<?php

namespace Tests\Unit\Support;

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
}
