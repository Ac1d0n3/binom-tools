---
title: KPI Definition, Ownership and Versioning — From Business Process to Trusted Metric
description: A practical deep dive for Business Users, Data Stewards and Data Architects: which information a KPI requires, how it is derived from a business process, where its calculation logic should live and how changes should be governed and versioned.
category: Data Governance
tags:
  - kpi-governance
  - metric-governance
  - data-stewardship
  - data-architecture
  - business-intelligence
  - semantic-layer
  - data-modeling
  - data-quality
  - versioning
  - ownership
  - analytics
order: -1
author: Thomas Lindackers
hero: images/playbooks/define-kpi-hero.png
---

## A formula is not yet a KPI

Many metrics begin with an apparently simple requirement:

> “We need the on-time delivery rate.”

A formula can be written quickly:

`On-time deliveries / all deliveries`

But this does not yet define a reliable KPI.

Important questions remain open:

- What is a delivery: an order, order line, shipment or completed delivery event?
- Which date applies: promised, planned or customer-confirmed delivery date?
- How are partial, follow-up and cancelled deliveries treated?
- Which reporting period contains a delivery corrected later?
- Which countries, products, customers and channels are in scope?
- Which source is authoritative?
- How are missing or conflicting dates handled?
- Who may change the definition?
- Which version was used in a historical report?

The formula is only one component. A KPI is a **business and technical contract with a controlled version** describing what is measured, why it is measured, which data may be used and how the result can be reproduced.

> **A trusted KPI connects business decision, governance and technical implementation.**

## Three roles define one shared metric

An enterprise KPI cannot be defined by the business, the Data Steward or technical architecture alone.

Each role contributes a different and necessary perspective.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/define-kpi-img1-en.png"
        alt="Business User, Data Steward and Data Architect contribute different information to a shared KPI contract"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The Business User defines purpose and decision, the Data Steward controls meaning and governance, and the Data Architect ensures reproducible technical implementation.
    </figcaption>
</figure>

### Business User or domain team

The business perspective begins with operational reality, not tables or formulas.

It must explain:

- which business question must be answered
- which decision or action the KPI influences
- which business process is measured
- what a good, critical or unacceptable result looks like
- which targets and thresholds matter
- which dimensions are required for analysis
- which business exceptions exist
- how frequently the metric is needed
- who is accountable for its business definition and use

The Business User is not automatically responsible for SQL, data models or performance. The role is responsible for ensuring that the KPI supports a real decision rather than merely producing a technically available number.

### Data Steward

The Data Steward stabilizes the meaning of the metric.

The Steward ensures that:

- approved terminology is used
- the business definition is documented unambiguously
- scope and exclusions are traceable
- Data Owners and Business Owners are named
- quality requirements are defined
- valid values and classifications are consistent
- approval and change workflows are followed
- certification status and validity are visible
- documentation and lineage are maintained
- duplicate or competing KPIs are identified

The Steward is therefore not merely a documentation role. It connects business meaning with controlled use.

### Data Architect

The Data Architect translates the KPI contract into a technically reproducible structure.

This includes:

- measurable fact or event
- grain
- authoritative source systems
- keys, dimensions and hierarchies
- date, period and calendar logic
- transformation and calculation logic
- historical treatment and late-arriving data
- calculation layer and exposure pattern
- testing and reconciliation
- performance, scale and reuse
- technical lineage and dependencies

The Data Architect should not replace missing business definitions with silent technical assumptions. Definition gaps must be made visible and returned for clarification.

## The KPI contract is the shared working object

The KPI contract is not a legal agreement. It is a governed and versioned object.

At minimum, it contains:

| Area | Required information | Primary responsibility |
| --- | --- | --- |
| **Name** | Unique name and permitted synonyms | Steward |
| **Purpose** | Why does the KPI exist? | Business User |
| **Decision** | Which decision or action does it support? | Business User |
| **Business process** | Which process is represented? | Business User |
| **Population** | Which cases belong to the total population? | Business User / Steward |
| **Exclusions** | Which cases are deliberately excluded? | Business User / Steward |
| **Numerator** | What counts as success or the relevant event? | Business User / Architect |
| **Denominator** | What is the complete comparison population? | Business User / Architect |
| **Grain** | At which level is the metric counted? | Architect |
| **Time logic** | Which date, period and calendar apply? | Steward / Architect |
| **Dimensions** | Which analysis axes are permitted? | Business User / Architect |
| **Source** | Which systems and data objects are authoritative? | Architect / Steward |
| **Quality rules** | Which minimum requirements apply? | Steward / Architect |
| **Owner** | Who owns meaning, data and implementation? | All roles |
| **Version** | Which definition is effective? | Steward / Architect |
| **Status** | Draft, reviewed, approved, active, deprecated or retired | Steward |

A KPI is complete only when business meaning and technical reproduction describe the same thing.

## Start with the business process — not the formula

The most stable route to a KPI begins with the decision.

```flow linear vertical
Business question
Decision or action
Relevant business process
Measurable business event
Population and exclusions
Numerator and denominator
Grain and dimensions
Time and period logic
Source and lineage
Quality and reconciliation
Targets and thresholds
Ownership and approval
Technical implementation
Certification and publication [active]
```

### 1. Define the business question

A useful business question is more precise than the name of the metric.

Weak:

> “What is our delivery rate?”

Better:

> “In which regions, product groups and process stages do we miss committed delivery dates often enough to require operational action?”

The second question already indicates that the KPI:

- must expose deviations
- must be analyzable by relevant dimensions
- needs a connection to a business process
- may trigger a decision or escalation

### 2. Define the decision and action

A KPI without a potential decision quickly becomes decorative reporting.

Possible actions for a delivery KPI include:

- prioritize critical suppliers
- change inventory or transport routes
- notify customers earlier
- escalate operational causes
- differentiate targets by business segment
- increase capacity in affected regions

The decision determines the required freshness, dimensions, thresholds and level of detail.

### 3. Identify the measurable event

Business processes consist of events.

For delivery, those events may be:

```flowchart
Order
Promise
Shipment
Delivery
Completion
```

The KPI must define which event represents the business reality.

“Delivery completed” could mean:

- the first shipment was delivered
- all order lines were delivered
- the customer confirmed receipt
- the ERP status changed to completed
- the remaining quantity was cancelled and the order was closed

These interpretations are not equivalent.

### 4. Define grain before aggregation

Grain answers the question:

> What does one countable record represent?

Possible levels include:

- order
- order line
- shipment
- package
- delivery event
- customer confirmation

An on-time rate at order level can differ from the same rate at order-line level. One order with ten lines and one late line may count as completely late at order level. At line level, nine of ten lines were on time.

The formula may look identical. The meaning is different.

## From process to technical KPI

After the business definition, the real process is translated into a governed data product.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/define-kpi-img2-en.png"
        alt="From business process through measurable event and KPI contract to technical model and consumption channels"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The KPI contract is the connecting layer between business reality and technical implementation. Dashboards, alerts, applications, AI and reports can then consume the same definition.
    </figcaption>
</figure>

The path should not run directly from a source table to a visualization.

```flowchart
Business Process
Measurable Event
KPI Contract
Technical Model
Consumption
```

### Business process

The domain team describes the real sequence and relevant business states.

### Measurable event

Business User, Steward and Architect agree on the event that represents the reality sufficiently well.

### KPI contract

Definition, scope, formula, grain, time logic, sources, quality, owners, version and status are agreed.

### Technical model

Facts, dimensions, transformations, tests and semantic exposure implement the contract.

### Consumption

The KPI may be used through several channels:

- dashboard
- alert
- operational application
- standard report
- ad-hoc analysis
- API
- semantic layer
- AI or ML application

Consumption channels may change the presentation. They should not silently change the core meaning.

## Example: On-Time Delivery Rate

The KPI contract for the running example could look like this:

| Contract element | Example definition |
| --- | --- |
| **Name** | On-Time Delivery Rate |
| **Purpose** | Monitor compliance with committed delivery dates |
| **Decision** | Prioritize causes and trigger operational action |
| **Population** | All planned deliveries completed in the reporting period |
| **Numerator** | Deliveries with actual delivery date on or before the promised date |
| **Denominator** | All valid planned deliveries in scope |
| **Grain** | Delivery |
| **Exclusions** | Cancelled orders, test orders, fully rejected deliveries |
| **Time logic** | Period based on actual delivery date |
| **Source** | ERP sales and logistics |
| **Quality rule** | Promised and actual delivery dates must be present and plausible |
| **Business Owner** | Head of Supply Chain |
| **Data Steward** | Supply Chain Data Steward |
| **Technical Owner** | Analytics Engineering |
| **Version** | 1.0.0 |
| **Status** | Approved |

The definition is deliberately more detailed than the formula. That additional information makes the metric reproducible.

## Centrally defined KPI and dynamic formula are not the same

The term “static KPI” can be misleading.

The value of a centrally defined KPI is not static. It changes with new data, periods and filters.

What remains stable is the **agreed core logic**:

- population
- numerator
- denominator
- grain
- time logic
- sources
- exclusions
- quality rules
- version

A dynamic formula is created or modified in the consumption context, for example through:

- Qlik Set Analysis
- Excel formulas
- Power BI measures
- Tableau calculations
- SQL in individual reports
- user-defined filters
- AI-generated calculations
- local application logic

Dynamic formulas are not inherently wrong. They are important for exploration, simulation and contextual analysis.

The risk begins when they silently redefine an approved KPI.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/define-kpi-img3-en.png"
        alt="A governed metric core is combined with a controlled dynamic analysis context while an uncontrolled formula path creates incomparable results"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Dynamic analysis should build on a stable KPI core. Time, region, product or scenario can remain flexible without silently changing population, grain or calculation meaning.
    </figcaption>
</figure>

## The target state: stable core, controlled context

A useful semantic or analytical architecture separates two layers.

### Governed KPI core

Fixed and versioned:

- purpose and definition
- population and exclusions
- numerator and denominator
- grain
- time logic
- source and lineage
- quality rules
- version and status

### Controlled analysis context

Selected at runtime:

- month, quarter, year
- region, country, city
- product, category, SKU
- customer segment
- channel
- plan, actual, forecast, what-if
- prior year, prior month, target comparison

The result remains dynamic but comparable.

```flowchart
Certified KPI Core
Approved Dimensions
Controlled Filters
Dynamic Analysis
```

A Business User may analyze the delivery rate for EMEA, Q2 and a product group. This changes the context, not the definition of “on time”.

If “on or before the promised date” is locally changed to “within three days after the promised date”, the result is a different KPI or a new KPI version.

## Where should the KPI be calculated?

There is no universal calculation layer. The appropriate layer depends on reuse, criticality, complexity, freshness and the tool landscape.

```flow linear vertical
Source system
Warehouse transformation
Semantic or metric layer
BI master measure
Application-specific formula
User-defined analysis
```

### Source system

Appropriate when the metric is an integral part of an operational process and the source system controls its meaning.

Risks include:

- limited historical logic
- difficult reuse
- different source-system definitions
- mixing operational and analytical requirements

### Warehouse or lakehouse

Appropriate for:

- complex transformations
- history
- multiple source systems
- reusable facts
- periodic snapshots
- data-quality tests
- cross-system harmonization

The central logic should be versioned, tested and documented.

### Semantic or metric layer

Appropriate for:

- shared metric definitions
- controlled aggregation behaviour
- approved dimensions and filters
- reuse across several tools
- centralized business metadata

This layer can connect the data model with flexible consumption.

### BI master measure

Appropriate when the platform supports certified master measures and several applications reuse the same controlled definition.

The platform should prevent every application from creating a private copy with slightly different logic.

### Application-specific or user-defined formula

Appropriate for:

- exploration
- temporary hypotheses
- personal scenarios
- uncertified analysis
- local presentation logic

It should be labelled clearly as local, experimental or uncertified.

> **The more business-critical and widely reused a KPI is, the more centrally its core logic should be governed.**

## Process for creating a new KPI

A new KPI should follow a visible lifecycle.

```flowchart
Propose
Define
Validate
Implement
Test
Approve
Publish
Monitor
```

### Propose

The request contains at least:

- business question
- decision or action
- expected value
- Business Owner
- target audience
- required freshness

### Define

Business User, Steward and Architect clarify:

- process and event
- population
- numerator and denominator
- grain
- scope and exclusions
- time logic
- dimensions
- targets
- quality requirements

### Validate

Before development begins, check:

- Does the same or a similar KPI already exist?
- Are business terms inconsistent?
- Is the required data available?
- Is quality sufficient for the intended purpose?
- Is the desired grain technically available?
- Is the metric consistent with dependent KPIs?
- Are regulatory or contractual rules affected?

### Implement

Depending on the architecture, implementation may include:

- facts and dimensions
- transformation models
- snapshots
- semantic layer or metric definition
- master measures
- APIs or data products
- metadata and lineage

### Test

Testing should cover more than one correct average value:

- unit tests
- schema and data tests
- source reconciliation
- historical periods
- boundary and exception cases
- null and error cases
- aggregation behaviour
- performance
- comparison with existing reports

### Approve

A productive KPI needs at least:

- business approval
- Steward approval
- technical approval
- documented effective date
- named owners
- defined certification status

### Publish

The KPI is exposed where users can find and understand it:

- data catalogue
- semantic layer
- BI platform
- governance hub
- API documentation
- KPI registry

### Monitor

After publication, monitor:

- data quality
- freshness
- usage
- differences between tools
- recurring user questions
- performance
- change demand
- new business exceptions

## Never silently overwrite an existing KPI

When the meaning of a KPI changes, the previous definition must not simply be replaced.

Otherwise historical reports lose reproducibility. The same KPI name can produce different results depending on when it is executed.

The change process therefore begins with classification.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/define-kpi-img4-en.png"
        alt="KPI change lifecycle from change request through classification, impact analysis, implementation and approval to deprecation of the old version"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Meaning changes create new versions. Impact analysis, parallel operation, effective dating and communication prevent one KPI from silently representing several truths.
    </figcaption>
</figure>

## Change types and versioning

A three-part version number can be used as a pragmatic governance convention:

`MAJOR.MINOR.PATCH`

It is not a mandatory universal industry standard. The important requirement is a clear and consistently applied organizational rule.

### Patch change

No change to business meaning or result.

Examples:

- correct a typo
- improve a description
- add a translation
- update a link or contact
- clarify documentation

Example:

`1.0.0 → 1.0.1`

### Minor change

A backward-compatible extension that does not change existing results.

Examples:

- additional optional dimension
- more descriptive metadata
- new certified drill-down
- additional quality indicator
- new presentation based on the same definition

Example:

`1.0.0 → 1.1.0`

### Major change

A change to business meaning, population or calculation.

Examples:

- different numerator or denominator
- changed scope
- new exclusions
- different grain
- different time logic
- different authoritative source
- changed cancellation or partial-delivery treatment
- changed historical treatment

Example:

`1.0.0 → 2.0.0`

| Change | Typical version | Historical values | Approval | Parallel run |
| --- | --- | --- | --- | --- |
| Description corrected | Patch | unchanged | Steward | no |
| Optional dimension added | Minor | unchanged | Steward + Architect | optional |
| Formula changed | Major | potentially changed | Business Owner + Steward + Architect | recommended |
| Grain changed | Major | changed | full approval | required |
| Source replaced | Minor or Major based on impact | assess | Steward + Architect | recommended |
| Target changed | separate target version | KPI value unchanged | Business Owner | usually no |

## Version KPI definition and target separately

A common failure is combining measurement definition and target value.

The KPI can remain unchanged while the target changes.

Example:

- KPI version: `2.0.0`
- 2026 target: `95%`
- 2027 target: `97%`

The measured value remains comparable. Only its evaluation changes.

Targets therefore need their own metadata:

- valid from
- valid until
- organizational unit
- product or segment
- scenario
- approval status
- owner
- rationale

## Impact analysis before any meaning change

A major change often affects more than one report.

Assess:

- dashboards and standard reports
- alerts and thresholds
- operational applications
- APIs and data contracts
- SLAs and contracts
- management objectives
- incentive or steering models
- dependent KPIs
- historical comparisons
- planning and forecast models
- AI and ML products
- training and documentation material
- external or regulatory reporting

Technical lineage shows where the metric is consumed. Business impact analysis explains which decisions are affected.

## Parallel operation for major versions

For a meaning change, old and new versions should be available in parallel for a defined transition period.

```flowchart
Old Version Active
New Version in Test
Parallel Comparison
New Version Approved
Old Version Deprecated
Old Version Retired
```

Parallel operation enables:

- result comparison
- explanation of differences
- report migration
- target adjustment
- historical impact assessment
- user communication
- rollback if defects are found

The old version should not run indefinitely. It needs a planned retirement date.

## Effective dating and historical reproducibility

Every KPI version needs at least:

```flow linear vertical
Version identifier
Status
Valid from
Valid until
Business Owner
Data Steward
Technical Owner
Approval date
Definition snapshot
Implementation reference
Predecessor and successor
```

A useful status model is:

```flowchart
Draft
Review
Approved
Active
Deprecated
Retired
```

Historical reporting can follow two approaches.

### As reported at the time

The report uses the KPI version that was effective when the report was produced.

This matters for:

- audit
- regulatory evidence
- past management decisions
- contractual reporting
- historical reproduction

### Restated using current definition

Historical data is recalculated with the current KPI definition.

This is useful for:

- long-term trends
- harmonized management views
- model and methodology changes
- consistent multi-year analysis

Both approaches are legitimate. They must be labelled clearly.

## What dynamic formulas should be allowed to do

Governance should not remove all analytical freedom.

A controlled model can provide certified building blocks:

- On-Time Deliveries
- All Valid Deliveries
- Delivery Delay Days
- Cancelled Deliveries
- Planned Delivery Date
- Actual Delivery Date
- approved dimensions
- approved calendars

Users may then:

- compare periods
- filter regions
- analyze product groups
- simulate scenarios
- create local visualizations
- test temporary hypotheses

A local formula should be labelled when it:

- changes the population
- uses a different denominator
- overrides time logic
- introduces unapproved sources
- treats exceptions differently
- replaces a certified KPI under the same name

Possible labels include:

- Certified
- Governed derivative
- Local analysis
- Experimental
- Deprecated

This preserves self-service without declaring every local calculation to be enterprise truth.

## Minimum viable governance process

Not every organization needs a complex KPI tool immediately.

A functional minimum includes:

```flowchart
Central KPI Registry
Clear Roles
Versioned Definition
Approval Workflow
Lineage
Usage Labels
Monitoring
```

The KPI registry can begin in a catalogue, Git repository, governance application or controlled Markdown structure.

The product matters less than:

- a unique identifier
- an accessible definition
- named owners
- clear status values
- traceable versions
- linked technical artifacts
- visible dependencies
- documented changes

## Practical approval checklist

Before activating a KPI, the following questions should be answered.

### Business

- Which decision does the KPI support?
- Which process and event are measured?
- Are population, numerator and denominator unambiguous?
- Are grain, time logic and exclusions understandable?
- Are target and KPI definition separated?
- Is a Business Owner named?

### Governance

- Does a similar KPI already exist?
- Are terms and classifications approved?
- Are quality requirements defined?
- Is certification documented?
- Is the change process defined?
- Are validity and version visible?

### Technical

- Are sources and lineage known?
- Is grain explicit in the model?
- Are transformation and calculation versioned?
- Are tests and reconciliation implemented?
- Is the KPI consistent across consumption channels?
- Are performance and freshness sufficient?

### Consumption

- Is it clear which filters are permitted?
- Are local formulas labelled?
- Can users reach the definition?
- Are alerts and targets linked to the correct version?
- Are usage, quality and deviations monitored?

## The central rule

A KPI should not be treated as a formula that a Data Architect builds once and a Business User subsequently consumes.

It is a shared governance object that must be maintained over time.

The Business User provides purpose and decision.  
The Data Steward controls meaning, accountability and approval.  
The Data Architect ensures reproducibility, scale and technical consistency.

Dynamic analysis remains possible when it builds on a governed core.

When meaning changes, a new version is created.

> **A trusted KPI is not only calculated correctly. It is defined unambiguously, changed under control and historically reproducible.**

## Related playbooks

- [The Missing Pieces – Part 1: Data Quality](/playbooks/missing-pieces-data-quality) — why incorrect or incomplete source data should not only be repaired in reporting
- [Data Quality Governance](/playbooks/data-quality-governance) — how quality rules, ownership, monitoring and improvement form an operating model
- [One App Cannot Answer Every Question](/playbooks/one-app) — why several focused applications should reuse the same governed facts and KPI definitions
