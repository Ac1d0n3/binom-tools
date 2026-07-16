---
title: Self-Hosted Data Platforms — Moderne Analytics jenseits von Cloud-first
description: Wie sich Data Warehouse, Transformation, Governance und Reporting heute überwiegend selbst betreiben lassen — von klassischen SQL-Plattformen bis zum offenen Lakehouse.
category: Data Platforms
tags:
  - self-hosted
  - on-prem
  - data-warehouse
  - data-platform
  - analytics
  - reporting
  - dbt
  - lakehouse
  - governance
  - hybrid-cloud
order: -1
author: Thomas Lindackers
hero: images/stories/self-hosted-hero.png
---

## Cloud ist eine Option — nicht die Architektur

Moderne Datenplattformen werden heute häufig automatisch mit Cloud-Angeboten verbunden. Snowflake, Databricks, BigQuery, Redshift oder Microsoft Fabric haben viele Architekturentscheidungen vereinfacht und neue Betriebsmodelle etabliert. Daraus folgt jedoch nicht, dass jede moderne Analytics-Plattform zwingend vollständig in einer Public Cloud betrieben werden muss.

Data Warehouse, Transformation, Governance, semantische Modelle und Reporting können weiterhin **überwiegend selbst betrieben** werden — im eigenen Rechenzentrum, in einer Private Cloud, auf virtualisierter Infrastruktur oder auf einer intern verwalteten Container-Plattform.

Dabei ist eine wichtige Unterscheidung sinnvoll:

- **On-Premises** beschreibt primär den Ort: Infrastruktur läuft im eigenen Rechenzentrum oder an einem selbst kontrollierten Standort.
- **Self-Hosted** beschreibt primär die Verantwortung: Das Unternehmen betreibt Plattform, Software und Betriebsprozesse selbst oder gemeinsam mit einem Dienstleister.
- **Private Cloud** beschreibt ein stärker automatisiertes, cloudähnliches Betriebsmodell auf dedizierter oder selbst kontrollierter Infrastruktur.

In der Praxis überschneiden sich diese Modelle. Entscheidend ist weniger das Etikett als die Frage, **wer Infrastruktur, Daten, Sicherheit, Updates, Skalierung und Verfügbarkeit verantwortet**.

> **Self-hosting is not the opposite of modern. It is a different operating model.**

Diese Story bewertet bewusst noch keine Kosten. Sie zeigt zunächst, welche technischen Varianten heute möglich sind, welche Verantwortung damit verbunden ist und wo ein selbst betriebener Ansatz sinnvoll sein kann.

## Eine moderne selbst betriebene Datenplattform

Auch eine überwiegend intern betriebene Plattform folgt grundsätzlich derselben logischen Kette wie eine Cloud Data Platform:

`Quellsysteme → Ingestion & Replikation → analytischer Speicher → Transformation & Datenmodellierung → Semantik & Reporting → Analytics`

Governance, Security, Metadaten, Monitoring und Betrieb wirken dabei nicht nur auf eine einzelne Schicht. Sie sollten die gesamte Plattform begleiten.

<figure class="playbook-prose__figure">
    <img
        src="images/stories/self-hosted-platform-de.png"
        alt="Moderne selbst betriebene Datenplattform von operativen Quellsystemen über Replikation, analytischen Speicher und Transformation bis zu Reporting und Analytics"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine moderne Self-Hosted Data Platform nutzt dieselben logischen Schichten wie eine Cloud-Plattform — der Unterschied liegt vor allem im Betriebs- und Verantwortungsmodell.
    </figcaption>
</figure>

Die einzelnen Schichten können sehr unterschiedlich umgesetzt werden. Eine kleinere Plattform kann aus wenigen stabilen Komponenten bestehen. Eine größere Umgebung kann zusätzliche Cluster, Object Storage, Container, Kataloge, Orchestrierung und getrennte Umgebungen benötigen.

Wichtig ist: **Modernität entsteht nicht allein durch den Hosting-Ort.** Automatisierung, klare Schichten, versionierter Code, Tests, standardisierte Deployments, Monitoring und Governance sind meist entscheidender als die Frage, ob ein Server im eigenen Rechenzentrum oder bei einem Hyperscaler steht.

## Warum Analytics-Daten meist trotzdem repliziert werden

Auch in einer vollständig lokalen Architektur ist es selten sinnvoll, alle Dashboards dauerhaft direkt auf produktive ERP-, CRM- oder operative Datenbanken zugreifen zu lassen.

Ein analytischer Speicher erfüllt andere Aufgaben als ein operatives System:

- operative Systeme werden vor schweren analytischen Abfragen geschützt
- Daten aus mehreren Quellen können zusammengeführt werden
- historische Zustände lassen sich aufbewahren
- Daten können bereinigt, harmonisiert und fachlich modelliert werden
- Reporting bleibt unabhängiger von Wartungen und Änderungen der Quellsysteme
- Berechtigungen und Governance können für Analytics gezielt umgesetzt werden
- wiederkehrende Abfragen lassen sich auf analytische Workloads optimieren

Deshalb bleibt Replikation auch On-Prem ein typischer Bestandteil:

`SAP / ERP / CRM → Ladeprozess oder Replikation → analytischer Speicher → Datenmodelle → Reporting`

Eine „Spiegelung“ muss jedoch nicht automatisch eine vollständige Echtzeitkopie sein. Das passende Verfahren hängt vom fachlichen Aktualitätsbedarf ab.

| Aktualitätsbedarf | Typischer Ansatz |
| --- | --- |
| tägliches Management Reporting | nächtlicher Batch Load |
| mehrmals tägliche Auswertungen | inkrementelle Ladeprozesse |
| zeitnahe operative Analytics | Change Data Capture oder häufige Micro-Batches |
| echte Event- oder Near-Real-Time-Szenarien | kontinuierliche Replikation oder Streaming |

Die technische Möglichkeit zu Echtzeit bedeutet nicht, dass jedes Unternehmen Echtzeit benötigt. Häufig sind stabile, nachvollziehbare und gut überwachte Ladefenster wichtiger als minimale Latenz.

## Vier typische Self-Hosted-Architekturen

Es gibt nicht den einen On-Prem-Stack. Unterschiedliche Plattformmodelle können sinnvoll sein — abhängig von Datenvolumen, Team-Skills, bestehenden Lizenzen, Betriebsmodell und Workloads.

### 1. Klassischer Enterprise SQL Stack

```flowchart
ERP / CRM / operative Datenbanken
ETL / Replikation
SQL Data Warehouse
Enterprise BI
```

Typische Bausteine können sein:

- SQL Server, PostgreSQL, Oracle oder eine andere relationale Datenbank
- vorhandene ETL- und Integrationstools
- SQL-Skripte, Stored Procedures oder dbt Core für Transformationen
- Qlik Sense Enterprise on Windows, Power BI Report Server oder andere intern betriebene Reporting-Lösungen

Dieser Ansatz kann gut passen, wenn bereits Datenbank-, Windows-, Linux-, Qlik- oder Microsoft-Know-how vorhanden ist und überwiegend strukturierte Daten verarbeitet werden.

**Stärken**

- etablierte Technologien und Betriebsprozesse
- häufig bereits vorhandene Kompetenzen und Infrastruktur
- gute Unterstützung für klassische Dimensionen-, Fakten- und Reporting-Modelle
- überschaubare Zahl zusätzlicher Plattformkomponenten

**Zu beachten**

- Skalierung und Hochverfügbarkeit müssen geplant werden
- ältere ETL- oder Datenmodellierungsansätze sollten nicht ungeprüft übernommen werden
- Versionskontrolle, Tests und automatisierte Deployments entstehen nicht automatisch
- ein klassischer Stack kann modern betrieben werden — benötigt dafür aber moderne Engineering-Praktiken

### 2. Moderner analytischer Datenbank-Stack

```flowchart
Quellsysteme
Replikation / ELT
Analytische Datenbank
dbt Core / SQL-Transformation
BI und Analytics
```

Ein Beispiel wäre:

`Quellen → ClickHouse → dbt Core / SQL → Superset, Metabase oder Qlik`

Analytische Datenbanken sind auf große Scan-, Filter- und Aggregations-Workloads ausgerichtet. Sie können für Log-, Event-, IoT- oder große Reporting-Datenmengen interessant sein.

**Stärken**

- hohe Abfrageleistung für analytische Workloads
- moderne SQL-orientierte Architektur
- häufig gute Eignung für große, append-lastige Datenmengen
- Trennung operativer Systeme von analytischer Last

**Zu beachten**

- Datenmodellierung und Workload-Verhalten unterscheiden sich teilweise von klassischen relationalen Warehouses
- Betrieb, Updates, Backup, Replikation und Monitoring bleiben eigene Aufgaben
- nicht jede analytische Datenbank ist für jede Art von Transaktion oder Datenänderung geeignet
- Benchmarks ersetzen keinen Test mit den eigenen Daten und Abfragen

### 3. Selbst betriebenes Open Lakehouse

```flowchart
Quellsysteme
Object Storage
Offenes Tabellenformat
SQL- oder Compute Engine
Analytics & Reporting
```

Ein möglicher Stack wäre:

`Quellen → S3-kompatibler Object Storage → Apache Iceberg → Trino oder Spark → BI`

Der Lakehouse-Ansatz trennt Speicher, Tabellenformat und Compute stärker voneinander. Offene Tabellenformate können Daten für mehrere Engines nutzbar machen und reduzieren die Bindung an ein einzelnes proprietäres Speicherformat.

**Stärken**

- offene Dateiformate und austauschbare Compute Engines
- geeignet für strukturierte und große dateibasierte Datenbestände
- flexible Kombination aus SQL, Data Engineering und gegebenenfalls Data Science
- Storage und Compute können unabhängig geplant werden

**Zu beachten**

- mehr technische Freiheit erzeugt mehr Architekturentscheidungen
- Object Storage, Katalog, Tabellenformat, Compute Engine und Orchestrierung müssen zusammenspielen
- Security, Berechtigungen und Governance verteilen sich über mehrere Komponenten
- Kompaktierung, Dateigrößen, Partitionierung und Metadatenpflege werden zu Betriebsaufgaben
- für kleine, stabile BI-Landschaften kann dieser Ansatz unnötig komplex sein

Ein Open Lakehouse ist deshalb nicht automatisch „moderner“ oder „besser“ als ein relationales Warehouse. Es ist ein anderes Architekturmodell für andere Anforderungen.

### 4. Hybride Self-Hosted-Plattform

Für viele Unternehmen ist eine Mischform realistischer als ein vollständiges Entweder-oder:

```text
Interne Quellsysteme
          ↓
Selbst betriebene Datenplattform
          ↓
Internes Reporting
          +
gezielt ausgewählte Cloud-Dienste
```

Beispiele:

- zentrale Datenhaltung und Reporting bleiben intern
- externe SaaS-Daten werden über APIs integriert
- einzelne AI-, Übersetzungs- oder Geodienste werden gezielt genutzt
- Backups oder Disaster-Recovery-Komponenten liegen an einem zweiten Standort oder in einer Cloud
- Entwicklungs- und Kollaborationsdienste können extern betrieben werden, während produktive Daten intern bleiben

Hybrid bedeutet nicht automatisch eine Übergangsarchitektur. Es kann ein bewusstes Zielbild sein, bei dem jede Komponente dort betrieben wird, wo Kontrolle, Betriebsaufwand, Integration und Skalierbarkeit am besten zusammenpassen.

## Die Bausteine eines modernen Self-Hosted Data Stacks

Eine selbst betriebene Plattform besteht nicht nur aus einem Data Warehouse. Sie umfasst technische und organisatorische Schichten vom Quellsystem bis zum Datenkonsumenten.

<figure class="playbook-prose__figure">
    <img
        src="images/stories/self-hosted-stack-de.png"
        alt="Moderner selbst betriebener Data Stack mit Quellen, Ingestion, analytischem Speicher, Transformation, Governance, Reporting und zugrunde liegender Infrastruktur"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der eigentliche Stack umfasst mehr als Storage: Replikation, Transformation, Semantik, Governance, Security, Monitoring und Wiederherstellung gehören zum Betriebsmodell.
    </figcaption>
</figure>

| Schicht | Typische Aufgaben | Mögliche selbst betriebene Ansätze |
| --- | --- | --- |
| **Quellen** | ERP, CRM, operative Datenbanken, Dateien, APIs, IoT | SAP, relationale Datenbanken, Fileshares, interne Anwendungen |
| **Ingestion & Replikation** | Batch Loads, inkrementelle Loads, CDC, Validierung | vorhandene ETL-Tools, Airbyte, NiFi, Kafka, eigene Pipelines |
| **Analytischer Speicher** | Warehouse, Data Lake, Staging, historisierte Daten | PostgreSQL, SQL Server, Oracle, ClickHouse, Object Storage |
| **Transformation** | Datenmodelle, Geschäftslogik, Tests, Datenqualität | dbt Core, SQL, Stored Procedures, Spark |
| **Semantik & Governance** | KPIs, Glossar, Metadaten, Zugriff, Ownership | BI-Semantik, Kataloge, dbt-Metadaten, interne Governance-Werkzeuge |
| **Reporting & Analytics** | Dashboards, Ad-hoc-Analysen, operative Reports, Exporte | Qlik Sense Enterprise on Windows, Power BI Report Server, Superset, Metabase |
| **Betrieb** | Monitoring, Logging, Backup, Recovery, Patching | vorhandene Infrastruktur- und Observability-Werkzeuge |

Die Liste ist keine Produktempfehlung und kein Pflicht-Blueprint. Einzelne Werkzeuge decken unterschiedliche Teile der Plattform ab. In vielen Unternehmen ist ein kleiner, gut integrierter Stack belastbarer als eine große Sammlung technisch interessanter Einzelkomponenten.

## Self-Hosted bedeutet nicht Legacy

Die Gleichung

`Cloud = modern`  
`On-Prem = legacy`

ist zu einfach.

Eine intern betriebene Plattform kann moderne Engineering-Prinzipien nutzen:

- Infrastructure as Code
- Git und Pull Requests
- CI/CD
- containerisierte Deployments
- automatisierte Tests
- dbt-basierte Transformationen
- deklarative Konfiguration
- zentrale Logs und Metriken
- automatisierte Backups und Recovery-Tests
- Metadata- und Policy-Governance

Umgekehrt kann auch eine Cloud-Plattform manuell, unübersichtlich und ohne klare Standards betrieben werden.

Die relevantere Frage lautet daher:

> **Wie automatisiert, beobachtbar, sicher, standardisiert und wartbar ist die Plattform?**

Der Hosting-Ort ist nur ein Teil der Antwort.

## Was ein Unternehmen beim Self-Hosting übernimmt

Mehr Kontrolle bedeutet gleichzeitig mehr Verantwortung.

### Unter eigener Kontrolle

- Infrastruktur und Netzwerk
- Datenhaltung und physischer beziehungsweise logischer Speicherort
- Wartungsfenster und Update-Zeitpunkte
- Sicherheitsarchitektur und interne Zugriffswege
- Backup- und Wiederherstellungsstrategie
- Kapazitätsplanung
- Integration in bestehende Identity- und Netzwerkstrukturen

### In eigener Verantwortung

- Installation und Konfiguration
- Patches und Versionswechsel
- Hochverfügbarkeit
- Performance Tuning
- Monitoring und Alerting
- Backup-Prüfungen und Restore-Tests
- Disaster Recovery
- Zertifikate, Secrets und technische Benutzer
- Schwachstellenmanagement
- Dokumentation und Betriebsübergabe
- Bereitschaft, Support und Eskalationswege

> **Self-hosting removes neither complexity nor risk. It changes who owns them.**

Ein Unternehmen kann Teile dieser Verantwortung an Hosting-, Managed-Service- oder Infrastrukturpartner übertragen. Die Plattform bleibt dann möglicherweise dediziert oder selbst kontrolliert, obwohl nicht jedes Betriebselement intern ausgeführt wird.

## Wann ein selbst betriebener Ansatz gut passen kann

Self-Hosting kann attraktiv sein, wenn mehrere dieser Bedingungen zusammenkommen:

- viele relevante Datenquellen befinden sich bereits im internen Netzwerk
- Datenvolumen und Wachstum sind relativ gut planbar
- bestehende Rechenzentrums-, Virtualisierungs- oder Datenbankinfrastruktur ist vorhanden
- internes Plattform-, Datenbank- oder BI-Know-how ist langfristig verfügbar
- Workloads sind stabil und benötigen keine extrem elastische Skalierung
- Netzwerklatenz zu lokalen Produktions- oder ERP-Systemen ist relevant
- besondere Integrationen oder technische Freiheiten sind erforderlich
- Daten und Betriebsprozesse sollen in einer stark kontrollierten Umgebung bleiben
- vorhandene Lizenzen und Betriebsmodelle sollen sinnvoll weitergenutzt werden

Keine einzelne Bedingung entscheidet allein. Insbesondere „Daten müssen in Deutschland bleiben“ bedeutet nicht automatisch On-Prem. Cloud-Plattformen können ebenfalls regionale Hosting-, Sicherheits- und Compliance-Anforderungen unterstützen. Umgekehrt macht ein deutscher Standort eine selbst betriebene Plattform nicht automatisch einfacher oder wirtschaftlicher.

## Wann ein vollständig selbst betriebener Ansatz schwieriger werden kann

Ein Self-Hosted-Modell kann unpassend oder unnötig aufwendig sein, wenn:

- stark schwankende Workloads sehr schnell skaliert werden müssen
- weltweit verteilte Teams und Regionen auf dieselbe Plattform zugreifen
- nur ein sehr kleines Betriebsteam vorhanden ist
- Plattform-Patching und 24/7-Verfügbarkeit nicht zuverlässig abgedeckt werden können
- neue Managed Services regelmäßig ohne eigenen Integrationsaufwand genutzt werden sollen
- die benötigte Infrastruktur nur für wenige sporadische Workloads vorgehalten würde
- schnelle Bereitstellung wichtiger ist als maximale technische Kontrolle

Auch hier gibt es kein pauschales Urteil. Ein Managed Service kann Betriebsaufwand reduzieren, erzeugt aber andere Abhängigkeiten, Kostenmodelle und Governance-Fragen. Diese Abwägung gehört in die nächste Story.

## Ein sinnvoller Entscheidungsrahmen

Vor einer Produktentscheidung sollten zunächst die Anforderungen an das Betriebsmodell geklärt werden.

| Frage | Warum sie wichtig ist |
| --- | --- |
| Wo liegen die wichtigsten Quellsysteme? | beeinflusst Netzwerk, Latenz und Integrationsaufwand |
| Wie aktuell müssen Daten wirklich sein? | bestimmt Batch, inkrementelle Loads, CDC oder Streaming |
| Wie stark schwanken Datenmenge und Rechenbedarf? | beeinflusst Sizing und Skalierungsmodell |
| Welche Skills sind langfristig verfügbar? | entscheidet, welche Plattform auch betreibbar bleibt |
| Welche Verfügbarkeit wird benötigt? | bestimmt Redundanz, Support und Disaster Recovery |
| Welche Daten und Metadaten müssen kontrolliert werden? | prägt Security, Governance und Auditierbarkeit |
| Welche BI- und Datenbanktechnologien existieren bereits? | kann Integration und Betriebsaufwand deutlich verändern |
| Wie viel Plattformkomplexität kann das Team verantworten? | verhindert überdimensionierte Architekturmodelle |

Erst danach sollte entschieden werden, ob ein relationales Warehouse, eine analytische Datenbank, ein Lakehouse oder eine hybride Architektur der passende Kern ist.

## Fazit

Eine moderne Data Platform muss nicht zwingend mit einem Public-Cloud-Service beginnen. Sie kann überwiegend selbst betrieben werden und trotzdem moderne Architektur- und Engineering-Prinzipien nutzen.

Der entscheidende Unterschied liegt nicht in den logischen Datenschichten. Quellen, Replikation, analytischer Speicher, Transformation, Governance und Reporting bleiben in beiden Welten notwendig. Der Unterschied liegt vor allem darin, **wer Plattformfunktionen bereitstellt und wer die operative Verantwortung trägt**.

Die bessere Ausgangsfrage lautet deshalb nicht:

*„Cloud oder On-Prem?“*

sondern:

***„Welches Betriebsmodell passt zu unseren Daten, unseren Workloads, unseren Fähigkeiten und unserer langfristigen Verantwortung?“***

Die nächste Story kann darauf aufbauen und beide Welten anhand von **Kosten, Kontrolle, Betriebsaufwand, Skalierbarkeit, Performance und Unternehmenskontext** vergleichen.

## Weiterführende Ressourcen

### Transformation und Datenbanken

- [dbt Core installieren — dbt Developer Hub](https://docs.getdbt.com/docs/local/install-dbt)
- [PostgreSQL Dokumentation](https://www.postgresql.org/docs/current/)
- [ClickHouse Installation — offizielle Dokumentation](https://clickhouse.com/docs/install)

### Open Lakehouse

- [Apache Iceberg Dokumentation](https://iceberg.apache.org/docs/latest/)
- [Trino Dokumentation](https://trino.io/docs/current/)
- [Trino Iceberg Connector](https://trino.io/docs/current/connector/iceberg.html)

### Self-Hosted BI

- [Qlik Sense Enterprise on Windows — Deployment](https://help.qlik.com/en-US/sense-admin/May2026/Subsystems/DeployAdministerQSE/Content/Sense_DeployAdminister/QSEoW/Deploy_QSEoW/Qlik-Sense-installation.htm)
- [Power BI Report Server — Übersicht](https://learn.microsoft.com/en-us/power-bi/report-server/get-started)
- [Apache Superset — Installation](https://superset.apache.org/admin-docs/installation/installation-methods/)
