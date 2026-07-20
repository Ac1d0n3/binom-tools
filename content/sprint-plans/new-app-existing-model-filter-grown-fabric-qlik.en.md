---
type: sprint-plan
title: Build new app on existing model with model filter — Grown Fabric / QVD / Qlik landscape
slug: new-app-existing-model-filter-grown-fabric-qlik
description: Operational template: build a new app on an existing data model including model filter, defaults, security and test matrix.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operational Template Library
roadmap_track: data-reporting-grown-fabric-qvd-qlik
roadmap_track_title: Grown Fabric / QVD / Qlik landscape
roadmap_option: New app with model filter
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Fabric
  - Qlik
  - dbt
  - Operational
  - new-app-existing-model-filter
---

Operational template: build a new app on an existing data model including model filter, defaults, security and test matrix. The estimates are guidance and should be checked at start against data volume, criticality and team availability.

```sprint
id: week-01
number: 1
title: Cut filter logic, audience and risk
goal: Fabric/Qlik: Cut filter logic, audience and risk.

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false
  - slug: python-bi-export-setup
    required: false

tasks:
  - id: filter-scope
    label: Cut filter logic, audience and risk
    plannedMinutes: 2160
    assigneeType: team
    assigneeId: null
    helpText: |
      Define filter purpose, required/default values, permissions, PII impact and performance risks.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage, python-bi-export-setup
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Generates Fabric-specific DQ rules for required fields, business keys, freshness, and release gates.
      - label: Fabric PII Governance Pattern Generator
        href: /tools/fabric-pii-governance-pattern-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
      - label: BI Python Export Toolkit
        href: /tools/bi-python-toolkit
        description: Python downloads for Qlik app/sheet inventory and KPI formula exports.
deliverables:
  - id: filter-scope-artifact
    label: Filter scope
    plannedMinutes: 960
    dependsOn: filter-scope
    helpText: |
      Create Filter scope with owner, decision, risk, result link and clear handover.

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
title: Implement model filter and app behavior
goal: Fabric/Qlik: Implement model filter and app behavior.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false
  - slug: python-bi-export-setup
    required: false

tasks:
  - id: implement-filter
    label: Implement model filter and app behavior
    plannedMinutes: 2460
    assigneeType: team
    assigneeId: null
    helpText: |
      Build filter logic in model/app layer, defaults, empty states, drilldowns and interactions.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage, python-bi-export-setup
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Generates Fabric-specific DQ rules for required fields, business keys, freshness, and release gates.
      - label: Fabric PII Governance Pattern Generator
        href: /tools/fabric-pii-governance-pattern-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
      - label: BI Python Export Toolkit
        href: /tools/bi-python-toolkit
        description: Python downloads for Qlik app/sheet inventory and KPI formula exports.
deliverables:
  - id: implement-filter-artifact
    label: Filter implementation
    plannedMinutes: 1020
    dependsOn: implement-filter
    helpText: |
      Create Filter implementation with owner, decision, risk, result link and clear handover.

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
title: Check filter test matrix and security
goal: Fabric/Qlik: Check filter test matrix and security.
dependsOn: week-02

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false
  - slug: python-bi-export-setup
    required: false

tasks:
  - id: filter-test-matrix
    label: Check filter test matrix and security
    plannedMinutes: 2340
    assigneeType: team
    assigneeId: null
    helpText: |
      Test combinations, roles, row-/column-level rules, exports, caching and performance.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage, python-bi-export-setup
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Generates Fabric-specific DQ rules for required fields, business keys, freshness, and release gates.
      - label: Fabric PII Governance Pattern Generator
        href: /tools/fabric-pii-governance-pattern-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
      - label: BI Python Export Toolkit
        href: /tools/bi-python-toolkit
        description: Python downloads for Qlik app/sheet inventory and KPI formula exports.
deliverables:
  - id: filter-test-matrix-artifact
    label: Filter test matrix
    plannedMinutes: 1020
    dependsOn: filter-test-matrix
    helpText: |
      Create Filter test matrix with owner, decision, risk, result link and clear handover.

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
title: Accept and publish filtered app
goal: Fabric/Qlik: Accept and publish filtered app.
dependsOn: week-03

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false
  - slug: python-bi-export-setup
    required: false

tasks:
  - id: release-filtered-app
    label: Accept and publish filtered app
    plannedMinutes: 2040
    assigneeType: team
    assigneeId: null
    helpText: |
      Validate business results, known limitations, help, monitoring and release decision.
      Consider both existing Qlik/QVD logic and the modernized Fabric/Fabric+dbt path.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage, python-bi-export-setup
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Generates Fabric-specific DQ rules for required fields, business keys, freshness, and release gates.
      - label: Fabric PII Governance Pattern Generator
        href: /tools/fabric-pii-governance-pattern-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
      - label: BI Python Export Toolkit
        href: /tools/bi-python-toolkit
        description: Python downloads for Qlik app/sheet inventory and KPI formula exports.
deliverables:
  - id: release-filtered-app-artifact
    label: Filtered app release
    plannedMinutes: 840
    dependsOn: release-filtered-app
    helpText: |
      Create Filtered app release with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-04
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

