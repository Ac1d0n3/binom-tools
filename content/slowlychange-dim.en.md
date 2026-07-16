---
title: Slowly Changing Dimensions — Preserving History with SQL and Qlik IntervalMatch
description: A technical end-to-end guide to SCD types 0–7, Type 2 dimension processing, point-in-time SQL joins, Qlik IntervalMatch, multiple source tables, late-arriving changes and data-quality controls.
category: Data Architecture
tags:
  - slowly-changing-dimensions
  - scd
  - scd-type-2
  - dimensional-modeling
  - data-warehouse
  - sql
  - qlik
  - intervalmatch
  - point-in-time
  - data-quality
  - data-governance
  - surrogate-keys
order: -1
author: Thomas Lindackers
hero: images/playbooks/slowlychange-dim-hero.png
---

## Slowly changing does not mean unimportant

Customers move between regions. Products are reclassified. Employees change departments. Account managers take over new territories. Suppliers receive new risk ratings. Organizational structures are reorganized.

These attributes usually change more slowly than transactions, which is why dimensional modelling calls them **Slowly Changing Dimensions**. The word *slowly* is relative: a customer may generate thousands of sales lines while its segment changes only twice. Those two changes can still alter every historical report if they are modelled incorrectly.

The technical question is not simply whether an attribute changed.

The real question is:

> **Which version of the dimension must a fact use — the current version, the version valid when the event occurred, or both?**

That decision determines whether a report answers an **as-was** question, an **as-is** question, or an accidental mixture of both.

> **Example note:** The diagrams use focused extracts so that tables and relationships remain readable. The technical tables and code in this playbook expand the same pattern to the complete multi-source sales example.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img1-en.png"
        alt="Comparison between a current-state customer dimension that rewrites historical sales and a Type 2 historical dimension that matches every sale to the version valid on the sale date"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A current-state join applies today’s customer attributes to every date. A Type 2 dimension preserves each historical version and links facts to the version that was valid at the business event.
    </figcaption>
</figure>

## A realistic sales landscape starts with several source tables

A warehouse rarely receives a finished fact table and a ready-made historical dimension. The required context is distributed across operational systems.

This example uses four source domains.

### ERP order headers

| order_id | order_date | customer_id | currency |
| --- | --- | --- | --- |
| SO-1001 | 2026-01-12 | C-100 | EUR |
| SO-1002 | 2026-02-08 | C-200 | EUR |
| SO-1003 | 2026-03-18 | C-100 | EUR |
| SO-1004 | 2026-04-11 | C-300 | EUR |
| SO-1005 | 2026-05-09 | C-100 | EUR |
| SO-1006 | 2026-06-03 | C-200 | EUR |
| SO-1007 | 2026-06-21 | C-300 | EUR |

### ERP order lines

| order_id | line_id | product_id | quantity | net_amount |
| --- | ---: | --- | ---: | ---: |
| SO-1001 | 10 | P-10 | 4 | 8,000 |
| SO-1001 | 20 | P-20 | 2 | 3,000 |
| SO-1002 | 10 | P-30 | 10 | 5,000 |
| SO-1003 | 10 | P-10 | 3 | 6,000 |
| SO-1003 | 20 | P-30 | 8 | 4,000 |
| SO-1004 | 10 | P-20 | 5 | 7,500 |
| SO-1005 | 10 | P-10 | 6 | 12,000 |
| SO-1005 | 20 | P-40 | 2 | 9,000 |
| SO-1006 | 10 | P-30 | 12 | 6,000 |
| SO-1006 | 20 | P-40 | 1 | 4,500 |
| SO-1007 | 10 | P-20 | 4 | 8,000 |
| SO-1007 | 20 | P-40 | 2 | 7,000 |

The grain of the future fact table is therefore:

> **One row per order line.**

The order date belongs to the header, while product, quantity and amount belong to the line. Both tables are required before any dimensional history can be resolved.

### CRM customer changes

| customer_id | customer_name | region | segment | effective_from | change meaning |
| --- | --- | --- | --- | --- | --- |
| C-100 | Alpha Systems | West | SMB | 2026-01-01 | initial state |
| C-100 | Alpha Systems | North | Enterprise | 2026-04-01 | region and segment change |
| C-200 | Beta Retail | South | Mid-Market | 2026-01-01 | initial state |
| C-200 | Beta Retail GmbH | South | Mid-Market | 2026-05-15 | name correction |
| C-300 | Gamma Industries | North | Enterprise | 2026-01-01 | initial state |
| C-300 | Gamma Industries | Central | Enterprise | 2026-06-01 | region change |

The C-100 and C-300 changes are historically relevant and require Type 2 handling. The C-200 name change is treated as a Type 1 correction because the previous name is considered wrong rather than historically meaningful.

### Product changes

| product_id | product_name | category | effective_from |
| --- | --- | --- | --- |
| P-10 | Analytics Appliance | Hardware | 2026-01-01 |
| P-20 | Integration Setup | Services | 2026-01-01 |
| P-30 | Platform Licence | Software | 2026-01-01 |
| P-40 | Governance Package | Software | 2026-01-01 |
| P-40 | Governance Package | Professional Services | 2026-05-20 |

A P-40 sale on 9 May belongs to **Software**. A P-40 sale on 3 June belongs to **Professional Services**.

### Sales-representative assignments

| customer_id | sales_rep_id | assigned_from | assigned_to |
| --- | --- | --- | --- |
| C-100 | S-01 | 2026-01-01 | 2026-04-01 |
| C-100 | S-04 | 2026-04-01 | 9999-12-31 |
| C-200 | S-02 | 2026-01-01 | 9999-12-31 |
| C-300 | S-03 | 2026-01-01 | 2026-06-01 |
| C-300 | S-02 | 2026-06-01 | 9999-12-31 |

This source is already interval-based. It is not necessarily part of the customer dimension: it may be a separate relationship whose validity must also be resolved at the sale date.

The important distinction is:

- `load_timestamp` tells us when the warehouse received a row.
- `effective_from` tells us when the business state became valid.

They are not automatically the same. Using ingestion time as business-valid time can create technically consistent but historically false results.

## What current-state joins do to the result

If all sales are joined only to the current customer row, every sale for C-100 is reported under North / Enterprise and every sale for C-300 under Central / Enterprise.

The historical result should instead be:

| historical region | revenue |
| --- | ---: |
| West | 21,000 |
| North | 28,500 |
| South | 15,500 |
| Central | 15,000 |
| **Total** | **80,000** |

A current-state customer join produces:

| current region | revenue |
| --- | ---: |
| North | 42,000 |
| South | 15,500 |
| Central | 22,500 |
| West | 0 |
| **Total** | **80,000** |

The grand total is still correct. The classification is wrong.

That makes SCD defects dangerous: a reconciliation that checks only total revenue can pass while regional, segment and sales-representative performance is materially misstated.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img2-en.png"
        alt="Sales example showing wrong aggregation with current customer attributes and correct aggregation with historical Type 2 customer versions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ignoring history does not necessarily change the total. It changes where the total appears — and therefore changes performance analysis, ownership and decisions.
    </figcaption>
</figure>

## SCD types are business rules, not maturity levels

SCD types are not a progression in which a higher number is automatically better. Each type answers a different business requirement.

### Type 0 — retain the original value

The value never changes.

Example: the original acquisition channel of a customer remains `Partner`, even if the customer is later managed through a direct-sales channel.

Use Type 0 when the business explicitly asks for the original state.

### Type 1 — overwrite

The old value is replaced and no history is retained.

Example: `Beta Retail` is corrected to `Beta Retail GmbH`.

Use Type 1 for corrections or attributes whose history has no analytical value. Within a Type 2 dimension, define whether a Type 1 correction updates only the current row or every historical row for the durable business key. For genuine corrections, updating all versions is often more consistent.

### Type 2 — add a new row

A new dimension row and a new surrogate key are created. The previous version remains available with a closed validity interval.

Example:

| customer_sk | customer_id | region | segment | valid_from | valid_to | is_current |
| ---: | --- | --- | --- | --- | --- | --- |
| 1001 | C-100 | West | SMB | 2026-01-01 | 2026-04-01 | false |
| 1002 | C-100 | North | Enterprise | 2026-04-01 | 9999-12-31 | true |

Type 2 is the standard pattern when historical states must remain reproducible.

### Type 3 — add a previous-value attribute

The row stores the current value and one or more explicitly defined previous values.

Example:

| customer_id | current_region | previous_region |
| --- | --- | --- |
| C-100 | North | West |

Type 3 is useful for a limited, predictable comparison. It is not a complete change history.

### Type 4 — mini-dimension

Rapidly changing attributes are moved into a separate dimension.

Example: risk band, engagement score or behavioural profile changes much more frequently than customer master data. Storing every combination in the main customer dimension could create excessive growth.

### Type 5 — mini-dimension plus current Type 1 reference

Facts retain the historical mini-dimension key, while the main dimension also exposes the current profile for fast current-state filtering.

### Type 6 — Type 1 attributes inside a Type 2 dimension

A Type 2 row retains historical attributes and additionally carries current-value attributes. This supports as-was and selected as-is analysis from one structure, but the update logic becomes more complex.

### Type 7 — dual Type 1 and Type 2 perspectives

The model exposes both:

- a Type 2 surrogate-key path for historical as-was reporting
- a durable-key or current-row path for as-is reporting

Type 7 provides strong analytical flexibility when users must deliberately switch perspectives.

This playbook follows the Kimball naming convention. Some sources use “Type 4” for a separate history table, so the chosen convention should be documented in the data model rather than assumed.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img3-en.png"
        alt="Practical comparison of Slowly Changing Dimension types zero through seven with customer, region, risk and reporting examples"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The correct SCD type follows the meaning of the attribute and the reporting question. It is not selected only by the engineering team.
    </figcaption>
</figure>

## The anatomy of a governed Type 2 dimension

A robust Type 2 dimension usually contains more than `valid_from` and `valid_to`.

| column | purpose |
| --- | --- |
| `customer_sk` | surrogate key for one specific version |
| `customer_id` | stable business or durable key |
| tracked attributes | fields whose change creates a new version |
| Type 1 attributes | corrected or current-state fields |
| `valid_from` | inclusive start of validity |
| `valid_to` | exclusive end of validity |
| `is_current` | identifies the active version |
| `hash_diff` | optional hash of normalized tracked attributes |
| `load_ts` | warehouse load timestamp |
| `source_system` | source provenance |
| `is_deleted` | optional explicit source-deletion state |

The safest temporal convention is a **half-open interval**:

```text
valid_from <= event_timestamp < valid_to
```

If one version ends at `2026-04-01 00:00:00`, the next version can begin at exactly the same timestamp without overlap.

End-inclusive intervals such as `23:59:59` are fragile when timestamp precision changes from seconds to milliseconds or microseconds.

## Change detection requires normalization before hashing

A hash is useful only when equal business values produce equal inputs.

Before calculating `hash_diff`, standardize:

- data types
- trimming and whitespace
- casing where case is not meaningful
- null representation
- date and timestamp precision
- decimal scale
- source codes and mapped values

Otherwise `North`, `NORTH` and `North ` may create three dimension versions even when the business sees one value.

It is also useful to maintain separate logic for:

- Type 1 attributes
- Type 2 attributes
- ignored or derived attributes

A volatile `last_login_timestamp` must not accidentally create a new customer version every day.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img4-en.png"
        alt="Technical process for loading source changes, standardizing values, detecting hash differences, expiring an old Type 2 row, inserting a new version and validating intervals"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Type 2 processing is a controlled versioning workflow: standardize, compare, classify, expire, insert and validate.
    </figcaption>
</figure>

## SQL implementation — build the customer Type 2 dimension

The following pattern is deliberately database-neutral in structure. Function names for hashes, concatenation, temporary tables and generated keys vary by platform.

The incremental batch must contain at most one effective change per business key, or multiple changes must be processed in `effective_from` order. A full unordered change log cannot be compared only with the current row and loaded correctly in one pass.

### 1. Standardize and classify the incoming records

```sql
-- PostgreSQL / Snowflake-style example.
-- Adapt hashing and null-safe comparison functions to the platform.

CREATE TEMPORARY TABLE customer_delta AS
WITH standardized AS (
    SELECT
        customer_id,
        TRIM(customer_name) AS customer_name,
        UPPER(TRIM(region)) AS region,
        UPPER(TRIM(segment)) AS segment,
        CAST(effective_from AS TIMESTAMP) AS effective_from,

        MD5(
            CONCAT_WS(
                '|',
                COALESCE(UPPER(TRIM(region)), '∅'),
                COALESCE(UPPER(TRIM(segment)), '∅')
            )
        ) AS type2_hash
    FROM stg_crm_customer
),
current_version AS (
    SELECT *
    FROM dim_customer
    WHERE is_current = TRUE
)
SELECT
    s.*,
    d.customer_sk AS current_customer_sk,
    d.hash_diff AS current_type2_hash,
    d.customer_name AS current_customer_name,
    d.valid_from AS current_valid_from,
    CASE
        WHEN d.customer_sk IS NULL THEN 'NEW'
        WHEN d.hash_diff <> s.type2_hash THEN 'TYPE2'
        WHEN d.customer_name <> s.customer_name THEN 'TYPE1'
        ELSE 'UNCHANGED'
    END AS change_action
FROM standardized s
LEFT JOIN current_version d
    ON d.customer_id = s.customer_id;
```

In production, use null-safe comparisons. A normal `<>` comparison does not treat `NULL` like an ordinary value.

### 2. Apply Type 1 corrections

For a genuine correction such as a company-name fix, updating all historical versions keeps the corrected description consistent.

```sql
UPDATE dim_customer d
SET
    customer_name = s.customer_name,
    load_ts = CURRENT_TIMESTAMP
FROM customer_delta s
WHERE d.customer_id = s.customer_id
  AND s.change_action IN ('TYPE1', 'TYPE2')
  AND COALESCE(d.customer_name, '∅')
      <> COALESCE(s.customer_name, '∅');
```

If the business wants the old name to remain historically visible, then the name is not Type 1. It belongs in the Type 2 hash and must create a new version.

### 3. Expire the current Type 2 row

```sql
UPDATE dim_customer d
SET
    valid_to = s.effective_from,
    is_current = FALSE,
    load_ts = CURRENT_TIMESTAMP
FROM customer_delta s
WHERE s.change_action = 'TYPE2'
  AND d.customer_sk = s.current_customer_sk
  AND s.effective_from > d.valid_from;
```

The final condition protects against a zero-length interval. It does not solve late-arriving changes; those require separate handling.

### 4. Insert new and changed versions

The surrogate key should come from an identity column or sequence. Do not generate it with `MAX(customer_sk) + 1`.

```sql
INSERT INTO dim_customer (
    customer_id,
    customer_name,
    region,
    segment,
    valid_from,
    valid_to,
    is_current,
    hash_diff,
    load_ts,
    source_system
)
SELECT
    customer_id,
    customer_name,
    region,
    segment,
    effective_from,
    TIMESTAMP '9999-12-31 00:00:00',
    TRUE,
    type2_hash,
    CURRENT_TIMESTAMP,
    'CRM'
FROM customer_delta
WHERE change_action IN ('NEW', 'TYPE2');
```

Run the expire and insert operations in one transaction so that consumers do not see a closed current row without its replacement.

## SQL implementation — resolve every sales line at its business date

First create the fact grain from ERP header and line tables.

```sql
WITH sales_source AS (
    SELECT
        h.order_id,
        l.line_id,
        h.order_date,
        h.customer_id,
        l.product_id,
        l.quantity,
        l.net_amount
    FROM erp_sales_order_header h
    JOIN erp_sales_order_line l
      ON l.order_id = h.order_id
)
SELECT
    s.order_id,
    s.line_id,
    s.order_date,
    c.customer_sk,
    p.product_sk,
    a.sales_rep_id,
    s.quantity,
    s.net_amount
FROM sales_source s
JOIN dim_customer c
  ON c.customer_id = s.customer_id
 AND s.order_date >= c.valid_from
 AND s.order_date <  c.valid_to
JOIN dim_product p
  ON p.product_id = s.product_id
 AND s.order_date >= p.valid_from
 AND s.order_date <  p.valid_to
LEFT JOIN sales_customer_assignment a
  ON a.customer_id = s.customer_id
 AND s.order_date >= a.assigned_from
 AND s.order_date <  a.assigned_to;
```

The resulting fact table contains stable references to the versions valid at the sale date.

| order_id | line_id | order_date | customer_sk | product_sk | sales_rep_id | net_amount |
| --- | ---: | --- | ---: | ---: | --- | ---: |
| SO-1001 | 10 | 2026-01-12 | 1001 | 501 | S-01 | 8,000 |
| SO-1001 | 20 | 2026-01-12 | 1001 | 502 | S-01 | 3,000 |
| SO-1002 | 10 | 2026-02-08 | 2001 | 503 | S-02 | 5,000 |
| SO-1003 | 10 | 2026-03-18 | 1001 | 501 | S-01 | 6,000 |
| SO-1003 | 20 | 2026-03-18 | 1001 | 503 | S-01 | 4,000 |
| SO-1004 | 10 | 2026-04-11 | 3001 | 502 | S-03 | 7,500 |
| SO-1005 | 10 | 2026-05-09 | 1002 | 501 | S-04 | 12,000 |
| SO-1005 | 20 | 2026-05-09 | 1002 | 504 | S-04 | 9,000 |
| SO-1006 | 10 | 2026-06-03 | 2001 | 503 | S-02 | 6,000 |
| SO-1006 | 20 | 2026-06-03 | 2001 | 505 | S-02 | 4,500 |
| SO-1007 | 10 | 2026-06-21 | 3002 | 502 | S-02 | 8,000 |
| SO-1007 | 20 | 2026-06-21 | 3002 | 505 | S-02 | 7,000 |

Resolving surrogate keys during the warehouse fact load is usually preferable to repeating range joins in every report. It creates a conventional star schema and centralizes the historical rule.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img5-en.png"
        alt="End-to-end architecture from CRM ERP and HR through staging, Type 2 dimensions, facts with surrogate keys, semantic layer and trusted historical reporting"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Historical resolution belongs in a governed data path: preserve source changes, create dimension versions, attach the correct surrogate keys to facts and expose deliberate as-was or as-is views.
    </figcaption>
</figure>

## Qlik implementation — resolve history with Extended IntervalMatch

Qlik can resolve a slowly changing dimension during the load script when the warehouse does not provide versioned surrogate keys.

`IntervalMatch` links discrete numeric values to intervals. Qlik dates and timestamps are dual values with a numeric representation, so date fields should be normalized consistently. The **extended syntax** adds one or more business keys, such as `CustomerID`, to the interval match.

The core condition remains:

```text
CustomerValidFrom <= OrderDate <= CustomerValidTo
```

Qlik IntervalMatch treats interval bounds as inclusive. To avoid double matches on a boundary, model Qlik intervals accordingly — for example, close a daily interval on the day before the next version, or use timestamps with a deliberately adjusted upper bound. This differs from the half-open SQL convention used earlier and must be documented.

### Load the sales lines

```qlik
Sales:
LOAD
    AutoNumberHash128(OrderID, LineID)            AS %SaleLineKey,
    OrderID,
    LineID,
    Floor(OrderDate)                              AS OrderDate,
    CustomerID,
    ProductID,
    Quantity,
    NetAmount,

    AutoNumberHash128(CustomerID, Floor(OrderDate))
                                                    AS %CustomerDateKey,
    AutoNumberHash128(ProductID, Floor(OrderDate))
                                                    AS %ProductDateKey
FROM [lib://Data/Sales.qvd] (qvd);
```

### Load the customer history

The warehouse example uses an exclusive `valid_to`. Because Qlik IntervalMatch uses inclusive bounds, this daily-grain example converts the upper boundary to the previous day.

```qlik
CustomerHistory:
LOAD
    CustomerSK,
    CustomerID,
    Floor(CustomerValidFrom)                      AS CustomerValidFrom,
    Floor(
        Alt(CustomerValidTo, MakeDate(2999, 12, 31))
    ) - 1                                         AS CustomerValidTo,
    Region,
    Segment,
    AccountManager
FROM [lib://Data/DimCustomerHistory.qvd] (qvd);
```

### Build a distinct set of customer-date combinations

This avoids creating a larger intermediate bridge than required when many order lines share the same customer and date.

```qlik
CustomerEventDates:
LOAD DISTINCT
    %CustomerDateKey,
    CustomerID,
    OrderDate                                     AS CustomerMatchDate
RESIDENT Sales;
```

### Apply Extended IntervalMatch

```qlik
CustomerIntervalMatch:
INTERVALMATCH (CustomerMatchDate, CustomerID)
LOAD
    CustomerValidFrom,
    CustomerValidTo,
    CustomerID
RESIDENT CustomerHistory;
```

The generated match table contains the event date, customer key and matching interval bounds.

### Add the version attributes to the match result

```qlik
LEFT JOIN (CustomerIntervalMatch)
LOAD
    CustomerID,
    CustomerValidFrom,
    CustomerValidTo,
    CustomerSK,
    Region,
    Segment,
    AccountManager
RESIDENT CustomerHistory;
```

### Attach the resolved version to the distinct event keys

```qlik
LEFT JOIN (CustomerEventDates)
LOAD
    CustomerMatchDate,
    CustomerID,
    CustomerSK,
    Region          AS SaleRegion,
    Segment         AS SaleSegment,
    AccountManager  AS SaleAccountManager
RESIDENT CustomerIntervalMatch;
```

### Attach it to every sales line

```qlik
LEFT JOIN (Sales)
LOAD
    %CustomerDateKey,
    CustomerSK,
    SaleRegion,
    SaleSegment,
    SaleAccountManager
RESIDENT CustomerEventDates;

DROP TABLE CustomerIntervalMatch;
DROP TABLE CustomerEventDates;
DROP TABLE CustomerHistory;
```

The same pattern can be applied to products with `%ProductDateKey`, `ProductValidFrom` and `ProductValidTo`.

A separate assignment history can also be resolved by customer and order date. If multiple representatives can be valid at the same time, the relationship is not a single-valued SCD lookup. It is a many-to-many allocation problem and needs a bridge table, potentially with allocation weights.

### Why materialize the result?

Keeping several interval-match bridges in the associative model can produce:

- synthetic keys
- ambiguous associations
- circular references
- unexpected row multiplication
- larger application models
- harder validation

Materializing the matched surrogate keys or descriptive attributes into the sales table during the script is often easier to govern. A deliberate link-table design is still valid when multiple facts must share the same temporal relationship.

### Qlik-specific checks

Before trusting the result, verify:

- all date fields are numeric and use the same granularity
- open-ended intervals have a numeric upper bound
- no two intervals overlap for the same business key
- every sales line has exactly one customer match
- every sales line has exactly one product match
- helper match tables do not multiply the fact grain
- time zones are normalized before `Floor()` removes the time portion

Official Qlik documentation states that overlapping intervals match all applicable intervals. In a Type 2 model, that usually means one fact can be duplicated unless the overlap is intentional.

## SQL and Qlik solve different layers of the same problem

| criterion | resolve in SQL / warehouse | resolve with Qlik IntervalMatch |
| --- | --- | --- |
| preferred location | central data platform | analytics load layer |
| fact storage | fact stores surrogate keys | app resolves keys or attributes at reload |
| reuse | shared across all tools | specific to Qlik application or reusable QVD layer |
| query complexity | simple star-schema joins | interval logic executed during reload |
| governance | centralized | must be standardized across Qlik apps |
| performance | range join once during fact load | depends on reload volume and match cardinality |
| best fit | governed enterprise fact tables | legacy facts, app-specific history, missing upstream resolution |
| main risk | incorrect ETL interval logic | overlaps, synthetic keys and row multiplication |

The strongest default architecture is:

> **Build and validate Type 2 dimensions in the warehouse, resolve surrogate keys during the fact load, and use Qlik IntervalMatch when the temporal relationship genuinely belongs in the Qlik load layer or upstream resolution is unavailable.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img6-en.png"
        alt="End-to-end Type 2 example from source change through change detection, version creation, fact linkage and historically correct reporting"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        One source change must travel through the complete chain: detect it, create a version, link later facts to the new surrogate key and preserve earlier facts on the old version.
    </figcaption>
</figure>

## Late-arriving changes require interval surgery

The simple expire-and-insert pattern assumes that changes arrive in effective-date order.

Consider this situation:

- version A is valid from 1 January
- version B is valid from 1 June
- on 15 July, the warehouse receives a missing change that was effective from 1 April

The correct result is:

| version | valid_from | valid_to |
| --- | --- | --- |
| A | 2026-01-01 | 2026-04-01 |
| late version | 2026-04-01 | 2026-06-01 |
| B | 2026-06-01 | 9999-12-31 |

Closing only the current version would be wrong. The load process must:

1. locate the interval containing the late effective date
2. split that interval
3. insert the missing version
4. preserve the later version
5. restate facts between the affected dates if surrogate keys were already assigned

This is one reason to retain raw change events and load metadata. Without source history, it may be impossible to reconstruct what was known and what was valid.

## Facts arriving before dimensions

A sale can arrive before its customer record.

Common strategies are:

- assign an **unknown member** surrogate key and restate the fact later
- hold the fact in a suspense table until the dimension exists
- create an inferred dimension member containing only the business key, then enrich it later

The correct strategy depends on latency requirements and auditability. Silently dropping the fact or joining it to the current row later is not a safe strategy.

## As-was and as-is are both valid — when explicitly separated

### As-was

The fact joins through the historical surrogate key.

Question:

> Which region, segment and representative owned the customer when the sale occurred?

### As-is

Historical facts are grouped according to the current customer structure.

Question:

> How would all historical revenue look under today’s territories?

An organization may need both. The model should expose them as separate, named perspectives. Type 6 or Type 7 patterns can support this requirement.

What should be avoided is a report labelled “historical performance” that quietly uses current attributes because they were easier to join.

## Essential validation queries

### Detect overlapping or gapped intervals

```sql
WITH ordered AS (
    SELECT
        customer_id,
        customer_sk,
        valid_from,
        valid_to,
        LAG(valid_to) OVER (
            PARTITION BY customer_id
            ORDER BY valid_from
        ) AS previous_valid_to
    FROM dim_customer
)
SELECT
    customer_id,
    customer_sk,
    previous_valid_to,
    valid_from,
    CASE
        WHEN previous_valid_to > valid_from THEN 'OVERLAP'
        WHEN previous_valid_to < valid_from THEN 'GAP'
    END AS issue
FROM ordered
WHERE previous_valid_to <> valid_from;
```

A gap may be allowed by design, but it must be explicit.

### Detect invalid current rows

```sql
SELECT
    customer_id,
    SUM(CASE WHEN is_current THEN 1 ELSE 0 END) AS current_rows
FROM dim_customer
GROUP BY customer_id
HAVING SUM(CASE WHEN is_current THEN 1 ELSE 0 END) <> 1;
```

Deleted entities may legitimately have no active row if that rule is documented. Otherwise each durable key should normally have exactly one current version.

### Detect unmatched or multiply matched facts

```sql
SELECT
    s.order_id,
    s.line_id,
    COUNT(c.customer_sk) AS customer_matches
FROM sales_source s
LEFT JOIN dim_customer c
  ON c.customer_id = s.customer_id
 AND s.order_date >= c.valid_from
 AND s.order_date <  c.valid_to
GROUP BY
    s.order_id,
    s.line_id
HAVING COUNT(c.customer_sk) <> 1;
```

A result of zero means a missing interval or missing dimension. A result greater than one means overlapping intervals or duplicated dimension rows.

## Governance is part of SCD design

A Type 2 pipeline requires explicit ownership.

The business owner or data steward should define:

- which attributes are Type 0, 1, 2 or another pattern
- which source defines the effective date
- whether corrections apply retroactively
- whether backdated changes are permitted
- how deletions are represented
- whether as-is reporting is required
- which historical results may be restated
- how long history is retained

The data architect or engineer should define:

- business and surrogate keys
- interval semantics and timestamp precision
- load ordering and transaction boundaries
- hash normalization
- late-arriving logic
- unknown-member handling
- performance and indexing
- automated validation
- lineage and operational monitoring

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/slowlychange-dim-img7-en.png"
        alt="Governance and engineering best practices for Type 2 dimensions including business keys, tracked attributes, validity periods, data quality, automation, access and audit"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Type 2 history remains trustworthy only when interval rules, attribute ownership, automated tests and controlled changes are governed continuously.
    </figcaption>
</figure>

## Practical design rules

1. Define the fact grain before resolving dimensions.
2. Separate business keys from surrogate version keys.
3. Store business-effective time separately from ingestion time.
4. Use one documented interval convention.
5. Normalize values before change detection.
6. Keep Type 1 and Type 2 attributes explicit.
7. Resolve surrogate keys centrally when possible.
8. Validate that each fact receives exactly one intended match.
9. Treat overlapping intervals as an error unless the relationship is genuinely many-to-many.
10. Design late-arriving and deletion behaviour before production.
11. Expose as-was and as-is as named semantic perspectives.
12. Never overwrite history merely because the current row is easier to query.

## The central lesson

Slowly Changing Dimensions are not primarily an ETL technique.

They are a contract about time.

A Type 2 dimension states that a fact must retain the business context that was valid when the event occurred. SQL point-in-time joins, surrogate keys and Qlik IntervalMatch are implementation mechanisms for that contract.

Without the contract, a technically correct join can still rewrite the past.

With it, reports can be reproduced, audited and compared — even after the business changes.

## Further resources

- [Kimball Group — Dimensional Modeling Techniques](https://www.kimballgroup.com/data-warehouse-business-intelligence-resources/kimball-techniques/dimensional-modeling-techniques/)
- [Kimball Group — Slowly Changing Dimension Types 0, 4, 5, 6 and 7](https://www.kimballgroup.com/2013/02/design-tip-152-slowly-changing-dimension-types-0-4-5-6-7/)
- [Kimball Group — Using SQL MERGE for Slowly Changing Dimension Processing](https://www.kimballgroup.com/2008/11/design-tip-107-using-the-sql-merge-statement-for-slowly-changing-dimension-processing/)
- [Qlik Help — Matching intervals to discrete data](https://help.qlik.com/en-US/sense/November2025/Subsystems/Hub/Content/Sense_Hub/LoadData/matching-intervals-to-discrete-data.htm)
- [Qlik Help — IntervalMatch prefix](https://help.qlik.com/en-US/qlikview/September2025/Subsystems/Client/Content/QV_QlikView/Scripting/ScriptPrefixes/IntervalMatch.htm)
- [dbt Developer Hub — Snapshots and Type 2 history](https://docs.getdbt.com/docs/build/snapshots)

> **dbt note:** Snapshots can create Type 2 history from mutable source tables. Their detected change time is not automatically the same as a business-effective date supplied by the source. That distinction must be considered when the history is used for point-in-time reporting.
