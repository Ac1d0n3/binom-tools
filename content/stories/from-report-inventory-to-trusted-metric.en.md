---
title: "From Report Inventory to Trusted Metric"
description: "Convert report and formula inventories into prioritized metric families, approved contracts, validated implementations and migrated consumers."
author: Thomas Lindackers
tags:
  - Report Inventory
  - Trusted Metrics
  - Metric Governance
  - BI Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/from-report-inventory-to-trusted-metric-hero.png
series: bi-governance-decisions
seriesTitle: BI Governance Decisions
seriesPart: 5
---

A report inventory is discovery evidence, not the governance outcome. The outcome is a smaller, explicit and operated portfolio of trusted metrics with approved definitions, controlled implementations and migrated consumers.

## Problem

Inventories often stop after listing report names, owners and usage. That catalog may support cleanup, but it cannot resolve why two reports show different values for the same label. Metric drift normally hides in expressions, source fields, base measures, filters, selections, date logic, currency rules, exclusions, grain and displayed context.

String equality is not semantic equality. Two syntactically different formulas may implement the same contract, while two identical expressions may behave differently because the surrounding model, filter context or source grain differs. The most-used implementation is also not automatically authoritative.

The inventory must therefore lead to metric-family decisions: retain, consolidate, approve as a child metric, keep as a local derivative, remediate, migrate, deprecate or reject.

## Decision

Build the inventory at the level required to compare meaning. Capture platform, workspace or app, report, sheet or visual, metric label, expression, semantic model or data source, source fields, base measures, filters, time behavior, grain, displayed dimensions, owner, audience, usage, criticality, certification and lifecycle status.

Cluster entries into metric families. Separate exact copies, syntax variants with the same meaning, approved child metrics, context-specific derivatives, competing business definitions and unknown variants.

Compare each candidate on:

- business question and intended decision;
- population, inclusions and exclusions;
- base grain and source authority;
- aggregation and non-additive behavior;
- date, period and comparison logic;
- filter and selection behavior;
- currency, unit, sign and null handling;
- intended consumers, owner and approval state.

Then prioritize families by business criticality, active consumers, number and severity of variants, observed contradictions, owner readiness, source readiness, expected reuse and migration effort.

An owner decision establishes the canonical contract. Technical implementation, reconciliation and consumer migration follow. Deletion comes last.

## Checklist

- Does the inventory contain expressions and semantic context, not only report metadata?
- Are metric labels normalized without assuming equal labels mean equal metrics?
- Are exact copies, semantic equivalents, child metrics and competing definitions separated?
- Is the authoritative business question known?
- Are grain, source, aggregation, time, filters, exclusions and units compared?
- Are usage and decision criticality measured?
- Is an accountable owner available to approve or reject the candidate?
- Is implementation placement decided using the semantic-layer boundary?
- Are reference data, scenario tests and reconciliation tolerances defined?
- Is parallel validation planned?
- Are consumers, downstream extracts and workbook dependencies inventoried?
- Is a migration owner and target date assigned?
- Is deprecation blocked until replacement and consumer acceptance exist?
- Are duplicate reduction and migrated-consumer share measured?

An inventory is successful when unresolved metric families decline and trusted implementations gain consumers—not when the spreadsheet reaches 100 percent completeness.

## Artifact

Create a **Trusted Metric Candidate Record** for each metric family and manage all records as a migration portfolio.

Required fields include metric-family ID, labels and aliases, discovered implementations, business questions, semantic comparison result, proposed and approved definition, version, base grain, source authority, placement, production references, Owner, Steward, custodians, tests, reconciliation, certification requirement, approved derivatives, conflicting variants, migration owner, target date, replacement, exceptions and review trigger.

Use decision outcomes:

- `certify now`
- `remediate definition`
- `consolidate implementations`
- `retain approved local derivative`
- `deprecate duplicate`
- `defer pending owner or source`
- `reject unsupported metric`

Portfolio metrics should include unresolved families, duplicate reduction, time to owner decision, evidence completeness, migrated-consumer share, deprecated-metric reuse and exceptions past expiry.

## Tools

Use Report Inventory to collect implementation evidence across BI platforms and Excel. Use KPI Definition once a candidate reaches owner review. Use the BI Python Toolkit for expression normalization, clustering support, dependency analysis and value reconciliation.

Automation can suggest similarity; it cannot decide semantic equivalence. Every automated cluster must remain reviewable and preserve links to the source implementation.

## Resources

Useful internal evidence sources include BI platform metadata APIs, semantic-model exports, workbook and application inventories, query logs, lineage catalogs, usage telemetry, certification records, support incidents and financial reconciliation files.

Keep personal or confidential workbook content protected during collection. Extract only the metadata and expressions required for the approved purpose.

## Playbooks

- [Define KPI](/playbooks/define-kpi)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)
- [Semantic Layer vs Measure in the Report](/stories/semantic-layer-vs-report-measure)

Reuse their definition and placement decisions rather than creating a second metric methodology inside the inventory process.

## Next step

Choose one label that appears in several reports, such as Net Revenue. Build the full metric family, compare semantics, obtain an owner decision, implement the approved contract, run parallel reconciliation and migrate one real consumer. Use the result to refine the inventory schema before scaling.
