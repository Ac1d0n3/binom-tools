---
title: "The Missing Pieces – Part 5: Policy & Access Governance"
description: "Why documented policies do not automatically become operational controls – and how organizations can connect intent, identity, access decisions, enforcement, review and evidence."
author: Thomas Lindackers
category: Data Governance
tags:
  - the-missing-pieces
  - policy-governance
  - access-governance
  - identity-governance
  - access-control
  - least-privilege
  - data-security
  - data-compliance
  - data-governance
order: -1
hero: images/playbooks/mp-access-hero.png
series: missing-pieces
seriesPart: 5
seriesTitle: The Missing Pieces
---

## A policy can be complete on paper and incomplete in practice

Most organizations do not lack policies.

They have security standards, data classifications, privacy requirements, retention rules, access models, approval processes and role definitions.

They may also have mature technical capabilities:

- identity providers and single sign-on,
- role-based access control,
- attribute-based policies,
- row-level and column-level security,
- masking and encryption,
- entitlement management,
- access request workflows,
- access reviews and recertification,
- audit logs and monitoring.

These capabilities are important. They provide the building blocks for controlled access.

The open question is whether the original policy intent remains connected to the controls that are actually executed across systems, platforms and business processes.

A policy may state:

> *Customer contact data may only be used by authorized teams for approved business purposes.*

But operational implementation still requires many decisions:

- Which data assets contain customer contact data?
- Which identities represent authorized users, applications and service accounts?
- Which business roles require access?
- At what level should access be granted: domain, catalog, schema, table, row or column?
- Should the data be visible, masked or aggregated?
- Who approves the request?
- How long should access remain valid?
- Which exceptions are allowed?
- How is continued need reviewed?
- What evidence demonstrates that the policy is working?

The policy is the intention.

The control environment is the implementation.

> **Governance becomes operational when policy intent can be traced to access decisions, technical enforcement, review and evidence.**

This playbook does not argue that policies, IAM systems or platform controls are ineffective. It examines the possible gap between documenting a rule and making that rule consistently executable across a changing data estate.

## Policy governance and access governance are connected – but not identical

The terms overlap, yet they solve different parts of the problem.

| Capability | Primary purpose | Typical questions |
| --- | --- | --- |
| Policy Governance | define, approve, communicate and maintain rules and obligations | What should be allowed, required, restricted or reviewed? |
| Identity Governance | manage the lifecycle of people, partners, contractors, applications and service identities | Who or what is requesting access, and is the identity still valid? |
| Access Governance | request, approve, provision, review and remove entitlements | Who should have access to which resource, for what purpose and for how long? |
| Access Control | technically enforce permissions and restrictions | What can this identity actually see or do in this system? |
| Data Security Controls | protect sensitive content through masking, filtering, encryption and related mechanisms | Which data values may be exposed under which conditions? |
| Monitoring & Detection | observe usage, anomalies, policy violations and control effectiveness | Is access being used as expected, and where is risk emerging? |
| Audit & Evidence | retain traceability of decisions, changes, reviews and enforcement | Can the organization demonstrate who approved what, when and why? |
| Exception Management | govern justified deviations from standard policy | Which exception exists, who accepted the risk and when must it be reviewed? |

A policy repository can document intent without enforcing access.

An IAM platform can manage identities without understanding the full business meaning of every data asset.

A data platform can enforce privileges without knowing whether the granted access still reflects current business need.

A data catalog can expose classifications and owners without being the system that provisions entitlements.

The value emerges when these capabilities are connected by a clear operating model.

## The policy-to-control chain

A practical policy lifecycle can be viewed as five connected stages:

1. **Policy defined**  
   Intent, scope, obligations, prohibited use and expected controls are approved.

2. **Policy assigned**  
   Owners, stewards, approvers, reviewers and technical control owners are identified.

3. **Access implemented**  
   Roles, privileges, filters, masks, entitlements and platform controls are configured.

4. **Access reviewed**  
   Usage, continued business need, role changes, conflicts and exceptions are evaluated.

5. **Access enforced and improved**  
   Unnecessary access is removed, violations are remediated, exceptions are controlled and the policy or control design is updated when needed.

Each stage can be mature on its own while the end-to-end chain remains incomplete.

Examples:

- a policy exists, but no technical owner is accountable for implementation;
- a role is assigned, but the permissions behind the role differ across platforms;
- access is provisioned, but no expiry or review date exists;
- reviews are completed, but the result is not applied to the target system;
- exceptions are approved, but they remain active after the original reason disappears;
- technical controls work, but users do not understand why access was granted or denied;
- evidence exists in several systems, but no one can reconstruct the full decision path.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-access-img1-en.png"
        alt="Policy and access governance lifecycle showing the path from policy definition and ownership to implementation, review and enforcement, including common gaps between policy and practice"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A documented policy becomes operational only when responsibilities, technical controls, reviews, exceptions and evidence remain connected over time.
    </figcaption>
</figure>

## The gap is often not a missing control – but a missing connection

Modern platforms provide many access-control mechanisms.

Snowflake supports role-based and object-level privilege models. Databricks Unity Catalog combines privileges, ownership, attribute-based policies, row filters, column masks and workspace restrictions. AWS Lake Formation supports fine-grained and tag-based controls. Cloud IAM platforms provide roles, conditions, temporary credentials and access analysis.

The challenge is rarely that no technical option exists.

The challenge is deciding:

- which control should implement which policy,
- where the control should be enforced,
- how the same intent remains consistent across multiple platforms,
- who owns the mapping between policy and implementation,
- how exceptions are represented,
- how changes are tested,
- how evidence is collected,
- how obsolete access is removed.

For example, a classification such as `Confidential – Customer PII` may need to influence:

- catalog visibility,
- data-product access requests,
- warehouse privileges,
- row filters,
- column masking,
- report-level security,
- export permissions,
- API access,
- machine-learning workspaces,
- service-account permissions,
- retention and deletion controls.

The classification is not the control itself.

It is context that should drive one or more controls.

> **A governed label becomes valuable when its meaning is translated consistently into operational behavior.**

## From identity to access control

Access governance starts before a permission is granted.

It begins with identity.

An organization may need to govern:

- employees,
- managers,
- external partners,
- contractors,
- temporary workers,
- guests,
- service accounts,
- applications,
- automation identities,
- data-sharing recipients,
- AI agents and other machine identities.

The access decision then depends on context such as:

- business role,
- organizational unit,
- project or domain,
- geographic location,
- employment status,
- device or network context,
- data classification,
- purpose of use,
- requested duration,
- risk level,
- segregation-of-duties constraints,
- contractual or regulatory obligations.

This creates a lifecycle:

Identity  
→ Role, group and attributes  
→ Access request or automated entitlement  
→ Policy and risk evaluation  
→ Approval or automated decision  
→ Provisioning  
→ Usage monitoring  
→ Review and recertification  
→ Change or removal

The process should not assume that every access request requires a long manual approval chain.

Low-risk, well-defined access can often be automated within approved guardrails.

High-risk or exceptional access may require additional review, limited duration and stronger evidence.

The goal is proportional governance:

> **The control effort should reflect the sensitivity, scope, duration and potential impact of the access.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/mp-access-img2-en.png"
        alt="Identity-to-access-control lifecycle covering identities, roles, access requests, decisions, provisioning, monitoring, governance controls, risks and outcomes"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Effective access governance connects identity, business need, policy evaluation, technical provisioning, continuous monitoring and timely removal.
    </figcaption>
</figure>

## Least privilege is a direction – not a one-time configuration

Least privilege is a widely accepted security principle: provide only the permissions required to perform the intended task.

In practice, least privilege is difficult to achieve through initial design alone.

Access needs change because:

- employees move between teams,
- projects end,
- responsibilities expand or shrink,
- applications are replaced,
- data products evolve,
- emergency access is granted,
- temporary work becomes permanent,
- service accounts accumulate new responsibilities,
- role definitions become broader over time.

An initially reasonable permission can become excessive later.

This means least privilege requires a lifecycle:

Define  
→ Grant  
→ Observe  
→ Review  
→ Reduce or remove  
→ Reassess

Usage evidence can help reviewers distinguish between access that is actively required and access that has become dormant.

But usage alone is not sufficient.

An entitlement may be used and still be inappropriate.

An entitlement may be unused during a quiet period and still be required for a valid seasonal or emergency process.

Business context remains necessary.

## Roles help scale access – but roles can also hide complexity

Role-based access control is useful because permissions can be assigned to roles instead of being managed individually for every user.

However, role design creates its own governance questions:

- What business responsibility does the role represent?
- Which technical permissions does it contain?
- Are the permissions consistent across environments?
- Is the role too broad?
- Does the role combine conflicting duties?
- Who owns the role definition?
- Who may request or approve the role?
- How often is the role reviewed?
- Are inherited permissions visible?
- Can users understand what they receive when the role is assigned?

Over time, organizations may develop:

- role explosion – too many narrowly defined roles,
- role accumulation – users retain roles from previous responsibilities,
- role inflation – existing roles receive additional permissions because creating a new model is difficult,
- nested complexity – groups and inherited roles make effective access hard to explain.

The answer is not necessarily to abandon roles.

It may be to combine roles with attributes, conditions, time limits, tags and explicit ownership.

## RBAC, ABAC and data-level controls serve different purposes

Different access-control models can complement each other.

| Control model | Strength | Governance question |
| --- | --- | --- |
| Role-Based Access Control (RBAC) | scalable assignment through job or responsibility-based roles | Are roles understandable, current and appropriately scoped? |
| Attribute-Based Access Control (ABAC) | dynamic decisions based on identity, resource and contextual attributes | Are attributes governed, reliable and consistently interpreted? |
| Policy-Based Access Control | centralized rules evaluate access against defined policy logic | Who owns the policy logic and how is it tested? |
| Row-Level Security | limit which records a user can see | Is the filter logic consistent across tools and use cases? |
| Column-Level Security | restrict access to sensitive fields | Are sensitive columns classified completely and kept current? |
| Dynamic Masking | show transformed or hidden values depending on context | Do users understand whether they are seeing original, masked or aggregated values? |
| Purpose- or Consent-Based Controls | restrict use according to approved purpose or legal basis | Can purpose be represented and enforced across the full data path? |
| Time-Bound Access | expire access automatically after a defined period | Is the duration aligned with the business need, and can it be renewed with evidence? |

No single model solves every scenario.

The important governance question is whether the organization can explain why a particular control model was chosen and how it implements the intended policy.

## Access to data is not only access to tables

A data-governance perspective must consider the full consumption path.

Data can be accessed through:

- warehouse queries,
- lakehouse notebooks,
- semantic models,
- BI dashboards,
- report subscriptions,
- exports,
- spreadsheets,
- APIs,
- applications,
- reverse ETL,
- data shares,
- machine-learning features,
- AI assistants and agents.

Access to a dashboard may be restricted while the underlying semantic model is broadly accessible.

A user may not have direct table access but may be able to export detailed records from a report.

A masked warehouse column may appear unmasked in a replicated downstream store.

An application may use a service account whose permissions exceed the needs of individual users.

A data share may continue after the original collaboration ends.

Therefore, the relevant question is not only:

> *Who can query this table?*

It is also:

> *Through which channels can this information be viewed, downloaded, combined, redistributed or used by automated systems?*

## Access reviews are decisions – not checkbox exercises

Periodic access reviews can help confirm whether users, groups, guests, applications and privileged roles still require access.

A review is valuable when the reviewer has enough context to decide.

A weak review may show only:

`User: Maria Example`  
`Group: FIN_DATA_READ`  
`Decision: Approve / Deny`

A stronger review may also show:

- business purpose,
- role and department,
- data sensitivity,
- requested scope,
- original approver,
- age of the entitlement,
- last usage or usage pattern,
- related project or contract,
- known segregation-of-duties conflicts,
- expiry date,
- owner recommendation,
- impact of removal.

The objective is not to make every review form large and complex.

It is to provide the minimum context required for a responsible decision.

Review fatigue is a real operational risk.

If reviewers receive hundreds of poorly explained entitlements, approval can become a routine confirmation rather than a meaningful control.

Prioritization can help:

- critical access first,
- privileged access more frequently,
- high-risk exceptions separately,
- low-risk standard access through automation and sampling,
- dormant or anomalous access highlighted,
- reviews routed to people who understand the business need.

## Exceptions should be governed as first-class objects

No policy model covers every legitimate business situation.

Exceptions may be necessary because of:

- urgent incident response,
- migration projects,
- regulatory investigations,
- temporary cross-functional work,
- vendor support,
- legacy-system constraints,
- business continuity,
- unresolved technical limitations.

The existence of an exception is not automatically a governance failure.

An unmanaged exception is the risk.

A governed exception should normally include:

| Context | Example |
| --- | --- |
| Policy or control affected | Customer PII access policy |
| Business justification | Temporary migration validation |
| Scope | Named project team; selected datasets only |
| Risk assessment | Limited export risk; monitored environment |
| Compensating controls | Logging, restricted workspace, no external sharing |
| Approver | Data Owner and Security Owner |
| Start date | 15 July 2026 |
| Expiry date | 30 September 2026 |
| Review trigger | Project milestone or scope change |
| Evidence | approval record, access logs and closure confirmation |

An exception without an end date can quietly become a permanent alternative policy.

> **Temporary access should have an explicit lifecycle, not an implicit memory.**

## The policy owner, data owner and technical owner do not have the same job

Policy and access governance often involve several responsibilities.

| Role | Primary responsibility |
| --- | --- |
| Policy Owner | defines intent, scope, obligations, exceptions and review expectations |
| Data Owner | decides acceptable use, risk posture and access principles for a data domain or product |
| Data Steward | maintains business context, classifications, access guidance and issue coordination |
| Identity / IAM Owner | manages identity lifecycle, entitlement processes, roles and governance controls |
| Platform Owner | implements and operates platform-specific privileges, filters, masking and audit capabilities |
| Application / Report Owner | governs access and downstream behavior in the consumption layer |
| Security / Privacy | provides control requirements, risk guidance, monitoring and assurance |
| Approver | evaluates a specific request or exception within delegated authority |
| Reviewer | confirms continued need and appropriateness of existing access |
| Auditor / Assurance | evaluates whether controls are designed, operating and evidenced as expected |

One person may perform several roles in a smaller organization.

The important point is clarity:

- Who defines the rule?
- Who decides access?
- Who implements the control?
- Who reviews continued need?
- Who resolves conflicts?
- Who verifies effectiveness?

Without this separation, an access problem can move between teams without a clear owner.

## Business-friendly access governance matters

Access governance must protect data without making appropriate access unnecessarily difficult.

If the approved route is slow, unclear or overly technical, users may seek alternative paths:

- requesting broader roles than needed,
- asking colleagues to export data,
- creating local copies,
- sharing credentials or files outside the intended process,
- reusing old access because obtaining new access is difficult.

This does not justify weak controls.

It supports a usability principle:

> **The governed path should be easier to understand than the workaround.**

A useful access request experience can explain:

- what the data product contains,
- which classification applies,
- intended and prohibited uses,
- available access levels,
- expected approval path,
- estimated duration,
- owner and support contact,
- whether access is time-bound,
- what will be logged or reviewed.

Business language should come before technical role names where possible.

Instead of asking a user to choose between cryptic groups, the experience could start with purpose:

> *I need aggregated customer revenue for monthly regional planning.*

The system can then map that purpose to the appropriate product, role and control.

## Policy-as-code can improve consistency – but code does not remove governance

Policy-as-code and infrastructure-as-code can make access rules versioned, testable, reviewable and repeatable.

This can improve:

- consistency across environments,
- peer review,
- automated validation,
- deployment traceability,
- rollback,
- drift detection,
- evidence generation.

But policy-as-code still requires decisions about:

- policy intent,
- business terminology,
- ownership,
- exceptions,
- risk acceptance,
- test scenarios,
- review cadence.

A technically valid rule can still implement the wrong business interpretation.

Code improves execution discipline.

It does not replace accountability.

## Effective access is more important than configured access

In complex environments, access may be inherited through:

- nested groups,
- role hierarchies,
- direct grants,
- object ownership,
- secondary roles,
- workspace permissions,
- application entitlements,
- shared credentials,
- service principals,
- delegated administration,
- data shares.

The configured permission is only one part of the picture.

Organizations also need to understand effective access:

> *What can this identity actually access after all grants, inheritance, conditions, filters and exceptions are evaluated?*

This is essential for:

- access reviews,
- incident investigation,
- policy validation,
- segregation-of-duties analysis,
- data exposure assessment,
- audit evidence.

Effective-access analysis should include machine identities as well as people.

## Evidence should be designed into the process

Audit evidence is easier to produce when traceability is captured during normal operations.

Useful evidence may include:

- approved policy version,
- control mapping,
- identity and role state,
- access request and business justification,
- approval decision,
- provisioning result,
- policy evaluation outcome,
- exception record,
- review and recertification decision,
- access usage or monitoring evidence,
- revocation or expiry confirmation,
- change history.

The objective is not to store every event forever.

Evidence should be proportionate to risk, policy and regulatory need.

The stronger principle is:

> **A control is easier to trust when its decision path can be reconstructed.**

## Measuring policy and access governance

Counting policies or roles does not show whether governance is effective.

Useful measures may include:

| Measure | What it may indicate |
| --- | --- |
| Policy-to-Control Coverage | whether critical policy requirements are linked to implemented controls |
| Classification-to-Control Coverage | whether sensitive assets receive the expected protection |
| Access Request Lead Time | whether appropriate access can be obtained efficiently |
| Automated Approval Rate | whether standard low-risk access is handled within guardrails |
| Excessive Access Findings | where permissions exceed defined need or policy |
| Dormant Access Rate | how much access appears unused and may need review |
| Orphaned Access | access belonging to inactive identities, completed projects or missing owners |
| Review Completion | whether required access reviews are completed on time |
| Review Decision Quality | whether reviewers change or remove access rather than approving everything by default |
| Revocation Latency | how quickly obsolete or non-compliant access is removed |
| Exception Age | whether temporary exceptions remain open beyond their intended period |
| Expired Access Removal | whether time-bound access is actually withdrawn |
| Policy Drift | where technical implementation differs from approved intent |
| Evidence Completeness | whether decisions and enforcement can be reconstructed |
| User Experience | whether users understand how to request, use and relinquish access |

Metrics require interpretation.

A high denial rate is not automatically good governance.

A very low denial rate is not automatically weak governance.

The outcome matters:

> **Do the right identities receive the right access, for a justified purpose, for an appropriate period, with understandable controls and reliable evidence?**

## Common anti-patterns

### Policy as documentation only

The policy is approved and published, but no mapping identifies which technical controls implement it.

### Access by historical accumulation

Users retain roles and permissions from previous teams, projects and responsibilities.

### Broad roles as the default solution

A large role is granted because it is faster than identifying the minimum required access.

### Manual approval without useful context

Approvers see technical group names but not business purpose, sensitivity, duration or risk.

### Review as mass confirmation

Hundreds of entitlements are approved because reviewers lack context or time.

### Permanent temporary access

Emergency or project access has no expiry, owner or closure check.

### Human identities governed, machine identities ignored

Service accounts, applications and automation retain broad access without equivalent review.

### Control at one layer only

The warehouse is protected, but exports, semantic models, reports, APIs or downstream copies create alternative access paths.

### Classification without enforcement

Sensitive data is labeled correctly, but the label does not influence access, masking, monitoring or review.

### Security without usability

The governed access path is so slow or difficult that users create informal alternatives.

### Tool ownership without policy ownership

The IAM or platform team operates the technology but cannot decide the business meaning of acceptable access.

## A practical operating model

One possible approach is:

1. **Define policy intent in business language**  
   State purpose, scope, prohibited use, control expectations, exception rules and review requirements.

2. **Identify critical data and access paths**  
   Connect classifications, data products, semantic models, reports, exports, APIs and machine consumers.

3. **Assign accountable roles**  
   Name policy owners, data owners, stewards, approvers, IAM owners, platform owners and reviewers.

4. **Translate policy into control patterns**  
   Define when to use RBAC, ABAC, masking, row filters, time-bound access, approval or automated decisions.

5. **Create understandable request paths**  
   Let users request access through business purpose and data-product context rather than only technical groups.

6. **Automate standard access within guardrails**  
   Reduce manual work for repeatable, low-risk scenarios while preserving traceability.

7. **Treat exceptions explicitly**  
   Require justification, scope, owner, compensating controls, expiry and review.

8. **Monitor effective access and usage**  
   Detect excessive, dormant, orphaned, anomalous and policy-inconsistent access.

9. **Review proportionately**  
   Focus human attention on sensitive, privileged, unusual or high-impact access.

10. **Apply review outcomes**  
   Ensure deny, revoke, reduce or expire decisions reach the target systems.

11. **Capture evidence by design**  
   Preserve the decision path and control result without relying on manual reconstruction.

12. **Improve policy and controls together**  
   Use incidents, feedback, access patterns and exceptions to refine both intent and implementation.

## Practical questions for teams

For a critical data product, policy or access path, ask:

1. **Which approved policy governs this access?**
2. **Can we trace the policy requirement to the technical control that enforces it?**
3. **Are owner, steward, approver and technical control owner clearly identified?**
4. **Can users understand what access they are requesting in business terms?**
5. **Is the requested scope limited to what is needed?**
6. **Is the duration appropriate, and can temporary access expire automatically?**
7. **Are row, column, masking, export and downstream-consumption controls aligned?**
8. **Do machine identities receive the same governance attention as human identities?**
9. **Can reviewers see business purpose, sensitivity, usage and risk?**
10. **Are exceptions visible, time-bound and reviewed?**
11. **Can we determine effective access after inheritance and all policy conditions?**
12. **Are review decisions actually applied to the target platform?**
13. **Can the full decision and enforcement path be reconstructed for audit?**
14. **Do users know why access was granted, denied, masked or removed?**
15. **Does the governed process enable legitimate work without encouraging workarounds?**

These questions do not require every organization to use the same IAM product, policy engine or approval model.

They test whether documented intent remains connected to operational reality.

## Conclusion

Policy and access governance are not missing technologies.

Modern identity platforms, data platforms and security tools already provide powerful capabilities for roles, attributes, privileges, masking, approvals, reviews, monitoring and evidence.

The possible missing piece is the connection between them.

A policy does not enforce itself.

A classification does not automatically protect every downstream copy.

A role assignment does not prove continued business need.

An access review does not create value if the reviewer lacks context or the result is never applied.

A technical control does not demonstrate policy effectiveness unless its purpose, ownership and evidence are understandable.

The operational chain is:

> **policy intent → governed identity → justified access need → decision → technical enforcement → monitoring → review → removal or renewal → evidence → improvement**

The central question is therefore:

> **Is the policy only documented – or is it consistently executed?**

Perhaps trusted access is not the state in which everyone has the same permissions.

Perhaps it is the state in which access can be explained:

- who or what has access,
- to which data,
- for which purpose,
- under which policy,
- with which restrictions,
- for how long,
- approved by whom,
- reviewed when,
- and removed when it is no longer justified.

## Further resources

- [NIST SP 800-207: Zero Trust Architecture](https://csrc.nist.gov/pubs/sp/800/207/final)
- [Microsoft Learn: What are Microsoft Entra access reviews?](https://learn.microsoft.com/en-us/entra/id-governance/access-reviews-overview)
- [Microsoft Learn: Plan a Microsoft Entra access reviews deployment](https://learn.microsoft.com/en-us/entra/id-governance/deploy-access-reviews)
- [Microsoft Learn: Data governance with Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-governance-overview)
- [Microsoft Learn: Data governance roles and permissions in Microsoft Purview](https://learn.microsoft.com/en-us/purview/data-governance-roles-permissions)
- [AWS Documentation: Security best practices in IAM](https://docs.aws.amazon.com/IAM/latest/UserGuide/best-practices.html)
- [AWS Documentation: Policies and permissions in IAM](https://docs.aws.amazon.com/IAM/latest/UserGuide/access_policies.html)
- [AWS Documentation: Lake Formation tag-based access control](https://docs.aws.amazon.com/lake-formation/latest/dg/tag-based-access-control.html)
- [Google Cloud Documentation: Use IAM securely](https://cloud.google.com/iam/docs/using-iam-securely)
- [Google Cloud Documentation: Privileged Access Manager overview](https://cloud.google.com/iam/docs/pam-overview)
- [Snowflake Documentation: Overview of access control](https://docs.snowflake.com/en/user-guide/security-access-control-overview)
- [Snowflake Documentation: Access control best practices](https://docs.snowflake.com/en/user-guide/security-access-control-considerations)
- [Databricks Documentation: Access control in Unity Catalog](https://docs.databricks.com/aws/en/data-governance/unity-catalog/access-control/)
- [Databricks Documentation: Unity Catalog permissions model concepts](https://docs.databricks.com/aws/en/data-governance/unity-catalog/access-control/permissions-concepts)
