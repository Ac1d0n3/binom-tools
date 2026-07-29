---
title: "Dynamics 365 → Mart: Opportunity-Fakten-Kandidaten"
description: "Aus dem freigegebenen Dynamics-365-Source-Scope einen Opportunity-Mart mit Dataverse-Grain, Fact/Dim-Kandidaten und versionierten KPI-Karten für ein erstes Vertriebsprodukt ableiten."
author: Thomas Lindackers
tags:
  - Dynamics 365
  - Dataverse
  - Mart Design
  - Grain
  - Data Governance
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
publishedAt: 2026-07-29
category: Data Governance
order: -1
series: supplier-to-mart
seriesTitle: Von Quelle zum Mart
seriesPart: 5
hero: images/playbooks/dynamics-365-to-mart-hero.png
---

Eine Dynamics-365-Umgebung ist eine konfigurierte Dataverse-Anwendung, in der Standardtabellen wie `account`, `contact` und `opportunity` durch Custom-Prozesse, Option Sets und Business Rules ergänzt werden können. Dieser Guide zeigt, wie aus dem freigegebenen Source Scope ein Opportunity-Mart entsteht: mit explizitem Grain, konkreten Fakten- und Dimensionskandidaten aus Dataverse und einer ersten Version der Standard-KPIs für Pipeline-Steuerung.

Wie bei den anderen Quellen dieser Serie ist der Ausgangspunkt nicht die vollständige Dataverse-Tabellenliste, sondern eine benannte Entscheidung — hier: Pipeline-Wert und -Bewegung nach Stage und Owner sichtbar machen.

## Problem

Ein tabellengetriebener Zugriff auf Dataverse führt zu denselben Fehlermustern wie bei anderen CRM-Systemen, verschärft durch die Konfigurierbarkeit der Plattform.

- `opportunity` und `opportunityproduct` (Line Item) werden vermischt, obwohl sie unterschiedliche Grains besitzen — Summen auf Opportunity-Ebene können von der Summe der Produktpositionen abweichen.
- Der aktuelle `statuscode`- oder `stepname`-Wert wird auf historische Perioden angewendet, sodass Stage-Bewegungen nicht mehr rekonstruierbar sind.
- Option-Set-Werte (z. B. Stage- oder Status-Labels) werden als stabile Business-Begriffe behandelt, obwohl sie in der Solution jederzeit umbenannt oder umsortiert werden können.
- `account` und `contact` werden ungeprüft mit voller Feldbreite geladen, obwohl nur wenige Segmentierungs- und Kontextfelder für den Mart benötigt werden.

Der freigegebene Source Scope aus [Welche Dynamics-365-Tabellen laden — und welche skippen](/playbooks/dynamics-365-tables-for-analytics) entscheidet, welche Tabellen, Felder und Beziehungen zulässig sind. Grain und Fakten für den Opportunity-Mart sind eine eigene, hier getroffene Entscheidung.

## Entscheidung

```text
Freigegebener Source Scope
→ Business Event und Ziel-Grain
→ Fact- und Dimension-Kandidaten
→ KPI-Karten auf demselben Grain
→ Mart Design Brief
```

Für ein erstes Vertriebsprodukt ist Opportunity-Grain der pragmatischste Startpunkt: eine Zeile pro `opportunity`, angereichert um governte `account`-, `contact`- und Owner-Kontexte (`systemuser`). Dieser Grain beantwortet Pipeline-Wert, Stage-Verteilung und Forecast-Fragen, ohne Produktkomplexität vorwegzunehmen.

Sobald Revenue nach Produkt benötigt wird, wechselt eine zweite Faktentabelle auf `opportunityproduct`-Grain — die Opportunity liefert dann nur noch Header-Kontext. Option-Set-Werte für Stage und Status werden auf eine governte, stabile Referenz gemappt, statt das rohe Label direkt als Dimension zu verwenden.

## Grain und Fact/Dim-Kandidaten

**Primärer Grain:** eine Zeile pro Opportunity (`opportunity`).

**Alternativer Grain (Produkt-Mart):** eine Zeile pro Opportunity-Produktposition (`opportunityproduct`).

| Rolle | Dataverse-Tabelle | Verwendung |
|---|---|---|
| Fact-Quelle (Opportunity-Grain) | `opportunity` | `estimatedvalue`, `actualvalue`, `estimatedclosedate` je Opportunity |
| Fact-Quelle (Positions-Grain) | `opportunityproduct` | Menge und Preis je Position, nur bei Produkt-Mart |
| Dimension: Unternehmen | `account` | Segment, Branche, Region als beschreibender Kontext |
| Dimension: Kontakt | `contact` | nur mit freigegebener Feld-Allowlist |
| Dimension: Owner | `systemuser` | verantwortliche Vertriebsperson oder Team |
| Dimension: Stage/Status | governte Referenz auf `statuscode` / `stepname` | Mapping auf eine stabile Stage-Reihenfolge |
| Dimension: Produkt | Product-Tabelle | nur bei Positions-Grain erforderlich |
| Dimension: Datum | Kalender-Dimension | `estimatedclosedate`, `actualclosedate`, `createdon` getrennt gehalten |

`estimatedvalue` und `actualvalue` sind additive Fakten, solange sie über die vorgesehenen Dimensionen konsistent summierbar bleiben — `actualvalue` ist erst nach Win/Loss belastbar befüllt. Statuscode und Stage sind Statusattribute, keine additiven Kennzahlen. Eine Stage-Bewegungsanalyse benötigt einen eigenen Snapshot- oder Event-Fact, nicht eine Ableitung aus dem aktuellen Zustand.

## PII und Skip

`contact`- und `systemuser`-Felder werden nur mit freigegebener Allowlist übernommen. Activities (`phonecall`, `appointment`, `email`) bleiben außerhalb des Opportunity-Marts, solange kein benannter Aktivitäts-Use-Case existiert. Custom Fields und Custom Tables aus installierten Solutions werden gegen den konfigurierten Prozess geprüft, bevor sie in den Scope aufgenommen werden.

Die vollständige Objekt- und Feldentscheidung — inklusive Security Roles und Löschverhalten — steht in [Welche Dynamics-365-Tabellen laden — und welche skippen](/playbooks/dynamics-365-tables-for-analytics). Nutze den [PII Recommend Generator](/tools/pii-recommend-generator) für die Feldklassifikation.

## Standard-KPIs

Auf Opportunity-Grain lassen sich verlässlich definieren:

- **Pipeline Value** — Summe `estimatedvalue` offener Opportunities je Stage, Owner und Periode.
- **Win Rate** — Anteil gewonnener Opportunities an allen abgeschlossenen Opportunities im Zeitraum.
- **Average Deal Size** — Durchschnittlicher `actualvalue` gewonnener Opportunities.
- **Stage Conversion Rate** — Anteil der Opportunities, die von einer Stage zur nächsten übergehen.

Jede Kennzahl benötigt eine eigene KPI-Karte mit Zähler, Nenner, Zeitlogik und Owner. Nutze [KPI definieren](/playbooks/define-kpi) für die Methodik und den [KPI Definition](/tools/kpi-definition)-Generator zur strukturierten Erfassung.

## Artefakt

- **`source-scope.csv` / `source-scope.md`** — freigegebene Dataverse-Tabellen, Felder und Beziehungen.
- **`kpi-cards.csv`** — die Standard-KPIs mit Formel, Grain-Referenz, Filtern und Owner.
- **`mart-design-brief.md`** — Business Event, Grain, Fact-/Dimension-Kandidaten, Stage-Mapping und Scope-Out für den Opportunity-Mart.

Dieselben Dateinamen gelten über alle Guides dieser Serie hinweg.

## Tools und nächste Schritte

- [Source Scope Builder](/tools/source-scope-builder) — Dataverse-Tabellen, Felder und Beziehungen als `source-scope.csv` dokumentieren.
- [PII Recommend Generator](/tools/pii-recommend-generator) — Feldrisiko für Contact- und User-Attribute klassifizieren.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — Grain, Fakten, Dimensionen und Scope-Out als `mart-design-brief.md` freigeben.
- [KPI Definition](/tools/kpi-definition) — Standard-KPIs als `kpi-cards.csv` versionieren.

Ob Dynamics 365 überhaupt die richtige nächste Quelle ist, klärt der [Governance Advisor](/governance/berater). Dieser Guide vertieft eine bereits getroffene Priorisierung.

## Verwandte Playbooks

- [Welche Dynamics-365-Tabellen laden — und welche skippen](/playbooks/dynamics-365-tables-for-analytics) — die vorgelagerte Load/Skip-Entscheidung für Tabellen, Felder und Beziehungen.
- [Vom Stakeholder-Interview zum Tabellenmodell](/playbooks/from-stakeholder-interview-to-table-model) — die allgemeine Methodik für Grain, Fakten, Dimensionen und den Mart Design Brief.
- [Welche Quelle zuerst laden?](/playbooks/which-source-to-load-first) — die Portfolio-Entscheidung, falls Dynamics 365 noch gegen andere Kandidaten priorisiert werden muss.

Das vollständige Supplier-Profil mit Produktkontext, Compliance-Hinweisen und weiteren Ressourcen steht in der [Supplier Library: Dynamics 365](/suppliers/dynamics365).
