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

        $this->get('/de/learning-paths')->assertOk();
        $this->get('/en/learning-paths/modernize-warehouse')->assertOk();
        $this->get('/learning-paths/missing')->assertNotFound();
    }
}
