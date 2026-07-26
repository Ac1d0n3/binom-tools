---
type: sprint-plan
title: Learning path — AI foundations (3 weeks)
slug: learning-path-ai-foundations
description: Turn the AI foundations learning path into a short plan — series basics, governance/eval, metadata readiness, hub handoff.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 5
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: en
tags:
  - Learning
  - AI
  - Governance
  - Metadata
---

Mirrors the Learning Path “AI foundations”. Guardrails and metadata readiness before RAG and agents hit production.

```sprint
id: week-01
number: 1
title: AI foundations series
goal: Align on basics, failure modes, and what “good enough” means for your context.

stories:
  - slug: ai-basics
    required: true
  - slug: ai-failures
    required: false

tasks:
  - id: ai-series
    label: Walk AI foundations series
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Cover basics, models, agents, and eval enough to share one mental model with the team.
    helpLinks:
      - label: Series — AI Foundations
        href: /playbooks/series/ai-foundations
        description: Orientation series for AI in data work.
  - id: ai-failures-read
    label: Review typical AI failures
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    stories:
      - slug: ai-failures
        required: false
    helpText: |
      List the failure modes most likely in your stack and data landscape.

deliverables:
  - id: ai-risk-note
    label: AI risk note
    plannedMinutes: 45
    helpText: |
      Done when top risks and non-goals for the first use case are named.

notes: true
```

```sprint
id: week-02
number: 2
title: Governance and eval
goal: Define guardrails and measurability before production traffic.

stories:
  - slug: ai-gov
    required: true
  - slug: ai-eval
    required: false

tasks:
  - id: ai-gov-read
    label: Read AI governance chapter
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: ai-gov
        required: true
    helpText: |
      Decide owners, approval gates, and evidence expectations for AI use cases.
  - id: ai-eval-read
    label: Sketch eval criteria
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    stories:
      - slug: ai-eval
        required: false
    tableColumns: Criterion, Measure, Owner, Gate
    helpText: |
      Pick a minimal eval set that can block a bad release.

deliverables:
  - id: ai-gates
    label: AI gate checklist
    plannedMinutes: 60
    helpText: |
      Done when approval owner and first eval gates are written down.

notes: true
```

```sprint
id: week-03
number: 3
title: Metadata readiness and handoff
goal: Prepare metadata for AI/RAG and connect to the metadata operating model path.

stories:
  - slug: prepare-metadata-for-ai-rag-and-model-training
    required: true

tasks:
  - id: ai-metadata
    label: Review metadata-for-AI readiness
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    stories:
      - slug: prepare-metadata-for-ai-rag-and-model-training
        required: true
    helpText: |
      Identify which metadata gaps would poison retrieval or training first.
    helpLinks:
      - label: Path — Metadata operating model
        href: /learning-paths/metadata-operating-model
        description: Operating model for catalog, lineage, and automation.
  - id: ai-handoff
    label: Capture hub decision and vendor learning
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Record the decision and optionally plan stack-specific learning.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Capture decision context.
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder
        description: Role-based vendor docs and certificates.

deliverables:
  - id: ai-readiness-checklist
    label: AI readiness checklist
    plannedMinutes: 45
    helpText: |
      Done when risks, gates, metadata gaps, and next path are linked.

notes: true
```
