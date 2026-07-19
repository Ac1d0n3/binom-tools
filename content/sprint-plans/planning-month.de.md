---
type: sprint-plan
title: Monatsplanung (4 Wochen)
slug: planning-month
description: Schlanker Monatsplan: Wochenziele, Owner und saubere Übergabe in den nächsten Monat.
duration: 4
unit: week
category: Planning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Planning
  - Monat
  - Lightweight
---

Vier Wochen, um einen fokussierten Monat ohne schwere Zeremonie zu planen und zu liefern.

```sprint
id: week-01
number: 1
title: Prioritäten & Kapazität
goal: Monatsziele und Kapazität festlegen.

stories:
  - slug: eight-pillars
    required: false

tasks:
  - id: w1-plan-week
    label: Wochenziele planen
    assigneeType: person
    assigneeId: null
    helpText: |
      Festhalten, was diesen Monat geliefert werden muss und was auf der Warteliste bleibt.
      Erfolgskriterien schriftlich vereinbaren.
    linkedStories: eight-pillars
    helpLinks:
      - label: 8 Säulen
        href: /playbooks/eight-pillars
  - id: w1-standup-board
    label: Board und Owner aktualisieren
    assigneeType: person
    assigneeId: null
    helpText: |
      Ein Board als Wahrheit. Owner, Termine und Status müssen sichtbar sein.
      Achtung: Geister-Tasks ohne Owner.

deliverables:
  - id: w1-week-outcome
    label: Monatsprioritäten dokumentiert
    helpText: |
      Kurze datierte Notiz mit Top-Zielen und Kapazitätsannahmen.

notes: true
```

```sprint
id: week-02
number: 2
title: Umsetzungswoche
goal: Höchstpriorisierte Arbeitspakete liefern.

tasks:
  - id: w2-plan-week
    label: Ziele in Wochenaufgaben zerlegen
    assigneeType: person
    assigneeId: null
    helpText: |
      Owner und Done-Kriterien je Paket. Scope früh kürzen, wenn Kapazität eng ist.
  - id: w2-standup-board
    label: Board und Owner aktualisieren
    assigneeType: person
    assigneeId: null
    helpText: |
      Board nach Standup oder Midweek-Check aktuell halten.

deliverables:
  - id: w2-week-outcome
    label: Wochenplan mit Ownern
    helpText: |
      Board-Snapshot oder kurze Liste mit Ownern.

notes: true
```

```sprint
id: week-03
number: 3
title: Risiken & Abhängigkeiten
goal: Entblocken und Mitte des Monats nachplanen.

tasks:
  - id: w3-plan-week
    label: Blocker und Abhängigkeiten listen
    assigneeType: person
    assigneeId: null
    helpText: |
      Scope kürzen vs. verschieben entscheiden. Eskalation nur mit klarer Bitte.
  - id: w3-standup-board
    label: Board und Owner aktualisieren
    assigneeType: person
    assigneeId: null
    helpText: |
      Umplanungen am selben Tag am Board spiegeln.

deliverables:
  - id: w3-week-outcome
    label: Risiko- und Abhängigkeitsliste
    helpText: |
      Benannte Risiken, Owner und Entscheidungen.

notes: true
```

```sprint
id: week-04
number: 4
title: Abschluss & Übergabe
goal: Monat abschließen und nächsten Monat vorbereiten.

tasks:
  - id: w4-plan-week
    label: Ergebnisse demos und Notizen sichern
    assigneeType: person
    assigneeId: null
    helpText: |
      Was geliefert wurde, was gerutscht ist und warum.
  - id: w4-standup-board
    label: Backlog für nächsten Monat skizzieren
    assigneeType: person
    assigneeId: null
    helpText: |
      Überträge und neue Prioritäten für den Folgemonat.

deliverables:
  - id: w4-week-outcome
    label: Monatsabschluss + Next-Backlog
    helpText: |
      One-Pager: Outcomes, Learnings, Next-Backlog.

notes: true
```
