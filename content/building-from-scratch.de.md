---
title: Ein Warehouse von Grund auf aufbauen
description: Wie ein Greenfield-Warehouse ausgehend von einer Business-Frage und einem vertikalen End-to-End-Datenprodukt aufgebaut wird — und wie aus dem ersten produktiven Use Case ein wiederverwendbares Plattformmuster entsteht.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - greenfield
  - vertical-slice
  - data-products
  - dimensional-modeling
  - data-quality
  - qlik-sense
  - power-bi
  - excel
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - sql
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 4
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start4-hero.png
---

## Greenfield-Freiheit kann die falsche Komplexität erzeugen

Ein Warehouse von Grund auf aufzubauen wirkt einfacher, als ein bestehendes Warehouse zu modernisieren. Es gibt keine Legacy-Schemas, die erhalten werden müssen, keine gewachsenen Ladeketten, die entwirrt werden müssen, und keine alten Berichte, die weiterhin funktionieren müssen.

Diese Freiheit ist nützlich. Sie erzeugt aber auch ein häufiges Fehlermuster: Das Team versucht, die vollständige Plattform zu bauen, bevor es eine einzige vertrauenswürdige Antwort geliefert hat.

Typische Greenfield-Pläne beginnen mit breiter technischer Arbeit:

```text
Alle Quellen anbinden
Die gesamte verfügbare Historie laden
Alle technischen Schichten aufbauen
Alle wichtigen fachlichen Entitäten modellieren
Jedes künftig denkbare Tool auswählen
Eine unternehmensweite Plattform definieren
Das Business nach seinen Anforderungen fragen
```

Diese Reihenfolge erzeugt Aktivität, ohne Nutzen zu beweisen. Pipelines, Schemas und Umgebungen wachsen, während das Team noch keine konkrete Business-Frage beantworten kann. Die Architektur wird dadurch für Annahmen optimiert und nicht für beobachtete Anforderungen.

Part 1, [Bevor die erste Tabelle entsteht](/playbooks/before-building-the-first-table), hat die Entscheidungen definiert, die vor der Umsetzung getroffen werden müssen. Part 2, [Mehr als Bronze, Silver und Gold](/playbooks/beyond-bronze-silver-gold), hat die logischen Verantwortlichkeiten von der Quelle bis zur governten Nutzung getrennt. Part 3, [Die einfachste tragfähige Architektur auswählen](/playbooks/choosing-the-simplest-viable-architecture), hat gezeigt, wie nur die tatsächlich benötigten physischen Fähigkeiten ausgewählt werden.

Dieser Part wendet diese Prinzipien auf eine Greenfield-Umsetzung an.

> **Baue das Warehouse nicht zuerst horizontal auf. Bringe zunächst einen vollständigen vertikalen Pfad von der Business-Frage bis zur Nutzung zum Laufen, lerne daraus und verwende das bewährte Muster erneut.**

## Der falsche Start

Der falsche Start wird nicht durch ein bestimmtes Tool definiert. Entscheidend ist die Reihenfolge der Entscheidungen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start4-img1-de.png"
        alt="Die falsche Greenfield-Reihenfolge: Plattform auswählen, alles anbinden, generische Schichten aufbauen, Berichte erstellen und anschließend feststellen, dass kein klarer Business-Nutzen entstanden ist"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein technologiegetriebenes Programm erzeugt Quellen, Schichten und Berichte, bevor Zielentscheidung, KPI und Datenprodukt explizit sind. Das Ergebnis sind häufig hohe Kosten, unfertige Integration und geringes Vertrauen.
    </figcaption>
</figure>

Das Anti-Pattern folgt meist vier Annahmen:

1. Mehr geladene Daten schaffen mehr zukünftige Flexibilität.
2. Generische Schichten können vor dem ersten realen Use Case gestaltet werden.
3. Fachliche Definitionen können später ergänzt werden.
4. Das Reporting-Tool wird zeigen, welche Fragen wichtig sind.

Jede Annahme enthält einen wahren Kern. Zusammen kehren sie jedoch die korrekte Abhängigkeit um.

Ein Warehouse ist wertvoll, weil es verlässliche Informationen für definierte Entscheidungen liefert. Die benötigten Quellen, die Historie, die Integrationslogik, die Qualitätskontrollen und die Service Levels können erst dann korrekt ausgewählt werden, wenn dieser Zweck verstanden ist.

Wer zuerst alle Quellen lädt, erzeugt außerdem ein Ownership-Problem. Ein technisches Team wird für Datensätze verantwortlich, deren fachliche Bedeutung, Kritikalität und erwartete Qualität noch nicht abgestimmt sind. Die Plattform enthält dann Daten, aber noch keine governbaren Produkte.

## Architekturprinzip: rückwärts entwerfen, vorwärts bauen

Ein Greenfield-Warehouse sollte vom benötigten Ergebnis rückwärts entworfen und anschließend durch einen kontrollierten Datenpfad vorwärts implementiert werden.

Die Entwurfsreihenfolge lautet:

```flow linear vertical
Business-Frage
Entscheidung und KPI
Zielgranularität
Zielfakt und Zieldimensionen
Benötigte Felder
Benötigte Quellen
Qualitäts- und Historienregeln
Nutzungsvertrag
```

Die Umsetzungsreihenfolge lautet:

```flow linear vertical
Benötigte Quellextrakte
Kontrollierte Ingestion
Standardisierung
Integration und Historie
Datenprodukt
Qualitätsergebnisse
Qlik / Power BI / Excel
```

Beide Reihenfolgen treffen sich in der Mitte. Das Zielmodell zeigt, was geladen werden muss. Die Quellenanalyse zeigt, was tatsächlich geliefert werden kann und wo Annahmen angepasst werden müssen.

Dieser Ansatz vermeidet zwei Extreme:

- **quellengetriebene Übernahme ohne Ziel**, bei der alles geladen wird, ohne einen definierten Zweck zu besitzen;
- **lokale reportgetriebene Modellierung**, bei der eine Antwort schnell in einer BI-Anwendung entsteht, aber nicht wiederverwendbar ist.

Das Ziel ist ein vollständiger, schmaler und governbarer Pfad.

## Der Vertical-Slice-Ansatz

Ein Vertical Slice enthält genügend Bestandteile jeder erforderlichen Fähigkeit, um ein vertrauenswürdiges Ergebnis zu produzieren. Sein Umfang ist bewusst schmal, seine Verantwortlichkeiten sind jedoch vollständig.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start4-img2-de.png"
        alt="Vertikaler Slice von einer Business-Frage über Zieldatenprodukt, minimale Quellen, Ingestion, Integration, governte Schichten und Nutzung bis zur wiederverwendbaren Skalierung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine Business-Frage bestimmt Granularität, benötigte Quellen, Transformationen, Tests und Consumer-Schnittstelle. Der Slice liefert früh Nutzen und wird zur Referenzumsetzung für weitere Datenprodukte.
    </figcaption>
</figure>

Ein geeigneter erster Slice sollte:

- wertvoll genug sein, dass das Business ihn tatsächlich nutzt;
- klein genug sein, um ihn in einem begrenzten Lieferzyklus abzuschließen;
- repräsentativ genug sein, um zentrale Plattformfähigkeiten zu erproben;
- explizit genug sein, um Ownership-, Qualitäts- und Security-Fragen sichtbar zu machen;
- wiederverwendbar genug sein, um Muster für spätere Arbeit zu erzeugen.

Er sollte kein Wegwerfprototyp sein. Der Umfang ist minimal, die Umsetzung aber produktionsorientiert.

## Konkretes Beispiel: das Sales Daily Data Product

Angenommen, die erste Warehouse-Anforderung ist die tägliche Vertriebssteuerung.

Die Business-Frage lautet:

> Welchen Nettoumsatz haben wir nach Business-Datum, Kunde, Produkt und Land erzielt, und welche Aufträge wurden seit dem vorherigen Ladevorgang geändert oder storniert?

Die ersten Entscheidungen sind:

| Entscheidungsbereich | Definition |
| --- | --- |
| Fachlicher Zweck | Tägliche Vertriebssteuerung und Analyse von Ausnahmen |
| KPI | Nettoumsatz ohne stornierte Auftragszeilen |
| Granularität | Ein Datensatz pro Auftragszeile und Business-Datum |
| Benötigte Dimensionen | Kunde, Produkt, Land, Auftragsstatus |
| Aktualität | Jeden Morgen vor dem Business Review verfügbar |
| Historie | Kunden- und Produktattribute müssen für das Auftragsdatum reproduzierbar sein |
| Qualität | Gültige Auftrags-, Kunden-, Produkt- und Länderkennung sowie Betrag, Status und Änderungszeitstempel |
| Consumer | Qlik, Power BI und kontrollierter Excel-Zugriff |
| Business Owner | Sales Controlling |
| Technischer Owner | Data-Platform-Team |
| Security | Vertriebsdetails nur für freigegebene Zugriffe; aggregierte Sichten können breiter verfügbar sein |
| Fehlerverhalten | Fehlgeschlagene Pflichtprüfungen werden sichtbar und verhindern bei wesentlichen Fehlern die vertrauenswürdige Veröffentlichung |

Diese Tabelle ist wichtiger als die erste Pipeline-Definition. Sie bestimmt, was die Pipeline nachweisen muss.

## Mit dem Zielvertrag beginnen

Das erste Ziel ist kein Dashboard. Es ist ein governter Datenproduktvertrag.

Ein praktikabler Vertrag für `sales_daily` könnte folgende Felder enthalten:

| Feld | Bedeutung |
| --- | --- |
| `business_date` | Datum für die tägliche Vertriebsanalyse |
| `order_id` | Stabiler Quellbezeichner des Auftrags |
| `order_line_id` | Stabiler Positionsbezeichner innerhalb des Auftrags |
| `customer_key` | Governter Warehouse-Kundenschlüssel |
| `product_key` | Governter Warehouse-Produktschlüssel |
| `country_code` | Standardisierter Ländercode |
| `order_status` | Standardisierter fachlicher Status |
| `gross_revenue` | Quellbetrag vor Anwendung der Stornoregel |
| `net_revenue` | Governter Betrag für den täglichen Vertriebs-KPI |
| `source_changed_at` | Letzter relevanter Änderungszeitstempel der Quelle |
| `loaded_at` | Verarbeitungszeitstempel des Warehouses |
| `quality_status` | Veröffentlichungsstatus oder Qualitätskennzeichen |

Der Vertrag sollte zusätzlich dokumentieren:

- die Granularität;
- zulässige Nullwerte;
- erlaubte Domains;
- fachliche Filter;
- Historienverhalten;
- Aktualitätserwartung;
- Owner;
- Sicherheitsklassifikation;
- Kompatibilitätserwartungen für Consumer.

Der Zielvertrag verhindert, dass Qlik, Power BI und Excel leicht unterschiedliche Interpretationen desselben fachlichen Konzepts erhalten.

## Das Zielmodell ableiten, bevor jede Quelltabelle untersucht wird

Fakt und Dimensionen können nun auf logischer Ebene definiert werden.

```text
fact_sales_order_line
dim_customer
dim_product
dim_country
dim_order_status
mart.sales_daily
```

Der erste Slice benötigt weder ein vollständiges Customer 360 noch eine vollständige Produktdomäne oder jede denkbare Vertriebskennzahl. Er benötigt nur die Attribute, die zur korrekten Beantwortung der freigegebenen Frage erforderlich sind.

Beispielsweise:

- `dim_customer` benötigt Kundenidentität, Land und die für den Verkauf relevante Historie;
- `dim_product` benötigt Produktidentität und die Reporting-Attribute der ersten Analyse;
- `fact_sales_order_line` benötigt Auftragszeilengranularität, Datumsfelder, Status und Umsatzfelder;
- `mart.sales_daily` stellt den stabilen Nutzungsvertrag bereit.

Weitere Attribute werden erst ergänzt, wenn ein konkreter Use Case sie verlangt oder ihre Aufnahme nahezu kostenfrei und eindeutig governt ist.

## Die minimal benötigten Quellen bestimmen

Der Zielvertrag bestimmt den Quellumfang.

| Anforderung | Minimale Quelle |
| --- | --- |
| Auftrag und Positionskennung | ERP-Auftragskopf und ERP-Auftragsposition |
| Business-Datum | ERP-Auftrags- oder Buchungsdatum |
| Kundenzuordnung | ERP-Kundenreferenz im Auftrag |
| Kundenattribute | CRM oder Kundenstamm |
| Produktzuordnung | ERP-Auftragsposition und Produktstamm |
| Land | Governte Kunden- oder Referenzzuordnung |
| Status und Stornos | ERP-Auftragsstatus und Stornokennzeichen |
| Änderungserkennung | Verlässlicher Quelländerungszeitstempel, CDC-Marker oder Extraktvergleich |
| Referenzdomains | Länder- und Statusreferenzdaten |

Die Quellenanalyse sollte folgende Fragen beantworten:

- Welches System ist für jedes Feld führend?
- Können Änderungen zuverlässig erkannt werden?
- Werden Löschungen dargestellt?
- Ist Historie vorhanden oder muss sie ab künftigen Ladevorgängen aufgebaut werden?
- Können Datensätze wiederholbar extrahiert werden, ohne Dubletten zu erzeugen?
- Welche Schlüssel verbinden die Quellen?
- Welche Felder enthalten sensible Daten?
- Was geschieht bei verspäteter Lieferung einer Quelle?

Nur die für den Slice benötigten Quellobjekte und Spalten werden in den ersten Ingestion-Umfang aufgenommen.

## Die kleinste produktionsfähige Umsetzung bauen

Eine minimale SQL-native Umsetzung kann eine vorhandene Datenbank und einen Scheduler verwenden.

```flow linear vertical
ERP- / CRM- / Referenzextrakte
raw-Schema
stg-Schema
core-Schema
mart-Schema
quality-Schema
Qlik / Power BI / Excel
```

Die logischen Verantwortlichkeiten bleiben getrennt, auch wenn sie dieselbe Datenbank verwenden.

Eine praktikable Namensstruktur könnte so aussehen:

```text
raw.erp_order_line
raw.crm_customer
raw.product_master
raw.country_reference

stg.erp_order_line
stg.crm_customer
stg.product_master

core.customer_history
core.product_history
core.sales_order_line

mart.sales_daily

quality.test_run
quality.test_result
```

Diese Umsetzung bietet bereits die wesentliche Architektur:

- nachvollziehbare Ingestion;
- standardisierte Datentypen und Domains;
- governte Schlüssel und Historie;
- eine stabile Faktgranularität;
- ein wiederverwendbares Datenprodukt;
- persistente Qualitätsergebnisse;
- kontrollierten Consumer-Zugriff.

Sie benötigt weder ein Lakehouse noch ein Transformationsframework oder mehrere Compute Engines, solange die Anforderungen diese Komponenten nicht rechtfertigen.

## Nur das laden, was der Slice benötigt

Der Ingestion-Prozess sollte wiederholbar, beobachtbar und sicher erneut ausführbar sein.

Für jedes Quellobjekt sollten mindestens folgende Informationen erfasst werden:

```text
source_system
source_object
source_record_key
source_changed_at
extract_batch_id
ingested_at
source_file_or_request_id
```

Der erste Lauf kann vollständig oder inkrementell sein. Die Architektur sollte dennoch definieren, wie spätere Änderungen behandelt werden.

Ein kontrolliertes inkrementelles Muster kann verwenden:

- Source CDC;
- einen verlässlichen `changed_at`-Zeitstempel;
- einen monoton steigenden Schlüssel;
- Dateimanifeste;
- Prüfsummen oder Snapshot-Vergleiche, wenn kein Änderungsmarker vorhanden ist.

Die konkrete Methode hängt von der Quelle ab. Das Prinzip bleibt stabil:

> **Inkrementelle Extraktion ist nicht dasselbe wie fachliche Historie.**

Ein Quelländerungszeitstempel zeigt der Pipeline, welche Datensätze geändert wurden. Er bewahrt nicht automatisch die historisch gültige Kunden- oder Produktversion, die das Reporting benötigt.

## Vor der Integration standardisieren

Die Standardisierung macht Quelldaten technisch konsistent.

Typische Regeln für das Beispiel sind:

- Auftragskennungen trimmen und typisieren;
- Datums- und Zeitstempelformate normalisieren;
- Länderwerte auf einen freigegebenen Code abbilden;
- Quellstatus auf eine kontrollierte Domain mappen;
- verpflichtende Schlüssel prüfen;
- nicht lesbare Beträge ablehnen oder in Quarantäne verschieben;
- ursprüngliche Quellwerte für die Nachvollziehbarkeit erhalten;
- Validierungsergebnisse am Datensatz oder Batch dokumentieren.

Eine vereinfachte standardisierte View für Auftragspositionen könnte so aussehen:

```sql
select
    trim(cast(order_id as varchar(100))) as order_id,
    trim(cast(order_line_id as varchar(100))) as order_line_id,
    cast(order_date as date) as business_date,
    trim(cast(customer_id as varchar(100))) as customer_id,
    trim(cast(product_id as varchar(100))) as product_id,
    upper(trim(order_status)) as source_order_status,
    cast(gross_amount as decimal(18, 2)) as gross_revenue,
    cast(changed_at as timestamp) as source_changed_at,
    extract_batch_id,
    ingested_at
from raw.erp_order_line;
```

Die genaue Syntax unterscheidet sich je Plattform. Entscheidend ist die Platzierung der Verantwortung.

## Schlüssel, Domains und Historie zentral integrieren

Die Integration übersetzt Quelldatensätze in gemeinsame fachliche Bedeutung.

Für den ersten Slice umfasst dies:

- Zuordnung von Quell-Kundenkennungen zu governten Kundenschlüsseln;
- Zuordnung von Produktkennungen zu governten Produktschlüsseln;
- Auswahl der Kunden- und Produktversion, die am Auftragsdatum gültig war;
- Zuordnung von Quellstatus zur freigegebenen Auftragsstatus-Domain;
- Anwendung der gemeinsamen Stornoregel;
- Erhalt nicht zuordenbarer oder ungültiger Datensätze für die Qualitätsanalyse.

Eine vereinfachte zentrale Mart-Transformation könnte so aussehen:

```sql
select
    o.business_date,
    o.order_id,
    o.order_line_id,
    c.customer_key,
    p.product_key,
    c.country_code,
    s.order_status,
    o.gross_revenue,
    case
        when s.is_cancelled = 1 then cast(0 as decimal(18, 2))
        else o.gross_revenue
    end as net_revenue,
    o.source_changed_at,
    current_timestamp as loaded_at
from core.sales_order_line o
join core.customer_history c
  on o.customer_id = c.customer_id
 and o.business_date >= c.valid_from
 and o.business_date < coalesce(c.valid_to, date '9999-12-31')
join core.product_history p
  on o.product_id = p.product_id
 and o.business_date >= p.valid_from
 and o.business_date < coalesce(p.valid_to, date '9999-12-31')
join core.order_status s
  on o.source_order_status = s.source_order_status;
```

Die Regel ist nun vom Consumer unabhängig.

## Qualitätsergebnisse als eigenes Produkt behandeln

Qualitätstests sollten nicht in Pipeline-Logs verborgen sein. Sie sollten persistente, abfragbare Ergebnisse erzeugen.

Ein minimales Modell für Qualitätsergebnisse kann folgende Felder enthalten:

| Feld | Zweck |
| --- | --- |
| `test_run_id` | Kennung des vollständigen Validierungslaufs |
| `data_product` | Getestetes Produkt |
| `object_name` | Getestete Tabelle oder getestetes Modell |
| `rule_id` | Stabiler Bezeichner der Qualitätsregel |
| `severity` | Warnung, Fehler oder blockierend |
| `failed_count` | Anzahl fehlerhafter Datensätze |
| `tested_count` | Anzahl geprüfter Datensätze |
| `failure_rate` | Anteil fehlerhafter Datensätze |
| `executed_at` | Testzeitstempel |
| `status` | Bestanden, Warnung oder fehlgeschlagen |
| `sample_reference` | Optionale Referenz auf Details fehlerhafter Datensätze |

Beispielregeln sind:

```text
DQ-SALES-001: order_id darf nicht null sein
DQ-SALES-002: order_line_id muss innerhalb eines Auftrags eindeutig sein
DQ-SALES-003: customer_key muss aufgelöst werden
DQ-SALES-004: product_key muss aufgelöst werden
DQ-SALES-005: country_code muss zur freigegebenen Domain gehören
DQ-SALES-006: source_changed_at muss vorhanden sein
DQ-SALES-007: net_revenue muss der Stornoregel entsprechen
DQ-SALES-008: Der Ladevorgang muss das vereinbarte Aktualitätsziel erfüllen
```

Eine blockierende Regel kann die Veröffentlichung von `mart.sales_daily` verhindern. Eine Warnung kann das Produkt mit sichtbarem Status veröffentlichen. Diese Entscheidung gehört in den Produktvertrag.

## Consumer schlank halten

Sobald das Datenprodukt existiert, sollte jeder Consumer dieselbe governte Basis erhalten.

Ein bewusst schlankes Qlik-Load-Skript kann so aussehen:

```qlik
SalesDaily:
SQL SELECT
    business_date,
    order_id,
    order_line_id,
    customer_key,
    product_key,
    country_code,
    order_status,
    gross_revenue,
    net_revenue,
    source_changed_at,
    loaded_at,
    quality_status
FROM mart.sales_daily;
```

Qlik-spezifische Assoziationen, Section Access oder Präsentationsfelder können in Qlik verbleiben, wenn sie tatsächlich erforderlich sind. Kundenintegration, Status-Mapping, historische Zuordnung und KPI-Definition sollten dort nicht erneut aufgebaut werden.

Power BI kann ein Semantikmodell über denselben governten Fakten und Dimensionen verwenden. Präsentations-Measures, Formatierung und Berichtsinteraktionen bleiben consumerspezifisch, während Nettoumsatzbasis und fachliche Granularität gemeinsam bleiben.

Excel kann eine governte Reporting-View, eine PivotTable-Verbindung oder einen kontrollierten Extrakt verwenden. Es sollte keinen unkontrollierten Raw-Export erhalten, der dieselben Definitionen und das Security-Modell umgeht.

## Vom ersten Use Case zum Plattformmuster

Das erste Datenprodukt ist nicht nur ein Lieferobjekt. Es ist ein Architekturexperiment, das sichtbar macht, welche Standards tatsächlich erforderlich sind.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start4-img3-de.png"
        alt="Entwicklung vom ersten fachlichen Use Case über Validierung und Standardisierung zu wiederverwendbaren Komponenten und einem governten Plattformmuster"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der erste produktive Slice validiert die Architektur. Bewährte Entscheidungen für Naming, Orchestrierung, Testing, Security, Deployment, Monitoring und Nutzung werden anschließend wiederverwendet, statt für jedes Datenprodukt neu gestaltet zu werden.
    </figcaption>
</figure>

Das Team sollte Entscheidungen erst dann als wiederverwendbare Muster festhalten, nachdem sie im Slice praktisch erprobt wurden.

| Fähigkeit | Aus dem ersten Slice abgeleitetes Muster |
| --- | --- |
| Naming | Konventionen für Quelle, Schicht, Objekt und Feld |
| Ingestion | Batch-Metadaten, Wiederanlauf, Watermarks und Fehlerbehandlung |
| Orchestrierung | Abhängigkeitsreihenfolge, Wiederholungen, Timeouts und Veröffentlichungsschranken |
| Modellierung | Faktgranularität, Surrogate Keys, Historie und Behandlung von Referenzdomains |
| Testing | Regelkennungen, Schweregrade, Ergebnistabellen und Veröffentlichungsverhalten |
| Security | Owner, Klassifikation, Rollenzuordnung und Consumer-Zugriff |
| Deployment | Versionsverwaltung, Umgebungskonfiguration und Release-Reihenfolge |
| Monitoring | Ladestatus, Aktualität, Volumen, Dauer und Qualitätsindikatoren |
| Nutzung | Stabile Views, Semantikverträge und Grenzen der BI-spezifischen Logik |
| Dokumentation | Owner, Definition, Lineage, SLA und Änderungsnotizen |

Das zweite Datenprodukt sollte diese Muster wiederverwenden und sie dort infrage stellen, wo es notwendig ist. Ein Muster wird zum Plattformstandard, wenn mehrere Use Cases zeigen, dass es stabil, verständlich und wirtschaftlich ist.

Das Ziel besteht nicht darin, den ersten Entwurf dauerhaft einzufrieren. Das Ziel besteht darin, sich aus Evidenz statt aus Spekulation weiterzuentwickeln.

## Greenfield-Optionen nach vorhandenem Stack

Der logische Slice bleibt plattformübergreifend gleich. Die Umsetzung sollte vorhandene Fähigkeiten nutzen und eine weitere Komponente nur dann ergänzen, wenn sie ein konkretes Problem löst.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start4-img4-de.png"
        alt="Vergleich von Greenfield-Startpunkten mit Dateien und Excel, einer On-Premises-SQL-Datenbank, einem Cloud Data Warehouse, einem Data Lake oder Lakehouse sowie einem besonders leichtgewichtigen Start"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Greenfield bedeutet keine verpflichtende Plattform. Ein kontrollierter Datei- oder SQL-Start kann den Use Case validieren. Fabric, Snowflake oder Databricks sind sinnvoll, wenn ihre nativen Fähigkeiten zum vorhandenen Stack und Workload passen.
    </figcaption>
</figure>

Das Schaubild zeigt breite Startpositionen. Dieselbe logische Architektur lässt sich vier verbreiteten produktiven Umsetzungsmustern zuordnen.

### SQL-native

Eine SQL-native Umsetzung ist für relationale tägliche Batch-Workloads häufig die einfachste produktive Option.

| Verantwortung | Mögliche Umsetzung |
| --- | --- |
| Ingestion | Vorhandenes ETL, Scheduler, Datenbank-Jobs oder kontrollierte Dateiladevorgänge |
| Standardisierung | SQL-Views, Prozeduren oder geplante SQL-Modelle |
| Integration | Core-Schemas, Key-Mappings und Historientabellen |
| Mart | Kuratierte Fakten, Dimensionen und Reporting-Views |
| Tests | SQL-Prüfungen mit persistenten Qualitätsergebnistabellen |
| Nutzung | Qlik Loads, Power-BI-Semantikmodelle und Excel-Views |
| Optionale Erweiterung | dbt, wenn Abhängigkeiten, Zusammenarbeit, Testing oder Deployment es rechtfertigen |

Diese Variante kann auf einer On-Premises-Datenbank oder einer gemanagten Cloud-Datenbank laufen. Die Architektur wird durch Verantwortlichkeiten definiert und nicht durch den Standort.

### Microsoft-Fabric-native

Eine Fabric-Umsetzung kann denselben vertikalen Slice in einer integrierten Microsoft-Umgebung realisieren.

Eine mögliche Zuordnung ist:

- Data-Factory-Pipelines, Copy-Aktivitäten oder Dataflows für die Ingestion;
- ein Lakehouse oder Warehouse für gemanagten analytischen Speicher;
- SQL, Notebooks oder Dataflows für Standardisierung und Integration;
- kuratierte Tabellen für das Sales-Daily-Datenprodukt;
- persistente Qualitätsergebnistabellen;
- Power-BI-Semantikmodelle für die native Nutzung;
- Qlik oder Excel über unterstützte SQL- oder Datenschnittstellen auf governte Ergebnisse;
- Deployment- und Monitoring-Fähigkeiten, sobald das Plattformmuster reift.

Fabric ist keine Voraussetzung für den Use Case. Es ist angemessen, wenn das Unternehmen bereits das Microsoft-Ökosystem nutzt und die integrierte Plattform Übergaben und Betriebsaufwand reduziert.

dbt bleibt optional. Es ist nur dann sinnvoll, wenn der gewählte Fabric-Workflow und das Teammodell von modularer SQL-Entwicklung, Tests und Deployment-Praktiken im dbt-Stil profitieren.

### Snowflake-native

Eine Snowflake-Umsetzung kann verwenden:

- Batch- oder kontinuierliches Laden über den verfügbaren Ingestion-Mechanismus;
- Raw- und standardisierte Tabellen in getrennten Schemas;
- SQL-Transformationen, geplante Tasks oder Dynamic-Table-Muster, wo sie angemessen sind;
- dbt als optionales Transformations- und Testframework;
- integrierte Fakten, Dimensionen und das `sales_daily`-Mart;
- Qualitätsergebnistabellen und Veröffentlichungskontrollen;
- governte Views für Qlik, Power BI und Excel.

Snowflake wird zur logischen Grundlage, wenn ein gemanagtes Cloud Data Warehouse bereits die strategische Plattform ist oder sein Betriebsmodell eine konkrete Anforderung löst. Es sollte nicht allein deshalb eingeführt werden, weil das Projekt Greenfield ist.

### Databricks-native

Eine Databricks-Umsetzung kann verwenden:

- Batch-Ingestion oder Auto Loader für Dateien in Cloud Object Storage;
- Delta-Tabellen für kontrollierte Datenzustände;
- SQL, Spark oder gemanagte Pipeline-Fähigkeiten für Transformationen;
- Workflows für die Orchestrierung;
- Qualitätsregeln und persistente Fehlerergebnisse;
- kuratierte Delta-Tabellen oder Views für das Sales-Daily-Produkt;
- governte SQL-Schnittstellen für Qlik, Power BI und Excel;
- dbt als optionalen SQL-Entwicklungspfad.

Databricks ist insbesondere relevant, wenn der Use Case voraussichtlich zu hohen Volumina, vielfältigen Daten, Streaming, Data Science oder ML-Workloads wächst. Ein kleiner relationaler täglicher Batch benötigt nicht allein deshalb verteilte Verarbeitung, weil die Plattform verfügbar ist.

### Dateien, Excel oder noch keine Plattform

Ein sehr kleiner kontrollierter Prototyp kann mit Dateien, Excel und Power Query beginnen, wenn zunächst Business-Frage und Zielvertrag validiert werden sollen.

Dieser Start ist nur dann geeignet, wenn:

- der Umfang sehr klein ist;
- Zugriffe kontrolliert sind;
- die Quelle reproduziert werden kann;
- Transformationen dokumentiert sind;
- das Ergebnis eindeutig als Validierungsstufe gekennzeichnet ist;
- vor geschäftskritischer Nutzung ein Übergang in ein Produktionsmuster geplant wird.

Der Prototyp darf nicht unbemerkt zum dauerhaften Enterprise-Warehouse werden.

## Wie dbt hineinpasst, ohne verpflichtend zu werden

dbt kann SQL-basierten Transformationen ergänzt werden, wenn es das Entwicklungs- und Betriebsmodell verbessert.

Es ist besonders nützlich, wenn:

- viele Modelle voneinander abhängen;
- mehrere Entwickler zusammenarbeiten;
- Pull Requests und Code Reviews erforderlich sind;
- Tests gemeinsam mit den Modellen definiert werden sollen;
- Dokumentation und Lineage eine einheitliche Struktur benötigen;
- Deployments zwischen Umgebungen wiederholbar werden sollen.

Es kann wenig Mehrwert liefern, wenn ein Entwickler eine kleine Anzahl verständlicher SQL-Transformationen in einem bereits kontrollierten Deployment-Prozess pflegt.

Die Entscheidung lautet deshalb nicht:

```text
Ist dbt eine moderne Best Practice?
```

Sondern:

```text
Löst dbt in dieser Umsetzung ein Problem bei Zusammenarbeit, Abhängigkeiten, Testing oder Deployment?
```

## Typische Anti-Patterns

### Alle Quellen laden, bevor ein Produkt definiert ist

Dadurch entstehen Speicher- und Pipeline-Verpflichtungen ohne vereinbarten Business-Nutzen, Qualität oder Ownership.

### Das unternehmensweite kanonische Modell im ersten Sprint entwerfen

Ein vollständiges Enterprise-Modell kann nicht von einem Team validiert werden, bevor konkrete Use Cases seine Annahmen praktisch erproben. Beginne mit den wiederverwendbaren Konzepten, die der erste Slice benötigt, und erweitere bewusst.

### Den ersten Slice als Wegwerfprodukt behandeln

Ein Proof of Concept mit hartcodierten Pfaden, manuellen Schritten und versteckter Logik beweist nicht, dass die Architektur betrieben werden kann. Der Slice sollte im Umfang minimal, im Betrieb aber produktionsorientiert sein.

### Den KPI im Dashboard aufbauen

Wird der Nettoumsatz unabhängig in Qlik, Power BI und Excel definiert, enthält das erste Datenprodukt bereits drei konkurrierende Verträge.

### Qlik als verstecktes Warehouse verwenden

Qlik kann anspruchsvolle Transformations- und Assoziationslogik ausführen. Gemeinsame Integration, Historie, Status-Mapping und KPI-Basen sollten dennoch vorgelagert werden, sobald mehrere Produkte oder Consumer sie benötigen.

### Bronze, Silver und Gold ohne explizite Verantwortlichkeiten erzeugen

Schichtnamen definieren weder Ownership noch Qualität, Historie oder Nutzungsverträge. Der erste Slice muss diese Verantwortlichkeiten sichtbar machen.

### Fabric, Snowflake, Databricks und dbt gleichzeitig einführen

Jedes Produkt kann nützlich sein. Werden alle eingeführt, bevor der Workload einen Bedarf beweist, entstehen mehrere Betriebsmodelle, Security-Grenzen und Fehlerpunkte.

### Jeden zukünftigen Use Case aus dem ersten standardisieren

Der erste Slice sollte Kandidaten für Standards erzeugen. Ein Muster wird erst dann verpflichtend, wenn seine Wiederverwendung zeigt, dass es weiterhin angemessen ist.

### Den Betrieb bis zum zweiten Produkt ignorieren

Wiederanlauf, Monitoring, fehlerhafte Datensätze, Deployment und Support gehören zum ersten produktiven Slice. Sie sind keine nachgelagerte Plattformarbeit.

### Daten in jedes BI-Tool kopieren

Unkontrollierte QVDs, Semantikmodell-Kopien und Excel-Exporte können den Vertrag fragmentieren. Consumerspezifische Bereitstellung ist zulässig, muss aber auf das governte Produkt zurückverfolgbar bleiben.

## Entscheidungshilfe

| Situation | Empfohlene Greenfield-Reaktion |
| --- | --- |
| Eine klare Business-Frage und relationale Quellen | Einen SQL-orientierten vertikalen Slice mit expliziten Schemas und Tests bauen |
| Vorhandene Microsoft-Analytics-Landschaft | Fabric-native Fähigkeiten nutzen, wenn sie Integration und Betriebsaufwand reduzieren |
| Vorhandene Snowflake-Plattform | Dieselben logischen Schichten und denselben Produktvertrag in Snowflake umsetzen; dbt nur bei echtem Nutzen ergänzen |
| Vorhandene Databricks-Plattform oder verteilter Workload | Delta und Databricks-native Verarbeitung nutzen; Spark-Komplexität bei trivialen Transformationen vermeiden |
| Es stehen nur Dateien oder Tabellenkalkulationen zur Verfügung | Zielvertrag mit einem kontrollierten kleinen Prototyp validieren und anschließend einen Produktionspfad etablieren |
| Mehrere BI-Tools benötigen das Ergebnis | Ein governtes Datenprodukt mit getrennten, schlanken Consumer-Verträgen veröffentlichen |
| Quellhistorie ist nicht verfügbar | Änderungen sofort erfassen und die historische Einschränkung dokumentieren |
| Qualitätsfehler müssen auditierbar sein | Regel- und datensatzbezogene Ergebnisse außerhalb flüchtiger Pipeline-Logs persistieren |
| Der zweite Use Case ist ähnlich | Die Muster des ersten Use Cases wiederverwenden, bevor neue Komponenten eingeführt werden |
| Der zweite Use Case stellt das Muster infrage | Den Standard explizit überarbeiten, statt eine versteckte Ausnahme zu erzeugen |
| Zusammenarbeit im Team wird schwierig | Versionsbasierte modulare Transformationswerkzeuge wie dbt dort ergänzen, wo sie das Problem lösen |
| Zukünftige Skalierung ist unsicher | Klare Grenzen und Observability erhalten; die physische Architektur bei gemessenem Bedarf skalieren |

## Wichtigste Empfehlungen

1. Beginne mit einer Business-Entscheidung und nicht mit dem Plattforminventar.
2. Definiere KPI, Granularität, Owner, Qualität und Nutzungsvertrag vor der Ingestion.
3. Entwirf rückwärts vom Zieldatenprodukt und baue vorwärts aus den benötigten Quellen.
4. Lade nur die Quellobjekte und Felder, die der erste Slice benötigt.
5. Bewahre nachvollziehbare Raw-Daten und unterscheide Extraktionsänderungen von fachlicher Historie.
6. Standardisiere technische Formate, bevor gemeinsame fachliche Integration erfolgt.
7. Löse Schlüssel, Historie, Domains und gemeinsame KPI-Logik zentral.
8. Persistiere Qualitätsergebnisse und definiere das Veröffentlichungsverhalten explizit.
9. Halte Qlik, Power BI und Excel als Consumer desselben governten Produkts schlank.
10. Nutze vorhandene SQL-, Fabric-, Snowflake- oder Databricks-Fähigkeiten, bevor eine weitere Plattform ergänzt wird.
11. Behandle dbt als optionales Entwicklungsframework und nicht als Warehouse-Voraussetzung.
12. Halte den ersten Slice im Umfang minimal, im Betrieb aber produktionsfähig.
13. Überführe bewährte Entscheidungen in wiederverwendbare Muster für Naming, Orchestrierung, Testing, Security, Deployment und Monitoring.
14. Validiere Standards mit dem zweiten und dritten Datenprodukt, bevor sie als universell gelten.
15. Erweitere das Warehouse jeweils um einen governten vertikalen Slice.

## Übergang zum nächsten Part

Ein Greenfield-Warehouse kann aus einem vertikalen Slice zu einem wiederverwendbaren Plattformmuster wachsen. Bestehende Warehouses benötigen einen anderen Einstiegspunkt, weil Quellen, Modelle, Berichte und duplizierte Fachlogik bereits vorhanden sind.

Der nächste Part, [Ein bestehendes Warehouse modernisieren](/playbooks/modernizing-an-existing-warehouse), wendet dieselben Architekturprinzipien auf eine Brownfield-Umgebung an, ohne einen vollständigen Neubau vorauszusetzen.
