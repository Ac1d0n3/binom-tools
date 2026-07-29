---
title: "Microsoft Fabric as a Governance Starting Point"
description: "Use Microsoft Fabric as a governance starting point when the existing Microsoft and Power BI estate reduces delivery friction and accountable roles can define clear boundaries across domains, workspaces, catalog, access, lineage, quality, semantic models and capacity."
author: Thomas Lindackers
tags:
  - data-governance
  - microsoft-fabric
  - onelake-catalog
  - microsoft-purview
  - power-bi
  - data-products
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/microsoft-fabric-governance-start-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance Platform Starting Points"
seriesPart: 2
---


## Problem

Microsoft Fabric can shorten the path from source data to a Power BI product. Data Factory, Data Engineering, Data Warehouse, Real-Time Intelligence, Data Science, semantic models and reports can be operated inside one SaaS analytics environment, while OneLake provides the shared data foundation. For an organization with an established Microsoft tenant, Microsoft Entra ID, Power BI estate and Fabric capacity, that integration can remove real delivery friction.

It does not automatically create governance.

A workspace admin is not automatically the accountable Data Owner. A Fabric domain does not grant or deny access. An item owner in the catalog does not necessarily own the business definition, permitted use or quality risk. A certified report does not prove that every metric inside it has an approved definition, grain, calculation owner and reconciliation method. Native lineage does not guarantee complete source-to-consumer evidence across every workspace, item type and external transformation.

The decision question is therefore not:

> Does Fabric contain governance features?

It is:

> Can the organization use Fabric to establish the first practical governance foundation for a specific governed data product?

Fabric is a credible starting point when the existing Microsoft context reduces implementation friction and when accountable business and technical roles can define and operate boundaries across:

- tenant and identity
- capacities
- domains
- workspaces
- Fabric items and OneLake data
- semantic models and reports
- catalog and business metadata
- access and sensitive-data protection
- lineage, quality and audit evidence
- deployment, support and cost accountability

![Fabric Governance Surfaces and Decision Owners](images/playbooks/microsoft-fabric-governance-start-img1-en.png)

The platform surfaces form an implementation hierarchy. They do not form a hierarchy of business accountability.

### Platform surfaces are not ownership decisions

Fabric tenant, capacity, domain, workspace and item roles determine what users can configure, create, administer or consume. Those roles are necessary for operating the platform. They do not answer:

- why the data product exists
- which business decision it supports
- which definition is approved
- which use is permitted
- which quality risk is acceptable
- who approves an exception
- when the product must be recertified or retired

Those decisions belong to accountable Data Owners, supported by Data Stewards, security, architecture, platform operations and delivery teams.

### Integration can hide missing boundaries

A tightly integrated platform can make a weak operating model appear mature. Teams can quickly create workspaces, lakehouses, warehouses, semantic models and reports, but still lack:

- a stable domain model
- named ownership
- consistent workspace purposes
- governed security groups
- a metadata maintenance workflow
- complete quality evidence
- lifecycle controls
- capacity and cost accountability

The first Fabric governance decision must therefore define the boundary before scaling the platform.

## Decision

Use Microsoft Fabric as the governance starting point when its integrated analytics and Power BI context reduces delivery friction and when accountable roles can define clear boundaries across domains, workspaces, catalog, access, lineage, semantic models, evidence and capacity.

Treat Fabric as the operating surface for the first governed product, not as proof that governance already exists and not as the universal default for every Microsoft customer.

### 1. Start from the existing Microsoft context

Document the current estate before designing the target.

The starting assessment should include:

- Microsoft Entra tenant and identity model
- existing Power BI workspaces, semantic models, reports and gateways
- Fabric capacities, SKUs, regions and workload usage
- current tenant settings and delegated administration
- existing Microsoft Purview capabilities and licensing
- source systems and ingestion patterns
- data residency, network and private-access requirements
- current CI/CD, Git and deployment practices
- platform, BI, security and stewardship skills
- support model and cost ownership

Fabric is more likely to be a useful starting point when the first governed product already depends on Power BI, Microsoft identity and a Microsoft-operated analytics path. It is less compelling when the use case requires extensive cross-cloud engineering, an existing non-Microsoft catalog is authoritative, or the organization cannot operate Fabric and Purview as coordinated but distinct control surfaces.

### 2. Separate business domains from Fabric domains

Fabric domains logically group workspaces and their items and improve organization, discovery and delegated administration. Domain assignment does not itself change item visibility or access.

A Fabric domain should therefore implement an approved business-domain model; it should not invent that model.

Before creating or restructuring domains, decide:

- the business capability or subject area represented
- the accountable domain owner
- the Steward network
- the products and decisions in scope
- the rules for assigning workspaces
- delegated settings and administration
- shared-data and cross-domain dependencies
- exceptions and escalation

A domain can support federated governance, but it cannot replace ownership. Domain admins and contributors implement domain organization. Data Owners remain accountable for definitions, use, quality and risk.

### 3. Give every workspace an explicit operating purpose

A workspace is a collaboration, administration, lifecycle and security surface. It should not become the default unit for every governance decision.

Each governed workspace should record:

- purpose and lifecycle stage
- assigned Fabric domain
- accountable Data Owner for the products inside it
- workspace admin and technical owner
- contributor, member and viewer groups
- permitted item types
- environment: development, test or production
- deployment path
- capacity assignment
- support and incident route
- retention and retirement rule

Avoid creating workspaces only around team names when the product, environment and security boundaries differ. Avoid using one workspace for unrelated grains, owners or lifecycle stages merely because the same delivery team builds them.

### 4. Define where Fabric ends and Purview begins

Fabric and Microsoft Purview overlap, but they are not interchangeable.

![Where Fabric Ends and Purview Begins](images/playbooks/microsoft-fabric-governance-start-img2-en.png)

#### Fabric-native operating surface

Use Fabric-native capabilities for the daily operation of Fabric assets, including:

- domains and workspaces
- Fabric item discovery through the OneLake catalog
- item details, tags, endorsements and sensitivity labels where supported
- workspace lineage and impact analysis
- semantic-model and Power BI delivery
- tenant settings and delegated administration
- capacity assignment and monitoring
- Git integration and deployment pipelines where supported

The OneLake catalog is the Fabric-centered discovery and governance entry point. Its Explore experience helps users find Fabric items. Its Govern experience provides governance insights, recommended actions and links to relevant tools. These insights are based on Fabric metadata and have documented limitations; they are not a complete enterprise governance operating model.

#### Purview governance surface

Use Microsoft Purview when governance must extend across Fabric and the wider data estate. Depending on the selected Purview capabilities, licensing and regional availability, this can include:

- enterprise catalog and business glossary
- governance domains and data products
- Data Map scanning and metadata ingestion
- cross-platform discovery
- automated classification
- business metadata and terminology
- broader lineage and impact context
- information protection, audit and DLP capabilities
- data quality for supported sources and asset types

Fabric tenant scanning can bring Fabric metadata and lineage into Microsoft Purview. Current coverage and granularity vary by item type. For non-Power BI Fabric items, Microsoft documents limitations including item-level coverage for many objects, incomplete sub-item lineage and missing cross-workspace lineage in specific scenarios. Those limitations must be tested for the selected product.

#### Business governance

Neither Fabric nor Purview should decide:

- accountable ownership
- approved definitions
- permitted use
- risk acceptance
- exception approval
- certification criteria
- review and recertification
- deprecation

The platforms store, expose, enforce or evidence decisions. The operating model makes them.

### 5. Design identity and access as one controlled chain

The access model should begin with Microsoft Entra groups and named group owners, not with individual permissions added during delivery.

The design should distinguish:

- tenant administration
- capacity administration
- domain administration
- workspace roles
- item permissions
- OneLake table and folder permissions where applicable
- SQL endpoint or warehouse permissions
- semantic-model permissions
- report and app distribution
- row-level or object-level security in semantic models
- external sharing and guest access
- service principals and managed identities

Workspace membership alone is often too broad for a governed product. OneLake security can provide more granular read access to selected lakehouse tables and folders through custom roles, but the required access path must be tested across all engines and consumers used by the product.

For every access decision, record:

- business purpose
- identity group
- group owner
- granted surface
- permitted operations
- sensitive-data conditions
- approval
- expiry or review date
- evidence source

Do not confuse successful authentication with permitted business use.

### 6. Treat classification and sensitivity labels as controls, not ownership

Sensitivity labels from Microsoft Purview Information Protection can be applied to supported Fabric and Power BI items. They help classify and protect content, but they do not replace:

- field-level PII classification
- a permitted-use decision
- access design
- masking or filtering requirements
- retention and deletion rules
- incident ownership

The governed product should define a classification path from source attributes through transformed data, semantic models, reports and exports.

For each sensitive attribute, record:

- PII category
- sensitivity level
- authoritative classification decision
- allowed purpose
- access restriction
- masking, omission or filtering requirement
- export and sharing constraints
- retention rule
- downstream propagation expectation
- control owner

Because label support, inheritance and enforcement behavior can vary by Fabric item and consumption path, the proof of value must test the actual end-to-end flow.

### 7. Connect the governed data product to the semantic model

The semantic model is not merely a reporting convenience. It is the governed consumption contract for many Power BI estates.

The model should make explicit:

- business process and grain
- conformed dimensions and keys
- approved measures
- time and filter behavior
- calculation ownership
- row-level and object-level security
- freshness expectation
- quality state
- source lineage
- change and compatibility rules
- owner, Steward and technical owner
- publication and deprecation status

A report may consume a certified semantic model, but local report measures can still create definition drift. Certification must therefore be tied to evidence about the semantic model and its metrics, not only to a visible badge.

### 8. Build source-to-consumer evidence

![From Source to Trusted Power BI Product](images/playbooks/microsoft-fabric-governance-start-img3-en.png)

The first governed product should maintain an evidence chain across:

```text
Source
→ ingestion
→ transformation
→ governed data product
→ semantic model
→ certified metric or report
→ consumer
```

At every stage, record:

- owner
- grain
- classification
- access decision
- lineage
- quality evidence
- change record

Fabric lineage view shows relationships inside a workspace and one-step upstream external sources. Downstream dependencies in other workspaces require impact analysis, and wider estate lineage can require Purview scanning or supplementary evidence. Do not assume a complete graph because one lineage view looks connected.

Common failure cases are:

- workspace ownership mistaken for Data Ownership
- report certification without metric approval
- hidden local measures
- cross-workspace lineage gaps
- quality results visible only in a development notebook
- source changes without a consumer migration record
- a sensitive source label that is not validated through exports or downstream tools

### 9. Make quality operational

Quality is not complete when a rule executes successfully once. A governed Fabric product needs:

- approved rules linked to business expectations
- rule owner and incident owner
- thresholds and severity
- execution schedule
- stored failure evidence
- affected product and consumer mapping
- remediation workflow
- exception approval and expiry
- consumer-visible health state
- change and recertification triggers

Microsoft Purview data quality can assess supported Fabric Lakehouse tables after the required registration, scan and configuration. Current support, prerequisites, formats and licensing must be validated. Platform-native notebooks, pipelines, SQL tests or third-party tools may still be needed. The decision is not which screen displays a score; it is who owns the rule, failure and recovery.

### 10. Govern deployment, capacity and cost

Fabric capacity is a shared operating resource. A governed product can fail even when its data controls are correct if capacity saturation, deployment drift or unclear cost ownership makes the service unreliable.

The operating model should define:

- capacity owner
- capacity admin
- workload and workspace assignment rules
- monitoring and alerting
- scale, pause and reservation decisions where applicable
- cost allocation or showback method
- development, test and production separation
- Git and deployment-pipeline usage
- supported-item validation
- deployment approvals and rollback
- semantic-model compatibility requirements
- incident and service-level ownership

Deployment pipelines do not support every item in the same way, and support changes over time. For example, Microsoft retired deployment-pipeline support for semantic models without Enhanced Metadata on 12 February 2026. The product release process must therefore validate current supported items and limitations rather than assume that an entire workspace is deployable as one consistent unit.

## Checklist

Approve Fabric as the starting point only when the following evidence is available.

### Context and starting use case

- Is one first governed use case named?
- Are the business decision, consumers and value clear?
- Does the current Microsoft and Power BI context materially reduce delivery friction?
- Is the no-new-platform alternative documented?

### Ownership and decision rights

- Is a Data Owner accountable for purpose, definition, permitted use and risk?
- Is a Steward accountable for metadata, issue coordination and review evidence?
- Are platform admins clearly separated from business accountability?
- Are exception and escalation paths defined?

### Domain and workspace model

- Does every Fabric domain implement an approved business-domain decision?
- Does every workspace have a single explicit operating purpose?
- Are domain assignment, environment, product, security and lifecycle boundaries documented?
- Is workspace sprawl controlled?

### Catalog and Purview boundary

- Is the OneLake catalog used for Fabric-centered discovery and governance insights?
- Is Microsoft Purview used where enterprise catalog, glossary, wider classification or cross-platform metadata is required?
- Are duplicate metadata fields assigned to one authoritative workflow?
- Are scan, refresh and lineage limitations recorded?

### Identity and access

- Are Entra groups used instead of unmanaged individual grants?
- Is every group owned and reviewed?
- Are workspace, item, OneLake, SQL and semantic-model permissions designed together?
- Are service principals, guests and external sharing included?
- Can effective access be tested from source to report?

### PII and sensitivity

- Are sensitive attributes classified at field level?
- Is the sensitivity-label strategy defined for supported items?
- Are masking, filtering, omission and export controls explicit?
- Are inheritance and downstream behavior tested?
- Are retention and incident responsibilities assigned?

### Lineage and change evidence

- Is source-to-consumer lineage sufficient for impact analysis?
- Are cross-workspace and unsupported transformations documented?
- Does every deployment create a change record?
- Can consumers be identified before a breaking change?

### Quality and certification

- Are quality rules approved and versioned?
- Are failures stored and routed to a named owner?
- Does certification require definition, grain, lineage, quality and ownership evidence?
- Are local report measures controlled?
- Are recertification and deprecation triggers defined?

### Semantic and Power BI fit

- Is the semantic model the governed consumption contract?
- Are measure definitions, time behavior and filter semantics approved?
- Are row-level and object-level security tested?
- Are reports thin enough to avoid duplicated business logic?

### Deployment and environments

- Are development, test and production boundaries explicit?
- Are Git and deployment pipelines used only for supported items?
- Are approvals, rollback and compatibility tests defined?
- Is manual production change controlled and evidenced?

### Capacity, operations and cost

- Is a capacity owner named?
- Are workload consumption, throttling and service health monitored?
- Are support and incident routes documented?
- Are licensing, region and preview dependencies recorded?
- Can cost be attributed to products, workspaces or domains sufficiently for decisions?

Fabric remains conditionally ready when one or more mandatory controls depend on an untested capability, unclear ownership, unsupported lineage, missing operating capacity or unresolved licensing and regional questions.

## Artifact

Record the result in a **Fabric Governance Boundary Map** and a **Fabric Governance Readiness Decision**.

![Record the Fabric Governance Readiness Decision](images/playbooks/microsoft-fabric-governance-start-img4-en.png)

### Mandatory fields

| Field | Required evidence |
|---|---|
| First governed use case | Named product, business decision, consumers and initial scope |
| Tenant and capacity context | Tenant, region, capacity, SKU, current workloads and constraints |
| Domain and workspace model | Business-domain mapping, workspace purpose, environment and lifecycle |
| Data Owner and Steward | Accountable owner, operational Steward and escalation route |
| Catalog and Purview boundary | OneLake catalog role, Purview role, authoritative metadata workflow and handoffs |
| Identity and access model | Entra groups, workspace roles, item access, OneLake/SQL/semantic permissions and review |
| PII and sensitivity controls | Attribute classification, labels, masking/filtering, export, retention and incident controls |
| Lineage and quality evidence | Required path, available automation, manual evidence, quality rules and incident route |
| Semantic-model ownership | Grain, measures, security, owner, certification and deprecation |
| Deployment and environment model | Dev/test/prod, Git, pipelines, approvals, rollback and supported-item limitations |
| Capacity and cost accountability | Capacity owner, monitoring, allocation, cost owner and optimization decisions |
| Gaps and validation tests | Unknowns, unsupported paths, preview dependencies and proof-of-value tests |

### Required outputs

- **Ready for proof of value** — the first product can test the complete governance chain.
- **Conditional readiness** — Fabric is plausible, but named controls still require validation.
- **Blockers** — missing ownership, unsupported control path, capacity, licensing, region or operating constraints prevent the start.
- **No-new-platform alternative** — the existing stack can satisfy the same controls with less risk or cost.
- **Review date** — the decision must be reassessed after the proof of value or a material platform change.

### Boundary-map structure

The boundary map should contain five connected layers:

1. **Business governance** — owner, Steward, definitions, permitted use, quality risk, certification and lifecycle.
2. **Fabric organization** — tenant, capacities, domains, workspaces and item types.
3. **Control implementation** — identity, permissions, sensitivity, quality, deployment and monitoring.
4. **Metadata and evidence** — OneLake catalog, Purview, lineage, audit, quality results and change records.
5. **Consumption** — semantic models, certified metrics, reports, apps, Excel and other consumers.

Every handoff must name a decision owner, implementation owner and evidence source.

### Decision rule

Approve Fabric as the starting point only when the organization can state:

> Fabric will host and operate the first governed data product because the existing Microsoft and Power BI context reduces delivery friction. Business ownership, permitted use, quality and certification decisions remain outside platform administration. OneLake catalog and Purview responsibilities are explicit. Identity, sensitive-data protection, lineage, semantic-model governance, deployment, capacity and cost controls will be validated across the complete source-to-consumer path.

Do not approve the platform because it is already licensed, because Power BI is already used, or because a governance screen is available.

## Tools

Use tools to collect and maintain decision evidence.

### Governance Stack Advisor

Use the [Governance Stack Advisor](/tools/governance-stack-advisor) to compare Fabric with the current stack and any conditional alternative.

Expected output:

- current-context profile
- mandatory governance controls
- Fabric strengths and dependencies
- capability and operating-model gaps
- no-new-platform alternative
- validation questions

### Architecture Fit

Use [Architecture Fit](/tools/architecture-fit) to test workload, integration, identity, network, region, deployment, capacity and coexistence requirements.

Expected output:

- workload boundaries
- source and consumer constraints
- target workspace and capacity model
- integration and coexistence impact
- validation architecture
- decision risks

### Compliance Roadmap

Use the [Compliance Roadmap](/compliance) to connect PII, sensitivity, retention, access, audit and recertification requirements to an implementable control sequence.

### Fabric-specific working artifacts

- first governed use-case canvas
- Fabric domain and workspace register
- ownership and stewardship RACI
- Entra group and access matrix
- item-type control matrix
- PII and sensitivity propagation map
- Fabric-to-Purview metadata responsibility matrix
- lineage coverage map
- quality rule and incident register
- semantic-model certification record
- deployment and environment register
- capacity and cost accountability record
- Fabric Governance Boundary Map
- Fabric Governance Readiness Decision

Tool availability is not evidence. Every required control must be configured, operated and tested for the selected item types, licenses, region and consumption path.

## Resources

Microsoft Fabric and Purview capabilities, names, APIs, licensing, preview status, regional availability and limitations must be checked again when the decision is implemented. The following official references were verified for this article on 29 July 2026:

- [Microsoft Fabric overview](https://learn.microsoft.com/en-us/fabric/fundamentals/microsoft-fabric-overview)
- [OneLake catalog overview](https://learn.microsoft.com/en-us/fabric/governance/onelake-catalog-overview)
- [Govern Fabric data with the OneLake catalog](https://learn.microsoft.com/en-us/fabric/governance/onelake-catalog-govern)
- [Fabric domains](https://learn.microsoft.com/en-us/fabric/governance/domains)
- [Use Microsoft Purview to govern Microsoft Fabric](https://learn.microsoft.com/en-us/fabric/governance/microsoft-purview-fabric)
- [Microsoft Purview Data Map](https://learn.microsoft.com/en-us/purview/data-map)
- [Metadata and lineage from Fabric into Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-map-lineage-fabric)
- [Lineage in Fabric](https://learn.microsoft.com/en-us/fabric/governance/lineage)
- [Microsoft Fabric permission model](https://learn.microsoft.com/en-us/fabric/security/permission-model)
- [Apply sensitivity labels to Fabric items](https://learn.microsoft.com/en-us/fabric/fundamentals/apply-sensitivity-labels)
- [Promote and certify Power BI content with endorsement](https://learn.microsoft.com/en-us/power-bi/collaborate-share/service-endorsement-overview)
- [Data quality for Fabric Lakehouse in Microsoft Purview Unified Catalog](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-fabric-lakehouse)
- [Fabric deployment pipelines](https://learn.microsoft.com/en-us/fabric/cicd/deployment-pipelines/intro-to-deployment-pipelines)
- [Understand Microsoft Fabric licenses](https://learn.microsoft.com/en-us/fabric/enterprise/licenses)
- [Microsoft Fabric capacity planning](https://learn.microsoft.com/en-us/fabric/enterprise/capacity-planning-overview)
- [Cost considerations for Microsoft Fabric workloads](https://learn.microsoft.com/en-us/azure/well-architected/microsoft-fabric/cost-optimization)
- [What’s new in Microsoft Fabric](https://learn.microsoft.com/en-us/fabric/fundamentals/whats-new)

## Playbooks

Use this story with the following decision and implementation playbooks:

- [Fabric, Databricks, Snowflake or BigQuery — Choose the Governance Starting Point](/stories/choose-governance-platform-starting-point)
- [Fabric vs Databricks — Choose the Governance Start](/stories/fabric-vs-databricks-governance-start)
- [Do Not Start with the Platform](/stories/do-not-start-with-the-platform)
- [Build the First Governed Vertical Slice](/stories/build-first-governed-vertical-slice)
- [Define the Data Product Contract](/playbooks/data-product-contract)
- [Define Ownership Before Tooling](/playbooks/data-ownership)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

The platform-selection story defines when Fabric should enter the shortlist. This story defines what must be true before Fabric becomes the practical governance foundation for the first governed product.

## Next step

Choose one existing Power BI decision path and trace it back to its authoritative source.

Then complete the Fabric Governance Boundary Map:

1. name the Data Owner and Steward
2. define the business question, approved grain and consumers
3. assign the Fabric domain and workspace purpose
4. document the OneLake catalog and Purview boundary
5. design Entra groups and effective access
6. classify PII and sensitivity from source to export
7. map lineage and known coverage gaps
8. define quality rules, thresholds and incident ownership
9. assign semantic-model ownership and certification evidence
10. define development, test, production and deployment controls
11. assign capacity, support and cost accountability
12. run one proof of value across the complete source-to-consumer chain
13. record readiness, conditions, blockers, the no-new-platform alternative and the review date

The next platform-specific story can then test Databricks and Unity Catalog using the same governance evidence categories rather than repeating a generic feature comparison.
