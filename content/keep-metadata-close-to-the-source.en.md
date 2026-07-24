---
title: Keep Metadata Close to the Source — Maintain Context Where Knowledge and Responsibility Exist
description: A practical architecture for assigning metadata to the systems and teams that can keep it correct while enabling central discovery, governance, lineage and controlled synchronization.
category: Data Governance
tags:
  - metadata
  - metadata-governance
  - data-catalog
  - data-provenance
  - data-lineage
  - dbt
  - semantic-layer
  - business-intelligence
  - federated-governance
  - metadata-ownership
  - active-metadata
  - data-products
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 3
seriesTitle: MetaData Deep Dive
hero: images/playbooks/keep-metadata-close-to-the-source-hero.png
---

## Metadata becomes unreliable when it is maintained in the wrong place

A central metadata platform creates an attractive promise: one place for definitions, ownership, classifications, lineage, quality, policies and search.

The problem begins when “one place to find metadata” is interpreted as “one place where every metadata value must be authored manually”.

That approach separates context from the systems and teams that can keep it correct.

A database column is renamed, but the copied catalog description remains unchanged. A transformation rule is modified in code, but its manually rewritten explanation is not updated. A semantic measure changes its filter behaviour, while the central glossary still presents the previous definition. A policy exception expires in the governance workflow, but the copied tag remains active elsewhere.

The catalog may still look complete. The information is no longer trustworthy.

> **Metadata should be maintained as close as practical to the system, code and accountable team that know its meaning. A central platform should connect, index and distribute that context rather than become the only place where truth is manually recreated.**

“Close to the source” does not mean that every metadata value must remain physically isolated. It means that authority and maintenance responsibility stay close to the knowledge required to keep the value correct.

A central system may store a searchable copy. It may normalize values, connect relationships and trigger workflows. It should still retain where the value came from, which system approves it and whether the central representation is authoritative, referenced, copied or synchronized.

## Put each metadata type where it belongs

Different metadata types require different systems of record.

A source application knows original field meaning, valid process states and source constraints. Transformation code knows derived logic, tests and dependencies. A semantic layer knows measures, dimensions and presentation behaviour. A governance platform knows enterprise vocabulary, policy, accountability and cross-system decisions.

These perspectives should be connected, but they should not be collapsed into an anonymous central record.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/keep-metadata-close-to-the-source-img1-en.png"
        alt="Source system, transformation repository, semantic and BI layer, and governance platform connected to a unified discovery and control layer"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Each metadata attribute should have one accountable origin. Central discovery connects source-native, transformation, semantic and governance context without making every attribute centrally authored.
    </figcaption>
</figure>

### Source-native metadata belongs with the operational source

The source system or its accountable business team should normally remain authoritative for:

- original business object and field meaning
- valid codes and status transitions
- source identifiers
- source keys and constraints
- source-of-record responsibility
- operational process rules
- source-side help text
- audit behaviour
- local system ownership

For example, an order-management system may define that `ORDER_STATUS = C` means that an order line was cancelled before fulfilment. That source meaning should not be invented downstream.

The central layer may expose a normalized label such as `Cancelled`. It should retain the original code, source object, source definition and accountable source owner.

### Transformation metadata belongs with transformation code

Derived meaning should remain with the versioned implementation that creates it.

Typical transformation metadata includes:

- source references
- renamed and derived columns
- joins
- filters
- calculations
- historization rules
- model dependencies
- tests
- contracts
- model and column descriptions
- code version
- transformation rationale

If a model defines `is_open_order`, the authoritative technical logic is the versioned transformation expression. Copying the formula manually into a catalog creates a second representation that can drift.

The better pattern is:

```text
Catalog representation
→ references transformation model and version
→ displays parsed or exported logic
→ adds approved business interpretation
```

The technical implementation remains with the code. The governance platform connects it to business meaning, ownership and policy.

### Semantic metadata belongs with the semantic or BI layer

The semantic layer should remain authoritative for analytical behaviour such as:

- measures
- dimensions
- hierarchies
- display names
- number and date formats
- units and currencies
- aggregation behaviour
- default filters
- time-intelligence rules
- supported analytical relationships
- certification of shared measures
- user-facing synonyms

A warehouse field can be technically correct while a semantic measure determines how users experience it.

For example, `net_amount_reporting_currency` may be additive by customer and product. The certified `Open Order Value` measure may additionally apply status filters and current-date logic. Those semantic rules should remain connected to the model that executes them.

### Governance metadata belongs with accountable governance processes

Enterprise-wide decisions should normally be maintained in the governance system or workflow that controls them.

Examples include:

- approved business terms
- enterprise definitions
- Data Owner and Data Steward accountability
- sensitivity classification
- retention class
- permitted usage
- policy assignment
- exception approval
- certification status
- review dates
- cross-system relationships
- conflict resolution decisions

Governance metadata is not automatically “central metadata”. A retention class may be approved centrally but technically enforced in several platforms. An owner may be maintained in a domain registry and referenced by the catalog. The decisive question is which process can keep the value controlled and current.

## Authority, storage and visibility are different decisions

For every metadata attribute, separate three questions:

1. **Where is the value authoritative?**
2. **Where is the value physically stored or cached?**
3. **Where must the value be visible and usable?**

These answers may point to three different systems.

A field description can be authoritative in a version-controlled repository, copied into a central search index and displayed in a BI development interface. The central copy supports discovery, but the repository remains the approved origin.

A quality result can be authoritative in the monitoring system, stored as a time-series record there and summarized centrally for governance reporting.

An enterprise sensitivity classification can be approved in a governance workflow, synchronized to a warehouse tag and exposed in a BI platform. The governance workflow remains the approval authority even when technical copies drive enforcement.

Confusing these dimensions produces unnecessary centralization.

## The simplest viable implementation

A useful architecture can begin without a large enterprise metadata platform.

Select one important data product and create a metadata ownership register. For each attribute, record:

- stable asset identifier
- metadata attribute
- authoritative system or process
- accountable role
- source object or record
- collection method
- update direction
- synchronization frequency
- approval status
- last successful collection
- conflict rule
- downstream consumers

A small example:

```yaml
asset_id: sales.order_line.net_amount

attributes:
  source_meaning:
    authority: source.order_management.NETWR
    accountable_role: Sales Operations Data Owner
    mode: reference

  physical_type:
    authority: warehouse.prod.sales_order_line.net_amount
    mode: harvested_copy
    refresh: every_schema_scan

  transformation_logic:
    authority: dbt.model.fct_sales_order_line.net_amount
    mode: reference
    version: git_commit

  semantic_label:
    authority: semantic.sales.net_amount
    mode: harvested_copy

  sensitivity:
    authority: governance.classification.sales_amount
    mode: approved_sync
```

The implementation can initially use:

- database comments for source-local descriptions
- version-controlled YAML for transformation and governance declarations
- parsed transformation artifacts for dependencies and tests
- BI exports or APIs for measures and report relationships
- governance workflow records for ownership, classification and approval
- a central index that joins stable identifiers and provenance

The objective is not to duplicate every value. It is to prove that each important attribute has a known origin, an accountable maintainer and a controlled path into central discovery.

## Store, copy, reference or synchronize

Not every metadata value should move in the same way.

The correct pattern depends on where knowledge is maintained, whether the source can be queried, whether a local copy is required, how quickly the value changes and which system approves it.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/keep-metadata-close-to-the-source-img2-en.png"
        alt="Decision flow for choosing whether a metadata attribute is stored centrally, referenced at the source, copied with provenance or synchronized bi-directionally"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The movement pattern should be chosen per attribute. Uncontrolled bi-directional editing creates the highest conflict and accountability risk.
    </figcaption>
</figure>

### Store centrally

Central storage is appropriate when the metadata represents an enterprise decision that has no better operational home.

Examples:

- enterprise glossary term
- cross-platform data-domain assignment
- governance review status
- policy exception
- enterprise certification decision
- relationship between assets from different systems

Main risk:

- the central platform becomes a dumping ground for values that should have remained linked to source systems

Central storage is justified by central accountability, not merely by central visibility.

### Reference the source

A reference is appropriate when the source remains accessible and provides the value reliably.

Examples:

- link to version-controlled transformation definition
- source-system code list
- semantic measure definition
- policy record
- quality monitor
- run evidence

Main risk:

- the reference breaks, permissions prevent access or the source cannot provide a stable identifier

A reference should therefore include:

- source-system identity
- stable source object
- source version where relevant
- access method
- last successful validation
- fallback behaviour

### Copy with provenance

A copy is appropriate when central search, performance, resilience or historical comparison requires a local representation.

Examples:

- database schema harvested into a catalog index
- model descriptions copied from a repository
- current semantic objects imported for discovery
- ownership records cached for search
- latest quality state summarized centrally

Main risk:

- the copy becomes stale and is mistaken for the authoritative value

Every copied value should retain:

- authoritative source
- source object identifier
- collection timestamp
- source version
- transformation applied during import
- synchronization status
- expiration or freshness expectation
- conflict state

A copied value without provenance is not governed metadata. It is an undocumented duplicate.

### Synchronize bi-directionally

Bi-directional synchronization is appropriate only when both systems must support controlled editing and a clear conflict model exists.

Possible examples:

- an approved business description can be proposed from a catalog and committed back to a repository through a reviewed pull request
- a governance workflow approves a classification and publishes it to technical enforcement systems
- a source team updates ownership through a governed central workflow that writes to the domain registry

Main risk:

- competing edits, loops, silent overwrites and unclear approval authority

The safe pattern is not unrestricted two-way editing. It is a controlled workflow:

```text
Propose change
→ Validate
→ Identify authority
→ Review
→ Approve
→ Write to authoritative system
→ Republish confirmed value
```

The central platform may initiate a task or proposal. It should not silently overwrite a source-owned value.

## Source ownership with central discovery

A federated metadata architecture keeps authority distributed while making metadata centrally searchable and governable.

Source systems publish metadata through connectors, exports, events or APIs. The central index creates search, relationships, governance views and machine-accessible interfaces.

Return paths represent tasks, review requests or approved changes. They are not permission for silent replacement.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/keep-metadata-close-to-the-source-img3-en.png"
        alt="Database catalog, dbt repository, semantic model, identity platform and observability system publishing metadata into a central metadata index"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Central discovery can connect distributed metadata authorities. Updates should return through explicit tasks and approval workflows rather than uncontrolled overwrites.
    </figcaption>
</figure>

A central metadata record should be able to answer:

- Which source supplied this value?
- Is the value authoritative, copied, inferred or proposed?
- Who can approve a change?
- Which source version was collected?
- When was it last synchronized?
- Has the source changed since collection?
- Is another system presenting a conflicting value?
- Which downstream systems use the value?
- What happens when the source is unavailable?
- Can the value trigger an automated control?

This turns the central platform into a control plane rather than a second authoring environment for every metadata field.

## A concrete example: `sales_amount`

Consider a source field called `NETWR`, transformed into `sales_amount`, exposed through a semantic measure called `Net Sales` and documented in a governance platform.

### Source system

The source system remains authoritative for:

- `NETWR` as the original technical identifier
- document-currency storage
- valid source records
- source-side cancellation states
- source owner
- source constraints

### Transformation repository

The transformation layer remains authoritative for:

- rename from `NETWR` to `sales_amount`
- currency conversion
- exclusion rules
- treatment of returns
- joins to exchange rates
- model tests
- source-to-target lineage
- code version

Example:

```yaml
models:
  - name: fct_sales_order_line
    description: Governed sales-order-line fact model.
    columns:
      - name: sales_amount
        description: >
          Net order-line amount in reporting currency after approved
          line-level discounts and before tax.
        config:
          meta:
            authoritative_logic: true
            source_field: ORDER_ITEM.NETWR
            business_term: net_sales_amount
```

The repository description should explain the derived field. It should not redefine the entire source process or enterprise policy.

### Semantic and BI layer

The semantic layer remains authoritative for:

- user-facing label `Net Sales`
- aggregation behaviour
- currency formatting
- supported dimensions
- default date context
- certified measure identifier
- local versus shared measure status

A report can still create a local measure. The central metadata layer should record that divergence rather than silently treating it as certified.

### Governance platform

The governance platform remains authoritative for:

- approved enterprise term
- Data Owner and Data Steward
- sensitivity
- allowed usage
- certification status
- review date
- relationship to financial reporting policy

The platform links these decisions to the source field, transformation column and semantic measure.

### Central index

The central index does not select one text field and discard the rest. It presents a connected profile:

```text
Original source meaning
+ Derived transformation definition
+ Semantic behaviour
+ Governance decision
+ Current operational evidence
+ Consumer relationships
```

Each contribution retains its provenance and authority.

## Practical implementation patterns

### Databases

Use database-native metadata for facts the database can keep current:

- object and column identity
- data types
- nullability
- keys and constraints
- grants
- views and dependencies
- database comments where they are operationally maintained

Do not force the database catalog to become the enterprise glossary. A column comment may provide local context, but enterprise terms, policy approvals and cross-platform relationships usually require another process.

Where source comments are valuable, harvest them automatically and preserve the source object identifier.

### dbt and transformation repositories

Keep derived logic, tests, dependencies, contracts and model descriptions with version-controlled transformation code.

A practical pattern is:

```text
YAML and SQL
→ parse or build metadata artifacts
→ validate required fields
→ publish to central index
→ keep repository as authority
```

Custom metadata can record ownership references, classifications, domain, lifecycle state or governance links. These values need a controlled vocabulary and validation. A free-form key-value structure alone does not create governance.

Avoid copying SQL expressions into a catalog as manually maintained prose. Link the catalog to the model, field and code version, then add the business explanation that code alone cannot provide.

### Semantic and BI tools

Keep measures, dimensions, formats, hierarchies and presentation rules with the semantic model that executes them.

Harvest:

- model objects
- measure expressions
- report dependencies
- local calculations
- usage
- owners
- certification state
- access relationships

The central platform should distinguish:

- shared semantic measure
- report-local calculation
- copied label
- business glossary term
- detected synonym

These objects may use similar names but have different authority.

### Governance platforms

Use governance platforms for decisions that span systems:

- business vocabulary
- accountability
- policy
- certification
- exception management
- review workflows
- cross-system relationships
- conflict resolution

The platform should store references and provenance for imported technical metadata. It should not require Data Stewards to manually recreate schemas, lineage or runtime evidence that machines can collect from the original systems.

## Conflict and precedence rules must be explicit

Distributed metadata will produce conflicts. The architecture must expose and resolve them rather than hide them.

A practical precedence model is:

```text
Approved authoritative declaration
> authoritative system-generated fact
> approved synchronized copy
> harvested copy
> inferred proposal
> detected proposal
> unverified manual entry
```

This order is not universal. Precedence should be defined per attribute type.

For example:

- the current physical data type comes from the database, not from the glossary
- the approved business definition comes from the accountable governance process, not from a scanner
- the transformation expression comes from versioned code, not from copied prose
- the current measure expression comes from the semantic model, not from a report screenshot
- the latest refresh comes from operational evidence, not from an expected schedule

When values conflict, retain both values and their provenance until the conflict is resolved.

A conflict record should contain:

- asset and attribute
- competing values
- authoritative sources
- versions and timestamps
- accountable decision owner
- impact assessment
- resolution status
- chosen value
- rationale
- effective date

Silent last-write-wins behaviour is unsuitable for governed metadata.

## Central-only documentation is an anti-pattern

Central-only documentation usually begins with good intent. Teams want one searchable place and therefore copy descriptions into a catalog.

The failure appears later.

The source field changes. Transformation logic evolves. The semantic measure is adjusted. The central description is not updated because the change occurred elsewhere. Users continue to see a polished but stale explanation.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/keep-metadata-close-to-the-source-img4-en.png"
        alt="Comparison of a central-only documentation anti-pattern and a target pattern that preserves source meaning, transformation logic, provenance and controlled review for sales_amount"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Manual central copies drift when source and transformation changes occur elsewhere. The target pattern keeps meaning and logic with their accountable origins and uses the central platform for provenance, links and review.
    </figcaption>
</figure>

Common warning signs include:

- the same description is editable in several systems
- no field identifies the authoritative source
- copied values have no collection timestamp
- source versions are not retained
- ownership describes a person but not a decision responsibility
- transformations are rewritten manually instead of referenced
- local BI calculations are hidden
- synchronization failures do not create incidents
- the catalog displays stale values without warning
- a connector can overwrite approved business context

The correction is not to eliminate the central platform. It is to redefine its role.

## Alternative operating patterns

### Source-local with shared conventions

Metadata remains primarily in source systems, repositories and BI models.

Appropriate when:

- the platform landscape is small
- teams use consistent version control and naming
- central discovery requirements are limited
- ownership is clear

Required safeguards:

- stable identifiers
- shared metadata schema
- documented authority
- repeatable exports
- periodic validation

### Central index with distributed authority

Metadata is harvested into a central search and relationship layer while authoritative maintenance remains distributed.

Appropriate when:

- several platforms must be searched together
- lineage and impact cross system boundaries
- governance teams need enterprise views
- technical teams retain platform ownership

This is the usual target pattern for a heterogeneous data stack.

### Governance-mastered attributes with technical distribution

Selected attributes are approved centrally and published to execution platforms.

Appropriate for:

- sensitivity
- retention
- certification
- permitted usage
- domain assignment
- policy references

Required safeguards:

- one approval authority
- controlled vocabulary
- technical mapping
- synchronization evidence
- rollback and exception handling

### Controlled contribution through pull requests or workflow tasks

Users can propose central changes, but the authoritative source is updated through review.

Appropriate when:

- source repositories are version controlled
- governance users should not edit code directly
- all changes require auditability
- automated validation is available

This pattern combines usable central workflows with source-owned truth.

## Decision guidance

For each metadata attribute, ask:

1. Where is the underlying knowledge created?
2. Which team can detect that the value is wrong?
3. Which system changes when the real-world meaning changes?
4. Is the value generated, observed, declared or inferred?
5. Does the value require business approval?
6. Must the value drive technical enforcement?
7. Does central search require a local copy?
8. How quickly can the value become stale?
9. Can the source provide a stable identifier and version?
10. What conflict rule applies?
11. Can a central change be written back safely?
12. Which evidence proves synchronization succeeded?

The answers determine whether to store, reference, copy or synchronize.

## Key recommendations

1. Assign one accountable origin to every important metadata attribute.
2. Separate authority from physical storage and user visibility.
3. Keep source meaning with source systems and accountable source teams.
4. Keep derived logic, tests and dependencies with version-controlled transformation code.
5. Keep measures, dimensions and analytical behaviour with the semantic layer.
6. Keep enterprise vocabulary, policy, accountability and cross-system decisions with governance processes.
7. Use central platforms for discovery, relationships, workflows, APIs and control views.
8. Copy metadata only with provenance, timestamps, versions and synchronization status.
9. Prefer references when the source is stable and accessible.
10. Restrict bi-directional synchronization to controlled proposal, validation and approval workflows.
11. Define precedence per metadata attribute rather than using a universal last-write-wins rule.
12. Preserve conflicting values until an accountable decision resolves them.
13. Distinguish source-owned, harvested, inferred, proposed and approved values.
14. Return tasks and approved updates to source systems; never use silent overwrites.
15. Begin with one critical data product and prove the ownership and synchronization model end to end.

## The next step: harvest metadata automatically

Keeping metadata close to its authoritative source solves the ownership problem. It does not solve the scaling problem.

Schemas, code dependencies, model descriptions, semantic objects, runtime evidence and usage change continuously. Collecting them manually would recreate the same stale central documentation problem in another form.

The next part, **Harvest Metadata Automatically**, explains how connectors, scanners, parsers, APIs, logs and events can collect source-owned metadata while preserving provenance, versions, confidence and approval boundaries.
