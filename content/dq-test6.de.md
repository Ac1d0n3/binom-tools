---
title: Datenqualitäts-Cockpit in Qlik und Power BI — Von der Kennzahl bis zur Behebung
description: Wie eine standardisierte Data-Quality-Historie in Qlik und Power BI zu einem operativen Management-Cockpit mit Quality Score, Failure Rate, Trends, Ownership, SLA, Maßnahmen und Drill-down auf fehlerhafte Datensätze wird.
category: Datenqualität
tags:
  - data-quality
  - operational-data-quality
  - data-quality-cockpit
  - qlik-sense
  - power-bi
  - quality-score
  - failure-rate
  - drill-down
  - drillthrough
  - sla
  - remediation
  - ownership
  - data-governance
order: -1
author: Thomas Lindackers
series: operational-data-quality
seriesPart: 6
seriesTitle: Operative Datenqualität
hero: images/playbooks/dq-test6-hero.png
---

## Ein Dashboard ist erst dann ein Data Quality Cockpit, wenn daraus gehandelt wird

Die vorherigen Teile dieser Serie haben gezeigt, wie technische Tests in Microsoft Fabric, dbt und Databricks standardisierte, historische Ergebnisse erzeugen.

Damit existiert die Grundlage:

```flowchart
Quality Rule
Test Execution
Standardized DQ Result
Historical Storage
```

Ein operatives Data Quality Cockpit ergänzt die Steuerungsebene:

```flowchart
Historical DQ Result
Quality KPIs
Drill-down
Owner and SLA
Remediation
Re-Test
```

Das Cockpit muss deshalb mehr leisten als eine grüne oder rote Übersicht.

Es muss beantworten:

- Wie entwickelt sich die Datenqualität?
- Welche Data Products verschlechtern sich?
- Welche kritischen Regeln schlagen fehl?
- Welche Tabelle und Spalte sind betroffen?
- Wie viele Datensätze sind fehlerhaft?
- Wer ist verantwortlich?
- Welche Maßnahme ist offen?
- Wird das vereinbarte SLA eingehalten?
- Welche konkreten Datensätze müssen untersucht werden?
- Hat die Behebung den nächsten Testlauf verbessert?

> **Ein Data Quality Cockpit verbindet Messung, Verantwortlichkeit und Behebung in einem durchgängigen operativen Prozess.**

## Das gemeinsame Datenmodell hinter Qlik und Power BI

Qlik und Power BI sollten dieselben kuratierten Fakten verwenden.

Ein belastbares Modell besteht aus mindestens vier fachlichen Objekten:

```flow linear vertical
DQ_RULE
DQ_TEST_RESULT
DQ_FAILURE_DETAIL
DQ_ACTION
```

Optional ergänzt:

```text
DQ_RUN
DQ_DATA_PRODUCT
DQ_OWNER
DQ_SLA_POLICY
DQ_DATE
```

### `DQ_RULE`

Eine Zeile je versionierter Qualitätsregel.

Typische Felder:

- Rule ID
- Rule Version
- Rule Name
- Business Description
- Data Product
- Table
- Column
- Test Type
- Threshold
- Severity
- Owner
- Active From
- Active To
- Execution Platform
- Details Link

### `DQ_TEST_RESULT`

Eine Zeile je Regelausführung.

Typische Felder:

- Run ID
- Rule ID
- Rule Version
- Status
- Rows Tested
- Rows Failed
- Failure Rate
- Executed At
- Platform
- Execution Method
- Duration
- Metric Completeness
- Owner Snapshot
- Severity Snapshot

### `DQ_FAILURE_DETAIL`

Null bis viele Fehlerdatensätze je Regelausführung.

Typische Felder:

- Run ID
- Rule ID
- Entity ID
- Failure Code
- Failure Reason
- Detected Value
- Source System
- Load ID
- Detail Timestamp

### `DQ_ACTION`

Eine Zeile je Maßnahme oder Ticket.

Typische Felder:

- Action ID
- Rule ID
- Run ID
- Owner
- Action Status
- Created At
- Due At
- Resolved At
- Resolution
- Ticket URL
- Re-Test Run ID
- SLA Status

Die Ergebnisfaktentabelle und die Fehlerdetailtabelle besitzen unterschiedliche Grains.

> **Aggregierte Qualität und einzelne Fehlerdatensätze dürfen nicht in eine einzige überladene Tabelle gezwungen werden.**

## Das Data Quality Management Cockpit

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test6-img1-de.png"
        alt="Datenqualitäts-Management-Cockpit mit Quality Score, Failure Rate, Trends, Schweregraden, Data Products, Regeln, Ownern, SLA, Maßnahmen und fehlerhaften Datensätzen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Cockpit verbindet Management-KPIs mit operativen Regel-, Owner-, SLA- und Fehlerdetails. Filter und Selektionen müssen sich konsistent durch alle Ebenen fortsetzen.
    </figcaption>
</figure>

Ein vollständiges Cockpit benötigt mehrere Perspektiven.

### 1. Management-KPIs

Die oberste Ebene zeigt wenige belastbare Kennzahlen:

- Quality Score
- Failure Rate
- ausgeführte Regeln
- fehlgeschlagene Prüfungen
- geprüftes Datenvolumen
- SLA-Konformität
- offene Maßnahmen
- überfällige Maßnahmen

Diese KPIs benötigen immer:

- einen Zeitraum
- einen Vergleichszeitraum
- einen klaren Filterkontext
- eine dokumentierte Berechnung
- einen Zeitstempel der letzten Aktualisierung

### 2. Entwicklung über Zeit

Zeitreihen zeigen:

- Quality Score je Tag oder Run
- Failure Rate
- bestandene und fehlgeschlagene Regeln
- Warnungen
- technische Errors
- verworfene Datensätze
- offene und überfällige Maßnahmen

Ein einzelner aktueller Wert kann eine systematische Verschlechterung verbergen.

### 3. Regeln nach Schweregrad

Severity hilft bei der Priorisierung:

- Critical
- High
- Medium
- Low

Severity beschreibt die fachliche Auswirkung.

Sie ist nicht dasselbe wie:

- Teststatus
- technische Action wie Warn, Drop oder Fail
- Anzahl fehlerhafter Zeilen
- SLA-Status

Eine kritische Regel kann nur wenige Datensätze betreffen und trotzdem sofortige Reaktion erfordern.

### 4. Data Product, Tabelle und Spalte

Das Cockpit muss von einer unternehmensweiten Übersicht zu den betroffenen Datenobjekten führen:

```flowchart
Domain
Data Product
Table
Column
Rule
Run
Failure Record
```

Die Ebenen müssen dem Datenmodell und den Verantwortlichkeiten der Organisation entsprechen.

### 5. Owner

Owner-Dimensionen ermöglichen:

- Fehler nach verantwortlichem Team
- offene Maßnahmen je Owner
- SLA-Verstöße
- wiederkehrende Fehler
- nicht zugeordnete Regeln
- Regeln mit veraltetem Owner

Der Owner sollte aus der historischen Ergebniszeile oder einer zeitabhängigen Zuordnung kommen. Nur der aktuelle Owner einer Stammtabelle kann historische Verantwortlichkeit falsch darstellen.

### 6. SLA und offene Maßnahmen

Das Cockpit wird operativ, wenn es nicht nur Fehler, sondern deren Bearbeitung zeigt:

- offen
- in Analyse
- in Behebung
- wartet auf Quelle
- bereit für Re-Test
- gelöst
- akzeptiertes Risiko
- geschlossen

Dazu gehören:

- Fälligkeitsdatum
- Alter der Maßnahme
- verantwortlicher Owner
- letzter Kommentar
- Ticket oder Workflow-Link
- Resolution
- Re-Test-Ergebnis

## Failure Rate und Quality Score sind unterschiedliche Kennzahlen

Die beiden Kennzahlen beantworten unterschiedliche Fragen.

### Failure Rate

Die zeilengewichtete Failure Rate lautet:

```text
Sum(Rows Failed) / Sum(Rows Tested)
```

Sie beantwortet:

> Welcher Anteil der geprüften Datensätze verletzt die ausgewählten Regeln?

Die Kennzahl eignet sich besonders für Regeln mit sinnvoll vergleichbarem Zeilengrain.

Sie darf nicht als einfacher Durchschnitt gespeicherter Prozentwerte berechnet werden.

Beispiel:

```text
Test A:
1 Fehler / 10 Zeilen = 10 %

Test B:
100 Fehler / 100.000 Zeilen = 0,1 %

Einfacher Durchschnitt:
5,05 %

Zeilengewichtete Failure Rate:
101 / 100.010 ≈ 0,101 %
```

### Rule Pass Rate

```text
Passed Rule Runs / Executed Rule Runs
```

Sie beantwortet:

> Welcher Anteil der ausgeführten Regeln wurde bestanden?

Ein Test mit zehn geprüften Zeilen hat hier dasselbe Gewicht wie ein Test mit zehn Millionen Zeilen.

### Quality Score

Ein Quality Score ist keine universell standardisierte Kennzahl. Die Organisation muss seine Semantik dokumentieren.

Ein einfaches, transparentes Beispiel verwendet status- und severitygewichtete Regelresultate.

Statuswerte:

| Status | Score |
| --- | ---: |
| Passed | 1,00 |
| Warning | 0,50 |
| Filtered | 0,25 |
| Failed | 0,00 |
| Failed Early | 0,00 |
| Error | separat behandeln |
| Skipped | aus dem Nenner ausschließen |

Severity-Gewichte:

| Severity | Gewicht |
| --- | ---: |
| Critical | 8 |
| High | 4 |
| Medium | 2 |
| Low | 1 |

Berechnung:

```text
Quality Score =
Sum(Status Score × Severity Weight)
/
Sum(Severity Weight)
× 100
```

Beispiel:

```text
Critical Passed:
1,00 × 8

High Failed:
0,00 × 4

Medium Warning:
0,50 × 2

Low Passed:
1,00 × 1

Quality Score:
(8 + 0 + 1 + 1) / (8 + 4 + 2 + 1)
= 66,7 %
```

Dieses Beispiel ist bewusst einfach.

Alternativ kann die Organisation:

- Threshold-Abweichungen einbeziehen
- Data-Product-Kritikalität gewichten
- Regelabdeckung berücksichtigen
- technische Errors als eigene KPI behandeln
- Quality Dimensions wie Completeness, Validity und Timeliness getrennt bewerten

> **Der Score muss verständlich, reproduzierbar und über Zeit stabil sein. Eine scheinbar präzise Prozentzahl ohne dokumentierte Formel ist keine Governance-Kennzahl.**

## Errors und Skipped Tests nicht verstecken

Eine hohe Quality Score kann irreführend sein, wenn viele Tests technisch nicht ausgeführt wurden.

Deshalb sollten zusätzlich sichtbar sein:

- Test Coverage
- Error Rate
- Skipped Rate
- Metric Completeness
- letztes erfolgreiches Ergebnis
- Alter des letzten Tests

Beispiel:

```text
100 definierte Regeln
80 Passed
5 Failed
15 Error

Rule Pass Rate bezogen auf alle Regeln:
80 %

Rule Pass Rate nur auf ausgeführte Qualitätsresultate:
94,1 %

Beide Werte erzählen eine andere Geschichte.
```

Technische Errors gehören nicht automatisch in die Failure Rate der Fachdaten. Sie benötigen eine separate operative Perspektive.

## Qlik-Modell und Kennzahlen

Qlik eignet sich besonders für associative Selections über:

```text
Data Product
Table
Column
Rule
Owner
Severity
Run
Status
Action
```

Ein mögliches logisches Modell:

```text
DQ_TEST_RESULT
  ↕ RuleKey
DQ_RULE

DQ_TEST_RESULT
  ↕ RunID
DQ_RUN

DQ_FAILURE_DETAIL
  ↕ RunRuleKey
DQ_TEST_RESULT

DQ_ACTION
  ↕ RuleKey / RunRuleKey
DQ_TEST_RESULT
```

Synthetische Schlüssel und unkontrollierte Many-to-many-Verbindungen sollten vermieden werden.

Empfohlene technische Schlüssel:

```text
RuleKey =
Rule ID & '|' & Rule Version

RunRuleKey =
Run ID & '|' & Rule ID & '|' & Rule Version
```

### Qlik Failure Rate

```qlik
Num(
    Sum(rows_failed)
    /
    Sum(rows_tested),
    '0.00%'
)
```

Null-sicher:

```qlik
If(
    Sum(rows_tested) > 0,
    Num(
        Sum(rows_failed) / Sum(rows_tested),
        '0.00%'
    )
)
```

### Qlik Rule Pass Rate

```qlik
Num(
    Count({
        <test_status = {'Passed'}>
    } DISTINCT run_rule_key)
    /
    Count({
        <test_status -= {'Skipped'}>
    } DISTINCT run_rule_key),
    '0.0%'
)
```

### Qlik offene Maßnahmen

```qlik
Count({
    <action_status -= {'Resolved', 'Closed', 'Accepted Risk'}>
} DISTINCT action_id)
```

### Qlik überfällige Maßnahmen

```qlik
Count({
    <
        action_status -= {'Resolved', 'Closed', 'Accepted Risk'},
        due_date = {"<$(=Date(Today()))"}
    >
} DISTINCT action_id)
```

### Qlik Set Analysis für Critical Failures

```qlik
Count({
    <
        severity = {'Critical'},
        test_status = {'Failed', 'Failed Early'}
    >
} DISTINCT run_rule_key)
```

### Qlik Drill-down

Eine Master-Dimension kann beispielsweise enthalten:

```text
Domain
Data Product
Table
Column
Rule Name
```

Qlik wechselt innerhalb einer Drill-down-Dimension automatisch auf die nächste Ebene, wenn durch Selektionen nur noch ein Wert der aktuellen Ebene möglich ist.

Für den Weg bis zum einzelnen Fehlerdatensatz sind häufig getrennte Sheets sinnvoll:

1. Management Overview
2. Data Product and Rule Analysis
3. Failure Records
4. Action and SLA Management

Selections und Buttons können den Kontext zwischen den Sheets erhalten.

## Power-BI-Modell und Kennzahlen

Power BI sollte ein sauberes Sternschema verwenden.

Fakten:

- `Fact DQ Test Result`
- `Fact DQ Failure Detail`
- `Fact DQ Action`

Dimensionen:

- `Dim Rule`
- `Dim Data Product`
- `Dim Owner`
- `Dim Severity`
- `Dim Platform`
- `Dim Date`
- `Dim Run`

Beziehungen sollten bevorzugt von Dimension zu Fakt in eine Richtung filtern.

### DAX Failure Rate

```DAX
Failure Rate =
DIVIDE(
    SUM('Fact DQ Test Result'[Rows Failed]),
    SUM('Fact DQ Test Result'[Rows Tested])
)
```

### DAX Rule Pass Rate

```DAX
Rule Pass Rate =
DIVIDE(
    CALCULATE(
        DISTINCTCOUNT(
            'Fact DQ Test Result'[Run Rule Key]
        ),
        'Fact DQ Test Result'[Test Status] = "Passed"
    ),
    CALCULATE(
        DISTINCTCOUNT(
            'Fact DQ Test Result'[Run Rule Key]
        ),
        'Fact DQ Test Result'[Test Status] <> "Skipped"
    )
)
```

### DAX offene Maßnahmen

```DAX
Open Actions =
CALCULATE(
    DISTINCTCOUNT('Fact DQ Action'[Action ID]),
    NOT (
        'Fact DQ Action'[Action Status]
            IN { "Resolved", "Closed", "Accepted Risk" }
    )
)
```

### DAX überfällige Maßnahmen

```DAX
Overdue Actions =
CALCULATE(
    DISTINCTCOUNT('Fact DQ Action'[Action ID]),
    'Fact DQ Action'[Due At] < TODAY(),
    NOT (
        'Fact DQ Action'[Action Status]
            IN { "Resolved", "Closed", "Accepted Risk" }
    )
)
```

### DAX SLA-Konformität

```DAX
SLA Compliance =
DIVIDE(
    CALCULATE(
        DISTINCTCOUNT('Fact DQ Action'[Action ID]),
        'Fact DQ Action'[SLA Status] = "Within SLA"
    ),
    CALCULATE(
        DISTINCTCOUNT('Fact DQ Action'[Action ID]),
        NOT ISBLANK('Fact DQ Action'[SLA Status])
    )
)
```

### Drillthrough in Power BI

Eine Drillthrough-Seite kann auf folgende Felder gefiltert werden:

- Rule ID
- Run ID
- Data Product
- Table
- Column
- Owner

Dadurch navigiert ein Nutzer von einer aggregierten Regel direkt zu einer Detailseite im ausgewählten Kontext.

Geeignete Seiten:

1. Executive Overview
2. Data Product Quality
3. Rule Details
4. Failure Records
5. Action and SLA Details

## Vom KPI bis zum fehlerhaften Datensatz

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/dq-test6-img2-de.png"
        alt="Drill-down-Pfad vom übergeordneten Datenqualitäts-KPI über Zeit, Schweregrad, Data Product, Tabelle, Spalte und Regel bis zu fehlerhaften Datensätzen und einem einzelnen Datensatz"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Jeder Schritt reduziert den Filterkontext, ohne die vorherigen Auswahlen zu verlieren. Am Ende steht nicht nur ein roter KPI, sondern der konkrete Fehler mit Quelle, Load, Ursache und verantwortlicher Maßnahme.
    </figcaption>
</figure>

Ein sinnvoller Analysepfad besteht aus sieben Ebenen.

### 1. KPI

Der Nutzer erkennt:

```text
Quality Score = 92,4 %
Failure Rate = 7,6 %
```

Diese Ebene zeigt das Ausmaß, aber noch keine Ursache.

### 2. Entwicklung über Zeit

Die Zeitreihe beantwortet:

- einmaliger Ausreißer oder Trend?
- seit welchem Run?
- nach welchem Release?
- Verbesserung nach einer Maßnahme?
- wiederkehrendes Muster?

### 3. Schweregrad

Der Nutzer fokussiert kritische und hohe Fehler.

### 4. Data Product, Tabelle und Spalte

Der betroffene fachliche und technische Bereich wird lokalisiert.

### 5. Regeldetail

Die Regelansicht zeigt:

- Business Description
- Rule ID und Version
- Threshold
- Test Type
- Ausführungsplattform
- letzte Runs
- Fehlerrate
- Owner
- Severity
- offene Maßnahmen
- technische Details

### 6. Fehlerhafte Datensätze

Die Detailtabelle zeigt nur relevante Spalten:

- Entity ID
- betroffene Werte
- Failure Reason
- Source System
- Load ID
- Detected At

### 7. Einzelner Datensatz

Die tiefste Ebene zeigt den vollständigen kontrollierten Kontext:

- Business Key
- relevante Attribute
- Quellsystem
- Load- und Batch-Information
- Regelverletzung
- vorheriger und aktueller Wert
- zuständiger Owner
- Ticket oder Maßnahme
- Re-Test-Status

## Drill-down und Datenschutz

Fehlerdetails können sensible Informationen enthalten.

Das Cockpit benötigt deshalb getrennte Zugriffslevel.

### Management-Level

Sieht:

- Scores
- Trends
- Data Products
- Severity
- Owner
- aggregierte Fehlermengen

### Data Steward und Data Owner

Sieht zusätzlich:

- Rule Details
- Business Keys
- Fehlerursachen
- Maßnahmen
- kontrollierte Fehlerdetails

### Technische Spezialisten

Sieht zusätzlich:

- Source System
- Load ID
- technische Events
- Pipeline- oder Job-Information
- Quarantäne- oder Evidenzlink

Schutzmaßnahmen:

- PII maskieren
- unnötige Attribute nicht laden
- Detaildaten zeitlich begrenzen
- Section Access in Qlik oder Row-Level Security in Power BI einsetzen
- Exportberechtigungen begrenzen
- Zugriffe auditieren
- Detailseiten getrennt berechtigen

> **Ein Data Quality Cockpit darf nicht zu einem unkontrollierten alternativen Datenzugang werden.**

## SLA-Modell

SLA beginnt nicht erst mit dem Ticket.

Ein mögliches SLA-Modell:

| Severity | Acknowledge | Analyse begonnen | Behebung oder akzeptierter Plan |
| --- | --- | --- | --- |
| Critical | 1 Stunde | 4 Stunden | 1 Arbeitstag |
| High | 4 Stunden | 1 Arbeitstag | 3 Arbeitstage |
| Medium | 1 Arbeitstag | 3 Arbeitstage | 10 Arbeitstage |
| Low | 3 Arbeitstage | nach Planung | nach Roadmap |

Die konkreten Zeiten müssen von der Organisation definiert werden.

Sinnvolle Zeitpunkte:

```text
Detected At
Assigned At
Acknowledged At
Analysis Started At
Resolved At
Re-Tested At
Closed At
```

Daraus entstehen Kennzahlen:

- Time to Assign
- Time to Acknowledge
- Time to Resolve
- Time to Re-Test
- SLA Compliance
- Overdue Age
- Reopen Rate

### Ein Fehler ist nicht mit der Datenkorrektur abgeschlossen

Ein vollständiger Abschluss benötigt:

```flow linear vertical
Ursache behoben
Daten korrigiert oder erneut geladen
Regel erneut ausgeführt
Test bestanden
Maßnahme dokumentiert
Ticket geschlossen
```

Ohne Re-Test bleibt unklar, ob die Maßnahme wirksam war.

## Maßnahmen und Tickets

Die `DQ_ACTION`-Tabelle kann aus einem Workflow- oder Ticketsystem kommen.

Ein Data Quality Cockpit sollte mindestens verlinken können auf:

- ServiceNow
- Jira
- Azure DevOps
- Microsoft Planner
- Power Automate Workflow
- Qlik Application Automation
- internes Governance-Portal

Das BI-Tool muss nicht das führende Ticket-System werden.

Besser:

```flowchart
DQ Cockpit
Create or Open Action
Workflow / Ticket System
Status Synchronization
DQ Cockpit
```

Die führende Quelle für Maßnahmenstatus muss eindeutig sein.

## Alerting

Alerts sollten gezielt eingesetzt werden.

Sinnvolle Trigger:

- Critical Rule Failed
- Failure Rate über Threshold
- Quality Score fällt stärker als definierter Grenzwert
- Regel schlägt in mehreren Läufen hintereinander fehl
- Data Product wurde nicht geprüft
- Test endet mit Error
- Maßnahme wird überfällig
- Re-Test schlägt erneut fehl

Nicht jede einzelne fehlerhafte Zeile benötigt eine Benachrichtigung.

### Qlik

Qlik Cloud kann datenbasierte Alerts für definierte Bedingungen und Schwellenwerte auswerten. Qlik Alerting bietet ebenfalls datenbezogene und komplexere Alert-Bedingungen, abhängig von der eingesetzten Qlik-Umgebung und Lizenzierung.

### Power BI

Power BI unterstützt Datenwarnungen für bestimmte Dashboard-Kacheln wie KPIs, Karten und Gauges. Zusätzlich stehen je nach Umgebung weitere Report-Alert-Funktionen zur Verfügung. Die aktuell verfügbare Funktionalität und Preview-Kennzeichnung sollte vor der Umsetzung geprüft werden.

Für komplexe operative DQ-Alerts ist häufig eine vorgelagerte Workflow-Logik robuster als ausschließlich visuelles Alerting.

## Performance und Datenvolumen

Fehlerdetailtabellen können sehr groß werden.

Empfehlungen:

- Cockpit standardmäßig auf aggregierte Fakten setzen
- Fehlerdetails erst bei Drill-down laden
- aktuelle Detaildaten von historischer Evidenz trennen
- Partitionierung nach Datum oder Run
- inkrementelles Laden
- nur relevante Spalten bereitstellen
- große Fehlergruppen samplen oder paginieren
- Detail-Retention begrenzen
- aggregierte Historie länger aufbewahren

Beispiel:

```text
DQ_TEST_RESULT:
36 Monate

DQ_FAILURE_DETAIL:
30 bis 90 Tage

Quarantäne:
abhängig von Fachprozess und Datenschutz
```

Die konkreten Fristen müssen durch Governance, Datenschutz und operative Anforderungen festgelegt werden.

## Aktualität des Cockpits

Das Cockpit sollte sichtbar ausweisen:

- Last Result Loaded At
- Last Source Refresh
- Last Successful Test Run
- Result Latency
- Action Status Sync Time

Ein Quality Score von 100 Prozent ist wertlos, wenn die Daten seit drei Tagen nicht aktualisiert wurden.

Deshalb ist die Aktualität des Monitorings selbst eine Kontrollkennzahl.

## Empfohlene Cockpit-Seiten

### Seite 1 — Management Overview

- Quality Score
- Failure Rate
- Rule Pass Rate
- Trends
- Severity
- Top Data Products
- SLA
- offene Maßnahmen

### Seite 2 — Data Product Analysis

- Domain und Data Product
- Tabelle und Spalte
- Quality Dimensions
- Regeln
- Plattform
- zeitliche Entwicklung

### Seite 3 — Rule Details

- Regeldefinition
- Threshold
- Version
- Owner
- letzte Läufe
- Statushistorie
- technische Herkunft
- Maßnahmen

### Seite 4 — Failure Records

- Fehlerdatensätze
- Quellsystem
- Load ID
- Ursache
- Evidenz
- Export nur bei Berechtigung

### Seite 5 — Actions and SLA

- offene Maßnahmen
- überfällige Maßnahmen
- Owner
- Alter
- Status
- Re-Test
- Ticket-Link

## Abnahmekriterien für ein operatives Cockpit

Ein Cockpit ist bereit für den Betrieb, wenn:

1. Jede KPI-Formel dokumentiert ist.
2. Failure Rate und Rule Pass Rate getrennt sind.
3. Errors und Skipped Tests sichtbar bleiben.
4. Zeit-, Data-Product-, Regel- und Owner-Filter konsistent funktionieren.
5. Der Drill-down bis zur Regel möglich ist.
6. Berechtigte Nutzer Fehlerdetails sehen können.
7. Owner und Severity historisch nachvollziehbar sind.
8. Maßnahmen und SLA mit den Fehlern verbunden sind.
9. Ein Re-Test-Ergebnis den Abschluss bestätigt.
10. Qlik und Power BI dieselbe fachliche Semantik verwenden.
11. Detailzugriffe datenschutzkonform sind.
12. Cockpit-Aktualität und Datenlatenz sichtbar sind.

## Typische Fehlentscheidungen

### Einen nicht dokumentierten Quality Score verwenden

Dann ist die Kennzahl nicht auditierbar und kann nicht verlässlich verglichen werden.

### Prozentwerte mitteln

Failure Rates müssen auf Basis ihrer Zähler und Nenner aggregiert werden.

### Errors ausblenden

Ein nicht ausführbarer Test ist kein bestandener Test.

### Management und Fehlerdetails auf dieselbe Seite laden

Das verschlechtert Performance, Bedienbarkeit und Datenschutz.

### Owner nur als aktuellen Stammdatenwert verwenden

Historische Verantwortlichkeit kann dadurch falsch erscheinen.

### Maßnahmen ohne Re-Test schließen

Die tatsächliche Verbesserung bleibt unbewiesen.

### Qlik- und Power-BI-Logik getrennt entwickeln

Dann entstehen unterschiedliche Quality Scores für dieselben Daten.

### Jede Warnung als Ticket erzeugen

Das erzeugt Alert Fatigue und überlastet den Prozess.

### Details ohne Zugriffskontrolle bereitstellen

Datenqualität darf Datenschutz und Berechtigungskonzepte nicht umgehen.

## Die zentrale Erkenntnis

> **Ein Data Quality Cockpit ist die operative Schnittstelle zwischen technischen Tests, fachlicher Verantwortung und nachweisbarer Verbesserung.**

Die standardisierte DQ-Historie liefert:

- Regeln
- Testläufe
- Status
- Mengen
- Fehlerraten
- Owner
- Severity

Qlik und Power BI ergänzen:

- Quality Score
- Trends
- Selektionen und Drill-down
- Data-Product- und Regelperspektiven
- SLA- und Maßnahmensteuerung
- kontrollierten Zugriff auf Fehlerdetails

Der Prozess wird geschlossen durch:

```flowchart
Measure
Prioritize
Assign
Remediate
Re-Test
Improve
```

Damit endet die Serie nicht bei einer Visualisierung. Sie endet bei einem wiederholbaren Betriebsmodell für messbare Datenqualität.

## Passende Playbooks

- [Part 1 — From Tests to Data Quality Monitoring](/playbooks/dq-test-kpis)
- [Part 2 — Data Quality in Microsoft Fabric](/playbooks/dq-test2)
- [Part 3 — Data Quality with dbt](/playbooks/dq-test3)
- [Part 4 — Data Quality in Databricks](/playbooks/dq-test4)
- [Part 5 — One Rule, Three Platforms](/playbooks/dq-test5)
- [Data Quality & Governance](/playbooks/data-quality-governance)
- [KPI Definition and Versioning](/playbooks/define-kpi)

## Quellen und weiterführende Dokumentation

- [Qlik Sense — Creating a Drill-down Dimension](https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Dimensions/create-drill-down-dimension.htm)
- [Qlik Cloud — Monitoring Changes with Alerts](https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Alerting/monitoring-changes-with-alerts.htm)
- [Qlik Cloud — Managing Alerts](https://help.qlik.com/en-US/cloud-services/Subsystems/Hub/Content/Sense_Hub/Alerting/managing-your-alerts.htm)
- [Qlik Alerting — Data Alerts](https://help.qlik.com/en-US/alerting/November2025/Content/QlikAlerting/data-alerts.htm)
- [Power BI — Drillthrough in Reports](https://learn.microsoft.com/en-us/power-bi/create-reports/desktop-drillthrough)
- [Power BI — Use Report Page Drillthrough](https://learn.microsoft.com/en-us/power-bi/guidance/report-drillthrough)
- [Power BI — Set Data Alerts on Dashboards](https://learn.microsoft.com/en-us/power-bi/explore-reports/end-user-alerts)
- [Power BI — Set Alerts on Reports](https://learn.microsoft.com/en-us/power-bi/explore-reports/business-user-set-alerts)
- [Power BI — Row-Level Security Guidance](https://learn.microsoft.com/en-us/power-bi/guidance/rls-guidance)
- [Power BI — Star Schema Guidance](https://learn.microsoft.com/en-us/power-bi/guidance/star-schema)

> **Stand der Funktionsbeschreibung:** Juli 2026. Qlik- und Power-BI-Funktionen, Lizenzabhängigkeiten und Preview-Status können sich ändern. Vor der Umsetzung sollte die aktuelle Herstellerdokumentation geprüft werden.
