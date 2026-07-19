---
title: Fachliche Logik außerhalb der BI-Apps halten
description: Wie Qlik-Anwendungen, Power-BI-Berichte und Excel-Arbeitsmappen schlank bleiben, indem gemeinsame Bereinigung, Integration, Historisierung, KPI-Grundlagen und Datenqualitätsregeln in governten Modellen außerhalb einzelner BI-Artefakte definiert werden.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - business-logic
  - semantic-layer
  - data-products
  - qlik-sense
  - qvd
  - set-analysis
  - section-access
  - power-bi
  - dax
  - excel
  - sql
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - data-quality
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 6
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start6-hero.png
---

## Die BI-App darf nicht selbst zum Warehouse werden

Eine BI-Anwendung ist häufig der schnellste Ort, um ein konkretes Business-Problem zu lösen.

Ein Qlik-Entwickler verbindet Quelltabellen, bereinigt Kennungen, ordnet Statuswerte zu, erzeugt eine Kundenhistorie, berechnet Umsatz und baut die visuelle Analyse in einer Anwendung. Ein Power-BI-Entwickler führt ähnliche Transformationen in Power Query aus und ergänzt die endgültigen Definitionen in DAX. Ein Excel-Nutzer erhält Exporte, korrigiert Werte mit Formeln und entwickelt die Arbeitsmappe schrittweise zu einem operativen Reporting-Prozess.

Jedes einzelne Ergebnis kann nützlich sein. Das strukturelle Problem entsteht, sobald dieselbe fachliche Bedeutung unabhängig in mehreren Consumer-Artefakten implementiert wird.

Eine typische Landschaft enthält dann beispielsweise:

```text
Qlik-App A       Umsatz schließt Stornostatus 90 aus
Qlik-App B       Umsatz schließt Status 90 und 95 aus
Power-BI-Modell  Umsatz zieht Stornodokumente ab
Excel-Datei      Umsatz entfernt negative Werte manuell
SQL-Export       Umsatz verwendet das Stornokennzeichen der Quelle
```

Alle fünf Ergebnisse können **Umsatz** heißen. Sie bilden trotzdem nicht dieselbe Kennzahl ab.

Das Problem besteht nicht darin, dass Qlik-Skripte, DAX oder Excel-Formeln verwendet werden. Diese Fähigkeiten sind am richtigen Ort unverzichtbar. Problematisch wird es, wenn ein Consumer-Tool der einzige Ort ist, an dem gemeinsame Bereinigung, Integration, Historisierung und KPI-Regeln verstanden werden.

Daraus entstehen mehrere Folgen:

- Jeder neue Bericht implementiert vorhandene Logik erneut.
- Korrekturen müssen mehrfach ausgerollt werden.
- Datenqualität wird unterschiedlich oder überhaupt nicht geprüft.
- Lineage endet in schwer nachvollziehbarem Anwendungscode.
- Eine Anwendung kann einen anderen Consumer nicht sicher versorgen.
- Fachliche Definitionen hängen von einzelnen Entwicklern ab.
- Migrationen werden schwierig, weil Logik und Darstellung untrennbar verbunden sind.

Part 5, [Ein bestehendes Warehouse modernisieren](/playbooks/modernizing-an-existing-warehouse), hat gezeigt, wie duplizierte QVD-, SQL-, Qlik-, Power-BI- und Excel-Logik schrittweise ersetzt werden kann. Dieser Part definiert das Zielprinzip für die daraus entstehende Architektur.

> **Definiere gemeinsame fachliche Bedeutung einmal in einer governten Datenschicht. Qlik, Power BI und Excel konsumieren diese Bedeutung und ergänzen nur die Logik, die für ihr jeweiliges Analyseerlebnis spezifisch ist.**

## Architekturprinzip: zentrale Wahrheit, schlanke Consumer

Die Zielarchitektur trennt zwei unterschiedliche Verantwortlichkeiten.

Die governte Datenplattform verantwortet wiederverwendbare fachliche Wahrheit:

```text
Bereinigung
Standardisierung
Quellenintegration
Business Keys
Referenzzuordnungen
Historisierung
KPI-Grundregeln
Währungsbehandlung
Stornologik
Datenqualitätsregeln
Veröffentlichungsverträge
```

Die BI-Consumer verantworten Tool-spezifisches Analyseverhalten:

```text
Assoziative Exploration
Filterkontext
Visuelle Berechnungen
Interaktive Zeitvergleiche
Darstellung
Navigation
Kommentare
Kontrollierte manuelle Eingaben
```

Diese Trennung ist präziser als die Aussage „Alle Logik muss außerhalb der Apps liegen“. Bestimmte Logik besitzt nur innerhalb des Consumers eine sinnvolle Bedeutung.

Eine Qlik-Link-Table existiert wegen des assoziativen Modells. Section Access steuert den Zugriff in einer Qlik-Anwendung. Set Analysis kann einen benutzerbezogenen Vergleich innerhalb des aktuellen Selektionszustands ausdrücken. DAX wertet Measures im Filterkontext des semantischen Modells aus. Excel ermöglicht Layouts, Kommentare und kontrollierte Szenarien, die nicht zwingend in eine Warehouse-Tabelle gehören.

Das sind legitime Ausnahmen.

Die leitende Regel lautet deshalb:

> **Gemeinsame fachliche Wahrheit gehört außerhalb einzelner Apps. Tool-spezifische Semantik darf im Tool verbleiben, wenn sie einen klaren analytischen oder operativen Mehrwert erzeugt und explizit dokumentiert ist.**

## Die schlanke Qlik-Anwendung

Eine umfangreiche Qlik-Anwendung übernimmt mehrere Architekturaufgaben gleichzeitig:

```flow linear vertical
Quelldaten extrahieren
Operative Systeme verbinden
Werte bereinigen und standardisieren
Historie auflösen
Fachliche KPIs berechnen
Mappings erzeugen
Assoziatives Modell aufbauen
Zugriff anwenden
Visuelle Analyse erstellen
```

Eine schlanke Qlik-Anwendung beginnt wesentlich später im Lebenszyklus.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start6-img1-de.png"
        alt="Vergleich einer komplexen Qlik-Anwendung mit Quellen-Joins, Bereinigung, Historisierung, KPI-Berechnungen und Mappings mit einer schlanken Qlik-Anwendung, die ein governtes Modell lädt und nur Qlik-spezifische Assoziationen, Zugriff und visuelle Analysen enthält"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die governte Plattform bereitet wiederverwendbare Fachmodelle und qualitätsgesicherte Daten auf. Die Qlik-Anwendung lädt den kuratierten Vertrag und behält nur die Skript- und Modelllogik, die Qlik tatsächlich benötigt.
    </figcaption>
</figure>

Eine praktikable schlanke Qlik-Anwendung kann weiterhin enthalten:

- Verbindungs- und Ladeanweisungen;
- Feldauswahl und technische Umbenennungen für das lokale Modell;
- für das Analyseerlebnis erforderliche Assoziationen;
- eine Link Table oder kanonische Datumsstruktur, sofern fachlich und technisch begründet;
- Section Access, wenn die Zugriffsdurchsetzung auf Anwendungsebene erforderlich ist;
- gezielte Performance-Optimierungen;
- Master-Elemente und visuelle Ausdrücke;
- Set Analysis für interaktive Analysekontexte.

Sie sollte normalerweise nicht enthalten:

- wiederholte Joins zwischen Quellsystemen;
- die einzige Implementierung der Stornoregel;
- unabhängige Länder- oder Statuszuordnungen;
- lokale Rekonstruktion der Kundenhistorie;
- eigene Regeln für die Währungsumrechnung;
- duplizierte Datenqualitätsentscheidungen;
- eine abweichende Definition des unternehmensweiten Umsatz-KPI.

Der Begriff **schlanke App** bedeutet nicht „kein Skript“. Er bedeutet, dass das Skript auf Konsum, Qlik-spezifische Modellierung und begründete Optimierung beschränkt bleibt.

## Was gehört wohin?

Der richtige Ort einer Logik hängt davon ab, ob sie wiederverwendbare fachliche Wahrheit oder Consumer-spezifisches Analyseverhalten darstellt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start6-img2-de.png"
        alt="Verantwortungsmatrix für die Zuordnung von Logik zur governten Datenplattform, zu Qlik, Power BI und Excel, wobei zentrale Bereinigung, Integration, Historisierung, KPI-Grundlagen und Datenqualität von Tool-spezifischer Analyse und Darstellung getrennt werden"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Logik, die konsistent sein oder wiederverwendet werden muss, gehört in die governte Plattform. Logik, die durch das Analysemodell, die Oberfläche oder einen kontrollierten lokalen Workflow des Tools entsteht, darf im jeweiligen Consumer verbleiben.
    </figcaption>
</figure>

Eine praktikable Zuordnung lautet:

| Logik | Primärer Ort | Begründung |
| --- | --- | --- |
| Technische Bereinigung | Governte Datenplattform | Muss für jeden Consumer wiederholbar sein |
| Quellenintegration | Governte Datenplattform | Schafft eine gemeinsame Interpretation über Quellen hinweg |
| Auflösung von Business Keys | Governte Datenplattform | Verhindert Consumer-spezifische Identitäten |
| Kunden- und Produkthistorie | Governte Datenplattform | Historische Wahrheit muss reproduzierbar bleiben |
| Stornobehandlung | Governte Datenplattform | Verändert die gemeinsame fachliche Bedeutung des Umsatzes |
| Währungsnormalisierung | Governte Datenplattform | Muss eine freigegebene Kurs- und Datumsregel verwenden |
| KPI-Grundbetrag | Governte Datenplattform | Liefert eine wiederverwendbare faktische Grundlage |
| Datenqualitätsprüfungen | Governte Datenplattform | Müssen auditierbar und berichtsunabhängig sein |
| Qlik-Assoziationen | Qlik | Gehören zum assoziativen Nutzungsmodell |
| Qlik Section Access | Qlik oder Plattform plus Qlik | Erforderlich, wenn Zugriff in der App durchgesetzt wird |
| Qlik Set Analysis | Qlik | Beschreibt interaktive Analysen im Selektionskontext |
| DAX-Zeitintelligenz | Semantisches Power-BI-Modell | Wertet Measures im Power-BI-Filterkontext aus |
| DAX-Darstellungs-Measures | Semantisches Power-BI-Modell | Unterstützen wiederverwendbare Modellanalysen |
| Excel-Layout und Kommentare | Excel | Gehören zum Nutzungserlebnis der Arbeitsmappe |
| Kontrollierte manuelle Annahmen | Excel plus governter Rückführungspfad | Lokale Eingaben können gültig sein, wenn sie kontrolliert und nachvollziehbar bleiben |

Der Ort kann geteilt werden, wenn unterschiedliche Verantwortlichkeiten bestehen. Beispielsweise kann die Zugriffsberechtigung zentral erzeugt werden, während Section Access sie innerhalb von Qlik durchsetzt. Die zentrale Schicht definiert, welcher Nutzer für welche Vertriebsregion berechtigt ist. Die Qlik-App konsumiert diese Berechtigungstabelle und wendet sie an.

## Mit der einfachsten sinnvollen Umsetzung beginnen

Fachliche Logik außerhalb von BI-Anwendungen zu halten, erfordert weder eine neue Cloud-Plattform noch ein zusätzliches Transformationsprodukt.

Die kleinste sinnvolle Umsetzung kann so aussehen:

```flow linear vertical
Vorhandene Quellextrakte
Vorhandene SQL-Datenbank
Views oder kontrollierte Tabellen
Persistente Qualitätsergebnisse
Qlik / Power BI / Excel
```

Angenommen, ein Unternehmen verwendet bereits SQL Server, PostgreSQL, Oracle oder eine andere relationale Datenbank. Es kann eine kleine Zahl klarer Verantwortungsbereiche einführen:

```text
stg       technisch standardisierte Quelldaten
core      integrierte Entitäten, Schlüssel und Historie
mart      governte Fakten, Dimensionen und Reporting Views
quality   Testläufe und Regelergebnisse
control   Lade-, Versions- und Veröffentlichungsmetadaten
```

Das erste Ziel ist nicht, sofort einen theoretisch perfekten Semantic Layer zu schaffen. Zunächst wird eine duplizierte Regel an einen governten Ort verschoben.

Beispiel:

```sql
create view mart.sales_consumption as
select
    s.business_date,
    s.order_id,
    s.order_line_id,
    s.customer_key,
    s.product_key,
    s.country_code,
    s.currency_code,
    s.gross_revenue_amount,
    case
        when s.is_cancelled = 1 then 0
        else s.revenue_amount_in_reporting_currency
    end as net_revenue_amount,
    s.quality_status
from core.sales_order_line s
where s.publication_status = 'PUBLISHED';
```

Diese View ist bewusst einfach. Die eigentliche Komplexität sollte bereits in kontrollierten vorgelagerten Schritten aufgelöst worden sein:

- `is_cancelled` folgt einer freigegebenen Status- und Dokumentregel;
- `revenue_amount_in_reporting_currency` verwendet ein freigegebenes Wechselkursdatum;
- `customer_key` löst Kundenidentität und Historie auf;
- `country_code` folgt einer governten Zuordnung;
- `publication_status` berücksichtigt persistente Qualitätsprüfungen.

Qlik, Power BI und Excel erhalten nun dieselbe faktische Grundlage.

## Konkretes KPI-Beispiel: Nettoumsatz

Betrachten wir den KPI **Nettoumsatz**.

Seine gemeinsame fachliche Definition benötigt Entscheidungen in fünf Bereichen:

| Regelbereich | Governte Entscheidung |
| --- | --- |
| Stornos | Stornierte Auftragspositionen tragen null zum Nettoumsatz bei |
| Währung | Beträge verwenden die freigegebene Berichtswährung und das vereinbarte Kursdatum |
| Business-Datum | Die Analyse verwendet das abgestimmte Buchungs- oder Belegdatum |
| Kundenzuordnung | Die Transaktion verwendet die am Business-Datum gültige Kundenversion |
| Historie | Spätere Kundenänderungen schreiben historische Ergebnisse nicht um |
| Qualität | Datensätze ohne Pflichtschlüssel oder Wechselkurs werden abgewiesen, isoliert oder explizit markiert |

Diese Entscheidungen gehören in den governten Datenpfad, weil jeder Consumer sie identisch anwenden muss.

Die Plattform kann folgende Felder veröffentlichen:

```text
gross_revenue_amount
net_revenue_amount
reporting_currency
business_date
customer_key
product_key
country_code
quality_status
```

Die Consumer ergänzen anschließend Kontext, ohne die Kennzahl neu zu definieren.

### Qlik

Das Basis-Measure kann sehr klein bleiben:

```qlik
Sum([Net Revenue Amount])
```

Set Analysis kann einen analytischen Vergleich ergänzen:

```qlik
Sum({
    <BusinessYear = {"$(=Max(BusinessYear)-1)"}>
} [Net Revenue Amount])
```

Der Set-Analysis-Ausdruck steuert den Vergleichskontext. Er berechnet Stornos, Währung oder Kundenhistorie nicht erneut.

### Power BI

Das Basis-Measure kann denselben veröffentlichten Betrag verwenden:

```DAX
Net Revenue :=
SUM ( SalesConsumption[NetRevenueAmount] )
```

Ein Zeitvergleich kann im semantischen Modell verbleiben:

```DAX
Net Revenue Previous Year :=
CALCULATE (
    [Net Revenue],
    DATEADD ( 'Date'[Date], -1, YEAR )
)
```

DAX liefert den Modellspezifischen Analysekontext. Die zugrunde liegende Umsatzregel wird nicht neu aufgebaut.

### Excel

Excel sollte vorzugsweise mit der zertifizierten Reporting View oder dem semantischen Modell verbunden werden und Folgendes verwenden:

- eine PivotTable;
- eine kontrollierte Abfrage;
- eine aktualisierbare Tabelle;
- freigegebene Szenariospalten, die klar von Ist-Daten getrennt sind.

Die Arbeitsmappe sollte keine versteckte Formel wie diese enthalten:

```text
IF(amount < 0, 0, amount)
```

und das Ergebnis Nettoumsatz nennen. Dadurch entstünde eine neue lokale fachliche Definition.

## Fachliche Logik außerhalb der Apps

Sobald gemeinsame Logik externalisiert ist, kann ein governter Kern mehrere Consumer bedienen, ohne sie auf dieselbe Oberfläche oder Berechnungssprache festzulegen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start6-img3-de.png"
        alt="Eine governte Fachlogikschicht mit Metriken, Geschäftsregeln, Referenzdaten, Transformationen, Datenqualität und Zeitkontext, die Qlik, Power BI, Excel, APIs und weitere Consumer mit konsistenten Definitionen versorgt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Gemeinsame Definitionen werden einmal implementiert, versioniert, getestet und verantwortet. Die Consumer-Tools können unterschiedliche Analyseerlebnisse bereitstellen und verwenden dennoch dieselbe governte faktische Grundlage.
    </figcaption>
</figure>

Die zentrale Schicht muss kein einzelnes physisches Produkt sein. Sie kann ein logischer Vertrag sein, der über folgende Komponenten umgesetzt wird:

- SQL-Tabellen und Views;
- Warehouse-Schemas;
- Lakehouse-Tabellen;
- governte Dateien;
- semantische Modelle;
- APIs;
- eine optionale QVD-Verteilungsschicht;
- eine Kombination dieser Komponenten.

Die architektonische Anforderung lautet, dass die maßgebliche Regel eindeutig identifizierbar, wiederverwendbar und testbar ist.

Ein geeigneter Datenproduktvertrag sollte Folgendes festhalten:

| Vertragselement | Beispiel |
| --- | --- |
| Produkt | `sales_consumption` |
| Granularität | Eine Zeile pro Auftragsposition |
| Owner | Sales Data Owner |
| Technischer Owner | Data-Platform-Team |
| Aktualisierung | Täglich vor 06:00 Uhr |
| Historie | Am Business-Datum gültige Kunden- und Produktversion |
| KPI-Grundlage | Veröffentlichtes Feld `net_revenue_amount` |
| Qualität | Pflichtprüfungen für Schlüssel, Währung, Datum und Status |
| Security | Regionsberechtigung als governte Zugriffsdaten |
| Consumer | Qlik, Power BI, Excel und freigegebene APIs |
| Änderungsrichtlinie | Versionierter Vertrag mit Kompatibilitätsprüfung |

Dieser Vertrag ist wichtiger als das physische Tool, mit dem er erzeugt wird.

## Optionale zentrale QVD-Schicht

Eine zentrale QVD-Schicht kann insbesondere in einer bestehenden Qlik-Landschaft eine gültige Umsetzungsoption sein.

Beispiel:

```flow linear vertical
Quellen
Zentrale SQL- oder governte Qlik-Transformation
Governte QVD-Datenprodukte
Schlanke Qlik-Anwendungen
```

Die QVD-Schicht kann folgende Vorteile bieten:

- effiziente Qlik-optimierte Verteilung;
- Entkopplung von der Verfügbarkeit der Quellen;
- Wiederverwendung über mehrere Qlik-Anwendungen;
- kontrollierte inkrementelle Ladevorgänge;
- einen Migrationsschritt weg von App-lokaler Quellenextraktion.

Sie sollte jedoch nicht automatisch zum Ort der gesamten unternehmensweiten fachlichen Wahrheit werden.

Eine governte QVD-Schicht ist geeignet, wenn:

- Qlik der dominante oder einzige Consumer ist;
- das Team die QVD-Erzeugung zentral betreiben und testen kann;
- Datenverträge, Ownership und Lineage explizit sind;
- Power BI und Excel keine ausschließlich in QVDs vorhandene Logik zurückentwickeln müssen;
- die Schicht Duplizierung reduziert, statt eine zusätzliche Kopie zu schaffen.

Wenn mehrere Consumer-Technologien dasselbe Produkt benötigen, ist eine plattformneutrale Tabelle, View oder API in der Regel der bessere führende Vertrag. QVDs können daraus weiterhin als Qlik-spezifisches Verteilungsformat erzeugt werden.

Die Entscheidung lautet deshalb nicht „QVD oder Warehouse“. Ein sinnvolles Muster kann so aussehen:

```text
Governte Warehouse-Modelle
        ↓
Optionaler governter QVD-Cache
        ↓
Schlanke Qlik-Apps
```

## Umsetzungsoptionen nach vorhandener Plattform

Das logische Prinzip bleibt über Plattformen hinweg identisch.

### Vorhandenes relationales Warehouse

Nutze Schemas, Views, Tabellen, Stored Procedures und den vorhandenen Scheduler. Ergänze persistente Qualitäts- und Kontrolltabellen. Das ist häufig die einfachste tragfähige Variante.

### Microsoft-orientierte Umgebung

Nutze die vorhandene Microsoft-Datenplattform, um governte Warehouse- oder Lakehouse-Tabellen und Consumer Views zu veröffentlichen. Ergänze separate semantische Measures nur dort, wo Power-BI-spezifischer Filterkontext erforderlich ist. Verschiebe gemeinsame Regeln nicht in jeden Bericht, nur weil die Plattform lokale Modellierung komfortabel macht.

### Snowflake-orientierte Umgebung

Veröffentliche governte Tabellen oder kontrollierte Consumption Views. Nutze zunächst SQL-Transformationen und die vorhandene Orchestrierung. Ergänze dbt, wenn modulare Modellabhängigkeiten, Tests, Dokumentation und Team-Deployment-Workflows ein reales Zusammenarbeitsproblem lösen.

### Databricks-orientierte Umgebung

Veröffentliche governte Delta- oder SQL-Consumption-Tabellen aus kontrollierten Transformationsschichten. Nutze Spark-spezifische Verarbeitung dort, wo Skalierung, Streaming oder Engineering-Anforderungen sie rechtfertigen. Eine kleine relationale KPI-Regel wird nicht automatisch besser, nur weil sie in verteiltem Code ausgeführt wird.

### Umgebung mit dbt

Nutze dbt als optionales Engineering-Framework für versionierte Modelle, Abhängigkeiten und Tests. dbt kann die Umsetzungsdisziplin verbessern, ist aber nicht selbst die fachliche Architektur. Vertrag, Ownership und Consumer-Grenzen müssen weiterhin gestaltet werden.

### Qlik-zentrierte Umgebung ohne Warehouse

Baue einen zentral betriebenen Qlik-Transformations- oder QVD-Erzeugungspfad als Zwischenarchitektur auf. Verschiebe Quellen-Joins, Bereinigung, Historisierung und gemeinsame KPI-Grundlagen aus einzelnen Apps. Dokumentiere den Pfad als governtes Datenprodukt und behalte nur Qlik-spezifische Logik in den konsumierenden Anwendungen.

### Datei-basierte oder kleine Umgebung

Ein kontrollierter Satz aus CSV-, Parquet- oder tabellenbasierten Ausgaben kann für einen kleinen Umfang ausreichen, sofern Erzeugung, Schema, Qualität, Ownership und Aktualisierung kontrolliert sind. Das Fehlen einer großen Plattform rechtfertigt keine unkontrollierten lokalen Definitionen.

## Notwendige Tool-spezifische Ausnahmen

Bestimmte Logik sollte im Consumer verbleiben, weil ihre Verlagerung Funktionalität entfernen, die Nutzbarkeit verschlechtern oder eine künstliche Abstraktion erzeugen würde.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start6-img4-de.png"
        alt="Entscheidungsleitfaden für notwendige Tool-spezifische Ausnahmen in Qlik, Power BI und Excel, darunter Qlik-Link-Tables, kanonische Datumsmodelle, Section Access und Set Analysis, Power-BI-DAX-Zeitintelligenz und Measures sowie Excel-Layouts, Kommentare und kontrollierte manuelle Eingaben"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Tool-spezifische Logik ist erlaubt, wenn das Tool der richtige Ausführungskontext ist. Jede Ausnahme bleibt dokumentiert, klar abgegrenzt und von der gemeinsamen fachlichen Definition getrennt.
    </figcaption>
</figure>

### Qlik-Ausnahmen

Gültige Qlik-spezifische Logik kann Folgendes umfassen:

- **Link Tables**, um mehrere Fakten und gemeinsame Dimensionen im assoziativen Modell zu verwalten;
- **kanonische Datumsmodelle**, um mehrere fachliche Datumsrollen über einen Kalender zu analysieren;
- **Section Access** für Reduktion auf Anwendungsebene;
- **gezielte Set Analysis** für selektionsabhängige Vergleiche;
- **Alternate States** für kontrollierte Vergleichsanalysen;
- **lokale Assoziationsbehandlung**, wenn das Consumption Model sie benötigt;
- **Performance-orientierte Feldaufbereitung**, wenn sie das Anwendungsverhalten wesentlich verbessert.

Diese Ausnahmen dürfen nicht als Begründung dienen, Quellenbereinigung und unternehmensweite KPI-Definitionen wieder in die App zu verschieben.

### Power-BI-Ausnahmen

Gültige Power-BI-spezifische Logik kann Folgendes umfassen:

- Measures im semantischen Modell;
- DAX-Zeitintelligenz;
- Calculation Groups;
- Row-Level Security, wenn sie durch das semantische Modell durchgesetzt wird;
- Display Folders und Modellmetadaten;
- Darstellungsberechnungen, die vom Filterkontext abhängen;
- What-if-Parameter für kontrollierte Szenarioanalysen.

Die zugrunde liegenden Fakten, Mappings, Stornoregeln und historischen Zuordnungen sollten außerhalb des Berichts wiederverwendbar bleiben.

### Excel-Ausnahmen

Gültige Excel-spezifische Logik kann Folgendes umfassen:

- Berichtslayout;
- Kommentare und Annotationen;
- kontrollierte Szenarioannahmen;
- manuell eingegebene Planwerte;
- Arbeitsmappen-spezifische Darstellungsformeln;
- lokale Pivot-Auswertungen und Diagramme;
- Abstimmungen als sichtbare temporäre Kontrolle.

Manuelle Eingaben sollten klar von governten Ist-Daten getrennt werden. Werden Eingaben operativ wichtig, benötigen sie Owner, Validierung, Versionierung und einen kontrollierten Rückführungspfad in die Plattform.

## Jede Ausnahme dokumentieren

Eine Ausnahme sollte in einem Architekturregister sichtbar sein und nicht in einem Skript oder einer Arbeitsmappe verborgen bleiben.

| Feld | Beispiel |
| --- | --- |
| Consumer | Qlik Sales Analysis |
| Ausnahme | Kanonische Datumsbrücke |
| Zweck | Auftrag, Lieferung und Rechnung über einen Kalender analysieren |
| Betroffene fachliche Definition | Keine; Datumsrollen bleiben zentral definiert |
| Owner | BI-Platform-Team |
| Test | Anzahl und Assoziationen nach jeder Modelländerung validieren |
| Wiederverwendung | Lokal für das assoziative Qlik-Modell |
| Prüftermin | Quartalsweise oder nach einer Modellneugestaltung |
| Bedingung zur Entfernung | Entfernen, wenn das Zielmodell keine Mehrfachdatums-Assoziation mehr benötigt |

Ein vergleichbarer Eintrag kann ein DAX-Measure oder eine kontrollierte Excel-Eingabe dokumentieren.

Das Register beantwortet fünf Fragen:

1. Warum existiert die Logik im Tool?
2. Welche gemeinsame Definition konsumiert sie?
3. Verändert sie fachliche Wahrheit oder nur den Analysekontext?
4. Wer verantwortet und testet sie?
5. Unter welcher Bedingung wird sie verschoben, wiederverwendet oder entfernt?

## Datenqualität muss außerhalb der Visualisierung bleiben

Ein Bericht kann Qualitätsergebnisse darstellen. Er sollte jedoch nicht der einzige Ort sein, an dem Qualität ausgewertet wird.

Persistente Qualitätsausführung sollte mindestens Folgendes erfassen:

```text
test_run_id
data_product
rule_id
severity
object_name
record_key
expected_value
actual_value
result_status
executed_at
owner
```

Für das Umsatzbeispiel können Regeln enthalten:

- Kennung der Auftragsposition ist vorhanden;
- Kundenschlüssel wurde aufgelöst;
- Produktschlüssel wurde aufgelöst;
- Business-Datum ist gültig;
- Wechselkurs ist verfügbar;
- Stornostatus ist bekannt;
- Nettoumsatz entspricht der freigegebenen Regel;
- Veröffentlichung ist vor dem vereinbarten Zeitpunkt vollständig.

Qlik und Power BI können Fehlerrate, Entwicklung und betroffene Datensätze visualisieren. Excel kann eine kontrollierte Bearbeitung unterstützen. Regelausführung und Ergebnishistorie bleiben unabhängig von allen drei Consumern.

## Zugriffslogik kann ohne Duplizierung geteilt werden

Security erstreckt sich häufig über Plattform und Consumer.

Ein sinnvolles Muster lautet:

```flow linear vertical
Identitäts- und Berechtigungsquelle
Governte Zugriffstabelle
Consumer-spezifische Durchsetzung
Auditierbare Zugriffstests
```

Die Plattform kann folgende Felder veröffentlichen:

```text
user_id
region_key
product_scope
valid_from
valid_to
access_status
```

Qlik kann diese Tabelle in Section Access verwenden. Power BI kann dieselbe Berechtigung in seinem Security Model einsetzen. Excel erhält ausschließlich eine freigegebene View oder einen kontrollierten Extrakt.

Die Berechtigungsregel ist zentral. Der Mechanismus zur Durchsetzung ist Tool-spezifisch.

## Typische Anti-Patterns

### Das Warehouse in jeder Qlik-App neu aufbauen

Quellen-Joins, Mappings, Historisierung und KPI-Berechnungen werden in mehrere Reload-Skripte kopiert. Jede App wird zu einer eigenen Warehouse-Implementierung.

### Eine gemeinsame Qlik-Include-Datei mit vollständiger Governance verwechseln

Eine Include-Datei reduziert kopierten Code. Sie liefert jedoch nicht automatisch Ownership, Testing, versionierte Datenverträge, Qualitätsevidenz oder plattformneutrale Wiederverwendung.

### Sämtliche Logik in eine riesige SQL-View verschieben

Zentralisierung allein schafft keine Wartbarkeit. Eine einzelne undurchsichtige View kann genauso schwer governbar werden wie ein großes Qlik-Skript. Verantwortlichkeiten müssen getrennt und Zwischenergebnisse bei Bedarf persistiert werden.

### Denselben KPI in DAX erneut implementieren

Jeder Power-BI-Bericht erzeugt seine eigene Variante von Nettoumsatz, Deckungsbeitrag oder aktivem Kunden. Ein gemeinsames semantisches Modell hilft. Wiederverwendbare faktische Regeln sollten bei Bedarf trotzdem außerhalb einer einzelnen Berichtstechnologie verfügbar sein.

### Excel als unsichtbare Korrekturschicht verwenden

Nutzer korrigieren Ländercodes, entfernen Stornos oder ergänzen fehlende Mappings manuell. Die Arbeitsmappe wird operativ kritisch, ohne Ownership oder Nachvollziehbarkeit zu besitzen.

### Jede Analyseberechnung in die Plattform zwingen

Wer jeden Vergleich, jedes Ranking und jeden selektionsabhängigen Ausdruck in Warehouse-Spalten verschiebt, erzeugt viele kontextspezifische Felder und reduziert die analytische Flexibilität.

### Jede Tool-spezifische Logik zur Ausnahme erklären

Eine Ausnahme wird durch den Ausführungskontext des Tools begründet. „Es war lokal schneller umzusetzen“ ist keine belastbare Architekturbegründung.

### Eine weitere zentrale Kopie erstellen, ohne lokale Logik stillzulegen

Ein neuer governter Mart wird veröffentlicht, alte Qlik-Skripte, Power-Query-Schritte und Excel-Formeln bleiben jedoch aktiv. Die Architektur gewinnt eine zusätzliche Schicht, ohne Inkonsistenz zu reduzieren.

### dbt, Fabric, Snowflake oder Databricks verpflichtend machen

Das Prinzip kann mit vorhandenem SQL, governten QVDs oder kontrollierten Dateien umgesetzt werden. Ein neues Produkt ist nur gerechtfertigt, wenn es ein identifiziertes Problem bei Lieferung, Skalierung, Zusammenarbeit oder Betrieb löst.

## Entscheidungshilfe

Verwende für jedes Stück Logik folgende Fragen:

| Frage | Wenn ja | Wenn nein |
| --- | --- | --- |
| Definiert es gemeinsame fachliche Bedeutung? | In den governten Datenpfad verschieben | Weiter prüfen |
| Müssen mehrere Consumer es identisch nutzen? | Einmal über einen wiederverwendbaren Vertrag veröffentlichen | Weiter prüfen |
| Integriert es Quellen, Schlüssel oder Historie? | Außerhalb einzelner BI-Apps halten | Weiter prüfen |
| Bestimmt es die Veröffentlichungsqualität? | Zentral ausführen und persistieren | Weiter prüfen |
| Existiert es wegen des assoziativen Qlik-Modells? | Dokumentierte Qlik-Ausnahme beibehalten | Weiter prüfen |
| Hängt es vom Power-BI-Filterkontext ab? | Dokumentiertes DAX-Measure oder semantische Modelllogik beibehalten | Weiter prüfen |
| Handelt es sich um Excel-Darstellung oder ein kontrolliertes lokales Szenario? | Mit klaren Grenzen in der Arbeitsmappe belassen | Weiter prüfen |
| Könnte das Ergebnis operativ wiederverwendet werden? | In Richtung einer governten Komponente verschieben | Lokale Umsetzung kann verbleiben |
| Ist die Logik heute dupliziert? | Einen Ziel-Owner festlegen und Consumer migrieren | Keine zweite Version erzeugen |
| Verbessert ein neues Tool die Lösung materiell? | Mit expliziter Verantwortung ergänzen | Vorhandene Fähigkeiten nutzen |

Eine kompakte Regel lautet:

```text
Gemeinsame Bedeutung       → zentral
Wiederverwendbare Logik    → zentral
Qualität und Historie      → zentral
Tool-Ausführungskontext    → lokal und dokumentiert
Darstellung                → lokal
Temporäre Exploration      → lokal, mit Pfad zur Überführung
```

## Wichtigste Empfehlungen

1. Behandle Qlik, Power BI und Excel als Consumer governten Datenprodukte und nicht als unabhängige Warehouses.
2. Verschiebe gemeinsame Bereinigung, Integration, Schlüsselauflösung, Historisierung, KPI-Grundlagen und Datenqualitätsregeln aus einzelnen BI-Artefakten.
3. Beginne mit einer duplizierten, fachlich wertvollen Regel, statt alle Anwendungen gleichzeitig neu zu gestalten.
4. Halte Qlik-Skripte so schlank wie praktisch möglich und behalte nur Verbindung, Laden, Assoziationen, Zugriff, Performance und begründete analytische Logik.
5. Nutze Set Analysis für Qlik-spezifischen Analysekontext und nicht zur erneuten Umsetzung gemeinsamer Storno-, Währungs- oder Historienregeln.
6. Nutze DAX für semantisches Modell- und Filterkontextverhalten und nicht als einzige Implementierung wiederverwendbarer fachlicher Wahrheit.
7. Verbinde Excel mit zertifizierten Views oder governten Modellen und trenne kontrollierte Annahmen von Ist-Daten.
8. Veröffentliche einen stabilen Consumer-Vertrag mit Granularität, Definitionen, Owner, Aktualisierung, Qualität, Security und Änderungsrichtlinie.
9. Persistiere Datenqualitätsergebnisse außerhalb von Berichten und definiere das Veröffentlichungsverhalten explizit.
10. Zentralisiere die Zugriffsberechtigung und verwende in jedem Consumer den passenden Mechanismus zur Durchsetzung.
11. Erlaube Tool-spezifische Ausnahmen nur, wenn der Ausführungskontext einen realen Mehrwert erzeugt.
12. Dokumentiere jede Ausnahme mit Zweck, Owner, betroffener Definition, Test und Bedingung zur Entfernung.
13. Nutze eine zentrale QVD-Schicht, wenn sie Qlik-Duplizierung reduziert, verwende QVD-only-Logik jedoch nicht als Standardvertrag für Consumer außerhalb von Qlik.
14. Nutze vorhandene relationale Datenbanken, Scheduler und BI-Landschaften, bevor eine weitere Plattform ergänzt wird.
15. Behandle Fabric, Snowflake, Databricks und dbt als Umsetzungsoptionen und nicht als Voraussetzungen.
16. Entferne alte lokale Definitionen, nachdem der governte Ersatz validiert wurde.
17. Messe Erfolg an weniger widersprüchlichen Definitionen, schlankeren Consumer-Artefakten, schnelleren Änderungen und besserer Nachvollziehbarkeit.

## Übergang zum nächsten Part

Sobald fachliche Logik außerhalb einzelner BI-Anwendungen definiert ist, kann dasselbe governte Datenprodukt mehrere Consumer bedienen, ohne sie auf eine gemeinsame Oberfläche festzulegen.

Der nächste Part, [Ein Datenprodukt, mehrere Consumer](/playbooks/one-data-product-multiple-consumers), zeigt, wie ein governter Vertrag Qlik, Power BI, Excel und weitere Bereitstellungskanäle unterstützen kann und gleichzeitig die jeweiligen Consumer-Stärken erhält.
