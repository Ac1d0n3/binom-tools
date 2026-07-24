---
title: Propagating PII Metadata across Data Warehouses
description: A controlled approach for preserving, merging and reviewing PII classifications as columns move through RAW, Conform, Core and Analytics models.
category: Data Governance
tags:
  - data-governance
  - pii
  - metadata-propagation
  - dbt
  - column-lineage
  - data-lineage
  - data-classification
  - data-warehouse
  - privacy
  - ci
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 4
seriesTitle: End-to-End Data Governance
hero: images/playbooks/propagating-pii-metadata-across-data-warehouses-hero.png
---

## PII metadata must follow the data

Part 3, [Automatic RAW Generation using dbt Macros](/playbooks/automatic-raw-generation-using-dbt-macros), created controlled RAW models from physical warehouse metadata and approved governance metadata.

The RAW layer is now explicit, reviewable and versioned.

The next problem begins as soon as a downstream model selects, renames, combines or transforms those columns.

```sql
select
    customer_id,
    lower(trim(email)) as normalized_email,
    concat(first_name, ' ', last_name) as customer_name
from {{ ref('raw_crm_customer') }}
```

The source columns may already have approved metadata:

```yaml
email:
  pii: true
  pii_category: email
  sensitivity: confidential
  classification_status: approved

first_name:
  pii: true
  pii_category: name
  sensitivity: confidential
  classification_status: approved

last_name:
  pii: true
  pii_category: name
  sensitivity: confidential
  classification_status: approved
```

The downstream columns are still sensitive.

But dbt does not automatically derive the correct governance result from the SQL semantics.

> **Data lineage identifies where a column came from. A propagation rule determines what its metadata should become.**

## Model lineage is not enough

Model-level lineage answers:

```text
Which model depends on which upstream model?
```

That is useful for deployment, impact analysis and ownership.

PII propagation needs a more precise question:

```text
Which upstream columns contribute to this target column?
```

That requires column-level lineage or an explicit column mapping.

A model can depend on a sensitive upstream model without selecting any sensitive columns.

The opposite is also possible: one derived column may combine several sensitive source columns.

A propagation system therefore needs at least:

- model dependencies;
- target column names;
- source column references;
- transformation type;
- approved source metadata;
- explicit target overrides;
- conflict rules.

dbt's manifest provides resource metadata and first-order resource relationships. Column-level lineage requires SQL parsing or another lineage mechanism, and it may be incomplete for ambiguous SQL, complex lateral constructs or Python models.

The architecture must handle uncertainty rather than pretending every transformation can be resolved automatically.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/propagating-pii-metadata-across-data-warehouses-img1-en.png"
        alt="PII metadata moving through RAW, Conform, Core, Analytics and Consumption layers with a separate controlled path for lineage, propagation rules, validation and approval"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Lineage provides the evidence. Propagation rules and governance review determine the approved downstream metadata.
    </figcaption>
</figure>

## Do not copy metadata blindly

The simplest possible implementation copies all source metadata to every downstream column.

That fails quickly.

Consider these transformations:

```sql
email as email
lower(email) as normalized_email
split_part(email, '@', 2) as email_domain
sha2(email) as email_hash
count(distinct email) as customer_count
'CRM' as source_system
```

All six columns reference or appear beside the same source field, but they do not have the same meaning.

Blind copying can create two kinds of error:

### Under-classification

A transformed field still identifies or relates to a person, but the metadata is lost.

Examples:

- renamed email address;
- normalized telephone number;
- concatenated full name;
- deterministic customer hash;
- extracted account identifier.

### Over-classification

A technical or sufficiently aggregated result inherits a classification that no longer applies.

Examples:

- constant source-system label;
- approved high-level aggregate;
- boolean quality flag;
- record count;
- technical load timestamp.

The propagation engine must therefore classify the transformation, not merely detect a dependency.

## Define propagation rules by transformation type

A practical rule set can begin with seven transformation classes.

### 1. Pass-through

```sql
email
```

Recommended rule:

```text
Copy approved column metadata.
```

This is the strongest candidate for automatic propagation.

### 2. Rename

```sql
email as customer_email
```

Recommended rule:

```text
Copy approved column metadata and record the source column.
```

A new name does not change the data meaning.

### 3. Cast or formatting transformation

```sql
lower(trim(email)) as normalized_email
cast(customer_id as varchar) as customer_id_text
```

Recommended rule:

```text
Retain the source classification unless an approved rule says otherwise.
```

Case conversion, trimming, formatting and type conversion usually change representation, not sensitivity.

### 4. Combination

```sql
concat(first_name, ' ', last_name) as full_name
coalesce(mobile_phone, landline_phone) as preferred_phone
```

Recommended rule:

```text
Merge all contributing source classifications.
```

The result should normally use the most restrictive sensitivity and the union of relevant PII categories.

For example:

```yaml
pii: true
pii_category:
  - name
sensitivity: confidential
classification_status: proposed
propagated_from:
  - raw_crm_customer.first_name
  - raw_crm_customer.last_name
```

The status may remain `approved` only when an approved transformation rule covers the combination. Otherwise, use `proposed`.

### 5. Hashing, tokenization or pseudonymization

```sql
sha2(email) as customer_token
```

Recommended rule:

```text
Retain sensitivity unless an approved governance rule explicitly changes it.
```

A deterministic hash may still support matching, linkage or re-identification. It must not be treated as anonymous merely because the clear text is gone.

The metadata may change category:

```yaml
pii: true
pii_category: pseudonymous_identifier
sensitivity: confidential
classification_status: proposed
```

### 6. Aggregation

```sql
count(distinct customer_id) as customer_count
```

Recommended rule:

```text
Do not downgrade automatically.
Send the result through an aggregation review rule.
```

The correct result depends on:

- aggregation grain;
- minimum group size;
- filters;
- sparse dimensions;
- suppression rules;
- possibility of differencing attacks;
- permitted use.

A monthly count by country may be non-personal in one context. A count by a rare medical condition and postcode may remain highly sensitive.

### 7. Constant or technical column

```sql
'CRM' as source_system
current_timestamp as loaded_at
```

Recommended rule:

```text
Do not inherit classification from neighboring source columns.
```

Technical columns need their own metadata, but not the PII metadata of unrelated inputs.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/propagating-pii-metadata-across-data-warehouses-img2-en.png"
        alt="Seven propagation rules for pass-through, rename, formatting, combination, hashing, aggregation and technical columns with automatic, rule-based and review-required outcomes"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Different transformation types require different propagation behavior. Only semantically safe cases should be fully automatic.
    </figcaption>
</figure>

## Use explicit metadata results

A propagated result should show how it was produced.

Example:

```yaml
columns:
  - name: normalized_email
    config:
      meta:
        pii: true
        pii_category: email
        sensitivity: confidential
        classification_status: approved
        propagation_method: approved_rule
        propagated_from:
          - raw_crm_customer.email
        propagation_rule: retain_on_normalization
```

A generated proposal might look like:

```yaml
columns:
  - name: full_name
    config:
      meta:
        pii: true
        pii_category:
          - name
        sensitivity: confidential
        classification_status: proposed
        propagation_method: inferred
        propagated_from:
          - raw_crm_customer.first_name
          - raw_crm_customer.last_name
        review_reason: multiple_sensitive_inputs
```

An unresolved transformation should remain explicit:

```yaml
columns:
  - name: customer_segment_code
    config:
      meta:
        classification_status: unreviewed
        propagation_method: unresolved
        review_reason: unsupported_sql_expression
```

The system must never convert uncertainty into silent approval.

## Resolve multiple inputs deterministically

A derived column can have several upstream inputs with different classifications.

Example:

```sql
concat(customer_id, ':', email) as contact_key
```

Input metadata:

| Input | PII | Sensitivity | Status |
| --- | --- | --- | --- |
| `customer_id` | true | internal | approved |
| `email` | true | confidential | approved |

A conservative default is:

```text
PII = true if any contributing source is PII
Sensitivity = most restrictive contributing value
PII category = controlled merge or new derived category
Status = proposed unless an approved rule resolves the combination
```

The metadata resolver needs an explicit severity order:

```text
public
< internal
< confidential
< restricted
```

It also needs controlled category mapping.

The union of `email` and `customer_identifier` should not become a free-form string such as:

```text
email + customer id
```

Instead, use an approved derived category:

```yaml
pii_category: contact_identifier
```

## Define precedence for overrides and proposals

Not every result should be generated.

The strongest source of truth should win.

A practical precedence model is:

```text
Approved target override
> approved transformation rule
> propagated approved source metadata
> inferred or detected proposal
```

### Approved target override

A steward explicitly classifies the target column.

```yaml
classification_status: approved
classification_source: steward_override
```

The propagation process must preserve it.

### Approved transformation rule

A reusable rule has been reviewed.

Example:

```text
lower(trim(email))
→ retain email classification
```

The engine may apply this automatically.

### Propagated source metadata

Simple pass-through or rename may reuse approved upstream metadata.

### Detection proposal

Name-based or content-based detection can suggest a classification.

It must never overwrite an approved decision.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/propagating-pii-metadata-across-data-warehouses-img3-en.png"
        alt="Multiple classified source columns resolved through column lineage, transformation rules, target overrides and conflict policies into approved, proposed or unresolved target metadata"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Conflicts require deterministic precedence. Approved target decisions remain stronger than generated propagation results.
    </figcaption>
</figure>

## Row-level governance also needs propagation

Column classification is only one part of governance.

A model may introduce or remove row-level dimensions such as:

- legal entity;
- region;
- tenant;
- department;
- customer portfolio;
- contract scope.

A join can add a new access domain even when no PII column changes.

Example:

```sql
select
    o.order_id,
    o.customer_id,
    c.legal_entity,
    o.amount
from {{ ref('conform_orders') }} o
join {{ ref('conform_customer') }} c
  on o.customer_id = c.customer_id
```

The model now contains `legal_entity`, which may be required for row-level enforcement.

Column lineage alone does not define the correct access rule for the whole model.

Use model-level metadata:

```yaml
config:
  meta:
    row_access_domain: legal_entity
    row_access_status: proposed
```

The propagation process can detect candidate access dimensions, but the final policy mapping requires review.

## Separate technical lineage from governance lineage

Technical lineage answers:

```text
Which SQL expression produced this column?
```

Governance lineage adds:

```text
Which classification decision was inherited?
Which rule changed it?
Who approved the result?
Which protection policy should apply?
```

A governance lineage record may contain:

```yaml
target: analytics_customer.customer_contact
sources:
  - conform_customer.normalized_email
rule: retain_on_formatting
previous_status: approved
result_status: approved
approved_by: data_governance
policy_mapping: email_mask
```

This creates evidence beyond a visual dependency graph.

## Generate a diff, not a hidden metadata update

Propagation should behave like code generation.

Recommended workflow:

```text
Parse dbt project
→ compile or parse models
→ construct column mappings
→ read approved metadata
→ apply propagation rules
→ compare target metadata
→ generate a deterministic diff
→ run governance validation
→ request review
```

The generated diff may:

- add metadata for a new target column;
- update `propagated_from`;
- mark a conflict as unresolved;
- propose a stronger sensitivity;
- identify a missing masking policy;
- flag an orphaned override;
- remove stale generated metadata after review.

It must not silently mutate the production catalog or warehouse.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/propagating-pii-metadata-across-data-warehouses-img4-en.png"
        alt="Pull-request workflow that parses models, builds column lineage, applies propagation rules, generates metadata differences, validates conflicts and requires steward approval before deployment"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Propagated metadata changes governed code. Git review keeps inferred changes, conflicts and overrides visible.
    </figcaption>
</figure>

## Validate the downstream result

A governance validator should inspect the final target metadata, not only the propagation process.

Useful rules include:

- every approved PII column has a PII category;
- every approved confidential or restricted PII column has a protection mapping;
- no `unreviewed` column enters a protected Analytics model;
- propagated metadata references valid upstream columns;
- approved overrides include an owner or review reference;
- sensitivity cannot be downgraded without an approved rule;
- deleted source columns do not leave stale propagation records;
- row-access domains exist in the entitlement model;
- a model with sensitive columns has an accountable owner.

Example rule:

```text
If pii = true
and classification_status = approved
and sensitivity in [confidential, restricted]
then masking_policy must be present.
```

This prepares the metadata for runtime enforcement in Part 5.

## Handle incomplete lineage safely

SQL lineage can be incomplete.

Common difficult cases include:

- dynamic SQL;
- macros that emit complex expressions;
- JSON extraction;
- lateral joins;
- wildcard selection;
- Python models;
- adapter-specific syntax;
- user-defined functions;
- stored procedures.

The safe response is not to guess.

Use one of four outcomes:

```text
Resolved automatically
Resolved by approved rule
Resolved by explicit override
Unresolved — review required
```

An unresolved column should block promotion when it may expose sensitive data.

## Recommended implementation architecture

A practical implementation can consist of:

### 1. Metadata registry

Stores approved governance values and target overrides.

This can remain in dbt YAML for code-adjacent technical metadata, with references to external policy or glossary systems.

### 2. Lineage extractor

Reads:

- dbt resources and dependencies;
- compiled SQL;
- column mappings from a parser or catalog;
- explicit mappings for unsupported cases.

### 3. Propagation engine

Applies:

- transformation classes;
- sensitivity ordering;
- category mappings;
- precedence rules;
- exception rules.

### 4. Diff generator

Produces stable YAML changes or a review report.

### 5. CI validator

Stops unresolved or invalid metadata from reaching governed layers.

### 6. Governance review

Approves exceptions, aggregate downgrades and new transformation rules.

The system can begin small. Pass-through, rename and simple normalization rules already remove large amounts of repetitive work.

## Common propagation anti-patterns

### Copy all model metadata to all columns

Model context is not a valid column classification.

### Copy any upstream PII flag to every target column

Constants and unrelated derived fields become over-classified.

### Drop PII after hashing

Pseudonymous identifiers may remain linkable and sensitive.

### Downgrade every aggregate automatically

Sparse groups and filters can still reveal individuals.

### Trust column names as lineage

A matching name does not prove a matching origin.

### Let detection overwrite approved metadata

A heuristic becomes stronger than a governance decision.

### Hide propagation inside a catalog sync

Changes are difficult to review, reproduce and audit.

### Ignore row-access dimensions

Column masking may work while users still receive unauthorized rows.

## Decision guide

| Transformation | Default propagation decision |
| --- | --- |
| Direct pass-through | Copy approved metadata |
| Rename | Copy approved metadata |
| Cast, trim, case conversion | Retain classification |
| Combination of columns | Merge and review unless rule is approved |
| Hash or token | Retain sensitivity; consider derived PII category |
| Aggregate | Review before downgrade |
| Constant or technical field | Do not inherit |
| Unsupported or ambiguous expression | Mark unresolved |
| Explicit approved target override | Preserve override |
| Detection-only result | Store as proposal only |

## The central insight

> **PII metadata should follow the data through the warehouse, but every propagation result must be explainable by lineage, a controlled rule or an explicit approved override.**

The strongest pattern is:

```text
Column lineage
+ approved metadata
+ transformation rules
+ deterministic precedence
→ reviewable metadata diff
→ governance validation
→ approved downstream contract
```

Part 5, [Snowflake Masking Policies + Qlik Section Access](/playbooks/snowflake-masking-policies-qlik-section-access), converts that approved downstream contract into complementary warehouse and application controls.

## Sources and further documentation

- [dbt — manifest JSON](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [dbt — Column-level lineage](https://docs.getdbt.com/docs/explore/column-level-lineage)
- [dbt — meta configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [dbt — columns property](https://docs.getdbt.com/reference/resource-properties/columns)
- [dbt — about dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)

> **Feature status:** July 2026. Column-lineage capabilities vary between dbt products and external parsers. SQL parsing can be incomplete for ambiguous or unsupported transformations, so unresolved lineage must remain reviewable.
