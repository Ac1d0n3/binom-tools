---
title: "Which ServiceNow Tables to Load — and Which to Skip"
description: "Define a governed ServiceNow source scope from the operational decision, table inheritance, event grain, reference semantics, CMDB class boundaries, journals and security."
author: Thomas Lindackers
tags:
  - ServiceNow
  - ITSM Analytics
  - CMDB
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/servicenow-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 8
---

A ServiceNow table hierarchy is an operational application model. Parent and child classes, references, SLAs, journals, audit records and CMDB relations must be translated into an explicit analytical grain and scope.

The objective is not to publish a generic list of tables that every ServiceNow instance should load. The objective is a reviewable **Source Scope** for one decision or compatible group of decisions. It records why each object, table, relationship or field is included, conditional, deferred, excluded or separated.

## Problem

A catalog-first approach starts with the table hierarchy, reference and operational-event inventory, a connector inventory or an export preview. Because a structure exists and can be queried, it is selected “for later.” The result is a broad landing zone whose business meaning, grain and access boundary are resolved only after downstream teams begin joining data.

A practical inventory can contain:

- Task parent and incident, problem, change, request and other child classes
- requests, catalog and workflow records
- task SLA and other operational events
- users, groups, assignments and reference records
- CMDB CI classes, services and relationships
- audit, state history, journals and snapshots
- attachments, configuration, system and technical tables

These categories are not automatic include or exclude decisions. Their meaning depends on the configured application, installed features, custom extensions and the selected business process.

![Which ServiceNow Tables to Load — and Which to Skip](images/playbooks/servicenow-tables-for-analytics-img1-en.png)

Typical failure modes are predictable:

- technically available structures are mistaken for approved analytical sources;
- current-state records, history, events and snapshots are mixed without a time contract;
- parent, child, header, line or association structures are joined without a declared target grain;
- display labels are treated as stable business definitions while the configured semantics differ;
- personal data, free text and attachments expand the access and retention boundary;
- custom process structures are ignored because standard names look more familiar;
- every downstream product creates its own interpretation, duplication rule and exception handling.

### Different decisions require different scopes

- Incident backlog by service and assignment group requires incident or governed task-class grain, current assignment context and a separately designed historical view if point-in-time backlog is required.
- SLA performance requires task-SLA event grain and rules for multiple SLA records per task; it is not one additional column on the incident.
- Change risk requires the configured change class, state and approval semantics plus selected CI or service context, not a full CMDB dump.
- Service impact analysis requires an allowlisted set of CI classes and relationship types chosen from the service question.

The correct source scope is therefore contextual. It must be derived from the decision and validated against the actual configuration.

## Decision

Define the scope in the following order. Connector and extraction design come later.

### 1. State the decision, population and target grain

Write the requirement as a decision with a user, action, population, time horizon and grain. “Build a dashboard from ServiceNow instance” is too weak because it does not establish which records, relationships or historical states are required.

For every intended fact, describe one row in business terms. Declare the event that creates the fact, the dimensions required to interpret it and the date that controls reporting. A source row is not automatically an analytical fact.

### 2. Classify structures by their business role

![Classify the source structures by business role](images/playbooks/servicenow-tables-for-analytics-img2-en.png)

### Core process facts
- incident, problem or change when required
- request or request item
- selected task or workflow records
- task SLA for defined service-level analysis

### Required context
- users, groups and assignment
- services, offerings or selected configuration items
- state, priority, category and calendar references
- approved custom tables

### Conditional history and controls
- state transitions
- audit evidence
- reassignment or approval events
- snapshots for backlog or point-in-time analysis

### Usually skip or separate
- duplicate parent and child representations
- unrestricted journals and work notes
- attachments and binary content
- broad CMDB or system-table dumps
- technical logs without an operational product

The groups are patterns, not universal lists. The actual application configuration, custom objects, installed modules, security and business meaning override generic examples.

### 3. Model relationships before joining

Use a relationship-centered model instead of a flat table list:

```text
Task
├─ Incident
├─ Problem
├─ Change
└─ Request or Other Child Class

Conditional facts: Task SLA; Assignment Group and User; CI or Business Service; State Transition or Snapshot
```

For every relationship, document:

- class and sys_class_name meaning
- parent versus child representation
- business event and target grain
- reference key and display value
- one-to-many effect
- current versus historical meaning
- domain, role and PII classification

Do not approve a join merely because both keys are available. A technically valid join can still duplicate facts, apply current context to historical events or introduce an unresolved many-to-many relationship.

![Respect relationships and event grain](images/playbooks/servicenow-tables-for-analytics-img3-en.png)

Explicitly test these failure cases:

- parent and child rows are counted twice
- several SLA records are treated as one task
- current CI or assignment is applied to historical events
- journal updates are counted as task events
- CMDB relationship traversal explodes the fact count

### 4. Separate current state, events, history and snapshots

The source scope must distinguish:

- current task state
- state-transition history
- SLA events and breach timing
- validated audit evidence
- purpose-built snapshots for backlog or point-in-time state

A generic created or updated timestamp is not proof of complete business history. Audit data is not automatically a process event log. If point-in-time reconstruction is required, define which state must be retained, how corrections appear and whether platform snapshots are needed.

### 5. Apply field, privacy and access controls

Object or table inclusion does not authorize all fields. Create an allowlist and make separate decisions for:

- descriptions, comments, work notes and journals
- attachments and binary content
- user and group data
- domain and role access boundaries
- retention, archive and deletion behavior

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

![Document the source-scope decision](images/playbooks/servicenow-tables-for-analytics-img4-en.png)

### Mandatory fields

| Field | Purpose |
| table and class name | Required decision evidence for the approved source scope |
| parent or extension relationship | Required decision evidence for the approved source scope |
| application or plugin | Required decision evidence for the approved source scope |
| business purpose and target data product | Required decision evidence for the approved source scope |
| task, event, SLA, CI or snapshot grain | Required decision evidence for the approved source scope |
| required columns and references | Required decision evidence for the approved source scope |
| display-value and choice mapping | Required decision evidence for the approved source scope |
| current, transition, audit or snapshot need | Required decision evidence for the approved source scope |
| journal, attachment and free-text decision | Required decision evidence for the approved source scope |
| domain, role and access boundary | Required decision evidence for the approved source scope |
| deletion, archive and retention behavior | Required decision evidence for the approved source scope |
| freshness and volume expectation | Required decision evidence for the approved source scope |
| business and platform owner | Required decision evidence for the approved source scope |
| decision and duplication rule | Required decision evidence for the approved source scope |
| rationale and review trigger | Required decision evidence for the approved source scope |

### Required outputs

- approved table and class scope
- inheritance and reference map
- parent-child duplication controls
- field allowlist and text boundary
- CMDB class allowlist
- history and SLA event contract
- ingestion handoff

The artifact must be versioned. A new module, custom object, process change, status model, access-policy change or material data-quality incident should trigger review. Queryability does not equal approval.

## Tools

Use the [Source Scope Builder](/tools/source-scope-builder) to capture include, conditional, defer, exclude and separate-product decisions with grain, authority, risk and review triggers.

Use [Suppliers](/tools/suppliers) to retain product-specific context and the [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker) when the scope contains personal data, free text, deletions or subject-rights obligations.

The tools structure evidence. They do not replace approval by Data Owner, Steward, Application Owner, Privacy or Security.

## Resources

- [ServiceNow table extension and classes](https://www.servicenow.com/docs/r/platform-administration/table-administration-and-data-management/table-extension-and-classes.html)
- [ServiceNow tables and data models](https://www.servicenow.com/docs/r/application-development/tables-and-data-models.html)
- [ServiceNow CMDB class definitions](https://www.servicenow.com/docs/r/servicenow-platform/configuration-management-database-cmdb/t_ViewTableDefinitions.html)

The configured ServiceNow instance remains the decisive technical evidence. Product documentation describes capabilities; it does not determine your business authority, grain or permitted use.

## Playbooks

- [Before Building the First Table](/playbooks/before-building-the-first-table) — establish the business question, grain, ownership, quality expectations and smallest complete vertical slice before source onboarding.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — define classification, permitted use, access, retention, deletion and evidence for personal and sensitive data.

Reuse these decisions. Do not recreate them independently inside connector configuration.

## Next step

Approve the class, inheritance, reference, history, journal and CMDB boundaries before configuring the Table API, IntegrationHub, a connector or Performance Analytics. The final part of the series resolves authority when several sources contain the same entity or attribute.
