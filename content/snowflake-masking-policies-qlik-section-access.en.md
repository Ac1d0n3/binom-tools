---
title: Snowflake Masking Policies + Qlik Section Access
description: How approved governance metadata becomes complementary runtime controls through Snowflake masking and row policies plus Qlik application access and dynamic data reduction.
category: Data Governance
tags:
  - data-governance
  - snowflake
  - qlik
  - section-access
  - masking-policy
  - row-access-policy
  - pii
  - data-security
  - access-control
  - dbt
products:
  - snowflake
  - dbt
  - qlik
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 5
seriesTitle: End-to-End Data Governance
hero: images/playbooks/snowflake-masking-policies-qlik-section-access-hero.png
---

## Governance metadata becomes useful when it changes the runtime result

Parts 1–4 created a controlled path:

```text
Governance policy
→ dbt metadata contract
→ generated RAW models
→ propagated downstream metadata
```

The final step is runtime enforcement.

An approved column may contain:

```yaml
pii: true
pii_category: email
sensitivity: confidential
classification_status: approved
masking_policy: email_mask
```

A governed model may contain:

```yaml
row_access_domain: legal_entity
row_access_status: approved
```

Those values still do not protect data by themselves.

They must be translated into technical controls.

This article combines two complementary layers:

```text
Snowflake
→ central warehouse enforcement

Qlik
→ application access and application-level data reduction
```

> **Snowflake protects the governed data product at query time. Qlik Section Access controls what a user may open and see inside a specific application.**

Neither layer should be presented as an automatic replacement for the other.

## One decision can require several controls

Consider a customer-service dataset.

Governance decisions:

```text
Email is confidential PII.
Agents may see only customers assigned to their legal entity.
Supervisors may see all customers in their region.
Data stewards may inspect clear email values.
```

The implementation may require:

- Snowflake masking policy for `email`;
- Snowflake role or entitlement mapping;
- Snowflake row access policy for `legal_entity`;
- Qlik app authorization;
- Qlik Section Access reduction by `LEGAL_ENTITY`;
- optional Qlik field omission for application-specific fields;
- persona tests and audit evidence.

The controls overlap in purpose but operate at different points.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/snowflake-masking-policies-qlik-section-access-img1-en.png"
        alt="Approved governance metadata branching into central Snowflake masking and row access controls plus Qlik application access and Section Access data reduction before producing the effective user view"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        One governance decision can require both warehouse and application enforcement. Central protection remains active outside Qlik.
    </figcaption>
</figure>

## Snowflake Dynamic Data Masking protects column values

Snowflake Dynamic Data Masking is a column-level security feature.

A masking policy is evaluated at query time and can return different representations based on execution context and authorization.

Possible outcomes include:

```text
Authorized role
→ clear value

Limited role
→ partially masked value

Unauthorized role
→ fully masked or null value
```

A simplified masking policy can look like:

```sql
create or replace masking policy governance.email_mask
as (value string) returns string ->
    case
        when is_role_in_session('PII_CLEAR_ROLE') then value
        when is_role_in_session('PII_PARTIAL_ROLE')
            then regexp_replace(value, '(^.).*(@.*$)', '\\1***\\2')
        else null
    end;
```

The exact policy body must match the organization's role and entitlement model.

Masking should not depend on application-specific assumptions when the same data product is used by Qlik, notebooks, APIs or other tools.

## Direct assignment or tag-based masking

Snowflake supports two main mapping patterns.

### Direct policy assignment

The policy is attached to a specific column.

```sql
alter table analytics.customer
    modify column email
    set masking policy governance.email_mask;
```

Advantages:

- explicit;
- easy to understand for exceptions;
- direct relationship between column and policy.

Disadvantages:

- repetitive at scale;
- more attachment operations;
- harder to manage across many schemas and models.

### Tag-based masking

A governance tag is assigned to a column, and a masking policy is associated with the tag.

Conceptually:

```text
dbt metadata
→ Snowflake tag value
→ tag-based masking policy
→ protected column
```

This creates a scalable mapping:

```yaml
pii_category: email
masking_policy: email_mask
```

to:

```text
Tag: PII_CATEGORY = EMAIL
Policy: EMAIL_MASK
```

New columns receiving the governed tag can be protected through the tag-policy relationship.

Tag-based masking is particularly useful when:

- many columns share a policy;
- tags are managed consistently;
- data types are controlled;
- policy ownership is separated from model ownership;
- policy references are audited centrally.

Direct assignment remains useful for explicit exceptions.

## Keep policy administration separate from model ownership

A data engineer may own a model without being authorized to define who may see clear PII.

A useful separation of duties is:

| Responsibility | Typical owner |
| --- | --- |
| dbt model and metadata reference | Data engineering |
| PII classification approval | Data steward or privacy |
| Masking policy definition | Security or privacy engineering |
| Tag-policy mapping | Governance security administration |
| Role and entitlement mapping | Identity and access management |
| Application reduction | BI or application owner |
| Effective-access validation | Governance, security and application owner |

This avoids a model owner silently weakening a protection policy.

## Deploy metadata mappings through a controlled process

The dbt model can contain the approved mapping:

```yaml
columns:
  - name: email
    config:
      meta:
        pii: true
        pii_category: email
        sensitivity: confidential
        classification_status: approved
        masking_policy: email_mask
```

A deployment step can:

1. read the compiled metadata contract;
2. resolve `email_mask` to an approved Snowflake policy;
3. generate tag or policy DDL;
4. compare desired and actual state;
5. apply the approved change;
6. query policy references;
7. run persona tests;
8. store evidence.

This logic may be implemented with controlled macros, operations, infrastructure code or a governance deployment service.

The key requirement is that policy attachment remains:

- deterministic;
- reviewed;
- idempotent;
- auditable;
- separated from ordinary query logic.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/snowflake-masking-policies-qlik-section-access-img2-en.png"
        alt="Flow from dbt governance metadata through policy mapping and a Snowflake tag or direct policy assignment to query-time masked results for authorized, limited and unauthorized roles"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Approved metadata selects a controlled policy mapping. Snowflake evaluates the masking policy when the column is queried.
    </figcaption>
</figure>

## Row access is separate from masking

Masking controls the value returned from a column.

Row access controls which records are returned.

A user may be allowed to see:

```text
all columns
but only rows for legal_entity = DE
```

Another user may see:

```text
all legal entities
but masked email values
```

These are different authorization dimensions.

A simplified Snowflake row access policy may use an entitlement mapping:

```sql
create or replace row access policy governance.legal_entity_access
as (legal_entity string) returns boolean ->
    exists (
        select 1
        from governance.user_legal_entity_access a
        where a.role_name = current_role()
          and a.legal_entity = legal_entity
    );
```

It can then be attached to a table or view:

```sql
alter table analytics.customer
    add row access policy governance.legal_entity_access
    on (legal_entity);
```

When an object has both a row access policy and masking policies, Snowflake evaluates row access before masking.

This central enforcement protects warehouse access outside Qlik.

## Qlik Section Access controls an application

Section Access is part of the Qlik load script.

It defines:

- who may access the application;
- which rows are available to the authenticated user;
- optionally, which fields are omitted.

The script has two sections:

```qlik
Section Access;

LOAD
    ACCESS,
    USERID,
    LEGAL_ENTITY,
    OMIT
FROM ...;

Section Application;

Customer:
LOAD
    CUSTOMER_ID,
    LEGAL_ENTITY,
    EMAIL,
    CUSTOMER_NAME
FROM ...;
```

Depending on the Qlik environment, identity may be represented through `USERID` or `USER.EMAIL`.

The reduction field must exist in both `Section Access` and `Section Application` with exactly the same name. Qlik's access-section field names and values are handled in uppercase, so the reduction design must normalize them consistently.

When the user opens the app, Qlik matches the user's security rows to the application data and hides excluded data.

## Section Access has three different jobs

### 1. Application authorization

If the user has no valid security row, the app is denied under strict exclusion.

This is different from having platform permission to see the app object in a space or stream.

Both platform authorization and Section Access may matter.

### 2. Row reduction

A reduction field links security rows to application rows.

Example security table:

```text
ACCESS | USERID             | LEGAL_ENTITY
USER   | DOMAIN\ANALYST_A   | DE
USER   | DOMAIN\ANALYST_B   | NL
ADMIN  | DOMAIN\STEWARD     | *
```

A normal user sees only matching data.

The wildcard must be governed carefully. In reduction fields, it represents values available in the Section Access table, not arbitrary future values that exist only in the application data.

### 3. Field omission

The optional `OMIT` field can hide named application fields for specific users.

This can support application-specific requirements, but it should not replace Snowflake masking for centrally sensitive columns.

Qlik documentation recommends avoiding `OMIT` on key fields because it can create confusing or incomplete application behavior.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/snowflake-masking-policies-qlik-section-access-img3-en.png"
        alt="Qlik Section Access flow from authenticated user and security table through matching uppercase reduction fields into a governed application with denied, reduced or broader approved data access"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Section Access protects a Qlik application through authorization and data reduction. It does not secure direct access to the underlying warehouse.
    </figcaption>
</figure>

## Do not maintain entitlements independently in every app

A common implementation embeds users directly in the script:

```qlik
Section Access;

LOAD * INLINE [
ACCESS, USERID, LEGAL_ENTITY
USER, DOMAIN\USER_A, DE
USER, DOMAIN\USER_B, NL
];
```

This may work for a prototype.

It does not scale as an enterprise access model.

A stronger pattern uses a governed entitlement source:

```text
Identity or role
→ entitlement table
→ warehouse access policy
→ Qlik Section Access extract
```

Example entitlement model:

| Principal | Access domain | Value | Application scope | Valid from | Valid to |
| --- | --- | --- | --- | --- | --- |
| Regional Analyst DE | legal_entity | DE | customer_app | 2026-01-01 | null |
| Regional Analyst NL | legal_entity | NL | customer_app | 2026-01-01 | null |
| Data Steward | legal_entity | * | customer_app | 2026-01-01 | null |

The same authoritative source can feed:

- Snowflake row access logic;
- Qlik Section Access tables;
- access-review reports;
- expiration checks;
- audit evidence.

The generated Qlik security table may still be application-specific, but the entitlement decision should not be recreated manually in every app.

## Align warehouse roles and Qlik users carefully

Snowflake may see a service role used by the Qlik connection, while Qlik applies end-user reduction inside the app.

That creates an important distinction.

```text
Snowflake query identity
→ Qlik connection or service role

Qlik application identity
→ authenticated end user
```

If the Qlik connection role can read broad data, Section Access becomes responsible for reducing the app result for users.

The warehouse must still protect sensitive columns against the connection role according to the intended architecture.

Possible patterns include:

### Broad governed service role

The Qlik service role can load all rows required by the app, while Snowflake masking protects values the app should never receive.

Qlik Section Access then reduces rows by end user.

### Multiple application roles or data products

Different apps or domains use narrower Snowflake roles or governed views.

This reduces the volume of data available to each app connection.

### End-user-aware query patterns

Where supported by the chosen architecture, the warehouse receives an end-user or entitlement context.

This can increase central enforcement but adds identity-passing and query-design complexity.

The correct design depends on Qlik deployment mode, connection model, latency and security requirements.

The non-negotiable rule is:

> **Document which identity Snowflake evaluates and which identity Qlik evaluates.**

## Section Access changes require reload and validation

Section Access is loaded into the application.

Changes to the security table or script require an application reload before they take effect.

The reload identity must also be considered. In Qlik Sense on Windows, scheduler or service identities may need explicit administrative access or an approved impersonation setup.

A failed service-account design can produce either:

- failed reloads;
- unintended broad access;
- lockout from the application;
- stale entitlements.

Service accounts belong in the test matrix, not as an afterthought.

## Test effective access, not only configuration

A policy can exist and still produce the wrong result.

Validation needs concrete personas.

Example:

| Persona | Snowflake role | Expected email | Expected rows | Qlik app |
| --- | --- | --- | --- | --- |
| Data steward | `PII_CLEAR_ROLE` | clear | approved broad scope | broad governed access |
| Regional analyst DE | `ANALYST_ROLE` | masked | DE only | DE only |
| Customer service user | `SERVICE_ROLE` | partial | assigned entity only | assigned entity only |
| Qlik reload service | `QLIK_LOAD_ROLE` | according to app contract | app load scope | reload succeeds |
| Unauthorized user | none | no access | none | app denied |

Tests should verify:

- clear, partial and masked values;
- legal-entity or regional row scope;
- denied access;
- missing entitlement behavior;
- wildcard behavior;
- unmatched reduction values;
- application reload;
- direct Snowflake query outside Qlik;
- copied or republished app behavior;
- evidence after policy changes.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/snowflake-masking-policies-qlik-section-access-img4-en.png"
        alt="Persona-based validation matrix comparing Snowflake role, masked column result, warehouse row scope and Qlik application result followed by evidence and approval"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Effective-access tests validate the complete path. Configuration checks alone cannot prove that each persona receives the intended result.
    </figcaption>
</figure>

## Audit configured and effective controls

Snowflake provides metadata views and functions for inspecting policy configuration.

Useful evidence includes:

- masking policy definitions;
- policy references;
- tag references;
- row access policy definitions;
- role grants;
- entitlement-table changes;
- query test results.

Qlik evidence can include:

- Section Access source version;
- application reload status;
- effective-user test results;
- published app version;
- failed access attempts;
- entitlement extracts;
- application ownership.

A useful evidence record connects:

```text
Governance decision
→ dbt metadata reference
→ Snowflake policy attachment
→ Qlik security mapping
→ persona test
→ review approval
```

## Deployment sequence matters

A safe sequence avoids temporary exposure.

For example:

```text
Create or update approved policy
→ attach policy or governed tag
→ verify policy reference
→ test warehouse personas
→ reload Qlik application
→ test Qlik personas
→ approve deployment
```

When replacing policies, avoid an uncontrolled interval in which the object is unprotected.

Changes to a tag, masking policy, row policy, role or Section Access table must be treated as security changes, not ordinary cosmetic configuration.

## Tag-based row access is not required for this architecture

Snowflake introduced tag-based support for additional policy types, including row access, as a public preview in July 2026.

This article does not require that preview feature.

The stable core architecture can use:

- tag-based masking for column protection;
- directly assigned Snowflake row access policies;
- Qlik Section Access for application-level reduction.

Preview capabilities can be evaluated separately under the organization's feature-adoption process.

## Common enforcement anti-patterns

### Only store the masking policy name in dbt

The metadata contract exists, but no runtime policy is attached.

### Protect PII only in Qlik

Direct Snowflake access can bypass the application control.

### Use masking for row security

Masked values do not prevent unauthorized rows from being returned.

### Use Section Access as the enterprise identity system

User and entitlement logic is duplicated across apps.

### Use a broad wildcard without testing future values

New entities may not behave as expected.

### Omit key fields

The application can become confusing or incomplete.

### Test only with administrators

Normal users, denied users and service accounts remain unverified.

### Assume the Qlik user is the Snowflake user

The warehouse may evaluate a shared connection role instead.

### Deploy policy replacements without sequencing

A temporary unprotected state can occur.

## Decision guide

| Requirement | Primary control |
| --- | --- |
| Mask sensitive column values centrally | Snowflake masking policy |
| Apply the same masking rule across many columns | Snowflake tag-based masking |
| Restrict warehouse rows | Snowflake row access policy |
| Restrict who can open a Qlik app | Qlik Section Access plus platform authorization |
| Reduce app rows by user | Qlik Section Access reduction field |
| Hide an app-specific field | Qlik `OMIT`, used carefully |
| Maintain reusable entitlement decisions | Governed entitlement source |
| Prove effective access | Persona-based warehouse and Qlik tests |
| Protect access outside Qlik | Snowflake controls |
| Handle application-specific reduction | Qlik Section Access |

## The central insight

> **Governance enforcement is strongest when Snowflake protects the shared data product centrally and Qlik adds controlled application-specific authorization and reduction.**

The complete operating model is:

```text
Approved metadata
→ controlled policy mapping
→ Snowflake masking and row access
→ governed Qlik load
→ Section Access reduction
→ persona tests
→ audit evidence
```

This completes the series:

1. architecture;
2. metadata contract;
3. controlled RAW generation;
4. metadata propagation;
5. runtime enforcement.

## Sources and further documentation

- [Snowflake — Understanding Dynamic Data Masking](https://docs.snowflake.com/en/user-guide/security-column-ddm-intro)
- [Snowflake — Tag-based masking policies](https://docs.snowflake.com/en/user-guide/tag-based-masking-policies)
- [Snowflake — Understanding row access policies](https://docs.snowflake.com/en/user-guide/security-row-intro)
- [Snowflake — Attribute-based access control using tag-based policies](https://docs.snowflake.com/en/user-guide/tag-based-policies)
- [Snowflake — POLICY_REFERENCES](https://docs.snowflake.com/en/sql-reference/functions/policy_references)
- [Qlik Sense May 2026 — Managing data security with Section Access](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Scripting/Security/manage-security-with-section-access.htm)
- [Qlik Sense May 2026 — Section statement](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Scripting/ScriptRegularStatements/Section.htm)
- [Qlik Sense May 2026 — Star statement and Section Access behavior](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/Scripting/ScriptRegularStatements/Star.htm)

> **Feature status:** July 2026. Snowflake Dynamic Data Masking requires Enterprise Edition or higher. Tag-based support for row access and several other policy types was introduced as a public preview on July 21, 2026; the core recommendation in this article does not depend on that preview.
