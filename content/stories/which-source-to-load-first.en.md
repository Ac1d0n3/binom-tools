---
title: "Which Source Should Load First?"
description: "Select the first governed source by combining decision value, authority, grain readiness, ownership, access, quality and learning value in the smallest complete vertical slice."
author: Thomas Lindackers
tags:
  - Source Prioritization
  - Vertical Slice
  - Source Scope
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/which-source-to-load-first-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 3
---

The first source in a data platform should not be selected because its connector is already licensed or because it contains the largest number of tables. It should be the smallest complete source slice that produces a trusted decision and validates the operating model end to end.

The output is a governed portfolio decision: start one source now, prepare high-value sources that are not ready, and defer sources whose value and readiness do not justify the next implementation slot.

## Problem

Most source roadmaps are initially ordered by convenience. A connector is available, a sponsor is visible, or a large application appears important, so ingestion starts before decision, authority, grain and consumer outcome are known.

![Do not start with the easiest connector](images/playbooks/which-source-to-load-first-img1-en.png)

The convenience-first path is usually:

```text
Available Connector
→ Large Raw Load
→ Unclear Consumer
→ Late Governance Questions
→ Rework
```

Weak selection signals include:

- the connector is already licensed;
- the source has the largest table count;
- an executive sponsor requests visibility;
- extraction effort looks low;
- the source appears technically clean;
- the team wants to prove that data can be moved.

None of those signals establishes that the source can produce a trusted business outcome. A technically successful raw load may still fail because the entity authority is unresolved, the fact grain is unknown, access is not approved, history is incomplete or no consumer uses the result.

### Readiness and priority are different

A source can be high priority but not ready. Another can be easy to extract but have little decision value. Treating these dimensions as one score hides the actual action:

- **High value, high readiness:** start now.
- **High value, low readiness:** prepare ownership, access, keys or quality evidence.
- **Lower value, high readiness:** use only when it provides deliberate reusable learning.
- **Lower value, low readiness:** defer.

## Decision

Use a decision-first sequence:

```text
Named Decision
→ Candidate Sources
→ Authority and Grain Check
→ Readiness and Risk Check
→ Smallest Complete Slice
→ Trusted Outcome
```

### 1. Define the outcome before the source

Name the user, decision, action, metric, population and time horizon. “Onboard the CRM” is not an outcome. “Sales leadership decides where to intervene in open pipeline by opportunity and accountable owner each morning” is.

The statement must identify the consumer and the action that changes when the source becomes trusted. It also defines the acceptance criteria for the vertical slice.

### 2. Score decision value

Assess each candidate against:

- named user and action;
- measurable business impact;
- urgency or control need;
- reuse across products;
- executive, operational or regulatory criticality;
- ability to reconcile the result with an existing process.

High visibility without a defined action is not high value.

### 3. Score source readiness

![Score source readiness and decision value](images/playbooks/which-source-to-load-first-img2-en.png)

Assess readiness independently:

- authority is understood;
- grain, keys and relationships are known;
- Data Owner and Steward are available;
- access, PII and permitted use are approved;
- quality can be measured;
- history, deletion and correction behavior are understood;
- extraction and support paths are sustainable;
- dependencies are visible and owned.

A low-readiness source is not rejected forever. It enters a preparation portfolio with named prerequisites.

### 4. Select the smallest complete vertical slice

![Select the smallest complete vertical slice](images/playbooks/which-source-to-load-first-img3-en.png)

The first source is not complete at raw ingestion. The slice must connect:

```text
Source Scope
→ Controlled Ingestion
→ Conformed Business Grain
→ Data Product
→ Semantic Model
→ Named Decision or Control
```

Each stage has an acceptance question:

- **Source:** Which objects and fields are approved?
- **Ingestion:** How are changes, deletions and failures handled?
- **Conform:** What is one row and which authority applies?
- **Data product:** Which quality contract is enforced?
- **Semantic model:** Which definitions and filters are reusable?
- **Consumer:** Which action becomes better, faster or safer?

The anti-pattern is `Source → Raw Tables → “Done”`. The first slice succeeds only when the governed outcome is used and reconciled.

### 5. Maximize reusable learning

The first source should test more than technology. It should validate:

- ownership and stewardship decisions;
- source-contract and change-management practice;
- PII classification and access approval;
- quality thresholds and incident ownership;
- lineage and evidence capture;
- semantic reuse and consumer adoption;
- cost attribution and operational support.

Choose complexity deliberately. A trivial source may prove little; an enormous source may prevent learning from completing.

### 6. Record start, prepare and defer decisions

Every candidate receives one state:

- **Start:** high enough value and readiness for a complete slice.
- **Prepare:** high-value source with explicit prerequisites and owners.
- **Opportunistic:** ready source used only for a bounded learning objective.
- **Defer:** insufficient value or readiness for the current horizon.

No candidate remains in an unexplained connector backlog.

## Checklist

### Decision value

- [ ] A named user and action exist.
- [ ] Expected business or control value is measurable.
- [ ] The urgency and time horizon are explicit.
- [ ] Reuse across products is understood.
- [ ] Reconciliation with an existing process is possible.

### Source readiness

- [ ] Authority, grain, keys and relationships are understood.
- [ ] Business Owner, Steward and Technical Owner are named.
- [ ] Access, PII and permitted use are approved.
- [ ] Quality, history, deletion and correction behavior can be tested.
- [ ] Extraction and support dependencies have owners.

### Vertical slice

- [ ] The source and field boundary is explicit.
- [ ] Ingestion handles failures and deletions.
- [ ] A conformed grain and quality contract exist.
- [ ] A reusable semantic definition is delivered.
- [ ] A named consumer uses and reconciles the result.

### Portfolio governance

- [ ] Every candidate is Start, Prepare, Opportunistic or Defer.
- [ ] Prerequisites and review triggers are recorded.
- [ ] The selected source has success and exit criteria.
- [ ] Learning objectives cover operating model and technology.
- [ ] Scope expansion requires approval.

## Artifact

Create a candidate decision portfolio with one card or row per source.

![Record the first-source decision](images/playbooks/which-source-to-load-first-img4-en.png)

| Field | Purpose |
|---|---|
| Source system | Candidate source under review |
| Starting decision and consumer | Outcome the first slice must support |
| Expected value | Business, operational or control value |
| Authoritative contribution | Entity, attribute or event owned by the source |
| Target grain | Business meaning of one fact row |
| Owner and steward | Accountable decision roles |
| Access and PII readiness | Approval and permitted-use status |
| Quality and reconciliation readiness | Test and acceptance evidence |
| Extraction dependency | Connector, API, contract and support dependency |
| Estimated scope and complexity | Bounded delivery size |
| Reusable learning | Operating-model and architecture learning |
| Decision | Start, Prepare, Opportunistic or Defer |
| Rationale and prerequisites | Evidence and unresolved work |
| Review trigger | Condition for portfolio reassessment |

The portfolio must produce a selected first source, the vertical-slice boundary, named owners, unresolved prerequisites, a deferred-source queue and measurable success criteria.

## Tools

Use the [Source Scope Builder](/tools/source-scope-builder) to define the approved objects, fields, relationships and risks of each serious candidate. Use the [Metadata Export Generator](/tools/meta-export-generator) to turn the selected decision into reusable contract metadata and implementation handoff.

## Resources

- [Which Salesforce Tables to Load for Analytics](/stories/salesforce-tables-for-analytics) — a concrete supplier-specific source-scope pattern.
- [SaaS Exports: Tables You Should Not Load](/stories/saas-exports-tables-to-skip) — generic skip and separate-product rules.
- Existing report, KPI and control inventory.
- Source contracts, access policies and data-classification rules.
- Data-product backlog, consumer map and incident history.

## Playbooks

Apply [Before Building the First Table](/playbooks/before-building-the-first-table). Reuse its business question, grain, ownership, acceptance criteria and vertical-slice boundary instead of recreating them during source onboarding.

## Next step

Approve the first-source portfolio decision before implementation begins. Then build only the selected complete slice. Part 4 continues with HubSpot and shows how to turn CRM objects, properties and associations into a governed source scope.
