---
title: Measure and Improve Metadata Quality — Treat Metadata as Data with Expectations, Scores and Remediation
description: A practical operating model for measuring metadata completeness, correctness, consistency, freshness, clarity, provenance, coverage, relationship integrity and operational usability, then assigning accountable remediation and tracking improvement over time.
category: Data Governance
tags:
  - metadata
  - metadata-quality
  - metadata-governance
  - data-catalog
  - data-quality
  - metadata-scoring
  - data-stewardship
  - metadata-provenance
  - metadata-freshness
  - business-glossary
  - data-lineage
  - data-products
  - kpi-governance
  - ai-ready-metadata
  - continuous-improvement
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
seriesPart: 12
seriesTitle: MetaData Deep Dive
hero: images/playbooks/measure-and-improve-metadata-quality-hero.png
publishedAt: 2026-07-01 10:00
---

## Metadata can look complete and still be unreliable

A catalog can contain thousands of assets, descriptions, owners, classifications and lineage links.

That does not mean the metadata is trustworthy.

A table may have an owner, but the owner left the organization six months ago. A KPI may have a detailed definition, but a second approved-looking definition exists in another domain. A dataset may be classified as confidential, but the review expired after a major schema change. A dashboard may link to a glossary term that no longer exists. A field description may be grammatically complete and still fail to explain units, grain, exclusions or intended use.

The catalog reports that the fields are populated.

The user still cannot make a safe decision.

This is the central problem of metadata quality: **presence is only one quality dimension**.

Completeness matters, but it does not prove correctness. Correctness does not prove freshness. Fresh metadata may still be ambiguous. A clear definition may still have unknown provenance. A technically valid relationship may be irrelevant for the operational workflow that needs it.

> **Metadata quality must be measured as a set of explicit, testable expectations. A useful quality model shows which dimension failed, why it matters, who must correct it and what evidence is required to close the issue.**

This changes metadata management from periodic documentation work into an operating discipline.

The objective is not to create a perfect enterprise score.

The objective is to make specific weaknesses visible early enough that they can be corrected before they cause wrong analysis, failed audits, unsafe automation or misleading AI answers.

## Treat metadata as data with a quality contract

Metadata should be managed with many of the same principles applied to governed datasets:

- defined schema;
- controlled values;
- accountable ownership;
- validation rules;
- freshness expectations;
- lineage and provenance;
- versioning;
- issue management;
- change evidence;
- measurable service levels.

The difference is that metadata describes and controls other assets. A metadata defect can therefore propagate beyond one record.

A wrong classification can activate the wrong protection. A stale owner can prevent escalation. A broken lineage edge can hide impact. A conflicting KPI definition can produce two executive numbers with the same label. An unclear description can cause a user or AI assistant to select the wrong field.

The quality contract should therefore answer five questions:

```text
What is expected?
For which asset type?
At which criticality?
Who is accountable?
What happens when the expectation fails?
```

A minimal contract can be represented declaratively:

```yaml
profile: governed_data_product_critical
applies_to:
  asset_type: data_product
  criticality: critical

expectations:
  description:
    required: true
    approved: true
    max_age_days: 365
  owner:
    required: true
    reference_type: accountable_role
    active_reference_required: true
  lineage:
    upstream_required: true
    downstream_required: true
    broken_edges_allowed: 0
  classification:
    required: true
    controlled_vocabulary: sensitivity_v3
    review_max_age_days: 180
  usage_restrictions:
    required_when:
      sensitivity:
        - confidential
        - restricted

failure_policy:
  critical:
    action: block_publication
  high:
    action: create_priority_issue
  medium:
    action: create_standard_issue
```

The contract is not the quality result.

It defines which checks are applicable. The result records whether those checks passed, failed, were excepted or could not be evaluated.

## Measure several quality dimensions separately

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/measure-and-improve-metadata-quality-img1-en.png"
        alt="Eight metadata quality dimensions surround one metadata profile: completeness, correctness, consistency, freshness, clarity, provenance, relationship integrity and operational usability, each with a concrete failure example"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A metadata profile can perform well in one dimension and fail in another. Separate dimensions preserve diagnostic value that a single blended score would hide.
    </figcaption>
</figure>

A practical model should distinguish at least the following dimensions.

### Completeness

Completeness asks whether required metadata exists.

Examples:

- owner is missing;
- description is empty;
- criticality is not assigned;
- classification is absent;
- review date is missing;
- a KPI has no formula;
- a Data Product has no usage restrictions.

A basic calculation is:

```text
completeness =
passed required-field checks
/
applicable required-field checks
```

Applicability matters. A source table, dashboard and AI training dataset should not share one universal mandatory-field list.

A field can be complete and wrong. Completeness must therefore never be treated as the overall quality result.

### Correctness

Correctness asks whether the metadata represents the real asset and its approved meaning.

Examples:

- a description says gross revenue while the calculation returns net revenue;
- the owner reference points to the wrong domain;
- the classification says public although personal data is present;
- the refresh frequency says hourly while the pipeline runs daily;
- the KPI formula omits returns that the approved definition requires.

Correctness is harder to automate because it often requires comparison with authoritative evidence.

Useful evidence sources include:

- source schema and constraints;
- transformation code;
- semantic model expressions;
- approved glossary definitions;
- observed runtime behaviour;
- steward review;
- reconciliation tests;
- policy decisions.

A value should not be marked correct merely because it has the expected format.

### Consistency

Consistency asks whether related metadata values agree with each other and with controlled standards.

Examples:

- the same asset is `internal` in one system and `confidential` in another;
- two approved definitions use the same KPI name;
- a Data Product belongs to Finance in the catalog and Sales in its contract;
- a field is marked deprecated while a dashboard presents it as certified;
- a controlled term is referenced through several unmanaged spellings.

Consistency checks can compare:

- source and central values;
- parent and child assets;
- local and enterprise definitions;
- environments;
- versions;
- classifications and protection policies;
- criticality and required controls.

Consistency does not mean forcing legitimate domain differences into one universal value. A conflict must first be classified as an error, an accepted local variant or an unresolved governance decision.

### Freshness

Freshness asks whether metadata is current enough for its purpose.

Examples:

- the approval review date has expired;
- the source was harvested 14 days ago although the schema changes daily;
- the owner assignment has not been verified after an organizational change;
- lineage reflects an earlier model version;
- a dashboard usage metric is too old to support deprecation decisions.

Freshness should be measured against an explicit expectation:

```text
freshness_status =
current_time - last_verified_at
compared with
allowed_age_for_attribute_and_asset
```

Not every attribute needs the same refresh cycle.

Technical schema metadata may require near-real-time or daily harvesting. Business definitions may remain valid for a year but still require review after a material change. Usage statistics may need a rolling period. Approvals may have legally or operationally defined expiry dates.

### Uniqueness

Uniqueness asks whether one governed concept is represented by the intended number of authoritative records.

Examples:

- two active glossary terms claim to be the enterprise definition of `Net Revenue`;
- one dashboard is harvested several times under different identifiers;
- an owner role is duplicated as free text and as a governed reference;
- one Data Product has several active certification records.

Uniqueness should not eliminate valid versions, environments, aliases or local concepts.

The check must use the correct identity key:

```text
business concept identity
technical asset identity
environment identity
version identity
local synonym identity
```

A duplicate is not simply a similar name. It is an unintended competing record for the same governed identity and scope.

### Clarity

Clarity asks whether people and machines can interpret the metadata without guessing.

Examples:

- `Sales amount` is described as `the sales amount`;
- a KPI definition omits time basis, currency, grain or exclusions;
- an owner field contains an unexplained team abbreviation;
- a status value has no lifecycle meaning;
- an AI usage note says `restricted` without stating which uses are prohibited.

Clarity can be evaluated through structured requirements.

A financial measure description may require:

- business meaning;
- calculation;
- grain;
- time basis;
- currency or unit;
- inclusions;
- exclusions;
- treatment of cancellations and corrections;
- intended decisions;
- known limitations;
- example.

Automated language checks can detect missing structure, circular definitions and placeholder text. Human review is still required for semantic precision.

### Provenance

Provenance asks where a metadata value came from, how it was produced and which authority supports it.

Examples:

- the source of a classification is unknown;
- an AI-generated definition has no model, prompt or evidence reference;
- a manual override replaced a source value without a reason;
- a propagated tag does not record its upstream origin;
- an approval value has no decision record.

A usable metadata value should be able to answer:

```text
source system
source object
collection or decision method
observed or inferred
creator or producer
timestamp
version
confidence
approval state
evidence reference
```

Unknown provenance does not always make a value false. It makes the value harder to trust, verify and correct.

### Coverage

Coverage asks how much of the intended metadata landscape is actually represented and evaluated.

Examples:

- 95% of warehouse tables are harvested, but only 20% of semantic measures;
- all Tier 1 Data Products are scored, but critical dashboards are not;
- column lineage exists for one platform and stops at the BI layer;
- definitions cover active assets but ignore AI training datasets.

Coverage differs from completeness.

Completeness evaluates whether a known asset has its required attributes. Coverage evaluates whether the relevant assets, relationships and platforms are included in the quality scope at all.

Useful coverage measures include:

```text
assets inventoried / assets expected
assets scored / assets inventoried
critical assets scored / critical assets expected
relationships observed / relationships expected
platforms connected / platforms in scope
```

### Relationship integrity

Relationship integrity asks whether links between metadata objects are valid, current and semantically appropriate.

Examples:

- a glossary link points to a deleted term;
- lineage references a removed column;
- a dashboard points to a superseded semantic model;
- a Data Product has an owner reference that no longer resolves;
- a KPI links to the wrong calculation asset;
- an exception references a policy version that is no longer valid.

Relationship checks should validate:

- both endpoints exist;
- the relationship type is allowed;
- scope and environment match;
- version rules are respected;
- cardinality is valid;
- the relationship is not expired;
- evidence supports inferred links.

A graph with many edges can still have poor integrity.

### Operational usability

Operational usability asks whether metadata can support the action for which it is intended.

Examples:

- a classification exists but cannot resolve to a protection policy;
- an owner name is present but cannot be routed to a responsible queue;
- a retention label has no executable policy mapping;
- a definition is readable but cannot be retrieved through the interface used by an AI application;
- a quality tier exists but activates no checks;
- a usage restriction is free text that no control can evaluate.

Operational usability is the strongest test of metadata maturity.

The question is not only:

```text
Is metadata present?
```

It is:

```text
Can the intended consumer use it reliably?
```

Consumers may include people, search, workflows, deployment pipelines, policy engines, observability systems, BI tools, RAG systems and AI assistants.

## Start with the simplest viable implementation

A metadata-quality initiative does not need an enterprise-wide scoring engine on the first day.

The simplest viable implementation can use five steps.

### 1. Select one asset class

Begin with an asset type that has clear business value and accountable owners.

Suitable starting points include:

- governed Data Products;
- critical KPIs;
- certified dashboards;
- sensitive datasets;
- AI training datasets.

Avoid beginning with every harvested technical object. A narrow scope makes expectations and remediation manageable.

### 2. Define ten to twenty explicit checks

Checks should be understandable and actionable.

Examples:

```text
owner reference exists
owner reference resolves
description is not placeholder text
definition contains grain
classification uses approved vocabulary
classification review is current
upstream lineage exists
glossary links resolve
approval is valid
usage restrictions exist when required
```

Each check should record:

- check identifier;
- applicable asset profile;
- dimension;
- severity;
- evaluation method;
- owner of the rule;
- remediation route;
- evidence requirement.

### 3. Evaluate and store detailed results

Store one result per check, not only a final score.

Example:

```yaml
asset: data_product.customer_contact
profile: governed_data_product_critical
evaluated_at: 2026-07-25T12:00:00Z

checks:
  - id: owner_reference_resolves
    dimension: completeness
    status: passed
    evidence: identity-role/customer-data-owner
  - id: classification_review_current
    dimension: freshness
    status: failed
    observed: 224_days
    expected_max: 180_days
  - id: glossary_relationship_valid
    dimension: relationship_integrity
    status: failed
    relationship: glossary/customer-contact
    reason: target_not_found
```

This result is diagnosable. A score of `82` is not.

### 4. Route issues to accountable owners

A failed check should create a task with:

- asset;
- failing rule;
- business impact;
- severity;
- responsible source;
- accountable owner;
- correction location;
- due date or service target;
- evidence required for closure;
- exception path.

The responsible source is important.

A wrong dbt description should normally be corrected in code. A stale identity reference should be corrected in the authoritative ownership system. A glossary conflict should be resolved in the governed vocabulary process. The central catalog should not silently patch every defect locally.

### 5. Re-evaluate after correction

Closure requires new evidence.

A task is not complete because someone changed a field. The affected metadata must be re-harvested or re-read, the rule must pass and the result must link to the corrected version.

## Score metadata by asset type and criticality

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/measure-and-improve-metadata-quality-img2-en.png"
        alt="A scoring matrix compares source tables, governed data products, KPIs, dashboards and AI training datasets across description, owner, lineage, classification, freshness, approval and usage restrictions, with weights changing for low, important and critical assets"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Quality expectations depend on asset type and criticality. A universal mandatory-field list creates false failures for some assets and dangerous omissions for others.
    </figcaption>
</figure>

Different assets support different decisions.

A source table primarily needs technical identity, schema, source ownership, freshness and upstream operational context. A governed Data Product needs business definition, accountability, lineage, quality expectations, classification, approval and supported use. A KPI needs formula, grain, filters, time basis, owner and authoritative implementation. A dashboard needs consumer context, certified source links, owner, usage and lifecycle state. An AI training dataset needs lineage, permitted use, provenance, representativeness context, restrictions, approval and version evidence.

A useful profile matrix may look like this:

| Check | Source table | Governed Data Product | KPI | Dashboard | AI training dataset |
|---|---:|---:|---:|---:|---:|
| Description | expected | required | required | required | required |
| Owner | technical | accountable + technical | business | product/report | accountable + technical |
| Lineage | upstream | end-to-end | formula + source | semantic + source | source + preparation |
| Classification | when sensitive | required | inherited + reviewed | inherited | required |
| Freshness | harvest and data | metadata and data | review | usage and content | version and source |
| Approval | optional | required | required | certification | required |
| Usage restrictions | when relevant | required | when relevant | sharing/export | required |

Criticality changes the weight and consequence of failure.

### Low criticality

A low-criticality exploratory asset may allow:

- draft description;
- team ownership instead of named accountable role;
- partial lineage;
- warning instead of publication block;
- longer review interval.

### Important

An important asset may require:

- approved description;
- accountable owner;
- validated upstream lineage;
- current classification;
- issue SLA;
- visible quality status.

### Critical

A critical asset may require:

- approved definition and owner;
- complete required lineage;
- current classification and approval;
- zero unresolved mandatory relationship failures;
- explicit usage restrictions;
- blocking policy for selected defects;
- closure evidence;
- controlled exceptions with expiry.

Criticality should influence both the score and the action.

A missing owner on a sandbox table may create a low-priority task. The same failure on a regulatory report or AI training dataset may block publication.

## Use scores without hiding the diagnosis

Scores are useful for comparison and trend analysis when they remain decomposable.

A dimension score can be calculated as:

```text
dimension score =
sum(check result × check weight)
/
sum(applicable check weights)
```

Where check result may be:

```text
passed = 1.0
warning = 0.5
failed = 0.0
not_applicable = excluded
not_evaluated = separate status
excepted = reported separately
```

Do not treat `not evaluated` as passed.

Do not hide exceptions inside the normal score.

A profile can expose:

```yaml
quality:
  completeness: 100
  correctness: 72
  consistency: 88
  freshness: 45
  clarity: 92
  provenance: 60
  relationship_integrity: 80
  operational_usability: 50

status:
  mandatory_failures: 2
  warnings: 3
  exceptions: 1
  not_evaluated: 4
```

This is more useful than:

```text
enterprise metadata quality score: 76
```

The blended score may still be displayed as a navigation aid, but it must never replace the dimension profile and failed-rule list.

Mandatory failures should remain visible even when the weighted average looks acceptable.

## Measure concrete metadata defects

### Mandatory fields

Mandatory-field checks should validate more than non-null values.

An owner field can fail because:

- it is empty;
- it contains placeholder text;
- the reference does not resolve;
- the role is inactive;
- the referenced owner is not accountable for the asset scope;
- the value is present only in a copied location;
- the assignment expired.

A description can fail because:

- it is empty;
- it repeats the technical name;
- it is below the required structure;
- it is not approved;
- it is stale after a material change;
- its language does not match the governed version.

### Broken relationships

Relationship checks should detect:

- missing targets;
- deleted targets;
- invalid relationship types;
- cross-environment links;
- circular ownership;
- lineage to obsolete versions;
- multiple active authoritative links;
- expired policy references;
- orphaned reports;
- unlinked semantic measures.

Each failure should identify both endpoints and the source that asserted the relationship.

### Stale approvals

An approval can be stale because:

- its review date expired;
- the governed asset changed materially;
- the approving policy version was replaced;
- the approver no longer holds the required authority;
- the source evidence changed;
- a dependent classification changed;
- the exception supporting the approval expired.

Freshness should therefore be event-aware.

A one-year review interval is not sufficient when a schema change introduces a new personal-data field after two weeks.

### Conflicting definitions

Definition conflicts should be detected by more than text similarity.

A conflict can involve:

- same governed name, different formula;
- same formula, different inclusion rules;
- same description, different time basis;
- local term incorrectly presented as enterprise term;
- two active authoritative records;
- approved definition and deployed semantic expression diverge.

Conflict resolution should retain legitimate local context.

Possible outcomes include:

```text
one definition corrected
one record deprecated
local variant retained
synonym created
scope clarified
mapping approved
conflict remains unresolved
```

Unresolved conflicts should be visible and routed. They should not be averaged into a quality score and forgotten.

## Use confidence and evidence for detected or inferred metadata

Not all metadata is declared by an authoritative human or system.

Classifications may be detected. Owners may be suggested from repository history. Glossary links may be inferred. Descriptions may be generated. Lineage may be parsed from SQL or observed at runtime.

These values can be useful before final approval when their status is explicit.

A proposed value should record:

```yaml
value: confidential
status: proposed
method: pattern_detection
confidence: 0.87
source:
  system: warehouse_scanner
  object: crm.customer.email
evidence:
  - detected_email_pattern
  - column_name_similarity
model_or_rule_version: classifier-4.2
generated_at: 2026-07-25T10:15:00Z
review_owner: role:data-privacy-steward
```

Confidence is not correctness.

A value with `0.98` confidence can still be wrong. A value with `0.65` confidence may still be useful as a review priority.

Confidence should influence:

- review order;
- automation threshold;
- whether the proposal can be displayed;
- whether it may be propagated;
- whether human approval is mandatory.

Control-driving metadata should require an approval policy independent of detector confidence.

Evidence must be retained so that reviewers can understand why the suggestion was made and so that rejected proposals can improve later matching without losing auditability.

## Operate a controlled metadata-quality issue workflow

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/measure-and-improve-metadata-quality-img3-en.png"
        alt="Metadata quality issues move from detection through severity classification, responsible-source identification, owner assignment, correction at source, re-harvesting, validation and closure with evidence; documented exceptions require an owner and expiry date"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadata defects should be corrected at the responsible source, re-collected and revalidated. Closure requires evidence, while exceptions remain scoped, owned and time-limited.
    </figcaption>
</figure>

A complete workflow is:

```text
Detect Issue
→ Classify Severity
→ Identify Responsible Source
→ Assign Owner
→ Correct at Source
→ Re-Harvest
→ Validate
→ Close with Evidence
```

### Detect issue

Detection may come from:

- automated rule;
- failed relationship validation;
- schema change;
- user feedback;
- failed search;
- audit finding;
- AI answer failure;
- incident analysis;
- steward review.

### Classify severity

Severity should consider:

- asset criticality;
- affected consumers;
- regulatory or contractual impact;
- control-driving use;
- likelihood of wrong decisions;
- number of downstream assets;
- duration;
- availability of a safe workaround.

A missing description and a wrong permitted-use restriction are not equivalent.

### Identify the responsible source

The issue record should state where the correction belongs:

```text
database catalog
transformation repository
semantic model
business glossary
identity system
policy registry
central metadata platform
observability system
```

The central quality service may detect the problem without becoming the authoring system.

### Assign the accountable owner

Assignment can use:

- source-system owner;
- Data Product owner;
- Data Steward;
- KPI owner;
- dashboard owner;
- policy owner;
- technical owner;
- governance queue.

The assignee fixes or coordinates the correction. Accountability remains with the governed owner.

### Correct at source

Corrections should normally occur where the value is authoritative.

Local correction prevents the next harvest from overwriting a central patch.

### Re-harvest and validate

The corrected version must be collected again. Validation confirms:

- the expected source changed;
- the value now passes;
- related conflicts were resolved;
- no new defect was introduced;
- the correct version is visible to consumers.

### Close with evidence

Closure evidence can include:

- source commit;
- approval record;
- successful check result;
- resolved relationship;
- new harvest timestamp;
- policy mapping;
- steward decision;
- screenshot or run reference where necessary.

The issue should record the time from detection to assignment, correction, validation and closure.

## Allow documented exceptions without normalizing failure

Some metadata defects cannot be corrected immediately.

A source system may not support a required field. A merger may leave temporary duplicate definitions. A legacy dashboard may need to remain available while replacement work continues. A policy review may depend on an external decision.

An exception should contain:

```yaml
exception_id: MQE-2026-014
asset: dashboard.regulatory_legacy
failed_rule: authoritative_kpi_link_required
reason: replacement_dashboard_in_validation
owner: role:finance-reporting-owner
approved_by: role:data-governance-chair
effective_from: 2026-07-01
expires_at: 2026-09-30
compensating_control: monthly_manual_reconciliation
review_frequency: monthly
```

An exception is not a pass.

It should be reported separately, included in aging views and re-evaluated at expiry. An exception without owner, scope, reason and expiry is simply an unmanaged defect.

## Concrete example: improve the metadata profile of a critical KPI

Assume the organization publishes a critical KPI named `Net Revenue`.

Its technical implementation is a semantic measure. It is used in executive dashboards, planning exports and an AI assistant that answers management questions.

The initial metadata profile contains:

```yaml
name: Net Revenue
description: Revenue after deductions
owner: Finance BI
formula: SUM(invoice_amount - discount_amount)
classification: internal
approval_status: approved
reviewed_at: 2025-10-01
```

The profile looks complete.

A detailed quality evaluation finds:

1. **Correctness failure**  
   The approved Finance definition also subtracts returns and credit notes. The deployed formula does not.

2. **Clarity failure**  
   The definition does not state currency conversion, posting date, exclusion of cancelled invoices or treatment of late corrections.

3. **Freshness failure**  
   The review is older than the 180-day requirement for critical KPIs.

4. **Uniqueness failure**  
   Sales Operations has another active `Net Revenue` definition based on order date.

5. **Provenance failure**  
   The approval record has no policy version or evidence reference.

6. **Relationship-integrity failure**  
   One executive dashboard links to a deprecated semantic measure with the same display label.

7. **Operational-usability failure**  
   The AI assistant retrieves both definitions without a scope rule and may combine them.

The dimension profile is:

```yaml
completeness: 100
correctness: 40
consistency: 35
freshness: 0
uniqueness: 50
clarity: 45
provenance: 25
relationship_integrity: 60
operational_usability: 20
mandatory_failures: 4
```

A single completeness score would have reported success.

The remediation workflow assigns:

- formula correction to the semantic-model owner;
- definition decision to the Finance KPI owner;
- local Sales Operations variant to its domain steward;
- approval refresh to the governance owner;
- deprecated dashboard relationship to the report owner;
- retrieval scope rule to the AI application owner.

After correction, the authoritative metadata becomes:

```yaml
term_id: kpi.finance.net_revenue
display_name: Net Revenue
scope: group_financial_reporting
grain: legal_entity_day
time_basis: accounting_posting_date
currency_basis: group_reporting_currency

calculation:
  formula_reference: semantic.finance.net_revenue.v4
  includes:
    - posted_invoice_amount
  subtracts:
    - discounts
    - returns
    - credit_notes
  excludes:
    - cancelled_invoices
    - unposted_documents

ownership:
  business_owner: role:group-finance-kpi-owner
  technical_owner: team:finance-semantic-platform

approval:
  status: approved
  policy_version: KPI-GOV-3.1
  evidence_reference: decision/KPI-2026-041
  reviewed_at: 2026-07-20
  review_by: 2027-01-20

usage:
  approved_for:
    - executive_reporting
    - planning
    - governed_ai_retrieval
  not_equivalent_to:
    - kpi.sales_ops.net_revenue
```

The local Sales Operations definition is retained with a different scope and explicit mapping. Legitimate difference is preserved without presenting both values as one universal KPI.

## Choose an implementation pattern that matches maturity

### Source-native validation

Rules execute in the source platform, repository or semantic layer.

Suitable when:

- one system is authoritative;
- checks can be expressed close to the metadata;
- correction ownership is local;
- central aggregation is secondary.

Risk:

Cross-platform conflicts, enterprise trends and shared criticality models remain difficult.

### Central metadata-quality service

A central service evaluates normalized metadata from several systems.

Suitable when:

- one enterprise view is required;
- common rules and reporting are important;
- source systems expose sufficient metadata;
- dedicated integration ownership exists.

Risk:

The service can become a second authoring system or evaluate stale copies if provenance and freshness are weak.

### Contract-as-code

Quality expectations are versioned with Data Product or transformation code and checked in CI/CD.

Suitable when:

- engineering workflows are mature;
- metadata changes travel with code;
- deployment gates are required;
- rules can be represented declaratively.

Risk:

Business definitions, approvals and exceptions still need accessible governance workflows. Code review does not automatically create business authority.

### Federated stewardship

Domains define and operate local quality profiles within enterprise minimum standards.

Suitable when:

- domain semantics differ;
- accountable stewards exist;
- central governance can define shared dimensions and evidence;
- local correction speed matters.

Risk:

Scores become incomparable when domains redefine checks, severity or applicability without control.

### Hybrid operating model

A central framework defines:

- common dimensions;
- rule schema;
- evidence model;
- criticality levels;
- issue states;
- exception requirements;
- enterprise reporting.

Domains define:

- asset-specific expectations;
- local vocabularies;
- authoritative sources;
- accountable owners;
- remediation workflows;
- justified local thresholds.

This is usually the most practical enterprise pattern.

It preserves comparability without forcing every asset into the same checklist.

## Run a continuous metadata-improvement loop

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/measure-and-improve-metadata-quality-img4-en.png"
        alt="A continuous loop moves through measure, prioritize, correct, validate, publish, observe usage, learn and measure again, using quality rules, feedback, failed searches, AI answer failures, audits and schema changes to improve templates, automation, ownership and vocabulary"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadata quality improves through repeated measurement, source correction, validation and observed use. Failures become inputs for better templates, automation, ownership and controlled vocabulary.
    </figcaption>
</figure>

The operating loop is:

```text
Measure
→ Prioritize
→ Correct
→ Validate
→ Publish
→ Observe Usage
→ Learn
→ Measure
```

### Measure

Evaluate applicable rules and retain detailed evidence.

### Prioritize

Use criticality, severity, downstream impact, age and consumer need.

### Correct

Change the authoritative source, not only the central representation.

### Validate

Re-run the relevant checks and confirm relationships, approval and publication state.

### Publish

Expose the corrected metadata to search, governance, pipelines, BI and AI consumers.

### Observe usage

Monitor whether users can find the asset, whether definitions are selected correctly, whether controls resolve and whether downstream applications use the intended version.

### Learn

Use failures to improve:

- description templates;
- controlled vocabularies;
- automated detection;
- source connectors;
- ownership assignment;
- review intervals;
- default quality profiles;
- training and stewardship guidance.

The loop should absorb several inputs:

```text
quality-rule failures
user feedback
failed searches
zero-result searches
AI answer failures
audit findings
schema changes
incidents
deprecated assets
unresolved conflicts
```

The outputs should reduce future manual effort:

```text
better templates
stronger automation
updated ownership
refined controlled vocabulary
earlier validation
fewer duplicate concepts
more precise retrieval
faster remediation
```

## Track trends, aging and exceptions

A quality dashboard should support action, not only presentation.

Useful views include:

### Dimension trend

Show completeness, correctness, freshness and other dimensions separately over time.

A stable overall score can hide that completeness improved while freshness declined.

### Critical-asset status

Report:

```text
critical assets with mandatory failures
critical assets not evaluated
critical assets with expired approval
critical assets with unresolved definition conflicts
```

### Issue aging

Use aging buckets such as:

```text
0–7 days
8–30 days
31–90 days
more than 90 days
```

Segment by severity, owner, domain, source and rule.

### Time to remediation

Measure:

- detection to assignment;
- assignment to correction;
- correction to re-harvest;
- re-harvest to validation;
- total time to closure.

This separates ownership delay from connector or validation delay.

### Reopen rate

A high reopen rate indicates superficial corrections, unstable source processes or weak validation.

### Exception aging

Track:

- active exceptions;
- exceptions near expiry;
- expired exceptions;
- repeatedly renewed exceptions;
- compensating controls without evidence.

Repeated renewal is a signal that the exception has become an undocumented target state.

### Coverage trend

Show whether more of the relevant landscape is actually being evaluated.

An improving score on a shrinking or biased scope is misleading.

## Avoid common anti-patterns

### One opaque enterprise score

A single number is easy to present and difficult to act on.

It hides the failing dimension, applicable rules, mandatory failures, exceptions and unevaluated scope.

### Universal mandatory-field list

One checklist for tables, KPIs, dashboards and AI datasets creates noise and false confidence.

### Non-null equals quality

Placeholder text, unresolved references and stale values can all be non-null.

### Central correction of source defects

A manual patch in the catalog may be overwritten by the next harvest and leaves the authoritative source wrong.

### Confidence treated as approval

A detector score is evidence for review, not governance authority.

### Exceptions counted as passes

This makes risk invisible and removes pressure to resolve temporary conditions.

### Stale assets improve the score

Deprecated or orphaned assets should be classified explicitly. They should not silently disappear from the denominator to improve results.

### Quality without remediation ownership

A dashboard that identifies defects but cannot route them becomes another reporting layer.

### Measuring only what is easy

Completeness is easy to automate. Correctness, clarity and operational usability require more deliberate evidence and review. Ignoring them produces a polished but weak catalog.

### Punishing domains for broader coverage

A domain that connects more systems may initially reveal more defects. Compare trend, criticality and coverage, not only raw failure counts.

## Decision guidance

Use the following questions to design the operating model:

1. Which decisions depend on the metadata?
2. Which asset types are in scope?
3. Which assets are critical?
4. Which dimensions can be tested automatically?
5. Which checks require human judgment?
6. What is the authoritative source for each value?
7. Who owns the rule?
8. Who owns remediation?
9. Which failures should block publication or deployment?
10. How are proposals, exceptions and unevaluated checks represented?
11. What evidence is required for closure?
12. How will quality trends be segmented by coverage and criticality?
13. Which consumer failures should feed the improvement loop?
14. How will corrected metadata be republished to downstream systems?

The safest starting design is not the largest scorecard.

It is the smallest complete loop that detects a material defect, assigns it to the correct owner, corrects it at the source, validates the result and records evidence.

## Key recommendations

1. Treat metadata as governed data with explicit schemas, owners, rules, evidence and lifecycle.
2. Measure completeness, correctness, consistency, freshness, uniqueness, clarity, provenance, coverage, relationship integrity and operational usability separately.
3. Define expectations by asset type and criticality.
4. Store detailed check results instead of only a blended score.
5. Keep mandatory failures, warnings, exceptions and unevaluated checks distinct.
6. Validate references, authority, state and freshness rather than only non-null values.
7. Use controlled identity keys before detecting duplicates.
8. Record method, confidence, evidence, status and version for inferred metadata.
9. Require independent approval for control-driving metadata.
10. Route every actionable defect to an accountable owner and responsible source.
11. Correct metadata at its authoritative source whenever possible.
12. Re-harvest and revalidate before closing an issue.
13. Keep exceptions scoped, approved, owned and expiring.
14. Track trends, coverage, issue aging, remediation time and reopen rate.
15. Use failed searches, user feedback, AI answer failures, audits and schema changes as improvement inputs.
16. Avoid one enterprise score that conceals specific risks.
17. Begin with one high-value asset class and a complete remediation loop.

> **Metadata quality is not the percentage of populated fields. It is the demonstrated ability of metadata to remain correct, current, explainable, connected and usable for the decisions and controls that depend on it.**

## Next: activate metadata through automation

Measurement creates visibility.

It does not correct a source, route an owner, update a catalog, block a deployment or trigger a review by itself.

Part 13 examines **how to activate metadata through automation**:

- event-driven metadata workflows;
- tasks and notifications;
- automated synchronization;
- policy activation;
- deployment and quality gates;
- ownership routing;
- approval orchestration;
- closed-loop evidence.

Part 12 establishes whether metadata can be trusted. Part 13 turns trustworthy metadata and detected quality events into controlled action.
