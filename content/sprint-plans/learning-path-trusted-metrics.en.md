---
type: sprint-plan
title: Learning path — Trusted metrics (3 weeks)
slug: learning-path-trusted-metrics
description: Turn the trusted metrics learning path into a short plan — KPI language, contracts, missing piece, hub handoff.
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
  - KPI
  - Metrics
  - Stewardship
---

Mirrors the Learning Path “Trusted metrics”. Definitions without ownership and change process stay slideware.

```sprint
id: week-01
number: 1
title: KPI language
goal: Align definition, owner, and decision rights for metrics.

stories:
  - slug: kpi-metric-governance
    required: true

tasks:
  - id: kpi-terms
    label: Align KPI governance terms
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Term, Working definition, Owner, Open question
    helpText: |
      Start with KPI governance vocabulary before writing formulas in tools.
    helpLinks:
      - label: Glossary — KPI Governance
        href: /glossary/kpi-governance
        description: Shared metric operating language.
  - id: kpi-story
    label: Read KPI metric governance
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: kpi-metric-governance
        required: true
    helpText: |
      Capture who can change a metric and how conflicts are escalated.

deliverables:
  - id: kpi-rights-note
    label: Metric decision-rights note
    plannedMinutes: 45
    helpText: |
      Done when accountable owner and change path are named.

notes: true
```

```sprint
id: week-02
number: 2
title: Write the contract
goal: Produce a first KPI contract with grain, formula, and steward.

stories:
  - slug: define-kpi
    required: true

tasks:
  - id: kpi-define-story
    label: Read define-KPI practice
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: define-kpi
        required: true
    helpText: |
      Use the story as the contract checklist for steward × architect collaboration.
  - id: kpi-tools
    label: Draft with KPI tools
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    tableColumns: KPI, Grain, Formula, Steward, Status
    helpText: |
      Treat tool output as a draft contract, then review with the accountable owner.
    helpLinks:
      - label: KPI Definition
        href: /tools/kpi-definition
        description: Structured KPI contract draft.
      - label: KPI Requirements Intake
        href: /tools/kpi-requirements-intake
        description: Intake before definition work.

deliverables:
  - id: kpi-contract-draft
    label: First KPI contract draft
    plannedMinutes: 90
    helpText: |
      Done when one priority KPI has grain, formula, steward, and open questions.

notes: true
```

```sprint
id: week-03
number: 3
title: Operate and hand off
goal: Cover the trusted-metrics missing piece and link into roles / delivery.

tasks:
  - id: kpi-missing
    label: Read trusted-metrics missing piece
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: missing-pieces-trusted-metrics
        required: true
    helpText: |
      Name what still fails after definitions exist — cadence, evidence, escalation.
  - id: kpi-handoff
    label: Hand off to hub and delivery
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Link decision rights in Roles and schedule follow-up delivery work.
    helpLinks:
      - label: Roles Hub
        href: /roles
        description: Decision rights for steward and owner.
      - label: Keep business logic outside BI
        href: /playbooks/keeping-business-logic-outside-bi-apps
        description: Decide where metrics logic lives before formula tools.
      - label: Power BI DAX Generator
        href: /tools/powerbi-dax-generator
        description: Formula helper after grain and owner are clear.
      - label: Sprint Planner
        href: /sprint-planner
        description: Continue with delivery templates.
      - label: Governance Hub
        href: /governance
        description: Capture the operating decision.

deliverables:
  - id: kpi-ops-checklist
    label: Trusted metrics checklist
    plannedMinutes: 45
    helpText: |
      Done when contract draft, owner, cadence, and next sprint link exist.

notes: true
```
