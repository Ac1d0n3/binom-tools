---
title: "Proof: Von der SaaS-Quelle zum freigegebenen Pilot-Mart"
description: "Ein anonymisiertes Beispiel, wie ein Team eine Salesforce-Instanz priorisierte, einen Source Scope mit PII-Klassifikation freigab und daraus einen ersten Pipeline-Mart baute."
author: Thomas Lindackers
tags:
  - Proof
  - Source Scope
  - PII
  - Salesforce
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
seriesPart: 2
hero: images/playbooks/proof-saas-source-to-pilot-mart-hero.png
---

Ein Analytics-Team mit drei möglichen ersten Quellen — Salesforce, ein Ticketing-System und ein ERP-Exportordner — musste entscheiden, wo die erste Ingestion beginnt. Diese anonymisierte Story zeigt den Weg von dieser Priorisierungsfrage über eine dokumentierte Load/Skip-Entscheidung bis zu einem freigegebenen Pilot-Mart, ohne Kundennamen oder reale Zahlen.

## Ausgangslage

Das Team hatte begrenzte Kapazität für genau eine vollständige erste Quelle in diesem Quartal. Alle drei Kandidaten hatten Fürsprecher: Salesforce war am sichtbarsten für die Geschäftsführung, das Ticketing-System hatte die „sauberste“ API, und der ERP-Export war bereits als Datei verfügbar. Bequemlichkeit allein sollte aber nicht die Entscheidung treiben.

Zusätzlich bestand Unsicherheit über den Umfang: Eine frühere, informelle Diskussion hatte vorgeschlagen, „einfach alle Salesforce-Objekte“ zu laden, um spätere Nacharbeit zu vermeiden — ohne zu klären, welche Objekte tatsächlich einen Analytics-Zweck hatten.

## Schritte

1. **Portfolio-Entscheidung statt Bauchgefühl.** Mit der Methodik aus [Welche Quelle zuerst laden?](/playbooks/which-source-to-load-first) wurden alle drei Kandidaten nach Decision Value und Source Readiness getrennt bewertet. Salesforce gewann nicht wegen der Sichtbarkeit, sondern weil eine benannte Entscheidung — Pipeline-Steuerung nach Stage und Owner — bereits klar formuliert war und Ownership, Zugriff und Grain-Wissen vorhanden waren.

2. **Skip-Muster generisch vorab prüfen.** Bevor objektspezifisch entschieden wurde, nutzte das Team [SaaS-Exporte: Tabellen, die man nicht laden sollte](/playbooks/saas-exports-tables-to-skip), um die generischen Kategorien zu kennen: UI-Caches, doppelte Snapshots, umfangreiche Audit-Logs und unbeschränkter Freitext sollten unabhängig vom Hersteller kritisch geprüft werden.

3. **Salesforce-Objekte klassifizieren.** Mit [Welche Salesforce-Tabellen für Analytics laden](/playbooks/salesforce-tables-for-analytics) wurden `Opportunity`, `Account` und `User` als Must-have eingestuft, `Contact` als Conditional mit Feld-Allowlist, und `Task`/`Event` sowie Notizen und Anhänge bewusst zurückgestellt, weil kein benannter Aktivitäts-Use-Case existierte.

4. **Source Scope dokumentieren.** Der [Source Scope Builder](/tools/source-scope-builder) hielt für jedes Objekt Zweck, Beitrag zum Grain, Zeitbedarf, PII-Risiko und Entscheidung fest — inklusive der explizit ausgeschlossenen Objekte und ihres Review Triggers.

5. **PII vor dem Load klassifizieren.** Für `Contact`- und `User`-Felder lieferte der [PII Recommend Generator](/tools/pii-recommend-generator) eine erste Risikoeinschätzung. Freitextfelder aus `Task` und `Event` blieben außerhalb des Scopes, bis ein Owner einen konkreten Zweck benennt.

6. **Vom Scope zum Mart.** Mit dem freigegebenen Scope folgte das Team [Salesforce → Mart: Grain, Fakten und KPI-Karten](/playbooks/salesforce-to-mart): Opportunity-Grain wurde als primärer Grain gewählt, Fact- und Dimension-Kandidaten benannt und die Standard-Pipeline-KPIs als Karten erfasst.

7. **Mart Design Brief freigeben.** Der [Mart Design Brief Generator](/tools/mart-design-brief-generator) verband Source Scope und KPI-Karten zu einem Brief mit Grain, Historienverhalten und Scope-Out, bevor die erste physische Tabelle gebaut wurde.

## Artefakte

- **`source-scope.csv`** — Salesforce-Objekte mit Decision (Include/Defer/Exclude), Begründung und Review Trigger.
- **`kpi-cards.csv`** — Pipeline Value, Win Rate und Average Deal Size als versionierte KPI-Karten auf Opportunity-Grain.
- **`mart-design-brief.md`** — Grain, Fact-/Dimension-Kandidaten und Scope-Out für den Pilot-Mart, freigegeben vor dem Tabellenbau.

Die vollständige Liste der Standard-Dateinamen steht in [Welche Artefakte entstehen?](/playbooks/which-artifacts-you-get).

## Lernerfolg

Der wichtigste Effekt der Portfolio-Entscheidung war, dass Salesforce nicht „weil es sichtbar ist“, sondern mit einer dokumentierten Begründung gewann — inklusive der zwei zurückgestellten Kandidaten mit klaren Prerequisites für eine spätere Bewertung. Das machte die Entscheidung für alle drei Fürsprecher nachvollziehbar, auch für die, deren Quelle nicht zuerst startete.

Der zweite Lernpunkt betraf den Scope selbst: Ohne die Objekt-für-Objekt-Klassifikation wäre wahrscheinlich der ursprüngliche Vorschlag „alle Objekte laden“ umgesetzt worden. Der freigegebene Scope reduzierte die tatsächlich geladenen Objekte deutlich, ohne dass der Pilot-Mart dadurch etwas verlor — der Grain brauchte diese Objekte schlicht nicht.

> **Hinweis:** Diese Story ist ein anonymisiertes, zusammengefasstes Beispiel zur Veranschaulichung der Methodik. Sie ersetzt keine Rechts-, Datenschutz- oder Vendor-Beratung und ist keine Fallstudie eines konkreten Kunden.

## Tools und nächste Schritte

- [Source Scope Builder](/tools/source-scope-builder) — Objekte, Felder und Beziehungen als `source-scope.csv` dokumentieren.
- [PII Recommend Generator](/tools/pii-recommend-generator) — Feldrisiko vor dem Load klassifizieren.
- [KPI Definition](/tools/kpi-definition) — Standard-KPIs als `kpi-cards.csv` versionieren.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — Grain, Fakten und Scope-Out als `mart-design-brief.md` freigeben.
- Unsicher, welche Quelle zuerst dran ist? Der [Governance Advisor](/governance/berater) führt durch die Priorisierung.

## Verwandte Playbooks

- [Welche Quelle zuerst laden?](/playbooks/which-source-to-load-first) — die Portfolio-Methodik hinter Schritt 1.
- [SaaS-Exporte: Tabellen, die man nicht laden sollte](/playbooks/saas-exports-tables-to-skip) — die generischen Skip-Muster hinter Schritt 2.
- [Welche Salesforce-Tabellen für Analytics laden](/playbooks/salesforce-tables-for-analytics) — die vollständige Objektklassifikation hinter Schritt 3.
- [Salesforce → Mart: Grain, Fakten und KPI-Karten](/playbooks/salesforce-to-mart) — der Guide hinter Schritt 6 und 7.

Teil 3 dieser Serie zeigt, wie ein bestehendes Report-Chaos in vertrauenswürdige, zertifizierte Kennzahlen überführt wird.
