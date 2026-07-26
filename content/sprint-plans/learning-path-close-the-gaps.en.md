---
type: sprint-plan
title: Learning path — Close the gaps (3 weeks)
slug: learning-path-close-the-gaps
description: Turn the close-the-gaps learning path into a short plan — missing pieces series, ownership capacity, operational gaps, next path.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 6
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Learning
  - Governance
  - Missing Pieces
---

Mirrors the Learning Path “Close the gaps”. Use the missing pieces as a diagnosis, then prioritize one deep dive.

```sprint
id: week-01
number: 1
title: Diagnose with missing pieces
goal: Walk the series and confirm foundations are in place before gap triage.

tasks:
  - id: gaps-series
    label: Walk the missing pieces series
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Skim the full series first — pick pressure points, do not try to fix everything.
    helpLinks:
      - label: Series — The Missing Pieces
        href: /playbooks/series/missing-pieces
        description: Diagnosis series for stalled governance.
      - label: Path — Governance foundations
        href: /learning-paths/governance-foundations
        description: Prerequisite shared language and roles.
  - id: gaps-score
    label: Score top three gaps
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Gap, Evidence, Impact, Owner, Priority
    helpText: |
      Rank by business impact and readiness — not by how interesting the topic is.

deliverables:
  - id: gaps-triage
    label: Gap triage board
    plannedMinutes: 60
    helpText: |
      Done when three prioritized gaps have owners and evidence links.

notes: true
```

```sprint
id: week-02
number: 2
title: Ownership and capacity
goal: Address the ownership missing piece and stewardship capacity constraints.

stories:
  - slug: missing-pieces-ownership-stewardship
    required: true
  - slug: stewardship-capacity
    required: false

tasks:
  - id: gaps-ownership
    label: Read ownership missing piece
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: missing-pieces-ownership-stewardship
        required: true
    helpText: |
      Name where roles exist on paper but capacity and escalation do not.
  - id: gaps-capacity
    label: Sketch stewardship capacity
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: stewardship-capacity
        required: false
    helpText: |
      Estimate realistic FTE share and scope cut for domain stewards.
    helpLinks:
      - label: Roles — Data Steward
        href: /roles/steward
        description: Steward decision rights and related stories.

deliverables:
  - id: gaps-capacity-note
    label: Capacity and ownership note
    plannedMinutes: 60
    helpText: |
      Done when at least one domain has named steward capacity and escalation path.

notes: true
```

```sprint
id: week-03
number: 3
title: Operational gaps and next path
goal: Review metadata, DQ, metrics, access, lifecycle — then choose one deep-dive path.

tasks:
  - id: gaps-ops-review
    label: Review operational missing pieces
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    stories:
      - slug: missing-pieces-metadata-catalog-lineage
        required: false
      - slug: missing-pieces-data-quality
        required: false
      - slug: missing-pieces-trusted-metrics
        required: false
      - slug: missing-pieces-policy-access-governance
        required: false
      - slug: missing-pieces-data-lifecycle-retirement
        required: false
    helpText: |
      Confirm or revise week-1 priorities with evidence from the operational chapters.
  - id: gaps-next-path
    label: Pick the next learning path
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Commit to one deep dive so learning converts into operating change.
    helpLinks:
      - label: Path — Metadata operating model
        href: /learning-paths/metadata-operating-model
        description: Catalog, lineage, and metadata product ops.
      - label: Path — DQ with dbt
        href: /learning-paths/dq-with-dbt
        description: DQ as operating discipline with dbt.
      - label: Path — Trusted metrics
        href: /learning-paths/trusted-metrics
        description: KPI contracts and metric ops.

deliverables:
  - id: gaps-action-plan
    label: Gap action plan
    plannedMinutes: 60
    helpText: |
      Done when next path, owner, and first 30-day outcome are written down.

notes: true
```
