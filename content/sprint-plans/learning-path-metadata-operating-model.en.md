---
type: sprint-plan
title: Learning path — Metadata operating model (3 weeks)
slug: learning-path-metadata-operating-model
description: Turn the metadata operating model learning path into a short plan — language, deep dive series, governance metadata, hub handoff.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 5
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Learning
  - Metadata
  - Catalog
  - Lineage
---

Mirrors the Learning Path “Metadata operating model”. Treat metadata as an operating lever, not a side project.

```sprint
id: week-01
number: 1
title: Terms and mandate
goal: Shared language for metadata, catalog, and lineage — plus a clear product mandate.

stories:
  - slug: what-metadata-actually-is
    required: true

tasks:
  - id: md-terms
    label: Align metadata terms
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Term, Working definition, Owner, Open question
    helpText: |
      Agree what metadata, catalog, and lineage mean before tool shortlists.
    helpLinks:
      - label: Glossary — Metadata
        href: /glossary/metadata
        description: Shared metadata vocabulary.
      - label: Glossary — Data Catalog
        href: /glossary/data-catalog
        description: Catalog as discovery and control surface.
      - label: Glossary — Lineage
        href: /glossary/lineage
        description: Lineage as impact and trust path.
  - id: md-story
    label: Read what metadata actually is
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: what-metadata-actually-is
        required: true
    helpText: |
      Capture which metadata classes matter for your stack in the next 90 days.

deliverables:
  - id: md-mandate-note
    label: Metadata mandate note
    plannedMinutes: 60
    helpText: |
      Done when scope, owner, and first success signal are named.

notes: true
```

```sprint
id: week-02
number: 2
title: Deep dive and product ops
goal: Walk the MetaData deep dive series and decide how metadata is operated as a product.

tasks:
  - id: md-series
    label: Walk MetaData deep dive series
    plannedMinutes: 210
    assigneeType: person
    assigneeId: null
    helpText: |
      Focus on birth, harvest, model, quality, and automation chapters that match your maturity.
    helpLinks:
      - label: Series — MetaData Deep Dive
        href: /playbooks/series/metadata-deep-dive
        description: Continuous series for metadata practice.
  - id: md-product
    label: Read operate-as-product chapter
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: operate-metadata-as-a-product
        required: true
    helpText: |
      Decide SLOs, intake, and ownership for the metadata product itself.

deliverables:
  - id: md-ops-sketch
    label: Metadata ops sketch
    plannedMinutes: 90
    helpText: |
      Done when product owner, cadence, and first SLO draft exist.

notes: true
```

```sprint
id: week-03
number: 3
title: Steer and hand off
goal: Connect governance-steering metadata and pick the next learning path.

tasks:
  - id: md-steer
    label: Review governance-steering patterns
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: governance-metadata-that-controls-data
        required: false
      - slug: metadata-driven-governance-with-dbt-meta
        required: false
    helpText: |
      Prefer patterns that control policy and dbt meta — not only describe assets.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Capture decision context.
  - id: md-next-path
    label: Choose next path
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Close gaps or continue into PII once the operating model draft is stable.
    helpLinks:
      - label: Path — Close the gaps
        href: /learning-paths/close-the-gaps
        description: Diagnose remaining operating gaps.
      - label: Path — PII in 5 steps
        href: /learning-paths/pii-in-five-steps
        description: Shortest solid privacy entry.

deliverables:
  - id: md-handoff
    label: Metadata handoff checklist
    plannedMinutes: 45
    helpText: |
      Done when next path, owner, and hub decision link are recorded.

notes: true
```
