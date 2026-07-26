---
type: sprint-plan
title: Governance Lernplan & Zertifizierung (4 Wochen)
slug: governance-learning-path-certification
description: Paralleler Lernplan für Stack, Datenqualität, PII, Projektübungen und Zertifikatsnachweise.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 6
capacity_hours_per_person_week: 6
category: Learning
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Governance
  - Lernen
  - Zertifizierung
  - dbt
  - Fabric
---

Ein paralleler Lernplan für Teams, die Governance nicht nur dokumentieren, sondern im Projekt beherrschen müssen. Der Plan bleibt bewusst getrennt vom Hauptplan, ist aber über Übungen und Nachweise gekoppelt.

```sprint
id: week-01
number: 1
title: Stack-Grundlagen und gemeinsame Sprache
goal: Ziel-Stack, Governance-Begriffe, Rollen und Projektkontext verstehen.

stories:
  - slug: end-to-end-governance-architecture
    required: true

tasks:
  - id: learn-stack-foundations
    label: Stack- und Governance-Grundlagen durchgehen
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    tableColumns: Thema, Quelle, Übung, Nachweis, offene Frage
    helpText: |
      Starte mit den Begriffen, die im Projekt wirklich gebraucht werden: Workspace, Lakehouse, Mart, Semantic Layer, Catalog, Owner, PII, DSDR und Data Quality Gate.
      Notiere nicht nur Links, sondern was verstanden wurde und wo noch Unsicherheit besteht.
    helpLinks:
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder
        description: Erstellt einen rollenspezifischen Lernpfad mit Doku, Übungen und Zertifikaten.
      - label: Vendor Resources & Zertifikate
        href: /resources
        description: Sammelt offizielle Hersteller-Doku, Lernpfade und Zertifikate.

deliverables:
  - id: learning-baseline
    label: Lern-Baseline mit Rollen
    plannedMinutes: 90
    helpText: |
      Fertig, wenn Rollen, vorhandenes Wissen, wichtigste Lücken und passende Lernquellen feststehen.

fields:
  - id: role-stack
    label: Rolle und Stack
    type: textarea
    placeholder: Rolle, Stack, Vorwissen, Projektaufgabe

notes: true
```

```sprint
id: week-02
number: 2
title: KPI, Modeling und dbt Tests
goal: Lernen direkt an KPI Cards, Mart-Grain und ersten Tests verankern.
dependsOn: week-01

stories:
  - slug: define-kpi
    required: true
  - slug: metadata-driven-governance-with-dbt-meta
    required: false

tasks:
  - id: practice-kpi-and-modeling
    label: KPI Card in Modell- und Testübung übersetzen
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: KPI, Grain, Modell, Test, Dokumentation, Review
    helpText: |
      Nutze eine echte KPI aus dem Governance Workspace. Baue daraus Grain, Fact-/Dimension-Hinweise, Testidee und Dokumentationsnotiz.
      Ziel ist nicht perfekte Technik, sondern ein prüfbarer Transfer von fachlicher Definition zu Modell und Test.
    helpLinks:
      - label: KPI Requirements Intake
        href: /tools/kpi-requirements-intake?demo=finance
        description: Gefülltes Beispiel für KPI Card und Akzeptanzbeispiel.
      - label: Mart Design Brief
        href: /tools/mart-design-brief-generator?demo=finance
        description: Gefülltes Beispiel für Fact-/Dimension-Kandidaten und DQ-Gates.

deliverables:
  - id: model-test-exercise
    label: KPI Modell- und Testübung
    plannedMinutes: 120
    helpText: |
      Fertig, wenn eine KPI in Modellskizze, Testidee und Review-Frage übersetzt wurde.

notes: true
```

```sprint
id: week-03
number: 3
title: Data Quality, PII und Betrieb
goal: DQ-Gates, PII-Kontrollen, Monitoring und Betrieb an Projektbeispielen üben.
dependsOn: week-02

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: data-quality-ownership
    required: true

tasks:
  - id: practice-dq-pii
    label: DQ- und PII-Reviews üben
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Risiko, Regel, Kontrolle, Owner, Nachweis
    helpText: |
      Prüfe anhand der Demo, welche Risiken wirklich vor Build oder Release geklärt werden müssen. Übe, daraus klare Regeln und Kontrollen zu formulieren.
    helpLinks:
      - label: PII/DSDR Readiness Checker
        href: /tools/pii-dsdr-readiness-checker?demo=finance
        description: Gefülltes Beispiel für Identifier, Kopien, Retention und Zugriff.
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt konkrete DQ-Regeln für Fabric.

deliverables:
  - id: dq-pii-review-note
    label: DQ/PII Review-Nachweis
    plannedMinutes: 120
    helpText: |
      Fertig, wenn DQ-Regeln, PII-Kontrollen und offene Owner-Fragen nachvollziehbar erklärt werden können.

notes: true
```

```sprint
id: week-04
number: 4
title: Zertifikats- und Transfer-Review
goal: Zertifikatsziele, Übungen, offene Lücken und Projekttransfer abschließen.
dependsOn: week-03

stories:
  - slug: operating-and-governing-the-platform
    required: true

tasks:
  - id: certification-and-transfer
    label: Zertifikatsplan und Projekttransfer finalisieren
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Zertifikat, Thema, Übung, Termin, Nachweis, Lücke
    helpText: |
      Wähle Zertifikate passend zum Projekt, nicht als Selbstzweck. DP-600, PL-300, dbt oder Databricks ergeben Sinn, wenn sie echte Projektaufgaben absichern.
      Halte fest, welche Übung als Nachweis zählt und welche Lücke im nächsten Lernzyklus bleibt.
    helpLinks:
      - label: Vendor Learning Path Builder
        href: /tools/vendor-learning-path-builder?demo=finance
        description: Gefülltes Beispiel für rollenbezogenen Lern- und Zertifikatspfad.

deliverables:
  - id: certification-transfer-plan
    label: Zertifikats- und Transferplan
    plannedMinutes: 120
    helpText: |
      Fertig, wenn Zertifikate, Lernquellen, Übungen, Termine, Nachweise und Projektbezug sauber zusammenpassen.

notes: true
```
