---
title: Von Tests zu messbarer Datenqualität — Operatives Data-Quality-Monitoring
description: Wie technische Datenqualitätstests so aufgebaut werden, dass jeder Lauf eine standardisierte Ergebniszeile erzeugt und dadurch in Qlik und Power BI historisch, vergleichbar und operativ auswertbar wird.
category: Datenqualität
tags:
  - data-quality
  - operational-data-quality
  - data-quality-monitoring
  - data-observability
  - microsoft-fabric
  - dbt
  - databricks
  - qlik-sense
  - power-bi
  - data-governance
  - data-stewardship
  - ownership
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 1
seriesTitle: Operative Datenqualität
hero: images/playbooks/dq-test-kpis-hero.png
---

## Ein bestandener oder fehlgeschlagener Test ist noch kein Monitoring

Technische Datenqualitätstests sind in modernen Datenplattformen schnell erstellt.

Eine SQL-Abfrage sucht nach `NULL`-Werten. Ein dbt-Test liefert fehlerhafte Datensätze zurück. Ein Fabric-Notebook berechnet Dubletten. Eine Databricks-Pipeline prüft Erwartungen an eingehende Daten. Ein Stored Procedure kontrolliert referenzielle Integrität.

Damit ist die konkrete Prüfung automatisiert.

Für ein dauerhaftes Data-Quality-Management fehlen aber häufig weiterhin die entscheidenden Antworten:

- Welche Regel ist heute fehlgeschlagen?
- Wie viele Zeilen wurden geprüft?
- Wie viele Zeilen waren fehlerhaft?
- Ist die Fehlerrate gegenüber gestern gestiegen?
- Welches Data Product ist betroffen?
- Wie kritisch ist der Fehler?
- Wer muss reagieren?
- Tritt dasselbe Problem regelmäßig auf?
- Wurde der Fehler nach einer Korrektur tatsächlich behoben?

Ein Test, der nur einen Exit Code, eine Logmeldung oder eine temporäre Fehlerliste erzeugt, beantwortet diese Fragen nicht zuverlässig.

> **Operational Data Quality beginnt dort, wo jeder Testlauf ein messbares, historisches und verantwortbares Ergebnis erzeugt.**

Das Grundprinzip dieser Serie lautet:

```flowchart
Data Platform
Quality Rules
Test Execution
Standardized DQ Result Table
Qlik / Power BI
Ownership and Remediation
```

Die Plattform darf wechseln. Die Testlogik darf plattformspezifisch sein. Das Ergebnisformat muss stabil bleiben.

## Aus einzelnen Tests wird eine auswertbare Historie

Jede Qualitätsregel prüft eine fachliche oder technische Erwartung.

Beispiele:

- `CUSTOMER.CUSTOMER_ID` darf nicht leer sein
- `SALES.ORDER_ID` muss eindeutig sein
- `SALES.PRODUCT_ID` muss in `PRODUCT.PRODUCT_ID` existieren
- `SALES.AMOUNT` muss größer oder gleich null sein
- `SALES.LAST_LOAD_TS` darf nicht älter als 24 Stunden sein

Der Test soll nicht nur `Passed` oder `Failed` zurückgeben. Er soll mindestens vier Arten von Informationen erzeugen:

| Bereich | Beispiele |
| --- | --- |
| **Ausführungskontext** | Run ID, Plattform, Ausführungszeitpunkt |
| **Regelkontext** | Rule ID, Testtyp, Tabelle, Spalte |
| **Messwerte** | geprüfte Zeilen, fehlerhafte Zeilen, Fehlerrate |
| **Governance-Kontext** | Data Product, Owner, Severity |

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test-kpis-img1-de.png"
        alt="Einzelne Datenqualitätstests schreiben standardisierte Messergebnisse in eine zentrale DQ-Ergebnistabelle, die anschließend in Qlik und Power BI überwacht und für Maßnahmen genutzt wird"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der entscheidende Schritt ist nicht ein weiteres Dashboard. Jeder Test muss zuerst eine konsistente Ergebniszeile erzeugen. Erst dadurch werden Datenqualität, Trends, Ownership und Behebung gemeinsam steuerbar.
    </figcaption>
</figure>

Der Grain der zentralen Tabelle ist eindeutig:

> **Eine Zeile repräsentiert die Ausführung einer Regel gegen ein definiertes Datenobjekt zu einem bestimmten Zeitpunkt.**

Ein Pipeline-Lauf mit 120 Regeln erzeugt daher typischerweise 120 Ergebniszeilen mit derselben `Run ID`, aber unterschiedlichen `Rule IDs`.

## Die zentrale DQ-Ergebnisstruktur

Die minimale, plattformunabhängige Struktur besteht aus den folgenden Feldern:

| Feld | Bedeutung |
| --- | --- |
| **Run ID** | Gruppiert alle Testergebnisse eines gemeinsamen Pipeline-, Job- oder Batch-Laufs |
| **Rule ID** | Stabile Kennung der Qualitätsregel über viele Ausführungen hinweg |
| **Platform** | Plattform oder Engine, auf der der Test ausgeführt wurde |
| **Data Product** | Fachlicher Datenkontext, für den die Regel gilt |
| **Table** | Geprüfte Tabelle, View oder logisches Datenobjekt |
| **Column** | Geprüfte Spalte; bei tabellenweiten Regeln optional |
| **Test Type** | Beispielsweise Not Null, Uniqueness, Referential Integrity, Freshness oder Business Rule |
| **Status** | Ergebnis des Testlaufs |
| **Rows Tested** | Anzahl der Datensätze im definierten Prüfumfang |
| **Rows Failed** | Anzahl der Datensätze, die die Regel verletzt haben |
| **Failure Rate** | Anteil fehlerhafter Datensätze am Prüfumfang |
| **Executed At** | Technischer Ausführungszeitpunkt |
| **Owner** | Verantwortliche Rolle oder verantwortliches Team |
| **Severity** | Fachlich vereinbarte Kritikalität der Regel |

Eine relationale Implementierung kann beispielsweise so aussehen:

```sql
CREATE TABLE governance.dq_test_result (
    run_id         VARCHAR(100),
    rule_id        VARCHAR(100),
    platform       VARCHAR(100),
    data_product   VARCHAR(200),
    table_name     VARCHAR(256),
    column_name    VARCHAR(256),
    test_type      VARCHAR(100),
    test_status    VARCHAR(30),
    rows_tested    BIGINT,
    rows_failed    BIGINT,
    failure_rate   DECIMAL(18,8),
    executed_at    TIMESTAMP,
    owner_name     VARCHAR(200),
    severity       VARCHAR(30)
);
```

Die Datentypen müssen an die jeweilige Plattform angepasst werden. Das logische Schema sollte dagegen überall gleich bleiben.

### Warum Run ID und Rule ID getrennt sind

`Run ID` und `Rule ID` erfüllen unterschiedliche Aufgaben.

Eine `Run ID` beantwortet:

> Welche Tests gehörten zu derselben technischen Ausführung?

Eine `Rule ID` beantwortet:

> Wie hat sich dieselbe Qualitätsregel über viele Ausführungen entwickelt?

Ohne stabile `Rule ID` entsteht keine verlässliche Zeitreihe. Wird die Kennung bei jeder Umbenennung oder bei jeder Codeänderung neu erzeugt, sieht das Monitoring eine neue Regel statt einer neuen Version derselben Regel.

### Platform beschreibt die Ausführungsengine

Das Feld `Platform` sollte die technische Umgebung benennen, die den Test ausgeführt hat, beispielsweise:

- Microsoft Fabric
- dbt
- Databricks
- SQL Server
- Snowflake
- weitere SQL- oder Data-Engineering-Plattformen

Es ist nicht automatisch dasselbe wie das fachliche Quellsystem. Ein dbt-Test kann beispielsweise in einem Fabric Warehouse ausgeführt werden, obwohl die ursprünglichen Daten aus SAP stammen.

### Owner und Severity sind historische Snapshots

Ownership und Kritikalität können sich ändern.

Wird nur der aktuelle Wert aus einer Regelstammtabelle geladen, erscheint ein historischer Fehler möglicherweise unter einem Owner, der zum damaligen Zeitpunkt noch nicht verantwortlich war.

Deshalb ist es sinnvoll, `Owner` und `Severity` bei jeder Ausführung in die Ergebniszeile zu übernehmen. Eine zusätzliche Regelstammtabelle kann weiterhin die aktuelle Definition verwalten.

## Ein Test muss Messwerte produzieren

Ein einfacher Not-Null-Test kann als Messabfrage formuliert werden:

```sql
SELECT
    COUNT(*) AS rows_tested,
    SUM(
        CASE
            WHEN email IS NULL THEN 1
            ELSE 0
        END
    ) AS rows_failed
FROM dbo.customer;
```

Die Fehlerrate ergibt sich aus:

```text
Rows Failed / Rows Tested
```

Das vollständige Testmuster schreibt die berechneten Werte anschließend in die zentrale Tabelle:

```sql
INSERT INTO governance.dq_test_result (
    run_id,
    rule_id,
    platform,
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
    severity
)
SELECT
    'RUN_20260718_081530',
    'DQ-001',
    'Microsoft Fabric',
    'Customer 360',
    'CUSTOMER',
    'EMAIL',
    'Not Null',
    CASE
        WHEN rows_failed = 0 THEN 'Passed'
        ELSE 'Failed'
    END,
    rows_tested,
    rows_failed,
    CAST(rows_failed AS DECIMAL(18,8))
        / NULLIF(rows_tested, 0),
    CURRENT_TIMESTAMP,
    'Data Steward CRM',
    'High'
FROM (
    SELECT
        COUNT(*) AS rows_tested,
        SUM(
            CASE
                WHEN email IS NULL THEN 1
                ELSE 0
            END
        ) AS rows_failed
    FROM dbo.customer
) test_metrics;
```

Das Beispiel ist bewusst generisch. In einer produktiven Implementierung werden `Run ID`, Plattform, Owner, Severity und weitere Parameter normalerweise durch Orchestrierung, Regelmetadaten oder ein wiederverwendbares Macro bereitgestellt.

Der zentrale Gedanke bleibt:

> **Die Qualitätsregel endet nicht bei der Prüfung. Sie endet mit dem persistierten Ergebnis.**

## Status muss Datenfehler und Technikfehler unterscheiden

Ein fehlgeschlagener Qualitätstest ist nicht dasselbe wie ein technisch nicht ausführbarer Test.

Empfehlenswerte Statuswerte sind:

| Status | Bedeutung |
| --- | --- |
| **Passed** | Die Regel wurde erfolgreich ausgeführt und der vereinbarte Schwellenwert eingehalten |
| **Failed** | Die Regel wurde erfolgreich ausgeführt, aber der Schwellenwert wurde verletzt |
| **Error** | Der Test konnte technisch nicht korrekt ausgeführt oder ausgewertet werden |
| **Skipped** | Die Regel wurde bewusst nicht ausgeführt, beispielsweise wegen einer Abhängigkeit oder Freigabe |

Diese Trennung ist für das Monitoring wesentlich.

Ein SQL-Fehler darf nicht als Datenqualitätsfehler gezählt werden. Umgekehrt darf ein technisch erfolgreicher Job nicht als qualitativ erfolgreich erscheinen, wenn 20 Prozent der Datensätze die Regel verletzen.

Auch ein leerer Prüfumfang benötigt eine definierte Behandlung. `Rows Tested = 0` darf nicht automatisch zu einem scheinbar perfekten Ergebnis führen. Je nach Regel kann dies `Error`, `Failed` oder ein eigener kontrollierter Status sein.

## Schwellenwerte gehören zur Regel

Nicht jede Regel benötigt Nulltoleranz.

Beispiele:

| Regel | Möglicher Schwellenwert |
| --- | --- |
| Primärschlüssel ist nicht null | 0 fehlerhafte Zeilen |
| Referenz auf aktives Produkt | maximal 0,01 Prozent |
| Telefonnummer vorhanden | mindestens 95 Prozent vollständig |
| Datenaktualität | letzte erfolgreiche Beladung jünger als 24 Stunden |
| Bestellwert | kein Wert kleiner als 0, außer dokumentierte Gutschriften |

Der Status sollte deshalb nicht fest mit `Rows Failed = 0` verbunden sein. Er wird aus Messwert und Regeldefinition abgeleitet.

```text
Measured Failure Rate
→ compare with threshold
→ derive test status
```

Die Ergebniszeile enthält das beobachtete Resultat. Die Regeldefinition sollte zusätzlich dokumentieren:

- erwartetes Verhalten
- Schwellenwert
- Gültigkeitsbereich
- Ausnahmebehandlung
- verantwortliche Rolle
- Severity
- Version

Diese Themen werden in weiteren Teilen der Serie vertieft.

## Ergebnis und Fehlerdetails sind zwei verschiedene Datenprodukte

Die zentrale Ergebnistabelle sollte kompakt bleiben.

Sie speichert eine Zeile je Regelausführung und eignet sich für:

- Monitoring
- Zeitreihen
- KPI-Berechnung
- Eskalation
- Ownership
- Auditierbarkeit

Für Ursachenanalyse werden häufig zusätzlich einzelne fehlerhafte Datensätze benötigt. Dafür sollte eine getrennte optionale Struktur verwendet werden:

```text
DQ_TEST_RESULT
Eine Zeile je Regelausführung

DQ_TEST_FAILURE_DETAIL
Null bis viele Fehlerdatensätze je Regelausführung
```

Eine Detailtabelle kann beispielsweise enthalten:

- Run ID
- Rule ID
- technischer oder maskierter Business Key
- Fehlercode
- Fehlerbeschreibung
- erkannter Wert
- Evidenz- oder Quellverweis

Dabei gelten zusätzliche Anforderungen:

- PII nicht unnötig duplizieren
- sensible Werte maskieren oder hashen
- Aufbewahrungsdauer begrenzen
- Zugriff stärker einschränken
- große Fehlermengen begrenzen oder samplen

Für das zentrale Monitoring reicht die aggregierte Ergebniszeile. Für die operative Behebung kann ein kontrollierter Link auf Detailinformationen ergänzt werden.

## Das Muster bleibt über Plattformen hinweg gleich

Die technische Umsetzung unterscheidet sich. Der Vertrag für das Ergebnis bleibt identisch.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test-kpis-img2-de.png"
        alt="Plattformneutrales Datenqualitätstestmuster von Quelldaten und Qualitätsregel über Testausführung und Kennzahlenberechnung bis zur zentralen DQ-Ergebnistabelle sowie Auswertung in Qlik und Power BI"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Fabric, dbt, Databricks oder eine klassische SQL-Plattform dürfen unterschiedliche Testmechanismen nutzen. Jede Ausführung wird jedoch auf dieselbe Ergebnisstruktur normalisiert.
    </figcaption>
</figure>

### Microsoft Fabric

In Fabric kann das Muster beispielsweise mit folgenden Komponenten umgesetzt werden:

- T-SQL-Abfrage im Warehouse
- Stored Procedure
- Fabric Notebook
- Data Pipeline
- Aufruf einer Stored Procedure durch eine Pipeline-Aktivität

Der Test berechnet die Kennzahlen und schreibt anschließend per `INSERT` eine Zeile in eine Warehouse-Tabelle. Die Orchestrierung liefert dabei beispielsweise `Run ID` und Ausführungszeitpunkt.

### dbt

dbt Data Tests sind SQL-Abfragen, die fehlerhafte Datensätze zurückgeben. Liefert ein Test keine Fehlerzeilen, gilt er als bestanden.

Mit `store_failures` kann dbt die fehlerhaften Datensätze eines Tests in einer eigenen Tabelle speichern. Das ist hilfreich für Ursachenanalyse, erzeugt aber noch nicht automatisch die hier beschriebene zentrale Ergebnisfaktentabelle.

Für das standardisierte Monitoring wird deshalb zusätzlich ein Muster benötigt, das beispielsweise:

- Testmetadaten aus dbt-Artefakten liest,
- die Anzahl fehlerhafter Zeilen ermittelt,
- Rule ID, Owner und Severity aus YAML- oder Governance-Metadaten ergänzt,
- eine Ergebniszeile in `DQ_TEST_RESULT` schreibt.

Das kann durch ein Macro, einen nachgelagerten dbt-Job, eine Orchestrierung oder eine kleine Metadaten-Pipeline erfolgen.

### Databricks

In Databricks können Qualitätsprüfungen mit SQL, PySpark, Notebooks oder Pipeline Expectations umgesetzt werden.

Expectations stellen Qualitätsmetriken im Pipeline Event Log bereit. Diese Metriken können in die standardisierte Ergebnisstruktur transformiert werden. Alternativ schreibt ein eigenes Test-Notebook die berechneten Kennzahlen direkt in eine Delta- oder SQL-Tabelle.

Entscheidend ist nicht, ob die Metrik ursprünglich aus SQL, Python oder einem Event Log stammt. Entscheidend ist, dass sie anschließend dieselben fachlichen Felder besitzt.

### Klassische SQL-Plattformen

In SQL Server, Snowflake, PostgreSQL oder anderen relationalen Plattformen kann das Muster mit wiederverwendbaren Stored Procedures, Views, Scheduled Queries oder Orchestrierungsjobs umgesetzt werden.

Eine generische Testprozedur kann Parameter erhalten wie:

```text
Rule ID
Table
Column
Test Type
Threshold
Owner
Severity
Run ID
```

Die Prozedur führt die Prüfung aus und schreibt das normalisierte Resultat.

## Direkt schreiben oder zentral konsolidieren

Nicht jede Plattform kann oder soll direkt in dieselbe physische Tabelle schreiben.

Zwei Architekturen sind üblich.

### Direkter Write

```flowchart
Test Execution
Central DQ Result Table
```

Der Test schreibt unmittelbar in die zentrale Governance- oder Monitoring-Datenbank.

Das ist einfach, wenn:

- alle Plattformen die Zieltabelle erreichen können
- Authentifizierung und Schreibrechte kontrollierbar sind
- das Schema zentral verwaltet wird
- der zusätzliche technische Coupling akzeptabel ist

### Lokales Ergebnis mit anschließender Konsolidierung

```flowchart
Test Execution
Local DQ Result
Consolidation Pipeline
Central DQ Result Table
```

Jede Plattform schreibt zunächst in eine lokale Tabelle oder ein lokales Event Log. Eine zentrale Pipeline vereinheitlicht und übernimmt die Ergebnisse.

Das passt häufig besser, wenn:

- mehrere Clouds oder Netzwerkzonen beteiligt sind
- Plattformen unterschiedliche Identitätsmodelle besitzen
- zentrale Schreibrechte vermieden werden sollen
- Event Logs oder native Testergebnisse bereits vorhanden sind
- die Konsolidierung bewusst entkoppelt werden soll

Beide Varianten erfüllen dasselbe Ziel. Qlik und Power BI sollten auf die konsolidierte, fachlich stabile Ergebnisschicht zugreifen.

## Qlik und Power BI konsumieren dieselben Fakten

Qlik und Power BI sind in diesem Muster nicht die Test-Engine.

Sie übernehmen drei Aufgaben:

1. Ergebnisse sichtbar machen
2. Trends und Schwerpunkte analysieren
3. Ownership und Maßnahmen unterstützen

Typische Visualisierungen sind:

- Failure Rate over Time
- fehlgeschlagene Regeln nach Severity
- Datenqualität nach Data Product
- wiederkehrend fehlschlagende Regeln
- Fehler nach Owner
- technische Errors und Skipped Tests
- Zeit bis zur Behebung
- erneuter Test nach einer Korrektur

### Fehlerraten nicht einfach mitteln

Eine wichtige Modellierungsregel lautet:

> **Fehlerraten über mehrere Tests oder Läufe dürfen nicht unkritisch als Durchschnitt der gespeicherten Prozentwerte berechnet werden.**

Beispiel:

- Test A: 1 Fehler bei 10 Zeilen = 10 Prozent
- Test B: 100 Fehler bei 100.000 Zeilen = 0,1 Prozent

Der einfache Durchschnitt wäre 5,05 Prozent. Die zeilengewichtete Fehlerrate beträgt dagegen ungefähr 0,101 Prozent.

Für eine zeilengewichtete Auswertung gilt:

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

Power BI / DAX:

```DAX
Failure Rate =
DIVIDE(
    SUM(DQ_Test_Result[Rows Failed]),
    SUM(DQ_Test_Result[Rows Tested])
)
```

Zusätzlich kann eine regelgewichtete Kennzahl sinnvoll sein:

```text
Passed Rules / Executed Rules
```

Beide Kennzahlen beantworten unterschiedliche Fragen:

- **Row Failure Rate:** Wie groß ist der Anteil fehlerhafter Daten?
- **Rule Pass Rate:** Wie groß ist der Anteil bestandener Regeln?

Sie sollten im Dashboard nicht miteinander verwechselt werden.

## Ownership macht aus Monitoring einen Prozess

Ein Dashboard allein verbessert keine Daten.

Operational Data Quality benötigt einen geschlossenen Ablauf:

```flow linear vertical
Fehler erkennen
Severity und Auswirkung bewerten
Owner zuordnen
Ursache analysieren
Daten oder Prozess korrigieren
Regel erneut ausführen
Ergebnis dokumentieren
Trend weiter überwachen
```

Die zentrale Tabelle schafft dafür die technische Basis.

Sie zeigt:

- wann ein Problem erstmals auftrat
- wie oft es wiederkehrte
- welche Datenmenge betroffen war
- wer zum Ausführungszeitpunkt verantwortlich war
- ob sich die Fehlerrate verschlechterte
- ob eine Korrektur den nächsten Test tatsächlich verbesserte

Damit wird Datenqualität von einer Sammlung technischer Checks zu einem messbaren Betriebsprozess.

## Praktische Designregeln

Für einen belastbaren Start gelten einige einfache Regeln:

1. **Eine Zeile je Regelausführung.** Der Grain darf nicht zwischen Tests wechseln.
2. **Stabile Rule IDs verwenden.** Anzeigenamen dürfen sich ändern, die Identität nicht.
3. **Run IDs über die Orchestrierung vergeben.** Zusammengehörige Tests müssen gruppierbar sein.
4. **Messwerte und Status getrennt speichern.** Der Status wird aus Messwert und Schwellenwert abgeleitet.
5. **Failed und Error unterscheiden.** Datenfehler und Technikfehler benötigen andere Maßnahmen.
6. **Owner und Severity zum Laufzeitpunkt speichern.** Historische Verantwortlichkeit muss reproduzierbar bleiben.
7. **Append statt Überschreiben.** Ohne Historie gibt es keine Trends und keine Nachvollziehbarkeit.
8. **Fehlerdetails getrennt halten.** Monitoring-Fakten und operative Evidenz haben unterschiedliche Grain- und Sicherheitsanforderungen.
9. **Qlik und Power BI dieselbe Logik geben.** Die Visualisierung darf den Qualitätsvertrag nicht neu interpretieren.
10. **Mit wenigen kritischen Regeln beginnen.** Ein kleiner sauberer Prozess ist wertvoller als Hunderte unbetreute Tests.

## Die zentrale Erkenntnis

> **Technische Tests werden erst zu Operational Data Quality, wenn jede Ausführung eine standardisierte, historische und verantwortbare Ergebniszeile erzeugt.**

Fabric, dbt, Databricks und klassische SQL-Plattformen können die Tests mit ihren jeweils passenden Mechanismen ausführen.

Die gemeinsame Ergebnistabelle verbindet diese technischen Prüfungen mit:

- Qlik und Power BI
- Zeitreihen und Kennzahlen
- Data Products
- Severity
- Ownership
- Ursachenanalyse
- Behebung und erneutem Test

Damit entsteht kein weiteres isoliertes Test-Framework. Es entsteht eine gemeinsame operative Sprache für Datenqualität.

## Passende Playbooks

- [The Missing Pieces — Data Quality](/playbooks/missing-pieces-data-quality)
- [Data Quality & Governance](/playbooks/data-quality-governance)
- [Welche Rolle dbt spielt](/playbooks/dbt-role)

## Quellen und weiterführende Dokumentation

- [Microsoft Fabric — Create Tables in the Warehouse](https://learn.microsoft.com/en-us/fabric/data-warehouse/create-table)
- [Microsoft Fabric — Transform Data with a Stored Procedure](https://learn.microsoft.com/en-us/fabric/data-warehouse/tutorial-transform-data)
- [Microsoft Fabric Data Factory — Stored Procedure Activity](https://learn.microsoft.com/en-us/fabric/data-factory/stored-procedure-activity)
- [dbt — Add Data Tests to Your DAG](https://docs.getdbt.com/docs/build/data-tests)
- [dbt — store_failures](https://docs.getdbt.com/reference/resource-configs/store_failures)
- [Databricks — Manage Data Quality with Pipeline Expectations](https://docs.databricks.com/aws/en/ldp/expectations)
- [Databricks — Lakeflow Pipeline Best Practices](https://docs.databricks.com/aws/en/ldp/best-practices)
- [Qlik Sense — Loading Data from Databases](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/DataSource/load-data-from-databases.htm)
- [Microsoft Fabric — Connect to Fabric Data Warehouse](https://learn.microsoft.com/en-us/fabric/data-warehouse/how-to-connect)
- [Microsoft Fabric — Create Reports in Power BI](https://learn.microsoft.com/en-us/fabric/data-warehouse/reports-power-bi-service)
