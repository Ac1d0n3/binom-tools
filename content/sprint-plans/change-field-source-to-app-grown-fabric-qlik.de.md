---
type: sprint-plan
title: Change Request — Neues Feld von Quelle bis App — Gewachsene Fabric / QVD / Qlik Landschaft
slug: change-field-source-to-app-grown-fabric-qlik
description: Operatives Template: ein neues Feld sauber von Quelle über Modell, DQ, PII, Catalog/Lineage bis in die App bringen.
duration: 2
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operative Template-Bibliothek
roadmap_track: data-reporting-grown-fabric-qvd-qlik
roadmap_track_title: Gewachsene Fabric / QVD / Qlik Landschaft
roadmap_option: CR neues Feld Quelle bis App
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Fabric
  - Qlik
  - dbt
  - Operational
  - change-field-source-to-app
---

Operatives Template: ein neues Feld sauber von Quelle über Modell, DQ, PII, Catalog/Lineage bis in die App bringen. Die Zeiten sind Richtwerte und sollten beim Start anhand Datenvolumen, Kritikalität und Teamverfügbarkeit geprüft werden.

```sprint
id: week-01
number: 1
title: Feld-Impact und Governance prüfen
goal: Fabric/Qlik: Feld-Impact und Governance prüfen.

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: field-impact
    label: Feld-Impact und Governance prüfen
    plannedMinutes: 1740
    assigneeType: team
    assigneeId: null
    helpText: |
      Kläre fachliche Bedeutung, Quelle, Datentyp, PII/DSDR, DQ-Regeln, betroffene Modelle, Reports und Owner.
      Berücksichtige sowohl vorhandene Qlik/QVD-Logik als auch den modernisierten Fabric/Fabric+dbt-Weg.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Hilft, PII-Klassen, Maskierung und Zugriffsregeln für das konkrete Feld oder die App festzulegen.
      - label: dbt DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
        description: Hilft, wiederverwendbare DQ-Prüfungen für Modell- oder Feldänderungen vorzubereiten.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: field-impact-artifact
    label: Feld-Impact-Analyse
    plannedMinutes: 780
    dependsOn: field-impact
    helpText: |
      Erstelle Feld-Impact-Analyse mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-01
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-02
number: 2
title: Feld durch Modell, Tests und App ziehen
goal: Fabric/Qlik: Feld durch Modell, Tests und App ziehen.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: field-implementation
    label: Feld durch Modell, Tests und App ziehen
    plannedMinutes: 1860
    assigneeType: team
    assigneeId: null
    helpText: |
      Implementiere Feld in Source-Mapping, Modell, Tests, Catalog/Lineage, App-View und Release-Nachweis.
      Berücksichtige sowohl vorhandene Qlik/QVD-Logik als auch den modernisierten Fabric/Fabric+dbt-Weg.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Hilft, PII-Klassen, Maskierung und Zugriffsregeln für das konkrete Feld oder die App festzulegen.
      - label: dbt DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
        description: Hilft, wiederverwendbare DQ-Prüfungen für Modell- oder Feldänderungen vorzubereiten.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: field-implementation-artifact
    label: Feld-Change-Release
    plannedMinutes: 780
    dependsOn: field-implementation
    helpText: |
      Erstelle Feld-Change-Release mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-02
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

