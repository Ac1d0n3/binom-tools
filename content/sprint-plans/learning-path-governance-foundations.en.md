---
type: sprint-plan
title: Learning path — Governance foundations (3 weeks)
slug: learning-path-governance-foundations
description: Turn the governance foundations learning path into a short plan — language, roles series, pillars, hub practice.
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
  - Roles
  - RACI
---

Mirrors the Learning Path “Governance foundations”. Pillars and roles before certificates and frameworks.

```sprint
id: week-01
number: 1
title: Shared language and roles
goal: Owner, steward, architect, catalog, and RACI as the entry set — then walk the roles series.

stories:
  - slug: raci-for-data-governance
    required: true
  - slug: data-architect-role
    required: false

tasks:
  - id: gf-glossary
    label: Align core role terms
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Term, Working definition, Open question
    helpText: |
      Start with owner, steward, architect, and RACI before organizational charts.
    helpLinks:
      - label: Glossary — Data Owner
        href: /glossary/data-owner
        description: Accountable business role.
      - label: Glossary — Data Steward
        href: /glossary/data-steward
        description: Operational quality role.
      - label: Glossary — Data Architect
        href: /glossary/data-architect
        description: Grain and contracts.
      - label: Roles Hub
        href: /roles
        description: Persona cards with stories and tools.
  - id: gf-roles-series
    label: Walk roles and decision rights series
    plannedMinutes: 210
    assigneeType: person
    assigneeId: null
    helpText: |
      Cover architect, RACI, product owner vs owner, stewardship capacity, and CoE at skim depth first.
    stories:
      - slug: raci-for-data-governance
        required: true
      - slug: data-product-owner-vs-data-owner
        required: false
      - slug: stewardship-capacity
        required: false
      - slug: governance-coe
        required: false
    helpLinks:
      - label: Series — Roles and Decision Rights
        href: /playbooks/series/roles-hub
        description: Roles Hub story series.

deliverables:
  - id: gf-role-map
    label: Working role map
    plannedMinutes: 90
    helpText: |
      Done when each persona has a named person or an explicit vacancy.

notes: true
```

```sprint
id: week-02
number: 2
title: Eight pillars and operating practice
goal: Build the mental model behind ownership, metadata, PII, DQ, and lifecycle.

stories:
  - slug: eight-pillars
    required: true
  - slug: data-ownership-stewardship
    required: true

tasks:
  - id: gf-pillars
    label: Read eight pillars overview
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    stories:
      - slug: eight-pillars
        required: true
    helpLinks:
      - label: Series — Governance pillars
        href: /playbooks/series/governance-pillars
        description: Eight pillars series.
  - id: gf-ownership
    label: Read ownership and stewardship
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: data-ownership-stewardship
        required: true
    helpText: |
      Capture decision rights that apply to your domain, not a generic org chart.
  - id: gf-raci-tool
    label: Draft stakeholder / RACI matrix
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Working draft of decision rights.

deliverables:
  - id: gf-pillars-notes
    label: Pillars + RACI draft
    plannedMinutes: 90
    helpText: |
      Done when pillars map and a first RACI draft are linked.

notes: true
```

```sprint
id: week-03
number: 3
title: Hub practice and deep dive choice
goal: Drive decisions in the hub, watch the radar, choose the next learning path.

tasks:
  - id: gf-hub
    label: Run a governance hub session
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Capture scenario, stack, and open decisions so the team can reopen them.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Advisor and session save.
      - label: Radar
        href: /governance/radar
        description: Curated governance news.
      - label: Compliance
        href: /compliance
        description: Place compliance obligations.
  - id: gf-deep-dive
    label: Choose next deep dive
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Option, Why now, Owner, Start link
    helpText: |
      Pick metadata deep dive, missing pieces, or the PII path depending on maturity.
    helpLinks:
      - label: Series — MetaData Deep Dive
        href: /playbooks/series/metadata-deep-dive
        description: Metadata operating depth.
      - label: Series — Missing Pieces
        href: /playbooks/series/missing-pieces
        description: What usually stays unfinished.
      - label: Path — PII in 5 steps
        href: /learning-paths/pii-in-five-steps
        description: Next short privacy journey.

deliverables:
  - id: gf-next-path
    label: Next path decision
    plannedMinutes: 45
    helpText: |
      Done when the next path or series has an owner and a start date.

notes: true
```
