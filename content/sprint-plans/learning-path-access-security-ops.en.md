---
type: sprint-plan
title: Learning path — Access & security ops (3 weeks)
slug: learning-path-access-security-ops
description: Turn the access & security ops learning path into a short plan — policy, masking practice, decision rights.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 5
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Learning
  - Access
  - Security
  - Privacy
---

Mirrors the Learning Path “Access & security ops”. Policy and masking as operating practice, not slideware.

```sprint
id: week-01
number: 1
title: Access governance language
goal: Align policy, roles, and typical access failure modes.

stories:
  - slug: access-security-governance
    required: true

tasks:
  - id: access-story
    label: Read access & security governance
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: access-security-governance
        required: true
    helpText: |
      Capture who approves access changes and where policy currently fails.
  - id: access-missing
    label: Review access missing piece
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    stories:
      - slug: missing-pieces-policy-access-governance
        required: false
    helpText: |
      Name the gap between written policy and runtime enforcement.

deliverables:
  - id: access-gap-note
    label: Access gap note
    plannedMinutes: 45
    helpText: |
      Done when top access gaps and owners are listed.

notes: true
```

```sprint
id: week-02
number: 2
title: Masking in practice
goal: Draft policy-driven masking patterns for the current stack.

tasks:
  - id: masking-story
    label: Review masking & section access patterns
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: snowflake-masking-policies-qlik-section-access
        required: false
    helpText: |
      Extract patterns that fit your warehouse and BI layer.
    helpLinks:
      - label: Glossary — Masking
        href: /glossary/masking
        description: Shared masking vocabulary.
  - id: masking-draft
    label: Draft PII policy starting points
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    tableColumns: Dataset, Policy, Role, Status
    helpText: |
      Treat generator output as a draft — review with steward and custodian.
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Policy starting points for PII handling.

deliverables:
  - id: masking-draft-pack
    label: Masking draft pack
    plannedMinutes: 60
    helpText: |
      Done when one priority dataset has a reviewed masking draft.

notes: true
```

```sprint
id: week-03
number: 3
title: Decision rights and handoff
goal: Clarify RACI for access and connect to the PII path.

tasks:
  - id: access-raci
    label: Capture access RACI
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Decision, R, A, C, I
    helpText: |
      Separate approve vs implement for access and masking changes.
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Decision-rights workbench.
      - label: Roles Hub
        href: /roles
        description: Personas and related stories.
  - id: access-next
    label: Continue into PII path
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Use the PII path when classification and DSDR still need grounding.
    helpLinks:
      - label: Path — PII in 5 steps
        href: /learning-paths/pii-in-five-steps
        description: Shortest solid privacy entry.

deliverables:
  - id: access-ops-checklist
    label: Access ops checklist
    plannedMinutes: 45
    helpText: |
      Done when RACI, draft policy, and next path are recorded.

notes: true
```
