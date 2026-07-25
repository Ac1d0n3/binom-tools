---
title: Centralized, Federated or Distributed Metadata — Choose an Architecture for Discovery, Ownership and Control
description: A practical decision framework for combining centralized discovery, federated ownership, distributed source metadata and selective central control without creating an unmaintained second truth.
category: Data Governance
tags:
  - metadata
  - metadata-architecture
  - metadata-governance
  - data-catalog
  - federated-governance
  - distributed-metadata
  - metadata-mesh
  - metadata-index
  - metadata-ownership
  - active-metadata
  - data-products
  - data-lineage
  - metadata-provenance
  - enterprise-governance
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 8
seriesTitle: MetaData Deep Dive
hero: images/playbooks/centralized-federated-or-distributed-metadata-hero.png
---

## Metadata architecture is not a binary platform decision

Organizations often frame metadata architecture as a choice between two extremes:

```text
One central catalog
or
Every domain manages its own metadata
```

Neither extreme describes how metadata is actually created or maintained.

Technical metadata originates in databases, pipelines, repositories, semantic models, BI platforms, security systems and runtime logs. Business definitions are supplied by domain experts. Enterprise policies require cross-domain authority. Quality expectations may belong to a Data Product team, while execution evidence remains in an observability platform. Access events can be too sensitive and too voluminous to copy into a general-purpose catalog. Cross-system lineage, however, cannot be understood from one source alone.

A central platform can provide a consistent entry point, but it does not automatically become the correct authoring location for every value. A domain can own its definitions, but local ownership alone does not provide enterprise search, shared vocabulary or cross-domain impact analysis. A source system can remain authoritative, but querying every source on demand can create latency, availability and integration problems.

The useful question is therefore not:

```text
Should metadata be centralized or decentralized?
```

It is:

```text
Which metadata capability should be centralized,
which responsibility should remain federated,
and which evidence should stay distributed at its source?
```

> **Most organizations need centralized discovery, federated ownership and selective central control. The physical location of metadata should follow authority, freshness, sensitivity, availability and operational use—not organizational fashion.**

This principle separates five decisions that are frequently collapsed into one:

```text
Authoring
Storage
Discovery
Approval
Enforcement
```

The same metadata attribute can use different locations for each decision.

A business definition may be authored by the Sales domain, stored in a domain repository, indexed centrally, approved through an enterprise workflow and enforced through a semantic-model validation rule. A technical schema may be authored by the source system, cached centrally for search and queried directly for the most current detail. An access event may remain in the security platform and only expose a central summary or reference.

Architecture becomes manageable when these responsibilities are designed explicitly.

## Three architecture patterns describe different operating assumptions

Centralized, federated and distributed metadata are not maturity levels. They are patterns with different strengths, risks and operating requirements.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/centralized-federated-or-distributed-metadata-img1-en.png"
        alt="Three side-by-side metadata architecture patterns comparing centralized, federated and distributed ownership, consistency, speed, integration effort, resilience and stale-copy risk"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        No pattern is universally correct. The appropriate design depends on who can maintain metadata, how quickly it changes, how broadly it is reused and which controls must operate across domains.
    </figcaption>
</figure>

## Centralized metadata repository

A centralized repository stores and governs most metadata in one platform.

Typical characteristics are:

- one primary metadata database;
- centrally managed schemas and workflows;
- common authoring interfaces;
- one governance team defining most rules;
- scheduled or event-driven ingestion from source systems;
- a single enterprise search and API layer;
- central persistence of relationships and history.

This pattern can be effective when:

- the number of systems and domains is limited;
- one team genuinely understands most assets;
- metadata changes at a manageable rate;
- central standards are more important than domain autonomy;
- source APIs are weak or unavailable;
- consistent reporting and audit are primary goals.

Its advantages are clear:

- one technical operating model;
- one place to secure, back up and monitor;
- consistent workflow states;
- simpler enterprise reporting;
- easier global search;
- lower initial coordination effort.

The main risk is not central technology. The main risk is central maintenance without central knowledge.

A repository becomes a second truth when it stores copied descriptions, ownership and classifications that no accountable team updates. The platform may remain available and searchable while the content silently diverges from source systems and domain decisions.

Centralization also creates concentration risk:

- connector failure can hide source changes;
- central ingestion latency can make metadata stale;
- a platform outage can remove discovery for the entire organization;
- the central team can become a review bottleneck;
- local teams can stop feeling accountable because “the catalog team owns it”.

A centralized repository is therefore appropriate only when central ownership is real, not merely assigned on an architecture slide.

## Central metadata index

A central index is narrower than a central repository.

It stores enough information to locate, identify and connect metadata, but it does not attempt to persist every source attribute.

A practical index may contain:

```text
canonical asset identifier
source system and environment
qualified source reference
display name
asset type
domain
owner or accountable role
lifecycle status
selected classifications
relationship pointers
searchable description
freshness status
last successful synchronization
```

Detailed technical attributes, runtime metrics, audit events or large profiling results can remain in their original systems.

The index supports:

- enterprise search;
- cross-system identity resolution;
- high-level lineage;
- domain and ownership navigation;
- shared terminology;
- links back to source-native detail;
- API-based discovery.

This pattern reduces duplication and central storage volume. It also preserves source authority more naturally.

Its weakness is dependency on source availability. Search results can remain visible while the detailed source view is unavailable. Querying several systems for one page or API response can create unpredictable latency. Source APIs may have incompatible authentication, pagination, rate limits and semantics.

A central index therefore needs explicit degradation behaviour:

```text
Current source detail available
Cached detail available
Source temporarily unavailable
Metadata freshness unknown
Reference no longer resolves
```

A reference without availability and freshness status is not a reliable architecture.

## Federated metadata governance

Federation separates enterprise coordination from domain accountability.

Domains own the metadata they are qualified to maintain:

- definitions;
- local terminology;
- Data Product context;
- quality expectations;
- approved mappings;
- local Steward assignments;
- known limitations;
- intended and prohibited use.

Enterprise governance defines minimum standards:

- required fields;
- canonical relationship types;
- shared classification taxonomy;
- approval requirements;
- interoperability rules;
- policy framework;
- escalation paths;
- evidence and audit requirements.

A central platform provides discovery and cross-domain services, but domains remain accountable for correctness.

Federation is suitable when:

- several domains possess distinct business knowledge;
- central teams cannot maintain detailed context;
- Data Products have clear accountable owners;
- local speed matters;
- cross-domain consistency is still required;
- enterprise policies must apply across decentralized delivery teams.

Federation does not mean that every domain invents its own model.

Without shared contracts, federation becomes fragmentation. A workable federated design needs a small mandatory core and controlled extension points.

For example:

```yaml
required_metadata:
  - domain
  - accountable_role
  - lifecycle_status
  - source_reference
  - classification_status
  - definition_status
  - freshness_expectation

domain_extensions:
  sales:
    - revenue_recognition_scope
    - sales_channel
  finance:
    - legal_entity
    - accounting_standard
  customer_service:
    - interaction_type
    - case_sensitivity
```

The enterprise core supports comparison and control. Domain extensions preserve legitimate local meaning.

## Distributed domain metadata

In a distributed pattern, metadata remains primarily in source systems, code repositories, domain platforms and operational tools.

The central layer may contain only:

- service endpoints;
- source references;
- identity mappings;
- minimal search records;
- selected policy states;
- cached relationship summaries.

Detailed metadata is queried on demand.

This pattern is useful when:

- metadata changes too quickly for periodic copying;
- source systems expose reliable APIs;
- sensitivity prevents broad replication;
- domains already operate mature metadata services;
- central storage would create unacceptable duplication;
- operational controls need source-native state.

Distributed architecture can improve freshness because the source is queried directly. It can also improve resilience when domains continue operating independently during a central outage.

However, the integration burden moves to runtime:

- every source requires authentication and authorization;
- APIs use different models and identifiers;
- availability varies;
- latency accumulates;
- cross-source queries are harder;
- historical values can disappear if sources do not retain them;
- enterprise reporting becomes more complex.

Distribution removes some copying cost but adds orchestration, observability and contract-management cost.

## Metadata mesh is an operating model, not another catalog label

A metadata mesh applies product and platform principles to metadata.

Domains publish metadata as governed, interoperable products. A shared platform provides common capabilities such as:

- identity;
- search;
- vocabulary;
- lineage exchange;
- policy contracts;
- access control;
- eventing;
- APIs;
- quality and freshness indicators.

A metadata product should have:

```text
owner
scope
schema
API or event contract
service level
freshness expectation
quality checks
versioning
deprecation policy
support path
```

The mesh idea becomes useful when domains already operate independently and can provide dependable interfaces.

It becomes an excuse for fragmentation when domains are told to “own metadata” without platform support, common contracts, funding or measurable obligations.

A metadata mesh is therefore not the default starting point for a small organization. It is an operating model for environments with real domain autonomy and sufficient engineering maturity.

## Separate authoring, storage, discovery, approval and enforcement

One metadata value may pass through several systems without changing its authority.

Consider a Business Term definition:

```text
Authored:
Sales domain repository

Stored authoritatively:
Sales metadata service

Indexed:
Central metadata platform

Approved:
Domain Owner plus enterprise review for shared use

Enforced:
Semantic-model validation and publication checks
```

Now consider a physical column type:

```text
Authored:
Database DDL

Stored authoritatively:
Database catalog

Cached:
Central metadata index

Approved:
No manual approval for observed structure

Enforced:
Database engine and deployment validation
```

And a personal-data classification:

```text
Detected:
Classification scanner

Proposed:
Central governance workflow

Approved:
Domain Steward or Privacy role

Stored authoritatively:
Governance platform

Distributed:
Warehouse tags, masking rules and catalog index

Enforced:
Access platform and data engine
```

These examples show why one system cannot automatically become the system of record for every metadata dimension.

A practical architecture should document each important attribute with a responsibility matrix.

| Metadata attribute | Authoritative author | Authoritative store | Discovery location | Approval authority | Enforcement location |
| --- | --- | --- | --- | --- | --- |
| Technical schema | Source platform or code | Source catalog or repository | Central index | Automated validation | Source platform |
| Business definition | Domain expert | Domain or governance repository | Central search | Domain Owner or Steward | Documentation and semantic checks |
| Enterprise term mapping | Domain Steward | Governance platform | Central search | Enterprise vocabulary authority | Modeling and publication checks |
| Classification | Detector plus Steward | Governance platform | Central search and source tags | Approved governance role | Data platform and access controls |
| Quality expectation | Data Product team | Quality contract or repository | Central search | Product Owner or Data Owner | Pipeline and observability |
| Runtime result | Execution system | Operational store | Central summary | Not normally approved | Alerting and publication control |
| Access event | Security platform | Audit store | Restricted summary or reference | Security policy | Identity and access platform |

This table should not be universal. Its purpose is to make authority explicit.

## Decide what should be stored centrally

Central storage should be a deliberate choice per metadata category, not the default behaviour of every connector.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/centralized-federated-or-distributed-metadata-img2-en.png"
        alt="Decision matrix for storing, caching, referencing or querying identifiers, definitions, schemas, metrics, access events, policies, classifications, usage statistics and profiling samples"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Storage mode should follow change frequency, sensitivity, source availability, cross-system use, historical requirements and enforcement latency.
    </figcaption>
</figure>

Four modes cover most needs.

### Store

Persist the metadata centrally as a governed record.

Use this when:

- the value is enterprise-owned;
- cross-system history is required;
- central workflows modify or approve it;
- source systems cannot retain the required history;
- several controls depend on a consistent state;
- central availability is mandatory.

Common candidates include:

- canonical identifiers;
- identity mappings;
- enterprise vocabulary;
- approved cross-domain mappings;
- policy definitions;
- approval records;
- relationship history;
- exceptions;
- central lifecycle state.

Central storage should still preserve provenance and source references.

### Cache

Store a temporary or synchronized copy whose source remains authoritative.

Use this when:

- fast search is important;
- the source can be unavailable;
- the data changes frequently but not continuously;
- eventual consistency is acceptable;
- the platform can show freshness and synchronization status.

Common candidates include:

- technical schemas;
- source descriptions;
- selected runtime aggregates;
- usage statistics;
- source ownership references;
- recent lineage edges;
- quality summaries.

A cache must expose:

```text
source
collected_at
source_observed_at
expected_refresh_interval
last_successful_sync
completeness
staleness status
```

A cached value without these fields can be mistaken for an authoritative current value.

### Reference

Store a stable pointer and enough metadata to identify the target.

Use this when:

- detailed content belongs in a specialist system;
- copying would create sensitivity or licensing concerns;
- a source UI or API provides the best interpretation;
- central consumers need navigation more than local persistence;
- payload size is large.

Common candidates include:

- detailed access investigations;
- full policy documents;
- code repositories;
- dashboard definitions;
- large profiling reports;
- incident records;
- model evaluation artifacts.

A reference should include a stable source identifier, not only a fragile URL.

### Query on demand

Retrieve metadata directly when requested.

Use this when:

- freshness must be near-real-time;
- the source API is reliable;
- the query scope is narrow;
- central persistence is undesirable;
- authorization must be evaluated by the source;
- the value has little cross-system reuse.

Common candidates include:

- current runtime status;
- recent access details;
- live source availability;
- current job state;
- high-volume event details;
- sensitive audit evidence.

On-demand querying requires timeouts, retries, circuit breaking, caching rules and clear fallback behaviour.

## Apply the decision criteria consistently

Each metadata category should be evaluated against the same questions.

### Change frequency

How quickly can the value change?

A stable Business Term can be centrally stored. A job status that changes every few seconds should normally remain operational and be queried or summarized.

### Sensitivity

Who may see the metadata?

Metadata can itself be sensitive. Access logs, security groups, sample values, model prompts and profiling results may reveal personal, confidential or security-relevant information.

Central discovery does not justify unrestricted central copying.

### Source availability

Can the source be relied upon during discovery and control execution?

An unreliable source may require a cache. A reliable source with a contractual API can support references or on-demand queries.

### Cross-system use

How many systems need the value?

Canonical identities, enterprise terms and cross-domain relationships gain value from central persistence. Highly local runtime detail may not.

### Required history

Must past states remain explainable?

If a source only exposes current state, central storage may be required for audit and impact analysis.

### Enforcement latency

How quickly must a control react?

A policy used for real-time access decisions cannot depend on a slow nightly catalog sync. Enforcement should occur in the platform capable of meeting the latency requirement, even when the approved policy is managed centrally.

## Central discovery with federated ownership is the common target

The most practical enterprise pattern is neither a fully central repository nor unrestricted distribution.

It is:

```text
Domain-owned authoring and accountability
+
Enterprise minimum standards
+
Central identity, discovery and relationships
+
Selective central approval and control
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/centralized-federated-or-distributed-metadata-img3-en.png"
        alt="Sales, Finance, Customer Service and Operations domains own definitions, Stewards, quality expectations and mappings while a central platform provides search, vocabulary, lineage, policy framework, APIs and escalation"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Enterprise governance defines the minimum interoperable contract. Domains remain accountable for the metadata that requires their business knowledge.
    </figcaption>
</figure>

The central platform should not absorb every responsibility.

It should provide capabilities that are inefficient to duplicate:

- canonical identity;
- enterprise search;
- cross-domain relationships;
- shared vocabulary;
- policy framework;
- common workflow states;
- central escalation;
- API and event contracts;
- audit of approvals;
- platform observability.

Domains should retain responsibilities that require local knowledge:

- business definitions;
- domain terminology;
- valid local distinctions;
- Data Product scope;
- quality expectations;
- local Stewardship;
- known limitations;
- approved local mappings;
- intended use;
- domain-specific deprecation decisions.

Enterprise governance should define minimum evidence and consistency requirements without rewriting domain content.

A useful rule is:

> Centralize the capability when it must work across domains. Federate the decision when correctness depends on domain knowledge.

## Concrete example: one customer concept across four domains

Assume four domains use related customer information.

### Sales

Sales maintains:

```text
Account
Sales territory
Opportunity relationship
Commercial contact
Revenue attribution
```

Its definition of `Customer` may include prospects or active accounts depending on the process.

### Finance

Finance maintains:

```text
Debtor
Legal entity
Billing account
Credit status
Receivable relationship
```

A debtor is not automatically identical to the Sales account.

### Customer Service

Customer Service maintains:

```text
Contact
Service account
Case participant
Communication preference
Escalation status
```

One service account may serve several contacts.

### Operations

Operations maintains:

```text
Delivery recipient
Installation location
Service location
Operational status
Fulfilment relationship
```

A delivery location may not be the legal debtor or commercial account.

A weak centralized approach creates one universal `Customer` definition and forces every domain to use it. The resulting term becomes vague enough to be accepted but too vague to support correct decisions.

A weak distributed approach lets every domain publish unrelated metadata with no mappings. Search returns several terms, but users cannot determine their relationships.

A federated design keeps the local concepts and adds approved relationships:

```text
Sales Account
maps to enterprise concept Party in Commercial Role

Finance Debtor
maps to enterprise concept Party in Financial Obligation

Service Contact
maps to enterprise concept Person or Organization in Service Interaction

Delivery Recipient
maps to enterprise concept Party in Fulfilment Role
```

The central platform stores or indexes:

- canonical identities;
- cross-domain mappings;
- synonyms;
- relationship types;
- approval status;
- provenance;
- effective dates;
- known non-equivalences.

The domains retain their definitions and examples.

This supports cross-domain questions without erasing meaning:

- Which Sales Accounts have Finance Debtors?
- Which Service Contacts belong to an account?
- Which delivery locations are linked to a legal entity?
- Which reports incorrectly treat account, debtor and recipient as the same grain?
- Which Data Products use the enterprise concept but implement different local roles?

The architecture coordinates semantics. It does not pretend that every local term is identical.

## Resilience, latency, duplication and integration cost are trade-offs

Every pattern pays for consistency somewhere.

### Resilience

A centralized repository simplifies backup and monitoring but creates a central dependency.

A distributed architecture allows domain autonomy but depends on many services for enterprise queries.

A federated architecture should define degraded operation:

- central search unavailable;
- one domain endpoint unavailable;
- cached metadata stale;
- approval service unavailable;
- policy distribution delayed;
- source reference unresolved.

Controls that protect data should fail according to explicit policy, not according to accidental connector behaviour.

### Latency

Central copies introduce synchronization delay.

On-demand queries introduce request latency and source dependency.

Hybrid designs can separate:

```text
Fast central discovery
+
Source-native current detail
+
Event-driven updates for critical changes
```

Not all metadata needs the same freshness.

A Business Term may tolerate daily synchronization. A revoked access rule may require immediate propagation. A runtime status should remain live. A schema change may need event-driven detection before publication.

### Duplication

Duplication is not automatically bad.

A searchable cache can be valuable when it is explicitly marked as a copy with freshness and provenance. Duplication becomes dangerous when copied content can be edited independently or shown without authority status.

A safe copy answers:

```text
Where did this value come from?
When was it observed?
Is it authoritative?
Can it be edited here?
When will it be refreshed?
What happens if the source changes?
```

### Integration cost

Centralization pays integration cost during ingestion and normalization.

Distribution pays integration cost during every federated query and user interaction.

Federation adds organizational contract cost:

- domain roles;
- standards;
- review obligations;
- service levels;
- escalation;
- change management.

The cheapest architecture on a diagram may be the most expensive to operate.

## The simplest viable implementation depends on organizational size

Architecture should begin with the smallest model that satisfies real requirements.

### Small team: source-native metadata plus one searchable index

A small team with a few systems rarely needs a full metadata mesh.

A practical minimum is:

1. Keep technical definitions in source DDL, transformation repositories and semantic models.
2. Maintain business definitions and ownership in one controlled location.
3. Harvest a minimal searchable index.
4. Store canonical identifiers and source references.
5. Add only the relationships needed for current discovery and impact questions.
6. Track synchronization freshness.
7. Use direct links to source-native detail.
8. Assign one accountable person per metadata category.

This pattern avoids premature platform complexity.

### Growing organization: federated catalog with domain ownership

As domains and Data Products increase, add:

- mandatory enterprise metadata fields;
- domain-level Steward queues;
- controlled vocabulary mappings;
- shared classification taxonomy;
- central approval for cross-domain terms and policies;
- relationship and lineage history;
- published APIs;
- connector ownership and service levels.

The central team should operate the platform and standards, not write every domain definition.

### Large organization: metadata platform plus domain products

A large organization may need:

- distributed domain metadata services;
- central identity resolution;
- event-driven metadata exchange;
- shared policy distribution;
- cross-domain graph and search;
- restricted specialist stores for security and AI metadata;
- versioned contracts;
- delegated administration;
- platform reliability objectives;
- measurable domain obligations.

The design should still avoid one enterprise platform becoming the authoring tool for every source-specific detail.

## Choose the simplest viable architecture

A decision path should start with systems, domains and operating capability—not product category.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/centralized-federated-or-distributed-metadata-img4-en.png"
        alt="Decision path from number of systems, maintainers, lineage needs, central enforcement, freshness and source API reliability to source-native, central index, federated catalog or enterprise active metadata platform"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Select the least complex architecture that can meet discovery, ownership, lineage, freshness and enforcement requirements. Each outcome has a suitable context and a specific warning.
    </figcaption>
</figure>

### Source-native only

Suitable context:

- few systems;
- one team;
- low cross-system discovery need;
- metadata is maintained directly in code and source platforms.

Warning:

- relationships and shared definitions can become invisible across tools.

Use this outcome deliberately and document where users find metadata.

### Lightweight central index

Suitable context:

- several systems;
- shared search is required;
- source systems remain authoritative;
- cross-domain control is still limited.

Warning:

- references and cached values require freshness, availability and ownership status.

This is often the best first central capability.

### Federated catalog

Suitable context:

- several business domains;
- domain teams can maintain context;
- shared vocabulary and lineage are required;
- enterprise standards must coexist with local accountability.

Warning:

- federation without contracts, Steward capacity and escalation becomes fragmented documentation.

This is the common target for mature Data Product organizations.

### Enterprise active metadata platform

Suitable context:

- many systems and domains;
- policy, quality, lineage and lifecycle controls depend on metadata;
- near-real-time propagation is required;
- APIs and events must integrate many platforms.

Warning:

- automation amplifies incorrect metadata if evidence, approval and exception handling are weak.

An active platform should be the result of proven governance, not a substitute for it.

## Common anti-patterns

### One central catalog as the only place metadata may exist

This removes metadata from the code, systems and teams that can maintain it.

### Copy everything because storage is cheap

Storage cost is not the primary problem. Authority, freshness, sensitivity and lifecycle are.

### Let every domain define everything independently

Local autonomy without shared identifiers, contracts and vocabulary prevents enterprise discovery and control.

### Central governance writes domain definitions

A central team can define standards and facilitate resolution. It usually cannot replace domain knowledge.

### Central search without source health

A result that links to an unavailable or deleted source should not look healthy.

### Editable copies on both sides

Bidirectional authoring without field-level authority and conflict rules creates synchronization loops.

### Real-time architecture for static metadata

Not every definition requires streaming. Unnecessary real-time integration increases operating cost without improving decisions.

### Nightly synchronization for access-critical policy

A control that must react immediately cannot depend on a slow central refresh.

### Metadata mesh without metadata products

Assigning domain ownership without APIs, contracts, service levels, funding and observability is organizational delegation without capability.

### Central platform without accountable content owners

Platform ownership does not equal metadata ownership.

### Distributed queries without timeout and fallback design

A federated screen that waits indefinitely for several source systems is not resilient.

### Hiding stale copies behind one preferred value

The preferred view must still expose source, authority, freshness and conflicts.

## Decision guidance

Use the following questions before selecting an architecture.

| Question | Architectural implication |
| --- | --- |
| How many systems and domains are in scope? | A small scope can remain source-native; broad scope usually needs a central index and shared identity. |
| Who can maintain the metadata correctly? | Authoring and accountability should stay with the team that has the required knowledge. |
| Is cross-domain discovery required? | Introduce central search, canonical identifiers and relationship indexing. |
| Is cross-domain lineage required? | Persist or exchange normalized relationship edges and versions. |
| Are enterprise policies approved centrally? | Centralize policy authority, but enforce in platforms that meet the required latency. |
| How fresh must each category be? | Choose stored, cached, event-driven or on-demand access per category. |
| Are source APIs reliable and supported? | Reliable APIs enable references and distributed queries; weak APIs justify caching or ingestion. |
| Is historical reconstruction required? | Persist versions centrally when sources expose only current state. |
| Is metadata itself sensitive? | Restrict central copies and expose summaries or references. |
| Can domains meet service obligations? | Federation requires named roles, capacity, quality expectations and escalation. |
| What happens during a source or platform outage? | Define degradation, fallback and fail-safe behaviour. |
| Which values trigger automated controls? | Require explicit authority, approval state, provenance and exception handling. |

The decision should be made per metadata capability and category. One organization can legitimately use all four outcomes at the same time.

For example:

```text
Source-native only:
Detailed transformation configuration

Lightweight central index:
Technical assets and source references

Federated catalog:
Definitions, ownership and Data Product context

Enterprise active metadata:
Approved classifications and access-control signals
```

Hybrid architecture is not a compromise. It is usually the accurate representation of different authority and latency requirements.

## Key recommendations

1. Do not treat metadata architecture as a binary choice between one catalog and complete decentralization.
2. Separate authoring, storage, discovery, approval and enforcement for every important metadata category.
3. Centralize enterprise discovery, canonical identity and cross-domain relationships where they create shared value.
4. Keep authoring and accountability close to the domains and systems that can maintain correctness.
5. Store enterprise-owned records and history centrally; cache source-owned detail only with freshness and provenance.
6. Use stable references for specialist or sensitive metadata that should not be broadly copied.
7. Query on demand only when source APIs, latency, authorization and fallback behaviour are dependable.
8. Define enterprise minimum standards without erasing legitimate domain extensions.
9. Make every copy explicit about authority, source, collection time, expected refresh and editability.
10. Design for source outages, stale caches, broken references and central-platform failure.
11. Match enforcement location to the required latency and failure policy.
12. Treat federation as an operating contract with roles, service levels, review duties and escalation.
13. Use metadata mesh patterns only when domains can publish reliable metadata products.
14. Begin with the simplest viable architecture and add distribution or active control only for demonstrated needs.
15. Prevent the central platform from becoming an unmaintained second truth by measuring ownership, freshness and unresolved conflicts.

## The next step: use native metadata across the stack

This part defined where metadata capabilities and responsibilities should operate.

The next question is how each platform in the data stack should participate.

Databases, transformation repositories, orchestration platforms, semantic models, BI tools, identity systems, observability services and AI platforms each expose different native metadata. Some should remain authoritative. Some should publish events. Some should receive approved classifications, ownership or policy decisions.

The next part, **Native Metadata Across the Data Stack**, examines how to use these native capabilities without rebuilding the same metadata manually in every product and without losing the centralized discovery and federated accountability established here.
