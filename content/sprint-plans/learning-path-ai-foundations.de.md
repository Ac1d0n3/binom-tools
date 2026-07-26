---
type: sprint-plan
title: Lernpfad — AI Foundations (3 Wochen)
slug: learning-path-ai-foundations
description: Den AI-Foundations-Lernpfad als kurzen Plan umsetzen — Serie, Governance/Eval, Metadata-Readiness, Hub-Übergabe.
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
  - AI
  - Governance
  - Metadata
---

Spiegelt den Learning Path „AI Foundations“. Guardrails und Metadata-Readiness, bevor RAG und Agents in Prod gehen.

```sprint
id: week-01
number: 1
title: AI-Foundations-Serie
goal: Basics, Failure Modes und „good enough“ für den eigenen Kontext angleichen.

stories:
  - slug: ai-basics
    required: true
  - slug: ai-failures
    required: false

tasks:
  - id: ai-series
    label: AI-Foundations-Serie lesen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Basics, Models, Agents und Eval soweit abdecken, dass das Team ein gemeinsames Modell teilt.
    helpLinks:
      - label: Serie — AI Foundations
        href: /playbooks/series/ai-foundations
        description: Orientierungs-Serie für AI in der Datenarbeit.
  - id: ai-failures-read
    label: Typische AI-Failures prüfen
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    stories:
      - slug: ai-failures
        required: false
    helpText: |
      Failure Modes auflisten, die im eigenen Stack und Datenlandschaft am wahrscheinlichsten sind.

deliverables:
  - id: ai-risk-note
    label: AI-Risiko-Notiz
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Top-Risiken und Non-Goals für den ersten Use Case benannt sind.

notes: true
```

```sprint
id: week-02
number: 2
title: Governance und Eval
goal: Guardrails und Messbarkeit definieren, bevor Produktivverkehr startet.

stories:
  - slug: ai-gov
    required: true
  - slug: ai-eval
    required: false

tasks:
  - id: ai-gov-read
    label: AI-Governance-Kapitel lesen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: ai-gov
        required: true
    helpText: |
      Owner, Freigabe-Gates und Evidence-Erwartungen für AI-Use-Cases entscheiden.
  - id: ai-eval-read
    label: Eval-Kriterien skizzieren
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: ai-eval
        required: false
    tableColumns: Kriterium, Maß, Owner, Gate
    helpText: |
      Minimales Eval-Set wählen, das einen schlechten Release blockieren kann.

deliverables:
  - id: ai-gates
    label: AI-Gate-Checkliste
    plannedMinutes: 60
    helpText: |
      Fertig, wenn Freigabe-Owner und erste Eval-Gates notiert sind.

notes: true
```

```sprint
id: week-03
number: 3
title: Metadata-Readiness und Übergabe
goal: Metadata für AI/RAG vorbereiten und an den Metadata-Operating-Model-Pfad anbinden.

stories:
  - slug: prepare-metadata-for-ai-rag-and-model-training
    required: true

tasks:
  - id: ai-metadata
    label: Metadata-für-AI-Readiness prüfen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: prepare-metadata-for-ai-rag-and-model-training
        required: true
    helpText: |
      Identifizieren, welche Metadata-Gaps Retrieval oder Training zuerst vergiften würden.
    helpLinks:
      - label: Pfad — Metadata Operating Model
        href: /learning-paths/metadata-operating-model
        description: Operating Model für Catalog, Lineage und Automation.
  - id: ai-handoff
    label: Hub-Entscheidung und Vendor-Lernen festhalten
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Entscheidung dokumentieren und optional stack-spezifisches Lernen planen.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Entscheidungskontext festhalten.
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder
        description: Rollenbasierte Vendor-Doku und Zertifikate.

deliverables:
  - id: ai-readiness-checklist
    label: AI-Readiness-Checkliste
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Risiken, Gates, Metadata-Gaps und nächster Pfad verlinkt sind.

notes: true
```
