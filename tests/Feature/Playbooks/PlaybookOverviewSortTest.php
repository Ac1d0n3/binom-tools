<?php

namespace Tests\Feature\Playbooks;

use Tests\TestCase;

class PlaybookOverviewSortTest extends TestCase
{
    public function test_playbooks_overview_includes_sort_dropdown_and_data_attributes(): void
    {
        $response = $this->get('/playbooks');

        $response->assertOk();
        $response->assertSee('data-overview-sort', false);
        $response->assertSee('data-sort-date=', false);
        $response->assertSee('data-sort-title-de=', false);
        $response->assertSee('data-sort-title-en=', false);
        $response->assertSee('data-sort-series-part=', false);
        $response->assertSee('data-sort-series-id=', false);
    }
}
