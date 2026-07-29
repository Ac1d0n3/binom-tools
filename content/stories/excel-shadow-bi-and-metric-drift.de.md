---
title: "Excel-Schatten-BI und Kennzahlen-Drift"
description: "Unterscheiden Sie legitime Excel-Analyse von kritischer Schatten-BI und verlagern Sie wiederverwendbare Wahrheit upstream, ohne den Nutzerworkflow zu zerstören."
author: Thomas Lindackers
tags:
  - Excel
  - Shadow BI
  - Metric Drift
  - Consumer Governance
publishedAt: 2026-07-29
category: Data Governance
order: -1
hero: images/playbooks/excel-shadow-bi-and-metric-drift-hero.png
series: bi-governance-decisions
seriesTitle: BI-Governance-Entscheidungen
seriesPart: 7
---

Excel ist nicht das Governance-Problem. Das Problem beginnt, wenn kritische Definitionen, Quellkopien, Refresh-Schritte, Anpassungen und Genehmigungen ausschließlich in unkontrollierten Workbooks existieren.

## Problem

Ein nützliches lokales Workbook kann schrittweise zum System of Record für eine wiederkehrende Entscheidung werden. Es wird kopiert, per E-Mail verteilt, in Zellen angepasst, mit Lookups und Makros erweitert und von einer einzigen Person betrieben. Danach zirkulieren mehrere Versionen ohne stabilen Refresh- oder Freigabevertrag.

Nicht die Dateiendung `.xlsx` erzeugt das Risiko. Ausschlaggebend sind versteckte Autorität, Geschäftskritikalität, wiederholte Verteilung, manuelle Transformation, schwache Zugriffskontrolle und Single-Person-Abhängigkeit.

Ein pauschales Excel-Verbot scheitert meist, weil es die Oberfläche entfernt, ohne den Geschäftsprozess zu ersetzen. Nutzer bauen den Workflow an anderer Stelle neu auf oder arbeiten mit Kopien weiter. Umgekehrt erlaubt uneingeschränkter Fortbestand Kennzahlen-Drift in Formeln, Filtern, Datumsregeln, Wechselkursen, Mappings und manuellen Anpassungen.

## Entscheidung

Etablieren Sie gesteuerte Koexistenz.

Halten Sie vertrauenswürdige Datenprodukte und gemeinsame Basiskennzahlen upstream. Binden Sie Excel über kontrollierte Semantic-Model-Verbindungen, zertifizierte Views, gesteuerte Extrakte oder zentral veröffentlichte Templates an. Erlauben Sie lokale Analyse und Darstellung, kennzeichnen Sie jedoch workbooklokale Ableitungen und trennen Sie manuelle Eingaben von vertrauenswürdigen Werten.

Klassifizieren Sie Workbooks als:

- gesteuertes verbundenes Workbook;
- kontrolliertes Template;
- legitime lokale Analyse;
- kritischer Schattenprozess.

Die Kritikalität richtet sich nach Entscheidung, Zielgruppe, Frequenz, finanzieller oder regulatorischer Wirkung, Konsumentenzahl und Single-Person-Abhängigkeit.

Verschieben Sie wiederverwendbare Logik upstream, wenn mehrere Workbooks sie benötigen, sie kritische Entscheidungen verändert, wiederholt abgestimmt werden muss oder das Workbook zur einzigen autoritativen Implementierung geworden ist. Excel kann Consumer Interface bleiben, wenn seine Flexibilität echten Wert schafft und die Vertrauensgrenze explizit ist.

## Checkliste

Inventarisieren Sie mehr als den Workbook-Namen:

- Datenbank-, Semantic-Model- und Dateiverbindungen;
- Power-Query-Quellen und Transformationsschritte;
- Workbook-Datenmodell, Beziehungen, DAX Measures und Calculated Columns;
- Pivot Calculated Fields, Filter, Slicer und Refresh-Verhalten;
- Zellformeln, Named Ranges, Lookup-Tabellen, Kurse und Mappings;
- Makros, externe Links, manuelle Eingaben, Anpassungen und Copy-Paste-Schritte;
- Owner und Backup Owner;
- Entscheidung, Prozess, Konsumenten und Kritikalität;
- Quellautorität, Refresh und Verteilung;
- Metriklabels, Upstream-Referenzen und konkurrierende Versionen;
- PII, Zugriff, Aufbewahrung und Abhängigkeiten.

Suchen Sie Drift in Definitionen, Populationen, Datumslogik, Filtern, Wechselkursen, Mappings, Vorzeichen, Einheiten und manuellen Overrides. Prüfen Sie persönliche oder vertrauliche Inhalte nur mit genehmigtem Zugriff.

Definieren Sie vor der Migration Referenzergebnis, Reconciliation-Toleranz, Parallelvalidierungszeitraum und Consumer-Akzeptanz. Retiren Sie kein Workbook, bevor abhängige Entscheidungen und Ersatzprozesse bekannt sind.

## Artefakt

Erstellen Sie ein **Excel Shadow BI and Metric Drift Register** mit einer Zeile pro Workbook oder Workbook-Familie.

Pflichtfelder sind stabile Workbook-ID, Owner, Backup Owner, unterstützte Entscheidung, Konsumenten, Kritikalität, Verbindung, Quellautorität, Refresh, Verteilung, eingebettete Kennzahlen, Formelpositionen, manuelle Anpassungen, konkurrierende Versionen, Trusted-Metric-Referenzen, Drift-Befunde, Reconciliation, Schutzanforderungen, Disposition, Zielarchitektur, Migrationsowner, Datum, Validierungskriterien und Review-Trigger.

Zulässige Dispositionen:

- `retain governed`
- `stabilize`
- `migrate reusable logic`
- `replace distribution`
- `retire`

Ergebnisse sind das kritische Shadow-BI-Portfolio, der Metric-Drift-Backlog, kontrollierte Templates, Upstream-Migrationsmaßnahmen, Consumer-Abhängigkeiten und Retirement-Evidenz.

Ein Workbook darf erhalten bleiben, wenn sein Consumer-Wert hoch ist, Owner, Verbindung und Refresh kontrolliert sind, lokale Logik gekennzeichnet ist und kritische Ergebnisse abgestimmt werden.

## Tools

Report Inventory registriert Workbooks und ihre Konsumenten. KPI Definition wird für Kennzahlen genutzt, die upstream verschoben werden. Das BI Python Toolkit unterstützt Formelvergleich, Clustering von Workbook-Familien und Reconciliation-Datensätze.

Automatisierung sollte zuerst Metadaten und nicht Inhalte erfassen. Nutzen Sie bei sensiblen Workbooks genehmigte Scanregeln und Least-Privilege-Zugriff.

## Ressourcen

Nützliche interne Quellen sind Microsoft-365- oder Fileshare-Inventare, Power-Query-Metadaten, Semantic-Model-Logs, DLP-Klassifizierungen, Workbook Owner, Finanzabstimmungen und Prozessdokumentation.

Die Zielarchitektur muss kontrollierte Verteilung einfacher machen als den Versand statischer Exporte per E-Mail.

## Playbooks

- [Semantic Layer vs Measure im Report](/stories/semantic-layer-vs-report-measure)
- [KPI definieren](/playbooks/define-kpi)
- [Operating and Governing the Platform](/playbooks/operating-and-governing-the-platform)

Verwenden Sie deren Lifecycle-, Zugriffs- und Consumer-Migrationskontrollen wieder.

## Nächster Schritt

Wählen Sie eine geschäftskritische Workbook-Familie. Inventarisieren Sie ihre vollständige Anatomie, identifizieren Sie den final autoritativen Wert, gleichen Sie ihn mit einer Upstream-Referenz ab, klassifizieren Sie jede lokale Formel und entscheiden Sie eine Disposition. Erhalten Sie den Nutzerworkflow, während Sie die erste wiederverwendbare Regel upstream verschieben.
