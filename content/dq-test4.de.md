---
title: Data Quality in Databricks — Expectations, Event Log und historische DQ-Auswertung
description: Wie Lakeflow Expectations Datenqualität in Streaming Tables und Materialized Views durchsetzen, Warn-, Drop- und Fail-Verhalten steuern und ihre Event-Log-Metriken in ein gemeinsames historisches Datenmodell für Qlik und Power BI überführt werden.
category: Datenqualität
tags:
  - data-quality
  - operational-data-quality
  - databricks
  - lakeflow
  - spark-declarative-pipelines
  - delta-live-tables
  - expectations
  - event-log
  - delta-lake
  - streaming-tables
  - materialized-views
  - qlik-sense
  - power-bi
  - data-governance
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 4
seriesTitle: Operational Data Quality
hero: images/playbooks/dq-test4-hero.png
---

## Expectations bringen Datenqualität direkt in die Databricks-Pipeline

Databricks Lakeflow Spark Declarative Pipelines können Datenqualitätsregeln direkt an Streaming Tables, Materialized Views und temporären Views ausführen.

Die Regeln heißen **Expectations**.

Eine Expectation besteht aus:

- einem stabilen Namen
- einem booleschen SQL-Ausdruck
- einer Reaktion auf ungültige Datensätze
- dem Dataset, auf dem sie ausgeführt wird

Die Prüfung erfolgt während des Datenflusses. Dadurch ist Datenqualität nicht nur ein nachgelagerter Bericht, sondern Teil der Verarbeitung.

```flowchart
Source Data
Streaming Table / Materialized View
Expectation
Warn / Drop / Fail
Pipeline Event Log
DQ History
Qlik / Power BI
```

Lakeflow stellt dabei zwei unterschiedliche Ebenen bereit:

1. **Durchsetzung:** Was soll mit einem ungültigen Datensatz geschehen?
2. **Beobachtung:** Wie werden Qualitätsergebnisse, Pipeline-Zustände und technische Ereignisse nachvollziehbar?

Expectations lösen die erste Aufgabe. Das Pipeline Event Log liefert die technische Grundlage für die zweite.

> **Operational Data Quality entsteht, wenn Expectations nicht nur Daten filtern oder Updates stoppen, sondern ihre Ergebnisse zusätzlich in ein stabiles historisches DQ-Modell überführt werden.**

## Von Delta Live Tables zu Lakeflow Pipelines

Das frühere Produkt **Delta Live Tables (DLT)** heißt inzwischen **Lakeflow Spark Declarative Pipelines** beziehungsweise Lakeflow Pipelines.

Bestehender DLT-Code bleibt laut Databricks grundsätzlich lauffähig. Für neue Python-Implementierungen empfiehlt Databricks jedoch die neue API:

```python
from pyspark import pipelines as dp
```

Statt älterer `dlt`-Dekoratoren werden neue `dp`-Dekoratoren verwendet:

| Früher | Aktuell |
| --- | --- |
| `import dlt` | `from pyspark import pipelines as dp` |
| `@dlt.table` | `@dp.table` |
| `@dlt.view` | `@dp.temporary_view` |
| DLT Pipeline | Lakeflow Pipeline |

In Event-Log-Strukturen, Konfigurationen und älteren Beispielen können weiterhin Bezeichnungen mit `dlt` vorkommen. Das Datenqualitätsprinzip ändert sich dadurch nicht.

## Streaming Table oder Materialized View

Expectations können auf unterschiedliche Dataset-Typen angewendet werden.

### Streaming Tables

Streaming Tables eignen sich besonders für:

- kontinuierlich oder inkrementell eintreffende Daten
- append-orientierte Quellen
- Auto Loader
- Kafka, Event Hubs oder andere Streaming-Quellen
- Bronze- und Silver-Verarbeitung mit Streaming-Semantik

Eine Streaming Table verarbeitet neue Daten inkrementell.

### Materialized Views

Materialized Views eignen sich besonders für:

- Batch-Transformationen
- aktuelle, vollständig berechnete Sichten
- Aggregationen
- Joins
- Geschäftslogik, die nicht rein append-orientiert ist
- kuratierte Silver- oder Gold-Datasets

Beide Dataset-Typen können Expectations enthalten. Der Unterschied liegt nicht in der Regeldefinition, sondern in der Verarbeitungssemantik des Datasets.

## Eine Expectation ist eine zeilenbasierte boolesche Regel

Eine Expectation bewertet jeden Datensatz mit einem Ausdruck, der `true` oder `false` liefert.

Beispiele:

```text
amount >= 0
country IS NOT NULL
order_timestamp <= current_timestamp()
status IN ('OPEN', 'CLOSED', 'CANCELLED')
```

Eine Regel kann auch komplexere boolesche Logik enthalten:

```sql
(
    order_type = 'SALE'
    AND amount > 0
)
OR
(
    order_type = 'REFUND'
    AND amount < 0
)
```

Die Constraints verwenden SQL-Ausdrücke — auch dann, wenn die Pipeline in Python entwickelt wird.

Nach der aktuellen Databricks-Dokumentation dürfen Expectations unter anderem nicht enthalten:

- benutzerdefinierte Python-Funktionen
- externe Service-Aufrufe
- Subqueries auf andere Tabellen

Das hat eine wichtige Konsequenz:

> **Nicht jede Datenqualitätsregel ist direkt als einfache Expectation abbildbar.**

Eine Eindeutigkeitsregel über mehrere Zeilen benötigt beispielsweise Aggregation, Window Logic, eine vorgelagerte View oder einen separaten Testprozess. `order_id IS NOT NULL` ist eine direkte Expectation. „`order_id` kommt genau einmal vor“ ist keine reine zeilenlokale Bedingung.

## Warn, Drop oder Fail

Wenn eine Regel verletzt wird, kann Databricks unterschiedlich reagieren.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test4-img1-de.png"
        alt="Databricks Expectations mit den drei Reaktionen Warnen, Verwerfen und Pipeline stoppen sowie den Auswirkungen auf Datenfluss, Event Log und nachgelagerte Verarbeitung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die gleiche Qualitätsregel kann je nach fachlicher Kritikalität nur protokollieren, ungültige Datensätze aus dem Ziel entfernen oder die betroffene Aktualisierung stoppen.
    </figcaption>
</figure>

### Warn — ungültige Daten behalten und protokollieren

`Warn` ist das Standardverhalten.

Ungültige Datensätze werden weiterhin in das Ziel geschrieben. Databricks erfasst jedoch, wie viele Datensätze die Regel bestanden oder verletzt haben.

Geeignet für:

- neue Regeln in einer Beobachtungsphase
- bekannte, aktuell tolerierte Abweichungen
- Regeln mit noch nicht abgestimmten Thresholds
- Daten, die downstream weiterhin benötigt werden
- Profiling und Baselining

Das Risiko:

> Ein Warnhinweis verbessert die Daten nicht automatisch.

Ein Owner, Threshold und Remediation-Prozess müssen festlegen, wann aus einem Warnsignal eine Maßnahme wird.

### Drop — ungültige Daten aus dem Ziel entfernen

Bei `Drop` werden Datensätze, die die Regel verletzen, vor dem Schreiben in das Ziel entfernt.

Geeignet für:

- technisch unbrauchbare Datensätze
- sicher isolierbare Fehler
- Regeln, bei denen die restlichen Daten weiterverarbeitet werden dürfen
- kuratierte Silver-Tabellen
- Prozesse mit separater Quarantäne oder Fehlerablage

Das Risiko:

> Das Ziel kann sauber aussehen, obwohl Datensätze verloren gegangen sind.

Deshalb müssen Drop-Anzahlen sichtbar bleiben. Eine abnehmende Datenmenge ist nicht automatisch steigende Datenqualität.

### Fail — betroffene Aktualisierung stoppen

Bei `Fail` verhindert ein ungültiger Datensatz den erfolgreichen Abschluss der betroffenen Aktualisierung beziehungsweise des betroffenen Flows.

Geeignet für:

- kritische Schlüssel
- regulatorisch relevante Daten
- Regeln, ohne deren Erfüllung das Ziel fachlich falsch wäre
- irreversible oder risikoreiche Downstream-Prozesse
- harte Data Contracts

Wichtig ist die technische Genauigkeit:

- In vereinfachten Architekturbildern wird dies oft als „Pipeline stoppen“ bezeichnet.
- Databricks beschreibt das Verhalten als Fehlschlag der betroffenen Aktualisierung beziehungsweise des betroffenen Flows.
- Andere unabhängige Flows innerhalb derselben Pipeline müssen dadurch nicht zwangsläufig ebenfalls fehlschlagen.

## SQL-Beispiele

### Warn als Standardverhalten

Ohne `ON VIOLATION` werden ungültige Datensätze im Ziel behalten:

```sql
CREATE OR REFRESH STREAMING TABLE silver.orders_warn (
    CONSTRAINT order_id_required
        EXPECT (order_id IS NOT NULL),

    CONSTRAINT amount_non_negative
        EXPECT (amount >= 0)
)
AS
SELECT *
FROM STREAM(bronze.orders);
```

Ergebnis:

- gültige Zeilen werden geschrieben
- ungültige Zeilen werden ebenfalls geschrieben
- Pass- und Failure-Metriken werden für die Expectations erfasst

### Ungültige Datensätze verwerfen

```sql
CREATE OR REFRESH STREAMING TABLE silver.orders_clean (
    CONSTRAINT order_id_required
        EXPECT (order_id IS NOT NULL)
        ON VIOLATION DROP ROW,

    CONSTRAINT amount_non_negative
        EXPECT (amount >= 0)
        ON VIOLATION DROP ROW
)
AS
SELECT *
FROM STREAM(bronze.orders);
```

Ergebnis:

- verletzende Zeilen werden nicht in `silver.orders_clean` geschrieben
- gültige Zeilen werden weiterverarbeitet
- Dropped- und Expectation-Metriken werden im Pipeline-Kontext erfasst

### Aktualisierung bei kritischem Verstoß stoppen

```sql
CREATE OR REFRESH MATERIALIZED VIEW gold.invoice_summary (
    CONSTRAINT invoice_id_required
        EXPECT (invoice_id IS NOT NULL)
        ON VIOLATION FAIL UPDATE,

    CONSTRAINT legal_entity_required
        EXPECT (legal_entity_id IS NOT NULL)
        ON VIOLATION FAIL UPDATE
)
AS
SELECT
    legal_entity_id,
    invoice_id,
    customer_id,
    amount,
    invoice_date
FROM silver.invoices;
```

Ergebnis:

- die Aktualisierung des betroffenen Flows schlägt fehl
- manuelle Korrektur oder kontrollierte Behandlung ist erforderlich
- nachgelagerte Verarbeitung darf das Ergebnis nicht als erfolgreich behandeln

## Python-Beispiele

Expectations werden als Dekoratoren zwischen Dataset-Dekorator und Funktion definiert.

### Warn

```python
from pyspark import pipelines as dp

@dp.table(name="orders_warn")
@dp.expect("order_id_required", "order_id IS NOT NULL")
@dp.expect("amount_non_negative", "amount >= 0")
def orders_warn():
    return spark.readStream.table("bronze.orders")
```

### Drop

```python
from pyspark import pipelines as dp

@dp.table(name="orders_clean")
@dp.expect_or_drop("order_id_required", "order_id IS NOT NULL")
@dp.expect_or_drop("amount_non_negative", "amount >= 0")
def orders_clean():
    return spark.readStream.table("bronze.orders")
```

### Fail

```python
from pyspark import pipelines as dp

@dp.materialized_view(name="invoice_summary")
@dp.expect_or_fail(
    "invoice_id_required",
    "invoice_id IS NOT NULL"
)
@dp.expect_or_fail(
    "legal_entity_required",
    "legal_entity_id IS NOT NULL"
)
def invoice_summary():
    return spark.table("silver.invoices")
```

### Mehrere Regeln aus einem Dictionary

Regeln können zentral vorbereitet und gemeinsam angewendet werden:

```python
from pyspark import pipelines as dp

order_rules = {
    "order_id_required": "order_id IS NOT NULL",
    "customer_id_required": "customer_id IS NOT NULL",
    "amount_non_negative": "amount >= 0",
    "valid_status": "status IN ('OPEN', 'CLOSED', 'CANCELLED')"
}

@dp.table(name="orders_validated")
@dp.expect_all(order_rules)
def orders_validated():
    return spark.readStream.table("bronze.orders")
```

Für andere Reaktionen stehen entsprechende Varianten zur Verfügung:

```python
@dp.expect_all_or_drop(order_rules)
```

```python
@dp.expect_all_or_fail(order_rules)
```

Jede Regel benötigt innerhalb eines Datasets einen eindeutigen Namen. Dieser Name ist später der wichtigste technische Schlüssel für Monitoring und Mapping.

## Erwartungsnamen als Rule IDs behandeln

Databricks verwendet den Namen einer Expectation zur Identifikation und Überwachung.

Beispiel:

```text
order_id_required
amount_non_negative
country_required
valid_order_status
```

Für das gemeinsame DQ-Datenmodell sollte daraus eine stabile Governance-Identität entstehen.

Zwei Varianten sind möglich.

### Erwartungsname ist direkt die Rule ID

```text
Rule ID = amount_non_negative
```

Das ist einfach, wenn Namen organisationsweit eindeutig und stabil sind.

### Erwartungsname wird auf eine zentrale Rule ID gemappt

```text
Pipeline ID + Dataset + Expectation Name
→ DQ-00427
```

Das ist belastbarer, wenn derselbe technische Name in mehreren Pipelines oder Data Products vorkommt.

Eine Regelregistry kann enthalten:

| Feld | Beispiel |
| --- | --- |
| Rule ID | DQ-00427 |
| Platform | Databricks |
| Pipeline ID | `a1b2...` |
| Dataset | `silver.orders_clean` |
| Expectation Name | `amount_non_negative` |
| Action | Drop |
| Data Product | Sales |
| Owner | Data Steward Sales |
| Severity | High |
| Threshold | 0 |
| Rule Version | 3 |

## Das Pipeline Event Log

Jede Lakeflow Pipeline besitzt ein Event Log.

Es enthält unter anderem:

- Pipeline- und Update-Ereignisse
- Datenqualitätsmetriken
- Flow-Fortschritt
- technische Fehler
- Lineage
- Laufzeitinformationen
- Benutzeraktionen
- Streaming- und Betriebsmetriken

Databricks speichert das Event Log standardmäßig als versteckte Delta-Tabelle im für die Pipeline konfigurierten Katalog und Schema.

Es kann:

- über die Pipeline-Oberfläche betrachtet
- über APIs gelesen
- direkt per SQL abgefragt
- in Unity Catalog veröffentlicht
- über eine kontrollierte View bereitgestellt werden

Eine Abfrage über die Pipeline-ID sieht konzeptionell so aus:

```sql
SELECT *
FROM event_log(<pipeline_id>);
```

Bei veröffentlichtem Event Log kann eine View erstellt werden:

```sql
CREATE OR REPLACE VIEW governance.event_log_raw AS
SELECT *
FROM operations.pipeline_event_log;
```

Eine View ist häufig sinnvoller als direkter Zugriff auf die Systemtabelle:

- Berechtigungen können begrenzt werden
- interne Felder können ausgeblendet werden
- JSON-Strukturen können normalisiert werden
- mehrere Event Logs können vereinheitlicht werden
- BI-Tools erhalten ein stabiles Schema

> **Das Event Log sollte nicht gelöscht werden. Es ist Bestandteil des Pipeline-Betriebs und nicht nur eine beliebige Reporting-Tabelle.**

## Welche Expectation-Metriken im Event Log stehen

Datenqualitätsinformationen stehen in Ereignissen vom Typ `flow_progress`.

Für einzelne Expectations sind insbesondere verfügbar:

- `passed_records`
- `failed_records`
- Expectation-Name
- Dataset

Auf Flow- beziehungsweise Dataset-Ebene ist zusätzlich verfügbar:

- `dropped_records`

Die Daten liegen in verschachtelten JSON-Strukturen wie:

```text
details.flow_progress.data_quality.expectations
details.flow_progress.data_quality.dropped_records
```

Eine wichtige Modellierungsregel lautet:

> **`dropped_records` ist nicht automatisch eine Kennzahl je einzelner Expectation.**

Ein Datensatz kann mehrere Regeln verletzen, wird aber nur einmal verworfen. Werden Dropped Rows jeder einzelnen Regel zugeschlagen, entsteht Doppelzählung.

Deshalb sollte das gemeinsame Modell unterscheiden:

```text
Rule-level metrics:
passed_records
failed_records

Flow-level metrics:
dropped_records
output_records
flow_status
```

## Besonderheit bei Fail

Für `Warn` und `Drop` stehen Expectation-Tracking-Metriken zur Verfügung.

Bei `Fail` stoppt die Ausführung beim ersten erkannten ungültigen Datensatz. Databricks weist darauf hin, dass hierfür keine vollständigen Expectation-Metriken erfasst werden.

Das hat Konsequenzen für die Historisierung:

- Ein Fail darf nicht als „0 fehlerhafte Zeilen“ interpretiert werden.
- Der technische Fehlschlag muss aus Update-, Flow- oder Error-Ereignissen abgeleitet werden.
- Die betroffene Regel kann über Fehlermeldung, Dataset und Expectation-Kontext zugeordnet werden.
- `Rows Tested`, `Rows Failed` und `Failure Rate` können bei Fail unbekannt bleiben.
- Für Monitoring ist ein eigener Status wie `Failed Early` oder `Execution Failed` sinnvoll.

Ein robustes Modell trennt daher:

| Feld | Bedeutung |
| --- | --- |
| **Quality Status** | Passed, Warning, Violated, Dropped |
| **Execution Status** | Completed, Failed, Cancelled, Skipped |
| **Action** | Warn, Drop, Fail |
| **Metric Completeness** | Complete, Partial, Not Available |

Das verhindert, dass ein früher technischer Abbruch wie ein vollständig gemessener Qualitätslauf dargestellt wird.

## Event Log in eine DQ-Historie überführen

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test4-img2-de.png"
        alt="Überführung von Databricks Pipeline Expectations über das Event-Protokoll und Delta-Daten in ein gemeinsames DQ-Historiemodell sowie die Nutzung in BI, Monitoring, Alerts und Root-Cause-Analysen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Event Log ist eine technische Ereignisquelle. Eine kuratierte DQ-Historie transformiert daraus stabile, fachlich angereicherte Ergebniszeilen für Qlik, Power BI und Governance-Prozesse.
    </figcaption>
</figure>

Das Event Log ist bereits historisch. Trotzdem sollte BI nicht direkt auf dessen Rohschema aufgebaut werden.

Gründe:

- verschachtelte JSON-Strukturen
- technische Event-Typen
- mehrere Events je Pipeline-Update
- kumulative oder wiederholte Metriken
- unterschiedliche Granularitäten
- fehlende fachliche Owner- und Severity-Informationen
- versionsabhängige Details
- Fail-Fälle ohne vollständige Expectation-Metriken

Die empfohlene Architektur lautet:

```flowchart
Pipeline Event Log
Raw Event View
Expectation Metrics Model
DQ History Delta Table
Governed DQ View
Qlik / Power BI
```

## Expectation-Metriken extrahieren

Ein vereinfachtes SQL-Muster:

```sql
WITH expectation_events AS (
    SELECT
        timestamp AS event_time,
        origin.pipeline_id AS pipeline_id,
        origin.pipeline_name AS pipeline_name,
        origin.update_id AS update_id,
        origin.flow_name AS flow_name,
        details:flow_progress.status::STRING AS flow_status,
        TRY_CAST(
            details:flow_progress.data_quality.dropped_records
            AS BIGINT
        ) AS dropped_records,
        EXPLODE(
            FROM_JSON(
                details:flow_progress.data_quality.expectations,
                'ARRAY<STRUCT<
                    name: STRING,
                    dataset: STRING,
                    passed_records: BIGINT,
                    failed_records: BIGINT
                >>'
            )
        ) AS expectation
    FROM governance.event_log_raw
    WHERE event_type = 'flow_progress'
      AND details:flow_progress.data_quality.expectations IS NOT NULL
)
SELECT
    pipeline_id,
    pipeline_name,
    update_id,
    flow_name,
    expectation.dataset AS dataset,
    expectation.name AS expectation_name,
    MAX(event_time) AS executed_at,
    MAX_BY(flow_status, event_time) AS final_flow_status,
    SUM(expectation.passed_records) AS rows_passed,
    SUM(expectation.failed_records) AS rows_failed,
    SUM(
        expectation.passed_records
        + expectation.failed_records
    ) AS rows_tested
FROM expectation_events
GROUP BY
    pipeline_id,
    pipeline_name,
    update_id,
    flow_name,
    expectation.dataset,
    expectation.name;
```

Das Muster orientiert sich an den dokumentierten Event-Log-Feldern. In der konkreten Umgebung muss geprüft werden:

- ob Events inkrementelle oder kumulative Metriken liefern
- welche Pipeline-Version verwendet wird
- ob Retries mehrfach gezählt werden
- welcher finale Flow-Status maßgeblich ist
- ob das Event Log veröffentlicht oder versteckt ist
- wie standalone Streaming Tables und Materialized Views angebunden sind

Für produktive Historisierung sollte die Extraktion mit realen Pipeline-Läufen validiert werden.

## Gemeinsames DQ-Datenmodell

Die Databricks-Ergebnisse werden auf dieselbe Struktur wie die anderen Plattformen der Serie abgebildet.

| Gemeinsames Feld | Databricks-Mapping |
| --- | --- |
| **Run ID** | Pipeline `update_id` |
| **Rule ID** | Registry-ID oder Kombination aus Pipeline, Dataset und Expectation |
| **Platform** | Databricks |
| **Data Product** | aus Rule Registry oder Unity-Catalog-Metadaten |
| **Table** | Dataset oder Flow |
| **Column** | aus Rule Registry, da nicht immer im Event Log vorhanden |
| **Test Type** | Expectation |
| **Status** | aus Metrik, Action und Execution Status abgeleitet |
| **Rows Tested** | Passed + Failed |
| **Rows Failed** | `failed_records` |
| **Failure Rate** | Failed / Tested |
| **Executed At** | Event- oder Update-Zeitpunkt |
| **Owner** | aus Rule Registry |
| **Severity** | aus Rule Registry |

Databricks-spezifische Erweiterungen:

| Feld | Zweck |
| --- | --- |
| **Pipeline ID** | technische Herkunft |
| **Pipeline Name** | lesbare Herkunft |
| **Flow Name** | betroffener Flow |
| **Dataset** | Erwartungs-Dataset |
| **Expectation Name** | technischer Regelname |
| **Action** | Warn, Drop oder Fail |
| **Rows Passed** | gültige Datensätze |
| **Rows Dropped** | verworfene Datensätze auf Flow-Ebene |
| **Flow Status** | technischer Endstatus |
| **Metric Completeness** | vollständig, teilweise oder nicht verfügbar |
| **Event ID** | technische Nachvollziehbarkeit |
| **Event Details** | kontrollierter Zusatzkontext |

## Delta-Tabelle für die Historie

Ein mögliches Zielschema:

```sql
CREATE TABLE IF NOT EXISTS governance.dq_test_history (
    run_id               STRING,
    rule_id              STRING,
    platform              STRING,
    data_product          STRING,
    table_name            STRING,
    column_name           STRING,
    test_type             STRING,
    test_status           STRING,
    rows_tested           BIGINT,
    rows_failed           BIGINT,
    failure_rate          DECIMAL(18,8),
    executed_at           TIMESTAMP,
    owner_name            STRING,
    severity              STRING,

    pipeline_id           STRING,
    pipeline_name         STRING,
    flow_name             STRING,
    dataset_name          STRING,
    expectation_name      STRING,
    expectation_action    STRING,
    rows_passed           BIGINT,
    rows_dropped          BIGINT,
    execution_status      STRING,
    metric_completeness   STRING,
    source_event_id       STRING,
    message               STRING
)
USING DELTA;
```

Die Tabelle ist append-orientiert.

Ein Pipeline-Update darf nicht einfach frühere Ergebnisse überschreiben. Der eindeutige technische Schlüssel kann beispielsweise sein:

```text
Pipeline ID
+ Update ID
+ Flow Name
+ Expectation Name
```

Bei Wiederholungen und Retries sollte zusätzlich geprüft werden, ob die Event-ID oder Sequenz benötigt wird.

## Status ableiten

Ein mögliches Regelwerk:

```text
Action = Warn
Failed Records = 0
→ Passed

Action = Warn
Failed Records > 0
→ Warning

Action = Drop
Failed Records = 0
→ Passed

Action = Drop
Failed Records > 0
→ Failed / Filtered

Action = Fail
Execution Status = Failed
→ Failed Early

Technical pipeline error without rule violation
→ Error
```

Dabei sollte zwischen Datenqualität und Technik unterschieden werden.

Beispiel:

| Quality Status | Execution Status | Interpretation |
| --- | --- | --- |
| Passed | Completed | Regel erfüllt |
| Warning | Completed | ungültige Daten wurden behalten |
| Filtered | Completed | ungültige Daten wurden entfernt |
| Failed Early | Failed | harte Expectation hat Flow gestoppt |
| Unknown | Failed | technischer Fehler ohne bestätigte Regelverletzung |

## Warn, Drop und Fail sind keine Severity

Die Action beschreibt, was die Pipeline technisch tut.

Severity beschreibt, wie kritisch die Regel fachlich ist.

Beides kann zusammenhängen, ist aber nicht identisch.

| Severity | Mögliche Action |
| --- | --- |
| Low | Warn |
| Medium | Warn oder Drop |
| High | Drop oder Fail |
| Critical | häufig Fail |

Eine kritische Regel kann bewusst zunächst im Warn-Modus laufen, um Datenlage und Fehlerrate zu verstehen. Umgekehrt kann eine technisch zu verwerfende Zeile fachlich nur geringe Kritikalität besitzen.

Deshalb gehören beide Felder getrennt in die Rule Registry und in die historische Ergebniszeile.

## Quarantäne statt stilles Verwerfen

Nicht jeder ungültige Datensatz sollte vollständig verschwinden.

Ein Quarantäne-Muster trennt gültige und ungültige Datensätze:

```flowchart
Source Data
Validation View
Valid Records
Quarantine Records
Curated Target
Remediation
```

Databricks dokumentiert hierfür Muster mit temporären Views und separaten Verarbeitungswegen.

Vorteile:

- Root-Cause-Analyse bleibt möglich
- Business Keys bleiben nachvollziehbar
- korrigierte Datensätze können erneut verarbeitet werden
- Drop-Zahlen erhalten operative Evidenz
- Data Stewards können gezielt arbeiten

Dabei gelten dieselben Governance-Anforderungen wie bei anderen Fehlerdetailtabellen:

- PII minimieren
- sensible Werte maskieren
- Zugriff einschränken
- Retention definieren
- große Fehlermengen begrenzen
- Korrektur- und Reprocessing-Prozess dokumentieren

## BI nicht direkt auf das Roh-Event-Log setzen

Das Event Log kann technisch direkt abgefragt werden. Für ein dauerhaftes Qlik- oder Power-BI-Modell ist eine kuratierte Schicht dennoch sinnvoll.

```flowchart
Databricks Event Log
DQ History Delta Table
DQ Monitoring View
Qlik / Power BI
```

Die Monitoring View kann:

- technische Statuswerte normalisieren
- Rule IDs ergänzen
- Owner und Severity hinzufügen
- Fail-Fälle kennzeichnen
- Warn-, Drop- und Fail-Logik vereinheitlichen
- Test- und Flow-Grain trennen
- Entwicklungs-Pipelines herausfiltern
- Data Products zuordnen
- aktuelle und historische Perspektiven anbieten

## Auswertung in Qlik

Zeilengewichtete Fehlerrate:

```qlik
Num(
    Sum(rows_failed)
    /
    Sum(rows_tested),
    '0.00%'
)
```

Warnungen:

```qlik
Count({
    <test_status = {'Warning'}>
} DISTINCT rule_id & '|' & run_id)
```

Verworfene Zeilen:

```qlik
Sum(rows_dropped)
```

Fehlgeschlagene harte Expectations:

```qlik
Count({
    <test_status = {'Failed Early'}>
} DISTINCT rule_id & '|' & run_id)
```

## Auswertung in Power BI

```DAX
Failure Rate =
DIVIDE(
    SUM(DQ_History[Rows Failed]),
    SUM(DQ_History[Rows Tested])
)
```

```DAX
Warning Rules =
CALCULATE(
    DISTINCTCOUNT(DQ_History[Rule Run Key]),
    DQ_History[Test Status] = "Warning"
)
```

```DAX
Dropped Rows =
SUM(DQ_History[Rows Dropped])
```

```DAX
Failed Expectations =
CALCULATE(
    DISTINCTCOUNT(DQ_History[Rule Run Key]),
    DQ_History[Test Status] = "Failed Early"
)
```

Wichtig: `Rows Dropped` darf nicht über Rule-Grain summiert werden, wenn derselbe Flow-Wert auf jede Expectation dupliziert wurde. Dafür ist eine getrennte Flow-Faktentabelle oder eine eindeutige Flow-Run-Kennzahl besser.

## Zwei Facts statt einer überladenen Tabelle

Für größere Implementierungen ist ein Modell mit zwei Fakten belastbarer.

### `DQ_EXPECTATION_RESULT`

Grain:

```text
Eine Zeile je Pipeline Update, Flow und Expectation
```

Enthält:

- passed records
- failed records
- Rule ID
- Action
- Owner
- Severity

### `DQ_FLOW_RESULT`

Grain:

```text
Eine Zeile je Pipeline Update und Flow
```

Enthält:

- dropped records
- output records
- execution status
- duration
- pipeline and flow metadata

Dadurch werden unterschiedliche Granularitäten nicht vermischt.

Ein gemeinsamer BI-Layer kann beide Facts über folgende Dimensionen verbinden:

- Run
- Pipeline
- Flow
- Dataset
- Data Product
- Time
- Owner
- Rule

## Alerts und operative Maßnahmen

Databricks stellt mehrere Wege für Benachrichtigungen und Aktionen bereit:

- Pipeline-Benachrichtigungen
- SQL Alerts auf kuratierten DQ-Views
- Lakeflow Jobs
- Event Hooks
- Webhooks oder nachgelagerte Integrationen
- Qlik- oder Power-BI-basierte Monitoring-Prozesse
- Ticket- und Workflow-Systeme

Ein sinnvoller operativer Ablauf:

```flow linear vertical
Expectation-Verstoß erkennen
Action und Severity bewerten
Owner zuordnen
Fehlerdetails oder Quarantäne prüfen
Ursache beheben
Pipeline oder Flow erneut ausführen
Historisches Ergebnis aktualisieren
Trend weiter überwachen
```

Die Alert-Logik sollte nicht nur auf `Failed` reagieren.

Beispiele:

- Warn-Fehlerrate steigt drei Läufe in Folge
- Drop-Rate überschreitet 0,5 Prozent
- kritische Expectation führt zu Fail
- Event Log liefert keine Metriken
- Dataset produziert plötzlich keine Zeilen
- dieselbe Rule ID schlägt in mehreren Data Products fehl
- Owner reagiert nicht innerhalb des SLA

## Praktische Implementierungsreihenfolge

Ein belastbarer Prototyp kann in zehn Schritten entstehen:

1. Eine Lakeflow Pipeline mit einer Streaming Table oder Materialized View auswählen.
2. Drei Expectations definieren: Pflichtfeld, Wertebereich und Statuswert.
3. Eine Regel als Warn konfigurieren.
4. Eine Regel als Drop konfigurieren.
5. Eine kritische Regel als Fail konfigurieren.
6. Event Log veröffentlichen oder eine kontrollierte View erstellen.
7. Expectation-Metriken aus `flow_progress` extrahieren.
8. Rule Registry mit Owner, Severity und Action anlegen.
9. Ergebnisse append-orientiert in eine Delta-Historientabelle schreiben.
10. Qlik oder Power BI auf eine kuratierte Monitoring View setzen.

## Typische Fehlentscheidungen

### Event Log direkt als fertiges BI-Modell behandeln

Das Event Log ist eine technische Ereignisquelle mit mehreren Grains und verschachtelten Strukturen.

### Fail als vollständig gemessenen Test interpretieren

Bei Fail stehen nicht dieselben vollständigen Expectation-Metriken wie bei Warn und Drop zur Verfügung.

### Dropped Records jeder Expectation zuordnen

Dropped Records liegen auf Flow-Ebene. Bei mehreren Regeln entsteht sonst Doppelzählung.

### Warn als bestandenen Test darstellen

Ein technisch erfolgreicher Flow kann dennoch ungültige Daten enthalten.

### Drop als fehlerfreie Datenqualität interpretieren

Die Zieltabelle ist sauberer, aber Fehler sind weiterhin aufgetreten und Datensätze wurden entfernt.

### Uniqueness als einfache zeilenbasierte Expectation formulieren

Eindeutigkeit über mehrere Zeilen benötigt Aggregation, Window Logic oder einen separaten Testpfad.

### Expectation-Namen regelmäßig ändern

Dadurch brechen Zeitreihen und Mapping auf zentrale Rule IDs.

### Owner und Severity nur im Dashboard pflegen

Governance-Metadaten sollten versioniert und zum Ausführungszeitpunkt in die Historie übernommen werden.

## Die zentrale Erkenntnis

> **Databricks Expectations verbinden Qualitätsregeln direkt mit Streaming Tables und Materialized Views. Das Event Log macht viele Ergebnisse technisch abfragbar. Erst eine kuratierte DQ-Historie macht sie jedoch plattformübergreifend vergleichbar und operativ steuerbar.**

Warn, Drop und Fail lösen unterschiedliche Probleme:

- Warn schafft Transparenz
- Drop schützt nachgelagerte Datasets
- Fail schützt kritische Prozesse

Das Event Log liefert:

- Expectation-Metriken
- Flow- und Update-Zustände
- technische Fehler
- Lineage und Laufzeitkontext

Das gemeinsame DQ-Datenmodell ergänzt:

- stabile Rule IDs
- Data Products
- Owner
- Severity
- historisierte Statuswerte
- Qlik- und Power-BI-freundliche Kennzahlen
- Remediation und Re-Testing

## Passende Playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [Part 2 — Data Quality in Microsoft Fabric](/playbooks/dq-test2)
- [Part 3 — Data Quality with dbt](/playbooks/dq-test3)
- [The Missing Pieces — Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality & Governance](/playbooks/data-quality-governance)

## Quellen und weiterführende Dokumentation

- [Databricks — Manage Data Quality with Pipeline Expectations](https://docs.databricks.com/aws/en/ldp/expectations)
- [Databricks — Expectation Recommendations and Advanced Patterns](https://docs.databricks.com/aws/en/ldp/expectation-patterns)
- [Databricks — Expectations Python API](https://docs.databricks.com/aws/en/ldp/developer/ldp-python-ref-expectations)
- [Databricks — Develop Lakeflow Pipeline Code with SQL](https://docs.databricks.com/aws/en/ldp/developer/sql-dev)
- [Databricks — Develop Lakeflow Pipeline Code with Python](https://docs.databricks.com/aws/en/ldp/developer/python-dev)
- [Databricks — Pipeline Event Log](https://docs.databricks.com/aws/en/ldp/monitor-event-logs)
- [Databricks — Pipeline Event Log Schema](https://docs.databricks.com/aws/en/ldp/monitor-event-log-schema)
- [Databricks — Monitor Pipelines](https://docs.databricks.com/aws/en/ldp/observability)
- [Databricks — Lakeflow Spark Declarative Pipelines](https://docs.databricks.com/aws/en/ldp/)
- [Databricks — What Happened to Delta Live Tables?](https://docs.databricks.com/aws/en/ldp/concepts/where-is-dlt)
- [Databricks — event_log Table-Valued Function](https://docs.databricks.com/aws/en/sql/language-manual/functions/event_log)

> **Stand der Funktionsbeschreibung:** Juli 2026. Databricks entwickelt Lakeflow, Spark Declarative Pipelines, Event-Log-Schemata und Monitoring-Funktionen laufend weiter. Syntax, Verfügbarkeit und Preview-Status sollten vor der Umsetzung gegen die aktuelle Dokumentation geprüft werden.
