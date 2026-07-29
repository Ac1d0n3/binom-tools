---
title: "Welche ServiceNow-Tabellen laden — und welche skippen"
description: "Einen governten ServiceNow Source Scope aus operativer Entscheidung, Tabellenvererbung, Event-Grain, Referenzsemantik, CMDB-Class-Boundaries, Journals und Security ableiten."
author: Thomas Lindackers
tags:
  - ServiceNow
  - ITSM Analytics
  - CMDB
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/servicenow-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 8
---

Eine ServiceNow-Tabellenhierarchie ist ein operatives Anwendungsmodell. Parent- und Child-Classes, References, SLAs, Journals, Audit Records und CMDB Relations müssen in einen expliziten analytischen Grain und Scope übersetzt werden.

Das Ziel ist keine generische Tabellenliste, die jede ServiceNow-Instanz laden sollte. Das Ziel ist ein prüfbarer **Source Scope** für eine Entscheidung oder eine kompatible Gruppe von Entscheidungen. Er dokumentiert, warum jedes Objekt, jede Tabelle, Beziehung oder jedes Feld eingeschlossen, konditional, zurückgestellt, ausgeschlossen oder getrennt wird.

## Problem

Ein kataloggetriebener Ansatz startet mit dem Inventar aus Tabellenhierarchie, Referenzen und Operational Events, einem Connector-Inventar oder einer Exportvorschau. Weil eine Struktur existiert und abgefragt werden kann, wird sie „für später“ ausgewählt. Das Ergebnis ist eine breite Landing Zone, deren Business-Bedeutung, Grain und Access Boundary erst geklärt werden, wenn Downstream-Teams bereits Daten verbinden.

Ein typisches Inventar kann enthalten:

- Task Parent sowie Incident-, Problem-, Change-, Request- und weitere Child Classes
- Requests, Catalog und Workflow Records
- Task SLA und weitere Operational Events
- Users, Groups, Assignments und Reference Records
- CMDB-CI-Classes, Services und Relationships
- Audit, State History, Journals und Snapshots
- Attachments, Configuration, System und Technical Tables

Diese Kategorien führen nicht automatisch zu Include oder Exclude. Ihre Bedeutung hängt von der konfigurierten Anwendung, aktivierten Features, Custom Extensions und dem gewählten Geschäftsprozess ab.

![Welche ServiceNow-Tabellen laden — und welche skippen](images/playbooks/servicenow-tables-for-analytics-img1-de.png)

Typische Fehlermuster sind vorhersehbar:

- technisch verfügbare Strukturen werden mit freigegebenen analytischen Quellen verwechselt;
- Current State, History, Events und Snapshots werden ohne Zeitvertrag vermischt;
- Parent-, Child-, Header-, Line- oder Association-Strukturen werden ohne Ziel-Grain verbunden;
- Display Labels gelten als stabile Business-Definitionen, obwohl die konfigurierte Semantik abweicht;
- personenbezogene Daten, Freitext und Attachments erweitern Access- und Retention-Boundary;
- Custom Process Structures werden ignoriert, weil Standardnamen vertrauter wirken;
- jedes Downstream-Produkt erfindet eigene Interpretationen, Duplication Rules und Exception Handling.

### Unterschiedliche Entscheidungen benötigen unterschiedliche Scopes

- Incident Backlog nach Service und Assignment Group benötigt Incident- oder governten Task-Class-Grain, aktuellen Assignment-Kontext und ein separat entworfenes Historical View für Point-in-time Backlog.
- SLA Performance benötigt Task-SLA-Event-Grain und Regeln für mehrere SLA Records je Task; sie ist nicht nur eine zusätzliche Incident-Spalte.
- Change Risk benötigt die konfigurierte Change Class, State- und Approval-Semantik sowie ausgewählten CI- oder Service-Kontext, nicht einen vollständigen CMDB-Dump.
- Service Impact Analysis benötigt eine allowlist-basierte Auswahl von CI Classes und Relationship Types aus der Service-Frage.

Der richtige Source Scope ist deshalb kontextabhängig. Er wird aus der Entscheidung abgeleitet und gegen die reale Konfiguration validiert.

## Decision

Definiere den Scope in der folgenden Reihenfolge. Connector- und Extraction Design folgen später.

### 1. Entscheidung, Population und Ziel-Grain formulieren

Beschreibe die Anforderung als Entscheidung mit Nutzer, Handlung, Population, Zeithorizont und Grain. „Ein Dashboard aus ServiceNow-Instanz bauen“ ist zu schwach, weil damit nicht feststeht, welche Datensätze, Beziehungen oder historischen Zustände erforderlich sind.

Beschreibe für jeden vorgesehenen Fakt eine Zeile in Business-Begriffen. Definiere das Event, das den Fakt erzeugt, die interpretierenden Dimensionen und das steuernde Reporting Date. Eine Quellzeile ist nicht automatisch ein analytischer Fakt.

### 2. Strukturen nach Business Role klassifizieren

![Quellstrukturen nach Business Role klassifizieren](images/playbooks/servicenow-tables-for-analytics-img2-de.png)

### Core Process Facts
- Incident, Problem oder Change bei Bedarf
- Request oder Request Item
- ausgewählte Task- oder Workflow-Records
- Task SLA für definierte Service-Level-Analyse

### Required Context
- Users, Groups und Assignment
- Services, Offerings oder ausgewählte Configuration Items
- State-, Priority-, Category- und Calendar-References
- freigegebene Custom Tables

### Conditional History und Controls
- State Transitions
- Audit Evidence
- Reassignment- oder Approval-Events
- Snapshots für Backlog- oder Point-in-time-Analyse

### Normalerweise Skip oder Separate
- doppelte Parent- und Child-Repräsentationen
- unbeschränkte Journals und Work Notes
- Attachments und Binary Content
- breite CMDB- oder System-Table-Dumps
- Technical Logs ohne Operational Product

Die Gruppen sind Muster und keine universellen Listen. Reale Application Configuration, Custom Objects, aktivierte Modules, Security und Business Meaning haben Vorrang vor generischen Beispielen.

### 3. Relationships vor den Joins modellieren

Nutze ein beziehungszentriertes Modell statt einer flachen Tabellenliste:

```text
Task
├─ Incident
├─ Problem
├─ Change
└─ Request oder andere Child Class

Conditional Facts: Task SLA; Assignment Group und User; CI oder Business Service; State Transition oder Snapshot
```

Für jede Beziehung werden dokumentiert:

- Class- und sys_class_name-Bedeutung
- Parent- gegenüber Child-Repräsentation
- Business Event und Ziel-Grain
- Reference Key und Display Value
- One-to-many-Effekt
- Current- gegenüber Historical-Bedeutung
- Domain-, Role- und PII-Klassifikation

Ein Join wird nicht allein deshalb freigegeben, weil beide Keys verfügbar sind. Ein technisch gültiger Join kann Fakten duplizieren, aktuellen Kontext auf historische Events anwenden oder eine ungelöste Many-to-many-Beziehung erzeugen.

![Relationships und Event-Grain respektieren](images/playbooks/servicenow-tables-for-analytics-img3-de.png)

Teste explizit diese Failure Cases:

- Parent- und Child-Zeilen werden doppelt gezählt
- mehrere SLA Records werden als ein Task behandelt
- aktueller CI- oder Assignment-Kontext wird auf historische Events angewendet
- Journal Updates werden als Task Events gezählt
- CMDB Relationship Traversal lässt den Fact Count explodieren

### 4. Current State, Events, History und Snapshots trennen

Der Source Scope unterscheidet:

- aktueller Task State
- State-Transition-History
- SLA Events und Breach Timing
- validierte Audit Evidence
- bewusst erzeugte Snapshots für Backlog oder Point-in-time State

Ein generischer Created- oder Updated-Timestamp beweist keine vollständige Business History. Audit Data ist nicht automatisch ein Process Event Log. Für Point-in-time-Rekonstruktion muss feststehen, welcher Zustand erhalten bleibt, wie Corrections erscheinen und ob Plattform-Snapshots benötigt werden.

### 5. Field-, Privacy- und Access-Controls anwenden

Die Aufnahme eines Objekts oder einer Tabelle autorisiert nicht alle Felder. Erstelle eine Allowlist und entscheide separat über:

- Descriptions, Comments, Work Notes und Journals
- Attachments und Binary Content
- User- und Group-Daten
- Domain- und Role-Access-Boundaries
- Retention-, Archive- und Deletion-Verhalten

Bevorzuge kontrollierte Kategorie, Count, Flag oder abgeleitetes Age, wenn die Entscheidung keinen Raw Content benötigt. Permitted Use, Role- und Domain Access, Masking, Retention, Deletion Propagation und Incident Ownership werden vor der Extraktion definiert.

### 6. Einen expliziten Decision State vergeben

Nutze mehr als eine binäre Load-or-ignore-Entscheidung:

- **Include** — Bedeutung, Autorität, Grain, Zugriff und Quality Controls sind freigegeben.
- **Conditional** — die Quelle wird nur für eine benannte Variante oder Kennzahl mit klarer Aktivierungsbedingung benötigt.
- **Defer** — der Bedarf ist valide, aber Ownership-, History-, Security-, Quality- oder Extraction-Evidence ist unvollständig.
- **Exclude** — kein freigegebener analytischer Zweck existiert oder die Struktur ist redundant, instabil oder unverhältnismäßig riskant.
- **Separate Product** — ein Operational-, Security-, Audit- oder Restricted-Use-Case existiert, darf aber nicht in das allgemeine Business-Produkt gemischt werden.

Jede nicht eingeschlossene Entscheidung benötigt Rationale und Review Trigger.

### 7. Gegen das konfigurierte System validieren

Namen und Standardbeispiele sind keine Verträge. Prüfe den tatsächlichen Prozess mit Business Ownern, Application Administrators, Privacy, Security und Integration Team. Bestätige Custom Objects, aktivierte Modules, Statusmodelle, Labels, Keys, Access und Lifecycle-Verhalten im realen Tenant oder in der Instanz.

## Checklist

### Entscheidung und Grain

- [ ] Die Business-Frage benennt Nutzer, Handlung, Population und Zeithorizont.
- [ ] Jeder Fakt besitzt einen deklarierten Business Grain.
- [ ] Event und Reporting Date sind explizit.
- [ ] Current State, Event History und Snapshots sind getrennt.
- [ ] Kennzahlen und Status-Semantik besitzen accountable Owner.

### Quellstrukturen und Relationships

- [ ] Jede eingeschlossene Struktur unterstützt eine benannte Anforderung.
- [ ] Custom und application-spezifische Strukturen wurden geprüft.
- [ ] Keys, Kardinalität und Optionalität sind dokumentiert.
- [ ] Duplicate-, Orphan- und Many-to-many-Verhalten ist definiert.
- [ ] Reference Labels und Codes besitzen ein freigegebenes Mapping.

### Risiko und Lifecycle

- [ ] Eine Field Allowlist existiert.
- [ ] PII, sensible Attribute und Freitext sind klassifiziert.
- [ ] Attachments und Binary Content besitzen eine separate Entscheidung.
- [ ] Deletion-, Merge-, Archive- oder Deactivation-Verhalten ist definiert.
- [ ] Retention und Permitted Use sind freigegeben.

### Betrieb

- [ ] Freshness und erwartetes Volumen sind verstanden.
- [ ] Completeness, Uniqueness und Relationship Quality sind testbar.
- [ ] Business und Technical Owner sind benannt.
- [ ] Reconciliation Controls sind definiert.
- [ ] Jede Defer-, Exclude- oder Separate-Product-Entscheidung besitzt einen Review Trigger.

## Artifact

Erstelle ein governtes Source-Scope-Register je Data Product oder kompatiblem Entscheidungsportfolio. Es ist der freigegebene Vertrag zwischen Business Requirement und späterem Extraction Design.

![Die Source-Scope-Entscheidung dokumentieren](images/playbooks/servicenow-tables-for-analytics-img4-de.png)

### Pflichtfelder

| Feld | Zweck |
| Table- und Class-Name | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Parent- oder Extension-Relationship | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Application oder Plugin | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business Purpose und Target Data Product | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Task-, Event-, SLA-, CI- oder Snapshot-Grain | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Required Columns und References | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Display-Value- und Choice-Mapping | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Current-, Transition-, Audit- oder Snapshot-Bedarf | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Journal-, Attachment- und Free-Text-Decision | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Domain-, Role- und Access-Boundary | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Deletion-, Archive- und Retention-Verhalten | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Freshness- und Volume-Erwartung | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business und Platform Owner | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Decision und Duplication Rule | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Rationale und Review Trigger | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |

### Erforderliche Outputs

- freigegebener Tabellen- und Class-Scope
- Inheritance- und Reference-Map
- Parent-Child-Duplication-Controls
- Field Allowlist und Text Boundary
- CMDB Class Allowlist
- History- und SLA-Event-Contract
- Ingestion Handoff

Das Artefakt wird versioniert. Neue Modules, Custom Objects, Prozessänderungen, Statusmodelle, Access-Policy-Änderungen oder wesentliche Data-Quality-Incidents lösen einen Review aus. Queryability ist keine Freigabe.

## Tools

Nutze den [Source Scope Builder](/tools/source-scope-builder), um Include-, Conditional-, Defer-, Exclude- und Separate-Product-Entscheidungen mit Grain, Autorität, Risiko und Review Triggern zu dokumentieren.

Nutze [Suppliers](/tools/suppliers) für produktspezifischen Kontext und den [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker), wenn der Scope personenbezogene Daten, Freitext, Löschungen oder Betroffenenrechte umfasst.

Die Tools strukturieren Evidenz. Sie ersetzen keine Freigabe durch Data Owner, Steward, Application Owner, Privacy oder Security.

## Resources

- [ServiceNow-Dokumentation zu Table Extension und Classes](https://www.servicenow.com/docs/r/platform-administration/table-administration-and-data-management/table-extension-and-classes.html)
- [ServiceNow-Dokumentation zu Tables and Data Models](https://www.servicenow.com/docs/r/application-development/tables-and-data-models.html)
- [ServiceNow-Dokumentation zu CMDB Class Definitions](https://www.servicenow.com/docs/r/servicenow-platform/configuration-management-database-cmdb/t_ViewTableDefinitions.html)

Die konfigurierte ServiceNow-Instanz bleibt die entscheidende technische Evidenz. Produktdokumentation beschreibt Capabilities; sie bestimmt nicht Business Authority, Grain oder Permitted Use.

## Playbooks

- [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) — Business Question, Grain, Ownership, Quality Expectations und kleinsten vollständigen Vertical Slice vor dem Source Onboarding definieren.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — Klassifikation, Permitted Use, Zugriff, Retention, Deletion und Evidenz für personenbezogene und sensible Daten festlegen.

Verwende diese Entscheidungen wieder. Erfinde sie nicht unabhängig in der Connector-Konfiguration neu.

## Next step

Gib Class-, Inheritance-, Reference-, History-, Journal- und CMDB-Boundaries frei, bevor Table API, IntegrationHub, Connector oder Performance Analytics konfiguriert werden. Der letzte Serienteil klärt die Autorität, wenn mehrere Quellen dieselbe Entity oder dasselbe Attribut enthalten.
