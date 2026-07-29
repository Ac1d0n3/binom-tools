---
title: "Fabric, Databricks, Snowflake or BigQuery — Choose the Governance Starting Point"
description: "Choose a governance platform starting point from the current estate, first governed use case, operating model and mandatory controls instead of running a feature beauty contest."
author: Thomas Lindackers
tags:
  - data-governance
  - platform-selection
  - microsoft-fabric
  - databricks
  - snowflake
  - bigquery
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/choose-governance-platform-starting-point-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance Platform Starting Points"
seriesPart: 1
---


## Problem

A platform decision becomes unreliable when it starts with product names. Fabric, Databricks, Snowflake and BigQuery can all support governed data delivery, but they begin from different architectural assumptions, cloud contexts, consumption patterns and operating models. A feature list alone does not show whether an organization can establish ownership, maintain business metadata, protect sensitive data, operate access controls, produce lineage and quality evidence, and support trusted consumption.

The real question is not:

> Which platform has the longest governance feature list?

It is:

> Which starting point can govern the first valuable data product with the least validated organizational friction?

That distinction matters because the technically strongest candidate can still be the wrong starting point. A platform may require identity changes, new skills, another catalog, additional licenses, regional exceptions, network changes or operating responsibilities that the organization cannot yet sustain. Conversely, the current stack may already meet the mandatory controls and only need clearer ownership, metadata and quality practices.

The comparison must therefore include five candidates:

- Microsoft Fabric
- Databricks
- Snowflake
- BigQuery
- no new platform

The fifth candidate is not a fallback for indecision. It is a valid outcome when the current estate can satisfy the required controls, the first governed use case does not justify migration, or the evidence for a platform change is incomplete.

![Start from Context, Not Product Names](images/playbooks/choose-governance-platform-starting-point-img1-en.png)

The starting context should be documented before any product is scored:

- existing cloud and platform estate
- dominant BI and consumption layer
- engineering, warehouse, streaming and machine-learning demand
- maturity of ownership and stewardship
- identity and access model
- catalog and metadata operating model
- PII, residency and network constraints
- delivery and operating capacity
- cost visibility and accountability

Product names should enter only after the mandatory controls, organizational fit and required evidence are defined.

## Decision

Choose the platform starting point that can establish accountable ownership, discoverability, protected sensitive data, controlled access, lineage, quality evidence and trusted consumption for the first governed use case. Treat the result as a validated starting point, not as permanent enterprise standardization.

### 1. Define the minimum governance contract

Before comparing platforms, specify what the first governed data product must prove.

At minimum, the contract should identify:

- the accountable Data Owner and operational Steward
- the business question and approved definition
- the source authority and target grain
- the permitted consumers and purposes
- the identity groups and access decision
- the PII or sensitivity classification
- the required masking, row filtering or other protection
- the lineage evidence from source to consumption
- the quality rules, thresholds and incident owner
- the publication, certification and deprecation process
- the operating team responsible after delivery

This contract separates a real governance requirement from a generic request for a new platform.

### 2. Select one first governed use case

Do not use a multi-year platform vision as the first test. Select a bounded product that is valuable enough to expose governance gaps but small enough to validate within a controlled proof of value.

A useful first use case has:

- a named decision or consumer
- a clear owner
- known source systems
- a stable initial grain
- at least one sensitive or controlled attribute
- measurable quality expectations
- a visible consumption path
- a realistic operational owner

The proof of value should test the complete control chain, not only ingestion or query performance.

![Match the First Governed Use Case to the Starting Point](images/playbooks/choose-governance-platform-starting-point-img2-en.png)

### 3. Use platform signals as hypotheses

The following signals narrow the investigation. They do not select a winner automatically.

#### Fabric

Fabric is a credible starting hypothesis when the estate is already Microsoft-centric, Power BI or semantic-model delivery is dominant, Entra-based identity is established, and the first governed product can benefit from a closely integrated analytics and consumption path.

Relevant evidence includes:

- how Fabric items, workspaces and domains map to accountable ownership
- how Microsoft Purview integration supports discovery, lineage and information protection
- how sensitivity labels, endorsements and certification fit the publication process
- whether the required Fabric and Purview capabilities are licensed and available in the target region
- whether the organization can operate capacity, tenant settings, workspaces and domain delegation consistently

The hypothesis weakens when the primary demand is cross-cloud engineering, extensive non-Microsoft processing, or an operating model that cannot coordinate Fabric and Purview responsibilities.

#### Databricks

Databricks is a credible starting hypothesis when the first use case is engineering-heavy, lakehouse-oriented, streaming-intensive or closely connected to machine learning and AI. Unity Catalog provides the governance control plane for governed data and AI assets inside the Databricks operating model.

Relevant evidence includes:

- the Unity Catalog metastore and workspace architecture
- ownership and privilege inheritance
- identity federation and group management
- table, volume, model and other securable-object coverage
- runtime lineage and audit evidence
- classification, tags, policies and quality monitoring required for the use case
- cloud-specific networking, storage and regional constraints
- the engineering capacity needed to operate pipelines, compute and governance together

The hypothesis weakens when the organization mainly needs a governed SQL warehouse and semantic delivery path but lacks the engineering capacity to operate the wider lakehouse environment.

#### Snowflake

Snowflake is a credible starting hypothesis when the first governed use case is centered on SQL analytics, governed warehouse consumption, data sharing or a cross-cloud data service. Snowflake Horizon Catalog and native policy objects can support discovery, lineage, classification and protection within the Snowflake estate.

Relevant evidence includes:

- the account, database, schema and role design
- ownership and role hierarchy
- tags, classification and policy assignment
- masking, row-access and other data-protection requirements
- lineage and access-history evidence
- secure sharing and consumer boundaries
- edition-dependent governance capabilities
- warehouse, storage and consumption cost accountability

The hypothesis weakens when the first product depends primarily on complex streaming, notebook-centric engineering or ML operations outside the intended Snowflake delivery model.

#### BigQuery

BigQuery is a credible starting hypothesis when the estate is GCP-native and the first use case benefits from serverless SQL analytics, Google Cloud IAM and a managed analytics operating model. Knowledge Catalog, formerly Dataplex Universal Catalog, provides catalog, glossary, metadata, lineage and data-quality capabilities around the Google Cloud data estate.

Relevant evidence includes:

- project, dataset and table ownership
- IAM and group design
- row-level access policies
- column-level protection through policy tags or current data-governance tags
- data masking requirements
- Knowledge Catalog metadata, glossary and lineage coverage
- automatic data-quality deployment and alert ownership
- regional location compatibility across BigQuery, policy and catalog resources
- slot, reservation or on-demand cost accountability

The hypothesis weakens when the organization is not prepared to operate GCP identity, projects, networking and regional resource dependencies as part of the governed product.

#### Current stack

The current stack is the preferred starting point when it can demonstrate the same mandatory controls without introducing a new platform. This may require process and metadata improvements rather than a migration.

Required evidence includes:

- named owners and stewards
- an approved business definition and grain
- searchable metadata or a controlled inventory
- enforceable access and protection
- lineage or reproducible source-to-consumer evidence
- executable quality checks and incident ownership
- trusted publication and deprecation rules
- acceptable operating cost and support

A “no new platform” decision is valid only when these controls are evidenced. It is not permission to leave governance implicit.

### 4. Separate starting point from standardization

The decision should state:

- where the first governed product starts
- which controls are proven
- which gaps remain
- which coexistence is accepted
- what would trigger broader standardization
- what would invalidate the decision

This prevents a proof of value from becoming an unreviewed enterprise mandate.

## Checklist

Use the same evidence categories for every candidate.

![Compare Governance Fit with the Same Evidence](images/playbooks/choose-governance-platform-starting-point-img3-en.png)

### Ownership and stewardship

- Is every governed product assigned to an accountable Data Owner?
- Can operational stewardship tasks be assigned and measured?
- Are platform administration and business accountability kept distinct?
- Is escalation defined when ownership is missing or disputed?

### Catalog and business metadata

- Can consumers find the product through approved business language?
- Are definitions, grain, owner, classification, freshness and permitted use visible?
- Can metadata be maintained through an operating workflow rather than one-time documentation?
- Can the catalog represent assets outside the candidate platform where required?

### Identity and access

- Does the candidate integrate with the authoritative identity provider?
- Are group ownership and lifecycle controlled?
- Can access be expressed at the required object, row and column level?
- Are privileged administration, break-glass access and audit evidence covered?

### PII classification and protection

- Can sensitive attributes be classified consistently?
- Can masking, filtering or other policies be enforced at query time?
- Does protection remain effective through extracts, shares, semantic models and downstream tools?
- Which capabilities require a higher edition, additional product or regional service?

### Lineage and source evidence

- Is lineage captured automatically for the required processing paths?
- Does it include the relevant tables, columns, jobs, notebooks, semantic models and reports?
- Can exceptions or unsupported transformations be documented?
- Is the evidence sufficient for impact analysis and incident investigation?

### Quality and incident ownership

- Can quality rules be versioned and executed?
- Are failures stored as evidence rather than only displayed?
- Is an incident routed to a named owner?
- Can consumers see whether a product is healthy, restricted or deprecated?

### Consumption and workload fit

- Does the platform fit the dominant BI and semantic layer?
- Does it support the required SQL, engineering, streaming and ML workloads?
- Are governed sharing and external consumers covered?
- Does the use case require another tool that would split metadata or control ownership?

### Cloud, residency and network fit

- Are all required services available in the target region?
- Can data residency and network isolation requirements be met?
- Are cross-region metadata, policy, replication or egress dependencies understood?
- Does the target design fit the existing cloud landing zone?

### Operating capacity

- Who operates the platform, catalog, identity, policies, quality and incidents?
- Are the required skills available internally?
- Is the support model clear?
- Can the organization maintain the controls after the project team leaves?

### Cost accountability

- Are platform, capacity, compute, storage, catalog, governance and network costs visible?
- Are edition and add-on dependencies included?
- Is a cost owner named?
- Can the cost of coexistence and migration be compared with the current stack?

A candidate should remain an open question when evidence is missing. Do not convert assumptions into scores.

## Artifact

Record the result in a **Platform Starting-Point Decision**. The artifact should be reviewable by business ownership, architecture, security, platform operations and delivery.

Record the decision with the [Governance Starting-Point Decision](/tools/governance-starting-point-decision) tool.

### Mandatory fields

| Field | Required decision evidence |
|---|---|
| First governed use case | Named product, consumers, business decision and scope |
| Existing context | Cloud, platforms, BI, identity, catalog, skills and constraints |
| Candidate | Fabric, Databricks, Snowflake, BigQuery or current stack |
| Strengths for this context | Evidence linked to the first use case |
| Governance gaps | Missing ownership, metadata, access, lineage, quality or lifecycle controls |
| Operating-model dependencies | Roles, teams, support and decision rights |
| Skills and capacity gap | Delivery and long-term operating capacity |
| Migration or coexistence impact | Data movement, duplicate controls, transition and retirement |
| Licensing and regional questions | Edition, add-on, preview, API and location dependencies |
| Proof-of-value test | Controls to demonstrate and acceptance evidence |
| Decision owner | Accountable approver |
| No-regret next step | Action useful even if the preferred candidate changes |

### Required outputs

- preferred starting point
- conditional alternative
- unresolved blockers
- validation plan
- explicit non-goals
- review date

### Decision rule

Reject a feature beauty contest. Approve a candidate only when it demonstrates better governance fit for the defined context and first governed use case.

A defensible decision can therefore read:

> Start with the current Microsoft estate and validate Fabric for the first governed semantic product. Keep Databricks as the conditional alternative if engineering and streaming requirements exceed the validated Fabric operating model. Do not standardize either platform until ownership, PII protection, lineage, quality evidence, support and cost accountability are demonstrated.

The same pattern can select any of the five candidates. The value lies in the evidence and conditions, not in the product name.

## Tools

Use tools to collect evidence, not to manufacture a universal score.

### Decision and operating-model tools

- first governed use-case canvas
- ownership and stewardship RACI
- mandatory-control checklist
- platform evidence matrix
- PII and access decision record
- lineage coverage map
- quality rule and incident register
- skills and operating-capacity assessment
- licensing and regional validation log
- proof-of-value acceptance record
- platform starting-point decision

### Platform control surfaces to validate

- **Fabric:** Fabric governance, domains, workspaces, endorsements, sensitivity labels and Microsoft Purview integration
- **Databricks:** Unity Catalog, account and workspace administration, privileges, lineage, audit and governed data/AI assets
- **Snowflake:** Horizon Catalog, role hierarchy, tags, classification, masking policies, row access policies, lineage and access history
- **BigQuery:** IAM, datasets, row-level access policies, column-level controls, data masking and Knowledge Catalog
- **Current stack:** existing catalog, IAM, metadata, quality, lineage and publication controls

Tool availability is not implementation evidence. Each control must be configured, operated and tested in the target context.

## Resources

Current product documentation should be checked again at the time of implementation because naming, licensing, APIs, previews, regional availability and limitations can change.

- [Microsoft Fabric governance documentation](https://learn.microsoft.com/en-us/fabric/governance/)
- [Use Microsoft Purview to govern Microsoft Fabric](https://learn.microsoft.com/en-us/fabric/governance/microsoft-purview-fabric)
- [Governance and compliance in Microsoft Fabric](https://learn.microsoft.com/en-us/fabric/governance/governance-compliance-overview)
- [Databricks: Data and AI governance with Unity Catalog](https://docs.databricks.com/aws/en/data-governance/)
- [Databricks: What is Unity Catalog?](https://docs.databricks.com/aws/en/data-governance/unity-catalog/)
- [Snowflake Horizon Catalog](https://docs.snowflake.com/en/user-guide/snowflake-horizon)
- [Snowflake data governance](https://docs.snowflake.com/en/guides-overview-govern)
- [Google Cloud Knowledge Catalog overview](https://docs.cloud.google.com/dataplex/docs/introduction)
- [Knowledge Catalog automatic data quality](https://docs.cloud.google.com/dataplex/docs/auto-data-quality-overview)
- [BigQuery row-level security](https://docs.cloud.google.com/bigquery/docs/row-level-security-intro)
- [BigQuery column-level access control](https://docs.cloud.google.com/bigquery/docs/column-level-security-intro)

## Playbooks

Use this story with the following decision and implementation playbooks:

- [Fabric vs Databricks — Choose the Governance Start](/stories/fabric-vs-databricks-governance-start)
- [Do Not Start with the Platform](/stories/do-not-start-with-the-platform)
- [Build the First Governed Vertical Slice](/stories/build-first-governed-vertical-slice)
- [Define the Data Product Contract](/playbooks/data-product-contract)
- [Define Ownership Before Tooling](/playbooks/data-ownership)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

The existing Fabric-versus-Databricks page remains a related standalone decision page. It should not be duplicated as another repeated platform comparison inside this series.

## Next step

Choose one first governed use case and complete the decision artifact before opening a procurement or standardization process.

The immediate no-regret actions are:

1. name the accountable owner and steward
2. define the business question, grain and consumers
3. document mandatory identity, PII, access, lineage and quality controls
4. collect the same evidence for all five candidates
5. identify licensing, API, preview and regional questions
6. run one proof of value across the complete governance chain
7. record the preferred starting point, conditional alternative and blockers
8. set a review date before broader standardization

The next story in the series can then evaluate the selected platform as a concrete governance starting point rather than as an abstract product winner.
