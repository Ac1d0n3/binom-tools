---
title: Metadata, Catalog & Lineage
description: A practical operating model for understandable metadata, discoverable data assets and traceable data flows from source systems to business use.
author: Thomas Lindackers
category: Data Governance
tags:
  - data-governance
  - metadata
  - data-catalog
  - data-lineage
  - business-glossary
  - impact-analysis
  - data-discovery
order: -1
publishedAt: 2026-06-03
series: governance-pillars
seriesPart: 3
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/metadata-lineage-hero.png
---

## Data needs context

Data can be stored and processed correctly and still remain difficult to understand, hard to discover or risky to change.

Common questions remain unanswered:

- Which data assets exist?
- What does a table, column, metric or data product mean to the business?
- Who is accountable?
- Which data is trusted and approved for a specific purpose?
- Where did the data come from?
- Which transformations were applied?
- Which reports, models or processes are affected by a change?

**Metadata, Catalog & Lineage** provides the shared context that turns technical data structures into understandable and trusted data assets.

> *A catalog makes data discoverable. Metadata makes it understandable. Lineage makes its origin, dependencies and impact traceable.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/metadata-lineage-en.png"
        alt="Operating model for metadata, data catalog and data lineage showing core capabilities, process steps and governance outcomes"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Metadata, catalog and lineage connect technical structures with business context, ownership, quality and traceable data flows.
    </figcaption>
</figure>

## Metadata is more than technical properties

Metadata is often reduced to table names, columns and data types. Those technical details matter, but they answer only part of the questions users and governance teams need to resolve.

A reliable governance model connects several types of metadata:

| Metadata type | Typical content | Core question |
| --- | --- | --- |
| **Technical metadata** | Tables, columns, data types, models, files, jobs, APIs | How is the asset technically structured? |
| **Business metadata** | Definitions, glossary terms, domains, usage context | What does the data mean? |
| **Governance metadata** | Owners, stewards, PII, criticality, policies, retention | Who is accountable and which rules apply? |
| **Operational metadata** | Runtime, freshness, failures, usage, volume, SLAs | How does the asset behave in operation? |
| **Quality metadata** | Rules, test results, thresholds, incidents | Can the data be trusted? |
| **Semantic metadata** | KPIs, metrics, dimensions, calculation logic | How is the data interpreted and measured? |

Value does not come from collecting the largest possible number of fields. It comes from connecting the relevant information into usable context.

## The three core capabilities

### Metadata Management

Metadata Management defines, captures, standardizes and maintains context around data assets.

Typical responsibilities include:

- capturing technical metadata automatically from platforms and pipelines
- adding business descriptions and definitions
- standardizing terms, tags and classifications
- linking owners, stewards and domains
- maintaining PII, confidentiality and criticality classifications
- connecting quality, access and retention rules
- versioning changes and making them reviewable

Metadata should be maintained as close as possible to where it is created. Technical information belongs in automated integrations; business definitions require accountable roles and a controlled review process.

### Data Catalog

A Data Catalog is the usable interface to metadata. It helps people and systems find, understand, evaluate and correctly use data assets.

A useful catalog should support at least:

- search by names, descriptions, tags and business terms
- navigation by domain, data product and dependency
- visible owner and steward assignments
- business and technical definitions
- quality status, certification and known limitations
- classifications and protection requirements
- lineage and impact analysis
- links to documentation, code, models and reports

A catalog is not an end in itself and not merely a repository for automatically imported tables. It becomes valuable when users can answer one practical question faster: **Can I understand and use this data asset for my purpose?**

### Data Lineage

Data Lineage shows how data flows from a source through transformations, models and data products into reports, APIs or business processes.

Lineage supports:

- tracing origin and processing
- understanding dependencies across sources, models and reports
- assessing the impact of planned changes
- accelerating root-cause analysis for quality issues
- understanding how PII and governance metadata move through the data chain
- improving audit and compliance evidence
- increasing trust in metrics and data products

Lineage becomes particularly valuable when it does more than display technical nodes and also includes business context, ownership, quality and criticality.

## The Data Consumer is part of the model

Metadata and catalogs are not built for governance teams alone. They are built for people who need to find, understand, change or use data.

Data Consumers should be able to:

- find trusted data products quickly
- understand definitions and intended use
- identify owners and stewards
- see quality, freshness and known limitations
- use lineage to interpret data and ask informed questions
- report gaps, ambiguity or issues directly

A catalog with complete technical ingestion but little adoption does not solve the original problem. Usability and adoption are therefore part of governance effectiveness.

## The Metadata Operating Model

Metadata governance should be implemented as a continuous process, not as a one-time catalog project.

```flow linear vertical
Capture metadata from systems and pipelines
Organize and classify assets in the catalog
Map lineage across sources, models and reports
Review ownership, quality and business context
Use, improve and govern continuously
```

### 1. Capture metadata

Technical metadata should be collected automatically wherever possible from the relevant platforms.

Typical sources include:

- operational applications and databases
- data warehouses and lakehouses
- ETL and ELT platforms
- dbt projects
- orchestration tools
- BI and reporting platforms
- APIs, files and messaging systems
- data quality and observability tools

Automated ingestion reduces manual effort and keeps technical structures current. It does not replace business enrichment.

### 2. Organize and classify

Captured assets need an understandable structure.

Important organizing dimensions include:

- business domain
- data product or use case
- source and target platform
- asset type
- criticality
- confidentiality
- PII or privacy class
- lifecycle status
- certification or trust status

Classifications should be standardized and reusable wherever possible. Free text alone quickly creates duplicates and inconsistent meaning.

### 3. Map lineage

Lineage should make the chain required for decisions visible.

Depending on maturity and need, it can include several levels:

| Level | Example |
| --- | --- |
| **System lineage** | CRM → Warehouse → BI |
| **Dataset lineage** | Source table → RAW model → Business model → Report |
| **Column lineage** | `customer_email` → transformed column → analytics model |
| **Metric lineage** | Source fields → calculation → KPI → dashboard |
| **Process lineage** | Business process → application → data product → decision |

Not every organization needs complete column-level lineage immediately. Scope should be driven by risk, criticality and practical use cases.

### 4. Review context and accountability

Automatically imported assets are often technically complete but poorly described from a business perspective.

Owners and stewards should therefore review regularly:

- Is the business definition clear and current?
- Is the correct owner assigned?
- Are PII, criticality and confidentiality classified correctly?
- Are relevant quality rules and SLAs connected?
- Is lineage complete enough for analysis and change management?
- Are there duplicate or conflicting definitions?
- Is the asset active, deprecated or retired?

### 5. Use and improve continuously

Metadata quality is not created by a one-time cleanup. It must become part of daily data work.

This includes:

- integrating metadata into development and approval processes
- detecting missing mandatory information automatically
- reviewing changes to critical definitions
- analyzing usage and search behavior
- marking or hiding obsolete assets
- incorporating feedback from Data Consumers
- prioritizing and closing metadata and lineage gaps

## Business Glossary and Data Catalog are not the same

A Business Glossary defines business terms. A Data Catalog describes concrete data assets. The two should be connected.

| Business Glossary | Data Catalog |
| --- | --- |
| Defines terms such as “Active Customer” or “Net Revenue” | Shows tables, columns, models, reports and data products |
| Creates a shared business language | Makes concrete data discoverable and understandable |
| Has business owners and approvals | Connects technical and business metadata |
| Can apply independently of one platform | Refers to real assets in the data landscape |

One glossary term can be linked to multiple data assets. Conversely, one data asset can support several business terms.

## Lineage for impact analysis

One of the highest-value uses of lineage is assessing planned changes.

Before changing a source, model or KPI, teams should be able to answer:

- Which downstream models are affected?
- Which reports or data products use the data?
- Which critical KPIs depend on it?
- Will PII, access or retention rules be affected?
- Which owners and stewards need to be involved?
- Which tests and approvals are required?

A practical change process can look like this:

```flow linear vertical
Planned change
Analyze lineage and dependencies
Identify critical assets, KPIs and policies
Engage owners and affected teams
Plan tests, migration and communication
Implement the change and update lineage
```

Lineage does not reduce risk automatically. It provides the transparency required to identify risk early and manage it deliberately.

## Metadata as Code

In modern data stacks, part of the metadata can be versioned directly with code.

With dbt, for example, teams can use:

- model and column descriptions
- tests
- `meta` attributes
- tags
- owner information
- exposures
- sources
- contracts and constraints

Example:

```yaml
models:
  - name: customer_master
    description: Governed customer master used by analytics products.
    meta:
      domain: customer
      owner: customer-data-office
      criticality: high
      contains_pii: true
    columns:
      - name: customer_id
        description: Stable business identifier for a customer.
        tests:
          - not_null
          - unique
```

The benefit is versioning, reviewability and proximity to technical implementation. Not every business definition must live in YAML, however. A catalog can still provide the central search and consumption experience.

## Centralized or federated maintenance?

Metadata needs common standards, while business context originates in the domains.

For many organizations, a federated model works well:

- a central governance function defines minimum fields, taxonomies and quality rules
- platform teams automate technical ingestion and lineage
- domains maintain definitions, ownership and business context
- Data Stewards review quality and coordinate gaps
- Data Consumers provide feedback on clarity and usability

Too much centralization creates bottlenecks. Complete decentralization creates inconsistent terms and classifications. The objective is shared structure with distributed business accountability.

## A practical minimum viable metadata record

Not every asset requires complete documentation from day one. Critical data assets should, however, contain at least the following:

| Field | Purpose |
| --- | --- |
| **Name and asset type** | Identifies the data object |
| **Business description** | Explains meaning and intended use |
| **Domain or data product** | Places the asset in organizational context |
| **Data Owner** | Names the accountable decision-maker |
| **Data Steward** | Names the operational contact |
| **Source and key targets** | Provides basic origin transparency |
| **Criticality** | Determines governance intensity |
| **PII and protection classification** | Connects privacy and access requirements |
| **Quality or trust status** | Supports the decision to use the data |
| **Last review** | Shows whether the business context is current |

This foundation can later be extended with complete lineage, policies, SLAs, usage, data contracts and automated controls.

## Measuring metadata quality

The number of imported tables does not determine success. The usability and reliability of context does.

Useful indicators include:

- percentage of critical assets with business descriptions
- percentage of critical assets with accepted owners and stewards
- percentage of PII and confidential data classified
- percentage of critical data products with end-to-end lineage
- completeness of mandatory metadata fields
- freshness of ownership and review information
- number of obsolete or orphaned assets
- average time required to find an appropriate data asset
- searches with no relevant result
- catalog adoption by active consumers and domains
- average time required for impact analysis and root-cause analysis

These metrics should measure more than documentation completeness. They should show whether metadata improves real workflows.

## A simple maturity model

| Maturity level | Typical state |
| --- | --- |
| **Fragmented** | Knowledge lives in people, tickets, wikis and individual files |
| **Captured** | Technical metadata is collected centrally |
| **Described** | Critical assets have definitions, ownership and classifications |
| **Connected** | Catalog, glossary, quality and lineage are linked |
| **Operational** | Metadata drives impact analysis, approvals, policies and workflows |
| **Measured** | Quality, usage, freshness and governance effectiveness are monitored |
| **Embedded** | Metadata is created as part of development, operations and the data product lifecycle |

The objective is not maximum documentation. It is reliable context for data that creates high value, high usage or meaningful risk.

## Common anti-patterns

Metadata, catalog and lineage initiatives often fail for predictable reasons:

- treating the catalog as a tool-only project
- importing millions of technical objects without prioritization
- missing business definitions or descriptions that only repeat column names
- displaying owners whose accountability was never accepted
- maintaining metadata centrally even though business context lives in the domains
- ending lineage at the warehouse while ignoring BI, KPIs and downstream use
- assuming automatically generated lineage is complete without validation
- using conflicting terminology across catalog, glossary and technical documentation
- creating PII and governance tags that do not affect controls or workflows
- keeping obsolete assets visible and available for continued use
- measuring success by the number of cataloged objects rather than adoption and time saved
- forcing users to open a separate tool for every question without bringing context into their workflow

A good catalog reduces uncertainty. A poor catalog merely moves uncertainty into a new interface.

## Connecting to the other governance pillars

Metadata, Catalog & Lineage is the connective layer between many governance disciplines:

| Pillar | Connection |
| --- | --- |
| **Data Ownership & Stewardship** | Owners and stewards are linked directly to data assets |
| **PII & Privacy Governance** | Classifications become visible and traceable across lineage |
| **DSDR Governance** | Affected systems, models and data products can be identified across the landscape |
| **Data Quality Governance** | Rules, results and incidents are linked to assets and accountable roles |
| **KPI & Metric Governance** | Definitions and metric lineage create consistent meaning |
| **Access & Security Governance** | Protection classes and policies can be derived from metadata |
| **Data Lifecycle & Retention** | Retention and deletion rules are connected to data classes and assets |

Metadata is therefore more than documentation. It can become the control layer for operational governance.

## From visibility to operational governance

A practical implementation can follow this path:

```flow linear vertical
Systems + Pipelines + Models + Reports
Automated Technical Metadata
Business Definitions + Ownership + Classifications
Catalog + Search + Lineage + Impact Analysis
Quality + Access + Privacy + Retention + Workflows
Trusted Use + Continuous Improvement
```

The catalog is the visible interface. The actual value comes from connecting context, accountability, data flows and operational controls.

## The outcome

Effective Metadata, Catalog & Lineage creates:

- **Transparency** — data, meaning, origin and dependencies become visible
- **Trust** — shared definitions, ownership and quality context reduce uncertainty
- **Impact Analysis** — changes and issues can be assessed faster and more safely
- **Compliance** — classifications, origin and controls become more traceable
- **Efficiency** — less search effort, less duplicate work and faster troubleshooting
- **Business Value** — usable data is found faster and understood more clearly

Metadata provides the context. The catalog makes it accessible. Lineage connects the data chain and reveals impact.

Together, they create the transparency governance needs to become not only documented, but operationally effective.
