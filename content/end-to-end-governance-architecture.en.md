---
title: End-to-End Governance Architecture
description: A practical architecture connecting policies, ownership, metadata, dbt transformation, Snowflake protection and governed Qlik consumption across the complete data lifecycle.
category: Data Governance
tags:
  - data-governance
  - governance-architecture
  - dbt
  - snowflake
  - qlik
  - metadata
  - lineage
  - masking
  - row-level-security
  - data-quality
products:
  - snowflake
  - dbt
  - qlik
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 1
seriesTitle: End-to-End Data Governance
hero: images/playbooks/end-to-end-governance-architecture-hero.png
---

## Governance is an architecture, not a catalog beside the platform

Many governance initiatives begin with a catalog, a classification spreadsheet or a policy document.

Those elements are useful, but none of them creates end-to-end governance on its own.

Governance becomes operational only when a business decision can be traced through the technical platform:

```text
Business policy
→ approved metadata
→ implemented transformation
→ runtime enforcement
→ operational evidence
→ governance review
```

If the chain stops after documentation, the platform may know that a column contains personal data without protecting it.

If the chain begins only at runtime security, the platform may mask a column without preserving the business reason, ownership or review status behind that control.

> **End-to-end governance connects decisions, metadata, code, protection, access and evidence as one controlled system.**

This article defines that system. The following parts implement individual mechanisms in greater depth.

## Begin with a strict responsibility boundary

The first architectural decision is simple but important:

> **Ingestion loads data into the platform. dbt transforms data that is already there.**

A typical flow is:

```text
Source systems
→ ingestion or replication
→ landing tables
→ dbt transformation
→ governed warehouse layers
→ governed consumption
```

The ingestion mechanism may be a managed connector, CDC service, orchestration pipeline, file loader or custom process.

Its responsibilities include:

- connecting to source systems;
- extracting and loading records;
- preserving delivery metadata;
- handling retries and checkpoints;
- detecting failed or delayed loads;
- creating or updating landing objects.

dbt begins after the physical landing objects exist.

Its responsibilities include:

- declaring sources;
- transforming source-aligned data;
- applying tests and contracts;
- recording lineage;
- producing documentation and artifacts;
- materializing governed models;
- exposing metadata for downstream automation.

Merging both concerns into one conceptual box obscures ownership and makes failure analysis harder.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/end-to-end-governance-architecture-img1-en.png"
        alt="End-to-end governance architecture with a separated ingestion flow, governed dbt warehouse layers, Qlik applications and a continuous governance strip for ownership, metadata, quality, protection and evidence"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The data moves through the platform, while governance decisions are implemented at several explicit control points. Ingestion and dbt remain separate responsibilities.
    </figcaption>
</figure>

## The architecture contains three connected planes

A governed platform is easier to reason about when it is divided into three planes.

### 1. The data plane

The data plane contains the physical and logical data products:

```text
Landing
→ RAW
→ Conform
→ Analytics
→ Applications
```

Each layer has a different responsibility.

| Layer | Primary responsibility |
| --- | --- |
| Landing | Preserve the delivered source structure and ingestion metadata |
| RAW | Expose source-aligned data as controlled transformation resources |
| Conform | Standardize, harmonize and integrate shared entities |
| Analytics | Provide curated facts, dimensions, metrics and use-case models |
| Applications | Present governed data to users and operational processes |

The names may differ between platforms. The separation of responsibilities matters more than the labels.

### 2. The metadata plane

The metadata plane describes the data plane.

It contains:

- physical schemas and data types;
- source and model descriptions;
- ownership and domain;
- column classification;
- sensitivity and retention;
- lineage and dependencies;
- data-quality results;
- freshness and operational state;
- generated documentation;
- runtime tags and policy references.

Metadata may be stored across dbt YAML, dbt artifacts, warehouse tags, catalogs, access-control tables and operational systems.

The architecture therefore needs an explicit contract that defines which system is authoritative for each type of metadata.

### 3. The control plane

The control plane turns decisions into enforced behavior.

It contains:

- policies and standards;
- approval workflows;
- CI validation;
- data tests and contracts;
- deployment controls;
- masking policies;
- row access policies;
- roles and entitlements;
- Qlik Section Access;
- audit and incident processes.

A catalog can describe a control. The control plane applies and verifies it.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/end-to-end-governance-architecture-img2-en.png"
        alt="Three aligned architecture lanes for the data plane, metadata plane and control plane with vertical connections between models, metadata and governance enforcement"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Data, metadata and controls follow different paths. Governance works when the paths are connected at defined control points without collapsing them into one process.
    </figcaption>
</figure>

## Governance decisions need a technical contract

A policy such as “customer email is confidential personal data” is too abstract for automation.

The platform needs a machine-readable representation such as:

```yaml
columns:
  - name: email
    config:
      meta:
        pii: true
        pii_category: email
        sensitivity: confidential
        classification_status: approved
        masking_policy: email_mask
        data_owner: customer_operations
        steward: data_governance
```

This metadata does not protect the column by itself.

It creates a technical contract that can be:

- reviewed in Git;
- validated in CI;
- compiled into dbt artifacts;
- shown in documentation;
- consumed by generation and propagation logic;
- mapped to warehouse tags and policies;
- compared with runtime implementation;
- used as evidence during review.

Part 2 defines this contract in detail.

## Classification and enforcement are different responsibilities

A classification answers:

```text
What is this data?
How sensitive is it?
Who owns the decision?
What handling rule has been approved?
```

Enforcement answers:

```text
What may the current identity see?
Which rows are allowed?
Which values must be masked?
Which application may consume the data?
```

The two must be connected, but they must not be confused.

A useful architecture separates at least two enforcement dimensions.

### Column protection

Column protection controls the representation of sensitive values.

Examples include:

- full masking;
- partial masking;
- hashing;
- tokenization;
- nulling;
- role-dependent clear text.

Snowflake Dynamic Data Masking applies masking policies to columns at query time. Tag-based masking can connect object tagging and masking policies so that tagged columns are protected according to the applicable policy and data type.

### Row-level access

Row-level access decides which records are visible.

Examples include:

- region;
- legal entity;
- department;
- customer portfolio;
- tenant;
- contract scope.

Snowflake row access policies can enforce row filtering in the warehouse.

Qlik Section Access can apply dynamic data reduction inside an application by linking user entitlements to reduction fields in the application data model.

These controls may complement each other:

```text
Warehouse policy
→ protects the governed data product centrally

Qlik Section Access
→ restricts application rows for the current user and use case
```

Qlik must not become the only place where sensitive data is protected. A user or service querying the warehouse outside Qlik would bypass an application-only control.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/end-to-end-governance-architecture-img3-en.png"
        alt="Controlled flow from business classification and steward approval through dbt metadata and validation to warehouse masking, row access, Qlik Section Access and audit evidence"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Classification becomes effective only after approved metadata is translated into technical controls. Column masking and row-level access remain separate enforcement dimensions.
    </figcaption>
</figure>

## Governance responsibilities by layer

Governance fails when every team assumes another team owns the final decision.

A practical ownership model distinguishes the following roles.

### Data owner

The data owner approves the business use of the data.

Typical decisions:

- permitted purposes;
- domain ownership;
- sensitivity;
- retention;
- external sharing;
- acceptable business risk.

### Data steward

The steward maintains the classification and ensures that the policy vocabulary is applied consistently.

Typical responsibilities:

- descriptions and glossary alignment;
- PII category;
- classification status;
- review date;
- issue coordination;
- approval evidence.

### Data engineering or platform engineering

Engineering implements the governed data product.

Typical responsibilities:

- source declarations;
- transformations;
- tests and contracts;
- metadata integration;
- deployment;
- lineage;
- technical policy attachment;
- drift detection.

Engineering may detect a probable PII column, but it should not silently approve the classification.

### Security and privacy

Security and privacy define protection standards and approve high-risk controls.

Typical responsibilities:

- masking patterns;
- policy administration;
- role design;
- segregation of duties;
- privacy requirements;
- access reviews;
- incident handling.

### BI or application owner

The application owner implements use-case-specific consumption controls.

Typical responsibilities:

- app access;
- Section Access;
- reduction fields;
- approved extracts;
- visualization behavior;
- user-facing explanations;
- application monitoring.

The architecture must make the handovers visible. A field can have a business owner, a technical owner and an application owner at the same time.

## Quality is part of governance

Governance is not limited to PII and access.

A data product cannot be trusted when its ownership is documented but its values are late, incomplete or structurally invalid.

The architecture should therefore connect governance metadata with:

- source freshness;
- not-null and uniqueness expectations;
- referential integrity;
- accepted values;
- volume anomalies;
- contract checks;
- reconciliation;
- business-rule validation;
- incident severity;
- quality ownership.

Not every test is equally important.

A useful model assigns a quality tier or criticality:

```yaml
config:
  meta:
    quality_tier: critical
    criticality: tier_1
    technical_owner: data_platform
```

CI and orchestration can then apply stricter gates to critical models than to exploratory models.

## Lineage provides impact, not automatic accountability

Lineage answers questions such as:

- Which source feeds this metric?
- Which models depend on this column?
- Which applications may be affected by a change?
- Where must classification be propagated?
- Which controls should exist downstream?

Lineage does not decide whether a downstream transformation preserves or changes the classification.

For example:

```text
email
→ lower(email)
```

usually remains personal data.

But:

```text
email
→ count(distinct email)
```

may produce a non-personal aggregate, depending on grain, filters and disclosure risk.

Part 4 addresses this propagation and resolution problem.

## Evidence closes the governance loop

A policy implementation must produce evidence.

Useful evidence includes:

- approved pull requests;
- metadata validation results;
- dbt test results;
- manifest and catalog artifacts;
- lineage snapshots;
- policy references;
- access reviews;
- query and access logs;
- schema-drift events;
- incidents and exceptions;
- review dates.

Evidence should answer three questions:

```text
What was approved?
What was implemented?
What happened at runtime?
```

If these answers cannot be connected, the organization has documentation but not auditable governance.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/end-to-end-governance-architecture-img4-en.png"
        alt="Closed governance operating loop from policy definition through implementation, validation, enforcement and observation back to improvement"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Governance is operated as a control loop. Runtime evidence and incidents must improve policies, metadata, models and controls.
    </figcaption>
</figure>

## A minimum viable governance architecture

A first implementation does not need every enterprise platform feature.

It needs a complete control path.

A minimum viable architecture can contain:

1. **A defined governance vocabulary**
   - owner;
   - domain;
   - PII;
   - sensitivity;
   - classification status;
   - retention class;
   - protection policy.

2. **Versioned metadata**
   - dbt YAML;
   - code review;
   - explicit approvers.

3. **Automated validation**
   - required fields;
   - allowed values;
   - PII policy checks;
   - unreviewed-column checks.

4. **Governed transformations**
   - source declarations;
   - tests;
   - lineage;
   - layer responsibilities.

5. **Runtime enforcement**
   - warehouse roles;
   - masking;
   - row-level controls;
   - application access.

6. **Operational evidence**
   - build results;
   - policy references;
   - access review;
   - incident process.

This is stronger than a large catalog with no connection to delivery or enforcement.

## Common architecture failures

### Governance metadata exists only in a catalog

The code and runtime platform cannot reliably consume it.

### Metadata exists only in dbt

Business users and stewards cannot review or govern it effectively.

### PII is detected automatically and treated as approved

Detection is evidence for review, not a governance decision.

### Access is implemented only in Qlik

Warehouse access outside the application remains insufficiently protected.

### Every rule is embedded directly in SQL

Policy maintenance becomes duplicated and difficult to audit.

### Metadata is copied across layers without transformation awareness

Derived columns inherit classifications that may be wrong, or sensitive lineage is lost.

### Ingestion, transformation and governance are treated as one tool responsibility

Failures, ownership and control boundaries become unclear.

## The central insight

> **A governed data platform is not a sequence of tools. It is a connected set of decisions, metadata contracts, transformations, runtime controls and evidence.**

Part 2, [Metadata-driven Governance with dbt meta](/playbooks/metadata-driven-governance-with-dbt-meta), turns the architectural contract into a concrete, versioned metadata model inside dbt.

## Sources and further documentation

- [dbt — meta configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [dbt — manifest JSON](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [dbt — about dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)
- [Snowflake — Dynamic Data Masking](https://docs.snowflake.com/en/user-guide/security-column-ddm-intro)
- [Snowflake — Tag-based masking policies](https://docs.snowflake.com/en/user-guide/tag-based-masking-policies)
- [Snowflake — Row access policies](https://docs.snowflake.com/en/user-guide/security-row-intro)
- [Qlik Sense — Managing data security with Section Access](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Scripting/Security/manage-security-with-section-access.htm)
