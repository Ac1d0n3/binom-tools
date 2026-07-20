---
type: sprint-plan
title: DB / report change with tests (3 weeks)
slug: change-tests
description: Ship a database or report change safely: impact, tests, verify, release.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 1
capacity_hours_per_person_week: 40
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
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Object, Change, Consumer, Risk, Rollback
    helpText: |
      Describe the change in a testable way: what changes, where it becomes visible, and which behavior must not change?
      List affected tables, reports, downstream jobs, consumers, and known SLAs.
      Document rollback or fallback before implementation starts.
      Use the Impact-Effort Prioritizer for scope and risk, but keep the technical impact list directly on the task.
    stories:
      - slug: data-quality-governance
        required: true
      - slug: missing-pieces-data-quality
        required: false
    helpLinks:
      - label: Impact-Effort Prioritizer
        href: /tools/impact-effort
        description: Use the tool to prioritize initiatives by impact, effort, risk, and dependencies.
      - label: GitHub Docs - Continuous integration
        href: https://docs.github.com/en/actions/get-started/continuous-integration
        description: Use the docs as a reference for setting up or reviewing continuous integration checks for changes.
  - id: w1-scope
    label: Agree in/out of scope
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Scope item, In/Out, Reason, Owner, Decision
    helpText: |
      Agree explicitly what changes this round and what does not. Metric definitions, filters, historical values, permissions, and layout are especially important.
      Write non-goals as concretely as goals so later discussions do not return as "just one small extra requirement".
      Every scope decision needs an owner or acceptance; otherwise it is only an assumption.
    helpLinks:
      - label: Atlassian - Acceptance criteria
        href: https://www.atlassian.com/work-management/project-management/acceptance-criteria
        description: Use the examples to write clear acceptance criteria for reports, data, or changes.

deliverables:
  - id: w1-impact-note
    label: Impact note
    plannedMinutes: 120
    helpText: |
      Create an impact note with change summary, affected objects, consumers, scope boundaries, and rollback.
      The deliverable is done when reviewers can see which tests are required and which risks are deliberately accepted.
      Link tickets, PRs, reports, or example queries that prove the impact.

fields:
  - id: change-summary
    label: Change summary
    type: textarea
    placeholder: What changes, why, where does it become visible?
  - id: rollback-plan
    label: Rollback plan
    type: textarea
    placeholder: Fallback option, accountable people, rollback trigger

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
    plannedMinutes: 360
    assigneeType: person
    assigneeId: null
    tableColumns: Risk, Test type, Assertion, Severity, Owner
    helpText: |
      Choose tests from risk, not habit: freshness, uniqueness, not-null, referential integrity, accepted values, KPI sanity, or custom SQL.
      Reuse existing DQ patterns and generators so test names, severity, and failure handling stay consistent.
      Critical tests should fail closed; informative checks may warn, but still need an owner.
      Document which assumption each test protects and what to do when it fails.
    stories:
      - slug: dq-test-kpis
        required: false
      - slug: dq-test2
        required: false
    helpLinks:
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Use the tool to turn observed data issues into testable quality checks.
      - label: DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
        description: Use the tool to prepare reusable dbt macros for data quality checks.
      - label: dbt Labs - Data quality testing
        href: https://www.getdbt.com/blog/data-quality-testing
        description: Use the article to translate test types and DQ thinking into concrete checks.
  - id: w2-fixtures
    label: Prepare fixtures / expected results
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Scenario, Input, Expected result, Edge case, Evidence
    helpText: |
      Prepare known-good rows, example KPIs, screenshots, or small queries that prove expected behavior.
      Cover at least the normal case and one relevant edge case, such as nulls, cancellations, empty dimensions, or late-arriving data.
      Fixtures must be small enough to understand in review but concrete enough to prevent misinterpretation.
    helpLinks:
      - label: GitHub Docs - Status checks
        href: https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/collaborating-on-repositories-with-code-quality-features/about-status-checks
        description: Use the docs as a reference for automated checks, review signals, and release quality.

deliverables:
  - id: w2-test-pack
    label: Test pack
    plannedMinutes: 180
    helpText: |
      Create a list of new or changed tests with assertion, severity, owner, target environment, and expected behavior.
      The deliverable is done when a reviewer can map every test to an impact risk.
      Add notes for warnings, allowed exceptions, and manual checks.

fields:
  - id: test-strategy
    label: Test strategy
    type: textarea
    placeholder: Critical checks, warnings, manual verification, open test gaps

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
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Check, Environment, Result, Evidence, Follow-up
    helpText: |
      Run tests in the target environment and save result, timestamp, version, and relevant artifacts.
      Critical checks must fail closed: no release while data integrity, access, or central KPI sanity is broken.
      Compare test results with the fixtures and clarify deviations before marking them as "known".
      Use the DQ History Generator when test results should become a timeline or release evidence.
    helpLinks:
      - label: DQ History Generator
        href: /tools/dbt-dq-history-generator
        description: Use the tool to make DQ results traceable over time and support validation evidence.
      - label: Microsoft Learn - Power BI enterprise content publishing
        href: https://learn.microsoft.com/en-us/power-bi/guidance/powerbi-implementation-planning-usage-scenario-enterprise-content-publishing
        description: Use the guidance to plan publishing, review, and approval for enterprise reports.
  - id: w3-release
    label: Release notes and monitoring
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Change, Watch signal, Owner, Escalation, Time window
    helpText: |
      Write release notes that business and operations can understand: what changed, why, which behavior is new, and what should be watched?
      Define monitoring signals for the first days, such as refresh, error rate, row count, KPI deviation, or user feedback.
      Name escalation path and observation window so problems do not wait until the next regular meeting.
    helpLinks:
      - label: Microsoft Fabric - Automate deployment pipelines
        href: https://learn.microsoft.com/en-us/fabric/cicd/deployment-pipelines/pipeline-automation
        description: Use the docs as a reference for deployment pipelines and automated releases in Fabric.

deliverables:
  - id: w3-release
    label: Release evidence
    plannedMinutes: 180
    helpText: |
      Collect test results, release notes, monitoring plan, known residual risks, and rollback decision.
      The deliverable is done when it is clear which version was released and why it is acceptable.
      Open follow-ups need owner and target date, otherwise they are not release-ready.

fields:
  - id: verification-summary
    label: Verification summary
    type: textarea
    placeholder: Test results, deviations, release decision, monitoring
  - id: release-watch
    label: Release monitoring
    type: textarea
    placeholder: Signals, owner, escalation path, observation window

notes: true
```
