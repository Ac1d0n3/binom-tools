---
title: Metadaten automatisiert erfassen — Technischen und operativen Kontext erfassen, ohne ihn manuell nachzubauen
description: Eine praxisnahe Architektur zur automatisierten Erfassung von Schemas, Lineage, Laufzeiten, Nutzung, Zugriffen und Qualitätsevidenz über unterstützte Schnittstellen mit erhaltener Provenance, Versionierung, Freshness und Änderungshistorie.
category: Data Governance
tags:
  - metadata
  - metadata-harvesting
  - metadata-ingestion
  - data-catalog
  - data-lineage
  - openlineage
  - dbt
  - information-schema
  - data-observability
  - schema-drift
  - metadata-governance
  - active-metadata
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 4
seriesTitle: MetaData Deep Dive
hero: images/playbooks/harvest-metadata-automatically-hero.png
publishedAt: 2026-06-23 10:00
---

## Die Metadatenerfassung wird zum Engpass, wenn Maschinen auf manuelle Dokumentation warten

Moderne Datenplattformen erzeugen fortlaufend technischen und operativen Kontext.

Eine Datenbank weiß, welche Tabellen und Felder vorhanden sind. Transformationscode kennt die Abhängigkeiten zwischen Inputs und Outputs. Eine Orchestrierungsplattform weiß, wann eine Pipeline ausgeführt wurde und ob sie erfolgreich war. Ein Warehouse protokolliert Queries und Zugriffe. Ein Quality-System beobachtet fehlgeschlagene Prüfungen. Ein semantisches Modell kennt seine Measures, Beziehungen und Reportabhängigkeiten.

Dieser Kontext sollte nicht manuell in einem zentralen Katalog nachgebaut werden.

Manuelle Rekonstruktion erzeugt gleichzeitig drei Probleme:

- die Erfassung ist zu langsam für die Geschwindigkeit technischer Änderungen;
- kopierte Metadaten werden veraltet, ohne sichtbar falsch zu wirken;
- wertvolle operative Evidenz wird auf gelegentliche Dokumentations-Snapshots reduziert.

Das Ergebnis ist häufig ein Katalog, der geordnet aussieht, aber aktuelle Fragen nicht beantworten kann:

- Wurde dieses Feld heute oder vor drei Monaten ergänzt?
- Welche Pipeline-Version hat die aktuelle Tabelle erzeugt?
- Basiert die Lineage auf geparstem Code, einer beobachteten Ausführung oder einer manuellen Deklaration?
- Wann wurde die Quelle zuletzt erfolgreich erfasst?
- Wurde ein Asset gelöscht oder ist lediglich der Connector ausgefallen?
- Welche Reports verwenden das abgekündigte Feld weiterhin?
- Hat ein Sensitivitätsdetektor nach einer Schemaänderung ein neues Muster gefunden?

> **Technische und operative Metadaten sollten über unterstützte Schnittstellen automatisiert erfasst, mit Provenance normalisiert und auf Änderungen überwacht werden. Menschliche Arbeit sollte für Bedeutung, Verantwortlichkeit, Freigaben und Ausnahmen eingesetzt werden, die Maschinen nicht zuverlässig bestimmen können.**

Automatisiertes Harvesting bedeutet nicht, ungeprüft alles in eine zentrale Plattform zu kopieren.

Eine vertrauenswürdige Harvesting-Architektur muss wissen:

- welche Schnittstelle den Metadatenwert geliefert hat;
- welches Quellobjekt und welche Version er repräsentiert;
- wann er erfasst oder beobachtet wurde;
- wie er normalisiert wurde;
- ob die Erfassung vollständig war;
- welche Freshness erwartet wird;
- ob ein fehlendes Objekt gelöscht, nicht sichtbar oder vorübergehend nicht erreichbar ist;
- welches Team den Connector und seine Fehlerbehandlung verantwortet.

Der Erfassungsprozess ist damit eine governte Datenpipeline für Metadaten.

## Metadaten über die Schnittstelle erfassen, die der ursprünglichen Evidenz am nächsten ist

Es gibt keinen universellen Metadaten-Connector.

Unterschiedliche Systeme stellen unterschiedliche Teile der Wahrheit über Datenbankkataloge, APIs, generierte Artefakte, Logs, Events, Code oder Repositories bereit. Jede Methode besitzt eine eigene Abdeckung, Latenz und eigene Fehlerbilder.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/harvest-metadata-automatically-img1-de.png"
        alt="Sechs Methoden zur Metadatenerfassung führen in eine Pipeline für Extraktion, Normalisierung, Identitätsauflösung, Provenance, Versionierung und Veröffentlichung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Kein Connector erfasst jede Metadatendimension. Eine belastbare Collection-Architektur kombiniert mehrere unterstützte Schnittstellen und dokumentiert Freshness sowie Abdeckung jedes Beitrags.
    </figcaption>
</figure>

### Datenbankkataloge und Information Schemas

Datenbanknative Metadaten sind normalerweise die beste Quelle für die aktuelle physische Struktur.

Typische Kandidaten sind:

- Datenbanken, Schemas, Tabellen und Views
- Felder und Datentypen
- Nullability
- Keys und Constraints
- Objektkommentare
- Partitionierungs- und Clustering-Informationen
- Privileges und Grants
- View-Definitionen
- von der Plattform bereitgestellte Abhängigkeiten
- Zeitpunkte von Erstellung, Änderung und Löschung, sofern verfügbar

Das Information Schema bietet für relationale Systeme einen vergleichsweise portablen Einstieg. Produktspezifische Systemkataloge liefern häufig mehr Details, benötigen aber plattformspezifische Logik.

Ein Scanner darf nicht annehmen, dass ein leeres Ergebnis eine leere Datenbank bedeutet. Metadaten-Views sind häufig berechtigungsabhängig. Ein Connector sieht möglicherweise nur die Objekte, die seine Service Identity untersuchen darf.

Das Erfassungsergebnis sollte deshalb enthalten:

```text
Zurückgelieferte Objekte
+ angeforderter Scope
+ verwendete Berechtigungen
+ Erfassungszeitpunkt
+ Erfassungsstatus
+ Bewertung der Vollständigkeit
```

Ohne Scope-Evidenz kann „kein Objekt gefunden“ nicht von „Objekt nicht sichtbar“ unterschieden werden.

### Produkt-APIs

APIs sind geeignet, wenn ein Produkt Metadaten besitzt, die aus Datenbankstrukturen nicht korrekt rekonstruiert werden können.

Beispiele sind:

- semantische Modelle
- Measures und Dimensions
- Dashboards und Reports
- Datenquellenverbindungen
- Zertifizierungsstatus
- Zeitpläne
- Owner und Collaborators
- Workflow-Status
- Policy-Zuordnungen
- Quality Monitors
- Incidents
- Model Runs
- Deployment-Historie

Ein API-Connector sollte möglichst stabile Objekt-Identifier statt Anzeigenamen verwenden.

Anzeigenamen ändern sich. Ordnerpfade werden verschoben. Lokalisierte Labels unterscheiden sich. Ein vom Produkt erzeugter stabiler Identifier in Kombination mit Produktinstanz und Objekttyp ist normalerweise der bessere Identity Anchor.

Der Connector muss außerdem Pagination, API-Version, Rate Limits, Berechtigungsscope und Teilfehler erfassen. Eine erfolgreiche HTTP-Antwort beweist nicht, dass jede Page, jeder Workspace oder jedes Objekt verarbeitet wurde.

### Manifeste und Dokumentationsartefakte

Build- und Deployment-Tools erzeugen häufig strukturierte Artefakte, die zuverlässiger sind als das nachträgliche Reverse Engineering der Zielplattform.

Artefakte können enthalten:

- Nodes und Objektidentitäten
- Source References
- Abhängigkeiten
- Tests
- kompilierte Logik
- Beschreibungen
- Tags und Custom Metadata
- Ausführungsergebnisse
- Laufzeiten
- Status
- Freshness-Evidenz
- Codeversion oder Invocation Identity

Für dbt-Projekte können erzeugte Artefakte wie `manifest.json`, `catalog.json`, `run_results.json` und Source-Freshness-Ausgaben unterschiedliche Perspektiven auf deklarierte Struktur, kompilierte Abhängigkeiten, physische Kataloginformationen und Ausführungsevidenz liefern.

Entscheidend ist die Versionszuordnung.

Ein Manifest aus einem Commit, ein Catalog aus einer anderen Umgebung und Run Results aus einem abweichenden Deployment dürfen nicht so zusammengeführt werden, als repräsentierten sie einen konsistenten Zustand.

Ein erfasstes Artefaktset sollte deshalb erhalten:

- Projektidentität
- Umgebung
- Invocation Identifier
- Erzeugungszeitpunkt
- Artefakt-Schemaversion
- Code Revision
- Deployment Identifier
- erzeugenden Command oder Job
- Checksum
- Erfassungszeitpunkt

Artefakte sollten möglichst direkt aus CI oder der Ausführungsumgebung veröffentlicht werden. In gemeinsamen Ordnern nach „der neuesten JSON-Datei“ zu suchen, ist keine belastbare Metadatenpipeline.

### Query-, Audit- und Access-Logs

Logs liefern Evidenz darüber, was tatsächlich geschehen ist.

Sie können zeigen:

- ausgeführte Queries
- gelesene und geschriebene Objekte
- beteiligte User, Rollen oder Service Accounts
- Laufzeiten
- Fehler
- gescannte oder verarbeitete Volumen
- aus Queries abgeleitete Downstream-Abhängigkeiten
- ungenutzte oder stark genutzte Assets
- Zugriffe auf sensitive Objekte
- Datenbewegungen zwischen Source- und Target-Objekten

Beobachtete Metadaten sind wertvoll, weil sie Verhalten erfassen, das statische Dokumentation nicht zeigen kann.

Sie besitzen aber Grenzen.

Ein Query Log kann gekürzt, gesampelt oder nur für einen begrenzten Zeitraum verfügbar sein. Dynamic SQL lässt sich möglicherweise nur unvollständig parsen. Caches können physische Zugriffe verdecken. Service Accounts verbergen unter Umständen den fachlichen Consumer. Eine Query auf eine View erklärt nicht automatisch die vollständige Transformationsbedeutung.

Beobachtete Lineage sollte deshalb als beobachtet gekennzeichnet werden, einschließlich Zeitfenster und Confidence. Sie darf deklarierte oder geparste Lineage nicht still ersetzen.

### Events und OpenLineage-Nachrichten

Events eignen sich, wenn Metadaten möglichst nahe am Zeitpunkt eines Deployments, Runs oder Zustandswechsels erfasst werden sollen.

Ein Lineage Event kann beschreiben:

- einen Run
- den ausgeführten Job
- Input- und Output-Datasets
- Start-, Abschluss- oder Fehlerstatus
- Schema
- SQL- oder Verarbeitungskontext
- Parent-Child-Runs
- Quality Assertions
- Custom Facets

Event-getriebene Erfassung reduziert die Verzögerung zwischen technischer Änderung und Sichtbarkeit der Metadaten.

Sie erzeugt gleichzeitig eine neue Abhängigkeit: Der Producer muss Events zuverlässig liefern.

Die Collection-Plattform muss behandeln:

- doppelte Zustellung
- falsche Reihenfolge
- verspätete Events
- fehlende Completion Events
- Schema Evolution
- inkompatible Producer-Versionen
- Replay
- Korrelation zwischen Parent- und Child-Runs

Events müssen idempotent verarbeitet werden. Die erneute Verarbeitung desselben Events darf keine doppelten Assets oder Beziehungen erzeugen.

### Code Parser und Repository Scanner

Ein Teil der Metadaten existiert ausschließlich im Code.

Ein Repository Scanner kann untersuchen:

- SQL-Modelle
- YAML-Definitionen
- Orchestrierungsdateien
- Infrastructure-as-Code
- Definitionen semantischer Modelle
- Konfigurationsdateien
- Policy Mappings
- Dokumentation
- Ownership-Dateien
- Pull Requests und Commit-Historie

Code Parsing kann Abhängigkeiten vor dem Deployment erkennen. Repository Harvesting ist deshalb in CI, Design Reviews und Impact Analysis nützlich.

Geparste Metadaten sind jedoch nicht automatisch Runtime Truth.

Generiertes SQL, Macros, Templating, dynamische Objektnamen, Stored Procedures, externe Services und umgebungsspezifische Konfiguration können statische Analyse unvollständig machen.

Das Ergebnis sollte unterscheiden:

```text
Deklarierte Beziehung
Geparste Beziehung
Kompilierte Beziehung
Beobachtete Beziehung
Freigegebene Beziehung
```

Diese Formen können sich gegenseitig bestätigen. Sie dürfen nicht zu einer anonymen Kante zusammengefasst werden.

## Die einfachste tragfähige Umsetzung

Eine nutzbare Metadata-Harvesting-Plattform muss nicht mit jedem System und jedem Event beginnen.

Starte mit einem kritischen Datenprodukt und drei Erfassungswegen:

1. ein geplanter Schema Scan der zentralen Datenplattform;
2. Artefaktveröffentlichung aus der Transformationspipeline;
3. Runtime- und Usage-Evidenz aus der Ausführungsplattform.

Ergänze ein kleines Source Register, das den Vertrag jedes Connectors festlegt.

```yaml
connector_id: warehouse-prod-schema
owner: Data Platform Team
source_type: database_catalog
source_instance: warehouse-prod
scope:
  databases:
    - analytics
collection:
  mode: incremental_with_periodic_full_scan
  expected_freshness: 6h
  checkpoint: last_altered_at
identity:
  namespace: warehouse-prod
  case_policy: preserve_and_normalize
reliability:
  retries: 5
  quarantine_invalid_records: true
  retain_raw_payload: true
deletion:
  confirm_after_missed_successful_scans: 2
security:
  service_identity: metadata_reader
  secret_reference: vault/metadata/warehouse-prod
```

Die erste Umsetzung sollte bereitstellen:

- eine stabile Connector Identity;
- Least-Privilege-Authentifizierung;
- eine Raw Landing Area;
- Schema Validation;
- kanonische Asset Identifier;
- Provenance für jeden importierten Wert;
- versionierte Snapshots oder Changes;
- Freshness Monitoring des Connectors;
- Retries und einen Quarantänepfad;
- Deletion Handling;
- einen benannten Operational Owner.

Das reicht für eine belastbare Grundlage.

Es ist besser, drei Metadatendimensionen korrekt zu erfassen als zwanzig Connectoren mit unbekannter Freshness, inkonsistenter Identität und fehlender Fehlerverantwortung zu betreiben.

## Scheduled Scans, Event-Driven Collection und Metadata Streaming lösen unterschiedliche Probleme

Der Collection Mode sollte nach Geschwindigkeit und Bedeutung der Änderungen gewählt werden.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/harvest-metadata-automatically-img2-de.png"
        alt="Vergleich zwischen geplantem Scanning, event-getriebener Metadatenerfassung und Metadata Streaming mit Hybrid Collection als Zielbild"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Scheduled Scans liefern ein breites Inventar, Events liefern zeitnahe Zustandswechsel und Streams liefern kontinuierliche operative Evidenz. Die meisten Umgebungen benötigen einen kontrollierten Hybrid.
    </figcaption>
</figure>

### Scheduled Scan

Ein Scheduled Scan fragt die Quelle in definierten Intervallen ab.

Stärken:

- einfaches Betriebsmodell
- breites Inventar
- geeignet für Schemas, Berechtigungen und langsam veränderliche Objekte
- gute Reconciliation gegen einen vollständigen Source Scope
- vorhersehbare Last

Schwächen:

- Änderungen werden erst beim nächsten Scan erkannt
- wiederholte Full Scans können teuer sein
- große Landschaften benötigen Partitionierung und Checkpoints
- ein unvollständiger Scan kann wie Asset-Löschung wirken
- schnell veränderliche Runtime-Evidenz kann verloren gehen

Geeignete Metadaten:

- Schemas
- Tabellen und Felder
- Kommentare
- Grants
- semantische Objekte
- Konfiguration
- Ownership Register
- langsam veränderliche Policy-Zuordnungen

Ein periodischer Full Scan bleibt auch bei inkrementellem Harvesting sinnvoll. Er erkennt verpasste Events, fehlerhafte Checkpoints und stille Abweichungen.

### Inkrementelles Harvesting

Inkrementelles Harvesting ist ein Scan-Muster, das nur seit einem Checkpoint geänderte Datensätze anfordert.

Mögliche Checkpoints sind:

- `last_modified`
- monoton steigende Sequenz
- Change Token
- API Cursor
- Event Offset
- Repository Commit
- Artefakt Invocation
- Partition Date

Der Checkpoint darf erst fortgeschrieben werden, nachdem der erfasste Batch dauerhaft gespeichert wurde.

Eine Fortschreibung vor der Persistenz erzeugt unsichtbaren Datenverlust. Wiederverwendung ohne Idempotenz erzeugt Duplikate.

Inkrementelles Harvesting reduziert Source Load, benötigt aber eine Reconciliation-Strategie für:

- Objekte, deren Änderungszeitpunkt nicht zuverlässig aktualisiert wird
- Löschungen
- Late-Arriving Records
- Clock Skew
- source-seitige Retention
- beschädigte Checkpoints

### Event-Driven Collection

Event-Driven Collection reagiert auf einen bekannten Trigger wie:

- Deployment abgeschlossen
- Pipeline gestartet oder beendet
- Modell gebaut
- Schema geändert
- Quality Check fehlgeschlagen
- Report veröffentlicht
- Ownership freigegeben
- Policy aktualisiert

Stärken:

- nahezu unmittelbare Sichtbarkeit
- wenig unnötiges Polling
- direkte Korrelation mit technischen Aktionen
- gute Basis für Workflow- und Control-Automatisierung

Schwächen:

- Abdeckung hängt von zuverlässigen Event Producern ab
- ältere Systeme erzeugen möglicherweise keine Events
- Retries und Dead-Letter Handling sind erforderlich
- Producer und Consumer müssen Schema Evolution beherrschen
- fehlende Events bleiben ohne Reconciliation möglicherweise unsichtbar

Events eignen sich besonders für Zustandsübergänge. Als einzige Quelle für ein vollständiges aktuelles Inventar sind sie weniger geeignet.

### Metadata Streaming

Metadata Streaming verarbeitet einen kontinuierlichen Strom operativer Events.

Es ist geeignet, wenn die Plattform aktuelle Evidenz benötigt, beispielsweise:

- Pipeline States
- Query Activity
- Access Events
- Schema Change Events
- Quality Observations
- Lineage Events
- Model Inference Events
- Ergebnisse technischer Policy Enforcement

Stärken:

- hohe Freshness
- skalierbare Verarbeitung operativer Evidenz
- temporale Analyse
- schnelle Erkennung und Automatisierung

Schwächen:

- höhere Plattformkomplexität
- Anforderungen an Ordering und Replay
- Behandlung doppelter und verspäteter Events
- Entscheidungen zu Retention und Compaction
- komplexere operative Diagnose
- Risiko, Activity Streams als autoritative Stammdaten zu behandeln

Streaming ist nicht automatisch besser als Scanning. Es ist gerechtfertigt, wenn Freshness Entscheidungen oder Controls verändert.

### Hybrid Collection ist das praktische Zielbild

Ein häufiges Zielmuster lautet:

```text
Periodisches vollständiges Inventar
+ inkrementelle Scans
+ Deployment-Artefakte
+ event-getriebene Zustandswechsel
+ gestreamte operative Evidenz
→ abgestimmter Metadatenzustand
```

Der Full Scan liefert Vollständigkeit. Inkrementelle Erfassung reduziert Last. Artefakte erhalten deklarierten und kompilierten Kontext. Events reduzieren Latenz. Streams erfassen aktuelles Verhalten.

Die Metadatenplattform muss diese Inputs miteinander abstimmen und darf nicht voraussetzen, dass sie immer übereinstimmen.

## Unterschiedliche Metadatendimensionen erfassen, ohne Evidenz und Entscheidung zu verwechseln

Automatische Erfassung kann wesentlich mehr abdecken als Namen und Datentypen.

### Schema-Evidenz

Erfasse:

- Asset Identity
- physischen Speicherort
- Objekttyp
- Feldreihenfolge
- Datentyp
- Precision und Scale
- Nullability
- Default Values
- Constraints
- Partitionierung
- Kommentare
- Objektzeitpunkte

Schema-Metadaten werden normalerweise von der Plattform erzeugt und können häufig als autoritative technische Fakten für die beobachtete Quelle und den Erfassungszeitpunkt behandelt werden.

### Lineage-Evidenz

Erfasse:

- Source-to-Target-Beziehungen
- Modellabhängigkeiten
- Feld-Mappings
- Transformationsausdrücke
- Job-to-Dataset-Beziehungen
- Report-to-Model-Beziehungen
- Parent-Child-Runs
- Write Targets
- externe Inputs und Outputs

Jede Lineage-Kante sollte ihre Methode dokumentieren:

```text
manual
declared
parsed
compiled
observed
inferred
approved
```

Eine geparste Abhängigkeit aus versioniertem SQL und eine beobachtete Abhängigkeit aus einer ausgeführten Query sind unterschiedliche Evidenz. Beide können wertvoll sein.

### Runtime-Evidenz

Erfasse:

- Run Identifier
- Job und Task
- Start- und Endzeit
- Dauer
- Status
- Retry Count
- Umgebung
- Codeversion
- verarbeitete Zeilen
- verarbeitete Bytes
- Error Class
- erzeugendes System
- Parent Run

Runtime-Metadaten unterstützen Freshness, Incident Analysis und Trust Assessment.

Sie sollten historisch erhalten bleiben. Werden alle Runs durch ein Feld `last_run_status` ersetzt, geht die Evidenz verloren, die für die Bewertung instabiler Prozesse benötigt wird.

### Usage- und Access-Evidenz

Erfasse dort, wo es rechtlich und operativ zulässig ist:

- abfragenden User, Rolle oder Service Identity
- zugegriffenes Objekt
- Access Timestamp
- Operation Type
- Report Views
- Dashboard Usage
- Downstream Extracts
- Export Activity
- Write Targets
- wiederholte Fehler
- zuletzt beobachtete Nutzung

Usage ist nicht Ownership.

Eine stark genutzte Tabelle erhält dadurch nicht automatisch einen verantwortlichen Owner. Ein selten abgefragtes Asset kann rechtlich erforderlich oder operativ kritisch sein. Nutzung hilft bei der Priorisierung von Reviews, ersetzt aber keine Governance-Entscheidung.

### Quality- und Security-Evidenz

Erfasse:

- Testdefinition
- geprüftes Asset und Feld
- Ausführungszeitpunkt
- Ergebnis
- Threshold
- Anzahl fehlerhafter Datensätze
- Severity
- Detector Version
- gesampelten oder vollständigen Scope
- Sensitive-Data-Finding
- Ergebnis der Policy Evaluation
- Evidenz für Masking oder Access Control

Ein Detektor-Finding ist Evidenz und nicht automatisch eine freigegebene Klassifikation.

Das zentrale Modell sollte unterscheiden:

```text
Erkanntes sensitives Muster
→ vorgeschlagene Klassifikation
→ geprüfte Klassifikation
→ freigegebene Policy-Zuordnung
→ technische Enforcement-Evidenz
```

Werden diese Stufen zusammengefasst, können automatische False Positives zu unkontrollierten Governance-Entscheidungen werden.

## Metadata Ingestion wie eine produktive Datenpipeline aufbauen

Ein Connector, der direkt in einen durchsuchbaren Katalog schreibt, überspringt Kontrollen, die für belastbaren Betrieb erforderlich sind.

Eine stärkere Architektur trennt Raw Collection, Validation, Identity Resolution und Publication.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/harvest-metadata-automatically-img3-de.png"
        alt="Metadatenpipeline vom Connector über Raw Landing, Validation, Identity Mapping, Deduplication und Relationship Resolution bis zum versionierten Store und zu Suche, Graph und APIs"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Raw Metadata muss für Troubleshooting und Replay verfügbar bleiben. Search- und Graph-Views sollten erst nach Validation, Identity Resolution, Provenance und Versionierung veröffentlicht werden.
    </figcaption>
</figure>

### Connector

Der Connector authentifiziert sich an der Quelle, wendet einen definierten Scope an und ruft Metadaten ab.

Er sollte operative Metriken ausgeben wie:

- Start- und Abschlusszeit
- angeforderte Datensätze
- empfangene Datensätze
- verarbeitete Pages
- Source Throttling
- Retries
- Permission Errors
- Parse Failures
- Checkpoint
- Source Version
- Connector Version

### Raw Metadata Landing

Die Raw Landing speichert das Source Payload so, wie es empfangen wurde, ergänzt um einen Ingestion Envelope.

```yaml
ingestion:
  connector_id: dbt-prod-artifacts
  collected_at: 2026-07-24T18:15:22Z
  source_event_time: 2026-07-24T18:14:58Z
  source_version: git:8f42c1a
  payload_schema: dbt-manifest-v12
  checksum: sha256:...
  batch_id: md_20260724_181522_00491
payload:
  ...
```

Raw Retention ermöglicht:

- Troubleshooting
- Connector Regression Tests
- Replay nach Mapping-Änderungen
- Evidenz darüber, was die Quelle geliefert hat
- Vergleich zwischen Connector-Versionen
- Recovery nach Downstream-Fehlern

Wer direkt im Arbeitsspeicher normalisiert und die Raw Response verwirft, entfernt dieses Sicherheitsnetz.

### Schema Validation

Validation prüft, ob das Payload strukturell nutzbar ist.

Mögliche Ergebnisse:

- valid
- valid mit unbekannten optionalen Feldern
- inkompatible Schemaversion
- fehlende Pflichtidentität
- fehlerhafter Timestamp
- ungültige Enumeration
- zu großes Feld
- unzulässiger Inhalt
- quarantined

Unbekannte optionale Felder sollten die Erfassung nicht automatisch stoppen. Metadaten-APIs entwickeln sich weiter. Die Pipeline benötigt kontrollierte Forward Compatibility.

### Identifier Mapping

Identity Resolution überführt lokale Identifier in ein kanonisches Modell.

Ein praktischer Identifier kann kombinieren:

```text
Plattforminstanz
+ Umgebung
+ Asset-Typ
+ nativen stabilen Identifier
```

Beispiel:

```text
snowflake://org/account/ANALYTICS/SALES/FCT_ORDER_LINE
dbt://sales-transform/prod/model.fct_order_line
bi://tenant/workspace/model/net-sales
```

Anzeigenamen und Pfade bleiben durchsuchbare Attribute. Sie sollten nicht der einzige Identity Key sein.

### Deduplication

Doppelte Datensätze können entstehen durch:

- wiederholte API Pages
- At-Least-Once Event Delivery
- wiederholte Artefaktveröffentlichung
- überlappende Connector Scopes
- gespiegelte Accounts
- mehrere Parser
- Replay

Deduplication sollte stabile Event- oder Record-Identität und Source Version verwenden, nicht nur einen Hash des normalisierten Payloads.

Zwei identische Beobachtungen zu unterschiedlichen Zeitpunkten können relevante Evidenz sein. Deduplication darf gültige Historie nicht löschen.

### Relationship Resolution

Beziehungen treffen häufig ein, bevor beide Endpunkte bekannt sind.

Die Pipeline sollte unterstützen:

- unresolved references
- Placeholder Identities
- Late Binding
- Confidence
- Relationship Source
- Relationship Method
- Validity Interval

Eine ungelöste Lineage-Kante sollte nicht verworfen werden, nur weil das Target Asset noch nicht gescannt wurde.

### Versioned Metadata Store

Der Store sollte sowohl den aktuellen Zustand als auch die Änderungshistorie erhalten.

Ein nützliches Muster trennt:

- immutable Observations
- normalisierte Versions
- aktuelle resolved View
- Approval State
- Conflict State
- Tombstones
- abgeleiteten Search Index

Damit kann ein zentrales Profil die aktuelle Antwort zeigen und gleichzeitig die Evidenz erhalten, aus der sie abgeleitet wurde.

### Search, Graph und APIs

Consumer Interfaces sollten aus governten, versionierten Metadaten aufgebaut werden und nicht direkt aus Connector Payloads.

Sie können bereitstellen:

- Search
- Asset Pages
- Lineage Graph
- Impact Analysis
- Ownership Views
- Freshness Status
- Change History
- Governance Workflows
- maschinenlesbare APIs
- Policy- und Automation-Trigger

Die Veröffentlichung sollte die Provenance jedes Wertes sichtbar machen. Eine saubere UI darf keine falsche Sicherheit erzeugen, indem sie Konflikte oder veraltete Evidenz verbirgt.

## Identifier und Zeit normalisieren, ohne Source Details zu zerstören

Normalisierung ist notwendig, weil Systeme unterschiedliche Namen, Schreibweisen, Zeitstempel und Objektmodelle verwenden.

Sie ist zugleich ein häufiger Ort für Informationsverlust.

### Native Identity erhalten

Für jedes Asset und jede Beziehung sollten erhalten bleiben:

- kanonischer Identifier
- nativer Identifier
- Source Instance
- nativer Pfad
- Display Name
- Objekttyp
- Umgebung
- ursprüngliche Case-Sensitive-Schreibweise
- normalisierte Suchform
- First Observed Time
- Last Observed Time
- Source Version

Der einzige gespeicherte Identifier darf nicht pauschal kleingeschrieben werden. Einige Plattformen sind case-sensitive, andere normalisieren nicht gequotete Identifier. Das Original bleibt erhalten; für Vergleiche wird eine getrennte normalisierte Form erzeugt.

### Event Time und Collection Time trennen

Mindestens zu unterscheiden sind:

- Zeitpunkt, zu dem das Ereignis laut Quelle stattfand;
- Zeitpunkt, zu dem der Connector es abgerufen hat;
- Zeitpunkt der Verarbeitung in der Pipeline;
- Zeitpunkt, ab dem die normalisierte Version wirksam ist.

```yaml
source_event_time: 2026-07-24T17:59:02Z
collected_at: 2026-07-24T18:03:11Z
processed_at: 2026-07-24T18:03:18Z
effective_from: 2026-07-24T17:59:02Z
```

Diese Trennung ist für Late Events, verzögerte Scans und Replay erforderlich.

Zeit sollte in einer konsistenten kanonischen Form gespeichert werden, normalerweise UTC. Der ursprüngliche Source Value und seine Zeitzone bleiben erhalten, wenn sie für Audit oder Interpretation relevant sind.

### Jede Transformation während des Imports dokumentieren

Beispiele:

- Case Normalization
- Timezone Conversion
- Type Mapping
- Enum Mapping
- Namespace Mapping
- Path Parsing
- SQL Parsing
- Owner Identity Resolution
- Confidence Calculation
- Redaction sensitiver Felder

Das normalisierte Ergebnis sollte aus Raw Input und Mapping-Version reproduzierbar sein.

## Änderungen erkennen, ohne jede Differenz gleich zu behandeln

Metadatenänderungen besitzen unterschiedliche technische und Governance-Auswirkungen.

Ein neues nullable Feld ist nicht mit einem geänderten Owner gleichzusetzen. Eine überarbeitete Beschreibung entspricht nicht einer entfernten Masking Policy. Ein vorübergehend nicht sichtbares Asset ist nicht automatisch gelöscht.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/harvest-metadata-automatically-img4-de.png"
        alt="Vorheriger und aktueller Metadaten-Snapshot werden verglichen, klassifiziert und vor der Veröffentlichung einer neuen Version automatisch akzeptiert, geprüft oder blockiert"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Technischer Drift und Governance-signifikante Änderungen benötigen unterschiedliche Behandlung. Der Diff muss Auswirkungen klassifizieren, bevor eine neue Metadatenversion veröffentlicht wird.
    </figcaption>
</figure>

### Vorherigen und aktuellen Zustand vergleichen

Ein Metadata Diff sollte erkennen:

- hinzugefügtes Asset
- entferntes Asset
- hinzugefügtes Feld
- entferntes Feld
- umbenanntes Feld
- geänderter Datentyp
- geänderte Nullability
- geänderte Definition
- entfernte Beschreibung
- geänderter Owner
- geänderte Sensitivität
- geänderte Policy-Zuordnung
- geänderte Lineage
- gebrochene Lineage
- geänderter Quality State
- geändertes Usage Pattern

Der Vergleich sollte stabile Identität verwenden. Rein namensbasierter Vergleich macht aus jeder Umbenennung eine Löschung und Neuanlage.

### Änderung klassifizieren

Ein nützliches Klassifikationsmodell lautet:

```text
Technisch informativ
Technisch brechend
Operativ signifikant
Governance-signifikant
Security-signifikant
Unbekannt
```

Beispiele:

- neues nullable Feld: technisch informativ
- String wird zu Integer: technisch brechend
- Pipeline-Freshness überschritten: operativ signifikant
- Owner entfernt: Governance-signifikant
- neues Sensitive-Value-Muster: Security-signifikant
- Asset fehlt nach fehlgeschlagenem Scan: unbekannt

### Änderung routen

Mögliche Outcomes:

- Auto-Accept
- akzeptieren und benachrichtigen
- Review eröffnen
- Publication blockieren
- Quarantine
- Source Confirmation anfordern
- Remediation Task erzeugen

Regeln sollten die Kritikalität des Assets berücksichtigen.

Ein neues Feld in einer experimentellen Sandbox und dasselbe Feld in einem regulierten Finanzdatenprodukt sollten nicht identisch behandelt werden.

### Löschung explizit behandeln

Deletion Detection ist schwierig, weil Abwesenheit mehrere Bedeutungen besitzt:

```text
gelöscht
umbenannt
verschoben
Berechtigung entfernt
Connector Scope geändert
Quelle nicht erreichbar
Scan unvollständig
temporär gefiltert
```

Verwende Tombstones, statt das Asset sofort zu entfernen.

Ein Deletion Workflow kann verlangen:

1. erfolgreichen Scan des erwarteten Scopes;
2. Asset fehlt in diesem Scan;
3. optionale Bestätigung durch einen zweiten erfolgreichen Scan oder ein Deletion Event;
4. Impact Analysis;
5. Erhalt historischer Identität und Lineage;
6. Veröffentlichung eines gelöschten oder retired Status.

So bleibt Historie erhalten und ein Connector mit verlorener Berechtigung löscht nicht scheinbar eine gesamte Datenlandschaft.

## Konkretes Beispiel: `sales_amount` automatisiert erfassen

Betrachten wir dasselbe Sales-Feld, das in der Serie wiederholt verwendet wird.

Die Quellanwendung enthält `ORDER_ITEM.NETWR`. Ein Transformationsmodell stellt `sales_amount` bereit. Ein semantisches Modell veröffentlicht das zertifizierte Measure `Net Sales`. Reports verwenden dieses Measure.

### Datenbank-Scan

Der Warehouse Scanner beobachtet:

```yaml
asset: ANALYTICS.SALES.FCT_ORDER_LINE.SALES_AMOUNT
data_type: NUMBER(18,2)
nullable: false
comment: Net sales amount in reporting currency
last_altered: 2026-07-24T15:41:00Z
```

Dies ist autoritative technische Evidenz für das gescannte Warehouse-Objekt zu diesem Zeitpunkt.

### Transformationsartefakt

Das Transformationsartefakt liefert:

- Source Dependency auf `ORDER_ITEM.NETWR`
- Modell-Identifier
- Feldbeschreibung
- Tests
- Tags
- kompilierten Dependency Graph
- Code Revision
- Deployment Invocation

Es erklärt, wie das Zielfeld erzeugt wird.

### Runtime Event

Das Pipeline Event liefert:

- Run Identifier
- Start- und Abschlusszeit
- Input- und Output-Datasets
- Run Status
- erzeugenden Job
- Codeversion
- Parent Orchestration Run

Es belegt, dass eine bestimmte Implementierung ausgeführt wurde.

### Query- und Access-Evidenz

Warehouse Logs zeigen:

- welche Reports oder Service Identities das Feld abgefragt haben;
- wann es zuletzt verwendet wurde;
- ob Downstream-Transformationen es an anderer Stelle geschrieben haben;
- ob der Zugriff über Basistabelle, View oder Semantic Service Identity erfolgte.

Dies unterstützt Impact Analysis, definiert das Feld aber nicht neu.

### Semantic API

Die BI- oder Semantic API liefert:

- Measure Identifier `Net Sales`
- Expression
- Format
- unterstützte Dimensions
- Certification State
- Reports, die das Measure verwenden
- Workspace und Owner

### Governance Workflow

Das Governance-System liefert freigegebene Entscheidungen:

- unternehmensweiten Business Term
- Data Owner
- Data Steward
- Sensitivität
- erlaubte Nutzung
- Review Date
- Certification Status

Das zentrale Profil kombiniert diese Beiträge, ohne ihren Ursprung zu verbergen.

```yaml
canonical_asset: sales.fct_order_line.sales_amount

technical_state:
  source: warehouse-prod
  collected_at: 2026-07-24T18:03:11Z
  data_type: DECIMAL(18,2)
  nullable: false

transformation:
  source: dbt-sales-prod
  code_version: 8f42c1a
  upstream:
    - source.order_item.netwr
  tests:
    - not_null

runtime:
  source: orchestration-prod
  last_successful_run: 2026-07-24T17:58:43Z

semantic:
  source: semantic-sales-prod
  measure: net_sales
  certification: certified

governance:
  source: governance-workflow
  owner: Sales Data Owner
  sensitivity: internal
  status: approved
```

Findet der nächste Schema Scan `VARCHAR` statt `DECIMAL`, ist der Diff technisch und möglicherweise brechend.

Verschwindet der Owner aus der Governance-Quelle, ist der Diff Governance-signifikant.

Findet der Sensitive-Data-Detector Kontonummern im Feld, ist das Ergebnis ein Security-relevanter Vorschlag, der geprüft werden muss.

Die Harvesting-Plattform darf diese Änderungen nicht als gleichwertige Textupdates behandeln.

## Zuverlässigkeit ist Teil der Metadatenqualität

Ein Metadatenwert kann nicht vertrauenswürdiger sein als der Erfassungsprozess, der ihn erzeugt hat.

### Retries und Backoff

Wiederhole transiente Fehler wie:

- Netzwerkunterbrechung
- temporärer API-Fehler
- Throttling
- Erneuerung eines abgelaufenen Tokens
- kurze Nichtverfügbarkeit der Quelle

Verwende begrenzte Retries mit Backoff und Jitter.

Permanente Fehler dürfen nicht unbegrenzt wiederholt werden. Ungültige Credentials, nicht unterstützte Schemaversionen und verbotene Scopes benötigen Eingriff.

### Checkpoints und Idempotenz

Jeder inkrementelle Connector benötigt:

- dauerhaften Checkpoint
- Batch Identity
- idempotenten Write
- Replay Path
- Reconciliation Scan
- Checkpoint Ownership

Der Checkpoint wird erst committed, nachdem der Raw Batch dauerhaft gespeichert wurde.

### Freshness Monitoring

Definiere Freshness pro Connector und Metadatentyp.

Beispiele:

```text
Warehouse Schema: innerhalb von 6 Stunden erwartet
Transformationsartefakte: nach jedem Production Deployment erwartet
Pipeline Events: innerhalb von 5 Minuten erwartet
Usage Logs: täglich erwartet
Ownership Registry: innerhalb von 24 Stunden erwartet
```

Überwache:

- letzte erfolgreiche Erfassung
- letzte vollständige Erfassung
- Source Event Delay
- Processing Delay
- erfasste Datensätze
- erwartetes gegenüber beobachtetem Volumen
- aufeinanderfolgende Fehler
- ältesten ungelösten Quarantänedatensatz

Ein grüner Connector Process mit null erfassten Datensätzen kann trotzdem fehlerhaft sein.

### Failed-Record Quarantine

Ein kompletter Batch sollte nicht wegen eines fehlerhaften optionalen Datensatzes verworfen werden.

Quarantänisiere ungültige Records mit:

- Source Payload
- Validation Error
- Connector- und Batch-Identität
- Retry State
- zugewiesenem Owner
- First und Last Failure Time
- Resolution Status

Gleichzeitig dürfen Beziehungen mit ungültiger Pflichtidentität nicht veröffentlicht werden.

### Replay

Replay wird benötigt, wenn:

- Mapping-Logik geändert wird
- ein Parser korrigiert wurde
- ein Event Consumer nicht verfügbar war
- Regeln für kanonische Identität geändert werden
- Downstream Publication fehlgeschlagen ist
- eine Connector Regression korrigiert werden muss

Raw Retention und versionierte Mappings machen Replay möglich.

### Connector Ownership

Jeder produktive Connector benötigt zwei Arten von Ownership:

- technischen Owner für Authentifizierung, Code, Runtime und Incidents;
- Metadata Owner für Scope, Semantik, Freshness-Erwartung und Downstream-Nutzung.

Ohne Ownership bleiben Connector-Fehler Platform Noise, bis Nutzer veraltete Katalogseiten bemerken.

## Alternative Harvesting-Muster

### Catalog-led Scheduled Scanning

Ein Metadatenkatalog führt verwaltete oder eigene Scanner gegen registrierte Systeme aus.

Geeignet, wenn:

- ein breites Inventar das erste Ziel ist;
- Systeme stabile Read Interfaces anbieten;
- Near-Real-Time-Erfassung nicht erforderlich ist;
- das Team Connector Operations zentralisieren möchte.

Hauptrisiken:

- Connector-Abdeckung kann uneinheitlich sein;
- plattformnative Identifier werden möglicherweise intransparent transformiert;
- Raw Responses bleiben eventuell nicht verfügbar;
- Scan-Zeitpläne verbergen unterschiedliche Freshness;
- Custom Metadata benötigt weiterhin eigene Wege.

### CI-led Artifact Publication

Repositories veröffentlichen Metadatenartefakte während Validation, Build oder Deployment.

Geeignet, wenn:

- Transformations- und Semantic Definitions versioniert sind;
- Code Revision mit Metadaten verbunden bleiben muss;
- Änderungen vor Deployment geprüft werden sollen;
- Pre-Deployment Impact Analysis erforderlich ist.

Hauptrisiken:

- Runtime-only Changes bleiben unsichtbar;
- fehlgeschlagene oder umgangene CI-Pfade erzeugen Lücken;
- Artefakte unterschiedlicher Umgebungen können vermischt werden;
- gelöschte Repository-Objekte benötigen explizite Tombstones.

### Event-Bus Collection

Plattformen senden Deployment-, Run-, Schema-, Quality- und Governance-Events an einen gemeinsamen Bus.

Geeignet, wenn:

- viele Producer zuverlässige Events liefern können;
- geringe Latenz relevant ist;
- Replay und Schema Governance bereits vorhanden sind;
- Metadaten automatisierte Controls auslösen sollen.

Hauptrisiken:

- fehlende Producer erzeugen falsche Vollständigkeit;
- Event Contracts entwickeln sich weiter;
- doppelte und verspätete Events benötigen disziplinierte Behandlung;
- Current State muss aus Event History oder Compacted Views rekonstruiert werden.

### Repository-first Parsing

Ein Scanner parst Code und Konfiguration direkt aus Repositories.

Geeignet, wenn:

- Metadaten vor dem Deployment benötigt werden;
- Code die autoritative technische Definition ist;
- die Zielplattform nur schwache Lineage liefert;
- Pull-Request Validation wichtig ist.

Hauptrisiken:

- dynamisches Verhalten kann statisch nicht vollständig aufgelöst werden;
- Parser-Support unterscheidet sich nach Sprache und Dialekt;
- generierter Code kann vom committed Source abweichen;
- Repository-Präsenz beweist kein Production Deployment.

### Source-native Publication

Jedes Produkt oder jede Domäne veröffentlicht ein normalisiertes Metadatenpaket über einen definierten Vertrag.

Geeignet, wenn:

- Source Teams ihre eigenen Metadaten am besten verstehen;
- die Organisation föderierte Ownership verwendet;
- zentrale Teams nicht jeden Connector bauen sollen;
- gemeinsame Schemas und Validation durchgesetzt werden können.

Hauptrisiken:

- inkonsistente Implementierungsqualität;
- fehlender operativer Support;
- Contract Drift;
- duplizierte Publisher-Logik.

Dieses Muster funktioniert nur, wenn die Veröffentlichung von Metadaten als operative Produktverantwortung behandelt wird.

## Häufige Anti-Pattern

### User Interfaces scrapen, obwohl stabile Schnittstellen existieren

HTML-Seiten und Browser Network Calls sind keine belastbaren Verträge, sofern das Produkt sie nicht ausdrücklich unterstützt.

UI Scraping bricht, wenn:

- Labels geändert werden;
- Layouts verändert werden;
- Authentication Flows wechseln;
- Pagination virtualisiert wird;
- interne Endpoints geändert werden;
- Anti-Automation Controls eingeführt werden.

Bevorzuge dokumentierte APIs, Exporte, Kataloge, Artefakte, Events oder Repository-Formate.

Unsupported Scraping kann temporär bei Discovery helfen. Es darf keine unsichtbare produktive Abhängigkeit werden.

### Normalisierte Datensätze direkt in den finalen Katalog schreiben

Dadurch fehlen Raw Evidence, Replay und Diagnosefähigkeit.

Ein Mapping-Fehler des Connectors kann dann die einzige gespeicherte Darstellung beschädigen.

### Den neuesten Write als Wahrheit behandeln

Unterschiedliche Quellen besitzen unterschiedliche Autorität.

Ein neu erkannter Owner Name darf einen freigegebenen Ownership Record nicht nur deshalb überschreiben, weil sein Timestamp neuer ist.

### Für jede Änderung Full Scans ausführen

Häufige Full Scans können unnötige Last erzeugen und trotzdem keine geringe Latenz liefern.

Nutze inkrementelle Erfassung und Events, wo sie gerechtfertigt sind, und behalte periodische vollständige Reconciliation bei.

### Löschung aus einem fehlgeschlagenen oder partiellen Scan ableiten

Ein Berechtigungsverlust kann sonst eine gesamte Datenlandschaft scheinbar außer Betrieb setzen.

Verlange erfolgreiche Scope-Evidenz und Tombstones.

### Detector Output direkt in freigegebenen Governance State überführen

Automatische Detection sollte normalerweise Evidenz oder einen Vorschlag erzeugen.

Freigaberegeln hängen von Confidence, Policy, Kritikalität und accountable Review ab.

### Hochprivilegierte Connector Identities verwenden

Ein Metadata Scanner benötigt selten uneingeschränkten Datenzugriff.

Trenne Metadata Visibility, Log Access und Sample-Data Inspection. Vergib nur die für den definierten Scope erforderlichen Berechtigungen.

### Ohne Freshness Contract sammeln

Ein Connector, der „jede Nacht läuft“, definiert nicht, was Nutzer vertrauen können.

Lege erwartete Freshness, Vollständigkeit und Failure Response fest.

### Konflikte verbergen, damit die UI sauber aussieht

Konflikte sind Governance-Information.

Eine zentrale Plattform sollte widersprüchliche Werte, ihre Ursprünge und die Regel zur Ermittlung des angezeigten aktuellen Zustands offenlegen.

## Entscheidungshilfe

Für jede Metadatenquelle sollten die folgenden Fragen beantwortet werden.

### Schnittstelle und Autorität

1. Welches System erzeugt die ursprüngliche Evidenz?
2. Gibt es eine dokumentierte API, einen Katalog, Export, ein Artefakt oder Event?
3. Ist die Schnittstelle für produktive Automatisierung vorgesehen?
4. Welche Metadatendimensionen deckt sie tatsächlich ab?
5. Ist das Ergebnis autoritativ, deklariert, geparst, beobachtet, abgeleitet oder vorgeschlagen?

### Identität und Scope

6. Liefert die Quelle stabile Identifier?
7. Wie werden Umgebung, Tenant und Plattforminstanz repräsentiert?
8. Ist der zurückgelieferte Scope berechtigungsabhängig?
9. Wie wird Vollständigkeit gemessen?
10. Wie werden umbenannte und verschobene Assets erkannt?

### Freshness und Änderung

11. Wie schnell können sich die Metadaten ändern?
12. Verändert geringere Latenz eine Entscheidung oder Control?
13. Ist Full Scanning, inkrementelles Harvesting, Event Collection oder Streaming geeignet?
14. Welcher Checkpoint oder Event Identifier ist verfügbar?
15. Wie werden Löschungen bestätigt?
16. Wie erfolgt periodische Reconciliation?

### Reliability und Betrieb

17. Wer verantwortet den Connector?
18. Welche Fehler werden wiederholt?
19. Wo werden ungültige Records quarantänisiert?
20. Wie lange werden Raw Metadata aufbewahrt?
21. Kann jeder Batch oder jedes Event erneut verarbeitet werden?
22. Welche Freshness- und Volumenmetriken werden überwacht?
23. Was geschieht, wenn sich das Source Schema ändert?

### Security und Governance

24. Welche Berechtigungen benötigt der Connector?
25. Enthält das Payload sensitive operative oder Identity-Daten?
26. Welche Werte dürfen normalisiert, redigiert oder gehasht werden?
27. Welche erfassten Werte dürfen automatisch veröffentlicht werden?
28. Welche Änderungen benötigen Review oder Freigabe?
29. Welche Quelle gewinnt bei Konflikten?
30. Ist jeder veröffentlichte Wert auf Raw Evidence zurückführbar?

Die Antworten bestimmen, ob ein Connector nur Daten extrahiert oder als governtes Metadatenprodukt betrieben wird.

## Zentrale Empfehlungen

1. Erfasse technische und operative Metadaten automatisiert, sobald eine unterstützte Quellschnittstelle vorhanden ist.
2. Nutze Datenbankkataloge für physische Struktur, APIs für produktnative Objekte, Artefakte für Build Context, Logs für beobachtetes Verhalten, Events für zeitnahe Zustandswechsel und Parser für code-native Definitionen.
3. Dokumentiere Methode und Freshness jedes erfassten Metadatenwertes.
4. Erhalte Raw Source Payloads vor der Normalisierung.
5. Verwende stabile native Identifier in Kombination mit Source Instance, Umgebung und Asset-Typ.
6. Erhalte Originalschreibweise, Zeitstempel und Source Fields zusätzlich zu normalisierten Werten.
7. Trenne Source Event Time, Collection Time, Processing Time und Effective Time.
8. Versioniere Mappings, Schemas und Connector Code.
9. Behandle Scheduled Scans, inkrementelles Harvesting, Events und Streams als komplementäre Muster.
10. Behalte periodische Full Reconciliation auch bei Low-Latency-Erfassung bei.
11. Gestalte Ingestion idempotent und replay-fähig.
12. Speichere ungelöste Beziehungen, statt sie still zu verwerfen.
13. Unterscheide deklarierte, geparste, kompilierte, beobachtete, abgeleitete, vorgeschlagene und freigegebene Metadaten.
14. Erhalte Run- und Quality-Historie, statt nur den letzten Status zu speichern.
15. Erkenne Schema Drift und Governance-signifikante Änderungen durch klassifizierte Metadata Diffs.
16. Verwende Tombstones und Bestätigungsregeln für gelöschte Assets.
17. Überwache Connector Freshness, Vollständigkeit, Volumen und aufeinanderfolgende Fehler.
18. Quarantänisiere ungültige Records mit verantwortlicher Bearbeitung.
19. Weise jedem produktiven Connector einen Technical Owner und einen Metadata Owner zu.
20. Verwende Least-Privilege Service Identities und dokumentiertes Secret Management.
21. Bevorzuge unterstützte APIs, Exporte, Artefakte und Events gegenüber UI Scraping.
22. Verwende Last-Write-Wins niemals als universelle Konfliktregel.
23. Überführe automatische Detection nicht ohne explizite Regel direkt in freigegebenen Governance State.
24. Beginne mit einem wichtigen Datenprodukt und beweise Schema-, Lineage-, Runtime- und Usage-Erfassung Ende zu Ende.
25. Behandle Metadata Harvesting als produktive Datenpipeline und nicht als nebensächliches Katalogfeature.

## Der nächste Schritt: Metadaten schreiben, die Menschen und Maschinen verstehen

Automatisiertes Harvesting löst das Volumen- und Freshness-Problem für technischen und operativen Kontext.

Es erzeugt nicht automatisch verständliche Metadaten.

Ein Scanner kann feststellen, dass `sales_amount` ein `DECIMAL(18,2)` ist. Ein Parser kann die Upstream-Felder extrahieren. Ein Runtime Event kann belegen, dass das Modell ausgeführt wurde. Ein Query Log kann zeigen, dass Reports das Feld verwenden.

Keine dieser Fakten erklärt allein präzise, was das Feld bedeutet, welche Geschäftssituationen enthalten sind, welche Ausschlüsse gelten, welche Granularität es repräsentiert oder wie ein AI-System es interpretieren soll.

Der nächste Part, **Metadaten schreiben, die Menschen und Maschinen verstehen**, konzentriert sich auf Beschreibungen, Definitionen, Beispiele, Constraints und strukturierten Kontext, die für Business User, Engineers, Governance-Prozesse und AI-Systeme gleichermaßen nutzbar bleiben.
