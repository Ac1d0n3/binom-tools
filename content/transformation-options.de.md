---
title: Optionen für Datentransformationen
description: Wie zwischen SQL, nativen Plattformfunktionen, Notebooks, Dataflows, Stored Procedures, klassischen ETL-Werkzeugen und dbt gewählt wird, ohne aus einer Toolentscheidung die Architektur zu machen.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - data-transformation
  - sql
  - stored-procedures
  - notebooks
  - dataflows
  - etl
  - elt
  - dbt
  - microsoft-fabric
  - snowflake
  - dynamic-tables
  - databricks
  - lakeflow
  - spark
  - python
  - qlik-sense
  - power-bi
  - excel
  - data-products
  - data-quality
  - data-governance
  - lineage
  - ci-cd
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 8
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start8-hero.png
---

## Die Transformationstechnologie ist eine Designentscheidung und nicht die Architektur

Jede moderne Datenplattform benötigt Transformationen.

Quellwerte müssen standardisiert, Datensätze integriert, Business Keys aufgelöst, Historien interpretiert, Qualitätsregeln angewendet und wiederverwendbare Datenprodukte veröffentlicht werden. Keine dieser Verantwortlichkeiten legt automatisch fest, ob die Implementierung SQL, eine Stored Procedure, ein Notebook, einen Dataflow, eine native Plattformfunktion, ein klassisches ETL-Werkzeug oder dbt verwenden muss.

Dieselbe gültige Geschäftsregel kann auf mehreren technisch tragfähigen Wegen umgesetzt werden:

```text
SQL View
Geplanter Tabellenaufbau
Stored Procedure
Low-Code-Dataflow
Python- oder Spark-Notebook
Native deklarative Pipeline
Klassisches ETL-Mapping
dbt-Modell und Datentest
```

Die Architektur wird fragil, wenn das Unternehmen mit dem Tool statt mit der Verantwortung beginnt.

Eine typische Tool-first-Diskussion klingt so:

```text
„Wir haben Fabric, deshalb gehört jede Transformation in ein Notebook.“
„Wir nutzen Snowflake, deshalb muss jede Pipeline eine Dynamic Table sein.“
„Wir haben dbt eingeführt, deshalb muss jedes SQL migriert werden.“
„Das Qlik-Skript funktioniert bereits, deshalb bleibt es die führende Implementierung.“
„Der Fachanwender bevorzugt einen Dataflow, deshalb gehört die Regel dauerhaft dorthin.“
```

Jede Aussage kann für einen konkreten Workload sinnvoll sein. Keine davon ist eine allgemeine Architekturregel.

Part 7, [Ein Datenprodukt, mehrere Consumer](/playbooks/one-data-product-multiple-consumers), hat gezeigt, dass Qlik, Power BI, Excel, APIs und KI dieselbe governte fachliche Wahrheit über unterschiedliche Verträge konsumieren können. Dieser Part beantwortet die nächste Frage: **Wo soll die Transformation ausgeführt werden, die diese Wahrheit erzeugt?**

> **Wähle den einfachsten Transformationsmechanismus, der die Anforderungen von Workload, Team und Governance erfüllt. Gib jeder gemeinsamen Geschäftsregel eine führende Implementierung und einen verantwortlichen Owner.**

## Architekturprinzip: eine Transformationsverantwortung, eine führende Implementierung

Eine Transformationsarchitektur sollte zuerst Verantwortlichkeiten definieren und erst danach Technologien zuordnen.

Die zentralen Fragen lauten:

```text
Welche fachliche Bedeutung wird erzeugt?
Mit welcher Granularität wird das Ergebnis veröffentlicht?
Welche Quellabhängigkeiten bestehen?
Wer verantwortet die Korrektheit?
Wie wird die Regel getestet?
Wie wird die Implementierung geprüft und versioniert?
Wie wird sie geplant und überwacht?
Welche Consumer hängen von ihr ab?
Wie werden Breaking Changes kommuniziert?
Wo werden fehlerhafte Datensätze und Qualitätsnachweise gespeichert?
```

Eine hilfreiche Einordnung ist:

| Art der Logik | Bevorzugter Architekturort |
| --- | --- |
| Gemeinsame Bereinigung und Standardisierung | Governte Transformationsschicht |
| Wiederverwendbare Geschäftsregeln | Eine führende governte Implementierung |
| Schlüsselauflösung und Historisierung | Governter Kern oder Datenproduktschicht |
| Datenqualitätsvalidierung | Ausführbare Regel plus persistenter Ergebnisnachweis |
| Plattformspezifische Performanceoptimierung | Native Plattformimplementierung, als technisches Verhalten dokumentiert |
| Qlik-Assoziationen, Section Access oder Set Analysis | Schlankes Qlik-Consumer-Modell, wenn das Verhalten tatsächlich Qlik-spezifisch ist |
| Power-BI-Filterkontext und Berichts-Measures | Semantisches Modell, wenn das Verhalten tatsächlich modellspezifisch ist |
| Excel-Layout, Kommentare und kontrollierte Annahmen | Arbeitsmappe oder Planungsprozess, getrennt von governten Ist-Daten |
| Temporäre Exploration | Notebook oder Sandbox mit explizitem Überführungspfad |

Diese Trennung verhindert zwei gegensätzliche Fehler.

Der erste Fehler besteht darin, alles zu zentralisieren, auch Logik, die nur innerhalb einer bestimmten Analyse-Engine sinnvoll ist. Der zweite besteht darin, jedem Tool eine eigene Version gemeinsamer fachlicher Bedeutung zu erlauben.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start8-img1-de.png"
        alt="Mehrere gültige Transformationsmethoden wie SQL, native Plattformfunktionen, Notebooks, Dataflows, dbt und Stored Procedures erzeugen governte Tabellen, Modelle und Datenprodukte"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Es gibt viele gültige Wege für Datentransformationen. Das Ziel besteht nicht darin, jeden Workload auf ein Tool zu standardisieren, sondern getestete, wiederverwendbare und governte Daten mit klarer Verantwortung zu erzeugen.
    </figcaption>
</figure>

## Viele gültige Wege für Datentransformationen

Die wichtigsten Transformationsoptionen überschneiden sich. Sie schließen sich nicht gegenseitig aus, und die meisten realen Plattformen verwenden mehr als eine davon.

### SQL-Skripte, Views und materialisierte Tabellen

SQL ist häufig der einfachste Ausgangspunkt, wenn sich die Daten bereits in einer relationalen Datenbank, einem Warehouse oder einem per SQL zugänglichen Lakehouse befinden.

Geeignete Einsatzfelder sind:

- Joins, Filter und Aggregationen;
- dimensionale Modelle und Reporting Views;
- deterministische Geschäftsregeln;
- Datenbereinigung und Standardisierung;
- materialisierte Tabellen für reproduzierbare Performance;
- inkrementelle Verarbeitung, sofern die Plattform passende Muster unterstützt;
- Veröffentlichung stabiler Consumer-Verträge.

Vorteile:

- verbreitetes Skillset;
- Ausführung in der Nähe der Daten;
- transparente Abfragelogik;
- geringe zusätzliche Toolkosten;
- einfache Integration mit Schedulern und BI-Consumern.

Risiken:

- unkontrollierte Ketten von Views;
- dupliziertes SQL in mehreren Jobs;
- schwache Deployment-Disziplin;
- verborgene Abhängigkeiten, wenn Objektreferenzen nicht katalogisiert werden;
- plattformspezifische Syntax, die spätere Migrationen erschwert.

Eine SQL-Implementierung ist nicht automatisch primitiv. Eine kleine, getestete Menge aus Views und geplanten Tabellen kann wartbarer sein als eine anspruchsvolle Toolchain, die das Team nicht zuverlässig betreiben kann.

### Stored Procedures und Funktionen

Stored Procedures sind sinnvoll, wenn die Transformation kontrollierte prozedurale Ausführung, Transaktionsbehandlung, Verzweigungen, wiederverwendbare Datenbankroutinen oder eine explizite Schrittfolge benötigt.

Geeignete Einsatzfelder sind:

- komplexes Merge-Verhalten;
- Batch-Steuerungslogik;
- parametrisierte Verarbeitung;
- operative Fehlerbehandlung;
- datenbanknative Performanceoptimierung;
- kontrollierte Veröffentlichungsprozeduren.

Das Hauptrisiko ist fehlende Transparenz. Eine große Stored Procedure kann zu einer monolithischen Anwendung innerhalb der Datenbank werden. Werden Prozeduren eingesetzt, müssen sie weiterhin versioniert, geprüft, getestet, dokumentiert und mit sichtbaren Laufmetadaten verbunden werden.

### Native Plattformfunktionen

Moderne Plattformen stellen integrierte Transformations- und Orchestrierungsfunktionen bereit. Beispiele sind Warehouse SQL, geplante Jobs, Tasks, deklarative Tabellen, Pipelines, materialisierte Views, native Quality Expectations und Plattformmonitoring.

Native Funktionen sind häufig die beste Option, wenn sie:

- die Anforderung bereits erfüllen;
- vom Betriebsteam verstanden werden;
- in Sicherheit, Zeitplanung und Monitoring integriert sind;
- externe Infrastruktur reduzieren;
- die erforderliche Performance und Zuverlässigkeit liefern.

Native-first bedeutet nicht native-only. Eine native Funktion sollte nur so lange die führende Implementierung bleiben, wie sie die fachlichen und Governance-Anforderungen weiterhin erfüllt.

### Dataflows und klassische ETL-Werkzeuge

Low-Code-Dataflows und etablierte ETL-Werkzeuge bleiben gültige Transformationsmechanismen.

Sie sind besonders geeignet für:

- breite Connector-Abdeckung;
- visuelle Source-to-Target-Mappings;
- standardisierte operative Integration;
- Teams mit starken ETL- oder Power-Query-Kenntnissen;
- kontrollierte Datei- und API-Ingestion;
- Workflows, die Datenbewegung, Transformation und Orchestrierung verbinden.

Die Architekturanforderung ist dieselbe wie bei Code: Mappings benötigen Ownership, Versionierung, Reviews, Tests, Deployment-Disziplin und sichtbare Abhängigkeiten.

Eine visuelle Oberfläche dokumentiert sich nicht automatisch selbst. Komplexe Dataflows können ohne Konventionen und klare Komponentengrenzen genauso schwer verständlich werden wie komplexer Code.

### Notebooks, Python und Spark

Notebooks sind wertvoll für Transformationen, die Python, Spark, erweiterte Bibliotheken, komplexe Datenstrukturen, Machine-Learning-Vorbereitung oder explorative Entwicklung benötigen.

Sie eignen sich für:

- umfangreiche verteilte Transformationen;
- Verarbeitung semistrukturierter Daten;
- Feature Engineering;
- fortgeschrittene Algorithmen;
- wiederverwendbare Python-Bibliotheken;
- Batch- und Streaming-Workloads;
- Untersuchungen, bevor eine stabile produktive Implementierung ausgewählt wird.

Ein Notebook wird riskant, wenn seine interaktive Form mit einem Betriebsmodell verwechselt wird. Produktive Notebooks benötigen Parameter, modularen Code, Dependency Management, automatisierte Tests, kontrollierte Umgebungen, Zeitplanung, Monitoring und einen klaren Owner.

### dbt

Ein dbt-Modell ist primär ein Transformationsmodell, das in der Zieldatenplattform ausgeführt wird. dbt ergänzt SQL und abhängig von der Plattformunterstützung Python um ein strukturiertes Projektmodell: Abhängigkeiten, Umgebungen, Materialisierungen, Tests, Dokumentation, Metadaten und Deployment-Workflows.

dbt kann deutlichen Mehrwert schaffen, wenn das Team Folgendes benötigt:

- viele voneinander abhängige SQL-Modelle;
- einheitliche Projektkonventionen;
- Git-basierte Zusammenarbeit und Reviews;
- automatisierte Datentests;
- generierte Dokumentation und Lineage-Metadaten;
- reproduzierbare Entwicklungs- und Produktionsumgebungen;
- modularen Wiedergebrauch über Referenzen und Macros;
- CI/CD für Analytics Engineering.

dbt liefert weniger Mehrwert, wenn:

- nur wenige stabile SQL Views existieren;
- das Team keine Kapazität für eine weitere Runtime und ein weiteres Repository besitzt;
- die native Plattformentwicklung bereits ausreichende Reviews, Tests und Deployments ermöglicht;
- der größte Teil der Transformationen Python, Spark, Streaming oder operative Integration statt SQL-zentrierter Modellierung ist;
- dbt eine bestehende führende Implementierung duplizieren würde.

Die richtige Frage lautet nicht „Nutzen wir dbt?“, sondern „Welche konkreten Probleme würde dbt besser lösen als die aktuelle Methode?“

## Die einfachste sinnvolle Umsetzung

Eine governte Transformationsschicht benötigt weder eine neue Cloud-Plattform noch ein neues Transformationsprodukt.

Eine minimale Umsetzung kann Folgendes verwenden:

```flow linear vertical
Vorhandener Quellextrakt oder Staging-Tabelle
Vorhandene SQL-Datenbank oder vorhandenes Warehouse
Eine kontrollierte Transformations-View oder ein Tabellenaufbau
Eine geplante Ausführung
Eine persistente Tabelle für Qualitätsergebnisse
Ein Veröffentlichungsstatus
Qlik / Power BI / Excel konsumieren das veröffentlichte Ergebnis
```

Für ein Customer-Datenprodukt kann die erste Version folgende Objekte enthalten:

```text
staging.customer_source
core.customer
quality.customer_rule_result
control.customer_publication
```

Die Transformation kann zunächst eine View sein:

```sql
create view core.customer as
select
    trim(customer_id) as customer_id,
    upper(trim(country_code)) as country_code,
    cast(updated_at as timestamp) as updated_at,
    case when active_flag = 'Y' then 1 else 0 end as is_active,
    source_system,
    ingestion_timestamp
from staging.customer_source;
```

Ein separater Qualitätsschritt bewertet die Veröffentlichungsregel:

```sql
select
    customer_id,
    'ACTIVE_CUSTOMER_REQUIRED_FIELDS' as rule_id,
    case
        when customer_id is null then 'MISSING_CUSTOMER_ID'
        when country_code is null then 'MISSING_COUNTRY'
        when updated_at is null then 'MISSING_UPDATED_AT'
        when updated_at < current_timestamp - interval '30' day then 'STALE_UPDATED_AT'
        else 'PASSED'
    end as rule_result
from core.customer
where is_active = 1;
```

Die konkrete Datumsarithmetik und die Datentypen unterscheiden sich je nach SQL-Dialekt. Das Architekturmuster bleibt gleich:

1. Quelle in eine kontrollierte Kundenstruktur transformieren;
2. Geschäftsregel explizit auswerten;
3. Fehler und Ausführungsmetadaten persistieren;
4. nur entsprechend einer vereinbarten Qualitätsrichtlinie veröffentlichen;
5. Ergebnis über einen stabilen Vertrag für Consumer bereitstellen.

Diese Implementierung kann jahrelang ausreichen. Weitere Tools sollten nur eingeführt werden, wenn Skalierung, Zusammenarbeit, Tests, Lineage, Sprache oder Betriebsanforderungen sie rechtfertigen.

## Nach Workload und Team entscheiden

Keine Transformationsmethode ist für jeden Workload die beste.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start8-img2-de.png"
        alt="Entscheidungsmatrix zum Vergleich von SQL, nativen Plattformfunktionen, Notebooks, Dataflows, dbt und Stored Procedures anhand von Komplexität, Fähigkeiten, Governance, Wiederverwendung, Performance und Betriebsaufwand"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die richtige Wahl hängt vom Workload, den im Team vorhandenen Sprachen, den Anforderungen an Zusammenarbeit, der erwarteten Skalierung, dem Testbedarf und dem dauerhaft tragbaren Betriebsaufwand ab.
    </figcaption>
</figure>

Die Entscheidung sollte mindestens acht Dimensionen berücksichtigen.

### 1. SQL-Anteil

Sind die meisten Transformationen relational, mengenorientiert und werden bereits in einem Warehouse ausgeführt, sind SQL Views, geplante Tabellen, native SQL-Jobs oder dbt natürliche Kandidaten.

Ein hoher SQL-Anteil rechtfertigt dbt nicht automatisch. Entscheidend sind die Anzahl der Modelle, die Komplexität der Abhängigkeiten, der Entwicklungsworkflow und die Governance-Anforderungen.

### 2. Python- oder Spark-Anteil

Wenn Transformationen stark von Python-Bibliotheken, verteilter Verarbeitung, unstrukturierten Daten oder fortgeschrittenen Algorithmen abhängen, können Notebooks oder native Python-/Spark-Pipelines die klarere führende Implementierung sein.

dbt kann parallel dazu SQL-zentrierte nachgelagerte Modelle übernehmen. Es sollte nicht gezwungen werden, Workloads zu verantworten, für die eine andere Engine und ein anderes Entwicklungsmodell besser geeignet sind.

### 3. Teamgröße und Ownership-Modell

Ein kleines Team mit zehn stabilen Transformationen kann mit SQL und einem vorhandenen Scheduler gut bedient sein.

Ein größeres Team mit Hunderten Modellen, mehreren Domänen und häufigen parallelen Änderungen benötigt stärkere Konventionen, isolierte Entwicklungsumgebungen, Code Reviews, automatisierte Tests und sichtbare Abhängigkeiten. dbt oder ein ausgereiftes natives Entwicklungsframework kann dann wesentlichen Mehrwert bieten.

### 4. Anforderungen an Git, Reviews und CI/CD

Transformationscode sollte unabhängig von der Technologie versioniert werden.

Die eigentliche Frage lautet, wie umfangreich der Entwicklungsworkflow sein muss:

- individuelle Versionshistorie;
- Pull Requests und Peer Reviews;
- automatisierte Kompilierung und Tests;
- Promotion zwischen Umgebungen;
- Rollback;
- Deployment-Nachweise;
- Trennung von Entwicklungs- und Produktionszugängen.

Unterstützt die native Plattform diese Anforderungen ausreichend, kann eine weitere Schicht unnötig sein. Tut sie es nicht, kann dbt oder ein externer Engineering-Workflow eine wichtige Lücke schließen.

### 5. Low-Code-Anteil

Dataflows sind geeignet, wenn Business Technologists oder ETL-Spezialisten eine visuelle Entwicklungsoberfläche benötigen und die Transformationen in dieser Form verständlich bleiben.

Low-Code darf nicht Low-Governance bedeuten. Das Unternehmen benötigt weiterhin Namenskonventionen, wiederverwendbare Komponenten, Reviews, Deployments, Laufhistorie und Ownership.

### 6. Batch, Near-Real-Time und Streaming

Ein täglicher Dimensionsaufbau hat andere Anforderungen als ein kontinuierlicher Stream.

- Batch-Workloads können Views, geplantes SQL, Prozeduren, dbt oder ETL-Jobs verwenden;
- Near-Real-Time-Workloads können inkrementelle Jobs, Plattform-Tasks oder deklarative Tabellen einsetzen;
- Streaming-Workloads benötigen in der Regel natives Streaming oder Spark-basierte Verarbeitung;
- ereignisgetriebene operative Logik kann Streams, Trigger, Queues oder spezialisierte Services erfordern.

Ein batchorientiertes Transformationsframework sollte nicht zur einzigen Antwort auf eine Streaming-Anforderung gemacht werden.

### 7. Anforderungen an Tests und Dokumentation

Alle Ansätze können getestet werden. Aufwand und integrierte Unterstützung unterscheiden sich.

Eine kleine SQL-Implementierung kann eigene Assertions und eine Ergebnistabelle verwenden. dbt stellt ein standardisiertes Modell für Datentests und Dokumentation bereit. Native Plattformen können Quality Expectations, Monitoring und Lineage liefern. ETL-Werkzeuge können Validierungskomponenten und Laufmetadaten anbieten.

Der korrekte Vergleich lautet nicht, ob Tests theoretisch möglich sind. Entscheidend ist, ob das Team den erforderlichen Nachweis konsequent implementieren und betreiben kann.

### 8. Betriebsaufwand und Kosten

Jedes zusätzliche Framework erzeugt Betriebsverantwortung:

```text
Runtime
Credentials
Repositories
Paket- und Adapterversionen
Deployment-Pipelines
Jobplanung
Monitoring
Incident Handling
Training
Hersteller- oder Community-Support
```

Ein Tool, das die Entwicklung verbessert, aber einen nicht unterstützten Betriebsaufwand erzeugt, ist keine nachhaltige Architektur.

Ein kompakter Vergleich ist:

| Option | Besonders geeignet | Wichtigstes Risiko |
| --- | --- | --- |
| SQL Views und Skripte | Relationale Transformationen, kleine bis mittlere Modellmenge, vorhandenes Warehouse | View-Wildwuchs, duplizierte Skripte, schwache Deployment-Disziplin |
| Materialisierte Tabellen und Prozeduren | Performance, kontrollierte Batches, prozedurale Logik | Monolithen, intransparente Abhängigkeiten, Datenbankkopplung |
| Native Plattformfunktionen | Integrierte Zeitplanung, Monitoring, Sicherheit und Skalierung | Plattformkopplung und funktionsspezifische Grenzen |
| Dataflows und ETL-Werkzeuge | Connector-intensive Integration, Low-Code-Teams, operative Mappings | Visuelle Komplexität, schwache Wiederverwendung, proprietäres Deployment-Modell |
| Notebooks und Python/Spark | Komplexe Algorithmen, große Datenmengen, semistrukturierte Daten, Streaming | Interaktiver Code wird ohne Production Engineering produktiv gesetzt |
| dbt | SQL-zentrierte Modellnetze, Git-Reviews, Tests, Dokumentation und Teamzusammenarbeit | Zusätzliche Runtime, Konventionen und mögliche Duplizierung nativer Fähigkeiten |

## Eine Transformation, ein Owner

Das schädlichste Transformationsproblem ist meist nicht das falsche Tool, sondern duplizierte Verantwortung.

Ein verbreitetes Anti-Pattern sieht so aus:

```text
Dataflow         Ersetzt fehlendes Land durch „DE“
Notebook         Ersetzt fehlendes Land durch den Standard des Quellsystems
Stored Procedure Lehnt den Datensatz ab
Qlik-Skript      Mappt fehlendes Land auf „Unknown“
dbt-Modell       Nutzt das Rechnungsland als Fallback
```

Alle fünf Implementierungen können technisch sinnvoll sein. Gemeinsam erzeugen sie fünf verschiedene Customer-Definitionen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start8-img3-de.png"
        alt="Governtes Transformationsmuster, bei dem Quellen eine verantwortete führende Implementierung versorgen, die getestete Assets für Qlik, Power BI, Excel und weitere Consumer veröffentlicht"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Eine führende Transformation erfordert kein universelles Tool. Sie erfordert eine autoritative Implementierung, einen benannten Owner, dokumentierte Abhängigkeiten, persistente Tests und klar definierte Consumer.
    </figcaption>
</figure>

Eine führende Implementierung sollte Folgendes definieren:

| Kontrolle | Erforderlicher Inhalt |
| --- | --- |
| Fachlicher Owner | Verantwortliche Person für Bedeutung und Akzeptanzkriterien |
| Technischer Owner | Team für Implementierung und Betrieb |
| Regel-ID | Stabiler Name wie `ACTIVE_CUSTOMER_REQUIRED_FIELDS` |
| Input-Vertrag | Erforderliche Quellfelder, Granularität und Aktualität |
| Output-Vertrag | Veröffentlichte Felder, Schlüssel, Granularität und Statusverhalten |
| Implementierungsort | View, Prozedur, Notebook, Dataflow, dbt-Modell oder native Pipeline |
| Tests | Erwartete Assertions, Grenzwerte und Fehlerverhalten |
| Qualitätsnachweis | Persistente fehlerhafte Datensätze und Ausführungsmetadaten |
| Abhängigkeiten | Vorgelagerte Quellen und nachgelagerte Consumer |
| Änderungsprozess | Review, Versionierung, Kommunikation und Deprecation |
| Serviceerwartung | Zeitplan, Aktualität, Monitoring und Wiederherstellung |

„Ein Owner“ bedeutet nicht, dass nur eine Person die Transformation bearbeiten darf. Es bedeutet, dass es ein eindeutiges Verantwortungsmodell und eine Implementierung gibt, die für die gemeinsame Regel autoritativ ist.

### Migration aus duplizierten Implementierungen

Ein sicheres Konsolidierungsmuster ist:

```flow linear vertical
Bestehende Regelvarianten inventarisieren
Fachliche Ergebnisse und Grenzfälle vergleichen
Autoritative Definition vereinbaren
Führende Implementierung und Owner auswählen
Persistente Tests aufbauen
Altes und neues Ergebnis parallel ausführen
Consumer einzeln migrieren
Duplizierte Logik entfernen oder deaktivieren
Governtes Ergebnis überwachen
```

Alte Qlik-Mappings, Notebook-Zellen oder Excel-Formeln sollten nach Abnahme des governten Ersatzes nicht „zur Sicherheit“ bestehen bleiben. Ruhende Duplikate werden beim nächsten Incident oder bei einer dringenden Änderung häufig wieder aktiviert.

## Native zuerst oder dbt?

Native-first und dbt-gestützte Transformation sind beide gültige Strategien.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start8-img4-de.png"
        alt="Entscheidungsleitfaden zum Vergleich nativer Plattformfunktionen mit dbt und zur Frage, wann dbt durch modulares SQL, Tests, Dokumentation, Lineage und versionierte Zusammenarbeit klaren Mehrwert schafft"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Beginne mit den Fähigkeiten, die das Problem bereits lösen. Ergänze dbt, wenn modulare SQL-Entwicklung, Abhängigkeitsmanagement, Tests, Dokumentation und kollaborative Bereitstellung genügend Mehrwert für das zusätzliche Betriebsmodell schaffen.
    </figcaption>
</figure>

### Native-first ist eine starke Wahl, wenn

- die Plattform bereits geeignetes SQL, Tabellen, Pipelines und Zeitplanung bereitstellt;
- die Transformationsmenge klein oder mittelkomplex ist;
- das Team das native Betriebsmodell versteht;
- Sicherheit und Monitoring bereits integriert sind;
- die Deployment-Anforderungen ausreichend abgedeckt sind;
- die meiste Logik plattformspezifisch oder eng mit nativem Streaming und Processing verbunden ist;
- eine weitere Runtime Qualität oder Liefergeschwindigkeit nicht wesentlich verbessern würde.

Beispiele sind:

- ein bestehendes SQL-Server-Warehouse mit Views, Prozeduren und SQL Agent oder einem anderen Scheduler;
- Fabric Warehouse SQL in Verbindung mit Pipelines, Dataflow Gen2 oder Notebooks, wo erforderlich;
- Snowflake SQL mit Dynamic Tables oder Streams und Tasks für geeignete Workloads;
- Databricks SQL oder Python mit nativen Jobs und Lakeflow Pipelines;
- eine klassische ETL-Plattform, die vom Team bereits zuverlässig betrieben wird.

### dbt schafft klaren Mehrwert, wenn

- die Transformationsschicht überwiegend SQL-zentriert ist;
- die Anzahl der Modelle und Abhängigkeiten wächst;
- mehrere Engineers dasselbe Projekt ändern;
- Pull Requests und automatisierte Prüfungen erforderlich sind;
- Tests und Dokumentation gemeinsamen Konventionen folgen sollen;
- Entwicklungs-, Test- und Produktionsumgebungen reproduzierbar sein müssen;
- Lineage und Modellmetadaten über einen großen Transformationsgraphen benötigt werden;
- modulare Referenzen und Macros wiederholtes SQL reduzieren;
- das Team dbt als Produkt betreiben kann und es nicht nur installiert.

### dbt entfernt keine Plattformverantwortung

Die Zieldatenplattform führt die Berechnung weiterhin aus. Das Team muss weiterhin Folgendes verstehen:

- Warehouse-Größe und Kosten;
- SQL-Dialekt der Plattform;
- physisches Design und Performance;
- Zugriffskontrolle;
- Zeitplanung und Jobzuverlässigkeit;
- Quell-Ingestion;
- Streaming und operative Integration;
- Incident Management;
- Veröffentlichung von Datenprodukten.

Auch Portabilität muss präzise betrachtet werden. dbt kann Kopplung reduzieren, weil Projektstruktur und Abhängigkeitslogik von der DDL-Orchestrierung getrennt werden. SQL-Funktionen, Datentypen, inkrementelle Strategien und Performanceeinstellungen unterscheiden sich jedoch weiterhin zwischen Plattformen. Eine Migration wird meist einfacher, aber nicht automatisch.

### Das Anti-Pattern der halben Migration vermeiden

Ein häufiger Fehler ist die Einführung von dbt, ohne festzulegen, was dbt verantwortet:

```text
Einige Modelle liegen in dbt
Andere werden weiterhin durch Prozeduren erzeugt
Ein Notebook überschreibt ein dbt-Ziel
Ein Dataflow ergänzt eine weitere Korrektur
Qlik wiederholt die abschließende Geschäftsregel
Keine Komponente ist eindeutig autoritativ
```

Eine dbt-Einführung sollte explizite Grenzen definieren:

```text
dbt verantwortet              SQL-zentrierte kuratierte Modelle und Datenproduktmodelle
dbt verantwortet nicht        Quell-Ingestion, Streaming oder Python-intensive Verarbeitung, sofern nicht explizit vorgesehen
native Plattform verantwortet Compute, Storage, Sicherheit, Scheduling-Integration und Plattformbetrieb
Consumer-Tools verantworten   ausschließlich Tool-spezifische Semantik und Darstellung
```

Die Grenze kann anders gewählt werden. Sie darf nicht implizit bleiben.

## Konkretes Beispiel: Jeder aktive Kunde muss veröffentlichungsfähig sein

Angenommen, das Unternehmen definiert folgende Regel:

> **Jeder aktive Kunde benötigt eine Customer ID, ein Land und einen Änderungszeitpunkt innerhalb des vereinbarten Aktualitätsfensters.**

Für das Beispiel beträgt das Aktualitätsfenster 30 Tage. In einem realen Datenprodukt sollte der Grenzwert konfigurierbar und vom Customer Data Owner freigegeben sein.

### Fachlicher Vertrag

| Element | Definition |
| --- | --- |
| Granularität | Eine Zeile je Kundenquellversion |
| Aktiv-Bedingung | `active_flag = 'Y'` |
| Erforderlicher Identifikator | `customer_id` ist nicht null und nicht leer |
| Erforderliches Land | `country_code` ist nicht null und folgt der freigegebenen Codeliste |
| Aktualität | `updated_at` ist nicht null und nicht älter als 30 Tage |
| Regel-ID | `ACTIVE_CUSTOMER_REQUIRED_FIELDS` |
| Fehlerbehandlung | Datensatz, Grund und Ausführungsmetadaten persistieren |
| Veröffentlichungsverhalten | Je nach vereinbarter Schwere warnen, quarantänisieren oder blockieren |
| Owner | Customer Data Owner |
| Consumer | Customer-Datenprodukt, Sales-Datenprodukt, Qlik, Power BI, Excel und APIs |

Die Implementierungstechnologie darf variieren. Die Bedeutung des Ergebnisses muss identisch bleiben.

### Option 1: SQL View und geplante Qualitätstabelle

Die Transformations-View standardisiert die Felder:

```sql
create view curated.customer as
select
    nullif(trim(customer_id), '') as customer_id,
    upper(nullif(trim(country_code), '')) as country_code,
    cast(updated_at as timestamp) as updated_at,
    case when active_flag = 'Y' then 1 else 0 end as is_active,
    source_system,
    source_record_id,
    ingestion_timestamp
from staging.customer_source;
```

Ein geplantes Statement persistiert Fehler:

```sql
insert into quality.customer_rule_result (
    execution_id,
    rule_id,
    source_system,
    source_record_id,
    customer_id,
    failure_reason,
    evaluated_at
)
select
    :execution_id,
    'ACTIVE_CUSTOMER_REQUIRED_FIELDS',
    source_system,
    source_record_id,
    customer_id,
    case
        when customer_id is null then 'MISSING_CUSTOMER_ID'
        when country_code is null then 'MISSING_COUNTRY'
        when updated_at is null then 'MISSING_UPDATED_AT'
        when updated_at < current_timestamp - interval '30' day then 'STALE_UPDATED_AT'
    end,
    current_timestamp
from curated.customer
where is_active = 1
  and (
      customer_id is null
      or country_code is null
      or updated_at is null
      or updated_at < current_timestamp - interval '30' day
  );
```

Dies ist eine gültige Implementierung, wenn vorhandene Datenbank, Scheduler und Monitoring ausreichen.

### Option 2: Microsoft Fabric

Mehrere Fabric-Implementierungen können gültig sein:

| Workload | Mögliche führende Implementierung |
| --- | --- |
| Relationale Warehouse-Transformation | Fabric-Warehouse-T-SQL-View oder geplanter Tabellenaufbau |
| Low-Code-Aufbereitung und connectorintensive Ingestion | Dataflow Gen2 |
| Python, Spark oder komplexe Dateiverarbeitung | Notebook |
| Mehrstufige Orchestrierung | Fabric-Pipeline, die abhängig vom Bedarf SQL, Stored Procedures, Dataflows oder Notebooks aufruft |

Ein minimales Warehouse-SQL-Muster kann dieselbe View- und Qualitätstabellenlogik wie das generische SQL-Beispiel verwenden, angepasst an die unterstützte T-SQL-Syntax.

Eine Dataflow-Gen2-Implementierung kann `customer_id`, `country_code` und `updated_at` standardisieren, gültige Datensätze an das kuratierte Ziel weiterleiten und abgewiesene Datensätze in einem Qualitätsziel persistieren. Ein Notebook ist geeignet, wenn die Regel Teil einer größeren Spark-Transformation ist oder Python-Bibliotheken benötigt.

Die Architekturkontrolle ist nicht der Typ des Fabric-Items. Entscheidend ist, dass ein Item als autoritativ festgelegt wird, Code oder Definition versioniert sind, das Qualitätsergebnis persistiert wird und Qlik oder Power BI die Regel nicht wiederholen.

### Option 3: Snowflake

Snowflake kann die Regel mit gewöhnlichen SQL Views und Tabellen, einer Dynamic Table für deklaratives Refresh-Verhalten oder Streams und Tasks für explizites CDC und prozedurale Kontrolle implementieren.

Eine konzeptionelle Dynamic-Table-Implementierung ist:

```sql
create or replace dynamic table curated.active_customer
    target_lag = '30 minutes'
    warehouse = transform_wh
as
select
    nullif(trim(customer_id), '') as customer_id,
    upper(nullif(trim(country_code), '')) as country_code,
    updated_at,
    source_system,
    source_record_id,
    case
        when customer_id is null then 'MISSING_CUSTOMER_ID'
        when country_code is null then 'MISSING_COUNTRY'
        when updated_at is null then 'MISSING_UPDATED_AT'
        when updated_at < dateadd(day, -30, current_timestamp()) then 'STALE_UPDATED_AT'
        else 'PASSED'
    end as quality_status
from staging.customer_source
where active_flag = 'Y';
```

Ein separater Qualitätsschritt kann fehlerhafte Datensätze mit den für das Governance-Modell erforderlichen Ausführungs- und Regelmetadaten persistieren.

Dynamic Tables sind für deklarative Tabellenpipelines attraktiv. Streams und Tasks bleiben sinnvoll, wenn der Prozess explizite Änderungsverfolgung, getriggerte Verarbeitung, Task-Graphen oder eigene prozedurale Schritte benötigt. Die Auswahl sollte dem Workload folgen und kein plattformweites Mandat sein.

### Option 4: Databricks

Databricks kann die Regel in SQL, Python oder einer deklarativen Lakeflow-Pipeline implementieren.

Eine SQL-Implementierung kann eine kuratierte Tabelle oder materialisierte View erzeugen. Eine PySpark-Implementierung ist sinnvoll, wenn die Customer-Transformation Teil einer größeren verteilten Pipeline ist:

```python
from pyspark.sql import functions as F

customer = (
    spark.table("staging.customer_source")
    .select(
        F.when(F.trim("customer_id") == "", None)
         .otherwise(F.trim("customer_id"))
         .alias("customer_id"),
        F.upper(F.when(F.trim("country_code") == "", None)
         .otherwise(F.trim("country_code")))
         .alias("country_code"),
        F.col("updated_at").cast("timestamp").alias("updated_at"),
        (F.col("active_flag") == "Y").alias("is_active"),
        "source_system",
        "source_record_id"
    )
)
```

Der Qualitätsstatus kann anschließend in SQL oder Python abgeleitet und in eine Delta-Tabelle wie `quality.customer_rule_result` geschrieben werden.

Deklarative Lakeflow-Pipelines sind geeignet, wenn das Unternehmen ein verwaltetes deklaratives Framework für Batch- oder Streaming-Tabellen und Flows in SQL oder Python benötigt. Ein normales Notebook oder ein geplanter Job kann für einen kleinen stabilen Workload weiterhin einfacher sein.

### Option 5: dbt-Modell und Tests

Eine dbt-Implementierung kann die Transformation in einem SQL-Modell ablegen:

```sql
-- models/curated/customer.sql

select
    nullif(trim(customer_id), '') as customer_id,
    upper(nullif(trim(country_code), '')) as country_code,
    cast(updated_at as timestamp) as updated_at,
    case when active_flag = 'Y' then true else false end as is_active,
    source_system,
    source_record_id,
    ingestion_timestamp
from {{ source('staging', 'customer_source') }}
```

Standardtests können strukturelle Erwartungen validieren:

```yaml
version: 2

models:
  - name: customer
    columns:
      - name: customer_id
        data_tests:
          - not_null:
              config:
                where: "is_active = true"
      - name: country_code
        data_tests:
          - not_null:
              config:
                where: "is_active = true"
      - name: updated_at
        data_tests:
          - not_null:
              config:
                where: "is_active = true"
```

Ein einzelner Datentest kann die vollständige Aktualitätsregel ausdrücken:

```sql
-- tests/active_customer_required_fields.sql

select *
from {{ ref('customer') }}
where is_active = true
  and (
      customer_id is null
      or country_code is null
      or updated_at is null
      or updated_at < current_timestamp - interval '30' day
  )
```

Da ein fehlgeschlagener Test allein nicht immer als operativer Nachweis ausreicht, kann ein separates Audit-Modell fehlerhafte Datensätze im governten Qualitätsschema persistieren:

```sql
-- models/quality/customer_rule_result.sql

select
    'ACTIVE_CUSTOMER_REQUIRED_FIELDS' as rule_id,
    source_system,
    source_record_id,
    customer_id,
    current_timestamp as evaluated_at
from {{ ref('customer') }}
where is_active = true
  and (
      customer_id is null
      or country_code is null
      or updated_at is null
      or updated_at < current_timestamp - interval '30' day
  )
```

Das genaue SQL bleibt adapter- und plattformabhängig. dbt ergänzt Abhängigkeitsmanagement, Testkonventionen, Dokumentation und Deployment-Workflow. Die fachliche Definition ändert sich nicht.

### Option 6: Dataflow oder klassisches ETL-Mapping

Dieselbe Regel kann visuell ausgedrückt werden:

```flow linear vertical
Customer-Quelle lesen
Customer ID trimmen
Ländercode normalisieren
Änderungszeitpunkt typisieren
Aktive Kunden filtern
Fehlergrund ableiten
Gültige Datensätze in curated customer schreiben
Fehlerhafte Datensätze in quality result schreiben
Ausführungsstatus in control table schreiben
```

Dies ist vollständig gültig, wenn das Mapping versioniert, geprüft, getestet, überwacht und verantwortet wird. Auf die visuelle Implementierung darf keine weitere undokumentierte Korrektur in einem Notebook oder Qlik-Skript folgen.

## Ein Ergebnis über alle Implementierungen

Unabhängig von der Technologie sollte der veröffentlichte Vertrag vergleichbar sein:

```text
customer_id
country_code
updated_at
is_active
quality_status
quality_rule_id
source_system
source_record_id
publication_timestamp
```

Für denselben Eingabedatensatz muss jede gültige Implementierung dasselbe Ergebnis erzeugen.

Beispiel:

```text
Eingabe
customer_id       = C-10042
country_code      = null
updated_at        = 2026-07-10 09:30:00
active_flag       = Y

Erwartetes Ergebnis
quality_status    = FAILED
quality_rule_id   = ACTIVE_CUSTOMER_REQUIRED_FIELDS
failure_reason    = MISSING_COUNTRY
publication       = entsprechend der vereinbarten Schweregradrichtlinie
```

Der parallele Vergleich der Ergebnisse ist die zuverlässigste Methode beim Wechsel der Implementierungstechnologie.

## Plattformmuster ohne verpflichtenden Stack

Die vorhandene Plattform sollte die Implementierung beeinflussen, aber nicht die Architektur neu definieren.

| Bestehende Umgebung | Pragmatischer Ausgangspunkt | Mögliche Erweiterung |
| --- | --- | --- |
| Klassisches SQL-Warehouse | Views, Prozeduren, geplante Tabellenaufbauten, Qualitätstabellen | Git-Deployment, stärkere Orchestrierung, dbt bei wachsender Modellzusammenarbeit |
| Microsoft Fabric | Warehouse SQL, Dataflow Gen2, Pipelines oder Notebooks entsprechend dem Workload | dbt für eine größere SQL-zentrierte Modelllandschaft, wenn es klaren Workflow-Mehrwert schafft |
| Snowflake | SQL Views/Tabellen, Dynamic Tables, Streams und Tasks | dbt für modulare SQL-Modellierung, Tests und Zusammenarbeit |
| Databricks | SQL, Notebooks, Jobs oder deklarative Lakeflow-Pipelines | dbt für SQL-zentrierte kuratierte Modelle und Marts neben Python-/Spark-Verarbeitung |
| Vorhandene ETL-Suite | Kontrollierte Mappings und Jobs | Codebasierte Transformationen, wenn Tests, Wiederverwendung oder Skalierung sie rechtfertigen |
| Qlik-zentriertes Brownfield | Zentrale SQL- oder governte QVD-Transformationsschicht als vorläufige führende Implementierung | Gemeinsame Logik schrittweise in ein plattformneutrales Datenprodukt verschieben und Qlik-Apps schlank halten |
| Kleine On-Premises-Umgebung | Vorhandene Datenbank, Scheduler und Dateischicht | Komponenten erst ergänzen, wenn Betriebsanforderungen die vorhandenen Fähigkeiten übersteigen |

Keine Zeile dieser Tabelle beschreibt einen verpflichtenden Ziel-Stack.

## Qlik, Power BI und Excel bleiben Consumer

Dieser Part behandelt Transformations-Ownership und verbietet keine Transformationsfunktionen innerhalb von BI-Tools.

Eine schlanke Qlik-App kann weiterhin:

- das veröffentlichte Customer-Datenprodukt laden;
- erforderliche Assoziationen erzeugen;
- Section Access mit governten Berechtigungsdaten implementieren;
- Set Analysis für selektionsabhängige Vergleiche verwenden;
- Felder für das assoziative Modell optimieren.

Ein semantisches Power-BI-Modell kann weiterhin:

- Beziehungen und Hierarchien definieren;
- DAX-Measures ergänzen, die vom Filterkontext abhängen;
- Row-Level Security anwenden;
- berichtsspezifische Zeitintelligenz bereitstellen.

Eine Excel-Arbeitsmappe kann weiterhin:

- eine kontrollierte Ausgabe für die Nutzbarkeit formen;
- PivotTables, Formatierung und Kommentare bereitstellen;
- klar getrennte Planungsannahmen verwalten.

Keiner dieser Consumer sollte unabhängig entscheiden, dass ein aktiver Kunde ohne Land zulässig ist oder ein anderes Aktualitätsfenster verwenden darf.

## Typische Anti-Patterns

### Tool-first-Architektur

Das Team wählt das Transformationswerkzeug, bevor es Workload, Ownership, Qualität und Betriebsanforderungen versteht.

### Dieselbe Regel in mehreren Tools

Ein Dataflow, Notebook, eine Prozedur, ein dbt-Modell und ein Qlik-Skript implementieren jeweils eine Variante derselben Customer- oder Umsatzregel.

### Notebook als undokumentierte Produktionsanwendung

Ein exploratives Notebook wird unverändert geplant, hängt von verborgenem Zustand ab und besitzt weder Tests, Paketkontrolle noch Owner.

### Endlose View-Ketten

Views rufen Views auf, die weitere Views aufrufen, bis Lineage und Performance nicht mehr nachvollziehbar sind.

### Monolithische Stored Procedure

Eine Prozedur übernimmt Ingestion, Bereinigung, Historisierung, Qualität, Veröffentlichung und Consumer-spezifische Ausgabe ohne modulare Grenzen.

### Low-Code ohne Engineering-Disziplin

Ein visueller Prozess gilt als selbstdokumentierend und wird ohne Review oder Deployment-Kontrolle direkt in Produktion geändert.

### dbt als Richtlinie statt als Lösung

Jede Transformation wird nach dbt migriert, obwohl native Funktionen, Python oder vorhandenes SQL einfacher und besser unterstützt wären.

### dbt plus native Duplizierung

Das dbt-Modell existiert, aber eine native Pipeline erzeugt dieselbe Business-Tabelle erneut, weil Ownership nie geklärt wurde.

### Tests ausschließlich in Dashboards

Qualität wird als rote Kennzahl in Power BI oder Qlik beobachtet, aber fehlerhafte Datensätze und Regelausführungen werden nicht zentral persistiert.

### Stilles Verwerfen

Ungültige Datensätze werden ohne Fehlergrund, Regel-ID, Owner oder Wiederherstellungsprozess ausgefiltert.

### „Ein Owner“ nur auf dem Papier

Eine Rolle ist dokumentiert, aber kein Team verantwortet Deployment, Monitoring, Incidents und Änderungskommunikation.

### Logik läuft in Consumer aus

Eine temporäre Korrektur in Qlik, Power Query oder Excel wird zur dauerhaften Implementierung gemeinsamer fachlicher Bedeutung.

## Entscheidungshilfe

Nutze diese Fragen in der angegebenen Reihenfolge:

| Frage | Konsequenz für die Entscheidung |
| --- | --- |
| Kann die vorhandene SQL-Plattform die Regel klar und zuverlässig implementieren? | Mit SQL beginnen, bevor ein weiteres Framework ergänzt wird |
| Ist der Workload überwiegend relational und mengenorientiert? | SQL, native Warehouse-Funktionen oder dbt bevorzugen |
| Sind Python, Spark oder semistrukturierte Verarbeitung unverzichtbar? | Notebooks oder native Python-/Spark-Pipelines bevorzugen |
| Benötigt das Team eine visuelle Low-Code-Entwicklung? | Dataflows oder etablierte ETL-Werkzeuge prüfen |
| Gibt es viele abhängige SQL-Modelle und häufige parallele Änderungen? | dbt oder ein ausgereiftes natives Code-Framework kann Mehrwert bieten |
| Sind Git-Reviews, CI/CD, Tests und generierte Dokumentation wesentliche Lücken? | Ein Framework auswählen, das diese Lücken explizit schließt |
| Ist Streaming oder ereignisgetriebene Verarbeitung erforderlich? | Eine für Streaming geeignete Plattform nutzen statt ein Batch-Muster zu erzwingen |
| Ist die Regel bereits an anderer Stelle implementiert? | Ownership konsolidieren, bevor eine weitere Version entsteht |
| Reduziert das vorgeschlagene Tool Duplizierung? | Nur fortfahren, wenn die Ownership-Grenze explizit ist |
| Erzeugt das Tool neue, nicht unterstützte Betriebsaufgaben? | Entscheidung überdenken oder zuerst ein Betriebsmodell bereitstellen |
| Können fehlerhafte Datensätze und Regelergebnisse persistiert werden? | Design erst veröffentlichen, wenn der Nachweis definiert ist |
| Können Qlik, Power BI und Excel das Ergebnis konsumieren, ohne die Regel neu aufzubauen? | Der Transformationsvertrag ist ausreichend wiederverwendbar |

Eine kompakte Regel lautet:

```text
Vorhandene Fähigkeit löst das Problem          → verwenden
Native Funktion verbessert den Betrieb         → bewusst verwenden
SQL-Modellnetz benötigt Zusammenarbeit         → dbt prüfen
Python/Spark ist Bestandteil des Workloads     → Notebook oder native Pipeline verwenden
Low-Code ist die wartbare Teamoberfläche       → Dataflow oder ETL-Werkzeug verwenden
Gemeinsame Regel existiert bereits             → wiederverwenden, nicht duplizieren
Kein klarer Owner oder Testnachweis             → Architektur ist nicht bereit
```

## Ein praktikabler Einführungspfad

Die Modernisierung von Transformationen sollte nicht mit der Migration jedes Jobs beginnen.

Beginne mit einer Geschäftsregel, die dupliziert, wichtig und messbar ist.

```flow linear vertical
Eine hochwertige Regel auswählen
Bestehende Varianten dokumentieren
Autoritatives fachliches Ergebnis definieren
Einfachste führende Implementierung auswählen
Fachliche und technische Ownership zuweisen
Qualitätsnachweise persistieren
Parallel validieren
Consumer migrieren
Duplizierte Implementierungen stilllegen
Verbesserung von Lieferung und Qualität messen
```

Gute erste Kandidaten sind:

- Zulässigkeit aktiver Kunden;
- Stornobehandlung;
- Währungsnormalisierung;
- Zuordnung von Produkthierarchien;
- Kunden-Länder-Mapping;
- Klassifikation von Rechnungsstatus;
- eine zentrale Basisdefinition für Umsatz.

Das Ziel der ersten Migration besteht nicht darin, die Überlegenheit eines Tools zu beweisen. Es besteht darin zu zeigen, dass eine governte Implementierung mehrere inkonsistente Varianten ersetzen kann.

## Wichtigste Empfehlungen

1. Definiere die Transformationsverantwortung, bevor das Tool ausgewählt wird.
2. Gib jeder gemeinsamen Geschäftsregel eine führende Implementierung und einen verantwortlichen Owner.
3. Beginne mit vorhandenem SQL, Scheduling und nativen Plattformfähigkeiten, wenn sie ausreichen.
4. Nutze Views für einfache wiederverwendbare Logik und materialisiere Ergebnisse, wenn Performance, Publikationsstabilität oder Workload dies erfordern.
5. Nutze Stored Procedures für begründetes prozedurales und transaktionales Verhalten und nicht als undokumentierten Plattformmonolithen.
6. Nutze Dataflows und klassische ETL-Werkzeuge, wenn ihre Connector-, Low-Code- und Betriebsstärken zum pflegenden Team passen.
7. Nutze Notebooks, Python und Spark für Workloads, die diese Fähigkeiten tatsächlich benötigen.
8. Überführe Notebooks in einen kontrollierten Produktions-Lifecycle, bevor sie zu kritischen Pipelines werden.
9. Nutze native Fabric-, Snowflake-, Databricks- oder Datenbankfunktionen, wenn Integration und Betriebswert die Plattformkopplung rechtfertigen.
10. Ergänze dbt, wenn modulares SQL, Abhängigkeitsmanagement, Tests, Dokumentation, Reviews und reproduzierbare Umgebungen ein reales Kollaborationsproblem lösen.
11. Führe dbt nicht nur ein, weil es in modernen Data Stacks verbreitet ist.
12. Berücksichtige, dass dbt in der Ziel-Engine ausgeführt wird und SQL-Dialekt- oder Plattformunterschiede nicht beseitigt.
13. Trenne Verantwortungen für Orchestrierung, Transformation, Qualitätsnachweise und Consumption explizit.
14. Persistiere fehlgeschlagene fachliche Regeln, wenn Remediation und Verantwortlichkeit relevant sind.
15. Halte Qlik-Skripte, Power Query und DAX frei von duplizierter gemeinsamer Transformationslogik.
16. Erlaube Qlik-, Power-BI- und Excel-spezifische Logik nur, wenn der jeweilige Consumer-Kontext sie benötigt.
17. Behandle eine governte QVD-Schicht als gültige Brownfield-Option und nicht als verpflichtenden unternehmensweiten Vertrag.
18. Betreibe native und ablösende Implementierungen nach einer Migration nicht dauerhaft als gleichberechtigte Wahrheiten.
19. Entscheide nach Workload, Team und Betriebsmodell statt nach Plattformmode.
20. Miss Erfolg an weniger duplizierten Regeln, schnelleren kontrollierten Änderungen, sichtbarer Qualität und geringerem Betriebsrisiko.

## Übergang zum nächsten Part

Eine Transformationsstrategie ist erst vollständig, wenn sie über unterschiedliche Technologielandschaften hinweg verständlich bleibt.

Der nächste Part, [Eine Architektur – mehrere Plattformen](/playbooks/one-architecture-multiple-platforms), ordnet dieselben Architekturverantwortungen Microsoft Fabric, Snowflake, Databricks, klassischen SQL-Warehouses, Qlik-orientierten Umgebungen und hybriden Kombinationen zu, ohne einen Stack als allgemeine Pflicht darzustellen.
