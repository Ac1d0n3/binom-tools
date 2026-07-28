---
title: Ein einheitliches Metadatenmodell aufbauen — Unterschiedliche Metadaten verbinden, ohne Herkunft oder Bedeutung zu verlieren
description: Eine praxisnahe Architektur, um quellnative Metadaten, stabile Asset-Identitäten, explizite Beziehungen, Versionen, Provenance, Freigabestatus und Konfliktregeln in einem nutzbaren Modell zu verbinden.
category: Data Governance
tags:
  - metadata
  - metadata-model
  - metadata-governance
  - data-catalog
  - metadata-graph
  - asset-identity
  - metadata-provenance
  - metadata-versioning
  - data-lineage
  - business-glossary
  - data-products
  - semantic-layer
  - ai-ready-metadata
products:
  - snowflake
  - dbt
  - qlik
  - fabric
  - databricks
  - powerbi
order: -1
author: Thomas Lindackers
series: metadata-deep-dive
seriesPart: 7
seriesTitle: MetaData Deep Dive
hero: images/playbooks/build-a-unified-metadata-model-hero.png
publishedAt: 2026-06-26 10:00
---

## Metadaten werden fragmentiert, bevor sie vereinheitlicht werden

Eine moderne Datenlandschaft besitzt selten nur ein Metadatenmodell.

Eine Datenbank beschreibt Catalogs, Schemas, Tabellen, Felder, Keys und Datentypen. Eine Orchestrierungsplattform beschreibt Workflows, Tasks, Zeitpläne und Runs. Transformationscode beschreibt Modelle, Tests und Abhängigkeiten. Ein Semantic Layer beschreibt Dimensionen, Measures und Beziehungen. Eine BI-Plattform beschreibt Applications, Datasets, Reports und Consumer. Eine Governance-Plattform ergänzt Begriffe, Owner, Policies, Klassifikationen und Freigaben. AI-Plattformen bringen Features, Training Datasets, Modelle, Prompts, Evaluierungen und Deployment-Kontext hinzu.

Jedes dieser Modelle ist innerhalb seines Systems sinnvoll. Das Problem beginnt, wenn eine Organisation sie verbinden möchte.

Dasselbe logische Dataset kann mehrfach erscheinen:

```text
CRM-Quelltabelle
→ replizierte Raw-Tabelle
→ Transformationsmodell
→ semantische Tabelle
→ BI-Objekt
→ Catalog Asset
```

Namen können abweichen. Identifier können neu erzeugt werden. Entwicklungs-, Test- und Produktionsumgebungen können ähnliche Objekte enthalten. Eine Tabelle kann umbenannt werden und denselben Zweck behalten. Eine Kopie kann identisch aussehen, aber eine andere operative Ownership besitzen. Ein abgeleitetes Modell kann die meisten Quellfelder übernehmen und trotzdem Grain und Bedeutung verändern.

Ein schwaches zentrales Design reagiert meist auf eine von zwei Arten:

- jedes Quellobjekt wird als unabhängiger Datensatz gespeichert und User müssen die Verbindungen selbst erraten;
- jedes Objekt wird in einen generischen Asset-Datensatz abgeflacht und verliert genau die Unterschiede, die seine Quellmetadaten wertvoll machen.

Beide Ansätze erzeugen einen Katalog, der Namen durchsuchen kann, aber Fragen zu Identität, Lineage, Ownership, Änderungen oder Autorität nicht zuverlässig beantwortet.

> **Ein einheitliches Metadatenmodell benötigt stabile Identitäten, explizite Beziehungstypen, Versionen und Provenance. Es sollte quellenspezifische Modelle verbinden, ohne jedes Konzept auf einen anonymen Datensatz zu reduzieren.**

Vereinheitlichung bedeutet nicht, dass jede Plattform intern dasselbe Schema verwenden muss. Sie bedeutet, eine kontrollierte Darstellung zu schaffen, in der lokale Modelle gemappt, verglichen und traversiert werden können, während ihre Herkunft sichtbar bleibt.

## Das Kernmodell ist ein Graph aus typisierten Assets und Beziehungen

Ein brauchbares Metadatenmodell beginnt mit zwei zentralen Konzepten:

```text
Typisiertes Asset
+
Typisierte Beziehung
```

Ein Asset repräsentiert etwas, das identifiziert, beschrieben, governed, versioniert oder mit einem anderen Objekt verbunden werden kann. Eine Beziehung drückt eine konkrete Aussage zwischen zwei Assets aus.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/build-a-unified-metadata-model-img1-de.png"
        alt="Zentraler Metadatengraph, der Data Assets, Prozesse, fachliche Konzepte, Governance-Objekte, Consumer und AI Assets über explizite Beziehungstypen verbindet"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein einheitliches Modell sollte den Unterschied zwischen Systemen, Datasets, Feldern, Prozessen, Begriffen, Policies, Personen, Reports und AI Assets erhalten und ihre Beziehungen traversierbar machen.
    </figcaption>
</figure>

### Data Assets

Data Assets beschreiben gespeicherte oder übertragene Datenstrukturen:

- System
- Database
- Schema
- Tabelle
- View
- Feld
- Datei
- Object-Store-Pfad
- Event Topic
- Message Schema
- Dataset

Hierarchische Beziehungen können lauten:

```text
System enthält Database
Database enthält Schema
Schema enthält Tabelle
Tabelle enthält Feld
Topic entspricht Message Schema
Dataset materialisiert Tabelle
```

Eine Hierarchie ist nützlich, aber nicht ausreichend. Ein Feld kann zusätzlich einen Business Term implementieren, zu einem KPI beitragen, als personenbezogen klassifiziert und von einem Modell konsumiert werden.

### Prozesse

Prozesse beschreiben Aktionen, die Daten erzeugen, bewegen, validieren oder bereitstellen:

- Pipeline
- Job
- Task
- Transformation
- Query
- Synchronisierung
- Quality Check
- Deployment

Typische Beziehungen sind:

```text
Pipeline liest Tabelle
Pipeline schreibt Tabelle
Transformation leitet Feld ab
Job führt Task aus
Query konsumiert Dataset
Quality Check validiert Feld
Deployment veröffentlicht Semantic Model
```

Einen Prozess als Freitext in einem Tabellendatensatz zu speichern erschwert Lineage und operative Analyse. Prozesse sollten eigenständige Assets sein, wenn sie eine eigene Identität, einen Lifecycle, einen Owner oder Ausführungsevidenz besitzen.

### Fachliche Assets

Fachliche Assets beschreiben Bedeutung und gelieferten Nutzen:

- Domain
- Subdomain
- Business Term
- KPI
- Metric
- Business Event
- Data Product
- Use Case

Typische Beziehungen sind:

```text
Feld implementiert Business Term
KPI wird aus Feld berechnet
Asset gehört zu Domain
Tabelle gehört zu Data Product
Data Product liefert Use Case
Business Term ist Synonym von Business Term
KPI konkretisiert Metric
```

Ein Business Term ist kein Feld. Ein KPI ist kein Report-Visual. Ein Data Product ist nicht nur eine Tabellensammlung. Das Modell muss diese Unterschiede erhalten.

### Governance Assets

Governance Assets beschreiben Verantwortung und Kontrolle:

- Person
- Team
- Rolle
- Owner-Zuordnung
- Steward-Zuordnung
- Policy
- Klassifikation
- Retention Rule
- Access Rule
- Quality Rule
- Freigabe
- Ausnahme

Typische Beziehungen sind:

```text
Rolle verantwortet Data Product
Person erfüllt Rolle
Policy regelt Asset
Klassifikation gilt für Feld
Quality Rule validiert KPI
Ausnahme überschreibt Policy für Scope
Freigabe akzeptiert Metadatenversion
```

Eine Person und eine Rolle sollten nicht als dasselbe Objekt dargestellt werden. Personen wechseln. Rollen und Verantwortlichkeiten sollten stabil genug bleiben, um sie neu zuzuweisen, ohne jede Beziehung umzuschreiben.

### Consumption und AI Assets

Consumer und AI Assets umfassen:

- Report
- Dashboard
- Application
- API
- Semantic Model
- Feature
- Feature Set
- Training Dataset
- Evaluation Dataset
- Modell
- Prompt Template
- Deployment

Typische Beziehungen sind:

```text
Report konsumiert Semantic Model
Application ruft API auf
Feature wird aus Feld abgeleitet
Training Dataset enthält Feature
Modell wird auf Dataset trainiert
Modell wird mit Evaluation Dataset bewertet
Deployment stellt Modell bereit
Prompt referenziert freigegebenen Kontext
```

Diese Assets ermöglichen Impact Analysis über das Warehouse hinaus. Gleichzeitig verhindern sie, dass AI-Kontext zu einem undokumentierten Seitenkanal wird, der von Data Governance getrennt ist.

## Kanonische Asset-Typen sollten stabil, aber nicht universell sein

Ein kanonisches Modell stellt gemeinsame Kategorien bereit, mit denen plattformübergreifende Fragen beantwortet werden können. Es sollte nicht versuchen, jede Quelleigenschaft als zentrales Enterprise-Feld nachzubauen.

Eine sinnvolle Trennung lautet:

```text
Kanonischer Kern
Quellnative Erweiterung
```

Der kanonische Kern enthält Attribute, die allgemein nutzbar sind:

```yaml
asset:
  asset_id: asset:warehouse:prod:sales:fct_order_line
  asset_type: table
  display_name: fct_order_line
  platform: cloud_warehouse
  environment: prod
  lifecycle_status: active
  current_version: 12
```

Die quellnative Erweiterung bewahrt Attribute, die innerhalb des Ursprungssystems relevant sind:

```yaml
source_representation:
  source_system: warehouse_a
  native_id: 7f6b9d20
  qualified_name: PROD.SALES.FCT_ORDER_LINE
  native_type: TRANSIENT_TABLE
  native_attributes:
    change_tracking: false
    retention_days: 1
    clustering_expression:
      - BOOKING_DATE
```

Das kanonische Modell kann das Objekt als `table` klassifizieren, ohne die Information zu löschen, dass die Quellplattform es als `TRANSIENT_TABLE` bezeichnet.

Diese Trennung verhindert zwei häufige Fehler:

- das Enterprise-Modell wächst so lange, bis es jede produktspezifische Option nachbildet;
- lokale Attribute werden verworfen, weil sie nicht in ein kleines generisches Schema passen.

Kanonische Typen sollten gemeinsame Navigation und Kontrolle ermöglichen. Quellnative Erweiterungen sollten Fidelity und Troubleshooting-Wert erhalten.

## Stabile Identität ist das Fundament

Namen sind für Discovery hilfreich. Als primäre Identifier sind sie schwach.

Ein Anzeigename kann sich ändern. Derselbe Name kann in mehreren Schemas vorkommen. Entwicklungs- und Produktionsobjekte können in getrennten Accounts identische Qualified Names besitzen. Ein kopiertes Dataset kann seinen Namen behalten und trotzdem zu einem anderen operativen Objekt werden. Ein umbenanntes Objekt kann dasselbe logische Asset bleiben.

Identity Resolution sollte mehrere Signale gemeinsam bewerten.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/build-a-unified-metadata-model-img2-de.png"
        alt="Identity Resolver, der Source-, Raw-, Transformations-, semantische, BI- und Catalog-Repräsentationen über Plattform, Umgebung, Qualified Name, stabilen Source ID, Version und Lineage-Evidenz verbindet"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Display Names unterstützen die Suche. Identitätsentscheidungen benötigen Plattform, Umgebung, Qualified Names, stabile Quell-Identifier, Versionen und Beziehungsevidenz.
    </figcaption>
</figure>

Ein belastbarer Identity Key berücksichtigt normalerweise:

```text
Plattform
+ Account oder Tenant
+ Umgebung
+ Namespace
+ Qualified Name
+ stabiler nativer Identifier
+ Objekttyp
+ Version oder Effective Interval
```

Zusätzliche Evidenz kann umfassen:

- Erstellungs- und Änderungshistorie
- Kontinuität der Lineage
- Deployment-Manifest-Mappings
- Repository-Identifier
- Connector-spezifische Aliase
- freigegebene manuelle Mappings
- Checksums technischer Strukturen
- Kontinuität der Ownership
- Synchronisierungskonfiguration

Das Ergebnis der Identity Resolution sollte nicht auf `Match` oder `No Match` beschränkt sein.

Sinnvolle Resultate sind:

```text
Dasselbe logische Asset
Abgeleitetes Asset
Replikat
Version eines Assets
Alias eines Assets
Durch Asset ersetzt
Ungelöster Kandidat
```

Diese Beziehungstypen verhindern falsche Gleichsetzung.

### Dasselbe logische Asset

Zwei Repräsentationen beziehen sich auf dasselbe governte Objekt.

Beispiel:

```text
Warehouse-Tabellenrepräsentation
↔
Catalog-Repräsentation dieser Warehouse-Tabelle
```

Der Catalog-Datensatz ist kein neues Dataset. Er ist eine weitere Repräsentation desselben logischen Assets.

### Abgeleitetes Asset

Ein Asset wird durch Transformation aus einem anderen erzeugt.

Beispiel:

```text
raw.crm_customer
→ abgeleitet zu
mart.customer_profile
```

Die Datensätze können sich überschneiden. Die Assets besitzen trotzdem unterschiedliche Granularität, Semantik, Lifecycle und Ownership.

### Replikat

Ein Asset ist eine operative Kopie eines anderen.

Beispiel:

```text
CRM.customer
→ repliziert als
raw.crm_customer
```

Ein Replikat kann die Struktur fast vollständig erhalten und trotzdem einen anderen Speicherort, Freshness-Vertrag und ein anderes Access Model besitzen.

### Ungelöster Kandidat

Evidenz deutet auf eine mögliche Beziehung hin, reicht aber nicht für eine Freigabe.

Dieser Zustand ist unverzichtbar. Erzwungene Matches erzeugen mehr Schaden als ein offen ungelöster Kandidat.

## Identifier als Aliase erhalten, nicht ersetzen

Ein einheitliches Asset sollte jeden relevanten lokalen Identifier mit seiner Herkunft bewahren.

```yaml
asset_identity:
  canonical_id: asset:customer_master:prod
  aliases:
    - system: crm
      environment: prod
      native_id: entity-4921
      qualified_name: crm.customer
      valid_from: 2024-02-01
      valid_to: null
    - system: warehouse
      environment: prod
      native_id: 7f6b9d20
      qualified_name: raw.crm_customer
      relationship: replica
      valid_from: 2024-02-02
      valid_to: null
    - system: catalog
      environment: prod
      native_id: 983441
      qualified_name: Customer Master
      relationship: representation
      valid_from: 2025-01-12
      valid_to: null
```

Der kanonische Identifier unterstützt interne Referenzen. Er darf Quell-Identifier nicht überschreiben.

Lokale Identifier werden benötigt für:

- Connector Updates
- API-Aufrufe zurück in Quellsysteme
- Change Detection
- Deletion Handling
- Troubleshooting
- Synchronisierung
- Audit
- Migration
- Versionsvergleich

Ein einheitliches Modell, das nicht auf das exakte Quellobjekt zurückverweisen kann, ist operativ nicht belastbar.

## Asset-Identität und Asset-Versionen trennen

Ein Asset und ein Zustand dieses Assets sind nicht dasselbe Konzept.

Eine Tabelle kann ihre logische Identität behalten, während sich Schema, Beschreibung, Owner oder Klassifikation ändern. Ein KPI kann denselben Namen behalten, obwohl sich seine Formel ändert. Eine Policy kann von Draft zu Approved wechseln und später ersetzt werden.

Ein praktisches Modell trennt:

```text
Asset-Identität
Asset-Version
Attribut-Assertion
Beziehungsversion
```

Beispiel:

```yaml
asset:
  asset_id: asset:kpi:monthly_net_revenue
  type: kpi

asset_version:
  version_id: asset:kpi:monthly_net_revenue:v5
  version_number: 5
  valid_from: 2026-07-01T00:00:00Z
  valid_to: null
  status: approved
  replaces: asset:kpi:monthly_net_revenue:v4
```

Auch Beziehungen benötigen Historie:

```yaml
relationship:
  relationship_id: rel:184288
  type: calculated_from
  from_asset: asset:kpi:monthly_net_revenue
  to_asset: asset:column:net_sales_amount
  valid_from: 2026-07-01T00:00:00Z
  valid_to: null
  approval_status: approved
```

Wenn der KPI später ein anderes Feld verwendet, sollte die vorherige Edge geschlossen und nicht gelöscht werden.

Historische Beziehungen ermöglichen Fragen wie:

- Welche Felder unterstützten den KPI am Ende des vorherigen Quartals?
- Welcher Owner gab die Definition frei, die in einem früheren Report verwendet wurde?
- Seit wann gilt eine sensitive Klassifikation?
- Welche Reports konsumierten die abgekündigte Tabelle vor der Migration?
- Welche Modellversion wurde auf einer bestimmten Dataset-Version trainiert?

Ohne Beziehungshistorie bleibt Impact Analysis auf die Gegenwart begrenzt.

## Metadatenwerte als Assertions mit Provenance modellieren

Ein Attribut wie `description`, `owner` oder `classification` kann Werte aus mehreren Quellen erhalten.

Eine Source API kann eine technische Beschreibung liefern. Ein Repository kann eine Model Description bereitstellen. Ein Detector kann eine Sensitive-Data-Kategorie vorschlagen. Ein Steward kann eine fachliche Definition freigeben. Eine Policy Engine kann eine Retention-Anforderung ableiten.

Diese Werte sollten sich nicht still gegenseitig überschreiben.

Sie werden als Assertions dargestellt:

```yaml
assertion:
  assertion_id: assertion:99218
  subject: asset:column:customer_email
  predicate: classification
  value: confidential_pii
  source:
    system: classifier_service
    method: detected
    model_version: pii-detector-4.2
  confidence: 0.94
  workflow_status: proposed
  observed_at: 2026-07-23T08:30:00Z
  valid_from: 2026-07-23T08:30:00Z
  valid_to: null
```

Eine später freigegebene Assertion kann parallel bestehen:

```yaml
assertion:
  subject: asset:column:customer_email
  predicate: classification
  value: confidential_pii
  source:
    system: governance_workflow
    method: declared
    supplied_by: role.customer_data_steward
  workflow_status: approved
  approved_by: role.customer_data_owner
  approved_at: 2026-07-23T14:10:00Z
```

Dieses Modell unterscheidet Evidenz von Autorität.

## Raw, normalisierte und freigegebene Metadaten sind unterschiedliche Schichten

Eine belastbare Architektur behandelt Ingestion, Normalisierung und Governance-Freigabe nicht als einen einzigen Schritt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/build-a-unified-metadata-model-img3-de.png"
        alt="Drei Metadatenschichten mit unveränderten Raw Source Metadata, normalisierten kanonischen Metadaten und freigegebenen governten Metadaten sowie den Zuständen Proposed, Rejected und Deprecated"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Raw, normalisierte und freigegebene Metadaten erfüllen unterschiedliche Aufgaben. Jede Schicht muss Provenance zur Originalquelle und zum Transformationsschritt erhalten.
    </figcaption>
</figure>

### Raw Source Metadata

Die Raw-Schicht speichert das Connector-Ergebnis unverändert:

- ursprünglicher Payload
- ursprüngliche Identifier
- native Feldnamen
- Source Timestamps
- Connector-Version
- Erfassungszeit
- Request Scope
- Response Status

Die Raw-Schicht unterstützt Replay, Debugging und spätere Remappings. Sie ist normalerweise nicht die primäre User-Darstellung.

### Normalisierte Metadaten

Die normalisierte Schicht mappt Quellinhalte auf kanonische Typen und Felder:

- kanonischer Asset-Typ
- standardisierte Timestamps
- normalisierte Umgebungsnamen
- aufgelöste Identifier-Kandidaten
- gemappte Beziehungstypen
- Source Aliases
- geparste native Attribute

Die Normalisierung sollte deterministisch und versioniert sein. Die Plattform muss erklären können, welche Regel einen Raw-Wert transformiert hat.

### Freigegebene Metadaten

Die freigegebene Schicht enthält governte Werte und Beziehungen:

- akzeptierte Beschreibungen
- validierte Ownership
- freigegebene Klassifikationen
- kuratierte Business-Term-Mappings
- akzeptierte Data-Product-Zugehörigkeit
- zertifizierte KPI-Definitionen
- freigegebene Policy-Gültigkeit

Freigegebene Metadaten können als bevorzugte Sicht veröffentlicht werden. Sie sollten weiterhin auf Evidenz und Quellwerte hinter der Entscheidung verweisen.

### Proposed, Rejected und Deprecated

Nicht jeder Wert wird freigegeben.

Ein vollständiger Workflow benötigt mindestens:

```text
Raw
Normalized
Proposed
Approved
Rejected
Deprecated
```

`Rejected` bedeutet nicht gelöscht. Ablehnungen sollten Proposal, Evidenz, Reviewer und Begründung erhalten.

`Deprecated` bedeutet nicht historisch ungültig. Abgekündigte Werte und Beziehungen können weiterhin erforderlich sein, um frühere Entscheidungen und Assets zu interpretieren.

## Konfliktauflösung benötigt explizite Präzedenzregeln

Konflikte sind in einem einheitlichen Modell normal.

Beispiele:

- Source Description unterscheidet sich von einer durch Stewards freigegebenen Definition;
- Detector schlägt `PII` vor, die freigegebene Klassifikation lautet jedoch `internal identifier`;
- zwei Systeme nennen unterschiedliche Owner;
- ein lokaler Begriff wird zwei Enterprise Terms zugeordnet;
- Lineage Parser und beobachtete Runtime liefern unterschiedliche Edges;
- ein kopiertes Dataset übernimmt eine Policy, die für den abgeleiteten Output nicht gelten sollte.

Eine Conflict Engine sollte Folgendes bewerten:

```text
Attributtyp
+ Autorität der Quelle
+ Methode
+ Scope
+ Umgebung
+ Effective Time
+ Freigabestatus
+ Confidence
+ expliziter Override
```

Präzedenz sollte pro Attribut- oder Beziehungstyp definiert werden, nicht als universelle Regel.

Ein sinnvolles Muster lautet:

```text
Freigegebener Target Override
> freigegebene Transformationsregel
> freigegebene Source Declaration
> propagierte Metadaten
> Detection Proposal
```

Für technische Struktur kann die Quellplattform autoritativ sein. Für fachliche Definitionen kann ein freigegebener Steward-Workflow Vorrang besitzen. Bei Lineage kann beobachtete Runtime-Evidenz die deklarierte Design Lineage ergänzen, aber nicht zwingend ersetzen.

## Vererbte Metadaten auflösen, ohne Unsicherheit zu verstecken

Metadatenvererbung wird schwierig, wenn ein abgeleitetes Asset mehrere Inputs kombiniert.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/build-a-unified-metadata-model-img4-de.png"
        alt="Resolver, der vertrauliche E-Mail- und Telefon-Inputs mit einem internen Customer Identifier über Lineage, Transformationsregeln, Target Overrides und Konfliktregeln kombiniert"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Propagierte Klassifikationen benötigen Lineage, transformationsbewusste Regeln und explizite Präzedenz. Konflikte sollten ungelöst bleiben, wenn die vorhandene Evidenz nicht ausreicht.
    </figcaption>
</figure>

Betrachten wir ein abgeleitetes Feld:

```sql
coalesce(email, phone, customer_id) as contact_reference
```

Die Inputs tragen unterschiedliche Metadaten:

```text
email        → confidential PII
phone        → confidential PII
customer_id  → internal identifier
```

Ein Resolver bewertet:

```text
Column Lineage
+ Transformationsregel
+ freigegebener Target Override
+ Konfliktregel
```

Mögliche Ergebnisse sind:

- aufgelöste Metadaten;
- vorgeschlagene Metadaten;
- ungelöst — Review erforderlich.

Das System sollte nicht automatisch den am wenigsten restriktiven Input wählen. Es sollte ebenso wenig annehmen, dass jeder Output jede Quellklassifikation unverändert übernimmt.

Eine Transformationsregel kann festlegen:

```text
Wenn ein möglicher Output direkte Kontaktdaten offenlegt,
confidential PII vorschlagen.
```

Ein freigegebener Target Override kann die Klassifikation bestätigen. Wenn die Transformation Werte hasht oder tokenisiert, kann eine andere Regel gelten. Regel und Evidenz müssen sichtbar bleiben.

## Das einfachste tragfähige einheitliche Modell

Ein Team kann starten, ohne sofort einen vollständigen Enterprise Knowledge Graph zu implementieren.

Ein praktisches Minimum besteht aus sechs Komponenten.

### 1. Eine stabile Asset Registry

Erzeuge für jedes Asset im Scope einen kanonischen Identifier und bewahre seine Source Aliases.

Minimale Felder:

```text
kanonischer Asset ID
Asset-Typ
Plattform
Umgebung
Qualified Name
nativer ID
Lifecycle Status
aktuelle Version
```

### 2. Ein kontrolliertes Typvokabular

Definiere eine kleine Menge von Asset- und Beziehungstypen, die für die ersten Use Cases benötigt werden.

Beispielhafte Asset-Typen:

```text
System
Dataset
Tabelle
Feld
Pipeline
Transformation
Business Term
KPI
Data Product
Policy
Person
Rolle
Report
Modell
```

Beispielhafte Beziehungstypen:

```text
enthält
liest
schreibt
leitet ab
implementiert
berechnet aus
gehört zu
wird konsumiert von
gehört zu Owner
wird geregelt durch
wird validiert durch
wurde trainiert auf
ersetzt
repliziert
```

Erzeuge nicht frühzeitig Dutzende fast identischer Beziehungstypen, bevor Governance-Regeln existieren.

### 3. Raw- und Normalized Storage

Bewahre Connector Payloads und erstelle eine deterministische normalisierte Repräsentation. Speichere die Mapping-Version jeder Ingestion.

### 4. Versionierte Assertions

Speichere Beschreibungen, Klassifikationen, Ownership und ähnliche Werte als Assertions mit Quelle, Methode, Status und Effective Time.

### 5. Beziehungshistorie

Versioniere relevante Beziehungen, anstatt sie zu überschreiben. Mindestens Lineage, Ownership, Policy, Term und Consumer Changes sollten erhalten bleiben.

### 6. Search, Traversal und API Access

Stelle drei Zugriffsmuster bereit:

```text
Suche über Namen, Aliase und Beschreibungen
Traversal typisierter Beziehungen
Lesen und Schreiben über governte APIs
```

Search findet wahrscheinliche Einstiegspunkte. Graph Traversal beantwortet zusammenhängende Fragen. APIs machen das Modell operativ.

## Alternative Implementierungsmuster

Das logische Modell verlangt keine bestimmte Speichertechnologie.

### Relationaler Kern mit Beziehungstabellen

Eine relationale Implementierung kann folgende Tabellen verwenden:

```text
assets
asset_versions
source_aliases
assertions
relationships
relationship_versions
approvals
```

Vorteile:

- vertrautes operatives Modell;
- starke Transaktionen und Constraints;
- unkompliziertes Reporting;
- für moderate Graph-Tiefe gut beherrschbar.

Grenzen:

- rekursive Traversals können komplex werden;
- stark vernetzte Exploration kann spezielle Indizes benötigen;
- Schemaänderungen können bei vielen neuen Beziehungstypen langsamer werden.

### Graph-native Metadata Store

Eine Graph Database kann Assets und Beziehungen direkt darstellen.

Vorteile:

- natürliche Multi-Hop-Traversal;
- flexible Erweiterung von Beziehungen;
- gute Eignung für Lineage, Impact und semantische Navigation.

Grenzen:

- operative und analytische Skills sind möglicherweise weniger verbreitet;
- Versionierung und Freigabeworkflows benötigen weiterhin explizites Design;
- Graph Storage löst Identity und Provenance nicht automatisch.

Eine Graph Database ersetzt kein Metadatenmodell. Ein schwach typisierter Graph verschiebt Mehrdeutigkeit lediglich in Nodes und Edges.

### Event-Sourced Metadata Model

Ein Event-Sourced-Ansatz speichert Änderungen wie:

```text
AssetDiscovered
AssetRenamed
RelationshipProposed
ClassificationApproved
OwnerChanged
AssetDeprecated
```

Vorteile:

- vollständige Historie;
- Replay und Rekonstruktion;
- starke Auditierbarkeit.

Grenzen:

- höhere Implementierungskomplexität;
- Current-State-Projections müssen gepflegt werden;
- Event-Semantik benötigt strikte Governance.

### Hybride Architektur

Viele Organisationen verwenden einen hybriden Ansatz:

```text
Raw Object Storage
+ relationaler Control Store
+ Search Index
+ Graph Projection
+ API Layer
```

Der Raw Store bewahrt Quelltreue. Der relationale Store verwaltet Identity, Workflow und Versionen. Der Search Index unterstützt Discovery. Die Graph Projection ermöglicht Traversal. Der API Layer stellt einen stabilen Vertrag bereit.

Das Modell sollte konsistent bleiben, auch wenn mehrere physische Stores verwendet werden.

## Konkretes Beispiel: ein Customer Dataset in sechs Systemen

Angenommen, ein Customer Object erscheint in folgenden Systemen:

```text
CRM: CUSTOMER
Warehouse Raw: RAW_CRM_CUSTOMER
dbt: STG_CUSTOMER
Semantic Model: DIM_CUSTOMER
BI Application: Customer Analysis
Catalog: Customer Master
```

Ein schwacher Katalog erzeugt sechs unabhängige Datensätze oder führt alle sechs in einem Datensatz namens `Customer` zusammen.

Ein einheitliches Modell stellt die Unterschiede dar:

```text
CRM.CUSTOMER
  └─ repliziert als → RAW_CRM_CUSTOMER
       └─ transformiert zu → STG_CUSTOMER
            └─ leitet ab → DIM_CUSTOMER
                 └─ wird konsumiert von → Customer Analysis
```

Die Catalog-Repräsentation verweist auf diese Assets, anstatt selbst zu einem weiteren Data Asset zu werden.

Fachlicher Kontext wird separat verknüpft:

```text
DIM_CUSTOMER
  ├─ implementiert → Business Term: Customer
  ├─ gehört zu → Data Product: Customer 360
  ├─ gehört zu Owner → Rolle: Customer Data Owner
  ├─ wird geregelt durch → Policy: Customer Data Handling
  └─ wird konsumiert von → Report: Customer Analysis
```

Quellnative Identifier bleiben an jedem technischen Asset erhalten. Der Enterprise Term löscht lokale Begriffe wie `Account`, `Debtor` oder `Party` nicht; sie können als Synonyme oder Domain-spezifische Konzepte mit expliziten Mappings bestehen bleiben.

Wenn `STG_CUSTOMER` in `STG_CUSTOMER_CURRENT` umbenannt wird, können Repository-Identifier und Deployment Mapping ermöglichen, die logische Identität zu erhalten und den vorherigen Qualified Name als historischen Alias hinzuzufügen.

Wenn sich `DIM_CUSTOMER` von einer Zeile pro Customer auf eine Zeile pro Customer und Legal Entity verändert, kann dies je nach Identity Policy eine neue Asset-Version oder eine neue Asset-Identität erfordern. Die Entscheidung sollte explizit sein, weil sich der Grain geändert hat.

## Häufige Anti-Patterns

### Ein generischer `asset`-Datensatz

Jedes Objekt erhält dieselben Felder und typspezifischer Kontext wird in unstrukturiertem JSON gespeichert.

Das wirkt flexibel, schwächt aber meist Validierung, Search und Governance. Eine Policy, Person, Tabelle und ein KPI besitzen nicht denselben Lifecycle und dieselben Pflichtbeziehungen.

### Identität über Display Name

Objekte mit demselben Namen werden zusammengeführt oder umbenannte Objekte dupliziert.

Display Names sollten für Discovery indexiert, aber nie als ausreichende Identity Evidence behandelt werden.

### Stille Last-Write-Wins-Updates

Das letzte Connector- oder User-Update ersetzt den aktuellen Wert, ohne Quelle, Freigabe oder vorherigen Zustand zu erhalten.

Das zerstört Auditierbarkeit und macht Konflikte unsichtbar.

### Lineage als Freitext

Ein Tabellendatensatz enthält eine Beschreibung wie `loaded from CRM`.

Impact Analysis benötigt explizite Prozess- und Asset-Beziehungen statt Prosa.

### Freigegebene Werte überall kopieren

Eine freigegebene Definition oder Klassifikation wird physisch in jedes verbundene Objekt kopiert.

Kopien werden veraltet und erschweren die Unterscheidung zwischen Vererbung, Referenz und lokalem Override.

### Abgelehnte Vorschläge löschen

Rejected Suggestions verschwinden aus dem System.

Dadurch gehen Audit-Evidenz und Lernsignale für zukünftige Matching-Logik verloren.

### Graph-Technologie als Design behandeln

Eine Graph Database wird ausgewählt, bevor Asset-Identität, Beziehungstypen, Autorität und Versionierungsregeln definiert sind.

Das Ergebnis ist ein visuell vernetzter, aber semantisch inkonsistenter Graph.

### Jedes lokale Konzept in eine Enterprise Definition zwingen

Legitime Domain-Unterschiede werden zu einem universellen Begriff abgeflacht.

Ein einheitliches Modell sollte Unterschiede verbinden und nicht verstecken.

## Entscheidungshilfe

Bevor ein neuer Asset-Typ ergänzt wird, frage:

```text
Besitzt das Objekt eine eigene Identität?
Hat es einen unabhängigen Lifecycle?
Kann es einen Owner oder eine Policy besitzen?
Kann es versioniert werden?
Werden User zu oder von diesem Objekt traversieren?
```

Bevor ein Beziehungstyp ergänzt wird, frage:

```text
Welche exakte Aussage macht die Edge?
Ist ihre Richtung relevant?
Kann sich die Beziehung im Zeitverlauf ändern?
Benötigt sie eine Freigabe?
Kann sie inferred, declared oder observed sein?
```

Bevor zwei Datensätze zusammengeführt werden, frage:

```text
Sind sie dasselbe logische Asset?
Sind sie Versionen, Replikate oder Ableitungen?
Welche Evidenz stützt die Entscheidung?
Kann die Entscheidung rückgängig gemacht werden?
Wer darf einen mehrdeutigen Match freigeben?
```

Bevor eine Speichertechnologie gewählt wird, frage:

```text
Welche Traversal-Fragen sind relevant?
Welche Workflows benötigen Transaktionen?
Wie viel Historie muss abfragbar bleiben?
Welche APIs müssen unterstützt werden?
Wie wird Search indexiert?
Wie bleiben Raw Source Payloads erhalten?
```

Das Design sollte den Fragen und Controls folgen und nicht einer bevorzugten Datenbankkategorie.

## Zentrale Empfehlungen

1. Definiere kanonische Asset- und Beziehungstypen, aber bewahre quellnative Attribute.
2. Verwende stabile kanonische Identifier und speichere jeden relevanten lokalen Identifier als versionierten Alias.
3. Unterscheide dasselbe Asset, Replikat, Ableitung, Version, Ersatz und ungelösten Kandidaten.
4. Trenne Asset-Identität von Asset-Versionen, Assertions und Beziehungshistorie.
5. Bewahre Raw Metadata und mache Normalisierungsregeln deterministisch und versioniert.
6. Stelle Proposed, Approved, Rejected und Deprecated explizit dar.
7. Definiere Präzedenz pro Metadatenattribut und Beziehungstyp.
8. Bewahre Provenance für jeden Wert, jedes Mapping, jeden Override und jede Freigabe.
9. Unterstütze Search, typisierte Graph Traversal und governte API-Zugriffe.
10. Starte mit einem begrenzten Data Product oder einer Domain und erweitere das Modell über bewährte Use Cases.

## Die nächste Frage ist, wo dieses Modell betrieben werden soll

Ein einheitliches Metadatenmodell definiert, was verbunden werden muss und wie Identität, Beziehungen, Versionen und Autorität dargestellt werden.

Es bestimmt noch nicht, wo Metadaten governed oder operativ betrieben werden sollten.

Einige Organisationen zentralisieren die meisten Entscheidungen in einer Plattform. Andere belassen Ownership und Authoring in den Domains und nutzen einen zentralen Index. Große Ökosysteme verteilen Storage und Kontrolle möglicherweise über Produkte und Teams.

Der nächste Teil untersucht diese Operating Models:

> **Zentrale, föderierte oder verteilte Metadaten — wie Autorität, Ownership und Plattformverantwortung aufgeteilt werden können, ohne Enterprise Discovery und Kontrolle zu verlieren.**
