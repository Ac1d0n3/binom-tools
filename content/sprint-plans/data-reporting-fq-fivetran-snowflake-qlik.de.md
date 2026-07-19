---
type: sprint-plan
title: First Quarter — Fivetran → Snowflake → dbt → Qlik
slug: data-reporting-fq-fivetran-snowflake-qlik
description: Standardpfad: Quellen via Fivetran nach Snowflake, Transformation mit dbt, Consumption in Qlik.
duration: 13
unit: week
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

Dreizehn Wochen, um Reporting und Datenplattform zu verstehen, Risiken zu klären und einen ersten Piloten umzusetzen.

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
    assigneeType: person
    assigneeId: null
    helpText: |
      Kläre, warum dieses Quartal existiert: Welche Entscheidungen sollen besser werden, welcher Schmerz ist inakzeptabel, und was heißt „gut genug“ nach 13 Wochen?
      Halte Erfolgskriterien schriftlich fest (nicht nur in Meetings). Frage explizit, was außerhalb des Scopes liegt.
      Achte auf: vage Ziele („besseres Reporting“), versteckten Compliance-Druck und widersprüchliche Sponsoren.
    linkedStories: data-ownership-stewardship, eight-pillars
    helpLinks:
      - label: Data Ownership & Stewardship
        href: /playbooks/data-ownership-stewardship
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: identify-stakeholders
    label: Relevante Stakeholder identifizieren
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
      - label: Fehlende Bausteine – Ownership & Stewardship
        href: /playbooks/missing-pieces-ownership-stewardship
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: stakeholder-list
    label: Stakeholder-Liste erstellen
    helpText: |
      Deliverable „Stakeholder-Liste erstellen“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: initial-mandate
    label: Initialen Auftrag dokumentiert
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
description: Fokus: Connector-Inventar, Snowflake-Rollen/Layers, dbt Tests/Docs, Qlik als Consumer — nicht als KPI-Owner.

stories:
  - slug: bi-tools
    required: true
  - slug: one-app
    required: false

tasks:
  - id: inventory-reports
    label: Bestehende Reports inventarisieren
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
      - label: Eine Geschäftsfrage, verschiedene BI-Engines
        href: /playbooks/bi-tools
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Eine App kann nicht jede Frage beantworten
        href: /playbooks/one-app
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: map-report-consumers
    label: Report-Nutzer und Nutzungshäufigkeit kartieren
    assigneeType: team
    assigneeId: null
    helpText: |
      Dokumentiere für kritische Reports: wer nutzt sie, für welche Entscheidung, wie oft.
      Trenne „nice to have“ von „blockiert Betrieb oder Board-Reporting“.
      Achte auf: Nutzer, die den Report nie öffnen aber Änderungen blockieren, und unbekannte Verteilerlisten.
    linkedStories: bi-tools, one-app
    helpLinks:
      - label: BI-Tools im Überblick
        href: /playbooks/bi-tools
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: report-inventory
    label: Report-Inventar dokumentiert
    helpText: |
      Deliverable „Report-Inventar dokumentiert“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: gap-list
    label: Erste Lückenliste erstellt
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

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: sap-overview
    required: false

tasks:
  - id: list-source-systems
    label: Quellsysteme und Owner erfassen
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
      - label: Bevor die erste Tabelle entsteht
        href: /playbooks/before-building-the-first-table
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: SAP Data & Analytics Stack
        href: /playbooks/sap-overview
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: document-interfaces
    label: Schnittstellen und Extraktionswege dokumentieren
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
      - label: Ein Warehouse von Grund auf aufbauen
        href: /playbooks/building-from-scratch
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: source-system-map
    label: Quellsystem-Landkarte erstellt
    helpText: |
      Deliverable „Quellsystem-Landkarte erstellt“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: owner-matrix
    label: Owner-Matrix erstellt
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

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: trash-iinout
    required: false

tasks:
  - id: trace-data-creation
    label: Datenerzeugung in Kernprozessen nachverfolgen
    assigneeType: person
    assigneeId: null
    helpText: |
      Laufe den Geschäftsprozess nach, der die Daten hinter kritischen Reports erzeugt. Wer erfasst was, wann, mit welchem Anreiz?
      Skizziere Prozess → Systemfelder → Reportfelder für 1–3 kritische Flows.
      Achte auf: späte Excel-Korrekturen, optionale Felder die KPIs treiben, und Prozess-Ausnahmen die nie im Warehouse landen.
    linkedStories: before-building-the-first-table, trash-iinout
    helpLinks:
      - label: Trash In, Trash Out
        href: /playbooks/trash-iinout
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Bevor die erste Tabelle entsteht
        href: /playbooks/before-building-the-first-table
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: capture-business-rules
    label: Geschäftsregeln und Ausnahmen dokumentieren
    assigneeType: team
    assigneeId: null
    helpText: |
      Erfasse Berechnungs- und Zulässigkeitsregeln in Klartext, inklusive bekannter Ausnahmen und wer sie freigeben darf.
      Lieber Beispiele mit echten Randfällen als abstrakte Definitionen.
      Achte auf: „das weiß doch jeder“-Regeln, die nur in Köpfen existieren.
    linkedStories: define-kpi, missing-pieces-trusted-metrics
    helpLinks:
      - label: KPI-Definition, Ownership und Versionierung
        href: /playbooks/define-kpi
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: creation-notes
    label: Notizen zur Datenerzeugung
    helpText: |
      Deliverable „Notizen zur Datenerzeugung“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: rule-summary
    label: Zusammenfassung der Geschäftsregeln
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
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Fehlende Bausteine – Metadaten, Katalog & Lineage
        href: /playbooks/missing-pieces-metadata-catalog-lineage
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: identify-lineage-gaps
    label: Lineage-Lücken und blinde Flecken markieren
    assigneeType: team
    assigneeId: null
    helpText: |
      Liste Brüche: manuelle Schritte, undokumentierte Transforms, umbenannte Felder, konkurrierende Definitionen.
      Priorisiere Lücken, die Vertrauen in kritische KPIs oder compliance-relevante Daten treffen.
      Achte auf: „temporäre“ Transforms, die seit Jahren existieren.
    linkedStories: metadata-catalog-lineage, missing-pieces-metadata-catalog-lineage
    helpLinks:
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: lineage-sketch
    label: Lineage-Skizze erstellt
    helpText: |
      Deliverable „Lineage-Skizze erstellt“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: lineage-gap-log
    label: Lineage-Lückenprotokoll
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
      - label: KPI-Definition, Ownership und Versionierung
        href: /playbooks/define-kpi
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: KPI & Metric Governance
        href: /playbooks/kpi-metric-governance
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: normalize-definitions
    label: Definitionen und Berechnungsregeln angleichen
    assigneeType: team
    assigneeId: null
    helpText: |
      Für Top-KPIs: eine Definition, ein Owner, ein Berechnungspfad. Offene Konflikte dokumentieren statt übertünchen.
      Versioniere die Definition, wenn die alte Variante vorübergehend weiter gebraucht wird.
      Achte auf: „Mittelwert aus allen Zahlen“ als Kompromiss.
    linkedStories: define-kpi, missing-pieces-trusted-metrics
    helpLinks:
      - label: Vertrauenswürdige Kennzahlen (Fehlende Bausteine)
        href: /playbooks/missing-pieces-trusted-metrics
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: kpi-inventory
    label: KPI-Inventar erstellt
    helpText: |
      Deliverable „KPI-Inventar erstellt“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: definition-backlog
    label: Definitions-Backlog priorisiert
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
    assigneeType: person
    assigneeId: null
    helpText: |
      Sammle bekannte Defekte: Vollständigkeit, Eindeutigkeit, Aktualität, Konsistenz über Systeme. Bewerte Impact auf kritische KPIs.
      Lieber messbare Symptome als Anekdoten. Notiere, wo Tests schon existieren vs. nur Erfahrungswissen.
      Achte auf: Kosmetik fixen, während Schlüssel und Daten weiter unzuverlässig bleiben.
    linkedStories: data-quality-governance, dq-test-kpis, missing-pieces-data-quality
    helpLinks:
      - label: Data Quality Governance
        href: /playbooks/data-quality-governance
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Von Tests zu messbarer Datenqualität
        href: /playbooks/dq-test-kpis
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Nutze das Tool, um Datenqualitätsregeln aus beobachteten Problemen in testbare Checks zu übersetzen.
      - label: DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
        description: Nutze das Tool, um wiederverwendbare dbt-Makros für Datenqualitätsprüfungen vorzubereiten.
  - id: rate-risks
    label: Business- und Compliance-Risiken bewerten
    assigneeType: team
    assigneeId: null
    helpText: |
      Bewerte Risiken für Fehlentscheidungen, regulatorische Exposition und PII/Privacy. Trenne Wahrscheinlichkeit und Impact.
      Markiere Zugangs- und Aufbewahrungsprobleme, die einen sicheren Piloten blockieren.
      Achte auf: jeden Datenfehler mit gleicher Dringlichkeit zu behandeln.
    linkedStories: pii-privacy-governance, access-security-governance, eight-pillars
    helpLinks:
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Access & Security Governance
        href: /playbooks/access-security-governance
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung und Zugriffsregeln als Policy-Entwurf zu strukturieren.

deliverables:
  - id: dq-risk-register
    label: DQ- und Risiko-Register
    helpText: |
      Deliverable „DQ- und Risiko-Register“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: hotspot-list
    label: Priorisierte Hotspot-Liste
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
      - label: Die einfachste tragfähige Architektur
        href: /playbooks/choosing-the-simplest-viable-architecture
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Mehr als Bronze, Silver und Gold
        href: /playbooks/beyond-bronze-silver-gold
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: BIG 5 Stacks im Überblick
        href: /playbooks/big-five
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Eine Architektur – mehrere Plattformen
        href: /playbooks/platform-examples
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: document-bottlenecks
    label: Engpässe und technische Schulden dokumentieren
    assigneeType: team
    assigneeId: null
    helpText: |
      Liste Engpässe: fragile Jobs, lange Laufzeiten, unklare Ownership, fehlende Tests, Tool-Wildwuchs, Hosting-Grenzen.
      Trenne „trifft den Piloten dieses Quartals“ von „strategische Schuld“.
      Achte auf: alles neu schreiben statt einen verbesserbaren Pfad zu isolieren.
    linkedStories: modernizing-an-existing-warehouse, host-vs-cloud, bridge-solution
    helpLinks:
      - label: Ein bestehendes Warehouse modernisieren
        href: /playbooks/modernizing-an-existing-warehouse
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Cloud vs. Self-Hosted
        href: /playbooks/host-vs-cloud
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: architecture-notes
    label: Notizen zur Architekturdiagnose
    helpText: |
      Deliverable „Notizen zur Architekturdiagnose“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: bottleneck-list
    label: Engpass-Liste
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

stories:
  - slug: eight-pillars
    required: true
  - slug: bridge-solution
    required: false

tasks:
  - id: score-initiatives
    label: Initiativen nach Impact und Aufwand bewerten
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
      - label: Bridge Solutions
        href: /playbooks/bridge-solution
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Einfachste tragfähige Architektur
        href: /playbooks/choosing-the-simplest-viable-architecture
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: agree-priorities
    label: Prioritäten mit Stakeholdern abstimmen
    assigneeType: team
    assigneeId: null
    helpText: |
      Stimme Sponsoren auf den Pilot-Kandidaten und das Wartende ab. Dokumentiere Trade-offs und Entscheidungsdatum.
      Kläre, wer Menschen und Systeme in den Pilotwochen freischalten kann.
      Achte auf: stillen Widerspruch, der nach Baustart wieder auftaucht.
    linkedStories: eight-pillars, data-ownership-stewardship
    helpLinks:
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: priority-matrix
    label: Priorisierungsmatrix
    helpText: |
      Deliverable „Priorisierungsmatrix“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: quarter-backlog
    label: Quartals-Backlog vereinbart
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
    assigneeType: person
    assigneeId: null
    helpText: |
      Beschreibe den gewünschten Endzustand für diese Domäne: Ownership, KPI-Vertrauen, Lineage-Sichtbarkeit, Quality Gates und Delivery-Muster.
      Halte Prinzipien kurz und prüfbar. Zeige, was ihr stoppen werdet.
      Achte auf: futuristische Architektur-Folien ohne nahen Pfad.
    linkedStories: bridge-solution, choosing-the-simplest-viable-architecture, dbt-role, transformation-options
    helpLinks:
      - label: Bridge Solutions
        href: /playbooks/bridge-solution
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Welche Rolle dbt spielt
        href: /playbooks/dbt-role
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Self-Hosted Data Platforms
        href: /playbooks/self-hosted-data-platform
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Optionen für Datentransformationen
        href: /playbooks/transformation-options
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: validate-target-picture
    label: Zielbild mit Stakeholdern validieren
    assigneeType: team
    assigneeId: null
    helpText: |
      Gehe Prinzipien und den Pilot-Ausschnitt mit Stakeholdern durch. Bestätige, dass sie mit den Constraints leben können.
      Halte Einwände als Backlog fest, nicht als Freeze-Grund.
      Achte auf: Approval-Theater ohne benannte Owner für den Zielzustand.
    linkedStories: eight-pillars, data-ownership-stewardship
    helpLinks:
      - label: Data Ownership & Stewardship
        href: /playbooks/data-ownership-stewardship
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: target-picture
    label: Zielbild dokumentiert
    helpText: |
      Deliverable „Zielbild dokumentiert“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: guiding-principles
    label: Leitprinzipien definiert
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
    assigneeType: person
    assigneeId: null
    helpText: |
      Liefere den vereinbarten Ausschnitt end-to-end: zuverlässiger Extract/Transform, klare Definition, grundlegende Tests und nutzbares Ergebnis für Zielnutzer.
      Scope hart halten. Abweichungen täglich dokumentieren.
      Achte auf: Scope-Ausweitung während des Baus und Tests „auf später“ zu schieben.
    linkedStories: building-from-scratch, dbt-role, dq-test-kpis
    helpLinks:
      - label: Ein Warehouse von Grund auf aufbauen
        href: /playbooks/building-from-scratch
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Welche Rolle dbt spielt
        href: /playbooks/dbt-role
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Schema YML Editor
        href: /tools/schema-yml-editor
        description: Nutze das Tool, um dbt-Schema-YAML, Spaltenbeschreibungen und Tests für den Pilot-Umfang zu pflegen.
      - label: Meta Export Generator
        href: /tools/meta-export-generator
        description: Nutze das Tool, um Metadaten aus Quellen, Feldern und Ownern als wiederverwendbaren Export vorzubereiten.
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
        description: Nutze das Tool, um Datenqualitätsregeln aus beobachteten Problemen in testbare Checks zu übersetzen.
  - id: track-pilot-blockers
    label: Blocker und Abhängigkeiten managen
    assigneeType: team
    assigneeId: null
    helpText: |
      Führe eine sichtbare Blocker-Liste mit Owner und benötigter Entscheidung. Eskaliere früh bei Zugang, Daten und Prioritätskonflikten.
      Schütze den Piloten vor unrelated „Quick Wins“.
      Achte auf: stilles Warten auf ungelöste Access-Tickets.
    linkedStories: access-security-governance, data-ownership-stewardship
    helpLinks:
      - label: Access & Security Governance
        href: /playbooks/access-security-governance
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: pilot-increment
    label: Pilot-Inkrement bereit
    helpText: |
      Deliverable „Pilot-Inkrement bereit“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: pilot-changelog
    label: Pilot-Changelog
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

stories:
  - slug: dq-test-kpis
    required: true
  - slug: define-kpi
    required: false

tasks:
  - id: run-pilot-review
    label: Piloten mit Nutzern reviewen
    assigneeType: person
    assigneeId: null
    helpText: |
      Review mit echten Nutzern: Können sie die Ziel-Frage schneller/sicherer beantworten? Was blockiert noch Vertrauen?
      Erfasse konkrete Änderungswünsche vs. Nice-to-haves.
      Achte auf: nur Sponsoren zu demos und Endnutzer zu überspringen.
    linkedStories: bi-tools, define-kpi
    helpLinks:
      - label: KPI-Definition, Ownership und Versionierung
        href: /playbooks/define-kpi
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: BI-Tools im Überblick
        href: /playbooks/bi-tools
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: measure-pilot-outcomes
    label: Ergebnisse und Qualität messen
    assigneeType: team
    assigneeId: null
    helpText: |
      Messe gegen die Erfolgskriterien aus Woche 1: Frische, Fehlerrate, Reconciliation, Time-to-Insight, Nutzervertrauen.
      Gib eine klare Go- / bedingte Go- / No-Go-Empfehlung mit Belegen.
      Achte auf: Erfolg zu erklären, weil „die Pipeline läuft“.
    linkedStories: dq-test-kpis, data-quality-governance
    helpLinks:
      - label: Von Tests zu messbarer Datenqualität
        href: /playbooks/dq-test-kpis
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: DQ History Generator
        href: /tools/dbt-dq-history-generator
        description: Nutze das Tool, um DQ-Ergebnisse über Zeit nachvollziehbar zu machen und Validierung zu belegen.

deliverables:
  - id: validation-report
    label: Validierungsbericht
    helpText: |
      Deliverable „Validierungsbericht“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: go-nogo-recommendation
    label: Go/No-Go-Empfehlung
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
    assigneeType: person
    assigneeId: null
    helpText: |
      Fasse Mandat, Findings, Pilot-Ergebnis, Rest-Risiken und getroffene Entscheidungen zusammen. Lesbar für Sponsoren halten.
      Hänge Artefakte (Inventar, KPI-Defs, Lineage-Skizze, Risiko-Register) als bleibende Assets an.
      Achte auf: schlechte Nachrichten oder offene Ownership in Anhängen zu verstecken.
    linkedStories: eight-pillars, dsdr-governance
    helpLinks:
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: DSDR Governance
        href: /playbooks/dsdr-governance
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
  - id: plan-next-quarter
    label: Nächstes Quartal grob planen
    assigneeType: team
    assigneeId: null
    helpText: |
      Schlage die nächsten 1–3 Initiativen auf Basis verbleibender Hotspots und des validierten Zielbilds vor.
      Bestätige Owner, Kapazität und was gestoppt werden muss, um Platz zu schaffen.
      Achte auf: Discovery von null neu zu starten statt bewährte Muster zu erweitern.
    linkedStories: bridge-solution, modernizing-an-existing-warehouse, operating-and-governing-the-platform
    helpLinks:
      - label: Bridge Solutions
        href: /playbooks/bridge-solution
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Ein bestehendes Warehouse modernisieren
        href: /playbooks/modernizing-an-existing-warehouse
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.
      - label: Die Datenplattform betreiben und steuern
        href: /playbooks/operating-and-governing-the-platform
        description: Öffnet die passende Planner-Story als Hintergrundwissen; nutze sie zum Verstehen, nicht als Installationsschritt.

deliverables:
  - id: quarter-report
    label: Quartalsbericht
    helpText: |
      Deliverable „Quartalsbericht“: halte es kurz, nachvollziehbar und mit Owner/Datum.
      Nutze es als Beleg für Entscheidungen — nicht als Ablage für Rohnotizen.
  - id: next-quarter-outline
    label: Skizze für das nächste Quartal
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
