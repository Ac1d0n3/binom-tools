---
title: Welche Rolle dbt spielt — und welche Alternativen es gibt
description: dbt im modernen Data Stack: Aufgaben, Grenzen, Stärken, Alternativen und ein praxisnaher Lernpfad für den Einstieg.
category: Data Engineering
tags:
  - dbt
  - analytics-engineering
  - data-transformation
  - elt
  - sql
  - data-modeling
  - data-quality
  - lineage
  - orchestration
  - data-platform
order: -1
publishedAt: 2026-07-12
hero: images/playbooks/dbt-role-hero.png
---

## Warum dbt im modernen Data Stack eine besondere Rolle spielt

Viele Datenplattformen können SQL ausführen. Genau deshalb wird dbt manchmal als „nur ein weiteres SQL-Tool“ unterschätzt. Der eigentliche Mehrwert liegt jedoch nicht darin, SQL neu zu erfinden, sondern darin, **Transformationen wie Software zu behandeln**.

dbt bringt Struktur in die Schicht zwischen geladenen Rohdaten und den Datenprodukten, die später von BI, Analytics, Data Science oder Anwendungen genutzt werden. Modelle werden als Code versioniert, Abhängigkeiten werden explizit, Tests werden Teil des Entwicklungsprozesses und Dokumentation sowie Metadaten entstehen direkt am technischen Artefakt.

Dabei bleibt eine wichtige Abgrenzung bestehen: **dbt ist kein Data Warehouse und verschiebt üblicherweise auch keine Daten aus den Quellsystemen.** Die Transformationen werden in der angebundenen Datenplattform ausgeführt — beispielsweise in Snowflake, Databricks, BigQuery oder Redshift.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dbt-role-img1-de.png"
        alt="Die Rolle von dbt zwischen Datenplattform und Analytics mit Modellen, Tests, Dokumentation, Lineage und Deployment"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        dbt sitzt in der Transformationsschicht: Die Daten bleiben in der Plattform, während Modelle, Tests, Dokumentation und Abhängigkeiten als Code organisiert werden.
    </figcaption>
</figure>

## Die Kernidee: Transformation as Code

Ein dbt-Projekt besteht im Kern aus SQL- oder teilweise auch Python-Modellen, YAML-Metadaten und Projektkonfiguration. Aus diesen Bausteinen entsteht ein gerichteter Abhängigkeitsgraph — der dbt DAG.

Typische Bausteine sind:

- **Models** — SQL- oder Python-Definitionen für Tabellen, Views oder andere persistierte Datenobjekte
- **`ref()`** — explizite Referenzen zwischen Modellen und damit die Grundlage für Abhängigkeiten und Lineage
- **Sources** — deklarierte Eingangstabellen aus der Lade- oder RAW-Schicht
- **Materializations** — Regeln dafür, ob ein Modell beispielsweise als View, Table, Incremental Model oder Ephemeral Model umgesetzt wird
- **Data Tests** — wiederverwendbare oder individuelle Qualitätsprüfungen
- **Documentation** — Beschreibungen für Modelle, Spalten, Quellen und weitere Ressourcen
- **Jinja und Macros** — Wiederverwendung, Automatisierung und Standardisierung von SQL-Logik
- **Git und CI/CD** — Reviews, Branches, Pull Requests und kontrollierte Deployments

Das Ergebnis ist nicht automatisch ein gutes Datenmodell. dbt schafft aber die technische Grundlage, um Modellierungsregeln **sichtbar, wiederholbar, testbar und versionierbar** umzusetzen.

## Wo dbt im Stack sitzt — und wo nicht

| Bereich | Typische Verantwortung | Rolle von dbt |
| --- | --- | --- |
| **Quellsysteme** | Operative Anwendungen, APIs, Dateien, SaaS-Plattformen | Keine direkte Kernaufgabe |
| **Ingestion / ELT** | Daten extrahieren und in die Zielplattform laden | Wird meist durch Fivetran, Airbyte, Cloud-Dienste oder eigene Pipelines erledigt |
| **Data Platform** | Storage, Compute, Security, Tabellen und Plattformbetrieb | dbt nutzt die Plattform, ersetzt sie aber nicht |
| **Transformation** | RAW-Daten strukturieren, Geschäftslogik modellieren, Datenprodukte erzeugen | **Kernbereich von dbt** |
| **Orchestration** | Jobs, Abhängigkeiten und systemübergreifende Workflows steuern | Teilweise innerhalb der dbt-Plattform möglich; übergreifend oft mit Airflow, Dagster, Prefect oder Plattformdiensten ergänzt |
| **Analytics / BI** | Dashboards, semantische Modelle, Ad-hoc-Analysen | dbt liefert vorbereitete Daten und Metadaten, ist aber kein BI-Frontend |

## Warum Teams dbt einsetzen

### 1. Modulare Datenmodelle

Statt lange SQL-Skripte mit vielen temporären Schritten zu pflegen, können Transformationen in kleinere Modelle zerlegt werden. Diese Modelle lassen sich wiederverwenden und über `ref()` miteinander verbinden.

Das verbessert nicht automatisch jede Architektur. Es macht Abhängigkeiten aber expliziter und reduziert die Versuchung, immer größere monolithische SQL-Skripte zu bauen.

### 2. Tests direkt an den Datenmodellen

Data Tests können technische und fachliche Annahmen absichern. Typische Beispiele sind:

- Primärschlüssel sind eindeutig und nicht leer
- Statuswerte stammen aus einer erlaubten Werteliste
- Fremdschlüssel besitzen passende Referenzen
- Beträge liegen in einem plausiblen Bereich
- Quellen sind aktuell genug

Tests ersetzen kein vollständiges Data-Quality-Management. Sie verschieben Qualitätsregeln aber näher an die Stelle, an der Datenlogik entwickelt und geändert wird.

### 3. Dokumentation und Lineage aus dem Projekt

Beschreibungen, Abhängigkeiten und Metadaten können gemeinsam mit dem Code gepflegt werden. Dadurch sinkt das Risiko, dass technische Dokumentation vollständig von der realen Implementierung getrennt ist.

Die Lineage entsteht aus den deklarierten Quellen und Modellreferenzen. Sie ist besonders hilfreich für Impact Analyses, Reviews und das Verständnis komplexer Transformationsketten.

### 4. Zusammenarbeit über Git

Datenlogik kann mit denselben Engineering-Prinzipien entwickelt werden wie andere Software:

- Branches für Änderungen
- Pull Requests und Reviews
- automatisierte Tests
- getrennte Entwicklungs-, Test- und Produktionsumgebungen
- nachvollziehbare Historie

Das verändert nicht nur das Tooling. Es verändert auch die Zusammenarbeit zwischen Analytics Engineers, Data Engineers, BI-Entwicklern und fachlichen Data Ownern.

### 5. Metadaten als Grundlage für Governance

Modelle, Spalten, Beschreibungen, Tests, Tags, Gruppen, Owner und Exposures können eine wertvolle Metadatenbasis bilden. Diese Informationen lassen sich für Kataloge, Qualitätsübersichten, PII-Kennzeichnung, Impact Analyses oder Governance-Automatisierung weiterverwenden.

Wichtig ist jedoch: **Metadaten sind noch keine Governance.** Rollen, Freigaben, Verantwortlichkeiten, Policies und fachliche Entscheidungen müssen weiterhin organisatorisch definiert und betrieben werden.

## Was dbt nicht automatisch löst

Eine realistische Einordnung ist wichtiger als eine Feature-Liste.

- **Keine Ingestion-Plattform:** dbt ersetzt normalerweise keine Connectoren oder Ladeprozesse aus Quellsystemen.
- **Kein Data Warehouse:** Storage, Compute, Security und Datenbankbetrieb bleiben Aufgaben der Zielplattform.
- **Kein vollständiger Enterprise-Orchestrator:** Systemübergreifende Pipelines, Sensoren, APIs oder komplexe Betriebsworkflows benötigen häufig zusätzliche Werkzeuge.
- **Kein BI-Frontend:** Dashboards, Self-Service und Visualisierung bleiben Aufgaben von Qlik, Power BI, Tableau, Looker, Excel oder anderen Konsumenten.
- **Keine automatische fachliche Wahrheit:** Ein technisch sauberer DAG verhindert keine unterschiedlichen KPI-Definitionen in Reports oder Fachbereichen.
- **Keine Governance per Knopfdruck:** Tags und Dokumentation helfen, ersetzen aber keine Ownership und keine kontrollierten Prozesse.

## dbt Core und die dbt-Plattform

Die grundlegenden Konzepte sind in beiden Varianten ähnlich, das Betriebsmodell unterscheidet sich jedoch.

| Variante | Typischer Einsatz |
| --- | --- |
| **dbt Core** | Lokale oder selbst betriebene CLI-basierte Entwicklung; maximale Kontrolle über Laufzeit, CI/CD und Infrastruktur |
| **dbt-Plattform** | Verwaltete Entwicklungs-, Deployment-, Katalog-, Scheduling- und Kollaborationsfunktionen innerhalb eines integrierten Angebots |

Die Entscheidung ist nicht nur technisch. Sie hängt von Betriebsmodell, Security, vorhandener CI/CD-Landschaft, Teamgröße, Support-Anforderungen und Kosten ab.

## Welche Alternativen gibt es?

Nicht jede Alternative ersetzt dbt vollständig. Einige decken nur einen Teil der Aufgaben ab, andere verfolgen bewusst einen anderen Entwicklungsansatz.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dbt-role-img2-de.png"
        alt="Alternativen und angrenzende Ansätze zu dbt mit SQL-Skripten, Warehouse-nativen Tools, dbt-ähnlichen Frameworks, Low-Code und codebasierter Verarbeitung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der richtige Ansatz hängt von Team-Skills, Plattform, Projektgröße, Governance-Anforderungen und gewünschtem Betriebsmodell ab.
    </figcaption>
</figure>

### 1. Manuelle SQL-Skripte und Stored Procedures

Transformationen können direkt als Views, Tabellen, Stored Procedures oder geplante SQL-Skripte in der Datenplattform umgesetzt werden.

**Stärken**

- wenige zusätzliche Komponenten
- direkter Zugriff auf native Plattformfunktionen
- hohe Kontrolle
- für kleine, klar begrenzte Lösungen oft ausreichend

**Trade-offs**

- Standards, Tests, Lineage und Dokumentation müssen selbst aufgebaut werden
- Wiederverwendung und Abhängigkeitsmanagement werden schnell schwieriger
- bei vielen Teams steigt das Risiko unterschiedlicher Entwicklungsstile

### 2. Warehouse-native Transformationswerkzeuge

Cloud-Plattformen bieten eigene Funktionen für SQL-Transformation, Scheduling, Pipelines und Workflows. Beispiele sind Snowflake Tasks, BigQuery Scheduled Queries, Databricks Workflows oder plattformspezifische SQL- und Pipeline-Funktionen.

**Passt häufig, wenn:** die Architektur bewusst stark auf eine Plattform ausgerichtet ist und zusätzliche Frameworks vermieden werden sollen.

**Trade-off:** Der Plattform-Lock-in kann steigen, und ein einheitliches Entwicklungsmodell über mehrere Plattformen hinweg wird schwieriger.

### 3. dbt-ähnliche Frameworks

Am nächsten an dbt liegen Frameworks, die ebenfalls SQL-first, codebasiert und modellorientiert arbeiten.

- **SQLMesh** — SQL-basierte Modellierung mit Versionierung, Planung und eigenen Ansätzen für Umgebungen und Änderungen
- **Dataform** — verwalteter SQL-Workflow-Ansatz im Google-Cloud-Ökosystem

Diese Werkzeuge können echte Alternativen sein, wenn ihre Plattformintegration oder ihr Entwicklungsmodell besser zum eigenen Kontext passt.

> **Einordnung:** Soda Core wird häufig im Umfeld von dbt genannt, ist aber primär ein Data-Quality-Werkzeug. Es ergänzt Transformationen und Tests, ersetzt dbt jedoch nicht als vollständiges Modellierungs- und Transformationsframework.

### 4. Visuelle und Low-Code-Transformation

Werkzeuge wie Matillion, SnapLogic, Domo Magic ETL, Dataiku oder andere visuelle Pipeline-Designer ermöglichen Transformationen über grafische Oberflächen.

**Stärken**

- schneller Einstieg für weniger codeorientierte Teams
- visuelle Darstellung von Abläufen
- häufig integrierte Connectoren und Betriebsfunktionen

**Trade-offs**

- Wiederverwendung und Versionskontrolle können je nach Produkt eingeschränkt sein
- komplexe Logik wird in grafischen Flows nicht automatisch übersichtlicher
- Plattform- oder Produktabhängigkeit kann steigen

### 5. Codebasierte Verarbeitung mit Spark, Python oder anderen Frameworks

Für komplexe Algorithmen, große verteilte Workloads, ML-nahe Verarbeitung oder spezielle Bibliotheken kann klassischer Code geeigneter sein.

Typische Ansätze sind:

- Apache Spark / PySpark
- Python
- Pandas oder Polars
- Apache Beam

Diese Ansätze bieten hohe Flexibilität, benötigen aber meist mehr Engineering, Tests, Packaging, Betriebslogik und Standardisierung.

### 6. Orchestrierung als Ergänzung — nicht als direkter Ersatz

Airflow, Dagster und Prefect werden oft in dbt-Vergleichen genannt. Sie lösen jedoch primär eine andere Aufgabe: Sie koordinieren Jobs, Systeme, Zeitpläne und Abhängigkeiten.

In vielen Architekturen gilt daher:

`Orchestrator → startet und überwacht dbt → dbt transformiert Daten in der Plattform`

Die Werkzeuge ergänzen sich häufig, statt sich gegenseitig zu ersetzen.

## Wann dbt besonders gut passt

| Situation | Einschätzung |
| --- | --- |
| SQL-basierte Transformation ist der Schwerpunkt | **Sehr guter Fit** |
| Mehrere Teams entwickeln gemeinsam Datenmodelle | **Starker Fit**, besonders mit Git, Reviews und Standards |
| Tests, Dokumentation und Lineage sollen nah am Code liegen | **Starker Fit** |
| Das Unternehmen nutzt Snowflake, Databricks, BigQuery, Redshift oder eine andere unterstützte Plattform | **Typischer Einsatzbereich** |
| Sehr kleine Lösung mit wenigen stabilen SQL-Objekten | dbt kann sinnvoll sein, ist aber nicht immer notwendig |
| Hauptaufgabe ist Ingestion | Andere Tools sind wichtiger |
| Hauptaufgabe sind komplexe Python-, ML- oder Streaming-Workloads | Spark- oder codebasierte Ansätze können geeigneter sein |
| Fachanwender sollen Transformationen überwiegend visuell erstellen | Low-Code kann besser passen |
| Es wird ein systemübergreifender Workflow-Orchestrator benötigt | dbt meist ergänzen, nicht allein einsetzen |

## Ein sinnvoller Lernpfad für dbt

Der schnellste Weg ist nicht, jede Funktion auswendig zu lernen. Sinnvoller ist ein kleiner End-to-End-Use-Case, der schrittweise erweitert wird.

### Stufe 1 — ELT und Analytics Engineering verstehen

Zuerst sollten die Grundbegriffe klar sein:

- Unterschied zwischen ETL und ELT
- Rolle eines Cloud Data Warehouse oder Lakehouse
- Transformation innerhalb der Zielplattform
- Dimensionen, Fakten und fachliche Datenprodukte
- Git-Grundlagen

### Stufe 2 — Einen offiziellen Quickstart auf der eigenen Plattform durchführen

Ein Quickstart vermittelt schneller als eine rein theoretische Einführung, wie Projekt, Verbindung, Modelle und Ausführung zusammenspielen.

Wähle möglichst die Plattform, die du später tatsächlich einsetzen möchtest:

- [dbt + Snowflake Quickstart](https://docs.getdbt.com/guides/snowflake)
- [dbt + Databricks Quickstart](https://docs.getdbt.com/guides/databricks)
- [dbt + BigQuery Quickstart](https://docs.getdbt.com/guides/bigquery)
- [dbt + Redshift Quickstart](https://docs.getdbt.com/guides/redshift)
- [dbt Core + DuckDB Quickstart](https://docs.getdbt.com/guides/duckdb) — gut für einen lokalen, kostengünstigen Einstieg
- [dbt Core manuell installieren](https://docs.getdbt.com/guides/manual-install)

### Stufe 3 — Models, `ref()` und Sources beherrschen

Baue eine kleine Schichtenlogik:

`Sources → Staging → Intermediate → Marts`

Lerne dabei:

- [dbt Models](https://docs.getdbt.com/docs/build/models)
- [`ref()` und Modellabhängigkeiten](https://docs.getdbt.com/reference/dbt-jinja-functions/ref)
- [Sources und Source Freshness](https://docs.getdbt.com/docs/build/sources)

### Stufe 4 — Tests und Dokumentation hinzufügen

Ergänze zunächst einfache Regeln:

- `not_null`
- `unique`
- `relationships`
- `accepted_values`

Danach folgen eigene fachliche Tests, Beschreibungen und eine bewusst gepflegte Dokumentation.

- [Data Tests](https://docs.getdbt.com/docs/build/data-tests)
- [Dokumentation in dbt](https://docs.getdbt.com/docs/build/documentation)

### Stufe 5 — Materializations und Incremental Models verstehen

Nicht jedes Modell sollte als Tabelle gebaut werden. Lerne die Auswirkungen von Views, Tables, Incremental Models und Ephemeral Models auf Performance, Kosten und Wartbarkeit.

- [Materializations](https://docs.getdbt.com/docs/build/materializations)

### Stufe 6 — Jinja, Macros und Packages gezielt einsetzen

Automatisierung ist wertvoll, aber zu frühe Abstraktion macht Projekte schwerer verständlich. Beginne mit wiederkehrender Logik und abstrahiere erst dann.

- [Jinja und Macros](https://docs.getdbt.com/docs/build/jinja-macros)
- [dbt Packages](https://docs.getdbt.com/docs/build/packages)

### Stufe 7 — Deployment, CI und Betriebsmodell aufbauen

Erst hier wird aus einem lokalen Projekt ein belastbarer Produktionsprozess.

Themen:

- Entwicklungs-, Test- und Produktionsumgebungen
- Pull Requests und Reviews
- CI-Jobs
- Scheduling und Deployments
- Monitoring und Fehlerbehandlung

- [dbt Deployments](https://docs.getdbt.com/docs/deploy/deployments)
- [Continuous Integration](https://docs.getdbt.com/docs/deploy/continuous-integration)

### Stufe 8 — Metadaten, Exposures, Governance und Semantik erweitern

Danach lohnt sich der Blick auf:

- Owner und Groups
- Tags und Meta Properties
- Exposures für Dashboards und Anwendungen
- Artefakte wie `manifest.json` und `catalog.json`
- dbt Semantic Layer
- dbt Mesh für größere, domänenübergreifende Umgebungen

- [Exposures](https://docs.getdbt.com/docs/build/exposures)
- [dbt Artifacts](https://docs.getdbt.com/docs/deploy/artifacts)
- [dbt Semantic Layer](https://docs.getdbt.com/docs/use-dbt-semantic-layer/dbt-sl)
- [dbt Mesh Quickstart](https://docs.getdbt.com/guides/mesh-qs)

## Die wichtigsten offiziellen Einstiegsseiten

| Ressource | Wofür sie geeignet ist |
| --- | --- |
| [dbt Developer Hub](https://docs.getdbt.com/) | Zentrale technische Dokumentation und Referenz |
| [Was ist dbt?](https://docs.getdbt.com/docs/introduction) | Überblick über Konzept, Rolle und Arbeitsweise |
| [dbt Quickstarts](https://docs.getdbt.com/docs/get-started-dbt) | Geführter Einstieg nach Datenplattform |
| [dbt Guides](https://docs.getdbt.com/guides) | Schritt-für-Schritt-Anleitungen und vertiefende Tutorials |
| [dbt Learn — Kurskatalog](https://learn.getdbt.com/catalog) | Kostenlose und weiterführende Lernmodule sowie Learning Paths |
| [dbt Learn](https://www.getdbt.com/dbt-learn) | Übersicht über offizielle Trainingsangebote |
| [dbt Certification](https://www.getdbt.com/dbt-certification) | Zertifizierungen und Prüfungseinstieg |
| [Analytics Engineering Certification](https://www.getdbt.com/certifications/analytics-engineer-certification-exam) | Prüfung für Modellierung, Tests und produktive dbt-Arbeit |
| [dbt Community](https://www.getdbt.com/community/join-the-community) | Austausch, Praxiswissen und Community-Ressourcen |

## Fazit

dbt ist weder die gesamte Datenplattform noch die Antwort auf jedes Data-Engineering-Problem. Seine Stärke liegt in einer klar definierten Rolle:

> **SQL-basierte Transformationen in der Datenplattform mit Software-Engineering-Prinzipien entwickeln, testen, dokumentieren und betreiben.**

Für viele moderne Analytics-Plattformen ist das eine sehr starke Kombination. Die Entscheidung sollte trotzdem vom Kontext ausgehen: Team-Skills, Plattformstrategie, Komplexität, Governance, Betriebsmodell und Kosten.

Die bessere Frage lautet deshalb nicht:

*„Ist dbt das beste Tool?“*

sondern:

***„Ist ein SQL-first, codebasierter und metadata-getriebener Transformationsansatz der richtige Fit für unsere Datenplattform und unser Team?“***
