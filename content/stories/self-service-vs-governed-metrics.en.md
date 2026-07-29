---
title: "Self-Service Metrics vs Governed Metrics"
description: "Protect shared and critical metrics while preserving analytical freedom through explicit metric zones, decision rights and promotion triggers."
author: Thomas Lindackers
tags:
  - Self-Service BI
  - Governed Metrics
  - Metric Policy
  - Operating Model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/self-service-vs-governed-metrics-hero.png
series: bi-governance-decisions
seriesTitle: BI Governance Decisions
seriesPart: 6
---

Self-service and governed metrics are not opposites. The workable alternative to both central control of every formula and unrestricted metric creation is a tiered operating model with explicit decision rights.

## Problem

When every calculation needs a central committee, experimentation slows and local work moves underground. When “self-service” means that every creator may publish any formula under any label, approved metrics lose authority and exploratory calculations quietly enter executive, financial or customer-facing reporting.

The missing element is not another approval workflow for every analytical thought. It is a clear distinction between protected base metrics, controlled derivatives and local experiments.

Users also need to know which changes preserve a metric and which changes redefine it. Changing a presentation format is different from changing population, grain, time logic or exclusions. Without visible boundaries, even well-intentioned users create conflicting meanings.

## Decision

Define three metric zones.

**Governed Base Metrics** have an approved definition and grain, named owner, shared implementation, common tests and protected name. They are used where consistent enterprise meaning is required.

**Controlled Derived Metrics** are built from governed bases using approved patterns. Permitted dimensions, filters, scenarios and time windows are explicit. They have an owner and intended scope and may become reusable after review.

**Local Exploratory Metrics** belong to one report, workbook or analysis. They are visibly labelled experimental, have an owner and expiry, make no enterprise-truth claim and are excluded from certified reporting.

Governance intensity rises with reuse, criticality and external consequence. Promotion is triggered when a local metric is copied, reused across governed products, used for executive or regulated decisions, requires common grain or reconciliation, or materially changes a shared decision.

Keep a metric local when it is presentation-only, a short-lived hypothesis, a one-off analysis or an approved derivative that does not redefine the base contract.

## Checklist

For each metric class define:

- who may create it;
- who may publish it;
- which source metrics may be used;
- which filters, dimensions and scenarios may vary;
- required name and status label;
- owner and expiry requirements;
- test and reconciliation level;
- permitted consumers;
- certification eligibility;
- promotion triggers;
- exception approver;
- deprecation and migration rules.

Also answer:

- Which metric names are protected?
- Which changes constitute a prohibited redefinition?
- Can a local variant visually resemble a certified metric?
- How are contested definitions escalated?
- What happens when no owner is available?
- How is an urgent exception approved and expired?
- How are experimental metrics removed from executive reports?
- How can users request a new governed derivative without waiting for a central implementation team?

The central governance function should define boundaries and resolve conflicts. It should not become the implementation queue for every formula.

## Artifact

Create a **Self-Service Metric Policy** and decision matrix.

Rows represent governed base metrics, approved derived metrics, local exploratory metrics and prohibited or conflicting redefinitions. Columns capture definition authority, creation rights, publication rights, allowed sources, permitted filters and dimensions, naming, status, owner, evidence, certification, expiry, promotion, exception approval and deprecation.

Maintain an experimental-metric register with metric name, creator, owner, report or workbook, purpose, source bases, creation date, expiry date, consumer scope and promotion status.

Use visible labels such as `governed`, `approved derivative`, `experimental`, `deprecated` and `prohibited conflict`. Do not allow local variants to reuse certified labels without qualification.

Measure reuse of governed bases, number of promoted derivatives, expired experiments, unresolved definition conflicts and executive reports containing local metrics.

## Tools

Use KPI Definition for metrics entering the governed or approved-derived zones. Use Report Inventory to identify copied formulas, reused labels and local measures that have crossed their intended boundary. Formula generators may implement approved child-measure patterns, but they must reject missing grain, source, filter or ownership information.

The tool experience should show users what they are allowed to change and which changes require a new metric contract.

## Resources

Relevant internal resources include the metric catalog, semantic-model documentation, report inventory, certification records, exception register, glossary, model usage telemetry and consumer migration plans.

Policy should be platform-neutral. Platform-specific permissions and certification mechanisms implement parts of the policy but do not define the business decision rights.

## Playbooks

- [Define KPI](/playbooks/define-kpi)
- [Semantic Layer vs Measure in the Report](/stories/semantic-layer-vs-report-measure)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)

These define the contract and evidence required when a metric is promoted.

## Next step

Select ten metrics currently used in self-service reports. Classify them into the three zones, identify one prohibited redefinition, publish the allowed derivative patterns and give every experimental metric an owner and expiry. Use the conflicts found to refine the policy.
