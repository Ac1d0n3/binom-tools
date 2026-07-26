---
type: sprint-plan
title: Lernpfad — Warehouse modernisieren (3 Wochen)
slug: learning-path-modernize-warehouse
description: Den Warehouse-Modernisierungs-Lernpfad als kurzen Plan umsetzen — Grain, Serie, Supplier, End-to-End-Governance.
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
  - Warehouse
  - Architecture
---

Spiegelt den Learning Path „Warehouse modernisieren“. Grain und Products klären, bevor Stack-Debatten starten.

```sprint
id: week-01
number: 1
title: Zielbild und Grain
goal: Data Product und Grain klären, bevor Stack-Debatten starten.

tasks:
  - id: wh-grain
    label: Product- und Grain-Hypothesen formulieren
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    tableColumns: Product, Grain, Consumer, Offenes Risiko
    helpText: |
      Arbeits-Grain und Product-Grenze zuerst schreiben. Architektur-Debatten danach.
    helpLinks:
      - label: Glossar — Data Product
        href: /glossary/data-product
        description: Sprache für Product-Grenzen.
      - label: Glossar — Grain
        href: /glossary/grain
        description: Grain als Modell-Contract.
  - id: wh-architect-role
    label: Architect vs Custodian klären
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Klären, wer Modellkonsistenz vs. Laufzeit-Obhut für den Modernisierungs-Scope trägt.
    helpLinks:
      - label: Roles — Data Architect
        href: /roles/architect
        description: Grain, Contracts, Konsistenz.
      - label: Roles — Data Custodian
        href: /roles/custodian
        description: Technische Obhut über Systeme.

deliverables:
  - id: wh-grain-note
    label: Grain- und Product-Notiz
    plannedMinutes: 60
    helpText: |
      Fertig, wenn Product, Grain und offene Risiken auf eine Seite passen.

notes: true
```

```sprint
id: week-02
number: 2
title: Serie und Supplier-Muster
goal: Modern-Warehouse-Serie lesen und Supplier-Muster übernehmen.

tasks:
  - id: wh-series
    label: Modern-Data-Warehouse-Serie lesen
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    helpText: |
      Fokus auf Schichten, Marts und Betriebsmodell passend zum aktuellen Stack.
    helpLinks:
      - label: Serie — Building a Modern Data Warehouse
        href: /playbooks/series/building-modern-data-warehouse
        description: Zehnteilige Warehouse-Serie.
  - id: wh-suppliers
    label: Supplier-Muster übernehmen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Quelle, Kerndimensionen, PII-Hinweis, Owner
    helpText: |
      Kerndimensionen und PII-Hinweise aus der Supplier Library übernehmen statt neu erfinden.
    helpLinks:
      - label: Supplier Library
        href: /suppliers
        description: Quellenmuster und PII-Hinweise.
      - label: Stack Advisor
        href: /tools/governance-stack-advisor
        description: Ziel-Stack-Shortlist.

deliverables:
  - id: wh-pattern-pack
    label: Quellen-Musterpaket
    plannedMinutes: 90
    helpText: |
      Fertig, wenn mindestens eine Prioritätsquelle mit Dims/PII-Notizen verlinkt ist.

notes: true
```

```sprint
id: week-03
number: 3
title: End-to-End Governance anbinden
goal: Warehouse ohne Ownership und Metadata bleibt ein teures Dateisystem.

tasks:
  - id: wh-e2e
    label: End-to-End-Governance-Serie anbinden
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Ownership, Metadata und Operating Practice an das Warehouse-Zielbild anschließen.
    helpLinks:
      - label: Serie — End-to-End Data Governance
        href: /playbooks/series/end-to-end-data-governance
        description: Governance-Anbindung fürs Warehouse.
      - label: Governance Hub
        href: /governance
        description: Entscheidung und nächste Schritte festhalten.
  - id: wh-next
    label: Nächste Delivery-Vorlage wählen
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Modellierungsarbeit nach den Lernwochen in eine Delivery-Vorlage (z. B. database-model) überführen.
    helpLinks:
      - label: Sprint Planner Templates
        href: /sprint-planner/templates
        description: Nächsten Delivery-Plan wählen.

deliverables:
  - id: wh-decision-brief
    label: Modernisierungs-Decision-Brief
    plannedMinutes: 60
    helpText: |
      Fertig, wenn Zielbild, Gaps, Owner und nächster Sprint-Link stehen.

notes: true
```
