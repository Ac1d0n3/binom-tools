---
type: sprint-plan
title: Governance Finance Mart implementation (4 weeks)
slug: governance-finance-mart-implementation
description: Turn governance discovery, KPI cards, source scope, DQ rules, and PII review into an actionable finance mart plan.
duration: 4
unit: week
recommended_people_min: 2
recommended_people_max: 5
capacity_hours_per_person_week: 24
category: Governance
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Governance
  - KPI
  - Data Quality
  - PII
  - Finance
---

Four weeks for a clean governance implementation plan: review session and scope first, define risks and data quality next, then close mart design and decision brief as a change-ready foundation.

```sprint
id: week-01
number: 1
title: Review session and decision
goal: Turn discovery output, decision, and open questions into a reliable working state.

stories:
  - slug: end-to-end-governance-architecture
    required: true
  - slug: define-kpi
    required: true

tasks:
  - id: review-governance-session
    label: Review governance session and report
    plannedMinutes: 240
    assigneeType: team
    assigneeId: null
    tableColumns: Topic, State, Owner, Decision, Open question
    helpText: |
      Review the saved Governance Session as a decision foundation, not as a text archive. Do goal, scenario, stack, supplier, KPI set, and risks fit together?
      Turn gaps into explicit review questions with owner and due date.
    helpLinks:
      - label: Governance Demo Workspace
        href: /governance/demo-workspace
        description: Shows a filled working state with main plan, learning plan, KPI cards, tool outputs, and report.
      - label: Decision Brief Generator
        href: /tools/decision-brief-generator
        description: Condenses context, recommendation, risks, open questions, and next tasks.
  - id: align-kpi-cards
    label: Finalize KPI cards
    plannedMinutes: 300
    assigneeType: team
    assigneeId: null
    tableColumns: KPI, Formula, Grain, Owner, Acceptance example, DQ candidate
    helpText: |
      Every KPI needs a business question, formula, grain, owner, and example. Without an example, the definition is not testable yet.
      Move open definition questions directly into the plan instead of hiding them in report text.
    helpLinks:
      - label: KPI Requirements Intake
        href: /tools/kpi-requirements-intake
        description: Captures KPI requirements as a reviewable KPI card.

deliverables:
  - id: decision-ready-session
    label: Decision-ready session state
    plannedMinutes: 180
    helpText: |
      Done when decision, KPI cards, owners, open questions, and next sprint are documented clearly.

notes: true
```

```sprint
id: week-02
number: 2
title: Source scope, PII, and DSDR
goal: Define source objects, skipped data, personal data, retention, and owners as a gate.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: dsdr-governance
    required: true

tasks:
  - id: source-scope-review
    label: Document source scope and supplier decision
    plannedMinutes: 300
    assigneeType: team
    assigneeId: null
    tableColumns: Object, Must-have, Optional, Skip, Reason, Owner
    helpText: |
      Decide deliberately what is loaded and what is not. A skip list is a governance result, not missing scope.
      Review supplier hints, core objects, standard measures, and system owners together.
    helpLinks:
      - label: Source Scope Builder
        href: /tools/source-scope-builder
        description: Clarifies must-have, optional, skip, PII, DSDR keys, and owners.
      - label: Supplier Library
        href: /suppliers
        description: Provides core objects, PII hints, and typical loads per supplier.
  - id: pii-dsdr-gate
    label: Review PII/DSDR gate
    plannedMinutes: 360
    assigneeType: team
    assigneeId: null
    tableColumns: Field, Identifier, Copy, Retention, Access, Control
    helpText: |
      Review personal data before implementation. The important point is not only fields, but every copy in raw, curated, mart, semantic layer, and exports.
    helpLinks:
      - label: PII/DSDR Readiness Checker
        href: /tools/pii-dsdr-readiness-checker
        description: Checks identifiers, free text, copies, retention, and access risks.

deliverables:
  - id: risk-backlog
    label: Risk backlog with owners
    plannedMinutes: 180
    helpText: |
      Done when risks, controls, owners, and approval requirements are clear before build or release.

notes: true
```

```sprint
id: week-03
number: 3
title: Data quality and mart design
goal: Translate KPI cards and source scope into fact/dimension candidates, DQ rules, and release gates.
dependsOn: week-02

stories:
  - slug: metadata-driven-governance-with-dbt-meta
    required: true
  - slug: data-quality-ownership
    required: false

tasks:
  - id: dq-rule-candidates
    label: Define DQ rules and validation
    plannedMinutes: 360
    assigneeType: team
    assigneeId: null
    tableColumns: Rule, Layer, KPI, Severity, Owner, Gate
    helpText: |
      Derive rules from real issues: freshness, completeness, business logic, and reconciliation with existing reports.
      Mark which rules only warn and which ones block build, release, or report approval.
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Creates Fabric-specific DQ rules and checks.
      - label: dbt DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Creates dbt test ideas for models and sources.
  - id: mart-design-brief
    label: Create mart design brief
    plannedMinutes: 420
    assigneeType: team
    assigneeId: null
    tableColumns: Fact, Grain, Dimension, History, Measure, Open modeling question
    helpText: |
      Use KPI cards and source scope as input. The mart design is good only when grain, history, dimensions, DQ gates, and ownership fit together.
    helpLinks:
      - label: Mart Design Brief
        href: /tools/mart-design-brief-generator
        description: Turns KPI cards into fact/dimension candidates and modeling questions.

deliverables:
  - id: quality-and-model-brief
    label: Quality gate and mart brief
    plannedMinutes: 240
    helpText: |
      Done when DQ rules, mart grain, open modeling questions, and owners are ready for review.

notes: true
```

```sprint
id: week-04
number: 4
title: Decision brief and change request
goal: Close implementation, learning plan, report, and future changes as a controllable workflow.
dependsOn: week-03

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: true

tasks:
  - id: final-decision-brief
    label: Finalize decision brief
    plannedMinutes: 300
    assigneeType: team
    assigneeId: null
    tableColumns: Decision, Recommendation, Risk, Assumption, Next action, Approver
    helpText: |
      The brief should enable a decision, not only document one. Separate recommendation, assumptions, risks, open questions, and next sprint.
    helpLinks:
      - label: Decision Brief Generator
        href: /tools/decision-brief-generator
        description: Creates a clear template for sponsor, architecture board, or change review.
  - id: change-request-model
    label: Connect change request need and learning plan
    plannedMinutes: 240
    assigneeType: team
    assigneeId: null
    tableColumns: Change, Reason, Impact, Approval, Learning need, Evidence
    helpText: |
      Capture which later changes must go through change request. Connect learning needs and certificates where missing skills create implementation risk.
    helpLinks:
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder
        description: Plans docs, exercises, and certificates for role, stack, and project.

deliverables:
  - id: governance-workspace-report
    label: Printable governance report with next plan
    plannedMinutes: 240
    helpText: |
      Done when report, plan tasks, review owners, change-request rule, and parallel learning plan fit together.

notes: true
```
