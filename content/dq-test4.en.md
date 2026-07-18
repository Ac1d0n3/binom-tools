---
title: Data Quality in Databricks — Expectations, Event Logs and Historical DQ Monitoring
description: How Lakeflow Expectations enforce data quality on streaming tables and materialized views, control warn, drop and fail behavior, and feed a common historical data quality model for Qlik and Power BI.
category: Data Quality
tags:
  - data-quality
  - operational-data-quality
  - databricks
  - lakeflow
  - spark-declarative-pipelines
  - delta-live-tables
  - expectations
  - event-log
  - delta-lake
  - streaming-tables
  - materialized-views
  - qlik-sense
  - power-bi
  - data-governance
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 4
seriesTitle: Operational Data Quality
hero: images/playbooks/dq-test4-hero.png
---

## Expectations put data quality directly into the Databricks pipeline

Databricks Lakeflow Spark Declarative Pipelines can execute data quality rules directly on streaming tables, materialized views and temporary views.

These rules are called **expectations**.

An expectation consists of:

- a stable name
- a Boolean SQL expression
- an action for invalid records
- the dataset on which it is evaluated

Validation runs while data moves through the pipeline. Data quality is therefore part of processing rather than only a report created afterwards.

```flowchart
Source Data
Streaming Table / Materialized View
Expectation
Warn / Drop / Fail
Pipeline Event Log
DQ History
Qlik / Power BI
```

Lakeflow provides two distinct layers:

1. **Enforcement:** What should happen to an invalid record?
2. **Observation:** How are quality outcomes, pipeline states and technical events made traceable?

Expectations solve the first task. The pipeline event log provides the technical foundation for the second.

> **Operational Data Quality emerges when expectations do more than filter data or stop updates and their results are also converted into a stable historical DQ model.**

## From Delta Live Tables to Lakeflow pipelines

The product formerly known as **Delta Live Tables (DLT)** is now named **Lakeflow Spark Declarative Pipelines**, or Lakeflow pipelines.

According to Databricks, existing DLT code generally continues to run. For new Python implementations, Databricks recommends the current API:

```python
from pyspark import pipelines as dp
```

Current decorators replace older `dlt` names:

| Earlier | Current |
| --- | --- |
| `import dlt` | `from pyspark import pipelines as dp` |
| `@dlt.table` | `@dp.table` |
| `@dlt.view` | `@dp.temporary_view` |
| DLT Pipeline | Lakeflow Pipeline |

Names containing `dlt` can still appear in event log structures, configurations and older examples. The data quality pattern remains the same.

## Streaming table or materialized view

Expectations can be applied to different dataset types.

### Streaming tables

Streaming tables are particularly suitable for:

- continuously or incrementally arriving data
- append-oriented sources
- Auto Loader
- Kafka, Event Hubs and other streaming sources
- Bronze and Silver processing with streaming semantics

A streaming table processes new data incrementally.

### Materialized views

Materialized views are particularly suitable for:

- batch transformations
- fully calculated current-state views
- aggregations
- joins
- business logic that is not purely append-oriented
- curated Silver or Gold datasets

Both dataset types can contain expectations. The difference is the processing semantics of the dataset, not the rule definition.

## An expectation is a row-level Boolean rule

An expectation evaluates every record using an expression that returns `true` or `false`.

Examples:

```text
amount >= 0
country IS NOT NULL
order_timestamp <= current_timestamp()
status IN ('OPEN', 'CLOSED', 'CANCELLED')
```

A rule can contain more complex Boolean logic:

```sql
(
    order_type = 'SALE'
    AND amount > 0
)
OR
(
    order_type = 'REFUND'
    AND amount < 0
)
```

Constraints use SQL expressions even when the pipeline is developed in Python.

According to the current Databricks documentation, expectations cannot contain elements such as:

- custom Python functions
- external service calls
- subqueries referencing other tables

This creates an important limitation:

> **Not every data quality rule can be expressed as a simple expectation.**

A uniqueness rule across records requires an aggregation, window logic, an upstream view or a separate test process. `order_id IS NOT NULL` is a direct expectation. “`order_id` occurs exactly once” is not a purely row-local condition.

## Warn, drop or fail

Databricks can react in different ways when a rule is violated.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test4-img1-en.png"
        alt="Databricks Expectations showing the three actions Warn, Drop and Fail and their effects on data flow, the event log and downstream processing"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The same quality rule can log violations, remove invalid records from the target or stop the affected update depending on business criticality.
    </figcaption>
</figure>

### Warn — keep invalid data and record the violation

`Warn` is the default behavior.

Invalid records are still written to the target. Databricks records how many records passed or failed the rule.

Suitable for:

- new rules in an observation phase
- known deviations that are currently tolerated
- rules whose thresholds are not yet agreed
- data that downstream consumers still require
- profiling and baselining

The risk:

> A warning does not improve data automatically.

An owner, threshold and remediation process must determine when a warning becomes an action.

### Drop — remove invalid data from the target

With `Drop`, records that violate the rule are removed before data is written to the target.

Suitable for:

- technically unusable records
- safely isolatable defects
- rules where the remaining data may continue
- curated Silver tables
- processes with a separate quarantine or evidence store

The risk:

> The target can appear clean even though records were lost.

Drop counts must therefore remain visible. A lower output volume does not automatically mean better data quality.

### Fail — stop the affected update

With `Fail`, an invalid record prevents the affected update or flow from succeeding.

Suitable for:

- critical keys
- regulated data
- rules without which the target would be materially incorrect
- irreversible or high-risk downstream processes
- hard data contracts

Technical precision matters:

- Architecture diagrams often simplify this as “fail the pipeline.”
- Databricks describes the affected update or flow as failing.
- Other independent flows in the same pipeline do not necessarily fail with it.

## SQL examples

### Warn as the default behavior

Without an `ON VIOLATION` clause, invalid records remain in the target:

```sql
CREATE OR REFRESH STREAMING TABLE silver.orders_warn (
    CONSTRAINT order_id_required
        EXPECT (order_id IS NOT NULL),

    CONSTRAINT amount_non_negative
        EXPECT (amount >= 0)
)
AS
SELECT *
FROM STREAM(bronze.orders);
```

Outcome:

- valid rows are written
- invalid rows are also written
- pass and failure metrics are captured for the expectations

### Drop invalid records

```sql
CREATE OR REFRESH STREAMING TABLE silver.orders_clean (
    CONSTRAINT order_id_required
        EXPECT (order_id IS NOT NULL)
        ON VIOLATION DROP ROW,

    CONSTRAINT amount_non_negative
        EXPECT (amount >= 0)
        ON VIOLATION DROP ROW
)
AS
SELECT *
FROM STREAM(bronze.orders);
```

Outcome:

- violating rows are not written to `silver.orders_clean`
- valid rows continue
- dropped-row and expectation metrics are captured in the pipeline context

### Fail an update for a critical violation

```sql
CREATE OR REFRESH MATERIALIZED VIEW gold.invoice_summary (
    CONSTRAINT invoice_id_required
        EXPECT (invoice_id IS NOT NULL)
        ON VIOLATION FAIL UPDATE,

    CONSTRAINT legal_entity_required
        EXPECT (legal_entity_id IS NOT NULL)
        ON VIOLATION FAIL UPDATE
)
AS
SELECT
    legal_entity_id,
    invoice_id,
    customer_id,
    amount,
    invoice_date
FROM silver.invoices;
```

Outcome:

- the affected flow update fails
- manual correction or controlled handling is required
- downstream processing must not treat the result as successful

## Python examples

Expectations are declared as decorators between the dataset decorator and the function.

### Warn

```python
from pyspark import pipelines as dp

@dp.table(name="orders_warn")
@dp.expect("order_id_required", "order_id IS NOT NULL")
@dp.expect("amount_non_negative", "amount >= 0")
def orders_warn():
    return spark.readStream.table("bronze.orders")
```

### Drop

```python
from pyspark import pipelines as dp

@dp.table(name="orders_clean")
@dp.expect_or_drop("order_id_required", "order_id IS NOT NULL")
@dp.expect_or_drop("amount_non_negative", "amount >= 0")
def orders_clean():
    return spark.readStream.table("bronze.orders")
```

### Fail

```python
from pyspark import pipelines as dp

@dp.materialized_view(name="invoice_summary")
@dp.expect_or_fail(
    "invoice_id_required",
    "invoice_id IS NOT NULL"
)
@dp.expect_or_fail(
    "legal_entity_required",
    "legal_entity_id IS NOT NULL"
)
def invoice_summary():
    return spark.table("silver.invoices")
```

### Apply several rules from a dictionary

Rules can be prepared centrally and applied together:

```python
from pyspark import pipelines as dp

order_rules = {
    "order_id_required": "order_id IS NOT NULL",
    "customer_id_required": "customer_id IS NOT NULL",
    "amount_non_negative": "amount >= 0",
    "valid_status": "status IN ('OPEN', 'CLOSED', 'CANCELLED')"
}

@dp.table(name="orders_validated")
@dp.expect_all(order_rules)
def orders_validated():
    return spark.readStream.table("bronze.orders")
```

Equivalent variants provide different actions:

```python
@dp.expect_all_or_drop(order_rules)
```

```python
@dp.expect_all_or_fail(order_rules)
```

Each rule name must be unique within a dataset. This name becomes the primary technical key for monitoring and mapping.

## Treat expectation names as rule identities

Databricks uses the expectation name to identify and monitor the rule.

Examples:

```text
order_id_required
amount_non_negative
country_required
valid_order_status
```

The shared DQ model needs a stable governance identity.

Two approaches are possible.

### Use the expectation name directly as the Rule ID

```text
Rule ID = amount_non_negative
```

This is simple when names are globally unique and stable.

### Map the expectation name to a central Rule ID

```text
Pipeline ID + Dataset + Expectation Name
→ DQ-00427
```

This is more robust when the same technical name occurs in several pipelines or data products.

A rule registry can contain:

| Field | Example |
| --- | --- |
| Rule ID | DQ-00427 |
| Platform | Databricks |
| Pipeline ID | `a1b2...` |
| Dataset | `silver.orders_clean` |
| Expectation Name | `amount_non_negative` |
| Action | Drop |
| Data Product | Sales |
| Owner | Data Steward Sales |
| Severity | High |
| Threshold | 0 |
| Rule Version | 3 |

## The pipeline event log

Every Lakeflow pipeline has an event log.

It contains information such as:

- pipeline and update events
- data quality metrics
- flow progress
- technical errors
- lineage
- runtime information
- user actions
- streaming and operational metrics

Databricks stores the event log by default as a hidden Delta table in the catalog and schema configured for the pipeline.

It can be:

- viewed in the pipeline UI
- read through APIs
- queried directly with SQL
- published in Unity Catalog
- exposed through a governed view

A query using the pipeline ID looks conceptually like this:

```sql
SELECT *
FROM event_log(<pipeline_id>);
```

For a published event log, create a governed view:

```sql
CREATE OR REPLACE VIEW governance.event_log_raw AS
SELECT *
FROM operations.pipeline_event_log;
```

A view is often preferable to direct access to the system table:

- permissions can be restricted
- internal fields can be hidden
- JSON structures can be normalized
- several event logs can be unified
- BI tools receive a stable schema

> **Do not delete the event log. It is part of pipeline operation, not merely a disposable reporting table.**

## Expectation metrics in the event log

Data quality information appears in events of type `flow_progress`.

For individual expectations, relevant fields include:

- `passed_records`
- `failed_records`
- expectation name
- dataset

At flow or dataset level, Databricks also provides:

- `dropped_records`

The data is contained in nested structures such as:

```text
details.flow_progress.data_quality.expectations
details.flow_progress.data_quality.dropped_records
```

An important modeling rule is:

> **`dropped_records` is not automatically a metric for each individual expectation.**

One record may violate several rules but is dropped only once. Assigning the same dropped count to every rule causes double counting.

The common model should therefore distinguish:

```text
Rule-level metrics:
passed_records
failed_records

Flow-level metrics:
dropped_records
output_records
flow_status
```

## The special case of fail

Expectation tracking metrics are available for `Warn` and `Drop`.

With `Fail`, execution stops when the first invalid record is detected. Databricks notes that complete expectation metrics are not recorded for this action.

This affects historical modeling:

- A failed update must not be interpreted as zero failed rows.
- The technical failure must be derived from update, flow or error events.
- The affected rule can be mapped through the error message, dataset and expectation context.
- `Rows Tested`, `Rows Failed` and `Failure Rate` may remain unknown.
- A separate status such as `Failed Early` or `Execution Failed` is useful.

A robust model separates:

| Field | Meaning |
| --- | --- |
| **Quality Status** | Passed, Warning, Violated, Dropped |
| **Execution Status** | Completed, Failed, Cancelled, Skipped |
| **Action** | Warn, Drop, Fail |
| **Metric Completeness** | Complete, Partial, Not Available |

This prevents an early abort from appearing as a fully measured quality execution.

## Transform the event log into DQ history

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test4-img2-en.png"
        alt="Transformation of Databricks pipeline expectations through the event log and Delta data into a common DQ history model used by BI, monitoring, alerts and root cause analysis"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The event log is a technical event source. A curated DQ history turns it into stable, business-enriched result rows for Qlik, Power BI and governance processes.
    </figcaption>
</figure>

The event log is already historical. BI should still not be built directly on its raw schema.

Reasons include:

- nested JSON structures
- technical event types
- several events per pipeline update
- cumulative or repeated metrics
- different grains
- missing business owner and severity information
- version-dependent details
- fail cases without complete expectation metrics

The recommended architecture is:

```flowchart
Pipeline Event Log
Raw Event View
Expectation Metrics Model
DQ History Delta Table
Governed DQ View
Qlik / Power BI
```

## Extract expectation metrics

A simplified SQL pattern:

```sql
WITH expectation_events AS (
    SELECT
        timestamp AS event_time,
        origin.pipeline_id AS pipeline_id,
        origin.pipeline_name AS pipeline_name,
        origin.update_id AS update_id,
        origin.flow_name AS flow_name,
        details:flow_progress.status::STRING AS flow_status,
        TRY_CAST(
            details:flow_progress.data_quality.dropped_records
            AS BIGINT
        ) AS dropped_records,
        EXPLODE(
            FROM_JSON(
                details:flow_progress.data_quality.expectations,
                'ARRAY<STRUCT<
                    name: STRING,
                    dataset: STRING,
                    passed_records: BIGINT,
                    failed_records: BIGINT
                >>'
            )
        ) AS expectation
    FROM governance.event_log_raw
    WHERE event_type = 'flow_progress'
      AND details:flow_progress.data_quality.expectations IS NOT NULL
)
SELECT
    pipeline_id,
    pipeline_name,
    update_id,
    flow_name,
    expectation.dataset AS dataset,
    expectation.name AS expectation_name,
    MAX(event_time) AS executed_at,
    MAX_BY(flow_status, event_time) AS final_flow_status,
    SUM(expectation.passed_records) AS rows_passed,
    SUM(expectation.failed_records) AS rows_failed,
    SUM(
        expectation.passed_records
        + expectation.failed_records
    ) AS rows_tested
FROM expectation_events
GROUP BY
    pipeline_id,
    pipeline_name,
    update_id,
    flow_name,
    expectation.dataset,
    expectation.name;
```

The pattern follows the documented event log fields. Validate the following in the actual environment:

- whether events contain incremental or cumulative metrics
- which pipeline version is in use
- whether retries create duplicate counts
- which final flow status is authoritative
- whether the event log is published or hidden
- how standalone streaming tables and materialized views are connected

Production history logic must be validated against real pipeline runs.

## Map to the common DQ model

Databricks results map to the same structure used by the other platforms in this series.

| Common field | Databricks mapping |
| --- | --- |
| **Run ID** | Pipeline `update_id` |
| **Rule ID** | Registry ID or combination of pipeline, dataset and expectation |
| **Platform** | Databricks |
| **Data Product** | Rule registry or Unity Catalog metadata |
| **Table** | Dataset or flow |
| **Column** | Rule registry because it may not be present in the event log |
| **Test Type** | Expectation |
| **Status** | derived from metric, action and execution status |
| **Rows Tested** | Passed + Failed |
| **Rows Failed** | `failed_records` |
| **Failure Rate** | Failed / Tested |
| **Executed At** | event or update timestamp |
| **Owner** | Rule registry |
| **Severity** | Rule registry |

Databricks-specific extensions:

| Field | Purpose |
| --- | --- |
| **Pipeline ID** | technical origin |
| **Pipeline Name** | readable origin |
| **Flow Name** | affected flow |
| **Dataset** | expectation dataset |
| **Expectation Name** | technical rule name |
| **Action** | Warn, Drop or Fail |
| **Rows Passed** | valid records |
| **Rows Dropped** | dropped records at flow level |
| **Flow Status** | technical final status |
| **Metric Completeness** | complete, partial or unavailable |
| **Event ID** | technical traceability |
| **Event Details** | controlled additional context |

## Delta table for historical results

A possible target schema:

```sql
CREATE TABLE IF NOT EXISTS governance.dq_test_history (
    run_id               STRING,
    rule_id              STRING,
    platform              STRING,
    data_product          STRING,
    table_name            STRING,
    column_name           STRING,
    test_type             STRING,
    test_status           STRING,
    rows_tested           BIGINT,
    rows_failed           BIGINT,
    failure_rate          DECIMAL(18,8),
    executed_at           TIMESTAMP,
    owner_name            STRING,
    severity              STRING,

    pipeline_id           STRING,
    pipeline_name         STRING,
    flow_name             STRING,
    dataset_name          STRING,
    expectation_name      STRING,
    expectation_action    STRING,
    rows_passed           BIGINT,
    rows_dropped          BIGINT,
    execution_status      STRING,
    metric_completeness   STRING,
    source_event_id       STRING,
    message               STRING
)
USING DELTA;
```

The table is append-oriented.

A pipeline update must not overwrite earlier results. A possible technical key is:

```text
Pipeline ID
+ Update ID
+ Flow Name
+ Expectation Name
```

Retries may require the event ID or sequence as an additional key.

## Derive the status

A possible rule set:

```text
Action = Warn
Failed Records = 0
→ Passed

Action = Warn
Failed Records > 0
→ Warning

Action = Drop
Failed Records = 0
→ Passed

Action = Drop
Failed Records > 0
→ Failed / Filtered

Action = Fail
Execution Status = Failed
→ Failed Early

Technical pipeline error without a confirmed rule violation
→ Error
```

Data quality and technical execution should remain separate.

Example:

| Quality Status | Execution Status | Interpretation |
| --- | --- | --- |
| Passed | Completed | rule met |
| Warning | Completed | invalid data was retained |
| Filtered | Completed | invalid data was removed |
| Failed Early | Failed | hard expectation stopped the flow |
| Unknown | Failed | technical failure without confirmed rule violation |

## Warn, drop and fail are not severity levels

The action describes what the pipeline does technically.

Severity describes how critical the rule is to the business.

The concepts can be related but are not identical.

| Severity | Possible action |
| --- | --- |
| Low | Warn |
| Medium | Warn or Drop |
| High | Drop or Fail |
| Critical | often Fail |

A critical rule may deliberately begin in warn mode to understand the data baseline. A technically droppable row can also have low business criticality.

Store both fields separately in the rule registry and historical result row.

## Quarantine instead of silent removal

Not every invalid record should disappear completely.

A quarantine pattern separates valid and invalid records:

```flowchart
Source Data
Validation View
Valid Records
Quarantine Records
Curated Target
Remediation
```

Databricks documents patterns using temporary views and separate processing paths.

Benefits include:

- root-cause analysis remains possible
- business keys remain traceable
- corrected records can be reprocessed
- drop counts have operational evidence
- Data Stewards can work on specific failures

The same governance requirements apply as for other failure-detail tables:

- minimize PII
- mask sensitive values
- restrict access
- define retention
- cap large failure volumes
- document correction and reprocessing

## Do not put BI directly on the raw event log

The event log can be queried directly. A curated layer is still preferable for a durable Qlik or Power BI model.

```flowchart
Databricks Event Log
DQ History Delta Table
DQ Monitoring View
Qlik / Power BI
```

The monitoring view can:

- normalize technical statuses
- add Rule IDs
- enrich owner and severity
- flag fail cases
- standardize warn, drop and fail semantics
- separate expectation and flow grains
- filter development pipelines
- assign Data Products
- provide current and historical perspectives

## Qlik analysis

Row-weighted failure rate:

```qlik
Num(
    Sum(rows_failed)
    /
    Sum(rows_tested),
    '0.00%'
)
```

Warnings:

```qlik
Count({
    <test_status = {'Warning'}>
} DISTINCT rule_id & '|' & run_id)
```

Dropped rows:

```qlik
Sum(rows_dropped)
```

Failed hard expectations:

```qlik
Count({
    <test_status = {'Failed Early'}>
} DISTINCT rule_id & '|' & run_id)
```

## Power BI analysis

```DAX
Failure Rate =
DIVIDE(
    SUM(DQ_History[Rows Failed]),
    SUM(DQ_History[Rows Tested])
)
```

```DAX
Warning Rules =
CALCULATE(
    DISTINCTCOUNT(DQ_History[Rule Run Key]),
    DQ_History[Test Status] = "Warning"
)
```

```DAX
Dropped Rows =
SUM(DQ_History[Rows Dropped])
```

```DAX
Failed Expectations =
CALCULATE(
    DISTINCTCOUNT(DQ_History[Rule Run Key]),
    DQ_History[Test Status] = "Failed Early"
)
```

Do not aggregate `Rows Dropped` at expectation grain if the same flow-level value has been duplicated across every expectation. A separate flow fact or unique flow-run metric is safer.

## Two facts instead of one overloaded table

For larger implementations, a two-fact model is more robust.

### `DQ_EXPECTATION_RESULT`

Grain:

```text
One row per pipeline update, flow and expectation
```

Contains:

- passed records
- failed records
- Rule ID
- Action
- Owner
- Severity

### `DQ_FLOW_RESULT`

Grain:

```text
One row per pipeline update and flow
```

Contains:

- dropped records
- output records
- execution status
- duration
- pipeline and flow metadata

A shared BI layer connects both facts through dimensions such as:

- Run
- Pipeline
- Flow
- Dataset
- Data Product
- Time
- Owner
- Rule

## Alerts and operational action

Databricks provides several ways to trigger notifications and actions:

- pipeline notifications
- SQL alerts on curated DQ views
- Lakeflow Jobs
- event hooks
- webhooks or downstream integrations
- Qlik or Power BI monitoring processes
- ticket and workflow systems

A useful operational loop is:

```flow linear vertical
Detect expectation violation
Assess action and severity
Assign owner
Inspect failure details or quarantine
Correct the cause
Run the pipeline or flow again
Record the historical result
Continue monitoring the trend
```

Alert logic should not react only to `Failed`.

Examples:

- warning failure rate rises for three consecutive runs
- drop rate exceeds 0.5 percent
- critical expectation triggers fail
- event log provides no metrics
- dataset suddenly produces no rows
- the same Rule ID fails in several Data Products
- owner does not respond within the SLA

## Practical implementation sequence

A reliable prototype can be created in ten steps:

1. Select a Lakeflow pipeline containing a streaming table or materialized view.
2. Define three expectations: required field, value range and accepted status.
3. Configure one rule as warn.
4. Configure one rule as drop.
5. Configure one critical rule as fail.
6. Publish the event log or create a governed view.
7. Extract expectation metrics from `flow_progress`.
8. Create a rule registry with owner, severity and action.
9. Append results to a Delta history table.
10. Put Qlik or Power BI on a curated monitoring view.

## Common design mistakes

### Treating the event log as a ready-made BI model

The event log is a technical event source with several grains and nested structures.

### Treating fail as a fully measured test

Fail does not provide the same complete expectation metrics as warn and drop.

### Assigning dropped records to every expectation

Dropped records exist at flow level. Repeating them at rule level creates double counting.

### Showing warn as passed

A technically successful flow can still contain invalid data.

### Interpreting drop as error-free quality

The target is cleaner, but errors occurred and records were removed.

### Expressing uniqueness as a simple row-level expectation

Cross-row uniqueness requires aggregation, window logic or a separate test path.

### Frequently renaming expectations

This breaks time series and mappings to central Rule IDs.

### Maintaining owner and severity only in the dashboard

Governance metadata should be versioned and snapshotted into history at execution time.

## The central conclusion

> **Databricks expectations connect quality rules directly to streaming tables and materialized views. The event log makes many outcomes technically queryable. A curated DQ history is still required to make them comparable across platforms and operationally manageable.**

Warn, drop and fail solve different problems:

- Warn creates transparency
- Drop protects downstream datasets
- Fail protects critical processes

The event log provides:

- expectation metrics
- flow and update states
- technical failures
- lineage and runtime context

The common DQ model adds:

- stable Rule IDs
- Data Products
- owners
- severity
- historical statuses
- Qlik- and Power-BI-ready metrics
- remediation and re-testing

## Related playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [Part 2 — Data Quality in Microsoft Fabric](/playbooks/dq-test2)
- [Part 3 — Data Quality with dbt](/playbooks/dq-test3)
- [The Missing Pieces — Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality & Governance](/playbooks/data-quality-governance)

## Sources and further reading

- [Databricks — Manage Data Quality with Pipeline Expectations](https://docs.databricks.com/aws/en/ldp/expectations)
- [Databricks — Expectation Recommendations and Advanced Patterns](https://docs.databricks.com/aws/en/ldp/expectation-patterns)
- [Databricks — Expectations Python API](https://docs.databricks.com/aws/en/ldp/developer/ldp-python-ref-expectations)
- [Databricks — Develop Lakeflow Pipeline Code with SQL](https://docs.databricks.com/aws/en/ldp/developer/sql-dev)
- [Databricks — Develop Lakeflow Pipeline Code with Python](https://docs.databricks.com/aws/en/ldp/developer/python-dev)
- [Databricks — Pipeline Event Log](https://docs.databricks.com/aws/en/ldp/monitor-event-logs)
- [Databricks — Pipeline Event Log Schema](https://docs.databricks.com/aws/en/ldp/monitor-event-log-schema)
- [Databricks — Monitor Pipelines](https://docs.databricks.com/aws/en/ldp/observability)
- [Databricks — Lakeflow Spark Declarative Pipelines](https://docs.databricks.com/aws/en/ldp/)
- [Databricks — What Happened to Delta Live Tables?](https://docs.databricks.com/aws/en/ldp/concepts/where-is-dlt)
- [Databricks — event_log Table-Valued Function](https://docs.databricks.com/aws/en/sql/language-manual/functions/event_log)

> **Feature status:** July 2026. Databricks continues to evolve Lakeflow, Spark Declarative Pipelines, event log schemas and monitoring capabilities. Verify syntax, availability and preview status against the current documentation before implementation.
