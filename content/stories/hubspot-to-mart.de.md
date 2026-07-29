---
title: "HubSpot → Mart: Deal-Grain zum Pilot-Mart"
description: "Aus dem freigegebenen HubSpot Source Scope einen ersten Deal-Mart mit klarem Grain, Fact/Dim-Kandidaten und versionierten KPI-Karten für einen Pilotprozess ableiten."
author: Thomas Lindackers
tags:
  - HubSpot
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
seriesPart: 2
hero: images/playbooks/hubspot-to-mart-hero.png
---

Ein freigegebener HubSpot Source Scope legt fest, welche Objekte, Properties und Associations geladen werden dürfen. Damit ist aber noch kein Mart entstanden. Dieser Guide zeigt den nächsten Schritt für ein typisches Pilotprodukt: einen Deal-Mart, der Pipeline-Steuerung nach Stage und Owner unterstützt, mit explizitem Grain, benannten Fact- und Dimension-Kandidaten und einer ersten Version der Standard-KPIs.

Der Fokus liegt bewusst auf einem kleinen, vollständigen Pilot-Mart statt auf einer vollständigen Abbildung des HubSpot-Portals. Ein Pilot, der Stage-Steuerung zuverlässig unterstützt, liefert mehr Vertrauen als ein breiter, aber unfertiger Export aller Objekte.

## Problem

HubSpot-Portale sind konfigurierbar: Pipelines, Stages, Custom Properties und Association Labels unterscheiden sich zwischen Portalen und sogar zwischen Business Units desselben Unternehmens. Ein Mart, der Deal-Objekte ungeprüft übernimmt, erbt typische Probleme.

- Ein Deal kann mit mehreren Companies oder Contacts assoziiert sein; ohne eine explizite Primary-Company-Regel entstehen doppelte oder willkürlich reduzierte Zeilen.
- Property History und der aktuelle Deal-Zustand werden vermischt, sodass eine Stage-Bewegungsanalyse denselben Datensatz mehrfach oder falsch datiert zählt.
- Deal Amount wird ohne Line-Item-Kontext summiert, obwohl Revenue nach Produkt eigentlich Positions-Grain benötigt.
- Pipeline- und Stage-Bezeichnungen werden als stabile Business-Begriffe behandelt, obwohl sie im Portal jederzeit umbenannt oder neu sortiert werden können.

Der freigegebene Source Scope aus [Welche HubSpot-Tabellen laden — und welche skippen](/playbooks/hubspot-tables-for-analytics) entscheidet, welche Objekte und Properties überhaupt verfügbar sind. Die Grain- und Fakten-Entscheidung für den Mart bleibt trotzdem ein eigener Schritt.

## Entscheidung

```text
Freigegebener Source Scope
→ Business Event und Ziel-Grain
→ Fact- und Dimension-Kandidaten
→ KPI-Karten auf demselben Grain
→ Mart Design Brief
```

Für einen ersten Pilot-Mart ist Deal-Grain der pragmatischste Startpunkt: eine Zeile pro Deal, angereichert um governte Company-, Contact- und Owner-Kontexte. Dieser Grain beantwortet die häufigste Einstiegsfrage — wo in der Pipeline muss eingegriffen werden — ohne Produktkomplexität vorwegzunehmen.

Sobald Revenue nach Produkt eine Rolle spielt, wird eine zweite Faktentabelle auf Line-Item-Grain ergänzt, statt den Deal-Mart nachträglich zu verbiegen. Für Company-Assoziationen gilt eine feste Regel: Wenn ein Deal mit mehreren Companies verknüpft ist, wird eine Primary-Company-Zuordnung dokumentiert und angewendet — nicht implizit die erste oder zufällig zurückgegebene Assoziation verwendet.

## Grain und Fact/Dim-Kandidaten

**Primärer Grain:** eine Zeile pro Deal.

**Alternativer Grain (Produkt-Mart):** eine Zeile pro Deal-Line-Item.

| Rolle | HubSpot-Objekt | Verwendung |
|---|---|---|
| Fact-Quelle (Deal-Grain) | `Deal` | Amount, Close Date, Pipeline, Stage je Deal |
| Fact-Quelle (Positions-Grain) | `Line Item` | Quantity, Price je Position, nur bei Produkt-Mart |
| Dimension: Unternehmen | `Company` | über die dokumentierte Primary-Company-Association |
| Dimension: Kontakt | `Contact` | nur mit freigegebener Property-Allowlist |
| Dimension: Owner | HubSpot Owner / Team | verantwortliche Vertriebsperson |
| Dimension: Pipeline und Stage | governte Pipeline-/Stage-Referenz | Mapping auf eine stabile Stage-Reihenfolge je Pipeline |
| Dimension: Produkt | `Product` | nur bei Positions-Grain erforderlich |
| Dimension: Datum | Kalender-Dimension | Close Date, Create Date, Last Stage Change getrennt gehalten |

Amount und Quantity sind additive Fakten, solange sie über die vorgesehenen Dimensionen konsistent summierbar bleiben. Stage und Deal Status sind Statusattribute. Eine Stage-Bewegungsanalyse benötigt einen eigenen Snapshot- oder Event-Fact (eine Zeile pro Deal und Stage-Wechsel oder Berichtsdatum) statt einer Ableitung aus dem aktuellen Deal-Zustand.

## PII und Skip

Contact- und Company-Felder werden nur mit freigegebener Property-Allowlist übernommen. Notes, Messages, Calls und Meetings bleiben außerhalb des Pilot-Marts, solange kein benannter Aktivitäts-Use-Case existiert. Property History wird nur für die tatsächlich benötigten Felder geladen, nicht pauschal für das gesamte Deal-Objekt.

Die vollständige Entscheidung über Objekte, Felder, Associations und Löschverhalten steht in [Welche HubSpot-Tabellen laden — und welche skippen](/playbooks/hubspot-tables-for-analytics). Für die Feldklassifikation eignet sich der [PII Recommend Generator](/tools/pii-recommend-generator).

## Standard-KPIs

Auf Deal-Grain lassen sich für den Piloten definieren:

- **Pipeline Coverage** — Verhältnis von offenem Pipeline-Wert zu Zielumsatz je Periode.
- **Win Rate** — Anteil gewonnener Deals an allen abgeschlossenen Deals im Zeitraum.
- **Average Deal Size** — Durchschnittlicher Amount gewonnener Deals.
- **Sales Cycle Length** — Tage zwischen Create Date und Close Date bei gewonnenen Deals.
- **Deal Velocity** — durchschnittliche Verweildauer je Stage.

Jede Kennzahl benötigt eine eigene KPI-Karte statt nur einer Formel. Nutze [KPI definieren](/playbooks/define-kpi) für die Methodik und den [KPI Definition](/tools/kpi-definition)-Generator zur strukturierten Erfassung.

## Artefakt

- **`source-scope.csv` / `source-scope.md`** — freigegebene HubSpot-Objekte, Properties und Associations.
- **`kpi-cards.csv`** — die Standard-KPIs mit Formel, Grain-Referenz, Filtern und Owner.
- **`mart-design-brief.md`** — Business Event, Grain, Fact-/Dimension-Kandidaten, Primary-Company-Regel und Scope-Out für den Deal-Mart.

Dieselben Dateinamen werden über alle Guides dieser Serie hinweg verwendet, damit Exporte, Tools und Dokumentation konsistent bleiben.

## Tools und nächste Schritte

- [Source Scope Builder](/tools/source-scope-builder) — HubSpot-Objekte, Properties und Associations als `source-scope.csv` dokumentieren.
- [PII Recommend Generator](/tools/pii-recommend-generator) — Feldrisiko für Contact- und Company-Properties klassifizieren.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — Grain, Fakten, Dimensionen und Scope-Out als `mart-design-brief.md` freigeben.
- [KPI Definition](/tools/kpi-definition) — Standard-KPIs als `kpi-cards.csv` versionieren.

Für die grundsätzliche Priorisierungsfrage — ob HubSpot überhaupt die richtige erste oder nächste Quelle ist — bleibt der [Governance Advisor](/governance/berater) der Einstiegspunkt. Dieser Guide vertieft die Entscheidung, ersetzt sie aber nicht.

## Verwandte Playbooks

- [Welche HubSpot-Tabellen laden — und welche skippen](/playbooks/hubspot-tables-for-analytics) — die vorgelagerte Load/Skip-Entscheidung für Objekte, Properties und Associations.
- [Vom Stakeholder-Interview zum Tabellenmodell](/playbooks/from-stakeholder-interview-to-table-model) — die allgemeine Methodik für Grain, Fakten, Dimensionen und den Mart Design Brief.
- [Welche Quelle zuerst laden?](/playbooks/which-source-to-load-first) — die Portfolio-Entscheidung, falls HubSpot noch gegen andere Kandidaten priorisiert werden muss.

Das vollständige Supplier-Profil mit Produktkontext, Compliance-Hinweisen und weiteren Ressourcen steht in der [Supplier Library: HubSpot](/suppliers/hubspot).
