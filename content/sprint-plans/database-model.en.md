---
type: sprint-plan
title: Database model (4 weeks)
slug: database-model
description: Design a focused database / warehouse model: scope, entities, relationships, naming, and review.
duration: 4
unit: week
category: Data Modeling
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Modeling
  - Database
  - Warehouse
---

Four weeks to get from questions to an approved model v1.

```sprint
id: week-01
number: 1
title: Scope & questions
goal: Clarify what the model must answer.

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: choosing-the-simplest-viable-architecture
    required: false

tasks:
  - id: w1-scope
    label: Capture business questions and grain
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    helpText: |
      List decisions the model supports and the grain of each fact.
      Watch for: mixing operational and analytical grains.
    linkedStories: before-building-the-first-table
    helpLinks:
      - label: Before the first table
        href: /playbooks/before-building-the-first-table
  - id: w1-sources
    label: Inventory candidate sources
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Systems, owners, freshness, and known quality issues.
      Use meta export scripts where helpful.
    helpLinks:
      - label: Meta export generator
        href: /tools/meta-export-generator

deliverables:
  - id: w1-scope-note
    label: Scope & grain note
    plannedMinutes: 60
    helpText: |
      Questions, grain, and out-of-scope items.

notes: true
```

```sprint
id: week-02
number: 2
title: Entities & relationships
goal: Draft entities, keys, and relationships.

tasks:
  - id: w2-entities
    label: Draft entity list and keys
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    helpText: |
      Natural vs surrogate keys, uniqueness, and SCD needs.
      Prefer the simplest viable structure.
    linkedStories: beyond-bronze-silver-gold, choosing-the-simplest-viable-architecture
    helpLinks:
      - label: Beyond Bronze/Silver/Gold
        href: /playbooks/beyond-bronze-silver-gold
  - id: w2-rels
    label: Map relationships and cardinality
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    helpText: |
      Document 1:n / n:m and ownership of bridge tables.

deliverables:
  - id: w2-model-draft
    label: Entity-relationship draft
    plannedMinutes: 120
    helpText: |
      Diagram or table list with keys and cardinality.

notes: true
```

```sprint
id: week-03
number: 3
title: Naming & standards
goal: Apply naming, types, and conventions.

tasks:
  - id: w3-naming
    label: Apply naming conventions
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Tables, columns, and boolean/date patterns.
      Align with platform standards.
  - id: w3-types
    label: Confirm types and nullability
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    helpText: |
      Document defaults and required fields; note PII candidates.

deliverables:
  - id: w3-standards
    label: Naming & type standards applied
    plannedMinutes: 60
    helpText: |
      Checklist or PR notes showing conventions applied.

notes: true
```

```sprint
id: week-04
number: 4
title: Review & freeze
goal: Review with owners and freeze v1.

tasks:
  - id: w4-review
    label: Walkthrough with stakeholders
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Capture open questions and acceptance of v1 scope.
    linkedStories: data-ownership-stewardship
    helpLinks:
      - label: Ownership & Stewardship
        href: /playbooks/data-ownership-stewardship
  - id: w4-freeze
    label: Freeze model v1 and next steps
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Version the model; list follow-ups for physical implementation.

deliverables:
  - id: w4-model-v1
    label: Model v1 approved
    plannedMinutes: 120
    helpText: |
      Approved artifact + open follow-ups.

notes: true
```
