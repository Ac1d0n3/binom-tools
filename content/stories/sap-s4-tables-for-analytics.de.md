---
title: "Welche SAP-S/4-Tabellen für Analytics laden — und welche skippen"
description: "Einen SAP-S/4-Analytics-Source-Scope aus Business Document Flow, Ziel-Grain, Organisations- und Currency-Semantik, Lifecycle-Regeln und unterstützter Extraktionsschnittstelle ableiten."
author: Thomas Lindackers
tags:
  - SAP S/4HANA
  - Source Scope
  - CDS Views
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/sap-s4-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 6
---

Der physische S/4-Tabellenkatalog ist Implementierungsevidenz und kein analytischer Vertrag. Der governte Scope folgt Geschäftsprozess, Document Flow, Ziel-Grain und einer für Deployment und Release geeigneten unterstützten Quelle.

Das Ziel ist keine generische Tabellenliste, die jede SAP-S/4-Landschaft laden sollte. Das Ziel ist ein prüfbarer **Source Scope** für eine Entscheidung oder eine kompatible Gruppe von Entscheidungen. Er dokumentiert, warum jedes Objekt, jede Tabelle, Beziehung oder jedes Feld eingeschlossen, konditional, zurückgestellt, ausgeschlossen oder getrennt wird.

## Problem

Ein kataloggetriebener Ansatz startet mit dem Katalog aus physischen Tabellen, Business Objects und Extraktionsquellen, einem Connector-Inventar oder einer Exportvorschau. Weil eine Struktur existiert und abgefragt werden kann, wird sie „für später“ ausgewählt. Das Ergebnis ist eine breite Landing Zone, deren Business-Bedeutung, Grain und Access Boundary erst geklärt werden, wenn Downstream-Teams bereits Daten verbinden.

Ein typisches Inventar kann enthalten:

- Sales-, Delivery-, Billing-, Purchasing-, Goods-Movement- und Accounting-Dokumente
- Header-, Item-, Schedule-Line-, Journal-Line- und Snapshot-Strukturen
- Business Partner, Material oder Product sowie organisatorische Stammdaten
- Status, Document Flow, Pricing und ausgewähltes Customizing
- Company Code, Sales Organization, Plant, Ledger und Fiscal References
- released CDS Views, APIs, Extractors und weitere unterstützte semantische Quellen
- Technical Logs, obsolete Views, temporäre Datensätze, Text und Attachments

Diese Kategorien führen nicht automatisch zu Include oder Exclude. Ihre Bedeutung hängt von der konfigurierten Anwendung, aktivierten Features, Custom Extensions und dem gewählten Geschäftsprozess ab.

![Welche SAP-S/4-Tabellen für Analytics laden — und welche skippen](images/playbooks/sap-s4-tables-for-analytics-img1-de.png)

Typische Fehlermuster sind vorhersehbar:

- technisch verfügbare Strukturen werden mit freigegebenen analytischen Quellen verwechselt;
- Current State, History, Events und Snapshots werden ohne Zeitvertrag vermischt;
- Parent-, Child-, Header-, Line- oder Association-Strukturen werden ohne Ziel-Grain verbunden;
- Display Labels gelten als stabile Business-Definitionen, obwohl die konfigurierte Semantik abweicht;
- personenbezogene Daten, Freitext und Attachments erweitern Access- und Retention-Boundary;
- Custom Process Structures werden ignoriert, weil Standardnamen vertrauter wirken;
- jedes Downstream-Produkt erfindet eigene Interpretationen, Duplication Rules und Exception Handling.

### Unterschiedliche Entscheidungen benötigen unterschiedliche Scopes

- Order-to-Cash benötigt einen expliziten Document Flow von Sales Order über Delivery und Billing bis zur Accounting-Auswirkung.
- Procure-to-Pay benötigt Purchase-Order-, Receipt- und Invoice-Events auf deklariertem Header- oder Item-Grain statt eines breiten Purchasing-Tabellensatzes.
- Inventory Analytics benötigt Movement-, Quantity-, Unit-, Plant-, Storage- und Posting-Period-Semantik; ein aktueller Stock Snapshot ist nicht mit Movement History austauschbar.
- Record-to-Report benötigt Journal-Entry-Grain, Ledger- und Currency-Kontext, Posting- und Reversal-Semantik sowie kontrollierten Organisations-Scope.

Der richtige Source Scope ist deshalb kontextabhängig. Er wird aus der Entscheidung abgeleitet und gegen die reale Konfiguration validiert.

## Decision

Definiere den Scope in der folgenden Reihenfolge. Connector- und Extraction Design folgen später.

### 1. Entscheidung, Population und Ziel-Grain formulieren

Beschreibe die Anforderung als Entscheidung mit Nutzer, Handlung, Population, Zeithorizont und Grain. „Ein Dashboard aus SAP-S/4-Landschaft bauen“ ist zu schwach, weil damit nicht feststeht, welche Datensätze, Beziehungen oder historischen Zustände erforderlich sind.

Beschreibe für jeden vorgesehenen Fakt eine Zeile in Business-Begriffen. Definiere das Event, das den Fakt erzeugt, die interpretierenden Dimensionen und das steuernde Reporting Date. Eine Quellzeile ist nicht automatisch ein analytischer Fakt.

### 2. Strukturen nach Business Role klassifizieren

![Quellstrukturen nach Business Role klassifizieren](images/playbooks/sap-s4-tables-for-analytics-img2-de.png)

### Transactional Facts
- Sales-, Delivery- und Billing-Dokumente
- Purchase-, Goods-Movement- und Invoice-Dokumente
- Accounting Journal Entries
- Inventory- oder Production-Events

### Master- und Organisationskontext
- Business Partner-, Customer- oder Supplier-Kontext
- Product oder Material
- Company Code, Sales Organization, Plant oder Cost Center
- Currency, Unit und Fiscal Calendar

### Conditional Process Evidence
- Document Flow und Status
- Schedule Lines
- Change- oder Event-Historie
- Pricing Conditions
- ausgewähltes Customizing zur Prozessinterpretation

### Normalerweise Skip, Replace oder Separate
- nicht unterstützte Raw-Table-Copies
- doppelte Extraktionsstrukturen
- obsolete oder deprecated Views
- Technical Logs und temporäre Datensätze
- breite Customizing-Dumps ohne Use Case
- Attachments und unbeschränkter Text

Die Gruppen sind Muster und keine universellen Listen. Reale Application Configuration, Custom Objects, aktivierte Modules, Security und Business Meaning haben Vorrang vor generischen Beispielen.

### 3. Relationships vor den Joins modellieren

Nutze ein beziehungszentriertes Modell statt einer flachen Tabellenliste:

```text
Sales Order
→ Delivery
→ Billing Document
→ Accounting Entry

Kontext: Business Partner; Product oder Material; Sales Organization; Company Code; Plant; Currency; Fiscal Period
```

Für jede Beziehung werden dokumentiert:

- Business Event und Document-Flow-Transition
- Header-, Item- oder Accounting-Line-Grain
- Key, Reference und Kardinalität
- Quantity-, Amount-, Currency- und Unit-Semantik
- Status-, Cancellation- und Reversal-Verhalten
- Posting-, Document- und Effective Date
- analytischer Ziel-Grain und Reconciliation Rule

Ein Join wird nicht allein deshalb freigegeben, weil beide Keys verfügbar sind. Ein technisch gültiger Join kann Fakten duplizieren, aktuellen Kontext auf historische Events anwenden oder eine ungelöste Many-to-many-Beziehung erzeugen.

![Relationships und Event-Grain respektieren](images/playbooks/sap-s4-tables-for-analytics-img3-de.png)

Teste explizit diese Failure Cases:

- Order- und Billing-Beträge werden als derselbe Fakt gezählt
- Header-Werte werden über Items wiederholt
- stornierte oder reversierte Dokumente bleiben in Kennzahlen aktiv
- Local und Group Currency werden vermischt
- Late Postings werden der falschen Fiscal Period zugeordnet

### 4. Current State, Events, History und Snapshots trennen

Der Source Scope unterscheidet:

- Document und Posting Dates
- Effective und Fiscal Periods
- Delta- und Deletion-Verhalten
- Archiving- und Late-Posting-Regeln
- Cancellation, Reversal und Corrected Restatement

Ein generischer Created- oder Updated-Timestamp beweist keine vollständige Business History. Audit Data ist nicht automatisch ein Process Event Log. Für Point-in-time-Rekonstruktion muss feststehen, welcher Zustand erhalten bleibt, wie Corrections erscheinen und ob Plattform-Snapshots benötigt werden.

### 5. Field-, Privacy- und Access-Controls anwenden

Die Aufnahme eines Objekts oder einer Tabelle autorisiert nicht alle Felder. Erstelle eine Allowlist und entscheide separat über:

- personenbezogene Business-Partner- und Employee-Daten
- kommerziell sensible Preise und Margen
- Organisations- und Company-Code-Grenzen
- unbeschränkter Text und Attachments
- nicht unterstützte oder deprecated Extraktionsquellen

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

![Die Source-Scope-Entscheidung dokumentieren](images/playbooks/sap-s4-tables-for-analytics-img4-de.png)

### Pflichtfelder

| Feld | Zweck |
| Business Process und Entscheidung | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business Object | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Technical Table-, CDS-View-, API- oder Extractor-Referenz | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Release- und Support-Status | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Deployment- und Release-Abhängigkeit | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Target Data Product und Grain | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Header-, Item- und Document-Flow-Keys | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Organisations-Scope | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Currency-, Unit- und Fiscal-Semantik | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Delta-, Deletion- und Archiving-Verhalten | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Cancellation- und Reversal-Regel | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Sensitivity Classification | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Freshness und Retention | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business-, Application- und Technical Owner | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Decision: Include, Conditional, Defer, Replace oder Exclude | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |

### Erforderliche Outputs

- freigegebener semantischer Extraktions-Scope
- Field Allowlist
- Source- und Delta-Contract
- Raw-Table-Skip- oder Replacement-Liste
- Reconciliation Controls
- Release-Change-Review-Plan

Das Artefakt wird versioniert. Neue Modules, Custom Objects, Prozessänderungen, Statusmodelle, Access-Policy-Änderungen oder wesentliche Data-Quality-Incidents lösen einen Review aus. Queryability ist keine Freigabe.

## Tools

Nutze den [Source Scope Builder](/tools/source-scope-builder), um Include-, Conditional-, Defer-, Exclude- und Separate-Product-Entscheidungen mit Grain, Autorität, Risiko und Review Triggern zu dokumentieren.

Nutze [Suppliers](/tools/suppliers) für produktspezifischen Kontext und den [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker), wenn der Scope personenbezogene Daten, Freitext, Löschungen oder Betroffenenrechte umfasst.

Die Tools strukturieren Evidenz. Sie ersetzen keine Freigabe durch Data Owner, Steward, Application Owner, Privacy oder Security.

## Resources

- [SAP: Data Extraction for Analytics](https://help.sap.com/docs/SAP_S4HANA_ON-PREMISE/ee6ff9b281d8448f96b4fe6c89f2bdc8/30c3635b37484e7486b851a67effc874.html)
- [SAP: CDS Views Enabled for Data Extraction](https://help.sap.com/docs/SAP_S4HANA_ON-PREMISE/ee6ff9b281d8448f96b4fe6c89f2bdc8/b7a5b8b72d3643b7a8ecf4cd695e0791.html)
- [SAP-S/4HANA-Cloud-Dokumentation zur Datenextraktion](https://help.sap.com/docs/SAP_S4HANA_CLOUD/c0c54048d35849128be8e872df5bea6d/30c3635b37484e7486b851a67effc874.html)

Die konfigurierte SAP-S/4-Landschaft bleibt die entscheidende technische Evidenz. Produktdokumentation beschreibt Capabilities; sie bestimmt nicht Business Authority, Grain oder Permitted Use.

## Playbooks

- [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) — Business Question, Grain, Ownership, Quality Expectations und kleinsten vollständigen Vertical Slice vor dem Source Onboarding definieren.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — Klassifikation, Permitted Use, Zugriff, Retention, Deletion und Evidenz für personenbezogene und sensible Daten festlegen.

Verwende diese Entscheidungen wieder. Erfinde sie nicht unabhängig in der Connector-Konfiguration neu.

## Next step

Gib Business-Document-, Semantic-Source- und Lifecycle-Contract frei, bevor ODP, SLT, Datasphere, BW, APIs oder andere Extraktionstechnologie ausgewählt oder konfiguriert werden. Der nächste Teil überträgt das Source-Scope-Muster auf effective-dated und security-sensitive Workday-Daten.
