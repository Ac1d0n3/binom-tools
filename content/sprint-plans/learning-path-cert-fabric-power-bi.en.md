---
type: sprint-plan
title: Fabric / Power BI certification companion (4 weeks)
slug: learning-path-cert-fabric-power-bi
description: Week-by-week companion while you take DP-600 and/or PL-300 — Microsoft Learn, labs, exam, project transfer.
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
  - Fabric
  - Power BI
---

Companion plan for people who are **taking DP-600 and/or PL-300**. Microsoft Learn stays the curriculum; this plan keeps cadence, labs, and transfer on track.

```sprint
id: week-01
number: 1
title: Pick exam and start Learn
goal: Choose DP-600 and/or PL-300, start Microsoft Learn, lock an exam window.

tasks:
  - id: ms-cert-enroll
    label: Open official cert pages and Learn paths
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Certificate, Learn path, Exam window, Status
    helpText: |
      Start on Microsoft Learn — this plan accompanies the cert; it does not replace the official modules.
    helpLinks:
      - label: DP-600 Fabric Analytics Engineer
        href: https://learn.microsoft.com/en-us/credentials/certifications/fabric-analytics-engineer-associate/
        description: Official DP-600 certification page.
      - label: PL-300 Power BI Data Analyst
        href: https://learn.microsoft.com/en-us/credentials/certifications/power-bi-data-analyst-associate/
        description: Official PL-300 certification page.
      - label: Learn — Get started with Fabric
        href: https://learn.microsoft.com/en-us/training/paths/get-started-fabric/
        description: Fabric fundamentals learning path.
      - label: Learn — Power BI
        href: https://learn.microsoft.com/en-us/training/powerplatform/power-bi
        description: Power BI learning entry.
  - id: ms-cert-baseline
    label: Capture role and project transfer target
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Role, Stack use, Skill gap, Transfer goal
    helpText: |
      Name what the badge must improve in the current program (lakehouse, semantic model, workspace roles, access).

deliverables:
  - id: ms-cert-plan-card
    label: Cert plan card
    plannedMinutes: 45
    helpText: |
      Done when certificate(s), Learn start, exam window, and transfer goal are written.

fields:
  - id: exam-window
    label: Exam window
    type: textarea
    placeholder: Target week / booking status for DP-600 and/or PL-300

notes: true
```

```sprint
id: week-02
number: 2
title: Study domains on Microsoft Learn
goal: Work through lakehouse, semantic, and governance topics on official materials.
dependsOn: week-01

tasks:
  - id: ms-cert-study
    label: Study Fabric / Power BI domains
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Domain, Source, Notes, Open question
    helpText: |
      Follow the Learn modules for your chosen exam; use docs for weak spots.
    helpLinks:
      - label: Docs — Fabric lakehouse
        href: https://learn.microsoft.com/en-us/fabric/data-engineering/lakehouse-overview
        description: Lakehouse overview.
      - label: Docs — Fabric governance
        href: https://learn.microsoft.com/en-us/fabric/governance/
        description: Governance in Fabric.
      - label: Docs — Fabric workspace roles
        href: https://learn.microsoft.com/en-us/fabric/fundamentals/roles-workspaces
        description: Workspace roles and permissions.
  - id: ms-cert-context-paths
    label: Connect platform companion paths
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Keep exam prep linked to warehouse and access operating practice.
    helpLinks:
      - label: Path — Modernize the warehouse
        href: /learning-paths/modernize-warehouse
        description: Grain, products, warehouse series.
      - label: Path — Access & security ops
        href: /learning-paths/access-security-ops
        description: Access and masking practice.

deliverables:
  - id: ms-cert-study-log
    label: Study log
    plannedMinutes: 45
    helpText: |
      Done when weak domains and next lab topics are listed.

notes: true
```

```sprint
id: week-03
number: 3
title: Labs beside Learn
goal: Produce stack/governance artifacts as exam prep and project evidence.
dependsOn: week-02

tasks:
  - id: ms-cert-labs
    label: Run architecture / decision labs
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Lab, Artifact, Reviewer, Status
    helpText: |
      Use Binom tools as labs — capture decisions that the cert skill should improve.
    helpLinks:
      - label: Architecture Fit
        href: /tools/architecture-fit
        description: Fit check lab.
      - label: Stack Advisor
        href: /tools/governance-stack-advisor
        description: Stack decision lab.
      - label: Decision Brief
        href: /tools/decision-brief-generator
        description: Short decision record.
      - label: Path — Simplest viable stack
        href: /learning-paths/simplest-viable-stack
        description: Companion path for stack simplicity.
  - id: ms-cert-mock
    label: Practice / mock pass
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Topic, Score/feeling, Gap, Next action
    helpText: |
      Use Microsoft practice assessments if available; otherwise self-quiz week-2 gaps.

deliverables:
  - id: ms-cert-lab-pack
    label: Lab evidence pack
    plannedMinutes: 60
    helpText: |
      Done when a decision brief/lab artifact and mock note exist.

notes: true
```

```sprint
id: week-04
number: 4
title: Exam week and project transfer
goal: Sit the exam, store proof, and transfer skills into delivery.
dependsOn: week-03

tasks:
  - id: ms-cert-exam
    label: Take DP-600 / PL-300
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Exam, Date, Result, Badge/proof link
    helpText: |
      Keep booking and result links with the plan.
    helpLinks:
      - label: DP-600 page
        href: https://learn.microsoft.com/en-us/credentials/certifications/fabric-analytics-engineer-associate/
        description: Fabric Analytics Engineer associate.
      - label: PL-300 page
        href: https://learn.microsoft.com/en-us/credentials/certifications/power-bi-data-analyst-associate/
        description: Power BI Data Analyst associate.
      - label: Resources
        href: /resources
        description: Curated Fabric / Power BI cert links.
  - id: ms-cert-transfer
    label: Transfer into the project
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Learning, Project task, Owner, Due
    helpText: |
      Convert one cert skill into a delivery task (workspace roles, semantic guardrails, lakehouse pattern).
    helpLinks:
      - label: Path — Cert + project in parallel
        href: /learning-paths/cert-project-evidence
        description: Optional shared evidence track.
      - label: Governance Hub
        href: /governance
        description: Capture the operating decision.

deliverables:
  - id: ms-cert-done
    label: Cert complete checklist
    plannedMinutes: 45
    helpText: |
      Done when exam proof, lab pack, and at least one transfer task are linked.

notes: true
```
