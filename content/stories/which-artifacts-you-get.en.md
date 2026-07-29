---
title: "Which Artifacts Do You Get?"
description: "The shared filenames Hub, Tools and Playbooks use for discovery, KPI cards, source scope, DQ backlog, mart design brief and decision brief — so results stay recognizable."
author: Thomas Lindackers
tags:
  - Artifacts
  - Governance Discovery
  - KPI Cards
  - Source Scope
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
hero: images/playbooks/which-artifacts-you-get-hero.png

---

Every tool in the Hub ends in one document: a KPI card, a source scope, a DQ backlog entry, a mart design brief, or a decision brief. These documents deliberately share the same filenames across every tool, playbook and supplier guide. Once you know what to look for, you can find the same artifact again — whether the path started with the Governance Advisor, a supplier playbook, or a single tool.

This guide is not another decision playbook. It answers one question only: which file do you get, and where do you use it next?

## Why fixed filenames matter

Without a shared naming convention, drift happens fast: one person names their KPI list `kpis_v2_final.csv`, another calls theirs `metrics-export.xlsx`. Both files can hold the same content, yet neither is discoverable, neither can be processed by automation, and nobody can tell at a glance whether they belong together.

The artifact standards solve this by turning the filename itself into the contract. `kpi-cards.csv` means the same thing in every tool, every playbook and every piece of documentation: a list of approved or in-progress KPI definitions with formula, grain, time logic, dimensions and owner — regardless of whether the file came from the KPI Definition card, a supplier-to-mart guide, or a manually maintained register.

## The standard artifacts

| Artifact | Filename | Produced by | Purpose |
|---|---|---|---|
| Discovery overview | `governance-discovery.md` | [Governance Discovery Canvas](/governance/discovery-canvas) | Summary of a discovery conversation: starting situation, stakeholders, open questions and next steps in one document. |
| KPI cards | `kpi-cards.csv` (+ JSON) | [KPI Definition](/tools/kpi-definition) | One row per metric with formula, grain, time logic, filters, dimensions and owner. |
| Source scope | `source-scope.csv` / `.md` / `.json` | [Source Scope Builder](/tools/source-scope-builder) | Approved, deferred and excluded source objects with rationale and review trigger. |
| DQ backlog | `dq-backlog.csv` / `.json` | [dbt DQ Rules Generator](/tools/dbt-dq-rules-generator) | Data-quality rule candidates and open quality issues that must be resolved before the mart build. |
| Mart design brief | `mart-design-brief.md` | [Mart Design Brief Generator](/tools/mart-design-brief-generator) | Business event, grain, fact/dimension candidates, history treatment and scope-out for a concrete mart. |
| Decision brief | `decision-brief.md` | [Decision Brief Generator](/tools/decision-brief-generator) | Context, recommended option, pilot scope, risks and open questions — condensed for a sponsor or architecture board. |

Each of these artifacts can stand on its own. In practice they usually build on each other: a `governance-discovery.md` from a first conversation leads to `kpi-cards.csv` and `source-scope.csv`, which in turn feed a `mart-design-brief.md`, while `dq-backlog.csv` accompanies the build. A `decision-brief.md` then summarizes these artifacts for an approval decision.

## Dual store: the payload stays normalized

These artifacts can be downloaded as a file and — where an account exists — saved to the governance workspace. Both paths use the same normalized payload; there is no second, divergent view logic that only exists for the file export. The filename is therefore not an arbitrary export detail but the visible side of the same data model that also lives in the database. This keeps the export, the saved workspace state and the documentation consistent without maintaining content twice.

## Where to start

This page explains filenames, not decisions. If you are not yet sure whether a KPI card, a source scope, or a discovery conversation is the right next step, start with the [Governance Advisor](/governance/berater). The Advisor walks through your starting situation and points from there to the right tool — this page then helps you recognize and reuse the result.

## Tools

- [Governance Discovery Canvas](/governance/discovery-canvas) — capture discovery conversations as `governance-discovery.md`.
- [KPI Definition](/tools/kpi-definition) — capture and version metrics as `kpi-cards.csv`.
- [Source Scope Builder](/tools/source-scope-builder) — document source objects, fields and relationships as `source-scope.csv` / `.md`.
- [dbt DQ Rules Generator](/tools/dbt-dq-rules-generator) — collect rule candidates and open issues as `dq-backlog.csv`.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — approve grain, facts, dimensions and scope-out as `mart-design-brief.md`.
- [Decision Brief Generator](/tools/decision-brief-generator) — condense discovery results into `decision-brief.md` for an approval decision.

## Related playbooks

- [From Stakeholder Interview to Table Model](/playbooks/from-stakeholder-interview-to-table-model) — how interview evidence becomes a mart design brief.
- [Which Source Should Load First?](/playbooks/which-source-to-load-first) — how a source scope feeds the portfolio decision.
- [Salesforce → Mart: Grain, Facts and KPI Cards](/playbooks/salesforce-to-mart) — an example where all artifacts on this page come together in one concrete guide.

## Next step

Open the tool that matches your current step, and download the result under the filename documented here or save it to the workspace. Then link it in a ticket, sprint or approval process under that exact name — so everyone on the team can tell which artifact is meant, without reinventing filenames every time.
