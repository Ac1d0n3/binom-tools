---
title: "Welche Quelle zuerst laden?"
description: "Die erste governte Quelle über Decision Value, Autorität, Grain Readiness, Ownership, Zugriff, Qualität und Learning Value als kleinsten vollständigen Vertical Slice auswählen."
author: Thomas Lindackers
tags:
  - Source Prioritization
  - Vertical Slice
  - Source Scope
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/which-source-to-load-first-hero.png
series: source-load-decisions
seriesTitle: Lade-Entscheidungen für Quellsysteme
seriesPart: 3
---

Die erste Quelle einer Datenplattform sollte nicht deshalb gewählt werden, weil ihr Connector bereits lizenziert ist oder sie die meisten Tabellen enthält. Sie sollte der kleinste vollständige Source Slice sein, der eine vertrauenswürdige Entscheidung erzeugt und das Operating Model Ende-zu-Ende validiert.

Das Ergebnis ist eine governte Portfolio-Entscheidung: Eine Quelle startet jetzt, wertvolle aber nicht bereite Quellen werden vorbereitet und andere Quellen nachvollziehbar zurückgestellt.

## Problem

Viele Source Roadmaps werden zunächst nach Bequemlichkeit sortiert. Ein Connector ist verfügbar, ein Sponsor sichtbar oder eine große Anwendung wirkt wichtig. Die Ingestion startet, bevor Entscheidung, Autorität, Grain und Consumer Outcome feststehen.

![Nicht mit dem einfachsten Connector starten](images/playbooks/which-source-to-load-first-img1-de.png)

Der Convenience-First-Pfad lautet häufig:

```text
Verfügbarer Connector
→ Großer Raw Load
→ Unklarer Consumer
→ Späte Governance-Fragen
→ Rework
```

Schwache Auswahlkriterien sind:

- der Connector ist bereits lizenziert;
- die Quelle besitzt die meisten Tabellen;
- ein Executive Sponsor fordert Sichtbarkeit;
- der Extraktionsaufwand wirkt niedrig;
- die Quelle erscheint technisch sauber;
- das Team möchte zunächst beweisen, dass Daten bewegt werden können.

Keines dieser Signale zeigt, dass die Quelle ein vertrauenswürdiges Business Outcome erzeugt. Ein technisch erfolgreicher Raw Load kann weiterhin scheitern, wenn Entity Authority ungelöst, Fact Grain unbekannt, Zugriff nicht freigegeben, History unvollständig oder kein Consumer vorhanden ist.

### Readiness und Priorität sind unterschiedliche Dimensionen

Eine Quelle kann hohe Priorität besitzen, aber nicht bereit sein. Eine andere kann leicht extrahierbar sein, aber wenig Decision Value liefern. Werden beide Dimensionen in einen Score vermischt, bleibt die richtige Maßnahme unsichtbar:

- **Hoher Value, hohe Readiness:** Start Now.
- **Hoher Value, niedrige Readiness:** Ownership, Access, Keys oder Quality Evidence vorbereiten.
- **Niedrigerer Value, hohe Readiness:** nur bei bewusstem wiederverwendbarem Learning nutzen.
- **Niedrigerer Value, niedrige Readiness:** Defer.

## Decision

Nutze eine Decision-First-Reihenfolge:

```text
Benannte Entscheidung
→ Candidate Sources
→ Authority- und Grain-Check
→ Readiness- und Risk-Check
→ Kleinster vollständiger Slice
→ Trusted Outcome
```

### 1. Outcome vor der Quelle definieren

Benenne Nutzer, Entscheidung, Handlung, Kennzahl, Population und Zeithorizont. „CRM onboarden“ ist kein Outcome. „Sales Leadership entscheidet jeden Morgen auf Opportunity- und Owner-Grain, wo in der offenen Pipeline eingegriffen wird“ ist eines.

Die Formulierung benennt Consumer und veränderte Handlung. Gleichzeitig definiert sie die Acceptance Criteria des Vertical Slice.

### 2. Decision Value bewerten

Bewerte jeden Kandidaten nach:

- benanntem Nutzer und Handlung;
- messbarem Business Impact;
- Urgency oder Control Need;
- Wiederverwendung über mehrere Produkte;
- Executive-, Operational- oder Regulatory-Criticality;
- Möglichkeit, das Ergebnis gegen einen bestehenden Prozess abzustimmen.

Hohe Sichtbarkeit ohne definierte Handlung ist kein hoher Value.

### 3. Source Readiness separat bewerten

![Source Readiness und Decision Value bewerten](images/playbooks/which-source-to-load-first-img2-de.png)

Bewerte unabhängig:

- Autorität ist verstanden;
- Grain, Keys und Relationships sind bekannt;
- Data Owner und Steward sind verfügbar;
- Access, PII und Permitted Use sind freigegeben;
- Quality ist messbar;
- History-, Deletion- und Correction-Verhalten ist verstanden;
- Extraction- und Support-Pfad sind tragfähig;
- Abhängigkeiten sind sichtbar und besitzen Owner.

Eine Quelle mit niedriger Readiness wird nicht dauerhaft abgelehnt. Sie gelangt mit benannten Prerequisites in ein Prepare-Portfolio.

### 4. Kleinsten vollständigen Vertical Slice auswählen

![Den kleinsten vollständigen Vertical Slice auswählen](images/playbooks/which-source-to-load-first-img3-de.png)

Die erste Quelle ist mit Raw Ingestion nicht abgeschlossen. Der Slice verbindet:

```text
Source Scope
→ Controlled Ingestion
→ Conformed Business Grain
→ Data Product
→ Semantic Model
→ Benannte Entscheidung oder Kontrolle
```

Jede Stufe besitzt eine Acceptance Question:

- **Source:** Welche Objekte und Felder sind freigegeben?
- **Ingestion:** Wie werden Changes, Deletions und Failures behandelt?
- **Conform:** Was ist eine Zeile und welche Autorität gilt?
- **Data Product:** Welcher Quality Contract wird erzwungen?
- **Semantic Model:** Welche Definitionen und Filter werden wiederverwendet?
- **Consumer:** Welche Handlung wird besser, schneller oder sicherer?

Das Anti-Pattern lautet `Source → Raw Tables → „Done“`. Der erste Slice ist erst erfolgreich, wenn das governte Ergebnis genutzt und reconciled wird.

### 5. Wiederverwendbares Learning maximieren

Die erste Quelle sollte mehr als Technologie testen:

- Ownership- und Stewardship-Entscheidungen;
- Source Contract und Change Management;
- PII Classification und Access Approval;
- Quality Thresholds und Incident Ownership;
- Lineage und Evidence Capture;
- Semantic Reuse und Consumer Adoption;
- Cost Attribution und Operational Support.

Wähle Komplexität bewusst. Eine triviale Quelle beweist wenig; eine übergroße Quelle verhindert den Abschluss des Learnings.

### 6. Start-, Prepare- und Defer-Entscheidungen dokumentieren

Jeder Kandidat erhält einen Status:

- **Start:** ausreichend Value und Readiness für einen vollständigen Slice.
- **Prepare:** hochwertige Quelle mit expliziten Prerequisites und Ownern.
- **Opportunistic:** bereite Quelle nur für ein begrenztes Learning-Ziel.
- **Defer:** zu geringer Value oder zu geringe Readiness im aktuellen Horizont.

Kein Kandidat bleibt in einem unerklärten Connector Backlog.

## Checklist

### Decision Value

- [ ] Ein benannter Nutzer und eine Handlung existieren.
- [ ] Erwarteter Business- oder Control-Value ist messbar.
- [ ] Urgency und Zeithorizont sind explizit.
- [ ] Wiederverwendung über Produkte ist verstanden.
- [ ] Reconciliation mit einem bestehenden Prozess ist möglich.

### Source Readiness

- [ ] Autorität, Grain, Keys und Relationships sind verstanden.
- [ ] Business Owner, Steward und Technical Owner sind benannt.
- [ ] Access, PII und Permitted Use sind freigegeben.
- [ ] Quality, History, Deletion und Correction sind testbar.
- [ ] Extraction- und Support-Abhängigkeiten besitzen Owner.

### Vertical Slice

- [ ] Source- und Field-Boundary ist explizit.
- [ ] Ingestion behandelt Failures und Deletions.
- [ ] Conformed Grain und Quality Contract existieren.
- [ ] Eine wiederverwendbare Semantic Definition wird geliefert.
- [ ] Ein benannter Consumer nutzt und reconciled das Ergebnis.

### Portfolio Governance

- [ ] Jeder Kandidat ist Start, Prepare, Opportunistic oder Defer.
- [ ] Prerequisites und Review Trigger sind dokumentiert.
- [ ] Die gewählte Quelle besitzt Success- und Exit-Criteria.
- [ ] Learning-Ziele umfassen Operating Model und Technologie.
- [ ] Scope Expansion benötigt Freigabe.

## Artifact

Erstelle ein Candidate Decision Portfolio mit einer Karte oder Zeile je Quelle. Den freigegebenen Ladeumfang dokumentierst du als `source-scope.csv` / `source-scope.md` im [Source Scope Builder](/tools/source-scope-builder).

![Die First-Source-Entscheidung dokumentieren](images/playbooks/which-source-to-load-first-img4-de.png)

| Feld | Zweck |
|---|---|
| Source System | Geprüfter Quellenkandidat |
| Starting Decision und Consumer | Outcome des ersten Slice |
| Expected Value | Business-, Operational- oder Control-Value |
| Authoritative Contribution | Entity, Attribut oder Event der Quelle |
| Target Grain | Business-Bedeutung einer Fact-Zeile |
| Owner und Steward | Accountable Decision Roles |
| Access und PII Readiness | Approval- und Permitted-Use-Status |
| Quality und Reconciliation Readiness | Test- und Acceptance-Evidence |
| Extraction Dependency | Connector-, API-, Contract- und Support-Abhängigkeit |
| Estimated Scope und Complexity | Begrenzter Delivery-Umfang |
| Reusable Learning | Operating-Model- und Architecture-Learning |
| Decision | Start, Prepare, Opportunistic oder Defer |
| Rationale und Prerequisites | Evidenz und offene Arbeit |
| Review Trigger | Bedingung für Portfolio-Neubewertung |

Das Portfolio erzeugt eine gewählte erste Quelle, die Vertical-Slice-Boundary, benannte Owner, offene Prerequisites, eine Deferred-Source-Queue und messbare Success Criteria.

## Tools

Nutze den [Source Scope Builder](/tools/source-scope-builder), um Objekte, Felder, Relationships und Risiken ernsthafter Kandidaten zu definieren. Nutze den [Metadata Export Generator](/tools/meta-export-generator), um die gewählte Entscheidung in wiederverwendbare Contract Metadata und Implementation Handoff zu überführen.

## Resources

- [Welche Salesforce-Tabellen für Analytics laden](/stories/salesforce-tables-for-analytics) — konkretes supplier-spezifisches Source-Scope-Muster.
- [SaaS-Exporte: Tabellen, die man nicht laden sollte](/stories/saas-exports-tables-to-skip) — generische Skip- und Separate-Product-Regeln.
- Bestehendes Report-, KPI- und Control-Inventar.
- Source Contracts, Access Policies und Data-Classification-Regeln.
- Data-Product-Backlog, Consumer Map und Incident History.

## Playbooks

Wende [Vor dem Bau der ersten Tabelle](/playbooks/before-building-the-first-table) an. Verwende Business Question, Grain, Ownership, Acceptance Criteria und Vertical-Slice-Boundary wieder, statt sie beim Source Onboarding neu zu erfinden.

## Next step

Gib die First-Source-Portfolio-Entscheidung vor Implementierungsbeginn frei. Baue anschließend nur den gewählten vollständigen Slice. Part 4 setzt mit HubSpot fort und zeigt, wie CRM Objects, Properties und Associations in einen governten Source Scope überführt werden.
