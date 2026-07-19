---
title: One Data Product, Multiple Consumers
description: How one governed data product can reliably serve Qlik, Power BI, Excel, APIs and AI through stable consumption contracts, shared business truth and deliberately consumer-specific semantic and presentation layers.
category: Data Architecture
tags:
  - data-architecture
  - modern-data-warehouse
  - data-products
  - consumption-contract
  - data-contracts
  - semantic-model
  - semantic-layer
  - qlik-sense
  - set-analysis
  - power-bi
  - dax
  - excel
  - power-query
  - api
  - ai
  - sql
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - data-quality
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 7
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start7-hero.png
---

## One business truth should not require one consumer tool

A governed data product is valuable only when it can be used.

The same Sales data may be needed for several different outcomes:

- Qlik users explore customer, product, region and time relationships interactively;
- management receives a controlled Power BI dashboard;
- sales operations works with a refreshable Excel list every morning;
- an operational application requests current sales information through an API;
- an AI assistant uses approved facts and definitions to create a management summary.

These consumers do not require the same interface, model shape or interaction pattern. They do, however, require the same underlying business truth.

Without an explicit consumption architecture, each tool gradually creates its own interpretation:

```text
Qlik app             Rebuilds customer and revenue logic in the load script
Power BI model       Repeats status mappings and KPI rules in Power Query and DAX
Excel workbook       Adds local corrections, formulas and manual categories
API service          Implements a separate query and field naming convention
AI context pipeline  Extracts an undocumented subset without quality or ownership
```

The organization then owns five implementations instead of five consumption paths.

The problem is not the number of tools. Qlik, Power BI, Excel, APIs and AI solve different problems and can all be appropriate. The problem is allowing every consumer to become an independent source of business meaning.

Part 6, [Keeping Business Logic Outside the BI Apps](/playbooks/keeping-business-logic-outside-bi-apps), established the principle that shared cleansing, integration, history, KPI foundations and quality rules should live outside individual BI artifacts. This part extends that principle to the complete consumption layer.

> **Build one governed factual core. Publish it through stable contracts. Allow every consumer to add only the semantics and interaction that are specific to its purpose.**

## Architecture principle: one governed core, several consumption paths

A data product should be understood as a governed business capability, not as one table for one report.

The product owns the reusable elements that every consumer must interpret consistently:

```text
Business scope
Grain
Business keys
Shared dimensions
Approved factual amounts
Base KPI definitions
Historical interpretation
Quality status
Security attributes
Refresh behavior
Ownership
Version and change policy
```

Consumers receive this core through interfaces that fit their technical and operational needs.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start7-img1-en.png"
        alt="A governed data product that receives data from several sources, applies ingestion, transformation, certification, governance and quality, and then supplies Qlik Sense, Power BI, Excel, APIs and AI through separate reliable consumption paths"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        One governed core can support several consumers without forcing them into one tool or one physical interface. The shared business truth remains stable while each consumption path is optimized for its own purpose.
    </figcaption>
</figure>

The consumption paths may include:

| Consumer | Suitable interface | Consumer-specific responsibility |
| --- | --- | --- |
| Qlik | Curated tables, SQL views, governed files or QVD delivery | Associative model, selections, Master Measures, Set Analysis, Qlik-specific access enforcement |
| Power BI | Warehouse or lakehouse tables, SQL endpoint, import model or approved semantic model | Relationships, DAX measures, filter context, report navigation and Microsoft-oriented distribution |
| Excel | Certified SQL view, controlled file, Power Query connection or approved semantic model | Pivot layout, local presentation, controlled scenarios, comments and operational lists |
| API | Versioned endpoint or service view | Request shape, pagination, response status, service-level behavior and application integration |
| AI | Approved context view, retrieval index or governed API | Prompt context, retrieval strategy, citations, summarization and model-specific controls |

This architecture does not require one physical semantic model to serve every tool. A Qlik associative model and a Power BI semantic model may differ because their engines and interaction patterns differ. The important requirement is that both models derive from the same governed factual contract.

## Shared data does not mean identical consumer models

The phrase **single source of truth** is often misinterpreted as “one database object that every tool must use directly.” That can become unnecessarily restrictive.

A practical architecture separates three concepts:

1. **Authoritative business truth** — the governed factual and dimensional foundation.
2. **Consumption contract** — the stable interface and service expectations offered to a consumer class.
3. **Consumer model** — the tool-specific representation used for analysis or operation.

For a Sales data product, the shared foundation may publish:

```text
business_date
order_id
order_line_id
customer_key
product_key
sales_region_key
quantity
net_revenue_amount
reporting_currency
quality_status
publication_timestamp
```

Qlik may load those fields into an associative model with a canonical calendar and a documented Link Table. Power BI may use a star schema with a Date dimension and DAX time intelligence. Excel may receive a denormalized Daily Sales view. An API may expose only the fields required by one operational process. AI may receive a restricted context view with approved descriptions and source references.

These are different models of the same product, not different definitions of Sales.

A useful governing test is:

> **Would a change alter the business answer for several consumers?**

If yes, the change belongs in the governed data product or its shared contract. If the change only affects how one tool filters, presents, navigates or interacts with the same facts, it may belong in the consumer model.

## The simplest useful implementation

Supporting multiple consumers does not require Fabric, Snowflake, Databricks, dbt or a new semantic-layer product.

A small organization can begin with existing capabilities:

```flow linear vertical
Existing source extracts
Existing SQL database or governed file layer
One certified Sales view
Persistent quality and refresh status
Qlik / Power BI / Excel connections
Optional API or controlled context export
```

A minimum relational implementation could contain:

```text
core.fact_sales
core.dim_customer
core.dim_product
core.dim_date
mart.sales_analysis
mart.sales_daily_excel
service.sales_api_v1
quality.sales_rule_result
control.data_product_publication
```

The objects do not need to be physically separate on the first day. The names illustrate different responsibilities.

A simple analytical view may look like this:

```sql
create view mart.sales_analysis as
select
    s.business_date,
    s.order_id,
    s.order_line_id,
    s.customer_key,
    c.customer_name,
    c.country_code,
    s.product_key,
    p.product_group,
    s.sales_region_key,
    s.quantity,
    s.net_revenue_amount,
    s.reporting_currency,
    s.quality_status,
    s.publication_timestamp
from core.fact_sales s
join core.dim_customer c
  on s.customer_key = c.customer_key
join core.dim_product p
  on s.product_key = p.product_key
where s.publication_status = 'PUBLISHED';
```

The view is useful only when the upstream definitions are controlled:

- `net_revenue_amount` follows the approved cancellation and currency rules;
- customer and product keys resolve the correct historical versions;
- rejected records are not silently omitted without evidence;
- `publication_status` reflects agreed quality gates;
- the owner and refresh expectation are known.

Qlik and Power BI can use a dimensional version of the product. Excel may use a narrower denormalized view. An API may use a service-specific view with stable field names. The organization still owns one business definition.

## Excel is a consumer, not a shadow warehouse

Excel is often treated as the problem because spreadsheets can contain duplicated data, hidden formulas and uncontrolled copies. That diagnosis is incomplete.

Excel is frequently the most efficient interface for:

- daily operational lists;
- ad-hoc analysis;
- PivotTables;
- planning and budgeting;
- what-if scenarios;
- comments and review workflows;
- controlled data exchange with business users.

The architectural mistake is not using Excel. The mistake is forcing users to rebuild missing data-product capabilities inside Excel.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start7-img2-en.png"
        alt="Comparison between uncontrolled Excel shadow-warehouse behavior with manual exports, multiple file versions and hidden logic, and a governed pattern where Excel consumes a validated and documented data product"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Excel creates risk when it becomes the only place where data is corrected, joined and interpreted. It creates value when it receives a governed contract and is used for analysis, planning, presentation and controlled local work.
    </figcaption>
</figure>

### The shadow-warehouse pattern

A typical uncontrolled process looks like this:

```flow linear vertical
Manual CSV export
Local lookup formulas
Hidden correction columns
Several copied workbooks
Email distribution
Unofficial KPI used in decisions
```

The workbook now owns business logic that should have been shared:

```text
Status 95 is treated as cancelled
Missing country is replaced with Germany
Negative amounts are forced to zero
Customer duplicates are removed manually
Exchange rate is copied from another file
```

None of these rules is automatically wrong. The problem is that they are local, weakly tested, difficult to discover and unavailable to other consumers.

### The governed Excel pattern

A controlled path can be much simpler:

```flow linear vertical
Certified SQL view or approved semantic model
Refreshable Excel query or PivotTable
Protected structure and documented calculations
Clearly separated local assumptions
Controlled sharing or output
```

Where the Microsoft environment, tenant configuration and licensing support it, Excel can consume an approved Power BI semantic model. In a simpler or non-Microsoft environment, Power Query can use a certified SQL view, or the platform can publish a controlled file with version, refresh timestamp and quality status.

A governed Excel workbook should make the boundary explicit:

| Content | Preferred owner |
| --- | --- |
| Actual sales amount | Governed data product |
| Cancellation and currency rules | Governed data product |
| Customer and product mapping | Governed data product |
| Refresh timestamp and quality status | Governed data product |
| Pivot layout and formatting | Excel workbook |
| User comments | Excel workbook or controlled collaboration process |
| Planning assumptions | Excel or planning process, clearly separated from actuals |
| Write-back | Controlled service or governed import process |

The workbook may still be operationally important. It should therefore have an owner, a purpose, a supported data source and a retirement or change process.

## Data product, semantic model and visualization are different layers

A recurring design error is treating the data product, semantic model and report as one object.

They solve different problems.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start7-img3-en.png"
        alt="Three-layer architecture separating a governed data product, a semantic model with business relationships and measures, and purpose-specific visualization and consumption in Qlik Sense, Power BI, Excel, APIs and AI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The data product provides governed facts and dimensions. Semantic models translate those facts into a tool-specific analytical language. Visualizations and interfaces then deliver interaction appropriate to each user and process.
    </figcaption>
</figure>

### Layer 1: governed data product

The data product owns durable business meaning:

- integrated facts and dimensions;
- approved grain and keys;
- historical interpretation;
- reusable base measures and amounts;
- data-quality rules and result status;
- security attributes;
- ownership, refresh and service expectations;
- versioned schema and change policy.

This layer should remain usable beyond one report technology.

### Layer 2: consumer-specific semantic model

The semantic model translates governed data into the language of a particular analytical engine.

For Qlik, this may include:

- associative relationships;
- a Link Table or canonical dates where justified;
- Master Dimensions and Master Measures;
- Section Access using governed entitlement data;
- Set Analysis for selection-aware comparisons.

For Power BI, this may include:

- star-schema relationships;
- calculation groups or reusable DAX measures where appropriate;
- row-level security based on governed entitlements;
- display folders, hierarchies and business-friendly names;
- time-intelligence measures in the model's filter context.

For Excel, the semantic layer may be lighter:

- a certified denormalized view;
- an approved semantic model consumed through PivotTables;
- a controlled Power Query transformation that only shapes the output;
- named tables and documented local calculations.

A semantic model should not silently redefine the central factual rule. It should add analytical context.

### Layer 3: visualization and interaction

The final layer answers questions such as:

- Which selections and exploration paths should Qlik provide?
- Which executive narrative and drill paths belong in Power BI?
- Which operational columns, comments and filters are required in Excel?
- Which fields and status codes should an API return?
- Which facts, definitions and source references may an AI assistant use?

A visualization is intentionally purpose-specific. Reuse does not require every user to see the same dashboard.

## Concrete example: one Sales data product, five consumers

Assume the organization publishes a governed Sales data product with the following contract:

| Element | Definition |
| --- | --- |
| Grain | One row per sales order line and business date |
| Main factual amount | `net_revenue_amount` in reporting currency |
| Cancellation rule | Cancelled order lines contribute zero net revenue |
| Customer history | Customer attributes are resolved as valid on the business date |
| Product history | Product hierarchy is resolved as valid on the business date |
| Refresh | Daily publication before 06:00 |
| Quality gate | Mandatory order line, customer, product, date, currency and status rules |
| Security | Region and business-unit entitlement attributes are included |
| Owner | Sales Data Owner |
| Version | `sales-consumption-v1` |

### Qlik: exploratory analysis

Qlik receives the governed facts and dimensions and builds an associative consumption model.

The base measure remains simple:

```qlik
Sum([Net Revenue Amount])
```

A tool-specific comparison can use Set Analysis:

```qlik
Sum({
    <BusinessYear = {"$(=Max(BusinessYear)-1)"}>
} [Net Revenue Amount])
```

Qlik adds associative exploration, selections and comparison behavior. It does not recalculate cancellations, currency conversion or historical customer assignment.

### Power BI: management dashboard

Power BI uses the same factual amount in a semantic model:

```DAX
Net Revenue :=
SUM ( Sales[NetRevenueAmount] )
```

A model-specific comparison may be defined in DAX:

```DAX
Net Revenue Previous Year :=
CALCULATE (
    [Net Revenue],
    DATEADD ( 'Date'[Date], -1, YEAR )
)
```

Power BI adds its report structure, filter context, management navigation and Microsoft-oriented distribution. It does not create a separate revenue definition.

### Excel: daily sales list

Excel receives a view designed for operational use:

```text
business_date
order_number
customer_number
customer_name
sales_region
product_number
product_description
quantity
net_revenue_amount
quality_status
publication_timestamp
```

The workbook can provide:

- filters and sorting;
- PivotTables;
- comments;
- local follow-up status;
- clearly separated planning or forecast columns.

The Excel-specific view may be denormalized because usability matters. It remains traceable to the same product keys and definitions.

### API: operational integration

An application may require a stable endpoint such as:

```text
GET /api/v1/sales/orders/{orderId}
```

The API contract may include:

```json
{
  "orderId": "4711",
  "businessDate": "2026-07-18",
  "customerId": "C-10042",
  "currency": "EUR",
  "netRevenue": 1250.00,
  "qualityStatus": "PASSED",
  "dataProductVersion": "sales-consumption-v1"
}
```

The API adds service-specific behavior such as response codes, pagination, request limits and endpoint versioning. The factual amount still comes from the governed Sales product.

### AI: controlled management summary

An AI assistant should not receive an arbitrary database dump. It should use an approved context contract that may contain:

- aggregated Sales facts;
- approved KPI names and descriptions;
- period and currency context;
- data-quality and freshness status;
- permitted dimensions;
- source identifiers or links;
- access-filtered records.

The model may summarize or explain the facts. It should not invent a new definition of Net Revenue or bypass the product's access rules.

A useful AI response context could include:

```text
Metric: Net Revenue
Period: 2026-07-01 to 2026-07-18
Value: EUR 12.4 million
Previous-year comparison: +4.2%
Quality status: Passed with 0.3% quarantined records
Source product: sales-consumption-v1
Publication timestamp: 2026-07-19 05:42 CET
```

The shared core makes the AI answer more reproducible because the factual inputs, definitions and publication state are explicit.

## The consumption contract

A consumer needs more than a table name. It needs a reliable agreement about what the product provides and how it behaves.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start7-img4-en.png"
        alt="A consumption contract between a governed data-product provider and consumers, covering provided entities and measures, refresh and availability, quality, permissions, interfaces, versioning, deprecation and communication"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The consumption contract converts an internal data object into a dependable product. It states what is provided, how it behaves, what quality is expected, how access works and how changes are introduced.
    </figcaption>
</figure>

A useful contract includes at least the following elements:

| Contract element | Example for the Sales product |
| --- | --- |
| Product name | `sales-consumption` |
| Business purpose | Trusted sales analysis and operational sales access |
| Grain | One row per order line and business date |
| Entities | Sales order line, customer, product, date, region |
| Fields | Named schema with type, meaning, nullability and classification |
| KPI foundation | Approved `net_revenue_amount` and `quantity` |
| Historical rule | Customer and product version valid on business date |
| Refresh | Daily before 06:00 CET |
| Freshness field | `publication_timestamp` |
| Quality rules | Mandatory keys, accepted status, valid currency and reconciliation |
| Failure behavior | Reject, quarantine, publish with warning or stop publication |
| Security | Region and business-unit access derived from governed entitlements |
| Interfaces | SQL view, dimensional tables, Excel view, API v1, approved AI context |
| Owner | Sales Data Owner |
| Technical owner | Data Platform team |
| SLA or SLO | Availability and publication expectation appropriate to the use case |
| Version | `v1` with compatibility rules |
| Deprecation | Notice period, migration guide and retirement date |
| Support | Contact, incident path and known limitations |

The contract can be stored in Markdown, YAML, a catalog, a database table or a dedicated data-product tool. The format is secondary. The agreement must be visible, versioned and operationally enforced.

### Schema stability is not enough

A table can keep the same columns while changing its meaning.

Examples include:

- cancellation status changes from one code set to another;
- business date changes from order date to posting date;
- currency conversion changes from daily to monthly rates;
- customer country changes from transaction-time history to current master data;
- quality failures are silently excluded instead of marked.

These are semantic contract changes even if no column is added or removed.

A compatibility assessment must therefore consider:

```text
Schema
Meaning
Grain
History
Timing
Quality behavior
Security behavior
Operational availability
```

## Consumer contracts can differ without fragmenting truth

A Sales data product may publish several legitimate contracts:

```text
sales_analytics_v1      Dimensional model for Qlik and Power BI
sales_daily_excel_v1    Denormalized operational list for Excel
sales_api_v1            Narrow service contract for applications
sales_ai_context_v1     Access-filtered context with definitions and source references
```

This does not necessarily create duplication. The contracts may be generated from the same governed core and validated against the same rules.

The danger begins when each contract develops independent business meaning.

A useful control is to identify which parts are shared and which are intentionally specific:

| Area | Shared across consumers | Intentionally consumer-specific |
| --- | --- | --- |
| Revenue rule | Yes | No |
| Customer history | Yes | No |
| Quality status | Yes | Presentation may differ |
| Security eligibility | Yes | Enforcement mechanism may differ |
| Field naming | Canonical definition exists | API or tool aliases may differ |
| Shape | Common facts and keys | Star schema, associative model or flat list |
| Analytical calculations | Base amounts shared | Set Analysis, DAX and workbook calculations may differ |
| Interaction | No | Qlik selections, Power BI navigation, Excel layout, API requests, AI prompts |

## Alternative implementations by existing platform

The architecture principle remains the same across platforms. The simplest implementation should use the capabilities already operated reliably.

### Existing relational warehouse

Use:

- SQL tables and views for facts, dimensions and consumer contracts;
- database roles or filtered views for access;
- scheduled procedures or existing ETL for publication;
- persistent quality and control tables;
- direct Qlik, Power BI and Excel connections where appropriate.

This is often sufficient.

### Existing Qlik-oriented environment

A governed QVD layer can temporarily or permanently serve Qlik efficiently. To support multiple consumers, the authoritative logic should also be exposed through a platform-neutral path such as SQL tables, governed files or an API where required.

Do not force Power BI, Excel, APIs and AI to reverse-engineer a Qlik-only model if broader reuse is a stated objective.

### Existing Microsoft Fabric environment

Fabric can host warehouse or lakehouse data and Power BI semantic models. Excel can consume approved Microsoft semantic models where the organization's configuration and licensing permit it. Qlik and other consumers can use appropriate SQL, file or service interfaces.

The Microsoft integration may reduce operating effort, but it does not remove the need for explicit grain, quality, ownership and change contracts.

### Existing Snowflake environment

Snowflake tables, views, secure views and service interfaces can implement the same logical contracts. dbt can add modular SQL development, tests, documentation and model contracts when those capabilities solve a team problem.

Neither Snowflake nor dbt is required for the architecture principle.

### Existing Databricks environment

Delta tables, SQL views, governed catalog objects or sharing interfaces can publish the contracts. Use distributed processing and sharing capabilities where workload, scale or cross-platform distribution justifies them.

Do not introduce Spark-oriented complexity for a small relational reporting problem that the existing database already solves well.

### On-premises or hybrid environment

Use existing SQL databases, governed files, QVDs, scheduled exports and APIs. A hybrid contract can keep sensitive processing on-premises while publishing approved aggregates or service outputs to cloud consumers.

Cloud location does not determine whether a product is governed. Ownership, definitions, controls and change behavior do.

## Typical anti-patterns

### Building one data product per report

Every dashboard receives a separate extract and local model. Reuse remains low and every new consumer repeats the same integration work.

### Forcing every consumer to use one identical physical table

A single object becomes wide, ambiguous and difficult to optimize. Qlik, Power BI, Excel and APIs may legitimately require different shapes.

### Treating a Power BI semantic model as the only enterprise truth

A semantic model can be a valuable governed consumption layer, but APIs, Qlik and non-Microsoft workloads may still require platform-neutral facts and definitions.

### Treating QVD distribution as automatically platform-neutral

QVDs are effective for Qlik workloads. They are not automatically the best contract for Power BI, Excel, APIs or AI.

### Banning Excel instead of governing it

Users continue to create unofficial extracts because the platform does not provide a usable operational contract. The shadow process becomes less visible, not less important.

### Publishing raw tables and calling them a data product

Raw access without grain, business meaning, quality, owner, security and change policy is a technical interface, not a dependable product.

### Copying the same KPI into Qlik, DAX, Excel and API code

The organization gains several syntactically different versions of the same business rule.

### Using one semantic layer for every calculation

Selection-aware analysis, report-specific ratios, local scenarios and application response logic do not all belong in the shared core.

### Ignoring non-schema changes

A KPI meaning or history rule changes without a column change. Consumers continue to run but produce a different answer.

### Providing AI direct access to unrestricted warehouse data

The model receives fields without approved definitions, access filtering, freshness information or quality status. A technically successful response can still be semantically unsafe.

### Creating new contracts without retiring old paths

Certified views are published, but manual exports, legacy QVD generators and copied workbooks remain active indefinitely. Complexity increases instead of decreasing.

## Decision guide

Use the following questions when designing a consumption path:

| Question | Architectural response |
| --- | --- |
| Must several consumers interpret this rule identically? | Define it in the governed data product |
| Does the rule determine grain, keys, history, quality or security eligibility? | Keep it outside individual consumer artifacts |
| Is the requirement caused by the analytical engine or interface? | Implement it in a documented consumer model |
| Does Excel need a daily operational list? | Publish a controlled, refreshable Excel contract rather than banning the use case |
| Can the existing SQL platform expose reliable views? | Start there before adding another platform |
| Does a Power BI semantic model reduce duplication for Microsoft consumers? | Use it as a governed consumption model, not as an automatic replacement for the platform-neutral core |
| Does Qlik require associative structures or Set Analysis? | Keep those Qlik-specific elements in a thin Qlik model |
| Does an API require a stable service schema? | Version the API contract separately while deriving facts from the same product |
| Does AI require context? | Publish an access-filtered, documented and freshness-aware context contract |
| Do consumers require different shapes? | Publish several derived contracts with one authoritative meaning |
| Is a change technically compatible but semantically different? | Treat it as a contract change and communicate it |
| Is the current implementation sufficient? | Improve governance before introducing a new tool |

## Most important recommendations

1. Design the data product around a business capability, not around one report.
2. Define grain, keys, KPI foundations, history, quality, security and ownership before publishing interfaces.
3. Keep shared business meaning in one governed core.
4. Allow separate Qlik, Power BI, Excel, API and AI consumption models when their purposes differ.
5. Make consumer-specific logic explicit and document why it belongs in the tool.
6. Treat Excel as a supported consumer when the business process genuinely needs it.
7. Separate actuals from local planning assumptions and manual inputs.
8. Publish quality status and freshness information with the data.
9. Version semantic changes, not only schema changes.
10. Use stable consumption contracts with owners, support paths and deprecation rules.
11. Reuse existing SQL, file, QVD or platform capabilities before adding another product.
12. Use Fabric, Snowflake, Databricks or dbt only when they solve a concrete integration, scale, collaboration or operating problem.
13. Keep Qlik and Power BI models thin enough that the central business rule remains reusable.
14. Give APIs and AI dedicated contracts instead of uncontrolled direct access.
15. Retire old exports, copied formulas and redundant pipelines after consumers migrate.

## Transition to the next part

One governed data product can serve several consumers only when its shared rules are implemented in a maintainable transformation path.

The next part, [Transformation Options](/playbooks/transformation-options), compares where those transformations can run — in existing SQL, warehouse procedures, Qlik-oriented layers, dbt, Fabric, Snowflake, Databricks or hybrid combinations — and how to choose the simplest option that preserves the product contract.
