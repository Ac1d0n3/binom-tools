---
type: sprint-plan
title: Second Quarter — Option 1 Fabric Lakehouse / Qlik
slug: data-reporting-q2-fabric-lakehouse-qlik
description: Q2 option 1 for the grown Fabric/QVD/Qlik landscape: extract app logic from existing Qlik/QVD scripts, reshape it in Fabric Lakehouse/Warehouse, and connect first app items.
duration: 13
unit: week
recommended_people_min: 2
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: data-reporting-year-roadmap
roadmap_title: Data & Reporting Year Roadmap
roadmap_track: data-reporting-grown-fabric-qvd-qlik
roadmap_track_title: Grown Fabric / QVD / Qlik landscape
roadmap_phase: 2
roadmap_option: Path 3 Option 1 Fabric Lakehouse / Qlik
roadmap_follows:
  - data-reporting-fq-fabric-qlik-qvd
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Q2
  - Fabric
  - Lakehouse
  - Qlik
  - Roadmap
---

Thirteen weeks of Q2 implementation with two people: extract existing app logic from existing Qlik apps, QVD generators, SUBs, DELTAs, and SCD logic, build a reliable star schema with delta loads and slowly changing dimensions, and deliver the first usable connection into the Qlik app.

```sprint
id: week-01
number: 1
title: Extract existing app logic
goal: Fabric Lakehouse/Warehouse and Qlik: Extract existing app logic.

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: extract-existing-logic
    label: Extract existing app logic
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Capture current logic, metrics, filters, and special scripts from existing Qlik apps, QVD generators, SUBs, DELTAs, and SCD logic. The goal is not copying everything, but separating business logic from tool code.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: business-logic-inventory
    label: Logic inventory
    plannedMinutes: 1080
    dependsOn: extract-existing-logic
    helpText: |
      Artifact for Logic inventory: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-01
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-02
number: 2
title: Slice star schema and app scope
goal: Fabric Lakehouse/Warehouse and Qlik: Slice star schema and app scope.
dependsOn: week-01

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: define-star-scope
    label: Slice star schema and app scope
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Define fact, dimensions, grain, key metrics, and non-goals for the first A-Z slice.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: star-schema-scope
    label: Star schema scope
    plannedMinutes: 1200
    dependsOn: define-star-scope
    helpText: |
      Artifact for Star schema scope: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-02
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-03
number: 3
title: Map sources to target model
goal: Fabric Lakehouse/Warehouse and Qlik: Map sources to target model.
dependsOn: week-02

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: map-source-to-target
    label: Map sources to target model
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Map sources, keys, history, delta fields, and business owners to the target model.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: source-target-map
    label: Source-to-target mapping
    plannedMinutes: 1140
    dependsOn: map-source-to-target
    helpText: |
      Artifact for Source-to-target mapping: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-03
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-04
number: 4
title: Design delta loads
goal: Fabric Lakehouse/Warehouse and Qlik: Design delta loads.
dependsOn: week-03

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: design-delta-loads
    label: Design delta loads
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Define incremental strategy, watermarks, reprocessing, failure modes, and reload logic.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
    helpLinks:
      - 
      - label: Delta Load / SCD Pattern Generator
        href: /tools/delta-load-scd-pattern-generator
        description: Generates Delta MERGE and SCD2 patterns for incremental lakehouse models.
      - label: dbt Docs - Incremental models
        href: https://docs.getdbt.com/docs/build/incremental-models
        description: Use the docs as a reference for incremental models, watermarks, and reprocessing decisions.
deliverables:
  - id: delta-load-design
    label: Delta load design
    plannedMinutes: 1200
    dependsOn: design-delta-loads
    helpText: |
      Artifact for Delta load design: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-04
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-05
number: 5
title: Design slowly changing dimensions
goal: Fabric Lakehouse/Warehouse and Qlik: Design slowly changing dimensions.
dependsOn: week-04

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: design-scd
    label: Design slowly changing dimensions
    plannedMinutes: 3120
    assigneeType: team
    assigneeId: null
    helpText: |
      Define SCD types, validity, surrogate keys, history, and business exceptions per dimension.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
    helpLinks:
      - 
      - label: Delta Load / SCD Pattern Generator
        href: /tools/delta-load-scd-pattern-generator
        description: Generates Delta MERGE and SCD2 patterns for incremental lakehouse models.
      - label: Kimball Group - Slowly Changing Dimensions
        href: https://www.kimballgroup.com/2008/09/slowly-changing-dimensions-part-2/
        description: Use the article as a reference for SCD types, history handling, and business trade-offs.
deliverables:
  - id: scd-design
    label: SCD design
    plannedMinutes: 1200
    dependsOn: design-scd
    helpText: |
      Artifact for SCD design: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-05
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-06
number: 6
title: Build staging and base models
goal: Fabric Lakehouse/Warehouse and Qlik: Build staging and base models.
dependsOn: week-05

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: build-staging
    label: Build staging and base models
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Normalize raw data, standardize technical fields, and create reproducible base models.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: staging-models
    label: Staging models
    plannedMinutes: 1200
    dependsOn: build-staging
    helpText: |
      Artifact for Staging models: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-06
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-07
number: 7
title: Build star schema
goal: Fabric Lakehouse/Warehouse and Qlik: Build star schema.
dependsOn: week-06

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: build-star-model
    label: Build star schema
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Implement fact and dimension models, move business rules out of app scripts, and make joins/grain testable.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: star-model
    label: Star schema model
    plannedMinutes: 1140
    dependsOn: build-star-model
    helpText: |
      Artifact for Star schema model: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-07
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-08
number: 8
title: Implement DQ and reconciliation tests
goal: Fabric Lakehouse/Warehouse and Qlik: Implement DQ and reconciliation tests.
dependsOn: week-07

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: implement-quality-tests
    label: Implement DQ and reconciliation tests
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Implement not-null, unique, relationships, accepted values, KPI sanity, and legacy reconciliation for critical values.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: quality-tests
    label: DQ test package
    plannedMinutes: 1140
    dependsOn: implement-quality-tests
    helpText: |
      Artifact for DQ test package: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-08
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-09
number: 9
title: Validate against old app values
goal: Fabric Lakehouse/Warehouse and Qlik: Validate against old app values.
dependsOn: week-08

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: validate-against-old-app
    label: Validate against old app values
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Compare new model values against existing app/QVD/report values and decide deviations with business owners.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: validation-evidence
    label: Validation evidence
    plannedMinutes: 1140
    dependsOn: validate-against-old-app
    helpText: |
      Artifact for Validation evidence: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-09
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-10
number: 10
title: Connect report app
goal: Fabric Lakehouse/Warehouse and Qlik: Connect report app.
dependsOn: week-09

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: connect-report-app
    label: Connect report app
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Load the new model into the first Qlik app, wiring data connection, semantics, and permissions for the pilot scope.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
    helpLinks:
      - 
      - label: Delta Load / SCD Pattern Generator
        href: /tools/delta-load-scd-pattern-generator
        description: Generates Delta MERGE and SCD2 patterns for incremental lakehouse models.
      - label: Qlik app
        href: https://help.qlik.com/
        description: Use the docs as a reference for app connection, semantics, and first visuals.
deliverables:
  - id: app-connection
    label: App connection
    plannedMinutes: 1080
    dependsOn: connect-report-app
    helpText: |
      Artifact for App connection: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-10
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-11
number: 11
title: Create first app items
goal: Fabric Lakehouse/Warehouse and Qlik: Create first app items.
dependsOn: week-10

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: create-first-app-items
    label: Create first app items
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Create first sheets/visuals/measures for the most important questions and keep them limited to the Q2 scope.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: first-app-items
    label: First app items
    plannedMinutes: 1080
    dependsOn: create-first-app-items
    helpText: |
      Artifact for First app items: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-11
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-12
number: 12
title: Harden operations, runbook, and ownership
goal: Fabric Lakehouse/Warehouse and Qlik: Harden operations, runbook, and ownership.
dependsOn: week-11

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: harden-runbook
    label: Harden operations, runbook, and ownership
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Document jobs, monitoring, DQ response, owners, release path, and known limits.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: runbook
    label: Runbook and ownership
    plannedMinutes: 1080
    dependsOn: harden-runbook
    helpText: |
      Artifact for Runbook and ownership: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-12
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```

```sprint
id: week-13
number: 13
title: Close Q2 and slice Q3 backlog
goal: Fabric Lakehouse/Warehouse and Qlik: Close Q2 and slice Q3 backlog.
dependsOn: week-12

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: q2-close-q3-backlog
    label: Close Q2 and slice Q3 backlog
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Capture outcome, open risks, reusable patterns, and next app candidates for Q3.
      Watch that existing app logic is understood functionally before it is rebuilt technically.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: q3-backlog
    label: Q3 follow-up backlog
    plannedMinutes: 1020
    dependsOn: q2-close-q3-backlog
    helpText: |
      Artifact for Q3 follow-up backlog: include owner, date, decision, open risks, and link to the result.

fields:
  - id: q2-notes-13
    label: Notes and decisions
    type: textarea
    placeholder: Decisions, open items, risks

notes: true
```
