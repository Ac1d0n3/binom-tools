---
title: Metadata-driven Governance with dbt meta
description: How to turn ownership, classification, sensitivity, retention and protection decisions into a versioned and validated governance contract using dbt meta.
category: Data Governance
tags:
  - data-governance
  - dbt
  - dbt-meta
  - metadata-driven
  - pii
  - classification
  - manifest-json
  - ci
  - data-contract
  - analytics-engineering
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 2
seriesTitle: End-to-End Data Governance
hero: images/playbooks/metadata-driven-governance-with-dbt-meta-hero.png
---

## Governance decisions need a machine-readable contract

Part 1, [End-to-End Governance Architecture](/playbooks/end-to-end-governance-architecture), defined governance as a connected system:

```text
Policy
→ metadata
→ code
→ enforcement
→ evidence
```

The architecture now needs a technical contract that can travel through development, review and automation.

dbt `meta` provides a practical place for that contract.

The `meta` configuration accepts custom key-value pairs on dbt resources. The values are included in `manifest.json` and can appear in generated documentation.

That makes `meta` useful for attributes such as:

- ownership;
- domain;
- PII classification;
- sensitivity;
- retention;
- protection policy;
- quality tier;
- review status;
- generation state.

But an unrestricted dictionary is not governance by itself.

> **dbt `meta` becomes a governance contract only when the vocabulary, scope, validation, approval and downstream use are explicitly defined.**

## `meta` stores context — it does not enforce every rule

A metadata entry such as:

```yaml
config:
  meta:
    sensitivity: confidential
    masking_policy: email_mask
```

does not automatically attach a masking policy in the warehouse.

It does not automatically prevent an unauthorized deployment.

It does not prove that a steward approved the classification.

It does not guarantee that a derived downstream column has the same meaning.

The value of the contract comes from the systems around it:

```text
dbt YAML or SQL config
→ code review
→ dbt parse
→ manifest validation
→ generation or propagation logic
→ warehouse implementation
→ runtime verification
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-driven-governance-with-dbt-meta-img1-en.png"
        alt="dbt meta used as a governance contract connecting a controlled governance vocabulary with resource metadata, manifest artifacts, validation, automation and runtime controls"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        dbt stores the technical contract. Review, validation and downstream automation turn that contract into operational governance.
    </figcaption>
</figure>

## Define the vocabulary before adding keys

A common mistake is to let every developer create new keys whenever a new requirement appears.

The result is technically valid YAML but semantically inconsistent metadata:

```yaml
pii: yes
personal_data: true
contains_pii: Y
data_class: personal
sensitive: email
```

All five entries may describe a similar concept. Automation cannot safely treat them as equivalent.

A controlled vocabulary should define:

- the exact key;
- its purpose;
- allowed values;
- scope;
- owner;
- default;
- approval rule;
- propagation behavior;
- enforcement mapping.

A compact governance dictionary might begin with:

| Key | Scope | Example | Purpose |
| --- | --- | --- | --- |
| `domain` | source, model | `customer` | Business domain |
| `data_owner` | source, model | `sales_operations` | Business accountability |
| `steward` | source, model, column | `data_governance` | Metadata stewardship |
| `technical_owner` | model | `data_platform` | Engineering responsibility |
| `pii` | column | `true` | Personal-data indicator |
| `pii_category` | column | `email` | Controlled PII category |
| `sensitivity` | model, column | `confidential` | Handling classification |
| `classification_status` | model, column | `approved` | Review state |
| `masking_policy` | column | `email_mask` | Approved protection mapping |
| `retention_class` | source, model | `operational_7y` | Lifecycle rule |
| `quality_tier` | model | `critical` | Validation priority |
| `generated` | model, column | `true` | Generation provenance |

The dictionary should be versioned like code.

## Use explicit review states

A boolean such as `pii: true` describes a classification, but not its approval state.

A detected or newly generated column should not look equivalent to a steward-approved classification.

Use an explicit workflow:

```text
unreviewed
→ proposed
→ approved
→ rejected
→ deprecated
```

The exact states may differ, but the distinction must remain visible.

Example:

```yaml
columns:
  - name: email
    config:
      meta:
        pii: true
        pii_category: email
        sensitivity: confidential
        classification_status: approved

  - name: contact_channel
    config:
      meta:
        pii: null
        classification_status: unreviewed
```

`unreviewed` should normally block promotion into governed downstream use when the field is new or potentially sensitive.

## Put metadata at the correct scope

dbt supports metadata on several resource types and scopes.

For this series, the most important locations are:

- source;
- source table;
- model;
- column.

### Source-level metadata

Source-level metadata describes the source system or ingestion context.

```yaml
version: 2

sources:
  - name: crm_landing
    database: LANDING
    schema: CRM

    config:
      meta:
        source_system: crm
        ingestion_owner: data_platform
        data_owner: sales_operations
        retention_class: source_delivery_30d
```

### Source-table metadata

Table-level source metadata describes one delivered object.

```yaml
    tables:
      - name: customer
        config:
          meta:
            domain: customer
            source_of_record: true
            classification_status: approved
```

### Model-level metadata

Model metadata describes the governed data product or transformation node.

```yaml
models:
  - name: raw_crm_customer
    description: Source-aligned customer data.

    config:
      meta:
        domain: customer
        data_owner: sales_operations
        steward: data_governance
        technical_owner: data_platform
        quality_tier: critical
        generated: true
```

### Column-level metadata

Column metadata describes the meaning and handling of an individual field.

```yaml
    columns:
      - name: email
        description: Customer email address.

        config:
          meta:
            pii: true
            pii_category: email
            sensitivity: confidential
            classification_status: approved
            masking_policy: email_mask
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-driven-governance-with-dbt-meta-img2-en.png"
        alt="Metadata hierarchy across source, source table, model and column scopes with example governance keys and a warning that column metadata does not inherit model metadata"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Put each decision at the scope where it applies. Column-level governance remains explicit and must not be assumed from model-level metadata.
    </figcaption>
</figure>

## Column metadata does not inherit model metadata

This point is important for governance design.

Columns are not independent dbt resources, and their `meta` values do not inherit the `meta` values of the parent resource.

A model may be classified as confidential:

```yaml
config:
  meta:
    sensitivity: confidential
```

That does not mean every column automatically contains:

```yaml
config:
  meta:
    sensitivity: confidential
```

Your validation or propagation code may define an organizational fallback, but that is your rule — not implicit dbt inheritance.

This distinction prevents hidden assumptions.

A useful pattern is:

```text
Model metadata
→ default context for review

Column metadata
→ explicit classification and protection decision
```

## Separate ownership, classification, protection and lifecycle

A flat list of keys becomes difficult to govern when unrelated concepts are mixed together.

Group the metadata logically.

### Ownership

```yaml
data_owner: sales_operations
steward: data_governance
technical_owner: data_platform
domain: customer
```

### Classification

```yaml
pii: true
pii_category: email
sensitivity: confidential
classification_status: approved
```

### Protection

```yaml
masking_policy: email_mask
row_access_domain: legal_entity
allowed_usage:
  - customer_service
  - finance_reporting
```

### Lifecycle

```yaml
retention_class: operational_7y
deletion_rule: source_contract
source_of_record: true
```

### Operations

```yaml
quality_tier: critical
criticality: tier_1
review_date: 2027-01-31
generated: true
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-driven-governance-with-dbt-meta-img3-en.png"
        alt="Practical dbt governance metadata schema grouped into ownership, classification, protection, lifecycle and operational metadata"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A controlled schema makes metadata understandable to humans and predictable for automation.
    </figcaption>
</figure>

## A complete model example

A practical properties file may look like this:

```yaml
version: 2

models:
  - name: raw_crm_customer
    description: Source-aligned customer data from the CRM landing table.

    config:
      materialized: view
      tags:
        - raw
        - customer
      meta:
        domain: customer
        data_owner: sales_operations
        steward: data_governance
        technical_owner: data_platform
        retention_class: operational_7y
        quality_tier: critical
        classification_status: approved
        generated: true

    columns:
      - name: customer_id
        description: Source customer identifier.
        data_tests:
          - not_null
          - unique
        config:
          meta:
            pii: false
            sensitivity: internal
            classification_status: approved

      - name: email
        description: Customer email address.
        config:
          meta:
            pii: true
            pii_category: email
            sensitivity: confidential
            classification_status: approved
            masking_policy: email_mask

      - name: phone
        description: Customer phone number.
        config:
          meta:
            pii: true
            pii_category: phone
            sensitivity: confidential
            classification_status: proposed
            masking_policy: phone_mask

      - name: preferred_contact_time
        description: Newly delivered source field.
        config:
          meta:
            classification_status: unreviewed
```

This example deliberately separates:

- model ownership;
- column classification;
- protection mapping;
- review status;
- quality tests.

## Put stable policy references in `meta`, not runtime secrets

Good `meta` values are:

- deterministic;
- reviewable;
- stable enough to version;
- meaningful outside one execution;
- safe to appear in artifacts and documentation.

Suitable examples:

```yaml
domain: customer
sensitivity: confidential
masking_policy: email_mask
retention_class: operational_7y
```

Do not store:

- passwords;
- tokens;
- secret keys;
- personal credentials;
- current user entitlements;
- large access-control lists;
- volatile runtime values;
- unrestricted free-form legal assessments.

The metadata should reference a policy or entitlement domain, not embed the entire runtime security system.

## The manifest makes the contract consumable

dbt includes resource metadata in `manifest.json`.

That makes the manifest a useful integration artifact for:

- CI validation;
- documentation;
- catalogs;
- impact analysis;
- code generation;
- metadata propagation;
- warehouse policy mapping;
- governance reporting.

The manifest version is tied to the dbt artifact schema. Consumers should use the correct schema for the dbt version in use rather than assuming that the JSON structure never changes.

A simplified validation process is:

```text
dbt parse
→ read target/manifest.json
→ inspect model and column metadata
→ apply organizational rules
→ fail or pass the pull request
```

## Validate the contract before build

YAML syntax validation is not enough.

The following file is valid YAML:

```yaml
config:
  meta:
    pii: perhaps
    sensitivity: very_secret
    classification_status: done
```

It is not valid governance metadata if the approved values are:

```text
pii: true | false | null
sensitivity: public | internal | confidential | restricted
classification_status: unreviewed | proposed | approved | rejected | deprecated
```

A CI validator should check at least:

- required ownership on governed models;
- allowed enum values;
- PII columns have a category;
- approved PII columns have an approved protection mapping;
- unreviewed columns cannot enter protected downstream layers;
- critical models define a technical owner;
- retention classes exist in the policy catalog;
- generated metadata does not overwrite approved manual decisions.

A simplified Python-style validator could inspect the manifest:

```python
for node in manifest["nodes"].values():
    if node.get("resource_type") != "model":
        continue

    model_meta = node.get("config", {}).get("meta", {})

    require(model_meta, "data_owner")
    require(model_meta, "technical_owner")
    validate_enum(model_meta, "quality_tier", QUALITY_TIERS)

    for column_name, column in node.get("columns", {}).items():
        column_meta = column.get("meta", {})

        validate_enum(
            column_meta,
            "classification_status",
            CLASSIFICATION_STATES,
        )

        if column_meta.get("pii") is True:
            require(column_meta, "pii_category")

            if column_meta.get("classification_status") == "approved":
                require(column_meta, "masking_policy")
```

The exact manifest structure must match the dbt artifact schema used by the project.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-driven-governance-with-dbt-meta-img4-en.png"
        alt="Pull-request validation workflow from dbt YAML or SQL changes through dbt parse, manifest inspection, governance rules, review and controlled build"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadata becomes a contract when invalid or incomplete decisions stop the delivery process before deployment.
    </figcaption>
</figure>

## Automated validation does not replace approval

Automation can verify:

- a value exists;
- a value belongs to an allowed set;
- related fields are consistent;
- an approved PII field references a masking policy;
- a new column remains unreviewed;
- a model has an owner.

Automation cannot reliably decide:

- whether a field is legally personal data in the current context;
- whether a business purpose is permitted;
- whether an aggregation is sufficiently anonymous;
- whether a retention exception is justified;
- whether a downstream use is acceptable.

The workflow therefore needs both:

```text
Automated validation
+
human governance approval
```

A green CI result proves structural compliance with the contract. It does not prove that every classification is correct.

## Keep the business glossary connected, not duplicated

dbt `meta` is suitable for technical governance attributes close to code.

It should not become the only enterprise glossary.

A practical split is:

| Information | Preferred authoritative location |
| --- | --- |
| Technical model and column metadata | dbt |
| Approved glossary definition | Catalog or glossary system |
| Policy text | Policy repository |
| Runtime roles and entitlements | Identity and access systems |
| Warehouse policy implementation | Warehouse |
| Application reduction mapping | Qlik security data |
| Cross-system evidence | Governance reporting or audit store |

dbt can store references:

```yaml
glossary_term: customer_email
policy_id: privacy_pii_001
retention_class: operational_7y
```

This keeps code connected to governance without duplicating full policy documents.

## Design for generation and propagation

The contract should support the next two parts of the series.

### Part 3: controlled RAW generation

A generator can combine:

```text
Physical warehouse metadata
+
approved governance metadata
+
generation conventions
```

to create source YAML, RAW SQL and model properties.

New columns should be generated with:

```yaml
classification_status: unreviewed
```

### Part 4: metadata propagation

A propagation process can inspect lineage and transformation semantics to decide whether metadata should be:

- copied;
- merged;
- downgraded;
- upgraded;
- removed;
- sent for review.

That process needs stable and explicit metadata keys. Free-form notes are not enough.

## Common metadata anti-patterns

### One boolean for all sensitivity

`pii: true` does not define category, sensitivity, status or protection.

### Free-form values

`high`, `very high`, `secret`, `sensitive` and `restricted` become impossible to automate consistently.

### No approval state

Detected and approved classifications become indistinguishable.

### Model-only metadata

Sensitive columns remain technically ambiguous.

### Assuming inheritance

Column behavior depends on undocumented fallback logic.

### Embedding runtime access lists

Versioned project files become a second identity-management system.

### Generating over approved metadata

A schema refresh silently destroys steward decisions.

### Metadata without enforcement mapping

The platform documents risk but does not reduce it.

## The central insight

> **dbt `meta` is the versioned technical contract between governance decisions and platform automation.**

The contract must remain:

- controlled;
- explicit;
- validated;
- reviewable;
- machine-readable;
- connected to authoritative policies;
- separate from runtime secrets;
- ready for generation and propagation.

Part 3, [Automatic RAW Generation using dbt Macros](/playbooks/automatic-raw-generation-using-dbt-macros), uses this contract to generate repetitive RAW scaffolding without losing Git review, lineage or governance control.

## Sources and further documentation

- [dbt — meta configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [dbt — columns property](https://docs.getdbt.com/reference/resource-properties/columns)
- [dbt — source configurations](https://docs.getdbt.com/reference/source-configs)
- [dbt — manifest JSON](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [dbt — about dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)
- [dbt — docs commands](https://docs.getdbt.com/reference/commands/cmd-docs)
