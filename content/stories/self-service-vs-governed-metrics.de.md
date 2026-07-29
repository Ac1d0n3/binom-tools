---
title: "Self-Service-Kennzahlen vs. governed Metrics"
description: "Schützen Sie gemeinsame und kritische Kennzahlen und erhalten Sie zugleich analytische Freiheit durch klare Metrikzonen, Entscheidungsrechte und Promotion-Trigger."
author: Thomas Lindackers
tags:
  - Self-Service BI
  - Governed Metrics
  - Metric Policy
  - Operating Model
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/self-service-vs-governed-metrics-hero.png
series: bi-governance-decisions
seriesTitle: BI-Governance-Entscheidungen
seriesPart: 6
---

Self-Service und gesteuerte Kennzahlen sind keine Gegensätze. Die praktikable Alternative zu zentraler Genehmigung jeder Formel und unbeschränkter Kennzahlenerstellung ist ein gestuftes Betriebsmodell mit expliziten Entscheidungsrechten.

## Problem

Wenn jede Berechnung ein zentrales Gremium benötigt, verlangsamt sich Exploration und lokale Arbeit wandert in den Schatten. Wenn „Self-Service“ bedeutet, dass jeder Ersteller jede Formel unter jedem Label veröffentlichen darf, verlieren genehmigte Kennzahlen ihre Autorität und experimentelle Berechnungen gelangen unbemerkt in Executive-, Finanz- oder Kundenberichte.

Es fehlt nicht noch ein Freigabeworkflow für jeden analytischen Gedanken. Es fehlt die klare Trennung zwischen geschützten Basiskennzahlen, kontrollierten Ableitungen und lokalen Experimenten.

Nutzer müssen außerdem erkennen, welche Änderungen eine Kennzahl erhalten und welche sie neu definieren. Eine andere Darstellung ist etwas anderes als eine Änderung von Population, Grain, Zeitlogik oder Ausschlüssen. Ohne sichtbare Grenzen erzeugen auch gut gemeinte Anpassungen konkurrierende Bedeutungen.

## Entscheidung

Definieren Sie drei Metrikzonen.

**Governed Base Metrics** besitzen genehmigte Definition und Grain, einen benannten Owner, eine gemeinsame Implementierung, gemeinsame Tests und einen geschützten Namen. Sie werden genutzt, wenn konsistente unternehmensweite Bedeutung erforderlich ist.

**Controlled Derived Metrics** werden aus genehmigten Basen nach erlaubten Mustern aufgebaut. Zulässige Dimensionen, Filter, Szenarien und Zeiträume sind explizit. Sie besitzen Owner und Geltungsbereich und können nach Review wiederverwendbar werden.

**Local Exploratory Metrics** gehören zu einem Report, Workbook oder einer Analyse. Sie sind sichtbar als experimentell gekennzeichnet, haben Owner und Ablaufdatum, beanspruchen keine Enterprise Truth und sind aus zertifiziertem Reporting ausgeschlossen.

Die Governance-Intensität steigt mit Wiederverwendung, Kritikalität und externer Konsequenz. Eine Promotion wird ausgelöst, wenn eine lokale Kennzahl kopiert, in mehreren gesteuerten Produkten verwendet, für Executive- oder regulatorische Entscheidungen genutzt wird, gemeinsame Grain- oder Reconciliation-Regeln benötigt oder eine wesentliche gemeinsame Entscheidung verändert.

Lokal bleiben darf eine Kennzahl bei reinem Präsentationsverhalten, kurzfristiger Hypothese, einmaliger Analyse oder genehmigter Ableitung, die den Basisvertrag nicht neu definiert.

## Checkliste

Definieren Sie je Metrikklasse:

- wer sie erstellen darf;
- wer sie veröffentlichen darf;
- welche Quellkennzahlen zulässig sind;
- welche Filter, Dimensionen und Szenarien variieren dürfen;
- erforderliche Namen und Statuslabels;
- Owner- und Ablaufanforderungen;
- Test- und Reconciliation-Niveau;
- zulässige Konsumenten;
- Zertifizierungsfähigkeit;
- Promotion-Trigger;
- Ausnahmegenehmiger;
- Deprecation- und Migrationsregeln.

Zusätzlich ist zu klären:

- Welche Metriknamen sind geschützt?
- Welche Änderungen sind eine verbotene Neudefinition?
- Darf eine lokale Variante optisch wie eine zertifizierte Kennzahl wirken?
- Wie werden strittige Definitionen eskaliert?
- Was geschieht bei fehlendem Owner?
- Wie wird eine dringende Ausnahme genehmigt und beendet?
- Wie werden experimentelle Kennzahlen aus Executive Reports entfernt?
- Wie können Nutzer eine neue genehmigte Ableitung beantragen, ohne auf ein zentrales Implementierungsteam zu warten?

Die zentrale Governance-Funktion definiert Grenzen und löst Konflikte. Sie darf nicht zur Implementierungswarteschlange für jede Formel werden.

## Artefakt

Erstellen Sie eine **Self-Service Metric Policy** mit Entscheidungsmatrix.

Die Zeilen enthalten Governed Base Metrics, Approved Derived Metrics, Local Exploratory Metrics und verbotene oder konfliktäre Neudefinitionen. Die Spalten erfassen Definitionsautorität, Erstellungsrechte, Veröffentlichungsrechte, erlaubte Quellen, zulässige Filter und Dimensionen, Benennung, Status, Owner, Evidenz, Zertifizierung, Ablauf, Promotion, Ausnahmegenehmigung und Deprecation.

Führen Sie ein Register experimenteller Kennzahlen mit Name, Ersteller, Owner, Report oder Workbook, Zweck, Basiskennzahlen, Erstellungsdatum, Ablaufdatum, Consumer Scope und Promotion-Status.

Nutzen Sie sichtbare Labels wie `governed`, `approved derivative`, `experimental`, `deprecated` und `prohibited conflict`. Lokale Varianten dürfen zertifizierte Labels nicht unqualifiziert wiederverwenden.

Messen Sie Wiederverwendung gesteuerter Basen, Anzahl promoteter Ableitungen, abgelaufene Experimente, ungelöste Definitionskonflikte und Executive Reports mit lokalen Kennzahlen.

## Tools

KPI Definition wird für Kennzahlen eingesetzt, die in die gesteuerte oder genehmigte Ableitungszone gelangen. Report Inventory findet kopierte Formeln, wiederverwendete Labels und lokale Measures, die ihre vorgesehene Grenze überschritten haben. Formelgeneratoren dürfen genehmigte Child-Measure-Muster implementieren, müssen aber bei fehlendem Grain, Quelle, Filter oder Ownership stoppen.

Die Tool-Oberfläche sollte anzeigen, was Nutzer verändern dürfen und welche Änderungen einen neuen Metrikvertrag benötigen.

## Ressourcen

Relevante interne Ressourcen sind Metrikkatalog, Dokumentation semantischer Modelle, Report Inventory, Zertifizierungsdatensätze, Ausnahmeregister, Glossary, Nutzungstelemetrie und Consumer-Migrationspläne.

Die Policy sollte plattformneutral bleiben. Plattformberechtigungen und Zertifizierungsmechanismen implementieren Teile der Policy, definieren aber nicht die fachlichen Entscheidungsrechte.

## Playbooks

- [KPI definieren](/playbooks/define-kpi)
- [Semantic Layer vs Measure im Report](/stories/semantic-layer-vs-report-measure)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)

Diese Playbooks definieren Vertrag und Evidenz, sobald eine Kennzahl promotet wird.

## Nächster Schritt

Wählen Sie zehn Kennzahlen aus aktuellen Self-Service-Reports. Ordnen Sie sie den drei Zonen zu, identifizieren Sie eine verbotene Neudefinition, veröffentlichen Sie erlaubte Ableitungsmuster und geben Sie jeder experimentellen Kennzahl Owner und Ablaufdatum. Nutzen Sie die gefundenen Konflikte zur Verfeinerung der Policy.
