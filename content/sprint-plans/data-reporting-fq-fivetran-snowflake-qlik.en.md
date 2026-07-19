---
type: sprint-plan
title: First Quarter — Fivetran → Snowflake → dbt → Qlik
slug: data-reporting-fq-fivetran-snowflake-qlik
description: Standard path: sources via Fivetran into Snowflake, transform with dbt, consume in Qlik.
duration: 13
unit: week
category: Data Platform
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Fivetran
  - Snowflake
  - dbt
  - Qlik
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
  - Source
  - Fivetran
  - Snowflake
  - dbt
  - Qlik



stories:
  - slug: data-ownership-stewardship
    required: true
  - slug: missing-pieces-ownership-stewardship
    required: false

tasks:
  - id: align-management-expectations
    label: Align expectations with leadership
    assigneeType: person
    assigneeId: null
    helpText: |
      Clarify why this quarter exists: which decisions should improve, which pain is unacceptable, and what “good enough” looks like after 13 weeks.
      Capture success criteria in writing (not only in meetings). Ask explicitly what is out of scope.
      Watch for: vague goals (“better reporting”), hidden compliance pressure, and conflicting sponsors.
    linkedStories: data-ownership-stewardship, eight-pillars
    helpLinks:
      - label: Data Ownership & Stewardship
        href: /playbooks/data-ownership-stewardship
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars
  - id: identify-stakeholders
    label: Identify relevant stakeholders
    assigneeType: team
    assigneeId: null
    helpText: |
      Map decision makers, report consumers, data producers, and platform owners. Prefer named people over departments.
      Note influence, interest, and who can block access to systems or definitions.
      Watch for: “everyone” as a stakeholder list, and missing operational owners for source data.
    linkedStories: data-ownership-stewardship, missing-pieces-ownership-stewardship
    helpLinks:
      - label: Missing Pieces – Ownership & Stewardship
        href: /playbooks/missing-pieces-ownership-stewardship

deliverables:
  - id: stakeholder-list
    label: Stakeholder list created
    helpText: |
      Deliverable “Stakeholder list created”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: initial-mandate
    label: Initial mandate documented
    helpText: |
      Deliverable “Initial mandate documented”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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
description: Focus: connector inventory, Snowflake roles/layers, dbt tests/docs, Qlik as consumer — not as KPI owner.

stories:
  - slug: bi-tools
    required: true
  - slug: one-app
    required: false

tasks:
  - id: inventory-reports
    label: Inventory existing reports
    assigneeType: person
    assigneeId: null
    helpText: |
      List active reports/dashboards with owner, tool, refresh cadence, and business question answered.
      Mark critical vs. rarely used. Prefer evidence (usage logs, interviews) over assumptions.
      Watch for: shadow Excel reports, duplicated KPIs under different names, and orphaned dashboards.
    linkedStories: bi-tools, one-app
    helpLinks:
      - label: One Business Question, Different BI Engines
        href: /playbooks/bi-tools
      - label: One App Cannot Answer Every Question
        href: /playbooks/one-app
  - id: map-report-consumers
    label: Map report consumers and usage frequency
    assigneeType: team
    assigneeId: null
    helpText: |
      For critical reports, document who uses them, for which decision, and how often.
      Separate “nice to have” from “blocks operations or board reporting”.
      Watch for: consumers who never open the report but block changes, and unknown distribution lists.
    linkedStories: bi-tools, one-app
    helpLinks:
      - label: BI tools overview
        href: /playbooks/bi-tools

deliverables:
  - id: report-inventory
    label: Report inventory documented
    helpText: |
      Deliverable “Report inventory documented”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: gap-list
    label: Initial gap list created
    helpText: |
      Deliverable “Initial gap list created”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: sap-overview
    required: false

tasks:
  - id: list-source-systems
    label: Capture source systems and owners
    assigneeType: person
    assigneeId: null
    helpText: |
      Inventory systems that feed reporting: ERP, CRM, finance, files, APIs. Record system owner and data steward if known.
      Rank by criticality for the reports you already inventoried.
      Watch for: unofficial databases, shared drives as “systems of record”, and unclear SAP vs. satellite ownership.
    linkedStories: before-building-the-first-table, sap-overview
    helpLinks:
      - label: Before Building the First Table
        href: /playbooks/before-building-the-first-table
      - label: SAP Data & Analytics Stack
        href: /playbooks/sap-overview
  - id: document-interfaces
    label: Document interfaces and extraction paths
    assigneeType: team
    assigneeId: null
    helpText: |
      For priority sources, document how data leaves the system (API, CDC, batch, file, manual export) and who operates it.
      Note latency, failure modes, and whether history is preserved.
      Watch for: undocumented Excel extracts and “someone runs a script on Fridays”.
    linkedStories: before-building-the-first-table, building-from-scratch
    helpLinks:
      - label: Building a Warehouse from Scratch
        href: /playbooks/building-from-scratch

deliverables:
  - id: source-system-map
    label: Source system map created
    helpText: |
      Deliverable “Source system map created”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: owner-matrix
    label: Owner matrix created
    helpText: |
      Deliverable “Owner matrix created”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: trash-iinout
    required: false

tasks:
  - id: trace-data-creation
    label: Trace data creation in core processes
    assigneeType: person
    assigneeId: null
    helpText: |
      Walk the business process that creates the data behind critical reports. Who enters what, when, and under which incentive?
      Sketch process → system fields → report fields for 1–3 critical flows.
      Watch for: late corrections in Excel, optional fields that drive KPIs, and process exceptions that never reach the warehouse.
    linkedStories: before-building-the-first-table, trash-iinout
    helpLinks:
      - label: Trash In, Trash Out
        href: /playbooks/trash-iinout
      - label: Before Building the First Table
        href: /playbooks/before-building-the-first-table
  - id: capture-business-rules
    label: Document business rules and exceptions
    assigneeType: team
    assigneeId: null
    helpText: |
      Capture calculation and eligibility rules in plain language, including known exceptions and who may approve them.
      Prefer examples with real edge cases over abstract definitions.
      Watch for: “everybody knows” rules that exist only in people’s heads.
    linkedStories: define-kpi, missing-pieces-trusted-metrics
    helpLinks:
      - label: KPI Definition, Ownership and Versioning
        href: /playbooks/define-kpi

deliverables:
  - id: creation-notes
    label: Data creation notes
    helpText: |
      Deliverable “Data creation notes”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: rule-summary
    label: Business rule summary
    helpText: |
      Deliverable “Business rule summary”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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
    assigneeType: person
    assigneeId: null
    helpText: |
      Draw source → staging/transform → semantic/KPI → report for the priority flows. Keep it rough but end-to-end.
      Name systems, jobs, and owners where known; mark unknowns explicitly.
      Watch for: false confidence from tool lineage that only covers part of the path.
    linkedStories: metadata-catalog-lineage, missing-pieces-metadata-catalog-lineage
    helpLinks:
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
      - label: Missing Pieces – Metadata, Catalog & Lineage
        href: /playbooks/missing-pieces-metadata-catalog-lineage
  - id: identify-lineage-gaps
    label: Mark lineage gaps and blind spots
    assigneeType: team
    assigneeId: null
    helpText: |
      List breaks: manual steps, undocumented transforms, renamed fields, multiple competing definitions.
      Prioritize gaps that affect trust in critical KPIs or compliance-sensitive data.
      Watch for: ignoring “temporary” transforms that have existed for years.
    linkedStories: metadata-catalog-lineage, missing-pieces-metadata-catalog-lineage
    helpLinks:
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage

deliverables:
  - id: lineage-sketch
    label: Lineage sketch created
    helpText: |
      Deliverable “Lineage sketch created”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: lineage-gap-log
    label: Lineage gap log
    helpText: |
      Deliverable “Lineage gap log”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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
    assigneeType: person
    assigneeId: null
    helpText: |
      Collect KPI names as used in reports and conversations. Record formula candidates, grain, filters, and owner guesses.
      Deduplicate synonyms early (“revenue”, “turnover”, “sales”).
      Watch for: KPIs without an owner and metrics that only exist as dashboard titles.
    linkedStories: define-kpi, kpi-metric-governance
    helpLinks:
      - label: KPI Definition, Ownership and Versioning
        href: /playbooks/define-kpi
      - label: KPI & Metric Governance
        href: /playbooks/kpi-metric-governance
  - id: normalize-definitions
    label: Align definitions and calculation rules
    assigneeType: team
    assigneeId: null
    helpText: |
      For top KPIs, agree one definition, one owner, and one calculation path. Document open disputes instead of papering over them.
      Version the definition if the business still needs the old variant temporarily.
      Watch for: “average of everyone’s number” as a compromise.
    linkedStories: define-kpi, missing-pieces-trusted-metrics
    helpLinks:
      - label: Trusted Metrics (Missing Pieces)
        href: /playbooks/missing-pieces-trusted-metrics

deliverables:
  - id: kpi-inventory
    label: KPI inventory created
    helpText: |
      Deliverable “KPI inventory created”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: definition-backlog
    label: Definition backlog prioritized
    helpText: |
      Deliverable “Definition backlog prioritized”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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
  - label: DQ Macro Generator
    href: /tools/dbt-dq-macro-generator

tasks:
  - id: assess-dq-issues
    label: Assess known DQ issues
    assigneeType: person
    assigneeId: null
    helpText: |
      Collect known defects: completeness, uniqueness, freshness, consistency across systems. Score impact on critical KPIs.
      Prefer measurable symptoms over anecdotes. Note where tests already exist vs. only tribal knowledge.
      Watch for: fixing cosmetics while core keys and dates remain unreliable.
    linkedStories: data-quality-governance, dq-test-kpis, missing-pieces-data-quality
    helpLinks:
      - label: Data Quality Governance
        href: /playbooks/data-quality-governance
      - label: From Tests to Measurable Data Quality
        href: /playbooks/dq-test-kpis
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
      - label: DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
  - id: rate-risks
    label: Rate business and compliance risks
    assigneeType: team
    assigneeId: null
    helpText: |
      Rate risks for wrong decisions, regulatory exposure, and PII/privacy. Separate likelihood from impact.
      Flag access and retention issues that block a safe pilot.
      Watch for: treating every data bug as equal urgency.
    linkedStories: pii-privacy-governance, access-security-governance, eight-pillars
    helpLinks:
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
      - label: Access & Security Governance
        href: /playbooks/access-security-governance
      - label: PII Policy Generator
        href: /tools/pii-policy-generator

deliverables:
  - id: dq-risk-register
    label: DQ and risk register
    helpText: |
      Deliverable “DQ and risk register”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: hotspot-list
    label: Prioritized hotspot list
    helpText: |
      Deliverable “Prioritized hotspot list”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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
flowVariant: linear
flowLayout: vertical
flowSteps:
  - Source
  - Fivetran
  - Snowflake
  - dbt
  - Qlik



stories:
  - slug: choosing-the-simplest-viable-architecture
    required: true
  - slug: beyond-bronze-silver-gold
    required: false
  - slug: big-five
    required: false

tasks:
  - id: review-architecture
    label: Review current architecture and tools
    assigneeType: person
    assigneeId: null
    helpText: |
      Describe the current path from sources to consumption: warehouse/lakehouse, transforms, semantic layer, BI, orchestration.
      Note what is intentional architecture vs. accidental growth. Compare against the simplest viable shape for your needs.
      Watch for: reinventing bronze/silver/gold without answering business questions.
    linkedStories: choosing-the-simplest-viable-architecture, beyond-bronze-silver-gold, big-five
    helpLinks:
      - label: Choosing the Simplest Viable Architecture
        href: /playbooks/choosing-the-simplest-viable-architecture
      - label: Beyond Bronze, Silver and Gold
        href: /playbooks/beyond-bronze-silver-gold
      - label: BIG 5 Stacks Overview
        href: /playbooks/big-five
  - id: document-bottlenecks
    label: Document bottlenecks and technical debt
    assigneeType: team
    assigneeId: null
    helpText: |
      List bottlenecks: fragile jobs, long runtimes, unclear ownership, missing tests, tool sprawl, hosting constraints.
      Separate “hurts this quarter’s pilot” from “strategic debt”.
      Watch for: rewriting everything instead of isolating one improv able path.
    linkedStories: modernizing-an-existing-warehouse, host-vs-cloud, bridge-solution
    helpLinks:
      - label: Modernizing an Existing Warehouse
        href: /playbooks/modernizing-an-existing-warehouse
      - label: Cloud vs. Self-Hosted
        href: /playbooks/host-vs-cloud

deliverables:
  - id: architecture-notes
    label: Architecture diagnosis notes
    helpText: |
      Deliverable “Architecture diagnosis notes”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: bottleneck-list
    label: Bottleneck list
    helpText: |
      Deliverable “Bottleneck list”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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

stories:
  - slug: eight-pillars
    required: true
  - slug: bridge-solution
    required: false

tasks:
  - id: score-initiatives
    label: Score initiatives by impact and effort
    assigneeType: person
    assigneeId: null
    helpText: |
      Turn findings into initiatives with rough impact (trust, speed, risk reduction) and effort (people, systems, dependencies).
      Prefer a small pilot that proves a pattern over a large platform rewrite.
      Watch for: scoring pet projects high because they are technically interesting.
    linkedStories: bridge-solution, choosing-the-simplest-viable-architecture
    helpLinks:
      - label: Bridge Solutions
        href: /playbooks/bridge-solution
      - label: Simplest Viable Architecture
        href: /playbooks/choosing-the-simplest-viable-architecture
  - id: agree-priorities
    label: Agree priorities with stakeholders
    assigneeType: team
    assigneeId: null
    helpText: |
      Align sponsors on the pilot candidate and what will wait. Document trade-offs and the decision date.
      Confirm who can unblock people and systems during the pilot weeks.
      Watch for: silent disagreement that resurfaces after build starts.
    linkedStories: eight-pillars, data-ownership-stewardship
    helpLinks:
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars

deliverables:
  - id: priority-matrix
    label: Prioritization matrix
    helpText: |
      Deliverable “Prioritization matrix”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: quarter-backlog
    label: Quarter backlog agreed
    helpText: |
      Deliverable “Quarter backlog agreed”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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

stories:
  - slug: bridge-solution
    required: true
  - slug: dbt-role
    required: false
  - slug: self-hosted-data-platform
    required: false

tasks:
  - id: draft-target-picture
    label: Draft target picture and principles
    assigneeType: person
    assigneeId: null
    helpText: |
      Describe the desired end state for this domain: ownership, KPI trust, lineage visibility, quality gates, and delivery pattern.
      Keep principles short and testable. Show what you will stop doing.
      Watch for: futuristic architecture slides with no near-term path.
    linkedStories: bridge-solution, choosing-the-simplest-viable-architecture, dbt-role
    helpLinks:
      - label: Bridge Solutions
        href: /playbooks/bridge-solution
      - label: The Role of dbt
        href: /playbooks/dbt-role
      - label: Self-Hosted Data Platforms
        href: /playbooks/self-hosted-data-platform
  - id: validate-target-picture
    label: Validate target picture with stakeholders
    assigneeType: team
    assigneeId: null
    helpText: |
      Walk stakeholders through principles and the pilot slice. Confirm they can live with the constraints.
      Capture objections as backlog items, not as reasons to freeze.
      Watch for: approval theater without named owners for the target state.
    linkedStories: eight-pillars, data-ownership-stewardship
    helpLinks:
      - label: Data Ownership & Stewardship
        href: /playbooks/data-ownership-stewardship

deliverables:
  - id: target-picture
    label: Target picture documented
    helpText: |
      Deliverable “Target picture documented”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: guiding-principles
    label: Guiding principles defined
    helpText: |
      Deliverable “Guiding principles defined”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false

links:
  - label: Schema YML Editor
    href: /tools/schema-yml-editor
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator

tasks:
  - id: build-pilot
    label: Build the pilot
    assigneeType: person
    assigneeId: null
    helpText: |
      Deliver the agreed slice end-to-end: reliable extract/transform, clear definition, basic tests, and a usable output for the target users.
      Keep scope ruthless. Document deviations daily.
      Watch for: expanding scope mid-build and skipping tests “until later”.
    linkedStories: building-from-scratch, dbt-role, dq-test-kpis
    helpLinks:
      - label: Building a Warehouse from Scratch
        href: /playbooks/building-from-scratch
      - label: The Role of dbt
        href: /playbooks/dbt-role
      - label: Schema YML Editor
        href: /tools/schema-yml-editor
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
  - id: track-pilot-blockers
    label: Manage blockers and dependencies
    assigneeType: team
    assigneeId: null
    helpText: |
      Maintain a visible blocker list with owner and needed decision. Escalate early on access, data, and priority conflicts.
      Protect the pilot from unrelated “quick wins”.
      Watch for: silent waiting on unresolved access tickets.
    linkedStories: access-security-governance, data-ownership-stewardship
    helpLinks:
      - label: Access & Security Governance
        href: /playbooks/access-security-governance

deliverables:
  - id: pilot-increment
    label: Pilot increment ready
    helpText: |
      Deliverable “Pilot increment ready”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: pilot-changelog
    label: Pilot change log
    helpText: |
      Deliverable “Pilot change log”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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

stories:
  - slug: dq-test-kpis
    required: true
  - slug: define-kpi
    required: false

tasks:
  - id: run-pilot-review
    label: Review pilot with users
    assigneeType: person
    assigneeId: null
    helpText: |
      Review with real consumers: Can they answer the target question faster/safer? What still blocks trust?
      Capture concrete change requests vs. nice-to-haves.
      Watch for: demoing to sponsors only and skipping end users.
    linkedStories: bi-tools, define-kpi
    helpLinks:
      - label: KPI Definition, Ownership and Versioning
        href: /playbooks/define-kpi
      - label: BI tools overview
        href: /playbooks/bi-tools
  - id: measure-pilot-outcomes
    label: Measure outcomes and quality
    assigneeType: team
    assigneeId: null
    helpText: |
      Measure against week-1 success criteria: freshness, defect rate, reconciliation results, time-to-insight, user confidence.
      Produce a clear go / conditional-go / no-go recommendation with evidence.
      Watch for: declaring success because “the pipeline runs”.
    linkedStories: dq-test-kpis, data-quality-governance
    helpLinks:
      - label: From Tests to Measurable Data Quality
        href: /playbooks/dq-test-kpis
      - label: DQ History Generator
        href: /tools/dbt-dq-history-generator

deliverables:
  - id: validation-report
    label: Validation report
    helpText: |
      Deliverable “Validation report”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: go-nogo-recommendation
    label: Go/No-Go recommendation
    helpText: |
      Deliverable “Go/No-Go recommendation”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

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

stories:
  - slug: eight-pillars
    required: true
  - slug: dsdr-governance
    required: false

tasks:
  - id: summarize-quarter
    label: Summarize quarter results
    assigneeType: person
    assigneeId: null
    helpText: |
      Summarize mandate, findings, pilot outcome, remaining risks, and decisions made. Keep it readable for sponsors.
      Attach the artifacts (inventory, KPI defs, lineage sketch, risk register) as lasting assets.
      Watch for: burying bad news or unfinished ownership in appendices.
    linkedStories: eight-pillars, dsdr-governance
    helpLinks:
      - label: The 8 Pillars of Data Governance
        href: /playbooks/eight-pillars
      - label: DSDR Governance
        href: /playbooks/dsdr-governance
  - id: plan-next-quarter
    label: Roughly plan the next quarter
    assigneeType: team
    assigneeId: null
    helpText: |
      Propose the next 1–3 initiatives based on remaining hotspots and the validated target picture.
      Reconfirm owners, capacity, and what must stop to make room.
      Watch for: restarting discovery from zero instead of extending proven patterns.
    linkedStories: bridge-solution, modernizing-an-existing-warehouse
    helpLinks:
      - label: Bridge Solutions
        href: /playbooks/bridge-solution
      - label: Modernizing an Existing Warehouse
        href: /playbooks/modernizing-an-existing-warehouse

deliverables:
  - id: quarter-report
    label: Quarter report
    helpText: |
      Deliverable “Quarter report”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.
  - id: next-quarter-outline
    label: Outline for the next quarter
    helpText: |
      Deliverable “Outline for the next quarter”: keep it short, traceable, and with owner/date.
      Use it as evidence for decisions — not as a dump of raw notes.

fields:
  - id: lessons-learned
    label: Lessons learned
    type: textarea
    placeholder: What to keep, what to change

notes: true
```
