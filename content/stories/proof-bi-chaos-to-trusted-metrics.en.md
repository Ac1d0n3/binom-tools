---
title: "Proof: From BI Report Chaos to Trusted Metrics"
description: "An anonymized example of how a reporting team consolidated seven variants of 'Net Revenue' through a report inventory, a placement decision, and controlled generators into one certified metric."
author: Thomas Lindackers
tags:
  - Proof
  - Trusted Metrics
  - Semantic Layer
  - BI Governance
  - Data Governance
products:
  - qlik
  - fabric
  - powerbi
  - snowflake
  - dbt
publishedAt: 2026-07-29
category: Data Governance
order: -1
series: governance-proof
seriesTitle: Governance Proof
seriesPart: 3
hero: images/playbooks/proof-bi-chaos-to-trusted-metrics-hero.png
---

A reporting team discovered during a quality review that the term "Net Revenue" appeared in at least seven different reports, semantic models, and a maintained Excel workbook — each with a different value. This anonymized story shows the path from that discovery through a structured report inventory to one certified, reusable metric.

## Starting situation

The organization used Power BI for executive reporting, Qlik for operational sales analysis, and a maintained Excel workbook in finance. All three environments contained a metric called "Net Revenue." A number reconciliation before a quarterly close surfaced discrepancies nobody could immediately explain — the formulas looked different in every tool because each BI engine expresses filter context differently.

The reflexive first suggestion was to "just copy one formula." The team chose a systematic review instead.

## Steps

1. **A report inventory instead of an ad hoc formula comparison.** Using the method from [From Report Inventory to Trusted Metric](/playbooks/from-report-inventory-to-trusted-metric), all seven implementations were captured: platform, expression, base grain, filters, time behavior, owner and usage. This capture alone showed that two of the seven variants were exact copies, three were syntactically different but semantically equal, and two were genuinely different business definitions (gross versus net of cancellations).

2. **Cluster and compare.** Candidates were compared on business question, population, grain, aggregation, time logic and owner readiness, rather than on apparent formula similarity. The most-used variant did not automatically turn out to be the business-correct one.

3. **Decide on placement.** [Semantic Layer vs Measure in the Report](/playbooks/semantic-layer-vs-report-measure) clarified which part of the logic belongs in the warehouse (cancellation treatment, currency standardization), which belongs in the semantic layer (approved aggregation, time behavior), and which may remain a report-local variant (share of the currently selected total in one sales dashboard).

4. **Get an owner decision.** A named metric owner from finance approved a single canonical definition for "Net Revenue" and assigned new, distinct names to the two diverging definitions instead of letting them continue under the same label.

5. **Generate implementations under control.** For the approved definition, [When BI Formula Generators Help Governance](/playbooks/when-to-use-bi-formula-generators) guided generating implementations for Power BI and Qlik — based on a complete metric contract, not a vague request. Reference results and tolerances were fixed up front.

6. **Reconcile in parallel and migrate.** Both generated implementations were reconciled against the same test scenarios before production reports were migrated. The Excel workbook remained in use as an analysis surface, but now pulls the core figure from the approved semantic model instead of its own formula.

7. **Check for remaining gaps.** Using [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics), the team finally checked whether definition, ownership, quality and certification were actually sufficient for trusted use, or whether one of the seven variants was still circulating undetected.

## Artifacts

- **Trusted Metric Candidate Record** — one row per metric family with discovered implementations, comparison result and owner decision (`certify`, `consolidate`, `deprecate`).
- **Metric Placement Decision** — documents which logic lives in the warehouse, in the semantic layer, and report-locally.
- **`kpi-cards.csv`** — the approved "Net Revenue" definition plus the two newly named variants, versioned as KPI cards.
- **Formula Generation Request and Validation Record** — input contract, generated formulas, reconciliation result and approval for Power BI and Qlik.

The shared filenames for KPI cards and other standard artifacts are described in [Which Artifacts Do You Get?](/playbooks/which-artifacts-you-get).

## What we learned

The central lesson was that technical validity does not prove semantic equivalence: two formulas can return the same value in a sample and still mean different things once filter context or time period changes. Only the structured comparison at the level of population, grain and time logic revealed that two of the seven variants actually answered different business questions.

The second lesson concerned the role of formula generators: they substantially accelerated implementation for two platforms, but at no point replaced the owner decision on meaning, grain, or certification.

> **Note:** This story is an anonymized, condensed example illustrating the method. It does not replace legal, privacy or vendor advice and is not a case study of a specific customer.

## Tools and next steps

- [Report Inventory](/tools/report-inventory) — capture formulas, grains and contradictions across platforms.
- [KPI Definition](/tools/kpi-definition) — version the approved definition as `kpi-cards.csv`.
- [Power BI DAX Measure Generator](/tools/powerbi-dax-generator) and [Qlik Set Analysis Generator](/tools/qlik-set-analysis-generator) — generate controlled implementations from the approved contract.
- Unsure whether a report inventory or a placement decision is the right next step? The [Governance Advisor](/governance/berater) helps place it.

## Related playbooks

- [From Report Inventory to Trusted Metric](/playbooks/from-report-inventory-to-trusted-metric) — the method behind steps 1 and 2.
- [Semantic Layer vs Measure in the Report](/playbooks/semantic-layer-vs-report-measure) — the placement decision behind step 3.
- [When BI Formula Generators Help Governance](/playbooks/when-to-use-bi-formula-generators) — the boundary between approved meaning and generated syntax behind step 5.
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics) — the final check behind step 7.

This story closes the three-part proof series: from stakeholder interviews, through a SaaS source, to trusted metrics — each built on the same standard artifacts.
