---
type: sprint-plan
title: Lernpfad — DQ mit dbt (3 Wochen)
slug: learning-path-dq-with-dbt
description: Den DQ-mit-dbt-Lernpfad als kurzen Plan umsetzen — Ownership-Sprache, Operations-Serie, Generatoren, Betriebsübergabe.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 4
capacity_hours_per_person_week: 8
category: Learning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Learning
  - Data Quality
  - dbt
---

Spiegelt den Learning Path „DQ mit dbt“. Data Quality als Betriebsdisziplin, nicht als einmaliges Audit.

```sprint
id: week-01
number: 1
title: DQ-Begriff und Ownership
goal: Fitness for purpose, Steward und Contract vor dem ersten Test.

stories:
  - slug: data-quality-governance
    required: true

tasks:
  - id: dq-terms
    label: DQ- und Contract-Begriffe angleichen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Begriff, Arbeitsdefinition, Steward, Offene Frage
    helpText: |
      Klären, was „fit for purpose“ in der Domäne heißt, bevor Macros oder Rules generiert werden.
    helpLinks:
      - label: Glossar — Data Quality
        href: /glossary/data-quality
        description: Gemeinsame DQ-Sprache.
      - label: Glossar — Data Contract
        href: /glossary/data-contract
        description: Contract als Betriebs-Interface.
  - id: dq-story
    label: DQ-Governance-Story lesen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: data-quality-governance
        required: true
    helpText: |
      Ownership- und Gate-Erwartungen für den eigenen Stack festhalten.

deliverables:
  - id: dq-ownership-note
    label: DQ-Ownership-Notiz
    plannedMinutes: 60
    helpText: |
      Fertig, wenn Steward, Contract-Owner und erste Gate-Kriterien benannt sind.

notes: true
```

```sprint
id: week-02
number: 2
title: Operations-Serie und Generatoren
goal: Operational-DQ-Serie durchgehen und erste dbt-Artefakte erzeugen.

tasks:
  - id: dq-series
    label: Operational-DQ-Serie lesen
    plannedMinutes: 210
    assigneeType: person
    assigneeId: null
    helpText: |
      Serie für KPIs, Plattform-Patterns und Remediation nutzen — Kapitel zur eigenen Schicht wählen.
    helpLinks:
      - label: Serie — Operational Data Quality
        href: /playbooks/series/operational-data-quality
        description: Durchgehende Serie für operational DQ.
  - id: dq-generators
    label: Macros, Rules, History-Entwürfe erzeugen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Artefakt, Scope, Owner, Status
    helpText: |
      Generator-Output als Copy-Paste-Startpunkt behandeln, danach mit dem Steward reviewen.
    helpLinks:
      - label: dbt DQ Macros
        href: /tools/dbt-dq-macro-generator
        description: Macro-Startpunkte.
      - label: dbt DQ Rules
        href: /tools/dbt-dq-rules-generator
        description: Rule-Startpunkte.
      - label: dbt DQ History
        href: /tools/dbt-dq-history-generator
        description: History-/Tracking-Startpunkte.

deliverables:
  - id: dq-draft-pack
    label: DQ-Entwurfspaket
    plannedMinutes: 90
    helpText: |
      Fertig, wenn mindestens ein Macro-/Rule-/History-Entwurf verlinkt und einmal reviewed ist.

notes: true
```

```sprint
id: week-03
number: 3
title: In den Betrieb überführen
goal: DQ nicht in der Pipeline stecken lassen — Advisor-Session und Betriebs-Cadence.

tasks:
  - id: dq-ops-handoff
    label: Betriebs-Cadence festlegen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Gate, Owner, Cadence, Eskalation
    helpText: |
      Benennen, wer Failures sieht, wer remediates und wann das Gate einen Release blockiert.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Entscheidungskontext festhalten.
      - label: Roles — Data Steward
        href: /roles/steward
        description: Decision Rights des Stewards.
  - id: dq-plan-followup
    label: Follow-up-Sprint-Arbeit planen
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Restarbeit in Delivery-Sprints (z. B. change-tests) überführen, damit Lernen nicht bei Entwürfen stoppt.
    helpLinks:
      - label: Sprint Planner
        href: /sprint-planner
        description: Weiter mit Delivery-Vorlagen.

deliverables:
  - id: dq-ops-checklist
    label: DQ-Ops-Checkliste
    plannedMinutes: 60
    helpText: |
      Fertig, wenn Gates, Owner und der Link zum nächsten Delivery-Plan stehen.

notes: true
```