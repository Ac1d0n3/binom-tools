---
title: "SAP S/4 → Mart: A Narrow Sales / Order Slice"
description: "Turn an approved SAP S/4 Source Scope into one narrow sales-order fact grain, header/item candidates, standard KPIs and a mart design brief — without attempting a full-ERP mart."
author: Thomas Lindackers
tags:
  - SAP S/4HANA
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
seriesPart: 3
hero: images/playbooks/sap-s4-to-mart-hero.png
---

SAP S/4 exposes an entire enterprise's transactional backbone: sales, procurement, inventory, finance and more. An approved Source Scope narrows that landscape to a supported semantic extraction surface. This guide narrows it further, on purpose, to one slice: the sales order document flow. It does not attempt a full-ERP mart, and it should not.

The scope here is a governed sales-order fact at header or item grain, moving from an already-approved S/4 Source Scope to a pilot mart that a sales-operations or order-management stakeholder can actually use and trust.

## Problem

Teams that gain SAP S/4 access are tempted to build broadly because the platform contains so much. That temptation produces predictable failure modes:

- sales, delivery, billing and accounting documents get joined into one mart without a declared document-flow transition, so a single order can appear multiple times or with inconsistent amounts;
- header and item amounts are summed together, silently double-counting revenue;
- currency is mixed — local currency, group currency and document currency get blended into one column without a conversion rule;
- cancelled and reversed documents remain active in the mart because reversal semantics were never modeled;
- postings that land in the wrong fiscal period distort period-over-period comparisons;
- the mart tries to cover order-to-cash, procure-to-pay and record-to-report at once, and ends up serving none of them reliably.

A narrow, explicit sales-order slice — one grain, one document-flow segment, one currency rule — is more valuable on day one than a broad ERP replica that nobody can reconcile.

## Decision

### 1. Confirm the approved source objects

Use only the transactional facts and organizational context already approved as must-have or conditional in the S/4 Source Scope for the sales-order use case: sales order and, if in scope, delivery and billing documents, plus business partner, material/product, sales organization, plant, currency and fiscal-period context. Purchasing, inventory-movement and general-ledger objects stay out of this slice even if they were separately approved for other decisions.

### 2. Write one grain sentence

For a narrow order-status pilot mart:

> One row per sales order item, as of the current extraction, for approved sales organizations.

A billing/revenue variant instead uses:

> One row per billing document item, at posting date.

These are two different marts. Building both in the same pilot risks mixing order-intent amounts with invoiced amounts under one label.

### 3. Move from objects to facts and dimensions

Classify approved fields by role instead of loading the full document structure.

### 4. Attach standard KPIs to the grain

Attach KPI logic only once the document-flow segment, grain and currency rule are explicit, so a KPI cannot silently mix order value with billed value.

## Grain and fact/dim candidates

For a sales-order-item pilot mart:

| Candidate | Role | Notes |
|---|---|---|
| Sales Order Item | Fact anchor | One row per order item, header context carried as attributes, not summed separately |
| Order Quantity / Net Value | Additive fact | Aggregate only within one currency and one document-flow stage |
| Document Currency | Fact attribute / conversion key | Local vs. group currency conversion must be an explicit, documented rule |
| Delivery / Billing Status | Status attribute | Not additive; use for filtering and document-flow-stage reporting |
| Sales Organization / Plant | Dimension | Organizational scope boundary for the mart |
| Business Partner (Customer) | Dimension | Resolve authoritative customer master and PII classification per the Source Scope |
| Material / Product | Dimension | Reference for product-level analysis |
| Posting / Fiscal Period | Date dimension key | Governed fiscal-calendar mapping, not a raw calendar date |
| Delivery / Billing Document | Deferred or separate mart | Only add once the header-to-item and document-flow join rule is explicitly modeled |

Cancellation and reversal indicators should travel as explicit attributes on the fact row so a KPI can exclude reversed documents by rule rather than by convention.

## PII and skip

Business-partner personal data, commercially sensitive pricing/margin fields, and the decision to skip raw-table copies, obsolete CDS views and technical logs are covered in the SAP S/4 Source Scope playbook. This guide does not re-derive that classification. See [Which SAP S/4 Tables to Load for Analytics — and Which to Skip](/stories/sap-s4-tables-for-analytics) for the full scope decision.

## Standard KPIs

Attach the narrow sales-order mart to governed KPI cards:

- **Open Order Value** — net value of order items not yet fully delivered or billed, in one governed currency.
- **On-Time Delivery Rate** — items delivered on or before the promised date, for a defined population and exclusion set.
- **Order-to-Billing Lag** — days between order creation and first billing document, at item grain.
- **Cancellation / Return Rate** — cancelled or returned order items divided by all order items in the period.

Define population, numerator, denominator, grain, currency and time logic explicitly using [Define a KPI](/playbooks/define-kpi), and capture the versioned definition with [KPI Definition](/tools/kpi-definition).

## Artifact

Produce three linked artifacts for the pilot mart:

- `source-scope.csv` — the approved S/4 object, CDS-view/API reference and field scope carried over from the Source Scope decision.
- `kpi-cards.csv` — one row per KPI with population, numerator, denominator, grain, currency, time logic and owner.
- `mart-design-brief.md` — the grain sentence, document-flow boundary, currency rule, fact/dimension list and approvals for the pilot mart.

Record unresolved currency-conversion rules, fiscal-period mapping gaps or reversal-handling questions in `dq-backlog.csv` rather than resolving them silently in a transformation script.

## Tools and next steps

- [Source Scope Builder](/tools/source-scope-builder) — confirm or extend the approved S/4 semantic extraction scope for this slice.
- [PII Recommend](/tools/pii-recommend-generator) — get a starting classification for business-partner and pricing fields.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — turn the grain sentence and document-flow boundary into a reviewable brief.
- [KPI Definition](/tools/kpi-definition) — capture the standard sales-order KPIs as versioned, owned definitions.
- Unsure whether this slice or a different S/4 process should come first? The [Governance Advisor](/governance/berater) helps sequence the decision.

## Related playbooks

- [Which SAP S/4 Tables to Load for Analytics — and Which to Skip](/stories/sap-s4-tables-for-analytics) — the Phase-B load/skip decision this guide builds on.
- [From Stakeholder Interview to Table Model](/playbooks/from-stakeholder-interview-to-table-model) — the general method for turning evidence into grain, fact and dimension decisions.
- [Which Source Should Load First?](/playbooks/which-source-to-load-first) — how a narrow S/4 slice compares to other candidate sources when prioritizing the first mart.

Continue exploring the SAP landscape in the [Supplier Library: SAP S/4HANA](/suppliers/sap-s4hana) for the wider platform and extraction-interface context.
