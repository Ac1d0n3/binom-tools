---
type: sprint-plan
title: Neue App auf vorhandenem Modell mit Modellfilter bauen — Snowflake / dbt / Power BI Landschaft
slug: new-app-existing-model-filter-snowflake-dbt-powerbi
description: Operatives Template: eine neue App auf vorhandenem Datenmodell bauen, inklusive Modellfilter, Default-Zuständen, Sicherheit und Testmatrix.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: operational-template-library
roadmap_title: Operative Template-Bibliothek
roadmap_track: data-reporting-snowflake-dbt-powerbi
roadmap_track_title: Snowflake / dbt / Power BI Landschaft
roadmap_option: Neue App mit Modellfilter
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Snowflake
  - dbt
  - Power BI
  - Operational
  - new-app-existing-model-filter
---

Operatives Template: eine neue App auf vorhandenem Datenmodell bauen, inklusive Modellfilter, Default-Zuständen, Sicherheit und Testmatrix. Die Zeiten sind Richtwerte und sollten beim Start anhand Datenvolumen, Kritikalität und Teamverfügbarkeit geprüft werden.

```sprint
id: week-01
number: 1
title: Filterlogik, Zielgruppe und Risiko schneiden
goal: Snowflake/dbt/Power BI: Filterlogik, Zielgruppe und Risiko schneiden.

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: filter-scope
    label: Filterlogik, Zielgruppe und Risiko schneiden
    plannedMinutes: 2160
    assigneeType: team
    assigneeId: null
    helpText: |
      Definiere Filterzweck, Pflicht-/Default-Werte, Berechtigungen, PII-Auswirkungen und Performance-Risiken.
      Berücksichtige Snowflake/dbt/Power BI, Power-BI-App, Modell, DQ, Zugriff und Datenschutz gemeinsam.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Hilft, PII-Klassen, Maskierung und Zugriffsregeln für das konkrete Feld oder die App festzulegen.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: filter-scope-artifact
    label: Filter-Scope
    plannedMinutes: 960
    dependsOn: filter-scope
    helpText: |
      Erstelle Filter-Scope mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

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
title: Modellfilter und App-Verhalten umsetzen
goal: Snowflake/dbt/Power BI: Modellfilter und App-Verhalten umsetzen.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: implement-filter
    label: Modellfilter und App-Verhalten umsetzen
    plannedMinutes: 2460
    assigneeType: team
    assigneeId: null
    helpText: |
      Baue Filterlogik im Modell/App-Layer, Default-Zustände, leere Treffer, Drilldowns und Interaktionen.
      Berücksichtige Snowflake/dbt/Power BI, Power-BI-App, Modell, DQ, Zugriff und Datenschutz gemeinsam.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Hilft, PII-Klassen, Maskierung und Zugriffsregeln für das konkrete Feld oder die App festzulegen.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: implement-filter-artifact
    label: Filter-Implementierung
    plannedMinutes: 1020
    dependsOn: implement-filter
    helpText: |
      Erstelle Filter-Implementierung mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

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
title: Filter-Testmatrix und Security prüfen
goal: Snowflake/dbt/Power BI: Filter-Testmatrix und Security prüfen.
dependsOn: week-02

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: filter-test-matrix
    label: Filter-Testmatrix und Security prüfen
    plannedMinutes: 2340
    assigneeType: team
    assigneeId: null
    helpText: |
      Teste Kombinationen, Rollen, Row-/Column-Level-Regeln, Exporte, Caching und Performance.
      Berücksichtige Snowflake/dbt/Power BI, Power-BI-App, Modell, DQ, Zugriff und Datenschutz gemeinsam.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Hilft, PII-Klassen, Maskierung und Zugriffsregeln für das konkrete Feld oder die App festzulegen.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: filter-test-matrix-artifact
    label: Filter-Testmatrix
    plannedMinutes: 1020
    dependsOn: filter-test-matrix
    helpText: |
      Erstelle Filter-Testmatrix mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

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
title: Gefilterte App abnehmen und veröffentlichen
goal: Snowflake/dbt/Power BI: Gefilterte App abnehmen und veröffentlichen.
dependsOn: week-03

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: release-filtered-app
    label: Gefilterte App abnehmen und veröffentlichen
    plannedMinutes: 2040
    assigneeType: team
    assigneeId: null
    helpText: |
      Validiere fachliche Ergebnisse, bekannte Einschränkungen, Hilfe, Monitoring und Release-Entscheidung.
      Berücksichtige Snowflake/dbt/Power BI, Power-BI-App, Modell, DQ, Zugriff und Datenschutz gemeinsam.
      Achte auf: kleine Änderungen ohne PII, DQ, Catalog und App-Auswirkung durchzuwinken.
    linkedStories: pii-privacy-governance, access-security-governance, metadata-catalog-lineage
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Hilft, PII-Klassen, Maskierung und Zugriffsregeln für das konkrete Feld oder die App festzulegen.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Nutze es, um Quelle, Modell, Feld, KPI und App-Verwendung nachvollziehbar zu dokumentieren.
deliverables:
  - id: release-filtered-app-artifact
    label: Gefilterter App-Release
    plannedMinutes: 840
    dependsOn: release-filtered-app
    helpText: |
      Erstelle Gefilterter App-Release mit Owner, Entscheidung, Risiko, Ergebnislink und klarer Übergabe.

fields:
  - id: notes-04
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

