---
title: "SaaS Exports: Tables You Should Not Load"
description: "A vendor-neutral decision framework for separating authoritative business data from logs, caches, duplicate snapshots, free text and attachments before loading a SaaS export into an analytical platform."
author: Thomas Lindackers
tags:
  - source scope
  - SaaS exports
  - data ingestion
  - data governance
  - PII
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/saas-exports-tables-to-skip-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 2
---

A SaaS export is usually designed for product operation, support, migration or backup. It is not an approved analytical model. The export may contain core business records, but it may also contain internal configuration, UI state, temporary structures, repeated snapshots, verbose audit events, unrestricted text and binary content.

The source-scope decision therefore comes before connector configuration. The first question is not whether a table can be extracted. It is whether a defined decision, control or data product requires it and whether its meaning, authority, grain, risk and operating cost can be governed.

## Problem

Vendor exports expose more structures than analytics normally needs because the application must support workflows, permissions, automation, integrations, recovery, user interfaces and operational diagnostics. Those implementation concerns create tables that look technically available but do not automatically represent useful business data.

![A SaaS Export Is Not an Analytics Model](images/playbooks/saas-exports-tables-to-skip-img1-en.png)

A practical inventory usually contains seven categories:

1. **Business records** such as accounts, subscriptions, cases, orders or projects.
2. **Relationship tables** that connect business entities, users, products, territories or permissions.
3. **Reference and configuration data** such as status codes, categories, routing rules or feature settings.
4. **History and snapshots** that may represent deliberate business history, technical change tracking or repeated copies of the same current state.
5. **Audit and system logs** generated for traceability, troubleshooting, security or platform operation.
6. **UI caches and temporary structures** that improve product performance but have no durable analytical meaning.
7. **Free text, files and attachments** whose content may be sensitive, difficult to classify and expensive to retain.

None of these categories is an automatic include or exclude decision. A business table can still be unsuitable if its authority or grain is unclear. A history table can be essential if it records economically meaningful state transitions. An audit log can justify a separate security product. Attachment metadata may be useful even when the file content remains outside the analytical platform.

The central risk is accidental scope. Loading every available table creates several failure modes:

- duplicate representations of the same business event produce double counting;
- technical timestamps are mistaken for intentional business history;
- current-state and snapshot tables are joined without an authority rule;
- operational events are mixed with business facts at incompatible grains;
- free text and attachments expand the PII, retention and access boundary;
- unused tables increase extraction volume, model complexity, test effort and incident surface.

Storage may be inexpensive compared with governance, interpretation and lifecycle cost. “Load everything because storage is cheap” is therefore not a neutral decision. It transfers unresolved source questions into every downstream model.

## Decision

Apply the same gated decision test to every export table, regardless of vendor.

![Use a Decision Test Before Loading Any Table](images/playbooks/saas-exports-tables-to-skip-img2-en.png)

### 1. Does a defined decision need it?

Name the report, KPI, control, workflow or data product that consumes the table. A generic statement such as “we may need it later” is not a sufficient use case.

If no named decision or control requires the data, exclude it from the current scope. Record the decision so that exclusion is visible rather than forgotten.

### 2. Does it add unique business meaning?

Determine whether the table contributes a new entity, relationship, state transition, classification or event. Denormalized convenience exports, replicated API views and repeated snapshots often contain the same meaning in a different shape.

Where several structures represent the same concept, select one authority. Do not preserve duplicates merely because they are available.

### 3. Is the authoritative source known?

State which table or object is the system of record for the analytical concept. Authority must be explicit when current-state tables, history tables, integration copies and convenience exports overlap.

If authority is unresolved, defer the table rather than allowing downstream teams to choose independently.

### 4. Can its grain and keys be stated?

Describe one row in business terms. Identify the business key, technical key, relationship keys and expected uniqueness. The table should not be loaded as a fact source if the target grain cannot be expressed and tested.

An event log, for example, may be at one row per system action, while the analytical fact is one row per order line. Both can be valid products, but they are not interchangeable.

### 5. Is history intentionally required?

A timestamp does not make a table useful history. Confirm which change is recorded, whether the sequence is complete, how corrections appear and which decision needs the historical state.

Useful examples include status history for funnel analytics, contract version history for effective-date reporting or ownership history for accountability. Repeated extracts of the same current state without a temporal contract are normally duplicate snapshots, not governed history.

### 6. Are PII, retention and access justified?

Classify identifiers, free text, notes, attachments and user-generated content before ingestion. Define permitted use, access boundaries, retention, deletion propagation and whether content-level search is actually required.

Sensitive content without an approved purpose should be excluded. Where only metadata is needed, load file name, type, owner, timestamp and classification without copying the binary file.

### 7. Can quality and cost be controlled?

Confirm that volume, freshness, completeness, uniqueness and referential integrity can be measured. Estimate extraction, storage, transformation, indexing, security and support cost.

A technically extractable table is still out of scope when its quality cannot be validated or its cost is disproportionate to the supported decision.

The result is one of four outcomes:

- **Include:** required meaning, authority, grain, controls and ownership are clear.
- **Defer:** the requirement is valid, but authority, grain, ownership, access or quality remains unresolved.
- **Exclude:** no supported use case exists, the content is duplicative, or risk and cost are unjustified.
- **Separate product:** an operational, security or compliance use case exists, but the data should not be mixed with the business analytical product.

## Checklist

Use the following checklist before approving a table for extraction.

![Common Skip Patterns — and Their Exceptions](images/playbooks/saas-exports-tables-to-skip-img3-en.png)

### Business meaning

- Which business decision, KPI, control or workflow requires the table?
- What new entity, relationship, state or event does it add?
- Is the table authoritative, derived, replicated or only convenient?
- Can one row be described in business terms?
- Which keys establish uniqueness and relationships?

### History

- Is the table current state, event history, effective-dated history or a repeated snapshot?
- Which change is analytically meaningful?
- Are late corrections, deletions and reprocessing understood?
- Could the same record appear in more than one export structure?
- Is an authority rule documented to prevent double counting?

### Risk and lifecycle

- Does the table contain direct identifiers, quasi-identifiers, secrets, notes or unrestricted text?
- Does it reference files or contain binary content?
- Is retention defined for both the source and the analytical copy?
- Can source deletions and legal holds be propagated?
- Are access, masking and permitted use approved?

### Operability

- Can completeness, uniqueness, freshness and relationships be tested?
- Is the expected volume proportionate to the use case?
- Is there an owner for semantic meaning and an owner for operation?
- Can the table be supported when the vendor changes its schema?
- Is a review trigger defined if the table is deferred or excluded?

### Common skip patterns and valid exceptions

**UI caches and temporary structures** are usually excluded because they represent product implementation state. Include them only when a named control depends on them and the vendor contract establishes stable meaning.

**Unused feature tables** are usually excluded because they create empty or misleading structures. Reconsider them when the corresponding business feature is activated and a consumer is identified.

**Duplicate denormalized snapshots** are usually excluded because they repeat authoritative data and create double-counting risk. Include one only when it becomes the approved authority for a specific grain and the competing representation is explicitly rejected.

**Verbose audit logs** are usually separated from business analytics. Include them in a governed security, operational or compliance product when the event model, retention, access and evidence requirement are defined.

**Unrestricted free-text blobs** are usually excluded. Include selected text only after classification, purpose approval, retention design and quality controls are in place.

**Files and attachments** are usually not copied into the warehouse. Include attachment metadata when it supports completeness, search routing or compliance evidence. Copy content only when a governed search, legal, regulatory or analytical use case requires it.

**System configuration noise** is usually excluded. Include selected configuration records when they explain business behavior, routing, thresholds or policy decisions and can be versioned as reference data.

## Artifact

The approved output is a governed source-scope register with one decision per table or export object.

![Record Skip Decisions as Governed Source Scope](images/playbooks/saas-exports-tables-to-skip-img4-en.png)

Use three explicit portfolios: **Included Now**, **Deferred** and **Excluded**. Do not leave unreviewed tables in an implicit backlog.

A minimum decision record contains:

| Field | Purpose |
|---|---|
| Table or object | Exact export structure under review |
| Category | Business, relationship, reference, history, audit, cache, text or attachment |
| Use case | Named decision, control or data product |
| Authority | System-of-record statement and competing representations |
| Grain | Business meaning of one row |
| Keys | Business key, technical key and relationship keys |
| History need | Current, event, effective-dated, snapshot or none |
| PII or free text | Classification and content risk |
| Decision | Include, defer, exclude or separate product |
| Rationale | Evidence supporting the decision |
| Owner | Accountable business or governance role |
| Review trigger | Requirement, feature, control, incident or policy change |
| Downstream impact | Products, marts, semantic models and controls affected |

The register should produce five operational outputs:

1. **Field allowlist** — only approved fields from included tables.
2. **Source contract input** — grain, keys, authority, freshness and change expectations.
3. **Retention boundary** — what will and will not enter the analytical lifecycle.
4. **Expected volume reduction** — evidence that scope control reduces extraction and operating cost.
5. **Unresolved questions** — explicit dependencies for deferred decisions.

### Example decision register

| Table | Category | Decision | Rationale | Review trigger |
|---|---|---|---|---|
| `subscription` | Business record | Include | Authoritative current subscription at one row per subscription | Contract model changes |
| `subscription_status_history` | History | Include | Required for conversion and churn-stage analysis | History semantics change |
| `subscription_export_snapshot` | Duplicate snapshot | Exclude | Repeats current subscription state without unique temporal meaning | New regulatory snapshot requirement |
| `ui_recent_items` | UI cache | Exclude | User-interface state with no supported analytical decision | Named product-usage control |
| `system_audit_event` | Audit log | Separate product | Needed for security evidence at system-event grain | Security retention policy changes |
| `attachment` | File metadata | Defer | Metadata may support case completeness; binary content not approved | Search or compliance requirement approved |
| `case_notes` | Free text | Defer | Potential service insight, but classification, access and retention are unresolved | Approved text-analytics purpose and controls |

A skipped table can be reconsidered, but only through a new requirement, control need or material source change. Reconsideration must create a new decision record; it should not silently bypass the original exclusion rationale.

## Tools

Use the [Source Scope Builder](/tools/source-scope-builder) to capture table-level include, defer, exclude and separate-product decisions with authority, grain, risk and review triggers.

Use the [Metadata Export Generator](/tools/meta-export-generator) to turn the approved scope into reusable metadata for source contracts, field allowlists and downstream implementation handoff.

The tools support the decision process; they do not replace Data Owner, Steward, Architecture, Privacy or Security approval where those roles are required.

## Resources

- [Salesforce Tables for Analytics](/stories/salesforce-tables-for-analytics) — Part 1 of this series, focused on relationship-centered scope decisions for a concrete SaaS source.
- Source export inventory from the vendor or connector.
- Data classification and retention policy.
- Existing report, KPI and control inventory.
- Source contract, schema documentation and change history.
- Data product backlog and consumer map.

The most useful source evidence is not the total number of available tables. It is the relationship between a named business need and an authoritative, testable source structure.

## Playbooks

Apply [Before Building the First Table](/playbooks/before-building-the-first-table) before implementation. Reuse its business-question, grain, ownership and acceptance-criteria decisions instead of recreating them inside the source-scope register.

This story adds the source-export boundary: the playbook establishes what the first data product must answer; this decision framework establishes which export structures are allowed to support it.

## Next step

Approve the source-scope register before configuring the production extraction. Hand the included tables and field allowlist to ingestion design, route justified audit or security data into a separate product, and keep deferred and excluded structures visible with owners and review triggers.

The correct outcome is not the largest possible landing zone. It is the smallest governed source scope that supports the required decisions without losing authority, history, control or evidence.
