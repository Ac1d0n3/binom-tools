---
title: "Proof: From Stakeholder Interview to Approved KPI Cards"
description: "An anonymized example of how a sales team moved from conflicting pipeline reports through structured interviews to approved KPI cards and a mart design brief."
author: Thomas Lindackers
tags:
  - Proof
  - KPI Governance
  - Stakeholder Interviews
  - Mart Design
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
seriesPart: 1
hero: images/playbooks/proof-stakeholder-to-kpi-cards-hero.png
---

A mid-sized B2B sales team came into a discovery conversation with a familiar problem: three dashboards showed three different numbers for "open pipeline," and nobody in the meeting could explain why. This anonymized story shows how, from that starting point, a few structured steps led to approved KPI cards and a mart design brief — with no customer names, figures, or identifying details.

## Starting situation

The sales team used Salesforce as its CRM and a BI tool for reporting. Three people had independently built pipeline dashboards: sales leadership, sales operations, and a business analyst from finance. All three used the term "open pipeline," but with different stage filters, different as-of dates, and different treatment of opportunities missing a close date.

The trigger for the discovery conversation was not a project kickoff but a recurring annoyance: every monthly meeting spent the first ten minutes arguing about which number was "correct" before any actual discussion could happen.

## Steps

1. **Structure the discovery conversation.** Instead of jumping straight to formulas, the team first clarified who makes which decision with "open pipeline." Sales leadership wanted to know where to intervene. Sales operations wanted to check forecast accuracy. Finance wanted a conservative number for monthly planning. The team followed the method from [From Stakeholder Interview to Table Model](/playbooks/from-stakeholder-interview-to-table-model): evidence, interpretation, and decision were documented separately instead of treating interview statements as modeling decisions directly.

2. **Write a grain sentence before talking about metrics.** From the three statements, the team distilled a shared grain: one row per opportunity, not per opportunity line item, because product detail was not required for any of the three questions. This decision alone resolved part of the conflict, since one of the three dashboards had unintentionally been aggregating at line-item level.

3. **Capture KPI requirements in a structured way.** For "open pipeline," the [KPI Requirements Intake](/tools/kpi-requirements-intake) form was used to lay the three competing definitions side by side: different stage populations, different handling of missing close dates, different snapshot-versus-live logic. None of the three variants was "wrong" — they answered different questions under the same name.

4. **Decide instead of compromise.** The named KPI owner from sales operations made the call: "open pipeline" would from now on be a single, named definition; the other two views would get their own clearly distinguished names (`Pipeline at Risk`, `Committed Forecast`) instead of sharing a term with different underlying logic.

5. **Version the KPI cards.** All three definitions were captured with the [KPI Definition](/tools/kpi-definition) tool as KPI cards with formula, grain, time logic, filters and owner — not as an Excel note, but as a versioned, reusable artifact.

6. **Derive a mart design brief.** From the shared grain and the three KPI cards, the [Mart Design Brief Generator](/tools/mart-design-brief-generator) produced a brief for a pipeline mart: a fact at opportunity grain, with the required dimensions (account, owner, stage, date) and a documented snapshot requirement for stage-movement analysis.

## Artifacts

This pass produced the standard artifacts also described in [Which Artifacts Do You Get?](/playbooks/which-artifacts-you-get):

- **`governance-discovery.md`** — a summary of the discovery conversation with the three competing views and open questions.
- **`kpi-cards.csv`** — three named, distinguishable KPI cards instead of one ambiguous metric.
- **`mart-design-brief.md`** — grain, fact/dimension candidates and snapshot requirement for the pipeline mart, approved before building the physical table.

## What we learned

The most valuable part of this pass was not the formula itself, but the realization that three technically correct calculations sharing one name can create a trust problem that no BI optimization fixes. Only splitting the term into three named metrics, each with an owner, ended the recurring debate in the monthly meeting.

A second lesson was about sequencing: without the grain sentence fixed up front, the KPI discussion would likely have landed back on "whose number is right" instead of "which question does this number answer."

> **Note:** This story is an anonymized, condensed example illustrating the method. It does not replace legal, privacy or vendor advice and is not a case study of a specific customer.

## Tools and next steps

- [KPI Requirements Intake](/tools/kpi-requirements-intake) — lay competing definitions side by side in a structured way.
- [KPI Definition](/tools/kpi-definition) — version approved metrics as `kpi-cards.csv`.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — approve grain, facts and dimensions as `mart-design-brief.md`.
- Unsure whether a discovery conversation or a KPI card is the right next step? The [Governance Advisor](/governance/berater) walks through your starting situation.

## Related playbooks

- [From Stakeholder Interview to Table Model](/playbooks/from-stakeholder-interview-to-table-model) — the full method behind steps 1 and 2.
- [Which Artifacts Do You Get?](/playbooks/which-artifacts-you-get) — the filenames used throughout this story.
- [Salesforce → Mart: Grain, Facts and KPI Cards](/playbooks/salesforce-to-mart) — a deeper guide for the same source type.

Part 2 of this series shows the same approach for a new SaaS source, from the load/skip decision through to the first pilot mart.
