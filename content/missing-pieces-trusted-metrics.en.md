---
title: "The Missing Pieces – Part 2: Trusted Metrics"
description: "Why centralized data does not automatically create consistent business metrics – and how shared definitions, visible ownership and understandable governance can strengthen trust."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - trusted-metrics
  - kpi-governance
  - semantic-layer
  - business-glossary
  - data-stewardship
  - self-service-bi
  - data-governance
order: -1
hero: images/playbooks/mp-metrics-hero.png
series: missing-pieces
seriesPart: 2
seriesTitle: The Missing Pieces
---
## Centralized data does not automatically create a single truth

Organizations invest heavily in centralized data platforms, reusable data products, certified datasets and shared governance standards. The objective is understandable: business teams should be able to work with consistent and trusted information.

At the same time, business meaning is created in many places:

```flow linear vertical
Source Data
Transformation / dbt Model
SQL View / Data Mart
Semantic Model
BI Measure
Report Expression
Application Logic
Excel Formula
```

Each of these layers can serve a legitimate purpose. A metric in a data mart can enable reuse. A semantic model can provide business terminology and calculations to multiple reports. A report-level measure can cover a local analytical need. Excel may be the fastest and most familiar way for business users to continue working with data.

The governance question is therefore not:

> *Which of these layers is wrong?*

It is:

> **How do we prevent the same business metric from being independently redefined across multiple layers?**

Centralizing data does not automatically centralize business logic.

## Why metrics are created in multiple places

Not every metric can be defined once and remain unchanged forever. Business models, products, contracts, market conditions and regulatory requirements evolve. Different user groups also need different levels of detail and analytical context.

That is why several locations for calculations can be valid:

| Layer | Typical purpose | Governance question |
| --- | --- | --- |
| Transformation / dbt Model | stable, reusable business logic and prepared facts | Is the definition business-approved and versioned? |
| SQL View / Data Mart | domain-specific views and efficient delivery | Is this a new metric variant or only a new view? |
| Semantic Model | shared terminology, relationships, measures and filter context | Is the metric reused across channels? |
| BI Measure | interactive calculation for reports and dashboards | Is it local or relevant across the organization? |
| Report Expression | specific context for a visualization or analysis | Does local logic change the meaning of the metric? |
| Application | business workflow, simulation or operational decision | Is the calculation documented and aligned with the shared model? |
| Excel | flexible analysis, enrichment, forecasting or operational work | When does a local formula become business-critical? |

The goal cannot be to force every calculation into exactly one technical location. Excessive centralization can block self-service and slow down new requirements unnecessarily.

A better principle is:

> **Define shared meaning centrally. Allow local analysis without silently redefining the shared meaning.**

## When one metric becomes many technically plausible versions

Consider a company using the metric **Revenue**.

At first, the definition sounds simple. In practice, many questions may arise:

- Gross or net revenue?
- Invoice date, booking date or service period?
- Should cancellations be applied immediately or retrospectively?
- Should one-time and recurring revenue be combined or separated?
- Active customers only?
- Which currency and exchange rate?
- Which organizational assignment applies after retrospective changes?
- Are internal transactions included?

When these decisions are made independently in several places, multiple results can emerge even though each individual calculation runs correctly from a technical perspective.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-metrics-img1-en.png"
        alt="Diagram showing how the same KPI can be recreated across transformation, data marts, semantic models, BI, applications and Excel and become multiple versions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Many layers can be valid on their own. Trust weakens when the same definition is independently reimplemented in too many places.
    </figcaption>
</figure>

The familiar meeting-room scenario with several people and several numbers is therefore not proof that a platform has failed. It can be a symptom that calculations use different definitions, filters, time ranges or contexts.

The numbers may each be correct – but they may not answer the same question.

## Technically valid does not automatically mean business-comparable

A common reaction is: *There must be one number.*

That is not always correct.

Finance may view revenue according to accounting rules. Sales may analyze booked contract value. Customer Success may focus on recurring revenue from active customers. All three metrics can be legitimate.

The governance problem begins when different metrics use the same name or when their context is invisible.

Trust therefore requires more than technical consistency. Users need to understand:

- **What** is being measured?
- **Why** is it being measured?
- **Which rules** apply?
- **Which time range** and filter context are used?
- **Which data sources** are included?
- **Who** owns the business definition?
- **For which purpose** is the metric approved?
- **Which variants** intentionally exist?

A good governance model does not have to prevent every variation. It must make variations understandable and comparable.

## Semantic layers address exactly this gap

The growing importance of semantic layers shows that centralized storage alone is not enough. Business definitions also need to become reusable.

The dbt Semantic Layer, for example, is designed to define critical business metrics centrally and expose them to different downstream tools. Microsoft describes Managed Self-Service BI as a model in which many report creators reuse centralized shared semantic models. Qlik Master Measures combine an expression with a name, description and tags so that measures can be reused within an application.

These approaches do not solve every governance problem. They do show a common direction:

> **Business logic should be reusable as a governed product – not repeatedly rebuilt as local code.**

Balance is important. A central semantic model should not become a monolithic object expected to represent every possible question. Microsoft also notes in its Managed Self-Service BI guidance that neither one model for all organizational data nor a new model for every report is usually the right outcome.

The objective is not maximum centralization. It is controlled reuse.

## Excel is not the problem

Excel is often positioned as the opposite of a governed data platform. That view is too simplistic.

Excel serves real needs:

- Business users know the tool.
- Analyses can be adapted quickly.
- Data can be enriched, annotated and planned.
- Ad-hoc questions do not always justify a new production report.
- Many operational processes still depend on spreadsheet models.

The governance risk does not come from the file itself. It arises when business-critical logic grows outside visible ownership, documentation and validation.

One possible approach is to keep Excel as the analysis interface while sourcing data and core metrics from a shared semantic model. Microsoft supports live connections from Excel to Power BI semantic models, allowing users to work in a familiar interface while reusing a governed data foundation.

However, a boundary remains:

```flow linear vertical
Trusted Semantic Model
Excel Pivot / Connected Table
local formula
manual adjustment
new file or copy
business decision
```

At which point does personal analysis become a new business definition?

The key question is therefore not:

> *How do we prevent Excel?*

It is:

> **How do we recognize when local logic should be reused, documented or promoted into a shared model?**

## “Trusted” is more than a badge

Certification and endorsement are useful. They help users identify high-quality, officially approved content. Power BI, for example, supports *Promoted* and *Certified* labels to make trusted content easier to discover.

A badge alone does not answer every question:

- Is the metric unambiguously defined in business terms?
- Is the owner visible and reachable?
- Are filters, exclusions and time logic understandable?
- Does certification apply only to the semantic model or also to local report changes?
- Is the metric adjusted further in an application or spreadsheet?
- When was the definition last reviewed?

“Trusted” should therefore be more than a status. It should be a traceable promise:

> **A trusted metric is understandable, owned, validated, reusable and transparent about its context.**

Trust does not require every person to see the same number in every situation. It requires every person to understand why a number applies – and whether it is comparable with another one.

## Governance must work for the people expected to maintain it

KPI governance is often treated as a technical metadata problem. Tables, columns, lineage, SQL expressions and platform objects then become the main focus.

Business users and data stewards often think differently:

- *What does the metric mean?*
- *When should I use it?*
- *Which variant applies to my process?*
- *Who decides on a change?*
- *Why is my result different from the Finance report?*

If answering these questions requires deep knowledge of technical lineage, database schemas or tool-specific terminology, governance remains limited to specialists.

Research on data catalogs describes this challenge directly: catalogs can make metadata easy to store but difficult to retrieve – or the other way around. Different user groups have different skill levels and require understandable mental models.

This leads to a practical design principle:

> **Governance participation should be easier than bypassing governance.**

A data steward should not have to read SQL before understanding a KPI. The business definition should be visible in business language. Technical details remain important, but they should be available as a deeper layer rather than acting as the entry barrier.

## Roles around a trusted metric

One role cannot cover every responsibility. Names vary across organizations, but responsibilities should remain visible.

| Role | Possible responsibility |
| --- | --- |
| Business Owner / Metric Owner | business meaning, purpose, approval and prioritization |
| Data Steward | definition, context, alignment, quality, change process and transparency |
| Data Product Owner | delivery and lifecycle of the underlying data product |
| Analytics Engineer / BI Developer | technical implementation and testing of calculation logic |
| Report Owner | correct usage, local extensions and communication context |
| Business User | usage, feedback and reporting of ambiguity or new requirements |

The data steward does not need to develop every DAX, SQL, Qlik or Excel formula. The role can focus on keeping the business definition and change process visible across technical boundaries.

## A possible target model for trusted metrics

A sustainable model can consist of seven steps:

1. **Define in business terms**  
   Describe the metric in understandable language, including purpose, scope, time context and known variants.

2. **Assign ownership**  
   Make a responsible business owner and data steward visible.

3. **Implement centrally**  
   Maintain calculation logic, filters and business rules in an appropriate shared location.

4. **Validate and certify**  
   Test data quality, calculation results, comparison values and business approval.

5. **Expose through a semantic layer**  
   Provide reports, applications and analytical tools with consistent access.

6. **Reuse across channels**  
   Allow BI, applications and Excel to create different views without reinventing the core meaning.

7. **Monitor usage and feedback**  
   Feed questions, deviations and new requirements into a controlled improvement process.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-metrics-img2-en.png"
        alt="Target model for trusted metrics with business definition, ownership, central calculation, validation, semantic layer and reuse across channels"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Trust is built through shared definition, visible ownership, understandable context and reuse – not by a technical label alone.
    </figcaption>
</figure>

## Reuse must not eliminate flexibility

A shared model succeeds only when people actually use it.

Too little governance can create many independent metric versions. Too much centralization can cause business teams to bypass the platform because every change is too slow or too complex.

Between those extremes lies a managed self-service model:

| Need | Useful approach |
| --- | --- |
| Visualize an existing metric differently | reuse the same shared metric |
| Local analysis with another filter | use the governed metric context and make the filter transparent |
| New business variant | define a clearly named variant with owner and scope |
| Experimental metric | test it as draft or clearly marked local logic |
| Frequently used local formula | review it and consider promoting it into the shared model |
| Business-process-critical metric | validate, document, version and certify it |

Governance should therefore do more than control. It should provide an easy path for turning a useful local idea into a shared definition.

## A simple lifecycle for metrics

A metric can have a lifecycle similar to a data product:

```flow linear vertical
Draft
Review
Approved
Certified
Monitored
Changed / Versioned
Deprecated
```

Changes should not happen invisibly.

A new definition can affect reports, applications, forecasts or contracts. The following information is therefore useful:

| Metadata | Guiding question |
| --- | --- |
| Business Definition | What does the metric mean in understandable language? |
| Formula / Logic | How is it calculated? |
| Scope | Which regions, products, processes or periods does it cover? |
| Owner | Who makes the business decision? |
| Steward | Who coordinates quality, documentation and changes? |
| Status | Draft, approved, certified or deprecated? |
| Version | Which definition applied at which point in time? |
| Sources | Which data products and fields are used? |
| Consumers | Which reports, applications or files use the metric? |
| Feedback | Where can users ask questions or report deviations? |

## Practical questions for teams

For every new KPI, measure or report calculation, the following questions can help:

1. **Does a comparable business metric already exist?**
2. **Do we need a new definition – or only a new presentation?**
3. **Where does the authoritative business logic live?**
4. **Which local extensions are allowed and how are they made visible?**
5. **Can business users understand the definition without reading technical code?**
6. **Are owner, steward, status and change history visible?**
7. **Can BI, applications and Excel reuse the same core metric?**
8. **How can a frequently used local formula be promoted back into the shared model?**

These questions do not replace a semantic layer or data catalog. They connect technical reuse with business accountability.

## Conclusion

Modern data platforms can centralize the storage, transformation and delivery of data. That alone does not guarantee consistent business answers.

Business meaning can continue to evolve in pipelines, views, semantic models, BI measures, applications and Excel. That flexibility is valuable. It becomes a risk only when the same metric is independently redefined and context, ownership or comparability are lost.

The missing piece is therefore not necessarily another tool. Often, it is the connection between:

```flowchart
shared definition
visible ownership
understandable context
technical reuse
continuous feedback
```

The key question is:

> **If several technically valid tools can calculate the same KPI differently, where does the trusted metric actually live?**

Perhaps the better answer is not *inside one specific tool*, but:

> **It lives in a shared, understood, owned and reusable definition.**

## Further resources

- [dbt Developer Hub: dbt Semantic Layer](https://docs.getdbt.com/docs/use-dbt-semantic-layer/dbt-sl)
- [dbt Developer Hub: Metric properties](https://docs.getdbt.com/reference/metric-properties)
- [Microsoft Learn: Managed self-service BI](https://learn.microsoft.com/en-us/power-bi/guidance/powerbi-implementation-planning-usage-scenario-managed-self-service-bi)
- [Microsoft Learn: Promote and certify Power BI content with endorsement](https://learn.microsoft.com/en-us/power-bi/collaborate-share/service-endorsement-overview)
- [Microsoft Learn: Power BI semantic model experience in Excel](https://learn.microsoft.com/en-us/power-bi/collaborate-share/office-integration/service-connect-excel-power-bi-datasets)
- [Microsoft Learn: Semantic models across workspaces](https://learn.microsoft.com/en-us/power-bi/connect-data/service-datasets-across-workspaces)
- [Qlik Help: Reusing measures with master measures](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Measures/create-master-measure.htm)
- [Qlik Help: Using master measures in expressions](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Measures/use-master-measures-expressions.htm)
- [Microsoft Learn: Glossary terms in Microsoft Purview Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-glossary-terms)
- [Research: Comprehensive and Comprehensible Data Catalogs](https://arxiv.org/abs/2103.07532)
