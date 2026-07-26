---
type: sprint-plan
title: Lernpfad — Gaps schließen (3 Wochen)
slug: learning-path-close-the-gaps
description: Den Gaps-schließen-Lernpfad als kurzen Plan umsetzen — Missing-Pieces-Serie, Ownership/Capacity, operative Gaps, nächster Pfad.
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
  - Missing Pieces
---

Spiegelt den Learning Path „Gaps schließen“. Missing Pieces als Diagnose nutzen, dann eine Vertiefung priorisieren.

```sprint
id: week-01
number: 1
title: Mit Missing Pieces diagnostizieren
goal: Serie lesen und Foundations sichern, bevor die Gap-Triage startet.

tasks:
  - id: gaps-series
    label: Missing-Pieces-Serie lesen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Zuerst die ganze Serie skimmen — Druckpunkte wählen, nicht alles gleichzeitig fixen.
    helpLinks:
      - label: Serie — The Missing Pieces
        href: /playbooks/series/missing-pieces
        description: Diagnose-Serie für stecken gebliebene Governance.
      - label: Pfad — Governance Foundations
        href: /learning-paths/governance-foundations
        description: Voraussetzung gemeinsame Sprache und Rollen.
  - id: gaps-score
    label: Top-drei Gaps bewerten
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Gap, Evidence, Impact, Owner, Priorität
    helpText: |
      Nach Business-Impact und Readiness ranken — nicht nach Themeninteresse.

deliverables:
  - id: gaps-triage
    label: Gap-Triage-Board
    plannedMinutes: 60
    helpText: |
      Fertig, wenn drei priorisierte Gaps Owner und Evidence-Links haben.

notes: true
```

```sprint
id: week-02
number: 2
title: Ownership und Capacity
goal: Ownership Missing Piece und Stewardship-Capacity adressieren.

stories:
  - slug: missing-pieces-ownership-stewardship
    required: true
  - slug: stewardship-capacity
    required: false

tasks:
  - id: gaps-ownership
    label: Ownership Missing Piece lesen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: missing-pieces-ownership-stewardship
        required: true
    helpText: |
      Benennen, wo Rollen auf Papier existieren, Capacity und Eskalation aber nicht.
  - id: gaps-capacity
    label: Stewardship-Capacity skizzieren
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: stewardship-capacity
        required: false
    helpText: |
      Realistischen FTE-Anteil und Scope-Cut für Domain-Stewards schätzen.
    helpLinks:
      - label: Roles — Data Steward
        href: /roles/steward
        description: Decision Rights und verwandte Stories des Stewards.

deliverables:
  - id: gaps-capacity-note
    label: Capacity- und Ownership-Notiz
    plannedMinutes: 60
    helpText: |
      Fertig, wenn mindestens eine Domain Steward-Capacity und Eskalationspfad benannt hat.

notes: true
```

```sprint
id: week-03
number: 3
title: Operative Gaps und nächster Pfad
goal: Metadata, DQ, Metrics, Access, Lifecycle prüfen — dann einen Vertiefungspfad wählen.

tasks:
  - id: gaps-ops-review
    label: Operative Missing Pieces reviewen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    stories:
      - slug: missing-pieces-metadata-catalog-lineage
        required: false
      - slug: missing-pieces-data-quality
        required: false
      - slug: missing-pieces-trusted-metrics
        required: false
      - slug: missing-pieces-policy-access-governance
        required: false
      - slug: missing-pieces-data-lifecycle-retirement
        required: false
    helpText: |
      Woche-1-Prioritäten mit Evidence aus den operativen Kapiteln bestätigen oder anpassen.
  - id: gaps-next-path
    label: Nächsten Lernpfad wählen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Eine Vertiefung festlegen, damit Lernen in Betriebsänderung mündet.
    helpLinks:
      - label: Pfad — Metadata Operating Model
        href: /learning-paths/metadata-operating-model
        description: Catalog, Lineage und Metadata-Produktbetrieb.
      - label: Pfad — DQ mit dbt
        href: /learning-paths/dq-with-dbt
        description: DQ als Betriebsdisziplin mit dbt.
      - label: Pfad — Trusted Metrics
        href: /learning-paths/trusted-metrics
        description: KPI-Contracts und Metric Ops.

deliverables:
  - id: gaps-action-plan
    label: Gap-Aktionsplan
    plannedMinutes: 60
    helpText: |
      Fertig, wenn nächster Pfad, Owner und erstes 30-Tage-Outcome notiert sind.

notes: true
```
