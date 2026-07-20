---
type: sprint-plan
title: Drittes Quartal — Databricks Lakehouse / dbt / Power BI Governance & App-Factory
slug: data-reporting-q3-databricks-dbt-powerbi
description: Q3-Anschluss an Databricks Lakehouse/dbt/Power BI: den ersten A-Z-Prototyp produktionsreif machen, PII und DSDR sauber verankern, Catalog/Lineage veröffentlichen und die App-Factory für weitere Power-BI-Apps vorbereiten.
duration: 13
unit: week
recommended_people_min: 2
recommended_people_max: 2
capacity_hours_per_person_week: 40
roadmap_family: data-reporting-year-roadmap
roadmap_title: Data & Reporting Jahres-Roadmap
roadmap_track: data-reporting-databricks-dbt-powerbi
roadmap_track_title: Databricks Lakehouse / dbt / Power BI Landschaft
roadmap_phase: 3
roadmap_option: Weg 4 Databricks Lakehouse / dbt / Power BI
roadmap_follows:
  - data-reporting-q2-databricks-dbt-powerbi
category: Data Platform
author: Thomas Lindackers
version: 1
locale: de
tags:
  - Q3
  - Databricks
  - Lakehouse
  - Delta
  - Unity Catalog
  - dbt
  - Power BI
  - PII
  - DSDR
  - Roadmap
---

Dreizehn Wochen Q3 mit zwei Personen: aus dem ersten Databricks Lakehouse/dbt/Power-BI-Durchstich wird ein produktionsnaher, governance-faehiger Daten- und Reporting-Baustein. PII, DSDR, Zugriff, Lineage, Betrieb und App-Factory werden als echte Liefergegenstaende umgesetzt.

```sprint
id: week-01
number: 1
title: Q2-Ergebnisse übernehmen und Produktionslücken schließen
goal: Databricks Lakehouse/dbt/Power BI: Q2-Ergebnisse übernehmen und Produktionslücken schließen.

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: q2-handover-production-gaps
    label: Q2-Ergebnisse übernehmen und Produktionslücken schließen
    plannedMinutes: 2760
    assigneeType: team
    assigneeId: null
    helpText: |
      Q2-Ergebnis, offene Betriebsrisiken, Testlücken, Datenschutzpunkte und fehlende Owner prüfen. Ziel ist ein realistischer Produktionsreife-Backlog statt ein schöner Abschlussbericht.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, access-security-governance
deliverables:
  - id: q2-handover-production-gaps-artifact
    label: Produktionslücken-Register
    plannedMinutes: 1080
    dependsOn: q2-handover-production-gaps
    helpText: |
      Erstelle das Artefakt fuer Produktionslücken-Register mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-01
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-02
number: 2
title: PII- und Sensitivitätsklassifikation durchführen
goal: Databricks Lakehouse/dbt/Power BI: PII- und Sensitivitätsklassifikation durchführen.
dependsOn: week-01

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: eight-pillars
    required: false

tasks:
  - id: pii-classification
    label: PII- und Sensitivitätsklassifikation durchführen
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Alle relevanten Felder, Tabellen, Modelle und App-Ausgaben nach PII, Sensitivität, Zweckbindung und Export-/Anzeige-Risiko klassifizieren.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: pii-privacy-governance, access-security-governance, eight-pillars
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
      - label: Unity Catalog Governance Generator
        href: /tools/unity-catalog-governance-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung, Rollen und Zugriffsregeln als konkrete Policy vorzubereiten.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Interner Leitfaden fuer PII-Klassifikation, Schutzbedarf und Datenschutzentscheidungen.
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Nutze die DSDR-Säule, um Lösch- und Nachweispfade nicht als Einzel-DELETE zu planen.
deliverables:
  - id: pii-classification-artifact
    label: PII-Klassifikationsmatrix
    plannedMinutes: 1080
    dependsOn: pii-classification
    helpText: |
      Erstelle das Artefakt fuer PII-Klassifikationsmatrix mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-02
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-03
number: 3
title: DSDR- und Auskunftspfade entwerfen
goal: Databricks Lakehouse/dbt/Power BI: DSDR- und Auskunftspfade entwerfen.
dependsOn: week-02

stories:
  - slug: eight-pillars
    required: true
  - slug: metadata-catalog-lineage
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: dsdr-paths
    label: DSDR- und Auskunftspfade entwerfen
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Nachvollziehen, wo personenbezogene Daten einer betroffenen Person im Modell, in Aggregaten, Exporten und App-Schichten vorkommen können. Löschung, Sperrung, Korrektur und Auskunft getrennt betrachten.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: eight-pillars, metadata-catalog-lineage, access-security-governance
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
      - label: Unity Catalog Governance Generator
        href: /tools/unity-catalog-governance-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung, Rollen und Zugriffsregeln als konkrete Policy vorzubereiten.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Interner Leitfaden fuer PII-Klassifikation, Schutzbedarf und Datenschutzentscheidungen.
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Nutze die DSDR-Säule, um Lösch- und Nachweispfade nicht als Einzel-DELETE zu planen.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Hilft, Quellen, Modelle, KPIs, Owner und DSDR-relevante Abhängigkeiten sichtbar zu machen.
deliverables:
  - id: dsdr-paths-artifact
    label: DSDR-Pfad- und Verantwortungsmatrix
    plannedMinutes: 1140
    dependsOn: dsdr-paths
    helpText: |
      Erstelle das Artefakt fuer DSDR-Pfad- und Verantwortungsmatrix mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-03
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-04
number: 4
title: Zugriff, Maskierung und Rollenmodell umsetzen
goal: Databricks Lakehouse/dbt/Power BI: Zugriff, Maskierung und Rollenmodell umsetzen.
dependsOn: week-03

stories:
  - slug: access-security-governance
    required: true
  - slug: pii-privacy-governance
    required: false
  - slug: data-ownership-stewardship
    required: false

tasks:
  - id: access-masking
    label: Zugriff, Maskierung und Rollenmodell umsetzen
    plannedMinutes: 3000
    assigneeType: team
    assigneeId: null
    helpText: |
      Rollen, Maskierung, Row-/Column-Level-Zugriff, Admin-Ausnahmen und Review-Prozess so definieren, dass Fachnutzer arbeiten können und sensible Daten geschützt bleiben.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: access-security-governance, pii-privacy-governance, data-ownership-stewardship
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
      - label: Unity Catalog Governance Generator
        href: /tools/unity-catalog-governance-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung, Rollen und Zugriffsregeln als konkrete Policy vorzubereiten.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Interner Leitfaden fuer PII-Klassifikation, Schutzbedarf und Datenschutzentscheidungen.
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Nutze die DSDR-Säule, um Lösch- und Nachweispfade nicht als Einzel-DELETE zu planen.
deliverables:
  - id: access-masking-artifact
    label: Access- und Maskierungsmodell
    plannedMinutes: 1200
    dependsOn: access-masking
    helpText: |
      Erstelle das Artefakt fuer Access- und Maskierungsmodell mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-04
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-05
number: 5
title: Datenschutz- und Exportkontrollen in der App härten
goal: Databricks Lakehouse/dbt/Power BI: Datenschutz- und Exportkontrollen in der App härten.
dependsOn: week-04

stories:
  - slug: pii-privacy-governance
    required: true
  - slug: access-security-governance
    required: false
  - slug: eight-pillars
    required: false

tasks:
  - id: app-privacy-controls
    label: Datenschutz- und Exportkontrollen in der App härten
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      Report-App auf personenbezogene Felder, Detailansichten, Drilldowns, Exporte, Screenshots, Downloads und Berechtigungslücken prüfen und absichern.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: pii-privacy-governance, access-security-governance, eight-pillars
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
      - label: Unity Catalog Governance Generator
        href: /tools/unity-catalog-governance-generator
        description: Nutze das Tool, um PII-Klassen, Maskierung, Rollen und Zugriffsregeln als konkrete Policy vorzubereiten.
      - label: PII & Privacy Governance
        href: /playbooks/pii-privacy-governance
        description: Interner Leitfaden fuer PII-Klassifikation, Schutzbedarf und Datenschutzentscheidungen.
      - label: Die 8 Säulen der Data Governance
        href: /playbooks/eight-pillars
        description: Nutze die DSDR-Säule, um Lösch- und Nachweispfade nicht als Einzel-DELETE zu planen.
deliverables:
  - id: app-privacy-controls-artifact
    label: App-Privacy-Abnahme
    plannedMinutes: 1140
    dependsOn: app-privacy-controls
    helpText: |
      Erstelle das Artefakt fuer App-Privacy-Abnahme mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-05
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-06
number: 6
title: Monitoring, Alerting und Runbook produktionsreif machen
goal: Databricks Lakehouse/dbt/Power BI: Monitoring, Alerting und Runbook produktionsreif machen.
dependsOn: week-05

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: monitoring-runbook
    label: Monitoring, Alerting und Runbook produktionsreif machen
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Ladefehler, Frische, Volumen, Laufzeit, DQ-Verletzungen, fehlende Quellen und App-Ausfälle messbar machen und mit klaren Reaktionswegen versehen.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, access-security-governance
deliverables:
  - id: monitoring-runbook-artifact
    label: Produktions-Runbook
    plannedMinutes: 1080
    dependsOn: monitoring-runbook
    helpText: |
      Erstelle das Artefakt fuer Produktions-Runbook mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-06
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-07
number: 7
title: Catalog, KPI-Registry und Lineage veröffentlichen
goal: Databricks Lakehouse/dbt/Power BI: Catalog, KPI-Registry und Lineage veröffentlichen.
dependsOn: week-06

stories:
  - slug: metadata-catalog-lineage
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: define-kpi
    required: false

tasks:
  - id: catalog-lineage
    label: Catalog, KPI-Registry und Lineage veröffentlichen
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Quellen, Modelle, KPIs, Owner, DQ-Regeln und App-Verwendung so dokumentieren, dass Fachbereich, Betrieb und Datenschutz dieselbe Sicht haben.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: metadata-catalog-lineage, data-ownership-stewardship, define-kpi
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
      - label: Metadata, Catalog & Lineage
        href: /playbooks/metadata-catalog-lineage
        description: Hilft, Quellen, Modelle, KPIs, Owner und DSDR-relevante Abhängigkeiten sichtbar zu machen.
deliverables:
  - id: catalog-lineage-artifact
    label: Catalog- und Lineage-Paket
    plannedMinutes: 1080
    dependsOn: catalog-lineage
    helpText: |
      Erstelle das Artefakt fuer Catalog- und Lineage-Paket mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-07
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-08
number: 8
title: DQ-Gates und Release-Prozess automatisieren
goal: Databricks Lakehouse/dbt/Power BI: DQ-Gates und Release-Prozess automatisieren.
dependsOn: week-07

stories:
  - slug: dq-test-kpis
    required: true
  - slug: access-security-governance
    required: false
  - slug: operating-and-governing-the-platform
    required: false

tasks:
  - id: dq-release-gates
    label: DQ-Gates und Release-Prozess automatisieren
    plannedMinutes: 2940
    assigneeType: team
    assigneeId: null
    helpText: |
      DQ-Checks, Review-Schritte, Deployment-Freigaben und Rückfallwege als wiederholbaren Release-Prozess für Datenmodell und App definieren.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: dq-test-kpis, access-security-governance, operating-and-governing-the-platform
    helpLinks:
      - label: Databricks DQ Expectation Generator
        href: /tools/databricks-dq-expectation-generator
        description: Erzeugt Databricks-spezifische DLT Expectations und Notebook-Checks fuer Pflichtfelder, Keys und Freshness.
deliverables:
  - id: dq-release-gates-artifact
    label: Release- und DQ-Gate-Prozess
    plannedMinutes: 1140
    dependsOn: dq-release-gates
    helpText: |
      Erstelle das Artefakt fuer Release- und DQ-Gate-Prozess mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-08
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-09
number: 9
title: Ersten A-Z-Prototyp fachlich abnehmen
goal: Databricks Lakehouse/dbt/Power BI: Ersten A-Z-Prototyp fachlich abnehmen.
dependsOn: week-08

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: go-live-readiness
    label: Ersten A-Z-Prototyp fachlich abnehmen
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Fachliche Abnahme, offene Risiken, bekannte Einschränkungen, Performance, Berechtigungen und Betriebsfähigkeit gemeinsam entscheiden.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, dq-test-kpis
deliverables:
  - id: go-live-readiness-artifact
    label: Go-live-Readiness-Entscheidung
    plannedMinutes: 1080
    dependsOn: go-live-readiness
    helpText: |
      Erstelle das Artefakt fuer Go-live-Readiness-Entscheidung mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-09
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-10
number: 10
title: App-Factory-Template schneiden
goal: Databricks Lakehouse/dbt/Power BI: App-Factory-Template schneiden.
dependsOn: week-09

stories:
  - slug: building-from-scratch
    required: true
  - slug: keeping-business-logic-outside-bi-apps
    required: false
  - slug: one-data-product-multiple-consumers
    required: false

tasks:
  - id: app-factory-template
    label: App-Factory-Template schneiden
    plannedMinutes: 2760
    assigneeType: team
    assigneeId: null
    helpText: |
      Aus dem ersten A-Z-Prototyp ein wiederverwendbares Muster für weitere Apps ableiten: Scope, Modell, DQ, Security, Help, Runbook und Abnahme.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: building-from-scratch, keeping-business-logic-outside-bi-apps, one-data-product-multiple-consumers
deliverables:
  - id: app-factory-template-artifact
    label: App-Factory-Template
    plannedMinutes: 1080
    dependsOn: app-factory-template
    helpText: |
      Erstelle das Artefakt fuer App-Factory-Template mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-10
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-11
number: 11
title: Zweite App als Wiederholungs-Slice vorbereiten
goal: Databricks Lakehouse/dbt/Power BI: Zweite App als Wiederholungs-Slice vorbereiten.
dependsOn: week-10

stories:
  - slug: building-from-scratch
    required: true
  - slug: keeping-business-logic-outside-bi-apps
    required: false
  - slug: one-data-product-multiple-consumers
    required: false

tasks:
  - id: second-app-slice
    label: Zweite App als Wiederholungs-Slice vorbereiten
    plannedMinutes: 2880
    assigneeType: team
    assigneeId: null
    helpText: |
      Die nächste App so schneiden, dass klar wird, ob das Muster wirklich wiederverwendbar ist: Quellen, KPIs, PII, Rollen und Migrationsrisiken erfassen.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: building-from-scratch, keeping-business-logic-outside-bi-apps, one-data-product-multiple-consumers
deliverables:
  - id: second-app-slice-artifact
    label: Zweite-App-Slice
    plannedMinutes: 1080
    dependsOn: second-app-slice
    helpText: |
      Erstelle das Artefakt fuer Zweite-App-Slice mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-11
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-12
number: 12
title: Betriebsschulung und Übergabe durchführen
goal: Databricks Lakehouse/dbt/Power BI: Betriebsschulung und Übergabe durchführen.
dependsOn: week-11

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: access-security-governance
    required: false

tasks:
  - id: training-handover
    label: Betriebsschulung und Übergabe durchführen
    plannedMinutes: 2700
    assigneeType: team
    assigneeId: null
    helpText: |
      Owner, Stewards, Entwickler, Support und Fachnutzer in Betrieb, Datenschutz, DQ-Reaktion, Änderungspfad und App-Nutzung einweisen.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, access-security-governance
deliverables:
  - id: training-handover-artifact
    label: Übergabe- und Schulungsnachweis
    plannedMinutes: 1020
    dependsOn: training-handover
    helpText: |
      Erstelle das Artefakt fuer Übergabe- und Schulungsnachweis mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-12
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

```sprint
id: week-13
number: 13
title: Q3 abschließen und Q4 App-Rollout planen
goal: Databricks Lakehouse/dbt/Power BI: Q3 abschließen und Q4 App-Rollout planen.
dependsOn: week-12

stories:
  - slug: operating-and-governing-the-platform
    required: true
  - slug: data-ownership-stewardship
    required: false
  - slug: dq-test-kpis
    required: false

tasks:
  - id: q4-rollout-backlog
    label: Q3 abschließen und Q4 App-Rollout planen
    plannedMinutes: 2580
    assigneeType: team
    assigneeId: null
    helpText: |
      Q4-Backlog für weitere Apps, Governance-Lücken, technische Schulden, Team-Skalierung und Betriebsverbesserungen priorisieren.
      Beziehe Power-BI-App, Datenmodell, Betrieb und Datenschutzsicht gemeinsam ein.
      Achte auf: Governance nur zu dokumentieren, ohne sie im Prozess testbar zu machen.
    linkedStories: operating-and-governing-the-platform, data-ownership-stewardship, dq-test-kpis
deliverables:
  - id: q4-rollout-backlog-artifact
    label: Q4-Rollout-Backlog
    plannedMinutes: 1020
    dependsOn: q4-rollout-backlog
    helpText: |
      Erstelle das Artefakt fuer Q4-Rollout-Backlog mit Owner, Datum, Entscheidung, offenem Risiko und Link zum Ergebnis. Es muss im Betrieb, in Datenschutzreviews oder fuer die naechste App wiederverwendbar sein.

fields:
  - id: q3-notes-13
    label: Notizen, Entscheidungen und offene Risiken
    type: textarea
    placeholder: Entscheidungen, offene Punkte, Risiken

notes: true
```

