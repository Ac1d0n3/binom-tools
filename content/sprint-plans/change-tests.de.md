---
type: sprint-plan
title: DB-/Report-Änderung mit Tests (3 Wochen)
slug: change-tests
description: DB- oder Report-Änderung sicher liefern: Impact, Tests, Verify, Release.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 1
capacity_hours_per_person_week: 40
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
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Object, Change, Consumer, Risk, Rollback
    helpText: |
      Beschreibe die Aenderung so, dass sie testbar ist: Was aendert sich, wo wird es sichtbar, und welches Verhalten darf sich nicht aendern?
      Liste betroffene Tabellen, Reports, Downstream-Jobs, Konsumenten und bekannte SLAs.
      Dokumentiere Rollback oder Rueckfalloption, bevor du mit der Umsetzung startest.
      Nutze den Impact-Effort Prioritizer fuer Scope und Risiko, aber halte die technische Impact-Liste direkt an der Aufgabe.
    stories:
      - slug: data-quality-governance
        required: true
      - slug: missing-pieces-data-quality
        required: false
    helpLinks:
      - label: Impact-Effort Prioritizer
        href: /tools/impact-effort
        description: Nutze das Tool, um Initiativen nach Wirkung, Aufwand, Risiko und Abhängigkeiten zu priorisieren.
      - label: GitHub Docs - Continuous integration
        href: https://docs.github.com/en/actions/get-started/continuous-integration
        description: Nutze die Doku als Referenz, um Continuous-Integration-Checks für Änderungen aufzusetzen oder zu prüfen.
  - id: w1-scope
    label: In/Out-of-Scope vereinbaren
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Scope item, In/Out, Reason, Owner, Decision
    helpText: |
      Vereinbare explizit, was diese Runde aendert und was nicht. Besonders wichtig sind Metrikdefinitionen, Filter, historische Werte, Berechtigungen und Layout.
      Formuliere Nicht-Ziele so konkret wie Ziele, damit spaetere Diskussionen nicht als "nur kleine Zusatzanforderung" zurueckkommen.
      Jede Scope-Entscheidung braucht Owner oder Abnahme, sonst ist sie nur eine Annahme.
    helpLinks:
      - label: Atlassian - Acceptance criteria
        href: https://www.atlassian.com/work-management/project-management/acceptance-criteria
        description: Nutze die Beispiele, um klare Akzeptanzkriterien für Reports, Daten oder Änderungen zu formulieren.

deliverables:
  - id: w1-impact-note
    label: Impact-Notiz
    plannedMinutes: 120
    helpText: |
      Erstelle eine Impact-Notiz mit Aenderungszusammenfassung, betroffenen Objekten, Konsumenten, Scope-Grenzen und Rollback.
      Das Deliverable ist fertig, wenn Reviewer erkennen koennen, welche Tests erforderlich sind und welche Risiken bewusst akzeptiert werden.
      Verlinke Tickets, PRs, Reports oder Beispielabfragen, die den Impact belegen.

fields:
  - id: change-summary
    label: Änderungszusammenfassung
    type: textarea
    placeholder: Was aendert sich, warum, wo wird es sichtbar?
  - id: rollback-plan
    label: Rollback-Plan
    type: textarea
    placeholder: Rueckfalloption, Verantwortliche, Ausloeser fuer Rollback

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
    plannedMinutes: 360
    assigneeType: person
    assigneeId: null
    tableColumns: Risk, Test type, Assertion, Severity, Owner
    helpText: |
      Waehle Tests aus dem Risiko heraus, nicht aus Gewohnheit: Frische, Eindeutigkeit, Not-Null, referenzielle Integritaet, akzeptierte Werte, KPI-Sanity oder Custom-SQL.
      Nutze bestehende DQ-Muster und Generatoren, damit Testnamen, Severity und Failure-Handling konsistent bleiben.
      Kritische Tests sollten fail-closed sein; informative Checks duerfen warnen, muessen aber trotzdem Owner haben.
      Dokumentiere fuer jeden Test, welche Annahme er schuetzt und was bei Fehlschlag zu tun ist.
    stories:
      - slug: dq-test-kpis
        required: false
      - slug: dq-test2
        required: false
    helpLinks:
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Nutze das Tool, um Datenqualitätsregeln aus beobachteten Problemen in testbare Checks zu übersetzen.
      - label: DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
        description: Nutze das Tool, um wiederverwendbare dbt-Makros für Datenqualitätsprüfungen vorzubereiten.
      - label: dbt Labs - Data quality testing
        href: https://www.getdbt.com/blog/data-quality-testing
        description: Nutze den Artikel, um Testarten und DQ-Denken in konkrete Checks zu übersetzen.
  - id: w2-fixtures
    label: Fixtures / erwartete Ergebnisse vorbereiten
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Scenario, Input, Expected result, Edge case, Evidence
    helpText: |
      Bereite bekannte gute Rows, Beispiel-KPIs, Screenshots oder kleine Abfragen vor, die erwartetes Verhalten beweisen.
      Decke mindestens den Normalfall und einen relevanten Randfall ab, zum Beispiel Nullwerte, Storno, leere Dimension oder spaet eintreffende Daten.
      Fixtures muessen klein genug sein, um sie im Review zu verstehen, aber konkret genug, um Fehlinterpretationen zu verhindern.
    helpLinks:
      - label: GitHub Docs - Status checks
        href: https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/collaborating-on-repositories-with-code-quality-features/about-status-checks
        description: Nutze die Doku als Referenz für automatisierte Checks, Review-Signale und Freigabequalität.

deliverables:
  - id: w2-test-pack
    label: Test-Pack
    plannedMinutes: 180
    helpText: |
      Erstelle eine Liste neuer oder geaenderter Tests mit Assertion, Severity, Owner, Zielumgebung und erwartetem Verhalten.
      Das Deliverable ist fertig, wenn ein Reviewer jeden Test einem Impact-Risiko zuordnen kann.
      Ergaenze Hinweise fuer Warnungen, erlaubte Ausnahmen und manuelle Checks.

fields:
  - id: test-strategy
    label: Teststrategie
    type: textarea
    placeholder: Kritische Checks, Warnungen, manuelle Verifikation, offene Testluecken

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
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Check, Environment, Result, Evidence, Follow-up
    helpText: |
      Fuehre Tests in der Zielumgebung aus und sichere Ergebnis, Zeitpunkt, Version und relevante Artefakte.
      Kritische Checks muessen fail-closed bleiben: kein Release, solange Datenintegritaet, Zugriff oder zentrale KPI-Sanity brechen.
      Vergleiche Testresultate mit den Fixtures und klaere Abweichungen, bevor du sie als "bekannt" markierst.
      Nutze den DQ History Generator, wenn Testresultate als Verlauf oder Release-Beleg dokumentiert werden sollen.
    helpLinks:
      - label: DQ History Generator
        href: /tools/dbt-dq-history-generator
        description: Nutze das Tool, um DQ-Ergebnisse über Zeit nachvollziehbar zu machen und Validierung zu belegen.
      - label: Microsoft Learn - Power BI enterprise content publishing
        href: https://learn.microsoft.com/en-us/power-bi/guidance/powerbi-implementation-planning-usage-scenario-enterprise-content-publishing
        description: Nutze die Guidance, um Veröffentlichung, Review und Freigabe von Enterprise-Reports zu planen.
  - id: w3-release
    label: Release Notes und Monitoring
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Change, Watch signal, Owner, Escalation, Time window
    helpText: |
      Schreibe Release Notes, die Fachbereich und Betrieb verstehen: Was wurde geaendert, warum, welches Verhalten ist neu und worauf soll geachtet werden?
      Lege Monitoring-Signale fuer die ersten Tage fest, zum Beispiel Refresh, Fehlerrate, row count, KPI-Abweichung oder Nutzerfeedback.
      Benenne Eskalationsweg und Zeitfenster, damit Probleme nicht erst im naechsten Regeltermin auffallen.
    helpLinks:
      - label: Microsoft Fabric - Automate deployment pipelines
        href: https://learn.microsoft.com/en-us/fabric/cicd/deployment-pipelines/pipeline-automation
        description: Nutze die Doku als Referenz für Deployment-Pipelines und automatisierte Freigaben in Fabric.

deliverables:
  - id: w3-release
    label: Release-Nachweis
    plannedMinutes: 180
    helpText: |
      Sammle Testergebnisse, Release Notes, Monitoring-Plan, bekannte Restrisiken und Rollback-Entscheidung.
      Das Deliverable ist fertig, wenn nachvollziehbar ist, welche Version released wurde und warum sie akzeptabel ist.
      Offene Follow-ups brauchen Owner und Zieltermin, sonst bleiben sie nicht releasefaehig.

fields:
  - id: verification-summary
    label: Verifikationszusammenfassung
    type: textarea
    placeholder: Testergebnisse, Abweichungen, Release-Entscheidung, Monitoring
  - id: release-watch
    label: Release-Monitoring
    type: textarea
    placeholder: Signale, Owner, Eskalationsweg, Beobachtungszeitraum

notes: true
```
