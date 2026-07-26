---
title: RACI for Data Governance — Decision Rights Without Role Sprawl
description: Use RACI as a maintained operating contract for data decisions, with one accountable role, explicit execution responsibility, targeted consultation and evidence-based escalation.
category: Data Governance
tags:
  - raci
  - decision-rights
  - data-governance
  - data-owner
  - data-steward
order: -1
author: Thomas Lindackers
hero: images/playbooks/raci-for-data-governance-hero.png
series: roles-hub
seriesTitle: Roles and Decision Rights
seriesPart: 2
---

RACI is often introduced as a simple matrix: roles in columns, activities in rows, and the letters R, A, C and I in the cells. That format is easy to produce. A useful RACI operating model is harder.

In Data Governance, the matrix must clarify how a specific decision is prepared, made, executed, communicated and escalated. It must identify who performs the work, who owns the decision and its outcome, whose expertise can change the decision, and who only needs to know the result. When it does that, RACI reduces ambiguity and prevents governance work from disappearing between business, data, platform, architecture, privacy and security teams.

When it does not, RACI becomes an organization chart with letters. Every role is assigned to every decision, several people are declared accountable, consultation becomes a substitute for decision-making, and committees are used to hide who can actually say yes or no.

The objective is therefore not to create the largest possible matrix. The objective is to establish the smallest clear decision contract that works in practice.

## Start with the decision, not the department

A RACI row should describe a decision or a concrete unit of work. It should not merely name a broad process such as “Data Governance,” “Data Quality” or “Metadata.” These labels are too wide to assign meaningful decision rights.

Useful decision objects are specific enough that the organization can answer four questions:

1. What exactly must be decided or completed?
2. What evidence is required before the decision?
3. Who has the authority to accept the outcome?
4. What happens when the required work, evidence or authority is missing?

Examples include:

- approve protection for a PII attribute;
- release a governed mart or Data Product;
- approve a changed KPI definition;
- accept a temporary Data Quality exception;
- retire a Data Product;
- approve an incompatible Data Contract change;
- resolve a conflict between two domain definitions.

These are not interchangeable decisions. They can involve the same roles but still require different RACI assignments. The Data Owner may be accountable for permitted business use, while a Privacy authority is accountable for a policy decision under a specific regulatory framework. A Data Product Owner may be accountable for a go-live decision, while Platform Operations performs deployment and support work. A KPI business owner may approve the meaning of a metric, while a Steward coordinates the definition and an engineering team implements it.

This is why RACI must model decision rights, not hierarchy.

![RACI decision model with sparse role assignments](images/playbooks/raci-for-data-governance-img1-en.png)

## Responsible and Accountable are different operating obligations

The distinction between Responsible and Accountable is the core of the model.

### Responsible performs the work

A Responsible role carries out the activity required to move the decision forward. Depending on the decision, that may include:

- collecting source information;
- documenting the proposed definition;
- evaluating lineage and impact;
- implementing a masking or access rule;
- executing tests;
- preparing an approval package;
- deploying an agreed change;
- recording evidence after implementation.

There can be more than one Responsible role when the work genuinely requires several disciplines. For example, a PII protection decision can require a Data Steward to validate classification and context while an engineering or platform role implements the technical control.

However, every additional R must correspond to explicit work. Adding people as Responsible merely because they are interested, senior or adjacent creates false obligations and obscures the actual delivery path.

### Accountable owns the decision and outcome

The Accountable role has the authority and obligation to accept or reject the proposed outcome. It also owns the consequences of the decision within its defined scope.

Accountability includes:

- confirming that the required evidence is sufficient;
- making or formally owning the final decision;
- accepting the residual risk within delegated authority;
- resolving escalation or invoking the next escalation level;
- ensuring that an approved exception has an owner and expiry date;
- answering for the outcome when the decision is reviewed later.

Accountable is not a synonym for the most senior person in the room. It is also not the role that performs all the work. A senior executive who has no operational decision authority should not automatically receive the A. A Steward who prepares evidence should not automatically receive the A if policy assigns the final decision to a Data Owner, Privacy authority or business owner.

Each decision should have exactly one Accountable role. Two or more As usually indicate one of three problems:

- the decision has not been decomposed far enough;
- authority boundaries are unresolved;
- the organization is avoiding a clear owner for the outcome.

When two authorities genuinely govern different aspects, split the row. For example, separate “approve business use of the data” from “approve the required privacy control.” Each resulting decision can then have one A.

![Responsible and Accountable mapped to different decision steps](images/playbooks/raci-for-data-governance-img2-en.png)

## Consulted and Informed should remain selective

Consulted and Informed are not weaker versions of accountability. They serve different purposes.

A Consulted role provides expertise before the decision. Consultation is justified only when the input can materially change the decision, the conditions of approval or the implementation approach. Examples include:

- Privacy interpreting whether a proposed use is permitted;
- Security assessing control adequacy;
- a Data Architect identifying cross-domain or platform impact;
- a domain expert validating the business meaning of a KPI;
- Platform Operations confirming whether an operational SLA is supportable.

Consultation must have a defined point in the decision flow. It should be clear what is being reviewed, by when input is required and what happens when the consulted role does not respond. Without these boundaries, C becomes an informal veto or an unlimited waiting state.

An Informed role receives the decision or implementation result because downstream work, support, reporting or usage is affected. The role is not part of the approval. Examples include report owners who must adapt to a KPI definition change, consumers affected by a Data Product retirement, or support teams that need the release date and operating instructions.

Assigning C and I to everybody does not increase governance. It creates notification noise, slows decisions and makes the matrix unreadable. A sparse matrix is usually a sign that the decision has been defined well.

## Treat RACI as an operating contract

A useful RACI row contains more than four letters. It is connected to the operating context in which the decision occurs.

At minimum, the decision contract should define:

- **Decision object:** the exact approval, change, exception or retirement decision.
- **Trigger:** the event that starts the decision, such as a schema change, new use case, release candidate or policy finding.
- **Cadence or time expectation:** whether the decision is event-driven, recurring or subject to a target response time.
- **Required evidence:** the artifacts needed to decide, such as lineage, test results, impact analysis, policy mapping, owner confirmation or rollback plan.
- **RACI assignments:** one A, one or more explicit Rs where work is required, selective Cs and Is.
- **Decision record:** where the outcome, conditions, approver and effective date are stored.
- **Escalation path:** what happens when authority, evidence, capacity or agreement is missing.
- **Review trigger:** the circumstances under which the RACI itself must be reconsidered.

This turns the matrix into a practical interface between roles. It also prevents a common governance failure: a role is nominally accountable but lacks the evidence, capacity or authority required to make the decision.

The Hub stakeholder/RACI matrix can support this work by making decisions and role assignments visible. It should not replace the underlying discussion about authority, evidence and escalation. A populated tool is not proof that the operating model works.

## The simplest viable implementation

A first implementation does not require an enterprise-wide matrix covering every governance activity. Start with a small set of high-friction decisions where ambiguity already causes delay, rework or risk.

A practical sequence is:

1. Select three to five recurring governance decisions.
2. Describe each decision in one sentence with a clear outcome.
3. Identify the evidence required before approval.
4. Name the role that has real authority to accept or reject.
5. Assign the roles that perform the preparation and implementation work.
6. Add only consultations that can change the outcome.
7. Add only recipients who must react to the result.
8. Define response times and an escalation path.
9. Test the RACI on the next real decision.
10. Revise it based on observed behavior rather than theoretical organization design.

Do not begin by listing every job title and trying to give each one a letter. That approach optimizes for representation instead of decision quality.

## Three governance decisions require three different RACIs

The role names below are illustrative. Organizations can use different titles, but the authority and work must remain explicit.

![Three governance decisions with different RACI assignments](images/playbooks/raci-for-data-governance-img3-en.png)

### Scenario 1: PII protection approval

A new attribute is classified as personal information and will be exposed through a governed analytical product.

The decision must answer:

- Is the classification correct?
- Is the intended use allowed?
- Which protection is required?
- Who implements and verifies the control?
- Which consumers are affected?

A viable assignment can be:

- **Accountable:** the Data Owner or an approved Privacy authority, according to the policy and delegated decision rights.
- **Responsible:** the Data Steward for classification context and evidence; the technical implementation role for masking, access control or other protection.
- **Consulted:** Privacy, Security and the Data Architect where legal interpretation, control design or cross-platform impact is relevant.
- **Informed:** affected consumers and Platform Operations.

The important point is not that the Data Owner must always be A. The policy must specify whether the Data Owner can approve the residual risk or whether a Privacy authority owns that decision. The RACI must represent the actual authority.

Evidence can include classification rationale, intended use, source and downstream lineage, the proposed technical control, test results, exception conditions and the effective date.

### Scenario 2: governed mart or Data Product go-live

A governed mart is ready for production use. The decision is not merely whether code can be deployed. It is whether the product is fit for its declared purpose and operating commitments.

A viable assignment can be:

- **Accountable:** the Data Product accountable role or a clearly designated business owner.
- **Responsible:** the engineering or platform delivery team that implements, tests and deploys the release.
- **Consulted:** the Data Architect, Data Steward and Security where architecture conformance, metadata completeness and protection controls can affect approval.
- **Informed:** consumers and support teams.

Required evidence can include the Data Contract, grain, ownership, quality results, lineage, access model, SLA, rollback plan, known limitations and support readiness.

The Data Architect can recommend that the release does not conform to an approved pattern. The Steward can identify missing definitions or ownership. Security can identify an unacceptable control gap. These inputs can change the decision, but they do not automatically make those roles accountable for the product outcome.

### Scenario 3: KPI definition change

A KPI business definition changes because the underlying commercial rule, population, time window or exclusion logic has changed.

A viable assignment can be:

- **Accountable:** the KPI business owner.
- **Responsible:** the Data Steward who coordinates the governed definition and the implementation team that changes the semantic model, transformation or report logic.
- **Consulted:** the Data Architect and affected domain experts.
- **Informed:** report owners and consumers.

Evidence can include the old and new definition, rationale, grain, population, effective date, historical treatment, impacted reports, validation results and communication plan.

The KPI owner is accountable for the business meaning. The implementation team is responsible for deploying the change correctly. The Steward ensures that the approved definition and metadata are updated. These are distinct obligations.

## Collaboration with adjacent roles

RACI works only when role boundaries align with adjacent operating practices.

### Data Owner and Data Steward

Ownership and stewardship should already be defined in the broader governance model. RACI does not replace those definitions. It applies them to specific decisions.

The Data Owner commonly holds accountability for business use, priority, risk acceptance within delegated authority and domain outcomes. The Data Steward commonly performs coordination, evidence preparation, metadata maintenance and issue follow-up. That pattern is useful, but it is not universal. The final assignment depends on the decision object and policy.

### Data Architect

The Data Architect is typically Consulted where a decision affects architecture standards, cross-domain interfaces, Data Contracts, semantic consistency, platform boundaries or reversibility. The Architect may be Responsible for architecture analysis or an Architecture Decision Record. The Architect should be Accountable only where the organization has explicitly delegated the relevant architecture decision.

### Privacy and Security

Privacy and Security should not be added automatically to every data decision. They are Consulted when expertise is required and may be Accountable when a policy or regulation assigns them formal authority. The RACI must distinguish expert input from formal approval authority.

### Platform Operations and engineering

Platform and engineering roles are often Responsible for implementation, deployment, monitoring and rollback. They may be Consulted about feasibility and supportability. They should not be made accountable for business meaning or permitted use simply because they operate the technology.

### Governance Lead and CoE

A Governance Lead or CoE can define standards, facilitate the matrix, detect missing roles and escalate structural gaps. It should not become the default A for every unresolved decision. Central governance that absorbs all accountability creates a bottleneck and weakens domain ownership.

The complete design of a Governance CoE belongs in the dedicated `governance-coe` playbook.

## Common anti-patterns

### Several Accountable roles

Multiple As make escalation ambiguous and allow each party to assume that another party owns the final outcome. Split the decision or resolve the authority boundary.

### No Responsible role

A decision can have a clear approver and still fail because nobody owns the preparation or implementation work. Assign R only to roles with concrete deliverables.

### Job titles instead of decision rights

“Director,” “Manager” or “Lead” does not explain the authority being exercised. Define the decision scope and delegated authority behind the title.

### C and I assigned to everybody

Universal consultation delays work. Universal information creates noise. Include a role only when its input can change the decision or it must act on the result.

### Committee accountability

A committee can review evidence or provide consultation. It should not be used to conceal who can accept or reject the outcome. Where collective approval is legally or formally required, define the committee’s decision rule and name the chair or authority responsible for completing the decision record and escalation.

### RACI created once and never revisited

An old matrix can preserve obsolete authority after a reorganization, domain change, platform migration or regulatory change. A stale RACI is more dangerous than no matrix because it gives false confidence.

### Accountable means “does all the work”

This overloads senior roles and weakens execution ownership. The A owns the decision and outcome; the R performs the work.

### Consultation without a deadline

A consulted role can unintentionally block a decision forever. Define the expected response time and escalation path.

## Re-negotiate RACI when the operating context changes

RACI is a maintained operating contract. It must change when the relationship between work, authority and risk changes.

![RACI renegotiation lifecycle](images/playbooks/raci-for-data-governance-img4-en.png)

Review the matrix when:

- the organization changes;
- a domain is split, merged or transferred;
- platform responsibility changes;
- a new regulation, policy or material risk appears;
- decisions are repeatedly delayed;
- escalation routinely bypasses the defined path;
- an assigned role lacks capacity;
- duplicate approvals emerge;
- required actions are repeatedly missed;
- the nominal A does not have real authority;
- consumers or support teams consistently receive information too late.

The review should compare the documented model with actual behavior:

1. Observe the trigger.
2. Identify the failing decision.
3. Review who actually performs the work and who has authority.
4. Update the RACI and required evidence.
5. Confirm the escalation path.
6. Communicate the changed contract.
7. Verify it during the next real decision.

Measure whether the change improves decision time, unresolved escalations, duplicate approvals, missing actions and stakeholder feedback. The objective is not matrix compliance for its own sake. The objective is a faster, clearer and auditable decision path.

## Decision guidance

Use the following tests before approving a RACI row:

- **One-A test:** Is exactly one role accountable for this decision?
- **Authority test:** Can that role actually accept, reject and escalate?
- **Work test:** Is every Responsible role linked to a concrete deliverable?
- **Evidence test:** Is the decision package defined?
- **Consultation test:** Can each C materially change the outcome?
- **Information test:** Must each I react to the result?
- **Timing test:** Are response expectations and deadlines clear?
- **Escalation test:** Is the next authority known when the decision stalls?
- **Audit test:** Can the organization later reconstruct what was decided, by whom, on which evidence and under which policy version?
- **Change test:** Are triggers defined for revisiting the matrix?

A RACI that fails these tests should not be expanded with more roles. It should be simplified or decomposed.

## Key recommendations

1. Model decisions and concrete work, not departments.
2. Require exactly one Accountable role for each decision.
3. Separate decision ownership from execution responsibility.
4. Use Consulted only when input can change the decision.
5. Use Informed only when the result affects downstream work.
6. Connect every row to evidence, timing, a decision record and escalation.
7. Start with a small set of recurring high-friction decisions.
8. Validate the matrix through real decisions, not workshops alone.
9. Re-negotiate assignments when authority, capacity, risk or platform ownership changes.
10. Prevent central governance or committees from becoming the default owner of every unresolved decision.

## What comes next

RACI clarifies how a decision moves across roles. It does not by itself resolve where business accountability ends and product accountability begins. The next story in the Roles and Decision Rights series, `data-product-owner-vs-data-owner`, separates those two roles and shows how their decision scopes interact without creating duplicate ownership.
