---
title: AI Governance — Making AI Controllable and Accountable
description: How roles, risk classification, approved models and use cases, governed data, versioning, access control, human oversight, auditability and lifecycle controls turn AI into a responsible enterprise capability.
category: Artificial Intelligence
tags:
  - ai
  - ai-foundations
  - ai-governance
  - responsible-ai
  - risk-management
  - model-governance
  - data-governance
  - human-oversight
  - auditability
  - ai-lifecycle
  - access-control
  - privacy
order: -1
author: Thomas Lindackers
series: ai-foundations
seriesPart: 7
seriesTitle: AI Foundations
hero: images/playbooks/ai-gov-hero.png
---

## AI governance is an operating model — not a policy document

The previous parts of this series explained how models learn, how language models generate outputs, how enterprise knowledge reaches AI through RAG, how agents execute actions, where AI systems fail and how quality can be evaluated in production.

The final question is organizational:

> **How can an organization decide which AI may be used, for which purpose, with which data, under whose responsibility and with which controls?**

AI governance provides that operating model.

It connects business ownership, data governance, model governance, privacy, security, risk management, engineering, operations and human decision-making. Its purpose is not to prevent experimentation. Its purpose is to make experimentation, deployment and scaling **controlled, traceable and accountable**.

A governed AI system should make it possible to answer:

- Which business purpose does the use case serve?
- Who owns the outcome and the associated risk?
- Which model, prompt, agent and tools are approved?
- Which data classes may be processed?
- Where did the training, evaluation and context data come from?
- Which system version produced a result?
- Which controls were active?
- Who approved the release?
- When is human review required?
- How is performance monitored?
- How can the system be changed, rolled back or retired?

Without these answers, an organization may have an AI prototype, but it does not yet have a responsibly operated AI capability.

```flow linear vertical
Business purpose
Accountable ownership
Risk-based controls
Controlled implementation
Measurable operation
Traceable change
Managed retirement
```

## AI governance builds on existing governance

AI governance is often treated as a new specialist discipline that starts with model cards, ethics principles or an AI committee.

Those elements can be useful, but they are not sufficient.

An AI system depends on capabilities that should already exist elsewhere in the organization:

- data quality and source ownership,
- metadata, catalog and lineage,
- PII and privacy governance,
- access and security governance,
- ownership and stewardship,
- KPI and metric governance,
- lifecycle and retention,
- policies, standards and change control.

AI adds new governed objects — models, prompts, agents, tools, training runs, evaluation datasets, guardrails and outputs — but it does not replace the existing foundation.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov.img1-en.png"
        alt="AI governance scope with use cases, models, prompts, agents, training data, context data, policies and outputs built on existing governance capabilities"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Trusted AI depends on trusted governance foundations. Weak data quality, unclear ownership, missing lineage or uncontrolled access remain weaknesses when AI is added — and may be amplified at scale.
    </figcaption>
</figure>

The relationship is direct:

| Existing governance capability | What it enables for AI |
| --- | --- |
| **Data Quality Governance** | approved data sources, quality rules, thresholds, known limitations and remediation |
| **Metadata, Catalog & Lineage** | provenance of training, evaluation and context data; traceability of dependencies |
| **PII & Privacy Governance** | lawful use, purpose limitation, minimization, retention and data-subject protections |
| **Access & Security Governance** | identity, roles, secrets, APIs, tools, data permissions and segregation of duties |
| **Ownership & Stewardship** | accountable business, data, model and operational roles |
| **KPI & Metric Governance** | governed business definitions and reliable decision signals |
| **Lifecycle & Retention** | controlled introduction, change, operation, archiving and deletion |
| **Policies & Standards** | consistent decision rules, review requirements and evidence expectations |

> **AI governance does not begin at the model. It begins at the business purpose and extends through every dependency that can influence the outcome.**

## What exactly must be governed?

“Govern the model” is too narrow for modern AI systems.

A production system may combine:

```text
Business use case
+ model and provider
+ system and task prompts
+ retrieved context
+ tools and APIs
+ agent orchestration
+ policies and guardrails
+ runtime configuration
+ human review
+ downstream business process
```

Every component can change behaviour, risk and accountability.

A practical AI inventory therefore needs more than a list of model names.

| Governed object | Minimum information |
| --- | --- |
| **AI use case** | purpose, owner, users, decision or action, expected value, risk class, status |
| **Model** | provider, hosting, model family, version, intended use, limitations, approval status |
| **Prompt** | owner, version, purpose, inputs, output schema, prohibited behaviour, test status |
| **Agent** | objective, orchestration logic, tools, permissions, stop conditions, approval points |
| **Training / fine-tuning data** | source, rights, classification, quality, lineage, snapshot, retention |
| **Evaluation data** | cases, provenance, expected behaviour, risk coverage, owner, version |
| **Context / RAG data** | source, validity, access rules, index version, deletion and refresh process |
| **Tool / API** | allowed operations, credentials, parameter constraints, side effects, monitoring |
| **Policy / guardrail** | rule, owner, implementation, version, tests, exception process |
| **Deployment** | environment, configuration, model and prompt version, users, access roles |
| **Decision / output** | input context, system version, evidence, result, review, action and outcome |

The inventory is not merely documentation. It becomes the basis for approval, monitoring, incident response, change management and retirement.

## Roles and responsibilities must cross organizational boundaries

AI governance fails when it is delegated entirely to Data Science, IT, Legal or an isolated AI committee.

The business owns the purpose and outcome. Data roles own the permitted and reliable use of data. Technical roles build and operate the system. Privacy, Security, Risk and Compliance define or validate controls. Human reviewers remain accountable where the process requires judgment.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov.img2-en.png"
        alt="AI governance roles and responsibilities with business, product, data, model, development, privacy, security, risk, human review and operations roles connected to one AI use case"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        One AI use case normally crosses several accountability domains. “The AI team owns it” is not a sufficient responsibility model.
    </figcaption>
</figure>

### Business Owner

Accountable for:

- business purpose and expected value,
- acceptable business impact and residual risk,
- outcome quality and process integration,
- continued need for the use case,
- final escalation for material business consequences.

The Business Owner should not approve technical details they cannot assess alone. They remain accountable for why the system is used and how its outputs affect the business.

### AI Product Owner

Responsible for:

- the end-to-end use-case backlog,
- requirements and stakeholder coordination,
- lifecycle status and release readiness,
- documentation completeness,
- coordinating risk reviews, approvals and re-assessments.

### Data Owner and Data Steward

The Data Owner approves whether specific data may be used for the stated purpose.

The Data Steward ensures that:

- classification is correct,
- quality requirements are defined,
- lineage and metadata are maintained,
- known limitations remain visible,
- deletion, correction and retention rules are applied.

### Model Owner

Responsible for:

- approved model and model version,
- documented capabilities and limitations,
- evaluation evidence,
- model risk and performance,
- upgrade, rollback and retirement decisions.

For an external model API, ownership does not disappear. The organization still needs an internal role accountable for selection, permitted usage and provider changes.

### AI Developer / Data Scientist

Responsible for:

- implementation,
- prompt and agent logic,
- evaluation and testing,
- technical documentation,
- reproducible builds and releases,
- secure integration with models, data and tools.

### Privacy / Legal

Assesses:

- lawful processing,
- purpose limitation and data minimization,
- PII and special-category data,
- data-subject rights,
- contracts and provider terms,
- intellectual-property and confidentiality concerns,
- applicable legal and regulatory obligations.

### Information Security

Controls:

- identities and roles,
- secrets and service accounts,
- tool and API permissions,
- threat modelling,
- data leakage and exfiltration controls,
- incident detection and response,
- secure hosting and provider integration.

### Risk / Compliance

Coordinates:

- use-case and impact classification,
- control requirements,
- exceptions and accepted risk,
- evidence for internal and external review,
- periodic re-assessment,
- regulatory mapping.

### Human Reviewer

A Human Reviewer is not simply “a person somewhere in the process.”

The role requires:

- defined review criteria,
- sufficient information and evidence,
- authority to reject, correct or escalate,
- sufficient time and competence,
- protection against automation bias,
- documentation of material overrides.

### Operations

Responsible for:

- deployment and runtime configuration,
- availability and observability,
- alerts and incident handling,
- usage and cost monitoring,
- change execution,
- rollback and retirement.

## Decision rights are more important than committee names

An AI council or governance board can coordinate standards and resolve exceptions. It should not become the owner of every individual outcome.

A practical decision model distinguishes at least:

| Decision | Accountable role |
| --- | --- |
| Is the use case valuable and still required? | Business Owner |
| May specific data be used for this purpose? | Data Owner, with Privacy / Legal where applicable |
| Is the model approved for this context? | Model Owner with Risk, Security and relevant specialists |
| Is the implementation technically ready? | Engineering / Technical Owner |
| Are residual risks accepted? | Designated business and risk authority |
| May the system enter production? | Defined release authority |
| Must a result be reviewed or blocked? | Policy-defined operational role |
| Should the system be suspended or retired? | Business Owner and operational / risk authority |

The exact RACI differs by organization. The non-negotiable requirement is that each material decision has a named accountable role.

## Classify the use case — not only the model

A model is not inherently low-risk or high-risk in every context.

The same language model may be used to:

- rephrase internal text,
- search policies,
- recommend customer actions,
- prioritize employee cases,
- approve credit,
- control a safety-relevant process.

The model may be identical. The impact is not.

Risk classification should therefore start with the **use case**, the affected people, the degree of automation, the data sensitivity and the consequences of error or misuse.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov.img3-en.png"
        alt="AI use case risk matrix combining data sensitivity and potential impact from assistive use to critical automated decisions"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Higher impact and higher data sensitivity require stronger controls. The matrix is an internal governance instrument; it does not replace a formal legal or regulatory classification.
    </figcaption>
</figure>

### Useful classification dimensions

#### Purpose and affected process

- drafting and productivity,
- knowledge access,
- operational support,
- recommendation or decision support,
- automated decision,
- autonomous or safety-relevant action.

#### Potential impact

- inconvenience or minor rework,
- financial loss,
- denial of service or opportunity,
- discrimination or unfair treatment,
- privacy or confidentiality breach,
- legal or contractual consequence,
- health, safety or fundamental-rights impact.

#### Data sensitivity

- public,
- internal,
- confidential,
- personal data,
- special-category or highly sensitive data,
- regulated or legally protected information.

#### Autonomy

- generates a draft,
- provides a recommendation,
- triggers a workflow,
- performs a reversible action,
- performs an irreversible action,
- makes or effectively determines a material decision.

#### Scale and reversibility

- number of affected people or transactions,
- frequency of execution,
- ability to detect and correct an error,
- time until harm occurs,
- availability of rollback or compensation.

#### Exposure and threat level

- internal users only,
- customers or external users,
- open public access,
- ability to call tools,
- ability to process untrusted content,
- incentive for manipulation or abuse.

### An internal risk model

A pragmatic internal classification can use four levels:

| Level | Typical profile | Control intensity |
| --- | --- | --- |
| **Low** | assistive, low-impact, non-sensitive, easily reviewed | standard documentation, access control, basic testing and monitoring |
| **Moderate** | operational influence or confidential data | documented assessment, stronger evaluation, logging, periodic review |
| **High** | material decisions, PII, significant financial or human impact | independent review, mandatory oversight, robust testing, formal approval, continuous monitoring |
| **Restricted** | unacceptable or only exceptionally permitted | explicit executive / legal approval or prohibition; strongest controls and complete evidence |

> **This internal matrix supports governance decisions. It must not be presented as a substitute for the risk categories, obligations or legal analysis required by applicable law, including the EU AI Act.**

## Approved models and approved use cases are different controls

A common control pattern is an approved-model list. That is useful, but incomplete.

A model can be approved for one purpose and prohibited for another.

Example:

| Model status | Use case | Decision |
| --- | --- | --- |
| approved enterprise model | summarizing public documents | permitted under standard controls |
| same approved model | processing confidential contracts | permitted only in an approved hosting and data boundary |
| same approved model | drafting customer communication | permitted with review and logging |
| same approved model | autonomous employment decision | not permitted without separate assessment and explicit authorization |
| unapproved public consumer service | any confidential company data | prohibited |

Approval should cover several dimensions:

- model provider and contract,
- hosting and data location,
- retention and provider-training settings,
- supported data classes,
- permitted regions and users,
- intended use and prohibited use,
- model version and update behaviour,
- security and privacy assessment,
- evaluation evidence,
- fallback and exit strategy.

The approved-model catalog should not become a static spreadsheet. It needs version, owner, status, effective date, limitations and re-assessment date.

## Data classification, PII and purpose limitation

AI systems can receive data through many paths:

- prompts entered by users,
- uploaded files,
- fine-tuning datasets,
- feature pipelines,
- RAG indexes,
- tool responses,
- conversation history,
- logs and traces,
- human feedback,
- cached outputs.

A policy that controls only training data misses most modern enterprise AI flows.

### Classify before connecting

For every data path, determine:

- classification,
- owner,
- permitted purpose,
- permitted model or provider,
- geographic and contractual restrictions,
- retention,
- masking or anonymization requirements,
- access roles,
- deletion process,
- monitoring requirements.

The application should enforce these rules technically where possible. A prompt instruction such as “do not expose confidential data” is not an access-control system.

### PII does not disappear in embeddings or logs

Personal or confidential content may remain sensitive when it is:

- embedded,
- indexed,
- cached,
- summarized,
- included in prompts,
- stored in traces,
- copied into evaluation datasets,
- used as human-review examples.

An embedding is not automatically anonymous. A vector index still needs classification, access control, deletion and lifecycle management aligned with its source content.

### Minimize data by design

A system should not receive more data than the use case needs.

Examples:

- retrieve only authorized and relevant document sections,
- mask identifiers before model processing,
- use structured tools for live data instead of copying broad database extracts,
- separate tenant and regional indexes,
- avoid logging full prompts where metadata is sufficient,
- apply shorter retention to sensitive traces,
- use synthetic or de-identified data for testing where appropriate.

## Provenance and quality of training and context data

The phrase “trained on company data” can hide several distinct mechanisms:

| Mechanism | Governance focus |
| --- | --- |
| **Pre-training by a provider** | provider transparency, rights, model limitations and procurement assessment |
| **Fine-tuning** | dataset rights, representativeness, PII, quality, version and rollback |
| **Classical ML training** | features, labels, sampling, bias, drift, reproducibility and validation |
| **RAG / context retrieval** | approved sources, validity, access filtering, index version, freshness and deletion |
| **In-context examples** | provenance, correctness, confidentiality and prompt-injection risk |
| **Human feedback** | reviewer quality, privacy, bias, retention and traceability |

For every governed dataset, record at least:

```text
Dataset identifier and version
Purpose and permitted use
Owner and steward
Source systems
Collection and transformation process
Classification and legal basis where required
Quality rules and known limitations
Sampling and coverage
Estimate, correction and synthetic-data markers
Lineage and effective period
Retention and deletion rules
Dependent models, indexes and use cases
```

A clean technical dataset can still be unsuitable for a use case. Quality must be defined against purpose.

Relevant questions include:

- Does the data represent the population and scenarios the system will encounter?
- Are rare but critical cases present?
- Are labels consistent and reviewable?
- Are historical decisions themselves biased or incorrect?
- Are estimated values visible?
- Are old and new policies distinguishable?
- Can deleted or corrected source content be removed from indexes and test datasets?
- Are quality changes propagated to dependent AI systems?

The related playbook [Trash In, Trash Out](/playbooks/trash-iinout) examines this data chain in more detail.

## Access control must cover models, data, prompts, tools and administration

AI access control is broader than access to a chat interface.

Different permissions are required for:

- using a model,
- accessing a data source,
- retrieving a document or record,
- editing a production prompt,
- changing an agent workflow,
- adding or modifying tools,
- approving a deployment,
- viewing sensitive traces,
- overriding a guardrail,
- disabling monitoring,
- deleting audit evidence.

A production design should distinguish:

```flow linear vertical
User identity
Permitted use case
Permitted data
Permitted model
Permitted tools
Permitted action
Required approval
Recorded outcome
```

### Enforce permissions before the model

For RAG and tool use, authorization should be evaluated in the application, retrieval layer, API or target system.

The model should not receive unauthorized content and then be expected to hide it.

### Apply least privilege to agents

An agent should receive only the tools and permissions needed for the current task.

Prefer:

- narrow tools instead of generic database or shell access,
- allow-listed operations,
- parameter validation,
- tenant and user context,
- read-only access by default,
- step, time and cost limits,
- separate approval for consequential writes,
- idempotency, rollback or compensation where possible.

### Separate critical duties

The same person should not be able to:

- change a production prompt,
- weaken a guardrail,
- approve the change,
- deploy it,
- and erase the evidence

without independent control.

The degree of separation should be proportional to risk.

## A governed lifecycle from idea to retirement

AI governance must operate as a lifecycle, not as a one-time approval.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov.img4-en.png"
        alt="Governed AI lifecycle from idea and classification through data assessment, development, testing, risk review, approval, deployment, monitoring, change management and retirement"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Governance gates create explicit decisions and evidence throughout the lifecycle. Changes to models, prompts, agents, data or policies can trigger a new review at the appropriate stage.
    </figcaption>
</figure>

### 1. Idea

Define:

- problem and desired outcome,
- affected users and stakeholders,
- business value,
- alternatives that do not require AI,
- accountable Business Owner.

The first governance question is not “Which model should we use?” It is “Should AI be used for this problem at all?”

### 2. Use-case classification

Document:

- purpose,
- impact,
- data sensitivity,
- degree of autonomy,
- affected people,
- preliminary risk class,
- required control path.

### 3. Data assessment

Confirm:

- approved sources,
- ownership and classification,
- PII and confidentiality requirements,
- quality and coverage,
- lineage and validity,
- retention and deletion,
- access enforcement.

### 4. Development

Build with:

- approved models and providers,
- version-controlled prompts and agent logic,
- narrow tools and explicit policies,
- secure secrets and environments,
- traceable configuration.

### 5. Testing and evaluation

Evaluate:

- task quality,
- source grounding,
- retrieval,
- robustness,
- bias and fairness where relevant,
- privacy and security,
- tool execution,
- failure paths,
- human-review workflow,
- latency and cost.

Part 6, [Evaluating and Operating AI](/playbooks/ai-eval), describes this evaluation model in depth.

### 6. Risk review

Assess:

- control effectiveness,
- unresolved limitations,
- residual risk,
- operational readiness,
- incident and rollback plans,
- need for additional approval or restrictions.

### 7. Approval

A release record should state:

- approved system version,
- approved scope and users,
- approved data classes,
- conditions and exceptions,
- required monitoring,
- expiry or review date,
- named approvers.

### 8. Deployment

Production deployment should activate:

- access roles,
- guardrails,
- logging and traceability,
- monitoring and alerts,
- rollback configuration,
- operational ownership.

### 9. Operation and monitoring

Monitor:

- quality and failure rates,
- unsupported outputs,
- retrieval and tool errors,
- drift,
- access anomalies,
- policy violations,
- overrides and escalations,
- incidents,
- latency and cost,
- user and business outcomes.

### 10. Change management

Assess changes to:

- model,
- prompt,
- agent,
- tool,
- data source,
- retrieval index,
- policy,
- user population,
- business process,
- hosting or provider.

Not every change requires a complete restart. Every material change requires a defined impact assessment and the correct re-test and re-approval path.

### 11. Retirement

Retirement includes more than switching off the interface.

It may require:

- removing access and credentials,
- disabling tools and agents,
- archiving or deleting model artifacts,
- deleting or retaining logs according to policy,
- removing vector indexes and cached context,
- terminating provider integrations,
- documenting the final status,
- redirecting dependent processes,
- preserving required audit evidence.

## Version the complete AI system

A model version alone does not reproduce an AI outcome.

The behaviour may also depend on:

- system prompt,
- task prompt,
- examples,
- retrieval index,
- embedding model,
- reranker,
- tools,
- policies,
- guardrails,
- runtime parameters,
- code,
- environment,
- user and permission context.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/ai-gov-img5-en.png"
        alt="AI governance artifacts that must be versioned and operational evidence that must be audited"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Reproducibility and accountability require versioned technical artifacts and an audit trail of access, changes, approvals, performance, incidents and retention.
    </figcaption>
</figure>

A release manifest can connect these elements:

```yaml
use_case:
  id: customer-policy-assistant
  version: 3.2
  risk_class: moderate
  owner: customer-operations

model:
  provider: approved-provider
  model: enterprise-language-model
  version: 2026-06
  hosting_profile: eu-enterprise

prompts:
  system_prompt: 7.4
  answer_template: 3.1

retrieval:
  source_collection: governed-policies
  index_snapshot: 2026-07-15
  embedding_model: embed-v4
  permission_filter: acl-policy-5

agent:
  workflow_version: 2.6
  tools:
    - policy-search: 4.0
    - ticket-create: 2.1
  max_steps: 6
  write_approval_required: true

controls:
  policy_bundle: ai-assistant-4.3
  output_validator: 2.2
  monitoring_profile: moderate-risk-3

evidence:
  evaluation_run: eval-2026-0716-04
  approval_record: approval-2026-0717-02
  deployed_at: 2026-07-18T08:00:00Z
```

The exact schema is implementation-specific. The governance requirement is that a result can be associated with the relevant system state.

## What must be auditable?

Auditability is the ability to reconstruct material events and decisions with appropriate evidence.

Depending on risk and legal constraints, evidence may include:

### Identity and access

- user and service identity,
- assigned roles,
- data and model access,
- tool permissions,
- administrative actions,
- privileged overrides.

### Inputs and context

- input category or full input where permitted,
- relevant conversation state,
- retrieved sources and versions,
- applied filters,
- data classification,
- tool results used by the model.

Sensitive content should not be logged without purpose. Auditability and data minimization must be balanced through risk-based logging, redaction, hashing, references and controlled retention.

### System state

- model and configuration,
- prompt and agent version,
- tools and policies,
- runtime parameters,
- code and deployment version,
- active feature flags and guardrails.

### Decisions and actions

- generated result,
- confidence or quality indicators where meaningful,
- evidence and citations,
- human review and override,
- tool calls and side effects,
- downstream business action,
- final outcome.

### Changes and approvals

- change request,
- reason and impact assessment,
- test evidence,
- approver,
- effective date,
- exception and expiry,
- rollback history.

### Incidents and monitoring

- alerts,
- policy violations,
- suspicious access,
- failed or harmful outputs,
- root-cause analysis,
- mitigation and recovery,
- lessons learned and new regression tests.

> **Auditability does not mean storing everything forever. It means retaining the right evidence, for the right purpose, under controlled access and retention.**

## Human oversight must be effective

Human oversight is often reduced to a checkbox: “A human remains in the loop.”

That statement is too vague.

Useful oversight models include:

| Oversight model | Meaning |
| --- | --- |
| **Human informed** | a person receives information but does not approve each case |
| **Human reviews exceptions** | normal cases proceed; defined anomalies are escalated |
| **Human approves action** | the system prepares a recommendation; a person authorizes execution |
| **Human makes the decision** | AI supplies evidence or analysis; the person determines the outcome |
| **Human can stop the system** | authorized personnel can suspend operation or autonomy |

Effective oversight requires:

- a clearly defined decision point,
- understandable evidence,
- adequate competence and time,
- authority to reject or change the result,
- escalation paths,
- protection against rubber-stamping,
- measurement of overrides and missed errors,
- periodic review of whether oversight still works.

### Automation bias is a governance risk

A human reviewer may accept an AI recommendation because it appears precise, confident or technically advanced.

Controls can include:

- show evidence and uncertainty before the recommendation,
- require reasons for acceptance in high-impact cases,
- use independent calculations or source checks,
- rotate and train reviewers,
- sample accepted and rejected decisions,
- measure override patterns,
- avoid interfaces that visually present AI as authoritative.

Human oversight should reduce risk, not merely transfer responsibility onto an operator who lacks real control.

## Monitoring must connect to action

A dashboard is not governance unless signals lead to defined responses.

Every monitored indicator should have:

- owner,
- threshold or decision rule,
- severity,
- response time,
- escalation route,
- remediation path,
- evidence and closure.

Useful governance metrics include:

| Area | Example indicators |
| --- | --- |
| **Inventory** | active use cases, unclassified use cases, expired approvals |
| **Data** | quality threshold breaches, stale sources, unresolved lineage gaps |
| **Models** | unsupported versions, evaluation regression, provider changes |
| **Security** | blocked access, suspicious tool calls, secret or data leakage events |
| **Privacy** | PII detections, retention exceptions, deletion failures |
| **Human oversight** | review rate, override rate, escalation rate, reviewer agreement |
| **Operations** | incidents, rollback frequency, availability, latency and cost |
| **Change** | unapproved changes, failed gates, overdue re-assessments |
| **Outcomes** | correction rate, harmful outcomes, process value, user trust signals |

A low override rate is not automatically positive. It may indicate a good system, weak review or automation bias. Metrics need interpretation and context.

## Exceptions need ownership and expiry

Organizations will occasionally accept temporary risk:

- a legacy integration cannot yet provide full lineage,
- a model upgrade is delayed,
- a manual control temporarily replaces automation,
- a pilot uses a restricted user group,
- a monitoring gap exists during migration.

An exception should include:

- scope,
- reason,
- risk,
- compensating control,
- accountable owner,
- approver,
- start and expiry date,
- remediation plan,
- review status.

Permanent undocumented exceptions are policy failures.

## A pragmatic implementation path

An organization does not need a large AI governance platform before it can establish control.

A practical minimum:

```flow linear vertical
Create one AI use-case inventory
Name accountable owners
Define an internal risk classification
Publish approved models and prohibited uses
Connect data classification and access rules
Introduce lifecycle gates
Version the complete release configuration
Require evaluation evidence
Activate monitoring and incident handling
Review and retire use cases
```

### Minimum inventory fields

Start with:

- use-case ID and title,
- business purpose,
- Business Owner,
- Product / Technical Owner,
- status,
- user group,
- model and provider,
- data classes,
- level of autonomy,
- risk class,
- approval record,
- last evaluation,
- next review,
- production endpoint,
- retirement status.

### Start with a small number of enforceable policies

Examples:

1. Confidential or personal data may only be processed through approved enterprise services.
2. Every production AI use case requires a Business Owner.
3. High-impact actions require explicit human authorization unless separately approved.
4. Production prompts, agents, tools and policies are version-controlled.
5. Every release references reproducible evaluation evidence.
6. Material changes require impact assessment and re-approval.
7. All production use cases have monitoring, incident ownership and retirement procedures.

A short policy that is technically and operationally enforced is more useful than a large principle document that cannot influence real systems.

## Common failure patterns

### Governing only procurement

Provider review is necessary, but it does not govern the use case, data, prompt, agent, tools or business outcome.

### Governing only the model

The system behaviour can change while the model remains unchanged.

### Allowing every approved model for every purpose

Approval must be scoped by data class, use case, users, region and degree of automation.

### Treating PII detection as complete privacy governance

Privacy also includes purpose, minimization, access, retention, rights, provider terms and deletion.

### Using human oversight as a disclaimer

A reviewer without evidence, time or authority is not an effective control.

### Logging everything by default

Unlimited prompt and trace storage can create a new privacy and security risk.

### Approving once and forgetting

Models, data, users, providers, policies and business processes change.

### Building an inventory without operational gates

A catalog that does not affect access, deployment, monitoring or change remains documentation rather than governance.

### Making the AI committee responsible for all outcomes

Central governance should define policy, coordinate and challenge. Accountability for business outcomes must remain with named operational owners.

## The central conclusion

AI governance is not a separate island and not a brake placed on top of innovation.

It is the operating model that connects:

- value with accountability,
- data with permitted purpose,
- models with use cases,
- autonomy with control,
- releases with evidence,
- operation with monitoring,
- change with re-assessment,
- retirement with controlled cleanup.

> **AI becomes governable when the organization can identify the responsible owner, the permitted purpose, the complete system version, the applied controls, the supporting evidence and the path to stop or change it.**

The objective is not risk-free AI. No complex technical or organizational system can promise that.

The objective is AI whose risks are visible, decisions are explicit, controls are proportionate and outcomes remain accountable.

## The series at a glance

```flow linear vertical
AI Foundations — How AI Works [done]
Language Models and Providers [done]
Enterprise Data, Embeddings and RAG [done]
AI Agents and Automation [done]
Known Problems and Failure Modes [done]
Evaluation, Costs and Operations [done]
AI Governance [active]
```

## Related playbooks

- [AI Foundations — How AI Works](/playbooks/ai-basics)
- [Enterprise Data, Embeddings and RAG](/playbooks/ai-rag)
- [AI Agents and Automation](/playbooks/ai-agents)
- [Known Problems and Failure Modes](/playbooks/ai-failures)
- [Evaluating and Operating AI](/playbooks/ai-eval)
- [Trash In, Trash Out](/playbooks/trash-iinout)
- [The Missing Pieces – Part 1: Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality Governance](/playbooks/data-quality-governance)

## Sources and further reading

- [NIST — AI Risk Management Framework](https://www.nist.gov/itl/ai-risk-management-framework)
- [NIST — Generative AI Profile](https://www.nist.gov/publications/artificial-intelligence-risk-management-framework-generative-artificial-intelligence)
- [NIST — AI RMF Playbook](https://airc.nist.gov/airmf-resources/playbook/)
- [European Union — Regulation (EU) 2024/1689, Artificial Intelligence Act](https://eur-lex.europa.eu/eli/reg/2024/1689/oj/eng)
- [OECD — AI Principles](https://oecd.ai/en/ai-principles)
- [ISO — ISO/IEC 42001 AI Management Systems](https://www.iso.org/standard/42001)
- [ISO — ISO/IEC 42005 AI System Impact Assessment](https://www.iso.org/standard/42005)

> **Note:** This playbook describes a practical governance operating model. It is not legal advice and does not replace a use-case-specific assessment by Legal, Privacy, Security, Risk and Compliance.
