---
title: DSDR Governance
description: A practical operating model for handling Data Subject Deletion Requests across systems, pipelines and data products in a traceable, timely and controlled way.
category: Data Governance
tags:
  - data-governance
  - dsdr
  - deletion-request
  - gdpr
  - privacy
  - data-deletion
  - retention
  - lineage
order: -1
series: governance-pillars
seriesPart: 5
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/dsdr-gov-hero.png
---

## Deletion is not a button — it is a governance process

A **Data Subject Deletion Request (DSDR)** looks simple at first: an individual asks for personal data to be deleted. In practice, this is rarely limited to one table or one application.

Personal data may exist simultaneously in:

- operational source systems
- CRM, ERP and HR applications
- data warehouses and lakehouses
- RAW, conform and analytics layers
- files, exports and backups
- BI datasets, reports and API outputs
- test, sandbox and development environments
- downstream third-party or business systems

Organizations usually do not fail because they do not care about deletion. They fail because they lack **visibility, clear accountability, trusted metadata and a controlled process**.

**DSDR Governance** translates privacy requirements into an operational model for discovery, evaluation, deletion, evidence and continuous improvement.

> *A deletion request is handled properly only when relevant data has been found, evaluated correctly, deleted or legitimately exempted in a controlled way, documented clearly and answered within defined timelines.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dsdr-gov-en.png"
        alt="DSDR Governance showing data subject rights, governance framework, disclosures and transparency, roles and metrics"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        DSDR Governance connects request intake, identity verification, data discovery, deletion, disclosure and evidence into an end-to-end operating process.
    </figcaption>
</figure>

## What DSDR Governance covers

DSDR Governance focuses on the controlled handling of deletion requests from data subjects. In practice, it touches multiple privacy rights and organizational obligations.

Typical elements include:

- intake and formal registration of the request
- verification of identity and legitimacy
- identifying and mapping the individual across systems
- evaluating legal, contractual or business retention obligations
- deleting, restricting, anonymizing or partially rejecting the request with justification
- considering derived data, copies and downstream usage
- documenting actions and decisions
- providing a timely response to the individual
- ensuring auditability and continuous improvement of the overall process

DSDR Governance is therefore not only a privacy topic. It sits at the intersection of **privacy, metadata, ownership, lineage, access, lifecycle and operational data management**.

## Why DSDR processes are difficult

Deletion becomes complex as soon as data is replicated or transformed across many layers.

Common problems include:

| Problem | Impact |
| --- | --- |
| **Distributed system landscape** | Relevant data exists in many applications and stores |
| **Missing reliable identity mapping** | One person cannot be found consistently across systems |
| **Lack of lineage** | Downstream objects and derivatives are overlooked |
| **Unclear ownership** | No one makes clean decisions on exceptions or priority |
| **Retention obligations** | Not everything can be deleted immediately |
| **Backups and snapshots** | Data remains technically present after productive deletion |
| **Manual case-by-case work** | Processes are slow, error-prone and hard to scale |
| **Missing evidence** | The organization cannot demonstrate correct handling |

A strong DSDR operating model must account for this reality explicitly.

## Core principles

An effective DSDR process should follow a few basic principles:

### Transparency
Requests, status, accountability and decisions must be visible and traceable.

### Lawfulness and proportionality
Not every deletion request leads to immediate full deletion. Legal, contractual or legitimate retention requirements must be assessed.

### Data minimization
The less unnecessary personal data is replicated, the easier DSDR becomes in the first place.

### Traceability
All actions must be documented, reviewable and repeatable.

### Time-bound execution
Requests must not disappear in everyday operations. Timelines, escalations and SLA-like controls are essential.

### End-to-end thinking
Not only the source system matters. Warehouses, reports, exports, models and APIs also need to be considered.

## The DSDR operating model

A practical flow can look like this:

```text
Capture request
        ↓
Verify identity
        ↓
Find and map data
        ↓
Evaluate legal and business constraints
        ↓
Delete, restrict or legitimately exempt
        ↓
Respond and preserve evidence
        ↓
Log, monitor and improve
```

### 1. Capture the request

Requests should enter through a defined channel and be recorded in a structured way.

Important information includes for example:

- identity or identifiers of the individual
- request type and intake date
- relevant systems or business relationship if known
- communication channel and response route
- timeline start and processing status
- assigned accountable team or function

A strong intake stage prevents requests from being lost in email, spreadsheets or ad hoc ticket queues.

### 2. Verify identity

Before processing begins, the organization must ensure that the request really comes from the data subject or an authorized representative.

The strength of verification depends on risk and channel. The goal is an appropriate balance between privacy and usability.

Typical measures include:

- matching known identifiers
- verification through existing customer or employee channels
- documented identity proof when required
- dedicated handling of incomplete or unclear requests

### 3. Find and map data

This is often the most critical step.

To find data across systems, the following are especially useful:

- a data inventory of relevant systems and domains
- reliable PII classifications
- unique or derivable person keys
- data catalogs with accountable roles
- lineage between source, model, report and export
- known downstream dependencies
- search and matching rules for multiple identifiers

Examples of relevant identifiers can include:

- customer number
- employee number
- email address
- contract number
- login ID
- CRM or ERP keys
- pseudonymized mapping keys

Without good mapping, DSDR quickly turns into incomplete and manual search work.

### 4. Evaluate legal and business constraints

Not all data can be deleted immediately.

The evaluation may lead to outcomes such as:

| Outcome | Meaning |
| --- | --- |
| **Delete** | The data may and should be removed |
| **Anonymize** | Personal linkage can be removed effectively |
| **Restrict / block processing** | Usage is limited although full deletion is not currently permitted |
| **Partial exemption** | Some datasets are subject to retention obligations |
| **Reject** | The request is not valid or cannot be fulfilled legally |

The evaluation should consider for example:

- statutory retention obligations
- tax or commercial record requirements
- open contracts, claims or legal disputes
- security and audit requirements
- technical differences between productive usage and backup restoration

A sound process documents not only the outcome but also the rationale.

### 5. Delete, restrict or exempt

Operational execution should be standardized as much as possible.

Affected areas may include:

- source system records
- tables, files and object storage
- staging and RAW layers
- conform and analytics models
- materializations, snapshots and exports
- BI extracts or semantic models
- downstream data products
- test and development copies

Possible actions include:

- physical deletion
- logical deletion with blocked status
- irreversible anonymization
- selective attribute removal
- deletion markers for downstream processing
- documented exception due to retention

Consistency matters: if the person is removed from the operational system but still exists in derived analytics objects, the request is often not truly complete.

### 6. Respond and preserve evidence

The organization should inform the data subject clearly and within the required timeline.

The response may include:

- confirmation of processing
- scope of deleted data
- justified exceptions or partial rejections
- notice of retention obligations
- remaining storage situations where legally or operationally relevant
- contact option for follow-up questions

At the same time, strong internal evidence must exist, such as:

- handling log
- decision path
- affected systems
- execution status per system
- accountable roles
- timestamps and timeline compliance

## Roles and responsibilities

DSDR Governance only works with clear role separation.

| Role | Typical responsibility |
| --- | --- |
| **Privacy / Data Protection** | defines guardrails, evaluates special cases and supports legal interpretation |
| **Data Owner** | decides for the domain on deletability, exceptions and priorities |
| **Data Steward** | understands data meaning, metadata, classifications and business use |
| **Data Engineering / Platform Team** | implements deletion paths, workflows, technical rules and monitoring |
| **Security / IT** | supports access, evidence, technical controls and infrastructure topics |
| **Business Function** | confirms business relevance, dependencies or retention context |
| **Service / Request Management** | manages intake, status, communication and timelines |

Role names may differ. What matters is that decision-making, operational maintenance and technical execution are not implicit or accidental.

## DSDR and metadata

A DSDR process becomes far more robust when key metadata is available.

Helpful metadata includes for example:

| Metadata field | Example |
| --- | --- |
| **PII status** | `true` |
| **DSDR relevance** | `true` |
| **Primary person key** | `customer_id` |
| **Alternative identifiers** | `email`, `contract_id` |
| **Retention class** | `customer_contract` |
| **Owner** | `Customer Domain Owner` |
| **Steward** | `CRM Data Steward` |
| **Downstream dependencies** | `analytics.customer_360` |
| **Deletion method** | `hard_delete` / `anonymize` / `restrict` |
| **Review date** | `2026-07-01` |

Metadata reduces manual case-by-case work and improves decision traceability.

## DSDR and lineage

Lineage is especially valuable in deletion processes.

Example:

```text
CRM.customer
        ↓
RAW.customer
        ↓
CONFORM.customer_master
        ↓
ANALYTICS.customer_value_segment
        ↓
BI Dataset / Dashboard / API Export
```

If only the source system is considered, downstream usage is easily missed.

Lineage helps answer questions such as:

- Which models depend on a personal source field?
- Which reports or APIs use the resulting attribute?
- Where must deletion, recalculation or restriction be triggered?
- Which derivatives could still allow conclusions about the person?

DSDR Governance without lineage is usually incomplete.

## Treat retention and exceptions properly

A common mistake is assuming that every deletion request must lead to immediate physical deletion everywhere.

In reality, organizations need clear rules for cases such as:

- statutory retention requirements
- tax-relevant documents
- invoices and contract evidence
- open legal disputes or claims
- security-relevant logs
- technical backups with restricted access situations

What matters is not only the exception itself but also **clear documentation**:

- why the exception applies
- which data is affected
- how long it applies
- what usage is still allowed or restricted
- when a new review is required

## Backups, snapshots and non-production environments

DSDR often fails at the edges of the architecture.

Especially critical are:

- database backups
- snapshots and clone functions
- file exports
- test and development environments
- personal analyst extracts
- sandbox copies

Selective immediate deletion is not always technically meaningful or possible there. In those cases, a defined handling model is needed, for example:

- no productive usage of those copies
- tightly restricted access
- maximum defined retention period
- deletion upon restore or rebuild
- documented exception with risk assessment

## Which parts should be automated?

Useful automation examples include:

- intake workflows with status and SLA control
- routing to accountable Owners and teams
- system search based on defined identifiers
- matching against DSDR-relevant assets in the catalog
- generation of work items per system or domain
- execution-status tracking
- escalation for missed deadlines
- documented decision templates for exceptions
- audit logging for handling and execution
- rebuild or refresh after deletion in downstream layers

Automation reduces time and error rate, but it does not replace business and legal judgment in edge cases.

## Which metrics help?

Useful management indicators include for example:

- number of incoming DSDR requests
- percentage of requests completed within timeline
- average handling time
- number of open or overdue requests
- percentage of automatically triggered system steps
- average number of systems touched per request
- percentage of requests with documented partial exemptions
- number of failed or incomplete deletion actions
- number of data assets without a known DSDR Owner
- percentage of DSDR-relevant assets with a retention rule
- number of requests requiring downstream rework

Metrics should show whether the process is **effective, scalable and auditable** — not just how many tickets were processed.

## A simple maturity model

| Maturity level | Typical state |
| --- | --- |
| **Ad hoc** | Deletion requests are handled manually and inconsistently |
| **Defined** | There is a formal process and named contact points |
| **Inventoried** | relevant systems, PII assets and accountabilities are known |
| **Controlled** | workflow, timelines and documented decisions are established |
| **Integrated** | catalog, lineage, retention and technical execution paths work together |
| **Monitored** | SLA, exceptions, effectiveness and risk are actively measured |
| **Embedded** | DSDR is part of normal data operations and architecture decisions |

## Common anti-patterns

DSDR initiatives are often weakened by:

- deletion requests managed only by email without tracking
- no reliable identity verification
- data inventory without DSDR relevance or accountability
- only source systems are considered, not downstream usage
- no distinction between deletion, restriction and exception
- retention rules are unknown or undocumented
- test and development data are ignored
- backups are not considered in the operating model
- business domains and Owners are not involved
- decisions are not justified or versioned
- success is measured only by timeline compliance rather than completeness and evidence

A timely but incomplete deletion is not a good process.

## Connecting to the other governance pillars

| Pillar | Connection |
| --- | --- |
| **Data Ownership & Stewardship** | Owners and Stewards decide on data meaning, exceptions and priority |
| **Metadata, Catalog & Lineage** | help find, map and trace relevant data |
| **PII & Privacy Governance** | provides classifications and protection context for personal data |
| **Data Quality Governance** | correct identifiers and metadata improve DSDR matching |
| **KPI & Metric Governance** | personal derivatives used in metrics may need recalculation |
| **Access & Security Governance** | access, logging and controlled exception handling are supported |
| **Data Lifecycle & Retention** | defines when data may, must or must not be deleted |

DSDR Governance is therefore a cross-cutting operational process that brings several governance pillars together in practice.

## The target state

A robust DSDR model can look like this:

```text
Data Subject / Request Portal
        ↓
Intake + Identity Verification
        ↓
Catalog + PII Metadata + Owners + Lineage
        ↓
Deletability and Retention Assessment
        ↓
Cross-system Execution + Tracking
        ↓
Response + Evidence + Audit
        ↓
Review + Process Improvement
```

This turns a single deletion request into a manageable and auditable governance workflow.

## The outcome

Effective DSDR Governance creates:

- **Trust** — individuals can exercise their rights in a transparent way
- **Compliance** — the organization responds in a controlled and timely manner
- **Transparency** — relevant data, systems and exceptions become visible
- **Control** — decisions and deletion paths are standardized and documented
- **Efficiency** — repeatable workflows reduce manual case-by-case work
- **Business Value** — lower risk, stronger evidence and cleaner data processes

DSDR is not a marginal exception process. In data-intensive organizations, it is a key part of effective Data Governance.

Related overview: [The 8 Pillars of Data Governance](/en/playbooks/eight-pillars).

Previous pillar: [PII & Privacy Governance](/en/playbooks/pii-privacy-governance).

Next pillar: [Data Quality Governance](/en/playbooks/data-quality-governance).
