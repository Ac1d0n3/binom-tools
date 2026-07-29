---
title: "dbt as a Cross-Platform Governance Control Layer"
description: "Use dbt to implement transformation contracts, metadata, tests, lineage and deployment evidence across platforms without turning the transformation repository into the authority for business ownership, access, permitted use or retention."
author: Thomas Lindackers
tags:
  - data-governance
  - dbt
  - transformation-governance
  - data-contracts
  - data-quality
  - lineage
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/dbt-governance-control-layer-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance Platform Starting Points"
seriesPart: 6
---

## Problem

dbt can create a consistent transformation workflow across warehouses and lakehouse SQL engines. Sources, models, contracts, tests, documentation, lineage, pull requests, deployments and artifacts can be expressed through one version-controlled project structure. This makes dbt a credible cross-platform control layer for transformation governance.

It does not make dbt the authority for every governance decision.

The transformation repository can state that a model has an owner, a PII classification, an allowed-usage value or a quality tier. It cannot prove that the accountable business role approved those values unless an external workflow, identity and decision record are connected to the declaration.

The relevant question is therefore not:

> Can we put governance metadata into YAML?

It is:

> Can dbt implement and evidence approved transformation controls while preserving clear authority boundaries for business purpose, ownership, access, privacy, retention and metric certification?

Four failure patterns are common.

### YAML becomes an unreviewed shadow catalog

Developers add descriptions, owners and classifications because the fields are available. The repository then appears complete, but values conflict with the business glossary, privacy inventory or platform catalog. Metadata without authority, provenance and review state creates another catalog rather than a governance control layer.

### Test success becomes the definition of quality

dbt data tests can assert model and source conditions, and failed records can be stored. Passing tests do not prove that the selected rules represent the business quality expectation, that thresholds were approved or that an incident owner will respond.

### Pull-request approval becomes business approval

A code reviewer can verify SQL, naming, performance and deployment risk. That review does not automatically approve a new business purpose, sensitive-data use, retention change or certified metric definition.

### Transformation lineage becomes end-to-end lineage

dbt artifacts represent the project graph and related resources. Ingestion outside dbt, platform policies, extracts, semantic models, reports, APIs and AI consumers can remain outside the graph. The handoff to external catalogs and evidence systems must be designed explicitly.

![What dbt Can Control — and What It Cannot Decide](images/playbooks/dbt-governance-control-layer-img1-en.png)

The boundary should remain visible:

| dbt can implement or evidence | dbt cannot own |
|---|---|
| Source and model contracts | Business purpose |
| Data tests and stored failures | Accountable Data Ownership |
| Transformation lineage | Permitted use |
| Metadata fields and documentation | Final PII approval |
| Version control and pull requests | Platform access enforcement |
| Build and deployment artifacts | Retention and deletion decisions |
| Source freshness checks | Exception approval |
| Semantic definitions where adopted | Business metric certification |

## Decision

Use dbt as a cross-platform governance control layer when SQL transformation is material across one or more analytical platforms and the organization wants one repeatable contract, metadata, test, review and evidence pattern without centralizing business authority in the transformation tool.

A positive decision normally requires:

- a meaningful volume of shared SQL transformation logic;
- supported adapters and deployment environments for the target platforms;
- a version-controlled development and release process;
- an authoritative metadata schema agreed outside individual projects;
- named Data Owners and Stewards who can approve promoted metadata;
- a clear catalog and lineage publication path;
- stored quality failures and an incident workflow;
- separation between code review and business approval;
- explicit handoffs for access, permitted use, retention and semantic certification;
- a migration plan for duplicated transformations in BI tools, stored procedures or local scripts.

Do not introduce dbt as a mandatory layer for every workload. Native SQL, stored procedures, notebooks, streaming engines, low-code tools and platform-specific pipelines remain valid when they better fit the workload and are governed through equivalent contracts and evidence.

### 1. Define the Transformation Governance Contract

The control layer needs one explicit contract that all participating projects implement.

At minimum, define:

- supported platforms and adapters;
- governed source and model scope;
- naming and stable identifiers;
- source declaration requirements;
- model contract requirements;
- mandatory model and column metadata;
- ownership and stewardship references;
- classification and permitted-use references;
- data-test categories and thresholds;
- stored-failure rules;
- review and approval states;
- deployment environments and version evidence;
- catalog, lineage and semantic handoffs;
- incident and exception workflow;
- deprecation and recertification triggers.

The contract should distinguish authoritative values from local implementation values. A model description can be authoritative for derived transformation logic. It should not silently overwrite the enterprise business term or privacy decision.

### 2. Use contracts for shape, not for every governance promise

A dbt model contract defines the shape of the returned dataset and can enforce column names and data types when supported. That is valuable implementation control, but it is narrower than a complete data-product contract.

The wider contract still needs:

- business purpose and consumer scope;
- approved grain and semantics;
- source authority;
- permitted use;
- classification and policy linkage;
- freshness and service expectation;
- quality thresholds;
- retention and deletion rule;
- incident owner;
- publication and deprecation status.

Model contracts should therefore be treated as one executable component of the wider Transformation Governance Contract.

### 3. Connect the full evidence lifecycle

The lifecycle should be explicit:

```text
approved requirement
→ source declaration
→ model contract
→ transformation
→ data tests
→ stored failure evidence
→ technical review
→ accountable approval
→ deployment
→ catalog and consumer publication
→ change and recertification
```

![Transformation Contract and Evidence Flow](images/playbooks/dbt-governance-control-layer-img2-en.png)

Mandatory metadata should include at least:

- owner and steward reference;
- domain or product;
- grain;
- PII or sensitivity classification;
- criticality;
- quality tier;
- allowed usage reference;
- policy references;
- lifecycle state;
- review date.

The repository must also preserve build evidence. dbt artifacts such as `manifest.json`, `run_results.json`, `catalog.json`, `sources.json` and the semantic manifest provide different evidence types. Store the artifact version, dbt version, environment, commit, invocation and deployment identifier required to reproduce a production state.

### 4. Store failures as operational evidence

A dashboard that shows a red test is not enough. For material controls, failed records or an approved aggregate evidence set should be retained with context.

The operating pattern should define:

- which tests block publication;
- which tests warn but allow publication;
- failure thresholds;
- failure-storage location and retention;
- sensitive-data handling in failure tables;
- incident routing;
- product-health status;
- consumer communication;
- exception owner and expiry;
- re-test and closure evidence.

`store_failures` can persist failing records for tests, but it replaces prior failures for the same test by default. Long-term incident evidence may therefore require an additional append or snapshot process. Failure storage is an implementation mechanism, not the full incident record.

### 5. Promote metadata through declared, validated and approved states

Metadata should move through three visible states.

![Promote Metadata from YAML to Governance Workflow](images/playbooks/dbt-governance-control-layer-img3-en.png)

#### Declared

The developer provides model descriptions, column descriptions, tests and implementation metadata. Values are useful but not yet automatically authoritative.

#### Validated

Automated checks verify required fields, controlled vocabularies, references, contract completeness and consistency. Catalog synchronization and Steward review compare the declaration with authoritative sources.

#### Approved

The accountable role approves the business-relevant value, policy linkage, effective date, version and recertification trigger. The approved state is published back to subscribers or linked through a stable identifier.

A practical metadata record should include:

```yaml
models:
  - name: fct_sales_order_line
    description: Governed sales-order-line transformation model.
    config:
      contract:
        enforced: true
      meta:
        product_id: sales-order-lines
        data_owner_ref: owner:sales-operations
        steward_ref: steward:sales-data
        grain: one-row-per-order-line
        pii_classification_ref: classification:customer-indirect
        allowed_usage_ref: policy:commercial-analytics
        quality_tier: tier-1
        review_status: approved
        policy_version: "2026-07"
        review_date: "2026-10-29"
```

The references should point to authoritative records. Avoid copying long policy text into every project.

### 6. Separate technical review from accountable approval

The pull-request workflow should expose distinct approval gates.

| Gate | Primary focus |
|---|---|
| Engineering review | SQL correctness, naming, performance, maintainability and tests |
| Data Steward review | Definitions, metadata completeness, classification and consistency |
| Data Owner approval | Purpose, permitted use, criticality, quality expectation and exception acceptance |
| Security or privacy approval | Sensitive-data controls, policy conditions and exceptions |
| Platform approval | Deployment, identity, runtime, permissions and operational readiness |
| Semantic approval | Metric definition, aggregation behavior, certification and consumer impact |

Not every change needs every gate. The contract should define thresholds. A comment correction may need technical review only; a grain change or new PII use requires accountable recertification.

### 7. Design catalog, access and semantic handoffs

The dbt project should publish or link the evidence it is best placed to maintain:

- model and column identifiers;
- transformation logic and dependencies;
- tests and results;
- source freshness;
- deployment version;
- descriptions of derived logic;
- exposures, groups and semantic definitions where adopted.

It should hand off decisions it does not enforce:

- identity and platform privileges;
- row and column policies;
- retention and deletion controls;
- legal or privacy approval;
- enterprise glossary authority;
- certified metric approval;
- downstream export and consumption controls.

The target is synchronized authority, not a single tool pretending to own every field.

## Checklist

### Scope and architecture

- Are supported platforms, adapters and environments explicit?
- Is dbt solving shared transformation-governance problems rather than being added by default?
- Is duplicated transformation logic inventoried and prioritized?
- Are non-dbt workloads covered by equivalent controls?

### Metadata and authority

- Is the authoritative metadata schema defined?
- Does each field have a source authority, approver and review state?
- Are YAML values synchronized with the business glossary and platform catalogs?
- Are stable identifiers used instead of name-only matching?

### Contracts, tests and evidence

- Are model contracts applied where they add enforceable value?
- Are data tests connected to approved quality expectations?
- Are failure records stored safely and routed to an owner?
- Are artifacts retained with commit, environment and deployment context?

### Review and approval

- Are code review and business approval separate?
- Are change thresholds and recertification triggers defined?
- Are exceptions time-limited and linked to evidence?
- Can a release be blocked when mandatory governance evidence is missing?

### Handoffs and lifecycle

- Is catalog and lineage publication automated or operationally owned?
- Are access, policy, retention and semantic decisions handed to the correct authority?
- Can consumers see model status, quality and deprecation state?
- Is the control layer reviewed when platforms, adapters or operating responsibilities change?

## Artifact

Record the decision with the [Governance Starting-Point Decision](/tools/governance-starting-point-decision?product=dbt) tool.

Complete the fillable dbt decision card in a workshop. Do not prefill an Approved, Conditional or Not Approved verdict.

The card records:

1. **Decision Context** — transformation-governance problem, business outcome, supported platforms, source/model scope and decision owner.
2. **Control-Layer Design** — metadata schema, contracts, test strategy, failure evidence, pull-request approvals, deployment evidence, catalog/lineage handoff, access/policy handoff, semantic handoff and migration of duplicated logic.
3. **Validation, Gaps and Exceptions** — evidence location, proof-of-value tests, unresolved gaps, dependencies, assumptions and time-limited exceptions.
4. **Decision Record** — status, approved pattern, Transformation Governance Contract location, alternative, blockers, no-regret next step, implementation owner and review date.

The artifact should show that dbt implements and evidences controls without becoming the authority for ownership, permitted use, access approval or retention.

## Tools

- dbt Control-Layer Decision Card
- Transformation Governance Contract
- Metadata Authority Matrix
- Required Metadata Validator
- Data-Test and Threshold Register
- Stored-Failure and Incident Pattern
- Pull-Request Approval Matrix
- Artifact Retention Register
- Catalog and Lineage Handoff Map
- Transformation Duplication Retirement Backlog

## Resources

### Contracts, tests and failures

- [Model contracts](https://docs.getdbt.com/docs/mesh/govern/model-contracts)
- [Add data tests to your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [`store_failures`](https://docs.getdbt.com/reference/resource-configs/store_failures)
- [Sources and source freshness](https://docs.getdbt.com/docs/build/sources)

### Metadata and artifacts

- [`meta` configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [About dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)
- [Manifest JSON file](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [Run results JSON file](https://docs.getdbt.com/reference/artifacts/run-results-json)
- [Catalog JSON file](https://docs.getdbt.com/reference/artifacts/catalog-json)
- [Sources JSON file](https://docs.getdbt.com/reference/artifacts/sources-json)

### Semantic handoff

- [dbt Semantic Layer](https://docs.getdbt.com/docs/use-dbt-semantic-layer/dbt-sl)
- [Semantic manifest](https://docs.getdbt.com/reference/artifacts/sl-manifest)

## Playbooks

- [Governance Platform Starting Points](/series/governance-platform-starting-points)
- [Keep Metadata Close to the Source](/stories/keep-metadata-close-to-the-source)
- [Define the Data Product Contract](/playbooks/data-product-contract)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

## Next step

Select one transformation path that currently spans duplicated SQL, BI logic or multiple platforms and complete the dbt Control-Layer Decision Card.

Then run one proof of value:

1. define the authoritative metadata schema;
2. declare one source and one governed model;
3. enforce the model shape where appropriate;
4. attach owner, grain, classification, permitted-use and quality references;
5. execute tests and store failures;
6. route a failure to the incident owner;
7. separate code review from accountable approval;
8. retain artifacts with commit and deployment identity;
9. publish lineage and metadata to the chosen catalog;
10. verify access, retention and semantic handoffs;
11. migrate one duplicated transformation;
12. record Approved, Conditional or Not Approved and set the review date.

The no-regret next step is not an enterprise-wide dbt mandate. It is proving that one approved transformation contract can be implemented consistently, observed operationally and connected to the authorities outside the repository.
