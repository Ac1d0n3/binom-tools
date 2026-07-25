---
title: Build a Unified Metadata Model — Connect Heterogeneous Metadata Without Erasing Origin or Meaning
description: A practical architecture for connecting source-native metadata, stable asset identities, explicit relationships, versions, provenance, approval states and conflict-resolution rules in one usable model.
category: Data Governance
tags:
  - metadata
  - metadata-model
  - metadata-governance
  - data-catalog
  - metadata-graph
  - asset-identity
  - metadata-provenance
  - metadata-versioning
  - data-lineage
  - business-glossary
  - data-products
  - semantic-layer
  - ai-ready-metadata
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 7
seriesTitle: MetaData Deep Dive
hero: images/playbooks/build-a-unified-metadata-model-hero.png
---

## Metadata becomes fragmented before it becomes unified

A modern data landscape rarely has one metadata model.

A database describes catalogs, schemas, tables, columns, keys and data types. An orchestration platform describes workflows, tasks, schedules and runs. Transformation code describes models, tests and dependencies. A semantic layer describes dimensions, measures and relationships. A BI platform describes applications, datasets, reports and consumers. A governance platform adds terms, owners, policies, classifications and approvals. AI platforms add features, training datasets, models, prompts, evaluations and deployment context.

Each model is valid within its own system. The problem begins when an organization tries to connect them.

The same logical dataset may appear as:

```text
CRM source table
→ replicated raw table
→ transformation model
→ semantic table
→ BI object
→ catalog asset
```

Names may differ. Identifiers may be regenerated. Development, test and production environments may contain similar objects. A table may be renamed while retaining its purpose. A copy may look identical but have different operational ownership. A derived model may reuse most source columns while changing grain and meaning.

A weak central design usually responds in one of two ways:

- it stores every source object as an unrelated record and leaves users to infer the connections;
- it flattens every object into one generic asset record and loses the distinctions that made the source metadata useful.

Both approaches create a catalog that can search names but cannot reliably answer questions about identity, lineage, ownership, change or authority.

> **A unified metadata model needs stable identities, explicit relationship types, versions and provenance. It should connect source-specific models without reducing every concept to one anonymous record.**

Unification does not mean forcing every platform to use the same internal schema. It means creating a controlled representation in which local models can be mapped, compared and traversed while their origin remains visible.

## The core model is a graph of typed assets and relationships

A useful metadata model starts with two primary concepts:

```text
Typed Asset
+
Typed Relationship
```

An asset represents something that can be identified, described, governed, versioned or related to another object. A relationship expresses a specific claim between two assets.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/build-a-unified-metadata-model-img1-en.png"
        alt="Core metadata graph connecting data assets, processes, business concepts, governance objects, consumers and AI assets through explicit relationship types"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A unified model should preserve the difference between systems, datasets, fields, processes, terms, policies, people, reports and AI assets while making their relationships traversable.
    </figcaption>
</figure>

### Data assets

Data assets describe stored or transmitted data structures:

- system
- database
- schema
- table
- view
- column
- file
- object store path
- event topic
- message schema
- dataset

Hierarchy relationships may include:

```text
system contains database
database contains schema
schema contains table
table contains column
topic conforms to message schema
dataset materializes table
```

A hierarchy is useful, but it is not enough. A column can also implement a Business Term, contribute to a KPI, be classified as personal data and be consumed by a model.

### Processes

Processes describe actions that create, move, validate or expose data:

- pipeline
- job
- task
- transformation
- query
- synchronization
- quality check
- deployment

Typical relationships include:

```text
pipeline reads table
pipeline writes table
transformation derives column
job executes task
query consumes dataset
quality check validates column
deployment publishes semantic model
```

Representing a process as free text inside a table record makes lineage and operational analysis difficult. Processes should be independent assets when they have their own identity, lifecycle, owner or execution evidence.

### Business assets

Business assets describe meaning and delivered value:

- domain
- subdomain
- Business Term
- KPI
- metric
- business event
- Data Product
- use case

Typical relationships include:

```text
column implements Business Term
KPI calculated from column
asset belongs to domain
table belongs to Data Product
Data Product delivers use case
Business Term is synonym of Business Term
KPI refines metric
```

A Business Term is not a column. A KPI is not a report visual. A Data Product is not merely a table collection. The model must preserve these distinctions.

### Governance assets

Governance assets describe accountability and control:

- person
- team
- role
- owner assignment
- Steward assignment
- policy
- classification
- retention rule
- access rule
- quality rule
- approval
- exception

Typical relationships include:

```text
role owns Data Product
person fulfils role
policy governs asset
classification applies to column
quality rule validates KPI
exception overrides policy for scope
approval accepts metadata version
```

A person and a role should not be represented as the same object. People change. Roles and accountabilities should remain stable enough to reassign without rewriting every relationship.

### Consumption and AI assets

Consumers and AI assets include:

- report
- dashboard
- application
- API
- semantic model
- feature
- feature set
- training dataset
- evaluation dataset
- model
- prompt template
- deployment

Typical relationships include:

```text
report consumes semantic model
application calls API
feature derived from column
training dataset contains feature
model trained on dataset
model evaluated by evaluation dataset
deployment serves model
prompt references approved context
```

These assets make impact analysis possible beyond the warehouse. They also prevent AI context from becoming an undocumented side channel disconnected from data governance.

## Canonical asset types should be stable but not universal

A canonical model provides common categories that enable cross-platform questions. It should not attempt to reproduce every source property as a first-class enterprise field.

A practical division is:

```text
Canonical core
Source-native extension
```

The canonical core contains attributes that are broadly useful:

```yaml
asset:
  asset_id: asset:warehouse:prod:sales:fct_order_line
  asset_type: table
  display_name: fct_order_line
  platform: cloud_warehouse
  environment: prod
  lifecycle_status: active
  current_version: 12
```

The source-native extension retains attributes that matter within the originating system:

```yaml
source_representation:
  source_system: warehouse_a
  native_id: 7f6b9d20
  qualified_name: PROD.SALES.FCT_ORDER_LINE
  native_type: TRANSIENT_TABLE
  native_attributes:
    change_tracking: false
    retention_days: 1
    clustering_expression:
      - BOOKING_DATE
```

The canonical model can classify the object as a `table` without deleting the fact that the source platform calls it a `TRANSIENT_TABLE`.

This separation prevents two common failures:

- the enterprise model grows until it mirrors every product-specific option;
- local attributes are discarded because they do not fit a small generic schema.

Canonical types should support common navigation and control. Source-native extensions should preserve fidelity and troubleshooting value.

## Stable identity is the foundation

Names are useful for discovery. They are poor primary identifiers.

A display name can change. The same name can exist in multiple schemas. Development and production objects can have identical qualified names in separate accounts. A copied dataset may preserve the name while becoming a different operational object. A renamed object may remain the same logical asset.

Identity resolution should evaluate several signals together.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/build-a-unified-metadata-model-img2-en.png"
        alt="Identity resolver connecting source, raw, transformation, semantic, BI and catalog representations using platform, environment, qualified name, stable source ID, version and lineage evidence"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Display names support search, but identity decisions require platform, environment, qualified names, stable source identifiers, versions and relationship evidence.
    </figcaption>
</figure>

A robust identity key usually considers:

```text
Platform
+ Account or tenant
+ Environment
+ Namespace
+ Qualified name
+ Stable native identifier
+ Object type
+ Version or effective interval
```

Additional evidence can include:

- creation and modification history
- lineage continuity
- deployment manifest mappings
- repository identifiers
- connector-specific aliases
- approved manual mappings
- checksums of structural metadata
- ownership continuity
- synchronization configuration

The result of identity resolution should not be limited to `match` or `no match`.

Useful outcomes are:

```text
Same logical asset
Derived asset
Replica
Version of asset
Alias of asset
Replaced by asset
Unresolved candidate
```

These relationship types prevent false equivalence.

### Same logical asset

Two representations refer to the same governed object.

Example:

```text
Warehouse table representation
↔
Catalog representation of that warehouse table
```

The catalog record is not a new dataset. It is another representation of the same logical asset.

### Derived asset

One asset is produced from another through transformation.

Example:

```text
raw.crm_customer
→ derived into
mart.customer_profile
```

The records may overlap, but the assets have different grain, semantics, lifecycle and ownership.

### Replica

One asset is an operational copy of another.

Example:

```text
CRM.customer
→ replicated as
raw.crm_customer
```

A replica may preserve structure closely while having a different location, freshness contract and access model.

### Unresolved candidate

Evidence suggests a possible relationship, but it is not sufficient for approval.

This state is essential. Forcing uncertain matches creates more damage than leaving a candidate unresolved.

## Preserve identifiers as aliases, not replacements

A unified asset should keep every relevant local identifier attached to its origin.

```yaml
asset_identity:
  canonical_id: asset:customer_master:prod
  aliases:
    - system: crm
      environment: prod
      native_id: entity-4921
      qualified_name: crm.customer
      valid_from: 2024-02-01
      valid_to: null
    - system: warehouse
      environment: prod
      native_id: 7f6b9d20
      qualified_name: raw.crm_customer
      relationship: replica
      valid_from: 2024-02-02
      valid_to: null
    - system: catalog
      environment: prod
      native_id: 983441
      qualified_name: Customer Master
      relationship: representation
      valid_from: 2025-01-12
      valid_to: null
```

The canonical identifier supports internal references. It must not overwrite source identifiers.

Local identifiers are needed for:

- connector updates
- API calls back to source systems
- change detection
- deletion handling
- troubleshooting
- synchronization
- audit
- migration
- version comparison

A unified model that cannot point back to the exact source object is not operationally reliable.

## Separate asset identity from asset versions

An asset and one state of that asset are not the same concept.

A table can retain its logical identity while its schema, description, owner or classification changes. A KPI can retain its name while its formula changes. A policy can move from draft to approved and later be superseded.

A practical model separates:

```text
Asset identity
Asset version
Attribute assertion
Relationship version
```

For example:

```yaml
asset:
  asset_id: asset:kpi:monthly_net_revenue
  type: kpi

asset_version:
  version_id: asset:kpi:monthly_net_revenue:v5
  version_number: 5
  valid_from: 2026-07-01T00:00:00Z
  valid_to: null
  status: approved
  replaces: asset:kpi:monthly_net_revenue:v4
```

Relationships also need history:

```yaml
relationship:
  relationship_id: rel:184288
  type: calculated_from
  from_asset: asset:kpi:monthly_net_revenue
  to_asset: asset:column:net_sales_amount
  valid_from: 2026-07-01T00:00:00Z
  valid_to: null
  approval_status: approved
```

When the KPI starts using a different field, the previous edge should be closed, not deleted.

Historical relationships enable questions such as:

- Which columns supported the KPI at the end of the previous quarter?
- Which owner approved the definition used in a past report?
- When did a sensitive classification begin to apply?
- Which reports consumed the deprecated table before migration?
- Which model version was trained on a particular dataset version?

Without relationship history, impact analysis becomes limited to the present.

## Model metadata values as assertions with provenance

A field such as `description`, `owner` or `classification` can receive values from several sources.

A source API may provide a technical description. A repository may provide a model description. A detector may propose a sensitive-data category. A Steward may approve a business definition. A policy engine may derive a retention requirement.

These should not silently overwrite each other.

Represent them as assertions:

```yaml
assertion:
  assertion_id: assertion:99218
  subject: asset:column:customer_email
  predicate: classification
  value: confidential_pii
  source:
    system: classifier_service
    method: detected
    model_version: pii-detector-4.2
  confidence: 0.94
  workflow_status: proposed
  observed_at: 2026-07-23T08:30:00Z
  valid_from: 2026-07-23T08:30:00Z
  valid_to: null
```

A later approved assertion can coexist:

```yaml
assertion:
  subject: asset:column:customer_email
  predicate: classification
  value: confidential_pii
  source:
    system: governance_workflow
    method: declared
    supplied_by: role.customer_data_steward
  workflow_status: approved
  approved_by: role.customer_data_owner
  approved_at: 2026-07-23T14:10:00Z
```

This model distinguishes evidence from authority.

## Raw, normalized and approved metadata are different layers

A reliable architecture does not treat ingestion, normalization and governance approval as one operation.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/build-a-unified-metadata-model-img3-en.png"
        alt="Three metadata layers showing unchanged raw source metadata, normalized canonical metadata and approved governed metadata with proposed, rejected and deprecated states"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Raw, normalized and approved metadata serve different purposes. All layers must retain provenance to the original source and transformation step.
    </figcaption>
</figure>

### Raw source metadata

The raw layer stores the connector result as received:

- original payload
- original identifiers
- native field names
- source timestamps
- connector version
- collection time
- request scope
- response status

The raw layer supports replay, debugging and future remapping. It is not normally the primary user-facing representation.

### Normalized metadata

The normalized layer maps source content into canonical types and fields:

- canonical asset type
- standardized timestamps
- normalized environment names
- resolved identifier candidates
- mapped relationship types
- source aliases
- parsed native attributes

Normalization should be deterministic and versioned. The platform must be able to explain which rule transformed a raw value.

### Approved metadata

The approved layer contains governed values and relationships:

- accepted descriptions
- validated ownership
- approved classifications
- curated Business Term mappings
- accepted Data Product membership
- certified KPI definitions
- approved policy applicability

Approved metadata can be published as the preferred view. It should still reference the evidence and source values behind the decision.

### Proposed, rejected and deprecated states

Not every value becomes approved.

A complete workflow needs at least:

```text
Raw
Normalized
Proposed
Approved
Rejected
Deprecated
```

`Rejected` does not mean deleted. Rejection records should preserve the proposal, evidence, reviewer and reason.

`Deprecated` does not mean invalid history. Deprecated values and relationships may still be required to interpret past decisions and assets.

## Conflict resolution needs explicit precedence rules

Conflicts are expected in a unified model.

Examples include:

- source description differs from Steward-approved definition;
- detector proposes `PII`, but approved classification is `internal identifier`;
- two systems claim different owners;
- local term maps to two enterprise terms;
- lineage parser and runtime observation produce different edges;
- copied dataset inherits a policy that should not apply to the derived output.

A conflict engine should evaluate:

```text
Attribute type
+ Source authority
+ Method
+ Scope
+ Environment
+ Effective time
+ Approval status
+ Confidence
+ Explicit override
```

Precedence should be defined per attribute or relationship type, not through one universal rule.

A useful pattern is:

```text
Approved target override
> Approved transformation rule
> Approved source declaration
> Propagated metadata
> Detection proposal
```

For technical structure, the source platform may be authoritative. For business definition, the approved Steward workflow may take precedence. For lineage, observed runtime evidence may supplement but not necessarily replace declared design lineage.

## Resolve inherited metadata without hiding uncertainty

Metadata propagation becomes difficult when a derived asset combines several inputs.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/build-a-unified-metadata-model-img4-en.png"
        alt="Resolver combining confidential email and phone inputs with an internal customer identifier using lineage, transformation rules, target overrides and conflict policy"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Propagated classifications require lineage, transformation-aware rules and explicit precedence. Conflicts should remain unresolved when the available evidence is insufficient.
    </figcaption>
</figure>

Consider a derived column:

```sql
coalesce(email, phone, customer_id) as contact_reference
```

The inputs carry different metadata:

```text
email        → confidential PII
phone        → confidential PII
customer_id  → internal identifier
```

A resolver evaluates:

```text
Column lineage
+ transformation rule
+ approved target override
+ conflict policy
```

Possible results are:

- resolved metadata;
- proposed metadata;
- unresolved — review required.

The system should not automatically choose the least restrictive input. It should also not assume that every output inherits every source classification unchanged.

A transformation rule may state:

```text
If any possible output exposes direct contact data,
propose confidential PII.
```

An approved target override may confirm the classification. If the transformation hashes or tokenizes the values, a different rule may apply. The rule and evidence must remain visible.

## The simplest viable unified model

A team can start without implementing a complete enterprise knowledge graph.

A practical minimum contains six components.

### 1. A stable asset registry

Create one canonical identifier for every in-scope asset and preserve its source aliases.

Minimum fields:

```text
canonical asset ID
asset type
platform
environment
qualified name
native ID
lifecycle status
current version
```

### 2. A controlled type vocabulary

Define a small set of asset and relationship types needed for the first use cases.

Example asset types:

```text
system
dataset
table
column
pipeline
transformation
Business Term
KPI
Data Product
policy
person
role
report
model
```

Example relationship types:

```text
contains
reads
writes
derives
implements
calculated from
belongs to
consumed by
owned by
governed by
validated by
trained on
replaces
replicates
```

Do not create dozens of near-duplicate relationship names before governance rules exist.

### 3. Raw and normalized storage

Retain connector payloads and create a deterministic normalized representation. Store the mapping version used for each import.

### 4. Versioned assertions

Store descriptions, classifications, ownership and similar values as assertions with source, method, status and effective time.

### 5. Relationship history

Version material relationships instead of overwriting them. At minimum, preserve lineage, ownership, policy, term and consumer changes.

### 6. Search, traversal and API access

Provide three access patterns:

```text
Search by names, aliases and descriptions
Traverse typed relationships
Read and write through governed APIs
```

Search finds likely starting points. Graph traversal answers connected questions. APIs make the model operational.

## Alternative implementation patterns

The logical model does not require one storage technology.

### Relational core with relationship tables

A relational implementation can use:

```text
assets
asset_versions
source_aliases
assertions
relationships
relationship_versions
approvals
```

Advantages:

- familiar operational model;
- strong transactions and constraints;
- straightforward reporting;
- manageable for moderate graph depth.

Limitations:

- recursive traversal can become complex;
- highly connected exploration may require specialized indexing;
- schema changes may be slower when many new relationship types appear.

### Graph-native metadata store

A graph database can represent assets and relationships directly.

Advantages:

- natural multi-hop traversal;
- flexible relationship expansion;
- strong fit for lineage, impact and semantic navigation.

Limitations:

- operational and analytical skills may be less common;
- versioning and approval workflows still require explicit design;
- graph storage does not automatically solve identity or provenance.

A graph database is not a substitute for a metadata model. A poorly typed graph simply moves ambiguity into nodes and edges.

### Event-sourced metadata model

An event-sourced approach records changes such as:

```text
AssetDiscovered
AssetRenamed
RelationshipProposed
ClassificationApproved
OwnerChanged
AssetDeprecated
```

Advantages:

- complete history;
- replay and reconstruction;
- strong auditability.

Limitations:

- more implementation complexity;
- current-state projections must be maintained;
- event semantics require strict governance.

### Hybrid architecture

Many organizations use a hybrid:

```text
Raw object storage
+ relational control store
+ search index
+ graph projection
+ API layer
```

The raw store preserves source fidelity. The relational store manages identity, workflow and versions. The search index supports discovery. The graph projection supports traversal. The API layer exposes a stable contract.

The model should remain consistent even when several physical stores are used.

## A concrete example: one customer dataset across six systems

Assume a customer object appears in the following systems:

```text
CRM: CUSTOMER
Warehouse raw: RAW_CRM_CUSTOMER
dbt: STG_CUSTOMER
Semantic model: DIM_CUSTOMER
BI application: Customer Analysis
Catalog: Customer Master
```

A weak catalog may create six unrelated records or merge all six into one record called `Customer`.

A unified model represents the distinctions:

```text
CRM.CUSTOMER
  └─ replicated as → RAW_CRM_CUSTOMER
       └─ transformed into → STG_CUSTOMER
            └─ derives → DIM_CUSTOMER
                 └─ consumed by → Customer Analysis
```

The catalog representation points to these assets rather than becoming another data asset.

Business context is linked separately:

```text
DIM_CUSTOMER
  ├─ implements → Business Term: Customer
  ├─ belongs to → Data Product: Customer 360
  ├─ owned by → Role: Customer Data Owner
  ├─ governed by → Policy: Customer Data Handling
  └─ consumed by → Report: Customer Analysis
```

Source-native identifiers remain attached to each technical asset. The enterprise term does not erase local terms such as `Account`, `Debtor` or `Party`; those can remain synonyms or domain-specific concepts with explicit mappings.

When `STG_CUSTOMER` is renamed to `STG_CUSTOMER_CURRENT`, the repository identifier and deployment mapping may allow the system to retain the logical identity and add the previous qualified name as a historical alias.

When `DIM_CUSTOMER` changes from one row per customer to one row per customer and legal entity, that may require a new asset version or a new asset identity, depending on the organization’s identity policy. The decision should be explicit because the grain changed.

## Common anti-patterns

### One generic `asset` record

Every object receives the same fields and type-specific context is stored in unstructured JSON.

This appears flexible but usually weakens validation, search and governance. A policy, person, table and KPI do not have the same lifecycle or required relationships.

### Identity by display name

Objects with the same name are merged, or renamed objects are duplicated.

Display names should be indexed for discovery, never treated as sufficient identity evidence.

### Silent last-write-wins updates

The latest connector or user update replaces the current value without preserving source, approval or previous state.

This destroys auditability and makes conflicts invisible.

### Flattening lineage into text

A table record contains a description such as `loaded from CRM`.

Impact analysis requires explicit process and asset relationships, not prose.

### Copying approved values everywhere

One approved definition or classification is physically copied to every related object.

Copies become stale and make it difficult to distinguish inheritance, reference and local override.

### Deleting rejected proposals

Rejected suggestions disappear from the system.

This removes audit evidence and prevents future matching logic from learning which patterns were misleading.

### Treating graph technology as the design

A graph database is selected before asset identity, relationship types, authority and versioning rules are defined.

The result is a visually connected but semantically inconsistent graph.

### Forcing every local concept into one enterprise definition

Legitimate domain differences are collapsed into a universal term.

A unified model should connect differences, not hide them.

## Decision guidance

Before adding a new asset type, ask:

```text
Does it have its own identity?
Does it have an independent lifecycle?
Can it have an owner or policy?
Can it be versioned?
Will users traverse to or from it?
```

Before adding a relationship type, ask:

```text
What exact claim does the edge make?
Is direction meaningful?
Can the relationship change over time?
Does it require approval?
Can it be inferred, declared or observed?
```

Before merging two records, ask:

```text
Are they the same logical asset?
Are they versions, replicas or derivatives?
Which evidence supports the decision?
Could the decision be reversed?
Who can approve an ambiguous match?
```

Before selecting a storage technology, ask:

```text
Which traversal questions matter?
Which workflows require transactions?
How much history must remain queryable?
Which APIs must be supported?
How will search be indexed?
How will raw source payloads be retained?
```

The design should follow the questions and controls, not the preferred database category.

## Key recommendations

1. Define canonical asset and relationship types, but preserve source-native attributes.
2. Use stable canonical identifiers and retain every relevant local identifier as a versioned alias.
3. Distinguish same asset, replica, derivative, version, replacement and unresolved candidate.
4. Separate asset identity from asset versions, assertions and relationship history.
5. Retain raw metadata and make normalization rules deterministic and versioned.
6. Represent proposed, approved, rejected and deprecated values explicitly.
7. Define precedence per metadata attribute and relationship type.
8. Preserve provenance for every value, mapping, override and approval.
9. Support search, typed graph traversal and governed API access.
10. Start with one bounded Data Product or domain and expand the model through proven use cases.

## The next question is where this model should be operated

A unified metadata model defines what must be connected and how identity, relationships, versions and authority are represented.

It does not yet determine where metadata should be governed or operated.

Some organizations centralize most decisions in one platform. Others keep ownership and authoring within domains while using a central index. Large ecosystems may distribute storage and control across products and teams.

The next part examines these operating choices:

> **Centralized, Federated or Distributed Metadata — how to divide authority, ownership and platform responsibility without losing enterprise discovery and control.**
