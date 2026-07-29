---
title: "Which HubSpot Tables to Load — and Which to Skip"
description: "Define a governed HubSpot source scope from the configured funnel or service process, target grain, associations, property history and data risk instead of exporting every CRM object and property."
author: Thomas Lindackers
tags:
  - HubSpot
  - Source Scope
  - CRM Analytics
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/hubspot-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 4
---

A HubSpot portal is a configured revenue, marketing and service application, not a universal analytics model. Objects and properties become approved sources only when they support a named decision at a declared grain.

The objective is not to publish a generic list of tables that every HubSpot portal should load. The objective is a reviewable **Source Scope** for one decision or compatible group of decisions. It records why each object, table, relationship or field is included, conditional, deferred, excluded or separated.

## Problem

A catalog-first approach starts with the CRM object, property and association catalog, a connector inventory or an export preview. Because a structure exists and can be queried, it is selected “for later.” The result is a broad landing zone whose business meaning, grain and access boundary are resolved only after downstream teams begin joining data.

A practical inventory can contain:

- contacts, companies, deals and tickets
- products, line items and activated commerce objects
- owners, teams, pipelines, stages and reference properties
- activities such as calls, meetings and communications
- custom objects, custom properties and association labels
- property history, archived records, merges and deletion signals
- notes, messages, files, attachments and technical records

These categories are not automatic include or exclude decisions. Their meaning depends on the configured application, installed features, custom extensions and the selected business process.

![Which HubSpot Tables to Load — and Which to Skip](images/playbooks/hubspot-tables-for-analytics-img1-en.png)

Typical failure modes are predictable:

- technically available structures are mistaken for approved analytical sources;
- current-state records, history, events and snapshots are mixed without a time contract;
- parent, child, header, line or association structures are joined without a declared target grain;
- display labels are treated as stable business definitions while the configured semantics differ;
- personal data, free text and attachments expand the access and retention boundary;
- custom process structures are ignored because standard names look more familiar;
- every downstream product creates its own interpretation, duplication rule and exception handling.

### Different decisions require different scopes

- Pipeline intervention by deal stage and owner requires deal grain, governed stage dates and the relevant owner and company associations.
- Revenue by product requires line-item grain and a controlled relationship from deal to line item and, when needed, to a reusable product reference.
- Lifecycle conversion by source requires an agreed lifecycle model, event or property-history semantics and explicit contact-company association rules.
- Ticket resolution by team requires ticket grain, status and date semantics, category references and only the activities that the metric actually uses.

The correct source scope is therefore contextual. It must be derived from the decision and validated against the actual configuration.

## Decision

Define the scope in the following order. Connector and extraction design come later.

### 1. State the decision, population and target grain

Write the requirement as a decision with a user, action, population, time horizon and grain. “Build a dashboard from HubSpot portal” is too weak because it does not establish which records, relationships or historical states are required.

For every intended fact, describe one row in business terms. Declare the event that creates the fact, the dimensions required to interpret it and the date that controls reporting. A source row is not automatically an analytical fact.

### 2. Classify structures by their business role

![Classify the source structures by business role](images/playbooks/hubspot-tables-for-analytics-img2-en.png)

### Core for the selected use case
- companies or contacts when they define the governed customer context
- deals for pipeline or revenue decisions
- tickets for a defined service product
- owners or teams when accountability is required
- pipeline, stage, date and source properties needed by the metric
- line items when revenue is explicitly line-grained

### Conditional
- products and product references
- campaign and attribution-related objects
- calls, meetings and communication activities
- quotes, orders, invoices, subscriptions or payments when activated and required
- selected property history
- custom objects and custom association labels

### Skip unless a requirement exists
- unused feature objects
- all-property exports
- duplicate convenience representations
- unrestricted notes, messages, files and attachments
- technical records without a named analytical or control product

The groups are patterns, not universal lists. The actual application configuration, custom objects, installed modules, security and business meaning override generic examples.

### 3. Model relationships before joining

Use a relationship-centered model instead of a flat table list:

```text
Company ↔ Contact
   \       /
      Deal → Line Item → Product Reference
       |
Ticket or Activity only when required
```

For every relationship, document:

- analytical purpose of the association
- direction, cardinality and optionality
- association label and configured business meaning
- effect on the target grain
- ownership of duplicate or ambiguous relationships
- current versus historical meaning
- PII classification and permitted use

Do not approve a join merely because both keys are available. A technically valid join can still duplicate facts, apply current context to historical events or introduce an unresolved many-to-many relationship.

![Respect relationships and event grain](images/playbooks/hubspot-tables-for-analytics-img3-en.png)

Explicitly test these failure cases:

- a contact-company many-to-many relationship is flattened into one arbitrary company
- a deal is counted once per associated contact
- product definitions and deal-specific line items are treated as the same grain
- activities are inflated through repeated associations
- custom objects that implement the actual process are omitted

### 4. Separate current state, events, history and snapshots

The source scope must distinguish:

- current object state
- property history for approved properties
- explicit lifecycle or business events
- platform-created and update timestamps
- purpose-built snapshots where complete point-in-time state is required

A generic created or updated timestamp is not proof of complete business history. Audit data is not automatically a process event log. If point-in-time reconstruction is required, define which state must be retained, how corrections appear and whether platform snapshots are needed.

### 5. Apply field, privacy and access controls

Object or table inclusion does not authorize all fields. Create an allowlist and make separate decisions for:

- email addresses, phone numbers and contact identifiers
- notes, messages and communication bodies
- free-form descriptions and subjects
- files and attachments
- archived, merged and deleted record behavior

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

![Document the source-scope decision](images/playbooks/hubspot-tables-for-analytics-img4-en.png)

### Mandatory fields

| Field | Purpose |
| object or API resource | Required decision evidence for the approved source scope |
| business purpose | Required decision evidence for the approved source scope |
| target data product | Required decision evidence for the approved source scope |
| target-grain contribution | Required decision evidence for the approved source scope |
| required property allowlist | Required decision evidence for the approved source scope |
| association path, label, direction and cardinality | Required decision evidence for the approved source scope |
| current, event, property-history or snapshot need | Required decision evidence for the approved source scope |
| archived, merged and deleted-record behavior | Required decision evidence for the approved source scope |
| PII and free-text risk | Required decision evidence for the approved source scope |
| freshness and retention | Required decision evidence for the approved source scope |
| business and technical owner | Required decision evidence for the approved source scope |
| decision: include, conditional, defer or exclude | Required decision evidence for the approved source scope |
| rationale and review trigger | Required decision evidence for the approved source scope |

### Required outputs

- approved object scope
- property allowlist
- approved association map
- explicit skip list
- history and deletion contract
- open questions and ingestion handoff

The artifact must be versioned. A new module, custom object, process change, status model, access-policy change or material data-quality incident should trigger review. Queryability does not equal approval.

## Tools

Use the [Source Scope Builder](/tools/source-scope-builder) to capture include, conditional, defer, exclude and separate-product decisions with grain, authority, risk and review triggers.

Use [Suppliers](/tools/suppliers) to retain product-specific context and the [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker) when the scope contains personal data, free text, deletions or subject-rights obligations.

The tools structure evidence. They do not replace approval by Data Owner, Steward, Application Owner, Privacy or Security.

## Resources

- [HubSpot CRM associations documentation](https://developers.hubspot.com/docs/api-reference/legacy/crm/associations/v3/associate-records)
- [HubSpot CRM object API documentation](https://developers.hubspot.com/docs/api/crm/understanding-the-crm)
- [HubSpot custom objects guide](https://developers.hubspot.com/docs/api/crm/crm-custom-objects)

The configured HubSpot portal remains the decisive technical evidence. Product documentation describes capabilities; it does not determine your business authority, grain or permitted use.

## Playbooks

- [Before Building the First Table](/playbooks/before-building-the-first-table) — establish the business question, grain, ownership, quality expectations and smallest complete vertical slice before source onboarding.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — define classification, permitted use, access, retention, deletion and evidence for personal and sensitive data.

Reuse these decisions. Do not recreate them independently inside connector configuration.

## Next step

Approve the object, property and association scope before configuring connector selection, incremental extraction or downstream joins. The next series part applies the same decision discipline to the configured Dataverse landscape in Dynamics 365.
