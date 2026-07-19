<?php

namespace Tests\Unit\SprintPlanner;

use App\SprintPlanner\SprintFenceParser;
use App\SprintPlanner\SprintPlanFrontmatterParser;
use App\SprintPlanner\SprintPlanRepository;
use App\SprintPlanner\SprintPlanValidator;
use Tests\TestCase;

class SprintPlanRepositoryTest extends TestCase
{
    public function test_loads_first_quarter_template_with_matching_ids(): void
    {
        $repo = new SprintPlanRepository(
            new SprintPlanFrontmatterParser,
            new SprintFenceParser,
            new SprintPlanValidator,
        );
        $repo->clearCache();

        $plan = $repo->find('data-reporting-first-quarter');

        $this->assertNotNull($plan);
        $this->assertFalse($plan->hasErrors(), implode("\n", $plan->toClientArray()['errors'] ?? []));
        $client = $plan->toClientArray();
        $this->assertSame('data-reporting-first-quarter', $client['slug']);
        $this->assertSame(13, $client['duration']);
        $this->assertCount(13, $client['sprints']);
        $this->assertSame('week-01', $client['sprints'][0]['id']);
        $this->assertSame('Orientierung und Mandat', $client['locales']['de']['sprints'][0]['title']);
        $this->assertSame('Orientation and Mandate', $client['locales']['en']['sprints'][0]['title']);
    }

    public function test_index_includes_template(): void
    {
        $repo = new SprintPlanRepository(
            new SprintPlanFrontmatterParser,
            new SprintFenceParser,
            new SprintPlanValidator,
        );
        $repo->clearCache();

        $index = $repo->allForIndex();
        $slugs = array_column($index, 'slug');

        $this->assertContains('data-reporting-first-quarter', $slugs);
    }
}
