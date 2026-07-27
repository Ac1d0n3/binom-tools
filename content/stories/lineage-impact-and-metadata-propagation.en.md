---
title: Lineage, Impact and Metadata Propagation — Trace How Data and Context Change Through Every Transformation
description: A practical architecture for connecting system, dataset, column, process, KPI, report and AI lineage with transformation-aware metadata propagation, conflict resolution, impact analysis and auditable evidence.
category: Data Governance
tags:
  - metadata
  - data-lineage
  - column-lineage
  - impact-analysis
  - metadata-propagation
  - metadata-governance
  - data-catalog
  - data-products
  - semantic-layer
  - data-quality
  - data-classification
  - data-retention
  - incident-response
  - ai-governance
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 10
seriesTitle: MetaData Deep Dive
hero: images/playbooks/lineage-impact-and-metadata-propagation-hero.png
publishedAt: 2026-06-29 10:00
---

## Lineage becomes valuable only when it explains consequences

Many metadata platforms can draw arrows between assets.

A source table feeds a transformation. The transformation produces a governed dataset. A semantic model consumes that dataset. A dashboard and an AI feature depend on the semantic model.

The graph may look complete, but a visible path does not yet answer the questions that matter during a change, incident or governance decision:

- Which exact columns contributed to the target?
- Was the relationship observed at runtime, declared in code or added manually?
- Did a transformation preserve, rename, aggregate, mask or fundamentally reinterpret the value?
- Should sensitivity, ownership, retention, quality rules or descriptions be inherited?
- Which downstream objects are technically dependent?
- Which of those objects are business-critical?
- Where does the lineage contain gaps, uncertain mappings or unresolved conflicts?
- Which owner must approve a change before deployment?

A weak implementation treats every arrow as equivalent. It assumes that connectivity alone is enough to propagate context and assess impact.

That assumption is unsafe.

A direct projection from `customer.email` to `customer_email` preserves much more meaning than an aggregation such as `COUNT(DISTINCT customer_id)`. A masked value may remain sensitive even though the original characters are no longer visible. A hash may support matching while no longer being appropriate for communication. A join can combine several owners, retention obligations and quality expectations. A semantic KPI may introduce filters and exclusions that do not exist in the physical source.

> **Lineage explains how assets are connected. Metadata propagation uses those connections together with transformation semantics, authority and explicit rules to determine which context may be inherited.**

This distinction turns lineage from a diagram into an operational control system.

## Model lineage across the complete consumption path

Lineage should not stop at the warehouse table.

A decision-relevant lineage model connects the path from the operational system to the final consumer:

```text
Source System
→ Ingestion Pipeline
→ Raw Dataset
→ Transformation Model
→ Governed Data Product
→ Semantic Model
→ Report, API and AI Consumer
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/lineage-impact-and-metadata-propagation-img1-en.png"
        alt="End-to-end lineage from source system through ingestion, raw data, transformation, governed data product and semantic model to report, API and AI consumers, with forward data flow and reverse impact-analysis paths"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Useful lineage connects technical movement, transformation logic, governed products and consumer-facing semantics. Reverse traversal turns the same graph into impact analysis.
    </figcaption>
</figure>

Different questions require different drill levels.

### System lineage

System lineage connects platforms and major operating boundaries:

```text
CRM
→ Integration Platform
→ Cloud Warehouse
→ BI Platform
→ AI Service
```

It is useful for architecture, ownership and broad incident scope. It is not precise enough for field-level change planning.

### Dataset and table lineage

Dataset lineage identifies which stored or logical collections depend on one another:

```text
crm.customer
→ raw.crm_customer
→ core.dim_customer
→ product.customer_360
```

This level supports deprecation planning, pipeline troubleshooting and data-product ownership.

### Column lineage

Column lineage identifies the input columns, expressions and target columns involved in a derivation:

```text
crm.customer.email
→ LOWER(TRIM(email))
→ core.dim_customer.email_normalized
```

This level is necessary for classification propagation, quality-rule assessment, schema-change analysis and AI feature traceability.

### Process lineage

Process lineage represents the executable step that created or moved data:

```text
source extract
→ ingestion task
→ SQL model
→ semantic refresh
→ report publication
```

The process node should retain references to code, deployment, runtime evidence and responsible team. Without process lineage, the graph can show that two datasets are connected while hiding how the connection was implemented.

### KPI and semantic lineage

A KPI is not merely another column.

A metric can combine:

- several source measures;
- dimensions and relationships;
- filter context;
- time windows;
- currency conversion;
- exclusions;
- aggregation behaviour;
- restatement rules.

Lineage should therefore connect the KPI to both its physical inputs and its semantic definition.

### Report and API lineage

Reports, dashboards, extracts and APIs are operational consumers. They should be represented as first-class assets because they create real dependencies, service commitments and user impact.

A field that is technically unused in the warehouse may still be embedded in an export, API contract or workbook calculation.

### AI lineage

AI lineage should connect:

```text
source data
→ training or retrieval dataset
→ feature or embedding
→ experiment or index build
→ model or retrieval component
→ deployment
→ evaluation and output
```

For AI use, lineage must also preserve purpose, time window, feature derivation, training snapshot, approval state and permitted use. Knowing that a model consumed a dataset is not enough to reproduce or govern the result.

## Distinguish observed, declared and curated lineage

Lineage has several evidence types. They should be combined, but never silently collapsed.

### Observed query lineage

Observed lineage is inferred from executed operations such as:

- SQL query history;
- runtime events;
- pipeline runs;
- read and write operations;
- API calls;
- stream producer and consumer activity.

Its strength is operational evidence. It proves that a relationship occurred in a specific environment and time window.

Its limitations include:

- missing history after retention expires;
- incomplete parsing of dynamic SQL;
- hidden movement outside monitored systems;
- temporary queries that should not become permanent architecture;
- runtime paths that differ between successful and failed executions.

Observed lineage should retain:

```text
observation time
execution identifier
environment
query or operation reference
parser version
coverage scope
confidence
```

### Declared code lineage

Declared lineage is derived from implemented definitions such as:

- transformation code;
- model manifests;
- pipeline specifications;
- notebook dependencies;
- semantic-model expressions;
- infrastructure configuration;
- data contracts.

Its strength is design intent and expression-level context.

Its limitations include:

- code that was never deployed;
- branches that differ from production;
- generated logic that is difficult to parse;
- macros or stored procedures that hide dependencies;
- configuration that no longer matches runtime behaviour.

Declared lineage should retain a code revision, environment and deployment state.

### Manually curated lineage

Curated lineage is supplied by a person when automated evidence is missing or insufficient.

It can represent:

- an external file transfer;
- a manual spreadsheet process;
- a business mapping;
- a legacy interface;
- a semantic relationship that cannot be parsed;
- an approved exception;
- a temporary bridge during migration.

Curated lineage is necessary in many enterprises. It becomes dangerous when it is indistinguishable from observed evidence.

Every curated edge should include:

```text
author
reason
evidence
scope
review date
approval status
expiry or revalidation date
```

A manually asserted relationship without review should not receive the same confidence as a repeatedly observed production path.

## Treat lineage as typed, versioned evidence

A lineage edge should be more than:

```text
Asset A → Asset B
```

A useful edge can be represented as:

```yaml
source: core.customer.email
target: product.customer_contact.email_normalized
relationship: derives
process: model.customer_contact
transformationType: rename_and_normalize
expression: lower(trim(email))
evidenceType: declared_code
environment: production
validFrom: 2026-07-01T08:00:00Z
observedAt: 2026-07-25T02:14:31Z
confidence: 0.98
coverage: column
status: active
```

The exact schema can differ. The required concepts should not.

Important attributes include:

- source and target identity;
- relationship type;
- process or transformation;
- expression or rule reference;
- evidence type;
- environment;
- validity period;
- observation time;
- confidence;
- coverage level;
- review and approval state.

Versioning matters because impact analysis is time-dependent.

A report may have depended on `customer_status` last month but use `customer_lifecycle_state` today. An incident investigation must reconstruct the graph that existed when the failure occurred, not only the current graph.

## Propagation requires transformation semantics

Lineage shows that an output depends on an input. It does not determine which metadata attributes remain valid after the transformation.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/lineage-impact-and-metadata-propagation-img2-en.png"
        alt="Rule matrix comparing propagation outcomes for descriptions, sensitivity, PII category, owner, retention, quality rule, unit and allowed usage across projection, rename, cast, concatenation, join, union, aggregation, masking, hashing and custom calculation"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Lineage identifies candidate inheritance paths. Transformation semantics determine whether metadata is propagated, transformed, recalculated, blocked or sent for review.
    </figcaption>
</figure>

A propagation engine should classify at least the following transformation types.

### Direct projection

```sql
SELECT email
FROM customer
```

A direct projection usually preserves:

- description;
- sensitivity;
- PII category;
- unit;
- allowed usage;
- quality expectations tied to the value.

Ownership and retention may still change when the target belongs to another Data Product or legal processing context.

### Rename

```sql
SELECT email AS customer_email
FROM customer
```

A rename normally preserves value-level meaning. The target description should record the new name and may require contextual wording, but classification and value semantics usually propagate.

### Cast

```sql
SELECT CAST(customer_id AS VARCHAR)
```

A cast preserves identity only when the conversion is lossless and does not alter interpretation.

Examples requiring review include:

- timestamp to date;
- decimal to integer;
- local time to UTC without timezone evidence;
- numeric code to formatted text;
- free text parsed into a structured category.

The data type changes, and quality rules may need recalculation.

### Concatenation

```sql
SELECT first_name || ' ' || last_name AS full_name
```

The output inherits sensitivity from both inputs, but the description must be transformed. Quality rules on the individual fields do not automatically apply to the combined string.

Concatenation can also create new sensitivity. Combining postal code, birth date and gender may increase re-identification risk even when each attribute alone had a lower classification.

### Join

A join combines row populations and business contexts.

Propagation must consider:

- join keys;
- cardinality;
- unmatched rows;
- duplicate creation;
- target grain;
- source ownership;
- combined policy scope.

Descriptions should not be copied indiscriminately from one input table. Dataset-level ownership usually belongs to the target Data Product, while source provenance remains visible.

### Union

A union combines records with nominally compatible structures.

Before propagating metadata, validate:

- equivalent meaning;
- compatible units;
- compatible code lists;
- aligned classifications;
- common retention obligations;
- target population definition.

A field called `status` in two systems may contain incompatible lifecycle semantics. The same column position does not prove the same meaning.

### Aggregation

```sql
SELECT region, COUNT(DISTINCT customer_id) AS active_customers
```

Aggregation usually requires:

- a new description;
- recalculated unit and grain;
- new quality expectations;
- target ownership;
- review of sensitivity and disclosure risk.

Raw PII classification should not blindly propagate to a count. However, small groups or rare categories may still create confidentiality risk. The result may require a different classification rather than no classification.

### Masking

Masking changes visibility, not necessarily sensitivity.

```text
thomas@example.com
→ t*****@example.com
```

The masked value can remain personal data. It may still support identification, correlation or contact-domain inference.

Propagation should distinguish:

- reversible masking;
- partial masking;
- tokenization;
- irreversible redaction;
- dynamic presentation masking.

Allowed usage must be evaluated explicitly.

### Hashing

Hashing can reduce direct readability while preserving linkability.

A deterministic hash may still be personal data when it can be matched across records or recomputed from a limited input space.

The target description must explain:

- algorithm class;
- salt or key handling;
- determinism;
- collision expectations;
- intended matching use;
- prohibited reversal or lookup use.

Do not automatically downgrade sensitivity to public or anonymous.

### Custom calculation and derivation

A custom expression can create a completely new concept:

```sql
CASE
    WHEN last_order_date >= current_date - 90
     AND open_balance = 0
    THEN 'active'
    ELSE 'inactive'
END AS customer_status
```

The output description, owner, quality rules and allowed use must be defined for the derived concept. Source metadata is supporting provenance, not a complete target definition.

## Define propagation rules per metadata attribute

Transformation type is only one axis. Each metadata attribute needs its own policy.

### Descriptions

A description may propagate when the value and meaning are preserved.

It should be transformed or rewritten when:

- the name changes materially;
- the grain changes;
- several inputs are combined;
- a business rule is applied;
- a code is mapped;
- an aggregation is introduced;
- the target has a narrower approved purpose.

A copied description that ignores the transformation is worse than no description because it creates false confidence.

### Sensitivity and PII category

Sensitivity should generally propagate conservatively.

Use rules such as:

```text
direct value preservation
→ retain classification

combination of sensitive inputs
→ retain or increase classification

masking or hashing
→ reassess, do not automatically remove

aggregation
→ recalculate disclosure risk

approved irreversible anonymization
→ classification may change with evidence
```

The system should retain the original source classifications even when the target receives a different approved classification.

### Ownership

Technical or business ownership should not usually propagate as a single inherited value.

A useful model separates:

- source owner;
- transformation owner;
- Data Product owner;
- semantic owner;
- consumer owner;
- policy owner;
- steward.

The target asset should have its own accountable owner while preserving upstream ownership for escalation.

### Retention

Retention depends on legal purpose, operational need, contractual obligation and target processing context.

A target cannot safely inherit the shortest or longest source retention without policy.

Typical rules include:

- direct copies remain constrained by source obligations;
- derived products receive an approved retention policy;
- joins use the strictest applicable obligation until reviewed;
- aggregates may qualify for longer retention only when re-identification and purpose constraints are resolved;
- temporary processing assets receive explicit deletion schedules.

### Quality rules

Some quality rules propagate; others must be recalculated.

Examples:

- `email must match approved format` can propagate through a rename;
- `customer_id must be unique` may fail after a join that duplicates rows;
- `sales_amount must be non-negative` may remain valid through currency conversion but requires unit-aware thresholds;
- `status must use source code list` should not propagate after mapping to enterprise lifecycle states.

A target should record whether a rule is inherited, transformed, newly defined or intentionally not applicable.

### Units and formats

Units require explicit semantic handling.

```text
EUR
→ converted to USD
```

is not a rename.

The target needs the conversion rate source, effective time, rounding and unit. Percentages, ratios, durations and timestamps require similar treatment.

### Allowed usage

Permitted use is not merely a technical attribute.

A field approved for service operations may not be approved for marketing, model training or automated decision-making. Propagation should evaluate purpose and consumer context.

A copied allowed-use tag without purpose validation can create unauthorized downstream use.

## Resolve multiple inputs, overrides and conflicts

A derived field can receive metadata from several inputs.

Consider:

```text
email        — confidential PII
phone        — confidential PII
customer_id  — internal identifier
```

These values contribute to one derived `contact_key`.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/lineage-impact-and-metadata-propagation-img3-en.png"
        alt="Three classified input columns flow through a resolver using column lineage, transformation rules, approved target overrides and conflict policy to produce resolved, proposed or unresolved metadata"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Multiple inputs require explicit precedence. Approved target decisions and transformation rules outrank propagated suggestions, while unresolved conflicts remain visible for review.
    </figcaption>
</figure>

The resolver should not select one input arbitrarily.

A practical precedence order is:

```text
Approved Target Override
> Approved Transformation Rule
> Propagated Source Metadata
> Detection Proposal
```

### Approved target override

A target override is an explicit, reviewed decision for the target asset.

Examples:

- the derived key is classified as confidential because it remains linkable;
- retention is limited to 30 days for a temporary matching process;
- marketing use is prohibited;
- ownership belongs to the Customer Identity Data Product.

An override should record approver, reason, scope, effective date and review date.

### Approved transformation rule

A reusable transformation rule can resolve recurring cases.

Example:

```text
deterministic hash of direct identifier
→ classification remains confidential
→ allowed use limited to matching
→ description must include algorithm policy reference
```

Rules should be versioned. A change to the rule can trigger reassessment of every target that used it.

### Propagated source metadata

Source metadata provides candidate values.

When several inputs agree, the result may be resolved automatically. When they disagree, the system should apply an approved conflict policy or create a review task.

### Detection proposal

Profiling or AI-based classification may suggest metadata. It should remain a proposal until the organization’s approval model allows automatic acceptance for that attribute and confidence threshold.

Detection should not silently overwrite approved target metadata.

### Resolved, proposed and unresolved states

The result should be explicit:

- `Resolved metadata` — supported by rules and authority;
- `Proposed metadata` — plausible but awaiting approval;
- `Unresolved — review required` — conflict, missing evidence or unsupported transformation.

A catalog that hides unresolved states behind one clean value loses the evidence needed for governance.

## Represent confidence, gaps and contradictory evidence

Lineage completeness is rarely binary.

A graph can contain:

- fully observed production paths;
- declared but not observed paths;
- parsed table lineage without column mappings;
- manual relationships awaiting review;
- external transfers with no technical connector;
- dynamic logic the parser could not resolve;
- conflicting source and target classifications.

Represent these conditions directly.

### Confidence

Confidence should describe the quality of the specific claim, not the general reputation of the source platform.

Possible factors include:

- evidence type;
- parser support;
- runtime frequency;
- environment match;
- code revision match;
- freshness;
- manual approval;
- unresolved dynamic logic;
- agreement between several sources.

Avoid presenting a precise score such as `0.93` unless the scoring model is defined and explainable. Categories such as `verified`, `high`, `medium`, `low` and `unknown` can be more honest.

### Coverage

Coverage should state which level is complete:

```text
system
dataset
table
column
expression
process
consumer
```

A path can be complete at table level and incomplete at column level.

### Gaps

A gap should be a first-class object or status.

Record:

- where the path stops;
- why it stops;
- expected source of evidence;
- responsible owner;
- business impact;
- review date;
- accepted risk or remediation plan.

### Conflicts

Conflicting lineage or metadata should remain visible until resolved.

Examples:

- code declares one source, runtime observes another;
- two parsers produce different column mappings;
- a target override lowers sensitivity without anonymization evidence;
- a curated edge contradicts the deployed model;
- a report owner claims a field is unused while usage telemetry shows active consumption.

Conflict resolution requires evidence and accountability, not silent precedence by connector order.

## Use lineage for impact analysis

Impact analysis is reverse graph traversal enriched with business context.

Start with a proposed change:

```text
Rename or Remove customer_status
```

Then traverse downstream dependencies.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/lineage-impact-and-metadata-propagation-img4-en.png"
        alt="Impact-analysis workflow for renaming or removing customer_status, tracing transformation models, tests, data products, semantic measures, dashboards, exports, AI features and policies before notification, testing and approval"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technical dependency identifies what can break. Business criticality determines priority, approval, communication and deployment controls.
    </figcaption>
</figure>

A practical workflow is:

```text
Detect Change
→ Traverse Dependents
→ Classify Impact
→ Notify Owners
→ Test
→ Approve or Block
→ Record Evidence
```

### Detect change

Changes can come from:

- schema comparison;
- code pull request;
- contract update;
- semantic-model revision;
- policy change;
- source-system release;
- deprecation request.

The change object should describe what will change, where, when and why.

### Traverse dependents

Traverse the lineage graph across:

- transformation models;
- tests;
- Data Products;
- semantic measures;
- dashboards;
- exports;
- APIs;
- AI features;
- policies;
- documentation;
- quality rules.

Traversal should respect environment and version. Development-only dependents should not be mixed with production impact.

### Classify technical impact

Technical impact includes:

- compilation failure;
- missing field;
- changed data type;
- changed grain;
- broken join;
- failed quality test;
- API contract violation;
- model feature mismatch;
- policy-binding failure.

### Classify business criticality

Business criticality includes:

- regulatory reporting;
- executive KPI;
- customer-facing service;
- financial close;
- operational decision;
- automated action;
- model risk;
- contractual export;
- internal convenience.

A technically direct dependency can be low criticality. An indirect semantic dependency can be highly critical.

### Notify owners

Notifications should be routed to the owners of the affected assets, not to a generic distribution list.

Each notification should include:

- proposed change;
- affected object;
- relationship path;
- impact classification;
- required action;
- deadline;
- evidence link;
- approval or block status.

### Test and decide

Tests can include:

- compile and deployment validation;
- schema compatibility;
- data-quality regression;
- semantic measure comparison;
- report rendering;
- API contract tests;
- AI feature and evaluation checks;
- policy enforcement checks.

The decision should be:

```text
approve
approve with migration
block
defer
accept documented risk
```

The evidence and approver should be retained.

## Use lineage during incidents

Lineage is equally valuable after a failure.

Suppose a source-system release changes `customer_status` values from:

```text
A / I / P
```

to:

```text
ACTIVE / INACTIVE / PENDING
```

The pipeline still loads because the field type remains text. The technical job succeeds, but the semantic mapping no longer recognizes the values.

A lineage-enabled incident workflow can:

1. locate the first changed asset and time;
2. identify the transformation that expected the old codes;
3. find downstream Data Products and semantic measures;
4. identify dashboards, exports and AI features that consumed the affected data;
5. estimate the affected time window;
6. notify accountable owners;
7. pause or label affected outputs;
8. rerun corrected transformations;
9. record recovery evidence.

Without lineage, each team investigates its own system and manually reconstructs the path. That increases recovery time and makes scope uncertain.

Incident lineage should be time-aware. The current graph may differ from the graph that produced the faulty output.

## The simplest viable implementation

A useful first implementation does not require a perfect enterprise graph.

Start with one critical path and implement five capabilities.

### 1. Stable asset identities

Create stable identities for:

- systems;
- datasets and tables;
- columns;
- processes;
- Data Products;
- semantic objects;
- reports and APIs;
- AI datasets and features.

Preserve native identifiers and environment.

### 2. Typed lineage edges

Record:

```text
reads
writes
derives
renames
joins
unions
aggregates
masks
hashes
publishes
consumes
```

A generic `related_to` edge is not sufficient for propagation or impact analysis.

### 3. Evidence and confidence

Store evidence type, timestamp, code revision, execution reference, coverage and confidence.

### 4. A small propagation policy set

Implement approved rules for:

- direct projection;
- rename;
- join;
- aggregation;
- masking;
- hashing;
- custom calculation.

Cover the most important metadata attributes first:

- sensitivity;
- description;
- owner;
- retention;
- quality rule;
- allowed usage.

### 5. Reverse traversal workflow

Support one controlled change workflow:

```text
proposed source change
→ downstream traversal
→ owner notification
→ test evidence
→ approval
```

This delivers operational value before every system is connected.

## Alternative implementation patterns

### Source-native lineage with a central index

Source platforms retain their own detailed lineage. A central index stores normalized identities, high-value edges and references back to source evidence.

Suitable when:

- source lineage is strong;
- detailed graphs are expensive to copy;
- freshness matters;
- central discovery is required.

Risk:

- cross-system traversal depends on reliable identity resolution;
- source APIs and retention can create gaps.

### Central lineage graph

A dedicated platform stores normalized lineage across systems and supports traversal, propagation and workflow.

Suitable when:

- cross-platform impact analysis is critical;
- several source systems expose partial lineage;
- a shared control plane is required.

Risk:

- the central graph can become stale;
- source-specific semantics may be flattened;
- connector ownership becomes a permanent operational responsibility.

### Code-first lineage

Transformation manifests, SQL parsing and repository metadata form the primary lineage source.

Suitable when:

- transformations are mostly code-defined;
- deployment is controlled;
- runtime movement is limited and observable.

Risk:

- manual processes, runtime-generated SQL and BI calculations remain invisible.

### Runtime-first lineage

Query history, execution events and access logs form the primary evidence.

Suitable when:

- workloads are dynamic;
- deployed behaviour matters more than declared intent;
- runtime instrumentation is reliable.

Risk:

- unused but valid dependencies may disappear;
- retention limits can erase history;
- observed execution does not always reveal business semantics.

### Hybrid evidence graph

Declared, observed and curated lineage coexist as separate evidence types and are reconciled.

This is usually the strongest enterprise pattern. It is also operationally more demanding because conflicts, versions and confidence must be managed explicitly.

## A concrete example: customer contact key

Assume three source fields:

```text
crm.customer.email
crm.customer.phone
erp.debtor.customer_id
```

A matching model creates:

```text
customer_contact_key =
SHA-256(
    lower(trim(email))
    || '|'
    || normalize_phone(phone)
    || '|'
    || customer_id
)
```

The lineage graph records:

- three source columns;
- one transformation process;
- normalization expressions;
- concatenation;
- hashing;
- one target column;
- one downstream matching service;
- one AI feature that uses the key for entity resolution.

A naïve propagation engine might remove PII classification because the output is hashed.

A governed resolver evaluates:

```text
source classifications
+ concatenation rule
+ deterministic hash rule
+ target purpose
+ approved override
```

It concludes:

```text
classification: confidential
PII category: pseudonymous identifier
owner: Customer Identity Data Product
allowed usage: entity matching only
retention: 30 days in temporary matching zone
description: deterministic composite key for approved identity matching
quality rule: non-null only when all required inputs are valid
```

The result retains provenance to the original fields.

Later, the phone-normalization logic changes. Impact analysis identifies:

- the target key;
- duplicate-detection tests;
- the matching service;
- an AI feature store;
- a customer-merge dashboard;
- a policy that restricts cross-domain matching.

The change is technically small but business-critical because it can alter identity resolution. The graph supports a controlled decision rather than an unreviewed deployment.

## Common anti-patterns

### Arrows without evidence

A graph shows connections but does not reveal whether they were observed, declared or guessed.

Result: users cannot assess trust or investigate contradictions.

### Propagate everything downstream

Every classification, owner, description and rule is copied through every edge.

Result: metadata becomes internally inconsistent and semantically wrong.

### Remove sensitivity after masking or hashing

The system assumes unreadable means anonymous.

Result: linkable or reversible data is under-classified.

### Stop lineage at the warehouse

Reports, semantic measures, exports and AI features are excluded.

Result: impact analysis misses the assets closest to business decisions.

### One owner for the complete path

Source, pipeline, Data Product, semantic model and report are assigned to one inherited owner.

Result: accountability becomes inaccurate and escalation fails.

### Current-state-only graph

Historical edges are overwritten.

Result: incidents and past decisions cannot be reconstructed.

### Hide gaps and conflicts

The catalog displays one clean answer even when evidence disagrees.

Result: unresolved risk is converted into false certainty.

### Treat technical dependency as business impact

Every downstream object receives the same priority.

Result: teams are overloaded with noise while critical dependencies are not distinguished.

## Decision guidance

Use the following questions when choosing scope and architecture:

```text
Which decisions must lineage support?
Which asset levels are required?
Which evidence types are available?
Where is column-level semantics necessary?
Which metadata attributes may propagate?
Which transformations require review?
How will conflicts and gaps be represented?
How long must historical lineage remain available?
Which owners must act on impact findings?
Which tests can produce approval evidence?
```

Prioritize lineages linked to high-consequence decisions:

- regulated data;
- executive and financial KPIs;
- customer-facing APIs;
- critical operational processes;
- sensitive Data Products;
- AI training and inference features;
- contractual exports.

Do not begin by connecting every table in the enterprise. Begin with the paths where incorrect change has measurable cost or risk.

## Key recommendations

1. Model system, dataset, table, column, process, KPI, report, API and AI lineage as connected but distinct asset levels.
2. Preserve observed, declared and curated lineage as separate evidence types.
3. Store transformation semantics on the lineage edge or linked process.
4. Define propagation rules per transformation type and metadata attribute.
5. Propagate sensitivity conservatively and reassess masking, hashing and aggregation.
6. Give target assets their own ownership, retention and approved-use decisions.
7. Use explicit precedence for target overrides, transformation rules, source metadata and detection proposals.
8. Represent confidence, coverage, gaps and unresolved conflicts directly.
9. Separate technical dependency from business criticality in impact analysis.
10. Version lineage and retain the evidence used for approvals, incidents and change decisions.

## From connected metadata to enforceable governance

Lineage and propagation explain where data came from, how it changed and which downstream assets depend on it.

The next step is to use that context as control metadata.

Policies, classifications, approvals, retention obligations, access conditions and usage restrictions must not remain passive labels in a catalog. They need to influence platforms, workflows and decisions.

Part 11 therefore moves from descriptive metadata to **governance metadata that controls data**.
