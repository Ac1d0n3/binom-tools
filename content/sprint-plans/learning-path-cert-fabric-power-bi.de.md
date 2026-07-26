---
type: sprint-plan
title: Fabric / Power BI Zertifizierung Begleiter (4 Wochen)
slug: learning-path-cert-fabric-power-bi
description: Wochenbegleiter während DP-600 und/oder PL-300 — Microsoft Learn, Labs, Exam, Projekt-Transfer.
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
  - Fabric
  - Power BI
---

Begleitplan für alle, die **DP-600 und/oder PL-300 machen**. Microsoft Learn bleibt das Curriculum; dieser Plan hält Cadence, Labs und Transfer fest.

```sprint
id: week-01
number: 1
title: Exam wählen und Learn starten
goal: DP-600 und/oder PL-300 wählen, Microsoft Learn starten, Exam-Fenster fixieren.

tasks:
  - id: ms-cert-enroll
    label: Offizielle Cert-Seiten und Learn-Pfade öffnen
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    tableColumns: Zertifikat, Learn-Pfad, Exam-Fenster, Status
    helpText: |
      Auf Microsoft Learn starten — dieser Plan begleitet die Cert; er ersetzt die offiziellen Module nicht.
    helpLinks:
      - label: DP-600 Fabric Analytics Engineer
        href: https://learn.microsoft.com/en-us/credentials/certifications/fabric-analytics-engineer-associate/
        description: Offizielle DP-600-Seite.
      - label: PL-300 Power BI Data Analyst
        href: https://learn.microsoft.com/en-us/credentials/certifications/power-bi-data-analyst-associate/
        description: Offizielle PL-300-Seite.
      - label: Learn — Get started with Fabric
        href: https://learn.microsoft.com/en-us/training/paths/get-started-fabric/
        description: Fabric-Fundamentals-Lernpfad.
      - label: Learn — Power BI
        href: https://learn.microsoft.com/en-us/training/powerplatform/power-bi
        description: Power-BI-Lerneinstieg.
  - id: ms-cert-baseline
    label: Rolle und Projekt-Transfer-Ziel festhalten
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    tableColumns: Rolle, Stack-Nutzung, Skill-Lücke, Transfer-Ziel
    helpText: |
      Benennen, was das Badge im aktuellen Programm verbessern muss (Lakehouse, Semantic Model, Workspace-Rollen, Access).

deliverables:
  - id: ms-cert-plan-card
    label: Cert-Plan-Karte
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Zertifikat(e), Learn-Start, Exam-Fenster und Transfer-Ziel notiert sind.

fields:
  - id: exam-window
    label: Exam-Fenster
    type: textarea
    placeholder: Zielwoche / Buchungsstatus für DP-600 und/oder PL-300

notes: true
```

```sprint
id: week-02
number: 2
title: Domains auf Microsoft Learn lernen
goal: Lakehouse-, Semantic- und Governance-Themen auf offiziellen Materialien durcharbeiten.
dependsOn: week-01

tasks:
  - id: ms-cert-study
    label: Fabric- / Power-BI-Domains lernen
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Domain, Quelle, Notizen, Offene Frage
    helpText: |
      Learn-Module für das gewählte Exam folgen; Docs für Schwachstellen nutzen.
    helpLinks:
      - label: Docs — Fabric Lakehouse
        href: https://learn.microsoft.com/en-us/fabric/data-engineering/lakehouse-overview
        description: Lakehouse-Übersicht.
      - label: Docs — Fabric Governance
        href: https://learn.microsoft.com/en-us/fabric/governance/
        description: Governance in Fabric.
      - label: Docs — Fabric Workspace Roles
        href: https://learn.microsoft.com/en-us/fabric/fundamentals/roles-workspaces
        description: Workspace-Rollen und Rechte.
  - id: ms-cert-context-paths
    label: Platform-Companion-Pfade anbinden
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    helpText: |
      Exam-Prep an Warehouse- und Access-Betriebspraxis koppeln.
    helpLinks:
      - label: Pfad — Warehouse modernisieren
        href: /learning-paths/modernize-warehouse
        description: Grain, Products, Warehouse-Serie.
      - label: Pfad — Access & Security Ops
        href: /learning-paths/access-security-ops
        description: Access- und Masking-Praxis.

deliverables:
  - id: ms-cert-study-log
    label: Study-Log
    plannedMinutes: 45
    helpText: |
      Fertig, wenn schwache Domains und nächste Lab-Themen gelistet sind.

notes: true
```

```sprint
id: week-03
number: 3
title: Labs parallel zu Learn
goal: Stack-/Governance-Artefakte als Exam-Prep und Projekt-Evidence erzeugen.
dependsOn: week-02

tasks:
  - id: ms-cert-labs
    label: Architecture- / Decision-Labs durchführen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Lab, Artefakt, Reviewer, Status
    helpText: |
      Binom-Tools als Labs nutzen — Entscheidungen festhalten, die die Cert-Skill verbessern soll.
    helpLinks:
      - label: Architecture Fit
        href: /tools/architecture-fit
        description: Fit-Check-Lab.
      - label: Stack Advisor
        href: /tools/governance-stack-advisor
        description: Stack-Entscheidungs-Lab.
      - label: Decision Brief
        href: /tools/decision-brief-generator
        description: Kurzer Entscheidungsnachweis.
      - label: Pfad — Simplest Viable Stack
        href: /learning-paths/simplest-viable-stack
        description: Companion-Pfad für Stack-Einfachheit.
  - id: ms-cert-mock
    label: Practice- / Mock-Durchgang
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Thema, Score/Feeling, Lücke, Nächste Aktion
    helpText: |
      Microsoft Practice Assessments nutzen, falls verfügbar; sonst Woche-2-Lücken selbst quizen.

deliverables:
  - id: ms-cert-lab-pack
    label: Lab-Evidence-Paket
    plannedMinutes: 60
    helpText: |
      Fertig, wenn Decision-Brief/Lab-Artefakt und Mock-Notiz existieren.

notes: true
```

```sprint
id: week-04
number: 4
title: Exam-Woche und Projekt-Transfer
goal: Exam ablegen, Nachweis sichern und Skills in Delivery überführen.
dependsOn: week-03

tasks:
  - id: ms-cert-exam
    label: DP-600 / PL-300 ablegen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Exam, Datum, Ergebnis, Badge/Proof-Link
    helpText: |
      Buchungs- und Ergebnis-Links im Plan halten.
    helpLinks:
      - label: DP-600 Seite
        href: https://learn.microsoft.com/en-us/credentials/certifications/fabric-analytics-engineer-associate/
        description: Fabric Analytics Engineer Associate.
      - label: PL-300 Seite
        href: https://learn.microsoft.com/en-us/credentials/certifications/power-bi-data-analyst-associate/
        description: Power BI Data Analyst Associate.
      - label: Resources
        href: /resources
        description: Kuratierte Fabric-/Power-BI-Cert-Links.
  - id: ms-cert-transfer
    label: In das Projekt transferieren
    plannedMinutes: 90
    assigneeType: person
    assigneeId: null
    tableColumns: Learning, Projekt-Task, Owner, Due
    helpText: |
      Eine Cert-Skill in eine Delivery-Aufgabe überführen (Workspace-Rollen, Semantic Guardrails, Lakehouse-Pattern).
    helpLinks:
      - label: Pfad — Cert + Projekt parallel
        href: /learning-paths/cert-project-evidence
        description: Optionaler gemeinsamer Evidence-Track.
      - label: Governance Hub
        href: /governance
        description: Betriebsentscheidung festhalten.

deliverables:
  - id: ms-cert-done
    label: Cert-Complete-Checkliste
    plannedMinutes: 45
    helpText: |
      Fertig, wenn Exam-Proof, Lab-Paket und mindestens ein Transfer-Task verlinkt sind.

notes: true
```
