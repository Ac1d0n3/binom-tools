<?php

namespace Tests\Feature\Playbooks;

use Tests\TestCase;

class DisclaimerPageTest extends TestCase
{
    public function test_disclaimer_page_is_available(): void
    {
        $response = $this->get('/disclaimer');

        $response->assertOk();
        $response->assertSee('Disclaimer');
        $response->assertSee('No binding advice');
        $response->assertSee('Not an advertising platform');
        $response->assertSee('not paid recommendations');
    }

    public function test_german_disclaimer_page_is_available(): void
    {
        $response = $this->get('/de/disclaimer');

        $response->assertOk();
        $response->assertSee('data-text-de="Keine verbindliche Beratung"', false);
        $response->assertSee('data-text-de="Keine Werbeplattform"', false);
        $response->assertSee('Ich übernehme keine Verantwortung', false);
    }
}
