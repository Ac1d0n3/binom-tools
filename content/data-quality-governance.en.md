---
title: Data Quality Governance
description: A practical operating model for reliable, complete, consistent, timely and fit-for-purpose data — with clear accountability, measurable rules and continuous improvement.
author: Thomas Lindackers
category: Data Governance
tags:
  - data-governance
  - data-quality
  - data-observability
  - data-testing
  - data-stewardship
  - quality-rules
  - monitoring
  - data-products
order: -1
publishedAt: 2026-06-06
series: governance-pillars
seriesPart: 6
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/quality-gov-hero.png
---

## Trust does not come from available data — it comes from reliable data

Data is not automatically trustworthy just because it is technically available.

A report may load on time and still contain incorrect values. A data product may be fully documented while silently losing important records. A pipeline may complete without a technical error even though business rules have been violated.

**Data Quality Governance** creates a binding framework for one core question:

***What level of quality does data need for its intended purpose — and how is that quality measured, governed and continuously improved?***

Common problems in mature data landscapes include:

- quality rules exist only in the heads of individual business experts
- technical tests validate structure but not business meaning
- every platform uses different thresholds
- defects become visible only in reports or executive meetings
- data quality is measured centrally but nobody feels accountable
- known issues remain open indefinitely
- quality metrics are published without triggering action
- data products have no defined quality commitment
- poor-quality data is replicated downstream
- root cause and impact are difficult to understand because lineage is incomplete

> *Data Quality Governance connects business expectations, technical rules, ownership, monitoring and improvement into a continuous operating model.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/quality-gov-en.png"
        alt="Data Quality Governance covering quality dimensions, management cycle, roles, quality activities, metrics and maturity"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Data Quality Governance turns quality into a shared, measurable and continuously managed property of data products.
    </figcaption>
</figure>

## Data quality is always purpose-specific

There is no universal definition of “good” data quality.

A dataset may be sufficient for high-level trend analysis but unsuitable for regulatory reporting. An address may be adequate for regional aggregation but not for contract delivery.

Quality must therefore be assessed in context:

```text
Data
  +
Intended Use
  +
Quality Expectation
  +
Measurable Rule
  =
Assessable Fitness for Purpose
```

The central question is not:

*“Is the data perfect?”*

It is:

***“Is the data sufficiently reliable for the agreed purpose?”***

## The key dimensions of data quality

| Dimension | Core question | Example |
| --- | --- | --- |
| **Accuracy** | Does the data represent the real world correctly? | Customer status matches the actual contract status |
| **Completeness** | Are all required values present? | Every active order has a customer ID |
| **Consistency** | Does data agree across systems and over time? | Customer segment is identical in CRM and warehouse |
| **Timeliness** | Is data available when expected and sufficiently current? | Daily revenue is fully loaded by 07:00 |
| **Validity** | Do values comply with formats, ranges and business rules? | End date is not earlier than start date |
| **Uniqueness** | Are unauthorized duplicates absent? | One customer ID identifies exactly one customer |
| **Integrity** | Are relationships and technical dependencies correct? | Every order references an existing customer |
| **Relevance** | Is the data appropriate for its intended purpose? | The KPI model contains the required granularity |
| **Conformity** | Does data follow agreed standards and definitions? | Country codes consistently use ISO standards |
| **Traceability** | Are origin, transformation and accountability visible? | A KPI value can be traced back to its source |

Not every asset requires all dimensions with the same weighting. Critical data products should have an explicit quality profile.

## From quality objective to measurable rule

A strong quality rule is understandable, executable and accountable.

Example:

```text
Business expectation:
Every active order must be linked to a valid customer.

Measurable rule:
customer_id IS NOT NULL
AND customer_id EXISTS IN customer_master

Threshold:
99.95% passed

Owner:
Order Data Owner

Steward:
Sales Data Steward

Response:
Warning below 99.95%
Incident below 99.50%
```

A complete rule definition can include:

| Metadata field | Example |
| --- | --- |
| **Rule name** | `active_order_has_valid_customer` |
| **Description** | Active orders require a valid customer |
| **Dimension** | Integrity |
| **Affected asset** | `conform.orders` |
| **Business criticality** | High |
| **Threshold** | 99.95% |
| **Measurement frequency** | After every load |
| **Data Owner** | Sales Domain Owner |
| **Data Steward** | Order Data Steward |
| **Technical Owner** | Data Platform Team |
| **Failure action** | Incident + remove certification |
| **Exception process** | Approved exception with expiry date |
| **Last review** | 2026-07-01 |

This turns a general expectation into a governable control point.

## The Data Quality Operating Model

A robust quality model follows a continuous cycle.

```text
1. Define quality objectives and accountabilities
        ↓
2. Profile data and implement rules
        ↓
3. Measure and monitor quality
        ↓
4. Analyze root causes and impact
        ↓
5. Implement corrective and preventive action
        ↓
6. Validate results and improve standards
        ↺
```

## 1. Define quality objectives and accountabilities

Not every table needs the same level of governance.

Prioritization should be risk-based:

- regulatory data
- financial metrics
- critical operational processes
- highly used data products
- sensitive or personal data
- core master data
- data with many downstream dependencies
- data with recurring defects

For each prioritized asset, determine:

- Which business process depends on it?
- Which users and decisions are affected?
- Which quality dimensions are critical?
- What minimum level of quality is expected?
- Who approves thresholds?
- Who responds to violations?
- What is the consequence of a failure?
- Can the asset continue to be used when the threshold is missed?

## 2. Profile data and implement rules

Data profiling creates an objective baseline.

Typical profiling outputs include:

- null rates
- value distributions
- minimum and maximum values
- format patterns
- cardinality
- duplicates
- outliers
- most frequent values
- new or missing categories
- historical change
- referential violations

Profiling identifies anomalies but does not define business quality by itself.

Example:

A field has 0% null values. Technically it appears complete. If every value is the placeholder `UNKNOWN`, business completeness is still poor.

Profiling should therefore be combined with business rules.

## 3. Measure and monitor quality

Quality measurement should begin as close as possible to where data is created.

A possible control model:

```text
Source
  ↓
Ingestion Checks
  ↓
RAW Structural Tests
  ↓
Conform Business Rules
  ↓
Analytics Reconciliation
  ↓
BI / KPI Validation
```

Typical control types include:

| Control type | Example |
| --- | --- |
| **Schema test** | Required columns exist with expected data types |
| **Null test** | Critical keys are complete |
| **Unique test** | Business keys are unique |
| **Relationship test** | Foreign keys reference valid records |
| **Range test** | Percentage values are between 0 and 100 |
| **Business rule** | Cancelled orders have a cancellation date |
| **Volume check** | Record counts do not deviate unexpectedly |
| **Freshness test** | Data is updated within the expected time window |
| **Reconciliation** | Source and target totals match within tolerance |
| **Anomaly detection** | Distributions or patterns differ significantly from normal |
| **Drift detection** | Schema, values or categories change unexpectedly |
| **KPI test** | A calculated metric matches the approved definition |

Not every rule must block a pipeline.

Different response levels may apply:

```text
INFO
→ make visible

WARNING
→ notify Owner and observe

ERROR
→ create incident and remove certification

CRITICAL
→ block publication or pipeline
```

## 4. Analyze root causes and impact

A quality issue should not be treated as a local event only.

Example:

```text
CRM source
   ↓
customer_email contains invalid values
   ↓
RAW customer
   ↓
CONFORM customer
   ↓
Customer 360
   ↓
Campaign audience
   ↓
Delivery failures and incorrect KPI
```

Lineage supports two questions:

- Where did the defect originate?
- Which data products, reports and decisions are affected?

Root-cause analysis should distinguish between:

- incorrect capture at source
- incomplete interface
- incorrect transformation
- missing or outdated reference data
- unclear business definition
- technical load failure
- late data delivery
- unintended schema change
- incorrect manual maintenance
- inappropriate quality objective

The root cause should be fixed where it originates whenever possible. Permanent cleansing in reporting only moves the problem downstream.

## 5. Implement corrective and preventive action

Actions can be applied at several levels.

| Level | Typical action |
| --- | --- |
| **Source** | Mandatory field, input validation, process change |
| **Interface** | Contract, schema, retry, error channel |
| **Pipeline** | Correct transformation logic |
| **Data model** | Represent business rule or reference correctly |
| **Governance** | Clarify definition, Owner or threshold |
| **Data cleansing** | Correct, standardize or deduplicate values |
| **Monitoring** | Add a rule or anomaly detector |
| **Training** | Improve data maintenance and quality accountability |

Every action should have at least:

- accountable role
- priority
- target date
- expected outcome
- validation criterion
- status
- documented decision

## 6. Validate results and improve standards

A closed ticket does not prove sustainable improvement.

After remediation, verify:

- Is the rule passing again?
- Has the root cause actually been removed?
- Does the issue recur?
- Have downstream datasets been corrected?
- Are reports and KPIs trustworthy again?
- Should the threshold be adjusted?
- Is an additional preventive rule required?
- Should the solution become a standard for other domains?

Data Quality Governance is therefore always a learning and improvement process.

## Roles and responsibilities

| Role | Typical responsibility |
| --- | --- |
| **Data Owner** | Defines quality objectives, criticality and acceptable risk |
| **Data Steward** | Maintains rules, definitions, issues and business assessments |
| **Data Quality Lead** | Develops standards, methods, scorecards and governance processes |
| **Data Engineer** | Implements tests, monitoring and technical remediation |
| **System Owner** | Improves quality in operational sources and interfaces |
| **Data Analyst** | Reports anomalies and assesses impact on analysis |
| **Data Product Owner** | Owns the quality commitment of the data product |
| **Data Consumer** | Uses data according to certification status and reports issues |

Data quality should not be delegated entirely to a central team.

A central team can provide standards and platforms. Business accountability remains within the domain.

## Quality by Design

Quality should be considered during development.

Before publication, a data product should be able to answer:

- Which business definition applies?
- Who is the Owner and Steward?
- Which quality dimensions are critical?
- Which rules are checked automatically?
- Which thresholds apply?
- What freshness is committed?
- What happens when a threshold is missed?
- How are consumers informed?
- Which known limitations exist?
- How is quality monitored continuously?

A possible Data Contract fragment:

```yaml
data_product: customer_360
owner: customer-domain
quality:
  freshness:
    max_delay_minutes: 60
  completeness:
    customer_id:
      threshold: 1.0
    email:
      threshold: 0.98
  uniqueness:
    customer_id:
      threshold: 1.0
  incident_policy:
    critical_breach: block_certification
```

## Data quality across the pipeline

A quality rule should be placed where it provides the greatest insight.

| Layer | Typical quality controls |
| --- | --- |
| **Source** | Input validation, mandatory fields, references |
| **Ingestion** | Schema, volume, delivery status, duplicates |
| **RAW** | Structure, technical completeness, load integrity |
| **Conform** | Standardization, relationships, business rules |
| **Analytics** | Aggregations, calculations, business consistency |
| **Semantic / BI** | KPI definitions, filter logic, reconciliation |
| **Data Product** | SLA, freshness, certification status, consumer guidance |

Not every rule should run only in the final layer. The earlier an issue is detected, the smaller the impact and rework.

## Data Quality and Observability

Data Observability complements classic quality rules.

Defined rules test known expectations. Observability additionally looks for unexpected change.

Examples include:

- sudden decline in data volume
- unusual null rates
- new categories
- changed value distributions
- schema drift
- delayed loads
- unexpected dependencies
- recurring error clusters

The combination is especially effective:

```text
Deterministic Rules
        +
Profiling
        +
Anomaly Detection
        +
Lineage
        +
Incident Management
        =
Operational Data Quality
```

## Issue Management

Quality problems require a defined workflow.

A possible model:

```text
Detected
  ↓
Triaged
  ↓
Assigned
  ↓
Root Cause Identified
  ↓
Remediation in Progress
  ↓
Validated
  ↓
Closed
```

Required information may include:

- issue ID
- affected asset
- quality dimension
- severity
- triggered rule
- detection timestamp
- business impact
- affected data products
- root cause
- Owner
- target date
- remediation
- validation
- recurrence status

Issues should be prioritized by business impact as well as technical urgency.

## Certification and trust status

A data product can expose a visible trust status.

Example:

| Status | Meaning |
| --- | --- |
| **Draft** | Not yet fully reviewed |
| **Observed** | Monitoring active, but no formal commitment |
| **Certified** | Definition, Owner, rules and thresholds reviewed |
| **Degraded** | A quality target is currently missed |
| **Restricted** | Use only under documented conditions |
| **Deprecated** | No longer intended for new use |

Certification status should react automatically or semi-automatically to quality events.

Example:

```text
Critical Rule Failed
        ↓
Status: Certified → Degraded
        ↓
Owner + Consumers notified
        ↓
Issue created
        ↓
Validation passed
        ↓
Status restored
```

## Measuring Data Quality Governance

Useful indicators include:

- Data Quality Score by data product or domain
- Rule Pass Rate
- number of active rules
- percentage of critical assets with defined quality objectives
- percentage of certified data products
- percentage of complete Owner and Steward assignments
- freshness compliance rate
- completeness rate
- issue count by severity
- Mean Time to Detect (MTTD)
- Mean Time to Acknowledge (MTTA)
- Mean Time to Resolve (MTTR)
- recurrence rate
- percentage of issues fixed at source
- number of overdue issues
- percentage of automatically validated rules
- number of manual exceptions
- percentage of quality issues with documented root cause
- number of degraded data products
- consumer trust or satisfaction for critical data products

One overall score can be useful, but it must not hide important detail.

## Example Quality Score

```text
Completeness:  98%
Accuracy:      95%
Freshness:    100%
Consistency:   92%

Weighting:
Completeness  30%
Accuracy      35%
Freshness     20%
Consistency   15%

Overall score:
96.2%
```

The score calculation should be transparent. A high average must not hide critical individual failures.

Hard conditions may therefore apply independently:

```text
Overall score >= 95%
AND
no critical rule failed
AND
Freshness SLA met
=
Certified
```

## A simple maturity model

| Maturity level | Typical state |
| --- | --- |
| **Reactive** | Issues are discovered by users or reports |
| **Measured** | Initial rules and manual scorecards exist |
| **Standardized** | Shared dimensions, rule types and roles are defined |
| **Automated** | Tests and monitoring run in pipelines and platforms |
| **Metadata-driven** | Rules, Owners, criticality and lineage are integrated |
| **Product-oriented** | Data products have explicit quality commitments and certification |
| **Proactive** | Observability and anomaly detection identify issues early |
| **Embedded** | Quality by Design is part of every data product and change |

## Common anti-patterns

Data quality initiatives often fail for predictable reasons:

- quality is checked only in reporting
- technical tests are confused with business quality
- rules have no Owner
- thresholds are set without business context
- every domain uses different dimensions and terminology
- defects are cleansed but not fixed at source
- one central score hides critical failures
- warnings are ignored indefinitely
- issues have no target date
- data quality is treated as the job of a small central team
- too many rules create alert fatigue
- tests run only overnight and not during change
- known exceptions have no expiry date
- lineage is not used for impact analysis
- quality status is invisible to Data Consumers
- degraded data products remain certified
- success is measured by the number of tests instead of reduced business problems

A passing test is not the objective. The objective is data that is reliable enough for its agreed purpose.

## Connecting to the other governance pillars

| Pillar | Connection |
| --- | --- |
| **Data Ownership & Stewardship** | Owners and Stewards define quality goals, priorities and actions |
| **Metadata, Catalog & Lineage** | Rules, scores, accountabilities and impact become discoverable |
| **PII & Privacy Governance** | PII classifications and protection metadata require quality controls |
| **DSDR Governance** | Identity keys, deletion status and validation must be reliable |
| **KPI & Metric Governance** | Trusted metrics require controlled inputs and defined rules |
| **Access & Security Governance** | Quality status and sensitive defect information require controlled visibility |
| **Data Lifecycle & Retention** | History, archiving and deletion influence completeness and consistency |

Data Quality Governance is therefore not an isolated testing discipline. It connects business expectations with operational data accountability.

## Practical target state

```text
Business Expectation
        ↓
Data Owner + Steward
        ↓
Quality Rule + Threshold
        ↓
Automated Test + Monitoring
        ↓
Score + Certification Status
        ↓
Issue + Root Cause + Remediation
        ↓
Validation + Continuous Improvement
        ↓
Trusted Data Products
```

## The outcome

Effective Data Quality Governance creates:

- **Trust** — consumers understand which data is reliable and certified
- **Transparency** — rules, scores, accountabilities and issues become visible
- **Control** — quality becomes measurable and linked to clear responses
- **Efficiency** — issues are detected earlier and rework is reduced
- **Accountability** — business and technical roles work with clear responsibilities
- **Risk Reduction** — incorrect decisions, faulty reports and regulatory issues are reduced
- **Business Value** — data products become more reliable and faster to use

High data quality does not come from creating as many tests as possible.

It comes from connecting **clear expectations, business accountability, automated measurement and consistent improvement**.

Related overview: [The 8 Pillars of Data Governance](/en/playbooks/eight-pillars).

Previous pillar: [DSDR Governance](/en/playbooks/dsdr-governance).

Next pillar: [KPI & Metric Governance](/en/playbooks/kpi-metric-governance).
