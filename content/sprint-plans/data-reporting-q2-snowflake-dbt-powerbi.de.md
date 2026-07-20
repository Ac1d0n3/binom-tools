---
type: sprint-plan
title: Zweites Quartal — Snowflake / dbt / Power BI Anschluss
slug: data-reporting-q2-snowflake-dbt-powerbi
description: Q2-Anschluss an Fivetran/Snowflake/dbt/Power BI: priorisierte App-Logik aus bestehenden Reports herausziehen, Star-Schema bauen, Delta Loads und SCD umsetzen und erste Power-BI-App-Items anbinden.
duration: 13
unit: week
recommended_people_min: 2
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: data-reporting-year-roadmap
roadmap_title: Data & Reporting Jahres-Roadmap
roadmap_track: data-reporting-snowflake-dbt-powerbi
roadmap_track_title: Snowflake / dbt / Power BI Landschaft
roadmap_phase: 2
roadmap_option: Weg 2 Snowflake / dbt / Power BI
roadmap_follows:
  - data-reporting-fq-fivetran-snowflake-powerbi
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Q2
  - Snowflake
  - dbt
  - Power BI
  - Roadmap
---

Dreizehn Wochen Q2-Umsetzung mit zwei Personen: vorhandene App-Logik aus bestehenden Power-BI-Reports, Datasets und vorgelagerten Logiken herausziehen, ein belastbares Star-Schema mit Delta Loads und Slowly Changing Dimensions bauen und den ersten nutzbaren Anschluss in die Power-BI-App liefern.

```sprint
id: week-01
number: 1
title: Bestehende App-Logik extrahieren
goal: Snowflake/dbt/Power BI: Bestehende App-Logik extrahieren.

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: extract-existing-logic
    label: Bestehende App-Logik extrahieren
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Vorhandene Logik, Kennzahlen, Filter und Sonderskripte aus bestehenden Power-BI-Reports, Datasets und vorgelagerten Logiken erfassen. Ziel ist nicht, alles zu kopieren, sondern die fachliche Logik vom Tool-Code zu trennen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: business-logic-inventory
    label: Logik-Inventar
    plannedMinutes: 1020
    dependsOn: extract-existing-logic
    helpText: |
      Artefakt fuer Logik-Inventar: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-01
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-02
number: 2
title: Star-Schema und App-Scope schneiden
goal: Snowflake/dbt/Power BI: Star-Schema und App-Scope schneiden.
dependsOn: week-01

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: define-star-scope
    label: Star-Schema und App-Scope schneiden
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Fakt, Dimensionen, Grain, wichtigste Kennzahlen und Nicht-Ziele fuer den ersten A-Z-Durchstich festlegen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: star-schema-scope
    label: Star-Schema-Schnitt
    plannedMinutes: 1080
    dependsOn: define-star-scope
    helpText: |
      Artefakt fuer Star-Schema-Schnitt: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-02
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-03
number: 3
title: Quellen auf Zielmodell mappen
goal: Snowflake/dbt/Power BI: Quellen auf Zielmodell mappen.
dependsOn: week-02

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: map-source-to-target
    label: Quellen auf Zielmodell mappen
    plannedMinutes: 2760
    assigneeType: team
    assigneeId: null
    helpText: |
      Quellen, Keys, Historie, Delta-Felder und fachliche Owner auf das Zielmodell mappen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: source-target-map
    label: Source-to-Target-Mapping
    plannedMinutes: 1080
    dependsOn: map-source-to-target
    helpText: |
      Artefakt fuer Source-to-Target-Mapping: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-03
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-04
number: 4
title: Delta Loads entwerfen
goal: Snowflake/dbt/Power BI: Delta Loads entwerfen.
dependsOn: week-03

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: design-delta-loads
    label: Delta Loads entwerfen
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Incremental-Strategie, Wasserzeichen, Reprocessing, Fehlerfaelle und Nachladelogik festlegen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
    helpLinks:
      - label: dbt Docs - Incremental models
        href: https://docs.getdbt.com/docs/build/incremental-models
        description: Nutze die Doku als Referenz fuer inkrementelle Modelle, Wasserzeichen und Reprocessing-Entscheidungen.
deliverables:
  - id: delta-load-design
    label: Delta-Load-Design
    plannedMinutes: 1140
    dependsOn: design-delta-loads
    helpText: |
      Artefakt fuer Delta-Load-Design: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-04
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-05
number: 5
title: Slowly Changing Dimensions entwerfen
goal: Snowflake/dbt/Power BI: Slowly Changing Dimensions entwerfen.
dependsOn: week-04

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: design-scd
    label: Slowly Changing Dimensions entwerfen
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      SCD-Typen, Gueltigkeit, Surrogate Keys, Historie und fachliche Ausnahmen je Dimension festlegen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
    helpLinks:
      - label: Kimball Group - Slowly Changing Dimensions
        href: https://www.kimballgroup.com/2008/09/slowly-changing-dimensions-part-2/
        description: Nutze den Artikel als Referenz fuer SCD-Typen, Historisierung und fachliche Trade-offs.
deliverables:
  - id: scd-design
    label: SCD-Design
    plannedMinutes: 1140
    dependsOn: design-scd
    helpText: |
      Artefakt fuer SCD-Design: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-05
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-06
number: 6
title: Staging- und Basis-Modelle bauen
goal: Snowflake/dbt/Power BI: Staging- und Basis-Modelle bauen.
dependsOn: week-05

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: build-staging
    label: Staging- und Basis-Modelle bauen
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Rohdaten sauber normalisieren, technische Felder standardisieren und reproduzierbare Basismodelle anlegen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: staging-models
    label: Staging-Modelle
    plannedMinutes: 1080
    dependsOn: build-staging
    helpText: |
      Artefakt fuer Staging-Modelle: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-06
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-07
number: 7
title: Star-Schema bauen
goal: Snowflake/dbt/Power BI: Star-Schema bauen.
dependsOn: week-06

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: build-star-model
    label: Star-Schema bauen
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Fakt- und Dimensionsmodelle umsetzen, Business Rules auslagern und Join-/Grain-Regeln testbar machen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: star-model
    label: Star-Schema-Modell
    plannedMinutes: 1080
    dependsOn: build-star-model
    helpText: |
      Artefakt fuer Star-Schema-Modell: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-07
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-08
number: 8
title: DQ- und Reconciliation-Tests umsetzen
goal: Snowflake/dbt/Power BI: DQ- und Reconciliation-Tests umsetzen.
dependsOn: week-07

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: implement-quality-tests
    label: DQ- und Reconciliation-Tests umsetzen
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Not-null, Unique, Relationships, Accepted Values, KPI-Sanity und Altsystem-Vergleich fuer kritische Werte umsetzen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: quality-tests
    label: DQ-Testpaket
    plannedMinutes: 1080
    dependsOn: implement-quality-tests
    helpText: |
      Artefakt fuer DQ-Testpaket: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-08
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-09
number: 9
title: Gegen alte App-Werte validieren
goal: Snowflake/dbt/Power BI: Gegen alte App-Werte validieren.
dependsOn: week-08

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: validate-against-old-app
    label: Gegen alte App-Werte validieren
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Neue Modellwerte gegen bestehende App-/QVD-/Report-Werte vergleichen und Abweichungen fachlich entscheiden.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: validation-evidence
    label: Validierungsnachweis
    plannedMinutes: 1140
    dependsOn: validate-against-old-app
    helpText: |
      Artefakt fuer Validierungsnachweis: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-09
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-10
number: 10
title: Report-App anbinden
goal: Snowflake/dbt/Power BI: Report-App anbinden.
dependsOn: week-09

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: connect-report-app
    label: Report-App anbinden
    plannedMinutes: 2760
    assigneeType: team
    assigneeId: null
    helpText: |
      Das neue Modell in die erste Power-BI-App laden, Datenverbindung, Semantik und Berechtigungen fuer den Pilotumfang herstellen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
    helpLinks:
      - label: Power-BI-App
        href: https://learn.microsoft.com/en-us/power-bi/create-reports/
        description: Nutze die Doku als Referenz fuer App-Anbindung, Semantik und erste Visuals.
deliverables:
  - id: app-connection
    label: App-Anbindung
    plannedMinutes: 1080
    dependsOn: connect-report-app
    helpText: |
      Artefakt fuer App-Anbindung: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-10
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-11
number: 11
title: Erste App-Items anlegen
goal: Snowflake/dbt/Power BI: Erste App-Items anlegen.
dependsOn: week-10

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: create-first-app-items
    label: Erste App-Items anlegen
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Erste Sheets/Visuals/Measures fuer die wichtigsten Fragen anlegen und bewusst auf den Q2-Scope begrenzen.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: first-app-items
    label: Erste App-Items
    plannedMinutes: 1020
    dependsOn: create-first-app-items
    helpText: |
      Artefakt fuer Erste App-Items: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-11
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-12
number: 12
title: Betrieb, Runbook und Ownership haerten
goal: Snowflake/dbt/Power BI: Betrieb, Runbook und Ownership haerten.
dependsOn: week-11

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: harden-runbook
    label: Betrieb, Runbook und Ownership haerten
    plannedMinutes: 2760
    assigneeType: team
    assigneeId: null
    helpText: |
      Jobs, Monitoring, DQ-Reaktion, Owner, Releaseweg und bekannte Grenzen dokumentieren.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: runbook
    label: Runbook und Ownership
    plannedMinutes: 1080
    dependsOn: harden-runbook
    helpText: |
      Artefakt fuer Runbook und Ownership: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-12
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-13
number: 13
title: Q2 abschliessen und Q3-Backlog schneiden
goal: Snowflake/dbt/Power BI: Q2 abschliessen und Q3-Backlog schneiden.
dependsOn: week-12

stories:
  - slug: building-from-scratch
    required: true
  - slug: dbt-role
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: q2-close-q3-backlog
    label: Q2 abschliessen und Q3-Backlog schneiden
    plannedMinutes: 2580
    assigneeType: team
    assigneeId: null
    helpText: |
      Ergebnis, offene Risiken, wiederverwendbare Muster und naechste App-Kandidaten fuer Q3 festhalten.
      Achte darauf, vorhandene App-Logik fachlich zu verstehen, bevor sie technisch neu gebaut wird.
    linkedStories: building-from-scratch, dq-test-kpis
deliverables:
  - id: q3-backlog
    label: Q3-Anschlussbacklog
    plannedMinutes: 1020
    dependsOn: q2-close-q3-backlog
    helpText: |
      Artefakt fuer Q3-Anschlussbacklog: mit Owner, Datum, Entscheidung, offenen Risiken und Link zum Ergebnis.

fields:
  - id: q2-notes-13
    label: Notizen und Entscheidungen
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```
