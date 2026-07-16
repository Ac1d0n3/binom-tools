---
title: Eine App kann nicht jede Frage beantworten
description: Warum ein gemeinsames governed Datenfundament im Kern groß und wiederverwendbar sein sollte — während Echtzeit-, Tagesgeschäfts-, Abteilungs-, Management- und CEO-Apps bewusst klein und fokussiert bleiben.
category: Architektur
tags:
  - business-intelligence
  - datenmodellierung
  - datenarchitektur
  - analytics
  - reporting
  - decision-apps
  - semantic-layer
  - data-governance
  - realtime
  - kpi-governance
order: -1
author: Thomas Lindackers
hero: images/playbooks/one-app-hero.png
---

## Eine Universal-App ist nicht dasselbe wie eine Source of Truth

Viele BI-Initiativen beginnen mit einem attraktiv klingenden Versprechen:

> **Eine App soll jede Frage für jeden Nutzer beantworten.**

Die Absicht ist nachvollziehbar. Eine App wirkt zunächst einfacher zu verteilen, zu govern-en und zu betreiben als viele getrennte Lösungen. Jede Abteilung soll dieselben Daten verwenden, jede Kennzahl soll verfügbar sein, jedes historische Detail soll erreichbar bleiben und jede neue Frage soll ohne einen weiteren Entwicklungszyklus beantwortet werden können.

Mit der Zeit wird die Anwendung jedoch häufig zu einem Container für alles:

- operative Details und strategische KPIs
- heutige Aktionen und zehn Jahre Historie
- Finance-, Vertriebs-, Operations-, HR- und Supply-Chain-Daten
- tägliche Steuerung und Jahresplanung
- Standardreporting und explorative Analysen
- Echtzeitsignale und langsam veränderliche Stammdaten
- Tausende Dimensionen, Kennzahlen, Filter und Visualisierungen

Die Antwort kann technisch irgendwo vorhanden sein — aber der Nutzer findet sie nicht mehr.

Eine **Single Source of Truth** benötigt keine **einzige Universal-App**. Sie benötigt gemeinsame Definitionen, kompatible Datenmodelle, einheitliche Dimensionen, kontrollierte Geschäftslogik und nachvollziehbare Ownership. Unterschiedliche Entscheidungen können anschließend unterschiedliche, fokussierte Ausschnitte desselben governed Fundaments nutzen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/one-app-img1-de.png"
        alt="Das Problem einer universellen BI-Anwendung für jede Rolle und jede Fragestellung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Soll eine Anwendung jede Rolle, jeden Zeitraum und jede analytische Tiefe bedienen, wird sie zum Datencontainer statt zum Entscheidungswerkzeug.
    </figcaption>
</figure>

## Die zentrale Trennung: Datenmodell und Entscheidungs-App

Ein großes unternehmensweites Datenmodell kann wertvoll sein.

Eine große Endanwender-App ist nicht automatisch wertvoll.

Beide Ebenen erfüllen unterschiedliche Aufgaben:

| Ebene | Hauptaufgabe |
| --- | --- |
| **Unternehmensweites Datenfundament** | Details bewahren, Fachbereiche harmonisieren sowie Schlüssel, Definitionen und Historie standardisieren |
| **Semantik- und Kennzahlenschicht** | Vertrauenswürdige Dimensionen, Kennzahlen, Hierarchien und fachliche Bedeutung bereitstellen |
| **Entscheidungs-App** | Einen definierten Fragenkatalog für eine bestimmte Rolle und einen konkreten Prozess beantworten |
| **Analytics- und KI-Zugriff** | Tiefere Analysen, Forecasting, Feature Engineering und Exploration ermöglichen |

Das Unternehmensfundament sollte breit genug für viele Use Cases sein. Die Nutzungsschicht sollte eng genug sein, um eine konkrete Entscheidung zu führen.

Daraus ergibt sich ein einfaches Architekturprinzip:

> **Klein in der Fachdomäne starten. Im Kern groß und wiederverwendbar werden. Am Entscheidungspunkt filtern und fokussieren.**

## Klein → groß → fokussiert

Die Zielarchitektur lautet weder „viele isolierte Apps“ noch „eine App mit allem“.

Sie beschreibt einen kontrollierten Fluss:

```flow linear vertical
Fokussierte Abteilungs-Facts
Gemeinsames governed Unternehmensmodell
Entscheidungsspezifische Filter und Aggregate
Zweckorientierte Apps
```

### 1. Mit fokussierten Abteilungs-Facts beginnen

Jede Abteilung modelliert zunächst ihre Geschäftsprozesse mit einem klar definierten Grain.

Beispiele:

- Vertrieb: eine Zeile pro Auftragsposition
- Finance: eine Zeile pro Buchung oder Rechnungsposition
- Operations: eine Zeile pro Produktionsereignis
- Supply Chain: eine Zeile pro Lieferung oder Bestandssnapshot
- Kundenservice: eine Zeile pro Fall oder Interaktion
- HR: eine Zeile pro Personalereignis oder periodischem Snapshot

Der Grain muss explizit sein. Fehlt er, werden Kennzahlen schnell dupliziert, falsch verknüpft oder widersprüchlich interpretiert.

Ein Abteilungsmodell sollte trotzdem nicht als isolierte Insel entstehen. Es sollte von Beginn an an unternehmensweite Konventionen anschließen:

- gemeinsame Datums- und Zeitschlüssel
- gemeinsame Organisationsstrukturen
- gemeinsame Kunden-, Produkt-, Standort- und Partner-IDs
- standardisierte Währungs- und Mengeneinheiten
- gemeinsame Szenarien wie Ist, Budget und Forecast
- konsistente Quellsystem- und Lineage-Metadaten
- gemeinsame Data-Quality- und Sensitivitätsklassifikationen

Dadurch werden die Modelle **komponierbar**.

### 2. Eine große unternehmensweite Fact-Landschaft aufbauen

Das Ziel ist nicht zwangsläufig, jeden Abteilungsprozess in eine einzige physische Tabelle zu zwingen.

Aufträge, Buchungen, Bestandssnapshots und Servicefälle besitzen häufig unterschiedliche Grains. Werden inkompatible Grains in eine riesige Tabelle gepresst, entstehen duplizierte Kennzahlen, leere Spalten und unklare Semantik.

Das bessere Ziel lautet:

> **Abteilungs-Facts so kompatibel gestalten, dass sie gemeinsam genutzt werden können.**

Dafür gibt es zwei sinnvolle Muster.

#### Muster A — eine unternehmensweite Event-Fact

Facts mit einem kompatiblen ereignisorientierten Grain können standardisiert und in einer logischen oder physischen Enterprise-Event-Fact zusammengeführt werden.

Eine vereinfachte Struktur könnte so aussehen:

```text
event_id
event_type
event_timestamp
department_key
company_key
customer_key
product_key
location_key
employee_key
scenario_key
currency_key
quantity
amount
cost
margin
status
source_system
source_record_id
```

Vertriebs-, Logistik- und Serviceereignisse lassen sich dann über einen standardisierten Ereignistyp und gemeinsame Schlüssel zusammenführen. Die ursprünglichen Abteilungs-Facts können für Spezialdetails weiterhin bestehen bleiben.

#### Muster B — Fact Constellation mit gemeinsamen Dimensionen

Unterscheiden sich die Grains, bleiben die Facts getrennt:

```text
fact_order_line
fact_invoice_line
fact_inventory_snapshot
fact_production_event
fact_service_case
fact_workforce_snapshot
```

Sie werden über gemeinsame Dimensionen und governed Kennzahlen verbunden:

```text
dim_date
dim_company
dim_department
dim_customer
dim_product
dim_location
dim_employee
dim_scenario
dim_currency
```

Für den Konsumenten kann dies trotzdem wie ein einheitliches Unternehmensmodell funktionieren. Die Vereinheitlichung erfolgt über Semantic Layer, governed Views, Kennzahlendefinitionen oder gezielte Data Products — nicht über eine physisch übergroße Tabelle.

Das entscheidende Ergebnis bleibt gleich:

- Abteilungen behalten einen klaren prozessspezifischen Grain
- unternehmensweite Fragen können Abteilungsgrenzen überschreiten
- KPIs verwenden konsistente Definitionen
- CEO- und Management-Sichten benötigen keine eigene Schattenlogik
- Python, BI und KI-Workloads nutzen dasselbe governed Fundament

## Das große Modell ist das Fundament — nicht die Benutzeroberfläche

Ein breites Unternehmensmodell kann enthalten:

- detaillierte Historie
- viele Business-Entitäten
- Hunderte oder Tausende Attribute
- transaktionale und aggregierte Facts
- Features für Forecasting
- technische Lineage- und Audit-Felder
- Security- und Klassifizierungsmetadaten

Für Advanced Analytics und Wiederverwendung kann das sinnvoll sein.

Es bedeutet nicht, dass jedes Feld in jede App gehört.

Fünftausend Dimensionen und fünfzigtausend Spalten erzeugen kein Self-Service. Sie verlagern Modellierung, Filterung und Interpretation vom Datenteam auf den Endanwender.

Ein Nutzer sollte nicht das vollständige Enterprise-Schema durchsuchen müssen, um folgende Fragen zu beantworten:

- Welche Aufträge benötigen heute Aufmerksamkeit?
- Welche Kunden sind gefährdet?
- Welche Standorte haben ihr Monatsziel verfehlt?
- Welche fünf KPIs benötigen eine Entscheidung der Geschäftsführung?
- Welche Ausnahmen erfordern sofortiges Handeln?

Das Datenfundament darf Komplexität bewahren. Die App muss sie reduzieren.

## Für die Entscheidung filtern

Die Nutzungsschicht wählt nur die Daten aus, die für eine Rolle, eine Entscheidung und einen Zeithorizont benötigt werden.

Typische Mechanismen sind:

- governed SQL Views
- materialisierte Views
- Aggregationstabellen
- semantische Modelle
- Metric Layer
- app-spezifische Extrakte
- Row- und Column-Level Security
- inkrementelle Datensätze
- Event Streams für ausgewählte Signale

Der Filter ist nicht nur eine technische `WHERE`-Bedingung. Er bildet einen fachlichen Vertrag:

- Welche Fragen beantwortet diese App?
- Welche Datensätze sind relevant?
- Welche Kennzahlen sind vertrauenswürdig?
- Welcher Kontext ist erforderlich?
- Wie aktuell müssen die Daten sein?
- Welche Aktionen soll der Nutzer ausführen?
- Welche Details müssen zur Erklärung verfügbar bleiben?

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/one-app-img2-de.png"
        alt="Ein gemeinsames governed Unternehmensdatenmodell wird in Echtzeit-, Tagesgeschäfts-, Abteilungs-, Management-, CEO- und Analytics-Anwendungen gefiltert"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das governed Modell kann groß und wiederverwendbar sein. Jede App erhält nur den Ausschnitt, die Aktualität und die Detailtiefe, die ihr Entscheidungsprozess wirklich benötigt.
    </figcaption>
</figure>

## Sechs fokussierte Nutzungsmuster

### 1. Echtzeit-KPI-App

Eine Echtzeit-App sollte nur die Signale enthalten, die tatsächlich minimale Latenz benötigen.

Typische Inhalte:

- fünf oder zehn kritische KPIs
- aktueller Status
- Grenzwertverletzungen
- Veränderungsrichtung
- Zeitpunkt der letzten Aktualisierung
- klarer Alarm- oder Eskalationsstatus

Sie sollte nicht das vollständige Unternehmensmodell in Echtzeit laden, nur weil die Plattform es technisch ermöglicht.

Geeignete Beispiele sind:

- Produktionsunterbrechung
- Betrugssignal
- Systemverfügbarkeit
- kritischer Bestandsmangel
- aktuelle Verletzung eines Service Levels

Das relevante Prinzip lautet:

> **So schnell wie notwendig — nicht so schnell wie technisch möglich.**

### 2. Daily Business App

Die Daily Business App beantwortet:

> **Was benötigt heute eine Aktion?**

Sie sollte sich konzentrieren auf:

- Ausnahmen
- überfällige Aufgaben
- kritische Kunden oder Aufträge
- Fälle mit Freigabebedarf
- fehlende Informationen
- empfohlene nächste Aktion
- verantwortliche Person
- operative Details zur Bearbeitung

Sie ist eine Aktionsliste und kein Explorer für die vollständige Historie.

Eine Daily App kann nur einige hundert oder wenige tausend Datensätze verwenden, obwohl die zugrunde liegende Plattform Milliarden Datensätze enthält.

### 3. Abteilungs-Apps

Abteilungs-Apps unterstützen wiederkehrende fachliche Entscheidungen:

- Vertriebssteuerung
- Finanzcontrolling
- Produktionsleistung
- Supply-Chain-Ausführung
- Personalplanung
- Service Management

Sie dürfen mehr Detail enthalten als eine CEO- oder Management-Sicht, sollten aber weiterhin auf die tatsächlichen Prozesse der Abteilung fokussiert bleiben.

Die Abteilungs-App sollte wiederverwenden:

- gemeinsame Dimensionen
- governed KPI-Definitionen
- einheitliche Security-Regeln
- zertifizierte Data Products
- konsistente Perioden- und Währungslogik

Sie sollte keine eigene Definition von Umsatz, Marge, Kunde oder Produkt erzeugen, sofern keine dokumentierte fachliche Abgrenzung besteht.

### 4. Management-App

Monats-, Quartals- und Jahresrunden benötigen typischerweise:

- aggregierte KPIs
- Plan-Ist-Vergleiche
- Trends
- Abweichungstreiber
- Organisations- und Produkthierarchien
- kontrollierten Drill-down
- einen stabilen und abstimmbaren Berichtsstand

Echtzeit ist hier selten die Hauptanforderung. Konsistenz, Vollständigkeit und Erklärbarkeit sind wichtiger.

Eine Management-App sollte einen geprüften Reporting-Zyklus abbilden und nicht einen permanent wechselnden Zahlenstrom.

### 5. CEO-Perspektive

Der CEO benötigt nicht jedes Detail aus jeder Abteilung.

Die Executive-Sicht sollte wenige unternehmensweite Fragen beantworten, zum Beispiel:

- Entwickeln sich Umsatz, Marge, Cash und Service wie erwartet?
- Welche strategischen Risiken benötigen ein Eingreifen?
- Wo liegen die größten Abweichungen?
- Welche Geschäftsbereiche benötigen Aufmerksamkeit?
- Welche Annahmen haben sich verändert?
- Welche Entscheidung ist jetzt erforderlich?

Die CEO-Perspektive kann aus dem großen Unternehmensmodell erzeugt werden, lädt aber nur die relevanten Kennzahlen, Treiber und Ausnahmen.

Eine kleine Executive-Sicht ist keine Einschränkung der Analysefähigkeit. Sie ist bewusste Priorisierung.

### 6. Analytics- und KI-Modell

Advanced Analytics benötigt den breitesten Zugriff:

- detaillierte Historie
- viele Attribute
- Trainingsfeatures
- Ereignisfolgen
- externe Daten
- Forecast-Zielgrößen
- reproduzierbare Datensätze
- Zugriff aus Python, Notebooks, SQL und BI-Werkzeugen

Hier erzeugt das große Modell einen erheblichen Mehrwert.

Das Analytics-Modell ist jedoch nicht automatisch eine Endanwender-App. Data Scientists und Analysten benötigen Explorationsfreiheit; operative Nutzer benötigen Führung und Handlungsorientierung.

Beide können dasselbe governed Fundament nutzen, ohne dieselbe Oberfläche zu erhalten.

## Aktualität ist eine Designdimension

Unterschiedliche Use Cases benötigen unterschiedliche Latenz.

| Use Case | Typisches Aktualitätsziel |
| --- | --- |
| Kritischer Alarm oder Maschinenereignis | Sekunden bis Minuten |
| Operative Warteschlange | Minuten bis stündlich |
| Daily Business Steuerung | stündlich bis täglich |
| Abteilungsperformance | mehrmals täglich oder täglich |
| Management Reporting | täglich, Periodenabschluss oder zertifizierter Snapshot |
| CEO-Perspektive | an Management-Zyklus ausgerichtet, ergänzt um ausgewählte kritische Alerts |
| Forecasting und Modelltraining | workload-spezifisch, häufig Batch oder inkrementell |

Alles in Echtzeit bereitzustellen erhöht Kosten, Komplexität und Betriebsrisiko, ohne automatisch bessere Entscheidungen zu erzeugen.

Echtzeit sollte gezielt für die Werte eingesetzt werden, bei denen Latenz die Handlung verändert.

## Gemeinsame Logik, getrennte Nutzungserlebnisse

Zweckorientierte Apps dürfen nicht zu isolierten Silos werden.

Die Trennung gehört in die **Experience- und Nutzungsschicht**, nicht in die Definitionen.

Folgende Elemente sollten gemeinsam bleiben:

- Kunden-, Produkt- und Organisationsdimensionen
- KPI-Formeln
- Währungs- und Einheitenumrechnung
- Kalender- und Geschäftsjahrlogik
- Data-Quality-Regeln
- Klassifikationen und Sensitivitätsmetadaten
- Zugriffsrichtlinien
- Lineage
- Ownership
- Zertifizierungsstatus

So entsteht eine governed Wahrheit mit mehreren zweckmäßigen Perspektiven.

```text
Eine Definition von Umsatz
Eine Definition von Marge
Eine Kundenidentität
Ein Geschäftskalender
Ein Security-Modell
Viele fokussierte Apps
```

## Beispiel: Von Abteilungsprozessen zur Executive-Sicht

Betrachten wir einen Order-to-Cash-Prozess.

Die Abteilungs-Facts können enthalten:

```text
Vertrieb       → Auftragspositionen
Operations     → Produktionsereignisse
Supply Chain   → Lieferungen
Finance        → Rechnungen und Zahlungen
Service        → Beschwerden und Retouren
```

Sie bleiben prozessspezifisch, teilen aber:

```text
Kunde
Produkt
Unternehmen
Abteilung
Standort
Datum
Währung
Auftragsreferenz
Business Event
```

Das Unternehmensmodell kann dadurch abteilungsübergreifende Fragen beantworten:

- Welche verspäteten Aufträge erzeugen Umsatzrisiken?
- Welche Produktionsprobleme betreffen strategische Kunden?
- Welche ausgelieferten Aufträge sind weiterhin unbezahlt?
- Welche Produktprobleme erhöhen Retouren und Servicekosten?
- Welche Kundensegmente erzeugen Marge, aber eine schlechte Cash Conversion?

Die CEO-App erhält nur wenige aggregierte Indikatoren und wesentliche Ausnahmen.

Die operative Daily App erhält nur die heute betroffenen Aufträge und erforderlichen Aktionen.

Finance erhält Rechnungs-, Zahlungs- und Cash-Details.

Python erhält die vollständige Ereignishistorie, um verspätete Zahlungen oder Lieferungen zu prognostizieren.

Das gemeinsame Modell ist groß. Jede Nutzungsschicht bleibt fokussiert.

## Was vermieden werden sollte

### Eine physische Mega-Fact um jeden Preis

Eine einzige Fact-Tabelle ist nur sinnvoll, wenn Grain und Semantik kompatibel sind. Nicht zusammenpassende Facts zu erzwingen erzeugt Mehrdeutigkeit statt Integration.

### Eine App für jede Rolle

Rollen besitzen unterschiedliche Entscheidungen, Zeithorizonte, Berechtigungen und Detailbedarfe.

### Echtzeit für die vollständige Plattform

Nur ausgewählte Ereignisse und KPIs benötigen minimale Latenz.

### KPI-Logik als Kopie in jeder App

Geschäftslogik sollte zentral governed und wiederverwendet werden.

### Abteilungsmodelle ohne Enterprise-Ausrichtung

Unabhängige Fachmodelle erzeugen doppelte Kunden, inkompatible Kalender und widersprüchliche Kennzahlen.

### Unbegrenzte Feldfreigabe als „Self-Service“

Self-Service benötigt verständliche, kuratierte und vertrauenswürdige Daten — nicht nur Zugriff auf jede Spalte.

## Ein pragmatischer Implementierungsweg

### Schritt 1 — Entscheidungen statt Dashboards identifizieren

Für jede Rolle werden dokumentiert:

- wiederkehrende Fragen
- Entscheidungen
- Aktionen
- erforderliche Belege
- akzeptable Latenz
- notwendige Detailtiefe

### Schritt 2 — Abteilungs-Grains definieren

Es muss eindeutig beschrieben sein, was eine Fact-Zeile repräsentiert und welche Kennzahlen additiv, semi-additiv oder nicht additiv sind.

### Schritt 3 — gemeinsame Dimensionen etablieren

Gemeinsame Business-Entitäten, Identifikatoren, Hierarchien und Zeitlogik werden standardisiert.

### Schritt 4 — KPI-Definitionen govern-en

Formeln, Owner, zulässige Filter, Aggregationsverhalten und Zertifizierungsstatus werden festgelegt.

### Schritt 5 — die unternehmensweite Fact-Landschaft erstellen

Wo Grains zusammenpassen, kann eine Enterprise-Event-Fact verwendet werden. Wo sie nicht zusammenpassen, wird eine Fact Constellation aufgebaut.

### Schritt 6 — entscheidungsspezifische Datensätze bauen

Für jedes Nutzungsmuster werden Views, Aggregate, Extrakte oder semantische Perspektiven erstellt.

### Schritt 7 — Aktualität bewusst zuweisen

Realtime oder Streaming bleibt Signalen vorbehalten, bei denen die Latenz die Entscheidung verändert.

### Schritt 8 — Nutzung messen und Altlasten entfernen

Es wird gemessen, welche Apps, Felder und KPIs tatsächlich verwendet werden. Redundante Sichten und veraltete Logik werden entfernt, statt das Portfolio unbegrenzt wachsen zu lassen.

## Das Architekturprinzip

Das Ziel ist nicht, die Anzahl der Apps um jeden Preis zu minimieren.

Das Ziel ist, duplizierte Datenlogik zu minimieren und gleichzeitig die Klarheit von Entscheidungen zu maximieren.

> **Das Datenfundament einmal aufbauen.  
> Breit genug für unternehmensweite Wiederverwendung gestalten.  
> Jeder Entscheidung nur die Daten liefern, die sie wirklich benötigt.**

Ein governed Modell kann viele Fragen unterstützen.

Eine Universal-App kann es normalerweise nicht.
