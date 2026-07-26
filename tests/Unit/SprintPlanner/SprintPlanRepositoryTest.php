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
        $this->assertSame(['week-01'], $client['sprints'][1]['dependsOn']);
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
        $this->assertSame(['identify-stakeholders'], $client['sprints'][0]['deliverables'][0]['dependsOn']);
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
        $this->assertContains('learning-path-pii-in-five-steps', $slugs);
        $this->assertContains('learning-path-dq-with-dbt', $slugs);
        $this->assertContains('learning-path-modernize-warehouse', $slugs);
        $this->assertContains('learning-path-governance-foundations', $slugs);
        $this->assertContains('learning-path-metadata-operating-model', $slugs);
        $this->assertContains('learning-path-trusted-metrics', $slugs);
        $this->assertContains('learning-path-close-the-gaps', $slugs);
        $this->assertContains('learning-path-ai-foundations', $slugs);
        $this->assertContains('learning-path-access-security-ops', $slugs);
        $this->assertContains('learning-path-end-to-end-governance', $slugs);
        $this->assertContains('learning-path-simplest-viable-stack', $slugs);
        $this->assertContains('learning-path-cert-dbt-analytics-engineer', $slugs);
        $this->assertContains('learning-path-cert-fabric-power-bi', $slugs);
        $this->assertContains('governance-learning-path-certification', $slugs);
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
            'learning-path-pii-in-five-steps' => 3,
            'learning-path-dq-with-dbt' => 3,
            'learning-path-modernize-warehouse' => 3,
            'learning-path-governance-foundations' => 3,
            'learning-path-metadata-operating-model' => 3,
            'learning-path-trusted-metrics' => 3,
            'learning-path-close-the-gaps' => 3,
            'learning-path-ai-foundations' => 3,
            'learning-path-access-security-ops' => 3,
            'learning-path-end-to-end-governance' => 3,
            'learning-path-simplest-viable-stack' => 3,
            'learning-path-cert-dbt-analytics-engineer' => 4,
            'learning-path-cert-fabric-power-bi' => 4,
            'governance-learning-path-certification' => 4,
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
