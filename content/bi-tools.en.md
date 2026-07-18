---
title: One Business Question, Different BI Engines — Reporting Tools, Calculation Context and Governance
description: A practical comparison of Qlik Sense, Power BI, Tableau, Looker, SAP Analytics Cloud and Excel, including strengths, weaknesses, semantic models and one filter-context example implemented across all six worlds.
category: Business Intelligence
tags:
  - business-intelligence
  - reporting-tools
  - qlik-sense
  - power-bi
  - tableau
  - looker
  - sap-analytics-cloud
  - excel
  - set-analysis
  - dax
  - semantic-layer
  - metric-governance
  - self-service
order: -1
author: Thomas Lindackers
hero: images/playbooks/bi-tools-hero.png
---

## Reporting tools do more than display the same data differently

A reporting-tool comparison often begins with chart types, dashboard layouts, connector lists or licensing models. Those criteria matter, but they do not explain the most important architectural difference.

Every BI tool has its own answer to four questions:

1. How is data modelled?
2. Where is business logic defined?
3. How does a calculation interact with filters and user selections?
4. How can a metric be governed, reused and changed safely?

This is why the same business question can require **Set Analysis in Qlik Sense, DAX in Power BI, a Level of Detail expression in Tableau, LookML in Looker, a restricted measure in SAP Analytics Cloud or a Pivot/Data Model formula in Excel**.

The syntax is not the main difference. The main difference is the calculation contract behind the syntax.

> **A BI tool is not only a visualization layer. It is also a modelling, calculation and governance environment.**

## The reporting-tool landscape

The first diagram provides an indicative orientation across six widely used reporting environments.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bi-tools-img1-en.png"
        alt="Indicative comparison of Qlik Sense, Power BI, Tableau, Looker, SAP Analytics Cloud and Excel across data modelling, semantic layer, visual exploration, self-service, enterprise governance and embedded analytics"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The comparison is directional rather than absolute. Product configuration, architecture, licences, team skills and governance practices can materially change the result.
    </figcaption>
</figure>

The diagram should not be read as a universal product ranking. A well-designed Tableau environment can be more governed than a poorly managed Power BI landscape. An Excel workbook connected to a certified semantic model can be safer than a dashboard containing duplicated local formulas. A Qlik application with uncontrolled chart expressions can lose consistency despite a strong associative model.

The relevant question is therefore not:

> “Which product is best?”

It is:

> **“Which product is strongest for our operating model, users, data architecture and governance maturity?”**

## Six tools, six characteristic operating models

| Tool | Characteristic strengths | Typical weaknesses or risks | Strong fit |
| --- | --- | --- | --- |
| **Qlik Sense** | Associative exploration, responsive selections, strong in-app data modelling, script-based transformation, Set Analysis, embedded analytics | Set Analysis has a learning curve; logic can be duplicated across objects and apps; app-centric development requires disciplined reuse and deployment | Guided and exploratory analytics where users must move freely through related data |
| **Power BI** | Tabular semantic model, DAX, broad Microsoft integration, strong self-service adoption, reusable datasets/semantic models, large ecosystem | DAX filter context is powerful but complex; report and model proliferation can create semantic sprawl; performance depends heavily on model design and capacity architecture | Organizations centred on Microsoft 365, Azure, Fabric or reusable tabular semantic models |
| **Tableau** | Visual analytics, rapid exploration, flexible chart design, strong authoring experience, LOD expressions | Calculations can become workbook-local; filter order and LOD behaviour are not always intuitive; central metric governance requires deliberate use of published data sources and controlled models | Visual discovery, analyst-led exploration and sophisticated data storytelling |
| **Looker** | Code-first semantic layer with LookML, Git-based development, reusable dimensions and measures, warehouse-native execution, embedded use cases | Requires modelling and SQL skills; governed model changes can be slower than local workbook changes; some visual filter behaviours require explicit modelling or dashboard design | Centralized semantic governance, software-style analytics development and embedded analytics |
| **SAP Analytics Cloud** | Close integration with SAP data and planning, governed models, restricted and calculated measures, analytics and planning in one environment | Best fit is usually strongest in SAP-centred landscapes; non-SAP integration and modelling choices can add complexity; story and model filter behaviour must be designed carefully | SAP-oriented reporting, planning and enterprise performance management |
| **Excel** | Ubiquity, speed, flexible ad-hoc analysis, PivotTables, Power Query, formulas and Power Pivot/Data Model | Copies, manual steps, cell references and local formulas can weaken lineage, versioning and control; workbook logic is easily fragmented | Personal productivity, controlled analysis, reconciliation, prototypes and governed model consumption |

These strengths and weaknesses are architectural tendencies, not immutable product limits.

## One business question exposes the calculation engine

Consider one apparently simple requirement:

> **Show current-year revenue for selected customers, ignore the current product selection and include a prior-year comparison.**

The intended filter contract is:

- retain the selected customers
- ignore the product selection for this metric
- calculate the current year
- calculate the previous year using the same customer scope
- do not silently change the result when Product is selected

The result can look identical in every tool. The implementation is not identical because each engine treats selections, filters, semantic models and visualization context differently.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bi-tools-img2-en.png"
        alt="The same current-year and prior-year revenue question implemented with Qlik Set Analysis, Power BI DAX, Tableau LOD expressions, Looker measures, SAP Analytics Cloud restricted measures and Excel Pivot or SUMIFS logic"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Similar analytical outcomes are produced through different calculation contexts. Some tools modify the current selection, some calculate at a fixed level of detail, and others depend on a governed semantic model or workbook logic.
    </figcaption>
</figure>

## Qlik Sense: Set Analysis modifies the selected set

Qlik selections normally define the possible records used by a chart. Set Analysis creates a different scope for one aggregation.

A simplified current-year expression is:

```qlik
Sum({
    $<
        Product=,
        Year={"$(=Year(Today()))"}
    >
} Revenue)
```

The important components are:

- `$` starts with the current selection state
- `Product=` clears the selection in Product for this expression
- the customer selection remains active because Customer is not modified
- `Year={...}` replaces the current Year selection with the specified value

The prior-year version follows the same pattern:

```qlik
Sum({
    $<
        Product=,
        Year={"$(=Year(Today())-1)"}
    >
} Revenue)
```

Set Analysis is close to the wording of the business rule: keep the current state, but change selected fields for one aggregation.

Its strength is explicit control over the selected set. Its risk is distribution. The same logic may exist in multiple charts, master measures, variables and applications. Reuse and ownership therefore matter as much as the expression itself.

A further technical detail is important: Set Analysis defines the record set for an aggregation and is not evaluated as ordinary row-by-row logic. Developers must understand the difference between set definition, aggregation scope and chart dimensionality.

## Power BI: DAX changes filter context

DAX expresses the requirement by evaluating a measure under a modified filter context.

Assume `[Revenue]` is an existing base measure:

```dax
Revenue :=
SUM ( Sales[Revenue] )
```

A strict current-year measure that ignores Product and explicit date selections can be written as:

```dax
Revenue CY - No Product :=
VAR CurrentYear = YEAR ( TODAY() )
RETURN
    CALCULATE (
        [Revenue],
        REMOVEFILTERS ( 'Product' ),
        REMOVEFILTERS ( 'Date' ),
        'Date'[Year] = CurrentYear
    )
```

The previous-year measure changes only the target year:

```dax
Revenue PY - No Product :=
VAR PreviousYear = YEAR ( TODAY() ) - 1
RETURN
    CALCULATE (
        [Revenue],
        REMOVEFILTERS ( 'Product' ),
        REMOVEFILTERS ( 'Date' ),
        'Date'[Year] = PreviousYear
    )
```

Customer filters continue to apply because the measure does not remove them.

The exact DAX should follow the intended time contract. A different implementation is required when month, fiscal period or a selected comparison window must be preserved. This is a central DAX design principle:

> **Do not write the expression until the desired filter context has been specified precisely.**

DAX is highly reusable when measures live in a managed semantic model. It becomes difficult to govern when similar measures are recreated in many models or when report-level calculations silently redefine an established KPI.

## Tableau: Level of Detail and filter order work together

Tableau does not have one direct equivalent of Qlik Set Analysis. A comparable outcome may use a FIXED or EXCLUDE Level of Detail expression, context filters and Tableau's order of operations.

A simplified current-year FIXED expression is:

```tableau
{ FIXED [Customer ID] :
    SUM(
        IF YEAR([Order Date]) = YEAR(TODAY())
        THEN [Revenue]
        END
    )
}
```

The previous-year expression uses `YEAR(TODAY()) - 1`.

For this design to retain selected customers while ignoring Product:

1. Customer is typically made a **context filter**.
2. Product remains a normal dimension filter.
3. The FIXED expression is evaluated after context filters but before normal dimension filters.

This is not merely a syntax detail. It means the result depends on Tableau's filter order and workbook configuration.

An `EXCLUDE [Product]` expression can remove Product from the view level of detail, but it does not automatically cancel every Product filter. The distinction between a dimension in the visualization and a filter applied to the data must remain explicit.

Tableau is particularly strong when the calculation serves visual analysis. Governance becomes more difficult when important calculations exist only inside individual workbooks rather than in governed published data sources or shared models.

## Looker: the semantic model is the primary contract

Looker normally defines reusable measures in LookML and executes the resulting query in the data warehouse.

A filtered current-year measure can be modelled as:

```lookml
measure: revenue_cy {
  type: sum
  sql: ${revenue} ;;
  filters: [order_date: "this year"]
}
```

The previous-year variant can use a corresponding relative-date expression:

```lookml
measure: revenue_py {
  type: sum
  sql: ${revenue} ;;
  filters: [order_date: "last year"]
}
```

However, a crucial distinction applies: a LookML `filters` parameter adds a filter to a measure. It does not universally cancel an external Product filter already applied to the query.

When a Product dashboard filter must not affect this metric, the solution may require one of the following:

- do not map the dashboard filter to the relevant tile
- expose a dedicated Explore or governed measure design
- use a separate derived query or modelling pattern
- make the filter contract explicit in dashboard architecture

This is a good example of why feature names should not be treated as exact equivalents. Looker is strongest when central modelling determines what users are allowed to query. It is less natural to reproduce every app-local selection override as a one-line measure expression.

## SAP Analytics Cloud: restricted measures and story design

SAP Analytics Cloud can create a restricted measure by combining a base measure with restrictions on one or more dimensions.

Conceptually, the current-year metric is configured as:

```text
Base measure: Revenue
Date restriction: Current Year
Customer: inherited from the active filter context
Product: not part of the measure restriction
```

A second restricted measure uses the previous year.

This centralizes the year restriction and allows it to be reused within the relevant model or story context. But leaving Product out of the restricted measure does not automatically guarantee that every story, page or chart Product filter is ignored.

The story designer must also control:

- which story and page filters affect the chart
- linked-analysis scope
- input controls
- model-level restrictions
- whether the calculation is local to a story or provided by a governed model

In an SAP-centred architecture, restricted measures can provide a consistent analytical contract. The design must still distinguish between a measure restriction and the wider filter context of the story.

## Excel: spreadsheet logic and semantic-model logic coexist

Excel must be separated into at least two analytical worlds.

### Cell formulas and tables

A formula can intentionally omit Product as a criterion:

```excel
=SUMIFS(
    Sales[Revenue],
    Sales[Customer], $B$2,
    Sales[Year], YEAR(TODAY())
)
```

The prior-year formula changes the final criterion to `YEAR(TODAY())-1`.

This is fast and transparent for a small controlled analysis. It also places the logic inside a workbook, where cell movement, copied formulas and local changes can weaken control.

### PivotTables and the Excel Data Model

A PivotTable can keep Customer as a filter or row field and leave Product outside the filter context. With Power Pivot and the Excel Data Model, explicit DAX measures can be created and reused across PivotTables in the same workbook.

This means Excel is not simply a weaker version of a BI platform. It can act as:

- a personal spreadsheet
- a Pivot-based analysis tool
- a Power Query transformation client
- a tabular semantic-model client
- a controlled front end for governed data

Its governance risk depends heavily on which of these modes is being used.

## Similar concepts are not identical equivalents

| Requirement | Qlik Sense | Power BI | Tableau | Looker | SAP Analytics Cloud | Excel |
| --- | --- | --- | --- | --- | --- | --- |
| Start from current user context | Current selection state `$` | Existing filter context | View and filter context | Explore query context | Story/model context | Active Pivot or formula inputs |
| Ignore Product for one result | `Product=` in Set Analysis | `REMOVEFILTERS('Product')` | LOD plus filter-order design | Usually dashboard/model design; filtered measure alone may not cancel query filter | Filter-scope and story design in addition to restricted measure | Omit criterion, remove Pivot filter or use DAX |
| Force current year | Set modifier | `CALCULATE` filter | Conditional expression or date filter | Filtered measure | Restricted measure | Formula, Pivot or DAX |
| Central reusable logic | Master measure / governed app pattern | Semantic-model measure | Published data source / governed calculation | LookML measure | Model or governed story calculation | Power Pivot measure or controlled template |
| Main conceptual challenge | Set scope and selections | Row context versus filter context | Order of operations and LOD | Semantic modelling and query generation | Model restrictions versus story filters | Workbook locality and formula control |

The practical conclusion is important:

> **Set Analysis has analogues in other tools, but it does not have one universal one-to-one replacement.**

Each product offers mechanisms for controlling aggregation scope. They operate at different layers and follow different evaluation rules.

## Where the calculation logic lives

The third diagram compares five possible locations for analytical logic.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bi-tools-img3-en.png"
        alt="Comparison showing whether business calculation logic is primarily defined in source SQL, a semantic model, dataset, visualization or individual workbook for six BI tools"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Logic defined high in the architecture is usually easier to reuse and govern. Logic defined close to a visualization is usually more flexible but more likely to be duplicated or changed locally.
    </figcaption>
</figure>

The five locations have different responsibilities.

### 1. Source or SQL layer

Appropriate for:

- reusable data transformations
- complex joins and grain alignment
- durable business events
- performance-intensive preprocessing
- logic shared by many consumption tools

Risk: business meaning can become hidden in views or transformations that report developers cannot inspect easily.

### 2. Semantic model

Appropriate for:

- governed measures
- dimensions and hierarchies
- relationships
- standardized time intelligence
- reusable security and calculation rules

This is normally the preferred location for enterprise metrics.

### 3. Dataset or application model

Appropriate for:

- application-specific derivations
- mapping tables
- resident or calculated tables
- use-case-specific aggregates

Risk: similar datasets can implement the same rule differently.

### 4. Visualization

Appropriate for:

- chart-specific ratios
- visual comparisons
- temporary analytical logic
- context-sensitive calculations

Risk: logic can be difficult to discover, test and reuse.

### 5. Individual workbook or report

Appropriate for:

- prototypes
- reconciliation
- temporary hypotheses
- personal productivity

Risk: the formula can become operationally important without ownership, lineage or version control.

## Centralize meaning, not every analytical action

A common governance mistake is to demand that every calculation be moved into SQL or a central semantic layer.

That removes useful analytical freedom and can create a slow central bottleneck.

The better distinction is between **business meaning** and **analytical presentation**.

Centralize and govern:

- revenue definition
- valid order population
- currency conversion
- cancellation logic
- customer and product keys
- fiscal calendar
- approved year-over-year method
- security and privacy rules

Allow controlled flexibility for:

- chart layout
- sorting
- temporary comparisons
- local scenarios
- exploratory segments
- visual reference lines
- non-certified prototypes

```flowchart
Governed source events
Certified semantic measures
Tool-specific implementation
Local analytical extension
Usage label and monitoring
```

The lower a calculation is implemented, the more clearly its status should be labelled.

Useful labels include:

- Certified
- Governed derivative
- Local analysis
- Experimental
- Deprecated

## A practical tool-selection framework

Do not select a BI platform from a feature matrix alone. Evaluate the intended operating model.

### Data and architecture

- Is analysis import-based, live-query or mixed?
- Does a governed warehouse or lakehouse already exist?
- Is a central semantic layer required?
- Must calculations be shared across several tools?
- Is embedded analytics a core requirement?

### Users and workflow

- Are users dashboard consumers, analysts, developers or planners?
- Is associative exploration more important than guided reporting?
- Is code-first modelling acceptable?
- How much local freedom should business users have?
- Must Excel remain a supported consumption channel?

### Governance

- Where are metric definitions stored?
- Can one measure be reused across reports?
- Are changes versioned and reviewed?
- Can local calculations be identified?
- Is usage and lineage visible?
- Can certified and experimental content coexist clearly?

### Operations

- Who develops, tests and publishes content?
- How are environments separated?
- How are reusable assets promoted?
- Which skills are available internally?
- How is performance monitored and optimized?

A product can be technically powerful and still be a poor organizational fit.

## The central lesson

Qlik Set Analysis, DAX, Tableau LOD expressions, LookML measures, SAP restricted measures and Excel formulas all answer a related problem:

> **Which data should this calculation include under the current analytical context?**

They do not answer it in the same way.

Qlik modifies a selected set. Power BI modifies filter context. Tableau combines level of detail with an explicit order of operations. Looker expresses business logic through a governed semantic model and generated SQL. SAP Analytics Cloud combines model and story calculation contexts. Excel ranges from local cell formulas to tabular DAX measures.

The best reporting architecture therefore does not try to make every tool behave identically.

It defines the business contract once, implements it appropriately in each environment and verifies that all implementations return the same governed result.

> **Trusted reporting is achieved when different tools may use different syntax, but they do not invent different meanings.**

## Related playbooks

- [KPI Definition, Ownership and Versioning](/en/playbooks/define-kpi) — how a metric becomes a governed and historically reproducible contract
- [KPI & Metric Governance](/en/playbooks/kpi-metric-governance) — why dynamic formulas and local dimensions can produce several versions of the same number
- [One App Cannot Answer Every Question](/en/playbooks/one-app) — how focused applications can reuse shared governed facts and measures
- [The Missing Pieces — Trusted Metrics](/en/playbooks/missing-pieces-trusted-metrics) — why a technically available metric is not automatically a trusted enterprise KPI

## Further resources

- [Qlik Help — Set Analysis and set expressions](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/ChartFunctions/SetAnalysis/set-analysis-expressions.htm)
- [Qlik Help — Set modifiers](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/ChartFunctions/SetAnalysis/set-modifiers.htm)
- [Microsoft Learn — CALCULATE](https://learn.microsoft.com/en-us/dax/calculate-function-dax)
- [Microsoft Learn — REMOVEFILTERS](https://learn.microsoft.com/en-us/dax/removefilters-function-dax)
- [Tableau Help — Level of Detail expressions](https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields_lod.htm)
- [Tableau Help — Filters and Level of Detail expressions](https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields_lod_filters.htm)
- [Google Cloud — Looker measure filters](https://docs.cloud.google.com/looker/docs/reference/param-field-filters)
- [Google Cloud — Looker measure types](https://docs.cloud.google.com/looker/docs/reference/param-measure-types)
- [SAP Help — Create restricted measures](https://help.sap.com/docs/SAP_ANALYTICS_CLOUD/00f68c2e08b941f081002fd3691d86a7/df0e123a79624e68a0735b557ef52081.html)
- [Microsoft Support — SUMIFS](https://support.microsoft.com/en-us/excel/functions/sumifs-function)
- [Microsoft Support — Measures in Power Pivot](https://support.microsoft.com/en-us/excel/measures-in-power-pivot)
