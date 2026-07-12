---
title: The 8 Pillars of Data Governance
description: A practical governance model for ownership, metadata, privacy, DSDR, data quality, metrics, access and the data lifecycle.
category: Data Governance
tags:
  - data-governance
  - data-ownership
  - metadata
  - pii
  - dsdr
  - data-quality
  - kpi-governance
  - data-lifecycle
order: -1
publishedAt: 2026-06-01
series: governance-pillars
seriesPart: 1
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/eight-pillar-hero.png
---

## Governance is not a single tool

Data Governance is often reduced to a data catalog, a collection of policies or an approval process. In practice, none of these building blocks is sufficient on its own. Effective governance emerges when **accountability, knowledge, protection, quality and usage** work together.

The eight pillars in this playbook are therefore not a rigid framework or a product architecture. They provide a **practical orientation model** for structuring governance initiatives, identifying gaps and prioritizing operational controls.

> *Data Governance is not only about control. It creates the foundation for making data discoverable, understandable, protected, trustworthy and usable for decision-making.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/eight-pillar-en.png"
        alt="The eight pillars of Data Governance: ownership, metadata, PII, DSDR, data quality, KPI, access, and data lifecycle"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eight connected pillars create compliance, trust, efficiency, transparency and measurable business value.
    </figcaption>
</figure>

## The eight pillars at a glance

| Pillar | Core question | Typical outcome |
| --- | --- | --- |
| **1. Data Ownership & Stewardship** | Who is accountable from a business and technical perspective? | Clear roles, ownership and escalation paths |
| **2. Metadata, Catalog & Lineage** | What data exists, what does it mean and where does it come from? | Discoverability, context and end-to-end transparency |
| **3. PII & Privacy Governance** | Which data is personal and how must it be protected? | Classification, protection rules and controlled processing |
| **4. DSDR Governance** | How are deletion requests executed securely, completely and with evidence? | Controlled deletion or anonymization process |
| **5. Data Quality Governance** | Is the data accurate, complete, timely and consistent? | Measurable quality standards and continuous improvement |
| **6. KPI & Metric Governance** | How do we ensure that metrics are understood consistently everywhere? | Standard definitions and trusted KPIs |
| **7. Access & Security Governance** | Who may view or change which data? | Role-based access, least privilege and auditability |
| **8. Data Lifecycle & Retention** | How long is data stored, archived or deleted? | Automated retention and deletion rules |

## 1. Data Ownership & Stewardship

Governance starts with accountability. Without named owners and stewards, policies remain optional, quality issues remain unresolved and decisions stall between business teams, data engineering and platform operations.

A robust ownership model defines at least:

- **Data Owner** — business accountability, prioritization and approval
- **Data Steward** — definitions, quality, classification and operational maintenance
- **Technical Owner** — implementation, operations and integrations
- **Clear escalation paths** — who decides when risks, conflicts or quality issues arise

Ownership should be connected directly to data products, domains, source systems or critical metrics — not only documented in a separate governance spreadsheet.

## 2. Metadata, Catalog & Lineage

Metadata makes data understandable. A catalog improves discoverability, but only the combination of **technical metadata, business context and lineage** creates meaningful transparency.

Important building blocks include:

- technical metadata for tables, columns, models and data types
- business descriptions, terms and definitions
- owners, stewards and domain assignments
- classifications such as PII, confidentiality or criticality
- lineage from source to data product, KPI or report
- impact analysis for planned changes

Metadata should be maintained as close as possible to where it is created, versioned and propagated automatically. Where dbt is used, descriptions, tests and `meta` attributes can form an important part of this chain.

## 3. PII & Privacy Governance

PII Governance does not only answer whether a column contains personal information. It also defines which safeguards follow from that classification and how those safeguards are enforced technically.

An operational PII process typically includes:

- identifying and classifying personal data
- defining sensitivity and protection requirements
- applying masking, tokenization or access restrictions
- propagating privacy metadata along the lineage
- versioning changes and making approvals traceable
- preventing accidental removal of critical protection metadata

The decisive step is connecting classification to technical control. A PII tag without an effect on masking, access or workflow remains documentation only.

## 4. DSDR Governance

In this playbook, **DSDR means Data Subject Deletion Request** — a specific request by a data subject to delete personal data in the context of GDPR.

A DSDR is not a single `DELETE` statement. Data may exist in operational systems, data warehouses, data lakes, exports, analytical models or downstream applications. At the same time, legal or business retention obligations may prevent complete deletion.

A controlled DSDR process should therefore:

1. verify the identity, authorization and validity of the request
2. determine the data subject and search identifiers unambiguously
3. locate relevant data across systems
4. evaluate retention obligations, legal holds and exceptions
5. delete, anonymize or restrict the processing of data
6. include dependent systems and data products
7. document execution and retain auditable evidence

Governance connects privacy, metadata, lineage, workflows and technical execution into one controlled process.

## 5. Data Quality Governance

Data Quality is more than a collection of technical tests. Governance defines **which level of quality is required**, who evaluates deviations and how issues are remediated sustainably.

Common quality dimensions include:

| Dimension | Example |
| --- | --- |
| **Completeness** | Mandatory fields are populated |
| **Validity** | Values comply with formats, ranges or reference data |
| **Uniqueness** | Business keys do not occur more than once |
| **Consistency** | Values do not contradict each other across systems |
| **Timeliness** | Data is available within the agreed time window |
| **Accuracy** | Data reflects the real-world fact correctly |

Operationally, this requires defined rules, measurable thresholds, monitoring, alerts, ownership and a remediation process. Tests should not merely fail; they should trigger a clear response.

## 6. KPI & Metric Governance

Two reports using the same KPI name should not produce different results. This is the problem that KPI & Metric Governance addresses.

For critical metrics, at least the following should be defined:

- unique name and business definition
- calculation logic and source data
- filters, time context and aggregation rules
- accountable owner
- approved version and validity period
- known limitations and quality requirements
- lineage to the underlying data

A semantic layer or metric store can support technical standardization. Governance additionally ensures that definitions are aligned, versioned and understood consistently across the organization.

## 7. Access & Security Governance

Access should not be granted broadly, but according to purpose, role and protection requirements. The objective is: **the right data for the right people — no more and no longer than necessary**.

Important principles include:

- Role-Based or Attribute-Based Access Control
- least privilege
- segregation of critical duties and responsibilities
- time-limited or approval-based access
- regular access recertification
- audit logs and monitoring of sensitive access
- technical enforcement through policies, Row-Level Security or masking

PII classification and Access Governance should be directly connected. The higher the protection requirement, the clearer approval, control and evidence must be.

## 8. Data Lifecycle & Retention

Data should not be stored indefinitely simply because storage is inexpensive. Retention Governance defines how data is handled from creation to deletion.

A complete lifecycle may include the following stages:

```text
Create → Use → Share → Archive → Delete or Anonymize
```

For relevant data classes, rules are defined for:

- retention period
- archiving and recoverability
- legal holds and regulatory exceptions
- automated deletion or anonymization
- treatment of backups, exports and replicas
- evidence that execution completed successfully

This pillar complements DSDR: retention governs regular, policy-based storage and deletion, while DSDR handles a specific deletion request from a data subject.

## How the pillars work together

The pillars are not separate programs. They reinforce each other:

```text
Ownership
    ↓
Metadata & Lineage
    ↓
PII Classification
    ↓
Access, Retention & DSDR Controls
    ↓
Data Quality & Trusted Metrics
    ↓
Reliable Decisions and Business Value
```

For example, a column is classified as PII. Lineage shows which models and reports use it. Access policies restrict visibility, retention rules govern how long it is stored and a DSDR workflow knows which systems are affected. Owners and stewards are accountable for approvals and exceptions. This connection turns metadata into operational governance.

## From documentation to executable governance

Many initiatives start with policies and spreadsheets. That is often necessary, but it is not sufficient. Mature governance gradually moves rules closer to technical execution.

| Maturity level | Typical state |
| --- | --- |
| **Documented** | Definitions and policies exist but are maintained manually |
| **Assigned** | Owners, stewards, domains and data classes are connected |
| **Measurable** | Quality, usage and controls are monitored |
| **Automated** | Rules generate tests, policies, workflows or technical controls |
| **Provable** | Changes, decisions and executions are versioned and auditable |

The goal is not maximum automation at any cost. What matters is that critical governance rules are implemented **consistently, repeatably and traceably**.

## One possible implementation pattern

In a modern data stack, governance can be implemented as an end-to-end chain:

```text
Business Definitions
        ↓
Metadata as Code
        ↓
dbt Models, Tests & Meta
        ↓
Warehouse Policies & Access Controls
        ↓
Catalog, Lineage & Monitoring
        ↓
Governed Data Products and KPIs
```

The specific tool selection remains open. The key question is not whether one product covers all eight pillars. The key question is whether information and controls are connected reliably across the systems in use.

## Common anti-patterns

Governance loses impact when it exists only outside operational data processes. Common patterns include:

- a data catalog without maintained ownership
- PII tags without technical protection
- Data Quality tests without alerts or accountable owners
- KPIs without approved definitions
- permissions without regular review
- retention policies without automated execution
- DSDR processes without complete system and lineage visibility
- policies that are neither versioned nor auditable

No single pillar can close these gaps on its own.

## The actual objective

Good Data Governance should not slow down data unnecessarily. It should reduce uncertainty and enable reliable use.

The outcomes are:

- **Compliance** — legal and regulatory obligations are fulfilled with evidence
- **Trust** — data and metrics are understandable and reliable
- **Efficiency** — standardized processes reduce manual effort and rework
- **Transparency** — lineage, accountability, quality and usage become visible
- **Business Value** — better data supports better decisions and outcomes

The eight pillars provide a shared frame for this work. They help position governance not as an isolated control function, but as a connecting layer across **people, processes, metadata, platforms and business value**.
