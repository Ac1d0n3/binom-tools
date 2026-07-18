---
title: Data Quality in Microsoft Fabric — Operationalizing Tests and Centralizing Results
description: How to execute data quality tests in Fabric Lakehouse and Warehouse with SQL, notebooks, pipelines, Materialized Lake Views and Microsoft Purview, then store standardized Delta or Warehouse results for Qlik and Power BI.
category: Data Quality
tags:
  - data-quality
  - operational-data-quality
  - microsoft-fabric
  - fabric-lakehouse
  - fabric-warehouse
  - onelake
  - delta-lake
  - materialized-lake-views
  - microsoft-purview
  - notebooks
  - pipelines
  - qlik-sense
  - power-bi
  - data-governance
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 2
seriesTitle: Operational Data Quality
hero: images/playbooks/dq-test2-hero.png
---

## Fabric provides several test mechanisms — the result still needs one shared contract

Microsoft Fabric does not provide only one data quality mechanism.

Depending on the workload, quality rules can be executed through:

- T-SQL in Fabric Warehouse
- SQL against Lakehouse tables
- PySpark or Spark SQL in Fabric notebooks
- Data Factory pipelines
- Stored Procedure or Script activities
- Materialized Lake Views with declarative constraints
- Microsoft Purview Data Quality scans for Fabric Lakehouses

These mechanisms have different strengths. They do not automatically produce the same operational result format.

A notebook may write a Delta table. A stored procedure may update a Warehouse table. A Materialized Lake View records constraint violations in Fabric system metrics. Purview calculates quality scores and can publish failing records to managed storage.

Qlik, Power BI, ownership and remediation still need a common result structure.

> **Fabric executes rules where the data lives. A standardized DQ result table makes those executions measurable together.**

```flowchart
Fabric Lakehouse / Warehouse
Quality Rules
SQL / Notebook / Pipeline / MLV / Purview
Standardized DQ Result
Qlik / Power BI
Ownership and Remediation
```

## Lakehouse or Warehouse as the execution and result layer

Fabric Lakehouse and Fabric Warehouse store data in OneLake and use Delta as the common storage foundation. Their development models differ.

| Decision | Lakehouse | Warehouse |
| --- | --- | --- |
| Primary development approach | Spark, Python, SQL, notebooks | T-SQL, views, stored procedures |
| Typical data | structured and semi-structured | primarily structured |
| Suitable DQ execution | large datasets, complex rules, data engineering | SQL-based rules, relational models, BI-oriented tests |
| Native result storage | Delta table | Warehouse table |
| Typical orchestrator | Notebook activity, pipeline | Stored Procedure or Script activity |

The central DQ table should not be maintained twice without a clear reason.

A practical decision is:

- **Delta table in the Lakehouse** when most rules run in Spark or notebooks and other engines should consume open Delta data.
- **Warehouse table** when the environment is SQL-first, results are modeled relationally and BI teams primarily consume the SQL endpoint.
- **Consolidation layer** when several workspaces, Lakehouses or Warehouses create local results.

```flow linear vertical
Local test execution
Local or direct result row
Central Fabric DQ table
Qlik and Power BI model
```

## The Fabric Data Quality Execution Flow

Test execution should not be treated as a reporting step added after the data process. It belongs inside the process.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test2-img1-en.png"
        alt="Microsoft Fabric data quality execution flow from source data through Lakehouse or Warehouse, quality rules and SQL, notebook or pipeline tests to a central DQ result table and monitoring in Qlik and Power BI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Rules may be executed by different Fabric workloads. Every successful or technically failed execution must then create a standardized result row.
    </figcaption>
</figure>

A reliable flow contains six steps:

1. Data is provided in a Lakehouse or Warehouse.
2. A rule defines the expected quality state.
3. SQL, notebook, pipeline, Materialized Lake View or Purview executes the check.
4. The execution calculates measurable metrics.
5. One result row is written to the central Delta or Warehouse table.
6. Qlik and Power BI use the table for monitoring and action.

The pipeline is not necessarily the test engine. It can orchestrate execution, pass parameters and ensure that all activities use a shared `Run ID`.

## The four example rules

Four common rules are enough for an initial Fabric prototype.

| Rule ID | Rule | Grain | Measurement |
| --- | --- | --- | --- |
| **DQ-001** | Required field | Column | Number of records containing `NULL` or an empty value |
| **DQ-002** | Value range | Column | Number of values outside the accepted range |
| **DQ-003** | Duplicates | Key or column combination | Number of additional records for keys occurring more than once |
| **DQ-004** | Freshness | Table or Data Product | Time since the last successful update |

The metric definition must be explicit.

For duplicates, `Rows Failed` may mean:

- every record whose key occurs more than once, or
- only additional records beyond the first occurrence.

Both approaches are valid. The definition must remain stable for the same `Rule ID`.

For freshness, `Rows Tested` is often not the number of business records. The rule evaluates the state of a data object. A useful convention is:

```text
Rows Tested = 1
Rows Failed = 0 or 1
```

## The standardized result structure in Fabric

The core structure from Part 1 remains unchanged:

| Field | Purpose |
| --- | --- |
| **Run ID** | Shared execution of a pipeline, notebook or batch |
| **Rule ID** | Stable identity of the rule |
| **Platform** | For example `Microsoft Fabric` |
| **Data Product** | Business context |
| **Table** | Tested data object |
| **Column** | Tested column where applicable |
| **Test Type** | Required field, range, duplicate, freshness or another rule type |
| **Status** | Passed, Failed, Error or Skipped |
| **Rows Tested** | Scope of the check |
| **Rows Failed** | Number of rule violations |
| **Failure Rate** | `Rows Failed / Rows Tested` |
| **Executed At** | Execution timestamp |
| **Owner** | Accountable role or team |
| **Severity** | Criticality |

Additional technical fields are useful in Fabric:

| Optional field | Value |
| --- | --- |
| **Execution Method** | SQL, Notebook, Pipeline, MLV or Purview |
| **Source Run ID** | Original pipeline, notebook, MLV or scan identifier |
| **Rule Name** | Readable display name |
| **Rule Version** | Traceable version of the logic |
| **Threshold Value** | Threshold used during execution |
| **Duration Seconds** | Test runtime |
| **Executed By** | Service principal, pipeline or user |
| **Message** | Brief technical or business summary |
| **Details URI** | Controlled link to failure evidence |

> **Core fields form the platform-neutral contract. Extension fields provide Fabric-specific traceability.**

## Option 1: SQL tests in Fabric Warehouse

For SQL-first teams, a stored procedure or Script activity is often the most direct implementation.

A result table in the Warehouse could be defined as follows:

```sql
CREATE TABLE governance.dq_test_result (
    run_id             VARCHAR(100)  NOT NULL,
    rule_id            VARCHAR(100)  NOT NULL,
    platform_name      VARCHAR(100)  NOT NULL,
    execution_method   VARCHAR(50)   NOT NULL,
    data_product       VARCHAR(200)  NOT NULL,
    table_name         VARCHAR(256)  NOT NULL,
    column_name        VARCHAR(256)  NULL,
    test_type          VARCHAR(100)  NOT NULL,
    test_status        VARCHAR(30)   NOT NULL,
    rows_tested        BIGINT        NULL,
    rows_failed        BIGINT        NULL,
    failure_rate       DECIMAL(18,8) NULL,
    executed_at        DATETIME2(6)  NOT NULL,
    owner_name         VARCHAR(200)  NULL,
    severity           VARCHAR(30)   NULL,
    duration_seconds   DECIMAL(18,3) NULL,
    executed_by        VARCHAR(200)  NULL,
    message            VARCHAR(1000) NULL
);
```

### Required-field test as a measurable SQL execution

```sql
WITH test_metrics AS (
    SELECT
        COUNT_BIG(*) AS rows_tested,
        SUM(
            CASE
                WHEN customer_id IS NULL THEN CAST(1 AS BIGINT)
                ELSE CAST(0 AS BIGINT)
            END
        ) AS rows_failed
    FROM silver.customer
)
INSERT INTO governance.dq_test_result (
    run_id,
    rule_id,
    platform_name,
    execution_method,
    data_product,
    table_name,
    column_name,
    test_type,
    test_status,
    rows_tested,
    rows_failed,
    failure_rate,
    executed_at,
    owner_name,
    severity,
    executed_by,
    message
)
SELECT
    'RUN_20260718_081530',
    'DQ-001',
    'Microsoft Fabric',
    'Warehouse SQL',
    'Customer 360',
    'CUSTOMER',
    'CUSTOMER_ID',
    'Not Null',
    CASE WHEN rows_failed = 0 THEN 'Passed' ELSE 'Failed' END,
    rows_tested,
    rows_failed,
    CAST(rows_failed AS DECIMAL(18,8))
        / NULLIF(CAST(rows_tested AS DECIMAL(18,8)), 0),
    SYSUTCDATETIME(),
    'Data Steward CRM',
    'High',
    'dq-fabric-pipeline',
    CONCAT(rows_failed, ' null customer IDs found')
FROM test_metrics;
```

The test performs three operations within one controlled flow:

1. It determines the test scope.
2. It counts failures.
3. It persists the result.

### Value range

```sql
SELECT
    COUNT_BIG(*) AS rows_tested,
    SUM(
        CASE
            WHEN amount < 0 OR amount > 1000000
                THEN CAST(1 AS BIGINT)
            ELSE CAST(0 AS BIGINT)
        END
    ) AS rows_failed
FROM silver.fact_sales;
```

### Duplicates

The following pattern counts additional rows beyond the first occurrence:

```sql
WITH key_counts AS (
    SELECT
        order_id,
        COUNT_BIG(*) AS key_count
    FROM silver.fact_sales
    GROUP BY order_id
)
SELECT
    (SELECT COUNT_BIG(*) FROM silver.fact_sales) AS rows_tested,
    COALESCE(
        SUM(
            CASE
                WHEN key_count > 1 THEN key_count - 1
                ELSE 0
            END
        ),
        0
    ) AS rows_failed
FROM key_counts;
```

### Freshness

```sql
SELECT
    CAST(1 AS BIGINT) AS rows_tested,
    CASE
        WHEN MAX(load_timestamp) >= DATEADD(HOUR, -24, SYSUTCDATETIME())
            THEN CAST(0 AS BIGINT)
        ELSE CAST(1 AS BIGINT)
    END AS rows_failed
FROM governance.load_log
WHERE data_product = 'Sales';
```

A pipeline can call the stored procedure with parameters. The Fabric Stored Procedure activity supports Fabric Data Warehouse and can import procedure parameters. A Script activity can execute T-SQL directly.

## Option 2: Notebook tests in Fabric Lakehouse

Notebooks are suitable for:

- PySpark rules
- large Delta tables
- complex business logic
- profiling and statistical checks
- reusable Python functions
- rules combining several data sources

A basic required-field test can be implemented as follows:

```python
from datetime import datetime, timezone
from pyspark.sql import Row
from pyspark.sql import functions as F

run_id = "RUN_20260718_081530"
rule_id = "DQ-001"

source_df = spark.table("silver.customer")

rows_tested = source_df.count()
rows_failed = (
    source_df
    .filter(F.col("customer_id").isNull())
    .count()
)

failure_rate = (
    rows_failed / rows_tested
    if rows_tested > 0
    else None
)

status = (
    "Error"
    if rows_tested == 0
    else "Passed" if rows_failed == 0
    else "Failed"
)

result = Row(
    run_id=run_id,
    rule_id=rule_id,
    platform_name="Microsoft Fabric",
    execution_method="Notebook / PySpark",
    data_product="Customer 360",
    table_name="CUSTOMER",
    column_name="CUSTOMER_ID",
    test_type="Not Null",
    test_status=status,
    rows_tested=rows_tested,
    rows_failed=rows_failed,
    failure_rate=failure_rate,
    executed_at=datetime.now(timezone.utc),
    owner_name="Data Steward CRM",
    severity="High",
    executed_by="dq-fabric-pipeline",
    message=f"{rows_failed} null customer IDs found"
)

(
    spark.createDataFrame([result])
    .write
    .format("delta")
    .mode("append")
    .saveAsTable("governance.dq_test_result")
)
```

The notebook should not be copied in full for every rule. A reusable function or lightweight framework is preferable:

```python
execute_rule(
    rule_id="DQ-001",
    data_product="Customer 360",
    table_name="silver.customer",
    column_name="customer_id",
    test_type="not_null",
    severity="High",
    owner="Data Steward CRM",
    run_id=run_id
)
```

The function can manage:

- rule parameters
- metric calculation
- threshold evaluation
- error handling
- result writing
- optional failure-detail writing

## Option 3: Pipeline as the orchestration framework

Fabric Data Factory pipelines coordinate and automate the process.

A pipeline may implement:

```flowchart
Generate Run ID
Execute SQL Tests
Execute Notebook Tests
Collect Technical Errors
Write Result Rows
Refresh Semantic Model
Send Alert
```

Typical activities include:

- Notebook activity
- Stored Procedure activity
- Script activity
- Dataflow Gen2 activity
- Lookup and ForEach activities for metadata-driven rules
- downstream notification or workflow activities

The pipeline should pass a shared `Run ID` to every test activity. This can be the unique pipeline execution identifier or a separate business batch identifier.

A metadata-driven implementation may use a rule registry:

| Rule ID | Method | Object | Column | Test Type | Threshold | Active |
| --- | --- | --- | --- | --- | ---: | --- |
| DQ-001 | Warehouse SQL | CUSTOMER | CUSTOMER_ID | Not Null | 0 | Yes |
| DQ-002 | Notebook | FACT_SALES | AMOUNT | Range | 0.001 | Yes |
| DQ-003 | Warehouse SQL | FACT_SALES | ORDER_ID | Uniqueness | 0 | Yes |
| DQ-004 | Notebook | LOAD_LOG | LOAD_TS | Freshness | 24h | Yes |

The pipeline reads active rules, branches by `Method` and passes the same governance parameters to each technical implementation.

> **The pipeline orchestrates the run. The rule engine calculates quality. The result table records both.**

## Materialized Lake Views: declarative rules inside the transformation

Materialized Lake Views add a declarative approach to Fabric.

The transformation is defined as SQL. Fabric materializes the output as a Delta table, manages dependencies and handles refresh.

Data quality rules can be declared directly as constraints:

```sql
CREATE OR REPLACE MATERIALIZED LAKE VIEW silver.sales_valid (
    CONSTRAINT order_id_required
        CHECK (order_id IS NOT NULL)
        ON MISMATCH FAIL,

    CONSTRAINT amount_non_negative
        CHECK (amount >= 0)
        ON MISMATCH DROP
)
AS
SELECT
    order_id,
    customer_id,
    product_id,
    amount,
    order_timestamp
FROM bronze.sales;
```

Fabric supports two primary responses:

- **FAIL** stops the refresh at the first constraint violation and is the default behavior.
- **DROP** continues processing and removes violating records from the output.

When `DROP` and `FAIL` constraints exist in the same Materialized Lake View, `FAIL` takes precedence when the corresponding violation occurs.

This pattern is particularly useful when quality is part of the medallion transformation:

```flow linear vertical
Bronze Delta Table
Materialized Lake View with Constraints
Validated Silver Delta Table
Gold Data Product
```

### Native MLV metrics as an input to the central DQ table

Fabric exposes execution, quality and error metrics for Materialized Lake Views.

The Lakehouse contains system tables under the `_mlv_system` schema:

- `sys_run_metrics`
- `sys_node_metrics`
- `sys_error_metrics`

Fabric can also generate an integrated Data Quality report. It includes trends, constraint violations and dropped records. Fabric creates a semantic model and Power BI report in the workspace for this purpose.

These native functions are valuable. For a cross-workload Operational DQ model, the relevant MLV metrics should still be normalized into the shared result contract:

```flowchart
MLV Constraint Metrics
Normalization Job
DQ_TEST_RESULT
Qlik / Power BI
```

A possible mapping is:

| Standard field | MLV context |
| --- | --- |
| Run ID | MLV refresh run |
| Rule ID | stable constraint name or mapping ID |
| Platform | Microsoft Fabric |
| Execution Method | Materialized Lake View |
| Table | Materialized Lake View |
| Test Type | Constraint |
| Rows Tested | rows evaluated during refresh |
| Rows Failed | violations or drops according to the defined metric |
| Executed At | refresh timestamp |
| Message | refresh or constraint error |

Violations and drops must not be treated as identical. One record can violate several constraints but is dropped only once. The result design must document which metric populates `Rows Failed`.

## Purview Data Quality for Fabric Lakehouse

Microsoft Purview Unified Catalog can profile Fabric Lakehouse data, apply quality rules and run DQ scans.

This creates an additional governance-oriented layer:

```flowchart
Fabric Lakehouse Table
Purview Profiling
Purview DQ Rules
DQ Scan and Score
Alerts / Error Records
```

Purview is particularly suitable for:

- centrally managed rules in governance domains
- quality assessment of data assets and data products
- rule-, asset- and product-level thresholds
- Data Steward workflows
- quality scores
- scheduled scans
- publishing failing records to a controlled error sink

According to Microsoft documentation, relevant prerequisites for Fabric Lakehouse include:

- Tables must use Delta or Iceberg format.
- The Data Map scan must have completed successfully.
- The Purview Managed Identity needs Contributor access to the Fabric workspace.
- The data quality connection uses a Fabric connection with Purview MSI.
- The data asset is typically associated with a data product in a governance domain.

Purview can publish failing rows from data quality scans to managed storage. A Fabric Lakehouse can be used as the storage location for cloud data sources.

### Purview complements the operational test process

Purview and technical pipeline tests should not execute the same rule twice without control.

A useful separation is:

| Layer | Responsibility |
| --- | --- |
| **Pipeline, SQL and notebook tests** | immediate technical release decision for a load or data product |
| **Materialized Lake View constraints** | quality during declarative transformation and refresh |
| **Purview DQ scans** | governance assessment, profiling, scorecards and stewardship |
| **Central DQ table** | shared history across execution methods |

The rule registry should therefore also document:

- leading execution source
- technical implementation
- Purview rule or scan mapping
- MLV constraint
- required frequency
- deduplication logic

## The Fabric DQ Results Table

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test2-img2-en.png"
        alt="Standardized Fabric Data Quality result table with run, rule, data product, tested and failed rows, failure rate, duration, owner, severity and message, together with Fabric execution sources and consumption in Qlik and Power BI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        SQL, notebooks, pipelines, Materialized Lake Views and Purview may produce different raw outputs. Monitoring and ownership require them to be mapped to the same result structure.
    </figcaption>
</figure>

The table is append-oriented.

Every test execution creates a new row. Existing results are not overwritten with the latest status.

This enables:

- rule-level time series
- comparison across Data Products
- failure development by Severity
- tracking of technical Errors
- runtime analysis
- ownership at execution time
- confirmation that remediation improved the next run

### Status model

Fabric implementations should use at least these statuses:

| Status | Meaning |
| --- | --- |
| **Passed** | Rule executed and threshold was met |
| **Failed** | Rule executed and threshold was violated |
| **Error** | SQL, notebook, pipeline, MLV refresh or scan failed technically |
| **Skipped** | Execution was deliberately omitted |
| **Dropped** | optional for rules that deliberately remove violating records |

`Dropped` does not always need to be a test status. For MLVs, it may be better to classify the rule as `Failed` or `Passed With Violations` and store the number of dropped records in a separate metric.

The exact names matter less than organization-wide consistency.

## Store failure details separately

The aggregated result table is optimized for monitoring.

Failing records belong in a separate structure:

```text
DQ_TEST_RESULT
One row per rule execution

DQ_TEST_FAILURE_DETAIL
Zero to many failing records per rule execution
```

In Fabric, the detail structure may be stored as:

- a Delta table in the Lakehouse
- a restricted Warehouse table
- Purview Error Records in managed storage
- a controlled OneLake path

A possible structure is:

```sql
CREATE TABLE governance.dq_test_failure_detail (
    run_id          VARCHAR(100)  NOT NULL,
    rule_id         VARCHAR(100)  NOT NULL,
    record_key      VARCHAR(500)  NULL,
    failure_code    VARCHAR(100)  NULL,
    failure_message VARCHAR(1000) NULL,
    detected_value  VARCHAR(1000) NULL,
    detected_at     DATETIME2(6)  NOT NULL
);
```

This layer requires stricter controls:

- avoid unnecessary PII duplication
- mask or hash sensitive values
- restrict access
- define retention
- cap very large failure sets
- use a `Details URI` instead of full values in the result table

## Put Qlik and Power BI on one governed result layer

Qlik and Power BI should not implement separate logic for every technical test source.

A better pattern is:

```flowchart
Fabric DQ Result Table
Governed DQ View
Qlik / Power BI
```

A curated view can:

- standardize technical field names
- normalize status values
- enrich owner and Data Product
- map MLV and Purview metrics
- exclude development executions
- provide current and historical perspectives

Typical analytics include:

- Failure Rate over Time
- Failed Rules by Data Product
- Critical Rules by Owner
- MLV Constraint Violations
- Purview Quality Score
- Technical Errors by Execution Method
- Test Duration and Capacity Hotspots
- Re-Test after Remediation

The row failure rate remains weighted:

```text
Sum(Rows Failed) / Sum(Rows Tested)
```

Qlik:

```qlik
Num(
    Sum(rows_failed) / Sum(rows_tested),
    '0.00%'
)
```

Power BI:

```DAX
Failure Rate =
DIVIDE(
    SUM(DQ_Test_Result[Rows Failed]),
    SUM(DQ_Test_Result[Rows Tested])
)
```

Purview quality scores and row-based failure rates are different metrics. They should not be blended into one percentage.

## Recommended Fabric target architecture

A production-oriented starting structure is:

```flow linear vertical
Governance.DQ_RULE
Governance.DQ_TEST_RESULT
Governance.DQ_TEST_FAILURE_DETAIL
Governance.V_DQ_MONITORING
Qlik / Power BI
```

### `DQ_RULE`

Manages the rule definition:

- Rule ID
- business description
- Data Product
- table and column
- Test Type
- threshold
- Severity
- Owner
- Execution Method
- implementation reference
- version
- active status

### `DQ_TEST_RESULT`

Stores one row per execution.

### `DQ_TEST_FAILURE_DETAIL`

Stores controlled evidence for remediation.

### `V_DQ_MONITORING`

Provides a stable, BI-friendly model.

## A practical Fabric starting point

An initial sprint can remain small:

1. Select one Lakehouse or Warehouse as the canonical DQ store.
2. Create the central `DQ_TEST_RESULT` table.
3. Implement four rules: required field, value range, duplicates and freshness.
4. Implement one SQL execution and one notebook execution.
5. Use a pipeline to generate the shared `Run ID` and start both execution methods.
6. Store results append-only.
7. Build a small Qlik or Power BI model on the table.
8. Expose Owner and Severity.
9. Optionally add a Materialized Lake View constraint.
10. Optionally configure a Purview DQ scan for the same Data Product and define clear responsibilities.

This creates a complete vertical prototype:

```flowchart
Rule
Execution
Result
Dashboard
Owner
Remediation
```

## Common design mistakes

### Monitoring only pipeline status

A green pipeline only means the technology executed. It does not prove that the data is correct.

### Overwriting test results

This removes history, trends and auditability.

### Maintaining both Lakehouse and Warehouse as master result stores

This creates conflicting results. One canonical result source is required.

### Treating Purview score and Failure Rate as the same metric

A governance score and the share of failing rows answer different questions.

### Hiding MLV drops

Removing invalid rows can make Silver data appear clean. Violations and dropped-row counts must remain visible.

### Treating `Rows Tested = 0` as passed

An empty test scope may be a technical or business failure and needs explicit handling.

### Executing and counting the same rule several times

A SQL test, MLV constraint and Purview rule may validate the same risk. Without a leading source and mapping, results are counted more than once.

## The central conclusion

> **Microsoft Fabric provides several ways to execute data quality. Operational Data Quality emerges only when relevant executions are converted into a stable result contract.**

SQL, notebooks and pipelines provide flexible technical tests.

Materialized Lake Views integrate declarative constraints directly into transformation and refresh.

Microsoft Purview adds profiling, rules, scores, scans and stewardship.

The Delta or Warehouse result table connects these capabilities to:

- Qlik and Power BI
- Data Products
- owners and Severity
- historical trends
- technical failures
- failure details
- remediation and re-testing

## Related playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [The Missing Pieces — Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality & Governance](/playbooks/data-quality-governance)
- [Cloud Hosting Models for Data Platforms](/playbooks/cloud-hosting-models-data-platforms)

## Sources and further reading

- [Microsoft Fabric — What is a Lakehouse?](https://learn.microsoft.com/en-us/fabric/data-engineering/lakehouse-overview)
- [Microsoft Fabric — Lakehouse and Delta Tables](https://learn.microsoft.com/en-us/fabric/data-engineering/lakehouse-and-delta-tables)
- [Microsoft Fabric — Choose between Warehouse and Lakehouse](https://learn.microsoft.com/en-us/fabric/fundamentals/decision-guide-lakehouse-warehouse)
- [Microsoft Fabric — Create Tables in the Warehouse](https://learn.microsoft.com/en-us/fabric/data-warehouse/create-table)
- [Microsoft Fabric — Transform Data with a Stored Procedure](https://learn.microsoft.com/en-us/fabric/data-warehouse/tutorial-transform-data)
- [Microsoft Fabric Data Factory — Stored Procedure Activity](https://learn.microsoft.com/en-us/fabric/data-factory/stored-procedure-activity)
- [Microsoft Fabric Data Factory — Script Activity](https://learn.microsoft.com/en-us/fabric/data-factory/script-activity)
- [Microsoft Fabric Data Factory — Notebook Activity](https://learn.microsoft.com/en-us/fabric/data-factory/notebook-activity)
- [Microsoft Fabric — Materialized Lake Views Overview](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/overview-materialized-lake-view)
- [Microsoft Fabric — Data Quality in Materialized Lake Views](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/data-quality)
- [Microsoft Fabric — Data Quality Report for Materialized Lake Views](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/data-quality-reports)
- [Microsoft Purview — Data Quality for Fabric Lakehouse](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-fabric-lakehouse)
- [Microsoft Purview — Data Quality Thresholds](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-threshold)
- [Microsoft Purview — Data Quality Error Records](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-error-records)

> **Feature status:** July 2026. Fabric and Purview functionality, regional availability and preview status can change. Verify the current Microsoft documentation before implementation.
