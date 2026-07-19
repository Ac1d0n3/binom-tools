---
type: sprint-plan
title: Database model (4 weeks)
slug: database-model
description: Design a focused database / warehouse model: scope, entities, relationships, naming, and review.
duration: 4
unit: week
category: Data Modeling
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Modeling
  - Database
  - Warehouse
---

Four weeks to get from questions to an approved model v1.

```sprint
id: week-01
number: 1
title: Scope & questions
goal: Clarify what the model must answer.

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: choosing-the-simplest-viable-architecture
    required: false
  - slug: platform-examples
    required: false

tasks:
  - id: w1-scope
    label: Capture business questions and grain
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Business question, Fact grain, Decision, Out of scope, Owner
    helpText: |
      Start with questions, not tables. For each decision question, list which event or observation should later become one fact row.
      Define the grain of each fact explicitly, for example order-line-day instead of only "sales".
      Keep operational grains separate from analytical grains so the model does not try to be both process log and reporting layer.
      Mark non-goals early, especially level of detail, history, and near-real-time expectations.
    stories:
      - slug: before-building-the-first-table
        required: true
    helpLinks:
      - label: Microsoft Learn - Star schema guidance
        href: https://learn.microsoft.com/en-ie/power-bi/guidance/star-schema
      - label: Snowflake - Data modeling guide
        href: https://www.snowflake.com/en/fundamentals/data-modeling/
  - id: w1-sources
    label: Inventory candidate sources
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Source, Owner, Freshness, Key fields, Known issue
    helpText: |
      Inventory candidate sources with system, owner, freshness, key fields, and known quality issues.
      Use the Meta Export Generator for a quick column view, but confirm critical fields with business owners.
      Check whether source systems provide history or only current state. That decision affects SCDs, snapshots, and auditability.
      Document missing access and unclear responsibilities as risks, not as later details.
    helpLinks:
      - label: Meta export generator
        href: /tools/meta-export-generator
      - label: Architecture Fit Checklist
        href: /tools/architecture-fit
      - label: Snowflake - Databases, tables, and views
        href: https://docs.snowflake.com/en/en/guides-overview-db

deliverables:
  - id: w1-scope-note
    label: Scope & grain note
    plannedMinutes: 60
    helpText: |
      Create a scope note with business questions, fact grain, required sources, non-goals, and open assumptions.
      The deliverable is done when another person can see which table class is needed and which details are intentionally not modeled.
      Link example reports, source inventory, and open decisions.

fields:
  - id: target-grain
    label: Target grain
    type: textarea
    placeholder: Fact grain, dimensions, history needs, and non-goals
  - id: source-risks
    label: Source risks
    type: textarea
    placeholder: Missing access, unclear owners, quality issues, history gaps

notes: true
```

```sprint
id: week-02
number: 2
title: Entities & relationships
goal: Draft entities, keys, and relationships.

tasks:
  - id: w2-entities
    label: Draft entity list and keys
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Entity, Type, Grain, Primary key, Change behavior
    helpText: |
      Draft entities as fact, dimension, bridge, or reference tables. Every entity needs grain, key, and clear change behavior.
      Decide deliberately between natural key, surrogate key, and composite key.
      Note which attributes must be historized and which may be overwritten.
      Prefer the simplest viable structure: more layers help only when they improve ownership, performance, or reuse.
    stories:
      - slug: beyond-bronze-silver-gold
        required: false
      - slug: choosing-the-simplest-viable-architecture
        required: false
      - slug: platform-examples
        required: false
    helpLinks:
      - label: Architecture Fit Checklist
        href: /tools/architecture-fit
      - label: Microsoft Learn - Star schema guidance
        href: https://learn.microsoft.com/en-ie/power-bi/guidance/star-schema
  - id: w2-rels
    label: Map relationships and cardinality
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: From table, To table, Cardinality, Join key, Integrity rule
    helpText: |
      Map relationships with cardinality, join key, optionality, and integrity rule.
      Resolve many-to-many relationships deliberately through bridge tables and name their owner.
      Check whether relationships are business-stable or only happen to work in the current data.
      Document edge cases such as unknown members, late-arriving data, or optional dimensions.
    helpLinks:
      - label: PostgreSQL - Primary and foreign keys
        href: https://www.postgresql.org/docs/current/ddl-constraints.html

deliverables:
  - id: w2-model-draft
    label: Entity-relationship draft
    plannedMinutes: 120
    helpText: |
      Create a diagram or table list with entities, keys, cardinalities, grain, and open modeling decisions.
      The deliverable is done when fact and dimension tables are distinguishable and every relationship has a reason.
      Mark which rules should later be tested technically or represented as constraints.

fields:
  - id: relationship-decisions
    label: Relationship decisions
    type: textarea
    placeholder: Cardinalities, bridge tables, optional relationships, open rules

notes: true
```

```sprint
id: week-03
number: 3
title: Naming & standards
goal: Apply naming, types, and conventions.

tasks:
  - id: w3-naming
    label: Apply naming conventions
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Object, Current name, Proposed name, Rule, Exception
    helpText: |
      Align tables, columns, boolean flags, date fields, and technical keys with platform standards.
      Names should be business-readable and technically stable: avoid abbreviations only one person understands.
      Document exceptions deliberately, for example source-system names, legal terms, or established KPI names.
      Check whether names stay understandable in BI tools, YAML docs, and SQL.
    helpLinks:
      - label: Snowflake - dbt projects best practices
        href: https://docs.snowflake.com/en/user-guide/data-engineering/dbt-projects-on-snowflake-best-practices
  - id: w3-types
    label: Confirm types and nullability
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Column, Type, Nullable, Default, PII, Test
    helpText: |
      Confirm data type, nullability, default, unit, and valid range for critical columns.
      Mark PII candidates directly and decide whether they are needed, masked, hashed, or excluded.
      Plan tests for required fields, uniqueness, accepted values, and relationships.
      Capture which rules will be technically enforced and which remain documented business expectations.
    helpLinks:
      - label: Schema YML Editor
        href: /tools/schema-yml-editor
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
      - label: PostgreSQL - Constraints
        href: https://www.postgresql.org/docs/current/ddl-constraints.html

deliverables:
  - id: w3-standards
    label: Naming & type standards applied
    plannedMinutes: 60
    helpText: |
      Store a checklist or PR note summarizing naming, types, nullability, PII decisions, and planned tests.
      The deliverable is done when reviewers can understand the conventions and comment on disputed exceptions directly.
      Link YAML documentation, model draft, and open privacy decisions.

fields:
  - id: standards-exceptions
    label: Standards exceptions
    type: textarea
    placeholder: Deliberate naming, type, or privacy exceptions with rationale

notes: true
```

```sprint
id: week-04
number: 4
title: Review & freeze
goal: Review with owners and freeze v1.

tasks:
  - id: w4-review
    label: Walkthrough with stakeholders
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Stakeholder, Concern, Decision, Owner, Due date
    helpText: |
      Run the walkthrough by business question, not by every column. Show grain, entities, relationships, and known limits.
      Capture objections as decisions: accept, change, defer, or remove from scope.
      Check whether owners are named for definitions, sources, and operating questions.
      Record explicit acceptance of v1 so later improvements do not return as vague defects.
    stories:
      - slug: data-ownership-stewardship
        required: false
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
      - label: Atlassian - Acceptance criteria
        href: https://www.atlassian.com/work-management/project-management/acceptance-criteria
  - id: w4-freeze
    label: Freeze model v1 and next steps
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Follow-up, Reason, Owner, Priority, Target
    helpText: |
      Version the model, diagram, YAML documentation, and open decisions together.
      List follow-ups for physical implementation, performance, tests, privacy, and later model extensions.
      Separate v1 fixes from v2 ideas so the freeze does not immediately soften again.
      Name who owns the model functionally and technically after approval.
    helpLinks:
      - label: Snowflake - Data modeling guide
        href: https://www.snowflake.com/en/fundamentals/data-modeling/

deliverables:
  - id: w4-model-v1
    label: Model v1 approved
    plannedMinutes: 120
    helpText: |
      Keep the approved model artifact, review decisions, known limits, and follow-ups in one place.
      The deliverable is done when v1 can be built without renegotiating the business modeling decision.
      Open points need owner, priority, and target; otherwise they do not belong in the freeze.

fields:
  - id: model-freeze-summary
    label: Freeze summary
    type: textarea
    placeholder: Approval, known limits, owners, v1 fixes, v2 ideas

notes: true
```
