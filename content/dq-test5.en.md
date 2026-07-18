---
title: One Rule, Three Platforms — A Business Data Quality Rule in Fabric, dbt and Databricks
description: How the same business data quality rule is implemented differently in Microsoft Fabric, dbt and Databricks while preserving identical semantics and a shared standardized result for monitoring.
category: Data Quality
tags:
  - data-quality
  - operational-data-quality
  - microsoft-fabric
  - dbt
  - databricks
  - data-contract
  - quality-rules
  - standardized-results
  - qlik-sense
  - power-bi
  - data-governance
  - rule-registry
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 5
seriesTitle: Operational Data Quality
hero: images/playbooks/dq-test5-hero.png
---

## A rule is a business definition — its implementation is technical

Data quality rules should not begin with the tool.

The business requirement is not:

- Create a Fabric test.
- Write a dbt test.
- Define a Databricks expectation.

It is, for example:

> **Every active customer requires a valid Customer ID, a Country and an Update Date within the last 24 hours.**

This requirement remains unchanged whether the data is processed in Microsoft Fabric, through dbt or in Databricks.

The technical implementation differs:

- Fabric can execute the rule with Warehouse SQL, notebook code, pipelines or, for some checks, Materialized Lake View constraints.
- dbt defines tests in YAML and SQL and returns the records that violate an assertion.
- Databricks defines expectations directly on streaming tables or materialized views and records their outcomes in the pipeline event log.

```flowchart
One Business Rule
Platform-Specific Implementation
Standardized Result Contract
Qlik / Power BI
Ownership and Remediation
```

> **Platform neutrality does not mean using the same code everywhere. It means preserving the same business semantics and the same result contract everywhere.**

## Define the example rule precisely

The sentence “Every active customer requires a valid Customer ID, a Country and an Update Date within the last 24 hours” appears clear. It is not yet precise enough for reproducible execution.

At least the following questions must be resolved:

| Question | Semantics used in this example |
| --- | --- |
| What is an active customer? | `is_active = true` |
| What is a valid Customer ID? | not `NULL` and not blank |
| What is a valid Country? | not `NULL` and not blank |
| Which date is evaluated? | `updated_at` |
| Which time zone applies? | UTC |
| What does “within 24 hours” mean? | `updated_at >= evaluation_timestamp - 24 hours` |
| How are future dates handled? | separate plausibility rule, not part of this rule |
| What happens when `updated_at` is `NULL`? | rule violation |
| Which records form the test scope? | active customers only |
| How are failures counted? | one violation per customer and atomic check |

This definition should be versioned in a central rule registry.

A possible business header is:

```text
Rule ID: DQ-CUST-ACTIVE-001
Rule Version: 1
Data Product: Customer 360
Owner: Data Steward CRM
Severity: High
Scope: Active customers
Evaluation Time Zone: UTC
```

## Decompose one business rule into atomic checks

For monitoring and root-cause analysis, decompose the composite rule into three atomic checks:

| Check ID | Check | Failure condition |
| --- | --- | --- |
| **DQ-CUST-ACTIVE-001-A** | Customer ID is present | active record and Customer ID is `NULL` or blank |
| **DQ-CUST-ACTIVE-001-B** | Country is present | active record and Country is `NULL` or blank |
| **DQ-CUST-ACTIVE-001-C** | Update Date is current | active record and `updated_at` is `NULL` or older than 24 hours |

This creates two levels:

```text
Business Rule
DQ-CUST-ACTIVE-001

Atomic Checks
DQ-CUST-ACTIVE-001-A
DQ-CUST-ACTIVE-001-B
DQ-CUST-ACTIVE-001-C
```

A customer may violate several conditions at the same time.

Example:

```text
Customer C12345:
Customer ID is missing
Country is missing
Update Date is too old
```

The sum of atomic violations is `3`, while only one customer is affected.

Keep two metrics separate:

- **Rule Violations:** number of atomic violations
- **Affected Entities:** number of distinct customers with at least one violation

> **Do not simply add failures across atomic checks when the same entity can violate more than one check.**

## Same business rule, different implementations

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test5-img1-en.png"
        alt="The same customer business rule covering Customer ID, Country and Update Date is implemented with different native mechanisms in Microsoft Fabric, dbt and Databricks"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Implementation follows the capabilities of each platform. Scope, evaluation time, failure conditions and result semantics must remain identical.
    </figcaption>
</figure>

The diagram deliberately shows different technical forms:

- a rule- or SQL-driven Fabric implementation
- a dbt YAML definition using reusable data tests
- Databricks Lakeflow expectations

The code blocks in the diagram are a compressed representation of the pattern. Production implementation also needs:

- Run ID
- shared evaluation timestamp
- Rule Version
- error handling
- historical persistence
- owner and severity
- standardized result mapping

## The shared execution context

Even an identical rule can produce different outcomes when platforms do not share the same execution context.

### Shared evaluation timestamp

The following conditions are not fully equivalent:

```text
Fabric evaluates at 08:00:01
dbt evaluates at 08:02:43
Databricks evaluates at 08:05:17
```

A customer close to the 24-hour boundary can pass on one platform and fail on another.

Orchestration should therefore provide one common value:

```text
Evaluation Timestamp:
2026-07-18T08:00:00Z
```

Every platform derives the same cutoff:

```text
Freshness Cutoff:
2026-07-17T08:00:00Z
```

### The same data snapshot

A common timestamp is insufficient if the underlying data changes while tests are running.

True reconciliation requires:

- the same snapshot
- the same Delta version
- the same batch or watermark
- the same source version
- or a business-equivalent replicated dataset

### The same null and blank semantics

Platforms and source systems can treat these values differently:

```text
NULL
''
'   '
'UNKNOWN'
'N/A'
```

The rule registry must define which values count as missing.

In this example:

```text
NULL, empty and whitespace-only
→ invalid
```

### The same grain

The rule evaluates active customers.

It does not evaluate:

- historical inactive customers
- technical intermediate records
- duplicate source rows when the Data Product contract already promises one customer row per customer
- deleted records outside the current business scope

## Implementation in Microsoft Fabric

For the complete example rule, a SQL or notebook test is usually the most direct option.

Materialized Lake Views support declarative constraints with `DROP` or `FAIL`. Current limitations on constraint expressions can restrict dynamic or function-based checks. Validate a relative 24-hour test against the current Fabric documentation or execute it with Warehouse SQL or notebook code.

### T-SQL pattern in Fabric Warehouse

Orchestration supplies the `Run ID` and `Evaluation Timestamp`.

```sql
DECLARE @run_id VARCHAR(100) =
    'RUN_20260718_080000';

DECLARE @evaluation_ts DATETIME2(6) =
    '2026-07-18T08:00:00';

WITH active_customers AS (
    SELECT
        customer_id,
        country,
        updated_at
    FROM silver.customer
    WHERE is_active = 1
),
check_metrics AS (
    SELECT
        'DQ-CUST-ACTIVE-001-A' AS check_id,
        'customer_id_required' AS check_name,
        COUNT_BIG(*) AS rows_tested,
        SUM(
            CASE
                WHEN customer_id IS NULL
                  OR LTRIM(RTRIM(customer_id)) = ''
                THEN CAST(1 AS BIGINT)
                ELSE CAST(0 AS BIGINT)
            END
        ) AS rows_failed
    FROM active_customers

    UNION ALL

    SELECT
        'DQ-CUST-ACTIVE-001-B',
        'country_required',
        COUNT_BIG(*),
        SUM(
            CASE
                WHEN country IS NULL
                  OR LTRIM(RTRIM(country)) = ''
                THEN CAST(1 AS BIGINT)
                ELSE CAST(0 AS BIGINT)
            END
        )
    FROM active_customers

    UNION ALL

    SELECT
        'DQ-CUST-ACTIVE-001-C',
        'updated_within_24h',
        COUNT_BIG(*),
        SUM(
            CASE
                WHEN updated_at IS NULL
                  OR updated_at <
                     DATEADD(HOUR, -24, @evaluation_ts)
                THEN CAST(1 AS BIGINT)
                ELSE CAST(0 AS BIGINT)
            END
        )
    FROM active_customers
)
SELECT
    @run_id AS run_id,
    check_id,
    check_name,
    rows_tested,
    rows_failed,
    CAST(rows_failed AS DECIMAL(18,8))
        / NULLIF(
            CAST(rows_tested AS DECIMAL(18,8)),
            0
        ) AS failure_rate,
    @evaluation_ts AS evaluation_timestamp
FROM check_metrics;
```

The result is then appended to the central DQ history.

### Failure details in Fabric

A separate detail table can support root-cause analysis:

```sql
SELECT
    @run_id AS run_id,
    'DQ-CUST-ACTIVE-001-B' AS check_id,
    customer_id AS entity_id,
    'country is missing' AS result_detail
FROM silver.customer
WHERE is_active = 1
  AND (
      country IS NULL
      OR LTRIM(RTRIM(country)) = ''
  );
```

Aggregated results and detailed failure rows belong in separate tables.

## Implementation with dbt

dbt data tests are SQL queries that return failing records.

The composite rule can be implemented as a reusable generic data test and configured in YAML.

### YAML configuration

```yaml
version: 2

models:
  - name: dim_customer
    data_tests:
      - active_customer_contract:
          arguments:
            active_column: is_active
            customer_id_column: customer_id
            country_column: country
            updated_at_column: updated_at
            evaluation_timestamp: "2026-07-18 08:00:00+00:00"
            freshness_hours: 24
          config:
            store_failures: true
            severity: error
            meta:
              rule_id: DQ-CUST-ACTIVE-001
              owner: Data Steward CRM
              dq_severity: High
              rule_version: 1
```

Current dbt syntax uses `data_tests:`. The older `tests:` key remains supported for backward compatibility.

### Generic test

A generic test can return one row per atomic violation:

```sql
{% test active_customer_contract(
    model,
    active_column,
    customer_id_column,
    country_column,
    updated_at_column,
    evaluation_timestamp,
    freshness_hours
) %}

with scoped as (
    select *
    from {{ model }}
    where {{ active_column }} = true
),

violations as (
    select
        cast({{ customer_id_column }} as {{ dbt.type_string() }})
            as entity_id,
        'DQ-CUST-ACTIVE-001-A' as check_id,
        'customer_id_required' as check_name,
        'customer_id is missing' as result_detail
    from scoped
    where {{ customer_id_column }} is null
       or trim({{ customer_id_column }}) = ''

    union all

    select
        cast({{ customer_id_column }} as {{ dbt.type_string() }}),
        'DQ-CUST-ACTIVE-001-B',
        'country_required',
        'country is missing'
    from scoped
    where {{ country_column }} is null
       or trim({{ country_column }}) = ''

    union all

    select
        cast({{ customer_id_column }} as {{ dbt.type_string() }}),
        'DQ-CUST-ACTIVE-001-C',
        'updated_within_24h',
        'updated_at is older than 24 hours'
    from scoped
    where {{ updated_at_column }} is null
       or {{ dq_older_than_hours(
            updated_at_column,
            evaluation_timestamp,
            freshness_hours
       ) }}
)

select *
from violations

{% endtest %}
```

`dq_older_than_hours` represents an adapter-aware macro that generates the correct date arithmetic for the target engine.

Possible technical translations include:

```text
Fabric Warehouse:
updated_at < DATEADD(HOUR, -24, evaluation_timestamp)

Databricks SQL:
updated_at < evaluation_timestamp - INTERVAL 24 HOURS

Snowflake:
updated_at < DATEADD(HOUR, -24, evaluation_timestamp)
```

The business rule remains stable while dbt compiles platform-specific SQL.

### `store_failures` is detail storage, not history

With `store_failures: true`, dbt persists failing records in an audit table.

This is useful for root-cause analysis.

The audit table is not a historical result fact:

- dbt stores the current failing records of the test.
- A later run replaces previous failures for the same test.
- A successful run can replace the earlier failure relation.
- Trend analysis therefore requires an additional append-only history table.

History can be built from:

- `run_results.json`
- `manifest.json`
- the audit table
- invocation metadata
- rule metadata from YAML
- a downstream DQ History model

```flowchart
dbt Test
Audit Failure Table
dbt Artifacts
DQ History Model
Standardized Result
```

## Implementation in Databricks

Databricks Lakeflow expectations are declared directly on a dataset.

For comparable measurement, warn behavior is a useful starting point because invalid data is not removed and the flow does not stop on the first violation.

### SQL example

```sql
CREATE OR REFRESH STREAMING TABLE silver.customer_validated (
    CONSTRAINT active_customer_id_required
        EXPECT (
            NOT is_active
            OR (
                customer_id IS NOT NULL
                AND TRIM(customer_id) <> ''
            )
        ),

    CONSTRAINT active_customer_country_required
        EXPECT (
            NOT is_active
            OR (
                country IS NOT NULL
                AND TRIM(country) <> ''
            )
        ),

    CONSTRAINT active_customer_updated_within_24h
        EXPECT (
            NOT is_active
            OR (
                updated_at IS NOT NULL
                AND updated_at >=
                    current_timestamp() - INTERVAL 24 HOURS
            )
        )
)
AS
SELECT *
FROM STREAM(bronze.customer);
```

Without `ON VIOLATION`, Databricks records violations as warnings and continues processing the records.

Alternative actions are:

```sql
ON VIOLATION DROP ROW
```

```sql
ON VIOLATION FAIL UPDATE
```

These change the technical semantics:

- `DROP` removes records from the target.
- `FAIL` can stop the affected update before all records are measured.
- Complete metrics may not be available for an early failure.

For a platform comparison, record the action explicitly and do not confuse it with severity.

### Use a shared evaluation timestamp

The example uses `current_timestamp()` for readability.

For reconciliation, supply the shared evaluation timestamp as pipeline configuration or a parameter.

Conceptually:

```text
evaluation_timestamp =
2026-07-18T08:00:00Z
```

The expectation evaluates:

```text
evaluation_timestamp - 24 hours
```

### The event log as the technical result source

Databricks records expectation metrics in the pipeline event log.

Relevant fields can include:

- Expectation Name
- Dataset
- Passed Records
- Failed Records
- Pipeline Update ID
- Flow Name
- Event Timestamp

A normalization layer maps:

```text
active_customer_id_required
→ DQ-CUST-ACTIVE-001-A

active_customer_country_required
→ DQ-CUST-ACTIVE-001-B

active_customer_updated_within_24h
→ DQ-CUST-ACTIVE-001-C
```

## Three platforms — three native raw outputs

| Aspect | Microsoft Fabric | dbt | Databricks |
| --- | --- | --- | --- |
| Primary implementation | SQL, notebook or pipeline | Generic or singular data test | Lakeflow expectation |
| Rule configuration | SQL, metadata or generator | YAML and SQL macro | SQL or Python |
| Raw output | query or notebook result | failing records and dbt artifact | event log metrics |
| Failure details | separate Delta or Warehouse table | audit table through `store_failures` | quarantine or separate failure dataset |
| History | explicit append | additional history required | event log is historical but needs curation |
| Enforcement | custom; MLV supports Drop/Fail | Warn/Error semantics in test execution | Warn, Drop or Fail |
| Shared output | DQ Result Table | DQ History Model | DQ History Delta Table |

The raw outputs are not identical.

The business statement can still be identical.

## One shared standardized result

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test5-img2-en.png"
        alt="Microsoft Fabric, dbt and Databricks write outcomes of the same customer business rule into a shared standardized DQ result structure"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Platform and execution method may vary. Rule ID, Check ID, scope, status, counting method and result semantics must remain stable.
    </figcaption>
</figure>

The aggregated result table from this series remains the central contract.

### Aggregated DQ result table

Grain:

> **One row per platform, run and atomic check.**

Recommended fields:

| Field | Example |
| --- | --- |
| Run ID | `RUN_20260718_080000` |
| Rule ID | `DQ-CUST-ACTIVE-001` |
| Check ID | `DQ-CUST-ACTIVE-001-B` |
| Rule Version | `1` |
| Platform | `dbt` |
| Execution Method | `Generic Data Test` |
| Data Product | `Customer 360` |
| Table | `DIM_CUSTOMER` |
| Column | `COUNTRY` |
| Test Type | `Conditional Not Null` |
| Status | `Failed` |
| Rows Tested | `250000` |
| Rows Failed | `382` |
| Failure Rate | `0.001528` |
| Evaluation Timestamp | `2026-07-18T08:00:00Z` |
| Executed At | actual technical execution time |
| Owner | `Data Steward CRM` |
| Severity | `High` |
| Source Execution ID | pipeline, dbt or update identifier |
| Metric Completeness | `Complete` |

### Optional DQ failure detail table

Grain:

> **One row per platform, run, atomic check and affected entity.**

Example:

| Run ID | Check ID | Platform | Entity ID | Result Detail |
| --- | --- | --- | --- | --- |
| RUN-001 | ...-A | Fabric | C12345 | Customer ID is missing |
| RUN-001 | ...-B | dbt | C12345 | Country is missing |
| RUN-001 | ...-C | Databricks | Update Date is older than 24 hours |

The diagram uses detail rows to illustrate the concept. Trend and KPI reporting should additionally use the aggregated result fact.

## Normalize status

The platforms use different terms.

A possible mapping is:

| Platform outcome | Common status |
| --- | --- |
| Fabric: zero failures | Passed |
| Fabric: threshold violated | Failed |
| dbt: pass | Passed |
| dbt: warn | Warning |
| dbt: fail | Failed |
| dbt: error | Error |
| Databricks warn with zero failures | Passed |
| Databricks warn with failures | Warning |
| Databricks drop with failures | Filtered |
| Databricks fail update | Failed Early |
| technical runtime failure | Error |

Store the action separately:

```text
Action:
Observe
Warn
Drop
Fail
```

Severity remains a separate business field.

## Make results comparable

At least eight conditions must be met before results are truly comparable:

1. **Same Rule Version**
2. **Same active scope**
3. **Same evaluation timestamp**
4. **Same time zone**
5. **Same data snapshot or watermark**
6. **Same null and blank semantics**
7. **Same counting method**
8. **Same status and threshold logic**

Only then is cross-platform comparison meaningful.

```text
Same business sentence
≠ automatically comparable execution
```

Comparability comes from the technical contract.

## Reconciliation: should the outcomes be identical?

When the same data snapshot is tested on all three platforms, every atomic check should produce:

```text
Rows Tested: identical
Rows Failed: identical
Failure Rate: identical
Affected Entity Set: identical
```

Differences become a data quality or replication signal.

Examples:

- Fabric sees 250,000 active customers while Databricks sees 249,980.
- dbt finds 382 missing countries while Fabric finds 380.
- Databricks classifies twelve customers as stale while dbt finds ten.
- One platform trims whitespace while another does not.
- Timestamps are interpreted in different time zones.

A reconciliation table can contain:

| Run ID | Check ID | Fabric Failed | dbt Failed | Databricks Failed | Reconciled |
| --- | --- | ---: | ---: | ---: | --- |
| RUN-001 | ...-A | 15 | 15 | 15 | Yes |
| RUN-001 | ...-B | 382 | 380 | 382 | No |
| RUN-001 | ...-C | 12 | 10 | 12 | No |

This is particularly useful during migration, parallel operations or platform replacement.

In normal target operations, the same rule does not need to run on three platforms simultaneously. The important requirement is that every platform can fulfill the same output contract.

## Rule Registry as the single source of truth

The business definition should not be maintained manually three times.

A central rule registry can contain:

```yaml
rule_id: DQ-CUST-ACTIVE-001
version: 1
data_product: Customer 360
scope:
  expression: is_active = true
checks:
  - check_id: DQ-CUST-ACTIVE-001-A
    type: required
    column: customer_id
    empty_is_invalid: true
  - check_id: DQ-CUST-ACTIVE-001-B
    type: required
    column: country
    empty_is_invalid: true
  - check_id: DQ-CUST-ACTIVE-001-C
    type: freshness
    column: updated_at
    max_age_hours: 24
owner: Data Steward CRM
severity: High
timezone: UTC
```

Generators can create:

```flowchart
Rule Registry
Fabric SQL / Notebook Definition
dbt YAML and Test Macro
Databricks Expectations
Result Mapping
```

This connects to the DG Tools introduced in Part 3:

- DG Macro Generator
- DQ Rules Generator
- DQ History Generator

Generation does not replace business approval. It reduces copy-and-paste, naming drift and inconsistent result logic.

## Version the rule

A rule change must not silently reinterpret historical results.

Example:

```text
Version 1:
maximum age = 24 hours

Version 2:
maximum age = 12 hours
```

The `Rule ID` can remain stable while `Rule Version` changes.

Historical result rows store:

- Rule ID
- Rule Version
- threshold
- Evaluation Timestamp
- technical implementation version
- Source Execution ID

BI can then distinguish:

```text
Trend within the same rule version
versus
change caused by a new rule definition
```

## Qlik and Power BI analysis

Qlik and Power BI consume the same curated result model.

Typical perspectives include:

- Failure Rate by Check
- platform comparison
- reconciliation differences
- trends by Rule Version
- affected customers
- failures by Owner and Severity
- freshness violations
- quality before and after migration
- technical Errors and incomplete metrics

### Row-weighted failure rate

Qlik:

```qlik
Num(
    Sum(rows_failed)
    /
    Sum(rows_tested),
    '0.00%'
)
```

Power BI:

```DAX
Failure Rate =
DIVIDE(
    SUM(DQ_Result[Rows Failed]),
    SUM(DQ_Result[Rows Tested])
)
```

### Affected customers

When failure details are available:

Qlik:

```qlik
Count(DISTINCT entity_id)
```

Power BI:

```DAX
Affected Customers =
DISTINCTCOUNT(DQ_Failure_Detail[Entity ID])
```

Do not derive this metric from the sum of atomic `Rows Failed`.

## Which platform implementation is best?

This playbook does not select a product winner.

The right implementation depends on the objective.

### Microsoft Fabric

Suitable when:

- Lakehouse and Warehouse already form the central platform
- SQL, notebook and pipeline teams work together
- results should be written directly as Delta or Warehouse tables
- Power BI integration is important
- Purview or Materialized Lake Views are additional components

### dbt

Suitable when:

- rules should be versioned as code and YAML
- the same test macros are reused across many models
- SQL transformations and tests are tightly connected
- several target platforms need support
- generator- and artifact-based history is being built

### Databricks

Suitable when:

- quality must be enforced directly in streaming and pipeline processing
- Warn, Drop and Fail are native pipeline actions
- event logs and Delta tables form the observability foundation
- large or continuous datasets are processed
- Lakeflow and Unity Catalog already form the platform foundation

> **The platform determines execution. Governance determines the rule.**

## A practical pilot

A reliable comparison can be built as a small pilot:

1. Provide an identical customer snapshot.
2. Define a common Rule ID and three Check IDs.
3. Set the evaluation timestamp in UTC.
4. Execute the Fabric test against the snapshot.
5. Run the dbt test with `store_failures`.
6. Execute Databricks expectations in warn mode.
7. Transform the raw results into the common schema.
8. Compare `Rows Tested`, `Rows Failed` and entity sets.
9. Investigate differences and correct semantic drift.
10. Put Qlik or Power BI on the standardized result table.

## Common design mistakes

### Confusing the same sentence with the same semantics

An identical description does not guarantee identical technical evaluation.

### Calling `current_timestamp()` separately everywhere

Results can differ near the 24-hour boundary.

### Adding atomic failures and presenting them as affected customers

One customer can violate several checks.

### Treating dbt audit tables as history

`store_failures` persists current failures but replaces earlier outcomes for the same test.

### Showing Databricks Drop as passed quality

Records were removed. The violation remains relevant.

### Comparing native Fabric, dbt and Databricks statuses directly

Status values need a common mapping.

### Making platform code the leading rule definition

This creates three business truths instead of one rule.

### Not storing Rule Version

A threshold change otherwise looks like a sudden quality decline.

## The central conclusion

> **A business rule can be implemented differently in Microsoft Fabric, dbt and Databricks while producing exactly the same data quality statement.**

It requires:

- a precise business definition
- atomic Check IDs
- a versioned Rule Registry
- a common scope
- a shared evaluation timestamp
- identical counting and status semantics
- a standardized result structure
- separate aggregates and failure details

The organization can then use the best technical platform for each data process without reinventing governance, monitoring and comparability.

## Related playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [Part 2 — Data Quality in Microsoft Fabric](/playbooks/dq-test2)
- [Part 3 — Data Quality with dbt](/playbooks/dq-test3)
- [Part 4 — Data Quality in Databricks](/playbooks/dq-test4)
- [Data Quality & Governance](/playbooks/data-quality-governance)
- [The Role of dbt](/playbooks/dbt-role)

## Sources and further reading

- [Microsoft Fabric — Data Quality in Materialized Lake Views](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/data-quality)
- [Microsoft Fabric — Materialized Lake Views Overview](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/overview-materialized-lake-view)
- [Microsoft Fabric — Data Quality Report for Materialized Lake Views](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/data-quality-reports)
- [dbt — Add Data Tests to Your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [dbt — Data Tests Property](https://docs.getdbt.com/reference/resource-properties/data-tests)
- [dbt — store_failures](https://docs.getdbt.com/reference/resource-configs/store_failures)
- [dbt — Run Results JSON](https://docs.getdbt.com/reference/artifacts/run-results-json)
- [Databricks — Manage Data Quality with Pipeline Expectations](https://docs.databricks.com/aws/en/ldp/expectations)
- [Databricks — Lakeflow Pipeline Best Practices](https://docs.databricks.com/aws/en/ldp/best-practices)
- [Databricks — Pipeline Event Log](https://docs.databricks.com/aws/en/ldp/monitor-event-logs)
- [Databricks — event_log Table-Valued Function](https://docs.databricks.com/aws/en/sql/language-manual/functions/event_log)

> **Feature status:** July 2026. Platform capabilities, syntax and preview status can change. Verify the current vendor documentation before production implementation.
