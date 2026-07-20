---
type: sprint-plan
title: Build new app on existing model with model filter — Snowflake / dbt / Qlik landscape
slug: new-app-existing-model-filter-snowflake-dbt-qlik
description: Operational template: build a new app on an existing data model including model filter, defaults, security and test matrix.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operational Template Library
roadmap_track: data-reporting-snowflake-dbt-qlik
roadmap_track_title: Snowflake / dbt / Qlik landscape
roadmap_option: New app with model filter
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Snowflake
  - dbt
  - Qlik
  - Operational
  - new-app-existing-model-filter
---

Operational template: build a new app on an existing data model including model filter, defaults, security and test matrix. The estimates are guidance and should be checked at start against data volume, criticality and team availability.

```sprint
id: week-01
number: 1
title: Cut filter logic, audience and risk
goal: Snowflake/dbt/Qlik: Cut filter logic, audience and risk.

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: filter-scope
    label: Cut filter logic, audience and risk
    plannedMinutes: 2160
    assigneeType: team
    assigneeId: null
    helpText: |
      Define filter purpose, required/default values, permissions, PII impact and performance risks.
      Consider Snowflake/dbt/Qlik, Qlik app, model, DQ, access and privacy together.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
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
goal: Snowflake/dbt/Qlik: Implement model filter and app behavior.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: implement-filter
    label: Implement model filter and app behavior
    plannedMinutes: 2460
    assigneeType: team
    assigneeId: null
    helpText: |
      Build filter logic in model/app layer, defaults, empty states, drilldowns and interactions.
      Consider Snowflake/dbt/Qlik, Qlik app, model, DQ, access and privacy together.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
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
goal: Snowflake/dbt/Qlik: Check filter test matrix and security.
dependsOn: week-02

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: filter-test-matrix
    label: Check filter test matrix and security
    plannedMinutes: 2340
    assigneeType: team
    assigneeId: null
    helpText: |
      Test combinations, roles, row-/column-level rules, exports, caching and performance.
      Consider Snowflake/dbt/Qlik, Qlik app, model, DQ, access and privacy together.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
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
goal: Snowflake/dbt/Qlik: Accept and publish filtered app.
dependsOn: week-03

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: release-filtered-app
    label: Accept and publish filtered app
    plannedMinutes: 2040
    assigneeType: team
    assigneeId: null
    helpText: |
      Validate business results, known limitations, help, monitoring and release decision.
      Consider Snowflake/dbt/Qlik, Qlik app, model, DQ, access and privacy together.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
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

