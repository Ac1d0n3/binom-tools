---
title: Building a Warehouse from Scratch
description: How to build a Greenfield warehouse from one business question and one vertical end-to-end data product — then turn the first productive use case into a reusable platform pattern.
category: Data Architecture
tags:
  - data-architecture
  - modern-data-warehouse
  - greenfield
  - vertical-slice
  - data-products
  - dimensional-modeling
  - data-quality
  - qlik-sense
  - power-bi
  - excel
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - sql
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 4
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start4-hero.png
---

## Greenfield freedom can create the wrong kind of complexity

Building a warehouse from scratch appears easier than modernizing an existing one. There are no legacy schemas to preserve, no established load chains to disentangle and no old reports that must continue to work.

That freedom is useful, but it also creates a common failure mode: the team tries to build the complete platform before it has delivered one trusted answer.

Typical Greenfield plans begin with broad technical work:

```text
Connect every source
Load all available history
Create every technical layer
Model all major business entities
Select every future tool
Define an enterprise-wide platform
Ask the business what it needs
```

This sequence creates activity without proving value. Pipelines, schemas and environments accumulate while the team still cannot answer a concrete business question. The architecture is then optimized for assumptions rather than observed requirements.

Part 1, [Before Building the First Table](/playbooks/before-building-the-first-table), established the decisions that must precede implementation. Part 2, [Beyond Bronze, Silver and Gold](/playbooks/beyond-bronze-silver-gold), separated the logical responsibilities from source to governed consumption. Part 3, [Choosing the Simplest Viable Architecture](/playbooks/choosing-the-simplest-viable-architecture), showed how to select only the physical capabilities that are actually required.

This part applies those principles to a Greenfield implementation.

> **Do not build the warehouse horizontally before one vertical path works. Build one complete data product from business question to consumption, learn from it and reuse the proven pattern.**

## The wrong way to start

The wrong start is not defined by one particular tool. It is defined by the order of decisions.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start4-img1-en.png"
        alt="The wrong Greenfield sequence: choose a platform, connect everything, build generic layers, create reports and discover that the result has no clear business value"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A technology-first program creates sources, layers and reports before the target decision, KPI and data product are explicit. The result is often high cost, unfinished integration and low trust.
    </figcaption>
</figure>

The anti-pattern usually follows four assumptions:

1. more loaded data creates more future flexibility;
2. generic layers can be designed before the first real use case;
3. business definitions can be added later;
4. the reporting tool will reveal which questions matter.

Each assumption contains some truth, but together they reverse the correct dependency.

A warehouse is valuable because it supplies reliable information for defined decisions. The required sources, history, integration logic, quality controls and service levels can only be selected correctly after that purpose is understood.

Loading every source first also creates an ownership problem. A technical team becomes responsible for datasets whose business meaning, criticality and expected quality have not been agreed. The platform may contain data, but it does not yet contain governable products.

## Architecture principle: design backward, build forward

A Greenfield warehouse should be designed backward from the required outcome and implemented forward through a controlled data path.

The design sequence is:

```flow linear vertical
Business question
Decision and KPI
Target grain
Target fact and dimensions
Required fields
Required sources
Quality and history rules
Consumer contract
```

The implementation sequence is:

```flow linear vertical
Required source extracts
Controlled ingestion
Standardization
Integration and history
Data product
Quality results
Qlik / Power BI / Excel
```

The two sequences meet in the middle. The target model tells the team what must be ingested. The source analysis tells the team what can actually be delivered and where assumptions must be revised.

This approach avoids two extremes:

- **source-first overcollection**, where everything is loaded without a defined purpose;
- **report-first local modeling**, where the answer is built quickly inside one BI application and cannot be reused.

The objective is a complete, narrow and governable path.

## The vertical-slice approach

A vertical slice contains enough of every required capability to produce one trusted result. It is deliberately narrow in scope but complete in responsibility.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start4-img2-en.png"
        alt="Vertical slice from one business question through target data product, minimal sources, ingestion, integration, governed layers, consumption and reusable scaling"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        One business question determines the grain, required sources, transformations, tests and consumer interface. The slice delivers value early and becomes the reference implementation for later data products.
    </figcaption>
</figure>

A useful first slice should be:

- valuable enough that the business will use it;
- small enough to complete in a limited delivery cycle;
- representative enough to exercise key platform capabilities;
- explicit enough to expose ownership, quality and security questions;
- reusable enough to produce patterns for later work.

It should not be a throwaway prototype. The scope is minimal, but the implementation is production-oriented.

## Concrete example: the Sales Daily Data Product

Assume the first warehouse requirement is daily sales steering.

The business question is:

> What net revenue did we generate by business date, customer, product and country, and which orders changed or were cancelled since the previous load?

The initial decisions are:

| Decision area | Definition |
| --- | --- |
| Business purpose | Daily sales steering and exception analysis |
| KPI | Net revenue excluding cancelled order lines |
| Grain | One record per order line and business date |
| Required dimensions | Customer, product, country, order status |
| Freshness | Available every morning before business review |
| History | Customer and product attributes must be reproducible for the order date |
| Quality | Valid order, customer, product, country, amount, status and change timestamp |
| Consumers | Qlik, Power BI and controlled Excel access |
| Business owner | Sales Controlling |
| Technical owner | Data Platform team |
| Security | Sales detail restricted by approved access; aggregated views may be broader |
| Failure behavior | Failed mandatory checks are visible and prevent trusted publication where material |

This table is more important than the first pipeline definition. It determines what the pipeline must prove.

## Start with the target contract

The first target is not a dashboard. It is a governed data-product contract.

A practical contract for `sales_daily` could contain:

| Field | Meaning |
| --- | --- |
| `business_date` | Date used for daily sales analysis |
| `order_id` | Stable source order identifier |
| `order_line_id` | Stable line identifier within the order |
| `customer_key` | Governed warehouse customer key |
| `product_key` | Governed warehouse product key |
| `country_code` | Standardized country code |
| `order_status` | Standardized business status |
| `gross_revenue` | Source amount before cancellation logic |
| `net_revenue` | Governed amount used for the daily sales KPI |
| `source_changed_at` | Last relevant source change timestamp |
| `loaded_at` | Warehouse processing timestamp |
| `quality_status` | Publication status or quality indicator |

The contract should also document:

- the grain;
- allowed nulls;
- accepted domains;
- business filters;
- history behavior;
- refresh expectation;
- owner;
- security classification;
- compatibility expectations for consumers.

The target contract prevents Qlik, Power BI and Excel from receiving subtly different interpretations of the same business concept.

## Derive the target model before inspecting every source table

The fact and dimensions can now be defined at a logical level.

```text
fact_sales_order_line
dim_customer
dim_product
dim_country
dim_order_status
mart.sales_daily
```

The first slice does not need a complete Customer 360, a complete product domain or every possible sales measure. It needs only the attributes required to answer the approved question correctly.

For example:

- `dim_customer` requires the customer identity, country and the history relevant to the sale;
- `dim_product` requires the product identity and reporting attributes used in the first analysis;
- `fact_sales_order_line` requires order-line grain, dates, status and revenue fields;
- `mart.sales_daily` exposes the stable consumer contract.

Additional attributes are added only when a concrete use case requires them or when their inclusion is nearly cost-free and clearly governed.

## Identify the minimal required sources

The target contract determines the source scope.

| Requirement | Minimal source |
| --- | --- |
| Order and line identifiers | ERP order header and order line |
| Business date | ERP order or posting date |
| Customer assignment | ERP order customer reference |
| Customer attributes | CRM or customer master |
| Product assignment | ERP order line and product master |
| Country | Governed customer or reference mapping |
| Status and cancellations | ERP order status and cancellation indicator |
| Change detection | Reliable source change timestamp, CDC marker or extract comparison |
| Reference domains | Country and status reference data |

The source analysis should answer:

- Which system is authoritative for each field?
- Can changes be detected reliably?
- Are deletions represented?
- Is history available, or must it be created from future loads?
- Can records be extracted repeatedly without producing duplicates?
- Which keys connect the sources?
- Which fields contain sensitive data?
- What happens when a source arrives late?

Only the source objects and columns needed for the slice are included in the first ingestion scope.

## Build the smallest production-grade implementation

A minimal SQL-native implementation can use one existing database and one scheduler.

```flow linear vertical
ERP / CRM / Reference extracts
raw schema
stg schema
core schema
mart schema
quality schema
Qlik / Power BI / Excel
```

The logical responsibilities remain separate even when they share one database.

A practical naming structure could be:

```text
raw.erp_order_line
raw.crm_customer
raw.product_master
raw.country_reference

stg.erp_order_line
stg.crm_customer
stg.product_master

core.customer_history
core.product_history
core.sales_order_line

mart.sales_daily

quality.test_run
quality.test_result
```

This implementation already provides the essential architecture:

- traceable ingestion;
- standardized data types and domains;
- governed keys and history;
- one stable fact grain;
- one reusable data product;
- persistent quality results;
- controlled consumer access.

It does not require a lakehouse, a transformation framework or multiple compute engines unless the requirements justify them.

## Ingest only what the slice requires

The ingestion process should be repeatable, observable and safe to rerun.

For each source object, capture at least:

```text
source_system
source_object
source_record_key
source_changed_at
extract_batch_id
ingested_at
source_file_or_request_id
```

The first load may be full or incremental. The architecture should nevertheless define how later changes are handled.

A controlled incremental pattern can use:

- source CDC;
- a reliable `changed_at` timestamp;
- a monotonically increasing key;
- file manifests;
- checksums or snapshot comparison where no change marker exists.

The specific method depends on the source. The principle is stable:

> **Do not confuse incremental extraction with business history.**

A source change timestamp tells the pipeline which records changed. It does not automatically preserve the historically valid customer or product version required for reporting.

## Standardize before integrating

Standardization makes source data technically consistent.

Typical rules for the example include:

- trim and type order identifiers;
- normalize date and timestamp formats;
- map country values to an approved code;
- map source statuses to a controlled domain;
- validate mandatory keys;
- reject or quarantine unreadable amounts;
- preserve the original source values for traceability;
- attach validation results to the record or batch.

A simplified standardized order-line view could be:

```sql
select
    trim(cast(order_id as varchar(100))) as order_id,
    trim(cast(order_line_id as varchar(100))) as order_line_id,
    cast(order_date as date) as business_date,
    trim(cast(customer_id as varchar(100))) as customer_id,
    trim(cast(product_id as varchar(100))) as product_id,
    upper(trim(order_status)) as source_order_status,
    cast(gross_amount as decimal(18, 2)) as gross_revenue,
    cast(changed_at as timestamp) as source_changed_at,
    extract_batch_id,
    ingested_at
from raw.erp_order_line;
```

The exact syntax varies by platform. The placement of responsibility is what matters.

## Integrate keys, domains and history centrally

Integration translates source records into shared business meaning.

For the first slice, this includes:

- mapping source customer IDs to governed customer keys;
- mapping product IDs to governed product keys;
- selecting the customer and product version valid on the order date;
- mapping source statuses to the approved order-status domain;
- applying the shared cancellation rule;
- preserving unmatched or invalid records for quality analysis.

A simplified central mart transformation could be:

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
join core.customer_history c
  on o.customer_id = c.customer_id
 and o.business_date >= c.valid_from
 and o.business_date < coalesce(c.valid_to, date '9999-12-31')
join core.product_history p
  on o.product_id = p.product_id
 and o.business_date >= p.valid_from
 and o.business_date < coalesce(p.valid_to, date '9999-12-31')
join core.order_status s
  on o.source_order_status = s.source_order_status;
```

The rule is now independent from the consumer.

## Make quality results a first-class output

Quality testing should not be hidden in pipeline logs. It should produce persistent, queryable results.

A minimal quality-results model can record:

| Field | Purpose |
| --- | --- |
| `test_run_id` | Identifier of the complete validation run |
| `data_product` | Product being tested |
| `object_name` | Table or model under test |
| `rule_id` | Stable identifier of the quality rule |
| `severity` | Warning, error or blocking |
| `failed_count` | Number of failing records |
| `tested_count` | Number of evaluated records |
| `failure_rate` | Share of failed records |
| `executed_at` | Test timestamp |
| `status` | Passed, warning or failed |
| `sample_reference` | Optional reference to failed-record details |

Example rules include:

```text
DQ-SALES-001: order_id must not be null
DQ-SALES-002: order_line_id must be unique within an order
DQ-SALES-003: customer_key must resolve
DQ-SALES-004: product_key must resolve
DQ-SALES-005: country_code must be in the approved domain
DQ-SALES-006: source_changed_at must be present
DQ-SALES-007: net_revenue must follow the cancellation rule
DQ-SALES-008: the load must meet the agreed freshness target
```

A blocking rule can prevent publication of `mart.sales_daily`. A warning can publish the product with a visible status. That decision belongs in the product contract.

## Keep the consumers thin

Once the data product exists, each consumer should receive the same governed basis.

A deliberately thin Qlik load can be:

```qlik
SalesDaily:
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
    loaded_at,
    quality_status
FROM mart.sales_daily;
```

Qlik-specific associations, Section Access or presentation fields may remain in Qlik where they are genuinely required. Customer integration, status mapping, historical assignment and KPI definition should not be rebuilt there.

Power BI can use a semantic model over the same governed fact and dimensions. Presentation measures, formatting and report interactions remain consumer-specific, while the net-revenue basis and business grain remain shared.

Excel can use a governed reporting view, PivotTable connection or controlled extract. It should not receive an unmanaged raw export that bypasses the same definitions and security model.

## From the first use case to a platform pattern

The first data product is not only a deliverable. It is an architectural experiment that exposes which standards are actually necessary.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start4-img3-en.png"
        alt="Progression from the first business use case through validation and standardization to reusable components and a governed platform pattern"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The first productive slice validates the architecture. Proven choices for naming, orchestration, testing, security, deployment, monitoring and consumption are then reused rather than redesigned for every data product.
    </figcaption>
</figure>

The team should capture decisions as reusable patterns only after they have been exercised by the slice.

| Capability | Pattern derived from the first slice |
| --- | --- |
| Naming | Source, layer, object and field naming conventions |
| Ingestion | Batch metadata, rerun behavior, watermarking and failure handling |
| Orchestration | Dependency order, retries, timeouts and publication gates |
| Modeling | Fact grain, surrogate keys, history and reference-domain handling |
| Testing | Rule identifiers, severities, result tables and publication behavior |
| Security | Owner, classification, role mapping and consumer access |
| Deployment | Source control, environment configuration and release sequence |
| Monitoring | Load status, freshness, volume, duration and quality indicators |
| Consumption | Stable views, semantic contracts and BI-specific boundaries |
| Documentation | Owner, definition, lineage, SLA and change notes |

The second data product should reuse these patterns and challenge them where necessary. A pattern becomes a platform standard when several use cases prove that it is stable, understandable and economical.

The objective is not to freeze the first design forever. The objective is to evolve from evidence instead of speculation.

## Greenfield options by existing stack

The logical slice remains the same across platforms. The implementation should use the capabilities already available and add another component only when it solves a concrete problem.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start4-img4-en.png"
        alt="Comparison of Greenfield starting points using files and Excel, an On-Premises SQL database, a cloud data warehouse, a data lake or lakehouse, or an extremely lightweight start"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Greenfield does not imply one mandatory platform. A controlled file or SQL start can validate the use case; Fabric, Snowflake or Databricks become appropriate when their native capabilities match the existing stack and workload.
    </figcaption>
</figure>

The diagram shows broad starting positions. The same logical architecture can be mapped to four common production patterns.

### SQL-native

A SQL-native implementation is often the simplest production option for relational daily-batch workloads.

| Responsibility | Possible implementation |
| --- | --- |
| Ingestion | Existing ETL, scheduler, database jobs or controlled file loads |
| Standardization | SQL views, procedures or scheduled SQL models |
| Integration | Core schemas, key mappings and history tables |
| Mart | Curated fact, dimensions and reporting views |
| Tests | SQL assertions with persistent quality-result tables |
| Consumption | Qlik loads, Power BI semantic models and Excel views |
| Optional extension | dbt when dependencies, collaboration, testing or deployment justify it |

This can run on an On-Premises database or a managed cloud database. The architecture is defined by responsibilities, not by location.

### Microsoft Fabric-native

A Fabric implementation can keep the same vertical slice inside an integrated Microsoft environment.

A possible allocation is:

- Data Factory pipelines, Copy activities or Dataflows for ingestion;
- a Lakehouse or Warehouse for managed analytical storage;
- SQL, notebooks or dataflows for standardization and integration;
- curated tables for the Sales Daily data product;
- persistent quality-result tables;
- Power BI semantic models for native consumption;
- Qlik or Excel consuming governed outputs through supported SQL or data interfaces;
- deployment and monitoring capabilities applied as the platform pattern matures.

Fabric is not required for the use case. It is appropriate when the organization already uses the Microsoft ecosystem and the integrated platform reduces handoffs and operations.

dbt remains optional. It is useful only if the selected Fabric workflow and team model benefit from dbt-style modular SQL development, tests and deployment practices.

### Snowflake-native

A Snowflake implementation can use:

- batch or continuous loading through the available ingestion mechanism;
- Raw and standardized tables in separate schemas;
- SQL transformations, scheduled tasks or dynamic-table patterns where appropriate;
- dbt as an optional transformation and testing framework;
- integrated facts, dimensions and the `sales_daily` mart;
- quality-result tables and publication checks;
- governed views for Qlik, Power BI and Excel.

Snowflake becomes the logical foundation when a managed cloud data warehouse is already the strategic platform or when its operational model solves a concrete requirement. It should not be introduced merely because the project is Greenfield.

### Databricks-native

A Databricks implementation can use:

- batch ingestion or Auto Loader for files arriving in cloud object storage;
- Delta tables for controlled data states;
- SQL, Spark or managed pipeline capabilities for transformation;
- workflows for orchestration;
- quality rules and persistent failure results;
- curated Delta tables or views for the Sales Daily product;
- governed SQL interfaces for Qlik, Power BI and Excel;
- dbt as an optional SQL-development path.

Databricks is especially relevant when the use case is expected to grow into high-volume, varied-data, streaming, data-science or ML workloads. A small relational daily batch does not require distributed processing merely because the platform is available.

### Files, Excel or nothing yet

A very small controlled prototype can begin with files, Excel and Power Query when the purpose is to validate the business question and target contract.

This start is valid only when:

- the scope is very small;
- access is controlled;
- the source can be reproduced;
- transformations are documented;
- the result is clearly identified as a validation stage;
- migration to a production pattern is planned before the product becomes business-critical.

The prototype must not silently become the permanent enterprise warehouse.

## How dbt fits without becoming mandatory

dbt can be added to SQL-based transformations when it improves the development operating model.

It is most useful when:

- many models depend on one another;
- several developers collaborate;
- pull requests and code review are required;
- tests should be declared with the models;
- documentation and lineage need a consistent structure;
- environment deployment should be repeatable.

It may add little value when one developer maintains a handful of clear SQL transformations in an existing controlled deployment process.

The decision is therefore not:

```text
Is dbt a modern best practice?
```

It is:

```text
Does dbt solve a collaboration, dependency, testing or deployment problem in this implementation?
```

## Typical anti-patterns

### Loading every source before one product is defined

This creates storage and pipeline obligations without agreed business value, quality or ownership.

### Designing the enterprise canonical model in the first sprint

A complete enterprise model cannot be validated by one team before concrete use cases exercise its assumptions. Start with reusable concepts required by the first slice and expand deliberately.

### Treating the first slice as disposable

A proof of concept with hard-coded paths, manual steps and hidden logic does not prove that the architecture can operate. The slice should be minimal but production-oriented.

### Building the KPI in the dashboard

When net revenue is defined independently in Qlik, Power BI and Excel, the first data product already contains three competing contracts.

### Using Qlik as the hidden warehouse

Qlik can perform sophisticated transformation and association logic. Shared integration, history, status mapping and KPI bases should still be moved upstream when multiple products or consumers need them.

### Creating Bronze, Silver and Gold without explicit responsibilities

Layer names do not define ownership, quality, history or consumer contracts. The first slice must make those responsibilities visible.

### Adding Fabric, Snowflake, Databricks and dbt at the same time

Each product can be useful. Introducing all of them before the workload proves a need creates several operating models, security boundaries and failure points.

### Standardizing every future use case from the first one

The first slice should create candidate standards. A pattern becomes mandatory only after reuse proves that it remains appropriate.

### Ignoring operations until the second product

Reruns, monitoring, failed records, deployment and support are part of the first production slice. They are not post-platform work.

### Copying data into each BI tool

Uncontrolled QVDs, semantic-model copies and Excel exports can fragment the contract. Consumer-specific delivery is valid, but it must remain traceable to the governed product.

## Decision guide

| Situation | Recommended Greenfield response |
| --- | --- |
| One clear business question and relational sources | Build one SQL-oriented vertical slice with explicit schemas and tests |
| Existing Microsoft analytics estate | Use Fabric-native capabilities where they reduce integration and operating effort |
| Existing Snowflake platform | Build the same logical layers and product contract in Snowflake; add dbt only if useful |
| Existing Databricks platform or distributed workload | Use Delta and Databricks-native processing; avoid Spark complexity for trivial transformations |
| Only files or spreadsheets are available | Validate the target contract with a controlled small prototype, then establish a production path |
| Several BI tools need the result | Publish one governed data product with separate, thin consumer contracts |
| Source history is unavailable | Begin capturing changes immediately and document the historical limitation |
| Quality failures must be audited | Persist rule-level and record-level results outside transient pipeline logs |
| The second use case is similar | Reuse the first patterns before introducing new components |
| The second use case challenges the pattern | Revise the standard explicitly rather than creating a hidden exception |
| Team collaboration becomes difficult | Add versioned modular transformation tooling such as dbt where it solves the problem |
| Future scale is uncertain | Preserve clear boundaries and observability; scale the physical architecture when measured demand requires it |

## Most important recommendations

1. Begin with one business decision, not with the platform inventory.
2. Define the KPI, grain, owner, quality and consumer contract before ingestion.
3. Design backward from the target data product and build forward from the required sources.
4. Load only the source objects and fields required for the first slice.
5. Preserve traceable Raw data and distinguish extraction changes from business history.
6. Standardize technical formats before applying shared business integration.
7. Resolve keys, history, domains and shared KPI logic centrally.
8. Persist quality results and make publication behavior explicit.
9. Keep Qlik, Power BI and Excel thin consumers of the same governed product.
10. Use the existing SQL, Fabric, Snowflake or Databricks capabilities before adding another platform.
11. Treat dbt as an optional development framework, not as a warehouse prerequisite.
12. Make the first slice minimal in scope but production-grade in operation.
13. Convert proven decisions into reusable naming, orchestration, testing, security, deployment and monitoring patterns.
14. Validate standards through the second and third data products before treating them as universal.
15. Expand the warehouse one governed vertical slice at a time.

## Transition to the next part

A Greenfield warehouse can grow from one vertical slice into a reusable platform pattern. Existing warehouses require a different entry point because sources, models, reports and duplicated business logic already exist.

The next part, [Modernizing an Existing Warehouse](/playbooks/modernizing-an-existing-warehouse), applies the same architecture principles to a Brownfield environment without requiring a full rebuild.
