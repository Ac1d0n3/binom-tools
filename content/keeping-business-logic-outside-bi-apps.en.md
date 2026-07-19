---
title: Keeping Business Logic Outside the BI Apps
description: How to keep Qlik applications, Power BI reports and Excel workbooks thin by defining shared cleansing, integration, history, KPI foundations and data-quality rules in governed models outside individual BI artifacts.
category: Data Architecture
tags:
  - data-architecture
  - modern-data-warehouse
  - business-logic
  - semantic-layer
  - data-products
  - qlik-sense
  - qvd
  - set-analysis
  - section-access
  - power-bi
  - dax
  - excel
  - sql
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - data-quality
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 6
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start6-hero.png
---

## The BI app should not become the warehouse

A BI application often begins as the fastest place to solve a business problem.

A Qlik developer joins source tables, cleans identifiers, maps statuses, creates customer history, calculates revenue and builds the visual analysis in one application. A Power BI developer performs similar transformations in Power Query and adds the final definitions in DAX. An Excel user receives extracts, corrects values with formulas and turns the workbook into an operational reporting process.

Each result can be useful. The structural problem appears when the same business meaning is implemented independently in several consumer artifacts.

A typical landscape then contains:

```text
Qlik app A       Revenue excludes cancellation status 90
Qlik app B       Revenue excludes statuses 90 and 95
Power BI model   Revenue subtracts cancellation documents
Excel workbook   Revenue removes negative values manually
SQL export       Revenue uses the source cancellation flag
```

All five outputs may be called **Revenue**. They are not the same metric.

The issue is not that Qlik scripts, DAX or Excel formulas exist. Those capabilities are essential in the correct place. The issue is that a consumer tool becomes the only location where shared cleansing, integration, history and KPI rules are understood.

That creates several consequences:

- every new report reimplements existing logic;
- corrections must be deployed several times;
- data quality is checked differently or not at all;
- lineage stops inside opaque application code;
- one application cannot safely serve another consumer;
- business definitions depend on individual developers;
- migrations become difficult because logic and presentation are inseparable.

Part 5, [Modernizing an Existing Warehouse](/playbooks/modernizing-an-existing-warehouse), showed how duplicated QVD, SQL, Qlik, Power BI and Excel logic can be replaced incrementally. This part defines the target operating principle for the resulting architecture.

> **Define shared business meaning once in a governed data layer. Let Qlik, Power BI and Excel consume that meaning and add only the logic that is specific to their analytical experience.**

## Architecture principle: central truth, thin consumers

The target architecture separates two different responsibilities.

The governed data platform owns reusable business truth:

```text
Cleansing
Standardization
Source integration
Business keys
Reference mappings
History
Base KPI rules
Currency treatment
Cancellation handling
Data-quality rules
Publication contracts
```

The BI consumers own tool-specific analytical behavior:

```text
Associative exploration
Filter context
Visual calculations
Time-comparison interaction
Presentation
Navigation
Comments
Controlled manual input
```

This distinction is more precise than the statement “all logic must be outside the apps.” Some logic only has meaning inside the consumer.

A Qlik Link Table exists because of the associative model. Section Access controls access in a Qlik application. Set Analysis can express a user-facing comparison within the current selection state. DAX evaluates measures in the semantic model's filter context. Excel provides layouts, comments and controlled scenarios that may not belong in a warehouse table.

These are valid exceptions.

The governing rule is therefore:

> **Shared business truth belongs outside individual apps. Tool-specific semantics may remain in the tool when they create a clear analytical or operational benefit and are explicitly documented.**

## The thin Qlik application

A thick Qlik application performs several architectural jobs at once:

```flow linear vertical
Extract source data
Join operational systems
Clean and standardize values
Resolve history
Calculate business KPIs
Create mappings
Build the associative model
Apply access
Create visual analysis
```

A thin Qlik application starts much later in the lifecycle.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start6-img1-en.png"
        alt="Comparison of a complex Qlik application containing source joins, cleansing, history, KPI calculations and mappings with a thin Qlik application that loads a governed model and retains only Qlik-specific associations, access and visual analytics"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The governed platform prepares reusable business models and quality-controlled data. The Qlik application loads the curated contract and keeps only the script and model logic that Qlik genuinely requires.
    </figcaption>
</figure>

A practical thin Qlik application may still contain:

- connection and load statements;
- field selection and technical renaming for the local model;
- associations required for the analytical experience;
- a Link Table or canonical date structure where justified;
- Section Access where application-level enforcement is required;
- targeted performance optimization;
- master items and visual expressions;
- Set Analysis for interactive analytical context.

It should normally not contain:

- repeated source-system joins;
- the only implementation of cancellation rules;
- independent country or status mappings;
- customer-history reconstruction;
- local currency conversion rules;
- duplicated data-quality decisions;
- a separate definition of the enterprise revenue KPI.

The phrase **thin app** does not mean “no script.” It means that the script is restricted to consumption, Qlik-specific modeling and justified optimization.

## What belongs where?

The correct location of logic depends on whether it is reusable business truth or consumer-specific analytical behavior.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start6-img2-en.png"
        alt="Responsibility matrix showing which logic belongs in the governed data platform, Qlik, Power BI and Excel, with central cleansing, integration, history, KPI foundations and data quality separated from tool-specific analysis and presentation"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Logic that must be consistent or reused belongs in the governed platform. Logic that exists because of a tool's analytical model, interface or controlled local workflow may remain in that consumer.
    </figcaption>
</figure>

A practical allocation is:

| Logic | Primary location | Reason |
| --- | --- | --- |
| Technical cleansing | Governed data platform | Must be repeatable for every consumer |
| Source integration | Governed data platform | Establishes one cross-source interpretation |
| Business-key resolution | Governed data platform | Prevents consumer-specific identities |
| Customer and product history | Governed data platform | Historical truth must be reproducible |
| Cancellation handling | Governed data platform | Changes the shared business meaning of revenue |
| Currency normalization | Governed data platform | Must use one approved rate and date rule |
| Base KPI amount | Governed data platform | Provides one reusable factual foundation |
| Data-quality checks | Governed data platform | Must be auditable and independent of reports |
| Qlik associations | Qlik | Belong to the associative consumption model |
| Qlik Section Access | Qlik or platform plus Qlik | Required where access is enforced in the app |
| Qlik Set Analysis | Qlik | Expresses interactive selection-aware analysis |
| DAX time intelligence | Power BI semantic model | Evaluates measures in Power BI filter context |
| DAX presentation measures | Power BI semantic model | Supports reusable model-level analytics |
| Excel layout and comments | Excel | Belong to the workbook experience |
| Controlled manual assumptions | Excel plus governed write-back process | Local input may be valid when controlled and traceable |

The location can be split when responsibilities differ. For example, access eligibility may be created centrally, while Section Access applies it inside Qlik. The central layer defines who is entitled to which sales region; the Qlik app consumes that entitlement table and enforces it.

## Start with the simplest useful implementation

Keeping logic outside BI applications does not require a new cloud platform or an additional transformation product.

The smallest useful implementation can be:

```flow linear vertical
Existing source extracts
Existing SQL database
Views or controlled tables
Persistent quality results
Qlik / Power BI / Excel
```

Assume an organization already has SQL Server, PostgreSQL, Oracle or another relational database. It can introduce a small number of explicit responsibilities:

```text
stg       technically standardized source data
core      integrated entities, keys and history
mart      governed facts, dimensions and reporting views
quality   test runs and rule results
control   load, version and publication metadata
```

The first objective is not to create a theoretically perfect semantic layer. It is to move one duplicated rule to one governed place.

For example:

```sql
create view mart.sales_consumption as
select
    s.business_date,
    s.order_id,
    s.order_line_id,
    s.customer_key,
    s.product_key,
    s.country_code,
    s.currency_code,
    s.gross_revenue_amount,
    case
        when s.is_cancelled = 1 then 0
        else s.revenue_amount_in_reporting_currency
    end as net_revenue_amount,
    s.quality_status
from core.sales_order_line s
where s.publication_status = 'PUBLISHED';
```

This view is intentionally simple. The complexity should already have been resolved in controlled upstream steps:

- `is_cancelled` follows one approved status and document rule;
- `revenue_amount_in_reporting_currency` uses one approved exchange-rate date;
- `customer_key` resolves the customer identity and history;
- `country_code` follows one governed assignment;
- `publication_status` reflects persistent quality checks.

Qlik, Power BI and Excel now receive the same factual basis.

## A concrete KPI example: net revenue

Consider the KPI **Net Revenue**.

Its shared business definition requires decisions in five areas:

| Rule area | Governed decision |
| --- | --- |
| Cancellations | Cancelled order lines contribute zero net revenue |
| Currency | Amounts use the approved reporting currency and rate date |
| Business date | Analysis uses the agreed booking or posting date |
| Customer assignment | The transaction uses the customer version valid for the business date |
| History | Later customer changes do not rewrite the historical result |
| Quality | Records missing mandatory keys or rates are rejected, quarantined or explicitly marked |

These decisions belong in the governed data path because every consumer must apply them identically.

The platform can publish:

```text
gross_revenue_amount
net_revenue_amount
reporting_currency
business_date
customer_key
product_key
country_code
quality_status
```

The consumers then add context without redefining the metric.

### Qlik

The base measure can remain very small:

```qlik
Sum([Net Revenue Amount])
```

Set Analysis may add an analytical comparison:

```qlik
Sum({
    <BusinessYear = {"$(=Max(BusinessYear)-1)"}>
} [Net Revenue Amount])
```

The Set Analysis expression controls the comparison context. It does not recalculate cancellations, currency or customer history.

### Power BI

The base measure can use the same published amount:

```DAX
Net Revenue :=
SUM ( SalesConsumption[NetRevenueAmount] )
```

A time comparison can remain in the semantic model:

```DAX
Net Revenue Previous Year :=
CALCULATE (
    [Net Revenue],
    DATEADD ( 'Date'[Date], -1, YEAR )
)
```

DAX supplies model-specific analytical context. It does not rebuild the underlying revenue rule.

### Excel

Excel should preferably connect to the certified reporting view or semantic model and use:

- a PivotTable;
- a controlled query;
- a refreshable table;
- approved scenario columns that are clearly separated from actuals.

The workbook should not contain a hidden formula such as:

```text
IF(amount < 0, 0, amount)
```

and call the result Net Revenue. That creates a new local business definition.

## Business logic outside the apps

Once shared logic is externalized, one governed core can serve several consumers without forcing them to use the same interface or calculation language.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start6-img3-en.png"
        alt="A governed business logic layer containing metrics, business rules, reference data, transformations, data quality and time context that supplies Qlik, Power BI, Excel, APIs and other consumers with consistent definitions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Shared definitions are implemented, versioned, tested and owned once. Consumer tools remain free to provide different analytical experiences while using the same governed factual foundation.
    </figcaption>
</figure>

The central layer does not have to be one physical product. It can be a logical contract implemented through:

- SQL tables and views;
- warehouse schemas;
- lakehouse tables;
- governed files;
- semantic models;
- APIs;
- an optional QVD distribution layer;
- a combination of these components.

The architectural requirement is that the authoritative rule is identifiable, reusable and testable.

A useful data-product contract should state:

| Contract element | Example |
| --- | --- |
| Product | `sales_consumption` |
| Grain | One row per order line |
| Owner | Sales Data Owner |
| Technical owner | Data Platform team |
| Refresh | Daily before 06:00 |
| History | Customer and product version valid on business date |
| KPI foundation | Published `net_revenue_amount` |
| Quality | Mandatory key, currency, date and status checks |
| Security | Region eligibility supplied as governed access data |
| Consumers | Qlik, Power BI, Excel and approved APIs |
| Change policy | Versioned contract with compatibility assessment |

This contract is more important than the physical tool used to create it.

## Optional central QVD layer

A central QVD layer can be a valid implementation option, particularly in an existing Qlik estate.

For example:

```flow linear vertical
Sources
Central SQL or governed Qlik transformation
Governed QVD data products
Thin Qlik applications
```

The QVD layer may provide:

- efficient Qlik-optimized distribution;
- isolation from source availability;
- reuse across several Qlik applications;
- controlled incremental loading;
- a migration step away from app-local source extraction.

However, it should not automatically become the location of all enterprise business truth.

A governed QVD layer is appropriate when:

- Qlik is the dominant or only consumer;
- the team can operate and test the QVD generation centrally;
- data contracts, ownership and lineage are explicit;
- Power BI and Excel are not forced to reverse-engineer QVD-only logic;
- the layer reduces duplication rather than creating another copy.

When several consumer technologies require the same product, a platform-neutral table, view or API is usually the better authoritative contract. QVDs can still be generated from it as a Qlik-specific distribution format.

The decision is therefore not “QVD or warehouse.” A useful pattern can be:

```text
Governed warehouse model
        ↓
Optional governed QVD cache
        ↓
Thin Qlik apps
```

## Implementation options by existing platform

The logical principle remains the same across platforms.

### Existing relational warehouse

Use schemas, views, tables, stored procedures and the current scheduler. Add persistent quality and control tables. This is often the simplest viable option.

### Microsoft-oriented environment

Use the existing Microsoft data platform to publish governed warehouse or lakehouse tables and consumer views. Add separate semantic measures only where Power BI-specific filter context is required. Do not move shared rules into every report merely because the platform makes local modeling convenient.

### Snowflake-oriented environment

Publish governed tables or secure consumption views. Use SQL transformations and existing orchestration first. Add dbt when modular model dependencies, testing, documentation and team deployment workflows solve a real collaboration problem.

### Databricks-oriented environment

Publish governed Delta or SQL consumption tables from controlled transformation layers. Keep Spark-specific processing where scale, streaming or engineering requirements justify it. A small relational KPI rule does not become better merely because it runs in distributed code.

### dbt-enabled environment

Use dbt as an optional engineering framework for versioned models, dependencies and tests. dbt can improve implementation discipline, but it is not the business architecture itself. The contract, ownership and consumer boundaries still need to be designed.

### Qlik-centric environment without a warehouse

Create one centrally operated Qlik transformation or QVD-generation path as an intermediate architecture. Move source joins, cleansing, history and shared KPI foundations out of individual apps. Document the path as a governed data product and retain only Qlik-specific logic in the consuming applications.

### File-based or small environment

A controlled set of CSV, Parquet or spreadsheet-based outputs can be sufficient for a small scope if generation, schema, quality, ownership and refresh are controlled. The absence of a large platform does not justify uncontrolled local definitions.

## Necessary tool-specific exceptions

Some logic should remain in the consumer because moving it outside would remove functionality, damage usability or create an artificial abstraction.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start6-img4-en.png"
        alt="Decision guide for necessary tool-specific exceptions in Qlik, Power BI and Excel, including Qlik Link Tables, canonical dates, Section Access and Set Analysis, Power BI DAX time intelligence and measures, and Excel layouts, comments and controlled manual inputs"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Tool-specific logic is permitted where the tool is the correct execution context. Every exception remains documented, bounded and separate from the shared business definition.
    </figcaption>
</figure>

### Qlik exceptions

Valid Qlik-specific logic can include:

- **Link Tables** to manage several facts and shared dimensions in an associative model;
- **canonical dates** to let one calendar analyze several business-date roles;
- **Section Access** for application-level reduction;
- **targeted Set Analysis** for selection-aware comparisons;
- **alternate states** for controlled comparative analysis;
- **local association handling** when the consumption model requires it;
- **performance-oriented field shaping** when it materially improves application behavior.

These exceptions should not become a reason to move source cleansing and enterprise KPI definitions back into the app.

### Power BI exceptions

Valid Power BI-specific logic can include:

- semantic-model measures;
- DAX time intelligence;
- calculation groups;
- row-level security where it is enforced by the semantic model;
- display folders and model metadata;
- presentation calculations that depend on filter context;
- what-if parameters for controlled scenario analysis.

The underlying facts, mappings, cancellation rules and historical assignments should remain reusable outside the report.

### Excel exceptions

Valid Excel-specific logic can include:

- report layout;
- comments and annotations;
- controlled scenario assumptions;
- user-entered planning values;
- workbook-specific presentation formulas;
- local pivots and charts;
- reconciliations used as a visible temporary control.

Manual inputs should be clearly separated from governed actuals. Where inputs become operationally important, they need an owner, validation, versioning and a controlled path back into the platform.

## Document every exception

An exception should be visible in an architecture register rather than hidden in a script or workbook.

| Field | Example |
| --- | --- |
| Consumer | Qlik Sales Analysis |
| Exception | Canonical date bridge |
| Purpose | Analyze order, shipment and invoice date through one calendar |
| Business definition affected | None; date roles remain centrally defined |
| Owner | BI Platform team |
| Test | Counts and associations validated after every model change |
| Reuse | Local to Qlik associative model |
| Review date | Quarterly or after model redesign |
| Removal condition | Removed if the target model no longer needs multi-date association |

A similar entry can document a DAX measure or a controlled Excel input.

This register answers five questions:

1. Why does the logic exist in the tool?
2. Which shared definition does it consume?
3. Does it alter business truth or only analytical context?
4. Who owns and tests it?
5. Under which condition should it be moved, reused or removed?

## Data quality must remain outside the visualization

A report can display quality results, but it should not be the only place where quality is evaluated.

Persistent quality execution should capture at least:

```text
test_run_id
data_product
rule_id
severity
object_name
record_key
expected_value
actual_value
result_status
executed_at
owner
```

For the revenue example, rules may include:

- order line identifier is present;
- customer key is resolved;
- product key is resolved;
- business date is valid;
- currency rate is available;
- cancellation status is recognized;
- net revenue reconciles with the approved rule;
- publication is complete before the agreed deadline.

Qlik and Power BI may visualize failure rate, trend and affected records. Excel may support controlled remediation. The rule execution and result history remain independent of all three consumers.

## Access logic can be split without duplication

Security frequently spans the platform and the consumer.

A useful pattern is:

```flow linear vertical
Identity and entitlement source
Governed access table
Consumer-specific enforcement
Auditable access tests
```

The platform may publish:

```text
user_id
region_key
product_scope
valid_from
valid_to
access_status
```

Qlik can use this table in Section Access. Power BI can use the same entitlement in its security model. Excel receives only a permitted view or approved extract.

The eligibility rule is central. The enforcement mechanism is tool-specific.

## Typical anti-patterns

### Rebuilding the warehouse inside every Qlik app

Source joins, mappings, history and KPI calculations are copied into several reload scripts. Each app becomes a separate warehouse implementation.

### Treating a shared Qlik include file as complete governance

An include file reduces copied code, but it does not automatically provide ownership, testing, versioned data contracts, quality evidence or platform-neutral reuse.

### Moving all logic into one huge SQL view

Centralization alone does not create maintainability. A single opaque view can become as difficult to govern as a large Qlik script. Separate responsibilities and persist intermediate results where required.

### Reimplementing the same KPI in DAX

Each Power BI report creates its own version of Net Revenue, Gross Margin or Active Customer. A shared semantic model helps, but reusable factual rules should still be available beyond one report technology where required.

### Using Excel as an invisible correction layer

Users repair country codes, remove cancellations or add missing mappings manually. The workbook becomes operationally critical without ownership or traceability.

### Forcing every analytical calculation into the platform

Moving every comparison, ranking and selection-aware expression into warehouse columns creates a large number of context-specific fields and reduces analytical flexibility.

### Declaring all tool-specific logic an exception

An exception is justified by the tool's execution context. “It was faster to implement locally” is not a durable architecture reason.

### Creating another central copy without retiring local logic

A new governed mart is published, but old Qlik scripts, Power Query steps and Excel formulas remain active. The architecture gains an additional layer without reducing inconsistency.

### Making dbt, Fabric, Snowflake or Databricks mandatory

The principle can be implemented with existing SQL, governed QVDs or controlled files. A new product is justified only when it solves an identified delivery, scale, collaboration or operating problem.

## Decision guide

Use the following questions for every piece of logic:

| Question | If yes | If no |
| --- | --- | --- |
| Does it define shared business meaning? | Put it in the governed data path | Continue |
| Must several consumers use it identically? | Publish it once through a reusable contract | Continue |
| Does it integrate sources, keys or history? | Keep it outside individual BI apps | Continue |
| Does it determine publication quality? | Execute and persist it centrally | Continue |
| Does it exist because of the Qlik associative model? | Keep a documented Qlik exception | Continue |
| Does it depend on Power BI filter context? | Keep a documented DAX or semantic-model measure | Continue |
| Is it Excel presentation or a controlled local scenario? | Keep it in the workbook with clear boundaries | Continue |
| Could the result become operationally reused? | Move it toward a governed component | Local implementation may remain |
| Is the logic duplicated today? | Choose one target owner and migrate consumers | Avoid creating a second version |
| Does a new tool materially improve the solution? | Add it with an explicit responsibility | Use existing capabilities |

A compact rule is:

```text
Shared meaning          → central
Reusable transformation → central
Quality and history     → central
Tool execution context  → local and documented
Presentation            → local
Temporary exploration   → local, with promotion path
```

## Most important recommendations

1. Treat Qlik, Power BI and Excel as consumers of governed data products, not as independent warehouses.
2. Move shared cleansing, integration, key resolution, history, KPI foundations and data-quality rules outside individual BI artifacts.
3. Start with one duplicated high-value rule rather than attempting to redesign every application at once.
4. Keep Qlik scripts as thin as practical and retain only connection, loading, associations, access, performance and justified analytical logic.
5. Use Set Analysis for Qlik-specific analytical context, not to recreate shared cancellation, currency or history rules.
6. Use DAX for semantic-model and filter-context behavior, not as the only implementation of reusable business truth.
7. Connect Excel to certified views or governed models and separate controlled assumptions from actuals.
8. Publish one stable consumer contract with grain, definitions, owner, refresh, quality, security and change policy.
9. Persist data-quality results outside reports and make publication behavior explicit.
10. Centralize access eligibility and use the appropriate enforcement mechanism in each consumer.
11. Permit tool-specific exceptions only when the execution context creates real value.
12. Document every exception with purpose, owner, affected definition, test and removal condition.
13. Use a central QVD layer where it reduces Qlik duplication, but do not make QVD-only logic the default contract for non-Qlik consumers.
14. Use the existing relational database, scheduler and BI estate before adding another platform.
15. Treat Fabric, Snowflake, Databricks and dbt as implementation options rather than prerequisites.
16. Remove old local definitions after the governed replacement has been validated.
17. Measure success by fewer conflicting definitions, thinner consumer artifacts, faster change and improved traceability.

## Transition to the next part

Once business logic is defined outside individual BI applications, the same governed data product can serve several consumers without forcing them into one interface.

The next part, [One Data Product, Multiple Consumers](/playbooks/one-data-product-multiple-consumers), shows how one governed contract can support Qlik, Power BI, Excel and other delivery channels while preserving consumer-specific strengths.
