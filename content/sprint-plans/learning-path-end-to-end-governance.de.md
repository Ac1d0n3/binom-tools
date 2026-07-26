---
type: sprint-plan
title: Lernpfad — End-to-End Governance (3 Wochen)
slug: learning-path-end-to-end-governance
description: Den End-to-End-Governance-Lernpfad als kurzen Plan umsetzen — Kettenbild, Meta/Runtime, PII entlang Lineage.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 6
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Learning
  - Governance
  - Metadata
  - dbt
---

Spiegelt den Learning Path „End-to-End Governance“. Eine Steuerungskette von Source bis Consumer.

```sprint
id: week-01
number: 1
title: Zielbild der Kette
goal: Verstehen, wie Governance von Source bis Consumer steuert.

stories:
  - slug: end-to-end-governance-architecture
    required: true

tasks:
  - id: e2e-series
    label: End-to-End-Governance-Serie lesen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Kapitel wählen, die zu den eigenen Stack-Schichten passen.
    helpLinks:
      - label: Serie — End-to-End Data Governance
        href: /playbooks/series/end-to-end-data-governance
        description: Durchgehende E2E-Governance-Serie.
  - id: e2e-architecture
    label: E2E-Architecture-Story lesen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: end-to-end-governance-architecture
        required: true
    helpText: |
      Aktuelle Control Points und fehlende Links skizzieren.

deliverables:
  - id: e2e-map
    label: Control-Chain-Map
    plannedMinutes: 60
    helpText: |
      Fertig, wenn Source → Transform → Serve Control Points benannt sind.

notes: true
```

```sprint
id: week-02
number: 2
title: Metadata steuert Runtime
goal: dbt meta und Automation in die Betriebsdisziplin überführen.

tasks:
  - id: e2e-meta
    label: dbt-meta-Governance prüfen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: metadata-driven-governance-with-dbt-meta
        required: true
    helpText: |
      Entscheiden, welche Policies aus Metadata gesteuert werden sollen — nicht aus Tickets.
  - id: e2e-raw
    label: Raw-Generation-Automation prüfen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: automatic-raw-generation-using-dbt-macros
        required: false
    helpText: |
      Einen Automation-Kandidaten wählen, der manuellen Drift reduziert.
    helpLinks:
      - label: Pfad — Metadata Operating Model
        href: /learning-paths/metadata-operating-model
        description: Operating Model für Catalog und Lineage.

deliverables:
  - id: e2e-runtime-note
    label: Runtime-Steering-Notiz
    plannedMinutes: 60
    helpText: |
      Fertig, wenn ein meta-gesteuertes Control und Owner vereinbart sind.

notes: true
```

```sprint
id: week-03
number: 3
title: PII entlang der Pipeline
goal: Klassifikation und Masking entlang der Lineage verfolgen, dann übergeben.

tasks:
  - id: e2e-pii
    label: PII-Metadata-Propagation prüfen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: propagating-pii-metadata-across-data-warehouses
        required: false
    helpText: |
      Ein sensibles Feld von Source bis Consumer nachverfolgen.
  - id: e2e-handoff
    label: An PII-Pfad und Hub übergeben
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Mit PII-Readiness weiter, wenn Klassifikations-Gaps bleiben.
    helpLinks:
      - label: Pfad — PII in 5 Schritten
        href: /learning-paths/pii-in-five-steps
        description: Kürzester belastbarer Privacy-Einstieg.
      - label: Governance Hub
        href: /governance
        description: Betriebsentscheidung festhalten.

deliverables:
  - id: e2e-handoff-checklist
    label: E2E-Übergabe-Checkliste
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Control-Map, Runtime-Notiz und nächster Pfad verlinkt sind.

notes: true
```
