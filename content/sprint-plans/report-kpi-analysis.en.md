---
type: sprint-plan
title: Report / analysis with KPIs (4 weeks)
slug: report-kpi-analysis
description: From decision questions to an accepted report: KPIs, sources, pilot, and handoff.
duration: 4
unit: week
category: Reporting
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Reporting
  - KPI
  - Analysis
---

Four weeks to ship a decision-ready report or analysis.

```sprint
id: week-01
number: 1
title: Questions & audience
goal: Clarify decisions and audience.

stories:
  - slug: define-kpi
    required: true
  - slug: kpi-metric-governance
    required: false

tasks:
  - id: w1-questions
    label: Write decision questions
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      What decision changes with this report? Who acts on it?
      Avoid vanity metrics.
    linkedStories: define-kpi, kpi-metric-governance
    helpLinks:
      - label: Report Inventory Canvas
        href: /tools/report-inventory
      - label: KPI Definition Card
        href: /tools/kpi-definition
      - label: Define KPI
        href: /playbooks/define-kpi
      - label: KPI governance
        href: /playbooks/kpi-metric-governance
  - id: w1-audience
    label: Map consumers and cadence
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Who needs it, how often, and in which tool.

deliverables:
  - id: w1-brief
    label: Analysis brief
    plannedMinutes: 120
    helpText: |
      Questions, audience, cadence, success criteria.

notes: true
```

```sprint
id: week-02
number: 2
title: KPI set
goal: Define KPIs and definitions.

tasks:
  - id: w2-kpis
    label: Select core KPIs
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Name, formula, grain, owner, and refresh.
      Keep the set small.
    linkedStories: define-kpi
    helpLinks:
      - label: KPI Definition Card
        href: /tools/kpi-definition
      - label: Define KPI
        href: /playbooks/define-kpi
  - id: w2-defs
    label: Document metric definitions
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Shared language for numerator/denominator and filters.

deliverables:
  - id: w2-kpi-catalog
    label: KPI catalog draft
    plannedMinutes: 120
    helpText: |
      Table of KPIs with formulas and owners.

notes: true
```

```sprint
id: week-03
number: 3
title: Sources & mock
goal: Connect sources and mock the report.

tasks:
  - id: w3-sources
    label: Validate source fields
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Confirm availability and quality of inputs.
      Meta export can help inventory columns.
    helpLinks:
      - label: Meta export generator
        href: /tools/meta-export-generator
      - label: BI tools
        href: /playbooks/bi-tools
  - id: w3-mock
    label: Build mock / pilot view
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    helpText: |
      Wireframe or thin pilot for stakeholder feedback.

deliverables:
  - id: w3-pilot
    label: Pilot view or mock
    plannedMinutes: 60
    helpText: |
      Link or screenshot of pilot + feedback notes.

notes: true
```

```sprint
id: week-04
number: 4
title: Acceptance
goal: Accept the report and hand off.

tasks:
  - id: w4-accept
    label: Run acceptance checklist
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    helpText: |
      Correctness, performance, access, and ownership.
    linkedStories: bi-tools
    helpLinks:
      - label: BI tools
        href: /playbooks/bi-tools
  - id: w4-handoff
    label: Handoff runbook
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      How to refresh, who owns incidents, known limits.

deliverables:
  - id: w4-accepted
    label: Report accepted
    plannedMinutes: 120
    helpText: |
      Signed checklist + runbook link.

notes: true
```
