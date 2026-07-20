---
type: sprint-plan
title: Neues App-Datenmodell erstellen — Databricks Lakehouse / dbt / Power BI Landschaft
slug: app-data-model-databricks-dbt-powerbi
description: Operatives Template: aus einer neuen App-Idee ein belastbares Datenmodell mit Quellen, Grain, KPIs, PII/DSDR, DQ, Lineage und Übergabe an den App-Bau erstellen.
duration: 6
unit: week
recommended_people_min: 2
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operative Template-Bibliothek
roadmap_track: data-reporting-databricks-dbt-powerbi
roadmap_track_title: Databricks Lakehouse / dbt / Power BI Landschaft
roadmap_option: Neues App-Datenmodell
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Databricks
  - dbt
  - Power BI
  - Operational
  - app-data-model
---

Operatives Template: aus einer neuen App-Idee ein belastbares Datenmodell mit Quellen, Grain, KPIs, PII/DSDR, DQ, Lineage und Übergabe an den App-Bau erstellen. Die Zeiten sind Richtwerte und sollten beim Start anhand Datenvolumen, Kritikalität und Teamverfügbarkeit geprüft werden.

```sprint
id: week-01
number: 1
title: App-Ziel, Grain und KPI-Schnitt festlegen
goal: Databricks Lakehouse/dbt/Power BI: App-Ziel, Grain und KPI-Schnitt festlegen.

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: scope-model
    label: App-Ziel, Grain und KPI-Schnitt festlegen
    plannedMinutes: 2460
    assigneeType: team
    assigneeId: null
    helpText: |
      Kläre Zielgruppe, Kernfragen, Grain, KPI-Definitionen, Nicht-Ziele, Owner und Abnahmekriterien.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Erzeugt Databricks-spezifische DLT-/SQL-/Notebook-Patterns fuer DQ-Regeln, Delta MERGE, SCD2 und Unity-Catalog-Gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: scope-model-artifact
    label: Modell-Scope
    plannedMinutes: 1020
    dependsOn: scope-model
    helpText: |
      Erstelle Modell-Scope mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

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
title: Quellen, PII und DSDR prüfen
goal: Databricks Lakehouse/dbt/Power BI: Quellen, PII und DSDR prüfen.
dependsOn: week-01

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: source-privacy-check
    label: Quellen, PII und DSDR prüfen
    plannedMinutes: 2580
    assigneeType: team
    assigneeId: null
    helpText: |
      Prüfe Quellen, Keys, personenbezogene Daten, DSDR-Relevanz, Zugriff und bekannte DQ-Probleme vor dem Modellbau.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Erzeugt Databricks-spezifische DLT-/SQL-/Notebook-Patterns fuer DQ-Regeln, Delta MERGE, SCD2 und Unity-Catalog-Gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: source-privacy-check-artifact
    label: Quellen- und Privacy-Check
    plannedMinutes: 1140
    dependsOn: source-privacy-check
    helpText: |
      Erstelle Quellen- und Privacy-Check mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

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
title: Fakten, Dimensionen und Historie entwerfen
goal: Databricks Lakehouse/dbt/Power BI: Fakten, Dimensionen und Historie entwerfen.
dependsOn: week-02

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: design-model
    label: Fakten, Dimensionen und Historie entwerfen
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Entwirf Fakt, Dimensionen, Historisierung, Delta-Strategie, SCD-Typen und fachliche Sonderfälle.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Erzeugt Databricks-spezifische DLT-/SQL-/Notebook-Patterns fuer DQ-Regeln, Delta MERGE, SCD2 und Unity-Catalog-Gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: design-model-artifact
    label: Modell-Design
    plannedMinutes: 1140
    dependsOn: design-model
    helpText: |
      Erstelle Modell-Design mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-03
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-04
number: 4
title: Staging und Business-Modell bauen
goal: Databricks Lakehouse/dbt/Power BI: Staging und Business-Modell bauen.
dependsOn: week-03

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: build-model
    label: Staging und Business-Modell bauen
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Baue Staging, Business-Modelle, zentrale KPIs und technische Prüfungen nach Landschaftsmuster.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Erzeugt Databricks-spezifische DLT-/SQL-/Notebook-Patterns fuer DQ-Regeln, Delta MERGE, SCD2 und Unity-Catalog-Gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: build-model-artifact
    label: Implementiertes App-Modell
    plannedMinutes: 1140
    dependsOn: build-model
    helpText: |
      Erstelle Implementiertes App-Modell mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-04
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-05
number: 5
title: DQ, Catalog und Lineage ergänzen
goal: Databricks Lakehouse/dbt/Power BI: DQ, Catalog und Lineage ergänzen.
dependsOn: week-04

stories:
  - slug: dq-test-kpis
    required: true
  - slug: metadata-catalog-lineage
    required: false
  - slug: operating-and-governing-the-platform
    required: false

tasks:
  - id: dq-lineage-catalog
    label: DQ, Catalog und Lineage ergänzen
    plannedMinutes: 2520
    assigneeType: team
    assigneeId: null
    helpText: |
      Ergänze DQ-Regeln, KPI-Doku, Owner, Lineage, PII-Klassen und bekannte Einschränkungen.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: dq-test-kpis, metadata-catalog-lineage, operating-and-governing-the-platform
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Erzeugt Databricks-spezifische DLT-/SQL-/Notebook-Patterns fuer DQ-Regeln, Delta MERGE, SCD2 und Unity-Catalog-Gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: dq-lineage-catalog-artifact
    label: DQ- und Catalog-Paket
    plannedMinutes: 1080
    dependsOn: dq-lineage-catalog
    helpText: |
      Erstelle DQ- und Catalog-Paket mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-05
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-06
number: 6
title: Modell validieren und an App-Bau übergeben
goal: Databricks Lakehouse/dbt/Power BI: Modell validieren und an App-Bau übergeben.
dependsOn: week-05

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: handover-app-build
    label: Modell validieren und an App-Bau übergeben
    plannedMinutes: 2280
    assigneeType: team
    assigneeId: null
    helpText: |
      Validiere Zahlen, Performance, Zugriff und Übergabe an App-Bau inklusive offener Risiken.
      Berücksichtige Databricks Lakehouse, Unity Catalog, Delta-Tabellen, Jobs und dbt-Modellgrenzen.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Pattern Generator
        href: /tools/databricks-dq-pattern-generator
        description: Erzeugt Databricks-spezifische DLT-/SQL-/Notebook-Patterns fuer DQ-Regeln, Delta MERGE, SCD2 und Unity-Catalog-Gates.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: handover-app-build-artifact
    label: App-Bau-Übergabe
    plannedMinutes: 960
    dependsOn: handover-app-build
    helpText: |
      Erstelle App-Bau-Übergabe mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-06
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

