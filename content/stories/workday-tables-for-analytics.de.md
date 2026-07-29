---
title: "Welche Workday-Objekte laden — und welche skippen"
description: "Einen governten Workday Source Scope aus einer benannten Workforce-Entscheidung, effective-dated Beziehungen, Worker- und Event-Grain, Security Domains und strikter Feldminimierung ableiten."
author: Thomas Lindackers
tags:
  - Workday
  - HR Analytics
  - Source Scope
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/workday-tables-for-analytics-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 7
---

Workday muss über eine benannte Workforce-, Finance- oder Control-Entscheidung und die relevanten effective-dated Business Relationships gescopt werden. Ein Custom Report oder Integration Output ist eine Implementierung und nicht die Business-Definition.

Das Ziel ist keine generische Tabellenliste, die jede Workday-Tenant laden sollte. Das Ziel ist ein prüfbarer **Source Scope** für eine Entscheidung oder eine kompatible Gruppe von Entscheidungen. Er dokumentiert, warum jedes Objekt, jede Tabelle, Beziehung oder jedes Feld eingeschlossen, konditional, zurückgestellt, ausgeschlossen oder getrennt wird.

## Problem

Ein kataloggetriebener Ansatz startet mit dem Inventar aus Business Objects, Reports und Integration Outputs, einem Connector-Inventar oder einer Exportvorschau. Weil eine Struktur existiert und abgefragt werden kann, wird sie „für später“ ausgewählt. Das Ergebnis ist eine breite Landing Zone, deren Business-Bedeutung, Grain und Access Boundary erst geklärt werden, wenn Downstream-Teams bereits Daten verbinden.

Ein typisches Inventar kann enthalten:

- Worker- und Contingent-Worker-Kontext
- Position, Job Profile, Manager und Supervisory Organization
- Company, Cost Center, Location und organisatorische Hierarchie
- Staffing-, Recruiting-, Compensation-, Time- und Absence-Events
- Custom Reports, Calculated Fields und RaaS Outputs
- Current-, Trended-, Effective-Dated- und Snapshot-Strukturen
- Payroll, Benefits, Health, Documents, Notes und weitere Restricted Contents

Diese Kategorien führen nicht automatisch zu Include oder Exclude. Ihre Bedeutung hängt von der konfigurierten Anwendung, aktivierten Features, Custom Extensions und dem gewählten Geschäftsprozess ab.

![Welche Workday-Objekte laden — und welche skippen](images/playbooks/workday-tables-for-analytics-img1-de.png)

Typische Fehlermuster sind vorhersehbar:

- technisch verfügbare Strukturen werden mit freigegebenen analytischen Quellen verwechselt;
- Current State, History, Events und Snapshots werden ohne Zeitvertrag vermischt;
- Parent-, Child-, Header-, Line- oder Association-Strukturen werden ohne Ziel-Grain verbunden;
- Display Labels gelten als stabile Business-Definitionen, obwohl die konfigurierte Semantik abweicht;
- personenbezogene Daten, Freitext und Attachments erweitern Access- und Retention-Boundary;
- Custom Process Structures werden ignoriert, weil Standardnamen vertrauter wirken;
- jedes Downstream-Produkt erfindet eigene Interpretationen, Duplication Rules und Exception Handling.

### Unterschiedliche Entscheidungen benötigen unterschiedliche Scopes

- Active Workforce nach Organisation benötigt Worker-Position-Organization-Beziehungen für ein explizites Effective Date.
- Open Positions und Hiring Demand benötigen Position- und Recruiting-Scope, nicht das vollständige Worker Profile.
- Compensation Analysis benötigt separate Zweckfreigabe, Population, Period, Security und Aggregation Controls.
- Absence- oder Time-Analytics benötigt Event- oder Period-Grain und eine klare Trennung zwischen freigegebenen Ergebnissen, geplanten Events und sensiblen Details.

Der richtige Source Scope ist deshalb kontextabhängig. Er wird aus der Entscheidung abgeleitet und gegen die reale Konfiguration validiert.

## Decision

Definiere den Scope in der folgenden Reihenfolge. Connector- und Extraction Design folgen später.

### 1. Entscheidung, Population und Ziel-Grain formulieren

Beschreibe die Anforderung als Entscheidung mit Nutzer, Handlung, Population, Zeithorizont und Grain. „Ein Dashboard aus Workday-Tenant bauen“ ist zu schwach, weil damit nicht feststeht, welche Datensätze, Beziehungen oder historischen Zustände erforderlich sind.

Beschreibe für jeden vorgesehenen Fakt eine Zeile in Business-Begriffen. Definiere das Event, das den Fakt erzeugt, die interpretierenden Dimensionen und das steuernde Reporting Date. Eine Quellzeile ist nicht automatisch ein analytischer Fakt.

### 2. Strukturen nach Business Role klassifizieren

![Quellstrukturen nach Business Role klassifizieren](images/playbooks/workday-tables-for-analytics-img2-de.png)

### Core Workforce Context
- Worker oder Contingent Worker
- Position und Job Profile
- Supervisory Organization
- Company, Cost Center und Location
- Manager und organisatorische Hierarchie

### Conditional Facts und Events
- Staffing Events
- Recruiting- und Candidate-Daten
- Compensation und Payroll
- Time, Absence und Leave
- Learning-, Performance- oder Talent-Daten

### Controlled Derived Sources
- Custom Reports
- Calculated Fields
- Integration Outputs
- freigegebene Snapshots

### Normalerweise Exclude oder Restrict
- unbeschränkte Worker-Profile-Felder
- Health-, Benefits- oder Document Content ohne benannten Zweck
- Notes, Attachments und Case Text
- doppelte Reports mit widersprüchlichen Berechnungen
- Technical Integration Metadata

Die Gruppen sind Muster und keine universellen Listen. Reale Application Configuration, Custom Objects, aktivierte Modules, Security und Business Meaning haben Vorrang vor generischen Beispielen.

### 3. Relationships vor den Joins modellieren

Nutze ein beziehungszentriertes Modell statt einer flachen Tabellenliste:

```text
Worker
→ Position
→ Job Profile
→ Supervisory Organization
→ Company / Cost Center / Location

Events: Hire; Transfer; Organization Change; Compensation Change; Leave; Return; Termination; Rescind
```

Für jede Beziehung werden dokumentiert:

- Effective Start und End
- Current-, Historical- oder Future-Dated-State
- Primary gegenüber Additional Job
- Worker Type und Contingent-Worker-Behandlung
- Correction- und Rescind-Verhalten
- Business Key und Reference ID
- Security Classification und erlaubte Population

Ein Join wird nicht allein deshalb freigegeben, weil beide Keys verfügbar sind. Ein technisch gültiger Join kann Fakten duplizieren, aktuellen Kontext auf historische Events anwenden oder eine ungelöste Many-to-many-Beziehung erzeugen.

![Relationships und Event-Grain respektieren](images/playbooks/workday-tables-for-analytics-img3-de.png)

Teste explizit diese Failure Cases:

- die aktuelle Organisation wird auf historische Fakten angewendet
- mehrere Positionen werden in einen Current Record kollabiert
- Future-Dated Changes werden zu früh sichtbar
- rescinded Events bleiben gültig
- die Manager-Hierarchie wird ohne Effective Dates rekonstruiert

### 4. Current State, Events, History und Snapshots trennen

Der Source Scope unterscheidet:

- Effective Date und Entry Date
- Current-, Historical- und Future-Dated-State
- Corrections und Rescinds
- Event History gegenüber Effective-Dated Snapshot
- periodengranulare Payroll-, Compensation-, Time- oder Absence-Facts

Ein generischer Created- oder Updated-Timestamp beweist keine vollständige Business History. Audit Data ist nicht automatisch ein Process Event Log. Für Point-in-time-Rekonstruktion muss feststehen, welcher Zustand erhalten bleibt, wie Corrections erscheinen und ob Plattform-Snapshots benötigt werden.

### 5. Field-, Privacy- und Access-Controls anwenden

Die Aufnahme eines Objekts oder einer Tabelle autorisiert nicht alle Felder. Erstelle eine Allowlist und entscheide separat über:

- direkte Identifier und Worker-Profile-Daten
- Compensation-, Payroll- und Financial Detail
- Health-, Benefits- und Leave-Informationen
- Documents, Notes, Attachments und Case Text
- Security-Domain- und Population-Restriktionen

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

![Die Source-Scope-Entscheidung dokumentieren](images/playbooks/workday-tables-for-analytics-img4-de.png)

### Pflichtfelder

| Feld | Zweck |
| Workforce Decision und Population | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Workday Business Object oder Report | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Source Interface oder Integration | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Target Data Product | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Worker-, Position-, Event- oder Period-Grain | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Effective-Date- und Correction-Semantik | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Required Fields und Calculated Fields | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Organization- und Hierarchy Relationship | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Worker-Type- und Multiple-Job-Handling | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Security Domain und Permitted Use | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| PII- und Sensitivity Classification | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Retention- und Deletion-Requirement | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Freshness | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Business-, Data- und Integration Owner | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |
| Decision: Include, Conditional, Defer, Exclude oder Restricted Product | Erforderliche Entscheidungsevidenz für den freigegebenen Source Scope |

### Erforderliche Outputs

- freigegebener Objekt- und Feld-Scope
- Security-Domain-Mapping
- Effective-Date-Contract
- Restricted-Data-Boundary
- Duplicate-Report-Retirement-Liste
- Reconciliation- und Access-Review-Controls

Das Artefakt wird versioniert. Neue Modules, Custom Objects, Prozessänderungen, Statusmodelle, Access-Policy-Änderungen oder wesentliche Data-Quality-Incidents lösen einen Review aus. Queryability ist keine Freigabe.

## Tools

Nutze den [Source Scope Builder](/tools/source-scope-builder), um Include-, Conditional-, Defer-, Exclude- und Separate-Product-Entscheidungen mit Grain, Autorität, Risiko und Review Triggern zu dokumentieren.

Nutze [Suppliers](/tools/suppliers) für produktspezifischen Kontext und den [PII and DSDR Readiness Checker](/tools/pii-dsdr-readiness-checker), wenn der Scope personenbezogene Daten, Freitext, Löschungen oder Betroffenenrechte umfasst.

Die Tools strukturieren Evidenz. Sie ersetzen keine Freigabe durch Data Owner, Steward, Application Owner, Privacy oder Security.

## Resources

- [Workday: Building Custom Reports](https://doc.workday.com/workday-education/en-us/course-manuals/creating-and-securing-integrations/building-custom-reports.html)
- [Workday: Configurable Security Overview](https://doc.workday.com/workday-education/en-us/course-manuals/student-for-administrators/configurable-security-overview.html)
- [Workday: Reporting with HCM Data](https://doc.workday.com/workday-education/en-us/course-manuals/advanced-workday-reporting-for-hcm/reporting-with-hcm-data.html)

Die konfigurierte Workday-Tenant bleibt die entscheidende technische Evidenz. Produktdokumentation beschreibt Capabilities; sie bestimmt nicht Business Authority, Grain oder Permitted Use.

## Playbooks

- [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) — Business Question, Grain, Ownership, Quality Expectations und kleinsten vollständigen Vertical Slice vor dem Source Onboarding definieren.
- [PII and Privacy Governance](/playbooks/pii-privacy-governance) — Klassifikation, Permitted Use, Zugriff, Retention, Deletion und Evidenz für personenbezogene und sensible Daten festlegen.

Verwende diese Entscheidungen wieder. Erfinde sie nicht unabhängig in der Connector-Konfiguration neu.

## Next step

Gib Population, Effective Date, Objekt-, Feld- und Security-Domain-Scope frei, bevor RaaS Report, EIB, Studio-, REST- oder SOAP-Extraktion gebaut werden. Der nächste Teil wendet dieselben Prinzipien auf ServiceNow Inheritance, SLA Events, Journals und CMDB Class Boundaries an.
