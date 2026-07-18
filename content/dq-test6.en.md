---
title: Data Quality Cockpit in Qlik and Power BI — From Metrics to Remediation
description: How a standardized data quality history becomes an operational management cockpit in Qlik and Power BI with Quality Score, Failure Rate, trends, ownership, SLA, actions and drill-down to failing records.
category: Data Quality
tags:
  - data-quality
  - operational-data-quality
  - data-quality-cockpit
  - qlik-sense
  - power-bi
  - quality-score
  - failure-rate
  - drill-down
  - drillthrough
  - sla
  - remediation
  - ownership
  - data-governance
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 6
seriesTitle: Operational Data Quality
hero: images/playbooks/dq-test6-hero.png
---

## A dashboard becomes a Data Quality Cockpit only when it drives action

The previous parts of this series showed how technical tests in Microsoft Fabric, dbt and Databricks create standardized historical results.

This provides the foundation:

```flowchart
Quality Rule
Test Execution
Standardized DQ Result
Historical Storage
```

An operational Data Quality Cockpit adds the management layer:

```flowchart
Historical DQ Result
Quality KPIs
Drill-down
Owner and SLA
Remediation
Re-Test
```

The cockpit must therefore provide more than a red or green overview.

It must answer:

- How is data quality changing?
- Which Data Products are deteriorating?
- Which critical rules are failing?
- Which table and column are affected?
- How many records are defective?
- Who is accountable?
- Which action remains open?
- Is the agreed SLA being met?
- Which specific records require investigation?
- Did remediation improve the next test run?

> **A Data Quality Cockpit connects measurement, accountability and remediation in one operational process.**

## The shared data model behind Qlik and Power BI

Qlik and Power BI should consume the same curated facts.

A robust model contains at least four business objects:

```flow linear vertical
DQ_RULE
DQ_TEST_RESULT
DQ_FAILURE_DETAIL
DQ_ACTION
```

Optional additions include:

```text
DQ_RUN
DQ_DATA_PRODUCT
DQ_OWNER
DQ_SLA_POLICY
DQ_DATE
```

### `DQ_RULE`

One row per versioned quality rule.

Typical fields:

- Rule ID
- Rule Version
- Rule Name
- Business Description
- Data Product
- Table
- Column
- Test Type
- Threshold
- Severity
- Owner
- Active From
- Active To
- Execution Platform
- Details Link

### `DQ_TEST_RESULT`

One row per rule execution.

Typical fields:

- Run ID
- Rule ID
- Rule Version
- Status
- Rows Tested
- Rows Failed
- Failure Rate
- Executed At
- Platform
- Execution Method
- Duration
- Metric Completeness
- Owner Snapshot
- Severity Snapshot

### `DQ_FAILURE_DETAIL`

Zero to many failure records per rule execution.

Typical fields:

- Run ID
- Rule ID
- Entity ID
- Failure Code
- Failure Reason
- Detected Value
- Source System
- Load ID
- Detail Timestamp

### `DQ_ACTION`

One row per action or ticket.

Typical fields:

- Action ID
- Rule ID
- Run ID
- Owner
- Action Status
- Created At
- Due At
- Resolved At
- Resolution
- Ticket URL
- Re-Test Run ID
- SLA Status

The result fact and failure detail table have different grains.

> **Aggregated quality and individual failing records should not be forced into one overloaded table.**

## The Data Quality Management Cockpit

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test6-img1-en.png"
        alt="Data Quality Management Cockpit with Quality Score, Failure Rate, trends, severity, Data Products, rules, owners, SLA, actions and failing records"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The cockpit connects management KPIs with operational rule, owner, SLA and failure details. Filters and selections must propagate consistently across every level.
    </figcaption>
</figure>

A complete cockpit needs several perspectives.

### 1. Management KPIs

The top level presents a small set of reliable metrics:

- Quality Score
- Failure Rate
- rules executed
- failed checks
- data volume checked
- SLA Compliance
- open actions
- overdue actions

Every KPI needs:

- a time range
- a comparison period
- a clear filter context
- a documented calculation
- a last-refresh timestamp

### 2. Development over time

Time series show:

- Quality Score by day or run
- Failure Rate
- passed and failed rules
- warnings
- technical errors
- dropped records
- open and overdue actions

One current value can hide a systematic decline.

### 3. Rules by severity

Severity supports prioritization:

- Critical
- High
- Medium
- Low

Severity describes business impact.

It is not the same as:

- test status
- a technical action such as Warn, Drop or Fail
- the number of failing rows
- SLA status

A critical rule may affect only a few records and still require immediate action.

### 4. Data Product, table and column

The cockpit must lead from the enterprise overview to affected data objects:

```flowchart
Domain
Data Product
Table
Column
Rule
Run
Failure Record
```

The levels should match the organization’s data model and accountability structure.

### 5. Owner

Owner dimensions support:

- failures by accountable team
- open actions by owner
- SLA violations
- recurring defects
- unassigned rules
- rules with an outdated owner

Owner should come from the historical result row or a time-dependent assignment. Joining only the current owner can misrepresent historical accountability.

### 6. SLA and open actions

The cockpit becomes operational when it shows not only defects but also how they are being handled:

- Open
- In Analysis
- In Remediation
- Waiting for Source
- Ready for Re-Test
- Resolved
- Accepted Risk
- Closed

Relevant fields include:

- due date
- age of action
- accountable owner
- latest comment
- ticket or workflow link
- resolution
- re-test result

## Failure Rate and Quality Score are different metrics

The metrics answer different questions.

### Failure Rate

The row-weighted Failure Rate is:

```text
Sum(Rows Failed) / Sum(Rows Tested)
```

It answers:

> What share of tested records violates the selected rules?

The metric is most useful for rules with a meaningfully comparable row grain.

Do not calculate it as a simple average of stored percentages.

Example:

```text
Test A:
1 failure / 10 rows = 10%

Test B:
100 failures / 100,000 rows = 0.1%

Simple average:
5.05%

Row-weighted Failure Rate:
101 / 100,010 ≈ 0.101%
```

### Rule Pass Rate

```text
Passed Rule Runs / Executed Rule Runs
```

It answers:

> What share of executed rules passed?

A test covering ten records has the same weight as a test covering ten million records.

### Quality Score

Quality Score is not a universal standardized metric. The organization must document its semantics.

A transparent example uses rule status and severity weights.

Status values:

| Status | Score |
| --- | ---: |
| Passed | 1.00 |
| Warning | 0.50 |
| Filtered | 0.25 |
| Failed | 0.00 |
| Failed Early | 0.00 |
| Error | handle separately |
| Skipped | exclude from denominator |

Severity weights:

| Severity | Weight |
| --- | ---: |
| Critical | 8 |
| High | 4 |
| Medium | 2 |
| Low | 1 |

Calculation:

```text
Quality Score =
Sum(Status Score × Severity Weight)
/
Sum(Severity Weight)
× 100
```

Example:

```text
Critical Passed:
1.00 × 8

High Failed:
0.00 × 4

Medium Warning:
0.50 × 2

Low Passed:
1.00 × 1

Quality Score:
(8 + 0 + 1 + 1) / (8 + 4 + 2 + 1)
= 66.7%
```

This example is deliberately simple.

Other designs can include:

- distance from threshold
- Data Product criticality
- rule coverage
- technical errors as a separate KPI
- separate quality dimensions such as Completeness, Validity and Timeliness

> **The score must be understandable, reproducible and stable over time. A precise-looking percentage without a documented formula is not a governed KPI.**

## Do not hide errors and skipped tests

A high Quality Score can be misleading when many tests did not execute.

Show additional metrics:

- Test Coverage
- Error Rate
- Skipped Rate
- Metric Completeness
- latest successful result
- age of latest test

Example:

```text
100 defined rules
80 Passed
5 Failed
15 Error

Rule Pass Rate across all defined rules:
80%

Rule Pass Rate only across completed quality outcomes:
94.1%

The values tell different stories.
```

Technical errors do not automatically belong in the business-data Failure Rate. They require a separate operational perspective.

## Qlik model and measures

Qlik is particularly effective for associative selections across:

```text
Data Product
Table
Column
Rule
Owner
Severity
Run
Status
Action
```

A possible logical model is:

```text
DQ_TEST_RESULT
  ↕ RuleKey
DQ_RULE

DQ_TEST_RESULT
  ↕ RunID
DQ_RUN

DQ_FAILURE_DETAIL
  ↕ RunRuleKey
DQ_TEST_RESULT

DQ_ACTION
  ↕ RuleKey / RunRuleKey
DQ_TEST_RESULT
```

Avoid synthetic keys and uncontrolled many-to-many relationships.

Recommended technical keys:

```text
RuleKey =
Rule ID & '|' & Rule Version

RunRuleKey =
Run ID & '|' & Rule ID & '|' & Rule Version
```

### Qlik Failure Rate

```qlik
Num(
    Sum(rows_failed)
    /
    Sum(rows_tested),
    '0.00%'
)
```

Null-safe:

```qlik
If(
    Sum(rows_tested) > 0,
    Num(
        Sum(rows_failed) / Sum(rows_tested),
        '0.00%'
    )
)
```

### Qlik Rule Pass Rate

```qlik
Num(
    Count({
        <test_status = {'Passed'}>
    } DISTINCT run_rule_key)
    /
    Count({
        <test_status -= {'Skipped'}>
    } DISTINCT run_rule_key),
    '0.0%'
)
```

### Qlik Open Actions

```qlik
Count({
    <action_status -= {'Resolved', 'Closed', 'Accepted Risk'}>
} DISTINCT action_id)
```

### Qlik Overdue Actions

```qlik
Count({
    <
        action_status -= {'Resolved', 'Closed', 'Accepted Risk'},
        due_date = {"<$(=Date(Today()))"}
    >
} DISTINCT action_id)
```

### Qlik Set Analysis for critical failures

```qlik
Count({
    <
        severity = {'Critical'},
        test_status = {'Failed', 'Failed Early'}
    >
} DISTINCT run_rule_key)
```

### Qlik drill-down

A master drill-down dimension can contain:

```text
Domain
Data Product
Table
Column
Rule Name
```

Within a drill-down dimension, Qlik automatically moves to the next level when selections leave only one possible value at the current level.

Separate sheets are often preferable for the complete journey:

1. Management Overview
2. Data Product and Rule Analysis
3. Failure Records
4. Action and SLA Management

Selections and buttons can preserve context between sheets.

## Power BI model and measures

Power BI should use a clean star schema.

Facts:

- `Fact DQ Test Result`
- `Fact DQ Failure Detail`
- `Fact DQ Action`

Dimensions:

- `Dim Rule`
- `Dim Data Product`
- `Dim Owner`
- `Dim Severity`
- `Dim Platform`
- `Dim Date`
- `Dim Run`

Relationships should preferably filter from dimensions to facts in one direction.

### DAX Failure Rate

```DAX
Failure Rate =
DIVIDE(
    SUM('Fact DQ Test Result'[Rows Failed]),
    SUM('Fact DQ Test Result'[Rows Tested])
)
```

### DAX Rule Pass Rate

```DAX
Rule Pass Rate =
DIVIDE(
    CALCULATE(
        DISTINCTCOUNT(
            'Fact DQ Test Result'[Run Rule Key]
        ),
        'Fact DQ Test Result'[Test Status] = "Passed"
    ),
    CALCULATE(
        DISTINCTCOUNT(
            'Fact DQ Test Result'[Run Rule Key]
        ),
        'Fact DQ Test Result'[Test Status] <> "Skipped"
    )
)
```

### DAX Open Actions

```DAX
Open Actions =
CALCULATE(
    DISTINCTCOUNT('Fact DQ Action'[Action ID]),
    NOT (
        'Fact DQ Action'[Action Status]
            IN { "Resolved", "Closed", "Accepted Risk" }
    )
)
```

### DAX Overdue Actions

```DAX
Overdue Actions =
CALCULATE(
    DISTINCTCOUNT('Fact DQ Action'[Action ID]),
    'Fact DQ Action'[Due At] < TODAY(),
    NOT (
        'Fact DQ Action'[Action Status]
            IN { "Resolved", "Closed", "Accepted Risk" }
    )
)
```

### DAX SLA Compliance

```DAX
SLA Compliance =
DIVIDE(
    CALCULATE(
        DISTINCTCOUNT('Fact DQ Action'[Action ID]),
        'Fact DQ Action'[SLA Status] = "Within SLA"
    ),
    CALCULATE(
        DISTINCTCOUNT('Fact DQ Action'[Action ID]),
        NOT ISBLANK('Fact DQ Action'[SLA Status])
    )
)
```

### Drillthrough in Power BI

A drillthrough page can be filtered by:

- Rule ID
- Run ID
- Data Product
- Table
- Column
- Owner

Users can then navigate from an aggregated rule to a detail page already filtered to the selected context.

Suitable pages include:

1. Executive Overview
2. Data Product Quality
3. Rule Details
4. Failure Records
5. Action and SLA Details

## From KPI to the failing record

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test6-img2-en.png"
        alt="Drill-down path from a high-level data quality KPI through time, severity, Data Product, table, column and rule to failing records and one individual record"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Each step reduces the filter context without losing previous selections. The journey ends not with a red KPI but with a specific defect, its source, load, reason and accountable action.
    </figcaption>
</figure>

A useful analysis path contains seven levels.

### 1. KPI

The user sees:

```text
Quality Score = 92.4%
Failure Rate = 7.6%
```

This indicates scale but not root cause.

### 2. Development over time

The time series answers:

- one-time outlier or trend?
- since which run?
- after which release?
- improvement after remediation?
- recurring pattern?

### 3. Severity

The user focuses on Critical and High defects.

### 4. Data Product, table and column

The affected business and technical area is located.

### 5. Rule detail

The rule view displays:

- Business Description
- Rule ID and version
- threshold
- Test Type
- execution platform
- recent runs
- Failure Rate
- Owner
- Severity
- open actions
- technical details

### 6. Failing records

The detail table shows only relevant columns:

- Entity ID
- affected values
- Failure Reason
- Source System
- Load ID
- Detected At

### 7. Single record

The deepest level shows the complete governed context:

- Business Key
- relevant attributes
- source system
- load and batch information
- rule violation
- previous and current value
- accountable owner
- ticket or action
- re-test status

## Drill-down and privacy

Failure details can contain sensitive information.

The cockpit therefore needs separate access levels.

### Management level

Can see:

- scores
- trends
- Data Products
- Severity
- Owner
- aggregated failure volumes

### Data Steward and Data Owner

Can additionally see:

- Rule Details
- Business Keys
- Failure Reasons
- actions
- governed failure details

### Technical specialists

Can additionally see:

- Source System
- Load ID
- technical events
- pipeline or job information
- quarantine or evidence links

Controls include:

- mask PII
- do not load unnecessary attributes
- limit detail retention
- use Section Access in Qlik or Row-Level Security in Power BI
- restrict export permissions
- audit access
- authorize detail pages separately

> **A Data Quality Cockpit must not become an uncontrolled alternative path into business data.**

## SLA model

SLA does not begin only when a ticket is created.

An example SLA model:

| Severity | Acknowledge | Analysis started | Fix or accepted plan |
| --- | --- | --- | --- |
| Critical | 1 hour | 4 hours | 1 business day |
| High | 4 hours | 1 business day | 3 business days |
| Medium | 1 business day | 3 business days | 10 business days |
| Low | 3 business days | according to planning | according to roadmap |

The organization must define the actual values.

Useful timestamps include:

```text
Detected At
Assigned At
Acknowledged At
Analysis Started At
Resolved At
Re-Tested At
Closed At
```

Derived metrics include:

- Time to Assign
- Time to Acknowledge
- Time to Resolve
- Time to Re-Test
- SLA Compliance
- Overdue Age
- Reopen Rate

### Correcting data is not the end

Complete closure requires:

```flow linear vertical
Correct root cause
Correct or reload data
Execute rule again
Test passes
Document result
Close ticket
```

Without a re-test, the effect of remediation remains unproven.

## Actions and tickets

The `DQ_ACTION` table can be sourced from a workflow or ticketing system.

A Data Quality Cockpit should at least be able to link to:

- ServiceNow
- Jira
- Azure DevOps
- Microsoft Planner
- Power Automate workflow
- Qlik Application Automation
- an internal governance portal

The BI tool does not have to become the master ticket system.

A preferable flow is:

```flowchart
DQ Cockpit
Create or Open Action
Workflow / Ticket System
Status Synchronization
DQ Cockpit
```

The leading source for action status must be explicit.

## Alerting

Alerts should be selective.

Useful triggers include:

- Critical Rule Failed
- Failure Rate exceeds threshold
- Quality Score drops beyond an agreed limit
- rule fails in several consecutive runs
- Data Product was not tested
- test ends with Error
- action becomes overdue
- re-test fails again

Not every failing row needs a notification.

### Qlik

Qlik Cloud can evaluate data-driven alerts for defined conditions and thresholds. Qlik Alerting also supports data-related and more complex alert conditions, depending on the Qlik deployment and licensing.

### Power BI

Power BI supports data alerts for certain dashboard tiles such as KPIs, cards and gauges. Additional report alert capabilities may be available depending on the environment. Verify current availability and preview status before implementation.

For complex operational DQ alerts, upstream workflow logic is often more robust than relying only on visual alerting.

## Performance and data volume

Failure detail tables can become very large.

Recommendations:

- use aggregated facts for the default cockpit
- load failure details only during drill-down
- separate current details from historical evidence
- partition by date or run
- load incrementally
- expose only relevant columns
- sample or paginate large failure groups
- limit detail retention
- retain aggregated history for longer

Example:

```text
DQ_TEST_RESULT:
36 months

DQ_FAILURE_DETAIL:
30 to 90 days

Quarantine:
depends on business process and privacy requirements
```

Actual retention must be defined by governance, privacy and operational needs.

## Cockpit freshness

The cockpit should display:

- Last Result Loaded At
- Last Source Refresh
- Last Successful Test Run
- Result Latency
- Action Status Sync Time

A Quality Score of 100 percent is meaningless when the data has not refreshed for three days.

Monitoring freshness is itself a control metric.

## Recommended cockpit pages

### Page 1 — Management Overview

- Quality Score
- Failure Rate
- Rule Pass Rate
- trends
- Severity
- top Data Products
- SLA
- open actions

### Page 2 — Data Product Analysis

- Domain and Data Product
- table and column
- Quality Dimensions
- rules
- platform
- development over time

### Page 3 — Rule Details

- rule definition
- threshold
- version
- Owner
- recent runs
- status history
- technical origin
- actions

### Page 4 — Failure Records

- failing records
- source system
- Load ID
- reason
- evidence
- export only when authorized

### Page 5 — Actions and SLA

- open actions
- overdue actions
- Owner
- age
- status
- re-test
- ticket link

## Acceptance criteria for an operational cockpit

A cockpit is ready for operation when:

1. Every KPI formula is documented.
2. Failure Rate and Rule Pass Rate remain separate.
3. Errors and Skipped tests stay visible.
4. Time, Data Product, rule and owner filters work consistently.
5. Drill-down to the rule is possible.
6. Authorized users can inspect failure details.
7. Owner and Severity are historically traceable.
8. Actions and SLA are connected to defects.
9. A re-test result confirms closure.
10. Qlik and Power BI use the same business semantics.
11. Detail access complies with privacy requirements.
12. Cockpit freshness and result latency are visible.

## Common design mistakes

### Using an undocumented Quality Score

The metric cannot be audited or compared reliably.

### Averaging percentages

Failure Rates must be aggregated from their numerators and denominators.

### Hiding errors

A test that could not execute is not a passed test.

### Loading management and failure details on one page

This harms performance, usability and privacy.

### Using only the current owner master value

Historical accountability can become incorrect.

### Closing actions without a re-test

Improvement remains unproven.

### Developing Qlik and Power BI logic independently

The same data then produces different Quality Scores.

### Creating a ticket for every warning

This creates alert fatigue and overwhelms the process.

### Exposing details without access control

Data quality must not bypass privacy and authorization controls.

## The central conclusion

> **A Data Quality Cockpit is the operational interface between technical tests, business accountability and demonstrable improvement.**

The standardized DQ history provides:

- rules
- test runs
- status
- volumes
- Failure Rates
- owners
- Severity

Qlik and Power BI add:

- Quality Score
- trends
- selections and drill-down
- Data Product and rule perspectives
- SLA and action management
- governed access to failure details

The process closes through:

```flowchart
Measure
Prioritize
Assign
Remediate
Re-Test
Improve
```

The series therefore ends not with a visualization but with a repeatable operating model for measurable data quality.

## Related playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [Part 2 — Data Quality in Microsoft Fabric](/playbooks/dq-test2)
- [Part 3 — Data Quality with dbt](/playbooks/dq-test3)
- [Part 4 — Data Quality in Databricks](/playbooks/dq-test4)
- [Part 5 — One Rule, Three Platforms](/playbooks/dq-test5)
- [Data Quality & Governance](/playbooks/data-quality-governance)
- [KPI Definition and Versioning](/playbooks/define-kpi)

## Sources and further reading

- [Qlik Sense — Creating a Drill-down Dimension](https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Dimensions/create-drill-down-dimension.htm)
- [Qlik Cloud — Monitoring Changes with Alerts](https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Alerting/monitoring-changes-with-alerts.htm)
- [Qlik Cloud — Managing Alerts](https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Alerting/managing-your-alerts.htm)
- [Qlik Alerting — Data Alerts](https://help.qlik.com/en-US/alerting/November2025/Content/QlikAlerting/data-alerts.htm)
- [Power BI — Drillthrough in Reports](https://learn.microsoft.com/en-us/power-bi/create-reports/desktop-drillthrough)
- [Power BI — Use Report Page Drillthrough](https://learn.microsoft.com/en-us/power-bi/guidance/report-drillthrough)
- [Power BI — Set Data Alerts on Dashboards](https://learn.microsoft.com/en-us/power-bi/explore-reports/end-user-alerts)
- [Power BI — Set Alerts on Reports](https://learn.microsoft.com/en-us/power-bi/explore-reports/business-user-set-alerts)
- [Power BI — Row-Level Security Guidance](https://learn.microsoft.com/en-us/power-bi/guidance/rls-guidance)
- [Power BI — Star Schema Guidance](https://learn.microsoft.com/en-us/power-bi/guidance/star-schema)

> **Feature status:** July 2026. Qlik and Power BI capabilities, licensing dependencies and preview status can change. Verify current vendor documentation before implementation.
