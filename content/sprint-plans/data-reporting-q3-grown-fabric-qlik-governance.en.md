---
type: sprint-plan
title: Third Quarter — Grown Fabric / QVD / Qlik Governance & App Factory
slug: data-reporting-q3-grown-fabric-qlik-governance
description: Q3 follow-up for the grown Fabric/QVD/Qlik landscape: make both Q2 follow-up paths governable, embed PII and DSDR controls, publish catalog/lineage and prepare the app factory for further apps.
duration: 13
unit: week
recommended_people_min: 2
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: data-reporting-year-roadmap
roadmap_title: Data & Reporting Year Roadmap
roadmap_track: data-reporting-grown-fabric-qvd-qlik
roadmap_track_title: Grown Fabric / QVD / Qlik landscape
roadmap_phase: 3
roadmap_option: null
roadmap_follows:
  - data-reporting-q2-fabric-lakehouse-qlik
  - data-reporting-q2-dbt-qvd-qlik
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Q3
  - Fabric
  - Qlik
  - dbt
  - PII
  - DSDR
  - Roadmap
---

Thirteen Q3 weeks with two people: stabilize the grown Fabric/QVD/Qlik path after Q2. Whether Q2 used Fabric Lakehouse or Fabric+dbt to replace Qlik scripts, Q3 embeds PII, DSDR, access, lineage, operations and app factory as a durable pattern.

```sprint
id: week-01
number: 1
title: Take over Q2 results and close production gaps
goal: Fabric/Qlik: Take over Q2 results and close production gaps.

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: q2-handover-production-gaps
    label: Take over Q2 results and close production gaps
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Review the Q2 outcome, operating risks, test gaps, privacy points and missing owners. The goal is a realistic production-readiness backlog, not a polished closing report.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, access-security-governance
deliverables:
  - id: q2-handover-production-gaps-artifact
    label: Production gap register
    plannedMinutes: 1080
    dependsOn: q2-handover-production-gaps
    helpText: |
      Create the artifact for Production gap register with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-01
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-02
number: 2
title: Classify PII and sensitivity
goal: Fabric/Qlik: Classify PII and sensitivity.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: eight-pillars
    required: false

tasks:
  - id: pii-classification
    label: Classify PII and sensitivity
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Classify relevant fields, tables, models and app outputs by PII, sensitivity, purpose limitation and export/display risk.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: pii-privacy-governance, access-security-governance, eight-pillars
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Use the tool to prepare PII classes, masking, roles and access rules as a concrete policy.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Internal guide for PII classification, protection needs and privacy decisions.
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars
        description: Use the DSDR pillar to avoid treating deletion and evidence paths as a single DELETE statement.
deliverables:
  - id: pii-classification-artifact
    label: PII classification matrix
    plannedMinutes: 1140
    dependsOn: pii-classification
    helpText: |
      Create the artifact for PII classification matrix with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-02
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-03
number: 3
title: Design DSDR and subject-right paths
goal: Fabric/Qlik: Design DSDR and subject-right paths.
dependsOn: week-02

stories:
  - slug: eight-pillars
    required: true
  - slug: metadata-catalog-lineage
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: dsdr-paths
    label: Design DSDR and subject-right paths
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Trace where personal data for a data subject can occur in models, aggregates, exports and app layers. Treat deletion, blocking, correction and access separately.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: eight-pillars, metadata-catalog-lineage, access-security-governance
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Use the tool to prepare PII classes, masking, roles and access rules as a concrete policy.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Internal guide for PII classification, protection needs and privacy decisions.
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars
        description: Use the DSDR pillar to avoid treating deletion and evidence paths as a single DELETE statement.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Helps make sources, models, KPIs, owners and DSDR-relevant dependencies visible.
deliverables:
  - id: dsdr-paths-artifact
    label: DSDR path and responsibility matrix
    plannedMinutes: 1200
    dependsOn: dsdr-paths
    helpText: |
      Create the artifact for DSDR path and responsibility matrix with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-03
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-04
number: 4
title: Implement access, masking and role model
goal: Fabric/Qlik: Implement access, masking and role model.
dependsOn: week-03

stories:
  - slug: access-security-governance
    required: true
  - slug: pii-privacy-governance
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: access-masking
    label: Implement access, masking and role model
    plannedMinutes: 3120
    assigneeType: team
    assigneeId: null
    helpText: |
      Define roles, masking, row-/column-level access, admin exceptions and review process so business users can work and sensitive data stays protected.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: access-security-governance, pii-privacy-governance, data-ownership-stewardship
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Use the tool to prepare PII classes, masking, roles and access rules as a concrete policy.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Internal guide for PII classification, protection needs and privacy decisions.
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars
        description: Use the DSDR pillar to avoid treating deletion and evidence paths as a single DELETE statement.
deliverables:
  - id: access-masking-artifact
    label: Access and masking model
    plannedMinutes: 1200
    dependsOn: access-masking
    helpText: |
      Create the artifact for Access and masking model with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-04
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-05
number: 5
title: Harden privacy and export controls in the app
goal: Fabric/Qlik: Harden privacy and export controls in the app.
dependsOn: week-04

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: eight-pillars
    required: false

tasks:
  - id: app-privacy-controls
    label: Harden privacy and export controls in the app
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Review and secure the report app for personal fields, detail views, drilldowns, exports, screenshots, downloads and permission gaps.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: pii-privacy-governance, access-security-governance, eight-pillars
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Use the tool to prepare PII classes, masking, roles and access rules as a concrete policy.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Internal guide for PII classification, protection needs and privacy decisions.
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars
        description: Use the DSDR pillar to avoid treating deletion and evidence paths as a single DELETE statement.
deliverables:
  - id: app-privacy-controls-artifact
    label: App privacy sign-off
    plannedMinutes: 1200
    dependsOn: app-privacy-controls
    helpText: |
      Create the artifact for App privacy sign-off with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-05
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-06
number: 6
title: Make monitoring, alerting and runbook production-ready
goal: Fabric/Qlik: Make monitoring, alerting and runbook production-ready.
dependsOn: week-05

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: monitoring-runbook
    label: Make monitoring, alerting and runbook production-ready
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Make load failures, freshness, volume, runtime, DQ breaches, missing sources and app outages measurable with clear response paths.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, access-security-governance
deliverables:
  - id: monitoring-runbook-artifact
    label: Production runbook
    plannedMinutes: 1140
    dependsOn: monitoring-runbook
    helpText: |
      Create the artifact for Production runbook with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-06
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-07
number: 7
title: Publish catalog, KPI registry and lineage
goal: Fabric/Qlik: Publish catalog, KPI registry and lineage.
dependsOn: week-06

stories:
  - slug: metadata-catalog-lineage
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: define-kpi
    required: false

tasks:
  - id: catalog-lineage
    label: Publish catalog, KPI registry and lineage
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Document sources, models, KPIs, owners, DQ rules and app usage so business, operations and privacy use the same view.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: metadata-catalog-lineage, data-ownership-stewardship, define-kpi
    helpLinks:
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Helps make sources, models, KPIs, owners and DSDR-relevant dependencies visible.
deliverables:
  - id: catalog-lineage-artifact
    label: Catalog and lineage package
    plannedMinutes: 1140
    dependsOn: catalog-lineage
    helpText: |
      Create the artifact for Catalog and lineage package with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-07
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-08
number: 8
title: Automate DQ gates and release process
goal: Fabric/Qlik: Automate DQ gates and release process.
dependsOn: week-07

stories:
  - slug: dq-test-kpis
    required: true
  - slug: access-security-governance
    required: false
  - slug: operating-and-governing-the-platform
    required: false

tasks:
  - id: dq-release-gates
    label: Automate DQ gates and release process
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Define DQ checks, review steps, deployment approvals and fallback paths as a repeatable release process for model and app.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: dq-test-kpis, access-security-governance, operating-and-governing-the-platform
    helpLinks:
      - label: dbt DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
        description: Use the tool to prepare reusable DQ checks for release gates.
deliverables:
  - id: dq-release-gates-artifact
    label: Release and DQ gate process
    plannedMinutes: 1200
    dependsOn: dq-release-gates
    helpText: |
      Create the artifact for Release and DQ gate process with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-08
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-09
number: 9
title: Business-sign off the first A-Z prototype
goal: Fabric/Qlik: Business-sign off the first A-Z prototype.
dependsOn: week-08

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: go-live-readiness
    label: Business-sign off the first A-Z prototype
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Decide business acceptance, open risks, known limitations, performance, permissions and operational readiness together.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, dq-test-kpis
deliverables:
  - id: go-live-readiness-artifact
    label: Go-live readiness decision
    plannedMinutes: 1140
    dependsOn: go-live-readiness
    helpText: |
      Create the artifact for Go-live readiness decision with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-09
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-10
number: 10
title: Shape the app factory template
goal: Fabric/Qlik: Shape the app factory template.
dependsOn: week-09

stories:
  - slug: building-from-scratch
    required: true
  - slug: keeping-business-logic-outside-bi-apps
    required: false
  - slug: one-data-product-multiple-consumers
    required: false

tasks:
  - id: app-factory-template
    label: Shape the app factory template
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Turn the first A-Z prototype into a reusable pattern for further apps: scope, model, DQ, security, help, runbook and sign-off.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: building-from-scratch, keeping-business-logic-outside-bi-apps, one-data-product-multiple-consumers
deliverables:
  - id: app-factory-template-artifact
    label: App factory template
    plannedMinutes: 1080
    dependsOn: app-factory-template
    helpText: |
      Create the artifact for App factory template with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-10
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-11
number: 11
title: Prepare the second app as repeatability slice
goal: Fabric/Qlik: Prepare the second app as repeatability slice.
dependsOn: week-10

stories:
  - slug: building-from-scratch
    required: true
  - slug: keeping-business-logic-outside-bi-apps
    required: false
  - slug: one-data-product-multiple-consumers
    required: false

tasks:
  - id: second-app-slice
    label: Prepare the second app as repeatability slice
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Cut the next app so it shows whether the pattern is truly reusable: sources, KPIs, PII, roles and migration risks.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: building-from-scratch, keeping-business-logic-outside-bi-apps, one-data-product-multiple-consumers
deliverables:
  - id: second-app-slice-artifact
    label: Second-app slice
    plannedMinutes: 1140
    dependsOn: second-app-slice
    helpText: |
      Create the artifact for Second-app slice with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-11
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-12
number: 12
title: Run training and handover
goal: Fabric/Qlik: Run training and handover.
dependsOn: week-11

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: training-handover
    label: Run training and handover
    plannedMinutes: 2760
    assigneeType: team
    assigneeId: null
    helpText: |
      Train owners, stewards, developers, support and business users on operations, privacy, DQ response, change path and app usage.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, access-security-governance
deliverables:
  - id: training-handover-artifact
    label: Handover and training evidence
    plannedMinutes: 1080
    dependsOn: training-handover
    helpText: |
      Create the artifact for Handover and training evidence with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-12
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```

```sprint
id: week-13
number: 13
title: Close Q3 and plan Q4 app rollout
goal: Fabric/Qlik: Close Q3 and plan Q4 app rollout.
dependsOn: week-12

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: q4-rollout-backlog
    label: Close Q3 and plan Q4 app rollout
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Prioritize the Q4 backlog for further apps, governance gaps, technical debt, team scaling and operational improvements.
      Cover both Q2 follow-up paths: Fabric Lakehouse/Qlik and Fabric+dbt as replacement for Qlik scripts. Check Qlik app, data model, operations and privacy perspective together for each path.
      Watch for: documenting governance without making it testable in the process.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, dq-test-kpis
deliverables:
  - id: q4-rollout-backlog-artifact
    label: Q4 rollout backlog
    plannedMinutes: 1020
    dependsOn: q4-rollout-backlog
    helpText: |
      Create the artifact for Q4 rollout backlog with owner, date, decision, open risk and result link. It must be reusable for operations, privacy reviews or the next app and cover both possible Q2 follow-up paths.

fields:
  - id: q3-notes-13
    label: Notes, decisions and open risks
    type: textarea
    placeholder: Decisions, open points, risks

notes: true
```
