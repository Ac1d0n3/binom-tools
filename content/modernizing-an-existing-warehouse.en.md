---
title: Modernizing an Existing Warehouse
description: How to modernize QVD landscapes, stored procedures, SSIS pipelines, Excel outputs, Power BI models and large Qlik scripts incrementally — using inventory, prioritization, parallel validation and controlled retirement instead of a risky Big Bang rebuild.
category: Data Architecture
tags:
  - data-architecture
  - modern-data-warehouse
  - brownfield
  - warehouse-modernization
  - strangler-pattern
  - data-products
  - qlik-sense
  - qvd
  - power-bi
  - excel
  - sql-server
  - ssis
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - data-quality
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 5
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start5-hero.png
---

## Brownfield modernization starts with a different problem

A Greenfield warehouse begins with an empty platform. A Brownfield warehouse begins with business dependency.

The existing environment may contain:

- QVD generators that feed several generations of Qlik applications;
- large Qlik load scripts containing extraction, joins, mappings, history and KPI logic;
- stored procedures that have accumulated years of business rules;
- SSIS packages with hidden dependencies and operational workarounds;
- SQL tables that are technically active but no longer understood;
- Power BI semantic models that repeat transformations already implemented elsewhere;
- Excel exports that have become informal interfaces for critical processes;
- reports whose usage, ownership and replacement risk are unknown.

This environment may look outdated, but parts of it are often reliable, operationally proven and deeply embedded in business routines. Replacing everything at once therefore combines several risks:

```text
Unknown dependencies
Unwritten business rules
Changing source behavior
New platform behavior
New operating processes
New consumer interfaces
One coordinated cutover
```

The problem is not simply how to build a more modern stack. The problem is how to reduce structural risk without interrupting trusted business outcomes.

Parts 1 to 4 of this series established the decisions, logical layers, minimum viable architecture and Greenfield vertical-slice approach. Brownfield modernization applies the same principles under one additional constraint: the current result must continue to work until its replacement is proven.

> **Modernization should replace risk gradually. It should not replace every existing component merely because it is old.**

## Do not rebuild everything at once

A Big Bang rebuild assumes that the current environment can be fully understood, reproduced and replaced as one coordinated program. In most mature warehouses, that assumption is false.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start5-img1-en.png"
        alt="Comparison between a risky Big Bang warehouse rebuild and a controlled step-by-step modernization that keeps useful components, replaces one area at a time and retires legacy parts only after validation"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A complete rebuild changes too many variables at the same time. Incremental modernization keeps proven capabilities active, replaces one bounded area, validates the result and retires only what is no longer needed.
    </figcaption>
</figure>

A complete rebuild creates a long interval in which the organization invests without receiving a production improvement. It also delays learning. Incorrect assumptions about history, status mappings, exception handling or report usage may remain undiscovered until the final cutover.

An incremental approach produces evidence earlier. Each migrated domain reveals:

- which legacy components are truly required;
- which business rules are duplicated or contradictory;
- which consumers use which outputs;
- which data-quality problems belong to the source and which belong to transformation logic;
- which parts can remain temporarily or permanently;
- which platform capabilities provide measurable value.

The modernization unit should therefore be a bounded business capability or data product, not the complete warehouse.

Examples include:

```text
Daily Sales
Customer 360
Inventory Position
Open Orders
Service Performance
Finance Actuals
```

Each unit receives its own scope, owner, target contract, validation plan, consumer migration and retirement decision.

## Architecture principle: surround, replace and retire

The warehouse version of the Strangler Pattern keeps the existing environment active while a new governed path takes over one domain at a time.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start5-img2-en.png"
        alt="The Warehouse Strangler Pattern from inventory and prioritization through parallel rebuilding, validation, consumer migration, retirement and continuous repetition"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The legacy warehouse remains partly active while one bounded slice is rebuilt, compared with the current result, adopted by consumers and then retired. The cycle repeats domain by domain.
    </figcaption>
</figure>

A practical cycle contains seven steps:

```flow linear vertical
Inventory
Prioritize
Rebuild one bounded slice
Run old and new in parallel
Validate and explain differences
Migrate consumers
Retire the replaced path
```

### 1. Inventory

Create an evidence-based view of the current estate. The inventory should include more than object names.

| Inventory area | Minimum information |
| --- | --- |
| Sources | System, object, extract method, frequency, history and owner |
| Pipelines | Scheduler, package or job, dependencies, runtime, failure behavior and support owner |
| SQL logic | Procedures, views, tables, parameters, write targets and downstream dependencies |
| QVDs | Generator, refresh chain, fields, consumers, size, load frequency and retention |
| Qlik apps | Reload script, data model, KPI expressions, Section Access, bookmarks and usage |
| Power BI | Data sources, Power Query logic, semantic model, measures, refresh and report usage |
| Excel | Export origin, recipients, manual transformations, macros and business process dependency |
| Quality | Existing checks, known defects, accepted exceptions and reconciliation routines |
| Operations | SLA, support contacts, runbook, recovery steps and recurring manual intervention |

A catalog can help, but a spreadsheet or SQL inventory table is sufficient to start. The important point is to capture usage and dependency, not to wait for a perfect metadata platform.

### 2. Prioritize

Prioritization should combine business value, migration feasibility and operational risk.

A useful scoring model is:

| Dimension | Questions |
| --- | --- |
| Business value | Does the domain support important decisions, revenue, cost or regulatory obligations? |
| Pain | Does it create frequent incidents, slow changes or high manual effort? |
| Reuse | Would a central product replace logic across several applications or teams? |
| Dependency | How many sources, pipelines and consumers must move together? |
| Rule uncertainty | Are definitions documented and owned, or embedded in code? |
| Validation feasibility | Can old and new results be compared over a representative period? |
| Retirement potential | Can meaningful legacy components be switched off after migration? |

The first slice should be valuable enough to matter but bounded enough to complete. The most complex finance consolidation or the least used report is rarely the best starting point.

### 3. Rebuild one bounded slice

The new slice should implement the complete path required for one governed outcome:

```flow linear vertical
Required source data
Controlled ingestion
Standardization
Business integration and history
Data product
Quality results
Consumer contract
```

The new solution may use the existing database, a new platform or a hybrid. The architectural requirement is not a product choice. It is the separation of shared business logic from consumer-specific presentation.

### 4. Run in parallel

Old and new paths receive comparable source periods and produce results at the same grain. Neither result is assumed to be correct merely because it is old or new.

### 5. Validate and explain differences

Validation must compare data, definitions, timing, history and consumer behavior. A difference is evidence to investigate, not automatically a defect in the new system.

### 6. Migrate consumers

Consumers move only after ownership, acceptance criteria, security, refresh behavior and rollback are clear. Qlik, Power BI and Excel may move at different times if their contracts differ.

### 7. Retire

A migrated path is not complete while duplicate generators, packages, tables and exports continue to run indefinitely. Retirement removes schedules, access paths, storage, monitoring, support obligations and documentation for the old component.

## The simplest useful Brownfield implementation

Modernization does not require a new platform on day one.

A minimum implementation can use the existing database, scheduler and BI estate:

```flow linear vertical
Current source extracts
Existing SQL database
New controlled schemas and tables
Persistent validation results
Current Qlik / Power BI / Excel consumers
Legacy jobs retired one by one
```

For example, an existing SQL Server environment could introduce a small number of explicit schemas:

```text
raw       received source data
stg       technically standardized data
core      integrated business entities and history
mart      governed facts, dimensions and data products
quality   test and reconciliation results
control   load, dependency and migration metadata
```

Existing SSIS packages or database jobs may continue to perform ingestion initially. New transformations can be implemented with existing SQL views or stored procedures if that is the most maintainable option for the team.

The modernization value comes from clearer responsibilities, central rules, persistent quality evidence and controlled interfaces. It does not depend on replacing the scheduler, database and BI tools simultaneously.

## Concrete example: modernizing a sales landscape

Assume the current sales environment contains:

- three Qlik applications with similar customer and revenue logic;
- two QVD generator applications;
- one SSIS extraction chain from ERP and CRM;
- stored procedures that prepare monthly reporting tables;
- one Power BI model that repeats status and country mappings;
- weekly Excel files used by regional managers;
- different definitions of net revenue across consumers.

The objective is to create one governed Sales data product with:

| Element | Target decision |
| --- | --- |
| Grain | One sales order line per business date |
| Main fact | `mart.fact_sales` |
| Shared dimensions | `mart.dim_customer`, `mart.dim_product`, `mart.dim_date` |
| Revenue rule | Cancelled order lines contribute zero net revenue |
| Customer history | Customer attributes are resolved as valid on the order date |
| Status mapping | Source statuses map to one governed status domain |
| Consumers | Qlik, Power BI and controlled Excel access |
| Validation | Counts, keys, history, revenue, status, country, freshness and report totals |
| Owner | Sales data owner with technical platform owner |

### Step 1: trace the current rule

The team may discover that net revenue is currently calculated in several places:

```text
QVD generator A       excludes status 90
Qlik app B            excludes status 90 and 95
Power BI measure      subtracts cancellations by document type
Excel workbook        manually removes negative values
Stored procedure      uses a cancellation flag from ERP
```

The migration should not copy all variants into the new platform. It should expose the conflict, obtain an approved definition and retain the legacy variants only for reconciliation.

### Step 2: build a central target model

A simplified platform-neutral transformation could be:

```sql
select
    o.business_date,
    o.order_id,
    o.order_line_id,
    c.customer_key,
    p.product_key,
    c.country_code,
    s.order_status,
    o.gross_revenue,
    case
        when s.is_cancelled = 1 then cast(0 as decimal(18, 2))
        else o.gross_revenue
    end as net_revenue,
    o.source_changed_at,
    current_timestamp as loaded_at
from core.sales_order_line o
left join core.customer_history c
  on o.customer_id = c.customer_id
 and o.business_date >= c.valid_from
 and o.business_date < coalesce(c.valid_to, date '9999-12-31')
left join core.product_history p
  on o.product_id = p.product_id
 and o.business_date >= p.valid_from
 and o.business_date < coalesce(p.valid_to, date '9999-12-31')
left join core.order_status s
  on o.source_order_status = s.source_order_status;
```

The exact syntax varies by database. The important point is that status mapping, history resolution and the shared revenue basis now live outside an individual BI application.

### Step 3: keep consumer interfaces deliberately thin

A Qlik application may load the governed product directly:

```qlik
Sales:
SQL SELECT
    business_date,
    order_id,
    order_line_id,
    customer_key,
    product_key,
    country_code,
    order_status,
    gross_revenue,
    net_revenue,
    source_changed_at,
    loaded_at
FROM mart.fact_sales;
```

Qlik-specific associations, Section Access, alternate states or presentation fields can remain in Qlik where they are genuinely required. Shared customer matching, status mapping, historical assignment and net-revenue logic should not be rebuilt in every app.

Power BI can use the same governed fact and dimensions through its semantic model. Report interactions, formatting and genuinely presentation-specific measures remain local; shared business meaning remains central.

Excel can receive a governed reporting view or controlled extract at the required grain. Manual workbooks should not become an alternative transformation layer that silently redefines the KPI.

## From QVD-centric to platform-centric

QVDs are useful for Qlik performance, reuse and decoupling. The problem is not the file format. The problem arises when the QVD landscape becomes the only integration architecture and the complete business model is implemented through opaque chains of application scripts.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start5-img3-en.png"
        alt="Transition from a QVD-centric architecture with extraction, transformation and business logic distributed across Qlik applications to a platform-centric architecture with centralized governed data products, an optional shared QVD layer and thin consumer applications"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Modernization moves reusable extraction, integration, history, quality and KPI foundations into a central platform. QVDs can remain as an optional governed delivery layer while Qlik applications become thinner.
    </figcaption>
</figure>

A practical target allocation is:

| Responsibility | Preferred location |
| --- | --- |
| Source extraction and load metadata | Central ingestion or controlled extraction layer |
| Type conversion and technical normalization | Standardization layer |
| Customer and product identity | Integrated core |
| Historization and valid-time resolution | Integrated core |
| Shared status and country mappings | Governed reference data |
| Shared fact grain and revenue basis | Governed data product |
| Data-quality rules and results | Central quality layer |
| Optional Qlik-optimized QVDs | Shared delivery layer, generated from governed products |
| Qlik associations and Section Access | Qlik where required |
| Power BI semantic relationships and presentation measures | Power BI semantic layer where consumer-specific |
| Excel layout and local analysis | Excel, without redefining shared business rules |

The optional central QVD layer can be an effective transition mechanism:

```flow linear vertical
Governed platform tables or views
Central QVD generator
Stable shared QVD contract
Thin Qlik applications
```

This allows Qlik consumers to migrate before every application can connect directly to the platform. It also preserves operational characteristics that are already valuable. The QVD layer becomes a controlled interface rather than the place where the entire warehouse is defined.

## Parallel validation before cutover

A new model should not replace a mature result based only on a successful technical load. Old and new paths need a representative parallel period and explicit acceptance criteria.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start5-img4-en.png"
        alt="Parallel validation process in which current and new warehouse paths run side by side, compare row counts, nulls, keys, metrics, business rules, reports and performance, and switch consumers only after approval and rollback preparation"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Cutover confidence comes from explained differences, not from identical totals alone. Data owners and business users approve the result after definitions, timing, history and quality have been reconciled.
    </figcaption>
</figure>

### Validate at several levels

| Validation level | Examples |
| --- | --- |
| Technical completeness | Row counts, distinct keys, duplicate rates, null rates, rejected records |
| Referential integrity | Unresolved customers, products, countries and statuses |
| Aggregations | Revenue by day, country, customer segment, product and status |
| History | Attribute version selected for the business date, late-arriving changes and restatements |
| KPI logic | Gross revenue, cancellations, net revenue, open order value and currency handling |
| Timing | Source cutoff, timezone, late files, reload duration and publication time |
| Consumer result | Qlik objects, Power BI visuals, Excel extracts and scheduled distributions |
| Operations | Restart behavior, monitoring, alerting, access and rollback |

### Classify every difference

A useful reconciliation table does not only record the variance. It records why the variance exists.

```text
DEFINITION   Old and new rules are different
TIMING       Source cutoffs or processing times differ
HISTORY      Different effective-dated records are selected
QUALITY      Old output contains duplicates, nulls or invalid mappings
SCOPE        Consumers include different populations or filters
DEFECT_OLD   The legacy result is wrong
DEFECT_NEW   The new result is wrong
EXPECTED     Approved and documented difference
UNKNOWN      Investigation still required
```

Example validation-result fields:

| Field | Purpose |
| --- | --- |
| `validation_run_id` | Identifier of the comparison run |
| `domain` | Migrated data product or subject area |
| `business_date` | Compared reporting date or period |
| `check_id` | Stable reconciliation rule |
| `old_value` | Legacy result |
| `new_value` | New result |
| `absolute_difference` | Numeric difference |
| `relative_difference` | Percentage difference |
| `classification` | Definition, timing, history, quality, defect or expected |
| `owner` | Person responsible for resolution |
| `status` | Open, explained, accepted or rejected |
| `evidence_reference` | Link or identifier for detailed records |

### Define acceptance before the comparison

Acceptance should be agreed before the final cutover discussion.

Examples:

- all required business keys are present;
- no blocking quality rule fails;
- daily row counts reconcile within an approved tolerance;
- net revenue matches after documented definition changes;
- all historical differences are explained by valid-time rules;
- critical Qlik and Power BI outputs are signed off by named business owners;
- Excel recipients receive the replacement interface and instructions;
- the new path meets the agreed refresh window;
- rollback has been tested;
- the old path has a dated retirement plan.

Perfect equality is not always the objective. A new system may intentionally correct legacy defects. The objective is that every material difference is understood, owned and accepted.

## Platform options for the target state

The Strangler Pattern is independent of the target platform. The same logical migration can be implemented with the capabilities already available.

| Existing or target environment | Practical modernization approach |
| --- | --- |
| SQL Server and SSIS | Keep stable extraction initially, introduce controlled schemas, central facts and dimensions, persistent tests and gradual package retirement |
| Microsoft Fabric | Move selected domains into a governed warehouse or lakehouse path while legacy SQL, QVD or file interfaces remain active during transition |
| Snowflake | Rebuild one domain in controlled schemas and publish stable data-product views or tables before moving each consumer |
| Databricks | Rebuild selected data products in a lakehouse-oriented path where distributed processing or existing platform standards justify it |
| Hybrid | Keep sources or stable transformations On-Premises while selected integration, product or consumption layers move gradually |
| Existing SQL warehouse without platform change | Modernize naming, ownership, testing, versioning, interfaces and BI boundaries without forcing a migration to another product |

Optional dbt usage can be introduced when modular SQL development, dependency management, testing, documentation or team collaboration provides enough value to justify the additional workflow. It is not a prerequisite for the migration method.

The platform decision should follow the criteria from [Choosing the Simplest Viable Architecture](/playbooks/choosing-the-simplest-viable-architecture): volume, freshness, transformation type, team model, governance, consumers, availability, existing skills and total operating cost.

## Typical anti-patterns

### Migrating objects instead of outcomes

Recreating every table, QVD and package preserves the old architecture in a new location. The migration unit should be a governed business outcome with an explicit consumer contract.

### Copying legacy logic without resolving conflicts

A rule that exists in five applications is not automatically correct. Duplicate definitions must be compared, owned and approved before they become central.

### Calling a platform replacement a modernization strategy

Moving the same opaque procedures and app logic to another engine changes infrastructure, not architecture.

### Building the new path without usage evidence

Unused reports, duplicate exports and abandoned QVDs should not be migrated merely because they exist.

### Running old and new forever

Parallel operation is a validation phase, not a permanent operating model. Every slice needs exit criteria and a retirement date.

### Validating only totals

Matching total revenue can hide missing customers, duplicated lines, different history, shifted dates or offsetting errors. Validation must include grain, keys, distributions and business rules.

### Moving all BI logic centrally

Shared definitions belong centrally. Consumer-specific interaction, visualization and tool behavior may remain in Qlik, Power BI or Excel. Centralization should increase reuse, not erase legitimate consumer responsibilities.

### Removing QVDs only to prove modernization

A governed QVD delivery layer can remain useful. The target is thin and controlled Qlik consumption, not the elimination of a file format for symbolic reasons.

### Introducing several new tools in the first slice

A first migration that simultaneously changes ingestion, storage, transformation framework, scheduler, catalog, BI interface and operating model makes root-cause analysis difficult. Change only what the bounded slice requires.

## Decision guide: what should move first?

| Situation | Recommended first action |
| --- | --- |
| Many Qlik apps repeat customer and revenue logic | Build a central customer dimension and sales fact, then migrate one high-value app |
| SSIS is stable but transformations are opaque | Keep extraction, move one transformation chain into controlled schemas and add reconciliation |
| Power BI and Qlik disagree on KPIs | Approve a shared data-product definition before changing either report layer |
| Excel exports are operationally critical | Treat the export as a consumer contract and replace it only after recipient validation |
| Large QVD estate with unknown usage | Measure reloads and consumers before deciding which QVDs to rebuild, retain or retire |
| Stable On-Premises warehouse with weak governance | Add ownership, tests, versioning, lineage and controlled interfaces before considering cloud migration |
| New platform already exists | Use it for one bounded domain and prove operating, quality and consumer migration patterns |
| Legacy result is known to be wrong | Preserve it for comparison, document the correction and obtain explicit business acceptance |
| No reliable dependency map exists | Start with runtime logs, scheduler chains, script references and user interviews; do not wait for perfect lineage |

## Most important recommendations

1. Modernize one bounded data product at a time.
2. Inventory usage, dependencies, owners, business rules and operational behavior before rebuilding.
3. Prioritize by business value, pain, reuse, feasibility and retirement potential.
4. Keep the current path active until the replacement is validated.
5. Treat legacy and new outputs as hypotheses to compare, not as automatic truth.
6. Classify differences by definition, timing, history, scope and data quality.
7. Move shared identity, history, mappings, fact grain and KPI foundations outside individual BI applications.
8. Keep Qlik scripts thin and retain only genuinely Qlik-specific logic in the app.
9. Use a central QVD layer when it provides a controlled and practical transition interface.
10. Let Power BI and Excel consume the same governed data product without redefining its shared rules.
11. Start with existing SQL, scheduling and BI capabilities when they can support the slice reliably.
12. Add Fabric, Snowflake, Databricks or dbt only when a concrete requirement justifies them.
13. Define acceptance criteria, business sign-off, rollback and communication before cutover.
14. Retire schedules, storage, access paths and support obligations after migration.
15. Use each completed slice to improve the next migration pattern.

## Transition to the next part

Brownfield modernization succeeds when reusable business logic moves out of fragmented procedures, QVD chains, Qlik applications, Power BI models and Excel workbooks into governed platform responsibilities.

That boundary deserves a deeper treatment. The next part, [Keeping Business Logic Outside the BI Apps](/playbooks/keeping-business-logic-outside-the-bi-apps), defines which logic belongs centrally, which logic may remain consumer-specific and how thin Qlik, Power BI and Excel layers can use the same governed data products.
