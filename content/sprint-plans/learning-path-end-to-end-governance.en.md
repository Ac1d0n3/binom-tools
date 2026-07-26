---
type: sprint-plan
title: Learning path — End-to-end governance (3 weeks)
slug: learning-path-end-to-end-governance
description: Turn the end-to-end governance learning path into a short plan — chain picture, meta/runtime, PII along lineage.
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
  - Metadata
  - dbt
---

Mirrors the Learning Path “End-to-end governance”. One control chain from source to consumer.

```sprint
id: week-01
number: 1
title: Target picture of the chain
goal: Understand how governance steers from source to consumer.

stories:
  - slug: end-to-end-governance-architecture
    required: true

tasks:
  - id: e2e-series
    label: Walk end-to-end governance series
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Focus on the chapters that match your stack layers.
    helpLinks:
      - label: Series — End-to-End Data Governance
        href: /playbooks/series/end-to-end-data-governance
        description: Continuous E2E governance series.
  - id: e2e-architecture
    label: Read E2E architecture story
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: end-to-end-governance-architecture
        required: true
    helpText: |
      Sketch your current control points and missing links.

deliverables:
  - id: e2e-map
    label: Control-chain map
    plannedMinutes: 60
    helpText: |
      Done when source → transform → serve control points are named.

notes: true
```

```sprint
id: week-02
number: 2
title: Metadata steers runtime
goal: Connect dbt meta and automation into operating practice.

tasks:
  - id: e2e-meta
    label: Review dbt meta governance
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: metadata-driven-governance-with-dbt-meta
        required: true
    helpText: |
      Decide which policies should be driven from metadata, not tickets.
  - id: e2e-raw
    label: Review raw-generation automation
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: automatic-raw-generation-using-dbt-macros
        required: false
    helpText: |
      Identify one automation candidate that reduces manual drift.
    helpLinks:
      - label: Path — Metadata operating model
        href: /learning-paths/metadata-operating-model
        description: Operating model for catalog and lineage.

deliverables:
  - id: e2e-runtime-note
    label: Runtime steering note
    plannedMinutes: 60
    helpText: |
      Done when one meta-driven control and owner are agreed.

notes: true
```

```sprint
id: week-03
number: 3
title: PII along the pipeline
goal: Follow classification and masking along lineage, then hand off.

tasks:
  - id: e2e-pii
    label: Review PII metadata propagation
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: propagating-pii-metadata-across-data-warehouses
        required: false
    helpText: |
      Trace one sensitive field from source to consumer.
  - id: e2e-handoff
    label: Hand off to PII path and hub
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Continue with PII readiness when classification gaps remain.
    helpLinks:
      - label: Path — PII in 5 steps
        href: /learning-paths/pii-in-five-steps
        description: Shortest solid privacy entry.
      - label: Governance Hub
        href: /governance
        description: Capture the operating decision.

deliverables:
  - id: e2e-handoff-checklist
    label: E2E handoff checklist
    plannedMinutes: 45
    helpText: |
      Done when control map, runtime note, and next path are linked.

notes: true
```
