---
title: One App Cannot Answer Every Question
description: Why a shared governed data foundation should become large and reusable at its core — while realtime, operational, departmental, management and CEO applications remain deliberately small and focused.
category: Data Architecture
tags:
  - business-intelligence
  - data-modeling
  - data-architecture
  - analytics
  - reporting
  - decision-apps
  - semantic-layer
  - data-governance
  - realtime
  - kpi-governance
order: -1
author: Thomas Lindackers
hero: images/playbooks/one-app-hero.png
---

## One universal app is not the same as one source of truth

Many BI initiatives begin with an attractive promise:

> **One application should answer every question for every user.**

The intention is understandable. One app appears easier to distribute, govern and maintain than many separate solutions. Every department should use the same data, every KPI should be available, every historical detail should be accessible and every new question should be answered without another development cycle.

Over time, however, the application often becomes a container for everything:

- operational detail and strategic KPIs
- current actions and ten years of history
- finance, sales, operations, HR and supply-chain data
- daily steering and annual planning
- standard reporting and exploratory analytics
- realtime signals and slowly changing reference data
- thousands of dimensions, measures, filters and visualizations

The result may technically contain the answer — but users can no longer find it.

A **single source of truth** does not require a **single universal application**. It requires shared definitions, compatible data models, common dimensions, controlled business logic and traceable ownership. Different decisions can then consume different, focused subsets of the same governed foundation.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/one-app-img1-en.png"
        alt="The problem with one universal BI application for every role and every question"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        When one application is expected to serve every role, every timeframe and every analytical depth, it becomes a data container instead of a decision tool.
    </figcaption>
</figure>

## The central distinction: data model versus decision application

A large enterprise data model can be valuable.

A large end-user application is not automatically valuable.

The two serve different purposes:

| Layer | Primary purpose |
| --- | --- |
| **Enterprise data foundation** | Preserve detail, harmonize domains, standardize keys, definitions and history |
| **Semantic and metric layer** | Provide trusted dimensions, measures, hierarchies and business meaning |
| **Decision application** | Answer a defined set of questions for a defined role and process |
| **Analytics and AI access** | Allow deeper exploration, forecasting, feature engineering and experimentation |

The enterprise foundation should be broad enough to support many use cases. The consumption layer should be narrow enough to guide a concrete decision.

That leads to a simple architectural principle:

> **Small at the source domain. Large and reusable at the core. Filtered and focused at the point of decision.**

## Small → large → focused

The target architecture is not “many isolated apps” and it is not “one app containing everything”.

It is a controlled flow:

```flow linear vertical
Focused departmental facts
Shared governed enterprise model
Decision-specific filters and aggregates
Purpose-built applications
```

### 1. Start with focused departmental facts

Each department should first model its own business processes with a clear grain.

Examples:

- Sales: one row per order line
- Finance: one row per accounting entry or invoice line
- Operations: one row per production event
- Supply chain: one row per shipment or inventory snapshot
- Customer service: one row per case or interaction
- HR: one row per workforce event or periodic snapshot

The grain must be explicit. Without it, measures are easily duplicated, joined incorrectly or interpreted inconsistently.

A departmental model should not be designed as an isolated island. It should align with enterprise conventions from the beginning:

- common date and time keys
- common organizational structures
- common customer, product, location and partner identifiers
- standardized currency and unit handling
- shared scenario definitions such as Actual, Budget and Forecast
- consistent source-system and lineage metadata
- common data-quality and sensitivity classifications

This makes the models **composable**.

### 2. Build a large enterprise-wide fact landscape

The goal is not necessarily to force every departmental process into one physical table.

Orders, accounting entries, inventory snapshots and support cases often have different grains. Combining incompatible grains into one giant table creates duplicated measures, sparse columns and ambiguous semantics.

The better objective is:

> **Make departmental facts compatible enough to be consumed together.**

There are two valid patterns.

#### Pattern A — one enterprise event fact

Facts with a compatible event-oriented grain can be standardized and combined into one logical or physical enterprise event fact.

A simplified structure could include:

```text
event_id
event_type
event_timestamp
department_key
company_key
customer_key
product_key
location_key
employee_key
scenario_key
currency_key
quantity
amount
cost
margin
status
source_system
source_record_id
```

Sales, logistics and service events can then be combined using a standardized event type and shared keys. The original departmental facts may still remain available for specialist detail.

#### Pattern B — a fact constellation with conformed dimensions

When grains differ, the facts remain separate:

```text
fact_order_line
fact_invoice_line
fact_inventory_snapshot
fact_production_event
fact_service_case
fact_workforce_snapshot
```

They are connected through shared dimensions and governed metrics:

```text
dim_date
dim_company
dim_department
dim_customer
dim_product
dim_location
dim_employee
dim_scenario
dim_currency
```

From a consumer perspective, this can still behave like one enterprise-wide model. The unification happens through a semantic layer, governed views, metric definitions or purpose-built data products rather than through one physically enormous table.

The important result is the same:

- departments keep a clear process-specific grain
- enterprise questions can cross departmental boundaries
- KPIs use consistent definitions
- the CEO view and management reporting do not need separate shadow logic
- Python, BI and AI workloads can use the same governed foundation

## The large model is a foundation — not the user interface

A broad enterprise model can contain:

- detailed history
- many business entities
- hundreds or thousands of attributes
- transactional and aggregated facts
- features for forecasting
- technical lineage and audit fields
- security and classification metadata

That may be appropriate for advanced analytics and reuse.

It does not mean that every field belongs in every application.

Five thousand dimensions and fifty thousand columns do not create self-service. They transfer the work of modelling, filtering and interpretation from the data team to the end user.

A user should not have to search through the complete enterprise schema to answer:

- Which orders need attention today?
- Which customers are at risk?
- Which locations missed the monthly target?
- Which five KPIs require an executive decision?
- Which exceptions require immediate action?

The data foundation should preserve complexity. The application should reduce it.

## Filter for the decision

The consumption layer selects only the data required for a role, decision and time horizon.

Typical mechanisms include:

- governed SQL views
- materialized views
- aggregate tables
- semantic models
- metric layers
- application-specific extracts
- row- and column-level security
- incremental datasets
- event streams for selected signals

The filter is not merely a technical `WHERE` clause. It represents a business contract:

- Which questions does this application answer?
- Which records are relevant?
- Which measures are trusted?
- Which context is required?
- How fresh must the data be?
- Which actions should the user take?
- Which details must remain accessible for explanation?

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/one-app-img2-en.png"
        alt="One governed enterprise data model filtered into realtime, daily business, departmental, management, CEO and analytics applications"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The governed model can be large and reusable. Each application receives only the subset, latency and level of detail required for its decision process.
    </figcaption>
</figure>

## Six focused consumption patterns

### 1. Realtime KPI app

A realtime application should contain only the signals that genuinely require minimum latency.

Typical content:

- five or ten critical KPIs
- current status
- threshold breaches
- direction of change
- last update timestamp
- clear alert or escalation state

It should not load the full enterprise model in realtime merely because the platform can.

Suitable examples include:

- production interruption
- fraud signal
- system availability
- critical inventory shortage
- current service-level breach

The relevant principle is:

> **As fast as necessary — not as fast as technically possible.**

### 2. Daily business app

The daily business app answers:

> **What requires action today?**

It should focus on:

- exceptions
- overdue tasks
- critical customers or orders
- cases requiring approval
- missing information
- recommended next action
- responsible owner
- operational detail required to complete the task

It is an action queue, not a historical data explorer.

A daily app may use only a few hundred or a few thousand records even when the underlying platform contains billions.

### 3. Department apps

Department applications support recurring domain-specific decisions:

- sales steering
- finance control
- production performance
- supply-chain execution
- workforce planning
- service management

They can contain more detail than a CEO or management view, but should still remain focused on the department’s real processes.

The department app should reuse:

- shared dimensions
- governed KPI definitions
- common security rules
- certified data products
- consistent period and currency logic

It should not create its own version of revenue, margin, customer or product unless a documented business distinction exists.

### 4. Management app

Month, quarter and year reviews usually need:

- aggregated KPIs
- plan-versus-actual comparisons
- trends
- variance drivers
- organizational and product hierarchies
- controlled drill-down
- a stable and reconcilable reporting state

Realtime is rarely the main requirement. Consistency, completeness and explainability are more important.

A management app should represent a reviewed reporting cycle rather than a permanent stream of changing numbers.

### 5. CEO perspective

The CEO does not need every departmental detail.

The executive view should answer a small number of enterprise questions, for example:

- Are revenue, margin, cash and service developing as expected?
- Which strategic risks require intervention?
- Where are the largest deviations?
- Which business units require attention?
- Which assumptions changed?
- What decision is required now?

The CEO perspective can be generated from the large enterprise model, but it should load only the relevant metrics, drivers and exceptions.

A small executive view is not a reduction in analytical capability. It is deliberate prioritization.

### 6. Analytics and AI model

Advanced analytics needs the broadest access:

- detailed history
- many attributes
- training features
- event sequences
- external data
- forecast targets
- reproducible datasets
- access from Python, notebooks, SQL and BI tools

This is where the large model creates substantial value.

But the analytics model is not automatically an end-user application. Data scientists and analysts need exploration freedom; operational users need guidance and actionability.

Both can use the same governed foundation without receiving the same interface.

## Freshness is a design dimension

Different use cases need different latency.

| Use case | Typical freshness target |
| --- | --- |
| Critical alert or machine event | seconds to minutes |
| Operational queue | minutes to hourly |
| Daily business steering | hourly to daily |
| Department performance | several times per day or daily |
| Management reporting | daily, period close or certified snapshot |
| CEO perspective | aligned with the management cycle, plus selected critical alerts |
| Forecasting and model training | workload-specific, often batch or incremental |

Trying to make everything realtime increases cost, complexity and operational risk without necessarily improving decisions.

Realtime should be applied selectively to the values where latency changes the action.

## Shared logic, separate experiences

Purpose-built apps must not become isolated silos.

The separation belongs in the **experience and consumption layer**, not in the definitions.

The following should remain shared:

- customer, product and organizational dimensions
- KPI formulas
- currency and unit conversion
- calendar and fiscal logic
- data-quality rules
- classifications and sensitivity metadata
- access policies
- lineage
- ownership
- certification status

This creates one governed truth with several fit-for-purpose perspectives.

```text
One definition of revenue
One definition of margin
One customer identity
One fiscal calendar
One security model
Many focused applications
```

## Example: from departmental processes to an executive view

Consider an order-to-cash process.

Departmental facts may include:

```text
Sales          → order lines
Operations     → production events
Supply Chain   → shipments
Finance        → invoices and payments
Service        → complaints and returns
```

They remain process-specific, but share:

```text
customer
product
company
department
location
date
currency
order reference
business event
```

The enterprise model can then answer cross-domain questions:

- Which delayed orders create revenue risk?
- Which production problems affect strategic customers?
- Which shipped orders remain unpaid?
- Which product issues increase returns and service cost?
- Which customer segments create margin but poor cash conversion?

The CEO app receives only a few aggregated indicators and major exceptions.

The daily operations app receives only today’s affected orders and required actions.

Finance receives invoice, payment and cash detail.

Python receives the full event history for forecasting late payment or delivery risk.

The shared model is large. Every consumption layer remains focused.

## What to avoid

### One physical mega-fact at any cost

A single fact table is useful only when the grain and semantics are compatible. Forcing unrelated facts together creates ambiguity rather than integration.

### One application for every role

Different roles have different decisions, time horizons, permissions and levels of detail.

### Realtime for the complete platform

Only selected events and KPIs usually require minimum latency.

### KPI logic copied into every app

Business logic should be governed centrally and reused.

### Departmental models without enterprise alignment

Independent domain models create duplicate customers, incompatible calendars and conflicting metrics.

### Unlimited field exposure as “self-service”

Self-service requires understandable, curated and trusted data — not merely access to every column.

## A practical implementation path

### Step 1 — identify decisions, not dashboards

For each role, document:

- recurring questions
- decisions
- actions
- required evidence
- acceptable latency
- necessary detail

### Step 2 — define departmental grains

Describe exactly what one fact row represents and which measures are additive, semi-additive or non-additive.

### Step 3 — establish conformed dimensions

Standardize shared business entities, identifiers, hierarchies and time logic.

### Step 4 — govern KPI definitions

Define formulas, owners, allowed filters, aggregation behaviour and certification status.

### Step 5 — create the enterprise fact landscape

Use an enterprise event fact where grains align. Use a fact constellation where they do not.

### Step 6 — build decision-specific datasets

Create views, aggregates, extracts or semantic perspectives for each consumption pattern.

### Step 7 — assign freshness deliberately

Reserve realtime or streaming for signals where latency changes the decision.

### Step 8 — measure usage and retire clutter

Track which apps, fields and KPIs are used. Remove redundant views and obsolete logic instead of allowing the portfolio to grow indefinitely.

## The architectural principle

The objective is not to minimize the number of applications at any price.

The objective is to minimize duplicated data logic while maximizing decision clarity.

> **Build the data foundation once.  
> Make it broad enough for enterprise reuse.  
> Deliver only the data each decision actually needs.**

One governed model can support many questions.

One universal app usually cannot.
