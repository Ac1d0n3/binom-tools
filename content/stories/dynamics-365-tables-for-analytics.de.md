---
title: "Welche Dynamics-365-Tabellen laden — und welche skippen"
description: "Einen governten Dynamics-365- und Dataverse-Source-Scope aus konfiguriertem Geschäftsprozess, Ziel-Grain, Tabellenbeziehungen, Activities, Option-Semantik und Security Boundary ableiten."
author: Thomas Lindackers
tags:
  - Dynamics 365
  - Dataverse
  - Source Scope
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/dynamics-365-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 5
---

Eine Dynamics-365-Umgebung ist eine konfigurierte Dataverse-Anwendungslandschaft. Die Verfügbarkeit einer Standardtabelle beweist weder ihre tatsächliche Nutzung noch ihre Autorität oder Eignung für den gewählten analytischen Prozess.

Das Ziel ist keine generische Tabellenliste, die jede Dynamics-365-Umgebung laden sollte. Das Ziel ist ein prüfbarer **Source Scope** für eine Entscheidung oder eine kompatible Gruppe von Entscheidungen. Er dokumentiert, warum jedes Objekt, jede Tabelle, Beziehung oder jedes Feld eingeschlossen, konditional, zurückgestellt, ausgeschlossen oder getrennt wird.

## Problem

Ein kataloggetriebener Ansatz startet mit dem Katalog aus Dataverse-Tabellen und Solutions, einem Connector-Inventar oder einer Exportvorschau. Weil eine Struktur existiert und abgefragt werden kann, wird sie „für später“ ausgewählt. Das Ergebnis ist eine breite Landing Zone, deren Business-Bedeutung, Grain und Access Boundary erst geklärt werden, wenn Downstream-Teams bereits Daten verbinden.

Ein typisches Inventar kann enthalten:

- Account-, Contact-, Lead- und Opportunity-Tabellen
- Opportunity Product, Quote, Order, Invoice und weitere Line-Item-Tabellen
- Product-, Price-, Currency-, Date- und Organisationsreferenzen
- Users, Teams, Business Units und Ownership-Strukturen
- Activities, ActivityPointer und Activity-Party-Beziehungen
- Custom Tables, Managed Solutions und Application Extensions
- Audit, Annotations, Attachments, Telemetry und Configuration Tables

Diese Kategorien führen nicht automatisch zu Include oder Exclude. Ihre Bedeutung hängt von der konfigurierten Anwendung, aktivierten Features, Custom Extensions und dem gewählten Geschäftsprozess ab.

![Welche Dynamics-365-Tabellen laden — und welche skippen](images/playbooks/dynamics-365-tables-for-analytics-img1-de.png)

Typische Fehlermuster sind vorhersehbar:

- technisch verfügbare Strukturen werden mit freigegebenen analytischen Quellen verwechselt;
- Current State, History, Events und Snapshots werden ohne Zeitvertrag vermischt;
- Parent-, Child-, Header-, Line- oder Association-Strukturen werden ohne Ziel-Grain verbunden;
- Display Labels gelten als stabile Business-Definitionen, obwohl die konfigurierte Semantik abweicht;
- personenbezogene Daten, Freitext und Attachments erweitern Access- und Retention-Boundary;
- Custom Process Structures werden ignoriert, weil Standardnamen vertrauter wirken;
- jedes Downstream-Produkt erfindet eigene Interpretationen, Duplication Rules und Exception Handling.

### Unterschiedliche Entscheidungen benötigen unterschiedliche Scopes

- Pipeline nach Opportunity und Owner benötigt Opportunity-Grain, konfigurierte Status-Semantik sowie ausgewählten Account-, Owner- und Currency-Kontext.
- Revenue nach Produkt benötigt Opportunity-Product-, Quote-Line-, Order-Line- oder Invoice-Line-Grain; diese Positionsstrukturen dürfen nicht in einen Header-Fakt gemischt werden.
- Service Analytics benötigt die tatsächlichen Case- oder Service-Application-Tabellen und den konfigurierten Lifecycle, nicht jede installierte Sales-Tabelle.
- Activity Analytics benötigt einen definierten Activity-Subtype, Participant Role, Datums- und Ziel-Grain-Regel; das Laden von Activity Parent und Subtype ohne Duplikationsregel bläht Counts auf.

Der richtige Source Scope ist deshalb kontextabhängig. Er wird aus der Entscheidung abgeleitet und gegen die reale Konfiguration validiert.

## Decision

Definiere den Scope in der folgenden Reihenfolge. Connector- und Extraction Design folgen später.

### 1. Entscheidung, Population und Ziel-Grain formulieren

Beschreibe die Anforderung als Entscheidung mit Nutzer, Handlung, Population, Zeithorizont und Grain. „Ein Dashboard aus Dynamics-365-Umgebung bauen“ ist zu schwach, weil damit nicht feststeht, welche Datensätze, Beziehungen oder historischen Zustände erforderlich sind.

Beschreibe für jeden vorgesehenen Fakt eine Zeile in Business-Begriffen. Definiere das Event, das den Fakt erzeugt, die interpretierenden Dimensionen und das steuernde Reporting Date. Eine Quellzeile ist nicht automatisch ein analytischer Fakt.

### 2. Strukturen nach Business Role klassifizieren

![Quellstrukturen nach Business Role klassifizieren](images/playbooks/dynamics-365-tables-for-analytics-img2-de.png)

### Erforderlich für den gewählten Prozess
- Account oder Contact, wenn der konfigurierte Prozess sie benötigt
- Lead oder Opportunity
- Opportunity Product bei Positions-Grain
- Product-, Price- und Currency-Referenzen
- Owner, User, Team und Business Unit für Verantwortlichkeit oder Security Scope
- erforderliche State-, Status-, Datums- und Organisationssemantik

### Conditional
- Quote, Sales Order und Invoice
- Case- oder Service-Tabellen
- Activities und Activity Parties
- ausgewählte Audit- oder Status-Historie
- Campaign- oder Marketing-Tabellen
- Custom Tables und Solution Extensions

### Skip oder separates Produkt ohne Bedarf
- ungenutzte Application Modules
- doppelte Activity-Repräsentationen
- unbeschränkte Annotations und Attachments
- umfangreiche Audit-Daten ohne Control Case
- Platform Telemetry und Configuration Noise
- Convenience Exports mit unklarer Autorität

Die Gruppen sind Muster und keine universellen Listen. Reale Application Configuration, Custom Objects, aktivierte Modules, Security und Business Meaning haben Vorrang vor generischen Beispielen.

### 3. Relationships vor den Joins modellieren

Nutze ein beziehungszentriertes Modell statt einer flachen Tabellenliste:

```text
Lead
→ Opportunity
→ Quote
→ Sales Order
→ Invoice

Kontext: Account oder Contact; Line Items; Product und Price List; Owner, Team und Business Unit
```

Für jede Beziehung werden dokumentiert:

- Business Event der Beziehung
- Header- oder Line-Grain
- Key, Kardinalität und Optionalität
- Bedeutung von State und Status
- Currency- und Datumssemantik
- Current- gegenüber Historical-Bedeutung
- Ownership-, Organisations- und PII-Klassifikation

Ein Join wird nicht allein deshalb freigegeben, weil beide Keys verfügbar sind. Ein technisch gültiger Join kann Fakten duplizieren, aktuellen Kontext auf historische Events anwenden oder eine ungelöste Many-to-many-Beziehung erzeugen.

![Relationships und Event-Grain respektieren](images/playbooks/dynamics-365-tables-for-analytics-img3-de.png)

Teste explizit diese Failure Cases:

- Header- und Line-Beträge werden doppelt gezählt
- ActivityPointer- und Activity-Subtype-Zeilen werden als separate Aktivitäten gezählt
- inaktive Datensätze werden entfernt, obwohl die Entscheidung sie benötigt
- Choice Labels gehen verloren und nur numerische Werte bleiben
- Custom Process Tables werden ausgelassen, weil Standardnamen vertraut wirken

### 4. Current State, Events, History und Snapshots trennen

Der Source Scope unterscheidet:

- aktueller Datensatzstatus
- konfigurierte State- und Status-Transitions
- ausgewählte Audit-Historie
- Event-Fakten aus expliziten Prozessmeilensteinen
- Plattform-Snapshots für Point-in-time Reporting

Ein generischer Created- oder Updated-Timestamp beweist keine vollständige Business History. Audit Data ist nicht automatisch ein Process Event Log. Für Point-in-time-Rekonstruktion muss feststehen, welcher Zustand erhalten bleibt, wie Corrections erscheinen und ob Plattform-Snapshots benötigt werden.

### 5. Field-, Privacy- und Access-Controls anwenden

Die Aufnahme eines Objekts oder einer Tabelle autorisiert nicht alle Felder. Erstelle eine Allowlist und entscheide separat über:

- personenbezogene Daten in Account-, Contact- und Activity-Feldern
- Descriptions, Annotations und E-Mail-Inhalte
- Attachments und binäre Inhalte
- User-, Team- und Organisationsgrenzen
- Deactivation-, Merge- und Deletion-Verhalten

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

![Die Source-Scope-Entscheidung dokumentieren](images/playbooks/dynamics-365-tables-for-analytics-img4-de.png)

### Pflichtfelder

| Feld | Zweck |
| Logical und Display Table Name | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Application oder Solution | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business Purpose | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Target Data Product | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Beitrag zum Ziel-Grain | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Required Columns | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Relationship, Key und Kardinalität | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Choice-, State- und Status-Semantik | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Current-, Audit-, Event- oder Snapshot-Bedarf | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Currency-, Time-Zone- und Organization Scope | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| PII-, Notes- und Attachment-Risiko | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Deactivation-, Merge- und Deletion-Verhalten | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Freshness und Retention | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business und Technical Owner | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Decision und Review Trigger | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |

### Erforderliche Outputs

- freigegebener Tabellen- und Spaltenumfang
- Relationship Map
- Choice- und Status-Mapping-Contract
- explizite Skip-Liste
- Reconciliation Requirements
- Ingestion Handoff

Das Artefakt wird versioniert. Neue Modules, Custom Objects, Prozessänderungen, Statusmodelle, Access-Policy-Änderungen oder wesentliche Data-Quality-Incidents lösen einen Review aus. Queryability ist keine Freigabe.

## Tools

Nutze den [Source Scope Builder](/tools/source-scope-builder), um Include-, Conditional-, Defer-, Exclude- und Separate-Product-Entscheidungen mit Grain, Autorität, Risiko und Review Triggern zu dokumentieren.

Nutze [Suppliers](/tools/suppliers) für produktspezifischen Kontext und den [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker), wenn der Scope personenbezogene Daten, Freitext, Löschungen oder Betroffenenrechte umfasst.

Die Tools strukturieren Evidenz. Sie ersetzen keine Freigabe durch Data Owner, Steward, Application Owner, Privacy oder Security.

## Resources

- [Microsoft-Dokumentation zu Dataverse-Tabellen](https://learn.microsoft.com/en-us/power-apps/developer/data-platform/entity-metadata)
- [Microsoft-Dokumentation zu Dataverse Activities](https://learn.microsoft.com/en-us/power-apps/developer/data-platform/activity-entities)
- [Dataverse Auditing verwalten](https://learn.microsoft.com/en-us/power-platform/admin/manage-dataverse-auditing)

Die konfigurierte Dynamics-365-Umgebung bleibt die entscheidende technische Evidenz. Produktdokumentation beschreibt Capabilities; sie bestimmt nicht Business Authority, Grain oder Permitted Use.

## Playbooks

- [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) — Business Question, Grain, Ownership, Quality Expectations und kleinsten vollständigen Vertical Slice vor dem Source Onboarding definieren.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — Klassifikation, Permitted Use, Zugriff, Retention, Deletion und Evidenz für personenbezogene und sensible Daten festlegen.

Verwende diese Entscheidungen wieder. Erfinde sie nicht unabhängig in der Connector-Konfiguration neu.

## Next step

Gib Prozess-, Tabellen-, Relationship- und Feld-Scope frei, bevor Synapse Link, Fabric Link, Connector oder API-Extraktion entworfen werden. Der nächste Teil wechselt zu SAP S/4 und trennt physische Tabellen von unterstützten semantischen Extraktionsquellen.
