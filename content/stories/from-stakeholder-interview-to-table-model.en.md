---
title: From Stakeholder Interview to Table Model
description: Convert interview evidence into explicit decisions about business events, grain, facts, dimensions, history, ownership and scope before building a physical mart.
author: Thomas Lindackers
tags:
  - data-modeling
  - dimensional-modeling
  - grain
  - stakeholder-interviews
  - mart-design
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/from-stakeholder-interview-to-table-model-hero.png
series: building-modern-data-warehouse
seriesTitle: Building a Modern Data Warehouse
seriesPart: 11
---

A stakeholder interview produces evidence, language, expectations and unresolved questions. It does not produce a table model. The modeling work starts when those statements are converted into explicit, reviewable decisions about the business process, business event, grain, measures, dimensions, history, ownership and scope.

![From stakeholder evidence to a reviewable table model](images/playbooks/from-stakeholder-interview-to-table-model-hero.png)

## Problem

Stakeholders rarely describe requirements in modeling terms. They ask for “revenue by customer,” “the current pipeline,” “all activities,” “one row per customer,” or “the same numbers as the management report.” Each statement may contain useful evidence, but none of them is precise enough to define a fact table.

The same noun can refer to different analytical objects. “Customer” may mean the contractual account, the invoice recipient, a legal entity, a prospect or a parent group. “Revenue” may refer to booked revenue, invoiced revenue, recognized revenue, expected revenue or probability-weighted pipeline. “Current” may mean the latest source state, the state at report execution time or the state at a selected historical date.

A report inventory often reveals that these ambiguities already exist in production. Two reports may use the same label but different filters. Several reports may calculate the same KPI differently. One report may mix transaction data with the latest status, while another reconstructs historical snapshots. Copying those structures into a new mart preserves the conflict instead of resolving it.

Typical failure modes are predictable:

- Interview nouns are copied directly into table and column names.
- Dimensions are selected before the grain is defined.
- Several business processes are combined in one fact table.
- “One row per customer” is accepted without a time meaning.
- Difficult requirements disappear without a documented decision.
- A current report layout is treated as the target model.
- KPI formulas are implemented before ownership and approval are clear.

The result may look technically complete while remaining semantically unstable. Measures become ambiguous, history cannot be explained and later changes reopen decisions that were never recorded.

![Turn interview statements into modeling decisions](images/playbooks/from-stakeholder-interview-to-table-model-img1-en.png)

## Decision

Treat every interview statement as evidence that must pass through a controlled decision chain:

```text
Stakeholder statement
→ Clarification
→ Modeling decision
→ Recorded evidence and approval
```

The core rule is simple: identify the business event and write one precise grain sentence before selecting facts or dimensions.

A useful grain sentence names the event, the participating object and the time semantics. For example:

> One row per opportunity line item per snapshot date.

This statement is materially different from “one row per opportunity” or “one row per customer.” It defines a periodic snapshot at line-item level. It determines which measures can coexist, which dimensions are valid and how historical analysis works.

From this grain, the model can evaluate candidates such as:

- **Additive facts:** quantity or expected revenue when summation is valid across the intended dimensions.
- **Semi-additive facts:** balances or pipeline values that may be summed across some dimensions but not across time.
- **Status attributes:** stage, approval state or risk category when they describe the row state rather than an event quantity.
- **Dimensions:** account, opportunity, product, owner, stage and date when they provide reusable analytical context.
- **Degenerate dimensions:** business identifiers such as an opportunity number that belong to the fact event but do not require a separate descriptive dimension.
- **Filters and rules:** inclusion criteria, status restrictions, currency rules and time-window semantics.
- **Explicit exclusions:** activities, free-text notes, unrelated service cases or other data that does not support the approved decision.

Do not mix transaction grain and snapshot grain in one fact merely because both relate to the same business object. A transaction records that something happened. A snapshot records a state at a defined point in time. Combining both creates measures that cannot be interpreted consistently.

![Define grain before facts and dimensions](images/playbooks/from-stakeholder-interview-to-table-model-img2-en.png)

### Separate evidence, interpretation and decision

For each relevant interview statement, record three distinct layers:

1. **Evidence:** what the stakeholder said, which report was shown, which KPI Card exists and which source behavior was observed.
2. **Interpretation:** what the team believes the statement means and which ambiguity still exists.
3. **Decision:** the approved business process, event, grain, measure definition, dimension, history treatment or scope-out.

This separation prevents interpretation from being presented as stakeholder fact. It also makes later review possible when a report, owner or source contradicts the initial assumption.

### Connect KPI semantics to target grain

A KPI Card should define more than a formula. It must clarify:

- business meaning and decision supported
- numerator, denominator and aggregation behavior
- filters and exclusions
- effective date and time semantics
- currency, unit and conversion rules
- target grain and allowed drill paths
- calculation owner and approving Data Owner
- quality expectations and known limitations

A KPI can only be implemented safely when its calculation is compatible with the mart grain. A monthly conversion rate, a line-item snapshot and a transaction-level event may require separate structures even when they appear on the same dashboard.

### Use roles as decision boundaries

The stakeholder matrix and RACI do not replace modeling. They establish who may provide evidence, define terminology, make business decisions, design the model and approve the result.

- The **Data Owner** approves business meaning, permitted use, material scope and acceptance of unresolved risk.
- The **Data Steward** maintains terminology, definitions, classifications and semantic consistency.
- The **Data Architect** converts approved semantics into grain, fact, dimension, history and contract decisions.
- Source and platform specialists confirm technical evidence, feasibility and operational constraints.
- Consumers validate that the resulting mart supports the intended decision without importing every report-specific preference.

The detailed role mechanics remain in the linked RACI and role playbooks. This story uses them to make the model decision reviewable.

## Checklist

Use this checklist before approving a mart design:

### Evidence

- [ ] Interview statements are stored separately from interpretations.
- [ ] The report inventory identifies duplicate, conflicting and report-specific requirements.
- [ ] Existing KPI Cards and definitions are linked.
- [ ] Source evidence is named and traceable.
- [ ] Open questions have an owner and required decision date or trigger.

### Business event and grain

- [ ] One business process is named.
- [ ] The business event is explicit.
- [ ] The grain is written as one precise sentence.
- [ ] Time semantics are explicit: transaction time, valid time, snapshot date or current state.
- [ ] The grain is compatible with the intended KPI calculations.
- [ ] Transaction and snapshot processes are not silently mixed.

### Facts and dimensions

- [ ] Each fact has a defined meaning, unit and aggregation behavior.
- [ ] Status attributes are not misclassified as additive measures.
- [ ] Dimensions describe the declared grain.
- [ ] Conformed dimensions and keys are identified where cross-mart reuse is required.
- [ ] Degenerate dimensions are used deliberately for business identifiers.
- [ ] Historical behavior is specified for facts and dimensions.

### Governance and scope

- [ ] Data Owner, Steward, Architect and approvers are named.
- [ ] PII, access and permitted-use requirements are recorded.
- [ ] Quality checks are linked to business acceptance criteria.
- [ ] Assumptions are visible.
- [ ] Deferred and excluded requirements include rationale, evidence, owner and review trigger.
- [ ] No difficult requirement has been silently removed.

## Artifact

The primary output is a **Mart Design Brief** (`mart-design-brief.md`). It is the reviewable contract between interview evidence and physical table creation. Export KPI cards as `kpi-cards.csv` and the approved scope as `source-scope.csv`.

![From KPI Card and RACI to a Mart Design Brief](images/playbooks/from-stakeholder-interview-to-table-model-img3-en.png)

A useful Mart Design Brief contains:

```yaml
mart:
  name: sales_pipeline_snapshot
  purpose: Support pipeline value, coverage and stage-movement decisions
  consumers:
    - sales-management
    - account-management

business_process:
  name: opportunity-pipeline-monitoring
  event: opportunity-line-state-at-snapshot
  grain: One row per opportunity line item per snapshot date

facts:
  - name: quantity
    aggregation: additive
    owner: sales-operations
  - name: expected_revenue
    aggregation: additive-within-currency
    owner: finance-and-sales-operations
  - name: probability_weighted_revenue
    aggregation: derived
    calculation_owner: sales-operations

dimensions:
  - account
  - opportunity
  - product
  - owner
  - stage
  - snapshot_date

history:
  pattern: periodic-snapshot
  cadence: daily
  late_arriving_policy: documented-before-build

quality:
  - opportunity_line_identifier_is_present
  - snapshot_date_is_present
  - probability_is_within_valid_range
  - expected_revenue_currency_is_known

security:
  pii: assessed
  access: role-and-region-policy

scope:
  included:
    - opportunity-line-state
    - approved-pipeline-measures
  deferred:
    - activity-engagement-score
  excluded:
    - free-text-notes
    - unrelated-service-cases

assumptions:
  - source-stage-values-use-approved-mapping

approvals:
  data_owner: named-person-or-role
  steward: named-person-or-role
  architect: named-person-or-role
```

The final shape may differ, but the brief must answer the same decision questions. It should be approved before physical tables, orchestration or report migration begins.

### Record scope-out as a governed decision

Scope-out is not a backlog graveyard. Every requested item belongs in one of three lanes:

- **Build now:** required for the first approved decision, compatible with the grain, supported by a known source and owner, and testable through acceptance criteria.
- **Defer:** a valid need that lacks a source, owner, definition or immediate priority, with a named trigger for reconsideration.
- **Exclude:** unsupported, duplicate, low-value, grain-incompatible or unjustified in relation to PII, risk or cost.

Each deferred or excluded item should record its rationale, decision owner, evidence, review trigger and affected consumer.

![Keep scope-out explicit](images/playbooks/from-stakeholder-interview-to-table-model-img4-en.png)

## Tools

Use the existing tools to collect and structure evidence:

- [Stakeholder Matrix](/tools/stakeholder-matrix) — identify evidence providers, decision owners, reviewers and affected consumers.
- [Report Inventory](/tools/report-inventory) — compare current reports, formulas, filters, grains and contradictions.
- [KPI Requirements Intake](/tools/kpi-requirements-intake) — capture KPI meaning, filters, time semantics, ownership and approval.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — convert the approved decisions into a reviewable mart contract.

These tools support the decision process. They do not replace owner approval or architectural judgment.

## Resources

Keep the following evidence attached to the Mart Design Brief:

- interview notes with source, date and participant role
- linked report examples and screenshots
- report inventory entries with conflicting calculations highlighted
- approved KPI Cards
- source-field examples and value distributions where relevant
- terminology decisions and unresolved synonyms
- assumptions, open questions and scope-out register
- approval record and decision history

A model review should be able to trace every material field or calculation back to evidence and an explicit decision.

## Playbooks

Reuse these existing playbooks as decision inputs:

- [Before Building the First Table](/playbooks/before-building-the-first-table) — use the foundational sequence for confirming the business question, process and decision before implementation.
- [Define a KPI](/playbooks/define-kpi) — establish KPI semantics, filters, time behavior, ownership and acceptance.
- [RACI for Data Governance](/playbooks/raci-for-data-governance) — assign responsibility, accountability, consultation and information duties for the decision.
- [Data Architect Role](/playbooks/data-architect-role) — preserve the boundary between business approval, stewardship, architecture and platform execution.

This article does not replace those playbooks. It connects their outputs to one specific deliverable: a reviewable table-model decision.

## Next step

Approve the Mart Design Brief before creating the physical mart. The next implementation step should use the approved business event, grain, facts, dimensions, history, controls and scope as constraints—not reopen them implicitly in SQL, dbt models, semantic layers or dashboard logic.

A design is ready to build when reviewers can answer five questions without inspecting code:

1. Which decision does the mart support?
2. What exactly does one row represent?
3. Which measures are valid at that grain?
4. Who owns the meaning and approval?
5. What is intentionally not included, and why?

When those answers are explicit, the table model becomes a governed contract rather than an undocumented interpretation of an interview.
