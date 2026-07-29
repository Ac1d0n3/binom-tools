---
title: "Metrik-Zertifizierung in Tableau"
description: "Definieren Sie, was eine Tableau-Metrik-Zertifizierung belegen muss, verknüpfen Sie Governance-Evidenz mit konkreten Produktionsobjekten und betreiben Sie Zertifizierung als überprüfbaren Lebenszyklus."
author: Thomas Lindackers
tags:
  - Tableau
  - Metric Certification
  - Trusted Metrics
  - Data Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/tableau-metric-certification-hero.png
series: bi-governance-decisions
seriesTitle: BI-Governance-Entscheidungen
seriesPart: 3
---

Tableau bietet nützliche Vertrauenssignale, doch ein Badge ist nicht die fachliche Entscheidung. Eine veröffentlichte Datenquelle, eine virtuelle Verbindung, eine Datenbank, eine Tabelle oder eine Tableau-Pulse-Metrikdefinition kann im Rahmen der jeweiligen Tableau-Funktionen und Berechtigungen zertifiziert werden. Diese Plattformaktion verbessert die Auffindbarkeit. Sie legt aber weder die fachliche Definition fest noch genehmigt sie jede nachgelagerte Berechnung oder beweist eine einheitliche Interpretation durch die Konsumenten.

## Problem

Organisationen vermischen häufig mehrere Fragen: Ist die Quelle vertrauenswürdig? Ist die Berechnung genehmigt? Eignet sich die Arbeitsmappe für eine bestimmte Entscheidung? Ist die Tableau-Pulse-Definition zertifiziert? Diese Fragen betreffen unterschiedliche Objekte und Verantwortlichkeiten.

Eine zertifizierte veröffentlichte Datenquelle kann weiterhin Arbeitsmappenberechnungen mit abweichenden Filtern, unterschiedlichen Level-of-Detail-Ausdrücken, Table-Calculation-Annahmen oder lokalen Ausschlüssen versorgen. Umgekehrt kann eine fachlich korrekte Metrik in einer Quelle implementiert sein, die noch kein Plattform-Zertifizierungslabel besitzt. Der Governance-Nachweis muss deshalb den exakten Metrikvertrag und die konkrete Produktionsimplementierung identifizieren.

Tableau Pulse macht diese Trennung besonders deutlich. Eine Pulse-Metrikdefinition basiert auf genau einer veröffentlichten Datenquelle und beschreibt Kennzahl, Aggregation, Zeitdimension, Filter und Darstellungskontext der daraus erzeugten Metriken. Die Zertifizierung der Metrikdefinition ist von der Zertifizierung der Datenquelle getrennt. Diese Trennung gehört in das Betriebsmodell und darf nicht hinter einem allgemeinen Status „zertifiziert“ verschwinden.

## Entscheidung

Zertifizieren Sie eine Tableau-Metrik nur, wenn sowohl der semantische Vertrag als auch die Produktionsimplementierung genehmigt wurden.

Der semantische Vertrag muss Geschäftsfrage, Definition, Ein- und Ausschlüsse, Basisgranularität, Aggregation, Zeitverhalten, Filtersemantik, Einheit, Owner, erlaubte Nutzung und Version enthalten. Der Implementierungsnachweis muss auf die konkrete Tableau-Site, das Projekt, die Datenquelle, die Pulse-Metrikdefinition, die Arbeitsmappenberechnung oder ein anderes gesteuertes Produktionsobjekt verweisen.

Nutzen Sie Tableau-Zertifizierungslabels als Veröffentlichungssignal nach der Governance-Entscheidung. Verwenden Sie das Label nicht, um die Entscheidung zu ersetzen.

Dauerhafte Quellenharmonisierung, Ereignislogik, Historisierung und Grain-Kontrolle gehören nach upstream. Eine veröffentlichte Tableau-Datenquelle eignet sich für wiederverwendbare analytische Felder und Berechnungen, sofern diese Grenze bewusst gewählt wurde. Tableau Pulse eignet sich für Zeitreihenmetriken, die in das unterstützte Definitionsmodell passen. Arbeitsmappenlokale LOD-Ausdrücke, Table Calculations und blattspezifische Logik bleiben lokal, bis eine Promotion und Prüfung erfolgt.

Eine Zertifizierung gilt nur für einen definierten Geltungsbereich. Eine Metrik für ein internes operatives Dashboard ist nicht automatisch für externe Berichte, Vergütung, regulatorische Meldungen oder Executive Reporting freigegeben.

## Checkliste

- Sind Geschäftsfrage und Entscheidungskontext eindeutig?
- Sind Definition, Population, Ausschlüsse, Basisgranularität und Aggregation genehmigt?
- Sind Datum, Filter, LOD- und Table-Calculation-Semantik dokumentiert?
- Ist die autoritative Quelle oder das gesteuerte Datenprodukt identifiziert?
- Verweist der Datensatz auf das konkrete Tableau-Produktionsobjekt und die genaue Berechnung?
- Sind Extrakt- oder Live-Verhalten, Refresh-Erwartung und Freshness-Evidenz bekannt?
- Liegen Lineage-, Data-Quality- und Reconciliation-Nachweise vor?
- Sind Owner, Steward, technischer Custodian und Reviewer benannt?
- Wurden Berechtigungen und zulässige Konsumenten geprüft?
- Sind lokale Overrides und gleich benannte Arbeitsmappenberechnungen inventarisiert?
- Wird das passende Tableau-Zertifizierungssignal erst nach der Genehmigung gesetzt?
- Sind Version, Wirksamkeitsdatum, Prüftermin und Änderungsauslöser erfasst?
- Existiert ein Deprecation- und Consumer-Migrationspfad?

Eine Metrik ist nicht produktionsreif, nur weil die Formel läuft, ein Stichprobenergebnis passt oder die Quelle ein grünes Badge besitzt. Sie ist bereit, wenn die Evidenz die vorgesehene Geschäftsentscheidung trägt und die freigegebene Implementierung nachvollziehbar und erneut prüfbar ist.

## Artefakt

Erstellen Sie pro genehmigter Metrikversion einen **Tableau Metric Certification Record**.

| Feld | Erforderliche Evidenz |
|---|---|
| Metrikidentität | Stabile Metrik-ID, Name, Aliase und Version |
| Fachlicher Vertrag | Frage, Definition, Ein- und Ausschlüsse, Grain und Aggregation |
| Semantisches Verhalten | Datumsfeld, Filter, LOD-Verhalten, Table-Calculation-Annahmen, Einheiten und Nullbehandlung |
| Tableau-Implementierung | Site, Projekt, Objekttyp, Objekt-ID oder URL, genaue Berechnung oder Pulse-Definition |
| Quellgrenze | Veröffentlichte Datenquelle, virtuelle Verbindung oder vorgelagertes gesteuertes Datenprodukt |
| Plattformsignale | Datenquellen- und Pulse-Definitionszertifizierung getrennt dokumentiert |
| Qualität | Testergebnisse, Freshness, Reconciliation-Ergebnis und genehmigte Toleranz |
| Ownership | Verantwortlicher Owner, Steward, Implementierungs-Custodian und Reviewer |
| Zugriff | Berechtigungen, freigegebene Zielgruppe und Schutzanforderungen |
| Lebenszyklus | Wirksamkeitsdatum, Review-Datum, Trigger, Ausnahmen, Ersatz und Deprecation-Status |

Geeignete Zustände sind `vorgeschlagen`, `Evidenz unvollständig`, `Remediation`, `genehmigt`, `zertifiziert`, `Rezertifizierung fällig`, `deprecated` und `retired`. Ein Plattform-Badge darf nie die einzige Evidenz für einen dieser Zustände sein.

Änderungsauslöser sind unter anderem Quellen- oder Feldänderungen, Berechnungs- oder Filteränderungen, Projektmigrationen, Refresh-Fehler, Qualitätsverletzungen, Berechtigungsänderungen, Ownership-Wechsel und neue Nutzungskontexte.

## Tools

Nutzen Sie das KPI-Definition-Tool, um den semantischen Vertrag vor Erstellung oder Zertifizierung des Tableau-Objekts zu erfassen. Report Inventory identifiziert gleich benannte Berechnungen, arbeitsmappenlokale Varianten und abhängige Konsumenten. Der Tableau Calculation Generator darf erst nach genehmigter Definition und Platzierung eingesetzt werden. Das BI Python Toolkit unterstützt Vergleichsdatensätze, Reconciliation und Migrationsanalyse.

Generierte Berechnungen sind Implementierungskandidaten. Sie benötigen weiterhin technische Prüfung, Ausführungstests, Reconciliation und Akzeptanz durch den Owner.

## Ressourcen

- Tableau-Hilfe: Erstellen von Metriken mit Tableau Pulse — https://help.tableau.com/current/online/de-de/pulse_create_metrics.htm
- Tableau-Hilfe: Use Certification to Help Users Find Trusted Data — https://help.tableau.com/current/online/en-us/datasource_certified.htm
- Tableau-Hilfe: About Virtual Connections and Data Policies — https://help.tableau.com/current/online/en-gb/dm_vconn_overview.htm
- Tableau Metadata API: MetricDefinition — https://help.tableau.com/current/api/metadata_api/en-us/reference/metricdefinition.doc.html

Prüfen Sie Produktfunktionen und Lizenzierung bei der Implementierung erneut, da Tableau Cloud, Tableau Server, Catalog, Data Management und Pulse kein identisches Zertifizierungsverhalten besitzen.

## Playbooks

- [KPI definieren](/playbooks/define-kpi)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)
- [Semantic Layer vs Measure im Report](/stories/semantic-layer-vs-report-measure)

Diese Playbooks definieren fachlichen Vertrag und Platzierungsentscheidung. Diese Story ergänzt die Tableau-spezifische Veröffentlichungs- und Lebenszyklusgrenze.

## Nächster Schritt

Wählen Sie eine hochwertige Tableau-Metrik mit sichtbarer Wiederverwendung oder widersprüchlichen Arbeitsmappenimplementierungen. Erstellen Sie den Zertifizierungsdatensatz, gleichen Sie das Produktionsergebnis mit einem genehmigten Referenzdatensatz ab, setzen Sie das passende Tableau-Signal und definieren Sie den ersten Rezertifizierungstrigger. Beginnen Sie nicht mit einer pauschalen Massenzertifizierung von Quellen oder Arbeitsmappen.
