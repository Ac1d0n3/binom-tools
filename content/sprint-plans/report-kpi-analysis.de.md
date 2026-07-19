---
type: sprint-plan
title: Report / Analyse mit KPIs (4 Wochen)
slug: report-kpi-analysis
description: Von Entscheidungsfragen zum abgenommenen Report: KPIs, Quellen, Pilot und Übergabe.
duration: 4
unit: week
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
    assigneeType: person
    assigneeId: null
    helpText: |
      Welche Entscheidung ändert der Report? Wer handelt?
      Vanity Metrics vermeiden.
    linkedStories: define-kpi, kpi-metric-governance
    helpLinks:
      - label: KPI definieren
        href: /playbooks/define-kpi
      - label: KPI-Governance
        href: /playbooks/kpi-metric-governance
  - id: w1-audience
    label: Konsumenten und Kadenz mappen
    assigneeType: person
    assigneeId: null
    helpText: |
      Wer braucht ihn, wie oft, in welchem Tool.

deliverables:
  - id: w1-brief
    label: Analyse-Briefing
    helpText: |
      Fragen, Zielgruppe, Kadenz, Erfolgskriterien.

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
    assigneeType: person
    assigneeId: null
    helpText: |
      Name, Formel, Grain, Owner, Refresh.
      Set klein halten.
    linkedStories: define-kpi
    helpLinks:
      - label: KPI definieren
        href: /playbooks/define-kpi
  - id: w2-defs
    label: Metrik-Definitionen dokumentieren
    assigneeType: person
    assigneeId: null
    helpText: |
      Gemeinsame Sprache für Zähler/Nenner und Filter.

deliverables:
  - id: w2-kpi-catalog
    label: KPI-Katalog-Entwurf
    helpText: |
      Tabelle mit Formeln und Ownern.

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
    assigneeType: person
    assigneeId: null
    helpText: |
      Verfügbarkeit und Qualität prüfen.
      Meta-Export hilft beim Spalten-Inventory.
    helpLinks:
      - label: Meta-Export Generator
        href: /tools/meta-export-generator
      - label: BI-Tools
        href: /playbooks/bi-tools
  - id: w3-mock
    label: Mock / Pilot-View bauen
    assigneeType: person
    assigneeId: null
    helpText: |
      Wireframe oder dünner Pilot für Feedback.

deliverables:
  - id: w3-pilot
    label: Pilot-View oder Mock
    helpText: |
      Link/Screenshot + Feedback-Notizen.

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
    assigneeType: person
    assigneeId: null
    helpText: |
      Korrektheit, Performance, Zugriff, Ownership.
    linkedStories: bi-tools
    helpLinks:
      - label: BI-Tools
        href: /playbooks/bi-tools
  - id: w4-handoff
    label: Handoff-Runbook
    assigneeType: person
    assigneeId: null
    helpText: |
      Refresh, Incident-Owner, bekannte Limits.

deliverables:
  - id: w4-accepted
    label: Report abgenommen
    helpText: |
      Checkliste + Runbook-Link.

notes: true
```
