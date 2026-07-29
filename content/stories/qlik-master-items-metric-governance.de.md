---
title: "Master Items und Kennzahlen-Governance in Qlik"
description: "Nutzen Sie Qlik Master Items als gesteuerte App-Objekte, ohne Wiederverwendung mit unternehmensweiter Kennzahlen-Governance zu verwechseln."
author: Thomas Lindackers
tags:
  - Qlik
  - Master Items
  - Metric Governance
  - Trusted Metrics
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/qlik-master-items-metric-governance-hero.png
series: bi-governance-decisions
seriesTitle: BI-Governance-Entscheidungen
seriesPart: 4
---

Qlik Master Items reduzieren Duplikate und erleichtern die Wiederverwendung genehmigter Dimensionen, Kennzahlen und Visualisierungen innerhalb einer App. Das ist wertvolle Implementierungskontrolle. Allein daraus entsteht jedoch noch kein unternehmensweites Kennzahlen-Governance-System.

## Problem

Kennzahlenlogik kann in Qlik über vorgelagerte Datenprodukte, Load Script, assoziatives Datenmodell, Variablen, Master Measures, Master Dimensions und chartlokale Ausdrücke verteilt sein. Kopien einer App erzeugen zusätzliche Varianten. Ohne definierte Grenze behandeln Teams eine app-lokale Master Measure möglicherweise als unternehmensweit genehmigte Kennzahl, obwohl fachliche Definition, Quellautorität, Owner, Tests und Cross-App-Lebenszyklus nicht gesteuert sind.

Innerhalb einer App können Änderungen an einem verknüpften Master Item auf die verwendenden Visualisierungen wirken. Daraus folgt nicht, dass das Master Item unabhängige Kopien oder andere Apps automatisch aktualisiert. Cross-App-Konsistenz benötigt einen gesteuerten Deployment-Mechanismus, ein Template, einen versionierten Export oder einen vergleichbaren Release-Prozess.

Qlik Cloud unterstützt außerdem Beschreibungen, Tags und Verknüpfungen zwischen Master Items und Begriffen eines Business Glossary. Diese Funktionen verbessern Bedeutung und Auffindbarkeit, ersetzen aber nicht den genehmigten Metrikvertrag.

## Entscheidung

Verwenden Sie eine Qlik Master Measure, wenn eine Berechnung innerhalb einer gesteuerten App wiederverwendet wird und ihr semantischer Vertrag bereits genehmigt ist.

Der Vertrag enthält Metrik-ID und Version, fachliche Definition, Basisgranularität, Aggregation, Quellautorität, Selection- und Set-Analysis-Verhalten, Zeitregeln, Einheiten, Owner, Tests und erlaubte Nutzung. Die Master Measure ist anschließend die app-spezifische Implementierung dieses Vertrags.

Trennen Sie die Verantwortungen:

- Vorgelagerte Datenprodukte verantworten Quellenintegration, historische Zuordnung und dauerhafte Geschäftsregeln.
- Load Script und Datenmodell verantworten Feldaufbereitung, Assoziationen und technische Hilfslogik.
- Master Items enthalten genehmigte wiederverwendbare app-lokale Kennzahlen, Dimensionen und Visualisierungsmuster.
- Variablen dürfen gesteuerte Parameter oder explizite Ausdrucksfragmente enthalten, aber keine unkontrollierten fachlichen Definitionen verstecken.
- Chartlokale Ausdrücke eignen sich für Visualisierungsverhalten, temporäre Analyse oder genehmigte lokale Ableitungen.

Promoten Sie einen Chartausdruck zur Master Measure, wenn Wiederverwendung, Kritikalität oder Kopieren eine app-lokale Steuerung erfordert. Eine app-lokale Master Measure wird erst dann zur gesteuerten Cross-App-Implementierung, wenn Deployment, Dependency-Tests, Release-Versionierung und Consumer-Migration definiert sind.

## Checkliste

- Existiert ein genehmigter Metrikvertrag mit stabiler ID und Version?
- Ist die fachliche Bedeutung vom Qlik-Ausdruck getrennt?
- Sind Quellautorität und Basisgranularität upstream gesteuert?
- Sind Selection State, Set Analysis, Alternate States und Zeitverhalten explizit?
- Ist das Master Item bei Bedarf mit Beschreibung, Tags oder Glossary Term verknüpft?
- Sind App-ID, Objekt-ID, Release-Version und Deployment-Pfad dokumentiert?
- Sind alle Chart-, Variablen- und Child-Measure-Abhängigkeiten inventarisiert?
- Sind App-Kopien und abweichende Varianten bekannt?
- Sind zulässige lokale Ableitungen und Overrides definiert?
- Liegen Regressionstests, Szenariotests und Reconciliation vor?
- Sind Owner, Steward und Implementierungs-Custodian benannt?
- Wird Cross-App-Wiederverwendung durch ein gesteuertes Template oder Deployment getragen?
- Sind Ersatz- und Deprecation-Auswirkungen vor einer Löschung bekannt?

Nicht jeder Ausdruck muss promotet werden. Lokale Berechnungen mit engem Präsentationszweck können lokal bleiben, wenn sie gekennzeichnet, verantwortet und von Enterprise-Truth-Ansprüchen ausgeschlossen sind.

## Artefakt

Erstellen Sie für jede gesteuerte Qlik-Implementierung einen **Qlik Metric Governance Record**.

| Feld | Inhalt |
|---|---|
| Metrikidentität | Name, stabile ID, Aliase und genehmigte Version |
| Definition | Geschäftsfrage, Formelkomponenten, Ein- und Ausschlüsse |
| Grain und Autorität | Basisgranularität, autoritative Quelle und vorgelagertes Datenprodukt |
| Qlik-Referenz | Tenant oder Umgebung, Space, App-ID, Master-Item-Objekt-ID und Ausdrucksreferenz |
| Selection-Semantik | Set Analysis, Selection-Verhalten, Alternate States und Zeitkontext |
| Metadaten | Beschreibung, Tags, Glossary-Verknüpfung und Formatierung |
| Abhängigkeiten | Felder, Basiskennzahlen, Variablen, Child Measures, Charts und App-Kopien |
| Verantwortlichkeit | Data Owner, Steward, technischer Custodian und Release Owner |
| Validierung | Regressionstests, Szenariotests, Reconciliation und Toleranz |
| Veröffentlichung | Versioniertes Artefakt, Deployment-Methode, App-Template und Release Notes |
| Lebenszyklus | Zertifizierungsstatus, Override-Policy, Review-Datum, Trigger und Ersatz |

Der Datensatz liefert eine genehmigte App-Implementierung, eine Cross-App-Reuse-Entscheidung, Migrationsmaßnahmen für chartlokale Kopien, einen Dependency- und Release-Plan sowie einen benannten Lifecycle Owner.

Ein Generator kann aus einem genehmigten Vertrag einen Ausdruck erzeugen. Er kann weder die autoritative Definition auswählen noch den Unternehmensumfang bestimmen oder seine Ausgabe selbst zertifizieren.

## Tools

Nutzen Sie KPI Definition für den fachlichen Vertrag. Report Inventory und das BI Python Toolkit helfen bei wiederholten Ausdrücken, Variablen, Kopien und Consumer-Abhängigkeiten. Der Qlik Set Analysis Generator wird erst eingesetzt, nachdem Grain, Quelle, Filter und Selection-Semantik genehmigt wurden.

Speichern Sie generierte Ausdrücke, Dependency-Listen, Testszenarien und Warnungen gemeinsam. Reiner Code genügt nicht, da auch ein syntaktisch korrekter Ausdruck die falsche Bedeutung implementieren kann.

## Ressourcen

- Qlik Cloud Hilfe: Arbeiten mit Master Items — https://help.qlik.com/de-DE/cloud-services/Subsystems/Hub/Content/Sense_Hub/Assets/work-with-master-items.htm
- Qlik Cloud Hilfe: Tagging von Master Items — https://help.qlik.com/de-DE/cloud-services/Subsystems/Hub/Content/Sense_Hub/Assets/tag-master-items.htm
- Qlik Cloud Hilfe: Begriffe mit Master Items verknüpfen — https://help.qlik.com/de-DE/cloud-services/Subsystems/Hub/Content/Sense_Hub/Catalog/BusinessGlossary/business-glossaries-master-items.htm
- Qlik Cloud Hilfe: Master Measure erstellen — https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Measures/create-master-measure-field.htm

Prüfen Sie Qlik Cloud und clientverwaltetes Qlik Sense bei der Implementierung getrennt, insbesondere Berechtigungen, Glossary-Funktionen und Deployment-Optionen.

## Playbooks

- [KPI definieren](/playbooks/define-kpi)
- [Keeping Business Logic Outside BI Apps](/playbooks/keeping-business-logic-outside-bi-apps)
- [The Missing Pieces of Trusted Metrics](/playbooks/missing-pieces-trusted-metrics)

Diese Playbooks definieren vorgelagerte und semantische Grenzen. Diese Story legt fest, wie der genehmigte Vertrag in Qlik implementiert und gesteuert wird.

## Nächster Schritt

Wählen Sie eine kritische Qlik-App. Inventarisieren Sie Chartausdrücke, Variablen und Master Measures einer Metrikfamilie. Genehmigen Sie einen Vertrag, überführen Sie die ausgewählte Implementierung in eine gesteuerte Master Measure, dokumentieren Sie Abhängigkeiten, validieren Sie das Ergebnis und definieren Sie das Deployment in weitere kontrollierte Apps.
