---
type: sprint-plan
title: Lernpfad — PII in 5 Schritten (3 Wochen)
slug: learning-path-pii-in-five-steps
description: Den PII-Lernpfad als kurzen Teamplan umsetzen — Begriffe, Privacy-Story, DSDR, Readiness, Hub-Anker.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 4
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Learning
  - PII
  - DSDR
  - Privacy
---

Leichter Plan, der den Learning Path „PII in 5 Schritten“ spiegelt. Gemeinsame Sprache und Readiness-Gaps sichern, bevor Policies in Prod landen.

```sprint
id: week-01
number: 1
title: Begriffe und Privacy-Story
goal: PII, DSDR, Retention und Masking angleichen — dann lesen, warum Klassifikation vor Tooling kommt.

stories:
  - slug: pii-privacy-governance
    required: true

tasks:
  - id: pii-terms
    label: Glossar-Begriffe angleichen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Begriff, Arbeitsdefinition, Owner, Offene Frage
    helpText: |
      Gemeinsame Formulierung für PII, DSDR, Retention und Masking klären, bevor Generator oder Policy-Entwurf startet.
    helpLinks:
      - label: Glossar — PII
        href: /glossary/pii
        description: Gemeinsame Sprache für personenbezogene Daten.
      - label: Glossar — DSDR
        href: /glossary/dsdr
        description: Betroffenenrechte / Löschanfragen.
      - label: Glossar — Masking
        href: /glossary/masking
        description: Masking vs. Zugriffsmuster.
  - id: pii-story
    label: Privacy-Governance-Story lesen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    helpText: |
      Festhalten, warum Zweckbindung und Klassifikation vor Tooling kommen. DSGVO-Berührungspunkte zum eigenen Stack notieren.
    stories:
      - slug: pii-privacy-governance
        required: true
    helpLinks:
      - label: Compliance — DSGVO / GDPR
        href: /compliance/gdpr
        description: Compliance-Hub-Einstieg zur DSGVO.

deliverables:
  - id: pii-language-baseline
    label: Gemeinsame PII-Sprachbasis
    plannedMinutes: 60
    helpText: |
      Fertig, wenn das Team auf vereinbarte Definitionen und eine Seite offener Fragen zeigen kann.

notes: true
```

```sprint
id: week-02
number: 2
title: Löschpfade und Readiness
goal: Löschung/Lineage denken und Readiness-Gaps sichtbar machen, bevor Prod-Policies greifen.

stories:
  - slug: dsdr-governance
    required: true

tasks:
  - id: pii-dsdr-paths
    label: DSDR-/Lineage-Pfade skizzieren
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: System, Personenbezogene Daten, Löschpfad, Gap
    helpText: |
      Ohne Lineage enden DSDR-Anfragen im Ticket-Chaos. Wo liegen personenbezogene Daten, und wie würde Löschung fließen?
    stories:
      - slug: dsdr-governance
        required: true
    helpLinks:
      - label: Glossar — Lineage
        href: /glossary/lineage
        description: Warum Lineage Löschung absichert.
  - id: pii-readiness
    label: Readiness prüfen
    plannedMinutes: 150
    assigneeType: person
    assigneeId: null
    helpText: |
      Readiness-Checker und Policy-Generator als Entwurfs-Hilfe nutzen — nicht als fertige Policy.
    helpLinks:
      - label: PII/DSDR Readiness
        href: /tools/pii-dsdr-readiness-checker
        description: Gaps sichtbar machen, bevor Policies in Prod landen.
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Startpunkte für Klassifikation und Masking.

deliverables:
  - id: pii-readiness-note
    label: Readiness-Gap-Notiz
    plannedMinutes: 90
    helpText: |
      Fertig, wenn Gaps, Owner und nächste Schritte für Privacy und Platform sichtbar sind.

notes: true
```

```sprint
id: week-03
number: 3
title: Im Hub verankern
goal: Entscheidung, Stack und nächste Schritte im Governance Advisor festhalten.

tasks:
  - id: pii-hub-session
    label: Advisor-Session speichern
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Lage (Hilfe / Ergänzen), Stack und PII-bezogene Entscheidungen speichern, damit das Team sie später wieder öffnen kann.
    helpLinks:
      - label: Governance Hub
        href: /governance
        description: Advisor-Einstieg für Orientierung und Session-Speichern.
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder
        description: Optionaler herstellerspezifischer Lern-Follow-up.
  - id: pii-roles-check
    label: Passende Rollen bestätigen
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Klären, wer Steward, Custodian und Consumer für den personenbezogenen Scope ist.
    helpLinks:
      - label: Roles Hub
        href: /roles
        description: Decision-Rights-Karten für Governance-Personas.

deliverables:
  - id: pii-next-actions
    label: Next-Actions-Liste
    plannedMinutes: 60
    helpText: |
      Fertig, wenn die nächsten drei Aktionen Owner und einen Link zurück zum Lernpfad oder Sprint-Plan haben.

notes: true
```
