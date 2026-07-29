---
title: "Welche HubSpot-Tabellen laden — und welche skippen"
description: "Einen governten HubSpot Source Scope aus konfiguriertem Funnel- oder Serviceprozess, Ziel-Grain, Associations, Property History und Datenrisiken ableiten, statt jedes CRM-Objekt und jede Property zu exportieren."
author: Thomas Lindackers
tags:
  - HubSpot
  - Source Scope
  - CRM Analytics
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/hubspot-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 4
---

Ein HubSpot-Portal ist eine konfigurierte Revenue-, Marketing- und Service-Anwendung und kein universelles Analytics-Modell. Objekte und Properties werden erst dann zu freigegebenen Quellen, wenn sie eine benannte Entscheidung auf einem definierten Grain unterstützen.

Das Ziel ist keine generische Tabellenliste, die jede HubSpot-Portal laden sollte. Das Ziel ist ein prüfbarer **Source Scope** für eine Entscheidung oder eine kompatible Gruppe von Entscheidungen. Er dokumentiert, warum jedes Objekt, jede Tabelle, Beziehung oder jedes Feld eingeschlossen, konditional, zurückgestellt, ausgeschlossen oder getrennt wird.

## Problem

Ein kataloggetriebener Ansatz startet mit dem Katalog aus CRM-Objekten, Properties und Associations, einem Connector-Inventar oder einer Exportvorschau. Weil eine Struktur existiert und abgefragt werden kann, wird sie „für später“ ausgewählt. Das Ergebnis ist eine breite Landing Zone, deren Business-Bedeutung, Grain und Access Boundary erst geklärt werden, wenn Downstream-Teams bereits Daten verbinden.

Ein typisches Inventar kann enthalten:

- Contacts, Companies, Deals und Tickets
- Products, Line Items und aktivierte Commerce-Objekte
- Owner, Teams, Pipelines, Stages und Referenz-Properties
- Aktivitäten wie Calls, Meetings und Communications
- Custom Objects, Custom Properties und Association Labels
- Property History, archivierte Datensätze, Merges und Löschsignale
- Notes, Messages, Files, Attachments und technische Datensätze

Diese Kategorien führen nicht automatisch zu Include oder Exclude. Ihre Bedeutung hängt von der konfigurierten Anwendung, aktivierten Features, Custom Extensions und dem gewählten Geschäftsprozess ab.

![Welche HubSpot-Tabellen laden — und welche skippen](images/playbooks/hubspot-tables-for-analytics-img1-de.png)

Typische Fehlermuster sind vorhersehbar:

- technisch verfügbare Strukturen werden mit freigegebenen analytischen Quellen verwechselt;
- Current State, History, Events und Snapshots werden ohne Zeitvertrag vermischt;
- Parent-, Child-, Header-, Line- oder Association-Strukturen werden ohne Ziel-Grain verbunden;
- Display Labels gelten als stabile Business-Definitionen, obwohl die konfigurierte Semantik abweicht;
- personenbezogene Daten, Freitext und Attachments erweitern Access- und Retention-Boundary;
- Custom Process Structures werden ignoriert, weil Standardnamen vertrauter wirken;
- jedes Downstream-Produkt erfindet eigene Interpretationen, Duplication Rules und Exception Handling.

### Unterschiedliche Entscheidungen benötigen unterschiedliche Scopes

- Pipeline-Steuerung nach Deal Stage und Owner benötigt Deal-Grain, governte Stage-Daten sowie die relevanten Owner- und Company-Associations.
- Revenue nach Produkt benötigt Line-Item-Grain und eine kontrollierte Beziehung vom Deal zum Line Item sowie bei Bedarf zu einer wiederverwendbaren Product Reference.
- Lifecycle Conversion nach Quelle benötigt ein abgestimmtes Lifecycle-Modell, Event- oder Property-History-Semantik und explizite Contact-Company-Association-Regeln.
- Ticket Resolution nach Team benötigt Ticket-Grain, Status- und Datumssemantik, Category-Referenzen und nur die Aktivitäten, die die Kennzahl tatsächlich verwendet.

Der richtige Source Scope ist deshalb kontextabhängig. Er wird aus der Entscheidung abgeleitet und gegen die reale Konfiguration validiert.

## Decision

Definiere den Scope in der folgenden Reihenfolge. Connector- und Extraction Design folgen später.

### 1. Entscheidung, Population und Ziel-Grain formulieren

Beschreibe die Anforderung als Entscheidung mit Nutzer, Handlung, Population, Zeithorizont und Grain. „Ein Dashboard aus HubSpot-Portal bauen“ ist zu schwach, weil damit nicht feststeht, welche Datensätze, Beziehungen oder historischen Zustände erforderlich sind.

Beschreibe für jeden vorgesehenen Fakt eine Zeile in Business-Begriffen. Definiere das Event, das den Fakt erzeugt, die interpretierenden Dimensionen und das steuernde Reporting Date. Eine Quellzeile ist nicht automatisch ein analytischer Fakt.

### 2. Strukturen nach Business Role klassifizieren

![Quellstrukturen nach Business Role klassifizieren](images/playbooks/hubspot-tables-for-analytics-img2-de.png)

### Core für den gewählten Use Case
- Companies oder Contacts, wenn sie den governten Kundenkontext definieren
- Deals für Pipeline- oder Revenue-Entscheidungen
- Tickets für ein definiertes Service-Produkt
- Owner oder Teams, wenn Verantwortlichkeit benötigt wird
- Pipeline-, Stage-, Datums- und Source-Properties der Kennzahl
- Line Items, wenn Revenue explizit auf Positions-Grain liegt

### Conditional
- Products und Product References
- Campaign- und Attributionsobjekte
- Calls, Meetings und Communication Activities
- Quotes, Orders, Invoices, Subscriptions oder Payments, wenn aktiviert und erforderlich
- ausgewählte Property History
- Custom Objects und Custom Association Labels

### Skip ohne konkrete Anforderung
- ungenutzte Feature-Objekte
- All-Property-Exporte
- doppelte Convenience-Repräsentationen
- unbeschränkte Notes, Messages, Files und Attachments
- technische Datensätze ohne benanntes Analytics- oder Control-Produkt

Die Gruppen sind Muster und keine universellen Listen. Reale Application Configuration, Custom Objects, aktivierte Modules, Security und Business Meaning haben Vorrang vor generischen Beispielen.

### 3. Relationships vor den Joins modellieren

Nutze ein beziehungszentriertes Modell statt einer flachen Tabellenliste:

```text
Company ↔ Contact
   \       /
      Deal → Line Item → Product Reference
       |
Ticket oder Activity nur bei Bedarf
```

Für jede Beziehung werden dokumentiert:

- analytischer Zweck der Association
- Richtung, Kardinalität und Optionalität
- Association Label und konfigurierte Business-Bedeutung
- Auswirkung auf den Ziel-Grain
- Ownership für doppelte oder mehrdeutige Beziehungen
- Current- gegenüber Historical-Bedeutung
- PII-Klassifikation und Permitted Use

Ein Join wird nicht allein deshalb freigegeben, weil beide Keys verfügbar sind. Ein technisch gültiger Join kann Fakten duplizieren, aktuellen Kontext auf historische Events anwenden oder eine ungelöste Many-to-many-Beziehung erzeugen.

![Relationships und Event-Grain respektieren](images/playbooks/hubspot-tables-for-analytics-img3-de.png)

Teste explizit diese Failure Cases:

- eine Many-to-many-Beziehung zwischen Contact und Company wird auf eine willkürliche Company reduziert
- ein Deal wird einmal je zugeordnetem Contact gezählt
- Product Definitions und deal-spezifische Line Items werden als derselbe Grain behandelt
- Activities werden durch wiederholte Associations aufgebläht
- Custom Objects des tatsächlichen Prozesses werden ausgelassen

### 4. Current State, Events, History und Snapshots trennen

Der Source Scope unterscheidet:

- aktueller Objektzustand
- Property History für freigegebene Properties
- explizite Lifecycle- oder Business-Events
- plattformseitige Created- und Update-Timestamps
- bewusst erzeugte Snapshots für vollständige Point-in-time-Zustände

Ein generischer Created- oder Updated-Timestamp beweist keine vollständige Business History. Audit Data ist nicht automatisch ein Process Event Log. Für Point-in-time-Rekonstruktion muss feststehen, welcher Zustand erhalten bleibt, wie Corrections erscheinen und ob Plattform-Snapshots benötigt werden.

### 5. Field-, Privacy- und Access-Controls anwenden

Die Aufnahme eines Objekts oder einer Tabelle autorisiert nicht alle Felder. Erstelle eine Allowlist und entscheide separat über:

- E-Mail-Adressen, Telefonnummern und Contact-Identifier
- Notes, Messages und Communication Bodies
- freie Beschreibungen und Betreffzeilen
- Files und Attachments
- Verhalten archivierter, zusammengeführter und gelöschter Datensätze

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

![Die Source-Scope-Entscheidung dokumentieren](images/playbooks/hubspot-tables-for-analytics-img4-de.png)

### Pflichtfelder

| Feld | Zweck |
| Objekt oder API Resource | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business Purpose | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Target Data Product | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Beitrag zum Ziel-Grain | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| freigegebene Property Allowlist | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Association Path, Label, Richtung und Kardinalität | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Current-, Event-, Property-History- oder Snapshot-Bedarf | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Verhalten archivierter, zusammengeführter und gelöschter Datensätze | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| PII- und Freitextrisiko | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Freshness und Retention | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business und Technical Owner | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Decision: Include, Conditional, Defer oder Exclude | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Rationale und Review Trigger | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |

### Erforderliche Outputs

- freigegebener Objektumfang
- Property Allowlist
- freigegebene Association Map
- explizite Skip-Liste
- History- und Deletion-Contract
- offene Fragen und Übergabe an Ingestion

Das Artefakt wird versioniert. Neue Modules, Custom Objects, Prozessänderungen, Statusmodelle, Access-Policy-Änderungen oder wesentliche Data-Quality-Incidents lösen einen Review aus. Queryability ist keine Freigabe.

## Tools

Nutze den [Source Scope Builder](/tools/source-scope-builder), um Include-, Conditional-, Defer-, Exclude- und Separate-Product-Entscheidungen mit Grain, Autorität, Risiko und Review Triggern zu dokumentieren.

Nutze [Suppliers](/tools/suppliers) für produktspezifischen Kontext und den [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker), wenn der Scope personenbezogene Daten, Freitext, Löschungen oder Betroffenenrechte umfasst.

Die Tools strukturieren Evidenz. Sie ersetzen keine Freigabe durch Data Owner, Steward, Application Owner, Privacy oder Security.

## Resources

- [HubSpot-Dokumentation zu CRM Associations](https://developers.hubspot.com/docs/api-reference/legacy/crm/associations/v3/associate-records)
- [HubSpot-Dokumentation zum CRM Object Model](https://developers.hubspot.com/docs/api/crm/understanding-the-crm)
- [HubSpot-Leitfaden zu Custom Objects](https://developers.hubspot.com/docs/api/crm/crm-custom-objects)

Die konfigurierte HubSpot-Portal bleibt die entscheidende technische Evidenz. Produktdokumentation beschreibt Capabilities; sie bestimmt nicht Business Authority, Grain oder Permitted Use.

## Playbooks

- [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) — Business Question, Grain, Ownership, Quality Expectations und kleinsten vollständigen Vertical Slice vor dem Source Onboarding definieren.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — Klassifikation, Permitted Use, Zugriff, Retention, Deletion und Evidenz für personenbezogene und sensible Daten festlegen.

Verwende diese Entscheidungen wieder. Erfinde sie nicht unabhängig in der Connector-Konfiguration neu.

## Next step

Gib Objekt-, Property- und Association-Scope frei, bevor Connector-Auswahl, inkrementelle Extraktion oder Downstream-Joins konfiguriert werden. Der nächste Serienteil wendet dieselbe Entscheidungsdisziplin auf die konfigurierte Dataverse-Landschaft in Dynamics 365 an.
