---
type: sprint-plan
title: Lernpfad — Access & Security Ops (3 Wochen)
slug: learning-path-access-security-ops
description: Den Access-&-Security-Ops-Lernpfad als kurzen Plan umsetzen — Policy, Masking-Praxis, Decision Rights.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 5
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Learning
  - Access
  - Security
  - Privacy
---

Spiegelt den Learning Path „Access & Security Ops“. Policy und Masking als Betriebsdisziplin, nicht als Folien.

```sprint
id: week-01
number: 1
title: Access-Governance-Sprache
goal: Policy, Rollen und typische Access-Failure-Modes angleichen.

stories:
  - slug: access-security-governance
    required: true

tasks:
  - id: access-story
    label: Access & Security Governance lesen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: access-security-governance
        required: true
    helpText: |
      Festhalten, wer Access-Änderungen freigibt und wo Policy heute scheitert.
  - id: access-missing
    label: Access Missing Piece prüfen
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    stories:
      - slug: missing-pieces-policy-access-governance
        required: false
    helpText: |
      Gap zwischen geschriebener Policy und Runtime-Enforcement benennen.

deliverables:
  - id: access-gap-note
    label: Access-Gap-Notiz
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Top-Access-Gaps und Owner gelistet sind.

notes: true
```

```sprint
id: week-02
number: 2
title: Masking in der Praxis
goal: Policy-gesteuerte Masking-Muster für den aktuellen Stack entwerfen.

tasks:
  - id: masking-story
    label: Masking- & Section-Access-Patterns prüfen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: snowflake-masking-policies-qlik-section-access
        required: false
    helpText: |
      Patterns extrahieren, die zu Warehouse und BI-Schicht passen.
    helpLinks:
      - label: Glossar — Masking
        href: /glossary/masking
        description: Gemeinsame Masking-Sprache.
  - id: masking-draft
    label: PII-Policy-Startpunkte entwerfen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    tableColumns: Dataset, Policy, Rolle, Status
    helpText: |
      Generator-Output als Entwurf behandeln — mit Steward und Custodian reviewen.
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Policy-Startpunkte für PII-Handling.

deliverables:
  - id: masking-draft-pack
    label: Masking-Entwurfspaket
    plannedMinutes: 60
    helpText: |
      Fertig, wenn ein Prioritäts-Dataset einen reviewed Masking-Entwurf hat.

notes: true
```

```sprint
id: week-03
number: 3
title: Decision Rights und Übergabe
goal: RACI für Access klären und an den PII-Pfad anbinden.

tasks:
  - id: access-raci
    label: Access-RACI festhalten
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Entscheidung, R, A, C, I
    helpText: |
      Freigabe vs. Umsetzung für Access- und Masking-Änderungen trennen.
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Decision-Rights-Workbench.
      - label: Roles Hub
        href: /roles
        description: Personas und verwandte Stories.
  - id: access-next
    label: In den PII-Pfad weitergehen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      PII-Pfad nutzen, wenn Klassifikation und DSDR noch fehlen.
    helpLinks:
      - label: Pfad — PII in 5 Schritten
        href: /learning-paths/pii-in-five-steps
        description: Kürzester belastbarer Privacy-Einstieg.

deliverables:
  - id: access-ops-checklist
    label: Access-Ops-Checkliste
    plannedMinutes: 45
    helpText: |
      Fertig, wenn RACI, Policy-Entwurf und nächster Pfad dokumentiert sind.

notes: true
```
