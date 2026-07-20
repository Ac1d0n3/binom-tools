---
type: sprint-plan
title: Third Quarter Parallel — Databricks Lakehouse / dbt / Power BI App Factory & Next Apps
slug: data-reporting-q3-parallel-app-factory-databricks-dbt-powerbi
description: Optional Q3 parallel plan for a second team: use the Databricks Lakehouse/dbt/Power BI pattern to build the next apps. Without a second team, this plan can be used as the Q4 follow-up plan.
duration: 13
unit: week
recommended_people_min: 2
recommended_people_max: 3
capacity_hours_per_person_week: 40
roadmap_family: data-reporting-year-roadmap
roadmap_title: Data & Reporting Year Roadmap
roadmap_track: data-reporting-databricks-dbt-powerbi
roadmap_track_title: Databricks Lakehouse / dbt / Power BI landscape
roadmap_phase: 3
roadmap_option: Parallel plan App Factory & next apps
roadmap_follows:
  - data-reporting-q2-databricks-dbt-powerbi
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Q3
  - Parallel
  - Databricks Lakehouse
  - dbt
  - Power BI
  - App-Factory
  - Roadmap
---

This plan is intentionally a parallel plan: if a second team exists, it can build the next apps in Q3 while the first team hardens governance, PII, DSDR and production readiness. Without a second team, the same plan is a useful Q4 follow-up plan.

```sprint
id: week-01
number: 1
title: Prioritize next app candidates
goal: Databricks Lakehouse/dbt/Power BI: Prioritize next app candidates.

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: choose-next-apps
    label: Prioritize next app candidates
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Rate app candidates by value, data availability, risk, reusability and proximity to the first A-Z prototype. The goal is a realistic team slice, not a wishlist.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: choose-next-apps-artifact
    label: Prioritized app candidates
    plannedMinutes: 1080
    dependsOn: choose-next-apps
    helpText: |
      Create Prioritized app candidates so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-01
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-02
number: 2
title: Cut App 2 scope, KPIs and owners
goal: Databricks Lakehouse/dbt/Power BI: Cut App 2 scope, KPIs and owners.
dependsOn: week-01

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app2-scope-kpis
    label: Cut App 2 scope, KPIs and owners
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Define audience, core questions, KPIs, business owners, non-goals and acceptance criteria for the second app. Keep the scope small enough for a clean slice.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: app2-scope-kpis-artifact
    label: App 2 scope and KPI cut
    plannedMinutes: 1140
    dependsOn: app2-scope-kpis
    helpText: |
      Create App 2 scope and KPI cut so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-02
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-03
number: 3
title: Check App 2 sources, PII and DSDR risks
goal: Databricks Lakehouse/dbt/Power BI: Check App 2 sources, PII and DSDR risks.
dependsOn: week-02

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: eight-pillars
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: app2-sources-pii-dsdr
    label: Check App 2 sources, PII and DSDR risks
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Check sources, personal fields, DSDR relevance, access needs, data quality and known process breaks before model build.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: pii-privacy-governance, eight-pillars, access-security-governance
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Use the tool to prepare PII classes, masking and access rules directly for the next app.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Helps handle personal data, purpose limitation, protection needs and review decisions cleanly.
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars
        description: Use PII, DSDR, access and lifecycle especially as mandatory gates for new apps.
deliverables:
  - id: app2-sources-pii-dsdr-artifact
    label: App 2 source and privacy check
    plannedMinutes: 1200
    dependsOn: app2-sources-pii-dsdr
    helpText: |
      Create App 2 source and privacy check so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-03
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-04
number: 4
title: Apply the app factory model pattern
goal: Databricks Lakehouse/dbt/Power BI: Apply the app factory model pattern.
dependsOn: week-03

stories:
  - slug: building-from-scratch
    required: true
  - slug: keeping-business-logic-outside-bi-apps
    required: false
  - slug: one-data-product-multiple-consumers
    required: false

tasks:
  - id: reuse-factory-model-pattern
    label: Apply the app factory model pattern
    plannedMinutes: 3120
    assigneeType: team
    assigneeId: null
    helpText: |
      Reuse the proven pattern from the first prototype: staging, business model, KPI definition, naming, tests, documentation and responsibility.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, keeping-business-logic-outside-bi-apps, one-data-product-multiple-consumers
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
      - label: Keeping business logic outside BI apps
        href: /playbooks/keeping-business-logic-outside-bi-apps
        description: Helps avoid rebuilding hidden logic inside Qlik/Power BI for new apps.
deliverables:
  - id: reuse-factory-model-pattern-artifact
    label: Reused model pattern
    plannedMinutes: 1200
    dependsOn: reuse-factory-model-pattern
    helpText: |
      Create Reused model pattern so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-04
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-05
number: 5
title: Build App 2 loads and model
goal: Databricks Lakehouse/dbt/Power BI: Build App 2 loads and model.
dependsOn: week-04

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: build-app2-loads-model
    label: Build App 2 loads and model
    plannedMinutes: 3120
    assigneeType: team
    assigneeId: null
    helpText: |
      Build delta/incremental loads, history, central dimensions/facts and technical checks for App 2.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
deliverables:
  - id: build-app2-loads-model-artifact
    label: App 2 data model
    plannedMinutes: 1200
    dependsOn: build-app2-loads-model
    helpText: |
      Create App 2 data model so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-05
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-06
number: 6
title: Implement App 2 DQ and reconciliation
goal: Databricks Lakehouse/dbt/Power BI: Implement App 2 DQ and reconciliation.
dependsOn: week-05

stories:
  - slug: dq-test-kpis
    required: true
  - slug: operating-and-governing-the-platform
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: app2-dq-reconciliation
    label: Implement App 2 DQ and reconciliation
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Implement DQ rules, volume checks, KPI comparisons, freshness checks and business reconciliation against existing values.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: dq-test-kpis, operating-and-governing-the-platform, metadata-catalog-lineage
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
deliverables:
  - id: app2-dq-reconciliation-artifact
    label: App 2 DQ package
    plannedMinutes: 1200
    dependsOn: app2-dq-reconciliation
    helpText: |
      Create App 2 DQ package so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-06
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-07
number: 7
title: Connect App 2 and build first views
goal: Databricks Lakehouse/dbt/Power BI: Connect App 2 and build first views.
dependsOn: week-06

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: connect-app2-report
    label: Connect App 2 and build first views
    plannedMinutes: 3120
    assigneeType: team
    assigneeId: null
    helpText: |
      Connect the model to the report app and build the most important views, filters, drilldowns and navigation points.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: connect-app2-report-artifact
    label: App 2 report slice
    plannedMinutes: 1200
    dependsOn: connect-app2-report
    helpText: |
      Create App 2 report slice so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-07
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-08
number: 8
title: Validate and sign off App 2
goal: Databricks Lakehouse/dbt/Power BI: Validate and sign off App 2.
dependsOn: week-07

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app2-acceptance
    label: Validate and sign off App 2
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Validate KPIs, performance, permissions, export behavior and business acceptance. Decide which limitations consciously remain.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: app2-acceptance-artifact
    label: App 2 acceptance
    plannedMinutes: 1200
    dependsOn: app2-acceptance
    helpText: |
      Create App 2 acceptance so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-08
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-09
number: 9
title: Cut App 3 as a small repeatability slice
goal: Databricks Lakehouse/dbt/Power BI: Cut App 3 as a small repeatability slice.
dependsOn: week-08

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app3-scope-slice
    label: Cut App 3 as a small repeatability slice
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Cut a third app or a clear part of it to test whether the app factory is truly repeatable.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: app3-scope-slice-artifact
    label: App 3 slice
    plannedMinutes: 1140
    dependsOn: app3-scope-slice
    helpText: |
      Create App 3 slice so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-09
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-10
number: 10
title: Prepare App 3 foundation
goal: Databricks Lakehouse/dbt/Power BI: Prepare App 3 foundation.
dependsOn: week-09

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app3-foundation
    label: Prepare App 3 foundation
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Set up sources, model skeleton, PII/DSDR check, baseline DQ rules and first app structure for App 3.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: app3-foundation-artifact
    label: App 3 foundation
    plannedMinutes: 1080
    dependsOn: app3-foundation
    helpText: |
      Create App 3 foundation so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-10
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-11
number: 11
title: Synchronize with Q3 governance team
goal: Databricks Lakehouse/dbt/Power BI: Synchronize with Q3 governance team.
dependsOn: week-10

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: metadata-catalog-lineage
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: align-with-governance-team
    label: Synchronize with Q3 governance team
    plannedMinutes: 2760
    assigneeType: team
    assigneeId: null
    helpText: |
      Align PII, DSDR, access, catalog, lineage, release gates and runbook with the governance plan. Parallel does not mean disconnected.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: pii-privacy-governance, metadata-catalog-lineage, access-security-governance
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Use the tool to prepare PII classes, masking and access rules directly for the next app.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Helps handle personal data, purpose limitation, protection needs and review decisions cleanly.
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars
        description: Use PII, DSDR, access and lifecycle especially as mandatory gates for new apps.
deliverables:
  - id: align-with-governance-team-artifact
    label: Governance sync notes
    plannedMinutes: 1080
    dependsOn: align-with-governance-team
    helpText: |
      Create Governance sync notes so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-11
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-12
number: 12
title: Package app factory assets
goal: Databricks Lakehouse/dbt/Power BI: Package app factory assets.
dependsOn: week-11

stories:
  - slug: building-from-scratch
    required: true
  - slug: keeping-business-logic-outside-bi-apps
    required: false
  - slug: one-data-product-multiple-consumers
    required: false

tasks:
  - id: package-factory-assets
    label: Package app factory assets
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Bundle templates, checklists, sample models, DQ rules, help text, runbook and acceptance forms.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, keeping-business-logic-outside-bi-apps, one-data-product-multiple-consumers
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
      - label: Keeping business logic outside BI apps
        href: /playbooks/keeping-business-logic-outside-bi-apps
        description: Helps avoid rebuilding hidden logic inside Qlik/Power BI for new apps.
deliverables:
  - id: package-factory-assets-artifact
    label: App factory package
    plannedMinutes: 1020
    dependsOn: package-factory-assets
    helpText: |
      Create App factory package so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-12
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

```sprint
id: week-13
number: 13
title: Cut Q4 scaling plan
goal: Databricks Lakehouse/dbt/Power BI: Cut Q4 scaling plan.
dependsOn: week-12

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: q4-scale-plan
    label: Cut Q4 scaling plan
    plannedMinutes: 2580
    assigneeType: team
    assigneeId: null
    helpText: |
      Plan which apps come next, which team can build them, which governance gates are mandatory and which technical debt must be removed first.
      This plan may run in parallel with the Q3 governance plan; actively align with the governance team on PII, DSDR, access, catalog and release gates.
      Include Power BI app, data model, DQ, access and privacy perspective together.
      Watch for: buying speed by skipping governance gates.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Generates Databricks-specific DLT, SQL and notebook patterns for DQ rules, Delta MERGE, SCD2 and Unity Catalog gates.
      - label: Keeping business logic outside BI apps
        href: /playbooks/keeping-business-logic-outside-bi-apps
        description: Helps avoid rebuilding hidden logic inside Qlik/Power BI for new apps.
deliverables:
  - id: q4-scale-plan-artifact
    label: Q4 app rollout plan
    plannedMinutes: 1020
    dependsOn: q4-scale-plan
    helpText: |
      Create Q4 app rollout plan so the governance team can review it and reuse it for later apps. Capture owner, decision, open risks and result link.

fields:
  - id: q3-parallel-notes-13
    label: Notes, decisions and handovers
    type: textarea
    placeholder: Decisions, open points, alignment with governance team

notes: true
```

