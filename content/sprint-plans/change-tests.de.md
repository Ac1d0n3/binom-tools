---
type: sprint-plan
title: DB-/Report-Änderung mit Tests (3 Wochen)
slug: change-tests
description: DB- oder Report-Änderung sicher liefern: Impact, Tests, Verify, Release.
duration: 3
unit: week
category: Quality
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Quality
  - Tests
  - Change
---

Drei Wochen, um etwas Wichtiges zu ändern — ohne Blindflug.

```sprint
id: week-01
number: 1
title: Impact & Scope
goal: Änderung und Wirkungsradius verstehen.

stories:
  - slug: data-quality-governance
    required: true
  - slug: dq-test-kpis
    required: false

tasks:
  - id: w1-impact
    label: Änderung und Impact dokumentieren
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Betroffene Tabellen/Reports, Konsumenten, Rollback.
      Änderungsbeschreibung testbar halten.
    linkedStories: data-quality-governance, missing-pieces-data-quality
    helpLinks:
      - label: Data-Quality-Governance
        href: /playbooks/data-quality-governance
  - id: w1-scope
    label: In/Out-of-Scope vereinbaren
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Was in dieser Runde explizit nicht geändert wird.

deliverables:
  - id: w1-impact-note
    label: Impact-Notiz
    plannedMinutes: 60
    helpText: |
      Änderungszusammenfassung, Konsumenten, Rollback.

notes: true
```

```sprint
id: week-02
number: 2
title: Tests designen
goal: Tests entwerfen und anlegen.

tasks:
  - id: w2-tests
    label: Automatisierte Tests anlegen/erweitern
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Frische, Uniqueness, Referenzen, KPI-Sanity — passend wählen.
      Bestehende DQ-Muster bevorzugen.
    linkedStories: dq-test-kpis, dq-test2
    helpLinks:
      - label: DQ-Test-KPIs
        href: /playbooks/dq-test-kpis
      - label: DQ-Tests
        href: /playbooks/dq-test2
  - id: w2-fixtures
    label: Fixtures / erwartete Ergebnisse vorbereiten
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Bekannte gute Rows oder KPI-Snapshots.

deliverables:
  - id: w2-test-pack
    label: Test-Pack
    plannedMinutes: 60
    helpText: |
      Liste neuer/aktualisierter Tests mit Ownern.

notes: true
```

```sprint
id: week-03
number: 3
title: Verifizieren & Release
goal: Änderung verifizieren und releasen.

tasks:
  - id: w3-verify
    label: Tests in Zielumgebung ausführen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Ergebnisse festhalten; bei kritischen Checks fail-closed.
  - id: w3-release
    label: Release Notes und Monitoring
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Was geändert wurde, worauf achten, wen anrufen.

deliverables:
  - id: w3-release
    label: Release-Nachweis
    plannedMinutes: 60
    helpText: |
      Testergebnisse + Release Notes.

notes: true
```
