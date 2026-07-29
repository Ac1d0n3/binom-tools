---
title: "Dynamics 365 → Mart: Opportunity Fact Candidates"
description: "Move from an approved Dynamics 365 Source Scope to an opportunity-grain sentence, account/contact/opportunity fact and dimension candidates, standard KPIs and a mart design brief."
author: Thomas Lindackers
tags:
  - Dynamics 365
  - Dataverse
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
seriesPart: 5
hero: images/playbooks/dynamics-365-to-mart-hero.png
---

An approved Dynamics 365 Source Scope establishes which Dataverse tables, columns and relationships are allowed into the warehouse. It does not yet establish what one row in a mart represents, or which pipeline number a sales manager can trust when `account`, `contact` and `opportunity` all carry configurable, organization-specific semantics. This guide continues from the Dynamics 365 Source Scope decision to a governed pilot mart built on those three tables.

The scope is intentionally narrow: an opportunity-grained pipeline mart, using `account`, `contact` and `opportunity`, with the standard KPIs a first Dynamics 365 mart should support. It assumes the table, column and relationship scope from the Dynamics 365 Source Scope playbook is already approved.

## Problem

Teams that gain access to a scoped Dataverse extract often go straight to a dashboard built on the raw table shape, which reproduces the application's configuration as the analytical model:

- `opportunity` state and status reason are organization-specific configuration; “Won” in one environment's process may not correspond to the same population as another's;
- `account` and `contact` relate to `opportunity` in configurable, sometimes many-to-many ways, so a naive join can duplicate a single opportunity across multiple contacts;
- choice (option set) fields expose numeric values without labels if the mapping isn't carried through, silently breaking a stage or reason report;
- current-state opportunity records get treated as if they represented history, so “pipeline last quarter” cannot be reconstructed;
- without a KPI card, win-rate and pipeline-value formulas get rebuilt per dashboard, and small filter differences produce inconsistent numbers under the same label.

A grain sentence and a deliberate fact/dimension split, built only from the tables already approved in the Source Scope, resolves this before the first report is built.

## Decision

### 1. Confirm the approved source tables

Use the tables already classified as required or conditional in the Dynamics 365 Source Scope: `opportunity` as the commercial fact source, `account` and/or `contact` as required by the configured process, `owner`/`team`/`business unit` for accountability and security scope, and the required state/status/organization semantics. Quote, order and invoice tables remain conditional and out of scope for this pipeline pilot.

### 2. Write one grain sentence

For a pipeline pilot mart:

> One row per open or recently closed opportunity, as of the current extraction, for the approved business units.

Naming the business-unit scope matters when security roles or organizational structure segment which opportunities different consumers may see.

### 3. Move from tables to facts and dimensions

Classify approved columns as facts, dimension attributes or filters, and resolve every choice-field mapping explicitly rather than exposing raw option-set values.

### 4. Attach standard KPIs to the grain

Attach KPI logic only once the grain and organizational scope are fixed, so a KPI's population matches the mart's actual security and organizational boundary.

## Grain and fact/dim candidates

For an opportunity-grained pilot mart:

| Candidate | Role | Notes |
|---|---|---|
| Opportunity | Fact anchor | One row per opportunity at current state; add a snapshot pattern if stage history is required |
| Estimated / Actual Revenue | Additive fact | Currency and exchange-rate handling must be explicit for multi-currency environments |
| Status Reason | Status attribute | Not additive; organization-configured — resolve to a governed mapping before use |
| Estimated / Actual Close Date | Date dimension key | Distinguish planned close from actual close |
| Account | Dimension | Resolve account-to-opportunity cardinality and hierarchy before joining |
| Contact | Conditional dimension | Only when the configured process ties opportunities to individual contacts rather than accounts |
| Owner / Team / Business Unit | Dimension | Accountable owner and organizational/security scope |
| Opportunity Product | Deferred | Only for a line-grained revenue mart, not the pipeline pilot |
| Activities (Task/Email/Phone Call) | Excluded from this pilot | Requires an explicit activity taxonomy and duplication rule (ActivityPointer vs. subtype) |

The Dataverse opportunity GUID and any business-friendly opportunity number travel as degenerate dimensions on the fact row.

## PII and skip

Personal data in `account`/`contact`/activity fields, annotation and attachment exclusions, and deactivation/merge/deletion handling are covered in the Dynamics 365 Source Scope playbook and are not repeated here. See [Which Dynamics 365 Tables to Load — and Which to Skip](/stories/dynamics-365-tables-for-analytics) for the full classification.

## Standard KPIs

Attach the pilot mart to governed KPI cards instead of dashboard-local formulas:

- **Open Pipeline Value** — sum of estimated revenue for open, in-scope opportunities.
- **Win Rate** — opportunities with a "Won" status reason divided by all closed opportunities in the period, for a named business-unit population.
- **Average Sales Cycle** — days between opportunity creation (or a defined qualification stage) and actual close date.
- **Pipeline by Status Reason** — count or value of open opportunities by status reason, reported by category rather than summed across categories.

Define population, numerator, denominator, grain and time logic explicitly using [Define a KPI](/playbooks/define-kpi), and capture the versioned definition with [KPI Definition](/tools/kpi-definition).

## Artifact

Produce three linked artifacts for the pilot mart:

- `source-scope.csv` — the approved Dynamics 365 table, column and relationship scope carried over from the Source Scope decision.
- `kpi-cards.csv` — one row per KPI with population, numerator, denominator, grain, time logic and owner.
- `mart-design-brief.md` — the grain sentence, business-unit scope, choice-field mappings, fact/dimension list and approvals for the pilot mart.

Log unresolved choice-field mappings, account/contact cardinality questions or activity-duplication rules in `dq-backlog.csv` rather than resolving them silently inside a transformation model.

## Tools and next steps

- [Source Scope Builder](/tools/source-scope-builder) — confirm or extend the approved Dynamics 365 table, column and relationship scope.
- [PII Recommend](/tools/pii-recommend-generator) — get a starting classification for account, contact and activity fields not yet classified.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — turn the grain sentence and organizational scope into a reviewable brief.
- [KPI Definition](/tools/kpi-definition) — capture the standard opportunity KPIs as versioned, owned definitions.
- Not sure which Dataverse tables to prioritize first? The [Governance Advisor](/governance/berater) helps sequence the decision.

## Related playbooks

- [Which Dynamics 365 Tables to Load — and Which to Skip](/stories/dynamics-365-tables-for-analytics) — the Phase-B load/skip decision this guide builds on.
- [From Stakeholder Interview to Table Model](/playbooks/from-stakeholder-interview-to-table-model) — the general method for turning evidence into grain, fact and dimension decisions.
- [Which Source Should Load First?](/playbooks/which-source-to-load-first) — how Dynamics 365 compares to other candidate sources when prioritizing the first mart.

Continue exploring the Dataverse landscape in the [Supplier Library: Dynamics 365](/suppliers/dynamics365) for the wider platform and integration context.
