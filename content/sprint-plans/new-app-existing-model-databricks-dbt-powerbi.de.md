---
type: sprint-plan
title: Neue App auf vorhandenem Modell bauen — Databricks Lakehouse / dbt / Power BI Landschaft
slug: new-app-existing-model-databricks-dbt-powerbi
description: Operatives Template: eine neue App auf einem vorhandenen Datenmodell bauen, ohne Modellfilter einzuführen.
duration: 3
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operative Template-Bibliothek
roadmap_track: data-reporting-databricks-dbt-powerbi
roadmap_track_title: Databricks Lakehouse / dbt / Power BI Landschaft
roadmap_option: Neue App ohne Modellfilter
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Databricks
  - dbt
  - Power BI
  - Operational
  - new-app-existing-model
---

Operatives Template: eine neue App auf einem vorhandenen Datenmodell bauen, ohne Modellfilter einzuführen. Die Zeiten sind Richtwerte und sollten beim Start anhand Datenvolumen, Kritikalität und Teamverfügbarkeit geprüft werden.

```sprint
id: week-01
number: 1
title: App-Scope und UX-Schnitt festlegen
goal: Databricks Lakehouse/dbt/Power BI: App-Scope und UX-Schnitt festlegen.

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app-scope
    label: App-Scope und UX-Schnitt festlegen
    plannedMinutes: 2100
    assigneeType: team
    assigneeId: null
    helpText: |
      Lege Zielgruppe, Kernfragen, Seiten, KPIs, Navigationslogik und Abnahme fest.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: app-scope-artifact
    label: App-Scope
    plannedMinutes: 900
    dependsOn: app-scope
    helpText: |
      Erstelle App-Scope mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

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
title: App-Views und Interaktionen bauen
goal: Databricks Lakehouse/dbt/Power BI: App-Views und Interaktionen bauen.
dependsOn: week-01

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: build-app
    label: App-Views und Interaktionen bauen
    plannedMinutes: 2280
    assigneeType: team
    assigneeId: null
    helpText: |
      Baue Views, Filter im BI-Tool, Navigation, KPIs, Detailpfade und Lade-/Refresh-Verhalten.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: build-app-artifact
    label: App-Slice
    plannedMinutes: 960
    dependsOn: build-app
    helpText: |
      Erstelle App-Slice mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-02
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-03
number: 3
title: Validieren, absichern und veröffentlichen
goal: Databricks Lakehouse/dbt/Power BI: Validieren, absichern und veröffentlichen.
dependsOn: week-02

stories:
  - slug: dq-test-kpis
    required: true
  - slug: metadata-catalog-lineage
    required: false
  - slug: operating-and-governing-the-platform
    required: false

tasks:
  - id: validate-release
    label: Validieren, absichern und veröffentlichen
    plannedMinutes: 2040
    assigneeType: team
    assigneeId: null
    helpText: |
      Prüfe Zahlen, Berechtigungen, Performance, Exporte, Hilfe und Release-Entscheidung.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: dq-test-kpis, metadata-catalog-lineage, operating-and-governing-the-platform
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: validate-release-artifact
    label: App-Release-Nachweis
    plannedMinutes: 840
    dependsOn: validate-release
    helpText: |
      Erstelle App-Release-Nachweis mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-03
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

