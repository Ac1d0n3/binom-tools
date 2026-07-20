---
type: sprint-plan
title: Drittes Quartal Parallel — Gewachsene Fabric / QVD / Qlik App-Factory & nächste Apps
slug: data-reporting-q3-parallel-app-factory-grown-fabric-qlik
description: Optionaler Q3-Parallelplan für ein zweites Team: auf Basis der gewachsenen Fabric/QVD/Qlik-Landschaft die nächsten Apps bauen. Ohne zweites Team kann dieser Plan als Q4-Anschlussplan genutzt werden.
duration: 13
unit: week
recommended_people_min: 2
recommended_people_max: 3
capacity_hours_per_person_week: 40
roadmap_family: data-reporting-year-roadmap
roadmap_title: Data & Reporting Jahres-Roadmap
roadmap_track: data-reporting-grown-fabric-qvd-qlik
roadmap_track_title: Gewachsene Fabric / QVD / Qlik Landschaft
roadmap_phase: 3
roadmap_option: Parallelplan App-Factory & nächste Apps
roadmap_follows:
  - data-reporting-q2-fabric-lakehouse-qlik
  - data-reporting-q2-dbt-qvd-qlik
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Q3
  - Parallel
  - Fabric
  - Qlik
  - dbt
  - App-Factory
  - Roadmap
---

Dieser Plan ist bewusst als Parallelplan gedacht: Wenn ein zweites Team vorhanden ist, kann es in Q3 die nächsten Apps bauen, während das erste Team Governance, PII, DSDR und Produktionsreife härtet. Ohne zweites Team wird derselbe Plan sinnvoll als Q4-Anschlussplan genutzt.

```sprint
id: week-01
number: 1
title: Nächste App-Kandidaten priorisieren
goal: Fabric/Qlik: Nächste App-Kandidaten priorisieren.

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: choose-next-apps
    label: Nächste App-Kandidaten priorisieren
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Bewerte App-Kandidaten nach Nutzen, Datenverfügbarkeit, Risiko, Wiederverwendbarkeit und Nähe zum ersten A-Z-Prototyp. Ziel ist ein realistischer Team-Schnitt, nicht eine Wunschliste.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: choose-next-apps-artifact
    label: Priorisierte App-Kandidaten
    plannedMinutes: 1140
    dependsOn: choose-next-apps
    helpText: |
      Erstelle Priorisierte App-Kandidaten so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-01
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-02
number: 2
title: App 2 Scope, KPIs und Owner schneiden
goal: Fabric/Qlik: App 2 Scope, KPIs und Owner schneiden.
dependsOn: week-01

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app2-scope-kpis
    label: App 2 Scope, KPIs und Owner schneiden
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Lege Zielgruppe, Kernfragen, KPIs, fachliche Owner, Nicht-Ziele und Abnahmekriterien für die zweite App fest. Halte den Scope klein genug für einen sauberen Durchstich.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: app2-scope-kpis-artifact
    label: App-2-Scope und KPI-Schnitt
    plannedMinutes: 1200
    dependsOn: app2-scope-kpis
    helpText: |
      Erstelle App-2-Scope und KPI-Schnitt so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-02
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-03
number: 3
title: App 2 Quellen, PII und DSDR-Risiken prüfen
goal: Fabric/Qlik: App 2 Quellen, PII und DSDR-Risiken prüfen.
dependsOn: week-02

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: eight-pillars
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: app2-sources-pii-dsdr
    label: App 2 Quellen, PII und DSDR-Risiken prüfen
    plannedMinutes: 3120
    assigneeType: team
    assigneeId: null
    helpText: |
      Prüfe Quellen, personenbezogene Felder, DSDR-Relevanz, Zugriffsbedarf, Datenqualität und bekannte Prozessbrüche vor dem Modellbau.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: pii-privacy-governance, eight-pillars, access-security-governance
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt Fabric-spezifische DQ-Regeln fuer Pflichtfelder, Business Keys, Freshness und Release-Gates.
      - label: Fabric PII Governance Pattern Generator
        href: /tools/fabric-pii-governance-pattern-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung und Zugriffsregeln direkt für die nächste App vorzubereiten.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Hilft, personenbezogene Daten, Zweckbindung, Schutzbedarf und Review-Entscheidungen sauber zu behandeln.
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Nutze besonders PII, DSDR, Zugriff und Lifecycle als Pflicht-Gates für neue Apps.
deliverables:
  - id: app2-sources-pii-dsdr-artifact
    label: App-2-Quellen- und Privacy-Check
    plannedMinutes: 1200
    dependsOn: app2-sources-pii-dsdr
    helpText: |
      Erstelle App-2-Quellen- und Privacy-Check so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-03
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-04
number: 4
title: App-Factory-Modellmuster anwenden
goal: Fabric/Qlik: App-Factory-Modellmuster anwenden.
dependsOn: week-03

stories:
  - slug: building-from-scratch
    required: true
  - slug: keeping-business-logic-outside-bi-apps
    required: false
  - slug: one-data-product-multiple-consumers
    required: false

tasks:
  - id: reuse-factory-model-pattern
    label: App-Factory-Modellmuster anwenden
    plannedMinutes: 3180
    assigneeType: team
    assigneeId: null
    helpText: |
      Übernimm das bewährte Muster aus dem ersten Prototyp: Staging, Business-Modell, KPI-Definition, Naming, Tests, Dokumentation und Verantwortlichkeit.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, keeping-business-logic-outside-bi-apps, one-data-product-multiple-consumers
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt Fabric-spezifische DQ-Regeln fuer Pflichtfelder, Business Keys, Freshness und Release-Gates.
      - label: Business-Logik außerhalb von BI-Apps
        href: /playbooks/keeping-business-logic-outside-bi-apps
        description: Hilft, neue Apps nicht wieder mit versteckter Logik in Qlik/Power BI zu bauen.
deliverables:
  - id: reuse-factory-model-pattern-artifact
    label: Wiederverwendetes Modellmuster
    plannedMinutes: 1260
    dependsOn: reuse-factory-model-pattern
    helpText: |
      Erstelle Wiederverwendetes Modellmuster so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-04
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-05
number: 5
title: App 2 Loads und Modell bauen
goal: Fabric/Qlik: App 2 Loads und Modell bauen.
dependsOn: week-04

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: build-app2-loads-model
    label: App 2 Loads und Modell bauen
    plannedMinutes: 3180
    assigneeType: team
    assigneeId: null
    helpText: |
      Baue Delta-/Incremental-Loads, Historisierung, zentrale Dimensionen/Fakten und technische Checks für App 2.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt Fabric-spezifische DQ-Regeln fuer Pflichtfelder, Business Keys, Freshness und Release-Gates.
deliverables:
  - id: build-app2-loads-model-artifact
    label: App-2-Datenmodell
    plannedMinutes: 1260
    dependsOn: build-app2-loads-model
    helpText: |
      Erstelle App-2-Datenmodell so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-05
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-06
number: 6
title: App 2 DQ und Reconciliation umsetzen
goal: Fabric/Qlik: App 2 DQ und Reconciliation umsetzen.
dependsOn: week-05

stories:
  - slug: dq-test-kpis
    required: true
  - slug: operating-and-governing-the-platform
    required: false
  - slug: metadata-catalog-lineage
    required: false

tasks:
  - id: app2-dq-reconciliation
    label: App 2 DQ und Reconciliation umsetzen
    plannedMinutes: 3120
    assigneeType: team
    assigneeId: null
    helpText: |
      Setze DQ-Regeln, Volumenprüfungen, KPI-Vergleiche, Frischechecks und fachliche Reconciliation gegen vorhandene Werte um.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: dq-test-kpis, operating-and-governing-the-platform, metadata-catalog-lineage
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt Fabric-spezifische DQ-Regeln fuer Pflichtfelder, Business Keys, Freshness und Release-Gates.
deliverables:
  - id: app2-dq-reconciliation-artifact
    label: App-2-DQ-Paket
    plannedMinutes: 1200
    dependsOn: app2-dq-reconciliation
    helpText: |
      Erstelle App-2-DQ-Paket so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-06
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-07
number: 7
title: App 2 anbinden und erste Views bauen
goal: Fabric/Qlik: App 2 anbinden und erste Views bauen.
dependsOn: week-06

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: connect-app2-report
    label: App 2 anbinden und erste Views bauen
    plannedMinutes: 3120
    assigneeType: team
    assigneeId: null
    helpText: |
      Binde das Modell an die Report-App an und erstelle die wichtigsten Views, Filter, Drilldowns und Navigationspunkte.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: connect-app2-report-artifact
    label: App-2-Report-Slice
    plannedMinutes: 1200
    dependsOn: connect-app2-report
    helpText: |
      Erstelle App-2-Report-Slice so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-07
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-08
number: 8
title: App 2 validieren und fachlich abnehmen
goal: Fabric/Qlik: App 2 validieren und fachlich abnehmen.
dependsOn: week-07

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app2-acceptance
    label: App 2 validieren und fachlich abnehmen
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Validiere KPIs, Performance, Berechtigungen, Exportverhalten und fachliche Akzeptanz. Entscheide, welche Einschränkungen bewusst bleiben.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: app2-acceptance-artifact
    label: App-2-Abnahme
    plannedMinutes: 1200
    dependsOn: app2-acceptance
    helpText: |
      Erstelle App-2-Abnahme so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-08
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-09
number: 9
title: App 3 als kleiner Wiederholungs-Slice schneiden
goal: Fabric/Qlik: App 3 als kleiner Wiederholungs-Slice schneiden.
dependsOn: week-08

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app3-scope-slice
    label: App 3 als kleiner Wiederholungs-Slice schneiden
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Schneide eine dritte App oder einen klaren Teil davon, um zu prüfen, ob die App-Factory wirklich wiederholbar ist.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: app3-scope-slice-artifact
    label: App-3-Slice
    plannedMinutes: 1200
    dependsOn: app3-scope-slice
    helpText: |
      Erstelle App-3-Slice so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-09
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-10
number: 10
title: App 3 Fundament vorbereiten
goal: Fabric/Qlik: App 3 Fundament vorbereiten.
dependsOn: week-09

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: app3-foundation
    label: App 3 Fundament vorbereiten
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Lege Quellen, Modell-Skelett, PII/DSDR-Check, DQ-Grundregeln und erste App-Struktur für App 3 an.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
deliverables:
  - id: app3-foundation-artifact
    label: App-3-Fundament
    plannedMinutes: 1140
    dependsOn: app3-foundation
    helpText: |
      Erstelle App-3-Fundament so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-10
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-11
number: 11
title: Mit Q3-Governance-Team synchronisieren
goal: Fabric/Qlik: Mit Q3-Governance-Team synchronisieren.
dependsOn: week-10

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: metadata-catalog-lineage
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: align-with-governance-team
    label: Mit Q3-Governance-Team synchronisieren
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Gleiche PII, DSDR, Access, Catalog, Lineage, Release-Gates und Runbook mit dem Governance-Plan ab. Parallel heißt nicht abgekoppelt.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: pii-privacy-governance, metadata-catalog-lineage, access-security-governance
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt Fabric-spezifische DQ-Regeln fuer Pflichtfelder, Business Keys, Freshness und Release-Gates.
      - label: Fabric PII Governance Pattern Generator
        href: /tools/fabric-pii-governance-pattern-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung und Zugriffsregeln direkt für die nächste App vorzubereiten.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Hilft, personenbezogene Daten, Zweckbindung, Schutzbedarf und Review-Entscheidungen sauber zu behandeln.
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Nutze besonders PII, DSDR, Zugriff und Lifecycle als Pflicht-Gates für neue Apps.
deliverables:
  - id: align-with-governance-team-artifact
    label: Governance-Sync-Protokoll
    plannedMinutes: 1080
    dependsOn: align-with-governance-team
    helpText: |
      Erstelle Governance-Sync-Protokoll so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-11
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-12
number: 12
title: App-Factory-Artefakte paketieren
goal: Fabric/Qlik: App-Factory-Artefakte paketieren.
dependsOn: week-11

stories:
  - slug: building-from-scratch
    required: true
  - slug: keeping-business-logic-outside-bi-apps
    required: false
  - slug: one-data-product-multiple-consumers
    required: false

tasks:
  - id: package-factory-assets
    label: App-Factory-Artefakte paketieren
    plannedMinutes: 2760
    assigneeType: team
    assigneeId: null
    helpText: |
      Paket aus Templates, Checklisten, Beispielmodellen, DQ-Regeln, Help-Texten, Runbook und Abnahmeformularen schnüren.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, keeping-business-logic-outside-bi-apps, one-data-product-multiple-consumers
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt Fabric-spezifische DQ-Regeln fuer Pflichtfelder, Business Keys, Freshness und Release-Gates.
      - label: Business-Logik außerhalb von BI-Apps
        href: /playbooks/keeping-business-logic-outside-bi-apps
        description: Hilft, neue Apps nicht wieder mit versteckter Logik in Qlik/Power BI zu bauen.
deliverables:
  - id: package-factory-assets-artifact
    label: App-Factory-Paket
    plannedMinutes: 1080
    dependsOn: package-factory-assets
    helpText: |
      Erstelle App-Factory-Paket so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-12
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

```sprint
id: week-13
number: 13
title: Q4 Skalierungsplan schneiden
goal: Fabric/Qlik: Q4 Skalierungsplan schneiden.
dependsOn: week-12

stories:
  - slug: building-from-scratch
    required: true
  - slug: define-kpi
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: q4-scale-plan
    label: Q4 Skalierungsplan schneiden
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Plane, welche Apps als nächstes kommen, welches Team sie bauen kann, welche Governance-Gates Pflicht sind und welche technischen Schulden vorher weg müssen.
      Dieser Plan darf parallel zum Q3-Governance-Plan laufen; gleiche dich bei PII, DSDR, Access, Catalog und Release-Gates aktiv mit dem Governance-Team ab.
      Decke bei der gewachsenen Landschaft beide Q2-Folgewege ab: Fabric Lakehouse/Qlik und Fabric+dbt als Ersatz fuer Qlik-Scripte.
      Achte auf: Geschwindigkeit durch Überspringen der Governance-Gates zu erkaufen.
    linkedStories: building-from-scratch, define-kpi, data-ownership-stewardship
    helpLinks:
      - label: Fabric DQ Rule Generator
        href: /tools/fabric-dq-rule-generator
        description: Erzeugt Fabric-spezifische DQ-Regeln fuer Pflichtfelder, Business Keys, Freshness und Release-Gates.
      - label: Business-Logik außerhalb von BI-Apps
        href: /playbooks/keeping-business-logic-outside-bi-apps
        description: Hilft, neue Apps nicht wieder mit versteckter Logik in Qlik/Power BI zu bauen.
deliverables:
  - id: q4-scale-plan-artifact
    label: Q4-App-Rollout-Plan
    plannedMinutes: 1020
    dependsOn: q4-scale-plan
    helpText: |
      Erstelle Q4-App-Rollout-Plan so, dass das Governance-Team es prüfen und für spätere Apps wiederverwenden kann. Halte Owner, Entscheidung, offene Risiken und Ergebnislink fest.

fields:
  - id: q3-parallel-notes-13
    label: Notizen, Entscheidungen und Übergaben
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Abstimmung mit Governance-Team

notes: true
```

