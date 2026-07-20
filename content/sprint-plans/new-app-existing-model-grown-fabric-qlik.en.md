---
type: sprint-plan
title: Build new app on existing model — Grown Fabric / QVD / Qlik landscape
slug: new-app-existing-model-grown-fabric-qlik
description: Operational template: build a new app on an existing data model without introducing a model filter.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operational Template Library
roadmap_track: data-reporting-grown-fabric-qvd-qlik
roadmap_track_title: Grown Fabric / QVD / Qlik landscape
roadmap_option: New app without model filter
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Fabric
  - Qlik
  - dbt
  - Operational
  - new-app-existing-model
---

Operational template: build a new app on an existing data model without introducing a model filter. The estimates are guidance and should be checked at start against data volume, criticality and team availability.

```sprint
id: week-01
number: 1
title: Define app scope and UX cut
goal: Fabric/Qlik: Define app scope and UX cut.

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app-scope
    label: Define app scope and UX cut
    plannedMinutes: 2100
    assigneeType: team
    assigneeId: null
    helpText: |
      Define audience, core questions, pages, KPIs, navigation logic and acceptance.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Generates Fabric-specific DQ rules for required fields, business keys, freshness, and release gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: app-scope-artifact
    label: App scope
    plannedMinutes: 900
    dependsOn: app-scope
    helpText: |
      Create App scope with owner, decision, risk, result link and clear handover.

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
title: Build app views and interactions
goal: Fabric/Qlik: Build app views and interactions.
dependsOn: week-01

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: build-app
    label: Build app views and interactions
    plannedMinutes: 2280
    assigneeType: team
    assigneeId: null
    helpText: |
      Build views, BI-tool filters, navigation, KPIs, detail paths and load/refresh behavior.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Generates Fabric-specific DQ rules for required fields, business keys, freshness, and release gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: build-app-artifact
    label: App slice
    plannedMinutes: 960
    dependsOn: build-app
    helpText: |
      Create App slice with owner, decision, risk, result link and clear handover.

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
title: Validate, secure and publish
goal: Fabric/Qlik: Validate, secure and publish.
dependsOn: week-02

stories:
  - slug: dq-test-kpis
    required: true
  - slug: metadata-catalog-lineage
    required: false
  - slug: operating-and-governing-the-platform
    required: false

tasks:
  - id: validate-release
    label: Validate, secure and publish
    plannedMinutes: 2040
    assigneeType: team
    assigneeId: null
    helpText: |
      Check figures, permissions, performance, exports, help and release decision.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: dq-test-kpis, metadata-catalog-lineage, operating-and-governing-the-platform
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Generates Fabric-specific DQ rules for required fields, business keys, freshness, and release gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: validate-release-artifact
    label: App release evidence
    plannedMinutes: 840
    dependsOn: validate-release
    helpText: |
      Create App release evidence with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-03
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

