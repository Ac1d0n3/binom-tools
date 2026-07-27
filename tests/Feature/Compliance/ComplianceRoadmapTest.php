<?php

namespace Tests\Feature\Compliance;

use Tests\TestCase;

class ComplianceRoadmapTest extends TestCase
{
    public function test_roadmap_shows_organisation_context_intro(): void
    {
        $this->get('/compliance/roadmap')
            ->assertOk()
            ->assertSee('compliance-roadmap-org-context', false)
            ->assertSee('By organisation context')
            ->assertSee('Open Governance Advisor', false)
            ->assertSee(route('governance.index'), false);
    }
}
