---
title: Ein bestehendes Warehouse modernisieren
description: Wie QVD-Landschaften, Stored Procedures, SSIS-Strecken, Excel-Ausgaben, Power-BI-Modelle und umfangreiche Qlik-Skripte schrittweise modernisiert werden — mit Inventarisierung, Priorisierung, paralleler Validierung und kontrollierter Stilllegung statt eines riskanten Big-Bang-Neuaufbaus.
category: Datenarchitektur
tags:
  - data-architecture
  - modern-data-warehouse
  - brownfield
  - warehouse-modernization
  - strangler-pattern
  - data-products
  - qlik-sense
  - qvd
  - power-bi
  - excel
  - sql-server
  - ssis
  - microsoft-fabric
  - snowflake
  - databricks
  - dbt
  - data-quality
  - data-governance
order: -1
author: Thomas Lindackers
series: building-modern-data-warehouse
seriesPart: 5
seriesTitle: Building a Modern Data Warehouse – From First Source to Governed Data Products
hero: images/playbooks/bp-start5-hero.png
---

## Brownfield-Modernisierung beginnt mit einem anderen Problem

Ein Greenfield-Warehouse beginnt mit einer leeren Plattform. Ein Brownfield-Warehouse beginnt mit fachlichen Abhängigkeiten.

Die bestehende Umgebung kann Folgendes enthalten:

- QVD-Generatoren, die mehrere Generationen von Qlik-Anwendungen versorgen;
- umfangreiche Qlik-Ladeskripte mit Extraktion, Joins, Mappings, Historie und KPI-Logik;
- Stored Procedures, in denen sich über Jahre fachliche Regeln angesammelt haben;
- SSIS-Pakete mit verborgenen Abhängigkeiten und operativen Workarounds;
- SQL-Tabellen, die technisch aktiv sind, aber fachlich nicht mehr verstanden werden;
- Power-BI-Semantikmodelle, die bereits an anderer Stelle vorhandene Transformationen wiederholen;
- Excel-Exporte, die zu informellen Schnittstellen für kritische Prozesse geworden sind;
- Berichte, deren Nutzung, Ownership und Ablösungsrisiko unbekannt sind.

Diese Umgebung kann veraltet wirken. Teile davon sind jedoch häufig zuverlässig, operativ erprobt und tief in fachliche Abläufe eingebettet. Alles gleichzeitig zu ersetzen, bündelt deshalb mehrere Risiken:

```text
Unbekannte Abhängigkeiten
Undokumentierte fachliche Regeln
Verändertes Verhalten der Quellen
Verhalten einer neuen Plattform
Neue Betriebsprozesse
Neue Consumer-Schnittstellen
Eine gemeinsame Umschaltung
```

Die Aufgabe besteht nicht nur darin, einen moderneren Stack aufzubauen. Die Aufgabe besteht darin, strukturelles Risiko zu reduzieren, ohne vertrauenswürdige fachliche Ergebnisse zu unterbrechen.

Die Parts 1 bis 4 dieser Serie haben Entscheidungen, logische Schichten, die einfachste tragfähige Architektur und den Greenfield-Ansatz über einen vertikalen Slice definiert. Die Brownfield-Modernisierung wendet dieselben Prinzipien unter einer zusätzlichen Bedingung an: Das aktuelle Ergebnis muss weiter funktionieren, bis sein Ersatz nachweislich tragfähig ist.

> **Modernisierung sollte Risiken schrittweise ersetzen. Sie sollte nicht jede bestehende Komponente nur deshalb austauschen, weil sie alt ist.**

## Nicht alles auf einmal neu aufbauen

Ein Big-Bang-Neuaufbau geht davon aus, dass die bestehende Umgebung vollständig verstanden, reproduziert und in einem koordinierten Programm ersetzt werden kann. Bei gewachsenen Warehouses ist diese Annahme meistens falsch.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start5-img1-de.png"
        alt="Vergleich zwischen einem riskanten Big-Bang-Neuaufbau des Warehouses und einer kontrollierten schrittweisen Modernisierung, die funktionierende Bestandteile erhält, jeweils einen Bereich ersetzt und Legacy-Komponenten erst nach der Validierung stilllegt"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ein vollständiger Neuaufbau verändert zu viele Variablen gleichzeitig. Eine schrittweise Modernisierung hält bewährte Fähigkeiten aktiv, ersetzt einen klar begrenzten Bereich, validiert das Ergebnis und legt nur noch unnötige Bestandteile still.
    </figcaption>
</figure>

Ein vollständiger Neuaufbau erzeugt einen langen Zeitraum, in dem die Organisation investiert, ohne eine produktive Verbesserung zu erhalten. Auch das Lernen wird verzögert. Falsche Annahmen über Historie, Status-Mappings, Ausnahmebehandlung oder Berichtsnutzung werden möglicherweise erst bei der abschließenden Umschaltung sichtbar.

Ein inkrementeller Ansatz liefert früher Evidenz. Jede migrierte Domäne zeigt:

- welche Legacy-Komponenten tatsächlich erforderlich sind;
- welche fachlichen Regeln dupliziert oder widersprüchlich sind;
- welche Consumer welche Ergebnisse verwenden;
- welche Datenqualitätsprobleme aus der Quelle und welche aus der Transformationslogik stammen;
- welche Teile vorübergehend oder dauerhaft bestehen bleiben können;
- welche Plattformfähigkeiten einen messbaren Nutzen erzeugen.

Die Modernisierungseinheit sollte deshalb eine klar begrenzte fachliche Fähigkeit oder ein Datenprodukt sein und nicht das vollständige Warehouse.

Beispiele sind:

```text
Täglicher Vertrieb
Customer 360
Lagerbestand
Offene Aufträge
Service-Performance
Finanz-Istwerte
```

Jede Einheit erhält einen eigenen Scope, Owner, Zielvertrag, Validierungsplan, eine Consumer-Migration und eine Stilllegungsentscheidung.

## Architekturprinzip: umschließen, ersetzen und stilllegen

Das Strangler Pattern für Warehouses hält die bestehende Umgebung aktiv, während ein neuer governter Pfad jeweils eine Domäne übernimmt.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start5-img2-de.png"
        alt="Das Strangler Pattern für Warehouses von Inventarisierung und Priorisierung über parallelen Neuaufbau, Validierung, Consumer-Migration und Stilllegung bis zur kontinuierlichen Wiederholung"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Das Legacy-Warehouse bleibt teilweise aktiv, während ein begrenzter Ausschnitt neu aufgebaut, mit dem aktuellen Ergebnis verglichen, von den Consumern übernommen und anschließend stillgelegt wird. Der Zyklus wird Domäne für Domäne wiederholt.
    </figcaption>
</figure>

Ein praktikabler Zyklus enthält sieben Schritte:

```flow linear vertical
Inventarisieren
Priorisieren
Einen begrenzten Ausschnitt neu aufbauen
Alt und Neu parallel betreiben
Validieren und Abweichungen erklären
Consumer migrieren
Den ersetzten Pfad stilllegen
```

### 1. Inventarisieren

Erstelle eine evidenzbasierte Sicht auf die bestehende Landschaft. Die Inventarisierung sollte mehr als Objektnamen enthalten.

| Inventarbereich | Mindestinformationen |
| --- | --- |
| Quellen | System, Objekt, Extraktionsmethode, Frequenz, Historie und Owner |
| Pipelines | Scheduler, Paket oder Job, Abhängigkeiten, Laufzeit, Fehlerverhalten und Support-Owner |
| SQL-Logik | Procedures, Views, Tabellen, Parameter, Schreibziele und nachgelagerte Abhängigkeiten |
| QVDs | Generator, Reload-Kette, Felder, Consumer, Größe, Ladefrequenz und Aufbewahrung |
| Qlik-Apps | Reload-Skript, Datenmodell, KPI-Ausdrücke, Section Access, Bookmarks und Nutzung |
| Power BI | Datenquellen, Power-Query-Logik, Semantikmodell, Measures, Aktualisierung und Berichtsnutzung |
| Excel | Herkunft des Exports, Empfänger, manuelle Transformationen, Makros und Prozessabhängigkeit |
| Qualität | Bestehende Prüfungen, bekannte Fehler, akzeptierte Ausnahmen und Abstimmungsroutinen |
| Betrieb | SLA, Supportkontakte, Runbook, Wiederherstellung und wiederkehrende manuelle Eingriffe |

Ein Katalog kann unterstützen. Für den Start reicht jedoch auch eine Tabelle in Excel oder SQL. Entscheidend ist, Nutzung und Abhängigkeiten zu erfassen, statt auf eine perfekte Metadatenplattform zu warten.

### 2. Priorisieren

Die Priorisierung sollte Business-Nutzen, Migrationsfähigkeit und operatives Risiko kombinieren.

Ein nützliches Bewertungsmodell ist:

| Dimension | Fragen |
| --- | --- |
| Business-Nutzen | Unterstützt die Domäne wichtige Entscheidungen, Umsatz, Kosten oder regulatorische Pflichten? |
| Schmerz | Erzeugt sie häufige Störungen, langsame Änderungen oder hohen manuellen Aufwand? |
| Wiederverwendung | Würde ein zentrales Produkt Logik in mehreren Anwendungen oder Teams ersetzen? |
| Abhängigkeit | Wie viele Quellen, Pipelines und Consumer müssen gemeinsam migriert werden? |
| Regelunsicherheit | Sind Definitionen dokumentiert und zugeordnet oder nur im Code verborgen? |
| Validierbarkeit | Können Alt und Neu über einen repräsentativen Zeitraum verglichen werden? |
| Stilllegungspotenzial | Können nach der Migration relevante Legacy-Komponenten abgeschaltet werden? |

Der erste Ausschnitt sollte relevant genug sein, um Nutzen zu erzeugen, aber klein genug, um abgeschlossen zu werden. Die komplexeste Finanzkonsolidierung oder der am wenigsten genutzte Bericht ist selten der beste Startpunkt.

### 3. Einen begrenzten Ausschnitt neu aufbauen

Der neue Ausschnitt sollte den vollständigen Pfad für ein governtes Ergebnis umsetzen:

```flow linear vertical
Benötigte Quelldaten
Kontrollierte Ingestion
Standardisierung
Fachliche Integration und Historie
Datenprodukt
Qualitätsergebnisse
Consumer-Vertrag
```

Die neue Lösung kann die bestehende Datenbank, eine neue Plattform oder eine hybride Variante verwenden. Die architektonische Anforderung ist keine Produktentscheidung. Sie besteht in der Trennung gemeinsamer fachlicher Logik von Consumer-spezifischer Darstellung.

### 4. Parallel betreiben

Der alte und der neue Pfad erhalten vergleichbare Quellzeiträume und liefern Ergebnisse auf derselben Granularität. Keines der beiden Ergebnisse gilt automatisch als richtig, nur weil es alt oder neu ist.

### 5. Validieren und Abweichungen erklären

Die Validierung muss Daten, Definitionen, Timing, Historie und Consumer-Verhalten vergleichen. Eine Abweichung ist ein Untersuchungsnachweis und nicht automatisch ein Fehler des neuen Systems.

### 6. Consumer migrieren

Consumer werden erst umgestellt, wenn Ownership, Akzeptanzkriterien, Security, Aktualisierungsverhalten und Rollback geklärt sind. Qlik, Power BI und Excel können zu unterschiedlichen Zeitpunkten wechseln, wenn ihre Verträge voneinander abweichen.

### 7. Stilllegen

Ein migrierter Pfad ist nicht abgeschlossen, solange doppelte Generatoren, Pakete, Tabellen und Exporte dauerhaft weiterlaufen. Die Stilllegung entfernt Zeitpläne, Zugriffspfade, Speicher, Monitoring, Supportpflichten und Dokumentation der alten Komponente.

## Die einfachste sinnvolle Brownfield-Umsetzung

Modernisierung erfordert am ersten Tag keine neue Plattform.

Eine minimale Umsetzung kann die bestehende Datenbank, den vorhandenen Scheduler und die vorhandene BI-Landschaft verwenden:

```flow linear vertical
Aktuelle Quellextrakte
Bestehende SQL-Datenbank
Neue kontrollierte Schemas und Tabellen
Persistente Validierungsergebnisse
Bestehende Qlik-, Power-BI- und Excel-Consumer
Legacy-Jobs schrittweise stilllegen
```

Eine bestehende SQL-Server-Umgebung könnte beispielsweise wenige explizite Schemas einführen:

```text
raw       empfangene Quelldaten
stg       technisch standardisierte Daten
core      integrierte fachliche Entitäten und Historie
mart      governte Fakten, Dimensionen und Datenprodukte
quality   Test- und Abstimmungsergebnisse
control   Lade-, Abhängigkeits- und Migrationsmetadaten
```

Bestehende SSIS-Pakete oder Datenbank-Jobs können die Ingestion zunächst weiter übernehmen. Neue Transformationen können mit vorhandenen SQL-Views oder Stored Procedures umgesetzt werden, wenn dies für das Team die wartbarste Variante ist.

Der Modernisierungsnutzen entsteht durch klarere Verantwortlichkeiten, zentrale Regeln, persistente Qualitätsnachweise und kontrollierte Schnittstellen. Er hängt nicht davon ab, Scheduler, Datenbank und BI-Tools gleichzeitig zu ersetzen.

## Konkretes Beispiel: Modernisierung einer Vertriebslandschaft

Angenommen, die bestehende Vertriebslandschaft enthält:

- drei Qlik-Anwendungen mit ähnlicher Kunden- und Umsatzlogik;
- zwei QVD-Generatoranwendungen;
- eine SSIS-Extraktionskette aus ERP und CRM;
- Stored Procedures für monatliche Reportingtabellen;
- ein Power-BI-Modell, das Status- und Länder-Mappings wiederholt;
- wöchentliche Excel-Dateien für regionale Verantwortliche;
- unterschiedliche Definitionen des Nettoumsatzes.

Das Ziel ist ein governtes Vertriebsdatenprodukt mit:

| Element | Zielentscheidung |
| --- | --- |
| Granularität | Eine Auftragsposition pro Geschäftsdatum |
| Zentrale Faktentabelle | `mart.fact_sales` |
| Gemeinsame Dimensionen | `mart.dim_customer`, `mart.dim_product`, `mart.dim_date` |
| Umsatzregel | Stornierte Auftragspositionen tragen null zum Nettoumsatz bei |
| Kundenhistorie | Kundenattribute werden in der am Auftragsdatum gültigen Version zugeordnet |
| Status-Mapping | Quellstatus werden einer governten Statusdomäne zugeordnet |
| Consumer | Qlik, Power BI und kontrollierter Excel-Zugriff |
| Validierung | Anzahlen, Schlüssel, Historie, Umsatz, Status, Land, Aktualität und Berichtssummen |
| Owner | Sales Data Owner mit technischem Plattform-Owner |

### Schritt 1: die aktuelle Regel nachverfolgen

Das Team kann feststellen, dass der Nettoumsatz derzeit an mehreren Stellen berechnet wird:

```text
QVD-Generator A       schließt Status 90 aus
Qlik-App B            schließt Status 90 und 95 aus
Power-BI-Measure      zieht Stornos nach Belegart ab
Excel-Arbeitsmappe    entfernt negative Werte manuell
Stored Procedure      verwendet ein Stornokennzeichen aus dem ERP
```

Die Migration sollte nicht alle Varianten in die neue Plattform kopieren. Sie sollte den Konflikt sichtbar machen, eine freigegebene Definition einholen und die Legacy-Varianten nur für die Abstimmung erhalten.

### Schritt 2: ein zentrales Zielmodell aufbauen

Eine vereinfachte plattformneutrale Transformation könnte so aussehen:

```sql
select
    o.business_date,
    o.order_id,
    o.order_line_id,
    c.customer_key,
    p.product_key,
    c.country_code,
    s.order_status,
    o.gross_revenue,
    case
        when s.is_cancelled = 1 then cast(0 as decimal(18, 2))
        else o.gross_revenue
    end as net_revenue,
    o.source_changed_at,
    current_timestamp as loaded_at
from core.sales_order_line o
left join core.customer_history c
  on o.customer_id = c.customer_id
 and o.business_date >= c.valid_from
 and o.business_date < coalesce(c.valid_to, date '9999-12-31')
left join core.product_history p
  on o.product_id = p.product_id
 and o.business_date >= p.valid_from
 and o.business_date < coalesce(p.valid_to, date '9999-12-31')
left join core.order_status s
  on o.source_order_status = s.source_order_status;
```

Die genaue Syntax unterscheidet sich je Datenbank. Entscheidend ist, dass Status-Mapping, Historienzuordnung und die gemeinsame Umsatzbasis nun außerhalb einer einzelnen BI-Anwendung liegen.

### Schritt 3: Consumer-Schnittstellen bewusst dünn halten

Eine Qlik-Anwendung kann das governte Produkt direkt laden:

```qlik
Sales:
SQL SELECT
    business_date,
    order_id,
    order_line_id,
    customer_key,
    product_key,
    country_code,
    order_status,
    gross_revenue,
    net_revenue,
    source_changed_at,
    loaded_at
FROM mart.fact_sales;
```

Qlik-spezifische Assoziationen, Section Access, Alternate States oder Präsentationsfelder können in Qlik verbleiben, wenn sie tatsächlich erforderlich sind. Gemeinsames Kunden-Matching, Status-Mapping, historische Zuordnung und Nettoumsatzlogik sollten nicht in jeder App erneut aufgebaut werden.

Power BI kann dieselbe governte Faktentabelle und dieselben Dimensionen über sein Semantikmodell verwenden. Berichtsinteraktionen, Formatierung und tatsächlich darstellungsspezifische Measures bleiben lokal; die gemeinsame fachliche Bedeutung bleibt zentral.

Excel kann eine governte Reporting-View oder einen kontrollierten Extrakt auf der benötigten Granularität erhalten. Manuelle Arbeitsmappen sollten nicht zu einer alternativen Transformationsschicht werden, die den KPI stillschweigend neu definiert.

## Vom QVD-zentrierten zum plattformzentrierten Ansatz

QVDs sind für Qlik-Performance, Wiederverwendung und Entkopplung nützlich. Das Problem ist nicht das Dateiformat. Das Problem entsteht, wenn die QVD-Landschaft zur einzigen Integrationsarchitektur wird und das vollständige fachliche Modell in undurchsichtigen Ketten von Anwendungsskripten umgesetzt ist.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start5-img3-de.png"
        alt="Übergang von einer QVD-zentrierten Architektur mit verteilter Extraktions-, Transformations- und Business-Logik in Qlik-Anwendungen zu einer plattformzentrierten Architektur mit zentralen governten Datenprodukten, einer optionalen gemeinsamen QVD-Schicht und dünnen Consumer-Anwendungen"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Die Modernisierung verlagert wiederverwendbare Extraktion, Integration, Historie, Qualität und KPI-Grundlagen in eine zentrale Plattform. QVDs können als optionale governte Bereitstellungsschicht bestehen bleiben, während Qlik-Anwendungen dünner werden.
    </figcaption>
</figure>

Eine praktikable Zielzuordnung ist:

| Verantwortung | Bevorzugter Ort |
| --- | --- |
| Quellextraktion und Lademetadaten | Zentrale Ingestion oder kontrollierte Extraktionsschicht |
| Typkonvertierung und technische Normalisierung | Standardisierungsschicht |
| Kunden- und Produktidentität | Integrierter Kern |
| Historisierung und zeitgültige Zuordnung | Integrierter Kern |
| Gemeinsame Status- und Länder-Mappings | Governte Referenzdaten |
| Gemeinsame Faktgranularität und Umsatzbasis | Governtes Datenprodukt |
| Datenqualitätsregeln und Ergebnisse | Zentrale Qualitätsschicht |
| Optionale Qlik-optimierte QVDs | Gemeinsame Bereitstellungsschicht aus governten Produkten |
| Qlik-Assoziationen und Section Access | Qlik, wenn erforderlich |
| Power-BI-Semantikbeziehungen und Präsentations-Measures | Power-BI-Semantikschicht, wenn Consumer-spezifisch |
| Excel-Layout und lokale Analyse | Excel, ohne gemeinsame fachliche Regeln neu zu definieren |

Die optionale zentrale QVD-Schicht kann ein wirksamer Übergangsmechanismus sein:

```flow linear vertical
Governte Plattformtabellen oder Views
Zentraler QVD-Generator
Stabiler gemeinsamer QVD-Vertrag
Dünne Qlik-Anwendungen
```

Damit können Qlik-Consumer migriert werden, bevor jede Anwendung direkt auf die Plattform zugreifen kann. Gleichzeitig bleiben operativ wertvolle Eigenschaften erhalten. Die QVD-Schicht wird zu einer kontrollierten Schnittstelle und nicht zu dem Ort, an dem das vollständige Warehouse definiert wird.

## Parallele Validierung vor der Umschaltung

Ein neues Modell sollte ein gewachsenes Ergebnis nicht allein aufgrund eines erfolgreichen technischen Ladevorgangs ersetzen. Der alte und der neue Pfad benötigen einen repräsentativen Parallelzeitraum und explizite Akzeptanzkriterien.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bp-start5-img4-de.png"
        alt="Prozess zur parallelen Validierung, bei dem aktueller und neuer Warehouse-Pfad nebeneinander laufen, Zeilenanzahlen, Nullwerte, Schlüssel, Kennzahlen, Geschäftsregeln, Berichte und Performance vergleichen und Consumer erst nach Freigabe und vorbereitetem Rollback umgestellt werden"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Sicherheit bei der Umschaltung entsteht durch erklärte Abweichungen und nicht allein durch identische Summen. Datenverantwortliche und Business-Nutzer geben das Ergebnis frei, nachdem Definitionen, Timing, Historie und Qualität abgestimmt wurden.
    </figcaption>
</figure>

### Auf mehreren Ebenen validieren

| Validierungsebene | Beispiele |
| --- | --- |
| Technische Vollständigkeit | Zeilenanzahlen, eindeutige Schlüssel, Dublettenraten, Nullraten und abgewiesene Datensätze |
| Referenzielle Integrität | Nicht aufgelöste Kunden, Produkte, Länder und Statuswerte |
| Aggregationen | Umsatz nach Tag, Land, Kundensegment, Produkt und Status |
| Historie | Am Geschäftsdatum ausgewählte Attributversion, verspätete Änderungen und Restatements |
| KPI-Logik | Bruttoumsatz, Stornos, Nettoumsatz, offener Auftragswert und Währungsbehandlung |
| Timing | Quell-Cutoff, Zeitzone, verspätete Dateien, Reload-Dauer und Veröffentlichungszeitpunkt |
| Consumer-Ergebnis | Qlik-Objekte, Power-BI-Visualisierungen, Excel-Extrakte und geplante Verteilungen |
| Betrieb | Wiederanlauf, Monitoring, Alarmierung, Zugriff und Rollback |

### Jede Abweichung klassifizieren

Eine nützliche Abstimmungstabelle speichert nicht nur die Differenz. Sie dokumentiert auch, warum sie existiert.

```text
DEFINITION   Alte und neue Regeln unterscheiden sich
TIMING       Quell-Cutoffs oder Verarbeitungszeiten unterscheiden sich
HISTORIE     Unterschiedliche zeitgültige Datensätze werden ausgewählt
QUALITÄT     Das alte Ergebnis enthält Dubletten, Nullwerte oder ungültige Mappings
SCOPE        Consumer verwenden unterschiedliche Grundmengen oder Filter
FEHLER_ALT   Das Legacy-Ergebnis ist falsch
FEHLER_NEU   Das neue Ergebnis ist falsch
ERWARTET     Freigegebene und dokumentierte Abweichung
UNGEKLÄRT    Untersuchung noch erforderlich
```

Beispielhafte Felder einer Validierungsergebnistabelle:

| Feld | Zweck |
| --- | --- |
| `validation_run_id` | Kennung des Vergleichslaufs |
| `domain` | Migriertes Datenprodukt oder Fachgebiet |
| `business_date` | Verglichenes Berichtsdatum oder Zeitraum |
| `check_id` | Stabile Abstimmungsregel |
| `old_value` | Legacy-Ergebnis |
| `new_value` | Neues Ergebnis |
| `absolute_difference` | Absolute Differenz |
| `relative_difference` | Prozentuale Differenz |
| `classification` | Definition, Timing, Historie, Qualität, Fehler oder erwartet |
| `owner` | Für die Klärung verantwortliche Person |
| `status` | Offen, erklärt, akzeptiert oder abgelehnt |
| `evidence_reference` | Link oder Kennung für Detaildatensätze |

### Akzeptanz vor dem Vergleich definieren

Die Akzeptanz sollte vor der finalen Umschaltungsdiskussion vereinbart werden.

Beispiele:

- alle erforderlichen Business Keys sind vorhanden;
- keine blockierende Qualitätsregel schlägt fehl;
- tägliche Zeilenanzahlen stimmen innerhalb einer freigegebenen Toleranz überein;
- der Nettoumsatz stimmt nach dokumentierten Definitionsänderungen überein;
- alle historischen Abweichungen werden durch zeitgültige Regeln erklärt;
- kritische Qlik- und Power-BI-Ergebnisse sind durch benannte Business Owner freigegeben;
- Excel-Empfänger haben die Ersatzschnittstelle und Anweisungen erhalten;
- der neue Pfad erfüllt das vereinbarte Aktualitätsfenster;
- der Rollback wurde getestet;
- für den alten Pfad existiert ein terminierter Stilllegungsplan.

Perfekte Gleichheit ist nicht immer das Ziel. Ein neues System kann Legacy-Fehler bewusst korrigieren. Entscheidend ist, dass jede wesentliche Abweichung verstanden, zugeordnet und akzeptiert ist.

## Plattformoptionen für den Zielzustand

Das Strangler Pattern ist unabhängig von der Zielplattform. Dieselbe logische Migration kann mit den bereits verfügbaren Fähigkeiten umgesetzt werden.

| Bestehende oder geplante Umgebung | Praktischer Modernisierungsansatz |
| --- | --- |
| SQL Server und SSIS | Stabile Extraktion zunächst behalten, kontrollierte Schemas, zentrale Fakten und Dimensionen sowie persistente Tests einführen und Pakete schrittweise stilllegen |
| Microsoft Fabric | Ausgewählte Domänen in einen governten Warehouse- oder Lakehouse-Pfad überführen, während bestehende SQL-, QVD- oder Dateischnittstellen im Übergang aktiv bleiben |
| Snowflake | Eine Domäne in kontrollierten Schemas neu aufbauen und stabile Datenprodukt-Views oder Tabellen veröffentlichen, bevor jeder Consumer umgestellt wird |
| Databricks | Ausgewählte Datenprodukte in einem Lakehouse-orientierten Pfad neu aufbauen, wenn verteilte Verarbeitung oder bestehende Plattformstandards dies rechtfertigen |
| Hybrid | Quellen oder stabile Transformationen On-Premises behalten, während ausgewählte Integrations-, Produkt- oder Nutzungsschichten schrittweise verlagert werden |
| Bestehendes SQL-Warehouse ohne Plattformwechsel | Naming, Ownership, Testing, Versionierung, Schnittstellen und BI-Grenzen modernisieren, ohne einen Produktwechsel zu erzwingen |

Der optionale Einsatz von dbt kann sinnvoll werden, wenn modulare SQL-Entwicklung, Abhängigkeitsmanagement, Testing, Dokumentation oder Teamzusammenarbeit genügend Nutzen für den zusätzlichen Workflow erzeugen. Für die Migrationsmethode ist dbt keine Voraussetzung.

Die Plattformentscheidung sollte den Kriterien aus [Die einfachste tragfähige Architektur auswählen](/playbooks/choosing-the-simplest-viable-architecture) folgen: Volumen, Aktualität, Transformationstyp, Teammodell, Governance, Consumer, Verfügbarkeit, vorhandene Fähigkeiten und gesamte Betriebskosten.

## Typische Anti-Patterns

### Objekte statt Ergebnisse migrieren

Wer jede Tabelle, jedes QVD und jedes Paket nachbaut, erhält die alte Architektur an einem neuen Ort. Die Migrationseinheit sollte ein governtes fachliches Ergebnis mit explizitem Consumer-Vertrag sein.

### Legacy-Logik kopieren, ohne Konflikte zu lösen

Eine Regel ist nicht automatisch korrekt, nur weil sie in fünf Anwendungen existiert. Doppelte Definitionen müssen verglichen, zugeordnet und freigegeben werden, bevor sie zentral werden.

### Einen Plattformwechsel als Modernisierungsstrategie bezeichnen

Dieselben undurchsichtigen Procedures und App-Logiken auf eine andere Engine zu verschieben, verändert die Infrastruktur und nicht die Architektur.

### Den neuen Pfad ohne Nutzungsnachweise bauen

Ungenutzte Berichte, doppelte Exporte und aufgegebene QVDs sollten nicht allein deshalb migriert werden, weil sie existieren.

### Alt und Neu dauerhaft parallel betreiben

Der Parallelbetrieb ist eine Validierungsphase und kein dauerhaftes Betriebsmodell. Jeder Ausschnitt benötigt Ausstiegskriterien und ein Stilllegungsdatum.

### Nur Summen validieren

Ein passender Gesamtumsatz kann fehlende Kunden, duplizierte Positionen, andere Historie, verschobene Datumswerte oder sich ausgleichende Fehler verbergen. Die Validierung muss Granularität, Schlüssel, Verteilungen und fachliche Regeln einschließen.

### Sämtliche BI-Logik zentralisieren

Gemeinsame Definitionen gehören in die zentrale Plattform. Consumer-spezifische Interaktion, Visualisierung und Tool-Verhalten können in Qlik, Power BI oder Excel verbleiben. Zentralisierung soll Wiederverwendung erhöhen und nicht legitime Consumer-Verantwortung entfernen.

### QVDs nur als Modernisierungsbeweis entfernen

Eine governte QVD-Bereitstellungsschicht kann weiterhin sinnvoll sein. Das Ziel sind dünne und kontrollierte Qlik-Consumer und nicht die symbolische Abschaffung eines Dateiformats.

### Im ersten Ausschnitt mehrere neue Tools gleichzeitig einführen

Eine erste Migration, die gleichzeitig Ingestion, Speicher, Transformationsframework, Scheduler, Katalog, BI-Schnittstelle und Betriebsmodell verändert, erschwert die Ursachenanalyse. Verändere nur das, was der begrenzte Ausschnitt tatsächlich benötigt.

## Entscheidungshilfe: Was sollte zuerst migriert werden?

| Situation | Empfohlener erster Schritt |
| --- | --- |
| Viele Qlik-Apps wiederholen Kunden- und Umsatzlogik | Eine zentrale Kundendimension und Vertriebsfaktentabelle aufbauen und anschließend eine hochwertige App migrieren |
| SSIS ist stabil, Transformationen sind jedoch undurchsichtig | Extraktion behalten, eine Transformationskette in kontrollierte Schemas verlagern und eine Abstimmung ergänzen |
| Power BI und Qlik widersprechen sich bei KPIs | Eine gemeinsame Datenproduktdefinition freigeben, bevor eine der Berichtsschichten verändert wird |
| Excel-Exporte sind operativ kritisch | Den Export als Consumer-Vertrag behandeln und erst nach Validierung durch die Empfänger ersetzen |
| Große QVD-Landschaft mit unbekannter Nutzung | Reloads und Consumer messen, bevor QVDs neu aufgebaut, behalten oder stillgelegt werden |
| Stabiles On-Premises-Warehouse mit schwacher Governance | Ownership, Tests, Versionierung, Lineage und kontrollierte Schnittstellen ergänzen, bevor eine Cloud-Migration erwogen wird |
| Eine neue Plattform ist bereits vorhanden | Sie für eine begrenzte Domäne verwenden und Betrieb, Qualität und Consumer-Migration als Muster nachweisen |
| Das Legacy-Ergebnis ist nachweislich falsch | Für den Vergleich erhalten, die Korrektur dokumentieren und eine explizite fachliche Freigabe einholen |
| Keine verlässliche Abhängigkeitskarte existiert | Mit Laufzeitlogs, Scheduler-Ketten, Skriptreferenzen und Nutzerinterviews beginnen; nicht auf perfekte Lineage warten |

## Wichtigste Empfehlungen

1. Modernisiere jeweils ein klar begrenztes Datenprodukt.
2. Inventarisiere Nutzung, Abhängigkeiten, Owner, fachliche Regeln und Betriebsverhalten vor dem Neuaufbau.
3. Priorisiere nach Business-Nutzen, Schmerz, Wiederverwendung, Umsetzbarkeit und Stilllegungspotenzial.
4. Halte den aktuellen Pfad aktiv, bis sein Ersatz validiert ist.
5. Behandle Legacy- und neue Ergebnisse als zu vergleichende Hypothesen und nicht als automatische Wahrheit.
6. Klassifiziere Abweichungen nach Definition, Timing, Historie, Scope und Datenqualität.
7. Verlage gemeinsame Identität, Historie, Mappings, Faktgranularität und KPI-Grundlagen aus einzelnen BI-Anwendungen.
8. Halte Qlik-Skripte dünn und belasse nur tatsächlich Qlik-spezifische Logik in der App.
9. Nutze eine zentrale QVD-Schicht, wenn sie eine kontrollierte und praktikable Übergangsschnittstelle bietet.
10. Lasse Power BI und Excel dasselbe governte Datenprodukt verwenden, ohne seine gemeinsamen Regeln neu zu definieren.
11. Beginne mit vorhandenen SQL-, Scheduling- und BI-Fähigkeiten, wenn sie den Ausschnitt zuverlässig tragen können.
12. Ergänze Fabric, Snowflake, Databricks oder dbt nur, wenn eine konkrete Anforderung sie rechtfertigt.
13. Definiere Akzeptanzkriterien, fachliche Freigabe, Rollback und Kommunikation vor der Umschaltung.
14. Lege nach der Migration Zeitpläne, Speicher, Zugriffspfade und Supportpflichten still.
15. Nutze jeden abgeschlossenen Ausschnitt, um das Muster für die nächste Migration zu verbessern.

## Übergang zum nächsten Part

Brownfield-Modernisierung gelingt, wenn wiederverwendbare fachliche Logik aus fragmentierten Procedures, QVD-Ketten, Qlik-Anwendungen, Power-BI-Modellen und Excel-Arbeitsmappen in governte Plattformverantwortlichkeiten verlagert wird.

Diese Grenze verdient eine genauere Betrachtung. Der nächste Part, [Fachliche Logik außerhalb der BI-Apps halten](/playbooks/keeping-business-logic-outside-the-bi-apps), definiert, welche Logik zentral gehört, welche Consumer-spezifisch bleiben darf und wie dünne Qlik-, Power-BI- und Excel-Schichten dieselben governten Datenprodukte verwenden können.
