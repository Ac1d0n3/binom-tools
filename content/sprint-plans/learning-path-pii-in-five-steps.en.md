---
type: sprint-plan
title: Learning path — PII in 5 steps (3 weeks)
slug: learning-path-pii-in-five-steps
description: Turn the PII learning path into a short team plan — terms, privacy story, DSDR, readiness, hub anchor.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 4
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Learning
  - PII
  - DSDR
  - Privacy
---

A light plan that mirrors the Learning Path “PII in 5 steps”. Capture shared language and readiness gaps before policies hit production.

```sprint
id: week-01
number: 1
title: Terms and privacy story
goal: Align on PII, DSDR, retention, and masking — then read why classification comes before tooling.

stories:
  - slug: pii-privacy-governance
    required: true

tasks:
  - id: pii-terms
    label: Align glossary terms
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Term, Working definition, Owner, Open question
    helpText: |
      Agree shared wording for PII, DSDR, retention, and masking before any generator or policy draft.
    helpLinks:
      - label: Glossary — PII
        href: /glossary/pii
        description: Shared vocabulary for personal data.
      - label: Glossary — DSDR
        href: /glossary/dsdr
        description: Data subject deletion / rights requests.
      - label: Glossary — Masking
        href: /glossary/masking
        description: Masking vs access patterns.
  - id: pii-story
    label: Read privacy governance story
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    helpText: |
      Capture why purpose binding and classification come before tooling. Note GDPR touchpoints for your stack.
    stories:
      - slug: pii-privacy-governance
        required: true
    helpLinks:
      - label: Compliance — GDPR
        href: /compliance/gdpr
        description: Compliance hub entry for GDPR.

deliverables:
  - id: pii-language-baseline
    label: Shared PII language baseline
    plannedMinutes: 60
    helpText: |
      Done when the team can point to agreed definitions and one page of open questions.

notes: true
```

```sprint
id: week-02
number: 2
title: Deletion paths and readiness
goal: Design deletion/lineage thinking and surface readiness gaps before prod policies.

stories:
  - slug: dsdr-governance
    required: true

tasks:
  - id: pii-dsdr-paths
    label: Sketch DSDR / lineage paths
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: System, Personal data, Deletion path, Gap
    helpText: |
      Without lineage, DSDR requests die in ticket chaos. Map where personal data lives and how deletion would flow.
    stories:
      - slug: dsdr-governance
        required: true
    helpLinks:
      - label: Glossary — Lineage
        href: /glossary/lineage
        description: Why lineage anchors deletion.
  - id: pii-readiness
    label: Run readiness check
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    helpText: |
      Use the readiness checker and policy generator as draft aids — not as finished policy.
    helpLinks:
      - label: PII/DSDR Readiness
        href: /tools/pii-dsdr-readiness-checker
        description: Surface gaps before policies hit production.
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Draft classification and masking starting points.

deliverables:
  - id: pii-readiness-note
    label: Readiness gap note
    plannedMinutes: 90
    helpText: |
      Done when gaps, owners, and next actions are visible to privacy and platform.

notes: true
```

```sprint
id: week-03
number: 3
title: Anchor in the hub
goal: Capture decision, stack, and next steps in the Governance Advisor.

tasks:
  - id: pii-hub-session
    label: Record advisor session
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Save the situation (help / extend), stack, and PII-related decisions so the team can reopen them later.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Advisor entry for orientation and session save.
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder
        description: Optional vendor-specific learning follow-up.
  - id: pii-roles-check
    label: Confirm related roles
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Confirm who acts as steward, custodian, and consumer for the personal-data scope.
    helpLinks:
      - label: Roles Hub
        href: /roles
        description: Decision-rights cards for governance personas.

deliverables:
  - id: pii-next-actions
    label: Next actions list
    plannedMinutes: 60
    helpText: |
      Done when the next three actions have owners and a link back to the learning path or sprint plan.

notes: true
```
