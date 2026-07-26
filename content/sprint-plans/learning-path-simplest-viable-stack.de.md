---
type: sprint-plan
title: Lernpfad — Simplest Viable Stack (3 Wochen)
slug: learning-path-simplest-viable-stack
description: Den Simplest-Viable-Stack-Lernpfad als kurzen Plan umsetzen — Designziel, Fit-Checks, Modernisierungs-Übergabe.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 5
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Learning
  - Architecture
  - Stack
---

Spiegelt den Learning Path „Simplest Viable Stack“. Die einfachste belastbare Architektur wählen, bevor Schicht-Slogans übernehmen.

```sprint
id: week-01
number: 1
title: Einfachheit als Designziel
goal: Klären, was „viable“ heißt, bevor Vendor- und Schicht-Debatten starten.

stories:
  - slug: choosing-the-simplest-viable-architecture
    required: true

tasks:
  - id: stack-simple
    label: Simplest Viable Architecture lesen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: choosing-the-simplest-viable-architecture
        required: true
    helpText: |
      Constraints, Non-Goals und den kleinsten Stack festhalten, der noch trägt.
  - id: stack-layers
    label: Bronze/Silver/Gold-Defaults hinterfragen
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    stories:
      - slug: beyond-bronze-silver-gold
        required: false
    helpText: |
      Nur Schichten behalten, die für das aktuelle Problem ihren Preis wert sind.

deliverables:
  - id: stack-constraints
    label: Stack-Constraints-Notiz
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Constraints, Non-Goals und Kandidaten-Stack notiert sind.

notes: true
```

```sprint
id: week-02
number: 2
title: Fit prüfen
goal: Kandidaten-Stack mit Architecture Fit und Advisors absichern.

tasks:
  - id: stack-fit
    label: Architecture Fit durchspielen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Kandidaten gegen reale Constraints stresstesten.
    helpLinks:
      - label: Architecture Fit
        href: /tools/architecture-fit
        description: Fit-Check für Architekturentscheidungen.
  - id: stack-advisor
    label: Stack Advisor / Decision Brief festhalten
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Entscheidung mit genug Kontext für Sponsoren dokumentieren.
    helpLinks:
      - label: Stack Advisor
        href: /tools/governance-stack-advisor
        description: Geführte Stack-Entscheidungshilfe.
      - label: Decision Brief
        href: /tools/decision-brief-generator
        description: Kurzer Entscheidungsnachweis.

deliverables:
  - id: stack-decision
    label: Stack-Entscheidungsentwurf
    plannedMinutes: 60
    helpText: |
      Fertig, wenn empfohlener Stack, Risiken und offene Fragen gelistet sind.

notes: true
```

```sprint
id: week-03
number: 3
title: In Modernisierung überführen
goal: An den Warehouse-Modernisierungs-Pfad übergeben.

tasks:
  - id: stack-handoff
    label: Warehouse-Modernisierungs-Pfad starten
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Die Entscheidung als Startbrief für Grain und Products nutzen.
    helpLinks:
      - label: Pfad — Warehouse modernisieren
        href: /learning-paths/modernize-warehouse
        description: Grain, Products und Warehouse-Serie.
      - label: Governance Hub
        href: /governance
        description: Entscheidung sichtbar halten.

deliverables:
  - id: stack-handoff-checklist
    label: Stack-Übergabe-Checkliste
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Decision Brief und nächster Pfad verlinkt sind.

notes: true
```
