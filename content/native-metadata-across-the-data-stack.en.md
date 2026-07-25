---
title: Native Metadata Across the Data Stack — Understand What Each Product Already Knows
description: A practical method for inventorying schema, lineage, semantic, operational, usage, security and AI metadata across modern data platforms before adding another catalog or governance tool.
category: Data Governance
tags:
  - metadata
  - native-metadata
  - metadata-governance
  - data-catalog
  - data-lineage
  - snowflake
  - databricks
  - microsoft-fabric
  - dbt
  - qlik
  - power-bi
  - tableau
  - kafka
  - mlflow
  - data-observability
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 9
seriesTitle: MetaData Deep Dive
hero: images/playbooks/native-metadata-across-the-data-stack-hero.png
---

## Every platform already knows something important

Organizations frequently start a metadata initiative by evaluating catalogs, governance suites or active-metadata platforms.

That sequence is backwards.

Before another platform is selected, the organization should identify which metadata already exists, where it is authoritative, how it can be extracted and which gaps are genuinely unresolved.

A modern data stack already contains many partial metadata systems:

- an operational application knows its field labels, process states and source constraints;
- a database knows its schemas, objects, privileges and query activity;
- a lakehouse knows tables, files, notebooks, jobs and runtime lineage;
- transformation code knows models, dependencies, tests and documentation;
- an orchestrator knows schedules, runs, retries and failures;
- a BI platform knows measures, dimensions, reports and user-facing semantics;
- an identity platform knows users, groups, roles and access events;
- a streaming platform knows topics, partitions, offsets and schema versions;
- an observability system knows checks, incidents and freshness evidence;
- an AI platform knows datasets, experiments, parameters, metrics and models.

Each platform has a local perspective. None normally understands the complete enterprise context.

A warehouse may know that `analytics.fct_sales_order_line.net_sales_amount` is a decimal column queried by several users. It may not know whether the value represents ordered, invoiced or recognized revenue. A BI model may know the approved measure expression and presentation format, but not the original operational status transition that determined whether a row was eligible. An identity platform can prove that a group received access, but not whether the underlying dataset was the correct source for a management KPI.

> **A good metadata architecture begins by inventorying native capabilities, interfaces, authority and gaps. An additional tool should connect missing context and cross-system relationships—not recreate metadata that another platform already maintains correctly.**

The first deliverable is therefore not a vendor shortlist. It is a native metadata inventory.

## Separate the metadata dimensions before comparing products

A product should not be classified as “metadata-rich” or “metadata-poor” without specifying the metadata dimension.

Seven dimensions cover most enterprise needs.

### Schema metadata

Schema metadata describes implemented structures:

```text
catalog
schema
table
view
file
topic
column
data type
nullability
key
constraint
partition
format
```

Databases, warehouses, lakehouses, schema registries and semantic models usually know this dimension well.

### Lineage metadata

Lineage metadata describes dependency and movement:

```text
source asset
transformation or process
target asset
column mapping
runtime observation
code reference
execution identifier
lineage confidence
```

Transformation tools, query engines, orchestrators, streaming platforms and BI systems may each contribute different lineage segments.

### Semantic metadata

Semantic metadata describes meaning and analytical behaviour:

```text
business definition
measure
dimension
calculation
aggregation
grain
filter behaviour
format
synonym
approved use
```

Semantic layers and BI platforms often contain the most complete user-facing semantics. A central catalog that ignores this layer can miss the definitions people actually use.

### Operational metadata

Operational metadata describes execution:

```text
schedule
run
duration
status
retry
checkpoint
refresh
failure
deployment
environment
```

Orchestrators, transformation platforms, stream processors, warehouses and observability tools are usually authoritative for this evidence.

### Usage metadata

Usage metadata describes observed consumption:

```text
query
report view
dashboard access
model invocation
consumer group activity
popular asset
unused asset
last accessed
```

Usage can be distributed across engines, BI platforms, identity logs and application telemetry. It is rarely complete in one place.

### Security metadata

Security metadata describes identity and control:

```text
user
group
role
grant
entitlement
classification
policy
masking rule
audit event
access decision
```

Identity platforms, data engines, BI platforms and governance systems each hold different parts of the security model.

### AI metadata

AI metadata describes data-science and model lifecycle context:

```text
training dataset
feature
experiment
run
parameter
metric
artifact
model version
evaluation
prompt
deployment
```

Experiment trackers and model registries are strong in this dimension, but usually weak in enterprise business vocabulary and policy interpretation.

These dimensions overlap. They should remain distinguishable because ownership, extraction methods, freshness and control requirements differ.

## Product categories know different parts of the truth

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/native-metadata-across-the-data-stack-img1-en.png"
        alt="Seven product categories connected to the metadata dimensions they know best: operational systems, databases and warehouses, lakehouse and streaming, transformation and orchestration, semantic and BI, identity and security, and AI and data science"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Native metadata is distributed by design. Each product category observes the objects and activities inside its own operating boundary.
    </figcaption>
</figure>

### Operational systems know original business behaviour

CRM, ERP, finance, service and logistics applications often know:

- original labels and help text;
- source identifiers;
- valid codes and status values;
- process transitions;
- validation constraints;
- local ownership;
- source-of-record responsibility;
- creation and update timestamps;
- operational audit events.

Their weakness is usually extraction consistency.

Some applications expose structured APIs, dictionaries or configuration exports. Others expose only database schemas, administration screens or proprietary metadata endpoints. A copied database table may reveal field names and types while hiding the process semantics configured in the application.

The source owner should be the application team or accountable business process owner—not the central catalog team.

### Databases and warehouses know implemented structures and engine activity

Relational databases and cloud warehouses usually know:

- catalogs, schemas, tables, views and columns;
- data types, keys and constraints;
- comments or extended properties;
- owners, roles and grants;
- object dependencies;
- statistics and storage properties;
- query or access history;
- engine-specific policies and tags.

Typical interfaces include:

- `INFORMATION_SCHEMA`;
- system catalog views;
- account-usage schemas;
- SQL functions;
- audit logs;
- REST APIs;
- JDBC or ODBC metadata;
- platform event streams.

The engine is authoritative for current physical implementation. It is not automatically authoritative for business meaning.

### Lakehouse and streaming platforms know files, tables, events and processing state

Lakehouse platforms can connect:

- files and managed tables;
- notebooks and jobs;
- catalogs and schemas;
- table versions;
- access controls;
- runtime operations;
- data and model lineage.

Streaming platforms typically know:

- topics;
- partitions;
- offsets;
- brokers;
- consumer groups;
- retention and configuration;
- producer and consumer activity.

A broker does not necessarily know the business meaning of an event payload. Schema Registry, data contracts, topic descriptions and producer ownership may be required to add that context.

### Transformation and orchestration know code and execution

Transformation tools know:

- source declarations;
- models;
- dependencies;
- tests;
- documentation;
- compiled or executed SQL;
- deployment environments;
- run results.

Orchestrators know:

- workflows or DAGs;
- tasks;
- schedules;
- dependencies;
- retries;
- runs;
- failures;
- duration;
- execution parameters.

The transformation repository should remain authoritative for implemented business logic. The orchestrator should remain authoritative for execution state. A central platform can connect both views without copying code into prose.

### Semantic and BI platforms know consumption semantics

Qlik, Power BI, Tableau and similar tools may know:

- fields;
- associations or relationships;
- dimensions;
- measures;
- calculations;
- semantic models;
- data sources;
- applications, reports and dashboards;
- publication state;
- usage and ownership;
- user-facing lineage.

This layer may contain business logic that does not exist in the warehouse or transformation repository.

A measure such as `Recognized Revenue YTD` can exist only in a semantic model. A Qlik expression can combine selections, set analysis and aggregation behaviour. A Power BI measure can encode DAX logic and filter context. A Tableau calculated field can carry a consumer-facing definition that is invisible in the source database.

Ignoring BI metadata creates a false belief that the governed data model ends at the warehouse.

### Identity and security platforms know people and entitlements

Identity platforms know:

- users;
- groups;
- service principals;
- roles;
- memberships;
- entitlements;
- authentication events;
- provisioning;
- administrative changes;
- access reviews.

They are authoritative for identity state. They normally do not know why a dataset exists or what a measure means.

Security metadata must also be protected. Group memberships, audit details and access paths can themselves be sensitive.

### AI and data-science platforms know experiments and model lifecycle

MLflow and similar platforms can record:

- experiments and runs;
- parameters and metrics;
- code references;
- artifacts;
- datasets;
- model versions;
- aliases and tags;
- evaluations;
- deployment context.

This evidence supports reproducibility and model governance.

It does not automatically establish whether the training dataset was approved for the intended purpose, whether the target variable is conceptually valid or whether a feature definition matches the enterprise vocabulary. Those links must be added through governance metadata.

## Compare platform families by capability category, not one volatile checklist

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/native-metadata-across-the-data-stack-img2-en.png"
        alt="Capability-category comparison of Snowflake, Databricks, Microsoft Fabric and a classical SQL warehouse across catalogs, descriptions, grants, history, lineage, quality evidence, APIs and external context gaps"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Platform families overlap, but their metadata depth, scope, retention, interfaces and licensing conditions differ. Exact current capabilities must be verified before implementation.
    </figcaption>
</figure>

A capability-category comparison is more durable than a matrix of green and red checkmarks.

| Capability category | Snowflake | Databricks | Microsoft Fabric | Classical SQL warehouse |
| --- | --- | --- | --- | --- |
| Catalogs and schemas | Strong account and database object metadata | Strong Unity Catalog object model when workloads are governed there | Workspace, item, OneLake and semantic-model metadata | Strong database-local catalogs; enterprise scope varies |
| Object descriptions | Comments, tags and object properties | Comments, tags, properties and catalog context | Item descriptions, labels, endorsements and selected sub-item metadata | Comments or extended properties depend on engine and discipline |
| Access and grants | Roles, privileges, policies and access evidence | Unity Catalog privileges, ownership and audit/system evidence | Workspace/item permissions, tenant and item governance context | Roles, grants and audit features vary by engine |
| Query or operation history | Warehouse and account history views | Query, audit, job and system tables depending on service | Activity, refresh, capacity and item operation evidence across services | Query Store, audit, logs or extensions vary |
| Lineage availability | Object dependencies and access-derived relationships; coverage is workload-dependent | Runtime lineage for supported Unity Catalog workloads; external coverage requires validation | Native item lineage plus scanner and Purview integration; depth varies by item type | Usually limited without parsing, instrumentation or external tools |
| Data-quality evidence | Constraints, profiling results and partner or custom checks | Expectations, constraints, monitoring and external quality systems | Data-quality capabilities and evidence vary by Fabric/Purview component | Constraints and custom checks; often fragmented |
| APIs and export | SQL views, functions, drivers and APIs | Information schema, system tables and REST APIs | Fabric REST APIs, scanner APIs and service-specific interfaces | SQL catalogs, drivers, logs and engine-specific APIs |
| External context gaps | Enterprise vocabulary, cross-platform semantics and non-Snowflake lineage | Non-Databricks semantics, source process meaning and external consumption context | External systems, detailed cross-tool lineage and domain definitions | Enterprise search, semantic context and cross-system relationships |

This table does not declare a winner. It identifies where an inventory must look.

### Snowflake

Snowflake exposes broad technical and operational metadata through Information Schema and Account Usage views. Object metadata, dependencies, query history, access history, tags and policy references can be queried through supported SQL interfaces.

Important qualifications remain:

- view latency and retention differ;
- some capabilities depend on edition or configuration;
- object dependencies do not represent every form of data movement;
- query-derived lineage and declared dependencies are different evidence types;
- business definitions and cross-platform semantics still require external context.

The right inventory question is not “Does Snowflake have metadata?” It is:

```text
Which Snowflake views are enabled,
which environments are covered,
what is the retention,
who owns extraction,
and which relationships remain outside Snowflake?
```

### Databricks

Databricks with Unity Catalog provides catalogs, schemas, tables, columns, permissions and governance context. Information Schema, REST APIs and system tables support programmatic extraction. Native lineage can capture relationships for supported workloads and can be queried through lineage system tables.

Coverage must still be assessed:

- objects outside Unity Catalog can have different visibility;
- external systems may not appear automatically;
- notebook, job, model and table relationships have different scopes;
- preview interfaces should not be treated as stable contracts without review;
- business vocabulary and BI semantics remain external unless deliberately linked.

Unity Catalog can be a strong source-native governance layer. It does not remove the need for enterprise identity resolution across platforms.

### Microsoft Fabric

Microsoft Fabric distributes metadata across workspaces, OneLake, Lakehouse, Warehouse, Data Factory, Real-Time Intelligence, semantic models, reports and administrative services.

The OneLake catalog supports discovery within Fabric. Metadata scanner APIs can retrieve tenant, workspace, item and selected semantic-model subartifact metadata when required tenant settings and permissions are configured. Fabric and Microsoft Purview can exchange metadata and lineage, but granularity varies by item type and current product support.

An inventory must therefore distinguish:

```text
Fabric item metadata
Power BI semantic-model metadata
OneLake object metadata
lineage shown in Fabric
metadata returned by scanner APIs
metadata available to Purview
activity and audit evidence
```

Treating all of these as one “Fabric metadata API” hides important differences.

### Classical SQL warehouse

A classical SQL warehouse or relational database typically provides durable system catalogs, Information Schema, roles, grants, constraints, statistics and engine logs. SQL Server, for example, adds catalog views, extended properties, Query Store and audit capabilities.

The main gap is usually cross-system context.

A database can describe its own objects very well while knowing little about:

- upstream operational applications;
- transformations executed elsewhere;
- BI measures;
- report usage;
- enterprise vocabulary;
- model-training use;
- stewardship decisions.

A lightweight central index can therefore add substantial value even when the database itself already exposes strong metadata.

## Transformation and BI metadata must be connected, not flattened

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/native-metadata-across-the-data-stack-img3-en.png"
        alt="Connected zones for dbt and code repositories, orchestration, Qlik Power BI and Tableau, and a catalog or governance platform, showing where transformation logic, runtime evidence and user-facing semantics reside"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Business logic can live in transformation code, orchestration parameters or the consumption layer. Central governance should connect these sources while preserving their authority.
    </figcaption>
</figure>

### dbt and code repositories

A dbt project produces a particularly useful metadata package.

The project and generated artifacts can describe:

- models and sources;
- tests;
- macros;
- exposures;
- semantic models and metrics;
- dependencies;
- descriptions;
- ownership conventions;
- compiled properties;
- run results;
- source freshness.

`manifest.json` represents project resources and relationships. Other artifacts add catalog details and execution evidence.

Git contributes a different dimension:

- commit identifier;
- author and committer;
- timestamp;
- branch and tag;
- change history;
- pull-request or review context when supplied by the hosting platform.

Git proves which code changed and when. It does not prove that the business definition is correct.

The correct pattern is:

```text
dbt resource
→ linked to source tables
→ linked to Git revision
→ linked to run evidence
→ linked to BI consumers
→ linked to approved business terms
```

### Orchestration

Airflow and other orchestrators understand the runtime graph.

A DAG definition can show task dependencies. DAG Runs and Task Instances show the actual execution state. Stable APIs can expose workflows, runs and task results.

This metadata is essential for:

- operational lineage;
- freshness;
- incident analysis;
- SLA evidence;
- retry behaviour;
- failure ownership.

It should not be used as the only source of transformation semantics. An orchestrator can know that Task B follows Task A without understanding every column-level transformation inside the task.

### Qlik

Qlik applications contain data models, fields, dimensions, measures, expressions, scripts and consumption context. Qlik Cloud also provides lineage and impact-analysis capabilities for supported cataloged content.

Coverage depends on deployment, content type and how data is loaded. App metadata, script logic and catalog lineage are related but not identical sources.

A metadata inventory should therefore separate:

- app object metadata;
- load-script metadata;
- logical data-model metadata;
- master dimensions and measures;
- reload history;
- catalog lineage;
- usage;
- security rules or entitlements.

### Power BI

Power BI and Fabric semantic models can hold:

- tables and columns;
- relationships;
- measures;
- DAX expressions;
- Power Query or mashup expressions;
- sensitivity labels;
- endorsements;
- reports and dashboards;
- refresh and usage context.

Administrative scanner APIs can retrieve selected artifact and subartifact metadata when tenant settings, permissions and scan options are configured.

A warehouse-only catalog will miss semantic-model logic if it does not ingest this layer.

### Tableau

Tableau Metadata API uses a GraphQL metadata model to expose published workbooks, data sources, flows and related external assets. Tableau Catalog can add lineage, impact analysis, descriptions, certifications and data-quality warnings depending on deployment and licensing.

The key gap is scope: published Tableau content can be richly indexed, while logic or source context outside Tableau still requires other systems.

## Streaming metadata needs contracts, not only broker inventory

Apache Kafka knows its operational topology:

```text
cluster
broker
topic
partition
replica
consumer group
offset
configuration
retention
```

That is valuable operational metadata.

It does not by itself define:

- event business meaning;
- field-level schema;
- compatibility expectations;
- producer ownership;
- permitted consumer use;
- personal-data classification;
- retention justification.

A schema registry adds schema versions and compatibility. A stream catalog or data-contract layer can add tags, ownership and business metadata. Producer repositories can add code and deployment lineage. Consumer groups add observed usage.

The complete streaming view is therefore assembled from several native sources.

## Observability provides evidence, not approved meaning

Observability and data-quality systems can know:

- expected freshness;
- validation rules;
- validation results;
- volume changes;
- schema drift;
- anomaly signals;
- incidents;
- acknowledgements;
- affected assets;
- recovery time.

Great Expectations, for example, generates validation results from executed expectations. OpenLineage represents Jobs, Runs, Datasets and extensible facets that can transport lineage and quality context.

This evidence is operationally important. It should not silently overwrite approved business definitions or classifications.

A failed uniqueness test proves that observed data violated an expectation. It does not prove whether the expectation itself was approved, correctly scoped or still appropriate.

## Extraction interfaces are part of the architecture

A capability is not practically available merely because a product UI displays it.

For every metadata category, record the supported extraction interface.

| Interface type | Typical strengths | Typical limitations |
| --- | --- | --- |
| SQL catalog or system view | Stable, queryable, automation-friendly | Usually limited to one engine and permission scope |
| REST API | Structured, remotely accessible, often filterable | Pagination, rate limits, versioning and tenant settings |
| GraphQL API | Flexible relationship traversal | Query complexity, permission and schema-version dependence |
| Generated artifact | Versionable and reproducible | Must be produced and collected after relevant runs |
| Audit or activity log | Strong operational evidence | High volume, retention and sensitive access |
| Event stream | Low latency and change-oriented | Requires ordering, deduplication and replay design |
| Repository scan | Captures code, ownership and history | Static analysis may not equal runtime behaviour |
| UI export | Useful for initial assessment | Weak automation and often incomplete history |
| Direct source reference | Preserves authority and detailed context | Depends on source availability and identity mapping |

The inventory must also state:

```text
authentication method
required role
scope
pagination
rate limit
retention
latency
history
incremental extraction
deletion detection
schema version
owner
failure handling
```

A connector name without these characteristics is not an integration design.

## Start with the simplest viable native metadata inventory

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/native-metadata-across-the-data-stack-img4-en.png"
        alt="Assessment workflow from listing platforms through native metadata, interfaces, ownership, coverage, freshness and gaps to the decision to integrate, extend or replace"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Tool selection should be driven by missing capabilities and consumer needs. Vendor category names are not evidence of a gap.
    </figcaption>
</figure>

The simplest viable implementation is a structured inventory.

### Step 1: List platforms and environments

Do not list only strategic products.

Include:

- production and non-production environments;
- source applications;
- databases and warehouses;
- lakehouses;
- streaming platforms;
- transformation repositories;
- orchestrators;
- BI platforms;
- identity systems;
- observability systems;
- AI and model platforms;
- existing catalogs and spreadsheets.

Shadow metadata systems matter because they often contain definitions or ownership that no official platform has captured.

### Step 2: Identify native metadata

For each platform, inspect the seven metadata dimensions.

Do not ask only what is available in the UI. Ask what is:

- stored;
- generated;
- observable;
- exportable;
- queryable;
- historically retained;
- permission-protected.

### Step 3: Identify supported interfaces

Record the exact supported interface and extraction scope.

Examples:

```text
Snowflake ACCOUNT_USAGE view
Databricks information_schema
Fabric scanner API
dbt manifest artifact
Airflow REST API
Qlik Engine or repository API
Power BI admin scan result
Tableau Metadata API
Kafka AdminClient
Schema Registry REST API
Microsoft Graph
MLflow REST or client API
OpenLineage events
```

An example interface is not a guarantee of complete coverage. The inventory must document the actual objects returned.

### Step 4: Assign source ownership

Every extraction requires two owners:

```text
Source metadata owner
Connector or integration owner
```

The source owner understands meaning and permissions. The integration owner maintains collection, mapping, monitoring and failure recovery.

Without both roles, the connector will eventually become an orphaned technical asset.

### Step 5: Measure coverage and freshness

Coverage should be measured by required consumer questions.

For example:

```text
Can we find every production dataset?
Can we identify every report using a deprecated field?
Can we retrieve the approved KPI definition?
Can we prove who accessed confidential data?
Can we trace a model version to its training dataset?
Can we see whether metadata is stale?
```

Freshness should be explicit:

```text
source observed at
collected at
last successful sync
expected interval
current staleness status
```

### Step 6: Identify gaps

Classify each gap.

```text
Not available in source
Available but not extractable
Extractable but not retained
Available but permission-restricted
Available but not normalized
Available but not linked across systems
Available but not approved
Available but too stale
Available but not trusted
```

Different gaps require different solutions.

### Step 7: Decide integrate, extend or replace

Use three actions.

**Integrate** when the source already holds authoritative metadata and exposes a usable interface.

**Extend** when the source is authoritative but missing selected attributes, history, workflow or cross-system relationships.

**Replace** only when the current capability cannot meet the required operating model and migration cost is justified.

Buying a catalog to duplicate an available system view is not replacement. It is an additional synchronization obligation.

## Use one assessment table across the stack

A practical inventory can start with these columns:

| Metadata type | Source system | Interface | Freshness | History | Owner | Quality | Consumer need |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Table schema | Snowflake | Information Schema | Near current | Current plus platform history | Warehouse team | High for physical state | Search and impact |
| dbt model dependency | dbt project | `manifest.json` | Per build | Git plus retained artifacts | Analytics Engineering | High for declared DAG | Lineage |
| Pipeline run | Airflow | REST API | Near real time | Metadata DB retention | Platform Operations | High for runtime state | Freshness and incidents |
| BI measure | Power BI | Scanner API or model interface | Per scan | Depends on retention strategy | BI Product Owner | High for published model | KPI discovery |
| Qlik expression | Qlik app | App or engine metadata | Per reload or scan | Requires version strategy | Qlik app owner | Medium until reviewed | Semantic impact |
| Stream schema | Schema Registry | REST API | Near current | Versioned schemas | Streaming team | High for registered contract | Producer and consumer compatibility |
| Identity group | Entra ID | Microsoft Graph | Near current | Audit retention varies | Identity team | High for directory state | Access analysis |
| Model run | MLflow | Tracking API | Per run | Tracking-store retention | Data Science platform | High for logged evidence | Reproducibility |
| Business definition | Governance repository | API or workflow export | On approval | Versioned approvals | Data Steward | High when approved | Correct use |

The values are examples. Every organization must test its real interfaces and permissions.

## Concrete example: trace one sales field across the stack

Consider `net_sales_amount`.

### Operational source

The sales application knows:

- the original amount field;
- order status;
- discount and tax flags;
- source currency;
- cancellation behaviour;
- source identifier;
- process owner.

This is the best origin for source meaning and process constraints.

### Streaming or ingestion

Kafka may know:

- the topic carrying order events;
- partition and retention settings;
- producer and consumer activity.

Schema Registry may know:

- the event schema;
- field name and type;
- schema version;
- compatibility rules;
- registered tags.

Neither source automatically knows the approved enterprise revenue definition.

### Warehouse or lakehouse

The data platform knows:

- the physical table and column;
- type and nullability;
- grants;
- queries;
- object dependencies;
- storage or runtime history.

It can prove implementation and use inside the platform.

### dbt

The dbt project knows:

- the source mapping;
- transformation model;
- dependency graph;
- tests;
- documentation;
- code revision;
- latest run result.

It can explain how the source value became `net_sales_amount`.

### Orchestration

The orchestrator knows:

- when the pipeline ran;
- which task failed;
- whether retry succeeded;
- which environment executed;
- how long the run took.

This supports freshness and operational accountability.

### BI

The semantic or BI layer may know:

- `Net Sales`;
- the approved measure expression;
- currency formatting;
- date relationship;
- filters;
- report consumers;
- usage.

This may be the only place where the value is translated into the KPI shown to users.

### Governance

The governance layer should connect:

```text
source field
→ event field
→ warehouse column
→ dbt model column
→ semantic measure
→ report
→ business term
→ KPI
→ owner
→ policy
```

It should not manually rewrite every native property.

The remaining gaps may be:

- one stable cross-system identity;
- approved revenue definition;
- ownership and stewardship;
- cross-platform lineage;
- policy classification;
- metadata quality and freshness;
- change-impact workflow.

Those gaps justify central capability. The existing schemas, runs and expressions do not need to be recreated manually.

## Alternative implementation patterns

### Source-native only

Use source systems and their own interfaces without a central metadata platform.

Suitable when:

- the stack is small;
- the same team owns most systems;
- cross-platform search is not critical;
- audit and impact-analysis requirements are limited.

Warning:

Users must know where to look, and cross-system relationships remain manual.

### Lightweight central index

Harvest identifiers, selected descriptions, ownership, relationships and freshness into a searchable index.

Suitable when:

- source metadata is generally strong;
- central discovery is the main gap;
- detailed properties should remain at source;
- the organization wants low operating complexity.

Warning:

Identity resolution, connector monitoring and stale-reference handling still require engineering.

### Federated catalog

Provide central search, vocabulary and minimum standards while domains own detailed metadata.

Suitable when:

- several domains have distinct knowledge;
- local ownership is real;
- cross-domain navigation and policy are required.

Warning:

Federation without mandatory contracts becomes fragmented documentation.

### Enterprise active-metadata platform

Combine broad harvesting, lineage, workflow, policy, automation and downstream activation.

Suitable when:

- the landscape is large;
- metadata must trigger controls;
- cross-system impact is operationally important;
- dedicated platform ownership exists.

Warning:

A broad platform increases connector, licensing, mapping, security and operating obligations. It does not eliminate source ownership.

## Common anti-patterns

### Buying before inventorying

The organization selects a product category before defining the missing capability.

Result:

- existing metadata is duplicated;
- connectors are configured without consumer questions;
- platform scope expands faster than value.

### Treating a UI as an API

A product displays lineage or usage in its interface, so the team assumes the same metadata is extractable.

Result:

- unsupported scraping;
- incomplete exports;
- fragile integration;
- no contractual schema.

### Calling every relationship lineage

Declared dependency, parsed SQL, runtime observation, manual mapping and inferred similarity are stored as one edge.

Result:

Users cannot assess confidence or interpret impact correctly.

### Ignoring consumption-layer logic

The inventory stops at warehouse tables.

Result:

Measures, calculations, filters and report-level definitions remain invisible.

### Copying code into descriptions

Transformation logic is pasted into a catalog field.

Result:

The description becomes stale and loses its connection to the executable version.

### Centralizing high-volume operational evidence indiscriminately

Every query, event, audit record and profiling result is copied into a general metadata store.

Result:

Cost, sensitivity and retention problems increase without improving discovery.

### Confusing extraction ownership with metadata ownership

The catalog team operates a connector and is therefore labelled owner of the imported metadata.

Result:

No accountable source team corrects meaning or quality.

### Ignoring permissions and licensing

A capability is documented from a product page without testing the tenant, edition, role and API scope.

Result:

The implementation plan assumes metadata that cannot be retrieved in the real environment.

## Decision guidance

Before selecting another tool, answer these questions.

### Coverage

Which consumer questions cannot be answered using existing native metadata?

### Authority

Which system is authoritative for each metadata attribute?

### Interface

Can the metadata be extracted through a supported, automatable interface?

### Freshness

How current must the metadata be, and can the source meet that requirement?

### History

Does the source retain enough history for audit, impact analysis and incident investigation?

### Identity

Can the same asset be resolved across source, transformation, warehouse, BI and AI systems?

### Semantics

Where do the actual business definitions and calculations live?

### Security

Can metadata be collected without exposing restricted identities, samples or audit evidence?

### Operations

Who monitors connector health, schema changes, failed scans and stale metadata?

### Value

Which missing capability changes a real decision, control or workflow?

The tool decision should follow the answers.

## Key recommendations

1. Inventory native metadata before creating a vendor shortlist.
2. Separate schema, lineage, semantic, operational, usage, security and AI metadata.
3. Keep each attribute linked to an authoritative source and accountable owner.
4. Record the exact extraction interface, scope, freshness, history and permissions.
5. Include transformation repositories, orchestration and BI—not only databases.
6. Distinguish declared, parsed, observed, manual and inferred lineage.
7. Use central platforms for cross-system identity, discovery, vocabulary, policy and workflow.
8. Avoid copying high-volume or sensitive evidence when a reference or aggregate is sufficient.
9. Treat connector operation as a governed data-engineering responsibility.
10. Select additional tooling only for measured gaps and defined consumer needs.

> **The objective is not to collect the largest possible volume of metadata. It is to connect the right native evidence with enough authority, freshness and context to support trustworthy decisions.**

## Next: lineage, impact and metadata propagation

Native metadata identifies what each platform knows.

The next challenge is to connect these local observations into reliable end-to-end relationships.

Part 10 examines lineage, impact analysis and metadata propagation:

- how lineage is captured from code, queries, events and manual mappings;
- how column-level relationships should be represented;
- how confidence and provenance affect impact analysis;
- which metadata can be propagated safely;
- when inherited context must be reviewed or overridden;
- how changes should trigger downstream assessment.

A native metadata inventory provides the evidence. Lineage determines how that evidence becomes a connected change model.

## Current verification note

The product examples in this article were checked against official documentation available in July 2026. Product scope changes frequently. Before implementation, verify current editions, tenant settings, retention, API versions, permissions, preview status and object coverage.

Official references include:

- [Snowflake Account Usage](https://docs.snowflake.com/en/sql-reference/account-usage)
- [Snowflake Object Dependencies](https://docs.snowflake.com/en/sql-reference/account-usage/object_dependencies)
- [Databricks Information Schema](https://docs.databricks.com/aws/en/sql/language-manual/sql-ref-information-schema)
- [Databricks Lineage System Tables](https://docs.databricks.com/aws/en/admin/system-tables/lineage)
- [Microsoft Fabric Metadata Scanning](https://learn.microsoft.com/en-us/fabric/governance/metadata-scanning-overview)
- [Microsoft Fabric OneLake Catalog](https://learn.microsoft.com/en-us/fabric/governance/onelake-catalog-overview)
- [dbt Manifest Artifact](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [Qlik Lineage](https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Catalog/lineage.htm)
- [Power BI Workspace Scanner API](https://learn.microsoft.com/en-us/rest/api/power-bi/admin/workspace-info-post-workspace-info)
- [Tableau Metadata API](https://help.tableau.com/current/api/metadata_api/en-us/index.html)
- [Apache Airflow REST API](https://airflow.apache.org/docs/apache-airflow/stable/stable-rest-api-ref.html)
- [Apache Kafka Documentation](https://kafka.apache.org/documentation/)
- [MLflow Tracking](https://mlflow.org/docs/latest/ml/tracking/)
- [OpenLineage Object Model](https://openlineage.io/docs/1.44.0/spec/object-model)
