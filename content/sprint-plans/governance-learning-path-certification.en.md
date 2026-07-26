---
type: sprint-plan
title: Governance learning plan & certification (4 weeks)
slug: governance-learning-path-certification
description: Parallel learning plan for stack, data quality, PII, project exercises, and certification evidence.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 6
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Governance
  - Learning
  - Certification
  - dbt
  - Fabric
---

A parallel learning plan for teams that need to practice governance in the project, not only document it. The plan stays separate from the main implementation plan but remains connected through exercises and evidence.

```sprint
id: week-01
number: 1
title: Stack foundations and shared language
goal: Understand target stack, governance vocabulary, roles, and project context.

stories:
  - slug: end-to-end-governance-architecture
    required: true

tasks:
  - id: learn-stack-foundations
    label: Review stack and governance foundations
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Topic, Source, Exercise, Evidence, Open question
    helpText: |
      Start with the terms used in the project: workspace, lakehouse, mart, semantic layer, catalog, owner, PII, DSDR, and data quality gate.
      Capture not only links, but what was understood and where uncertainty remains.
    helpLinks:
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder
        description: Creates a role-specific learning path with docs, exercises, and certificates.
      - label: Vendor Resources & Certificates
        href: /resources
        description: Collects official vendor docs, learning paths, and certificates.

deliverables:
  - id: learning-baseline
    label: Learning baseline with roles
    plannedMinutes: 90
    helpText: |
      Done when roles, current knowledge, main gaps, and relevant learning sources are clear.

notes: true
```

```sprint
id: week-02
number: 2
title: KPI, modeling, and dbt tests
goal: Anchor learning in KPI cards, mart grain, and first tests.
dependsOn: week-01

stories:
  - slug: define-kpi
    required: true
  - slug: metadata-driven-governance-with-dbt-meta
    required: false

tasks:
  - id: practice-kpi-and-modeling
    label: Translate KPI card into model and test exercise
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: KPI, Grain, Model, Test, Documentation, Review
    helpText: |
      Use a real KPI from the Governance Workspace. Turn it into grain, fact/dimension hints, test idea, and documentation note.
      The goal is not perfect engineering, but a reviewable transfer from business definition to model and test.
    helpLinks:
      - label: KPI Requirements Intake
        href: /tools/kpi-requirements-intake?demo=finance
        description: Filled example for KPI card and acceptance example.
      - label: Mart Design Brief
        href: /tools/mart-design-brief-generator?demo=finance
        description: Filled example for fact/dimension candidates and DQ gates.

deliverables:
  - id: model-test-exercise
    label: KPI model and test exercise
    plannedMinutes: 120
    helpText: |
      Done when one KPI has been translated into a model sketch, test idea, and review question.

notes: true
```

```sprint
id: week-03
number: 3
title: Data quality, PII, and operations
goal: Practice DQ gates, PII controls, monitoring, and operations on project examples.
dependsOn: week-02

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: data-quality-ownership
    required: true

tasks:
  - id: practice-dq-pii
    label: Practice DQ and PII reviews
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Risk, Rule, Control, Owner, Evidence
    helpText: |
      Use the demo to identify which risks must be clarified before build or release. Practice turning them into clear rules and controls.
    helpLinks:
      - label: PII/DSDR Readiness Checker
        href: /tools/pii-dsdr-readiness-checker?demo=finance
        description: Filled example for identifiers, copies, retention, and access.
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Creates concrete DQ rules for Fabric.

deliverables:
  - id: dq-pii-review-note
    label: DQ/PII review evidence
    plannedMinutes: 120
    helpText: |
      Done when DQ rules, PII controls, and open owner questions can be explained clearly.

notes: true
```

```sprint
id: week-04
number: 4
title: Certification and transfer review
goal: Close certification goals, exercises, open gaps, and project transfer.
dependsOn: week-03

stories:
  - slug: operating-and-governing-the-platform
    required: true

tasks:
  - id: certification-and-transfer
    label: Finalize certification plan and project transfer
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Certificate, Topic, Exercise, Date, Evidence, Gap
    helpText: |
      Choose certificates because they support the project, not as decoration. DP-600, PL-300, dbt, or Databricks make sense when they reduce real implementation risk.
      Capture which exercise counts as evidence and which gap remains for the next learning cycle.
    helpLinks:
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder?demo=finance
        description: Filled example for a role-based learning and certification path.

deliverables:
  - id: certification-transfer-plan
    label: Certification and transfer plan
    plannedMinutes: 120
    helpText: |
      Done when certificates, learning sources, exercises, dates, evidence, and project relevance fit together.

notes: true
```
