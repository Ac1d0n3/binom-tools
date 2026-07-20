---
type: sprint-plan
title: Create new app data model — Grown Fabric / QVD / Qlik landscape
slug: app-data-model-grown-fabric-qlik
description: Operational template: turn a new app idea into a robust data model with sources, grain, KPIs, PII/DSDR, DQ, lineage and handover to app build.
duration: 6
unit: week
recommended_people_min: 2
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operational Template Library
roadmap_track: data-reporting-grown-fabric-qvd-qlik
roadmap_track_title: Grown Fabric / QVD / Qlik landscape
roadmap_option: New app data model
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Fabric
  - Qlik
  - dbt
  - Operational
  - app-data-model
---

Operational template: turn a new app idea into a robust data model with sources, grain, KPIs, PII/DSDR, DQ, lineage and handover to app build. The estimates are guidance and should be checked at start against data volume, criticality and team availability.

```sprint
id: week-01
number: 1
title: Define app goal, grain and KPI cut
goal: Fabric/Qlik: Define app goal, grain and KPI cut.

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: scope-model
    label: Define app goal, grain and KPI cut
    plannedMinutes: 2460
    assigneeType: team
    assigneeId: null
    helpText: |
      Clarify audience, core questions, grain, KPI definitions, non-goals, owners and acceptance criteria.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Pattern Generator
        href: /tools/fabric-dq-pattern-generator
        description: Generates Fabric-specific SQL and notebook patterns for DQ rules, Delta loads, SCD2 and pipeline gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: scope-model-artifact
    label: Model scope
    plannedMinutes: 1020
    dependsOn: scope-model
    helpText: |
      Create Model scope with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-01
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-02
number: 2
title: Check sources, PII and DSDR
goal: Fabric/Qlik: Check sources, PII and DSDR.
dependsOn: week-01

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: source-privacy-check
    label: Check sources, PII and DSDR
    plannedMinutes: 2580
    assigneeType: team
    assigneeId: null
    helpText: |
      Check sources, keys, personal data, DSDR relevance, access and known DQ issues before model build.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Pattern Generator
        href: /tools/fabric-dq-pattern-generator
        description: Generates Fabric-specific SQL and notebook patterns for DQ rules, Delta loads, SCD2 and pipeline gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: source-privacy-check-artifact
    label: Source and privacy check
    plannedMinutes: 1140
    dependsOn: source-privacy-check
    helpText: |
      Create Source and privacy check with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-02
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-03
number: 3
title: Design facts, dimensions and history
goal: Fabric/Qlik: Design facts, dimensions and history.
dependsOn: week-02

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: design-model
    label: Design facts, dimensions and history
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Design fact, dimensions, history, delta strategy, SCD types and business exceptions.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Pattern Generator
        href: /tools/fabric-dq-pattern-generator
        description: Generates Fabric-specific SQL and notebook patterns for DQ rules, Delta loads, SCD2 and pipeline gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: design-model-artifact
    label: Model design
    plannedMinutes: 1140
    dependsOn: design-model
    helpText: |
      Create Model design with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-03
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-04
number: 4
title: Build staging and business model
goal: Fabric/Qlik: Build staging and business model.
dependsOn: week-03

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: build-model
    label: Build staging and business model
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Build staging, business models, central KPIs and technical checks using the landscape pattern.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Pattern Generator
        href: /tools/fabric-dq-pattern-generator
        description: Generates Fabric-specific SQL and notebook patterns for DQ rules, Delta loads, SCD2 and pipeline gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: build-model-artifact
    label: Implemented app model
    plannedMinutes: 1140
    dependsOn: build-model
    helpText: |
      Create Implemented app model with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-04
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-05
number: 5
title: Add DQ, catalog and lineage
goal: Fabric/Qlik: Add DQ, catalog and lineage.
dependsOn: week-04

stories:
  - slug: dq-test-kpis
    required: true
  - slug: metadata-catalog-lineage
    required: false
  - slug: operating-and-governing-the-platform
    required: false

tasks:
  - id: dq-lineage-catalog
    label: Add DQ, catalog and lineage
    plannedMinutes: 2520
    assigneeType: team
    assigneeId: null
    helpText: |
      Add DQ rules, KPI docs, owners, lineage, PII classes and known limitations.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: dq-test-kpis, metadata-catalog-lineage, operating-and-governing-the-platform
    helpLinks:
      - label: Fabric DQ Pattern Generator
        href: /tools/fabric-dq-pattern-generator
        description: Generates Fabric-specific SQL and notebook patterns for DQ rules, Delta loads, SCD2 and pipeline gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: dq-lineage-catalog-artifact
    label: DQ and catalog package
    plannedMinutes: 1080
    dependsOn: dq-lineage-catalog
    helpText: |
      Create DQ and catalog package with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-05
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-06
number: 6
title: Validate model and hand over to app build
goal: Fabric/Qlik: Validate model and hand over to app build.
dependsOn: week-05

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: handover-app-build
    label: Validate model and hand over to app build
    plannedMinutes: 2280
    assigneeType: team
    assigneeId: null
    helpText: |
      Validate figures, performance, access and handover to app build including open risks.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Pattern Generator
        href: /tools/fabric-dq-pattern-generator
        description: Generates Fabric-specific SQL and notebook patterns for DQ rules, Delta loads, SCD2 and pipeline gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: handover-app-build-artifact
    label: App build handover
    plannedMinutes: 960
    dependsOn: handover-app-build
    helpText: |
      Create App build handover with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-06
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

