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
        $response->assertSee('data-overview-stories-grid', false);
        $response->assertSee('data-overview-layout-toggle="grid"', false);
        $response->assertSee('data-overview-layout-toggle="list"', false);
        $response->assertSee('data-overview-hide-read', false);
        $response->assertSee('data-overview-read-reset', false);
        $response->assertSee('data-playbook-slug=', false);
        $response->assertSee('data-sort-date=', false);
        $response->assertSee('data-sort-title-de=', false);
        $response->assertSee('data-sort-title-en=', false);
        $response->assertSee('data-sort-series-part=', false);
        $response->assertSee('data-sort-series-id=', false);
    }

    public function test_playbooks_overview_lists_newest_stories_first(): void
    {
        $html = $this->get('/playbooks')->getContent();

        $dbtRolePos = strpos($html, 'data-playbook-slug="dbt-role"');
        $bigFivePos = strpos($html, 'data-playbook-slug="big-five"');
        $bridgePos = strpos($html, 'data-playbook-slug="bridge-solution"');
        $helpHubPos = strpos($html, 'data-playbook-slug="help-hub-platform"');

        $this->assertNotFalse($dbtRolePos);
        $this->assertNotFalse($bigFivePos);
        $this->assertNotFalse($bridgePos);
        $this->assertNotFalse($helpHubPos);

        $this->assertLessThan($bigFivePos, $dbtRolePos);
        $this->assertLessThan($bridgePos, $bigFivePos);
        $this->assertLessThan($helpHubPos, $bridgePos);
    }
}
