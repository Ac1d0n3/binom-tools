---
title: "SAP S/4 → Mart: klarer Sales-/Auftrags-Ausschnitt"
description: "Aus dem freigegebenen SAP-S/4-Source-Scope einen eng geschnittenen Sales-Order-Mart mit Auftragspositions-Grain, Fact/Dim-Kandidaten und versionierten KPI-Karten ableiten — bewusst kein vollständiges ERP-Modell."
author: Thomas Lindackers
tags:
  - SAP S/4HANA
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
seriesPart: 3
hero: images/playbooks/sap-s4-to-mart-hero.png
---

Eine SAP-S/4-Landschaft deckt Sales, Logistik, Einkauf und Finance ab. Dieser Guide baut bewusst keinen vollständigen ERP-Mart. Er zeigt, wie aus dem freigegebenen Source Scope ein eng geschnittener Ausschnitt entsteht: Sales Order und Auftragsposition als Fact-Quelle, ergänzt um die Beziehung zum Billing Document, wenn realisierter statt zugesagter Umsatz benötigt wird.

Der schmale Zuschnitt ist kein Kompromiss, sondern die eigentliche Empfehlung. Ein Order-to-Cash-Mart, der Header, Item und Billing sauber trennt, liefert früher belastbare Kennzahlen als ein Versuch, die gesamte Auftragsabwicklung in einem Schritt zu modellieren.

## Problem

Der physische S/4-Tabellenkatalog ist Implementierungsevidenz, kein analytisches Modell. Ein objektgetriebener Ansatz führt zu vorhersehbaren Fehlern.

- Header-Werte wie der Gesamtnettowert eines Auftrags werden über Positionen wiederholt und dann versehentlich mehrfach summiert.
- Order- und Billing-Beträge werden als derselbe Fakt behandelt, obwohl ein Auftrag mehrfach oder teilweise fakturiert werden kann — Order Value und Billed Value sind unterschiedliche Wahrheiten zu unterschiedlichen Zeitpunkten.
- Organisationsstrukturen wie Sales Organization, Distribution Channel oder Company Code werden nicht konsistent auf Kopf- und Positionsebene geführt.
- Released CDS Views, native Tabellen und Custom Extraktoren liefern konkurrierende Repräsentationen desselben Geschäftsvorfalls, ohne dass eine Autorität dokumentiert ist.

Der freigegebene Source Scope aus [Welche SAP-S/4-Tabellen für Analytics laden — und welche skippen](/playbooks/sap-s4-tables-for-analytics) entscheidet, welche Views, Extraktoren und Felder überhaupt zulässig sind. Grain, Fakten und die Order-to-Billing-Beziehung sind eine eigene Modellierungsentscheidung, die dieser Guide trifft.

## Entscheidung

```text
Freigegebener Source Scope
→ Document Flow und Ziel-Grain
→ Fact- und Dimension-Kandidaten
→ KPI-Karten auf demselben Grain
→ Mart Design Brief
```

Der Document Flow für Order-to-Cash lautet in vereinfachter Form:

```text
Sales Order (Header)
→ Sales Order Item
→ Delivery
→ Billing Document
→ Accounting Entry
```

Für einen ersten Mart wird nur ein Ausschnitt dieses Flows freigegeben: Sales Order Header und Item als primäre Fact-Quelle für Order Value und Backlog, sowie optional die Beziehung zum Billing Document, wenn realisierter Umsatz statt zugesagtem Auftragswert benötigt wird. Delivery- und Goods-Movement-Ereignisse bleiben außerhalb des Scopes, solange keine Logistik-Kennzahl explizit gefordert ist.

Order Value und Billed Value werden nicht in derselben Kennzahl vermischt. Eine KPI wie Book-to-Bill vergleicht beide Werte explizit auf vergleichbarer Periodenbasis, statt sie stillschweigend gleichzusetzen.

## Grain und Fact/Dim-Kandidaten

**Primärer Grain:** eine Zeile pro Sales-Order-Position (VBAP-Ebene, konkret über die freigegebene CDS View oder den Extraktor).

**Ergänzender Grain (Umsatzrealisierung):** eine Zeile pro Billing-Document-Position.

| Rolle | SAP-Objekt / Quelle | Verwendung |
|---|---|---|
| Fact-Quelle (Order-Grain) | Sales Order Header/Item (VBAK/VBAP-Äquivalent, released CDS View) | Auftragsmenge, Nettowert je Position |
| Fact-Quelle (Billing-Grain) | Billing Document Header/Item | fakturierte Menge und fakturierter Wert |
| Dimension: Kunde | Sold-to Party / Customer | Segment, Region, Vertriebsweg |
| Dimension: Material | Material / Product | Produkthierarchie und Warengruppe |
| Dimension: Organisation | Sales Organization, Distribution Channel, Company Code | konsistent auf Kopf- und Positionsebene geführt |
| Dimension: Datum | Kalender-Dimension | Auftragsdatum, gewünschtes Lieferdatum, Fakturadatum getrennt gehalten |
| Referenz: Document Flow | Order-to-Billing-Zuordnung | verhindert Doppelzählung zwischen Order und Billing |

Auftragsmenge und Nettowert sind additive Fakten auf Positionsebene. Der Auftragsstatus ist ein Statusattribut. Currency- und Unit-of-Measure-Umrechnung werden explizit dokumentiert, bevor über Organisationseinheiten oder Perioden aggregiert wird — Beträge aus unterschiedlichen Währungen dürfen nicht ungeprüft addiert werden.

## PII und Skip

Ein Sales-Order-Mart enthält primär kommerzielle, keine personenbezogenen Daten. Wo Ansprechpartner-Referenzen oder Kontaktfelder aus dem Customer-Master einfließen, gilt dieselbe Feld-Allowlist-Regel wie in jeder anderen Quelle: nur freigegebene Felder, keine unbeschränkten Freitextfelder oder Notizen.

Die vollständige Objekt- und Feldentscheidung — inklusive Technical Logs, obsoleter Views und Textfeldern — steht in [Welche SAP-S/4-Tabellen für Analytics laden — und welche skippen](/playbooks/sap-s4-tables-for-analytics). Nutze den [PII Recommend Generator](/tools/pii-recommend-generator) für die Feldklassifikation, falls Kundenkontakte einbezogen werden.

## Standard-KPIs

Auf dem gewählten Order-/Billing-Ausschnitt lassen sich verlässlich definieren:

- **Order Backlog** — Summe offener, noch nicht fakturierter Auftragswerte je Periode.
- **Book-to-Bill Ratio** — Verhältnis von Auftragseingang zu fakturiertem Umsatz im selben Zeitraum.
- **Revenue by Product** — fakturierter Wert je Material und Periode.
- **Order Fulfillment Rate** — Anteil vollständig fakturierter Positionen an allen Positionen im Zeitraum.

Jede Kennzahl benötigt eine eigene KPI-Karte mit Zähler, Nenner, Zeitlogik, Währungsregel und Owner. Nutze [KPI definieren](/playbooks/define-kpi) für die Methodik und den [KPI Definition](/tools/kpi-definition)-Generator zur Erfassung.

## Artefakt

- **`source-scope.csv` / `source-scope.md`** — freigegebene Views, Extraktoren, Felder und die Order-to-Billing-Zuordnung.
- **`kpi-cards.csv`** — die Standard-KPIs mit Formel, Grain-Referenz, Währungsregel und Owner.
- **`mart-design-brief.md`** — Document Flow, Grain, Fact-/Dimension-Kandidaten und explizit ausgeschlossene Prozessschritte (Delivery, Goods Movement) für den Sales-Order-Mart.

Dieselben Dateinamen gelten über alle Guides dieser Serie hinweg.

## Tools und nächste Schritte

- [Source Scope Builder](/tools/source-scope-builder) — freigegebene Views, Extraktoren und Felder als `source-scope.csv` dokumentieren.
- [PII Recommend Generator](/tools/pii-recommend-generator) — Feldrisiko klassifizieren, sobald Kundenkontakte einbezogen werden.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — Grain, Fakten, Dimensionen und Scope-Out als `mart-design-brief.md` freigeben.
- [KPI Definition](/tools/kpi-definition) — Standard-KPIs als `kpi-cards.csv` versionieren.

Ob überhaupt ein schmaler Sales-Ausschnitt oder ein anderer SAP-Prozess der richtige Startpunkt ist, klärt der [Governance Advisor](/governance/berater). Dieser Guide setzt eine bereits getroffene Priorisierung fort.

## Verwandte Playbooks

- [Welche SAP-S/4-Tabellen für Analytics laden — und welche skippen](/playbooks/sap-s4-tables-for-analytics) — die vorgelagerte Load/Skip-Entscheidung für Views, Extraktoren und Felder.
- [Vom Stakeholder-Interview zum Tabellenmodell](/playbooks/from-stakeholder-interview-to-table-model) — die allgemeine Methodik für Grain, Fakten, Dimensionen und den Mart Design Brief.
- [Welche Quelle zuerst laden?](/playbooks/which-source-to-load-first) — die Portfolio-Entscheidung, falls SAP S/4 noch gegen andere Kandidaten priorisiert werden muss.

Das vollständige Supplier-Profil mit Produktkontext, Compliance-Hinweisen und weiteren Ressourcen steht in der [Supplier Library: SAP S/4HANA](/suppliers/sap-s4hana).
