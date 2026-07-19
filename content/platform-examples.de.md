---
title: Eine Architektur – mehrere Plattformen
description: Wie dieselbe governte Warehouse-Architektur mit SQL Server und On-Premises-Infrastruktur, Microsoft Fabric, Snowflake, Databricks oder bewusst gewählten hybriden Kombinationen umgesetzt werden kann, ohne einen Produkt-Stack zur allgemeinen Voraussetzung zu machen.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - platform-architecture
  - reference-architecture
  - sql-server
  - on-premises
  - microsoft-fabric
  - onelake
  - fabric-lakehouse
  - fabric-warehouse
  - power-bi
  - power-bi-report-server
  - qlik-sense
  - excel
  - snowflake
  - dynamic-tables
  - databricks
  - delta-lake
  - unity-catalog
  - lakehouse
  - dbt
  - data-products
  - semantic-model
  - data-quality
  - data-governance
  - hybrid-cloud
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 9
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start9-hero.png
---

## Die Plattform ist eine Umsetzung — nicht die Architektur

Ein modernes Data Warehouse benötigt klare Verantwortlichkeiten:

```text
Quelldaten erfassen
Einen wiederherstellbaren Rohzustand erhalten
Datensätze standardisieren und integrieren
Schlüssel und Historien auflösen
Gemeinsame Geschäftsregeln anwenden
Governte Datenprodukte veröffentlichen
Analytische und operative Consumer versorgen
Sicherheit, Qualität, Lineage und Monitoring über alle Schichten betreiben
```

Keine dieser Verantwortlichkeiten setzt ein bestimmtes Produkt voraus.

Sie können mit einer vorhandenen SQL-Server-Landschaft, Microsoft Fabric, Snowflake, Databricks, einem anderen relationalen Warehouse oder einer bewusst gewählten hybriden Kombination umgesetzt werden. Die technischen Objekte, Ausführungs-Engines und Betriebsmodelle unterscheiden sich. Die logische Architektur sollte erkennbar gleich bleiben.

Ein Tool-first-Design kehrt diese Reihenfolge um:

```text
„Wir haben Fabric gekauft, deshalb muss jeder Workload Fabric nutzen.“
„Wir nutzen Databricks, deshalb muss jede Transformation zu Spark werden.“
„Wir haben Snowflake gewählt, deshalb muss jede Quelle nach Snowflake kopiert werden.“
„Wir haben bereits Qlik, deshalb bleibt das Qlik-Ladeskript das Warehouse.“
„Wir haben dbt eingeführt, deshalb muss jedes SQL-Objekt in dbt neu gebaut werden.“
```

Jede Entscheidung kann für einen konkreten Workload sinnvoll sein. Keine davon ist ein Architekturprinzip.

Part 8, [Optionen für Datentransformationen](/playbooks/transformation-options), hat gezeigt, dass SQL, Stored Procedures, Notebooks, Dataflows, native Plattformfunktionen und dbt legitime Transformationsmechanismen sein können. Dieser Part ordnet die vollständige Architektur unterschiedlichen Plattformen zu.

> **Halte die fachlichen Verantwortlichkeiten stabil. Wähle die technische Umsetzung, die am besten zur vorhandenen Landschaft, zum Workload, zum Team und zum Betriebsmodell passt.**

## Architekturprinzip: Verantwortlichkeiten statt Produktnamen erhalten

Eine portable Referenzarchitektur beschreibt zuerst, was jede Schicht leisten muss, und ordnet erst danach eine Technologie zu.

| Logische Verantwortung | Erforderliches Ergebnis | Mögliche physische Umsetzung |
| --- | --- | --- |
| Quellen | Identifizierte Systeme, Owner, Extraktionserwartungen und Änderungsverhalten | ERP, CRM, Dateien, APIs, Events, operative Datenbanken |
| Ingestion | Zuverlässige, beobachtbare und wiederholbare Erfassung | ETL-/ELT-Job, Pipeline, Replikation, Mirroring, Streaming, geplantes Skript |
| Roh- oder Staging-Schicht | Wiederherstellbarer quellnaher Zustand mit technischen Metadaten | Datenbankschema, Objektspeicher, Delta-Tabellen, interne Stage, governte Dateien |
| Standardisierte Schicht | Bereinigte Datentypen, normalisierte Codes, Deduplizierung und Quellenkonformität | SQL-Tabellen/-Views, Notebook-Ausgabe, deklarative Tabellen, ETL-Mappings |
| Core Warehouse | Integrierte Fakten, Dimensionen, Schlüssel, Historie und wiederverwendbare Regeln | Relationales Warehouse, Lakehouse-Tabellen, Delta-Modell, Snowflake-Tabellen |
| Datenprodukte und Marts | Fachlich nutzbare Verträge mit Owner, Qualität und Veröffentlichungsstatus | Sternschema, kuratierte Views, Aggregate, governte Tabellen, Service Views |
| Semantik und Consumption | Toolgerechte Analyse ohne Neudefinition gemeinsamer Wahrheit | Qlik-Assoziativmodell, Power-BI-Semantikmodell, Excel View, API |
| Übergreifende Kontrollen | Sicherheit, Qualität, Lineage, Observability, Deployment und Lifecycle | Native Plattformkontrollen, Katalog, Control-Tabellen, Monitoring und CI/CD |

Bronze, Silver und Gold können sinnvolle Namen für einige physische Schichten sein. Sie ersetzen die genannten Verantwortlichkeiten nicht. Eine Bronze-Tabelle ohne Wiederherstellbarkeit, Ownership und Ingestion-Metadaten ist keine vollständige Rohschicht. Eine Gold-Tabelle ohne explizite Granularität, fachliche Definition, Qualitätsstatus und Owner ist nicht automatisch ein Datenprodukt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img1-de.png"
        alt="Eine logische Data-Warehouse-Architektur wird auf SQL Server On-Premises, Microsoft Fabric, Snowflake und Databricks abgebildet"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Dieselben logischen Verantwortlichkeiten können mit unterschiedlichen Plattformobjekten umgesetzt werden. Das Schaubild ist eine Zuordnung, kein Ranking und keine Aufforderung, alle dargestellten Komponenten einzusetzen.
    </figcaption>
</figure>

Ein hilfreicher Portabilitätstest lautet:

> **Kann das Team fachliche Granularität, Historie, Qualität, Sicherheit und Veröffentlichungsverhalten erklären, ohne den Plattformnamen zu verwenden?**

Lautet die Antwort nein, beschreibt das Design wahrscheinlich noch Produkte statt Architektur.

## Die einfachste gültige Umsetzung

Das einfachste sinnvolle Warehouse kann bereits vorhanden sein.

Ein Unternehmen mit SQL Server, einem Scheduler, Qlik, Power BI Report Server und Excel kann eine governte Architektur aufbauen, ohne zuerst eine Cloud-Datenplattform zu beschaffen.

Eine minimale Umsetzung kann folgende Objekte enthalten:

```text
staging.sales_order_line
staging.customer
core.fact_sales_order_line
core.dim_customer
core.dim_product
core.dim_date
mart.sales_customer_daily
quality.sales_rule_result
control.data_product_publication
```

Der technische Ablauf kann so aussehen:

```flow linear vertical
Exporte aus operativen Quellen
Geplante Ingestion in Staging
SQL-Transformationen in Core-Fakten und -Dimensionen
Qualitätsergebnisse und Abstimmungskontrollen
Veröffentlichtes Sales Data Mart
Consumption durch Qlik / Power BI Report Server / Excel
```

Die erste Version benötigt weder einen separaten Lake noch Spark, dbt, ein Katalogprodukt oder ein neues Semantic-Layer-Tool. Sie benötigt jedoch:

- eine autoritative Sales-Definition;
- eine dokumentierte Granularität;
- stabile Business- und Surrogate Keys;
- explizite Historienregeln;
- persistente Datenqualitätsnachweise;
- kontrollierte Veröffentlichung;
- Zugriffsregeln;
- versionierten Transformationscode;
- Monitoring und einen verantwortlichen Owner.

Zusätzliche Technologie sollte nur eingeführt werden, wenn sie eine konkrete Einschränkung bei Integration, Skalierung, Zusammenarbeit, Latenz, Governance oder Betrieb löst.

## Ein Sales- und Customer-Beispiel über alle Plattformen

Der Plattformvergleich ist nur sinnvoll, wenn jede Umsetzung dieselbe fachliche Antwort erzeugt.

Angenommen, das governte Produkt heißt **Sales by Customer**.

### Produktvertrag

| Vertragselement | Definition |
| --- | --- |
| Granularität | Eine Zeile je gebuchter Auftragsposition und Business-Datum |
| Business-Datum | Das von Finance für das Reporting verwendete Buchungsdatum |
| Kundenhistorie | Die am Business-Datum gültige Kundenversion |
| Produkthistorie | Die am Business-Datum gültige Produktklassifikation |
| Nettoumsatz | Bruttobetrag der Position abzüglich freigegebener Rabatte; stornierte Positionen ausgeschlossen; Umrechnung mit dem für das Business-Datum gültigen freigegebenen Kurs |
| Berichtswährung | EUR |
| Pflichtschlüssel | Auftragsposition, Kunde, Produkt, Business-Datum und Quellsystem |
| Quality Gates | Gültiger Kunden-, Produkt- und Wechselkursschlüssel; Beträge stimmen mit der Quell-Kontrollsumme überein |
| Veröffentlichung | Täglich erst nach bestandenen Pflichtprüfungen und Abstimmungen |
| Consumer | Qlik-Analyse, Power-BI-Management-Reporting und kontrollierte Excel-Ausgaben |
| Owner | Sales Data Owner, unterstützt durch das Data-Platform-Team |

Ein plattformneutraler Core kann Folgendes bereitstellen:

```text
fact_sales_order_line
  business_date_key
  order_id
  order_line_id
  customer_key
  product_key
  quantity
  gross_amount
  discount_amount
  net_revenue_amount
  reporting_currency
  quality_status
  source_system
  publication_timestamp

dim_customer
  customer_key
  customer_id
  customer_name
  country_code
  segment_code
  valid_from
  valid_to
  is_current
```

Die wiederverwendbare Regel gehört in die governte Transformations- und Produktschicht:

```sql
select
    s.business_date,
    s.order_id,
    s.order_line_id,
    c.customer_key,
    p.product_key,
    s.quantity,
    (s.gross_amount - s.discount_amount) * fx.reporting_rate
        as net_revenue_amount,
    'EUR' as reporting_currency
from standardized.sales_order_line s
join core.dim_customer c
  on s.customer_id = c.customer_id
 and s.business_date >= c.valid_from
 and s.business_date < c.valid_to
join core.dim_product p
  on s.product_id = p.product_id
 and s.business_date >= p.valid_from
 and s.business_date < p.valid_to
join reference.exchange_rate fx
  on s.currency_code = fx.source_currency
 and fx.target_currency = 'EUR'
 and s.business_date = fx.rate_date
where s.standardized_status = 'POSTED';
```

Die genaue SQL-Syntax, Datumsgrenzen und Materialisierung unterscheiden sich je nach Engine. Die Bedeutung darf sich nicht unterscheiden.

Consumer-spezifisches Verhalten bleibt dort lokal, wo es begründet ist:

| Consumer | Gemeinsam aus dem Datenprodukt | Consumer-spezifische Verantwortung |
| --- | --- | --- |
| Qlik | Sales-Fakten, Kundenhistorie, freigegebener Nettoumsatz und Qualitätsstatus | Assoziatives Modell, Selektionen, Master Measures, Set Analysis und Qlik-spezifisches Zugriffsverhalten |
| Power BI | Dieselben Fakten, Dimensionen und freigegebenen Beträge | Beziehungen, DAX-Measures, Filterkontext, Berichtsnavigation und Verteilung |
| Excel | Kontrollierte denormalisierte Sales View oder freigegebenes Semantikmodell | Pivot-Layout, Kommentare, lokale Planungsspalten und operative Nachverfolgung |

Qlik darf Storno, Währungsumrechnung oder historische Kundenzuordnung nicht in jeder App neu berechnen. Power BI darf keine abweichende Nettoumsatzdefinition in DAX erzeugen. Excel darf nicht der einzige Ort werden, an dem fehlende Kundenzuordnungen korrigiert werden.

## Microsoft Fabric mit Qlik, Power BI und Excel

Microsoft Fabric ist eine gültige Umsetzung, wenn das Unternehmen von einer gemanagten Microsoft-Analytics-Plattform, OneLake, Fabric Data Factory, Lakehouse- oder Warehouse-Workloads und enger Power-BI-Integration profitiert.

Fabric ist nicht automatisch die beste Wahl, nur weil Microsoft 365, Azure oder Power BI bereits vorhanden sind.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img2-de.png"
        alt="Microsoft-Fabric-Referenzarchitektur mit Quellen, Fabric-Ingestion, Staging, Modellierung, governten Datenprodukten, Semantikmodellen und kontrollierter Nutzung durch Qlik, Power BI, Excel und Anwendungen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Fabric kann die vollständige logische Architektur umsetzen, während Qlik, Power BI und Excel unterschiedliche Consumer bleiben. Fabric-native Integration erfordert nicht, gemeinsame Geschäftslogik in jedem Consumer neu aufzubauen.
    </figcaption>
</figure>

### Eine minimale Fabric-Zuordnung

| Logische Verantwortung | Mögliche Fabric-Umsetzung |
| --- | --- |
| Ingestion | Data-Factory-Pipeline, Copy Activity, Dataflow Gen2, Mirroring oder ein vorhandenes Ingestion-Werkzeug |
| Raw Landing | Lakehouse-Dateien oder Delta-Tabellen in OneLake |
| Standardisierung | Durch Spark/SQL erzeugte Lakehouse-Tabellen, Dataflow-Ausgaben oder kontrollierte Transformationen |
| Core Warehouse | Fabric Warehouse für T-SQL-zentrierte Modellierung oder kuratierte Delta-Tabellen in einem Lakehouse |
| Datenprodukt | Warehouse-Sternschema, kuratierte Lakehouse-Tabellen oder governte Views |
| Semantikmodell | Power-BI-Semantikmodell mit geeignetem Storage Mode und Sicherheitsdesign |
| Qlik-Consumption | Governtes Warehouse oder SQL Analytics Endpoint über einen unterstützten SQL-/ODBC-Zugriffspfad oder einen anderen kontrollierten Bereitstellungsvertrag |
| Excel-Consumption | Freigegebenes Semantikmodell, governte SQL View, Power-Query-Verbindung oder kontrollierte Dateiausgabe |
| Governance und Betrieb | Workspace-Berechtigungen, SQL-Berechtigungen, OneLake-Sicherheit, Monitoring, Deployment und bei Bedarf Purview-Integration |

Fabric stellt mehrere gültige Wege bereit. Die Architektur sollte die kleinste Menge auswählen, die das Team zuverlässig betreiben kann.

### Lakehouse, Warehouse oder beides

Ein Lakehouse ist sinnvoll, wenn der Workload Dateien, Delta-Tabellen, Spark, Python, semistrukturierte Daten oder Data-Science-Integration benötigt.

Ein Fabric Warehouse ist sinnvoll, wenn der Workload stark relational ist und das Team T-SQL, Warehouse-Objekte, dimensionale Modellierung und SQL-orientierte Consumption bevorzugt.

Beides zu verwenden kann sinnvoll sein, wenn die Verantwortlichkeiten explizit getrennt sind:

```text
Lakehouse
  quellnahe Dateien und Delta-Tabellen
  große oder semistrukturierte Transformationen
  Spark- und Python-Workloads

Warehouse
  integrierte Fakten und Dimensionen
  governte relationale Datenprodukte
  stabile SQL-Verträge für BI-Consumer
```

Beides ohne Verantwortungsgrenze zu verwenden erzeugt duplizierte Speicherung, wiederholte Transformationen und unklare Ownership.

### Mirroring, Pipelines und Shortcuts sind Optionen

Mirroring kann für unterstützte Quellszenarien den Aufwand eigener Ingestion reduzieren. Data-Factory-Pipelines können mehrstufige Workflows orchestrieren und automatisieren. OneLake Shortcuts können vorhandene Daten ohne direkte Kopie zugänglich machen, wenn Quelle, Sicherheit und Performance-Muster dafür geeignet sind.

Diese Funktionen lösen unterschiedliche Probleme. Sie sollten nicht alle in jeden Flow eingebaut werden.

Eine hilfreiche Auswahlreihenfolge ist:

1. Kann der vorhandene Ingestion-Prozess die benötigten Daten zuverlässig liefern?
2. Liefert Mirroring die erforderliche Quellenunterstützung, Latenz und das passende Betriebsverhalten?
3. Würde eine Pipeline Abhängigkeiten, Zeitplanung und Monitoring klarer machen?
4. Kann ein Shortcut unnötige Kopien vermeiden, ohne Ownership oder Sicherheit zu schwächen?
5. Ist physische Materialisierung für Performance, Historie, Qualitätsisolierung oder Vertragsstabilität erforderlich?

### Power BI nutzt Fabric-native Stärken

Power BI kann Fabric-Semantikmodelle und Direct Lake verwenden, wenn Modell, Capacity, Sicherheit und Betriebsanforderungen dazu passen.

Das Semantikmodell bleibt eine Consumption-Schicht. Gemeinsame Sales-Regeln gehören weiterhin in governte Datenprodukte, sofern sie nicht tatsächlich Filterkontext- oder Darstellungsverhalten sind.

Geeignete Power-BI-Verantwortlichkeiten sind:

- Beziehungen und Hierarchien;
- DAX-Measures auf freigegebenen Fakten;
- Time-Intelligence-Verhalten;
- Berichtsnavigation;
- Zeilen- oder Objektsicherheit im Modell, wo sie geeignet ist;
- kontrollierte Verteilung und Endorsement.

### Qlik bleibt ein schlanker analytischer Consumer

Qlik kann governte Fabric-Daten über einen unterstützten Datenzugriffspfad konsumieren. Die Qlik-Anwendung kann ergänzen:

- ein assoziatives Consumer-Modell;
- kanonische Kalender;
- Master Dimensions und Master Measures;
- Set Analysis für Qlik-spezifische Vergleiche;
- Section Access oder einen anderen begründeten Durchsetzungsmechanismus;
- App-spezifische Performanceoptimierung.

Das Qlik-Ladeskript sollte nicht zu einer zweiten Fabric-Transformationsplattform werden. Gemeinsame Kundenintegration, Währungsumrechnung, Historie und Qualitätsregeln bleiben vorgelagert.

### Excel erhält einen kontrollierten Vertrag

Excel kann abhängig von Anwenderprozess und Lizenzumgebung ein freigegebenes Semantikmodell, eine governte SQL View, eine Power-Query-Verbindung oder eine kontrollierte Ausgabedatei verwenden.

Die Arbeitsmappe darf Folgendes verantworten:

- Pivot-Layout;
- Kommentare und Review-Status;
- kontrollierte Planungsannahmen;
- lokale Formatierung;
- operative Nachverfolgungsspalten.

Sie sollte nicht das autoritative Kunden-Matching, die Stornoregel oder die Nettoumsatzberechnung verantworten.

## Snowflake und Databricks als gleichwertige Alternativen

Snowflake und Databricks können beide dieselbe governte Architektur umsetzen. Sie besitzen unterschiedliche Stärken, Schnittstellen und Betriebsmodelle, aber keine der Plattformen sollte als verpflichtendes Premium-Zielbild behandelt werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img3-de.png"
        alt="Parallele Snowflake- und Databricks-Umsetzungen derselben logischen Architektur von Quellen über Raw, standardisierte und fachlich nutzbare Schichten bis zu Semantik und BI-Consumern"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Snowflake und Databricks können dasselbe governte Sales- und Customer-Produkt über unterschiedliche physische Muster erzeugen. Die Auswahl sollte Workload und Betriebskontext folgen statt dem Status einer Plattform.
    </figcaption>
</figure>

### Snowflake-Umsetzung

Eine SQL-zentrierte Snowflake-Umsetzung kann folgende Schemas verwenden:

```text
RAW
STANDARDIZED
CORE
MART
QUALITY
CONTROL
```

Das Sales-Beispiel kann so zugeordnet werden:

| Verantwortung | Snowflake-Umsetzungsoption |
| --- | --- |
| Ingestion | Bulk Loading, Snowpipe, Partnerintegration oder ein vorhandenes ETL-/ELT-Werkzeug |
| Raw | Quellnahe Tabellen und Staging-Dateien |
| Standardisierung | SQL Views, Tabellen oder Dynamic Tables |
| Core | Integrierte Fakten und Dimensionen in Snowflake-Tabellen |
| Datenprodukt | Governte Views, Tabellen, Marts oder sichere Bereitstellungsobjekte |
| Inkrementelle Steuerung | Dynamic Tables oder explizite Streams und Tasks, wenn sie passen |
| Compute-Isolierung | Getrennte Virtual Warehouses für Ingestion, Transformation und BI, wo begründet |
| Consumption | Qlik, Power BI, Excel, APIs oder andere Tools über governte Schnittstellen |

Dynamic Tables können deklarative SQL-Pipelines vereinfachen, wenn Target Freshness und Dependency Management zur Anforderung passen. Streams und Tasks können explizitere Änderungserfassung und prozedurales Scheduling bereitstellen. Gewöhnliche SQL Views und geplante Tabellenaufbauten bleiben gültig.

Das Team sollte nicht alle drei Muster für dieselbe Regel verwenden.

Ein praktisches Snowflake-Sales-Design kann so aussehen:

```text
RAW.SALES_ORDER_LINE
RAW.CUSTOMER
STANDARDIZED.SALES_ORDER_LINE
STANDARDIZED.CUSTOMER
CORE.FACT_SALES_ORDER_LINE
CORE.DIM_CUSTOMER
MART.SALES_CUSTOMER_DAILY
QUALITY.SALES_RULE_RESULT
CONTROL.SALES_PUBLICATION
```

Qlik und Power BI können dedizierten BI-Compute oder Workload-Steuerung verwenden, ohne die Produktdefinition zu verändern. Excel kann eine schmalere View oder einen kontrollierten Extrakt erhalten.

Snowflake ist besonders attraktiv, wenn ein Cloud Data Warehouse, SQL-zentrierte Analytics, unabhängige Compute-Skalierung, teamübergreifendes Data Sharing oder Multi-Cloud-Verfügbarkeit ein reales Problem löst. Snowflake ist unnötig, wenn eine vorhandene relationale Plattform den Workload bereits wirtschaftlich und betrieblich erfüllt.

### Databricks-Umsetzung

Eine Databricks-Umsetzung ordnet die Architektur häufig governten Delta-Tabellen und einer medallion-artigen Entwicklung zu:

```text
Bronze      Quellnahe Delta-Tabellen
Silver      Bereinigte, standardisierte und integrierte Delta-Tabellen
Gold        Fachlich nutzbare Fakten, Dimensionen, Aggregate und Datenprodukte
```

Diese Benennung bleibt optional. Die Verantwortlichkeiten sind verpflichtend.

Das Sales-Beispiel kann so zugeordnet werden:

| Verantwortung | Databricks-Umsetzungsoption |
| --- | --- |
| Ingestion | Lakeflow Connect, Auto Loader, Streaming, Batch Jobs oder ein vorhandenes Ingestion-Werkzeug |
| Raw | Bronze-Delta-Tabellen im Cloud-Objektspeicher |
| Standardisierung | Silver-Delta-Tabellen mit SQL, Python oder Spark |
| Core und Produkt | Gold-Fakten, -Dimensionen, -Aggregate und governte Views |
| Orchestrierung | Lakeflow Jobs und Pipelines oder ein anderer kontrollierter Orchestrator |
| Governance | Unity Catalog für governte Daten- und KI-Assets, Berechtigungen und Discovery |
| SQL-Consumption | Databricks SQL Warehouse und governte SQL-Endpunkte |
| Erweiterte Workloads | Spark, Python, Streaming, Data Science und ML, wo erforderlich |

Eine mögliche governte Objektstruktur lautet:

```text
main.raw.sales_order_line
main.raw.customer
main.standardized.sales_order_line
main.standardized.customer
main.core.fact_sales_order_line
main.core.dim_customer
main.product.sales_customer_daily
main.quality.sales_rule_result
main.control.sales_publication
```

Databricks ist eine starke Option, wenn das Unternehmen eine gemeinsame Umgebung für umfangreiches Engineering, SQL Analytics, Streaming, Python, Spark, Data Science oder Machine Learning benötigt.

Databricks sollte nicht als Open-Source-On-Premises-Produkt beschrieben werden. Delta Lake ist Open Source, die Databricks-Plattform ist jedoch eine gemanagte Cloud-Plattform auf AWS, Azure und Google Cloud. Eine On-Premises-Installation von Apache Spark oder Delta Lake besitzt ein anderes Betriebsmodell und sollte nicht Databricks genannt werden.

Qlik kann über den unterstützten Databricks-Connector und SQL Compute auf governte Databricks-Tabellen zugreifen. Power BI kann einen unterstützten Databricks-Verbindungsweg verwenden. Excel sollte eine freigegebene SQL-nahe View, ein Semantikmodell oder einen kontrollierten Extrakt konsumieren statt beliebige Notebooks oder Bronze-Tabellen direkt zu verwenden.

### dbt bleibt auf beiden Plattformen optional

dbt kann Snowflake, Databricks, Fabric oder einer anderen SQL-fähigen Plattform Mehrwert liefern, wenn das Team Folgendes benötigt:

- einen modularen SQL-Modellgraphen;
- Git-basierte Reviews;
- Tests und Dokumentation;
- wiederverwendbare Macros und Konventionen;
- CI/CD für Analytics Engineering;
- einen einheitlichen Entwicklungsworkflow über viele Modelle.

dbt sollte nicht zu einer Pflichtschicht werden, nur weil mehrere Plattformen vorhanden sind.

Eine hilfreiche Regel lautet:

```text
Die native Plattformtransformation ist autoritativ
oder
das dbt-Projekt ist autoritativ
```

Ein Mischmodell ist nur dann gültig, wenn die Grenze explizit ist, zum Beispiel:

```text
Native Ingestion und Streaming
Native oder Python-basierte Standardisierung
Autoritatives dbt-Projekt für SQL-zentrierten Core und Marts
Plattformnative Planung und Überwachung
```

## Welche Plattform passt zu welchem Kontext?

Die Plattformentscheidung sollte mit der vorhandenen Landschaft und dem dominanten Workload beginnen und nicht mit einem generischen Funktionsvergleich.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img4-de.png"
        alt="Entscheidungsmatrix zum Vergleich von SQL Server, Microsoft Fabric, Snowflake und Databricks anhand vorhandener Landschaft, primärem Workload, Teamkompetenzen, Betriebsmodell, Governance, Cloud-Strategie und On-Premises-Anforderungen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die passende Plattform hängt vom vorhandenen Ökosystem, Workload, Team und betrieblichen Einschränkungen ab. Kein fester Schwellenwert für Datenvolumen und keine Funktionsanzahl ersetzt eine kontextbezogene Entscheidung.
    </figcaption>
</figure>

### Vorhandenes Ökosystem

Nutze die vorhandene Plattform, wenn sie die fachliche Anforderung mit akzeptablem Risiko und Betriebsaufwand erfüllen kann.

Eine ausgereifte SQL-Server-Landschaft mit zuverlässigem Betrieb, starken SQL-Kompetenzen und planbaren Reporting-Workloads kann wertvoller sein als eine überhastete Migration auf eine Cloud-Plattform.

Fabric kann Integrationsaufwand reduzieren, wenn Power BI, Azure Identity, OneLake-orientierte Analytics und Microsoft-Plattformbetrieb strategisch sind.

Snowflake kann zu einem Cloud-Data-Warehouse-Betriebsmodell mit starken SQL-Analytics-, Workload-Isolierungs- und Data-Sharing-Anforderungen passen.

Databricks kann zu einer Engineering- und KI-intensiven Umgebung passen, in der SQL, Spark, Python, Streaming und Machine Learning gemeinsam betrieben werden müssen.

### SQL versus Spark und Python

Ein überwiegend relationaler Workload benötigt nicht allein wegen eines großen Datenvolumens Spark.

Wähle SQL-orientierte Verarbeitung, wenn:

- Transformationen mengenorientiert und relational sind;
- dimensionale Modellierung dominiert;
- das Team am stärksten in SQL ist;
- BI-Concurrency und governtes Reporting im Mittelpunkt stehen;
- das aktuelle Warehouse Latenz- und Skalierungsanforderungen erfüllt.

Wähle Spark-/Python-orientierte Verarbeitung, wenn:

- Transformationen verteilten Code oder komplexe Bibliotheken benötigen;
- semistrukturierte oder unstrukturierte Daten zentral sind;
- Streaming und Event-Verarbeitung einen wesentlichen Anteil besitzen;
- Data Science und Feature Engineering echte Kern-Workloads sind;
- wiederverwendbare Software-Engineering-Muster über SQL hinaus erforderlich sind.

Hybride SQL- und Python-Plattformen können beides unterstützen. Die Architektur muss trotzdem festlegen, welche Engine jede Regel verantwortet.

### BI-Fokus versus ML und Streaming

Eine Management-Reporting-Plattform und eine ML-Plattform können governte Daten teilen, ohne jede Runtime zu teilen.

Zwinge den BI-Workload nicht in eine Engineering-Engine, nur weil ML existiert. Zwinge ML-Aufbereitung nicht in BI-orientiertes SQL, wenn Python oder Spark tatsächlich erforderlich ist.

Die gemeinsame Grenze ist das governte Datenprodukt mit seinen Verträgen.

### Betriebsmodell

Eine Plattform ist nicht nur eine Entwicklungsoberfläche. Bewerte:

```text
Identity- und Zugriffsadministration
Netzwerk und private Konnektivität
Umgebungstrennung
Source Control und Deployment
Zeitplanung und Dependency Management
Monitoring und Alarmierung
Kosten- und Capacity-Steuerung
Backup, Recovery und Continuity
Incident Ownership
Hersteller- und internen Support
Verfügbarkeit von Kompetenzen
```

Eine funktionsreiche Plattform ohne nachhaltiges Betriebsmodell ist eine schwache Architektur.

### Kostenmodell

Vergleiche die vollständigen Betriebskosten statt nur den Listenpreis.

Berücksichtige:

- Lizenzen oder Subscriptions;
- Capacity oder Verbrauch;
- Speicherung und Datentransfer;
- Leerlauf- und Spitzen-Compute;
- Entwicklungs- und Migrationsaufwand;
- Plattformadministration;
- Monitoring- und Sicherheitswerkzeuge;
- Training und Recruiting;
- Support und Incident Recovery;
- Parallelbetrieb während der Migration.

On-Premises ist nicht automatisch günstiger, weil die Hardware bereits vorhanden ist. Cloud ist nicht automatisch günstiger, weil sie verbrauchsabhängig ist. Workload-Profil und Betriebsdisziplin bestimmen das Ergebnis.

### Datenresidenz und Konnektivität

Wenn Richtlinien, Regulierung, Verträge oder Konnektivität lokale Verarbeitung erfordern, kann eine On-Premises- oder sorgfältig entworfene Hybridarchitektur das richtige Ziel sein.

Ist Cloud-Nutzung erlaubt, müssen Region, Netzwerk, Identity, Verschlüsselung, Datentransfer und Continuity weiterhin explizit geprüft werden.

### Teamkompetenz

Wähle eine Plattform nicht nur deshalb, weil externe Spezialisten sie aufbauen können.

Das interne Team muss in der Lage sein:

- Änderungen zu reviewen;
- Abhängigkeiten zu verstehen;
- Produktion zu betreiben;
- Fehler zu diagnostizieren;
- Kosten zu kontrollieren;
- Sicherheit durchzusetzen;
- das Modell nach Ende des Einführungsprojekts weiterzuentwickeln.

Ein kleineres Design mit vorhandener Kompetenz ist häufig sicherer als eine theoretisch überlegene Plattform, die betrieblich extern bleibt.

## On-Premises ist weiterhin eine valide Architektur

On-Premises bedeutet nicht ungovernt, monolithisch oder veraltet.

Dieselben modernen Prinzipien gelten:

```text
Quellnahes Staging
Wiederherstellbare Ingestion
Versionierte Transformationen
Konforme Fakten und Dimensionen
Persistente Qualitätsnachweise
Governte Datenprodukte
Schlanke Consumer-Modelle
Automatisiertes Monitoring und Deployment
Explizite Ownership und Lifecycle
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start9-img5-de.png"
        alt="Moderne On-Premises-Data-Warehouse-Architektur von Quellen über Staging-Datenbank, Core Warehouse und Data Marts bis zu Qlik, Power BI Report Server und Excel"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        On-Premises bleibt eine gültige Umsetzung, wenn sie zu regulatorischen, konnektiven, investiven, fachlichen oder betrieblichen Anforderungen passt. Moderne Architektur wird durch Verantwortlichkeiten und Kontrollen definiert und nicht durch den Hosting-Ort.
    </figcaption>
</figure>

### Eine praktische On-Premises-Zuordnung

| Verantwortung | Mögliche Umsetzung |
| --- | --- |
| Ingestion | SSIS, Talend, Informatica, Qlik Replicate, Datenbankjobs, Skripte oder ein anderes etabliertes Integrationswerkzeug |
| Staging | SQL-Server-Datenbank, Datei-Landing-Zone oder governter lokaler Objektspeicher |
| Core Warehouse | SQL Server, Oracle, PostgreSQL oder eine andere unterstützte relationale Engine |
| Data Marts | Sternschemata, materialisierte Tabellen und governte SQL Views |
| Qlik | Schlanke assoziative Anwendungen, die governte Marts oder kontrollierte QVD-Ausgaben laden |
| Power BI | Power BI Report Server, wenn ein lokales Berichtsportal erforderlich ist, vorbehaltlich des aktuellen Funktionsumfangs und der Lizenzierung |
| Excel | Zertifizierte SQL Views, kontrollierte Dateien, Power Query oder freigegebene Analysis-Services-artige Schnittstellen |
| Governance | Datenbankberechtigungen, Katalog- und Metadatenprozesse, Qualitätstabellen, Audit Logs, Monitoring und Change Control |

Das Design kann weiterhin Git, automatisierte Deployments, Datentests, Lineage-Extraktion, API-Verträge und Observability umfassen.

### Gültige Gründe für On-Premises

- vertragliche oder regulatorische Datenresidenz;
- sensible Workloads mit strenger interner Kontrolle;
- unzuverlässige oder eingeschränkte externe Konnektivität;
- Low-Latency-Integration mit lokalen operativen Systemen;
- große bestehende Investition und ein erfahrenes Betriebsteam;
- planbare Workloads mit wirksamer vorhandener Capacity;
- eine Übergangsstrategie, die das Risiko einer sofortigen Migration nicht rechtfertigt.

### On-Premises-Verantwortlichkeiten, die nicht ignoriert werden dürfen

Das Unternehmen verantwortet größere Teile der Plattform selbst:

- Hardware und Virtualisierung;
- Betriebssysteme und Datenbanken;
- Patching und Upgrades;
- Capacity Planning;
- Backup und Disaster Recovery;
- Netzwerk- und Identity-Integration;
- Hochverfügbarkeit;
- Security Monitoring;
- Support und Lifecycle Management.

Dieser Betriebsaufwand muss in die Plattformentscheidung einbezogen werden.

## Hybride Kombinationen können bewusst gewählt sein

Hybridarchitektur sollte nicht bedeuten, dass jeder Datensatz auf jede Plattform kopiert wird.

Ein gültiges Hybridmuster kann so aussehen:

```text
On-Premises-Betriebssysteme
Kontrollierte lokale Extraktion oder Replikation
Cloud-Raw- und Transformationsplattform
Governte Cloud-Datenprodukte
Qlik-, Power-BI- und Excel-Consumption entsprechend Netzwerk- und Sicherheitsanforderungen
```

Ein weiteres gültiges Muster kann so aussehen:

```text
On-Premises-Core-Warehouse bleibt autoritativ
Ausgewähltes Cloud-Datenprodukt wird für Advanced Analytics oder ML veröffentlicht
Ergebnisse kehren über einen kontrollierten Vertrag zurück
Core-BI bleibt auf der vorhandenen Plattform
```

Ein drittes Muster kann Fabric für Power BI und gemeinsame Microsoft-Consumption nutzen, während eine vorhandene Snowflake- oder Databricks-Plattform für Transformation autoritativ bleibt.

Die Architektur muss Folgendes definieren:

| Frage | Erforderliche Entscheidung |
| --- | --- |
| Wo liegt der autoritative Datensatz? | Ein benanntes System oder eine benannte Produktschicht |
| Welche Daten überschreiten die Grenze? | Explizite Datensätze, Felder und Klassifikationen |
| Ist der Transfer Kopie, Replikation oder virtueller Zugriff? | Auswahl nach Latenz, Sicherheit und Wiederherstellungsbedarf |
| Wo wird Historie aufgelöst? | Eine führende Implementierung |
| Wo werden Quality Gates ausgewertet? | Ein autoritatives Ergebnis mit sichtbarem Nachweis |
| Welche Plattform veröffentlicht den Consumer-Vertrag? | Benannter Owner und benannte Schnittstelle |
| Was geschieht bei Netzwerkausfall? | Definiertes Retry-, Backlog- und Veröffentlichungsverhalten |
| Wie werden Kosten kontrolliert? | Datentransfer, Compute, Speicherung und Duplizierung überwacht |
| Wie erfolgt die Stilllegung? | Exit-Kriterien und Entfernung ersetzter Pfade |

Eine Hybridplattform ohne diese Entscheidungen wird zu einem teuren Synchronisationsproblem.

## Plattformspezifische Stärken sollen plattformspezifisch bleiben

Eine technologieoffene Architektur bedeutet nicht, so zu tun, als seien alle Produkte identisch.

Sie bedeutet, Plattformstärken zu verwenden, ohne gemeinsame fachliche Bedeutung in inkompatible lokale Implementierungen zu verschieben.

Beispiele:

| Plattformspezifische Stärke | Geeignete Nutzung | Zu schützende Grenze |
| --- | --- | --- |
| Fabric Direct Lake und Power-BI-Integration | Effiziente Microsoft-native Semantik-Consumption, wo geeignet | Nettoumsatz und Kundenhistorie bleiben vorgelagert governt |
| Snowflake Virtual Warehouses | Workload-Isolierung und unabhängige Compute-Steuerung | Getrennter Compute erzeugt keine getrennten Geschäftsdefinitionen |
| Snowflake Dynamic Tables | Deklarative SQL-Refresh-Pipelines | Dieselbe Regel nicht zusätzlich in Tasks und einem weiteren ETL-Job duplizieren |
| Databricks Spark und Python | Komplexes Engineering, Streaming und ML-Aufbereitung | Einfache BI-Regeln nicht ohne Bedarf in Notebooks zwingen |
| Unity Catalog | Governte Discovery und Zugriff für Daten- und KI-Assets | Katalogregistrierung ersetzt keine Produkt-Ownership |
| SQL-Server-Engine | Stabile dimensionale Modellierung und SQL-Workloads | Datenbankprozeduren nicht als undokumentierten Anwendungscode behandeln |
| Qlik Associative Engine | Explorative Analyse und Selektionsverhalten | Qlik-spezifische Logik darf gemeinsame Fakten nicht neu definieren |
| Power-BI-Semantikmodell | DAX, Filterkontext und Microsoft-Verteilung | Semantische Measures müssen auf freigegebenen Fakten aufbauen |
| Excel | Flexible operative und analytische Arbeit | Lokale Formeln dürfen nicht zur einzigen autoritativen Regel werden |

## Typische Anti-Patterns

### Die Produktnamen-Architektur

```text
Quelle → Fabric → Power BI
```

Dies definiert weder Raw Recovery, Historie, Qualität, Produktverträge, Sicherheit noch Ownership.

### Bronze, Silver und Gold ohne Verantwortlichkeiten

Drei Ordner oder Schemas erzeugen kein Warehouse. Jede Schicht benötigt einen expliziten Zweck, Owner, Inputs, Outputs und Kontrollen.

### Dieselbe Regel je Plattform neu aufbauen

```text
SQL-Server-Nettoumsatz
Fabric-Nettoumsatz
Snowflake-Nettoumsatz
Databricks-Nettoumsatz
Qlik-Nettoumsatz
Power-BI-Nettoumsatz
Excel-Nettoumsatz
```

Eine Transition kann vorübergehend parallele Implementierungen enthalten. Das Zielbild benötigt eine autoritative Regel je Produktvertrag.

### Dauerhafter Parallelbetrieb nach einer Migration

Parallele Validierung ist vor dem Cutover sinnvoll. Dauerhaft gleichberechtigte Ownership erzeugt Kosten, Abstimmungsaufwand und Mehrdeutigkeit.

### Plattformauswahl anhand maximaler theoretischer Skalierung

Eine Plattform mit extremer Skalierungsfähigkeit ist nicht automatisch die beste Wahl für einen moderaten, stabilen Workload. Komplexität und Betriebsaufwand bleiben real.

### Cloud allein als Modernisierung behandeln

Undokumentierte Prozeduren, duplizierte Regeln und unkontrollierte Berichte in einen Cloud-Service zu verschieben erhält das Architekturproblem.

### On-Premises automatisch als veraltet behandeln

Ein stabiles, governtes Warehouse ohne fachlichen oder betrieblichen Nutzen zu ersetzen ist keine Modernisierung.

### Qlik zur Integrationsplattform machen

Eine gemeinsame Qlik-Extraktions- oder QVD-Schicht kann ein gültiger Brownfield-Schritt sein. Sie sollte nicht der einzige Ort für wiederverwendbare Geschäftsregeln werden, wenn Power BI, Excel, APIs oder andere Consumer dieselbe Wahrheit benötigen.

### Databricks als On-Premises-Distribution behandeln

Open-Source-Komponenten wie Delta Lake oder Apache Spark können außerhalb von Databricks betrieben werden. Dadurch wird die resultierende Umgebung nicht zu einer Databricks-Bereitstellung.

### dbt ohne Verantwortungsgrenze ergänzen

Wenn dbt und native Transformationen dasselbe Modell bauen, hat das Unternehmen eine weitere Implementierung statt Governance ergänzt.

### Direkter Consumer-Zugriff auf Raw-Daten

Qlik-, Power-BI- oder Excel-Anwender sollten nicht Quellcodes, unvollständige Historien und fehlerhafte Datensätze verstehen müssen, um vertrauenswürdiges Reporting zu erstellen.

## Entscheidungshilfe

Verwende folgende Reihenfolge, bevor eine Plattform gewählt oder geändert wird.

| Frage | Wenn ja | Wenn nein |
| --- | --- | --- |
| Kann die vorhandene Plattform Granularität, Qualität, Latenz und Skalierung erfüllen? | Zuerst aktuelles Design verbessern | Alternativen bewerten |
| Ist der Haupt-Workload relational und SQL-zentriert? | SQL-orientierte Umsetzung bevorzugen | Python, Spark, Streaming oder Spezialverarbeitung bewerten |
| Benötigt das Unternehmen integrierte Microsoft-Analytics- und Power-BI-Funktionen? | Fabric kann Integrationsaufwand reduzieren | Fabric nicht nur wegen Status ergänzen |
| Sind unabhängiger Cloud-Warehouse-Compute und Data Sharing ein wesentlicher Bedarf? | Snowflake kann passen | Vorhandenes SQL oder eine andere Plattform kann ausreichen |
| Sind Spark, Python, Streaming, Data Science und ML echte Kern-Workloads? | Databricks kann passen | Engineering-Komplexität ohne Workload vermeiden |
| Sind On-Premises-Residenz oder Konnektivitätsgrenzen bindend? | On-Premises- oder Hybridpfad erhalten bzw. gestalten | Cloud-Optionen bleiben offen |
| Löst dbt Probleme bei Modellzusammenarbeit, Tests und Deployment? | Mit expliziter Ownership ergänzen | Native oder vorhandene Transformationsmethoden nutzen |
| Kann das Team die Zielplattform nach der Einführung betreiben? | Bewertung fortsetzen | Scope reduzieren, Kompetenzen aufbauen oder einfachere Plattform wählen |
| Kann die Migration anhand fachlicher Ergebnisse validiert werden? | Kontrollierten Cutover planen | Noch nicht migrieren |
| Existiert ein Stilllegungspfad für ersetzte Assets? | In die Roadmap aufnehmen | Risiko dauerhafter Duplizierung bleibt |

Ein kompaktes Scoring-Modell kann jeden Kandidaten von 1 bis 5 in folgenden Dimensionen bewerten:

```text
Business Fit
Fit zur vorhandenen Landschaft
SQL-Fit
Python-/Spark-Fit
BI-Integration
Streaming- und ML-Fit
Governance-Fit
Security- und Residency-Fit
Teamkompetenz
Betriebsaufwand
Kostenplanbarkeit
Migrationsaufwand
Exit und Portabilität
```

Der Score wählt die Plattform nicht automatisch. Er macht Annahmen sichtbar und zwingt dazu, Zielkonflikte zu diskutieren.

## Wichtigste Empfehlungen

1. Definiere die logischen Verantwortlichkeiten, bevor eine Plattform benannt wird.
2. Erhalte eine fachliche Granularität, Historienregel, Qualitätsrichtlinie und Ownership über alle Umsetzungen.
3. Beginne mit den Fähigkeiten, die bereits vorhanden sind und zuverlässig betrieben werden.
4. Behandle SQL Server und andere On-Premises-Warehouses als valide moderne Optionen, wenn sie zum Kontext passen.
5. Nutze Fabric, wenn OneLake, Data Factory, Warehouse/Lakehouse und Power-BI-Integration konkrete Anforderungen lösen.
6. Entscheide zwischen Fabric Lakehouse, Warehouse oder beidem anhand von Workload-Grenzen statt Plattformmode.
7. Halte Power-BI-Semantiklogik auf Modell- und Filterkontextverhalten fokussiert.
8. Halte Qlik-Skripte schlank und behalte nur begründete Qlik-spezifische Logik.
9. Gib Excel einen kontrollierten Vertrag, statt Anwender in manuelle Schattenprozesse zu zwingen.
10. Nutze Snowflake, wenn Cloud Warehouse, Workload-Isolierung, SQL-Modell und Data Sharing Mehrwert schaffen.
11. Nutze Databricks, wenn SQL, Spark, Python, Streaming, Engineering, Data Science oder ML eine gemeinsam governte Plattform benötigen.
12. Beschreibe Databricks nicht als Open-Source-On-Premises-Produkt.
13. Behandle dbt als optionale Projekt- und Governance-Schicht und nicht als Voraussetzung.
14. Nutze native Plattformfunktionen zuerst, wenn sie die Anforderung erfüllen und das Team sie betreiben kann.
15. Vermeide, dieselbe Geschäftsregel gleichzeitig in nativem SQL, Notebooks, dbt und BI-Tools umzusetzen.
16. Definiere in jeder Hybridarchitektur die autoritative Grenze.
17. Persistiere Qualitätsnachweise und Veröffentlichungsstatus unabhängig von Dashboards.
18. Validiere Migrationen mit abgestimmten fachlichen Ergebnissen und nicht nur mit erfolgreichen Jobs und Zeilenzahlen.
19. Beziehe Identity, Netzwerk, Monitoring, Deployment, Support und Incident Handling in die Plattformentscheidung ein.
20. Vergleiche vollständige Betriebskosten einschließlich Kompetenzen und Migration statt nur Lizenzpreise.
21. Plane die Stilllegung vor dem Cutover, damit parallele Implementierungen nicht dauerhaft bestehen bleiben.
22. Bewerte die Plattform neu, wenn sich Workload oder Betriebsmodell wesentlich ändern — nicht bei jeder neuen Produktfunktion.

## Übergang zum nächsten Part

Eine Plattform auszuwählen und die logische Architektur zuzuordnen macht die Plattform nicht automatisch zuverlässig.

Der nächste Part, [Plattform betreiben und governieren](/playbooks/operating-and-governing-the-platform), behandelt Ownership, Deployment, Observability, Datenqualität, Sicherheit, Kostenkontrolle, Incident Handling, Lifecycle und die betrieblichen Kontrollen, die erforderlich sind, um jede dieser Umsetzungen in Produktion vertrauenswürdig zu halten.
