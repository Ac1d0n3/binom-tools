---
title: Where Metadata Is Born — Identify Which System Knows Which Part of the Truth
description: A practical guide to locating technical, business, operational, governance, usage and AI metadata across the complete data lifecycle while preserving provenance, authority and accountability.
category: Data Governance
tags:
  - metadata
  - metadata-governance
  - data-catalog
  - data-lineage
  - data-observability
  - data-quality
  - semantic-layer
  - identity-governance
  - data-provenance
  - active-metadata
  - ai-governance
  - data-products
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 2
seriesTitle: MetaData Deep Dive
hero: images/playbooks/where-metadata-is-born-hero.png
---

## Metadata does not begin in the catalog

A central data catalog is often treated as the place where metadata is created. In reality, it is usually the place where metadata from other systems is collected, normalized, connected and presented.

The original context is created much earlier and in many different places:

- a business application knows why a status exists and which process transition is valid
- a database knows physical structure, constraints and privileges
- an ingestion process knows when and how data was extracted
- an orchestration platform knows schedules, dependencies and failures
- transformation code knows how source fields became derived models
- a semantic model knows measures, dimensions, hierarchies and aggregation behaviour
- a BI application knows which filters, bookmarks and reports people actually use
- an identity system knows which roles and groups control access
- an observability process knows whether current data is late, incomplete or drifting
- an AI or data-science platform knows which datasets, features, experiments and models were used

None of these systems naturally knows the complete truth.

A source system can explain that `ORDER_STATUS = C` means “cancelled by the customer before fulfilment”. It may not know that the field is later renamed, combined with delivery state and used in a certified open-order KPI. A BI application may know that thousands of users consume the KPI every day. It may not know which source-system state transitions are legally valid. A central catalog may connect both perspectives, but it should not invent either of them.

> **Metadata is born wherever meaning, structure, movement, transformation, control or use becomes observable. A trustworthy metadata platform preserves that origin instead of replacing it with an anonymous central copy.**

This principle changes the central question from “Where should we document everything?” to:

> “Which system or accountable role knows this specific part of the truth best, and how do we preserve that knowledge across the lifecycle?”

## Metadata is created across the entire data lifecycle

Metadata creation is not a single event. It is a sequence of declarations, technical generation and runtime observations that continues from the operational transaction to the final report, application or AI model.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/where-metadata-is-born-img1-en.png"
        alt="Metadata created across source systems, ingestion, storage, transformation, semantic models, BI applications and AI or data-science platforms"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Every lifecycle stage creates a different part of the metadata context. A central layer becomes useful when it connects these contributions without obscuring their origin.
    </figcaption>
</figure>

### Source systems create original business and structural context

Operational source systems are where business events occur. They often know context that no downstream platform can reconstruct reliably.

Typical source-native metadata includes:

- application object and field names
- source labels and help text
- allowed values and process states
- keys, constraints and relationships
- transaction timestamps
- source-of-record responsibility
- workflow or status-transition rules
- audit fields
- user-entered classifications or categories
- local ownership information

For an order process, the source application may know that:

- one order can contain several order lines
- a line can be cancelled independently
- a requested delivery date can be changed after order entry
- a specific status is only valid after credit approval
- a manual override requires a reason code
- net value is stored in document currency

A downstream scanner can discover names, data types and value distributions. It cannot reliably infer the complete process meaning behind those fields.

Source metadata is not automatically perfect. Labels may be cryptic, help text may be outdated and business logic may exist only in application code or individual knowledge. The source is authoritative for what it actually stores and enforces, but not necessarily for every enterprise definition built on top of it.

### Ingestion creates movement and extraction metadata

Ingestion is the first major handoff. Data leaves the operational context and becomes a transported representation.

The ingestion process can generate:

- extraction start and end time
- extraction method
- full or incremental load mode
- source connection and object
- source-to-target mapping
- watermark or change token
- captured schema version
- row and file counts
- load status
- retry information
- rejected records
- landing location
- ingestion run identifier
- technical service account

These values explain how data arrived. They are essential when a downstream table contains fewer rows than expected, when a load is delayed or when source fields appear under different names.

A landing table can be physically correct while its extraction context is missing. Without the run identifier, source mapping and extraction timestamp, it may be impossible to determine whether the table represents the current source state, an incomplete batch or a replayed historical load.

### Storage creates physical metadata

Databases, warehouses, lakehouses and file stores know how data is represented and optimized.

Typical storage metadata includes:

- database, catalog, schema, table and column identity
- physical data types
- nullability
- keys and constraints
- file format
- partitioning and clustering
- statistics
- object size
- row count
- storage location
- grants and roles
- object creation and modification time
- retention or time-travel configuration
- physical dependencies such as views

Storage metadata is often the easiest metadata to harvest automatically because it is structured and machine-readable. That does not make it the most important context.

A warehouse can show that `NET_AMOUNT` is a decimal field in a partitioned table. It may not know whether the amount includes cancelled orders, which business owner approved the definition or whether the field is suitable for customer-level AI use.

### Orchestration creates process and runtime metadata

Orchestration platforms know when work should run, what it depends on and what happened during execution.

They create metadata such as:

- schedule and trigger
- upstream and downstream task dependencies
- run identifier
- execution start, end and duration
- success, failure and retry state
- parameters and environment
- deployment or code version
- processed record count
- alert and incident references
- responsible technical team
- service-level expectation

This metadata is not a replacement for data lineage. A task dependency may show that job B runs after job A, but it does not automatically prove which columns were derived from which source fields.

Orchestration metadata explains operational flow. Transformation metadata explains data derivation. Both are needed.

### Transformation creates derived meaning and lineage

Transformation code is where source-aligned data becomes standardized, joined, filtered, historized, aggregated or otherwise interpreted.

This layer knows:

- transformation expressions
- model dependencies
- source references
- renamed and derived fields
- filters and exclusions
- joins
- business-rule implementation
- tests and expectations
- model descriptions
- materialization behaviour
- version-controlled change history
- model contracts
- column-level lineage where it can be derived

For example, a transformation may define `open_order_value` as:

```text
order_line_net_amount
where order_status not in ('cancelled', 'completed')
and delivery_status <> 'fully_delivered'
converted to reporting currency using the approved daily rate
```

This logic does not belong to the source database catalog. It is created in the transformation layer and should be documented and versioned there.

The transformation layer must also preserve the source relationship. Renaming `NETWR` to `net_amount` improves readability, but the original field, source object and transformation rule should remain traceable.

### The semantic layer creates analytical behaviour

A semantic model adds context that does not naturally exist in source tables or transformation models.

It may define:

- measures and calculated metrics
- dimensions
- hierarchies
- display names
- number and date formats
- units and currencies
- aggregation behaviour
- time-intelligence rules
- default filters
- valid analytical relationships
- certified versus local calculations
- semantic synonyms

A warehouse column may contain `net_amount_reporting_currency`. The semantic layer may define:

- `Open Order Value`
- additive by customer, product and sales organization
- evaluated against the current reporting date
- excluded for cancelled and completed lines
- formatted in the selected reporting currency
- certified for operational sales reporting

That KPI presentation and analytical behaviour are born in the semantic layer. A central platform should connect the measure to its warehouse fields and source logic without pretending that the source system created the final KPI.

### BI and applications create consumption metadata

Reports, dashboards and analytical applications reveal how governed data is actually used.

They create or expose:

- report ownership
- report and sheet structure
- visual dependencies
- filters and selections
- bookmarks
- locally calculated fields
- refresh behaviour
- export activity
- user and group access
- query frequency
- active users
- last access
- adoption trends
- critical business processes
- subscriptions and alerts

Consumption metadata provides evidence about relevance and impact. A field used in one experimental report has a different change risk from a measure used in executive reporting, sales operations and customer service.

This information often remains trapped in the BI platform. When it is not returned to the shared metadata context, the organization can see upstream lineage but not the business consequence of change.

### Identity, access and governance systems create control metadata

Identity and governance systems know who may decide and who may access.

Relevant metadata includes:

- users, groups and roles
- role membership
- approval workflows
- access requests
- policy assignments
- entitlement decisions
- allowed purposes
- separation-of-duty rules
- exceptions and expiry dates
- review evidence
- certification status
- access events

The warehouse may expose that a role has `SELECT` access. An identity platform may know why the user received the role, who approved it and when the entitlement expires. A governance workflow may know whether the dataset is approved for a specific use case.

These perspectives must be connected. A technical grant without business approval context is incomplete. An approval without evidence of actual technical enforcement is equally incomplete.

### Observability creates current evidence

Data observability, quality monitoring and operational telemetry create metadata that only exists at runtime.

Examples include:

- freshness
- volume and row-count trends
- schema drift
- distribution changes
- failed quality rules
- anomaly events
- runtime and latency
- cost and workload behaviour
- incident state
- affected downstream assets
- current service-level compliance

No static documentation can replace this evidence. A description may be correct and an owner may be assigned, while the latest load is four hours late and a key field has become 40% null.

Observed metadata is time-bound. It must retain timestamp, scope, test or monitor version and the object version it evaluated.

### AI and data-science platforms create model and experiment metadata

AI and data-science work creates another layer of context:

- datasets used for training, evaluation or retrieval
- feature definitions
- feature generation code
- experiment parameters
- model versions
- prompt or retrieval dependencies
- training and evaluation runs
- lineage from source data to model output
- quality and performance metrics
- approvals
- deployment state
- known limitations
- model consumers

A model registry can describe a model version and evaluation result. It may not know whether the original customer data was approved for that training purpose unless governance and source metadata are connected to it.

The same principle applies to RAG. A vector index may know which chunks were embedded. Trust requires a link back to the original document or record, its version, owner, access policy, sensitivity, language, effective date and deletion status.

## Each system knows a different part of the truth

The objective is not to nominate one platform as the universal metadata owner. It is to assign authority by knowledge domain.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/where-metadata-is-born-img2-en.png"
        alt="Responsibility map showing which metadata knowledge is best held by business sources, pipelines, transformation, consumption, identity and governance systems"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A central platform connects the original business meaning, operational movement, derived logic, consumption behaviour and governance decisions. It should not silently invent any of them.
    </figcaption>
</figure>

### Business sources know original meaning and valid process states

The source application and its accountable business roles should remain authoritative for:

- original transaction meaning
- valid states and transitions
- source-system identifiers
- source-of-record responsibility
- operational process rules

This authority may be implemented in application configuration, code, source documentation or accountable human knowledge. The metadata program must identify which of these is actually reliable.

### Pipelines and orchestration know movement and execution

Ingestion and orchestration should remain authoritative for:

- how data moved
- when it moved
- which run produced it
- what dependencies executed
- what failed or retried
- which technical version was deployed

A catalog can receive these facts, but it should retain the original run and system reference.

### Transformation knows derived logic

Transformation repositories and execution artifacts should remain authoritative for:

- derived fields
- filters
- joins
- calculations
- model dependencies
- tests
- contracts
- code versions

A manually rewritten formula in a catalog becomes a second, potentially inconsistent implementation. The better pattern is to connect the catalog representation to the versioned transformation definition.

### Consumption systems know presentation and behaviour

Semantic and BI platforms should remain authoritative for:

- measures and dimensions presented to users
- semantic relationships
- display behaviour
- local calculations
- report ownership
- usage and adoption

A central platform can flag local logic that diverges from governed models, but it should not erase evidence that the local logic exists.

### Identity and governance know decisions and permissions

Identity, access and governance processes should remain authoritative for:

- roles and group membership
- approvals
- policy assignments
- allowed usage
- exceptions
- review state
- certification decisions

The central metadata layer connects these decisions to the relevant assets, processes and consumers.

## Origin, authority and storage location are different concepts

A metadata value can be stored centrally without becoming centrally authoritative.

For example, a central profile may contain:

```yaml
asset_id: sales.order_line
metadata:
  business_definition:
    value: Active and historical sales order line recorded by the order management process.
    origin_type: declared
    authoritative_source: business_glossary.sales.order_line
    accountable_role: Sales Operations Data Owner
    status: approved
    effective_from: 2026-06-01

  physical_data_type:
    value: DECIMAL(18,2)
    origin_type: generated
    authoritative_source: warehouse.prod.sales_order_line.net_amount
    observed_at: 2026-07-24T17:20:00Z

  last_successful_refresh:
    value: 2026-07-24T16:55:12Z
    origin_type: observed
    authoritative_source: orchestration.run.184294
    observed_at: 2026-07-24T16:55:12Z

  open_order_measure:
    value: semantic.sales.open_order_value
    origin_type: declared
    authoritative_source: semantic_model.sales.metrics
    code_version: 8f42c1a
```

The central system stores a connected representation. Authority still remains distributed.

At minimum, an imported metadata value should retain:

- stable asset identifier
- metadata attribute
- value
- origin type
- source system
- source object or record identifier
- accountable owner or process
- collection method
- collection timestamp
- effective time or evaluated time
- version
- status
- confidence where relevant
- transformation or normalization applied during import

Without these fields, centralization can hide conflicts and create false certainty.

## Automatically generated and human-supplied metadata must be combined

Automation can harvest large volumes of metadata. It cannot replace accountable business context.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/where-metadata-is-born-img3-en.png"
        alt="Automatically generated metadata and human-supplied metadata combined into one metadata profile"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technical harvesting reduces manual effort. Business definition, intended use, ownership, legal basis, exceptions and approval still require accountable input.
    </figcaption>
</figure>

### Metadata that is usually generated or observed

Good candidates for automated collection include:

- schemas and data types
- physical keys and constraints
- object locations
- query or code lineage
- pipeline runs
- runtime and freshness
- schema drift
- quality results
- access events
- usage frequency
- model and experiment runs
- detected sensitive-data patterns

Even here, automation has boundaries.

Detected PII is a proposal unless the detection rule and context justify an authoritative classification. Parsed lineage may be incomplete when dynamic SQL, generated code, external procedures or opaque applications are involved. Usage logs may show that an object was queried, but not whether the result influenced a business decision.

### Metadata that normally requires accountable human input

Human-supplied metadata commonly includes:

- business definition
- intended use
- scope and exclusions
- accountable owner
- Data Steward
- source-of-record decision
- KPI interpretation
- legal basis
- approved sensitivity
- policy exception
- approved retention class
- approval and certification status
- known limitations

This does not mean every field must be typed manually into a catalog. Human decisions can be maintained in version-controlled files, workflow systems, domain registries or source applications. The important point is that the value represents an accountable decision rather than an automated guess.

### Conflicts must remain visible

Central systems often receive different values for the same attribute.

For example:

```text
Source label: Customer segment
Transformation description: Commercial customer grouping
Semantic label: Account tier
Business glossary term: Customer value class
```

The platform should not silently choose one label and discard the others.

A useful resolution model distinguishes:

- original source labels
- technical model names
- approved business term
- consumer-facing display name
- synonyms
- deprecated terms
- unresolved conflicts

The result is a connected vocabulary, not one flattened string.

## The simplest viable implementation

A metadata program can begin without an enterprise graph or a large catalog rollout.

The simplest viable implementation is an origin-aware metadata register for one end-to-end data product.

Choose one business output, for example `Open Order Reporting`, and identify:

1. the operational source objects
2. the ingestion jobs
3. the landing and warehouse objects
4. the transformation models
5. the semantic measures
6. the reports and applications
7. the access roles
8. the quality and observability checks
9. any AI or data-science consumers

For every component, record:

| Area | Minimum information |
| --- | --- |
| **Identity** | stable identifier, system, object type and location |
| **Origin** | system or role that created the metadata value |
| **Authority** | source that remains authoritative for the value |
| **Relationship** | upstream and downstream identifiers |
| **Version** | schema, code, model or policy version |
| **Time** | collection time, effective time or observation time |
| **Responsibility** | business owner, steward or technical owner |
| **Status** | proposed, reviewed, approved, deprecated or failed |

The register can initially be implemented with:

- database comments for source-local technical descriptions
- version-controlled YAML or JSON for governed declarations
- transformation manifests or parsed code for lineage
- orchestration logs for run evidence
- exported BI metadata for measures and reports
- access-system records for roles and approvals
- a small central index that connects stable identifiers

The first objective is not complete automation. It is to prove that one business output can be traced across systems without losing origin.

## End-to-end example: from order entry to open-order report

Consider an organization that needs a certified `Open Order Value` KPI.

### 1. The order-management system records the transaction

The source application creates an order line with:

- `order_id`
- `order_line_id`
- `customer_id`
- `product_id`
- `order_status`
- `delivery_status`
- `requested_delivery_date`
- `net_amount`
- `currency_code`

The source knows that `order_status = CANCELLED` means the customer or internal process cancelled the line. It also knows which transitions are valid and which user performed the change.

Metadata born here:

- source identifiers
- source labels
- allowed statuses
- transaction meaning
- audit fields
- source-of-record responsibility

### 2. Ingestion extracts changed records

An incremental process extracts records changed since the previous watermark.

Metadata born here:

- source connection
- extraction timestamp
- watermark
- source-to-target mapping
- run identifier
- extracted row count
- load status
- rejected records

The process must preserve the source primary key and extraction context. Otherwise a downstream duplicate or missing record cannot be diagnosed reliably.

### 3. Storage records the landed and warehouse structures

The landing and warehouse layers create physical tables and files.

Metadata born here:

- schema
- physical data types
- nullability
- partitions
- statistics
- grants
- object versions
- storage location

The warehouse may standardize the column name from `NETWR` to `net_amount`. The original name should remain linked through source-to-target mapping.

### 4. Transformation derives the governed order model

Transformation logic joins currency rates, normalizes statuses and calculates a reporting-currency value.

Metadata born here:

- transformation logic
- dependencies
- filters
- joins
- model tests
- derived fields
- column lineage
- code version
- model description

The transformation may create:

```text
is_open_order =
  order_status not in ('CANCELLED', 'COMPLETED')
  and delivery_status <> 'FULLY_DELIVERED'
```

This rule is not source-native. It is a governed interpretation implemented in transformation code.

### 5. The semantic model defines the KPI

The semantic layer defines:

```text
Open Order Value =
SUM(net_amount_reporting_currency)
WHERE is_open_order = true
```

Metadata born here:

- measure identity
- display name
- format
- aggregation behaviour
- supported dimensions
- default time context
- certification status
- semantic owner

### 6. BI applications expose actual use

A dashboard presents the KPI by customer, sales organization, product group and requested delivery month.

Metadata born here:

- report ownership
- visual dependencies
- filters
- bookmarks
- subscriptions
- active users
- export activity
- local calculations
- adoption

If a report creates its own `Open Order Value` formula instead of using the certified measure, that divergence is also metadata and should be visible.

### 7. Identity and governance control access

The organization restricts customer-level detail to authorized sales roles while aggregated results are available more broadly.

Metadata born here:

- roles
- group membership
- policy assignment
- access approval
- exception expiry
- review evidence
- access events

### 8. Observability measures current reliability

Monitoring confirms whether the source, ingestion and transformation completed successfully.

Metadata born here:

- latest refresh
- freshness against expectation
- processed volume
- failed quality rules
- schema drift
- incident state
- affected reports

### 9. AI or data science reuses the governed context

A forecasting model consumes open-order history and requested delivery dates.

Metadata born here:

- feature definitions
- training dataset version
- transformation lineage
- experiment parameters
- model version
- evaluation result
- deployment state
- permitted use

The model must link back to the same governed order definitions. Otherwise the AI platform may use a technically similar field with a different business meaning.

## Metadata is most often lost at handoff points

The highest risk is not that metadata never existed. It is that it existed in one system and disappeared during transfer.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/where-metadata-is-born-img4-en.png"
        alt="Metadata losses from source label through extracted column, renamed model field, semantic measure and dashboard KPI, followed by a corrected provenance-preserving pattern"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Context is commonly lost when fields are extracted, renamed, transformed and presented. Preserving origin, mappings, transformation records and consumer links maintains end-to-end meaning.
    </figcaption>
</figure>

### Source descriptions are not exported

A connector extracts tables and fields but ignores source labels, value descriptions or application help text.

Result:

- the physical data arrives
- the business vocabulary remains behind
- downstream teams rebuild descriptions manually
- source and warehouse terminology diverge

### Transformation rationale is missing

Code shows a filter or calculation, but the reason is not documented.

Result:

- lineage explains what depends on what
- it does not explain why the rule exists
- future teams cannot distinguish a deliberate business rule from a workaround

### Renaming hides the original field

A source field is renamed for readability without preserving the original identifier.

Result:

- downstream users see a clearer name
- source impact analysis becomes difficult
- source-system changes cannot be matched reliably

### KPI logic is disconnected from base columns

A semantic measure or BI formula is not connected to transformation fields and source objects.

Result:

- the KPI appears documented
- its reproducibility and impact path remain unclear
- multiple local variants can develop unnoticed

### Consumption metadata never returns upstream

Reports and applications use a field, but the warehouse and catalog do not receive that information.

Result:

- unused and critical assets look identical
- change risk is underestimated
- deprecation decisions are made without consumer evidence

### Corrected handoff pattern

Every handoff should preserve four elements:

```text
Preserve origin
+ Capture mapping
+ Record transformation
+ Connect consumer
```

That means:

- retain the original source identifier
- create an explicit source-to-target mapping
- record transformation logic and rationale
- connect the final measure, report, application or model
- return runtime and usage evidence to the shared profile

## Alternative operating patterns

Different environments require different levels of centralization.

### Source-local metadata with shared conventions

Metadata remains in databases, code repositories, pipelines and BI models.

Appropriate when:

- the platform landscape is small
- engineering discipline is strong
- version-controlled documentation is already established
- cross-platform discovery is limited but manageable

Required safeguards:

- stable identifiers
- common metadata fields
- exportability
- clear ownership
- no undocumented local exceptions

### Central harvesting and indexing

A central platform periodically collects metadata from connected systems.

Appropriate when:

- discovery across platforms is important
- the organization needs cross-system lineage
- multiple tools expose usable APIs or exports
- governance teams need a common view

Required safeguards:

- provenance fields
- synchronization status
- conflict handling
- source-specific extensions
- visibility of stale or failed harvests

### Event-driven metadata updates

Systems publish metadata changes when schemas, models, policies or runs change.

Appropriate when:

- metadata must remain current
- the platform supports reliable events
- operational automation depends on metadata
- batch harvesting is too slow

Required safeguards:

- idempotent processing
- versioned events
- replay capability
- event ordering rules
- dead-letter and reconciliation processes

### Federated ownership with a shared model

Domains remain responsible for meaning and approval while a central team defines standards and connections.

Appropriate when:

- business knowledge is distributed
- one central documentation team cannot scale
- domains own their data products
- enterprise policies still require consistency

Required safeguards:

- minimum required metadata
- domain contracts
- shared identifiers
- escalation and conflict resolution
- measurable metadata quality

## Common anti-patterns

### Treating the catalog as the original source of every value

The catalog becomes a parallel manual database. Definitions, schemas, owners and policies drift from the systems that actually implement them.

### Harvesting only storage metadata

The organization gets a detailed inventory of tables and columns but misses process meaning, transformation rationale, BI usage, access decisions and AI dependencies.

### Flattening all metadata into one description field

Source labels, business definitions, technical notes and consumer display names are mixed into one text field. Authority and intended use become unclear.

### Copying values without provenance

A central field contains `Confidential`, but nobody can determine whether it came from a scanner, a source tag, a steward approval or an import default.

### Assuming detected means approved

A classifier proposal is used directly for masking, retention or AI permission without review or confidence handling.

### Ignoring runtime-only metadata

The catalog shows owners and definitions but no current freshness, quality, failure or access evidence.

### Documenting lineage but not rationale

The organization can trace a field through models but cannot explain why filters, joins or business rules were applied.

### Ignoring consumer-side logic

Local BI calculations, notebook transformations and application rules are excluded from lineage, even though they change the meaning delivered to users.

### Centralizing responsibility instead of visibility

A central governance team is expected to maintain business meaning for every domain. The result is slow updates, shallow definitions and low accountability.

## Decision guidance

The appropriate metadata origin strategy depends on the attribute being managed.

| Metadata question | Best primary authority | Suitable central role |
| --- | --- | --- |
| What does the original transaction mean? | source application and business process owner | connect, index and expose |
| What schema exists now? | source or storage catalog | harvest and compare versions |
| How did data move? | ingestion and orchestration | connect runs to assets |
| How was a field derived? | transformation code and model artifacts | parse, link and present |
| What does a KPI show? | governed semantic definition and business owner | connect formula, lineage and approval |
| Who may access it? | identity, policy and access systems | connect decisions to assets and usage |
| Is it currently trustworthy? | quality and observability evidence | aggregate and compare with expectations |
| How is it used? | BI, applications, query and access telemetry | return consumer relationships upstream |
| Which data trained a model? | data-science and model-management artifacts | connect datasets, features, versions and approvals |

Use four questions for every metadata field:

1. **Where is this value originally created?**
2. **Which system or role can keep it correct?**
3. **How can it be exported, referenced or observed?**
4. **What provenance must remain visible after centralization?**

The answer may differ by attribute even within the same asset.

## Key recommendations

1. Treat metadata creation as a lifecycle, not a catalog-entry task.
2. Identify the original authority separately for business meaning, physical structure, movement, transformation, consumption, access and runtime evidence.
3. Preserve stable identifiers across source, ingestion, storage, transformation, semantic, BI and AI objects.
4. Import metadata with source system, source record, origin type, version, timestamp, status and accountability.
5. Keep generated, detected, observed and human-approved values distinguishable.
6. Use automation for schemas, lineage, runs, freshness, usage and technical evidence, but not as a substitute for accountable definitions and approvals.
7. Preserve source-to-target mappings whenever fields are extracted or renamed.
8. Record both transformation logic and business rationale.
9. Return consumer and usage metadata upstream so impact and criticality remain visible.
10. Let a central platform connect truths from other systems; do not allow it to invent missing truth silently.
11. Begin with one end-to-end data product and prove the complete provenance chain before scaling.
12. Measure not only metadata completeness, but also origin coverage, synchronization status and unresolved conflicts.

## The next step: keep metadata close to the source

Understanding where metadata is born leads directly to the next architectural decision: where should each metadata value be maintained?

A central platform is useful for discovery, relationships, governance workflows and enterprise impact analysis. It becomes unreliable when every definition, classification and technical fact must be copied and maintained manually in that platform.

The next part, **Keep Metadata Close to the Source**, explains how metadata can remain near the code, application, model, policy or team that can keep it correct while still being harvested and connected centrally.
