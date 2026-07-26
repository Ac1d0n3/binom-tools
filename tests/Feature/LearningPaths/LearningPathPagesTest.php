<?php

namespace Tests\Feature\LearningPaths;

use Tests\TestCase;

class LearningPathPagesTest extends TestCase
{
    public function test_learning_paths_index_and_show_pages(): void
    {
        $index = $this->get('/learning-paths');
        $index->assertOk();
        $index->assertSee('data-i18n="learningPaths.indexTitle"', false);
        $index->assertSee('learning-paths-hub-grid', false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'pii-in-five-steps']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'metadata-operating-model']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'trusted-metrics']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'close-the-gaps']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'ai-foundations']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'cert-project-evidence']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'cert-dbt-analytics-engineer']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'cert-fabric-power-bi']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'access-security-ops']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'end-to-end-governance']), false);
        $index->assertSee(route('learning-paths.show', ['slug' => 'simplest-viable-stack']), false);
        $index->assertDontSee('data-overview-search', false);
        $index->assertDontSee('learningPaths.searchPlaceholder', false);

        $show = $this->get('/learning-paths/dq-with-dbt');
        $show->assertOk();
        $show->assertSee('DQ with dbt', false);
        $show->assertSee('learning-path-detail__steps', false);
        $show->assertSee('learningPaths.startSprintPlan', false);
        $show->assertSee('start=learning-path-dq-with-dbt', false);
        $show->assertSee('learningPaths.relatedRolesTitle', false);
        $show->assertSee(route('roles.show', ['slug' => 'steward']), false);
        $show->assertSee(route('playbooks.series', ['seriesId' => 'operational-data-quality']), false);

        $pii = $this->get('/learning-paths/pii-in-five-steps');
        $pii->assertOk();
        $pii->assertSee('start=learning-path-pii-in-five-steps', false);

        $metadata = $this->get('/learning-paths/metadata-operating-model');
        $metadata->assertOk();
        $metadata->assertSee('start=learning-path-metadata-operating-model', false);

        $cert = $this->get('/learning-paths/cert-project-evidence');
        $cert->assertOk();
        $cert->assertSee('start=governance-learning-path-certification', false);

        $dbtCert = $this->get('/learning-paths/cert-dbt-analytics-engineer');
        $dbtCert->assertOk();
        $dbtCert->assertSee('dbt cert companion', false);
        $dbtCert->assertSee('https://www.getdbt.com/certifications', false);
        $dbtCert->assertSee('start=learning-path-cert-dbt-analytics-engineer', false);
        $dbtCert->assertSee('learningPaths.startSprintPlan', false);
        $dbtCert->assertSee('target="_blank"', false);

        $this->get('/de/learning-paths')->assertOk();
        $this->get('/en/learning-paths/modernize-warehouse')->assertOk();
        $this->get('/learning-paths/missing')->assertNotFound();
    }
}
