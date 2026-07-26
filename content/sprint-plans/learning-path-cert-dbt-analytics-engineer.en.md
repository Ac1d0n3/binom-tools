---
type: sprint-plan
title: dbt certification companion (4 weeks)
slug: learning-path-cert-dbt-analytics-engineer
description: Week-by-week companion while you take the official dbt certification — Learn coursework, labs, exam, project transfer.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 3
capacity_hours_per_person_week: 8
category: Learning
author: Thomas Lindackers
version: 2
locale: en
tags:
  - Learning
  - Certification
  - dbt
  - Analytics Engineering
---

Companion plan for people who are **taking the dbt certification**. Official Learn/Docs stay the curriculum; this plan keeps cadence, labs, and project transfer on track.

```sprint
id: week-01
number: 1
title: Enroll and set the exam date
goal: Start official dbt Learn, pick the certificate, and lock an exam window.

stories:
  - slug: dbt-role
    required: false

tasks:
  - id: dbt-cert-enroll
    label: Open official cert + Learn track
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Certificate, Learn course, Exam window, Status
    helpText: |
      Start on the official pages — this plan accompanies the cert; it does not replace dbt Learn.
    helpLinks:
      - label: dbt Certifications
        href: https://www.getdbt.com/certifications
        description: Official certification overview.
      - label: Analytics Engineering Certificate
        href: https://learn.getdbt.com/courses/analytics-engineering
        description: Certificate path on dbt Learn.
      - label: dbt Fundamentals
        href: https://learn.getdbt.com/courses/dbt-fundamentals
        description: Foundations course if you need a warm-up.
  - id: dbt-cert-baseline
    label: Capture role and project transfer target
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    stories:
      - slug: dbt-role
        required: false
    tableColumns: Role, Project use of dbt, Skill gap, Transfer goal
    helpText: |
      Name what the badge must improve in the current project (tests, contracts, meta, ownership).

deliverables:
  - id: dbt-cert-plan-card
    label: Cert plan card
    plannedMinutes: 45
    helpText: |
      Done when certificate, Learn start, exam window, and transfer goal are written.

fields:
  - id: exam-window
    label: Exam window
    type: textarea
    placeholder: Target week / booking status

notes: true
```

```sprint
id: week-02
number: 2
title: Study domains on official materials
goal: Work through models, tests, contracts, and meta on dbt Docs/Learn.
dependsOn: week-01

tasks:
  - id: dbt-cert-study-core
    label: Study tests, contracts, meta
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Domain, Source, Notes, Open question
    helpText: |
      Follow the official curriculum; use the docs links as anchors for weak spots.
    helpLinks:
      - label: Docs — Data tests
        href: https://docs.getdbt.com/docs/build/data-tests
        description: Tests in the DAG.
      - label: Docs — Model contracts
        href: https://docs.getdbt.com/docs/collaborate/govern/model-contracts
        description: Schema contracts and breaking changes.
      - label: Docs — Meta config
        href: https://docs.getdbt.com/reference/resource-configs/meta
        description: Meta for ownership, PII, and DQ.
      - label: dbt Learn
        href: https://learn.getdbt.com/
        description: Continue the enrolled courses.
  - id: dbt-cert-gov-story
    label: Read governance companion stories
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    stories:
      - slug: metadata-driven-governance-with-dbt-meta
        required: false
    helpText: |
      Connect exam topics to how governance uses dbt meta in real stacks.

deliverables:
  - id: dbt-cert-study-log
    label: Study log
    plannedMinutes: 45
    helpText: |
      Done when weak domains and next practice topics are listed.

notes: true
```

```sprint
id: week-03
number: 3
title: Labs beside the coursework
goal: Produce practice artifacts that double as exam prep and project evidence.
dependsOn: week-02

tasks:
  - id: dbt-cert-labs
    label: Run DQ / governance labs
    plannedMinutes: 210
    assigneeType: person
    assigneeId: null
    tableColumns: Lab, Artifact, Reviewer, Status
    helpText: |
      Treat generator output as lab material — review with a steward before claiming evidence.
    helpLinks:
      - label: Path — DQ with dbt
        href: /learning-paths/dq-with-dbt
        description: Operating practice path for DQ with dbt.
      - label: dbt DQ Macros
        href: /tools/dbt-dq-macro-generator
        description: Macro lab starting points.
      - label: dbt DQ Rules
        href: /tools/dbt-dq-rules-generator
        description: Rules lab starting points.
      - label: dbt Governance Macros
        href: /tools/dbt-governance-macro-generator
        description: Governance macro lab.
  - id: dbt-cert-mock
    label: Mock review / practice quiz pass
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Topic, Score/feeling, Gap, Next action
    helpText: |
      Use Learn practice materials if available; otherwise self-quiz the weak domains from week 2.

deliverables:
  - id: dbt-cert-lab-pack
    label: Lab evidence pack
    plannedMinutes: 60
    helpText: |
      Done when at least one reviewed lab artifact and a mock-review note exist.

notes: true
```

```sprint
id: week-04
number: 4
title: Exam week and project transfer
goal: Sit the exam, store proof, and transfer skills into delivery.
dependsOn: week-03

tasks:
  - id: dbt-cert-exam
    label: Take the exam / complete certificate requirements
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Exam date, Result, Badge/proof link, Notes
    helpText: |
      Keep booking and result links with the plan — the companion ends when proof and transfer are recorded.
    helpLinks:
      - label: dbt Certifications
        href: https://www.getdbt.com/certifications
        description: Official certification hub.
      - label: Resources — vendor certs
        href: /resources
        description: Curated vendor docs and certificate links.
  - id: dbt-cert-transfer
    label: Transfer into the project
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Learning, Project task, Owner, Due
    helpText: |
      Convert one cert skill into a concrete delivery task (tests, contract, meta, ownership).
    helpLinks:
      - label: Path — Cert + project in parallel
        href: /learning-paths/cert-project-evidence
        description: Optional shared evidence track for teams.
      - label: Sprint Planner
        href: /sprint-planner
        description: Continue with delivery templates.

deliverables:
  - id: dbt-cert-done
    label: Cert complete checklist
    plannedMinutes: 45
    helpText: |
      Done when exam proof, lab pack, and at least one project transfer task are linked.

notes: true
```
