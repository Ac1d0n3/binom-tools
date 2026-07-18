---
title: Die einfachste tragfähige Architektur auswählen
description: Wie sich aus Datenvolumen, Aktualität, Transformationskomplexität, Teamgröße, Governance und Consumer-Bedarf die kleinste tragfähige Warehouse-Architektur ableiten lässt — mit vorhandenen Fähigkeiten, bevor weitere Tools ergänzt werden.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - architecture-decisions
  - data-products
  - qlik-sense
  - power-bi
  - excel
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - sql
  - on-premises
  - hybrid-cloud
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 3
seriesTitle: Ein modernes Data Warehouse aufbauen
hero: images/playbooks/bp-start3-hero.png
---

## Ein moderner Stack ist nicht automatisch die bessere Architektur

Moderne Datenplattformen bieten viele nützliche Funktionen: Pipelines, Dataflows, Warehouses, Lakehouses, Notebooks, Semantikmodelle, Streaming Engines, Kataloge, Test-Frameworks und Deployment-Automatisierung.

Dass diese Funktionen verfügbar sind, bedeutet nicht, dass jedes Warehouse alle davon benötigt.

Eine Architektur ist tragfähig, wenn sie die erforderlichen Business-Fragen zuverlässig beantwortet, Qualitäts- und Serviceerwartungen erfüllt, für das Team verständlich bleibt und ohne übermäßiges Risiko weiterentwickelt werden kann. Sie wird nicht allein dadurch besser, dass sie mehr Produkte enthält oder einem Referenzdiagramm eines Herstellers ähnelt.

Der richtige Ausgangspunkt lautet deshalb nicht:

```text
Welche modernen Tools können wir miteinander kombinieren?
```

Sondern:

```text
Welche ist die kleinste Architektur, die die Anforderung zuverlässig erfüllen kann?
```

Damit wird die Logik aus [Bevor die erste Tabelle entsteht](/playbooks/before-building-the-first-table) und [Mehr als Bronze, Silver und Gold](/playbooks/beyond-bronze-silver-gold) fortgeführt. Der erste Part definierte die Entscheidungen, die vor der Umsetzung getroffen werden müssen. Der zweite Part trennte die logischen Verantwortlichkeiten zwischen Quelle, Raw, Standardisierung, Integration, Datenprodukten und Nutzung. Dieser Part bestimmt, wie viel physische Technologie zur Umsetzung dieser Verantwortlichkeiten tatsächlich erforderlich ist.

> **Die einfachste tragfähige Architektur ist nicht die Architektur mit den wenigsten Kästchen. Sie ist die Architektur mit der geringsten unnötigen Komplexität, die die Anforderung weiterhin erfüllt.**

## Architekturprinzip: Anforderungen bestimmen die Komplexität

Architekturkomplexität muss durch messbare Anforderungen begründet sein.

Die wichtigsten Treiber sind:

| Treiber | Zu klärende Fragen |
| --- | --- |
| Datenvolumen | Wie viele Daten werden pro Lauf, pro Tag und über den Aufbewahrungszeitraum verarbeitet? |
| Aktualität | Reicht täglicher Batch oder werden stündliche, nahezu echtzeitfähige oder Streaming-Aktualisierungen benötigt? |
| Transformationstyp | Ist der Workload überwiegend relationales SQL oder werden verteilte Verarbeitung, komplexe Dateien, Events, ML oder graphähnliche Logik benötigt? |
| Teamgröße | Pflegt eine Person wenige Modelle oder ändern viele Beteiligte gemeinsame Transformationen? |
| Governance | Wie viel Nachvollziehbarkeit, Freigabe, Funktionstrennung, Testing und Auditierbarkeit werden benötigt? |
| Consumer | Gibt es eine BI-Anwendung, mehrere BI-Tools, APIs, Data Science, operative Nutzung oder externes Sharing? |
| Verfügbarkeit | Welches Ausfallfenster ist akzeptabel und wie schnell muss der Service wiederhergestellt werden? |
| Bestehende Landschaft | Welche Datenbanken, Plattformen, Fähigkeiten, Verträge und Betriebsprozesse funktionieren bereits? |
| Änderungsrate | Wie häufig ändern sich Quellen, Modelle, KPIs und Nutzungsverträge? |
| Kostenmodell | Welche zusätzlichen Lizenzen, Compute-Ressourcen, Betriebsaufwände und Spezialkenntnisse würde eine weitere Komponente verursachen? |

Diese Faktoren führen nicht automatisch zu einem bestimmten Produkt. Sie bestimmen, welche Fähigkeiten erforderlich sind.

Ein kleines Team mit zehn SQL-Modellen, einem täglichen Ladevorgang und zwei BI-Anwendungen benötigt möglicherweise eine disziplinierte SQL-Datenbank und keine Plattform mit mehreren Engines.

Ein größeres Team mit Hunderten voneinander abhängigen SQL-Modellen kann von modularer Entwicklung, Versionierung, automatisierten Tests und Deployment-Workflows profitieren.

Ein Workload mit hohen Event-Volumen, verteilter Transformation oder Machine Learning kann eine Spark-orientierte Plattform rechtfertigen.

Eine Microsoft-zentrierte Organisation kann mit einer integrierten Fabric-Umsetzung einfacher arbeiten als mit mehreren voneinander unabhängigen Services.

Ein stabiles On-Premises-Warehouse benötigt möglicherweise eine Modernisierung von Testing, Deployment und Schnittstellen statt eines Plattformwechsels.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start3-img1-de.png"
        alt="Entscheidungsfluss von Business-Anforderungen über Datenvolumen, Aktualisierungsfrequenz, Transformationskomplexität, Teamgröße, Governance und Consumer-Vielfalt zu mehreren gültigen Zielarchitekturen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Volumen, Aktualität, Transformationstyp, Zusammenarbeit, Governance und Consumer-Vielfalt bestimmen die benötigten Fähigkeiten. Mehrere Architekturen können dieselben logischen Warehouse-Verantwortlichkeiten mit unterschiedlich hoher physischer Komplexität erfüllen.
    </figcaption>
</figure>

## Die einfachste Umsetzung, die funktionieren kann

Die minimale tragfähige Architektur sollte die logischen Verantwortlichkeiten aus dem vorherigen Part umsetzen, ohne für jede Verantwortung ein separates Produkt einzuführen.

Für einen überschaubaren Batch-orientierten Use Case kann eine vorhandene SQL-Datenbank genügen:

```flow linear vertical
Operative Quellen
SQL-Staging-Tabellen
Standardisierte und integrierte SQL-Views oder Tabellen
Governte Fakten- und Dimensionstabellen
Qualitätsergebnisse
Qlik / Power BI / Excel
```

Mehrere logische Verantwortlichkeiten können dieselbe Datenbank nutzen und dennoch durch Schemas, Namenskonventionen, Ownership und Deployment-Regeln klar getrennt bleiben.

Beispielsweise:

```text
raw.order_lines
stg.order_lines
core.customer
core.product
mart.sales_daily
quality.test_results
```

Dieses Design ist einfach, aber nicht unkontrolliert. Es benötigt weiterhin:

- eine explizite Granularität;
- fachliche und technische Ownership;
- wiederholbare Ladevorgänge;
- stabile Schlüssel;
- dokumentierte Transformationsregeln;
- Qualitätsprüfungen;
- Zugriffskontrolle;
- Ladeüberwachung;
- einen definierten Nutzungsvertrag;
- kontrollierte Änderungen.

Das Fehlen einer zusätzlichen Plattform hebt die Notwendigkeit architektonischer Disziplin nicht auf.

## Konkretes Beispiel: tägliche Vertriebssteuerung

Angenommen, das Unternehmen benötigt ein governtes Datenprodukt für die tägliche Vertriebssteuerung mit folgenden Anforderungen:

| Anforderungsbereich | Entscheidung |
| --- | --- |
| Fachlicher Zweck | Tägliche Vertriebssteuerung und Erkennung negativer Abweichungen |
| Granularität | Eine Auftragszeile pro Business-Datum |
| Quellen | ERP-Aufträge, CRM-Kunden, Produktstamm, Länderreferenzdaten |
| Volumen | Etwa 10 Millionen neue oder geänderte Auftragszeilen pro Jahr |
| Aktualität | Jeden Morgen bis 07:00 verfügbar |
| Historie | Kunden- und Produktzuordnungen müssen historisch reproduzierbar sein |
| Qualität | Verpflichtende Auftrags-, Kunden-, Produkt- und Länderkennung sowie Betrag und Änderungszeitstempel |
| Consumer | Qlik, Power BI und kontrollierter Excel-Zugriff |
| Team | Zwei Entwickler und ein Business Owner |
| Governance | Freigegebene KPI-Definition, Lineage, Sichtbarkeit fehlerhafter Datensätze und Änderungshistorie |
| Erweiterte Verarbeitung | Kein Streaming, kein ML und kein großer unstrukturierter Workload |

Die minimale Architektur könnte verwenden:

- vorhandene Zeitplanung oder ETL für die Extraktion;
- SQL-Staging-Tabellen für empfangene Daten;
- SQL-Views oder gespeicherte Transformationen für die Standardisierung;
- konforme Kunden- und Produktdimensionen;
- einen Sales Fact auf Auftragszeilenebene;
- ein kuratiertes Datenprodukt `sales_daily`;
- eine Tabelle `data_quality_results`;
- einen schlanken Qlik Load;
- ein Power-BI-Semantikmodell oder eine Excel-Reporting-View, wo erforderlich.

Eine vereinfachte zentrale Transformation könnte so aussehen:

```sql
create or replace view mart.sales_daily as
select
    cast(o.order_date as date) as business_date,
    o.order_id,
    o.order_line_id,
    c.customer_key,
    p.product_key,
    c.country_code,
    o.gross_amount,
    o.cancellation_flag,
    case
        when o.cancellation_flag = 1 then 0
        else o.gross_amount
    end as net_revenue,
    o.changed_at
from core.order_line o
join core.customer_history c
  on o.customer_id = c.customer_id
 and o.order_date >= c.valid_from
 and o.order_date < coalesce(c.valid_to, date '9999-12-31')
join core.product_history p
  on o.product_id = p.product_id
 and o.order_date >= p.valid_from
 and o.order_date < coalesce(p.valid_to, date '9999-12-31');
```

Die Syntax muss an die jeweilige Datenbank angepasst werden. Architektonisch entscheidend ist die Platzierung der Regel:

- historische Kunden- und Produktzuordnungen werden zentral aufgelöst;
- die Nettoumsatzbasis wird einmal definiert;
- Qlik, Power BI und Excel erhalten dasselbe governte Ergebnis;
- Qualitätsfehler können vor der Nutzung ausgewertet werden.

Ein bewusst schlankes Qlik-Skript kann sich anschließend auf das Laden des vorbereiteten Produkts und wirklich Qlik-spezifische Benennungs- oder Assoziationslogik beschränken:

```qlik
SalesDaily:
SQL SELECT
    business_date,
    order_id,
    order_line_id,
    customer_key,
    product_key,
    country_code,
    gross_amount,
    cancellation_flag,
    net_revenue,
    changed_at
FROM mart.sales_daily;
```

Qlik bleibt ein leistungsfähiger analytischer Consumer. Es muss nicht zur versteckten Integrations- und KPI-Engine werden, wenn wiederverwendbare Logik vorgelagert gepflegt werden kann.

## Fünf gültige Startpunkte

Dieselbe logische Architektur kann mit unterschiedlichen nativen Fähigkeiten umgesetzt werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start3-img2-de.png"
        alt="Vergleich von fünf gültigen Architektur-Startpunkten mit Qlik und SQL, Microsoft Fabric, Snowflake, Databricks und einem On-Premises-Warehouse über dieselben logischen Schichten"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Quellen, Ingestion, Transformation, governte Datenprodukte und Consumer bleiben dieselben logischen Themen. Die native Umsetzung unterscheidet sich nach vorhandener Plattform und dem Workload, den sie unterstützen muss.
    </figcaption>
</figure>

### Startpunkt 1: Qlik und eine SQL-Datenbank

Das ist eine gültige Architektur, wenn:

- der Workload überwiegend Batch-orientiert ist;
- Transformationen hauptsächlich relational sind;
- die Anzahl der Modelle und Beteiligten beherrschbar bleibt;
- eine SQL-Datenbank bereits zuverlässig betrieben wird;
- Qlik, Power BI oder Excel vorbereitete Tabellen oder Views nutzen können;
- keine fortgeschrittene verteilte Verarbeitung erforderlich ist.

Eine praktikable Zuordnung ist:

| Verantwortung | Mögliche Umsetzung |
| --- | --- |
| Ingestion | Vorhandenes ETL, Scheduler, Datenbank-Jobs oder kontrollierte Qlik-Extraktion, wenn erforderlich |
| Raw / Staging | SQL-Tabellen oder kontrollierte Dateien |
| Standardisierung | SQL-Views, Prozeduren oder geplante Transformationen |
| Integration | SQL-Fakten, Dimensionen, Key-Mappings und Historientabellen |
| Datenprodukt | Kuratierte Tabellen, Views oder governte QVD-Extrakte |
| Qualität | SQL-Prüfungen und eine persistente Tabelle mit Qualitätsergebnissen |
| Nutzung | Schlanke Qlik-Skripte, Power-BI-Semantikmodell, Excel-View |

Diese Option sollte nicht als veraltet abgelehnt werden. Sie sollte nur dann verworfen werden, wenn Skalierung, Zusammenarbeit, Betrieb oder Governance wirtschaftlich nicht ausreichend erfüllt werden können.

### Startpunkt 2: Microsoft Fabric mit Qlik oder Power BI

Fabric kann die einfachste Option sein, wenn die Organisation überwiegend im Microsoft-Ökosystem arbeitet und von integrierten Funktionen für Ingestion, Transformation, Storage, Engineering und Reporting profitiert.

Mögliche Komponenten sind:

- Data-Factory-Pipelines oder Dataflows für Ingestion und Aufbereitung;
- ein Lakehouse oder Warehouse für verwalteten analytischen Storage;
- SQL oder Notebooks für Transformationen;
- governte Tabellen und Semantikmodelle für die Nutzung;
- Power BI als nativer Consumer;
- Qlik oder Excel als Consumer derselben kuratierten Ergebnisse über geeignete Schnittstellen.

Fabric hebt die Notwendigkeit logischer Grenzen nicht auf. Lakehouse, Warehouse und Semantikmodell sollten eingeführt werden, weil sie unterschiedliche Anforderungen erfüllen und nicht allein deshalb, weil sie verfügbar sind.

Eine kleine Fabric-Umsetzung benötigt möglicherweise nur einen Ingestion-Pfad, ein Warehouse oder Lakehouse, wenige Transformationen und ein governtes Datenprodukt. Eine Architektur mit doppelten Pipelines, Notebooks, SQL-Transformationen und semantischer Logik wird nicht allein dadurch einfacher, dass alle Services zu einer Plattform gehören.

### Startpunkt 3: Snowflake mit BI-Tools

Snowflake kann geeignet sein, wenn die Hauptanforderung ein verwaltetes Cloud-Warehouse mit unabhängig skalierbarem Compute, SQL-basierter Transformation und governter Datenfreigabe oder Nutzung ist.

Eine minimale Snowflake-orientierte Umsetzung kann verwenden:

- kontrolliertes Laden in Landing- oder Staging-Schemas;
- SQL-Transformationen in Views, Tabellen, Tasks oder einem bereits vorhandenen Orchestrierungsmechanismus;
- Core- und Mart-Schemas;
- getrennte Compute-Konfigurationen für passende Workloads;
- Qlik, Power BI, Excel oder APIs als Consumer.

dbt kann ergänzt werden, wenn es den Entwicklungsworkflow verbessert. Snowflake setzt dbt nicht voraus, und dbt setzt Snowflake nicht voraus. Die Architekturentscheidung lautet, ob modulare Modellabhängigkeiten, Code Reviews, automatisierte Tests, Dokumentation und teamorientiertes Deployment das zusätzliche Framework rechtfertigen.

### Startpunkt 4: Databricks mit BI-Tools

Databricks kann geeignet sein, wenn der Workload tatsächlich von Lakehouse Storage, Spark-basierter verteilter Verarbeitung, Streaming, fortgeschrittenem Engineering oder Machine Learning profitiert.

Eine minimale Databricks-orientierte Umsetzung kann verwenden:

- kontrolliertes Landing von Dateien, Events oder Quellextrakten;
- Delta-Tabellen für verwaltete Datenzustände;
- SQL, Notebooks oder deklarative Pipelines für Transformationen;
- kuratierte Tabellen oder Datenprodukte;
- BI-Zugriff über governte SQL-Schnittstellen;
- ML oder Streaming nur dort, wo es benötigt wird.

Einige Millionen relationale Zeilen, die einmal täglich geladen werden, benötigen nicht automatisch Spark. Databricks schafft Mehrwert, wenn Verarbeitungsmuster, Skalierung, Datenvielfalt oder ML-Lifecycle ein Problem lösen, das eine einfachere SQL-Architektur nicht ausreichend bewältigen kann.

### Startpunkt 5: ein bestehendes On-Premises-Warehouse

Ein stabiles On-Premises-Warehouse bleibt gültig, wenn es Business-, Sicherheits-, Latenz-, Resilienz- und Kostenanforderungen erfüllt.

Die Modernisierung kann sich konzentrieren auf:

- Verringerung von Logik, die in BI-Anwendungen dupliziert ist;
- versioniertes SQL;
- automatisierte Tests;
- bessere Metadaten und Lineage;
- Trennung von Core-Modellen und consumerspezifischen Views;
- verlässliche Deployment-Pfade;
- governte Schnittstellen für Qlik, Power BI und Excel;
- selektive Ergänzung von Cloud-Funktionen.

Ein Plattformwechsel ist gerechtfertigt, wenn die bestehende Umgebung eine wesentliche Einschränkung verursacht. Er ist nicht allein deshalb gerechtfertigt, weil die aktuelle Plattform nicht als modern bezeichnet wird.

### Hybride Szenarien

Hybrid ist häufig kein vorübergehender Fehler. Es kann die einfachste tragfähige Architektur sein, wenn führende Systeme, Sicherheitsanforderungen, Datenresidenz, Latenz oder Migrationsreihenfolge unterschiedliche Standorte erfordern.

Eine kontrollierte Hybridarchitektur muss definieren:

- welches System für welches Objekt führend ist;
- wo Raw-Daten aufbewahrt werden;
- welche Transformationen On-Premises und welche in der Cloud stattfinden;
- wie Schlüssel und Historie konsistent bleiben;
- wie Daten Netzwerk- und Sicherheitsgrenzen überschreiten;
- wo Qualitätsergebnisse und Lineage zusammengeführt werden;
- welche Schnittstellen für Qlik, Power BI, Excel oder APIs stabil sind;
- wie doppelte Transformationslogik verhindert wird.

Hybrid wird problematisch, wenn beide Seiten dieselbe fachliche Bedeutung unabhängig voneinander implementieren. Das Ziel ist eine logische Architektur über mehrere physische Umgebungen und nicht zwei unkontrollierte Warehouses.

## Wann ein weiteres Tool Mehrwert schafft

Ein weiteres Tool sollte erst ergänzt werden, wenn drei Fragen beantwortet werden können:

1. Welche konkrete Grenze besteht heute?
2. Welche Fähigkeit fehlt?
3. Löst die vorgeschlagene Komponente diese Grenze besser als eine Verbesserung der vorhandenen Plattform?

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start3-img3-de.png"
        alt="Entscheidungsmatrix, die vorhandene Fähigkeiten und konkrete Probleme mit sinnvollen Erweiterungen wie dbt, Microsoft Fabric, Snowflake, Databricks oder keinem neuen Tool verbindet"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein Produktname ist keine Anforderung. dbt, Fabric, Snowflake und Databricks schaffen Mehrwert, wenn sie eine tatsächlich fehlende Fähigkeit bereitstellen. Eine stabile SQL-Landschaft kann kein neues Tool benötigen.
    </figcaption>
</figure>

### dbt wird interessant, wenn SQL-Entwicklung zum Teamproblem wird

dbt kann Mehrwert schaffen, wenn:

- viele SQL-Modelle voneinander abhängen;
- mehrere Entwickler an gemeinsamen Transformationen arbeiten;
- Pull Requests und Code Reviews erforderlich sind;
- wiederholbare Tests an Modelle gebunden werden sollen;
- Dokumentation und Abhängigkeiten konsistent erzeugt werden müssen;
- Deployments zwischen Umgebungen stärker strukturiert werden müssen.

dbt sollte nicht eingeführt werden, um zehn verständliche SQL-Views lediglich durch zehn dbt-Modelle zu ersetzen. Das Framework wird durch das Entwicklungs- und Governance-Problem gerechtfertigt, das es löst.

### Fabric wird interessant, wenn Integration den Gesamt-Stack reduziert

Fabric kann Mehrwert schaffen, wenn:

- die Organisation bereits stark von Microsoft-Services abhängt;
- getrennte Services für Ingestion, Storage, Engineering und Reporting vermeidbare Übergaben erzeugen;
- eine governte Plattform Identität, Betrieb und Bereitstellung vereinfachen kann;
- die Power-BI-Integration strategisch wichtig ist;
- das Team die integrierte Plattform wirksamer betreiben kann als mehrere unabhängige Produkte.

Fabric ist nicht automatisch einfacher, wenn alle vorhandenen Services bestehen bleiben und Fabric zusätzlich darübergelegt wird, ohne Duplikate zu entfernen.

### Snowflake wird interessant, wenn Cloud-Warehousing die tatsächliche Anforderung ist

Snowflake kann Mehrwert schaffen, wenn:

- elastisches oder isoliertes Warehouse Compute benötigt wird;
- SQL-basierte analytische Workloads eine verwaltete Cloud-Plattform benötigen;
- governte Freigabe und teamübergreifende Nutzung wichtig sind;
- der Betriebsaufwand einer selbst verwalteten Datenbank eine wesentliche Einschränkung darstellt;
- Workload-Trennung und Verbrauchsskalierung die Plattform rechtfertigen.

Snowflake sollte nicht allein deshalb ergänzt werden, weil die vorhandene SQL-Datenbank einen anderen Produktnamen trägt.

### Databricks wird interessant, wenn verteilte Verarbeitung erforderlich ist

Databricks kann Mehrwert schaffen, wenn:

- sehr große Datenvolumen verteilte Verarbeitung erfordern;
- hochvolumige Events oder Streaming wesentliche Anforderungen sind;
- komplexe Dateien oder semi-strukturierte Daten den Workload dominieren;
- Data-Engineering- und Machine-Learning-Workflows auf derselben govern­ten Datengrundlage arbeiten sollen;
- Spark-orientierte Fähigkeiten und Betriebspraktiken vorhanden oder begründbar sind.

Eine Spark-Plattform ist kein Erfolgsmaßstab. Sie ist eine Antwort auf einen Workload.

### Kein neues Tool ist eine gültige Entscheidung

Die bestehende Architektur beizubehalten kann richtig sein, wenn:

- Service Levels erfüllt werden;
- Kosten kontrolliert sind;
- Qualitäts- und Audit-Anforderungen erfüllt werden;
- das Team die Lösung versteht und warten kann;
- Skalierungserwartungen realistisch sind;
- Fachlogik wiederverwendbar ist und nicht in Reports eingeschlossen bleibt;
- die nächste Grenze noch nicht erreicht ist.

Die Begründungspflicht liegt bei der vorgeschlagenen Ergänzung und nicht bei der vorhandenen Komponente.

## Eine praktische Entscheidungsmatrix

Die folgende Matrix übersetzt typische Situationen in angemessene Reaktionen.

| Situation | Einfachste sinnvolle Reaktion | Eine weitere Komponente ergänzen, wenn |
| --- | --- | --- |
| Wenige Modelle, kleines Team, täglicher Batch | Vorhandene SQL-Datenbank und geplante Transformationen | Zusammenarbeit, Testing oder Deployment schwierig werden |
| Mehrere BI-Consumer | Gemeinsames SQL-Datenprodukt plus consumerspezifische Semantik- oder Präsentationsschicht | Nutzungsverträge unabhängige Skalierung oder Security benötigen |
| Viele SQL-Abhängigkeiten und Beteiligte | Strukturiertes SQL-Repository, Tests und Deployment-Disziplin | dbt Modularität und Workflow wesentlich verbessert |
| Starke Microsoft-Landschaft | Vorhandene Microsoft-Services konsistent nutzen | Fabric getrennte Services und Übergaben reduziert |
| Verwaltetes elastisches Cloud-Warehouse erforderlich | Cloud-Warehouse wie Snowflake bewerten | Kosten-, Isolations-, Sharing- und Skalierungsvorteile die Migration rechtfertigen |
| Spark-, Streaming- oder ML-lastiger Workload | Databricks oder andere verteilte Plattform bewerten | Verteilte Verarbeitung eine echte Workload-Anforderung ist |
| Stabiles On-Premises-Warehouse | Code, Tests, Lineage und Schnittstellen schrittweise modernisieren | Eine wesentliche Kapazitäts-, Resilienz-, Support- oder strategische Grenze besteht |
| Hybride regulatorische oder migrationsbedingte Grenze | Ein logisches Modell über Standorte definieren | Ein neuer Service kontrollierte Datenbewegung oder Duplikation reduziert |
| Eine Qlik-Anwendung mit lokaler Berechnung | Gemeinsame Bedeutung zunächst vorgelagert definieren | Qlik-spezifisches Verhalten tatsächlich erforderlich ist |
| Vorhandenes SQL erfüllt alle Anforderungen | Einfach halten | Eine messbare Anforderung nicht mehr erfüllt wird |

## Kosten bestehen nicht nur aus Lizenzkosten

Jede zusätzliche Komponente verursacht mehr als eine Subscription.

Die Architektur muss berücksichtigen:

- Plattformadministration;
- Integration von Identität und Zugriff;
- Netzwerke;
- Umgebungen;
- Deployment-Pipelines;
- Monitoring;
- Incident-Bearbeitung;
- Integration von Metadaten und Lineage;
- Backup und Recovery;
- Spezialkenntnisse;
- Hersteller- und Release-Management;
- duplizierten Storage oder Compute;
- zusätzliche Fehlergrenzen.

Eine Komponente kann sich trotzdem lohnen. Ihr Nutzen muss die vollständigen Lebenszykluskosten übersteigen.

## Typische Anti-Patterns

### Eine Referenzarchitektur ohne deren Anforderungen kopieren

Herstellerdiagramme zeigen Fähigkeiten. Sie sind keine Vorgabe, jeden dargestellten Service einzusetzen.

### Ein Tool pro logischer Schicht verwenden

Logische Verantwortlichkeiten benötigen nicht jeweils ein physisches Produkt. Raw, Standardisierung, Integration und Marts können getrennte Schemas in einer Datenbank sein.

### dbt ergänzen, bevor Entwicklungskomplexität besteht

dbt kann modulare SQL-Entwicklung, Testing und Dokumentation verbessern. Es kann keinen Mehrwert erzeugen, wenn kein Problem bei Zusammenarbeit, Abhängigkeiten oder Delivery besteht.

### Spark für gewöhnliche relationale Batch-Verarbeitung auswählen

Verteilte Verarbeitung führt neue Betriebs- und Entwicklungsmuster ein. Sie sollte für Workloads ausgewählt werden, die diese tatsächlich benötigen.

### In die Cloud wechseln, ohne alte Komplexität zu entfernen

Eine Migration, bei der alte Pipelines, alte KPI-Logik und alte Consumer-Extrakte bestehen bleiben, erzeugt eine zusätzliche Plattform statt einer einfacheren Architektur.

### Dieselbe Fachregel in jedem Consumer neu entwickeln

Nettoumsatz, Kundenidentität, Länderzuordnung und Historie dürfen nicht unabhängig in Qlik, Power BI und Excel implementiert werden.

### Integration als Produktfunktion statt als Betriebsmodell behandeln

Auch eine integrierte Plattform kann getrennte Teams, doppelte Pipelines und widersprüchliche Definitionen enthalten. Integration muss sich in Ownership, Entwicklung und Betrieb widerspiegeln.

### On-Premises automatisch als veraltet ansehen

Ein stabiles On-Premises-Warehouse kann technisch und wirtschaftlich angemessen sein. Seine Schwächen müssen explizit bewertet und dürfen nicht allein aus seinem Standort abgeleitet werden.

### Für eine nur angenommene zukünftige Skalierung entwerfen

Die Architektur sollte Wachstum ermöglichen, aber nicht bereits heute für einen Workload bezahlen, der möglicherweise nie entsteht. Skaliert wird, wenn eine glaubwürdige Prognose oder ein gemessener Trend dies begründet.

## Wichtigste Empfehlungen

1. Beginne mit Business-Anforderungen und nicht mit Produktkategorien.
2. Trenne logische Architektur und physische Umsetzung.
3. Nutze vorhandene Werkzeuge und Fähigkeiten, wenn sie die Anforderung erfüllen.
4. Halte gemeinsame Fachlogik außerhalb einzelner Qlik-Apps, Power-BI-Reports und Excel-Arbeitsmappen.
5. Halte Qlik-Skripte so schlank wie praktisch möglich; nur tatsächlich notwendige Qlik-spezifische Logik bleibt dort.
6. Behandle SQL als gültige primäre Transformationsoption für relationale Workloads.
7. Ergänze dbt, wenn Modellabhängigkeiten, Testing und Team-Workflows es rechtfertigen.
8. Nutze Fabric, wenn eine integrierte Microsoft-Plattform die Gesamtarchitektur reduziert.
9. Nutze Snowflake, wenn verwaltetes elastisches Cloud-Warehousing eine konkrete Anforderung löst.
10. Nutze Databricks, wenn verteilte Verarbeitung, Streaming oder ML-Workloads es erfordern.
11. Modernisiere ein stabiles On-Premises-Warehouse, bevor du es standardmäßig ersetzt.
12. Bewahre in Hybridumgebungen eine logische Architektur und eine führende Definition fachlicher Bedeutung.
13. Berücksichtige Betrieb, Governance, Fähigkeiten und Lebenszykluskosten bei jeder Toolentscheidung.
14. Akzeptiere „Kein neues Tool erforderlich“ als legitime Architekturentscheidung.
15. Bewerte die Architektur neu, wenn sich Anforderungen ändern und nicht, wenn ein neues Produkt modern erscheint.

## Übergang zum nächsten Part

Dieser Part hat gezeigt, wie aus expliziten Anforderungen eine angemessene physische Architektur ausgewählt wird. Die nächste Herausforderung besteht darin, die erste Warehouse-Fähigkeit aufzubauen, ohne sofort die gesamte Unternehmensplattform implementieren zu wollen.

Der nächste Part, [Ein Warehouse von Grund auf aufbauen](/playbooks/building-from-scratch), beginnt mit einem vertikalen Datenprodukt und entwickelt den ersten Use Case zu einem wiederverwendbaren Plattformmuster.
