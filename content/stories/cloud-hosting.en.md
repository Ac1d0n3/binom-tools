---
title: Cloud Hosting Models for Data Platforms
description: A practical overview of on-premises, IaaS, managed platforms, PaaS, SaaS, serverless and hybrid enterprise data architectures – including how the Big 5 platforms and the SAP stack fit together.
author: Thomas Lindackers
category: Data Platforms
tags:
  - cloud
  - hosting-models
  - on-premises
  - iaas
  - paas
  - saas
  - serverless
  - hybrid-cloud
  - data-platform
  - sap
  - big-five
hero: images/playbooks/cloud-hosting-hero.png
publishedAt: 2026-07-12
---

## Cloud is not one operating model

When organizations talk about a **cloud data platform**, they may mean very different architectures. In one solution, the cloud provider operates only the data center, network and virtualization layer. In another, the platform provider also manages the operating system, runtime, patching, scaling and major parts of platform operations. In a SaaS offering, even the application itself is fully managed.

These differences are particularly important for data platforms. They determine, among other things:

- which technical layers must still be operated internally
- where configuration and custom architecture decisions remain possible
- how identities, networks and access controls are integrated
- who is responsible for platform availability, patching and upgrades
- where data models, pipelines, metadata and governance are anchored
- how on-premises, SAP and cloud systems are operated together

The central question is therefore not only:

> *Does the platform run in the cloud?*

A better question is:

> *Which layers are operated by the provider, which layers remain the responsibility of the enterprise, and where do data, security and governance responsibilities remain?*

This playbook brings the **Big 5 data platforms** and the **SAP Data & Analytics Stack** from the previous overviews into one shared hosting and operating model.

## Four hosting models and their responsibility split

The boundaries between on-premises, IaaS, PaaS, managed platforms, SaaS and serverless are often blurred in product marketing. For architecture, however, the important question is which layers are actually operated by the provider.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/cloud-hosting-img1-en.png"
        alt="Comparison of the responsibility split across on-premises, IaaS, managed platform or PaaS, and SaaS or serverless hosting models"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        A simplified responsibility split across four common hosting models. The exact boundary always depends on the specific product, service tier and deployment model.
    </figcaption>
</figure>

The diagram deliberately separates **technical platform operations** from **responsibility for data and usage**. As a service becomes more managed, more technical operating tasks move to the provider. Responsibility for data, access, business quality and governance remains with the enterprise.

## 1. On-premises

In a traditional on-premises model, the enterprise operates the entire technical stack itself or delegates it to an infrastructure partner under its control.

Typical areas of responsibility include:

- data center or dedicated infrastructure
- network, firewalls and segmentation
- operating systems and base software
- runtime, database or compute engine
- installation and operation of the data platform
- upgrades, patching and technical maintenance
- backup, recovery, monitoring and capacity planning
- data models, pipelines, security and governance

Typical examples include:

- SAP BW or SAP BW/4HANA in an enterprise data center
- SAP S/4HANA on-premises
- self-managed Hadoop or Spark platforms
- self-installed databases and data warehouses

On-premises provides maximum technical control, but also maximum operational responsibility. The enterprise can control network zones, operating systems, maintenance windows and platform configurations in detail. At the same time, it must maintain the skills, processes and operating structures required to run the platform over the long term.

> **Important:** On-premises is not automatically obsolete or unsuitable. It can remain a deliberate target model for certain regulatory, technical or deeply integrated workloads.

## 2. Infrastructure as a Service – IaaS

With IaaS, the cloud provider supplies the physical infrastructure, compute, storage, foundational networking and virtualization. The enterprise runs its own operating systems, platform components and applications on top.

Typical provider responsibilities:

- data centers
- physical servers and storage
- foundational cloud networking
- virtualization and resource provisioning

Typical enterprise responsibilities:

- operating systems and hardening
- platform installation and runtime
- databases, clusters and middleware
- patching above the infrastructure layer
- network configuration inside the enterprise cloud environment
- identities, roles and access policies
- data models, pipelines and governance

A self-managed Spark, Hadoop or database cluster running on virtual machines is a typical IaaS pattern. The hardware no longer has to be operated by the enterprise, but the actual data platform remains largely an internal responsibility.

IaaS is particularly relevant when existing platform software is moved to the cloud without immediately changing the entire technical operating model.

## 3. Managed platform and Platform as a Service – PaaS

With a managed platform or PaaS, the provider takes over larger parts of platform operations. These may include the operating system, runtime, scaling, patching, high availability and selected platform services.

The enterprise focuses more strongly on:

- platform configuration
- data ingestion and integration
- data models and transformations
- business data products
- identities, permissions and policies
- data quality, metadata and governance

Typical data-platform examples include:

- Databricks on AWS, Azure or Google Cloud
- Amazon Redshift
- Azure Synapse Analytics
- other managed database, warehouse or lakehouse services

This is a broad category. Two managed platforms can expose very different operating boundaries. With Databricks, for example, the responsibility split depends on whether classic compute resources run in the customer's cloud environment or serverless compute is used. Azure Synapse and Amazon Redshift also provide different compute and deployment models within the same product families.

> **Architecture rule:** The product name alone is not sufficient. The actual combination of control plane, compute plane, storage, network, identity and operating model determines the responsibility split.

## 4. SaaS and serverless

With SaaS, the provider delivers a fully managed platform or application. The enterprise configures and uses the service but normally does not operate servers, operating systems or platform runtimes.

Serverless additionally abstracts many decisions related to compute provisioning, scaling and resource management. “Serverless” does not mean that no servers exist. It means that the customer does not provision and operate them directly.

Typical examples include:

- Snowflake as a fully managed cloud data service
- Google BigQuery as a serverless analytics and warehouse platform
- Microsoft Fabric as an integrated SaaS analytics platform
- SAP Datasphere as a fully managed cloud data environment
- SAP Business Data Cloud as a comprehensive managed SaaS offering
- SAP Analytics Cloud as a SaaS solution for analytics and planning

Even with SaaS, essential responsibilities remain with the enterprise:

- data classification and protection requirements
- identity and authorization design
- business data models and metrics
- pipeline and data-product logic
- data ownership and stewardship
- data quality and certification processes
- retention, deletion and regulatory requirements
- monitoring of usage and governance controls

The platform is managed. Responsibility for the **correct use of the platform and the meaning of the data** is not outsourced.

## Shared responsibility: responsibility moves, but it does not disappear

Cloud providers describe this principle as the **shared responsibility model**. The exact split differs by provider and service, but it follows a common pattern:

- The provider protects and operates the infrastructure and managed platform components under its control.
- The enterprise remains responsible for its data, identities, access, configurations, workloads and correct business use.
- The higher the abstraction level, the more technical operating tasks are handled by the provider.
- Governance, privacy, ownership and business quality still remain enterprise responsibilities.

For data platforms, the last point is critical. An automatically patched warehouse does not automatically know the correct business owner. A serverless query engine does not decide which KPI definition is certified. A SaaS catalog does not automatically resolve inconsistent classifications.

## Where the major data platforms typically fit

The Big 5 platforms and SAP products do not all sit at the same hosting level. Some are clearly oriented toward SaaS or serverless. Others combine managed control planes with compute and storage components in the customer's cloud environment. Others are available in multiple deployment models.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/cloud-hosting-img2-en.png"
        alt="Positioning of major data platforms across on-premises and private cloud, managed cloud platform, SaaS and managed service, and serverless and SaaS"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Typical hosting positions of major data platforms. The diagram is an orientation model, not a rigid product taxonomy, because several platforms span multiple operating models.
    </figcaption>
</figure>

### On-premises and private cloud

#### SAP BW/4HANA

SAP BW/4HANA is a HANA-optimized enterprise data warehouse that can be used in on-premises and hybrid scenarios. In many organizations it remains a central system for historical data, harmonized business logic, authorizations and reporting.

Technical operations may be handled in the enterprise data center, in a private-cloud environment or by a managed operations partner. The exact responsibility split depends on the operating contract.

#### SAP S/4HANA

SAP S/4HANA is not primarily a data platform; it is the operational ERP core. It is available in different deployment models. In the diagram, it represents substantial operational SAP systems that often remain in an enterprise-controlled or private operating model and feed analytical platforms.

#### Self-managed Hadoop or Spark

Self-managed Hadoop or Spark illustrates that cloud infrastructure alone does not create a managed platform. If clusters, runtimes, patching, scaling and operating processes are handled by the enterprise, the platform remains largely customer-operated even when the servers run in a public cloud.

### Managed cloud platforms

#### Databricks

Databricks is a managed lakehouse and data-intelligence platform on AWS, Azure and Google Cloud. Its architecture separates Databricks-managed platform services from the areas in which data and compute are processed.

Depending on the cloud and compute model, classic resources may run in the customer's cloud environment, while serverless compute can run in a Databricks-managed plane. Databricks therefore does not permanently fit into only one hosting category.

#### Amazon Redshift

Amazon Redshift is a managed data warehouse on AWS. AWS handles major tasks such as provisioning, operations, scaling, backups and patching of the warehouse engine. Redshift Serverless provides a more abstracted model in which warehouse capacity is automatically provisioned and scaled.

#### Azure Synapse Analytics

Azure Synapse Analytics is a managed analytics platform that brings together data warehousing, SQL, Spark, pipelines and other Azure services. It provides both dedicated and serverless resource models. Its exact position therefore depends on the Synapse service being used.

### SaaS and managed services

#### Snowflake

Snowflake runs entirely on public-cloud infrastructure and is delivered as a managed service. Infrastructure, platform software, maintenance and upgrades are managed by Snowflake. The enterprise controls areas such as data, roles, security policies, warehouses, models and workloads.

Snowflake can be deployed on different hyperscalers and in different regions, but it is not a locally installable on-premises platform.

#### SAP Datasphere

SAP Datasphere is a fully managed cloud data environment and a core component of the SAP Business Data Cloud direction. It connects SAP and non-SAP data, supports integration, semantic modeling and data products, and is designed to preserve business context across hybrid and multi-cloud landscapes.

#### SAP Business Data Cloud

SAP Business Data Cloud is a comprehensive managed SaaS offering for unifying and governing SAP and third-party data. It brings together business data fabric, data products, analytics, BW modernization, and data-engineering and AI capabilities within a broader platform direction.

### Serverless and SaaS

#### Google BigQuery

BigQuery is a serverless analytics and data-warehouse platform. Infrastructure, resource provisioning and much of scaling are abstracted by Google. Teams can focus more strongly on data, SQL, models, workloads and governance.

#### Microsoft Fabric

Microsoft Fabric is an integrated SaaS analytics platform with shared services for ingestion, engineering, data science, real-time processing, data warehousing and Power BI. OneLake provides a common logical data foundation across Fabric workloads.

#### SAP Analytics Cloud

SAP Analytics Cloud is a SaaS solution for business intelligence, analytics and enterprise planning. In this overview it is deliberately positioned in the analytics and consumption layer rather than as an enterprise data warehouse.

## Why the classification is not absolute

The four categories are useful, but they are not hard product boundaries.

| Platform | Why its position can vary |
| --- | --- |
| **Databricks** | Classic and serverless compute models expose different operating boundaries |
| **Amazon Redshift** | Provisioned clusters and Redshift Serverless abstract different operating tasks |
| **Azure Synapse** | Dedicated SQL pools, serverless SQL pools, Spark and pipelines use different resource models |
| **SAP S/4HANA** | On-premises, private-cloud and cloud editions change the operating responsibility |
| **SAP BW/4HANA** | Self-operation, private cloud and managed operations are all possible |
| **Snowflake** | Fully managed service, but with customer responsibility for data, roles, policies and workloads |
| **Microsoft Fabric** | SaaS platform that still contains different workloads and capacity models |

An architecture diagram should therefore not show only the product name. It should also document:

- cloud and region
- tenant, account and subscription boundaries
- control plane and compute plane
- storage location and data residency
- network paths and private connectivity
- identity provider and role model
- responsibility for patching, availability and recovery
- ownership of data models, pipelines and governance

## Three common enterprise hosting architectures

In practice, organizations rarely build pure single-product landscapes. They combine operational systems, data platforms, integration services, semantic layers and BI tools across multiple operating models.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/cloud-hosting-img3-en.png"
        alt="Comparison of an on-premises or private-cloud architecture, a cloud-native architecture and a hybrid enterprise architecture"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Three common target architectures. Many real enterprise landscapes are permanently hybrid and combine several platform and hosting models.
    </figcaption>
</figure>

### 1. On-premises or private cloud

```flow linear vertical
Enterprise Applications
SAP S/4HANA / ERP / CRM
SAP BW/4HANA
Data Warehouse
Data Marts / Semantic Layer
SAP BusinessObjects / Qlik / other BI tools
```

This pattern is common in long-established SAP and enterprise landscapes. It can provide a high degree of control over networking, maintenance windows, technical changes and data location.

Typical strengths:

- close integration with established enterprise systems
- mature operations, transport and authorization processes
- controlled technical change cycles
- continued use of existing platform expertise and business logic

Typical challenges:

- high internal share of platform operations
- specialized platform expertise
- longer technical lifecycle and upgrade programs
- new cloud and SaaS sources must be integrated deliberately

Security and governance are implemented primarily through internal policies, network zones, platform permissions and established control processes.

### 2. Cloud-native

```flow linear vertical
Cloud Sources
Ingestion Layer
Snowflake / Databricks / BigQuery / Redshift / Fabric
dbt or platform-native transformation
Power BI / Tableau / Qlik / other analytics tools
```

This pattern uses managed cloud services as the primary data and analytics platform. The Big 5 do not represent one shared technical architecture; they represent five widely used platform ecosystems with different operating models.

Typical strengths:

- rapid availability of managed platform capabilities
- elastic or serverless resource models
- broad integration with cloud services and SaaS sources
- modern data-engineering, analytics and AI workloads
- Infrastructure as Code and automated platform provisioning

Typical challenges:

- identity, network and governance design across several cloud services
- clear separation between platform configuration and data-product responsibility
- prevention of uncontrolled workspaces, projects and shadow platforms
- consistent metadata, lineage and access policies across multiple tools

Cloud-native does not automatically mean that all components come from the same provider. Ingestion, data platform, transformation, governance and BI may still be separate products.

### 3. Hybrid enterprise

```text
SAP S/4HANA / Legacy Systems       Cloud and SaaS Sources
              ↓                              ↓
        Integration / Replication / CDC / APIs
                           ↓
                SAP Datasphere
                      +
        Snowflake / Databricks / BigQuery / other platform
                           ↓
             Semantic Layer / Data Products
                           ↓
      SAP Analytics Cloud / Power BI / Qlik / Applications
```

This pattern connects established SAP and on-premises systems with modern cloud data platforms. It is not only a transitional state. For many large enterprises, hybrid is a long-term target model because operational core systems, regulatory requirements, specialized platforms and cloud innovation must coexist.

Typical strengths:

- continued use of existing SAP and legacy investments
- incremental modernization instead of a big-bang migration
- selection of the right platform for different workloads
- combination of SAP semantics with open cloud and data-engineering tools

Typical challenges:

- duplicated data and unclear systems of record
- complex replication and synchronization paths
- distributed identity, network and security models
- semantics and KPIs can be duplicated across platforms
- end-to-end lineage and consistent governance become more difficult

The most important architecture element is therefore the **unified security and governance layer** shown in the diagram. Policies do not necessarily have to be implemented in one product. They do, however, need to be defined consistently, enforced technically and monitored traceably across all environments involved.

## Hybrid is not automatically a migration failure

Many architecture models present hybrid only as a temporary phase between on-premises and cloud. That is too narrow for enterprise data landscapes.

Hybrid can be a valid long-term model when:

- operational SAP systems remain in an enterprise-controlled or private operating model
- certain data must be processed locally for regulatory or technical reasons
- specific workloads require specialized cloud platforms
- multiple regions or cloud providers are part of the enterprise strategy
- existing BW or data-warehouse models continue to be used in a controlled way
- data products must be delivered across platform boundaries

The important question is not whether a landscape is hybrid. The important question is whether the transitions between environments are **designed and governed deliberately**.

## Selection criteria for a hosting model

A hosting model should not be selected based only on a product name. The following questions should be answered for every platform and workload.

| Criterion | Questions to ask |
| --- | --- |
| **Control and customization** | Which technical layers must remain configurable? Are special runtime, network or extension requirements needed? |
| **Operating model** | Which tasks should remain internal and which can be handled by a provider? Which teams own platform operations, data products and support? |
| **Data residency** | In which countries and regions may data be stored, processed and backed up? |
| **Integration** | Which SAP, on-premises, SaaS, file, streaming and API sources must be connected? |
| **Latency and data movement** | Which data must be processed close to source systems or consumers? Which replication and synchronization patterns are required? |
| **Identity and access** | How are SSO, groups, service principals, roles, technical accounts and privileged access controlled? |
| **Network and isolation** | Are private endpoints, VPC/VNet integration, IP restrictions, firewalls or separated security zones required? |
| **Resilience** | Who is responsible for backup, recovery, multi-region design, restart procedures and disaster-recovery testing? |
| **Governance** | Where are ownership, classification, lineage, data quality, policies, approvals and certified data products managed? |
| **Skills** | Which platform and cloud skills already exist internally, and which capabilities must be maintained over the long term? |

## Governance remains necessary in every model

Hosting and governance answer different questions:

- **Hosting** describes where a platform runs and who operates its technical layers.
- **Governance** describes who may make decisions about data, which rules apply and how compliance with those rules is demonstrated.

| Governance area | On-premises | Managed platform | SaaS / serverless | Hybrid |
| --- | --- | --- | --- | --- |
| **Data ownership** | internal | internal | internal | coordinate internally across system boundaries |
| **Classification** | implement internally | configure platform capabilities | configure SaaS capabilities | unify taxonomy and propagation |
| **Access** | platform and infrastructure roles | cloud IAM plus platform roles | tenant, workspace and data roles | federate identities and policies |
| **Data quality** | own rules and execution | managed engines, own rules | platform capabilities, own expectations | align rules across replication and data products |
| **Lineage** | capture directly or integrate a catalog | connect platform and catalog metadata | use and extend provider metadata | establish end-to-end lineage across systems |
| **Retention and deletion** | implement fully | configure storage and platform capabilities | configure SaaS capabilities and policies | coordinate retention and deletion across copies |

A higher managed-service share reduces technical platform operations. It does not replace business accountability.

## Common misconceptions

### “Cloud automatically means SaaS”

No. A self-managed database on cloud virtual machines is IaaS. A managed warehouse is PaaS or a managed service. A fully integrated analytics application may be SaaS. All three run in the cloud, but they expose different responsibility boundaries.

### “Serverless means that there are no servers”

No. Servers and compute resources still exist. Provisioning, scaling and technical management are simply abstracted more strongly by the provider.

### “With SaaS, the provider is responsible for all security and governance”

No. The provider protects and operates the platform components under its control. The enterprise remains responsible for data, identities, roles, configurations, approvals, privacy and correct business use.

### “Hybrid is only a poor temporary solution”

Not necessarily. Hybrid can be a deliberate long-term target model when different workloads, platforms and regulatory requirements must be combined.

### “All Big 5 platforms use the same hosting model”

No. BigQuery and Fabric are strongly serverless or SaaS-oriented. Snowflake is a fully managed cloud service. Databricks combines managed platform services with different compute models. Redshift supports both provisioned and serverless variants.

### “SAP and the Big 5 are direct alternatives”

Only in part. SAP S/4HANA is primarily an operational business platform. SAP BW/4HANA, Datasphere and Business Data Cloud have different analytical and data-platform roles. Many organizations combine SAP components with Snowflake, Databricks, BigQuery, Redshift or Fabric.

### “Using one cloud provider automatically creates one governance model”

No. Even within one hyperscaler, separate services, accounts, workspaces, role models and metadata structures can emerge. Governance must be designed deliberately across these boundaries.

## A practical learning path

For a structured introduction, use the following sequence:

1. **Understand shared responsibility:** [Microsoft – Shared Responsibility in the Cloud](https://learn.microsoft.com/en-us/azure/security/fundamentals/shared-responsibility)
2. **Compare the model with a second provider:** [AWS Shared Responsibility Model](https://docs.aws.amazon.com/wellarchitected/latest/security-pillar/shared-responsibility.html)
3. **Add Google Cloud's perspective:** [Shared Responsibilities and Shared Fate on Google Cloud](https://docs.cloud.google.com/architecture/framework/security/shared-responsibility-shared-fate)
4. **Understand a fully managed data platform:** [Snowflake – Key Concepts and Architecture](https://docs.snowflake.com/en/user-guide/intro-key-concepts)
5. **Understand control plane and compute plane:** [Databricks – High-Level Architecture](https://docs.databricks.com/aws/en/getting-started/high-level-architecture)
6. **Position serverless analytics:** [Google BigQuery Overview](https://docs.cloud.google.com/bigquery/docs/introduction)
7. **Compare managed and serverless warehousing:** [Amazon Redshift Overview](https://docs.aws.amazon.com/redshift/latest/mgmt/overview.html) and [Amazon Redshift Serverless](https://docs.aws.amazon.com/redshift/latest/mgmt/serverless-whatis.html)
8. **Explore an integrated SaaS analytics platform:** [Microsoft Fabric Overview](https://learn.microsoft.com/en-us/fabric/fundamentals/microsoft-fabric-overview)
9. **Understand SAP's cloud data layer:** [SAP Datasphere](https://www.sap.com/products/data-cloud/datasphere.html)
10. **Position SAP's broader data and analytics direction:** [SAP Business Data Cloud](https://www.sap.com/products/data-cloud.html)

Useful additional resources:

- [Azure Synapse Analytics Overview](https://learn.microsoft.com/en-us/azure/synapse-analytics/overview-what-is)
- [SAP BW/4HANA for On-Premises and Hybrid Scenarios](https://help.sap.com/docs/sap-btp-guidance-framework/integration-architecture-guide/using-sap-bw-4hana)
- [Exploring SAP Analytics Cloud](https://learning.sap.com/courses/exploring-sap-analytics-cloud)
- [SAP Datasphere Documentation](https://help.sap.com/docs/SAP_DATASPHERE)
- [Microsoft Fabric Documentation](https://learn.microsoft.com/en-us/fabric/)

## Key takeaways

- **Cloud is not one operating model.** IaaS, managed platforms, PaaS, SaaS and serverless expose different responsibility boundaries.
- As a platform becomes more managed, the provider takes over more technical operating tasks.
- **Data, identities, access, business models, quality and governance remain enterprise responsibilities.**
- The Big 5 platforms do not all sit in the same hosting category, and several span multiple operating models.
- SAP BW/4HANA, SAP Datasphere, SAP Business Data Cloud and SAP Analytics Cloud serve different roles and can coexist with Big 5 platforms.
- On-premises, cloud-native and hybrid are not maturity levels with one automatically correct endpoint. They are different architecture models.
- Hybrid landscapes are often a valid long-term model for large enterprises, provided that integration, semantics, security and governance are designed consistently across platform boundaries.
- The right architecture starts not with a product name, but with a clear allocation of responsibilities.
