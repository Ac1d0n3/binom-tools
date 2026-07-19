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
}
