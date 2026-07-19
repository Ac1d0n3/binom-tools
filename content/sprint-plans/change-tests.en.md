---
type: sprint-plan
title: DB / report change with tests (3 weeks)
slug: change-tests
description: Ship a database or report change safely: impact, tests, verify, release.
duration: 3
unit: week
category: Quality
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Quality
  - Tests
  - Change
---

Three weeks to change something important without flying blind.

```sprint
id: week-01
number: 1
title: Impact & scope
goal: Understand the change and blast radius.

stories:
  - slug: data-quality-governance
    required: true
  - slug: dq-test-kpis
    required: false

tasks:
  - id: w1-impact
    label: Document change and impact
    assigneeType: person
    assigneeId: null
    helpText: |
      Tables/reports touched, consumers, rollback path.
      Keep the change description testable.
    linkedStories: data-quality-governance, missing-pieces-data-quality
    helpLinks:
      - label: Data quality governance
        href: /playbooks/data-quality-governance
  - id: w1-scope
    label: Agree in/out of scope
    assigneeType: person
    assigneeId: null
    helpText: |
      What is explicitly not changing this round.

deliverables:
  - id: w1-impact-note
    label: Impact note
    helpText: |
      Change summary, consumers, rollback.

notes: true
```

```sprint
id: week-02
number: 2
title: Tests design
goal: Design and add tests.

tasks:
  - id: w2-tests
    label: Add or extend automated tests
    assigneeType: person
    assigneeId: null
    helpText: |
      Freshness, uniqueness, referential, KPI sanity — pick what fits.
      Prefer existing DQ patterns.
    linkedStories: dq-test-kpis, dq-test2
    helpLinks:
      - label: DQ test KPIs
        href: /playbooks/dq-test-kpis
      - label: DQ tests
        href: /playbooks/dq-test2
  - id: w2-fixtures
    label: Prepare fixtures / expected results
    assigneeType: person
    assigneeId: null
    helpText: |
      Known-good rows or expected KPI snapshots.

deliverables:
  - id: w2-test-pack
    label: Test pack
    helpText: |
      List of tests added/updated with owners.

notes: true
```

```sprint
id: week-03
number: 3
title: Verify & release
goal: Verify change and release.

tasks:
  - id: w3-verify
    label: Run tests in target environment
    assigneeType: person
    assigneeId: null
    helpText: |
      Record results; fail closed on critical checks.
  - id: w3-release
    label: Release notes and monitoring
    assigneeType: person
    assigneeId: null
    helpText: |
      What changed, how to watch it, who to call.

deliverables:
  - id: w3-release
    label: Release evidence
    helpText: |
      Test results + release notes.

notes: true
```
