---
type: sprint-plan
title: Datenbankmodell (4 Wochen)
slug: database-model
description: Fokussiertes DB-/Warehouse-Modell: Scope, Entitäten, Beziehungen, Naming und Review.
duration: 4
unit: week
recommended_people_min: 1
recommended_people_max: 2
capacity_hours_per_person_week: 40
category: Data Modeling
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Modeling
  - Datenbank
  - Warehouse
---

Vier Wochen von den Fragen bis zum freigegebenen Modell v1.

```sprint
id: week-01
number: 1
title: Scope & Fragen
goal: Klären, welche Fragen das Modell beantworten muss.

stories:
  - slug: before-building-the-first-table
    required: true
  - slug: choosing-the-simplest-viable-architecture
    required: false
  - slug: platform-examples
    required: false

tasks:
  - id: w1-scope
    label: Business-Fragen und Grain festhalten
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Business question, Fact grain, Decision, Out of scope, Owner
    helpText: |
      Starte mit den Fragen, nicht mit Tabellen. Liste pro Entscheidungsfrage, welches Ereignis oder welche Beobachtung spaeter eine Fact-Zeile bilden soll.
      Definiere den Grain je Fact explizit, zum Beispiel Auftrag-Position-Tag statt nur "Sales".
      Trenne operative Grains von analytischen Grains, damit das Modell nicht gleichzeitig Prozesslog und Reporting-Schicht sein will.
      Markiere Nicht-Ziele frueh, besonders Detailtiefe, Historie und Near-Realtime-Erwartungen.
    stories:
      - slug: before-building-the-first-table
        required: true
    helpLinks:
      - label: Microsoft Learn - Star schema guidance
        href: https://learn.microsoft.com/en-ie/power-bi/guidance/star-schema
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.
      - label: Snowflake - Data modeling guide
        href: https://www.snowflake.com/en/fundamentals/data-modeling/
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.
  - id: w1-sources
    label: Kandidaten-Quellen inventarisieren
    plannedMinutes: 300
    assigneeType: person
    assigneeId: null
    tableColumns: Source, Owner, Freshness, Key fields, Known issue
    helpText: |
      Inventarisiere Kandidaten-Quellen mit System, Owner, Aktualitaet, Schluesselfeldern und bekannten Qualitaetsproblemen.
      Nutze den Meta-Export Generator fuer einen schnellen Spaltenblick, aber bestaetige kritische Felder mit fachlichen Ownern.
      Pruefe, ob Quellsysteme Historie liefern oder nur aktuellen Zustand. Diese Entscheidung beeinflusst SCD, Snapshots und Auditierbarkeit.
      Dokumentiere fehlende Zugriffe und unklare Verantwortlichkeiten als Risiko, nicht als spaeteres Detail.
    helpLinks:
      - label: Meta-Export Generator
        href: /tools/meta-export-generator
        description: Nutze das Tool, um Metadaten aus Quellen, Feldern und Ownern als wiederverwendbaren Export vorzubereiten.
      - label: Architecture Fit Checklist
        href: /tools/architecture-fit
        description: Nutze das Tool, um aktuelle Architektur, Engpässe und Zielbild gegen pragmatische Kriterien zu bewerten.
      - label: Snowflake - Databases, tables, and views
        href: https://docs.snowflake.com/en/en/guides-overview-db
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.

deliverables:
  - id: w1-scope-note
    label: Scope- & Grain-Notiz
    plannedMinutes: 180
    helpText: |
      Erstelle eine Scope-Notiz mit Business-Fragen, Fact-Grain, benoetigten Quellen, Nicht-Zielen und offenen Annahmen.
      Das Deliverable ist fertig, wenn eine andere Person daraus erkennen kann, welche Tabellenklasse gebraucht wird und welche Details bewusst nicht modelliert werden.
      Verlinke Beispielreports, Quellinventar und offene Entscheidungen.

fields:
  - id: target-grain
    label: Ziel-Grain
    type: textarea
    placeholder: Fact-Grain, Dimensionen, Historienbedarf und Nicht-Ziele
  - id: source-risks
    label: Quellenrisiken
    type: textarea
    placeholder: Fehlende Zugriffe, unklare Owner, Qualitaetsprobleme, Historienluecken

notes: true
```

```sprint
id: week-02
number: 2
title: Entitäten & Beziehungen
goal: Entitäten, Keys und Beziehungen entwerfen.

tasks:
  - id: w2-entities
    label: Entitätsliste und Keys entwerfen
    plannedMinutes: 360
    assigneeType: person
    assigneeId: null
    tableColumns: Entity, Type, Grain, Primary key, Change behavior
    helpText: |
      Entwirf Entitaeten als Fact-, Dimension-, Bridge- oder Referenztabellen. Jede Entitaet braucht Grain, Schluessel und klares Aenderungsverhalten.
      Entscheide bewusst zwischen Natural Key, Surrogate Key und zusammengesetztem Schluessel.
      Notiere, welche Attribute historisiert werden muessen und welche ueberschrieben werden duerfen.
      Bevorzuge die einfachste tragfaehige Struktur: Mehr Schichten helfen nur, wenn sie Ownership, Performance oder Wiederverwendung tatsaechlich verbessern.
    stories:
      - slug: beyond-bronze-silver-gold
        required: false
      - slug: choosing-the-simplest-viable-architecture
        required: false
      - slug: platform-examples
        required: false
    helpLinks:
      - label: Architecture Fit Checklist
        href: /tools/architecture-fit
        description: Nutze das Tool, um aktuelle Architektur, Engpässe und Zielbild gegen pragmatische Kriterien zu bewerten.
      - label: Microsoft Learn - Star schema guidance
        href: https://learn.microsoft.com/en-ie/power-bi/guidance/star-schema
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.
  - id: w2-rels
    label: Beziehungen und Kardinalität mappen
    plannedMinutes: 300
    assigneeType: person
    assigneeId: null
    tableColumns: From table, To table, Cardinality, Join key, Integrity rule
    helpText: |
      Mappe Beziehungen mit Kardinalitaet, Join-Key, optionaler Beziehung und Integritaetsregel.
      Loese n:m-Beziehungen bewusst ueber Bridge-Tabellen auf und benenne deren Owner.
      Pruefe, ob Beziehungen fachlich stabil sind oder nur durch Datenzufall gerade funktionieren.
      Dokumentiere Sonderfaelle wie unbekannte Mitglieder, spaet eintreffende Daten oder optionale Dimensionen.
    helpLinks:
      - label: PostgreSQL - Primary and foreign keys
        href: https://www.postgresql.org/docs/current/ddl-constraints.html
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.

deliverables:
  - id: w2-model-draft
    label: ER-Entwurf
    plannedMinutes: 360
    helpText: |
      Erstelle ein Diagramm oder eine Tabellenliste mit Entitaeten, Keys, Kardinalitaeten, Grain und offenen Modellentscheidungen.
      Das Deliverable ist fertig, wenn Fact- und Dimensionstabellen unterscheidbar sind und jede Beziehung einen Grund hat.
      Markiere bewusst, welche Regeln spaeter technisch getestet oder als Constraint abgebildet werden sollen.

fields:
  - id: relationship-decisions
    label: Beziehungsentscheidungen
    type: textarea
    placeholder: Kardinalitaeten, Bridge-Tabellen, optionale Beziehungen, offene Regeln

notes: true
```

```sprint
id: week-03
number: 3
title: Naming & Standards
goal: Naming, Typen und Konventionen anwenden.

tasks:
  - id: w3-naming
    label: Naming-Konventionen anwenden
    plannedMinutes: 240
    assigneeType: person
    assigneeId: null
    tableColumns: Object, Current name, Proposed name, Rule, Exception
    helpText: |
      Richte Tabellen, Spalten, Boolean-Flags, Datumsfelder und technische Keys an den Plattformstandards aus.
      Namen sollen fachlich lesbar und technisch stabil sein: keine Abkuerzungen, die nur eine Person versteht.
      Dokumentiere Ausnahmen bewusst, zum Beispiel Quellsystemnamen, gesetzliche Begriffe oder etablierte KPI-Namen.
      Pruefe, ob Namen in BI-Tools, YAML-Doku und SQL gleich verstaendlich bleiben.
    helpLinks:
      - label: Snowflake - dbt projects best practices
        href: https://docs.snowflake.com/en/user-guide/data-engineering/dbt-projects-on-snowflake-best-practices
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.
  - id: w3-types
    label: Typen und Nullability bestätigen
    plannedMinutes: 300
    assigneeType: person
    assigneeId: null
    tableColumns: Column, Type, Nullable, Default, PII, Test
    helpText: |
      Bestaetige Datentyp, Nullability, Default, Einheiten und Wertebereich fuer kritische Spalten.
      Markiere PII-Kandidaten direkt und entscheide, ob sie gebraucht, maskiert, gehasht oder ausgeschlossen werden.
      Plane Tests fuer Pflichtfelder, Eindeutigkeit, gueltige Werte und Beziehungen.
      Halte fest, welche Regeln technisch erzwungen werden und welche nur dokumentierte fachliche Erwartung bleiben.
    helpLinks:
      - label: Schema YML Editor
        href: /tools/schema-yml-editor
        description: Nutze das Tool, um dbt-Schema-YAML, Spaltenbeschreibungen und Tests für den Pilot-Umfang zu pflegen.
      - label: PII Policy Generator
        href: /tools/pii-policy-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung und Zugriffsregeln als Policy-Entwurf zu strukturieren.
      - label: PostgreSQL - Constraints
        href: https://www.postgresql.org/docs/current/ddl-constraints.html
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.

deliverables:
  - id: w3-standards
    label: Naming- & Typstandards angewendet
    plannedMinutes: 180
    helpText: |
      Lege eine Checkliste oder PR-Notiz ab, die Naming, Typen, Nullability, PII-Entscheidungen und geplante Tests zusammenfasst.
      Das Deliverable ist fertig, wenn Reviewer die Konventionen nachvollziehen und strittige Ausnahmen gezielt kommentieren koennen.
      Verlinke YAML-Doku, Modell-Entwurf und offene Datenschutzentscheidungen.

fields:
  - id: standards-exceptions
    label: Standard-Ausnahmen
    type: textarea
    placeholder: Bewusste Naming-, Typ- oder Datenschutz-Ausnahmen mit Begruendung

notes: true
```

```sprint
id: week-04
number: 4
title: Review & Freeze
goal: Mit Ownern reviewen und v1 einfrieren.

tasks:
  - id: w4-review
    label: Walkthrough mit Stakeholdern
    plannedMinutes: 360
    assigneeType: person
    assigneeId: null
    tableColumns: Stakeholder, Concern, Decision, Owner, Due date
    helpText: |
      Fuehre den Walkthrough entlang der Business-Fragen, nicht entlang jeder Spalte. Zeige Grain, Entitaeten, Beziehungen und bekannte Grenzen.
      Sammle Einwaende als Entscheidungen: akzeptieren, aendern, vertagen oder aus Scope nehmen.
      Pruefe, ob Owner fuer fachliche Definitionen, Quellen und Betriebsfragen benannt sind.
      Halte Abnahme von v1 explizit fest, damit spaetere Verbesserungen nicht als unklare Maengel zurueckkommen.
    stories:
      - slug: data-ownership-stewardship
        required: false
    helpLinks:
      - label: Stakeholder & RACI Matrix
        href: /tools/stakeholder-matrix
        description: Nutze das Tool, um Personen, Rollen, Einfluss, Interesse und Owner direkt als Stakeholder-Tabelle zu strukturieren.
      - label: Atlassian - Acceptance criteria
        href: https://www.atlassian.com/work-management/project-management/acceptance-criteria
        description: Nutze die Beispiele, um klare Akzeptanzkriterien für Reports, Daten oder Änderungen zu formulieren.
  - id: w4-freeze
    label: Modell v1 einfrieren und Next Steps
    plannedMinutes: 360
    assigneeType: person
    assigneeId: null
    tableColumns: Follow-up, Reason, Owner, Priority, Target
    helpText: |
      Versioniere Modell, Diagramm, YAML-Doku und offene Entscheidungen zusammen.
      Liste Follow-ups fuer physische Umsetzung, Performance, Tests, Datenschutz und spaetere Modell-Erweiterungen.
      Trenne v1-Fixes von v2-Ideen, damit der Freeze nicht sofort wieder aufweicht.
      Notiere, wer das Modell nach der Freigabe fachlich und technisch pflegt.
    helpLinks:
      - label: Snowflake - Data modeling guide
        href: https://www.snowflake.com/en/fundamentals/data-modeling/
        description: Nutze die Seite als externe Referenz für Beispiele, Prüffragen oder Vorgehensweise zu dieser Aufgabe; nichts installieren.

deliverables:
  - id: w4-model-v1
    label: Modell v1 freigegeben
    plannedMinutes: 300
    helpText: |
      Sammle freigegebenes Modellartefakt, Review-Entscheidungen, bekannte Limits und Follow-ups an einem Ort.
      Das Deliverable ist fertig, wenn v1 gebaut werden kann, ohne die fachliche Modellentscheidung neu zu verhandeln.
      Offene Punkte brauchen Owner, Prioritaet und Ziel, sonst gehoeren sie nicht in den Freeze.

fields:
  - id: model-freeze-summary
    label: Freeze-Zusammenfassung
    type: textarea
    placeholder: Freigabe, bekannte Limits, Owner, v1-Fixes, v2-Ideen

notes: true
```
