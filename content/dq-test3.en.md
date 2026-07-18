---
title: Data Quality with dbt — From Tests and Failure Tables to Historical Monitoring
description: How dbt Generic and Custom Data Tests are defined in YAML, how failing records are persisted with store_failures, and how an additional history table operationalizes the results for Qlik, Power BI and Data Governance.
category: Data Quality
tags:
  - data-quality
  - operational-data-quality
  - dbt
  - dbt-tests
  - generic-tests
  - custom-tests
  - store-failures
  - run-results
  - data-quality-history
  - qlik-sense
  - power-bi
  - data-governance
  - automation
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 3
seriesTitle: Operational Data Quality
hero: images/playbooks/dq-test3-hero.png
---

## dbt tests data — historical quality management must be added

dbt uses a very clear testing principle:

> **A Data Test is a SQL query that returns the records that violate an expectation.**

When the query returns no rows, the test passes. When it returns failing rows, dbt warns or marks the test as failed.

This makes dbt well suited to connect quality rules directly to models, sources, seeds and snapshots.

However, dbt does not automatically provide every capability required for operational data quality monitoring:

- Standard output focuses on the current execution.
- `store_failures` persists failing records but replaces the previous failure relation for the same test.
- Failure tables from different tests do not necessarily share the same columns.
- A passed test has no failing records but still needs a history row.
- A current failure count alone does not create a complete time series.
- `Rows Tested` is not automatically exposed as a standardized metric by a normal dbt Data Test.
- Owner, Data Product, Severity and business Rule ID must be enriched from governance metadata.

The complete pattern therefore contains three separate layers:

```flowchart
dbt Data Test
Failure Table
DQ History Table
Qlik / Power BI
Ownership and Remediation
```

> **The failure table explains which records are currently wrong. The history table shows how quality changes across many executions.**

## Generic Tests, Custom Generic Tests and Singular Tests

dbt primarily distinguishes between reusable Generic Tests and one-off Singular Tests.

### Built-in Generic Tests

dbt provides four reusable Generic Tests:

| Test | Meaning |
| --- | --- |
| **not_null** | A column must not contain null values |
| **unique** | A column or expression must be unique |
| **accepted_values** | Values must be included in a defined list |
| **relationships** | Values must exist in a referenced relation |

These rules cover many technical baseline checks.

```yaml
models:
  - name: dim_customer
    columns:
      - name: customer_id
        data_tests:
          - not_null
          - unique

      - name: customer_status
        data_tests:
          - accepted_values:
              arguments:
                values:
                  - active
                  - inactive
                  - blocked

      - name: country_id
        data_tests:
          - relationships:
              arguments:
                to: ref('dim_country')
                field: country_id
```

The older `tests:` property remains supported for backward compatibility. `data_tests:` is clearer for new projects because dbt now also supports unit tests.

### Custom Generic Tests

A Custom Generic Test packages reusable organization-specific logic.

Example: A value must remain within an accepted range.

```sql
{% test value_between(
    model,
    column_name,
    min_value,
    max_value
) %}

    select
        *
    from {{ model }}
    where {{ column_name }} < {{ min_value }}
       or {{ column_name }} > {{ max_value }}

{% endtest %}
```

The rule is then applied in YAML:

```yaml
models:
  - name: fact_sales
    columns:
      - name: amount
        data_tests:
          - value_between:
              name: dq_102_sales_amount_range
              arguments:
                min_value: 0
                max_value: 1000000
              config:
                severity: error
                store_failures: true
```

Custom Generic Tests are appropriate for:

- value ranges
- conditional required fields
- valid time intervals
- email or identifier formats
- multi-column comparisons
- uniqueness across column combinations
- business thresholds
- tenant-dependent rules
- checks limited to active records

The test name should be stable and traceable. A name such as `dq_102_sales_amount_range` is easier to map to a governance rule than an automatically constructed technical name.

### Singular Tests

Singular Tests are standalone SQL files that return failing records.

Example:

```sql
-- tests/assert_order_total_matches_components.sql

select
    order_id,
    order_total,
    item_total,
    tax_amount
from {{ ref('fact_orders') }}
where order_total <> item_total + tax_amount
```

Singular Tests are useful for:

- complex rules across several models
- one-off business assertions
- reconciliations between fact and aggregate tables
- balance and control-total checks
- validations that cannot be parameterized cleanly

When similar Singular Tests are copied repeatedly, they should be converted into a Custom Generic Test or a generated macro.

## YAML becomes an executable rule definition

The YAML file connects the data model and the quality rule.

It can contain not only the test and its arguments, but also operational behavior:

```yaml
models:
  - name: fact_sales
    description: Governed sales transaction model

    columns:
      - name: order_id
        data_tests:
          - not_null:
              name: dq_101_order_id_required
              config:
                severity: error
                store_failures: true
                tags:
                  - data_quality
                  - critical

          - unique:
              name: dq_103_order_id_unique
              config:
                severity: error
                error_if: "> 0"
                warn_if: "> 0"
                store_failures: true

      - name: amount
        data_tests:
          - value_between:
              name: dq_102_sales_amount_range
              arguments:
                min_value: 0
                max_value: 1000000
              config:
                severity: error
                error_if: "> 100"
                warn_if: "> 0"
                store_failures: true
```

The YAML definition determines:

- which model is tested
- which column is affected
- which test logic applies
- which arguments are used
- how the test is named
- whether failures are stored
- when dbt warns
- when the execution fails
- which tags support selection and operations

### Rule ID and dbt Test Name

For a small project, the custom test name may also function as the Rule ID.

A governance framework benefits from separate identities:

| Identity | Purpose |
| --- | --- |
| **Rule ID** | stable business identity of the quality rule |
| **dbt Test Name** | readable and selectable name of the dbt implementation |
| **dbt Unique ID** | technical identity of the compiled dbt node |
| **Rule Version** | version of the business and technical definition |

This allows a rule to be reimplemented technically without losing its business history.

A generator can maintain the mapping centrally:

```text
DQ-102
→ dq_102_sales_amount_range
→ test.sales_project.dq_102_sales_amount_range...
→ Rule Version 3
```

## Severity and thresholds

A dbt Data Test does not need to stop the entire run for every failing row.

Relevant configurations include:

- `severity: error`
- `severity: warn`
- `error_if`
- `warn_if`

Example:

```yaml
data_tests:
  - unique:
      name: dq_103_order_id_unique
      config:
        severity: error
        error_if: "> 100"
        warn_if: "> 0"
```

The logic means:

- more than 100 failures → Error
- 1 to 100 failures → Warning
- 0 failures → Pass

This technical threshold must align with the business severity.

A critical rule may fail on a single violation. A less critical completeness rule may warn first and only block above an agreed threshold.

### `fail_calc` defines the failure count

By default, dbt counts the rows returned by the test query.

In a uniqueness test, one returned row may represent a duplicated key group. The actual number of affected records may be larger.

`fail_calc` can change the calculation:

```yaml
data_tests:
  - unique:
      config:
        fail_calc: >
          case
            when count(*) > 0 then sum(n_records)
            else 0
          end
```

The meaning of `Rows Failed` must therefore be documented:

- number of duplicate key groups
- number of all records in duplicate groups
- number of additional duplicates beyond the first occurrence

The meaning must not change silently for the same Rule ID.

## `store_failures` stores evidence, not history

With `store_failures: true`, dbt persists the failing records of a test in the target data platform.

```yaml
models:
  - name: dim_customer
    columns:
      - name: email
        data_tests:
          - not_null:
              name: dq_201_customer_email_required
              config:
                store_failures: true
```

By default, dbt uses an audit schema whose name typically ends in `_dbt_test__audit`.

The stored relation uses the test name or a configured alias.

This provides immediate operational value:

- failing records can be queried directly
- Data Engineers can investigate the root cause
- business keys can be identified
- technical failure patterns become visible
- downstream processes can access the current failure set

The critical limitation is:

> **Each execution replaces the previous failure relation for the same test.**

A later passing run also replaces the previous failures.

`store_failures` therefore answers:

> Which records violate the rule in the current test execution?

It does not answer:

> How many failures did the same rule have yesterday, last week or before the last correction?

## Failure Table and History Table have different grains

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test3-img1-en.png"
        alt="dbt Data Tests write current failing records to audit tables through store_failures while a separate append-oriented history table stores aggregated results per rule and execution for Qlik and Power BI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The dbt audit table is optimized for root-cause analysis. The additional history table stores every test execution and enables trends, KPIs and accountability.
    </figcaption>
</figure>

The two tables must not be confused.

### Failure Table

Grain:

> One row per currently failing record or failing group.

Typical content:

- business key
- tested value
- comparison value
- failure reason
- technical source columns
- additional evidence returned by the test

The structure may differ by test.

### DQ History Table

Grain:

> One row per Rule ID, dbt test and execution.

Recommended structure:

| Field | Source |
| --- | --- |
| **Run ID** | `invocation_id` or orchestrator |
| **Rule ID** | rule registry or generator |
| **Platform** | `dbt` plus target platform |
| **Data Product** | governance metadata |
| **Table** | dbt node or model |
| **Column** | test metadata |
| **Test Type** | test name or normalized type |
| **Status** | dbt Result status |
| **Rows Tested** | additional metric query |
| **Rows Failed** | dbt Result `failures` or controlled `fail_calc` |
| **Failure Rate** | calculated metric |
| **Executed At** | run timestamp |
| **Owner** | rule registry |
| **Severity** | governance and dbt configuration |
| **Execution Time** | dbt Result |
| **dbt Unique ID** | `run_results.json` and `manifest.json` |
| **Failure Relation** | persisted audit relation |
| **Message** | dbt Result message |

A passed test also creates a history row:

```text
Rows Failed = 0
Status = Passed
```

Without that row, monitoring would only see failures and could not calculate a correct Pass Rate or continuous time series.

## dbt does not automatically provide `Rows Tested`

A dbt Data Test primarily returns failing records and a failure count.

The total number of tested records is not automatically part of the normal result.

It must therefore be added for the result contract defined in Part 1.

### Option 1: Separate scope query

The History Generator knows the model, column and optional `where` filter and calculates:

```sql
select count(*) as rows_tested
from {{ ref('fact_sales') }}
where <same test scope>
```

Advantage:

- clear and general metric

Disadvantage:

- additional query and compute

### Option 2: Generated metric logic

The DG Macro Generator creates both a failure query and a standardized metric query.

```text
Failure Query
→ returns failing records

Metric Query
→ returns Rows Tested and Rows Failed
```

Advantages:

- consistent semantics
- improved optimization
- centralized control of metric definitions

### Option 3: Rule-specific metric

Not every rule validates records.

For a freshness rule:

```text
Rows Tested = 1
Rows Failed = 0 or 1
```

For a reconciliation rule, `Rows Tested` may represent compared groups rather than individual transactions.

The rule registry must therefore also describe the metric grain.

## Three approaches to history

### 1. Ingest dbt artifacts after every run

dbt creates `run_results.json` with information about executed nodes, including:

- `unique_id`
- status
- failure count
- execution time
- timing
- message
- stored failure relation when available

`manifest.json` adds metadata about models, tests, dependencies and configuration.

A downstream process can combine both artifacts:

```flowchart
dbt test
run_results.json
manifest.json
Artifact Loader
DQ_TEST_HISTORY
```

This is usually the most robust platform-neutral approach.

Advantages:

- clear separation between dbt execution and monitoring
- no additional inserts during the test run
- artifacts can be consolidated across several projects
- technical errors remain visible
- history logic can be versioned independently

Artifacts must be preserved for each invocation. Keeping only the latest local file in `target/` would again provide no history.

### 2. `on-run-end` hook

The dbt `on-run-end` context exposes Result objects for executed resources.

A simplified pattern:

```sql
{% macro persist_dq_history(results) %}

    {% if execute %}

        {% for result in results
            if result.node.resource_type == 'test' %}

            {% set rows_failed =
                result.failures
                if result.failures is not none
                else 0
            %}

            {% set insert_sql %}

                insert into governance.dq_test_history (
                    run_id,
                    dbt_unique_id,
                    test_name,
                    test_status,
                    rows_failed,
                    execution_time,
                    executed_at
                )
                values (
                    '{{ invocation_id }}',
                    '{{ result.node.unique_id }}',
                    '{{ result.node.name }}',
                    '{{ result.status }}',
                    {{ rows_failed }},
                    {{ result.execution_time }},
                    '{{ run_started_at }}'
                )

            {% endset %}

            {% do run_query(insert_sql) %}

        {% endfor %}

    {% endif %}

{% endmacro %}
```

Activation:

```yaml
on-run-end:
  - "{{ persist_dq_history(results) }}"
```

The example illustrates the concept only. A production implementation needs:

- adapter-aware quoting
- safe handling of messages and special characters
- dbt status mapping
- Rule ID and Owner enrichment
- hook error handling
- transaction and rollback behavior
- target schema versioning
- `Rows Tested` calculation
- duplicate protection for retries

### 3. Incremental History Model

An additional dbt execution reads normalized run metadata or a staging table and appends new results incrementally.

```sql
{{ config(
    materialized='incremental',
    unique_key=['run_id', 'dbt_unique_id']
) }}

select
    run_id,
    rule_id,
    dbt_unique_id,
    test_status,
    rows_tested,
    rows_failed,
    failure_rate,
    executed_at
from {{ ref('stg_dbt_test_results') }}

{% if is_incremental() %}

where executed_at >
    (
        select coalesce(max(executed_at), '1900-01-01')
        from {{ this }}
    )

{% endif %}
```

The model must not read only the current `store_failures` tables because passing tests no longer contain historical failures.

It needs a persistent input from:

- artifact loader
- on-run-end staging
- orchestrator API
- event or log export

## `invocation_id` as Run ID

dbt generates a UUID named `invocation_id` for every command.

It works well as the technical Run ID:

```text
dbt invocation
→ invocation_id
→ several models and tests
→ shared run group
```

A higher-level orchestrator may also provide a business batch identifier:

| Field | Example |
| --- | --- |
| **dbt Invocation ID** | one execution of `dbt build` or `dbt test` |
| **Pipeline Run ID** | complete flow across ingestion, dbt, export and reporting |
| **Business Batch ID** | business processing date or financial close |

The history table may retain all three identities.

## Normalize statuses

dbt and the cross-platform DQ model may use different status values.

A possible mapping:

| dbt status | Standard status |
| --- | --- |
| `pass` | Passed |
| `warn` | Warning |
| `fail` | Failed |
| `error` | Error |
| `skipped` | Skipped |

`warn` should not automatically be merged into `Failed`.

A warning means:

- the rule executed
- an agreed warning threshold was violated
- the error threshold was not exceeded
- execution may continue according to configuration

Qlik and Power BI should expose warnings separately.

## DG Tools as rule-driven automation

The DG Tools move work from repeated hand coding to a central rule definition.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test3-img2-en.png"
        alt="DG Tools generate reusable dbt test macros, YAML test definitions and an incremental DQ history model from a central rule definition, standardizing failure tables and test results for Qlik and Power BI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Define the rule once. Macro Generator, Rules Generator and History Generator produce reproducible dbt artifacts and a consistent historical result layer.
    </figcaption>
</figure>

The toolchain has three distinct responsibilities.

### DG Macro Generator

The DG Macro Generator creates reusable Custom Generic Tests.

Possible inputs:

- Test Type
- supported data types
- rule arguments
- failure condition
- optional scope
- failure calculation
- adapter-specific SQL
- standardized evidence columns

Possible outputs:

```text
tests/generic/value_between.sql
tests/generic/required_when.sql
tests/generic/valid_date_interval.sql
tests/generic/unique_combination.sql
```

The generator should enforce:

- consistent naming
- consistent arguments
- identical null handling
- controlled `fail_calc` semantics
- documented result structures
- reuse across projects
- optional adapter dispatch

### DQ Rules Generator

The DQ Rules Generator translates governance rules into executable YAML definitions.

Inputs may come from:

- central Rule Repository
- spreadsheet
- Data Governance portal
- Data Product configuration
- versioned YAML or JSON

The generated definition contains, for example:

- Rule ID
- dbt Test Name
- model and column
- Test Type
- arguments
- threshold
- Severity
- Owner
- Data Product
- tags
- `store_failures`
- active status
- Rule Version

Output:

```text
models/customer/schema.yml
models/sales/schema.yml
models/finance/schema.yml
```

Governance rules therefore become reproducible dbt configuration.

### DQ History Generator

The DQ History Generator builds the operational result layer.

It can generate:

- schema for `DQ_TEST_HISTORY`
- artifact staging
- dbt Unique ID to Rule ID mapping
- `on-run-end` macro
- incremental History Model
- status normalization
- `Rows Tested` calculation
- Failure Rate calculation
- Qlik and Power BI view
- optional failure-detail references

A possible structure:

```text
models/dq/stg_dbt_run_results.sql
models/dq/int_dbt_test_metrics.sql
models/dq/dq_test_history.sql
models/dq/v_dq_monitoring.sql
macros/dq/persist_dq_results.sql
```

## One central Rule Definition as the source of truth

A rule-driven approach separates definition from generation.

Example:

```yaml
rule_id: DQ-102
name: Sales amount within accepted range
data_product: Sales
owner: Data Steward Sales
severity: High
target:
  model: fact_sales
  column: amount
test:
  type: value_between
  arguments:
    min_value: 0
    max_value: 1000000
thresholds:
  warn_if: "> 0"
  error_if: "> 100"
execution:
  engine: dbt
  store_failures: true
history:
  rows_tested_strategy: model_count
  enabled: true
version: 3
```

This generates:

```flowchart
Rule Definition
dbt Macro
schema.yml
Failure Table
History Model
Monitoring View
```

The rule definition is not only documentation. It becomes the source for code, execution and monitoring.

## Standardize Failure Tables without losing evidence

dbt stores the columns returned by the individual test query.

This supports root-cause analysis but produces heterogeneous failure tables.

A not-null test may return:

```text
customer_id
email
source_system
```

A duplicate test may return:

```text
order_id
n_records
```

A relationships test may return:

```text
product_id
```

Failure Tables do not need to be forced into one rigid detail format.

A better separation is:

```text
DQ_TEST_HISTORY
→ fully standardized

DBT_TEST__AUDIT.*
→ test-specific evidence

DQ_FAILURE_INDEX
→ standardized reference to evidence
```

A Failure Index may contain:

- Run ID
- Rule ID
- dbt Unique ID
- Failure Relation
- number of stored failing rows
- business-key column
- retention class
- PII classification
- Details URI

Monitoring remains consistent while business evidence remains useful.

## Privacy and retention

`store_failures` can duplicate personal or confidential values.

Audit tables therefore require controls:

- return only necessary columns
- mask or hash PII where possible
- store business keys instead of full records
- restrict access to the audit schema
- define retention
- separate development and production schemas
- limit extremely large failure sets
- classify failure relations by Rule ID

A Custom Generic Test should not use `select *` by default when three columns are sufficient for remediation.

## Qlik and Power BI consume the history

Qlik and Power BI should not load every heterogeneous audit table directly.

The preferred architecture is:

```flowchart
dbt Test Results
DQ_TEST_HISTORY
Governed Monitoring View
Qlik / Power BI
```

Typical analytics include:

- Failure Rate over Time
- Rule Pass Rate
- warnings and failures by Severity
- failed rules by Data Product
- recurring failures by Rule ID
- test execution time
- current Failure Relation
- last successful execution
- rules without a current run
- time from failure to remediation
- re-test after correction

### Row Failure Rate

```text
Sum(Rows Failed) / Sum(Rows Tested)
```

### Rule Pass Rate

```text
Passed Tests / Executed Tests
```

### Execution Coverage

```text
Executed Active Rules / Active Rules
```

Coverage matters. A green dashboard is misleading when part of the active rule set did not execute.

## Practical dbt target architecture

A production-oriented structure can use:

```flow linear vertical
Governance Rule Repository
Generated dbt Tests and YAML
dbt Execution and Audit Tables
Artifact or Hook Staging
DQ_TEST_HISTORY
DQ Monitoring View
Qlik / Power BI
```

Recommended components:

| Component | Purpose |
| --- | --- |
| **Rule Repository** | business rule, Owner, Severity, version |
| **DG Macro Generator** | reusable test logic |
| **DQ Rules Generator** | executable YAML instances |
| **dbt Data Tests** | technical execution |
| **store_failures** | current failing records |
| **Artifact Loader / Hook** | capture results for each invocation |
| **DQ History Generator** | standardized history |
| **Monitoring View** | BI-friendly result layer |

## A practical first sprint

1. Select one dbt application with four rules.
2. Assign stable Rule IDs and custom test names.
3. Enable `store_failures` for critical rules.
4. Persist failure relations in a controlled audit schema.
5. Preserve `run_results.json` and `manifest.json` for every invocation.
6. Build a staging table for test results.
7. Define `Rows Tested` clearly for all four rules.
8. Create an append-oriented `DQ_TEST_HISTORY`.
9. Enrich status, Owner, Severity and Data Product.
10. Provide a monitoring view for Qlik or Power BI.
11. Introduce the three generators step by step.

The prototype should prove the complete vertical flow:

```flowchart
Rule
YAML
dbt Test
Failure Evidence
History Row
Dashboard
Owner
```

## Common design mistakes

### Confusing `store_failures` with history

The audit relation contains the current failure set and replaces previous results.

### Storing only failed tests

This removes passed executions and prevents a correct Pass Rate.

### Using `Rows Failed` without defined semantics

For duplicates, one returned row may represent one group or many records.

### Expecting `Rows Tested` from the standard dbt result

This metric normally requires additional calculation.

### Using generated test names as permanent governance identities

Changes to arguments or models may alter technical identities. A stable Rule ID should be maintained separately.

### Loading all audit tables directly into Qlik or Power BI

Their structures are test-specific and are not designed as a shared fact model.

### Keeping failure details indefinitely

Audit tables can become large and may contain sensitive data.

### Treating DG Tools only as code generators

The main benefit is not only reduced manual work. The generators enforce one rule contract from governance through execution and history.

## The central conclusion

> **dbt provides a strong testing engine and can persist current failures directly. True Operational Data Quality requires an additional append-oriented history table.**

Generic Tests and Custom Tests provide executable rules.

YAML connects rules to models, arguments, Severity, thresholds and `store_failures`.

Audit tables preserve current evidence.

`run_results.json`, `manifest.json`, `invocation_id` and the `on-run-end` context provide technical metadata for history.

The DG Tools then automate the complete chain:

```flowchart
DG Macro Generator
DQ Rules Generator
DQ History Generator
```

Individual dbt tests become a rule-driven, versioned Data Quality platform that can be analyzed in Qlik or Power BI.

## Related playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [Part 2 — Data Quality in Microsoft Fabric](/playbooks/dq-test2)
- [The Role of dbt](/playbooks/dbt-role)
- [The Missing Pieces — Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality & Governance](/playbooks/data-quality-governance)

## Sources and further reading

- [dbt — Add Data Tests to Your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [dbt — Data Tests Property](https://docs.getdbt.com/reference/resource-properties/data-tests)
- [dbt — store_failures](https://docs.getdbt.com/reference/resource-configs/store_failures)
- [dbt — fail_calc](https://docs.getdbt.com/reference/resource-configs/fail_calc)
- [dbt — severity, error_if and warn_if](https://docs.getdbt.com/reference/resource-configs/severity)
- [dbt — Run Results JSON](https://docs.getdbt.com/reference/artifacts/run-results-json)
- [dbt — on-run-end Context](https://docs.getdbt.com/reference/dbt-jinja-functions/on-run-end-context)
- [dbt — invocation_id](https://docs.getdbt.com/reference/dbt-jinja-functions/invocation_id)

> **Feature status:** July 2026. dbt syntax, supported configurations and artifact schemas may differ between dbt Core, Fusion and the dbt platform. Verify the documentation for the version used by the project.
