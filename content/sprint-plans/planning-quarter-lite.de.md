---
type: sprint-plan
title: Quartalsplanung (lite, 13 Wochen)
slug: planning-quarter-lite
description: Leichtes Quartal: wöchentliche Planung und Lieferrhythmus ohne volle Platform-Journey.
duration: 13
unit: week
recommended_people_min: 1
recommended_people_max: 1
capacity_hours_per_person_week: 40
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
    tableColumns: Outcome, Owner, Success signal, Non-goal, Risk
    helpText: |
      Leite aus den Quartalszielen 1-3 Wochen-Outcomes ab, die sichtbar geliefert oder entschieden werden koennen.
      Schreibe pro Outcome Owner, Erfolgssignal und Nicht-Ziel auf. So bleibt die Liste klein genug, um wirklich fertig zu werden.
      Klaere direkt, wer fachlich entscheidet und wer nur informiert werden muss.
      Nutze den Impact-Effort Prioritizer, wenn mehrere Outcomes konkurrieren, aber starte nur Arbeit mit realistischem Done-Kriterium.
    stories:
      - slug: data-ownership-stewardship
        required: false
    helpLinks:
      - label: Impact-Effort Prioritizer
        href: /tools/impact-effort
        description: Nutze das Tool, um Initiativen nach Wirkung, Aufwand, Risiko und Abhängigkeiten zu priorisieren.
      - label: Scrum Guide - Sprint Planning
        href: https://scrumguides.org/scrum-guide.html#sprint-planning
        description: Nutze den offiziellen Scrum Guide als kurze Referenz für Sprint-Ziel, Umfang und Planungslogik.
      - label: Atlassian - Sprint planning
        href: https://www.atlassian.com/agile/scrum/sprint-planning
        description: Nutze den Leitfaden als Referenz für Ziel, Umfang und Commitment einer Sprint-Planung.
  - id: w1-run-week
    label: Woche fahren und Board aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Halte das Board aktuell und fuehre blockierte Arbeit sichtbar, nicht nur mental.
      Status soll jeden Tag die naechste Entscheidung erleichtern: weiterarbeiten, entblocken, schneiden oder verschieben.
      Beende die Woche mit einer kurzen schriftlichen Notiz, damit der Quartalsverlauf spaeter nachvollziehbar bleibt.
    helpLinks:
      - label: Atlassian - Product backlog
        href: https://www.atlassian.com/en/agile/scrum/backlogs
        description: Nutze die Erklärung, um Backlog-Einträge sauber zu schneiden und zu priorisieren.

deliverables:
  - id: w1-week-outcome
    label: Wochen-Outcome-Notiz
    plannedMinutes: 60
    helpText: |
      Erstelle eine datierte Notiz mit geplant vs. erledigt, Blockern, Entscheidungen und naechstem Fokus.
      Das Deliverable ist fertig, wenn jemand den Wochenstand ohne zusaetzliches Meeting nachvollziehen kann.
      Markiere bewusst, was nicht weiterverfolgt wird.

fields:
  - id: quarter-outcomes
    label: Quartals-Outcomes
    type: textarea
    placeholder: 3-5 Outcomes, Erfolgssignale, Non-Goals
  - id: kickoff-decisions
    label: Kickoff-Entscheidungen
    type: textarea
    placeholder: Owner, Scope-Grenzen, offene Entscheidungen

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
    tableColumns: Dependency, Owner, Needed by, Risk, Ask
    helpText: |
      Nutze diese Woche, um kritische Abhaengigkeiten sichtbar zu machen, bevor sie den Mid-Quarter-Check blockieren.
      Formuliere pro Abhaengigkeit Owner, benoetigten Termin, Risiko und konkrete Bitte.
      Plane nur Outcomes ein, die trotz der Abhaengigkeiten realistisch lieferbar sind.
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Nutze das Tool, um Personen, Rollen, Einfluss, Interesse und Owner direkt als Stakeholder-Tabelle zu strukturieren.
      - label: Atlassian - Dependency mapping
        href: https://www.atlassian.com/team-playbook/plays/dependency-mapping
        description: Nutze die Methode, um Abhängigkeiten, Owner und Risiken sichtbar zu machen.
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
      Halte geplante vs. erledigte Outcomes, Abhaengigkeiten, Owner und offene Entscheidungen fest.
      Das Deliverable ist fertig, wenn jede kritische Abhaengigkeit eine naechste Aktion und einen Owner hat.

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
    tableColumns: Outcome, Status, Evidence, Decision, Adjustment
    helpText: |
      Vergleiche Quartals-Outcomes mit echtem Fortschritt: Was ist fertig, was ist nur begonnen, und was erzeugt noch keinen Nutzen?
      Entscheide bewusst, was bleibt, was geschnitten wird und was in ein spaeteres Quartal wandert.
      Nutze diese Woche nicht fuer mehr Scope, sondern fuer einen besseren Restplan.
    helpLinks:
      - label: Impact-Effort Prioritizer
        href: /tools/impact-effort
        description: Nutze das Tool, um Initiativen nach Wirkung, Aufwand, Risiko und Abhängigkeiten zu priorisieren.
      - label: Atlassian - Health monitor
        href: https://www.atlassian.com/team-playbook/health-monitor
        description: Nutze die Fragen als Check, ob Team, Scope, Risiken und Entscheidungen gesund genug sind.
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
      Dokumentiere Fortschritt, Evidenz, gestrichenen Scope, neue Risiken und den Fokus fuer die naechsten Wochen.
      Das Deliverable ist fertig, wenn der Rest des Quartals kleiner und klarer geworden ist.

fields:
  - id: midquarter-adjustment
    label: Mid-Quarter-Anpassung
    type: textarea
    placeholder: Was bleibt, was wird geschnitten, was wird verschoben?

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
    tableColumns: Risk, Impact, Owner, Mitigation, Decision
    helpText: |
      Fokussiere die Woche auf die groessten Rest-Risiken. Jedes Risiko braucht Impact, Owner, Entscheidung und naechsten Schritt.
      Reduziere Risiko aktiv: testen, vereinfachen, entscheiden lassen oder Scope schneiden.
      Verschiebe Risiken nicht nur in die naechste Woche, wenn sie das Quartalsziel gefaehrden.
    helpLinks:
      - label: Atlassian - Risk assessment matrix
        href: https://www.atlassian.com/work-management/project-management/risk-assessment-matrix
        description: Nutze die Matrix, um Risiken nach Wahrscheinlichkeit und Impact einzuordnen.
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
      Halte fest, welche Risiken reduziert, akzeptiert oder eskaliert wurden.
      Das Deliverable ist fertig, wenn jedes Top-Risiko einen Owner und eine klare Entscheidung hat.

fields:
  - id: risk-burndown
    label: Risiko-Burn-down
    type: textarea
    placeholder: Top-Risiken, Entscheidungen, Owner, Rest-Risiko

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
    tableColumns: Outcome, Result, Evidence, Learning, Next quarter
    helpText: |
      Schliesse das Quartal ueber Outcomes ab, nicht ueber Aktivitaeten. Was wurde geliefert, entschieden, gelernt oder bewusst gestoppt?
      Sammle Evidenz: Links, Screenshots, Abnahmen, Metriken oder kurze Entscheidungsnotizen.
      Skizziere den naechsten Quartals-Backlog nur grob und trenne Carry-over von neuen Ideen.
    helpLinks:
      - label: Scrum Guide - Sprint Review
        href: https://scrumguides.org/scrum-guide.html#sprint-review
        description: Nutze den offiziellen Scrum Guide als Referenz für Review, Feedback und Anpassung des Backlogs.
      - label: Atlassian - Retrospective play
        href: https://www.atlassian.com/team-playbook/plays/retrospective
        description: Nutze die Retro-Struktur, um Learnings und Verbesserungen fürs nächste Sprint-/Quartalssetup zu sammeln.
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
      Dokumentiere Quartals-Outcomes, Learnings, offene Risiken und den groben Fokus fuer das naechste Quartal.
      Das Deliverable ist fertig, wenn klar ist, was abgeschlossen ist, was bewusst weitergeht und welche Entscheidung offen bleibt.

fields:
  - id: quarter-close
    label: Quartalsabschluss
    type: textarea
    placeholder: Outcomes, Learnings, offene Risiken, naechste Quartalsideen

notes: true
```
