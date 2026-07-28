---
title: Use AI to Improve Metadata — Generate Suggestions at Scale Without Turning Probability into Truth
description: A practical operating model for using deterministic rules, statistical detection and generative AI to propose descriptions, classifications, domains, owners and relationships while preserving evidence, confidence, review, approval and metadata quality.
category: Data Governance
tags:
  - metadata
  - artificial-intelligence
  - ai-assisted-metadata
  - metadata-enrichment
  - data-classification
  - metadata-quality
  - data-governance
  - human-in-the-loop
  - provenance
  - confidence
  - data-lineage
  - business-glossary
  - pii-classification
  - active-metadata
  - ai-governance
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
seriesPart: 16
seriesTitle: MetaData Deep Dive
hero: images/playbooks/use-ai-to-improve-metadata-hero.png
publishedAt: 2026-07-05 10:00
---

## Metadata work does not scale when every decision begins with a blank field

Metadata programs frequently encounter the same operational problem.

The organization has thousands of tables, columns, reports, metrics, documents, models and data products. Technical metadata may already be harvested, but descriptions are incomplete, classifications are inconsistent, business terms are missing, domains are unclear and ownership is outdated.

The usual response is to ask stewards and engineers to document everything manually.

That approach fails for predictable reasons:

- the inventory grows faster than the documentation capacity;
- experts spend time rewriting information that can already be inferred from schema, code or lineage;
- similar assets receive different descriptions and classifications;
- review queues become too large;
- low-risk improvements wait behind high-risk governance decisions;
- users lose trust when generated metadata appears without evidence;
- automated classifications become operational controls before they have been validated.

AI can reduce this burden.

It can draft descriptions, identify likely business terms, detect personal-data patterns, propose domains, suggest owners, discover similar assets and recommend quality rules.

But an AI output is not approved metadata.

It is a proposal produced by a method with known inputs, assumptions and failure modes.

> **AI should accelerate metadata work by producing traceable suggestions. It must not convert probability into authority or bypass the controls required for consequential decisions.**

The objective is therefore not maximum automation.

The objective is to reduce manual effort while preserving or improving metadata quality, accountability and control.

## The core principle: generation and approval are different system states

AI-assisted metadata requires an explicit separation between what a system inferred and what the organization accepts as true.

A useful lifecycle distinguishes at least five states:

```text
Observed
→ Generated
→ Validated
→ Proposed
→ Approved
```

These states answer different questions.

`Observed` records source facts such as a column name, data type, lineage edge, query pattern or sample profile.

`Generated` records what a rule, detector or model produced.

`Validated` means that the result passed structural, vocabulary, policy and consistency checks.

`Proposed` means that the suggestion is ready for a defined review process.

`Approved` means that an accountable person or authorized low-risk rule accepted the value for a specific scope and version.

The approved value may match the generated value exactly, but the states must remain separate.

That separation prevents a common implementation error:

```text
Model response
→ overwrite catalog field
```

A safer pattern is:

```text
Model response
→ suggestion record
→ validation
→ risk-based review
→ approved metadata
```

The original suggestion remains available even after approval, correction, rejection or supersession. This preserves provenance and makes later evaluation possible.

## Use AI as an enrichment service, not as an authority

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/use-ai-to-improve-metadata-img1-en.png"
        alt="Schema, sample profiles, lineage, code, approved metadata and usage context flow into an AI enrichment service that creates suggestions which pass through validation and human review before becoming approved metadata"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        AI enrichment creates proposals from multiple evidence sources. Validation and human review remain separate gates, and the service cannot write directly into approved metadata.
    </figcaption>
</figure>

A metadata enrichment service should combine several context sources.

### Schema

Schema provides names, types, constraints, keys, nullability and structural relationships.

Examples:

```text
customer_email VARCHAR(320)
order_total DECIMAL(18,2)
contract_valid_from DATE
is_employee BOOLEAN
```

Schema is valuable but ambiguous.

`status` can describe a customer, order, contract, payment, pipeline run or legal case. A data type does not reveal business meaning by itself.

### Sample profiles

Profiles can expose patterns without requiring broad raw-value access.

Useful signals include:

- null ratio;
- distinct count;
- value length;
- pattern frequency;
- minimum and maximum;
- distribution;
- common values;
- uniqueness;
- entropy;
- date range;
- identifier structure.

Profiles can support classification. They do not establish purpose, legal basis or ownership.

Sensitive examples should be minimized, masked or represented as derived patterns whenever possible. Sending raw production values to a generative model is rarely necessary for basic metadata enrichment.

### Lineage

Lineage provides context from upstream sources and downstream consumers.

A field named `cust_id` becomes easier to understand when it originates from `crm.customer.customer_id`, feeds a governed customer dimension and appears in retention and support dashboards.

Lineage can support:

- propagated descriptions;
- inherited or proposed classifications;
- domain inference;
- relationship suggestions;
- owner candidates;
- impact-aware prioritization.

Propagation must still respect transformation logic and target overrides. A derived aggregate does not necessarily inherit every source classification unchanged.

### Code

SQL, transformation definitions, semantic models, tests and application code often contain the most precise available implementation evidence.

Code can reveal:

- aliases;
- calculations;
- filters;
- joins;
- units;
- time windows;
- null handling;
- comments;
- tests;
- source references;
- business-rule names.

Generated metadata should summarize relevant meaning rather than copy implementation code without interpretation.

### Existing approved metadata

Approved definitions, glossary terms, classifications, ownership mappings and policy values are high-value grounding context.

They can help the system use established vocabulary and avoid inventing near-duplicate terms.

Approved metadata should normally receive more weight than unreviewed descriptions found on similar assets.

### Usage context

Usage shows how assets are consumed.

Useful signals include:

- dashboards and reports using the asset;
- frequent queries;
- certified data products;
- user groups;
- common joins;
- support tickets;
- failed searches;
- applied filters;
- export patterns;
- last meaningful use.

Usage can indicate importance and likely purpose. It cannot prove that current usage is correct or permitted.

## Generate different suggestion types with different evidence

A single prompt should not be expected to solve every metadata task equally well.

Each task needs its own input contract, evaluation set and approval policy.

### Description suggestions

Descriptions can be proposed from:

```text
name
+ type
+ table purpose
+ code
+ lineage
+ glossary links
+ usage
```

A useful description should explain meaning, grain, unit, time reference, null behavior, limitations and suitable use where relevant.

The model should not merely expand a technical name:

```text
customer_status = Status of the customer
```

A stronger proposal is:

```text
Lifecycle state of the customer account at the end of the daily CRM load.
Values are active, suspended, closed and pending-review.
Do not use this field as the current support eligibility decision.
```

### Business-term and synonym suggestions

A model can propose links to existing terms or suggest synonyms.

The preferred order is:

```text
match an approved term
→ propose a synonym for review
→ propose a new term only when no suitable term exists
```

Similarity is not equivalence.

`Revenue`, `bookings`, `billings` and `cash received` may appear related while representing materially different concepts.

### PII and sensitivity suggestions

Classification can combine:

```text
deterministic patterns
+ column names
+ type
+ sample profile
+ lineage
+ source classifications
+ domain context
```

A detected email pattern can support a `personal-contact-data` proposal. It cannot determine legal usage, retention or access policy by itself.

High-impact classification requires stronger evidence and review because false positives and false negatives have different operational consequences.

### Domain suggestions

Domain inference can use:

- source system;
- schema and path;
- upstream and downstream assets;
- business terms;
- query communities;
- data-product membership;
- existing ownership;
- report usage.

Cross-domain assets may need multiple relationships instead of one forced domain.

### Owner candidates

AI can rank likely owners based on:

- source ownership;
- code maintainers;
- recent contributors;
- data-product accountability;
- dashboard owners;
- domain stewardship;
- operational support history.

An owner candidate is not an owner assignment.

Activity is evidence of involvement, not necessarily accountability.

### Similar-asset and relationship suggestions

Embeddings, schema similarity, lineage and usage can identify:

- duplicate datasets;
- equivalent fields;
- related metrics;
- source-to-product relationships;
- glossary candidates;
- replacement assets;
- deprecated copies.

The system should explain why two assets appear similar:

```text
same source lineage
+ 91% field overlap
+ matching grain
+ shared dashboard consumers
```

A similarity score without evidence is difficult to review.

### Quality-rule suggestions

Profiles, contracts, tests and downstream expectations can support proposed rules such as:

- not-null;
- uniqueness;
- accepted values;
- format;
- referential integrity;
- freshness;
- range;
- distribution drift;
- reconciliation.

A generated rule must include the expected behavior, scope, severity and evidence. It should not block production merely because a model suggested it.

## Separate deterministic rules, statistical detection and generative AI

The enrichment architecture should distinguish three method families.

### Deterministic rules

Deterministic rules are appropriate when the logic is explicit and repeatable.

Examples:

```text
trim whitespace
normalize case
map ISO currency code
recognize an exact approved identifier pattern
apply an approved synonym mapping
inherit a technical tag through a lossless rename
```

Their strength is predictability.

Their limitation is that they only cover conditions that have been encoded.

### Statistical detection

Statistical methods identify patterns from values, distributions or relationships.

Examples:

- email-pattern detection;
- identifier likelihood;
- anomaly detection;
- semantic similarity;
- clustering;
- duplicate detection;
- distribution comparison;
- owner-candidate ranking.

Their output remains probabilistic even when the algorithm is not generative.

### Generative AI

Generative models are useful for synthesis and interpretation.

Examples:

- drafting a concise description from multiple evidence sources;
- explaining why a term may match;
- summarizing transformation logic;
- generating reviewer-facing rationales;
- proposing examples and counterexamples;
- identifying ambiguities that require clarification.

Generative AI should not replace deterministic checks that already exist.

A robust pipeline can combine all three:

```text
Deterministic extraction and normalization
→ statistical detection
→ generative synthesis
→ deterministic validation
→ risk-based review
```

The method used for each suggestion must be stored. Otherwise the organization cannot evaluate performance or reproduce the result.

## Start with the simplest viable implementation

The simplest viable implementation is one bounded metadata task with measurable review effort and quality.

A practical first use case is description generation for one governed data product.

### 1. Define the target scope

For example:

```text
Generate description proposals for undocumented columns
in the certified customer analytics data product.
```

Exclude:

- calculated regulatory fields;
- unresolved sensitive data;
- temporary staging objects;
- assets scheduled for deletion;
- fields without stable identity.

### 2. Define the context package

A minimal context package can include:

```yaml
asset_id: customer_analytics.customer_daily.customer_status
asset_type: column
name: customer_status
data_type: string
parent_asset:
  name: customer_daily
  approved_description: Daily customer account snapshot
lineage:
  upstream:
    - crm.customer.status_code
  downstream:
    - customer_retention_dashboard
code_summary: Mapped from CRM status code through approved lookup table
approved_terms:
  - customer-account
  - lifecycle-status
profile:
  values:
    - active
    - suspended
    - closed
    - pending-review
  null_ratio: 0.0
usage:
  common_filters:
    - active
    - suspended
```

Raw customer records are not required.

### 3. Define the expected output

Use structured output rather than free text:

```yaml
suggested_value: >
  Lifecycle state of the customer account at the end of the daily CRM load.
  Values are active, suspended, closed and pending-review.
limitations:
  - not the current support eligibility decision
evidence_refs:
  - approved-term:lifecycle-status
  - lineage:crm.customer.status_code
  - code:mapping-status-code-v4
uncertainties:
  - exact business effective time not documented
```

### 4. Validate before review

Validation can check:

- required fields;
- maximum length;
- prohibited content;
- unsupported claims;
- approved vocabulary;
- evidence references;
- target identity;
- duplicate text;
- language;
- formatting;
- policy constraints.

A grammatically fluent result may still fail because it claims more than the evidence supports.

### 5. Review in a focused queue

Group similar suggestions.

Show reviewers:

- current value;
- proposed value;
- differences;
- supporting evidence;
- confidence;
- validation results;
- downstream impact;
- similar prior decisions.

Allow approve, edit-and-approve, reject and defer.

### 6. Measure the baseline and result

Before deployment, measure:

- average manual authoring time;
- review time;
- current completion rate;
- correction rate;
- quality score;
- reviewer agreement.

After deployment, compare the same metrics.

AI is useful only when total effort falls without reducing quality.

## Every suggestion needs a traceable contract

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/use-ai-to-improve-metadata-img2-en.png"
        alt="A proposal card records the suggested value, target, model, prompt version, evidence, confidence, generation time, status, reviewer and decision reason across generated, validated, proposed, approved, rejected and superseded states"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A suggestion is a governed record, not a temporary chat response. Its method, evidence, confidence, lifecycle and decision must remain reconstructable.
    </figcaption>
</figure>

A suggestion record should be immutable for the version that was evaluated.

Corrections should produce a decision or a new version rather than silently changing the original model output.

A practical contract can look like this:

```yaml
suggestion_id: ms-2026-0001842
target:
  asset_id: customer_analytics.customer_daily.customer_email
  attribute: pii_category
suggested_value:
  - personal-contact-data
method:
  type: combined
  rule_version: pii-patterns-12
  detector:
    name: contact-pattern-classifier
    version: 3.4
  model:
    provider: internal-model-endpoint
    name: metadata-reasoner
    version: 2026-07
task_version: pii-classification-v6
prompt_version: pii-review-context-v4
evidence:
  - type: schema-name
    value: customer_email
  - type: profile-pattern
    value: email-format-98.7-percent
  - type: upstream-classification
    value: crm.customer.email=personal-contact-data
confidence:
  score: 0.96
  calibration_version: pii-calibration-3
generated_at: 2026-07-26T08:42:11Z
status: proposed
review:
  reviewer: null
  decided_at: null
  decision: null
  reason: null
```

### Suggested value

Store the actual proposal in a type-appropriate format.

A relationship suggestion needs source, target and relationship type. A description needs language and text. A classification may need category, scope and inherited status.

### Target asset and attribute

Stable identity is mandatory.

A suggestion for `email` is not sufficient when hundreds of fields have that name. Environment, platform, asset and version may all matter.

### Model and version

Store the exact model or endpoint version when available.

A generic label such as `AI-generated` is not enough for regression analysis.

### Prompt or task version

The task definition often changes performance more than the model name.

Store the prompt template, output schema, context-selection logic and policy version through a reproducible task version.

### Supporting evidence

Evidence should reference stable facts, not only repeat the generated rationale.

Useful evidence types include:

- schema;
- profile;
- code;
- lineage;
- approved metadata;
- glossary;
- usage;
- prior decisions;
- policy rules;
- source documents.

### Confidence

Confidence is useful only when its meaning is defined.

A raw token probability, similarity score and calibrated classification probability are different measures.

Confidence should be interpreted per task and evaluated against observed outcomes.

### Generated at

Timestamps help identify stale suggestions.

A suggestion based on an old schema, former owner or superseded policy may no longer be reviewable.

### Status

A controlled status model can include:

```text
Generated
→ Validated
→ Proposed
→ Approved
→ Superseded
```

with rejection available from the review states.

### Reviewer and decision reason

Approval without a reviewer or authorized automation identity is incomplete.

Rejection reasons are especially valuable because they become evaluation labels and reveal missing context, ambiguous vocabulary and systematic model errors.

## Match the automation level to risk

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/use-ai-to-improve-metadata-img3-en.png"
        alt="A three-level risk model maps auto-accept to formatting and deterministic mappings, bulk review to descriptions and domain suggestions, and individual approval to PII, legal usage, retention, access policy and training permission"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Automation should follow consequence, reversibility and evidence strength. Confidence is one signal, not the only approval criterion.
    </figcaption>
</figure>

The approval path should be selected by risk, not by whether AI was used.

### Auto-accept

Auto-accept is suitable for low-impact, reversible and strongly constrained changes.

Examples:

- formatting normalization;
- approved deterministic synonym mappings;
- case normalization;
- low-risk technical tags;
- exact identifier extraction;
- deduplication of whitespace;
- propagation through a verified lossless rename.

Auto-accepted changes still need provenance and rollback.

### Bulk review

Bulk review is suitable when suggestions are numerous, impact is moderate and patterns can be assessed together.

Examples:

- descriptions;
- domain suggestions;
- similar-asset links;
- business-term candidates;
- owner candidates;
- low-severity quality-rule proposals.

The interface should support grouping, filtering and sampling without hiding individual evidence.

Bulk approval must not mean blind approval.

### Individual approval

Individual approval is required when a value can activate legal, security, access, retention or AI-usage controls.

Examples:

- PII classification;
- special-category data classification;
- legal usage;
- retention;
- access policy;
- masking requirement;
- deletion policy;
- training permission;
- external-model permission.

For these decisions, the reviewer may need evidence from Legal, Security, Privacy, Records Management or the accountable data owner.

### Confidence is not a control policy

A rule such as:

```text
confidence >= 0.95 → approve
```

is insufficient.

Approval should also consider:

- task risk;
- evidence type;
- evidence freshness;
- source authority;
- model calibration;
- domain;
- ambiguity;
- reversibility;
- downstream controls;
- disagreement between methods;
- change from the current approved value.

A 99% description suggestion is not equivalent in impact to a 99% training-permission suggestion.

## Prevent proposals from activating high-impact controls

The boundary between descriptive metadata and control-driving metadata must remain explicit.

A model may propose:

```yaml
pii_category: personal-contact-data
retention_class: customer-contract-seven-years
training_permission: prohibited
```

Those values should not directly alter:

- access grants;
- masking;
- row-level security;
- retention jobs;
- deletion workflows;
- exports;
- model-training datasets;
- deployment gates.

The safe pattern is:

```text
AI proposal
→ validation
→ required approval
→ approved governance metadata
→ policy engine
→ runtime control
→ execution evidence
```

The policy engine should consume only approved values from the authorized state.

Suggestions can still trigger non-binding actions:

- create a review task;
- notify an owner;
- prioritize an asset;
- request evidence;
- block approval until required fields exist.

A high-confidence proposal may accelerate review. It must not silently become a legal or security decision.

## Design review for speed and quality

Human review is not automatically safe or efficient.

A poor interface can cause reviewers to approve fluent text without inspecting evidence.

A useful review screen should show:

```text
Current approved value
Proposed value
Difference
Evidence
Confidence and calibration
Validation results
Source freshness
Downstream impact
Similar prior decisions
```

### Use task-specific queues

Do not mix descriptions, PII classifications and owner candidates in one queue.

Different tasks require different expertise and decision criteria.

### Prioritize by impact

Review order can consider:

- criticality;
- downstream usage;
- missing mandatory metadata;
- potential exposure;
- number of dependents;
- age;
- disagreement;
- review effort.

### Support edit-and-approve

A corrected suggestion should capture:

```text
original proposal
→ reviewer-edited value
→ approved value
→ correction category
```

This provides stronger feedback than a simple approval flag.

### Capture rejection reasons

Use a controlled set with optional comments:

```yaml
reason:
  code: insufficient-evidence
  detail: The source field is a shared mailbox, not a customer email address
```

Useful reason categories include:

- wrong meaning;
- wrong scope;
- outdated context;
- insufficient evidence;
- invalid vocabulary;
- false positive;
- false negative;
- duplicate term;
- owner not accountable;
- policy requires specialist review;
- asset changed;
- suggestion no longer relevant.

### Use sampling for bulk decisions

Bulk review can combine:

- deterministic grouping;
- representative samples;
- outlier detection;
- confidence bands;
- mandatory review of disagreements;
- post-approval quality sampling.

Sampling reduces effort but does not eliminate accountability.

## A concrete example: enrich a customer field without activating a policy

Assume a warehouse contains:

```text
analytics.customer_daily.contact_email
```

The field has no description, owner or PII classification.

The enrichment service receives:

```yaml
schema:
  name: contact_email
  type: varchar
profile:
  pattern: email
  pattern_match_rate: 0.994
  null_ratio: 0.18
lineage:
  upstream:
    - crm.customer.primary_email
  downstream:
    - service_case_contact_view
approved_upstream_metadata:
  description: Primary email address supplied by the customer
  pii_category:
    - personal-contact-data
usage:
  frequent_users:
    - customer-service-analytics
code:
  transformation: lower(trim(primary_email))
```

The deterministic rule identifies a lossless normalization.

The statistical detector identifies an email pattern.

The generative model produces a proposed description:

```text
Normalized primary email address supplied by the customer and used by
customer-service analytics. Null indicates that no primary email address
was available in the CRM source at the daily load time.
```

The service creates three separate suggestions:

```yaml
description:
  review_mode: bulk-review
  confidence: 0.93

pii_category:
  value: personal-contact-data
  review_mode: individual-approval
  confidence: 0.98

owner_candidate:
  value: customer-service-data-product-owner
  review_mode: bulk-review
  confidence: 0.74
```

The classification suggestion does not activate masking.

After approval, the governance metadata service publishes the classification. The policy engine then evaluates whether masking is required for each runtime context and records evidence that the policy was applied.

The owner candidate may be rejected because the team consumes the field but does not own its source.

This example shows why suggestion type, evidence and consequence must be separated.

## Alternative implementation patterns

Different operating models can implement the same principles.

### Source-native assistance

AI suggestions are generated inside the source, transformation or BI platform.

Suitable when:

- metadata ownership is local;
- context is strongest near the source;
- the platform provides sufficient review and export;
- cross-platform consistency is not yet critical.

Warning:

Local suggestions can become fragmented and difficult to evaluate centrally.

### Central enrichment service

A shared service consumes metadata from multiple systems and writes suggestion records to a central metadata platform.

Suitable when:

- common vocabularies matter;
- evaluation should be consistent;
- cross-platform lineage is available;
- model and prompt governance are centralized.

Warning:

The service must preserve source-specific context and avoid flattening every asset into one generic prompt.

### Federated domain enrichment

A common platform provides contracts, models, metrics and controls while domains own context, review and acceptance.

Suitable when:

- terminology differs by domain;
- accountable experts are distributed;
- enterprise standards and local meaning both matter.

Warning:

Without minimum standards, every domain can create incompatible statuses, confidence meanings and review practices.

### Embedded copilot

A user requests suggestions while editing metadata.

Suitable when:

- the primary goal is authoring assistance;
- a human is already in the workflow;
- immediate context is available;
- approval is explicit.

Warning:

Chat history must not become the only provenance record. The final suggestion and evidence still need structured storage.

### Batch enrichment pipeline

Suggestions are generated on a schedule or after harvesting.

Suitable when:

- the backlog is large;
- changes can be detected;
- review queues are established;
- costs and throughput need control.

Warning:

Repeated generation can create duplicate suggestions unless asset, attribute, evidence and task versions are deduplicated.

## Close the feedback loop

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/use-ai-to-improve-metadata-img4-en.png"
        alt="A feedback loop connects suggestion generation, human decisions, decision reasons, task evaluation, improvements to context rules or prompts, retesting and release of a new version while tracking acceptance, corrections, false positives, review time, coverage, quality and drift"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Human decisions become evaluation evidence. Rejected suggestions remain available for audit, error analysis and regression testing instead of disappearing from the system.
    </figcaption>
</figure>

Feedback is useful only when it is structured and connected to the exact suggestion version.

The operating loop is:

```text
Generate suggestions
→ capture human decisions
→ capture reasons and edits
→ evaluate by task and risk
→ improve context, rules or prompt
→ re-test
→ release a new version
```

### Keep rejected suggestions

Rejected proposals should not be deleted.

They support:

- audit;
- false-positive analysis;
- regression testing;
- model comparison;
- prompt evaluation;
- reviewer calibration;
- dispute reconstruction;
- drift detection.

Retention should follow the organization’s metadata and AI evidence policy, especially when samples or sensitive evidence are involved.

### Build task-specific evaluation sets

A description benchmark and a PII-classification benchmark should not share one generic accuracy score.

Evaluation sets should represent:

- domains;
- asset types;
- languages;
- common cases;
- rare cases;
- ambiguous cases;
- changed schemas;
- conflicting evidence;
- high-impact classes.

Human-approved historical decisions can seed evaluation, but they may contain inconsistency. Gold sets need their own review and versioning.

### Re-test before release

A new model, prompt, rule, context source or threshold can improve one task and degrade another.

Regression testing should compare:

- current production version;
- candidate version;
- baseline without AI;
- domain slices;
- confidence bands;
- high-risk classes;
- known prior failures.

Release criteria should be defined per task.

## Measure effort and quality together

Acceptance rate alone is a weak success metric.

A model can achieve a high acceptance rate by proposing only obvious low-value changes. It can also create hidden work when reviewers must inspect verbose or poorly evidenced output.

A balanced scorecard includes the following.

### Acceptance rate

```text
approved suggestions / reviewed suggestions
```

Interpret by task, domain and confidence band.

### Correction rate

```text
edit-and-approved suggestions / reviewed suggestions
```

Corrections reveal near-misses and can be more informative than rejection.

### False-positive rate

Especially important for classifications and relationship proposals.

For high-impact tasks, false-negative rate must also be measured.

### Review time

Measure median and distribution, not only average.

A small number of difficult cases can dominate total effort.

### Coverage gained

Track how many useful metadata gaps were closed.

Coverage should be weighted by asset criticality and usage, not only asset count.

### Quality after approval

Approved metadata should be evaluated against quality rules such as:

- completeness;
- accuracy;
- consistency;
- specificity;
- evidence;
- vocabulary compliance;
- freshness;
- reviewer agreement;
- user usefulness.

### Drift by domain

Performance may deteriorate when schemas, terminology, source systems or business processes change.

Measure results by domain, language, asset type and source.

### Total effort

A useful outcome metric is:

```text
generation cost
+ validation cost
+ review time
+ correction time
+ operational support
```

compared with the manual baseline.

The program succeeds when this total falls while approved metadata quality remains stable or improves.

## Common anti-patterns

### Writing directly into approved fields

This destroys the separation between proposal and truth.

### Treating all confidence scores as comparable

A similarity score, detector probability and model self-rating do not have the same meaning.

### Sending unnecessary production values to a model

Metadata enrichment often needs profiles and patterns, not raw sensitive records.

### Using one prompt for every task

Descriptions, classifications, ownership and relationships require different evidence and evaluation.

### Optimizing only for acceptance rate

Reviewers may accept generic text because correcting it is slower than approving it.

### Deleting rejected suggestions

This removes the evidence needed to understand and improve failure modes.

### Auto-activating controls from generated values

A proposal should not directly change access, masking, retention, deletion or training permission.

### Assuming upstream metadata always propagates

Transformations can aggregate, tokenize, mask, combine or change meaning.

### Generating owners from activity alone

The most active user is not automatically accountable.

### Hiding unsupported claims inside fluent descriptions

Validation must compare statements with evidence, not only syntax.

### Measuring volume instead of value

Ten thousand generated descriptions are not useful when they are generic, stale or never reviewed.

## Decision guidance

Use a simple decision sequence for each enrichment task.

```text
Can the value be derived deterministically?
→ use a rule and store its version

Does the task require pattern detection?
→ use a detector and calibrate it

Does the task require synthesis across evidence?
→ use generative AI with structured output

Can an incorrect value activate a consequential control?
→ require individual approval

Can suggestions be grouped safely?
→ use bulk review with sampling and outlier checks

Can quality and effort be measured against a baseline?
→ run a controlled proof of value

Can every decision be reconstructed?
→ release to production
```

Do not begin with the question:

```text
Which model should generate all our metadata?
```

Begin with:

```text
Which metadata task creates avoidable effort?
What evidence exists?
What happens when the suggestion is wrong?
Who is accountable for approval?
How will quality be measured?
```

## Key recommendations

1. Treat every AI output as a versioned suggestion until an authorized process approves it.
2. Ground suggestions in schema, profiles, lineage, code, usage and approved metadata.
3. Separate deterministic rules, statistical detectors and generative AI in architecture and evaluation.
4. Store the model, task, prompt, evidence, confidence, timestamp and lifecycle state.
5. Define quality metrics and gold sets separately for each task and risk level.
6. Use auto-accept only for low-risk, reversible and strongly constrained changes.
7. Use bulk review for scalable moderate-risk enrichment and individual approval for consequential governance values.
8. Prevent unapproved suggestions from activating access, masking, retention, deletion or AI-usage controls.
9. Preserve rejections, corrections and decision reasons as evaluation evidence.
10. Measure total effort, coverage and approved quality together.

## The next step: operate metadata as a product

AI can make metadata creation faster.

It does not create a sustainable metadata capability by itself.

The organization still needs:

- clear consumers;
- service levels;
- ownership;
- prioritization;
- quality objectives;
- adoption measures;
- release management;
- support;
- lifecycle decisions;
- a roadmap.

Part 17 therefore moves from AI-assisted enrichment to the long-term operating model: **Operate Metadata as a Product**.
