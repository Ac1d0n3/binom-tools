---
title: Choosing the Simplest Viable Architecture
description: How to derive the smallest sustainable warehouse architecture from data volume, freshness, transformation complexity, team size, governance and consumer needs — using existing capabilities before adding tools.
category: Data Architecture
tags:
  - data-architecture
  - modern-data-warehouse
  - architecture-decisions
  - data-products
  - qlik-sense
  - power-bi
  - excel
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - sql
  - on-premises
  - hybrid-cloud
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 3
seriesTitle: Building a Modern Data Warehouse
hero: images/playbooks/bp-start3-hero.png
---

## A modern stack is not automatically a better architecture

Modern data platforms offer many useful capabilities: pipelines, dataflows, warehouses, lakehouses, notebooks, semantic models, streaming engines, catalogs, testing frameworks and deployment automation.

The existence of these capabilities does not mean that every warehouse needs all of them.

An architecture becomes viable when it can reliably answer the required business questions, meet its quality and service expectations, remain understandable to the team and evolve without excessive risk. It does not become better merely because it contains more products or resembles a vendor reference diagram.

The correct starting point is therefore not:

```text
Which modern tools can we combine?
```

It is:

```text
What is the smallest architecture that can meet the requirement reliably?
```

This continues the logic established in [Before Building the First Table](/playbooks/before-building-the-first-table) and [Beyond Bronze, Silver and Gold](/playbooks/beyond-bronze-silver-gold). The first part defined the decisions that must precede implementation. The second part separated the logical responsibilities between source, Raw, standardization, integration, data products and consumption. This part determines how much physical technology is actually required to implement those responsibilities.

> **The simplest viable architecture is not the architecture with the fewest boxes. It is the architecture with the least unnecessary complexity that still meets the requirement.**

## Architecture principle: requirements determine complexity

Architecture complexity should be justified by measurable requirements.

The most important drivers are:

| Driver | Questions to clarify |
| --- | --- |
| Data volume | How much data is processed per load, per day and over the retention period? |
| Freshness | Is daily batch sufficient, or are hourly, near-real-time or streaming updates required? |
| Transformation type | Is the workload mainly relational SQL, or does it require distributed processing, complex files, events, ML or graph-like logic? |
| Team size | Is one person maintaining a few models, or are many contributors changing shared transformations? |
| Governance | How much traceability, approval, segregation of duties, testing and auditability are required? |
| Consumers | Is there one BI application, several BI tools, APIs, data science, operational use or external sharing? |
| Availability | What failure window is acceptable and how quickly must the service recover? |
| Existing estate | Which databases, platforms, skills, contracts and operating processes already work? |
| Change rate | How often do sources, models, KPIs and consumer contracts change? |
| Cost model | Which additional licenses, compute, operations and specialist skills would another component introduce? |

These factors do not automatically point to one product. They determine which capabilities are necessary.

A small team with ten SQL models, one daily load and two BI applications may need a disciplined SQL database, not a multi-engine platform.

A larger team with hundreds of interdependent SQL models may benefit from modular development, version control, automated tests and deployment workflows.

A workload dominated by high-volume events, distributed transformations or machine learning may justify a Spark-oriented platform.

A Microsoft-centric organization may find an integrated Fabric implementation simpler than connecting several independent services.

A stable On-Premises warehouse may need modernization of testing, deployment and interfaces rather than a platform replacement.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start3-img1-en.png"
        alt="Decision flow from business requirements through data volume, refresh frequency, transformation complexity, team size, governance and consumer diversity to several valid target architectures"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Volume, freshness, transformation type, collaboration, governance and consumer diversity determine the required capabilities. Several architectures can satisfy the same logical warehouse responsibilities at different levels of physical complexity.
    </figcaption>
</figure>

## The simplest implementation that can work

The minimum viable architecture should implement the logical responsibilities from the previous part without creating a separate product for every responsibility.

For a straightforward batch-oriented use case, one existing SQL database can be sufficient:

```flow linear vertical
Operational sources
SQL staging tables
Standardized and integrated SQL views or tables
Governed fact and dimension tables
Quality results
Qlik / Power BI / Excel
```

Several logical responsibilities may share one database while remaining explicit through schemas, naming, ownership and deployment rules.

For example:

```text
raw.order_lines
stg.order_lines
core.customer
core.product
mart.sales_daily
quality.test_results
```

This design is simple, but it is not uncontrolled. It still requires:

- an explicit grain;
- business and technical ownership;
- repeatable loads;
- stable keys;
- documented transformation rules;
- quality checks;
- access control;
- load monitoring;
- a defined consumer contract;
- controlled changes.

The absence of an additional platform does not remove the need for architecture discipline.

## Concrete example: daily sales steering

Assume the organization needs a governed daily sales data product with the following requirements:

| Requirement area | Decision |
| --- | --- |
| Business purpose | Daily sales steering and identification of negative deviations |
| Grain | One order line per business date |
| Sources | ERP orders, CRM customers, product master, country reference data |
| Volume | Approximately 10 million new or changed order lines per year |
| Freshness | Available every morning by 07:00 |
| History | Customer and product assignments must be reproducible historically |
| Quality | Mandatory order, customer, product, country, amount and change timestamp |
| Consumers | Qlik, Power BI and controlled Excel access |
| Team | Two developers and one business owner |
| Governance | Approved KPI definition, lineage, failed-record visibility and change history |
| Advanced processing | No streaming, ML or large unstructured-data workload |

The minimal architecture could use:

- existing scheduling or ETL for extraction;
- SQL staging tables for received data;
- SQL views or stored transformations for standardization;
- conformed customer and product dimensions;
- one sales fact at order-line grain;
- one curated `sales_daily` data product;
- one `data_quality_results` table;
- a thin Qlik load;
- a Power BI semantic model or Excel reporting view where required.

A simplified central transformation could be:

```sql
create or replace view mart.sales_daily as
select
    cast(o.order_date as date) as business_date,
    o.order_id,
    o.order_line_id,
    c.customer_key,
    p.product_key,
    c.country_code,
    o.gross_amount,
    o.cancellation_flag,
    case
        when o.cancellation_flag = 1 then 0
        else o.gross_amount
    end as net_revenue,
    o.changed_at
from core.order_line o
join core.customer_history c
  on o.customer_id = c.customer_id
 and o.order_date >= c.valid_from
 and o.order_date < coalesce(c.valid_to, date '9999-12-31')
join core.product_history p
  on o.product_id = p.product_id
 and o.order_date >= p.valid_from
 and o.order_date < coalesce(p.valid_to, date '9999-12-31');
```

The syntax must be adapted to the database. The architectural point is the placement of the rule:

- historical customer and product assignment is resolved centrally;
- the net-revenue basis is defined once;
- Qlik, Power BI and Excel receive the same governed result;
- quality failures can be evaluated before consumption.

A deliberately thin Qlik script could then be limited to loading the prepared product and applying only Qlik-specific naming or association behavior:

```qlik
SalesDaily:
SQL SELECT
    business_date,
    order_id,
    order_line_id,
    customer_key,
    product_key,
    country_code,
    gross_amount,
    cancellation_flag,
    net_revenue,
    changed_at
FROM mart.sales_daily;
```

Qlik remains a powerful analytical consumer. It does not need to become the hidden integration and KPI engine when reusable logic can be maintained upstream.

## Five valid starting points

The same logical architecture can be implemented with different native capabilities.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start3-img2-en.png"
        alt="Comparison of five valid architecture starting points using Qlik and SQL, Microsoft Fabric, Snowflake, Databricks and an On-Premises warehouse across the same logical layers"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Sources, ingestion, transformation, governed data products and consumers remain the same logical concerns. The native implementation differs according to the platform that already exists and the workload it must support.
    </figcaption>
</figure>

### Starting point 1: Qlik and an SQL database

This is a valid architecture when:

- the workload is predominantly batch-oriented;
- transformations are mainly relational;
- the number of models and contributors is manageable;
- an SQL database is already operated reliably;
- Qlik, Power BI or Excel can consume prepared tables or views;
- advanced distributed processing is not required.

A practical allocation is:

| Responsibility | Possible implementation |
| --- | --- |
| Ingestion | Existing ETL, scheduler, database jobs or controlled Qlik extraction where necessary |
| Raw / staging | SQL tables or controlled files |
| Standardization | SQL views, procedures or scheduled transformations |
| Integration | SQL facts, dimensions, key mappings and history tables |
| Data product | Curated tables, views or governed QVD extracts |
| Quality | SQL assertions and a persistent quality-results table |
| Consumption | Thin Qlik scripts, Power BI semantic model, Excel view |

This option should not be dismissed as old-fashioned. It should be rejected only when it cannot satisfy scale, collaboration, operational or governance requirements economically.

### Starting point 2: Microsoft Fabric with Qlik or Power BI

Fabric can be the simplest option when the organization already operates primarily in the Microsoft ecosystem and benefits from integrated ingestion, transformation, storage, engineering and reporting capabilities.

Possible components include:

- Data Factory pipelines or Dataflows for ingestion and preparation;
- a Lakehouse or Warehouse for managed analytical storage;
- SQL or notebooks for transformations;
- governed tables and semantic models for consumption;
- Power BI as a native consumer;
- Qlik or Excel consuming the same curated outputs through appropriate interfaces.

Fabric does not remove the need to define logical boundaries. A Lakehouse, Warehouse and semantic model should be introduced because they serve different requirements, not because all are available.

A small Fabric implementation may need only one ingestion path, one Warehouse or Lakehouse, a few transformations and one governed data product. An architecture with duplicated pipelines, notebooks, SQL transformations and semantic logic is not simpler merely because the services belong to one platform.

### Starting point 3: Snowflake with BI tools

Snowflake can be appropriate when the primary requirement is a managed cloud warehouse with independently scalable compute, SQL-based transformation and governed data sharing or consumption.

A minimal Snowflake-oriented implementation may use:

- controlled loading into landing or staging schemas;
- SQL transformations in views, tables, tasks or another existing orchestration mechanism;
- core and mart schemas;
- separate compute configurations for appropriate workloads;
- Qlik, Power BI, Excel or APIs as consumers.

dbt may be added when it improves the development workflow. Snowflake does not require dbt, and dbt does not require Snowflake. The architectural decision is whether modular model dependencies, code review, automated testing, documentation and team-based deployment justify the additional framework.

### Starting point 4: Databricks with BI tools

Databricks can be appropriate when the workload genuinely benefits from lakehouse storage, Spark-based distributed processing, streaming, advanced engineering or machine learning.

A minimal Databricks-oriented implementation may use:

- controlled landing of files, events or source extracts;
- Delta tables for managed data states;
- SQL, notebooks or declarative pipelines for transformation;
- curated tables or data products;
- BI access through governed SQL interfaces;
- ML or streaming only where required.

A few million relational rows loaded once per day do not automatically require Spark. Databricks becomes valuable when the processing pattern, scale, data variety or ML lifecycle solves a problem that a simpler SQL architecture cannot solve adequately.

### Starting point 5: an existing On-Premises warehouse

A stable On-Premises warehouse remains valid when it meets the business, security, latency, resilience and cost requirements.

Modernization may focus on:

- reducing logic duplicated in BI applications;
- introducing source-controlled SQL;
- automating tests;
- improving metadata and lineage;
- separating core models from consumer-specific views;
- creating reliable deployment paths;
- exposing governed interfaces to Qlik, Power BI and Excel;
- adding cloud capabilities selectively.

A platform migration is justified when the existing environment presents a material limitation. It is not justified only because the current platform is not described as modern.

### Hybrid scenarios

Hybrid is often not a temporary defect. It can be the simplest viable architecture when systems of record, security constraints, data residency, latency or migration sequencing require different locations.

A controlled hybrid architecture should define:

- which system is authoritative for each object;
- where Raw data is retained;
- which transformations occur On-Premises and which in the cloud;
- how keys and history remain consistent;
- how data crosses network and security boundaries;
- where quality results and lineage are consolidated;
- which interfaces are stable for Qlik, Power BI, Excel or APIs;
- how duplicate transformation logic is prevented.

Hybrid becomes problematic when both sides independently implement the same business meaning. The objective is one logical architecture across several physical environments, not two uncontrolled warehouses.

## When another tool adds value

Another tool should be added only after three questions can be answered:

1. Which concrete limitation exists today?
2. Which capability is missing?
3. Does the proposed component solve that limitation better than improving the existing platform?

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start3-img3-en.png"
        alt="Decision matrix connecting existing capabilities and concrete problems to useful extensions such as dbt, Microsoft Fabric, Snowflake, Databricks or no new tool"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A product name is not a requirement. dbt, Fabric, Snowflake and Databricks add value when they supply a capability that is genuinely missing. A stable SQL landscape may require no new tool.
    </figcaption>
</figure>

### dbt becomes interesting when SQL development becomes a team problem

dbt can add value when:

- many SQL models depend on one another;
- several developers work on shared transformations;
- pull requests and code reviews are required;
- repeatable tests should be attached to models;
- documentation and dependency visibility need to be generated consistently;
- deployment between environments needs more structure.

dbt should not be introduced only to replace ten understandable SQL views with ten dbt models. The framework is justified by the development and governance problem it solves.

### Fabric becomes interesting when integration reduces the total stack

Fabric can add value when:

- the organization already depends strongly on Microsoft services;
- separate ingestion, storage, engineering and reporting services create avoidable handoffs;
- one governed platform can simplify identity, operations and delivery;
- Power BI integration is strategically important;
- the team can operate the integrated platform more effectively than several independent products.

Fabric is not automatically simpler if the organization retains every existing service and adds Fabric on top without removing duplication.

### Snowflake becomes interesting when cloud warehousing is the actual requirement

Snowflake can add value when:

- elastic or isolated warehouse compute is required;
- SQL-based analytical workloads need a managed cloud platform;
- governed sharing and cross-team consumption are important;
- operational effort for a self-managed database is a material constraint;
- workload separation and consumption scaling justify the platform.

Snowflake should not be added merely because the current SQL database uses a different product name.

### Databricks becomes interesting when distributed processing is required

Databricks can add value when:

- very large data volumes require distributed processing;
- high-volume events or streaming are material requirements;
- complex files or semi-structured data dominate the workload;
- data engineering and machine-learning workflows need to operate on the same governed data foundation;
- Spark-oriented skills and operational practices already exist or are justified.

A Spark platform is not a success metric. It is a response to a workload.

### No new tool is a valid decision

Keeping the current architecture can be the correct outcome when:

- service levels are met;
- costs are controlled;
- quality and audit requirements are satisfied;
- the team understands and can maintain the solution;
- scaling expectations are realistic;
- business logic is reusable and not trapped in reports;
- the next limitation has not yet appeared.

The burden of proof belongs to the proposed addition, not to the existing component.

## A practical decision matrix

The following matrix translates common situations into proportionate responses.

| Situation | Simplest reasonable response | Add another component when |
| --- | --- | --- |
| Few models, small team, daily batch | Existing SQL database and scheduled transformations | Collaboration, testing or deployment becomes difficult |
| Several BI consumers | Shared SQL data product plus consumer-specific semantic or presentation layer | Consumer contracts require independent scaling or security |
| Many SQL dependencies and contributors | Structured SQL repository, tests and deployment discipline | dbt materially improves modularity and workflow |
| Strong Microsoft estate | Use available Microsoft services consistently | Fabric reduces separate services and handoffs |
| Managed elastic cloud warehouse required | Evaluate a cloud warehouse such as Snowflake | Cost, isolation, sharing and scaling benefits justify migration |
| Spark, streaming or ML-heavy workload | Evaluate Databricks or another distributed platform | Distributed processing is a real workload requirement |
| Stable On-Premises warehouse | Modernize code, tests, lineage and interfaces incrementally | A material capacity, resilience, support or strategic limitation exists |
| Hybrid regulatory or migration constraint | Define one logical model across locations | A new service reduces controlled data movement or duplication |
| One Qlik application with local calculations | Move shared meaning upstream first | Qlik-specific behavior is genuinely required |
| Existing SQL already meets all needs | Keep it simple | A measurable requirement is no longer met |

## Cost is more than license cost

Every additional component creates more than a subscription charge.

The architecture must account for:

- platform administration;
- identity and access integration;
- networking;
- environments;
- deployment pipelines;
- monitoring;
- incident handling;
- metadata and lineage integration;
- backup and recovery;
- specialist skills;
- vendor and release management;
- duplicated storage or compute;
- additional failure boundaries.

A component can still be worthwhile. The benefit must exceed the full lifecycle cost.

## Typical anti-patterns

### Copying a reference architecture without its requirements

Vendor diagrams are designed to show capabilities. They are not a mandate to deploy every displayed service.

### Using one tool per logical layer

Logical responsibilities do not require one physical product each. Raw, standardization, integration and marts may be separate schemas in one database.

### Adding dbt before development complexity exists

dbt can improve modular SQL development, testing and documentation. It cannot create value if there is no collaboration, dependency or delivery problem to solve.

### Choosing Spark for ordinary relational batch processing

Distributed processing introduces new operational and development patterns. It should be selected for workloads that need them.

### Moving to the cloud without removing old complexity

A migration that leaves the old pipelines, old KPI logic and old consumer extracts in place creates an additional platform rather than a simpler architecture.

### Rebuilding the same business rule in every consumer

Net revenue, customer identity, country assignment and history must not be independently reimplemented in Qlik, Power BI and Excel.

### Treating integration as a product feature instead of an operating model

An integrated platform can still contain disconnected teams, duplicated pipelines and conflicting definitions. Integration must be reflected in ownership, development and operations.

### Assuming On-Premises means obsolete

A stable On-Premises warehouse can be technically and economically appropriate. Its weaknesses should be assessed explicitly instead of inferred from its location.

### Designing for an imagined future scale

Architecture should allow growth, but it should not pay today for a workload that may never exist. Scale when a credible forecast or measured trend justifies it.

## Most important recommendations

1. Start with business requirements, not product categories.
2. Separate logical architecture from physical implementation.
3. Use the tools and skills already available when they meet the requirement.
4. Keep shared business logic outside individual Qlik apps, Power BI reports and Excel workbooks.
5. Keep Qlik scripts as thin as practical; retain only Qlik-specific logic that is genuinely necessary.
6. Treat SQL as a valid primary transformation option for relational workloads.
7. Add dbt when model dependencies, testing and team workflows justify it.
8. Use Fabric when an integrated Microsoft platform reduces the total architecture.
9. Use Snowflake when managed elastic cloud warehousing solves a concrete requirement.
10. Use Databricks when distributed processing, streaming or ML workloads require it.
11. Modernize a stable On-Premises warehouse before replacing it by default.
12. In hybrid environments, maintain one logical architecture and one authoritative definition of business meaning.
13. Include operations, governance, skills and lifecycle cost in every tool decision.
14. Accept “no new tool required” as a legitimate architecture decision.
15. Reassess the architecture when requirements change, not when a new product becomes fashionable.

## Transition to the next part

This part established how to select a proportionate physical architecture from explicit requirements. The next challenge is to build the first warehouse capability without trying to implement the entire enterprise platform at once.

The next part, [Building a Warehouse from Scratch](/playbooks/building-from-scratch), starts with one vertical data product and turns the first use case into a reusable platform pattern.
