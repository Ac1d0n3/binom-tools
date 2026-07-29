---
title: "Same Entity, Two Systems — Which Source Is Authoritative?"
description: "Assign authority by entity, attribute, event and time context; separate identity matching, survivorship and publishing; and preserve provenance and governed exceptions across several sources."
author: Thomas Lindackers
tags:
  - Multi-source Governance
  - Data Authority
  - Identity Resolution
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/multi-source-entity-authority-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 9
---

When the same customer, supplier, worker, product or account exists in several systems, “choose the master system” is usually too broad. CRM, billing, service, identity and consent platforms may each own different attributes, events and time contexts.

The governed decision therefore assigns authority at the level where meaning actually differs: entity identity, attribute group, business event, effective period and analytical use.

## Problem

Two records with the same name do not automatically represent the same entity, and two systems containing the same attribute do not automatically have equal authority. A CRM may own the sales relationship, an ERP the legal account and invoice balance, a service platform the support state, and a consent platform the communication preference.

![Same name does not mean same authority](images/playbooks/multi-source-entity-authority-img1-en.png)

Broad statements create hidden conflicts:

- “CRM is the customer master” ignores legal, billing, service and consent facts.
- “The newest timestamp wins” confuses technical freshness with business authority.
- “Keep one golden record” can hide legitimate contextual differences.
- Automatic matching can merge different entities.
- Last-write-wins can overwrite a corrected authoritative value with a later replica.
- Downstream teams invent different precedence rules and publish conflicting metrics.

Authority must answer four questions for every relevant fact:

1. Which system creates the fact?
2. Which role or system is allowed to correct it?
3. Which time context applies?
4. Which downstream decisions may use it?

### Distinguish four source roles

- **System of entry:** where a user or process first captures the value.
- **System of record:** where the organization maintains the accountable operational state.
- **System of reference:** the governed source used to standardize or enrich other systems.
- **Analytical trusted source:** the approved representation for a specific analytical context.

These roles can be the same, but they do not have to be.

## Decision

### 1. Define the entity and matching boundary

State the business meaning of the entity, included populations and excluded subtypes. Define identity keys, crosswalk keys and matching scope before discussing which attributes survive.

Identity resolution asks only whether records represent the same business entity. It must not silently decide which value is trusted.

### 2. Assign authority by attribute, event and time

![Assign authority by attribute, event and time](images/playbooks/multi-source-entity-authority-img2-en.png)

Create an authority matrix for items such as:

- legal customer identity;
- preferred contact details;
- consent and communication preference;
- sales ownership;
- active contract status;
- invoice balance;
- service entitlement;
- support-case state.

For every row record source of entry, authoritative source, analytical trusted source, effective date, freshness expectation, conflict rule, fallback and owner.

Authority also depends on time:

- current operational state;
- historically effective state;
- event state at transaction time;
- corrected restatement.

“Latest record wins” is valid only when explicitly approved for that attribute and time context.

### 3. Separate match, survive and publish

![Resolve overlap with match, survivorship and lineage](images/playbooks/multi-source-entity-authority-img3-en.png)

Use a governed flow:

```text
Source Records
→ Standardize Keys
→ Match and Identity Resolution
→ Confidence and Exception Gate
→ Attribute Survivorship
→ Conformed Entity or Source-Specific Views
→ Downstream Products
```

The three decisions are distinct:

1. **Match:** Do the records represent the same entity?
2. **Survive:** Which value is trusted for each attribute and time?
3. **Publish:** Should consumers receive one conformed entity or several contextual views?

A high match confidence does not authorize automatic survivorship. A conformed entity is appropriate only where shared identity and attribute rules are stable. Preserve source-specific views when meanings legitimately differ or conflict resolution remains contextual.

### 4. Define conflict, fallback and correction rules

For each governed attribute or event, specify:

- precedence between sources;
- when a fallback is allowed;
- maximum tolerated latency;
- how null, stale and invalid values are treated;
- how corrections and restatements propagate;
- how unresolved conflicts are retained;
- which owner decides exceptions.

Do not erase the losing values. Preserve provenance so the decision is explainable and reversible.

### 5. Govern identity exceptions

Mandatory controls include:

- source-to-conformed crosswalk keys;
- match confidence and reason codes;
- manual-review threshold;
- merge and split history;
- unresolved duplicate queue;
- false-positive remediation;
- downstream impact analysis before identity changes;
- SLA and escalation owner for steward review.

Ambiguous matches should remain unresolved rather than being forced into one entity.

### 6. Preserve provenance and effective dating

Every published value should retain enough evidence to identify:

- contributing source and source key;
- source extraction and event time;
- authority-rule version;
- effective start and end;
- conflict or fallback status;
- correction and review history.

Without provenance, survivorship becomes an irreversible overwrite.

### 7. Control downstream migration

Changing an authority rule can change identifiers, dimensions, filters and historical metrics. Treat the change as a consumer-contract migration. Identify affected data products, compare old and new results, communicate the effective date and preserve the previous rule long enough for reconciliation.

## Checklist

### Entity and identity

- [ ] The entity has a business definition and explicit population.
- [ ] Identity keys and matching scope are approved.
- [ ] Crosswalk keys preserve every source identity.
- [ ] Match confidence and review thresholds are defined.
- [ ] Merge, split and unresolved-duplicate behavior is documented.

### Authority and survivorship

- [ ] Authority is assigned per attribute or event.
- [ ] Current, historical, transaction-time and restated contexts are separated.
- [ ] Precedence, fallback and latency rules are explicit.
- [ ] Null, stale, invalid and conflicting values have rules.
- [ ] Business Owner and Steward approve the matrix.

### Publishing and lineage

- [ ] The choice between conformed entity and contextual views is explicit.
- [ ] Provenance is retained for every published value.
- [ ] Authority-rule versions and effective dates are queryable.
- [ ] Corrections propagate to affected products.
- [ ] Consumer migration and reconciliation are planned.

### Exceptions

- [ ] An exception queue exists.
- [ ] Steward SLA and escalation owner are named.
- [ ] Temporary fallbacks have expiry and evidence.
- [ ] False-positive merges can be reversed.
- [ ] Review triggers cover source, policy and process changes.

## Artifact

Create a governed authority record with four linked sections.

![Record the entity authority decision](images/playbooks/multi-source-entity-authority-img4-en.png)

### Entity definition

- entity name and business meaning;
- identity keys and matching scope;
- included populations;
- excluded or separate entity types.

### Authority matrix

| Attribute or event | Source of entry | Authoritative source | Analytical trusted source | Time context | Precedence or fallback | Owner |
|---|---|---|---|---|---|---|
| Legal identity | CRM or onboarding | Billing / ERP | Conformed customer view | Effective-dated | ERP unless approved correction | Customer Data Owner |
| Consent | Consent platform | Consent platform | Consent-controlled consumer view | Event and current | No fallback without approval | Privacy Owner |
| Invoice balance | ERP | ERP | Finance fact | Posting period | No CRM override | Finance Data Owner |
| Support state | Service platform | Service platform | Service fact | Event and current | Source-specific | Service Owner |

### Exception rules

Record match-confidence threshold, conflict type, steward queue, review SLA, escalation owner and any temporary fallback with expiry.

### Downstream contract

Record conformed key, provenance fields, freshness and quality controls, affected products, change plan and review trigger.

Required outputs are the approved authority matrix, crosswalk and match policy, survivorship rules, exception queue, lineage requirement and consumer migration actions. Matching or formula tools may implement the decision, but they cannot assign business authority.

## Tools

Use the [Source Scope Builder](/tools/source-scope-builder) to document each source contribution and competing representation. Use the [Metadata Export Generator](/tools/meta-export-generator) to publish authority, precedence, provenance and review metadata into reusable contracts.

## Resources

- Source-system data dictionaries and source contracts.
- Identity crosswalk and duplicate-management records.
- Data-ownership and stewardship decision-rights model.
- Consumer inventory and lineage graph.
- Correction, merge, split and incident history.

## Playbooks

- [Before Building the First Table](/playbooks/before-building-the-first-table) — define the decision, grain and acceptance criteria before creating the conformed product.
- [Data Ownership and Stewardship](/playbooks/data-ownership-stewardship) — assign authority, conflict resolution, approval and escalation rights.

## Next step

Approve the entity definition and authority matrix before building matching, survivorship or golden-record logic. Then implement the rules as versioned metadata with lineage and an exception workflow. This closes the Source Load Decisions series by connecting source onboarding to cross-source authority.
