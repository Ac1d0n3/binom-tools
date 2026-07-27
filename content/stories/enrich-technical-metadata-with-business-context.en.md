---
title: Enrich Technical Metadata with Business Context — Connect Structures to Terminology, Ownership, Rules and Real Business Use
description: A practical method for connecting harvested schemas and fields to business vocabulary, KPIs, data products, accountable roles, usage boundaries, policies, evidence and approval history.
category: Data Governance
tags:
  - metadata
  - metadata-enrichment
  - metadata-governance
  - data-catalog
  - business-glossary
  - data-products
  - data-stewardship
  - metadata-provenance
  - kpi-governance
  - data-quality
  - semantic-layer
  - active-metadata
  - ai-ready-metadata
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 6
seriesTitle: MetaData Deep Dive
hero: images/playbooks/enrich-technical-metadata-with-business-context-hero.png
publishedAt: 2026-06-25 10:00
---

## A scanned schema is not yet business metadata

Automatic harvesting can discover that a table named `fct_sales_order_line` exists. It can collect its columns, data types, constraints, lineage, refresh history and consumers. Profiling can show that `net_sales_amount` contains decimal values, `booking_date` behaves like a date and `currency_code` contains a small set of three-letter values.

These observations are useful, but they do not answer the questions that determine whether someone can use the data correctly:

- Does `net_sales_amount` represent ordered, invoiced or recognized revenue?
- Which discounts, taxes, cancellations and credit notes are included?
- Is `booking_date` the creation date, acceptance date or accounting date?
- Which business domain owns the meaning?
- Which Data Steward approves changes?
- Which KPI and Data Product use the field?
- Which uses are permitted, discouraged or explicitly prohibited?
- Which limitations are known?
- Was the definition supplied by a person, inferred by a model or imported from another system?
- Has the enrichment been reviewed and approved?

A technically complete catalog can therefore remain semantically weak.

> **Technical metadata becomes decision-ready only when structures are connected to vocabulary, accountability, business use, rules, evidence and approval.**

Enrichment is not the act of adding a friendly label to a column. It is the controlled construction of a trusted metadata profile around an asset.

## Enrichment connects several kinds of evidence

A reliable profile is built in layers. Each layer contributes a different kind of knowledge and carries a different authority.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/enrich-technical-metadata-with-business-context-img1-en.png"
        alt="Layered path from technical metadata through profiling, business vocabulary, ownership, usage and policy context to a trusted metadata profile"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Profiling and detection can produce evidence and suggestions. Business meaning, accountability and governed use require explicit ownership and approval.
    </figcaption>
</figure>

### Technical metadata

Technical metadata describes the implemented object:

- native name and identifier
- source system and environment
- asset type
- schema, table and column hierarchy
- data type and format
- nullability, keys and constraints
- lineage and transformation references
- refresh, runtime and usage observations

This layer proves what exists and how it behaves technically. It does not by itself establish business meaning.

### Profiling and detection

Profiling can add observed evidence:

- value patterns
- minimum, maximum and distributions
- null and distinct-value ratios
- representative examples
- likely identifiers
- likely dates, currencies or country codes
- possible sensitive-data categories
- similarity to already approved assets

These signals can accelerate enrichment. They must remain distinguishable from approved facts.

A field containing values such as `EUR`, `USD` and `GBP` is likely a currency code. That does not establish whether it represents document currency, local accounting currency or reporting currency. A name such as `customer_id` may resemble an approved glossary term, but the identifier may be local to one tenant and unsuitable as an enterprise customer key.

### Business vocabulary

Business vocabulary adds semantic interpretation:

- business name
- definition
- synonyms and abbreviations
- domain and subdomain
- related glossary terms
- calculation concept
- business events and states
- distinctions from similar concepts

The objective is not to replace the physical name. The objective is to connect the implemented asset to the vocabulary required to understand it.

### Ownership and accountability

A trusted profile identifies accountable roles:

- Business Owner for meaning and business acceptance
- Data Steward for definition quality and ongoing review
- Technical Owner for implementation and operation
- Data Product Owner for product-level decisions
- Policy Owner for specific governance requirements

One person may hold several roles in a small team. The responsibilities should still be explicit.

### Usage and policy context

Business meaning remains incomplete without use boundaries:

- intended analytical use
- prohibited or unsuitable use
- known limitations
- approved consumers
- consuming reports, semantic models and AI use cases
- applicable sensitivity, retention and access rules
- required quality controls
- certification or approval status

A field can be correctly defined and still be unsafe for a particular decision. For example, order intake may be suitable for operational sales reporting but unsuitable as recognized revenue.

## A trusted metadata profile needs more than a description

A narrative definition is central, but it should be supported by structured relationships and governed attributes.

For a business-relevant column, a practical profile can include:

```yaml
asset:
  id: warehouse.prod.fct_sales_order_line.net_sales_amount
  type: column
  technical_name: net_sales_amount
  native_type: decimal(18,2)

business_context:
  business_name: Net Sales Amount
  definition: >
    Net value of one accepted sales-order line after approved
    line-level discounts and before tax, shipping and later credit notes.
  domain: Sales
  data_product: Sales Performance
  synonyms:
    - net order value
    - net sales value

relationships:
  implements_term: glossary.net_revenue
  contributes_to_kpi:
    - kpi.monthly_net_revenue
  consumed_by:
    - report.sales_performance_monthly
  governed_by:
    - policy.confidential_commercial_data
  validated_by:
    - quality_rule.valid_reporting_currency

accountability:
  business_owner: role.sales_operations_owner
  steward: role.sales_data_steward
  technical_owner: team.commercial_data_platform

use_context:
  intended_use:
    - order-intake reporting
    - operational sales analysis
  prohibited_use:
    - recognized-revenue reporting
    - statutory tax reporting
  known_limitations:
    - later billing credit notes remain in the billing model

provenance:
  definition_source: steward_entry
  supplied_by: user:steward-184
  supplied_at: 2026-07-20T09:15:00Z
  approved_by: role.sales_data_owner
  approved_at: 2026-07-21T14:30:00Z
  approval_status: approved
  effective_version: 4
```

The specific field names can differ by platform. The design principles should not:

- technical identity remains stable;
- semantic links are explicit relationships;
- roles are represented separately from free text;
- intended and prohibited use are both captured;
- provenance and approval are first-class metadata;
- the current profile is versioned rather than silently overwritten.

## Connect assets, terms, KPIs and Data Products as a graph

Metadata enrichment becomes more useful when context is linked instead of copied into isolated text fields.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/enrich-technical-metadata-with-business-context-img2-en.png"
        alt="Metadata graph connecting the Net Revenue business term, Monthly Net Revenue KPI, Sales Performance Data Product, sales fact table, columns, owners, reports, policies and quality rules"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Explicit relationship types preserve the difference between a term, a KPI, a Data Product, an implemented table, contributing columns and the controls around them.
    </figcaption>
</figure>

Consider the chain:

```text
Business Term: Net Revenue
↕
KPI: Monthly Net Revenue
↕
Data Product: Sales Performance
↕
Table: fct_sales_order_line
↕
Columns: net_sales_amount, booking_date, currency_code
```

These objects are related, but they are not interchangeable.

The Business Term defines the concept. The KPI defines a governed calculation and reporting window. The Data Product defines a delivered capability with ownership and service expectations. The table implements part of that product. The columns contribute values and time or currency context.

Relationship types should make those distinctions visible:

```text
column implements business term
KPI calculated from columns
asset belongs to Data Product
Data Product consumed by report
asset governed by policy
KPI validated by quality rule
Data Product owned by accountable role
```

This graph supports several practical questions:

- Which physical fields implement the enterprise term `Net Revenue`?
- Which KPIs use `net_sales_amount`?
- Which reports are affected if the field definition changes?
- Who must approve a change?
- Which policy and quality rules apply?
- Does another Data Product use the same term with a different calculation?

Copying the same definition into every object makes these questions harder. A relationship can be traversed, governed and changed independently.

## Name matching is a suggestion, not approval

Automated matching is valuable for scale. It is also one of the easiest ways to create false semantic certainty.

A matcher may use:

- exact and fuzzy name similarity
- descriptions
- source-system context
- data profiles
- lineage neighbours
- existing approved mappings
- domain membership
- co-usage in reports
- embedding similarity

The result should be represented as a proposal with evidence:

```yaml
suggestion:
  proposed_relationship: implements_term
  target: glossary.net_revenue
  source: semantic_matcher_v3
  confidence: 0.87
  status: proposed
  evidence:
    - name similarity: net_sales_amount
    - profile: decimal monetary values
    - lineage neighbour: currency_code
    - similar approved asset: mart_sales.net_order_value
```

A confidence score is not an approval state.

High confidence can justify prioritization or automatic assignment to a review queue. It should not silently convert an inferred relationship into an approved enterprise mapping unless a narrowly defined governance rule explicitly permits that action.

## Profiling supports meaning but cannot supply it

Profiling is especially useful when technical names are poor or undocumented.

For example, a field named `AMT_17` may show:

```text
98.7% populated
range: -125,000.00 to 2,400,000.00
frequent values: 0.00, 49.99, 99.00
paired with: CURR_CD
used in: sales margin report
lineage from: order line pricing
```

This evidence strongly suggests a monetary business value. It still does not answer:

- gross or net?
- ordered, invoiced or recognized?
- which discount classes?
- which conversion date?
- which return or cancellation treatment?
- which permitted use?

Representative values can reveal anomalies and support review. They should be sampled, protected and labelled as examples rather than definitions. Sensitive values may require masking, tokenization or synthetic examples.

The correct rule is:

> **Machines can observe, compare and propose. Accountable people approve meaning and use.**

## The simplest viable enrichment implementation

A team does not need a complete enterprise knowledge graph before starting. A useful minimum can be implemented around one important Data Product.

### 1. Select a bounded scope

Choose one Data Product, domain or reporting process where ambiguity creates real cost. Include its key tables, fields, KPIs and consumers.

A bounded scope makes ownership, review capacity and success criteria visible.

### 2. Harvest the technical baseline

Collect stable asset identifiers, hierarchy, types, lineage and current consumers. Do not ask stewards to recreate facts that systems already expose.

### 3. Define a minimum business profile

Require a small set of fields for material assets:

```text
business name
business definition
domain
owner
steward
intended use
prohibited use
known limitations
approval status
```

Additional relationships can be added when relevant:

```text
glossary term
KPI
Data Product
policy
quality rule
report or consumer
```

### 4. Generate proposals with evidence

Use names, profiles, lineage and existing approved examples to suggest likely terms, classifications, owners and related assets.

Every suggestion should include:

```text
source
confidence
evidence
status
created time
```

### 5. Review by exception and business impact

Prioritize high-impact or ambiguous assets rather than forcing identical manual effort on every column.

Typical priority signals are:

- executive or regulatory use
- high consumer count
- sensitive data
- conflicting mappings
- frequent change
- weak descriptions
- failed quality checks
- use in AI or automated decisions

### 6. Approve and version

Record who approved the enrichment, when it became effective and which prior version it replaced. Rejected suggestions should remain available for audit and future matcher improvement.

### 7. Publish where users work

Make approved context visible in catalog search, Data Product pages, semantic modelling workflows, BI development, quality review and AI retrieval. Enrichment that only exists in a hidden governance screen has limited operational value.

## A practical Steward enrichment workflow

Stewardship should be designed as a repeatable workflow rather than an open-ended request to “document the data”.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/enrich-technical-metadata-with-business-context-img3-en.png"
        alt="Steward workflow from harvested asset through generated suggestions, domain assignment, definition, term and rule links, example review, approval and publishing"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Suggestions accelerate review when their source, confidence and status remain visible. Approval converts proposals into governed metadata without deleting rejected evidence.
    </figcaption>
</figure>

A practical sequence is:

```text
Harvest Asset
→ Generate Suggestions
→ Assign Domain
→ Select Steward
→ Add or Confirm Definition
→ Link Terms and Rules
→ Review Examples
→ Approve
→ Publish
```

### Harvest Asset

The workflow starts from a real technical object with a stable identity. Creating an unlinked documentation record first increases duplicate and matching risk.

### Generate Suggestions

Possible proposals include:

- likely Business Term
- likely sensitive-data category
- likely Business Owner or Steward
- similar approved asset
- likely Data Product
- likely quality rule

Each proposal requires source, confidence and status.

### Assign Domain and Steward

Domain assignment determines the review context. The selected Steward must have sufficient knowledge or a clear escalation route.

Routing by technical platform alone is usually insufficient. A warehouse administrator may know where a field is stored but not what the business concept means.

### Add or confirm the definition

The Steward can accept, edit or reject a proposed definition. The workflow should highlight unresolved fields such as missing use boundaries or ambiguous time semantics.

### Link terms and rules

Relationships should be selected deliberately. A term link, KPI contribution and policy assignment represent different claims and can require different approvers.

### Review examples

Examples help verify interpretation. They should display sampling source, masking status and observation time.

### Approve and publish

Approval records the responsible role and effective version. Publishing makes the approved result discoverable and usable by downstream systems.

## Bulk enrichment should reduce repetition, not accountability

Large environments require bulk operations. Without them, Stewards spend time repeating obvious context across hundreds of similar fields. Poorly governed bulk updates, however, can spread one mistake across an entire estate.

Useful bulk operations include:

- apply one domain to assets under a governed namespace;
- assign a Steward to a defined Data Product scope;
- propose one glossary mapping for columns with the same lineage pattern;
- add a common policy to confirmed sensitive fields;
- inherit intended-use text from a Data Product to its internal assets;
- reuse an approved definition template while requiring asset-specific exceptions;
- mark identical technical copies as references to one authoritative business definition.

A bulk action should show its exact scope before execution:

```text
selection rule
number of assets
environments affected
attributes to add or replace
conflicts
current approved values
required approver
rollback version
```

Bulk enrichment should not use a universal last-write-wins rule.

A safe pattern separates three actions:

```text
Propose in bulk
→ Review conflicts and exceptions
→ Approve selected changes
```

For low-risk attributes, an approved rule may permit automatic propagation. For semantic definitions, prohibited use, ownership or policy classification, review is normally more appropriate.

## Preserve provenance for every enrichment

A metadata value without provenance becomes difficult to trust when it conflicts with another value.

For each enrichment, record at least:

- value
- attribute type
- source system or workflow
- source object
- supplied by
- supplied time
- method: declared, imported, detected, inferred or derived
- confidence where relevant
- approval status
- approved by
- approval time
- effective interval or version
- reason for rejection or override

This allows the platform to distinguish:

```text
Imported source description
Detected PII proposal
Steward-edited definition
Enterprise mapping approved by Domain Owner
Policy inherited from Data Product
Local exception approved until a defined date
```

Do not flatten these into one anonymous current value too early.

The published view can present one effective value while retaining the evidence and decision history behind it.

## Resolve conflicts without destroying legitimate local meaning

Business language is rarely uniform across all systems and domains.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/enrich-technical-metadata-with-business-context-img4-en.png"
        alt="Conflict-resolution model comparing Customer, Debtor and Account labels using local meaning, enterprise vocabulary, domain context, approved mappings and Steward decisions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Enterprise vocabulary should connect local meanings, not erase valid distinctions. The correct result may be a mapping, synonym, retained local label or unresolved conflict.
    </figcaption>
</figure>

Assume three systems use different labels:

```text
CRM: Customer
ERP: Debtor
Sales App: Account
```

A naive normalization process may replace all three with `Customer`. That can be wrong.

In the CRM, `Customer` may represent a relationship-managed commercial party. In the ERP, `Debtor` may represent the legal entity responsible for receivables. In the Sales App, `Account` may include prospects that have never purchased.

The resolver should evaluate:

```text
Local Source Meaning
+ Enterprise Vocabulary
+ Domain Context
+ Approved Mapping
+ Steward Decision
```

Possible outcomes are:

### Local label retained

Use this when the local concept is valid and more precise in its domain.

### Mapped to an enterprise term

Use this when the local concept is semantically equivalent within the defined scope.

### Synonym added

Use this for alternative language that helps search without claiming full equivalence.

### Conflict unresolved

Use this when available evidence is insufficient or the concepts overlap without being identical.

An unresolved conflict is preferable to a misleading universal definition.

Mappings should be scope-aware:

```yaml
mapping:
  local_term: ERP.Debtor
  enterprise_term: Party.ResponsibleForReceivable
  relationship: narrower_than
  scope: Finance.AccountsReceivable
  status: approved
```

This is more precise than storing `Debtor = Customer` as a global synonym.

## Alternative enrichment patterns

Different operating models can implement the same principles.

### Central Steward workflow

A central governance team manages definitions and mappings in one platform.

This can provide consistency, but it may become a bottleneck and lose source-specific knowledge. It works best for enterprise terms, cross-domain KPIs and policy decisions rather than every local field.

### Domain-owned enrichment

Domain teams enrich their own Data Products using shared templates and vocabulary services.

This keeps accountability close to the business context. It requires common identity, relationship types, approval states and quality controls so that metadata remains interoperable.

### Metadata-as-code

Definitions, mappings and ownership can be maintained in version-controlled files close to transformation or semantic code.

This supports review, change history and deployment workflows. It still needs business participation and a mechanism for publishing relationships to the broader metadata environment.

### Workflow-driven catalog enrichment

Harvested assets enter queues based on priority, risk and missing metadata. Stewards review proposals in a user interface and approved results are published centrally.

This pattern is accessible to non-technical roles but must retain versioning, provenance and exportability.

### Hybrid pattern

A common model is:

```text
Source-native context near the source
+ metadata-as-code for transformation meaning
+ catalog workflow for cross-system relationships
+ governance platform for enterprise vocabulary and approvals
```

The important design question is not where every value is displayed. It is where the value is authored, approved and kept current.

## Common anti-patterns

### Friendly-name-only enrichment

Renaming `CUST_ID` to `Customer ID` improves readability but does not define scope, stability or business role.

### Automatic glossary approval by name match

A similar label is evidence, not proof of equivalence.

### One description copied to every layer

A source field, transformed column, semantic measure and KPI can be related without having identical definitions.

### Profiling presented as business truth

Observed values cannot establish intended meaning, lawful use or accountable ownership.

### Owner as an unvalidated email address

Ownership should reference an active role or identity with lifecycle handling, not an unmanaged string.

### Missing prohibited use

Only documenting intended use leaves users without explicit boundaries.

### Bulk overwrite without conflict detection

Large updates require preview, exception handling, approval and rollback.

### Approved value without evidence history

Removing proposals, prior versions and rejection reasons makes later disputes harder to resolve.

### Enterprise vocabulary that erases domains

Universal definitions can become vague or wrong when legitimate local distinctions are collapsed.

### Enrichment detached from consumers

Context that is not available in search, BI development, semantic modelling, quality review or AI retrieval will not influence daily decisions.

## Decision guidance

Use the following questions when designing an enrichment process.

### Scope and priority

1. Which Data Products, KPIs or decisions carry the highest semantic risk?
2. Which assets have the most consumers?
3. Which objects contain sensitive or regulated data?
4. Which definitions are currently conflicting or missing?

### Authority

5. Who knows the local source meaning?
6. Who owns the enterprise term?
7. Who approves intended and prohibited use?
8. Which role resolves cross-domain conflict?

### Automation

9. Which evidence can be harvested or profiled?
10. Which relationships can only be proposed?
11. Which low-risk attributes can be propagated by an approved rule?
12. Which changes always require human review?

### Provenance and lifecycle

13. Can every value be traced to its source and supplier?
14. Are detected, proposed and approved states separated?
15. Are approvals versioned and effective-dated?
16. Can rejected suggestions and prior values be audited?

### Consumption

17. Where do users search for data?
18. Where do engineers implement transformations?
19. Where are KPIs and reports defined?
20. How will approved context reach AI and RAG systems without exposing unapproved proposals as fact?

The answers determine whether enrichment is a documentation exercise or an operational governance capability.

## Key recommendations

1. Start from harvested technical assets with stable identifiers.
2. Treat enrichment as controlled relationships and governed attributes, not friendly labels alone.
3. Add business names, definitions, synonyms, domains, Data Products, Owners and Stewards where they materially improve decisions.
4. Separate technical ownership, business ownership, stewardship and Data Product accountability.
5. Connect fields and tables to Business Terms and KPIs with explicit relationship types.
6. Do not treat name similarity or embedding similarity as semantic approval.
7. Use profiling, lineage, examples and consumer context as evidence for proposals.
8. Keep detected, inferred, proposed, approved and rejected states distinct.
9. Record source, supplier, time, method, confidence and approval for each enrichment.
10. Capture intended use, prohibited use and known limitations together.
11. Prefer role-based ownership references with lifecycle management over free-text contacts.
12. Design Steward queues around risk, impact and ambiguity rather than asset count alone.
13. Support bulk proposal and bulk review, but avoid unqualified bulk approval.
14. Preview scope, conflicts and replacements before every bulk change.
15. Preserve rejected suggestions so matching can improve without losing auditability.
16. Use scope-aware mappings for local and enterprise terminology.
17. Keep legitimate domain differences when a universal definition would mislead.
18. Publish approved context into the tools and workflows where data is selected, transformed, analysed and consumed.
19. Version semantic changes and connect them to impact analysis.
20. Begin with one valuable Data Product and prove the complete path from harvested structure to approved, consumed business context.

## The next step: build a unified metadata model

Enrichment adds the business context that technical structures lack.

The result, however, is distributed across many object types and relationships:

```text
systems
assets
columns
terms
KPIs
Data Products
people and roles
policies
quality rules
reports
suggestions
approvals
versions
```

Without a coherent model, each connector or workflow can represent these objects differently. Identity becomes unstable, relationships become ambiguous and provenance is difficult to query consistently.

The next part, **Build a Unified Metadata Model**, focuses on the shared entities, identifiers, relationship types, provenance structures, versioning rules and extension patterns needed to connect this context without flattening it into one oversized record.
