---
title: "Salesforce → Mart: Grain, Fakten und KPI-Karten"
description: "Aus dem freigegebenen Salesforce Source Scope einen konkreten Pipeline-Mart mit Opportunity-Grain, Fact/Dim-Kandidaten und versionierten KPI-Karten ableiten."
author: Thomas Lindackers
tags:
  - Salesforce
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
seriesPart: 1
hero: images/playbooks/salesforce-to-mart-hero.png
---

Ein freigegebener Salesforce Source Scope beantwortet, welche Objekte, Felder und Beziehungen geladen werden dürfen. Er beantwortet noch nicht, wie eine Faktentabelle aussieht, was eine Zeile bedeutet und welche KPI-Karten daraus entstehen. Dieser Guide setzt genau dort an: Er führt vom freigegebenen Scope zu einem konkreten Pipeline-Mart mit explizitem Grain, benannten Fact- und Dimension-Kandidaten und einer ersten Version der Standard-KPIs.

Der Guide ersetzt weder die Load/Skip-Entscheidung noch die Advisor-Journey. Er ist die nächste Stufe, nachdem Objekte und Felder freigegeben sind.

## Problem

Teams, die von Salesforce direkt zu einem Dashboard springen, überspringen häufig eine Zwischenstufe: die Entscheidung, was eine Zeile im Mart bedeutet. Ohne diese Entscheidung entstehen typische Fehler.

- `Opportunity` und `OpportunityLineItem` werden vermischt, obwohl sie unterschiedliche Grains besitzen — eine Summe auf Opportunity-Ebene kann von der Summe der Positionen abweichen, wenn Rabatte, Teilmengen oder mehrere Pricebooks im Spiel sind.
- Der aktuelle `StageName`-Wert wird auf historische Perioden angewendet, sodass eine gewonnene Opportunity rückwirkend so aussieht, als wäre sie schon immer gewonnen gewesen.
- Amount wird ohne Währungs- und Wahrscheinlichkeitslogik summiert, obwohl Finance zwischen Best Case, Commit und Pipeline unterscheidet.
- Owner wird als Freitext statt als governte Referenz auf `User` behandelt, wodurch Reorganisationen die Historie verfälschen.

Der freigegebene Source Scope aus [Welche Salesforce-Tabellen für Analytics laden](/playbooks/salesforce-tables-for-analytics) verhindert unkontrollierte Objektauswahl. Er entscheidet aber nicht automatisch über Grain, Fakten und Dimensionen des Marts — das ist eine eigene Modellierungsentscheidung.

## Entscheidung

Der Weg von den freigegebenen Salesforce-Objekten zum Mart folgt einer festen Reihenfolge:

```text
Freigegebener Source Scope
→ Business Event und Ziel-Grain
→ Fact- und Dimension-Kandidaten
→ KPI-Karten auf demselben Grain
→ Mart Design Brief
```

Zuerst wird das Business Event benannt: Eine Opportunity wird erstellt, bewegt sich durch Stages und wird gewonnen, verloren oder storniert. Dieses Event — nicht die Salesforce-Tabelle — bestimmt den Grain. Für ein erstes Pipeline-Produkt ist der pragmatischste Startpunkt meist Opportunity-Grain, weil er ohne Produktkomplexität auskommt und trotzdem Stage-, Owner- und Forecast-Fragen beantwortet. Sobald Revenue nach Produkt oder Menge eine Rolle spielt, wechselt der Grain auf die Opportunity-Position — dann übernimmt `OpportunityLineItem` die Rolle der Faktenquelle, und `Opportunity` liefert nur noch Header-Kontext.

Beide Grains dürfen nicht in derselben Faktentabelle vermischt werden. Wird ein Produkt-Mart gebraucht, entsteht eine zweite Faktentabelle auf Positions-Grain statt einer Spalten-Erweiterung der ersten.

## Grain und Fact/Dim-Kandidaten

**Primärer Grain:** eine Zeile pro Opportunity.

**Alternativer Grain (Produkt-Mart):** eine Zeile pro Opportunity-Position.

| Rolle | Salesforce-Objekt | Verwendung |
|---|---|---|
| Fact-Quelle (Opportunity-Grain) | `Opportunity` | Amount, Probability, CloseDate, StageName je Opportunity |
| Fact-Quelle (Positions-Grain) | `OpportunityLineItem` | Quantity, UnitPrice, TotalPrice je Position |
| Dimension: Kunde | `Account` | Segment, Industry, Region als beschreibender Kontext |
| Dimension: Ansprechpartner | `Contact` | nur mit freigegebener Feld-Allowlist, primär als Rollen-Referenz |
| Dimension: Owner | `User` | verantwortliche Vertriebsperson oder Team |
| Dimension: Produkt | `Product2` / `PricebookEntry` | nur bei Positions-Grain erforderlich |
| Dimension: Stage | governte Stage-Referenz | Mapping von `StageName` auf eine stabile Stage-Reihenfolge |
| Dimension: Datum | Kalender-Dimension | CloseDate, CreatedDate, LastModifiedDate getrennt gehalten |

Additive Fakten sind Amount, Quantity und Expected Revenue, solange die Summierung über die vorgesehenen Dimensionen gültig bleibt. Probability und StageName sind Statusattribute, keine additiven Kennzahlen — sie beschreiben den Zustand einer Zeile, nicht ein Mengenereignis. Ein Snapshot-Fact (eine Zeile pro Opportunity und Berichtsdatum) ist ein bewusst eigenständiges Design, wenn die Bewegung durch Stages über Zeit analysiert werden soll; er wird nicht rückwirkend aus dem Current-State-Objekt simuliert.

## PII und Skip

Der Umfang personenbezogener Daten wird nicht in diesem Guide entschieden, sondern im freigegebenen Salesforce Source Scope. Für den Mart gilt die verkürzte Regel: `Contact`- und `User`-Felder werden nur mit freigegebener Allowlist übernommen, Freitext, Notizen und Aktivitäten bleiben außerhalb des Pipeline-Marts, solange kein benannter Use Case existiert.

Die vollständige Objekt-für-Objekt-Entscheidung — inklusive Custom Objects, History-Verhalten und Löschregeln — steht in [Welche Salesforce-Tabellen für Analytics laden](/playbooks/salesforce-tables-for-analytics). Nutze zur Klassifikation der Felder den [PII Recommend Generator](/tools/pii-recommend-generator).

## Standard-KPIs

Auf Opportunity-Grain lassen sich verlässlich definieren:

- **Pipeline Value** — Summe Amount offener Opportunities je Stage, Owner und Periode.
- **Win Rate** — Anteil gewonnener Opportunities an allen abgeschlossenen Opportunities im Zeitraum.
- **Average Deal Size** — Durchschnittlicher Amount gewonnener Opportunities.
- **Sales Cycle Length** — Tage zwischen CreatedDate und CloseDate bei gewonnenen Opportunities.
- **Stage Conversion Rate** — Anteil der Opportunities, die von einer Stage zur nächsten übergehen.

Jede dieser Kennzahlen benötigt eine eigene KPI-Karte mit Zähler, Nenner, Zeitlogik, Ausschlüssen und Owner — die Formel allein reicht nicht. Nutze [KPI definieren](/playbooks/define-kpi) für die Methodik und den [KPI Definition](/tools/kpi-definition)-Generator, um die Karten strukturiert zu erfassen und zu versionieren.

## Artefakt

Der Guide erzeugt drei zusammenhängende Artefakte, die denselben Grain referenzieren:

- **`source-scope.csv` / `source-scope.md`** — die freigegebenen Salesforce-Objekte, Felder und Beziehungen aus dem Source-Scope-Register.
- **`kpi-cards.csv`** — die Standard-KPIs mit Formel, Grain-Referenz, Filtern und Owner.
- **`mart-design-brief.md`** — Business Event, Grain, Fact-/Dimension-Kandidaten, Historienverhalten und Scope-Out für den Pipeline-Mart, bereit zur Freigabe vor dem physischen Tabellenbau.

Diese Dateinamen sind bewusst konsistent mit den übrigen Guides dieser Serie, damit dieselben Exporte in Tools und Dokumentation wiedererkennbar bleiben.

## Tools und nächste Schritte

- [Source Scope Builder](/tools/source-scope-builder) — Salesforce-Objekte, Felder und Beziehungen als `source-scope.csv` dokumentieren.
- [PII Recommend Generator](/tools/pii-recommend-generator) — Feldrisiko für Contact- und User-Attribute klassifizieren.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — Grain, Fakten, Dimensionen und Scope-Out als `mart-design-brief.md` freigeben.
- [KPI Definition](/tools/kpi-definition) — Standard-KPIs als `kpi-cards.csv` versionieren.

Wer unsicher ist, ob Salesforce überhaupt die richtige erste Quelle ist oder wie ein Discovery-Gespräch strukturiert wird, sollte zuerst den [Governance Advisor](/governance/berater) nutzen. Die Rolle dieses Guides ist die Vertiefung nach der Entscheidung — nicht der Entscheidungseinstieg.

## Verwandte Playbooks

- [Welche Salesforce-Tabellen für Analytics laden](/playbooks/salesforce-tables-for-analytics) — die vorgelagerte Load/Skip-Entscheidung für Objekte, Felder und Beziehungen.
- [Vom Stakeholder-Interview zum Tabellenmodell](/playbooks/from-stakeholder-interview-to-table-model) — die allgemeine Methodik für Grain, Fakten, Dimensionen und den Mart Design Brief.
- [Welche Quelle zuerst laden?](/playbooks/which-source-to-load-first) — die Portfolio-Entscheidung, falls Salesforce noch gegen andere Kandidaten priorisiert werden muss.

Das vollständige Supplier-Profil mit Produktkontext, Compliance-Hinweisen und weiteren Ressourcen steht in der [Supplier Library: Salesforce](/suppliers/salesforce).
