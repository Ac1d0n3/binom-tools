---
title: Data Lifecycle & Retention
description: A practical operating model for governing data from creation to secure deletion — with clear retention rules, ownership, cost control and verifiable controls.
category: Data Governance
tags:
  - data-governance
  - data-lifecycle
  - retention
  - archiving
  - data-deletion
  - data-classification
  - storage-optimization
  - compliance
  - records-management
order: -1
publishedAt: 2026-06-09
series: governance-pillars
seriesPart: 9
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/life-gov-hero.png
---

## Data needs a governed lifecycle

Data is often created or ingested deliberately — but archived, reviewed or deleted far less consistently.

This creates common problems:

- operational systems retain historical data with no current use
- data is replicated multiple times and kept for different periods
- analytics platforms grow without a clear archive strategy
- retention periods exist only in policies, not in technical metadata
- old data products remain active although nobody needs them
- backups, exports and local copies follow different rules from production systems
- legal retention and privacy requirements are managed separately
- deletions happen without considering downstream systems
- data is retained indefinitely “just in case”
- storage, compute and operating costs continue to grow
- quality and interpretability decline as history expands
- nobody owns the decision about when data has lost its value

**Data Lifecycle & Retention Governance** connects business need, legal obligations, data classification, technical execution, cost management and controlled deletion into an end-to-end operating model.

> *Good lifecycle governance does not keep as much data as possible for as long as possible. It keeps the right data for as long as necessary — and removes it under control when its purpose ends.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/life-gov-en.png"
        alt="Data lifecycle from creation and active use through inactivity, archive and review to secure deletion"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Data Lifecycle & Retention Governance controls data from creation through use and archive to controlled deletion.
    </figcaption>
</figure>

## The data lifecycle

A practical lifecycle can distinguish six stages:

```text
1. Create / Receive
        ↓
2. Use Actively
        ↓
3. Manage as Inactive
        ↓
4. Archive
        ↓
5. Review and Assess
        ↓
6. Delete or Anonymize Permanently
```

These stages are not merely technical storage tiers.

Each stage changes:

- permitted use
- access
- protection needs
- availability
- performance
- cost
- retention
- monitoring
- accountability

## 1. Create or receive

Data is created or received through:

- operational applications
- customer and employee processes
- sensors and machines
- files and forms
- APIs
- data providers
- partners
- external platforms
- manual entry
- analytical derivations

Important questions should be answered at creation:

- Why is the data needed?
- Which purpose applies?
- Who is the Data Owner?
- Which classification applies?
- Does the data contain PII?
- Which quality requirements exist?
- Which retention class applies?
- Which downstream use is permitted?
- Must the record remain fully reproducible?
- Which archive or deletion mechanism will be required?

Lifecycle Governance therefore begins at design time, not in the archive.

## 2. Active use

Active data supports ongoing processes, analytics and decisions.

Typical characteristics:

- frequent use
- high availability requirements
- low access latency
- regular updates
- operational or analytical relevance
- broad integration into data products and reports

Active data often requires:

- high-performance storage
- current quality controls
- clear access policies
- monitoring and freshness checks
- known Owners
- documented lineage
- defined recovery objectives

Active does not automatically mean that the data should be kept forever.

## 3. Inactive data

Data becomes inactive when it is no longer used regularly but may still have business, legal or historical value.

Examples:

- closed customer cases
- completed projects
- old operational transactions
- rarely used detailed history
- superseded data products
- former employee data
- outdated reference states

Inactive data should not simply remain hidden in production systems.

Review:

- Is access still required?
- Is the current storage tier cost-effective?
- Can the data product be downgraded?
- Must quality still be actively monitored?
- Are all downstream uses known?
- Do retention obligations remain?
- Should the data be archived?

## 4. Archive

Archiving reduces operational load while preserving necessary information.

An archive is more than cheap storage.

It needs:

- clear scope rules
- traceable transfer
- integrity validation
- access control
- encryption
- documented retention
- discoverability
- governed restore procedures
- auditability
- defined deletion at the end of the period

Typical archive strategies include:

| Strategy | Typical use |
| --- | --- |
| **Cold Storage** | rarely accessed data with low retrieval frequency |
| **Historical Partition** | time-based separation within the same platform |
| **Read-only Archive** | immutable historical information |
| **Object Storage** | cost-efficient long-term retention |
| **Records Archive** | evidence- or record-keeping content |
| **Snapshot Archive** | reproducible historical states |
| **Aggregated Archive** | remove detail while retaining historical metrics |

The archive strategy should match retrieval and usage needs.

## 5. Review and assess

Retention must not be defined once and then forgotten.

Periodic reviews ask:

- Does the original purpose still exist?
- Is the asset still used?
- Does a legal retention requirement still apply?
- Is retention economically justified?
- Is the data redundant?
- Can granularity be reduced?
- Can data be anonymized?
- Is there a Legal Hold?
- Is the classification still correct?
- Are Owners still current?
- Can the asset be retired?

A review can result in:

```text
Continue Active Use
        or
Move to Inactive
        or
Archive
        or
Extend Retention
        or
Anonymize
        or
Delete
```

## 6. Delete or anonymize

Deletion is the controlled endpoint of the lifecycle.

It should be:

- approved
- technically defined
- traceable
- complete
- repeatable
- validated
- documented

Possible actions include:

| Action | Meaning |
| --- | --- |
| **Physical deletion** | remove the record technically |
| **Secure deletion** | remove data irreversibly according to the storage technology |
| **Anonymization** | permanently remove the relationship to an identifiable person |
| **Aggregation** | delete detail while retaining summarized history |
| **Pseudonymization** | replace identifiers while personal-data status may remain |
| **Logical deletion** | mark a record as deleted while retaining it technically |
| **Deferred deletion** | execute deletion after a defined period |
| **Tombstone / Suppression** | prevent deleted data from being reintroduced |

Not every action serves the same purpose.

Logical deletion is not automatically privacy deletion. Pseudonymization is not automatically anonymization.

## Retention is a business decision

Retention rules should not be defined by infrastructure or database administration alone.

Typical roles include:

| Role | Responsibility |
| --- | --- |
| **Data Owner** | assesses business purpose, value and required retention |
| **Data Steward** | maintains retention metadata, classification and reviews |
| **Legal / Compliance** | defines legal and regulatory guardrails |
| **Privacy** | assesses minimization, deletion and privacy requirements |
| **Records Management** | governs records and evidence obligations |
| **Platform / Infrastructure** | implements storage tiers, archiving and deletion |
| **Data Engineering** | implements lifecycle rules in pipelines and models |
| **Security** | protects archives, backups and deletion processes |
| **Data Product Owner** | governs retirement, migration and consumer communication |

## Retention classes

A standardized model can use retention classes.

| Class | Example | Typical rule |
| --- | --- | --- |
| **Operational Short** | technical staging data | 7–30 days |
| **Operational Standard** | active business transactions | process-dependent |
| **Analytical Active** | active data products | while there is a valid business need |
| **Historical** | completed historical data | multi-year retention |
| **Regulated** | legally relevant documents | fixed retention period |
| **PII Limited** | personal data without permanent purpose | as short as possible |
| **Legal Hold** | legally preserved data | until formal release |
| **Archive** | long-term evidence | governed archive period |
| **Ephemeral** | temporary calculation data | hours or days |

The exact period depends on jurisdiction, data type, contract and business purpose.

A retention class should therefore contain more than a number.

## Retention policy as metadata

Example:

```yaml
retention_policy:
  id: customer-contract-standard
  scope:
    domain: customer
    asset_types:
      - contract
      - invoice
  trigger:
    event: contract_closed
  active_period:
    duration: P1Y
  archive_period:
    duration: P9Y
  final_action: secure_delete
  legal_hold_supported: true
  owner: legal-records
  steward: customer-data-steward
  review_cycle: annual
```

Important elements include:

- scope
- start or trigger event
- active period
- archive period
- final action
- exceptions
- Legal Hold
- Owner
- review cycle
- technical implementation

## The trigger is often more important than the duration

A rule such as “retain for 10 years” is incomplete.

The critical question is:

***From which event does the period begin?***

Possible triggers include:

- creation date
- last business event
- contract end
- account closure
- case closure
- invoice date
- project completion
- last login
- withdrawal of consent
- employee termination
- completion of legal proceedings

Example:

```text
Retention:
10 years

Trigger:
End of the calendar year
in which the contract was closed
```

Without the correct trigger, the same duration may be calculated differently.

## Lifecycle metadata

A Data Catalog can include:

| Metadata field | Example |
| --- | --- |
| **Lifecycle Status** | Active |
| **Retention Class** | customer-contract-standard |
| **Retention Trigger** | contract_closed |
| **Retention Start** | 2026-07-01 |
| **Retention End** | 2036-12-31 |
| **Final Action** | secure_delete |
| **Archive Tier** | cold-storage |
| **Data Owner** | Customer Domain Owner |
| **Data Steward** | Contract Data Steward |
| **Legal Hold** | false |
| **Review Date** | 2027-07-01 |
| **Last Accessed** | 2026-07-10 |
| **Deletion Method** | platform_workflow_v2 |
| **Validation Rule** | no records after execution |

This turns lifecycle into a measurable property of a data asset.

## Governing data across platform layers

A modern data stack contains multiple copies.

Example:

```text
Source
  ↓
Landing
  ↓
RAW
  ↓
CONFORM
  ↓
ANALYTICS
  ↓
Semantic Layer
  ↓
BI Extract
  ↓
Excel Export
```

Retention must answer:

- Does the same period apply in every layer?
- Can staging data be deleted much earlier?
- Must analytical detail be kept as long as source evidence?
- Can aggregated history remain longer?
- How are exports and extracts handled?
- How is deleted data prevented from reappearing after reload?

A common failure pattern is:

```text
Source deleted
        ↓
Warehouse copy remains
        ↓
BI extract remains
        ↓
Excel export remains indefinitely
```

Lifecycle Governance must consider the full data chain.

## Different rules by layer

Example:

| Layer | Retention |
| --- | --- |
| **Landing** | 7 days |
| **RAW** | 90 days |
| **Conform** | 3 years |
| **Analytics Detail** | 2 years |
| **Analytics Aggregate** | 10 years |
| **BI Extract** | 30 days |
| **Local Export** | 14 days |
| **Archive** | according to business and legal policy |

This differentiation reduces cost and risk.

## Lifecycle and dbt

dbt can support lifecycle rules technically, for example through:

- time-based models
- incremental cleanup
- retention metadata in `meta`
- automated archive models
- tests for expired data
- generation of deletion or archive SQL
- lifecycle-status documentation
- propagation of retention classes

Example:

```yaml
models:
  - name: customer_events
    meta:
      lifecycle:
        status: active
        retention_class: customer-events-3y
        final_action: anonymize
        owner: customer-domain
```

A macro can use this metadata to prepare technical actions.

The important distinction remains:

dbt is an implementation tool. Business retention decisions require governance.

## Backups and restore

Backups often follow their own lifecycle rules.

Define:

- backup frequency
- retention period
- encryption
- access
- immutability
- restore
- deletion
- handling of previously deleted data after restore

A central control point is:

```text
Restore
        ↓
Reapply valid deletion and suppression rules
        ↓
Validate restored environment
        ↓
Release for use
```

Otherwise, deleted data can re-enter production systems.

## Legal Hold

A Legal Hold temporarily suspends normal deletion.

A controlled process needs:

- clear scope
- accountable function
- start date
- rationale
- affected assets
- technical hold
- review date
- approval for release
- documented closure

A Legal Hold must not mean indefinite retention without review.

## Data value and cost

Lifecycle Governance is not only about compliance.

It also supports cost control.

Costs arise from:

- storage
- replication
- backups
- compute
- data transfer
- monitoring
- cataloging
- security
- support
- migration
- recovery

A possible evaluation model:

```text
Business Value
        +
Legal Need
        +
Operational Need
        +
Analytical Need
        -
Storage Cost
        -
Security Risk
        -
Maintenance Cost
        =
Retention Decision
```

Not every historical record has the same value.

## Retiring data products

Data products also require a lifecycle.

Possible statuses:

| Status | Meaning |
| --- | --- |
| **Draft** | under development |
| **Active** | productive and supported |
| **Limited** | limited future development |
| **Deprecated** | replacement announced |
| **Archived** | no longer active, historically available |
| **Retired** | fully decommissioned |

Before retirement, review:

- consumers
- downstream dependencies
- reports
- APIs
- scheduled jobs
- documentation
- Owner
- replacement product
- migration period
- archive needs
- deletion rules

## Retention and Data Quality

Lifecycle affects data quality.

Examples:

- deleted history changes trend analysis
- archive reduces immediate availability
- partial history creates inconsistent reporting
- missing trigger dates prevent correct retention calculation
- old master data loses business relevance
- historical data still requires interpretability

Quality rules can validate:

- retention end date present
- valid retention class
- Owner assigned
- expired data identified
- no active use of archived assets
- no records after final deletion date
- complete Legal Hold documentation

## Monitoring

Lifecycle monitoring can track:

- data volume by lifecycle stage
- assets without a retention class
- assets without an Owner
- expired retention periods
- open deletion tasks
- failed archive processes
- failed deletions
- Legal Holds without review
- unused active data products
- rarely used hot-storage data
- archives without restore testing
- uncontrolled exports
- local copies without expiry
- data with conflicting retention rules

## Measuring Data Lifecycle & Retention

Useful indicators include:

- percentage of critical assets with a retention class
- percentage of assets with Owner and Steward
- percentage of retention policies with a documented trigger
- number of expired records
- time from expiry to technical deletion
- percentage of automated archive processes
- percentage of automated deletion processes
- number of failed lifecycle jobs
- data volume by lifecycle stage
- storage cost by data class
- percentage of rarely used data in expensive storage
- number of active Legal Holds
- number of overdue Legal Hold reviews
- percentage of successful restore tests
- number of restored records requiring renewed deletion
- number of outdated data products
- time from deprecation to retirement
- percentage of expired PII deleted or anonymized
- number of exceptions without expiry
- number of assets with conflicting retention

## A simple maturity model

| Maturity level | Typical state |
| --- | --- |
| **Unlimited** | data is retained indefinitely without clear rules |
| **Documented** | initial retention policies exist |
| **Classified** | assets have retention classes and Owners |
| **Technically implemented** | archive and deletion are defined per platform |
| **Automated** | lifecycle actions are driven by metadata and workflows |
| **Integrated** | rules cover sources, platforms, BI and exports |
| **Monitored** | deadlines, cost, exceptions and deletion quality are measured |
| **Value-driven** | data value, risk and cost continuously govern the lifecycle |

## Common anti-patterns

- all data is retained indefinitely
- retention exists only in a PDF policy
- periods have no defined trigger
- every platform uses separate rules
- archives have no Owner
- “archived” only means copied into another bucket
- deletions are not validated
- sources are deleted while analytics copies remain
- exports and Excel files are ignored
- backups can reintroduce deleted data
- Legal Holds have no end or review date
- old data products remain active
- cost is evaluated technically but not from a business perspective
- storage is optimized while business context is lost
- PII is kept longer than necessary
- data is deleted although important history is still required
- retention classes are too generic
- exceptions have no Owner
- success is measured only by deleted data volume

Lifecycle Governance means neither “keep everything” nor “delete everything”.

It creates controlled decisions about value, risk, use and time.

## Connecting to the other governance pillars

| Pillar | Connection |
| --- | --- |
| **Data Ownership & Stewardship** | Owners and Stewards decide value, purpose, periods and exceptions |
| **Metadata, Catalog & Lineage** | Lifecycle status, triggers, periods and dependencies become visible |
| **PII & Privacy Governance** | Minimization and Privacy by Design limit unnecessary retention |
| **DSDR Governance** | Deletion requests use retention policies and technical deletion mechanisms |
| **Data Quality Governance** | Lifecycle status and retention metadata require quality controls |
| **KPI & Metric Governance** | Historical comparability depends on retention and reproducibility |
| **Access & Security Governance** | Access to active, archived and held data is governed differently |

Data Lifecycle & Retention provides the time dimension for all other governance pillars.

## Practical target state

```text
Data Created
        ↓
Classified + Owned
        ↓
Active Use
        ↓
Usage and Value Monitoring
        ↓
Inactive / Archive
        ↓
Retention Review
        ↓
Delete / Anonymize / Retain with Justification
        ↓
Validation + Audit Evidence
```

## The outcome

Effective Data Lifecycle & Retention Governance creates:

- **Compliance** — retention and deletion follow traceable rules
- **Privacy** — personal data is not kept longer than necessary
- **Transparency** — lifecycle status, periods and accountability are visible
- **Cost Control** — storage and operations are optimized by use and value
- **Risk Reduction** — unnecessary datasets and uncontrolled copies are reduced
- **Traceability** — archive, exceptions and deletion are auditable
- **Efficiency** — standardized metadata and automation reduce manual work
- **Business Value** — relevant history remains usable without unlimited platform growth

The decisive question is not:

*“How long can we technically store this data?”*

It is:

***“How long do we need this data for business and legal reasons — and what controlled action follows after that?”***

Related overview: [The 8 Pillars of Data Governance](/en/playbooks/eight-pillars).

Previous pillar: [Access & Security Governance](/en/playbooks/access-security-governance).
