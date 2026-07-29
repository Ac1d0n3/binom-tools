---
title: "Master Items and Metric Governance in Qlik"
description: "Use Qlik Master Items as controlled app assets without confusing reuse with enterprise metric governance."
author: Thomas Lindackers
tags:
  - Qlik
  - Master Items
  - Metric Governance
  - Trusted Metrics
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/qlik-master-items-metric-governance-hero.png
series: bi-governance-decisions
seriesTitle: BI Governance Decisions
seriesPart: 4
---

Qlik Master Items reduce duplication and make approved dimensions, measures and visualizations easier to reuse inside an application. That is valuable implementation control. It is not, by itself, an enterprise metric-governance system.

## Problem

Metric logic in Qlik can be distributed across the upstream data product, load script, associative data model, variables, Master Measures, Master Dimensions and chart-local expressions. Copies of an application can then create additional variants. Without a declared boundary, teams may treat an app-level Master Measure as an enterprise-approved metric even though the business definition, source authority, owner, tests and cross-app lifecycle are not controlled.

Within an app, updates to a linked Master Item can propagate to the visualizations that use it. That does not mean a Master Item automatically updates independent copies or other applications. Cross-app consistency needs a controlled deployment mechanism, template, source-controlled export or other release process.

Qlik Cloud also supports descriptions, tags and links between Master Items and business-glossary terms. These features improve meaning and discovery, but they do not replace the approved metric contract.

## Decision

Use a Qlik Master Measure when a calculation is reusable within a governed application and its semantic contract has already been approved.

The contract must identify the metric ID and version, business definition, base grain, aggregation, source authority, selection and Set Analysis behavior, time rules, units, owner, tests and permitted use. The Master Measure is then the app-specific implementation of that contract.

Keep responsibilities separated:

- Upstream data products own source integration, historical assignment and durable business rules.
- The load script and data model own field preparation, associations and technical helper logic.
- Master Items own approved reusable app-level measures, dimensions and visualization patterns.
- Variables may hold controlled parameters or explicit expression fragments, but must not hide unowned business definitions.
- Chart-local expressions are suitable for visual behavior, temporary analysis or approved local derivatives.

Promote a chart expression to a Master Measure when reuse, criticality or copying makes app-level control necessary. Promote an app-local Master Measure to a governed cross-app implementation only after defining deployment, dependency testing, release versioning and consumer migration.

## Checklist

- Is there an approved metric contract with stable ID and version?
- Is the business meaning separated from the Qlik expression?
- Is source authority and base grain controlled upstream?
- Are selection state, Set Analysis, alternate states and time behavior explicit?
- Is the Master Item linked to a description, tags or glossary term where appropriate?
- Are app ID, object ID, release version and deployment path recorded?
- Are all chart, variable and child-measure dependencies inventoried?
- Are copied applications and diverging variants known?
- Are permitted local derivatives and overrides defined?
- Are regression, scenario and reconciliation tests available?
- Are owner, steward and implementation custodian named?
- Is cross-app reuse supported by a controlled template or deployment mechanism?
- Is replacement and deprecation impact understood before deletion?

Do not promote every expression. Local calculations with a narrow presentation purpose can remain local when they are labelled, owned and excluded from enterprise-truth claims.

## Artifact

Create a **Qlik Metric Governance Record** for every governed Qlik implementation.

Mandatory fields:

| Field | Content |
|---|---|
| Metric identity | Name, stable ID, aliases and approved version |
| Definition | Business question, formula components, inclusions and exclusions |
| Grain and authority | Base grain, authoritative source and upstream data product |
| Qlik reference | Tenant or environment, space, app ID, Master Item object ID and expression reference |
| Selection semantics | Set Analysis, selection behavior, alternate states and time context |
| Metadata | Description, tags, glossary link and formatting |
| Dependencies | Fields, base measures, variables, child measures, charts and app copies |
| Accountability | Data Owner, Steward, technical custodian and release owner |
| Validation | Regression tests, scenario tests, reconciliation and tolerance |
| Publication | Source-controlled artifact, deployment method, app template and release notes |
| Lifecycle | Certification status, local override policy, review date, triggers and replacement |

The record produces an approved app implementation, a cross-app reuse decision, migration actions for chart-local copies, a dependency and release plan and a named lifecycle owner.

A generator may produce an expression from an approved contract. It cannot select the authoritative definition, determine enterprise scope or certify its own output.

## Tools

Use KPI Definition for the business contract. Use Report Inventory and the BI Python Toolkit to discover repeated expressions, variables, copies and consumer dependencies. Use the Qlik Set Analysis Generator after grain, source, filters and selection semantics are approved.

Store generated expressions, dependency lists, test scenarios and warnings together. A code-only output is insufficient because a syntactically valid expression can still implement the wrong meaning.

## Resources

- Qlik Cloud Help: Working with Master Items — https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Assets/work-with-master-items.htm
- Qlik Cloud Help: Tagging Master Items — https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Assets/tag-master-items.htm
- Qlik Cloud Help: Linking terms to Master Items — https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Catalog/BusinessGlossary/business-glossaries-master-items.htm
- Qlik Cloud Help: Creating a Master Measure — https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Measures/create-master-measure-field.htm

Verify Qlik Cloud and client-managed Qlik Sense behavior separately during implementation, especially permissions, glossary functions and deployment options.

## Playbooks

- [Define KPI](/playbooks/define-kpi)
- [Keeping Business Logic Outside BI Apps](/playbooks/keeping-business-logic-outside-bi-apps)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)

These playbooks define the upstream and semantic boundaries. This story determines how the approved contract is implemented and governed in Qlik.

## Next step

Choose one critical Qlik application. Inventory chart expressions, variables and Master Measures for one metric family. Approve one contract, convert the chosen implementation into a governed Master Measure, record dependencies, validate the result and define how the same implementation will be deployed to other controlled apps.
