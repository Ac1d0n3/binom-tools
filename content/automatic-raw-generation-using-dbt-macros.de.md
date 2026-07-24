---
title: Automatische RAW-Generierung mit dbt-Macros
description: Wie Landing-Tabellen erkannt, quellsystemnahe RAW-Modelle und governte YAML-Dateien mit dbt-Macros erzeugt und die Ergebnisse prüfbar, versioniert und sicher betrieben werden.
category: Data Governance
tags:
  - data-governance
  - dbt
  - dbt-macros
  - raw-layer
  - code-generation
  - metadata-driven
  - schema-drift
  - snowflake
  - analytics-engineering
  - pii
products:
  - snowflake
  - dbt
order: -1
author: Thomas Lindackers
series: end-to-end-data-governance
seriesPart: 3
seriesTitle: End-to-End Data Governance
hero: images/playbooks/automatic-raw-generation-using-dbt-macros-hero.png
---

## RAW-Modelle sind einfach — die Pflege von Hunderten ist es nicht

Ein quellsystemnahes RAW-Modell ist fachlich meistens nicht komplex.

Es wählt häufig nur die Spalten einer Landing-Tabelle aus, erhält die Quellwerte, übernimmt Ingestion-Metadaten und stellt das Ergebnis unter einem kontrollierten Namen bereit.

Schwierig wird es, wenn dieselbe Arbeit für Dutzende oder Hunderte Tabellen wiederholt werden muss:

- Source-Deklarationen werden manuell kopiert;
- SQL-Modelle weichen vom physischen Quellschema ab;
- Spaltenlisten sind nicht mehr aktuell;
- Namenskonventionen werden uneinheitlich angewendet;
- Beschreibungen und Governance-Metadaten bleiben unvollständig;
- neue Spalten erreichen nachgelagerte Nutzer ohne Prüfung;
- PII-Klassifikationen werden getrennt vom generierten Code gepflegt;
- Entwickler verbringen Zeit mit vorhersehbarem Grundgerüst.

Das ist ein geeigneter Automatisierungsfall.

Es ist jedoch kein Grund, ein Macro beliebige Warehouse-Objekte außerhalb des dbt-Projekts erzeugen zu lassen.

> **Das Ziel ist nicht, Code abzuschaffen. Das Ziel ist, deterministischen, prüfbaren und governten Code aus kontrollierten Metadaten zu erzeugen.**

Part 1, [End-to-End Governance Architecture](/playbooks/end-to-end-governance-architecture), hat den vollständigen Governance-Fluss definiert. Part 2, [Metadata-driven Governance with dbt meta](/playbooks/metadata-driven-governance-with-dbt-meta), hat dbt-Metadaten als technischen Governance-Vertrag etabliert.

Dieser Part wendet diesen Vertrag auf den repetitiven Anfang der Transformationsschicht an: die quellsystemnahen RAW-Modelle.

## Die Grenze: Ingestion findet vor der RAW-Generierung statt

dbt transformiert Daten, die bereits in der angebundenen Plattform vorhanden sind.

Ein Ingestion-Service, ein Replikationsprozess, eine Pipeline, ein File Loader oder ein plattformnativer Mechanismus erzeugt zuerst die Landing-Objekte.

Beispiele:

```text
Quellsystem
→ Fivetran / Airbyte / Data Factory / eigene Ingestion
→ Landing-Tabelle
→ dbt-RAW-Modell
```

Die Generierung beginnt erst, nachdem die Landing-Relation existiert und untersucht werden kann.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/automatic-raw-generation-using-dbt-macros-img1-de.png"
        alt="Abgrenzung zwischen Ingestion und automatischer RAW-Generierung: Quellsysteme werden zuerst in Landing-Tabellen geladen, danach erkennt dbt Schemas und erzeugt Source-Deklarationen, RAW-Modelle und Governance-YAML"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Ingestion erzeugt die Landing-Objekte. Der Generator liest deren Struktur und produziert governte dbt-Artefakte. dbt bleibt für Transformation, Dokumentation, Lineage und kontrolliertes Deployment verantwortlich — nicht für die Extraktion aus dem Quellsystem.
    </figcaption>
</figure>

Diese Trennung hält die Verantwortlichkeiten eindeutig:

| Verantwortung | Zuständigkeit |
| --- | --- |
| Quelldaten extrahieren und laden | Ingestion-Prozess |
| Liefermetadaten der Quelle erhalten | Landing Layer |
| Relationen und Spalten erkennen | Generator |
| dbt-Source-Deklarationen erzeugen | Generator plus Review |
| Quellsystemnahes RAW-SQL erzeugen | Generator plus Review |
| Governance-Metadaten ergänzen | Freigegebene Metadaten plus Generator |
| RAW-Modelle bauen und testen | dbt |
| Schemaänderungen und Klassifikationen freigeben | Data Owner / Steward / Engineering |

## Was sollte generiert werden?

Ein sinnvoller RAW-Generator erzeugt drei zusammengehörige Artefakttypen.

### 1. Source-Deklarationen

Der Generator kann folgende Datei erzeugen oder aktualisieren:

```text
models/raw/crm/_crm__sources.yml
```

Sie deklariert Landing-Datenbank, Schema, Tabellen, Spalten, Freshness-Konfiguration und Metadaten der Quelle.

### 2. SQL für RAW-Modelle

Der Generator kann pro Landing-Tabelle ein kontrolliertes Modell erzeugen:

```text
models/raw/crm/raw_crm_customer.sql
models/raw/crm/raw_crm_order.sql
models/raw/crm/raw_crm_contact.sql
```

Jedes Modell bleibt quellsystemnah und verwendet eine explizite, deterministische Spaltenliste.

### 3. Properties der RAW-Modelle

Der Generator kann folgende Datei erzeugen:

```text
models/raw/crm/_crm__raw_models.yml
```

Sie enthält Modellbeschreibungen, Tags, Ownership, Domäne, Generierungsmetadaten und Governance-Attribute auf Spaltenebene.

Alle drei Artefakte müssen dieselbe Objektmenge beschreiben. Nur SQL zu generieren und YAML manuell zu pflegen erzeugt eine neue Form von Drift.

## Drei Umsetzungsmuster

Es gibt drei häufige Wege, diese Arbeit zu automatisieren. Sie sind nicht gleich stark.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/automatic-raw-generation-using-dbt-macros-img2-de.png"
        alt="Vergleich von drei Mustern zur RAW-Automatisierung: Macro-gesteuerte Modellvorlagen, Codegenerierung in versionierte Dateien und direkte DDL-Ausführung außerhalb des dbt-Modellgraphen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Macro-gesteuerte Modelle und generierte Dateien bleiben im dbt-Projekt sichtbar. Direkte DDL-Generierung kann Objekte schnell erzeugen, schwächt jedoch Lineage, Selektion, Tests und Deployment-Kontrolle, wenn sie normale dbt-Ressourcen umgeht.
    </figcaption>
</figure>

### Muster A — Ein kleines Modell ruft ein wiederverwendbares RAW-Macro auf

Jede Modelldatei enthält nur einen Macro-Aufruf:

```sql
{{ raw_select(
    source_name = 'crm_landing',
    table_name = 'customer'
) }}
```

Das Macro erkennt die Spalten und erzeugt das endgültige `select`.

Vorteile:

- das Modell bleibt eine normale dbt-Ressource;
- `source()` erzeugt explizite Lineage;
- normale Selektion, Tests, Dokumentation und Deployment funktionieren weiterhin;
- wiederkehrende SQL-Muster sind zentralisiert;
- Adapterlogik kann wiederverwendet werden.

Grenzen:

- pro Tabelle ist weiterhin eine kleine Modelldatei erforderlich;
- Source-YAML muss bereits existieren;
- Schemaerkennung findet beim Kompilieren mit Live-Verbindung statt;
- eine Änderung des Quellschemas kann kompiliertes SQL verändern, ohne dass sich die Modelldatei ändert;
- im Code Review ist die vollständige Spaltenänderung nur sichtbar, wenn kompilierter Output oder ein Schema-Diff geprüft wird.

Dieses Muster eignet sich für kontrollierte Projekte mit einer mittleren Anzahl von Tabellen.

### Muster B — Ein Macro rendert Dateien, ein Wrapper schreibt sie

Eine Generierungsoperation untersucht das Landing-Schema und rendert SQL sowie YAML. Ein kleines Skript oder ein CI-Schritt schreibt den ausgegebenen Inhalt in das Projekt.

```text
dbt run-operation
→ gerendertes SQL / YAML
→ Wrapper schreibt Dateien
→ Git-Diff
→ Review
→ dbt parse / build
```

Vorteile:

- das vollständige generierte SQL und YAML ist in Git sichtbar;
- Schemaänderungen werden zu expliziten Pull-Request-Diffs;
- der generierte Output ist deterministisch;
- CI kann unerwartete Änderungen ablehnen;
- manuelle Metadaten können über kontrollierte Merge-Regeln erhalten bleiben;
- die erzeugten Ressourcen bleiben normale dbt-Modelle.

Grenzen:

- ein Wrapper oder Generierungstool ist erforderlich, weil ein normales dbt-Macro Projektdateien nicht eigenständig verwaltet;
- Regenerierungsregeln müssen stabil sein;
- das Merge-Verhalten zwischen generierten und manuell freigegebenen Metadaten muss entworfen werden;
- das Team muss festlegen, wann die Generierung läuft.

Für ein größeres governtes Projekt ist dies normalerweise das stärkste Muster.

Das Package `dbt-labs/codegen` kann hilfreiche Bausteine für Source- und Modellgerüste bereitstellen. Ein eigener Governance-Generator kann dieses Prinzip um organisationsspezifische Benennung, Metadaten, Klassifikationen und Review-Regeln erweitern.

### Muster C — Ein Macro führt DDL aus und erzeugt RAW-Objekte direkt

Eine Operation kann generiertes DDL gegen das Warehouse ausführen.

Das kann technisch möglich sein, sollte aber eine Ausnahme bleiben.

Risiken:

- Objekte sind nicht als normale dbt-Modelle repräsentiert;
- Lineage kann unvollständig sein;
- Node Selection verwaltet die Objekte nicht;
- Tests und Dokumentation benötigen eine separate Behandlung;
- State Comparison und CI werden schwieriger;
- Deployment-Verhalten hängt von Seiteneffekten ab;
- `run_query` kann in Compile-bezogenen Abläufen ausgeführt werden, wenn es nicht sorgfältig begrenzt ist.

Direkte DDL-Operationen eignen sich für administrative Aktionen oder streng kontrollierte Plattformautomatisierung. Für einen governten RAW Layer sind sie normalerweise schwächer als generierte dbt-Ressourcen.

## Ein dbt-natives Macro für die RAW-Auswahl

Ein minimales Macro kann die Spalten einer deklarierten Source-Relation aufzählen.

```sql
{% macro raw_select(source_name, table_name) %}

    {% set relation = source(source_name, table_name) %}

    {% if execute %}
        {% set columns = adapter.get_columns_in_relation(relation) %}
    {% else %}
        {% set columns = [] %}
    {% endif %}

    select
    {% if columns | length > 0 %}
        {% for column in columns %}
            {{ adapter.quote(column.name) }}{% if not loop.last %},{% endif %}
        {% endfor %}
    {% else %}
        *
    {% endif %}
    from {{ relation }}

{% endmacro %}
```

Ein Modell wird dadurch sehr klein:

```sql
{{ config(
    materialized = 'view',
    tags = ['raw', 'generated', 'crm']
) }}

{{ raw_select(
    source_name = 'crm_landing',
    table_name = 'customer'
) }}
```

Mehrere Details sind bewusst gewählt.

### `source()` statt einer fest codierten Relation verwenden

`source()` verbindet das Modell mit einem deklarierten Source-Node. Dadurch entstehen eine explizite Abhängigkeit und Lineage.

### Nach Möglichkeit Adaptermethoden verwenden

`adapter.get_columns_in_relation()` bietet eine dbt-Abstraktion über den plattformspezifischen Metadatenzugriff.

Für speziellere Erkennung kann ein Macro `information_schema` über `run_query` abfragen. Dann müssen jedoch SQL-Dialekt, Berechtigungen und Ausführungskontext explizit behandelt werden.

### Ausführung während reinem Parsing absichern

Während des Parsings kann `execute` den Wert `false` haben. Der Fallback hält das Jinja-Rendering gültig, ohne eine Live-Metadatenabfrage zu benötigen.

### Explizite Spalten im kompilierten oder generierten SQL bevorzugen

Eine explizite Liste macht Spaltenreihenfolge, Quoting und Schemaänderungen sichtbar.

Ein dauerhaftes `select *` ist bequem, veröffentlicht aber jede neue Quellspalte automatisch. Das ist besonders gefährlich, wenn eine neue PII-Spalte hinzukommt.

## Beispiel: CRM-Landing-Source

Angenommen, der Ingestion-Prozess erzeugt:

```text
LANDING_DB.CRM.CUSTOMER
```

mit folgenden Spalten:

```text
CUSTOMER_ID
CUSTOMER_NAME
EMAIL
PHONE
COUNTRY_CODE
UPDATED_AT
_FIVETRAN_SYNCED
_FIVETRAN_DELETED
```

Die Source-Deklaration kann so generiert werden:

```yaml
version: 2

sources:
  - name: crm_landing
    database: LANDING_DB
    schema: CRM

    config:
      meta:
        source_system: crm
        owner: data_platform

    tables:
      - name: customer
        description: Landing-Tabelle, die aus dem CRM-System repliziert wird.

        config:
          loaded_at_field: _FIVETRAN_SYNCED
          freshness:
            warn_after:
              count: 2
              period: hour
            error_after:
              count: 6
              period: hour
          meta:
            ingestion_method: fivetran
            raw_generation: enabled

        columns:
          - name: CUSTOMER_ID
            description: Kundenidentifier des Quellsystems.

          - name: EMAIL
            description: E-Mail-Adresse des Kunden.
            config:
              meta:
                pii: true
                pii_category: email
                sensitivity: confidential
                classification_status: approved
```

Die Properties des RAW-Modells können so generiert werden:

```yaml
version: 2

models:
  - name: raw_crm_customer
    description: Quellsystemnahes RAW-Modell für die CRM-Kundentabelle.

    config:
      materialized: view
      tags:
        - raw
        - generated
        - crm
      meta:
        generated: true
        generation_rule: raw_v1
        source_system: crm
        owner: data_platform
        domain: customer

    columns:
      - name: CUSTOMER_ID
        description: Kundenidentifier des Quellsystems.
        data_tests:
          - not_null
        config:
          meta:
            pii: false
            classification_status: approved

      - name: EMAIL
        description: E-Mail-Adresse des Kunden.
        config:
          meta:
            pii: true
            pii_category: email
            sensitivity: confidential
            masking_policy: email_mask
            classification_status: approved

      - name: PHONE
        description: Telefonnummer des Kunden.
        config:
          meta:
            pii: true
            pii_category: phone
            sensitivity: confidential
            masking_policy: phone_mask
            classification_status: approved
```

Das SQL bleibt absichtlich einfach:

```sql
{{ config(
    materialized = 'view',
    tags = ['raw', 'generated', 'crm'],
    meta = {
        'generated': true,
        'generation_rule': 'raw_v1',
        'source_system': 'crm',
        'owner': 'data_platform',
        'domain': 'customer'
    }
) }}

{{ raw_select(
    source_name = 'crm_landing',
    table_name = 'customer'
) }}
```

Das RAW-Modell standardisiert keine Ländercodes, führt keine Kunden zusammen und berechnet keine fachlichen Attribute. Diese Verantwortlichkeiten gehören in spätere Schichten.

## Der Generator benötigt mehr als das Information Schema

Das physische Schema kann Fragen beantworten wie:

- Welche Tabellen existieren?
- Welche Spalten existieren?
- Welche physischen Datentypen besitzen sie?
- Welche Position haben sie?
- Müssen Identifier gequotet werden?
- Hat sich das Schema verändert?

Es kann nicht zuverlässig beantworten:

- Ist eine Spalte PII?
- Welche Fachdomäne ist verantwortlich?
- Welche Retention Policy gilt?
- Welche Masking Policy ist freigegeben?
- Darf eine neue Spalte nachgelagert verwendet werden?
- Ist ein technisch wirkendes Feld tatsächlich vertraulich?
- Welche Beschreibung ist fachlich sinnvoll?

Ein governter Generator kombiniert deshalb mehrere Eingaben.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/automatic-raw-generation-using-dbt-macros-img3-de.png"
        alt="Generierungsprozess, der physische Warehouse-Metadaten, freigegebene Governance-Metadaten und Generierungskonventionen kombiniert, um dbt-Source-YAML, RAW-Modell-SQL und Modell-Properties-YAML zu erzeugen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Physische Metadaten beschreiben, was existiert. Governance-Metadaten beschreiben, wie es behandelt werden muss. Generierungskonventionen bestimmen, wie beides in reproduzierbare dbt-Ressourcen überführt wird.
    </figcaption>
</figure>

Eine praktische Prioritätsreihenfolge lautet:

```text
Freigegebene Governance-Metadaten
→ vorhandener manueller Override
→ Metadaten des Quellsystems
→ Standardwert des Generators
```

Für eine neu erkannte Spalte darf der Generator keine freigegebene Klassifikation erfinden.

Ein sicherer Standard ist:

```yaml
config:
  meta:
    classification_status: unreviewed
    downstream_publication: blocked
```

Dadurch entsteht eine kontrollierte Ausnahme, die geprüft werden muss.

## Deterministische Generierung ist zwingend

Wird der Generator zweimal mit denselben Eingaben ausgeführt, müssen identische Dateien entstehen.

Dafür braucht es stabile Regeln für:

- Dateinamen;
- Modellnamen;
- Source-Namen;
- Spaltenreihenfolge;
- Quoting;
- Groß- und Kleinschreibung;
- Reihenfolge der YAML-Keys;
- Whitespace;
- Beschreibungen;
- Standard-Tags;
- generierte Metadaten;
- Kommentare und Header.

Nicht deterministischer Output erzeugt unnötige Git-Diffs und schwächt das Vertrauen in die Automatisierung.

Eine generierte Datei kann einen kontrollierten Header enthalten:

```text
Generated by: raw_generator
Generation rule: raw_v1
Source relation: LANDING_DB.CRM.CUSTOMER
Do not edit generated sections manually.
Manual governance overrides are stored in: governance/crm.yml
```

Der Generator sollte entweder die gesamte Datei besitzen oder generierte und manuell gepflegte Abschnitte eindeutig trennen. Verdeckte Merge-Logik ist schwer zu betreiben.

## Schema Drift muss zu einem Review-Workflow werden

Automatische Generierung ist besonders wertvoll, wenn sich eine Quelle ändert.

Ein sicherer Schema-Drift-Prozess lautet:

```flow linear vertical
Landing-Schema untersuchen
Mit committed Definition vergleichen
Änderung klassifizieren
Vorgeschlagenes SQL und YAML generieren
Neue Spalten als unreviewed markieren
Parse, Compile und Tests ausführen
Pull Request erstellen
Freigeben oder ablehnen
Deployen
```

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/automatic-raw-generation-using-dbt-macros-img4-de.png"
        alt="Kontrollierter Schema-Drift-Workflow vom Vergleich des Landing-Schemas über Klassifikation, generierten Pull Request, Governance-Review und dbt-Validierung bis zum Deployment"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Schema Drift soll einen sichtbaren Änderungsvorschlag erzeugen und keine unsichtbare Produktionsänderung. Neue Spalten können automatisch erkannt werden, während Veröffentlichung und Klassifikation kontrollierte Entscheidungen bleiben.
    </figcaption>
</figure>

Sinnvolle Änderungskategorien sind:

| Änderung | Typische Aktion |
| --- | --- |
| Neue Tabelle | Deaktivierte oder Draft-Ressourcen bis zur Freigabe erzeugen |
| Neue Spalte | Als unreviewed ergänzen; nachgelagerte Weitergabe bei Bedarf blockieren |
| Entfernte Spalte | CI fehlschlagen lassen, wenn nachgelagerte Abhängigkeiten bestehen |
| Datentypänderung | Kompatibilitätsprüfung und gezielte Tests verlangen |
| Umbenannte Spalte | Als Entfernen plus Hinzufügen behandeln, wenn kein explizites Mapping existiert |
| Geänderte Spaltenreihenfolge | Normalerweise ohne semantische Auswirkung regenerieren |
| Neue PII-Klassifikation | Sicherheitsänderung vor breiterer Veröffentlichung propagieren |
| Fehlende Source-Relation | Generierung fehlschlagen lassen oder Quelle gemäß Policy deaktivieren |

Der Generator darf manuell gepflegte Governance-Entscheidungen nicht still löschen, weil eine physische Quellspalte vorübergehend fehlt.

## PII-Erkennung kann unterstützen — nicht freigeben

Spaltennamen können nützliche Hinweise liefern:

```text
EMAIL
PHONE
IBAN
BIRTH_DATE
SOCIAL_SECURITY_NUMBER
```

Pattern Matching, Catalog-Klassifikationen oder ML-basierte Discovery können Metadaten vorschlagen.

Der Output muss ein Vorschlag bleiben:

```yaml
config:
  meta:
    pii_candidate: true
    suggested_pii_category: email
    classification_status: unreviewed
```

Der freigegebene Wert muss davon unterscheidbar sein:

```yaml
config:
  meta:
    pii: true
    pii_category: email
    classification_status: approved
    approved_by: data_steward_customer
```

Diese Trennung verhindert, dass eine Namensheuristik als verbindliche Governance-Entscheidung erscheint.

## Empfohlene Projektstruktur

Eine skalierbare Struktur kann so aussehen:

```text
macros/
  raw/
    raw_select.sql
    discover_relations.sql
    discover_columns.sql
    render_source_yaml.sql
    render_raw_model.sql
    render_model_yaml.sql

models/
  raw/
    crm/
      _crm__sources.yml
      _crm__raw_models.yml
      raw_crm_customer.sql
      raw_crm_contact.sql
    erp/
      _erp__sources.yml
      _erp__raw_models.yml
      raw_erp_customer.sql
      raw_erp_sales_order.sql

governance/
  crm.yml
  erp.yml

scripts/
  generate_raw.py

tests/
  generator/
    expected_output/
```

Die Dateien unter `governance` enthalten freigegebene Klassifikationen und manuelle Overrides. Generierte Dateien bleiben reproduzierbare Outputs.

## CI/CD-Prüfungen für generierte RAW-Ressourcen

Der Generator sollte Teil des Delivery-Prozesses sein und kein undokumentierter Entwicklerbefehl.

Eine praktische CI-Sequenz lautet:

```text
dbt-Packages installieren
Generator-Konfiguration validieren
Mit Read-only-Metadatenrolle verbinden
Landing-Schema erkennen
RAW-Artefakte regenerieren
Bei nicht leerem Git-Diff fehlschlagen
dbt parse ausführen
dbt compile ausführen
Gezielte Source- und RAW-Tests ausführen
Unreviewed-Klassifikationen prüfen
Namens- und Metadatenanforderungen prüfen
Pull-Request-Diff veröffentlichen
```

Die Prüfung „bei nicht leerem Git-Diff fehlschlagen“ erkennt, wenn committed generierte Dateien nicht mehr zum Quellschema oder zu den Generatorregeln passen.

Getrennte Credentials sind sinnvoll:

| Aktivität | Berechtigung |
| --- | --- |
| Metadaten erkennen | Metadaten und Source-Definitionen lesen |
| RAW-Modelle in Entwicklung bauen | Objekte im Entwicklungsschema erstellen |
| CI Parse / Render | Keine Schreibrechte in Produktion |
| Produktionsdeployment | Kontrollierte Deployment-Rolle |
| Governance-Freigabe | Repository-Freigabe, nicht standardmäßig Warehouse-Admin |

## Häufige Anti-Patterns

### Mit `select *` jede neue Spalte veröffentlichen

Dadurch wird Source Schema Drift zu einem unkontrollierten Veröffentlichungsmechanismus.

### DDL außerhalb des dbt-DAG erzeugen

Die Objekte existieren, dbt kann sie aber nicht als First-Class-Ressourcen verwalten.

### Freigegebene PII-Metadaten überschreiben

Physische Schemaerkennung darf Governance-Entscheidungen nicht ersetzen.

### Generierten Code als nicht prüfbar behandeln

Generierter Code kann weiterhin Sicherheits-, Kosten- und Kompatibilitätsprobleme erzeugen. Er benötigt Tests und Review.

### Transformation in den RAW-Generator mischen

Fachliche Umbenennung, Standardisierung von Werten und KPI-Logik machen das RAW-Muster quellspezifisch und semantisch überladen.

### Seiteneffektbehaftete `run_query`-Logik im normalen Compile ausführen

Metadaten lesen ist das eine. DDL, DML oder destruktive Aktionen benötigen explizite Command-Begrenzung und kontrollierte Operations.

### Dateien mit instabiler Formatierung regenerieren

Unruhiger Output versteckt fachlich relevante Änderungen.

### Den Generator zur einzigen Source of Truth machen

Das Quellschema beschreibt die Struktur. Governance-Metadaten und freigegebene Ausnahmen bleiben eigenständige autoritative Eingaben.

## Entscheidungshilfe

| Situation | Empfohlenes Muster |
| --- | --- |
| Wenige Quellen und geringe Änderungsrate | Manuelle Modelle mit gemeinsamen Konventionen können ausreichen |
| Mittlere Anzahl repetitiver RAW-Modelle | Macro-gesteuerte Modellvorlagen |
| Viele Quellen mit häufigem Schema Drift | Dateigenerierung plus Git-Review |
| Nur initiales Grundgerüst erforderlich | Codegen-Package oder einmaliger Generator |
| Governter Metadaten-Merge erforderlich | Eigener Generator mit freigegebenem Override-Register |
| Administratives Warehouse-DDL erforderlich | Explizites `run-operation`, getrennt von der Modellgenerierung |
| Vollständig dynamische Objekte ohne committed Code erforderlich | Prüfen, ob dbt-Modelle die richtige Abstraktion sind |

Automatisierung ist gerechtfertigt, wenn sie wiederkehrende Arbeit reduziert, ohne Kontrolle zu reduzieren.

## Die zentrale Erkenntnis

> **Generiere das repetitive RAW-Grundgerüst automatisch, halte aber jedes resultierende Modell, jede Source-Deklaration und jede Governance-Entscheidung sichtbar, testbar und reviewbar.**

dbt-Macros können Relationen untersuchen, SQL erzeugen und wiederverwendbare Muster rendern.

`run-operation` kann Generierungslogik aufrufen.

Adaptermethoden und `run_query` können Warehouse-Metadaten lesen.

Ein Wrapper oder ein Codegenerierungstool kann den gerenderten Output in versionierte Projektdateien überführen.

Das stärkste Betriebsmodell lautet daher:

```flowchart
Landing-Schema
Freigegebene Governance-Metadaten
Generierungsregeln
Generiertes SQL und YAML
Git-Review
dbt Build
Governter RAW Layer
```

Part 4, [Propagating PII Metadata across Data Warehouses](/playbooks/propagating-pii-metadata-across-data-warehouses), setzt an diesem Punkt fort. Sobald der RAW Layer freigegebene Metadaten enthält, besteht die nächste Herausforderung darin, diese Metadaten durch Conform-, Core- und Analytics-Modelle zu erhalten und aufzulösen.

## Quellen und weiterführende Dokumentation

- [dbt — Jinja and macros](https://docs.getdbt.com/docs/build/jinja-macros)
- [dbt — adapter object](https://docs.getdbt.com/reference/dbt-jinja-functions/adapter)
- [dbt — run_query](https://docs.getdbt.com/reference/dbt-jinja-functions/run_query)
- [dbt — run-operation](https://docs.getdbt.com/reference/commands/run-operation)
- [dbt — Hooks and operations](https://docs.getdbt.com/docs/build/hooks-operations)
- [dbt — meta configuration](https://docs.getdbt.com/reference/resource-configs/meta)
- [dbt — columns properties](https://docs.getdbt.com/reference/resource-properties/columns)
- [dbt — source freshness](https://docs.getdbt.com/reference/resource-properties/freshness)
- [dbt Package Hub — codegen](https://hub.getdbt.com/dbt-labs/codegen/latest/)

> **Stand der Funktionsbeschreibung:** Juli 2026. dbt-Syntax und Ausführungsverhalten können sich zwischen dbt Core, Fusion Engine und dbt-Plattform unterscheiden. Für die konkrete Implementierung sollte die Dokumentation der eingesetzten Engine und Version geprüft werden.
