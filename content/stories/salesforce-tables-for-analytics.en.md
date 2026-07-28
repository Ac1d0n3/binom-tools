---
title: "Which Salesforce Tables to Load for Analytics — and Which to Skip"
description: "Which source tables to load — and which to skip — from grain and risk."
author: Thomas Lindackers
tags:
  - Salesforce
  - Source Scope
  - Analytics Engineering
  - Data Governance
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
hero: images/playbooks/salesforce-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 1
---

A Salesforce organization can expose hundreds of standard, custom, managed-package and technical objects. That catalog is an inventory, not an analytics requirement. The load scope should contain only the objects and fields needed to answer a defined business question at a defined grain—with explicit decisions for relationships, history, personal data, free text and deletion behavior.

The objective is not to find the one correct Salesforce object list. It is to create a reviewable **Source Scope** for one analytics product and to document why each object is included, deferred or excluded.

## Problem

An object-first approach usually starts with a connector, an export catalog or a metadata scan. Because the objects are available, teams select them “for later.” This creates volume quickly, but not necessarily useful information.

The resulting problems are structural:

- no one can state which analytical question an object supports;
- several tables represent different grains and are joined without a declared relationship model;
- current record values are treated as if they represented historical states;
- notes, activities, files and descriptions introduce unrestricted free text and personal data;
- custom objects are ignored because their names are unfamiliar, although they may contain the actual business process;
- standard object names are trusted even when the configured business meaning is different;
- deleted or merged records disappear from downstream results without an explicit policy;
- every later consumer inherits a large, poorly governed source layer.

![Start with business questions, not the Salesforce object list](images/playbooks/salesforce-tables-for-analytics-img1-en.png)

### The catalog does not define the product

The same Salesforce organization can require different source scopes:

- A **pipeline by stage and owner** product may need `Opportunity`, the relevant account and owner references, governed stage semantics and selected dates. Product lines are unnecessary unless the metric is line- or product-grained.
- A **bookings by product** product usually needs the commercial relationship from `Opportunity` through `OpportunityLineItem` and `PricebookEntry` to `Product2`, plus the organization-specific definition of booked, won, cancelled and effective date.
- A **lead conversion** product may need `Lead` and the records or keys created during conversion. `Campaign` and `CampaignMember` are conditional because attribution depends on the configured process, not merely on object availability.
- An **account activity** product needs `Task`, `Event` or another activity source only when a decision, event type, actor, time window and target grain have been defined.

These examples are patterns. They are not a universal Salesforce load list.

### Three time meanings must be separated

Salesforce source data can support different time perspectives, but they must not be mixed implicitly:

1. **Current state** asks what the record looks like now.
2. **Event or field history** asks which tracked change occurred and when.
3. **Snapshot history** asks what the complete relevant state looked like at a repeated point in time.

A mutable current-state record cannot reconstruct every prior state. A history object or audit source only helps for the fields and period actually tracked. A snapshot is a separate analytical design decision and normally has to be created and retained deliberately in the data platform.

### Free text is a separate scope decision

Fields and objects containing notes, descriptions, emails, comments, activities or files must not enter the scope merely because they are related to a required business record. `Task`, `Event`, `EmailMessage`, `ContentNote`, `ContentDocument`, `ContentVersion`, `ContentDocumentLink` and legacy attachment structures can introduce large volumes, confidential content, personal data and binary payloads.

For each free-text or file source, require a specific use case, a field allowlist, access rules, retention and a documented decision on whether content, metadata or only a derived signal is needed.

## Decision

Derive the Salesforce load scope from the **business process and target grain**, not from the complete object catalog. Every included object needs an analytical purpose. Every deferred or excluded object needs a reason and a review trigger.

Use the following decision sequence.

### 1. State the analytics decision

Write the question as a decision, not as a dashboard title.

Weak:

> Build a Salesforce sales dashboard.

Stronger:

> Sales leadership decides where to intervene in the current quarter by comparing open pipeline, stage age, expected close date and accountable owner at opportunity grain.

The stronger statement identifies the user, action, period and analytical grain. It also shows that product lines, activities, notes or cases are not automatically required.

### 2. Define the process, event and target grain

Before selecting objects, state:

- the business process being observed;
- the event that creates or changes a fact;
- the row grain of each intended fact table;
- the dimensions needed to interpret that fact;
- the time meaning: current, event history or snapshot;
- the metrics and decisions the model must support.

Examples of target grain include one row per opportunity, opportunity line, lead conversion event, case status event or account-day snapshot. “One row per Salesforce record” is not a sufficient analytical grain because it merely repeats the source structure.

### 3. Trace the required business relationships

Start with the central fact and add only relationships that supply required meaning.

For a product-level sales fact, a common pattern is:

```text
Account
  └─ Opportunity
       └─ OpportunityLineItem
            └─ PricebookEntry
                 └─ Product2
```

This relationship is illustrative. Your organization may use custom products, subscriptions, quotes, orders, revenue schedules, managed-package objects or custom junction objects instead.

For every relationship, record:

- analytical purpose;
- cardinality and optionality;
- target-grain effect;
- business key and Salesforce identifier;
- current versus historical meaning;
- duplicate and orphan handling;
- PII classification;
- whether the relationship is authoritative or merely convenient.

Do not load a relationship table only because it exists. A junction object without a target grain and allocation rule can turn one business event into duplicate facts or an unresolved many-to-many model.

![Load the business relationship, not every table](images/playbooks/salesforce-tables-for-analytics-img3-en.png)

### 4. Classify each object

Use four decision states rather than a binary load-or-ignore choice.

#### Must-have

The object or export table is required to produce the selected fact, dimension, filter, control or reconciliation at the agreed grain.

Possible patterns for a product-level pipeline use case include:

- `Opportunity` as the commercial fact source;
- `OpportunityLineItem` when the target grain is a line or product;
- `Account` when account attributes are required for segmentation;
- `PricebookEntry` and `Product2` when product and price-book semantics are needed;
- `User` or another governed owner reference when accountable ownership is part of the decision;
- selected date, currency or territory attributes when they materially affect the metric.

#### Conditional

The object is loaded only when a named requirement is approved.

Typical examples include:

- `Lead` for lead-funnel or conversion analysis;
- `Campaign` and `CampaignMember` for a defined attribution model;
- `Case` for service outcomes or sales-service interaction;
- `Task` and `Event` for an explicit activity metric;
- the applicable history objects or audit sources for selected changes;
- custom objects and managed-package objects that implement the actual business process;
- contact roles or other relationship objects when the target product needs them.

#### Deferred

The object may become relevant, but the current product does not justify its cost or risk. The Source Scope records what evidence would move it into the approved scope—for example, a funded metric, a confirmed owner, a retention decision or an agreed relationship model.

#### Excluded

The object has no approved analytical purpose or violates the current scope boundary. Common examples are unused feature objects, setup and UI-support objects, duplicated export structures, unrestricted notes or attachments, and technical logs without an operational analytics case.

![Classify Salesforce objects: must-have, conditional or skip](images/playbooks/salesforce-tables-for-analytics-img2-en.png)

### 5. Apply field-level controls

Object inclusion does not authorize every field. Create a field allowlist based on:

- metric or dimension purpose;
- business definition;
- join or reconciliation need;
- personal-data classification;
- free-text and secret risk;
- required precision and format;
- freshness and change behavior;
- retention and deletion requirements.

Treat fields such as names, email addresses, phone numbers, addresses, free-form descriptions, comments and user-entered subjects as separate risk decisions. Where the use case needs only a category, count, flag or age, load or derive that controlled signal instead of copying unrestricted content.

### 6. Decide deletion, history and snapshot behavior

Salesforce records can be deleted, merged or soft-deleted. A source scope must therefore state whether downstream analytics should:

- remove the record;
- preserve it as historically valid;
- mark it as deleted;
- reconcile merges to the surviving business entity;
- retain a deletion event for audit or metric correction.

Do not assume the normal current-record query covers deleted rows. Salesforce provides query mechanisms that can include soft-deleted records while they remain available, but the analytics requirement—not the extraction default—must determine the downstream behavior.

For history, specify the exact fields or events required. Native history data is configuration- and licensing-dependent, and its scope and retention must be verified in the actual organization. When complete point-in-time reconstruction is required, define platform snapshots rather than assuming source history is sufficient.

### 7. Let custom configuration override object names

A standard object name does not guarantee standard business meaning. Organizations can rename labels, change stage processes, introduce record types, add validation logic, use custom fields, install managed packages and place critical transactions in custom objects ending in `__c`.

Review the configured process with business owners and administrators. The correct scope may include fewer standard objects and more custom objects than a generic reference architecture suggests.

## Checklist

Use this checklist before approving the Salesforce source scope.

### Business and grain

- [ ] The business question names a user, decision and time horizon.
- [ ] The business process and relevant event are explicit.
- [ ] Every fact has a declared target grain.
- [ ] Current state, event history and snapshots are separated.
- [ ] Measures have agreed business definitions and date semantics.

### Objects and relationships

- [ ] Every included object supports a named requirement.
- [ ] Standard objects are treated as examples, not mandatory defaults.
- [ ] Custom and managed-package objects have been reviewed.
- [ ] Relationship cardinality and optionality are documented.
- [ ] Junction objects have an allocation or target-grain rule.
- [ ] Duplicate and orphan behavior is defined.
- [ ] Business keys and Salesforce identifiers are recorded.

### Fields and data risk

- [ ] A field allowlist exists for every included object.
- [ ] PII and sensitive attributes are classified.
- [ ] Free text, notes, activities and files have separate approval.
- [ ] Binary content is excluded unless the use case requires it.
- [ ] Access, masking, retention and permitted use are documented.

### Time and lifecycle

- [ ] Freshness is based on the decision need.
- [ ] Soft deletion and merge behavior are defined.
- [ ] Required history fields and retention are verified in the organization.
- [ ] Snapshot frequency and retention are defined where needed.
- [ ] Each deferred or excluded object has a review trigger.

### Operations

- [ ] A business owner approves the analytical meaning.
- [ ] A technical owner validates keys, fields and extractability.
- [ ] Expected volume and change rate are understood.
- [ ] Reconciliation controls are defined.
- [ ] Open questions have owners and deadlines.

## Artifact

Create one governed **Salesforce Source Scope** artifact per analytics product or compatible group of products. It is not a raw object inventory. It is the approved contract between the business requirement and the later ingestion design.

![Document the Salesforce source scope decision](images/playbooks/salesforce-tables-for-analytics-img4-en.png)

### Mandatory fields

| Field | Purpose |
|---|---|
| Object or export table | Exact source object, view or connector table name |
| Business purpose | Decision, metric or control supported by the source |
| Target data product | Product that consumes the source |
| Target grain contribution | How the object creates, enriches or filters the grain |
| Required fields | Approved field allowlist |
| Relationship and key | Join path, key, cardinality and optionality |
| Time need | Current state, history event or snapshot |
| PII and free-text risk | Classification and handling decision |
| Freshness | Required availability based on the business decision |
| Retention | Required source-layer and analytical retention |
| Owner | Business and technical accountability |
| Decision | Include, defer or exclude |
| Rationale | Evidence for the decision |
| Review trigger | Condition that causes reassessment |

### Example decision rows

| Object | Purpose | Grain contribution | Time need | Risk | Decision | Rationale / review trigger |
|---|---|---|---|---|---|---|
| `Opportunity` | Open pipeline and stage analysis | One opportunity | Current plus selected stage history | Commercially sensitive | Include | Core fact; review when sales process or stage model changes |
| `Account` | Segment pipeline by governed account attributes | Many opportunities to one account | Current, unless historical segmentation is required | May contain PII | Include with field allowlist | Only approved segmentation fields; review when person accounts or hierarchy analysis enters scope |
| `OpportunityLineItem` | Product-level pipeline | One opportunity line | Current | Pricing sensitivity | Include only for line-grained product | Exclude from opportunity-grained product; review when product analytics is funded |
| `Product2` / `PricebookEntry` | Resolve product and price-book context | Reference for opportunity line | Current or effective-period mapping | Low to medium | Conditional | Include only when product or pricing semantics are required |
| `User` | Resolve accountable owner | Many facts to one owner | Current plus controlled organization history if required | Employee PII | Include with minimal fields | Use approved identifiers and organization attributes only |
| `Task` / `Event` | Activity-based decision | One approved activity event | Event | High free-text and PII risk | Defer | Add only with an explicit activity taxonomy and field allowlist |
| Files and notes | Document content analysis | Separate content or link grain | Versioned content | High confidentiality and volume | Exclude | Reconsider only with approved content use case, access and retention |
| Custom object | Organization-specific commercial milestone | Defined by configured process | Current, event or snapshot | To be classified | Conditional | Business owner must confirm meaning, keys and authority |

### Required outputs

The approved artifact produces:

- an extraction object list;
- a field allowlist;
- an explicit skip list;
- relationship and grain rules;
- history, deletion and snapshot requirements;
- PII and free-text controls;
- open questions with owners;
- the handoff to ingestion and incremental-load design.

**“Not loaded” is a documented decision.** It is not an accidental omission and it is not permanent. The review trigger keeps the scope adaptable without turning the first delivery into an unrestricted data copy.

### Anti-patterns to reject

| Anti-pattern | Why it fails | Required correction |
|---|---|---|
| Load every object “for later” | Cost, risk and ambiguity grow without a decision use case | Require purpose, owner and review trigger per object |
| Assume standard objects have standard meaning | Configuration, custom fields and process design change semantics | Validate the configured process and business definitions |
| Copy unrestricted free text | Sensitive and irrelevant content enters broad analytical access | Use a field allowlist and separate content approval |
| Ignore soft deletion and history | Metrics drift and prior states cannot be explained | Define deletion, merge, history and snapshot behavior |
| Load relationship tables without target grain | Joins duplicate facts or create many-to-many ambiguity | Document cardinality, keys and allocation rules first |

## Tools

- [Source Scope Builder](/tools/source-scope-builder) — document objects, fields, relationships, decisions, rationale and review triggers.
- [Supplier Landscape: Salesforce](/tools/suppliers) — place Salesforce in the wider source and platform landscape without making the supplier catalog the architecture.
- [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker) — assess personal-data scope, access, retention and data-subject request implications before loading free text or identity attributes.

## Resources

Use the configured Salesforce organization as the source of truth and validate object availability, fields, relationships and permissions against current Salesforce documentation.

- [Salesforce Object Reference: Standard Objects](https://developer.salesforce.com/docs/atlas.en-us.object_reference.meta/object_reference/sforce_api_objects_list.htm)
- [OpportunityLineItem Object Reference](https://developer.salesforce.com/docs/atlas.en-us.object_reference.meta/object_reference/sforce_api_objects_opportunitylineitem.htm)
- [PricebookEntry Object Reference](https://developer.salesforce.com/docs/atlas.en-us.object_reference.meta/object_reference/sforce_api_objects_pricebookentry.htm)
- [Salesforce Files Data Model](https://developer.salesforce.com/docs/platform/data-models/guide/salesforce-files.html)
- [QueryAll REST Resource](https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/resources_queryall.htm)
- [Field Audit Trail Implementation Guide](https://developer.salesforce.com/docs/atlas.en-us.field_history_retention.meta/field_history_retention/field_audit_trail.htm)

## Playbooks

- [Before Building the First Table](/playbooks/before-building-the-first-table) — start from the business question, metric, grain, ownership and quality expectations before selecting technology or source tables.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — define classification, permitted use, access, retention and evidence for personal data across the analytical lifecycle.

These playbooks provide the business-first and privacy foundations. This story applies them specifically to the Salesforce object and field scope; it does not replace them.

## Next step

Approve the Salesforce Source Scope before designing connector settings, incremental extraction, landing structures or change-data handling. The ingestion design must implement the approved object list, field allowlist, relationship assumptions, deletion behavior and history requirements—not silently widen them.

Then continue with **SaaS Exports: Tables to Skip** to generalize the exclusion patterns beyond Salesforce while preserving supplier-specific decisions in each Source Scope.
