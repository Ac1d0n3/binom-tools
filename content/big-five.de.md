---
title: BIG 5 Stacks im Überblick
description: Snowflake, Databricks, BigQuery, Amazon Redshift und Microsoft Fabric im kompakten Vergleich — Plattformmodell, typische Stärken und wichtige Abwägungen.
author: Thomas Lindackers
category: Data Platforms
tags:
  - data-warehouse
  - lakehouse
  - cloud-data-platform
  - snowflake
  - databricks
  - bigquery
  - redshift
  - microsoft-fabric
order: -1
publishedAt: 2026-07-11
hero: images/playbooks/big-five-hero.png
---

## Fünf Plattformen — fünf unterschiedliche Schwerpunkte

Moderne Datenplattformen werden häufig in einem Atemzug genannt, obwohl sie nicht exakt dasselbe Problem auf dieselbe Weise lösen. Einige kommen aus der klassischen Cloud-Data-Warehouse-Welt, andere aus dem Lakehouse-Ansatz oder aus einer vollständig integrierten Analytics-Suite.

Die **BIG 5** in dieser Story sind deshalb keine offizielle Rangliste. Sie stehen für fünf prägende Plattformansätze:

- **Snowflake** — Cloud-native Data Platform
- **Databricks** — Lakehouse und Data Intelligence
- **Google BigQuery** — serverlose Analytics-Plattform
- **Amazon Redshift** — AWS-natives Cloud Data Warehouse
- **Microsoft Fabric** — integrierte End-to-End-Analytics-Plattform

Alle fünf können große analytische Workloads abbilden. Der Unterschied liegt vor allem darin, **wie Daten gespeichert, verarbeitet, entwickelt und konsumiert werden** — und wie stark die Plattform an ein bestimmtes Cloud-Ökosystem gekoppelt ist.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/big-five-de.png"
        alt="Die fünf modernen Data-Platform-Stacks Snowflake, Databricks, BigQuery, Amazon Redshift und Microsoft Fabric im Vergleich"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die BIG 5 auf hoher Ebene: ähnliche Ziele, aber unterschiedliche Plattformmodelle und Schwerpunkte.
    </figcaption>
</figure>

## Der schnelle Überblick

| Plattform | Grundmodell | Typischer Schwerpunkt |
| --- | --- | --- |
| **Snowflake** | Cloud Data Warehouse / Data Platform | SQL Analytics, Workload-Isolation, Data Sharing |
| **Databricks** | Lakehouse | Data Engineering, Spark, ML und AI |
| **BigQuery** | Serverless Data Warehouse | Skalierbare SQL Analytics ohne Infrastrukturverwaltung |
| **Amazon Redshift** | Managed Cloud Data Warehouse | Analytics im AWS-Ökosystem |
| **Microsoft Fabric** | Integrierte SaaS Analytics Platform | OneLake, Data Engineering, Warehouse und Power BI |

## Snowflake

Snowflake wurde als Cloud-native Datenplattform mit einer klaren Trennung von **Storage**, **Compute** und zentralen Plattformdiensten aufgebaut. Daten werden zentral gespeichert, während unterschiedliche virtuelle Warehouses unabhängig voneinander Rechenleistung bereitstellen können.

Das macht Snowflake besonders attraktiv für Organisationen, die viele SQL-basierte Workloads, unterschiedliche Teams und klar getrennte Compute-Ressourcen auf einer gemeinsamen Datenbasis betreiben möchten.

### Stärken

- **Klare Trennung von Storage und Compute**
- **Unabhängige Compute-Cluster** für unterschiedliche Teams und Workloads
- Sehr starker Fokus auf **SQL, Analytics und klassische Data-Warehouse-Szenarien**
- Einfaches Skalieren ohne Verwaltung eigener Cluster-Infrastruktur
- Starkes Ökosystem für **ELT, dbt, BI und Data Sharing**
- Auf mehreren großen Cloud-Plattformen verfügbar

### Zu beachten

- Verbrauchsbasierte Compute-Nutzung benötigt saubere **Kosten- und Workload-Steuerung**
- Viele komfortable Funktionen sind eng an die Snowflake-Plattform gebunden
- Data-Science- und Engineering-Szenarien können zusätzliche Plattformdienste oder andere Arbeitsweisen benötigen
- Eine einfache Bedienung ersetzt kein durchdachtes Datenmodell und keine saubere Workload-Architektur

**Passt häufig gut für:** SQL-zentrierte Analytics-Plattformen, mehrere BI-Teams, getrennte Workloads und cloudübergreifende Plattformstrategien.

## Databricks

Databricks verfolgt den **Lakehouse-Ansatz**: Die Skalierbarkeit und Offenheit eines Data Lakes werden mit Funktionen kombiniert, die traditionell aus Data Warehouses kommen. Delta Lake bildet dabei die zentrale Tabellen- und Speicherschicht.

Die Plattform ist stark auf Data Engineering, verteilte Verarbeitung, Streaming, Data Science, Machine Learning und AI ausgerichtet. SQL Analytics ist heute ebenfalls ein zentraler Bestandteil, bleibt aber Teil eines breiteren Engineering- und AI-Stacks.

### Stärken

- Gemeinsame Plattform für **Data Engineering, SQL, Streaming, ML und AI**
- Lakehouse-Modell auf Basis von Object Storage und Delta Lake
- Sehr leistungsfähig für große und komplexe Datenverarbeitungs-Workloads
- Gute Unterstützung für strukturierte, semi-strukturierte und unstrukturierte Daten
- Starke Notebook-, Spark- und Python-orientierte Arbeitsweisen
- Geeignet für Teams, die Analytics und Advanced Analytics enger verbinden möchten

### Zu beachten

- Mehr technische Möglichkeiten bedeuten auch mehr **Architekturentscheidungen**
- Spark-, Compute- und Lakehouse-Konzepte benötigen entsprechendes Plattformwissen
- Für rein klassische BI- und SQL-Teams kann der Gesamtumfang zunächst komplexer wirken
- Cluster-, Job- und Compute-Konfigurationen müssen bewusst standardisiert werden

**Passt häufig gut für:** Data Engineering, große Transformations-Workloads, Streaming, Data Science, Machine Learning und AI.

## Google BigQuery

BigQuery ist eine vollständig verwaltete, serverlose Analytics-Plattform in Google Cloud. Infrastruktur, Cluster und klassische Datenbankadministration treten weitgehend in den Hintergrund. Teams arbeiten primär mit Daten, SQL, APIs und verwalteten Plattformfunktionen.

Der serverlose Ansatz eignet sich besonders für stark schwankende oder sehr große analytische Workloads, bei denen möglichst wenig Infrastruktur betrieben werden soll.

### Stärken

- **Serverlose Architektur** ohne klassische Clusterverwaltung
- Sehr schnelle Skalierung für große analytische Abfragen
- Starke Integration in das **Google-Cloud-Ökosystem**
- SQL-basierter Zugriff mit integrierten Analytics-, ML- und Geodatenfunktionen
- Gut geeignet für ereignisbasierte, digitale und sehr große Datenmengen
- Geringer operativer Aufwand für die zugrunde liegende Infrastruktur

### Zu beachten

- Abfrage- und Datenmodell-Design bleiben entscheidend für Performance und Kosten
- Teams benötigen klare Regeln für Datenvolumen, Abfragen und Kapazitätsnutzung
- Der größte Integrationsvorteil entsteht innerhalb des Google-Cloud-Ökosystems
- Bestehende Enterprise-Stacks außerhalb von GCP können zusätzliche Integrationsarbeit erfordern

**Passt häufig gut für:** GCP-zentrierte Plattformen, serverlose Analytics, große oder stark schwankende Abfrage-Workloads.

## Amazon Redshift

Amazon Redshift ist das etablierte Cloud Data Warehouse von AWS. Es unterstützt sowohl bereitgestellte als auch serverlose Betriebsmodelle und ist eng mit AWS-Diensten wie S3, Glue, Lake Formation und dem weiteren Analytics-Ökosystem verbunden.

Mit Redshift Spectrum können Daten direkt in S3 analysiert werden, ohne dass jede Datei zuerst vollständig in lokale Warehouse-Tabellen geladen werden muss.

### Stärken

- Tiefe Integration in das **AWS-Ökosystem**
- Ausgereifte SQL- und BI-Unterstützung
- Wahl zwischen **provisionierten** und **serverlosen** Betriebsmodellen
- Direkte Einbindung von Daten aus Amazon S3
- Gut geeignet für bestehende AWS-Datenplattformen
- Breites Zusammenspiel mit AWS-Security-, Integration- und Analytics-Diensten

### Zu beachten

- Provisionierte Architekturen können mehr Entscheidungen zu Sizing und Workload-Verteilung erfordern
- Die optimale Architektur besteht häufig aus mehreren zusammenspielenden AWS-Diensten
- Plattformbetrieb und Performance-Tuning können sichtbarer sein als bei stärker abstrahierten Serverless-Angeboten
- Der größte Mehrwert entsteht meist bei einer bereits starken AWS-Ausrichtung

**Passt häufig gut für:** AWS-zentrierte Datenplattformen, klassische Enterprise-Warehouses und Analytics auf Daten in S3.

## Microsoft Fabric

Microsoft Fabric bündelt Data Integration, Data Engineering, Data Science, Real-Time Analytics, Data Warehouse und Power BI in einer gemeinsamen SaaS-Plattform. **OneLake** bildet dabei die zentrale logische Datenspeicherschicht für den gesamten Fabric-Tenant.

Fabric bietet sowohl ein Lakehouse als auch ein relationales Warehouse. Dadurch können Spark-, SQL- und Power-BI-orientierte Teams innerhalb einer gemeinsamen Plattform arbeiten.

### Stärken

- Integrierte End-to-End-Plattform von **Ingestion bis Reporting**
- **OneLake** als gemeinsame Datenspeicherschicht
- Enge Verbindung von Data Factory, Lakehouse, Warehouse und Power BI
- Sehr attraktiv für Organisationen mit starkem Microsoft- und Power-BI-Fokus
- Gemeinsame Benutzeroberfläche und Plattform für unterschiedliche Datenrollen
- Geringere Integrationshürden zwischen Engineering, Warehouse und BI innerhalb von Fabric

### Zu beachten

- Kapazitäten und Workloads müssen trotz SaaS-Modell bewusst geplant und gesteuert werden
- Lakehouse, Warehouse, SQL Endpoints und semantische Modelle benötigen klare Architekturregeln
- Die Plattform entwickelt sich schnell; Funktionen und Best Practices verändern sich entsprechend
- Der größte Integrationsvorteil entsteht in einer Microsoft-zentrierten Daten- und BI-Landschaft

**Passt häufig gut für:** Microsoft-zentrierte Unternehmen, Power-BI-Umgebungen und integrierte End-to-End-Analytics-Plattformen.

## Direkter Vergleich

| Kriterium | Snowflake | Databricks | BigQuery | Redshift | Fabric |
| --- | --- | --- | --- | --- | --- |
| **Primärer Ansatz** | Cloud Data Platform | Lakehouse | Serverless Analytics | AWS Data Warehouse | Integrierte Analytics Suite |
| **SQL & BI** | Sehr stark | Stark | Sehr stark | Sehr stark | Sehr stark |
| **Data Engineering** | Stark | Sehr stark | Stark | Stark | Stark |
| **ML & AI Workloads** | Stark | Sehr stark | Stark | Stark | Stark |
| **Infrastrukturaufwand** | Niedrig | Mittel | Sehr niedrig | Niedrig bis mittel | Niedrig |
| **Cloud-Ausrichtung** | Multi-Cloud | Multi-Cloud | Google Cloud | AWS | Microsoft Azure |
| **Natürlicher BI-Fit** | Tool-unabhängig | Tool-unabhängig | Looker / GCP | AWS-Ökosystem | Power BI |

Die Bewertung ist bewusst qualitativ. Jede Plattform deckt heute deutlich mehr ab als ihr ursprünglicher Kernbereich. Die Tabelle zeigt daher **Schwerpunkte**, keine harten Produktgrenzen.

## Kein universeller Gewinner

Die richtige Plattform ergibt sich selten aus einer isolierten Feature-Liste. Entscheidender sind bestehende Cloud-Verträge, vorhandene Kompetenzen, Datenvolumen, Workload-Muster, BI-Landschaft und die Frage, wie viel Plattformkomplexität ein Team selbst steuern möchte.

Eine einfache Orientierung:

- **Snowflake**, wenn SQL Analytics, Workload-Isolation und eine cloudunabhängigere Data Platform im Vordergrund stehen.
- **Databricks**, wenn Data Engineering, Lakehouse, Streaming, ML und AI eng zusammengehören.
- **BigQuery**, wenn serverlose Analytics und eine starke Google-Cloud-Ausrichtung entscheidend sind.
- **Amazon Redshift**, wenn die Datenplattform tief im AWS-Ökosystem verankert ist.
- **Microsoft Fabric**, wenn OneLake, Power BI und eine integrierte Microsoft-Plattform zusammengeführt werden sollen.

Am Ende gewinnt nicht die Plattform mit den meisten Features, sondern der Stack, der **zum Team, zur bestehenden Landschaft und zu den tatsächlichen Workloads** passt.
