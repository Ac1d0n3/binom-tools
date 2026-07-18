---
title: Eine Geschäftsfrage, verschiedene BI-Engines — Reporting-Tools, Berechnungskontext und Governance
description: Ein praxisnaher Vergleich von Qlik Sense, Power BI, Tableau, Looker, SAP Analytics Cloud und Excel mit Stärken, Schwächen, semantischen Modellen und einem Filterkontext-Beispiel in allen sechs Welten.
category: Business Intelligence
tags:
  - business-intelligence
  - reporting-tools
  - qlik-sense
  - power-bi
  - tableau
  - looker
  - sap-analytics-cloud
  - excel
  - set-analysis
  - dax
  - semantic-layer
  - metric-governance
  - self-service
order: -1
author: Thomas Lindackers
hero: images/playbooks/bi-tools-hero.png
---

## Reporting-Tools stellen dieselben Daten nicht nur unterschiedlich dar

Ein Vergleich von Reporting-Tools beginnt häufig mit Diagrammtypen, Dashboard-Layouts, Konnektoren oder Lizenzmodellen. Diese Kriterien sind relevant, erklären aber nicht den wichtigsten architektonischen Unterschied.

Jedes BI-Tool beantwortet vier Fragen auf seine eigene Weise:

1. Wie werden Daten modelliert?
2. Wo wird Geschäftslogik definiert?
3. Wie reagiert eine Berechnung auf Filter und Benutzerauswahlen?
4. Wie lässt sich eine Kennzahl steuern, wiederverwenden und kontrolliert ändern?

Deshalb kann dieselbe Geschäftsfrage **Set Analysis in Qlik Sense, DAX in Power BI, eine Level-of-Detail-Expression in Tableau, LookML in Looker, eine eingeschränkte Measure in SAP Analytics Cloud oder eine Pivot-/Datenmodell-Formel in Excel** erfordern.

Der wichtigste Unterschied ist nicht die Syntax. Entscheidend ist der Berechnungsvertrag hinter der Syntax.

> **Ein BI-Tool ist nicht nur eine Visualisierungsschicht. Es ist zugleich Modellierungs-, Berechnungs- und Governance-Umgebung.**

## Die Reporting-Tool-Landschaft

Das erste Schaubild bietet eine indikative Orientierung über sechs häufig eingesetzte Reporting-Umgebungen.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bi-tools-img1-de.png"
        alt="Indikativer Vergleich von Qlik Sense, Power BI, Tableau, Looker, SAP Analytics Cloud und Excel bei Datenmodellierung, semantischer Schicht, visueller Exploration, Self-Service, Enterprise Governance und Embedded Analytics"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Der Vergleich zeigt Tendenzen und keine absoluten Produkturteile. Konfiguration, Architektur, Lizenzen, Fähigkeiten des Teams und das Governance-Modell können das tatsächliche Ergebnis deutlich verändern.
    </figcaption>
</figure>

Das Schaubild ist keine universelle Rangliste. Eine gut gesteuerte Tableau-Umgebung kann stärker governed sein als eine unkontrollierte Power-BI-Landschaft. Eine Excel-Arbeitsmappe auf einem zertifizierten semantischen Modell kann sicherer sein als ein Dashboard mit mehrfach kopierten lokalen Formeln. Eine Qlik-App kann trotz eines starken assoziativen Modells inkonsistent werden, wenn Ausdrücke unkontrolliert in Diagrammen verteilt sind.

Die relevante Frage lautet deshalb nicht:

> „Welches Produkt ist das beste?“

Sondern:

> **„Welches Produkt passt am besten zu unserem Betriebsmodell, unseren Nutzern, unserer Datenarchitektur und unserer Governance-Reife?“**

## Sechs Tools und sechs typische Betriebsmodelle

| Tool | Charakteristische Stärken | Typische Schwächen oder Risiken | Besonders geeignet für |
| --- | --- | --- | --- |
| **Qlik Sense** | Assoziative Exploration, direkte Reaktion auf Auswahlen, starke Datenmodellierung innerhalb der App, skriptbasierte Transformation, Set Analysis, Embedded Analytics | Set Analysis besitzt eine Lernkurve; Logik kann über Objekte und Apps dupliziert werden; app-zentrierte Entwicklung benötigt konsequente Wiederverwendung und Deployment-Regeln | Geführte und explorative Analysen, bei denen Nutzer frei durch zusammenhängende Daten navigieren sollen |
| **Power BI** | Tabellarisches semantisches Modell, DAX, breite Microsoft-Integration, starke Self-Service-Verbreitung, wiederverwendbare Semantic Models, großes Ökosystem | DAX-Filterkontext ist mächtig, aber komplex; viele Modelle und Reports können semantischen Wildwuchs erzeugen; Performance hängt stark von Modell- und Kapazitätsarchitektur ab | Organisationen mit Microsoft 365, Azure, Fabric oder wiederverwendbaren tabellarischen Modellen |
| **Tableau** | Visuelle Analyse, schnelle Exploration, flexible Diagrammgestaltung, starke Authoring-Erfahrung, LOD Expressions | Berechnungen können arbeitsmappenlokal werden; Filterreihenfolge und LOD-Verhalten sind nicht immer intuitiv; zentrale Kennzahlensteuerung erfordert bewusst eingesetzte veröffentlichte Datenquellen und kontrollierte Modelle | Visuelle Discovery, analystengetriebene Exploration und anspruchsvolles Data Storytelling |
| **Looker** | Code-first Semantic Layer mit LookML, Git-basierte Entwicklung, wiederverwendbare Dimensionen und Measures, Warehouse-native Ausführung, Embedded Use Cases | Benötigt Modellierungs- und SQL-Kompetenz; kontrollierte Modelländerungen sind langsamer als lokale Workbook-Änderungen; bestimmte Filtereffekte brauchen bewusstes Modell- oder Dashboard-Design | Zentrale semantische Governance, softwareähnliche Analytics-Entwicklung und Embedded Analytics |
| **SAP Analytics Cloud** | Enge Integration mit SAP-Daten und Planung, gesteuerte Modelle, eingeschränkte und berechnete Measures, Analyse und Planung in einer Umgebung | Stärkster Fit meist in SAP-zentrierten Landschaften; Nicht-SAP-Integration und Modellierungsentscheidungen können Komplexität erhöhen; Story- und Modellfilter müssen bewusst gestaltet werden | SAP-orientiertes Reporting, Planung und Enterprise Performance Management |
| **Excel** | Nahezu überall verfügbar, schnell, flexibel für Ad-hoc-Analysen, PivotTables, Power Query, Formeln und Power Pivot/Datenmodell | Kopien, manuelle Schritte, Zellreferenzen und lokale Formeln schwächen Lineage, Versionierung und Kontrolle; Workbook-Logik fragmentiert leicht | Persönliche Produktivität, kontrollierte Analyse, Reconciliation, Prototypen und Nutzung gesteuerter Modelle |

Diese Stärken und Schwächen sind architektonische Tendenzen und keine unveränderlichen Produktgrenzen.

## Eine Geschäftsfrage macht die Berechnungs-Engine sichtbar

Betrachten wir eine scheinbar einfache Anforderung:

> **Zeige den Umsatz des aktuellen Jahres für ausgewählte Kunden, ignoriere dabei die aktuelle Produktauswahl und ergänze einen Vergleich zum Vorjahr.**

Der beabsichtigte Filtervertrag lautet:

- ausgewählte Kunden beibehalten
- Produktauswahl für diese Kennzahl ignorieren
- aktuelles Jahr berechnen
- Vorjahr mit demselben Kunden-Scope berechnen
- das Ergebnis darf sich durch eine Produktauswahl nicht unbemerkt verändern

Das Ergebnis kann in allen Tools identisch aussehen. Die Umsetzung ist nicht identisch, weil die Engines Auswahlen, Filter, semantische Modelle und Visualisierungskontexte unterschiedlich behandeln.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bi-tools-img2-de.png"
        alt="Dieselbe Umsatzfrage für aktuelles Jahr und Vorjahr umgesetzt mit Qlik Set Analysis, Power BI DAX, Tableau LOD Expressions, Looker Measures, SAP Analytics Cloud Restricted Measures und Excel Pivot oder SUMIFS"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Ähnliche Analyseergebnisse entstehen durch unterschiedliche Berechnungskontexte. Einige Tools verändern die aktuelle Auswahl, andere rechnen auf einer festgelegten Detailstufe oder stützen sich auf ein semantisches Modell beziehungsweise eine Arbeitsmappe.
    </figcaption>
</figure>

## Qlik Sense: Set Analysis verändert die ausgewählte Menge

Qlik-Auswahlen definieren normalerweise die möglichen Datensätze eines Diagramms. Set Analysis erzeugt für eine Aggregation einen abweichenden Scope.

Ein vereinfachter Ausdruck für das aktuelle Jahr lautet:

```qlik
Sum({
    $<
        Produkt=,
        Jahr={"$(=Year(Today()))"}
    >
} Umsatz)
```

Die entscheidenden Bestandteile sind:

- `$` startet mit dem aktuellen Auswahlzustand
- `Produkt=` löscht für diesen Ausdruck die Auswahl im Feld Produkt
- die Kundenauswahl bleibt aktiv, weil Kunde nicht verändert wird
- `Jahr={...}` ersetzt die aktuelle Jahresauswahl durch den angegebenen Wert

Die Vorjahresvariante folgt demselben Muster:

```qlik
Sum({
    $<
        Produkt=,
        Jahr={"$(=Year(Today())-1)"}
    >
} Umsatz)
```

Set Analysis liegt sprachlich nahe an der Geschäftsregel: Behalte den aktuellen Zustand, verändere aber für eine Aggregation gezielt einzelne Felder.

Die Stärke ist die explizite Kontrolle der ausgewählten Menge. Das Risiko liegt in der Verteilung. Dieselbe Logik kann in mehreren Diagrammen, Master Measures, Variablen und Apps existieren. Wiederverwendung und Ownership sind deshalb genauso wichtig wie der Ausdruck selbst.

Ein weiterer technischer Punkt ist relevant: Set Analysis definiert die Datensatzmenge einer Aggregation und wird nicht wie gewöhnliche Zeilenlogik Datensatz für Datensatz ausgewertet. Entwickler müssen zwischen Set-Definition, Aggregations-Scope und Diagrammdimensionalität unterscheiden.

## Power BI: DAX verändert den Filterkontext

DAX formuliert die Anforderung, indem eine Measure unter einem veränderten Filterkontext ausgewertet wird.

Angenommen, `[Umsatz]` ist bereits als Basis-Measure definiert:

```dax
Umsatz :=
SUM ( Sales[Umsatz] )
```

Eine strikte Current-Year-Measure, die Produkt- und explizite Datumsfilter ignoriert, kann so aussehen:

```dax
Umsatz AJ - ohne Produkt :=
VAR AktuellesJahr = YEAR ( TODAY() )
RETURN
    CALCULATE (
        [Umsatz],
        REMOVEFILTERS ( 'Produkt' ),
        REMOVEFILTERS ( 'Datum' ),
        'Datum'[Jahr] = AktuellesJahr
    )
```

Die Vorjahres-Measure ändert lediglich das Zieljahr:

```dax
Umsatz VJ - ohne Produkt :=
VAR Vorjahr = YEAR ( TODAY() ) - 1
RETURN
    CALCULATE (
        [Umsatz],
        REMOVEFILTERS ( 'Produkt' ),
        REMOVEFILTERS ( 'Datum' ),
        'Datum'[Jahr] = Vorjahr
    )
```

Kundenfilter bleiben bestehen, weil die Measure sie nicht entfernt.

Die konkrete DAX-Formel muss dem gewünschten Zeitvertrag folgen. Eine andere Umsetzung ist nötig, wenn Monat, Geschäftsjahr oder ein ausgewähltes Vergleichsfenster erhalten bleiben sollen. Daraus folgt eine zentrale DAX-Regel:

> **Der gewünschte Filterkontext muss präzise beschrieben sein, bevor die Formel geschrieben wird.**

DAX ist stark wiederverwendbar, wenn Measures in einem kontrollierten semantischen Modell liegen. Die Governance wird schwieriger, wenn ähnliche Measures in vielen Modellen neu erstellt werden oder Report-Berechnungen eine etablierte KPI unbemerkt neu definieren.

## Tableau: Level of Detail und Filterreihenfolge wirken zusammen

Tableau besitzt kein einzelnes direktes Gegenstück zu Qlik Set Analysis. Ein vergleichbares Ergebnis kann eine FIXED- oder EXCLUDE-Level-of-Detail-Expression, Kontextfilter und Tableaus Order of Operations kombinieren.

Ein vereinfachter FIXED-Ausdruck für das aktuelle Jahr lautet:

```tableau
{ FIXED [Kunden-ID] :
    SUM(
        IF YEAR([Auftragsdatum]) = YEAR(TODAY())
        THEN [Umsatz]
        END
    )
}
```

Für das Vorjahr wird `YEAR(TODAY()) - 1` verwendet.

Damit ausgewählte Kunden berücksichtigt und Produkte ignoriert werden:

1. Der Kundenfilter wird typischerweise zum **Kontextfilter** gemacht.
2. Produkt bleibt ein normaler Dimensionsfilter.
3. Die FIXED-Expression wird nach Kontextfiltern, aber vor normalen Dimensionsfiltern ausgewertet.

Das ist nicht nur eine Syntaxfrage. Das Ergebnis hängt von Tableaus Filterreihenfolge und der Konfiguration der Arbeitsmappe ab.

Eine `EXCLUDE [Produkt]`-Expression kann Produkt aus dem Detailgrad der Ansicht entfernen. Sie löscht aber nicht automatisch jeden Produktfilter. Die Unterscheidung zwischen einer Dimension in der Visualisierung und einem Filter auf den Daten muss sichtbar bleiben.

Tableau ist besonders stark, wenn die Berechnung der visuellen Analyse dient. Governance wird schwieriger, wenn zentrale Berechnungen ausschließlich in einzelnen Arbeitsmappen statt in gesteuerten veröffentlichten Datenquellen oder gemeinsamen Modellen existieren.

## Looker: Das semantische Modell ist der primäre Vertrag

Looker definiert wiederverwendbare Measures üblicherweise in LookML und lässt die resultierende Abfrage im Data Warehouse ausführen.

Eine gefilterte Measure für das aktuelle Jahr kann so modelliert werden:

```lookml
measure: umsatz_aktuelles_jahr {
  type: sum
  sql: ${umsatz} ;;
  filters: [auftragsdatum: "this year"]
}
```

Die Vorjahresvariante kann einen entsprechenden relativen Datumsfilter verwenden:

```lookml
measure: umsatz_vorjahr {
  type: sum
  sql: ${umsatz} ;;
  filters: [auftragsdatum: "last year"]
}
```

Dabei gilt eine entscheidende Einschränkung: Der LookML-Parameter `filters` fügt einer Measure einen Filter hinzu. Er hebt nicht grundsätzlich einen externen Produktfilter auf, der bereits auf die Abfrage wirkt.

Soll ein Produktfilter im Dashboard diese Kennzahl nicht beeinflussen, kann die Lösung beispielsweise erfordern:

- den Dashboard-Filter nicht mit der betreffenden Kachel zu verbinden
- ein eigenes Explore oder eine explizit gesteuerte Measure bereitzustellen
- eine separate Derived Query oder ein anderes Modellierungsmuster zu verwenden
- den Filtervertrag in der Dashboard-Architektur ausdrücklich zu definieren

Das zeigt, warum Feature-Namen nicht als exakte Entsprechungen behandelt werden dürfen. Looker ist besonders stark, wenn die zentrale Modellierung festlegt, was Nutzer abfragen können. Nicht jede app-lokale Überschreibung einer Auswahl lässt sich dort sinnvoll als Einzeiler in einer Measure nachbauen.

## SAP Analytics Cloud: eingeschränkte Measures und Story-Design

SAP Analytics Cloud kann eine eingeschränkte Measure erzeugen, indem eine Basis-Measure mit Einschränkungen auf eine oder mehrere Dimensionen kombiniert wird.

Konzeptionell wird die Current-Year-Kennzahl so konfiguriert:

```text
Basis-Measure: Umsatz
Datumsrestriktion: Aktuelles Jahr
Kunde: aus dem aktiven Filterkontext übernehmen
Produkt: nicht Bestandteil der Measure-Restriktion
```

Eine zweite eingeschränkte Measure verwendet das Vorjahr.

Damit wird die Jahresrestriktion zentralisiert und kann innerhalb des jeweiligen Modell- oder Story-Kontexts wiederverwendet werden. Produkt nicht in die eingeschränkte Measure aufzunehmen, garantiert jedoch nicht automatisch, dass jeder Produktfilter auf Story-, Seiten- oder Diagrammebene ignoriert wird.

Der Story-Designer muss zusätzlich steuern:

- welche Story- und Seitenfilter auf das Diagramm wirken
- den Scope von Linked Analysis
- Input Controls
- Einschränkungen im Modell
- ob die Berechnung lokal in einer Story oder in einem gesteuerten Modell bereitgestellt wird

In einer SAP-zentrierten Architektur können eingeschränkte Measures einen konsistenten analytischen Vertrag bilden. Trotzdem muss zwischen Measure-Restriktion und dem umfassenderen Filterkontext der Story unterschieden werden.

## Excel: Tabellenlogik und semantische Modelllogik existieren nebeneinander

Excel muss mindestens in zwei analytische Welten getrennt werden.

### Zellformeln und Tabellen

Eine Formel kann Produkt bewusst nicht als Kriterium verwenden:

```excel
=SUMMEWENNS(
    Sales[Umsatz];
    Sales[Kunde]; $B$2;
    Sales[Jahr]; JAHR(HEUTE())
)
```

Für das Vorjahr wird das letzte Kriterium auf `JAHR(HEUTE())-1` geändert.

Das ist für eine kleine kontrollierte Analyse schnell und transparent. Gleichzeitig liegt die Logik in einer Arbeitsmappe, in der verschobene Zellen, kopierte Formeln und lokale Änderungen die Kontrolle schwächen können.

### PivotTables und Excel-Datenmodell

Eine PivotTable kann Kunde als Filter oder Zeile beibehalten und Produkt außerhalb des Filterkontexts lassen. Mit Power Pivot und dem Excel-Datenmodell können explizite DAX-Measures angelegt und in mehreren PivotTables derselben Arbeitsmappe wiederverwendet werden.

Excel ist deshalb nicht einfach eine schwächere Variante einer BI-Plattform. Es kann eingesetzt werden als:

- persönliche Tabellenkalkulation
- Pivot-basiertes Analysewerkzeug
- Power-Query-Transformationsclient
- Client für ein tabellarisches semantisches Modell
- kontrolliertes Frontend für gesteuerte Daten

Das Governance-Risiko hängt stark davon ab, welcher dieser Modi tatsächlich genutzt wird.

## Ähnliche Konzepte sind keine identischen Entsprechungen

| Anforderung | Qlik Sense | Power BI | Tableau | Looker | SAP Analytics Cloud | Excel |
| --- | --- | --- | --- | --- | --- | --- |
| Mit aktuellem Benutzerkontext starten | Aktueller Auswahlzustand `$` | Bestehender Filterkontext | View- und Filterkontext | Explore-Abfragekontext | Story-/Modellkontext | Aktive Pivot- oder Formeleingaben |
| Produkt für ein Ergebnis ignorieren | `Produkt=` in Set Analysis | `REMOVEFILTERS('Produkt')` | LOD plus bewusstes Filterreihenfolge-Design | Häufig Dashboard-/Modelldesign; gefilterte Measure allein hebt Query-Filter nicht zwingend auf | Filter-Scope und Story-Design zusätzlich zur eingeschränkten Measure | Kriterium auslassen, Pivot-Filter entfernen oder DAX verwenden |
| Aktuelles Jahr erzwingen | Set Modifier | Filter in `CALCULATE` | Bedingter Ausdruck oder Datumsfilter | Gefilterte Measure | Eingeschränkte Measure | Formel, Pivot oder DAX |
| Zentrale wiederverwendbare Logik | Master Measure / gesteuertes App-Muster | Measure im Semantic Model | Veröffentlichte Datenquelle / kontrollierte Berechnung | LookML Measure | Modell oder gesteuerte Story-Berechnung | Power-Pivot-Measure oder kontrolliertes Template |
| Zentrale konzeptionelle Schwierigkeit | Set-Scope und Auswahlen | Zeilen- versus Filterkontext | Order of Operations und LOD | Semantische Modellierung und Query-Generierung | Modellrestriktionen versus Story-Filter | Workbook-Lokalität und Formelkontrolle |

Die praktische Schlussfolgerung ist wichtig:

> **Set Analysis besitzt in anderen Tools vergleichbare Mechanismen, aber keinen universellen Eins-zu-eins-Ersatz.**

Alle Produkte können den Scope einer Aggregation kontrollieren. Sie tun dies auf unterschiedlichen Ebenen und nach unterschiedlichen Auswertungsregeln.

## Wo die Berechnungslogik definiert wird

Das dritte Schaubild vergleicht fünf mögliche Orte für analytische Logik.

<figure class="playbook-prose__figure">
    <img
        src="images/playbooks/bi-tools-img3-de.png"
        alt="Vergleich, ob Geschäfts- und Berechnungslogik bei sechs BI-Tools primär in Quelle und SQL, semantischem Modell, Datensatz, Visualisierung oder individueller Arbeitsmappe definiert wird"
        class="playbook-prose__image playbook-prose__image--diagram"
    />
    <figcaption class="playbook-prose__figure-caption">
        Hoch in der Architektur definierte Logik lässt sich normalerweise leichter wiederverwenden und steuern. Visualisierungsnahe Logik ist flexibler, wird aber häufiger dupliziert oder lokal verändert.
    </figcaption>
</figure>

Die fünf Orte haben unterschiedliche Aufgaben.

### 1. Quelle oder SQL-Schicht

Geeignet für:

- wiederverwendbare Datentransformationen
- komplexe Joins und Grain-Ausrichtung
- dauerhafte Geschäftsereignisse
- rechenintensive Vorverarbeitung
- Logik, die mehrere Konsumwerkzeuge verwenden

Risiko: Fachliche Bedeutung kann in Views oder Transformationen verborgen sein, die Report-Entwickler nicht leicht nachvollziehen können.

### 2. Semantisches Modell

Geeignet für:

- gesteuerte Measures
- Dimensionen und Hierarchien
- Beziehungen
- standardisierte Zeitlogik
- wiederverwendbare Sicherheits- und Berechnungsregeln

Dies ist normalerweise der bevorzugte Ort für unternehmensweite Kennzahlen.

### 3. Datensatz oder App-Modell

Geeignet für:

- anwendungsspezifische Ableitungen
- Mapping-Tabellen
- Resident Loads oder berechnete Tabellen
- use-case-spezifische Aggregate

Risiko: Ähnliche Datensätze können dieselbe Regel unterschiedlich umsetzen.

### 4. Visualisierung

Geeignet für:

- diagrammspezifische Quoten
- visuelle Vergleiche
- temporäre analytische Logik
- kontextsensitive Berechnungen

Risiko: Die Logik ist schwerer auffindbar, testbar und wiederverwendbar.

### 5. Individuelle Arbeitsmappe oder Bericht

Geeignet für:

- Prototypen
- Reconciliation
- temporäre Hypothesen
- persönliche Produktivität

Risiko: Eine Formel kann operativ relevant werden, ohne Ownership, Lineage oder Versionierung zu besitzen.

## Bedeutung zentralisieren, nicht jede analytische Handlung

Ein häufiger Governance-Fehler besteht darin, jede Berechnung zwingend nach SQL oder in eine zentrale semantische Schicht verschieben zu wollen.

Das nimmt wertvolle analytische Freiheit und kann einen langsamen zentralen Engpass erzeugen.

Besser ist die Trennung zwischen **fachlicher Bedeutung** und **analytischer Darstellung**.

Zentral definieren und steuern:

- Umsatzdefinition
- gültige Auftragspopulation
- Währungsumrechnung
- Stornologik
- Kunden- und Produktschlüssel
- Geschäftskalender
- freigegebene Vorjahresmethode
- Sicherheits- und Datenschutzregeln

Kontrollierte Flexibilität erlauben für:

- Diagramm-Layout
- Sortierung
- temporäre Vergleiche
- lokale Szenarien
- explorative Segmente
- visuelle Referenzlinien
- nicht zertifizierte Prototypen

```flowchart
Gesteuerte Quellereignisse
Zertifizierte semantische Measures
Tool-spezifische Umsetzung
Lokale analytische Erweiterung
Nutzungskennzeichnung und Monitoring
```

Je tiefer eine Berechnung umgesetzt wird, desto deutlicher sollte ihr Status gekennzeichnet sein.

Geeignete Status sind beispielsweise:

- Certified
- Governed derivative
- Local analysis
- Experimental
- Deprecated

## Ein praktischer Rahmen für die Tool-Auswahl

Eine BI-Plattform sollte nicht allein anhand einer Feature-Matrix ausgewählt werden. Entscheidend ist das gewünschte Betriebsmodell.

### Daten und Architektur

- Wird importiert, live abgefragt oder beides kombiniert?
- Existiert bereits ein gesteuertes Warehouse oder Lakehouse?
- Wird ein zentraler Semantic Layer benötigt?
- Müssen Berechnungen über mehrere Tools geteilt werden?
- Ist Embedded Analytics eine Kernanforderung?

### Nutzer und Arbeitsweise

- Sind die Nutzer Dashboard-Konsumenten, Analysten, Entwickler oder Planer?
- Ist assoziative Exploration wichtiger als stark geführtes Reporting?
- Ist Code-first-Modellierung akzeptabel?
- Wie viel lokale Freiheit sollen Fachanwender erhalten?
- Muss Excel ein offiziell unterstützter Konsumkanal bleiben?

### Governance

- Wo werden Kennzahlendefinitionen gespeichert?
- Kann eine Measure in mehreren Reports wiederverwendet werden?
- Werden Änderungen versioniert und geprüft?
- Lassen sich lokale Berechnungen identifizieren?
- Sind Nutzung und Lineage sichtbar?
- Können zertifizierte und experimentelle Inhalte klar nebeneinander existieren?

### Betrieb

- Wer entwickelt, testet und veröffentlicht Inhalte?
- Wie werden Umgebungen getrennt?
- Wie werden wiederverwendbare Artefakte befördert?
- Welche Fähigkeiten sind intern verfügbar?
- Wie werden Performance und Nutzung überwacht?

Ein Produkt kann technisch sehr leistungsfähig sein und organisatorisch trotzdem schlecht passen.

## Die zentrale Erkenntnis

Qlik Set Analysis, DAX, Tableau LOD Expressions, LookML Measures, eingeschränkte SAP-Measures und Excel-Formeln beantworten ein verwandtes Problem:

> **Welche Daten soll diese Berechnung im aktuellen Analysekontext berücksichtigen?**

Sie beantworten diese Frage nicht auf dieselbe Weise.

Qlik verändert eine ausgewählte Menge. Power BI verändert den Filterkontext. Tableau kombiniert den Detailgrad mit einer ausdrücklichen Filterreihenfolge. Looker formuliert Geschäftslogik in einem gesteuerten semantischen Modell und erzeugt daraus SQL. SAP Analytics Cloud verbindet Modell- und Story-Kontexte. Excel reicht von lokalen Zellformeln bis zu tabellarischen DAX-Measures.

Eine gute Reporting-Architektur versucht deshalb nicht, alle Tools identisch funktionieren zu lassen.

Sie definiert den fachlichen Vertrag einmal, implementiert ihn passend zur jeweiligen Umgebung und prüft, dass alle Implementierungen dasselbe gesteuerte Ergebnis liefern.

> **Trusted Reporting entsteht, wenn verschiedene Tools unterschiedliche Syntax verwenden dürfen, aber keine unterschiedlichen Bedeutungen erfinden.**

## Verwandte Playbooks

- [KPI-Definition, Ownership und Versionierung](/playbooks/define-kpi) — wie aus einer Kennzahl ein gesteuerter und historisch reproduzierbarer Vertrag wird
- [KPI & Metric Governance](/playbooks/kpi-metric-governance) — warum dynamische Formeln und lokale Dimensionen mehrere Versionen derselben Zahl erzeugen können
- [One App Cannot Answer Every Question](/playbooks/one-app) — wie fokussierte Apps gemeinsame gesteuerte Fakten und Measures wiederverwenden
- [The Missing Pieces — Trusted Metrics](/playbooks/missing-pieces-trusted-metrics) — warum eine technisch verfügbare Kennzahl nicht automatisch eine vertrauenswürdige Unternehmens-KPI ist

## Weiterführende Ressourcen

- [Qlik Help — Set Analysis und Set Expressions](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/ChartFunctions/SetAnalysis/set-analysis-expressions.htm)
- [Qlik Help — Set Modifiers](https://help.qlik.com/en-US/sense/May2026/Subsystems/Hub/Content/Sense_Hub/ChartFunctions/SetAnalysis/set-modifiers.htm)
- [Microsoft Learn — CALCULATE](https://learn.microsoft.com/de-de/dax/calculate-function-dax)
- [Microsoft Learn — REMOVEFILTERS](https://learn.microsoft.com/de-de/dax/removefilters-function-dax)
- [Tableau Help — Level-of-Detail-Expressions](https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields_lod.htm)
- [Tableau Help — Filter und Level-of-Detail-Expressions](https://help.tableau.com/current/pro/desktop/en-us/calculations_calculatedfields_lod_filters.htm)
- [Google Cloud — Looker Measure Filters](https://docs.cloud.google.com/looker/docs/reference/param-field-filters)
- [Google Cloud — Looker Measure Types](https://docs.cloud.google.com/looker/docs/reference/param-measure-types)
- [SAP Help — Eingeschränkte Measures erstellen](https://help.sap.com/docs/SAP_ANALYTICS_CLOUD/00f68c2e08b941f081002fd3691d86a7/df0e123a79624e68a0735b557ef52081.html)
- [Microsoft Support — SUMMEWENNS](https://support.microsoft.com/de-de/excel/sumifs-function-c9e748f5-7ea7-455d-9406-611cebce642b)
- [Microsoft Support — Measures in Power Pivot](https://support.microsoft.com/de-de/excel/measures-in-power-pivot)
