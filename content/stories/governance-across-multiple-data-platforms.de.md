---
title: "Governance über mehrere Datenplattformen hinweg"
description: "Governe Fabric, Databricks, Snowflake, BigQuery, dbt und Downstream Consumption mit einer Authority pro Governance Concern, expliziten Platform-Enforcement-Grenzen und prüfbaren Cross-Platform Evidence Handoffs."
author: Thomas Lindackers
tags:
  - data-governance
  - multi-platform
  - governance-architecture
  - metadata
  - lineage
  - access-control
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/governance-across-multiple-data-platforms-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance-Einstiegspunkte für Datenplattformen"
seriesPart: 7
---

![Governance über mehrere Datenplattformen hinweg](images/playbooks/governance-across-multiple-data-platforms-hero.png)

## Problem

Die meisten Organisationen betreiben nicht nur eine Analytics Platform. Eine realistische Landschaft kombiniert beispielsweise Microsoft Fabric und Power BI, Databricks für Engineering und AI, Snowflake oder BigQuery für Warehouse Workloads, dbt für Transformationen, operative Datenbanken, SaaS Sources, APIs, Excel und mehrere BI Tools.

Das Problem ist nicht die Coexistence selbst. Das Problem ist duplizierte Authority.

Wenn jede Platform ihr eigenes Governance Center wird, entstehen:

- mehrere Business Glossaries;
- widersprüchliche Owner Records;
- unterschiedliche PII Classifications;
- wiederholte Masking- und Row-Filter-Logic;
- kopierte Transformationen;
- lokale Semantic Metrics;
- unvollständige Lineage Graphs;
- mehrere Incident Queues;
- inkonsistente Retention Rules;
- unklare Cost Ownership.

Jedes Tool kann intern korrekt sein, während der End-to-End Control falsch ist.

Die relevante Frage lautet deshalb nicht:

> Welche Platform soll Governance ownen?

Sondern:

> Welches System oder welche Rolle ist für jeden Governance Concern autoritativ, wo wird die Entscheidung durchgesetzt, wo wird die Evidenz gespeichert und wie abonnieren alle Platforms diese Entscheidung, ohne konkurrierende Authorities zu erzeugen?

Drei Missverständnisse verursachen die meisten Cross-Platform-Probleme.

### Ein Central Catalog wird mit universeller Authority verwechselt

Ein Central Catalog kann Assets indexieren, Business Terms anzeigen und Lineage verbinden. Er sollte nicht automatisch zur Authoring Authority für jede technische Tatsache, Transformation Definition, Platform Privilege, Metric Behavior oder Runtime Status werden. Metadata bleibt nur zuverlässig, wenn jedes Feld Provenance und eine explizite Authority besitzt.

### Platform-native Enforcement wird mit Policy Ownership verwechselt

Eine Platform kann Masking, Row Filters, Tags, Privileges, Retention Settings oder Workspace Boundaries implementieren. Der Platform Control ownet nicht den genehmigten Business Purpose oder die Exception Decision. Mehrere Platforms können eine Policy durchsetzen; nur eine Authority sollte die Policy freigeben.

### Integration wird mit Governance verwechselt

Metadata über APIs zu synchronisieren erzeugt Transport und keine Decision Rights. Eine Integration kann Konflikte schneller replizieren. Cross-Platform Governance benötigt Precedence Rules, Stable Identifiers, Validation, Exception Ownership und Recertification.

![Eine Authority pro Governance Concern zuweisen](images/playbooks/governance-across-multiple-data-platforms-img1-de.png)

## Entscheidung

Governe eine Multi-Platform-Landschaft, indem du jedem Governance Concern eine Authority zuweist, plattformspezifisches Enforcement zulässt und Cross-Platform Evidence an jedem materiellen Handoff aufbewahrst.

Das Operating Model dokumentiert fünf Elemente pro Concern:

1. autoritatives System oder Rolle;
2. Enforcement Point oder Points;
3. Evidence Location;
4. Downstream Subscribers;
5. Exception Owner.

Mehrere Platforms können dieselbe Entscheidung konsumieren oder durchsetzen. Sie dürfen nicht stillschweigend zu konkurrierenden Authorities werden.

### 1. Die Authority Matrix nach Concern aufbauen

Nutze ein Concern-by-Concern-Modell statt einer einzigen „Master Platform“.

| Governance Concern | Typische Authority | Typisches Enforcement | Evidenz |
|---|---|---|---|
| Business Glossary | Governance Workflow und verantwortlicher Business Owner | Catalog- und Consumer Interfaces | Approved Term Version und Review Record |
| Data Ownership | Operating Model oder Product Registry | Catalog Display, Workflow Routing und Eskalation | Owner Acceptance und Review Date |
| Technical Catalog | Source Platform oder Technical Metadata Index | Platform Inventory und Central Index | Object Identifier, Schema und Collection Timestamp |
| Identity | Corporate Identity Provider | Platform IAM, Groups und Service Identities | Membership, Authentication und Access Review |
| PII Classification | Privacy- oder Governance Authority | Tags, Labels, Policies und Downstream Propagation | Classification Decision und Evidence |
| Access Policy | Data Owner mit Security- oder Privacy-Standards | Platform Privileges, Masks, Filters und Sharing Controls | Effective-Access-Tests und Audit Logs |
| Retention | Records-, Legal- oder Policy Authority | Storage Lifecycle, Table Policy und Deletion Jobs | Retention Assignment und Deletion Evidence |
| Lineage | Runtime Systems plus Central Lineage Index | Instrumentation und Metadata Ingestion | Stable Edge Identifiers und Collection State |
| Quality Evidence | Data Product Contract und Engineering Execution | Tests, Monitors und Publication Gates | Results, Failures, Incidents und Exceptions |
| Semantic Metrics | Metric Owner und Semantic Governance Process | Shared Semantic Layer und BI Consumers | Definition, Grain, Approval und Usage |
| Incident Coordination | Product Operating Model | Service Desk, Alerting und Platform Queues | Incident Record, Owner und Closure |
| Cost Accountability | Product Owner und FinOps Model | Billing Tags, Capacities, Warehouses und Reservations | Usage, Attribution, Budget und Eskalation |

Die Matrix verhindert eine falsche Wahl zwischen „central“ und „federated“. Authority kann für einen Concern zentralisiert und für einen anderen nach Domain föderiert sein, solange jeder Record eine verantwortliche Source besitzt.

### 2. Stable Identifiers über Platform Handoffs verwenden

Namen reichen nicht. `customer`, `dim_customer` und `Customer Master` können dasselbe Asset, verschiedene Assets oder mehrere Versionen eines Products bezeichnen.

Definiere Stable Identifiers für:

- Business Terms;
- Data Products;
- Source Objects;
- Transformation Models;
- Warehouse- oder Lakehouse Objects;
- Semantic Models und Metrics;
- Policies;
- Owners und Organizational Units;
- Quality Rules;
- Incidents und Exceptions;
- Deployment Versions.

Die Handoff Chain ist explizit:

```text
Source
→ Ingestion Platform
→ Transformation Layer
→ Warehouse oder Lakehouse
→ Semantic Model
→ BI-, API- oder AI-Consumer
```

![Cross-Platform Lineage und Evidence Handoffs](images/playbooks/governance-across-multiple-data-platforms-img2-de.png)

An jedem Handoff werden aufbewahrt:

- Asset Identifier;
- Owner;
- Classification;
- Lineage Link;
- Quality Status;
- Deployment Version;
- Access-Policy-Reference;
- Incident Route.

Broken Handoffs und Duplicate Identifiers müssen als Exceptions sichtbar sein. Records dürfen nicht nur wegen ähnlicher Namen gemerged werden.

### 3. Metadata nah an ihrer operativen Authority halten

Eine praktikable Multi-Platform-Architektur kopiert nicht jedes Feld an einen Ort und erklärt die Kopie zur Authority.

Nutze diese Default-Prinzipien:

- Source Systems bleiben autoritativ für originale Technical Identifiers und Source Constraints.
- Transformation Repositories bleiben autoritativ für Derived SQL Logic, Tests und Code Versions.
- Platform Catalogs bleiben autoritativ für aktuelle Objects, Privileges und Runtime Metadata, die sie beobachten können.
- Das Business Glossary bleibt autoritativ für freigegebene Terms und Meanings.
- Der Identity Provider bleibt autoritativ für People, Groups und Authentication Lifecycle.
- Die Semantic Layer bleibt autoritativ für Metric Execution Behavior und Analytical Grain.
- Der Governance Workflow bleibt autoritativ für Ownership, Permitted Use, Approval und Review States.
- Der Central Index verbindet Records, bewahrt Provenance und zeigt Konflikte.

Die Central Control Plane sollte beantworten, woher jeder Wert stammt, wann er gesammelt wurde, wer eine Änderung genehmigen darf und welche Subscribers ihn erhalten haben.

### 4. Authority, Enforcement und Evidence trennen

Diese drei Konzepte dürfen nicht zusammenfallen.

Beispiel: eine PII-Masking-Entscheidung.

| Element | Verantwortung |
|---|---|
| Authority | Privacy Standard plus Data Owner Approval für Product und Use Case |
| Enforcement | Fabric, Databricks, Snowflake, BigQuery oder eine andere Engine setzt den nativen Control um |
| Evidence | Policy Mapping, Deployment Version, Effective-Access-Test, Audit Event und Exception Record |

Eine Policy kann auf jeder Platform anders durchgesetzt werden und trotzdem eine freigegebene Bedeutung und einen Exception Process behalten.

Das Platform Mapping muss Semantic Equivalence dokumentieren. Ein Mask, der auf einer Platform `NULL` und auf einer anderen einen Hash liefert, repräsentiert möglicherweise nicht denselben Control. Cross-Platform Validation benötigt erwartete Outcomes und nicht nur identische Policy Names.

### 5. Coexistence governen statt duplizieren

![Duplicate Catalogs, Policies und Semantic Logic vermeiden](images/playbooks/governance-across-multiple-data-platforms-img3-de.png)

Uncontrolled Coexistence erzeugt lokale Optimierung und Enterprise Inconsistency. Governed Coexistence nutzt:

- eine Authority pro Concern;
- synchronisierte Metadata mit Provenance;
- plattformspezifische Enforcement Patterns;
- gemeinsame Source-, Transformation-, Product- und Consumption Contracts;
- kontrollierte Semantic- und Metric Ownership;
- koordiniertes Incident Routing;
- explizite Consolidation- oder Exit-Trigger.

Jede Coexistence Decision muss erklären, warum mehrere Platforms benötigt werden. Valide Gründe können Workload Specialization, Cloud Boundary, Residency, Acquired Estates, Consumer Requirements, Migration Stages oder Resilience sein. „Beide Teams bevorzugen ihr Tool“ ist keine ausreichende Governance-Rationale.

### 6. Duplicate-Control-Retirement-Rules definieren

Einige Duplikate sind transitional oder technisch notwendig. Sie benötigen trotzdem Owner und Exit Condition.

Klassifiziere Duplicate Controls als:

- **authoritative plus synchronized copy** – erwartet und kontrolliert;
- **platform-specific implementation** – anderer Mechanismus für dieselbe Approved Rule;
- **temporary migration duplicate** – zeitlich begrenzt und monitored;
- **local extension** – nur innerhalb eines dokumentierten Scope erlaubt;
- **uncontrolled conflict** – Blocker oder Exception mit Remediation-Pflicht.

Retirement Trigger können sein:

- Abschluss einer Migration;
- Verfügbarkeit einer Catalog Integration;
- Konsolidierung des Semantic Model;
- Platform Decommissioning;
- Standardisierung einer Policy Engine;
- Duplicate Incident Volume über einem Threshold;
- Cost- oder Operating-Capacity-Threshold;
- Review Date ohne weiterhin valide Coexistence-Rationale.

### 7. Incidents über die Product Chain koordinieren

Ein Source Failure kann gleichzeitig als dbt Test Failure, Warehouse Freshness Breach und defekter BI Report erscheinen. Drei Platform Tickets dürfen nicht zu drei unabhängigen Incidents werden.

Das Cross-Platform Incident Model definiert:

- einen Product Incident Identifier;
- verantwortlichen Incident Coordinator;
- betroffene Assets und Consumers;
- plattformspezifische Technical Owner;
- Quality- und Access-Impact;
- Communication Owner;
- Workaround und Exception;
- Closure Evidence;
- Post-Incident Metadata- oder Policy-Updates.

Platform Queues führen Arbeit aus. Product-level Coordination ownet das End-to-End Outcome.

### 8. Kosten über gemeinsame Processing Paths sichtbar machen

Multi-Platform Products verstecken Kosten häufig an Handoffs. Ingestion wird einem Project belastet, Transformation einer anderen Platform, Semantic Queries einer BI Capacity und Extracts einem Consumer Team.

Das Cost Model verbindet:

- Product Identifier;
- Platform Resource Identifiers;
- Billing Project, Warehouse, Cluster, Capacity oder Reservation;
- Processing- und Storage Owner;
- Shared-Service Allocation Rule;
- Budget und Threshold;
- Anomaly Route;
- Consumer- oder Domain Attribution;
- Consolidation Trigger.

Cost Accountability ist ein Governance Concern, weil Architecture Decisions Verpflichtungen über das erste Delivery Team hinaus erzeugen.

## Checkliste

### Authority

- Ist eine Authority für jeden Governance Concern definiert?
- Sind Business-, Technical-, Identity-, Policy-, Semantic- und Cost-Authorities getrennt?
- Ist Precedence definiert, wenn Systeme widersprechen?
- Hat jede Exception einen Owner und Expiry?

### Integration und Identifiers

- Werden Stable Identifiers über Platforms hinweg genutzt?
- Bleibt Provenance für jedes synchronisierte Feld erhalten?
- Sind Duplicate Identifiers und Broken Lineage Edges sichtbar?
- Werden Integrations auf Delay, Schema Change und Failed Delivery überwacht?

### Enforcement und Validation

- Ist jede Approved Policy auf plattformspezifische Controls gemappt?
- Werden Effective Results mit echten Identities und Query Paths getestet?
- Sind semantisch unterschiedliche Implementierungen dokumentiert?
- Sind Privileged Bypass und Export Paths enthalten?

### Evidence und Incidents

- Deckt die Evidence Chain Source bis Consumer ab?
- Sind Deployment-, Quality-, Access- und Incident-Records verknüpft?
- Wird ein Product Incident über Platform Queues hinweg koordiniert?
- Können Reviewer den Approved State für ein konkretes Datum und eine Version rekonstruieren?

### Coexistence und Exit

- Ist der Grund für jede Platform im Scope dokumentiert?
- Sind Duplicate Catalogs, Policies und Transformations klassifiziert?
- Gibt es Retirement Owner und Trigger für temporäre Duplikate?
- Ist das Review Date an eine reale Consolidation- oder Continuation-Entscheidung gekoppelt?

## Artefakt

Dokumentiere die Entscheidung mit dem Tool [Governance Starting-Point Decision](/tools/governance-starting-point-decision?product=multiple).

Fülle die Governance Boundary Decision Card in einem Workshop aus. Das Ergebnis wird nicht vorab eingetragen.

Die Karte dokumentiert:

1. **Decision Context** – governed Outcome, Platforms und Workloads, Asset- und Consumer-Scope, Coexistence Rationale und Decision Owner.
2. **Authority and Enforcement Boundary** – authoritative Metadata Source, Business Owner und Steward, Identity-, Classification- und Policy-Authorities, Enforcement Points, Lineage Identifiers, Quality Evidence, Semantic Ownership, Incident Coordination, Retention und Cost Ownership.
3. **Validation, Conflicts and Exit Conditions** – Cross-Platform Evidence Location, Effective-Control-Tests, ungelöste Authority Conflicts, zu entfernende Duplicate Controls, Exceptions und Consolidation Trigger.
4. **Decision Record** – Approved, Conditional oder Not Approved; Approved Boundary Map; Alternative; Blocker; Integration Actions; Retirement Plan; No-Regret Next Step; Implementation Owner; Recertification oder Review Date.

Die Karte ist die gespeicherte Evidenz, dass jeder Concern eine Authority besitzt, auch wenn mehrere Platforms die Entscheidung durchsetzen oder konsumieren.

## Tools

- Cross-Platform Governance Boundary Decision Card
- Governance Authority Matrix
- Asset Identifier and Handoff Register
- Metadata Provenance Map
- Platform Policy Equivalence Matrix
- Effective-Control Test Pack
- Duplicate-Control Retirement Backlog
- Cross-Platform Incident Route
- Product Cost Attribution Map
- Consolidation and Exit Trigger Register

## Ressourcen

Nutze aktuelle offizielle Dokumentation für jede Platform in der freigegebenen Boundary Map. Mindestens sind zu validieren:

- [Microsoft Fabric governance documentation](https://learn.microsoft.com/fabric/governance/)
- [Microsoft Purview documentation](https://learn.microsoft.com/purview/)
- [Databricks Unity Catalog documentation](https://docs.databricks.com/en/data-governance/unity-catalog/)
- [Snowflake data governance documentation](https://docs.snowflake.com/en/guides-overview-govern)
- [BigQuery data governance documentation](https://docs.cloud.google.com/bigquery/docs/data-governance)
- [Knowledge Catalog documentation](https://docs.cloud.google.com/dataplex/docs)
- [dbt artifacts](https://docs.getdbt.com/reference/artifacts/dbt-artifacts)
- [OpenLineage documentation](https://openlineage.io/docs/)

Feature Parity über Cloud Provider, Regionen, Editions, Runtimes oder Licensing Plans darf nicht vorausgesetzt werden. Jede ungelöste Capability wird als Validation Question in der Decision Card dokumentiert.

## Playbooks

- [Governance-Einstiegspunkte für Datenplattformen](/series/governance-platform-starting-points)
- [Fabric, Databricks, Snowflake oder BigQuery — Den Governance-Einstieg auswählen](/stories/choose-governance-platform-starting-point)
- [dbt als plattformübergreifende Governance-Kontrollschicht](/stories/dbt-governance-control-layer)
- [Metadata nah an der Quelle halten](/stories/keep-metadata-close-to-the-source)
- [Den Data Product Contract definieren](/playbooks/data-product-contract)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

## Nächster Schritt

Wähle ein reales Data Product, das mindestens zwei Platforms überquert, und fülle die Cross-Platform Governance Boundary Decision Card aus.

Führe anschließend eine kontrollierte Validierung durch:

1. eine Authority pro Governance Concern zuweisen;
2. jeden Platform Enforcement Point mappen;
3. Stable Identifiers für Product, Assets, Policies und Metrics erzeugen;
4. Lineage und Evidence von Source bis Consumer verfolgen;
5. einen erlaubten und einen verweigerten Access Path testen;
6. Policy Outcomes über Platforms vergleichen;
7. einen simulierten Quality Incident durch die Product Chain routen;
8. Duplicate Catalogs, Policies, Logic und Incident Queues identifizieren;
9. Retirement Owner und Trigger zuweisen;
10. alle Platform Costs mit dem Product Owner verbinden;
11. Approved, Conditional oder Not Approved dokumentieren;
12. Recertification Date setzen.

Der No-Regret Next Step ist nicht die Auswahl einer universellen Governance Platform. Er besteht darin, Authority, Enforcement und Evidence für ein Cross-Platform Product explizit zu machen und das erste uncontrolled Duplicate zu entfernen.
