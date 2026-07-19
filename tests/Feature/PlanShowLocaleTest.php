<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PlanShowLocaleTest extends TestCase
{
    public function test_api_urls_are_locale_free_and_history_works(): void
    {
        Config::set('accounts.enabled', true);

        $this->post('/de/login', [
            'email' => 't.l@binom-network.org',
            'password' => 'TempTest123!',
        ])->assertRedirect();

        // locale_route must not prefix /de for plan APIs
        $this->assertStringNotContainsString('/de/api/', locale_route('accounts.plans.index', [], 'de'));
        $this->assertStringContainsString('/api/sprint-planner/plans', locale_route('accounts.plans.index', [], 'de'));

        $this->getJson('/api/sprint-planner/plans/plan_20260817_acid1')
            ->assertOk()
            ->assertJsonPath('plan.id', 'plan_20260817_acid1');

        $this->getJson('/api/sprint-planner/plans/plan_20260817_acid1/history')
            ->assertOk()
            ->assertJsonStructure(['revisions']);

        // Old localized API paths should 404 (no longer registered)
        $this->getJson('/de/api/sprint-planner/plans/plan_20260817_acid1/history')
            ->assertNotFound();
    }
}
