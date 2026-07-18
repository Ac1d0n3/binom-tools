---
title: Datenqualität mit dbt — Von Tests und Fehlertabellen zur historischen Überwachung
description: Wie dbt Generic und Custom Data Tests in YAML definiert, fehlerhafte Datensätze mit store_failures persistiert und die Ergebnisse über eine zusätzliche Historientabelle für Qlik, Power BI und Data Governance operationalisiert werden.
category: Datenqualität
tags:
  - data-quality
  - operational-data-quality
  - dbt
  - dbt-tests
  - generic-tests
  - custom-tests
  - store-failures
  - run-results
  - data-quality-history
  - qlik-sense
  - power-bi
  - data-governance
  - automation
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 3
seriesTitle: Operative Datenqualität
hero: images/playbooks/dq-test3-hero.png
---

## dbt testet Daten — eine historische Qualitätssteuerung muss ergänzt werden

dbt besitzt ein sehr klares Testprinzip:

> **Ein Data Test ist eine SQL-Abfrage, die genau die Datensätze zurückgibt, welche eine Regel verletzen.**

Liefert die Abfrage keine Zeilen, besteht der Test. Liefert sie fehlerhafte Zeilen, warnt dbt oder bewertet den Test als fehlgeschlagen.

Damit eignet sich dbt sehr gut, um Datenqualitätsregeln direkt mit Modellen, Quellen, Seeds und Snapshots zu verbinden.

dbt löst aber nicht automatisch alle Anforderungen eines operativen Data-Quality-Monitorings:

- Die Standardausgabe konzentriert sich auf den aktuellen Testlauf.
- `store_failures` speichert fehlerhafte Datensätze, ersetzt aber die vorherige Fehlertabelle desselben Tests.
- Die Fehlertabellen verschiedener Tests besitzen nicht zwingend dieselbe Spaltenstruktur.
- Ein bestandener Test enthält keine Fehlerzeilen, benötigt für die Historie aber trotzdem eine Ergebniszeile.
- Der aktuelle Fehlerzähler allein liefert noch keine vollständige Zeitreihe.
- `Rows Tested` wird von einem normalen dbt Data Test nicht automatisch als standardisierte Kennzahl bereitgestellt.
- Owner, Data Product, Severity und fachliche Rule ID müssen aus Governance-Metadaten ergänzt werden.

Deshalb besteht das vollständige Muster aus drei getrennten Ebenen:

```flowchart
dbt Data Test
Failure Table
DQ History Table
Qlik / Power BI
Ownership and Remediation
```

> **Die Fehlertabelle erklärt, welche Datensätze aktuell falsch sind. Die Historientabelle zeigt, wie sich die Qualität über viele Läufe entwickelt.**

## Generic Tests, Custom Generic Tests und Singular Tests

dbt unterscheidet bei Data Tests im Wesentlichen zwischen wiederverwendbaren Generic Tests und einmalig formulierten Singular Tests.

### Eingebaute Generic Tests

dbt liefert vier wiederverwendbare Generic Tests aus:

| Test | Bedeutung |
| --- | --- |
| **not_null** | Eine Spalte darf keine Nullwerte enthalten |
| **unique** | Eine Spalte oder ein Ausdruck muss eindeutig sein |
| **accepted_values** | Werte müssen in einer definierten Werteliste enthalten sein |
| **relationships** | Werte müssen in einer referenzierten Tabelle vorhanden sein |

Diese Regeln decken viele technische Basisprüfungen ab.

```yaml
models:
  - name: dim_customer
    columns:
      - name: customer_id
        data_tests:
          - not_null
          - unique

      - name: customer_status
        data_tests:
          - accepted_values:
              arguments:
                values:
                  - active
                  - inactive
                  - blocked

      - name: country_id
        data_tests:
          - relationships:
              arguments:
                to: ref('dim_country')
                field: country_id
```

Die ältere Eigenschaft `tests:` wird aus Kompatibilitätsgründen weiterhin unterstützt. Für neue Projekte ist `data_tests:` eindeutiger, weil dbt inzwischen zusätzlich Unit Tests kennt.

### Custom Generic Tests

Ein Custom Generic Test kapselt wiederverwendbare, organisationsspezifische Logik.

Beispiel: Ein Wert muss innerhalb eines erlaubten Bereichs liegen.

```sql
{% test value_between(
    model,
    column_name,
    min_value,
    max_value
) %}

    select
        *
    from {{ model }}
    where {{ column_name }} < {{ min_value }}
       or {{ column_name }} > {{ max_value }}

{% endtest %}
```

Die Regel wird anschließend in YAML verwendet:

```yaml
models:
  - name: fact_sales
    columns:
      - name: amount
        data_tests:
          - value_between:
              name: dq_102_sales_amount_range
              arguments:
                min_value: 0
                max_value: 1000000
              config:
                severity: error
                store_failures: true
```

Custom Generic Tests sind sinnvoll für Regeln wie:

- Wertebereich
- bedingtes Pflichtfeld
- gültige Zeitintervalle
- E-Mail- oder Identifier-Formate
- Vergleich mehrerer Spalten
- Eindeutigkeit einer Spaltenkombination
- fachliche Schwellenwerte
- mandantenabhängige Regeln
- Prüfungen nur für aktive Datensätze

Der Testname sollte stabil und nachvollziehbar sein. Ein Name wie `dq_102_sales_amount_range` lässt sich leichter mit einer Governance-Regel verknüpfen als ein automatisch zusammengesetzter technischer Name.

### Singular Tests

Singular Tests sind eigenständige SQL-Dateien, die fehlerhafte Datensätze zurückgeben.

Beispiel:

```sql
-- tests/assert_order_total_matches_components.sql

select
    order_id,
    order_total,
    item_total,
    tax_amount
from {{ ref('fact_orders') }}
where order_total <> item_total + tax_amount
```

Singular Tests passen zu:

- komplexen Regeln über mehrere Modelle
- einmaligen fachlichen Assertions
- Abstimmungen zwischen Fakt- und Aggregattabellen
- Salden- oder Kontrollsummen
- Prüfungen, die nicht sinnvoll parametrisiert werden können

Werden ähnliche Singular Tests mehrfach kopiert, sollte daraus ein Custom Generic Test oder ein generiertes Macro werden.

## YAML wird zur ausführbaren Regeldefinition

Die YAML-Datei verbindet Datenmodell und Qualitätsregel.

Sie kann nicht nur den Testnamen und seine Argumente enthalten, sondern auch die operative Behandlung:

```yaml
models:
  - name: fact_sales
    description: Governed sales transaction model

    columns:
      - name: order_id
        data_tests:
          - not_null:
              name: dq_101_order_id_required
              config:
                severity: error
                store_failures: true
                tags:
                  - data_quality
                  - critical

          - unique:
              name: dq_103_order_id_unique
              config:
                severity: error
                error_if: "> 0"
                warn_if: "> 0"
                store_failures: true

      - name: amount
        data_tests:
          - value_between:
              name: dq_102_sales_amount_range
              arguments:
                min_value: 0
                max_value: 1000000
              config:
                severity: error
                error_if: "> 100"
                warn_if: "> 0"
                store_failures: true
```

Die YAML-Definition legt damit fest:

- welches Modell geprüft wird
- welche Spalte betroffen ist
- welche Testlogik gilt
- welche Parameter verwendet werden
- wie der Test heißt
- ob Fehler gespeichert werden
- wann gewarnt wird
- wann der Lauf fehlschlägt
- welche Tags für Selektion und Betrieb gelten

### Rule ID und dbt Test Name

Für kleinere Projekte kann der benutzerdefinierte Testname gleichzeitig als Rule ID dienen.

Für ein Governance-Framework ist eine getrennte Identität robuster:

| Identität | Aufgabe |
| --- | --- |
| **Rule ID** | stabile fachliche Identität der Qualitätsregel |
| **dbt Test Name** | lesbarer und selektierbarer Name der konkreten dbt-Implementierung |
| **dbt Unique ID** | technische Identität des kompilierten dbt-Knotens |
| **Rule Version** | Version der fachlichen und technischen Regeldefinition |

Dadurch kann eine Regel technisch neu implementiert werden, ohne ihre fachliche Historie zu verlieren.

Ein Generator kann dieses Mapping zentral verwalten:

```text
DQ-102
→ dq_102_sales_amount_range
→ test.sales_project.dq_102_sales_amount_range...
→ Rule Version 3
```

## Severity und Schwellenwerte

dbt Data Tests müssen nicht bei jeder einzelnen fehlerhaften Zeile den gesamten Lauf abbrechen.

Die Konfiguration unterstützt unter anderem:

- `severity: error`
- `severity: warn`
- `error_if`
- `warn_if`

Beispiel:

```yaml
data_tests:
  - unique:
      name: dq_103_order_id_unique
      config:
        severity: error
        error_if: "> 100"
        warn_if: "> 0"
```

Die Logik bedeutet:

- mehr als 100 Fehler → Error
- 1 bis 100 Fehler → Warning
- 0 Fehler → Pass

Diese technische Schwelle muss zur fachlichen Severity passen.

Eine kritische Regel kann beispielsweise bereits bei einer einzigen Verletzung fehlschlagen. Eine weniger kritische Vollständigkeitsregel kann zunächst warnen und erst oberhalb eines definierten Grenzwerts blockieren.

### `fail_calc` bestimmt den Fehlerzähler

Standardmäßig zählt dbt die von der Testabfrage zurückgegebenen Zeilen.

Bei einem Eindeutigkeitstest kann eine zurückgegebene Zeile jedoch eine mehrfach vorkommende Schlüsselgruppe repräsentieren. Die tatsächliche Anzahl betroffener Datensätze kann höher sein.

Mit `fail_calc` kann der Fehlerzähler angepasst werden:

```yaml
data_tests:
  - unique:
      config:
        fail_calc: >
          case
            when count(*) > 0 then sum(n_records)
            else 0
          end
```

Damit muss klar dokumentiert werden, was `Rows Failed` bedeutet:

- Anzahl fehlerhafter Schlüsselgruppen
- Anzahl aller Datensätze in fehlerhaften Gruppen
- Anzahl zusätzlicher Duplikate über das erste Vorkommen hinaus

Die Semantik darf sich für dieselbe Rule ID nicht unkontrolliert ändern.

## `store_failures` speichert Evidenz, aber keine Historie

Mit `store_failures: true` persistiert dbt die fehlerhaften Datensätze eines Tests in der Datenplattform.

```yaml
models:
  - name: dim_customer
    columns:
      - name: email
        data_tests:
          - not_null:
              name: dq_201_customer_email_required
              config:
                store_failures: true
```

Standardmäßig verwendet dbt dafür ein Audit-Schema, dessen Name typischerweise auf `_dbt_test__audit` endet.

Die gespeicherte Relation verwendet den Namen des Tests beziehungsweise einen konfigurierten Alias.

Das bietet einen unmittelbaren operativen Vorteil:

- fehlerhafte Datensätze sind direkt abfragbar
- Data Engineers können die Ursache analysieren
- Business Keys können identifiziert werden
- technische Fehlermuster werden sichtbar
- nachgelagerte Prozesse können auf die aktuelle Fehlerliste zugreifen

Die entscheidende Einschränkung lautet:

> **Die Fehlerrelation eines Tests ersetzt bei jedem neuen Lauf den vorherigen Inhalt.**

Auch ein bestandener Folgelauf ersetzt die vorherigen Fehler.

Damit beantwortet `store_failures`:

> Welche Datensätze verletzen die Regel im aktuellen Testlauf?

Es beantwortet nicht:

> Wie viele Fehler hatte dieselbe Regel gestern, letzte Woche oder vor der letzten Korrektur?

## Failure Table und History Table haben unterschiedliche Grains

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test3-img1-de.png"
        alt="dbt Data Tests schreiben aktuelle fehlerhafte Datensätze über store_failures in Audit-Tabellen, während eine separate append-orientierte Historientabelle aggregierte Ergebnisse pro Regel und Lauf für Qlik und Power BI speichert"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die dbt Audit-Tabelle ist für Root-Cause-Analysen optimiert. Die zusätzliche Historientabelle speichert dagegen jeden Testlauf und ermöglicht Trends, KPIs und Verantwortlichkeit.
    </figcaption>
</figure>

Die beiden Tabellen dürfen nicht verwechselt werden.

### Failure Table

Grain:

> Eine Zeile je aktuell fehlerhaftem Datensatz oder je fehlerhafter Gruppe.

Typische Inhalte:

- Business Key
- geprüfter Wert
- Vergleichswert
- Fehlergrund
- technische Quellspalten
- weitere durch den Test zurückgegebene Evidenz

Die Struktur kann sich je Test unterscheiden.

### DQ History Table

Grain:

> Eine Zeile je Rule ID, dbt-Test und Ausführung.

Empfohlene Struktur:

| Feld | Herkunft |
| --- | --- |
| **Run ID** | `invocation_id` oder Orchestrator |
| **Rule ID** | Regelregistry oder Generator |
| **Platform** | `dbt` plus Zielplattform |
| **Data Product** | Governance-Metadaten |
| **Table** | dbt Node beziehungsweise Modell |
| **Column** | Testmetadaten |
| **Test Type** | Testname oder normalisierter Typ |
| **Status** | dbt Result Status |
| **Rows Tested** | zusätzliche Messabfrage |
| **Rows Failed** | dbt Result `failures` oder kontrollierter `fail_calc` |
| **Failure Rate** | berechnete Kennzahl |
| **Executed At** | Run-Zeitpunkt |
| **Owner** | Regelregistry |
| **Severity** | Governance- und dbt-Konfiguration |
| **Execution Time** | dbt Result |
| **dbt Unique ID** | `run_results.json` und `manifest.json` |
| **Failure Relation** | gespeicherte Audit-Relation |
| **Message** | dbt Result Message |

Ein bestandener Test erzeugt ebenfalls eine Historienzeile:

```text
Rows Failed = 0
Status = Passed
```

Ohne diese Zeile würde das Monitoring nur Fehler sehen und könnte keine Pass Rate oder stabile Zeitreihe berechnen.

## dbt liefert nicht automatisch `Rows Tested`

Ein dbt Data Test gibt primär fehlerhafte Datensätze und einen Fehlerzähler zurück.

Die Anzahl aller geprüften Datensätze ist nicht automatisch Bestandteil des normalen Testergebnisses.

Für die Ergebnisstruktur aus Part 1 muss sie deshalb ergänzt werden.

### Variante 1: Separate Scope-Abfrage

Der History Generator kennt Modell, Spalte und einen möglichen `where`-Filter und berechnet:

```sql
select count(*) as rows_tested
from {{ ref('fact_sales') }}
where <identischer Test-Scope>
```

Vorteil:

- klare und allgemeine Kennzahl

Nachteil:

- zusätzliche Abfrage und zusätzlicher Compute

### Variante 2: Generierte Messlogik

Der DG Macro Generator erzeugt neben der eigentlichen Fehlerabfrage eine standardisierte Messabfrage.

```text
Failure Query
→ liefert fehlerhafte Datensätze

Metric Query
→ liefert Rows Tested und Rows Failed
```

Vorteil:

- konsistente Semantik
- bessere Optimierung
- zentrale Kontrolle der Messdefinition

### Variante 3: Regelabhängige Kennzahl

Nicht jede Regel prüft Datensätze.

Bei einer Aktualitätsregel kann gelten:

```text
Rows Tested = 1
Rows Failed = 0 oder 1
```

Bei einer Abstimmungsregel kann `Rows Tested` die Anzahl verglichener Gruppen statt die Anzahl einzelner Transaktionen darstellen.

Deshalb muss die Regelregistry zusätzlich den Metric Grain beschreiben.

## Drei Wege zur Historisierung

### 1. dbt-Artefakte nach jedem Lauf einlesen

dbt erzeugt `run_results.json` mit Informationen zu ausgeführten Nodes, darunter:

- `unique_id`
- Status
- Fehlerzahl
- Laufzeit
- Timing
- Nachricht
- Relation der gespeicherten Fehler, sofern vorhanden

Das `manifest.json` liefert ergänzende Metadaten zu Modellen, Tests, Abhängigkeiten und Konfigurationen.

Ein nachgelagerter Prozess kann beide Artefakte verbinden:

```flowchart
dbt test
run_results.json
manifest.json
Artifact Loader
DQ_TEST_HISTORY
```

Dies ist meist die robusteste plattformneutrale Variante.

Vorteile:

- klare Trennung zwischen dbt-Ausführung und Monitoring
- keine zusätzlichen Inserts innerhalb des Testlaufs
- Artefakte können zentral über mehrere Projekte konsolidiert werden
- technische Fehler werden ebenfalls sichtbar
- Historisierung kann unabhängig versioniert werden

Die Artefakte müssen pro Invocation gesichert werden. Wird nur die letzte lokale Datei aus `target/` behalten, entsteht erneut keine Historie.

### 2. `on-run-end` Hook

Im `on-run-end`-Kontext stellt dbt die Result-Objekte der ausgeführten Ressourcen bereit.

Ein vereinfachtes Muster:

```sql
{% macro persist_dq_history(results) %}

    {% if execute %}

        {% for result in results
            if result.node.resource_type == 'test' %}

            {% set rows_failed =
                result.failures
                if result.failures is not none
                else 0
            %}

            {% set insert_sql %}

                insert into governance.dq_test_history (
                    run_id,
                    dbt_unique_id,
                    test_name,
                    test_status,
                    rows_failed,
                    execution_time,
                    executed_at
                )
                values (
                    '{{ invocation_id }}',
                    '{{ result.node.unique_id }}',
                    '{{ result.node.name }}',
                    '{{ result.status }}',
                    {{ rows_failed }},
                    {{ result.execution_time }},
                    '{{ run_started_at }}'
                )

            {% endset %}

            {% do run_query(insert_sql) %}

        {% endfor %}

    {% endif %}

{% endmacro %}
```

Aktivierung:

```yaml
on-run-end:
  - "{{ persist_dq_history(results) }}"
```

Das Beispiel zeigt nur das Prinzip. Eine produktive Umsetzung benötigt:

- adaptergerechtes Quoting
- sichere Behandlung von Nachrichten und Sonderzeichen
- Mapping der dbt-Statuswerte
- Rule-ID- und Owner-Anreicherung
- Fehlerbehandlung des Hooks
- Verhalten bei Transaktionen und Rollbacks
- Versionierung des Zielschemas
- Berechnung von `Rows Tested`
- Vermeidung doppelter Inserts bei Wiederholungen

### 3. Inkrementelles History Model

Eine zusätzliche dbt-Ausführung liest normalisierte Run-Metadaten oder eine Staging-Tabelle und hängt neue Resultate inkrementell an.

```sql
{{ config(
    materialized='incremental',
    unique_key=['run_id', 'dbt_unique_id']
) }}

select
    run_id,
    rule_id,
    dbt_unique_id,
    test_status,
    rows_tested,
    rows_failed,
    failure_rate,
    executed_at
from {{ ref('stg_dbt_test_results') }}

{% if is_incremental() %}

where executed_at >
    (
        select coalesce(max(executed_at), '1900-01-01')
        from {{ this }}
    )

{% endif %}
```

Das Modell darf nicht nur die aktuellen `store_failures`-Tabellen lesen, weil bestandene Tests dort keine historischen Fehler mehr besitzen.

Es benötigt einen persistenten Eingang aus:

- Artefakt-Loader
- on-run-end Staging
- Orchestrator-API
- Event- oder Log-Export

## Die `invocation_id` als Run ID

dbt erzeugt für jeden Command eine UUID namens `invocation_id`.

Sie eignet sich als technische Run ID:

```text
dbt invocation
→ invocation_id
→ mehrere Modelle und Tests
→ gemeinsame Run-Gruppe
```

Bei einer übergeordneten Orchestrierung kann zusätzlich eine fachliche Batch-ID sinnvoll sein:

| Feld | Beispiel |
| --- | --- |
| **dbt Invocation ID** | konkrete Ausführung von `dbt build` oder `dbt test` |
| **Pipeline Run ID** | gesamter Ablauf aus Ingestion, dbt, Export und Reporting |
| **Business Batch ID** | fachlicher Verarbeitungstag oder Monatsabschluss |

Die Historientabelle kann alle drei Identitäten speichern.

## Statuswerte normalisieren

dbt und das plattformübergreifende DQ-Modell können unterschiedliche Statuswerte verwenden.

Ein mögliches Mapping:

| dbt Status | Standardstatus |
| --- | --- |
| `pass` | Passed |
| `warn` | Warning |
| `fail` | Failed |
| `error` | Error |
| `skipped` | Skipped |

`warn` sollte nicht automatisch mit `Failed` zusammengefasst werden.

Eine Warning bedeutet:

- die Regel wurde ausgeführt
- ein definierter Warnschwellenwert wurde verletzt
- der Error-Schwellenwert wurde nicht überschritten
- die Ausführung darf gemäß Konfiguration fortgesetzt werden

Qlik und Power BI sollten Warnungen separat darstellen.

## DG Tools als regelgetriebene Automatisierung

Die DG Tools verschieben die Arbeit von wiederholtem Handcoding zu einer zentralen Regeldefinition.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test3-img2-de.png"
        alt="DG Tools erzeugen aus einer zentralen Regeldefinition wiederverwendbare dbt Test-Makros, YAML-Testdefinitionen und ein inkrementelles DQ-Historienmodell, das Fehlertabellen und Testresultate für Qlik und Power BI standardisiert"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Regel wird einmal definiert. Macro Generator, Rules Generator und History Generator erzeugen daraus reproduzierbare dbt-Artefakte und eine einheitliche historische Ergebnisschicht.
    </figcaption>
</figure>

Die Toolchain besitzt drei getrennte Aufgaben.

### DG Macro Generator

Der DG Macro Generator erstellt wiederverwendbare Custom Generic Tests.

Mögliche Eingaben:

- Test Type
- unterstützte Datentypen
- Rule Arguments
- Fehlerbedingung
- optionaler Scope
- Fail Calculation
- adapterabhängige SQL-Variante
- standardisierte Evidenzspalten

Mögliche Ausgaben:

```text
tests/generic/value_between.sql
tests/generic/required_when.sql
tests/generic/valid_date_interval.sql
tests/generic/unique_combination.sql
```

Der Generator sollte sicherstellen:

- einheitliche Benennung
- konsistente Parameter
- identische Nullbehandlung
- kontrollierte `fail_calc`-Semantik
- dokumentierte Ergebnisstruktur
- Wiederverwendbarkeit über Projekte
- optionales Adapter Dispatching

### DQ Rules Generator

Der DQ Rules Generator übersetzt Governance-Regeln in ausführbare YAML-Definitionen.

Eingaben können stammen aus:

- zentralem Rule Repository
- Spreadsheet
- Data-Governance-Portal
- Data-Product-Konfiguration
- versionierter YAML- oder JSON-Datei

Die generierte Definition enthält beispielsweise:

- Rule ID
- dbt Test Name
- Modell und Spalte
- Test Type
- Argumente
- Threshold
- Severity
- Owner
- Data Product
- Tags
- `store_failures`
- Aktivstatus
- Rule Version

Ausgabe:

```text
models/customer/schema.yml
models/sales/schema.yml
models/finance/schema.yml
```

Damit werden Governance-Regeln reproduzierbar in dbt überführt.

### DQ History Generator

Der DQ History Generator baut die operative Ergebnisschicht.

Er kann erzeugen:

- Schema für `DQ_TEST_HISTORY`
- Artefakt-Staging
- Mapping aus dbt Unique ID zu Rule ID
- `on-run-end` Macro
- inkrementelles History Model
- Statusnormalisierung
- Berechnung von `Rows Tested`
- Berechnung von Failure Rate
- View für Qlik und Power BI
- optionale Failure-Detail-Referenzen

Eine mögliche Struktur:

```text
models/dq/stg_dbt_run_results.sql
models/dq/int_dbt_test_metrics.sql
models/dq/dq_test_history.sql
models/dq/v_dq_monitoring.sql
macros/dq/persist_dq_results.sql
```

## Eine zentrale Rule Definition als Single Source of Truth

Ein regelgetriebener Ansatz trennt Definition und Generierung.

Beispiel:

```yaml
rule_id: DQ-102
name: Sales amount within accepted range
data_product: Sales
owner: Data Steward Sales
severity: High
target:
  model: fact_sales
  column: amount
test:
  type: value_between
  arguments:
    min_value: 0
    max_value: 1000000
thresholds:
  warn_if: "> 0"
  error_if: "> 100"
execution:
  engine: dbt
  store_failures: true
history:
  rows_tested_strategy: model_count
  enabled: true
version: 3
```

Daraus entstehen:

```flowchart
Rule Definition
dbt Macro
schema.yml
Failure Table
History Model
Monitoring View
```

Die Regeldefinition ist damit nicht nur Dokumentation. Sie ist die Quelle für Code, Ausführung und Monitoring.

## Failure Tables standardisieren, ohne Evidenz zu verlieren

dbt speichert grundsätzlich die Spalten, welche die jeweilige Testabfrage zurückgibt.

Das ist für die Ursachenanalyse hilfreich, führt aber zu heterogenen Fehlertabellen.

Ein Not-Null-Test kann zurückgeben:

```text
customer_id
email
source_system
```

Ein Duplikattest kann zurückgeben:

```text
order_id
n_records
```

Ein Beziehungen-Test kann zurückgeben:

```text
product_id
```

Die Failure Tables müssen daher nicht in ein starres gemeinsames Detailformat gezwungen werden.

Besser ist eine Trennung:

```text
DQ_TEST_HISTORY
→ vollständig standardisiert

DBT_TEST__AUDIT.*
→ testspezifische Evidenz

DQ_FAILURE_INDEX
→ standardisierter Verweis auf die Evidenz
```

Ein Failure Index kann enthalten:

- Run ID
- Rule ID
- dbt Unique ID
- Failure Relation
- Anzahl gespeicherter Fehlerzeilen
- Business-Key-Spalte
- Retention-Klasse
- PII-Klassifikation
- Details URI

So bleibt das Monitoring einheitlich, ohne die fachliche Evidenz zu verlieren.

## Datenschutz und Aufbewahrung

`store_failures` kann personenbezogene oder vertrauliche Werte duplizieren.

Deshalb müssen für Audit-Tabellen Regeln gelten:

- nur erforderliche Spalten zurückgeben
- PII möglichst maskieren oder hashen
- Business Key statt vollständigem Datensatz speichern
- Zugriff auf das Audit-Schema einschränken
- Aufbewahrungsdauer definieren
- Entwicklungs- und Produktionsschemas trennen
- sehr große Fehlermengen begrenzen
- Fehlerrelationen nach Rule ID klassifizieren

Ein Custom Generic Test sollte nicht pauschal `select *` verwenden, wenn für die Behebung nur drei Spalten benötigt werden.

## Qlik und Power BI konsumieren die Historie

Qlik und Power BI sollten nicht direkt alle heterogenen Audit-Tabellen laden.

Die bevorzugte Architektur lautet:

```flowchart
dbt Test Results
DQ_TEST_HISTORY
Governed Monitoring View
Qlik / Power BI
```

Typische Auswertungen:

- Failure Rate over Time
- Rule Pass Rate
- Warnungen und Fehler nach Severity
- fehlgeschlagene Regeln nach Data Product
- wiederkehrende Fehler nach Rule ID
- Laufzeit je Test
- aktuelle Failure Relation
- letzte erfolgreiche Ausführung
- Tests ohne aktuellen Lauf
- Zeit zwischen Fehler und Behebung
- Re-Test nach Korrektur

### Zeilenfehlerrate

```text
Sum(Rows Failed) / Sum(Rows Tested)
```

### Regel-Pass-Rate

```text
Passed Tests / Executed Tests
```

### Execution Coverage

```text
Executed Active Rules / Active Rules
```

Coverage ist wichtig. Ein scheinbar grünes Dashboard ist wertlos, wenn ein Teil der aktiven Regeln gar nicht ausgeführt wurde.

## Praktisches dbt-Zielbild

Ein produktiver Aufbau kann folgende Strukturen verwenden:

```flow linear vertical
Governance Rule Repository
Generated dbt Tests and YAML
dbt Execution and Audit Tables
Artifact or Hook Staging
DQ_TEST_HISTORY
DQ Monitoring View
Qlik / Power BI
```

Empfohlene Komponenten:

| Komponente | Aufgabe |
| --- | --- |
| **Rule Repository** | fachliche Regel, Owner, Severity, Version |
| **DG Macro Generator** | wiederverwendbare Testlogik |
| **DQ Rules Generator** | ausführbare YAML-Instanzen |
| **dbt Data Tests** | technische Ausführung |
| **store_failures** | aktuelle fehlerhafte Datensätze |
| **Artifact Loader / Hook** | Resultate je Invocation erfassen |
| **DQ History Generator** | standardisierte Historie |
| **Monitoring View** | BI-freundliche Ergebnisschicht |

## Ein sinnvoller erster Sprint

1. Eine dbt-Anwendung mit vier Regeln auswählen.
2. Stabile Rule IDs und benutzerdefinierte Testnamen vergeben.
3. `store_failures` für kritische Regeln aktivieren.
4. Fehlerrelationen in ein kontrolliertes Audit-Schema schreiben.
5. `run_results.json` und `manifest.json` je Invocation sichern.
6. Eine Staging-Tabelle für Testresultate aufbauen.
7. `Rows Tested` für die vier Regeln eindeutig definieren.
8. Eine append-orientierte `DQ_TEST_HISTORY` erzeugen.
9. Status, Owner, Severity und Data Product ergänzen.
10. Eine Monitoring View für Qlik oder Power BI bereitstellen.
11. Danach die drei Generatoren schrittweise einsetzen.

Der erste Prototyp sollte den vollständigen vertikalen Ablauf beweisen:

```flowchart
Rule
YAML
dbt Test
Failure Evidence
History Row
Dashboard
Owner
```

## Typische Fehlentscheidungen

### `store_failures` mit Historisierung verwechseln

Die Audit-Tabelle enthält den aktuellen Fehlerbestand und ersetzt vorherige Ergebnisse.

### Nur fehlgeschlagene Tests speichern

Dadurch fehlen bestandene Läufe und eine korrekte Pass Rate.

### `Rows Failed` ohne Semantik verwenden

Bei Duplikaten kann eine Zeile eine Gruppe oder viele Datensätze repräsentieren.

### `Rows Tested` aus dem dbt-Resultat erwarten

Diese Kennzahl muss normalerweise zusätzlich berechnet werden.

### Automatisch generierte Testnamen als dauerhafte Governance-ID verwenden

Änderungen an Argumenten oder Modellen können technische Identitäten verändern. Eine stabile Rule ID sollte separat verwaltet werden.

### Alle Audit-Tabellen direkt in Qlik oder Power BI laden

Die Strukturen sind testspezifisch und nicht als gemeinsames Faktenmodell gedacht.

### Fehlerdetails unbegrenzt speichern

Audit-Tabellen können groß werden und sensible Daten enthalten.

### DG Tools nur als Codegenerator betrachten

Der entscheidende Nutzen liegt nicht nur in weniger Handarbeit. Die Generatoren erzwingen einen gemeinsamen Regelvertrag von Governance über Ausführung bis Historie.

## Die zentrale Erkenntnis

> **dbt liefert eine starke Test-Engine und kann aktuelle Fehler direkt persistieren. Für echte Operational Data Quality benötigt es zusätzlich eine append-orientierte Historientabelle.**

Generic Tests und Custom Tests bilden die ausführbare Regel.

YAML verbindet die Regel mit Modellen, Argumenten, Severity, Schwellenwerten und `store_failures`.

Audit-Tabellen speichern die aktuelle Evidenz.

`run_results.json`, `manifest.json`, `invocation_id` und der `on-run-end`-Kontext liefern die technischen Metadaten für eine Historisierung.

Die DG Tools automatisieren anschließend die vollständige Kette:

```flowchart
DG Macro Generator
DQ Rules Generator
DQ History Generator
```

Damit wird aus einzelnen dbt-Tests eine regelgetriebene, versionierte und in Qlik oder Power BI auswertbare Data-Quality-Plattform.

## Passende Playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [Part 2 — Data Quality in Microsoft Fabric](/playbooks/dq-test2)
- [Welche Rolle dbt spielt](/playbooks/dbt-role)
- [The Missing Pieces — Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality & Governance](/playbooks/data-quality-governance)

## Quellen und weiterführende Dokumentation

- [dbt — Add Data Tests to Your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [dbt — Data Tests Property](https://docs.getdbt.com/reference/resource-properties/data-tests)
- [dbt — store_failures](https://docs.getdbt.com/reference/resource-configs/store_failures)
- [dbt — fail_calc](https://docs.getdbt.com/reference/resource-configs/fail_calc)
- [dbt — severity, error_if and warn_if](https://docs.getdbt.com/reference/resource-configs/severity)
- [dbt — Run Results JSON](https://docs.getdbt.com/reference/artifacts/run-results-json)
- [dbt — on-run-end Context](https://docs.getdbt.com/reference/dbt-jinja-functions/on-run-end-context)
- [dbt — invocation_id](https://docs.getdbt.com/reference/dbt-jinja-functions/invocation_id)

> **Stand der Funktionsbeschreibung:** Juli 2026. dbt-Syntax, unterstützte Konfigurationen und Artefaktschemas können sich zwischen dbt Core, Fusion und der dbt-Plattform verändern. Für die konkrete Implementierung sollte die Dokumentation der eingesetzten Version geprüft werden.
