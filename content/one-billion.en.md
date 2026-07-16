---
title: Why Process 10 Billion Rows for Every Question?
description: Why a data platform should preserve complete history and detail while applications and decision products process and load only the data a specific decision actually requires.
category: Data Architecture
tags:
  - data-architecture
  - business-intelligence
  - decision-data
  - decision-products
  - data-modeling
  - semantic-layer
  - performance
  - cost-efficiency
  - data-governance
  - analytics
order: -1
author: Thomas Lindackers
hero: images/playbooks/one-billion-hero.png
publishedAt: 2026-07-16 14:00
---

## More data does not automatically create more decision value

Modern data platforms can store billions of transactions, events and historical states. That capability is valuable. It enables detailed analysis, long-term comparison, regulatory evidence, forecasting, machine learning and traceable reconstruction of business activity.

The problem does not begin with the size of the platform.

It begins when **every question is treated as if it must process the complete platform again**.

A CEO dashboard may need twelve critical KPIs and a small set of material deviations. An operational application may need only the orders, cases or risks that require action today. Finance needs a different subset from Sales, Supply Chain or Compliance.

Yet many architectures gradually follow the same pattern:

- as much data as possible is loaded into a universal model
- the model is transferred into an increasingly large application
- every role receives the same dimensions, history and detail tables
- every new question expands both the model and the application
- performance problems are addressed primarily with more compute

The result can be technically impressive while remaining weak as a decision system.

> **The data platform must handle Big Data. The decision layer must turn it into Decision Data.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/one-billion-img1-en.png"
        alt="Ten billion rows flow through one giant data model and one giant application to several roles, producing a result that is slow, complex, expensive and unclear"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        When complete history, all details and every possible question are forced into the same application, technical load grows faster than decision value.
    </figcaption>
</figure>

## The number is an illustration — not the complete cost formula

“10 billion rows” is intentionally a striking example.

In a modern columnar platform, the number of stored rows alone does not determine runtime or cost. Important factors include:

- bytes and columns actually read
- partitioning and pruning
- filter pushdown
- join complexity and cardinality
- level of aggregation
- materialization and caching
- concurrency and number of simultaneous users
- data movement between platform, gateway and application
- refresh frequency and incremental processing

A well-designed platform may query billions of rows efficiently when only relevant partitions and columns are read.

The central question still remains:

> **Why should the same broad processing be required again for every recurring decision?**

Processing ten billion rows for fifty similar dashboards or reloads does not automatically create fifty times more insight. It first creates fifty times more technical activity.

Architecture should therefore ask more than whether a query is technically possible. It should ask **which subset is necessary for the specific decision process**.

## Big Data remains necessary

Decision Data does not mean deleting historical or detailed data.

The complete data foundation remains important for:

- auditability and evidence
- root-cause analysis and drill-down
- data science and machine learning
- forecasting and simulation
- new questions that are not yet known
- regulatory retention
- reconstruction of business events
- model training and feature engineering
- cross-domain analysis

The platform must support these requirements.

But not every role needs the same level of detail at the same time.

A data scientist may require years of detailed events. An operations manager may need only today’s exceptions. A CEO may need a small number of aggregated indicators, their drivers and the most important risks.

That is not a contradiction. These are different **consumption contracts** on the same governed foundation.

## Three layers with three different responsibilities

The architecture becomes clearer when three layers are separated.

### 1. Enterprise data platform

It preserves breadth, history and detail.

Typical content includes:

- transactions
- events
- document metadata
- external data
- historical states
- technical audit and lineage information
- raw and harmonized data

This layer can be large. Its purpose is reuse and traceability.

### 2. Shared governed facts and dimensions

This is where business consistency is created.

The layer defines:

- conformed dimensions
- business facts with an explicit grain
- harmonized keys
- semantic definitions
- certified metrics
- data-quality rules
- sensitivity and access metadata
- lineage and ownership

Stored data becomes a reliable enterprise model here.

### 3. Role-based decision products

They answer concrete questions for concrete processes.

Examples include:

- CEO daily view
- finance performance
- sales steering
- supply-chain risks
- customer actions
- compliance alerts

A decision product is not an arbitrary export. It is a business-owned and technically controlled subset with a defined purpose.

```flow linear vertical
Enterprise data platform
Shared governed facts and dimensions
Department and domain models
Role-based decision products [active]
```

## From Enterprise Data to Decision Data

The solution is not to create many disconnected shadow models.

It is to **reuse one shared governed foundation in several deliberate ways**.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/one-billion-img2-en.png"
        alt="An enterprise data platform is transformed through shared governed facts and dimensions and department and domain models into role-based decision products"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The complete data foundation remains available. Content, detail, freshness and access are narrowed only at the consumption layer for a specific decision.
    </figcaption>
</figure>

The reduction is controlled:

- **business-focused**, by selecting only relevant processes and metrics
- **time-focused**, by loading only the necessary period
- **organizational**, by including only relevant domains and responsibilities
- **technical**, by deliberately materializing columns, rows and aggregates
- **security-focused**, by exposing only permitted data
- **visual**, by presenting only information that supports a decision

A decision product does not reduce the truth. It reduces the amount of truth that must be processed and presented simultaneously for one purpose.

## A CEO does not need the complete fact table

Assume a platform contains ten billion transactions.

The daily CEO view may still require only:

- twelve critical KPIs
- the largest plan-versus-actual deviations
- a small number of critical business units
- relevant risks and exceptions
- the most important drivers
- a link or drill-through for explanation
- clearly assigned accountability
- a potential next decision

The application does not need to load every transaction row to provide this.

It may need only:

- a certified period snapshot
- precomputed metrics
- a small exception table
- aggregated drivers
- reference keys for controlled drill-down

The full details remain available in the platform. They are read only when a specific deviation is investigated.

> **Overview first. Relevant drill-down second. Complete detail only when analysis actually requires it.**

## Progressive detail instead of maximum initial load

Many applications treat maximum detail as a quality attribute. Detail is valuable only when it is available at the right moment.

A useful progression can look like this:

### Stage 1 — overview

The application shows:

- a small number of KPIs
- status and trend
- deviations
- risks
- required decisions

### Stage 2 — business drill-down

After selecting a deviation, the application loads:

- affected region
- business unit
- product group
- customer segment
- process stage
- responsible organization

### Stage 3 — operational investigation

Only now, where required, it displays:

- specific orders
- invoices
- shipments
- events
- accounting entries
- individual cases

### Stage 4 — source evidence

For audit or defect analysis, the path to the source record remains traceable.

This architecture combines clarity with analytical depth. It prevents the first screen from becoming an executive dashboard, analysis environment, audit report and raw-data browser at the same time.

## The filter is a business contract

A decision-specific subset should not be created through accidental technical filters alone.

It should document:

| Contract element | Guiding question |
| --- | --- |
| **Purpose** | Which decision or action does the product support? |
| **Target role** | Who uses it and what responsibility does that role have? |
| **Metrics** | Which definitions are mandatory and certified? |
| **Grain** | What does one row or event represent? |
| **Time horizon** | How much history is required in the initial view? |
| **Freshness** | Must the data be realtime, hourly, daily or period-based? |
| **Exceptions** | Which thresholds and deviations are highlighted? |
| **Access** | Which rows, columns and detail levels are permitted? |
| **Drill-down** | How does the user reach an explanation in a controlled way? |
| **Owner** | Who owns definition, quality and further development? |

This prevents a small application from becoming an isolated data mart. It remains a controlled perspective on the shared model.

## Technical patterns for Decision Data

There is no single technical implementation. Different patterns can be combined depending on platform and workload.

### Governed views

Views encapsulate filters, joins and business logic without immediately creating additional physical copies.

They fit when:

- query performance is sufficient
- freshness is important
- logic should remain centralized
- several consumers use the same subset

### Materialized views and aggregate tables

Recurring compute-intensive queries can be prepared in advance.

They are suitable for:

- daily and management KPIs
- frequently used period aggregates
- defined hierarchies
- large join and aggregation steps
- stable reporting cycles

### Application-specific extracts

An extract can be appropriate when a BI application needs a local, fast and controlled dataset.

The important conditions are:

- KPI logic remains governed.
- The extract has a defined purpose.
- Refresh and retention are documented.
- The extract does not become an independent shadow truth.

### Semantic models and metric layers

They provide shared dimensions, measures and filter behaviour.

Several small decision products can reuse the same business definition without implementing the logic separately in every application.

### Incremental processing

Not every refresh must recalculate the complete history.

Possible strategies include:

- processing new or changed partitions
- calculating snapshot deltas
- updating open cases
- freezing closed periods
- selectively reprocessing late-arriving changes

### Event-driven exceptions

For operational decisions, delivering only relevant events or threshold breaches can be more efficient than repeatedly scanning the complete fact table.

## Performance is more than speed

A focused decision product improves more than load time.

### Lower technical load

Less data read, narrower joins and smaller transfers reduce compute and infrastructure demand.

### Better concurrency

When many users consume small purpose-built datasets, they compete less aggressively for the same large workloads.

### More stable refreshes

Small incremental models can be updated more frequently and predictably than a universal full reload.

### Clearer defect analysis

A defined data contract makes it easier to determine whether an issue originated in the source, transformation, metric or application.

### Easier testing

A decision product can be tested specifically for completeness, uniqueness, thresholds and business expectations.

### Better user guidance

Fewer irrelevant fields and filters reduce interpretation effort and misuse.

Performance is therefore also a governance and user-experience concern.

## Governance becomes more precise with smaller products

A universal model often supports so many possible uses that ownership and quality expectations become vague.

A decision product can explicitly define:

- business owner
- data owner
- data steward
- technical responsibility
- mandatory KPI definitions
- permitted audiences
- data classification
- quality SLAs
- refresh cycle
- retention
- certification status
- dependencies and lineage

This does not mean each decision product gets its own definitions.

The opposite is true:

> **Definitions are shared. Accountability for the concrete use is made precise.**

The enterprise layer provides shared semantics. The decision product documents how those semantics are used in one process.

## Cost control without an artificial austerity architecture

This story does not argue that every query should be made as cheap as possible or that analytical freedom should be restricted.

Exploration requires breadth. Data science requires detail. New questions require flexible access.

Cost control therefore comes from **workload differentiation**:

- production dashboards use stable and focused datasets
- exploratory analysis may read broader areas
- data-science workloads receive detailed and reproducible data
- rare historical analysis is not operated like an operational realtime process
- management snapshots do not need to be recalculated every second
- critical events are selectively accelerated

Compute is then used where it produces decision value.

## What to avoid

### Copying the complete model into every application

The central platform can be large. Every application does not need to be.

### Aggregates without traceable origin

A small metric table is trustworthy only when definition, period, filters and lineage are clear.

### Shadow logic in the consumption layer

Filtering must not cause revenue, margin or customer to be defined differently in every application.

### Removing all detailed data

Without controlled drill-down, metrics lose explainability.

### Materializing every possible question

Too many specialist tables create a new form of complexity. Materialization should follow recurring and valuable workloads.

### Solving performance only with larger compute

More compute can reduce symptoms, but it does not replace a clear grain, effective partitioning or a defined consumption contract.

### Treating realtime as the default

Realtime is useful when latency changes the action. For many management and reporting processes, consistency and reproducibility are more important.

## A practical implementation path

### Step 1 — describe the decision

Do not begin with tables or visualizations.

Document:

- recurring question
- possible decision
- expected action
- responsible role
- required evidence
- acceptable freshness

### Step 2 — define the minimum necessary subset

Which metrics, dimensions, exceptions and detail levels are genuinely required?

“Minimum” does not mean insufficient. It means **complete for the purpose**.

### Step 3 — reuse shared semantics

Dimensions, KPI formulas, calendar logic, currencies, security and quality rules come from the governed core.

### Step 4 — select the appropriate technical access pattern

Depending on the workload:

- view
- materialized view
- aggregate table
- semantic model
- incremental dataset
- event stream
- application extract

### Step 5 — preserve drill-down and evidence

Every aggregation needs stable references and lineage to the layer below it.

### Step 6 — measure load and usage

Useful measures include:

- runtime
- data volume read
- refresh duration
- concurrency
- use of fields and views
- frequency of drill-down
- obsolete or unused products

### Step 7 — optimize or retire deliberately

A permanently unused decision product is not a governance achievement. It is technical debt.

## The real architecture question

The question is not:

> *How do we put ten billion rows into every application?*

It is:

> **How do we preserve ten billion rows in a governed and traceable way — while delivering exactly the required subset to each decision?**

A good data platform keeps all required data available.

A good decision architecture prevents every user from processing all of it for every question.

> **The platform preserves the detail.  
> The shared model preserves the meaning.  
> The decision product delivers the focus.**
