---
title: Warum 10 Milliarden Zeilen für jede Frage verarbeiten?
description: Warum eine Datenplattform vollständige Historie und Detailtiefe bewahren sollte, während Apps und Decision Products nur die Daten verarbeiten und laden, die eine konkrete Entscheidung tatsächlich benötigt.
category: Architektur
tags:
  - data-architecture
  - business-intelligence
  - decision-data
  - decision-products
  - data-modeling
  - semantic-layer
  - performance
  - cost-efficiency
  - data-governance
  - analytics
order: -1
author: Thomas Lindackers
hero: images/playbooks/one-billion-hero.png
publishedAt: 2026-07-16 14:00
---

## Mehr Daten sind nicht automatisch mehr Entscheidung

Moderne Datenplattformen können Milliarden Transaktionen, Ereignisse und historische Zustände speichern. Diese Fähigkeit ist wertvoll. Sie ermöglicht detaillierte Analysen, langfristige Vergleiche, regulatorische Nachweise, Forecasting, Machine Learning und eine nachvollziehbare Rekonstruktion geschäftlicher Abläufe.

Das Problem beginnt nicht bei der Größe der Plattform.

Es beginnt, wenn **jede Frage so behandelt wird, als müsste sie erneut die vollständige Plattform verarbeiten**.

Ein CEO-Dashboard benötigt vielleicht zwölf zentrale Kennzahlen und einige wesentliche Abweichungen. Eine operative App benötigt die Aufträge, Fälle oder Risiken, die heute eine Aktion erfordern. Finance benötigt einen anderen Ausschnitt als Vertrieb, Supply Chain oder Compliance.

Trotzdem entsteht häufig dieses Muster:

- möglichst viele Daten werden in ein universelles Modell geladen
- das Modell wird in eine möglichst große App übernommen
- jede Rolle erhält dieselben Dimensionen, Historien und Detailtabellen
- jede neue Frage erweitert das Modell und die App weiter
- Performance-Probleme werden primär mit mehr Rechenleistung beantwortet

Das Ergebnis ist technisch beeindruckend, aber fachlich oft unscharf.

> **Die Datenplattform muss Big Data beherrschen. Die Entscheidungsebene muss daraus Decision Data machen.**

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/one-billion-img1-de.png"
        alt="Zehn Milliarden Zeilen fließen durch ein riesiges Datenmodell und eine universelle App zu unterschiedlichen Rollen, während das Ergebnis langsam, komplex, teuer und unklar wird"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Werden vollständige Historie, sämtliche Details und jede mögliche Fragestellung in dieselbe App gedrückt, steigt die technische Last schneller als der Entscheidungswert.
    </figcaption>
</figure>

## Die Zahl ist ein Bild — nicht die vollständige Kostenformel

„10 Milliarden Zeilen“ ist in dieser Story bewusst ein plakatives Beispiel.

In einer modernen spaltenorientierten Plattform entscheidet nicht allein die Anzahl der vorhandenen Zeilen über Laufzeit und Kosten. Relevant sind unter anderem:

- tatsächlich gelesene Bytes und Spalten
- Partitionierung und Pruning
- Filter-Pushdown
- Join-Komplexität und Kardinalität
- Aggregationsgrad
- Materialisierung und Caching
- Parallelität und Anzahl gleichzeitiger Nutzer
- Datenbewegung zwischen Plattform, Gateway und App
- Aktualisierungsfrequenz und inkrementelle Verarbeitung

Eine gute Plattform kann eine Abfrage auf Milliarden Zeilen sehr effizient ausführen, wenn nur relevante Partitionen und Spalten gelesen werden.

Die Kernfrage bleibt trotzdem bestehen:

> **Warum sollte dieselbe breite Verarbeitung für jede wiederkehrende Entscheidung erneut notwendig sein?**

Werden zehn Milliarden Zeilen für fünfzig ähnliche Dashboards oder Reloads verarbeitet, entsteht nicht automatisch fünfzigmal mehr Erkenntnis. Es entsteht zunächst fünfzigmal mehr technische Aktivität.

Die Architektur sollte deshalb nicht nur fragen, ob eine Abfrage technisch möglich ist. Sie sollte fragen, **welcher Datenausschnitt für den jeweiligen Entscheidungsprozess erforderlich ist**.

## Big Data bleibt wichtig

Decision Data bedeutet nicht, historische oder detaillierte Daten zu löschen.

Die vollständige Datenbasis bleibt wichtig für:

- Auditierbarkeit und Nachweise
- Ursachenanalysen und Drill-down
- Data Science und Machine Learning
- Forecasting und Simulation
- neue, heute noch unbekannte Fragestellungen
- regulatorische Aufbewahrung
- Rekonstruktion von Geschäftsereignissen
- Modelltraining und Feature Engineering
- domänenübergreifende Analysen

Die Plattform muss diese Anforderungen erfüllen können.

Aber nicht jede Rolle benötigt dieselbe Detailtiefe zur selben Zeit.

Ein Data Scientist kann Jahre detaillierter Ereignisse benötigen. Ein Operations Manager benötigt vielleicht nur die heutigen Ausnahmen. Der CEO benötigt eventuell nur wenige aggregierte Kennzahlen, ihre Treiber und die wichtigsten Risiken.

Das ist kein Widerspruch. Es sind unterschiedliche **Consumption Contracts** auf demselben governed Fundament.

## Drei Ebenen mit drei unterschiedlichen Aufgaben

Die Architektur wird klarer, wenn drei Ebenen getrennt betrachtet werden.

### 1. Enterprise-Datenplattform

Sie bewahrt Breite, Historie und Detail.

Typische Inhalte sind:

- Transaktionen
- Ereignisse
- Dokumentmetadaten
- externe Daten
- historische Zustände
- technische Audit- und Lineage-Informationen
- Rohdaten und harmonisierte Daten

Diese Ebene darf groß sein. Ihre Aufgabe ist Wiederverwendbarkeit und Nachvollziehbarkeit.

### 2. Gemeinsame governed Fakten und Dimensionen

Hier entsteht fachliche Konsistenz.

Diese Ebene definiert:

- konforme Dimensionen
- Business Facts mit eindeutigem Grain
- harmonisierte Schlüssel
- semantische Definitionen
- zertifizierte Kennzahlen
- Data-Quality-Regeln
- Sensitivitäts- und Zugriffsmetadaten
- Lineage und Ownership

Hier wird aus gespeicherten Daten ein verlässliches Unternehmensmodell.

### 3. Rollenbasierte Decision Products

Sie beantworten konkrete Fragen für konkrete Prozesse.

Beispiele:

- CEO-Tagesüberblick
- Finance Performance
- Vertriebssteuerung
- Supply-Chain-Risiken
- Kundenaktionen
- Compliance-Warnungen

Ein Decision Product ist kein beliebiger Export. Es ist ein fachlich verantworteter und technisch kontrollierter Datenausschnitt mit klarer Aufgabe.

```flow linear vertical
Enterprise-Datenplattform
Gemeinsame governed Fakten und Dimensionen
Abteilungs- und Domänenmodelle
Rollenbasierte Decision Products [active]
```

## Von Enterprise Data zu Decision Data

Die Lösung besteht nicht darin, viele voneinander getrennte Schattenmodelle zu bauen.

Sie besteht darin, **ein gemeinsames governed Fundament mehrfach und gezielt zu nutzen**.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/one-billion-img2-de.png"
        alt="Eine Enterprise-Datenplattform wird über gemeinsame governed Fakten und Dimensionen sowie Abteilungs- und Domänenmodelle in rollenbasierte Decision Products überführt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die vollständige Datenbasis bleibt erhalten. Erst an der Nutzungsschicht werden Inhalt, Detailtiefe, Aktualität und Zugriff auf die konkrete Entscheidung zugeschnitten.
    </figcaption>
</figure>

Die Reduktion erfolgt kontrolliert:

- **fachlich**, indem nur relevante Prozesse und Kennzahlen ausgewählt werden
- **zeitlich**, indem nur der notwendige Zeitraum geladen wird
- **organisatorisch**, indem nur relevante Bereiche und Verantwortungen enthalten sind
- **technisch**, indem Spalten, Zeilen und Aggregate gezielt materialisiert werden
- **sicherheitsbezogen**, indem nur erlaubte Daten sichtbar sind
- **visuell**, indem nur die Informationen dargestellt werden, die eine Entscheidung unterstützen

Das Decision Product reduziert nicht die Wahrheit. Es reduziert die Menge an Wahrheit, die für einen bestimmten Moment gleichzeitig verarbeitet und präsentiert werden muss.

## Ein CEO benötigt nicht die vollständige Faktentabelle

Angenommen, eine Plattform enthält zehn Milliarden Transaktionen.

Der tägliche CEO-Blick könnte trotzdem nur benötigen:

- zwölf zentrale KPIs
- die größten Plan-Ist-Abweichungen
- wenige kritische Geschäftsbereiche
- relevante Risiken und Ausnahmen
- die wichtigsten Treiber
- einen Link oder Drill-through zur Erklärung
- eine klar zugeordnete Verantwortung
- eine mögliche nächste Entscheidung

Die App muss dafür nicht jede Transaktionszeile vollständig laden.

Sie benötigt möglicherweise nur:

- einen zertifizierten Periodensnapshot
- vorberechnete Kennzahlen
- eine kleine Ausnahmentabelle
- aggregierte Treiber
- Referenzschlüssel für den kontrollierten Drill-down

Die vollständigen Details bleiben in der Plattform verfügbar. Sie werden erst dann gelesen, wenn eine konkrete Abweichung untersucht wird.

> **Übersicht zuerst. Relevanter Drill-down danach. Vollständiges Detail nur bei tatsächlichem Analysebedarf.**

## Progressive Detailtiefe statt maximaler Erstladung

Viele Anwendungen behandeln maximale Detailtiefe als Qualitätsmerkmal. Dabei ist sie häufig nur dann wertvoll, wenn sie im richtigen Moment verfügbar ist.

Eine sinnvolle Staffelung kann so aussehen:

### Stufe 1 — Überblick

Die App zeigt:

- wenige KPIs
- Status und Trend
- Abweichungen
- Risiken
- notwendige Entscheidungen

### Stufe 2 — fachlicher Drill-down

Nach Auswahl einer Abweichung werden geladen:

- betroffene Region
- Geschäftsbereich
- Produktgruppe
- Kundensegment
- Prozessstufe
- verantwortliche Organisation

### Stufe 3 — operative Untersuchung

Erst jetzt werden bei Bedarf angezeigt:

- konkrete Aufträge
- Rechnungen
- Lieferungen
- Ereignisse
- Buchungen
- Einzelfälle

### Stufe 4 — Quellnachweis

Für Audit oder Fehleranalyse bleibt der Weg bis zum Quellsatz nachvollziehbar.

Diese Architektur verbindet Klarheit mit Analysefähigkeit. Sie verhindert, dass die erste Bildschirmansicht gleichzeitig Executive Dashboard, Analysewerkzeug, Auditbericht und Rohdatenbrowser sein muss.

## Der Filter ist ein fachlicher Vertrag

Ein entscheidungsspezifischer Datenausschnitt darf nicht nur durch zufällige technische Filter entstehen.

Er sollte dokumentieren:

| Vertragsbestandteil | Leitfrage |
| --- | --- |
| **Zweck** | Welche Entscheidung oder Aktion unterstützt das Produkt? |
| **Zielrolle** | Wer nutzt es und welche Verantwortung besitzt diese Rolle? |
| **Kennzahlen** | Welche Definitionen sind verbindlich und zertifiziert? |
| **Grain** | Was repräsentiert eine Zeile oder ein Ereignis? |
| **Zeitraum** | Welche Historie wird in der Erstansicht benötigt? |
| **Aktualität** | Muss der Stand realtime, stündlich, täglich oder periodisch sein? |
| **Ausnahmen** | Welche Grenzwerte und Abweichungen werden hervorgehoben? |
| **Zugriff** | Welche Zeilen, Spalten und Detailstufen sind erlaubt? |
| **Drill-down** | Wie gelangt der Nutzer kontrolliert zur Erklärung? |
| **Owner** | Wer verantwortet Definition, Qualität und Weiterentwicklung? |

Damit wird eine kleine App nicht zu einem isolierten Datenmart. Sie bleibt eine kontrollierte Perspektive auf das gemeinsame Modell.

## Technische Muster für Decision Data

Es gibt nicht nur eine technische Umsetzung. Je nach Plattform und Nutzung können unterschiedliche Muster kombiniert werden.

### Governed Views

Views kapseln Filter, Joins und Fachlogik, ohne sofort zusätzliche physische Kopien zu erzeugen.

Sie eignen sich, wenn:

- die Abfrage ausreichend schnell ist
- Aktualität wichtig ist
- die Logik zentral bleiben soll
- mehrere Konsumenten denselben Ausschnitt verwenden

### Materialisierte Views und Aggregationstabellen

Wiederkehrende, rechenintensive Abfragen können vorberechnet werden.

Geeignet für:

- Daily- und Management-KPIs
- häufig verwendete Periodenaggregate
- definierte Hierarchien
- große Join- und Aggregationsschritte
- stabile Reporting-Zyklen

### App-spezifische Extrakte

Ein Extrakt kann sinnvoll sein, wenn eine BI-Anwendung eine lokale, schnelle und kontrollierte Datenmenge benötigt.

Entscheidend ist:

- Die KPI-Logik bleibt governed.
- Der Extrakt besitzt einen definierten Zweck.
- Aktualisierung und Retention sind dokumentiert.
- Der Extrakt wird nicht zur unabhängigen Schattenwahrheit.

### Semantische Modelle und Metric Layer

Sie stellen gemeinsame Dimensionen, Kennzahlen und Filterverhalten bereit.

Dadurch können mehrere kleine Decision Products dieselbe fachliche Definition wiederverwenden, ohne die Logik in jeder App neu zu implementieren.

### Inkrementelle Verarbeitung

Nicht jede Aktualisierung muss die vollständige Historie neu berechnen.

Mögliche Strategien sind:

- neue oder veränderte Partitionen verarbeiten
- Snapshot-Deltas berechnen
- offene Fälle aktualisieren
- abgeschlossene Perioden einfrieren
- späte Änderungen gezielt nachladen

### Event-getriebene Ausnahmen

Für operative Entscheidungen kann es effizienter sein, nur relevante Ereignisse oder Grenzwertverletzungen an eine App zu liefern, statt permanent die gesamte Faktentabelle neu zu prüfen.

## Performance ist nicht nur Geschwindigkeit

Ein fokussiertes Decision Product verbessert mehr als die Ladezeit.

### Geringere technische Last

Weniger gelesene Daten, weniger breite Joins und kleinere Transfers reduzieren Compute- und Infrastrukturbedarf.

### Bessere Parallelität

Wenn viele Nutzer kleine, zweckgebundene Datensätze konsumieren, konkurrieren sie weniger stark um dieselben großen Workloads.

### Stabilere Aktualisierung

Kleine inkrementelle Modelle lassen sich häufiger und kontrollierter aktualisieren als ein universeller Komplett-Reload.

### Klarere Fehleranalyse

Ein abgegrenzter Datenvertrag erleichtert die Frage, ob ein Problem in Quelle, Transformation, Kennzahl oder App entstanden ist.

### Einfachere Tests

Ein Decision Product kann gezielt auf Vollständigkeit, Eindeutigkeit, Grenzwerte und fachliche Erwartungen getestet werden.

### Bessere Nutzerführung

Weniger irrelevante Felder und Filter reduzieren Interpretationsaufwand und Fehlbedienung.

Performance ist damit auch ein Governance- und UX-Thema.

## Governance wird durch kleinere Produkte präziser

Ein universelles Modell besitzt häufig so viele mögliche Nutzungen, dass Ownership und Qualitätsanforderungen unscharf werden.

Ein Decision Product kann dagegen klar benennen:

- Business Owner
- Data Owner
- Data Steward
- technische Verantwortung
- verbindliche KPI-Definitionen
- erlaubte Zielgruppen
- Datenklassifikation
- Qualitäts-SLAs
- Aktualisierungszyklus
- Retention
- Zertifizierungsstatus
- Abhängigkeiten und Lineage

Das bedeutet nicht, dass jedes Decision Product eigene Definitionen erhält.

Im Gegenteil:

> **Definitionen werden geteilt. Verantwortung für die konkrete Nutzung wird präzisiert.**

Die Enterprise-Schicht liefert die gemeinsame Semantik. Das Decision Product dokumentiert, wie diese Semantik in einem konkreten Prozess verwendet wird.

## Kostenkontrolle ohne künstliche Spararchitektur

Diese Story ist kein Plädoyer dafür, jede Abfrage möglichst billig zu machen oder analytische Freiheit zu beschränken.

Exploration benötigt Breite. Data Science benötigt Detail. Neue Fragen benötigen flexible Zugänge.

Kostenkontrolle entsteht deshalb durch **Workload-Differenzierung**:

- produktive Dashboards nutzen stabile und fokussierte Datensätze
- explorative Analysen dürfen größere Bereiche lesen
- Data-Science-Workloads erhalten detaillierte, reproduzierbare Daten
- seltene historische Analysen werden nicht wie operative Realtime-Prozesse betrieben
- Management-Snapshots müssen nicht im Sekundentakt neu berechnet werden
- kritische Events werden gezielt beschleunigt

So wird Rechenleistung dort eingesetzt, wo sie Entscheidungswert erzeugt.

## Was vermieden werden sollte

### Das vollständige Modell in jede App kopieren

Die zentrale Plattform darf groß sein. Jede App muss es nicht sein.

### Aggregate ohne nachvollziehbare Herkunft

Eine kleine Kennzahlentabelle ist nur vertrauenswürdig, wenn Definition, Zeitraum, Filter und Lineage klar sind.

### Schattenlogik in der Nutzungsschicht

Filterung darf nicht dazu führen, dass Umsatz, Marge oder Kunde in jeder App anders definiert werden.

### Detaildaten vollständig entfernen

Ohne kontrollierten Drill-down verlieren Kennzahlen ihre Erklärbarkeit.

### Jede Frage vorab materialisieren

Zu viele Spezialtabellen erzeugen neue Komplexität. Materialisierung sollte auf wiederkehrenden, wertvollen Workloads basieren.

### Performance nur mit größerem Compute lösen

Mehr Rechenleistung kann Symptome reduzieren, ersetzt aber kein sauberes Grain, keine gute Partitionierung und keinen klaren Consumption Contract.

### Realtime als Standard

Realtime ist sinnvoll, wenn Latenz die Handlung verändert. Für viele Management- und Reporting-Prozesse sind Konsistenz und Reproduzierbarkeit wichtiger.

## Ein pragmatischer Implementierungsweg

### Schritt 1 — die Entscheidung beschreiben

Nicht mit Tabellen oder Visualisierungen beginnen.

Dokumentiert werden:

- wiederkehrende Frage
- mögliche Entscheidung
- erwartete Aktion
- verantwortliche Rolle
- erforderlicher Beleg
- akzeptable Aktualität

### Schritt 2 — den minimal notwendigen Datenausschnitt definieren

Welche Kennzahlen, Dimensionen, Ausnahmen und Detailstufen werden wirklich benötigt?

„Minimal“ bedeutet nicht unzureichend. Es bedeutet **zweckvollständig**.

### Schritt 3 — gemeinsame Semantik wiederverwenden

Dimensionen, KPI-Formeln, Kalenderlogik, Währungen, Security und Qualitätsregeln stammen aus dem governed Kern.

### Schritt 4 — den geeigneten technischen Zugriff wählen

Je nach Workload:

- View
- materialisierte View
- Aggregationstabelle
- semantisches Modell
- inkrementeller Datensatz
- Event Stream
- App-Extrakt

### Schritt 5 — Drill-down und Nachweis erhalten

Jede Aggregation benötigt stabile Referenzen und Lineage zur darunterliegenden Ebene.

### Schritt 6 — Last und Nutzung messen

Gemessen werden sollten unter anderem:

- Laufzeit
- gelesene Datenmenge
- Aktualisierungsdauer
- Parallelität
- Nutzung von Feldern und Sichten
- Häufigkeit von Drill-downs
- veraltete oder ungenutzte Produkte

### Schritt 7 — gezielt optimieren oder entfernen

Ein dauerhaft ungenutztes Decision Product ist keine Governance-Leistung. Es ist eine Altlast.

## Die eigentliche Architekturfrage

Die Frage lautet nicht:

> *Wie bekommen wir zehn Milliarden Zeilen in jede App?*

Sie lautet:

> **Wie bewahren wir zehn Milliarden Zeilen governed und nachvollziehbar — und liefern jeder Entscheidung exakt den benötigten Ausschnitt?**

Eine gute Datenplattform hält alle erforderlichen Daten verfügbar.

Eine gute Decision Architecture verhindert, dass jeder Nutzer sie bei jeder Frage vollständig verarbeiten muss.

> **Die Plattform bewahrt das Detail.  
> Das gemeinsame Modell bewahrt die Bedeutung.  
> Das Decision Product liefert den Fokus.**
