---
type: sprint-plan
title: Lernpfad — Trusted Metrics (3 Wochen)
slug: learning-path-trusted-metrics
description: Den Trusted-Metrics-Lernpfad als kurzen Plan umsetzen — KPI-Sprache, Contracts, Missing Piece, Hub-Übergabe.
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
  - KPI
  - Metrics
  - Stewardship
---

Spiegelt den Learning Path „Trusted Metrics“. Definitionen ohne Ownership und Change-Prozess bleiben Folien.

```sprint
id: week-01
number: 1
title: KPI-Sprache
goal: Definition, Owner und Decision Rights für Kennzahlen angleichen.

stories:
  - slug: kpi-metric-governance
    required: true

tasks:
  - id: kpi-terms
    label: KPI-Governance-Begriffe angleichen
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Begriff, Arbeitsdefinition, Owner, Offene Frage
    helpText: |
      Mit KPI-Governance-Vokabular starten, bevor Formeln in Tools landen.
    helpLinks:
      - label: Glossar — KPI Governance
        href: /glossary/kpi-governance
        description: Gemeinsame Metric-Betriebssprache.
  - id: kpi-story
    label: KPI Metric Governance lesen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: kpi-metric-governance
        required: true
    helpText: |
      Festhalten, wer eine Kennzahl ändern darf und wie Konflikte eskaliert werden.

deliverables:
  - id: kpi-rights-note
    label: Metric-Decision-Rights-Notiz
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Accountable Owner und Änderungspfad benannt sind.

notes: true
```

```sprint
id: week-02
number: 2
title: Contract schreiben
goal: Ersten KPI-Contract mit Grain, Formel und Steward erzeugen.

stories:
  - slug: define-kpi
    required: true

tasks:
  - id: kpi-define-story
    label: Define-KPI-Praxis lesen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: define-kpi
        required: true
    helpText: |
      Story als Contract-Checkliste für Steward × Architect nutzen.
  - id: kpi-tools
    label: Mit KPI-Tools entwerfen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    tableColumns: KPI, Grain, Formel, Steward, Status
    helpText: |
      Tool-Output als Vertragsentwurf behandeln, danach mit dem Accountable Owner reviewen.
    helpLinks:
      - label: KPI Definition
        href: /tools/kpi-definition
        description: Strukturierter KPI-Vertragsentwurf.
      - label: KPI Requirements Intake
        href: /tools/kpi-requirements-intake
        description: Intake vor Definitionsarbeit.

deliverables:
  - id: kpi-contract-draft
    label: Erster KPI-Vertragsentwurf
    plannedMinutes: 90
    helpText: |
      Fertig, wenn eine Prioritäts-KPI Grain, Formel, Steward und offene Fragen hat.

notes: true
```

```sprint
id: week-03
number: 3
title: Betreiben und übergeben
goal: Trusted-Metrics Missing Piece abdecken und an Rollen / Delivery anbinden.

tasks:
  - id: kpi-missing
    label: Trusted-Metrics Missing Piece lesen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: missing-pieces-trusted-metrics
        required: true
    helpText: |
      Benennen, was nach Definitionen noch scheitert — Cadence, Evidence, Eskalation.
  - id: kpi-handoff
    label: An Hub und Delivery übergeben
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Decision Rights in Roles verlinken und Follow-up-Delivery planen.
    helpLinks:
      - label: Roles Hub
        href: /roles
        description: Decision Rights für Steward und Owner.
      - label: Sprint Planner
        href: /sprint-planner
        description: Weiter mit Delivery-Vorlagen.
      - label: Governance Hub
        href: /governance
        description: Betriebsentscheidung festhalten.

deliverables:
  - id: kpi-ops-checklist
    label: Trusted-Metrics-Checkliste
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Vertragsentwurf, Owner, Cadence und nächster Sprint-Link stehen.

notes: true
```
