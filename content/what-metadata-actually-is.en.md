---
title: What Metadata Actually Is — Beyond Names, Types and Descriptions
description: A practical foundation for understanding technical, business, operational, governance, security, quality, usage, semantic and AI metadata as one connected context for trusted data.
category: Data Governance
tags:
  - metadata
  - metadata-governance
  - data-catalog
  - data-lineage
  - data-quality
  - data-security
  - semantic-layer
  - active-metadata
  - data-observability
  - ai-governance
  - rag
  - data-products
order: -1
author: Thomas Lindackers
hero: images/playbooks/what-metadata-actually-is-hero.png
---

## Metadata is often reduced to the smallest visible part

When teams talk about metadata, they often mean the fields that are easiest to see:

- table name
- column name
- data type
- description
- perhaps an owner or tag

These fields are useful, but they describe only a fraction of the context required to understand and control a data asset.

Consider a table called `sales_order_line`.

Its schema may tell us that `net_amount` is a decimal and `customer_id` is a string. That does not tell us:

- what one row represents
- whether cancelled order lines are included
- which system is authoritative
- how frequently the table is refreshed
- whether the latest load completed successfully
- whether `customer_id` is considered sensitive
- who owns the business meaning
- which quality rules must pass
- which reports, models or applications depend on it
- whether the asset may be used for AI training or retrieval
- whether its definitions still reflect current business reality

A technically correct schema can therefore remain operationally unreliable, semantically ambiguous and unsafe to use.

> **Metadata is the combined context that explains what a data asset is, how it was created, how it behaves, who may use it and whether it can be trusted.**

This definition is broader than a data catalog. A catalog may store or present metadata, but metadata also exists in source systems, database catalogs, transformation code, orchestration logs, security policies, quality results, BI applications, access logs, model registries and operational processes.

## One asset requires several metadata perspectives

The same `Sales Order Line` asset can be described from several valid perspectives. These perspectives should not become isolated catalogs. They are different parts of one metadata profile.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/what-metadata-actually-is-img1-en.png"
        alt="One Sales Order Line asset surrounded by technical, business, operational, governance, security, quality, usage and AI metadata"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Names and data types are only one metadata group. A trustworthy asset combines technical structure with meaning, ownership, runtime evidence, controls, quality, usage and AI context.
    </figcaption>
</figure>

### Technical metadata

Technical metadata describes the physical and logical structure of data.

Typical examples include:

- system, database, schema, table and column names
- physical and logical data types
- nullability, keys and constraints
- partitions, clustering and indexes
- file formats and storage locations
- model dependencies
- transformation expressions
- source-to-target mappings
- schema versions

For `sales_order_line`, technical metadata may state that one row contains `order_id`, `order_line_id`, `product_id`, `quantity`, `currency_code` and `net_amount`, and that the combination of `order_id` and `order_line_id` is expected to be unique.

Technical metadata explains how the asset is represented. It does not automatically explain why it exists or how the business interprets it.

### Business metadata

Business metadata describes meaning, purpose and terminology.

It includes:

- business definition
- business purpose
- domain and subdomain
- approved terms and synonyms
- scope and exclusions
- business rules
- accountable business role
- relevant processes and decisions

For the running example, business metadata must explain whether a sales order line represents the originally requested item, the currently active item, the delivered item or the invoiced item. These interpretations can lead to different measures even when the table name is identical.

A weak description says:

> `net_amount`: Sales amount.

A useful description says:

> Net value of the active sales order line in document currency after line-level discounts and before tax. Cancelled lines remain in the source-aligned table but are excluded from the certified open-order measure.

The second description connects the field to calculation logic, scope and intended use.

### Operational metadata

Operational metadata describes what happens while data is produced and processed.

Examples include:

- last successful refresh
- expected refresh interval
- runtime and duration
- processed row count
- failed executions
- retry status
- freshness and latency
- schema drift events
- source availability
- incident history

Operational metadata changes frequently. It can show that `sales_order_line` is defined correctly but has not been refreshed for twelve hours, or that the latest load processed only 20% of the expected volume.

Without operational metadata, documentation can look trustworthy while the actual asset is stale or incomplete.

### Governance metadata

Governance metadata establishes responsibility, status and controlled use.

It may include:

- Data Owner
- Data Steward
- technical owner
- business domain
- certification status
- approval state
- policy mapping
- retention class
- authoritative-source status
- lifecycle state
- review date
- exception and waiver information

Governance metadata answers who may decide, who must maintain the context and whether the asset is approved for a particular purpose.

Ownership is not a decorative field. It must connect to real responsibilities such as approving definitions, resolving quality issues, reviewing access and retiring obsolete assets.

### Security and privacy metadata

Security metadata describes how information must be protected.

Typical fields include:

- sensitivity level
- personal-data classification
- confidentiality category
- access groups or roles
- row-level access domain
- masking or tokenization policy
- permitted purpose
- geographic or contractual restrictions
- encryption requirements
- retention and deletion controls

For `sales_order_line`, `customer_id` may be classified as an internal identifier, while a linked email address may be confidential personal data. The classification alone does not enforce protection, but it provides the decision input required by access, masking and policy controls.

### Quality metadata

Quality metadata describes what “fit for use” means and whether current data meets that expectation.

It includes:

- completeness, validity, uniqueness and consistency rules
- accepted value domains
- reconciliation rules
- thresholds
- latest test results
- quality score
- incidents and exceptions
- affected records
- quality trend
- responsible resolution team

For the running example, useful expectations include:

- `order_id` and `order_line_id` must be present
- their combination must be unique within the active source snapshot
- `quantity` must not be negative unless the line represents a return
- `currency_code` must use an approved code set
- `net_amount` must reconcile with the source within an agreed tolerance

The rule definition is relatively stable. The latest result is dynamic metadata.

### Usage metadata

Usage metadata describes how an asset is consumed.

Examples include:

- dependent reports, dashboards, models and APIs
- active consumers
- query frequency
- last access
- common filters and joins
- critical business processes
- certified versus experimental use
- cost or workload contribution
- unused or declining assets

Usage metadata helps distinguish a technically available table from a critical data product. It also supports impact analysis: changing `net_amount` is more consequential when the field is used by financial reporting, forecasting and customer-facing applications.

Usage must not be interpreted as quality by itself. A frequently used field can still be wrong, and an infrequently used field can still be required for a critical monthly process.

### Semantic metadata

Semantic metadata describes relationships, concepts and analytical behaviour.

It includes:

- business concepts and their relationships
- entity and attribute meaning
- hierarchies
- measures and dimensions
- aggregation behaviour
- units and currencies
- synonyms and terminology mappings
- valid join paths
- semantic types
- contextual rules

Semantic metadata may state that:

- `customer_id` identifies a Customer entity
- `product_id` identifies a Product entity
- `net_amount` is an additive monetary measure within one currency
- `order_date` belongs to the Order Date role
- sales order lines roll up to sales orders, customers, products and sales organizations

This context is important for BI, search, natural-language interfaces and AI systems. A model cannot reliably infer correct analytical behaviour from column names alone.

### AI metadata

AI metadata describes whether and how data may be used by AI systems.

It can include:

- permitted AI use cases
- training permission
- RAG suitability
- source provenance
- content version
- sensitivity and access filters
- semantic relationships
- chunk or record context
- embedding status
- model or prompt dependencies
- evaluation evidence
- known bias or coverage limitations
- deletion and reprocessing requirements

For the sales asset, AI metadata may distinguish between:

- approved use for internal sales forecasting
- prohibited use for general model training
- permitted use in a controlled RAG assistant only after customer identifiers are removed
- unsuitable use for customer-level recommendations because required consent or quality evidence is missing

AI metadata does not replace security, quality or governance metadata. It combines and extends them for AI-specific decisions.

## Metadata can describe data, processes, models, controls and consumption

Metadata is often organized only around tables and columns. That creates a blind spot because enterprise data is produced and used through a larger system.

### Metadata about data

This describes datasets, tables, files, fields, records, business entities and data products.

Examples:

- schema
- field definitions
- sensitivity
- quality expectations
- domain
- retention

### Metadata about processes

This describes ingestion, transformation, orchestration, approval and operational workflows.

Examples:

- schedule
- dependencies
- runtime
- retry policy
- responsible team
- deployment version
- incident history

A failed ingestion process may make several otherwise valid assets unusable.

### Metadata about models

This includes analytical, semantic, statistical and AI models.

Examples:

- model purpose
- input features
- output measures
- training dataset
- version
- validation evidence
- calculation behaviour
- supported dimensions
- known limitations

A warehouse model, a semantic model and a machine-learning model need different details, but all require controlled context.

### Metadata about controls

Controls include policies, tests, validation rules, approvals, access rules and enforcement mechanisms.

Relevant metadata includes:

- control objective
- trigger condition
- affected assets
- responsible owner
- implementation location
- last execution
- result
- exception process
- evidence

This makes it possible to distinguish a written policy from a policy that is technically implemented and monitored.

### Metadata about consumption

Consumption metadata describes reports, dashboards, APIs, notebooks, exports, AI assistants and operational applications.

It explains:

- who uses the output
- which upstream assets it depends on
- which definitions it applies
- how critical it is
- which access rules apply
- whether local logic changes the governed meaning

The result is an end-to-end context rather than a catalog limited to storage objects.

## Metadata has different origins and confidence levels

Not all metadata is created in the same way. A useful operating model distinguishes four origins: declared, detected, inferred and observed.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/what-metadata-actually-is-img2-en.png"
        alt="Comparison of declared, detected, inferred and observed metadata with origin, confidence, review and suitable use"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadata origin determines confidence and review requirements. Detected or inferred context should not silently overwrite approved declarations.
    </figcaption>
</figure>

### Declared metadata

Declared metadata is intentionally written by an accountable person or engineering process.

Examples:

- approved business definition
- named owner
- retention class
- expected freshness
- documented transformation rule
- permitted AI use

Declared metadata can have high authority, but only when the author, scope, approval state and review date are known. A manually entered description is not automatically correct forever.

### Detected metadata

Detected metadata is discovered through scanning, profiling, parsing or classification.

Examples:

- physical data type
- email or phone pattern
- schema change
- file format
- repeated value distribution
- technical dependency parsed from code

Detection is valuable for scale and discovery. It usually produces evidence or a proposal, not a final governance decision.

A classifier may detect that a field resembles an email address. It cannot always decide whether the field contains real personal data, test values, hashes or obsolete content.

### Inferred metadata

Inferred metadata is derived from relationships, rules or other metadata.

Examples:

- sensitivity propagated through a direct column transformation
- ownership inherited from a domain assignment
- criticality inferred from dependent applications
- semantic relationships derived from keys and lineage
- probable duplicate business terms

Inference can fill gaps and reduce manual work, but it must remain explainable. The system should retain the evidence, rule and confidence behind the result.

An inferred value should normally be marked as `proposed` or `unreviewed` until the relevant workflow approves it.

### Observed metadata

Observed metadata is measured from runtime behaviour.

Examples:

- last refresh
- query frequency
- access events
- current row count
- failure rate
- quality result
- processing duration
- freshness delay

Observed metadata provides current evidence. It can show whether declared expectations are actually being met.

For example:

```text
Declared refresh expectation: every 60 minutes
Observed last successful refresh: 4 hours ago
Result: freshness breach
```

Neither field is sufficient alone. The declared expectation defines what should happen; the observed value shows what did happen.

## Approval status must remain separate from origin

Origin and approval are different dimensions.

A declared description may still be a draft. A detected classification may be reviewed and approved. An inferred relationship may remain unresolved. An observed runtime metric may be authoritative as a measurement but not suitable as a permanent business definition.

A practical lifecycle is:

```text
Proposed → Reviewed → Approved → Deprecated
```

Additional states may be useful, such as `unreviewed`, `rejected`, `expired` or `retired`. The essential rule is that consumers can distinguish a proposal from an approved governance decision.

A metadata record should therefore retain at least:

- value
- origin
- source or evidence
- confidence where relevant
- status
- responsible reviewer
- approval date
- effective date
- version
- review or expiry date

## Trust requires both static and dynamic metadata

Some metadata changes slowly. Other metadata changes every minute.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/what-metadata-actually-is-img3-en.png"
        alt="Static and slowly changing metadata combined with dynamic and continuously observed metadata in one metadata profile"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Definitions, ownership and classifications provide stable context. Freshness, quality, failures, schema drift and access events provide current operational evidence.
    </figcaption>
</figure>

### Static or slowly changing metadata

Typical examples include:

- table purpose
- field definition
- owner
- domain
- retention class
- sensitivity
- approved terminology
- expected grain

These values should not change with every pipeline run. They normally require versioning, review or approval.

“Static” does not mean permanent. Business definitions, ownership and classifications can change. The important point is that they change through a controlled lifecycle rather than through runtime measurement.

### Dynamic or continuously observed metadata

Typical examples include:

- last refresh
- current quality score
- active consumers
- recent failures
- schema drift
- access events
- current volume
- runtime and cost

Dynamic metadata must include time context. A quality score without a timestamp, test version and evaluated scope is difficult to interpret.

The two categories must be connected. A current quality result should refer to a defined rule. A freshness observation should be compared with an expected service level. An access event should be evaluated against an approved policy.

## The simplest viable metadata implementation

A useful metadata program does not have to begin with a large catalog platform or a complete enterprise graph.

The simplest viable implementation can be a versioned metadata profile for one important data asset.

For `sales_order_line`, the minimum profile could contain:

| Area | Minimum metadata |
| --- | --- |
| **Identity** | unique asset identifier, name, system and location |
| **Meaning** | purpose, grain, business definition and scope |
| **Structure** | schema, keys, important fields and data types |
| **Ownership** | Data Owner, Data Steward and technical owner |
| **Lineage** | authoritative source and primary downstream consumers |
| **Operation** | refresh expectation and latest successful refresh |
| **Quality** | critical rules, latest result and open incidents |
| **Security** | sensitivity, access expectation and policy mapping |
| **Lifecycle** | status, version, review date and retirement rule |
| **AI context** | permitted use, prohibited use and provenance requirements |

This profile can initially be stored in the tools already closest to the asset:

- database comments for basic structure and descriptions
- version-controlled YAML or JSON for governed declarations
- transformation code for model-level metadata
- orchestration logs for runtime evidence
- access systems for security events
- BI metadata for downstream consumption
- a simple documentation site or registry for discovery

The minimum implementation should satisfy five requirements:

1. Every critical value has an accountable source.
2. Proposed and approved values are distinguishable.
3. Runtime evidence is time-stamped.
4. The same asset has one stable identifier across systems.
5. Metadata can be exported or connected later.

The objective is not to centralize everything immediately. It is to prevent important context from remaining implicit or trapped in individual knowledge.

## Alternative metadata patterns

Different organizations need different operating patterns.

### Source-local metadata

Metadata remains close to databases, code, pipelines and applications.

Advantages:

- engineers maintain context where changes occur
- strong connection to implementation
- easier version control
- low initial platform overhead

Limitations:

- difficult cross-platform search
- inconsistent schemas
- fragmented ownership and status
- limited enterprise impact analysis

This is often the correct starting point, but it needs shared conventions and stable identifiers.

### Central metadata repository or catalog

Metadata from several systems is harvested into one searchable platform.

Advantages:

- centralized discovery
- common vocabulary
- cross-system lineage
- governance workflows
- broader impact analysis

Limitations:

- central copies can become stale
- not every system exposes sufficient detail
- local context may be lost during normalization
- a catalog can become passive documentation if it is disconnected from engineering and controls

The source should remain authoritative for metadata that is created there. Centralization should connect and present context rather than blindly replace local responsibility.

### Federated metadata model

Domains maintain their metadata within shared enterprise rules.

Advantages:

- domain expertise remains local
- common governance can coexist with decentralized ownership
- scales better than one central documentation team

Limitations:

- requires clear contracts and minimum fields
- quality can vary between domains
- conflict resolution and shared terms need governance

### Metadata graph

Assets, processes, terms, controls, people and consumption objects are represented as connected entities.

Advantages:

- flexible lineage and impact analysis
- semantic relationships
- policy and control connections
- useful context for search and AI

Limitations:

- requires stable identifiers and relationship quality
- graph complexity does not compensate for weak definitions
- inferred relationships need explainability and review

The appropriate pattern depends on scale, tool diversity, regulatory needs, organizational ownership and the maturity of existing engineering practices.

## Passive documentation and active metadata are different maturity levels

Metadata can remain descriptive, or it can participate in operational decisions.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/what-metadata-actually-is-img4-en.png"
        alt="Maturity path from names and types through searchable documentation and connected metadata to validation, automated controls and AI-ready context"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Organizations can advance incrementally. Active metadata becomes valuable when reliable context is connected to validation, controls and governed consumption.
    </figcaption>
</figure>

### Names and types

The organization can inspect schemas and physical objects.

This supports technical discovery but provides little business or governance context.

### Searchable documentation

Descriptions, owners and terms can be found in one or more documentation systems.

This reduces dependency on individual knowledge, but users still need to interpret and apply the information manually.

### Connected metadata graph

Assets are connected to sources, transformations, owners, terms, controls and consumers.

This supports lineage, impact analysis and richer discovery.

### Quality and policy validation

Metadata is evaluated against rules.

Examples:

- critical assets require an owner
- personal data requires an approved protection mapping
- published models require descriptions for business-facing fields
- certified data products require defined quality expectations
- AI-enabled assets require permitted-use metadata

Validation can run during change review, deployment or scheduled governance checks.

### Automated controls

Approved metadata triggers technical behaviour.

Examples:

- sensitivity metadata selects a masking policy
- domain metadata selects an access rule
- retention metadata triggers deletion workflows
- quality status blocks publication
- deprecation status generates impact notifications

This is active metadata: context is no longer only read by people; it becomes an input to controlled automation.

Automation must remain transparent. A classification should not trigger an unexplained control through hidden logic. The metadata value, policy mapping, implementation and evidence must remain traceable.

### AI-ready context

Metadata supports governed retrieval, model input selection, source attribution, access filtering and evaluation.

AI-ready metadata requires more than adding embeddings. It requires reliable meaning, provenance, permissions, freshness and semantic relationships.

Not every organization needs the most advanced stage immediately. The correct target is the simplest level that reliably supports current decisions and risks.

## A concrete metadata profile for Sales Order Line

The running example can be summarized as one connected profile.

### Asset identity

```text
Asset ID: sales.sales_order_line
Asset type: analytical table
Domain: Sales
Lifecycle status: active
```

### Business meaning

```text
Purpose: Provide one source-aligned record per sales order line for governed downstream transformation.
Grain: One record per order ID and order line ID in the current source version.
Scope: Standard, return and cancellation lines retained with explicit status.
```

### Technical context

```text
Source: ERP sales order item table
Primary key expectation: order_id + order_line_id
Refresh mode: incremental
Primary downstream model: sales_order_line_conformed
```

### Governance and security

```text
Business Owner: Head of Sales Operations
Data Steward: Sales Data Steward
Technical Owner: Data Platform Team
Sensitivity: internal
Customer identifier: restricted internal identifier
Retention class: sales transaction retention policy
```

### Quality expectations

```text
Key completeness: 100%
Key uniqueness: required for active source version
Currency validity: approved code list
Amount reconciliation: within agreed tolerance
Freshness target: 60 minutes
```

### Observed evidence

```text
Last successful refresh: timestamped runtime value
Latest quality result: pass, warning or fail
Recent volume: row count and change from baseline
Open incidents: linked operational records
```

### Usage and semantic context

```text
Consumers: order backlog, sales performance, forecasting
Entities: Sales Order, Customer, Product
Measures: quantity, gross amount, net amount
Valid joins: governed customer, product and organization keys
```

### AI context

```text
Forecasting: permitted for approved internal use
General model training: prohibited
RAG use: only through governed semantic descriptions and access-filtered evidence
Provenance: source, transformation version and refresh time required
```

No single system has to own every line physically. The essential requirement is that the profile can be resolved consistently and that each value has a trusted source.

## Common anti-patterns

### Treating descriptions as the complete metadata model

Descriptions cannot replace ownership, quality, security, lineage and runtime evidence.

### Writing descriptions that repeat the field name

Examples such as “Sales is sales” or “Customer ID is the customer identifier” add almost no usable context. A field description should explain business meaning, scope, calculation, valid values, exceptions or relationships.

### Centralizing metadata but not responsibility

A central catalog with no accountable owners becomes a larger collection of stale fields.

### Assuming detected metadata is approved metadata

Automated classification and profiling produce valuable evidence. They should not silently become binding governance decisions.

### Copying metadata without transformation semantics

A downstream field may combine, aggregate, hash or reinterpret several inputs. Blind propagation can create incorrect classifications and definitions.

### Storing dynamic values without timestamps

A “quality score: 96” is meaningless without time, evaluated scope, rule version and threshold.

### Confusing usage with trust

Frequent use does not prove correctness. Low use does not prove irrelevance.

### Creating a separate metadata repository for every perspective

Technical, business, security and AI metadata may originate in different systems, but consumers need a resolvable view of the same asset.

### Automating controls before metadata is reliable

Incorrect or unreviewed metadata can trigger incorrect access, masking, deletion or publication decisions. Automation requires status, evidence and exception handling.

### Preparing data for AI without provenance and permissions

Embedding undocumented content into a retrieval index does not make it AI-ready. It can make incorrect or unauthorized context easier to retrieve.

## Decision guidance

Use the following questions to define the appropriate next step:

| Question | Implication |
| --- | --- |
| Can users find the asset but not understand it? | Improve business and semantic metadata first. |
| Is meaning clear but current reliability unknown? | Connect operational and quality metadata. |
| Are ownership and classifications inconsistent? | Establish governance fields, statuses and approval workflows. |
| Are several tools describing the same asset differently? | Introduce stable identifiers and a unified metadata model. |
| Are changes difficult to assess? | Improve lineage, usage and dependency metadata. |
| Are policies documented but manually enforced? | Connect approved metadata to validation and controls. |
| Is AI planned? | Add provenance, permissions, semantic context, freshness and AI-use metadata before indexing or training. |
| Is the catalog becoming stale? | Move maintenance closer to source and automate harvesting of observable facts. |

The most important design decision is not which catalog product to buy. It is which metadata values are authoritative, who owns them, how they are reviewed and how they remain synchronized with actual systems.

## Key recommendations

1. Treat metadata as a connected context, not a set of catalog fields.
2. Distinguish technical, business, operational, governance, security, quality, usage, semantic and AI metadata.
3. Model metadata about processes, models, controls and consumption—not only tables and columns.
4. Preserve origin, evidence, confidence and approval status.
5. Keep stable definitions separate from dynamic observations, then connect both.
6. Start with one critical asset and a minimum viable metadata profile.
7. Maintain metadata close to the system or team that can keep it correct.
8. Centralize discovery and relationships without removing local accountability.
9. Use automation first for harvesting and validation; use enforcement only with approved, traceable metadata.
10. Prepare AI context through meaning, provenance, access and quality—not through embeddings alone.

## The next step: understand where metadata is born

Before metadata can be harvested, unified or activated, its origins must be understood.

Some metadata is created intentionally in source applications. Some exists in database catalogs and transformation code. Some appears only in orchestration logs, BI applications, security systems or runtime observations. Other context must be declared by business owners and stewards because no scanner can derive it reliably.

The next part, **Where Metadata Is Born**, maps these origins across the complete data lifecycle and establishes which source should remain authoritative for each type of metadata.
