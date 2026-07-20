---
type: sprint-plan
title: Report / Analyse mit KPIs (4 Wochen)
slug: report-kpi-analysis
description: Von Entscheidungsfragen zum abgenommenen Report: KPIs, Quellen, Pilot und Übergabe.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
category: Reporting
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Reporting
  - KPI
  - Analyse
---

Vier Wochen bis zum entscheidungsfähigen Report oder zur Analyse.

```sprint
id: week-01
number: 1
title: Fragen & Zielgruppe
goal: Entscheidungen und Zielgruppe klären.

stories:
  - slug: define-kpi
    required: true
  - slug: kpi-metric-governance
    required: false

tasks:
  - id: w1-questions
    label: Entscheidungsfragen formulieren
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Decision question, Audience, Action, KPI candidate, Success signal
    helpText: |
      Starte nicht mit Visuals, sondern mit Entscheidungen: Welche Frage soll der Report beantworten, wer handelt danach, und was waere eine bessere Entscheidung?
      Formuliere jede Frage so, dass sie eine Aktion ausloesen kann. Eine Kennzahl ohne moegliche Handlung ist meist nur Dekoration.
      Sammle KPI-Kandidaten, aber markiere sofort, welche davon nur Aufmerksamkeit erzeugen und welche wirklich Verhalten steuern.
      Nutze die KPI Definition Card, um Business-Frage, Owner und Erfolgssignal zu schaerfen.
    stories:
      - slug: define-kpi
        required: true
      - slug: kpi-metric-governance
        required: false
    helpLinks:
      - label: Report Inventory Canvas
        href: /tools/report-inventory
        description: Nutze das Tool, um Reports mit Owner, Tool, Rhythmus und Geschäftsfrage einheitlich zu inventarisieren.
      - label: KPI Definition Card
        href: /tools/kpi-definition
        description: Nutze das Tool, um KPI-Name, Formel, Grain, Filter, Owner und offene Definitionen sauber festzuhalten.
      - label: Tableau - Best Practices for Effective Dashboards
        href: https://help.tableau.com/current/pro/desktop/en-us/dashboards_best_practices.htm
        description: Nutze die Best Practices, um Dashboard-Struktur, Lesbarkeit und Nutzerführung zu prüfen.
      - label: Power BI - Report and dashboard creation
        href: https://learn.microsoft.com/en-us/power-bi/create-reports/
        description: Nutze die Doku als Referenz für Report-Aufbau, Seitenstruktur und Power-BI-Umsetzung.
  - id: w1-audience
    label: Konsumenten und Kadenz mappen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Consumer group, Decision, Cadence, Tool, Access need
    helpText: |
      Mappe Zielgruppen namentlich oder als klare Rollen: Entscheider, operative Nutzer, Analysten, Controller, externe Empfaenger.
      Notiere fuer jede Gruppe Kadenz, bevorzugtes Tool, Detailgrad und ob sie nur lesen oder aktiv weiterarbeiten muss.
      Pruefe, ob der Report eine wiederkehrende Entscheidung unterstuetzt oder nur eine einmalige Analyse ist.
      Achte auf Zielgruppen, die nicht im Meeting sitzen, aber spaeter Zugriff, Layout oder Definitionen blockieren koennen.

deliverables:
  - id: w1-brief
    label: Analyse-Briefing
    plannedMinutes: 180
    helpText: |
      Erstelle ein kurzes Briefing mit Entscheidungsfragen, Zielgruppen, Kadenz, Erfolgskriterien und expliziten Nicht-Zielen.
      Das Deliverable ist fertig, wenn Auftraggeber und Umsetzer dieselbe Antwort auf "Warum bauen wir das?" geben koennen.
      Halte Annahmen und offene Entscheidungen sichtbar fest, damit sie nicht spaeter als scheinbare Anforderungen zurueckkommen.

fields:
  - id: decision-questions
    label: Entscheidungsfragen
    type: textarea
    placeholder: Welche Entscheidungen soll der Report verbessern?
  - id: audience-cadence
    label: Zielgruppe & Kadenz
    type: textarea
    placeholder: Nutzergruppen, Nutzungshaeufigkeit, Tool, Zugriff

notes: true
```

```sprint
id: week-02
number: 2
title: KPI-Set
goal: KPIs und Definitionen festlegen.

tasks:
  - id: w2-kpis
    label: Kern-KPIs auswählen
    plannedMinutes: 300
    assigneeType: person
    assigneeId: null
    tableColumns: KPI, Formula, Grain, Owner, Refresh, Decision use
    helpText: |
      Waehle wenige Kern-KPIs, die direkt zu den Entscheidungsfragen passen. Jede Kennzahl braucht Name, Formel, Grain, Owner, Refresh und eine erklaerte Nutzung.
      Definiere Schwellwerte oder Vergleichswerte nur dort, wo sie fachlich haltbar sind.
      Halte das Set klein: lieber drei belastbare Kennzahlen als zehn Werte ohne Ownership.
      Nutze die KPI Definition Card fuer jede Kernkennzahl, bevor du sie in den Report uebernimmst.
    stories:
      - slug: define-kpi
        required: true
    helpLinks:
      - label: KPI Definition Card
        href: /tools/kpi-definition
        description: Nutze das Tool, um KPI-Name, Formel, Grain, Filter, Owner und offene Definitionen sauber festzuhalten.
      - label: Tableau - Visualize Key Progress Indicators
        href: https://help.tableau.com/current/pro/desktop/en-us/kpi.htm
        description: Nutze die Beispiele, um KPI-Darstellung und Schwellenwerte verständlich zu gestalten.
  - id: w2-defs
    label: Metrik-Definitionen dokumentieren
    plannedMinutes: 360
    assigneeType: person
    assigneeId: null
    tableColumns: Metric, Numerator, Denominator, Filters, Exclusions, Owner
    helpText: |
      Dokumentiere Zaehler, Nenner, Filter, Ausschluesse, Zeitzone, Rundung und Gueltigkeitsbereich.
      Schreibe Definitionen so, dass Fachbereich und Daten-Team denselben Wert reproduzieren koennen.
      Benenne bekannte Grauzonen statt sie zu verstecken, zum Beispiel Stornos, Testdaten, Rueckdatierungen oder manuelle Korrekturen.
      Eine Definition ist erst gut genug, wenn jemand sie testen und fachlich abnehmen kann.

deliverables:
  - id: w2-kpi-catalog
    label: KPI-Katalog-Entwurf
    plannedMinutes: 240
    helpText: |
      Erstelle einen KPI-Katalog mit Formel, Grain, Owner, Quelle, Refresh, fachlicher Abnahme und offenen Fragen.
      Das Deliverable ist fertig, wenn jede Kernkennzahl eine verantwortliche Person und eine pruefbare Definition hat.
      Markiere Kennzahlen, die noch nicht produktionsreif sind, statt sie stillschweigend als fertig darzustellen.
    helpLinks:
      - label: Atlassian - Acceptance criteria
        href: https://www.atlassian.com/work-management/project-management/acceptance-criteria
        description: Nutze die Beispiele, um klare Akzeptanzkriterien für Reports, Daten oder Änderungen zu formulieren.

fields:
  - id: kpi-scope
    label: KPI-Scope
    type: textarea
    placeholder: Kern-KPIs, ausgeschlossene Metriken, offene Definitionsfragen

notes: true
```

```sprint
id: week-03
number: 3
title: Quellen & Mock
goal: Quellen anbinden und Report mocken.

tasks:
  - id: w3-sources
    label: Quellfelder validieren
    plannedMinutes: 360
    assigneeType: person
    assigneeId: null
    tableColumns: Source field, Business meaning, Quality check, Owner, Issue
    helpText: |
      Validere fuer jede KPI die benoetigten Quellfelder: Verfuegbarkeit, Aktualitaet, fachliche Bedeutung und bekannte Qualitaetsprobleme.
      Nutze den Meta-Export fuer ein schnelles Spalten-Inventar, aber bestaetige kritische Felder mit Ownern oder Beispielwerten.
      Pruefe frueh, ob Filter, Joins oder historische Logik den KPI-Wert veraendern.
      Wenn ein Feld unklar ist, dokumentiere die Unsicherheit direkt im Mock statt sie erst bei der Abnahme zu entdecken.
    stories:
      - slug: bi-tools
        required: false
    helpLinks:
      - label: Meta-Export Generator
        href: /tools/meta-export-generator
        description: Nutze das Tool, um Metadaten aus Quellen, Feldern und Ownern als wiederverwendbaren Export vorzubereiten.
      - label: Power BI - Report and dashboard creation
        href: https://learn.microsoft.com/en-us/power-bi/create-reports/
        description: Nutze die Doku als Referenz für Report-Aufbau, Seitenstruktur und Power-BI-Umsetzung.
  - id: w3-mock
    label: Mock / Pilot-View bauen
    plannedMinutes: 720
    assigneeType: person
    assigneeId: null
    helpText: |
      Baue einen Wireframe oder duennen Pilot mit echten Beispielwerten, wenn moeglich.
      Zeige nur die wichtigsten Entscheidungen und Interaktionen; Details koennen spaeter wachsen.
      Hole Feedback zur Frage "Welche Entscheidung wuerdest du damit treffen?", nicht nur zu Farben oder Layout.
      Markiere Platzhalter, Datenluecken und Annahmen sichtbar, damit der Mock nicht als fertiger Report missverstanden wird.
    helpLinks:
      - label: Tableau - Best Practices for Effective Dashboards
        href: https://help.tableau.com/current/pro/desktop/en-us/dashboards_best_practices.htm
        description: Nutze die Best Practices, um Dashboard-Struktur, Lesbarkeit und Nutzerführung zu prüfen.

deliverables:
  - id: w3-pilot
    label: Pilot-View oder Mock
    plannedMinutes: 180
    helpText: |
      Lege Link oder Screenshot des Piloten zusammen mit Feedback-Notizen, offenen Datenfragen und Designentscheidungen ab.
      Das Deliverable ist fertig, wenn die Zielgruppe sagen kann, ob Richtung, Kennzahlen und Detailgrad passen.
      Dokumentiere bewusst, was noch nicht produktionsreif ist.

fields:
  - id: source-risks
    label: Quellenrisiken
    type: textarea
    placeholder: Fehlende Felder, Qualitaetsprobleme, offene Owner, Annahmen
  - id: pilot-feedback
    label: Pilot-Feedback
    type: textarea
    placeholder: Was passt, was fehlt, welche Entscheidung bleibt offen?

notes: true
```

```sprint
id: week-04
number: 4
title: Abnahme
goal: Report abnehmen und übergeben.

tasks:
  - id: w4-accept
    label: Abnahme-Checkliste durchgehen
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Check, Result, Evidence, Owner, Follow-up
    helpText: |
      Pruefe Korrektheit, Performance, Zugriff, Ownership, Aktualisierung und fachliche Verstaendlichkeit.
      Jede Abweichung braucht Entscheidung: beheben, akzeptieren, dokumentieren oder aus Scope nehmen.
      Akzeptanz heisst nicht "sieht gut aus", sondern "ist fuer die vereinbarte Entscheidung belastbar genug".
    stories:
      - slug: bi-tools
        required: false
    helpLinks:
      - label: Atlassian - Acceptance criteria
        href: https://www.atlassian.com/work-management/project-management/acceptance-criteria
        description: Nutze die Beispiele, um klare Akzeptanzkriterien für Reports, Daten oder Änderungen zu formulieren.
  - id: w4-handoff
    label: Handoff-Runbook
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Topic, Owner, Procedure, Frequency, Escalation
    helpText: |
      Schreibe auf, wie der Report aktualisiert wird, wer fachlich und technisch verantwortlich ist und wie Probleme gemeldet werden.
      Dokumentiere bekannte Limits offen: Datenlatenz, Ausschluesse, Filterlogik, Berechtigungen und manuelle Schritte.
      Uebergabe ist fertig, wenn jemand anderes den Report betreiben oder zumindest sinnvoll eskalieren kann.
    helpLinks:
      - label: Power BI - Report and dashboard creation
        href: https://learn.microsoft.com/en-us/power-bi/create-reports/
        description: Nutze die Doku als Referenz für Report-Aufbau, Seitenstruktur und Power-BI-Umsetzung.

deliverables:
  - id: w4-accepted
    label: Report abgenommen
    plannedMinutes: 180
    helpText: |
      Sammle Abnahme-Checkliste, Runbook-Link, bekannte Limits und offene Folgeaufgaben an einem Ort.
      Das Deliverable ist fertig, wenn fachliche Abnahme, Betriebsverantwortung und naechster Review-Termin dokumentiert sind.
      Vermeide stille Uebergaben: Jede offene Frage braucht Owner oder eine bewusste Entscheidung, sie nicht mehr zu verfolgen.

fields:
  - id: acceptance-summary
    label: Abnahme-Zusammenfassung
    type: textarea
    placeholder: Abgenommen von, bekannte Limits, offene Follow-ups, naechster Review

notes: true
```
