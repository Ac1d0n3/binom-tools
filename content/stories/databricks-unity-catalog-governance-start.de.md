---
title: "Databricks und Unity Catalog als Governance-Einstieg"
description: "Entscheide, wann Databricks und Unity Catalog die passende Governance-Grundlage für Engineering-, Lakehouse-, Streaming- und AI-Workloads bilden und welche Operating-Model-Grenzen zuerst geklärt werden müssen."
author: Thomas Lindackers
tags:
  - Databricks
  - Unity Catalog
  - Data Governance
  - Lakehouse
  - Data und AI Governance
  - Platform Operating Model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/databricks-unity-catalog-governance-start-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance-Einstiegspunkte für Datenplattformen"
seriesPart: 3
---

# Databricks und Unity Catalog als Governance-Einstieg

Databricks kann ein starker Governance-Einstieg sein, wenn der erste governed Use Case durch engineering-intensive Verarbeitung, Streaming, Machine Learning oder AI geprägt ist. Unity Catalog ergänzt eine gemeinsame Kontrollschicht für Daten- und AI-Assets. Zu einer Governance-Grundlage wird die Plattform jedoch erst, wenn fachliche Verantwortung, Identitäten, Kataloggrenzen, Ausführungsumgebungen, Lineage-Evidenz, Quality Ownership und Kostenverantwortung gemeinsam betrieben werden.

Die Entscheidungsfrage lautet deshalb nicht, ob Unity Catalog einen Katalog, Lineage oder Zugriffskontrollen bereitstellt. Entscheidend ist, ob die Organisation diese Fähigkeiten in ein überprüfbares **Databricks Governance Operating Model** überführen kann.

## Problem

Plattformauswahl beginnt häufig mit einer Feature-Liste:

- Kann die Plattform Tabellen und Modelle katalogisieren?
- Kann sie Zugriffsrechte durchsetzen?
- Kann sie sensible Daten klassifizieren?
- Kann sie Lineage darstellen?
- Kann sie Batch-, Streaming- und AI-Workloads ausführen?

Diese Fragen sind notwendig, aber nicht ausreichend. Eine Plattform kann Kontrollen implementieren, ohne die fachlichen Entscheidungen dahinter zu besitzen.

Unity Catalog modelliert governed Assets als Securable Objects. Die Hierarchie beginnt beim Metastore und führt über Catalogs und Schemas zu Objekten wie Tabellen, Views, Volumes, Funktionen und Modellen. Privileges können Benutzern, Service Principals und Gruppen zugewiesen werden. Dadurch entsteht eine technisch konsistente Kontrollschicht. Sie entscheidet jedoch nicht:

- wer für den fachlichen Zweck eines Data Products verantwortlich ist;
- wer die erlaubte Nutzung genehmigt;
- wer Grain und semantische Bedeutung festlegt;
- wer eine Quality Exception akzeptiert;
- wer entscheidet, ob ein Modell oder Feature in einem neuen Kontext verwendet werden darf;
- wer für die Workloads bezahlt, die das Asset erzeugen und nutzen.

Drei Fehler treten besonders häufig auf.

### Catalog Ownership wird mit Data Ownership verwechselt

Ein Catalog Owner oder ein Principal mit `MANAGE` kann Plattformobjekte und Kontrollen administrieren. Diese Rolle ist nicht automatisch der verantwortliche Data Owner. Die fachliche Ownership bleibt für Zweck, erlaubte Nutzung, Qualitätserwartungen, Risikoakzeptanz und Ausnahmeentscheidungen verantwortlich.

### Workspace-Design wird mit Governance-Design verwechselt

Ein Workspace ist eine Ausführungs- und Kollaborationsumgebung. Er ist allein keine vollständige Daten-, Residency-, Zugriffs- oder Kostengrenze. Catalogs sind standardmäßig aus den Workspaces erreichbar, die am selben Metastore hängen, sofern Workspace Bindings sie nicht einschränken. Eine belastbare Umgebungstrennung benötigt deshalb explizite Entscheidungen zu Catalog, Workspace, Identität, Privileges, Storage und Compute.

### Runtime Lineage wird mit End-to-End-Evidenz verwechselt

Unity Catalog erfasst Lineage für unterstützte Databricks-Aktivitäten. First-Mile-Ingestion außerhalb von Databricks, unmanaged Copies, externe APIs und Last-Mile-BI-Tools können außerhalb dieses Runtime-Graphen bleiben. External Lineage Metadata oder eine andere Integration ist erforderlich, wenn diese Kanten für Impact Analysis oder Audit-Evidenz relevant sind.

![Unity-Catalog-Governance-Grenze](images/playbooks/databricks-unity-catalog-governance-start-img1-de.png)

Die Governance-Grenze besteht aus drei unterschiedlichen Ebenen:

| Ebene | Primäre Verantwortung |
|---|---|
| Business Governance | Zweck, erlaubte Nutzung, verantwortliche Ownership, Qualitätserwartungen und Ausnahmeentscheidungen |
| Unity-Catalog-Kontrollschicht | Catalogs, Schemas, Ownership, Privileges, Governed Tags, Klassifikation, Policies, Lineage und Audit-Evidenz |
| Ausführungsebene | Workspaces, Compute, Serverless, Notebooks, Jobs, Pipelines, SQL- und AI-Workloads |

Die Kontrollschicht implementiert Entscheidungen. Sie darf nicht unbemerkt zum Entscheidungsverantwortlichen werden.

## Entscheidung

Nutze Databricks mit Unity Catalog als Governance-Einstieg, wenn der erste governed Use Case erhebliche Engineering-, Lakehouse-, Streaming- oder AI-Fähigkeiten benötigt **und** die Organisation die Plattformgrenzen explizit betreiben kann.

Eine positive Startentscheidung weist typischerweise die meisten der folgenden Merkmale auf:

- Der erste Use Case benötigt skalierbare Transformationen, Streaming, Feature Engineering, Modellentwicklung oder kombinierte Data-and-AI-Workflows.
- Daten- und AI-Assets sollen über ein gemeinsames Objekt- und Privilege-Modell governed werden.
- Mehrere Workspaces benötigen einen gemeinsamen Metastore und konsistenten Datenzugriff.
- Produktionsjobs können unter kontrollierten Service Principals statt unter persönlichen Identitäten laufen.
- Catalog-, Schema- und Workspace-Grenzen lassen sich aus Domain-, Environment-, Residency- und Permitted-Use-Anforderungen ableiten.
- Runtime Lineage kann für externe Quellen und Consumer ergänzt werden.
- Quality-Evidenz und Incident Ownership können jedem governed Product zugeordnet werden.
- Compute-Verbrauch kann einem Product, Team, Owner oder Cost Center zugerechnet werden.

Databricks sollte nicht allein deshalb ausgewählt werden, weil es den breitesten Engineering-Funktionsumfang bietet. Ein einfacheres governed Warehouse, eine BI-Plattform oder eine Verbesserung des Semantic Layers kann die bessere No-new-platform-Alternative sein, wenn das erste Problem auf stabile SQL-Transformationen, vertrauenswürdiges Reporting oder Metric Governance begrenzt ist und die Organisation die zusätzliche Engineering- und AI-Betriebsfläche nicht benötigt.

### Die Hierarchie vor den Objekten entwerfen

Die technische Kontrollhierarchie muss in eine betriebliche Hierarchie übersetzt werden.

| Ebene | Governance-Entscheidung |
|---|---|
| Account | Kommerzielle Grenze, Account Administration, Identity Federation und globale Plattformstandards |
| Cloud und Region | Residency, Netzwerk, Cloud IAM, regionale Serviceverfügbarkeit und Metastore-Platzierung |
| Metastore | Oberste Unity-Catalog-Grenze für Metadaten, Privileges und regionale Workspace-Zuordnung |
| Catalog | Primäre Grenze für Data Domain, Product, Environment oder Isolation |
| Schema | Subdomain, Lifecycle Stage, Team oder Product Component |
| Object | Tabelle, View, Volume, Funktion, Feature, Modell oder Service mit expliziter Ownership und Control Evidence |
| Workspace | Processing Environment für Personen und Workloads |
| Compute | Runtime-, Access-Mode-, Policy-, Library-, Netzwerk- und Kostengrenze |

Für ein regionales Deployment ist der Metastore eine zentrale Architekturentscheidung. Databricks dokumentiert einen Metastore je Betriebsregion; die Workspaces der Region werden daran angebunden. Workspaces am selben Metastore sehen denselben Catalog Namespace. Ein Workspace erzeugt daher standardmäßig keinen unabhängigen Katalog.

Workspace-Catalog Binding kann einen Catalog auf ausgewählte Workspaces beschränken und eine Bindung als Read-only definieren. Das ist für die Trennung von Development und Production nützlich, darf aber nur als eine Kontrolle im gesamten Environment Model verstanden werden.

![Account, Metastore, Catalog, Schema und Workspace](images/playbooks/databricks-unity-catalog-governance-start-img2-de.png)

Cross-Workspace- und Cross-Catalog-Zugriffe müssen als Entscheidungen dokumentiert werden. Sie dürfen nicht zufällig aus Default Visibility oder breiten vererbten Grants entstehen.

### Fachliche, administrative und technische Ownership trennen

Ein minimales Ownership-Modell unterscheidet mindestens fünf Verantwortungsbereiche.

| Rolle | Verantwortlich für |
|---|---|
| Data Owner | Fachlicher Zweck, erlaubte Nutzung, Kritikalität, Qualitätserwartungen und Akzeptanz von Ausnahmen |
| Data Steward | Definition, Klassifikation, Vollständigkeit der Metadaten, Review Workflow und Koordination von Issues |
| Catalog- oder Schema-Administrator | Umsetzung von Ownership, Privileges, Tags, Policies und Workspace Bindings |
| Engineering Owner | Pipeline, Code, Deployment, Runtime-Quality-Kontrollen, Recovery und technische Incidents |
| Platform- und FinOps-Owner | Metastore, Workspaces, Compute Policies, Plattformbetrieb, Budgets und Kostenzuordnung |

In einer kleinen Organisation kann eine Person mehrere Rollen übernehmen. Die Entscheidungen müssen trotzdem unterscheidbar bleiben. Ein Catalog Owner darf einen sensiblen Use Case nicht allein deshalb freigeben, weil die Plattform ihm das Erteilen des Zugriffs erlaubt.

### Effektiven Zugriff statt nur direkte Grants prüfen

Databricks kennt Benutzer, Service Principals und Gruppen als Identitäten. Account-level Groups sollten der Standard für Zugriffszuweisungen sein. Produktionsjobs und automatisierte Deployments sollten, wo praktikabel, unter Service Principals laufen.

Ein Effective-Access-Review muss mehr als ein `SELECT` auf einer Tabelle berücksichtigen:

```text
effektiver Zugriff
= Account-Identität
+ Gruppenmitgliedschaften
+ vererbte Privileges
+ direkte Privileges
+ Ownership- oder Admin-Rechte
+ Workspace- und Catalog-Binding
+ Row-Filter- und Column-Mask-Policies
+ ausführbare Funktionen und Compute-Pfad
```

Der Review sollte beantworten:

- Welche Identitäten können das Objekt entdecken?
- Welche Identitäten können es lesen, verändern, taggen oder verwalten?
- Welche Gruppen liefern vererbten Zugriff?
- Welcher Service Principal schreibt Produktionsdaten?
- Kann der Workspace den Catalog erreichen?
- Verändern Row Filters oder Column Masks das Ergebnis abhängig von der Identität?
- Kann ein Owner oder Administrator den vorgesehenen Freigabeprozess umgehen?
- Wird Zugriff entfernt, wenn eine Person die Rolle wechselt oder das Unternehmen verlässt?

Das Ziel ist keine lange Grant-Liste. Das Ziel ist erklärbarer, testbarer und widerrufbarer Zugriff.

### Klassifikation und Policy als governed Workflows behandeln

Unity Catalog unterstützt Tags auf Securable Objects, Governed Tags mit kontrollierten Werten und Berechtigungen, automatisierte Data Classification, tabellenspezifische Row Filters und Column Masks sowie tagbasierte Attribute-based Policies.

Diese Mechanismen benötigen getrennte Ownership-Entscheidungen:

- Der Data Owner genehmigt die Permitted-Use-Regel.
- Der Steward verwaltet Klassifikation und Review Status.
- Security oder Privacy definiert Policy Standards.
- Das Plattformteam implementiert wiederverwendbare Policy Functions und Guardrails.
- Engineering verifiziert das Verhalten auf dem unterstützten Compute.
- Audit- oder Control Owner prüfen Evidenz und Ausnahmen.

Automatisierte Klassifikation ist Evidenz, keine finale Genehmigung. Ein erkannter PII-Tag kann einen Control Workflow auslösen, bestimmt aber weder rechtmäßigen Zweck noch Retention, Consent oder fachliche Kritikalität.

Tagbasierte Policies können bei skalierbaren Kontrollen die objektweise Administration reduzieren. Table-level Filters und Masks können für isolierte Fälle weiterhin sinnvoll sein. Die Entscheidung muss Scope, Policy Ownership, Runtime Requirements, Performance, Testbarkeit und aktuelle Cloud-spezifische Verfügbarkeit berücksichtigen.

### Lineage durch externe Evidenz vervollständigen

Die Evidenzkette eines governed Products sollte Folgendes abdecken:

```text
Quelle
→ Ingestion
→ Transformation oder Streaming
→ governed Tabelle oder Feature
→ Modell oder analytisches Product
→ BI-, API- oder AI-Consumer
```

Für jeden Schritt werden mindestens benötigt:

- Code- und Deployment-Version;
- verantwortlicher Owner;
- Technical Owner;
- Klassifikation;
- Access Policy;
- Lineage;
- Quality Tests;
- Incident Owner;
- Kostenzuordnung;
- Approval Record.

Unity Catalog kann unterstützte Runtime Lineage innerhalb von Databricks automatisch erfassen. External Metadata und External Lineage können vorgelagerte Systeme und nachgelagerte Consumer ergänzen, die nicht automatisch beobachtet werden. Diese Beziehungen benötigen weiterhin Ownership und Pflege. Eine manuell angelegte Lineage-Kante, die nie überprüft wird, ist keine verlässliche Evidenz.

Audit Logs und System Tables ergänzen die operative Evidenz, beantworten aber unterschiedliche Fragen:

- Lineage zeigt Beziehungen und Flüsse.
- Audit-Evidenz zeigt Aktionen und Zugriffsereignisse.
- Quality-Evidenz zeigt, ob das Product vereinbarte Kontrollen erfüllt hat.
- Change Records zeigen, welche genehmigte Version deployed wurde.
- Incident Records zeigen, wer bei einem Evidenzfehler reagiert hat.

![Vom Engineering Workflow zum governed Data and AI Product](images/playbooks/databricks-unity-catalog-governance-start-img3-de.png)

Unmanaged Extracts und externe Consumer müssen als explizite Gaps dargestellt werden, bis sie integriert, eingeschränkt oder über eine befristete Ausnahme akzeptiert sind.

### Compute und Kosten als Teil der Governance behandeln

Engineering- und AI-Plattformen erzeugen eine zusätzliche Governance-Dimension: Auch die Ausführung besitzt Owner, Policies und Kosten.

Das Operating Model sollte definieren:

- erlaubte Compute-Typen je Environment;
- Einsatz von Standard, Dedicated oder Serverless, soweit passend;
- unterstützte Runtime- und Library-Patterns;
- Identitäten für Produktionsjobs;
- Netzwerk- und External-Access-Kontrollen;
- Resource Tags und Cost-Center-Regeln;
- Budgetgrenzen und Eskalation;
- Ownership für idle, fehlgeschlagene oder ausufernde Workloads;
- Aufbewahrung von Evidenz für Jobs, Pipelines, Modelle und Endpoints.

Serverless kann die Infrastrukturadministration reduzieren, entfernt aber nicht die Verantwortung. Aktuelle Serverless Limitations, unterstützte Sprachen, Netzwerkverhalten, Policy Support und regionale Verfügbarkeit müssen für Cloud und Workload geprüft werden.

Databricks Billing System Tables, darunter `system.billing.usage`, können Nutzung Ressourcen, Identitäten, Produkten und Custom Tags zuordnen. Diese Evidenz wird erst nutzbar, wenn Tagging Model und Cost Owner vor dem Production Deployment verpflichtend sind.

## Checkliste

Nutze die Readiness Checkliste, bevor Databricks als Einstieg freigegeben wird.

| Entscheidungsbereich | Erforderliche Evidenz | Beispiel für einen Blocker |
|---|---|---|
| Erster governed Use Case | Benanntes Product, Consumer, Value, Grain und Kritikalität | „Lakehouse bauen“ ohne governed Outcome |
| Cloud und Region | Cloud, Residency, Region, Netzwerk und Serviceverfügbarkeit | Benötigte Fähigkeit in der Zielregion nicht verfügbar |
| Account und Metastore | Account Owner, Metastore Design und regionales Zuordnungsmodell | Mehrere Regionen ohne Metastore- oder Sharing-Konzept |
| Catalog und Schema | Domain-, Product-, Environment- und Lifecycle-Grenzen | Catalogs entstehen nur nach Präferenz des Technikteams |
| Workspace Model | Dev-/Test-/Prod-Pattern und Binding-Regeln | Production Catalog für jeden angebundenen Workspace offen |
| Business Ownership | Data Owner und Steward mit Entscheidungsrechten | Catalog Owner wird als einziger Owner dargestellt |
| Identität | IdP Source, Account Groups und Service Principals | Produktionsjobs laufen unter persönlichen Benutzern |
| Privileges | Inheritance, direkte Grants, Owner, Admins und Review Process | Kein Effective-Access-Test |
| Klassifikation | Genehmigte Taxonomie, Governed Tags und Review Workflow | Automatisierte Tags gelten als finale Business-Freigabe |
| Policy | Row-, Column- und Permitted-Use-Kontrollen mit Policy Owner | Masking Logic ohne verantwortlichen Rule Owner |
| Lineage | Runtime Coverage plus Plan für externe Quelle und Consumer | Last-Mile BI und exportierte Kopien sind unsichtbar |
| Quality | Tests, Thresholds, Failure Handling und Incident Owner | Quality Monitor vorhanden, aber niemand akzeptiert Incidents |
| Compute | Runtime-, Access-Mode-, Environment- und Policy-Standards | Teams wählen unbeschränktes Production Compute |
| Kosten | Attribution Tags, Budget Owner, Dashboard und Eskalation | Nutzung kann keinem Product oder Cost Center zugeordnet werden |
| Change | Version, Deployment, Approval und Rollback Evidence | Production Changes umgehen den Review |
| Validierung | Benannte Proof-of-Value-Tests und Acceptance Criteria | Plattform wird nach einer Feature-Demo freigegeben |

Dokumentiere die Entscheidung mit dem Tool [Governance Starting-Point Decision](/tools/governance-starting-point-decision?product=databricks).

Ein Readiness Result sollte eines von vier expliziten Ergebnissen verwenden:

- **Bereit für den Proof of Value:** Grenzen, Owner und Validierungstests sind ausreichend definiert.
- **Bedingte Readiness:** Databricks ist plausibel, aber benannte Gaps müssen im Proof of Value geschlossen werden.
- **Blockiert:** Ein wesentliches Residency-, Identity-, Control-, Operating-Capacity- oder Kostenproblem verhindert einen verantwortbaren Start.
- **No-new-platform-Alternative:** Das erste governed Ergebnis kann sicherer mit der bestehenden Plattform und einer engeren Governance-Maßnahme erreicht werden.

## Artefakt

Das erforderliche Artefakt ist ein **Databricks Governance Operating Model**, kein allgemeines Architekturdiagramm.

Dokumentiere mindestens die folgenden Felder:

```yaml
decision:
  firstGovernedUseCase:
  businessOutcome:
  targetConsumers:
  criticality:
  cloud:
  region:
  accountAndMetastoreDesign:
  catalogAndSchemaBoundaries:
  workspaceAndEnvironmentModel:
  dataOwner:
  dataSteward:
  technicalOwner:
  identitySource:
  accountGroups:
  productionServicePrincipals:
  privilegeModel:
  classificationTaxonomy:
  governedTags:
  rowAndColumnPolicyModel:
  policyOwner:
  lineageCoverage:
  externalLineageGaps:
  auditEvidence:
  qualityTests:
  qualityThresholds:
  incidentOwner:
  computeModel:
  costAttribution:
  budgetOwner:
  deploymentAndChangeModel:
  unresolvedGaps:
  validationTests:
  decisionOutcome:
  noRegretNextStep:
  reviewDate:
```

Das Operating Model weist außerdem die wiederkehrenden Verantwortungen zu.

| Aktivität | Verantwortliche Rolle | Ausführende Rolle | Evidenz |
|---|---|---|---|
| Zweck und Permitted Use genehmigen | Data Owner | Steward | Approved Use Record |
| Definitionen und Klassifikation pflegen | Data Steward | Domain Team | Metadaten und Review History |
| Catalog-Grenzen administrieren | Platform Governance Owner | Catalog Administrators | Catalog- und Binding-Konfiguration |
| Identitäten und Gruppen verwalten | Identity Owner | IAM Administrators | Gruppen- und Provisioning-Evidenz |
| Access Policies implementieren | Security- oder Policy Owner | Platform Engineers | Policy Definition und Tests |
| Pipelines bauen und deployen | Engineering Owner | Data Engineers | Repository, Deployment- und Run History |
| Quality überwachen | Data Owner | Steward und Engineering | Tests, Thresholds und Incidents |
| External Lineage pflegen | Product Owner | Integration- oder Metadata-Team | Überprüfte externe Beziehungen |
| Kosten zuordnen und steuern | Budget Owner | FinOps und Plattformteam | Usage Dashboard und Eskalationsnachweis |
| Nach Änderungen neu bewerten | Data Owner | Governance Workflow Owner | Review Decision und Effective Date |

Ownership muss Personalwechsel überstehen. Nutze Gruppen, Service Principals, managed Deployment Processes und Review Dates statt das Wissen eines einzelnen Administrators.

## Tools

Ein Proof of Value sollte Governance-Verhalten testen, nicht nur Workload Performance.

### 1. Boundary Test

Erzeuge einen Development- und einen Production Catalog. Binde den Production Catalog nur an den genehmigten Production Workspace, definiere den vorgesehenen Access Mode und verifiziere den verweigerten Zugriff aus einem nicht gebundenen Workspace.

### 2. Identity and Privilege Test

Provisioniere Account Groups und einen Production Service Principal. Erteile Zugriff über Gruppen, führe den Production Workload über den Service Principal aus und dokumentiere den effektiven Zugriff eines Developers, Consumers, Stewards und Administrators.

### 3. Policy and Classification Test

Wende einen Governed Classification Tag auf sensible Spalten an. Implementiere die ausgewählte Row- oder Column-Policy, teste positive und negative Fälle und dokumentiere, wer die Regel genehmigt, implementiert und verändern kann.

### 4. Evidence Chain Test

Verfolge eine Quelle durch Ingestion, Transformation, ein governed Product und einen externen Consumer. Ergänze External Lineage für fehlende Kanten, verknüpfe die deployed Code Version, füge Quality Results hinzu und prüfe Audit-Evidenz.

### 5. Failure and Incident Test

Erzwinge einen Quality-Threshold-Verstoß oder einen Access-Policy-Fehler. Prüfe, ob der Workload wie vorgesehen fehlschlägt oder quarantined wird, ob der richtige Owner informiert wird und ob Remediation und Ausnahmeentscheidung dokumentiert werden.

### 6. Cost Accountability Test

Tagge den Workload mit Product-, Environment- und Cost-Center-Metadaten. Frage Billing-Evidenz ab, gleiche sie mit dem verantwortlichen Budget Owner ab und teste die Eskalationsgrenze.

Infrastructure as Code sollte dort eingesetzt werden, wo es Wiederholbarkeit für Metastores, Workspace Assignment, Catalogs, Grants, Policies und Compute Configuration verbessert. Automatisierung implementiert genehmigte Entscheidungen; sie darf Ownership oder Permitted-Use-Regeln nicht erfinden.

## Ressourcen

Die folgenden offiziellen Databricks-Ressourcen wurden für diesen Entscheidungsartikel geprüft. Product Behavior, Cloud Support, Preview Status, Licensing und regionale Verfügbarkeit müssen für das konkrete Deployment erneut validiert werden.

- [What is Unity Catalog?](https://docs.databricks.com/aws/en/data-governance/unity-catalog/)
- [Unity Catalog securable objects](https://docs.databricks.com/aws/en/data-governance/unity-catalog/securable-objects)
- [Unity Catalog privileges reference](https://docs.databricks.com/aws/en/data-governance/unity-catalog/access-control/privileges-reference)
- [Create a Unity Catalog metastore](https://docs.databricks.com/aws/en/data-governance/unity-catalog/create-metastore)
- [Workspace-catalog binding](https://docs.databricks.com/aws/en/data-governance/unity-catalog/access-control/workspace-catalog-binding)
- [Identity best practices](https://docs.databricks.com/aws/en/admin/users-groups/best-practices)
- [Governed tags](https://docs.databricks.com/aws/en/admin/governed-tags/)
- [Data Classification](https://docs.databricks.com/aws/en/data-governance/unity-catalog/data-classification)
- [Row filters and column masks](https://docs.databricks.com/aws/en/data-governance/unity-catalog/filters-and-masks/)
- [Create and manage ABAC policies](https://docs.databricks.com/aws/en/data-governance/unity-catalog/abac/policies)
- [Lineage in Unity Catalog](https://docs.databricks.com/aws/en/data-governance/unity-catalog/data-lineage)
- [External lineage](https://docs.databricks.com/aws/en/data-governance/unity-catalog/external-lineage)
- [Audit log system table](https://docs.databricks.com/aws/en/admin/system-tables/audit-logs)
- [Monitor costs using system tables](https://docs.databricks.com/aws/en/admin/usage/system-tables)
- [Serverless compute limitations](https://docs.databricks.com/aws/en/compute/serverless/limitations)
- [Databricks pricing and platform tiers](https://www.databricks.com/product/pricing)

## Playbooks

Nutze diese Seite gemeinsam mit den übergeordneten Plattformentscheidungen:

- [Governance Platform Starting Points](/series/governance-platform-starting-points)
- [Fabric vs Databricks as a Governance Starting Point](/stories/fabric-vs-databricks-governance-start)
- Governance Ownership and Stewardship Model
- Data Product Contract and Quality Evidence
- Identity-, Access- und PII-Control-Design
- Platform Cost Accountability Model

Der Chooser entscheidet, ob Databricks auf die Shortlist gehört. Diese Seite entscheidet, ob die Organisation bereit ist, Databricks als governed Einstiegspunkt zu betreiben.

## Nächster Schritt

Wähle einen governed Vertical Slice und vervollständige das Readiness-Artefakt, bevor eine breite Catalog-Hierarchie aufgebaut wird.

Der Slice sollte eine Quelle, einen Ingestion Path, einen Transformation- oder Streaming-Prozess, ein governed Data oder AI Product und einen realen Consumer enthalten. Führe die Boundary-, Identity-, Policy-, Lineage-, Quality- und Cost-Tests für diesen Slice aus. Genehmige Databricks erst, wenn die Evidenz zeigt, dass fachliche Entscheidungen verantwortlich bleiben, Plattformkontrollen wiederholbar sind und externe Gaps sichtbar werden.

Der No-regret Next Step besteht nicht darin, weitere Workspaces oder Catalogs anzulegen. Er besteht darin, die erste Governance-Entscheidung explizit zu machen und zu testen, ob die Plattform sie umsetzen kann, ohne Ownership-, Lineage-, Quality- oder Kostenschulden zu verstecken.
