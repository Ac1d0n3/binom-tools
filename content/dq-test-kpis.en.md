---
title: From Tests to Measurable Data Quality — Operational Data Quality Monitoring
description: How to design technical data quality tests so that every execution produces a standardized result row that can be analyzed historically, consistently and operationally in Qlik and Power BI.
category: Data Quality
tags:
  - data-quality
  - operational-data-quality
  - data-quality-monitoring
  - data-observability
  - microsoft-fabric
  - dbt
  - databricks
  - qlik-sense
  - power-bi
  - data-governance
  - data-stewardship
  - ownership
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 1
seriesTitle: Operational Data Quality
hero: images/playbooks/dq-test-kpis-hero.png
---

## A passed or failed test is not yet monitoring

Technical data quality tests are easy to create in modern data platforms.

A SQL query searches for `NULL` values. A dbt test returns failing records. A Fabric notebook calculates duplicates. A Databricks pipeline validates expectations for incoming data. A stored procedure checks referential integrity.

The individual check is then automated.

However, a sustainable data quality operating model still often lacks the answers that matter:

- Which rule failed today?
- How many rows were tested?
- How many rows failed?
- Did the failure rate increase compared with yesterday?
- Which data product is affected?
- How critical is the issue?
- Who must respond?
- Does the same problem occur repeatedly?
- Did the correction actually improve the next test result?

A test that produces only an exit code, log message or temporary list of failures cannot answer these questions reliably.

> **Operational Data Quality begins when every test execution produces a measurable, historical and accountable result.**

The core pattern of this series is:

```flowchart
Data Platform
Quality Rules
Test Execution
Standardized DQ Result Table
Qlik / Power BI
Ownership and Remediation
```

The platform may change. Test logic may be platform-specific. The result contract must remain stable.

## Individual tests become an analyzable history

Every quality rule validates a technical or business expectation.

Examples include:

- `CUSTOMER.CUSTOMER_ID` must not be null
- `SALES.ORDER_ID` must be unique
- `SALES.PRODUCT_ID` must exist in `PRODUCT.PRODUCT_ID`
- `SALES.AMOUNT` must be greater than or equal to zero
- `SALES.LAST_LOAD_TS` must not be older than 24 hours

The test should return more than `Passed` or `Failed`. It should generate at least four types of information:

| Area | Examples |
| --- | --- |
| **Execution context** | Run ID, platform, execution timestamp |
| **Rule context** | Rule ID, test type, table, column |
| **Metrics** | rows tested, rows failed, failure rate |
| **Governance context** | data product, owner, severity |

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test-kpis-img1-en.png"
        alt="Individual data quality tests write standardized metrics into a central DQ result table which is then monitored in Qlik and Power BI and used to drive remediation"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The decisive step is not another dashboard. Every test must first create a consistent result row. Only then can quality, trends, ownership and remediation be managed together.
    </figcaption>
</figure>

The grain of the central table is explicit:

> **One row represents one execution of one rule against one defined data object at one point in time.**

A pipeline run containing 120 rules will therefore usually generate 120 result rows with the same `Run ID` and different `Rule IDs`.

## The central DQ result structure

The minimum platform-neutral structure contains the following fields:

| Field | Meaning |
| --- | --- |
| **Run ID** | Groups all test results belonging to the same pipeline, job or batch execution |
| **Rule ID** | Stable identity of the quality rule across many executions |
| **Platform** | Platform or engine on which the test was executed |
| **Data Product** | Business data context to which the rule applies |
| **Table** | Tested table, view or logical data object |
| **Column** | Tested column; optional for table-level rules |
| **Test Type** | For example Not Null, Uniqueness, Referential Integrity, Freshness or Business Rule |
| **Status** | Outcome of the test execution |
| **Rows Tested** | Number of records within the defined test scope |
| **Rows Failed** | Number of records that violated the rule |
| **Failure Rate** | Share of failing records within the test scope |
| **Executed At** | Technical execution timestamp |
| **Owner** | Accountable role or team |
| **Severity** | Agreed business criticality of the rule |

A relational implementation could look like this:

```sql
CREATE TABLE governance.dq_test_result (
    run_id         VARCHAR(100),
    rule_id        VARCHAR(100),
    platform       VARCHAR(100),
    data_product   VARCHAR(200),
    table_name     VARCHAR(256),
    column_name    VARCHAR(256),
    test_type      VARCHAR(100),
    test_status    VARCHAR(30),
    rows_tested    BIGINT,
    rows_failed    BIGINT,
    failure_rate   DECIMAL(18,8),
    executed_at    TIMESTAMP,
    owner_name     VARCHAR(200),
    severity       VARCHAR(30)
);
```

Data types must be adapted to the target platform. The logical schema should remain consistent.

### Why Run ID and Rule ID are separate

`Run ID` and `Rule ID` serve different purposes.

A `Run ID` answers:

> Which tests belonged to the same technical execution?

A `Rule ID` answers:

> How did the same quality rule behave across many executions?

Without a stable `Rule ID`, reliable trend analysis is impossible. If the identifier changes with every rename or code change, monitoring sees a new rule instead of a new version of the same rule.

### Platform describes the execution engine

The `Platform` field should identify the technical environment that executed the test, for example:

- Microsoft Fabric
- dbt
- Databricks
- SQL Server
- Snowflake
- another SQL or data engineering platform

It is not automatically the same as the original source system. A dbt test may execute in a Fabric Warehouse even though the underlying business data originated in SAP.

### Owner and Severity are historical snapshots

Ownership and criticality can change.

If reporting only joins the current value from a rule master table, a historical incident may appear under an owner who was not accountable at that time.

It is therefore useful to copy `Owner` and `Severity` into every execution result. A separate rule registry can still maintain the current definition.

## A test must produce metrics

A basic not-null test can be expressed as a metric query:

```sql
SELECT
    COUNT(*) AS rows_tested,
    SUM(
        CASE
            WHEN email IS NULL THEN 1
            ELSE 0
        END
    ) AS rows_failed
FROM dbo.customer;
```

The failure rate is:

```text
Rows Failed / Rows Tested
```

The complete test pattern then writes the calculated values into the central table:

```sql
INSERT INTO governance.dq_test_result (
    run_id,
    rule_id,
    platform,
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
    severity
)
SELECT
    'RUN_20260718_081530',
    'DQ-001',
    'Microsoft Fabric',
    'Customer 360',
    'CUSTOMER',
    'EMAIL',
    'Not Null',
    CASE
        WHEN rows_failed = 0 THEN 'Passed'
        ELSE 'Failed'
    END,
    rows_tested,
    rows_failed,
    CAST(rows_failed AS DECIMAL(18,8))
        / NULLIF(rows_tested, 0),
    CURRENT_TIMESTAMP,
    'Data Steward CRM',
    'High'
FROM (
    SELECT
        COUNT(*) AS rows_tested,
        SUM(
            CASE
                WHEN email IS NULL THEN 1
                ELSE 0
            END
        ) AS rows_failed
    FROM dbo.customer
) test_metrics;
```

The example is intentionally generic. In production, `Run ID`, platform, owner, severity and other parameters are normally supplied by orchestration, rule metadata or a reusable macro.

The central idea remains:

> **The quality rule does not end with the check. It ends with a persisted result.**

## Status must distinguish data failures from technical failures

A failed quality expectation is not the same as a test that could not execute.

Recommended status values include:

| Status | Meaning |
| --- | --- |
| **Passed** | The rule executed successfully and remained within its agreed threshold |
| **Failed** | The rule executed successfully but violated its threshold |
| **Error** | The test could not be executed or evaluated correctly |
| **Skipped** | The rule was deliberately not executed, for example because of a dependency or release condition |

This distinction is essential for monitoring.

A SQL error must not be counted as a data quality failure. Conversely, a technically successful job must not appear as a quality success when 20 percent of records violate the rule.

An empty test scope also needs explicit handling. `Rows Tested = 0` must not automatically create an apparently perfect result. Depending on the rule, the outcome may need to be `Error`, `Failed` or another controlled status.

## Thresholds belong to the rule

Not every rule requires zero tolerance.

Examples:

| Rule | Possible threshold |
| --- | --- |
| Primary key is not null | 0 failing rows |
| Reference to an active product | maximum 0.01 percent |
| Telephone number is present | at least 95 percent complete |
| Data freshness | last successful load less than 24 hours old |
| Order amount | no value below 0 except documented credit notes |

The status should therefore not be hard-coded to `Rows Failed = 0`. It is derived from the measured result and the rule definition.

```text
Measured Failure Rate
→ compare with threshold
→ derive test status
```

The result row stores the observed outcome. The rule definition should additionally document:

- expected behavior
- threshold
- scope
- exception handling
- accountable role
- severity
- version

These topics will be expanded in later parts of the series.

## Test results and failure details are different data products

The central result table should remain compact.

It stores one row per rule execution and supports:

- monitoring
- time series
- KPI calculation
- escalation
- ownership
- auditability

Root-cause analysis often also requires individual failing records. These should be stored in a separate optional structure:

```text
DQ_TEST_RESULT
One row per rule execution

DQ_TEST_FAILURE_DETAIL
Zero to many failing records per rule execution
```

A detail table may contain:

- Run ID
- Rule ID
- technical or masked business key
- failure code
- failure description
- observed value
- evidence or source reference

Additional controls are required:

- do not duplicate PII unnecessarily
- mask or hash sensitive values
- limit retention
- restrict access more strongly
- cap or sample very large failure sets

The aggregated result row is sufficient for central monitoring. Operational remediation can use a controlled link to detailed evidence.

## The pattern remains consistent across platforms

Technical implementation differs. The result contract remains the same.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test-kpis-img2-en.png"
        alt="Platform-neutral data quality test pattern from source data and quality rule through test execution and metric calculation to a central DQ result table and analysis in Qlik and Power BI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Fabric, dbt, Databricks or a traditional SQL platform may use different testing mechanisms. Every execution is normalized to the same result structure.
    </figcaption>
</figure>

### Microsoft Fabric

In Fabric, the pattern can be implemented with components such as:

- a T-SQL query in Warehouse
- a stored procedure
- a Fabric notebook
- a data pipeline
- a pipeline Stored Procedure activity

The test calculates its metrics and uses `INSERT` to add one row to a Warehouse table. Orchestration can provide the `Run ID` and execution context.

### dbt

dbt Data Tests are SQL queries that return failing records. A test passes when it returns no failing rows.

The `store_failures` configuration can persist the failing records of a test in a dedicated table. This is useful for root-cause analysis, but it does not automatically create the central result fact table described in this playbook.

Standardized monitoring therefore requires an additional pattern that can, for example:

- read test metadata from dbt artifacts,
- determine the number of failing rows,
- enrich the result with Rule ID, owner and severity from YAML or governance metadata,
- write one result row into `DQ_TEST_RESULT`.

This can be implemented through a macro, a downstream dbt job, orchestration or a small metadata pipeline.

### Databricks

In Databricks, quality checks can be implemented with SQL, PySpark, notebooks or pipeline expectations.

Expectations expose data quality metrics through the pipeline event log. These metrics can be transformed into the standardized result structure. Alternatively, a custom test notebook can write calculated metrics directly into a Delta or SQL table.

It does not matter whether the metric originated in SQL, Python or an event log. It matters that the resulting record contains the same business fields.

### Traditional SQL platforms

In SQL Server, Snowflake, PostgreSQL or other relational platforms, the same pattern can be implemented with reusable stored procedures, views, scheduled queries or orchestration jobs.

A generic test procedure may receive parameters such as:

```text
Rule ID
Table
Column
Test Type
Threshold
Owner
Severity
Run ID
```

The procedure executes the check and writes the normalized result.

## Write directly or consolidate centrally

Not every platform can or should write directly into the same physical table.

Two common architectures exist.

### Direct write

```flowchart
Test Execution
Central DQ Result Table
```

The test writes directly into the central governance or monitoring database.

This is straightforward when:

- all platforms can reach the destination table
- authentication and write permissions can be controlled
- the schema is centrally managed
- the additional technical coupling is acceptable

### Local result followed by consolidation

```flowchart
Test Execution
Local DQ Result
Consolidation Pipeline
Central DQ Result Table
```

Each platform first writes to a local result table or native event log. A central pipeline normalizes and consolidates the records.

This often fits better when:

- several clouds or network zones are involved
- platforms use different identity models
- central write permissions should be avoided
- event logs or native test outputs already exist
- consolidation should remain deliberately decoupled

Both variants serve the same objective. Qlik and Power BI should consume the consolidated, business-stable result layer.

## Qlik and Power BI consume the same facts

Qlik and Power BI are not the test engine in this pattern.

They perform three tasks:

1. make results visible
2. analyze trends and concentrations
3. support ownership and action

Typical visualizations include:

- Failure Rate over Time
- failed rules by Severity
- data quality by Data Product
- repeatedly failing rules
- failures by Owner
- technical Errors and Skipped tests
- time to remediation
- re-test results after correction

### Do not average failure rates blindly

An important modeling rule is:

> **Failure rates across several tests or runs should not be calculated as an uncritical average of stored percentages.**

Example:

- Test A: 1 failure in 10 rows = 10 percent
- Test B: 100 failures in 100,000 rows = 0.1 percent

The simple average is 5.05 percent. The row-weighted failure rate is approximately 0.101 percent.

For a row-weighted view:

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

Power BI / DAX:

```DAX
Failure Rate =
DIVIDE(
    SUM(DQ_Test_Result[Rows Failed]),
    SUM(DQ_Test_Result[Rows Tested])
)
```

A rule-weighted metric may also be useful:

```text
Passed Rules / Executed Rules
```

The metrics answer different questions:

- **Row Failure Rate:** What share of data records is defective?
- **Rule Pass Rate:** What share of rules passed?

They should not be mixed in the dashboard.

## Ownership turns monitoring into a process

A dashboard alone does not improve data.

Operational Data Quality requires a closed loop:

```flow linear vertical
Detect issue
Assess severity and impact
Assign owner
Investigate root cause
Correct data or process
Execute the rule again
Document the result
Continue monitoring the trend
```

The central table creates the technical foundation.

It shows:

- when an issue first occurred
- how often it repeated
- how much data was affected
- who was accountable at execution time
- whether the failure rate deteriorated
- whether a correction improved the next test result

Data quality therefore becomes a measurable operating process rather than a collection of technical checks.

## Practical design rules

A reliable implementation can begin with a small set of rules:

1. **One row per rule execution.** The grain must not vary between tests.
2. **Use stable Rule IDs.** Display names may change; identity should not.
3. **Create Run IDs in orchestration.** Related tests must be groupable.
4. **Store metrics separately from status.** Status is derived from metrics and threshold.
5. **Distinguish Failed from Error.** Data defects and technical failures require different actions.
6. **Snapshot Owner and Severity at runtime.** Historical accountability must remain reproducible.
7. **Append instead of overwrite.** Without history there are no trends or auditability.
8. **Keep failure details separate.** Monitoring facts and operational evidence have different grain and security requirements.
9. **Give Qlik and Power BI the same logic.** Visualization must not reinterpret the quality contract.
10. **Start with a few critical rules.** A small managed process is more valuable than hundreds of unattended tests.

## The central conclusion

> **Technical tests become Operational Data Quality only when every execution creates a standardized, historical and accountable result row.**

Fabric, dbt, Databricks and traditional SQL platforms can execute tests using the mechanisms that fit their environments.

The shared result table connects those checks to:

- Qlik and Power BI
- time series and metrics
- data products
- severity
- ownership
- root-cause analysis
- remediation and re-testing

This does not create another isolated testing framework. It creates a shared operational language for data quality.

## Related playbooks

- [The Missing Pieces — Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality & Governance](/playbooks/data-quality-governance)
- [The Role of dbt](/playbooks/dbt-role)

## Sources and further reading

- [Microsoft Fabric — Create Tables in the Warehouse](https://learn.microsoft.com/en-us/fabric/data-warehouse/create-table)
- [Microsoft Fabric — Transform Data with a Stored Procedure](https://learn.microsoft.com/en-us/fabric/data-warehouse/tutorial-transform-data)
- [Microsoft Fabric Data Factory — Stored Procedure Activity](https://learn.microsoft.com/en-us/fabric/data-factory/stored-procedure-activity)
- [dbt — Add Data Tests to Your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [dbt — store_failures](https://docs.getdbt.com/reference/resource-configs/store_failures)
- [Databricks — Manage Data Quality with Pipeline Expectations](https://docs.databricks.com/aws/en/ldp/expectations)
- [Databricks — Lakeflow Pipeline Best Practices](https://docs.databricks.com/aws/en/ldp/best-practices)
- [Qlik Sense — Loading Data from Databases](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/DataSource/load-data-from-databases.htm)
- [Microsoft Fabric — Connect to Fabric Data Warehouse](https://learn.microsoft.com/en-us/fabric/data-warehouse/how-to-connect)
- [Microsoft Fabric — Create Reports in Power BI](https://learn.microsoft.com/en-us/fabric/data-warehouse/reports-power-bi-service)
