---
title: Automatic RAW Generation using dbt Macros
description: How to discover landing tables, generate source-aligned RAW models and governed YAML with dbt macros, and keep the result reviewable, version-controlled and safe.
category: Data Governance
tags:
  - data-governance
  - dbt
  - dbt-macros
  - raw-layer
  - code-generation
  - metadata-driven
  - schema-drift
  - snowflake
  - analytics-engineering
  - pii
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 3
seriesTitle: End-to-End Data Governance
hero: images/playbooks/automatic-raw-generation-using-dbt-macros-hero.png
---

## RAW models are simple — maintaining hundreds of them is not

A source-aligned RAW model is usually not intellectually complex.

It often selects the columns of one landing table, preserves source values, keeps ingestion metadata and exposes the result under a controlled name.

The difficulty begins when the same work must be repeated for dozens or hundreds of tables:

- source declarations are copied manually;
- SQL models drift from the physical source schema;
- column lists become outdated;
- naming conventions are applied inconsistently;
- descriptions and governance metadata are incomplete;
- newly added columns reach downstream users without review;
- PII classification is maintained separately from the generated code;
- developers spend time recreating predictable scaffolding.

This is a suitable automation problem.

It is not, however, a reason to let a macro create arbitrary warehouse objects outside the dbt project.

> **The objective is not to remove code. The objective is to generate deterministic, reviewable and governed code from controlled metadata.**

Part 1, [End-to-End Governance Architecture](/playbooks/end-to-end-governance-architecture), defined the complete governance flow. Part 2, [Metadata-driven Governance with dbt meta](/playbooks/metadata-driven-governance-with-dbt-meta), established dbt metadata as a technical governance contract.

This part applies that contract to the repetitive beginning of the transformation layer: the source-aligned RAW models.

## The boundary: ingestion happens before RAW generation

dbt transforms data that already exists in the connected platform.

An ingestion service, replication process, pipeline, file loader or platform-native mechanism first creates the landing objects.

Examples include:

```text
Source system
→ Fivetran / Airbyte / Data Factory / custom ingestion
→ Landing table
→ dbt RAW model
```

The generation process begins only after the landing relation exists and can be inspected.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/automatic-raw-generation-using-dbt-macros-img1-en.png"
        alt="Boundary between ingestion and automatic RAW generation: source systems are loaded into landing tables before dbt discovers schemas and generates source declarations, RAW models and governance YAML"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ingestion creates the landing objects. The generator reads their structure and produces governed dbt artifacts. dbt remains responsible for transformation, documentation, lineage and controlled deployment — not source extraction.
    </figcaption>
</figure>

This separation matters because it keeps responsibilities explicit:

| Responsibility | Owner |
| --- | --- |
| Extract and load source data | Ingestion process |
| Preserve source delivery metadata | Landing layer |
| Discover relations and columns | Generator |
| Create dbt source declarations | Generator plus review |
| Create source-aligned RAW SQL | Generator plus review |
| Add governance metadata | Approved metadata plus generator |
| Build and test RAW models | dbt |
| Approve schema changes and classifications | Data owner / steward / engineering |

## What should be generated?

A useful RAW generator produces three related artifact types.

### 1. Source declarations

The generator can create or update:

```text
models/raw/crm/_crm__sources.yml
```

This file declares the landing database, schema, tables, columns, freshness configuration and source-level metadata.

### 2. RAW model SQL

The generator can create one controlled model per landing table:

```text
models/raw/crm/raw_crm_customer.sql
models/raw/crm/raw_crm_order.sql
models/raw/crm/raw_crm_contact.sql
```

Each model remains source-aligned and uses an explicit, deterministic column list.

### 3. RAW model properties

The generator can create:

```text
models/raw/crm/_crm__raw_models.yml
```

This file contains model descriptions, tags, ownership, domain, generation metadata and column-level governance attributes.

The three artifacts must describe the same object set. Generating only SQL while leaving YAML manual creates a new form of drift.

## The three implementation patterns

There are three common ways to automate this work. They are not equally strong.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/automatic-raw-generation-using-dbt-macros-img2-en.png"
        alt="Comparison of three RAW automation patterns: macro-driven model templates, code generation into version-controlled files and direct DDL execution outside the dbt model graph"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Macro-driven models and generated files remain visible in the dbt project. Direct DDL generation can create objects quickly, but weakens lineage, selection, testing and deployment control when it bypasses normal dbt resources.
    </figcaption>
</figure>

### Pattern A — A small model calls a reusable RAW macro

Each model file contains only a macro call:

```sql
{{ raw_select(
    source_name = 'crm_landing',
    table_name = 'customer'
) }}
```

The macro discovers the columns and generates the final `select`.

Advantages:

- the model remains a normal dbt resource;
- `source()` creates explicit lineage;
- normal selection, testing, documentation and deployment still work;
- repeated SQL patterns are centralized;
- adapter logic can be reused.

Limitations:

- one small model file is still required per table;
- source YAML must already exist;
- schema discovery occurs while compiling with a live connection;
- a source schema change can alter compiled SQL without changing the model file;
- code review does not show the full generated column change unless compiled output or a schema-diff check is included.

This pattern is useful for controlled projects with moderate table counts.

### Pattern B — A macro renders files, and a wrapper writes them

A generation operation inspects the landing schema and renders SQL and YAML. A small script or CI task writes the returned output into the project.

```text
dbt run-operation
→ rendered SQL / YAML
→ wrapper writes files
→ Git diff
→ review
→ dbt parse / build
```

Advantages:

- the complete generated SQL and YAML are visible in Git;
- schema changes become explicit pull-request diffs;
- generated output is deterministic;
- CI can reject unexpected changes;
- manual metadata can be preserved through controlled merge rules;
- generated resources remain normal dbt models.

Limitations:

- a wrapper or generation tool is required because a normal dbt macro does not inherently manage project files;
- regeneration rules must be stable;
- merge behavior between generated and manually approved metadata must be designed;
- the team must decide when generation runs.

This is usually the strongest pattern for a larger governed project.

The `dbt-labs/codegen` package can provide useful building blocks for source and model scaffolding. A custom governance generator may extend that idea with organization-specific naming, metadata, classifications and review rules.

### Pattern C — A macro executes DDL and creates RAW objects directly

An operation can execute generated DDL against the warehouse.

This may be technically possible, but it should be an exception.

Risks include:

- objects are not represented as normal dbt models;
- lineage may be incomplete;
- node selection does not manage the objects;
- tests and documentation require separate handling;
- state comparison and CI become harder;
- deployment behavior depends on side effects;
- `run_query` can execute during compile-related workflows if it is not scoped carefully.

Direct DDL operations are suitable for administrative actions or tightly controlled platform automation. They are normally weaker than generated dbt resources for a governed RAW layer.

## A dbt-native RAW selection macro

A minimal macro can enumerate the columns of a declared source relation.

```sql
{% macro raw_select(source_name, table_name) %}

    {% set relation = source(source_name, table_name) %}

    {% if execute %}
        {% set columns = adapter.get_columns_in_relation(relation) %}
    {% else %}
        {% set columns = [] %}
    {% endif %}

    select
    {% if columns | length > 0 %}
        {% for column in columns %}
            {{ adapter.quote(column.name) }}{% if not loop.last %},{% endif %}
        {% endfor %}
    {% else %}
        *
    {% endif %}
    from {{ relation }}

{% endmacro %}
```

A model then becomes:

```sql
{{ config(
    materialized = 'view',
    tags = ['raw', 'generated', 'crm']
) }}

{{ raw_select(
    source_name = 'crm_landing',
    table_name = 'customer'
) }}
```

Several details are deliberate.

### Use `source()` rather than a hard-coded relation

`source()` connects the model to a declared source node. This creates explicit dependency and lineage.

### Use adapter methods where practical

`adapter.get_columns_in_relation()` provides a dbt abstraction over platform-specific metadata access.

For more specialized discovery, a macro can query `information_schema` through `run_query`, but then platform dialect, permissions and execution context must be handled explicitly.

### Protect parse-only execution

During parsing, `execute` may be false. The fallback keeps the Jinja render valid without requiring a live metadata query.

### Prefer explicit columns in compiled or generated SQL

An explicit list makes column order, quoting and schema changes visible.

A permanent `select *` is convenient, but it silently exposes every new source column. That is especially dangerous when a new PII column appears.

## Example: CRM landing source

Assume the ingestion process creates:

```text
LANDING_DB.CRM.CUSTOMER
```

with the following columns:

```text
CUSTOMER_ID
CUSTOMER_NAME
EMAIL
PHONE
COUNTRY_CODE
UPDATED_AT
_FIVETRAN_SYNCED
_FIVETRAN_DELETED
```

The source declaration may be generated as:

```yaml
version: 2

sources:
  - name: crm_landing
    database: LANDING_DB
    schema: CRM

    config:
      meta:
        source_system: crm
        owner: data_platform

    tables:
      - name: customer
        description: Landing table replicated from the CRM system.

        config:
          loaded_at_field: _FIVETRAN_SYNCED
          freshness:
            warn_after:
              count: 2
              period: hour
            error_after:
              count: 6
              period: hour
          meta:
            ingestion_method: fivetran
            raw_generation: enabled

        columns:
          - name: CUSTOMER_ID
            description: Source customer identifier.

          - name: EMAIL
            description: Customer email address.
            config:
              meta:
                pii: true
                pii_category: email
                sensitivity: confidential
                classification_status: approved
```

The RAW model properties may be generated as:

```yaml
version: 2

models:
  - name: raw_crm_customer
    description: Source-aligned RAW model for the CRM customer table.

    config:
      materialized: view
      tags:
        - raw
        - generated
        - crm
      meta:
        generated: true
        generation_rule: raw_v1
        source_system: crm
        owner: data_platform
        domain: customer

    columns:
      - name: CUSTOMER_ID
        description: Source customer identifier.
        data_tests:
          - not_null
        config:
          meta:
            pii: false
            classification_status: approved

      - name: EMAIL
        description: Customer email address.
        config:
          meta:
            pii: true
            pii_category: email
            sensitivity: confidential
            masking_policy: email_mask
            classification_status: approved

      - name: PHONE
        description: Customer phone number.
        config:
          meta:
            pii: true
            pii_category: phone
            sensitivity: confidential
            masking_policy: phone_mask
            classification_status: approved
```

The SQL remains intentionally simple:

```sql
{{ config(
    materialized = 'view',
    tags = ['raw', 'generated', 'crm'],
    meta = {
        'generated': true,
        'generation_rule': 'raw_v1',
        'source_system': 'crm',
        'owner': 'data_platform',
        'domain': 'customer'
    }
) }}

{{ raw_select(
    source_name = 'crm_landing',
    table_name = 'customer'
) }}
```

The RAW model does not standardize country codes, merge customers or calculate business attributes. Those responsibilities belong to later layers.

## The generator needs more than Information Schema

The physical schema can answer questions such as:

- Which tables exist?
- Which columns exist?
- What are their physical data types?
- What is their ordinal position?
- Are identifiers quoted?
- Has the schema changed?

It cannot reliably answer:

- Is a column PII?
- What business domain owns it?
- Which retention policy applies?
- Which masking policy is approved?
- Is a new column allowed for downstream use?
- Is a technical-looking field actually confidential?
- Which description is meaningful to the business?

A governed generator therefore combines multiple inputs.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/automatic-raw-generation-using-dbt-macros-img3-en.png"
        alt="Generation pipeline combining physical warehouse metadata, approved governance metadata and generation conventions to create dbt source YAML, RAW model SQL and model properties YAML"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Physical metadata describes what exists. Governance metadata describes how it must be handled. Generation conventions determine how both are rendered into reproducible dbt resources.
    </figcaption>
</figure>

A practical precedence order is:

```text
Approved governance metadata
→ existing manual override
→ source-system metadata
→ generator default
```

For a newly discovered column, the generator should not invent an approved classification.

A safe default is:

```yaml
config:
  meta:
    classification_status: unreviewed
    downstream_publication: blocked
```

This creates a controlled exception that requires review.

## Deterministic generation is essential

Running the generator twice against the same inputs should produce identical files.

That requires stable rules for:

- file names;
- model names;
- source names;
- column order;
- quoting;
- capitalization;
- YAML key order;
- whitespace;
- descriptions;
- default tags;
- generated metadata;
- comments and headers.

Non-deterministic output creates noisy Git diffs and weakens trust in automation.

A generated file can include a controlled header:

```text
Generated by: raw_generator
Generation rule: raw_v1
Source relation: LANDING_DB.CRM.CUSTOMER
Do not edit generated sections manually.
Manual governance overrides are stored in: governance/crm.yml
```

The generator should either own the complete file or clearly separate generated and manually maintained sections. Hidden merge behavior is difficult to operate.

## Schema drift must become a review workflow

Automatic generation is most valuable when a source changes.

A safe schema-drift process is:

```flow linear vertical
Inspect landing schema
Compare with committed definition
Classify the change
Generate proposed SQL and YAML
Mark new columns as unreviewed
Run parse, compile and tests
Create pull request
Approve or reject
Deploy
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/automatic-raw-generation-using-dbt-macros-img4-en.png"
        alt="Controlled schema drift workflow from landing schema comparison through classification, generated pull request, governance review, dbt validation and deployment"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Schema drift should create a visible change proposal, not an invisible production change. New columns can be discovered automatically while publication and classification remain controlled decisions.
    </figcaption>
</figure>

Useful change categories include:

| Change | Typical action |
| --- | --- |
| New table | Generate disabled or draft resources until approved |
| New column | Add as unreviewed; block downstream propagation if required |
| Removed column | Fail CI when downstream dependencies exist |
| Data-type change | Require compatibility review and targeted tests |
| Renamed column | Treat as remove plus add unless an explicit mapping exists |
| Column order change | Usually regenerate without semantic impact |
| New PII classification | Propagate security changes before wider publication |
| Source relation missing | Fail generation or mark source inactive according to policy |

The generator must not silently delete manually maintained governance decisions because a physical source column disappeared temporarily.

## PII detection can assist — it cannot approve

Column names can provide useful hints:

```text
EMAIL
PHONE
IBAN
BIRTH_DATE
SOCIAL_SECURITY_NUMBER
```

Pattern matching, catalog classifications or ML-based discovery can propose metadata.

The output should remain a proposal:

```yaml
config:
  meta:
    pii_candidate: true
    suggested_pii_category: email
    classification_status: unreviewed
```

The approved value should be distinct:

```yaml
config:
  meta:
    pii: true
    pii_category: email
    classification_status: approved
    approved_by: data_steward_customer
```

This distinction prevents the automation from presenting a naming heuristic as an authoritative governance decision.

## Recommended project structure

A scalable structure may look like:

```text
macros/
  raw/
    raw_select.sql
    discover_relations.sql
    discover_columns.sql
    render_source_yaml.sql
    render_raw_model.sql
    render_model_yaml.sql

models/
  raw/
    crm/
      _crm__sources.yml
      _crm__raw_models.yml
      raw_crm_customer.sql
      raw_crm_contact.sql
    erp/
      _erp__sources.yml
      _erp__raw_models.yml
      raw_erp_customer.sql
      raw_erp_sales_order.sql

governance/
  crm.yml
  erp.yml

scripts/
  generate_raw.py

tests/
  generator/
    expected_output/
```

The `governance` files hold approved classifications and manual overrides. Generated files remain reproducible outputs.

## CI/CD checks for generated RAW resources

The generator should be part of the delivery process, not an undocumented developer command.

A practical CI sequence is:

```text
Install dbt packages
Validate generator configuration
Connect with read-only metadata role
Discover landing schema
Regenerate RAW artifacts
Fail when Git diff is not empty
Run dbt parse
Run dbt compile
Run targeted source and RAW tests
Validate unreviewed classifications
Validate naming and metadata requirements
Publish pull-request diff
```

The “fail when Git diff is not empty” check detects when committed generated files no longer match the source schema or generator rules.

Separate credentials are advisable:

| Activity | Permission |
| --- | --- |
| Discover metadata | Read metadata and source definitions |
| Build development RAW models | Create objects in development schema |
| CI parse / render | No production write permission |
| Production deployment | Controlled deployment role |
| Governance approval | Repository approval, not warehouse admin by default |

## Common anti-patterns

### Letting `select *` expose every new column

This turns source schema drift into an uncontrolled data-publication mechanism.

### Generating DDL outside the dbt DAG

The objects exist, but dbt cannot manage them as first-class resources.

### Overwriting approved PII metadata

Physical schema discovery must not replace governance decisions.

### Treating generated code as unreviewable

Generated code can still create security, cost and compatibility problems. It requires tests and review.

### Mixing transformation into the RAW generator

Renaming business concepts, standardizing values and applying KPIs makes the RAW pattern source-specific and semantically overloaded.

### Running side-effecting `run_query` logic during normal compilation

Metadata reads are one thing. DDL, DML or destructive actions require explicit command scoping and controlled operations.

### Regenerating files with unstable formatting

Noisy output hides meaningful changes.

### Making the generator the only source of truth

The source schema describes structure. Governance metadata and approved exceptions remain separate authoritative inputs.

## Decision guide

| Situation | Recommended pattern |
| --- | --- |
| Few sources and low change rate | Manual models with shared conventions may be enough |
| Moderate number of repetitive RAW models | Macro-driven model templates |
| Many sources with frequent schema drift | File generation plus Git review |
| Need initial scaffolding only | Use code-generation package or one-time generator |
| Need governed metadata merge | Custom generator with approved override registry |
| Need administrative warehouse DDL | Explicit `run-operation`, separated from model generation |
| Need fully dynamic objects with no committed code | Reconsider whether dbt models are the correct abstraction |

Automation is justified when it reduces repeated work without reducing control.

## The central conclusion

> **Generate the repetitive RAW scaffolding automatically, but keep every resulting model, source declaration and governance decision visible, testable and reviewable.**

dbt macros can inspect relations, generate SQL and render reusable patterns.

`run-operation` can invoke generation logic.

Adapter methods and `run_query` can read warehouse metadata.

A wrapper or code-generation tool can turn rendered output into version-controlled project files.

The strongest operating model is therefore:

```flowchart
Landing Schema
Approved Governance Metadata
Generation Rules
Generated SQL and YAML
Git Review
dbt Build
Governed RAW Layer
```

Part 4, [Propagating PII Metadata across Data Warehouses](/playbooks/propagating-pii-metadata-across-data-warehouses), continues from this point. Once the RAW layer contains approved metadata, the next challenge is preserving and resolving that metadata through Conform, Core and Analytics models.

## Sources and further reading

- [dbt — Jinja and macros](https://docs.getdbt.com/docs/build/jinja-macros)
- [dbt — adapter object](https://docs.getdbt.com/reference/dbt-jinja-functions/adapter)
- [dbt — run_query](https://docs.getdbt.com/reference/dbt-jinja-functions/run_query)
- [dbt — run-operation](https://docs.getdbt.com/reference/commands/run-operation)
- [dbt — Hooks and operations](https://docs.getdbt.com/docs/build/hooks-operations)
- [dbt — meta configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [dbt — columns properties](https://docs.getdbt.com/reference/resource-properties/columns)
- [dbt — source freshness](https://docs.getdbt.com/reference/resource-properties/freshness)
- [dbt Package Hub — codegen](https://hub.getdbt.com/dbt-labs/codegen/latest/)

> **Feature status:** July 2026. dbt syntax and execution behavior can differ between dbt Core, the Fusion engine and the dbt platform. Verify the documentation for the engine and version used by the project.
