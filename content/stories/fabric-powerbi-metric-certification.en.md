---
title: Metric Certification in Fabric and Power BI
description: Define what certified and endorsed should mean for a metric, which evidence and decision rights are required, and how trusted metrics are reviewed, published, recertified and retired in Fabric and Power BI.
author: Thomas Lindackers
tags:
  - metric-governance
  - metric-certification
  - trusted-metrics
  - kpi-governance
  - power-bi
  - microsoft-fabric
  - semantic-model
  - data-quality
  - data-lineage
products:
  - qlik
  - fabric
  - powerbi
publishedAt: 2026-07-28
category: Data Governance
order: -1
hero: images/playbooks/fabric-powerbi-metric-certification-hero.png
series: bi-governance-decisions
seriesTitle: BI Governance Decisions
seriesPart: 2
---

A certification badge can improve discovery. It cannot, by itself, prove that a metric has an approved meaning, correct grain, accountable owner, controlled implementation or current evidence.

Metric certification is therefore an **accountable evidence decision**. The organization decides whether a metric is trusted for a defined scope. Fabric or Power BI then exposes an appropriate platform endorsement on the governed item that contains the implementation.

## Problem

Organizations often use the word `certified` for several different things:

- a Power BI or Fabric endorsement badge;
- approval of a semantic model;
- approval of an individual business metric;
- completion of a governance workflow;
- a professional qualification such as CDMP;
- a privacy, security or compliance certification.

These meanings are not interchangeable.

A platform badge is useful metadata. It helps users find content that the organization considers valuable or authoritative. It does not define the evidence that the organization reviewed, the metric scope that was approved or the conditions under which the trust claim remains valid.

### The platform label is not the governance definition

As of July 2026, Microsoft Fabric supports the endorsement labels **Promoted**, **Certified** and **Master data** for eligible Fabric items. Power BI content endorsement focuses on **Promoted** and **Certified**. Certification is controlled through tenant settings and designated certifiers, while promotion can be applied more broadly by item owners or users with write permission.

These are item-level capabilities. A Power BI measure is an object inside a semantic model; it does not receive an independent built-in endorsement badge. A certified semantic model can therefore contain:

- approved shared measures;
- technical helper measures;
- measures that are valid only for a restricted context;
- deprecated measures awaiting migration;
- ungoverned local calculations introduced downstream.

The organization must maintain the metric-level certification decision separately and link it to the production implementation.

### Five common anti-patterns

**Badge without an approved definition.** The semantic model is certified because it is widely used, but the KPI still lacks a formally agreed population, exclusions, time logic and base grain.

**Owner field without decision authority.** A name is recorded in the catalog, but that person cannot approve meaning, accept risk, prioritize remediation or retire the metric.

**Certification copied across environments.** A development or test artifact is treated as trusted because a related production item is certified, or a trust status is assumed to move automatically with deployment. Environment-specific data sources, rules, refresh, access and evidence are not revalidated.

**No recertification after change.** A source, formula, relationship, grain, filter rule, access policy or owner changes, while the badge and trusted-metric entry remain untouched.

**Certified semantic model with ungoverned local metrics.** Users create report-local, visual or composite-model calculations that reuse the trusted label but redefine the approved business meaning.

The failure is not the existence of an endorsement feature. The failure is using the badge as a substitute for a governed decision.

### Certification must answer a precise question

A useful certification statement is bounded:

> `Net Revenue` version 3.2 is approved for monthly management reporting in EUR at sales-order-line grain, using the documented eligibility, cancellation, return and currency rules, implemented in the production Sales semantic model, reconciled to the finance reference result within the approved tolerance, and subject to quarterly review or immediate review after a defined change trigger.

That statement is testable. “Certified KPI” without scope, version, implementation and review condition is not.

## Decision

Define metric certification as a lifecycle decision with explicit states, evidence, decision rights and review triggers.

The internal governance decision should occur **before** the platform label is applied.

![Certification is an evidence decision, not a badge](images/playbooks/fabric-powerbi-metric-certification-img1-en.png)

A practical decision flow is:

```text
Metric Candidate
→ Definition and Grain Review
→ Owner Approval
→ Lineage and Quality Evidence
→ Reconciliation and Consumer Validation
→ Access and Usage Review
→ Certification Decision
→ Published Trusted Metric
```

The possible outcomes are not limited to certified or rejected:

- **Certified** — the metric meets the required evidence and control standard for the approved scope.
- **Promoted** — the metric or containing item is useful and discoverable, but the full certification evidence is not yet complete.
- **Returned for remediation** — the candidate is valid, but one or more evidence gaps must be closed.
- **Rejected** — the proposed meaning, implementation or control design is not acceptable.
- **Deprecated or retired** — the metric is no longer approved for new use and consumers must migrate.

### Define trust states independently of platform mechanics

Use four organization-level states.

| State | Governance meaning | Permitted trust claim |
| --- | --- | --- |
| **Working** | Local development or analysis. Definition, implementation or evidence may still change. | No enterprise trust claim. |
| **Promoted** | Useful, named and discoverable. An owner is identified, but evidence may be incomplete or scope-limited. | Recommended for evaluation or controlled reuse. |
| **Certified** | Definition, grain, implementation, evidence, access and lifecycle controls are approved for a named scope. | Trusted for the approved decisions and consumers. |
| **Deprecated or Retired** | New use is stopped. A replacement, migration path and retirement status are recorded. | Historical reference only, or no permitted use. |

![Define trust states and decision rights](images/playbooks/fabric-powerbi-metric-certification-img2-en.png)

Map these internal states to Microsoft platform labels only where the mapping is appropriate.

| Organization state | Possible Fabric or Power BI implementation | Important boundary |
| --- | --- | --- |
| **Working** | No endorsement | Development content must not inherit a production trust claim. |
| **Promoted** | Promoted item | Promotion improves visibility but does not prove complete metric evidence. |
| **Certified** | Certified production semantic model or other eligible item | The item badge does not certify every internal measure or downstream local calculation. |
| **Deprecated** | Endorsement removed or changed; replacement linked | Consumers need an explicit migration signal because the platform badge alone does not manage the business lifecycle. |
| **Retired** | Item removed, archived or access restricted according to policy | Evidence and decision history should remain available for audit and historical interpretation. |

`Master data` is a separate Fabric endorsement for eligible data-containing items. It should not be used as a synonym for a certified metric.

### Separate metric certification from semantic-model certification

A semantic model and a metric are related governance objects, but they are not identical.

A **metric certification** approves:

- the business question and intended decision;
- definition, inclusions and exclusions;
- base grain and aggregation behavior;
- time, filter and dimensional semantics;
- accountable owner and permitted consumers;
- reference result and reconciliation tolerance;
- version, effective date and review triggers.

A **semantic-model certification** approves the reusable item that implements one or more metrics and dimensions:

- model structure and relationships;
- approved measures and calculation behavior;
- refresh and operational controls;
- security and Build access;
- model-level lineage, ownership and support;
- controlled release and change process;
- suitability for governed reuse.

The relationship should be explicit:

```text
Certified Metric Record
→ approved implementation reference
→ production semantic model
→ technical measure object
→ permitted reports and consumers
```

Certification of the model is necessary when the model is the governed delivery channel. It is not sufficient to certify every metric inside it.

### Assign decision rights

Certification should never be a self-approval by the implementation team alone.

| Role | Decision responsibility |
| --- | --- |
| **Data Owner** | Approves business meaning, risk acceptance, permitted use and retirement. |
| **Metric or Data Product Owner** | Maintains scope, version, consumer impact, review dates and lifecycle status. |
| **Data Steward** | Validates terminology, evidence completeness, duplicates, lineage references and policy alignment. |
| **BI or Platform Team** | Implements the approved state, technical measure, model metadata, access configuration and deployment controls. |
| **Quality or Control Reviewer** | Reviews tests, thresholds, incidents and reconciliation evidence where independence is required. |
| **Consumer Representatives** | Validate usability, expected interpretation and decision fitness. |
| **Authorized Platform Certifier** | Applies or removes the Fabric or Power BI certification after the governance decision. |

One person may hold more than one role in a smaller organization, but the responsibilities must remain distinguishable. The person who writes the DAX expression should not silently define the business meaning.

### Require environment-specific approval

Development, test and production have different trust implications.

A certification record should identify the exact production item and implementation version. Deployment to another workspace or stage does not prove that:

- the target uses the same governed source;
- gateway, credentials and refresh are valid;
- access and RLS are equivalent;
- deployment rules point to the intended environment;
- reconciliation results remain inside tolerance;
- the endorsed item is the approved production object.

Treat production certification as a release gate. Do not copy the trust claim merely because content was copied.

### Make certification discoverable without confusing discovery and access

Endorsement supports discovery. In Power BI, an endorsed semantic model can be made discoverable so users without current access can find it and request access. Discoverability does not grant Build permission or bypass security.

The trusted-metric entry should therefore expose:

- metric name, purpose and definition;
- status and approved scope;
- accountable owner and support contact;
- production semantic model and measure;
- freshness and last successful evidence review;
- permitted consumers and access request path;
- replacement when deprecated;
- links to lineage, tests and reconciliation results.

Discovery tells users that an approved asset exists. Access controls still determine whether they may use it.

### Certification is a lifecycle

A permanent badge creates stale trust. Certification must return to review when evidence or context changes.

![Certification is a lifecycle](images/playbooks/fabric-powerbi-metric-certification-img4-en.png)

Use a closed loop:

```text
Propose
→ Review Evidence
→ Remediate Gaps
→ Approve and Publish
→ Monitor Use and Quality
→ Detect Change
→ Reassess
→ Recertify or Deprecate
```

Immediate reassessment triggers include:

- source-system or source-table change;
- formula, grain, relationship or aggregation change;
- new consumer context or materially different decision;
- quality threshold breach or unresolved incident;
- refresh or freshness failure;
- access, PII or permitted-use policy change;
- owner or steward change;
- production migration or major platform change;
- planned review date.

A calendar review is still required because not every material change is detected automatically. A risk-based cadence can use quarterly reviews for critical executive or regulated metrics, semiannual reviews for broadly reused operational metrics and annual reviews for stable lower-risk metrics. Change triggers always override the calendar.

## Checklist

Use this checklist before approving a metric as certified.

![Evidence required for a certified metric](images/playbooks/fabric-powerbi-metric-certification-img3-en.png)

### Definition

- [ ] The business question and supported decision are explicit.
- [ ] Formula, inclusions, exclusions and exception rules are approved.
- [ ] The metric has a stable identifier and version.
- [ ] Similar labels and competing definitions have been reviewed.

### Grain and semantics

- [ ] Base grain and valid aggregation are documented.
- [ ] Time basis, effective date and period behavior are explicit.
- [ ] Filter behavior and supported dimensions are defined.
- [ ] Currency, unit, sign and null behavior are controlled.
- [ ] Permitted local derivatives are distinguished from redefinitions.

### Ownership and decision rights

- [ ] An accountable Data Owner can approve meaning and accept risk.
- [ ] A Metric or Product Owner maintains the lifecycle.
- [ ] A Steward validates terminology and evidence.
- [ ] An implementation custodian supports the technical object.
- [ ] Authorized platform certifiers are defined through tenant governance.

### Lineage and implementation

- [ ] Governed source systems and tables are linked.
- [ ] Transformations and business-rule locations are traceable.
- [ ] The exact production semantic model and measure are identified.
- [ ] Downstream reports, composite models and material consumers are known.
- [ ] Development and test objects cannot be mistaken for production.

### Quality and freshness

- [ ] Quality rules, thresholds and severity levels are documented.
- [ ] Test results are current and reproducible.
- [ ] Refresh and freshness expectations are explicit.
- [ ] Incident ownership, escalation and remediation are defined.
- [ ] Known limitations and accepted exceptions have expiry dates.

### Reconciliation and consumer validation

- [ ] A reference result or authoritative comparison is named.
- [ ] Reconciliation grain, period and tolerance are documented.
- [ ] Variances are explained and approved.
- [ ] Consumer representatives have validated interpretation and usability.
- [ ] Evidence is linked rather than preserved only as screenshots.

### Protection and permitted use

- [ ] Access requirements and Build permission are reviewed.
- [ ] RLS, OLS or other relevant controls are tested where used.
- [ ] PII, sensitivity and permitted-use classifications are documented.
- [ ] Discovery does not expose restricted details.
- [ ] Export, sharing and downstream reuse conditions are explicit.

### Lifecycle

- [ ] Effective date and review date are set.
- [ ] Change triggers are registered.
- [ ] Recertification owner and evidence expectations are clear.
- [ ] Deprecation status, replacement and migration plan are defined.
- [ ] The trust marker is removed or changed when approval expires.

### Platform mapping

- [ ] The internal trust state is decided before the platform endorsement.
- [ ] The selected item type is eligible for the intended endorsement.
- [ ] Tenant settings and certifier permissions are verified.
- [ ] The endorsement is applied to the exact approved production item.
- [ ] Metric-level evidence remains accessible independently of the item badge.

## Artifact

The primary output is a **Metric Certification Record**. It is the evidence-backed decision artifact behind the catalog entry and platform endorsement.

A practical structure can look like this:

```yaml
metric_certification:
  metric_id: net-revenue
  metric_name: Net Revenue
  version: 3.2
  governance_state: certified
  effective_date: 2026-07-28

  business_scope:
    question: How much eligible revenue remains after approved cancellations and returns?
    decisions:
      - monthly-management-reporting
      - regional-sales-review
    approved_consumers:
      - executive-sales-report
      - regional-sales-analysis
    prohibited_uses:
      - statutory-revenue-reporting-without-finance-adjustments

  definition:
    approved_definition: /governance/metrics/net-revenue/3.2
    formula_components:
      - eligible-sales-lines
      - approved-cancellations
      - approved-returns
      - reporting-currency-conversion
    base_grain: sales-order-line
    aggregation: additive-by-approved-reporting-dimensions
    time_basis: posting-date
    exclusions:
      - test-orders
      - unapproved-manual-adjustments

  ownership:
    data_owner: sales-finance-owner
    metric_owner: sales-data-product-owner
    steward: commercial-data-steward
    implementation_custodian: bi-platform-team
    platform_certifier: authorized-certifier-group

  implementation:
    environment: production
    workspace: governed-sales-bi
    semantic_model: sales-performance
    semantic_model_item_id: linked-platform-id
    measure: Net Revenue
    deployment_version: release-2026.07.28
    endorsement:
      requested: certified
      applied_after_governance_decision: true

  evidence:
    lineage: /lineage/net-revenue/3.2
    quality_results: /quality/net-revenue/latest
    freshness_slo: data-available-by-07-00-cet
    reconciliation:
      reference: finance-monthly-close
      tolerance: 0.10-percent
      latest_result: passed
    consumer_validation: /reviews/net-revenue-consumers
    access_review: /access/sales-performance

  lifecycle:
    review_cadence: quarterly
    next_review: 2026-10-28
    change_triggers:
      - source-change
      - formula-or-grain-change
      - quality-threshold-breach
      - access-policy-change
      - ownership-change
    deprecation_replacement: null

  decision:
    outcome: certified
    approved_by: accountable-data-owner
    reviewed_by:
      - commercial-data-steward
      - quality-control-reviewer
      - consumer-representative
    decision_date: 2026-07-28
    open_exceptions: []
```

The record should reference durable evidence locations. Copied screenshots age quickly, are difficult to query and often lose the context that produced them.

### Minimum outputs

The certification workflow should produce:

- certification decision and approved scope;
- accountable owner and implementation custodian;
- production implementation reference;
- evidence-completeness result;
- platform endorsement action;
- unresolved exceptions and expiry dates;
- review date and change triggers;
- deprecation or migration action where required.

Useful operating metrics include:

- certification lead time;
- evidence completeness;
- certified metric reuse;
- unresolved exceptions;
- overdue reviews;
- quality incidents affecting certified metrics;
- consumer migration from deprecated metrics.

These metrics measure the health of the trust process. Counting badges alone does not.

## Tools

Use the existing Binom tools to create and validate the evidence package:

- [KPI Definition Card](/tools/kpi-definition) — capture the business question, approved definition, grain, formula components, owner, status and version.
- [Report Inventory Canvas](/tools/report-inventory) — identify competing metric implementations, local overrides, affected consumers and migration scope.
- [BI Python Export Toolkit](/tools/bi-python-toolkit) — extract larger inventories of Power BI semantic models, measures, expressions and report dependencies for review.
- [Power BI DAX Measure Generator](/tools/powerbi-dax-generator) — create implementation code and documentation only after the metric definition and certification scope are approved.

The tools generate evidence or implementation artifacts. They do not approve business meaning, accept risk or apply certification autonomously.

## Resources

- [Semantic Layer vs Measure in the Report](/en/stories/semantic-layer-vs-report-measure) — decide where the metric should live before certifying its implementation.
- [Trusted Metrics learning path](/en/paths/trusted-metrics) — connect definition, ownership, implementation, evidence and lifecycle.
- [Microsoft Learn — Power BI content endorsement](https://learn.microsoft.com/en-us/power-bi/collaborate-share/service-endorsement-overview)
- [Microsoft Learn — Endorse Fabric and Power BI items](https://learn.microsoft.com/en-us/fabric/fundamentals/endorsement-promote-certify)
- [Microsoft Learn — Enable item certification](https://learn.microsoft.com/en-us/fabric/admin/endorsement-certification-enable)
- [Microsoft Learn — Semantic model discoverability](https://learn.microsoft.com/en-us/power-bi/collaborate-share/service-discovery)
- [Microsoft Learn — Semantic model Build permission](https://learn.microsoft.com/en-us/power-bi/connect-data/service-datasets-build-permissions)
- [Microsoft Learn — Metadata scanning](https://learn.microsoft.com/en-us/fabric/governance/metadata-scanning-overview)
- [Microsoft Learn — OneLake catalog exploration](https://learn.microsoft.com/en-us/fabric/governance/onelake-catalog-explore)
- [Microsoft Learn — Fabric deployment process](https://learn.microsoft.com/en-us/fabric/cicd/deployment-pipelines/understand-the-deployment-process)

> **Feature status:** July 2026. Microsoft product names, eligible item types, tenant controls, certifier permissions, discovery behavior, APIs and licensing conditions can change. Verify the current Microsoft documentation and the deployed tenant configuration before implementation.

Professional qualifications or external compliance certifications can establish individual capability or organizational controls. They do not replace metric-specific definition, lineage, quality, reconciliation, access and lifecycle evidence.

## Playbooks

Reuse these playbooks rather than recreating their governance decisions:

- [KPI & Metric Governance](/en/playbooks/kpi-metric-governance) — define how business meaning, calculations, dimensions, ownership and controlled change remain consistent.
- [The Missing Pieces — Trusted Metrics](/en/playbooks/missing-pieces-trusted-metrics) — evaluate whether definition, ownership, lineage, quality, access and lifecycle are sufficient for trusted use.
- [KPI Definition, Ownership and Versioning](/en/playbooks/define-kpi) — create the approved and historically reproducible metric contract used by the certification record.

This story does not replace those playbooks. It defines the narrower decision that converts their evidence into a controlled trust state and a platform implementation.

## Next step

Select one business-critical metric that already exists in a production Power BI semantic model.

Do not begin by certifying the entire workspace. Build one complete Metric Certification Record and verify the chain:

```text
Approved KPI definition
→ Metric placement decision
→ Production implementation
→ Current evidence package
→ Owner and reviewer approval
→ Platform endorsement
→ Discovery and permitted reuse
→ Recertification lifecycle
```

Use a metric with visible business impact and at least one known competing implementation. Reconcile the result, close or time-limit the evidence gaps, assign the review date and apply the platform badge only after the governance decision is complete.

A trusted metric is not the one with the most prominent badge. It is the one whose meaning, implementation, evidence and lifecycle remain reviewable.
