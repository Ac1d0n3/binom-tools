---
type: sprint-plan
title: Data & Reporting – Erstes Quartal
slug: data-reporting-first-quarter
description: Die Daten- und Reporting-Landschaft verstehen und die erste nachhaltige Verbesserung umsetzen.
duration: 13
unit: week
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Data Platform
  - Reporting
  - Governance
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
  - Mandat & Stakeholder
  - Reporting-Landschaft
  - Quellen & Entstehung
  - Lineage & KPIs
  - Pilot & Abschluss


stories:
  - slug: data-ownership-stewardship
    required: true
  - slug: missing-pieces-ownership-stewardship
    required: false

tasks:
  - id: align-management-expectations
    label: Erwartungen mit der Führung abstimmen
    plannedMinutes: 30
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
  - id: identify-stakeholders
    label: Relevante Stakeholder identifizieren
    plannedMinutes: 30
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
      - label: Atlassian - DACI decision framework
        href: https://www.atlassian.com/team-playbook/plays/daci

deliverables:
  - id: stakeholder-list
    label: Stakeholder-Liste erstellen
    plannedMinutes: 30
    dependsOn: identify-stakeholders
    helpText: |
      Nutze die Tabelle aus der Aufgabe „Relevante Stakeholder identifizieren“ als Arbeitsbasis.
      Das Deliverable ist fertig, wenn Name, Rolle, Einfluss, Interesse und Owner für die relevanten Stakeholder gepflegt sind.
      Ergänze offene Zugänge, blockierende Personen oder fehlende operative Owner als Hinweise, statt sie in Rohnotizen zu verstecken.
  - id: initial-mandate
    label: Initialen Auftrag dokumentiert
    plannedMinutes: 60
    dependsOn: align-management-expectations, identify-stakeholders
    helpText: |
      Erstelle das Artefakt „Initialen Auftrag dokumentiert“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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

stories:
  - slug: bi-tools
    required: true
  - slug: one-app
    required: false

tasks:
  - id: inventory-reports
    label: Bestehende Reports inventarisieren
    plannedMinutes: 120
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
      - label: Microsoft Learn - Power BI implementation planning
        href: https://learn.microsoft.com/en-us/power-bi/guidance/powerbi-implementation-planning-introduction
  - id: map-report-consumers
    label: Report-Nutzer und Nutzungshäufigkeit kartieren
    plannedMinutes: 120
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
    plannedMinutes: 120
    dependsOn: inventory-reports
    helpText: |
      Erstelle das Artefakt „Report-Inventar dokumentiert“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: gap-list
    label: Erste Lückenliste erstellt
    plannedMinutes: 30
    dependsOn: inventory-reports, map-report-consumers
    helpText: |
      Erstelle das Artefakt „Erste Lückenliste erstellt“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
    plannedMinutes: 30
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
  - id: document-interfaces
    label: Schnittstellen und Extraktionswege dokumentieren
    plannedMinutes: 120
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
      - label: Atlassian - DACI decision framework
        href: https://www.atlassian.com/team-playbook/plays/daci

deliverables:
  - id: source-system-map
    label: Quellsystem-Landkarte erstellt
    plannedMinutes: 60
    dependsOn: list-source-systems
    helpText: |
      Erstelle das Artefakt „Quellsystem-Landkarte erstellt“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: owner-matrix
    label: Owner-Matrix erstellt
    plannedMinutes: 30
    dependsOn: list-source-systems, document-interfaces
    helpText: |
      Erstelle das Artefakt „Owner-Matrix erstellt“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Laufe den Geschäftsprozess nach, der die Daten hinter kritischen Reports erzeugt. Wer erfasst was, wann, mit welchem Anreiz?
      Skizziere Prozess → Systemfelder → Reportfelder für 1–3 kritische Flows.
      Achte auf: späte Excel-Korrekturen, optionale Felder die KPIs treiben, und Prozess-Ausnahmen die nie im Warehouse landen.
    linkedStories: before-building-the-first-table, trash-iinout
  - id: capture-business-rules
    label: Geschäftsregeln und Ausnahmen dokumentieren
    plannedMinutes: 30
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
    plannedMinutes: 60
    dependsOn: trace-data-creation
    helpText: |
      Erstelle das Artefakt „Notizen zur Datenerzeugung“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: rule-summary
    label: Zusammenfassung der Geschäftsregeln
    plannedMinutes: 60
    dependsOn: capture-business-rules
    helpText: |
      Erstelle das Artefakt „Zusammenfassung der Geschäftsregeln“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
    plannedMinutes: 60
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
  - id: identify-lineage-gaps
    label: Lineage-Lücken und blinde Flecken markieren
    plannedMinutes: 30
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
    plannedMinutes: 60
    dependsOn: map-lineage-paths
    helpText: |
      Erstelle das Artefakt „Lineage-Skizze erstellt“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: lineage-gap-log
    label: Lineage-Lückenprotokoll
    plannedMinutes: 60
    dependsOn: identify-lineage-gaps
    helpText: |
      Erstelle das Artefakt „Lineage-Lückenprotokoll“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
    plannedMinutes: 120
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
      - label: Tableau - Visualize Key Progress Indicators
        href: https://help.tableau.com/current/pro/desktop/en-us/kpi.htm
  - id: normalize-definitions
    label: Definitionen und Berechnungsregeln angleichen
    plannedMinutes: 60
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
    plannedMinutes: 120
    dependsOn: collect-kpis
    helpText: |
      Erstelle das Artefakt „KPI-Inventar erstellt“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: definition-backlog
    label: Definitions-Backlog priorisiert
    plannedMinutes: 60
    dependsOn: normalize-definitions
    helpText: |
      Erstelle das Artefakt „Definitions-Backlog priorisiert“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
  - label: DQ Macro Generator
    href: /tools/dbt-dq-macro-generator

tasks:
  - id: assess-dq-issues
    label: Bekannte DQ-Probleme bewerten
    plannedMinutes: 60
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
      - label: dbt Docs - Data tests
        href: https://docs.getdbt.com/docs/build/data-tests
      - label: DQ Macro Generator
        href: /tools/dbt-dq-macro-generator
  - id: rate-risks
    label: Business- und Compliance-Risiken bewerten
    plannedMinutes: 60
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
      - label: Microsoft Purview - Data classification
        href: https://learn.microsoft.com/en-us/purview/data-classification

deliverables:
  - id: dq-risk-register
    label: DQ- und Risiko-Register
    plannedMinutes: 60
    dependsOn: assess-dq-issues, rate-risks
    helpText: |
      Erstelle das Artefakt „DQ- und Risiko-Register“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: hotspot-list
    label: Priorisierte Hotspot-Liste
    plannedMinutes: 30
    dependsOn: rate-risks
    helpText: |
      Erstelle das Artefakt „Priorisierte Hotspot-Liste“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
  - Quellen
  - Integration / Plattform
  - Transformation
  - Consumption / BI


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
    plannedMinutes: 180
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
      - label: Microsoft Learn - Medallion architecture
        href: https://learn.microsoft.com/en-us/azure/databricks/lakehouse/medallion
  - id: document-bottlenecks
    label: Engpässe und technische Schulden dokumentieren
    plannedMinutes: 120
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
    plannedMinutes: 180
    dependsOn: review-architecture
    helpText: |
      Erstelle das Artefakt „Notizen zur Architekturdiagnose“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: bottleneck-list
    label: Engpass-Liste
    plannedMinutes: 30
    dependsOn: document-bottlenecks
    helpText: |
      Erstelle das Artefakt „Engpass-Liste“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
    plannedMinutes: 60
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
      - label: Atlassian - Prioritization matrix
        href: https://www.atlassian.com/work-management/project-management/prioritization-matrix
  - id: agree-priorities
    label: Prioritäten mit Stakeholdern abstimmen
    plannedMinutes: 120
    dependsOn: score-initiatives
    assigneeType: team
    assigneeId: null
    helpText: |
      Stimme Sponsoren auf den Pilot-Kandidaten und das Wartende ab. Dokumentiere Trade-offs und Entscheidungsdatum.
      Kläre, wer Menschen und Systeme in den Pilotwochen freischalten kann.
      Achte auf: stillen Widerspruch, der nach Baustart wieder auftaucht.
    linkedStories: eight-pillars, data-ownership-stewardship
deliverables:
  - id: priority-matrix
    label: Priorisierungsmatrix
    plannedMinutes: 120
    dependsOn: score-initiatives
    helpText: |
      Erstelle das Artefakt „Priorisierungsmatrix“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: quarter-backlog
    label: Quartals-Backlog vereinbart
    plannedMinutes: 180
    dependsOn: agree-priorities, priority-matrix
    helpText: |
      Erstelle das Artefakt „Quartals-Backlog vereinbart“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
    plannedMinutes: 120
    assigneeType: person
    assigneeId: null
    helpText: |
      Beschreibe den gewünschten Endzustand für diese Domäne: Ownership, KPI-Vertrauen, Lineage-Sichtbarkeit, Quality Gates und Delivery-Muster.
      Halte Prinzipien kurz und prüfbar. Zeige, was ihr stoppen werdet.
      Achte auf: futuristische Architektur-Folien ohne nahen Pfad.
    linkedStories: bridge-solution, choosing-the-simplest-viable-architecture, dbt-role, transformation-options
  - id: validate-target-picture
    label: Zielbild mit Stakeholdern validieren
    plannedMinutes: 120
    dependsOn: draft-target-picture
    assigneeType: team
    assigneeId: null
    helpText: |
      Gehe Prinzipien und den Pilot-Ausschnitt mit Stakeholdern durch. Bestätige, dass sie mit den Constraints leben können.
      Halte Einwände als Backlog fest, nicht als Freeze-Grund.
      Achte auf: Approval-Theater ohne benannte Owner für den Zielzustand.
    linkedStories: eight-pillars, data-ownership-stewardship
deliverables:
  - id: target-picture
    label: Zielbild dokumentiert
    plannedMinutes: 120
    dependsOn: draft-target-picture, validate-target-picture
    helpText: |
      Erstelle das Artefakt „Zielbild dokumentiert“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: guiding-principles
    label: Leitprinzipien definiert
    plannedMinutes: 60
    dependsOn: draft-target-picture
    helpText: |
      Erstelle das Artefakt „Leitprinzipien definiert“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
  - label: Meta Export Generator
    href: /tools/meta-export-generator
  - label: DQ Rules Generator
    href: /tools/dbt-dq-rules-generator

tasks:
  - id: build-pilot
    label: Den Piloten bauen
    plannedMinutes: 240
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
      - label: Meta Export Generator
        href: /tools/meta-export-generator
      - label: DQ Rules Generator
        href: /tools/dbt-dq-rules-generator
      - label: dbt Docs - Data tests
        href: https://docs.getdbt.com/docs/build/data-tests
  - id: track-pilot-blockers
    label: Blocker und Abhängigkeiten managen
    plannedMinutes: 60
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
    plannedMinutes: 60
    dependsOn: build-pilot
    helpText: |
      Erstelle das Artefakt „Pilot-Inkrement bereit“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: pilot-changelog
    label: Pilot-Changelog
    plannedMinutes: 60
    dependsOn: build-pilot, track-pilot-blockers
    helpText: |
      Erstelle das Artefakt „Pilot-Changelog“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
    plannedMinutes: 60
    assigneeType: person
    assigneeId: null
    helpText: |
      Review mit echten Nutzern: Können sie die Ziel-Frage schneller/sicherer beantworten? Was blockiert noch Vertrauen?
      Erfasse konkrete Änderungswünsche vs. Nice-to-haves.
      Achte auf: nur Sponsoren zu demos und Endnutzer zu überspringen.
    linkedStories: bi-tools, define-kpi
  - id: measure-pilot-outcomes
    label: Ergebnisse und Qualität messen
    plannedMinutes: 60
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
      - label: GitHub Docs - Status checks
        href: https://docs.github.com/en/pull-requests/collaborating-with-pull-requests/collaborating-on-repositories-with-code-quality-features/about-status-checks

deliverables:
  - id: validation-report
    label: Validierungsbericht
    plannedMinutes: 120
    dependsOn: run-pilot-review, measure-pilot-outcomes
    helpText: |
      Erstelle das Artefakt „Validierungsbericht“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: go-nogo-recommendation
    label: Go/No-Go-Empfehlung
    plannedMinutes: 60
    dependsOn: validation-report
    helpText: |
      Erstelle das Artefakt „Go/No-Go-Empfehlung“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

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
    plannedMinutes: 180
    assigneeType: person
    assigneeId: null
    helpText: |
      Fasse Mandat, Findings, Pilot-Ergebnis, Rest-Risiken und getroffene Entscheidungen zusammen. Lesbar für Sponsoren halten.
      Hänge Artefakte (Inventar, KPI-Defs, Lineage-Skizze, Risiko-Register) als bleibende Assets an.
      Achte auf: schlechte Nachrichten oder offene Ownership in Anhängen zu verstecken.
    linkedStories: eight-pillars, dsdr-governance
  - id: plan-next-quarter
    label: Nächstes Quartal grob planen
    plannedMinutes: 180
    dependsOn: summarize-quarter
    assigneeType: team
    assigneeId: null
    helpText: |
      Schlage die nächsten 1–3 Initiativen auf Basis verbleibender Hotspots und des validierten Zielbilds vor.
      Bestätige Owner, Kapazität und was gestoppt werden muss, um Platz zu schaffen.
      Achte auf: Discovery von null neu zu starten statt bewährte Muster zu erweitern.
    linkedStories: bridge-solution, modernizing-an-existing-warehouse, operating-and-governing-the-platform
deliverables:
  - id: quarter-report
    label: Quartalsbericht
    plannedMinutes: 120
    dependsOn: summarize-quarter
    helpText: |
      Erstelle das Artefakt „Quartalsbericht“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.
  - id: next-quarter-outline
    label: Skizze für das nächste Quartal
    plannedMinutes: 180
    dependsOn: plan-next-quarter, quarter-report
    helpText: |
      Erstelle das Artefakt „Skizze für das nächste Quartal“ mit Zweck, Owner, Datum, Quelle und offener Entscheidung.
      Das Deliverable ist fertig, wenn es eine konkrete Entscheidung oder Übergabe stützt und nicht nur Rohnotizen sammelt.

fields:
  - id: lessons-learned
    label: Lessons Learned
    type: textarea
    placeholder: Was behalten, was ändern

notes: true
```
