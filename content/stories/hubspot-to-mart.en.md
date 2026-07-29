---
title: "HubSpot → Mart: Deals Grain to Pilot Mart"
description: "Move from an approved HubSpot Source Scope to a deal-grain sentence, fact and dimension candidates, standard pipeline KPIs and a mart design brief for a pilot mart."
author: Thomas Lindackers
tags:
  - HubSpot
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
seriesPart: 2
hero: images/playbooks/hubspot-to-mart-hero.png
---

An approved HubSpot Source Scope tells you which objects, properties and associations are allowed into the warehouse. It does not tell you what one row in a mart means, or which deal amount a revenue leader can actually trust when three pipelines are open at once. This guide continues from the HubSpot Source Scope decision to a governed pilot mart built on Deals, Companies and Contacts.

The scope is intentionally narrow: a deal-grained pipeline mart, a small set of fact and dimension candidates, and the standard KPIs a first HubSpot-based mart should support. It assumes the object, property and association scope from the HubSpot Source Scope playbook is already approved.

## Problem

Teams that have scoped a HubSpot extraction often move straight from landed objects to a dashboard built on the raw association structure. That inherits HubSpot's application model as the analytical model:

- Deals associate to both companies and contacts through many-to-many relationships, so a naive join can multiply deal amount across every associated contact;
- deal stage and pipeline are portal-specific configuration, so “closed won” in one pipeline may not mean the same population as in another;
- property history and current-state properties get blended, so a “stage as of today” report cannot be reproduced against last month's snapshot;
- without a KPI card, deal-value and win-rate formulas get rebuilt locally in each dashboard, and small differences in filters produce different numbers for what is presented as the same metric.

An explicit grain sentence and a deliberate fact/dimension split, built only from the objects already approved in the Source Scope, resolves this before the first dashboard is built.

## Decision

### 1. Confirm the approved source objects

Use the objects already classified as core or conditional in the HubSpot Source Scope: `Deal` as the commercial fact source, `Company` and `Contact` for customer context, `Owner`/`Team` for accountability, and pipeline/stage reference properties. Line items and product references are conditional and only enter scope if the pilot mart is explicitly line-grained.

### 2. Write one grain sentence

For a pipeline pilot mart:

> One row per open or recently closed deal, as of the current extraction, for the approved pipelines.

Naming “the approved pipelines” matters in a multi-pipeline portal — a deal-grain mart that silently blends sales and post-sales pipelines will produce a pipeline value that no single stakeholder recognizes.

### 3. Move from objects to facts and dimensions

Classify approved properties as facts, dimension attributes or filters rather than carrying every property through unchanged.

### 4. Attach standard KPIs to the grain

Attach KPI definitions only once the grain and the approved pipeline scope are fixed, so a KPI's numerator and denominator can be checked against the actual population the mart contains.

## Grain and fact/dim candidates

For a deal-grained pilot mart:

| Candidate | Role | Notes |
|---|---|---|
| Deal | Fact anchor | One row per deal at current state; add a snapshot pattern if stage-history is required |
| Amount | Additive fact | Currency and multi-currency portals need an explicit conversion rule |
| Deal Stage | Status attribute | Not additive; portal- and pipeline-specific — resolve to a governed mapping |
| Pipeline | Filter / dimension | Explicitly named in the grain sentence when a portal has more than one active pipeline |
| Close Date | Date dimension key | Distinguish committed close date from actual close date if both exist |
| Company | Dimension | Resolve the deal-to-company association direction and cardinality before joining |
| Contact | Conditional dimension | Only when buyer-role or multi-contact analysis is in scope; watch many-to-many association |
| Owner / Team | Dimension | Accountable owner and team, using approved identifiers only |
| Line Item / Product | Deferred | Only for a line-grained revenue mart, not the pipeline pilot |

Deal ID travels as a degenerate dimension on the fact row rather than requiring its own table.

## PII and skip

Property-level PII classification, free-text and activity exclusions (notes, messages, files, attachments) and archived/merged-record handling are covered in the HubSpot Source Scope playbook and are not repeated here. See [Which HubSpot Tables to Load — and Which to Skip](/stories/hubspot-tables-for-analytics) for the full classification.

## Standard KPIs

Attach the pilot mart to governed KPI cards instead of local dashboard formulas:

- **Open Pipeline Value** — sum of deal amount for open, in-scope-pipeline deals.
- **Win Rate** — closed-won deals divided by all closed deals in the period, for a named pipeline and population.
- **Average Deal Cycle Time** — days between deal creation (or a defined start stage) and close date.
- **Stage Distribution** — count or value of open deals by stage, reported by stage rather than summed across stages.

Define population, numerator, denominator, grain and time logic before implementation using [Define a KPI](/playbooks/define-kpi), and capture the versioned definition with [KPI Definition](/tools/kpi-definition).

## Artifact

Produce three linked artifacts for the pilot mart:

- `source-scope.csv` — the approved HubSpot object, property and association scope carried over from the Source Scope decision.
- `kpi-cards.csv` — one row per KPI with population, numerator, denominator, grain, time logic and owner.
- `mart-design-brief.md` — the grain sentence, named pipeline scope, fact/dimension list, history treatment and approvals for the pilot mart.

Log unresolved property-history gaps, stage-mapping ambiguity or association-cardinality questions in `dq-backlog.csv` rather than resolving them silently inside a dashboard query.

## Tools and next steps

- [Source Scope Builder](/tools/source-scope-builder) — confirm or extend the approved HubSpot object, property and association scope.
- [PII Recommend](/tools/pii-recommend-generator) — get a starting classification for properties not yet classified.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — turn the grain sentence and named pipeline scope into a reviewable brief.
- [KPI Definition](/tools/kpi-definition) — capture the standard deal KPIs as versioned, owned definitions.
- Not sure which artifact fits your current stage? Start with the [Governance Advisor](/governance/berater).

## Related playbooks

- [Which HubSpot Tables to Load — and Which to Skip](/stories/hubspot-tables-for-analytics) — the Phase-B load/skip decision this guide builds on.
- [From Stakeholder Interview to Table Model](/playbooks/from-stakeholder-interview-to-table-model) — the general method for turning evidence into grain, fact and dimension decisions.
- [Which Source Should Load First?](/playbooks/which-source-to-load-first) — how HubSpot compares to other candidate sources when prioritizing the first mart.

Continue exploring HubSpot in the [Supplier Library: HubSpot](/suppliers/hubspot) for the wider platform and connector context.
