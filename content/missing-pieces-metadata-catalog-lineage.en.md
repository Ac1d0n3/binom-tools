---
title: "The Missing Pieces – Part 4: Metadata, Catalog & Lineage"
description: "Why technical visibility does not automatically create business understanding – and how catalogs, glossaries, context and stewardship can bridge the gap."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - metadata-management
  - data-catalog
  - data-lineage
  - business-glossary
  - data-discovery
  - business-context
  - data-stewardship
  - data-governance
order: -1
hero: images/playbooks/mp-meta-hero.png
series: missing-pieces
seriesPart: 4
seriesTitle: The Missing Pieces
---

## Technical visibility is valuable – but it is not the same as understanding

Modern data platforms can collect and expose an enormous amount of information about data:

- databases, schemas, tables and columns,
- files, APIs and streaming sources,
- pipelines, jobs and transformations,
- dependencies between upstream and downstream assets,
- classifications and sensitivity labels,
- quality results, usage signals and ownership assignments,
- reports, dashboards, models and data products.

This visibility is a major improvement over undocumented data estates and isolated knowledge in individual teams.

It helps answer questions such as:

> *Where does this field come from?*

> *Which reports depend on this table?*

> *What may be affected if this model changes?*

> *Where does sensitive information flow?*

But business users often begin with different questions:

> *What does this number mean?*

> *Which definition is approved for my use case?*

> *Why does this data exist?*

> *Can I use it for this decision?*

> *Who can explain it or approve a change?*

The difference is important:

> **Metadata can make data visible. Context makes it understandable.**

This playbook does not question the value of catalogs, metadata harvesting or lineage. It explores the gap between technical transparency and shared business understanding.

## Metadata, catalog and lineage solve different problems

The terms are related, but they are not interchangeable.

| Capability | Primary purpose | Typical questions |
| --- | --- | --- |
| Metadata Management | collect, structure, enrich and maintain information about data assets | What is this asset, how is it structured and what information is known about it? |
| Data Catalog | make governed data assets searchable, discoverable and reusable | What data exists, which asset is relevant and how can I access or use it? |
| Business Glossary | establish shared business language and controlled definitions | What do Customer, Revenue, Active Contract or Churn mean in this organization? |
| Technical Lineage | show technical flow, dependencies and transformations | Where does the data come from and what is affected by a change? |
| Business Lineage | summarize the path from business concepts and sources to products, metrics and reports | How does business meaning move from origin to decision? |
| Data Quality Context | expose expectations, results, incidents and fitness for use | Is the data suitable for this purpose and which limitations are known? |
| Ownership & Stewardship | connect assets and definitions to accountable people and processes | Who maintains the context, decides on meaning and coordinates issues? |

A mature governance experience connects these capabilities.

A catalog without business meaning can become a searchable inventory of technical objects.

A glossary without links to real assets can become a dictionary disconnected from daily work.

Lineage without context can become a detailed graph that shows every path but explains little about purpose.

The value emerges through the relationships between them.

## The gap between technical visibility and business understanding

Technical metadata is often easier to automate because systems can expose schemas, object names, data types, jobs and execution relationships.

Business meaning is different. It depends on organizational language, process knowledge, intended use, policy, interpretation and sometimes negotiation between domains.

A scanner can usually discover that a column exists.

It cannot always determine reliably:

- what the field means in business language,
- whether its name is misleading,
- which definition is approved,
- why a transformation rule exists,
- which exceptions are accepted,
- whether a KPI is suitable for a specific decision,
- which business process creates or consumes the data,
- who should resolve ambiguity between departments.

This does not make automated harvesting less important. Automation is necessary for scale.

It means that automation and stewardship have different strengths:

> **Machines can collect structure and evidence at scale. People still have to establish meaning, purpose and accountability.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-meta-img1-en.png"
        alt="Diagram comparing technical metadata visibility with the business context required for understanding and trusted decisions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technical visibility answers where data comes from and how it moves. Business understanding adds meaning, purpose, ownership, impact and policy context.
    </figcaption>
</figure>

## More metadata does not automatically mean easier discovery

A catalog can contain millions of assets and still leave users uncertain about where to start.

Search results may include:

- several physical tables with similar names,
- historical versions,
- staging and intermediate models,
- multiple domain views,
- certified and non-certified datasets,
- report-specific extracts,
- technical objects that are important for operations but not intended for direct business use.

All of these assets may be correctly cataloged.

The remaining challenge is navigation.

A user asking for *active customer revenue* may not know whether to start with:

`raw.crm_account`

`stg_salesforce_account`

`conformed_customer`

`mart_revenue_customer_month`

`semantic_sales_model`

or an approved report.

A useful catalog therefore needs more than complete indexing. It needs meaningful entry points.

Possible business-oriented entry points include:

- business domains,
- data products,
- critical data elements,
- business terms,
- trusted metrics,
- certified semantic models,
- approved reports,
- common business questions,
- process or capability maps.

The technical assets remain available as evidence and implementation detail. They do not have to be the first thing every user sees.

## The catalog should help users choose – not only find

Discovery has at least three levels:

1. **Findability** – Can the user locate relevant assets?
2. **Understandability** – Can the user understand purpose, meaning and limitations?
3. **Decision support** – Can the user determine which asset is appropriate for the intended use?

A search result is not yet a trusted choice.

To support a choice, users may need:

| Context | Example |
| --- | --- |
| Business purpose | Monthly revenue reporting for active subscriptions |
| Definition | Net recurring revenue after approved exclusions |
| Grain | One record per customer, contract and month |
| Time logic | Calendar month, posted transactions only |
| Scope | EMEA direct business; partner revenue excluded |
| Quality status | Certified; freshness target met; one known limitation |
| Owner | Commercial Finance Data Owner |
| Steward | Revenue Data Steward |
| Primary consumers | Finance dashboard, forecast process, management report |
| Usage guidance | Approved for monthly reporting; not intended for real-time operations |
| Related assets | semantic model, KPI definition, source systems and reports |

The purpose is not to create the longest possible metadata form.

The purpose is to provide the minimum context required for a confident decision.

## Technical lineage is evidence – business context is interpretation

Technical lineage is highly valuable for impact analysis, root-cause investigation, change planning and tracing sensitive data.

It can show a path such as:

Source System  
→ Ingestion  
→ Raw Layer  
→ Transformation  
→ Data Mart  
→ Semantic Model  
→ Dashboard

That path answers important questions:

- Which upstream assets contribute to the result?
- Which downstream objects may be affected by a change?
- Where is a field transformed?
- Which reports consume the output?

But the path alone may not explain:

- why a source is authoritative,
- which business rule is applied,
- why a record is excluded,
- which definition the metric represents,
- whether the dashboard is approved for a specific decision,
- which team owns the meaning,
- which known limitations affect interpretation.

This is not a weakness of lineage. It is a boundary between technical traceability and business explanation.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-meta-img2-en.png"
        alt="Comparison of technical lineage, which shows structure and dependencies, with business understanding, which explains meaning, purpose, ownership and impact"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Lineage shows the path. Business terms, rich metadata, context mapping, stewardship and continuous maintenance explain what the path means.
    </figcaption>
</figure>

## Technical lineage and business lineage should complement each other

Technical lineage is often detailed and system-oriented.

Business lineage is usually more selective and outcome-oriented.

| Technical lineage | Business lineage |
| --- | --- |
| tables, columns, files and jobs | business concepts, products, metrics and reports |
| detailed transformation paths | understandable end-to-end summary |
| implementation and dependency focus | purpose, meaning and impact focus |
| useful for engineers, analysts, architects and auditors | useful for owners, stewards, business users and decision-makers |
| can be captured largely through automation | often requires curation and mapping |
| supports technical impact analysis | supports business impact awareness |

Neither view should replace the other.

A simplified business lineage without technical evidence can become too abstract.

A complete technical graph without a business view can become too complex for many users.

A useful model allows people to move between levels:

Business Term  
→ Trusted Metric  
→ Data Product  
→ Semantic Model  
→ Data Mart  
→ Transformations  
→ Source Fields

The user can begin with meaning and drill into technical evidence when needed.

## Business meaning should be linked – not copied into every asset

A common maintenance problem appears when the same definition is copied into dozens of tables, columns, reports and documents.

The copied text eventually diverges.

A stronger pattern is to govern reusable concepts centrally and link them to relevant assets.

For example:

**Business Term: Active Customer**

Definition: A customer with at least one active, billable contract on the reporting date.

Linked to:

- customer status field,
- conformed customer model,
- active customer KPI,
- customer semantic model,
- commercial dashboard,
- retention policy or quality rule where relevant.

The central concept can have:

- an owner,
- a steward,
- approval status,
- version history,
- examples,
- synonyms,
- related terms,
- policy references.

The technical assets can add local implementation details without redefining the concept independently.

This reduces duplication while keeping context close to use.

## Not every metadata field deserves the same governance effort

A common reaction to incomplete context is to add more mandatory fields.

That can improve completeness, but it can also reduce participation when every asset requires the same level of manual documentation.

Governance should be proportionate to value, risk and reach.

One possible tiered approach is:

| Asset type or tier | Suggested metadata depth |
| --- | --- |
| Raw or intermediate technical asset | automated technical metadata, classification, owner of the platform or pipeline, lineage where available |
| Reusable domain dataset | purpose, domain, owner, steward, grain, freshness, quality status, usage guidance and linked business terms |
| Critical data product | full business context, consumers, service expectations, quality rules, policies, lifecycle and end-to-end lineage |
| Enterprise KPI | approved definition, calculation logic, filters, time logic, owner, steward, certification, change history and linked reports |
| Regulatory or high-risk data | detailed classification, policy, access, retention, evidence, control ownership and review cadence |
| Experimental asset | lightweight metadata, clear draft status, creator, purpose and expiry or review date |

This avoids two extremes:

- documenting every temporary object manually,
- leaving critical business assets with only technical metadata.

## Automated metadata and human curation should be designed together

Automation can contribute:

- schema and column discovery,
- data types and technical properties,
- classifications,
- lineage from supported systems,
- job and pipeline relationships,
- usage statistics,
- freshness and execution metadata,
- quality test results,
- change detection.

Human curation is particularly important for:

- business definitions,
- purpose and intended use,
- examples and exceptions,
- criticality,
- approval and certification,
- ownership decisions,
- business impact,
- known limitations,
- cross-domain interpretation.

The objective should not be to replace people with scanning or to document everything manually.

A practical model is:

> **Automate what systems know. Curate what the business must agree.**

## Metadata quality is a governance topic of its own

Metadata can also be incomplete, outdated, inconsistent or difficult to trust.

Typical metadata-quality questions include:

- Is the owner still in the role?
- Is the description current?
- Does the lineage cover the full path or only one platform?
- Is the certification status still valid?
- Are glossary terms linked to the correct assets?
- Is a deprecated table still presented as a recommended source?
- Are known limitations visible?
- Is the documented grain consistent with the actual data?

A catalog should therefore not only store metadata. It should make metadata health visible.

Possible indicators include:

| Indicator | What it may reveal |
| --- | --- |
| Description Coverage | whether relevant assets have understandable descriptions |
| Business-Term Linkage | whether technical assets are connected to governed concepts |
| Ownership Coverage | whether critical assets have current contacts |
| Stewardship Acceptance | whether assigned responsibilities are acknowledged |
| Lineage Coverage | how much of the required end-to-end path is visible |
| Certification Freshness | whether approvals have been reviewed within the expected period |
| Stale Metadata Rate | how many descriptions, owners or links may be outdated |
| Deprecated Asset Usage | whether consumers still depend on retired or discouraged assets |
| Search Success | whether users find an appropriate asset without repeated support requests |
| User Feedback | whether descriptions and context are considered understandable and useful |

Coverage percentages require interpretation.

A catalog can reach high metadata completeness while users still struggle to choose the right asset.

The outcome matters more than the number of populated fields.

## The last mile of lineage matters

Many data paths do not end at a warehouse table.

They continue into:

Semantic Model  
→ BI Measure  
→ Report  
→ Export  
→ Spreadsheet  
→ Presentation  
→ Business Decision

Not every downstream step can be captured automatically across every tool.

This creates practical questions:

- Does lineage include the semantic layer?
- Are report-level measures visible?
- Can users see which certified data product a dashboard uses?
- Are exports and local calculations treated as governed outputs or personal analysis?
- Can an important spreadsheet be registered as a business asset when it becomes operationally relevant?
- Is the decision context linked to the data used?

The goal is not to govern every private calculation with enterprise-level process.

The goal is to identify when local use becomes shared, recurring or business-critical.

At that point, context and accountability should follow the importance of the output.

## A catalog should support different users with different views

The same metadata graph serves different needs.

| User | Likely first questions |
| --- | --- |
| Business User | What data should I use and what does it mean? |
| Data Steward | Which definitions, issues and metadata require attention? |
| Data Owner | Which critical assets, risks and decisions are in my scope? |
| Analyst | Which dataset is suitable, at what grain and with which limitations? |
| Data Engineer | Where does the data come from and what depends on this change? |
| Architect | How do domains, platforms, products and interfaces connect? |
| Security / Privacy | Where does sensitive data exist and where does it flow? |
| Auditor | Which controls, approvals, owners and evidence apply? |

One interface does not need to show everything at once.

Progressive disclosure can help:

1. Start with business name, purpose, status and owner.
2. Show quality, freshness, usage guidance and related assets.
3. Offer lineage and technical implementation details on demand.
4. Provide policy, audit and operational evidence for specialist roles.

This keeps technical depth without making it the only entry point.

## Stewardship keeps meaning connected to reality

Catalogs and lineage are not one-time implementation projects.

Business definitions change. Systems are replaced. Metrics are revised. Reports are retired. New regulations create new classifications and controls.

Without stewardship, the catalog can slowly become a historical record of what was once true.

Stewardship can support continuity by:

- reviewing critical definitions,
- validating ownership and contacts,
- linking new assets to existing concepts,
- resolving duplicate or conflicting terms,
- reviewing certification and usage guidance,
- documenting known limitations,
- coordinating feedback from users,
- ensuring that deprecated assets are clearly marked,
- checking whether lineage and context remain complete after change.

The steward should not have to maintain every technical detail manually.

The role is to protect meaning, quality and usability while automation maintains much of the technical evidence.

## Common anti-patterns

### The catalog as a technical inventory

Millions of assets are scanned, but business users cannot identify approved entry points.

### The glossary as a separate dictionary

Definitions exist, but they are not linked to datasets, metrics, reports or processes.

### Lineage as a wall of nodes

The graph is technically complete but too detailed to explain impact to non-specialists.

### Mandatory metadata without clear value

Users fill fields because the workflow requires it, but the information does not help discovery, decisions or control.

### Copying definitions everywhere

The same business meaning is maintained independently in models, reports, catalog fields and documents.

### Certification without review

An asset remains marked as trusted even though ownership, logic or usage has changed.

### Automated completeness without human validation

Descriptions and classifications are generated at scale but are not reviewed where business impact is high.

### Business context without technical evidence

A polished data product page exists, but users cannot trace the source, transformation or affected downstream assets.

## A practical model for metadata that creates understanding

One possible approach is:

1. **Identify business entry points**  
   Start with domains, data products, critical metrics, reports and business terms that users actually search for.

2. **Harvest technical metadata automatically**  
   Scan supported platforms, schemas, pipelines, models, classifications and lineage.

3. **Connect business concepts to technical assets**  
   Link glossary terms, processes, capabilities, KPIs and policies to the assets that implement them.

4. **Add minimum useful business context**  
   Describe purpose, scope, grain, intended use, owner, steward, quality status and known limitations.

5. **Create understandable lineage views**  
   Offer both detailed technical lineage and simplified business-oriented paths.

6. **Make trusted choices visible**  
   Use certification, lifecycle status and usage guidance to distinguish recommended assets from drafts, legacy objects and technical intermediates.

7. **Integrate feedback and stewardship**  
   Allow users to ask questions, report ambiguity and propose improvements where they consume data.

8. **Review metadata as part of change**  
   Update context, links, ownership and certification when data products, pipelines or reports change.

9. **Measure outcomes, not only completeness**  
   Track whether users find, understand and correctly use data with less support effort.

## Practical questions for teams

For a critical data product, metric or report, ask:

1. **Can a business user find it without knowing technical object names?**
2. **Is its purpose understandable in business language?**
3. **Is the definition linked to governed business terms?**
4. **Are owner and steward visible and current?**
5. **Can users see the intended use, grain, scope and known limitations?**
6. **Is the quality and freshness status understandable?**
7. **Does lineage reach from relevant sources to the point of consumption?**
8. **Can users switch between business and technical lineage views?**
9. **Are approved, draft, legacy and deprecated assets clearly distinguished?**
10. **Is duplicated business meaning linked centrally rather than copied repeatedly?**
11. **Can users provide feedback without leaving their normal workflow?**
12. **Is metadata reviewed when the underlying data product changes?**

These questions do not require every organization to implement the same catalog model.

They help test whether metadata supports real understanding and use.

## Conclusion

Metadata, catalogs and lineage are not missing capabilities. Modern platforms already provide powerful ways to discover assets, capture technical relationships, enrich context and trace data flows.

The possible gap appears when technical visibility is treated as the final outcome.

A complete map does not automatically create a shared language.

A detailed lineage graph does not automatically explain business meaning.

A searchable catalog does not automatically tell users which asset is appropriate for their decision.

The bridge requires:

> **technical metadata → business terms → purpose and context → ownership → quality and policy → understandable lineage → continuous stewardship**

The central question is therefore:

> **Do we have more metadata – or more understanding?**

Perhaps the goal of metadata governance is not to document everything equally.

Perhaps it is to make the most important data understandable enough that people can find it, evaluate it, use it correctly and know who can help when meaning is unclear.

## Further resources

- [Microsoft Learn: Microsoft Purview Data Governance glossary](https://learn.microsoft.com/en-us/purview/data-governance-glossary)
- [Microsoft Learn: Microsoft Purview Data Map](https://learn.microsoft.com/en-us/purview/data-map)
- [Microsoft Learn: Search for and manage data assets in Microsoft Purview Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-data-assets-search)
- [Microsoft Learn: Data lineage in Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-gov-classic-lineage)
- [Databricks Documentation: Lineage in Unity Catalog](https://docs.databricks.com/aws/en/data-governance/unity-catalog/data-lineage)
- [Databricks Documentation: External lineage in Unity Catalog](https://docs.databricks.com/aws/en/data-governance/unity-catalog/external-lineage)
- [Databricks Documentation: What is Unity Catalog?](https://docs.databricks.com/aws/en/data-governance/unity-catalog/)
- [Collibra Product Resource Center: Business Glossary](https://productresources.collibra.com/docs/collibra/latest/Content/BusinessGlossary/to_business-glossary.htm)
- [Collibra Product Resource Center: About Data Catalog](https://productresources.collibra.com/docs/collibra/latest/Content/Catalog/to_catalog.htm)
- [Collibra Product Resource Center: About Collibra Data Lineage](https://productresources.collibra.com/docs/collibra/latest/Content/CollibraDataLineage/co_collibra-data-lineage.htm)
- [AWS Documentation: Amazon DataZone concepts](https://docs.aws.amazon.com/datazone/latest/userguide/datazone-concepts.html)
- [AWS Documentation: Create and maintain a business glossary in Amazon DataZone](https://docs.aws.amazon.com/datazone/latest/userguide/create-maintain-business-glossary.html)
