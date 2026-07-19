---
type: sprint-plan
title: Data & Reporting – Erstes Quartal
slug: data-reporting-first-quarter
description: Die Daten- und Reporting-Landschaft verstehen und die erste nachhaltige Verbesserung umsetzen.
duration: 13
unit: week
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Data Platform
  - Reporting
  - Governance
---

Dreizehn Wochen, um Reporting und Datenplattform zu verstehen, Risiken zu klären und einen ersten Piloten umzusetzen.

```sprint
id: week-01
number: 1
title: Orientierung und Mandat
goal: Auftrag, Erwartungen und relevante Stakeholder verstehen.

tasks:
  - id: align-management-expectations
    label: Erwartungen mit der Führung abstimmen
    assigneeType: person
    assigneeId: null
  - id: identify-stakeholders
    label: Relevante Stakeholder identifizieren
    assigneeType: team
    assigneeId: null

deliverables:
  - id: stakeholder-list
    label: Stakeholder-Liste erstellt
  - id: initial-mandate
    label: Initialen Auftrag dokumentiert

fields:
  - id: management-expectations
    label: Erwartungen der Führung
    type: textarea
    placeholder: Ziele, Erwartungen und Erfolgskriterien
  - id: primary-owner
    label: Hauptverantwortlicher
    type: text
    placeholder: Name oder Rolle

notes: true
```

```sprint
id: week-02
number: 2
title: Reporting-Landschaft
goal: Bestehende Reports, Nutzer und kritische Lücken erfassen.

tasks:
  - id: inventory-reports
    label: Bestehende Reports inventarisieren
    assigneeType: person
    assigneeId: null
  - id: map-report-consumers
    label: Report-Nutzer und Nutzungshäufigkeit kartieren
    assigneeType: team
    assigneeId: null

deliverables:
  - id: report-inventory
    label: Report-Inventar dokumentiert
  - id: gap-list
    label: Erste Lückenliste erstellt

fields:
  - id: critical-reports
    label: Kritische Reports
    type: textarea
    placeholder: Reports mit hohem Business-Impact

notes: true
```

```sprint
id: week-03
number: 3
title: Quellsysteme
goal: Relevante Quellsysteme, Owner und Schnittstellen verstehen.

tasks:
  - id: list-source-systems
    label: Quellsysteme und Owner erfassen
    assigneeType: person
    assigneeId: null
  - id: document-interfaces
    label: Schnittstellen und Extraktionswege dokumentieren
    assigneeType: team
    assigneeId: null

deliverables:
  - id: source-system-map
    label: Quellsystem-Karte erstellt
  - id: owner-matrix
    label: Owner-Matrix erstellt

fields:
  - id: system-risks
    label: Systemrisiken
    type: textarea
    placeholder: Bekannte Risiken oder Engpässe

notes: true
```

```sprint
id: week-04
number: 4
title: Datenentstehung
goal: Wie operative Daten entstehen und welche Regeln gelten.

tasks:
  - id: trace-data-creation
    label: Datenentstehung in Kernprozessen nachvollziehen
    assigneeType: person
    assigneeId: null
  - id: capture-business-rules
    label: Geschäftliche Regeln und Ausnahmen dokumentieren
    assigneeType: team
    assigneeId: null

deliverables:
  - id: creation-notes
    label: Notizen zur Datenentstehung
  - id: rule-summary
    label: Zusammenfassung der Geschäftsregeln

fields:
  - id: key-processes
    label: Schlüsselprozesse
    type: textarea
    placeholder: Prozesse mit größtem Einfluss auf Reporting

notes: true
```

```sprint
id: week-05
number: 5
title: End-to-End-Lineage
goal: Den Weg von der Quelle bis zum Report nachvollziehbar machen.

tasks:
  - id: map-lineage-paths
    label: Zentrale Lineage-Pfade skizzieren
    assigneeType: person
    assigneeId: null
  - id: identify-lineage-gaps
    label: Lineage-Lücken und Blindspots markieren
    assigneeType: team
    assigneeId: null

deliverables:
  - id: lineage-sketch
    label: Lineage-Skizze erstellt
  - id: lineage-gap-log
    label: Lineage-Lückenprotokoll

fields:
  - id: priority-flows
    label: Prioritäre Datenflüsse
    type: textarea
    placeholder: Flüsse für den ersten Piloten

notes: true
```

```sprint
id: week-06
number: 6
title: KPI-Inventar
goal: Wichtige KPIs, Definitionen und Ownership klären.

tasks:
  - id: collect-kpis
    label: KPIs aus Reports und Stakeholder-Interviews sammeln
    assigneeType: person
    assigneeId: null
  - id: normalize-definitions
    label: Definitionen und Berechnungsregeln abstimmen
    assigneeType: team
    assigneeId: null

deliverables:
  - id: kpi-inventory
    label: KPI-Inventar erstellt
  - id: definition-backlog
    label: Definitions-Backlog priorisiert

fields:
  - id: top-kpis
    label: Top-KPIs
    type: textarea
    placeholder: Die wichtigsten Kennzahlen für das Quartal

notes: true
```

```sprint
id: week-07
number: 7
title: Datenqualität und Risiken
goal: Qualitätsprobleme und Risiken priorisieren.

tasks:
  - id: assess-dq-issues
    label: Bekannte DQ-Probleme bewerten
    assigneeType: person
    assigneeId: null
  - id: rate-risks
    label: Business- und Compliance-Risiken bewerten
    assigneeType: team
    assigneeId: null

deliverables:
  - id: dq-risk-register
    label: DQ- und Risikoregister
  - id: hotspot-list
    label: Hotspot-Liste priorisiert

fields:
  - id: top-risks
    label: Top-Risiken
    type: textarea
    placeholder: Risiken mit höchster Priorität

notes: true
```

```sprint
id: week-08
number: 8
title: Architekturdiagnose
goal: Ist-Architektur bewerten und Engpässe benennen.

tasks:
  - id: review-architecture
    label: Ist-Architektur und Tools reviewen
    assigneeType: person
    assigneeId: null
  - id: document-bottlenecks
    label: Engpässe und technische Schulden dokumentieren
    assigneeType: team
    assigneeId: null

deliverables:
  - id: architecture-notes
    label: Architekturdiagnose-Notizen
  - id: bottleneck-list
    label: Engpassliste

fields:
  - id: architecture-findings
    label: Architektur-Findings
    type: textarea
    placeholder: Zentrale Erkenntnisse

notes: true
```

```sprint
id: week-09
number: 9
title: Priorisierung
goal: Maßnahmen nach Impact und Machbarkeit priorisieren.

tasks:
  - id: score-initiatives
    label: Initiativen nach Impact und Aufwand bewerten
    assigneeType: person
    assigneeId: null
  - id: agree-priorities
    label: Prioritäten mit Stakeholdern abstimmen
    assigneeType: team
    assigneeId: null

deliverables:
  - id: priority-matrix
    label: Priorisierungsmatrix
  - id: quarter-backlog
    label: Quartals-Backlog abgestimmt

fields:
  - id: pilot-candidate
    label: Pilot-Kandidat
    type: text
    placeholder: Ausgewählte Initiative für den Piloten

notes: true
```

```sprint
id: week-10
number: 10
title: Zielbild
goal: Ein klares Zielbild für Reporting und Datenplattform skizzieren.

tasks:
  - id: draft-target-picture
    label: Zielbild und Prinzipien entwerfen
    assigneeType: person
    assigneeId: null
  - id: validate-target-picture
    label: Zielbild mit Stakeholdern validieren
    assigneeType: team
    assigneeId: null

deliverables:
  - id: target-picture
    label: Zielbild dokumentiert
  - id: guiding-principles
    label: Leitprinzipien definiert

fields:
  - id: target-summary
    label: Zielbild-Kurzfassung
    type: textarea
    placeholder: Kurzbeschreibung des gewünschten Endzustands

notes: true
```

```sprint
id: week-11
number: 11
title: Pilot umsetzen
goal: Den priorisierten Piloten in einem begrenzten Scope umsetzen.

tasks:
  - id: build-pilot
    label: Pilot umsetzen
    assigneeType: person
    assigneeId: null
  - id: track-pilot-blockers
    label: Blocker und Abhängigkeiten steuern
    assigneeType: team
    assigneeId: null

deliverables:
  - id: pilot-increment
    label: Pilot-Inkrement bereit
  - id: pilot-changelog
    label: Pilot-Änderungsprotokoll

fields:
  - id: pilot-scope
    label: Pilot-Scope
    type: textarea
    placeholder: Was ist im und außerhalb des Scopes

notes: true
```

```sprint
id: week-12
number: 12
title: Pilot validieren
goal: Nutzen, Qualität und Akzeptanz des Piloten prüfen.

tasks:
  - id: run-pilot-review
    label: Pilot mit Nutzern reviewen
    assigneeType: person
    assigneeId: null
  - id: measure-pilot-outcomes
    label: Ergebnis und Qualität messen
    assigneeType: team
    assigneeId: null

deliverables:
  - id: validation-report
    label: Validierungsbericht
  - id: go-nogo-recommendation
    label: Go/No-Go-Empfehlung

fields:
  - id: validation-notes
    label: Validierungsnotizen
    type: textarea
    placeholder: Feedback und Messwerte

notes: true
```

```sprint
id: week-13
number: 13
title: Quartalsabschluss
goal: Ergebnisse sichern, lernen und nächstes Quartal vorbereiten.

tasks:
  - id: summarize-quarter
    label: Quartalsergebnisse zusammenfassen
    assigneeType: person
    assigneeId: null
  - id: plan-next-quarter
    label: Nächstes Quartal grob planen
    assigneeType: team
    assigneeId: null

deliverables:
  - id: quarter-report
    label: Quartalsbericht
  - id: next-quarter-outline
    label: Skizze für das nächste Quartal

fields:
  - id: lessons-learned
    label: Lessons Learned
    type: textarea
    placeholder: Was behalten, was ändern

notes: true
```
