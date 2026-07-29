---
title: "Which SAP S/4 Tables to Load for Analytics — and Which to Skip"
description: "Define an SAP S/4 analytics source scope from the business document flow, target grain, organizational and currency semantics, lifecycle rules and supported extraction interface."
author: Thomas Lindackers
tags:
  - SAP S/4HANA
  - Source Scope
  - CDS Views
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/sap-s4-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 6
---

The physical S/4 table catalog is implementation evidence, not the analytical contract. The governed scope follows the business process, document flow, target grain and a supported source appropriate to the deployment and release.

The objective is not to publish a generic list of tables that every SAP S/4 landscape should load. The objective is a reviewable **Source Scope** for one decision or compatible group of decisions. It records why each object, table, relationship or field is included, conditional, deferred, excluded or separated.

## Problem

A catalog-first approach starts with the physical table, business object and extraction-source catalog, a connector inventory or an export preview. Because a structure exists and can be queried, it is selected “for later.” The result is a broad landing zone whose business meaning, grain and access boundary are resolved only after downstream teams begin joining data.

A practical inventory can contain:

- sales, delivery, billing, purchasing, goods-movement and accounting documents
- header, item, schedule-line, journal-line and snapshot structures
- business partner, material or product and organizational master data
- status, document flow, pricing and selected customizing
- company code, sales organization, plant, ledger and fiscal references
- released CDS views, APIs, extractors and other supported semantic sources
- technical logs, obsolete views, temporary records, text and attachments

These categories are not automatic include or exclude decisions. Their meaning depends on the configured application, installed features, custom extensions and the selected business process.

![Which SAP S/4 Tables to Load for Analytics — and Which to Skip](images/playbooks/sap-s4-tables-for-analytics-img1-en.png)

Typical failure modes are predictable:

- technically available structures are mistaken for approved analytical sources;
- current-state records, history, events and snapshots are mixed without a time contract;
- parent, child, header, line or association structures are joined without a declared target grain;
- display labels are treated as stable business definitions while the configured semantics differ;
- personal data, free text and attachments expand the access and retention boundary;
- custom process structures are ignored because standard names look more familiar;
- every downstream product creates its own interpretation, duplication rule and exception handling.

### Different decisions require different scopes

- Order-to-cash requires an explicit document flow from sales order through delivery and billing to the accounting consequence.
- Procure-to-pay requires purchase-order, receipt and invoice events at declared header or item grain rather than one broad purchasing table set.
- Inventory analytics requires movement, quantity, unit, plant, storage and posting-period semantics; a current stock snapshot is not interchangeable with movement history.
- Record-to-report requires journal-entry grain, ledger and currency context, posting and reversal semantics and controlled organizational scope.

The correct source scope is therefore contextual. It must be derived from the decision and validated against the actual configuration.

## Decision

Define the scope in the following order. Connector and extraction design come later.

### 1. State the decision, population and target grain

Write the requirement as a decision with a user, action, population, time horizon and grain. “Build a dashboard from SAP S/4 landscape” is too weak because it does not establish which records, relationships or historical states are required.

For every intended fact, describe one row in business terms. Declare the event that creates the fact, the dimensions required to interpret it and the date that controls reporting. A source row is not automatically an analytical fact.

### 2. Classify structures by their business role

![Classify the source structures by business role](images/playbooks/sap-s4-tables-for-analytics-img2-en.png)

### Transactional facts
- sales, delivery and billing documents
- purchase, goods movement and invoice documents
- accounting journal entries
- inventory or production events

### Master and organizational context
- business partner, customer or supplier context
- product or material
- company code, sales organization, plant or cost center
- currency, unit and fiscal calendar

### Conditional process evidence
- document flow and status
- schedule lines
- change or event history
- pricing conditions
- selected customizing needed to interpret the process

### Usually skip, replace or separate
- unsupported raw-table copies
- duplicate extraction structures
- obsolete or deprecated views
- technical logs and temporary records
- broad customizing dumps without a named use case
- attachments and unrestricted text

The groups are patterns, not universal lists. The actual application configuration, custom objects, installed modules, security and business meaning override generic examples.

### 3. Model relationships before joining

Use a relationship-centered model instead of a flat table list:

```text
Sales Order
→ Delivery
→ Billing Document
→ Accounting Entry

Context: Business Partner; Product or Material; Sales Organization; Company Code; Plant; Currency; Fiscal Period
```

For every relationship, document:

- business event and document-flow transition
- header, item or accounting-line grain
- key, reference and cardinality
- quantity, amount, currency and unit semantics
- status, cancellation and reversal behavior
- posting, document and effective date
- target analytical grain and reconciliation rule

Do not approve a join merely because both keys are available. A technically valid join can still duplicate facts, apply current context to historical events or introduce an unresolved many-to-many relationship.

![Respect relationships and event grain](images/playbooks/sap-s4-tables-for-analytics-img3-en.png)

Explicitly test these failure cases:

- order and billing amounts are counted as the same fact
- header values are repeated across items
- cancelled or reversed documents remain active in metrics
- local and group currency are mixed
- late postings are assigned to the wrong fiscal period

### 4. Separate current state, events, history and snapshots

The source scope must distinguish:

- document and posting dates
- effective and fiscal periods
- delta and deletion behavior
- archiving and late-posting rules
- cancellation, reversal and corrected restatement

A generic created or updated timestamp is not proof of complete business history. Audit data is not automatically a process event log. If point-in-time reconstruction is required, define which state must be retained, how corrections appear and whether platform snapshots are needed.

### 5. Apply field, privacy and access controls

Object or table inclusion does not authorize all fields. Create an allowlist and make separate decisions for:

- business-partner and employee-related personal data
- commercially sensitive prices and margins
- organizational and company-code boundaries
- unrestricted text and attachments
- unsupported or deprecated extraction sources

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

![Document the source-scope decision](images/playbooks/sap-s4-tables-for-analytics-img4-en.png)

### Mandatory fields

| Field | Purpose |
| business process and decision | Required decision evidence for the approved source scope |
| business object | Required decision evidence for the approved source scope |
| technical table, CDS view, API or extractor reference | Required decision evidence for the approved source scope |
| release and support status | Required decision evidence for the approved source scope |
| deployment and release dependency | Required decision evidence for the approved source scope |
| target data product and grain | Required decision evidence for the approved source scope |
| header, item and document-flow keys | Required decision evidence for the approved source scope |
| organizational scope | Required decision evidence for the approved source scope |
| currency, unit and fiscal semantics | Required decision evidence for the approved source scope |
| delta, deletion and archiving behavior | Required decision evidence for the approved source scope |
| cancellation and reversal rule | Required decision evidence for the approved source scope |
| sensitivity classification | Required decision evidence for the approved source scope |
| freshness and retention | Required decision evidence for the approved source scope |
| business, application and technical owner | Required decision evidence for the approved source scope |
| decision: include, conditional, defer, replace or exclude | Required decision evidence for the approved source scope |

### Required outputs

- approved semantic extraction scope
- field allowlist
- source and delta contract
- raw-table skip or replacement list
- reconciliation controls
- release-change review plan

The artifact must be versioned. A new module, custom object, process change, status model, access-policy change or material data-quality incident should trigger review. Queryability does not equal approval.

## Tools

Use the [Source Scope Builder](/tools/source-scope-builder) to capture include, conditional, defer, exclude and separate-product decisions with grain, authority, risk and review triggers.

Use [Suppliers](/tools/suppliers) to retain product-specific context and the [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker) when the scope contains personal data, free text, deletions or subject-rights obligations.

The tools structure evidence. They do not replace approval by Data Owner, Steward, Application Owner, Privacy or Security.

## Resources

- [SAP: Data Extraction for Analytics](https://help.sap.com/docs/SAP_S4HANA_ON-PREMISE/ee6ff9b281d8448f96b4fe6c89f2bdc8/30c3635b37484e7486b851a67effc874.html)
- [SAP: CDS Views Enabled for Data Extraction](https://help.sap.com/docs/SAP_S4HANA_ON-PREMISE/ee6ff9b281d8448f96b4fe6c89f2bdc8/b7a5b8b72d3643b7a8ecf4cd695e0791.html)
- [SAP S/4HANA Cloud data extraction documentation](https://help.sap.com/docs/SAP_S4HANA_CLOUD/c0c54048d35849128be8e872df5bea6d/30c3635b37484e7486b851a67effc874.html)

The configured SAP S/4 landscape remains the decisive technical evidence. Product documentation describes capabilities; it does not determine your business authority, grain or permitted use.

## Playbooks

- [Before Building the First Table](/playbooks/before-building-the-first-table) — establish the business question, grain, ownership, quality expectations and smallest complete vertical slice before source onboarding.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — define classification, permitted use, access, retention, deletion and evidence for personal and sensitive data.

Reuse these decisions. Do not recreate them independently inside connector configuration.

## Next step

Approve the business-document, semantic-source and lifecycle contract before selecting or configuring ODP, SLT, Datasphere, BW, APIs or other extraction technology. The next part applies the source-scope pattern to effective-dated and security-sensitive Workday data.
