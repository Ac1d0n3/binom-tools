---
title: Harvest Metadata Automatically — Collect Technical and Operational Context Without Rebuilding It Manually
description: A practical architecture for harvesting schemas, lineage, runtime, usage, access and quality evidence through supported interfaces while preserving provenance, versions, freshness and change history.
category: Data Governance
tags:
  - metadata
  - metadata-harvesting
  - metadata-ingestion
  - data-catalog
  - data-lineage
  - openlineage
  - dbt
  - information-schema
  - data-observability
  - schema-drift
  - metadata-governance
  - active-metadata
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 4
seriesTitle: MetaData Deep Dive
hero: images/playbooks/harvest-metadata-automatically-hero.png
publishedAt: 2026-06-23 10:00
---

## Metadata collection becomes a bottleneck when machines wait for manual documentation

Modern data platforms continuously create technical and operational context.

A database knows which tables and columns exist. Transformation code knows which inputs create which outputs. An orchestration platform knows when a pipeline ran and whether it succeeded. A warehouse records queries and access. A quality system observes failed checks. A semantic model knows its measures, relationships and report dependencies.

None of this context should be recreated manually in a central catalog.

Manual reconstruction creates three problems at the same time:

- collection is too slow for the rate of technical change;
- copied metadata becomes stale without being visibly wrong;
- valuable operational evidence is reduced to occasional documentation snapshots.

The result is often a catalog that looks organized but cannot answer current questions:

- Was this column added today or three months ago?
- Which pipeline version created the current table?
- Is the lineage based on parsed code, an observed execution or a manual declaration?
- When was the source last collected successfully?
- Has an asset disappeared, or did the connector merely fail?
- Which reports still use the deprecated field?
- Did a sensitivity detector find a new pattern after the schema changed?

> **Technical and operational metadata should be harvested automatically through supported interfaces, normalized with provenance and monitored for change. Human effort should be reserved for meaning, accountability, approval and exceptions that machines cannot determine reliably.**

Automatic harvesting is not the same as blindly copying everything into one platform.

A trustworthy harvesting architecture must know:

- which interface supplied the metadata;
- which source object and version it represents;
- when it was collected or observed;
- how it was normalized;
- whether it is complete;
- how fresh it is expected to be;
- whether a missing object is deleted, inaccessible or temporarily unavailable;
- which team owns the connector and its failure handling.

The collection process is therefore a governed data pipeline for metadata.

## Harvest through the interface closest to the original evidence

There is no single universal metadata connector.

Different systems expose different parts of the truth through database catalogs, APIs, generated artifacts, logs, events, code or repositories. Each method has its own coverage, latency and failure modes.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/harvest-metadata-automatically-img1-en.png"
        alt="Six metadata harvesting methods feed an extraction, normalization, identity resolution, provenance, versioning and publishing pipeline"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        No connector captures every metadata dimension. A reliable collection design combines several supported interfaces and records the freshness and coverage of each contribution.
    </figcaption>
</figure>

### Database catalogs and information schemas

Database-native metadata is normally the best source for current physical structure.

Typical candidates include:

- databases, schemas, tables and views
- columns and data types
- nullability
- keys and constraints
- object comments
- partitions and clustering information
- privileges and grants
- view definitions
- dependencies exposed by the platform
- object creation, alteration and deletion timestamps where available

The information schema provides a relatively portable starting point for relational systems. Product-specific system catalogs often expose more detail, but they also require platform-specific logic.

A scanner must not assume that an empty result means an empty database. Metadata views are commonly permission-scoped. A connector may see only the objects its service identity is allowed to inspect.

The collected result should therefore include:

```text
Objects returned
+ scope requested
+ permissions used
+ collection timestamp
+ collection status
+ completeness assessment
```

Without scope evidence, “no object found” cannot be distinguished from “object not visible”.

### Product APIs

APIs are appropriate when a product owns metadata that cannot be reconstructed correctly from database structures.

Examples include:

- semantic models
- measures and dimensions
- dashboards and reports
- data-source connections
- certification state
- schedules
- owners and collaborators
- workflow status
- policy assignments
- quality monitors
- incidents
- model runs
- deployment history

An API connector should use stable object identifiers rather than display names wherever possible.

Display names change. Folder paths move. Localized labels differ. A product-generated immutable identifier, combined with the product instance and object type, is normally a better identity anchor.

The connector must also record pagination, API version, rate limits, permission scope and partial failures. A successful HTTP response does not prove that every page, workspace or object was collected.

### Manifests and documentation artifacts

Build and deployment tools often generate structured artifacts that are more reliable than reverse-engineering the final platform.

Artifacts can contain:

- nodes and object identities
- source references
- dependencies
- tests
- compiled logic
- descriptions
- tags and custom metadata
- execution results
- timing
- status
- freshness evidence
- code version or invocation identity

For dbt projects, generated artifacts such as `manifest.json`, `catalog.json`, `run_results.json` and source-freshness output can provide different views of declared structure, compiled dependencies, physical catalog information and execution evidence.

The critical design point is version alignment.

A manifest from one commit, a catalog from another environment and run results from a different deployment must not be merged as though they represent one coherent state.

A collected artifact set should therefore retain:

- project identity
- environment
- invocation identifier
- generated timestamp
- artifact schema version
- code revision
- deployment identifier
- producing command or job
- checksum
- collection timestamp

Artifacts should be published directly from CI or the execution environment where possible. Searching shared folders for “the latest JSON file” is not a reliable metadata pipeline.

### Query, audit and access logs

Logs provide evidence about what actually happened.

They can reveal:

- queries executed
- objects read and written
- users, roles or service accounts involved
- runtimes
- failures
- scanned or processed volume
- downstream dependencies inferred from queries
- unused or heavily used assets
- access to sensitive objects
- data movement between source and target objects

Observed metadata is valuable because it captures behaviour that static documentation cannot.

It also has limits.

A query log may be truncated, sampled or retained for a limited period. Dynamic SQL may be difficult to parse. Cached results may hide physical access. Service accounts may conceal the business consumer. A query touching a view does not automatically reveal the complete transformation meaning.

Observed lineage should therefore be labelled as observed, with its time window and confidence. It should not silently replace declared or parsed lineage.

### Events and OpenLineage messages

Events are suitable when metadata should be collected near the moment a deployment, run or state change occurs.

A lineage event can describe:

- a run
- the job being executed
- input and output datasets
- start, completion or failure state
- schema
- SQL or processing context
- parent-child runs
- quality assertions
- custom facets

Event-driven collection reduces the delay between technical change and metadata visibility.

It also introduces a new dependency: the producer must emit events reliably.

The collection platform must handle:

- duplicate delivery
- out-of-order arrival
- late events
- missing completion events
- schema evolution
- incompatible producer versions
- replay
- correlation across parent and child runs

Events should be idempotent. Reprocessing the same event must not create duplicate assets or relationships.

### Code parsers and repository scanners

Some metadata exists only in code.

A repository scanner can inspect:

- SQL models
- YAML definitions
- orchestration files
- infrastructure-as-code
- semantic-model definitions
- configuration files
- policy mappings
- documentation
- ownership files
- pull requests and commit history

Parsing code can reveal dependencies before deployment. This makes repository harvesting useful in CI, design review and impact analysis.

But parsed metadata is not automatically runtime truth.

Generated SQL, macros, templating, dynamic object names, stored procedures, external services and environment-specific configuration can make static analysis incomplete.

The result should distinguish:

```text
Declared relationship
Parsed relationship
Compiled relationship
Observed relationship
Approved relationship
```

These forms can reinforce each other. They should not be collapsed into one anonymous edge.

## The simplest viable implementation

A useful metadata harvesting platform does not need to begin with every system and every event.

Start with one critical data product and three collection paths:

1. a scheduled schema scan from the main data platform;
2. artifact publication from the transformation pipeline;
3. runtime and usage evidence from the execution platform.

Add a small source register that defines the contract for each connector.

```yaml
connector_id: warehouse-prod-schema
owner: Data Platform Team
source_type: database_catalog
source_instance: warehouse-prod
scope:
  databases:
    - analytics
collection:
  mode: incremental_with_periodic_full_scan
  expected_freshness: 6h
  checkpoint: last_altered_at
identity:
  namespace: warehouse-prod
  case_policy: preserve_and_normalize
reliability:
  retries: 5
  quarantine_invalid_records: true
  retain_raw_payload: true
deletion:
  confirm_after_missed_successful_scans: 2
security:
  service_identity: metadata_reader
  secret_reference: vault/metadata/warehouse-prod
```

The first implementation should provide:

- a stable connector identity;
- least-privilege authentication;
- one raw landing area;
- schema validation;
- canonical asset identifiers;
- provenance for every imported value;
- versioned snapshots or changes;
- connector freshness monitoring;
- retries and a quarantine path;
- deletion handling;
- a named operational owner.

This is enough to build a reliable foundation.

It is better to collect three metadata dimensions correctly than to deploy twenty connectors with unknown freshness, inconsistent identity and no failure ownership.

## Scheduled scans, event-driven collection and metadata streaming solve different problems

Collection mode should be selected according to the rate and importance of change.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/harvest-metadata-automatically-img2-en.png"
        alt="Comparison of scheduled scanning, event-driven metadata collection and metadata streaming, ending in a hybrid collection target"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Scheduled scans provide broad inventory, events provide timely state changes and streams provide continuous operational evidence. Most environments need a controlled hybrid.
    </figcaption>
</figure>

### Scheduled scan

A scheduled scan queries the source at defined intervals.

Strengths:

- simple operating model
- broad inventory
- good fit for schemas, permissions and slowly changing objects
- easy reconciliation against a complete source scope
- predictable load

Weaknesses:

- change detection is delayed until the next scan
- repeated full scans can be expensive
- large estates require partitioning and checkpoints
- an incomplete scan can resemble asset deletion
- rapidly changing runtime evidence may be lost

Suitable metadata:

- schemas
- tables and columns
- comments
- grants
- semantic objects
- configuration
- ownership registers
- slowly changing policy assignments

A periodic full scan remains useful even when incremental harvesting exists. It detects missed events, checkpoint errors and silent divergence.

### Incremental harvesting

Incremental harvesting is a scan pattern that requests only records changed after a checkpoint.

Possible checkpoints include:

- `last_modified`
- monotonically increasing sequence
- change token
- API cursor
- event offset
- repository commit
- artifact invocation
- partition date

The checkpoint must advance only after the collected batch has been durably stored.

Advancing it before persistence creates invisible data loss. Reusing it without idempotency creates duplicates.

Incremental harvesting reduces source load but requires a reconciliation strategy for:

- objects whose change timestamp is not updated reliably
- deletions
- late-arriving records
- clock skew
- source-side retention
- checkpoint corruption

### Event-driven collection

Event-driven collection reacts to a known trigger such as:

- deployment completed
- pipeline started or finished
- model built
- schema changed
- quality check failed
- report published
- ownership approved
- policy updated

Strengths:

- near-real-time visibility
- low unnecessary polling
- direct correlation with technical actions
- good support for workflow and control automation

Weaknesses:

- coverage depends on reliable event producers
- old systems may not emit events
- retries and dead-letter handling are required
- producers and consumers must manage schema evolution
- missing events may remain invisible without reconciliation

Events are particularly useful for state transitions. They are less suitable as the only source for a complete current inventory.

### Metadata streaming

Metadata streaming processes a continuous flow of operational events.

It is appropriate when the platform needs current evidence such as:

- pipeline states
- query activity
- access events
- schema-change events
- quality observations
- lineage events
- model inference events
- policy enforcement results

Strengths:

- high freshness
- scalable processing of operational evidence
- temporal analysis
- rapid detection and automation

Weaknesses:

- higher platform complexity
- ordering and replay requirements
- duplicate and late event handling
- retention and compaction decisions
- more difficult operational diagnosis
- risk of treating activity streams as authoritative master data

Streaming is not automatically better than scanning. It is justified when freshness changes decisions or controls.

### Hybrid collection is the practical target

A common target pattern is:

```text
Periodic full inventory
+ incremental scans
+ deployment artifacts
+ event-driven state changes
+ streamed operational evidence
→ reconciled metadata state
```

The full scan provides completeness. Incremental collection reduces load. Artifacts preserve declared and compiled context. Events reduce latency. Streams capture current behaviour.

The metadata platform must reconcile these inputs rather than assume they will always agree.

## Harvest different metadata dimensions without confusing evidence and decisions

Automatic collection can cover much more than names and data types.

### Schema evidence

Collect:

- asset identity
- physical location
- object type
- column order
- data type
- precision and scale
- nullability
- default values
- constraints
- partitioning
- comments
- object timestamps

Schema metadata is normally generated by the platform and can often be treated as an authoritative technical fact for the observed source and time.

### Lineage evidence

Collect:

- source-to-target relationships
- model dependencies
- column mappings
- transformation expressions
- job-to-dataset relationships
- report-to-model relationships
- parent-child runs
- write targets
- external inputs and outputs

Every lineage edge should record its method:

```text
manual
declared
parsed
compiled
observed
inferred
approved
```

A parsed dependency from version-controlled SQL and an observed dependency from an executed query are different evidence. Both can be useful.

### Runtime evidence

Collect:

- run identifier
- job and task
- start and end time
- duration
- status
- retry count
- environment
- code version
- rows processed
- bytes processed
- error class
- producing system
- parent run

Runtime metadata supports freshness, incident analysis and trust assessment.

It should remain historical. Replacing all run records with one `last_run_status` field destroys the evidence needed to understand instability.

### Usage and access evidence

Collect where legally and operationally appropriate:

- querying user, role or service identity
- object accessed
- access timestamp
- operation type
- report views
- dashboard usage
- downstream extracts
- export activity
- write targets
- repeated failures
- last observed use

Usage is not ownership.

A heavily used table does not automatically have an accountable owner. A rarely queried asset may still be legally required or operationally critical. Usage helps prioritize review; it does not replace governance decisions.

### Quality and security evidence

Collect:

- test definition
- tested asset and field
- execution time
- result
- threshold
- failed-record count
- severity
- detector version
- sampled or complete scope
- sensitive-data finding
- policy-evaluation result
- masking or access-control evidence

A detector finding is evidence, not necessarily an approved classification.

The central model should distinguish:

```text
detected sensitive pattern
→ proposed classification
→ reviewed classification
→ approved policy assignment
→ technical enforcement evidence
```

Collapsing these stages can cause automatic false positives to become uncontrolled governance decisions.

## Build metadata ingestion like a production data pipeline

A connector that writes directly into a searchable catalog skips the controls required for reliable operation.

A stronger architecture separates raw collection, validation, identity resolution and publication.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/harvest-metadata-automatically-img3-en.png"
        alt="Metadata connector pipeline from raw landing through validation, identity mapping, deduplication, relationship resolution and a versioned store to search, graph and APIs"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Raw metadata must remain available for troubleshooting and replay. Search and graph views should be published only after validation, identity resolution, provenance and version controls.
    </figcaption>
</figure>

### Connector

The connector authenticates to the source, applies a defined scope and retrieves metadata.

It should emit operational metrics such as:

- started and completed time
- records requested
- records received
- pages processed
- source throttling
- retries
- permission errors
- parse failures
- checkpoint
- source version
- connector version

### Raw metadata landing

The raw landing stores the source payload as received, plus an ingestion envelope.

```yaml
ingestion:
  connector_id: dbt-prod-artifacts
  collected_at: 2026-07-24T18:15:22Z
  source_event_time: 2026-07-24T18:14:58Z
  source_version: git:8f42c1a
  payload_schema: dbt-manifest-v12
  checksum: sha256:...
  batch_id: md_20260724_181522_00491
payload:
  ...
```

Raw retention enables:

- troubleshooting
- connector regression tests
- replay after mapping changes
- evidence of what the source returned
- comparison between connector versions
- recovery from downstream defects

Normalizing in memory and discarding the raw response removes this safety net.

### Schema validation

Validation checks whether the payload is structurally usable.

Possible outcomes:

- valid
- valid with unknown optional fields
- incompatible schema version
- missing mandatory identity
- malformed timestamp
- invalid enumeration
- oversized field
- unauthorized content
- quarantined

Unknown optional fields should not automatically fail collection. Metadata APIs evolve. The pipeline needs controlled forward compatibility.

### Identifier mapping

Identity resolution converts local identifiers into a canonical model.

A practical identifier may combine:

```text
platform instance
+ environment
+ asset type
+ native stable identifier
```

Example:

```text
snowflake://org/account/ANALYTICS/SALES/FCT_ORDER_LINE
dbt://sales-transform/prod/model.fct_order_line
bi://tenant/workspace/model/net-sales
```

Display names and paths remain searchable attributes. They should not be the only identity key.

### Deduplication

Duplicate records can arise from:

- retried API pages
- at-least-once event delivery
- repeated artifact publication
- overlapping connector scopes
- mirrored accounts
- multiple parsers
- replay

Deduplication should use stable event or record identity and source version, not only a hash of the normalized payload.

Two identical observations at different times may be meaningful evidence. Deduplication must not erase valid history.

### Relationship resolution

Relationships often arrive before both endpoints are known.

The pipeline should support:

- unresolved references
- placeholder identities
- late binding
- confidence
- relationship source
- relationship method
- validity interval

An unresolved lineage edge should not be discarded merely because the target asset has not yet been scanned.

### Versioned metadata store

The store should preserve both current state and change history.

A useful pattern separates:

- immutable observations
- normalized versions
- current resolved view
- approval state
- conflict state
- tombstones
- derived search index

This allows a central profile to show the current answer while retaining the evidence used to produce it.

### Search, graph and APIs

Consumer interfaces should be built from governed, versioned metadata rather than directly from connector payloads.

They can provide:

- search
- asset pages
- lineage graph
- impact analysis
- ownership views
- freshness status
- change history
- governance workflows
- machine-readable APIs
- policy and automation triggers

Publication should expose the provenance of each value. A clean UI must not create false certainty by hiding conflicting or stale evidence.

## Normalize identifiers and time without destroying source detail

Normalization is necessary because systems use different names, cases, timestamps and object models.

It is also a common place to lose evidence.

### Preserve native identity

For every asset and relationship, retain:

- canonical identifier
- native identifier
- source instance
- native path
- display name
- object type
- environment
- case-sensitive original
- normalized search form
- first observed time
- last observed time
- source version

Do not lowercase the only stored copy of an identifier. Some platforms are case-sensitive; others normalize unquoted identifiers. Preserve the original and derive a separate comparison form.

### Separate event time and collection time

At minimum, distinguish:

- when the source says the event occurred;
- when the connector retrieved it;
- when the pipeline processed it;
- when the normalized version became effective.

```yaml
source_event_time: 2026-07-24T17:59:02Z
collected_at: 2026-07-24T18:03:11Z
processed_at: 2026-07-24T18:03:18Z
effective_from: 2026-07-24T17:59:02Z
```

This distinction is essential for late events, delayed scans and replay.

Store time in a consistent canonical form, normally UTC, while retaining the original source value and timezone where it is relevant to audit or interpretation.

### Record every transformation applied during import

Examples:

- case normalization
- timezone conversion
- type mapping
- enum mapping
- namespace mapping
- path parsing
- SQL parsing
- owner identity resolution
- confidence calculation
- sensitive-field redaction

The normalized result should be reproducible from raw input and mapping version.

## Detect changes without treating every difference the same

Metadata changes have different technical and governance impact.

A new nullable column is not equivalent to a changed owner. A renamed description is not equivalent to a removed masking policy. A temporarily inaccessible asset is not equivalent to a confirmed deletion.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/harvest-metadata-automatically-img4-en.png"
        alt="Previous and current metadata snapshots are compared, classified and routed to auto-accept, review or block before publishing a new version"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technical drift and governance-significant change require different handling. The diff must classify impact before a new metadata version is published.
    </figcaption>
</figure>

### Compare previous and current state

A metadata diff should identify:

- added asset
- removed asset
- added field
- removed field
- renamed field
- changed data type
- changed nullability
- changed definition
- removed description
- changed owner
- changed sensitivity
- changed policy assignment
- changed lineage
- broken lineage
- changed quality state
- changed usage pattern

The comparison should use stable identity. Name-based comparison alone turns every rename into a deletion and creation.

### Classify the change

A useful classification model is:

```text
Technical informational
Technical breaking
Operational significant
Governance significant
Security significant
Unknown
```

Examples:

- new nullable field: technical informational
- string changed to integer: technical breaking
- pipeline freshness exceeded: operational significant
- owner removed: governance significant
- new sensitive-value pattern: security significant
- asset missing after failed scan: unknown

### Route the change

Possible outcomes:

- auto-accept
- accept and notify
- open review
- block publication
- quarantine
- request source confirmation
- create remediation task

Rules should consider asset criticality.

A field addition in an experimental sandbox and the same addition in a regulated financial data product should not follow identical governance.

### Handle deletion explicitly

Deletion detection is difficult because absence has several meanings:

```text
deleted
renamed
moved
permission removed
connector scope changed
source unavailable
scan incomplete
temporarily filtered
```

Use tombstones rather than immediately erasing the asset.

A deletion workflow can require:

1. successful scan of the expected scope;
2. asset missing from that scan;
3. optional confirmation in a second successful scan or deletion event;
4. impact analysis;
5. retention of historical identity and lineage;
6. publication of a deleted or retired state.

This preserves history and avoids mass deletion when a connector loses permission.

## Concrete example: harvesting `sales_amount`

Consider the same sales field used throughout the series.

The source application contains `ORDER_ITEM.NETWR`. A transformation model exposes `sales_amount`. A semantic model publishes the certified measure `Net Sales`. Reports consume that measure.

### Database scan

The warehouse scanner observes:

```yaml
asset: ANALYTICS.SALES.FCT_ORDER_LINE.SALES_AMOUNT
data_type: NUMBER(18,2)
nullable: false
comment: Net sales amount in reporting currency
last_altered: 2026-07-24T15:41:00Z
```

This is authoritative technical evidence for the scanned warehouse object at that time.

### Transformation artifact

The transformation artifact contributes:

- source dependency on `ORDER_ITEM.NETWR`
- model identifier
- column description
- tests
- tags
- compiled dependency graph
- code revision
- deployment invocation

It explains how the target field is created.

### Runtime event

The pipeline event contributes:

- run identifier
- start and completion time
- input and output datasets
- run status
- producing job
- code version
- parent orchestration run

It proves that a particular implementation executed.

### Query and access evidence

Warehouse logs show:

- which reports or service identities queried the field;
- when it was last used;
- whether downstream transformations wrote it elsewhere;
- whether access occurred through the base table, a view or a semantic service identity.

This supports impact analysis but does not redefine the field.

### Semantic API

The BI or semantic API contributes:

- measure identifier `Net Sales`
- expression
- format
- supported dimensions
- certification state
- reports using the measure
- workspace and owner

### Governance workflow

The governance system contributes approved decisions:

- enterprise business term
- Data Owner
- Data Steward
- sensitivity
- permitted usage
- review date
- certification status

The central profile combines these contributions without hiding their origin.

```yaml
canonical_asset: sales.fct_order_line.sales_amount

technical_state:
  source: warehouse-prod
  collected_at: 2026-07-24T18:03:11Z
  data_type: DECIMAL(18,2)
  nullable: false

transformation:
  source: dbt-sales-prod
  code_version: 8f42c1a
  upstream:
    - source.order_item.netwr
  tests:
    - not_null

runtime:
  source: orchestration-prod
  last_successful_run: 2026-07-24T17:58:43Z

semantic:
  source: semantic-sales-prod
  measure: net_sales
  certification: certified

governance:
  source: governance-workflow
  owner: Sales Data Owner
  sensitivity: internal
  status: approved
```

If the next schema scan finds `VARCHAR` instead of `DECIMAL`, the diff is technical and potentially breaking.

If the owner disappears from the governance source, the diff is governance-significant.

If the sensitive-data detector finds account numbers in the field, the result is a security-relevant proposal requiring review.

The harvesting platform should not process these changes as equivalent text updates.

## Reliability is part of metadata quality

A metadata value cannot be more trustworthy than the collection process that produced it.

### Retries and backoff

Retry transient failures such as:

- network interruption
- temporary API failure
- throttling
- expired token refresh
- short source unavailability

Use bounded retries with backoff and jitter.

Do not retry permanent errors indefinitely. Invalid credentials, unsupported schema versions and forbidden scopes require intervention.

### Checkpoints and idempotency

Every incremental connector needs:

- durable checkpoint
- batch identity
- idempotent write
- replay path
- reconciliation scan
- checkpoint ownership

The checkpoint should be committed only after the raw batch is durably stored.

### Freshness monitoring

Define freshness per connector and metadata type.

Examples:

```text
Warehouse schema: expected within 6 hours
Transformation artifacts: expected after every production deployment
Pipeline events: expected within 5 minutes
Usage logs: expected daily
Ownership registry: expected within 24 hours
```

Track:

- last successful collection
- last complete collection
- source event delay
- processing delay
- records collected
- expected versus observed volume
- consecutive failures
- oldest unresolved quarantine record

A green connector process with zero collected records can still be unhealthy.

### Failed-record quarantine

Do not reject a complete batch because one optional record is malformed.

Quarantine invalid records with:

- source payload
- validation error
- connector and batch identity
- retry state
- assigned owner
- first and last failure time
- resolution status

At the same time, do not publish relationships that depend on invalid mandatory identities.

### Replay

Replay is required when:

- mapping logic changes
- a parser is fixed
- an event consumer was unavailable
- canonical identity rules change
- downstream publication failed
- a connector regression must be corrected

Raw retention and versioned mappings make replay possible.

### Connector ownership

Every production connector needs two forms of ownership:

- technical owner for authentication, code, runtime and incidents;
- metadata owner for scope, semantics, freshness expectation and downstream use.

Without ownership, connector failures remain platform noise until users notice stale catalog pages.

## Alternative harvesting patterns

### Catalog-led scheduled scanning

A metadata catalog runs managed or custom scanners against registered systems.

Appropriate when:

- broad inventory is the first objective;
- systems expose stable read interfaces;
- near-real-time collection is not required;
- the team wants centralized connector operations.

Main risks:

- connector coverage may be uneven;
- platform-native identifiers can be transformed opaquely;
- raw responses may not remain accessible;
- scan schedules may hide freshness differences;
- custom metadata may still require separate paths.

### CI-led artifact publication

Repositories publish metadata artifacts during validation, build or deployment.

Appropriate when:

- transformation and semantic definitions are version controlled;
- code revision must remain linked to metadata;
- changes should be reviewed before deployment;
- pre-deployment impact analysis is needed.

Main risks:

- runtime-only changes remain invisible;
- failed or bypassed CI paths create gaps;
- artifacts from different environments can be mixed;
- deleted repository objects require explicit tombstones.

### Event-bus collection

Platforms emit deployment, run, schema, quality and governance events to a shared bus.

Appropriate when:

- many producers can emit reliable events;
- low latency matters;
- replay and schema governance already exist;
- metadata should trigger automated controls.

Main risks:

- missing producers create false completeness;
- event contracts evolve;
- duplicate and late events require disciplined handling;
- current state must be reconstructed from event history or compacted views.

### Repository-first parsing

A scanner parses code and configuration directly from repositories.

Appropriate when:

- metadata is needed before deployment;
- code is the authoritative technical definition;
- the target platform exposes weak lineage;
- pull-request validation is important.

Main risks:

- dynamic behaviour cannot be resolved statically;
- parser support varies by language and dialect;
- generated code can differ from committed source;
- repository presence does not prove production deployment.

### Source-native publication

Each product or domain publishes a normalized metadata package through a defined contract.

Appropriate when:

- source teams understand their own metadata best;
- the organization uses federated ownership;
- central teams should not build every connector;
- shared schemas and validation can be enforced.

Main risks:

- inconsistent implementation quality;
- missing operational support;
- contract drift;
- duplicated publisher logic.

This pattern works only when publishing metadata is treated as an operational product responsibility.

## Common anti-patterns

### Scraping user interfaces when stable interfaces exist

HTML pages and browser network calls are not reliable contracts unless the product explicitly supports them.

UI scraping breaks when:

- labels change;
- layouts change;
- authentication flows change;
- pagination becomes virtualized;
- internal endpoints change;
- anti-automation controls are introduced.

Prefer documented APIs, exports, catalogs, artifacts, events or repository formats.

Unsupported scraping may be a temporary discovery aid. It should not become an invisible production dependency.

### Writing normalized records directly into the final catalog

This removes raw evidence, replay and diagnostic capability.

A connector mapping bug can then corrupt the only stored representation.

### Treating the latest write as truth

Different sources have different authority.

A recently detected owner name should not overwrite an approved ownership record merely because its timestamp is newer.

### Running full scans for every change

Frequent full scans can create unnecessary load and still fail to deliver low latency.

Use incremental collection and events where justified, while retaining periodic full reconciliation.

### Inferring deletion from one failed or partial scan

A permission loss can otherwise retire an entire data estate.

Require successful scope evidence and tombstones.

### Converting detector output directly into approved governance state

Automated detection should normally create evidence or a proposal.

Approval rules depend on confidence, policy, criticality and accountable review.

### Using highly privileged connector identities

A metadata scanner rarely needs unrestricted data access.

Separate metadata visibility, log access and sample-data inspection. Grant only the permissions required for the defined collection scope.

### Collecting without a freshness contract

A connector that “runs nightly” does not define what users can trust.

Specify expected freshness, completeness and failure response.

### Hiding conflicts to keep the UI clean

Conflicts are governance information.

A central platform should expose conflicting values, their origins and the rule used to resolve the displayed current state.

## Decision guidance

For every metadata source, answer the following questions.

### Interface and authority

1. Which system creates the original evidence?
2. Is a documented API, catalog, export, artifact or event available?
3. Is the interface intended for production automation?
4. Which metadata dimensions does it actually cover?
5. Is the result authoritative, declared, parsed, observed, inferred or proposed?

### Identity and scope

6. Does the source provide stable identifiers?
7. How are environment, tenant and platform instance represented?
8. Is the returned scope permission-dependent?
9. How is completeness measured?
10. How are renamed and moved assets recognized?

### Freshness and change

11. How quickly can the metadata change?
12. Does lower latency change a decision or control?
13. Is full scanning, incremental harvesting, event collection or streaming appropriate?
14. Which checkpoint or event identity is available?
15. How are deletions confirmed?
16. How is periodic reconciliation performed?

### Reliability and operation

17. Who owns the connector?
18. Which failures are retried?
19. Where are invalid records quarantined?
20. How long is raw metadata retained?
21. Can every batch or event be replayed?
22. Which freshness and volume metrics are monitored?
23. What happens when the source schema changes?

### Security and governance

24. Which permissions does the connector require?
25. Does the payload contain sensitive operational or identity data?
26. Which values may be normalized, redacted or hashed?
27. Which harvested values can be published automatically?
28. Which changes require review or approval?
29. Which source wins when values conflict?
30. Is every published value traceable to raw evidence?

These answers determine whether the connector is merely extracting data or operating as a governed metadata product.

## Key recommendations

1. Harvest technical and operational metadata automatically wherever a supported source interface exists.
2. Use database catalogs for physical structure, APIs for product-owned objects, artifacts for build context, logs for observed behaviour, events for timely state changes and parsers for code-native definitions.
3. Record the method and freshness of every collected metadata value.
4. Preserve raw source payloads before normalization.
5. Use stable native identifiers combined with source instance, environment and asset type.
6. Keep original case, timestamps and source fields alongside normalized values.
7. Separate source event time, collection time, processing time and effective time.
8. Version mappings, schemas and connector code.
9. Treat scheduled scans, incremental harvesting, events and streams as complementary patterns.
10. Retain periodic full reconciliation even when low-latency collection exists.
11. Make ingestion idempotent and replayable.
12. Store unresolved relationships instead of silently dropping them.
13. Distinguish declared, parsed, compiled, observed, inferred, proposed and approved metadata.
14. Preserve run and quality history instead of storing only the latest status.
15. Detect schema drift and governance-significant change through classified metadata diffs.
16. Use tombstones and confirmation rules for deleted assets.
17. Monitor connector freshness, completeness, volume and consecutive failures.
18. Quarantine invalid records with accountable resolution.
19. Assign a technical owner and a metadata owner to every production connector.
20. Use least-privilege service identities and documented secret management.
21. Prefer supported APIs, exports, artifacts and events over UI scraping.
22. Never use last-write-wins as a universal conflict rule.
23. Do not convert automated detection directly into approved governance state without an explicit rule.
24. Begin with one important data product and prove schema, lineage, runtime and usage collection end to end.
25. Treat metadata harvesting as a production data pipeline, not as a background catalog feature.

## The next step: write metadata people and machines can understand

Automatic harvesting solves the volume and freshness problem for technical and operational context.

It does not automatically create understandable metadata.

A scanner can identify that `sales_amount` is a `DECIMAL(18,2)`. A parser can extract its upstream fields. A runtime event can prove that the model executed. A query log can show that reports use it.

None of these facts alone explains precisely what the field means, which business situations it includes, which exclusions apply, which grain it represents or how an AI system should interpret it.

The next part, **Write Metadata People and Machines Can Understand**, focuses on descriptions, definitions, examples, constraints and structured context that remain useful to business users, engineers, governance processes and AI systems.
