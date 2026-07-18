---
title: Data Quality in Microsoft Fabric — Tests operationalisieren und Ergebnisse zentral auswerten
description: Wie Datenqualitätstests in Fabric Lakehouse und Warehouse mit SQL, Notebooks, Pipelines, Materialized Lake Views und Microsoft Purview ausgeführt und als standardisierte Delta- oder Warehouse-Ergebnisse für Qlik und Power BI gespeichert werden.
category: Datenqualität
tags:
  - data-quality
  - operational-data-quality
  - microsoft-fabric
  - fabric-lakehouse
  - fabric-warehouse
  - onelake
  - delta-lake
  - materialized-lake-views
  - microsoft-purview
  - notebooks
  - pipelines
  - qlik-sense
  - power-bi
  - data-governance
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 2
seriesTitle: Operational Data Quality
hero: images/playbooks/dq-test2-hero.png
---

## Fabric stellt mehrere Testmechanismen bereit – das Ergebnis braucht trotzdem einen gemeinsamen Vertrag

Microsoft Fabric bietet nicht nur einen einzigen Mechanismus für Datenqualität.

Je nach Workload können Qualitätsregeln ausgeführt werden durch:

- T-SQL im Fabric Warehouse
- SQL gegen Lakehouse-Tabellen
- PySpark oder Spark SQL in Fabric Notebooks
- Data Factory Pipelines
- Stored-Procedure- oder Script-Aktivitäten
- Materialized Lake Views mit deklarativen Constraints
- Microsoft Purview Data Quality Scans für Fabric Lakehouses

Diese Mechanismen haben unterschiedliche Stärken. Sie liefern jedoch nicht automatisch dasselbe operative Ergebnisformat.

Ein Notebook kann eine Delta-Tabelle schreiben. Eine Stored Procedure kann eine Warehouse-Tabelle aktualisieren. Eine Materialized Lake View zählt Constraint-Verletzungen in Fabric-Systemmetriken. Purview berechnet Qualitätswerte und kann fehlerhafte Datensätze in einen verwalteten Speicher schreiben.

Für Qlik, Power BI, Ownership und Remediation sollte daraus trotzdem eine gemeinsame Ergebnisstruktur entstehen.

> **Fabric führt die Regeln dort aus, wo die Daten liegen. Eine standardisierte DQ-Ergebnistabelle macht die Ausführungen anschließend gemeinsam messbar.**

```flowchart
Fabric Lakehouse / Warehouse
Quality Rules
SQL / Notebook / Pipeline / MLV / Purview
Standardized DQ Result
Qlik / Power BI
Ownership and Remediation
```

## Lakehouse oder Warehouse als Ausführungs- und Ergebnisebene

Fabric Lakehouse und Fabric Warehouse speichern ihre Daten in OneLake und verwenden Delta als zentrale Speichergrundlage. Das Entwicklungsmodell unterscheidet sich jedoch.

| Entscheidung | Lakehouse | Warehouse |
| --- | --- | --- |
| Primärer Entwicklungsansatz | Spark, Python, SQL, Notebooks | T-SQL, Views, Stored Procedures |
| Typische Daten | strukturiert und semi-strukturiert | überwiegend strukturiert |
| Geeignet für DQ-Ausführung | große Datenmengen, komplexe Regeln, Data Engineering | SQL-basierte Regeln, relationale Modelle, BI-nahe Tests |
| Nativer Ergebnisspeicher | Delta-Tabelle | Warehouse-Tabelle |
| Typischer Orchestrator | Notebook-Aktivität, Pipeline | Stored-Procedure- oder Script-Aktivität |

Die zentrale DQ-Tabelle sollte nicht ohne Not doppelt geführt werden.

Eine praktikable Entscheidung lautet:

- **Delta-Tabelle im Lakehouse**, wenn die meisten Regeln in Spark oder Notebooks ausgeführt werden und weitere Plattformen direkt auf Delta zugreifen sollen.
- **Warehouse-Tabelle**, wenn SQL-first gearbeitet wird, die Testergebnisse relational modelliert werden und BI-Teams primär über den SQL-Endpunkt konsumieren.
- **Konsolidierungsschicht**, wenn mehrere Workspaces, Lakehouses oder Warehouses lokale Ergebnisse erzeugen.

```flow linear vertical
Lokale Testausführung
Lokale oder direkte Ergebniszeile
Zentrale Fabric-DQ-Tabelle
Qlik- und Power-BI-Modell
```

## Der Fabric Data Quality Execution Flow

Die Testausführung sollte nicht als nachträglicher Berichtsschritt verstanden werden. Sie gehört in den Datenprozess.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test2-img1-de.png"
        alt="Microsoft Fabric Data-Quality-Ausführungsfluss von Datenquellen über Lakehouse oder Warehouse, Qualitätsregeln und SQL-, Notebook- oder Pipeline-Tests bis zur zentralen DQ-Ergebnistabelle und zum Monitoring in Qlik und Power BI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Regel kann mit unterschiedlichen Fabric-Workloads ausgeführt werden. Jede erfolgreiche oder technisch fehlgeschlagene Ausführung muss anschließend eine standardisierte Ergebniszeile erzeugen.
    </figcaption>
</figure>

Ein belastbarer Ablauf besteht aus sechs Schritten:

1. Daten werden in einem Lakehouse oder Warehouse bereitgestellt.
2. Eine Regel beschreibt den erwarteten Qualitätszustand.
3. SQL, Notebook, Pipeline, Materialized Lake View oder Purview führt die Prüfung aus.
4. Die Ausführung berechnet messbare Kennzahlen.
5. Eine Ergebniszeile wird in die zentrale Delta- oder Warehouse-Tabelle geschrieben.
6. Qlik und Power BI verwenden diese Tabelle für Monitoring und Maßnahmen.

Die Pipeline ist dabei nicht zwingend die Test-Engine. Sie kann die Ausführung orchestrieren, Parameter übergeben und sicherstellen, dass ein gemeinsamer `Run ID` verwendet wird.

## Die vier Beispielregeln

Für einen ersten Fabric-Prototyp reichen vier typische Regeln.

| Rule ID | Regel | Grain | Messung |
| --- | --- | --- | --- |
| **DQ-001** | Pflichtfeld | Spalte | Anzahl Datensätze mit `NULL` oder leerem Wert |
| **DQ-002** | Wertebereich | Spalte | Anzahl Werte außerhalb des erlaubten Bereichs |
| **DQ-003** | Duplikate | Schlüssel oder Spaltenkombination | Anzahl zusätzlicher Datensätze je mehrfach vorkommendem Schlüssel |
| **DQ-004** | Aktualität | Tabelle oder Data Product | Zeit seit letzter erfolgreicher Aktualisierung |

Wichtig ist eine eindeutige Definition der Kennzahl.

Bei Duplikaten kann `Rows Failed` beispielsweise bedeuten:

- alle Datensätze, deren Schlüssel mehrfach vorkommt, oder
- nur die zusätzlichen Datensätze über das erste Vorkommen hinaus.

Beide Varianten sind möglich. Die Definition muss jedoch für dieselbe `Rule ID` stabil bleiben.

Bei Aktualität ist `Rows Tested` häufig nicht die Anzahl der Fachdatenzeilen. Die Regel prüft den Zustand eines Datenobjekts. Dafür kann gelten:

```text
Rows Tested = 1
Rows Failed = 0 oder 1
```

## Standardisierte Ergebnisstruktur in Fabric

Die Kernstruktur aus Part 1 bleibt bestehen:

| Feld | Aufgabe |
| --- | --- |
| **Run ID** | Gemeinsame Ausführung von Pipeline, Notebook oder Batch |
| **Rule ID** | Stabile Identität der Regel |
| **Platform** | Hier beispielsweise `Microsoft Fabric` |
| **Data Product** | Fachlicher Kontext |
| **Table** | Geprüftes Datenobjekt |
| **Column** | Geprüfte Spalte, falls zutreffend |
| **Test Type** | Pflichtfeld, Wertebereich, Duplikat, Aktualität oder weitere Regelart |
| **Status** | Passed, Failed, Error oder Skipped |
| **Rows Tested** | Umfang der Prüfung |
| **Rows Failed** | Anzahl der Regelverletzungen |
| **Failure Rate** | `Rows Failed / Rows Tested` |
| **Executed At** | Ausführungszeitpunkt |
| **Owner** | Verantwortliche Rolle oder Team |
| **Severity** | Kritikalität |

Für Fabric sind zusätzliche technische Felder sinnvoll:

| Optionales Feld | Nutzen |
| --- | --- |
| **Execution Method** | SQL, Notebook, Pipeline, MLV oder Purview |
| **Source Run ID** | Originale Pipeline-, Notebook-, MLV- oder Scan-ID |
| **Rule Name** | Lesbarer Anzeigename |
| **Rule Version** | Nachvollziehbare Version der Logik |
| **Threshold Value** | Zur Laufzeit verwendeter Schwellenwert |
| **Duration Seconds** | Laufzeit des Tests |
| **Executed By** | Service Principal, Pipeline oder Benutzer |
| **Message** | Kurze technische oder fachliche Zusammenfassung |
| **Details URI** | Kontrollierter Verweis auf Fehlerdetails |

> **Die Kernfelder bilden den plattformneutralen Vertrag. Erweiterungsfelder liefern Fabric-spezifische Nachvollziehbarkeit.**

## Variante 1: SQL-Tests im Fabric Warehouse

Für SQL-first-Teams ist eine Stored Procedure oder Script-Aktivität häufig der direkteste Weg.

Eine Ergebnistabelle im Warehouse kann beispielsweise so angelegt werden:

```sql
CREATE TABLE governance.dq_test_result (
    run_id             VARCHAR(100)  NOT NULL,
    rule_id            VARCHAR(100)  NOT NULL,
    platform_name      VARCHAR(100)  NOT NULL,
    execution_method   VARCHAR(50)   NOT NULL,
    data_product       VARCHAR(200)  NOT NULL,
    table_name         VARCHAR(256)  NOT NULL,
    column_name        VARCHAR(256)  NULL,
    test_type          VARCHAR(100)  NOT NULL,
    test_status        VARCHAR(30)   NOT NULL,
    rows_tested        BIGINT        NULL,
    rows_failed        BIGINT        NULL,
    failure_rate       DECIMAL(18,8) NULL,
    executed_at        DATETIME2(6)  NOT NULL,
    owner_name         VARCHAR(200)  NULL,
    severity           VARCHAR(30)   NULL,
    duration_seconds   DECIMAL(18,3) NULL,
    executed_by        VARCHAR(200)  NULL,
    message            VARCHAR(1000) NULL
);
```

### Pflichtfeldtest als messbare SQL-Ausführung

```sql
WITH test_metrics AS (
    SELECT
        COUNT_BIG(*) AS rows_tested,
        SUM(
            CASE
                WHEN customer_id IS NULL THEN CAST(1 AS BIGINT)
                ELSE CAST(0 AS BIGINT)
            END
        ) AS rows_failed
    FROM silver.customer
)
INSERT INTO governance.dq_test_result (
    run_id,
    rule_id,
    platform_name,
    execution_method,
    data_product,
    table_name,
    column_name,
    test_type,
    test_status,
    rows_tested,
    rows_failed,
    failure_rate,
    executed_at,
    owner_name,
    severity,
    executed_by,
    message
)
SELECT
    'RUN_20260718_081530',
    'DQ-001',
    'Microsoft Fabric',
    'Warehouse SQL',
    'Customer 360',
    'CUSTOMER',
    'CUSTOMER_ID',
    'Not Null',
    CASE WHEN rows_failed = 0 THEN 'Passed' ELSE 'Failed' END,
    rows_tested,
    rows_failed,
    CAST(rows_failed AS DECIMAL(18,8))
        / NULLIF(CAST(rows_tested AS DECIMAL(18,8)), 0),
    SYSUTCDATETIME(),
    'Data Steward CRM',
    'High',
    'dq-fabric-pipeline',
    CONCAT(rows_failed, ' null customer IDs found')
FROM test_metrics;
```

Der Test führt drei Dinge in einem kontrollierten Ablauf aus:

1. Er bestimmt den Prüfumfang.
2. Er zählt die Fehler.
3. Er persistiert das Ergebnis.

### Wertebereich

```sql
SELECT
    COUNT_BIG(*) AS rows_tested,
    SUM(
        CASE
            WHEN amount < 0 OR amount > 1000000
                THEN CAST(1 AS BIGINT)
            ELSE CAST(0 AS BIGINT)
        END
    ) AS rows_failed
FROM silver.fact_sales;
```

### Duplikate

Das folgende Muster zählt zusätzliche Zeilen über das erste Vorkommen hinaus:

```sql
WITH key_counts AS (
    SELECT
        order_id,
        COUNT_BIG(*) AS key_count
    FROM silver.fact_sales
    GROUP BY order_id
)
SELECT
    (SELECT COUNT_BIG(*) FROM silver.fact_sales) AS rows_tested,
    COALESCE(
        SUM(
            CASE
                WHEN key_count > 1 THEN key_count - 1
                ELSE 0
            END
        ),
        0
    ) AS rows_failed
FROM key_counts;
```

### Aktualität

```sql
SELECT
    CAST(1 AS BIGINT) AS rows_tested,
    CASE
        WHEN MAX(load_timestamp) >= DATEADD(HOUR, -24, SYSUTCDATETIME())
            THEN CAST(0 AS BIGINT)
        ELSE CAST(1 AS BIGINT)
    END AS rows_failed
FROM governance.load_log
WHERE data_product = 'Sales';
```

Eine Pipeline kann die Stored Procedure mit Parametern aufrufen. Die Fabric Stored Procedure Activity unterstützt Fabric Data Warehouse und kann Parameter aus der Procedure übernehmen. Alternativ kann eine Script Activity T-SQL direkt ausführen.

## Variante 2: Notebook-Tests im Fabric Lakehouse

Notebooks eignen sich für:

- PySpark-Regeln
- große Delta-Tabellen
- komplexe fachliche Logik
- Profiling und statistische Tests
- Wiederverwendung gemeinsamer Python-Funktionen
- Regeln, die mehrere Datenquellen kombinieren

Ein einfacher Pflichtfeldtest kann so aufgebaut werden:

```python
from datetime import datetime, timezone
from pyspark.sql import Row
from pyspark.sql import functions as F

run_id = "RUN_20260718_081530"
rule_id = "DQ-001"

source_df = spark.table("silver.customer")

rows_tested = source_df.count()
rows_failed = (
    source_df
    .filter(F.col("customer_id").isNull())
    .count()
)

failure_rate = (
    rows_failed / rows_tested
    if rows_tested > 0
    else None
)

status = (
    "Error"
    if rows_tested == 0
    else "Passed" if rows_failed == 0
    else "Failed"
)

result = Row(
    run_id=run_id,
    rule_id=rule_id,
    platform_name="Microsoft Fabric",
    execution_method="Notebook / PySpark",
    data_product="Customer 360",
    table_name="CUSTOMER",
    column_name="CUSTOMER_ID",
    test_type="Not Null",
    test_status=status,
    rows_tested=rows_tested,
    rows_failed=rows_failed,
    failure_rate=failure_rate,
    executed_at=datetime.now(timezone.utc),
    owner_name="Data Steward CRM",
    severity="High",
    executed_by="dq-fabric-pipeline",
    message=f"{rows_failed} null customer IDs found"
)

(
    spark.createDataFrame([result])
    .write
    .format("delta")
    .mode("append")
    .saveAsTable("governance.dq_test_result")
)
```

Das Notebook sollte nicht für jede Regel vollständig kopiert werden. Besser ist eine wiederverwendbare Funktion oder ein Framework:

```python
execute_rule(
    rule_id="DQ-001",
    data_product="Customer 360",
    table_name="silver.customer",
    column_name="customer_id",
    test_type="not_null",
    severity="High",
    owner="Data Steward CRM",
    run_id=run_id
)
```

Die Funktion übernimmt dann:

- Laden der Regelparameter
- Berechnung der Kennzahlen
- Threshold-Bewertung
- Fehlerbehandlung
- Schreiben der Ergebniszeile
- optionales Schreiben von Fehlerdetails

## Variante 3: Pipeline als Orchestrierungsrahmen

Fabric Data Factory Pipelines koordinieren und automatisieren den Prozess.

Eine Pipeline kann beispielsweise:

```flowchart
Generate Run ID
Execute SQL Tests
Execute Notebook Tests
Collect Technical Errors
Write Result Rows
Refresh Semantic Model
Send Alert
```

Typische Aktivitäten sind:

- Notebook Activity
- Stored Procedure Activity
- Script Activity
- Dataflow Gen2 Activity
- Lookup- und ForEach-Aktivitäten für metadatengesteuerte Regeln
- nachgelagerte Benachrichtigung oder Workflow-Aktivität

Die Pipeline sollte einen gemeinsamen `Run ID` an alle Testaktivitäten übergeben. Dafür kann die eindeutige Pipeline-Ausführungs-ID verwendet oder eine eigene fachliche Batch-ID erzeugt werden.

Ein metadatengesteuerter Aufbau verwendet beispielsweise eine Regelregistry:

| Rule ID | Method | Object | Column | Test Type | Threshold | Active |
| --- | --- | --- | --- | --- | ---: | --- |
| DQ-001 | Warehouse SQL | CUSTOMER | CUSTOMER_ID | Not Null | 0 | Yes |
| DQ-002 | Notebook | FACT_SALES | AMOUNT | Range | 0.001 | Yes |
| DQ-003 | Warehouse SQL | FACT_SALES | ORDER_ID | Uniqueness | 0 | Yes |
| DQ-004 | Notebook | LOAD_LOG | LOAD_TS | Freshness | 24h | Yes |

Die Pipeline liest aktive Regeln, verzweigt nach `Method` und übergibt dieselben Governance-Parameter an die technische Ausführung.

> **Die Pipeline orchestriert den Lauf. Die Regel-Engine berechnet die Qualität. Die Ergebnistabelle dokumentiert beides.**

## Materialized Lake Views: deklarative Regeln direkt in der Transformation

Materialized Lake Views erweitern Fabric um einen deklarativen Ansatz.

Die Transformation wird als SQL definiert. Fabric materialisiert das Ergebnis als Delta-Tabelle, verwaltet Abhängigkeiten und übernimmt die Aktualisierung.

Datenqualitätsregeln können direkt als Constraints in der Definition hinterlegt werden:

```sql
CREATE OR REPLACE MATERIALIZED LAKE VIEW silver.sales_valid (
    CONSTRAINT order_id_required
        CHECK (order_id IS NOT NULL)
        ON MISMATCH FAIL,

    CONSTRAINT amount_non_negative
        CHECK (amount >= 0)
        ON MISMATCH DROP
)
AS
SELECT
    order_id,
    customer_id,
    product_id,
    amount,
    order_timestamp
FROM bronze.sales;
```

Fabric unterstützt dabei zwei zentrale Reaktionen:

- **FAIL** stoppt den Refresh beim ersten Constraint-Verstoß und ist das Standardverhalten.
- **DROP** setzt die Verarbeitung fort und entfernt verletzende Datensätze aus dem Ergebnis.

Werden `DROP` und `FAIL` in derselben Materialized Lake View verwendet, hat `FAIL` bei einer entsprechenden Verletzung Vorrang.

Dieses Muster eignet sich besonders, wenn Qualität Bestandteil der Medallion-Transformation sein soll:

```flow linear vertical
Bronze Delta Table
Materialized Lake View with Constraints
Validated Silver Delta Table
Gold Data Product
```

### Native MLV-Metriken als Quelle für die zentrale DQ-Tabelle

Fabric stellt für Materialized Lake Views Ausführungs-, Qualitäts- und Fehlermetriken bereit.

Im Lakehouse werden dafür Systemtabellen unter dem Schema `_mlv_system` geführt:

- `sys_run_metrics`
- `sys_node_metrics`
- `sys_error_metrics`

Zusätzlich kann Fabric einen integrierten Data Quality Report erzeugen. Dieser zeigt unter anderem Trends, Constraint-Verletzungen und verworfene Datensätze. Fabric erzeugt dafür im Hintergrund ein semantisches Modell und einen Power-BI-Bericht.

Diese nativen Funktionen sind wertvoll. Für ein übergreifendes Operational-DQ-Modell sollten die relevanten MLV-Metriken dennoch in den gemeinsamen Ergebnisvertrag normalisiert werden:

```flowchart
MLV Constraint Metrics
Normalization Job
DQ_TEST_RESULT
Qlik / Power BI
```

Dabei kann beispielsweise gelten:

| Standardfeld | MLV-Kontext |
| --- | --- |
| Run ID | MLV Refresh Run |
| Rule ID | stabiler Constraint-Name oder Mapping-ID |
| Platform | Microsoft Fabric |
| Execution Method | Materialized Lake View |
| Table | Materialized Lake View |
| Test Type | Constraint |
| Rows Tested | während Refresh geprüfte Zeilen |
| Rows Failed | Constraint-Verletzungen oder Drops gemäß definierter Semantik |
| Executed At | Refresh-Zeitpunkt |
| Message | Refresh- oder Constraint-Fehler |

Wichtig ist, Verletzungen und Drops nicht gleichzusetzen. Ein Datensatz kann mehrere Constraints verletzen, wird aber nur einmal verworfen. Das Ergebnisdesign muss deshalb klar dokumentieren, welche Metrik in `Rows Failed` verwendet wird.

## Purview Data Quality für Fabric Lakehouse

Microsoft Purview Unified Catalog kann Fabric-Lakehouse-Daten profilieren, Qualitätsregeln anwenden und DQ-Scans ausführen.

Damit entsteht eine zusätzliche governance-orientierte Ebene:

```flowchart
Fabric Lakehouse Table
Purview Profiling
Purview DQ Rules
DQ Scan and Score
Alerts / Error Records
```

Purview ist besonders geeignet für:

- zentral verwaltete Regeln in Governance Domains
- Qualitätsbewertung von Data Assets und Data Products
- Rule-, Asset- und Product-Level Thresholds
- Data Steward Workflows
- Qualitäts-Scores
- geplante Scans
- Veröffentlichung fehlerhafter Datensätze in einen kontrollierten Error Sink

Für Fabric Lakehouse gelten laut Microsoft-Dokumentation unter anderem folgende Voraussetzungen:

- Die Tabellen müssen im Delta- oder Iceberg-Format vorliegen.
- Der Data Map Scan muss erfolgreich gelaufen sein.
- Die Purview Managed Identity benötigt Contributor-Zugriff auf den Fabric Workspace.
- Für die Data-Quality-Verbindung wird eine Fabric-Verbindung mit Purview MSI verwendet.
- Das Datenasset wird typischerweise einem Data Product in einer Governance Domain zugeordnet.

Purview kann fehlerhafte Zeilen aus Data-Quality-Scans in verwalteten Speicher veröffentlichen. Für Cloudquellen kann dafür auch ein Fabric Lakehouse verwendet werden.

### Purview ergänzt den operativen Testprozess

Purview und technische Pipeline-Tests sollten nicht unkontrolliert dieselbe Regel doppelt ausführen.

Eine sinnvolle Aufteilung ist:

| Ebene | Verantwortung |
| --- | --- |
| **Pipeline-, SQL- und Notebook-Tests** | unmittelbare technische Freigabe eines Loads oder Data Products |
| **Materialized Lake View Constraints** | Qualität direkt während deklarativer Transformation und Refresh |
| **Purview DQ Scans** | governance-orientierte Bewertung, Profiling, Scorecards und Stewardship |
| **Zentrale DQ-Tabelle** | gemeinsame Historie über alle Ausführungsarten |

Die Regelregistry sollte deshalb zusätzlich dokumentieren:

- führende Ausführungsquelle
- technische Implementierung
- Purview-Regel oder Scan-Mapping
- MLV-Constraint
- gewünschte Ausführungsfrequenz
- Deduplizierungslogik

## Die Fabric DQ Results Table

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test2-img2-de.png"
        alt="Standardisierte Fabric Data Quality Ergebnistabelle mit Run ID, Regel, Data Product, geprüften und fehlerhaften Zeilen, Fehlerrate, Laufzeit, Owner, Schweregrad und Nachricht sowie den Fabric-Ausführungsquellen und der Nutzung in Qlik und Power BI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        SQL, Notebooks, Pipelines, Materialized Lake Views und Purview dürfen unterschiedliche Rohresultate erzeugen. Für Monitoring und Ownership werden sie auf dieselbe Ergebnisstruktur abgebildet.
    </figcaption>
</figure>

Die Tabelle ist append-orientiert.

Jede Testausführung erzeugt eine neue Zeile. Bestehende Resultate werden nicht mit dem neuesten Status überschrieben.

Das ermöglicht:

- Zeitreihen je Regel
- Vergleich von Data Products
- Fehlerentwicklung nach Severity
- Nachverfolgung technischer Errors
- Laufzeitanalyse
- Ownership zum Ausführungszeitpunkt
- Prüfung, ob eine Korrektur den nächsten Lauf verbessert hat

### Statusmodell

Für Fabric sollten mindestens diese Statuswerte verwendet werden:

| Status | Bedeutung |
| --- | --- |
| **Passed** | Regel ausgeführt und Threshold eingehalten |
| **Failed** | Regel ausgeführt und Threshold verletzt |
| **Error** | SQL, Notebook, Pipeline, MLV-Refresh oder Scan technisch fehlgeschlagen |
| **Skipped** | Ausführung kontrolliert ausgelassen |
| **Dropped** | optional für Regeln, die verletzende Datensätze bewusst entfernen |

`Dropped` ist nicht immer ein eigener Teststatus. Bei MLVs kann es besser sein, den Test als `Failed` oder `Passed With Violations` zu bewerten und die Anzahl verworfener Datensätze in einem zusätzlichen Feld zu speichern.

Wichtig ist nicht der konkrete Name, sondern eine organisationsweit konsistente Semantik.

## Fehlerdetails separat speichern

Die aggregierte Ergebnistabelle ist für Monitoring optimiert.

Fehlerhafte Datensätze gehören in eine separate Struktur:

```text
DQ_TEST_RESULT
Eine Zeile je Regelausführung

DQ_TEST_FAILURE_DETAIL
Null bis viele Fehlerdatensätze je Regelausführung
```

Im Fabric-Kontext kann die Detailstruktur gespeichert werden als:

- Delta-Tabelle im Lakehouse
- eingeschränkte Warehouse-Tabelle
- Purview Error Records in verwaltetem Speicher
- kontrollierter Pfad in OneLake

Ein möglicher Aufbau:

```sql
CREATE TABLE governance.dq_test_failure_detail (
    run_id          VARCHAR(100)  NOT NULL,
    rule_id         VARCHAR(100)  NOT NULL,
    record_key      VARCHAR(500)  NULL,
    failure_code    VARCHAR(100)  NULL,
    failure_message VARCHAR(1000) NULL,
    detected_value  VARCHAR(1000) NULL,
    detected_at     DATETIME2(6)  NOT NULL
);
```

Dabei gelten strengere Regeln:

- keine unnötige PII-Duplizierung
- sensible Werte maskieren oder hashen
- Zugriff einschränken
- Aufbewahrungsdauer definieren
- sehr große Fehlermengen begrenzen
- `Details URI` statt vollständiger Werte in der Ergebnistabelle verwenden

## Qlik und Power BI auf eine gemeinsame Ergebnisschicht setzen

Qlik und Power BI sollten nicht für jede technische Testquelle eigene Logik implementieren.

Besser ist:

```flowchart
Fabric DQ Result Table
Governed DQ View
Qlik / Power BI
```

Eine kuratierte View kann:

- technische Feldnamen vereinheitlichen
- Statuswerte normalisieren
- Owner und Data Product ergänzen
- MLV- und Purview-Metriken mappen
- technische Testläufe herausfiltern
- aktuelle und historische Perspektiven bereitstellen

Typische Auswertungen sind:

- Failure Rate over Time
- Failed Rules by Data Product
- Critical Rules by Owner
- MLV Constraint Violations
- Purview Quality Score
- Technical Errors by Execution Method
- Test Duration and Capacity Hotspots
- Re-Test after Remediation

Die Zeilenfehlerrate wird weiterhin gewichtet berechnet:

```text
Sum(Rows Failed) / Sum(Rows Tested)
```

Qlik:

```qlik
Num(
    Sum(rows_failed) / Sum(rows_tested),
    '0.00%'
)
```

Power BI:

```DAX
Failure Rate =
DIVIDE(
    SUM(DQ_Test_Result[Rows Failed]),
    SUM(DQ_Test_Result[Rows Tested])
)
```

Purview Quality Scores und zeilenbasierte Failure Rates sind unterschiedliche Kennzahlen. Sie sollten nicht in einer gemeinsamen Prozentzahl vermischt werden.

## Empfohlene Fabric-Zielarchitektur

Für einen produktiven Einstieg bietet sich folgende Struktur an:

```flow linear vertical
Governance.DQ_RULE
Governance.DQ_TEST_RESULT
Governance.DQ_TEST_FAILURE_DETAIL
Governance.V_DQ_MONITORING
Qlik / Power BI
```

### `DQ_RULE`

Verwaltet die Regeldefinition:

- Rule ID
- fachliche Beschreibung
- Data Product
- Tabelle und Spalte
- Test Type
- Threshold
- Severity
- Owner
- Execution Method
- Implementierungsreferenz
- Version
- Aktivstatus

### `DQ_TEST_RESULT`

Speichert eine Zeile je Ausführung.

### `DQ_TEST_FAILURE_DETAIL`

Speichert kontrollierte Evidenz für die Behebung.

### `V_DQ_MONITORING`

Stellt ein konsistentes, BI-freundliches Modell bereit.

## Praktischer Start in Fabric

Ein sinnvoller erster Sprint kann klein bleiben:

1. Ein Lakehouse oder Warehouse als kanonischen DQ-Speicher auswählen.
2. Die zentrale `DQ_TEST_RESULT`-Tabelle anlegen.
3. Vier Regeln implementieren: Pflichtfeld, Wertebereich, Duplikate und Aktualität.
4. Eine SQL- und eine Notebook-Ausführung umsetzen.
5. Eine Pipeline erzeugt den gemeinsamen `Run ID` und startet beide Ausführungsarten.
6. Resultate append-orientiert speichern.
7. Ein kleines Qlik- oder Power-BI-Modell auf die Tabelle setzen.
8. Owner und Severity sichtbar machen.
9. Optional eine Materialized Lake View mit Constraint ergänzen.
10. Optional einen Purview DQ Scan für dasselbe Data Product konfigurieren und die Rollen klar abgrenzen.

Damit entsteht ein belastbarer vertikaler Prototyp:

```flowchart
Rule
Execution
Result
Dashboard
Owner
Remediation
```

## Typische Fehlentscheidungen

### Nur Pipeline-Status überwachen

Eine grüne Pipeline bedeutet lediglich, dass die Technik ausgeführt wurde. Sie sagt nicht, dass die Daten fachlich korrekt sind.

### Testergebnisse überschreiben

Dadurch gehen Historie, Trends und Auditierbarkeit verloren.

### Lakehouse und Warehouse parallel als Master führen

Das erzeugt widersprüchliche Ergebnisse. Es sollte eine kanonische Ergebnisquelle geben.

### Purview Score und Failure Rate gleichsetzen

Ein Governance-Score und der Anteil fehlerhafter Zeilen beantworten unterschiedliche Fragen.

### MLV Drops nicht transparent machen

Entfernte Zeilen können zu scheinbar sauberen Silver-Daten führen. Die Anzahl der Verletzungen und Drops muss sichtbar bleiben.

### `Rows Tested = 0` als bestanden werten

Ein leerer Prüfumfang kann ein technischer oder fachlicher Fehler sein und braucht eine definierte Behandlung.

### Dieselbe Regel mehrfach ausführen und mehrfach zählen

SQL-Test, MLV-Constraint und Purview-Regel können dasselbe Risiko prüfen. Ohne führende Quelle und Mapping entsteht Doppelzählung.

## Die zentrale Erkenntnis

> **Microsoft Fabric bietet mehrere Wege, Datenqualität auszuführen. Operational Data Quality entsteht erst, wenn alle relevanten Ausführungen in einen stabilen Ergebnisvertrag überführt werden.**

SQL, Notebooks und Pipelines liefern flexible technische Tests.

Materialized Lake Views integrieren deklarative Constraints direkt in Transformation und Refresh.

Microsoft Purview ergänzt Profiling, Regeln, Scores, Scans und Stewardship.

Die Delta- oder Warehouse-Ergebnistabelle verbindet diese Fähigkeiten mit:

- Qlik und Power BI
- Data Products
- Ownern und Severity
- historischen Trends
- technischen Fehlern
- Fehlerdetails
- Remediation und erneutem Test

## Passende Playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [The Missing Pieces — Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality & Governance](/playbooks/data-quality-governance)
- [Cloud Hosting Models for Data Platforms](/playbooks/cloud-hosting-models-data-platforms)

## Quellen und weiterführende Dokumentation

- [Microsoft Fabric — What is a Lakehouse?](https://learn.microsoft.com/en-us/fabric/data-engineering/lakehouse-overview)
- [Microsoft Fabric — Lakehouse and Delta Tables](https://learn.microsoft.com/en-us/fabric/data-engineering/lakehouse-and-delta-tables)
- [Microsoft Fabric — Choose between Warehouse and Lakehouse](https://learn.microsoft.com/en-us/fabric/fundamentals/decision-guide-lakehouse-warehouse)
- [Microsoft Fabric — Create Tables in the Warehouse](https://learn.microsoft.com/en-us/fabric/data-warehouse/create-table)
- [Microsoft Fabric — Transform Data with a Stored Procedure](https://learn.microsoft.com/en-us/fabric/data-warehouse/tutorial-transform-data)
- [Microsoft Fabric Data Factory — Stored Procedure Activity](https://learn.microsoft.com/en-us/fabric/data-factory/stored-procedure-activity)
- [Microsoft Fabric Data Factory — Script Activity](https://learn.microsoft.com/en-us/fabric/data-factory/script-activity)
- [Microsoft Fabric Data Factory — Notebook Activity](https://learn.microsoft.com/en-us/fabric/data-factory/notebook-activity)
- [Microsoft Fabric — Materialized Lake Views Overview](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/overview-materialized-lake-view)
- [Microsoft Fabric — Data Quality in Materialized Lake Views](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/data-quality)
- [Microsoft Fabric — Data Quality Report for Materialized Lake Views](https://learn.microsoft.com/en-us/fabric/data-engineering/materialized-lake-views/data-quality-reports)
- [Microsoft Purview — Data Quality for Fabric Lakehouse](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-fabric-lakehouse)
- [Microsoft Purview — Data Quality Thresholds](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-threshold)
- [Microsoft Purview — Data Quality Error Records](https://learn.microsoft.com/en-us/purview/unified-catalog-data-quality-error-records)

> **Stand der Funktionsbeschreibung:** Juli 2026. Fabric- und Purview-Funktionen, regionale Verfügbarkeit sowie Preview-Status können sich ändern. Für die Umsetzung sollte die aktuelle Microsoft-Dokumentation geprüft werden.
