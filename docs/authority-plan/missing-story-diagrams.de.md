# Fehlende Story-Schaubilder – ausführliche EN-Beschreibungen

Stand: 2026-07-29

Nur Stories mit fehlenden Inline-Schaubildern. Die Dateinamen und Reihenfolge entsprechen der Fehlstellenliste; unter jedem Bildpaar steht die ausführliche englische Schaubildbeschreibung aus dem vorhandenen Story-Brief.

Stories: 13 · fehlende Bild-Paare/Dateien: 45

---

## BigQuery as a Governance Starting Point

`bigquery-governance-start`

`bigquery-governance-start-img1-en.png`
`bigquery-governance-start-img1-de.png`

### BigQuery Governance Surfaces and Decision Owners

Create a layered model.

Cloud organization:
- organization and folders
- projects
- regions and network boundaries

Analytics platform:
- datasets
- tables and views
- routines and models
- jobs and consumers

Governance:
- Data Owner and Steward
- business metadata
- classification
- IAM and fine-grained access
- lineage
- quality evidence
- retention and cost accountability

Show clear decision and implementation owners.

---

`bigquery-governance-start-img2-en.png`
`bigquery-governance-start-img2-de.png`

### Identity, Dataset, Table and Column Control Boundaries

Create a control hierarchy.

```text
Identity and groups
→ project access
→ dataset access
→ table or view access
→ row-level rule
→ column policy or masking
→ effective-access test
→ audit evidence
```

For each level show:
- business purpose
- technical control owner
- approval
- exception expiry
- recertification

Show permitted use as separate from technical access.

---

`bigquery-governance-start-img3-en.png`
`bigquery-governance-start-img3-de.png`

### From Serverless Analytics to Governed Data Product

Create an evidence flow.

```text
Source
→ ingestion or federation
→ transformation
→ governed dataset
→ analytical product
→ BI, API or data-sharing consumer
```

Attach:
- owner and steward
- grain
- classification
- access
- lineage
- quality tests
- region and retention
- cost attribution
- change approval

Add failure cases:
- project sprawl
- unmanaged exports
- duplicated datasets
- unclear billing ownership

---

## dbt as a Cross-Platform Governance Control Layer

`dbt-governance-control-layer`

`dbt-governance-control-layer-img1-en.png`
`dbt-governance-control-layer-img1-de.png`

### What dbt Can Control — and What It Cannot Decide

Create two columns.

### `dbt can implement or evidence`
- source and model contracts
- tests and stored failures
- transformation lineage
- metadata fields
- documentation
- version control and pull requests
- deployment evidence
- freshness checks

### `dbt cannot own`
- business purpose
- Data Ownership
- permitted use
- final PII approval
- platform access enforcement
- retention and deletion decisions
- exception approval
- metric certification

Show the handoff between both columns.

---

`dbt-governance-control-layer-img2-en.png`
`dbt-governance-control-layer-img2-de.png`

### Transformation Contract and Evidence Flow

Create a lifecycle flow.

```text
Approved requirement
→ source declaration
→ model contract
→ transformation
→ tests
→ stored failure evidence
→ review and approval
→ deployment
→ catalog and consumer publication
→ change and recertification
```

Mandatory metadata:
- owner
- domain
- grain
- PII
- criticality
- quality tier
- allowed usage
- policy references

---

`dbt-governance-control-layer-img3-en.png`
`dbt-governance-control-layer-img3-de.png`

### Promote Metadata from YAML to Governance Workflow

Create three states.

### `Declared`
- developer-provided metadata
- source and model descriptions
- test configuration

### `Validated`
- automated checks
- catalog synchronization
- Steward review
- consistency rules

### `Approved`
- Data Owner decision
- policy linkage
- effective date
- version
- recertification trigger

Show that metadata promotion requires evidence and accountable approval.

---

## Which Dynamics 365 Tables to Load — and Which to Skip

`dynamics-365-tables-for-analytics`

`dynamics-365-tables-for-analytics-img1-en.png`
`dynamics-365-tables-for-analytics-img1-de.png`

### Which Dynamics 365 Tables to Load — and Which to Skip

Create an environment inventory with these categories:

1. `Core Business Tables`
2. `Process and Line-Item Tables`
3. `Reference, User and Team Tables`
4. `Activities and Activity Parties`
5. `Custom Tables and Solutions`
6. `Audit, Notes and Attachments`
7. `Platform, Configuration and Operational Tables`

Show that installed or populated tables are not automatically in scope.

Add decision dimensions:
- configured business purpose
- authoritative role
- row grain
- relationship and polymorphic reference behavior
- option and status semantics
- current versus history need
- PII and access
- support and extraction cost

---

`dynamics-365-tables-for-analytics-img2-en.png`
`dynamics-365-tables-for-analytics-img2-de.png`

### Classify the source structures by business role

Create three decision columns.

### `Required for the Selected Process`
Possible patterns:
- account or contact
- lead or opportunity
- opportunity product for line grain
- product and price references
- owner, user or team
- required date, currency and status references

### `Conditional`
- quote, sales order and invoice
- cases or service tables
- activities and activity parties
- selected audit or status history
- campaign or marketing tables
- custom tables and solution extensions

### `Skip or Separate Unless Required`
- unused application modules
- duplicate activity representations
- unrestricted annotations and attachments
- verbose audit data without a control case
- platform telemetry and configuration noise
- unmanaged convenience exports with unclear authority

Add a note that the actual app configuration overrides generic examples.

---

`dynamics-365-tables-for-analytics-img3-en.png`
`dynamics-365-tables-for-analytics-img3-de.png`

### Respect relationships and event grain

Create a relationship-centered process model:

```text
Lead
→ Opportunity
→ Quote
→ Sales Order
→ Invoice
```

Supporting relationships:
- account or contact
- opportunity product, quote line, order line or invoice line
- product and price list
- owner, team and business unit
- activities only when a defined metric needs them

For every relationship show:
- business event
- header or line grain
- key and cardinality
- state and status meaning
- currency and date semantics
- current versus historical meaning
- PII classification

Add failure cases:
- header and line amounts double counted
- activity parent and subtype duplicated
- inactive records silently removed
- option labels lost
- custom process tables omitted

---

`dynamics-365-tables-for-analytics-img4-en.png`
`dynamics-365-tables-for-analytics-img4-de.png`

### Document the source-scope decision

Create a governed artifact with one row per Dataverse table or relationship.

Mandatory fields:
- logical and display table name
- application or solution
- business purpose
- target data product
- target grain contribution
- required columns
- relationship, key and cardinality
- option, status and state semantics
- current, audit, event or snapshot need
- currency, time-zone and organization scope
- PII, notes and attachment risk
- deactivation, merge and deletion behavior
- freshness and retention
- business and technical owner
- decision: include, conditional, defer, exclude or separate product
- rationale and review trigger

Outputs:
- approved table and column scope
- relationship map
- option and status mapping contract
- explicit skip list
- reconciliation requirements
- ingestion handoff

Show that the Dataverse metadata catalog supports the decision but does not make it.

---

## Governance Across Multiple Data Platforms

`governance-across-multiple-data-platforms`

`governance-across-multiple-data-platforms-img1-en.png`
`governance-across-multiple-data-platforms-img1-de.png`

### Assign One Authority per Governance Concern

Create an authority matrix.

Governance concerns:
- business glossary
- Data Ownership
- technical catalog
- identity
- PII classification
- access policy
- retention
- lineage
- quality evidence
- semantic metrics
- incident coordination
- cost accountability

Columns:
- authoritative system or role
- enforcement point
- evidence location
- downstream subscribers
- exception owner

Show that multiple platforms may subscribe, but only one authority exists per concern.

---

`governance-across-multiple-data-platforms-img2-en.png`
`governance-across-multiple-data-platforms-img2-de.png`

### Cross-Platform Lineage and Evidence Handoffs

Create an end-to-end chain across multiple platforms.

```text
Source
→ ingestion platform
→ transformation layer
→ warehouse or lakehouse
→ semantic model
→ BI, API or AI consumer
```

At every handoff show:
- asset identifier
- owner
- classification
- lineage link
- quality status
- deployment version
- access policy reference
- incident route

Highlight broken handoffs and duplicated identifiers.

---

`governance-across-multiple-data-platforms-img3-en.png`
`governance-across-multiple-data-platforms-img3-de.png`

### Avoid Duplicate Catalogs, Policies and Semantic Logic

Create paired contrasts.

### `Uncontrolled coexistence`
- duplicate glossary terms
- conflicting classifications
- repeated masking logic
- copied transformations
- local metrics
- multiple incident queues
- unclear cost owner

### `Governed coexistence`
- one authority per concern
- synchronized metadata
- platform-specific enforcement
- shared contracts
- controlled semantic layer
- coordinated incident ownership
- consolidation trigger

---

## Building a Data Governance Center of Excellence

`governance-coe`

`governance-coe-img1-en.png`
`governance-coe-img1-de.png`

### The Governance CoE Mission and Boundary

Create three sections.

### `CoE Enables`
- standards and templates
- role onboarding
- decision frameworks
- reusable controls
- training and coaching
- measurement methods

### `CoE Coordinates`
- cross-domain conflicts
- enterprise vocabulary
- council decisions
- exceptions and escalation
- portfolio prioritization
- sponsor reporting

### `Domains and Platforms Execute`
- maintain definitions
- implement controls
- resolve quality issues
- operate data products
- provide evidence
- own business outcomes

Add a visible boundary:
- CoE does not own every dataset
- CoE does not approve every routine change
- CoE does not replace Data Owners or Stewards

---

`governance-coe-img2-en.png`
`governance-coe-img2-de.png`

### Central Core, Federated Execution

Place a central `Governance CoE` connected to:

- Domain A
  - Data Owner
  - Steward
  - Product or delivery team

- Domain B
  - Data Owner
  - Steward
  - Product or delivery team

- Domain C
  - Data Owner
  - Steward
  - Product or delivery team

Shared partners:
- Data Architecture
- Platform Operations
- Privacy and Security
- Risk and Compliance
- Executive Sponsor

Flows from the CoE:
- standards
- templates
- training
- common metrics
- escalation support

Flows back:
- decisions
- exceptions
- evidence
- adoption feedback
- unresolved conflicts

The visual must show federation, not a central team doing all work.

---

`governance-coe-img3-en.png`
`governance-coe-img3-de.png`

### Council Cadence and Escalation Paths

Create four operating layers:

1. `Operational Intake`
   - new issue
   - policy question
   - exception request
   - cross-domain conflict

2. `Triage`
   - local resolution
   - specialist review
   - council decision
   - sponsor escalation

3. `Governance Council`
   - decide enterprise topics
   - approve material exceptions
   - resolve ownership conflicts
   - prioritize shared capabilities

4. `Executive Sponsor`
   - unblock funding or authority
   - accept material enterprise risk
   - resolve unresolved strategic conflict

Show cadences as adaptable operating rhythms rather than rigid dates:
- continuous intake
- regular operational review
- scheduled council
- periodic sponsor review
- urgent escalation when thresholds are met

Each decision must produce:
- owner
- due date
- rationale
- evidence
- follow-up

---

`governance-coe-img4-en.png`
`governance-coe-img4-de.png`

### Report Outcomes, Not Governance Activity

Create four evidence groups.

1. `Coverage`
   - priority domains onboarded
   - critical assets governed
   - accountable roles assigned

2. `Decision Performance`
   - decision lead time
   - unresolved escalations
   - exception age
   - repeat conflicts

3. `Control Outcomes`
   - policy compliance
   - quality improvement
   - protection implemented
   - audit evidence complete

4. `Adoption and Value`
   - standards reused
   - manual effort reduced
   - trusted assets used
   - stakeholder confidence

Add an anti-vanity section:
- meetings held
- documents created
- fields completed
- training attendance

Show that these activity counts may support context but do not prove governance outcomes.

---

## Which HubSpot Tables to Load — and Which to Skip

`hubspot-tables-for-analytics`

`hubspot-tables-for-analytics-img1-en.png`
`hubspot-tables-for-analytics-img1-de.png`

### Which HubSpot Tables to Load — and Which to Skip

Show two contrasting paths.

### `Object-First`
```text
CRM Object Catalog
→ Export Objects and All Properties
→ Join by Convenience
→ Mixed Grain and PII
→ Conflicting Funnel Metrics
```

### `Decision-First`
```text
Revenue or Service Decision
→ Configured Process
→ Target Grain
→ Required Associations
→ Required Objects and Properties
→ Controlled Scope
```

Use example questions:
- pipeline by stage and owner
- conversion by lifecycle stage and source
- revenue by deal line item
- ticket resolution by team and category

Show that each question requires a different object, property and association scope.

---

`hubspot-tables-for-analytics-img2-en.png`
`hubspot-tables-for-analytics-img2-de.png`

### Classify the source structures by business role

Create three columns.

### `Core for the Selected Use Case`
Possible patterns:
- companies or contacts
- deals
- tickets
- owners or teams
- required pipeline, stage and date properties
- line items for line-grained revenue

### `Conditional`
- products
- campaigns and attribution-related objects
- activities such as calls, meetings or communications
- quotes, orders, invoices, subscriptions or payments when activated and required
- selected property history
- custom objects and association labels

### `Skip Unless a Requirement Exists`
- unused feature objects
- all-property exports
- duplicate convenience representations
- unrestricted notes or message content
- files and attachments
- technical or operational records without a named product

Add a note:
- examples are patterns, not a universal list
- portal configuration and custom business meaning override default labels

---

`hubspot-tables-for-analytics-img3-en.png`
`hubspot-tables-for-analytics-img3-de.png`

### Respect relationships and event grain

Create an association-centered model:

```text
Company ↔ Contact
   \       /
      Deal → Line Item → Product Reference
       |
     Ticket or Activity only when required
```

Supporting references:
- owner or team
- pipeline and stage
- source and campaign context
- relevant dates
- custom association labels

For every association show:
- analytical purpose
- cardinality and optionality
- association label
- target-grain effect
- ownership of duplicates
- current versus historical meaning
- PII classification

Add failure cases:
- contact-company many-to-many ambiguity
- deal duplicated by multiple contacts
- product and line-item grain mixed
- activity counts inflated through repeated associations
- custom objects ignored

---

`hubspot-tables-for-analytics-img4-en.png`
`hubspot-tables-for-analytics-img4-de.png`

### Document the source-scope decision

Create a governed artifact with one row per object, association or property group.

Mandatory fields:
- object or API resource
- business purpose
- target data product
- target grain contribution
- required property allowlist
- association path, label and cardinality
- current, event, property-history or snapshot need
- archived, merged and deleted-record behavior
- PII and free-text risk
- freshness
- retention
- owner
- decision: include, conditional, defer or exclude
- rationale
- review trigger

Outputs:
- approved object scope
- property allowlist
- approved association map
- explicit skip list
- history and deletion contract
- open questions and handoff to ingestion design

Show that an object being activated in HubSpot does not automatically place it in the analytics scope.

---

## Same Entity, Two Systems — Which Source Is Authoritative?

`multi-source-entity-authority`

`multi-source-entity-authority-img1-en.png`
`multi-source-entity-authority-img1-de.png`

### Same name does not mean same authority

Create four source cards for the same entity:

- `CRM` — relationship, sales owner, pipeline context
- `Billing or ERP` — legal account, contract, invoice and balance
- `Service Platform` — cases, service state and operational contact
- `Identity, Consent or Master Data` — identity, preferences or governed reference

Show overlapping attributes:
- name
- address
- email or phone
- legal identifier
- account status
- segment
- owner
- contract status

Add four authority questions:
- which system creates the fact?
- which system is allowed to correct it?
- which time context applies?
- which downstream decisions may use it?

Show that authority can differ per attribute and event.

---

`multi-source-entity-authority-img2-en.png`
`multi-source-entity-authority-img2-de.png`

### Assign authority by attribute, event and time

Create an authority matrix with rows such as:

- legal customer identity
- preferred contact details
- consent and communication preference
- sales ownership
- active contract status
- invoice balance
- service entitlement
- support-case state

Columns:
- source of entry
- authoritative source
- analytical trusted source
- effective date
- freshness expectation
- conflict rule
- fallback source
- owner

Add time-context examples:
- current operational state
- historically effective state
- event at transaction time
- corrected restatement

Show that `latest record wins` is valid only when explicitly approved for that attribute and context.

---

`multi-source-entity-authority-img3-en.png`
`multi-source-entity-authority-img3-de.png`

### Resolve overlap with match, survivorship and lineage

Create a governed flow:

```text
Source Records
→ Standardize Keys
→ Match and Identity Resolution
→ Confidence and Exception Gate
→ Attribute Survivorship
→ Conformed Entity or Source-Specific Views
→ Downstream Products
```

Separate three decisions visually:
1. `Match` — do records represent the same entity?
2. `Survive` — which value is trusted for each attribute and time?
3. `Publish` — should one conformed record or several contextual views be exposed?

Mandatory controls:
- crosswalk keys
- match confidence
- manual review threshold
- merge and split history
- source provenance
- effective dating
- conflict retention
- downstream impact analysis

Add failure cases:
- false positive merge
- duplicate entity left unresolved
- stale value overrides authoritative event
- source lineage lost
- correction not propagated

---

`multi-source-entity-authority-img4-en.png`
`multi-source-entity-authority-img4-de.png`

### Record the entity authority decision

Create a governed authority artifact with four linked sections.

### `Entity Definition`
- entity name and business meaning
- identity keys and matching scope
- included populations
- excluded or separate entity types

### `Authority Matrix`
- attribute or event
- source of entry
- authoritative source
- analytical trusted source
- precedence and fallback
- time and correction semantics

### `Exception Rules`
- match-confidence threshold
- unresolved conflict type
- steward queue and SLA
- escalation owner
- permitted temporary fallback

### `Downstream Contract`
- conformed key
- provenance fields
- freshness and quality controls
- affected products
- change and migration plan
- review trigger

Outputs:
- approved entity authority matrix
- crosswalk and match policy
- survivorship rules
- exception queue
- lineage requirement
- consumer migration actions

Show that formula or matching tools may implement the decision but cannot assign business authority.

---

## SaaS Exports: Tables You Should Not Load

`saas-exports-tables-to-skip`

`saas-exports-tables-to-skip-img3-en.png`
`saas-exports-tables-to-skip-img3-de.png`

### Common Skip Patterns — and Their Exceptions

Create paired cards.

### `Usually Skip or Separate`
- UI caches
- temporary or staging exports
- unused feature tables
- duplicate denormalized snapshots
- verbose audit logs
- unrestricted free-text blobs
- binary files and attachments
- system configuration noise

### `Include Only When`
- a named decision or control requires it
- authority and grain are clear
- retention and access are approved
- quality can be tested
- cost is proportionate
- a separate operational, security or compliance product is defined

Show examples:
- audit log for security evidence
- status history for funnel analytics
- attachment metadata without copying file content
- selected text after classification and purpose approval

---

## Which SAP S/4 Tables to Load for Analytics — and Which to Skip

`sap-s4-tables-for-analytics`

`sap-s4-tables-for-analytics-img1-en.png`
`sap-s4-tables-for-analytics-img1-de.png`

### Which SAP S/4 Tables to Load for Analytics — and Which to Skip

Show two contrasting paths.

### `Physical-Table-First`
```text
Table Catalog
→ Copy Available Tables
→ Rebuild Semantics Downstream
→ Mixed Grain and Status
→ Fragile Analytics
```

### `Business-Semantics-First`
```text
Business Decision
→ Process and Document Flow
→ Target Grain
→ Supported Semantic Source
→ Required Fields and Delta
→ Governed Scope
```

Add evaluation dimensions:
- released and supported interface
- deployment and release compatibility
- business object meaning
- header, item or line grain
- organizational scope
- currency and unit semantics
- reversal and cancellation behavior
- delta and deletion support

---

`sap-s4-tables-for-analytics-img2-en.png`
`sap-s4-tables-for-analytics-img2-de.png`

### Classify the source structures by business role

Create four source groups.

### `Transactional Facts`
- sales, delivery and billing documents
- purchase, goods movement and invoice documents
- accounting journal entries
- inventory or production events

### `Master and Organizational Context`
- business partner or customer and supplier context
- product or material
- company code, sales organization, plant or cost center
- currency, unit and fiscal calendar

### `Conditional Process Evidence`
- document flow and status
- schedule lines
- change or event history
- pricing conditions
- selected customizing required to interpret behavior

### `Usually Skip, Replace or Separate`
- unsupported raw-table copies
- duplicate extraction structures
- obsolete or deprecated views
- technical logs and temporary records
- broad customizing dumps without a named use case
- attachments and unrestricted text

Show that each source still needs an authority, grain and lifecycle decision.

---

`sap-s4-tables-for-analytics-img3-en.png`
`sap-s4-tables-for-analytics-img3-de.png`

### Respect relationships and event grain

Create an order-to-cash document flow:

```text
Sales Order
→ Delivery
→ Billing Document
→ Accounting Entry
```

Supporting references:
- business partner
- product or material
- sales organization and company code
- plant or distribution context
- currency, unit and fiscal period

For every transition show:
- business event
- header and item relationship
- key and document-flow reference
- quantity and amount semantics
- status, cancellation and reversal behavior
- posting or effective date
- target analytical grain

Add failure cases:
- order and billing amounts counted as the same fact
- header values repeated across items
- cancelled or reversed documents treated as active
- local and group currency mixed
- late postings assigned to the wrong reporting period

---

`sap-s4-tables-for-analytics-img4-en.png`
`sap-s4-tables-for-analytics-img4-de.png`

### Document the source-scope decision

Create a governed source-scope register with one row per business object or extraction source.

Mandatory fields:
- business process and decision
- business object
- technical table, CDS view, API or extractor reference
- release and support status
- deployment and release dependency
- target data product
- target grain
- header, item and document-flow keys
- organizational scope
- currency, unit and fiscal semantics
- delta, deletion and archiving behavior
- cancellation and reversal rule
- PII or sensitive-commercial-data classification
- freshness and retention
- business, application and technical owner
- decision: include, conditional, defer, replace or exclude
- rationale and review trigger

Outputs:
- approved semantic extraction scope
- field allowlist
- source and delta contract
- explicit raw-table skip or replacement list
- reconciliation controls
- release-change review plan

Show that a source can be replaced when a supported released interface becomes available or an existing view is deprecated.

---

## Which ServiceNow Tables to Load — and Which to Skip

`servicenow-tables-for-analytics`

`servicenow-tables-for-analytics-img1-en.png`
`servicenow-tables-for-analytics-img1-de.png`

### Which ServiceNow Tables to Load — and Which to Skip

Create a logical source inventory with these categories:

1. `Task Parent and Child Classes`
2. `Requests, Catalog and Workflow Records`
3. `SLA and Operational Events`
4. `Users, Groups and Assignment References`
5. `CMDB Classes and Relationships`
6. `Audit, History and Journal Data`
7. `Attachments, Configuration and System Tables`

Show that a parent table may contain or represent child records and that loading both can duplicate facts.

Add decision dimensions:
- business process and class
- logical versus physical representation
- row and event grain
- reference and display-value semantics
- current state versus history
- journal and attachment risk
- domain and role access
- expected volume and support cost

---

`servicenow-tables-for-analytics-img2-en.png`
`servicenow-tables-for-analytics-img2-de.png`

### Classify the source structures by business role

Create four decision groups.

### `Core Process Facts`
- incident, problem or change when the decision needs them
- request or request item
- selected task or workflow records
- task SLA for defined service-level analysis

### `Required Context`
- users, groups and assignment
- services, offerings or selected configuration items
- state, priority, category and calendar references
- approved custom tables

### `Conditional History and Controls`
- state transitions
- audit evidence
- reassignment or approval events
- snapshots for backlog or point-in-time analysis

### `Usually Skip or Separate`
- duplicate parent and child representations
- unrestricted journals and work notes
- attachments and binary content
- broad CMDB or system-table dumps
- technical logs without an operational product

Add a note that installed plugins and custom applications override generic examples.

---

`servicenow-tables-for-analytics-img3-en.png`
`servicenow-tables-for-analytics-img3-de.png`

### Respect relationships and event grain

Create a relationship-centered model:

```text
Task
├─ Incident
├─ Problem
├─ Change
└─ Request or Other Child Class
```

Connect conditional supporting facts:
- Task SLA
- assignment group and user
- configuration item or business service
- state transition or snapshot

For every relationship show:
- class and `sys_class_name` meaning
- parent versus child representation
- business event and target grain
- reference key and display value
- one-to-many effect
- current versus historical meaning
- domain and PII classification

Add failure cases:
- parent and child rows counted twice
- several SLA records treated as one task
- current CI or assignment applied to historical events
- journal updates counted as task events
- CMDB relationship explosion

---

`servicenow-tables-for-analytics-img4-en.png`
`servicenow-tables-for-analytics-img4-de.png`

### Document the source-scope decision

Create a governed artifact with one row per logical table, child class or relationship.

Mandatory fields:
- table and class name
- parent or extension relationship
- application or plugin
- business purpose
- target data product
- target task, event, SLA, CI or snapshot grain
- required columns and references
- display-value and choice mapping
- current, transition, audit or snapshot need
- journal, attachment and free-text decision
- domain, role and access boundary
- deletion, archive and retention behavior
- freshness and volume expectation
- business and platform owner
- decision: include, conditional, defer, exclude or separate product
- duplication rule
- rationale and review trigger

Outputs:
- approved table and class scope
- inheritance and reference map
- explicit parent-child duplication controls
- field allowlist and text boundary
- CMDB class allowlist
- history and SLA event contract
- ingestion handoff

Show that a table being queryable through an API does not make it an approved analytical source.

---

## Snowflake as a Governance Starting Point

`snowflake-governance-start`

`snowflake-governance-start-img1-en.png`
`snowflake-governance-start-img1-de.png`

### Governance Decisions and Snowflake Enforcement

Create two aligned columns.

### `Business decisions`
- approved purpose
- Data Owner and Steward
- classification
- permitted use
- retention
- quality expectations
- sharing approval
- exception decision

### `Snowflake enforcement`
- role hierarchy
- privileges
- tags
- masking policies
- row access policies
- secure views
- audit evidence
- resource and cost controls

Connect every enforcement control to an accountable decision.

---

`snowflake-governance-start-img2-en.png`
`snowflake-governance-start-img2-de.png`

### Map Classification to Policies and Access

Create a policy chain.

```text
Business classification
→ approved metadata tag
→ policy selection
→ role or attribute condition
→ masking, row restriction or object access
→ effective access test
→ audit evidence
→ recertification
```

Add exception path:
- temporary exception
- owner approval
- expiry
- evidence
- review

Show that classification alone is not enforcement.

---

`snowflake-governance-start-img3-en.png`
`snowflake-governance-start-img3-de.png`

### Secure Sharing Without Losing Accountability

Create a provider-to-consumer boundary model.

### `Provider`
- governed source
- approved product
- secure view or share
- permitted use
- retention and revocation

### `Consumer`
- named organization or domain
- approved purpose
- local access owner
- downstream-copy boundary
- incident contact

### `Cross-boundary evidence`
- contract
- lineage
- access history
- usage review
- change notice
- revocation test

---

## Which Source Should Load First?

`which-source-to-load-first`

`which-source-to-load-first-img1-en.png`
`which-source-to-load-first-img1-de.png`

### Do not start with the easiest connector

Show two contrasting paths.

### `Convenience-First`
```text
Available Connector
→ Large Raw Load
→ Unclear Consumer
→ Late Governance Questions
→ Rework
```

### `Decision-First`
```text
Named Decision
→ Candidate Sources
→ Authority and Grain Check
→ Readiness and Risk Check
→ Smallest Complete Slice
→ Trusted Outcome
```

Add weak selection signals:
- connector already licensed
- largest table count
- most visible executive sponsor
- lowest extraction effort

Add strong selection signals:
- decision value
- authoritative contribution
- testable grain
- accountable owner
- approved access
- reusable learning

---

`which-source-to-load-first-img2-en.png`
`which-source-to-load-first-img2-de.png`

### Score source readiness and decision value

Create a two-axis prioritization matrix.

### `Decision Value`
- named user and action
- measurable business impact
- urgency or control need
- reuse across products
- executive or regulatory criticality

### `Source Readiness`
- authority understood
- grain and keys known
- owner available
- access and PII approved
- quality measurable
- history and deletion behavior understood
- extraction path supportable

Place candidates into four zones:
1. `Start Now` — high value, high readiness
2. `Prepare` — high value, low readiness
3. `Opportunistic` — lower value, high readiness
4. `Defer` — lower value, low readiness

Show that the selected source still requires a complete vertical-slice definition.

---

`which-source-to-load-first-img3-en.png`
`which-source-to-load-first-img3-de.png`

### Select the smallest complete vertical slice

Create a left-to-right flow:

```text
Source Scope
→ Controlled Ingestion
→ Conformed Business Grain
→ Data Product
→ Semantic Model
→ Named Decision or Control
```

For every stage show one acceptance question:
- source: which objects and fields are approved?
- ingestion: how are changes, deletions and failures handled?
- conform: what is one row and which authority applies?
- product: which quality contract is enforced?
- semantic: which definitions and filters are reused?
- consumer: which action becomes better or safer?

Contrast with an incomplete slice:

```text
Source → Raw Tables → “Done”
```

Show that the first source is successful only when the governed outcome is used and reconciled.

---

`which-source-to-load-first-img4-en.png`
`which-source-to-load-first-img4-de.png`

### Record the first-source decision

Create a candidate decision portfolio with one card per source.

Mandatory fields:
- source system
- starting decision and consumer
- expected value
- authoritative contribution
- target grain
- owner and steward
- access and PII readiness
- quality and reconciliation readiness
- extraction dependency
- estimated scope and complexity
- reusable learning
- decision: start, prepare or defer
- rationale
- prerequisites
- review trigger

Outputs:
- selected first source
- first vertical-slice boundary
- named implementation and business owners
- unresolved prerequisites
- deferred-source queue
- success and exit criteria

Show that selection is a governed portfolio decision, not a connector backlog order.

---

## Which Workday Objects to Load — and Which to Skip

`workday-tables-for-analytics`

`workday-tables-for-analytics-img1-en.png`
`workday-tables-for-analytics-img1-de.png`

### Which Workday Objects to Load — and Which to Skip

Show two contrasting paths.

### `Report-First`
```text
Existing Custom Report
→ Export All Available Fields
→ Flatten Current State
→ Sensitive Duplication
→ Conflicting Workforce Metrics
```

### `Decision-First`
```text
Workforce Decision
→ Population and Effective Date
→ Worker or Event Grain
→ Required Business Objects
→ Security and Field Scope
→ Governed Product
```

Use example decisions:
- active workforce by organization and effective date
- open positions and hiring demand
- approved compensation analysis
- absence or time decision with defined population

Show that the same tenant requires different scopes and security boundaries for each decision.

---

`workday-tables-for-analytics-img2-en.png`
`workday-tables-for-analytics-img2-de.png`

### Classify the source structures by business role

Create four groups.

### `Core Workforce Context`
- worker or contingent worker
- position and job profile
- supervisory organization
- company, cost center and location
- manager and organizational hierarchy

### `Conditional Facts and Events`
- staffing events
- recruiting and candidate data
- compensation and payroll
- time, absence and leave
- learning, performance or talent data

### `Controlled Derived Sources`
- custom reports
- calculated fields
- integration outputs
- approved snapshots

### `Usually Exclude or Restrict`
- unrestricted worker profile fields
- health, benefits or document content without a named purpose
- notes, attachments and case text
- duplicate reports with conflicting calculations
- technical integration metadata

Show that inclusion depends on population, purpose, security domain, grain and effective-date behavior.

---

`workday-tables-for-analytics-img3-en.png`
`workday-tables-for-analytics-img3-de.png`

### Respect relationships and event grain

Create an effective-dated relationship model:

```text
Worker
→ Position
→ Job Profile
→ Supervisory Organization
→ Company / Cost Center / Location
```

Add parallel events:
- hire or contract start
- transfer
- manager or organization change
- compensation change
- leave and return
- termination or rescind

For every relationship show:
- effective start and end
- current, historical or future-dated state
- primary versus additional job
- worker type
- correction and rescind behavior
- business key and reference ID
- security classification

Add failure cases:
- current organization applied to historical facts
- multiple positions collapsed
- future changes included too early
- rescinded events retained as valid
- manager hierarchy reconstructed without effective dates

---

`workday-tables-for-analytics-img4-en.png`
`workday-tables-for-analytics-img4-de.png`

### Document the source-scope decision

Create a governed artifact with one row per business object, report or service output.

Mandatory fields:
- workforce decision and population
- Workday business object or report
- source interface or integration
- target data product
- worker, position, event or period grain
- effective-date and correction semantics
- required fields and calculated fields
- organization and hierarchy relationship
- worker-type and multiple-job handling
- security domain and permitted use
- PII and sensitivity classification
- retention and deletion requirement
- freshness
- business, data and integration owner
- decision: include, conditional, defer, exclude or restricted product
- rationale and review trigger

Outputs:
- approved object and field scope
- security-domain mapping
- effective-date contract
- restricted-data boundary
- duplicate-report retirement list
- reconciliation and access-review controls

Show that access to a field in a report does not automatically authorize analytical reuse.

---
