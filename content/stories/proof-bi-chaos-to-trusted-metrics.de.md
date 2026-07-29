---
title: "Proof: Vom BI-Report-Chaos zu vertrauenswürdigen Kennzahlen"
description: "Ein anonymisiertes Beispiel, wie ein Reporting-Team sieben Varianten von 'Net Revenue' über ein Report-Inventar, eine Platzierungsentscheidung und kontrollierte Generatoren zu einer zertifizierten Kennzahl konsolidierte."
author: Thomas Lindackers
tags:
  - Proof
  - Trusted Metrics
  - Semantic Layer
  - BI Governance
  - Data Governance
products:
  - qlik
  - fabric
  - powerbi
  - snowflake
  - dbt
publishedAt: 2026-07-29
category: Data Governance
order: -1
series: governance-proof
seriesTitle: Governance Proof
seriesPart: 3
hero: images/playbooks/proof-bi-chaos-to-trusted-metrics-hero.png
---

Ein Reporting-Team entdeckte im Rahmen einer Qualitätsprüfung, dass der Begriff „Net Revenue“ in mindestens sieben verschiedenen Reports, Semantic Models und einer Excel-Arbeitsmappe vorkam — mit unterschiedlichen Werten. Diese anonymisierte Story zeigt den Weg von diesem Fund über ein strukturiertes Report-Inventar bis zu einer zertifizierten, wiederverwendbaren Kennzahl.

## Ausgangslage

Die Organisation nutzte Power BI für Executive-Reporting, Qlik für operative Vertriebsanalysen und eine gepflegte Excel-Arbeitsmappe im Controlling. Alle drei Umgebungen enthielten eine Kennzahl namens „Net Revenue“. Bei einem Zahlenabgleich vor einem Quartalsabschluss fielen Abweichungen auf, die zunächst niemand erklären konnte — die Formeln sahen in jedem Tool unterschiedlich aus, weil jede BI-Engine Filterkontext anders ausdrückt.

Der reflexartige erste Vorschlag lautete, „einfach eine Formel zu kopieren“. Das Team entschied sich stattdessen für eine systematische Aufarbeitung.

## Schritte

1. **Report-Inventar statt Formelvergleich per Zuruf.** Mit der Methodik aus [Vom Report Inventory zur vertrauenswürdigen Kennzahl](/playbooks/from-report-inventory-to-trusted-metric) wurden alle sieben Implementierungen erfasst: Plattform, Ausdruck, Basisgranularität, Filter, Zeitverhalten, Owner und Nutzung. Bereits diese Erfassung zeigte, dass zwei der sieben Varianten exakte Kopien waren, drei syntaktisch unterschiedlich, aber semantisch gleich, und zwei tatsächlich unterschiedliche fachliche Definitionen (brutto versus netto nach Stornos).

2. **Cluster bilden und vergleichen.** Die Kandidaten wurden nach Geschäftsfrage, Population, Grain, Aggregation, Zeitlogik und Owner-Bereitschaft verglichen, statt nach vermeintlicher Ähnlichkeit der Formel. Die am häufigsten genutzte Variante erwies sich dabei nicht automatisch als die fachlich korrekte.

3. **Platzierung entscheiden.** Mit [Semantic Layer vs Measure im Report](/playbooks/semantic-layer-vs-report-measure) wurde geklärt, welcher Teil der Logik ins Warehouse gehört (Stornobehandlung, Währungsstandardisierung), welcher in die semantische Schicht (freigegebene Aggregation, Zeitverhalten) und welcher als report-lokale Variante bestehen bleiben darf (Anteil an der aktuell ausgewählten Gesamtsumme in einem Vertriebsdashboard).

4. **Owner-Entscheidung einholen.** Ein benannter Metric Owner aus Finance genehmigte eine einzige kanonische Definition für „Net Revenue“ und ordnete den zwei abweichenden Definitionen neue, eindeutige Namen zu, statt sie unter demselben Label weiterlaufen zu lassen.

5. **Implementierung kontrolliert generieren.** Für die genehmigte Definition wurden mit [Wann BI-Formel-Generatoren der Governance helfen](/playbooks/when-to-use-bi-formula-generators) Implementierungen für Power BI und Qlik erzeugt — auf Basis eines vollständigen Metrikvertrags, nicht einer vagen Anforderung. Referenzergebnisse und Toleranzen wurden vorab festgelegt.

6. **Parallel abgleichen und migrieren.** Beide generierten Implementierungen wurden gegen dieselben Testszenarien reconciled, bevor produktive Reports migriert wurden. Die Excel-Arbeitsmappe blieb als Analyseoberfläche bestehen, bezieht die Kernzahl aber seither aus dem freigegebenen Semantic Model statt aus einer eigenen Formel.

7. **Verbleibende Lücken prüfen.** Mit [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics) prüfte das Team abschließend, ob Definition, Ownership, Qualität und Zertifizierung tatsächlich für vertrauenswürdige Nutzung ausreichten, oder ob eine der sieben Varianten weiterhin unentdeckt im Umlauf war.

## Artefakte

- **Trusted Metric Candidate Record** — eine Zeile pro Metrikfamilie mit gefundenen Implementierungen, Vergleichsergebnis und Owner-Entscheidung (`zertifizieren`, `konsolidieren`, `deprecaten`).
- **Metric Placement Decision** — dokumentiert, welche Logik im Warehouse, in der semantischen Schicht und report-lokal lebt.
- **`kpi-cards.csv`** — die genehmigte „Net Revenue“-Definition sowie die beiden neu benannten Varianten, versioniert als KPI-Karten.
- **Formula Generation Request and Validation Record** — Input-Vertrag, generierte Formeln, Reconciliation-Ergebnis und Freigabe für Power BI und Qlik.

Die gemeinsamen Dateinamen für KPI-Karten und weitere Standard-Artefakte stehen in [Welche Artefakte entstehen?](/playbooks/which-artifacts-you-get).

## Lernerfolg

Der zentrale Lernpunkt war, dass technische Gültigkeit keine semantische Äquivalenz beweist: Zwei Formeln können in einer Stichprobe denselben Wert liefern und trotzdem unterschiedliche Bedeutungen haben, sobald sich Filterkontext oder Zeitraum ändern. Erst der strukturierte Vergleich auf Ebene von Population, Grain und Zeitlogik machte sichtbar, dass zwei der sieben Varianten tatsächlich unterschiedliche Geschäftsfragen beantworteten.

Der zweite Lernpunkt betraf die Rolle der Formelgeneratoren: Sie beschleunigten die Implementierung für zwei Plattformen erheblich, ersetzten aber an keiner Stelle die Owner-Entscheidung über Bedeutung, Grain oder Zertifizierung.

> **Hinweis:** Diese Story ist ein anonymisiertes, zusammengefasstes Beispiel zur Veranschaulichung der Methodik. Sie ersetzt keine Rechts-, Datenschutz- oder Vendor-Beratung und ist keine Fallstudie eines konkreten Kunden.

## Tools und nächste Schritte

- [Report Inventory](/tools/report-inventory) — Formeln, Grains und Widersprüche über Plattformen hinweg erfassen.
- [KPI Definition](/tools/kpi-definition) — die genehmigte Definition als `kpi-cards.csv` versionieren.
- [Power BI DAX Measure Generator](/tools/powerbi-dax-generator) und [Qlik Set Analysis Generator](/tools/qlik-set-analysis-generator) — kontrollierte Implementierungen aus dem genehmigten Vertrag erzeugen.
- Unsicher, ob ein Report-Inventar oder direkt eine Platzierungsentscheidung der richtige nächste Schritt ist? Der [Governance Advisor](/governance/berater) hilft bei der Einordnung.

## Verwandte Playbooks

- [Vom Report Inventory zur vertrauenswürdigen Kennzahl](/playbooks/from-report-inventory-to-trusted-metric) — die Methodik hinter Schritt 1 und 2.
- [Semantic Layer vs Measure im Report](/stories/semantic-layer-vs-report-measure) — die Platzierungsentscheidung hinter Schritt 3.
- [Wann BI-Formel-Generatoren der Governance helfen](/playbooks/when-to-use-bi-formula-generators) — die Grenze zwischen genehmigter Bedeutung und generierter Syntax hinter Schritt 5.
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics) — die abschließende Prüfung hinter Schritt 7.

Diese Story schließt die dreiteilige Proof-Serie ab: von Stakeholder-Interviews über eine SaaS-Quelle bis zu vertrauenswürdigen Kennzahlen — jeweils mit denselben Standard-Artefakten.
