---
type: sprint-plan
title: Report / analysis with KPIs (4 weeks)
slug: report-kpi-analysis
description: From decision questions to an accepted report: KPIs, sources, pilot, and handoff.
duration: 4
unit: week
category: Reporting
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Reporting
  - KPI
  - Analysis
---

Four weeks to ship a decision-ready report or analysis.

```sprint
id: week-01
number: 1
title: Questions & audience
goal: Clarify decisions and audience.

stories:
  - slug: define-kpi
    required: true
  - slug: kpi-metric-governance
    required: false

tasks:
  - id: w1-questions
    label: Write decision questions
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Decision question, Audience, Action, KPI candidate, Success signal
    helpText: |
      Do not start with visuals; start with decisions. What question should the report answer, who acts on it, and what would be a better decision?
      Write each question so it can trigger an action. A metric without a possible action is usually decoration.
      Collect KPI candidates, but mark immediately which ones only create attention and which ones can actually steer behavior.
      Use the KPI Definition Card to sharpen the business question, owner, and success signal.
    stories:
      - slug: define-kpi
        required: true
      - slug: kpi-metric-governance
        required: false
    helpLinks:
      - label: Report Inventory Canvas
        href: /tools/report-inventory
        description: Use the tool to inventory reports consistently with owner, tool, cadence, and business question.
      - label: KPI Definition Card
        href: /tools/kpi-definition
        description: Use the tool to capture KPI name, formula, grain, filters, owner, and open definition questions.
      - label: Tableau - Best Practices for Effective Dashboards
        href: https://help.tableau.com/current/pro/desktop/en-us/dashboards_best_practices.htm
        description: Use the best practices to check dashboard structure, readability, and user guidance.
      - label: Power BI - Report and dashboard creation
        href: https://learn.microsoft.com/en-us/power-bi/create-reports/
        description: Use the docs as a reference for report structure, pages, and Power BI implementation.
  - id: w1-audience
    label: Map consumers and cadence
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Consumer group, Decision, Cadence, Tool, Access need
    helpText: |
      Map audiences by name or clear role: decision makers, operational users, analysts, controllers, and external recipients.
      Capture cadence, preferred tool, detail level, and whether each group only reads or actively works with the result.
      Check whether the report supports a recurring decision or a one-off analysis.
      Watch for audiences who are not in the meeting but can later block access, layout, or definitions.

deliverables:
  - id: w1-brief
    label: Analysis brief
    plannedMinutes: 120
    helpText: |
      Create a short brief with decision questions, audiences, cadence, success criteria, and explicit non-goals.
      The deliverable is done when sponsor and implementer can give the same answer to "Why are we building this?"
      Keep assumptions and open decisions visible so they do not return later as hidden requirements.

fields:
  - id: decision-questions
    label: Decision questions
    type: textarea
    placeholder: Which decisions should the report improve?
  - id: audience-cadence
    label: Audience & cadence
    type: textarea
    placeholder: User groups, usage frequency, tool, access

notes: true
```

```sprint
id: week-02
number: 2
title: KPI set
goal: Define KPIs and definitions.

tasks:
  - id: w2-kpis
    label: Select core KPIs
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: KPI, Formula, Grain, Owner, Refresh, Decision use
    helpText: |
      Select a small set of core KPIs that directly support the decision questions. Every metric needs a name, formula, grain, owner, refresh, and explained use.
      Define thresholds or comparison values only where they are defensible.
      Keep the set small: three trustworthy metrics beat ten values without ownership.
      Use the KPI Definition Card for each core metric before it enters the report.
    stories:
      - slug: define-kpi
        required: true
    helpLinks:
      - label: KPI Definition Card
        href: /tools/kpi-definition
        description: Use the tool to capture KPI name, formula, grain, filters, owner, and open definition questions.
      - label: Tableau - Visualize Key Progress Indicators
        href: https://help.tableau.com/current/pro/desktop/en-us/kpi.htm
        description: Use the examples to design understandable KPI display and thresholds.
  - id: w2-defs
    label: Document metric definitions
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Metric, Numerator, Denominator, Filters, Exclusions, Owner
    helpText: |
      Document numerator, denominator, filters, exclusions, time zone, rounding, and validity scope.
      Write definitions so business and data teams can reproduce the same value.
      Name known gray areas instead of hiding them, for example cancellations, test data, backdating, or manual corrections.
      A definition is good enough only when someone can test and accept it.

deliverables:
  - id: w2-kpi-catalog
    label: KPI catalog draft
    plannedMinutes: 120
    helpText: |
      Create a KPI catalog with formula, grain, owner, source, refresh, business acceptance, and open questions.
      The deliverable is done when every core metric has an accountable person and a testable definition.
      Mark metrics that are not production-ready yet instead of silently presenting them as done.
    helpLinks:
      - label: Atlassian - Acceptance criteria
        href: https://www.atlassian.com/work-management/project-management/acceptance-criteria
        description: Use the examples to write clear acceptance criteria for reports, data, or changes.

fields:
  - id: kpi-scope
    label: KPI scope
    type: textarea
    placeholder: Core KPIs, excluded metrics, open definition questions

notes: true
```

```sprint
id: week-03
number: 3
title: Sources & mock
goal: Connect sources and mock the report.

tasks:
  - id: w3-sources
    label: Validate source fields
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Source field, Business meaning, Quality check, Owner, Issue
    helpText: |
      Validate the source fields needed for each KPI: availability, freshness, business meaning, and known quality issues.
      Use the Meta Export Generator for a quick column inventory, but confirm critical fields with owners or sample values.
      Check early whether filters, joins, or historical logic change the KPI value.
      If a field is unclear, document the uncertainty in the mock instead of discovering it during acceptance.
    stories:
      - slug: bi-tools
        required: false
    helpLinks:
      - label: Meta Export Generator
        href: /tools/meta-export-generator
        description: Use the tool to prepare reusable metadata exports from sources, fields, and owners.
      - label: Power BI - Report and dashboard creation
        href: https://learn.microsoft.com/en-us/power-bi/create-reports/
        description: Use the docs as a reference for report structure, pages, and Power BI implementation.
  - id: w3-mock
    label: Build mock / pilot view
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    helpText: |
      Build a wireframe or thin pilot with real sample values where possible.
      Show only the most important decisions and interactions; detail can grow later.
      Ask for feedback on "Which decision would you make with this?", not only on color or layout.
      Mark placeholders, data gaps, and assumptions visibly so the mock is not mistaken for a finished report.
    helpLinks:
      - label: Tableau - Best Practices for Effective Dashboards
        href: https://help.tableau.com/current/pro/desktop/en-us/dashboards_best_practices.htm
        description: Use the best practices to check dashboard structure, readability, and user guidance.

deliverables:
  - id: w3-pilot
    label: Pilot view or mock
    plannedMinutes: 60
    helpText: |
      Store a link or screenshot of the pilot together with feedback notes, open data questions, and design decisions.
      The deliverable is done when the audience can say whether the direction, metrics, and detail level fit.
      Document deliberately what is not production-ready yet.

fields:
  - id: source-risks
    label: Source risks
    type: textarea
    placeholder: Missing fields, quality issues, open owners, assumptions
  - id: pilot-feedback
    label: Pilot feedback
    type: textarea
    placeholder: What works, what is missing, which decision remains open?

notes: true
```

```sprint
id: week-04
number: 4
title: Acceptance
goal: Accept the report and hand off.

tasks:
  - id: w4-accept
    label: Run acceptance checklist
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    tableColumns: Check, Result, Evidence, Owner, Follow-up
    helpText: |
      Check correctness, performance, access, ownership, refresh, and business readability.
      Every deviation needs a decision: fix, accept, document, or remove from scope.
      Acceptance does not mean "looks good"; it means "reliable enough for the agreed decision."
    stories:
      - slug: bi-tools
        required: false
    helpLinks:
      - label: Atlassian - Acceptance criteria
        href: https://www.atlassian.com/work-management/project-management/acceptance-criteria
        description: Use the examples to write clear acceptance criteria for reports, data, or changes.
  - id: w4-handoff
    label: Handoff runbook
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Topic, Owner, Procedure, Frequency, Escalation
    helpText: |
      Write down how the report is refreshed, who owns it functionally and technically, and how issues are reported.
      Document known limits openly: data latency, exclusions, filter logic, permissions, and manual steps.
      Handoff is done when someone else can operate the report or at least escalate it sensibly.
    helpLinks:
      - label: Power BI - Report and dashboard creation
        href: https://learn.microsoft.com/en-us/power-bi/create-reports/
        description: Use the docs as a reference for report structure, pages, and Power BI implementation.

deliverables:
  - id: w4-accepted
    label: Report accepted
    plannedMinutes: 120
    helpText: |
      Keep the acceptance checklist, runbook link, known limits, and follow-up work in one place.
      The deliverable is done when business acceptance, operating responsibility, and next review date are documented.
      Avoid silent handoffs: every open question needs an owner or a deliberate decision to stop tracking it.

fields:
  - id: acceptance-summary
    label: Acceptance summary
    type: textarea
    placeholder: Accepted by, known limits, open follow-ups, next review

notes: true
```
