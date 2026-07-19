---
title: Transformation Options
description: How to choose between SQL, native platform features, notebooks, dataflows, stored procedures, classical ETL tools and dbt without turning a tool choice into the architecture.
category: Data Architecture
tags:
  - data-architecture
  - modern-data-warehouse
  - data-transformation
  - sql
  - stored-procedures
  - notebooks
  - dataflows
  - etl
  - elt
  - dbt
  - microsoft-fabric
  - snowflake
  - dynamic-tables
  - databricks
  - lakeflow
  - spark
  - python
  - qlik-sense
  - power-bi
  - excel
  - data-products
  - data-quality
  - data-governance
  - lineage
  - ci-cd
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 8
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start8-hero.png
---

## Transformation technology is a design choice, not the architecture

Every modern data platform needs transformations.

Source values must be standardized, records must be integrated, business keys must be resolved, history must be interpreted, quality rules must be applied and reusable data products must be published. None of those responsibilities automatically determines whether the implementation must use SQL, a stored procedure, a notebook, a dataflow, a native platform feature, a classical ETL tool or dbt.

The same valid business rule can be implemented in several technically sound ways:

```text
SQL view
Scheduled table build
Stored procedure
Low-code dataflow
Python or Spark notebook
Native declarative pipeline
Classical ETL mapping
dbt model and data test
```

The architecture becomes fragile when the organization starts with the tool instead of the responsibility.

A typical tool-first discussion sounds like this:

```text
“We have Fabric, therefore every transformation belongs in a notebook.”
“We use Snowflake, therefore every pipeline must be a Dynamic Table.”
“We introduced dbt, therefore every piece of SQL must be migrated.”
“The Qlik script already works, therefore it remains the master implementation.”
“The business user prefers a dataflow, therefore the rule belongs there permanently.”
```

Each statement may be reasonable for a specific workload. None is a general architectural rule.

Part 7, [One Data Product, Multiple Consumers](/playbooks/one-data-product-multiple-consumers), established that Qlik, Power BI, Excel, APIs and AI can consume the same governed business truth through different contracts. This part addresses the next question: **where should the transformation that creates that truth run?**

> **Choose the simplest transformation mechanism that satisfies the workload, team and governance requirements. Give every shared business rule one leading implementation and one accountable owner.**

## Architecture principle: one transformation responsibility, one leading implementation

A transformation architecture should first define responsibilities and only then assign technologies.

The central questions are:

```text
What business meaning is created?
At which grain is the result published?
Which source dependencies exist?
Who owns correctness?
How is the rule tested?
How is the implementation reviewed and versioned?
How is it scheduled and monitored?
Which consumers depend on it?
How are breaking changes communicated?
Where are failed records and quality evidence stored?
```

A useful classification is:

| Type of logic | Preferred architectural location |
| --- | --- |
| Shared cleansing and standardization | Governed transformation layer |
| Reusable business rules | One leading governed implementation |
| Key resolution and history | Governed core or data-product layer |
| Data-quality validation | Executable rule plus persistent result evidence |
| Platform-specific performance optimization | Native platform implementation, documented as technical behavior |
| Qlik associations, Section Access or Set Analysis | Thin Qlik consumer model where the behavior is genuinely Qlik-specific |
| Power BI filter-context and report measures | Semantic model where the behavior is genuinely model-specific |
| Excel layout, comments and controlled assumptions | Workbook or planning process, separated from governed actuals |
| Temporary exploration | Notebook or sandbox with an explicit promotion path |

The distinction prevents two opposite mistakes.

The first mistake is centralizing everything, including logic that only makes sense inside a specific analytical engine. The second is allowing every tool to implement its own version of shared business meaning.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start8-img1-en.png"
        alt="Several valid transformation methods including SQL, native platform features, notebooks, dataflows, dbt and stored procedures all producing governed tables, models and data products"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        There are many valid ways to transform data. The goal is not to standardize every workload on one tool, but to produce tested, reusable and governed data with clear ownership.
    </figcaption>
</figure>

## Many valid ways to transform data

The major transformation options overlap. They are not mutually exclusive, and most real platforms use more than one.

### SQL scripts, views and materialized tables

SQL is often the simplest starting point when the data already resides in a relational database, warehouse or SQL-accessible lakehouse.

Suitable uses include:

- joins, filters and aggregations;
- dimensional models and reporting views;
- deterministic business rules;
- data cleansing and standardization;
- materialized tables for repeatable performance;
- incremental processing where the platform supports suitable patterns;
- publication of stable consumer contracts.

Advantages:

- familiar skill set;
- execution close to the data;
- transparent query logic;
- low additional tooling cost;
- straightforward integration with schedulers and BI consumers.

Risks:

- uncontrolled chains of views;
- duplicated SQL in several jobs;
- weak deployment discipline;
- hidden dependencies when object references are not catalogued;
- platform-specific syntax that complicates later migration.

A SQL implementation is not automatically primitive. A small, tested set of views and scheduled tables can be more maintainable than a sophisticated toolchain that the team cannot operate reliably.

### Stored procedures and functions

Stored procedures are useful when the transformation requires controlled procedural execution, transaction handling, branching, reusable database-side routines or explicit step sequencing.

They can be appropriate for:

- complex merge behavior;
- batch-control logic;
- parameterized processing;
- operational error handling;
- database-native performance tuning;
- controlled publication procedures.

Their main risk is opacity. A large stored procedure can become a monolithic application inside the database. If procedures are used, they should still be versioned, reviewed, tested, documented and connected to observable run metadata.

### Native platform features

Modern platforms provide built-in transformation and orchestration capabilities. Examples include warehouse SQL, scheduled jobs, tasks, declarative tables, pipelines, materialized views, native quality expectations and platform monitoring.

Native features are often the best option when they:

- already satisfy the requirement;
- are understood by the operations team;
- integrate with security, scheduling and monitoring;
- reduce external infrastructure;
- provide the required performance and reliability.

Native-first does not mean native-only. A native feature should remain the leading implementation only while it continues to meet the functional and governance requirements.

### Dataflows and classical ETL tools

Low-code dataflows and established ETL tools remain valid transformation mechanisms.

They are particularly useful for:

- broad connector coverage;
- visual source-to-target mappings;
- standard operational integration;
- teams with strong ETL or Power Query skills;
- controlled file and API ingestion;
- workflows that combine movement, transformation and orchestration.

The architectural requirement is the same as for code: mappings need ownership, versioning, review, testing, deployment discipline and visible dependencies.

A visual canvas is not automatically self-documenting. Complex dataflows can become as difficult to understand as complex code when conventions and component boundaries are missing.

### Notebooks, Python and Spark

Notebooks are valuable for transformations that require Python, Spark, advanced libraries, complex data structures, machine-learning preparation or exploratory development.

They are well suited to:

- large-scale distributed transformations;
- semi-structured data processing;
- feature engineering;
- advanced algorithms;
- reusable Python libraries;
- batch and streaming workloads;
- investigation before a stable production implementation is selected.

A notebook becomes risky when its interactive form is mistaken for an operating model. Production notebooks need parameters, modular code, dependency management, automated tests, controlled environments, scheduling, monitoring and a clear owner.

### dbt

A dbt model is primarily a transformation model executed in the target data platform. dbt adds a structured project model around SQL and, depending on platform support, Python: dependencies, environments, materializations, tests, documentation, metadata and deployment workflows.

It can create significant value when the team needs:

- many interdependent SQL models;
- consistent project conventions;
- Git-based collaboration and review;
- automated data tests;
- generated documentation and lineage metadata;
- repeatable development and production environments;
- modular reuse through references and macros;
- CI/CD for analytics engineering.

It adds less value when:

- only a few stable SQL views exist;
- the team has no capacity to operate another runtime and repository;
- native platform development already provides sufficient review, tests and deployment;
- most transformations are Python, Spark, streaming or operational integration rather than SQL-centric modeling;
- dbt would duplicate an existing leading implementation.

The correct question is not “Do we use dbt?” It is “Which concrete problems would dbt solve better than the current method?”

## The simplest useful implementation

A governed transformation layer does not require a new cloud platform or a new transformation product.

A minimal implementation can use:

```flow linear vertical
Existing source extract or staging table
Existing SQL database or warehouse
One controlled transformation view or table build
One scheduled execution
One persistent quality-result table
One publication status
Qlik / Power BI / Excel consume the published result
```

For a Customer data product, the first version might contain:

```text
staging.customer_source
core.customer
quality.customer_rule_result
control.customer_publication
```

The transformation can initially be a view:

```sql
create view core.customer as
select
    trim(customer_id) as customer_id,
    upper(trim(country_code)) as country_code,
    cast(updated_at as timestamp) as updated_at,
    case when active_flag = 'Y' then 1 else 0 end as is_active,
    source_system,
    ingestion_timestamp
from staging.customer_source;
```

A separate quality step evaluates the publication rule:

```sql
select
    customer_id,
    'ACTIVE_CUSTOMER_REQUIRED_FIELDS' as rule_id,
    case
        when customer_id is null then 'MISSING_CUSTOMER_ID'
        when country_code is null then 'MISSING_COUNTRY'
        when updated_at is null then 'MISSING_UPDATED_AT'
        when updated_at < current_timestamp - interval '30' day then 'STALE_UPDATED_AT'
        else 'PASSED'
    end as rule_result
from core.customer
where is_active = 1;
```

The exact date arithmetic and data types vary by SQL dialect. The architectural pattern does not:

1. transform the source into a controlled customer structure;
2. evaluate the business rule explicitly;
3. persist failures and execution metadata;
4. publish only according to an agreed quality policy;
5. expose the result to consumers through a stable contract.

This implementation may be sufficient for years. Additional tools should be introduced only when scale, collaboration, testing, lineage, language or operating requirements justify them.

## Choose by workload and team

No transformation method is best across every workload.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start8-img2-en.png"
        alt="Decision matrix comparing SQL, native platform features, notebooks, dataflows, dbt and stored procedures by complexity, skills, governance, reuse, performance and operating effort"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The right choice depends on the workload, the languages already used by the team, collaboration requirements, expected scale, testing needs and the operating effort the organization can sustain.
    </figcaption>
</figure>

The decision should consider at least eight dimensions.

### 1. SQL share

If most transformations are relational, set-based and already execute in a warehouse, SQL views, scheduled tables, native SQL jobs or dbt are natural candidates.

A high SQL share does not automatically justify dbt. The deciding factors are the number of models, dependency complexity, development workflow and governance needs.

### 2. Python or Spark share

When transformations rely heavily on Python libraries, distributed processing, unstructured data or advanced algorithms, notebooks or native Python/Spark pipelines may be the clearer leading implementation.

dbt can coexist with that path for SQL-centric downstream models. It should not be forced to own workloads for which another engine and development model are more suitable.

### 3. Team size and ownership model

A small team with ten stable transformations may be well served by SQL and an existing scheduler.

A larger team with hundreds of models, several domains and frequent concurrent changes needs stronger conventions, isolated development environments, code review, automated testing and dependency visibility. dbt or a mature native development framework can then provide material value.

### 4. Git, review and CI/CD requirements

Transformation code should be versioned regardless of technology.

The real question is how much development workflow is needed:

- individual version history;
- pull requests and peer review;
- automated compilation and tests;
- environment promotion;
- rollback;
- deployment evidence;
- separation of developer and production credentials.

If the native platform already supports these requirements adequately, another layer may be unnecessary. If it does not, dbt or an external engineering workflow may close an important gap.

### 5. Low-code share

Dataflows are suitable when business technologists or ETL specialists need a visual development experience and the transformations remain understandable in that form.

Low-code should not mean low-governance. The organization still needs naming conventions, reusable components, reviews, deployment, run history and ownership.

### 6. Batch, near-real-time and streaming

A daily dimensional build has different requirements from a continuous stream.

- batch workloads can use views, scheduled SQL, procedures, dbt or ETL jobs;
- near-real-time workloads may use incremental jobs, platform tasks or declarative tables;
- streaming workloads usually require platform-native streaming or Spark-based processing;
- event-driven operational logic may require streams, triggers, queues or specialized services.

Do not use a batch-oriented transformation framework as the only answer to a streaming requirement.

### 7. Testing and documentation requirements

All approaches can be tested. The effort and built-in support differ.

A small SQL implementation can use custom assertions and a result table. dbt provides a standardized model for data tests and documentation. Native platforms may provide quality expectations, monitoring and lineage. ETL tools may offer validation components and run metadata.

The correct comparison is not whether testing is theoretically possible. It is whether the team can implement and operate the required level of evidence consistently.

### 8. Operating effort and cost

Every additional framework creates operating responsibilities:

```text
Runtime
Credentials
Repositories
Package and adapter versions
Deployment pipelines
Job scheduling
Monitoring
Incident handling
Training
Vendor or community support
```

A tool that improves development but creates an unsupported operating burden is not a sustainable architecture.

A compact comparison is:

| Option | Strong fit | Main caution |
| --- | --- | --- |
| SQL views and scripts | Relational transformations, small to medium model set, existing warehouse | View sprawl, duplicated scripts, weak deployment discipline |
| Materialized tables and procedures | Performance, controlled batches, procedural logic | Monoliths, opaque dependencies, database lock-in |
| Native platform features | Integrated scheduling, monitoring, security and scale | Platform coupling and feature-specific limitations |
| Dataflows and ETL tools | Connector-heavy integration, low-code teams, operational mappings | Visual complexity, weak reuse, proprietary deployment model |
| Notebooks and Python/Spark | Complex algorithms, large-scale processing, semi-structured data, streaming | Interactive code promoted without production engineering |
| dbt | SQL-centric model networks, Git review, tests, documentation and team collaboration | Added runtime, conventions and possible duplication of native capabilities |

## One transformation, one owner

The most damaging transformation problem is usually not a wrong tool. It is duplicated ownership.

A common anti-pattern looks like this:

```text
Dataflow        Replaces missing country with “DE”
Notebook        Replaces missing country with source-system default
Stored procedure Rejects the row
Qlik script     Maps missing country to “Unknown”
dbt model       Uses billing country as fallback
```

All five implementations may be technically reasonable. Together they create five different Customer definitions.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start8-img3-en.png"
        alt="A governed transformation pattern in which sources feed one owned leading implementation that publishes tested assets to Qlik, Power BI, Excel and other consumers"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        One leading transformation does not require one universal tool. It requires one authoritative implementation, a named owner, documented dependencies, persistent tests and clearly defined consumers.
    </figcaption>
</figure>

A leading implementation should define:

| Control | Required content |
| --- | --- |
| Business owner | Accountable person for the meaning and acceptance criteria |
| Technical owner | Team responsible for implementation and operation |
| Rule identifier | Stable name such as `ACTIVE_CUSTOMER_REQUIRED_FIELDS` |
| Input contract | Required source fields, grain and freshness |
| Output contract | Published fields, keys, grain and status behavior |
| Implementation location | View, procedure, notebook, dataflow, dbt model or native pipeline |
| Tests | Expected assertions, thresholds and failure behavior |
| Quality evidence | Persistent failing records and execution metadata |
| Dependencies | Upstream sources and downstream consumers |
| Change process | Review, versioning, communication and deprecation |
| Service expectation | Schedule, freshness, monitoring and recovery |

“One owner” does not mean that only one person may edit the transformation. It means there is one accountable ownership model and one implementation that is authoritative for the shared rule.

### Migrating from duplicated implementations

A safe consolidation pattern is:

```flow linear vertical
Inventory existing rule variants
Compare business outcomes and edge cases
Agree the authoritative definition
Select the leading implementation and owner
Build persistent tests
Run old and new results in parallel
Migrate consumers one by one
Remove or disable duplicate logic
Monitor the governed result
```

Do not leave the old Qlik mapping, notebook cell or Excel formula in place “for safety” after the governed replacement is accepted. Dormant duplicates tend to become active again during the next incident or urgent change.

## Native first or dbt?

Native-first and dbt-based transformation are both valid strategies.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start8-img4-en.png"
        alt="Decision guide comparing native platform transformation capabilities with dbt and showing when dbt adds clear value through modular SQL, tests, documentation, lineage and version-controlled collaboration"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Start with capabilities that already solve the problem. Add dbt when modular SQL development, dependency management, tests, documentation and collaborative delivery create enough value to justify the additional operating model.
    </figcaption>
</figure>

### Native-first is a strong choice when

- the platform already provides suitable SQL, tables, pipelines and scheduling;
- the transformation set is small or moderately complex;
- the team understands the native operating model;
- security and monitoring are already integrated;
- deployment requirements are adequately covered;
- most logic is platform-specific or tightly connected to native streaming and processing;
- adding another runtime would not materially improve quality or delivery speed.

Examples include:

- an existing SQL Server warehouse with views, procedures and SQL Agent or another scheduler;
- Fabric Warehouse SQL combined with pipelines, Dataflow Gen2 or notebooks where required;
- Snowflake SQL with Dynamic Tables or Streams and Tasks for suitable workloads;
- Databricks SQL or Python with native jobs and Lakeflow pipelines;
- a classical ETL platform already operated reliably by the team.

### dbt adds clear value when

- the transformation layer is primarily SQL-centric;
- the number of models and dependencies is growing;
- several engineers change the same project;
- pull requests and automated checks are required;
- tests and documentation should follow a shared convention;
- development, test and production environments must be repeatable;
- lineage and model metadata are needed across a large transformation graph;
- modular references and macros reduce repeated SQL;
- the team has the capacity to operate dbt as a product, not only install it.

### dbt does not remove platform responsibilities

The target data platform still performs the computation. The team still needs to understand:

- warehouse sizing and cost;
- platform SQL dialect;
- physical design and performance;
- access control;
- scheduling and job reliability;
- source ingestion;
- streaming and operational integration;
- incident management;
- data-product publication.

Portability also needs precision. dbt can reduce coupling by separating project structure and dependency logic from DDL orchestration, but SQL functions, data types, incremental strategies and performance settings still differ between platforms. Migration is usually easier, not automatic.

### Avoid the half-migration anti-pattern

A common failure mode is introducing dbt without deciding what it owns:

```text
Some models are in dbt
Some are still created by procedures
A notebook overwrites one dbt target
A dataflow applies another correction
Qlik repeats the final business rule
No component is clearly authoritative
```

A dbt adoption should define explicit boundaries:

```text
dbt owns              SQL-centric curated and data-product models
dbt does not own      Source ingestion, streaming or Python-heavy processing unless explicitly designed
native platform owns  Compute, storage, security, scheduling integration and platform operations
consumer tools own    Tool-specific semantics and presentation only
```

The boundary can be different. It cannot be implicit.

## Concrete example: every active customer must be publishable

Assume the organization defines this rule:

> **Every active customer requires a Customer ID, a country and a modification timestamp inside the agreed freshness window.**

For the example, the freshness window is 30 days. In a real data product, the threshold should be configurable and approved by the Customer Data Owner.

### Business contract

| Element | Definition |
| --- | --- |
| Grain | One row per source customer version |
| Active condition | `active_flag = 'Y'` |
| Required identifier | `customer_id` is not null and not blank |
| Required country | `country_code` is not null and follows the approved code list |
| Freshness | `updated_at` is not null and no older than 30 days |
| Rule ID | `ACTIVE_CUSTOMER_REQUIRED_FIELDS` |
| Failure handling | Persist the record, reason and execution metadata |
| Publication behavior | Warn, quarantine or block according to the agreed severity |
| Owner | Customer Data Owner |
| Consumers | Customer data product, Sales product, Qlik, Power BI, Excel and APIs |

The implementation technology may differ. The output meaning must remain identical.

### Option 1: SQL view and scheduled quality table

The transformation view standardizes the fields:

```sql
create view curated.customer as
select
    nullif(trim(customer_id), '') as customer_id,
    upper(nullif(trim(country_code), '')) as country_code,
    cast(updated_at as timestamp) as updated_at,
    case when active_flag = 'Y' then 1 else 0 end as is_active,
    source_system,
    source_record_id,
    ingestion_timestamp
from staging.customer_source;
```

A scheduled statement persists failures:

```sql
insert into quality.customer_rule_result (
    execution_id,
    rule_id,
    source_system,
    source_record_id,
    customer_id,
    failure_reason,
    evaluated_at
)
select
    :execution_id,
    'ACTIVE_CUSTOMER_REQUIRED_FIELDS',
    source_system,
    source_record_id,
    customer_id,
    case
        when customer_id is null then 'MISSING_CUSTOMER_ID'
        when country_code is null then 'MISSING_COUNTRY'
        when updated_at is null then 'MISSING_UPDATED_AT'
        when updated_at < current_timestamp - interval '30' day then 'STALE_UPDATED_AT'
    end,
    current_timestamp
from curated.customer
where is_active = 1
  and (
      customer_id is null
      or country_code is null
      or updated_at is null
      or updated_at < current_timestamp - interval '30' day
  );
```

This is a valid implementation when the existing database, scheduler and monitoring are sufficient.

### Option 2: Microsoft Fabric

Several Fabric implementations can be valid:

| Workload | Possible leading implementation |
| --- | --- |
| Relational warehouse transformation | Fabric Warehouse T-SQL view or scheduled table build |
| Low-code shaping and connector-heavy ingestion | Dataflow Gen2 |
| Python, Spark or complex file processing | Notebook |
| Multi-step orchestration | Fabric pipeline invoking SQL, stored procedures, dataflows or notebooks as appropriate |

A minimal Warehouse SQL pattern can use the same view and quality-table logic as the generic SQL example, adapted to the supported T-SQL syntax.

A Dataflow Gen2 implementation can standardize `customer_id`, `country_code` and `updated_at`, then route valid rows to the curated destination and persist rejected rows to a quality destination. A notebook is appropriate when the rule is part of a wider Spark transformation or requires Python libraries.

The architectural control is not the Fabric item type. It is that one item is declared authoritative, its code or definition is versioned, the quality result is persisted and Qlik or Power BI does not repeat the rule.

### Option 3: Snowflake

Snowflake can implement the rule using ordinary SQL views and tables, a Dynamic Table for declarative refresh behavior, or Streams and Tasks when explicit CDC and procedural control are required.

A conceptual Dynamic Table implementation is:

```sql
create or replace dynamic table curated.active_customer
    target_lag = '30 minutes'
    warehouse = transform_wh
as
select
    nullif(trim(customer_id), '') as customer_id,
    upper(nullif(trim(country_code), '')) as country_code,
    updated_at,
    source_system,
    source_record_id,
    case
        when customer_id is null then 'MISSING_CUSTOMER_ID'
        when country_code is null then 'MISSING_COUNTRY'
        when updated_at is null then 'MISSING_UPDATED_AT'
        when updated_at < dateadd(day, -30, current_timestamp()) then 'STALE_UPDATED_AT'
        else 'PASSED'
    end as quality_status
from staging.customer_source
where active_flag = 'Y';
```

A separate quality publication step can persist failed records with the execution and rule metadata required by the governance model.

Dynamic Tables are attractive for declarative table pipelines. Streams and Tasks remain useful when the process needs explicit change tracking, triggered processing, task graphs or custom procedural steps. The choice should follow the workload rather than a platform-wide mandate.

### Option 4: Databricks

Databricks can implement the rule in SQL, Python or a Lakeflow declarative pipeline.

A SQL implementation may create a curated table or materialized view. A PySpark implementation is useful when the customer transformation is part of a larger distributed pipeline:

```python
from pyspark.sql import functions as F

customer = (
    spark.table("staging.customer_source")
    .select(
        F.when(F.trim("customer_id") == "", None)
         .otherwise(F.trim("customer_id"))
         .alias("customer_id"),
        F.upper(F.when(F.trim("country_code") == "", None)
         .otherwise(F.trim("country_code")))
         .alias("country_code"),
        F.col("updated_at").cast("timestamp").alias("updated_at"),
        (F.col("active_flag") == "Y").alias("is_active"),
        "source_system",
        "source_record_id"
    )
)
```

The quality status can then be derived in SQL or Python and written to a Delta table such as `quality.customer_rule_result`.

Lakeflow declarative pipelines are suitable when the organization wants a managed declarative framework for batch or streaming tables and flows in SQL or Python. A normal notebook or scheduled job may still be simpler for a small stable workload.

### Option 5: dbt model and tests

A dbt implementation can place the transformation in one SQL model:

```sql
-- models/curated/customer.sql

select
    nullif(trim(customer_id), '') as customer_id,
    upper(nullif(trim(country_code), '')) as country_code,
    cast(updated_at as timestamp) as updated_at,
    case when active_flag = 'Y' then true else false end as is_active,
    source_system,
    source_record_id,
    ingestion_timestamp
from {{ source('staging', 'customer_source') }}
```

Standard tests can validate structural expectations:

```yaml
version: 2

models:
  - name: customer
    columns:
      - name: customer_id
        data_tests:
          - not_null:
              config:
                where: "is_active = true"
      - name: country_code
        data_tests:
          - not_null:
              config:
                where: "is_active = true"
      - name: updated_at
        data_tests:
          - not_null:
              config:
                where: "is_active = true"
```

A singular data test can express the complete freshness rule:

```sql
-- tests/active_customer_required_fields.sql

select *
from {{ ref('customer') }}
where is_active = true
  and (
      customer_id is null
      or country_code is null
      or updated_at is null
      or updated_at < current_timestamp - interval '30' day
  )
```

Because a test failure alone is not always sufficient operational evidence, a separate audit model can persist failing rows in the governed quality schema:

```sql
-- models/quality/customer_rule_result.sql

select
    'ACTIVE_CUSTOMER_REQUIRED_FIELDS' as rule_id,
    source_system,
    source_record_id,
    customer_id,
    current_timestamp as evaluated_at
from {{ ref('customer') }}
where is_active = true
  and (
      customer_id is null
      or country_code is null
      or updated_at is null
      or updated_at < current_timestamp - interval '30' day
  )
```

The exact SQL remains adapter- and platform-dependent. dbt contributes dependency management, testing conventions, documentation and deployment workflow. It does not change the business definition.

### Option 6: dataflow or classical ETL mapping

The same rule can be expressed visually:

```flow linear vertical
Read customer source
Trim Customer ID
Normalize country code
Cast modification timestamp
Filter active customers
Derive failure reason
Write valid records to curated customer
Write failed records to quality result
Write execution status to control table
```

This is fully valid when the mapping is versioned, reviewed, tested, monitored and owned. The visual implementation must not be followed by another undocumented correction in a notebook or Qlik script.

## One result across all implementations

Regardless of technology, the published contract should be comparable:

```text
customer_id
country_code
updated_at
is_active
quality_status
quality_rule_id
source_system
source_record_id
publication_timestamp
```

For the same input record, every valid implementation must produce the same result.

Example:

```text
Input
customer_id       = C-10042
country_code      = null
updated_at        = 2026-07-10 09:30:00
active_flag       = Y

Expected result
quality_status    = FAILED
quality_rule_id   = ACTIVE_CUSTOMER_REQUIRED_FIELDS
failure_reason    = MISSING_COUNTRY
publication       = according to agreed severity policy
```

Parallel comparison of outputs is the most reliable method when changing implementation technology.

## Platform patterns without mandatory stacks

The available platform should influence the implementation, but not redefine the architecture.

| Existing environment | Pragmatic starting point | Possible extension |
| --- | --- | --- |
| Classical SQL warehouse | Views, procedures, scheduled table builds, quality tables | Git deployment, stronger orchestration, dbt if model collaboration grows |
| Microsoft Fabric | Warehouse SQL, Dataflow Gen2, pipelines or notebooks according to workload | dbt for a larger SQL-centric model estate if it adds clear workflow value |
| Snowflake | SQL views/tables, Dynamic Tables, Streams and Tasks | dbt for modular SQL modeling, tests and collaboration |
| Databricks | SQL, notebooks, jobs or Lakeflow declarative pipelines | dbt for SQL-centric curated and mart models alongside Python/Spark processing |
| Existing ETL suite | Controlled mappings and jobs | Code-based transformations where testing, reuse or scale justifies them |
| Qlik-centric brownfield | Central SQL or governed QVD transformation layer as an interim leading implementation | Move shared logic toward a platform-neutral data product while keeping Qlik apps thin |
| Small on-premises environment | Existing database, scheduler and file layer | Add components only when operating requirements exceed current capabilities |

No row in this table implies a mandatory target stack.

## Qlik, Power BI and Excel remain consumers

This part is about transformation ownership, not about banning transformation features inside BI tools.

A thin Qlik application can still:

- load the published Customer data product;
- create required associations;
- implement Section Access with governed entitlement data;
- use Set Analysis for selection-aware comparisons;
- optimize fields for the associative model.

A Power BI semantic model can still:

- define relationships and hierarchies;
- add DAX measures that depend on filter context;
- apply row-level security;
- provide report-specific time intelligence.

An Excel workbook can still:

- shape a controlled output for usability;
- provide PivotTables, formatting and comments;
- maintain clearly separated planning assumptions.

None of those consumers should independently decide that an active customer may omit a country or use a different freshness threshold.

## Typical anti-patterns

### Tool-first architecture

The team selects the transformation tool before it understands the workload, ownership, quality and operating requirements.

### The same rule in several tools

A dataflow, notebook, procedure, dbt model and Qlik script each implement a variant of the same customer or revenue rule.

### Notebook as undocumented production application

An exploratory notebook is scheduled unchanged, depends on hidden state and has no tests, package control or owner.

### Endless view chains

Views call views that call other views until lineage and performance become impossible to reason about.

### Monolithic stored procedure

One procedure performs ingestion, cleansing, history, quality, publication and consumer-specific output with no modular boundaries.

### Low-code without engineering discipline

A visual process is treated as self-documenting and is changed directly in production without review or deployment control.

### dbt as a policy instead of a solution

Every transformation is migrated to dbt even when native features, Python or existing SQL are simpler and better supported.

### dbt plus native duplication

The dbt model exists, but a native pipeline still recreates the same business table because ownership was never clarified.

### Tests only inside dashboards

Quality is observed as a red KPI in Power BI or Qlik, but failed records and rule executions are not persisted centrally.

### Silent rejection

Invalid rows are filtered out without a failure reason, rule identifier, owner or recovery process.

### “One owner” only on paper

A role is named in documentation, but no team is accountable for deployment, monitoring, incidents and change communication.

### Consumer leakage

A temporary correction in Qlik, Power Query or Excel becomes the permanent implementation of shared business meaning.

## Decision aid

Use these questions in sequence:

| Question | Decision implication |
| --- | --- |
| Can the existing SQL platform implement the rule clearly and reliably? | Start with SQL before adding another framework |
| Is the workload predominantly relational and set-based? | Prefer SQL, native warehouse features or dbt |
| Is Python, Spark or semi-structured processing essential? | Prefer notebooks or native Python/Spark pipelines |
| Does the team need visual low-code development? | Consider dataflows or established ETL tooling |
| Are there many dependent SQL models and frequent concurrent changes? | dbt or a mature native code framework may add value |
| Are Git review, CI/CD, tests and generated documentation major gaps? | Select a framework that closes those gaps explicitly |
| Is streaming or event-driven processing required? | Use a platform designed for streaming rather than forcing a batch pattern |
| Is the rule already implemented elsewhere? | Consolidate ownership before creating another version |
| Will the proposed tool reduce duplication? | Continue only if the ownership boundary is explicit |
| Will the tool create new unsupported operations? | Reconsider or provide an operating model first |
| Can failed records and rule results be persisted? | Do not publish the design until evidence is defined |
| Can Qlik, Power BI and Excel consume the result without rebuilding the rule? | The transformation contract is sufficiently reusable |

A compact rule is:

```text
Existing capability solves the problem        → use it
Native feature improves operation             → use it deliberately
SQL model network needs collaboration          → consider dbt
Python/Spark is intrinsic to the workload      → use notebook or native pipeline
Low-code is the maintainable team interface    → use dataflow or ETL tool
Shared rule already exists                     → reuse it, do not duplicate it
No clear owner or test evidence                → architecture is not ready
```

## A practical adoption path

Transformation modernization should not start by migrating every job.

Start with one business rule that is duplicated, important and measurable.

```flow linear vertical
Select one high-value rule
Document existing variants
Define the authoritative business outcome
Choose the simplest leading implementation
Assign business and technical ownership
Persist quality evidence
Validate in parallel
Migrate consumers
Retire duplicate implementations
Measure delivery and quality improvement
```

Good first candidates are:

- active-customer eligibility;
- cancellation handling;
- currency normalization;
- product hierarchy assignment;
- customer-country mapping;
- invoice status classification;
- one base revenue definition.

The goal of the first migration is not to prove that one tool is superior. It is to prove that one governed implementation can replace several inconsistent ones.

## Most important recommendations

1. Define the transformation responsibility before selecting the tool.
2. Give every shared business rule one leading implementation and one accountable owner.
3. Start with existing SQL, scheduling and platform capabilities when they solve the problem adequately.
4. Treat SQL views, materialized tables and stored procedures as valid modern options when they are versioned, tested and observable.
5. Use dataflows and classical ETL tools where connector coverage, low-code development or operational mapping creates real value.
6. Use notebooks and Python/Spark for workloads that genuinely require their language, libraries or scale.
7. Do not promote an exploratory notebook to production without modularization, testing, environment control and monitoring.
8. Use native platform features when they simplify security, orchestration, monitoring and performance.
9. Add dbt when SQL-model modularity, dependency management, Git review, tests, documentation and team collaboration justify the added operating model.
10. Do not introduce dbt as a mandatory layer for every workload.
11. Define explicit ownership boundaries when dbt and native platform transformations coexist.
12. Persist data-quality failures and execution metadata outside dashboards.
13. Validate technology migrations by comparing business outputs, not only row counts and job success.
14. Keep Qlik scripts, Power BI transformations and Excel formulas free from duplicated shared business rules.
15. Retain tool-specific consumer logic only where the analytical or operational engine requires it.
16. Avoid silent filtering of failed records; use explicit severity, quarantine and publication policies.
17. Version transformation code, dependencies, tests and contracts regardless of implementation technology.
18. Include runtime, credentials, monitoring, skills and incident handling in every tool decision.
19. Prefer a small number of well-operated patterns over a broad catalogue of theoretically supported tools.
20. Reassess the implementation when workload, team size or governance requirements materially change.

## Transition to the next part

Choosing a transformation mechanism solves only one part of the platform design. The same architectural responsibilities may still need to be implemented across different technology estates.

The next part, [One Architecture – Multiple Platforms](/playbooks/one-architecture-multiple-platforms), shows how the same governed warehouse architecture can be mapped to Microsoft Fabric, Snowflake, Databricks, classical SQL and hybrid or on-premises environments without turning one product stack into a universal requirement.
