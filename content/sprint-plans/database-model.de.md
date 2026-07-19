---
type: sprint-plan
title: Datenbankmodell (4 Wochen)
slug: database-model
description: Fokussiertes DB-/Warehouse-Modell: Scope, Entitäten, Beziehungen, Naming und Review.
duration: 4
unit: week
category: Data Modeling
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Modeling
  - Datenbank
  - Warehouse
---

Vier Wochen von den Fragen bis zum freigegebenen Modell v1.

```sprint
id: week-01
number: 1
title: Scope & Fragen
goal: Klären, welche Fragen das Modell beantworten muss.

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: choosing-the-simplest-viable-architecture
    required: false

tasks:
  - id: w1-scope
    label: Business-Fragen und Grain festhalten
    assigneeType: person
    assigneeId: null
    helpText: |
      Entscheidungen und Grain je Fact listen.
      Achtung: operative und analytische Grains nicht mischen.
    linkedStories: before-building-the-first-table
    helpLinks:
      - label: Bevor die erste Tabelle
        href: /playbooks/before-building-the-first-table
  - id: w1-sources
    label: Kandidaten-Quellen inventarisieren
    assigneeType: person
    assigneeId: null
    helpText: |
      Systeme, Owner, Frische und bekannte Qualitätsprobleme.
      Meta-Export-Skripte nutzen, wo hilfreich.
    helpLinks:
      - label: Meta-Export Generator
        href: /tools/meta-export-generator

deliverables:
  - id: w1-scope-note
    label: Scope- & Grain-Notiz
    helpText: |
      Fragen, Grain und Out-of-Scope.

notes: true
```

```sprint
id: week-02
number: 2
title: Entitäten & Beziehungen
goal: Entitäten, Keys und Beziehungen entwerfen.

tasks:
  - id: w2-entities
    label: Entitätsliste und Keys entwerfen
    assigneeType: person
    assigneeId: null
    helpText: |
      Natural vs. Surrogate Keys, Uniqueness, SCD-Bedarf.
      Einfachst-sinnvolle Struktur bevorzugen.
    linkedStories: beyond-bronze-silver-gold, choosing-the-simplest-viable-architecture
    helpLinks:
      - label: Beyond Bronze/Silver/Gold
        href: /playbooks/beyond-bronze-silver-gold
  - id: w2-rels
    label: Beziehungen und Kardinalität mappen
    assigneeType: person
    assigneeId: null
    helpText: |
      1:n / n:m und Ownership von Bridge-Tabellen dokumentieren.

deliverables:
  - id: w2-model-draft
    label: ER-Entwurf
    helpText: |
      Diagramm oder Tabellenliste mit Keys und Kardinalität.

notes: true
```

```sprint
id: week-03
number: 3
title: Naming & Standards
goal: Naming, Typen und Konventionen anwenden.

tasks:
  - id: w3-naming
    label: Naming-Konventionen anwenden
    assigneeType: person
    assigneeId: null
    helpText: |
      Tabellen, Spalten, Boolean-/Datums-Muster an Plattformstandards ausrichten.
  - id: w3-types
    label: Typen und Nullability bestätigen
    assigneeType: person
    assigneeId: null
    helpText: |
      Defaults und Pflichtfelder; PII-Kandidaten notieren.

deliverables:
  - id: w3-standards
    label: Naming- & Typstandards angewendet
    helpText: |
      Checkliste oder PR-Notizen.

notes: true
```

```sprint
id: week-04
number: 4
title: Review & Freeze
goal: Mit Ownern reviewen und v1 einfrieren.

tasks:
  - id: w4-review
    label: Walkthrough mit Stakeholdern
    assigneeType: person
    assigneeId: null
    helpText: |
      Offene Fragen und Acceptance von v1 festhalten.
    linkedStories: data-ownership-stewardship
    helpLinks:
      - label: Ownership & Stewardship
        href: /playbooks/data-ownership-stewardship
  - id: w4-freeze
    label: Modell v1 einfrieren und Next Steps
    assigneeType: person
    assigneeId: null
    helpText: |
      Modell versionieren; Follow-ups für physische Umsetzung listen.

deliverables:
  - id: w4-model-v1
    label: Modell v1 freigegeben
    helpText: |
      Freigegebenes Artefakt + offene Follow-ups.

notes: true
```
