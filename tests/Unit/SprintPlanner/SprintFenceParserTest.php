<?php

namespace Tests\Unit\SprintPlanner;

use App\SprintPlanner\SprintFenceParser;
use PHPUnit\Framework\TestCase;

class SprintFenceParserTest extends TestCase
{
    public function test_parses_sprint_block_with_tasks_deliverables_and_fields(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

tasks:
  - id: align-management-expectations
    label: Align expectations
    assigneeType: person
    assigneeId: null
  - id: identify-stakeholders
    label: Identify stakeholders
    assigneeType: team
    assigneeId: null

deliverables:
  - id: stakeholder-list
    label: Stakeholder list
    dependsOn: identify-stakeholders

fields:
  - id: management-expectations
    label: Expectations
    type: textarea
    placeholder: Goals

notes: true
```
MD;

        $result = $parser->parse($body);

        $this->assertSame([], $result['errors']);
        $this->assertCount(1, $result['sprints']);
        $sprint = $result['sprints'][0];
        $this->assertSame('week-01', $sprint['id']);
        $this->assertSame(1, $sprint['number']);
        $this->assertTrue($sprint['notes']);
        $this->assertCount(2, $sprint['tasks']);
        $this->assertSame('align-management-expectations', $sprint['tasks'][0]['id']);
        $this->assertSame('person', $sprint['tasks'][0]['assigneeType']);
        $this->assertCount(1, $sprint['deliverables']);
        $this->assertSame(['identify-stakeholders'], $sprint['deliverables'][0]['dependsOn']);
        $this->assertCount(1, $sprint['fields']);
        $this->assertSame('textarea', $sprint['fields'][0]['type']);
    }

    public function test_parses_linked_stories_on_sprint_and_task(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

linkedStories:
  - ai-basics
  - eight-pillars

tasks:
  - id: read-guide
    label: Read guide
    linkedStories: secret-guide, ai-basics
```
MD;

        $result = $parser->parse($body);
        $this->assertSame([], $result['errors']);
        $sprint = $result['sprints'][0];
        $this->assertSame(['ai-basics', 'eight-pillars'], $sprint['linkedStories']);
        $this->assertSame(['secret-guide', 'ai-basics'], $sprint['tasks'][0]['linkedStorySlugs']);
    }

    public function test_parses_help_text_help_links_and_sprint_links(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

links:
  - label: DQ Rules
    href: /tools/dbt-dq-rules-generator

tasks:
  - id: read-guide
    label: Read guide
    helpText: |
      First line
      Second line
    helpLinks:
      - label: KPI Definition
        href: /playbooks/define-kpi
      - label: DQ Tool
        href: /tools/dbt-dq-macro-generator
```
MD;

        $result = $parser->parse($body);
        $this->assertSame([], $result['errors']);
        $sprint = $result['sprints'][0];
        $this->assertSame([
            ['label' => 'DQ Rules', 'href' => '/tools/dbt-dq-rules-generator'],
        ], $sprint['links']);
        $task = $sprint['tasks'][0];
        $this->assertSame("First line\nSecond line", $task['helpText']);
        $this->assertSame([
            ['label' => 'KPI Definition', 'href' => '/playbooks/define-kpi'],
            ['label' => 'DQ Tool', 'href' => '/tools/dbt-dq-macro-generator'],
        ], $task['helpLinks']);
    }

    public function test_falls_back_unknown_field_type_to_text(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Test
goal: Test goal.

fields:
  - id: weird
    label: Weird
    type: magic
```
MD;

        $result = $parser->parse($body);

        $this->assertSame('text', $result['sprints'][0]['fields'][0]['type']);
        $this->assertNotEmpty($result['warnings']);
    }

    public function test_reports_error_when_required_fields_missing(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Only title
```
MD;

        $result = $parser->parse($body);

        $this->assertNotEmpty($result['errors']);
        $this->assertSame([], $result['sprints']);
    }

    public function test_parses_sprint_level_stories_and_merges_linked_stories(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

linkedStories:
  - ai-basics
  - bi-tools

stories:
  - slug: define-kpi
    required: true
  - slug: bi-tools
    required: false
```
MD;

        $result = $parser->parse($body);

        $this->assertSame([], $result['errors']);
        $sprint = $result['sprints'][0];
        $this->assertSame(['ai-basics', 'bi-tools'], $sprint['linkedStories']);
        $this->assertSame([
            ['slug' => 'define-kpi', 'required' => true],
            ['slug' => 'bi-tools', 'required' => false],
            ['slug' => 'ai-basics', 'required' => true],
        ], $sprint['stories']);
    }

    public function test_parses_sprint_level_flow_configuration(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

flowVariant: linear
flowLayout: vertical
flowSteps:
  - Source
  - Fivetran
```
MD;

        $result = $parser->parse($body);

        $this->assertSame([], $result['errors']);
        $sprint = $result['sprints'][0];
        $this->assertSame('linear', $sprint['flowVariant']);
        $this->assertSame('vertical', $sprint['flowLayout']);
        $this->assertSame(['Source', 'Fivetran'], $sprint['flowSteps']);
    }

    public function test_flow_configuration_defaults_when_absent(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.
```
MD;

        $result = $parser->parse($body);

        $sprint = $result['sprints'][0];
        $this->assertSame('linear', $sprint['flowVariant']);
        $this->assertSame('vertical', $sprint['flowLayout']);
        $this->assertSame([], $sprint['flowSteps']);
    }

    public function test_parses_nested_stories_and_demo_code_on_tasks_and_deliverables(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

tasks:
  - id: read-guide
    label: Read guide
    linkedStories: secret-guide
    stories:
      - slug: define-kpi
        required: true
      - slug: bi-tools
        required: false
    demoCode: |
      SELECT 1;
      SELECT 2;

deliverables:
  - id: kpi-sheet
    label: KPI sheet
    stories:
      - slug: define-kpi
        required: true
    demoCode: |
      echo "done"
```
MD;

        $result = $parser->parse($body);

        $this->assertSame([], $result['errors']);
        $sprint = $result['sprints'][0];

        $task = $sprint['tasks'][0];
        $this->assertSame([
            ['slug' => 'define-kpi', 'required' => true],
            ['slug' => 'bi-tools', 'required' => false],
            ['slug' => 'secret-guide', 'required' => true],
        ], $task['stories']);
        $this->assertSame("SELECT 1;\nSELECT 2;", $task['demoCode']);

        $deliverable = $sprint['deliverables'][0];
        $this->assertSame('kpi-sheet', $deliverable['id']);
        $this->assertSame('KPI sheet', $deliverable['label']);
        $this->assertSame([
            ['slug' => 'define-kpi', 'required' => true],
        ], $deliverable['stories']);
        $this->assertSame('echo "done"', $deliverable['demoCode']);
    }

    public function test_normalizes_tasks_and_deliverables_with_help_and_story_fields(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

tasks:
  - id: read-guide
    label: Read guide
    helpText: |
      First line
    helpLinks:
      - label: KPI Definition
        href: /playbooks/define-kpi
    stories:
      - slug: define-kpi
        required: true

deliverables:
  - id: kpi-sheet
    label: KPI sheet
    helpText: |
      Deliverable help
    helpLinks:
      - label: KPI Sheet Template
        href: /tools/kpi-sheet
    stories:
      - slug: define-kpi
        required: false
```
MD;

        $result = $parser->parse($body);

        $this->assertSame([], $result['errors']);
        $sprint = $result['sprints'][0];

        $task = $sprint['tasks'][0];
        $this->assertSame('read-guide', $task['id']);
        $this->assertSame('Read guide', $task['label']);
        $this->assertSame('First line', $task['helpText']);
        $this->assertSame([
            ['label' => 'KPI Definition', 'href' => '/playbooks/define-kpi'],
        ], $task['helpLinks']);
        $this->assertSame([
            ['slug' => 'define-kpi', 'required' => true],
        ], $task['stories']);
        $this->assertNull($task['demoCode']);

        $deliverable = $sprint['deliverables'][0];
        $this->assertSame('kpi-sheet', $deliverable['id']);
        $this->assertSame('KPI sheet', $deliverable['label']);
        $this->assertSame('Deliverable help', $deliverable['helpText']);
        $this->assertSame([
            ['label' => 'KPI Sheet Template', 'href' => '/tools/kpi-sheet'],
        ], $deliverable['helpLinks']);
        $this->assertSame([
            ['slug' => 'define-kpi', 'required' => false],
        ], $deliverable['stories']);
        $this->assertNull($deliverable['demoCode']);
    }

    public function test_parses_table_columns_on_task(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

tasks:
  - id: identify-stakeholders
    label: Identify stakeholders
    assigneeType: team
    assigneeId: null
    tableColumns: Name, Role, Influence, Interest, Owner
```
MD;

        $result = $parser->parse($body);
        $this->assertSame([], $result['errors']);
        $table = $result['sprints'][0]['tasks'][0]['table'] ?? null;
        $this->assertIsArray($table);
        $this->assertCount(5, $table['columns']);
        $this->assertSame('name', $table['columns'][0]['id']);
        $this->assertSame('Name', $table['columns'][0]['label']);
        $this->assertSame([], $table['rows']);
    }

    public function test_parses_planned_minutes_on_tasks_and_deliverables(): void
    {
        $parser = new SprintFenceParser;
        $body = <<<'MD'
```sprint
id: week-01
number: 1
title: Orientation
goal: Understand the mandate.

tasks:
  - id: align-management-expectations
    label: Align expectations
    assigneeType: person
    assigneeId: null
    plannedMinutes: 45
  - id: identify-stakeholders
    label: Identify stakeholders
    planned_minutes: 30

deliverables:
  - id: stakeholder-list
    label: Stakeholder list
    plannedMinutes: 120
```
MD;

        $result = $parser->parse($body);
        $this->assertSame([], $result['errors']);
        $sprint = $result['sprints'][0];
        $this->assertSame(45, $sprint['tasks'][0]['plannedMinutes']);
        $this->assertSame(30, $sprint['tasks'][1]['plannedMinutes']);
        $this->assertSame(120, $sprint['deliverables'][0]['plannedMinutes']);
    }
}
