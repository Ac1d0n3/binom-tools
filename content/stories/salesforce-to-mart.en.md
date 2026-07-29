---
title: "Salesforce → Mart: Grain, Facts and KPI Cards"
description: "Turn an approved Salesforce source scope into a grain sentence, fact and dimension candidates, standard KPI cards and a mart design brief for a pilot pipeline mart."
author: Thomas Lindackers
tags:
  - Salesforce
  - Mart Design
  - Grain
  - Data Governance
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
publishedAt: 2026-07-29
category: Data Governance
order: -1
series: supplier-to-mart
seriesTitle: Supplier to Mart
seriesPart: 1
hero: images/playbooks/salesforce-to-mart-hero.png
---

Loading the right Salesforce objects is only half of the work. The Source Scope tells you which tables, fields and relationships are approved. It does not yet tell you what one row in the mart represents, which measures are additive, or which KPI a sales leader can actually trust. This guide starts where the Salesforce Source Scope decision ends and walks it forward to a governed pilot mart.

The scope of this guide is deliberately narrow: `Opportunity`, `Account` and `Contact` as the commercial core, one clear grain sentence, a short list of fact and dimension candidates, and the standard pipeline KPIs a first mart should support. It assumes the load/skip decision from the Salesforce Source Scope playbook is already approved.

## Problem

Teams that have successfully scoped a Salesforce extraction often stall at the next step. They land `Opportunity`, `Account` and `Contact` in a warehouse schema and then build a dashboard directly against the raw shape of those tables. That approach silently inherits Salesforce's operational structure as the analytical model:

- `Opportunity` mixes current-state fields with implied history, so “open pipeline” and “stage as of last week” cannot both be answered from the same query;
- `Account` and `Contact` carry many-to-many relationships to `Opportunity` that are never made explicit, so aggregations double-count or arbitrarily pick one relationship;
- stage, owner and close-date semantics are copied as labels without a declared grain, so the same report can be rebuilt with a different row count by a different analyst;
- KPI formulas get written ad hoc in the BI tool because no KPI card exists yet, and three dashboards quietly disagree on “win rate.”

The fix is not more source tables. It is an explicit grain sentence and a small number of governed fact and dimension candidates, built from the objects already approved in the Source Scope.

## Decision

Take the path from source objects to mart in four steps.

### 1. Confirm the approved source objects

Start from the objects already approved as must-have or conditional in the Salesforce Source Scope: `Opportunity` as the commercial fact source, `Account` for segmentation, `Contact` and `User`/owner references for accountability, and — only if the pilot mart is explicitly line-grained — `OpportunityLineItem`, `PricebookEntry` and `Product2`. Do not add objects here; if a new object seems necessary, return to the Source Scope and make that decision explicitly.

### 2. Write one grain sentence

Before naming a single fact or dimension, write the sentence that defines one row. For a pipeline pilot mart, a typical sentence is:

> One row per open or recently closed opportunity, as of the current extraction.

A line-grained revenue mart instead uses:

> One row per opportunity line item, as of the current extraction.

These are different marts even though both start from `Opportunity`. Pick one grain for the pilot and defer the other.

### 3. Move from objects to facts and dimensions

With the grain fixed, classify each approved field as a fact, a dimension attribute or a filter, rather than copying the object schema wholesale.

### 4. Attach standard KPIs to the grain

Only after the grain is fixed do you attach KPI definitions. A KPI that assumes line-level detail cannot be safely calculated on an opportunity-grained mart, and vice versa.

## Grain and fact/dim candidates

For an opportunity-grained pilot mart:

| Candidate | Role | Notes |
|---|---|---|
| Opportunity | Fact anchor | One row per opportunity at current state; add a snapshot pattern later if history is required |
| Amount / Expected Revenue | Additive fact | Aggregation validity depends on currency and probability treatment |
| Stage | Status attribute | Not additive; use for filtering and stage-duration analysis, not summation |
| Close Date | Date dimension key | Governed date semantics: planned vs. actual close |
| Account | Dimension | Segment, industry, region attributes; watch for one-to-many account hierarchies |
| Owner (User) | Dimension | Accountable owner; use approved identifier and role attributes only |
| Contact / Contact Role | Conditional dimension | Only if the pilot needs buyer-role or multi-contact analysis |
| OpportunityLineItem | Deferred | Only for a line-grained revenue mart, not the pipeline pilot |

Degenerate dimensions such as the Salesforce opportunity number can travel with the fact row without a separate dimension table.

## PII and skip

Field-level PII decisions, free-text exclusions (`Task`, `Event`, notes, attachments) and deletion/merge handling are already covered in the Salesforce Source Scope playbook and should not be re-derived here. See [Which Salesforce Tables to Load for Analytics — and Which to Skip](/stories/salesforce-tables-for-analytics) for the full classification and field allowlist rationale.

## Standard KPIs

Attach the pilot mart to a small number of governed KPI cards rather than ad hoc formulas:

- **Open Pipeline Value** — sum of amount for open, non-excluded opportunities.
- **Win Rate** — closed-won opportunities divided by all closed opportunities in the period, with an explicit population and exclusion rule.
- **Average Sales Cycle** — days between a defined start event and close date, at opportunity grain.
- **Stage Coverage** — count or value of opportunities by stage, used for pipeline health, not summed across stages as a single number.

Define each KPI's population, numerator, denominator, grain and time logic explicitly before implementation. Use [Define a KPI](/playbooks/define-kpi) for the full contract structure and the [KPI Definition](/tools/kpi-definition) tool to capture and version the definition.

## Artifact

Produce three linked artifacts for the pilot mart:

- `source-scope.csv` — the approved Salesforce object, field and relationship scope (carried over from the Source Scope decision).
- `kpi-cards.csv` — one row per KPI with population, numerator, denominator, grain, time logic and owner.
- `mart-design-brief.md` — the grain sentence, fact/dimension list, history treatment, scope-in/scope-out and approvals for the pilot mart.

Where field-level data-quality issues are found during scoping (missing close dates, unmapped stages, orphaned owners), record them in `dq-backlog.csv` rather than silently filtering them out of the mart.

## Tools and next steps

- [Source Scope Builder](/tools/source-scope-builder) — confirm or extend the approved Salesforce object and field scope before mart design.
- [PII Recommend](/tools/pii-recommend-generator) — get a starting classification for any field still marked as unclassified.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — turn the grain sentence and fact/dimension candidates into a reviewable brief.
- [KPI Definition](/tools/kpi-definition) — capture the standard pipeline KPIs as versioned, owned definitions.
- Unsure where to start or which artifact applies to your situation? The [Governance Advisor](/governance/berater) walks through the decision sequence for your context.

## Related playbooks

- [Which Salesforce Tables to Load for Analytics — and Which to Skip](/stories/salesforce-tables-for-analytics) — the Phase-B load/skip decision this guide builds on.
- [From Stakeholder Interview to Table Model](/playbooks/from-stakeholder-interview-to-table-model) — the general method for turning evidence into grain, fact and dimension decisions.
- [Which Source Should Load First?](/playbooks/which-source-to-load-first) — how Salesforce compares to other candidate sources when prioritizing the first mart.

Continue exploring Salesforce in the [Supplier Library: Salesforce](/suppliers/salesforce) for the wider platform and connector context.
