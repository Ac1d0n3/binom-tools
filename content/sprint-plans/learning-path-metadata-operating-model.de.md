---
type: sprint-plan
title: Lernpfad — Metadata Operating Model (3 Wochen)
slug: learning-path-metadata-operating-model
description: Den Metadata-Operating-Model-Lernpfad als kurzen Plan umsetzen — Sprache, Deep-Dive-Serie, Governance-Metadata, Hub-Übergabe.
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
  - Metadata
  - Catalog
  - Lineage
---

Spiegelt den Learning Path „Metadata Operating Model“. Metadata als Steuerungshebel, nicht als Nebenprojekt.

```sprint
id: week-01
number: 1
title: Begriffe und Auftrag
goal: Gemeinsame Sprache für Metadata, Catalog und Lineage — plus klarer Produktauftrag.

stories:
  - slug: what-metadata-actually-is
    required: true

tasks:
  - id: md-terms
    label: Metadata-Begriffe angleichen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Begriff, Arbeitsdefinition, Owner, Offene Frage
    helpText: |
      Klären, was Metadata, Catalog und Lineage bedeuten, bevor Tool-Shortlists starten.
    helpLinks:
      - label: Glossar — Metadata
        href: /glossary/metadata
        description: Gemeinsame Metadata-Sprache.
      - label: Glossar — Data Catalog
        href: /glossary/data-catalog
        description: Catalog als Discovery- und Control-Fläche.
      - label: Glossar — Lineage
        href: /glossary/lineage
        description: Lineage als Impact- und Trust-Pfad.
  - id: md-story
    label: Story „What Metadata Actually Is“ lesen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: what-metadata-actually-is
        required: true
    helpText: |
      Festhalten, welche Metadata-Klassen in den nächsten 90 Tagen zählen.

deliverables:
  - id: md-mandate-note
    label: Metadata-Mandatsnotiz
    plannedMinutes: 60
    helpText: |
      Fertig, wenn Scope, Owner und erstes Erfolgssignal benannt sind.

notes: true
```

```sprint
id: week-02
number: 2
title: Deep Dive und Produktbetrieb
goal: MetaData-Deep-Dive-Serie lesen und entscheiden, wie Metadata als Produkt betrieben wird.

tasks:
  - id: md-series
    label: MetaData-Deep-Dive-Serie lesen
    plannedMinutes: 210
    assigneeType: person
    assigneeId: null
    helpText: |
      Kapitel zu Geburt, Harvest, Modell, Qualität und Automation passend zur Reife wählen.
    helpLinks:
      - label: Serie — MetaData Deep Dive
        href: /playbooks/series/metadata-deep-dive
        description: Durchgehende Serie für Metadata-Praxis.
  - id: md-product
    label: Operate-as-Product-Kapitel lesen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: operate-metadata-as-a-product
        required: true
    helpText: |
      SLOs, Intake und Ownership für das Metadata-Produkt selbst entscheiden.

deliverables:
  - id: md-ops-sketch
    label: Metadata-Ops-Skizze
    plannedMinutes: 90
    helpText: |
      Fertig, wenn Product Owner, Cadence und erster SLO-Entwurf stehen.

notes: true
```

```sprint
id: week-03
number: 3
title: Steuern und übergeben
goal: Governance-steuernde Metadata anbinden und nächsten Lernpfad wählen.

tasks:
  - id: md-steer
    label: Governance-Steering-Patterns prüfen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: governance-metadata-that-controls-data
        required: false
      - slug: metadata-driven-governance-with-dbt-meta
        required: false
    helpText: |
      Patterns bevorzugen, die Policy und dbt meta steuern — nicht nur Assets beschreiben.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Entscheidungskontext festhalten.
  - id: md-next-path
    label: Nächsten Pfad wählen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Gaps schließen oder zu PII weitergehen, sobald der Operating-Model-Entwurf stabil ist.
    helpLinks:
      - label: Pfad — Gaps schließen
        href: /learning-paths/close-the-gaps
        description: Verbleibende Betriebs-Gaps diagnostizieren.
      - label: Pfad — PII in 5 Schritten
        href: /learning-paths/pii-in-five-steps
        description: Kürzester belastbarer Privacy-Einstieg.

deliverables:
  - id: md-handoff
    label: Metadata-Übergabe-Checkliste
    plannedMinutes: 45
    helpText: |
      Fertig, wenn nächster Pfad, Owner und Hub-Entscheidungslink dokumentiert sind.

notes: true
```
