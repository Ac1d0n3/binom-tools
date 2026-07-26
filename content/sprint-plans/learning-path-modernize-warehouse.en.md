---
type: sprint-plan
title: Learning path — Modernize the warehouse (3 weeks)
slug: learning-path-modernize-warehouse
description: Turn the warehouse modernization learning path into a short plan — grain, series, suppliers, end-to-end governance.
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
  - Warehouse
  - Architecture
---

Mirrors the Learning Path “Modernize the warehouse”. Clarify grain and products before stack debates.

```sprint
id: week-01
number: 1
title: Target picture and grain
goal: Clarify data product and grain before stack debates begin.

tasks:
  - id: wh-grain
    label: Define product and grain hypotheses
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    tableColumns: Product, Grain, Consumer, Open risk
    helpText: |
      Write the working grain and product boundary first. Architecture debates come after.
    helpLinks:
      - label: Glossary — Data Product
        href: /glossary/data-product
        description: Product boundary language.
      - label: Glossary — Grain
        href: /glossary/grain
        description: Grain as the modeling contract.
  - id: wh-architect-role
    label: Align architect vs custodian roles
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Confirm who owns model consistency vs runtime custody for the modernization scope.
    helpLinks:
      - label: Roles — Data Architect
        href: /roles/architect
        description: Grain, contracts, consistency.
      - label: Roles — Data Custodian
        href: /roles/custodian
        description: Technical custody of systems.

deliverables:
  - id: wh-grain-note
    label: Grain and product note
    plannedMinutes: 60
    helpText: |
      Done when product, grain, and open risks fit on one page.

notes: true
```

```sprint
id: week-02
number: 2
title: Series and supplier patterns
goal: Walk the modern warehouse series and reuse supplier patterns.

tasks:
  - id: wh-series
    label: Read modern data warehouse series
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    helpText: |
      Focus on layers, marts, and operating model chapters that match your current stack.
    helpLinks:
      - label: Series — Building a Modern Data Warehouse
        href: /playbooks/series/building-modern-data-warehouse
        description: Ten-part warehouse series.
  - id: wh-suppliers
    label: Pull supplier patterns
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Source, Core dims, PII hint, Owner
    helpText: |
      Reuse core dimensions and PII hints from the Supplier Library instead of inventing them.
    helpLinks:
      - label: Supplier Library
        href: /suppliers
        description: Source patterns and PII hints.
      - label: Stack Advisor
        href: /tools/governance-stack-advisor
        description: Target stack shortlist.

deliverables:
  - id: wh-pattern-pack
    label: Source pattern pack
    plannedMinutes: 90
    helpText: |
      Done when at least one priority source has dims/PII notes linked.

notes: true
```

```sprint
id: week-03
number: 3
title: Attach end-to-end governance
goal: Warehouse without ownership and metadata stays an expensive filesystem.

tasks:
  - id: wh-e2e
    label: Attach end-to-end governance series
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Connect ownership, metadata, and operating practice to the warehouse target picture.
    helpLinks:
      - label: Series — End-to-End Data Governance
        href: /playbooks/series/end-to-end-data-governance
        description: Governance attachment for the warehouse.
      - label: Governance Hub
        href: /governance
        description: Capture decision and next actions.
  - id: wh-next
    label: Decide next delivery template
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Promote modeling work into a delivery template (e.g. database-model) after the learning weeks.
    helpLinks:
      - label: Sprint Planner templates
        href: /sprint-planner/templates
        description: Pick the next delivery plan.

deliverables:
  - id: wh-decision-brief
    label: Modernization decision brief
    plannedMinutes: 60
    helpText: |
      Done when target picture, gaps, owners, and next sprint link are written.

notes: true
```
