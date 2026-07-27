---
title: KPI & Metric Governance
description: Ein praxisnahes Betriebsmodell für klar definierte, konsistent berechnete und vertrauenswürdige KPIs und Metriken über Datenmodelle, Semantic Layer, BI-Tools und Excel hinweg.
author: Thomas Lindackers
category: Data Governance
tags:
  - data-governance
  - kpi-governance
  - metric-governance
  - semantic-layer
  - dbt
  - qlik
  - excel
  - power-bi
  - tableau
  - business-metrics
order: -1
publishedAt: 2026-06-07
series: governance-pillars
seriesPart: 7
seriesTitle: The 8 Pillars of Data Governance
hero: images/playbooks/kpi-gov-hero.png
---

## Vertrauenswürdige Daten führen nicht automatisch zu vertrauenswürdigen Kennzahlen

Ein Unternehmen kann sauber modellierte Daten, dokumentierte Tabellen und bestandene Data-Quality-Tests besitzen — und trotzdem in verschiedenen Reports unterschiedliche Zahlen sehen.

Der Grund liegt häufig nicht in den Rohdaten, sondern in der **Metrikschicht**:

- dieselbe KPI wird in mehreren Tools unterschiedlich berechnet
- Filter und Selektionslogik verändern das Ergebnis
- Business-Regeln werden direkt im Report statt im Datenmodell umgesetzt
- Excel-Dateien enthalten eigene Formeln und manuelle Korrekturen
- Qlik-, Power-BI- oder Tableau-Anwendungen definieren eigene Dimensionen
- Aggregationen reagieren unterschiedlich auf Granularität und Kontext
- fachliche Änderungen werden nicht versioniert oder freigegeben
- ein KPI-Name bleibt gleich, obwohl sich die Berechnung verändert
- verschiedene Teams verwenden unterschiedliche Zeit-, Kunden- oder Produktdefinitionen
- Kennzahlen besitzen keinen eindeutigen Owner

**KPI & Metric Governance** verbindet fachliche Bedeutung, technische Berechnung, Ownership, Lineage, Freigabe und Nutzung zu einem kontrollierten Lifecycle.

> *Eine KPI ist erst dann vertrauenswürdig, wenn Definition, Berechnung, Filter, Granularität, Herkunft und Verantwortlichkeit nachvollziehbar und über alle Nutzungskanäle hinweg konsistent sind.*

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/kpi-gov-de.png"
        alt="KPI und Metric Governance mit Definition, Ownership, Berechnung, Veröffentlichung, Monitoring sowie Nutzung in Qlik, Excel, Power BI und Tableau"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        KPI & Metric Governance verbindet fachliche Definition, kontrollierte Berechnung, Semantic Layer und konsistente Nutzung in Reporting- und Self-Service-Tools.
    </figcaption>
</figure>

## Eine Kennzahl besteht aus mehr als einer Formel

Eine belastbare KPI-Definition umfasst mindestens:

| Bestandteil | Beispiel |
| --- | --- |
| **Name** | Net Revenue |
| **Fachliche Beschreibung** | Umsatz nach Stornierungen, Rabatten und Retouren |
| **Zweck** | Steuerung der realisierten Umsatzentwicklung |
| **Formel** | Bruttoumsatz − Rabatte − Retouren |
| **Zähler / Nenner** | Bei Quoten eindeutig definiert |
| **Granularität** | Tag, Kunde, Produkt, Region |
| **Zeitlogik** | Buchungsdatum statt Rechnungsdatum |
| **Dimensionen** | Region, Produktgruppe, Vertriebskanal |
| **Filter** | Nur gebuchte und nicht stornierte Positionen |
| **Währung** | Konzernwährung nach freigegebenem FX-Verfahren |
| **Owner** | Finance KPI Owner |
| **Steward** | Finance Data Steward |
| **Quelle** | Faktentabelle plus definierte Dimensionen |
| **Freigabestatus** | Certified |
| **Version** | 3.2 |
| **Gültig ab** | 2026-07-01 |
| **Ersetzt durch** | Optional bei Ablösung |

Ohne diese Informationen kann dieselbe Formel in verschiedenen Kontexten unterschiedliche Ergebnisse erzeugen.

## Das typische Problem zwischen dbt und Reporting

dbt ist stark in der Transformation, Dokumentation und Versionierung von Datenmodellen.

Viele dbt-Projekte modellieren bewusst:

- **Facts** für messbare Ereignisse
- **Dimensions** für fachliche Merkmale
- standardisierte Transformationen
- vorberechnete Felder
- Tests und Dokumentation

Das ist eine gute Grundlage — aber noch keine Garantie für einheitliche Kennzahlen.

In Reportlösungen entstehen häufig zusätzliche Berechnungsebenen:

- Qlik verwendet dynamische Expressions und kontextabhängige Aggregationen
- Power BI nutzt DAX Measures
- Tableau verwendet Calculated Fields und Level-of-Detail-Ausdrücke
- Excel enthält Zellformeln, Pivot-Berechnungen, Power Query oder Power Pivot
- Self-Service-Nutzer erstellen eigene Dimensionen, Gruppen und Filter
- semantische Modelle definieren weitere Logik außerhalb von dbt

Dadurch kann eine Kennzahl trotz identischer Facts und Dimensions auseinanderlaufen.

Beispiel:

```text
dbt Modell:
revenue = quantity * unit_price

Qlik:
Sum({<Status={'Closed'}>} revenue)

Power BI:
CALCULATE(
    SUM(FactSales[Revenue]),
    DimStatus[Status] = "Closed"
)

Excel:
=SUMIFS(Revenue, Status, "Closed")
```

Die Formeln wirken gleich — können aber unterschiedliche Ergebnisse liefern, wenn:

- `Status` unterschiedlich interpretiert wird
- Nullwerte anders behandelt werden
- Beziehungen im semantischen Modell abweichen
- Filterkontext unterschiedlich wirkt
- Zeitdimensionen nicht identisch sind
- Duplikate oder Many-to-Many-Beziehungen existieren
- Nutzer eigene Gruppen oder Ausnahmen hinzufügen
- Excel-Daten nicht denselben Aktualitätsstand besitzen

Das Kernproblem lautet:

```text
Trusted Facts + Trusted Dimensions
        ≠
automatisch Trusted Metrics
```

Vertrauenswürdige Kennzahlen benötigen eine kontrollierte Metrikschicht.

## Dynamische Formeln sind nicht das Problem

Dynamische Berechnungen sind in Reporting-Tools oft notwendig.

Sie ermöglichen:

- kontextabhängige Aggregationen
- flexible Zeitvergleiche
- Set Analysis
- Szenarien und What-if-Analysen
- Nutzerselektionen
- dynamische Gruppierungen
- rollenspezifische Sichtweisen
- interaktive Self-Service-Analysen

Das Problem entsteht erst, wenn die Logik:

- nur lokal im Report existiert
- nicht dokumentiert ist
- keinen Owner besitzt
- nicht getestet wird
- nicht versioniert ist
- von anderen Tools abweicht
- fachlich nie freigegeben wurde
- durch Kopieren unkontrolliert vervielfältigt wird

KPI Governance sollte dynamische Logik deshalb nicht verbieten, sondern kontrolliert klassifizieren.

## Drei Ebenen der Metriklogik

Ein praktisches Modell unterscheidet drei Ebenen.

| Ebene | Typische Logik | Governance-Ziel |
| --- | --- | --- |
| **Data Model** | Standardisierte Facts, Dimensions, bereinigte Attribute | Verlässliche, wiederverwendbare Datengrundlage |
| **Semantic / Metrics Layer** | Freigegebene KPI-Formeln, Zeitlogik, Filter, Granularität | Zentrale und toolübergreifende Definition |
| **Report / Analysis Layer** | Dynamische Selektionen, Visualisierung, lokale Analysen | Kontrollierte Flexibilität ohne neue „Wahrheiten“ |

Die entscheidende Frage ist:

***Welche Logik gehört in die zentrale, freigegebene Metrikdefinition — und welche darf bewusst lokal und explorativ bleiben?***

## Das KPI & Metric Operating Model

Ein belastbarer Lifecycle kann in sechs Schritten aufgebaut werden.

```flow linear vertical
1. KPI fachlich definieren
2. Owner, Steward und Gültigkeit festlegen
3. Berechnungslogik und Granularität standardisieren
4. Freigegebene Metrik veröffentlichen
5. Nutzung, Qualität und Abweichungen überwachen
6. Review, Versionierung und kontrollierte Ablösung
```

## 1. KPI fachlich definieren

Vor der technischen Umsetzung müssen folgende Fragen beantwortet sein:

- Welche Business-Frage beantwortet die KPI?
- Welche Entscheidung soll sie unterstützen?
- Welche Ereignisse werden gezählt?
- Welche Ereignisse werden ausgeschlossen?
- Welche Zeitlogik gilt?
- Welche Granularität ist zulässig?
- Welche Dimensionen dürfen verwendet werden?
- Welche Zielwerte und Schwellenwerte gelten?
- Welche Ausnahmen existieren?
- Welche andere Kennzahl darf nicht mit ihr verwechselt werden?

Eine Definition wie „aktive Kunden“ reicht nicht.

Besser:

```text
Active Customer

Ein Kunde gilt als aktiv, wenn innerhalb der letzten
90 Kalendertage mindestens ein abgeschlossener Kauf
mit positivem Nettoumsatz vorliegt.

Ausgeschlossen:
- Testkunden
- vollständig stornierte Bestellungen
- interne Mitarbeiterkonten

Zeitbezug:
Rolling 90 Days auf Basis des Buchungsdatums
```

## 2. Ownership und Entscheidungsrechte

Kennzahlen benötigen fachliche Verantwortung.

| Rolle | Typische Verantwortung |
| --- | --- |
| **KPI Owner** | Verantwortet Bedeutung, Zweck, Freigabe und Zielwerte |
| **Data Steward** | Pflegt Definition, Metadaten, Glossar und Änderungsverlauf |
| **BI / Analytics Lead** | Stellt konsistente Umsetzung in Reporting und Semantic Layer sicher |
| **Data Engineer** | Implementiert Datenmodell, Transformationen und technische Tests |
| **Business Analyst** | Validiert fachliche Nutzung und erkennt Inkonsistenzen |
| **Data Product Owner** | Verantwortet Bereitstellung und Service-Level |
| **Data Consumer** | Nutzt freigegebene Kennzahlen korrekt und meldet Abweichungen |

Wichtig ist eine eindeutige Entscheidungshoheit.

Nicht jedes Team darf dieselbe KPI unabhängig neu definieren.

## 3. Berechnung, Filter und Granularität standardisieren

Eine technische Definition sollte explizit festhalten:

- Formel
- Quellen
- Join-Logik
- Aggregationsart
- Zeitdimension
- Granularität
- Standardfilter
- Ausschlüsse
- Nullbehandlung
- Währungslogik
- Rundung
- Dimensionsabhängigkeiten
- Slowly-Changing-Dimension-Verhalten
- Late-arriving Facts
- historische Neuberechnung

Beispiel:

```yaml
metric: net_revenue
version: 3.2
owner: finance
expression: gross_revenue - discounts - returns
time_dimension: booking_date
default_grain: day
currency: group_currency
filters:
  order_status:
    - booked
    - completed
exclude:
  - test_customer
  - internal_account
certification: certified
```

## 4. Freigegebene Metriken veröffentlichen

Freigegebene Kennzahlen sollten dort verfügbar sein, wo Nutzer arbeiten.

Mögliche Zielsysteme:

- Semantic Layer
- Metrics Layer
- dbt Semantic Layer
- Qlik Apps und Master Measures
- Power BI Semantic Models
- Tableau Published Data Sources
- Excel über kontrollierte Datenverbindungen
- Datenkatalog und Business Glossary
- API-basierte Datenprodukte

Das Ziel ist nicht, alle Nutzer in ein Tool zu zwingen.

Das Ziel ist:

```flow linear vertical
One approved business meaning
Consistent metric logic
Multiple governed consumption tools
```

Qlik, Excel, Power BI und Tableau dürfen unterschiedliche Oberflächen bieten — aber die freigegebene KPI sollte dieselbe Bedeutung behalten.

## Excel ist Teil der Governance-Landschaft

Excel wird in vielen Unternehmen weiterhin intensiv genutzt.

Typische Gründe sind:

- hohe Verbreitung
- flexible Ad-hoc-Analyse
- schnelle Szenarien
- individuelle Berechnungen
- vertraute Arbeitsweise
- einfache Weitergabe
- Nähe zu Finance und Controlling

Excel aus Governance auszuschließen ist deshalb unrealistisch.

Stattdessen sollten klare Regeln gelten:

- zertifizierte Datenquellen verwenden
- freigegebene KPI-Definitionen referenzieren
- manuelle Überschreibungen sichtbar machen
- lokale Formeln von offiziellen Kennzahlen unterscheiden
- Version und Aktualitätsstand anzeigen
- kritische Dateien kontrolliert speichern
- fachliche Freigabe für wiederkehrende Management-Reports
- bekannte Excel-KPIs in das Metrik-Inventar aufnehmen

Ein sinnvoller Status kann sein:

| Status | Bedeutung |
| --- | --- |
| **Exploratory** | Lokale Analyse ohne offiziellen KPI-Status |
| **Reviewed** | Fachlich geprüft, aber noch nicht zentral veröffentlicht |
| **Certified** | Freigegebene Definition und kontrollierte Datenquelle |
| **Deprecated** | Nicht mehr für neue Nutzung vorgesehen |

## Qlik und dynamischer Filterkontext

Qlik bietet mit Set Analysis und assoziativem Datenmodell hohe Flexibilität.

Gerade diese Stärke kann jedoch zu Abweichungen führen.

Beispiel:

```qlik
Sum(Sales)
```

gegen:

```qlik
Sum({<OrderStatus={'Closed'}>} Sales)
```

gegen:

```qlik
Sum(
    Aggr(
        Sum({<OrderStatus={'Closed'}>} Sales),
        CustomerID
    )
)
```

Alle drei Ausdrücke können fachlich sinnvoll sein — aber sie messen nicht automatisch dasselbe.

Governance sollte daher festlegen:

- Welche Master Measures sind zertifiziert?
- Welche Set-Analysis-Filter gehören zur offiziellen Definition?
- Welche Dimensionen sind freigegeben?
- Welche Alternate States beeinflussen die Kennzahl?
- Welche lokalen Expressions sind explorativ?
- Wie werden Änderungen an Master Measures geprüft?
- Wie wird die Formel in Lineage und Katalog sichtbar?

## Eigene Dimensionen verändern die Aussage

Nutzer definieren häufig eigene Dimensionen:

- Kundengruppen
- Produktcluster
- Regionen
- Altersklassen
- Vertriebssegmente
- Zeitperioden
- manuelle Ausnahmen

Diese Flexibilität ist wertvoll, kann aber Kennzahlen verändern.

Beispiel:

```text
Zentrale Dimension:
Region basiert auf Vertragsstandort

Lokale Report-Dimension:
Region basiert auf Postleitzahl des Rechnungsempfängers
```

Beide Reports zeigen „Umsatz nach Region“, messen aber unterschiedliche Konzepte.

Deshalb benötigen auch Dimensionen Governance:

- Definition
- Herkunft
- Gültigkeit
- Hierarchie
- Granularität
- Änderungsverlauf
- Owner
- Freigabestatus

## Metrik-Lineage

Eine Kennzahl sollte bis zu ihren Quellen nachvollziehbar sein.

Beispiel:

```flow linear vertical
CRM + ERP
RAW sales data
CONFORM sales facts
DIM customer / product / calendar
Semantic Metric: Net Revenue
Qlik / Excel / Power BI / Tableau
Management Report
```

Lineage sollte nicht am Tabellenmodell enden.

Sie sollte möglichst auch erfassen:

- verwendete Formel
- Filter
- Dimensionen
- semantische Modelle
- Report-Objekte
- Excel-Verbindungen
- Downstream-Management-Reports

## KPI-Konflikte erkennen

Typische Konfliktmuster sind:

- gleicher Name, unterschiedliche Formel
- gleiche Formel, andere Zeitlogik
- gleiche KPI, andere Standardfilter
- gleiche Kennzahl, andere Granularität
- lokale Excel-Korrekturen
- Qlik Set Analysis weicht von DAX oder SQL ab
- Dimensionen verwenden unterschiedliche Hierarchien
- historische Versionen werden parallel genutzt
- alte Reports behalten veraltete Definitionen

Ein Governance-Prozess sollte Konflikte sichtbar machen.

Beispiel:

| KPI | Tool | Version | Status |
| --- | --- | --- | --- |
| Net Revenue | Semantic Layer | 3.2 | Certified |
| Net Revenue | Qlik Sales App | 3.2 | Certified |
| Net Revenue | Finance Excel | 2.8 | Outdated |
| Revenue Net | Power BI | unbekannt | Review required |

## Testing von Kennzahlen

KPI-Tests sollten über technische Datentests hinausgehen.

Geeignete Kontrollen sind:

- Formeltest
- Reconciliation zwischen Quelle und Metrik
- Vergleich über Reporting-Tools
- Filter- und Kontexttest
- Zeitlogiktest
- Granularitätstest
- Regressionstest bei Änderungen
- bekannte Beispieldatensätze
- Grenzfalltests
- Abweichungstest gegenüber freigegebener Referenz

Beispiel:

```text
Reference Scenario:
Customer A
2 booked orders
1 cancelled order
10 EUR discount

Expected Net Revenue:
190 EUR
```

Die Referenz sollte in dbt, Semantic Layer und BI-Tool dasselbe Ergebnis erzeugen.

## Change Management

Metriken verändern sich.

Änderungen können ausgelöst werden durch:

- neue Geschäftsmodelle
- regulatorische Anforderungen
- veränderte Produktstruktur
- neue Währungslogik
- neue Zeitdefinitionen
- Fusionen oder Organisationsänderungen
- korrigierte fachliche Regeln

Jede Änderung sollte dokumentieren:

- Änderungsgrund
- betroffene Version
- neue Definition
- Gültigkeitsdatum
- Auswirkung auf historische Werte
- betroffene Reports
- Migration
- Freigabe
- Kommunikationsplan

Ein KPI-Name sollte nicht stillschweigend eine neue Bedeutung erhalten.

## Versionierung

Beispiel:

| Version | Änderung | Gültig ab |
| --- | --- | --- |
| 1.0 | Erstdefinition | 2024-01-01 |
| 2.0 | Retouren einbezogen | 2025-01-01 |
| 3.0 | Neue Konzernwährung | 2026-01-01 |
| 3.2 | Korrektur der Storno-Logik | 2026-07-01 |

Historische Reports benötigen eine klare Regel:

- alte Werte unverändert lassen
- vollständig neu berechnen
- parallele Versionen anzeigen
- Umstellung ab Stichtag

## KPI & Metric Governance messen

Nützliche Kennzahlen sind:

- Anteil freigegebener KPIs mit Owner
- Anteil KPIs mit dokumentierter Formel
- Anteil KPIs mit definierter Granularität
- Anteil KPIs mit verfügbarer Lineage
- Anteil zertifizierter Metriken je Reporting-Tool
- Anzahl widersprüchlicher Definitionen
- Anzahl lokaler, nicht registrierter KPI-Formeln
- Anzahl veralteter KPI-Versionen in Reports
- Zeit bis zur Freigabe einer KPI-Änderung
- Anzahl Änderungsanträge
- Anzahl KPI-bezogener Data-Quality-Incidents
- Nutzung zertifizierter gegenüber lokaler Kennzahlen
- Anzahl Excel-Reports mit manueller Überschreibung
- Anteil Reports auf kontrollierten Semantic Models
- Anzahl KPI-Abweichungen zwischen Tools
- Wiederverwendungsrate zentraler Metriken

## Ein einfaches Reifegradmodell

| Reifegrad | Typischer Zustand |
| --- | --- |
| **Lokal** | KPIs werden individuell in Reports und Excel definiert |
| **Dokumentiert** | Erste Definitionen und Glossare existieren |
| **Standardisiert** | Owner, Formeln und Dimensionen sind vereinheitlicht |
| **Zertifiziert** | Freigegebene Kennzahlen besitzen Status und Version |
| **Semantisch integriert** | Zentrale Metriken werden über mehrere Tools bereitgestellt |
| **Überwacht** | Nutzung, Konflikte und Abweichungen werden gemessen |
| **Automatisiert** | Tests, Lineage und Change Workflows sind integriert |
| **Eingebettet** | Neue Reports und Datenprodukte nutzen standardmäßig freigegebene Metriken |

## Typische Anti-Patterns

- dieselbe KPI wird in jedem Tool neu gebaut
- dbt-Facts und Dimensions gelten automatisch als vollständige KPI-Governance
- dynamische Reportformeln sind nicht dokumentiert
- Excel wird ignoriert, obwohl Management-Entscheidungen darauf basieren
- lokale Dimensionen verändern unbemerkt die Aussage
- unterschiedliche Zeitlogiken verwenden denselben KPI-Namen
- Kennzahlen besitzen keinen fachlichen Owner
- Berechnungen werden kopiert statt wiederverwendet
- Änderungen erfolgen ohne Versionierung
- alte Reports bleiben mit veralteten Formeln aktiv
- Semantic Layer und BI-Logik widersprechen sich
- Tests prüfen Daten, aber nicht das KPI-Ergebnis
- Filter und Granularität sind nicht Bestandteil der Definition
- Nutzer sehen keinen Zertifizierungsstatus
- Erfolg wird an der Anzahl dokumentierter KPIs statt an reduzierten Konflikten gemessen

Eine zentrale Faktentabelle verhindert keine widersprüchlichen Kennzahlen.

Erst die kontrollierte Verbindung von **Datenmodell, Metrikdefinition, Semantic Layer und Reporting-Nutzung** schafft Vertrauen.

## Verbindung zu den anderen Governance-Säulen

| Säule | Verbindung |
| --- | --- |
| **Data Ownership & Stewardship** | KPI Owner und Stewards entscheiden Bedeutung, Freigabe und Änderungen |
| **Metadata, Catalog & Lineage** | Definition, Formel, Herkunft und Report-Nutzung werden nachvollziehbar |
| **PII & Privacy Governance** | Personenbezogene Dimensionen und Segmente benötigen kontrollierte Nutzung |
| **DSDR Governance** | Löschungen können KPI-Populationen und historische Vergleiche beeinflussen |
| **Data Quality Governance** | Kennzahlen benötigen verlässliche Eingangsdaten und eigene Ergebnisprüfungen |
| **Access & Security Governance** | Nicht jede Kennzahl und Detaildimension darf für jede Rolle sichtbar sein |
| **Data Lifecycle & Retention** | Historisierung und Aufbewahrung bestimmen Vergleichbarkeit und Reproduzierbarkeit |

## Praktisches Zielbild

```flow linear vertical
Business Definition
KPI Owner + Steward
Versioned Metric Specification
Facts + Dimensions + Semantic Logic
Certified Metric
Qlik / Excel / Power BI / Tableau
Monitoring + Reconciliation + Review
Trusted Decisions
```

## Das Ergebnis

Wirksame KPI & Metric Governance schafft:

- **Einheitliche Bedeutung** — Teams sprechen über dieselbe fachliche Kennzahl
- **Konsistente Berechnung** — zentrale Logik reduziert Tool-Abweichungen
- **Transparenz** — Formel, Filter, Granularität, Version und Herkunft sind sichtbar
- **Vertrauen** — Management und Fachbereiche können Zahlen nachvollziehen
- **Flexibilität** — Reporting-Tools bleiben nutzbar, ohne neue Wahrheiten zu erzeugen
- **Effizienz** — freigegebene Metriken werden wiederverwendet
- **Kontrolle** — Änderungen werden versioniert, getestet und freigegeben
- **Business Value** — Entscheidungen basieren auf abgestimmten und belastbaren Kennzahlen

Die entscheidende Frage lautet nicht:

*„Haben wir eine zentrale Faktentabelle?“*

Sondern:

***„Erzeugen alle relevanten Tools aus denselben fachlichen Regeln dieselbe vertrauenswürdige Kennzahl?“***

Verwandte Übersicht: [Die 8 Säulen der Data Governance](/playbooks/eight-pillars).

Vorherige Säule: [Data Quality Governance](/playbooks/data-quality-governance).
