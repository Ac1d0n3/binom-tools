---
title: Ein Datenprodukt, mehrere Consumer
description: Wie ein governtes Datenprodukt Qlik, Power BI, Excel, APIs und KI zuverlässig über stabile Nutzungsverträge, gemeinsame fachliche Wahrheit und bewusst Consumer-spezifische Semantik- und Darstellungsschichten versorgt.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - data-products
  - consumption-contract
  - data-contracts
  - semantic-model
  - semantic-layer
  - qlik-sense
  - set-analysis
  - power-bi
  - dax
  - excel
  - power-query
  - api
  - ai
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
seriesPart: 7
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start7-hero.png
---

## Eine fachliche Wahrheit darf nicht an ein einziges Consumer-Tool gebunden sein

Ein governtes Datenprodukt ist nur dann wertvoll, wenn es tatsächlich genutzt werden kann.

Dieselben Vertriebsdaten können für sehr unterschiedliche Ergebnisse benötigt werden:

- Qlik-Nutzer explorieren Kunden-, Produkt-, Regions- und Zeitbeziehungen interaktiv;
- das Management erhält ein kontrolliertes Power-BI-Dashboard;
- der Vertriebsinnendienst arbeitet jeden Morgen mit einer aktualisierbaren Excel-Liste;
- eine operative Anwendung fragt aktuelle Vertriebsinformationen über eine API ab;
- ein KI-Assistent verwendet freigegebene Fakten und Definitionen für eine Management-Zusammenfassung.

Diese Consumer benötigen nicht dieselbe Schnittstelle, dieselbe Modellform oder dasselbe Interaktionsmuster. Sie benötigen jedoch dieselbe zugrunde liegende fachliche Wahrheit.

Ohne eine explizite Consumption-Architektur entwickelt jedes Tool schrittweise eine eigene Interpretation:

```text
Qlik-App             Baut Kunden- und Umsatzlogik im Ladeskript erneut auf
Power-BI-Modell      Wiederholt Status-Mappings und KPI-Regeln in Power Query und DAX
Excel-Arbeitsmappe   Ergänzt lokale Korrekturen, Formeln und manuelle Kategorien
API-Service          Implementiert eine eigene Abfrage und Feldbenennung
KI-Kontextstrecke    Extrahiert eine undokumentierte Teilmenge ohne Qualität und Ownership
```

Das Unternehmen besitzt anschließend fünf Implementierungen statt fünf Nutzungswege.

Das Problem ist nicht die Anzahl der Tools. Qlik, Power BI, Excel, APIs und KI lösen unterschiedliche Aufgaben und können jeweils angemessen sein. Problematisch wird es, wenn jeder Consumer zu einer eigenständigen Quelle fachlicher Bedeutung wird.

Part 6, [Fachliche Logik außerhalb der BI-Apps halten](/playbooks/keeping-business-logic-outside-bi-apps), hat das Prinzip etabliert, dass gemeinsame Bereinigung, Integration, Historisierung, KPI-Grundlagen und Qualitätsregeln außerhalb einzelner BI-Artefakte liegen sollten. Dieser Part erweitert dieses Prinzip auf die gesamte Consumption-Schicht.

> **Baue einen governten faktischen Kern. Veröffentliche ihn über stabile Verträge. Erlaube jedem Consumer nur die Semantik und Interaktion zu ergänzen, die für seinen Zweck spezifisch ist.**

## Architekturprinzip: ein governierter Kern, mehrere Nutzungswege

Ein Datenprodukt sollte als governte fachliche Fähigkeit verstanden werden und nicht als eine Tabelle für einen Bericht.

Das Produkt verantwortet die wiederverwendbaren Elemente, die jeder Consumer konsistent interpretieren muss:

```text
Fachlicher Umfang
Granularität
Business Keys
Gemeinsame Dimensionen
Freigegebene faktische Beträge
KPI-Grunddefinitionen
Historische Interpretation
Qualitätsstatus
Sicherheitsattribute
Aktualisierungsverhalten
Ownership
Version und Änderungsrichtlinie
```

Consumer erhalten diesen Kern über Schnittstellen, die zu ihren technischen und operativen Anforderungen passen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start7-img1-de.png"
        alt="Ein governtes Datenprodukt, das Daten aus mehreren Quellen erhält, Ingestion, Transformation, Zertifizierung, Governance und Qualität anwendet und anschließend Qlik Sense, Power BI, Excel, APIs und KI über getrennte verlässliche Nutzungswege versorgt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein governierter Kern kann mehrere Consumer unterstützen, ohne sie auf ein Tool oder eine physische Schnittstelle festzulegen. Die gemeinsame fachliche Wahrheit bleibt stabil, während jeder Nutzungsweg für seinen eigenen Zweck optimiert wird.
    </figcaption>
</figure>

Mögliche Nutzungswege sind:

| Consumer | Geeignete Schnittstelle | Consumer-spezifische Verantwortung |
| --- | --- | --- |
| Qlik | Kuratierte Tabellen, SQL Views, governte Dateien oder QVD-Bereitstellung | Assoziatives Modell, Selektionen, Master Measures, Set Analysis, Qlik-spezifische Zugriffsdurchsetzung |
| Power BI | Warehouse- oder Lakehouse-Tabellen, SQL Endpoint, Importmodell oder freigegebenes semantisches Modell | Beziehungen, DAX-Measures, Filterkontext, Berichtsnavigation und Microsoft-orientierte Verteilung |
| Excel | Zertifizierte SQL View, kontrollierte Datei, Power-Query-Verbindung oder freigegebenes semantisches Modell | Pivot-Layout, lokale Darstellung, kontrollierte Szenarien, Kommentare und operative Listen |
| API | Versionierter Endpoint oder Service View | Request-Form, Pagination, Antwortstatus, Serviceverhalten und Anwendungsintegration |
| KI | Freigegebene Context View, Retrieval-Index oder governte API | Prompt-Kontext, Retrieval-Strategie, Quellenhinweise, Zusammenfassung und modellspezifische Kontrollen |

Diese Architektur setzt nicht voraus, dass ein einziges physisches semantisches Modell jedes Tool versorgt. Ein assoziatives Qlik-Modell und ein semantisches Power-BI-Modell dürfen unterschiedlich sein, weil sich ihre Engines und Interaktionsmuster unterscheiden. Entscheidend ist, dass beide Modelle aus demselben governten faktischen Vertrag abgeleitet werden.

## Gemeinsame Daten bedeuten nicht identische Consumer-Modelle

Der Begriff **Single Source of Truth** wird häufig als „ein Datenbankobjekt, das jedes Tool direkt verwenden muss“ interpretiert. Das kann unnötig einschränkend werden.

Eine praktikable Architektur trennt drei Konzepte:

1. **Autoritative fachliche Wahrheit** — die governte faktische und dimensionale Grundlage.
2. **Nutzungsvertrag** — die stabile Schnittstelle und die Serviceerwartungen für eine Consumer-Klasse.
3. **Consumer-Modell** — die Tool-spezifische Darstellung für Analyse oder operative Nutzung.

Für ein Sales-Datenprodukt kann die gemeinsame Grundlage folgende Felder veröffentlichen:

```text
business_date
order_id
order_line_id
customer_key
product_key
sales_region_key
quantity
net_revenue_amount
reporting_currency
quality_status
publication_timestamp
```

Qlik kann diese Felder in ein assoziatives Modell mit kanonischem Kalender und dokumentierter Link Table laden. Power BI kann ein Sternschema mit Datumsdimension und DAX-Zeitintelligenz verwenden. Excel kann eine denormalisierte Daily-Sales-View erhalten. Eine API kann nur die für einen operativen Prozess erforderlichen Felder ausgeben. KI kann eine eingeschränkte Context View mit freigegebenen Beschreibungen und Quellenhinweisen erhalten.

Das sind unterschiedliche Modelle desselben Produkts und keine unterschiedlichen Definitionen von Sales.

Eine hilfreiche Governance-Frage lautet:

> **Würde eine Änderung die fachliche Antwort für mehrere Consumer verändern?**

Wenn ja, gehört die Änderung in das governte Datenprodukt oder seinen gemeinsamen Vertrag. Verändert sie ausschließlich Filterung, Darstellung, Navigation oder Interaktion eines einzelnen Tools bei unveränderten Fakten, darf sie im Consumer-Modell liegen.

## Die einfachste sinnvolle Umsetzung

Mehrere Consumer zu unterstützen, erfordert weder Fabric noch Snowflake, Databricks, dbt oder ein neues Semantic-Layer-Produkt.

Ein kleineres Unternehmen kann mit vorhandenen Mitteln beginnen:

```flow linear vertical
Vorhandene Quellextrakte
Vorhandene SQL-Datenbank oder governte Dateischicht
Eine zertifizierte Sales View
Persistenter Qualitäts- und Aktualisierungsstatus
Verbindungen für Qlik / Power BI / Excel
Optionale API oder kontrollierter Kontextexport
```

Eine minimale relationale Umsetzung kann folgende Objekte enthalten:

```text
core.fact_sales
core.dim_customer
core.dim_product
core.dim_date
mart.sales_analysis
mart.sales_daily_excel
service.sales_api_v1
quality.sales_rule_result
control.data_product_publication
```

Die Objekte müssen am ersten Tag nicht physisch getrennt sein. Die Namen verdeutlichen unterschiedliche Verantwortlichkeiten.

Eine einfache Analyse-View kann so aussehen:

```sql
create view mart.sales_analysis as
select
    s.business_date,
    s.order_id,
    s.order_line_id,
    s.customer_key,
    c.customer_name,
    c.country_code,
    s.product_key,
    p.product_group,
    s.sales_region_key,
    s.quantity,
    s.net_revenue_amount,
    s.reporting_currency,
    s.quality_status,
    s.publication_timestamp
from core.fact_sales s
join core.dim_customer c
  on s.customer_key = c.customer_key
join core.dim_product p
  on s.product_key = p.product_key
where s.publication_status = 'PUBLISHED';
```

Die View ist nur dann sinnvoll, wenn die vorgelagerten Definitionen kontrolliert sind:

- `net_revenue_amount` folgt den freigegebenen Storno- und Währungsregeln;
- Kunden- und Produktschlüssel lösen die korrekten historischen Versionen auf;
- abgewiesene Datensätze werden nicht ohne Nachweis stillschweigend entfernt;
- `publication_status` berücksichtigt vereinbarte Qualitäts-Gates;
- Owner und Aktualisierungserwartung sind bekannt.

Qlik und Power BI können eine dimensionale Variante des Produkts verwenden. Excel erhält möglicherweise eine schmalere denormalisierte View. Eine API kann eine Service-spezifische View mit stabilen Feldnamen nutzen. Das Unternehmen besitzt weiterhin eine fachliche Definition.

## Excel ist ein Consumer und kein Schatten-Data-Warehouse

Excel wird häufig als Problem behandelt, weil Tabellenkalkulationen duplizierte Daten, versteckte Formeln und unkontrollierte Kopien enthalten können. Diese Diagnose ist unvollständig.

Excel ist oft die effizienteste Oberfläche für:

- tägliche operative Listen;
- Ad-hoc-Analysen;
- PivotTables;
- Planung und Budgetierung;
- Was-wäre-wenn-Szenarien;
- Kommentare und Review-Workflows;
- kontrollierten Datenaustausch mit Fachanwendern.

Der Architekturfehler besteht nicht in der Nutzung von Excel. Er entsteht, wenn Anwender fehlende Datenprodukt-Fähigkeiten innerhalb von Excel neu aufbauen müssen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start7-img2-de.png"
        alt="Vergleich zwischen unkontrolliertem Excel-Schatten-Data-Warehouse mit manuellen Exporten, mehreren Dateiversionen und versteckter Logik und einem governten Muster, bei dem Excel ein validiertes und dokumentiertes Datenprodukt konsumiert"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Excel erzeugt Risiko, wenn es zum einzigen Ort für Datenkorrektur, Verknüpfung und Interpretation wird. Es schafft Mehrwert, wenn es einen governten Vertrag erhält und für Analyse, Planung, Darstellung und kontrollierte lokale Arbeit genutzt wird.
    </figcaption>
</figure>

### Das Schatten-Data-Warehouse-Muster

Ein typischer unkontrollierter Prozess sieht so aus:

```flow linear vertical
Manueller CSV-Export
Lokale Nachschlageformeln
Versteckte Korrekturspalten
Mehrere kopierte Arbeitsmappen
Versand per E-Mail
Inoffizieller KPI wird für Entscheidungen verwendet
```

Die Arbeitsmappe verantwortet nun fachliche Logik, die gemeinsam bereitgestellt werden müsste:

```text
Status 95 wird als storniert interpretiert
Fehlendes Land wird durch Deutschland ersetzt
Negative Beträge werden auf null gesetzt
Kundenduplikate werden manuell entfernt
Wechselkurs wird aus einer anderen Datei kopiert
```

Keine dieser Regeln ist automatisch falsch. Problematisch ist, dass sie lokal, schwach getestet, schwer auffindbar und für andere Consumer nicht verfügbar sind.

### Das governte Excel-Muster

Ein kontrollierter Pfad kann wesentlich einfacher sein:

```flow linear vertical
Zertifizierte SQL View oder freigegebenes semantisches Modell
Aktualisierbare Excel-Abfrage oder PivotTable
Geschützte Struktur und dokumentierte Berechnungen
Klar getrennte lokale Annahmen
Kontrollierte Weitergabe oder Ausgabe
```

Sofern Microsoft-Umgebung, Tenant-Konfiguration und Lizenzierung es unterstützen, kann Excel ein freigegebenes semantisches Power-BI-Modell konsumieren. In einer einfacheren oder nicht auf Microsoft ausgerichteten Umgebung kann Power Query eine zertifizierte SQL View verwenden. Alternativ veröffentlicht die Plattform eine kontrollierte Datei mit Version, Aktualisierungszeitpunkt und Qualitätsstatus.

Eine governte Excel-Arbeitsmappe sollte die Grenze explizit machen:

| Inhalt | Bevorzugter Owner |
| --- | --- |
| Tatsächlicher Umsatzbetrag | Governtes Datenprodukt |
| Storno- und Währungsregeln | Governtes Datenprodukt |
| Kunden- und Produktzuordnung | Governtes Datenprodukt |
| Aktualisierungszeitpunkt und Qualitätsstatus | Governtes Datenprodukt |
| Pivot-Layout und Formatierung | Excel-Arbeitsmappe |
| Benutzerkommentare | Excel-Arbeitsmappe oder kontrollierter Kollaborationsprozess |
| Planungsannahmen | Excel oder Planungsprozess, klar von Ist-Daten getrennt |
| Write-back | Kontrollierter Service oder governter Importprozess |

Die Arbeitsmappe kann weiterhin operativ wichtig sein. Deshalb benötigt auch sie einen Owner, einen definierten Zweck, eine unterstützte Datenquelle sowie einen Änderungs- und Ablöseprozess.

## Datenprodukt, semantisches Modell und Visualisierung sind unterschiedliche Schichten

Ein wiederkehrender Designfehler besteht darin, Datenprodukt, semantisches Modell und Bericht als ein Objekt zu behandeln.

Sie lösen unterschiedliche Aufgaben.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start7-img3-de.png"
        alt="Dreistufige Architektur, die ein governtes Datenprodukt, ein semantisches Modell mit fachlichen Beziehungen und Measures sowie eine zweckgerechte Visualisierung und Nutzung in Qlik Sense, Power BI, Excel, APIs und KI trennt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Datenprodukt liefert governte Fakten und Dimensionen. Semantische Modelle übersetzen diese Fakten in die Tool-spezifische Analysesprache. Visualisierungen und Schnittstellen ermöglichen anschließend eine zur jeweiligen Rolle und zum Prozess passende Interaktion.
    </figcaption>
</figure>

### Schicht 1: governtes Datenprodukt

Das Datenprodukt verantwortet dauerhafte fachliche Bedeutung:

- integrierte Fakten und Dimensionen;
- freigegebene Granularität und Schlüssel;
- historische Interpretation;
- wiederverwendbare Basis-Measures und Beträge;
- Datenqualitätsregeln und Ergebnisstatus;
- Sicherheitsattribute;
- Ownership, Aktualisierung und Serviceerwartungen;
- versioniertes Schema und Änderungsrichtlinie.

Diese Schicht sollte unabhängig von einer einzelnen Berichtstechnologie nutzbar bleiben.

### Schicht 2: Consumer-spezifisches semantisches Modell

Das semantische Modell übersetzt governte Daten in die Sprache einer bestimmten Analyse-Engine.

Für Qlik kann dies umfassen:

- assoziative Beziehungen;
- eine Link Table oder kanonische Datumsstrukturen, sofern begründet;
- Master Dimensions und Master Measures;
- Section Access mit governten Berechtigungsdaten;
- Set Analysis für selektionsabhängige Vergleiche.

Für Power BI kann dies umfassen:

- Sternschema-Beziehungen;
- Calculation Groups oder wiederverwendbare DAX-Measures, sofern sinnvoll;
- Row-Level Security auf Basis governter Berechtigungen;
- Anzeigeordner, Hierarchien und fachlich verständliche Namen;
- Zeitintelligenz-Measures im Filterkontext des Modells.

Für Excel kann die semantische Schicht leichter ausfallen:

- eine zertifizierte denormalisierte View;
- ein freigegebenes semantisches Modell für PivotTables;
- eine kontrollierte Power-Query-Transformation, die ausschließlich die Ausgabe formt;
- benannte Tabellen und dokumentierte lokale Berechnungen.

Ein semantisches Modell darf die zentrale faktische Regel nicht stillschweigend neu definieren. Es ergänzt analytischen Kontext.

### Schicht 3: Visualisierung und Interaktion

Die letzte Schicht beantwortet Fragen wie:

- Welche Selektionen und Explorationspfade soll Qlik bereitstellen?
- Welche Management-Narrative und Drill-Pfade gehören in Power BI?
- Welche operativen Spalten, Kommentare und Filter benötigt Excel?
- Welche Felder und Statuscodes soll eine API zurückgeben?
- Welche Fakten, Definitionen und Quellenhinweise darf ein KI-Assistent verwenden?

Eine Visualisierung ist bewusst zweckgebunden. Wiederverwendung bedeutet nicht, dass jeder Nutzer dasselbe Dashboard sehen muss.

## Konkretes Beispiel: ein Sales-Datenprodukt, fünf Consumer

Angenommen, das Unternehmen veröffentlicht ein governtes Sales-Datenprodukt mit folgendem Vertrag:

| Element | Definition |
| --- | --- |
| Granularität | Eine Zeile je Auftragsposition und Business-Datum |
| Zentraler faktischer Betrag | `net_revenue_amount` in Berichtswährung |
| Stornoregel | Stornierte Auftragspositionen tragen null zum Nettoumsatz bei |
| Kundenhistorie | Kundenattribute werden so aufgelöst, wie sie am Business-Datum gültig waren |
| Produkthistorie | Produkthierarchie wird so aufgelöst, wie sie am Business-Datum gültig war |
| Aktualisierung | Tägliche Veröffentlichung vor 06:00 Uhr |
| Qualitäts-Gate | Pflichtregeln für Auftragsposition, Kunde, Produkt, Datum, Währung und Status |
| Sicherheit | Attribute für Regions- und Business-Unit-Berechtigungen sind enthalten |
| Owner | Sales Data Owner |
| Version | `sales-consumption-v1` |

### Qlik: explorative Analyse

Qlik erhält die governten Fakten und Dimensionen und baut ein assoziatives Consumption-Modell.

Das Basis-Measure bleibt einfach:

```qlik
Sum([Net Revenue Amount])
```

Ein Tool-spezifischer Vergleich kann Set Analysis verwenden:

```qlik
Sum({
    <BusinessYear = {"$(=Max(BusinessYear)-1)"}>
} [Net Revenue Amount])
```

Qlik ergänzt assoziative Exploration, Selektionen und Vergleichsverhalten. Stornierung, Währungsumrechnung oder historische Kundenzuordnung werden nicht erneut berechnet.

### Power BI: Management-Dashboard

Power BI verwendet denselben faktischen Betrag in einem semantischen Modell:

```DAX
Net Revenue :=
SUM ( Sales[NetRevenueAmount] )
```

Ein modellspezifischer Vergleich kann in DAX definiert werden:

```DAX
Net Revenue Previous Year :=
CALCULATE (
    [Net Revenue],
    DATEADD ( 'Date'[Date], -1, YEAR )
)
```

Power BI ergänzt Berichtsstruktur, Filterkontext, Management-Navigation und Microsoft-orientierte Verteilung. Es entsteht keine separate Umsatzdefinition.

### Excel: tägliche Vertriebsliste

Excel erhält eine für die operative Nutzung entworfene View:

```text
business_date
order_number
customer_number
customer_name
sales_region
product_number
product_description
quantity
net_revenue_amount
quality_status
publication_timestamp
```

Die Arbeitsmappe kann bereitstellen:

- Filter und Sortierung;
- PivotTables;
- Kommentare;
- lokalen Nachverfolgungsstatus;
- klar getrennte Planungs- oder Forecast-Spalten.

Die Excel-spezifische View darf aus Gründen der Nutzbarkeit denormalisiert sein. Sie bleibt über dieselben Produktschlüssel und Definitionen nachvollziehbar.

### API: operative Integration

Eine Anwendung benötigt möglicherweise einen stabilen Endpoint wie:

```text
GET /api/v1/sales/orders/{orderId}
```

Der API-Vertrag kann folgende Antwort enthalten:

```json
{
  "orderId": "4711",
  "businessDate": "2026-07-18",
  "customerId": "C-10042",
  "currency": "EUR",
  "netRevenue": 1250.00,
  "qualityStatus": "PASSED",
  "dataProductVersion": "sales-consumption-v1"
}
```

Die API ergänzt Service-spezifisches Verhalten wie Antwortcodes, Pagination, Request Limits und Endpoint-Versionierung. Der faktische Betrag stammt weiterhin aus dem governten Sales-Produkt.

### KI: kontrollierte Management-Zusammenfassung

Ein KI-Assistent sollte keinen beliebigen Datenbank-Dump erhalten. Er verwendet einen freigegebenen Kontextvertrag, der beispielsweise folgende Inhalte enthält:

- aggregierte Sales-Fakten;
- freigegebene KPI-Namen und Beschreibungen;
- Perioden- und Währungskontext;
- Datenqualitäts- und Aktualisierungsstatus;
- zulässige Dimensionen;
- Quellkennungen oder Links;
- zugriffsgefilterte Datensätze.

Das Modell darf Fakten zusammenfassen oder erklären. Es darf keine neue Definition von Nettoumsatz erfinden und die Zugriffsregeln des Produkts nicht umgehen.

Ein geeigneter KI-Antwortkontext kann so aussehen:

```text
Kennzahl: Nettoumsatz
Periode: 01.07.2026 bis 18.07.2026
Wert: 12,4 Millionen EUR
Vorjahresvergleich: +4,2 %
Qualitätsstatus: Bestanden, 0,3 % der Datensätze isoliert
Quellprodukt: sales-consumption-v1
Veröffentlichungszeitpunkt: 19.07.2026 05:42 CET
```

Der gemeinsame Kern macht die KI-Antwort reproduzierbarer, weil faktische Eingaben, Definitionen und Veröffentlichungsstatus explizit sind.

## Der Nutzungsvertrag

Ein Consumer benötigt mehr als einen Tabellennamen. Er braucht eine verlässliche Vereinbarung darüber, was das Produkt bereitstellt und wie es sich verhält.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start7-img4-de.png"
        alt="Ein Nutzungsvertrag zwischen dem Anbieter eines governten Datenprodukts und seinen Consumern, der bereitgestellte Entitäten und Measures, Aktualisierung und Verfügbarkeit, Qualität, Berechtigungen, Schnittstellen, Versionierung, Abkündigung und Kommunikation umfasst"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Nutzungsvertrag macht aus einem internen Datenobjekt ein verlässliches Produkt. Er beschreibt, was bereitgestellt wird, wie es sich verhält, welche Qualität erwartet werden kann, wie der Zugriff funktioniert und wie Änderungen eingeführt werden.
    </figcaption>
</figure>

Ein sinnvoller Vertrag enthält mindestens folgende Elemente:

| Vertragselement | Beispiel für das Sales-Produkt |
| --- | --- |
| Produktname | `sales-consumption` |
| Fachlicher Zweck | Vertrauenswürdige Vertriebsanalyse und operativer Zugriff auf Sales-Daten |
| Granularität | Eine Zeile je Auftragsposition und Business-Datum |
| Entitäten | Auftragsposition, Kunde, Produkt, Datum, Region |
| Felder | Benanntes Schema mit Datentyp, Bedeutung, Nullfähigkeit und Klassifikation |
| KPI-Grundlage | Freigegebene Werte `net_revenue_amount` und `quantity` |
| Historische Regel | Kunden- und Produktversion gültig am Business-Datum |
| Aktualisierung | Täglich vor 06:00 Uhr CET |
| Aktualitätsfeld | `publication_timestamp` |
| Qualitätsregeln | Pflichtschlüssel, zulässiger Status, gültige Währung und Abstimmung |
| Fehlerverhalten | Abweisen, isolieren, mit Warnung veröffentlichen oder Veröffentlichung stoppen |
| Sicherheit | Regions- und Business-Unit-Zugriff aus governten Berechtigungen |
| Schnittstellen | SQL View, dimensionale Tabellen, Excel View, API v1, freigegebener KI-Kontext |
| Owner | Sales Data Owner |
| Technischer Owner | Data-Platform-Team |
| SLA oder SLO | Zur Nutzung passende Verfügbarkeits- und Veröffentlichungserwartung |
| Version | `v1` mit Kompatibilitätsregeln |
| Abkündigung | Vorlaufzeit, Migrationsleitfaden und Abschaltdatum |
| Support | Kontakt, Incident-Pfad und bekannte Einschränkungen |

Der Vertrag kann in Markdown, YAML, einem Katalog, einer Datenbanktabelle oder einem dedizierten Datenprodukt-Tool gespeichert werden. Das Format ist zweitrangig. Die Vereinbarung muss sichtbar, versioniert und operativ durchgesetzt sein.

### Schemastabilität allein reicht nicht aus

Eine Tabelle kann dieselben Spalten behalten und trotzdem ihre Bedeutung verändern.

Beispiele:

- Der Stornostatus wechselt von einem Codesatz zu einem anderen.
- Das Business-Datum ändert sich vom Auftragsdatum zum Buchungsdatum.
- Die Währungsumrechnung wechselt von täglichen auf monatliche Kurse.
- Das Kundenland wird nicht mehr historisch zum Transaktionszeitpunkt, sondern aus aktuellen Stammdaten ermittelt.
- Qualitätsfehler werden stillschweigend ausgeschlossen statt markiert.

Das sind semantische Vertragsänderungen, auch wenn keine Spalte ergänzt oder entfernt wird.

Eine Kompatibilitätsbewertung muss deshalb Folgendes berücksichtigen:

```text
Schema
Bedeutung
Granularität
Historie
Timing
Qualitätsverhalten
Sicherheitsverhalten
Operative Verfügbarkeit
```

## Consumer-Verträge dürfen sich unterscheiden, ohne die Wahrheit zu fragmentieren

Ein Sales-Datenprodukt kann mehrere legitime Verträge veröffentlichen:

```text
sales_analytics_v1      Dimensionales Modell für Qlik und Power BI
sales_daily_excel_v1    Denormalisierte operative Liste für Excel
sales_api_v1            Schmaler Servicevertrag für Anwendungen
sales_ai_context_v1     Zugriffsgefilterter Kontext mit Definitionen und Quellenhinweisen
```

Das erzeugt nicht zwingend fachliche Duplikation. Die Verträge können aus demselben governten Kern generiert und gegen dieselben Regeln validiert werden.

Die Gefahr beginnt, sobald jeder Vertrag eine unabhängige fachliche Bedeutung entwickelt.

Eine hilfreiche Kontrolle besteht darin, gemeinsame und bewusst spezifische Bestandteile zu kennzeichnen:

| Bereich | Gemeinsam für Consumer | Bewusst Consumer-spezifisch |
| --- | --- | --- |
| Umsatzregel | Ja | Nein |
| Kundenhistorie | Ja | Nein |
| Qualitätsstatus | Ja | Darstellung kann variieren |
| Zugriffsberechtigung | Ja | Durchsetzungsmechanismus kann variieren |
| Feldbenennung | Kanonische Definition vorhanden | API- oder Tool-Aliase dürfen abweichen |
| Modellform | Gemeinsame Fakten und Schlüssel | Sternschema, assoziatives Modell oder flache Liste |
| Analytische Berechnungen | Basisbeträge gemeinsam | Set Analysis, DAX und Arbeitsmappenberechnungen dürfen variieren |
| Interaktion | Nein | Qlik-Selektionen, Power-BI-Navigation, Excel-Layout, API-Requests, KI-Prompts |

## Alternative Umsetzungen nach vorhandener Plattform

Das Architekturprinzip bleibt über Plattformen hinweg gleich. Die einfachste Umsetzung verwendet zunächst die Fähigkeiten, die bereits zuverlässig betrieben werden.

### Bestehendes relationales Warehouse

Nutze:

- SQL-Tabellen und Views für Fakten, Dimensionen und Consumer-Verträge;
- Datenbankrollen oder gefilterte Views für Zugriff;
- geplante Prozeduren oder vorhandenes ETL für die Veröffentlichung;
- persistente Qualitäts- und Control-Tabellen;
- direkte Verbindungen für Qlik, Power BI und Excel, sofern geeignet.

Das reicht häufig aus.

### Bestehende Qlik-orientierte Umgebung

Eine governte QVD-Schicht kann Qlik effizient temporär oder dauerhaft versorgen. Wenn mehrere Consumer ein erklärtes Ziel sind, sollte die autoritative Logik zusätzlich über einen plattformneutralen Pfad wie SQL-Tabellen, governte Dateien oder eine API verfügbar sein.

Power BI, Excel, APIs und KI sollten kein ausschließlich für Qlik optimiertes Modell rückwärts analysieren müssen, wenn breite Wiederverwendung beabsichtigt ist.

### Bestehende Microsoft-Fabric-Umgebung

Fabric kann Warehouse- oder Lakehouse-Daten und semantische Power-BI-Modelle bereitstellen. Excel kann freigegebene Microsoft-Semantikmodelle konsumieren, sofern Konfiguration und Lizenzierung der Organisation dies zulassen. Qlik und andere Consumer verwenden geeignete SQL-, Datei- oder Service-Schnittstellen.

Die Microsoft-Integration kann den Betriebsaufwand senken. Sie ersetzt jedoch nicht die explizite Definition von Granularität, Qualität, Ownership und Änderungsverträgen.

### Bestehende Snowflake-Umgebung

Snowflake-Tabellen, Views, Secure Views und Service-Schnittstellen können dieselben logischen Verträge umsetzen. dbt kann modulare SQL-Entwicklung, Tests, Dokumentation und Model Contracts ergänzen, wenn diese Fähigkeiten ein konkretes Teamproblem lösen.

Weder Snowflake noch dbt sind Voraussetzung für das Architekturprinzip.

### Bestehende Databricks-Umgebung

Delta-Tabellen, SQL Views, governte Katalogobjekte oder Sharing-Schnittstellen können die Verträge veröffentlichen. Verteilte Verarbeitung und Sharing-Fähigkeiten werden dort eingesetzt, wo Workload, Skalierung oder plattformübergreifende Verteilung sie rechtfertigen.

Für ein kleines relationales Reporting-Problem sollte keine Spark-orientierte Komplexität eingeführt werden, wenn die vorhandene Datenbank es bereits zuverlässig löst.

### On-Premises oder Hybrid

Nutze vorhandene SQL-Datenbanken, governte Dateien, QVDs, geplante Exporte und APIs. Ein hybrider Vertrag kann sensible Verarbeitung On-Premises halten und nur freigegebene Aggregate oder Service-Ausgaben an Cloud-Consumer veröffentlichen.

Der Cloud-Standort entscheidet nicht darüber, ob ein Produkt governt ist. Entscheidend sind Ownership, Definitionen, Kontrollen und Änderungsverhalten.

## Typische Anti-Patterns

### Ein Datenprodukt je Bericht bauen

Jedes Dashboard erhält einen separaten Extrakt und ein lokales Modell. Wiederverwendung bleibt gering, und jeder neue Consumer wiederholt dieselbe Integrationsarbeit.

### Jeden Consumer zu derselben physischen Tabelle zwingen

Ein einziges Objekt wird breit, mehrdeutig und schwer zu optimieren. Qlik, Power BI, Excel und APIs dürfen legitimerweise unterschiedliche Formen benötigen.

### Ein semantisches Power-BI-Modell als einzige Unternehmenswahrheit behandeln

Ein semantisches Modell kann eine wertvolle governte Consumption-Schicht sein. APIs, Qlik und nicht auf Microsoft ausgerichtete Workloads benötigen möglicherweise weiterhin plattformneutrale Fakten und Definitionen.

### QVD-Verteilung automatisch als plattformneutral ansehen

QVDs sind für Qlik-Workloads effizient. Sie sind nicht automatisch der beste Vertrag für Power BI, Excel, APIs oder KI.

### Excel verbieten, statt es zu governieren

Anwender erstellen weiterhin inoffizielle Exporte, weil die Plattform keinen nutzbaren operativen Vertrag anbietet. Der Schattenprozess wird weniger sichtbar, aber nicht weniger relevant.

### Raw-Tabellen veröffentlichen und sie Datenprodukt nennen

Raw-Zugriff ohne Granularität, fachliche Bedeutung, Qualität, Owner, Sicherheit und Änderungsrichtlinie ist eine technische Schnittstelle und kein verlässliches Produkt.

### Denselben KPI in Qlik, DAX, Excel und API-Code kopieren

Das Unternehmen erhält mehrere syntaktisch unterschiedliche Versionen derselben fachlichen Regel.

### Jede Berechnung in eine gemeinsame semantische Schicht zwingen

Selektionsabhängige Analyse, berichtsspezifische Quoten, lokale Szenarien und Antwortlogik einer Anwendung gehören nicht alle in den gemeinsamen Kern.

### Änderungen ohne Schemaänderung ignorieren

KPI-Bedeutung oder Historieregel ändern sich ohne neue Spalte. Consumer laufen technisch weiter, liefern aber eine andere fachliche Antwort.

### KI direkten Zugriff auf uneingeschränkte Warehouse-Daten geben

Das Modell erhält Felder ohne freigegebene Definitionen, Zugriffsfilter, Aktualitätsinformation oder Qualitätsstatus. Eine technisch erfolgreiche Antwort kann fachlich trotzdem unsicher sein.

### Neue Verträge veröffentlichen, ohne alte Pfade abzuschalten

Zertifizierte Views werden bereitgestellt, während manuelle Exporte, alte QVD-Generatoren und kopierte Arbeitsmappen unbegrenzt aktiv bleiben. Die Komplexität steigt, statt zu sinken.

## Entscheidungshilfe

Nutze bei der Gestaltung eines Nutzungswegs folgende Fragen:

| Frage | Architektonische Reaktion |
| --- | --- |
| Müssen mehrere Consumer diese Regel identisch interpretieren? | Im governten Datenprodukt definieren |
| Bestimmt die Regel Granularität, Schlüssel, Historie, Qualität oder Zugriffsberechtigung? | Außerhalb einzelner Consumer-Artefakte halten |
| Entsteht die Anforderung durch Analyse-Engine oder Oberfläche? | In einem dokumentierten Consumer-Modell umsetzen |
| Benötigt Excel eine tägliche operative Liste? | Einen kontrollierten, aktualisierbaren Excel-Vertrag veröffentlichen, statt den Use Case zu verbieten |
| Kann die bestehende SQL-Plattform verlässliche Views veröffentlichen? | Dort beginnen, bevor eine weitere Plattform ergänzt wird |
| Reduziert ein semantisches Power-BI-Modell Duplikation für Microsoft-Consumer? | Als governte Consumption-Schicht nutzen, nicht automatisch als Ersatz des plattformneutralen Kerns |
| Benötigt Qlik assoziative Strukturen oder Set Analysis? | Diese Qlik-spezifischen Elemente in einem schlanken Qlik-Modell belassen |
| Benötigt eine API ein stabiles Service-Schema? | API-Vertrag separat versionieren und Fakten aus demselben Produkt ableiten |
| Benötigt KI Kontext? | Einen zugriffsgefilterten, dokumentierten und aktualitätsbewussten Kontextvertrag veröffentlichen |
| Benötigen Consumer unterschiedliche Formen? | Mehrere abgeleitete Verträge mit einer autoritativen Bedeutung veröffentlichen |
| Ist eine Änderung technisch kompatibel, aber semantisch abweichend? | Als Vertragsänderung behandeln und kommunizieren |
| Reicht die aktuelle Umsetzung aus? | Zunächst Governance verbessern, bevor ein neues Tool eingeführt wird |

## Wichtigste Empfehlungen

1. Entwirf das Datenprodukt um eine fachliche Fähigkeit und nicht um einen einzelnen Bericht.
2. Definiere Granularität, Schlüssel, KPI-Grundlagen, Historie, Qualität, Sicherheit und Ownership vor der Veröffentlichung von Schnittstellen.
3. Halte gemeinsame fachliche Bedeutung in einem governten Kern.
4. Erlaube getrennte Qlik-, Power-BI-, Excel-, API- und KI-Modelle, wenn sich ihre Zwecke unterscheiden.
5. Mache Consumer-spezifische Logik explizit und dokumentiere, warum sie in das Tool gehört.
6. Behandle Excel als unterstützten Consumer, wenn der Fachprozess es tatsächlich benötigt.
7. Trenne Ist-Daten von lokalen Planungsannahmen und manuellen Eingaben.
8. Veröffentliche Qualitätsstatus und Aktualitätsinformation gemeinsam mit den Daten.
9. Versioniere semantische Änderungen und nicht nur Schemaänderungen.
10. Verwende stabile Nutzungsverträge mit Ownern, Support-Pfaden und Abkündigungsregeln.
11. Nutze vorhandene SQL-, Datei-, QVD- oder Plattformfähigkeiten, bevor ein weiteres Produkt ergänzt wird.
12. Setze Fabric, Snowflake, Databricks oder dbt nur ein, wenn sie ein konkretes Integrations-, Skalierungs-, Kollaborations- oder Betriebsproblem lösen.
13. Halte Qlik- und Power-BI-Modelle so schlank, dass die zentrale fachliche Regel wiederverwendbar bleibt.
14. Gib APIs und KI dedizierte Verträge statt unkontrolliertem Direktzugriff.
15. Schalte alte Exporte, kopierte Formeln und redundante Pipelines nach der Consumer-Migration ab.

## Übergang zum nächsten Part

Ein governtes Datenprodukt kann mehrere Consumer nur dann versorgen, wenn seine gemeinsamen Regeln über einen wartbaren Transformationspfad umgesetzt werden.

Der nächste Part, [Transformationsoptionen](/playbooks/transformation-options), vergleicht, wo diese Transformationen ausgeführt werden können — in vorhandenem SQL, Warehouse-Prozeduren, Qlik-orientierten Schichten, dbt, Fabric, Snowflake, Databricks oder hybriden Kombinationen — und wie die einfachste Option gewählt wird, die den Produktvertrag zuverlässig erhält.
