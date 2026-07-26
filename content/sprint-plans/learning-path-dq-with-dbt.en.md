---
type: sprint-plan
title: Learning path — DQ with dbt (3 weeks)
slug: learning-path-dq-with-dbt
description: Turn the DQ with dbt learning path into a short plan — ownership language, operational series, generators, ops handoff.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 4
capacity_hours_per_person_week: 8
category: Learning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Learning
  - Data Quality
  - dbt
---

Mirrors the Learning Path “DQ with dbt”. Treat data quality as an operating discipline, not a one-off audit.

```sprint
id: week-01
number: 1
title: DQ language and ownership
goal: Fitness for purpose, steward, and contract before the first test.

stories:
  - slug: data-quality-governance
    required: true

tasks:
  - id: dq-terms
    label: Align DQ and contract terms
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Term, Working definition, Steward, Open question
    helpText: |
      Agree what “fit for purpose” means for the domain before generating macros or rules.
    helpLinks:
      - label: Glossary — Data Quality
        href: /glossary/data-quality
        description: Shared DQ vocabulary.
      - label: Glossary — Data Contract
        href: /glossary/data-contract
        description: Contract as operating interface.
  - id: dq-story
    label: Read DQ governance story
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: data-quality-governance
        required: true
    helpText: |
      Capture ownership and gate expectations for your stack.

deliverables:
  - id: dq-ownership-note
    label: DQ ownership note
    plannedMinutes: 60
    helpText: |
      Done when steward, contract owner, and first gate criteria are named.

notes: true
```

```sprint
id: week-02
number: 2
title: Operational series and generators
goal: Walk the operational DQ series and produce first dbt artifacts.

tasks:
  - id: dq-series
    label: Walk operational DQ series
    plannedMinutes: 210
    assigneeType: person
    assigneeId: null
    helpText: |
      Use the series for KPIs, platform patterns, and remediation — pick the chapters that match your layer.
    helpLinks:
      - label: Series — Operational Data Quality
        href: /playbooks/series/operational-data-quality
        description: Continuous series for operational DQ.
  - id: dq-generators
    label: Generate macros, rules, history drafts
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Artifact, Scope, Owner, Status
    helpText: |
      Treat generator output as copy-paste starting points, then review with the steward.
    helpLinks:
      - label: dbt DQ Macros
        href: /tools/dbt-dq-macro-generator
        description: Macro starting points.
      - label: dbt DQ Rules
        href: /tools/dbt-dq-rules-generator
        description: Rule starting points.
      - label: dbt DQ History
        href: /tools/dbt-dq-history-generator
        description: History / tracking starting points.

deliverables:
  - id: dq-draft-pack
    label: Draft DQ artifact pack
    plannedMinutes: 90
    helpText: |
      Done when at least one macro/rule/history draft is linked and reviewed once.

notes: true
```

```sprint
id: week-03
number: 3
title: Move into operations
goal: Keep DQ from stalling in the pipeline — advisor session and operating cadence.

tasks:
  - id: dq-ops-handoff
    label: Define operating cadence
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Gate, Owner, Cadence, Escalation
    helpText: |
      Name who watches failures, who remediates, and when the gate blocks a release.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Capture the decision context.
      - label: Roles — Data Steward
        href: /roles/steward
        description: Steward decision rights.
  - id: dq-plan-followup
    label: Schedule follow-up sprint work
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Promote remaining work into delivery sprints (e.g. change-tests) so learning does not stop at drafts.
    helpLinks:
      - label: Sprint Planner
        href: /sprint-planner
        description: Continue into delivery templates.

deliverables:
  - id: dq-ops-checklist
    label: DQ ops checklist
    plannedMinutes: 60
    helpText: |
      Done when gates, owners, and the next delivery plan link are written down.

notes: true
```
