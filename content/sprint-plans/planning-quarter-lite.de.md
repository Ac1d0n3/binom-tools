---
type: sprint-plan
title: Quartalsplanung (lite, 13 Wochen)
slug: planning-quarter-lite
description: Leichtes Quartal: wöchentliche Planung und Lieferrhythmus ohne volle Platform-Journey.
duration: 13
unit: week
category: Planning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Planning
  - Quartal
  - Lightweight
---

Dreizehn Wochen mit leichter Zeremonie: planen, liefern, nachplanen — bereit für die nächsten Sprints.

```sprint
id: week-01
number: 1
title: Kickoff & Outcomes
goal: Quartals-Outcomes und Non-Goals vereinbaren.

stories:
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: w1-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
    linkedStories: data-ownership-stewardship
    helpLinks:
      - label: Impact–Effort Prioritizer
        href: /tools/impact-effort
      - label: Ownership & Stewardship
        href: /playbooks/data-ownership-stewardship
  - id: w1-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w1-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-02
number: 2
title: Backlog schärfen
goal: Dünnen Backlog für die nächsten 2–3 Wochen schärfen.

tasks:
  - id: w2-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w2-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w2-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-03
number: 3
title: Lieferrhythmus
goal: Wöchentlichen Lieferrhythmus etablieren.

tasks:
  - id: w3-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w3-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w3-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-04
number: 4
title: Abhängigkeitskarte
goal: Kritische Abhängigkeiten und Owner kartieren.

tasks:
  - id: w4-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
  - id: w4-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w4-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-05
number: 5
title: Mid-Quarter Check
goal: Fortschritt vs. Outcomes prüfen; nachplanen.

stories:
  - slug: eight-pillars
    required: false

tasks:
  - id: w5-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
    helpLinks:
      - label: Impact–Effort Prioritizer
        href: /tools/impact-effort
  - id: w5-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w5-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-06
number: 6
title: Fokuslieferung
goal: Fokuszeit für Top-Outcomes schützen.

tasks:
  - id: w6-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w6-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w6-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-07
number: 7
title: Quality Gate
goal: Quality Gates für laufende Arbeit definieren.

tasks:
  - id: w7-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w7-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w7-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-08
number: 8
title: Stakeholder-Sync
goal: Entscheidungen mit Stakeholdern synchronisieren.

tasks:
  - id: w8-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w8-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w8-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-09
number: 9
title: Risiko-Burn-down
goal: Top-Risiken mit klaren Ownern abbauen.

tasks:
  - id: w9-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w9-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w9-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-10
number: 10
title: Integrationswoche
goal: End-to-end-Pfade integrieren und validieren.

tasks:
  - id: w10-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w10-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w10-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-11
number: 11
title: Härtung
goal: Härten, dokumentieren und Rest-Scope schneiden.

tasks:
  - id: w11-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w11-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w11-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-12
number: 12
title: Demo-Vorbereitung
goal: Demos und Abnahme-Nachweise vorbereiten.

tasks:
  - id: w12-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w12-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w12-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```

```sprint
id: week-13
number: 13
title: Abschluss & nächstes Quartal
goal: Quartal abschließen und nächstes vorbereiten.

tasks:
  - id: w13-plan-week
    label: Wochenziele planen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      1–3 Outcomes für die Woche an Quartalszielen ausrichten.
      Liste kurz genug halten, um fertig zu werden.
  - id: w13-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Standup-Updates. Blockierte Arbeit sichtbar halten.
      Woche mit kurzem schriftlichem Status beenden.

deliverables:
  - id: w13-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Datiert: geplant vs. erledigt, Blocker, nächster Fokus.

notes: true
```
