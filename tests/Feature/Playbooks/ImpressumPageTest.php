<?php

namespace Tests\Feature\Playbooks;

use Tests\TestCase;

class ImpressumPageTest extends TestCase
{
    public function test_impressum_page_is_available(): void
    {
        $response = $this->get('/impressum');

        $response->assertOk();
        $response->assertSee('Impressum');
        $response->assertSee('Thomas Lindackers');
        $response->assertSee('support@governance.binom.net');
    }
}
