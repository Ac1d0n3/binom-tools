<?php

namespace Tests\Feature\Playbooks;

use Tests\TestCase;

class PrivacyPageTest extends TestCase
{
    public function test_privacy_page_is_available(): void
    {
        $response = $this->get('/datenschutz');

        $response->assertOk();
        $response->assertSee('Privacy Policy');
        $response->assertSee('support@governance.binom.net');
        $response->assertSee('localStorage');
    }

    public function test_german_privacy_page_is_available(): void
    {
        $response = $this->get('/de/datenschutz');

        $response->assertOk();
        $response->assertSee('Datenschutz');
    }
}
