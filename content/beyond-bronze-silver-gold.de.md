---
title: Mehr als Bronze, Silver und Gold
description: Warum Bronze, Silver und Gold als technische Bezeichnungen nützlich, für eine vollständige Warehouse-Architektur aber nicht präzise genug sind — und wie klare Schichten Rohdatenaufnahme, Standardisierung, Integration, governte Datenprodukte und Nutzungsverträge trennen.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - medallion-architecture
  - bronze-silver-gold
  - data-products
  - semantic-models
  - qlik-sense
  - power-bi
  - excel
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 2
seriesTitle: Ein modernes Data Warehouse aufbauen
hero: images/playbooks/bp-start2-hero.png
---

## Ein nützliches Muster ist noch keine vollständige Architektur

Bronze, Silver und Gold haben sich als verbreitete Bezeichnungen zur Strukturierung analytischer Datenverarbeitung etabliert.

Das Muster ist nützlich, weil es eine Entwicklung vermittelt:

```flowchart
Bronze
Silver
Gold
```

Bronze steht meist für quellnahe Daten. Silver bezeichnet üblicherweise bereinigte oder transformierte Daten. Gold beschreibt Daten, die für die fachliche Nutzung vorbereitet sind.

Das ist ein praktikabler Einstieg, beantwortet aber nicht präzise genug die Architekturfragen, die darüber entscheiden, ob ein Warehouse verständlich, wiederverwendbar und governbar bleibt.

Unter der Bezeichnung **Gold** können mehrere grundsätzlich verschiedene Themen liegen:

- integrierte Unternehmensdaten;
- historisierte fachliche Entitäten;
- konforme Fakten und Dimensionen;
- KPI-fähige Datensätze;
- fachbereichsspezifische Marts;
- für Qlik optimierte Views;
- Power-BI-Semantikmodelle;
- Excel-Reporting-Views;
- Extrakte für einen einzelnen Bericht oder eine einzelne Anwendung.

Diese Objekte besitzen nicht denselben Zweck, dieselbe Granularität, dieselbe Ownership, denselben Lebenszyklus oder dasselbe Wiederverwendungspotenzial. Werden sie gemeinsam in einer groben Gold-Schicht abgelegt, bleiben Entscheidungen verborgen, die explizit sein sollten.

> **Bronze, Silver und Gold sind nützliche technische Kategorien. Für die logische Architektur eines echten Warehouses reichen sie nicht aus.**

Part 1 dieser Serie, [Bevor die erste Tabelle entsteht](/playbooks/before-building-the-first-table), hat festgelegt, dass die Architektur mit Business-Entscheidung, KPI, Granularität, Quellen, Qualitätsanforderungen und Ownership beginnt. Dieser Part überführt diese Entscheidungen in einen präziseren Lebenszyklus von der Quelle bis zur Nutzung.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start2-img1-de.png"
        alt="Vergleich zwischen dem vereinfachten Bronze-Silver-Gold-Muster und einer präziseren Warehouse-Architektur mit Raw, Standardisierung, integriertem Kern, Datenprodukten sowie Semantik- und Nutzungsschichten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Medallion-Muster bleibt nützlich. Die Bezeichnung Gold vermischt jedoch häufig Integration, dimensionale Modellierung, KPI-Aufbereitung und werkzeugspezifische Nutzung. Präzisere logische Schichten machen diese Verantwortlichkeiten sichtbar.
    </figcaption>
</figure>

## Architekturprinzip: Verantwortlichkeiten statt Werkzeuge trennen

Eine logische Schicht sollte eine stabile Verantwortung im Datenlebenszyklus beschreiben. Sie sollte nicht lediglich den Namen eines Produkts, einer Engine oder einer Speichertechnologie wiederholen.

Ein praktikabler Warehouse-Lebenszyklus lässt sich so ausdrücken:

```flowchart
Quellsysteme
Landing / Raw
Standardisiert / Validiert
Integrierter Kern
Fachliche Datenprodukte / Marts
Nutzungsverträge / Semantikmodelle
Berichte, Apps und Analysen
```

Die Grenzen sind relevant, weil jede Schicht eine andere Frage beantwortet.

| Schicht | Zentrale Frage |
| --- | --- |
| Quellsysteme | Wo wurden das operative Ereignis oder die Stammdaten erzeugt? |
| Landing / Raw | Was hat die Quelle exakt geliefert und wann wurde es empfangen? |
| Standardisiert / Validiert | Können die Daten konsistent verarbeitet werden und sind sie technisch plausibel? |
| Integrierter Kern | Welche fachliche Entität, Beziehung und historische Version stellt der Datensatz dar? |
| Fachliche Datenprodukte / Marts | Welche govern­ten Fakten, Dimensionen und KPI-Basen werden für einen definierten fachlichen Zweck bereitgestellt? |
| Nutzungsverträge / Semantikmodelle | Wie darf ein konkretes Werkzeug oder ein Consumer auf das governte Produkt zugreifen und es interpretieren? |

Diese Struktur ist logisch und nicht vorschreibend. Eine kleine Umsetzung kann mehrere Verantwortlichkeiten in einer Datenbank realisieren. Eine große Umsetzung kann getrennte Schemas, Speicherzonen, Compute Engines oder Deployment-Pipelines verwenden.

Das Ziel ist nicht die maximal mögliche Anzahl physischer Schichten. Das Ziel besteht darin, Verantwortlichkeiten so klar zu machen, dass Logik bewusst platziert wird.

## Der vollständige Warehouse-Lebenszyklus

Ein modernes Warehouse besteht nicht nur aus einer Folge von Speicherbereichen. Datenqualität, Governance, Sicherheit, Lineage, Observability und Delivery-Praktiken gelten über den gesamten Lebenszyklus.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start2-img2-de.png"
        alt="Vollständiger Warehouse-Lebenszyklus von Quellsystemen über Landing oder Raw, Standardisierung oder Validierung, integrierten Kern, fachliche Datenprodukte oder Marts sowie Nutzungsverträge oder Semantikmodelle mit Datenqualität, Governance, Sicherheit, Lineage, Observability und CI/CD als Querschnittsfunktionen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Schichten trennen Datenübernahme, technische Standardisierung, fachliche Integration, governte Produkte und consumerspezifische Bereitstellung. Querschnittskontrollen wirken über alle Schichten und werden nicht erst am Ende ergänzt.
    </figcaption>
</figure>

### Quellsysteme

Quellsysteme bleiben für operative Prozesse wie Kundenpflege, Auftragserfassung, Fakturierung, Produktverwaltung oder Ereigniserzeugung verantwortlich.

Sie sind keine Warehouse-Schichten. Sie sind die Systeme, aus denen der analytische Lebenszyklus Daten erhält.

Typische Quellen sind:

- ERP- und CRM-Systeme;
- operative Datenbanken;
- Dateien und Tabellenkalkulationen;
- APIs;
- Event Streams;
- externe Referenzdaten;
- manuell gepflegte Klassifikationen.

Das Warehouse sollte führendes System, Extraktionsmechanismus, Quellzeitstempel, Quellschlüssel und erwartetes Lieferverhalten dokumentieren.

### Landing / Raw

Die Landing- oder Raw-Schicht bewahrt die von der Quelle empfangenen Daten mit möglichst geringen semantischen Änderungen.

Typische Verantwortlichkeiten sind:

- unveränderte oder nur minimal veränderte Übernahme;
- Quell- und Ladezeitstempel;
- Batch-, Datei- oder Event-Kennungen;
- Quellmetadaten;
- Nachvollziehbarkeit bis zum empfangenen Payload;
- kontrollierte Aufbewahrung oder Wiederholbarkeit;
- technische Quarantäne nicht lesbarer Eingaben.

Diese Schicht ist nicht der Ort für einen Golden Customer, eine Net-Revenue-KPI oder eine fachliche Hierarchie. Ihr Zweck sind Nachweisbarkeit und Wiederherstellbarkeit.

Eine Raw-Schicht erfordert nicht zwingend einen Data Lake. Sie kann mit Datenbanktabellen, Dateien, Object Storage, Delta-Tabellen oder einem anderen kontrollierten Landing-Mechanismus umgesetzt werden.

### Standardisiert / Validiert

Die standardisierte oder validierte Schicht überführt quellspezifische Strukturen in technisch konsistente Daten.

Typische Verantwortlichkeiten sind:

- Konvertierung von Datentypen;
- Normalisierung von Datums-, Zahlen- und Zeichencodierungen;
- Harmonisierung von Spaltennamen und Strukturen;
- Prüfung verpflichtender Felder;
- Domain- und Referenzvalidierung;
- Standardisierung von Länder- oder Währungscodes;
- Erkennung möglicher Dubletten;
- Validierungskennzeichen und Ablehnungsgründe.

Diese Schicht beantwortet, ob Daten konsistent verarbeitet werden können. Sie entscheidet noch nicht, ob zwei Quelldatensätze denselben realen Kunden repräsentieren oder welche historische Kundenversion zu einem Verkauf gehört.

### Integrierter Kern

Der integrierte Kern bildet gemeinsam genutzte fachliche Entitäten und Beziehungen über mehrere Quellen ab.

Typische Verantwortlichkeiten sind:

- unternehmensweite Business Keys;
- Zuordnung von Quell- zu Unternehmensschlüsseln;
- Golden Records;
- Konsolidierung von Stammdaten;
- Historisierung und Slowly Changing Dimensions;
- gültig-von-/gültig-bis-Beziehungen;
- konforme Kunden-, Produkt- und Organisationsstrukturen;
- integrierte Fakten mit stabiler Granularität;
- wiederverwendbare Regeln zur Definition gemeinsamer fachlicher Bedeutung.

Im integrierten Kern können ein CRM-Kunde und ein ERP-Kunde zu einer govern­ten Kundenidentität zusammengeführt werden. Dort werden auch historische Zuordnungen erhalten, wenn Berichte eine As-was-Sicht benötigen.

Diese Schicht sollte unabhängig von einem einzelnen Dashboard bleiben. Ihre Objekte sind wiederverwendbare Bausteine.

### Fachliche Datenprodukte / Marts

Ein fachliches Datenprodukt bündelt governte Daten für einen definierten Business-Zweck.

Typische Verantwortlichkeiten sind:

- Fakten und Dimensionen für einen Themenbereich;
- explizite Granularität;
- KPI-Basisspalten;
- kuratierte Attribute;
- dokumentierte Filter und Ausschlüsse;
- Qualitätsstatus;
- Business Owner und technischer Owner;
- Aktualitäts- und Service-Erwartungen;
- stabile Schnittstellen für nachgelagerte Consumer.

Beispiele sind:

- Täglicher Vertrieb;
- Customer 360;
- Produktrentabilität;
- Vertragsportfolio;
- Data-Quality-Ergebnisse.

Ein Datenprodukt ist nicht lediglich der Name eines Schemas. Es verbindet Daten, Semantik, Ownership, Qualität und einen definierten Nutzungsvertrag.

### Nutzungsverträge / Semantikmodelle

Die Nutzungsschicht passt governte Datenprodukte an die Zugriffsmuster konkreter Werkzeuge und Nutzergruppen an.

Typische Verantwortlichkeiten sind:

- für Qlik optimierte Präsentations-Views oder governte QVD-Extrakte;
- Power-BI-Semantikmodelle und Präsentations-Measures;
- Excel-Reporting-Views;
- governte Spaltennamen und Beschreibungen;
- zeilen- oder objektbasierte Zugriffssteuerung, falls erforderlich;
- stabile Schnittstellen und Kompatibilitätszusagen;
- Last-Mile-Berechnungen, die tatsächlich consumerspezifisch sind.

Die zentrale KPI-Bedeutung sollte bereits vorgelagert existieren. Ein Semantikmodell kann Formatierung, Berechnungsgruppen, Anzeigehierarchien oder interaktionsspezifische Measures ergänzen, sollte aber nicht unbemerkt die Unternehmens-KPI neu definieren.

> **Gemeinsame Bedeutung gehört so weit nach links wie sinnvoll. Werkzeugspezifisches Verhalten gehört nur so weit nach rechts wie nötig.**

## Was gehört in welche Schicht?

Die Zuordnung wird klarer, wenn ein durchgängiges Kunden- und Vertriebsbeispiel betrachtet wird.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start2-img3-de.png"
        alt="Schichtweise Zuordnung von Quellerfassung, unveränderter Übernahme, Standardisierung, Dublettenprüfung, Golden Customer, SCD-Historie, Fakten und Dimensionen, KPI-Basen sowie werkzeugspezifischen Qlik-, Power-BI- und Excel-Nutzungsobjekten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Jede Schicht besitzt eine eigene Verantwortung. Kundenidentität, Historie und gemeinsame KPI-Logik werden zentral gelöst. Qlik, Power BI und Excel erhalten erst nach dem govern­ten Datenprodukt consumerspezifische Schnittstellen.
    </figcaption>
</figure>

## Konkretes Kunden- und Vertriebsbeispiel

Angenommen, das Unternehmen erhält Kundendaten aus einem CRM-System und Verkaufsaufträge aus einem ERP-System.

Das Business möchte analysieren:

> Nettoumsatz nach Kunde, Land und Monat unter Verwendung der Kundenmerkmale, die zum Zeitpunkt des Auftrags gültig waren.

Die benötigte Logik umfasst:

- eine gültige Customer ID;
- standardisierte Länder;
- Dublettenerkennung;
- einen Golden Customer;
- historische Kundenattribute;
- einen Umsatz-Fact auf Auftragszeilenebene;
- eine zentral definierte Nettoumsatzbasis;
- Qlik, Power BI und Excel als mögliche Consumer.

### Quellsysteme

Das CRM liefert Kundendatensätze:

| crm_customer_id | name | country | segment | changed_at |
| --- | --- | --- | --- | --- |
| C-100 | Alpha Systems | DE | SMB | 2026-01-03 10:15 |
| C-101 | Alpha Systems GmbH | Germany | SMB | 2026-01-04 08:10 |
| C-200 | Beta Retail | NL | Enterprise | 2026-01-02 15:30 |

Das ERP liefert Auftragszeilen:

| order_id | line_id | order_date | erp_customer_id | amount | cancellation_flag |
| --- | ---: | --- | --- | ---: | ---: |
| SO-1001 | 10 | 2026-01-12 | C-100 | 8000 | 0 |
| SO-1002 | 10 | 2026-02-08 | C-200 | 5000 | 0 |
| SO-1003 | 10 | 2026-03-18 | C-100 | 6000 | 1 |

In den Quellsystemen sollte keine Warehouse-Logik ergänzt werden, nur damit ein einzelner Bericht funktioniert.

### Landing / Raw

Die empfangenen Zeilen werden mit technischen Metadaten gespeichert:

```text
source_system
source_object
source_file_or_batch_id
ingested_at
source_changed_at
raw_payload_or_source_columns
```

Die Länderwerte bleiben exakt wie geliefert: `DE`, `Germany`, `NL`.

Auch die beiden Datensätze zu Alpha Systems bleiben getrennt. Raw bewahrt den Nachweis und entscheidet nicht, ob es sich um Dubletten handelt.

### Standardisiert / Validiert

Das standardisierte Kundenmodell kann:

- `crm_customer_id` in einen einheitlichen Textdatentyp überführen;
- Namen trimmen und normalisieren;
- `DE` und `Germany` auf den ISO-Ländercode `DE` abbilden;
- prüfen, ob die Customer ID vorhanden ist;
- einen Schlüssel für mögliche Dubletten erzeugen;
- Validierungskennzeichen vergeben.

Eine vereinfachte SQL-View kann so aussehen:

```sql
select
    trim(cast(crm_customer_id as varchar(50))) as customer_id,
    trim(customer_name) as customer_name,
    case
        when upper(trim(country)) in ('DE', 'GERMANY', 'DEUTSCHLAND') then 'DE'
        when upper(trim(country)) in ('NL', 'NETHERLANDS', 'NIEDERLANDE') then 'NL'
        else null
    end as country_code,
    trim(segment) as segment,
    changed_at,
    case
        when crm_customer_id is null then 'INVALID_CUSTOMER_ID'
        when country is null then 'INVALID_COUNTRY'
        else 'VALID'
    end as validation_status
from raw_crm_customer;
```

Die genaue SQL-Syntax kann sich je Plattform unterscheiden. Der architektonische Punkt ist, dass technische Standardisierung wiederverwendbar und sichtbar erfolgt.

### Integrierter Kern

Der integrierte Kern löst Identität und Historie auf.

Beispiel:

| enterprise_customer_id | source_customer_id | source_system | valid_from | valid_to |
| --- | --- | --- | --- | --- |
| EC-001 | C-100 | CRM | 2026-01-01 | 9999-12-31 |
| EC-001 | C-101 | CRM | 2026-01-01 | 9999-12-31 |
| EC-002 | C-200 | CRM | 2026-01-01 | 9999-12-31 |

`C-100` und `C-101` werden nach einer govern­ten Matching-Entscheidung demselben Golden Customer `EC-001` zugeordnet.

Die historische Kundendimension kann enthalten:

| customer_sk | enterprise_customer_id | country_code | segment | valid_from | valid_to |
| ---: | --- | --- | --- | --- | --- |
| 1001 | EC-001 | DE | SMB | 2026-01-01 | 2026-04-01 |
| 1002 | EC-001 | DE | Enterprise | 2026-04-01 | 9999-12-31 |
| 2001 | EC-002 | NL | Enterprise | 2026-01-01 | 9999-12-31 |

Hier wird die historische Beziehung explizit. Ein Verkauf am 18.03.2026 verwendet Kundenversion `1001`; ein späterer Verkauf nach dem 01.04.2026 verwendet `1002`.

### Fachliches Datenprodukt / Mart

Das Vertriebsdatenprodukt stellt eine Zeile je Auftragsposition mit govern­ten Schlüsseln und KPI-Basen bereit.

```sql
select
    s.order_id,
    s.line_id,
    s.order_date,
    c.customer_sk,
    c.enterprise_customer_id,
    c.country_code,
    c.segment,
    s.amount as gross_revenue,
    s.cancellation_flag,
    case
        when s.cancellation_flag = 1 then 0
        else s.amount
    end as net_revenue,
    s.quality_status
from standardized_sales_line s
join customer_key_map m
  on m.source_system = s.source_system
 and m.source_customer_id = s.customer_id
join dim_customer_history c
  on c.enterprise_customer_id = m.enterprise_customer_id
 and s.order_date >= c.valid_from
 and s.order_date < c.valid_to;
```

Das Datenprodukt definiert:

```text
Granularität: eine ERP-Auftragszeile
Business Owner: Vertriebscontrolling
Data Owner: Kunden- und Vertriebsdomäne
Aktualisierung: täglich vor 07:00 Uhr
Verpflichtende Qualität: gültiger Auftrag, Kunde, Land, Betrag und Änderungszeitstempel
Consumer: Qlik, Power BI, Excel und freigegebene operative Extrakte
```

Die Basis `net_revenue` wird damit nicht unabhängig in jeder BI-Anwendung neu erstellt.

### Nutzungsverträge

Die consumerspezifische Schicht kann bereitstellen:

- eine Qlik-View oder ein governtes QVD mit stabilen Feldnamen und optimierten Assoziationen;
- ein Power-BI-Semantikmodell mit Beziehungen, Formatierung und Präsentations-Measures;
- eine Excel-View mit fachlich verständlichen Spalten und eingeschränktem Detailzugriff.

Ein bewusst schlanker Qlik-Load kann so aussehen:

```qlik
Sales:
LOAD
    order_id,
    line_id,
    order_date,
    customer_sk,
    enterprise_customer_id,
    country_code,
    segment,
    gross_revenue,
    cancellation_flag,
    net_revenue,
    quality_status
FROM [lib://GovernedData/sales_data_product.qvd] (qvd);
```

Qlik darf weiterhin Qlik-spezifische Logik enthalten, wenn sie erforderlich ist, etwa anwendungsspezifische Assoziationen, die Integration von Section Access oder tatsächlich interaktive Berechnungen. Qlik sollte jedoch nicht zum versteckten Ablageort für gemeinsam genutztes Kunden-Matching, Historisierung oder KPI-Definitionen werden.

Ein Power-BI-Semantikmodell kann dieselben govern­ten Fakten und Dimensionen verwenden. Excel kann eine kontrollierte Reporting-View nutzen. Unterschiedliche Consumer benötigen keine unterschiedlichen fachlichen Wahrheiten.

## Die einfachste tragfähige Umsetzung

Eine präzise logische Architektur erfordert weder sechs unterschiedliche Technologien noch sechs Datenbanken.

Für ein kleines Team mit vorhandener SQL-Datenbank kann der gesamte Lebenszyklus mit Schemas und Views umgesetzt werden:

```text
Quellreferenzen
Raw-Tabellen
standardisierte Views
Core-Tabellen
Mart-Tabellen oder -Views
Consumer-Views
```

Eine minimale physische Struktur kann so aussehen:

```text
raw.crm_customer
raw.erp_sales_line

standardized.customer
standardized.sales_line

core.customer_key_map
core.dim_customer_history

mart.fact_sales
mart.dim_customer

consumption.qlik_sales
consumption.powerbi_sales
consumption.excel_sales
```

Dieselbe Datenbank kann alle Objekte enthalten. Getrennte Schemas und Namenskonventionen können ausreichen, um die logischen Grenzen zu erhalten.

Zum Minimum gehören außerdem Querschnittskontrollen:

- Protokollierung von Lade- und Transformationsläufen;
- eine kleine Menge automatisierter Qualitätsprüfungen;
- Ownership-Metadaten;
- Zugriffssteuerung;
- versionierte Skripte;
- ausreichende Lineage-Dokumentation, um eine KPI bis zu ihren Quellen zurückzuverfolgen.

Diese Architektur ist modern, weil Verantwortlichkeiten explizit und wiederverwendbar sind — nicht weil sie ein bestimmtes Cloud-Produkt enthält.

## Alternative Umsetzungen nach vorhandener Plattform

Die logische Architektur bleibt stabil, während sich die physische Umsetzung ändert.

| Logische Verantwortung | Klassisches SQL / On-Premises | Microsoft Fabric | Snowflake | Databricks |
| --- | --- | --- | --- | --- |
| Landing / Raw | Staging-Tabellen, Dateien, vorhandene ETL-Landing-Zone | Vorhandene Ingestion- und Speichermechanismen | Raw-Schemas und Ingestion-Prozesse | Raw-Tabellen oder Dateien im etablierten Lakehouse |
| Standardisiert / Validiert | SQL-Views, Prozeduren, ETL-Mappings | Bereits genutzte SQL- oder Notebook-Transformationen | SQL-Transformationen, Tasks oder bestehendes Transformationsframework | SQL- oder Spark-Transformationen |
| Integrierter Kern | Core-Warehouse-Tabellen, Historientabellen | Governte Warehouse- oder Lakehouse-Strukturen | Core-Schemas und historisierte Modelle | Governte Delta-basierte Core-Modelle |
| Datenprodukte / Marts | Star-Schemata, kuratierte Views | Kuratierte Warehouse- oder Lakehouse-Produkte | Produktschemas, Marts und sichere Views | Kuratierte Tabellen, Marts und Serving-Views |
| Nutzung | Qlik-Views/QVDs, Power-BI-Modelle, Excel-Views | Power BI, Qlik oder Excel auf govern­ten Outputs | BI-Semantikmodelle und Präsentations-Views | SQL-Serving oder governte Extrakte für BI-Werkzeuge |

Das sind Beispiele und keine vorgeschriebenen Stack-Designs.

### Klassisches SQL-Warehouse oder On-Premises

Vorhandene relationale Tabellen, Views, Prozeduren, Scheduler und ETL-Werkzeuge können weiterverwendet werden, wenn sie die Anforderungen erfüllen. Eine logische Trennung über Schemas, Benennung und Deployment-Konventionen kann ausreichen.

Ein Plattformwechsel ist nicht erforderlich, nur um über Bronze, Silver und Gold hinauszugehen.

### Microsoft Fabric

Ist Fabric bereits die ausgewählte Umgebung, werden dieselben Verantwortlichkeiten auf die vorhandenen Speicher-, Transformations- und Bereitstellungskomponenten abgebildet. Es muss nicht automatisch für jede Quelle eine umfangreiche Drei-Zonen-Struktur entstehen, bevor ein fachliches Datenprodukt geliefert werden kann.

Qlik, Power BI und Excel können dasselbe kuratierte Produkt konsumieren, wenn dies betrieblich sinnvoll ist.

### Snowflake

Ist Snowflake bereits vorhanden, können Schemas, Tabellen, Views und die bestehende Orchestrierungs- oder Transformationslösung die logischen Grenzen abbilden. dbt kann ergänzt werden, wenn modulare SQL-Modelle, Tests, Dokumentation und Abhängigkeitsmanagement ein konkretes Teamproblem besser lösen. dbt ist nicht allein deshalb erforderlich, weil Snowflake eingesetzt wird.

### Databricks

Ist Databricks bereits etabliert, kann die Medallion-Terminologie für die technische Verarbeitung weiterhin nützlich sein. Die logische Architektur sollte trotzdem Standardisierung, unternehmensweite Integration, fachliche Datenprodukte und semantische Nutzung unterscheiden, statt alle kuratierten Objekte in einer undifferenzierten Gold-Zone zusammenzufassen.

### dbt

dbt ist eine mögliche Transformationsmanagement-Schicht für geeignete SQL-Plattformen. Abhängigkeiten zwischen Modellen, Tests, Dokumentation und Deployment-Praktiken können dadurch expliziter werden.

dbt ersetzt die Architekturentscheidungen nicht. Auch ein dbt-Projekt kann Raw-Bereinigung, Identitätsauflösung, KPI-Logik und reportspezifische Modelle vermischen, wenn die logischen Grenzen nicht bewusst entworfen werden.

### Qlik als stärkste vorhandene Fähigkeit

In einer Brownfield-Umgebung kann ein erheblicher Teil der Transformationen derzeit in Qlik-Skripten und QVD-Schichten liegen.

Das praktische Ziel muss nicht zwingend eine sofortige Neuentwicklung sein. Eine kontrollierte Migration kann:

1. die aktuelle Qlik-Logik dokumentieren;
2. gemeinsam genutzte fachliche Regeln identifizieren;
3. wiederverwendbare Standardisierung, Integration und KPI-Logik in SQL oder eine andere gemeinsame Transformationsschicht verschieben;
4. governte QVDs oder Views veröffentlichen;
5. nur Qlik-spezifische Logik in der Anwendung behalten;
6. doppelte Skriptlogik nach der Abstimmung stilllegen.

Die Schichten zeigen, wo Logik zusammengeführt werden sollte. Sie verlangen keinen disruptiven Austausch in einem Schritt.

## Querschnittsfunktionen sind keine zusätzliche Endschicht

Datenqualität, Governance, Sicherheit, Lineage, Observability und CI/CD dürfen nicht als Aktivitäten behandelt werden, die erst beginnen, nachdem Gold-Daten existieren.

### Datenqualität

Qualitätsregeln treten in unterschiedlichen Schichten auf:

- Raw: Datei empfangen, Zeilenanzahl, Schema lesbar;
- Standardisiert: Pflichtfelder, Datentypen, erlaubte Wertebereiche;
- Integrierter Kern: Schlüsselzuordnung, Dublettenauflösung, zeitliche Konsistenz;
- Datenprodukt: Granularität, referenzielle Integrität, KPI-Plausibilität, Vollständigkeit;
- Nutzung: Vertragskompatibilität und consumerspezifische Validierung.

### Governance

Governance definiert:

- fachliche und technische Ownership;
- freigegebene Definitionen;
- Datenklassifikation;
- Änderungsfreigaben;
- Aufbewahrung;
- Service-Erwartungen;
- Ablösung und Stilllegung.

### Sicherheit

Sicherheit muss den Daten durch den gesamten Lebenszyklus folgen. Personenbezogene Rohdaten können strengeren Zugriff benötigen als aggregierte Datenprodukte. Consumer-Modelle dürfen nicht zu unkontrollierten Kopien werden, die den govern­ten Zugriffspfad umgehen.

### Lineage

Lineage sollte Folgendes verbinden:

```text
Quellfeld
→ Raw-Feld
→ standardisiertes Feld
→ integriertes fachliches Objekt
→ Datenproduktfeld oder KPI-Basis
→ semantisches Measure oder Bericht
```

Das Ziel sind Auswirkungsanalyse und Verantwortlichkeit — nicht nur ein Diagramm von Tabellenabhängigkeiten.

### Observability

Observability umfasst:

- Pipeline-Ausführungen;
- Aktualität;
- Volumenanomalien;
- Schemaänderungen;
- Qualitätsfehler;
- Verletzungen von Service Levels;
- Incidents mit Auswirkungen auf Consumer.

### CI/CD

Versionierung und Deployment-Praktiken gelten für:

- Ingestion-Definitionen;
- SQL, Notebooks oder Transformationsmodelle;
- Qualitätsregeln;
- Schemaänderungen;
- Semantikmodelle;
- Qlik-Load-Skripte, soweit sie erforderlich bleiben;
- Dokumentation und Verträge.

## Typische Anti-Patterns

### Zonen umbenennen, ohne Verantwortlichkeiten zu klären

Die Umbenennung von `Bronze`, `Silver` und `Gold` in `Raw`, `Core` und `Mart` verbessert nichts, wenn dieselbe vermischte Logik darin verborgen bleibt.

### Gold als alles behandeln, was das Business verwendet

Dadurch werden wiederverwendbare Unternehmensstrukturen, fachliche Marts, KPI-Logik und werkzeugspezifische Modelle zusammengeführt. Ownership und Auswirkungen von Änderungen werden unklar.

### Für jede logische Verantwortung eine physische Plattformschicht erzeugen

Eine logische Grenze benötigt nicht automatisch eine eigene Datenbank, Engine oder ein zusätzliches Werkzeug. Übertriebene physische Trennung erhöht die Betriebskomplexität, ohne Governance automatisch zu verbessern.

### Identitätsauflösung in jedem Bericht durchführen

Kunden-Matching und Golden-Record-Logik sollten nicht unabhängig in Qlik, Power BI und Excel implementiert werden. Die Ergebnisse würden sich je Consumer unterscheiden und wären schwer auditierbar.

### KPI-Logik in Semantikmodellen neu aufbauen

Semantikmodelle dürfen präsentationsspezifische Measures enthalten. Die gemeinsame KPI-Basis sollte jedoch vorgelagert govern­t sein. Andernfalls wird dieselbe KPI von der eingesetzten Berichtstechnologie abhängig.

### Raw-Daten so stark bereinigen, dass der Quellnachweis verschwindet

Werden Werte in Raw unbemerkt überschrieben, werden Quellnachvollziehbarkeit und Wiederholbarkeit unzuverlässig. Standardisierung sollte eine neue kontrollierte Darstellung erzeugen, statt die empfangene Darstellung zu zerstören.

### Eine Schicht mit einem Speicherformat gleichsetzen

Logische Architektur und physische Speicherung sind getrennte Entscheidungen. Raw kann aus Dateien oder Tabellen bestehen. Ein Datenprodukt kann Tabelle, View oder governtes Extrakt sein. Die richtige Form hängt von den Anforderungen ab.

### Eine weitere Plattform einführen, damit das Schaubild modern aussieht

Fabric, Snowflake, Databricks und dbt können jeweils reale Probleme lösen. Keines dieser Werkzeuge ist automatisch für präzise Schichten erforderlich. Eine Komponente sollte nur ergänzt werden, wenn sie Skalierung, Zusammenarbeit, Tests, Governance, Performance oder Betrieb ausreichend verbessert, um Aufwand und Komplexität zu rechtfertigen.

## Entscheidungshilfe: Wie viele physische Schichten sind erforderlich?

Verwende das logische Modell als Checkliste und fasse physische Schichten dort zusammen, wo die Verantwortlichkeiten trotzdem klar und sicher bleiben.

| Situation | Praktische Umsetzung |
| --- | --- |
| Kleines Team, eine SQL-Datenbank, täglicher Batch | Getrennte Schemas oder Namenskonventionen in einer Datenbank |
| Mehrere Consumer und wiederverwendbare fachliche Entitäten | Getrennte Core- und Datenprodukt-Schemas mit kontrollierten Schnittstellen |
| Hohe Audit- oder Replay-Anforderungen | Dauerhafte Raw-Speicherung und explizite Transformationsoutputs |
| Mehrere Quellsysteme mit Identität und Historie | Eigene Modelle für den integrierten Kern |
| Mehrere BI-Werkzeuge | Gemeinsame Datenprodukte und bei Bedarf getrennte Nutzungsverträge |
| Komplexe Transformationsabhängigkeiten und teambasierte Entwicklung | Modulares Transformationsmanagement wie dbt ergänzen, wenn es das Kollaborationsproblem löst |
| Vorhandenes Lakehouse mit hoher Skalierung | Technische Zonen beibehalten, aber logische Core-, Produkt- und Nutzungsverantwortlichkeiten darin oder darüber definieren |
| Stabiles On-Premises-Warehouse | Modellierung, Tests, Versionierung und Verträge modernisieren, ohne eine Cloud-Migration zu erzwingen |

Eine Schicht ist gerechtfertigt, wenn sie eine relevante Grenze für Verantwortung, Wiederverwendung, Kontrolle, Lebenszyklus oder Performance schafft. Sie ist nicht gerechtfertigt, nur weil eine Referenzarchitektur ein weiteres Kästchen enthält.

## Wichtigste Empfehlungen

1. Bronze, Silver und Gold können als technische Kurzform verwendet werden, dürfen aber keine logische Warehouse-Architektur ersetzen.
2. Unveränderte Übernahme, technische Standardisierung, fachliche Integration, Datenprodukte und consumerspezifische Bereitstellung sollten klar getrennt werden.
3. Raw sollte als nachvollziehbarer Quellnachweis erhalten bleiben.
4. Business Keys, Golden Records und Historie sollten zentral aufgelöst werden.
5. Fakten, Dimensionen und KPI-Basen gehören in governte Datenprodukte.
6. Gemeinsam genutzte Fachlogik sollte außerhalb einzelner Qlik-Apps, Power-BI-Berichte und Excel-Arbeitsmappen liegen.
7. Consumerspezifische Logik sollte nur dort entstehen, wo der jeweilige Consumer sie tatsächlich benötigt.
8. Datenqualität, Governance, Sicherheit, Lineage, Observability und CI/CD wirken über alle Schichten.
9. Die logischen Grenzen sollten zunächst mit den vorhandenen Werkzeugen umgesetzt werden, bevor eine weitere Plattform ergänzt wird.
10. Verwende die kleinste physische Architektur, die Verantwortlichkeiten explizit und wartbar hält.

## Übergang zum nächsten Part

Präzise Schichten klären, was zwischen einer Quelle und einem vertrauenswürdigen Datenprodukt geschehen muss. Sie legen noch nicht fest, wie viele Werkzeuge, Engines oder physische Plattformen erforderlich sind.

Der nächste Part, [Die einfachste tragfähige Architektur auswählen](/playbooks/choosing-the-simplest-viable-architecture), leitet aus Anforderungen wie Datenvolumen, Aktualität, Transformationskomplexität, Teamgröße, Governance und Consumer-Bedarf die einfachste Architektur ab, die funktionieren kann.
