---
title: "Wann BI-Formel-Generatoren der Governance helfen"
description: "Nutzen Sie BI-Formel-Generatoren als kontrollierte Compiler genehmigter Metrikverträge, nicht als Autorität für fachliche Bedeutung oder Zertifizierung."
author: Thomas Lindackers
tags:
  - BI Formula Generators
  - Metric Contract
  - DAX
  - Tableau
  - Qlik
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/when-to-use-bi-formula-generators-hero.png
series: bi-governance-decisions
seriesTitle: BI-Governance-Entscheidungen
seriesPart: 8
---

Formelgeneratoren können Governance stärken, wenn sie einen genehmigten Metrikvertrag in konsistente Implementierungen, Dokumentation und Tests übersetzen. Sie erzeugen Governance-Schulden, wenn sie fachliche Bedeutung erfinden sollen.

## Problem

Eine vage Anforderung wie „Erstelle eine Revenue-KPI“ definiert weder Population noch Grain, Basiskennzahlen, Datumsverhalten, Filter, Ausschlüsse, Währung, Owner oder erwartete Ergebnisse. Ein Generator kann trotzdem syntaktisch gültigen DAX-, Tableau- oder Qlik-Code erzeugen. Dieser Code kann laufen und dennoch falsch sein.

Cross-Tool-Übersetzung erhöht das Risiko. DAX-Filterkontext, Tableau-LOD- und Table-Calculation-Verhalten sowie Qlik-Selection- und Set-Analysis-Semantik sind keine austauschbare Syntax. Gleiche Werte in einer Stichprobe beweisen keine semantische Äquivalenz.

Bulk-Generierung kann außerdem Dutzende ungenutzte Measures, versteckte Abhängigkeiten und unversionierte Varianten erzeugen. Ändert sich generierter Code ohne Aktualisierung von Metrikversion und Evidenz, ist die Implementierung nicht mehr nachvollziehbar.

## Entscheidung

Verwenden Sie einen Formelgenerator erst nach Genehmigung von Metrikdefinition und Platzierung.

Der minimale Input-Vertrag enthält:

- Metrik-ID und genehmigte Version;
- Geschäftsfrage und Definition;
- Formelkomponenten und genehmigte Basiskennzahlen;
- Basisgranularität und erlaubte Dimensionen;
- Quellfelder und Modellreferenzen;
- Datums-, Filter- und Selection-Semantik;
- Null-, Währungs-, Einheiten- und Vorzeichenverhalten;
- zulässige lokale Variation;
- Owner, Reviewer und erlaubte Nutzung;
- erwartete Szenarien, Referenzergebnisse und Toleranz.

Der Generator darf daraus plattformspezifische Formelkandidaten, Inline-Erklärungen, Dependency-Listen, Metadaten, Testszenarien, Migrationssnippets und Review-Checklisten erzeugen.

Die Entscheidungsgrenze bleibt menschlich. Menschen genehmigen fachliche Bedeutung, Quellautorität, Grain, Platzierung, Filter, Ausschlüsse, Zeitregeln, Ownership, Ausnahmen, Zertifizierung und Retirement. Der Generator wendet genehmigte Muster an, markiert fehlende Inputs und stoppt bei unvollständiger Evidenz.

Geeignete Einsatzfälle sind Boilerplate, kontrollierte Child Metrics, Syntaximplementierung, Dokumentation, Testgenerierung und Vergleich bereits genehmigter Implementierungen. Ungeeignet sind mehrdeutige KPI-Anforderungen, Quellenauswahl, Grain-Reparatur, Autoritätskonflikte, Zertifizierung und unkontrollierte Massenerzeugung.

## Checkliste

Vor der Generierung:

- Ist der Metrikvertrag genehmigt und versioniert?
- Sind Zielplattform und Objekttyp bekannt?
- Ist das Zielmodell, die Datenquelle oder App identifiziert?
- Sind Basiskennzahlen, Felder und Dependencies genehmigt?
- Sind Grain, Aggregation und dimensionaler Scope explizit?
- Sind Zeit-, Filter-, Selection- und Calculation Context definiert?
- Sind Einheit, Währung, Vorzeichen und Nullregeln festgelegt?
- Sind lokale Varianten und Performance-Grenzen beschrieben?
- Liegen Owner, technischer Reviewer und Referenzergebnisse vor?

Nach der Generierung:

- Wurde die Ausgabe von einem Plattformspezialisten geprüft?
- Läuft sie im Zielmodell?
- Decken Szenariotests Totale, Filter, Zeitwechsel, Nullwerte und Grenzfälle ab?
- Liegt die Reconciliation innerhalb der genehmigten Toleranz?
- Wurden Security- und Access-Auswirkungen geprüft?
- Wurde Performance getestet?
- Sind Warnungen und offene Annahmen geschlossen?
- Sind Produktionsobjekt und Release-Version dokumentiert?
- Wurde der Einfluss auf Zertifizierung bewertet?
- Ist die Generated-from-Referenz erhalten?

Syntaktische Gültigkeit ist keine Governance-Evidenz. Eine generierte Formel bleibt Implementierungskandidat, bis Validierung und Genehmigung einen Production Release erzeugen.

## Artefakt

Erstellen Sie einen kombinierten **Formula Generation Request and Validation Record**.

Der Request dokumentiert Metrik-ID, Version, Zielplattform, Objekttyp, Modell oder App, Formelkomponenten, Basiskennzahlen, Grain, Aggregation, Dimensionen, Zeitverhalten, Filterverhalten, Einheitenregeln, lokale Variation, Performance-Anforderungen, Owner, Reviewer und erwartete Referenzergebnisse.

Generierte Outputs umfassen Formel, Abhängigkeiten, Dokumentation, Metadaten, Tags, Testfälle, Migrationshinweise, Warnungen und offene Annahmen.

Die Validierung dokumentiert Syntax- und Ausführungsergebnis, Szenariotests, Reconciliation und Toleranz, Security-Auswirkung, Performance, Reviewer-Freigabe, Produktionsobjekt, Release-Version, Zertifizierungsauswirkung und Review-Trigger.

Der Datensatz muss eine deterministische Beziehung zwischen Input-Vertrag, Generatorversion, generierter Ausgabe und freigegebenem Produktionsobjekt erhalten. Eine Regenerierung nach Vertragsänderung erfordert eine neue Metrik- oder Implementierungsversion.

## Tools

Relevante Tools sind Power BI DAX Generator, Tableau Calculation Generator, Qlik Set Analysis Generator, KPI Definition und Report Inventory.

Ein guter Generator sollte:

- strukturierte Inputs verlangen statt nur Freitext;
- unvollständige Verträge ablehnen;
- plattformspezifische Annahmen offenlegen;
- Code und Evidenz aus demselben Input erzeugen;
- Metrik-ID und Version erhalten;
- Review unterstützen und sich nicht selbst genehmigen;
- unkontrollierte Massenerzeugung vermeiden.

## Ressourcen

Nutzen Sie Plattformdokumentation für aktuelle Berechnungssemantik und Objektfunktionen. Pflegen Sie genehmigte interne Muster für DAX Measures, Tableau Calculations und Qlik Expressions. Versionieren Sie Generator-Templates, Prompts, Regeln und Testbibliotheken.

Verschieben Sie Quellenintegration, dauerhafte Historisierung oder Grain-Reparatur nicht in BI-Ausdrücke, nur weil ein Generator dafür Syntax erzeugen kann.

## Playbooks

- [KPI definieren](/playbooks/define-kpi)
- [Semantic Layer vs Measure im Report](/stories/semantic-layer-vs-report-measure)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)

Diese Playbooks erzeugen den genehmigten Input. Der Generator beginnt erst nach diesen Entscheidungen.

## Nächster Schritt

Nehmen Sie einen genehmigten Metrikvertrag und generieren Sie Implementierungen für zwei BI-Engines. Dokumentieren Sie alle Annahmen, führen Sie denselben Szenariodatensatz aus, gleichen Sie die Ergebnisse ab und erklären Sie, warum sich die Implementierungen unterscheiden. Verwenden Sie dieses kontrollierte Beispiel als erstes Generator-Template.
