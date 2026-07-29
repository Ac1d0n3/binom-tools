---
title: "Vom Report Inventory zur vertrauenswürdigen Kennzahl"
description: "Überführen Sie Report- und Formelinventare in priorisierte Metrikfamilien, genehmigte Verträge, validierte Implementierungen und migrierte Konsumenten."
author: Thomas Lindackers
tags:
  - Report Inventory
  - Trusted Metrics
  - Metric Governance
  - BI Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/from-report-inventory-to-trusted-metric-hero.png
series: bi-governance-decisions
seriesTitle: BI-Governance-Entscheidungen
seriesPart: 5
---

Ein Report Inventory ist Discovery-Evidenz, nicht das Governance-Ergebnis. Das Ergebnis ist ein kleineres, explizites und betriebenes Portfolio vertrauenswürdiger Kennzahlen mit genehmigten Definitionen, gesteuerten Implementierungen und migrierten Konsumenten.

## Problem

Inventare enden häufig bei Reportnamen, Ownern und Nutzung. Dieser Katalog kann eine Bereinigung unterstützen, erklärt aber nicht, warum zwei Reports unter demselben Label unterschiedliche Werte zeigen. Kennzahlen-Drift steckt meist in Ausdrücken, Quellfeldern, Basiskennzahlen, Filtern, Selections, Datumslogik, Währungsregeln, Ausschlüssen, Grain und dargestelltem Kontext.

String-Gleichheit ist keine semantische Gleichheit. Zwei syntaktisch unterschiedliche Formeln können denselben Vertrag implementieren, während identische Ausdrücke wegen Datenmodell, Filterkontext oder Quellgranularität unterschiedliche Bedeutungen haben. Auch die meistgenutzte Variante ist nicht automatisch autoritativ.

Das Inventar muss deshalb zu Entscheidungen über Metrikfamilien führen: beibehalten, konsolidieren, als Child Metric genehmigen, als lokale Ableitung behalten, korrigieren, migrieren, deprecaten oder ablehnen.

## Entscheidung

Erfassen Sie das Inventar in der Tiefe, die für einen Bedeutungsvergleich erforderlich ist. Dazu gehören Plattform, Workspace oder App, Report, Sheet oder Visual, Metriklabel, Ausdruck, semantisches Modell oder Datenquelle, Quellfelder, Basiskennzahlen, Filter, Zeitverhalten, Grain, dargestellte Dimensionen, Owner, Zielgruppe, Nutzung, Kritikalität, Zertifizierung und Lifecycle-Status.

Clustern Sie Einträge in Metrikfamilien. Trennen Sie exakte Kopien, Syntaxvarianten mit gleicher Bedeutung, genehmigte Child Metrics, kontextspezifische Ableitungen, konkurrierende fachliche Definitionen und unbekannte Varianten.

Vergleichen Sie jeden Kandidaten anhand von:

- Geschäftsfrage und vorgesehener Entscheidung;
- Population, Ein- und Ausschlüssen;
- Basisgranularität und Quellautorität;
- Aggregation und nicht-additivem Verhalten;
- Datum, Periode und Vergleichslogik;
- Filter- und Selection-Verhalten;
- Währung, Einheit, Vorzeichen und Nullbehandlung;
- Konsumenten, Owner und Genehmigungsstatus.

Priorisieren Sie anschließend nach Geschäftskritikalität, aktiven Konsumenten, Anzahl und Schwere der Varianten, beobachteten Widersprüchen, Owner-Bereitschaft, Quellenreife, Wiederverwendungswert und Migrationsaufwand.

Eine Owner-Entscheidung etabliert den kanonischen Vertrag. Technische Implementierung, Reconciliation und Consumer-Migration folgen. Löschung ist der letzte Schritt.

## Checkliste

- Enthält das Inventar Ausdrücke und semantischen Kontext statt nur Reportmetadaten?
- Werden Labels normalisiert, ohne daraus automatisch Gleichheit abzuleiten?
- Sind exakte Kopien, semantische Äquivalente, Child Metrics und konkurrierende Definitionen getrennt?
- Ist die autoritative Geschäftsfrage bekannt?
- Werden Grain, Quelle, Aggregation, Zeit, Filter, Ausschlüsse und Einheiten verglichen?
- Werden Nutzung und Entscheidungskritikalität gemessen?
- Kann ein verantwortlicher Owner den Kandidaten genehmigen oder ablehnen?
- Ist die Implementierungsplatzierung anhand der Semantic-Layer-Grenze entschieden?
- Sind Referenzdaten, Szenariotests und Reconciliation-Toleranzen definiert?
- Ist eine Parallelvalidierung geplant?
- Sind Konsumenten, Downstream-Exporte und Workbook-Abhängigkeiten inventarisiert?
- Sind Migrationsowner und Zieldatum benannt?
- Ist Deprecation blockiert, bis Ersatz und Consumer-Akzeptanz existieren?
- Werden Duplikatreduktion und Anteil migrierter Konsumenten gemessen?

Ein Inventar ist erfolgreich, wenn ungelöste Metrikfamilien abnehmen und vertrauenswürdige Implementierungen mehr Konsumenten gewinnen – nicht wenn eine Tabelle formal vollständig ist.

## Artefakt

Erstellen Sie pro Metrikfamilie einen **Trusted Metric Candidate Record** und verwalten Sie alle Records als Migrationsportfolio.

Pflichtfelder sind Metrikfamilien-ID, Labels und Aliase, gefundene Implementierungen, Geschäftsfragen, semantisches Vergleichsergebnis, vorgeschlagene und genehmigte Definition, Version, Basisgranularität, Quellautorität, Platzierung, Produktionsreferenzen, Owner, Steward, Custodians, Tests, Reconciliation, Zertifizierungsbedarf, genehmigte Ableitungen, Konfliktvarianten, Migrationsowner, Zieldatum, Ersatz, Ausnahmen und Review-Trigger.

Mögliche Entscheidungen:

- `jetzt zertifizieren`
- `Definition korrigieren`
- `Implementierungen konsolidieren`
- `genehmigte lokale Ableitung behalten`
- `Duplikat deprecaten`
- `bis Owner oder Quelle bereit ist zurückstellen`
- `nicht unterstützte Kennzahl ablehnen`

Portfoliometriken umfassen ungelöste Familien, Duplikatreduktion, Zeit bis zur Owner-Entscheidung, Evidenzvollständigkeit, Anteil migrierter Konsumenten, Nutzung deprecated Kennzahlen und überfällige Ausnahmen.

## Tools

Report Inventory sammelt Implementierungsevidenz über BI-Plattformen und Excel. KPI Definition wird eingesetzt, sobald ein Kandidat in den Owner-Review gelangt. Das BI Python Toolkit unterstützt Ausdrucksnormalisierung, Clustering, Dependency-Analyse und Wertabgleich.

Automatisierung darf Ähnlichkeit vorschlagen, aber keine semantische Äquivalenz entscheiden. Jedes automatisch erzeugte Cluster muss überprüfbar bleiben und die Links zu den Quellimplementierungen erhalten.

## Ressourcen

Nützliche interne Evidenzquellen sind BI-Metadaten-APIs, Exporte semantischer Modelle, Workbook- und App-Inventare, Query Logs, Lineage-Kataloge, Nutzungstelemetrie, Zertifizierungsdatensätze, Supportfälle und finanzielle Abstimmdateien.

Schützen Sie persönliche oder vertrauliche Workbook-Inhalte während der Erfassung. Extrahieren Sie nur Metadaten und Ausdrücke, die für den genehmigten Zweck erforderlich sind.

## Playbooks

- [KPI definieren](/playbooks/define-kpi)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)
- [Semantic Layer vs Measure im Report](/stories/semantic-layer-vs-report-measure)

Verwenden Sie deren Definitions- und Platzierungsentscheidungen wieder, statt im Inventarprozess eine zweite Metrikmethodik aufzubauen.

## Nächster Schritt

Wählen Sie ein Label, das in mehreren Reports vorkommt, etwa Net Revenue. Bilden Sie die vollständige Metrikfamilie, vergleichen Sie die Semantik, holen Sie eine Owner-Entscheidung ein, implementieren Sie den genehmigten Vertrag, führen Sie parallele Reconciliation durch und migrieren Sie einen realen Konsumenten. Verfeinern Sie mit diesem Ergebnis das Inventarschema, bevor Sie skalieren.
