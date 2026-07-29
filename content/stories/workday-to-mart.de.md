---
title: "Workday → Mart: Workforce-Headcount-Snapshot"
description: "Aus dem freigegebenen Workday-Source-Scope einen periodischen Headcount-Snapshot-Mart mit Worker-, Position- und Organisations-Grain sowie versionierten KPI-Karten ableiten."
author: Thomas Lindackers
tags:
  - Workday
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
seriesPart: 4
hero: images/playbooks/workday-to-mart-hero.png
---

Workday führt Worker, Position und Organisation als effective-dated Objekte: Jede Änderung besitzt ein Gültigkeitsdatum, und mehrere Zustände können nebeneinander existieren. Ein Headcount-Mart muss diese Zeitsemantik bewusst abbilden, statt sie in einen einfachen aktuellen Zustand zu komprimieren. Dieser Guide zeigt, wie aus dem freigegebenen Source Scope ein periodischer Snapshot-Mart entsteht, der Headcount, FTE und Vakanzen auf einer konsistenten, wiederholbaren Grundlage berichtet.

Der Snapshot-Ansatz ist bewusst gewählt: Workforce-Kennzahlen werden fast immer zu einem Stichtag oder für eine Periode gebraucht, nicht als kontinuierlicher Eventstrom.

## Problem

Ein aktueller Worker-Export beantwortet nur, wie die Belegschaft heute aussieht. Historische oder periodische Fragen scheitern an typischen Fehlern.

- Die aktuelle Supervisory Organization wird rückwirkend auf vergangene Perioden angewendet, sodass eine Reorganisation die Historie verfälscht.
- Mehrere Positionen desselben Workers (Primary und Additional Job) werden in einen Current Record kollabiert, wodurch Headcount und FTE nicht mehr übereinstimmen.
- Future-Dated Changes — bereits erfasste, aber noch nicht wirksame Änderungen — werden zu früh sichtbar, wenn Effective-Date-Logik fehlt.
- Compensation- und Payroll-Daten werden ungeprüft in denselben Mart geladen wie Headcount-Kennzahlen, obwohl beide unterschiedliche Zweckfreigaben und Security Domains benötigen.

Der freigegebene Source Scope aus [Welche Workday-Objekte laden — und welche skippen](/playbooks/workday-tables-for-analytics) entscheidet, welche Objekte und Felder überhaupt zulässig sind. Die Snapshot-Logik und der Grain für den Headcount-Mart sind eine eigene, hier getroffene Entscheidung.

## Entscheidung

```text
Freigegebener Source Scope
→ Workforce-Entscheidung und Snapshot-Grain
→ Fact- und Dimension-Kandidaten
→ KPI-Karten auf demselben Grain
→ Mart Design Brief
```

Die zentrale Entscheidung lautet: Der Headcount-Mart ist ein periodischer Snapshot, keine Transaktionstabelle. Für jeden Snapshot-Zeitpunkt — üblicherweise Monatsende — wird eine Zeile pro aktivem Worker und Position erzeugt, basierend auf dem zu diesem Zeitpunkt effective-dated Zustand. Correction- und Rescind-Verhalten werden explizit behandelt: Eine rückwirkende Korrektur verändert einen bereits erzeugten Snapshot nicht automatisch, sondern löst eine dokumentierte Reprocessing-Regel aus.

Compensation, Payroll und andere Financial-Detail-Daten werden nicht Teil dieses Marts. Sie benötigen eine eigene Zweckfreigabe, eigene Population und eigene Security Domain und gehören in ein separates Produkt.

## Grain und Fact/Dim-Kandidaten

**Primärer Grain:** eine Zeile pro Worker, Position und Snapshot-Datum.

| Rolle | Workday-Objekt | Verwendung |
|---|---|---|
| Fact-Quelle | Worker / Position zum Snapshot-Datum | Headcount-Zählung, FTE, Vakanzstatus |
| Dimension: Organisation | Supervisory Organization | Hierarchieebene zum Snapshot-Zeitpunkt |
| Dimension: Rolle | Job Profile | Funktionsfamilie und Level |
| Dimension: Standort | Location | Land, Standort, Region |
| Dimension: Kostenstelle | Cost Center / Company | Kostenzuordnung |
| Dimension: Worker-Typ | Worker Type (Employee, Contingent Worker) | getrennte Behandlung in Kennzahlen |
| Dimension: Datum | Snapshot-Kalender | Monats- oder Periodenende, keine freie Tagesauswahl ohne Regel |

Headcount und FTE sind additive Fakten innerhalb eines Snapshots, aber nicht über Snapshots hinweg summierbar — sie werden je Periode neu berechnet, nicht kumuliert. Vakante Positionen sind ein eigener Fact-Kandidat, kein Attribut des Worker-Fact. Turnover- und Hiring-Kennzahlen benötigen ein separates Event-Fact (eine Zeile pro Eintritt, Austritt oder Wechsel), weil sie Bewegungen und keinen Zustand messen — sie werden nicht aus der Differenz zweier Snapshots geraten, sondern aus den zugrundeliegenden Events abgeleitet.

## PII und Skip

Ein Headcount-Mart benötigt keine direkten Worker-Identifikatoren, Kontaktdaten oder Compensation-Details. Aggregierte oder pseudonymisierte Worker-Referenzen genügen für Headcount- und FTE-Kennzahlen. Health-, Benefits- und Document-Content-Daten bleiben grundsätzlich außerhalb, ebenso unbeschränkte Freitextfelder aus Worker-Profilen.

Die vollständige Objekt- und Feldentscheidung — inklusive Security Domains und Feldminimierung — steht in [Welche Workday-Objekte laden — und welche skippen](/playbooks/workday-tables-for-analytics). Nutze den [PII Recommend Generator](/tools/pii-recommend-generator), falls einzelne Worker-Attribute doch benötigt werden.

## Standard-KPIs

Auf dem Snapshot-Grain lassen sich verlässlich definieren:

- **Headcount** — Anzahl aktiver Worker je Snapshot-Datum, Organisation und Standort.
- **FTE** — Summe der Full-Time-Equivalent-Werte je Snapshot-Datum und Organisation.
- **Vacancy Rate** — Anteil offener Positionen an allen budgetierten Positionen.
- **Span of Control** — durchschnittliche Anzahl direkter Berichte je Manager.

Turnover- und Time-to-Fill-Kennzahlen gehören auf ein separates Event-Fact und werden hier nur als Ausblick genannt. Jede Kennzahl benötigt eine eigene KPI-Karte mit Population, Snapshot-Logik und Owner. Nutze [KPI definieren](/playbooks/define-kpi) für die Methodik und den [KPI Definition](/tools/kpi-definition)-Generator zur Erfassung.

## Artefakt

- **`source-scope.csv` / `source-scope.md`** — freigegebene Worker-, Position- und Organisationsobjekte samt Security-Domain-Entscheidung.
- **`kpi-cards.csv`** — Headcount-, FTE- und Vacancy-KPIs mit Snapshot-Logik und Owner.
- **`mart-design-brief.md`** — Snapshot-Grain, Effective-Date- und Correction-Verhalten, Fact-/Dimension-Kandidaten und explizit ausgeschlossene Compensation-Daten.
- **`dq-backlog.csv`** — offene Datenqualitätsfragen, etwa unvollständige Effective-Date-Ketten oder ungeklärte Multiple-Job-Fälle.

Dieselben Dateinamen gelten über alle Guides dieser Serie hinweg.

## Tools und nächste Schritte

- [Source Scope Builder](/tools/source-scope-builder) — Worker-, Position- und Organisationsobjekte als `source-scope.csv` dokumentieren.
- [PII Recommend Generator](/tools/pii-recommend-generator) — Feldrisiko für Worker-Attribute klassifizieren.
- [Mart Design Brief Generator](/tools/mart-design-brief-generator) — Snapshot-Grain, Fakten, Dimensionen und Scope-Out als `mart-design-brief.md` freigeben.
- [KPI Definition](/tools/kpi-definition) — Standard-KPIs als `kpi-cards.csv` versionieren.

Ob Workday überhaupt die richtige nächste Quelle ist und wer die Snapshot-Entscheidung freigeben muss, klärt der [Governance Advisor](/governance/berater).

## Verwandte Playbooks

- [Welche Workday-Objekte laden — und welche skippen](/playbooks/workday-tables-for-analytics) — die vorgelagerte Load/Skip-Entscheidung für Objekte, Felder und Security Domains.
- [Vom Stakeholder-Interview zum Tabellenmodell](/playbooks/from-stakeholder-interview-to-table-model) — die allgemeine Methodik für Grain, Fakten, Dimensionen und den Mart Design Brief.
- [Welche Quelle zuerst laden?](/playbooks/which-source-to-load-first) — die Portfolio-Entscheidung, falls Workday noch gegen andere Kandidaten priorisiert werden muss.

Das vollständige Supplier-Profil mit Produktkontext, Compliance-Hinweisen und weiteren Ressourcen steht in der [Supplier Library: Workday](/suppliers/workday).
