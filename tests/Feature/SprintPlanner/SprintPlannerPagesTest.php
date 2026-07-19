<?php

namespace Tests\Feature\SprintPlanner;

use Tests\TestCase;

class SprintPlannerPagesTest extends TestCase
{
    public function test_index_page_is_reachable_in_english_and_german(): void
    {
        $this->get('/sprint-planner')->assertOk()->assertSee('Sprint Planner', false);
        $this->get('/de/sprint-planner')->assertOk()->assertSee('Sprint Planner', false);
    }

    public function test_templates_and_people_pages_are_reachable(): void
    {
        $this->get('/sprint-planner/templates')->assertOk();
        $this->get('/sprint-planner/people')->assertOk();
        $this->get('/de/sprint-planner/templates')->assertOk();
        $this->get('/de/sprint-planner/people')->assertOk();
    }

    public function test_show_and_settings_accept_plan_ids(): void
    {
        $this->get('/sprint-planner/plan_20260701_abc123')
            ->assertOk()
            ->assertSee('data-sp-instance-id="plan_20260701_abc123"', false);

        $this->get('/de/sprint-planner/plan_20260701_abc123')
            ->assertOk()
            ->assertSee('data-sp-instance-id="plan_20260701_abc123"', false);

        $this->get('/sprint-planner/plan_20260701_abc123/settings')->assertOk();
        $this->get('/de/sprint-planner/plan_20260701_abc123/settings')
            ->assertOk()
            ->assertSee('data-sp-instance-id="plan_20260701_abc123"', false);
    }

    public function test_sidebar_contains_sprint_planner_nav(): void
    {
        $this->get('/sprint-planner')
            ->assertOk()
            ->assertSee('nav.sprintPlanner', false)
            ->assertSee('data-reporting-first-quarter', false);
    }
}
