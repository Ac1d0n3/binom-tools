---
title: One Architecture – Multiple Platforms
description: How the same governed warehouse architecture can be implemented with SQL Server and on-premises infrastructure, Microsoft Fabric, Snowflake, Databricks or deliberate hybrid combinations without turning one product stack into a universal requirement.
category: Data Architecture
tags:
  - data-architecture
  - modern-data-warehouse
  - platform-architecture
  - reference-architecture
  - sql-server
  - on-premises
  - microsoft-fabric
  - onelake
  - fabric-lakehouse
  - fabric-warehouse
  - power-bi
  - power-bi-report-server
  - qlik-sense
  - excel
  - snowflake
  - dynamic-tables
  - databricks
  - delta-lake
  - unity-catalog
  - lakehouse
  - dbt
  - data-products
  - semantic-model
  - data-quality
  - data-governance
  - hybrid-cloud
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 9
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start9-hero.png
---

## The platform is an implementation — not the architecture

A modern data warehouse needs clear responsibilities:

```text
Acquire source data
Preserve a recoverable raw state
Standardize and integrate records
Resolve keys and history
Apply shared business rules
Publish governed data products
Serve analytical and operational consumers
Operate security, quality, lineage and monitoring across every layer
```

None of these responsibilities requires one specific product.

They can be implemented with an existing SQL Server estate, Microsoft Fabric, Snowflake, Databricks, another relational warehouse or a deliberate hybrid combination. The technical objects, execution engines and operating models differ. The logical architecture should remain recognizable.

A tool-first design reverses this order:

```text
“We bought Fabric, so every workload must use Fabric.”
“We use Databricks, so every transformation must become Spark.”
“We selected Snowflake, so every source must be copied into Snowflake.”
“We already have Qlik, so the Qlik load script remains the warehouse.”
“We introduced dbt, so every SQL object must be rebuilt in dbt.”
```

Each decision may be valid for a particular workload. None is an architectural principle.

Part 8, [Transformation Options](/playbooks/transformation-options), showed that SQL, stored procedures, notebooks, dataflows, native platform functions and dbt can all be legitimate transformation mechanisms. This part maps the complete architecture to different platforms.

> **Keep the business responsibilities stable. Select the technical implementation that best fits the existing landscape, workload, team and operating model.**

## Architecture principle: preserve responsibilities, not product names

A portable reference architecture describes what each layer must achieve before it assigns a technology.

| Logical responsibility | Required outcome | Possible physical implementation |
| --- | --- | --- |
| Sources | Identified systems, owners, extraction expectations and change behavior | ERP, CRM, files, APIs, events, operational databases |
| Ingestion | Reliable, observable and repeatable acquisition | ETL/ELT job, pipeline, replication, mirroring, streaming, scheduled script |
| Raw or staging | Recoverable source-aligned state with technical metadata | Database schema, object storage, Delta tables, internal stage, governed files |
| Standardized layer | Clean types, normalized codes, deduplication and source conformance | SQL tables/views, notebook output, declarative tables, ETL mappings |
| Core warehouse | Integrated facts, dimensions, keys, history and reusable rules | Relational warehouse, lakehouse tables, Delta model, Snowflake tables |
| Data products and marts | Business-ready contracts with owner, quality and publication status | Star schema, curated views, aggregates, governed tables, service views |
| Semantic and consumption layer | Tool-appropriate analysis without redefining shared truth | Qlik associative model, Power BI semantic model, Excel view, API |
| Cross-cutting controls | Security, quality, lineage, observability, deployment and lifecycle | Platform-native controls, catalog, control tables, monitoring and CI/CD |

Bronze, Silver and Gold can be useful names for some physical layers. They do not replace the responsibilities above. A Bronze table without recoverability, ownership and ingestion metadata is not a complete raw layer. A Gold table without an explicit grain, business definition, quality status and owner is not automatically a data product.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img1-en.png"
        alt="One logical data warehouse architecture mapped to SQL Server on-premises, Microsoft Fabric, Snowflake and Databricks implementations"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The same logical responsibilities can be implemented with different platform objects. The diagram is a mapping, not a ranking and not a requirement to use all listed components.
    </figcaption>
</figure>

A useful portability test is:

> **Can the team explain the business grain, history, quality, security and publication behavior without naming the platform?**

If the answer is no, the design is probably still describing products rather than architecture.

## The simplest valid implementation

The simplest useful warehouse may already be available.

A company with SQL Server, a scheduler, Qlik, Power BI Report Server and Excel can build a governed architecture without first purchasing a cloud data platform.

A minimal implementation can contain:

```text
staging.sales_order_line
staging.customer
core.fact_sales_order_line
core.dim_customer
core.dim_product
core.dim_date
mart.sales_customer_daily
quality.sales_rule_result
control.data_product_publication
```

The technical flow may be:

```flow linear vertical
Operational source extracts
Scheduled ingestion into staging
SQL transformations into core facts and dimensions
Quality results and reconciliation controls
Published Sales data mart
Qlik / Power BI Report Server / Excel consumption
```

The first version does not require a separate lake, Spark, dbt, a catalog product or a new semantic-layer tool. It does require:

- one authoritative definition of Sales;
- a documented grain;
- stable business and surrogate keys;
- explicit history rules;
- persistent data-quality evidence;
- controlled publication;
- access rules;
- versioned transformation code;
- monitoring and an accountable owner.

Additional technology should be introduced only when it solves a concrete limitation in integration, scale, collaboration, latency, governance or operations.

## One Sales and Customer example across every platform

The platform comparison is only useful when every implementation produces the same business answer.

Assume the governed product is **Sales by Customer**.

### Product contract

| Contract element | Definition |
| --- | --- |
| Grain | One row per posted sales order line and business date |
| Business date | Posting date used by Finance reporting |
| Customer history | Customer version valid on the business date |
| Product history | Product classification valid on the business date |
| Net revenue | Gross line amount minus approved discounts; cancelled lines excluded; converted with the approved rate valid for the business date |
| Reporting currency | EUR |
| Required keys | Order line, customer, product, business date and source system |
| Quality gates | Valid customer key, product key and exchange rate; amounts reconcile to the source control total |
| Publication | Daily only after required quality and reconciliation checks pass |
| Consumers | Qlik analysis, Power BI management reporting and controlled Excel extracts |
| Owner | Sales Data Owner, supported by the data-platform team |

A platform-neutral core model may expose:

```text
fact_sales_order_line
  business_date_key
  order_id
  order_line_id
  customer_key
  product_key
  quantity
  gross_amount
  discount_amount
  net_revenue_amount
  reporting_currency
  quality_status
  source_system
  publication_timestamp

dim_customer
  customer_key
  customer_id
  customer_name
  country_code
  segment_code
  valid_from
  valid_to
  is_current
```

The reusable rule belongs in the governed transformation and product layer:

```sql
select
    s.business_date,
    s.order_id,
    s.order_line_id,
    c.customer_key,
    p.product_key,
    s.quantity,
    (s.gross_amount - s.discount_amount) * fx.reporting_rate
        as net_revenue_amount,
    'EUR' as reporting_currency
from standardized.sales_order_line s
join core.dim_customer c
  on s.customer_id = c.customer_id
 and s.business_date >= c.valid_from
 and s.business_date < c.valid_to
join core.dim_product p
  on s.product_id = p.product_id
 and s.business_date >= p.valid_from
 and s.business_date < p.valid_to
join reference.exchange_rate fx
  on s.currency_code = fx.source_currency
 and fx.target_currency = 'EUR'
 and s.business_date = fx.rate_date
where s.standardized_status = 'POSTED';
```

The exact SQL syntax, date boundaries and materialization method vary by engine. The meaning must not.

Consumer-specific behavior remains local when justified:

| Consumer | Shared from the data product | Consumer-specific responsibility |
| --- | --- | --- |
| Qlik | Sales facts, customer history, approved net revenue and quality status | Associative model, selections, Master Measures, Set Analysis and Qlik-specific access behavior |
| Power BI | Same facts, dimensions and approved amounts | Relationships, DAX measures, filter context, report navigation and distribution |
| Excel | Controlled denormalized Sales view or approved semantic model | Pivot layout, comments, local planning columns and operational follow-up |

Qlik must not recalculate cancellation, currency conversion or historical customer assignment in every app. Power BI must not create a different net-revenue definition in DAX. Excel must not become the only place where missing customer mappings are corrected.

## Microsoft Fabric with Qlik, Power BI and Excel

Microsoft Fabric is a valid implementation when the organization benefits from a managed Microsoft analytics platform, OneLake, Fabric Data Factory, lakehouse or warehouse workloads and close Power BI integration.

It is not automatically the best choice merely because Microsoft 365, Azure or Power BI already exists.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img2-en.png"
        alt="Microsoft Fabric reference architecture with sources, Fabric ingestion, staging, modeling, governed data products, semantic models and controlled consumption by Qlik, Power BI, Excel and applications"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Fabric can implement the complete logical architecture, while Qlik, Power BI and Excel remain distinct consumers. Fabric-native integration does not require shared business logic to be recreated in each consumer.
    </figcaption>
</figure>

### A minimal Fabric mapping

| Logical responsibility | Possible Fabric implementation |
| --- | --- |
| Ingestion | Data Factory pipeline, Copy activity, Dataflow Gen2, mirroring or an existing ingestion tool |
| Raw landing | Lakehouse files or Delta tables in OneLake |
| Standardization | Lakehouse tables created by Spark/SQL, Dataflow output or controlled transformations |
| Core warehouse | Fabric Warehouse for T-SQL-centric modeling, or curated Delta tables in a Lakehouse |
| Data product | Warehouse star schema, curated Lakehouse tables or governed views |
| Semantic model | Power BI semantic model using the suitable storage mode and security design |
| Qlik consumption | Governed Warehouse or SQL analytics endpoint through a supported SQL/ODBC connection path, or another controlled delivery contract |
| Excel consumption | Approved semantic model, governed SQL view, Power Query connection or controlled file output |
| Governance and operation | Workspace permissions, SQL permissions, OneLake security, monitoring, deployment and Purview integration where required |

Fabric provides several valid paths. The architecture should select the smallest set that the team can operate.

### Lakehouse, Warehouse or both

A Lakehouse is useful when the workload needs files, Delta tables, Spark, Python, semi-structured data or data-science integration.

A Fabric Warehouse is useful when the workload is strongly relational and the team prefers T-SQL, warehouse objects, dimensional modeling and SQL-oriented consumption.

Using both can be appropriate when responsibilities are explicit:

```text
Lakehouse
  source-aligned files and Delta tables
  large or semi-structured transformations
  Spark and Python workloads

Warehouse
  integrated facts and dimensions
  governed relational data products
  stable SQL contracts for BI consumers
```

Using both without a responsibility boundary creates duplicated storage, repeated transformations and unclear ownership.

### Mirroring, pipelines and shortcuts are choices

Mirroring can reduce custom ingestion effort for supported source scenarios. Data Factory pipelines can orchestrate and automate multi-step workflows. OneLake shortcuts can expose existing data without directly copying it where the source, security and performance pattern are suitable.

These capabilities solve different problems. They should not all be inserted into every flow.

A useful selection sequence is:

1. Can the existing ingestion process reliably deliver the required data?
2. Does mirroring provide the required source support, latency and operational behavior?
3. Would a pipeline make dependencies, scheduling and monitoring clearer?
4. Can a shortcut avoid unnecessary copies without weakening ownership or security?
5. Is physical materialization required for performance, history, quality isolation or contractual stability?

### Power BI uses Fabric-native strengths

Power BI can use Fabric semantic models and Direct Lake where the model, capacity, security and operational requirements make it appropriate.

The semantic model remains a consumption layer. Shared Sales rules still belong in governed data products unless they are genuinely filter-context or presentation behavior.

Suitable Power BI responsibilities include:

- relationships and hierarchies;
- DAX measures built on approved facts;
- time-intelligence behavior;
- report navigation;
- row-level or object-level model security where appropriate;
- controlled distribution and endorsement.

### Qlik remains a thin analytical consumer

Qlik can consume governed Fabric data through a supported data-access path. The Qlik application may add:

- an associative consumer model;
- canonical calendars;
- Master Dimensions and Master Measures;
- Set Analysis for Qlik-specific comparisons;
- Section Access or another justified enforcement mechanism;
- app-specific performance optimization.

The Qlik load script should not become a second Fabric transformation platform. Shared customer integration, currency conversion, history and quality rules remain upstream.

### Excel receives a controlled contract

Excel can use an approved semantic model, a governed SQL view, a Power Query connection or a controlled output file depending on the user workflow and licensing environment.

The workbook may own:

- pivot layout;
- comments and review status;
- controlled planning assumptions;
- local formatting;
- operational follow-up columns.

It should not own the authoritative customer match, cancellation rule or net-revenue calculation.

## Snowflake and Databricks as equal alternatives

Snowflake and Databricks can both implement the same governed architecture. They have different strengths, interfaces and operating models, but neither should be treated as the mandatory premium destination.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img3-en.png"
        alt="Side-by-side Snowflake and Databricks implementations of the same logical architecture from sources through raw, standardized and business-ready layers to semantic and BI consumers"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Snowflake and Databricks can produce the same governed Sales and Customer product through different physical patterns. The selection should follow workload and operating context rather than platform status.
    </figcaption>
</figure>

### Snowflake implementation

A SQL-centric Snowflake implementation may use schemas such as:

```text
RAW
STANDARDIZED
CORE
MART
QUALITY
CONTROL
```

The Sales example may be mapped as:

| Responsibility | Snowflake implementation option |
| --- | --- |
| Ingestion | Bulk loading, Snowpipe, partner integration or an existing ETL/ELT tool |
| Raw | Source-aligned tables and staged files |
| Standardization | SQL views, tables or Dynamic Tables |
| Core | Integrated facts and dimensions in Snowflake tables |
| Data product | Governed views, tables, marts or secure delivery objects |
| Incremental control | Dynamic Tables or explicit Streams and Tasks where they fit |
| Compute isolation | Separate virtual warehouses for ingestion, transformation and BI where justified |
| Consumption | Qlik, Power BI, Excel, APIs or other tools through governed interfaces |

Dynamic Tables can simplify declarative SQL pipelines where target freshness and dependency management match the requirement. Streams and Tasks can provide more explicit change capture and procedural scheduling behavior. Ordinary SQL views and scheduled table builds remain valid.

The team should not use all three patterns for the same rule.

A practical Snowflake Sales design might be:

```text
RAW.SALES_ORDER_LINE
RAW.CUSTOMER
STANDARDIZED.SALES_ORDER_LINE
STANDARDIZED.CUSTOMER
CORE.FACT_SALES_ORDER_LINE
CORE.DIM_CUSTOMER
MART.SALES_CUSTOMER_DAILY
QUALITY.SALES_RULE_RESULT
CONTROL.SALES_PUBLICATION
```

Qlik and Power BI can use dedicated BI compute or workload controls without changing the product definition. Excel can receive a narrower view or controlled extract.

Snowflake is especially attractive when a cloud data warehouse, SQL-centric analytics, independent compute scaling, cross-team data sharing or multi-cloud availability solves a real need. It is unnecessary when an existing relational platform already meets the workload economically and operationally.

### Databricks implementation

A Databricks implementation commonly maps the architecture to governed Delta tables and a medallion-style progression:

```text
Bronze      Source-aligned Delta tables
Silver      Cleaned, standardized and integrated Delta tables
Gold        Business-ready facts, dimensions, aggregates and data products
```

That naming remains optional. The responsibilities are mandatory.

The Sales example may be mapped as:

| Responsibility | Databricks implementation option |
| --- | --- |
| Ingestion | Lakeflow Connect, Auto Loader, streaming, batch jobs or an existing ingestion tool |
| Raw | Bronze Delta tables in cloud object storage |
| Standardization | Silver Delta tables using SQL, Python or Spark |
| Core and product | Gold facts, dimensions, aggregates and governed views |
| Orchestration | Lakeflow Jobs and pipelines, or another controlled orchestrator |
| Governance | Unity Catalog for governed data and AI assets, permissions and discovery |
| SQL consumption | Databricks SQL warehouse and governed SQL endpoints |
| Advanced workloads | Spark, Python, streaming, data science and ML where required |

A possible governed object structure is:

```text
main.raw.sales_order_line
main.raw.customer
main.standardized.sales_order_line
main.standardized.customer
main.core.fact_sales_order_line
main.core.dim_customer
main.product.sales_customer_daily
main.quality.sales_rule_result
main.control.sales_publication
```

Databricks is a strong option when the organization needs one environment for large-scale engineering, SQL analytics, streaming, Python, Spark, data science or machine learning.

It should not be described as an on-premises open-source product. Delta Lake is open source, but the Databricks platform is a managed cloud platform available across AWS, Azure and Google Cloud. An on-premises Apache Spark or Delta Lake deployment is a different operating model and should not be labelled as Databricks.

Qlik can connect to governed Databricks tables through the supported Databricks connector and SQL compute. Power BI can use a supported Databricks connection pattern. Excel should consume an approved SQL-facing view, semantic model or controlled extract rather than accessing arbitrary notebooks or Bronze tables.

### dbt remains optional on both platforms

dbt can add value to Snowflake, Databricks, Fabric or another SQL-capable platform when the team needs:

- a modular SQL model graph;
- Git-based review;
- tests and documentation;
- reusable macros and conventions;
- CI/CD for analytics engineering;
- a consistent development workflow across many models.

It should not become a mandatory layer simply because several platforms are present.

A useful rule is:

```text
Native platform transformation is authoritative
or
dbt project is authoritative
```

A mixed model is valid only when the boundary is explicit, for example:

```text
Native ingestion and streaming
Native or Python standardization
Authoritative dbt project for SQL-centric core and marts
Platform-native scheduling and monitoring
```

## Which platform fits which context?

The platform decision should start with the current estate and the dominant workload, not with a generic feature comparison.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img4-en.png"
        alt="Decision matrix comparing SQL Server, Microsoft Fabric, Snowflake and Databricks by existing landscape, primary workload, team skills, operating model, governance, cloud strategy and on-premises requirements"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Platform fit depends on the existing ecosystem, workload, team and operating constraints. No fixed data-volume threshold or feature count can replace a contextual decision.
    </figcaption>
</figure>

### Existing ecosystem

Use the existing platform when it can meet the business requirement with acceptable risk and operating effort.

A mature SQL Server estate with reliable operations, strong SQL skills and predictable reporting workloads may be more valuable than a hurried migration to a cloud platform.

Fabric can reduce integration friction when Power BI, Azure identity, OneLake-oriented analytics and Microsoft platform operations are strategic.

Snowflake can fit a cloud-data-warehouse operating model with strong SQL analytics, workload isolation and data-sharing requirements.

Databricks can fit an engineering- and AI-intensive environment where SQL, Spark, Python, streaming and machine learning need to coexist.

### SQL versus Spark and Python

A predominantly relational workload does not need Spark merely because the data volume is large.

Choose SQL-oriented processing when:

- transformations are set-based;
- dimensional modeling dominates;
- the team is strongest in SQL;
- BI concurrency and governed reporting are primary;
- the current warehouse meets latency and scale requirements.

Choose Spark/Python-oriented processing when:

- transformations require distributed code or complex libraries;
- semi-structured or unstructured data is central;
- streaming and event processing are substantial;
- data science and feature engineering are first-class workloads;
- reusable software-engineering patterns are needed beyond SQL.

Hybrid SQL and Python platforms can support both. The architecture must still define which engine owns each rule.

### BI focus versus ML and streaming

A management-reporting platform and an ML platform may share governed data without sharing every runtime.

Do not force the BI workload into an engineering engine only because ML exists. Do not force ML preparation into BI-oriented SQL if Python or Spark is genuinely required.

The shared boundary is the governed data product and its contracts.

### Operating model

A platform is not only a development interface. Evaluate:

```text
Identity and access administration
Network and private connectivity
Environment separation
Source control and deployment
Scheduling and dependency management
Monitoring and alerting
Cost and capacity management
Backup, recovery and continuity
Incident ownership
Vendor and internal support
Skill availability
```

A feature-rich platform with no sustainable operating model is a weak architecture.

### Cost model

Compare total operating cost rather than list price.

Include:

- licenses or subscriptions;
- capacity or consumption;
- storage and data transfer;
- idle and peak compute;
- development and migration effort;
- platform administration;
- monitoring and security tooling;
- training and recruitment;
- support and incident recovery;
- parallel operation during migration.

On-premises is not automatically cheaper because hardware already exists. Cloud is not automatically cheaper because it is consumption-based. The workload profile and operating discipline determine the result.

### Data residency and connectivity

If policy, regulation, contracts or connectivity require local processing, an on-premises or carefully designed hybrid architecture may be the correct target.

If cloud use is permitted, region, network, identity, encryption, data-transfer and continuity requirements still need explicit validation.

### Team competence

Do not select a platform only because external specialists can build it.

The internal team must be able to:

- review changes;
- understand dependencies;
- operate production;
- diagnose failures;
- control cost;
- enforce security;
- evolve the model after the initial project ends.

A smaller design using existing competence is often safer than a theoretically superior platform that remains operationally external.

## On-premises is still a valid architecture

On-premises does not mean ungoverned, monolithic or outdated.

The same modern principles apply:

```text
Source-aligned staging
Recoverable ingestion
Versioned transformations
Conformed facts and dimensions
Persistent quality evidence
Governed data products
Thin consumer models
Automated monitoring and deployment
Explicit ownership and lifecycle
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img5-en.png"
        alt="Modern on-premises data warehouse architecture from sources through staging database, core warehouse and data marts to Qlik, Power BI Report Server and Excel"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        On-premises remains a valid implementation when it matches regulatory, connectivity, investment, skill or operating requirements. Modern architecture is defined by responsibilities and controls, not by hosting location.
    </figcaption>
</figure>

### A practical on-premises mapping

| Responsibility | Possible implementation |
| --- | --- |
| Ingestion | SSIS, Talend, Informatica, Qlik Replicate, database jobs, scripts or another established integration tool |
| Staging | SQL Server database, file landing zone or governed local object storage |
| Core warehouse | SQL Server, Oracle, PostgreSQL or another supported relational engine |
| Data marts | Star schemas, materialized tables and governed SQL views |
| Qlik | Thin associative applications loading governed marts or controlled QVD outputs |
| Power BI | Power BI Report Server where an on-premises report portal is required, subject to its current feature set and licensing |
| Excel | Certified SQL views, controlled files, Power Query or approved Analysis Services-style interfaces |
| Governance | Database permissions, catalog and metadata processes, quality tables, audit logs, monitoring and change control |

The design can still include Git, automated deployment, data tests, lineage extraction, API contracts and observability.

### Valid reasons to remain on-premises

- contractual or regulatory data residency;
- sensitive workloads with strict internal control;
- unreliable or restricted external connectivity;
- low-latency integration with local operational systems;
- large existing investment and a skilled operating team;
- predictable workloads with effective current capacity;
- a transition strategy that does not justify immediate migration risk.

### On-premises responsibilities that cannot be ignored

The organization owns more of the platform:

- hardware and virtualization;
- operating systems and databases;
- patching and upgrades;
- capacity planning;
- backup and disaster recovery;
- network and identity integration;
- high availability;
- security monitoring;
- support and lifecycle management.

This operating effort must be included in the platform decision.

## Hybrid combinations can be deliberate

Hybrid architecture should not mean that every dataset is copied into every platform.

A valid hybrid pattern may be:

```text
On-premises operational systems
On-premises controlled extraction or replication
Cloud raw and transformation platform
Governed cloud data products
Qlik, Power BI and Excel consumption according to network and security needs
```

Another valid pattern may be:

```text
On-premises core warehouse remains authoritative
Selected cloud data product is published for advanced analytics or ML
Results return through a controlled contract
Core BI continues on the existing platform
```

A third pattern may use Fabric for Power BI and shared Microsoft consumption while an existing Snowflake or Databricks platform remains authoritative for transformation.

The architecture must define:

| Question | Required decision |
| --- | --- |
| Where is the authoritative record? | One named system or product layer |
| Which data crosses the boundary? | Explicit datasets, fields and classifications |
| Is the transfer copy, replication or virtual access? | Selected for latency, security and recovery needs |
| Where is history resolved? | One leading implementation |
| Where are quality gates evaluated? | One authoritative result with visible evidence |
| Which platform publishes the consumer contract? | Named owner and interface |
| What happens during network failure? | Defined retry, backlog and publication behavior |
| How is cost controlled? | Transfer, compute, storage and duplication monitored |
| How is decommissioning handled? | Exit criteria and removal of replaced paths |

A hybrid platform without these decisions becomes an expensive synchronization problem.

## Platform-specific strengths should remain platform-specific

A technology-open architecture does not mean pretending that all products are identical.

It means using platform strengths without moving shared business meaning into incompatible local implementations.

Examples:

| Platform-specific strength | Appropriate use | Boundary to protect |
| --- | --- | --- |
| Fabric Direct Lake and Power BI integration | Efficient Microsoft-native semantic consumption where suitable | Net revenue and customer history remain governed upstream |
| Snowflake virtual warehouses | Workload isolation and independent compute control | Separate compute does not create separate business definitions |
| Snowflake Dynamic Tables | Declarative SQL refresh pipelines | Do not duplicate the same rule in Tasks and another ETL job |
| Databricks Spark and Python | Complex engineering, streaming and ML preparation | Do not force simple BI rules into notebooks without need |
| Unity Catalog | Governed discovery and access for data and AI assets | Catalog registration does not replace product ownership |
| SQL Server relational engine | Stable dimensional modeling and SQL workloads | Do not treat database procedures as undocumented application code |
| Qlik associative engine | Exploratory analysis and selection behavior | Qlik-specific logic must not redefine shared facts |
| Power BI semantic model | DAX, filter context and Microsoft distribution | Semantic measures must build on approved factual amounts |
| Excel | Flexible operational and analytical work | Local formulas must not become the only authoritative rule |

## Typical anti-patterns

### The product-name architecture

```text
Source → Fabric → Power BI
```

This does not define raw recovery, history, quality, product contracts, security or ownership.

### Bronze, Silver and Gold without responsibilities

Three folders or schemas do not create a warehouse. Each layer needs explicit purpose, owner, inputs, outputs and controls.

### Rebuilding the same rule per platform

```text
SQL Server net revenue
Fabric net revenue
Snowflake net revenue
Databricks net revenue
Qlik net revenue
Power BI net revenue
Excel net revenue
```

A transition may temporarily contain parallel implementations. The target state needs one authoritative rule per product contract.

### Permanent dual running after migration

Parallel validation is useful before cutover. Permanent equal ownership creates cost, reconciliation work and ambiguity.

### Selecting a platform by maximum theoretical scale

A platform capable of extreme scale is not automatically the best fit for a moderate, stable workload. Complexity and operating cost remain real.

### Treating cloud as modernization by itself

Moving undocumented procedures, duplicated rules and uncontrolled reports into a cloud service preserves the architectural problem.

### Treating on-premises as automatically obsolete

Replacing a stable, governed warehouse without a business or operating benefit is not modernization.

### Turning Qlik into the integration platform

A shared Qlik extraction or QVD layer can be a valid Brownfield step. It should not become the only location for reusable business rules when Power BI, Excel, APIs or other consumers need the same truth.

### Treating Databricks as an on-premises distribution

Open-source components such as Delta Lake or Apache Spark can run outside Databricks. That does not make the resulting environment a Databricks deployment.

### Adding dbt without a responsibility boundary

If dbt and native transformations both build the same model, the organization has added another implementation rather than governance.

### Direct consumer access to raw data

Qlik, Power BI or Excel users should not need to understand source codes, incomplete history and failed records in order to create trustworthy reporting.

## Decision framework

Use the following sequence before selecting or changing a platform.

| Question | If yes | If no |
| --- | --- | --- |
| Can the existing platform meet the required grain, quality, latency and scale? | Improve the current design first | Evaluate alternatives |
| Is the main workload relational and SQL-centric? | Prefer a SQL-oriented implementation | Evaluate Python, Spark, streaming or specialized processing |
| Does the organization need integrated Microsoft analytics and Power BI behavior? | Fabric may reduce integration effort | Do not add Fabric only for status |
| Is independent cloud warehouse compute and data sharing a material need? | Snowflake may fit | Existing SQL or another platform may be sufficient |
| Are Spark, Python, streaming, data science and ML first-class workloads? | Databricks may fit | Avoid engineering complexity without a workload |
| Are on-premises residency or connectivity constraints binding? | Keep or design an on-premises/hybrid path | Cloud options remain open |
| Does dbt solve model collaboration, tests and deployment problems? | Add it with explicit ownership | Use native or existing transformation methods |
| Can the team operate the target platform after implementation? | Continue evaluation | Reduce scope, train or select a simpler platform |
| Can the migration be validated against business outputs? | Plan controlled cutover | Do not migrate yet |
| Is there a decommissioning path for replaced assets? | Include it in the roadmap | Risk of permanent duplication remains |

A compact scoring model can evaluate each candidate from 1 to 5 across:

```text
Business fit
Existing ecosystem fit
SQL fit
Python/Spark fit
BI integration
Streaming and ML fit
Governance fit
Security and residency fit
Team capability
Operating effort
Cost predictability
Migration effort
Exit and portability
```

The score does not choose the platform automatically. It makes assumptions visible and forces trade-offs to be discussed.

## Most important recommendations

1. Define the logical responsibilities before naming a platform.
2. Preserve one business grain, history rule, quality policy and owner across implementations.
3. Start with the capabilities already available and reliably operated.
4. Treat SQL Server and other on-premises warehouses as valid modern options when they meet the context.
5. Use Fabric when OneLake, Data Factory, Warehouse/Lakehouse and Power BI integration solve concrete requirements.
6. Choose between Fabric Lakehouse, Warehouse or both through workload boundaries rather than platform fashion.
7. Keep Power BI semantic logic focused on model and filter-context behavior.
8. Keep Qlik scripts thin and retain only justified Qlik-specific logic.
9. Give Excel a controlled contract instead of forcing users into manual shadow processes.
10. Use Snowflake when its cloud warehouse, workload isolation, SQL model and data-sharing capabilities create value.
11. Use Databricks when SQL, Spark, Python, streaming, engineering, data science or ML need a shared governed platform.
12. Do not describe Databricks as an on-premises open-source product.
13. Treat dbt as an optional project and governance layer, not as a prerequisite.
14. Use native platform features first when they meet the requirement and the team can operate them.
15. Avoid implementing the same business rule in native SQL, notebooks, dbt and BI tools at the same time.
16. Define the authoritative boundary in every hybrid architecture.
17. Persist quality evidence and publication status independently of dashboards.
18. Validate migrations with reconciled business outputs, not only successful jobs and row counts.
19. Include identity, network, monitoring, deployment, support and incident handling in the platform decision.
20. Compare total operating cost, including skills and migration, rather than license price alone.
21. Plan decommissioning before cutover so parallel implementations do not become permanent.
22. Reassess the platform when the workload or operating model changes materially — not whenever a new product feature is announced.

## Transition to the next part

Choosing a platform and mapping the logical architecture does not make the platform reliable by itself.

The next part, [Operating and Governing the Platform](/playbooks/operating-and-governing-the-platform), addresses ownership, deployment, observability, data quality, security, cost control, incident handling, lifecycle and the operational controls required to keep any of these implementations trustworthy in production.
