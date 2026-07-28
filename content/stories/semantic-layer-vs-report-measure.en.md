---
title: Semantic Layer vs Measure in the Report
description: Where metrics belong — warehouse, semantic layer or report-local measure.
author: Thomas Lindackers
tags:
  - metric-governance
  - semantic-layer
  - business-intelligence
  - kpi-governance
  - power-bi
  - tableau
  - qlik
  - data-modeling
  - report-governance
products:
  - qlik
  - fabric
  - powerbi
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/semantic-layer-vs-report-measure-hero.png
series: bi-governance-decisions
seriesTitle: BI Governance Decisions
seriesPart: 1
---

A metric is not governed merely because its formula is technically correct. It is governed when its meaning, grain, calculation boundary, owner, tests, permitted context and reuse are explicit.

The placement decision therefore cannot be reduced to “central is good” or “local is flexible.” Reusable business truth should not be copied into every report. At the same time, presentation-only logic should not be forced into warehouse tables merely to satisfy a blanket centralization rule.

![A governed metric passes through a placement decision before reusable and local logic are separated](images/playbooks/semantic-layer-vs-report-measure-hero.png)

## Problem

Organizations often discover metric inconsistency only after several reports are already in production.

The same label may exist in:

- a warehouse view;
- a Power BI semantic model;
- a Tableau published data source or workbook;
- a Qlik Master Measure, variable or chart expression;
- an Excel workbook;
- a local report calculation copied from another application.

The formulas may look similar while using different grains, date fields, exclusion rules or filter behavior. Conversely, two formulas may look different because each BI engine expresses analytical context differently, while both implement the same approved metric contract.

This creates a placement problem rather than only a formula problem.

### The common anti-patterns

**Every calculation in the warehouse** creates physical columns and aggregates for behavior that only exists in one visual context. The warehouse becomes coupled to page layouts, selected totals, temporary scenarios and tool-specific interactions.

**Every calculation inside the report** distributes business rules across files, applications and visual objects. Reuse depends on copying, reconciliation becomes manual and ownership is difficult to prove.

**Duplicated “same” KPIs with different filters** create several operational truths under one business label. The conflict often remains invisible until two reports are compared in a meeting.

**Hidden business rules in visualization expressions** make important exclusions, netting rules or period definitions difficult to discover, test and review.

**A certified semantic model with ungoverned local overrides** creates false confidence. Certification of the shared model does not automatically certify every local calculation created on top of it.

### Report inventory reveals the real metric estate

A report inventory should capture more than report title and owner. For metric placement it should record:

- metric label and technical expression location;
- report, workbook, app, semantic model and data source;
- business question and decision supported;
- base grain and displayed grain;
- filters, selections and time behavior;
- source fields and shared measures referenced;
- owner, criticality and certification status;
- evidence of copies, near-duplicates and overrides;
- usage, audience and expected lifetime.

The inventory turns an abstract governance concern into evidence. It shows whether a supposedly local measure is already reused, whether a certified KPI has unofficial variants and where migration effort is concentrated.

## Decision

Treat metric placement as an explicit governance decision based on **reuse, risk, grain, analytical context, testability, ownership and performance**.

The decision should produce one of four outcomes:

1. **Warehouse or data product** for durable, reusable business-event logic and grain alignment.
2. **Shared semantic layer** for governed analytical behavior that must be reused across reports.
3. **Report-local measure** for intentionally local visualization or analysis behavior.
4. **Temporary experiment requiring review** when the future scope is not yet known.

![Place logic by reuse, risk and grain](images/playbooks/semantic-layer-vs-report-measure-img1-en.png)

No technical layer is universally correct. The same mathematical formula can have a different correct home depending on its scope.

### Warehouse or data product

Place logic in the warehouse, lakehouse, mart or governed data product when it defines durable data meaning before a BI engine evaluates the result.

Typical examples include:

- transaction eligibility;
- cancellation, return and reversal treatment;
- currency standardization;
- source-system harmonization;
- historical assignment of customer or product attributes;
- deduplication and business-key resolution;
- reusable netting logic;
- grain alignment across several sources;
- data-quality status required by every consumer.

This layer should create stable facts and attributes that can be tested independently of a report. It should not attempt to materialize every possible selected-total ratio, chart comparison or temporary user scenario.

### Shared semantic layer

Place logic in a shared semantic model when it defines reusable analytical behavior over governed data.

Typical examples include:

- approved aggregation behavior;
- reusable base and derived measures;
- governed time intelligence;
- supported dimensions and hierarchies;
- common filter behavior;
- business-friendly names and formats;
- calculation groups or comparable reusable calculation patterns;
- controlled security and analytical relationships;
- certification and discoverability metadata.

The semantic layer should not compensate for unresolved warehouse grain or reproduce complex source-integration logic that belongs upstream. Its role is to expose governed facts in the calculation context of the analytical engine.

### Report-local measure

Keep a measure local when its meaning depends intentionally on one report, page, visual or temporary analytical interaction.

Appropriate examples include:

- share of the currently selected total;
- one visual’s reference line or ranking context;
- a page-specific comparison;
- presentation-only ratios;
- short-lived scenarios;
- exploratory segmentation;
- a clearly labelled prototype that makes no enterprise-truth claim.

A local measure still requires an owner and a status. “Local” must not mean undocumented, permanent by accident or allowed to silently redefine a certified KPI.

### Temporary experiment requiring review

Some measures are created before their reuse and criticality are known. They may remain experimental when:

- the business hypothesis is still being tested;
- the expected lifetime is limited;
- one owner is accountable;
- the output is clearly labelled as experimental;
- the measure is excluded from certified reporting;
- an expiry or review date is recorded.

The experiment becomes governance debt when it survives beyond the review point without a placement decision.

### One metric, three possible homes

`Net Revenue` illustrates why placement is not a competition between layers.

![One metric with warehouse, semantic-layer and report responsibilities](images/playbooks/semantic-layer-vs-report-measure-img2-en.png)

| Layer | Net Revenue responsibility | Why it belongs there |
| --- | --- | --- |
| **Warehouse or data product** | Determine eligible transactions, cancellations, returns, approved netting and reporting-currency amounts | These rules define the reusable factual basis and must reconcile independently of a BI tool |
| **Semantic layer** | Provide approved aggregation, time behavior, business naming, format and governed filter semantics | These rules describe how consumers analyze the governed factual basis |
| **Report** | Calculate share of selected total, a temporary scenario or a page-specific comparison | These calculations depend on a specific visual and do not redefine the core metric |

The boundary is explicit:

> Do not duplicate the core Net Revenue rules inside reports. Do not force visual-only context into physical warehouse tables.

### Evaluate the placement dimensions together

A useful decision review asks the following questions.

| Dimension | Central placement signal | Local placement signal |
| --- | --- | --- |
| **Reuse** | Used or expected in several governed products | Used in one report or visual only |
| **Criticality** | Executive, financial, regulated or operationally binding | Exploratory or presentation-only |
| **Definition stability** | Approved and expected to remain stable | Hypothesis or short-lived analysis |
| **Grain** | Requires source, mart or cross-model grain control | Operates only on an already governed measure |
| **Filter context** | Shared filter behavior must be consistent | Behavior is intentionally page- or visual-specific |
| **Testability** | Must reconcile independently and repeatedly | Can be validated within one controlled report |
| **Ownership** | Named owner and approval are available | Local owner is sufficient and scope is bounded |
| **Performance** | Precomputation or shared optimization benefits many consumers | Central materialization would add cost without reuse |
| **Certification** | Must become a trusted reusable asset | Must remain explicitly uncertified or local |

No single row decides the outcome. A metric used in one report may still need central placement when it drives a regulated decision. A metric used in several exploratory workbooks may remain provisional until its meaning is approved.

### Tool-specific context without tool-specific truth

The placement principle is technology-neutral, but each BI engine exposes a different implementation boundary.

**Power BI** uses semantic models as reusable reporting sources and supports explicit measures in the model. Endorsement can identify promoted or certified content, but governance must still control local calculations and downstream report behavior. A semantic model’s certification is not permission to redefine the KPI in every report.

**Tableau** can place calculated fields in a workbook or a data source, and published data sources can provide a shared controlled basis. Workbook calculations are useful for visual analysis, but important business definitions should not remain discoverable only by inspecting individual sheets.

**Qlik Sense** supports reusable Master Measures within an application and allows them to be referenced by visualizations and expressions. Master Measures reduce duplication inside the governed app boundary, but cross-app reuse, variables and chart expressions still require inventory, ownership and deployment discipline.

The goal is not identical implementation syntax. The goal is one approved metric contract with clearly governed platform-specific implementations.

### When a local measure must be promoted

A local measure should be reviewed for promotion when any of these conditions becomes true:

- it is used by more than one governed report or data product;
- it appears in executive, financial, regulatory or contractual reporting;
- it requires shared grain, time or filter rules;
- it cannot be reconciled independently from the report;
- users copy and modify it;
- several implementations use the same label;
- it requires certification, common tests or formal change control;
- its failure would change a material business decision.

![When a report measure becomes governance debt](images/playbooks/semantic-layer-vs-report-measure-img3-en.png)

A measure may remain local when it has one-off visual behavior, a known owner, a limited lifetime, an explicit exploratory label and no claim of enterprise truth.

Promotion does not always mean moving everything into the warehouse. The correct target may be a shared semantic measure built on an already governed warehouse fact.

## Checklist

Use this checklist before implementing or approving a metric placement.

### Definition and scope

- [ ] The business question and supported decision are explicit.
- [ ] The approved metric definition is linked.
- [ ] Population, exclusions, time logic and permitted dimensions are documented.
- [ ] The base grain is precise.
- [ ] Core business meaning is separated from presentation behavior.

### Reuse and criticality

- [ ] Current and expected consumers are named.
- [ ] Report inventory has been checked for duplicates and near-duplicates.
- [ ] Executive, regulated, financial or operational use is identified.
- [ ] The expected lifetime is recorded.
- [ ] The metric’s enterprise-truth claim is explicit.

### Technical placement

- [ ] Warehouse responsibilities are distinguished from semantic-model responsibilities.
- [ ] Tool-specific filter or selection behavior is documented.
- [ ] Performance and refresh impact have been assessed.
- [ ] The implementation can be traced to governed source fields and base measures.
- [ ] Local logic does not reproduce cancellation, currency, history or source-integration rules.

### Governance and control

- [ ] Business, stewardship and technical owners are named where required.
- [ ] Test and reconciliation methods are defined.
- [ ] Certification need and status are explicit.
- [ ] Permitted local overrides are defined.
- [ ] Experimental exceptions have an expiry or review date.
- [ ] Migration actions for existing duplicates are assigned.

## Artifact

The primary output is a **Metric Placement Decision**. It records why a metric belongs in a specific layer and what must happen to competing implementations.

![Record the metric placement decision](images/playbooks/semantic-layer-vs-report-measure-img4-en.png)

A practical artifact can use this structure:

```yaml
metric_placement_decision:
  metric_id: net-revenue
  metric_name: Net Revenue
  business_question: How much eligible revenue remains after approved cancellations and returns?
  approved_definition: linked-kpi-definition-id
  base_grain: sales-order-line

  calculation_components:
    warehouse_or_data_product:
      - transaction-eligibility
      - cancellation-and-return-treatment
      - currency-standardization
      - reusable-netting-logic
    semantic_layer:
      - approved-aggregation
      - time-intelligence
      - governed-filter-behavior
      - business-friendly-name-and-format
    permitted_report_local:
      - share-of-selected-total
      - temporary-scenario
      - page-specific-comparison

  consumers:
    - executive-sales-report
    - regional-sales-analysis
  reuse_expectation: multi-product
  criticality: high
  selected_home: shared-semantic-layer-on-governed-data-product

  ownership:
    business_owner: named-role
    data_steward: named-role
    implementation_owner: analytics-engineering

  controls:
    reconciliation_method: monthly-finance-reconciliation
    test_method:
      - warehouse-rule-tests
      - semantic-measure-acceptance-tests
    certification_need: required
    permitted_local_overrides:
      - presentation-only
      - no-change-to-population-grain-or-netting

  exception:
    status: none
    expiry: null

  related_assets:
    semantic_models:
      - sales-certified-model
    reports:
      - executive-sales-report
      - regional-sales-analysis

  migration_actions:
    - replace-report-local-net-revenue-copies
    - preserve-only-approved-visual-derivatives
  review_date: 2026-10-31
```

The artifact should produce five visible outputs:

- the selected placement;
- the implementation owner;
- migration actions for duplicates;
- the certification candidate;
- the review date.

A formula generator can implement DAX, Tableau calculations or Qlik expressions after this decision. It cannot decide whether the business definition is approved, whether reuse justifies promotion or whether a local override is permitted.

## Tools

Use the existing Binom tools to collect evidence and implement the approved decision:

- [KPI Definition Card](/tools/kpi-definition) — capture business question, definition, grain, formula, owner and agreement status.
- [Report Inventory Canvas](/tools/report-inventory) — identify duplicate labels, copied formulas, local overrides, owners and affected reports.
- [BI Python Export Toolkit](/tools/bi-python-toolkit) — extract Qlik KPI, app, sheet and cross-BI formula inventories for larger estates.
- [Power BI DAX Measure Generator](/tools/powerbi-dax-generator) — generate implementation snippets and documentation from an approved measure definition.
- [Tableau Calculation Generator](/tools/tableau-calculation-generator) — generate calculated-field and LOD variants after the calculation boundary is decided.
- [Qlik Set Analysis Generator](/tools/qlik-set-analysis-generator) — generate controlled child measures and variables from an approved base measure.

The tools support discovery and implementation. They do not replace the Metric Placement Decision or owner approval.

## Resources

- [One Business Question, Different BI Engines](/en/playbooks/bi-tools) — compare calculation context and governance across Qlik, Power BI, Tableau, Looker, SAP Analytics Cloud and Excel.
- [Microsoft Learn — Semantic models in the Power BI service](https://learn.microsoft.com/en-us/power-bi/connect-data/service-datasets-understand)
- [Microsoft Learn — Power BI content endorsement](https://learn.microsoft.com/en-us/power-bi/collaborate-share/service-endorsement-overview)
- [Microsoft Learn — Star schema and measures in Power BI](https://learn.microsoft.com/en-us/power-bi/guidance/star-schema)
- [Tableau Help — Best practices for published data sources](https://help.tableau.com/current/pro/desktop/en-us/publish_datasources_about.htm)
- [Tableau Help — Create custom fields with calculations](https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields.htm)
- [Qlik Help — Reusing measures with Master Measures](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Measures/create-master-measure.htm)
- [Qlik Help — Using Master Measures in expressions](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Measures/use-master-measures-expressions.htm)

> **Feature status:** July 2026. Product names, permissions, endorsement behavior and metadata capabilities can change. Verify the documentation for the deployed platform version and licensing model before implementation.

## Playbooks

Reuse these playbooks rather than redefining their decisions:

- [Keeping Business Logic Outside the BI Apps](/en/playbooks/keeping-business-logic-outside-bi-apps) — establish the boundary between reusable business logic and necessary consumer-specific behavior.
- [KPI Definition, Ownership and Versioning](/en/playbooks/define-kpi) — provide the approved KPI contract, grain, time logic, owners and change status used by the placement decision.
- [The Missing Pieces — Trusted Metrics](/en/playbooks/missing-pieces-trusted-metrics) — evaluate whether definition, ownership, quality, lineage and certification are sufficient for trusted use.

This story does not replace those playbooks. It uses their outputs to answer one narrower question: **where should this metric be implemented and governed?**

## Next step

Select one duplicated or business-critical metric from the Report Inventory and create its Metric Placement Decision before rewriting any formulas.

The first implementation should prove the complete chain:

```text
Approved KPI definition
→ Metric Placement Decision
→ Governed warehouse fact or data product
→ Shared semantic measure where required
→ Controlled local derivatives
→ Reconciliation and certification evidence
```

Do not begin by moving every calculation. Begin with one metric whose current placement creates measurable inconsistency, risk or maintenance effort. Promote the reusable core, retain justified local behavior and assign migration actions to every competing copy.

The next part of the series addresses the certification lifecycle for Fabric and Power BI metrics after the placement boundary has been decided.
