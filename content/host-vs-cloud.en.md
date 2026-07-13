---
title: Cloud vs. Self-Hosted Data Platforms — Cost, Control, Governance and Business Fit
description: A comprehensive decision guide for cloud, self-hosted, sovereign cloud and hybrid data platforms — covering TCO, operating effort, privacy, governance, performance and pragmatic mixed models.
category: Data Platforms
tags:
  - cloud
  - self-hosted
  - on-prem
  - hybrid-cloud
  - sovereign-cloud
  - data-platform
  - data-warehouse
  - total-cost-of-ownership
  - finops
  - data-governance
  - data-protection
  - gdpr
order: -1
author: Thomas Lindackers
hero: images/stories/host-vs-cloud-hero.png
---

## Cloud or self-hosted is often the wrong first question

Discussions about modern data platforms frequently begin with an apparently simple alternative:

*“Do we move to the cloud — or continue to operate the platform ourselves?”*

The question is understandable, but it is too narrow. A data platform is neither a single server nor a single cloud service. It consists of source systems, ingestion, replication, storage, transformation, data models, governance, security, reporting, monitoring, backup, support and the people who turn data into business meaning.

A better starting question is therefore:

> **Which operating model creates the greatest business value for a specific workload — at an acceptable level of cost, risk and operational effort?**

Cloud and self-hosted are not ideologies. They are operating models with different strengths, responsibilities and cost profiles.

Cloud can accelerate provisioning, provide elastic compute and reduce the operation of technical foundation components. A self-hosted platform can remain highly controllable and economical for stable workloads, existing infrastructure and local data needs. A European or sovereign cloud can sit between those poles. For many organizations, a deliberately designed hybrid model is the most realistic target architecture.

This story therefore looks beyond **cloud versus on-prem** and considers the full spectrum from self-hosted platforms to global hyperscalers — including cost, value, operating effort, privacy, governance, performance and useful mixed models.

## The operating model is a spectrum

Several operating models exist between an organization’s own data centre and a global hyperscaler. They differ primarily in how much infrastructure control, operational responsibility, scalability and managed capability an organization wants to retain or delegate.

<figure class="playbook-prose__figure">
    <img
        src="images/stories/host-vs-cloud-img1-en.png"
        alt="Data platform operating model spectrum from self-hosted environments through colocation, private cloud and sovereign cloud to public cloud and global hyperscalers"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        More control usually also means more direct responsibility. More managed services often increase agility and scalability, but change dependencies, cost models and governance requirements.
    </figcaption>
</figure>

### 1. Self-hosted / on-premises

Infrastructure runs in the organization’s own data centre or another directly controlled location. Hardware, virtualization, operating systems, databases, networking, backup and operations remain primarily the organization’s responsibility.

Typical benefits:

- high technical and organizational control
- direct integration with local networks and source systems
- predictable capacity for stable workloads
- freedom to choose maintenance windows, software and operating processes
- existing infrastructure and expertise can be reused

Typical challenges:

- hardware lifecycle and capacity planning
- patching, high availability and disaster recovery
- internal operations and security expertise
- slower provisioning of additional physical capacity
- limited elasticity without additional infrastructure

### 2. Colocation

Organization-owned hardware is hosted in a professional third-party data centre. Power, cooling, physical security and connectivity are partially outsourced, while the platform itself remains largely under the organization’s control.

Colocation can be useful when an organization does not want to operate physical data-centre facilities but still wants to control hardware, network design and the platform stack.

### 3. Private cloud

Dedicated, more highly automated infrastructure is operated for one organization — internally or by a service provider. Self-service, APIs, Infrastructure as Code and standardized provisioning can feel cloud-like even though the infrastructure remains exclusive or more tightly controlled.

Private cloud does not automatically reduce operating effort. It can require additional platform expertise if the organization owns the automation and orchestration layer itself.

### 4. European or sovereign cloud

European and sovereign cloud offerings aim to combine cloud convenience with stronger requirements for data residency, jurisdiction, operational control and digital sovereignty.

An important distinction remains: **“European”, “sovereign” and “data stored in Germany” are not fully interchangeable terms.** The actual assessment must be made at the level of the service, region, contract, subprocessors, support access and technical data flows.

### 5. Public cloud

Public-cloud platforms provide shared but logically isolated infrastructure together with broad portfolios of managed services. Capacity can be provisioned, scaled and removed quickly.

The strongest benefits usually appear where an organization genuinely gains from elasticity, global availability, managed services or rapid access to innovation.

### 6. Global hyperscaler

Global hyperscalers provide the broadest service portfolios, worldwide regions, extensive automation and very high scale. At the same time, the complexity of service selection, dependency on proprietary services and the need for cost, identity and data governance may increase.

## For an organization operating mainly in Germany

A business that operates only or primarily in Germany is **not automatically a poor fit for cloud**. It does, however, change the weight of certain benefits.

If an organization:

- runs its key source systems in Germany or inside its own network
- primarily serves internal users in Germany
- has stable data volumes and predictable load windows
- does not require global scale
- already has infrastructure, database or BI expertise
- does not require a broad portfolio of specialized managed services

then the incremental value of a globally distributed public cloud may be lower than it is for an international digital business.

Cloud may still make sense when:

- a small team cannot operate infrastructure and databases reliably
- new environments must be provisioned quickly
- the platform is growing rapidly or loads vary substantially
- AI, ML, streaming or specialist services are required
- internal high availability and disaster recovery would be disproportionately expensive
- European or German cloud regions meet the organization’s requirements

The organization’s geographic footprint is therefore only **one factor**. Data gravity, workload behaviour, available skills, service needs and the long-term operating model matter more.

## The real cost of a data platform

Cost comparisons between cloud and self-hosted environments are often incomplete.

A common comparison is:

`Cloud invoice ↔ Server purchase`

That is not a TCO comparison.

A credible view should include at least the following categories:

```text
Infrastructure
+ Software and licenses
+ Data processing
+ Storage
+ Network and data transfer
+ High availability
+ Backup and disaster recovery
+ Monitoring and security
+ Support
+ People and operations
+ Migration and parallel operation
+ Cost management
+ Lifecycle and exit
```

Some activities are also required in **both** operating models:

- data engineering
- data modelling
- business logic
- data quality
- data governance
- metadata and lineage
- BI and analytics development
- business ownership
- documentation and enablement

<figure class="playbook-prose__figure">
    <img
        src="images/stories/host-vs-cloud-img2-en.png"
        alt="Illustrative TCO comparison between a self-hosted and cloud data platform with cost categories for infrastructure, services, operations and staff"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        The figures shown are rough, rounded planning estimates for an illustrative mid-sized organization. They are not market prices, supplier offers or universally applicable benchmarks.
    </figcaption>
</figure>

> **Important cost note:** The figures in the diagram are intended only as an order-of-magnitude planning scenario and a basis for discussion. The example assumes approximately 500–1,500 employees, 100–500 BI users, 5–20 TB of analytical data, daily or hourly loads, moderate growth and high-availability requirements. Actual cost depends on existing infrastructure, depreciation, contracts, region, architecture, data volume, query patterns, availability design, discounts, staff allocation and existing licenses. One-time migration, transformation, parallel operation and organizational change may add material cost.

The ranges overlap intentionally. That is the central point: **neither cloud nor self-hosted is inherently cheaper.** A well-designed architecture can be economical in either model. A poorly governed platform can be expensive in either model.

### Self-hosted costs that are often underestimated

- hardware, storage and network equipment
- virtualization and operating systems
- database, ETL, monitoring and security licenses
- rack space, electricity, cooling and connectivity
- spare hardware and hardware renewal
- backup infrastructure and a secondary site
- monitoring, patching and vulnerability management
- on-call support and escalation
- capacity reserves for growth and peak demand
- internal staff effort not directly allocated to the platform

Existing infrastructure can reduce marginal cost significantly. It is not automatically free. Depreciation, operations, renewal and reserved capacity still belong in the economic model.

### Cloud costs that are often underestimated

- continuously running compute resources
- separate development, test and production environments
- hot, cool and archive storage tiers
- processing, warehouses, pipelines and streaming
- managed databases, catalogues and orchestration
- logs, metrics, tracing and audit data
- backups, snapshots and cross-region replication
- network, egress and inter-region traffic
- premium support and higher SLA tiers
- security services, key management and threat detection
- abandoned test resources and oversized instances
- FinOps, cost allocation and ongoing optimization

Cloud cost is often more variable and granular. This is an advantage when resources are genuinely scaled to demand. It becomes a risk when usage, ownership and budgets are not transparent.

### CAPEX versus OPEX is not the whole decision

Self-hosted is often associated with CAPEX and cloud with OPEX. That is a useful first approximation, but too simplistic.

Self-hosted environments also include ongoing OPEX for people, power, licenses, support and operations. Cloud can include longer-term commitments, reservations or minimum consumption. The decision should consider more than the accounting category:

- What is the total cost over three to five years?
- How variable are demand and data volume?
- Which investments already exist?
- How much capacity remains unused?
- Which operational risks are actually transferred?
- Which new skills are required?
- What is the value of faster provisioning?

### A practical TCO model

A pragmatic model can be structured as follows:

```text
3–5 year TCO
=
Platform cost
+ People cost
+ License and support cost
+ Network and data transfer
+ Security and compliance
+ High availability and recovery
+ Migration and parallel operation
+ Training and organizational change
+ Exit and portability cost
− reusable investments
− avoided operational effort
```

The calculation should include at least three scenarios:

1. **Base case** — expected data volume and normal utilization
2. **Growth case** — more data, more users and additional workloads
3. **Stress case** — peak utilization, higher availability, larger replication or unexpected data movement

Cloud scenarios should use current provider calculators and actual contract terms. Self-hosted scenarios should include internal full costs, depreciation, operating effort and renewal cycles.

## Value versus operational responsibility

Managed services are not merely a cost item. They can create real value when they remove undifferentiated operational work.

Examples include:

- hardware does not need to be procured and operated
- foundation services can be provisioned more quickly
- managed databases may provide patching, replication or failover as a service
- elastic resources can be scaled for short periods
- new capabilities are often available without installing a platform internally
- standardized APIs and automation can reduce time to value

Operational responsibility does not disappear completely. It shifts.

<figure class="playbook-prose__figure">
    <img
        src="images/stories/host-vs-cloud-img3-en.png"
        alt="Comparison of value and operational responsibility between self-hosted and cloud platforms"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Cloud can shift responsibility for infrastructure and technical platform services. Responsibility for data, business logic, governance, access, compliance and business value remains with the organization.
    </figcaption>
</figure>

### What a provider may operate

Depending on whether the service is IaaS, PaaS or SaaS, a provider may operate:

- physical data centres
- hardware, power and cooling
- network backbone
- virtualization
- the base platform and parts of the operating system
- managed database operations
- automatic scaling
- technical replication and snapshots
- parts of patching and high availability

### What remains with the organization

Regardless of hosting model, the organization typically remains responsible for:

- data selection and classification
- data ownership and business accountability
- data models and business logic
- KPI definitions and semantic models
- data quality and business controls
- roles, permissions and approval processes
- retention and deletion
- privacy assessment
- cloud-resource configuration
- identities, secrets and service accounts
- cost ownership and budget control
- auditability and evidence
- business value and adoption

The shared-responsibility model is therefore not a transfer of governance. It is a **redistribution of technical operating tasks**.

> **Managed services primarily reduce undifferentiated operational work. They replace neither data accountability nor business expertise.**

### Time to value and opportunity cost

A pure infrastructure comparison may overlook business value.

If a cloud platform makes a project productive six months earlier, that time advantage may justify a higher infrastructure price. If an existing self-hosted platform already supports a stable reporting workload without new investment, a migration may create substantial effort without additional business value.

The assessment should therefore also include:

- time to first production use
- effort required to create new environments
- availability of required skills
- speed of experimentation
- time spent on patching and platform maintenance
- impact on productivity and innovation
- risk that platform complexity delays delivery

## Data protection: more than the location of the data centre

Privacy discussions are often reduced to one question:

*“Are the servers located in Germany?”*

Location matters, but it is not sufficient.

For personal, confidential or regulated data, at least the following dimensions should be assessed:

| Governance and privacy aspect | Self-hosted | European / sovereign cloud | Global public cloud |
| --- | --- | --- | --- |
| **Physical data control** | directly controlled | contractually and regionally defined | region- and service-dependent |
| **Data residency** | internally defined | often restricted to selected EU/EEA regions | verify specific region and service |
| **Processing location** | internally controlled | assess service description and data flows | not automatically identical to storage location |
| **Backup location** | internally selected | verify region, geo-redundancy and restore paths | service- and configuration-dependent |
| **Metadata and telemetry** | internally controlled | assess separately | may follow different flows from customer data |
| **Administrative access** | own organization | assess operator, support and emergency access | review shared responsibility and support model |
| **Subprocessors** | often limited | review contractual documents | often a broader supply chain |
| **Encryption** | self-configured | commonly available as a platform capability | usually extensive, but must be configured correctly |
| **Key control** | full control possible | assess BYOK/HYOK and key location | service-dependent |
| **Deletion and retention** | self-implemented | verify service capability and backup lifecycle | configure per service |
| **Auditability** | own logs and controls | combine provider reports with own logging | provider assurance plus customer controls |
| **Exit and portability** | depends on the technical stack | assess export formats, APIs and termination process | assess egress, proprietary services and migration effort |

This table is not legal advice. The actual assessment depends on the use case, the data processed, roles, contracts and technical measures.

### Core review areas

```text
Data location
Processing location
Backup and recovery location
Metadata and telemetry
Administrative access
Support and emergency access
Subprocessors
Data processing agreement
Third-country transfers
Encryption
Key ownership
Identity & Access Management
Audit logging
Retention
Deletion
Portability
Exit strategy
```

GDPR obligations remain with the organization when cloud services are used. Processor arrangements require appropriate contractual and organizational controls. Transfers to third countries require additional assessment. Depending on the risk, a data protection impact assessment may also be required.

### Cloud is not automatically insecure — on-prem is not automatically secure

A professionally operated cloud can provide extensive security capabilities, certifications, physical protection and standardized controls. At the same time, misconfiguration, overly broad permissions, uncontrolled copies and unclear accountability can create significant risk.

A local server is not automatically compliant or secure. Missing patches, untested backups, shared administrator accounts, weak segmentation and incomplete logging can also create substantial exposure.

The relevant question is:

> **Which model can demonstrate appropriate technical and organizational measures for this workload?**

### BSI C5 as a reference point

The German Federal Office for Information Security’s Cloud Computing Compliance Criteria Catalogue defines minimum requirements and transparency criteria for secure cloud computing. A C5 attestation can support provider assessment, but it does not replace the organization’s own risk analysis, assessment of the specific service or responsibility for customer configuration.

## European, German and sovereign cloud options

“Cloud” does not automatically mean “global US public cloud without regional control”.

European providers and sovereign operating models exist alongside global hyperscalers. Examples include:

| Example | Regional context | Potential use | Still verify |
| --- | --- | --- | --- |
| **STACKIT** | data centres in Germany and Austria | IaaS, platform services, sovereignty-oriented workloads | specific service, region, certifications, support and subprocessor model |
| **IONOS Cloud** | locations include Frankfurt and Berlin | virtual data centres, compute, storage and platform services | selected location, service scope, contract and operating model |
| **T Cloud Public** | EU-DE in Germany and EU-NL in the Netherlands | European public-cloud infrastructure and managed services | region, service description, data flows and operating responsibility |
| **Hyperscalers with EU regions or sovereign offerings** | multiple European regions; some additional sovereignty offerings | broad managed-service portfolios, global scale, AI and specialist capabilities | service-specific residency, metadata, support access, exceptions, contracts and exit model |

The table is neither a ranking nor a product recommendation. Provider portfolios, regions, certifications and contractual terms change. Each decision should therefore be validated against current official documentation and actual contracts.

Microsoft’s EU Data Boundary is an example of why detail matters. It defines geographic commitments for selected enterprise online services, while the documentation also describes continuing or service-specific transfers and exceptions. “EU Data Boundary” therefore does not eliminate the need to assess the exact service and features in use.

Global providers are also expanding sovereignty offerings. AWS made its separate European Sovereign Cloud generally available in 2026. These options broaden the market, but still need to be assessed for service scope, operating model, data flows, cost and portability.

## Governance remains independent of hosting model

A data platform requires the same foundational governance capabilities regardless of where it runs.

### Data governance

- data owners and data stewards
- business terminology and glossary
- data classification
- PII and sensitivity labels
- data quality rules
- lineage and provenance
- certification of trusted data products
- ownership of metrics and KPIs

### Access and security governance

- role and permission models
- least privilege
- separation of administration and consumption
- service accounts and secrets
- privileged access
- periodic recertification
- masking and row-/column-level security
- audit logs and traceability

### Lifecycle governance

- retention periods
- deletion rules
- archiving
- legal holds
- backup lifecycle
- retirement of obsolete data products

### Cost governance

In cloud environments, cost governance becomes an additional platform discipline:

- cost centres and product ownership
- accounts, subscriptions, projects and tenants
- tags and labels
- budgets and alerts
- showback or chargeback
- cost per data product, team or environment
- detection of unused resources
- rightsizing and usage optimization
- commitments and reservations
- data-transfer cost

FinOps is not simply invoice checking. It connects usage, cost, ownership and business value. The same thinking is increasingly relevant to hybrid environments, data centres, licenses and SaaS.

## Performance: data and compute need to align

Hosting location alone does not determine performance. What matters is where the data resides, where it is processed and how frequently large volumes cross site or cloud boundaries.

A weak hybrid design may look like this:

```text
Local source systems
        ↓
Cloud warehouse
        ↓
Local BI with many live queries
        ↓
continuous network traffic and higher latency
```

A more resilient design places analytical processing closer to the data and transfers intentionally:

```text
Source systems
        ↓
incremental replication / CDC
        ↓
analytical storage close to compute
        ↓
curated models
        ↓
reporting and data products
```

### Does performance require everything to be mirrored?

Not necessarily in full, and not necessarily in real time.

Common patterns include:

| Requirement | Practical pattern |
| --- | --- |
| daily or hourly BI | batch or incremental replication |
| low-latency operational analytics | CDC or micro-batches |
| widely distributed users | regional caches, replicas or published data products |
| AI/ML with temporary high compute demand | move curated data selectively into elastic compute |
| local core systems and a cloud specialist service | transfer minimal classified datasets and return results |

The most important rule is:

> **Not every query should cross an environment boundary. Data movement must be designed, measured and governed deliberately.**

Cloud data transfer may also create direct cost. In self-hosted environments, WAN capacity, latency and network operations affect economics. In both models, data movement belongs in architecture and cost governance.

## A pragmatic mixed data platform

Hybrid should not mean that components are distributed randomly across environments.

A sound mixed model places each workload where it creates the most value — under a common governance layer.

<figure class="playbook-prose__figure">
    <img
        src="images/stories/host-vs-cloud-img4-en.png"
        alt="Pragmatic mixed data platform with a self-hosted or sovereign core platform, optional cloud services and a central governance layer"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Core and sensitive data can remain on a controlled platform while cloud services are used selectively for elasticity, AI, experimentation or specialist capabilities. Governance remains cross-platform.
    </figcaption>
</figure>

### Possible mixed models

#### Model A — controlled core, cloud for AI

```text
SAP / ERP / CRM
        ↓
Self-hosted or sovereign core warehouse
        ↓
curated and classified data
        ↓
controlled AI gateway
        ↓
Cloud AI service
```

This can fit when core and PII data should remain controlled while specialized models or elastic AI infrastructure are consumed.

Important controls include:

- data minimization
- anonymization or pseudonymization
- prompt and response logging under clear rules
- approved providers and models
- prevention of uncontrolled data disclosure
- centralized usage and cost monitoring

#### Model B — self-host the stable base, use cloud for peaks

```text
stable warehouse and BI load
        → self-hosted / private / sovereign core

temporary high compute demand
        → elastic cloud compute
```

This can be useful when daily operation is predictable but selected transformation, forecasting or ML workloads vary substantially.

#### Model C — controlled production, flexible development

```text
Production
→ controlled core platform

Development and test
→ temporary cloud environments
→ synthetic, masked or anonymized data
```

Benefit: fast provisioning and isolated experimentation.

Risk: test data and configuration must not expose production information unintentionally.

#### Model D — European cloud as the core with local integration

```text
local SAP, ERP and production systems
        ↓
private connectivity / controlled replication
        ↓
European or sovereign cloud data platform
        ↓
BI, analytics and data products
```

This can fit when internal operating capacity is limited but European data residency and a regional operating model are important.

#### Model E — local reporting with selected cloud data products

```text
local warehouse and reporting
        +
external market, geo or SaaS data from cloud
        +
curated cloud results
```

Avoid making every dashboard query multiple external systems live. Replicated or curated data products are usually more resilient.

## Decision matrix

The following matrix is not an automatic product selection. It indicates tendencies.

| Requirement or starting point | Self-hosted / private | European / sovereign cloud | Public cloud / hyperscaler | Hybrid |
| --- | --- | --- | --- | --- |
| stable long-term base load | strong | strong | possible, optimize cost | strong |
| existing infrastructure and operations team | strong | possible | prove migration value | strong |
| very small infrastructure team | demanding | strong | strong | possible |
| highly variable or unpredictable demand | limited | strong | very strong | very strong |
| global users and regions | expensive to build | provider-dependent | very strong | strong |
| rapid new environments | medium | strong | very strong | strong |
| broad AI/ML managed services | limited | growing | very strong | very strong |
| sensitive local core data | strong | strong after assessment | possible after assessment | very strong |
| maximum technical control | very strong | strong | lower | strong |
| low provider dependency | strong with open standards | medium | service-dependent | strong with clear boundaries |
| simple cost predictability | often good for stable load | medium | requires active management | medium |
| existing cloud expertise | not essential | important | very important | very important |
| very fast time to value | depends on existing platform | strong | very strong | strong |

## A structured decision process

### Step 1 — assess workloads, not only platforms

Do not classify the entire landscape in one decision. Individual workloads may require different operating models:

- core warehouse
- operational reporting
- self-service BI
- AI/ML
- streaming
- external data sharing
- development environments
- archiving
- disaster recovery

### Step 2 — define non-negotiable requirements

Examples include:

- data residency
- maximum recovery time
- maximum data loss
- regulatory requirements
- permitted providers and regions
- encryption and key model
- audit and logging requirements
- availability of internal skills

### Step 3 — capture current cost completely

Do not look only at invoices. Include:

- infrastructure and depreciation
- licenses
- staff allocation
- external services
- support
- data-centre cost
- networking
- backup and DR
- security
- outages and technical debt

### Step 4 — model target architecture and TCO

For each alternative, include:

- base, growth and stress scenarios
- three to five years
- one-time migration
- parallel operation
- training
- new roles and skills
- exit cost
- expected savings
- expected business value

### Step 5 — test performance with real data

A proof of concept should not demonstrate only one ideal query. It should include realistic data distributions, transformations, concurrency, load windows and recovery behaviour.

### Step 6 — define privacy and governance before migration

Do not wait until after go-live:

- data classification
- role model
- processor agreement
- third-country assessment
- logging
- retention
- deletion
- cost allocation
- provider exit

### Step 7 — assess portability realistically

“We use containers” does not automatically prevent vendor lock-in. Dependency may be created by:

- proprietary SQL extensions
- specialist data formats
- managed ETL or orchestration services
- proprietary identity models
- event and messaging services
- AI APIs
- catalogues and governance metadata
- operating processes and team skills

Portability does not mean every service can be replaced at any time. It means critical dependencies are understood and a realistic exit path exists.

## Common decision failures

### Cloud failures

- migration without a clear business case
- lift-and-shift without adapting the architecture
- buying every new capability as a managed service
- missing tags, budgets and cost ownership
- uncontrolled development and test resources
- failing to model data-transfer and logging cost
- building governance only after migration
- treating an EU region as complete legal and technical sovereignty
- no defined exit plan

### Self-hosted failures

- treating existing hardware as “free”
- ignoring staff and on-call effort
- retaining obsolete platforms only because skills already exist
- assuming high availability without testing it
- creating backups without testing restores
- overprovisioning permanently for peak demand
- accepting manual deployment as an inevitable consequence of on-prem
- using physical location as the only security and governance argument

### Hybrid failures

- random distribution without workload-placement principles
- duplicate data without a clear system of record
- live queries across slow or expensive network boundaries
- inconsistent identity and role models without central governance
- multiple platforms without sufficient operating and support expertise
- no unified view of cost and metadata

## A pragmatic recommendation

For many mid-sized organizations operating primarily in Germany, the following approach can be practical:

1. **Do not migrate stable, business-critical base workloads without a business case.**
2. **Treat existing infrastructure and skills as real value — but not as free.**
3. **Use cloud where elasticity, managed services or rapid provisioning create measurable value.**
4. **Assess European and sovereign offerings as distinct options.**
5. **Do not exclude sensitive data categorically; decide by classification, service, region and controls.**
6. **Keep governance, identity, metadata and cost ownership centralized and independent of hosting model.**
7. **Place data and compute as close together as practical.**
8. **Design hybrid intentionally — not as an unplanned collection of platforms.**

## Conclusion

Cloud is not a destination. Self-hosting is not a principle. Both are tools for different requirements.

A cloud platform can reduce operating effort, accelerate scaling and provide access to specialized services. A self-hosted platform can remain highly sensible for stable workloads, existing infrastructure and high-control requirements. European and sovereign clouds expand the spectrum. Hybrid models can combine strengths — when data movement, governance and accountability are designed deliberately.

The most important difference is not whether data sits “in the cloud” or “in the basement”.

It is:

- which activities a provider operates
- which accountability remains with the organization
- which costs actually arise
- which value becomes available faster or more effectively
- how data, access and lifecycle are controlled
- how dependent and portable the platform remains over time

The best decision is therefore rarely simply **cloud** or **self-hosted**.

It is:

> **Run the stable core where it is controllable and economical — and use cloud selectively where it creates demonstrable value.**

## Sources and further reading

### Cost and FinOps

- [FinOps Framework — FinOps Foundation](https://www.finops.org/framework/)
- [FinOps Framework 2026 — overview](https://www.finops.org/insights/2026-finops-framework/)
- [AWS Pricing Calculator](https://aws.amazon.com/calculator/)
- [Microsoft Azure Pricing Calculator](https://azure.microsoft.com/en-us/pricing/calculator/)
- [Google Cloud Pricing Calculator](https://cloud.google.com/products/calculator)
- [STACKIT cloud pricing and calculator](https://www.stackit.de/en/pricing/cloud-services/)

### Privacy, security and compliance

- [General Data Protection Regulation — EUR-Lex](https://eur-lex.europa.eu/eli/reg/2016/679/oj/eng)
- [BSI C5 — Cloud Computing Compliance Criteria Catalogue](https://www.bsi.bund.de/EN/Themen/Unternehmen-und-Organisationen/Informationen-und-Empfehlungen/Empfehlungen-nach-Angriffszielen/Cloud-Computing/Kriterienkatalog-C5/kriterienkatalog-c5_node.html)
- [AWS Shared Responsibility Model](https://aws.amazon.com/compliance/shared-responsibility-model/)
- [Microsoft EU Data Boundary — overview](https://learn.microsoft.com/en-us/privacy/eudb/eu-data-boundary-learn)
- [Microsoft EU Data Boundary — continuing transfers](https://learn.microsoft.com/en-us/privacy/eudb/eu-data-boundary-transfers-for-all-services)

### European and sovereign cloud options

- [STACKIT — Sovereign Cloud](https://www.stackit.de/en/)
- [IONOS Cloud — data centre locations](https://cloud.ionos.de/rechenzentren)
- [T Cloud Public — portfolio and regions](https://www.open-telekom-cloud.com/en/products-services/roadmap)
- [AWS European Sovereign Cloud](https://aws.amazon.com/compliance/europe-digital-sovereignty/)
