---
type: sprint-plan
title: Quarter planning (lite, 13 weeks)
slug: planning-quarter-lite
description: Lightweight quarter: weekly planning and delivery rhythm without a full platform journey.
duration: 13
unit: week
category: Planning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Planning
  - Quarter
  - Lightweight
---

Thirteen weeks of light ceremony: plan, deliver, replan — ready for the next sprints.

```sprint
id: week-01
number: 1
title: Kickoff & outcomes
goal: Agree quarter outcomes and non-goals.

stories:
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: w1-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Outcome, Owner, Success signal, Non-goal, Risk
    helpText: |
      Derive 1-3 weekly outcomes from the quarter goals that can be visibly delivered or decided.
      Write owner, success signal, and non-goal for each outcome. That keeps the list short enough to actually finish.
      Clarify who decides functionally and who only needs to be informed.
      Use the Impact-Effort Prioritizer when outcomes compete, but start only work with a realistic done criterion.
    stories:
      - slug: data-ownership-stewardship
        required: false
    helpLinks:
      - label: Impact-Effort Prioritizer
        href: /tools/impact-effort
        description: Use the tool to prioritize initiatives by impact, effort, risk, and dependencies.
      - label: Scrum Guide - Sprint Planning
        href: https://scrumguides.org/scrum-guide.html#sprint-planning
        description: Use the official Scrum Guide as a short reference for sprint goal, scope, and planning logic.
      - label: Atlassian - Sprint planning
        href: https://www.atlassian.com/agile/scrum/sprint-planning
        description: Use the guide as a reference for sprint goal, scope, and commitment.
  - id: w1-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Keep the board current and move blocked work visibly, not only mentally.
      Status should make the next decision easier every day: continue, unblock, cut, or defer.
      End the week with a short written note so the quarter remains traceable later.
    helpLinks:
      - label: Atlassian - Product backlog
        href: https://www.atlassian.com/en/agile/scrum/backlogs
        description: Use the explanation to slice and prioritize backlog items cleanly.

deliverables:
  - id: w1-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Create a dated note with planned vs. done, blockers, decisions, and next focus.
      The deliverable is done when someone can understand the week status without another meeting.
      Mark deliberately what will not continue.

fields:
  - id: quarter-outcomes
    label: Quarter outcomes
    type: textarea
    placeholder: 3-5 outcomes, success signals, non-goals
  - id: kickoff-decisions
    label: Kickoff decisions
    type: textarea
    placeholder: Owners, scope boundaries, open decisions

notes: true
```

```sprint
id: week-02
number: 2
title: Backlog shaping
goal: Shape a thin backlog for the next 2–3 weeks.

tasks:
  - id: w2-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Define 1–3 outcomes for the week tied to quarter goals.
      Keep the list short enough to finish.
  - id: w2-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w2-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Dated note: planned vs done, blockers, next focus.

notes: true
```

```sprint
id: week-03
number: 3
title: Delivery rhythm
goal: Establish weekly delivery rhythm.

tasks:
  - id: w3-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Define 1–3 outcomes for the week tied to quarter goals.
      Keep the list short enough to finish.
  - id: w3-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w3-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Dated note: planned vs done, blockers, next focus.

notes: true
```

```sprint
id: week-04
number: 4
title: Dependency map
goal: Map critical dependencies and owners.

tasks:
  - id: w4-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Dependency, Owner, Needed by, Risk, Ask
    helpText: |
      Use this week to make critical dependencies visible before they block the mid-quarter check.
      For each dependency, write owner, needed-by date, risk, and concrete ask.
      Plan only outcomes that are realistic despite the dependencies.
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Use the tool to structure people, roles, influence, interest, and owners directly as a stakeholder table.
      - label: Atlassian - Dependency mapping
        href: https://www.atlassian.com/team-playbook/plays/dependency-mapping
        description: Use the method to make dependencies, owners, and risks visible.
  - id: w4-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w4-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Capture planned vs. done outcomes, dependencies, owners, and open decisions.
      The deliverable is done when every critical dependency has a next action and owner.

notes: true
```

```sprint
id: week-05
number: 5
title: Mid-quarter check
goal: Review progress vs outcomes; replan.

stories:
  - slug: eight-pillars
    required: false

tasks:
  - id: w5-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Outcome, Status, Evidence, Decision, Adjustment
    helpText: |
      Compare quarter outcomes with real progress: what is done, what has only started, and what creates no value yet?
      Decide deliberately what stays, what gets cut, and what moves into a later quarter.
      Use this week for a better remaining plan, not for more scope.
    helpLinks:
      - label: Impact-Effort Prioritizer
        href: /tools/impact-effort
        description: Use the tool to prioritize initiatives by impact, effort, risk, and dependencies.
      - label: Atlassian - Health monitor
        href: https://www.atlassian.com/team-playbook/health-monitor
        description: Use the questions to check whether team, scope, risks, and decisions are healthy enough.
  - id: w5-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w5-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Document progress, evidence, cut scope, new risks, and focus for the next weeks.
      The deliverable is done when the rest of the quarter has become smaller and clearer.

fields:
  - id: midquarter-adjustment
    label: Mid-quarter adjustment
    type: textarea
    placeholder: What stays, what gets cut, what moves?

notes: true
```

```sprint
id: week-06
number: 6
title: Focus delivery
goal: Protect focus time for top outcomes.

tasks:
  - id: w6-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Define 1–3 outcomes for the week tied to quarter goals.
      Keep the list short enough to finish.
  - id: w6-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w6-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Dated note: planned vs done, blockers, next focus.

notes: true
```

```sprint
id: week-07
number: 7
title: Quality gate
goal: Define quality gates for work in flight.

tasks:
  - id: w7-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Define 1–3 outcomes for the week tied to quarter goals.
      Keep the list short enough to finish.
  - id: w7-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w7-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Dated note: planned vs done, blockers, next focus.

notes: true
```

```sprint
id: week-08
number: 8
title: Stakeholder sync
goal: Sync decisions with stakeholders.

tasks:
  - id: w8-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Define 1–3 outcomes for the week tied to quarter goals.
      Keep the list short enough to finish.
  - id: w8-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w8-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Dated note: planned vs done, blockers, next focus.

notes: true
```

```sprint
id: week-09
number: 9
title: Risk burn-down
goal: Burn down top risks with explicit owners.

tasks:
  - id: w9-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Risk, Impact, Owner, Mitigation, Decision
    helpText: |
      Focus the week on the largest remaining risks. Every risk needs impact, owner, decision, and next step.
      Reduce risk actively: test, simplify, get a decision, or cut scope.
      Do not simply push risks into next week when they threaten the quarter outcome.
    helpLinks:
      - label: Atlassian - Risk assessment matrix
        href: https://www.atlassian.com/work-management/project-management/risk-assessment-matrix
        description: Use the matrix to classify risks by likelihood and impact.
  - id: w9-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w9-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Capture which risks were reduced, accepted, or escalated.
      The deliverable is done when every top risk has an owner and clear decision.

fields:
  - id: risk-burndown
    label: Risk burn-down
    type: textarea
    placeholder: Top risks, decisions, owners, residual risk

notes: true
```

```sprint
id: week-10
number: 10
title: Integration week
goal: Integrate and validate end-to-end paths.

tasks:
  - id: w10-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Define 1–3 outcomes for the week tied to quarter goals.
      Keep the list short enough to finish.
  - id: w10-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w10-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Dated note: planned vs done, blockers, next focus.

notes: true
```

```sprint
id: week-11
number: 11
title: Hardening
goal: Harden, document, and cut remaining scope.

tasks:
  - id: w11-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Define 1–3 outcomes for the week tied to quarter goals.
      Keep the list short enough to finish.
  - id: w11-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w11-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Dated note: planned vs done, blockers, next focus.

notes: true
```

```sprint
id: week-12
number: 12
title: Demo prep
goal: Prepare demos and acceptance evidence.

tasks:
  - id: w12-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Define 1–3 outcomes for the week tied to quarter goals.
      Keep the list short enough to finish.
  - id: w12-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w12-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Dated note: planned vs done, blockers, next focus.

notes: true
```

```sprint
id: week-13
number: 13
title: Close & next quarter
goal: Close the quarter and seed the next one.

tasks:
  - id: w13-plan-week
    label: Plan this week’s outcomes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Outcome, Result, Evidence, Learning, Next quarter
    helpText: |
      Close the quarter through outcomes, not activity. What was delivered, decided, learned, or deliberately stopped?
      Collect evidence: links, screenshots, acceptances, metrics, or short decision notes.
      Sketch next quarter's backlog only roughly and separate carry-over from new ideas.
    helpLinks:
      - label: Scrum Guide - Sprint Review
        href: https://scrumguides.org/scrum-guide.html#sprint-review
        description: Use the official Scrum Guide as a reference for review, feedback, and backlog adaptation.
      - label: Atlassian - Retrospective play
        href: https://www.atlassian.com/team-playbook/plays/retrospective
        description: Use the retro structure to collect learnings and improvements for the next sprint or quarter.
  - id: w13-run-week
    label: Run and update the board
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Daily/stand-up updates. Move blocked work visibly.
      End the week with a short written status.

deliverables:
  - id: w13-week-outcome
    label: Weekly outcome note
    plannedMinutes: 60
    helpText: |
      Document quarter outcomes, learnings, open risks, and rough focus for the next quarter.
      The deliverable is done when it is clear what is complete, what deliberately continues, and which decision remains open.

fields:
  - id: quarter-close
    label: Quarter close
    type: textarea
    placeholder: Outcomes, learnings, open risks, next-quarter ideas

notes: true
```
