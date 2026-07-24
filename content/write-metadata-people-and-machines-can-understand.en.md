---
title: Write Metadata People and Machines Can Understand — Describe Tables, Fields and Metrics with Meaning, Boundaries and Examples
description: A practical method for writing table, column, KPI, identifier, status, timestamp, calculated-field and AI-feature descriptions that remain useful to people, catalogs, RAG systems and AI assistants.
category: Data Governance
tags:
  - metadata
  - metadata-governance
  - data-catalog
  - data-documentation
  - business-glossary
  - data-quality
  - semantic-layer
  - rag
  - ai-ready-metadata
  - data-products
  - kpi-governance
  - data-contracts
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 5
seriesTitle: MetaData Deep Dive
hero: images/playbooks/write-metadata-people-and-machines-can-understand-hero.png
---

## A technically correct name is not a useful description

A metadata platform can harvest a table name, data type, lineage edge and last refresh automatically. It can prove that a field exists, show where it came from and identify which reports use it.

That still does not explain what the field means.

Names such as `sales_amount`, `customer_id`, `order_date` and `status` look familiar. Their apparent clarity is dangerous because different teams can interpret the same name differently:

- Is `sales_amount` gross, net, invoiced, ordered or recognized revenue?
- Does it include tax, shipping, rebates, cancellations and later credit notes?
- Is `customer_id` stable across source systems, legal entities and customer merges?
- Does `order_date` represent creation, booking, confirmation or fulfillment?
- Is `status` a source code, a normalized lifecycle state or the latest observed state?

A description such as `Sales = Revenue` does not resolve any of these questions. It merely replaces one ambiguous label with another.

> **A useful description adds information that cannot be derived from the technical name. It explains meaning, grain, calculation, time, units, boundaries, exceptions, relationships and intended use.**

This is important for people and machines.

A business user needs enough context to choose the correct field. An engineer needs enough precision to implement and test it. A Data Steward needs enough structure to assess ownership, quality and policy. A catalog needs consistent attributes for search and comparison. A RAG system or AI assistant needs explicit boundaries so that it does not treat a plausible label as a complete definition.

Good metadata is therefore not decorative prose. It is a compact decision contract.

## Write descriptions for decisions, not for inventory

A weak description answers only:

```text
What is this object called?
```

A decision-ready description answers:

```text
What does it represent?
At which grain?
For which population?
At which point in time?
In which unit or currency?
How is it calculated?
Which exceptions apply?
How does it relate to similar fields?
Where may it be used?
Where must it not be used?
Who is accountable for the meaning?
```

Not every asset needs the same amount of text. The required detail should depend on ambiguity, business impact, reuse, sensitivity and the risk of incorrect interpretation.

A technical staging column that is never exposed outside one pipeline may need a concise source mapping and transformation note. A certified enterprise KPI used for executive decisions requires a complete definition, formula, denominator, time window, exclusions, restatement policy, owner and approved use.

The objective is not maximum documentation volume. The objective is sufficient information for a correct decision.

## The anatomy of a useful table description

A table description must explain the dataset as a governed population of rows. Repeating the table name or listing a few columns is not enough.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/write-metadata-people-and-machines-can-understand-img1-en.png"
        alt="Anatomy of a useful description for the fct_sales_order_line table covering purpose, grain, population, time, measures and keys, limitations and intended use"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A useful table description defines the represented process, row grain, population boundaries, time behaviour, important keys and measures, known limitations and intended analytical use.
    </figcaption>
</figure>

Consider the fact table `fct_sales_order_line`.

A useful table description should cover seven components.

### Purpose

State which business process or analytical subject the table represents.

Weak:

```text
Sales order line fact table.
```

Better:

```text
Represents commercial sales-order lines accepted by the order-management process and prepared for operational sales analysis and management reporting.
```

The better version explains the business process and the role of the table. It does not merely restate the object name.

### Grain

State exactly what one row represents.

Example:

```text
One row per source sales-order line and effective version.
```

The word `version` matters. Without it, a user may assume one current row per order line and double-count records in a historized model.

Grain should identify the smallest stable business event or entity represented by one row. For snapshots or historized datasets, it must also explain the snapshot date, effective interval or version mechanism.

### Population

Define what is included and excluded.

Example:

```text
Includes accepted standard, service and replacement order lines from the European order platform. Excludes quotations, test orders, internally rejected lines and records removed before source confirmation.
```

Population is often the difference between two tables that appear to contain the same subject.

### Time

Explain the relevant business dates and update behaviour.

Example:

```text
The primary business date is booking_date. Source events are loaded incrementally. Late corrections can create a new effective version for a previously reported order line.
```

A table can contain many timestamps. The description should identify which one normally controls reporting and how late changes affect historical results.

### Measures and keys

Name the principal identifiers and measures without reproducing the complete schema.

Example:

```text
The business key is sales_order_id + sales_order_line_id. Main additive measures are ordered_quantity and net_sales_amount in reporting currency.
```

This tells users how the table can be joined and aggregated. It does not need to list every technical surrogate key or audit field.

### Known limitations

State material weaknesses, gaps and exceptions.

Example:

```text
Historical source states before 2024-01-01 are incomplete for one legacy region. Credit notes are represented in the billing model and are not retroactively applied to net_sales_amount in this table.
```

A known limitation is part of the product contract. Hiding it does not improve trust.

### Intended use

State suitable analytical purposes.

Example:

```text
Suitable for order intake, operational pipeline analysis and reconciliation to the order platform. Not suitable as the authoritative source for recognized revenue or final invoiced value.
```

This prevents users from choosing a technically related dataset for the wrong business decision.

## The anatomy of a useful field description

A field description should be more precise than the parent table description because users often encounter fields in search results, semantic models or AI responses without reading the full dataset context.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/write-metadata-people-and-machines-can-understand-img2-en.png"
        alt="Structured metadata card for net_sales_amount with business meaning, calculation, currency, time reference, null behaviour, sign convention, exceptions, relationships, uses and ownership"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Field descriptions become reliable when meaning, calculation, unit, temporal reference, null behaviour, sign convention, exceptions, relationships and use boundaries are represented explicitly.
    </figcaption>
</figure>

For `net_sales_amount`, a useful definition could begin with:

```text
Net value of one order line after line-level discounts, before tax,
shipping and later credit notes.
```

That sentence provides the core business meaning, but a complete reusable metadata profile should separate additional attributes.

### Business meaning

Describe the business concept represented by the value.

Do not begin with implementation detail unless the implementation is the concept. `Calculated from AMT_01 minus DISC_02` is not a business definition.

### Calculation

Explain the calculation at the level required to understand the result.

Example:

```text
Base line value minus approved line-level discounts. Header-level rebates, tax, shipping charges and later billing corrections are excluded.
```

The description should summarize the logic. The authoritative executable expression should remain linked to version-controlled code or the semantic model that evaluates it.

### Unit or currency

State whether the value is a count, percentage, duration, quantity, score, local currency, document currency or reporting currency.

For currency fields, identify the currency source and conversion point.

Example:

```text
Stored in reporting currency. The currency code is reporting_currency_code. Conversion uses the approved daily rate valid on booking_date.
```

A decimal data type does not reveal a unit.

### Time reference

Explain which event, period or effective date the value represents.

Example:

```text
Represents the order-line value at the effective version valid for booking_date reporting. Later credit notes remain separate.
```

This is essential for mutable business processes and restated metrics.

### Null and default behaviour

Distinguish unknown, not applicable, not supplied and zero.

Example:

```text
NULL means the amount could not be derived because price or currency conversion input was unavailable. Zero is a valid calculated result for free-of-charge lines.
```

Replacing null with zero changes meaning. The description must make that visible.

### Sign convention

State how positive and negative values should be interpreted.

Example:

```text
Positive values increase order intake. Negative values represent reversal lines created by the order process. Billing credit notes are not represented here.
```

### Exceptions

Document business cases that do not follow the standard rule.

Examples include manual overrides, legacy regions, migration periods, missing source states, special product types and regulatory adjustments.

### Relationship to other fields

Differentiate the field from similar values.

Example:

```text
Differs from gross_sales_amount, which is calculated before discounts, and invoiced_net_amount, which represents billed value after invoice corrections.
```

This is often more useful than a longer standalone definition.

### Suitable and unsuitable use

State both.

Example:

```text
Suitable for order intake and discount analysis at order-line grain.
Not suitable for tax reporting, cash collection, recognized revenue or invoice reconciliation.
```

### Owner

Identify the role accountable for the definition and exceptions.

The owner of the physical database column and the owner of the business meaning may differ. Both can be recorded, but the description contract should identify who approves the semantic definition.

## Keep business explanation separate from executable logic

Calculation metadata must be precise without creating a manually maintained copy of implementation code.

A useful pattern has three layers:

```text
Business definition
+ structured calculation summary
+ reference to authoritative executable logic and version
```

Example:

```yaml
name: net_sales_amount
business_definition: >
  Net value of one order line after line-level discounts,
  before tax, shipping and later credit notes.
calculation_summary: >
  Base line value minus approved line-level discounts.
  Header-level rebates are excluded.
implementation_reference:
  system: transformation-repository
  asset: model.fct_sales_order_line
  field: net_sales_amount
  version: git:8f42c1a
```

This avoids two failure modes.

The first is vague prose that does not explain the calculation. The second is a copied SQL expression that becomes stale when the code changes.

The description explains intent and boundaries. The linked implementation proves how the current version executes that intent.

For complex KPIs, add a formula model with explicit components:

```text
Numerator
Denominator
Aggregation
Time window
Population filter
Exclusions
Currency or unit
Restatement rule
Rounding
```

A formula such as `revenue / customers` is not complete until both terms, population, time window and aggregation level are defined.

## Examples and counterexamples reduce interpretation risk

Definitions become more reliable when they include representative examples.

For a status code:

```text
FULFILLED — all required quantities were shipped and no open fulfillment task remains.
```

Add a counterexample:

```text
A line with partial shipment and a remaining backorder is not FULFILLED.
```

For a customer identifier:

```text
Example: 47110815 identifies one customer record in the European CRM tenant.
Counterexample: it is not an enterprise-wide legal-party identifier and may change after a customer merge.
```

For a KPI:

```text
Example: an order booked on 31 March and cancelled on 2 April remains in March gross order intake but is removed from current open-order value.
```

Examples are especially valuable when rules depend on time, state transitions or exclusions.

They help human readers, unit-test design, catalog search, RAG retrieval and AI assistants. The examples should be realistic but should not expose confidential personal or transactional data.

## Weak descriptions create confident mistakes

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/write-metadata-people-and-machines-can-understand-img3-en.png"
        alt="Before-and-after comparison of weak and decision-ready descriptions for sales_amount, customer_id, order_date and status"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Weak descriptions repeat labels. Decision-ready descriptions define boundaries, stability, time behaviour, valid states and analytical use.
    </figcaption>
</figure>

### `sales_amount`

Weak:

```text
Sales amount.
```

Decision-ready:

```text
Net value of one order line in reporting currency after line-level discounts and before tax, shipping and later credit notes. Additive across order lines for the same reporting-currency context.
```

### `customer_id`

Weak:

```text
Customer number.
```

Decision-ready:

```text
Identifier issued by the European CRM tenant for one customer record. Unique within that tenant, not enterprise-wide, and not guaranteed to remain stable after duplicate-record merges. Reuse after deletion is prohibited by the source process.
```

### `order_date`

Weak:

```text
Date of order.
```

Decision-ready:

```text
Calendar date on which the source order was accepted for booking, derived from the source event timestamp in Europe/Berlin. It may be corrected by a later source event. Use for order-intake reporting, not for shipment or invoice-period reporting.
```

### `status`

Weak:

```text
Current status.
```

Decision-ready:

```text
Normalized current lifecycle state of the order line. Allowed values are OPEN, PARTIALLY_FULFILLED, FULFILLED and CANCELLED. FULFILLED and CANCELLED are terminal states. A transition from FULFILLED to OPEN requires a source correction event and is flagged for review.
```

The improved descriptions are longer, but length is not the main difference. They contain decision-relevant constraints.

## One generic template is not enough

Tables, columns, KPIs, identifiers, statuses, timestamps, calculated fields and AI features fail in different ways. They therefore need different description questions.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/write-metadata-people-and-machines-can-understand-img4-en.png"
        alt="Template library with separate description questions for tables, columns, KPIs, identifiers, status codes, timestamps, calculated fields and AI features"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Reusable templates should be specific to the asset type. A single free-text description field cannot reliably capture every semantic, temporal and governance requirement.
    </figcaption>
</figure>

### Table template

Ask:

1. Which process, entity or event does the table represent?
2. What does one row represent?
3. Which records are included and excluded?
4. Which business date and update behaviour matter?
5. What are the intended uses and known limitations?

### Column template

Ask:

1. What business meaning does the value carry?
2. Which unit, currency, format or code system applies?
3. What do null, zero and defaults mean?
4. Which exceptions and similar fields must be distinguished?
5. Where may and may not the field be used?

### KPI template

Ask:

1. What decision does the KPI support?
2. What are formula, numerator, denominator and aggregation rules?
3. Which population, time window and exclusions apply?
4. How are currency, rounding, late data and restatements handled?
5. Who approves the definition and changes?

### Identifier template

Ask:

1. Which system issues the identifier?
2. Within which scope is it unique?
3. Is it stable across time, merges, migrations and source systems?
4. Can it be reused?
5. Which enterprise identifier or crosswalk relates to it?

### Status-code template

Ask:

1. Which process state does each code represent?
2. Which transitions are valid?
3. Which states are terminal?
4. How are unknown or legacy codes handled?
5. Is the value current state, event state or historical state?

### Timestamp template

Ask:

1. Which event or system action does the timestamp represent?
2. Which timezone and precision apply?
3. Is it source event time, ingestion time, processing time or effective time?
4. Can it be corrected or arrive late?
5. Which reporting decisions should use it?

### Calculated-field template

Ask:

1. What business concept is derived?
2. Which inputs, filters and transformation rules apply?
3. At which grain is the calculation valid?
4. How are nulls, signs, rounding and exceptional cases handled?
5. Where is the authoritative executable logic stored?

### AI-feature template

Ask:

1. How is the feature derived and from which source period?
2. Which entity and prediction point does it represent?
3. Which training window and freshness rule apply?
4. Is there target leakage, proxy discrimination or prohibited information risk?
5. For which model, population and purpose is the feature permitted?

An AI feature requires description of derivation and temporal validity. A technically valid feature can still be unsuitable if it includes information that would not have been available at prediction time.

## The simplest viable implementation

A team does not need to redesign its entire metadata platform before improving descriptions.

Start with one important data product and introduce five controls.

### 1. Define required fields by asset type

Use structured attributes for recurring questions and one concise narrative summary.

For a column, a minimum profile can be:

```yaml
name: net_sales_amount
summary: >
  Net value of one order line after line-level discounts,
  before tax, shipping and later credit notes.
unit_type: currency
currency_field: reporting_currency_code
time_reference: booking_date
null_meaning: unavailable_input
zero_is_valid: true
sign_convention: positive_increases_order_intake
suitable_use:
  - order_intake
  - discount_analysis
unsuitable_use:
  - recognized_revenue
  - tax_reporting
owner: sales-data-product-owner
```

### 2. Validate descriptions in the delivery workflow

A description should be reviewed when code, schema or semantic logic changes.

Useful automated checks include:

- required description missing;
- description identical to asset name or display label;
- banned placeholder phrases such as `TBD`, `same as source` or `self-explanatory`;
- missing unit for numeric measures;
- missing timezone for timestamps;
- missing allowed values for governed codes;
- missing owner for certified KPIs;
- referenced field or asset does not exist;
- implementation reference points to an outdated version.

Automation can detect incompleteness. It cannot determine whether a business definition is substantively correct.

### 3. Keep the authoring point close to the accountable knowledge

Source meaning should be authored by the source team or business owner. Derived-field context should be maintained with transformation code. Measure behaviour should be maintained with the semantic model. Enterprise terms and approvals should be maintained through governance workflows.

The central catalog can index and display all of them with provenance.

### 4. Publish both human-readable and structured representations

The narrative summary helps people understand the concept quickly. Structured attributes enable filters, validation, machine retrieval and comparison.

Do not force an AI assistant to infer `currency`, `timezone`, `terminal status` or `unsuitable use` from one paragraph when those values can be represented explicitly.

### 5. Review examples and boundaries with real consumers

Ask an analyst, engineer and accountable business owner to use the description for a real decision.

If they still need to ask what is included, which date to use or whether a field can be aggregated, the description is incomplete.

## Alternative operating patterns

### Repository-first documentation

Descriptions live with version-controlled models, schemas and semantic definitions. CI validates the required fields and publishes them to a catalog.

Best when technical and derived metadata changes through code review.

Main risk: business owners may not work comfortably in repository workflows.

### Catalog-first authoring

Business users and Data Stewards maintain definitions in a central governance platform. Approved values are linked or synchronized to technical assets.

Best when glossary, ownership and approval workflows dominate.

Main risk: technical logic and source changes can drift from copied descriptions.

### Federated authoring with central discovery

Each system remains authoritative for the metadata it can maintain correctly. A central layer connects descriptions, provenance, lineage, ownership and search.

Best for heterogeneous data estates.

Main risk: conflicts and precedence rules must be explicit.

### Controlled write-back

A central platform proposes a description change that is reviewed and written back to the authoritative repository or source system.

Best when users need a convenient interface but the repository must remain the System of Record.

Main risk: unrestricted bidirectional editing creates loops and silent overwrites.

The correct pattern depends on where accountable knowledge exists, not on which user interface is most attractive.

## Make descriptions usable for catalogs, RAG and AI assistants

AI systems do not remove the need for disciplined metadata. They increase it.

A RAG system retrieves fragments. An assistant may receive the field description without the full table page. A model may choose a field based on semantic similarity. Ambiguous descriptions therefore produce plausible but incorrect answers.

AI-ready metadata should provide:

- a concise standalone summary;
- explicit asset type;
- parent asset and grain;
- business term and synonyms;
- unit, currency and timezone;
- allowed values and null semantics;
- suitable and unsuitable uses;
- examples and counterexamples;
- owner and approval state;
- source and implementation references;
- effective version and update time;
- links to related and contrasting fields.

Descriptions should avoid unresolved pronouns such as `this value` when retrieved outside the original page. They should name the concept directly.

A machine-readable profile should preserve language and translation relationships. English and German descriptions may be equivalent, but one should not silently overwrite the other. Record language, translation status and approval state.

For retrieval, keep the atomic definition close to the asset identity. Long policy documents can provide additional context, but the field-level meaning should not depend on an AI system assembling five unrelated passages correctly.

## A concrete end-to-end example

The following profile combines table and field context without duplicating executable code.

```yaml
asset:
  name: fct_sales_order_line
  type: table
  purpose: >
    Represents accepted sales-order lines for operational sales analysis
    and management reporting.
  grain: one row per sales order line and effective version
  population:
    includes:
      - accepted standard order lines
      - service order lines
      - replacement order lines
    excludes:
      - quotations
      - test orders
      - internally rejected lines
  primary_time_reference: booking_date
  limitations:
    - legacy region history incomplete before 2024-01-01
    - later billing credit notes are represented in another model
  suitable_use:
    - order intake
    - operational pipeline analysis
    - source reconciliation
  unsuitable_use:
    - recognized revenue
    - final invoiced value

field:
  name: net_sales_amount
  business_meaning: >
    Net value of one order line after line-level discounts,
    before tax, shipping and later credit notes.
  calculation_summary: >
    Base line value minus approved line-level discounts.
  unit_type: currency
  currency_field: reporting_currency_code
  time_reference: booking_date
  null_behavior: null means required pricing or conversion input is unavailable
  zero_behavior: zero is valid for free-of-charge lines
  sign_convention: positive increases order intake; negative represents reversal lines
  related_fields:
    gross_sales_amount: before discounts
    invoiced_net_amount: billed value after invoice corrections
  suitable_use:
    - order intake
    - line-level discount analysis
  unsuitable_use:
    - tax reporting
    - cash collection
    - recognized revenue
  semantic_owner: sales-data-product-owner
  implementation_reference:
    system: transformation-repository
    asset: model.fct_sales_order_line
    field: net_sales_amount
```

A person can understand the narrative values. A validation process can test required attributes. A catalog can index relationships. An AI assistant can distinguish the field from gross, invoiced and recognized amounts.

## Common anti-patterns

### Repeating the name

```text
customer_id: Customer identifier
```

This adds no new information.

### Writing only the formula

```text
margin_pct = margin / revenue
```

The numerator, denominator, population, time window, zero-denominator handling and aggregation rule remain unknown.

### Copying implementation code into prose

The copied expression drifts from the executable version and is difficult for business readers to interpret.

### Describing the happy path only

Exceptions, late changes, nulls, cancellations and restatements are where analytical errors occur.

### Using one description for several different assets

A source field, transformed column, semantic measure and dashboard KPI can share a label while implementing different logic.

### Omitting unsuitable use

Users often know what a field resembles, not where its boundaries end.

### Treating detected metadata as approved meaning

A detector can propose that a field looks like an email address. It cannot approve the business definition, lawful use or policy classification by itself.

### Allowing unrestricted free text

Free text is useful, but without structured fields it is difficult to validate currency, timezone, allowed states, ownership and use restrictions.

### Making the description dependent on tribal knowledge

Phrases such as `standard logic`, `as usual` or `same as legacy report` are not portable metadata.

### Optimizing for completeness scores

A catalog can show 100% populated descriptions while every value says `self-explanatory`. Coverage is not quality.

## Decision guidance

For each important metadata asset, answer the following questions.

### Meaning and scope

1. What business concept, event or entity is represented?
2. What does one row or value represent?
3. Which population is included and excluded?
4. Which similar concepts must be distinguished?

### Calculation and aggregation

5. Is the value sourced, normalized, derived or manually entered?
6. Which calculation, filters and dependencies matter?
7. At which grain is it valid?
8. Is it additive, semi-additive, non-additive or non-aggregatable?
9. Where is the authoritative executable logic?

### Unit and time

10. Which unit, currency, format or code system applies?
11. Which timestamp or business period controls interpretation?
12. Which timezone and precision apply?
13. Can late changes or restatements alter historical results?

### Nulls, states and exceptions

14. What do null, blank, zero and default values mean?
15. Which values are allowed?
16. Which transitions are valid?
17. Which exceptions and known limitations apply?

### Use and accountability

18. Which decisions may use the asset?
19. Which decisions must not use it?
20. Who owns the semantic definition?
21. Who maintains the implementation?
22. Which review or approval state applies?

### Machine use

23. Can the description stand alone outside its original page?
24. Are key constraints represented as structured attributes?
25. Are examples, counterexamples, synonyms and related fields available?
26. Is provenance and version information retained?
27. Can an AI assistant distinguish this asset from similarly named alternatives?

If these questions cannot be answered, the asset may be technically available but is not yet decision-ready.

## Key recommendations

1. Require every useful description to add information beyond the technical name.
2. Describe tables through purpose, grain, population, time, keys, measures, limitations and intended use.
3. Describe fields through business meaning, calculation, unit, time reference, null behaviour, sign, exceptions, relationships and use boundaries.
4. Define KPIs with numerator, denominator, aggregation, population, time window, exclusions, restatement and rounding rules.
5. Define identifiers through issuing system, uniqueness scope, stability, merge behaviour and reuse policy.
6. Define status codes through allowed values, transition meaning, terminal states and unknown-code handling.
7. Define timestamps through represented event, timezone, precision, update behaviour and reporting use.
8. Keep executable logic in the system that runs it and link descriptions to the current version.
9. Use examples and counterexamples for ambiguous, temporal or state-dependent rules.
10. State suitable and unsuitable uses explicitly.
11. Differentiate fields and metrics that share similar labels.
12. Use asset-specific templates instead of one universal description box.
13. Combine a concise narrative summary with structured metadata attributes.
14. Validate required fields, placeholders, references, units, timezones and ownership in delivery workflows.
15. Keep authoring responsibility close to the team that can maintain the meaning correctly.
16. Preserve provenance, language, version, approval state and effective time.
17. Treat detected or generated descriptions as proposals until an accountable process approves them.
18. Test descriptions with real analytical and operational decisions.
19. Measure metadata quality through correctness, clarity and decision fitness, not only field population.
20. Design descriptions so that people, catalogs, RAG systems and AI assistants receive the same boundaries.

## The next step: enrich technical metadata with business context

Clear descriptions establish what an individual table, field, metric, identifier or status means.

They do not yet connect every technical asset to broader enterprise concepts, processes, policies, domains and accountability structures.

A field can be well described and still remain isolated from the business term it implements. A KPI can be precise but disconnected from the objective it supports. A table can have a clear grain but no link to the process, product or domain that owns it.

The next part, **Enrich Technical Metadata with Business Context**, explains how harvested technical metadata and precise descriptions can be connected to business vocabulary, ownership, policies, processes, criticality and decision context without erasing their source provenance.
