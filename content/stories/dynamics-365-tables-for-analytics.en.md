---
title: "Which Dynamics 365 Tables to Load — and Which to Skip"
description: "Define a governed Dynamics 365 and Dataverse source scope from the configured business process, target grain, table relationships, activities, option semantics and security boundary."
author: Thomas Lindackers
tags:
  - Dynamics 365
  - Dataverse
  - Source Scope
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/dynamics-365-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 5
---

A Dynamics 365 environment is a configured Dataverse application landscape. Standard table availability does not prove that the table is used, authoritative or suitable for the selected analytical process.

The objective is not to publish a generic list of tables that every Dynamics 365 environment should load. The objective is a reviewable **Source Scope** for one decision or compatible group of decisions. It records why each object, table, relationship or field is included, conditional, deferred, excluded or separated.

## Problem

A catalog-first approach starts with the Dataverse table and solution catalog, a connector inventory or an export preview. Because a structure exists and can be queried, it is selected “for later.” The result is a broad landing zone whose business meaning, grain and access boundary are resolved only after downstream teams begin joining data.

A practical inventory can contain:

- account, contact, lead and opportunity tables
- opportunity product, quote, order, invoice and other line-item tables
- product, price, currency, date and organizational references
- users, teams, business units and ownership structures
- activities, ActivityPointer and activity-party relationships
- custom tables, managed solutions and application extensions
- audit, annotations, attachments, telemetry and configuration tables

These categories are not automatic include or exclude decisions. Their meaning depends on the configured application, installed features, custom extensions and the selected business process.

![Which Dynamics 365 Tables to Load — and Which to Skip](images/playbooks/dynamics-365-tables-for-analytics-img1-en.png)

Typical failure modes are predictable:

- technically available structures are mistaken for approved analytical sources;
- current-state records, history, events and snapshots are mixed without a time contract;
- parent, child, header, line or association structures are joined without a declared target grain;
- display labels are treated as stable business definitions while the configured semantics differ;
- personal data, free text and attachments expand the access and retention boundary;
- custom process structures are ignored because standard names look more familiar;
- every downstream product creates its own interpretation, duplication rule and exception handling.

### Different decisions require different scopes

- Pipeline by opportunity and owner requires opportunity grain, configured status semantics and selected account, owner and currency context.
- Revenue by product requires opportunity-product, quote-line, order-line or invoice-line grain; those line structures must not be blended into a header fact.
- Service analytics requires the actual case or service application tables and the configured lifecycle, not every installed sales table.
- Activity analysis requires a defined activity subtype, participant role, date and target-grain rule; retrieving both the activity parent and subtype without a duplication rule inflates counts.

The correct source scope is therefore contextual. It must be derived from the decision and validated against the actual configuration.

## Decision

Define the scope in the following order. Connector and extraction design come later.

### 1. State the decision, population and target grain

Write the requirement as a decision with a user, action, population, time horizon and grain. “Build a dashboard from Dynamics 365 environment” is too weak because it does not establish which records, relationships or historical states are required.

For every intended fact, describe one row in business terms. Declare the event that creates the fact, the dimensions required to interpret it and the date that controls reporting. A source row is not automatically an analytical fact.

### 2. Classify structures by their business role

![Classify the source structures by business role](images/playbooks/dynamics-365-tables-for-analytics-img2-en.png)

### Required for the selected process
- account or contact when required by the configured process
- lead or opportunity
- opportunity product when the fact is line-grained
- product, price and currency references
- owner, user, team and business unit where accountability or security scope matters
- required state, status, date and organization semantics

### Conditional
- quote, sales order and invoice
- case or service tables
- activities and activity parties
- selected audit or status history
- campaign or marketing tables
- custom tables and solution extensions

### Skip or separate unless required
- unused application modules
- duplicate activity representations
- unrestricted annotations and attachments
- verbose audit data without a control case
- platform telemetry and configuration noise
- convenience exports with unclear authority

The groups are patterns, not universal lists. The actual application configuration, custom objects, installed modules, security and business meaning override generic examples.

### 3. Model relationships before joining

Use a relationship-centered model instead of a flat table list:

```text
Lead
→ Opportunity
→ Quote
→ Sales Order
→ Invoice

Supporting: Account or Contact; line items; Product and Price List; Owner, Team and Business Unit
```

For every relationship, document:

- business event represented by the relationship
- header or line grain
- key, cardinality and optionality
- state and status meaning
- currency and date semantics
- current versus historical meaning
- ownership, organization and PII classification

Do not approve a join merely because both keys are available. A technically valid join can still duplicate facts, apply current context to historical events or introduce an unresolved many-to-many relationship.

![Respect relationships and event grain](images/playbooks/dynamics-365-tables-for-analytics-img3-en.png)

Explicitly test these failure cases:

- header and line amounts are double counted
- ActivityPointer and activity subtype rows are both counted as separate activities
- inactive records are silently removed even though the decision requires them
- choice labels are lost and only numeric values remain
- custom process tables are omitted because standard names look familiar

### 4. Separate current state, events, history and snapshots

The source scope must distinguish:

- current record state
- configured state and status transitions
- selected audit history
- event facts derived from explicit process milestones
- platform snapshots for point-in-time reporting

A generic created or updated timestamp is not proof of complete business history. Audit data is not automatically a process event log. If point-in-time reconstruction is required, define which state must be retained, how corrections appear and whether platform snapshots are needed.

### 5. Apply field, privacy and access controls

Object or table inclusion does not authorize all fields. Create an allowlist and make separate decisions for:

- personal data in account, contact and activity fields
- descriptions, annotations and email content
- attachments and binary content
- user, team and organizational access boundaries
- deactivation, merge and deletion behavior

Prefer a controlled category, count, flag or derived age where the decision does not need raw content. Define permitted use, role and domain access, masking, retention, deletion propagation and incident ownership before extraction.

### 6. Assign an explicit decision state

Use more than a binary load-or-ignore choice:

- **Include** — required meaning, authority, grain, access and quality controls are approved.
- **Conditional** — the source is needed only for a named variant or metric and has an explicit activation condition.
- **Defer** — the requirement is valid, but ownership, history, security, quality or extraction evidence is incomplete.
- **Exclude** — no approved analytical purpose exists, or the structure is duplicative, unstable or unjustifiably risky.
- **Separate product** — an operational, security, audit or restricted use case exists but must not be mixed with the general business product.

Every non-included decision needs a rationale and review trigger.

### 7. Validate against the configured system

Names and standard examples are not contracts. Review the actual process with business owners, application administrators, privacy, security and the integration team. Confirm custom objects, activated modules, status models, labels, keys, access and lifecycle behavior in the real tenant or instance.

## Checklist

### Decision and grain

- [ ] The business question names the user, action, population and time horizon.
- [ ] Every fact has a declared business grain.
- [ ] The event and reporting date are explicit.
- [ ] Current state, event history and snapshots are separated.
- [ ] Measures and status semantics have accountable owners.

### Source structures and relationships

- [ ] Every included structure supports a named requirement.
- [ ] Custom and application-specific structures have been reviewed.
- [ ] Keys, cardinality and optionality are documented.
- [ ] Duplicate, orphan and many-to-many behavior is defined.
- [ ] Reference labels and codes have an approved mapping.

### Risk and lifecycle

- [ ] A field allowlist exists.
- [ ] PII, sensitive attributes and free text are classified.
- [ ] Attachments and binary content have a separate decision.
- [ ] Deletion, merge, archive or deactivation behavior is defined.
- [ ] Retention and permitted use are approved.

### Operations

- [ ] Freshness and expected volume are understood.
- [ ] Completeness, uniqueness and relationship quality can be tested.
- [ ] Business and technical owners are named.
- [ ] Reconciliation controls are defined.
- [ ] Every defer, exclude or separate-product decision has a review trigger.

## Artifact

Create one governed source-scope register per data product or compatible decision portfolio. It is the approved contract between the business requirement and later extraction design.

![Document the source-scope decision](images/playbooks/dynamics-365-tables-for-analytics-img4-en.png)

### Mandatory fields

| Field | Purpose |
| logical and display table name | Required decision evidence for the approved source scope |
| application or solution | Required decision evidence for the approved source scope |
| business purpose | Required decision evidence for the approved source scope |
| target data product | Required decision evidence for the approved source scope |
| target-grain contribution | Required decision evidence for the approved source scope |
| required columns | Required decision evidence for the approved source scope |
| relationship, key and cardinality | Required decision evidence for the approved source scope |
| choice, state and status semantics | Required decision evidence for the approved source scope |
| current, audit, event or snapshot need | Required decision evidence for the approved source scope |
| currency, time-zone and organization scope | Required decision evidence for the approved source scope |
| PII, notes and attachment risk | Required decision evidence for the approved source scope |
| deactivation, merge and deletion behavior | Required decision evidence for the approved source scope |
| freshness and retention | Required decision evidence for the approved source scope |
| business and technical owner | Required decision evidence for the approved source scope |
| decision and review trigger | Required decision evidence for the approved source scope |

### Required outputs

- approved table and column scope
- relationship map
- choice and status mapping contract
- explicit skip list
- reconciliation requirements
- ingestion handoff

The artifact must be versioned. A new module, custom object, process change, status model, access-policy change or material data-quality incident should trigger review. Queryability does not equal approval.

## Tools

Use the [Source Scope Builder](/tools/source-scope-builder) to capture include, conditional, defer, exclude and separate-product decisions with grain, authority, risk and review triggers.

Use [Suppliers](/tools/suppliers) to retain product-specific context and the [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker) when the scope contains personal data, free text, deletions or subject-rights obligations.

The tools structure evidence. They do not replace approval by Data Owner, Steward, Application Owner, Privacy or Security.

## Resources

- [Microsoft Dataverse table definitions](https://learn.microsoft.com/en-us/power-apps/developer/data-platform/entity-metadata)
- [Dataverse activity tables](https://learn.microsoft.com/en-us/power-apps/developer/data-platform/activity-entities)
- [Manage Dataverse auditing](https://learn.microsoft.com/en-us/power-platform/admin/manage-dataverse-auditing)

The configured Dynamics 365 environment remains the decisive technical evidence. Product documentation describes capabilities; it does not determine your business authority, grain or permitted use.

## Playbooks

- [Before Building the First Table](/playbooks/before-building-the-first-table) — establish the business question, grain, ownership, quality expectations and smallest complete vertical slice before source onboarding.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — define classification, permitted use, access, retention, deletion and evidence for personal and sensitive data.

Reuse these decisions. Do not recreate them independently inside connector configuration.

## Next step

Approve the process, table, relationship and field scope before designing Synapse Link, Fabric Link, connector or API extraction. The next part moves to SAP S/4 and the distinction between physical tables and supported semantic extraction sources.
