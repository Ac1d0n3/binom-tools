---
type: sprint-plan
title: dbt-Zertifizierung Begleiter (4 Wochen)
slug: learning-path-cert-dbt-analytics-engineer
description: Wochenbegleiter während der offiziellen dbt-Zertifizierung — Learn-Kurs, Labs, Exam, Projekt-Transfer.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 3
capacity_hours_per_person_week: 8
category: Learning
author: Thomas Lindackers
version: 2
locale: de
tags:
  - Learning
  - Certification
  - dbt
  - Analytics Engineering
---

Begleitplan für alle, die die **dbt-Zertifizierung machen**. Offizielles Learn/Docs bleibt das Curriculum; dieser Plan hält Cadence, Labs und Projekt-Transfer fest.

```sprint
id: week-01
number: 1
title: Anmelden und Exam-Fenster setzen
goal: Offizielles dbt Learn starten, Zertifikat wählen und Exam-Fenster fixieren.

stories:
  - slug: dbt-role
    required: false

tasks:
  - id: dbt-cert-enroll
    label: Offiziellen Cert- + Learn-Track öffnen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Zertifikat, Learn-Kurs, Exam-Fenster, Status
    helpText: |
      Auf den offiziellen Seiten starten — dieser Plan begleitet die Cert; er ersetzt dbt Learn nicht.
    helpLinks:
      - label: dbt Certifications
        href: https://www.getdbt.com/certifications
        description: Offizielle Zertifizierungs-Übersicht.
      - label: Analytics Engineering Certificate
        href: https://learn.getdbt.com/courses/analytics-engineering
        description: Zertifikatspfad auf dbt Learn.
      - label: dbt Fundamentals
        href: https://learn.getdbt.com/courses/dbt-fundamentals
        description: Einstiegskurs, falls Warm-up nötig.
  - id: dbt-cert-baseline
    label: Rolle und Projekt-Transfer-Ziel festhalten
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    stories:
      - slug: dbt-role
        required: false
    tableColumns: Rolle, dbt im Projekt, Skill-Lücke, Transfer-Ziel
    helpText: |
      Benennen, was das Badge im aktuellen Projekt verbessern muss (Tests, Contracts, Meta, Ownership).

deliverables:
  - id: dbt-cert-plan-card
    label: Cert-Plan-Karte
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Zertifikat, Learn-Start, Exam-Fenster und Transfer-Ziel notiert sind.

fields:
  - id: exam-window
    label: Exam-Fenster
    type: textarea
    placeholder: Zielwoche / Buchungsstatus

notes: true
```

```sprint
id: week-02
number: 2
title: Domains auf offiziellen Materialien lernen
goal: Modelle, Tests, Contracts und Meta über dbt Docs/Learn durcharbeiten.
dependsOn: week-01

tasks:
  - id: dbt-cert-study-core
    label: Tests, Contracts, Meta lernen
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Domain, Quelle, Notizen, Offene Frage
    helpText: |
      Dem offiziellen Curriculum folgen; Docs-Links als Anker für Schwachstellen nutzen.
    helpLinks:
      - label: Docs — Data Tests
        href: https://docs.getdbt.com/docs/build/data-tests
        description: Tests im DAG.
      - label: Docs — Model Contracts
        href: https://docs.getdbt.com/docs/collaborate/govern/model-contracts
        description: Schema-Verträge und Breaking Changes.
      - label: Docs — Meta Config
        href: https://docs.getdbt.com/reference/resource-configs/meta
        description: Meta für Ownership, PII und DQ.
      - label: dbt Learn
        href: https://learn.getdbt.com/
        description: Eingeschriebene Kurse fortsetzen.
  - id: dbt-cert-gov-story
    label: Governance-Companion-Stories lesen
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    stories:
      - slug: metadata-driven-governance-with-dbt-meta
        required: false
    helpText: |
      Exam-Themen mit der realen Nutzung von dbt meta in Governance-Stacks verbinden.

deliverables:
  - id: dbt-cert-study-log
    label: Study-Log
    plannedMinutes: 45
    helpText: |
      Fertig, wenn schwache Domains und nächste Praxis-Themen gelistet sind.

notes: true
```

```sprint
id: week-03
number: 3
title: Labs parallel zum Kurs
goal: Praxis-Artefakte erzeugen, die als Exam-Prep und Projekt-Evidence dienen.
dependsOn: week-02

tasks:
  - id: dbt-cert-labs
    label: DQ- / Governance-Labs durchführen
    plannedMinutes: 210
    assigneeType: person
    assigneeId: null
    tableColumns: Lab, Artefakt, Reviewer, Status
    helpText: |
      Generator-Output als Lab-Material behandeln — vor Evidence mit einem Steward reviewen.
    helpLinks:
      - label: Pfad — DQ mit dbt
        href: /learning-paths/dq-with-dbt
        description: Betriebs-Praxispfad für DQ mit dbt.
      - label: dbt DQ Macros
        href: /tools/dbt-dq-macro-generator
        description: Macro-Lab-Startpunkte.
      - label: dbt DQ Rules
        href: /tools/dbt-dq-rules-generator
        description: Rules-Lab-Startpunkte.
      - label: dbt Governance Macros
        href: /tools/dbt-governance-macro-generator
        description: Governance-Macro-Lab.
  - id: dbt-cert-mock
    label: Mock-Review / Übungsdurchgang
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Thema, Score/Feeling, Lücke, Nächste Aktion
    helpText: |
      Learn-Übungen nutzen, falls vorhanden; sonst Schwachstellen aus Woche 2 selbst quizen.

deliverables:
  - id: dbt-cert-lab-pack
    label: Lab-Evidence-Paket
    plannedMinutes: 60
    helpText: |
      Fertig, wenn mindestens ein reviewed Lab-Artefakt und eine Mock-Review-Notiz existieren.

notes: true
```

```sprint
id: week-04
number: 4
title: Exam-Woche und Projekt-Transfer
goal: Exam ablegen, Nachweis sichern und Skills in Delivery überführen.
dependsOn: week-03

tasks:
  - id: dbt-cert-exam
    label: Exam ablegen / Zertifikatsanforderungen erfüllen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Exam-Datum, Ergebnis, Badge/Proof-Link, Notizen
    helpText: |
      Buchung und Ergebnis-Links im Plan halten — der Begleiter endet, wenn Nachweis und Transfer dokumentiert sind.
    helpLinks:
      - label: dbt Certifications
        href: https://www.getdbt.com/certifications
        description: Offizieller Zertifizierungs-Hub.
      - label: Resources — Vendor Certs
        href: /resources
        description: Kuratierte Vendor-Doku und Zertifikatslinks.
  - id: dbt-cert-transfer
    label: In das Projekt transferieren
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Learning, Projekt-Task, Owner, Due
    helpText: |
      Eine Cert-Skill in eine konkrete Delivery-Aufgabe überführen (Tests, Contract, Meta, Ownership).
    helpLinks:
      - label: Pfad — Cert + Projekt parallel
        href: /learning-paths/cert-project-evidence
        description: Optionaler gemeinsamer Evidence-Track für Teams.
      - label: Sprint Planner
        href: /sprint-planner
        description: Weiter mit Delivery-Vorlagen.

deliverables:
  - id: dbt-cert-done
    label: Cert-Complete-Checkliste
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Exam-Proof, Lab-Paket und mindestens ein Projekt-Transfer-Task verlinkt sind.

notes: true
```
