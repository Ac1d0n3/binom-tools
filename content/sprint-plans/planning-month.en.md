---
type: sprint-plan
title: Monthly planning (4 weeks)
slug: planning-month
description: Lightweight month plan: weekly outcomes, owners, and a clean handoff to next month.
duration: 4
unit: week
category: Planning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Planning
  - Month
  - Lightweight
---

Four weeks to plan and deliver a focused month without heavy ceremony.

```sprint
id: week-01
number: 1
title: Priorities & capacity
goal: Set month goals and capacity.

stories:
  - slug: eight-pillars
    required: false

tasks:
  - id: w1-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    helpText: |
      Capture what must ship this month and what stays on the waitlist.
      Agree success criteria in writing.
    linkedStories: eight-pillars
    helpLinks:
      - label: Impact–Effort Prioritizer
        href: /tools/impact-effort
      - label: 8 Pillars
        href: /playbooks/eight-pillars
  - id: w1-standup-board
    label: Update board and owners
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      One board of record. Owners, due dates, and status must be visible.
      Watch for: ghost tasks without owners.

deliverables:
  - id: w1-week-outcome
    label: Month priorities documented
    plannedMinutes: 120
    helpText: |
      Short dated note with top goals and capacity assumptions.

notes: true
```

```sprint
id: week-02
number: 2
title: Execution week
goal: Deliver the highest-priority work packages.

tasks:
  - id: w2-plan-week
    label: Break goals into weekly tasks
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Owners and done criteria for each package. Cut scope early if capacity is tight.
  - id: w2-standup-board
    label: Update board and owners
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Keep the board current after each standup or mid-week check.
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix

deliverables:
  - id: w2-week-outcome
    label: Weekly plan with owners
    plannedMinutes: 30
    helpText: |
      Board snapshot or short list of packages with owners.

notes: true
```

```sprint
id: week-03
number: 3
title: Risks & dependencies
goal: Unblock and re-plan mid-month.

tasks:
  - id: w3-plan-week
    label: List blockers and dependencies
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    helpText: |
      Decide cut scope vs push. Escalate only with a clear ask.
    helpLinks:
      - label: Impact–Effort Prioritizer
        href: /tools/impact-effort
  - id: w3-standup-board
    label: Update board and owners
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Reflect replans on the board the same day.

deliverables:
  - id: w3-week-outcome
    label: Risk and dependency list
    plannedMinutes: 30
    helpText: |
      Named risks, owners, and decisions.

notes: true
```

```sprint
id: week-04
number: 4
title: Close & handoff
goal: Close the month and seed next month.

tasks:
  - id: w4-plan-week
    label: Demo outcomes and archive notes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Capture what shipped, what slipped, and why.
  - id: w4-standup-board
    label: Draft next-month backlog
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Seed the next month with carry-overs and new priorities.

deliverables:
  - id: w4-week-outcome
    label: Month close + next backlog
    plannedMinutes: 60
    helpText: |
      One-pager: outcomes, learnings, next backlog.

notes: true
```
