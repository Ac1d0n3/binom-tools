---
title: "Governance Across Multiple Data Platforms"
description: "Govern Fabric, Databricks, Snowflake, BigQuery, dbt and downstream consumption through one authority per governance concern, explicit platform enforcement boundaries and reviewable cross-platform evidence handoffs."
author: Thomas Lindackers
tags:
  - data-governance
  - multi-platform
  - governance-architecture
  - metadata
  - lineage
  - access-control
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/governance-across-multiple-data-platforms-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance Platform Starting Points"
seriesPart: 7
---

## Problem

Most organizations do not operate one analytical platform. A realistic estate may combine Microsoft Fabric and Power BI, Databricks for engineering and AI, Snowflake or BigQuery for warehouse workloads, dbt for transformations, operational databases, SaaS sources, APIs, Excel and several BI tools.

The problem is not coexistence itself. The problem is duplicated authority.

When every platform becomes its own governance center, the organization accumulates:

- several business glossaries;
- conflicting owner records;
- different PII classifications;
- repeated masking and row-filter logic;
- copied transformations;
- local semantic metrics;
- incomplete lineage graphs;
- multiple incident queues;
- inconsistent retention rules;
- unclear cost ownership.

Each tool may be internally correct while the end-to-end control is wrong.

The relevant question is therefore not:

> Which platform should own governance?

It is:

> Which system or role is authoritative for each governance concern, where is the decision enforced, where is the evidence retained and how do all platforms subscribe without creating competing authorities?

Three misunderstandings create most cross-platform failure.

### Central catalog is mistaken for universal authority

A central catalog can index assets, display business terms and connect lineage. It should not automatically become the authoring authority for every technical fact, transformation definition, platform privilege, metric behavior or runtime status. Metadata remains reliable only when each field has provenance and an explicit authority.

### Platform-native enforcement is mistaken for policy ownership

A platform may implement masking, row filters, tags, privileges, retention settings or workspace boundaries. The platform control does not own the approved business purpose or exception decision. Several platforms may enforce one policy; only one authority should approve the policy.

### Integration is mistaken for governance

Synchronizing metadata through APIs creates transport, not decision rights. An integration can replicate conflicts faster. Cross-platform governance needs precedence rules, stable identifiers, validation, exception ownership and recertification.

![Assign One Authority per Governance Concern](images/playbooks/governance-across-multiple-data-platforms-img1-en.png)

## Decision

Govern a multi-platform estate by assigning one authority per governance concern, allowing platform-specific enforcement where required and retaining cross-platform evidence at every material handoff.

The operating model should record five elements for every concern:

1. authoritative system or role;
2. enforcement point or points;
3. evidence location;
4. downstream subscribers;
5. exception owner.

Multiple platforms can consume or enforce the same decision. They must not silently become competing authorities.

### 1. Build the authority matrix by concern

Use a concern-by-concern model rather than selecting one “master platform.”

| Governance concern | Typical authority | Typical enforcement | Evidence |
|---|---|---|---|
| Business glossary | Governance workflow and accountable business owner | Catalog and consumer interfaces | Approved term version and review record |
| Data Ownership | Operating model or product registry | Catalog display, workflow routing and escalation | Owner acceptance and review date |
| Technical catalog | Source platform or technical metadata index | Platform inventory and central index | Object identifier, schema and collection timestamp |
| Identity | Corporate identity provider | Platform IAM, groups and service identities | Membership, authentication and access review |
| PII classification | Privacy or governance authority | Tags, labels, policies and downstream propagation | Classification decision and evidence |
| Access policy | Data Owner with security or privacy standards | Platform privileges, masks, filters and sharing controls | Effective-access tests and audit logs |
| Retention | Records, legal or policy authority | Storage lifecycle, table policy, deletion jobs | Retention assignment and deletion evidence |
| Lineage | Runtime systems plus central lineage index | Instrumentation and metadata ingestion | Stable edge identifiers and collection state |
| Quality evidence | Data product contract and engineering execution | Tests, monitors and publication gates | Results, failures, incidents and exceptions |
| Semantic metrics | Metric owner and semantic governance process | Shared semantic layer and BI consumers | Definition, grain, approval and usage |
| Incident coordination | Product operating model | Service desk, alerting and platform queues | Incident record, owner and closure |
| Cost accountability | Product owner and FinOps model | Billing tags, capacities, warehouses and reservations | Usage, attribution, budget and escalation |

The matrix prevents a false choice between “central” and “federated.” Authority can be centralized for one concern and federated by domain for another, as long as each record has one accountable source.

### 2. Use stable identifiers across platform handoffs

Names are not sufficient. `customer`, `dim_customer` and `Customer Master` can refer to the same asset, different assets or several versions of one product.

Define stable identifiers for:

- business terms;
- data products;
- source objects;
- transformation models;
- warehouse or lakehouse objects;
- semantic models and metrics;
- policies;
- owners and organizational units;
- quality rules;
- incidents and exceptions;
- deployment versions.

The handoff chain should be explicit:

```text
source
→ ingestion platform
→ transformation layer
→ warehouse or lakehouse
→ semantic model
→ BI, API or AI consumer
```

![Cross-Platform Lineage and Evidence Handoffs](images/playbooks/governance-across-multiple-data-platforms-img2-en.png)

At every handoff retain:

- asset identifier;
- owner;
- classification;
- lineage link;
- quality status;
- deployment version;
- access-policy reference;
- incident route.

Broken handoffs and duplicate identifiers must be visible as exceptions. Do not merge records based only on similar names.

### 3. Keep metadata close to its operational authority

A practical multi-platform architecture does not copy every field into one place and declare the copy authoritative.

Use these default principles:

- Source systems remain authoritative for original technical identifiers and source constraints.
- Transformation repositories remain authoritative for derived SQL logic, tests and code versions.
- Platform catalogs remain authoritative for current objects, privileges and runtime metadata they can observe.
- The business glossary remains authoritative for approved terms and meanings.
- The identity provider remains authoritative for people, groups and authentication lifecycle.
- The semantic layer remains authoritative for metric execution behavior and analytical grain.
- The governance workflow remains authoritative for ownership, permitted use, approval and review states.
- The central index connects the records, preserves provenance and exposes conflicts.

The central control plane should answer where each value came from, when it was collected, who can approve a change and which subscribers received it.

### 4. Separate authority, enforcement and evidence

These three concepts must not be collapsed.

Example: a PII masking decision.

| Element | Responsibility |
|---|---|
| Authority | Privacy standard plus Data Owner approval for the product and use case |
| Enforcement | Fabric, Databricks, Snowflake, BigQuery or another engine applies its native control |
| Evidence | Policy mapping, deployment version, effective-access test, audit event and exception record |

A policy can be enforced differently on each platform while retaining one approved meaning and one exception process.

The platform mapping must document semantic equivalence. A mask that returns `NULL` on one platform and a hashed value on another may not represent the same control. Cross-platform validation needs expected outcomes, not only matching policy names.

### 5. Govern coexistence instead of duplicating it

![Avoid Duplicate Catalogs, Policies and Semantic Logic](images/playbooks/governance-across-multiple-data-platforms-img3-en.png)

Uncontrolled coexistence creates local optimizations and enterprise inconsistency. Governed coexistence uses:

- one authority per concern;
- synchronized metadata with provenance;
- platform-specific enforcement patterns;
- shared source, transformation, product and consumption contracts;
- controlled semantic and metric ownership;
- coordinated incident routing;
- explicit consolidation or exit triggers.

Every coexistence decision should state why multiple platforms are needed. Valid reasons can include workload specialization, cloud boundary, residency, acquired estates, consumer requirements, migration stages or resilience. “Both teams prefer their tool” is not a sufficient governance rationale.

### 6. Define duplicate-control retirement rules

Some duplication is transitional or technically necessary. It must still have an owner and exit condition.

Classify duplicate controls as:

- **authoritative plus synchronized copy** — expected and controlled;
- **platform-specific implementation** — different mechanism for the same approved rule;
- **temporary migration duplicate** — time-limited and monitored;
- **local extension** — allowed only within a documented scope;
- **uncontrolled conflict** — blocker or exception requiring remediation.

Retirement triggers can include:

- migration completion;
- catalog integration becoming available;
- semantic model consolidation;
- platform decommissioning;
- policy engine standardization;
- duplicate incident volume exceeding a threshold;
- cost or operating-capacity threshold;
- review date reached without a valid coexistence rationale.

### 7. Coordinate incidents across the product chain

A source failure may appear as a dbt test failure, a warehouse freshness breach and a broken BI report. Three platform tickets must not become three unrelated incidents.

The cross-platform incident model should define:

- one product incident identifier;
- accountable incident coordinator;
- affected assets and consumers;
- platform-specific technical owners;
- quality and access impact;
- communication owner;
- workaround and exception;
- closure evidence;
- post-incident metadata or policy updates.

Platform queues execute work. Product-level coordination owns the end-to-end outcome.

### 8. Make cost visible across shared processing paths

Multi-platform products often hide cost at handoffs. Ingestion is charged to one project, transformation to another platform, semantic queries to BI capacity and extracts to a consumer team.

The cost model should connect:

- product identifier;
- platform resource identifiers;
- billing project, warehouse, cluster, capacity or reservation;
- processing and storage owner;
- shared-service allocation rule;
- budget and threshold;
- anomaly route;
- consumer or domain attribution;
- consolidation trigger.

Cost accountability is a governance concern because architecture decisions create obligations beyond the first delivery team.

## Checklist

### Authority

- Is one authority defined for every governance concern?
- Are business, technical, identity, policy, semantic and cost authorities distinguished?
- Is precedence defined when systems disagree?
- Does every exception have one owner and expiry?

### Integration and identifiers

- Are stable identifiers used across platforms?
- Is provenance preserved for every synchronized field?
- Are duplicate identifiers and broken lineage edges visible?
- Are integrations monitored for delay, schema change and failed delivery?

### Enforcement and validation

- Is each approved policy mapped to platform-specific controls?
- Are effective results tested with real identities and query paths?
- Are semantically different implementations documented?
- Are privileged bypass and export paths included?

### Evidence and incidents

- Does the evidence chain cover source to consumer?
- Are deployment, quality, access and incident records linked?
- Is one product incident coordinated across platform queues?
- Can reviewers reconstruct the approved state for a specific date and version?

### Coexistence and exit

- Is the reason for every platform in scope documented?
- Are duplicate catalogs, policies and transformations classified?
- Is there a retirement owner and trigger for temporary duplication?
- Is the review date tied to a real consolidation or continuation decision?

## Artifact

Record the decision with the [Governance Starting-Point Decision](/tools/governance-starting-point-decision?product=multiple) tool.

Complete the fillable governance boundary decision card in a workshop. Do not prefill the verdict.

The card records:

1. **Decision Context** — governed outcome, platforms and workloads, asset and consumer scope, coexistence rationale and decision owner.
2. **Authority and Enforcement Boundary** — authoritative metadata source, business owner and steward, identity, classification and policy authorities, enforcement points, lineage identifiers, quality evidence, semantic ownership, incident coordination, retention and cost ownership.
3. **Validation, Conflicts and Exit Conditions** — cross-platform evidence location, effective-control tests, unresolved authority conflicts, duplicate controls to retire, exceptions and consolidation trigger.
4. **Decision Record** — Approved, Conditional or Not Approved; approved boundary map; alternative; blockers; integration actions; retirement plan; no-regret next step; implementation owner; recertification or review date.

The card is the retained evidence that each concern has one authority even when several platforms enforce or consume the decision.

## Tools

- Cross-Platform Governance Boundary Decision Card
- Governance Authority Matrix
- Asset Identifier and Handoff Register
- Metadata Provenance Map
- Platform Policy Equivalence Matrix
- Effective-Control Test Pack
- Duplicate-Control Retirement Backlog
- Cross-Platform Incident Route
- Product Cost Attribution Map
- Consolidation and Exit Trigger Register

## Resources

Use current official documentation for every platform in the approved boundary map. At minimum, validate:

- [Microsoft Fabric governance documentation](https://learn.microsoft.com/fabric/governance/)
- [Microsoft Purview documentation](https://learn.microsoft.com/purview/)
- [Databricks Unity Catalog documentation](https://docs.databricks.com/en/data-governance/unity-catalog/)
- [Snowflake data governance documentation](https://docs.snowflake.com/en/guides-overview-govern)
- [BigQuery data governance documentation](https://docs.cloud.google.com/bigquery/docs/data-governance)
- [Knowledge Catalog documentation](https://docs.cloud.google.com/dataplex/docs)
- [dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)
- [OpenLineage documentation](https://openlineage.io/docs/)

Do not assume feature parity across cloud providers, regions, editions, runtimes or licensing plans. Record every unresolved capability as a validation question in the decision card.

## Playbooks

- [Governance Platform Starting Points](/series/governance-platform-starting-points)
- [Fabric, Databricks, Snowflake or BigQuery — Choose the Governance Starting Point](/stories/choose-governance-platform-starting-point)
- [dbt as a Cross-Platform Governance Control Layer](/stories/dbt-governance-control-layer)
- [Keep Metadata Close to the Source](/stories/keep-metadata-close-to-the-source)
- [Define the Data Product Contract](/playbooks/data-product-contract)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

## Next step

Select one real data product that crosses at least two platforms and complete the Cross-Platform Governance Boundary Decision Card.

Then perform one controlled validation:

1. assign one authority per governance concern;
2. map every platform enforcement point;
3. create stable identifiers for the product, assets, policies and metrics;
4. trace lineage and evidence from source to consumer;
5. test one permitted and one denied access path;
6. compare policy outcomes across platforms;
7. route one simulated quality incident through the product chain;
8. identify duplicate catalogs, policies, logic and incident queues;
9. assign retirement owners and triggers;
10. connect all platform cost to the product owner;
11. record Approved, Conditional or Not Approved;
12. set a recertification date.

The no-regret next step is not selecting one universal governance platform. It is making authority, enforcement and evidence explicit for one cross-platform product and removing the first uncontrolled duplicate.
