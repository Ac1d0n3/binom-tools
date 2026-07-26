---
type: sprint-plan
title: Governance Finance Mart Umsetzung (4 Wochen)
slug: governance-finance-mart-implementation
description: Aus Governance Discovery, KPI Cards, Source Scope, DQ-Regeln und PII Review einen umsetzbaren Finance Mart Plan machen.
duration: 4
unit: week
recommended_people_min: 2
recommended_people_max: 5
capacity_hours_per_person_week: 24
category: Governance
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Governance
  - KPI
  - Data Quality
  - PII
  - Finance
---

Vier Wochen für einen sauberen Governance-Umsetzungsplan: erst Session und Scope prüfen, dann Risiken und Datenqualität festlegen, danach Mart Design und Entscheidung als Change-fähige Grundlage abschließen.

```sprint
id: week-01
number: 1
title: Session und Entscheidung prüfen
goal: Discovery-Ergebnis, Entscheidung und offene Fragen in einen belastbaren Arbeitsstand bringen.

stories:
  - slug: end-to-end-governance-architecture
    required: true
  - slug: define-kpi
    required: true

tasks:
  - id: review-governance-session
    label: Governance Session und Report prüfen
    plannedMinutes: 240
    assigneeType: team
    assigneeId: null
    tableColumns: Thema, Stand, Owner, Entscheidung, offene Frage
    helpText: |
      Prüfe die gespeicherte Governance Session nicht als Textablage, sondern als Entscheidungsgrundlage. Stimmen Ziel, Szenario, Stack, Supplier, KPI-Set und Risiken zusammen?
      Markiere Lücken als konkrete Review-Fragen mit Owner und Fälligkeitsdatum.
    helpLinks:
      - label: Governance Demo Workspace
        href: /governance/demo-workspace
        description: Zeigt einen gefüllten Arbeitsstand mit Hauptplan, Lernplan, KPI Cards, Tool-Ergebnissen und Report.
      - label: Decision Brief Generator
        href: /tools/decision-brief-generator
        description: Verdichtet Kontext, Empfehlung, Risiken, offene Fragen und nächste Aufgaben.
  - id: align-kpi-cards
    label: KPI Cards finalisieren
    plannedMinutes: 300
    assigneeType: team
    assigneeId: null
    tableColumns: KPI, Formel, Grain, Owner, Akzeptanzbeispiel, DQ-Kandidat
    helpText: |
      Jede KPI braucht Geschäftsfrage, Formel, Grain, Owner und ein Beispiel. Ohne Beispiel ist die Definition noch nicht prüfbar.
      Übernimm offene Definitionsfragen direkt in den Plan, statt sie im Report-Text zu verstecken.
    helpLinks:
      - label: KPI Requirements Intake
        href: /tools/kpi-requirements-intake
        description: Sammelt KPI-Anforderungen als prüfbare KPI Card.

deliverables:
  - id: decision-ready-session
    label: Entscheidungsreifer Session-Stand
    plannedMinutes: 180
    helpText: |
      Fertig, wenn Entscheidung, KPI Cards, Owner, offene Fragen und nächster Sprint nachvollziehbar dokumentiert sind.

fields:
  - id: session-report
    label: Session Report Link oder Notiz
    type: textarea
    placeholder: Link, Report-Abschnitt oder wichtigste offene Fragen
  - id: kpi-decisions
    label: KPI Entscheidungen
    type: textarea
    placeholder: KPI, Grain, Formel, Owner, Akzeptanzbeispiel

notes: true
```

```sprint
id: week-02
number: 2
title: Source Scope, PII und DSDR
goal: Quellobjekte, ausgeschlossene Daten, Personenbezug, Retention und Owner als Gate festlegen.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: dsdr-governance
    required: true

tasks:
  - id: source-scope-review
    label: Source Scope und Supplier-Entscheidung festhalten
    plannedMinutes: 300
    assigneeType: team
    assigneeId: null
    tableColumns: Objekt, Must-have, Optional, Skip, Grund, Owner
    helpText: |
      Entscheide bewusst, was geladen wird und was nicht. Eine Skip-Liste ist ein Governance-Ergebnis, kein fehlender Scope.
      Prüfe Supplier-Hinweise, Kernobjekte, Standard-Measures und System-Owner zusammen.
    helpLinks:
      - label: Source Scope Builder
        href: /tools/source-scope-builder
        description: Klärt Must-have, Optional, Skip, PII, DSDR Keys und Owner.
      - label: Supplier Library
        href: /suppliers
        description: Liefert Kernobjekte, PII-Hinweise und typische Loads pro Supplier.
  - id: pii-dsdr-gate
    label: PII/DSDR Gate prüfen
    plannedMinutes: 360
    assigneeType: team
    assigneeId: null
    tableColumns: Feld, Identifier, Kopie, Retention, Zugriff, Kontrolle
    helpText: |
      Prüfe Personenbezug vor der Umsetzung. Wichtig sind nicht nur Felder, sondern alle Kopien in Raw, Curated, Mart, Semantic Layer und Exporten.
    helpLinks:
      - label: PII/DSDR Readiness Checker
        href: /tools/pii-dsdr-readiness-checker
        description: Prüft Identifier, Freitext, Kopien, Retention und Access-Risiken.

deliverables:
  - id: risk-backlog
    label: Risk Backlog mit Ownern
    plannedMinutes: 180
    helpText: |
      Fertig, wenn Risiken, Kontrollen, Owner und Freigabepflicht vor Build oder Release klar sind.

notes: true
```

```sprint
id: week-03
number: 3
title: Datenqualität und Mart Design
goal: KPI Cards und Source Scope in Fact-/Dimension-Kandidaten, DQ-Regeln und Release-Gates übersetzen.
dependsOn: week-02

stories:
  - slug: metadata-driven-governance-with-dbt-meta
    required: true
  - slug: data-quality-ownership
    required: false

tasks:
  - id: dq-rule-candidates
    label: DQ-Regeln und Validierung festlegen
    plannedMinutes: 360
    assigneeType: team
    assigneeId: null
    tableColumns: Regel, Schicht, KPI, Schweregrad, Owner, Gate
    helpText: |
      Leite Regeln aus echten Problemen ab: Freshness, Vollständigkeit, Business-Logik und Abgleich mit bestehenden Reports.
      Markiere, welche Regeln nur warnen und welche Build, Release oder Report-Freigabe blockieren.
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt Fabric-spezifische DQ-Regeln und Checks.
      - label: dbt DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Erzeugt dbt-Testideen für Modelle und Quellen.
  - id: mart-design-brief
    label: Mart Design Brief erstellen
    plannedMinutes: 420
    assigneeType: team
    assigneeId: null
    tableColumns: Fact, Grain, Dimension, Historie, Measure, offene Modellfrage
    helpText: |
      Nutze KPI Cards und Source Scope als Input. Das Mart Design ist erst gut, wenn Grain, Historie, Dimensionen, DQ-Gates und Ownership zusammenpassen.
    helpLinks:
      - label: Mart Design Brief
        href: /tools/mart-design-brief-generator
        description: Führt KPI Cards zu Fact-/Dimension-Kandidaten und Modellierungsfragen.

deliverables:
  - id: quality-and-model-brief
    label: Quality Gate und Mart Brief
    plannedMinutes: 240
    helpText: |
      Fertig, wenn DQ-Regeln, Mart-Grain, offene Modellfragen und Owner in einem Review-fähigen Stand stehen.

notes: true
```

```sprint
id: week-04
number: 4
title: Decision Brief und Change Request
goal: Umsetzung, Lernplan, Report und spätere Änderungen als steuerbaren Workflow abschließen.
dependsOn: week-03

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: true

tasks:
  - id: final-decision-brief
    label: Decision Brief finalisieren
    plannedMinutes: 300
    assigneeType: team
    assigneeId: null
    tableColumns: Entscheidung, Empfehlung, Risiko, Annahme, nächste Aktion, Approver
    helpText: |
      Der Brief soll eine Entscheidung ermöglichen, nicht nur dokumentieren. Trenne Empfehlung, Annahmen, Risiken, offene Fragen und nächsten Sprint.
    helpLinks:
      - label: Decision Brief Generator
        href: /tools/decision-brief-generator
        description: Erstellt eine klare Vorlage für Sponsor, Architekturboard oder Change Review.
  - id: change-request-model
    label: Change Request Bedarf und Lernplan koppeln
    plannedMinutes: 240
    assigneeType: team
    assigneeId: null
    tableColumns: Änderung, Grund, Impact, Approval, Lernbedarf, Nachweis
    helpText: |
      Halte fest, welche Änderungen später über Change Request laufen müssen. Koppel Lernbedarf und Zertifikate dort an, wo Skills ein Umsetzungsrisiko sind.
    helpLinks:
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder
        description: Plant Doku, Übungen und Zertifikate passend zu Rolle, Stack und Projekt.

deliverables:
  - id: governance-workspace-report
    label: Druckbarer Governance Report mit nächstem Plan
    plannedMinutes: 240
    helpText: |
      Fertig, wenn Report, Plan-Aufgaben, Review-Owner, Change-Request-Regel und paralleler Lernplan zusammenpassen.

notes: true
```
