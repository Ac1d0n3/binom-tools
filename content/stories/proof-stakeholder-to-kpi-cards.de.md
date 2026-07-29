---
title: "Proof: Vom Stakeholder-Interview zu freigegebenen KPI-Karten"
description: "Ein anonymisiertes Beispiel, wie ein Vertriebsteam von widersprüchlichen Pipeline-Reports über strukturierte Interviews zu freigegebenen KPI-Karten und einem Mart Design Brief kam."
author: Thomas Lindackers
tags:
  - Proof
  - KPI Governance
  - Stakeholder Interviews
  - Mart Design
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
series: governance-proof
seriesTitle: Governance Proof
seriesPart: 1
hero: images/playbooks/proof-stakeholder-to-kpi-cards-hero.png
---

Ein mittelständisches B2B-Vertriebsteam kam mit einem vertrauten Problem in ein Discovery-Gespräch: Drei Dashboards zeigten drei unterschiedliche Zahlen für „offene Pipeline“, und niemand konnte in der Besprechung erklären, warum. Diese Story zeigt anonymisiert, wie aus diesem Ausgangspunkt in wenigen Schritten freigegebene KPI-Karten und ein Mart Design Brief entstanden — ohne Kundennamen, Zahlen oder identifizierbare Details.

## Ausgangslage

Das Vertriebsteam nutzte Salesforce als CRM und ein BI-Tool für Reporting. Drei Personen hatten unabhängig voneinander Pipeline-Dashboards gebaut: Vertriebsleitung, Sales Operations und ein Business Analyst aus dem Controlling. Alle drei verwendeten den Begriff „offene Pipeline“, aber mit unterschiedlichen Stage-Filtern, unterschiedlichen Stichtagen und unterschiedlicher Behandlung von Opportunities ohne Close Date.

Der Auslöser für das Discovery-Gespräch war kein Projektstart, sondern ein wiederkehrendes Ärgernis: In jedem Monatsmeeting wurde zunächst zehn Minuten diskutiert, welche Zahl „stimmt“, bevor überhaupt über Inhalte gesprochen werden konnte.

## Schritte

1. **Discovery-Gespräch strukturieren.** Statt sofort über Formeln zu sprechen, wurde zunächst geklärt, wer welche Entscheidung mit „offener Pipeline“ trifft. Die Vertriebsleitung wollte wissen, wo eingegriffen werden muss. Sales Operations wollte Forecast-Genauigkeit prüfen. Controlling wollte eine konservative Zahl für die Monatsplanung. Das Team folgte dabei der Methodik aus [Vom Stakeholder-Interview zum Tabellenmodell](/playbooks/from-stakeholder-interview-to-table-model): Evidenz, Interpretation und Entscheidung wurden getrennt dokumentiert, statt Interview-Aussagen direkt als Modellentscheidung zu übernehmen.

2. **Grain-Satz formulieren, bevor über Kennzahlen gesprochen wird.** Aus den drei Aussagen wurde ein gemeinsamer Grain destilliert: eine Zeile pro Opportunity, nicht pro Opportunity-Position, weil Produktdetails für keine der drei Fragen erforderlich waren. Diese Entscheidung allein löste bereits einen Teil des Konflikts, weil eines der drei Dashboards ungewollt auf Positionsebene aggregiert hatte.

3. **KPI-Anforderungen strukturiert erfassen.** Für „offene Pipeline“ wurden mit der [KPI Requirements Intake](/tools/kpi-requirements-intake) die drei konkurrierenden Definitionen nebeneinandergelegt: unterschiedliche Stage-Populationen, unterschiedlicher Umgang mit fehlendem Close Date, unterschiedliche Snapshot- versus Live-Logik. Keine der drei Varianten war „falsch“ — sie beantworteten unterschiedliche Fragen unter demselben Namen.

4. **Entscheidung statt Kompromiss.** Der benannte KPI-Owner aus dem Sales-Operations-Team traf die Entscheidung: „Offene Pipeline“ wird künftig als eine einzige, benannte Definition geführt; die beiden anderen Sichten erhalten eigene, klar unterschiedene Namen (`Pipeline at Risk`, `Committed Forecast`) statt denselben Begriff für unterschiedliche Logik zu verwenden.

5. **KPI-Karten versionieren.** Alle drei Definitionen wurden mit dem [KPI Definition](/tools/kpi-definition)-Generator als KPI-Karten mit Formel, Grain, Zeitlogik, Filtern und Owner erfasst — nicht nur als Excel-Notiz, sondern als versioniertes, wiederverwendbares Artefakt.

6. **Mart Design Brief ableiten.** Aus dem gemeinsamen Grain und den drei KPI-Karten entstand mit dem [Mart Design Brief Generator](/tools/mart-design-brief-generator) ein Brief für einen Pipeline-Mart: ein Fact auf Opportunity-Grain, mit den benötigten Dimensionen (Account, Owner, Stage, Datum) und einem dokumentierten Snapshot-Bedarf für die Stage-Bewegungsanalyse.

## Artefakte

Aus diesem Durchlauf entstanden die Standard-Artefakte, die auch in [Welche Artefakte entstehen?](/playbooks/which-artifacts-you-get) beschrieben sind:

- **`governance-discovery.md`** — Zusammenfassung des Discovery-Gesprächs mit den drei konkurrierenden Sichten und offenen Fragen.
- **`kpi-cards.csv`** — drei benannte, unterscheidbare KPI-Karten statt einer mehrdeutigen Kennzahl.
- **`mart-design-brief.md`** — Grain, Fact-/Dimension-Kandidaten und Snapshot-Bedarf für den Pipeline-Mart, freigegeben vor dem physischen Tabellenbau.

## Lernerfolg

Der wertvollste Teil dieses Durchlaufs war nicht die Formel selbst, sondern die Erkenntnis, dass drei technisch korrekte Berechnungen unter demselben Namen ein Vertrauensproblem erzeugen können, das keine BI-Optimierung löst. Erst die explizite Trennung in drei benannte Kennzahlen mit je einem Owner beendete die wiederkehrende Diskussion im Monatsmeeting.

Ein zweiter Lernpunkt betraf die Reihenfolge: Ohne den vorab festgelegten Grain-Satz wäre die KPI-Diskussion vermutlich wieder bei „wessen Zahl stimmt“ gelandet, statt bei „welche Frage beantwortet diese Zahl“.

> **Hinweis:** Diese Story ist ein anonymisiertes, zusammengefasstes Beispiel zur Veranschaulichung der Methodik. Sie ersetzt keine Rechts-, Datenschutz- oder Vendor-Beratung und ist keine Fallstudie eines konkreten Kunden.

## Tools und nächste Schritte

- [KPI Requirements Intake](/tools/kpi-requirements-intake) — konkurrierende Definitionen strukturiert nebeneinanderlegen.
- [KPI Definition](/tools/kpi-definition) — freigegebene Kennzahlen als `kpi-cards.csv` versionieren.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — Grain, Fakten und Dimensionen als `mart-design-brief.md` freigeben.
- Unsicher, ob ein Discovery-Gespräch oder direkt eine KPI-Karte der richtige nächste Schritt ist? Der [Governance Advisor](/governance/berater) führt durch die Ausgangslage.

## Verwandte Playbooks

- [Vom Stakeholder-Interview zum Tabellenmodell](/playbooks/from-stakeholder-interview-to-table-model) — die vollständige Methodik hinter Schritt 1 und 2.
- [Welche Artefakte entstehen?](/playbooks/which-artifacts-you-get) — die Dateinamen, die in dieser Story verwendet werden.
- [Salesforce → Mart: Grain, Fakten und KPI-Karten](/playbooks/salesforce-to-mart) — ein vertiefender Guide für denselben Quelltyp.

Teil 2 dieser Serie zeigt denselben Ansatz für eine neue SaaS-Quelle, von der Load/Skip-Entscheidung bis zum ersten Pilot-Mart.
