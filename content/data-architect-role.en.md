---
title: The Data Architect Role — Grain, Contracts and Architectural Consistency
description: How Data Architects create coherence across grain, model boundaries, Data Contracts, platform choices and change without replacing business ownership, stewardship or operations.
category: Data Governance
tags:
  - data-governance
  - data-architecture
  - data-architect
  - decision-rights
  - data-contract
  - grain
  - architecture-decision-record
  - data-product
  - semantic-model
  - platform-operations
order: -1
author: Thomas Lindackers
series: roles-hub
seriesPart: 1
seriesTitle: Roles and Decision Rights
hero: images/playbooks/data-architect-role-hero.png
---

## Architecture fails at the boundaries

Many data initiatives have competent engineers, capable platforms and documented governance roles. They still produce inconsistent results.

The failure often appears between components:

- a source exposes one business event, while a transformation silently changes the grain;
- a Data Product claims daily stability, while its schema changes without compatibility rules;
- a semantic model combines facts at different levels of detail;
- a platform team optimizes runtime implementation without knowing the consumer contract;
- a business team accepts a KPI, but no one owns the architectural consequences of its model;
- a central architect reviews every detail and becomes a delivery bottleneck.

These are not isolated modeling errors. They are failures of architectural decision rights.

> **A Data Architect is accountable for architectural coherence across grain, model boundaries, interfaces and change. The role does not replace business ownership, stewardship or platform operations.**

The Architect therefore connects decisions that would otherwise remain local. The objective is not central control over every implementation choice. The objective is a data path whose components remain compatible, explainable and changeable.

## The role is defined by coherence, not by a tool

A Data Architect is not the person who selects a warehouse product first and then fits every problem into it.

The role begins with questions such as:

- What business event is represented?
- What does one row mean?
- Which model boundary should remain stable?
- Which interface is consumed by other teams?
- Which changes are compatible?
- Where must business meaning be approved?
- Which runtime constraint materially changes the design?
- Which decision can remain local, and which one has enterprise impact?

The answers shape source integration, transformation boundaries, Data Products, marts, semantic models, APIs and deployment patterns.

Technology matters, but it is evaluated against declared requirements. A tool can implement an architecture. It cannot define the business grain, accept risk or decide which meaning is authoritative.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-architect-role-img1-en.png"
        alt="Decision boundary between Data Owner, Data Steward, Data Architect and Platform Operations around shared Data Product, change, release and exception decisions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The Data Architect owns architectural consistency. Business accountability, governance approval and runtime operation remain with their respective roles.
    </figcaption>
</figure>

## Four roles, four different accountabilities

A shared data initiative needs collaboration, but collaboration does not mean that responsibilities should become interchangeable.

### Data Owner

The Data Owner remains accountable for the business outcome and the acceptable use of the data.

Typical decisions include:

- business priority and funding;
- permitted use;
- risk acceptance;
- outcome ownership;
- escalation when business value and control requirements conflict.

The Architect may explain consequences, but should not accept business risk on behalf of the Owner.

### Data Steward

The Data Steward maintains and validates governed meaning.

Typical responsibilities include:

- business definitions;
- classification;
- quality expectations;
- controlled vocabulary;
- approval evidence;
- coordination of corrections and review.

The Architect ensures that these decisions can be represented consistently across models and interfaces. The Steward determines whether the meaning, classification or quality expectation is correct.

### Data Architect

The Data Architect is accountable for decisions that affect structural coherence.

Typical responsibilities include:

- declared grain;
- keys and time semantics;
- model and domain boundaries;
- integration patterns;
- Data Contracts and interface compatibility;
- reusable architectural standards;
- impact analysis for structural change;
- architecture review thresholds;
- Architecture Decision Records;
- controlled exceptions.

This does not mean that the Architect designs every table. It means that teams have clear guardrails and that important deviations are visible and reviewable.

### Platform Operations

Platform Operations owns reliable execution.

Typical responsibilities include:

- deployment mechanisms;
- runtime reliability;
- observability;
- incident handling;
- operational standards;
- capacity and performance constraints;
- backup, recovery and lifecycle execution.

The Architect must design within real implementation and operating constraints. Platform Operations should not have to infer architectural intent from deployment tickets.

## Grain is the first architectural contract

Teams frequently begin with columns, schemas or source extracts. The most important decision is often made later, implicitly:

> What does one row represent?

That question defines the grain.

A useful grain statement is precise:

```text
One row represents one invoiced order line
for one legal entity
in the source transaction currency
at the time of posting.
```

This statement drives:

- uniqueness rules;
- primary and business keys;
- join behavior;
- aggregation safety;
- late-arriving changes;
- historical corrections;
- relationships to dimensions;
- consumer expectations.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-architect-role-img2-en.png"
        alt="Architecture sequence from business event and declared grain through keys, facts, contracted interface and semantic model to consumers, including a failure path for mixed grain"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Grain must be declared before the schema becomes an interface. Mixed or undefined grain creates duplicated measures, ambiguous joins and inconsistent KPIs downstream.
    </figcaption>
</figure>

### Mixed grain must not be hidden inside one model

Consider a model that contains:

- one row per order line for sales amounts;
- one row per shipment for delivery status;
- one row per order for customer attributes;
- one row per invoice for payment status.

If these levels are combined without explicit modeling, the table may look convenient but cannot be safely aggregated. A semantic layer may hide the duplication temporarily, but it does not remove the structural defect.

The Architect should require one of the following:

- separate facts with explicit relationships;
- a declared higher-level aggregate;
- a bridge or allocation rule with documented semantics;
- a purpose-specific interface that clearly limits valid measures;
- a redesign before the model becomes a shared contract.

The decision is architectural because it affects every downstream consumer, not only the current implementation.

## One architecture contract across the data path

A Data Contract is often treated as a schema agreement at the source boundary. That is useful but incomplete.

A governed data path contains several connected contracts:

1. **Source Contract** — identifiers, source schema and change expectations.
2. **Transformation Contract** — grain, derivation and quality checks.
3. **Data Product Contract** — purpose, owner, service expectations and permitted use.
4. **Semantic Contract** — entities, measures, dimensions and time logic.
5. **Consumption Contract** — API or model, filters, refresh and compatibility.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-architect-role-img3-en.png"
        alt="Five connected contracts across source, transformation, Data Product, semantic and consumption layers with shared version, ownership, approval, compatibility and evidence"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The Data Architect aligns structural contracts across the path. Data Owners and Stewards still approve business meaning, permitted use, classifications and governance decisions.
    </figcaption>
</figure>

Each contract should make five control dimensions visible:

- **version** — which state is being used;
- **owner** — who is accountable for the relevant decision;
- **approval** — which decisions require explicit acceptance;
- **compatibility** — what downstream consumers can rely on;
- **evidence** — how implementation and verification are demonstrated.

The contracts do not need to be stored in one tool. They do need to remain connected.

For example, a source schema registry may hold physical compatibility rules, transformation code may hold grain and tests, a catalog may expose ownership and approved meaning, a semantic model may define measures, and an API specification may define consumption behavior. The architectural responsibility is to prevent these representations from contradicting one another.

## The Architect’s contribution to key technical decisions

The Data Architect should neither dominate every technical decision nor disappear after producing a target-state diagram.

### Stack decisions

The Architect defines the evaluation context:

- workload and latency requirements;
- integration boundaries;
- required controls;
- interoperability;
- operating model;
- portability and lock-in exposure;
- cost drivers;
- team capability;
- expected change rate.

The final product choice may be shared with engineering, platform leadership, security, procurement and business sponsors. The Architect ensures that the decision is based on requirements rather than feature enthusiasm.

### Integration decisions

The Architect decides or defines guardrails for:

- batch, streaming or event-driven integration;
- source extraction boundaries;
- canonical versus source-aligned models;
- identity and key management;
- schema evolution;
- replay and correction behavior;
- cross-domain interfaces.

### Mart and semantic decisions

The Architect establishes where reusable business structures belong and where purpose-specific models are appropriate.

Questions include:

- Is this a governed Data Product or a local analytical mart?
- Which grain is authoritative?
- Which dimensions are conformed?
- Which time logic is shared?
- Which measures require a governed KPI Contract?
- Which logic may remain consumer-specific?

Detailed KPI governance belongs in the dedicated KPI playbooks. The Architect’s role here is to ensure that the model can support an approved definition without duplicating incompatible logic.

### Deployment decisions

The Architect contributes architectural release conditions:

- contract validation;
- compatibility checks;
- migration and rollback strategy;
- consumer impact analysis;
- parallel validation where needed;
- evidence that the implemented model matches the approved design.

Platform Operations owns the deployment mechanism and runtime execution. The Architect defines the structural conditions under which the change is safe.

## The simplest viable architectural practice

A large architecture board is not the minimum requirement.

A small organization can start with six artifacts:

1. **Grain statement** for every shared fact or analytical interface.
2. **Interface contract** with schema, semantics, version and compatibility rule.
3. **Published standards** for common model and integration patterns.
4. **Architecture Decision Record** for material or non-obvious choices.
5. **Review thresholds** that define when architecture involvement is mandatory.
6. **Exception record** with owner, rationale, risk, expiry and remediation path.

These artifacts should live close to delivery. They may be stored in version control, a documentation platform or a governance repository, but they must be reviewable with the implementation.

### A minimal ADR

An Architecture Decision Record can remain short:

```yaml
title: Separate shipment fact from order-line fact
status: accepted
decision_owner: data-architect
business_approver: sales-data-owner
governance_reviewer: sales-data-steward
context: Shipment events occur at a different grain than order lines.
decision: Create a shipment fact and relate it through order-line allocation.
consequences:
  - delivery measures cannot be joined directly to order-line measures
  - semantic model requires an explicit relationship
  - historical shipment corrections remain traceable
review_date: 2027-01-31
```

The ADR records why a choice was made. It does not replace code, tests, contracts or business approval.

## Collaboration should produce one implementable decision

### With the Data Owner

The Architect translates business priorities and accepted risks into structural consequences.

Examples:

- a near-real-time requirement may require a different integration path;
- a cross-domain Data Product may require stable enterprise identifiers;
- an accepted temporary quality limitation may require a visible consumer warning;
- a permitted-use restriction may affect interface and access design.

The Owner approves the business trade-off. The Architect ensures that the technical design reflects it.

### With the Data Steward

The Steward and Architect meet at the boundary between meaning and structure.

They jointly clarify:

- whether a field represents the intended business concept;
- whether classification applies to derived attributes;
- which quality expectation is mandatory;
- which definitions are approved;
- how changes affect catalog, lineage and consumer documentation;
- which evidence is required before release.

The Steward should not be asked to approve a model whose grain or derivation cannot be explained.

### With Platform Operations

The Architect needs operational facts early:

- supported deployment patterns;
- service limits;
- observability capabilities;
- recovery objectives;
- runtime security controls;
- cost and capacity behavior;
- maintenance windows;
- platform lifecycle constraints.

Platform Operations, in turn, needs explicit contracts and non-functional expectations. Reliability cannot be inferred from an architecture drawing.

## Concrete example: changing the sales interface

A Sales Data Product publishes an `order_line` interface used by Finance, Operations and several BI models.

The domain team proposes adding shipment status directly to the existing table.

The request appears small. Architectural analysis reveals that:

- one order line may have several shipments;
- shipment events can arrive after invoicing;
- corrections may reorder the shipment history;
- Finance consumes the table at invoice-posting grain;
- Operations needs the latest shipment state;
- the current contract promises one row per invoiced order line.

The Data Architect does not decide whether shipment status is important. That is a business decision.

The Architect does decide that adding repeated shipment records would break the existing grain and contract.

A controlled solution could be:

1. retain the existing `order_line` contract;
2. create a separate `shipment_event` fact;
3. publish an optional current-shipment projection for operational consumers;
4. define keys and temporal behavior explicitly;
5. record the decision in an ADR;
6. let the Data Steward approve the shipment definitions and quality expectations;
7. let the Data Owner confirm priority and permitted use;
8. let Platform Operations validate refresh, observability and recovery requirements;
9. test affected semantic models before release.

This preserves architectural consistency without preventing the domain team from delivering value.

## Scale architecture without creating a bottleneck

Architecture becomes ineffective when every decision requires central approval.

The alternative is not unrestricted autonomy. It is delegated decision-making within explicit guardrails.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/data-architect-role-img4-en.png"
        alt="Decision workflow from team proposal through published standards, local decision or architecture review, ADR, implementation, verification and updated standards"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Teams should decide locally inside published guardrails. Architecture Review is reserved for decisions with enterprise, cross-domain, compatibility, cost or risk impact.
    </figcaption>
</figure>

### Decisions that normally require Architecture Review

- a new enterprise pattern;
- a cross-domain interface;
- an incompatible contract change;
- a new platform category;
- a high-cost or high-risk exception;
- a decision that changes shared identity, grain or time semantics;
- a change that cannot be reversed safely.

### Decisions that can normally remain local

- implementation details inside an approved pattern;
- an approved pattern variant;
- reversible low-risk choices;
- local optimizations that do not change a shared contract;
- naming or packaging choices covered by standards.

The important mechanism is escalation by threshold, not escalation by habit.

## Common anti-patterns

### The Architect as mandatory approval bottleneck

Every team waits for one central person. Queues grow, decisions move into informal channels and teams bypass architecture to maintain delivery speed.

**Correction:** publish guardrails, define review thresholds and delegate reversible decisions.

### Paper architecture disconnected from implementation

The target state is documented, but code, interfaces and deployments are not verified against it.

**Correction:** connect ADRs, contracts, tests and release evidence to delivery.

### Tool-first architecture

A platform is selected before grain, consumers, controls and operating constraints are understood.

**Correction:** define the decision context first and evaluate products against it.

### Mixed grain hidden inside one model

Convenience is prioritized over structural correctness. Consumers receive duplicated measures and ambiguous joins.

**Correction:** declare grain, separate facts or define an explicit allocation and aggregation contract.

### Interfaces changed without consumer impact analysis

A technically valid schema change breaks reports, semantic models, APIs or downstream transformations.

**Correction:** version interfaces, classify compatibility and identify consumers before release.

### Architecture ownership confused with business accountability

The Architect is asked to approve meaning, permitted use or business risk.

**Correction:** keep business decisions with the Data Owner and governed meaning with Stewardship. Architecture records and implements their structural consequences.

## Decide, advise or delegate

The Data Architect should use three decision modes.

| Mode | Use when | Architect’s responsibility |
| --- | --- | --- |
| **Decide** | The choice affects enterprise patterns, shared grain, cross-domain interfaces, compatibility or material architectural risk. | Make or chair the architectural decision, document it and define consequences. |
| **Advise** | The accountable decision belongs to the Data Owner, Steward, Platform Operations or another specialist role, but has architectural implications. | Explain options, constraints, dependencies and impact. |
| **Delegate within guardrails** | The choice is local, reversible and covered by published standards. | Provide standards, remain available for escalation and verify outcomes through evidence. |

A mature architecture function does not maximize the number of decisions made centrally. It maximizes the number of correct decisions that can be made safely across the organization.

## Key recommendations

1. Define the Data Architect by accountability for coherence, not by ownership of a specific platform.
2. Declare grain before shared schemas, facts or semantic models are implemented.
3. Treat source, transformation, Data Product, semantic and consumption contracts as one connected architecture path.
4. Keep business risk and permitted use with the Data Owner.
5. Keep definitions, classifications, quality expectations and approval evidence with Stewardship.
6. Keep runtime reliability, deployment and observability with Platform Operations.
7. Use ADRs for material decisions, not for every implementation detail.
8. Publish standards and review thresholds so domain teams can act autonomously.
9. Require impact analysis for incompatible interface changes.
10. Verify that implemented models and releases match the architectural decision.

## Next: make shared responsibilities explicit with RACI

This story defines the Data Architect’s decision boundary. The next Roles Hub story, `raci-for-data-governance`, turns collaboration across Owners, Stewards, Architects, Platform Operations and domain teams into explicit responsibility assignments.

The purpose is not to create a large responsibility matrix for every activity. It is to identify where accountability is singular, where contribution is shared and where escalation must be unambiguous.
