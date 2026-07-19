---
type: sprint-plan
title: Data & Reporting – First Quarter
slug: data-reporting-first-quarter
description: Understand the data and reporting landscape and deliver the first sustainable improvement.
duration: 13
unit: week
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Data Platform
  - Reporting
  - Governance
---

Thirteen weeks to understand reporting and the data platform, clarify risks, and deliver a first pilot.

```sprint
id: week-01
number: 1
title: Orientation and Mandate
goal: Understand the assignment, expectations, and relevant stakeholders.

flowVariant: linear
flowLayout: vertical
flowSteps:
  - Mandate & stakeholders
  - Reporting landscape
  - Sources & creation
  - Lineage & KPIs
  - Pilot & close


stories:
  - slug: data-ownership-stewardship
    required: true
  - slug: missing-pieces-ownership-stewardship
    required: false

tasks:
  - id: align-management-expectations
    label: Align expectations with leadership
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    helpText: |
      Clarify why this quarter exists: which decisions should improve, which pain is unacceptable, and what “good enough” looks like after 13 weeks.
      Capture success criteria in writing (not only in meetings). Ask explicitly what is out of scope.
      Watch for: vague goals (“better reporting”), hidden compliance pressure, and conflicting sponsors.
    linkedStories: data-ownership-stewardship, eight-pillars
    helpLinks:
      - label: Atlassian - Project poster
        href: https://www.atlassian.com/team-playbook/plays/project-poster
        description: Read this as a thinking frame for mandate, problem, goal, stakeholders, and open decisions; nothing to install.
  - id: identify-stakeholders
    label: Identify relevant stakeholders
    plannedMinutes: 30
    assigneeType: team
    assigneeId: null
    tableColumns: Name, Role, Influence, Interest, Owner
    helpText: |
      Map decision makers, report consumers, data producers, and platform owners. Prefer named people over departments.
      Note influence, interest, and who can block access to systems or definitions.
      Watch for: “everyone” as a stakeholder list, and missing operational owners for source data.
    linkedStories: data-ownership-stewardship, missing-pieces-ownership-stewardship
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Use the tool to structure people, roles, influence, interest, and owners directly as a stakeholder table.
      - label: Atlassian - DACI decision framework
        href: https://www.atlassian.com/team-playbook/plays/daci
        description: Use it to separate decision makers, contributors, and informed people for decisions.

deliverables:
  - id: stakeholder-list
    label: Create stakeholder list
    plannedMinutes: 30
    dependsOn: identify-stakeholders
    helpText: |
      Use the table from “Identify relevant stakeholders” as the working base.
      The deliverable is done when name, role, influence, interest, and owner are captured for the relevant stakeholders.
      Add open access needs, blocking people, or missing operational owners as notes instead of hiding them in raw notes.
  - id: initial-mandate
    label: Initial mandate documented
    plannedMinutes: 120
    dependsOn: align-management-expectations, identify-stakeholders
    helpText: |
      Create the artifact “Initial mandate documented” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: management-expectations
    label: Management expectations
    type: textarea
    placeholder: Goals, expectations, and success criteria
  - id: primary-owner
    label: Primary owner
    type: text
    placeholder: Name or role

notes: true
```

```sprint
id: week-02
number: 2
title: Reporting Landscape
goal: Capture existing reports, users, and critical gaps.
dependsOn: week-01

stories:
  - slug: bi-tools
    required: true
  - slug: one-app
    required: false

tasks:
  - id: inventory-reports
    label: Inventory existing reports
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Report, Owner, Tool, Cadence, Business question
    helpText: |
      List active reports/dashboards with owner, tool, refresh cadence, and business question answered.
      Mark critical vs. rarely used. Prefer evidence (usage logs, interviews) over assumptions.
      Watch for: shadow Excel reports, duplicated KPIs under different names, and orphaned dashboards.
    linkedStories: bi-tools, one-app
    helpLinks:
      - label: Report Inventory Canvas
        href: /tools/report-inventory
        description: Use the tool to inventory reports consistently with owner, tool, cadence, and business question.
      - label: Microsoft Learn - Power BI implementation planning
        href: https://learn.microsoft.com/en-us/power-bi/guidance/powerbi-implementation-planning-introduction
        description: Use the guidance as a checklist for Power BI roles, workspaces, governance, and rollout questions.
  - id: map-report-consumers
    label: Map report consumers and usage frequency
    plannedMinutes: 120
    assigneeType: team
    assigneeId: null
    helpText: |
      For critical reports, document who uses them, for which decision, and how often.
      Separate “nice to have” from “blocks operations or board reporting”.
      Watch for: consumers who never open the report but block changes, and unknown distribution lists.
    linkedStories: bi-tools, one-app
deliverables:
  - id: report-inventory
    label: Report inventory documented
    plannedMinutes: 120
    dependsOn: inventory-reports
    helpText: |
      Create the artifact “Report inventory documented” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: gap-list
    label: Initial gap list created
    plannedMinutes: 30
    dependsOn: inventory-reports, map-report-consumers
    helpText: |
      Create the artifact “Initial gap list created” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: critical-reports
    label: Critical reports
    type: textarea
    placeholder: Reports with high business impact

notes: true
```

```sprint
id: week-03
number: 3
title: Source Systems
goal: Understand relevant source systems, owners, and interfaces.
dependsOn: week-02

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: sap-overview
    required: false

tasks:
  - id: list-source-systems
    label: Capture source systems and owners
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    tableColumns: Source, Owner, Access, Consumed by
    helpText: |
      Inventory systems that feed reporting: ERP, CRM, finance, files, APIs. Record system owner and data steward if known.
      Rank by criticality for the reports you already inventoried.
      Watch for: unofficial databases, shared drives as “systems of record”, and unclear SAP vs. satellite ownership.
    linkedStories: before-building-the-first-table, sap-overview
    helpLinks:
      - label: Meta Export Generator
        href: /tools/meta-export-generator
        description: Use the tool to prepare reusable metadata exports from sources, fields, and owners.
  - id: document-interfaces
    label: Document interfaces and extraction paths
    plannedMinutes: 120
    dependsOn: list-source-systems
    assigneeType: team
    assigneeId: null
    helpText: |
      For priority sources, document how data leaves the system (API, CDC, batch, file, manual export) and who operates it.
      Note latency, failure modes, and whether history is preserved.
      Watch for: undocumented Excel extracts and “someone runs a script on Fridays”.
    linkedStories: before-building-the-first-table, building-from-scratch
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Use the tool to structure people, roles, influence, interest, and owners directly as a stakeholder table.
      - label: Atlassian - DACI decision framework
        href: https://www.atlassian.com/team-playbook/plays/daci
        description: Use it to separate decision makers, contributors, and informed people for decisions.

deliverables:
  - id: source-system-map
    label: Source system map created
    plannedMinutes: 60
    dependsOn: list-source-systems
    helpText: |
      Create the artifact “Source system map created” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: owner-matrix
    label: Owner matrix created
    plannedMinutes: 30
    dependsOn: list-source-systems, document-interfaces
    helpText: |
      Create the artifact “Owner matrix created” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: system-risks
    label: System risks
    type: textarea
    placeholder: Known risks or bottlenecks

notes: true
```

```sprint
id: week-04
number: 4
title: Data Creation
goal: How operational data is created and which rules apply.
dependsOn: week-03

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: trash-iinout
    required: false

tasks:
  - id: trace-data-creation
    label: Trace data creation in core processes
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Walk the business process that creates the data behind critical reports. Who enters what, when, and under which incentive?
      Sketch process → system fields → report fields for 1–3 critical flows.
      Watch for: late corrections in Excel, optional fields that drive KPIs, and process exceptions that never reach the warehouse.
    linkedStories: before-building-the-first-table, trash-iinout
  - id: capture-business-rules
    label: Document business rules and exceptions
    plannedMinutes: 30
    dependsOn: trace-data-creation
    assigneeType: team
    assigneeId: null
    helpText: |
      Capture calculation and eligibility rules in plain language, including known exceptions and who may approve them.
      Prefer examples with real edge cases over abstract definitions.
      Watch for: “everybody knows” rules that exist only in people’s heads.
    linkedStories: define-kpi, missing-pieces-trusted-metrics
deliverables:
  - id: creation-notes
    label: Data creation notes
    plannedMinutes: 60
    dependsOn: trace-data-creation
    helpText: |
      Create the artifact “Data creation notes” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: rule-summary
    label: Business rule summary
    plannedMinutes: 60
    dependsOn: capture-business-rules
    helpText: |
      Create the artifact “Business rule summary” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: key-processes
    label: Key processes
    type: textarea
    placeholder: Processes with the greatest impact on reporting

notes: true
```

```sprint
id: week-05
number: 5
title: End-to-End Lineage
goal: Make the path from source to report traceable.
dependsOn: week-03, week-04

flowVariant: linear
flowLayout: vertical
flowSteps:
  - Source system
  - Extract / interface
  - Transform / model
  - KPI / semantic
  - Report


stories:
  - slug: metadata-catalog-lineage
    required: true
  - slug: missing-pieces-metadata-catalog-lineage
    required: false

tasks:
  - id: map-lineage-paths
    label: Sketch central lineage paths
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Draw source → staging/transform → semantic/KPI → report for the priority flows. Keep it rough but end-to-end.
      Name systems, jobs, and owners where known; mark unknowns explicitly.
      Watch for: false confidence from tool lineage that only covers part of the path.
    linkedStories: metadata-catalog-lineage, missing-pieces-metadata-catalog-lineage
    helpLinks:
      - label: Meta Export Generator
        href: /tools/meta-export-generator
        description: Use the tool to prepare reusable metadata exports from sources, fields, and owners.
  - id: identify-lineage-gaps
    label: Mark lineage gaps and blind spots
    plannedMinutes: 30
    dependsOn: map-lineage-paths
    assigneeType: team
    assigneeId: null
    helpText: |
      List breaks: manual steps, undocumented transforms, renamed fields, multiple competing definitions.
      Prioritize gaps that affect trust in critical KPIs or compliance-sensitive data.
      Watch for: ignoring “temporary” transforms that have existed for years.
    linkedStories: metadata-catalog-lineage, missing-pieces-metadata-catalog-lineage
deliverables:
  - id: lineage-sketch
    label: Lineage sketch created
    plannedMinutes: 60
    dependsOn: map-lineage-paths
    helpText: |
      Create the artifact “Lineage sketch created” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: lineage-gap-log
    label: Lineage gap log
    plannedMinutes: 60
    dependsOn: identify-lineage-gaps
    helpText: |
      Create the artifact “Lineage gap log” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: priority-flows
    label: Priority data flows
    type: textarea
    placeholder: Flows for the first pilot

notes: true
```

```sprint
id: week-06
number: 6
title: KPI Inventory
goal: Clarify key KPIs, definitions, and ownership.
dependsOn: week-02, week-04

stories:
  - slug: define-kpi
    required: true
  - slug: kpi-metric-governance
    required: false
  - slug: missing-pieces-trusted-metrics
    required: false

tasks:
  - id: collect-kpis
    label: Collect KPIs from reports and stakeholder interviews
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Collect KPI names as used in reports and conversations. Record formula candidates, grain, filters, and owner guesses.
      Deduplicate synonyms early (“revenue”, “turnover”, “sales”).
      Watch for: KPIs without an owner and metrics that only exist as dashboard titles.
    linkedStories: define-kpi, kpi-metric-governance
    helpLinks:
      - label: KPI Definition Card
        href: /tools/kpi-definition
        description: Use the tool to capture KPI name, formula, grain, filters, owner, and open definition questions.
      - label: Tableau - Visualize Key Progress Indicators
        href: https://help.tableau.com/current/pro/desktop/en-us/kpi.htm
        description: Use the examples to design understandable KPI display and thresholds.
  - id: normalize-definitions
    label: Align definitions and calculation rules
    plannedMinutes: 30
    dependsOn: collect-kpis
    assigneeType: team
    assigneeId: null
    helpText: |
      For top KPIs, agree one definition, one owner, and one calculation path. Document open disputes instead of papering over them.
      Version the definition if the business still needs the old variant temporarily.
      Watch for: “average of everyone’s number” as a compromise.
    linkedStories: define-kpi, missing-pieces-trusted-metrics
deliverables:
  - id: kpi-inventory
    label: KPI inventory created
    plannedMinutes: 120
    dependsOn: collect-kpis
    helpText: |
      Create the artifact “KPI inventory created” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: definition-backlog
    label: Definition backlog prioritized
    plannedMinutes: 120
    dependsOn: normalize-definitions
    helpText: |
      Create the artifact “Definition backlog prioritized” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: top-kpis
    label: Top KPIs
    type: textarea
    placeholder: The most important metrics for the quarter

notes: true
```

```sprint
id: week-07
number: 7
title: Data Quality and Risks
goal: Prioritize quality issues and risks.
dependsOn: week-05, week-06

stories:
  - slug: data-quality-governance
    required: true
  - slug: missing-pieces-data-quality
    required: false
  - slug: pii-privacy-governance
    required: false

links:
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator
    description: Use the tool to turn observed data issues into testable quality checks.
  - label: DQ Macro Generator
    href: /tools/dbt-dq-macro-generator
    description: Use the tool to prepare reusable dbt macros for data quality checks.

tasks:
  - id: assess-dq-issues
    label: Assess known DQ issues
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Collect known defects: completeness, uniqueness, freshness, consistency across systems. Score impact on critical KPIs.
      Prefer measurable symptoms over anecdotes. Note where tests already exist vs. only tribal knowledge.
      Watch for: fixing cosmetics while core keys and dates remain unreliable.
    linkedStories: data-quality-governance, dq-test-kpis, missing-pieces-data-quality
    helpLinks:
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Use the tool to turn observed data issues into testable quality checks.
      - label: dbt Docs - Data tests
        href: https://docs.getdbt.com/docs/build/data-tests
        description: Use the docs to turn quality assumptions into implementable dbt tests.
      - label: DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
        description: Use the tool to prepare reusable dbt macros for data quality checks.
  - id: rate-risks
    label: Rate business and compliance risks
    plannedMinutes: 60
    dependsOn: assess-dq-issues
    assigneeType: team
    assigneeId: null
    helpText: |
      Rate risks for wrong decisions, regulatory exposure, and PII/privacy. Separate likelihood from impact.
      Flag access and retention issues that block a safe pilot.
      Watch for: treating every data bug as equal urgency.
    linkedStories: pii-privacy-governance, access-security-governance, eight-pillars
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Use the tool to structure PII classes, masking, and access rules as a policy draft.
      - label: Microsoft Purview - Data classification
        href: https://learn.microsoft.com/en-us/purview/data-classification
        description: Use the docs to classify PII/sensitivity levels and data categories cleanly.

deliverables:
  - id: dq-risk-register
    label: DQ and risk register
    plannedMinutes: 60
    dependsOn: assess-dq-issues, rate-risks
    helpText: |
      Create the artifact “DQ and risk register” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: hotspot-list
    label: Prioritized hotspot list
    plannedMinutes: 30
    dependsOn: rate-risks
    helpText: |
      Create the artifact “Prioritized hotspot list” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: top-risks
    label: Top risks
    type: textarea
    placeholder: Highest-priority risks

notes: true
```

```sprint
id: week-08
number: 8
title: Architecture Diagnosis
goal: Assess the current architecture and name bottlenecks.
dependsOn: week-03

flowVariant: linear
flowLayout: vertical
flowSteps:
  - Sources
  - Integration / platform
  - Transformation
  - Consumption / BI


stories:
  - slug: choosing-the-simplest-viable-architecture
    required: true
  - slug: beyond-bronze-silver-gold
    required: false
  - slug: big-five
    required: false
  - slug: platform-examples
    required: false

tasks:
  - id: review-architecture
    label: Review current architecture and tools
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Describe the current path from sources to consumption: warehouse/lakehouse, transforms, semantic layer, BI, orchestration.
      Note what is intentional architecture vs. accidental growth. Compare against the simplest viable shape for your needs.
      Watch for: reinventing bronze/silver/gold without answering business questions.
    linkedStories: choosing-the-simplest-viable-architecture, beyond-bronze-silver-gold, big-five, platform-examples
    helpLinks:
      - label: Architecture Fit Checklist
        href: /tools/architecture-fit
        description: Use the tool to assess current architecture, bottlenecks, and target shape against pragmatic criteria.
      - label: Microsoft Learn - Medallion architecture
        href: https://learn.microsoft.com/en-us/azure/databricks/lakehouse/medallion
        description: Use the explanation as a reference for Bronze/Silver/Gold zones and check whether they are really needed here.
  - id: document-bottlenecks
    label: Document bottlenecks and technical debt
    plannedMinutes: 120
    dependsOn: review-architecture
    assigneeType: team
    assigneeId: null
    helpText: |
      List bottlenecks: fragile jobs, long runtimes, unclear ownership, missing tests, tool sprawl, hosting constraints.
      Separate “hurts this quarter’s pilot” from “strategic debt”.
      Watch for: rewriting everything instead of isolating one improv able path.
    linkedStories: modernizing-an-existing-warehouse, host-vs-cloud, bridge-solution
deliverables:
  - id: architecture-notes
    label: Architecture diagnosis notes
    plannedMinutes: 180
    dependsOn: review-architecture
    helpText: |
      Create the artifact “Architecture diagnosis notes” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: bottleneck-list
    label: Bottleneck list
    plannedMinutes: 30
    dependsOn: document-bottlenecks
    helpText: |
      Create the artifact “Bottleneck list” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: architecture-findings
    label: Architecture findings
    type: textarea
    placeholder: Key findings

notes: true
```

```sprint
id: week-09
number: 9
title: Prioritization
goal: Prioritize actions by impact and feasibility.
dependsOn: week-07, week-08

stories:
  - slug: eight-pillars
    required: true
  - slug: bridge-solution
    required: false

tasks:
  - id: score-initiatives
    label: Score initiatives by impact and effort
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Turn findings into initiatives with rough impact (trust, speed, risk reduction) and effort (people, systems, dependencies).
      Prefer a small pilot that proves a pattern over a large platform rewrite.
      Watch for: scoring pet projects high because they are technically interesting.
    linkedStories: bridge-solution, choosing-the-simplest-viable-architecture
    helpLinks:
      - label: Impact–Effort Prioritizer
        href: /tools/impact-effort
        description: Use the tool to prioritize initiatives by impact, effort, risk, and dependencies.
      - label: Atlassian - Prioritization matrix
        href: https://www.atlassian.com/work-management/project-management/prioritization-matrix
        description: Use the examples to align impact-vs-effort scoring with stakeholders.
  - id: agree-priorities
    label: Agree priorities with stakeholders
    plannedMinutes: 120
    dependsOn: score-initiatives
    assigneeType: team
    assigneeId: null
    helpText: |
      Align sponsors on the pilot candidate and what will wait. Document trade-offs and the decision date.
      Confirm who can unblock people and systems during the pilot weeks.
      Watch for: silent disagreement that resurfaces after build starts.
    linkedStories: eight-pillars, data-ownership-stewardship
deliverables:
  - id: priority-matrix
    label: Prioritization matrix
    plannedMinutes: 120
    dependsOn: score-initiatives
    helpText: |
      Create the artifact “Prioritization matrix” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: quarter-backlog
    label: Quarter backlog agreed
    plannedMinutes: 180
    dependsOn: agree-priorities, priority-matrix
    helpText: |
      Create the artifact “Quarter backlog agreed” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: pilot-candidate
    label: Pilot candidate
    type: text
    placeholder: Selected initiative for the pilot

notes: true
```

```sprint
id: week-10
number: 10
title: Target Picture
goal: Sketch a clear target picture for reporting and the data platform.
dependsOn: week-09

stories:
  - slug: bridge-solution
    required: true
  - slug: dbt-role
    required: false
  - slug: self-hosted-data-platform
    required: false
  - slug: transformation-options
    required: false

tasks:
  - id: draft-target-picture
    label: Draft target picture and principles
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Describe the desired end state for this domain: ownership, KPI trust, lineage visibility, quality gates, and delivery pattern.
      Keep principles short and testable. Show what you will stop doing.
      Watch for: futuristic architecture slides with no near-term path.
    linkedStories: bridge-solution, choosing-the-simplest-viable-architecture, dbt-role, transformation-options
  - id: validate-target-picture
    label: Validate target picture with stakeholders
    plannedMinutes: 60
    dependsOn: draft-target-picture
    assigneeType: team
    assigneeId: null
    helpText: |
      Walk stakeholders through principles and the pilot slice. Confirm they can live with the constraints.
      Capture objections as backlog items, not as reasons to freeze.
      Watch for: approval theater without named owners for the target state.
    linkedStories: eight-pillars, data-ownership-stewardship
deliverables:
  - id: target-picture
    label: Target picture documented
    plannedMinutes: 120
    dependsOn: draft-target-picture, validate-target-picture
    helpText: |
      Create the artifact “Target picture documented” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: guiding-principles
    label: Guiding principles defined
    plannedMinutes: 60
    dependsOn: draft-target-picture
    helpText: |
      Create the artifact “Guiding principles defined” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: target-summary
    label: Target picture summary
    type: textarea
    placeholder: Short description of the desired end state

notes: true
```

```sprint
id: week-11
number: 11
title: Implement Pilot
goal: Implement the prioritized pilot within a limited scope.
dependsOn: week-10

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false

links:
  - label: Schema YML Editor
    href: /tools/schema-yml-editor
    description: Use the tool to maintain dbt schema YAML, column descriptions, and tests for the pilot scope.
  - label: Meta Export Generator
    href: /tools/meta-export-generator
    description: Use the tool to prepare reusable metadata exports from sources, fields, and owners.
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator
    description: Use the tool to turn observed data issues into testable quality checks.

tasks:
  - id: build-pilot
    label: Build the pilot
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    helpText: |
      Deliver the agreed slice end-to-end: reliable extract/transform, clear definition, basic tests, and a usable output for the target users.
      Keep scope ruthless. Document deviations daily.
      Watch for: expanding scope mid-build and skipping tests “until later”.
    linkedStories: building-from-scratch, dbt-role, dq-test-kpis
    helpLinks:
      - label: Schema YML Editor
        href: /tools/schema-yml-editor
        description: Use the tool to maintain dbt schema YAML, column descriptions, and tests for the pilot scope.
      - label: Meta Export Generator
        href: /tools/meta-export-generator
        description: Use the tool to prepare reusable metadata exports from sources, fields, and owners.
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Use the tool to turn observed data issues into testable quality checks.
      - label: dbt Docs - Data tests
        href: https://docs.getdbt.com/docs/build/data-tests
        description: Use the docs to turn quality assumptions into implementable dbt tests.
  - id: track-pilot-blockers
    label: Manage blockers and dependencies
    plannedMinutes: 60
    assigneeType: team
    assigneeId: null
    helpText: |
      Maintain a visible blocker list with owner and needed decision. Escalate early on access, data, and priority conflicts.
      Protect the pilot from unrelated “quick wins”.
      Watch for: silent waiting on unresolved access tickets.
    linkedStories: access-security-governance, data-ownership-stewardship
deliverables:
  - id: pilot-increment
    label: Pilot increment ready
    plannedMinutes: 30
    dependsOn: build-pilot
    helpText: |
      Create the artifact “Pilot increment ready” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: pilot-changelog
    label: Pilot change log
    plannedMinutes: 60
    dependsOn: build-pilot, track-pilot-blockers
    helpText: |
      Create the artifact “Pilot change log” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: pilot-scope
    label: Pilot scope
    type: textarea
    placeholder: What is in and out of scope

notes: true
```

```sprint
id: week-12
number: 12
title: Validate Pilot
goal: Verify the pilot's value, quality, and acceptance.
dependsOn: week-11

stories:
  - slug: dq-test-kpis
    required: true
  - slug: define-kpi
    required: false

tasks:
  - id: run-pilot-review
    label: Review pilot with users
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Review with real consumers: Can they answer the target question faster/safer? What still blocks trust?
      Capture concrete change requests vs. nice-to-haves.
      Watch for: demoing to sponsors only and skipping end users.
    linkedStories: bi-tools, define-kpi
  - id: measure-pilot-outcomes
    label: Measure outcomes and quality
    plannedMinutes: 60
    dependsOn: run-pilot-review
    assigneeType: team
    assigneeId: null
    helpText: |
      Measure against week-1 success criteria: freshness, defect rate, reconciliation results, time-to-insight, user confidence.
      Produce a clear go / conditional-go / no-go recommendation with evidence.
      Watch for: declaring success because “the pipeline runs”.
    linkedStories: dq-test-kpis, data-quality-governance
    helpLinks:
      - label: DQ History Generator
        href: /tools/dbt-dq-history-generator
        description: Use the tool to make DQ results traceable over time and support validation evidence.
      - label: GitHub Docs - Status checks
        href: https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/collaborating-on-repositories-with-code-quality-features/about-status-checks
        description: Use the docs as a reference for automated checks, review signals, and release quality.

deliverables:
  - id: validation-report
    label: Validation report
    plannedMinutes: 120
    dependsOn: run-pilot-review, measure-pilot-outcomes
    helpText: |
      Create the artifact “Validation report” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: go-nogo-recommendation
    label: Go/No-Go recommendation
    plannedMinutes: 60
    dependsOn: validation-report
    helpText: |
      Create the artifact “Go/No-Go recommendation” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: validation-notes
    label: Validation notes
    type: textarea
    placeholder: Feedback and metrics

notes: true
```

```sprint
id: week-13
number: 13
title: Quarter Close
goal: Secure results, capture lessons, and prepare the next quarter.
dependsOn: week-12

stories:
  - slug: eight-pillars
    required: true
  - slug: dsdr-governance
    required: false
  - slug: operating-and-governing-the-platform
    required: false

tasks:
  - id: summarize-quarter
    label: Summarize quarter results
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Summarize mandate, findings, pilot outcome, remaining risks, and decisions made. Keep it readable for sponsors.
      Attach the artifacts (inventory, KPI defs, lineage sketch, risk register) as lasting assets.
      Watch for: burying bad news or unfinished ownership in appendices.
    linkedStories: eight-pillars, dsdr-governance
  - id: plan-next-quarter
    label: Roughly plan the next quarter
    plannedMinutes: 180
    dependsOn: summarize-quarter
    assigneeType: team
    assigneeId: null
    helpText: |
      Propose the next 1–3 initiatives based on remaining hotspots and the validated target picture.
      Reconfirm owners, capacity, and what must stop to make room.
      Watch for: restarting discovery from zero instead of extending proven patterns.
    linkedStories: bridge-solution, modernizing-an-existing-warehouse, operating-and-governing-the-platform
deliverables:
  - id: quarter-report
    label: Quarter report
    plannedMinutes: 120
    dependsOn: summarize-quarter
    helpText: |
      Create the artifact “Quarter report” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.
  - id: next-quarter-outline
    label: Outline for the next quarter
    plannedMinutes: 180
    dependsOn: plan-next-quarter, quarter-report
    helpText: |
      Create the artifact “Outline for the next quarter” with purpose, owner, date, source, and open decision.
      The deliverable is done when it supports a concrete decision or handoff and does not only collect raw notes.

fields:
  - id: lessons-learned
    label: Lessons learned
    type: textarea
    placeholder: What to keep, what to change

notes: true
```
