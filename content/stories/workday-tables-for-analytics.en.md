---
title: "Which Workday Objects to Load — and Which to Skip"
description: "Define a governed Workday source scope from a named workforce decision, effective-dated relationships, worker and event grain, security domains and strict field minimization."
author: Thomas Lindackers
tags:
  - Workday
  - HR Analytics
  - Source Scope
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/workday-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Source Load Decisions
seriesPart: 7
---

Workday must be scoped through a named workforce, finance or control decision and the relevant effective-dated business relationships. A custom report or integration output is an implementation, not the business definition.

The objective is not to publish a generic list of tables that every Workday tenant should load. The objective is a reviewable **Source Scope** for one decision or compatible group of decisions. It records why each object, table, relationship or field is included, conditional, deferred, excluded or separated.

## Problem

A catalog-first approach starts with the business-object, report and integration-output inventory, a connector inventory or an export preview. Because a structure exists and can be queried, it is selected “for later.” The result is a broad landing zone whose business meaning, grain and access boundary are resolved only after downstream teams begin joining data.

A practical inventory can contain:

- worker and contingent-worker context
- position, job profile, manager and supervisory organization
- company, cost center, location and organizational hierarchy
- staffing, recruiting, compensation, time and absence events
- custom reports, calculated fields and RaaS outputs
- current, trended, effective-dated and snapshot structures
- payroll, benefits, health, documents, notes and other restricted content

These categories are not automatic include or exclude decisions. Their meaning depends on the configured application, installed features, custom extensions and the selected business process.

![Which Workday Objects to Load — and Which to Skip](images/playbooks/workday-tables-for-analytics-img1-en.png)

Typical failure modes are predictable:

- technically available structures are mistaken for approved analytical sources;
- current-state records, history, events and snapshots are mixed without a time contract;
- parent, child, header, line or association structures are joined without a declared target grain;
- display labels are treated as stable business definitions while the configured semantics differ;
- personal data, free text and attachments expand the access and retention boundary;
- custom process structures are ignored because standard names look more familiar;
- every downstream product creates its own interpretation, duplication rule and exception handling.

### Different decisions require different scopes

- Active workforce by organization requires worker-position-organization relationships evaluated for an explicit effective date.
- Open positions and hiring demand require position and recruiting scope, not the complete worker profile.
- Compensation analysis requires separate purpose approval, population, period, security and aggregation controls.
- Absence or time analysis requires event or period grain and a clear distinction between approved outcomes, planned events and sensitive detail.

The correct source scope is therefore contextual. It must be derived from the decision and validated against the actual configuration.

## Decision

Define the scope in the following order. Connector and extraction design come later.

### 1. State the decision, population and target grain

Write the requirement as a decision with a user, action, population, time horizon and grain. “Build a dashboard from Workday tenant” is too weak because it does not establish which records, relationships or historical states are required.

For every intended fact, describe one row in business terms. Declare the event that creates the fact, the dimensions required to interpret it and the date that controls reporting. A source row is not automatically an analytical fact.

### 2. Classify structures by their business role

![Classify the source structures by business role](images/playbooks/workday-tables-for-analytics-img2-en.png)

### Core workforce context
- worker or contingent worker
- position and job profile
- supervisory organization
- company, cost center and location
- manager and organizational hierarchy

### Conditional facts and events
- staffing events
- recruiting and candidate data
- compensation and payroll
- time, absence and leave
- learning, performance or talent data

### Controlled derived sources
- custom reports
- calculated fields
- integration outputs
- approved snapshots

### Usually exclude or restrict
- unrestricted worker-profile fields
- health, benefits or document content without a named purpose
- notes, attachments and case text
- duplicate reports with conflicting calculations
- technical integration metadata

The groups are patterns, not universal lists. The actual application configuration, custom objects, installed modules, security and business meaning override generic examples.

### 3. Model relationships before joining

Use a relationship-centered model instead of a flat table list:

```text
Worker
→ Position
→ Job Profile
→ Supervisory Organization
→ Company / Cost Center / Location

Events: Hire; Transfer; Organization Change; Compensation Change; Leave; Return; Termination; Rescind
```

For every relationship, document:

- effective start and end
- current, historical or future-dated state
- primary versus additional job
- worker type and contingent-worker treatment
- correction and rescind behavior
- business key and reference ID
- security classification and permitted population

Do not approve a join merely because both keys are available. A technically valid join can still duplicate facts, apply current context to historical events or introduce an unresolved many-to-many relationship.

![Respect relationships and event grain](images/playbooks/workday-tables-for-analytics-img3-en.png)

Explicitly test these failure cases:

- current organization is applied to historical facts
- multiple positions are collapsed into one current record
- future-dated changes become visible too early
- rescinded events remain valid
- manager hierarchy is reconstructed without effective dates

### 4. Separate current state, events, history and snapshots

The source scope must distinguish:

- effective date and entry date
- current, historical and future-dated state
- corrections and rescinds
- event history versus effective-dated snapshot
- period-grained payroll, compensation, time or absence facts

A generic created or updated timestamp is not proof of complete business history. Audit data is not automatically a process event log. If point-in-time reconstruction is required, define which state must be retained, how corrections appear and whether platform snapshots are needed.

### 5. Apply field, privacy and access controls

Object or table inclusion does not authorize all fields. Create an allowlist and make separate decisions for:

- direct identifiers and worker-profile data
- compensation, payroll and financial detail
- health, benefits and leave information
- documents, notes, attachments and case text
- security-domain and population restrictions

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

![Document the source-scope decision](images/playbooks/workday-tables-for-analytics-img4-en.png)

### Mandatory fields

| Field | Purpose |
| workforce decision and population | Required decision evidence for the approved source scope |
| Workday business object or report | Required decision evidence for the approved source scope |
| source interface or integration | Required decision evidence for the approved source scope |
| target data product | Required decision evidence for the approved source scope |
| worker, position, event or period grain | Required decision evidence for the approved source scope |
| effective-date and correction semantics | Required decision evidence for the approved source scope |
| required fields and calculated fields | Required decision evidence for the approved source scope |
| organization and hierarchy relationship | Required decision evidence for the approved source scope |
| worker type and multiple-job handling | Required decision evidence for the approved source scope |
| security domain and permitted use | Required decision evidence for the approved source scope |
| PII and sensitivity classification | Required decision evidence for the approved source scope |
| retention and deletion requirement | Required decision evidence for the approved source scope |
| freshness | Required decision evidence for the approved source scope |
| business, data and integration owner | Required decision evidence for the approved source scope |
| decision: include, conditional, defer, exclude or restricted product | Required decision evidence for the approved source scope |

### Required outputs

- approved object and field scope
- security-domain mapping
- effective-date contract
- restricted-data boundary
- duplicate-report retirement list
- reconciliation and access-review controls

The artifact must be versioned. A new module, custom object, process change, status model, access-policy change or material data-quality incident should trigger review. Queryability does not equal approval.

## Tools

Use the [Source Scope Builder](/tools/source-scope-builder) to capture include, conditional, defer, exclude and separate-product decisions with grain, authority, risk and review triggers.

Use [Suppliers](/tools/suppliers) to retain product-specific context and the [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker) when the scope contains personal data, free text, deletions or subject-rights obligations.

The tools structure evidence. They do not replace approval by Data Owner, Steward, Application Owner, Privacy or Security.

## Resources

- [Workday: Building Custom Reports](https://doc.workday.com/workday-education/en-us/course-manuals/creating-and-securing-integrations/building-custom-reports.html)
- [Workday: Configurable Security Overview](https://doc.workday.com/workday-education/en-us/course-manuals/student-for-administrators/configurable-security-overview.html)
- [Workday: Reporting with HCM Data](https://doc.workday.com/workday-education/en-us/course-manuals/advanced-workday-reporting-for-hcm/reporting-with-hcm-data.html)

The configured Workday tenant remains the decisive technical evidence. Product documentation describes capabilities; it does not determine your business authority, grain or permitted use.

## Playbooks

- [Before Building the First Table](/playbooks/before-building-the-first-table) — establish the business question, grain, ownership, quality expectations and smallest complete vertical slice before source onboarding.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — define classification, permitted use, access, retention, deletion and evidence for personal and sensitive data.

Reuse these decisions. Do not recreate them independently inside connector configuration.

## Next step

Approve the population, effective-date, object, field and security-domain scope before building a RaaS report, EIB, Studio, REST or SOAP extraction. The next part applies the same principles to ServiceNow inheritance, SLA events, journals and CMDB class boundaries.
