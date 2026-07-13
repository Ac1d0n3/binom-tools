<?php

namespace Tests\Feature\About;

use Tests\TestCase;

class AboutPageTest extends TestCase
{
    public function test_about_page_renders(): void
    {
        $response = $this->get('/about');

        $response->assertOk();
        $response->assertSee('data-i18n="about.title"', false);
        $response->assertSee('data-i18n="about.stories.body"', false);
        $response->assertSee('data-i18n="about.feedback.title"', false);
        $response->assertSee('tools-release-meta', false);
        $response->assertSee('v0.1.0', false);
        $response->assertSee('tools-beta-badge', false);
        $response->assertSee('https://github.com/Ac1d0n3/binom-tools', false);
        $response->assertDontSee('mailto:', false);
    }

    public function test_about_page_is_localized(): void
    {
        $response = $this->get('/de/about');

        $response->assertOk();
        $response->assertSee('data-i18n="about.title"', false);
    }
}
