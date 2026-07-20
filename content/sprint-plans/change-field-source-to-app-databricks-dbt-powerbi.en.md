---
type: sprint-plan
title: Change Request — New field from source to app — Databricks Lakehouse / dbt / Power BI landscape
slug: change-field-source-to-app-databricks-dbt-powerbi
description: Operational template: move a new field cleanly from source through model, DQ, PII, catalog/lineage into the app.
duration: 2
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operational Template Library
roadmap_track: data-reporting-databricks-dbt-powerbi
roadmap_track_title: Databricks Lakehouse / dbt / Power BI landscape
roadmap_option: CR new field source to app
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Databricks
  - dbt
  - Power BI
  - Operational
  - change-field-source-to-app
---

Operational template: move a new field cleanly from source through model, DQ, PII, catalog/lineage into the app. The estimates are guidance and should be checked at start against data volume, criticality and team availability.

```sprint
id: week-01
number: 1
title: Check field impact and governance
goal: Databricks Lakehouse/dbt/Power BI: Check field impact and governance.

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: field-impact
    label: Check field impact and governance
    plannedMinutes: 1740
    assigneeType: team
    assigneeId: null
    helpText: |
      Clarify business meaning, source, data type, PII/DSDR, DQ rules, affected models, reports and owners.
      Consider Databricks Lakehouse, Unity Catalog, Delta tables, jobs and dbt model boundaries.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: field-impact-artifact
    label: Field impact analysis
    plannedMinutes: 780
    dependsOn: field-impact
    helpText: |
      Create Field impact analysis with owner, decision, risk, result link and clear handover.

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
title: Move field through model, tests and app
goal: Databricks Lakehouse/dbt/Power BI: Move field through model, tests and app.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: field-implementation
    label: Move field through model, tests and app
    plannedMinutes: 1860
    assigneeType: team
    assigneeId: null
    helpText: |
      Implement field in source mapping, model, tests, catalog/lineage, app view and release evidence.
      Consider Databricks Lakehouse, Unity Catalog, Delta tables, jobs and dbt model boundaries.
      Watch for: approving small changes without PII, DQ, catalog and app impact checks.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Helps define PII classes, masking and access rules for the concrete field or app.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Use it to document source, model, field, KPI and app usage traceably.
deliverables:
  - id: field-implementation-artifact
    label: Field change release
    plannedMinutes: 780
    dependsOn: field-implementation
    helpText: |
      Create Field change release with owner, decision, risk, result link and clear handover.

fields:
  - id: notes-02
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

