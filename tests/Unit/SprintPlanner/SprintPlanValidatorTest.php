<?php

namespace Tests\Unit\SprintPlanner;

use App\SprintPlanner\SprintPlanValidator;
use PHPUnit\Framework\TestCase;

class SprintPlanValidatorTest extends TestCase
{
    public function test_validates_required_meta_and_unique_ids(): void
    {
        $validator = new SprintPlanValidator;

        $meta = [
            'type' => 'sprint-plan',
            'title' => 'Demo',
            'slug' => 'demo',
            'description' => 'Desc',
            'duration' => 1,
            'unit' => 'week',
            'version' => 1,
            'locale' => 'en',
        ];

        $sprints = [[
            'id' => 'week-01',
            'number' => 1,
            'title' => 'Week 1',
            'goal' => 'Goal',
            'tasks' => [
                ['id' => 'task-a', 'label' => 'A'],
                ['id' => 'task-a', 'label' => 'A again'],
            ],
            'deliverables' => [['id' => 'del-a', 'label' => 'D']],
            'fields' => [['id' => 'field-a', 'label' => 'F', 'type' => 'text']],
        ]];

        $result = $validator->validateLocaleVariant($meta, $sprints);

        $this->assertTrue(
            collect($result['errors'])->contains(fn (string $e): bool => str_contains($e, 'duplicate task id'))
        );
    }

    public function test_compares_de_en_structure_ids(): void
    {
        $validator = new SprintPlanValidator;

        $de = [[
            'id' => 'week-01',
            'number' => 1,
            'tasks' => [['id' => 'task-a']],
            'deliverables' => [['id' => 'del-a']],
            'fields' => [['id' => 'field-a']],
        ]];
        $en = [[
            'id' => 'week-01',
            'number' => 1,
            'tasks' => [['id' => 'task-b']],
            'deliverables' => [['id' => 'del-a']],
            'fields' => [['id' => 'field-a']],
        ]];

        $errors = $validator->compareLocaleStructures(
            $de,
            $en,
            ['slug' => 'demo', 'version' => 1],
            ['slug' => 'demo', 'version' => 1],
        );

        $this->assertNotEmpty($errors);
    }

    public function test_accepts_matching_de_en_structures(): void
    {
        $validator = new SprintPlanValidator;

        $shared = [[
            'id' => 'week-01',
            'number' => 1,
            'tasks' => [['id' => 'task-a']],
            'deliverables' => [['id' => 'del-a']],
            'fields' => [['id' => 'field-a']],
        ]];

        $errors = $validator->compareLocaleStructures(
            $shared,
            $shared,
            ['slug' => 'demo', 'version' => 1],
            ['slug' => 'demo', 'version' => 1],
        );

        $this->assertSame([], $errors);
    }
}
