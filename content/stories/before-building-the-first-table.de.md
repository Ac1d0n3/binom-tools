---
title: "Bevor die erste Tabelle entsteht"
description: "Beginne mit Entscheidungen, nicht mit Tools"
author: "Thomas Lindackers"
category: Datenarchitektur
tags:
  - building-modern-data-warehouse
  - data-warehouse
  - data-product
  - data-architecture
  - governance
order: -1
publishedAt: 2026-07-18
series: building-modern-data-warehouse
seriesPart: 1
seriesTitle: Ein modernes Data Warehouse aufbauen
hero: "images/playbooks/bp-start-hero.png"
---

**Beginne mit Entscheidungen, nicht mit Tools.**

Ein modernes Data Warehouse sollte nicht mit der Auswahl einer Plattform, einem Schichtnamen oder der ersten Ingestion-Pipeline beginnen. Es sollte mit einer Entscheidung beginnen, die das Business treffen muss.

Diese Unterscheidung klingt einfach, verändert aber die gesamte Architektur. Startet ein Projekt mit einem Tool, werden häufig früh große Datenmengen geladen und technische Schichten aufgebaut, während KPI-Definitionen, Ownership, Qualitätsregeln und Sicherheitsanforderungen vertagt werden. Das Ergebnis kann viele Tabellen und Pipelines enthalten und trotzdem keine einzige vertrauenswürdige Antwort liefern.

Der bessere Startpunkt ist eine klar eingegrenzte Business-Frage und das kleinste governte Datenprodukt, das diese Frage durchgängig beantworten kann.

Dieses Prinzip gilt für Greenfield- und Brownfield-Umgebungen, für Cloud- und On-Premises-Plattformen sowie für kleine Teams und große Unternehmensprogramme. Microsoft Fabric, Snowflake, Databricks, dbt, SQL Server, Qlik, Power BI und Excel sind mögliche Bestandteile der Umsetzung. Keines dieser Produkte ist der architektonische Ausgangspunkt.

## Das Problem: Architektur vor Klarheit

Typische Warehouse-Initiativen beginnen mit Fragen wie:

- Welche Cloud-Plattform sollen wir nutzen?
- Benötigen wir Bronze, Silver und Gold?
- Welches Ingestion-Tool soll die Quellsysteme laden?
- Brauchen wir ein Lakehouse, ein Warehouse oder beides?
- Welches BI-Tool soll zum Standard werden?

Diese Fragen sind berechtigt, aber sie sind nicht die ersten Fragen.

Bevor eine Plattform bewertet werden kann, muss das Team verstehen, welches Ergebnis die Lösung liefern soll. Andernfalls wird die Architektur für ein noch nicht definiertes Problem optimiert. Daraus entstehen häufig vorhersehbare Folgen:

- Zu viele Quellen werden geladen, bevor ihr Zweck bekannt ist.
- Pipelines werden für Daten gebaut, die kein Consumer benötigt.
- Fachlogik wird in Qlik-Skripten, Power-BI-Modellen, Excel-Dateien, SQL-Views und Notebooks dupliziert.
- Ähnliche Tabellen werden für unterschiedliche Reports mehrfach erzeugt.
- Datenqualität wird erst geprüft, nachdem Nutzer Fehler gefunden haben.
- Niemand ist eindeutig für den KPI oder das Datenprodukt verantwortlich.
- Die Plattform wächst in die Breite, während das Vertrauen niedrig bleibt.

![Starte nicht mit der Plattform](images/playbooks/bp-start-img1-de.png)

## Architekturprinzip: Entscheidungen zuerst, Umsetzung zuletzt

Die architektonische Reihenfolge sollte lauten:

1. Business-Entscheidung definieren.
2. KPI und fachliche Bedeutung definieren.
3. Granularität festlegen.
4. Nur die benötigten Quellen und Felder identifizieren.
5. Aktualität und Historisierung festlegen.
6. Qualität, Sicherheit und Ownership definieren.
7. Das kleinste vollständige Datenprodukt bauen.
8. Die Umsetzung anhand des tatsächlichen Bedarfs auswählen oder erweitern.

Technologie ist dabei nicht unwichtig. Sie wird später ausgewählt, weil ihre Eignung erst anhand klarer Anforderungen sinnvoll beurteilt werden kann.

Ein Datenprodukt für die tägliche Vertriebssteuerung benötigt vielleicht eine tägliche Aktualisierung, moderate Datenmengen und eine governte SQL-Tabelle. Ein operativer Near-Real-Time-Anwendungsfall kann dagegen Streaming, Event-Verarbeitung und andere Service Levels erfordern. Die Architektur folgt dem Entscheidungskontext.

## Mit einem vertikalen Datenprodukt starten

Die erste sinnvolle Lieferung sollte vertikal statt breit sein.

Ein vertikales Datenprodukt verbindet eine Business-Frage mit allen technischen Schritten, die für ihre Beantwortung erforderlich sind:

```flowchart
Business-Frage
Benötigte Quellen
Ingestion
Standardisierung
Fachlogik
Qualitätstests
Governtes Datenprodukt
Qlik / Power BI / Excel
```

Dieser vollständige Pfad ist wertvoller als eine breite, aber unfertige Plattform. Er beweist, dass das Team Daten von der Quelle bis zur Entscheidung liefern kann – einschließlich Ownership, Qualität und Nutzung.

![Starte mit einem vertikalen Datenprodukt](images/playbooks/bp-start-img2-de.png)

Das Ziel ist nicht das kleinstmögliche technische Objekt. Das Ziel ist der kleinste sinnvolle, vertrauenswürdige und wiederverwendbare Umfang.

Ein Minimum Viable Data Product sollte daher:

- auf eine konkrete Entscheidung fokussiert sein;
- ausschließlich die benötigten Daten verwenden;
- von Beginn an governte Strukturen besitzen;
- möglichst von mehreren Consumern wiederverwendbar sein;
- nicht von einer einzelnen BI-Anwendung abhängig sein;
- bei einem realen Bedarf erweiterbar bleiben.

## Konkretes Beispiel: tägliche Vertriebssteuerung

Angenommen, das Business möchte folgende Frage beantworten:

> Wie entwickelt sich der tägliche Nettoumsatz nach Kunde, Produkt und Land unter Berücksichtigung von Stornierungen und nachträglichen Änderungen?

Bevor die erste Tabelle erzeugt wird, müssen die folgenden Entscheidungen getroffen werden.

| Entscheidungsbereich | Erforderliche Klärung | Beispielentscheidung |
|---|---|---|
| Business-Frage | Welche Entscheidung soll das Ergebnis unterstützen? | Tägliche Vertriebssteuerung und Erkennung negativer Abweichungen |
| KPI-Definition | Was zählt fachlich als Umsatz? | Nettoumsatz auf Auftragspositionsebene nach Stornierungen |
| Granularität | Was stellt eine Zeile dar? | Eine Auftragsposition pro Geschäftstag |
| Quellsysteme | Welche Systeme enthalten die benötigten Daten? | Aufträge, Kunden, Produkte und Länderreferenzen |
| Aktualität | Wann müssen die Daten verfügbar sein? | Täglich bis 07:00 Uhr |
| Historisierung | Müssen Änderungen rekonstruiert werden können? | Gültige Änderungen an Auftragspositionen und Stammdaten erhalten |
| Datenqualität | Welche Regeln sind verpflichtend? | Gültige Auftrags-, Kunden- und Produkt-ID, Land, Betrag und Änderungsdatum |
| Sicherheit | Welche Daten müssen eingeschränkt werden? | Kundenattribute und wirtschaftlich sensible Werte |
| Verantwortliche | Wer genehmigt Bedeutung und Änderungen? | Sales Owner für den KPI, Data Owner für das Datenprodukt |
| Consumer | Welche Tools nutzen das Ergebnis? | Qlik, Power BI, Excel und operative Reports |

Diese Entscheidungen bestimmen das technische Modell.

Ein erstes praktisches Datenprodukt könnte folgende Felder enthalten:

| Feld | Zweck |
|---|---|
| `business_date` | Reporting- und Aktualisierungsdatum |
| `order_id` | Fachlicher Schlüssel des Auftrags |
| `order_line_id` | Identifikator der granularitätsbestimmenden Position |
| `customer_id` | Governte Kundenreferenz |
| `product_id` | Governte Produktreferenz |
| `country_code` | Standardisiertes Land |
| `gross_revenue` | Umsatz vor Berücksichtigung der Stornierungslogik |
| `cancellation_flag` | Kennzeichnet stornierte Auftragspositionen |
| `net_revenue` | Zentral definierter KPI-Beitrag |
| `changed_at` | Änderungszeitpunkt aus der Quelle |
| `quality_status` | Ergebnis der verpflichtenden Qualitätsprüfungen |

Die zentrale Fachregel könnte einmalig in SQL umgesetzt werden:

```sql
select
    cast(order_date as date) as business_date,
    order_id,
    order_line_id,
    customer_id,
    product_id,
    country_code,
    gross_revenue,
    cancellation_flag,
    case
        when cancellation_flag = 1 then 0
        else gross_revenue
    end as net_revenue,
    changed_at
from standardized_order_lines;
```

Die konkrete Syntax ist nicht der architektonisch entscheidende Punkt. Relevant ist, dass die Nettoumsatz-Regel zentral definiert und von allen Consumern wiederverwendet wird.

## Wo die Logik liegen sollte

Nicht jede Transformation gehört in dieselbe Schicht. Die folgende Zuordnung hält die Lösung nachvollziehbar.

| Logiktyp | Bevorzugter Ort | Begründung |
|---|---|---|
| Quellextraktion und technische Ingestion | Ingestion-Prozess | Hält Quellzugriff und Ladeverhalten kontrollierbar |
| Normalisierung von Schlüsseln, Datentypen und Formaten | Standardisierungsschicht | Macht technische Strukturen wiederverwendbar |
| KPI-Logik, Stornoregeln und Länderzuordnung | Zentrales SQL-Modell, Transformationsmodell oder governte semantische Schicht | Verhindert widersprüchliche Definitionen |
| Qualitätsprüfungen | In der Nähe der Transformation und des Datenprodukts | Erkennt Fehler vor der Nutzung |
| Zugriffsregeln | Governte Plattform oder semantische Zugriffsschicht | Wendet Einschränkungen konsistent an |
| Qlik-spezifische Assoziationen oder Berechnungen | Nur dann in Qlik, wenn analytisch notwendig | Hält Qlik-Skripte dünn |
| Power-BI-spezifische Präsentationskennzahlen | Nur dann in Power BI, wenn sie tatsächlich darstellungsspezifisch sind | Verhindert die Duplizierung zentraler KPI-Logik |
| Excel-spezifische Formatierung | Excel | Trennt Präsentation und Datenlogik |

Die Kernregel ist einfach: Logik, die die Bedeutung gemeinsam genutzter Daten definiert, darf nicht versteckt in einem einzelnen Report oder einer einzelnen Anwendung liegen.

## Die einfachste tragfähige Umsetzung

Eine moderne Architektur benötigt nicht automatisch eine große Tool-Landschaft.

Für einen kleinen oder frühen Anwendungsfall kann folgende Kombination bereits ausreichen:

- eine vorhandene SQL-Datenbank;
- ein geplanter Ladeprozess oder ein bestehendes ETL-Verfahren;
- eine standardisierte Quelltabelle oder View;
- ein kuratiertes Vertriebsdatenprodukt;
- wenige automatisierte Qualitätsprüfungen;
- Qlik, Power BI oder Excel als Consumer desselben govern­ten Ergebnisses.

Beispiel:

```flow linear vertical
ERP- und Referenzdaten
SQL-Staging-Tabellen
Standardisierte View für Auftragspositionen
Governte tägliche Vertriebstabelle
Qlik / Power BI / Excel
```

Diese Lösung ist ausreichend, wenn Datenvolumen, Teamgröße, Betriebsrisiko und Änderungsrate beherrschbar bleiben. Eine Plattform sollte erst erweitert werden, wenn die aktuelle Umsetzung eine reale Grenze erreicht.

## Umsetzungsalternativen nach vorhandener Umgebung

### Es stehen nur Qlik und SQL Server zur Verfügung

SQL Server übernimmt wiederverwendbare Transformationen, KPI-Regeln, Historisierung und Qualitätsergebnistabellen. Qlik lädt das vorbereitete Datenprodukt und enthält nur Qlik-spezifische Logik, die upstream nicht sinnvoller umgesetzt werden kann.

Ein bewusst dünner Qlik-Load könnte so aussehen:

```qlik
SalesDaily:
LOAD
    business_date,
    order_id,
    order_line_id,
    customer_id,
    product_id,
    country_code,
    net_revenue,
    quality_status
FROM [lib://GovernedData/sales_daily.qvd] (qvd);
```

Die Qlik-App ist damit Consumer und nicht der versteckte Owner der unternehmensweiten KPI-Definition.

### Microsoft Fabric mit Qlik oder Power BI

Die vorhandenen Fabric-Komponenten können Ingestion, Standardisierung, Tests und Veröffentlichung des Vertriebsdatenprodukts übernehmen. Qlik und Power BI konsumieren dasselbe kuratierte Ergebnis. Die architektonische Anforderung bleibt gleich: KPI-Definition und gemeinsam genutzte Transformationslogik dürfen nicht unabhängig in jedem Report neu aufgebaut werden.

### Snowflake ist bereits vorhanden

Snowflake kann als zentrale Ausführungs- und Speicherplattform für das benötigte Datenprodukt dienen. Zu Beginn werden nur die Schemas und Transformationen umgesetzt, die der Vertriebsanwendungsfall tatsächlich benötigt. dbt wird erst ergänzt, wenn modulare Modelle, Tests, Dokumentation, Abhängigkeitsmanagement oder Zusammenarbeit ein konkretes Problem besser lösen als der vorhandene Ansatz.

### Databricks ist bereits vorhanden

Databricks kann Ingestion, Standardisierung, Transformation und Qualitätsprüfungen übernehmen, wenn es bereits die etablierte Plattform ist. Eine Lakehouse-Struktur kann das Datenprodukt unterstützen. Das Projekt beginnt dennoch mit Entscheidung, Granularität, KPI und Ownership und nicht automatisch mit breiten Bronze-, Silver- und Gold-Schichten für jede Quelle.

### Ein klassisches On-Premises-Warehouse ist vorhanden

Das bestehende Warehouse wird um einen fokussierten Vertriebsfakt und die benötigten konformen Dimensionen erweitert. Vorhandene Scheduler, SQL-Prozeduren, Views oder ETL-Tools können vollständig ausreichen. Eine Cloud-Migration ist keine Voraussetzung für ein governtes Datenprodukt.

## Greenfield und Brownfield benötigen unterschiedliche Startmaßnahmen

### Greenfield

In einer Greenfield-Umgebung besteht das Risiko im Over-Engineering. Das Team versucht möglicherweise, die vollständige zukünftige Plattform zu entwerfen, bevor ein erstes Ergebnis geliefert wird.

Der bessere Ansatz:

- einen wertvollen Anwendungsfall auswählen;
- die kleinste durchgängige Architektur definieren;
- Konventionen für Benennung, Ownership, Tests und Dokumentation festlegen;
- das Muster nachweisen;
- erst nach einem funktionierenden ersten Produkt erweitern.

### Brownfield

In einer Brownfield-Umgebung besteht das Risiko in unkontrollierter Duplizierung. Vorhandene Reports und Skripte enthalten häufig widersprüchliche Versionen desselben KPI.

Der bessere Ansatz:

- vorhandene Reports und Logik für die Business-Frage identifizieren;
- Definitionen vergleichen und Unterschiede dokumentieren;
- eine verbindliche Definition und einen Owner bestimmen;
- wiederverwendbare Logik in ein zentrales Modell überführen;
- Consumer schrittweise migrieren;
- duplizierte Logik nach erfolgreicher Validierung stilllegen.

Brownfield-Modernisierung bedeutet nicht, alles gleichzeitig zu ersetzen. Sie bedeutet, einen vertrauenswürdigen Pfad zu etablieren und Abweichungen schrittweise zu reduzieren.

## Typische Anti-Patterns

### Alle Quellen laden, bevor der Anwendungsfall definiert ist

Dadurch entsteht Datenverfügbarkeit ohne Entscheidungswert. Geladen wird nur, was das erste Produkt benötigt. Die Architektur bleibt dennoch für spätere Quellen offen.

### Bronze, Silver und Gold als vollständige Business-Architektur behandeln

Diese Schichten können die technische Verarbeitung strukturieren. Sie definieren jedoch weder KPI-Bedeutung noch Ownership, Consumer, Sicherheit oder Serviceerwartungen.

### Fachlogik unabhängig in jedem BI-Tool entwickeln

Dieselbe Stornoregel darf nicht getrennt in Qlik, Power BI, Excel und SQL implementiert werden. Gemeinsame Bedeutung gehört in eine gemeinsame Schicht.

### Auf die perfekte Zielarchitektur warten

Ein vollständiges Enterprise-Zielbild kann die Richtung vorgeben, darf aber ein sinnvolles erstes Produkt nicht blockieren. Das erste Muster muss zur Zielrichtung passen und erweiterbar sein.

### dbt, Snowflake, Databricks oder Fabric ohne definierten Bedarf einführen

Jede dieser Plattformen kann wertvoll sein. Keine sollte nur deshalb ergänzt werden, weil sie als modern gilt. Neue Komponenten müssen ein identifiziertes Problem bei Skalierung, Wartbarkeit, Governance, Zusammenarbeit oder Performance lösen.

### Ownership erst nach der Lieferung klären

Ohne Verantwortliche bleiben KPI-Konflikte und Qualitätsfehler ungelöst. Ownership ist Bestandteil der Produktdefinition und kein nachgelagerter Verwaltungsprozess.

## Die Entscheidungen vor der ersten Pipeline

Die erste Pipeline sollte erst entworfen werden, nachdem fachliche, technische und organisatorische Entscheidungen explizit getroffen wurden.

![Die Entscheidungen vor der ersten Pipeline](images/playbooks/bp-start-img3-de.png)

Diese Entscheidungen benötigen keine monatelange Dokumentation. Für das erste Datenprodukt reicht ein kompakter Entscheidungsnachweis, wenn er eindeutig, freigegeben und gepflegt ist.

Das praktische Minimum enthält:

- Business-Frage und primären Consumer;
- KPI-Definition und Ausschlüsse;
- Granularität und Scope;
- benötigte Quellen und führendes System;
- Aktualität und Historisierung;
- verpflichtende Qualitätsregeln und Schwellwerte;
- Sicherheitsklassifikation und Zugriff;
- Business Owner und Data Owner;
- Lieferziel und erwartetes Service Level.

## Entscheidungshilfe

Verwende die einfachste Architektur, die die expliziten Anforderungen erfüllt.

| Situation | Empfohlener Startpunkt |
|---|---|
| Kleiner Scope, wenige Nutzer, beherrschbares Volumen | Vorhandenes SQL und geplante Ladeprozesse |
| Wiederverwendbare Modelle werden für mehrere Consumer benötigt | Zentrales Warehouse oder kuratierte SQL-Schicht |
| Transformationen, Tests und Abhängigkeiten werden schwer wartbar | Modulares Transformationsmanagement wie dbt ergänzen |
| Skalierte Verarbeitung oder ein etabliertes Lakehouse ist bereits vorhanden | Vorhandenes Databricks oder eine vergleichbare Plattform nutzen |
| Ein governtes Cloud-Warehouse ist bereits etabliert | Produkt in Snowflake oder im vorhandenen Warehouse aufbauen |
| Fabric ist bereits die unternehmensweite Datenplattform | Fabric nutzen, aber das Produkt weiterhin entscheidungsorientiert entwerfen |
| Qlik ist der wichtigste Consumer | Qlik-Skripte dünn halten und governte Produkte konsumieren |
| Power BI ist der wichtigste Consumer | Das governte Produkt wiederverwenden und isolierte KPI-Definitionen vermeiden |
| Excel bleibt operativ wichtig | Excel mit demselben govern­ten Ergebnis verbinden |

Das entscheidende Kriterium ist nicht das Prestige einer Plattform. Entscheidend ist, ob die Umsetzung mit vertretbarem Aufwand, Risiko und Wartungsbedarf eine vertrauenswürdige Antwort liefert.

## Wichtigste Empfehlungen

1. Mit einer Business-Entscheidung starten, nicht mit einer Plattform.
2. KPI und Granularität vor dem Tabellenentwurf definieren.
3. Nur die Quellen und Felder laden, die für das erste Produkt benötigt werden.
4. Aktualität, Historisierung, Qualität, Sicherheit und Ownership vor der Umsetzung klären.
5. Gemeinsam genutzte Fachlogik außerhalb einzelner BI-Anwendungen platzieren.
6. Qlik-Skripte und reportspezifische Transformationen so dünn wie sinnvoll halten.
7. Vorhandene Tools nutzen, bis eine konkrete Grenze eine Erweiterung rechtfertigt.
8. Ein vollständiges vertikales Datenprodukt liefern, bevor die Plattform verbreitert wird.
9. Governance als Bestandteil des ersten Releases behandeln und nicht als spätere Phase.
10. Über bewährte Produkte erweitern statt über spekulativ aufgebaute Infrastruktur.

## Übergang zum nächsten Part

Dieser Part hat geklärt, welche Entscheidungen getroffen werden müssen, bevor die erste Tabelle oder Pipeline entsteht. Im nächsten Schritt werden diese Entscheidungen in eine konkrete Source-to-Product-Architektur übersetzt: Wie Daten ingestiert, standardisiert, modelliert, getestet und veröffentlicht werden, ohne jede technische Schicht zu einer eigenen fachlichen Definition werden zu lassen.
