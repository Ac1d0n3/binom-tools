---
type: sprint-plan
title: Erstes Quartal — Fivetran → Snowflake → dbt → Qlik
slug: data-reporting-fq-fivetran-snowflake-qlik
description: Q1-Roadmap für eine Standardlandschaft mit Fivetran, Snowflake, dbt und Qlik: Daten- und Reporting-Landschaft verstehen, Risiken klären, wichtigste App festlegen und den A-Z-Prototyp für Q2 sauber vorbereiten.
duration: 13
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: data-reporting-year-roadmap
roadmap_title: Data & Reporting Jahres-Roadmap
roadmap_track: data-reporting-snowflake-dbt-qlik
roadmap_track_title: Snowflake / dbt / Qlik Landschaft
roadmap_phase: 1
roadmap_option: Weg 1 Snowflake / dbt / Qlik
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Fivetran
  - Snowflake
  - dbt
  - Qlik
---

Dreizehn Wochen, um die Standardlandschaft mit Fivetran, Snowflake, dbt und Qlik so zu verstehen, dass am Quartalsende eine belastbare App-Entscheidung, ein geschnittener A-Z-Prototyp und ein realistisches Q2-Umsetzungsbacklog vorliegen.

```sprint
id: week-01
number: 1
title: Orientierung und Mandat
goal: Auftrag, Erwartungen und relevante Stakeholder verstehen.
flowVariant: linear
flowLayout: vertical
flowSteps:
  - Quelle
  - Fivetran
  - Snowflake
  - dbt
  - Qlik



stories:
  - slug: data-ownership-stewardship
    required: true
  - slug: missing-pieces-ownership-stewardship
    required: false

tasks:
  - id: align-management-expectations
    label: Erwartungen mit der Führung abstimmen
    plannedMinutes: 540
    assigneeType: person
    assigneeId: null
    helpText: |
      Kläre, warum dieses Quartal existiert: Welche Entscheidungen sollen besser werden, welcher Schmerz ist inakzeptabel, und was heißt „gut genug“ nach 13 Wochen?
      Halte Erfolgskriterien schriftlich fest (nicht nur in Meetings). Frage explizit, was außerhalb des Scopes liegt.
      Achte auf: vage Ziele („besseres Reporting“), versteckten Compliance-Druck und widersprüchliche Sponsoren.
    linkedStories: data-ownership-stewardship, eight-pillars
    helpLinks:
      - label: Atlassian - Project poster
        href: https://www.atlassian.com/team-playbook/plays/project-poster
        description: Lies die Vorlage als Denkrahmen für Auftrag, Problem, Ziel, Stakeholder und offene Entscheidungen; nichts installieren.
  - id: identify-stakeholders
    label: Relevante Stakeholder identifizieren
    plannedMinutes: 540
    assigneeType: team
    assigneeId: null
    tableColumns: Name, Rolle, Einfluss, Interesse, Owner
    helpText: |
      Mappe Entscheider, Report-Nutzer, Datenproduzenten und Plattform-Verantwortliche. Lieber namentlich als nur Abteilungen.
      Notiere Einfluss, Interesse und wer Zugänge zu Systemen oder Definitionen blockieren kann.
      Achte auf: „alle“ als Stakeholder-Liste und fehlende operative Owner für Quelldaten.
    linkedStories: data-ownership-stewardship, missing-pieces-ownership-stewardship
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Nutze das Tool, um Personen, Rollen, Einfluss, Interesse und Owner direkt als Stakeholder-Tabelle zu strukturieren.
      - label: Atlassian - DACI decision framework
        href: https://www.atlassian.com/team-playbook/plays/daci
        description: Nutze den Artikel, um Entscheider, Beitragende und informierte Personen für Entscheidungen klar zu trennen.

deliverables:
  - id: stakeholder-list
    label: Stakeholder-Liste erstellen
    plannedMinutes: 360
    dependsOn: identify-stakeholders
    helpText: |
      Deliverable „Stakeholder-Liste erstellen“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: initial-mandate
    label: Initialen Auftrag dokumentiert
    plannedMinutes: 420
    dependsOn: align-management-expectations
    helpText: |
      Deliverable „Initialen Auftrag dokumentiert“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: management-expectations
    label: Erwartungen der Führung
    type: textarea
    placeholder: Ziele, Erwartungen und Erfolgskriterien
  - id: primary-owner
    label: Hauptverantwortlicher
    type: text
    placeholder: Name oder Rolle

notes: true
```

```sprint
id: week-02
number: 2
title: Reporting-Landschaft
goal: Bestehende Reports, Nutzer und kritische Lücken erfassen.
dependsOn: week-01
description: Fokus: Connector-Inventar, Snowflake-Rollen/Layers, dbt Tests/Docs, Qlik als Consumer — nicht als KPI-Owner.

stories:
  - slug: bi-tools
    required: true
  - slug: one-app
    required: false

tasks:
  - id: inventory-reports
    label: Bestehende Reports inventarisieren
    plannedMinutes: 840
    assigneeType: person
    assigneeId: null
    tableColumns: Report, Owner, Tool, Rhythmus, Geschäftsfrage
    helpText: |
      Liste aktive Reports/Dashboards mit Owner, Tool, Aktualisierungsrhythmus und beantworteter Geschäftsfrage.
      Markiere kritisch vs. selten genutzt. Lieber Belege (Nutzungslogs, Interviews) als Annahmen.
      Achte auf: Schatten-Excel-Reports, doppelte KPIs unter anderen Namen und verwaiste Dashboards.
    linkedStories: bi-tools, one-app
    helpLinks:
      - label: Report Inventory Canvas
        href: /tools/report-inventory
        description: Nutze das Tool, um Reports mit Owner, Tool, Rhythmus und Geschäftsfrage einheitlich zu inventarisieren.
      - label: Microsoft Learn - Power BI implementation planning
        href: https://learn.microsoft.com/en-us/power-bi/guidance/powerbi-implementation-planning-introduction
        description: Nutze die Guidance als Checkliste für Power-BI-Rollen, Arbeitsbereiche, Governance und Rollout-Fragen.
  - id: map-report-consumers
    label: Report-Nutzer und Nutzungshäufigkeit kartieren
    plannedMinutes: 600
    assigneeType: team
    assigneeId: null
    helpText: |
      Dokumentiere für kritische Reports: wer nutzt sie, für welche Entscheidung, wie oft.
      Trenne „nice to have“ von „blockiert Betrieb oder Board-Reporting“.
      Achte auf: Nutzer, die den Report nie öffnen aber Änderungen blockieren, und unbekannte Verteilerlisten.
    linkedStories: bi-tools, one-app

deliverables:
  - id: report-inventory
    label: Report-Inventar dokumentiert
    plannedMinutes: 480
    dependsOn: inventory-reports
    helpText: |
      Deliverable „Report-Inventar dokumentiert“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: gap-list
    label: Erste Lückenliste erstellt
    plannedMinutes: 180
    dependsOn: inventory-reports, map-report-consumers
    helpText: |
      Deliverable „Erste Lückenliste erstellt“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: critical-reports
    label: Kritische Reports
    type: textarea
    placeholder: Reports mit hohem Business-Impact

notes: true
```

```sprint
id: week-03
number: 3
title: Quellsysteme
goal: Relevante Quellsysteme, Owner und Schnittstellen verstehen.
dependsOn: week-02

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: sap-overview
    required: false

tasks:
  - id: list-source-systems
    label: Quellsysteme und Owner erfassen
    plannedMinutes: 600
    assigneeType: person
    assigneeId: null
    tableColumns: Source, Owner, Access, Consumed by
    helpText: |
      Inventarisiere Systeme, die Reporting speisen: ERP, CRM, Finance, Dateien, APIs. Erfasse System-Owner und ggf. Data Steward.
      Priorisiere nach Relevanz für die bereits inventarisierten Reports.
      Achte auf: inoffizielle Datenbanken, Shared Drives als „System of Record“ und unklare SAP- vs. Satelliten-Ownership.
    linkedStories: before-building-the-first-table, sap-overview
    helpLinks:
      - label: Meta Export Generator
        href: /tools/meta-export-generator
        description: Nutze das Tool, um Metadaten aus Quellen, Feldern und Ownern als wiederverwendbaren Export vorzubereiten.
  - id: document-interfaces
    label: Schnittstellen und Extraktionswege dokumentieren
    plannedMinutes: 720
    dependsOn: list-source-systems
    assigneeType: team
    assigneeId: null
    helpText: |
      Dokumentiere für Prioritätsquellen, wie Daten das System verlassen (API, CDC, Batch, Datei, manueller Export) und wer das betreibt.
      Notiere Latenz, Fehlermodi und ob Historie erhalten bleibt.
      Achte auf: undokumentierte Excel-Extrakte und „jemand startet freitags ein Skript“.
    linkedStories: before-building-the-first-table, building-from-scratch
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Nutze das Tool, um Personen, Rollen, Einfluss, Interesse und Owner direkt als Stakeholder-Tabelle zu strukturieren.
      - label: Atlassian - DACI decision framework
        href: https://www.atlassian.com/team-playbook/plays/daci
        description: Nutze den Artikel, um Entscheider, Beitragende und informierte Personen für Entscheidungen klar zu trennen.

deliverables:
  - id: source-system-map
    label: Quellsystem-Landkarte erstellt
    plannedMinutes: 480
    dependsOn: list-source-systems
    helpText: |
      Deliverable „Quellsystem-Landkarte erstellt“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: owner-matrix
    label: Owner-Matrix erstellt
    plannedMinutes: 240
    dependsOn: list-source-systems, document-interfaces
    helpText: |
      Deliverable „Owner-Matrix erstellt“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: system-risks
    label: Systemrisiken
    type: textarea
    placeholder: Bekannte Risiken oder Engpässe

notes: true
```

```sprint
id: week-04
number: 4
title: Datenerzeugung
goal: Wie operative Daten entstehen und welche Regeln gelten.
dependsOn: week-03

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: trash-iinout
    required: false

tasks:
  - id: trace-data-creation
    label: Datenerzeugung in Kernprozessen nachverfolgen
    plannedMinutes: 720
    assigneeType: person
    assigneeId: null
    helpText: |
      Laufe den Geschäftsprozess nach, der die Daten hinter kritischen Reports erzeugt. Wer erfasst was, wann, mit welchem Anreiz?
      Skizziere Prozess → Systemfelder → Reportfelder für 1–3 kritische Flows.
      Achte auf: späte Excel-Korrekturen, optionale Felder die KPIs treiben, und Prozess-Ausnahmen die nie im Warehouse landen.
    linkedStories: before-building-the-first-table, trash-iinout
  - id: capture-business-rules
    label: Geschäftsregeln und Ausnahmen dokumentieren
    plannedMinutes: 480
    dependsOn: trace-data-creation
    assigneeType: team
    assigneeId: null
    helpText: |
      Erfasse Berechnungs- und Zulässigkeitsregeln in Klartext, inklusive bekannter Ausnahmen und wer sie freigeben darf.
      Lieber Beispiele mit echten Randfällen als abstrakte Definitionen.
      Achte auf: „das weiß doch jeder“-Regeln, die nur in Köpfen existieren.
    linkedStories: define-kpi, missing-pieces-trusted-metrics

deliverables:
  - id: creation-notes
    label: Notizen zur Datenerzeugung
    plannedMinutes: 360
    dependsOn: trace-data-creation
    helpText: |
      Deliverable „Notizen zur Datenerzeugung“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: rule-summary
    label: Zusammenfassung der Geschäftsregeln
    plannedMinutes: 300
    dependsOn: capture-business-rules
    helpText: |
      Deliverable „Zusammenfassung der Geschäftsregeln“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: key-processes
    label: Schlüsselprozesse
    type: textarea
    placeholder: Prozesse mit größtem Einfluss auf Reporting

notes: true
```

```sprint
id: week-05
number: 5
title: End-to-End Lineage
goal: Den Weg von der Quelle zum Report nachvollziehbar machen.
dependsOn: week-03, week-04

flowVariant: linear
flowLayout: vertical
flowSteps:
  - Quellsystem
  - Extrakt / Schnittstelle
  - Transform / Modell
  - KPI / Semantik
  - Report


stories:
  - slug: metadata-catalog-lineage
    required: true
  - slug: missing-pieces-metadata-catalog-lineage
    required: false

tasks:
  - id: map-lineage-paths
    label: Zentrale Lineage-Pfade skizzieren
    plannedMinutes: 720
    assigneeType: person
    assigneeId: null
    helpText: |
      Zeichne Quelle → Staging/Transform → Semantik/KPI → Report für die Prioritätsflows. Grobe, aber durchgängige Pfade reichen.
      Benenne Systeme, Jobs und Owner wo bekannt; markiere Unbekanntes explizit.
      Achte auf: falsche Sicherheit durch Tool-Lineage, die nur einen Teilpfad abdeckt.
    linkedStories: metadata-catalog-lineage, missing-pieces-metadata-catalog-lineage
    helpLinks:
      - label: Meta Export Generator
        href: /tools/meta-export-generator
        description: Nutze das Tool, um Metadaten aus Quellen, Feldern und Ownern als wiederverwendbaren Export vorzubereiten.
  - id: identify-lineage-gaps
    label: Lineage-Lücken und blinde Flecken markieren
    plannedMinutes: 360
    dependsOn: map-lineage-paths
    assigneeType: team
    assigneeId: null
    helpText: |
      Liste Brüche: manuelle Schritte, undokumentierte Transforms, umbenannte Felder, konkurrierende Definitionen.
      Priorisiere Lücken, die Vertrauen in kritische KPIs oder compliance-relevante Daten treffen.
      Achte auf: „temporäre“ Transforms, die seit Jahren existieren.
    linkedStories: metadata-catalog-lineage, missing-pieces-metadata-catalog-lineage

deliverables:
  - id: lineage-sketch
    label: Lineage-Skizze erstellt
    plannedMinutes: 480
    dependsOn: map-lineage-paths
    helpText: |
      Deliverable „Lineage-Skizze erstellt“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: lineage-gap-log
    label: Lineage-Lückenprotokoll
    plannedMinutes: 300
    dependsOn: identify-lineage-gaps
    helpText: |
      Deliverable „Lineage-Lückenprotokoll“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: priority-flows
    label: Prioritäre Datenflüsse
    type: textarea
    placeholder: Flows für den ersten Piloten

notes: true
```

```sprint
id: week-06
number: 6
title: KPI-Inventar
goal: Wichtige KPIs, Definitionen und Ownership klären.
dependsOn: week-02, week-04

stories:
  - slug: define-kpi
    required: true
  - slug: kpi-metric-governance
    required: false
  - slug: missing-pieces-trusted-metrics
    required: false

tasks:
  - id: collect-kpis
    label: KPIs aus Reports und Stakeholder-Interviews sammeln
    plannedMinutes: 720
    assigneeType: person
    assigneeId: null
    helpText: |
      Sammle KPI-Namen wie in Reports und Gesprächen verwendet. Erfasse Formel-Kandidaten, Grain, Filter und vermutete Owner.
      Dedupliziere Synonyme früh („Umsatz“, „Revenue“, „Sales“).
      Achte auf: KPIs ohne Owner und Metriken, die nur als Dashboard-Titel existieren.
    linkedStories: define-kpi, kpi-metric-governance
    helpLinks:
      - label: KPI Definition Card
        href: /tools/kpi-definition
        description: Nutze das Tool, um KPI-Name, Formel, Grain, Filter, Owner und offene Definitionen sauber festzuhalten.
      - label: Tableau - Visualize Key Progress Indicators
        href: https://help.tableau.com/current/pro/desktop/en-us/kpi.htm
        description: Nutze die Beispiele, um KPI-Darstellung und Schwellenwerte verständlich zu gestalten.
  - id: normalize-definitions
    label: Definitionen und Berechnungsregeln angleichen
    plannedMinutes: 600
    dependsOn: collect-kpis
    assigneeType: team
    assigneeId: null
    helpText: |
      Für Top-KPIs: eine Definition, ein Owner, ein Berechnungspfad. Offene Konflikte dokumentieren statt übertünchen.
      Versioniere die Definition, wenn die alte Variante vorübergehend weiter gebraucht wird.
      Achte auf: „Mittelwert aus allen Zahlen“ als Kompromiss.
    linkedStories: define-kpi, missing-pieces-trusted-metrics

deliverables:
  - id: kpi-inventory
    label: KPI-Inventar erstellt
    plannedMinutes: 480
    dependsOn: collect-kpis
    helpText: |
      Deliverable „KPI-Inventar erstellt“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: definition-backlog
    label: Definitions-Backlog priorisiert
    plannedMinutes: 240
    dependsOn: normalize-definitions
    helpText: |
      Deliverable „Definitions-Backlog priorisiert“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: top-kpis
    label: Top-KPIs
    type: textarea
    placeholder: Die wichtigsten Kennzahlen für das Quartal

notes: true
```

```sprint
id: week-07
number: 7
title: Datenqualität und Risiken
goal: Qualitätsprobleme und Risiken priorisieren.
dependsOn: week-05, week-06

stories:
  - slug: data-quality-governance
    required: true
  - slug: missing-pieces-data-quality
    required: false
  - slug: pii-privacy-governance
    required: false

links:
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator
    description: Nutze das Tool, um Datenqualitätsregeln aus beobachteten Problemen in testbare Checks zu übersetzen.
  - label: DQ Macro Generator
    href: /tools/dbt-dq-macro-generator
    description: Nutze das Tool, um wiederverwendbare dbt-Makros für Datenqualitätsprüfungen vorzubereiten.

tasks:
  - id: assess-dq-issues
    label: Bekannte DQ-Probleme bewerten
    plannedMinutes: 600
    assigneeType: person
    assigneeId: null
    helpText: |
      Sammle bekannte Defekte: Vollständigkeit, Eindeutigkeit, Aktualität, Konsistenz über Systeme. Bewerte Impact auf kritische KPIs.
      Lieber messbare Symptome als Anekdoten. Notiere, wo Tests schon existieren vs. nur Erfahrungswissen.
      Achte auf: Kosmetik fixen, während Schlüssel und Daten weiter unzuverlässig bleiben.
    linkedStories: data-quality-governance, dq-test-kpis, missing-pieces-data-quality
    helpLinks:
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Nutze das Tool, um Datenqualitätsregeln aus beobachteten Problemen in testbare Checks zu übersetzen.
      - label: dbt Docs - Data tests
        href: https://docs.getdbt.com/docs/build/data-tests
        description: Nutze die Doku, um Datenqualitätsannahmen als dbt-Tests umsetzbar zu machen.
      - label: DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
        description: Nutze das Tool, um wiederverwendbare dbt-Makros für Datenqualitätsprüfungen vorzubereiten.
  - id: rate-risks
    label: Business- und Compliance-Risiken bewerten
    plannedMinutes: 480
    dependsOn: assess-dq-issues
    assigneeType: team
    assigneeId: null
    helpText: |
      Bewerte Risiken für Fehlentscheidungen, regulatorische Exposition und PII/Privacy. Trenne Wahrscheinlichkeit und Impact.
      Markiere Zugangs- und Aufbewahrungsprobleme, die einen sicheren Piloten blockieren.
      Achte auf: jeden Datenfehler mit gleicher Dringlichkeit zu behandeln.
    linkedStories: pii-privacy-governance, access-security-governance, eight-pillars
    helpLinks:
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung und Zugriffsregeln als Policy-Entwurf zu strukturieren.
      - label: Microsoft Purview - Data classification
        href: https://learn.microsoft.com/en-us/purview/data-classification
        description: Nutze die Doku, um PII-/Sensitivity-Klassen und Datenklassifikation sauber einzuordnen.

deliverables:
  - id: dq-risk-register
    label: DQ- und Risiko-Register
    plannedMinutes: 600
    dependsOn: assess-dq-issues, rate-risks
    helpText: |
      Deliverable „DQ- und Risiko-Register“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: hotspot-list
    label: Priorisierte Hotspot-Liste
    plannedMinutes: 240
    dependsOn: rate-risks
    helpText: |
      Deliverable „Priorisierte Hotspot-Liste“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: top-risks
    label: Top-Risiken
    type: textarea
    placeholder: Risiken mit höchster Priorität

notes: true
```

```sprint
id: week-08
number: 8
title: Architekturdiagnose
goal: Die aktuelle Architektur bewerten und Engpässe benennen.
dependsOn: week-03
flowVariant: linear
flowLayout: vertical
flowSteps:
  - Quelle
  - Fivetran
  - Snowflake
  - dbt
  - Qlik



stories:
  - slug: choosing-the-simplest-viable-architecture
    required: true
  - slug: beyond-bronze-silver-gold
    required: false
  - slug: big-five
    required: false
  - slug: platform-examples
    required: false

tasks:
  - id: review-architecture
    label: Aktuelle Architektur und Tools prüfen
    plannedMinutes: 720
    assigneeType: person
    assigneeId: null
    helpText: |
      Beschreibe den Weg von Quellen zur Nutzung: Warehouse/Lakehouse, Transforms, Semantic Layer, BI, Orchestrierung.
      Trenne bewusste Architektur von historisch gewachsenem Zufall. Vergleiche mit der einfachsten tragfähigen Form.
      Achte auf: Bronze/Silver/Gold neu erfinden, ohne Geschäftsfragen zu beantworten.
    linkedStories: choosing-the-simplest-viable-architecture, beyond-bronze-silver-gold, big-five, platform-examples
    helpLinks:
      - label: Architecture Fit Checklist
        href: /tools/architecture-fit
        description: Nutze das Tool, um aktuelle Architektur, Engpässe und Zielbild gegen pragmatische Kriterien zu bewerten.
      - label: Microsoft Learn - Medallion architecture
        href: https://learn.microsoft.com/en-us/azure/databricks/lakehouse/medallion
        description: Nutze die Erklärung als Referenz für Bronze/Silver/Gold-Zonen und prüfe, ob sie hier wirklich nötig sind.
  - id: document-bottlenecks
    label: Engpässe und technische Schulden dokumentieren
    plannedMinutes: 480
    dependsOn: review-architecture
    assigneeType: team
    assigneeId: null
    helpText: |
      Liste Engpässe: fragile Jobs, lange Laufzeiten, unklare Ownership, fehlende Tests, Tool-Wildwuchs, Hosting-Grenzen.
      Trenne „trifft den Piloten dieses Quartals“ von „strategische Schuld“.
      Achte auf: alles neu schreiben statt einen verbesserbaren Pfad zu isolieren.
    linkedStories: modernizing-an-existing-warehouse, host-vs-cloud, bridge-solution

deliverables:
  - id: architecture-notes
    label: Notizen zur Architekturdiagnose
    plannedMinutes: 480
    dependsOn: review-architecture
    helpText: |
      Deliverable „Notizen zur Architekturdiagnose“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: bottleneck-list
    label: Engpass-Liste
    plannedMinutes: 240
    dependsOn: document-bottlenecks
    helpText: |
      Deliverable „Engpass-Liste“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: architecture-findings
    label: Architektur-Findings
    type: textarea
    placeholder: Wichtigste Erkenntnisse

notes: true
```

```sprint
id: week-09
number: 9
title: Priorisierung
goal: Maßnahmen nach Wirkung und Machbarkeit priorisieren.
dependsOn: week-07, week-08

stories:
  - slug: eight-pillars
    required: true
  - slug: bridge-solution
    required: false

tasks:
  - id: score-initiatives
    label: Initiativen nach Impact und Aufwand bewerten
    plannedMinutes: 480
    assigneeType: person
    assigneeId: null
    helpText: |
      Mache aus Findings Initiativen mit grobem Impact (Vertrauen, Tempo, Risikosenkung) und Aufwand (Menschen, Systeme, Abhängigkeiten).
      Lieber ein kleiner Pilot, der ein Muster beweist, als ein großer Plattform-Rewrite.
      Achte auf: Lieblingsprojekte hoch zu scoren, weil sie technisch interessant sind.
    linkedStories: bridge-solution, choosing-the-simplest-viable-architecture
    helpLinks:
      - label: Impact–Effort Prioritizer
        href: /tools/impact-effort
        description: Nutze das Tool, um Initiativen nach Wirkung, Aufwand, Risiko und Abhängigkeiten zu priorisieren.
      - label: Atlassian - Prioritization matrix
        href: https://www.atlassian.com/work-management/project-management/prioritization-matrix
        description: Nutze die Beispiele, um Impact-vs.-Effort-Bewertungen verständlich mit Stakeholdern abzustimmen.
  - id: agree-priorities
    label: Prioritäten mit Stakeholdern abstimmen
    plannedMinutes: 600
    dependsOn: score-initiatives
    assigneeType: team
    assigneeId: null
    helpText: |
      Stimme Sponsoren auf den Pilot-Kandidaten und das Wartende ab. Dokumentiere Trade-offs und Entscheidungsdatum.
      Kläre, wer Menschen und Systeme in den Pilotwochen freischalten kann.
      Achte auf: stillen Widerspruch, der nach Baustart wieder auftaucht.
    linkedStories: eight-pillars, data-ownership-stewardship

  - id: select-primary-app
    label: Wichtigste App verbindlich festlegen
    plannedMinutes: 360
    plannedMinutes: 360
    dependsOn: agree-priorities
    assigneeType: team
    assigneeId: null
    helpText: |
      Entscheide verbindlich, welche App als erster A-Z-Prototyp gebaut wird. Begründe die Wahl mit Geschäftswert, Datenverfügbarkeit, Sponsor-Stärke und Wiederverwendbarkeit des Musters.
      Halte explizit fest, welche guten Kandidaten warten müssen und warum.
      Achte auf: mehrere Apps gleichzeitig zu starten, bevor ein Muster von Quelle bis Nutzer sauber bewiesen ist.
    linkedStories: one-app, bridge-solution, eight-pillars
deliverables:
  - id: priority-matrix
    label: Priorisierungsmatrix
    plannedMinutes: 420
    dependsOn: score-initiatives
    helpText: |
      Deliverable „Priorisierungsmatrix“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: quarter-backlog
    label: Quartals-Backlog vereinbart
    plannedMinutes: 420
    dependsOn: select-primary-app, priority-matrix
    helpText: |
      Deliverable „Quartals-Backlog vereinbart“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: pilot-candidate
    label: Pilot-Kandidat
    type: text
    placeholder: Ausgewählte Initiative für den Piloten

notes: true
```

```sprint
id: week-10
number: 10
title: Zielbild
goal: Ein klares Zielbild für Reporting und Datenplattform skizzieren.
dependsOn: week-09

stories:
  - slug: bridge-solution
    required: true
  - slug: dbt-role
    required: false
  - slug: self-hosted-data-platform
    required: false
  - slug: transformation-options
    required: false

tasks:
  - id: draft-target-picture
    label: Zielbild und Prinzipien entwerfen
    plannedMinutes: 720
    assigneeType: person
    assigneeId: null
    helpText: |
      Beschreibe den gewünschten Endzustand für diese Domäne: Ownership, KPI-Vertrauen, Lineage-Sichtbarkeit, Quality Gates und Delivery-Muster.
      Halte Prinzipien kurz und prüfbar. Zeige, was ihr stoppen werdet.
      Achte auf: futuristische Architektur-Folien ohne nahen Pfad.
    linkedStories: bridge-solution, choosing-the-simplest-viable-architecture, dbt-role, transformation-options
  - id: validate-target-picture
    label: Zielbild mit Stakeholdern validieren
    plannedMinutes: 480
    dependsOn: draft-target-picture
    assigneeType: team
    assigneeId: null
    helpText: |
      Gehe Prinzipien und den Pilot-Ausschnitt mit Stakeholdern durch. Bestätige, dass sie mit den Constraints leben können.
      Halte Einwände als Backlog fest, nicht als Freeze-Grund.
      Achte auf: Approval-Theater ohne benannte Owner für den Zielzustand.
    linkedStories: eight-pillars, data-ownership-stewardship

  - id: slice-az-prototype
    label: A-Z-Prototyp grob schneiden
    plannedMinutes: 480
    plannedMinutes: 480
    dependsOn: validate-target-picture
    assigneeType: team
    assigneeId: null
    helpText: |
      Schneide den ersten Prototyp so klein, dass er von Quelle über Modell/Regeln bis zur nutzbaren App-Endansicht durchläuft. Definiere Datenumfang, KPI-Frage, Nutzergruppe, Akzeptanzsignal und klare Nicht-Ziele.
      Plane bewusst nur den ersten belastbaren Durchstich, nicht die finale Plattform.
      Achte auf: Scope, der fachlich nach MVP klingt, technisch aber schon drei Produkte enthält.
    linkedStories: one-app, building-from-scratch, define-kpi, bridge-solution
deliverables:
  - id: target-picture
    label: Zielbild dokumentiert
    plannedMinutes: 480
    dependsOn: draft-target-picture, validate-target-picture, slice-az-prototype
    helpText: |
      Deliverable „Zielbild dokumentiert“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: guiding-principles
    label: Leitprinzipien definiert
    plannedMinutes: 240
    dependsOn: draft-target-picture
    helpText: |
      Deliverable „Leitprinzipien definiert“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: target-summary
    label: Kurzfassung Zielbild
    type: textarea
    placeholder: Kurze Beschreibung des gewünschten Endzustands

notes: true
```

```sprint
id: week-11
number: 11
title: Pilot umsetzen
goal: Den priorisierten Piloten in begrenztem Scope umsetzen.
dependsOn: week-10

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false

links:
  - label: Schema YML Editor
    href: /tools/schema-yml-editor
    description: Nutze das Tool, um dbt-Schema-YAML, Spaltenbeschreibungen und Tests für den Pilot-Umfang zu pflegen.
  - label: Meta Export Generator
    href: /tools/meta-export-generator
    description: Nutze das Tool, um Metadaten aus Quellen, Feldern und Ownern als wiederverwendbaren Export vorzubereiten.
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator
    description: Nutze das Tool, um Datenqualitätsregeln aus beobachteten Problemen in testbare Checks zu übersetzen.

tasks:
  - id: build-pilot
    label: Den Piloten bauen
    plannedMinutes: 1320
    assigneeType: person
    assigneeId: null
    helpText: |
      Liefere den vereinbarten Ausschnitt end-to-end: zuverlässiger Extract/Transform, klare Definition, grundlegende Tests und nutzbares Ergebnis für Zielnutzer.
      Scope hart halten. Abweichungen täglich dokumentieren.
      Achte auf: Scope-Ausweitung während des Baus und Tests „auf später“ zu schieben.
    linkedStories: building-from-scratch, dbt-role, dq-test-kpis
    helpLinks:
      - label: Schema YML Editor
        href: /tools/schema-yml-editor
        description: Nutze das Tool, um dbt-Schema-YAML, Spaltenbeschreibungen und Tests für den Pilot-Umfang zu pflegen.
      - label: Meta Export Generator
        href: /tools/meta-export-generator
        description: Nutze das Tool, um Metadaten aus Quellen, Feldern und Ownern als wiederverwendbaren Export vorzubereiten.
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Nutze das Tool, um Datenqualitätsregeln aus beobachteten Problemen in testbare Checks zu übersetzen.
      - label: dbt Docs - Data tests
        href: https://docs.getdbt.com/docs/build/data-tests
        description: Nutze die Doku, um Datenqualitätsannahmen als dbt-Tests umsetzbar zu machen.
  - id: track-pilot-blockers
    label: Blocker und Abhängigkeiten managen
    plannedMinutes: 240
    assigneeType: team
    assigneeId: null
    helpText: |
      Führe eine sichtbare Blocker-Liste mit Owner und benötigter Entscheidung. Eskaliere früh bei Zugang, Daten und Prioritätskonflikten.
      Schütze den Piloten vor unrelated „Quick Wins“.
      Achte auf: stilles Warten auf ungelöste Access-Tickets.
    linkedStories: access-security-governance, data-ownership-stewardship

deliverables:
  - id: pilot-increment
    label: Pilot-Inkrement bereit
    plannedMinutes: 360
    dependsOn: build-pilot
    helpText: |
      Deliverable „Pilot-Inkrement bereit“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: pilot-changelog
    label: Pilot-Changelog
    plannedMinutes: 120
    dependsOn: build-pilot, track-pilot-blockers
    helpText: |
      Deliverable „Pilot-Changelog“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: pilot-scope
    label: Pilot-Scope
    type: textarea
    placeholder: Was ist in und außerhalb des Scopes

notes: true
```

```sprint
id: week-12
number: 12
title: Pilot validieren
goal: Nutzen, Qualität und Akzeptanz des Piloten prüfen.
dependsOn: week-11

stories:
  - slug: dq-test-kpis
    required: true
  - slug: define-kpi
    required: false

tasks:
  - id: run-pilot-review
    label: Piloten mit Nutzern reviewen
    plannedMinutes: 480
    assigneeType: person
    assigneeId: null
    helpText: |
      Review mit echten Nutzern: Können sie die Ziel-Frage schneller/sicherer beantworten? Was blockiert noch Vertrauen?
      Erfasse konkrete Änderungswünsche vs. Nice-to-haves.
      Achte auf: nur Sponsoren zu demos und Endnutzer zu überspringen.
    linkedStories: bi-tools, define-kpi
  - id: measure-pilot-outcomes
    label: Ergebnisse und Qualität messen
    plannedMinutes: 480
    dependsOn: run-pilot-review
    assigneeType: team
    assigneeId: null
    helpText: |
      Messe gegen die Erfolgskriterien aus Woche 1: Frische, Fehlerrate, Reconciliation, Time-to-Insight, Nutzervertrauen.
      Gib eine klare Go- / bedingte Go- / No-Go-Empfehlung mit Belegen.
      Achte auf: Erfolg zu erklären, weil „die Pipeline läuft“.
    linkedStories: dq-test-kpis, data-quality-governance
    helpLinks:
      - label: DQ History Generator
        href: /tools/dbt-dq-history-generator
        description: Nutze das Tool, um DQ-Ergebnisse über Zeit nachvollziehbar zu machen und Validierung zu belegen.
      - label: GitHub Docs - Status checks
        href: https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/collaborating-on-repositories-with-code-quality-features/about-status-checks
        description: Nutze die Doku als Referenz für automatisierte Checks, Review-Signale und Freigabequalität.

deliverables:
  - id: validation-report
    label: Validierungsbericht
    plannedMinutes: 600
    dependsOn: run-pilot-review, measure-pilot-outcomes
    helpText: |
      Deliverable „Validierungsbericht“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: go-nogo-recommendation
    label: Go/No-Go-Empfehlung
    plannedMinutes: 300
    dependsOn: validation-report
    helpText: |
      Deliverable „Go/No-Go-Empfehlung“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: validation-notes
    label: Validierungsnotizen
    type: textarea
    placeholder: Feedback und Kennzahlen

notes: true
```

```sprint
id: week-13
number: 13
title: Quartalsabschluss
goal: Ergebnisse sichern, Lessons festhalten und das nächste Quartal vorbereiten.
dependsOn: week-12

stories:
  - slug: eight-pillars
    required: true
  - slug: dsdr-governance
    required: false
  - slug: operating-and-governing-the-platform
    required: false

tasks:
  - id: summarize-quarter
    label: Quartalsergebnisse zusammenfassen
    plannedMinutes: 480
    assigneeType: person
    assigneeId: null
    helpText: |
      Fasse Mandat, Findings, Pilot-Ergebnis, Rest-Risiken und getroffene Entscheidungen zusammen. Lesbar für Sponsoren halten.
      Hänge Artefakte (Inventar, KPI-Defs, Lineage-Skizze, Risiko-Register) als bleibende Assets an.
      Achte auf: schlechte Nachrichten oder offene Ownership in Anhängen zu verstecken.
    linkedStories: eight-pillars, dsdr-governance
  - id: plan-next-quarter
    label: Nächstes Quartal grob planen
    plannedMinutes: 240
    dependsOn: summarize-quarter
    assigneeType: team
    assigneeId: null
    helpText: |
      Schlage die nächsten 1–3 Initiativen auf Basis verbleibender Hotspots und des validierten Zielbilds vor.
      Bestätige Owner, Kapazität und was gestoppt werden muss, um Platz zu schaffen.
      Achte auf: Discovery von null neu zu starten statt bewährte Muster zu erweitern.
    linkedStories: bridge-solution, modernizing-an-existing-warehouse, operating-and-governing-the-platform

  - id: create-q2-prototype-backlog
    label: Q2-Anschlussbacklog erstellen
    plannedMinutes: 360
    plannedMinutes: 360
    dependsOn: summarize-quarter
    assigneeType: team
    assigneeId: null
    helpText: |
      Leite aus Findings, Zielbild, App-Entscheidung und Prototyp-Schnitt ein umsetzbares Q2-Backlog ab. Schneide Epics und Tasks so, dass Umsetzung, Review, Datenqualität und Abnahme sichtbar bleiben.
      Markiere Kettenaufgaben, parallele Arbeitspakete, Abhängigkeiten zu Fachbereichen und Entscheidungen, die vor Sprintstart fallen müssen.
      Achte auf: ein Wunschlisten-Backlog ohne Reihenfolge, Definition of Done oder Owner.
    linkedStories: bridge-solution, building-from-scratch, operating-and-governing-the-platform
  - id: check-prototype-readiness
    label: Technische Readiness für den Prototyp prüfen
    plannedMinutes: 360
    plannedMinutes: 360
    dependsOn: create-q2-prototype-backlog
    assigneeType: team
    assigneeId: null
    helpText: |
      Prüfe für den ersten A-Z-Prototyp Zugriff, Testdaten, Zielsystem, Entwicklungsumgebung, Review-Weg, Datenschutzpunkte und fachliche Ansprechpartner.
      Dokumentiere fehlende Freigaben als Start-Risiken für Q2 mit Owner und spätestem Entscheidungsdatum.
      Achte auf: Q2 mit einer perfekten Roadmap zu starten, aber ohne lauffähigen Zugang zur relevanten Quelle.
    linkedStories: access-security-governance, dq-test-kpis, building-from-scratch
deliverables:
  - id: quarter-report
    label: Quartalsbericht
    plannedMinutes: 480
    dependsOn: summarize-quarter
    helpText: |
      Deliverable „Quartalsbericht“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: next-quarter-outline
    label: Skizze für das nächste Quartal
    plannedMinutes: 360
    dependsOn: plan-next-quarter, create-q2-prototype-backlog, check-prototype-readiness, quarter-report
    helpText: |
      Deliverable „Skizze für das nächste Quartal“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.

fields:
  - id: lessons-learned
    label: Lessons Learned
    type: textarea
    placeholder: Was behalten, was ändern

notes: true
```
