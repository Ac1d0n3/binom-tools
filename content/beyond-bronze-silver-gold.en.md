---
title: Beyond Bronze, Silver and Gold
description: Why Bronze, Silver and Gold are useful technical labels but insufficient for a complete warehouse architecture — and how precise layers separate raw ingestion, standardization, integration, governed data products and consumption contracts.
category: Data Architecture
tags:
  - data-architecture
  - modern-data-warehouse
  - medallion-architecture
  - bronze-silver-gold
  - data-products
  - semantic-models
  - qlik-sense
  - power-bi
  - excel
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 2
seriesTitle: Building a Modern Data Warehouse
hero: images/playbooks/bp-start2-hero.png
---

## A useful pattern is not yet a complete architecture

Bronze, Silver and Gold have become common labels for organizing analytical data processing.

The pattern is useful because it communicates a progression:

```flowchart
Bronze
Silver
Gold
```

Bronze usually represents data close to the source. Silver usually represents cleaned or transformed data. Gold usually represents data prepared for business use.

That is a practical starting point, but it is too broad to answer the architectural questions that determine whether a warehouse remains understandable, reusable and governable.

The label **Gold** may contain several fundamentally different concerns:

- integrated enterprise data;
- historized business entities;
- conformed facts and dimensions;
- KPI-ready datasets;
- subject-specific marts;
- Qlik-optimized views;
- Power BI semantic models;
- Excel reporting views;
- extracts created for one report or application.

These objects do not have the same purpose, grain, ownership, lifecycle or reuse potential. Placing all of them in one broad Gold layer hides decisions that should be explicit.

> **Bronze, Silver and Gold are useful technical categories. They are not a sufficient logical architecture for a real warehouse.**

Part 1 of this series, [Before Building the First Table](/playbooks/before-building-the-first-table), established that the architecture must begin with a business decision, KPI, grain, sources, quality requirements and ownership. This part translates those decisions into a more precise source-to-consumption lifecycle.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start2-img1-en.png"
        alt="Comparison between the simplified Bronze, Silver and Gold pattern and a more precise warehouse architecture with Raw, Standardized, Integrated Core, Data Products and Semantic or Consumption layers"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The Medallion pattern remains useful, but the Gold label often combines integration, dimensional modeling, KPI preparation and tool-specific consumption. More precise logical layers make these responsibilities visible.
    </figcaption>
</figure>

## Architecture principle: separate responsibilities, not tools

A logical layer should describe a stable responsibility in the data lifecycle. It should not merely repeat the name of a product, engine or storage technology.

A practical warehouse lifecycle can be expressed as:

```flowchart
Source Systems
Landing / Raw
Standardized / Validated
Integrated Core
Business Data Products / Marts
Consumption Contracts / Semantic Models
Reports, Apps and Analytics
```

The boundaries matter because each layer answers a different question.

| Layer | Primary question |
| --- | --- |
| Source Systems | Where was the operational event or master data created? |
| Landing / Raw | What exactly did the source provide, and when was it received? |
| Standardized / Validated | Can the data be processed consistently and is it technically plausible? |
| Integrated Core | Which business entity, relationship and historical version does the record represent? |
| Business Data Products / Marts | Which governed facts, dimensions and KPI bases are delivered for a defined business purpose? |
| Consumption Contracts / Semantic Models | How may a specific tool or consumer access and interpret the governed product? |

This structure is logical, not prescriptive. A small implementation may realize several responsibilities in one database. A large implementation may use separate schemas, storage zones, compute engines or deployment pipelines.

The objective is not to create the maximum number of physical layers. The objective is to make the responsibilities explicit enough that logic is placed deliberately.

## The complete warehouse lifecycle

A modern warehouse is not only a sequence of storage areas. Quality, governance, security, lineage, observability and delivery practices apply across the full lifecycle.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start2-img2-en.png"
        alt="Complete warehouse lifecycle from source systems through Landing or Raw, Standardized or Validated, Integrated Core, Business Data Products or Marts and Consumption Contracts or Semantic Models, with Data Quality, Governance, Security, Lineage, Observability and CI/CD as cross-cutting functions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The layers separate ingestion, technical standardization, business integration, governed products and consumer-specific delivery. Cross-cutting controls operate across every layer rather than being added only at the end.
    </figcaption>
</figure>

### Source Systems

Source systems remain responsible for operational processes such as customer maintenance, order entry, invoicing, product management or event generation.

They should not be treated as warehouse layers. They are the systems from which the analytical lifecycle receives data.

Typical sources include:

- ERP and CRM systems;
- operational databases;
- files and spreadsheets;
- APIs;
- event streams;
- external reference data;
- manually maintained classifications.

The warehouse should document the system of record, extraction mechanism, source timestamp, source key and expected delivery behavior.

### Landing / Raw

The Landing or Raw layer preserves what was received from the source with as little semantic change as practical.

Typical responsibilities include:

- unchanged or minimally changed ingestion;
- source and load timestamps;
- batch, file or event identifiers;
- source metadata;
- traceability to the received payload;
- controlled retention or replay capability;
- technical quarantine of unreadable input.

This layer is not the place for a Golden Customer, a net-revenue KPI or a business hierarchy. Its purpose is evidence and recoverability.

A Raw layer does not always require a data lake. It may be implemented with database tables, files, object storage, Delta tables or another controlled landing mechanism.

### Standardized / Validated

The Standardized or Validated layer converts source-specific structures into technically consistent data.

Typical responsibilities include:

- data-type conversion;
- date, number and encoding normalization;
- column naming and structural harmonization;
- mandatory-field checks;
- domain and reference validation;
- country or currency code standardization;
- duplicate-candidate detection;
- validation flags and rejection reasons.

This layer answers whether data can be processed consistently. It does not yet decide that two source records represent the same real customer or which historical customer version applied to a sale.

### Integrated Core

The Integrated Core represents shared business entities and relationships across sources.

Typical responsibilities include:

- enterprise business keys;
- source-to-enterprise key mapping;
- Golden Records;
- master-data consolidation;
- historization and Slowly Changing Dimensions;
- valid-from and valid-to relationships;
- conformed customer, product and organizational structures;
- integrated facts at stable grains;
- reusable rules that define shared business meaning.

The Integrated Core is where a CRM customer and an ERP customer can be resolved into one governed customer identity. It is also where historical assignments should be preserved when reporting requires an as-was view.

This layer should remain independent from a single dashboard. Its objects are reusable building blocks.

### Business Data Products / Marts

A business data product packages governed data for a defined business purpose.

Typical responsibilities include:

- facts and dimensions for a subject area;
- explicit grain;
- KPI-base columns;
- curated attributes;
- documented filters and exclusions;
- quality status;
- business owner and technical owner;
- freshness and service expectations;
- stable interfaces for downstream consumers.

Examples include:

- Daily Sales;
- Customer 360;
- Product Profitability;
- Contract Portfolio;
- Data Quality Results.

A data product is not merely a schema name. It combines data, semantics, ownership, quality and a defined consumer contract.

### Consumption Contracts / Semantic Models

The Consumption layer adapts governed data products to the access patterns of specific tools and user groups.

Typical responsibilities include:

- Qlik-optimized presentation views or governed QVD extracts;
- Power BI semantic models and presentation measures;
- Excel reporting views;
- governed column names and descriptions;
- row-level or object-level access where required;
- stable interfaces and compatibility commitments;
- last-mile calculations that are genuinely consumer-specific.

The central KPI meaning should already exist upstream. A semantic model may add formatting, calculation groups, display hierarchies or interaction-specific measures, but it should not silently redefine the enterprise KPI.

> **Shared meaning belongs as far left as practical. Tool-specific behavior belongs as far right as necessary.**

## What belongs in each layer?

The assignment becomes clearer when one customer and sales example is followed end to end.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start2-img3-en.png"
        alt="Layer-by-layer assignment of source capture, unchanged ingestion, standardization, duplicate checks, Golden Customer, SCD history, facts and dimensions, KPI bases and tool-specific Qlik, Power BI and Excel consumption objects"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Each layer has a distinct responsibility. Customer identity, history and shared KPI logic are resolved centrally; Qlik, Power BI and Excel receive consumer-specific interfaces only after the governed data product exists.
    </figcaption>
</figure>

## Concrete customer and sales example

Assume the organization receives customer data from a CRM system and sales orders from an ERP system.

The business needs to analyze:

> Net revenue by customer, country and month, using the customer attributes that were valid when the order occurred.

The required logic includes:

- a valid Customer ID;
- standardized countries;
- duplicate detection;
- one Golden Customer;
- historical customer attributes;
- a sales fact at order-line grain;
- a centrally defined net-revenue basis;
- Qlik, Power BI and Excel as possible consumers.

### Source Systems

The CRM provides customer records:

| crm_customer_id | name | country | segment | changed_at |
| --- | --- | --- | --- | --- |
| C-100 | Alpha Systems | DE | SMB | 2026-01-03 10:15 |
| C-101 | Alpha Systems GmbH | Germany | SMB | 2026-01-04 08:10 |
| C-200 | Beta Retail | NL | Enterprise | 2026-01-02 15:30 |

The ERP provides order lines:

| order_id | line_id | order_date | erp_customer_id | amount | cancellation_flag |
| --- | ---: | --- | --- | ---: | ---: |
| SO-1001 | 10 | 2026-01-12 | C-100 | 8000 | 0 |
| SO-1002 | 10 | 2026-02-08 | C-200 | 5000 | 0 |
| SO-1003 | 10 | 2026-03-18 | C-100 | 6000 | 1 |

No warehouse logic should be added to the source systems merely to make one report work.

### Landing / Raw

The received rows are stored with technical metadata:

```text
source_system
source_object
source_file_or_batch_id
ingested_at
source_changed_at
raw_payload_or_source_columns
```

The country values remain exactly as received: `DE`, `Germany`, `NL`.

The two Alpha Systems records also remain separate. Raw preserves evidence; it does not decide whether they are duplicates.

### Standardized / Validated

The standardized customer model may:

- cast `crm_customer_id` to a consistent text type;
- trim and normalize names;
- map `DE` and `Germany` to ISO country code `DE`;
- validate that Customer ID is present;
- create a duplicate-candidate key;
- assign validation flags.

A simplified SQL view could look like this:

```sql
select
    trim(cast(crm_customer_id as varchar(50))) as customer_id,
    trim(customer_name) as customer_name,
    case
        when upper(trim(country)) in ('DE', 'GERMANY', 'DEUTSCHLAND') then 'DE'
        when upper(trim(country)) in ('NL', 'NETHERLANDS', 'NIEDERLANDE') then 'NL'
        else null
    end as country_code,
    trim(segment) as segment,
    changed_at,
    case
        when crm_customer_id is null then 'INVALID_CUSTOMER_ID'
        when country is null then 'INVALID_COUNTRY'
        else 'VALID'
    end as validation_status
from raw_crm_customer;
```

The exact SQL syntax may differ by platform. The architectural point is that technical standardization is reusable and visible.

### Integrated Core

The Integrated Core resolves identity and history.

For example:

| enterprise_customer_id | source_customer_id | source_system | valid_from | valid_to |
| --- | --- | --- | --- | --- |
| EC-001 | C-100 | CRM | 2026-01-01 | 9999-12-31 |
| EC-001 | C-101 | CRM | 2026-01-01 | 9999-12-31 |
| EC-002 | C-200 | CRM | 2026-01-01 | 9999-12-31 |

`C-100` and `C-101` are linked to the same Golden Customer `EC-001` after a governed matching decision.

The historical customer dimension may contain:

| customer_sk | enterprise_customer_id | country_code | segment | valid_from | valid_to |
| ---: | --- | --- | --- | --- | --- |
| 1001 | EC-001 | DE | SMB | 2026-01-01 | 2026-04-01 |
| 1002 | EC-001 | DE | Enterprise | 2026-04-01 | 9999-12-31 |
| 2001 | EC-002 | NL | Enterprise | 2026-01-01 | 9999-12-31 |

This is where the historical relationship becomes explicit. A sale on 2026-03-18 uses customer version `1001`; a later sale after 2026-04-01 uses `1002`.

### Business Data Product / Mart

The Sales data product exposes one row per order line with governed keys and KPI bases.

```sql
select
    s.order_id,
    s.line_id,
    s.order_date,
    c.customer_sk,
    c.enterprise_customer_id,
    c.country_code,
    c.segment,
    s.amount as gross_revenue,
    s.cancellation_flag,
    case
        when s.cancellation_flag = 1 then 0
        else s.amount
    end as net_revenue,
    s.quality_status
from standardized_sales_line s
join customer_key_map m
  on m.source_system = s.source_system
 and m.source_customer_id = s.customer_id
join dim_customer_history c
  on c.enterprise_customer_id = m.enterprise_customer_id
 and s.order_date >= c.valid_from
 and s.order_date < c.valid_to;
```

The data product defines:

```text
Grain: one ERP order line
Business owner: Sales Controlling
Data owner: Customer and Sales domain
Refresh: daily before 07:00
Mandatory quality: valid order, customer, country, amount and change timestamp
Consumers: Qlik, Power BI, Excel and approved operational extracts
```

The `net_revenue` basis is therefore not recreated independently in every BI application.

### Consumption Contracts

The consumer-specific layer may expose:

- a Qlik view or governed QVD with stable field names and optimized associations;
- a Power BI semantic model with relationships, formatting and presentation measures;
- an Excel view with business-friendly columns and restricted detail.

A deliberately thin Qlik load could be:

```qlik
Sales:
LOAD
    order_id,
    line_id,
    order_date,
    customer_sk,
    enterprise_customer_id,
    country_code,
    segment,
    gross_revenue,
    cancellation_flag,
    net_revenue,
    quality_status
FROM [lib://GovernedData/sales_data_product.qvd] (qvd);
```

Qlik may still contain Qlik-specific logic where required, such as application-specific associations, Section Access integration or genuinely interactive calculations. It should not become the hidden location of shared customer matching, history or KPI definitions.

A Power BI semantic model can use the same governed fact and dimensions. Excel can use a controlled reporting view. Different consumers do not require different business truths.

## The simplest viable implementation

A precise logical architecture does not require six separate technologies or databases.

For a small team with an existing SQL database, the entire lifecycle may be implemented with schemas and views:

```text
source references
raw tables
standardized views
core tables
mart tables or views
consumer views
```

A minimal physical structure could be:

```text
raw.crm_customer
raw.erp_sales_line

standardized.customer
standardized.sales_line

core.customer_key_map
core.dim_customer_history

mart.fact_sales
mart.dim_customer

consumption.qlik_sales
consumption.powerbi_sales
consumption.excel_sales
```

The same database can host these objects. Separate schemas and naming conventions may be enough to preserve the logical boundaries.

The minimum also requires cross-cutting controls:

- load and transformation logging;
- a small set of automated quality checks;
- ownership metadata;
- access control;
- version-controlled scripts;
- lineage documentation sufficient to trace a KPI back to its sources.

This architecture is modern because responsibilities are explicit and reusable, not because it contains a particular cloud product.

## Alternative implementations by existing platform

The logical architecture remains stable while its physical implementation changes.

| Logical responsibility | Classical SQL / On-Premises | Microsoft Fabric | Snowflake | Databricks |
| --- | --- | --- | --- | --- |
| Landing / Raw | Staging tables, files, existing ETL landing | Existing ingestion and storage capabilities | Raw schemas and ingestion processes | Raw tables or files in the established lakehouse |
| Standardized / Validated | SQL views, procedures, ETL mappings | SQL or notebook transformations already used by the team | SQL transformations, tasks or existing transformation framework | SQL or Spark transformations |
| Integrated Core | Core warehouse tables, history tables | Governed warehouse or lakehouse structures | Core schemas and historized models | Governed Delta-based core models |
| Data Products / Marts | Star schemas, curated views | Curated warehouse or lakehouse products | Product schemas, marts and secure views | Curated tables, marts and serving views |
| Consumption | Qlik views/QVDs, Power BI models, Excel views | Power BI, Qlik or Excel over governed outputs | BI semantic models and presentation views | SQL-serving or governed extracts for BI tools |

These are examples, not mandatory stack designs.

### Classical SQL warehouse or On-Premises

Use existing relational tables, views, procedures, schedulers and ETL tools when they satisfy the requirements. Logical separation through schemas, naming and deployment conventions may be sufficient.

A platform migration is not required merely to move beyond Bronze, Silver and Gold.

### Microsoft Fabric

When Fabric is already the selected environment, map the same responsibilities onto the available storage, transformation and serving components. Do not assume that every source needs a large three-zone implementation before one business data product can be delivered.

Qlik, Power BI and Excel may consume the same curated product where this is operationally appropriate.

### Snowflake

When Snowflake already exists, use schemas, tables, views and the existing orchestration or transformation approach to preserve the logical boundaries. dbt may be added when modular SQL models, testing, documentation and dependency management solve a concrete team problem. It is not required merely because Snowflake is present.

### Databricks

When Databricks is already established, the Medallion terminology may remain useful for technical processing. The logical architecture should still distinguish standardization, enterprise integration, business data products and semantic consumption rather than treating all curated objects as one undifferentiated Gold zone.

### dbt

dbt is a possible transformation-management layer across suitable SQL platforms. It can make model dependencies, tests, documentation and deployment practices more explicit.

It does not replace the architectural decisions. A dbt project can still mix raw cleanup, identity resolution, KPI logic and report-specific models unless the logical boundaries are deliberately designed.

### Qlik as the strongest existing capability

A Brownfield environment may currently contain substantial transformation logic in Qlik scripts and QVD layers.

The practical target is not necessarily an immediate rewrite. A controlled migration can:

1. document the current Qlik logic;
2. identify shared business rules;
3. move reusable standardization, integration and KPI logic into SQL or another shared transformation layer;
4. publish governed QVDs or views;
5. retain only Qlik-specific logic in the application;
6. retire duplicated script logic after reconciliation.

The layers describe where logic should converge. They do not require a disruptive one-time replacement.

## Cross-cutting functions are not another final layer

Data Quality, Governance, Security, Lineage, Observability and CI/CD must not be treated as activities that begin only after Gold data exists.

### Data Quality

Quality rules appear at different layers:

- Raw: file received, row count, schema readable;
- Standardized: required fields, data types, accepted domains;
- Integrated Core: key mapping, duplicate resolution, temporal consistency;
- Data Product: grain, referential integrity, KPI plausibility, completeness;
- Consumption: contract compatibility and consumer-specific validation.

### Governance

Governance defines:

- business and technical ownership;
- approved definitions;
- data classification;
- change approval;
- retention;
- service expectations;
- deprecation and retirement.

### Security

Security must follow the data through the lifecycle. Raw personal data may require stricter access than aggregated data products. Consumer models must not become uncontrolled copies that bypass the governed access path.

### Lineage

Lineage should connect:

```text
Source field
→ Raw field
→ Standardized field
→ Integrated business object
→ Data product field or KPI basis
→ Semantic measure or report
```

The objective is impact analysis and accountability, not merely a diagram of table dependencies.

### Observability

Observability covers:

- pipeline execution;
- freshness;
- volume anomalies;
- schema changes;
- quality failures;
- service-level breaches;
- consumer-impacting incidents.

### CI/CD

Versioning and deployment practices apply to:

- ingestion definitions;
- SQL, notebooks or transformation models;
- quality rules;
- schema changes;
- semantic models;
- Qlik load scripts where they remain necessary;
- documentation and contracts.

## Typical anti-patterns

### Renaming zones without clarifying responsibilities

Changing `Bronze`, `Silver` and `Gold` to `Raw`, `Core` and `Mart` does not improve the architecture when the same mixed logic remains hidden inside them.

### Treating Gold as everything used by the business

This combines reusable enterprise structures, subject marts, KPI logic and tool-specific models. Ownership and change impact become unclear.

### Creating a physical platform layer for every logical responsibility

A logical boundary does not always require a separate database, engine or tool. Excessive physical separation increases operational complexity without automatically improving governance.

### Performing identity resolution in each report

Customer matching and Golden Record logic should not be implemented independently in Qlik, Power BI and Excel. The result would differ by consumer and be difficult to audit.

### Rebuilding KPI logic in semantic models

Semantic models may contain presentation-specific measures, but the shared KPI basis should be governed upstream. Otherwise the same KPI becomes dependent on the report technology.

### Cleaning Raw data until the source evidence disappears

If Raw silently overwrites values, source traceability and replay become unreliable. Standardization should produce a new controlled representation rather than destroying the received one.

### Assuming one layer equals one storage format

Logical architecture and physical storage are different decisions. Raw may be files or tables. A data product may be a table, view or governed extract. The correct form depends on requirements.

### Introducing another platform to make the diagram look modern

Fabric, Snowflake, Databricks and dbt can each solve real problems. None is automatically required to create precise layers. Add a component only when it improves scale, collaboration, testing, governance, performance or operations enough to justify its cost and complexity.

## Decision guide: how many physical layers are necessary?

Use the logical model as a checklist, then collapse physical layers where that remains clear and safe.

| Situation | Practical implementation |
| --- | --- |
| Small team, one SQL database, daily batch | Separate schemas or naming conventions in one database |
| Several consumers and reusable business entities | Distinct core and data-product schemas with controlled interfaces |
| High audit or replay requirements | Durable Raw storage plus explicit transformation outputs |
| Multiple source systems with identity and history | Dedicated Integrated Core models |
| Several BI tools | Shared data products plus separate consumption contracts where necessary |
| Complex transformation dependencies and team-based development | Add modular transformation management such as dbt if it solves the collaboration problem |
| Existing lakehouse at scale | Keep technical zones, but define logical core, product and consumption responsibilities inside or above them |
| Stable On-Premises warehouse | Modernize modeling, testing, versioning and contracts without forcing a cloud migration |

A layer is justified when it creates a meaningful boundary for responsibility, reuse, control, lifecycle or performance. It is not justified merely because a reference architecture contains another box.

## Most important recommendations

1. Keep Bronze, Silver and Gold as a technical shorthand where useful, but do not let them replace a logical warehouse architecture.
2. Separate unchanged ingestion, technical standardization, business integration, data products and consumer-specific delivery.
3. Preserve Raw as traceable source evidence.
4. Resolve business keys, Golden Records and history centrally.
5. Define facts, dimensions and KPI bases in governed data products.
6. Keep shared business logic outside individual Qlik apps, Power BI reports and Excel workbooks.
7. Allow consumer-specific logic only where the consumer genuinely requires it.
8. Apply quality, governance, security, lineage, observability and CI/CD across all layers.
9. Implement the logical boundaries with the tools already available before adding another platform.
10. Use the smallest physical architecture that keeps responsibilities explicit and maintainable.

## Transition to the next part

Precise layers clarify what must happen between a source and a trusted data product. They still do not determine how many tools, engines or physical platforms are necessary.

The next part, [Choosing the Simplest Viable Architecture](/playbooks/choosing-the-simplest-viable-architecture), turns requirements such as volume, freshness, transformation complexity, team size, governance and consumer needs into the simplest architecture that can work.
