---
title: Access & Security Governance
description: A practical operating model for traceable, role- and policy-based data access — with clear accountability, least privilege, continuous reviews and auditable controls.
author: Thomas Lindackers
category: Data Governance
tags:
  - data-governance
  - access-governance
  - security-governance
  - iam
  - rbac
  - abac
  - least-privilege
  - segregation-of-duties
  - data-security
  - audit
order: -1
publishedAt: 2026-06-08
series: governance-pillars
seriesPart: 8
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/access-gov-hero.png
---

## The right access. For the right people. At the right time.

Data access is necessary for people to work, analyze and make decisions.

At the same time, one of the largest governance risks emerges when permissions:

- accumulate over time
- are granted directly to individual users
- remain after role changes
- are managed differently across platforms
- are not reviewed regularly
- expose sensitive data without business approval
- combine technical administration with business data use
- belong to service accounts without clear ownership
- are bypassed by self-service tools
- remain active as permanent exceptions

**Access & Security Governance** connects identities, business accountability, data classification, technical policies, monitoring and regular recertification into a controlled lifecycle.

> *Access is well governed when it is traceable, purpose-bound, least-privileged, time-appropriate and continuously reviewable.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/access-gov-en.png"
        alt="Access and Security Governance covering identification, classification, authorization, enforcement, monitoring, review and access revocation"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Access & Security Governance connects identities, roles, policies, technical controls and continuous reviews into a traceable access model.
    </figcaption>
</figure>

## Security protects systems — governance controls decisions

Security and governance overlap, but they are not the same.

Security addresses questions such as:

- How is an identity authenticated?
- How is data encrypted?
- How are attacks detected?
- How are systems technically protected?

Access Governance adds:

- Who is allowed to see which data?
- On what business basis?
- For which purpose?
- Who approves access?
- How long should it remain valid?
- Which sensitivity level applies?
- Which conflicts arise from combined roles?
- How is continued need confirmed regularly?
- How is access removed in a controlled way?

A successful login proves only that an identity is known. It does not prove that every available data permission is justified.

## The Access & Security Operating Model

A robust lifecycle can be structured in seven steps.

```text
1. Identify identities, roles, systems and data assets
        ↓
2. Classify data, risk and access levels
        ↓
3. Approve access based on business need
        ↓
4. Provision permissions through governed policies
        ↓
5. Monitor usage, anomalies and exceptions
        ↓
6. Recertify access regularly
        ↓
7. Revoke outdated or risky permissions
        ↺
```

This lifecycle applies not only to human users, but also to:

- service accounts
- technical users
- pipelines
- APIs
- applications
- bots
- automations
- external partners
- temporary project accounts

## 1. Identify identities, roles, systems and data assets

Governance requires transparency into who or what has access.

An access inventory can include:

| Metadata field | Example |
| --- | --- |
| **Identity** | `thomas.lindackers@example.org` |
| **Identity Type** | Employee |
| **Business Role** | Finance Analyst |
| **Technical Role** | `SNOWFLAKE_FINANCE_ANALYST` |
| **Data Domain** | Finance |
| **System** | Snowflake |
| **Environment** | Production |
| **Access Level** | Read |
| **Sensitivity Scope** | Confidential |
| **Access Owner** | Finance Data Owner |
| **Approval Date** | 2026-07-01 |
| **Expiry Date** | 2026-12-31 |
| **Last Review** | 2026-07-10 |
| **Justification** | Monthly financial reporting |

Without the connection between business roles and technical permissions, reviews remain difficult to understand.

## 2. Classify data and access levels

Not every asset requires the same access level.

A possible model:

| Sensitivity level | Typical data | Default principle |
| --- | --- | --- |
| **Public** | published information | broadly accessible |
| **Internal** | general internal data | employee access |
| **Confidential** | internal business and customer data | role- and purpose-based |
| **Restricted** | sensitive PII, financial or HR data | tightly limited access |
| **Highly Restricted** | highly critical or regulated data | explicit approval and strong controls |

The sensitivity level should influence technical controls.

Example:

```text
PII Category: direct_identifier
        +
Sensitivity: restricted
        ↓
Required Controls:
- role-based access
- dynamic masking
- audit logging
- quarterly review
- explicit Data Owner approval
```

This turns PII and sensitivity metadata into operational access controls.

## 3. Approve access based on business need

A good access request answers:

- Who is requesting access?
- Which role or application needs it?
- Which data product or asset is required?
- Which level of access?
- For which purpose?
- For how long?
- Which sensitivity class is involved?
- Which training or obligations are required?
- Who approves?
- Does the request create a conflict with existing permissions?

Example:

```yaml
requestor: thomas.lindackers@example.org
business_role: Finance Analyst
asset: finance.certified_revenue
access_level: read
purpose: Monthly management reporting
duration: 180 days
approval:
  - manager
  - data_owner
controls:
  - no_export
  - masked_personal_data
```

Access should be requested through roles wherever possible.

Direct user grants make the following more difficult:

- transparency
- reuse
- recertification
- role changes
- revocation
- audit

## RBAC, ABAC and policy-based access

### Role-Based Access Control — RBAC

RBAC assigns permissions to roles.

```text
User
  ↓
Business Role
  ↓
Technical Role
  ↓
Permissions
  ↓
Data Asset
```

Benefits:

- understandable
- reusable
- easier to approve
- easier to recertify

Risks:

- too many roles
- roles grow over time
- exceptions create role explosion
- business and technical roles become mixed

### Attribute-Based Access Control — ABAC

ABAC evaluates attributes.

Examples:

- country
- organizational unit
- employment type
- sensitivity level
- project membership
- employment status
- data region
- purpose
- device or network

Example:

```text
Allow access when:

user.department = "Finance"
AND data.domain = "Finance"
AND data.sensitivity <= user.clearance
AND request.purpose = "Management Reporting"
```

ABAC supports more granular decisions, but also increases complexity and explainability requirements.

### Policy-based access

Policies connect business requirements with technical enforcement.

Example:

```text
Policy:
Restricted PII may only be used
for approved operational purposes.

Enforcement:
- approved role required
- dynamic masking by default
- unmask only with explicit entitlement
- export disabled
- all access logged
```

In practice, RBAC, ABAC and policies are often combined.

## 4. Enforce access technically

Technical access controls can operate at multiple layers.

| Layer | Typical control |
| --- | --- |
| **Identity Provider** | Single Sign-On, MFA, groups |
| **Cloud / Platform** | roles, policies, service principals |
| **Warehouse / Lakehouse** | grants, roles, row access, column policies |
| **dbt / Transformation** | governed deployments, separated environments |
| **Semantic Layer** | object-, row- or metric-level access |
| **Qlik** | Section Access, app and stream permissions |
| **Power BI** | workspace roles, Row-Level Security, Object-Level Security |
| **Tableau** | site, project, workbook and row-level rules |
| **Excel** | controlled data connections and source permissions |
| **API** | scopes, claims, tokens, rate limits |
| **Files / Exports** | encryption, expiry dates, download rules |

The best control depends on the use case.

A common mistake is controlling only platform access.

Example:

```text
User has access to BI workspace
        ↓
Can open report
        ↓
Can export underlying data
        ↓
Sensitive detail becomes available outside governed layer
```

Governance must therefore include export, download, API usage and local processing.

## Row-, column- and value-level access

### Row-Level Security

Example:

```text
Regional Manager
        ↓
sees only Region = "West"
```

### Column-Level Security

Example:

```text
Standard Analyst
        ↓
cannot see salary or email columns
```

### Dynamic Data Masking

Example:

```text
email = t***@example.org
```

### Purpose-based Access

Example:

```text
Customer Support
        ↓
may access contact data
only for active support cases
```

The more sensitive the data, the more important the combination of role, purpose, context and monitoring becomes.

## Least Privilege

**Least Privilege** means:

An identity receives only the permissions required for the current purpose.

This applies to:

- data access
- administrative permissions
- technical deployments
- service accounts
- API scopes
- export permissions
- masking exceptions
- production access

Least Privilege is not a one-time state.

Permissions change because of:

- role changes
- project completion
- team movement
- new responsibilities
- temporary coverage
- system migration
- organizational change

Least Privilege therefore requires recurring reviews and automated revocation.

## Segregation of Duties

Some permissions should not be combined.

Examples:

| Conflict | Risk |
| --- | --- |
| Change data + approve the same change | lack of independent control |
| Create users + approve permissions | bypass of approval process |
| Calculate KPI + certify it without review | uncontrolled metric |
| Access data + delete audit logs | evidence can be manipulated |
| Develop pipeline + deploy to production without review | uncontrolled change |
| Change masking policy + grant unmasked access | protection can be bypassed |

A SoD model should define:

- conflict rule
- affected roles
- risk level
- permitted exception
- compensating control
- expiry date
- review ownership

## Privileged Access Management

Administrative and privileged access requires additional controls.

Examples:

- Warehouse Administrator
- Cloud Administrator
- Security Administrator
- Database Owner
- Platform Root Account
- Break-glass Account

Suitable controls include:

- MFA
- time-bound activation
- Just-in-Time Access
- dual approval
- session logging
- separate admin identity
- no daily work with admin accounts
- automatic deactivation
- recurring recertification

Administrators can often do more technically than they need for a business purpose.

Governance therefore needs visibility into use and justification.

## Service accounts and technical identities

Technical identities are often less visible than human users.

A service account should have at least:

| Metadata field | Example |
| --- | --- |
| **Name** | `svc_dbt_prod` |
| **Type** | Service Account |
| **Owner** | Data Platform Team |
| **Purpose** | dbt Production Deployment |
| **Environment** | Production |
| **Permissions** | Create/Replace in governed schemas |
| **Secret Owner** | Platform Security |
| **Rotation** | every 90 days |
| **Last Used** | 2026-07-11 |
| **Expiry Date** | 2027-01-01 |
| **Emergency Process** | IAM Escalation |

Anti-patterns include:

- shared technical users
- unknown Owner
- non-expiring secrets
- permanent production admin rights
- no usage logs
- active accounts after project closure

## Access Reviews and recertification

A review should not only ask:

*“Does the user still need access?”*

It should also ask:

- Is the business role still current?
- Is the access actually used?
- Is the permission level appropriate?
- Are direct user grants present?
- Are there SoD conflicts?
- Has the sensitivity level changed?
- Are better technical alternatives available?
- Is the exception still required?
- Does the user or service account still exist?
- Should access be reduced or removed?

Review frequency can be risk-based:

| Access type | Example frequency |
| --- | --- |
| Standard Internal | annually |
| Confidential | semi-annually |
| Restricted PII | quarterly |
| Privileged Access | monthly or quarterly |
| Temporary Access | automatically at expiry |

## Joiner, Mover, Leaver

The identity lifecycle directly affects data access.

### Joiner

- derive baseline role from function
- verify required training
- provision access through policy

### Mover

- review old permissions
- assign the new role
- prevent permission accumulation
- reassess SoD conflicts

### Leaver

- revoke access quickly
- terminate sessions and tokens
- rotate technical credentials
- transfer ownership of reports, applications and data products
- reassign open approvals

The Mover process is often the largest risk.

New permissions are added, but old permissions remain.

## Monitoring, logging and audit

Monitoring should identify:

- unusually large data queries
- access outside normal time patterns
- new privileged permissions
- repeated failed access attempts
- unmasked use of sensitive data
- large data exports
- access to unused systems
- activity from outdated accounts
- direct permission grants
- changes to roles and policies
- access after role change or termination
- bypass of governed semantic layers

One event alone does not prove misuse.

Monitoring should provide context:

```text
Identity
  +
Role
  +
Data Sensitivity
  +
Purpose
  +
Volume
  +
Time
  +
Historical Behavior
  =
Risk Signal
```

## Access in the Modern Data Stack

An end-to-end model can look like this:

```text
Identity Provider
        ↓
Business Role
        ↓
Platform Role
        ↓
Warehouse / Lakehouse Policy
        ↓
Semantic Layer
        ↓
Qlik / Power BI / Tableau / Excel
        ↓
Export / API / Data Product
        ↓
Monitoring + Review
```

The access chain should remain as consistent as possible.

Problems emerge when every layer defines separate and unaligned rules.

Example:

```text
Warehouse:
PII is masked

BI Extract:
PII was exported unmasked

Excel:
Local copy remains accessible indefinitely
```

Governance must therefore consider the entire consumption chain.

## Access Governance for self-service

Self-service is important, but it increases the number of possible access paths.

Governance should enable:

- certified data products
- controlled data spaces
- role-based self-service access
- visible sensitivity labels
- standardized export rules
- traceable approvals
- automatic expiry
- secure sandbox models
- monitoring without unnecessary blocking

The objective is not maximum restriction.

The objective is controlled and traceable use.

## Access metadata

A data catalog can include access metadata:

| Metadata field | Meaning |
| --- | --- |
| **Access Owner** | accountable business approver |
| **Default Access Level** | default permission |
| **Sensitivity** | protection class |
| **Allowed Roles** | approved roles |
| **Restricted Attributes** | specially protected fields |
| **Masking Policy** | technical protection rule |
| **Export Policy** | allowed, restricted or prohibited |
| **Review Frequency** | recertification interval |
| **Retention of Logs** | access log retention |
| **SoD Rules** | known role conflicts |
| **Approval Workflow** | required approval steps |
| **Emergency Access** | break-glass procedure |

This makes access part of the asset description.

## Measuring Access & Security Governance

Useful indicators include:

- percentage of critical assets with an Access Owner
- percentage of sensitive assets with a documented access policy
- percentage of users covered by role-based access
- number of direct user grants
- number of privileged accounts
- number of stale or orphaned accounts
- number of service accounts without an Owner
- percentage of time-bound access
- Access Review completion rate
- number of overdue reviews
- time to provision approved access
- time to revoke after role change
- time to revoke after termination
- number of SoD conflicts
- number of approved exceptions
- number of expired exceptions
- number of failed or suspicious access attempts
- number of unmasked access events on sensitive data
- number of large data exports
- audit findings related to permissions
- percentage of governed versus direct access paths

Metrics should not only measure security events.

They should show whether the permission model is current, traceable and effective.

## A simple maturity model

| Maturity level | Typical state |
| --- | --- |
| **Ad hoc** | Permissions are granted manually and directly |
| **Documented** | Roles and initial approval processes are described |
| **Role-based** | Standard roles and Owners are established |
| **Policy-based** | Sensitivity, purpose and attributes drive access |
| **Automated** | Provisioning, expiry and revocation are workflow-driven |
| **Integrated** | Identity, data classification and platform controls are connected |
| **Monitored** | Usage, conflicts and exceptions are continuously analyzed |
| **Adaptive Governance** | Access responds to risk, context and change |

## Common anti-patterns

- every user receives individual permissions
- permissions are added but rarely removed
- roles have grown historically and are too broad
- business roles and technical roles are disconnected
- Data Owners are not involved in approval
- reviews consist only of large spreadsheets
- reviewers do not understand technical role names
- service accounts have no Owner
- secrets never expire
- admin rights are used for daily work
- shared accounts reduce accountability
- self-service tools bypass masking or semantic layers
- exports are not considered
- data is protected in the warehouse but exposed in BI extracts
- exceptions have no expiry date
- SoD conflicts are not detected
- logs exist but are never analyzed
- role changes only add permissions
- certification is treated as a one-time exercise
- success is measured by fast approvals rather than appropriate access

Approved access does not remain appropriate forever.

Access Governance is a lifecycle.

## Connecting to the other governance pillars

| Pillar | Connection |
| --- | --- |
| **Data Ownership & Stewardship** | Owners and Stewards define business access rules and exceptions |
| **Metadata, Catalog & Lineage** | Sensitivity, Owners and access paths become visible |
| **PII & Privacy Governance** | PII categories drive masking, approval and monitoring |
| **DSDR Governance** | Access to cases, identity data and evidence must be tightly controlled |
| **Data Quality Governance** | Quality status and sensitive issue data require differentiated visibility |
| **KPI & Metric Governance** | Detailed metrics and sensitive dimensions are delivered by role |
| **Data Lifecycle & Retention** | Expiry, deletion and archiving apply to permissions and access logs |

Access & Security Governance is therefore the operational connection between identity, business accountability, privacy and technical enforcement.

## Practical target state

```text
Identity
        ↓
Business Role
        ↓
Access Request + Purpose
        ↓
Data Owner Approval
        ↓
RBAC / ABAC / Policy
        ↓
Warehouse + Semantic + BI Enforcement
        ↓
Monitoring + Logging
        ↓
Review + Revoke
```

## The outcome

Effective Access & Security Governance creates:

- **Control** — permissions follow clear policies
- **Accountability** — business and technical responsibilities are traceable
- **Transparency** — access, usage and exceptions become visible
- **Security** — sensitive data is protected appropriately
- **Compliance** — approvals, reviews and decisions are auditable
- **Efficiency** — standard roles and workflows reduce manual case-by-case work
- **Flexibility** — self-service remains possible without abandoning protection principles
- **Risk Reduction** — excessive, outdated and unmanaged permissions are reduced

The decisive question is not:

*“Can the user log in?”*

It is:

***“Does this identity have exactly the access required for its current purpose — no more and no longer?”***

Related overview: [The 8 Pillars of Data Governance](/en/playbooks/eight-pillars).

Previous pillar: [KPI & Metric Governance](/en/playbooks/kpi-metric-governance).

Next pillar: [Data Lifecycle & Retention](/en/playbooks/data-lifecycle-retention).
