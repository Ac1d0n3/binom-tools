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
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Priority, Outcome, Owner, Effort, Decision
    helpText: |
      Write one month goal and no more than three measurable weekly outcomes. Separate committed work from the waitlist so the month does not overflow in week 1.
      Estimate capacity roughly in days or hours, subtract fixed meetings, leave, and operational work, and keep visible buffer.
      Agree success criteria in writing: what must be visible, decided, or handed over?
      Use the Impact-Effort Prioritizer if helpful, but only pull work that has an owner and a realistic done criterion.
    stories:
      - slug: eight-pillars
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
  - id: w1-standup-board
    label: Update board and owners
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Keep one board of record for the month. Every active task needs an owner, next step, due date, or a deliberate decision to park it.
      Remove duplicate lists and private side boards, otherwise status work turns into search work.
      Watch especially for tasks without owners, stale due dates, and work that has not moved for more than a week.
    helpLinks:
      - label: Atlassian - Product backlog
        href: https://www.atlassian.com/en/agile/scrum/backlogs
        description: Use the explanation to slice and prioritize backlog items cleanly.

deliverables:
  - id: w1-week-outcome
    label: Month priorities documented
    plannedMinutes: 120
    helpText: |
      Create a dated note with the month goal, top priorities, capacity assumptions, and explicit non-goals.
      The deliverable is done when a sponsor, teammate, or future you can see what the month is focused on and what intentionally waits.
      Link the board, open decisions, and the most important dependencies.

fields:
  - id: month-focus
    label: Month focus
    type: textarea
    placeholder: Main outcome, non-goals, and success criteria
  - id: capacity-assumptions
    label: Capacity assumptions
    type: textarea
    placeholder: Available days, fixed meetings, leave, operations, buffer

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
    tableColumns: Work package, Owner, Done criteria, Risk, Next step
    helpText: |
      Break each weekly outcome into small work packages that can be checked within a few days. Capture the owner, done criterion, and next step for each package.
      Cut scope early when capacity is tight: it is better to finish a smaller outcome than to create several half-open work streams.
      Before starting, check whether dependencies, access, or review people are missing.
    helpLinks:
      - label: Atlassian - Sprint backlog vs. product backlog
        href: https://www.atlassian.com/agile/project-management/sprint-backlog-product-backlog
        description: Use the page as an external reference for examples, review questions, or approach for this task; nothing to install.
  - id: w2-standup-board
    label: Update board and owners
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Update the board after the standup or mid-week check, not only at the end of the week.
      Status should make the next decision easier: continue, unblock, cut, or deliberately defer.
      If a task is stuck, write the concrete blocker and who can resolve it.
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Use the tool to structure people, roles, influence, interest, and owners directly as a stakeholder table.
      - label: Scrum.org - Introduction to the Sprint
        href: https://www.scrum.org/resources/introduction-sprint
        description: Use the page as an external reference for examples, review questions, or approach for this task; nothing to install.

deliverables:
  - id: w2-week-outcome
    label: Weekly plan with owners
    plannedMinutes: 30
    helpText: |
      Save a board snapshot or short list of active work packages with owner, done criterion, and next step.
      The deliverable is done when someone else can understand the week status without another meeting.
      Mark work that was intentionally cut or deferred.

fields:
  - id: delivery-risks
    label: Delivery risks
    type: textarea
    placeholder: Blockers, tight capacity, missing reviews, or open access

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
    tableColumns: Blocker, Dependency, Owner, Decision, Due date
    helpText: |
      List blockers, dependencies, and open decisions separately. Every blocker needs an owner, next action, and a date to revisit it.
      Decide deliberately between unblocking, cutting scope, deferring, or stopping.
      Escalate only with a clear ask: what decision is needed, from whom, by when, and with what impact?
    helpLinks:
      - label: Impact-Effort Prioritizer
        href: /tools/impact-effort
        description: Use the tool to prioritize initiatives by impact, effort, risk, and dependencies.
      - label: Atlassian - Sprint planning
        href: https://www.atlassian.com/agile/scrum/sprint-planning
        description: Use the guide as a reference for sprint goal, scope, and commitment.
  - id: w3-standup-board
    label: Update board and owners
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Reflect replans on the board the same day. Remove old priorities visibly or move them cleanly into next month.
      Do not only mark blocked work in red; write the reason and the next unblock step.
      Use the middle of the month to protect focus, not to push everything forward at once.

deliverables:
  - id: w3-week-outcome
    label: Risk and dependency list
    plannedMinutes: 30
    helpText: |
      Create a short list of risks, dependencies, owners, decisions, and due dates.
      The deliverable is done when each risk is clearly accepted, reduced, escalated, or turned into scope.
      Open points without an owner do not belong on the list; they go back into clarification.

fields:
  - id: replanning-decision
    label: Replanning decision
    type: textarea
    placeholder: What stays, what gets cut, what moves?

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
    tableColumns: Outcome, Status, Evidence, Learning, Follow-up
    helpText: |
      Capture what shipped, what slipped, and why. Support outcomes with links, screenshots, short notes, or decisions.
      Separate outcome, learning, and follow-up work so the month close does not become a vague recap.
      Discuss only the most important points live; details belong in the note or board.
    helpLinks:
      - label: Scrum Guide - Sprint Review
        href: https://scrumguides.org/scrum-guide.html#sprint-review
        description: Use the official Scrum Guide as a reference for review, feedback, and backlog adaptation.
  - id: w4-standup-board
    label: Draft next-month backlog
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    tableColumns: Backlog item, Owner, Reason, Target week, Next step
    helpText: |
      Carry work into next month only when it is still relevant. Everything else is closed, archived, or deliberately discarded.
      Write new backlog entries as an outcome plus reason, not just as loose ideas.
      Give every carry-over an owner and target week, otherwise it becomes quiet permanent leftovers.
    helpLinks:
      - label: Atlassian - Retrospective play
        href: https://www.atlassian.com/team-playbook/plays/retrospective
        description: Use the retro structure to collect learnings and improvements for the next sprint or quarter.

deliverables:
  - id: w4-week-outcome
    label: Month close + next backlog
    plannedMinutes: 60
    helpText: |
      Write a one-pager with outcomes, learnings, open risks, and the rough next backlog.
      The deliverable is done when it is clear what is complete, what deliberately continues, and which decision remains open for next month.
      Add retrospective actions with owner and date instead of only collecting observations.
    helpLinks:
      - label: Scrum Guide - Sprint Retrospective
        href: https://scrumguides.org/scrum-guide.html#sprint-retrospective
        description: Use the page as an external reference for examples, review questions, or approach for this task; nothing to install.

fields:
  - id: month-review
    label: Month review
    type: textarea
    placeholder: Outcomes, learnings, open risks, next decisions

notes: true
```
