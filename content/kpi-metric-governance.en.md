---
title: KPI & Metric Governance
description: A practical operating model for clearly defined, consistently calculated and trusted KPIs and metrics across data models, semantic layers, BI tools and Excel.
author: Thomas Lindackers
category: Data Governance
tags:
  - data-governance
  - kpi-governance
  - metric-governance
  - semantic-layer
  - dbt
  - qlik
  - excel
  - power-bi
  - tableau
  - business-metrics
order: -1
publishedAt: 2026-06-07
series: governance-pillars
seriesPart: 7
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/kpi-gov-hero.png
---

## Trusted data does not automatically create trusted metrics

An organization may have well-modeled data, documented tables and passing Data Quality tests — and still see different numbers in different reports.

The problem often exists not in the raw data, but in the **metric layer**:

- the same KPI is calculated differently across tools
- filters and selection logic change the result
- business rules are implemented directly in reports instead of governed layers
- Excel files contain local formulas and manual adjustments
- Qlik, Power BI or Tableau applications define their own dimensions
- aggregations behave differently depending on grain and context
- business changes are not versioned or approved
- a KPI name remains unchanged while the calculation changes
- teams use different time, customer or product definitions
- metrics have no accountable Owner

**KPI & Metric Governance** connects business meaning, technical calculation, ownership, lineage, approval and consumption into a controlled lifecycle.

> *A KPI becomes trustworthy only when its definition, formula, filters, grain, lineage and accountability are transparent and consistent across all consumption channels.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/kpi-gov-en.png"
        alt="KPI and Metric Governance covering definition, ownership, calculation, publication, monitoring and consumption in Qlik, Excel, Power BI and Tableau"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        KPI & Metric Governance connects business definitions, controlled calculation, semantic layers and consistent use across reporting and self-service tools.
    </figcaption>
</figure>

## A metric is more than a formula

A robust KPI definition includes at least:

| Component | Example |
| --- | --- |
| **Name** | Net Revenue |
| **Business description** | Revenue after cancellations, discounts and returns |
| **Purpose** | Monitor realized revenue performance |
| **Formula** | Gross revenue − discounts − returns |
| **Numerator / denominator** | Explicitly defined for ratios |
| **Grain** | Day, customer, product, region |
| **Time logic** | Booking date rather than invoice date |
| **Dimensions** | Region, product group, sales channel |
| **Filters** | Booked and non-cancelled items only |
| **Currency** | Group currency using the approved FX method |
| **Owner** | Finance KPI Owner |
| **Steward** | Finance Data Steward |
| **Source** | Fact table plus approved dimensions |
| **Approval status** | Certified |
| **Version** | 3.2 |
| **Valid from** | 2026-07-01 |
| **Replaced by** | Optional during deprecation |

Without this information, the same formula can produce different results in different contexts.

## The common gap between dbt and reporting

dbt is strong in transformation, documentation and version control for data models.

Many dbt projects deliberately model:

- **Facts** for measurable events
- **Dimensions** for business attributes
- standardized transformations
- pre-calculated fields
- tests and documentation

This is a strong foundation — but it does not guarantee consistent metrics.

Reporting tools often introduce additional calculation layers:

- Qlik uses dynamic expressions and context-dependent aggregation
- Power BI uses DAX Measures
- Tableau uses Calculated Fields and Level-of-Detail expressions
- Excel uses cell formulas, Pivot calculations, Power Query or Power Pivot
- self-service users create their own dimensions, groups and filters
- semantic models add logic outside dbt

As a result, a metric can diverge even when all tools use the same Facts and Dimensions.

Example:

```text
dbt model:
revenue = quantity * unit_price

Qlik:
Sum({<Status={'Closed'}>} revenue)

Power BI:
CALCULATE(
    SUM(FactSales[Revenue]),
    DimStatus[Status] = "Closed"
)

Excel:
=SUMIFS(Revenue, Status, "Closed")
```

The expressions appear equivalent but may return different results when:

- `Status` is interpreted differently
- null values are handled differently
- semantic relationships differ
- filter context behaves differently
- time dimensions are not aligned
- duplicates or many-to-many relationships exist
- users add custom groups or exceptions
- Excel uses a different data refresh

The core issue is:

```text
Trusted Facts + Trusted Dimensions
        ≠
automatically Trusted Metrics
```

Trusted metrics require a governed metric layer.

## Dynamic formulas are not the problem

Dynamic calculations are often essential in reporting tools.

They enable:

- context-aware aggregation
- flexible time comparisons
- Set Analysis
- scenarios and what-if analysis
- user selections
- dynamic grouping
- role-specific views
- interactive self-service analysis

The problem starts when logic:

- exists only locally in a report
- is undocumented
- has no Owner
- is not tested
- is not versioned
- differs from other tools
- has never received business approval
- is copied across applications without control

KPI Governance should therefore not prohibit dynamic logic. It should classify and govern it.

## Three layers of metric logic

A practical model distinguishes three layers.

| Layer | Typical logic | Governance objective |
| --- | --- | --- |
| **Data Model** | Standardized Facts, Dimensions and curated attributes | Reliable, reusable data foundation |
| **Semantic / Metrics Layer** | Approved KPI formulas, time logic, filters and grain | Central, cross-tool business definition |
| **Report / Analysis Layer** | Dynamic selections, visualization and local analysis | Controlled flexibility without creating new official truths |

The critical question is:

***Which logic belongs in the central approved metric definition — and which logic may remain intentionally local and exploratory?***

## The KPI & Metric Operating Model

A robust lifecycle can be structured in six steps.

```flow linear vertical
1. Define the KPI in business terms
2. Assign Owner, Steward and validity
3. Standardize formula, filters and grain
4. Publish the approved metric
5. Monitor usage, quality and divergence
6. Review, version and deprecate under control
```

## 1. Define the KPI in business terms

Before technical implementation, answer:

- Which business question does the KPI answer?
- Which decision does it support?
- Which events are counted?
- Which events are excluded?
- Which time logic applies?
- Which grain is valid?
- Which dimensions may be used?
- Which targets and thresholds apply?
- Which exceptions exist?
- Which similar metric must not be confused with it?

A definition such as “active customer” is insufficient.

A stronger definition:

```text
Active Customer

A customer is active when at least one completed
purchase with positive net revenue exists within
the previous 90 calendar days.

Excluded:
- test customers
- fully cancelled orders
- internal employee accounts

Time basis:
Rolling 90 Days using booking date
```

## 2. Ownership and decision rights

Metrics require business accountability.

| Role | Typical responsibility |
| --- | --- |
| **KPI Owner** | Owns meaning, purpose, approval and targets |
| **Data Steward** | Maintains definition, metadata, glossary and change history |
| **BI / Analytics Lead** | Ensures consistent implementation across reporting and semantic models |
| **Data Engineer** | Implements data models, transformations and technical tests |
| **Business Analyst** | Validates business use and identifies inconsistencies |
| **Data Product Owner** | Owns delivery and service levels |
| **Data Consumer** | Uses certified metrics correctly and reports divergence |

Decision authority must be clear.

The same KPI should not be redefined independently by every team.

## 3. Standardize calculation, filters and grain

A technical metric specification should explicitly define:

- formula
- sources
- join logic
- aggregation type
- time dimension
- grain
- default filters
- exclusions
- null handling
- currency logic
- rounding
- dimension dependencies
- Slowly Changing Dimension behavior
- late-arriving Facts
- historical recalculation

Example:

```yaml
metric: net_revenue
version: 3.2
owner: finance
expression: gross_revenue - discounts - returns
time_dimension: booking_date
default_grain: day
currency: group_currency
filters:
  order_status:
    - booked
    - completed
exclude:
  - test_customer
  - internal_account
certification: certified
```

## 4. Publish approved metrics

Approved metrics should be available where users actually work.

Possible targets include:

- Semantic Layer
- Metrics Layer
- dbt Semantic Layer
- Qlik applications and Master Measures
- Power BI Semantic Models
- Tableau Published Data Sources
- Excel through controlled data connections
- Data Catalog and Business Glossary
- API-based data products

The objective is not to force every user into one tool.

The objective is:

```flow linear vertical
One approved business meaning
Consistent metric logic
Multiple governed consumption tools
```

Qlik, Excel, Power BI and Tableau may provide different user experiences — but the approved KPI should retain the same meaning.

## Excel is part of the governance landscape

Excel remains heavily used in many organizations.

Typical reasons include:

- broad adoption
- flexible ad-hoc analysis
- rapid scenario modeling
- individual calculations
- familiar workflows
- easy distribution
- strong use in Finance and Controlling

Excluding Excel from governance is therefore unrealistic.

Instead, clear controls should apply:

- use certified data sources
- reference approved KPI definitions
- make manual overrides visible
- distinguish local formulas from official metrics
- display version and data freshness
- store critical workbooks under control
- require business approval for recurring management reports
- register important Excel KPIs in the metric inventory

A useful status model can be:

| Status | Meaning |
| --- | --- |
| **Exploratory** | Local analysis without official KPI status |
| **Reviewed** | Business-reviewed but not centrally published |
| **Certified** | Approved definition and controlled data source |
| **Deprecated** | No longer intended for new use |

## Qlik and dynamic filter context

Qlik provides high flexibility through Set Analysis and the associative data model.

That strength can also create divergence.

Example:

```qlik
Sum(Sales)
```

versus:

```qlik
Sum({<OrderStatus={'Closed'}>} Sales)
```

versus:

```qlik
Sum(
    Aggr(
        Sum({<OrderStatus={'Closed'}>} Sales),
        CustomerID
    )
)
```

All three expressions may be valid — but they do not automatically measure the same thing.

Governance should therefore define:

- Which Master Measures are certified?
- Which Set Analysis filters belong to the official definition?
- Which dimensions are approved?
- Which Alternate States affect the metric?
- Which local expressions are exploratory?
- How are Master Measure changes reviewed?
- How is the formula exposed through lineage and catalog metadata?

## Custom dimensions change meaning

Users frequently create their own dimensions:

- customer groups
- product clusters
- regions
- age bands
- sales segments
- time periods
- manual exceptions

This flexibility is valuable but can change the meaning of a metric.

Example:

```text
Central dimension:
Region based on contract location

Local report dimension:
Region based on invoice recipient postal code
```

Both reports show “Revenue by Region”, but they measure different concepts.

Dimensions therefore need governance as well:

- definition
- lineage
- validity
- hierarchy
- grain
- change history
- Owner
- approval status

## Metric lineage

A metric should be traceable back to its sources.

Example:

```flow linear vertical
CRM + ERP
RAW sales data
CONFORM sales facts
DIM customer / product / calendar
Semantic Metric: Net Revenue
Qlik / Excel / Power BI / Tableau
Management Report
```

Lineage should not stop at tables.

Where possible, it should also include:

- formula
- filters
- dimensions
- semantic models
- report objects
- Excel connections
- downstream management reports

## Detecting metric conflicts

Common conflict patterns include:

- same name, different formula
- same formula, different time logic
- same KPI, different default filters
- same metric, different grain
- local Excel adjustments
- Qlik Set Analysis diverges from DAX or SQL
- dimensions use different hierarchies
- historical versions remain in parallel use
- old reports retain outdated definitions

A governance process should make these conflicts visible.

Example:

| KPI | Tool | Version | Status |
| --- | --- | --- | --- |
| Net Revenue | Semantic Layer | 3.2 | Certified |
| Net Revenue | Qlik Sales App | 3.2 | Certified |
| Net Revenue | Finance Excel | 2.8 | Outdated |
| Revenue Net | Power BI | unknown | Review required |

## Testing metrics

Metric testing must go beyond technical data tests.

Useful controls include:

- formula tests
- source-to-metric reconciliation
- cross-tool comparison
- filter and context tests
- time logic tests
- grain tests
- regression tests during change
- known reference datasets
- boundary case tests
- divergence checks against an approved reference

Example:

```text
Reference Scenario:
Customer A
2 booked orders
1 cancelled order
10 EUR discount

Expected Net Revenue:
190 EUR
```

The reference should return the same result in dbt, the Semantic Layer and each governed BI tool.

## Change Management

Metrics change over time.

Typical triggers include:

- new business models
- regulatory requirements
- changed product structures
- new currency logic
- new time definitions
- mergers or organizational change
- corrected business rules

Every change should document:

- reason for change
- affected version
- new definition
- effective date
- impact on historical values
- affected reports
- migration approach
- approval
- communication plan

A KPI name should never silently acquire a new meaning.

## Versioning

Example:

| Version | Change | Valid from |
| --- | --- | --- |
| 1.0 | Initial definition | 2024-01-01 |
| 2.0 | Returns included | 2025-01-01 |
| 3.0 | New group currency | 2026-01-01 |
| 3.2 | Cancellation logic corrected | 2026-07-01 |

Historical reporting requires an explicit decision:

- keep historical values unchanged
- recalculate all history
- display parallel versions
- switch from an effective date

## Measuring KPI & Metric Governance

Useful indicators include:

- percentage of approved KPIs with an Owner
- percentage of KPIs with documented formulas
- percentage of KPIs with defined grain
- percentage of KPIs with available lineage
- percentage of certified metrics by reporting tool
- number of conflicting definitions
- number of local unregistered KPI formulas
- number of outdated KPI versions in reports
- time to approve a KPI change
- number of metric change requests
- number of data-quality incidents affecting KPIs
- use of certified versus local metrics
- number of Excel reports with manual overrides
- percentage of reports using governed semantic models
- number of cross-tool KPI mismatches
- reuse rate of centrally defined metrics

## A simple maturity model

| Maturity level | Typical state |
| --- | --- |
| **Local** | KPIs are defined individually in reports and Excel |
| **Documented** | Initial definitions and glossaries exist |
| **Standardized** | Owners, formulas and dimensions are harmonized |
| **Certified** | Approved metrics have status and version |
| **Semantically integrated** | Central metrics are delivered across multiple tools |
| **Monitored** | Usage, conflicts and divergence are measured |
| **Automated** | Tests, lineage and change workflows are integrated |
| **Embedded** | New reports and data products use approved metrics by default |

## Common anti-patterns

- the same KPI is rebuilt independently in every tool
- dbt Facts and Dimensions are assumed to provide complete KPI Governance
- dynamic report formulas are undocumented
- Excel is ignored even though management decisions rely on it
- local dimensions silently change meaning
- different time logic uses the same KPI name
- metrics have no business Owner
- calculations are copied instead of reused
- changes occur without versioning
- old reports remain active with outdated formulas
- Semantic Layer and BI logic contradict one another
- tests validate data but not the final metric result
- filters and grain are not part of the definition
- users cannot see certification status
- success is measured by the number of documented KPIs rather than fewer conflicts

A central fact table does not prevent conflicting metrics.

Trust emerges only when the **data model, metric definition, semantic layer and reporting consumption** are governed together.

## Connecting to the other governance pillars

| Pillar | Connection |
| --- | --- |
| **Data Ownership & Stewardship** | KPI Owners and Stewards approve meaning, changes and use |
| **Metadata, Catalog & Lineage** | Definitions, formulas, lineage and report usage become traceable |
| **PII & Privacy Governance** | Personal dimensions and segments require controlled use |
| **DSDR Governance** | Deletions may affect KPI populations and historical comparability |
| **Data Quality Governance** | Trusted metrics require reliable inputs and metric-level validation |
| **Access & Security Governance** | Not every metric or detailed dimension should be visible to every role |
| **Data Lifecycle & Retention** | History and retention determine reproducibility and comparison over time |

## Practical target state

```flow linear vertical
Business Definition
KPI Owner + Steward
Versioned Metric Specification
Facts + Dimensions + Semantic Logic
Certified Metric
Qlik / Excel / Power BI / Tableau
Monitoring + Reconciliation + Review
Trusted Decisions
```

## The outcome

Effective KPI & Metric Governance creates:

- **Shared meaning** — teams discuss the same business concept
- **Consistent calculation** — central logic reduces cross-tool divergence
- **Transparency** — formula, filters, grain, version and lineage are visible
- **Trust** — management and business users can explain the numbers
- **Flexibility** — reporting tools remain useful without creating new official truths
- **Efficiency** — approved metrics are reused
- **Control** — changes are versioned, tested and approved
- **Business Value** — decisions are based on aligned and reliable metrics

The decisive question is not:

*“Do we have a central fact table?”*

It is:

***“Do all relevant tools produce the same trusted metric from the same approved business rules?”***

Related overview: [The 8 Pillars of Data Governance](/en/playbooks/eight-pillars).

Previous pillar: [Data Quality Governance](/en/playbooks/data-quality-governance).
