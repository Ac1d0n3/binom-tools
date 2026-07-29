---
title: "Workday → Mart: Workforce Headcount Snapshot"
description: "Turn an approved Workday Source Scope into an effective-dated headcount snapshot grain, Worker/Position/Organization fact and dimension candidates, standard KPIs and a mart design brief."
author: Thomas Lindackers
tags:
  - Workday
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
seriesPart: 4
hero: images/playbooks/workday-to-mart-hero.png
---

Workday data is effective-dated by design: a worker, position or organizational assignment can have a past, current and future state at the same time. An approved Source Scope tells you which objects, fields and security domains are allowed into the warehouse. It does not tell you how to turn effective-dated worker, position and organization records into a single, reproducible headcount number. This guide continues from the Workday Source Scope decision to a governed headcount snapshot mart.

The scope is intentionally narrow: a periodic headcount snapshot built from Worker, Position and Supervisory Organization, with an explicit as-of date, rather than an attempt to model every HR process at once.

## Problem

Teams that gain Workday access often try to answer “how many people do we have” directly against current-state worker records, which fails in predictable ways:

- a worker's position, manager and organization can change mid-period, so a “current state” query answers “who reports where today,” not “what was headcount in March”;
- multiple concurrent jobs for one worker can silently inflate a naive headcount count if each job record is treated as a separate person;
- future-dated changes (an already-approved transfer effective next month) can leak into a “current” report before they should be visible;
- rescinded or corrected events can leave a stale record active if correction handling isn't modeled;
- compensation, benefits and other sensitive facts get pulled into the same mart “while we're at it,” expanding the access boundary far beyond a headcount decision.

An explicit snapshot grain — one row per worker per as-of date — resolves the time-meaning problem before a single dashboard is built.

## Decision

### 1. Confirm the approved source objects

Use only the core workforce context already approved in the Workday Source Scope: Worker (including contingent-worker treatment), Position, Job Profile, Supervisory Organization and Company/Cost Center/Location. Staffing events, compensation and other conditional facts stay out of this pilot mart unless a separate decision brings them in.

### 2. Write one grain sentence

For a headcount snapshot pilot mart:

> One row per active worker per supervisory organization, as of a defined snapshot date.

This is materially different from “one row per worker,” which has no time meaning and cannot answer a historical headcount question at all.

### 3. Move from objects to facts and dimensions

Classify approved fields by role, and resolve effective-dating before anything else.

### 4. Attach standard KPIs to the grain

Attach KPI logic only once the snapshot cadence and effective-date rule are fixed, so headcount and movement metrics use the same as-of-date discipline.

## Grain and fact/dim candidates

For a headcount-snapshot pilot mart:

| Candidate | Role | Notes |
|---|---|---|
| Worker (as-of snapshot date) | Fact anchor | One row per worker per snapshot date; resolve primary vs. additional job before counting |
| Headcount / FTE | Additive fact | Define whether contingent workers and multiple concurrent jobs are counted, and how |
| Position | Dimension | Job profile, position ID and vacancy status |
| Supervisory Organization | Dimension | Effective-dated hierarchy; do not apply today's hierarchy to a historical snapshot |
| Company / Cost Center / Location | Dimension | Organizational and geographic scope boundary |
| Worker Type | Dimension attribute | Employee vs. contingent worker distinction, required for correct headcount rules |
| Hire / Termination Date | Fact attribute | Drives new-hire and attrition metrics; must respect rescind and correction handling |
| Snapshot Date | Date dimension key | The defined as-of date for the periodic snapshot, distinct from event timestamps |
| Compensation / Payroll | Excluded from this pilot | Requires a separate purpose, population and security-domain approval |

Rescinded or corrected events must be resolved before the snapshot is taken; a snapshot should never expose a future-dated change before its effective date.

## PII and skip

Direct identifiers, compensation and payroll detail, health/benefits information and security-domain scoping are covered in the Workday Source Scope playbook and are not repeated here. This pilot mart intentionally excludes worker-profile fields and sensitive facts that are not required for a headcount count. See [Which Workday Objects to Load — and Which to Skip](/stories/workday-tables-for-analytics) for the full classification.

## Standard KPIs

Attach the pilot mart to governed KPI cards rather than local formulas built against a raw effective-dated table:

- **Headcount / FTE** — count of active workers (or FTE-weighted equivalent) at a defined snapshot date and organizational scope.
- **New Hire Rate** — hires in the period divided by average headcount, with an explicit population.
- **Attrition Rate** — terminations in the period divided by average headcount, with voluntary/involuntary split if required.
- **Span of Control** — direct reports per manager, using the effective-dated supervisory-organization hierarchy at the snapshot date.

Define population, numerator, denominator, snapshot cadence and effective-date treatment explicitly using [Define a KPI](/playbooks/define-kpi), and capture the versioned definition with [KPI Definition](/tools/kpi-definition).

## Artifact

Produce three linked artifacts for the pilot mart:

- `source-scope.csv` — the approved Workday object, field and security-domain scope carried over from the Source Scope decision.
- `kpi-cards.csv` — one row per KPI with population, numerator, denominator, snapshot grain, effective-date treatment and owner.
- `mart-design-brief.md` — the snapshot-grain sentence, effective-date and correction rules, fact/dimension list and approvals for the pilot mart.

Record unresolved effective-date edge cases (concurrent jobs, rescinds, cross-organization transfers) in `dq-backlog.csv` rather than resolving them silently inside a snapshot query.

## Tools and next steps

- [Source Scope Builder](/tools/source-scope-builder) — confirm or extend the approved Workday object and security-domain scope.
- [PII Recommend](/tools/pii-recommend-generator) — get a starting classification for any worker-related field not yet classified.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — turn the snapshot grain and effective-date rules into a reviewable brief.
- [KPI Definition](/tools/kpi-definition) — capture the standard headcount KPIs as versioned, owned definitions.
- Unsure how much workforce scope to take on first? The [Governance Advisor](/governance/berater) helps sequence the decision responsibly.

## Related playbooks

- [Which Workday Objects to Load — and Which to Skip](/stories/workday-tables-for-analytics) — the Phase-B load/skip decision this guide builds on.
- [From Stakeholder Interview to Table Model](/playbooks/from-stakeholder-interview-to-table-model) — the general method for turning evidence into grain, fact and dimension decisions, including effective-dated sources.
- [Which Source Should Load First?](/playbooks/which-source-to-load-first) — how a Workday headcount pilot compares to other candidate sources.

Continue exploring Workday in the [Supplier Library: Workday](/suppliers/workday) for the wider platform and integration context.
