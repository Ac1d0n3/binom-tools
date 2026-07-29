---
title: "Microsoft Fabric als Governance-Einstieg"
description: "Nutze Microsoft Fabric als Governance-Einstieg, wenn die bestehende Microsoft- und Power-BI-Landschaft die Umsetzung vereinfacht und verantwortliche Rollen klare Grenzen für Domains, Workspaces, Katalog, Zugriff, Lineage, Qualität, Semantic Models und Capacity definieren können."
author: Thomas Lindackers
tags:
  - data-governance
  - microsoft-fabric
  - onelake-catalog
  - microsoft-purview
  - power-bi
  - data-products
  - operating-model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/microsoft-fabric-governance-start-hero.png
series: governance-platform-starting-points
seriesTitle: "Governance-Einstiegspunkte für Datenplattformen"
seriesPart: 2
---

## Problem

Microsoft Fabric kann den Weg von den Quelldaten bis zu einem Power-BI-Produkt verkürzen. Data Factory, Data Engineering, Data Warehouse, Real-Time Intelligence, Data Science, Semantic Models und Reports können innerhalb einer SaaS-Analytics-Umgebung betrieben werden, während OneLake die gemeinsame Datengrundlage bildet. Für eine Organisation mit etabliertem Microsoft Tenant, Microsoft Entra ID, Power-BI-Landschaft und Fabric Capacity kann diese Integration reale Delivery-Reibung reduzieren.

Sie erzeugt jedoch nicht automatisch Governance.

Ein Workspace Admin ist nicht automatisch der verantwortliche Data Owner. Eine Fabric Domain vergibt oder entzieht keinen Zugriff. Ein Item Owner im Katalog verantwortet nicht zwangsläufig Business-Definition, erlaubte Nutzung oder Qualitätsrisiko. Ein zertifizierter Report beweist nicht, dass jede enthaltene Kennzahl eine freigegebene Definition, einen Grain, einen Calculation Owner und eine Reconciliation-Methode besitzt. Native Lineage garantiert keine vollständige Source-to-Consumer-Evidenz über alle Workspaces, Item-Typen und externen Transformationen.

Die Entscheidungsfrage lautet deshalb nicht:

> Besitzt Fabric Governance-Funktionen?

Sondern:

> Kann die Organisation Fabric als erste praktikable Governance-Grundlage für ein konkretes governed Data Product nutzen?

Fabric ist ein plausibler Einstiegspunkt, wenn der bestehende Microsoft-Kontext die Umsetzung vereinfacht und verantwortliche fachliche und technische Rollen klare Grenzen definieren und betreiben können für:

- Tenant und Identity
- Capacities
- Domains
- Workspaces
- Fabric Items und OneLake-Daten
- Semantic Models und Reports
- Katalog und Business-Metadaten
- Zugriff und Schutz sensibler Daten
- Lineage-, Quality- und Audit-Evidenz
- Deployment, Support und Kostenverantwortung

![Fabric Governance Surfaces und Decision Owner](images/playbooks/microsoft-fabric-governance-start-img1-de.png)

Die Plattform-Surfaces bilden eine technische Umsetzungshierarchie. Sie bilden keine Hierarchie fachlicher Verantwortung.

### Plattform-Surfaces sind keine Ownership-Entscheidungen

Rollen auf Tenant-, Capacity-, Domain-, Workspace- und Item-Ebene bestimmen, was Benutzer konfigurieren, erstellen, administrieren oder konsumieren dürfen. Diese Rollen sind für den Plattformbetrieb erforderlich. Sie beantworten nicht:

- warum das Data Product existiert
- welche Business-Entscheidung es unterstützt
- welche Definition freigegeben ist
- welche Nutzung erlaubt ist
- welches Qualitätsrisiko akzeptiert wird
- wer eine Ausnahme genehmigt
- wann das Produkt rezertifiziert oder stillgelegt wird

Diese Entscheidungen gehören zu verantwortlichen Data Ownern, unterstützt durch Data Stewards, Security, Architektur, Platform Operations und Delivery-Teams.

### Integration kann fehlende Grenzen verdecken

Eine eng integrierte Plattform kann ein schwaches Operating Model reifer erscheinen lassen, als es ist. Teams können schnell Workspaces, Lakehouses, Warehouses, Semantic Models und Reports anlegen, aber weiterhin ohne folgende Grundlagen arbeiten:

- stabiles Domain-Modell
- benannte Ownership
- konsistente Workspace-Zwecke
- governede Security Groups
- Workflow zur Metadatenpflege
- vollständige Qualitätsnachweise
- Lifecycle Controls
- Capacity- und Kostenverantwortung

Die erste Fabric-Governance-Entscheidung muss deshalb die Grenze definieren, bevor die Plattform skaliert wird.

## Entscheidung

Nutze Microsoft Fabric als Governance-Einstieg, wenn der integrierte Analytics- und Power-BI-Kontext die Delivery-Reibung reduziert und verantwortliche Rollen klare Grenzen über Domains, Workspaces, Katalog, Zugriff, Lineage, Semantic Models, Evidenz und Capacity definieren können.

Behandle Fabric als Operating Surface für das erste governed Data Product, nicht als Beweis bereits vorhandener Governance und nicht als universellen Standard für jeden Microsoft-Kunden.

### 1. Mit dem bestehenden Microsoft-Kontext beginnen

Dokumentiere die aktuelle Landschaft, bevor das Zielbild entsteht.

Die Ausgangsbewertung sollte enthalten:

- Microsoft-Entra-Tenant und Identity-Modell
- bestehende Power-BI-Workspaces, Semantic Models, Reports und Gateways
- Fabric Capacities, SKUs, Regionen und Workload-Nutzung
- aktuelle Tenant Settings und delegierte Administration
- bestehende Microsoft-Purview-Funktionen und Lizenzierung
- Quellsysteme und Ingestion-Muster
- Data-Residency-, Netzwerk- und Private-Access-Anforderungen
- aktuelle CI/CD-, Git- und Deployment-Praktiken
- Skills für Plattform, BI, Security und Stewardship
- Supportmodell und Kostenverantwortung

Fabric ist eher ein sinnvoller Startpunkt, wenn das erste governed Data Product bereits von Power BI, Microsoft Identity und einem Microsoft-betriebenen Analytics-Pfad abhängt. Die Eignung sinkt, wenn der Use Case umfangreiches Cross-Cloud Engineering benötigt, ein bestehender Nicht-Microsoft-Katalog autoritativ ist oder die Organisation Fabric und Purview nicht als koordinierte, aber getrennte Control Surfaces betreiben kann.

### 2. Business Domains und Fabric Domains trennen

Fabric Domains gruppieren Workspaces und deren Items logisch und unterstützen Organisation, Discovery und delegierte Administration. Die Domain-Zuordnung verändert für sich allein weder Sichtbarkeit noch Zugriff auf Items.

Eine Fabric Domain sollte deshalb ein freigegebenes Business-Domain-Modell umsetzen und dieses nicht eigenständig erfinden.

Vor dem Anlegen oder Umbauen von Domains müssen folgende Punkte entschieden sein:

- dargestellte Business Capability oder Subject Area
- verantwortlicher Domain Owner
- Steward-Netzwerk
- Data Products und Entscheidungen im Scope
- Regeln für die Zuordnung von Workspaces
- delegierte Settings und Administration
- Shared-Data- und Cross-Domain-Abhängigkeiten
- Ausnahmen und Eskalation

Eine Domain kann federierte Governance unterstützen, aber Ownership nicht ersetzen. Domain Admins und Contributors setzen die Domain-Organisation technisch um. Data Owner bleiben verantwortlich für Definitionen, Nutzung, Qualität und Risiko.

### 3. Jedem Workspace einen expliziten Betriebszweck geben

Ein Workspace ist eine Collaboration-, Administrations-, Lifecycle- und Security-Surface. Er sollte nicht zur Standardeinheit für jede Governance-Entscheidung werden.

Jeder governed Workspace sollte dokumentieren:

- Zweck und Lifecycle-Phase
- zugeordnete Fabric Domain
- verantwortlicher Data Owner für die enthaltenen Produkte
- Workspace Admin und Technical Owner
- Contributor-, Member- und Viewer-Gruppen
- erlaubte Item-Typen
- Umgebung: Development, Test oder Production
- Deployment-Pfad
- Capacity-Zuordnung
- Support- und Incident-Route
- Retention- und Stilllegungsregel

Vermeide Workspaces, die ausschließlich nach Teamnamen angelegt werden, wenn Produkt-, Umgebungs- oder Security-Grenzen unterschiedlich sind. Vermeide einen gemeinsamen Workspace für nicht zusammengehörende Grains, Owner oder Lifecycle-Phasen, nur weil dasselbe Delivery-Team sie entwickelt.

### 4. Definieren, wo Fabric endet und Purview beginnt

Fabric und Microsoft Purview überlappen, sind aber nicht austauschbar.

![Wo Fabric endet und Purview beginnt](images/playbooks/microsoft-fabric-governance-start-img2-de.png)

#### Fabric-native Operating Surface

Nutze Fabric-native Funktionen für den täglichen Betrieb von Fabric Assets, insbesondere:

- Domains und Workspaces
- Discovery von Fabric Items über den OneLake Catalog
- Item Details, Tags, Endorsements und Sensitivity Labels, soweit unterstützt
- Workspace Lineage und Impact Analysis
- Semantic-Model- und Power-BI-Delivery
- Tenant Settings und delegierte Administration
- Capacity-Zuordnung und Monitoring
- Git Integration und Deployment Pipelines, soweit unterstützt

Der OneLake Catalog ist der Fabric-zentrierte Einstieg für Discovery und Governance. Die Explore Experience unterstützt das Auffinden von Fabric Items. Die Govern Experience stellt Governance Insights, empfohlene Maßnahmen und Verweise auf relevante Tools bereit. Diese Insights basieren auf Fabric-Metadaten und besitzen dokumentierte Einschränkungen. Sie sind kein vollständiges Enterprise-Governance-Operating-Model.

#### Purview Governance Surface

Nutze Microsoft Purview, wenn Governance über Fabric hinaus in die gesamte Datenlandschaft reichen muss. Abhängig von den ausgewählten Purview-Funktionen, der Lizenzierung und regionalen Verfügbarkeit kann dies umfassen:

- Enterprise Catalog und Business Glossary
- Governance Domains und Data Products
- Data-Map-Scanning und Metadaten-Ingestion
- plattformübergreifende Discovery
- automatisierte Klassifizierung
- Business-Metadaten und Terminologie
- breiteren Lineage- und Impact-Kontext
- Information Protection, Audit und DLP
- Data Quality für unterstützte Quellen und Asset-Typen

Das Scannen eines Fabric Tenants kann Fabric-Metadaten und Lineage in Microsoft Purview übernehmen. Die aktuelle Abdeckung und Granularität variiert nach Item-Typ. Für Nicht-Power-BI-Items dokumentiert Microsoft unter anderem Einschränkungen bei der Granularität, unvollständige Sub-Item-Lineage und fehlende Cross-Workspace-Lineage in bestimmten Szenarien. Diese Grenzen müssen für das ausgewählte Data Product getestet werden.

#### Business Governance

Weder Fabric noch Purview sollten folgende Entscheidungen treffen:

- verantwortliche Ownership
- freigegebene Definitionen
- erlaubte Nutzung
- Risikoakzeptanz
- Ausnahmegenehmigung
- Zertifizierungskriterien
- Review und Rezertifizierung
- Deprecation

Die Plattformen speichern, zeigen, erzwingen oder belegen Entscheidungen. Das Operating Model trifft sie.

### 5. Identity und Access als eine kontrollierte Kette entwerfen

Das Access-Modell sollte mit Microsoft-Entra-Gruppen und benannten Group Ownern beginnen, nicht mit individuellen Berechtigungen, die während der Entwicklung hinzugefügt werden.

Das Design muss unterscheiden zwischen:

- Tenant Administration
- Capacity Administration
- Domain Administration
- Workspace Roles
- Item Permissions
- OneLake-Tabellen- und Ordnerberechtigungen, soweit anwendbar
- SQL-Endpoint- oder Warehouse-Berechtigungen
- Semantic-Model-Berechtigungen
- Report- und App-Verteilung
- Row-Level oder Object-Level Security in Semantic Models
- externem Sharing und Guest Access
- Service Principals und Managed Identities

Workspace Membership allein ist für ein governed Data Product häufig zu breit. OneLake Security kann über Custom Roles einen granulareren Lesezugriff auf ausgewählte Lakehouse-Tabellen und Ordner ermöglichen. Der erforderliche Access-Pfad muss jedoch über alle Engines und Konsumenten des Produkts getestet werden.

Für jede Access-Entscheidung sind zu dokumentieren:

- Business Purpose
- Identity Group
- Group Owner
- freigegebene Surface
- erlaubte Operationen
- Bedingungen für sensible Daten
- Freigabe
- Ablauf- oder Review-Datum
- Evidenzquelle

Erfolgreiche Authentifizierung ist nicht mit erlaubter Business-Nutzung gleichzusetzen.

### 6. Klassifizierung und Sensitivity Labels als Controls behandeln

Sensitivity Labels aus Microsoft Purview Information Protection können auf unterstützte Fabric- und Power-BI-Items angewendet werden. Sie helfen bei Klassifizierung und Schutz, ersetzen aber nicht:

- PII-Klassifizierung auf Feldebene
- eine Entscheidung zur erlaubten Nutzung
- Access Design
- Masking- oder Filtering-Anforderungen
- Retention- und Löschregeln
- Incident Ownership

Das governed Data Product muss einen Klassifizierungspfad von den Quellattributen über transformierte Daten und Semantic Models bis zu Reports und Exporten definieren.

Für jedes sensible Attribut sind festzuhalten:

- PII-Kategorie
- Sensitivity Level
- autoritative Klassifizierungsentscheidung
- erlaubter Zweck
- Access Restriction
- Masking-, Omit- oder Filtering-Anforderung
- Export- und Sharing-Beschränkung
- Retention Rule
- erwartete Downstream-Propagation
- Control Owner

Da Label-Unterstützung, Vererbung und Enforcement je nach Fabric Item und Consumption-Pfad variieren können, muss der Proof of Value den tatsächlichen End-to-End-Fluss testen.

### 7. Das governed Data Product mit dem Semantic Model verbinden

Das Semantic Model ist nicht nur eine Reporting-Hilfe. In vielen Power-BI-Landschaften bildet es den governeden Consumption Contract.

Das Modell muss explizit machen:

- Business Process und Grain
- Conformed Dimensions und Keys
- freigegebene Measures
- Time- und Filter-Verhalten
- Calculation Ownership
- Row-Level und Object-Level Security
- Freshness-Erwartung
- Quality State
- Source Lineage
- Change- und Compatibility-Regeln
- Owner, Steward und Technical Owner
- Publication- und Deprecation-Status

Ein Report kann ein zertifiziertes Semantic Model nutzen und trotzdem durch lokale Report Measures Definition Drift erzeugen. Certification muss deshalb an Evidenz über Semantic Model und Kennzahlen gebunden sein und nicht nur an ein sichtbares Badge.

### 8. Source-to-Consumer-Evidenz aufbauen

![Von der Quelle zum vertrauenswürdigen Power-BI-Produkt](images/playbooks/microsoft-fabric-governance-start-img3-de.png)

Das erste governed Data Product sollte eine Evidenzkette über folgende Stufen pflegen:

```text
Quelle
→ Ingestion
→ Transformation
→ governed Data Product
→ Semantic Model
→ zertifizierte Kennzahl oder Report
→ Konsument
```

Auf jeder Stufe werden dokumentiert:

- Owner
- Grain
- Klassifizierung
- Access-Entscheidung
- Lineage
- Quality-Evidenz
- Change Record

Die Fabric Lineage View zeigt Beziehungen innerhalb eines Workspaces und externe Quellen eine Stufe Upstream. Downstream-Abhängigkeiten in anderen Workspaces benötigen Impact Analysis. Für eine breitere Data-Estate-Lineage können Purview-Scans oder zusätzliche Evidenz erforderlich sein. Ein verbunden aussehendes Lineage-Diagramm ist kein Beweis für einen vollständigen Graphen.

Typische Fehlerbilder:

- Workspace Ownership wird mit Data Ownership verwechselt
- Report Certification ohne Kennzahlenfreigabe
- versteckte lokale Measures
- Cross-Workspace-Lineage-Lücken
- Quality Results nur in einem Development Notebook
- Source Changes ohne Consumer-Migrationsnachweis
- ein sensibles Source Label ohne Validierung in Exporten oder Downstream Tools

### 9. Qualität operationalisieren

Qualität ist nicht abgeschlossen, wenn eine Regel einmal erfolgreich ausgeführt wurde. Ein governed Fabric Product benötigt:

- freigegebene Regeln mit Bezug zu Business Expectations
- Rule Owner und Incident Owner
- Schwellenwerte und Severity
- Ausführungsplan
- gespeicherte Failure Evidence
- Zuordnung zu betroffenen Products und Consumers
- Remediation Workflow
- Ausnahmefreigabe und Ablaufdatum
- für Konsumenten sichtbaren Health State
- Change- und Recertification-Trigger

Microsoft Purview Data Quality kann unterstützte Fabric-Lakehouse-Tabellen nach erforderlicher Registrierung, Scan und Konfiguration prüfen. Aktuelle Unterstützung, Voraussetzungen, Formate und Lizenzierung müssen validiert werden. Plattform-native Notebooks, Pipelines, SQL Tests oder Drittanbieter-Tools können weiterhin erforderlich sein. Entscheidend ist nicht, welcher Screen einen Score zeigt, sondern wer Regel, Fehler und Wiederherstellung verantwortet.

### 10. Deployment, Capacity und Kosten governeden

Fabric Capacity ist eine gemeinsam genutzte Betriebsressource. Ein governed Data Product kann trotz korrekter Daten-Controls scheitern, wenn Capacity Saturation, Deployment Drift oder unklare Kostenverantwortung den Service unzuverlässig machen.

Das Operating Model sollte definieren:

- Capacity Owner
- Capacity Admin
- Regeln für Workload- und Workspace-Zuordnung
- Monitoring und Alerting
- Scale-, Pause- und Reservation-Entscheidungen, soweit anwendbar
- Cost Allocation oder Showback
- Trennung von Development, Test und Production
- Nutzung von Git und Deployment Pipelines
- Validierung unterstützter Item-Typen
- Deployment-Freigaben und Rollback
- Compatibility-Anforderungen für Semantic Models
- Incident- und Service-Level-Ownership

Deployment Pipelines unterstützen nicht jedes Item in gleicher Weise, und die Unterstützung verändert sich. Microsoft hat beispielsweise am 12. Februar 2026 die Pipeline-Unterstützung für Semantic Models ohne Enhanced Metadata beendet. Der Release-Prozess muss deshalb aktuelle unterstützte Items und Einschränkungen validieren, statt einen vollständigen Workspace als einheitlich deploybar anzunehmen.

## Checkliste

Fabric darf erst als Startpunkt freigegeben werden, wenn die folgende Evidenz verfügbar ist.

### Kontext und erster Use Case

- Ist ein erster governed Use Case benannt?
- Sind Business-Entscheidung, Konsumenten und Wert klar?
- Reduziert der aktuelle Microsoft- und Power-BI-Kontext die Delivery-Reibung tatsächlich?
- Ist die Alternative ohne neue Plattform dokumentiert?

### Ownership und Decision Rights

- Ist ein Data Owner verantwortlich für Zweck, Definition, erlaubte Nutzung und Risiko?
- Ist ein Steward verantwortlich für Metadaten, Issue Coordination und Review-Evidenz?
- Sind Platform Admins klar von fachlicher Verantwortung getrennt?
- Sind Ausnahme- und Eskalationswege definiert?

### Domain- und Workspace-Modell

- Setzt jede Fabric Domain eine freigegebene Business-Domain-Entscheidung um?
- Besitzt jeder Workspace einen eindeutigen Betriebszweck?
- Sind Domain-Zuordnung, Umgebung, Produkt, Security und Lifecycle dokumentiert?
- Wird Workspace Sprawl kontrolliert?

### Katalog- und Purview-Grenze

- Wird der OneLake Catalog für Fabric-zentrierte Discovery und Governance Insights genutzt?
- Wird Microsoft Purview eingesetzt, wenn Enterprise Catalog, Glossary, breitere Klassifizierung oder plattformübergreifende Metadaten erforderlich sind?
- Ist für doppelte Metadatenfelder ein autoritativer Pflegeworkflow festgelegt?
- Sind Scan-, Refresh- und Lineage-Limitierungen dokumentiert?

### Identity und Access

- Werden Entra-Gruppen statt unkontrollierter Einzelberechtigungen verwendet?
- Besitzt jede Gruppe einen Owner und Review-Prozess?
- Sind Workspace-, Item-, OneLake-, SQL- und Semantic-Model-Berechtigungen gemeinsam entworfen?
- Sind Service Principals, Gäste und externes Sharing berücksichtigt?
- Kann effektiver Zugriff von der Quelle bis zum Report getestet werden?

### PII und Sensitivity

- Sind sensible Attribute auf Feldebene klassifiziert?
- Ist die Sensitivity-Label-Strategie für unterstützte Items definiert?
- Sind Masking, Filtering, Omit und Export Controls explizit?
- Sind Vererbung und Downstream-Verhalten getestet?
- Sind Retention- und Incident-Verantwortung zugeordnet?

### Lineage und Change Evidence

- Reicht die Source-to-Consumer-Lineage für Impact Analysis aus?
- Sind Cross-Workspace- und nicht unterstützte Transformationen dokumentiert?
- Erzeugt jedes Deployment einen Change Record?
- Können Konsumenten vor einem Breaking Change identifiziert werden?

### Qualität und Certification

- Sind Quality Rules freigegeben und versioniert?
- Werden Fehler gespeichert und an einen benannten Owner geroutet?
- Erfordert Certification Evidenz zu Definition, Grain, Lineage, Qualität und Ownership?
- Werden lokale Report Measures kontrolliert?
- Sind Recertification- und Deprecation-Trigger definiert?

### Semantic- und Power-BI-Fit

- Ist das Semantic Model der governed Consumption Contract?
- Sind Measure Definitions, Time Behavior und Filter Semantics freigegeben?
- Sind Row-Level und Object-Level Security getestet?
- Sind Reports ausreichend thin, um doppelte Business-Logik zu vermeiden?

### Deployment und Umgebungen

- Sind Development-, Test- und Production-Grenzen explizit?
- Werden Git und Deployment Pipelines nur für unterstützte Items verwendet?
- Sind Freigaben, Rollback und Compatibility Tests definiert?
- Werden manuelle Production Changes kontrolliert und belegt?

### Capacity, Betrieb und Kosten

- Ist ein Capacity Owner benannt?
- Werden Workload Consumption, Throttling und Service Health überwacht?
- Sind Support- und Incident-Routen dokumentiert?
- Sind Lizenz-, Regions- und Preview-Abhängigkeiten erfasst?
- Können Kosten ausreichend Products, Workspaces oder Domains zugeordnet werden?

Fabric bleibt nur bedingt bereit, wenn verpflichtende Controls von einer ungetesteten Funktion, unklarer Ownership, nicht unterstützter Lineage, fehlender Betriebskapazität oder offenen Lizenz- und Regionsfragen abhängen.

## Artefakt

Dokumentiere das Ergebnis in einer **Fabric Governance Boundary Map** und einer **Fabric Governance Readiness Decision**.

![Die Fabric-Governance-Readiness-Entscheidung dokumentieren](images/playbooks/microsoft-fabric-governance-start-img4-de.png)

### Pflichtfelder

| Feld | Erforderliche Evidenz |
|---|---|
| Erster governed Use Case | Benanntes Data Product, Business-Entscheidung, Konsumenten und initialer Scope |
| Tenant- und Capacity-Kontext | Tenant, Region, Capacity, SKU, aktuelle Workloads und Constraints |
| Domain- und Workspace-Modell | Business-Domain-Zuordnung, Workspace-Zweck, Umgebung und Lifecycle |
| Data Owner und Steward | Verantwortlicher Owner, operativer Steward und Eskalationsweg |
| Katalog- und Purview-Grenze | Rolle des OneLake Catalog, Rolle von Purview, autoritativer Metadatenworkflow und Handoffs |
| Identity- und Access-Modell | Entra-Gruppen, Workspace Roles, Item Access, OneLake-/SQL-/Semantic-Permissions und Review |
| PII- und Sensitivity-Controls | Attributklassifizierung, Labels, Masking/Filtering, Export, Retention und Incident Controls |
| Lineage- und Quality-Evidenz | Erforderlicher Pfad, verfügbare Automatisierung, manuelle Evidenz, Quality Rules und Incident Route |
| Semantic-Model-Ownership | Grain, Measures, Security, Owner, Certification und Deprecation |
| Deployment- und Umgebungsmodell | Dev/Test/Prod, Git, Pipelines, Freigaben, Rollback und Supported-Item-Limitierungen |
| Capacity- und Kostenverantwortung | Capacity Owner, Monitoring, Zuordnung, Cost Owner und Optimierungsentscheidungen |
| Lücken und Validierungstests | Unbekannte Punkte, nicht unterstützte Pfade, Preview-Abhängigkeiten und Proof-of-Value-Tests |

### Erforderliche Outputs

- **Bereit für Proof of Value** — das erste Data Product kann die vollständige Governance-Kette testen.
- **Bedingte Readiness** — Fabric ist plausibel, aber benannte Controls müssen noch validiert werden.
- **Blocker** — fehlende Ownership, nicht unterstützter Control-Pfad, Capacity-, Lizenz-, Regions- oder Betriebsgrenzen verhindern den Start.
- **Alternative ohne neue Plattform** — der bestehende Stack kann dieselben Controls mit geringerem Risiko oder geringeren Kosten erfüllen.
- **Review-Datum** — die Entscheidung wird nach dem Proof of Value oder einer wesentlichen Plattformänderung neu bewertet.

### Struktur der Boundary Map

Die Boundary Map sollte fünf verbundene Ebenen enthalten:

1. **Business Governance** — Owner, Steward, Definitionen, erlaubte Nutzung, Qualitätsrisiko, Certification und Lifecycle.
2. **Fabric-Organisation** — Tenant, Capacities, Domains, Workspaces und Item-Typen.
3. **Control Implementation** — Identity, Permissions, Sensitivity, Quality, Deployment und Monitoring.
4. **Metadaten und Evidenz** — OneLake Catalog, Purview, Lineage, Audit, Quality Results und Change Records.
5. **Consumption** — Semantic Models, zertifizierte Kennzahlen, Reports, Apps, Excel und weitere Konsumenten.

Jeder Handoff muss einen Decision Owner, Implementation Owner und eine Evidenzquelle benennen.

### Entscheidungsregel

Fabric darf nur als Einstiegspunkt freigegeben werden, wenn die Organisation folgende Aussage treffen kann:

> Fabric hostet und betreibt das erste governed Data Product, weil der bestehende Microsoft- und Power-BI-Kontext die Delivery-Reibung reduziert. Entscheidungen zu Business Ownership, erlaubter Nutzung, Qualität und Certification bleiben außerhalb der Plattformadministration. Die Verantwortung von OneLake Catalog und Purview ist explizit. Identity, Schutz sensibler Daten, Lineage, Semantic-Model-Governance, Deployment, Capacity und Kosten werden über den vollständigen Source-to-Consumer-Pfad validiert.

Die Plattform darf nicht allein deshalb freigegeben werden, weil sie bereits lizenziert ist, weil Power BI bereits genutzt wird oder weil eine Governance-Oberfläche vorhanden ist.

## Tools

Nutze Tools zur Sammlung und Pflege von Entscheidungsevidenz.

### Governance Stack Advisor

Nutze den [Governance Stack Advisor](/tools/governance-stack-advisor), um Fabric mit dem bestehenden Stack und einer möglichen bedingten Alternative zu vergleichen.

Erwarteter Output:

- Profil des bestehenden Kontexts
- verpflichtende Governance Controls
- Fabric-Stärken und Abhängigkeiten
- Capability- und Operating-Model-Lücken
- Alternative ohne neue Plattform
- Validierungsfragen

### Architecture Fit

Nutze [Architecture Fit](/tools/architecture-fit), um Workload-, Integrations-, Identity-, Netzwerk-, Regions-, Deployment-, Capacity- und Koexistenzanforderungen zu testen.

Erwarteter Output:

- Workload Boundaries
- Source- und Consumer-Constraints
- Zielmodell für Workspaces und Capacity
- Integrations- und Koexistenzwirkung
- Validierungsarchitektur
- Entscheidungsrisiken

### Compliance Roadmap

Nutze die [Compliance Roadmap](/compliance), um PII-, Sensitivity-, Retention-, Access-, Audit- und Recertification-Anforderungen mit einer umsetzbaren Control-Sequenz zu verbinden.

### Fabric-spezifische Arbeitsartefakte

- Canvas für den ersten governeden Use Case
- Fabric Domain- und Workspace-Register
- Ownership- und Stewardship-RACI
- Entra-Gruppen- und Access-Matrix
- Control-Matrix nach Item-Typ
- PII- und Sensitivity-Propagation-Map
- Verantwortungsmatrix für Fabric- und Purview-Metadaten
- Lineage Coverage Map
- Quality-Rule- und Incident-Register
- Semantic-Model-Certification-Record
- Deployment- und Umgebungsregister
- Capacity- und Kostenverantwortungsnachweis
- Fabric Governance Boundary Map
- Fabric Governance Readiness Decision

Die Verfügbarkeit eines Tools ist keine Evidenz. Jeder erforderliche Control muss für die ausgewählten Item-Typen, Lizenzen, Region und den Consumption-Pfad konfiguriert, betrieben und getestet werden.

## Ressourcen

Funktionen, Bezeichnungen, APIs, Lizenzierung, Preview-Status, regionale Verfügbarkeit und Einschränkungen von Microsoft Fabric und Purview müssen zum Umsetzungszeitpunkt erneut geprüft werden. Die folgenden offiziellen Quellen wurden für diesen Artikel am 29. Juli 2026 verifiziert:

- [Microsoft-Fabric-Übersicht](https://learn.microsoft.com/de-de/fabric/fundamentals/microsoft-fabric-overview)
- [OneLake-Catalog-Übersicht](https://learn.microsoft.com/de-de/fabric/governance/onelake-catalog-overview)
- [Fabric-Daten mit dem OneLake Catalog governeden](https://learn.microsoft.com/de-de/fabric/governance/onelake-catalog-govern)
- [Fabric Domains](https://learn.microsoft.com/de-de/fabric/governance/domains)
- [Microsoft Purview zur Governance von Microsoft Fabric verwenden](https://learn.microsoft.com/de-de/fabric/governance/microsoft-purview-fabric)
- [Microsoft Purview Data Map](https://learn.microsoft.com/de-de/purview/data-map)
- [Metadaten und Lineage von Fabric in Microsoft Purview](https://learn.microsoft.com/de-de/purview/data-map-lineage-fabric)
- [Lineage in Fabric](https://learn.microsoft.com/de-de/fabric/governance/lineage)
- [Microsoft-Fabric-Berechtigungsmodell](https://learn.microsoft.com/de-de/fabric/security/permission-model)
- [Sensitivity Labels auf Fabric Items anwenden](https://learn.microsoft.com/de-de/fabric/fundamentals/apply-sensitivity-labels)
- [Power-BI-Inhalte mit Endorsement promoten und zertifizieren](https://learn.microsoft.com/de-de/power-bi/collaborate-share/service-endorsement-overview)
- [Data Quality für Fabric Lakehouse in Microsoft Purview Unified Catalog](https://learn.microsoft.com/de-de/purview/unified-catalog-data-quality-fabric-lakehouse)
- [Fabric Deployment Pipelines](https://learn.microsoft.com/de-de/fabric/cicd/deployment-pipelines/intro-to-deployment-pipelines)
- [Microsoft-Fabric-Lizenzen verstehen](https://learn.microsoft.com/de-de/fabric/enterprise/licenses)
- [Microsoft-Fabric-Capacity-Planung](https://learn.microsoft.com/de-de/fabric/enterprise/capacity-planning-overview)
- [Kostenaspekte für Microsoft-Fabric-Workloads](https://learn.microsoft.com/de-de/azure/well-architected/microsoft-fabric/cost-optimization)
- [Neuerungen in Microsoft Fabric](https://learn.microsoft.com/de-de/fabric/fundamentals/whats-new)

## Playbooks

Nutze diese Story zusammen mit den folgenden Entscheidungs- und Implementierungs-Playbooks:

- [Fabric, Databricks, Snowflake oder BigQuery — Den Governance-Einstieg auswählen](/stories/choose-governance-platform-starting-point)
- [Fabric vs Databricks — Den Governance-Einstieg auswählen](/stories/fabric-vs-databricks-governance-start)
- [Nicht mit der Plattform beginnen](/stories/do-not-start-with-the-platform)
- [Den ersten governeden Vertical Slice bauen](/stories/build-first-governed-vertical-slice)
- [Den Data Product Contract definieren](/playbooks/data-product-contract)
- [Ownership vor Tooling definieren](/playbooks/data-ownership)
- [Operational Data Quality](/series/operational-data-quality)
- [Governance Architecture](/series/governance-architecture)

Die Plattformauswahl-Story definiert, wann Fabric in die Shortlist gehört. Diese Story definiert, was erfüllt sein muss, bevor Fabric zur praktischen Governance-Grundlage für das erste governed Data Product wird.

## Nächster Schritt

Wähle einen bestehenden Power-BI-Entscheidungspfad und verfolge ihn bis zur autoritativen Quelle zurück.

Vervollständige anschließend die Fabric Governance Boundary Map:

1. Data Owner und Steward benennen
2. Business-Frage, freigegebenen Grain und Konsumenten definieren
3. Fabric Domain und Workspace-Zweck zuordnen
4. Grenze zwischen OneLake Catalog und Purview dokumentieren
5. Entra-Gruppen und effektiven Zugriff entwerfen
6. PII und Sensitivity von der Quelle bis zum Export klassifizieren
7. Lineage und bekannte Coverage-Lücken abbilden
8. Quality Rules, Schwellenwerte und Incident Ownership definieren
9. Semantic-Model-Ownership und Certification-Evidenz zuordnen
10. Development-, Test-, Production- und Deployment-Controls definieren
11. Capacity-, Support- und Kostenverantwortung zuordnen
12. einen Proof of Value über die vollständige Source-to-Consumer-Kette durchführen
13. Readiness, Bedingungen, Blocker, die Alternative ohne neue Plattform und das Review-Datum dokumentieren

Die nächste plattformspezifische Story kann Databricks und Unity Catalog anschließend mit denselben Governance-Evidenzkategorien prüfen, ohne einen weiteren generischen Feature-Vergleich zu wiederholen.
