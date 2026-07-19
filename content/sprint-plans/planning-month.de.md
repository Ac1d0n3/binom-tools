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
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Priority, Outcome, Owner, Effort, Decision
    helpText: |
      Formuliere ein Monatsziel und maximal drei messbare Wochenziele. Trenne Pflichtarbeit von Warteliste, damit der Monat nicht schon in Woche 1 ueberlaeuft.
      Schaetze Kapazitaet grob in Tagen oder Stunden und ziehe feste Termine, Urlaub und Betriebsaufgaben ab.
      Halte Erfolgskriterien schriftlich fest: Was muss sichtbar, entschieden oder uebergeben sein?
      Nutze bei Bedarf den Impact-Effort Prioritizer, aber uebernimm nur Arbeit, fuer die es Owner und ein realistisches Done-Kriterium gibt.
    stories:
      - slug: eight-pillars
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
  - id: w1-standup-board
    label: Board und Owner aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Fuehre ein Board als Wahrheit fuer den Monat. Jede aktive Aufgabe braucht Owner, naechsten Schritt, Termin oder eine bewusste Entscheidung, sie zu parken.
      Raeume doppelte Listen und private Nebenboards auf, sonst wird Statusarbeit zur Sucharbeit.
      Achte besonders auf Aufgaben ohne Owner, veraltete Faelligkeiten und Arbeit, die laenger als eine Woche ohne Bewegung bleibt.
    helpLinks:
      - label: Atlassian - Product backlog
        href: https://www.atlassian.com/en/agile/scrum/backlogs
        description: Nutze die Erklärung, um Backlog-Einträge sauber zu schneiden und zu priorisieren.

deliverables:
  - id: w1-week-outcome
    label: Monatsprioritäten dokumentiert
    plannedMinutes: 120
    helpText: |
      Erstelle eine datierte Notiz mit Monatsziel, Top-Prioritaeten, Kapazitaetsannahmen und expliziten Nicht-Zielen.
      Das Deliverable ist fertig, wenn Sponsor, Team oder du selbst daraus lesen koennen, worauf der Monat fokussiert ist und was bewusst spaeter kommt.
      Verlinke Board, offene Entscheidungen und die wichtigsten Abhaengigkeiten.

fields:
  - id: month-focus
    label: Monatsfokus
    type: textarea
    placeholder: Wichtigstes Ergebnis, Nicht-Ziele und Erfolgskriterien
  - id: capacity-assumptions
    label: Kapazitätsannahmen
    type: textarea
    placeholder: Verfuegbare Tage, fixe Termine, Urlaub, Betrieb, Puffer

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
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Work package, Owner, Done criteria, Risk, Next step
    helpText: |
      Zerlege jedes Wochenziel in kleine Arbeitspakete, die innerhalb weniger Tage pruefbar sind. Schreibe pro Paket Owner, Done-Kriterium und naechsten Schritt auf.
      Schneide Scope frueh, wenn Kapazitaet eng wird: lieber ein kleineres Ergebnis abschliessen als mehrere halb offene Linien erzeugen.
      Pruefe vor dem Start, ob Abhaengigkeiten, Zugriffe oder Review-Personen fehlen.
    helpLinks:
      - label: Atlassian - Sprint backlog vs. product backlog
        href: https://www.atlassian.com/agile/project-management/sprint-backlog-product-backlog
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.
  - id: w2-standup-board
    label: Board und Owner aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Aktualisiere das Board nach Standup oder Midweek-Check, nicht erst am Ende der Woche.
      Status soll die naechste Entscheidung erleichtern: weiterarbeiten, entblocken, schneiden oder bewusst verschieben.
      Wenn eine Aufgabe haengt, notiere den konkreten Blocker und wer ihn loesen kann.
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Nutze das Tool, um Personen, Rollen, Einfluss, Interesse und Owner direkt als Stakeholder-Tabelle zu strukturieren.
      - label: Scrum.org - Introduction to the Sprint
        href: https://www.scrum.org/resources/introduction-sprint
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.

deliverables:
  - id: w2-week-outcome
    label: Wochenplan mit Ownern
    plannedMinutes: 30
    helpText: |
      Sichere einen Board-Snapshot oder eine kurze Liste der aktiven Arbeitspakete mit Owner, Done-Kriterium und naechstem Schritt.
      Das Deliverable ist fertig, wenn eine andere Person den Wochenstand ohne weiteres Meeting nachvollziehen kann.
      Markiere Arbeit, die bewusst gestrichen oder verschoben wurde.

fields:
  - id: delivery-risks
    label: Liefer-Risiken
    type: textarea
    placeholder: Blocker, knappe Kapazitaet, fehlende Reviews oder offene Zugriffe

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
    plannedMinutes: 30
    assigneeType: person
    assigneeId: null
    tableColumns: Blocker, Dependency, Owner, Decision, Due date
    helpText: |
      Liste Blocker, Abhaengigkeiten und offene Entscheidungen getrennt. Ein Blocker braucht immer Owner, naechste Aktion und einen Zeitpunkt fuer die Wiedervorlage.
      Entscheide bewusst zwischen entblocken, Scope kuerzen, verschieben oder stoppen.
      Eskaliere nur mit klarer Bitte: Welche Entscheidung wird gebraucht, von wem, bis wann und mit welcher Auswirkung?
    helpLinks:
      - label: Impact-Effort Prioritizer
        href: /tools/impact-effort
        description: Nutze das Tool, um Initiativen nach Wirkung, Aufwand, Risiko und Abhängigkeiten zu priorisieren.
      - label: Atlassian - Sprint planning
        href: https://www.atlassian.com/agile/scrum/sprint-planning
        description: Nutze den Leitfaden als Referenz für Ziel, Umfang und Commitment einer Sprint-Planung.
  - id: w3-standup-board
    label: Board und Owner aktualisieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    helpText: |
      Spiegele Umplanungen am selben Tag im Board. Entferne alte Prioritaeten sichtbar oder verschiebe sie sauber in den naechsten Monat.
      Markiere blockierte Aufgaben nicht nur als rot, sondern schreibe den Grund und den naechsten Entblockungsschritt dazu.
      Nutze die Mitte des Monats, um Fokus zu retten, nicht um alles gleichzeitig weiterzuschieben.

deliverables:
  - id: w3-week-outcome
    label: Risiko- und Abhängigkeitsliste
    plannedMinutes: 30
    helpText: |
      Erstelle eine kurze Liste mit Risiken, Abhaengigkeiten, Ownern, Entscheidungen und Termin.
      Das Deliverable ist fertig, wenn fuer jedes Risiko klar ist, ob es akzeptiert, reduziert, eskaliert oder in Scope umgewandelt wird.
      Offene Punkte ohne Owner gehoeren nicht in die Liste, sondern zurueck in die Klaerung.

fields:
  - id: replanning-decision
    label: Nachplanungsentscheidung
    type: textarea
    placeholder: Was bleibt, was wird gekuerzt, was wird verschoben?

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
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Outcome, Status, Evidence, Learning, Follow-up
    helpText: |
      Sammle, was geliefert wurde, was gerutscht ist und warum. Belege Ergebnisse mit Links, Screenshots, kurzen Notizen oder Entscheidungen.
      Trenne Ergebnis, Lernen und offene Nacharbeit, damit der Monatsabschluss nicht zum diffusen Rueckblick wird.
      Besprich nur die wichtigsten Punkte live; Details gehoeren in die Notiz oder ins Board.
    helpLinks:
      - label: Scrum Guide - Sprint Review
        href: https://scrumguides.org/scrum-guide.html#sprint-review
        description: Nutze den offiziellen Scrum Guide als Referenz für Review, Feedback und Anpassung des Backlogs.
  - id: w4-standup-board
    label: Backlog für nächsten Monat skizzieren
    plannedMinutes: 15
    assigneeType: person
    assigneeId: null
    tableColumns: Backlog item, Owner, Reason, Target week, Next step
    helpText: |
      Uebertrage nur Arbeit in den naechsten Monat, die noch relevant ist. Alles andere wird geschlossen, archiviert oder bewusst verworfen.
      Formuliere neue Backlog-Eintraege als Ergebnis plus Grund, nicht nur als lose Idee.
      Gib jedem Carry-over einen Owner und eine Zielwoche, sonst wird er zum stillen Dauerrest.
    helpLinks:
      - label: Atlassian - Retrospective play
        href: https://www.atlassian.com/team-playbook/plays/retrospective
        description: Nutze die Retro-Struktur, um Learnings und Verbesserungen fürs nächste Sprint-/Quartalssetup zu sammeln.

deliverables:
  - id: w4-week-outcome
    label: Monatsabschluss + Next-Backlog
    plannedMinutes: 60
    helpText: |
      Schreibe einen One-Pager mit Outcomes, Learnings, offenen Risiken und dem groben Next-Backlog.
      Das Deliverable ist fertig, wenn klar ist, welche Arbeit abgeschlossen ist, welche bewusst weitergeht und welche Entscheidung fuer den naechsten Monat offen bleibt.
      Fuege Retro-Aktionen mit Owner und Termin hinzu, statt nur Beobachtungen zu sammeln.
    helpLinks:
      - label: Scrum Guide - Sprint Retrospective
        href: https://scrumguides.org/scrum-guide.html#sprint-retrospective
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.

fields:
  - id: month-review
    label: Monatsreview
    type: textarea
    placeholder: Outcomes, Learnings, offene Risiken, naechste Entscheidungen

notes: true
```
