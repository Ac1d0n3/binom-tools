---
title: "Before Building the First Table"
description: "Start with decisions, not with tools"
author: "Thomas Lindackers"
category: Data Architecture
tags:
  - building-modern-data-warehouse
  - data-warehouse
  - data-product
  - data-architecture
  - governance
order: -1
publishedAt: 2026-07-18
series: building-modern-data-warehouse
seriesPart: 1
seriesTitle: Building a Modern Data Warehouse
hero: "images/playbooks/bp-start-hero.png"
---

**Start with decisions, not with tools.**

A modern data warehouse should not begin with a platform selection, a layer name, or the first ingestion pipeline. It should begin with a decision that the business needs to make.

That distinction sounds simple, but it changes the entire architecture. When a project starts with a tool, teams tend to load broadly, create technical layers early, and postpone KPI definitions, ownership, quality rules, and security decisions. The result may contain many tables and pipelines while still failing to provide one trusted answer.

A better starting point is a narrowly defined business question and the smallest governed data product that can answer it end to end.

This principle applies to Greenfield and Brownfield environments, to cloud and on-premises platforms, and to small teams as well as larger enterprise programs. Microsoft Fabric, Snowflake, Databricks, dbt, SQL Server, Qlik, Power BI, and Excel are possible implementation components. None of them is the architectural starting point.

## The problem: architecture before clarity

Typical warehouse initiatives begin with questions such as:

- Which cloud platform should we use?
- Do we need Bronze, Silver, and Gold?
- Which ingestion tool should load the source systems?
- Should we use a lakehouse, warehouse, or both?
- Which BI tool should become the standard?

These are valid questions, but they are not the first questions.

Before a platform can be evaluated, the team must understand what the solution is expected to deliver. Otherwise, the architecture is optimized for an undefined problem. This often creates predictable consequences:

- too many sources are loaded before their purpose is known;
- pipelines are built for data that no consumer needs;
- business logic is duplicated in Qlik scripts, Power BI models, Excel files, SQL views, and notebooks;
- similar tables are created for different reports;
- data quality is checked only after users find errors;
- nobody is clearly accountable for the KPI or the data product;
- the platform becomes broader while trust remains low.

![Do Not Start with the Platform](images/playbooks/bp-start-img1-en.png)

## Architecture principle: decisions first, implementation last

The architecture sequence should be:

1. Define the business decision.
2. Define the KPI and its business meaning.
3. Define the grain.
4. Identify only the required sources and fields.
5. Define freshness and history requirements.
6. Define quality, security, and ownership.
7. Build the smallest complete data product.
8. Select or extend the implementation according to the actual need.

The important point is not that technology is unimportant. Technology is selected later because its suitability can only be judged against explicit requirements.

A daily sales steering product may require one daily refresh, moderate data volumes, and a governed SQL table. A near-real-time operational use case may require streaming, event processing, and different service levels. The architecture follows the decision context.

## Start with one vertical data product

The first useful delivery should be vertical rather than broad.

A vertical data product connects one business question to all technical steps required to answer it:

```flowchart
Business question
Required sources
Ingestion
Standardization
Business logic
Quality tests
Governed data product
Qlik / Power BI / Excel
```

This is more valuable than building a wide but unfinished platform. The vertical path proves that the team can move from source to decision, including ownership, quality, and consumption.

![Start with One Vertical Data Product](images/playbooks/bp-start-img2-en.png)

The objective is not to design the smallest possible technical object. The objective is to define the smallest useful, trustworthy, and reusable scope.

A minimum viable data product should therefore be:

- focused on one concrete decision;
- based only on the required data;
- governed from the beginning;
- reusable by more than one consumer where practical;
- independent from a single BI application;
- extendable when a real requirement appears.

## Concrete example: daily sales steering

Assume the business wants to answer this question:

> How are daily net sales developing by customer, product, and country, including cancellations and late changes?

Before creating the first table, the following decisions must be made.

| Decision area | Required clarification | Example decision |
|---|---|---|
| Business question | Which decision should the result support? | Daily sales steering and identification of negative deviations |
| KPI definition | What exactly counts as revenue? | Net order-line revenue after cancellations |
| Grain | What does one row represent? | One order line per business date |
| Source systems | Which systems contain the required data? | Orders, customers, products, country reference data |
| Freshness | When must the data be available? | Daily by 07:00 |
| History | Do changes have to be reconstructed? | Preserve valid changes to order lines and master data |
| Data quality | Which rules are mandatory? | Valid order ID, customer ID, product ID, country, amount, and change date |
| Security | Which data requires restriction? | Customer attributes and commercially sensitive values |
| Owner | Who approves meaning and changes? | Sales owner for the KPI, data owner for the data product |
| Consumers | Which tools use the result? | Qlik, Power BI, Excel, and operational reports |

These decisions determine the technical model.

A practical first data product could contain:

| Field | Purpose |
|---|---|
| `business_date` | Reporting and refresh date |
| `order_id` | Business key of the order |
| `order_line_id` | Grain-defining line identifier |
| `customer_id` | Governed customer reference |
| `product_id` | Governed product reference |
| `country_code` | Standardized country |
| `gross_revenue` | Revenue before cancellation logic |
| `cancellation_flag` | Identifies cancelled order lines |
| `net_revenue` | Centrally defined KPI contribution |
| `changed_at` | Source change timestamp |
| `quality_status` | Result of mandatory quality checks |

The central business rule could be implemented once in SQL:

```sql
select
    cast(order_date as date) as business_date,
    order_id,
    order_line_id,
    customer_id,
    product_id,
    country_code,
    gross_revenue,
    cancellation_flag,
    case
        when cancellation_flag = 1 then 0
        else gross_revenue
    end as net_revenue,
    changed_at
from standardized_order_lines;
```

The exact syntax is not the architectural point. The relevant design decision is that the net-revenue rule is defined centrally and reused by all consumers.

## Where the logic should live

Not every transformation belongs in the same layer. The following allocation keeps the solution understandable.

| Logic type | Preferred location | Reason |
|---|---|---|
| Source extraction and technical ingestion | Ingestion process | Keeps source access and load behavior controlled |
| Key normalization, data types, formats | Standardization layer | Makes technical structures reusable |
| KPI logic, cancellation rules, country assignment | Central SQL, transformation model, or governed semantic layer | Prevents conflicting definitions |
| Quality checks | Close to the transformation and data product | Detects failures before consumption |
| Access rules | Governed platform or semantic access layer | Applies restrictions consistently |
| Qlik-specific associations or calculations | Qlik only when analytically necessary | Keeps Qlik scripts thin |
| Power BI-specific presentation measures | Power BI only when truly presentation-specific | Avoids duplicating core KPI logic |
| Excel-specific formatting | Excel | Keeps presentation separate from data logic |

The core rule is simple: logic that defines the meaning of shared data should not be hidden inside one report or one application.

## The simplest viable implementation

A modern architecture does not require a large toolchain.

For a small or early-stage use case, a viable setup may consist of:

- an existing SQL database;
- a scheduled load or existing ETL process;
- one standardized source table or view;
- one curated sales data product;
- a small set of automated quality checks;
- Qlik, Power BI, or Excel consuming the same governed result.

For example:

```flow linear vertical
ERP and reference data
SQL staging tables
Standardized order-line view
Governed daily sales table
Qlik / Power BI / Excel
```

This is sufficient when the volume, team size, operational risk, and change rate are manageable. A platform should only be extended when the current implementation reaches a real limit.

## Implementation alternatives by existing environment

### Only Qlik and SQL Server are available

Use SQL Server for reusable transformations, KPI rules, history, and quality result tables. Qlik should load the prepared data product and retain only Qlik-specific logic that cannot be handled more appropriately upstream.

A deliberately thin Qlik load could look like this:

```qlik
SalesDaily:
LOAD
    business_date,
    order_id,
    order_line_id,
    customer_id,
    product_id,
    country_code,
    net_revenue,
    quality_status
FROM [lib://GovernedData/sales_daily.qvd] (qvd);
```

The Qlik application is then a consumer, not the hidden owner of the enterprise KPI definition.

### Microsoft Fabric with Qlik or Power BI

Use the available Fabric components to ingest, standardize, test, and publish the sales data product. Qlik and Power BI can consume the same curated result. The architectural requirement remains the same: the KPI definition and shared transformation logic should not be recreated independently in each report.

### Snowflake already exists

Use Snowflake as the central execution and storage platform for the required data product. Start with the minimum schemas and transformations required for the sales use case. Add dbt only when modular models, testing, documentation, dependency management, or team collaboration solve a concrete problem better than the existing approach.

### Databricks already exists

Use Databricks for ingestion, standardization, transformation, and quality checks when it is already the established platform. A lakehouse structure can support the product, but the project should still begin with the decision, grain, KPI, and ownership rather than automatically creating broad Bronze, Silver, and Gold layers for every source.

### A classical on-premises warehouse exists

Extend the existing warehouse with a focused sales fact and the required conformed dimensions. Existing schedulers, SQL procedures, views, or ETL tools may be entirely sufficient. Cloud migration is not a prerequisite for a governed data product.

## Greenfield and Brownfield require different starting actions

### Greenfield

In a Greenfield environment, the risk is over-design. The team may try to define the complete future platform before delivering one result.

The better approach is:

- choose one high-value use case;
- define the smallest end-to-end architecture;
- establish naming, ownership, testing, and documentation conventions;
- prove the pattern;
- expand only after the first product works.

### Brownfield

In a Brownfield environment, the risk is uncontrolled duplication. Existing reports and scripts often contain conflicting versions of the same KPI.

The better approach is:

- identify the current reports and logic used for the business question;
- compare definitions and document differences;
- select an authoritative definition and owner;
- move reusable logic into a central model;
- migrate consumers gradually;
- retire duplicated logic after validation.

Brownfield modernization does not require replacing everything at once. It requires establishing one trusted path and reducing divergence step by step.

## Anti-patterns

### Loading every source before defining the use case

This produces data availability without decision value. Load only what the first product requires, while leaving the architecture open for future sources.

### Treating Bronze, Silver, and Gold as the business architecture

These layers can organize technical processing, but they do not define KPI meaning, ownership, consumers, security, or service expectations.

### Building business logic independently in each BI tool

The same cancellation rule should not be implemented separately in Qlik, Power BI, Excel, and SQL. Shared meaning belongs in a shared layer.

### Waiting for the perfect target architecture

A complete enterprise target state can guide direction, but it should not block a useful first product. Build a small pattern that is consistent with the direction and can evolve.

### Introducing dbt, Snowflake, Databricks, or Fabric without a defined need

Each can be valuable. None should be added only because it is considered modern. New components must solve an identified problem in scale, maintainability, governance, collaboration, or performance.

### Leaving ownership until after delivery

Without an owner, KPI disputes and quality failures remain unresolved. Ownership is part of the product definition, not an administrative follow-up.

## The decisions before the first pipeline

The first pipeline should only be designed after business, technical, and organizational decisions have been made explicit.

![The Decisions Before the First Pipeline](images/playbooks/bp-start-img3-en.png)

These decisions do not need months of documentation. For the first data product, a concise decision record is sufficient if it is explicit, approved, and maintained.

A practical minimum contains:

- business question and primary consumer;
- KPI definition and exclusions;
- grain and scope;
- required sources and system of record;
- freshness and history;
- mandatory quality rules and thresholds;
- security classification and access;
- business owner and data owner;
- delivery target and expected service level.

## Decision guide

Use the simplest architecture that can satisfy the explicit requirements.

| Situation | Recommended starting point |
|---|---|
| Small scope, few users, manageable volume | Existing SQL and scheduled loads |
| Reusable models are needed across several consumers | Central warehouse or curated SQL layer |
| Transformations, tests, and dependencies are becoming difficult to manage | Add modular transformation management such as dbt |
| Large-scale processing or an established lakehouse already exists | Use the existing Databricks or equivalent platform |
| A governed cloud warehouse is already established | Build the product in Snowflake or the existing warehouse |
| Fabric is already the enterprise data platform | Use Fabric, but keep the product decision-first |
| Qlik is the main consumer | Keep Qlik scripts thin and consume governed products |
| Power BI is the main consumer | Reuse the governed product and avoid isolated KPI definitions |
| Excel remains operationally important | Connect Excel to the same governed result |

The key criterion is not platform prestige. It is whether the implementation delivers a trusted answer with acceptable effort, risk, and maintainability.

## Most important recommendations

1. Start with one business decision, not with a platform.
2. Define the KPI and grain before designing tables.
3. Load only the sources and fields required for the first product.
4. Define freshness, history, quality, security, and ownership before implementation.
5. Place shared business logic outside individual BI applications.
6. Keep Qlik scripts and report-specific transformations as thin as practical.
7. Use existing tools until a concrete limitation justifies an extension.
8. Deliver one complete vertical data product before broadening the platform.
9. Treat governance as part of the first release, not a later phase.
10. Expand through proven products rather than speculative infrastructure.

## Transition to the next part

This part established what must be decided before the first table or pipeline is created. The next step is to translate these decisions into the first concrete source-to-product architecture: how data is ingested, standardized, modeled, tested, and published without turning every technical layer into a separate business definition.
