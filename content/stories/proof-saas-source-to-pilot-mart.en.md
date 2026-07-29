---
title: "Proof: From SaaS Source to an Approved Pilot Mart"
description: "An anonymized example of how a team prioritized a Salesforce instance, approved a source scope with PII classification, and built a first pipeline mart from it."
author: Thomas Lindackers
tags:
  - Proof
  - Source Scope
  - PII
  - Salesforce
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
series: governance-proof
seriesTitle: Governance Proof
seriesPart: 2
hero: images/playbooks/proof-saas-source-to-pilot-mart-hero.png
---

An analytics team with three candidate first sources — Salesforce, a ticketing system, and an ERP export folder — had to decide where the first ingestion would start. This anonymized story shows the path from that prioritization question through a documented load/skip decision to an approved pilot mart, with no customer names or real figures.

## Starting situation

The team had capacity for exactly one complete first source this quarter. All three candidates had advocates: Salesforce was the most visible to leadership, the ticketing system had the "cleanest" API, and the ERP export was already available as a file. Convenience alone was not supposed to drive the decision.

There was also uncertainty about scope: an earlier, informal discussion had suggested "just loading all Salesforce objects" to avoid rework later — without clarifying which objects actually served an analytics purpose.

## Steps

1. **Portfolio decision instead of gut feel.** Using the method from [Which Source Should Load First?](/playbooks/which-source-to-load-first), all three candidates were scored separately on decision value and source readiness. Salesforce won not because of visibility, but because a named decision — pipeline management by stage and owner — was already clearly articulated, and ownership, access and grain knowledge existed.

2. **Check generic skip patterns up front.** Before deciding object by object, the team used [SaaS Exports: Tables You Should Not Load](/playbooks/saas-exports-tables-to-skip) to understand the generic categories: UI caches, duplicate snapshots, bulky audit logs, and unbounded free text needed critical review regardless of vendor.

3. **Classify Salesforce objects.** Using [Which Salesforce Tables to Load for Analytics — and Which to Skip](/playbooks/salesforce-tables-for-analytics), `Opportunity`, `Account` and `User` were classified as must-have, `Contact` as conditional with a field allowlist, and `Task`/`Event` along with notes and attachments were deliberately deferred because no named activity use case existed.

4. **Document the source scope.** The [Source Scope Builder](/tools/source-scope-builder) recorded purpose, contribution to grain, time requirements, PII risk and decision for each object — including the objects explicitly excluded and their review trigger.

5. **Classify PII before loading.** For `Contact` and `User` fields, the [PII Recommend Generator](/tools/pii-recommend-generator) provided a starting risk classification. Free-text fields from `Task` and `Event` stayed outside the scope until an owner names a concrete purpose.

6. **From scope to mart.** With the approved scope in hand, the team followed [Salesforce → Mart: Grain, Facts and KPI Cards](/playbooks/salesforce-to-mart): opportunity grain was chosen as the primary grain, fact and dimension candidates were named, and the standard pipeline KPIs were captured as cards.

7. **Approve the mart design brief.** The [Mart Design Brief Generator](/tools/mart-design-brief-generator) combined the source scope and KPI cards into a brief with grain, history treatment and scope-out before the first physical table was built.

## Artifacts

- **`source-scope.csv`** — Salesforce objects with decision (include/defer/exclude), rationale and review trigger.
- **`kpi-cards.csv`** — Pipeline Value, Win Rate and Average Deal Size as versioned KPI cards at opportunity grain.
- **`mart-design-brief.md`** — grain, fact/dimension candidates and scope-out for the pilot mart, approved before building the table.

The full list of standard filenames is in [Which Artifacts Do You Get?](/playbooks/which-artifacts-you-get).

## What we learned

The most important effect of the portfolio decision was that Salesforce won on a documented rationale rather than "because it's visible" — including the two deferred candidates with clear prerequisites for later re-evaluation. That made the decision understandable to all three advocates, including the ones whose source did not start first.

The second lesson was about the scope itself: without the object-by-object classification, the original "load all objects" suggestion would likely have been implemented. The approved scope significantly reduced the objects actually loaded, without the pilot mart losing anything — the grain simply did not need those objects.

> **Note:** This story is an anonymized, condensed example illustrating the method. It does not replace legal, privacy or vendor advice and is not a case study of a specific customer.

## Tools and next steps

- [Source Scope Builder](/tools/source-scope-builder) — document objects, fields and relationships as `source-scope.csv`.
- [PII Recommend Generator](/tools/pii-recommend-generator) — classify field risk before loading.
- [KPI Definition](/tools/kpi-definition) — version standard KPIs as `kpi-cards.csv`.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — approve grain, facts and scope-out as `mart-design-brief.md`.
- Unsure which source should go first? The [Governance Advisor](/governance/berater) walks through the prioritization.

## Related playbooks

- [Which Source Should Load First?](/playbooks/which-source-to-load-first) — the portfolio method behind step 1.
- [SaaS Exports: Tables You Should Not Load](/playbooks/saas-exports-tables-to-skip) — the generic skip patterns behind step 2.
- [Which Salesforce Tables to Load for Analytics — and Which to Skip](/playbooks/salesforce-tables-for-analytics) — the full object classification behind step 3.
- [Salesforce → Mart: Grain, Facts and KPI Cards](/playbooks/salesforce-to-mart) — the guide behind steps 6 and 7.

Part 3 of this series shows how an existing report chaos gets turned into trusted, certified metrics.
