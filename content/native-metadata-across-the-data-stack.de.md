---
title: Native Metadaten im gesamten Data Stack — Verstehen, was jedes Produkt bereits weiß
description: Eine praxisnahe Methode, um Schema-, Lineage-, semantische, operative, Nutzungs-, Security- und AI-Metadaten über moderne Datenplattformen zu inventarisieren, bevor ein weiteres Catalog- oder Governance-Tool ergänzt wird.
category: Data Governance
tags:
  - metadata
  - native-metadata
  - metadata-governance
  - data-catalog
  - data-lineage
  - snowflake
  - databricks
  - microsoft-fabric
  - dbt
  - qlik
  - power-bi
  - tableau
  - kafka
  - mlflow
  - data-observability
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 9
seriesTitle: MetaData Deep Dive
hero: images/playbooks/native-metadata-across-the-data-stack-hero.png
publishedAt: 2026-06-28 10:00
---

## Jede Plattform weiß bereits etwas Wichtiges

Organisationen beginnen eine Metadateninitiative häufig mit der Bewertung von Catalogs, Governance Suites oder Active-Metadata-Plattformen.

Diese Reihenfolge ist falsch.

Bevor eine weitere Plattform ausgewählt wird, sollte die Organisation identifizieren, welche Metadaten bereits existieren, wo sie autoritativ sind, wie sie extrahiert werden können und welche Lücken tatsächlich ungelöst bleiben.

Ein moderner Data Stack enthält bereits viele partielle Metadatensysteme:

- eine operative Anwendung kennt Feldbezeichnungen, Prozesszustände und Quell-Constraints;
- eine Database kennt Schemas, Objekte, Berechtigungen und Query-Aktivität;
- ein Lakehouse kennt Tabellen, Files, Notebooks, Jobs und Runtime Lineage;
- Transformationscode kennt Models, Abhängigkeiten, Tests und Dokumentation;
- ein Orchestrator kennt Zeitpläne, Runs, Retries und Fehler;
- eine BI-Plattform kennt Measures, Dimensions, Reports und nutzernahe Semantik;
- eine Identity-Plattform kennt User, Groups, Roles und Access Events;
- eine Streaming-Plattform kennt Topics, Partitions, Offsets und Schema-Versionen;
- ein Observability-System kennt Checks, Incidents und Freshness-Evidenz;
- eine AI-Plattform kennt Datasets, Experiments, Parameters, Metrics und Models.

Jede Plattform besitzt eine lokale Perspektive. Normalerweise versteht keine davon den vollständigen Enterprise-Kontext.

Ein Warehouse kann wissen, dass `analytics.fct_sales_order_line.net_sales_amount` ein Decimal-Feld ist, das von mehreren Usern abgefragt wird. Es weiß möglicherweise nicht, ob der Wert bestellten, fakturierten oder realisierten Umsatz repräsentiert. Ein BI Model kann den freigegebenen Measure-Ausdruck und das Format kennen, aber nicht den ursprünglichen operativen Statusübergang, der bestimmt hat, ob eine Zeile berücksichtigt werden durfte. Eine Identity-Plattform kann beweisen, dass eine Group Zugriff erhalten hat, aber nicht, ob das zugrunde liegende Dataset die richtige Quelle für einen Management-KPI war.

> **Eine gute Metadatenarchitektur beginnt mit einem Inventar nativer Fähigkeiten, Interfaces, Autorität und Lücken. Ein zusätzliches Tool sollte fehlenden Kontext und systemübergreifende Beziehungen verbinden — nicht Metadaten neu erzeugen, die eine andere Plattform bereits korrekt pflegt.**

Das erste Ergebnis ist deshalb keine Vendor Shortlist. Es ist ein Inventar nativer Metadaten.

## Metadatendimensionen trennen, bevor Produkte verglichen werden

Ein Produkt sollte nicht als „metadatenreich“ oder „metadatenarm“ klassifiziert werden, ohne die Metadatendimension zu benennen.

Sieben Dimensionen decken die meisten Enterprise-Anforderungen ab.

### Schema-Metadaten

Schema-Metadaten beschreiben implementierte Strukturen:

```text
catalog
schema
table
view
file
topic
column
data type
nullability
key
constraint
partition
format
```

Databases, Warehouses, Lakehouses, Schema Registries und Semantic Models kennen diese Dimension normalerweise gut.

### Lineage-Metadaten

Lineage-Metadaten beschreiben Abhängigkeit und Bewegung:

```text
source asset
transformation or process
target asset
column mapping
runtime observation
code reference
execution identifier
lineage confidence
```

Transformation Tools, Query Engines, Orchestrators, Streaming-Plattformen und BI-Systeme können jeweils unterschiedliche Lineage-Segmente beitragen.

### Semantische Metadaten

Semantische Metadaten beschreiben Bedeutung und analytisches Verhalten:

```text
business definition
measure
dimension
calculation
aggregation
grain
filter behaviour
format
synonym
approved use
```

Semantic Layers und BI-Plattformen enthalten häufig die vollständigste nutzernahe Semantik. Ein zentraler Catalog, der diese Schicht ignoriert, verfehlt möglicherweise die Definitionen, die Menschen tatsächlich verwenden.

### Operative Metadaten

Operative Metadaten beschreiben Ausführung:

```text
schedule
run
duration
status
retry
checkpoint
refresh
failure
deployment
environment
```

Orchestrators, Transformation Platforms, Stream Processors, Warehouses und Observability Tools sind normalerweise autoritativ für diese Evidenz.

### Usage-Metadaten

Usage-Metadaten beschreiben beobachtete Nutzung:

```text
query
report view
dashboard access
model invocation
consumer group activity
popular asset
unused asset
last accessed
```

Usage kann über Engines, BI-Plattformen, Identity Logs und Application Telemetry verteilt sein. Sie ist selten an einem Ort vollständig.

### Security-Metadaten

Security-Metadaten beschreiben Identität und Kontrolle:

```text
user
group
role
grant
entitlement
classification
policy
masking rule
audit event
access decision
```

Identity-Plattformen, Data Engines, BI-Plattformen und Governance-Systeme halten jeweils unterschiedliche Teile des Security Models.

### AI-Metadaten

AI-Metadaten beschreiben Data-Science- und Model-Lifecycle-Kontext:

```text
training dataset
feature
experiment
run
parameter
metric
artifact
model version
evaluation
prompt
deployment
```

Experiment Tracker und Model Registries sind in dieser Dimension stark, aber normalerweise schwach bei Enterprise Business Vocabulary und Policy Interpretation.

Diese Dimensionen überlappen. Sie sollten unterscheidbar bleiben, weil Ownership, Extraction Methods, Freshness und Control Requirements unterschiedlich sind.

## Produktkategorien kennen unterschiedliche Teile der Wahrheit

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/native-metadata-across-the-data-stack-img1-de.png"
        alt="Sieben Produktkategorien werden mit den Metadatendimensionen verbunden, die sie am besten kennen: operative Systeme, Databases und Warehouses, Lakehouse und Streaming, Transformation und Orchestration, Semantic und BI, Identity und Security sowie AI und Data Science"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Native Metadaten sind bewusst verteilt. Jede Produktkategorie beobachtet die Objekte und Aktivitäten innerhalb ihrer eigenen Betriebsgrenze.
    </figcaption>
</figure>

### Operative Systeme kennen das ursprüngliche Geschäftsverhalten

CRM-, ERP-, Finance-, Service- und Logistics-Anwendungen kennen häufig:

- ursprüngliche Labels und Help Text;
- Source Identifier;
- gültige Codes und Statuswerte;
- Prozessübergänge;
- Validierungs-Constraints;
- lokale Ownership;
- Source-of-Record-Verantwortung;
- Erstellungs- und Änderungszeitpunkte;
- operative Audit Events.

Ihre Schwäche ist häufig die konsistente Extraktion.

Einige Anwendungen stellen strukturierte APIs, Dictionaries oder Configuration Exports bereit. Andere besitzen nur Database Schemas, Administrationsoberflächen oder proprietäre Metadata Endpoints. Eine kopierte Datenbanktabelle kann Feldnamen und Typen zeigen, während die in der Anwendung konfigurierte Prozesssemantik verborgen bleibt.

Source Owner sollte das Application Team oder der accountable Business Process Owner sein — nicht das zentrale Catalog-Team.

### Databases und Warehouses kennen implementierte Strukturen und Engine-Aktivität

Relationale Databases und Cloud Warehouses kennen normalerweise:

- Catalogs, Schemas, Tables, Views und Columns;
- Data Types, Keys und Constraints;
- Comments oder Extended Properties;
- Owner, Roles und Grants;
- Object Dependencies;
- Statistics und Storage Properties;
- Query oder Access History;
- Engine-spezifische Policies und Tags.

Typische Interfaces sind:

- `INFORMATION_SCHEMA`;
- System Catalog Views;
- Account-Usage-Schemas;
- SQL Functions;
- Audit Logs;
- REST APIs;
- JDBC- oder ODBC-Metadaten;
- Platform Event Streams.

Die Engine ist autoritativ für die aktuelle physische Implementierung. Sie ist nicht automatisch autoritativ für fachliche Bedeutung.

### Lakehouse- und Streaming-Plattformen kennen Files, Tables, Events und Processing State

Lakehouse-Plattformen können verbinden:

- Files und Managed Tables;
- Notebooks und Jobs;
- Catalogs und Schemas;
- Table Versions;
- Access Controls;
- Runtime Operations;
- Data und Model Lineage.

Streaming-Plattformen kennen typischerweise:

- Topics;
- Partitions;
- Offsets;
- Brokers;
- Consumer Groups;
- Retention und Configuration;
- Producer- und Consumer-Aktivität.

Ein Broker kennt nicht zwingend die fachliche Bedeutung eines Event Payloads. Schema Registry, Data Contracts, Topic Descriptions und Producer Ownership können erforderlich sein, um diesen Kontext zu ergänzen.

### Transformation und Orchestration kennen Code und Ausführung

Transformation Tools kennen:

- Source Declarations;
- Models;
- Dependencies;
- Tests;
- Documentation;
- kompiliertes oder ausgeführtes SQL;
- Deployment Environments;
- Run Results.

Orchestrators kennen:

- Workflows oder DAGs;
- Tasks;
- Schedules;
- Dependencies;
- Retries;
- Runs;
- Failures;
- Duration;
- Execution Parameters.

Das Transformation Repository sollte autoritativ für implementierte Business Logic bleiben. Der Orchestrator sollte autoritativ für Execution State bleiben. Eine zentrale Plattform kann beide Sichten verbinden, ohne Code in Prosa zu kopieren.

### Semantic- und BI-Plattformen kennen Consumption Semantics

Qlik, Power BI, Tableau und ähnliche Tools können kennen:

- Fields;
- Associations oder Relationships;
- Dimensions;
- Measures;
- Calculations;
- Semantic Models;
- Data Sources;
- Applications, Reports und Dashboards;
- Publication State;
- Usage und Ownership;
- usernahe Lineage.

Diese Schicht kann Business Logic enthalten, die weder im Warehouse noch im Transformation Repository existiert.

Ein Measure wie `Recognized Revenue YTD` kann ausschließlich in einem Semantic Model existieren. Eine Qlik Expression kann Selections, Set Analysis und Aggregation Behaviour verbinden. Ein Power-BI-Measure kann DAX Logic und Filter Context kodieren. Ein Tableau Calculated Field kann eine Consumer-nahe Definition tragen, die in der Source Database nicht sichtbar ist.

BI-Metadaten zu ignorieren erzeugt die falsche Annahme, dass das governte Datenmodell am Warehouse endet.

### Identity- und Security-Plattformen kennen Personen und Entitlements

Identity-Plattformen kennen:

- Users;
- Groups;
- Service Principals;
- Roles;
- Memberships;
- Entitlements;
- Authentication Events;
- Provisioning;
- Administrative Changes;
- Access Reviews.

Sie sind autoritativ für Identity State. Sie wissen normalerweise nicht, warum ein Dataset existiert oder was ein Measure bedeutet.

Security-Metadaten müssen ebenfalls geschützt werden. Group Memberships, Audit Details und Access Paths können selbst sensitiv sein.

### AI- und Data-Science-Plattformen kennen Experiments und Model Lifecycle

MLflow und ähnliche Plattformen können erfassen:

- Experiments und Runs;
- Parameters und Metrics;
- Code References;
- Artifacts;
- Datasets;
- Model Versions;
- Aliases und Tags;
- Evaluations;
- Deployment Context.

Diese Evidenz unterstützt Reproducibility und Model Governance.

Sie belegt nicht automatisch, ob das Training Dataset für den vorgesehenen Zweck freigegeben war, ob die Target Variable konzeptionell valide ist oder ob eine Feature Definition zum Enterprise Vocabulary passt. Diese Verbindungen müssen durch Governance-Metadaten ergänzt werden.

## Plattformfamilien über Fähigkeitskategorien vergleichen, nicht über eine volatile Checkliste

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/native-metadata-across-the-data-stack-img2-de.png"
        alt="Vergleich der Fähigkeitskategorien von Snowflake, Databricks, Microsoft Fabric und einem klassischen SQL Warehouse über Catalogs, Beschreibungen, Grants, Historie, Lineage, Quality Evidence, APIs und Lücken für externen Kontext"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Plattformfamilien überlappen, unterscheiden sich jedoch bei Metadatentiefe, Scope, Retention, Interfaces und Lizenzbedingungen. Exakte aktuelle Fähigkeiten müssen vor der Implementierung geprüft werden.
    </figcaption>
</figure>

Ein Vergleich über Fähigkeitskategorien ist dauerhafter als eine Matrix aus grünen und roten Haken.

| Fähigkeitskategorie | Snowflake | Databricks | Microsoft Fabric | Klassisches SQL Warehouse |
| --- | --- | --- | --- | --- |
| Catalogs und Schemas | Starke Account- und Database-Object-Metadaten | Starkes Unity-Catalog-Objektmodell, wenn Workloads dort governed werden | Workspace-, Item-, OneLake- und Semantic-Model-Metadaten | Starke database-lokale Catalogs; Enterprise Scope variiert |
| Object Descriptions | Comments, Tags und Object Properties | Comments, Tags, Properties und Catalog Context | Item Descriptions, Labels, Endorsements und ausgewählte Sub-Item-Metadaten | Comments oder Extended Properties hängen von Engine und Disziplin ab |
| Access und Grants | Roles, Privileges, Policies und Access Evidence | Unity-Catalog-Privileges, Ownership und Audit-/System-Evidenz | Workspace-/Item-Permissions, Tenant- und Item-Governance-Kontext | Roles, Grants und Audit Features variieren je Engine |
| Query oder Operation History | Warehouse- und Account-History-Views | Query-, Audit-, Job- und System Tables je Service | Activity-, Refresh-, Capacity- und Item-Operation-Evidenz über mehrere Services | Query Store, Audit, Logs oder Extensions variieren |
| Lineage Availability | Object Dependencies und Access-abgeleitete Beziehungen; Coverage ist Workload-abhängig | Runtime Lineage für unterstützte Unity-Catalog-Workloads; externe Coverage muss validiert werden | Native Item Lineage plus Scanner- und Purview-Integration; Tiefe variiert nach Item Type | Meist begrenzt ohne Parsing, Instrumentation oder externe Tools |
| Data-Quality-Evidenz | Constraints, Profiling Results sowie Partner- oder Custom Checks | Expectations, Constraints, Monitoring und externe Quality-Systeme | Data-Quality-Fähigkeiten und Evidenz variieren je Fabric-/Purview-Komponente | Constraints und Custom Checks; häufig fragmentiert |
| APIs und Export | SQL Views, Functions, Drivers und APIs | Information Schema, System Tables und REST APIs | Fabric REST APIs, Scanner APIs und service-spezifische Interfaces | SQL Catalogs, Drivers, Logs und Engine-spezifische APIs |
| Lücken für externen Kontext | Enterprise Vocabulary, Cross-Platform Semantics und Non-Snowflake Lineage | Non-Databricks Semantics, Source Process Meaning und externer Consumption Context | Externe Systeme, detaillierte Cross-Tool Lineage und Domain Definitions | Enterprise Search, Semantic Context und Cross-System Relationships |

Diese Tabelle bestimmt keinen Sieger. Sie zeigt, wo ein Inventar suchen muss.

### Snowflake

Snowflake stellt breite technische und operative Metadaten über Information Schema und Account Usage Views bereit. Object Metadata, Dependencies, Query History, Access History, Tags und Policy References können über unterstützte SQL Interfaces abgefragt werden.

Wichtige Einschränkungen bleiben:

- View Latency und Retention unterscheiden sich;
- einige Fähigkeiten hängen von Edition oder Configuration ab;
- Object Dependencies repräsentieren nicht jede Form von Data Movement;
- Query-abgeleitete Lineage und deklarierte Dependencies sind unterschiedliche Evidenztypen;
- Business Definitions und Cross-Platform Semantics benötigen weiterhin externen Kontext.

Die richtige Inventarfrage lautet nicht „Hat Snowflake Metadaten?“, sondern:

```text
Welche Snowflake Views sind aktiviert,
welche Environments werden abgedeckt,
wie lang ist die Retention,
wer verantwortet die Extraktion
und welche Beziehungen liegen außerhalb von Snowflake?
```

### Databricks

Databricks mit Unity Catalog stellt Catalogs, Schemas, Tables, Columns, Permissions und Governance Context bereit. Information Schema, REST APIs und System Tables unterstützen programmatische Extraktion. Native Lineage kann Beziehungen für unterstützte Workloads erfassen und über Lineage System Tables abgefragt werden.

Die Coverage muss trotzdem bewertet werden:

- Objekte außerhalb von Unity Catalog können anders sichtbar sein;
- externe Systeme erscheinen möglicherweise nicht automatisch;
- Notebook-, Job-, Model- und Table-Beziehungen besitzen unterschiedliche Scopes;
- Preview Interfaces sollten ohne Prüfung nicht als stabile Contracts behandelt werden;
- Business Vocabulary und BI Semantics bleiben extern, solange sie nicht bewusst verbunden werden.

Unity Catalog kann eine starke quellnahe Governance-Schicht sein. Es entfernt nicht den Bedarf nach Enterprise Identity Resolution über mehrere Plattformen.

### Microsoft Fabric

Microsoft Fabric verteilt Metadaten über Workspaces, OneLake, Lakehouse, Warehouse, Data Factory, Real-Time Intelligence, Semantic Models, Reports und administrative Services.

Der OneLake Catalog unterstützt Discovery innerhalb von Fabric. Metadata Scanner APIs können Tenant-, Workspace-, Item- und ausgewählte Semantic-Model-Subartifact-Metadaten liefern, wenn erforderliche Tenant Settings und Permissions konfiguriert sind. Fabric und Microsoft Purview können Metadaten und Lineage austauschen, die Granularität variiert jedoch nach Item Type und aktuellem Product Support.

Ein Inventar muss deshalb unterscheiden:

```text
Fabric Item Metadata
Power BI Semantic Model Metadata
OneLake Object Metadata
Lineage shown in Fabric
Metadata returned by Scanner APIs
Metadata available to Purview
Activity and Audit Evidence
```

Alles als eine „Fabric Metadata API“ zu behandeln verdeckt wichtige Unterschiede.

### Klassisches SQL Warehouse

Ein klassisches SQL Warehouse oder eine relationale Database stellt typischerweise langlebige System Catalogs, Information Schema, Roles, Grants, Constraints, Statistics und Engine Logs bereit. SQL Server ergänzt beispielsweise Catalog Views, Extended Properties, Query Store und Audit-Fähigkeiten.

Die Hauptlücke ist normalerweise der systemübergreifende Kontext.

Eine Database kann ihre eigenen Objekte sehr gut beschreiben und gleichzeitig wenig wissen über:

- vorgelagerte operative Anwendungen;
- außerhalb ausgeführte Transformationen;
- BI Measures;
- Report Usage;
- Enterprise Vocabulary;
- Model-Training-Nutzung;
- Stewardship Decisions.

Ein Lightweight Central Index kann deshalb erheblichen Mehrwert liefern, obwohl die Database selbst bereits starke Metadaten bereitstellt.

## Transformation- und BI-Metadaten verbinden, nicht abflachen

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/native-metadata-across-the-data-stack-img3-de.png"
        alt="Verbundene Zonen für dbt und Code Repositories, Orchestration, Qlik Power BI und Tableau sowie eine Catalog- oder Governance-Plattform zeigen, wo Transformationslogik, Runtime Evidence und nutzernahe Semantik liegen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Business Logic kann in Transformation Code, Orchestration Parameters oder im Consumption Layer liegen. Zentrale Governance sollte diese Quellen verbinden und ihre Autorität erhalten.
    </figcaption>
</figure>

### dbt und Code Repositories

Ein dbt Project erzeugt ein besonders nutzbares Metadatenpaket.

Project und generierte Artifacts können beschreiben:

- Models und Sources;
- Tests;
- Macros;
- Exposures;
- Semantic Models und Metrics;
- Dependencies;
- Descriptions;
- Ownership Conventions;
- Compiled Properties;
- Run Results;
- Source Freshness.

`manifest.json` repräsentiert Project Resources und Relationships. Weitere Artifacts ergänzen Catalog Details und Execution Evidence.

Git trägt eine andere Dimension bei:

- Commit Identifier;
- Author und Committer;
- Timestamp;
- Branch und Tag;
- Change History;
- Pull-Request- oder Review-Kontext, wenn die Hosting-Plattform ihn bereitstellt.

Git beweist, welcher Code sich wann geändert hat. Es beweist nicht, dass die fachliche Definition korrekt ist.

Das passende Muster lautet:

```text
dbt resource
→ linked to source tables
→ linked to Git revision
→ linked to run evidence
→ linked to BI consumers
→ linked to approved business terms
```

### Orchestration

Airflow und andere Orchestrators verstehen den Runtime Graph.

Eine DAG Definition kann Task Dependencies zeigen. DAG Runs und Task Instances zeigen den tatsächlichen Execution State. Stabile APIs können Workflows, Runs und Task Results bereitstellen.

Diese Metadaten sind wesentlich für:

- Operational Lineage;
- Freshness;
- Incident Analysis;
- SLA Evidence;
- Retry Behaviour;
- Failure Ownership.

Sie sollten nicht als einzige Quelle für Transformation Semantics verwendet werden. Ein Orchestrator kann wissen, dass Task B nach Task A läuft, ohne jede Column-Level Transformation innerhalb des Tasks zu verstehen.

### Qlik

Qlik Applications enthalten Data Models, Fields, Dimensions, Measures, Expressions, Scripts und Consumption Context. Qlik Cloud stellt außerdem Lineage- und Impact-Analysis-Fähigkeiten für unterstützten katalogisierten Content bereit.

Coverage hängt von Deployment, Content Type und Ladeart ab. App Metadata, Script Logic und Catalog Lineage sind zusammenhängende, aber nicht identische Quellen.

Ein Metadateninventar sollte deshalb trennen:

- App Object Metadata;
- Load Script Metadata;
- Logical Data Model Metadata;
- Master Dimensions und Measures;
- Reload History;
- Catalog Lineage;
- Usage;
- Security Rules oder Entitlements.

### Power BI

Power BI und Fabric Semantic Models können enthalten:

- Tables und Columns;
- Relationships;
- Measures;
- DAX Expressions;
- Power Query oder Mashup Expressions;
- Sensitivity Labels;
- Endorsements;
- Reports und Dashboards;
- Refresh- und Usage-Kontext.

Administrative Scanner APIs können ausgewählte Artifact- und Subartifact-Metadaten liefern, wenn Tenant Settings, Permissions und Scan Options konfiguriert sind.

Ein Warehouse-only Catalog verfehlt Semantic-Model-Logik, wenn er diese Schicht nicht erfasst.

### Tableau

Die Tableau Metadata API verwendet ein GraphQL Metadata Model, um veröffentlichte Workbooks, Data Sources, Flows und zugehörige externe Assets bereitzustellen. Tableau Catalog kann je Deployment und Lizenz Lineage, Impact Analysis, Descriptions, Certifications und Data Quality Warnings ergänzen.

Die wesentliche Lücke ist der Scope: veröffentlichter Tableau Content kann detailliert indexiert werden, während Logic oder Source Context außerhalb von Tableau weiterhin andere Systeme benötigt.

## Streaming-Metadaten benötigen Contracts, nicht nur Broker-Inventar

Apache Kafka kennt seine operative Topologie:

```text
cluster
broker
topic
partition
replica
consumer group
offset
configuration
retention
```

Das sind wertvolle operative Metadaten.

Sie definieren allein nicht:

- fachliche Bedeutung eines Events;
- Field-Level Schema;
- Compatibility Expectations;
- Producer Ownership;
- erlaubte Consumer-Nutzung;
- Klassifikation personenbezogener Daten;
- Begründung der Retention.

Eine Schema Registry ergänzt Schema Versions und Compatibility. Ein Stream Catalog oder Data-Contract-Layer kann Tags, Ownership und Business Metadata ergänzen. Producer Repositories können Code und Deployment Lineage hinzufügen. Consumer Groups ergänzen beobachtete Usage.

Die vollständige Streaming-Sicht wird deshalb aus mehreren nativen Quellen zusammengesetzt.

## Observability liefert Evidenz, keine freigegebene Bedeutung

Observability- und Data-Quality-Systeme können kennen:

- erwartete Freshness;
- Validation Rules;
- Validation Results;
- Volume Changes;
- Schema Drift;
- Anomaly Signals;
- Incidents;
- Acknowledgements;
- betroffene Assets;
- Recovery Time.

Great Expectations erzeugt beispielsweise Validation Results aus ausgeführten Expectations. OpenLineage repräsentiert Jobs, Runs, Datasets und erweiterbare Facets, die Lineage- und Quality-Kontext transportieren können.

Diese Evidenz ist operativ wichtig. Sie sollte freigegebene Business Definitions oder Classifications nicht still überschreiben.

Ein fehlgeschlagener Uniqueness Test beweist, dass beobachtete Daten eine Expectation verletzt haben. Er beweist nicht, ob die Expectation selbst freigegeben, richtig gescoped oder weiterhin angemessen war.

## Extraction Interfaces sind Teil der Architektur

Eine Fähigkeit ist praktisch nicht verfügbar, nur weil eine Product UI sie anzeigt.

Für jede Metadatenkategorie muss das unterstützte Extraction Interface dokumentiert werden.

| Interface Type | Typische Stärken | Typische Grenzen |
| --- | --- | --- |
| SQL Catalog oder System View | Stabil, abfragbar, automation-freundlich | Meist auf eine Engine und Permission Scope begrenzt |
| REST API | Strukturiert, remote erreichbar, häufig filterbar | Pagination, Rate Limits, Versioning und Tenant Settings |
| GraphQL API | Flexible Relationship Traversal | Query-Komplexität, Permissions und Schema-Version-Abhängigkeit |
| Generated Artifact | Versionierbar und reproduzierbar | Muss nach relevanten Runs erzeugt und eingesammelt werden |
| Audit oder Activity Log | Starke operative Evidenz | Hohes Volumen, Retention und sensitiver Zugriff |
| Event Stream | Niedrige Latenz und Change-orientiert | Benötigt Ordering, Deduplication und Replay Design |
| Repository Scan | Erfasst Code, Ownership und Historie | Static Analysis entspricht nicht zwingend Runtime Behaviour |
| UI Export | Nützlich für ein erstes Assessment | Schwache Automatisierung und häufig unvollständige Historie |
| Direct Source Reference | Bewahrt Autorität und Detailkontext | Hängt von Source Availability und Identity Mapping ab |

Das Inventar muss außerdem enthalten:

```text
authentication method
required role
scope
pagination
rate limit
retention
latency
history
incremental extraction
deletion detection
schema version
owner
failure handling
```

Ein Connector-Name ohne diese Eigenschaften ist kein Integrationsdesign.

## Mit dem einfachsten tragfähigen Inventar nativer Metadaten starten

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/native-metadata-across-the-data-stack-img4-de.png"
        alt="Assessment Workflow vom Auflisten der Plattformen über native Metadaten, Interfaces, Ownership, Coverage, Freshness und Lücken bis zur Entscheidung für Integration, Erweiterung oder Ersatz"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Tool-Auswahl sollte durch fehlende Fähigkeiten und Consumer Needs bestimmt werden. Vendor Category Names sind kein Beweis für eine Lücke.
    </figcaption>
</figure>

Die einfachste tragfähige Implementierung ist ein strukturiertes Inventar.

### Schritt 1: Plattformen und Environments auflisten

Nicht nur strategische Produkte erfassen.

Einzubeziehen sind:

- Production- und Non-Production-Environments;
- Source Applications;
- Databases und Warehouses;
- Lakehouses;
- Streaming Platforms;
- Transformation Repositories;
- Orchestrators;
- BI Platforms;
- Identity Systems;
- Observability Systems;
- AI- und Model-Plattformen;
- bestehende Catalogs und Spreadsheets.

Shadow Metadata Systems sind relevant, weil sie häufig Definitionen oder Ownership enthalten, die keine offizielle Plattform erfasst hat.

### Schritt 2: Native Metadaten identifizieren

Für jede Plattform die sieben Metadatendimensionen prüfen.

Nicht nur fragen, was in der UI verfügbar ist. Prüfen, was:

- gespeichert;
- generiert;
- beobachtbar;
- exportierbar;
- abfragbar;
- historisch aufbewahrt;
- durch Permissions geschützt

ist.

### Schritt 3: Unterstützte Interfaces identifizieren

Das exakte unterstützte Interface und den Extraction Scope dokumentieren.

Beispiele:

```text
Snowflake ACCOUNT_USAGE view
Databricks information_schema
Fabric scanner API
dbt manifest artifact
Airflow REST API
Qlik Engine or repository API
Power BI admin scan result
Tableau Metadata API
Kafka AdminClient
Schema Registry REST API
Microsoft Graph
MLflow REST or client API
OpenLineage events
```

Ein Beispielinterface garantiert keine vollständige Coverage. Das Inventar muss die tatsächlich zurückgegebenen Objekte dokumentieren.

### Schritt 4: Source Ownership zuordnen

Jede Extraktion benötigt zwei Owner:

```text
Source metadata owner
Connector or integration owner
```

Der Source Owner versteht Bedeutung und Permissions. Der Integration Owner pflegt Collection, Mapping, Monitoring und Failure Recovery.

Ohne beide Rollen wird der Connector langfristig zu einem verwaisten technischen Asset.

### Schritt 5: Coverage und Freshness messen

Coverage sollte anhand benötigter Consumer Questions gemessen werden.

Beispiel:

```text
Können wir jedes Production Dataset finden?
Können wir jeden Report identifizieren, der ein deprecated Field verwendet?
Können wir die freigegebene KPI-Definition abrufen?
Können wir beweisen, wer auf vertrauliche Daten zugegriffen hat?
Können wir eine Model Version zu ihrem Training Dataset zurückverfolgen?
Können wir erkennen, ob Metadaten veraltet sind?
```

Freshness muss explizit sein:

```text
source observed at
collected at
last successful sync
expected interval
current staleness status
```

### Schritt 6: Lücken identifizieren

Jede Lücke klassifizieren.

```text
Not available in source
Available but not extractable
Extractable but not retained
Available but permission-restricted
Available but not normalized
Available but not linked across systems
Available but not approved
Available but too stale
Available but not trusted
```

Unterschiedliche Lücken benötigen unterschiedliche Lösungen.

### Schritt 7: Integrate, Extend oder Replace entscheiden

Drei Aktionen verwenden.

**Integrate**, wenn die Quelle bereits autoritative Metadaten hält und ein nutzbares Interface bereitstellt.

**Extend**, wenn die Quelle autoritativ ist, aber ausgewählte Attribute, Historie, Workflow oder Cross-System Relationships fehlen.

**Replace** nur dann, wenn die bestehende Fähigkeit das erforderliche Operating Model nicht erfüllen kann und die Migrationskosten gerechtfertigt sind.

Einen Catalog zu kaufen, um eine verfügbare System View zu duplizieren, ist kein Replacement. Es ist eine zusätzliche Synchronisierungsverpflichtung.

## Eine Assessment-Tabelle über den gesamten Stack verwenden

Ein praktisches Inventar kann mit diesen Spalten beginnen:

| Metadata Type | Source System | Interface | Freshness | History | Owner | Quality | Consumer Need |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Table Schema | Snowflake | Information Schema | Nahezu aktuell | Aktueller State plus Platform History | Warehouse Team | Hoch für Physical State | Search und Impact |
| dbt Model Dependency | dbt Project | `manifest.json` | Je Build | Git plus aufbewahrte Artifacts | Analytics Engineering | Hoch für deklarierten DAG | Lineage |
| Pipeline Run | Airflow | REST API | Near Real Time | Metadata-DB-Retention | Platform Operations | Hoch für Runtime State | Freshness und Incidents |
| BI Measure | Power BI | Scanner API oder Model Interface | Je Scan | Abhängig von Retention Strategy | BI Product Owner | Hoch für Published Model | KPI Discovery |
| Qlik Expression | Qlik App | App- oder Engine-Metadaten | Je Reload oder Scan | Benötigt Version Strategy | Qlik App Owner | Mittel bis zum Review | Semantic Impact |
| Stream Schema | Schema Registry | REST API | Nahezu aktuell | Versionierte Schemas | Streaming Team | Hoch für registrierten Contract | Producer- und Consumer-Compatibility |
| Identity Group | Entra ID | Microsoft Graph | Nahezu aktuell | Audit Retention variiert | Identity Team | Hoch für Directory State | Access Analysis |
| Model Run | MLflow | Tracking API | Je Run | Tracking-Store-Retention | Data Science Platform | Hoch für protokollierte Evidenz | Reproducibility |
| Business Definition | Governance Repository | API oder Workflow Export | Bei Approval | Versionierte Approvals | Data Steward | Hoch bei Freigabe | Korrekte Nutzung |

Die Werte sind Beispiele. Jede Organisation muss ihre realen Interfaces und Permissions testen.

## Konkretes Beispiel: Ein Sales-Feld über den Stack verfolgen

Betrachten wir `net_sales_amount`.

### Operative Quelle

Die Sales Application kennt:

- das ursprüngliche Amount Field;
- Order Status;
- Discount- und Tax-Flags;
- Source Currency;
- Cancellation Behaviour;
- Source Identifier;
- Process Owner.

Dies ist der beste Ursprung für Source Meaning und Process Constraints.

### Streaming oder Ingestion

Kafka kann kennen:

- das Topic für Order Events;
- Partition- und Retention-Settings;
- Producer- und Consumer-Aktivität.

Schema Registry kann kennen:

- das Event Schema;
- Field Name und Type;
- Schema Version;
- Compatibility Rules;
- registrierte Tags.

Keine dieser Quellen kennt automatisch die freigegebene Enterprise-Revenue-Definition.

### Warehouse oder Lakehouse

Die Data Platform kennt:

- Physical Table und Column;
- Type und Nullability;
- Grants;
- Queries;
- Object Dependencies;
- Storage- oder Runtime History.

Sie kann Implementierung und Nutzung innerhalb der Plattform belegen.

### dbt

Das dbt Project kennt:

- Source Mapping;
- Transformation Model;
- Dependency Graph;
- Tests;
- Documentation;
- Code Revision;
- aktuelles Run Result.

Es kann erklären, wie aus dem Source Value `net_sales_amount` wurde.

### Orchestration

Der Orchestrator kennt:

- wann die Pipeline lief;
- welcher Task fehlschlug;
- ob der Retry erfolgreich war;
- welches Environment ausgeführt wurde;
- wie lange der Run dauerte.

Dies unterstützt Freshness und Operational Accountability.

### BI

Der Semantic- oder BI-Layer kann kennen:

- `Net Sales`;
- den freigegebenen Measure-Ausdruck;
- Currency Formatting;
- Date Relationship;
- Filters;
- Report Consumers;
- Usage.

Dies kann der einzige Ort sein, an dem der Wert in den KPI übersetzt wird, den User sehen.

### Governance

Die Governance-Schicht sollte verbinden:

```text
source field
→ event field
→ warehouse column
→ dbt model column
→ semantic measure
→ report
→ business term
→ KPI
→ owner
→ policy
```

Sie sollte nicht jede native Property manuell neu schreiben.

Die verbleibenden Lücken können sein:

- eine stabile Cross-System Identity;
- freigegebene Revenue Definition;
- Ownership und Stewardship;
- Cross-Platform Lineage;
- Policy Classification;
- Metadata Quality und Freshness;
- Change-Impact Workflow.

Diese Lücken rechtfertigen zentrale Fähigkeiten. Bestehende Schemas, Runs und Expressions müssen nicht manuell neu erstellt werden.

## Alternative Implementierungsmuster

### Nur quellnative Metadaten

Source Systems und ihre eigenen Interfaces ohne zentrale Metadatenplattform verwenden.

Geeignet, wenn:

- der Stack klein ist;
- dasselbe Team die meisten Systeme besitzt;
- Cross-Platform Search nicht kritisch ist;
- Audit- und Impact-Analysis-Anforderungen begrenzt sind.

Warnung:

User müssen wissen, wo sie suchen müssen, und Cross-System Relationships bleiben manuell.

### Lightweight Central Index

Identifier, ausgewählte Descriptions, Ownership, Relationships und Freshness in einen durchsuchbaren Index harvesten.

Geeignet, wenn:

- Source Metadata grundsätzlich stark sind;
- Central Discovery die Hauptlücke ist;
- Detail-Properties an der Quelle verbleiben sollen;
- die Organisation geringe Operating Complexity anstrebt.

Warnung:

Identity Resolution, Connector Monitoring und Stale-Reference Handling benötigen weiterhin Engineering.

### Federated Catalog

Central Search, Vocabulary und Minimum Standards bereitstellen, während Domains detaillierte Metadaten verantworten.

Geeignet, wenn:

- mehrere Domains unterschiedliches Fachwissen besitzen;
- lokale Ownership real existiert;
- Cross-Domain Navigation und Policy benötigt werden.

Warnung:

Federation ohne verpflichtende Contracts wird zu fragmentierter Dokumentation.

### Enterprise Active-Metadata-Plattform

Breites Harvesting, Lineage, Workflow, Policy, Automation und Downstream Activation verbinden.

Geeignet, wenn:

- die Landschaft groß ist;
- Metadaten Controls auslösen müssen;
- Cross-System Impact operativ wichtig ist;
- dedizierte Platform Ownership vorhanden ist.

Warnung:

Eine breite Plattform erhöht Connector-, Licensing-, Mapping-, Security- und Operating-Verpflichtungen. Sie entfernt Source Ownership nicht.

## Häufige Anti-Patterns

### Kauf vor Inventar

Die Organisation wählt eine Product Category, bevor die fehlende Fähigkeit definiert wurde.

Ergebnis:

- bestehende Metadaten werden dupliziert;
- Connectors werden ohne Consumer Questions konfiguriert;
- Platform Scope wächst schneller als der Nutzen.

### Eine UI als API behandeln

Ein Produkt zeigt Lineage oder Usage in seiner Oberfläche. Das Team nimmt deshalb an, dieselben Metadaten seien extrahierbar.

Ergebnis:

- unsupported Scraping;
- unvollständige Exports;
- fragile Integration;
- kein vertragliches Schema.

### Jede Beziehung Lineage nennen

Declared Dependency, Parsed SQL, Runtime Observation, Manual Mapping und Inferred Similarity werden als derselbe Edge gespeichert.

Ergebnis:

User können Confidence nicht bewerten und Impact nicht korrekt interpretieren.

### Consumption-Layer-Logik ignorieren

Das Inventar endet bei Warehouse Tables.

Ergebnis:

Measures, Calculations, Filters und Report-Level Definitions bleiben unsichtbar.

### Code in Beschreibungen kopieren

Transformation Logic wird in ein Catalog Field eingefügt.

Ergebnis:

Die Beschreibung veraltet und verliert ihre Verbindung zur ausführbaren Version.

### High-Volume Operational Evidence wahllos zentralisieren

Jede Query, jedes Event, jeder Audit Record und jedes Profiling Result wird in einen allgemeinen Metadata Store kopiert.

Ergebnis:

Kosten, Sensitivität und Retention-Probleme steigen, ohne Discovery zu verbessern.

### Extraction Ownership mit Metadata Ownership verwechseln

Das Catalog-Team betreibt einen Connector und wird deshalb zum Owner der importierten Metadaten erklärt.

Ergebnis:

Kein accountable Source Team korrigiert Bedeutung oder Qualität.

### Permissions und Licensing ignorieren

Eine Fähigkeit wird aus einer Product Page dokumentiert, ohne Tenant, Edition, Role und API Scope zu testen.

Ergebnis:

Der Implementierungsplan setzt Metadaten voraus, die in der realen Umgebung nicht abgerufen werden können.

## Entscheidungshilfe

Vor der Auswahl eines weiteren Tools müssen diese Fragen beantwortet werden.

### Coverage

Welche Consumer Questions können mit bestehenden nativen Metadaten nicht beantwortet werden?

### Authority

Welches System ist für jedes Metadatenattribut autoritativ?

### Interface

Können die Metadaten über ein unterstütztes, automatisierbares Interface extrahiert werden?

### Freshness

Wie aktuell müssen die Metadaten sein, und kann die Quelle diese Anforderung erfüllen?

### History

Bewahrt die Quelle genug Historie für Audit, Impact Analysis und Incident Investigation?

### Identity

Kann dasselbe Asset über Source-, Transformation-, Warehouse-, BI- und AI-Systeme aufgelöst werden?

### Semantics

Wo liegen die tatsächlichen Business Definitions und Calculations?

### Security

Können Metadaten gesammelt werden, ohne eingeschränkte Identities, Samples oder Audit Evidence offenzulegen?

### Operations

Wer überwacht Connector Health, Schema Changes, Failed Scans und Stale Metadata?

### Value

Welche fehlende Fähigkeit verändert eine reale Entscheidung, Control oder einen Workflow?

Die Tool-Entscheidung folgt aus diesen Antworten.

## Zentrale Empfehlungen

1. Native Metadaten inventarisieren, bevor eine Vendor Shortlist erstellt wird.
2. Schema-, Lineage-, semantische, operative, Usage-, Security- und AI-Metadaten trennen.
3. Jedes Attribut mit einer autoritativen Quelle und accountable Ownership verbinden.
4. Exaktes Extraction Interface, Scope, Freshness, History und Permissions dokumentieren.
5. Transformation Repositories, Orchestration und BI einbeziehen — nicht nur Databases.
6. Declared, Parsed, Observed, Manual und Inferred Lineage unterscheiden.
7. Zentrale Plattformen für Cross-System Identity, Discovery, Vocabulary, Policy und Workflow verwenden.
8. High-Volume oder sensitive Evidenz nicht kopieren, wenn Reference oder Aggregate ausreichen.
9. Connector Operations als governte Data-Engineering-Verantwortung behandeln.
10. Zusätzliche Tools nur für gemessene Lücken und definierte Consumer Needs auswählen.

> **Das Ziel ist nicht, die größtmögliche Menge an Metadaten zu sammeln. Das Ziel ist, die richtige native Evidenz mit ausreichender Autorität, Freshness und Kontext für vertrauenswürdige Entscheidungen zu verbinden.**

## Als Nächstes: Lineage, Impact Analysis und Metadatenvererbung

Native Metadaten zeigen, was jede Plattform weiß.

Die nächste Herausforderung besteht darin, diese lokalen Beobachtungen zu belastbaren End-to-End-Beziehungen zu verbinden.

Part 10 behandelt Lineage, Impact Analysis und Metadata Propagation:

- wie Lineage aus Code, Queries, Events und Manual Mappings erfasst wird;
- wie Column-Level Relationships dargestellt werden sollten;
- wie Confidence und Provenance die Impact Analysis beeinflussen;
- welche Metadaten sicher propagiert werden können;
- wann vererbter Kontext reviewed oder überschrieben werden muss;
- wie Änderungen ein Downstream Assessment auslösen sollten.

Ein Inventar nativer Metadaten liefert die Evidenz. Lineage bestimmt, wie daraus ein verbundenes Change Model entsteht.

## Hinweis zur aktuellen Verifikation

Die Produktbeispiele in diesem Artikel wurden anhand offizieller Dokumentation geprüft, die im Juli 2026 verfügbar war. Product Scope ändert sich häufig. Vor der Implementierung müssen aktuelle Editions, Tenant Settings, Retention, API Versions, Permissions, Preview Status und Object Coverage erneut geprüft werden.

Offizielle Referenzen:

- [Snowflake Account Usage](https://docs.snowflake.com/en/sql-reference/account-usage)
- [Snowflake Object Dependencies](https://docs.snowflake.com/en/sql-reference/account-usage/object_dependencies)
- [Databricks Information Schema](https://docs.databricks.com/aws/en/sql/language-manual/sql-ref-information-schema)
- [Databricks Lineage System Tables](https://docs.databricks.com/aws/en/admin/system-tables/lineage)
- [Microsoft Fabric Metadata Scanning](https://learn.microsoft.com/en-us/fabric/governance/metadata-scanning-overview)
- [Microsoft Fabric OneLake Catalog](https://learn.microsoft.com/en-us/fabric/governance/onelake-catalog-overview)
- [dbt Manifest Artifact](https://docs.getdbt.com/reference/artifacts/manifest-json)
- [Qlik Lineage](https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Catalog/lineage.htm)
- [Power BI Workspace Scanner API](https://learn.microsoft.com/en-us/rest/api/power-bi/admin/workspace-info-post-workspace-info)
- [Tableau Metadata API](https://help.tableau.com/current/api/metadata_api/en-us/index.html)
- [Apache Airflow REST API](https://airflow.apache.org/docs/apache-airflow/stable/stable-rest-api-ref.html)
- [Apache Kafka Documentation](https://kafka.apache.org/documentation/)
- [MLflow Tracking](https://mlflow.org/docs/latest/ml/tracking/)
- [OpenLineage Object Model](https://openlineage.io/docs/1.44.0/spec/object-model)
