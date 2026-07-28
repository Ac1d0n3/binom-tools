---
title: Prepare Metadata for AI, RAG and Model Training — Create Trusted, Permission-Aware Context for Retrieval and Learning
description: A practical architecture for preparing documents, datasets, chunks, features and models with meaning, provenance, quality, temporal validity, permissions, lineage and evidence so AI systems can retrieve, rank, cite and learn from approved context.
category: Data Governance
tags:
  - metadata
  - ai-ready-metadata
  - artificial-intelligence
  - rag
  - retrieval-augmented-generation
  - model-training
  - training-data
  - feature-metadata
  - data-lineage
  - data-quality
  - data-provenance
  - data-classification
  - permission-aware-retrieval
  - ai-governance
  - explainable-ai
  - semantic-search
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
seriesPart: 15
seriesTitle: MetaData Deep Dive
hero: images/playbooks/prepare-metadata-for-ai-rag-and-model-training-hero.png
publishedAt: 2026-07-04 10:00
---

## AI systems fail when context is available but not trustworthy

Organizations often begin an AI initiative by collecting text.

Policies, manuals, tickets, reports, contracts, data dictionaries, dashboards, datasets and source-code documentation are copied into a search index or object store. Documents are split into chunks, embeddings are generated and a model receives the passages that look semantically similar to a user question.

The first demonstration can be impressive.

The system finds relevant words and produces a fluent answer. It may even cite a document title.

The operational questions appear later:

- Was the retrieved document still valid when the question was asked?
- Was it an approved policy or an outdated working draft?
- Did the user have permission to see the source content?
- Was the answer based on the authoritative definition or a convenient copy?
- Which business domain and jurisdiction applied?
- Did the source describe the current process or a historical state?
- Was the dataset suitable for retrieval but prohibited for model training?
- Did a quality incident make the source temporarily unreliable?
- Can the system explain why one source was selected and another excluded?
- Can the exact context used for an answer or training run be reconstructed later?

A vector representation cannot answer these questions by itself.

Semantic similarity indicates that two pieces of content may be related. It does not prove that the content is approved, current, complete, permitted, authoritative or appropriate for the intended use.

The same problem exists in model training. A directory of files or a large table can be technically accessible while containing duplicated records, expired labels, prohibited data, leaked target information, unclear sampling, unknown provenance or content collected for a purpose that does not permit training.

> **AI-ready metadata is the control layer that determines which context may be retrieved, how candidate sources should be ranked, which evidence must accompany an answer and whether data may enter a training, validation or evaluation workflow.**

AI readiness therefore requires more than descriptions.

It requires structured meaning, relationships, quality evidence, temporal validity, sensitivity, permitted use, approval and provenance.

## The core principle: context selection is a governed decision

An AI system should not treat every indexed item as an equally valid source.

A useful decision model separates four questions:

```text
Can this source be considered?
Is it relevant to the question or training purpose?
Is it trustworthy for that purpose and point in time?
Can the result be explained and reconstructed?
```

These questions form a controlled context-selection pipeline:

```text
Identity and meaning
+ temporal validity
+ permission and allowed usage
+ quality and authority
+ lineage and evidence
→ approved candidate set
→ ranked context
→ cited use
→ recorded outcome
```

The order matters.

Permission, legal restrictions, approved usage and mandatory validity rules are hard gates. A prohibited source must not receive a lower ranking. It must remain outside the candidate set and outside the prompt or training package.

Relevance, freshness, authority and quality can then rank the remaining candidates.

This distinction prevents a common design error:

```text
Retrieve everything
→ ask the model to ignore restricted or weak content
```

By that point, the content has already entered the model context.

The safer pattern is:

```text
Evaluate metadata and entitlement
→ exclude prohibited sources
→ retrieve only eligible content
→ assemble traceable context
```

## Build an AI-ready metadata package around every asset

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/prepare-metadata-for-ai-rag-and-model-training-img1-en.png"
        alt="An AI-ready metadata package surrounds a data or document asset with Meaning, Structure, Trust, Time, Permission, Retrieval and Evidence metadata, showing that a plain text description is only one component"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        An AI-ready asset combines semantic, structural, governance, temporal, retrieval and evidence metadata. A description is valuable, but it cannot replace permission, provenance, version, quality or lineage.
    </figcaption>
</figure>

The package does not need to live in one physical table or product.

It is a logical contract assembled from authoritative systems.

### Meaning

Meaning metadata helps a human or machine understand what the asset represents.

Useful attributes include:

```yaml
title: recognized-revenue-policy
definition: Rules for recognizing subscription revenue after delivery obligations are satisfied
synonyms:
  - revenue recognition
  - recognized sales
domain: finance
business_terms:
  - recognized-revenue
  - performance-obligation
language: en
jurisdiction: global-with-local-addenda
```

A title and description are only the beginning.

Synonyms improve keyword and semantic retrieval. Domain limits the search space. Controlled business terms connect documents, datasets, metrics and policies that use different technical names. Language, geography, product line and jurisdiction prevent a globally similar document from being treated as locally applicable.

Examples and counterexamples are especially useful for ambiguous concepts:

```yaml
examples:
  - annual subscription recognized monthly after service delivery
counterexamples:
  - signed order without fulfilled performance obligation
limitations:
  - does not cover hardware revenue
```

### Structure

Structure metadata explains how the asset is organized and how its parts relate.

For structured data this includes:

- schema;
- field types;
- keys;
- grain;
- units;
- relationships;
- partitioning;
- expected ranges;
- null semantics.

For documents it includes:

- document hierarchy;
- section hierarchy;
- page or paragraph references;
- tables and figures;
- headings;
- language;
- attachments;
- parent and child documents.

For a chunk it includes:

```yaml
chunk_id: policy-2026-04-section-7-chunk-03
parent_document_id: policy-2026-04
section_path:
  - Revenue Recognition
  - Contract Modifications
sequence: 3
page_start: 18
page_end: 19
character_start: 9421
character_end: 12804
chunking_strategy: heading-aware-v2
```

Without hierarchy, a retrieved paragraph can lose the scope established by its heading, previous paragraph, table note or appendix.

### Trust

Trust metadata describes why an asset should or should not influence an AI decision.

Useful attributes include:

```yaml
owner: finance-policy-office
steward: revenue-accounting-team
source_system: controlled-policy-repository
provenance_status: verified
certification: approved
quality_tier: critical
quality_status: passed
quality_checked_at: 2026-07-25T06:00:00Z
known_issues: []
```

Trust is not a single score.

Ownership, approval, provenance and quality provide different evidence. A source can be authoritative but temporarily fail a freshness expectation. Another source can be current but merely proposed. A third can be technically correct while outside the requested business scope.

The system should retain these distinctions instead of flattening them into a decorative badge.

### Time

AI systems need to know when information applies.

Useful attributes include:

```yaml
valid_from: 2026-04-01
valid_to: null
published_at: 2026-03-15T09:00:00Z
observed_at: 2026-07-25T06:00:00Z
last_refreshed_at: 2026-07-25T06:00:00Z
freshness_objective: PT24H
version: 4.2
supersedes: policy-2025-11
status: effective
```

`Published at`, `observed at`, `valid from` and `last refreshed at` are not interchangeable.

A policy may be published before its effective date. A dataset may be refreshed today while describing transactions from last month. A historical document may be old but correct for a question about the past. The newest source is therefore not automatically the correct source.

### Permission

Permission metadata defines who may use an asset and for which purpose.

Useful attributes include:

```yaml
sensitivity: confidential
contains_personal_data: true
pii_categories:
  - customer-identifier
allowed_usage:
  - internal-answering
  - approved-analytics
rag_suitability: approved-with-entitlement
training_permission: prohibited
fine_tuning_permission: prohibited
external_model_usage: prohibited
required_entitlements:
  - finance-policy-reader
retention_class: regulated-seven-years
```

Access and allowed usage are separate decisions.

A user may be allowed to read a document but the organization may still prohibit using it for model training. A dataset may be approved for aggregate analytics but not for individual-level responses. A public document may allow retrieval but carry copyright or contractual restrictions on training or redistribution.

The metadata contract must express intended purpose rather than relying on one generic `accessible` flag.

### Retrieval

Retrieval metadata improves candidate discovery and ranking.

Useful attributes include:

```yaml
keywords:
  - revenue
  - recognition
  - subscription
search_boost_terms:
  - performance obligation
embedding_profile: multilingual-general-v3
chunk_hierarchy: section-aware
source_priority: 90
retrieval_domains:
  - finance
  - accounting
supported_question_types:
  - policy interpretation
  - effective-date lookup
excluded_question_types:
  - legal advice
```

Retrieval metadata can also store:

- preferred aliases;
- language variants;
- document type;
- asset type;
- geographic scope;
- semantic concepts;
- chunk relationships;
- source ranking rules;
- retrieval exclusions;
- expected answer type.

Embeddings are one retrieval signal. Keyword search, graph traversal, metadata filters and structured queries remain important because exact identifiers, dates, policy codes and field names are often poorly served by semantic similarity alone.

### Evidence

Evidence metadata makes use explainable.

Useful attributes include:

```yaml
lineage:
  - source: approved-policy-repository/revenue-recognition-v4.2
    transformation: pdf-to-structured-document-v2
    target: knowledge-index/policy-2026-04
citations:
  - type: source-document
    locator: pages-18-19
approval_evidence: approval-78421
quality_evidence: validation-run-2026-07-25-0600
limitations:
  - local tax treatment requires jurisdictional addendum
```

Evidence should support two forms of reconstruction:

```text
Why was this source eligible and selected?
Which exact version and passage influenced the result?
```

That requires stable identifiers and versioned references, not only a display title.

## Different AI assets require different metadata profiles

A single generic `asset` schema is useful for shared fields. It is insufficient for every AI use case.

Documents, chunks, datasets, features and models need type-specific metadata.

### Document metadata

A document profile should normally include:

- stable document identity;
- title and aliases;
- document type;
- authoring organization;
- owner and steward;
- language;
- domain and jurisdiction;
- status and approval;
- version and supersession;
- valid-from and valid-to dates;
- sensitivity and permitted use;
- source location;
- checksum;
- extraction status;
- known limitations.

### Chunk metadata

A chunk profile should include:

- stable chunk identity;
- parent document and parent section;
- exact locator;
- sequence;
- chunking strategy and version;
- inherited and overridden classifications;
- inherited validity;
- embedding profile;
- retrieval keywords;
- local summary;
- surrounding-context references;
- extraction confidence.

Chunk metadata should not silently replace document metadata.

The chunk inherits default context from the document but may need more restrictive rules. A table containing personal data inside an otherwise internal manual may require a higher sensitivity level than the parent document.

### Dataset metadata

A dataset profile should include:

- business purpose;
- population;
- grain;
- time window;
- collection method;
- source systems;
- inclusion and exclusion rules;
- sampling;
- labels and label provenance;
- quality results;
- representativeness limitations;
- sensitivity;
- permitted use;
- retention;
- version;
- lineage;
- approval state.

### Feature metadata

A feature profile should include:

- business meaning;
- derivation logic;
- source lineage;
- calculation time;
- availability time;
- null handling;
- expected distribution;
- stability;
- leakage risk;
- protected-attribute relationship;
- online and offline consistency;
- version;
- owner;
- approved model scopes.

### Model metadata

A model profile should include:

- model identity and version;
- intended purpose;
- training dataset versions;
- feature or input contract;
- evaluation datasets;
- metrics and thresholds;
- known limitations;
- deployment scope;
- prohibited uses;
- approval status;
- monitoring expectations;
- rollback reference;
- lineage to outputs and downstream consumers.

The shared metadata graph should connect these profiles without pretending they are identical.

## Separate retrieval metadata from training dataset metadata

RAG and model training use information differently.

RAG selects context at query time. The source content remains external to the model weights and can be filtered, cited, updated or revoked for future queries.

Training changes model parameters or a task-specific model artifact. The exact effect of an individual training item is harder to isolate or remove later.

The metadata requirements therefore overlap but are not interchangeable.

### Retrieval metadata asks

```text
May this user retrieve this source now?
Is the source relevant to this question?
Is it valid for the requested point in time?
How should it be ranked?
Which exact passage should be cited?
```

### Training metadata asks

```text
Was this content collected and approved for training?
What population and time window does the dataset represent?
Which transformations, labels and sampling rules were applied?
Can the training run be reproduced?
Which model version consumed this dataset version?
Which limitations and prohibited uses follow from the data?
```

A practical contract keeps separate attributes:

```yaml
rag:
  suitability: approved
  allowed_audiences:
    - finance-employees
  citation_required: true
  temporal_filter_required: true

training:
  permission: prohibited
  reason: contractual-use-restriction
  reviewed_by: legal-data-governance
  reviewed_at: 2026-05-10
```

Do not infer training permission from retrievability.

Do not infer retrievability from inclusion in an internal training dataset.

## Start with the simplest viable implementation

The simplest viable implementation is not an enterprise-wide knowledge graph with every possible AI asset.

It is one governed use case with explicit source boundaries.

A practical first implementation can follow eight steps.

### 1. Define one question domain

Choose a bounded use case such as:

```text
Answer internal questions about approved finance policies.
```

Define:

- intended users;
- permitted question types;
- authoritative source repositories;
- excluded repositories;
- expected freshness;
- citation requirement;
- escalation path when sources conflict.

### 2. Inventory authoritative sources

For each repository or dataset, record:

```yaml
source_id: finance-policy-repository
source_owner: finance-policy-office
authority_scope:
  - global-finance-policy
content_types:
  - approved-policy
  - approved-procedure
excluded_states:
  - draft
  - expired
  - withdrawn
interface: repository-api
freshness_objective: PT1H
permission_source: corporate-identity-groups
```

Source authority must have a scope.

A legal repository may be authoritative for contract templates but not for accounting policy. A BI catalog may be authoritative for metric definitions but not for operational procedure. A ticketing system may contain useful examples but not approved policy.

### 3. Define a minimal AI metadata contract

Begin with fields that change system behaviour:

```yaml
asset_id: required
asset_type: required
domain: required
status: required
version: required
valid_from: required
valid_to: optional
owner: required
source_authority: required
sensitivity: required
allowed_usage: required
rag_suitability: required
training_permission: required
required_entitlements: required
quality_status: required
source_locator: required
```

Descriptions, synonyms and examples improve retrieval. Permission, validity and source identity protect the control boundary. Both groups are necessary.

### 4. Preserve raw, normalized and approved states

Keep the original source metadata and extraction result.

Then normalize identifiers, dates, asset types and classifications.

Finally apply approved governance values.

```text
Raw source metadata
→ normalized AI metadata
→ approved retrieval or training profile
```

Do not overwrite the source evidence when a steward corrects or enriches a value.

### 5. Enforce permission before content retrieval

Resolve the user or service identity before fetching source content.

Apply:

- repository entitlement;
- row or document scope;
- sensitivity rules;
- allowed-usage rules;
- jurisdiction restrictions;
- current exception status.

Only eligible identifiers should be passed to the retrieval engine.

### 6. Combine retrieval methods

Use the retrieval methods that fit the question:

```text
metadata filtering
+ exact keyword or identifier search
+ semantic search
+ relationship traversal
+ structured data query
```

A policy code may require exact search. A broad conceptual question may benefit from semantic retrieval. A question about downstream impact may require lineage traversal. A numerical answer may require a governed query rather than document chunks.

### 7. Build citations from stable evidence

The context builder should retain:

- asset ID;
- version;
- chunk ID;
- document locator;
- source system;
- retrieval timestamp;
- ranking reasons;
- applied permission decision;
- quality state.

The answer should cite the source that actually contributed to the context, not merely the repository homepage.

### 8. Log the decision without copying unnecessary content

Record enough metadata to reconstruct the decision:

```yaml
question_id: q-2026-07-25-00418
user_scope_hash: 62f...
retrieval_policy_version: rag-policy-3.1
candidate_count: 42
eligible_count: 7
selected_chunks:
  - chunk_id: policy-2026-04-section-7-chunk-03
    rank: 1
    reasons:
      - semantic-match
      - approved-source
      - temporally-valid
      - quality-passed
excluded_reason_counts:
  permission: 12
  expired: 8
  draft: 6
  wrong-domain: 9
```

Avoid logging full sensitive prompts and content when identifiers and hashes are sufficient for operational evidence.

## Assemble trusted context for RAG before generation

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/prepare-metadata-for-ai-rag-and-model-training-img2-en.png"
        alt="A RAG workflow moves from User Question through Intent and Domain Detection, Metadata Filter, Permission Check, Source Ranking, Retrieve Chunks and Data, Build Context with Citations and Generate Answer, while rejected sources remain outside the prompt context"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Trusted RAG filters candidate sources by domain, asset type, validity, quality, sensitivity and entitlement before content enters the prompt. Rejected sources remain outside the model context.
    </figcaption>
</figure>

A robust RAG path separates query understanding, policy evaluation, retrieval and generation.

### User question

Capture the question, user identity, session purpose and requested point in time.

A question such as:

```text
Which revenue-recognition rule applied to contract modifications in November 2025?
```

contains an explicit historical date. A system that only prefers the newest document may select the wrong policy.

### Intent and domain detection

Identify:

- question type;
- business domain;
- relevant asset types;
- entities;
- dates;
- geographic or legal scope;
- requested level of detail.

Intent detection can be model-assisted, but the result should remain observable and correctable.

### Metadata filter

Apply deterministic filters where possible:

- domain;
- asset type;
- language;
- jurisdiction;
- approval status;
- temporal validity;
- minimum quality state;
- retrieval suitability.

### Permission check

Resolve current entitlement against the authoritative access system.

A copied permission label in a vector index can become stale. Critical permission decisions should use current identity and policy evidence or a controlled cache with explicit freshness and failure behaviour.

Fail closed when the entitlement result is unavailable for sensitive content.

### Source ranking

Rank only eligible sources.

Useful signals include:

- semantic relevance;
- approved definition;
- data quality;
- freshness;
- source authority;
- temporal fit;
- exact identifier match;
- domain match;
- citation quality;
- known limitations.

### Retrieve chunks and data

Retrieve the smallest context that remains understandable.

This may include:

- a document chunk plus its heading;
- a table plus the associated note;
- a policy section plus the version header;
- a metric definition plus grain and filters;
- a structured query result plus data timestamp;
- a lineage path plus affected assets.

### Build context with citations

Context assembly should preserve boundaries between sources.

Do not merge conflicting statements into one unlabeled paragraph.

A useful context envelope can look like:

```yaml
source_id: policy-2025-11
version: 3.8
status: superseded
valid_from: 2025-01-01
valid_to: 2026-03-31
locator: section-7.2-pages-16-17
authority: approved-global-finance-policy
content: ...
```

### Generate answer

The generation instruction should require the model to:

- answer from supplied evidence;
- cite material claims;
- disclose conflicts;
- state when no approved source is sufficient;
- distinguish current from historical rules;
- avoid inferring permission or policy beyond the evidence.

A refusal or escalation can be the correct result.

## Keep rejected sources outside the prompt

A rejected source should not be passed to the model with an instruction such as `do not use this source`.

That pattern creates several risks:

- sensitive content enters the inference boundary;
- the model may still use the content;
- prompt-injection instructions remain visible;
- logs or traces may capture prohibited content;
- downstream tools may receive it;
- the organization cannot honestly claim that the source was excluded.

The correct control point is before content retrieval or before context assembly.

Use explicit exclusion reasons:

```yaml
candidate_id: draft-policy-2026-08
eligible: false
reasons:
  - status-not-approved
  - valid-from-in-future
  - user-not-entitled
```

Exclusion evidence is useful even when content is never fetched.

## A concrete RAG example: answering a historical policy question

Assume four candidate sources:

```text
A. Approved policy v3.8 — valid during 2025
B. Approved policy v4.2 — current from April 2026
C. Draft interpretation note — created July 2026
D. Help-desk article — updated May 2026
```

The user asks:

```text
How were contract modifications handled in November 2025?
```

A pure semantic search may rank B, C and D above A because they use newer terminology and contain more explicit examples.

The metadata-aware process evaluates:

```text
requested time: November 2025
required domain: finance
required asset type: approved policy or approved procedure
user entitlement: finance-policy-reader
minimum quality: passed
citation required: true
```

The result is:

- A becomes primary context because it was approved and valid in November 2025;
- B may become supporting context only if the answer explains that it applies from April 2026;
- C is excluded because it is a draft;
- D may be excluded from policy interpretation or used only as a non-authoritative example.

The answer can then state:

```text
For November 2025, policy v3.8 applied.
The current policy differs from April 2026 onward.
```

The system should cite the exact section in v3.8 and disclose the later change when relevant.

This example demonstrates why freshness and authority cannot be reduced to one ordering field.

## Govern training datasets, features and models as connected profiles

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/prepare-metadata-for-ai-rag-and-model-training-img3-en.png"
        alt="Three connected profiles describe Training Dataset metadata, Feature metadata and Model metadata, linked through versioned lineage and approval"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Training datasets, features and models require distinct profiles connected through versioned lineage and approval. This makes purpose, derivation, evaluation and deployment scope reconstructable.
    </figcaption>
</figure>

Training governance fails when only the final model is registered.

A model card cannot reconstruct missing dataset lineage after the fact. A dataset catalog entry cannot explain feature leakage unless derivation and availability time are captured. An approved dataset cannot automatically approve every model purpose.

The three profiles must remain connected.

### Training dataset profile

A useful training dataset contract can include:

```yaml
dataset_id: churn-training-2026q2
version: 2.1
purpose: predict-voluntary-customer-churn
population: active-consumer-contracts
observation_window:
  start: 2024-01-01
  end: 2026-03-31
prediction_horizon: P90D
label:
  name: churn-within-90-days
  derivation_version: label-rule-4
sampling:
  method: stratified
  seed: 48117
  exclusions:
    - fraud-investigation
    - employee-accounts
permitted_use:
  - churn-model-development
prohibited_use:
  - credit-decision
approval_status: approved
```

The profile should also record quality, representativeness, known gaps, sensitivity, retention, licensing or contractual restrictions and lineage to raw sources.

### Feature profile

A useful feature contract can include:

```yaml
feature_id: support-contact-count-30d
version: 3
meaning: Number of completed customer support contacts in the prior 30 days
derivation: count(completed_contacts)
event_time_field: contact_closed_at
availability_delay: PT2H
null_handling: zero-after-source-completeness-check
source_lineage:
  - support.case
leakage_risk: reviewed
stability_status: monitored
approved_model_scopes:
  - churn-prediction
```

`Event time` and `availability time` are critical.

A feature can be historically present in a warehouse but unavailable at the time a real prediction would have been made. Using it in training creates leakage.

### Model profile

A useful model contract can include:

```yaml
model_id: customer-churn-classifier
version: 7.3
intended_purpose: prioritize-retention-outreach
training_dataset: churn-training-2026q2@2.1
feature_contract: churn-feature-set@5.0
evaluation_dataset: churn-holdout-2026q2@1.0
approval_status: approved
deployment_scope:
  - germany-consumer-subscriptions
prohibited_uses:
  - automated-contract-termination
  - creditworthiness-assessment
limitations:
  - performance-degrades-for-contracts-younger-than-60-days
monitoring_profile: churn-monitoring@3
```

The model profile should point to evaluation evidence instead of copying only headline metrics.

## Versioned lineage must connect data, features, models and decisions

A training lineage graph should answer:

```text
Which raw sources produced this dataset version?
Which transformation version created each feature?
Which labels and sampling rules were applied?
Which model run consumed the dataset?
Which evaluation result supported approval?
Where is the model deployed?
Which downstream decisions or products use its output?
```

Useful lineage edges include:

```text
source dataset version
→ transformed dataset version
→ training dataset version
→ feature set version
→ training run
→ model version
→ evaluation run
→ approval decision
→ deployment
→ monitored outputs
```

Lineage without versions is insufficient.

If `customer_status` changes after training, an edge to the current table does not prove which schema and values the model used.

Approval should also be version-scoped.

```text
Dataset v2.1 approved for churn prediction
```

is different from:

```text
All future versions of this dataset are approved for any model
```

The second statement is rarely defensible.

## Rank sources by trust, relevance and time

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/prepare-metadata-for-ai-rag-and-model-training-img4-en.png"
        alt="Candidate sources are evaluated by Semantic Relevance, Approved Definition, Data Quality, Freshness, Source Authority, User Permission and Temporal Fit, producing Primary context, Supporting context, Excluded and Conflict requiring disclosure"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Source selection uses hard permission and validity gates followed by multi-signal ranking. The newest source is not automatically the most authoritative, and unresolved conflicts must remain visible.
    </figcaption>
</figure>

A single relevance score hides important decisions.

Use hard gates first:

```text
approved usage
AND user permission
AND sensitivity policy
AND required validity
AND minimum quality state
```

Then rank eligible candidates.

A conceptual ranking model can be expressed as:

```text
rank =
  semantic relevance
+ exact concept match
+ approved-definition weight
+ source-authority weight
+ quality evidence
+ freshness fit
+ temporal fit
+ citation precision
- known limitation penalty
- unresolved conflict penalty
```

The formula should not become false precision.

Its purpose is to make signals explicit and testable.

### Primary context

Primary context is the strongest authoritative evidence for the question and point in time.

### Supporting context

Supporting context adds examples, implementation detail, local guidance or corroboration without replacing the primary source.

### Excluded

Excluded sources fail a hard gate or are irrelevant.

### Conflict requiring disclosure

A conflict exists when two eligible sources make materially different claims and metadata cannot resolve authority or scope.

Do not silently average or merge them.

The answer should identify the conflict, cite both sources and escalate when necessary.

## Use lineage and quality evidence to rank sources

Lineage can strengthen or weaken trust.

A dashboard description copied from a governed semantic model may be useful. A screenshot copied into a ticket may be less authoritative. A manually uploaded spreadsheet may contain the same values as a certified dataset but lack current refresh evidence.

Source lineage can distinguish:

```text
original approved source
controlled derivative
cached copy
manual extract
unverified duplicate
```

Quality evidence adds operational state:

- freshness passed or failed;
- required tests passed or failed;
- schema drift detected;
- reconciliation completed;
- incident open;
- certification expired;
- known limitation acknowledged.

A source should not retain a permanent high rank after its quality evidence becomes stale.

Useful attributes include:

```yaml
quality_evidence_id: dq-run-2026-07-25-0600
quality_state: passed
evidence_valid_until: 2026-07-26T06:00:00Z
open_incidents: []
certification_valid_until: 2026-09-30
```

## Support citations and explainability by design

Citations are not a formatting feature added after generation.

They depend on ingestion and metadata design.

A reliable citation needs:

- stable asset ID;
- version;
- exact source locator;
- source title;
- authoritative system;
- access-safe display link;
- retrieval timestamp;
- content checksum or equivalent integrity reference.

For structured data, the citation may need:

- dataset and version;
- query or metric definition;
- time range;
- freshness timestamp;
- applied filters;
- aggregation grain.

An answer such as:

```text
Revenue was €4.2 million.
```

is not explained by linking to a dashboard homepage.

A useful evidence record describes:

```yaml
metric: recognized-revenue
semantic_model_version: finance-metrics@12.4
time_range: 2026-06-01/2026-06-30
filters:
  legal_entity: DE01
query_executed_at: 2026-07-25T08:14:22Z
data_freshness: 2026-07-25T06:00:00Z
result: 4200000
currency: EUR
```

Explainability should cover selection, not only generation.

The system should be able to state:

```text
This source was selected because it was approved,
valid for the requested date,
within the user's entitlement,
and passed its latest quality checks.
```

## Preserve temporal validity and version throughout the pipeline

Time metadata is often lost during indexing.

A document repository contains a version history. The ingestion process extracts only the latest text. The vector index stores one copy. The old version is deleted. Historical questions can no longer be answered correctly.

A safer design keeps versioned identities:

```text
logical asset: revenue-recognition-policy
versioned asset: revenue-recognition-policy@3.8
versioned asset: revenue-recognition-policy@4.2
```

Each version carries:

- publication time;
- effective interval;
- supersession relationship;
- approval state;
- checksum;
- source locator;
- extraction version;
- index status.

The retrieval request should include a temporal intent:

```yaml
temporal_intent:
  type: valid-at
  timestamp: 2025-11-15
```

When no date is specified, the policy can prefer currently effective sources. The assumption should still be visible in the retrieval trace.

## Prevent sensitive or unapproved content from entering prompts

Security for AI context requires more than index-level access control.

Consider the full path:

```text
source
→ ingestion
→ parsing
→ chunking
→ embedding
→ index
→ retrieval
→ prompt assembly
→ model
→ logs and traces
→ answer cache
```

Sensitive content can leak at any stage.

Controls should include:

### Source admission

Only approved repositories and datasets may enter the pipeline.

### Classification propagation

Document and dataset classifications must propagate to chunks, embeddings, derived summaries and caches unless an approved rule changes them.

### Least-privilege ingestion

The ingestion identity should access only required sources and fields.

### Entitlement-aware retrieval

User and service entitlements must be applied before content is returned.

### Purpose control

`Allowed usage` and `training permission` must be evaluated separately from read access.

### Prompt-boundary control

The context builder must receive only eligible content.

### Safe logging

Logs should avoid storing raw sensitive prompts, context and responses unless explicitly required and protected.

### Revocation and deletion

The system needs a process to remove or deactivate content from indexes, caches, evaluation sets and future training runs when permission changes.

### Prompt-injection handling

Retrieved content should be treated as untrusted data, not as system instructions. Provenance and source type can influence how content is isolated and interpreted, but metadata does not replace runtime prompt-injection defenses.

## Alternative architecture patterns

No single AI metadata architecture fits every organization.

### Source-native retrieval

The AI service queries one governed source directly.

Suitable when:

- the use case is narrow;
- one platform is authoritative;
- native permissions are strong;
- cross-system ranking is not required.

Advantages:

- low duplication;
- current permissions;
- simple ownership;
- detailed native metadata.

Warning:

- difficult to combine multiple domains;
- limited cross-source lineage;
- platform-specific search and citation behaviour.

### Central metadata index with distributed content

A central service stores searchable metadata and source references, while content remains in source systems until retrieval.

Suitable when:

- enterprise discovery is needed;
- content should not be copied broadly;
- sources expose reliable APIs;
- permission can be checked at runtime.

Advantages:

- smaller sensitive-data footprint;
- centralized ranking context;
- distributed source authority.

Warning:

- runtime latency and source availability matter;
- identity mapping must be reliable;
- citations need stable source locators.

### Central knowledge index

Approved content and metadata are copied into a controlled retrieval index.

Suitable when:

- low-latency retrieval is required;
- source APIs are weak;
- version history must be preserved;
- a bounded corpus can be governed.

Advantages:

- predictable performance;
- controlled chunking and indexing;
- reproducible versions;
- consistent retrieval interface.

Warning:

- permissions and deletions can become stale;
- copied content creates a second controlled store;
- synchronization and evidence are mandatory.

### Federated retrieval

Multiple domain retrieval services return authorized evidence to an orchestrator.

Suitable when:

- domains have strong local ownership;
- content cannot be centralized;
- different asset types require specialized retrieval;
- enterprise questions span domains.

Advantages:

- local authority and controls;
- specialized retrieval;
- reduced central copying.

Warning:

- ranking across services is difficult;
- latency and partial failure must be handled;
- common identity, citation and evidence contracts are required.

### Curated knowledge products

A domain publishes a governed package specifically for AI consumption.

The package can contain:

```text
approved documents
+ governed datasets
+ semantic definitions
+ retrieval metadata
+ permissions
+ quality evidence
+ citation contract
```

Suitable when:

- high-value domains need predictable answers;
- source complexity should be hidden from consumers;
- the domain can own a service level.

Warning:

- curation can become manual and stale;
- the package must remain linked to source changes and lineage.

### Training registry connected to feature and model governance

Training datasets, feature sets, model runs and approvals are versioned in connected registries or metadata services.

Suitable when:

- models are trained repeatedly;
- multiple teams reuse features or datasets;
- regulatory or audit reconstruction is required;
- deployment depends on approved evidence.

Warning:

- registering names without immutable versions and lineage creates inventory, not reproducibility.

## Common anti-patterns

### Index everything first

A broad crawl creates coverage but also imports drafts, duplicates, expired documents, sensitive data and unclear authority.

Define admission and exclusion rules before indexing.

### Treat embeddings as metadata

Embeddings encode statistical similarity. They do not replace owner, approval, validity, permission, quality, source authority or permitted use.

### Store permissions only at document level

A document can contain sections, tables or attachments with different restrictions. Derived chunks and summaries need inherited or overridden classifications.

### Copy source access lists once

Permissions change. A static copy without freshness, reconciliation and failure behaviour becomes unsafe.

### Use one `approved` flag

Approval for publication, retrieval, analytics, training, external sharing and production deployment are different decisions.

### Prefer the newest source

New may mean draft, local guidance or an unapproved interpretation. Evaluate effective time and authority.

### Use one generic quality score

A high score can hide a failed mandatory rule. Preserve quality dimensions and hard failures.

### Lose the parent document during chunking

A chunk without heading, version, document status and locator is difficult to interpret and cite.

### Allow the model to resolve source conflicts silently

Conflicts are governance information. Preserve and disclose them.

### Register only the final model

Without dataset, feature, code, evaluation and approval lineage, the model cannot be reproduced or governed effectively.

### Infer training permission from internal access

Internal access does not prove consent, contractual permission, copyright suitability or approved purpose for training.

### Log every prompt and context by default

Detailed logs can create a second sensitive corpus. Log the minimum evidence required for operation, testing and audit.

## Decision guidance

Use the following questions before choosing an implementation pattern or product.

### Scope

- Which questions, decisions or model purposes are in scope?
- Which domains and jurisdictions apply?
- Which asset types must be included?
- Which content is explicitly excluded?

### Authority

- Which system is authoritative for each metadata attribute?
- How are duplicates and derivatives identified?
- What makes one source primary and another supporting?
- Who resolves conflicts?

### Time

- Must historical questions be answered?
- How are effective intervals represented?
- How quickly must source changes reach the AI system?
- What happens when freshness evidence is missing?

### Permission

- Which identity is used at retrieval time?
- Are permissions evaluated at document, row, field or chunk level?
- How are allowed usage and training permission represented?
- Does the system fail closed for sensitive content?

### Trust

- Which approval and quality states are mandatory?
- Can certification expire?
- How do incidents affect ranking or eligibility?
- Is source lineage available and versioned?

### Evidence

- Can every answer cite the exact source and version?
- Can a training run be reconstructed?
- Are ranking and exclusion reasons recorded?
- Can sensitive evidence be retained without copying full content?

### Operations

- Who owns ingestion, metadata quality and policy rules?
- How are deletions and revocations propagated?
- How are extraction, chunking and embedding changes versioned?
- How are false retrievals, stale context and permission failures measured?

## Key recommendations

1. Treat AI context selection as a governed decision, not a similarity search.
2. Build a logical metadata package covering meaning, structure, trust, time, permission, retrieval and evidence.
3. Keep document, chunk, dataset, feature and model profiles type-specific.
4. Preserve stable identifiers, versions and provenance from source to answer or model.
5. Separate RAG suitability, allowed usage, training permission and external-model usage.
6. Apply permission and mandatory validity rules before retrieving content.
7. Keep rejected sources outside the prompt context.
8. Combine metadata filters, exact search, semantic search, graph traversal and governed queries.
9. Rank only eligible sources using relevance, authority, quality, freshness and temporal fit.
10. Do not assume that the newest source is the most authoritative source.
11. Preserve conflicts and disclose them instead of silently merging them.
12. Retain parent document, section, locator and version on every chunk.
13. Propagate sensitivity and usage restrictions to chunks, embeddings, summaries and caches.
14. Use lineage and current quality evidence to strengthen or reduce source trust.
15. Build citations from stable source evidence, not from display titles alone.
16. Version chunking, extraction, embedding and ranking policies.
17. Connect training datasets, features, model runs, evaluations, approvals and deployments through versioned lineage.
18. Record event time and availability time to detect feature leakage.
19. Scope approvals to a concrete asset version and intended purpose.
20. Start with one bounded domain and one measurable question set.
21. Test permission failures, stale metadata, conflicting sources, historical questions and revocation before production use.
22. Log enough metadata to reconstruct decisions without creating an unnecessary sensitive-content copy.
23. Verify product-specific metadata filters, permission propagation, citation payloads, APIs, limits and licensing in a Proof of Value.
24. Expand the corpus only after admission, exclusion, ranking and evidence controls are reliable.

> **AI becomes more trustworthy when the system can prove not only what it answered, but why this context was eligible, authoritative and valid for this user, purpose and point in time.**

## Next: use AI to improve metadata

Preparing metadata for AI creates a structured control layer:

```text
meaning
+ relationships
+ provenance
+ quality
+ validity
+ permission
+ evidence
```

The same foundation can also be used in the opposite direction.

AI can propose descriptions, identify synonyms, detect classifications, map business terms, suggest owners, summarize lineage, find conflicts and prioritize missing metadata.

Those proposals are useful only when their state and evidence remain explicit.

Part 16 therefore examines **how AI can improve metadata** without turning generated suggestions into an unreviewed second truth.

It will separate:

- detected from declared metadata;
- inferred from approved values;
- proposal confidence from authority;
- automated enrichment from human accountability;
- useful assistance from uncontrolled metadata generation.
