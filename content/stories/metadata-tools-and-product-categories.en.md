---
title: Metadata Tools and Product Categories — Select Capabilities Based on Architecture and Operating Needs, Not Labels
description: A practical decision framework for distinguishing catalogs, governance platforms, metadata management, active metadata, lineage, observability, data quality, semantic layers, marketplaces, MDM and schema registries, and for selecting a build, buy, extend or compose strategy based on verified capabilities, connectors, operating effort and lock-in.
category: Data Governance
tags:
  - metadata
  - metadata-tools
  - metadata-management
  - data-catalog
  - data-governance
  - active-metadata
  - data-lineage
  - data-observability
  - data-quality
  - semantic-layer
  - data-marketplace
  - master-data-management
  - schema-registry
  - open-source
  - metadata-architecture
  - tool-selection
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 14
seriesTitle: MetaData Deep Dive
hero: images/playbooks/metadata-tools-and-product-categories-hero.png
publishedAt: 2026-07-03 10:00
---

## Product labels create false certainty

A metadata initiative often reaches the tooling discussion too early.

A team asks for a Data Catalog. Another team wants a Governance Platform. Engineering proposes an open-source metadata platform. Security requests policy enforcement. Analytics wants a Semantic Layer. Operations wants Data Observability. A vendor describes its product as an Active Metadata Platform and presents lineage, quality, workflows, AI assistance and a marketplace in the same demonstration.

Every request may be legitimate.

The labels still do not define a complete architecture.

Modern metadata products overlap because they operate on many of the same assets, relationships and events. A catalog may contain glossary terms, lineage, ownership and quality results. A governance suite may provide discovery and workflows. A lakehouse may include native catalog, access control, lineage and quality monitoring. An observability platform may build a lineage graph to determine incident impact. A semantic layer may expose governed metrics through APIs. An open-source metadata platform may combine discovery, governance, lineage and quality in one extensible service.

The same feature name can also hide very different implementation depth.

`Lineage` may mean:

- manually curated dataset relationships;
- parsed SQL lineage;
- lineage imported from transformation manifests;
- query-log-derived lineage;
- runtime events;
- column-level lineage;
- cross-platform impact analysis;
- a visual graph without exportable relationship evidence.

`Workflow` may mean:

- assigning an owner;
- approving a glossary term;
- integrating with an external ticketing system;
- running a configurable state machine;
- executing policy-driven automation;
- merely sending a notification.

A category name therefore cannot prove that a product fits the required use case.

> **Metadata tool selection should begin with required capabilities, authoritative sources, integration patterns, operating ownership and evidence needs. Product categories are useful orientation, but they are not architecture decisions.**

The correct question is not:

```text
Which product is the best Data Catalog?
```

It is:

```text
Which capability gaps must be closed,
which systems already contain authoritative metadata,
how fresh must the connected view be,
who will operate the solution,
and how can metadata be exported or replaced later?
```

## The core principle: select a capability system, not a label

A useful metadata architecture separates five concerns:

```text
Capture
→ Connect
→ Govern
→ Activate
→ Verify
```

**Capture** collects source-native, declared, inferred and observed metadata.

**Connect** resolves identities and relationships across systems.

**Govern** adds definitions, ownership, policies, approvals and exceptions.

**Activate** distributes metadata or uses it to trigger controlled actions.

**Verify** records whether the expected runtime state or business outcome actually exists.

One product may implement several of these concerns. It rarely owns every authoritative input or every runtime control.

This leads to a practical evaluation model:

```text
Required use case
+ authoritative metadata source
+ supported integration
+ required freshness
+ operating owner
+ control boundary
+ evidence requirement
+ exit path
= viable architecture
```

The model deliberately includes the exit path. Metadata becomes infrastructure. Once many systems depend on one graph, API or identifier scheme, replacement cost can become larger than the original license cost.

## The metadata tool landscape is a set of overlapping capabilities

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-tools-and-product-categories-img1-en.png"
        alt="A metadata tool landscape places Data Catalog, Governance Platform, Metadata Management, Active Metadata, Lineage, Data Observability, Data Quality, Semantic Layer, Data Marketplace, Master Data Management, Schema Registry and Open-Source Metadata Platform around shared capabilities such as discovery, graph, workflow, policy, runtime evidence and APIs"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadata product categories overlap around shared discovery, graph, workflow, policy, runtime-evidence and API capabilities. The category indicates a product's center of gravity, not an exclusive function.
    </figcaption>
</figure>

The following categories describe different centers of gravity.

### Data Catalog

A Data Catalog primarily helps people and systems discover, understand and evaluate data assets.

Typical capabilities include:

- harvesting technical metadata;
- search and browsing;
- asset profiles;
- ownership;
- descriptions and glossary links;
- lineage visualization;
- usage signals;
- certification or trust indicators;
- collaboration.

A catalog may support governance workflows and policy metadata. Its primary purpose is still discovery and context. It does not automatically enforce runtime access, retention, masking or data quality.

### Governance Platform

A Governance Platform focuses on accountability, policy, decision rights and controlled processes.

Typical capabilities include:

- governance domains;
- business vocabulary;
- ownership and stewardship;
- policy and rule management;
- classifications;
- approval workflows;
- issue and exception management;
- evidence and audit history;
- reporting on governance obligations.

Some governance platforms include a catalog and metadata graph. Others integrate with separate technical metadata systems. The important distinction is whether governance decisions are merely documented or connected to enforceable controls.

### Metadata Management Platform

Metadata Management is the broadest category.

It can include:

- metadata ingestion;
- normalization;
- repository or graph;
- technical and business metadata;
- versioning;
- lineage;
- APIs;
- governance;
- administration;
- exchange with other systems.

The label is too broad to guide selection by itself. It must be decomposed into specific required capabilities and operating responsibilities.

### Active Metadata Platform

Active Metadata emphasizes continuous metadata movement and event-driven action.

Typical patterns include:

- incremental or event-driven ingestion;
- metadata change events;
- automated enrichment;
- policy evaluation;
- workflow triggers;
- synchronization to other tools;
- deployment gates;
- notifications and tasks;
- evidence returned after an action.

The term does not guarantee safe automation. A useful implementation still separates detection, decision, action, approval, rollback and verification.

### Lineage Product or Service

A lineage-focused product explains how data, processes, fields, metrics and consumers are connected.

Its depth depends on:

- supported parsers;
- runtime instrumentation;
- query history;
- manifest ingestion;
- source and target identity resolution;
- column-level support;
- transformation semantics;
- confidence and provenance;
- cross-system graph traversal;
- API export.

OpenLineage, for example, defines an interoperable event model around jobs, runs and datasets. It is a lineage collection standard, not a complete catalog, glossary or governance operating model.

### Data Observability

Data Observability focuses on operational health and incident detection.

Common signals include:

- freshness;
- volume;
- schema change;
- distribution change;
- failed pipelines;
- data-quality results;
- usage anomalies;
- incident impact.

Observability platforms often build lineage because impact and root-cause analysis require dependencies. Their graph may be excellent for operational incidents while remaining incomplete for business vocabulary, policy approval or enterprise stewardship.

### Data Quality

Data Quality products define and execute expectations against data.

They may provide:

- profiling;
- rules;
- validation;
- anomaly detection;
- test results;
- issue workflows;
- quality scores;
- pipeline integration;
- remediation evidence.

Great Expectations, for example, centers on expressive expectations and validation workflows. Quality results are valuable metadata, but a quality engine is not automatically the authoritative catalog, business glossary or policy repository.

### Semantic Layer

A Semantic Layer defines reusable business-facing models, dimensions and metrics.

Its main concerns are:

- metric logic;
- dimensions;
- entities;
- joins;
- grains;
- filters;
- time semantics;
- query generation;
- consistent consumption through BI tools and APIs.

The dbt Semantic Layer, powered by MetricFlow, is a current example of centrally defined metrics exposed through downstream integrations and APIs. A Semantic Layer can be authoritative for KPI logic while relying on another platform for enterprise discovery, stewardship and policy.

### Data Marketplace

A Data Marketplace provides a consumer-oriented experience for finding, requesting and receiving Data Products, datasets, models, applications or other governed offerings.

Typical capabilities include:

- listings;
- product descriptions;
- producer information;
- access requests;
- terms of use;
- delivery mechanisms;
- subscriptions;
- usage tracking;
- lifecycle management.

A marketplace depends on metadata but is not the same as a metadata repository. It packages governed offerings for consumption. Databricks Marketplace, for example, provides listings for data and AI-related assets and uses platform-specific sharing and governance capabilities.

### Master Data Management

Master Data Management manages authoritative business entities such as Customer, Product, Supplier, Location or Material.

Core capabilities can include:

- matching;
- deduplication;
- survivorship;
- hierarchy management;
- reference data;
- golden records;
- stewardship;
- synchronization to operational systems.

MDM manages the data and lifecycle of core business entities. A catalog describes and connects assets. The two overlap in vocabulary, ownership and quality but solve different primary problems.

### Schema Registry

A Schema Registry manages versioned data contracts for messages or interfaces.

Typical capabilities include:

- schema storage;
- versioning;
- compatibility checks;
- producer and consumer integration;
- APIs;
- controlled schema evolution.

Confluent Schema Registry, for example, exposes REST APIs and compatibility modes for versioned schemas. It is authoritative for schema contracts in its scope, but it is not a general business glossary, enterprise catalog or stewardship platform.

### Open-Source Metadata Platform

An open-source metadata platform can combine catalog, graph, lineage, governance, quality and APIs in one extensible codebase.

Current examples include OpenMetadata and DataHub Core.

OpenMetadata documents an integrated catalog, glossary, lineage, data quality and governance model with schema-first REST APIs. Its core project is licensed under Apache License 2.0.

DataHub provides a schema-first metadata model, graph-oriented relationships, search, ingestion and actions around metadata changes. DataHub Core is also released under Apache License 2.0, while managed cloud packaging and enterprise services are separate commercial decisions.

Open source removes neither operating cost nor architecture risk. The organization still owns deployment, upgrades, security, connector maintenance, backups, scaling, incident response and customization discipline unless a managed service assumes those responsibilities.

## Shared capabilities must be assessed independently

The same six capabilities appear across many categories:

### Discovery

Can users and machines find the correct asset?

Assess:

- search quality;
- filters and facets;
- synonyms;
- business and technical names;
- environment separation;
- ranking;
- API search;
- access-aware results.

### Graph

Can the platform represent and traverse typed relationships?

Assess:

- asset types;
- relationship types;
- column-level edges;
- versions;
- historical relationships;
- confidence;
- provenance;
- graph query;
- impact-analysis depth.

### Workflow

Can decisions move through a controlled lifecycle?

Assess:

- states;
- assignments;
- approvals;
- separation of duties;
- escalation;
- expiry;
- exceptions;
- external ticket integration;
- evidence;
- API control.

### Policy

Can policies be represented and evaluated?

Assess:

- machine-readable rules;
- controlled vocabularies;
- policy versions;
- scope;
- effective dates;
- exceptions;
- approval;
- mapping to runtime controls;
- verification.

### Runtime evidence

Can the platform connect intended metadata state to observed behaviour?

Assess:

- run events;
- quality results;
- access evidence;
- usage events;
- deployment results;
- incident states;
- enforcement confirmation;
- freshness of evidence.

### APIs and exchange

Can metadata be imported, queried, changed and exported reliably?

Assess:

- documented APIs;
- bulk export;
- change feed;
- webhooks or events;
- stable identifiers;
- schema versioning;
- rate limits;
- pagination;
- SDKs;
- authentication;
- delete semantics;
- historical export.

A platform can look complete in the user interface while remaining weak as infrastructure if these interfaces are limited.

## Compare capabilities, not product labels

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-tools-and-product-categories-img2-en.png"
        alt="A neutral capability matrix compares native platforms, dedicated catalogs, governance suites, open-source platforms and custom metadata services across connectors, technical metadata, business glossary, column lineage, workflow, policy enforcement, quality integration, usage analytics, versioning, APIs and export, AI assistance and deployment model using Strong, Partial, External and Not primary"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Capability matrices should describe architectural fit rather than assign universal vendor scores. Strong, Partial, External and Not primary indicate where a capability normally lives and what must be integrated.
    </figcaption>
</figure>

A neutral comparison uses architectural terms instead of scores.

`Strong` means the capability is central, supported and expected to operate at production depth.

`Partial` means the capability exists but may cover only selected asset types, deployment modes or workflows.

`External` means the architecture expects another system to provide the capability.

`Not primary` means the capability may appear, but it is not the product's main responsibility.

These values must be tested for the actual environment.

A connector listed as supported does not prove:

- column-level lineage;
- incremental harvesting;
- deletions;
- usage metadata;
- policy synchronization;
- service-account compatibility;
- on-premises connectivity;
- private-network deployment;
- source-version compatibility;
- acceptable API consumption.

A capability matrix is therefore a hypothesis for a Proof of Value, not the final answer.

## Start with the simplest viable implementation

The simplest viable metadata architecture usually begins with native capabilities and one clearly defined gap.

A practical starting pattern is:

```text
Native platform metadata
+ transformation metadata
+ BI metadata
→ lightweight central index or graph
→ selected governance enrichment
→ API access
```

The first implementation should answer a small number of high-value questions:

```text
What is this asset?
Who owns it?
Where did it come from?
Which consumers depend on it?
Which definition and quality state are approved?
Can the metadata be exported?
```

Do not begin by recreating every local feature centrally.

A warehouse or lakehouse may remain authoritative for schemas, permissions, query activity and native lineage. dbt may remain authoritative for model definitions, tests and declared dependencies. A BI platform may remain authoritative for measures, applications and report usage. A central platform can connect these views and add enterprise vocabulary, cross-platform ownership and policy context.

This design reduces duplicated authoring and keeps operational detail close to the systems that produce it.

## Native platform metadata and dedicated products should coexist

Native metadata is usually deepest inside its own platform.

A dedicated product is usually broader across platforms.

The architecture should exploit both properties.

```text
Native platform
- detailed local schema
- local permissions
- platform runtime events
- native lineage
- platform-specific semantics

Dedicated metadata platform
- cross-platform identity
- enterprise search
- common vocabulary
- cross-domain lineage
- policy framework
- shared APIs
- escalation and evidence
```

The dedicated platform should not blindly replace native metadata. It should reference, normalize, cache or synchronize it according to authority and freshness requirements.

Useful patterns include:

### Index

Store a searchable representation centrally while retaining the native platform as the system of record.

### Reference

Keep a stable central identifier and link to the authoritative source for current detail.

### Synchronize

Exchange selected governed values in both directions with explicit precedence rules.

### Materialize

Copy metadata centrally when cross-system performance, history or availability requires it.

### Federate

Query multiple metadata services through common APIs while preserving local ownership.

The choice can differ by metadata attribute.

## Build, buy, extend or compose are all valid strategies

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-tools-and-product-categories-img3-en.png"
        alt="Four metadata implementation strategies compare Build, Buy, Extend Native Platforms and Compose using scale, required controls, team skills, existing stack, connector needs, customization and total operating cost"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Build, buy, extend and compose can all be correct. The decision depends on control requirements, skills, connector depth, customization and total operating cost rather than a universal maturity sequence.
    </figcaption>
</figure>

### Build

Build is appropriate when the organization has unusual metadata models, strict control requirements or a product capability that is strategically differentiating.

Advantages:

- full model control;
- custom APIs and workflows;
- direct integration with internal systems;
- controlled deployment;
- freedom from vendor UI assumptions.

Costs:

- product engineering;
- connector development;
- identity resolution;
- search and graph operation;
- security;
- upgrades;
- documentation;
- on-call responsibility;
- long-term ownership.

A custom service should remain narrow. Rebuilding search, lineage, workflow, glossary, policy, quality and AI assistance simultaneously is rarely the simplest option.

### Buy

Buy is appropriate when required capabilities are common, time-to-value matters and the operating model can use a supported product.

Advantages:

- faster initial capability;
- packaged connectors;
- supported upgrades;
- security and administration features;
- established user experience;
- vendor accountability.

Constraints:

- licensing and packaging;
- product roadmap;
- connector limitations;
- customization boundaries;
- data residency;
- API limits;
- migration and lock-in.

The license price must be compared with total operating cost, not with zero.

### Extend native platforms

Extending native platforms is appropriate when most relevant assets and controls live in one ecosystem.

Advantages:

- lower integration effort;
- deeper native detail;
- existing identity and permissions;
- fewer moving parts;
- operational ownership already exists.

Constraints:

- fragmented enterprise view;
- incomplete cross-platform lineage;
- duplicated vocabulary;
- ecosystem dependence;
- weak export outside the platform;
- separate experiences for different domains.

This strategy can be the best answer for a bounded platform even if it is not an enterprise-wide answer.

### Compose

Compose combines specialized components.

Example:

```text
native catalogs
+ OpenLineage events
+ central metadata graph
+ external quality engine
+ ticket workflow
+ policy engine
+ semantic layer
```

Advantages:

- best-fit components;
- replaceable boundaries;
- open standards;
- incremental implementation;
- reduced dependence on one product.

Costs:

- integration ownership;
- identifier alignment;
- duplicated state;
- version compatibility;
- operational complexity;
- unclear incident responsibility.

Compose works only when interfaces and ownership are explicit.

## Connector depth matters more than connector count

Connector pages are useful discovery material. They are not acceptance evidence.

Evaluate each critical connector with a structured contract:

```yaml
source: production-warehouse
connector_owner: data-platform
supported_version: verified
asset_types:
  - catalog
  - schema
  - table
  - view
  - column
capture:
  schema: true
  lineage: column
  usage: query-history
  quality: external-reference
  deletions: true
mode:
  - scheduled
  - incremental
freshness_objective: 30m
identity_strategy: platform-id-plus-qualified-name
export_tested: true
failure_alerting: true
```

Test:

- initial full load;
- incremental update;
- rename;
- deletion;
- permission failure;
- API throttling;
- late events;
- duplicate events;
- environment mapping;
- historical backfill;
- connector upgrade.

A connector without an owner becomes stale infrastructure.

## Freshness must match the use case

Not every metadata attribute needs real-time updates.

Examples:

```text
schema change for deployment gate: minutes
quality incident for operational response: minutes
usage trend for lifecycle review: daily
business definition: after approval
policy decision: effective-date driven
organizational ownership: daily or event-driven
historical lineage: batch may be sufficient
```

Freshness requirements affect:

- ingestion pattern;
- API consumption;
- event infrastructure;
- storage cost;
- failure handling;
- user expectations;
- control safety.

A daily scan cannot safely support a deployment gate that must detect a breaking field removal before release.

## Exportability is part of the architecture

Metadata must be treated as portable operational knowledge.

Require evidence that the solution can export:

- assets;
- source identifiers;
- relationships;
- glossary terms;
- ownership;
- classifications;
- policies;
- approvals;
- quality references;
- lineage;
- versions;
- audit history;
- custom properties.

Inspect the export format.

A flat CSV may export visible fields but lose:

- typed relationships;
- temporal validity;
- nested structures;
- provenance;
- workflow history;
- policy versions;
- deletes;
- unresolved references.

Also test whether exported identifiers can be mapped back to source-native identifiers.

A platform that allows read access only through a proprietary user interface creates hidden migration risk.

## Lock-in has several forms

Lock-in is not limited to contract duration.

### Data lock-in

Metadata cannot be exported with sufficient structure or history.

### Identifier lock-in

Downstream systems depend on proprietary identifiers that cannot be recreated.

### Workflow lock-in

Approval and exception logic exists only inside a proprietary workflow engine.

### Connector lock-in

Critical extraction works only through vendor-managed connectors with no alternative path.

### Control lock-in

Runtime policies depend on proprietary mappings or agents.

### Skill lock-in

Only a small specialist group can maintain the platform.

### Operating-model lock-in

Ownership and stewardship processes are designed around one interface rather than stable responsibilities and APIs.

The goal is not zero lock-in. The goal is understood, bounded and accepted dependency.

## A concrete example: govern one revenue KPI end to end

Assume an organization needs to govern `Recognized Revenue`.

The relevant context is distributed:

```text
CRM
- source statuses
- customer and order identifiers

Warehouse
- tables and columns
- query usage
- native permissions

dbt
- transformation logic
- tests
- model dependencies

Semantic Layer
- metric definition
- grain
- time dimension
- approved filters

BI
- dashboards
- report usage
- presentation labels

Governance
- owner
- definition
- policy
- criticality
- approval

Quality
- freshness and reconciliation results

Runtime controls
- deployment gates
- access and masking
```

A weak selection process asks which vendor can display all these objects.

A useful process asks:

1. Which system is authoritative for each attribute?
2. Which relationships are required for traceability?
3. How fresh must each relationship be?
4. Which decision must be approved?
5. Which controls consume the approved metadata?
6. Which evidence proves the controls worked?
7. Which APIs can export the full model?

The resulting architecture may use native warehouse metadata, dbt artifacts, a semantic layer, a central metadata graph, an external quality engine and a governance workflow.

That is not necessarily tool sprawl.

It is tool sprawl only when responsibilities overlap without clear authority, interfaces or ownership.

## Use a gap-first selection process

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-tools-and-product-categories-img4-en.png"
        alt="A tool selection workflow starts with inventorying native metadata, defining use cases, freshness and operating model, identifying capability gaps, testing connectors and APIs, running a Proof of Value, evaluating cost and lock-in, and selecting an architecture; scenarios trace a KPI, classify a sensitive field, detect a schema change, resolve an owner, answer an impact question and export metadata through an API"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Tool selection should start with verified gaps and representative end-to-end scenarios. A generic feature checklist cannot prove connector depth, freshness, ownership, exportability or operational fit.
    </figcaption>
</figure>

The sequence is:

```text
Inventory Native Metadata
→ Define Use Cases
→ Define Required Freshness
→ Define Operating Model
→ Identify Capability Gaps
→ Test Connectors and APIs
→ Run Proof of Value
→ Evaluate Cost and Lock-In
→ Select Architecture
```

### Inventory native metadata

Document what each current platform already knows, how it exposes that metadata and who owns the interface.

### Define use cases

Use decision-oriented questions rather than feature names.

Examples:

- Can a user find the approved definition of one KPI?
- Can a Steward identify all assets containing a sensitive field?
- Can Engineering see the downstream impact of removing a column?
- Can an owner prove that a quality incident was resolved?
- Can an AI system retrieve only approved context?

### Define required freshness

State a measurable objective for each scenario.

### Define operating model

Assign:

- platform owner;
- connector owner;
- metadata owner;
- workflow owner;
- policy owner;
- support route;
- upgrade responsibility;
- incident responsibility.

### Identify capability gaps

Only gaps become product requirements.

### Test connectors and APIs

Use production-representative systems and permissions.

### Run a Proof of Value

A useful Proof of Value should complete end-to-end scenarios:

```text
trace one KPI
classify one sensitive field
detect one schema change
resolve one owner
answer one impact question
export metadata through API
```

### Evaluate cost and lock-in

Include:

- licenses;
- infrastructure;
- connector add-ons;
- implementation;
- integration;
- security review;
- migration;
- administration;
- upgrades;
- training;
- support;
- exit cost.

### Select architecture

Select the combination of native, purchased, open-source and custom components that closes the verified gaps with an accountable operating model.

## Proof-of-Value acceptance criteria

A demonstration is not a Proof of Value.

Require repeatable evidence.

For each scenario, record:

```yaml
scenario: trace-one-kpi
source_assets_verified: true
column_lineage_verified: partial
business_definition_resolved: true
owner_resolved: true
freshness_met: true
api_export_complete: false
manual_steps: 3
known_limitations:
  - scheduled export dependency missing
decision: conditional
```

Acceptance criteria should cover:

- correctness;
- completeness;
- freshness;
- provenance;
- security;
- scalability;
- usability;
- API behaviour;
- operational effort;
- failure recovery;
- exportability.

The result may legitimately be `conditional` rather than pass or fail.

## Common anti-patterns

### Starting with a vendor category

The organization decides it needs a Catalog before defining what users and controls must accomplish.

### Counting connectors

A large connector count is treated as proof of depth and maintainability.

### Rebuilding native metadata manually

Definitions, schemas and lineage are copied into a central tool without authority or synchronization rules.

### Buying overlapping tools without boundaries

Several products store owner, glossary, lineage and quality status, but none is the system of record.

### Treating AI assistance as architecture

Generated descriptions and conversational search are evaluated before identity, provenance, approval and access control are reliable.

### Ignoring export until contract renewal

The migration path is examined only after many downstream processes depend on proprietary identifiers and workflows.

### Selecting for the demonstration dataset

The product performs well on a simple cloud warehouse but fails on private networking, legacy systems, complex BI models or production scale.

### Assuming open source is free

Infrastructure, security, upgrades, connectors, support and on-call work are omitted from the cost model.

### Assuming a suite removes integration

A suite may reduce vendor count while still requiring source-specific extraction, identity alignment and runtime-control integration.

### Building a platform without a product owner

Custom metadata services become unowned internal frameworks with growing dependency and no roadmap.

## Decision guidance

Use a native-only approach when:

- the relevant estate is concentrated in one platform;
- cross-platform discovery is not required;
- native governance and APIs satisfy the use cases;
- operating simplicity is the primary constraint.

Use a dedicated catalog or governance product when:

- cross-platform discovery matters;
- business vocabulary and stewardship require a shared experience;
- packaged connectors and support reduce implementation risk;
- the licensing and export model are acceptable.

Use an open-source metadata platform when:

- model and API control matter;
- the organization can operate the stack;
- extensibility is required;
- community and project maturity fit the risk profile;
- managed and self-hosted options have been compared.

Use a custom metadata service when:

- the scope is narrow and differentiating;
- required behaviour is not available elsewhere;
- stable internal engineering ownership exists;
- the service uses open interfaces and avoids recreating commodity features.

Use a composed architecture when:

- different systems are authoritative for different metadata dimensions;
- standards and APIs provide clear boundaries;
- integration ownership is funded;
- components can be replaced independently.

## Key recommendations

1. Define use cases before product categories.

2. Inventory native metadata before adding another platform.

3. Evaluate capabilities independently: harvesting, graph, glossary, workflow, policy, lineage, quality, runtime evidence, APIs, deployment and AI support.

4. Test connector depth with real assets, permissions, changes and failures.

5. Assign an operating owner to every connector and synchronization path.

6. Match metadata freshness to the decision or control it supports.

7. Keep authoritative metadata close to the platform or team that can maintain it correctly.

8. Use dedicated products to connect enterprise context, not to recreate every local detail manually.

9. Require structured export, stable identifiers and an exit strategy before dependency grows.

10. Compare build, buy, extend and compose using total operating cost.

11. Treat open-source licensing, managed-service packaging and commercial feature boundaries as separate facts.

12. Re-verify features, APIs, connectors, deployment options and licensing immediately before procurement or publication.

## Verification snapshot for named products

The product examples in this article were checked against official documentation on **25 July 2026**. They are examples of category overlap, not recommendations or a vendor ranking.

Verified points include:

- Microsoft Purview separates the Data Map foundation from the Unified Catalog governance experience.
- Databricks Unity Catalog provides native governance capabilities for data and AI assets in the Databricks ecosystem.
- The dbt Semantic Layer defines and exposes centrally governed metrics through MetricFlow and APIs.
- Confluent Schema Registry manages versioned schemas and compatibility through documented REST APIs.
- OpenLineage defines an extensible standard for job, run and dataset lineage events.
- OpenMetadata combines catalog, glossary, lineage, governance, quality and schema-first REST APIs; the core project uses Apache License 2.0.
- DataHub Core provides a schema-first metadata model, graph relationships, ingestion and metadata actions; the core project uses Apache License 2.0.
- Great Expectations centers on expectations and data validation workflows.
- Informatica describes MDM as consolidation and maintenance of authoritative records for core business entities.

Official references:

- [Microsoft Purview data governance overview](https://learn.microsoft.com/en-us/purview/data-governance-overview)
- [Microsoft Purview Data Map](https://learn.microsoft.com/en-us/purview/data-map)
- [Databricks data and AI governance with Unity Catalog](https://docs.databricks.com/aws/en/data-governance/)
- [dbt Semantic Layer](https://docs.getdbt.com/docs/use-dbt-semantic-layer/dbt-sl)
- [dbt Semantic Layer APIs](https://docs.getdbt.com/docs/dbt-apis/sl-api-overview)
- [Confluent Schema Registry](https://docs.confluent.io/platform/current/schema-registry/index.html)
- [Confluent Schema Registry API](https://docs.confluent.io/platform/current/schema-registry/develop/api.html)
- [OpenLineage documentation](https://openlineage.io/docs/)
- [OpenMetadata documentation](https://docs.open-metadata.org/v1.12.x/quick-start/getting-started)
- [OpenMetadata APIs](https://docs.open-metadata.org/v1.12.x/api-reference/main-concepts/metadata-standard/apis)
- [OpenMetadata license](https://github.com/open-metadata/OpenMetadata/blob/main/LICENSE)
- [DataHub documentation and license](https://datahubproject.io/docs/introduction/)
- [DataHub metadata model](https://datahubproject.io/docs/metadata-modeling/metadata-model/)
- [Great Expectations Core overview](https://docs.greatexpectations.io/docs/core/introduction/gx_overview/)
- [Informatica MDM overview](https://www.informatica.com/products/master-data-management.html)

Product packaging, connector coverage, cloud regions, licensing and feature boundaries change. Procurement decisions should therefore repeat this verification against the exact edition, deployment model, contract and source-system versions.

## The next step: prepare metadata for AI, RAG and model training

Selecting a metadata architecture does not yet make its context safe or useful for AI.

An AI Assistant needs more than a searchable catalog. It needs approved definitions, access-aware retrieval, stable identities, provenance, freshness, semantic relationships, usage boundaries and evidence that the selected context applies to the current question.

A model-training workflow needs additional decisions:

- Is the dataset permitted for training?
- Which version was approved?
- Which personal or restricted attributes are present?
- Which transformations produced it?
- Which quality and representativeness limits apply?
- Which licenses, contracts or retention rules constrain use?
- Which metadata may be exposed in prompts or retrieved documents?

Part 15 therefore moves from tool architecture to AI readiness: **Prepare Metadata for AI, RAG and Model Training**.
