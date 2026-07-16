---
title: KPI-Definition, Ownership und Versionierung — Vom Geschäftsprozess zur vertrauenswürdigen Kennzahl
description: Ein praktischer Deep Dive für Business User, Data Stewards und Data Architects: Welche Informationen eine KPI benötigt, wie sie aus einem Geschäftsprozess entsteht, wo ihre Berechnungslogik umgesetzt wird und wie Änderungen kontrolliert versioniert werden.
category: Data Governance
tags:
  - kpi-governance
  - metric-governance
  - data-stewardship
  - data-architecture
  - business-intelligence
  - semantic-layer
  - data-modeling
  - data-quality
  - versioning
  - ownership
  - analytics
order: -1
author: Thomas Lindackers
hero: images/playbooks/define-kpi-hero.png
---

## Eine Formel ist noch keine KPI

Viele Kennzahlen beginnen mit einer scheinbar einfachen Anforderung:

> „Wir brauchen die Lieferquote.“

Technisch lässt sich dafür schnell eine Formel formulieren:

`Pünktliche Lieferungen / alle Lieferungen`

Damit ist jedoch noch keine belastbare KPI definiert.

Offen bleiben unter anderem folgende Fragen:

- Was ist eine Lieferung: Auftrag, Auftragsposition, Versand oder abgeschlossener Liefervorgang?
- Welcher Termin gilt: zugesagter Termin, geplanter Termin oder bestätigter Kundentermin?
- Wie werden Teil-, Nach- und Stornolieferungen behandelt?
- In welche Periode gehört eine verspätet korrigierte Lieferung?
- Welche Länder, Produkte, Kunden und Kanäle gehören zum Scope?
- Welche Datenquelle ist maßgeblich?
- Wie wird mit fehlenden oder widersprüchlichen Terminen umgegangen?
- Wer darf die Definition ändern?
- Welche Version wurde in einem historischen Bericht verwendet?

Die Formel ist nur ein Bestandteil. Eine KPI ist ein **fachlich und technisch versionierter Vertrag** darüber, was gemessen wird, warum es gemessen wird, welche Daten dafür zulässig sind und wie das Ergebnis reproduziert werden kann.

> **Eine vertrauenswürdige KPI verbindet Geschäftsentscheidung, Governance und technische Umsetzung.**

## Drei Rollen definieren eine gemeinsame Kennzahl

Eine unternehmensweit verwendbare KPI kann nicht allein vom Fachbereich, vom Data Steward oder von der technischen Architektur definiert werden.

Jede Rolle liefert eine andere, notwendige Perspektive.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/define-kpi-img1-de.png"
        alt="Business User, Data Steward und Data Architect liefern unterschiedliche Informationen für einen gemeinsamen KPI-Vertrag"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Business User definiert Zweck und Entscheidung, der Data Steward kontrolliert Bedeutung und Governance, und der Data Architect stellt eine reproduzierbare technische Umsetzung sicher.
    </figcaption>
</figure>

### Business User oder Fachbereich

Der Fachbereich beginnt nicht mit Tabellen oder Formeln, sondern mit der fachlichen Realität.

Er muss erklären:

- welche Geschäftsfrage beantwortet werden soll
- welche Entscheidung oder Maßnahme die KPI beeinflusst
- welcher Geschäftsprozess gemessen wird
- welches Ergebnis als gut, kritisch oder unzulässig gilt
- welche Ziel- und Schwellenwerte relevant sind
- nach welchen Dimensionen analysiert werden soll
- welche fachlichen Ausnahmen existieren
- wie häufig die Kennzahl benötigt wird
- wer fachlich für Definition und Nutzung verantwortlich ist

Der Business User trägt nicht automatisch die Verantwortung für SQL, Datenmodell oder Performance. Er ist jedoch dafür verantwortlich, dass die KPI eine reale Entscheidung unterstützt und nicht nur eine technisch verfügbare Zahl erzeugt.

### Data Steward

Der Data Steward stabilisiert die Bedeutung der Kennzahl.

Er stellt sicher, dass:

- freigegebene Begriffe verwendet werden
- die fachliche Definition eindeutig dokumentiert ist
- Scope und Ausschlüsse nachvollziehbar sind
- Data Owner und Business Owner benannt sind
- Qualitätsanforderungen definiert wurden
- zulässige Werte und Klassifikationen konsistent sind
- Freigabe- und Änderungsprozesse eingehalten werden
- Zertifizierungsstatus und Gültigkeit sichtbar sind
- Dokumentation und Lineage gepflegt werden
- konkurrierende oder doppelte KPIs erkannt werden

Der Steward ist damit nicht nur Dokumentationsrolle. Er verbindet fachliche Bedeutung mit kontrollierter Nutzung.

### Data Architect

Der Data Architect übersetzt den KPI-Vertrag in eine technisch reproduzierbare Struktur.

Dazu gehören:

- messbarer Fakt oder messbares Ereignis
- Granularität beziehungsweise Grain
- autoritative Quellsysteme
- Keys, Dimensionen und Hierarchien
- Zeit-, Perioden- und Kalenderlogik
- Transformations- und Berechnungslogik
- Historisierung und Late-Arriving Data
- Berechnungsebene und Bereitstellungsform
- Tests und Reconciliation
- Performance, Skalierung und Wiederverwendung
- technische Lineage und Abhängigkeiten

Der Data Architect sollte keine fehlende Fachdefinition durch technische Annahmen ersetzen. Er muss solche Lücken sichtbar machen und zur Klärung zurückgeben.

## Der KPI-Vertrag ist die gemeinsame Arbeitsgrundlage

Der KPI-Vertrag ist kein juristischer Vertrag. Er ist ein kontrolliertes, versioniertes Governance-Objekt.

Er enthält mindestens:

| Bereich | Erforderliche Information | Primäre Verantwortung |
| --- | --- | --- |
| **Name** | Eindeutige Bezeichnung und gegebenenfalls Synonyme | Steward |
| **Zweck** | Warum existiert die KPI? | Business User |
| **Entscheidung** | Welche Entscheidung oder Aktion wird unterstützt? | Business User |
| **Geschäftsprozess** | Welcher Prozess wird abgebildet? | Business User |
| **Population** | Welche Fälle gehören zur Grundgesamtheit? | Business User / Steward |
| **Ausschlüsse** | Welche Fälle werden bewusst ausgeschlossen? | Business User / Steward |
| **Zähler** | Was gilt als Erfolg oder relevantes Ereignis? | Business User / Architect |
| **Nenner** | Was ist die vollständige Grundgesamtheit? | Business User / Architect |
| **Grain** | Auf welcher Ebene wird gezählt? | Architect |
| **Zeitlogik** | Welches Datum, welche Periode und welcher Kalender gelten? | Steward / Architect |
| **Dimensionen** | Welche Analyseachsen sind zugelassen? | Business User / Architect |
| **Quelle** | Welche Systeme und Datenobjekte sind maßgeblich? | Architect / Steward |
| **Qualitätsregeln** | Welche Mindestanforderungen gelten? | Steward / Architect |
| **Owner** | Wer verantwortet Bedeutung, Daten und Umsetzung? | Alle Rollen |
| **Version** | Welche Definition ist wirksam? | Steward / Architect |
| **Status** | Entwurf, geprüft, freigegeben, aktiv, veraltet oder stillgelegt | Steward |

Eine KPI ist erst dann vollständig definiert, wenn fachliche Bedeutung und technische Reproduktion zusammenpassen.

## Nicht mit der Formel beginnen — mit dem Geschäftsprozess beginnen

Der stabilste Weg zur KPI beginnt bei der Entscheidung.

```flow linear vertical
Geschäftsfrage
Entscheidung oder Maßnahme
Relevanter Geschäftsprozess
Messbares Geschäftsereignis
Population und Ausschlüsse
Zähler und Nenner
Granularität und Dimensionen
Zeit- und Periodenlogik
Quelle und Lineage
Qualität und Abgleich
Zielwerte und Schwellen
Ownership und Freigabe
Technische Umsetzung
Zertifizierung und Veröffentlichung [active]
```

### 1. Geschäftsfrage definieren

Eine gute Geschäftsfrage ist konkreter als der Name der Kennzahl.

Schwach:

> „Wie hoch ist unsere Lieferquote?“

Besser:

> „In welchen Regionen, Produktgruppen und Prozessschritten verfehlen wir zugesagte Liefertermine so häufig, dass operative Maßnahmen erforderlich sind?“

Die zweite Frage beschreibt bereits, dass die KPI:

- Abweichungen sichtbar machen soll
- nach relevanten Dimensionen analysierbar sein muss
- einen Prozessbezug benötigt
- eine Entscheidung oder Eskalation auslösen kann

### 2. Entscheidung und Aktion festlegen

Eine KPI ohne mögliche Entscheidung wird schnell zu dekorativem Reporting.

Mögliche Aktionen bei einer Liefer-KPI sind:

- kritische Lieferanten priorisieren
- Bestände oder Transportwege anpassen
- Kunden frühzeitig informieren
- operative Ursachen eskalieren
- Zielwerte nach Geschäftssegment differenzieren
- Kapazitäten in betroffenen Regionen erhöhen

Die Entscheidung bestimmt, welche Aktualität, Dimensionen, Schwellenwerte und Detailtiefe erforderlich sind.

### 3. Das messbare Ereignis identifizieren

Geschäftsprozesse bestehen aus Ereignissen.

Für eine Lieferung könnten das sein:

```flowchart
Bestellung
Zusage
Versand
Lieferung
Abschluss
```

Die KPI muss festlegen, welches dieser Ereignisse gemessen wird.

„Lieferung abgeschlossen“ kann beispielsweise bedeuten:

- erster Versand wurde zugestellt
- alle Auftragspositionen wurden geliefert
- Kunde hat die Lieferung bestätigt
- ERP-Status wurde auf abgeschlossen gesetzt
- Restmenge wurde storniert und der Auftrag geschlossen

Diese Varianten sind fachlich nicht identisch.

### 4. Grain vor Aggregation definieren

Der Grain beantwortet die Frage:

> Was repräsentiert ein einzelner zählbarer Datensatz?

Mögliche Ebenen sind:

- Auftrag
- Auftragsposition
- Versand
- Paket
- Lieferereignis
- Kundenbestätigung

Eine Lieferquote auf Auftragsebene kann anders ausfallen als auf Positionsebene. Ein Auftrag mit zehn Positionen und einer verspäteten Position zählt auf Auftragsebene möglicherweise vollständig als verspätet. Auf Positionsebene wären neun von zehn Positionen pünktlich.

Die Formel kann gleich aussehen. Die Bedeutung ist unterschiedlich.

## Vom Prozess zur technischen KPI

Nach der fachlichen Definition wird der reale Prozess in ein kontrolliertes Datenprodukt übersetzt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/define-kpi-img2-de.png"
        alt="Vom Geschäftsprozess über das messbare Ereignis und den KPI-Vertrag zum technischen Modell und den Nutzungskanälen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der KPI-Vertrag bildet die verbindende Schicht zwischen Geschäftsrealität und technischer Umsetzung. Dashboards, Alerts, Anwendungen, KI und Reports nutzen anschließend dieselbe Definition.
    </figcaption>
</figure>

Der Weg sollte nicht direkt von einer Quelltabelle in eine Visualisierung führen.

```flowchart
Geschäftsprozess
Messbares Ereignis
KPI-Vertrag
Technisches Modell
Nutzung
```

### Geschäftsprozess

Der Fachbereich beschreibt die reale Abfolge und die fachlich relevanten Zustände.

### Messbares Ereignis

Business User, Steward und Architect einigen sich darauf, welches Ereignis die Realität ausreichend repräsentiert.

### KPI-Vertrag

Definition, Scope, Formel, Grain, Zeitlogik, Quellen, Qualität, Owner, Version und Status werden verbindlich festgelegt.

### Technisches Modell

Fakten, Dimensionen, Transformationen, Tests und semantische Bereitstellung setzen den Vertrag um.

### Nutzung

Die KPI kann in verschiedenen Kanälen verwendet werden:

- Dashboard
- Alert
- operative Anwendung
- Standardreport
- Ad-hoc-Analyse
- API
- semantische Schicht
- AI- oder ML-Anwendung

Die Nutzungskanäle dürfen die Darstellung verändern. Sie sollten nicht unbemerkt die Kernbedeutung verändern.

## Beispiel: On-Time Delivery Rate

Für das durchgehende Beispiel könnte der KPI-Vertrag so aussehen:

| Vertragselement | Beispieldefinition |
| --- | --- |
| **Name** | On-Time Delivery Rate |
| **Zweck** | Einhaltung verbindlicher Lieferzusagen überwachen |
| **Entscheidung** | Ursachen priorisieren und operative Maßnahmen auslösen |
| **Population** | Alle geplanten, im Berichtszeitraum abgeschlossenen Lieferungen |
| **Zähler** | Lieferungen mit tatsächlichem Lieferdatum am oder vor dem zugesagten Datum |
| **Nenner** | Alle gültigen geplanten Lieferungen im Scope |
| **Grain** | Lieferung |
| **Ausschlüsse** | Stornierte Aufträge, Testaufträge, vollständig abgelehnte Lieferungen |
| **Zeitlogik** | Periode nach tatsächlichem Lieferdatum |
| **Quelle** | ERP-Vertrieb und Logistik |
| **Qualitätsregel** | Zugesagtes und tatsächliches Lieferdatum müssen vorhanden und plausibel sein |
| **Business Owner** | Head of Supply Chain |
| **Data Steward** | Supply Chain Data Steward |
| **Technischer Owner** | Analytics Engineering |
| **Version** | 1.0.0 |
| **Status** | Freigegeben |

Die Definition ist bewusst ausführlicher als die Formel. Genau diese zusätzlichen Informationen machen die Kennzahl reproduzierbar.

## Zentral definierte KPI und dynamische Formel sind nicht dasselbe

Der Begriff „statische KPI“ kann missverständlich sein.

Der Wert einer zentral definierten KPI ist nicht statisch. Er verändert sich mit neuen Daten, Perioden und Filtern.

Statisch beziehungsweise stabil ist die **vereinbarte Kernlogik**:

- Population
- Zähler
- Nenner
- Grain
- Zeitlogik
- Quellen
- Ausschlüsse
- Qualitätsregeln
- Version

Eine dynamische Formel wird dagegen im Nutzungskontext erzeugt oder verändert, beispielsweise durch:

- Qlik Set Analysis
- Excel-Formeln
- Power-BI-Measures
- Tableau Calculations
- SQL in einzelnen Reports
- benutzerdefinierte Filter
- AI-generierte Berechnungen
- lokale App-Logik

Dynamische Formeln sind nicht grundsätzlich falsch. Sie sind für Exploration, Simulation und kontextbezogene Analyse wichtig.

Das Risiko entsteht, wenn sie eine freigegebene KPI unbemerkt neu definieren.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/define-kpi-img3-de.png"
        alt="Ein gesteuerter KPI-Kern wird mit einem kontrollierten dynamischen Analysekontext kombiniert, während ein ungesteuerter Formelpfad zu nicht vergleichbaren Ergebnissen führt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Dynamik sollte auf einem stabilen KPI-Kern aufbauen. Zeit, Region, Produkt oder Szenario können flexibel gewählt werden, ohne Population, Grain oder Berechnungsbedeutung heimlich zu verändern.
    </figcaption>
</figure>

## Der richtige Zielzustand: stabiler Kern, kontrollierter Kontext

Eine gute semantische oder analytische Architektur trennt zwei Ebenen.

### Gesteuerter KPI-Kern

Fest definiert und versioniert:

- Zweck und Definition
- Population und Ausschlüsse
- Zähler und Nenner
- Grain
- Zeitlogik
- Quelle und Lineage
- Qualitätsregeln
- Version und Status

### Kontrollierter Analysekontext

Zur Laufzeit auswählbar:

- Monat, Quartal, Jahr
- Region, Land, Stadt
- Produkt, Kategorie, SKU
- Kundensegment
- Kanal
- Plan, Ist, Forecast, What-if
- Vorjahr, Vormonat, Zielvergleich

Das Ergebnis bleibt dynamisch, aber vergleichbar.

```flowchart
Zertifizierter KPI-Kern
Zugelassene Dimensionen
Kontrollierte Filter
Dynamische Analyse
```

Ein Business User kann beispielsweise die Lieferquote für EMEA, Q2 und eine Produktgruppe analysieren. Er verändert damit den Kontext, nicht die Definition von „pünktlich“.

Wird dagegen aus „am oder vor dem zugesagten Termin“ lokal „bis drei Tage nach dem Termin“ gemacht, entsteht eine andere KPI oder eine neue KPI-Version.

## Wo sollte die KPI berechnet werden?

Es gibt keine universelle Berechnungsebene. Die richtige Ebene hängt von Wiederverwendung, Kritikalität, Komplexität, Aktualität und Tool-Landschaft ab.

```flow linear vertical
Quellsystem
Warehouse-Transformation
Semantische oder Metric-Schicht
BI Master Measure
Anwendungsspezifische Formel
Benutzerdefinierte Analyse
```

### Quellsystem

Geeignet, wenn die Kennzahl integraler Bestandteil eines operativen Prozesses ist und das Quellsystem ihre Bedeutung verbindlich kontrolliert.

Risiken:

- begrenzte historische Logik
- schwerere Wiederverwendung
- unterschiedliche Quellsystemdefinitionen
- operative und analytische Anforderungen vermischen sich

### Warehouse oder Lakehouse

Geeignet für:

- komplexe Transformationen
- Historisierung
- mehrere Quellsysteme
- wiederverwendbare Fakten
- periodische Snapshots
- Datenqualitätsprüfungen
- systemübergreifende Harmonisierung

Die zentrale Logik sollte versioniert, getestet und dokumentiert sein.

### Semantische oder Metric-Schicht

Geeignet für:

- gemeinsame Kennzahlendefinitionen
- kontrolliertes Aggregationsverhalten
- zugelassene Dimensionen und Filter
- Wiederverwendung durch mehrere Tools
- zentrale fachliche Metadaten

Diese Ebene kann die Brücke zwischen Datenmodell und flexibler Nutzung bilden.

### BI Master Measure

Geeignet, wenn die Plattform zertifizierte Master Measures unterstützt und mehrere Apps dieselbe Definition kontrolliert wiederverwenden.

Dabei muss verhindert werden, dass jede App eine private Kopie mit leicht veränderter Logik erhält.

### Anwendungsspezifische oder benutzerdefinierte Formel

Geeignet für:

- Exploration
- temporäre Hypothesen
- persönliche Szenarien
- nicht zertifizierte Analysen
- lokale Präsentationslogik

Sie sollte klar als lokal, experimentell oder nicht zertifiziert gekennzeichnet sein.

> **Je geschäftskritischer und häufiger wiederverwendet eine KPI ist, desto zentraler sollte ihre Kernlogik kontrolliert werden.**

## Prozess für eine neue KPI

Eine neue KPI sollte einen erkennbaren Lebenszyklus durchlaufen.

```flowchart
Vorschlagen
Definieren
Validieren
Umsetzen
Testen
Freigeben
Veröffentlichen
Überwachen
```

### Vorschlagen

Der Antrag enthält mindestens:

- Geschäftsfrage
- Entscheidung oder Aktion
- erwarteter Nutzen
- Business Owner
- Zielgruppe
- benötigte Aktualität

### Definieren

Business User, Steward und Architect klären:

- Prozess und Ereignis
- Population
- Zähler und Nenner
- Grain
- Scope und Ausschlüsse
- Zeitlogik
- Dimensionen
- Zielwerte
- Qualitätsanforderungen

### Validieren

Vor der Entwicklung wird geprüft:

- Existiert bereits dieselbe oder eine ähnliche KPI?
- Gibt es widersprüchliche Begriffe?
- Sind die Daten verfügbar?
- Reicht die Datenqualität für den Verwendungszweck?
- Ist die gewünschte Granularität technisch vorhanden?
- Ist die Kennzahl mit anderen KPIs konsistent?
- Sind regulatorische oder vertragliche Regeln betroffen?

### Umsetzen

Die technische Umsetzung umfasst je nach Architektur:

- Facts und Dimensions
- Transformationsmodelle
- Snapshots
- Semantic Layer oder Metric Definition
- Master Measures
- APIs oder Data Products
- Metadaten und Lineage

### Testen

Tests sollten mehr als einen korrekten Durchschnittswert prüfen:

- Unit Tests
- Schema- und Datentests
- Reconciliation mit Quellen
- historische Perioden
- Grenz- und Ausnahmefälle
- Null- und Fehlerfälle
- Aggregationsverhalten
- Performance
- Vergleich mit bestehenden Reports

### Freigeben

Eine produktive KPI benötigt mindestens:

- fachliche Freigabe
- Steward-Freigabe
- technische Freigabe
- dokumentiertes Wirksamkeitsdatum
- benannte Owner
- definierten Zertifizierungsstatus

### Veröffentlichen

Die KPI wird dort sichtbar gemacht, wo Nutzer sie finden und verstehen können:

- Datenkatalog
- Semantic Layer
- BI-Plattform
- Governance Hub
- API-Dokumentation
- KPI-Register

### Überwachen

Nach der Veröffentlichung werden beobachtet:

- Datenqualität
- Aktualität
- Nutzung
- Abweichungen zwischen Tools
- wiederkehrende Nutzerfragen
- Performance
- Änderungsbedarf
- neue fachliche Ausnahmen

## Eine vorhandene KPI niemals still überschreiben

Ändert sich die Bedeutung einer KPI, darf die alte Definition nicht einfach ersetzt werden.

Sonst verlieren historische Reports ihre Reproduzierbarkeit. Dieselbe KPI-Bezeichnung kann dann je nach Ausführungszeitpunkt unterschiedliche Ergebnisse liefern.

Der Änderungsprozess beginnt deshalb mit einer Klassifikation.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/define-kpi-img4-de.png"
        alt="Lebenszyklus für KPI-Änderungen von der Änderungsanfrage über Klassifikation, Impact-Analyse, Umsetzung und Freigabe bis zur Deprezierung der alten Version"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Bedeutungsänderungen erzeugen neue Versionen. Impact-Analyse, Parallelbetrieb, Wirksamkeitsdatum und Kommunikation verhindern, dass dieselbe KPI unbemerkt verschiedene Wahrheiten repräsentiert.
    </figcaption>
</figure>

## Änderungstypen und Versionierung

Eine dreiteilige Versionsnummer kann als pragmatische Governance-Konvention verwendet werden:

`MAJOR.MINOR.PATCH`

Sie ist kein verpflichtender universeller Branchenstandard. Entscheidend ist, dass die Organisation eine klare, konsistente Regel definiert.

### Patch Change

Keine Änderung der fachlichen Bedeutung oder des Ergebnisses.

Beispiele:

- Tippfehler korrigieren
- Beschreibung verbessern
- Übersetzung ergänzen
- Link oder Ansprechpartner aktualisieren
- Dokumentation präzisieren

Beispiel:

`1.0.0 → 1.0.1`

### Minor Change

Rückwärtskompatible Erweiterung, die bestehende Ergebnisse nicht verändert.

Beispiele:

- zusätzliche optionale Dimension
- weitere beschreibende Metadaten
- neuer zertifizierter Drill-down
- zusätzlicher Qualitätsindikator
- neue Darstellungsform auf derselben Definition

Beispiel:

`1.0.0 → 1.1.0`

### Major Change

Änderung der fachlichen Bedeutung, Population oder Berechnung.

Beispiele:

- anderer Zähler oder Nenner
- veränderter Scope
- neue Ausschlüsse
- anderer Grain
- andere Zeitlogik
- andere autoritative Quelle
- geänderte Storno- oder Teillieferungslogik
- veränderte historische Behandlung

Beispiel:

`1.0.0 → 2.0.0`

| Änderung | Typische Version | Historische Werte | Freigabe | Parallelbetrieb |
| --- | --- | --- | --- | --- |
| Beschreibung korrigiert | Patch | unverändert | Steward | nein |
| Optionale Dimension ergänzt | Minor | unverändert | Steward + Architect | optional |
| Formel geändert | Major | potenziell verändert | Business Owner + Steward + Architect | empfohlen |
| Grain geändert | Major | verändert | vollständige Freigabe | erforderlich |
| Quelle ersetzt | Minor oder Major nach Auswirkung | prüfen | Steward + Architect | empfohlen |
| Zielwert geändert | separate Zielversion | KPI-Wert unverändert | Business Owner | meist nein |

## KPI-Definition und Zielwert getrennt versionieren

Eine häufige Fehlerquelle ist die Vermischung von Messdefinition und Zielvorgabe.

Die KPI kann unverändert bleiben, während sich das Ziel ändert.

Beispiel:

- KPI-Version: `2.0.0`
- Zielwert 2026: `95 %`
- Zielwert 2027: `97 %`

Der Messwert bleibt vergleichbar. Nur die Bewertung verändert sich.

Zielwerte benötigen deshalb eigene Metadaten:

- gültig von
- gültig bis
- Organisationseinheit
- Produkt oder Segment
- Szenario
- Freigabestatus
- Owner
- Begründung

## Impact-Analyse vor jeder Bedeutungsänderung

Eine Major-Änderung betrifft häufig mehr als einen Report.

Zu prüfen sind:

- Dashboards und Standardberichte
- Alerts und Schwellenwerte
- operative Anwendungen
- APIs und Datenverträge
- SLAs und Verträge
- Managementziele
- Bonus- oder Steuerungsmodelle
- abhängige KPIs
- historische Vergleiche
- Planungs- und Forecast-Modelle
- AI- und ML-Produkte
- Schulungs- und Dokumentationsmaterial
- externe oder regulatorische Berichte

Die technische Lineage zeigt, wo die Kennzahl verwendet wird. Die fachliche Impact-Analyse erklärt, welche Entscheidungen davon betroffen sind.

## Parallelbetrieb für Major-Versionen

Bei einer Bedeutungsänderung sollten alte und neue Version für eine definierte Übergangszeit parallel verfügbar sein.

```flowchart
Alte Version aktiv
Neue Version im Test
Parallelvergleich
Neue Version freigegeben
Alte Version veraltet
Alte Version stillgelegt
```

Der Parallelbetrieb ermöglicht:

- Ergebnisvergleich
- Erklärung von Abweichungen
- Migration von Reports
- Anpassung von Zielwerten
- Prüfung historischer Auswirkungen
- Kommunikation an Nutzer
- Rückfallmöglichkeit bei Fehlern

Die alte Version sollte nicht unbegrenzt weiterlaufen. Sie benötigt ein geplantes Enddatum.

## Effective Dating und historische Reproduzierbarkeit

Jede KPI-Version benötigt mindestens:

```flow linear vertical
Versionskennung
Status
Gültig von
Gültig bis
Business Owner
Data Steward
Technischer Owner
Freigabedatum
Definitions-Snapshot
Implementierungsreferenz
Vorgänger und Nachfolger
```

Ein sinnvolles Statusmodell ist:

```flowchart
Entwurf
Prüfung
Freigegeben
Aktiv
Veraltet
Stillgelegt
```

Historische Berichte können auf zwei Arten dargestellt werden.

### As reported at the time

Der Bericht verwendet die KPI-Version, die zum damaligen Berichtszeitpunkt gültig war.

Das ist wichtig für:

- Audit
- regulatorische Nachweise
- damalige Managemententscheidungen
- vertragliche Berichte
- historische Reproduktion

### Restated using current definition

Historische Daten werden mit der aktuellen KPI-Definition neu berechnet.

Das ist sinnvoll für:

- langfristige Trendvergleiche
- harmonisierte Managementsicht
- Modell- und Methodenwechsel
- konsistente Analyse über mehrere Jahre

Beide Varianten sind legitim. Sie müssen sichtbar gekennzeichnet sein.

## Was bei dynamischen Formeln erlaubt sein sollte

Governance sollte analytische Freiheit nicht vollständig entfernen.

Ein kontrolliertes Modell kann zertifizierte Bausteine bereitstellen:

- On-Time Deliveries
- All Valid Deliveries
- Delivery Delay Days
- Cancelled Deliveries
- Planned Delivery Date
- Actual Delivery Date
- freigegebene Dimensionen
- freigegebene Kalender

Darauf dürfen Nutzer:

- Zeiträume vergleichen
- Regionen filtern
- Produktgruppen analysieren
- Szenarien simulieren
- lokale Visualisierungen erstellen
- temporäre Hypothesen testen

Eine lokale Formel sollte jedoch gekennzeichnet werden, wenn sie:

- die Population verändert
- einen anderen Nenner verwendet
- die Zeitlogik überschreibt
- nicht freigegebene Datenquellen einbindet
- Ausnahmen anders behandelt
- eine zertifizierte KPI unter gleichem Namen ersetzt

Mögliche Statuskennzeichnungen sind:

- Certified
- Governed derivative
- Local analysis
- Experimental
- Deprecated

So bleibt Self-Service möglich, ohne jede lokale Berechnung zur Unternehmenswahrheit zu erklären.

## Minimaler Governance-Prozess

Nicht jede Organisation benötigt sofort ein komplexes KPI-Tool.

Ein funktionierendes Minimum besteht aus:

```flowchart
Zentrales KPI-Register
Klare Rollen
Versionierte Definition
Freigabeprozess
Lineage
Nutzungskennzeichnung
Monitoring
```

Das KPI-Register kann zunächst in einem Katalog, einem Git-Repository, einer Governance-Anwendung oder einer kontrollierten Markdown-Struktur geführt werden.

Wichtiger als das Produkt sind:

- eine eindeutige ID
- eine zugängliche Definition
- benannte Owner
- klare Statuswerte
- nachvollziehbare Versionen
- verknüpfte technische Artefakte
- sichtbare Abhängigkeiten
- dokumentierte Änderungen

## Praktische Freigabe-Checkliste

Vor der Aktivierung einer KPI sollten folgende Fragen beantwortet sein.

### Fachlich

- Welche Entscheidung unterstützt die KPI?
- Welcher Prozess und welches Ereignis werden gemessen?
- Sind Population, Zähler und Nenner eindeutig?
- Sind Grain, Zeitlogik und Ausschlüsse verständlich?
- Sind Zielwert und KPI-Definition getrennt?
- Ist ein Business Owner benannt?

### Governance

- Existiert bereits eine ähnliche KPI?
- Sind Begriffe und Klassifikationen freigegeben?
- Sind Qualitätsanforderungen definiert?
- Ist die Zertifizierung dokumentiert?
- Ist der Änderungsprozess festgelegt?
- Sind Gültigkeit und Version sichtbar?

### Technisch

- Sind Quellen und Lineage bekannt?
- Ist der Grain im Modell eindeutig?
- Sind Transformation und Berechnung versioniert?
- Sind Tests und Reconciliation vorhanden?
- Ist die KPI über mehrere Nutzungskanäle konsistent?
- Sind Performance und Aktualität ausreichend?

### Nutzung

- Ist erkennbar, welche Filter zulässig sind?
- Sind lokale Formeln gekennzeichnet?
- Können Nutzer die Definition erreichen?
- Sind Alerts und Zielwerte mit der richtigen Version verbunden?
- Werden Nutzung, Qualität und Abweichungen überwacht?

## Die zentrale Regel

Eine KPI sollte nicht als Formel behandelt werden, die ein Data Architect einmal baut und ein Business User anschließend verwendet.

Sie ist ein gemeinsames, dauerhaft gepflegtes Governance-Objekt.

Der Business User liefert Zweck und Entscheidung.  
Der Data Steward kontrolliert Bedeutung, Verantwortung und Freigabe.  
Der Data Architect stellt Reproduzierbarkeit, Skalierung und technische Konsistenz sicher.

Dynamische Analyse bleibt möglich, solange sie auf einem kontrollierten Kern aufbaut.

Ändert sich die Bedeutung, entsteht eine neue Version.

> **Eine vertrauenswürdige KPI ist nicht nur korrekt berechnet. Sie ist eindeutig definiert, kontrolliert verändert und historisch reproduzierbar.**

## Verwandte Playbooks

- [The Missing Pieces – Part 1: Data Quality](/playbooks/missing-pieces-data-quality) — warum fehlerhafte oder unvollständige Quelldaten nicht nur im Reporting repariert werden sollten
- [Data Quality Governance](/playbooks/data-quality-governance) — wie Qualitätsregeln, Ownership, Monitoring und Verbesserung als Betriebsmodell zusammenwirken
- [Eine App kann nicht jede Frage beantworten](/playbooks/one-app) — warum mehrere fokussierte Anwendungen dieselben governed Fakten und KPI-Definitionen wiederverwenden sollten
