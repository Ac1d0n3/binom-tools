---
title: Eine Regel, drei Plattformen — Eine fachliche Datenqualitätsregel in Fabric, dbt und Databricks
description: Wie dieselbe fachliche Datenqualitätsregel in Microsoft Fabric, dbt und Databricks unterschiedlich implementiert, aber mit identischer Semantik und einem gemeinsamen standardisierten Ergebnis überwacht wird.
category: Datenqualität
tags:
  - data-quality
  - operational-data-quality
  - microsoft-fabric
  - dbt
  - databricks
  - data-contract
  - quality-rules
  - standardized-results
  - qlik-sense
  - power-bi
  - data-governance
  - rule-registry
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 5
seriesTitle: Operative Datenqualität
hero: images/playbooks/dq-test5-hero.png
---

## Eine Regel ist fachlich — ihre Implementierung ist technisch

Datenqualitätsregeln sollten nicht mit dem Werkzeug beginnen.

Die fachliche Anforderung lautet nicht:

- Erstelle einen Fabric-Test.
- Schreibe einen dbt-Test.
- Definiere eine Databricks Expectation.

Sie lautet beispielsweise:

> **Jeder aktive Kunde benötigt eine gültige Customer ID, ein Land und ein Änderungsdatum innerhalb der letzten 24 Stunden.**

Diese Anforderung bleibt gleich, unabhängig davon, ob die Daten in Microsoft Fabric, über dbt oder in Databricks verarbeitet werden.

Die technische Umsetzung unterscheidet sich jedoch:

- Fabric kann die Regel mit Warehouse SQL, Notebook-Code, Pipelines oder teilweise mit Materialized Lake View Constraints ausführen.
- dbt beschreibt Tests in YAML und SQL und liefert die Datensätze zurück, die eine Assertion verletzen.
- Databricks definiert Expectations direkt an Streaming Tables oder Materialized Views und protokolliert deren Ergebnisse im Pipeline Event Log.

```flowchart
One Business Rule
Platform-Specific Implementation
Standardized Result Contract
Qlik / Power BI
Ownership and Remediation
```

> **Plattformneutralität bedeutet nicht, überall denselben Code zu verwenden. Sie bedeutet, überall dieselbe fachliche Semantik und dieselbe Ergebnisstruktur zu erhalten.**

## Die Beispielregel präzise definieren

Der Satz „Jeder aktive Kunde benötigt eine gültige Customer ID, ein Land und ein Änderungsdatum innerhalb der letzten 24 Stunden“ klingt eindeutig. Für eine reproduzierbare technische Umsetzung reicht er noch nicht aus.

Vor der Implementierung müssen mindestens folgende Fragen beantwortet werden:

| Frage | Festgelegte Semantik im Beispiel |
| --- | --- |
| Was ist ein aktiver Kunde? | `is_active = true` |
| Was bedeutet gültige Customer ID? | nicht `NULL` und nicht leer |
| Was bedeutet gültiges Land? | nicht `NULL` und nicht leer |
| Welches Datumsfeld wird geprüft? | `updated_at` |
| Welche Zeitzone gilt? | UTC |
| Was bedeutet „innerhalb der letzten 24 Stunden“? | `updated_at >= evaluation_timestamp - 24 Stunden` |
| Was geschieht bei zukünftigem Datum? | separate Plausibilitätsregel, nicht Bestandteil dieser Regel |
| Was geschieht bei `NULL` in `updated_at`? | Regelverletzung |
| Welche Datensätze bilden den Prüfumfang? | nur aktive Kunden |
| Wie werden Fehler gezählt? | eine Verletzung je Kunde und atomarem Check |

Diese Definition sollte in einer zentralen Rule Registry versioniert werden.

Ein möglicher fachlicher Header:

```text
Rule ID: DQ-CUST-ACTIVE-001
Rule Version: 1
Data Product: Customer 360
Owner: Data Steward CRM
Severity: High
Scope: Active customers
Evaluation Time Zone: UTC
```

## Eine Business Rule wird in atomare Checks zerlegt

Für Monitoring und Ursachenanalyse ist es sinnvoll, die zusammengesetzte Regel in drei atomare Checks zu zerlegen:

| Check ID | Check | Fehlerbedingung |
| --- | --- | --- |
| **DQ-CUST-ACTIVE-001-A** | Customer ID vorhanden | aktive Zeile und Customer ID ist `NULL` oder leer |
| **DQ-CUST-ACTIVE-001-B** | Land vorhanden | aktive Zeile und Land ist `NULL` oder leer |
| **DQ-CUST-ACTIVE-001-C** | Änderungsdatum aktuell | aktive Zeile und `updated_at` ist `NULL` oder älter als 24 Stunden |

Dadurch entstehen zwei Ebenen:

```text
Business Rule
DQ-CUST-ACTIVE-001

Atomic Checks
DQ-CUST-ACTIVE-001-A
DQ-CUST-ACTIVE-001-B
DQ-CUST-ACTIVE-001-C
```

Das ist wichtig, weil ein Kunde mehrere Bedingungen gleichzeitig verletzen kann.

Beispiel:

```text
Customer C12345:
Customer ID fehlt
Land fehlt
Änderungsdatum ist zu alt
```

Die Summe der drei Check-Fehler wäre `3`. Fachlich ist aber nur ein Kunde betroffen.

Deshalb sollten zwei Kennzahlen getrennt werden:

- **Rule Violations:** Anzahl atomarer Regelverletzungen
- **Affected Entities:** Anzahl unterschiedlicher Kunden mit mindestens einer Verletzung

> **Fehlerzeilen über atomare Checks dürfen nicht einfach addiert werden, wenn dieselbe Entität mehrere Checks verletzen kann.**

## Gleiche Regel, unterschiedliche Implementierungen

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test5-img1-de.png"
        alt="Dieselbe fachliche Kundenregel mit Customer ID, Land und Änderungsdatum wird in Microsoft Fabric, dbt und Databricks mit unterschiedlichen nativen Mechanismen implementiert"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Implementierung folgt den Fähigkeiten der jeweiligen Plattform. Entscheidend ist, dass Scope, Zeitbezug, Fehlerbedingungen und Ergebnissemantik identisch bleiben.
    </figcaption>
</figure>

Das Schaubild zeigt bewusst unterschiedliche technische Formen:

- eine regel- oder SQL-basierte Fabric-Implementierung
- eine dbt-Definition in YAML mit wiederverwendbaren Data Tests
- Databricks Lakeflow Expectations

Die Codeblöcke im Schaubild sind als komprimierte Darstellung des Musters zu verstehen. Eine produktive Umsetzung benötigt zusätzlich:

- Run ID
- gemeinsamen Bewertungszeitpunkt
- Rule Version
- Fehlerbehandlung
- Historisierung
- Owner und Severity
- standardisiertes Result Mapping

## Der gemeinsame Laufzeitkontext

Selbst bei identischer Regel können Plattformen unterschiedliche Ergebnisse erzeugen, wenn sie nicht denselben Laufzeitkontext verwenden.

### Gemeinsamer Bewertungszeitpunkt

Diese beiden Bedingungen sind fachlich nicht vollständig identisch:

```text
Fabric prüft um 08:00:01
dbt prüft um 08:02:43
Databricks prüft um 08:05:17
```

Ein Kunde nahe der 24-Stunden-Grenze kann in einer Plattform bestehen und in einer anderen fehlschlagen.

Deshalb sollte die Orchestrierung einen gemeinsamen Wert vorgeben:

```text
Evaluation Timestamp:
2026-07-18T08:00:00Z
```

Alle Plattformen leiten daraus denselben Cutoff ab:

```text
Freshness Cutoff:
2026-07-17T08:00:00Z
```

### Gleicher Datensnapshot

Ein identischer Bewertungszeitpunkt reicht nicht, wenn die zugrunde liegenden Daten während der Tests verändert werden.

Für einen echten Reconciliation-Test benötigen die Plattformen:

- denselben Snapshot
- dieselbe Delta-Version
- denselben Batch oder Watermark
- dieselbe Quellversion
- oder eine fachlich identische replizierte Datenmenge

### Gleiche Null- und Leerwertsemantik

Plattformen und Quellsysteme können unterschiedlich mit folgenden Werten umgehen:

```text
NULL
''
'   '
'UNKNOWN'
'N/A'
```

Die Rule Registry muss festlegen, welche Werte als fehlend gelten.

Im Beispiel:

```text
NULL, leer und nur aus Leerzeichen bestehend
→ ungültig
```

### Gleicher Grain

Die Regel prüft aktive Kunden.

Nicht geprüft werden:

- historische inaktive Kunden
- technische Zwischenstände
- mehrfach vorhandene Quellzeilen, wenn das Data Product bereits eine Kundenzeile je Kunde verspricht
- gelöschte Datensätze außerhalb des aktuellen fachlichen Scopes

## Umsetzung in Microsoft Fabric

Für die vollständige Beispielregel ist ein SQL- oder Notebook-Test meist der direkteste Weg.

Materialized Lake Views unterstützen deklarative Constraints mit `DROP` oder `FAIL`. Aktuelle Einschränkungen bei Constraint-Ausdrücken können jedoch dynamische oder funktionsbasierte Prüfungen begrenzen. Eine relative 24-Stunden-Prüfung sollte deshalb vor der Umsetzung gegen die aktuelle Fabric-Dokumentation geprüft oder mit Warehouse SQL beziehungsweise Notebook-Code ausgeführt werden.

### T-SQL-Muster im Fabric Warehouse

Die Orchestrierung übergibt `Run ID` und `Evaluation Timestamp`.

```sql
DECLARE @run_id VARCHAR(100) =
    'RUN_20260718_080000';

DECLARE @evaluation_ts DATETIME2(6) =
    '2026-07-18T08:00:00';

WITH active_customers AS (
    SELECT
        customer_id,
        country,
        updated_at
    FROM silver.customer
    WHERE is_active = 1
),
check_metrics AS (
    SELECT
        'DQ-CUST-ACTIVE-001-A' AS check_id,
        'customer_id_required' AS check_name,
        COUNT_BIG(*) AS rows_tested,
        SUM(
            CASE
                WHEN customer_id IS NULL
                  OR LTRIM(RTRIM(customer_id)) = ''
                THEN CAST(1 AS BIGINT)
                ELSE CAST(0 AS BIGINT)
            END
        ) AS rows_failed
    FROM active_customers

    UNION ALL

    SELECT
        'DQ-CUST-ACTIVE-001-B',
        'country_required',
        COUNT_BIG(*),
        SUM(
            CASE
                WHEN country IS NULL
                  OR LTRIM(RTRIM(country)) = ''
                THEN CAST(1 AS BIGINT)
                ELSE CAST(0 AS BIGINT)
            END
        )
    FROM active_customers

    UNION ALL

    SELECT
        'DQ-CUST-ACTIVE-001-C',
        'updated_within_24h',
        COUNT_BIG(*),
        SUM(
            CASE
                WHEN updated_at IS NULL
                  OR updated_at <
                     DATEADD(HOUR, -24, @evaluation_ts)
                THEN CAST(1 AS BIGINT)
                ELSE CAST(0 AS BIGINT)
            END
        )
    FROM active_customers
)
SELECT
    @run_id AS run_id,
    check_id,
    check_name,
    rows_tested,
    rows_failed,
    CAST(rows_failed AS DECIMAL(18,8))
        / NULLIF(
            CAST(rows_tested AS DECIMAL(18,8)),
            0
        ) AS failure_rate,
    @evaluation_ts AS evaluation_timestamp
FROM check_metrics;
```

Das Ergebnis wird anschließend in die zentrale DQ-Historie geschrieben.

### Fehlerdetails in Fabric

Für Root-Cause-Analysen kann zusätzlich eine Detailtabelle erzeugt werden:

```sql
SELECT
    @run_id AS run_id,
    'DQ-CUST-ACTIVE-001-B' AS check_id,
    customer_id AS entity_id,
    'country is missing' AS result_detail
FROM silver.customer
WHERE is_active = 1
  AND (
      country IS NULL
      OR LTRIM(RTRIM(country)) = ''
  );
```

Die aggregierte Ergebniszeile und die Detailzeilen gehören in unterschiedliche Tabellen.

## Umsetzung mit dbt

dbt Data Tests sind SQL-Abfragen, die fehlerhafte Datensätze zurückgeben.

Die zusammengesetzte Regel kann als wiederverwendbarer Generic Data Test implementiert und in YAML konfiguriert werden.

### YAML-Konfiguration

```yaml
version: 2

models:
  - name: dim_customer
    data_tests:
      - active_customer_contract:
          arguments:
            active_column: is_active
            customer_id_column: customer_id
            country_column: country
            updated_at_column: updated_at
            evaluation_timestamp: "2026-07-18 08:00:00+00:00"
            freshness_hours: 24
          config:
            store_failures: true
            severity: error
            meta:
              rule_id: DQ-CUST-ACTIVE-001
              owner: Data Steward CRM
              dq_severity: High
              rule_version: 1
```

Die aktuelle dbt-Syntax verwendet `data_tests:`. `tests:` bleibt aus Gründen der Abwärtskompatibilität weiterhin verfügbar.

### Generic Test

Ein Generic Test kann eine Zeile je atomarer Verletzung zurückgeben:

```sql
{% test active_customer_contract(
    model,
    active_column,
    customer_id_column,
    country_column,
    updated_at_column,
    evaluation_timestamp,
    freshness_hours
) %}

with scoped as (
    select *
    from {{ model }}
    where {{ active_column }} = true
),

violations as (
    select
        cast({{ customer_id_column }} as {{ dbt.type_string() }})
            as entity_id,
        'DQ-CUST-ACTIVE-001-A' as check_id,
        'customer_id_required' as check_name,
        'customer_id is missing' as result_detail
    from scoped
    where {{ customer_id_column }} is null
       or trim({{ customer_id_column }}) = ''

    union all

    select
        cast({{ customer_id_column }} as {{ dbt.type_string() }}),
        'DQ-CUST-ACTIVE-001-B',
        'country_required',
        'country is missing'
    from scoped
    where {{ country_column }} is null
       or trim({{ country_column }}) = ''

    union all

    select
        cast({{ customer_id_column }} as {{ dbt.type_string() }}),
        'DQ-CUST-ACTIVE-001-C',
        'updated_within_24h',
        'updated_at is older than 24 hours'
    from scoped
    where {{ updated_at_column }} is null
       or {{ dq_older_than_hours(
            updated_at_column,
            evaluation_timestamp,
            freshness_hours
       ) }}
)

select *
from violations

{% endtest %}
```

`dq_older_than_hours` steht hier für ein adapterfähiges Macro, das die passende Datumsarithmetik für das jeweilige Zielsystem generiert.

Beispiele für mögliche technische Übersetzungen:

```text
Fabric Warehouse:
updated_at < DATEADD(HOUR, -24, evaluation_timestamp)

Databricks SQL:
updated_at < evaluation_timestamp - INTERVAL 24 HOURS

Snowflake:
updated_at < DATEADD(HOUR, -24, evaluation_timestamp)
```

Damit bleibt die fachliche Regel stabil, während dbt plattformspezifisches SQL kompiliert.

### `store_failures` ist Detailablage, keine Historie

Mit `store_failures: true` persistiert dbt die fehlerhaften Datensätze in einer Audit-Tabelle.

Das ist für Ursachenanalyse sehr nützlich.

Die Audit-Tabelle ist jedoch keine historische Ergebnisfaktentabelle:

- dbt speichert die aktuellen Fehlerdatensätze des Tests.
- Ein späterer Lauf ersetzt die vorherigen Fehler für denselben Test.
- Ein erfolgreicher Lauf kann die vorherige Fehlerablage ersetzen.
- Historische Trends benötigen deshalb eine zusätzliche Append-Tabelle.

Die Historisierung kann erzeugt werden aus:

- `run_results.json`
- `manifest.json`
- der Audit-Tabelle
- Run-Metadaten
- Rule-Metadaten aus YAML
- einem nachgelagerten DQ History Model

```flowchart
dbt Test
Audit Failure Table
dbt Artifacts
DQ History Model
Standardized Result
```

## Umsetzung in Databricks

Databricks Lakeflow Expectations werden direkt an einem Dataset definiert.

Für eine vergleichbare Messung eignet sich zunächst das Warn-Verhalten, weil ungültige Daten nicht entfernt werden und der Flow nicht beim ersten Fehler stoppt.

### SQL-Beispiel

```sql
CREATE OR REFRESH STREAMING TABLE silver.customer_validated (
    CONSTRAINT active_customer_id_required
        EXPECT (
            NOT is_active
            OR (
                customer_id IS NOT NULL
                AND TRIM(customer_id) <> ''
            )
        ),

    CONSTRAINT active_customer_country_required
        EXPECT (
            NOT is_active
            OR (
                country IS NOT NULL
                AND TRIM(country) <> ''
            )
        ),

    CONSTRAINT active_customer_updated_within_24h
        EXPECT (
            NOT is_active
            OR (
                updated_at IS NOT NULL
                AND updated_at >=
                    current_timestamp() - INTERVAL 24 HOURS
            )
        )
)
AS
SELECT *
FROM STREAM(bronze.customer);
```

Ohne `ON VIOLATION` protokolliert Databricks die Verstöße als Warnungen und verarbeitet die Datensätze weiter.

Alternative Reaktionen:

```sql
ON VIOLATION DROP ROW
```

```sql
ON VIOLATION FAIL UPDATE
```

Diese Varianten verändern jedoch die technische Semantik:

- `DROP` entfernt Datensätze aus dem Ziel.
- `FAIL` kann die betroffene Aktualisierung frühzeitig stoppen.
- Bei frühem Fail stehen nicht zwingend vollständige Metriken für alle Datensätze bereit.

Für einen reinen Plattformvergleich sollte die Action deshalb explizit dokumentiert und nicht mit Severity verwechselt werden.

### Gemeinsamen Bewertungszeitpunkt verwenden

Das Beispiel verwendet zur Lesbarkeit `current_timestamp()`.

Für eine echte Reconciliation sollte der gemeinsame Bewertungszeitpunkt als Pipeline-Konfiguration oder Parameter bereitgestellt werden.

Konzeptionell:

```text
evaluation_timestamp =
2026-07-18T08:00:00Z
```

Die Expectation prüft dann gegen:

```text
evaluation_timestamp - 24 hours
```

### Event Log als technische Ergebnisquelle

Databricks protokolliert Expectation-Metriken im Pipeline Event Log.

Für atomare Checks können unter anderem verwendet werden:

- Expectation Name
- Dataset
- Passed Records
- Failed Records
- Pipeline Update ID
- Flow Name
- Event Timestamp

Eine Normalisierungsschicht mappt:

```text
active_customer_id_required
→ DQ-CUST-ACTIVE-001-A

active_customer_country_required
→ DQ-CUST-ACTIVE-001-B

active_customer_updated_within_24h
→ DQ-CUST-ACTIVE-001-C
```

## Drei Plattformen — drei native Rohresultate

| Aspekt | Microsoft Fabric | dbt | Databricks |
| --- | --- | --- | --- |
| Primäre Implementierung | SQL, Notebook oder Pipeline | Generic oder Singular Data Test | Lakeflow Expectation |
| Regelkonfiguration | SQL, Metadaten oder Generator | YAML und SQL Macro | SQL oder Python |
| Rohresultat | Query-/Notebook-Ergebnis | fehlerhafte Datensätze und dbt Artifact | Event-Log-Metriken |
| Fehlerdetails | separate Delta- oder Warehouse-Tabelle | Audit-Tabelle durch `store_failures` | Quarantäne oder separates Fehlerdataset |
| Historisierung | explizites Append | zusätzliche History erforderlich | Event Log ist historisch, aber muss kuratiert werden |
| Enforcement | frei implementierbar; MLV unterstützt Drop/Fail | Warn/Error-Semantik im Testlauf | Warn, Drop oder Fail |
| Gemeinsame Ausgabe | DQ Result Table | DQ History Model | DQ History Delta Table |

Die Rohresultate sind nicht identisch.

Die fachliche Aussage kann trotzdem identisch sein.

## Ein gemeinsames standardisiertes Ergebnis

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test5-img2-de.png"
        alt="Microsoft Fabric, dbt und Databricks schreiben Ergebnisse derselben fachlichen Kundenregel in eine gemeinsame standardisierte DQ-Ergebnisstruktur"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Plattformname und Ausführungsmechanismus dürfen variieren. Rule ID, Check ID, Scope, Status, Zählweise und Ergebnissemantik müssen stabil bleiben.
    </figcaption>
</figure>

Für die Serie bleibt die aggregierte Ergebnistabelle der zentrale Vertrag.

### Aggregierte DQ-Ergebnistabelle

Grain:

> **Eine Zeile je Plattform, Run und atomarem Check.**

Empfohlene Felder:

| Feld | Beispiel |
| --- | --- |
| Run ID | `RUN_20260718_080000` |
| Rule ID | `DQ-CUST-ACTIVE-001` |
| Check ID | `DQ-CUST-ACTIVE-001-B` |
| Rule Version | `1` |
| Platform | `dbt` |
| Execution Method | `Generic Data Test` |
| Data Product | `Customer 360` |
| Table | `DIM_CUSTOMER` |
| Column | `COUNTRY` |
| Test Type | `Conditional Not Null` |
| Status | `Failed` |
| Rows Tested | `250000` |
| Rows Failed | `382` |
| Failure Rate | `0.001528` |
| Evaluation Timestamp | `2026-07-18T08:00:00Z` |
| Executed At | tatsächlicher technischer Zeitpunkt |
| Owner | `Data Steward CRM` |
| Severity | `High` |
| Source Execution ID | Pipeline-, dbt- oder Update-ID |
| Metric Completeness | `Complete` |

### Optionale DQ-Fehlerdetailtabelle

Grain:

> **Eine Zeile je Plattform, Run, atomarem Check und betroffener Entität.**

Beispiel:

| Run ID | Check ID | Platform | Entity ID | Result Detail |
| --- | --- | --- | --- | --- |
| RUN-001 | ...-A | Fabric | C12345 | Customer ID fehlt |
| RUN-001 | ...-B | dbt | C12345 | Land fehlt |
| RUN-001 | ...-C | Databricks | C12345 | Änderungsdatum älter als 24 Stunden |

Das Schaubild verwendet zur Veranschaulichung Detailzeilen. Für Trends und KPIs sollte daraus zusätzlich die aggregierte Ergebnisfaktentabelle entstehen.

## Status normalisieren

Die Plattformen verwenden unterschiedliche Begriffe.

Ein mögliches Mapping:

| Plattformresultat | Gemeinsamer Status |
| --- | --- |
| Fabric: 0 Fehler | Passed |
| Fabric: Threshold verletzt | Failed |
| dbt: pass | Passed |
| dbt: warn | Warning |
| dbt: fail | Failed |
| dbt: error | Error |
| Databricks Warn mit 0 Fehlern | Passed |
| Databricks Warn mit Fehlern | Warning |
| Databricks Drop mit Fehlern | Filtered |
| Databricks Fail Update | Failed Early |
| technischer Laufzeitfehler | Error |

Die Action wird zusätzlich separat gespeichert:

```text
Action:
Observe
Warn
Drop
Fail
```

Severity bleibt ein eigenes fachliches Feld.

## Ergebnisse vergleichbar machen

Damit die drei Resultate wirklich vergleichbar sind, müssen mindestens acht Bedingungen erfüllt sein:

1. **Gleiche Rule Version**
2. **Gleicher aktiver Scope**
3. **Gleicher Bewertungszeitpunkt**
4. **Gleiche Zeitzone**
5. **Gleicher Datensnapshot oder Watermark**
6. **Gleiche Null- und Leerwertsemantik**
7. **Gleiche Zählweise**
8. **Gleiche Status- und Threshold-Logik**

Erst dann ist ein Ergebnisvergleich sinnvoll.

```text
Same business sentence
≠ automatically comparable execution
```

Vergleichbarkeit entsteht durch den technischen Vertrag.

## Reconciliation: Sollten die Ergebnisse identisch sein?

Wenn wirklich derselbe Datensnapshot auf allen drei Plattformen geprüft wird, sollten für jeden atomaren Check gelten:

```text
Rows Tested: identisch
Rows Failed: identisch
Failure Rate: identisch
Affected Entity Set: identisch
```

Abweichungen sind dann selbst ein Qualitäts- oder Replikationssignal.

Beispiele:

- Fabric sieht 250.000 aktive Kunden, Databricks nur 249.980.
- dbt findet 382 fehlende Länder, Fabric 380.
- Databricks bewertet zwölf Kunden als veraltet, dbt nur zehn.
- eine Plattform normalisiert Leerzeichen, eine andere nicht.
- Zeitstempel wurden in unterschiedlichen Zeitzonen interpretiert.

Eine Reconciliation-Tabelle kann enthalten:

| Run ID | Check ID | Fabric Failed | dbt Failed | Databricks Failed | Reconciled |
| --- | --- | ---: | ---: | ---: | --- |
| RUN-001 | ...-A | 15 | 15 | 15 | Yes |
| RUN-001 | ...-B | 382 | 380 | 382 | No |
| RUN-001 | ...-C | 12 | 10 | 12 | No |

Dies ist vor allem bei Migrationen, Parallelbetrieb oder der Ablösung einer Plattform hilfreich.

In einem normalen Zielbetrieb muss eine Regel nicht auf drei Plattformen gleichzeitig ausgeführt werden. Entscheidend ist, dass jede Plattform denselben Output-Vertrag erfüllen kann.

## Rule Registry als Single Source of Truth

Die fachliche Definition sollte nicht dreimal manuell gepflegt werden.

Eine zentrale Rule Registry kann enthalten:

```yaml
rule_id: DQ-CUST-ACTIVE-001
version: 1
data_product: Customer 360
scope:
  expression: is_active = true
checks:
  - check_id: DQ-CUST-ACTIVE-001-A
    type: required
    column: customer_id
    empty_is_invalid: true
  - check_id: DQ-CUST-ACTIVE-001-B
    type: required
    column: country
    empty_is_invalid: true
  - check_id: DQ-CUST-ACTIVE-001-C
    type: freshness
    column: updated_at
    max_age_hours: 24
owner: Data Steward CRM
severity: High
timezone: UTC
```

Generatoren können daraus erzeugen:

```flowchart
Rule Registry
Fabric SQL / Notebook Definition
dbt YAML and Test Macro
Databricks Expectations
Result Mapping
```

Dies knüpft an die in Part 3 beschriebenen DG Tools an:

- DG Macro Generator
- DQ Rules Generator
- DQ History Generator

Der Generator ersetzt nicht die fachliche Freigabe. Er reduziert jedoch Copy-and-paste, Namensabweichungen und inkonsistente Ergebnislogik.

## Versionierung der Regel

Eine Regeländerung darf historische Resultate nicht stillschweigend neu interpretieren.

Beispiel:

```text
Version 1:
maximales Alter = 24 Stunden

Version 2:
maximales Alter = 12 Stunden
```

Die `Rule ID` kann stabil bleiben, aber `Rule Version` muss sich ändern.

Historische Ergebniszeilen speichern:

- Rule ID
- Rule Version
- Threshold
- Evaluation Timestamp
- technische Implementierungsversion
- Source Execution ID

Dadurch kann BI unterscheiden:

```text
Trend innerhalb derselben Regelversion
versus
Sprung durch geänderte Regeldefinition
```

## Qlik- und Power-BI-Auswertung

Qlik und Power BI greifen auf dasselbe kuratierte Ergebnisdatenmodell zu.

Typische Perspektiven:

- Failure Rate nach Check
- Plattformvergleich
- Reconciliation-Differenzen
- Trends je Rule Version
- betroffene Kunden
- Fehler nach Owner und Severity
- Freshness-Verstöße
- Datenqualität vor und nach Migration
- technische Errors und unvollständige Metriken

### Zeilengewichtete Fehlerrate

Qlik:

```qlik
Num(
    Sum(rows_failed)
    /
    Sum(rows_tested),
    '0.00%'
)
```

Power BI:

```DAX
Failure Rate =
DIVIDE(
    SUM(DQ_Result[Rows Failed]),
    SUM(DQ_Result[Rows Tested])
)
```

### Betroffene Kunden

Wenn Failure Details verfügbar sind:

Qlik:

```qlik
Count(DISTINCT entity_id)
```

Power BI:

```DAX
Affected Customers =
DISTINCTCOUNT(DQ_Failure_Detail[Entity ID])
```

Diese Kennzahl darf nicht aus der Summe der atomaren `Rows Failed` abgeleitet werden.

## Welche Plattformimplementierung ist die beste?

Die Story soll keinen Produktsieger bestimmen.

Die passende Umsetzung hängt vom Ziel ab.

### Microsoft Fabric

Geeignet, wenn:

- Lakehouse und Warehouse bereits die zentrale Plattform bilden
- SQL-, Notebook- und Pipeline-Teams gemeinsam arbeiten
- Ergebnisse direkt als Delta- oder Warehouse-Tabelle bereitgestellt werden sollen
- Power BI eng integriert ist
- Purview oder Materialized Lake Views ergänzend genutzt werden

### dbt

Geeignet, wenn:

- Regeln als Code und YAML versioniert werden sollen
- dieselben Test-Macros in mehreren Modellen wiederverwendet werden
- SQL-Transformationen und Tests eng gekoppelt sind
- mehrere Zielplattformen unterstützt werden sollen
- eine Generator- und Artifact-basierte Historisierung aufgebaut wird

### Databricks

Geeignet, wenn:

- Qualität direkt in Streaming- und Pipeline-Verarbeitung durchgesetzt werden soll
- Warn, Drop und Fail native Pipeline-Aktionen sind
- Event Logs und Delta Tables die Observability-Basis bilden
- große oder kontinuierliche Datenmengen verarbeitet werden
- Lakeflow und Unity Catalog bereits die Plattformbasis bilden

> **Die Plattform bestimmt den Ausführungsmechanismus. Die Governance bestimmt die Regel.**

## Praktischer Pilot

Ein belastbarer Vergleich kann in einem kleinen Pilot aufgebaut werden:

1. Einen identischen Kundensnapshot bereitstellen.
2. Gemeinsame Rule ID und drei Check IDs definieren.
3. Bewertungszeitpunkt in UTC festlegen.
4. Fabric-Test gegen den Snapshot ausführen.
5. dbt-Test mit `store_failures` ausführen.
6. Databricks Expectations im Warn-Modus ausführen.
7. Rohresultate in das gemeinsame Schema überführen.
8. `Rows Tested`, `Rows Failed` und Entity Sets vergleichen.
9. Unterschiede analysieren und Semantik korrigieren.
10. Qlik oder Power BI auf die standardisierte Ergebnistabelle setzen.

## Typische Fehlentscheidungen

### Den gleichen Text mit gleicher Semantik verwechseln

Ein identischer Beschreibungssatz garantiert keine identische technische Auswertung.

### `current_timestamp()` überall separat verwenden

Nahe der 24-Stunden-Grenze entstehen unterschiedliche Ergebnisse.

### Atomare Fehler addieren und als betroffene Kunden darstellen

Ein Kunde kann mehrere Checks verletzen.

### dbt Audit-Tabellen als Historie behandeln

`store_failures` persistiert aktuelle Fehler, ersetzt aber frühere Ergebnisse desselben Tests.

### Databricks Drop als bestandene Qualität darstellen

Datensätze wurden entfernt. Die Verletzung bleibt relevant.

### Fabric-, dbt- und Databricks-Status direkt vergleichen

Die Statuswerte benötigen ein gemeinsames Mapping.

### Plattformcode zur führenden Regeldefinition machen

Dann entstehen drei fachliche Wahrheiten statt einer Regel.

### Rule Version nicht speichern

Eine Threshold-Änderung erscheint sonst wie eine plötzliche Qualitätsverschlechterung.

## Die zentrale Erkenntnis

> **Eine fachliche Regel kann auf Microsoft Fabric, dbt und Databricks unterschiedlich implementiert werden und trotzdem exakt dieselbe Datenqualitätsaussage liefern.**

Dafür benötigt sie:

- eine präzise fachliche Definition
- atomare Check IDs
- eine versionierte Rule Registry
- einen gemeinsamen Scope
- einen gemeinsamen Bewertungszeitpunkt
- identische Zähl- und Statussemantik
- eine standardisierte Ergebnisstruktur
- getrennte Aggregate und Fehlerdetails

So kann die Organisation die beste technische Plattform für den jeweiligen Datenprozess verwenden, ohne Governance, Monitoring und Vergleichbarkeit neu zu erfinden.

## Passende Playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [Part 2 — Data Quality in Microsoft Fabric](/playbooks/dq-test2)
- [Part 3 — Data Quality with dbt](/playbooks/dq-test3)
- [Part 4 — Data Quality in Databricks](/playbooks/dq-test4)
- [Data Quality & Governance](/playbooks/data-quality-governance)
- [Welche Rolle dbt spielt](/playbooks/dbt-role)

## Quellen und weiterführende Dokumentation

- [Microsoft Fabric — Data Quality in Materialized Lake Views](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/data-quality)
- [Microsoft Fabric — Materialized Lake Views Overview](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/overview-materialized-lake-view)
- [Microsoft Fabric — Data Quality Report for Materialized Lake Views](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/data-quality-reports)
- [dbt — Add Data Tests to Your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [dbt — Data Tests Property](https://docs.getdbt.com/reference/resource-properties/data-tests)
- [dbt — store_failures](https://docs.getdbt.com/reference/resource-configs/store_failures)
- [dbt — Run Results JSON](https://docs.getdbt.com/reference/artifacts/run-results-json)
- [Databricks — Manage Data Quality with Pipeline Expectations](https://docs.databricks.com/aws/en/ldp/expectations)
- [Databricks — Lakeflow Pipeline Best Practices](https://docs.databricks.com/aws/en/ldp/best-practices)
- [Databricks — Pipeline Event Log](https://docs.databricks.com/aws/en/ldp/monitor-event-logs)
- [Databricks — event_log Table-Valued Function](https://docs.databricks.com/aws/en/sql/language-manual/functions/event_log)

> **Stand der Funktionsbeschreibung:** Juli 2026. Plattformfunktionen, Syntax und Preview-Status können sich ändern. Vor der produktiven Umsetzung sollte die aktuelle Herstellerdokumentation geprüft werden.
