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
        $this->assertNotEmpty($client['sprints'][0]['linkedStorySlugs']);
        $firstTask = $client['sprints'][0]['tasks'][0];
        $this->assertSame('align-management-expectations', $firstTask['id']);
        $this->assertNotEmpty($firstTask['linkedStorySlugs']);
        $this->assertNotEmpty($firstTask['helpLinks']);
        $deTask = $client['locales']['de']['sprints'][0]['tasks'][0];
        $enTask = $client['locales']['en']['sprints'][0]['tasks'][0];
        $this->assertNotEmpty($deTask['helpText']);
        $this->assertNotEmpty($enTask['helpText']);
        $this->assertSame($firstTask['helpLinks'][0]['href'], $deTask['helpLinks'][0]['href']);
        $this->assertNotEmpty($client['sprints'][0]['flowSteps'] ?? []);
        $this->assertNotEmpty($client['sprints'][0]['stories'] ?? $client['sprints'][0]['linkedStorySlugs'] ?? []);
    }

    public function test_index_includes_stack_templates(): void
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
        $this->assertContains('data-reporting-fq-fivetran-snowflake-qlik', $slugs);
        $this->assertContains('data-reporting-fq-fivetran-snowflake-powerbi', $slugs);
        $this->assertContains('data-reporting-fq-fabric-qlik-qvd', $slugs);
        $this->assertContains('planning-month', $slugs);
        $this->assertContains('planning-quarter-lite', $slugs);
        $this->assertContains('database-model', $slugs);
        $this->assertContains('report-kpi-analysis', $slugs);
        $this->assertContains('change-tests', $slugs);
    }

    public function test_loads_new_lightweight_templates(): void
    {
        $repo = new SprintPlanRepository(
            new SprintPlanFrontmatterParser,
            new SprintFenceParser,
            new SprintPlanValidator,
        );
        $repo->clearCache();

        $cases = [
            'planning-month' => 4,
            'planning-quarter-lite' => 13,
            'database-model' => 4,
            'report-kpi-analysis' => 4,
            'change-tests' => 3,
        ];

        foreach ($cases as $slug => $duration) {
            $plan = $repo->find($slug);
            $this->assertNotNull($plan, $slug);
            $this->assertFalse(
                $plan->hasErrors(),
                $slug."\n".implode("\n", $plan->toClientArray()['errors'] ?? []),
            );
            $client = $plan->toClientArray();
            $this->assertSame($slug, $client['slug']);
            $this->assertSame($duration, $client['duration']);
            $this->assertCount($duration, $client['sprints']);
            $this->assertSame('week-01', $client['sprints'][0]['id']);
            $this->assertNotEmpty($client['locales']['de']['sprints'][0]['title']);
            $this->assertNotEmpty($client['locales']['en']['sprints'][0]['title']);
        }
    }
}