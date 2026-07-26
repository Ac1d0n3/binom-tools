---
type: sprint-plan
title: Learning path — Simplest viable stack (3 weeks)
slug: learning-path-simplest-viable-stack
description: Turn the simplest viable stack learning path into a short plan — design goal, fit checks, modernization handoff.
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
  - Architecture
  - Stack
---

Mirrors the Learning Path “Simplest viable stack”. Choose the simplest solid architecture before layer slogans take over.

```sprint
id: week-01
number: 1
title: Simplicity as a design goal
goal: Agree what “viable” means before vendor and layer debates.

stories:
  - slug: choosing-the-simplest-viable-architecture
    required: true

tasks:
  - id: stack-simple
    label: Read simplest viable architecture
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: choosing-the-simplest-viable-architecture
        required: true
    helpText: |
      Capture constraints, non-goals, and the smallest stack that still works.
  - id: stack-layers
    label: Challenge bronze/silver/gold defaults
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    stories:
      - slug: beyond-bronze-silver-gold
        required: false
    helpText: |
      Keep only layers that earn their keep for the current problem.

deliverables:
  - id: stack-constraints
    label: Stack constraints note
    plannedMinutes: 45
    helpText: |
      Done when constraints, non-goals, and candidate stack are written.

notes: true
```

```sprint
id: week-02
number: 2
title: Check fit
goal: Validate the candidate stack with Architecture Fit and advisors.

tasks:
  - id: stack-fit
    label: Run architecture fit
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Pressure-test the candidate against real constraints.
    helpLinks:
      - label: Architecture Fit
        href: /tools/architecture-fit
        description: Fit check for architecture choices.
  - id: stack-advisor
    label: Capture stack advisor / decision brief
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Record the decision with enough context for sponsors.
    helpLinks:
      - label: Stack Advisor
        href: /tools/governance-stack-advisor
        description: Guided stack decision aid.
      - label: Decision Brief
        href: /tools/decision-brief-generator
        description: Short decision record.

deliverables:
  - id: stack-decision
    label: Stack decision draft
    plannedMinutes: 60
    helpText: |
      Done when recommended stack, risks, and open questions are listed.

notes: true
```

```sprint
id: week-03
number: 3
title: Move into modernization
goal: Hand off into the warehouse modernization path.

tasks:
  - id: stack-handoff
    label: Start warehouse modernization path
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Use the decision as the starting brief for grain and products.
    helpLinks:
      - label: Path — Modernize the warehouse
        href: /learning-paths/modernize-warehouse
        description: Grain, products, and warehouse series.
      - label: Governance Hub
        href: /governance
        description: Keep the decision visible.

deliverables:
  - id: stack-handoff-checklist
    label: Stack handoff checklist
    plannedMinutes: 45
    helpText: |
      Done when decision brief and next path are linked.

notes: true
```
