---
type: sprint-plan
title: Lernpfad — Governance Foundations (3 Wochen)
slug: learning-path-governance-foundations
description: Den Foundations-Lernpfad als kurzen Plan umsetzen — Sprache, Rollen-Serie, Säulen, Hub-Praxis.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 6
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Learning
  - Governance
  - Roles
  - RACI
---

Spiegelt den Learning Path „Governance Foundations“. Säulen und Rollen vor Zertifikaten und Frameworks.

```sprint
id: week-01
number: 1
title: Gemeinsame Sprache und Rollen
goal: Owner, Steward, Architect, Catalog und RACI als Einstieg — danach die Rollen-Serie.

stories:
  - slug: raci-for-data-governance
    required: true
  - slug: data-architect-role
    required: false

tasks:
  - id: gf-glossary
    label: Kern-Rollenbegriffe angleichen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Begriff, Arbeitsdefinition, Offene Frage
    helpText: |
      Mit Owner, Steward, Architect und RACI starten — vor Organigrammen.
    helpLinks:
      - label: Glossar — Data Owner
        href: /glossary/data-owner
        description: Fachlich accountable Rolle.
      - label: Glossar — Data Steward
        href: /glossary/data-steward
        description: Operative Qualitätsrolle.
      - label: Glossar — Data Architect
        href: /glossary/data-architect
        description: Grain und Contracts.
      - label: Roles Hub
        href: /roles
        description: Persona-Karten mit Stories und Tools.
  - id: gf-roles-series
    label: Roles-and-Decision-Rights-Serie lesen
    plannedMinutes: 210
    assigneeType: person
    assigneeId: null
    helpText: |
      Architect, RACI, Product Owner vs Owner, Stewardship Capacity und CoE zuerst im Überblick.
    stories:
      - slug: raci-for-data-governance
        required: true
      - slug: data-product-owner-vs-data-owner
        required: false
      - slug: stewardship-capacity
        required: false
      - slug: governance-coe
        required: false
    helpLinks:
      - label: Serie — Roles and Decision Rights
        href: /playbooks/series/roles-hub
        description: Roles-Hub-Story-Serie.

deliverables:
  - id: gf-role-map
    label: Arbeits-Rollenkarte
    plannedMinutes: 90
    helpText: |
      Fertig, wenn jede Persona eine benannte Person oder eine explizite Vakanz hat.

notes: true
```

```sprint
id: week-02
number: 2
title: Acht Säulen und Operating Practice
goal: Mentales Modell hinter Ownership, Metadata, PII, DQ und Lifecycle aufbauen.

stories:
  - slug: eight-pillars
    required: true
  - slug: data-ownership-stewardship
    required: true

tasks:
  - id: gf-pillars
    label: Eight-Pillars-Überblick lesen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    stories:
      - slug: eight-pillars
        required: true
    helpLinks:
      - label: Serie — Governance-Säulen
        href: /playbooks/series/governance-pillars
        description: Serie der acht Säulen.
  - id: gf-ownership
    label: Ownership & Stewardship lesen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: data-ownership-stewardship
        required: true
    helpText: |
      Decision Rights der eigenen Domäne festhalten — kein generisches Organigramm.
  - id: gf-raci-tool
    label: Stakeholder-/RACI-Matrix entwerfen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Arbeitsentwurf der Decision Rights.

deliverables:
  - id: gf-pillars-notes
    label: Säulen + RACI-Entwurf
    plannedMinutes: 90
    helpText: |
      Fertig, wenn Säulen-Map und erster RACI-Entwurf verlinkt sind.

notes: true
```

```sprint
id: week-03
number: 3
title: Hub-Praxis und Vertiefungswahl
goal: Entscheidungen im Hub führen, Radar beobachten, nächsten Lernpfad wählen.

tasks:
  - id: gf-hub
    label: Governance-Hub-Session führen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Szenario, Stack und offene Entscheidungen speichern, damit das Team sie wieder öffnen kann.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Advisor und Session speichern.
      - label: Radar
        href: /governance/radar
        description: Kuratierte Governance-News.
      - label: Compliance
        href: /compliance
        description: Compliance-Pflichten verorten.
  - id: gf-deep-dive
    label: Nächste Vertiefung wählen
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Option, Warum jetzt, Owner, Start-Link
    helpText: |
      Metadata Deep Dive, Missing Pieces oder PII-Pfad je nach Reife wählen.
    helpLinks:
      - label: Serie — MetaData Deep Dive
        href: /playbooks/series/metadata-deep-dive
        description: Metadata-Betrieb in der Tiefe.
      - label: Serie — Missing Pieces
        href: /playbooks/series/missing-pieces
        description: Was meist unfertig bleibt.
      - label: Path — PII in 5 Schritten
        href: /learning-paths/pii-in-five-steps
        description: Nächste kurze Privacy-Journey.

deliverables:
  - id: gf-next-path
    label: Next-Path-Entscheidung
    plannedMinutes: 45
    helpText: |
      Fertig, wenn der nächste Path oder die nächste Serie Owner und Startdatum hat.

notes: true
```
